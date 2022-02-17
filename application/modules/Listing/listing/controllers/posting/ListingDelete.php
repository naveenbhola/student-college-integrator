<?php

require APPPATH.'/modules/Listing/listing/controllers/posting/AbstractListingPost.php';

class ListingDelete extends AbstractListingPost
{
    private $listingDeleteModel;
    
    function __construct()
    {
		parent::__construct();
        
        $this->load->library('message_board_client');
		$messageBoardClient = new message_board_client();
		
        $this->load->model('listing/posting/listingdeletemodel');
        $this->listingDeleteModel = new ListingDeleteModel($messageBoardClient);
    }
    
    function delete()
    {
        $startTime = microtime(true);
    	$validateuser = $this->userStatus;
        if($validateuser[0]['usergroup'] != "cms") {
            echo "Invalid Call";
            return 1;
        }
        
        ini_set('max_execution_time', 9000); // 15 Min timeout
        
    	$user_id = $validateuser[0]['userid'];
		
        $this->load->library('listing_client');
        $listingObj = new Listing_client();
    	
        $listingTypeId = $this->input->post('type_id',true);
    	$listingType = $this->input->post('listing_type',true);
        
        /**
         * Check for Online form
         * If the institute/course contains an Online form,
         * we should now allow it to be deleted.
         */ 
    	$this->load->library('Online_form_client');
    	$onlineClient = new Online_form_client();
    	$listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm($listingType,$listingTypeId);
    	if($listingHasOnlineForm){
    		echo 'OnlineFormAvailable';
    		return 1;
    	}
        
        $instituteId = "";
        
        if($listingType == 'course') {  
            $instituteId = $listingObj->getInstituteIdForCourseId(1,$listingTypeId);
        }
        
        $deleteStatus = $this->listingDeleteModel->deleteListing($listingType, $listingTypeId, $user_id);
        
        // delete cache
        $this->load->library('categoryList/CacheUtilityLib');
        $cacheUtilityLib = new CacheUtilityLib;
        $this->load->library('listing/ListingCache');
        $listingCacheObj = new ListingCache();
        
        if($listingType == 'institute') {
           // $cacheUtilityLib->refreshCacheForInstitutes(array($listingTypeId)); //Gateway Timout : Disabling refresh cache for category pages
            $listingCacheObj->deleteInstitute($listingTypeId);
        }
        else if($listingType == 'course') {
            $this->load->library('listing/ListingProfileLib');
            $this->listingprofilelib->updateProfileCompletion($instituteId);
          //  $cacheUtilityLib->refreshCacheForCourses(array($listingTypeId)); //Gateway Timout : Disabling refresh cache for category pages
            $listingCacheObj->deleteCourse($listingTypeId);
        }

		// update popular institute	
        $this->load->model('institutemodel');	
		$this->institutemodel->updatePopularInstitute(array($listingTypeId),$listingType);
        
        $this->load->library('cacheLib');		
		$key = md5('getInstituteForTabs');
		$this->cachelib->clearCache($key);

    	echo $deleteStatus['QueryStatus'];
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }
    
    function deleteMultipleCourses()
    {
        $startTime = microtime(true);
        $this->load->library('listing/cache/ListingCache');	
		$listingCacheObj = new ListingCache();
        
        $this->load->library('Online_form_client');
		$onlineClient = new Online_form_client();
        
        $this->load->library('listing/ListingProfileLib');
        $listingProfileObj = new ListingProfileLib;
        
        $validateuser = $this->userStatus;
        if($validateuser[0]['usergroup'] != "cms") {
            echo "Invalid Call";
            return 1;
        }
		
        ini_set('max_execution_time', 9000); // 15 Min timeout

        $user_id = $validateuser[0]['userid'];
		$courses = explode("|",$this->input->post('courses',true));
        $instituteId = $this->input->post('institute',true);
        
        foreach($courses as $courseId) {
			$listingHasOnlineForm = $onlineClient->checkIfListingHasOnlineForm('course', $courseId);
			if($listingHasOnlineForm){
                echo "The course Id: $courseId has an Online form attached to it and cannot be deleted.";
                return 1;
			}
            $this->listingDeleteModel->deleteListing('course',$courseId, $user_id); 
			$listingCacheObj->deleteCourse($courseId);
		}
        
        if($instituteId > 0) {
    		$listingProfileObj->updateProfileCompletion($instituteId);
        }
        
        $this->load->model('institutemodel');	
        $this->load->library('cacheLib');
        // update popular institute	                      	
        $this->institutemodel->updatePopularInstitute($courses,'course');
        
        $key = md5('getInstituteForTabs');
        $this->cachelib->clearCache($key);
        echo "Courses Deleted. Press OK to continue..";
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }
}