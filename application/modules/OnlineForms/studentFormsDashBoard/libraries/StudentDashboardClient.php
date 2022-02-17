<?php
/**
 * This is the client, makes server call for all types of services
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class StudentDashboardClient
{
	var $CI = '';
	var $cacheLib;
	static $instance;
	/**
	 *
	 * Return new instance if already not exists
	 *
	 * @access	public
	 * @return	object
	 */
	public static function  getInstance () {
		if(!self::$instance) {
			self::$instance = new StudentDashboardClient();
		}
		return self::$instance;
	}
	/**
	 * this method sets the server url and other parameters required for xml rpc call
	 *
	 * @param none
	 * @return void
	 */
	private function _init()
	{
		$this->CI = & get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(STUDENT_DASHBOARD_SERVER_URL, STUDENT_DASHBOARD_SERVER_PORT);
	}
	/**
	 *
	 * make server calls
	 *
	 * @access	private
	 * @return	array
	 */
	private function _makeServerCall($key,$request,$methodName) {
		if($this->cacheLib->get($key)=='ERROR_READING_CACHE'){
			$this->CI->xmlrpc->method($methodName);
			$this->CI->xmlrpc->request($request);
			if ( ! $this->CI->xmlrpc->send_request()){
				return  $this->CI->xmlrpc->display_error();
			} else {

				$response=$this->CI->xmlrpc->display_response();
				if($cache == 1)
				{
					$this->cacheLib->store($key,$response);
				}
				return $response;
			}
		}else {
			return  $this->cacheLib->get($key);
		}
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function getTheIdsOfInstituteHavingOF($key_word,$category_id,$department)
	{
		$this->_init();
		$key=md5('getTheIdsOfInstituteHavingOF');
		$request = array ($key_word,$category_id,$department,'struct');
		return $this->_makeServerCall($key,$request,'getTheIdsOfInstituteHavingOF');
	}
	/**
	 * method returns a list of array having all the details listed
	 *
	 * @param none
	 * @return void
	 */
	public function renderInstituteListWithDetails($of_institute_ids_array, $department = '') {
		// Didn't put this method to any server side, because this is only logical implemention
		// if some one wants to use it, just laod this library and call
		//load listing client
		$this->_init();
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
                $instituteBuilder = new InstituteBuilder();
                $instituteRepository = $instituteBuilder->getInstituteRepository();

		
		$institutes = array();
		
		$of_institute_ids_array = array_unique($of_institute_ids_array);
		// populate all the required information related to an instiute ans it's courses
		//foreach ($of_institute_ids_array as $institute_id) {
			// get the course id for an institute
			$course_ids_array = json_decode($this->getCourseIdForInstituteId($of_institute_ids_array,$department),true);
			if(is_array($course_ids_array) && count($course_ids_array) > 0) {
				$institutes = $institutes + $course_ids_array;
			//}
		}
	
		$institutes = $instituteRepository->findWithCourses($institutes, array('media'), array('eligibility'));
		return $institutes;
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function returnOfInstitutesOfferandOtherDetails($institute_ids, $department='')
	{
		$this->_init();
		$key=md5('returnOfInstitutesOfferandOtherDetails');
		$request = array(json_encode($institute_ids),json_encode($department));
		return $this->_makeServerCall($key,$request,'returnOfInstitutesOfferandOtherDetails');
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function getCourseIdForInstituteId($institute_id_array,$department='')
	{
		$this->_init();
		$key=md5('getCourseIdForInstituteId');
		$request = array(json_encode($institute_id_array),json_encode($department));
		return $this->_makeServerCall($key,$request,'getCourseIdForInstituteId');
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function checkDocumentTitle($doc_title,$user_id)
	{
		$this->_init();
		$key=md5('checkDocumentTitle');
		$request = array(json_encode($doc_title),json_encode($user_id));
		return $this->_makeServerCall($key,$request,'checkDocumentTitle');
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function insertDocument($document_title,$document_saved_path,$instituteId = NULL,$status="live",$user_id,$doc_type)
	{
		$this->_init();
		$key=md5('insertDocument');
		$request = array(json_encode($document_title),json_encode($document_saved_path),json_encode($instituteId),json_encode($status),json_encode($user_id),json_encode($doc_type));
		return $this->_makeServerCall($key,$request,'insertDocument');
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function getDocumentDetails($type="all",$userid)
	{
		$this->_init();
		$key=md5('getDocumentDetails');
		$request = array(json_encode($type),json_encode($userid));
		return $this->_makeServerCall($key,$request,'getDocumentDetails');
	}
	/**
	 * returns an array of ids the institutes having online forms
	 *
	 * @param
	 * @return array
	 */
	public function updateDocumentDetails($column_name,$column_value,$id)
	{
		$this->_init();
		$key=md5('updateDocumentDetails');
		$request = array(json_encode($column_name),json_encode($column_value),json_encode($id));
		return $this->_makeServerCall($key,$request,'updateDocumentDetails');
	}
        /**
	 * It handles pagination on the online form institute page
	 *
	 * @param
	 * @return array
	 */
	function handlePagination($instituteIds,$total_pages,$current_page,$limit_per_page,$department='') {
		$offset = ($current_page-1)*$limit_per_page;
		$upper_limit = $current_page*$limit_per_page-1;
		$count_total_result = count($instituteIds);
		if($upper_limit>=$count_total_result) {
			$upper_limit = $count_total_result;
		}
		for($index=0;$index<$count_total_result;$index++) {
			$split = $instituteIds[$index];
			$split_array = explode("_",$split);
			$split_array1[] = $split_array[0];
			if($index<$offset) {
				continue;
			}
			if($index>=$offset && $index<=$upper_limit) {
				$required_array[] = $instituteIds[$index];
			}
		}
		// api return each and every details for a list of institute
		if(!empty($required_array) && is_array($required_array) && count($required_array)>0){
			//load the required library
			$instituteList = $this->renderInstituteListWithDetails($split_array1,$department);
			foreach($instituteList as $key=>$value) {
				if(in_array($key,$required_array)) {
					$arraya[$key] = $value;
				}
			}
			$data['instituteList'] = $arraya;
			$data['institute_features'] = json_decode($this->returnOfInstitutesOfferandOtherDetails($split_array1, $department),true);
			return $data;
		}
	}

	function prepareBeaconTrackData($type){
		$beaconTrackData = array(
			'pageIdentifier' 	=> 'studentFormsDashBoardPage',
			'pageEntityId'	=> 0,
			'extraData'			=> array(
				'countryId' => 2,
				'type'		=> $type
				)
			);
		return $beaconTrackData;
	}
}
?>
