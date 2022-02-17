<?php

class msearchLib {

	private $_messageboardproxy = NULL;
	private $_CI = NULL;
	public static $number_of_result = 15;
	public static $course_type_array = array(
	''=>'Select Mode',
	'full-time'=>'Full Time',
	'correspondence'=>'Correspondence',
	'part-time'=>'Part Time',
	'e-learning'=>'E Learning',
	'classroom'=>'Classroom',
	'test-series'=>'Test Series'
	);
	public static $course_level_array = array(
	''=>'Select Course Level',
	'certification'=>'Certification',
	'under-graduate-degree'=>'Under Graduate Degree',
	'diploma'=>'Diploma',
	'post-graduate-degree'=>'Post Graduate Degree',
	'exam-preparation'=>'Exam Preparation',
	'post-graduate-diploma'=>'Post Graduate Diploma',
	'others'=>'Others',
	'vocational'=>'Vocational'			
	);

	public static $shiksha_top_searches = array(
	"/list-mba-colleges-institutes-in-India.html"=>"MBA colleges and institutes in India",
	"/list-MBA-Correspondence-colleges-institutes-in-India.html"=>"MBA Correspondence colleges and institutes in India",	
	"/list-rhce-colleges-institutes-in-delhi.html"=>"RHCE colleges and institutes in delhi",
	"/list-MBA-Part-time-colleges-institutes-in-India.html"=>"MBA Part time colleges and institutes in India",
	"/list-Executive-MBA-colleges-institutes-in-India.html"=>"Executive MBA colleges and institutes in India",
	"/list-MCA-colleges-institutes-in-India.html"=>"MCA colleges and institutes in India",
	"/list-BBA-colleges-institutes-in-India.html"=>"BBA colleges and institutes in India",
	"/list-BCA-colleges-institutes-in-India.html"=>"BCA colleges and institutes in India",
	"/list-mba-colleges-institutes-in-delhi.html"=>"MBA colleges and institutes in delhi",
	"/list-mba-colleges-institutes-in-pune.html"=>"MBA colleges and institutes in pune",
	"/list-mtech-colleges-institutes-in-India.html"=>"MTECH colleges and institutes in India",
	"/list-Pilot-Training-colleges-institutes-in-India.html"=>"Pilot Training colleges and institutes in India",
	"/list-mba-colleges-institutes-in-mumbai.html"=>"MBA colleges and institutes in mumbai",
	"/list-mba-colleges-institutes-in-bangalore.html"=>"MBA colleges and institutes in bangalore",
	"/list-mba-colleges-institutes-in-hyderabad.html"=>"MBA colleges and institutes in hyderabad",
	"/list-m-tech-colleges-institutes-in-India.html"=>"MTECH colleges and institutes in India",
	"/list-mba-colleges-institutes-in-chennai.html"=>"MBA colleges and institutes in chennai",
	"/list-mca-colleges-institutes-in-Bangalore.html"=>"MCA colleges and institutes in Bangalore",
	"/list-mca-colleges-institutes-in-Delhi-NCR.html"=>"MCA colleges and institutes in Delhi NCR",
	"/list-mca-colleges-institutes-in-delhi.html"=>"MCA colleges and institutes in delhi",
	"/list-mba-colleges-institutes-in-kolkata.html"=>"MBA colleges and institutes in kolkata",
	"/list-animation-colleges-institutes-in-India.html"=>"Animation colleges and institutes in India",
	"/list-mca-colleges-institutes-in-Pune.html"=>"MCA colleges and institutes in Pune",
	"/list-bca-colleges-institutes-in-delhi.html"=>"BCA colleges and institutes in delhi",
	"/list-bba-colleges-institutes-in-delhi.html"=>"BBA colleges and institutes in delhi",
	"/list-clinical-research-colleges-institutes-in-India.html"=>"Clinical research colleges and institutes in India",
	"/list-engineering-colleges-institutes-in-India.html"=>"Engineering colleges and institutes in India",
	"/list-bca-colleges-institutes-in-Delhi-NCR.html"=>"BCA colleges and institutes in Delhi NCR",
	"/list-mca-colleges-institutes-in-Hyderabad.html"=>"MCA colleges and institutes in Hyderabad",
	"/list-mca-colleges-institutes-in-All-Mumbai.html"=>"MCA colleges and institutes in All Mumbai",
	"/list-mca-colleges-institutes-in-Chennai.html"=>"MCA colleges and institutes in Chennai",
	"/list-MBA-Correspondence-colleges-institutes-in-pune.html"=>"MBA Correspondence colleges and institutes in pune",
	"/list-mba-colleges-institutes-in-india.html"=>"MBA colleges and institutes in india",
	"/list-MBA-Correspondence-colleges-institutes-in-delhi.html"=>"MBA Correspondence colleges and institutes in delhi",
	"/list-mba-colleges-institutes-in-ahmedabad.html"=>"MBA colleges and institutes in ahmedabad",
	"/list-mca-colleges-institutes-in-mumbai.html"=>"MBA colleges and institutes in mumbai",
	"/list-animation-colleges-institutes-in-delhi.html"=>"Animation colleges and institutes in delhi",
	"/list-mca-colleges-institutes-in-Kolkata.html"=>"MCA colleges and institutes in Kolkata",
	"/list-b-tech-colleges-institutes-in-India.html"=>"BTECH colleges and institutes in India",
	"/list-Spoken-English-colleges-institutes-in-India.html"=>"Spoken English colleges and institutes in India",
	"/list-bba-colleges-institutes-in-pune.html"=>"BBA colleges and institutes in pune",
	"/list-mba-colleges-institutes-in-indore.html"=>"MBA colleges and institutes in indore",
	"/list-bca-colleges-institutes-in-mumbai.html"=>"BCA colleges and institutes in mumbai",
	"/list-animation-colleges-institutes-in-mumbai.html"=>"Animation colleges and institutes in mumbai",
	"/list-mba-colleges-institutes-in-jaipur.html"=>"MBA colleges and institutes in jaipur",
	"/list-MBA-Correspondence-colleges-institutes-in-mumbai.html"=>"MBA Correspondence colleges and institutes in mumbai",
	"/list-sap-colleges-institutes-in-India.html"=>"SAP colleges and institutes in India",
	"/list-phd-colleges-institutes-in-India.html"=>"PHD colleges and institutes in India",
	"/list-bca-colleges-institutes-in-Bangalore.html"=>"BCA colleges and institutes in Bangalore",
	"/list-animation-colleges-institutes-in-pune.html"=>"Animation colleges and institutes in pune",
	"/list-bca-colleges-institutes-in-All-Mumbai.html"=>"BCA colleges and institutes in All Mumbai",
	"/list-bca-colleges-institutes-in-pune.html"=>"BCA colleges and institutes in pune",
	"/list-ccna-colleges-institutes-in-India.html"=>"CCNA colleges and institutes in India",
	"/list-engineering-colleges-institutes-in-pune.html"=>"Engineering colleges and institutes in pune",
	"/list-where-should-I-do-MBA-from-colleges-institutes-in-India.html"=>"Where should I do MBA from colleges and institutes in India",
	"/list-engineering-colleges-institutes-in-delhi.html"=>"Engineering colleges and institutes in delhi",
	"/list-MBA-Correspondence-colleges-institutes-in-bangalore.html"=>"MBA Correspondence colleges and institutes in bangalore",
	"/list-mba-colleges-institutes-in-canada.html"=>"MBA colleges and institutes in canada",
	"/list-MBA-colleges-in-all.html"=>"MBA colleges in all",
	"/list-mba-colleges-institutes-in-coimbatore.html"=>"MBA colleges and institutes in coimbatore",
	"/list-hotel-management-colleges-institutes-in-India.html"=>"Hotel management colleges and institutes in India"

	);

