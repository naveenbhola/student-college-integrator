<?php

require APPPATH.'/modules/Listing/listing/controllers/posting/AbstractListingPost.php';

class ListingUpdate extends AbstractListingPost
{
    private $listingUpdateModel;
    
    function __construct()
    {
		parent::__construct();
        
        $this->load->model('listing/posting/listingupdatemodel');
        $this->listingUpdateModel = new ListingUpdateModel();
    }
    
    function updateContactDetails()
    {
		$startTime = microtime(true);
		$cmsUserInfo = $this->cmsUserValidation();
		if($cmsUserInfo['usergroup'] != 'cms') {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		
		$contactDetails = array(
			'name' => $_REQUEST['contact_name_location'],
			'phone' => $_REQUEST['contact_phone_location'],
			'mobile' => $_REQUEST['contact_mobile_location'],
			'email' => $_REQUEST['contact_email_location']
		);
		
		$instituteId = intval($_POST['institute_id']);
		
		$locationIdsForInstitute = explode(',',$_POST['locationIds_for_institute']);
		$instituteLocationIds = array();
		foreach($locationIdsForInstitute as $locationId) {
			if(intval(trim($locationId))) {
				$instituteLocationIds[] = intval(trim($locationId));
			}
		}
		
		$locationIdsForCourses = explode(',',$_POST['locationIds_for_courses']);
		
		$courseLocationIds = array();
		$courseIds = array();
		foreach($locationIdsForCourses as $locationId) {
			if(trim($locationId)) {
				list($courseId,$courseLocationId) = explode('||',$locationId);
				$courseLocationIds[] = array(
												'courseId' => intval($courseId),
												'locationId' => intval($courseLocationId)
											);
				$courseIds[] = $courseId;
			}
		}
		
		if(count($instituteLocationIds) > 0) {
			$this->listingUpdateModel->updateContactDetailsForInstitute($instituteId,$instituteLocationIds,$contactDetails);
			$this->buildListingCache(array(array('type' => 'institute','typeId' => $instituteId)));
		}
		
		if(count($courseLocationIds) > 0) {
			$this->listingUpdateModel->updateContactDetailsForCourse($courseLocationIds,$contactDetails);
			
			$courseIds = array_unique($courseIds);
			foreach($courseIds as $courseId) {
				$this->buildListingCache(array(array('type' => 'course','typeId' => $courseId)));	
			}
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
}