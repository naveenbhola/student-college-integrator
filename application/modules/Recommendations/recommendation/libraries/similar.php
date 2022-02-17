<?php 

class Similar
{
	private $CI;
	private $matchCourseFactory;
	private $searchServer;
	
	function __construct()
	{
		$this->CI = & get_instance();

		$this->CI->load->library('recommendation/matchCourse/MatchCourseFactory');
		$this->matchCourseFactory = new MatchCourseFactory;
		
		$this->CI->load->library('search/SearchServer/SolrServer');
		$this->searchServer = new SolrServer;
	}
	
	function getSimilarCourses($courseIds, $numResults = 10, $exclusionList = array())
	{
        if(!is_array($courseIds) || count($courseIds) == 0) {
			return array();
		}

		$numResults = intval($numResults);
		if(!$numResults) {
			$numResults = 10;
		}

		$matchCourses = $this->matchCourseFactory->getMatchCourses($courseIds);
	
		$similarResults = array();
		foreach($courseIds as $courseId) {
			$matchCourse = $matchCourses[$courseId];
			if(empty($matchCourse)){
				continue;
			}
			$similarResultsForCourse = $this->getSimilarResultsForCourse($matchCourse, $numResults, $exclusionList);
			if(count($similarResultsForCourse) > 0) {
				$similarResults = array_merge($similarResults, $similarResultsForCourse);
			}
		}
		
		usort($similarResults, function($s1, $s2) { return $s2['viewCount'] - $s1['viewCount']; });
		$finalResults = array();
		foreach($similarResults as $result) {
			if(!array_key_exists($result['instituteId'], $finalResults)) {
				$finalResults[$result['instituteId']] = array(
					'courseId' => $result['courseId'],
					'instituteId' => $result['instituteId'],
				);
			}
			if(count($finalResults) >= $numResults) {
				break;
			}
		}
		
		return array_values($finalResults);
	}
	
	private function getSimilarResultsForCourse(MatchCourse $matchCourse, $numResults, $exclusionList)
	{
		$requestParams = array();
			
		$requestParams[] = "fq=nl_course_education_type_id:".intval($matchCourse->getEducationType());
		$requestParams[] = "fq=nl_course_delivery_method_id:".intval($matchCourse->getDeliveryMethod());
		$requestParams[] = "fq=nl_course_level_id:".intval($matchCourse->getCourseLevel());
		$requestParams[] = "fq=nl_course_credential_id:".intval($matchCourse->getCredential());
		$requestParams[] = "fq=nl_base_course_id:".intval($matchCourse->getBaseCourse());
		
		$hierarchies = $matchCourse->getHierarchies();
		$hparams = array();
		foreach($hierarchies as $hierarchy) {
			$hparams[] = "(nl_stream_id:".$hierarchy[0]."%20AND%20nl_substream_id:".$hierarchy[1].")";
		}
		$requestParams[] = "fq=".implode("%20OR%20",$hparams);

                $cities = $matchCourse->getCities();
                $cparams = array();
                foreach($cities as $city) {
                        $cparams[] = "(nl_course_city_id:".$city.")";
                }
                $requestParams[] = "fq=".implode("%20OR%20",$cparams);
		
		//$instituteExclusion[] = "nl_institute_id:".intval($matchCourse->getInstituteId());
        $instituteExclusion[] = intval($matchCourse->getInstituteId());
		if(is_array($exclusionList)) {
			foreach($exclusionList as $ex) {
				//$instituteExclusion[] = "nl_institute_id:".intval($ex);
				$instituteExclusion[] = intval($ex);
			}
		}
		
		//$requestParams[] = "fq=-(".implode("%20OR%20", $instituteExclusion).")";
		$requestParams[] = "fq=-nl_institute_id:(".implode("%20", $instituteExclusion).")";
		
		$requestParams[] = "fl=nl_course_id,nl_institute_id,nl_course_view_count_year";
		$requestParams[] = "rows=".$numResults;
		
		$requestParams[] = "group=true";
		$requestParams[] = "group.field=nl_institute_id";
		$requestParams[] = "sort=nl_course_view_count_year%20desc";
		
		$request = $this->searchServer->getSolrUrl('course', 'select');
		$request .= 'q=*%3A*&wt=phps&';
		$request .= implode('&', $requestParams);
       
 
		$response = unserialize($this->searchServer->curl($request));
		
		$similarResults = array();
		if($response['grouped']['nl_institute_id']['matches'] > 0) {
			foreach($response['grouped']['nl_institute_id']['groups'] as $group) {
				$similarResults[] = array(
					'courseId' => $group['doclist']['docs'][0]['nl_course_id'],
					'instituteId' => $group['doclist']['docs'][0]['nl_institute_id'],
					'viewCount' => $group['doclist']['docs'][0]['nl_course_view_count_year']
				);
			}
		}
		
		return $similarResults;
	}
}
