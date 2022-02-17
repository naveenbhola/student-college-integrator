<?php

class MatchCourseFactory
{
	private $CI;
	private $matchCourseModel;
	
	private $courseMatchingParams = NULL;
	
	function __construct()
	{
		$this->CI = & get_instance();
		$this->CI->load->model('recommendation/matchcourse_model');
    	$this->matchCourseModel = new MatchCourse_Model;
		
		$this->CI->load->library('recommendation/matchCourse/MatchCourse');

		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$courseBuilder = new CourseBuilder();
		$this->courseRepo = $courseBuilder->getCourseRepository();
	}
	
	/**
	 * We are going to match a large number of courses
	 * So it's better to load matching params for all the courses in the system at once
	 */ 
	public function setBulkOp()
	{
		$this->courseMatchingParams = $this->matchCourseModel->getCourseMatchingParameters();
	}
	
	
    public function getMatchCourse($courseId)
    {
        $matchCourses = $this->getMatchCourses(array($courseId));
        return $matchCourses[$courseId];
    }

    public function getMatchCourses($courseIds = array())
    {
		if(!is_array($courseIds) || count($courseIds) == 0) {
			return array();
		}
		
		$courseMatchingParams = $this->courseMatchingParams;
		if(!$courseMatchingParams) {
			// optimizing use cache instead of query when only one courseId
			if(count($courseIds) == 1){
				$courseId = reset($courseIds);
				$courseObj = $this->courseRepo->find($courseId, array('location'));
				if(!empty($courseObj) && $courseObj->getId() != ""){
					$courseMatchingParams[$courseId]['institute_id'] = $courseObj->getInstituteId();
					$courseMatchingParams[$courseId]['education_type'] = $courseObj->getEducationType()->getId();
					$courseMatchingParams[$courseId]['delivery_method'] = $courseObj->getDeliveryMethod()->getId();
					$typeInformation = $courseObj->getCourseTypeInformation();
					$courseMatchingParams[$courseId]['credential'] = $typeInformation['entry_course']->getCredential()->getId();
					$courseMatchingParams[$courseId]['course_level'] = $typeInformation['entry_course']->getCourseLevel()->getId();
					$courseMatchingParams[$courseId]['base_course'] = $typeInformation['entry_course']->getBaseCourse();

					$courseMatchingParams[$courseId]['hierarchies'] = array();
					$hierarchies = $typeInformation['entry_course']->getHierarchies();
					foreach ($hierarchies as $row) {
						$courseMatchingParams[$courseId]['hierarchies'][$row['stream_id'].':'.$row['substream_id']] = array($row['stream_id'],$row['substream_id']);
					}

					$courseMatchingParams[$courseId]['cities'] = array();
					$locations = $courseObj->getLocations();
					foreach ($locations as $row) {
						$courseMatchingParams[$courseId]['cities'][] = $row->getCityId();
					}
					$courseMatchingParams[$courseId]['cities'] = array_values(array_unique($courseMatchingParams[$courseId]['cities']));
				}
			}
			else{
				$courseMatchingParams = $this->matchCourseModel->getCourseMatchingParameters($courseIds);
			}
		}
		
        $matchCourses = array();
        foreach($courseIds as $courseId) {
			$matchingParams = $courseMatchingParams[$courseId];
			if(empty($matchingParams)){
				continue;
			}
			
            $matchCourses[$courseId] = new MatchCourse($courseId,
                $matchingParams['institute_id'],
				$matchingParams['education_type'],
				$matchingParams['delivery_method'],
				$matchingParams['course_level'],
				$matchingParams['credential'],
				$matchingParams['base_course'],
				$matchingParams['hierarchies'],
				$matchingParams['cities']
            );
        }

        return $matchCourses;
    }
}