<?php
/**
 * MultipleMarketingPage Application Controller
 *
 * This calss serves as a multiple marketing pages client
 *
 * @package Enterprise
 */

class MultipleMarketingPageClient {

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
			self::$instance = new MultipleMarketingPageClient();
		}
		return self::$instance;
	}
	/**
	 *
	 * Loads required library
	 *
	 * @access	private
	 * @return	void
	 */
	private function init()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(NEW_MARKETING_PAGE_SERVER_URL,NEW_MARKETING_PAGE_SERVER_PORT);
	}

	/**
	 *
	 * make server calls
	 *
	 * @access	private
	 * @return	array
	 */
	private function makeServerCall($key,$request,$methodName) {
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
	 *
	 * This method adds new marketing page entry in the database
	 *
	 * @access	public
	 * @return	void
	 */
    public function checkPageName($name){
        $this->init();
        $key=md5('checkPageName');
        $request = array ($name,'struct');
        return $this->makeServerCall($key,$request,'checkPageName');
    }

	public function addNewMarketingPage() {
		$this->init();
		$key=md5('addNewMarketingPage');
		$request = array ('struct');
		return $this->makeServerCall($key,$request,'addNewMarketingPage');
	}
	/**
	 *
	 * This method retrieves the list of marketing pages
	 *
	 * @access	public
	 * @return	array
	 */
	public function marketingPageDetails($start, $count) {
		$this->init();
		$key=md5('marketingPageDetails');
		$request = array ($start, $count, 'struct');
		return $this->makeServerCall($key,$request,'marketingPageDetails');
	}
    /**
	 *
	 * This method retrieves the list of marketing pages
	 *
	 * @access	public
	 * @return	array
	 */
	public function marketingPageDetailsById($page_id) {
		$this->init();
		$key=md5('marketingPageDetailsById');
		$request = array ($page_id,'struct');
		return $this->makeServerCall($key,$request,'marketingPageDetailsById');
	}
	/**
	 *
	 * This method retrieves details for a particular marketing page
	 *
	 * @access	public
	 * @return	array
	 */
	public function getmarketingPageContents($extraparam) {
		$this->init();
		$key=md5('getmarketingPageContents');
		$request = array ($extraparam,'struct');
		return $this->makeServerCall($key,$request,'getmarketingPageContents');


	}

	/**
	 * savemarketingPageContents
	 *
	 * It will save the changes done for selected marketing page
	 *
	 * @access	public
	 * @return	array
	 */
	public function savemarketingPageContents($page_id, $header_text, $banner_zone_id, $banner_text, $form_heading,$subheading,$banner_url, $background_url,$background_image, $pixel_codes, $submitButtonText,$headerImageUrl) {
		$this->init();
		$key=md5('savemarketingPageContents');
		$request = array($page_id, $header_text, $banner_zone_id, $banner_text, $form_heading,$subheading,$banner_url, $background_url,$background_image,$pixel_codes, $submitButtonText, $headerImageUrl, 'struct');
		return $this->makeServerCall($key,$request,'savemarketingPageContents');

	}
	
	public function saveMarketingPageMailer($page_id,$subject,$content,$attachment_url,$attachment_name,$update_attachment,$downloadConfirmationMessage) {
		$this->init();
		$key=md5('saveMarketingPageMailer');
		$request = array($page_id,$subject,$content,$attachment_url,$attachment_name,$update_attachment,$downloadConfirmationMessage,'struct');
		return $this->makeServerCall($key,$request,'saveMarketingPageMailer');

	}
	
	/**
	 * savemarketingPageContents
	 *
	 * It will save the changes done for selected marketing page
	 *
	 * @access	public
	 * @return	array
	 */
	public function savemarketingPageName($page_id,$page_name, $display_on_page) {
		$this->init();
		$key=md5('savemarketingPageName');
		$request = array($page_id, $page_name, $display_on_page, 'struct');
		return $this->makeServerCall($key,$request,'savemarketingPageName');
	}
	/**
	 *
	 * It will save the changes done for destination url of selected page
	 *
	 * @access	public
	 * @return	array
	 */
	public function saveDestinationURL($page_id,$destination_url) {
		$this->init();
		$key=md5('saveDestinationURL');
		$request = array($page_id, $destination_url,'struct');
		return $this->makeServerCall($key,$request,'saveDestinationURL');

	}
	/**
	 *
	 * It will retrieve exhaustive list of courses (category_id, group_id, courses details)
	 *
	 * @access	public
	 * @return	array
	 */
	public function getCourseSpecializationForAllCategoryIdGroup($appId) {
		$this->init();
		$key=md5('getCourseSpecializationForAllCategoryIdGroup');
		$request = array($appId,'struct');
		return $this->makeServerCall($key,$request,'getCourseSpecializationForAllCategoryIdGroup');

	}
	/**
	 * saves selected courses for a selected page
	 *getManagementCourses
	 * @access	public
	 * @return	array
	 */
	public function saveMMPageCourses($page_id,$courses_ids,$page_type) {
	   $this->init();
	   $key=md5('saveMMPageCourses');
	   $request = array($page_id,$courses_ids,$page_type,'struct');
	   return $this->makeServerCall($key,$request,'saveMMPageCourses');

	}
	/**
	 * This method list list of couses for a page based on category or group
	 *
	 * @access	public
	 * @return array
	 */
	public function getCourselistForApage($page_id,$order_type) {
	   $this->init();
	   $key=md5('getCourselistForApage'.$page_id);
	   $request = array($page_id,$order_type,'struct');
	  //print_r($this->makeServerCall($key,$request,'getCourselistForApage'));
	   return $this->makeServerCall($key,$request,'getCourselistForApage');

	}
    /**
	 * returns management courses
	 *
	 * @access	private
	 * @return	array
	 */
   public function getManagementCourses($management_ids) {
        $ldbObj = new LDB_Client();
		//$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
		$management_saved_cpurses = json_decode($ldbObj->sgetCourseList($appId,3),true);
		$distance_course = json_decode($ldbObj->sgetSpecializationListByParentId($appId,24),true);
		$management_saved_cpurses = array_merge($management_saved_cpurses,$distance_course);
		$management_course = array();
		foreach($management_saved_cpurses as $courses) {
			$management_course[$courses['SpecializationId']]= $courses;
		}
		$management_ids = split(' ', $management_ids);
		$index=0;
		$saved_array = array();
		foreach($management_ids as $id) {
			if ($id == '20')
			{
			    $id = '2';
			}  
			$saved_array[] = $management_course[$id];
		}
		sort($saved_array);
		return $saved_array;
   }
   /**
    * returns testprep saved courses
    *
    * @access	private
    * @return	array
    */
   function getTestPrepCoursesListForApage($appId,$page_id,$pagetype,$type) {
   	   $this->init();
   	   $key=md5('getTestPrepCoursesListForApage'.$page_id);
	   $request = array($appId,$page_id,$pagetype,$type,'struct');
	   return $this->makeServerCall($key,$request,'getTestPrepCoursesListForApage');
	}
   /**
    * returns testprep saved courses
    *
    * @access	private
    * @return	array
    */
   function getStudyAbroadCoursesListForApage($appId,$page_id,$pagetype,$type,$flag='') {
   	   $this->init();
   	   $key=md5('getStudyAbroadCoursesListForApage'.$page_id);
	   $request = array($appId,$page_id,$pagetype,$type,$flag,'struct');
	   return $this->makeServerCall($key,$request,'getStudyAbroadCoursesListForApage');
	}
}
?>
