<?php

class Enterprise_client{

	var $CI_Instance;

	function init(){
		$this->CI_Instance = & get_instance();
		$this->CI_Instance->load->helper('url');
		$this->CI_Instance->load->library('xmlrpc');
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->server(ENTERPRISE_SERVER_URL, ENTERPRISE_SERVER_PORT);
	}

	function initSearch(){
		$this->CI_Instance =& get_instance();
		$this->CI_Instance->load->helper('url');
		$this->CI_Instance->load->library('xmlrpc');
		global $searchIP;
                $server_url = "http://$searchIP/enterprise/EnterpriseServer";
		$this->CI_Instance->xmlrpc->set_debug(0);
		$this->CI_Instance->xmlrpc->server($server_url, 80);
	}

	/*
	*       Method to Update Page-Item Table
	*/
	function updateCmsItem($appID,$updateCMSData,$updateKeyPage){
		$this->init();

		$this->CI_Instance->xmlrpc->method('supdateCmsItem');
		$request = array(array($appID,'int'),array($updateCMSData,'struct'),array($updateKeyPage,'struct'));
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}


	/*
	*       Method to Get Product IDs and other fields for a Key_ID and Product Type
	*/
	function getItems($appID,$clientUpdItemArr){
		$this->initSearch();

		$this->CI_Instance->xmlrpc->method('sgetItems');
		$request = array(array($appID,'int'),array($clientUpdItemArr,'struct'));
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}


	/*
	*       Method to Get All Key-Pages for a Product_ID and Product Type
	*/
	function getKeyPages($appID,$clientUpdItemArr,$fromWhere){
		$this->initSearch();

		$this->CI_Instance->xmlrpc->method('sgetKeyPages');
		$request = array(array($appID,'int'),array($clientUpdItemArr,'struct'), $fromWhere);
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}