	private $location_facets = array();
	private $course_type_facets = array();
	private $course_level_facets = array();
	private $url_params = array();

	public function __construct($wurfl)
	{
		$this->_CI = & get_instance();
		$this->_CI->load->library('ANA/messageBoardProxy');
		$this->_messageboardproxy = new messageBoardProxy($wurfl);
	}

	public function renderView($view) {

		$view_file = "";

		if($this->_messageboardproxy->getDeviceObj()) {

			$view_file = "smartphones/$view";
		} else {

			$view_file =  "lowScreen/$view";
		}

		return   $view_file;
	}

	public function getModeList() {

		$course_type = self::$course_type_array;
		return $course_type;
	}

	public function getCourseLevelList() {

		$course_level = self::$course_level_array;
		return $course_level;
	}

	public function getLocationList() {

		$locations = array();
		$locations[''] = 'All Cities';

		$this->_CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
		$locationRepository = $locationBuilder->getLocationRepository();
		$cityList = $locationRepository->getCitiesByMultipleTiers(array(1),2);
		foreach ($cityList[1] as $city_object) {
			$locations[$city_object->getId()] = $city_object->getName();
		}
		$locations_json = base64_encode(json_encode($locations));
		setcookie('msearch_locations',$locations_json,0,'/',COOKIEDOMAIN);

		return $locations;
	}