	/*
	*       Method to Get All Key-Page IDs and Names
	*/
	function getAllKeyPages($appID){
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetAllKeyPages');
		$request = array(array($appID,'int'));
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	/*
	*       Method to Update and assign new institute for a Course_id
	*/
	function updateAssignNewInstitute($appID,$courseId,$newInstId){
		$this->init();
		$this->CI_Instance->xmlrpc->method('supdateAssignNewInstitute');
		$request = array(array($appID,'int'),array($courseId,'int'),array($newInstId,'int'));
		error_log_shiksha("SHAS updateAssignNewInstitute CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	/*
	*       Method to remove Institute Logo
	*/
	function removeInstiLogoCMS($appID,$instituteId){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sremoveInstiLogoCMS');
		$request = array(array($appID,'int'),array($instituteId,'int'));
		error_log_shiksha("SHAS removeInstiLogoCMS CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	/*
	*       Method to remove Institute Panel
	*/
	function removeFeaturedPanelLogo($appID,$instituteId){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sremoveFeaturedPanelLogo');
		$request = array(array($appID,'int'),array($instituteId,'int'));
		error_log_shiksha("SHAS removeFeaturedPanelLogo CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	/*
	*       Method to remove Course Media Content
	*/
	function removeCourseMediaCMS($appID,$removeMediaArr){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sremoveCourseMediaCMS');
		$request = array(array($appID,'int'),array($removeMediaArr,'struct'));
		error_log_shiksha("SHAS removeInstiLogoCMS CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}



	/*
	*       Method to Update old (ie. existing) institute for a Course_id
	*/
	function updateOldInstitute($appID,$instituteArr){
		$this->init();
		$this->CI_Instance->xmlrpc->method('supdateOldInstitute');
		$request = array(array($appID,'int'),array($instituteArr,'struct'));
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
                    }else{
                        $this->CI_Instance->load->library('cacheLib');
                        $this->cacheLib = new cacheLib();
                        $this->cacheLib->clearCache('cmsFedCourse');
                        return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	/*
	*       Method to Update a Course
	*/
	function EditUpdateCourse($appId,$courseData,$Data,$testsArray){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sEditUpdateCourse');
		$request = array(array($appID,'int'),array($courseData,'struct'),array($Data,'struct'),array($testsArray,'struct'));
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

                if ( ! $this->CI_Instance->xmlrpc->send_request() ){
                    return ($this->CI_Instance->xmlrpc->display_error());
                }else{
                    $this->CI_Instance->load->library('cacheLib');
                    $this->cacheLib = new cacheLib();
                    $this->cacheLib->clearCache('cmsFedCourse');
                    return ($this->CI_Instance->xmlrpc->display_response());
                }
	}

	/*
	*       Method to Update a Scholarship
	*/
	function updateScholarship($appId,$addScholarshipData,$s_eligibility){
		$this->init();
		$this->CI_Instance->xmlrpc->method('supdateScholarship');
		$request = array(array($appId,'int'),array($addScholarshipData,'struct'),array($s_eligibility,'struct'));
		//		error_log_shiksha("CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	/**************************************************************
	DIFFERENT PRODUCT APIs
	**************************************************************/

	function getEventDetailCMS($appId,$event_id){
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetEventDetailCMS');
		$request = array($appId,$event_id);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			error_log_shiksha("EVENT_CAL_CLIENT::getEventDetailCMS ---- error:=>".$this->CI_Instance->xmlrpc->display_error());
			return array();
		}else{
			// print_r($this->CI_Instance->xmlrpc->display_response());
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	function getPopularTopicsCMS($appId,$board_id,$start,$count,$srchTitle,$srchAuthor,$filter1,$showReportedAbuse="false"){
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetPopularTopicsCMS');
		$request = array($appId,$board_id,$start,$count,$srchTitle,$srchAuthor,$filter1,$showReportedAbuse);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function getHeaderTabs($appId,$userGroup,$userid)
	{

		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sGetHeaderTabs');
		$request = array(array($appId,'int'),array($userGroup,'string'),array($userid,'string'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function addEnterpriseUser($userArray)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('saddEnterpriseUser');
		$request = array(array($userArray,'struct'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function getInstituteList($appId,$cityId,$usergroup,$userid) {
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetInstituteList');
		$request = array(array($appId,'int'),array($cityId,'int'),array($usergroup,'string'),array($userid,'int'));
		error_log_shiksha("CLIENT REQUEST getInstituteList ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	function getCitiesWithCollege($appId,$countryId,$usergroup,$userid) {
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetCitiesWithCollege');
		$request = array(array($appId,'int'),array($countryId,'int'),array($usergroup,'string'),array($userid,'int'));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

	function getSearchSubCategories($request)
	{
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('getSearchSubCategories');
		$request = array(array($request,'struct'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}

	}

	function removeNotificationDoc($request)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('sRemoveNotificationDoc');
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function editNotification($appId,$editArr,$eligibility)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('sEditNotification');
		$request = array (array($appId,'int'),array($editArr,'struct'),array($eligibility,'struct'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	/*
	*       Method to remove Scholarship Media Content
	*/
	function RemoveScholarshipDoc($appID,$removeMediaArr){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sRemoveScholarshipDoc');
		$request = array(array($appID,'int'),array($removeMediaArr,'struct'));
		error_log_shiksha("SHAS sRemoveScholarshipDoc CLIENT REQUEST ARRAY".print_r($request,TRUE));
		$this->CI_Instance->xmlrpc->request($request);

		if ( ! $this->CI_Instance->xmlrpc->send_request() ){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}
	function getNotificationEvents($appId,$notificationId)
	{
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('getNotificationEvents');
		$request = array (array($appId,'int'),array($notificationId,'int'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function getEnterpriseUserDetails($appId,$userId)
	{
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('getEnterpriseUserDetails');
		$request = array (array($appId,'int'),array($userId,'int'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function updateEnterpriseUserDetails($appId,$request)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('updateEnterpriseUserDetails');
		$request = array (array($appId,'int'),array($request,'struct'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function changePassword($appId,$request)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('changePassword');
		$request = array (array($appId,'int'),array($request,'struct'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function updateUserGroup($appId,$data)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('updateUserGroup');
		$request = array (array($appId,'int'),array(base64_encode(serialize($data)),'string'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
            $this->CI_Instance->load->library('cacheLib');
            $this->cacheLib = new cacheLib();

            $key = "lu_".md5('validateuser'.$_COOKIE['user'].'on');
			$this->cacheLib->clearCacheForKey($key);
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

	function getViewCountForUserFedListings($appId,$userId)
	{
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('getViewCountForUserFedListings');
		$request = array (array($appId,'int'),array($userId,'int'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			return $this->CI_Instance->xmlrpc->display_response();
		}
	}

    function getMediaData($appId,$typeofmedia,$start,$count,$startDate,$endDate)
	{
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetMediaData');
		$request = array ($appId,$typeofmedia,$start,$count,$startDate,$endDate);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
		   return $this->CI_Instance->xmlrpc->display_error();
		}else{
		   return $this->CI_Instance->xmlrpc->display_response();
		}
	}

    function deleteMediaData($appId,$type,$userids)
	{
		$this->init();
		$this->CI_Instance->xmlrpc->method('sdeleteMediaData');
		$request = array (array($appId,'int'),array($type,'string'),array($userids,'string'));
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
		   return $this->CI_Instance->xmlrpc->display_error();
		}else{
		   return $this->CI_Instance->xmlrpc->display_response();
		}
	}

    function getcountofMedia($appId,$typeofmediadata,$type,$startDate,$endDate)
	{
		$this->initSearch();
		$this->CI_Instance->xmlrpc->method('sgetcountofMedia');
		$request = array ($appId,$typeofmediadata,$type,$startDate,$endDate);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
		   return $this->CI_Instance->xmlrpc->display_error();
		}else{
		   return $this->CI_Instance->xmlrpc->display_response();
		}
	}

    function getReportedChangesForBlogs($appID,$listing_type, $listing_type_id) {
        $this->init();
        error_log_shiksha("ASHISH APP ID getReportedChangesForBlogs =>$appID :: $type_id,$listing_type");
        $this->CI_Instance->xmlrpc->method('getReportedChangesForBlogs');
        $request = array(array($appID,'int'),array($listing_type,'string'),array($listing_type_id,'string'));
        error_log_shiksha("CLIENT REQUEST getReportedChangesForBlogs ARRAY".print_r($request,TRUE));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }

function getReportedChangesById($appID,$changeId) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('getReportedChangesById');
        $request = array(array($appID,'int'),array($changeId,'int'));
        $this->CI_Instance->xmlrpc->request($request);

        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return ($this->CI_Instance->xmlrpc->display_error());
        }else{
            return ($this->CI_Instance->xmlrpc->display_response());
        }

    }
function addMainCollegeLink($appID, $testprepCat,$countryId,$cityId,$categoryId, $listingId,$type,$startDate,$endDate,$subscriptionId,$subcategoryId,$stateId)
{
	$this->init();
	error_log("hey111111111".$stateId);
	$this->CI_Instance->xmlrpc->method('saddMainCollegeLink');
	$request = array(
			array($appID,'int'),
			array($testprepCat,'int'),
			array($countryId,'int'),
			array($cityId,'int'),
			array($stateId,'int'),
			array($categoryId,'int'),
			array($subcategoryId,'int'),
			array($listingId,'string'),
			array($type,'string'),
			array($startDate,'string'),
			array($endDate,'string'),
			array($subscriptionId,'string')
			);
	$this->CI_Instance->xmlrpc->request($request);

	if ( ! $this->CI_Instance->xmlrpc->send_request()){
		return ($this->CI_Instance->xmlrpc->display_error());
	}else{
		return ($this->CI_Instance->xmlrpc->display_response());
	}

}

function cancelSubscription($appID, $subscriptionId)
{
	$this->init();
	$this->CI_Instance->xmlrpc->method('scancelSubscription');
	$request = array(
			array($appID,'int'),
			array($subscriptionId,'string')
			);
	$this->CI_Instance->xmlrpc->request($request);

	if ( ! $this->CI_Instance->xmlrpc->send_request()){
		return ($this->CI_Instance->xmlrpc->display_error());
	}else{
		return ($this->CI_Instance->xmlrpc->display_response());
	}
}

    function getListingsByClient($appId,$userArr)
    {
        $this->initSearch();
        $this->CI_Instance->xmlrpc->method('sgetListingsByClient');
        $request = array (array($appId,'int'),array($userArr,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

    function getMainInstitutesByClient($appId,$userArr) {
        $this->init();
        $this->CI_Instance->xmlrpc->method('sgetMainInstitutesByClient');
        $request = array (array($appId,'int'),array($userArr,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }        
    }

    function checkUniqueTitle($appId,$dataArr)
    {
        $this->init();
        $this->CI_Instance->xmlrpc->method('scheckUniqueTitle');
        $request = array (array($appId,'int'),array($dataArr,'struct'));
        $this->CI_Instance->xmlrpc->request($request);
        if ( ! $this->CI_Instance->xmlrpc->send_request()){
            return $this->CI_Instance->xmlrpc->display_error();
        }else{
            return $this->CI_Instance->xmlrpc->display_response();
        }
    }

function supdateMainCollegeLink($id, $updateArr)
{
	$this->init();
	$this->CI_Instance->xmlrpc->method('supdateMainCollegeLink');
	$request = array(
			array($id,'int'),
			array($updateArr,'struct')
			);
	$this->CI_Instance->xmlrpc->request($request);

	if ( ! $this->CI_Instance->xmlrpc->send_request()){
		return ($this->CI_Instance->xmlrpc->display_error());
	}else{
		return ($this->CI_Instance->xmlrpc->display_response());
	}
}



function insertCreditCardDetails($flag, $userId, $json, $paymentId, $partPaymentId,$transactionId="-1",$PartiallypaidAmount, $gateway='') {
    $this->init();
    $this->CI_Instance->xmlrpc->method('insertCreditCardDetails');
    $request = array(
            array($flag,'string'),
            array($userId,'string'),
            array($json,'string'),
            array($paymentId,'string'),
            array($partPaymentId,'string'),
            array($transactionId,'string'), 
            array($PartiallypaidAmount,'string'),
            array($gateway,'string')
            );
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

function getCreditTransactionStatus($TxnNum) {
    $this->init();
    $this->CI_Instance->xmlrpc->method('getCreditTransactionStatus');
    $request = array(
        array($TxnNum,'string')
    );
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        return ($this->CI_Instance->xmlrpc->display_response());
    }
}

        function getListingsByClientAndType($appId,$userArr)
        {
            $this->initSearch();
            $this->CI_Instance->xmlrpc->method('sgetListingsByClientAndType');
            $request = array (array($appId,'int'),array($userArr,'struct'));
            $this->CI_Instance->xmlrpc->request($request);
            if ( ! $this->CI_Instance->xmlrpc->send_request()){
                return $this->CI_Instance->xmlrpc->display_error();
            }else{
                return $this->CI_Instance->xmlrpc->display_response();
            }
        }

function checkAndConsumeActualSubscription($appID,$listings,$audit) {
    $this->init();
    $this->CI_Instance->xmlrpc->method('checkAndConsumeActualSubscription');
    $request = array(array($appID,'int'),array(base64_encode(serialize($listings)),'string'),array(base64_encode(serialize($audit)),'string'));
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = ($this->CI_Instance->xmlrpc->display_response());
        return unserialize(base64_decode($response));
    }
}


function toConsumeActualSubscriptionCheck($appID,$listings) {
    $this->init();
    $this->CI_Instance->xmlrpc->method('toConsumeActualSubscriptionCheck');
    $request = array(array($appID,'int'),array(base64_encode(serialize($listings)),'string'));
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = ($this->CI_Instance->xmlrpc->display_response());
        return unserialize(base64_decode($response));
    }
}

function getAbuseList($appId,$loggedUserId,$start,$count,$module,$status='',$userNameFieldData='',$userLevelFieldData='',$reported='',$filterByReason=''){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getAbuseList');
      $request = array($appId,$loggedUserId,$start,$count,$module,$status,$userNameFieldData,$userLevelFieldData,$reported,$filterByReason);

      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }

}

function updateStatusAbuseList($appId,$loggedUserId,$entityId,$entityType,$status=''){
      $this->init();
      $this->CI_Instance->xmlrpc->method('updateStatusAbuseList');
      $request = array($appId,$loggedUserId,$entityId,$entityType,$status);

      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }

}
function getQuestionlogInfo($appId,$loggedInUserId,$start,$rows,$moduleName,$filter,$userNameFieldData,$userLevelFieldData){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getQuestionlogInfo');
      $request = array($appId,$loggedInUserId,$start,$rows,$moduleName,$filter,$userNameFieldData,$userLevelFieldData);
      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }

}
function deleteQuestionInfoInLog($appId,$msgId,$userId,$msgTitle,$displayName,$status){
        $this->init();
    $this->CI_Instance->xmlrpc->method('deleteQuestionInfoInLog');
    $request = array($appId,$msgId,$userId,$msgTitle,$displayName,$status);
     $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }
}

 function updateTitle($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription){
                $this->init('write');
		$this->CI_Instance->xmlrpc->method('updateTitle');
		$request = array($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription);
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return $this->CI_Instance->xmlrpc->display_error();
		}else{
			$response = $this->CI_Instance->xmlrpc->display_response();

			return $response;
		}

  }

function republishEntity($appId,$entityId,$threadId,$ownerId,$entityType,$penalty){
      $this->init();
      $this->CI_Instance->xmlrpc->method('republishEntity');
      $request = array($appId,$entityId,$threadId,$ownerId,$entityType,$penalty);

      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      if($entityType=="Question"){
			//Modified by Ankur on 8 March for SHiksha cafe performance
			//After republishing a question, we will have to increase the count of questions from Cache
		$republishResult = $this->CI_Instance->xmlrpc->display_response();
		if(is_array($republishResult) && isset($republishResult['categoryId']) && $republishResult['categoryId']!=0){
		    $this->CI_Instance->load->library('cacheLib');
		    $cacheLibObj = new cacheLib();
		    $categoryId = $republishResult['categoryId'];
		    $countryId = $republishResult['countryId'];
		    $countKey = md5('getPostedTopicsCount'.$appId.$categoryId.$countryId);
		    if(($cacheLibObj->get($countKey)!='ERROR_READING_CACHE') && intval($cacheLibObj->get($countKey)) > 0){
			$newCount = intval($cacheLibObj->get($countKey))+1;
			$cacheLibObj->store($countKey,$newCount,1440000,'misc');
		    }
		    //Also, reduce the count for All Category - All Country 
		    $countKey = md5('getPostedTopicsCount'.$appId.'11');
		    if(($cacheLibObj->get($countKey)!='ERROR_READING_CACHE') && intval($cacheLibObj->get($countKey)) > 0){
			$newCount = intval($cacheLibObj->get($countKey))+1;
			$cacheLibObj->store($countKey,$newCount,1440000,'misc');
		    }
		}
		//End Modifications by Ankur

	      }
	      else if($entityType=="Event"){
		$this->CI_Instance->load->library('event_cal_client');
		$eventClient = new Event_cal_client();
		$eventClient->indexEvent($entityId);
	      }
		  
		/*For indexing purpose*/
		$discussionIndexTypes = array('discussion', 'discussion comment', 'discussion reply');
		$questionIndexTypes = array('user', 'question');
		if(in_array(strtolower($entityType), $discussionIndexTypes)){
			modules::run('search/Indexer/addToQueue', $threadId, 'discussion', 'delete');
			modules::run('search/Indexer/addToQueue', $threadId, 'discussion');
		} else if(in_array(strtolower($entityType), $questionIndexTypes)){
			modules::run('search/Indexer/addToQueue', $threadId, 'question', 'delete');
			modules::run('search/Indexer/addToQueue', $threadId, 'question');
		}
		return $this->CI_Instance->xmlrpc->display_response();
      }

}

function getQuestionsPostedForEnterpriseUser($appId,$userId,$startOffset,$countOffset){
	$this->init();
    $this->CI_Instance->xmlrpc->method('getQuestionsPostedForEnterpriseUser');
    $request = array($appId,$userId,$startOffset,$countOffset);
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = ($this->CI_Instance->xmlrpc->display_response());
	return json_decode(gzuncompress(base64_decode($response)),true);
    }
}



function addSpotlightEvent($appID,$eventId1,$eventId2,$paidEventId,$uploadedImage,$tillDate) {
    $this->init();
    $this->CI_Instance->xmlrpc->method('addSpotlightEvent');
    $request = array(array($appID,'int'),array($eventId1,'int'),array($eventId2,'int'),array($paidEventId,'string'),array($uploadedImage,'file'),array($tillDate,'date'));
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = ($this->CI_Instance->xmlrpc->display_response());
        return $response;
    }
}


function modCompanyLogo($name,$url,$id){

    $this->init();
    $this->CI_Instance->xmlrpc->method('modCompanyLogo');
    $request = array(array($name,'string'),array($url,'string'),array($id,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());}
    else{ $response = ($this->CI_Instance->xmlrpc->display_response());
        error_log("last response is ".print_r($response,true));
        return $response;}
}


// Client Function to retrieve the company listing
function getCompanyLogo($sortClass,$rstart,$rcount)
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('getCompanyLogo');
    $request = array(array($sortClass,'string'),array($rstart,'int'), array($rcount,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request())
    return ($this->CI_Instance->xmlrpc->display_error());
    else{$response = ($this->CI_Instance->xmlrpc->display_response());
        //error_log("last response is ".print_r($response,true));
        return $response;}
}

function setCompanyLogo($name,$url){

    $this->init();
    $this->CI_Instance->xmlrpc->method('setCompanyLogo');
    $request = array(array($name,'string'),array($url,'string'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());}
    else{ $response = ($this->CI_Instance->xmlrpc->display_response());
        error_log("last response is ".print_r($response,true));
        return $response;}
}

function addLogoListing($id){

    $this->init();
    $this->CI_Instance->xmlrpc->method('addLogoListing');
    $request = array(array($id,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error()); }
   else{ $response = ($this->CI_Instance->xmlrpc->display_response());
        error_log("last response is ".print_r($response,true));
        return $response;}
}



function delCompanyLogo($delid){

    $this->init();
    $this->CI_Instance->xmlrpc->method('delCompanyLogo');
    $request = array(array($delid,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error()); }
   else{ $response = ($this->CI_Instance->xmlrpc->display_response());
        error_log("last response is ".print_r($response,true));
        return $response;}
}


function checkDeleteLogo($delid){

    $this->init();
    $this->CI_Instance->xmlrpc->method('checkDeleteLogo');
    $request = array(array($delid,'int'));
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error()); }
    else{ $response = ($this->CI_Instance->xmlrpc->display_response());
        error_log("last response is ".print_r($response,true));
        return $response;}

}

// Bhuvnesh : Client Function to retrieve the popular institute listings
function getPopularInstitutes()
{
    $this->init();
    $this->CI_Instance->xmlrpc->method('getPopularInstitutes');
    $this->CI_Instance->xmlrpc->request();
    if ( ! $this->CI_Instance->xmlrpc->send_request())
    return ($this->CI_Instance->xmlrpc->display_error());
    else{$response = ($this->CI_Instance->xmlrpc->display_response());        
        return $response;}
}
function setPopularInstitutes($popParams)
{
    $this->init();
    $request = array($popParams[0],$popParams[1],$popParams[2],$popParams[3]);
    $this->CI_Instance->xmlrpc->method('setPopularInstitutes');
    $this->CI_Instance->xmlrpc->request($request);
    if ( ! $this->CI_Instance->xmlrpc->send_request())
    return ($this->CI_Instance->xmlrpc->display_error());
    else{$response = ($this->CI_Instance->xmlrpc->display_response());
        return $response;}
}





function getSpotlightEvents(){
    error_log("inside getSpotlightEvents in client");
    $this->init();
    $this->CI_Instance->xmlrpc->method('getSpotlightEvents');
    $this->CI_Instance->xmlrpc->request($request);

    if ( ! $this->CI_Instance->xmlrpc->send_request()){
        return ($this->CI_Instance->xmlrpc->display_error());
    }else{
        $response = ($this->CI_Instance->xmlrpc->display_response());
	error_log("last response is ".print_r($response,true));
        return $response;
    }
}
	function addTVCUser($name, $email, $mobile, $city, $company, $page, $answer){
		$this->init();
		$this->CI_Instance->xmlrpc->method('sAddTVCUser');
		$request = array(
						array($name,'string'),
						array($email,'string'),
						array($mobile,'string'),
						array($city,'string'),
						array($company,'string'),
						array($page,'string'),
						array($answer,'string')
					);		
		$this->CI_Instance->xmlrpc->request($request);
		if ( ! $this->CI_Instance->xmlrpc->send_request()){
			return ($this->CI_Instance->xmlrpc->display_error());
		}else{
			return ($this->CI_Instance->xmlrpc->display_response());
		}
	}

function getExperts($appId,$loggedUserId,$start,$count,$module,$status='',$userNameFieldData='',$userLevelFieldData=''){
      $this->init();
      $this->CI_Instance->xmlrpc->method('getExperts');
      $request = array($appId,$loggedUserId,$start,$count,$module,$status,$userNameFieldData,$userLevelFieldData);

      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }
}

function actionExpert($appId,$userId,$action='accept'){
      $this->init();
      $this->CI_Instance->xmlrpc->method('actionExpert');
      $request = array($appId,$userId,$action);

      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }
}

function removeExpertProfilePic($appId,$userId){
      $this->init();
      $this->CI_Instance->xmlrpc->method('removeExpertProfilePic');
      $request = array($appId,$userId);

      $this->CI_Instance->xmlrpc->request($request);
      if ( ! $this->CI_Instance->xmlrpc->send_request()){
	      return $this->CI_Instance->xmlrpc->display_error();
      }else{
	      return $this->CI_Instance->xmlrpc->display_response();
      }
}
}
?>