	public function makeRequestInfo($courses) {
		$addReqInfoVars = array();
		foreach($courses as $c){
			if($c->isPaid()){
				$institute = $c->getInstitute();
				foreach($c->getAllLocations() as $course_location){
					$locality_name = $course_location->getLocality()->getName();
					if($locality_name !='') $locality_name = ' |'.$course_location->getLocality()->getName();
					$addReqInfoVars[$c->getName().' | '.$course_location->getCity()->getName().$locality_name]=$c->getId()."*".html_escape($institute->getName())."*".$c->getUrl()."*".$course_location->getCity()->getId()."*".$course_location->getLocality()->getId();
				}
			}
		}
		$addReqInfoVars=serialize($addReqInfoVars);
		$addReqInfoVars=base64_encode($addReqInfoVars);

		return 	$addReqInfoVars;


	}

	public function getAllCourses($results) {
		$courses = array();
		foreach($results as $cat_id=>$value) {
			$courses = array_merge($courses,$value);
		}

		return $courses;
			
	}

	public function makeSearchURL($base_url,$query_params) {
		$query_array = array();
		foreach($query_params as $key=>$value) {
			$query_array[] = $key."=".$value;
		}

		return urlencode($base_url."?".implode('&',$query_array));
	}

	public function findMaxOffset($current_offset,$limit,$total_result) {
		if($total_result > $limit) {
			if($total_result%$limit == 0) {
				return ($total_result - $limit);
			} else {
				$reminder = ($total_result%$limit);
				return ($total_result - $reminder);
			}
		} else {
			return 0;
		}
	}

	public function getSearchTrackURL($searchurlparams) {
		$url = urldecode($this->makeSearchURL('/searchmatrix/SearchMatrix/trackSearchQuery',$searchurlparams));
		return $url;
			
	}

	public function getPageId($offset=0) {
		return (intval($offset/self::$number_of_result) +1);
	}

	public function validateUrlParams($urlParams) {

		$num_result = self::$number_of_result;

		unset($urlParams['min_duration']);
		unset($urlParams['max_duration']);
		unset($urlParams['show_featured_results']);
		unset($urlParams['show_banner_results']);

		if(($urlParams['start']%$num_result >0 ) || $urlParams['start'] < 0 || ($urlParams['start']>0 && $urlParams['start'] < $num_result)) {
			$urlParams['start'] = 0;
		}

		// validate keyword for strip tags
		//$urlParams['keyword'] = strip_tags($urlParams['keyword']);
			
		return $urlParams;
	}

	public function getNextRemainingResults($offset,$max_count) {

		if( ($offset + 2*(self::$number_of_result) ) < $max_count) {
			return self::$number_of_result;
		} else {
			return ($max_count - ($offset+self::$number_of_result));
		}
	}

	public function setDefaultSearchParams($urlParams) {

		$urlParams['start'] = 0;
		$urlParams['start']= 0;
		$urlParams['institute_rows']= -1;
		$urlParams['content_rows']= 0;
		$urlParams['country_id'] = '';
		$urlParams['zone_id'] = '';
		$urlParams['locality_id'] = '';
		$urlParams['search_type'] = 'institute';
		$urlParams['search_data_type'] = 'institute';
		$urlParams['sort_type'] ='';
		$urlParams['utm_campaign'] ='';
		$urlParams['utm_medium']= '';
		$urlParams['utm_source'] ='';
		$urlParams['from_page'] ='mobilesearchhome';
		$urlParams['show_featured_results'] = 0;
		$urlParams['show_sponsored_results'] = 1;
		$urlParams['show_banner_results'] = 0;

		return $urlParams;
	}

	public function is_robot() {

		if (isset($_SERVER['HTTP_USER_AGENT']) && preg_match('/bot|crawl|slurp|spider/i', $_SERVER['HTTP_USER_AGENT'])) {
			return TRUE;
		} else {
			return FALSE;
		}


	}

	public function parseFacets($facets) {

		if(!is_array($facets) || count($facets) == 0) {
			return array();
		}
		$temp_array = $this->url_params;
		$temp_array['start'] = 0;
		foreach ($facets['location'] as $key=>$location_facet) {
			$this->location_facets[$key]['name'] = $location_facet['name'];                      
			$this->location_facets[$key]['value'] = $location_facet['value'];
			$temp_array['country_id'] = $key;
			$temp_array['city_id'] = ""; 
                        if($key == 1) {
				$temp_array['country_id'] = "";
                        }
			$this->location_facets[$key]['url'] = urldecode($this->makeSearchURL(SHIKSHA_HOME."/search/index", $temp_array));
			foreach ($location_facet['cities'] as $key1=>$facet) {
				$this->location_facets[$key]['cities'][$key1]['name'] = $facet['name'];
				$this->location_facets[$key]['cities'][$key1]['value'] = $facet['value'];
				$temp_array['city_id'] = $key1;
				$temp_array['country_id'] = "";
				$this->location_facets[$key]['cities'][$key1]['url'] = urldecode($this->makeSearchURL(SHIKSHA_HOME."/search/index", $temp_array));
			}
		}
		foreach ($facets['course_type']['others'] as $key=>$course_type_facet) {
			$course_type_facet['url'] = preg_replace('/^(.*)start=[0-9]+&(.*)$/', "$1start=0&$2", $course_type_facet['url']);
			$this->course_type_facets[$key] = $course_type_facet;
		}
		
		foreach ($facets['course_level']['others'] as $key=> $course_level_facet) {
			$course_level_facet['url'] = preg_replace('/^(.*)start=[0-9]+&(.*)$/', "$1start=0&$2", $course_level_facet['url']);
			$this->course_level_facets[$key] = $course_level_facet;
		}
	}

	public function getLocationFacets() {
		return $this->location_facets;
	}

	public function getCourselevelFacets() {
		return $this->course_level_facets;
	}

	public function getCoursetypeFacets() {
		return $this->course_type_facets;
	}

	public function getFiltersPickedInfo($data) {

		//_P($data);

		$filters_array = array();

		if(count($data) == 0) {
			return $filters_array;
		}

		$posted_city_id = $this->_CI->input->get_post('city_id',true);
		$posted_city_id = trim($posted_city_id);
		if(!empty($posted_city_id) && array_key_exists('course_city_id', $data) && !in_array($posted_city_id, $data['course_city_id'])) {
			$filters_array['city_id'] = "style='text-decoration:line-through'";
		}

		$posted_course_type = $this->_CI->input->get_post('course_type',true);
		$posted_course_type = trim($posted_course_type);
		if(!empty($posted_course_type) && array_key_exists('course_type_cluster', $data) && !in_array($posted_course_type, $data['course_type_cluster'])) {
			$filters_array['course_type'] = "style='text-decoration:line-through'";
		}

		$posted_course_level = $this->_CI->input->get_post('course_level',true);
		$posted_course_level = trim($posted_course_level);
		if(!empty($posted_course_level) && array_key_exists('course_level_cluster', $data) && !in_array($posted_course_level, $data['course_level_cluster'])) {
			$filters_array['course_level'] = "style='text-decoration:line-through'";
		}

		return $filters_array;
	}
	
	public function __set($name,$value) {
		$this->$name = $value;
	}
	
	public function getUrlParams() {
		return $this->url_params;
	}
}
