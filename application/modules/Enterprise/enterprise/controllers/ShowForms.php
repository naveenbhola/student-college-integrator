<?php


/**
 * Class For Listing Forms in the system.

 Copyright 2007 Info Edge India Ltd

 $Rev::            $:  Revision of last commit
 $Author: manishz $:  Author of last commit
 $Date: 2010/07/05 11:31:25 $:  Date of last commit

 Enterprise.php : Controller file: Makes call to server using XML RPC calls.

 $Id: ShowForms.php,v 1.31 2010/07/05 11:31:25 manishz Exp $:

 */

class ShowForms extends MX_Controller {

	private $noOfCopies = 10;
	/**
	 * Common function to initially load all general helpers and libraries with user validation
	 * @access  private
	 * @param  No parameters needed
	 * @return  No return
	 * @ToDo
	 */
	private function init() {
		ini_set('memory_limit', '1024M');
		$this->load->helper(array('form', 'url','date','image','shikshaUtility'));
		$this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','alerts_client'));
		$this->userStatus = $this->checkUserValidation();
	}

	/**
	 * Logged-in user validation and enterprise user products
	 * @access  public
	 * @param  No parameters needed
	 * @return array Array having vital informations of Logged-in user
	 * @ToDo
	 */
	function cmsUserValidation($usergroupAllowed = array('cms','enterprise','sums')) {
		$startTime = microtime(true);
		$validity = $this->checkUserValidation();
		error_log_shiksha("VAlidity: ".$validity);
		global $logged;
		global $userid;
		global $usergroup;
		$thisUrl = $_SERVER['REQUEST_URI'];
		if(($validity == "false" )||($validity == "")) {
			$logged = "No";
			header('location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}else {
			$logged = "Yes";
			$userid = $validity[0]['userid'];
			$usergroup = $validity[0]['usergroup'];
			if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser") {
				header("location:/enterprise/Enterprise/migrateUser");
				exit;
			}
			if(!in_array($usergroup,$usergroupAllowed)){
				header("location:/enterprise/Enterprise/unauthorizedEnt");
				exit();
			}
		}
		$this->load->library('enterprise_client');
		$entObj = new Enterprise_client();
		$headerTabs = $entObj->getHeaderTabs(1,$validity[0]['usergroup'],$validity[0]['userid']);
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$myProductDetails = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));

		$returnArr['userid']=$userid;
		$returnArr['usergroup']=$usergroup;
		$returnArr['logged'] = $logged;
		$returnArr['thisUrl'] = $thisUrl;
		$returnArr['validity'] = $validity;
		$returnArr['headerTabs'] = $headerTabs;
		$returnArr['myProducts'] = $myProductDetails;
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $returnArr;
	}

	/**
	 * Common function For Check Enterprise user flow regarding listing forms
	 * @access  public
	 * @param   int $clientId
	 * @param   array $cmsUserInfo
	 * @return  array
	 * @ToDo
	 */
	function checkFlowCase($clientUserId, $cmsUserInfo){
		$startTime = microtime(true);
		if($cmsUserInfo['usergroup'] == 'cms'){
			if($this->input->post('selectedUserId',true)!=0){
				$clientId = $this->input->post('selectedUserId',true);
			}else if($clientUserId !="-1"){
				$clientId = $clientUserId;
			}else{
				$clientId = $cmsUserInfo['userid'];
			}
		}else{
			$clientId = $cmsUserInfo['userid'];
		}


		if($cmsUserInfo['usergroup'] == 'cms'){
			//CMS user logged in
			if($clientId == $cmsUserInfo['userid'] || $clientId == '1' || $clientId == ''){
				//CMS posting not on somebody else behalf
				$onBehalf = 'false';
			}
			else{
				//CMS posting on client behalf (assuming the clientId will be for some enterprise user only
				$onBehalf = 'true';
				$this->load->library('sums_product_client');
				$objSumsProduct =  new Sums_Product_client();
				$cmsUserInfo['myProducts'] = $objSumsProduct->getProductsForUser(1,array('userId'=>$clientId));
			}
		}
		else{
			//enterprise user posting himself
			$onBehalf = 'false';
		}
		$cmsUserInfo['onBehalfOf'] = $onBehalf;
		$cmsUserInfo['clientId'] = $clientId;
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $cmsUserInfo;
	}

	/**
	 * Show Institute form for inserting new institute
	 * @access  public
	 * @param   int $clientId
	 * @param   int $instituteId
	 * @return  string (view)
	 * @ToDo
	 */
	function showInstituteForm($clientUserId="-1",$instituteType=0)
	{
		$startTime = microtime(true);
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        //redirecting for enterprise usergroup
        if($cmsUserInfo['usergroup'] == 'enterprise') {
            header('Location:/enterprise/Enterprise/disallowedAccess');
            exit;
        }
		$cmsUserInfo = $this->checkFlowCase($clientUserId,$cmsUserInfo);
		$clientId = $cmsUserInfo['clientId'];
		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['pageTitle'] = "Add new institute.";
		$cmsPageArr['viewType'] = 1;
		$cmsPageArr['clientId'] = $clientId;
		$cmsPageArr['clientDetails'] = $this->_userDetails($clientId);

		$clientDetails = $this->_userDetails($clientId);
		if($clientDetails['usergroup']!='cms'){
			$clientStatus = $this->checkTotalListingsOfUser($clientId);
			$cmsPageArr['paidStatus']=$clientStatus['paidStatus'];
		}
		$cmsPageArr['clientDetails'] = $clientDetails;

		$cmsPageArr['subscriptionId'] = $this->input->post('selectedSubs');
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$cmsPageArr['clientId']));
		// _p($cmsPageArr['subscriptionDetails']); // die;

		$categoryClient = new Category_list_client();
		$cmsPageArr['country_list'] = $categoryClient->getCountries('1');

	    /*** change to hide abroad countries in national posting*****/

		$countryArrayToShowOnly = array();
		 foreach($cmsPageArr['country_list'] as $country){
		 	if($country['countryID'] <= 2) {
		 		$countryArrayToShowOnly[] = $country;
		 	}
		 }
		 
		$cmsPageArr['country_list'] = $countryArrayToShowOnly; 

		$zonemap = $categoryClient->getZones('1');
		$countVar = 0;
		foreach($zonemap AS $zone){
			$zoneList[$zone[$countVar]['zoneId'][0]] = $zone[$countVar]['zoneName'][0];
			$cityList[$zone[$countVar]['cityId'][0]][$zone[$countVar]['zoneId'][0]] = $zone[$countVar]['zoneName'][0];
			$localityList[$zone[$countVar]['zoneId'][0]][$zone[$countVar]['localityId'][0]] = $zone[$countVar]['localityName'][0];
			//  echo "<hr> zoneId = $zoneId, cityId = $cityId , localityId = $localityId , zoneName = $zoneName , localityName = $localityName";
		}

		$cmsPageArr['city_list'] = $cityList;
		// echo "<pre>"; print_r($localityList); echo "</pre>"; die;

		$cmsPageArr['zone_list'] = $zoneList;
		$cmsPageArr['locality_list'] = $localityList;

		$ListingClientObj = new Listing_client();
		$listing_type = 'institute';
		$cmsPageArr['wikiData'] = $ListingClientObj->getWikiFields('1',$listing_type);
		$cmsPageArr['flow'] = 'add';
		$cmsPageArr['formPostUrl'] = '/listing/posting/InstitutePost/post';
		$cmsPageArr['prodId'] = '7';
		//echo "<pre>";print_r($cmsPageArr);echo "</pre>";
		global $aimaRatings;
		$cmsPageArr['aimaRatings'] = $aimaRatings;
		
		global $sourceList;
		$cmsPageArr['sourceList'] = $sourceList;
		
		$cmsPageArr['instituteType'] = $instituteType;

		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/institutedetailsmodel');
		$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
		$facilitiesfields = $instituteDetailsModel->getInstituteFacilityFields();
		$cmsPageArr['facilitiesfields'] = $facilitiesfields;

        $this->load->library('OnlineFormEnterprise_client');
        $ofObj = new OnlineFormEnterprise_client();
        $cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);

		//_p($cmsPageArr);die;
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * Controller to Show Institute Edit Form
	 * @access  public
	 * @param   int $clientId
	 * @param   int $instituteId
	 * @return  string (view)
	 * @ToDo
	 */
	function editInstituteForm($instituteId='')
	{
		$startTime = microtime(true);
		ini_set('memory_limit', '256M');
		if($instituteId==''){
			header("location:/enterprise/Enterprise/index/7");
			exit;
		}

		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        //redirecting for enterprise usergroup
        if($cmsUserInfo['usergroup'] == "enterprise"){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}

		if(!preg_match('/^[0-9]+$/', $instituteId)){
			show_404();
		}

		$ListingClientObj = new Listing_client();
		$listing_type = 'institute';
        $isInstituteEditCase = 1;
				
		//$listingData = $ListingClientObj->getListingForEdit('1',$instituteId,$listing_type, "", $isInstituteEditCase);
		
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/institutedetailsmodel');
		$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
		$facilitiesfields = $instituteDetailsModel->getInstituteFacilityFields();
		$listingData = $instituteDetailsModel->getInstituteDetails($instituteId);
		
		//_p($listingData); exit();
                
		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
			show_404();
		}

		/*** Abroad Listing check *****/
		 $isNationalListing = $instituteDetailsModel->checkIfInstituteBelongsToNationalPosting($instituteId);
		 
		 if(!$isNationalListing) {
		 	header("location:/enterprise/Enterprise/disallowedAccess");
		 	exit();
		 }
		 
		// _p($listingData); die;
		if($cmsUserInfo['usergroup']!= "cms"){
			if($listingData[0]['userId'] != $cmsUserInfo['userid']){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}
		$clientId = $listingData[0]['userId'];
		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);
		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['viewType'] = 1;
		$cmsPageArr['clientId'] = $clientId;
		$cmsPageArr['status'] = $listingData[0]['status'];

		$categoryClient = new Category_list_client();
		$cmsPageArr['country_list'] = $categoryClient->getCountries('1');
        
		/*** change to hide abroad countries in national posting*****/
		$countryArrayToShowOnly = array();
		foreach($cmsPageArr['country_list'] as $country){
			if($country['countryID'] <= 2) {
				$countryArrayToShowOnly[] = $country;
			}
		}
			
		$cmsPageArr['country_list'] = $countryArrayToShowOnly;

		foreach($listingData[0] as $key => $val){
			$cmsPageArr[$key] = $val;
		}
		// echo "cmsPageArr = <pre>"; print_r($cmsPageArr); echo "</pre>";
		$cmsPageArr['pageTitle'] = "Editing ".$cmsPageArr['title'];
		$wikiFields = unserialize(base64_decode($listingData[0]['wikiFields']));
		$reformattedFields = systemAndUserFieldsDataSegregation($wikiFields);
		$cmsPageArr['systemFieldsArr'] = $reformattedFields['systemFieldsArr'];
		$cmsPageArr['userFieldsArr'] = $reformattedFields['userFieldsArr'];
		$cmsPageArr['wikiData'] = $ListingClientObj->getWikiFields('1',$listing_type);

		$cmsPageArr['clientDetails'] = $this->_userDetails($clientId);
		$cmsPageArr['flow'] = 'edit';
		//$cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/editInstitute';
		$cmsPageArr['formPostUrl'] = '/listing/posting/InstitutePost/post';
		$cmsPageArr['institute_id'] = $instituteId;
		global $aimaRatings;
		$cmsPageArr['aimaRatings'] = $aimaRatings;
		global $sourceList;
		$cmsPageArr['sourceList'] = $sourceList;
		$cmsPageArr['prodId'] = '7';
		$zonemap = $categoryClient->getZones('1');
		// echo "zone map = <pre>"; print_r($zonemap); echo "</pre>";
		$countVar = 0;
		foreach($zonemap AS $zone){
			/*$zoneList[$zone['zoneId']] = $zone['zoneName'];
			 $cityList[$zone['cityId']][$zone['zoneId']] = $zone['zoneName'];
			 $localityList[$zone['zoneId']][$zone['localityId']] = $zone['localityName'];
			 *
			 */
			$zoneList[$zone[$countVar]['zoneId'][0]] = $zone[$countVar]['zoneName'][0];
			$cityList[$zone[$countVar]['cityId'][0]][$zone[$countVar]['zoneId'][0]] = $zone[$countVar]['zoneName'][0];
			$localityList[$zone[$countVar]['zoneId'][0]][$zone[$countVar]['localityId'][0]] = $zone[$countVar]['localityName'][0];
		}
		// echo "zone List = <pre>"; print_r($zoneList); echo "</pre>";
		$cmsPageArr['city_list'] = $cityList;
		$cmsPageArr['zone_list'] = $zoneList;
		$cmsPageArr['locality_list'] = $localityList;

		$cmsPageArr['insttitle'] = $listingData[0]['title'];
		$cmsPageArr['institute_request_brochure_year'] = $listingData[0]['institute_request_brochure_year'];                		
		$this->load->library('OnlineFormEnterprise_client');
		$ofObj = new OnlineFormEnterprise_client();
		$cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);
		
		if($listingData[0]['instituteType'] == 1) {
			$this->load->library('listing/ListingProfileLib');
			$cmsPageArr['score_array']  = $this->listingprofilelib->calculateProfileCompeletion($instituteId);
                }
		
		$cmsPageArr['facilitiesfields'] = $facilitiesfields;
		$cmsPageArr['facilitiesfieldvalues'] = $listingData[0]['instituteFacilities'];
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	/**
	 * Controller to Show Institute Upgrade Form
	 * @access  public
	 * @param   int $clientId
	 * @param   int $instituteId
	 * @return  string (view)
	 * @ToDo
	 */
	function upgradeListingClientSearchForm($instituteId='',$listing_type)
	{
		$startTime = microtime(true);
		if (empty($instituteId)) {
			$instituteId = $this->input->post('instituteId',true);
		}

		if($instituteId=='' || $listing_type == 'institute'){
			header("location:/enterprise/Enterprise/index/7");
			exit;
		}
		$this->init();
		$data['cmsUserInfo'] = $this->cmsUserValidation();
		if($data['cmsUserInfo']['usergroup']!='cms'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$data['extraInfoArray'] = $extraInfoArray;
		$data['forListingPost'] = true;
		$data['instituteId'] = $instituteId;
		$data['listingType']=$listing_type;
		$data['prodId'] = 7;
		if($listing_type=='institute')
		{
			$data['flag_listing_upgrade'] = '1';
		}
		else
		{
			$request_array = array('listing_type_id'=>$instituteId,'listing_type'=>$listing_type);
			$this->load->model('ListingModel');
			$model_object = new ListingModel();
			$result = $model_object->getListingClientInfo($request_array);
			$data['owner_info'] = 0;
			$data['flag_listing_upgrade'] = '2';
			if(count($result)>0) {
				$data['owner_info'] = addslashes(json_encode($result));
			}
		}
		if($listing_type == 'course'){
			$data['courseId'] = $instituteId;
		}
		$this->load->view('enterprise/userSelect',$data);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}


	function upgradeInstituteForm($instituteId='')
	{
		$startTime = microtime(true);
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		/* START UPGRADE LISTING */
		$upgrade_onBehalfOf = $this->input->post('onBehalfOf',true);
		$upgrade_instituteId = $this->input->post('instituteId',true);
		$upgrade_flag_listing_upgrade = $this->input->post('flag_listing_upgrade',true);
		$upgrade_client_id = $this->input->post('selectedUserId',true);
		/* END UPGRADE LISTING */
		/* START UPGRADE LISTING */
		if ($upgrade_flag_listing_upgrade == '1') {
			$instituteId = $upgrade_instituteId;
		}
		/* END UPGRADE LISTING */

		$ListingClientObj = new Listing_client();
		$listing_type = 'institute';
		$listingData = $ListingClientObj->getListingForEdit('1',$instituteId,$listing_type);
                
		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }

		if($cmsUserInfo['usergroup']!= "cms"){
			if($listingData[0]['userId'] != $cmsUserInfo['userid']){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}
		$clientId = $listingData[0]['userId'];
		/* START UPGRADE LISTING */
		if ($upgrade_flag_listing_upgrade == '1') {
			$clientId = $upgrade_client_id;
		}
		/* END UPGRADE LISTING */

		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);
		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['viewType'] = 1;
		$cmsPageArr['clientId'] = $clientId;

		$categoryClient = new Category_list_client();
		$cmsPageArr['country_list'] = $categoryClient->getCountries('1');

		$zonemap = $categoryClient->getZones('1');
		$countVar = 0;
		foreach($zonemap AS $zone){
			/*$zoneList[$zone['zoneId']] = $zone['zoneName'];
			 $cityList[$zone['cityId']][$zone['zoneId']] = $zone['zoneName'];
			 $localityList[$zone['zoneId']][$zone['localityId']] = $zone['localityName'];
			 * */
			$zoneList[$zone[$countVar]['zoneId'][0]] = $zone[$countVar]['zoneName'][0];
			$cityList[$zone[$countVar]['cityId'][0]][$zone[$countVar]['zoneId'][0]] = $zone[$countVar]['zoneName'][0];
			$localityList[$zone[$countVar]['zoneId'][0]][$zone[$countVar]['localityId'][0]] = $zone[$countVar]['localityName'][0];
		}
		$cmsPageArr['city_list'] = $cityList;
		$cmsPageArr['zone_list'] = $zoneList;
		$cmsPageArr['locality_list'] = $localityList;

		foreach($listingData[0] as $key => $val){
			$cmsPageArr[$key] = $val;
		}
		$cmsPageArr['pageTitle'] = "Upgrading ".$cmsPageArr['title'];
		$wikiFields = unserialize(base64_decode($listingData[0]['wikiFields']));
		$reformattedFields = systemAndUserFieldsDataSegregation($wikiFields);
		$cmsPageArr['systemFieldsArr'] = $reformattedFields['systemFieldsArr'];
		$cmsPageArr['userFieldsArr'] = $reformattedFields['userFieldsArr'];
		$cmsPageArr['wikiData'] = $ListingClientObj->getWikiFields('1',$listing_type);

		$cmsPageArr['clientDetails'] = $this->_userDetails($clientId);
		$cmsPageArr['flow'] = 'upgrade';
		$cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/editInstitute';
		$cmsPageArr['institute_id'] = $instituteId;
		$cmsPageArr['prodId'] = '7';
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$cmsPageArr['clientId']));

        $this->load->library('OnlineFormEnterprise_client');
        $ofObj = new OnlineFormEnterprise_client();
        $cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);

        $this->load->view('listing_forms/new_homepage',$cmsPageArr);
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}


	/**
	 * getLastActionMessage
	 * Common Function to Show Msg on different CRUD Action
	 * @access  public
	 * @param   int $code
	 * @return  array
	 * @ToDo
	 */
	function getLastActionMessage($code){
		$retArr = array();
		switch($code){
			case '1':
				$retArr['successResponse'] =1;
				$retArr['successResponseText'] ='Institute has been saved. Add Course to this institute';
				break;
			case '2':
				$retArr['successResponse'] =1;
				$retArr['successResponseText'] ='Course has been saved. Add another course.';
				break;
			case '3':
				$retArr['successResponse'] =1;
				$retArr['successResponseText'] ='Institute has been edited. Add course to this institute.';
				break;

		}
		return $retArr;
	}


	/**
	 * getCategories
	 * Common Function to Show Course Form
	 * @access  private
	 * @param   int $appId // Do'nt know why it is Hard coded ?
	 * @return  array array of categories
	 * @ToDo
	 */
	private function getCategories(){
		$startTime = microtime(true);
		$appId = 12;
		$this->init();
		$categoryClient = new Category_list_client();
		$categoryList = $categoryClient->getCategoryTree($appId);
		$others = array();
		$categoryForLeftPanel = array();
		foreach($categoryList as $temp)
		{
			if((stristr($temp['categoryName'],'Others') == false))
			{
				$categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
			}
			else
			{
				$others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
			}
		}
		foreach($others as $key => $temp)
		{
			$categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $categoryForLeftPanel;
	}

	/**
	 * setFlags
	 * Common Function For Enterprise User Flow
	 * @access  public
	 * @param   array $cmsUserInfo ACL Array
	 * @return  array
	 * @ToDo
	 */
	function setFlags($cmsUserInfo){
		if($cmsUserInfo['usergroup'] == 'cms'){
			$flagArray['moderation'] = 1;
			if($this->input->post('onBehalfOf') == 'true'){
				$flagArray['userid'] = $this->input->post('clientId');
				$flagArray['packType'] = $this->input->post('required_packtype');
			}
			else{
				$flagArray['userid'] = $cmsUserInfo['userid'];
				$flagArray['packType'] = BRONZE_LISTINGS_BASE_PRODUCT_ID;
			}
		}
		else{
			$flagArray['moderation'] = 0;
			$flagArray['userid'] = $cmsUserInfo['userid'];
                        $required_type = $this->input->post('required_packtype');
			$flagArray['packType'] = GOLD_SL_LISTINGS_BASE_PRODUCT_ID;
                        if(!empty($required_type)) {
				$flagArray['packType'] = $required_type;
                        }
		}
                error_log('hohohohohohohoohohoohhhhhh'.print_r($flagArray,true));
		return $flagArray;
	}

	/**
	 * postDataForInstitute
	 * Common Function For CRUD Operation in Institute
	 * @access  public
	 * @param   array $_REQUEST
	 * @return  array Array having processed _POST data
	 * @ToDo
	 */
	function postDataForInstitute(){
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		$ListingClientObj = new Listing_client();

		$data['dataFromCMS']=$flagArray['moderation'];
		$data['packType']=$flagArray['packType'];
		$data['username']=$flagArray['userid'];
		$data['subscriptionId']=$this->input->post('selectedSubs');
		$data['clientId'] =$this->input->post('clientId');
		$data['onBehalfOf'] =$this->input->post('onBehalfOf');
		$data['institute_request_brochure_link_year'] = $this->input->post('c_brochure_panel_year',true);
		$data['request_brochure_link_delete'] = $this->input->post('request_brochure_link_delete',true);
		//Basic University details
		$data['institute_name'] =$this->input->post('c_institute_name');
		//        $data['institute_name'] .= rand(1,100);
		if(strlen($this->input->post('deemedUniversity')) > 0){
			if($this->input->post('deemedUniversity') == 'deemedUniversity'){
				$data['affiliated_to'] = 'Deemed University';
			}
			else{
				$data['affiliated_to'] = $this->input->post('affiliated_to');
			}
		} else {
			$data['affiliated_to'] = '';
		}
		if ($this->input->post('deemedUniversity_clear_selection') == '-1') {
			$data['affiliated_to'] = '';
		}
		$data['establish_year'] = $this->input->post('i_establish_year');
		if($cmsUserInfo['usergroup'] == 'cms'){
			$data['hiddenTags'] = $this->input->post('i_tags');
		}
		$data['abbreviation'] = $this->input->post('c_abbreviation');
		$data['aima_rating'] = $this->input->post('i_aima_rating');
	
		$data['usp'] = $this->input->post('i_usp');
	
		$source_type = trim($this->input->post('i_source_type'));
		$source_name = trim($this->input->post('i_source_name'));

		$flow_check = trim($this->input->post('flow',true));

		if(!(empty($source_name)) && !(empty($source_type)) || ($flow_check == 'edit') && empty($source_name) && empty($source_type)){
				$data['source_name'] = $source_name;
				$data['source_type'] = $source_type;	
		}	
	
		//Institute Type
		$data['insituteType'] = $this->input->post('insituteType');

		//Testprep extras
		$data['admission_counseling'] = $this->input->post('admission_counseling');
		$data['visa_assistance'] = $this->input->post('visa_assistance');

		//Seo Fields
		$data['listing_seo_url'] = $this->input->post('listing_seo_url');
		$data['listing_seo_title'] = $this->input->post('listing_seo_title');
		$data['listing_seo_description'] = $this->input->post('listing_seo_description');
		$data['listing_seo_keywords'] = $this->input->post('listing_seo_keywords');
		
		//mandatory comments
		$data['mandatory_comments'] = $this->input->post('mandatory_comments');
		$data['cmsTrackUserId'] = $this->input->post('cmsTrackUserId');
		$data['cmsTrackListingId'] = $this->input->post('cmsTrackListingId');
		$data['cmsTrackTabUpdated'] = $this->input->post('cmsTrackTabUpdated');

		// By Amit K for Multilocation Support..
		$innerSeparatorChar = "|=#=|";
		$outerSeparatorChar = "||++||";
		$contactInfoArray = explode($outerSeparatorChar, $this->input->post('contactInfoHiddenVar'));
		$locationInfoArray = explode($outerSeparatorChar, $this->input->post('locationInfoHiddenVar'));
		$multilocationCount = count($contactInfoArray);

		for($i = 0; $i < $multilocationCount; $i++) {
			$contactInfoSubArray[$i] = explode($innerSeparatorChar, $contactInfoArray[$i]);
			$data['contactInfo'][$i]['contact_person_name'] = $contactInfoSubArray[$i][0];
			$data['contactInfo'][$i]['main_phone_number'] = $contactInfoSubArray[$i][1];
			$data['contactInfo'][$i]['mobile_number'] = $contactInfoSubArray[$i][2];
			$data['contactInfo'][$i]['alternate_phone_number'] = $contactInfoSubArray[$i][3];
			$data['contactInfo'][$i]['fax_number'] = $contactInfoSubArray[$i][4];
			$data['contactInfo'][$i]['contact_person_email'] = $contactInfoSubArray[$i][5];
			$data['contactInfo'][$i]['website'] = $contactInfoSubArray[$i][6];

			$locationInfoSubArray[$i] = explode($innerSeparatorChar, $locationInfoArray[$i]);
			$data['locationInfo'][$i]['country_id'] = $locationInfoSubArray[$i][0];
			$data['locationInfo'][$i]['city_id'] = $locationInfoSubArray[$i][1];
			$data['locationInfo'][$i]['zone_id'] = $locationInfoSubArray[$i][2];
			$data['locationInfo'][$i]['locality_id'] = $locationInfoSubArray[$i][3];
			$data['locationInfo'][$i]['address1'] = $locationInfoSubArray[$i][4];
			$data['locationInfo'][$i]['address2'] = $locationInfoSubArray[$i][5];
			$data['locationInfo'][$i]['locality_name'] = $locationInfoSubArray[$i][6];
			$data['locationInfo'][$i]['city_name'] = $locationInfoSubArray[$i][7];
			$data['locationInfo'][$i]['pin_code'] = $locationInfoSubArray[$i][8];
			$data['flow']=$this->input->post('flow');
			if(isset($locationInfoSubArray[$i][9]) && $locationInfoSubArray[$i][9] != "") {
				$data['locationInfo'][$i]['institute_location_id'] = $locationInfoSubArray[$i][9];
			}
		}

		// error_log("\n\n_post data ".print_r($data['contactInfo'],true)."\n Location Info: ".print_r($data['locationInfo'],true),3,'/home/infoedge/Desktop/log.txt');die;

		//Why Join Institute
		$data['photo_title'] = $this->input->post('photo_title');
		$data['details'] = $this->input->post('details');
		$data['wiki'] = array();
		//Wiki Section additions
		$wikiData = $ListingClientObj->getWikiFields('1','institute');
		foreach($wikiData as $wikiField){
			$wikiField_key_name = trim($this->input->post($wikiField['key_name']));
			if($data['flow'] == 'edit'){
				if(empty($wikiField_key_name)){
					$wikiField_key_name = ' ';
				}	
			}
			if(strlen($wikiField_key_name) > 0){
			//error_log("trying ".$wikiField['key_name'],3,'/home/naukri/Desktop/log.txt');
				try{
					$data['wiki'][$wikiField['key_name']]=is_text_in_html_string($wikiField_key_name)?trimmed_tidy_repair_string($wikiField_key_name):'';
				//error_log("trying pass ",3,'/home/naukri/Desktop/log.txt');
				}catch (Exception $ex){
				}
			}
		}
		//error_log("post data ".print_r($data,true),3,'/home/naukri/Desktop/log.txt');
		$userCreatedWikisCaptions = $this->input->post('wikkicontent_main');
		$userCreatedWikisDetails = $this->input->post('wikkicontent_detail');

		$data['wiki']['user_fields'] = array();
		for($i = 0; $i < count($userCreatedWikisCaptions); $i++){
			if (( $userCreatedWikisCaptions[$i] != 'Enter Title' ) && ( $userCreatedWikisDetails[$i] != 'Enter Description' ) ) {
				$data['wiki']['user_fields'][$i]['caption']=trimmed_tidy_repair_string($userCreatedWikisCaptions[$i]);
				$data['wiki']['user_fields'][$i]['caption'] = str_replace("\n", " ", $data['wiki']['user_fields'][$i]['caption']);
				$data['wiki']['user_fields'][$i]['value']=is_text_in_html_string($userCreatedWikisDetails[$i]) ?trimmed_tidy_repair_string($userCreatedWikisDetails[$i]):'';
			}
		}
	if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $data;
	}

	/**
	 * instiLogoAndPanelUpload
	 * Function For Upload institute Logo etc.
	 * @access  public
	 * @param   array $_FILE
	 * @return  array Instittue Logo and Panel Arrays with 'removal' action information
	 * @ToDo
	 */
	function instiLogoAndPanelUpload(){
		$startTime = microtime(true);
		$appId =1;
		$logoArr = array();
		$panelArr = array();
		$photoArr = array();
		$this->load->library('upload_client');
		$uploadClient = new Upload_client();
		// Block to Upload Institute Logo and Panel
		$arrCaption = $this->input->post('c_insti_logo_caption');
		$inst_logo= array();
		for($i=0;$i<count($_FILES['i_insti_logo']['name']);$i++){
			$inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_insti_logo']['name'][$i];
		}
		if(!(isset($_FILES['i_insti_logo']['tmp_name'][0]) && ($_FILES['i_insti_logo']['tmp_name'][0] != '')) && ($this->input->post('logoRemoved')==1))
		{
			$logoArr['thumburl'] = "";
		}else if(isset($_FILES['i_insti_logo']['tmp_name'][0]) && ($_FILES['i_insti_logo']['tmp_name'][0] != ''))
		{
			$i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,"-1","institute",'i_insti_logo');
			if($i_upload_logo['status'] == 1)
			{
				for($k = 0;$k < $i_upload_logo['max'] ; $k++)
				{

					$tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
					list($width, $height, $type, $attr) = $tmpSize;
					$logoArr['width']=$width;
					$logoArr['height']=$height;
					$logoArr['type']=$type;
					$logoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
					$logoArr['url']=$i_upload_logo[$k]['imageurl'];
					$logoArr['title']=$i_upload_logo[$k]['title'];
					$logoArr['thumburl']=$i_upload_logo[$k]['imageurl'];
				}
			} else{
				$logoArr['error'] = $i_upload_logo;
				$logoArr['thumburl'] = "";
			}
		}

		$arrCaption = $this->input->post('c_feat_panel_caption');
		$inst_logo= array();
		for($i=0;$i<count($_FILES['i_feat_panel']['name']);$i++){
			$inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_feat_panel']['name'][$i];
		}
		if(!(isset($_FILES['i_feat_panel']['tmp_name'][0]) && ($_FILES['i_feat_panel']['tmp_name'][0] != '')) && ($this->input->post('panelRemoved')==1))
		{
			$panelArr['thumburl'] = "";
		}
		else if(isset($_FILES['i_feat_panel']['tmp_name'][0]) && ($_FILES['i_feat_panel']['tmp_name'][0] != ''))
		{
			$i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,"-1","featured",'i_feat_panel');
			if($i_upload_logo['status'] == 1)
			{
				for($k = 0;$k < $i_upload_logo['max'] ; $k++)
				{
					$tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
					list($width, $height, $type, $attr) = $tmpSize;
					$panelArr['width']=$width;
					$panelArr['height']=$height;
					$panelArr['type']=$type;
					$panelArr['mediaid']=$i_upload_logo[$k]['mediaid'];
					$panelArr['url']=$i_upload_logo[$k]['imageurl'];
					$panelArr['title']=$i_upload_logo[$k]['title'];
					$panelArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
				}
			}else{
				$panelArr['error'] = $i_upload_logo;
				$panelArr['thumburl'] = "";
			}
		}
		$arrCaption = $this->input->post('photo_title');
		$inst_logo= array();
		//		error_log("file ".print_r($_FILES,true),3,'/home/naukri/Desktop/log.txt');
		for($i=0;$i<count($_FILES['photo']['name']);$i++){
			$inst_logo[$i] = ($arrCaption!="")?$arrCaption:$_FILES['photo']['name'][$i];
		}
		if(!(isset($_FILES['photo']['tmp_name'][0])) && ($_FILES['photo']['tmp_name'][0] != '')){
			$photoArr['thumburl'] = "";
		}
		else if(isset($_FILES['photo']['tmp_name'][0]) && ($_FILES['photo']['tmp_name'][0] != ''))
		{
			$i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,"-1","photo",'photo');
			//			error_log("logo array ".print_r($i_upload_logo,true),3,'/home/naukri/Desktop/log.txt');
			if($i_upload_logo['status'] == 1)
			{
				for($k = 0;$k < $i_upload_logo['max'] ; $k++)
				{
					$tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
					list($width, $height, $type, $attr) = $tmpSize;
					$photoArr['width']=$width;
					$photoArr['height']=$height;
					$photoArr['type']=$type;
					$photoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
					$photoArr['url']=$i_upload_logo[$k]['imageurl'];
					$photoArr['title']=$i_upload_logo[$k]['title'];
					$photoArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
					//					error_log("photo array ".print_r($photoArr,true),3,'/home/naukri/Desktop/log.txt');
				}
			}else{
				$photoArr['error'] = $i_upload_logo;
				$photoArr['thumburl'] = "";
			}
		}
		$response['logoArr'] = $logoArr;
		$response['panelArr'] = $panelArr;
		$response['photoArr'] = $photoArr;
		//							error_log("photo array ".print_r($response,true),3,'/home/naukri/Desktop/log.txt');
		//		error_log('photo upload '.print_r($response,true));
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $response;
	}

	/**
	 * addInstitute
	 * Function For adding Institute info. in the backend
	 * @access  public
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  string view of adding course to college
	 * @ToDo
	 */
	function addInstitute()
	{
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();

		$ListingClientObj = new Listing_client();
		$data = array();
		$data = $this->postDataForInstitute();
		
		$data['editedBy'] = $cmsUserInfo['userid'];
        $data['group_to_be_checked'] = $cmsUserInfo['usergroup'];

		$logoAndPanelResp = $this->instiLogoAndPanelUpload();
        $requestBrochureResp = $this->_instituteRequestBrochureUpload();
		if(array_key_exists('Fail', $requestBrochureResp)) {
			echo json_encode($requestBrochureResp);
			exit;
		}
		//error_log("image ".print_r($logoAndPanelResp,true),3,'/home/infoedge/Desktop/log.txt');
		$data['logoArr'] = $logoAndPanelResp['logoArr'];
		$data['panelArr'] = $logoAndPanelResp['panelArr'];
		$data['photoArr'] = $logoAndPanelResp['photoArr'];
        $data['institute_request_brochure_link'] = $requestBrochureResp;
		$exitFlag = false;
		$logoPanelRespArray = array();
				
		/*
		 *	Lets check if the subscription selected has not been consumed while posting this listing..
		 *	This is to ensure that expiry_date should get filled properly (at Listing Publishing) in listtings_main table.
		 */		
		$validateSubscriptionFlag = $this->validateSubscriptionInfo($data['group_to_be_checked'], $data['onBehalfOf'], $data['subscriptionId'], $data['clientId']);
        if($validateSubscriptionFlag[0] == 0) {
            $logoPanelRespArray["Fail"]['subscription_issue'] = $validateSubscriptionFlag[1];
			$logoPanelRespArray['subscription_id'] = $validateSubscriptionFlag[2];
            $exitFlag = true;
        }
				
		// Validate all the locations now..
		$validateLocationFlag = $this->validateLocationInfo($data['locationInfo'], 'institute');
		if($validateLocationFlag[0] == 0) {
			$logoPanelRespArray["Fail"]['location_issue'] = $validateLocationFlag[1];
			$exitFlag = true;
		}

		if(isset($logoAndPanelResp['logoArr']['error'])) {
			$logoPanelRespArray["Fail"]['logo'] = 'Only '. $logoAndPanelResp['logoArr']['error'];
			$exitFlag = true;
		}
		if(isset($logoAndPanelResp['panelArr']['error'])) {
			$logoPanelRespArray["Fail"]['panel'] = 'Only '. $logoAndPanelResp['panelArr']['error'];
			$exitFlag = true;
		}
		if(isset($logoAndPanelResp['photoArr']['error'])) {
			$logoPanelRespArray["Fail"]['photo'] = 'Only '. $logoAndPanelResp['photoArr']['error'];
			$exitFlag = true;
		}
		if (isset($logoAndPanelResp['photoArr']['width']) && isset($logoAndPanelResp['photoArr']['height'])) {
			$logoAndPanelRespWidth = $logoAndPanelResp['photoArr']['width'];
			$logoAndPanelRespHeight = $logoAndPanelResp['photoArr']['height'];
			//	        error_log("image size $logoAndPanelRespWidth x $logoAndPanelRespHeight",3,'/home/naurki/Desktop/log.txt');
			if (($logoAndPanelRespWidth  != 252) &&  ($logoAndPanelRespHeight != 103)) {
				$logoPanelRespArray["Fail"]['photo'] = "Please upload a photo with height equal to 103 pixels and width equal to 252 pixels";
				$exitFlag = true;
			}
			//$exitFlag = true;
		}

		if ((isset($logoAndPanelResp['logoArr'])) && (count($logoAndPanelResp['logoArr']) > 0)) {
			$logoAndPanelRespWidth = (int)$logoAndPanelResp['logoArr']['width'];
			$logoAndPanelRespHeight = (int)$logoAndPanelResp['logoArr']['height'];

			if (($logoAndPanelRespWidth  > 340) &&  ($logoAndPanelRespHeight > 65)) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height less than or equal to 65 pixels and width less than or equal to 340 pixels";
				$exitFlag = true;
			}
			if (($logoAndPanelRespWidth  < 60) &&  ($logoAndPanelRespHeight < 40)) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height more than or equal to 40 pixels and width more than or equal to 60 pixels";
				$exitFlag = true;
			}
			if ($logoAndPanelRespWidth  < 60) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with width more than or equal to 60 pixels.";
				$exitFlag = true;
			}
			if ($logoAndPanelRespWidth  > 340) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with width less than or equal to 340 pixels.";
				$exitFlag = true;
			}
			if ($logoAndPanelRespHeight < 40) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height more than or equal to 40 pixels.";
				$exitFlag = true;
			}
			if ($logoAndPanelRespHeight > 65) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height less than or equal to 65 pixels";
				$exitFlag = true;
			}
		}
		if($exitFlag) {
			echo json_encode($logoPanelRespArray);
			exit;
		}
		echo $this->addInstituteInfo($data,1);

		$showDetailsFlags = $this->getDetailPageFlags($data['subscriptionId']);
		$data = array_merge($data,$showDetailsFlags);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		//echo "<pre>";echo print_r($data); echo "</pre>";
	}
	
	private function validateSubscriptionInfo($userGroup, $onBehalfOf, $subscriptionId, $clientId) {
		$startTime = microtime(true);
		// error_log("\n\n YES in validateSubscriptionInfo, userGroup = ".$userGroup.", onBehalfOf = ".$onBehalfOf.", subscriptionId = ".$subscriptionId.", cid = ".$clientId,3,'/home/amitkuksal/Desktop/log.txt');
		$responseArray[0] = 1;
		if(!($userGroup == 'cms' && $onBehalfOf == "false" ) && $subscriptionId != "") {
			$objSumsProduct = $this->load->library('sums_product_client');
			$subscriptions = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));
			if(is_array($subscriptions[$subscriptionId])) {
				$chosenSubsArray = $subscriptions[$subscriptionId];
			} else {
				$chosenSubsArray = "";
			}

			if(!(is_array($chosenSubsArray) && $chosenSubsArray['BaseProdPseudoRemainingQuantity'] > 0)) {
                            $responseArray[0] = 0;
			    $responseArray[1] = 'Your chosen subscription has been consumed with other listings. Please select some other subscription to proceed.';
			    $responseArray[2] = $subscriptionId;				
			}
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $responseArray;
	}

	/**
	 * addInstituteInfo
	 * Function For Add Institute info. in the backend
	 * @access  private
	 * @param   array $data $jsonReturn
	 * @return  array of flags to indicate suceess or failure of add institute.
	 */
	private function addInstituteInfo($data,$jsonReturn = 0){
		// error_log("Data in addInstituteInfo ".print_r($data,true),3,'/home/infoedge/Desktop/log.txt');
		$ListingClientObj = new Listing_client();
		$msgbrdClient = new message_board_client();
		$msgTxt = "You can discuss about ".$data['institute_name']." here.";
		$topicResult = $msgbrdClient->addTopic(1,1,$msgTxt,1,S_REMOTE_ADDR,'group');
		$data['threadId']= $topicResult['ThreadID'];

		$response = $ListingClientObj->newAddInstitute('1',$data);
                //error_log('in gandh'.print_r($data,true));
		if(is_array($response)){
			if($response['listing_id'] > 0 && $response['institute_id'] > 0)
			{
				if(!($data['group_to_be_checked'] == 'cms' && $data['onBehalfOf'] == "false" )) {
                                   // error_log('in gandh1');
                                    //if($data['clientId'] != 11) {
					$this->load->library('Subscription_client');
					$subsObj = new Subscription_client();
					$resp = $subsObj->consumePseudoSubscription($appId,$data['subscriptionId'],'-1',$data['clientId'],$data['editedBy'],'-1',$response['institute_id'],'institute','-1','-1');
                                   // }

				}
				//header("location:/enterprise/ShowForms/showCourseForm/".$response['institute_id']."/1");
				if($jsonReturn == 1){
					// return json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$response['institute_id']."/1"));
					$responseToReturn = json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$response['institute_id']."/1"));
					return $responseToReturn;

				}else{
					return array('Result' => 'Success','institute_id' => $response['institute_id']);
				}
			}
			else{

				if($response['listing_id'] < 0){
					if($jsonReturn == 1){
						return json_encode(array('Fail' => array('common' => 'Duplicate Listing !!!')));
					}else{
						return array('Result' => 'Duplicate');
					}
				}
			}
		}else{
			if($jsonReturn){
				return json_encode(array('Fail' => array('common' => 'Some issue with institute listing !!! Please try reposting.')));
			}else{
				return array('Result' => 'Failed');
			}
		}
	}

	function selectCopyInstitute($instituteId){
		$startTime = microtime(true);
		$this->init();
		$packTypeArrayForSubscriptionSelection = array(1,2,7);
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();
		if($cmsUserInfo['usergroup'] !== 'cms'){
			header('Location:/enterprise/Enterprise/index/7');
			exit;
		}
		$ListingClientObj = new Listing_client();
		$listingDetails = $ListingClientObj->getListingDetails($appId,$instituteId,'institute');
		$packTypeOfListingToBeCopied = is_array($listingDetails[0])?$listingDetails[0]['packType']:0;
		$data['subscriptionStrict'] = 'true';
		if (!in_array($packTypeOfListingToBeCopied,$packTypeArrayForSubscriptionSelection)) {
			$data['subscriptionStrict'] = 'false';
		}
		$data['listingLocation'] = is_array($listingDetails[0])?$listingDetails[0]['locations']:0;
		$data['packTypeArrayForSubscriptionSelection'] = $packTypeArrayForSubscriptionSelection;
		$data['listingTitle'] = is_array($listingDetails[0])?$listingDetails[0]['title']:"";
		$listngOwnerId = is_array($listingDetails[0])?$listingDetails[0]['userId']:0;
		$instituteUser = $this->_userDetails($listngOwnerId);
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		//	$SubscriptionArray= $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$listngOwnerId));
		$SubscriptionArray= $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$listngOwnerId));
		$categoryClient = new Category_list_client();
		$data['country_list'] = $categoryClient->getCountries('1');
		$data['SubscriptionArray'] = $SubscriptionArray;
		$data['cmsUserInfo'] = $cmsUserInfo;
		$data['headerTabs'] = $data['cmsUserInfo']['headerTabs'];
		$data['noOfCopies'] = $this->noOfCopies;
		$data['instituteId'] = $instituteId;
		$data['listngOwnerId'] = $listngOwnerId;
		$data['contact_email'] = isset($listingDetails[0]['contact_email'])?$listingDetails[0]['contact_email']:'';
		$data['validateuser'] = $this->checkUserValidation();
		$data['prodId']=7;
		$this->load->view('listing_forms/selectCopyInstitute',$data);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * submitCopyInstitute
	 * Function For Edit Institute info. in the backend
	 * @access  public
	 * @param   array $_POST
	 * @return  string view of adding course to edited college
	 * @ToDo
	 */
	function submitCopyInstitute(){
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		$instituteId = $this->input->post('instituteId');
		$ListingClientObj = new Listing_client();
		$Result = $ListingClientObj->getListingForEdit('1',$instituteId,'institute');
		$listingData = is_array($Result[0])?$Result[0]:array();
		$noOfCopies = $this->input->post('noOfCopiesMade');
		$copiesCancled = explode(",",$this->input->post('copiesCancled'));
		$wikiFields = unserialize(base64_decode($listingData['wikiFields']));
		$reformattedFields = systemAndUserFieldsDataSegregation($wikiFields);
		$logoArr = array();$panelArr=array();$data = array();$copyListingMappingArray = array();
		$copyListingMappingArray['originalListingId'] = $instituteId;
		$copyListingMappingArray['originalListingType'] = 'institute';
		$copyListingMappingArray['addedBy'] = $cmsUserInfo['userid'];
		$data['clientId'] =$this->input->post('clientId');
		$data['editedBy'] = $cmsUserInfo['userid'];
		$mediaUpdateUserInfo = array();
		$mediaUpdateUserInfo['dataFromCMS']=$flagArray['moderation'];
		$mediaUpdateUserInfo['packType']=$flagArray['packType'];
		$mediaUpdateUserInfo['username']=$flagArray['userid'];

		//For logo
		$logoArr['thumburl']=$listingData['institute_logo'];
		$panelArr['thumburl']=$listingData['featured_panel'];
		$data['logoArr'] = $logoArr;
		$data['panelArr'] = $panelArr;
		$data['dataFromCMS']=$listingData['moderation'];
		$data['packType']=$listingData['packType'];
		$data['username']=$listingData['userId'];
		$data['onBehalfOf'] =true;
		//Basic University details
		$data['affiliated_to'] = $listingData['certification'];
		$data['establish_year'] = $listingData['establish_year'];
		$data['hiddenTags'] = $listingData['hiddenTags'];
		//Contact Info
		$data['contact_name']=$listingData['contact_name'];
		$data['contact_cell']=$listingData['contact_cell'];
		$data['contact_alternate_phone']=$listingData['contact_alternate_phone'];
		$data['contact_fax']=$listingData['contact_fax'];
		$data['url'] = $listingData['url'];

		//Wiki Section additions
		$wikiData = $ListingClientObj->getWikiFields('1','institute');
		foreach($reformattedFields['systemFieldsArr'] as $temp){
			foreach($wikiData as $wikiField){
				if($temp['key_name'] == $wikiField['key_name']){
					$wikiDesc = str_replace(array("\n","\r","\n\r"),"",trim($temp['attributeValue']));
					$data['wiki'][$wikiField['key_name']]=is_text_in_html_string($temp['attributeValue'])?trimmed_tidy_repair_string($temp['attributeValue']):'';
				}
			}
		}
		$i=0;
		foreach($reformattedFields['userFieldsArr'] as $temp){
			$data['wiki']['user_fields'][$i]['caption']=$temp['caption'];
			$data['wiki']['user_fields'][$i]['value']=$temp['attributeValue'];
			$i++;
		}
		//Media Info
		$ResultMediaInfo = unserialize(base64_decode($listingData['mediaInfo']));
		$i = 0;
		$tempMediaInfo = array();
		foreach($ResultMediaInfo as $key => $temp){
			$temp['keyId'] = $temp['media_id'];
			$tempMediaInfo[$i] = $temp;
			$i++;
		}
		// pa($tempMediaInfo);
		$ResultMediaInfo = systemAndUserFieldsDataSegregation($tempMediaInfo);
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();

		//Loop for making the copy of the institute.
		$copyListingMappingArray['copiedListingIds'] = array();
		$cnt = 0;
		for($i=1;$i<=$this->noOfCopies;$i++){
			if(in_array($i,$copiesCancled) || ($cnt >= $noOfCopies)){
				continue;
			}
			$cnt++;
			if (isset($_POST['selectedSubs'.$i])) {
				$data['onBehalfOf']=true;
				$data['subscriptionId']=$this->input->post('selectedSubs'.$i);
			} else {
				$data['onBehalfOf']=false;
			}
			$data['institute_name'] = $this->input->post('nameOfInstitute'.$i);
			//Location of institute
			$data['locations'][0]['city_id']=$this->input->post('cityOfInstitute'.$i);
			$data['locations'][0]['country_id']=$this->input->post('country'.$i);
			$data['locations'][0]['address_line']=$this->input->post('addressOfInstitute'.$i);
			$data['locations'][0]['pincode']=$this->input->post('pincodeOfInstitute'.$i);
			$data['contact_main_phone']=$this->input->post('main_phone_number'.$i);
			$data['contact_email']=$this->input->post('institute_email'.$i);

			$ReturnVal = $this->addInstituteInfo($data,0);
			if(@array_key_exists('institute_id',$ReturnVal)){
				foreach($ResultMediaInfo['systemFieldsArr'] as $temp) {
					$temp['type_id'] = $ReturnVal['institute_id'];
					$temp['listing_type_id'] = $ReturnVal['institute_id'];
					$listingMediaType = $temp['media_type'];
					$reqArr = array();
					$reqArr['mediaId']=$temp['media_id'];
					$reqArr['mediaUrl']=$temp['url'];
					$reqArr['mediaName']=$temp['name'];
					$reqArr['mediaThumbUrl']=$temp['thumburl'];
					//pa($reqArr);
					$updateListingMedia = $ListingMediaClientObj->mapMediaContentWithListing($appId,$ReturnVal['institute_id'],'institute',$listingMediaType,base64_encode(json_encode($reqArr)));
				}
				array_push($copyListingMappingArray['copiedListingIds'],$ReturnVal['institute_id']);
				//pa($ResultMediaInfo);
				$MediaUpdateResult = $this->associateMediaWithListing($ResultMediaInfo,'institute',$ReturnVal['institute_id'],$mediaUpdateUserInfo);
			}
		}

		$Result = $ListingClientObj->makeCopyListingMapEntry('1',$copyListingMappingArray);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		header("LOCATION:/enterprise/Enterprise/index/7");
		exit;
	}

	/**
	 * performUniqueCheckForInstitute
	 * Function For duplicacy check of institutes.
	 * @access  public
	 * @param   array $_POST
	 * @return  flag for duplicacy.
	 * @ToDo
	 */
	function performUniqueCheckForInstitute(){
		$this->init();
		$ListingClientObj = new Listing_client();
		$dplicacyCheckFlag = 0;
		for($i=1;$i<=$this->noOfCopies;$i++){
			if(isset($_POST['institute'.$i]) && isset($_POST['pincode'.$i])){
				$listingTitle = $this->input->post('institute'.$i);
				$pincodeOfInstitute = $this->input->post('pincode'.$i);
				$dupData = $ListingClientObj->checkInstituteDuplicacy('1',0,$listingTitle,$pincodeOfInstitute);
				if(isset($dupData[0]) && is_array($dupData[0]) && ($dupData[0]['institute_id'] > 0)){
					echo json_encode(array('duplicacy' => 'true','institute_name'=>$listingTitle,'institute_id' => $dupData[0]['institute_id']));
					$dplicacyCheckFlag = 1;
					break;
				}
			}
		}
		if($dplicacyCheckFlag == 0){
			echo json_encode(array('duplicacy' => 'false'));
		}
	}

	/**
	 * performUniqueCheckForInstitute
	 * Function For duplicacy check of institutes.
	 * @access  public
	 * @param   array $_POST
	 * @return  flag for duplicacy.
	 * @ToDo
	 */
	function performUniqueCheckForCourse(){
		$this->init();
		$ListingClientObj = new Listing_client();
		$dplicacyCheckFlag = 0;
		$courseId = 0;
		$courseTitle = html_entity_decode(urldecode($this->input->post('courseTitle')));
		$courseType = urldecode($this->input->post('courseType'));
		for($i=1;$i<=$this->noOfCopies;$i++){
			if(isset($_POST['instituteId'.$i])){
				$instituteId = $this->input->post('instituteId'.$i);
				$dupData = $ListingClientObj->checkCourseDuplicacy('1',$courseId,$instituteId,$courseTitle,$courseType);
				//error_log("TTT :: ".print_r($dupData,true));
				if(isset($dupData[0]) && is_array($dupData[0]) && ($dupData[0]['course_id'] > 0)){
					echo json_encode(array('duplicacy' => 'true','institute_name'=>$dupData[0]['institute_name'],'course_name'=>$dupData[0]['courseTitle'],'institute_id'=>$instituteId));
					$dplicacyCheckFlag = 1;
					break;
				}
			}
		}
		if($dplicacyCheckFlag == 0){
			echo json_encode(array('duplicacy' => 'false'));
		}
	}
	/**
	 * associateMediaWithListing
	 * Function For association of media with listing.
	 * @access  pricare
	 * @param   array $Result,
	 * @return  array of media association result.
	 * @ToDo
	 */
	private function associateMediaWithListing($MediaInfo,$listingType,$listingTypeId,$loggedInUserInfo){
	$startTime = microtime(true);
		$appId = 1;
		$newMediaDataArray = array();
		foreach($MediaInfo['systemFieldsArr'] as $temp){
			$newMediaDataArray[$listingType][$listingTypeId][$temp['media_type']][$temp['media_id']] = 'addition';
		}
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		$updateStatus = $ListingMediaClientObj->associateMedia($appId,$listingType, $listingTypeId, base64_encode(json_encode($newMediaDataArray)), base64_encode(json_encode($loggedInUserInfo)));
	if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $updateStatus;
	}

	/**
	 * editInstitute
	 * Function For Edit Institute info. in the backend
	 * @access  public
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  string view of adding course to edited college
	 * @ToDo
	 */
	function editInstitute()
	{
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$instituteId = $this->input->post('instituteId');
		if($instituteId==''){
			header("location:/enterprise/Enterprise/index/7/choose");
			exit;
		}
		$cmsUserInfo = $this->cmsUserValidation();
		$data = array();
		$data['flow']=$this->input->post('flow');
		$ListingClientObj = new Listing_client();
		$listingData = $ListingClientObj->getListingForEdit('1',$instituteId,'institute');

		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }
                
		$instituteUser = $this->_userDetails($listingData[0]['userId']);

		$flag = 'disallow';
		if($cmsUserInfo['usergroup'] == 'cms'){
			if($instituteUser['usergroup'] == 'cms'){
				$flag = 'allow';
			}else{
				$clientId = $instituteUser['userid'];
				$flag = 'allow';
			}
		}else{
			if($cmsUserInfo['userid'] == $instituteUser['userid']){
				$flag = 'allow';
				$clientId = $cmsUserInfo['userid'];
			}
		}
		if($flag != 'allow'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}

		$data = $this->postDataForInstitute();

		$data['editedBy'] = $cmsUserInfo['userid'];

		$logoAndPanelResp = $this->instiLogoAndPanelUpload();
                $requestBrochureResp = $this->_instituteRequestBrochureUpload();
		//error_log("image ".print_r($logoAndPanelResp,true),3,'/home/naurki/Desktop/log.txt');
                if(array_key_exists('Fail', $requestBrochureResp)) {
			echo json_encode($requestBrochureResp);
			exit;
		}
		$data['logoArr'] = $logoAndPanelResp['logoArr'];
		$data['panelArr'] = $logoAndPanelResp['panelArr'];
		$data['photoArr'] = $logoAndPanelResp['photoArr'];
                $data['institute_request_brochure_link'] = $requestBrochureResp;
		$exitFlag = false;
		$logoPanelRespArray = array();

                // Validate all the locations now..
                $validateLocationFlag = $this->validateLocationInfo($data['locationInfo'], 'institute');
                if($validateLocationFlag[0] == 0) {
                        $logoPanelRespArray["Fail"]['location_issue'] = $validateLocationFlag[1];
                        $exitFlag = true;
                }

		if (isset($logoAndPanelResp['photoArr']['width']) && isset($logoAndPanelResp['photoArr']['height'])) {
			$logoAndPanelRespWidth = $logoAndPanelResp['photoArr']['width'];
			$logoAndPanelRespHeight = $logoAndPanelResp['photoArr']['height'];
			//	        error_log("image size $logoAndPanelRespWidth x $logoAndPanelRespHeight",3,'/home/naurki/Desktop/log.txt');
			if (($logoAndPanelRespWidth  != 252) &&  ($logoAndPanelRespHeight != 103)) {
				$logoPanelRespArray["Fail"]['photo'] = "Please upload a photo with height equal to 103 pixels and width equal to 252 pixels";
				$exitFlag = true;
			}
		}
		if(isset($logoAndPanelResp['logoArr']['error'])) {
			$logoPanelRespArray["Fail"]['logo'] = $logoAndPanelResp['logoArr']['error'];
			$exitFlag = true;
		}
		if(isset($logoAndPanelResp['panelArr']['error'])) {
			$logoPanelRespArray["Fail"]['panel'] = $logoAndPanelResp['panelArr']['error'];
			$exitFlag = true;
		}
		if ((isset($logoAndPanelResp['logoArr'])) && (count($logoAndPanelResp['logoArr']) > 0) && array_key_exists('width',$logoAndPanelResp['logoArr'])) {

			$logoAndPanelRespWidth = (int)$logoAndPanelResp['logoArr']['width'];
			$logoAndPanelRespHeight = (int)$logoAndPanelResp['logoArr']['height'];

			if (($logoAndPanelRespWidth  > 340) &&  ($logoAndPanelRespHeight > 65)) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height less than or equal to 65 pixels and width less than or equal to 340 pixels";
				$exitFlag = true;
			}
			if (($logoAndPanelRespWidth  < 60) &&  ($logoAndPanelRespHeight < 40)) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height more than or equal to 40 pixels and width more than or equal to 60 pixels";
				$exitFlag = true;
			}
			if ($logoAndPanelRespWidth  < 60) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with width more than or equal to 60 pixels.";
				$exitFlag = true;
			}
			if ($logoAndPanelRespWidth  > 340) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with width less than or equal to 340 pixels.";
				$exitFlag = true;
			}
			if ($logoAndPanelRespHeight < 40) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height more than or equal to 40 pixels.";
				$exitFlag = true;
			}
			if ($logoAndPanelRespHeight > 65) {
				$logoPanelRespArray["Fail"]['logo'] = "Please upload a logo with height less than or equal to 65 pixels";
				$exitFlag = true;
			}
		}

		if($exitFlag) {
			echo json_encode($logoPanelRespArray);
			exit;
		}

		$editResponse = $ListingClientObj->editInstitute('1',$instituteId,$data);
		if($editResponse == 1){
			//		error_log("Gobu ".print_r($data,true));
			if(!($cmsUserInfo['usergroup']=='cms' && $data['onBehalfOf']==false ) && $_POST['flow']=='upgrade') {
                            if($data['clientId'] != 11) {
				$this->load->library('Subscription_client');
				$subsObj = new Subscription_client();
				$resp = $subsObj->consumePseudoSubscription($appId,$data['subscriptionId'],'-1',$data['clientId'],$data['editedBy'],'-1',$instituteId,'institute','-1','-1');
                            }
			}
			if($this->input->post('nextAction') == 1){
				//header("location:/enterprise/ShowForms/showPreviewPage/".$instituteId."");
				echo json_encode(array("Success" => "/enterprise/ShowForms/showPreviewPage/".$instituteId));
			}
			else{
				//header("location:/enterprise/ShowForms/showCourseForm/".$instituteId."/3");
				echo json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$instituteId."/3"));
			}
		}
		else{
			//header("location:/enterprise/ShowForms/editInstituteForm/".$instituteId."/4");
			echo json_encode(array("Success" => "/enterprise/ShowForms/editInstituteForm/".$instituteId."/4"));
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

        function validateLocationInfo($locationInfoArray, $listingType) {
            $startTime = microtime(true);
            $responseArray[0] = 1;
            if($listingType == 'institute') {
                    foreach($locationInfoArray as $key => $locationInfo) {
                        $currentLocation = "'".$locationInfo['city_name'].($locationInfo['locality_name'] == "" ? "" : " -- ".$locationInfo['locality_name'])."' location";
                        // Check for valid Country ID..
                        if(!is_numeric($locationInfo['country_id']) || $locationInfo['country_id'] < 1) {
                            $responseArray[0] = 0;
                            $responseArray[1] = "Please edit $currentLocation and enter the valid Country.";
                            break;
                        }
                        // Check for valid city ID..
                        if(!is_numeric($locationInfo['city_id']) || $locationInfo['city_id'] < 1) {
                            $responseArray[0] = 0;
                            $responseArray[1] = "Please edit $currentLocation and enter the valid City.";
                            break;
                        }
                        // Check for non empty pin_code..
                        if($locationInfo['pin_code'] == "") {
                            $responseArray[0] = 0;
                            $responseArray[1] = "Please edit $currentLocation and enter the valid Pin Code.";
                            break;
                        }
                    }
            } else {                
                    // Check for the Head Ofc location first..
                    if(!is_numeric($locationInfoArray['head_ofc_location_id']) || $locationInfoArray['head_ofc_location_id'] < 1) {
                            $responseArray[0] = 0;
                            $responseArray[1] = "Please select the Head Office location first.";
                    }

                    // Now validate the assigned locations..
                    $institute_location_ids_array = explode(",", $locationInfoArray['institute_location_ids']);
                    foreach($institute_location_ids_array as $key => $institute_location_id) {
                            $institute_location_id = trim($institute_location_id);
                            if(!is_numeric($institute_location_id) || $institute_location_id < 1) {
                                    $responseArray[0] = 0;
                                    $responseArray[1] = "Please assign the valid Location.";
                                    break;
                            }
                    }
            }
			if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
            return $responseArray;
            
        }

	/**
	 * addCourse
	 * Function For Adding Course info. in the backend
	 * @access  public
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  string view having option for adding another course or Adding Media
	 * @ToDo
	 */
	function addCourse()
	{   
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();

		$ListingClientObj = new Listing_client();
		$EventCalClientObj=new Event_cal_client();
		$data = array();
		$data = $this->_postDataForCourse();
		
                // Validate all the locations now for this course..
                $locArray['institute_location_ids'] =  $data['institute_location_ids'];
                $locArray['head_ofc_location_id'] =  $data['head_ofc_location_id'];
                $validateLocationFlag = $this->validateLocationInfo($locArray, 'course');
                if($validateLocationFlag[0] == 0) {
                        $logoPanelRespArray["Fail"]['location_issue'] = $validateLocationFlag[1];
                        echo json_encode($logoPanelRespArray);
                        exit;
                }

		$data['editedBy'] = $cmsUserInfo['userid'];
                $data['group_to_be_checked'] = $cmsUserInfo['usergroup'];
		
		/*
		 *	Lets check if the subscription selected has not been consumed while posting this listing..
		 *	This is to ensure that expiry_date should get filled properly (at Listing Publishing) in listtings_main table.
		 */
		$validateSubscriptionFlag = $this->validateSubscriptionInfo($data['group_to_be_checked'], $data['onBehalfOf'], $data['subscriptionId'], $data['clientId']);
                if($validateSubscriptionFlag[0] == 0) {
                        $logoPanelRespArray["Fail"]['subscription_issue'] = $validateSubscriptionFlag[1];
			$logoPanelRespArray['subscription_id'] = $validateSubscriptionFlag[2];
                        echo json_encode($logoPanelRespArray);
                        exit;
                }
		
		$logoArr = array();
		if ($this->input->post('applicationForm') == '') {
			$data['form_upload'] = '';
			$data['ApplicationDocArr']['url'] = '';
		} else {
			$data['form_upload'] = $this->input->post('applicationForm');
			$data['ApplicationDocArr'] = $this->UploadCourseApplicationDoc();
		}
		// error_log("\n\n Data : ".print_r($data, true),3,'/home/infoedge/Desktop/log.txt'); // die;
		// echo "Data = <pre>".print_r($data)."</pre>"; die;
                $course_request_broucher = $this->_courseRequestBrochureUpload();
		if(array_key_exists("Fail", $course_request_broucher)) {
			echo json_encode($course_request_broucher);
			exit;
		}
		$data['course_request_brochure_link'] = $course_request_broucher;
		$applicationDocResponse= array();
		if(isset($data['ApplicationDocArr']['error'])) {
			$applicationDocResponse["Fail"]['applicationDoc'] = $data['ApplicationDocArr']['error'];
			echo json_encode($applicationDocResponse);
			exit;
		}

		$showDetailsFlags = $this->getDetailPageFlags($data['subscriptionId']);
		$data = array_merge($data,$showDetailsFlags);
		echo $this->addCourseInfo($data,1);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * addCourseInfo
	 * Function For Editing Course info. in the backend
	 * @access  public
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  string view having option for adding another course or Adding Media
	 * @ToDo
	 */
	private function addCourseInfo($data,$jsonReturn=0)
	{
		// die("checking");
                //error_log('in gandh'.print_r($data,true));
		$ListingClientObj = new Listing_client();
		$response = $ListingClientObj->newAddCourse('1',$data);
		if(is_array($response)){
			if($response['Listing_id'] > 0 && $response['type_id'] > 0)
			{
				$this->load->library('message_board_client');
				$msgbrdClient = new Message_board_client();
				$topicDescription = "You can discuss on this event below";
				$requestIp = S_REMOTE_ADDR;
				$topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,($data['category_id']==""?1:$data['category_id']),$requestIp,'event');
				if(isset($topicResult['ThreadID'])){
					$data['threadId'] = $topicResult['ThreadID'];
					$this->load->library('event_cal_client');
					$EventCalClientObj = new Event_cal_client();
					$EventCalClientObj->addEventsForListing('1',$data,$response['Course_id']);
				}

				if(!($data['group_to_be_checked'] == 'cms' && ($data['onBehalfOf'] == NULL || $data['onBehalfOf'] == "false"))) {
                                    //if($data['clientId'] != 11) {
					$this->load->library('Subscription_client');
					$subsObj = new Subscription_client();
					$resp = $subsObj->consumePseudoSubscription($appId,$data['subscriptionId'],'-1',$data['clientId'],$data['editedBy'],'-1',$response['type_id'],'course','-1','-1');
                                   // }
				}

				if(isset($_POST['previewAction']) && ($this->input->post('previewAction') == 1)){
					if($jsonReturn == 1){
						return json_encode(array("Success" => "/enterprise/ShowForms/showPreviewPage/".$data['institute_id']."/course/".$response['type_id']));
					}else{
						return array('Result' => 'Success','type_id' => $response['type_id']);
					}
				}
				else{
					if(isset($_POST['nextAction']) && ($this->input->post('nextAction') == 1)){
						if($jsonReturn == 1){
							return json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$data['institute_id']."/2"));
						}else{
							return array('Result' => 'Success','type_id' => $response['type_id']);
						}

					}
					else{
						if($jsonReturn == 1){
							return json_encode(array("Success" => "/enterprise/ShowForms/showMediaInstituteForm/institute/".$data['institute_id']."/2"));
						}else{
							return array('Result' => 'Success','type_id' => $response['type_id']);
						}
					}
				}
			}
			else {
				if($response['listing_id'] < 0){
					if($jsonReturn == 1){
						return json_encode(array('Fail' => array('common' => 'Duplicate Listing !!!')));
					}else{
						return array('Result' => 'Duplicate Listing !!!');
					}
				}
			}
		}
		else{
			if($jsonReturn == 1){
				return json_encode(array('Fail' => array('common' => 'Some issues while adding course !!! Please try again later.')));
			}else{
				return array('Result' => 'Some issues while adding course !!! Please try again later.');
			}
		}
	}

	function selectInstituteForCopyCourse($courseId){
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$packTypeArrayForSubscriptionSelection = array(1,2,7);
		$cmsUserInfo = $this->cmsUserValidation();
		if($cmsUserInfo['usergroup'] !== 'cms'){
			header('Location:/enterprise/Enterprise/index/6');
			exit;
		}
		$ListingClientObj = new Listing_client();
		$listingDetails = $ListingClientObj->getListingDetails($appId,$courseId,'course');
		$data['listingLocation'] = is_array($listingDetails[0])?$listingDetails[0]['locations']:0;
		$packTypeOfListingToBeCopied = is_array($listingDetails[0])?$listingDetails[0]['packType']:0;
		$data['subscriptionStrict'] = 'true';
		if (!in_array($packTypeOfListingToBeCopied,$packTypeArrayForSubscriptionSelection)) {
			$data['subscriptionStrict'] = 'false';
		}
		$data['listingTitle'] = is_array($listingDetails[0])?$listingDetails[0]['title']:"";
		$data['instituteName'] = is_array($listingDetails[0])?$listingDetails[0]['institute_name']:"";
		$data['courseType'] = is_array($listingDetails[0])?$listingDetails[0]['course_type']:"";
		$data['instituteId'] = is_array($listingDetails[0])?$listingDetails[0]['institute_id']:0;
		$data['courseId'] = $courseId;
		$listngOwnerId = is_array($listingDetails[0])?$listingDetails[0]['userId']:0;
		$ownerInfo = $this->_userDetails($listngOwnerId);
		if($ownerInfo['usergroup'] !== 'cms'){
			$instituteList = $ListingClientObj->getListingsList($appId,"institute",0,10000,"","","","","","","",$ownerInfo['usergroup'],$listngOwnerId);
			$instituteList1 = $ListingClientObj->getListingsList($appId,"institute",0,10000,"","","","","","draft","",$ownerInfo['usergroup'],$listngOwnerId);
			$liveInstitutes = array();
			$totalCount = 0;$totalCount1=0;
			foreach($instituteList as $key => $temp) {
				if(isset($temp['institute_id'])){
					array_push($liveInstitutes,$temp['institute_id']);
				}else{
					$totalCount = $temp['totalCount'];
				}
			}
			unset($instituteList[count($instituteList)-1]);
			$tempArray1 = array();
			foreach($instituteList1 as $temp){
				if(isset($temp['institute_id']) && (!in_array($temp['institute_id'],$liveInstitutes))) {
					array_push($tempArray1,$temp);
				}else{
					$totalCount1 = $temp['totalCount'];
				}
			}
			$data['instituteList'] = array_merge($instituteList,$tempArray1);
			$data['instituteList'][count($data['instituteList'])] = array('totalCount'=>($totalCount+$totalCount1));
		}
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		//	$SubscriptionArray= $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$listngOwnerId));
		$SubscriptionArray= $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$listngOwnerId));
		$categoryClient = new Category_list_client();
		$data['country_list'] = $categoryClient->getCountries('1');
		$data['SubscriptionArray'] = $SubscriptionArray;
		$data['cmsUserInfo'] = $cmsUserInfo;
		$data['headerTabs'] = $data['cmsUserInfo']['headerTabs'];
		$data['noOfCopies'] = $this->noOfCopies;
		$data['listngOwnerId'] = $listngOwnerId;
		$data['ownerUserGroup'] = $ownerInfo['usergroup'];
		$data['validateuser'] = $this->checkUserValidation();
		$data['prodId']=6;
		$this->load->view('listing_forms/selectCopyCourse',$data);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}

	/**
	 * getListingInfoForCopyCourse
	 * Function For gettting the institute details for listing id
	 * @access  public
	 * @param   array $listing_id $listing_type
	 * @return  listing details for given id.
	 * @ToDo
	 */
	function getListingInfoForCopyCourse(){
		$startTime = microtime(true);
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$listingId = $this->input->post('listingId');
		$listingType = $this->input->post('listingType');
		$ListingClientObj = new Listing_client();
		$Result = $ListingClientObj->getListingForEdit($appId,$listingId,$listingType);
		$listngOwnerId = is_array($Result[0])?$Result[0]['userId']:0;
		if (($cmsUserInfo['usergroup'] == 'cms') || ($cmsUserInfo['userid'] == $listngOwnerId)) {
			$listingDetails = is_array($Result[0])?$Result[0]:array();
			echo json_encode($listingDetails);
		} else {
			echo "false";
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * submitCopyCourse
	 * Function For copying the course listing.
	 * @access  public
	 * @param   array $_POST
	 */
	function submitCopyCourse(){
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		$courseId = $this->input->post('courseId');
		$ListingClientObj = new Listing_client();
		$Result = $ListingClientObj->getListingForEdit('1',$courseId,'course');
		$courseInfo = is_array($Result[0])?$Result[0]:array();
		$data = array();
		$data['dataFromCMS']=$flagArray['moderation'];
		$data['packType']=$flagArray['packType'];
		$data['username']=$this->input->post('clientId');
		$data['clientId']=$this->input->post('clientId');
		//Basic Course details
		$data['courseTitle'] = html_entity_decode($courseInfo['title']);
		$data['approvedBy']=$courseInfo['approvedBy'];
		$data['courseType']=$courseInfo['course_type'];
		$copyListingMappingArray = array();
		$copyListingMappingArray['originalListingId'] = $courseId;
		$copyListingMappingArray['originalListingType'] = 'course';
		$copyListingMappingArray['addedBy'] = $cmsUserInfo['userid'];
		//Course Level different cases
		//TEST PREP FOR COURSE
		$testPrepArray = json_decode(html_entity_decode($courseInfo['tests_preparation']),true);
		$tempArray = array();
		foreach($testPrepArray as $temp){
			array_push($tempArray,$temp['blogId']);
		}
		$data['tests_preparation'] = implode(",",$tempArray);
		$testPrepOtherArray = json_decode(html_entity_decode($courseInfo['tests_preparation_other']),true);
		if(count($testPrepOtherArray) > 0){
			$data['tests_preparation_other'] = 'true';
			$data['tests_preparation_exam_name'] = is_array($testPrepOtherArray[0])?$testPrepOtherArray[0]['exam_name']:"";
		}
		$data['tests_required_other'] = '';
		//TEST PREP FOR COURSE
		$data['courseLevel']=$courseInfo['course_level'];
		switch(strtolower($data['courseLevel'])){
			case 'dual degree':
				$data['courseLevel_1']=$courseInfo['course_level_1'];
				$data['courseLevel_2']=$courseInfo['course_level_2'];
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'degree':
				$data['courseLevel_1']=$courseInfo['course_level_1'];
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'diploma':
				$data['courseLevel_1']=$courseInfo['course_level_1'];
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'certification':
				$data['courseLevel_1']='';
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'vocational':
				$data['courseLevel_1']='';
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			default:
				$data['courseLevel_1']='';
				$data['courseLevel_2']='';
				break;
		}
		$catArray = array();
		foreach($courseInfo['categoryArr'] as $temp){
			array_push($catArray,$temp['category_id']);
		}
		$data['category_id']=implode(",",$catArray);
		$data['duration_value']=$courseInfo['duration_value'];
		$data['duration_unit']=$courseInfo['duration_unit'];
		$data['duration']=$data['duration_value']." ".$data['duration_unit'];
		$data['fees_value']=$courseInfo['fees_value'];
		$data['fees_unit']=$courseInfo['fees_unit'];
		$data['fees']=$data['fees_value']." ".$data['fees_unit'];
		$data['seats_general']=$courseInfo['seats_general'];
		$data['seats_reserved']=$courseInfo['seats_reserved'];
		$data['seats_management']=$courseInfo['seats_management'];
		$data['date_form_submission']=$courseInfo['date_form_submission'];
		$data['date_result_declaration']=$courseInfo['date_result_declaration'];
		$data['date_course_comencement']=$courseInfo['date_course_comencement'];
		$data['hiddenTags'] = $courseInfo['hiddenTags'];
		$wikiFields = unserialize(base64_decode($courseInfo['wikiFields']));
		$reformattedFields = systemAndUserFieldsDataSegregation($wikiFields);
		//Wiki Section additions
		$wikiData = $ListingClientObj->getWikiFields('1','course');
		foreach($reformattedFields['systemFieldsArr'] as $temp){
			foreach($wikiData as $wikiField){
				if($temp['key_name'] == $wikiField['key_name']){
					$wikiDesc = str_replace(array("\n","\r","\n\r"),"",trim($temp['attributeValue']));
					$data['wiki'][$wikiField['key_name']]=is_text_in_html_string($temp['attributeValue'])?trimmed_tidy_repair_string($temp['attributeValue']):'';
				}
			}
		}
		//pa($reformattedFields['userFieldsArr']);
		$i=0;
		foreach($reformattedFields['userFieldsArr'] as $temp){
			$data['wiki']['user_fields'][$i]['caption']=$temp['caption'];
			$data['wiki']['user_fields'][$i]['value']=$temp['attributeValue'];
			$i++;
		}
		//pa($data['wiki']['user_fields']);
		//Media Info

		$ResultMediaInfo = unserialize(base64_decode($courseInfo['mediaInfo']));
		$i = 0;
		$tempMediaInfo = array();
		foreach($ResultMediaInfo as $key => $temp){
			$temp['keyId'] = $temp['media_id'];
			$tempMediaInfo[$i] = $temp;
			$i++;
		}
		$ResultMediaInfo = systemAndUserFieldsDataSegregation($tempMediaInfo);
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		$noOfCopies = $this->input->post('noOfCopiesMade');
		$copiesCancled = explode(",",$this->input->post('copiesCancled'));
		$copyListingMappingArray['copiedListingIds'] = array();
		$cnt = 0;
		for($i=1;$i<=$this->noOfCopies;$i++){
			if(in_array($i,$copiesCancled) || ($cnt >= $noOfCopies)){
				continue;
			}
			$cnt++;
			if (isset($_POST['selectedSubs'.$i])) {
				$data['onBehalfOf']=true;
				$data['subscriptionId']=$this->input->post('selectedSubs'.$i);
			} else {
				$data['onBehalfOf']=false;
			}
			$data['institute_id'] =$this->input->post('instituteId'.$i);
			$instDetails = $ListingClientObj->getListingForEdit($appId,$data['institute_id'],'institute');
			//Contact Info
			$data['contact_details_id']=$instDetails[0]['contact_details_id'];
			$data['contact_name']=$instDetails[0]['contact_name'];
			$data['contact_main_phone']=$instDetails[0]['contact_main_phone'];
			$data['contact_cell']=$instDetails[0]['contact_cell'];
			$data['contact_email']=$instDetails[0]['contact_email'];
			$ReturnVal = $this->addCourseInfo($data,0);
			if(@array_key_exists('type_id',$ReturnVal)){
				foreach($ResultMediaInfo['systemFieldsArr'] as $temp) {
					$temp['type_id'] = $ReturnVal['type_id'];
					$temp['listing_type_id'] = $ReturnVal['type_id'];
					$listingMediaType = $temp['media_type'];
					$reqArr = array();
					$reqArr['mediaId']=$temp['media_id'];
					$reqArr['mediaUrl']=$temp['url'];
					$reqArr['mediaName']=$temp['name'];
					$reqArr['mediaThumbUrl']=$temp['thumburl'];

					$updateListingMedia = $ListingMediaClientObj->mapMediaContentWithListing($appId, $data['institute_id'], 'institute', $listingMediaType, base64_encode(json_encode($reqArr)));
				}
				array_push($copyListingMappingArray['copiedListingIds'],$ReturnVal['type_id']);
				$MediaUpdateResult = $this->associateMediaWithListing($ResultMediaInfo,'course',$ReturnVal['type_id'],$mediaUpdateUserInfo);
				$Result = $ListingClientObj->makeCopyListingMapEntry('1',$copyListingMappingArray);
			}
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		header("LOCATION:/enterprise/Enterprise/index/6");
		exit;
	}

	/**
	 * editCourse
	 * Function For Editing Course info. in the backend
	 * @access  public
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  string view having option for adding another course or Adding Media
	 * @ToDo
	 */
	function editCourse()
	{
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$courseId = $this->input->post('courseId');
		if($courseId == ''){
			echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));//Logical Hack for redirection by ajax
			//header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}

		$cmsUserInfo = $this->cmsUserValidation();
		$instituteId = $this->input->post('instituteId');
		$clientId = $this->input->post('clientId');
		
		if($instituteId == ''){
			echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));//Logical Hack for redirection by ajax
			//header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}

		$ListingClientObj = new Listing_client();
		$EventCalClientObj = new Event_cal_client();
		$listingData = $ListingClientObj->getListingForEdit('1',$instituteId,'institute');

		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }
                
		$instituteUser = $this->_userDetails($listingData[0]['userId']);

		$flag = 'disallow';
		if($cmsUserInfo['usergroup'] == 'cms'){
			if($instituteUser['usergroup'] == 'cms'){
				$flag = 'allow';
			}else{
				if(!isset($clientId) || $clientId='')
				{
					$clientId = $instituteUser['userid'];
				}
				$flag = 'allow';
			}
		}else{
			if($cmsUserInfo['userid'] == $instituteUser['userid']){
				$flag = 'allow';
				$clientId = $cmsUserInfo['userid'];
			}
		}
		if($flag != 'allow'){
			echo json_encode(array("Success" => "/enterprise/Enterprise/disallowedAccess"));//Logical Hack for redirection by ajax
			//header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$data = array();
		$data = $this->_postDataForCourse();


		/*
		 *	Lets check if the subscription selected has not been consumed while posting this listing..
		 *	This is to ensure that expiry_date should get filled properly (at Listing Publishing) in listtings_main table.
		 */
		if($_POST['flow'] == 'upgrade') {
			$validateSubscriptionFlag = $this->validateSubscriptionInfo($cmsUserInfo['usergroup'], $_POST['onBehalfOf'], $data['subscriptionId'], $data['clientId']);
			if($validateSubscriptionFlag[0] == 0) {
				$logoPanelRespArray["Fail"]['subscription_issue'] = $validateSubscriptionFlag[1];
				$logoPanelRespArray['subscription_id'] = $validateSubscriptionFlag[2];
				echo json_encode($logoPanelRespArray);
				exit;
			}               
		}
		
	        // Validate all the locations now for this course..
                $locArray['institute_location_ids'] =  $data['institute_location_ids'];
                $locArray['head_ofc_location_id'] =  $data['head_ofc_location_id'];
                $validateLocationFlag = $this->validateLocationInfo($locArray, 'course');
                if($validateLocationFlag[0] == 0) {
                        $logoPanelRespArray["Fail"]['location_issue'] = $validateLocationFlag[1];
                        echo json_encode($logoPanelRespArray);
                        exit;
                }

		$data['editedBy'] = $cmsUserInfo['userid'];
		$logoArr = array();
		if ($this->input->post('applicationForm') == '') {
			$data['form_upload'] = '';
			$data['ApplicationDocArr']['url'] = '';
		} else {
			$data['form_upload'] = $this->input->post('applicationForm');
			$data['ApplicationDocArr'] = $this->UploadCourseApplicationDoc();
		}
                $course_request_broucher = $this->_courseRequestBrochureUpload();
		if(array_key_exists("Fail", $course_request_broucher)) {
			echo json_encode($course_request_broucher);
			exit;
		}
		$data['course_request_brochure_link'] = $course_request_broucher;
		$applicationDocResponse= array();
		if(isset($data['ApplicationDocArr']['error'])) {
			$applicationDocResponse["Fail"]['applicationDoc'] = $data['ApplicationDocArr']['error'];
			echo json_encode($applicationDocResponse);
			exit;
		}
		$response = $ListingClientObj->editCourse('1',$courseId,$data);
		if($response['Listing_id'] > 0 && $response['type_id'] > 0)
		{   
			$upgradeCourseForm = $this->input->post('upgradeCourseForm');
			if($upgradeCourseForm == 'upgradeCourseForm') {
				$this->load->model('ListingModel');
				$model_object = new ListingModel();
                                $owner_id = $this->input->post('clientId');
				$model_object->updateOwnerInfoForRelatedListings($courseId,$owner_id);
			}
			$msgbrdClient = new Message_board_client();
			$topicDescription = "You can discuss on this event below";
			$requestIp = S_REMOTE_ADDR;
			$topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,$data['category_id'],$requestIp,'event');
			$data['threadId'] = $topicResult['ThreadID'];
			$EventCalClientObj->addUpdateEventsForListing('1',$courseId,$data);
			//error_log("Gobu".print_r($_POST,true));
			if(!($cmsUserInfo['usergroup']=='cms' && $_POST['onBehalfOf']==false) && $_POST['flow']=='upgrade') {
                           // if($data['clientId'] != 11) {
				$this->load->library('Subscription_client');
				$subsObj = new Subscription_client();
				$resp = $subsObj->consumePseudoSubscription($appId,$data['subscriptionId'],'-1',$data['clientId'],$data['editedBy'],'-1',$courseId,'course','-1','-1');
                            // }
			}

			if($this->input->post('previewAction') == 1){
				echo json_encode(array("Success" => "/enterprise/ShowForms/showPreviewPage/".$data['institute_id']."/course/".$courseId));//Logical Hack for redirection by ajax
				//header("location:/enterprise/ShowForms/showPreviewPage/".$data['institute_id']."/course/".$courseId);
				exit;
			}
			else{
				if($this->input->post('nextAction') == 1){
					echo json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$data['institute_id']."/2"));
					//header("location:/enterprise/ShowForms/showCourseForm/".$data['institute_id']."/2");
					exit;
				}
				else{
					echo json_encode(array("Success" => "/enterprise/ShowForms/showMediaInstituteForm/institute/".$data['institute_id']."/2"));
					//header("location:/enterprise/ShowForms/showMediaInstituteForm/institute/".$data['institute_id']."/2");
					exit;
				}
			}
		}
		else {
			if($response['listing_id'] < 0){
				echo json_encode(array('Fail' => array('common' => 'Duplicate Listing !!!')));
			}
		}
	 if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}

	/**
	 * showCourseEditForm
	 * Function to show Course Edit Form
	 * @access  public
	 * @param int|string $courseId
	 *
	 * @return string
	 */
	function showCourseEditForm($courseId='')
	{
		
		$startTime = microtime(true);
		ini_set('memory_limit', '1024M');
	
		if($courseId == ''){
			//    echo 'please pass course id';
			header("location:/enterprise/Enterprise/index/6/choose");
			exit;
		}
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        //redirecting for enterprise usergroup
        if($cmsUserInfo['usergroup'] == "enterprise"){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}

		$ListingClientObj = new Listing_client();
		$listing_type = 'course';
		
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/coursedetailsmodel');
		$courseDetailsModel = new CourseDetailsModel($cacheLib);
		$listingData = $courseDetailsModel->getCourseDetails($courseId);
		
		//_p($listingData); exit();
		//
		//$listingData = $ListingClientObj->getListingForEdit('1',$courseId,$listing_type);
		//_p($listingData); exit();

		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }
                
		//_p($listingData);
		
         /**** disallow access if course id belongs to abroad cms ****/
         $isNationalCourse = $courseDetailsModel->checkIfCourseBelongsToNationalPosting($courseId);
                
         if(!$isNationalCourse) {
         	header("location:/enterprise/Enterprise/disallowedAccess");
         	exit();
         }
         
         
		if($cmsUserInfo['usergroup']!= "cms"){
			if($listingData[0]['userId'] != $cmsUserInfo['userid']){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}
                
		$clientId = $listingData[0]['userId'];
		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);

		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['clientDetails'] = $this->_userDetails($clientId);
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['pageTitle'] = "Editing ".$listingData[0]['title'].", ".$listingData[0]['institute_name'];
		$cmsPageArr['institute_name'] = $listingData[0]['institute_name'];
		$cmsPageArr['institute_location'] = $listingData[0]['locations'][0]['city_name'].((strtolower($listingData[0]['locations'][0]['country_name']) != "india")?", ". $listingData[0]['locations'][0]['country_name']:"");
		$cmsPageArr['viewType'] = 2;
		$cmsPageArr['clientId'] = $clientId;
		$cmsPageArr['submitDate'] = $listingData[0]['submit_date'];
		$cmsPageArr['viewCount'] = $listingData[0]['viewCount'];
		$cmsPageArr['no_Of_Past_Free_Views'] = $listingData[0]['no_Of_Past_Paid_Views'];
		$cmsPageArr['no_Of_Past_Paid_Views'] = $listingData[0]['no_Of_Past_Paid_Views'];
		$cmsPageArr['status'] = $listingData[0]['status'];
		
		$categoryClient = new Category_list_client();
		$cmsPageArr['country_list'] = $categoryClient->getCountries('1');

		foreach($listingData[0]['courseAttributes'] AS $attribute){
			$cmsPageArr[$attribute['attribute']] = $attribute['value'];
		}
		if(isset($cmsPageArr['SalaryMax'])){
			$salaryParts = explode(' ',$cmsPageArr['SalaryMax']);
			$cmsPageArr['max_salary'] = $salaryParts[0];
			$cmsPageArr['max_salary_unit'] = $salaryParts[1];
		}
		if(isset($cmsPageArr['SalaryAvg'])){
			$salaryParts = explode(' ',$cmsPageArr['SalaryAvg']);
			$cmsPageArr['avg_salary'] = $salaryParts[0];
			$cmsPageArr['avg_salary_unit'] = $salaryParts[1];
		}
		if(isset($cmsPageArr['SalaryMin'])){
			// echo "<pre>"; print_r($cmsPageArr['SalaryMin']); die;
			$salaryParts = explode(' ',$cmsPageArr['SalaryMin']);
			$cmsPageArr['min_salary'] = $salaryParts[0];
			$cmsPageArr['min_salary_unit'] = $salaryParts[1];
			// echo "unit = ".$salaryParts[1]; die;
		}
		foreach($listingData[0]['courseFeatures'] AS $feature){
			$cmsPageArr[$feature['feature_name']] = $feature['value'];
		}
		foreach($listingData[0] as $key => $val){
			$cmsPageArr[$key] = $val;
		}

		$wikiFields = unserialize(base64_decode($listingData[0]['wikiFields']));
		$reformattedFields = systemAndUserFieldsDataSegregation($wikiFields);
		$cmsPageArr['systemFieldsArr'] = $reformattedFields['systemFieldsArr'];
		$cmsPageArr['userFieldsArr'] = $reformattedFields['userFieldsArr'];
		$cmsPageArr['wikiData'] = $ListingClientObj->getWikiFields('1',$listing_type);
		$cmsPageArr['flow'] = 'edit';
		//$cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/editCourse';
		$cmsPageArr['formPostUrl'] = '/listing/posting/CoursePost/post';
		$cmsPageArr['courseId'] = $courseId;
		$cmsPageArr['categoryForLeftPanel'] = $this->getCategories();
		$instituteId =  $listingData[0]['institute_id'];

		$tmpArr=array();
		$tmpArr['listing_type_id'] = $courseId;
		$tmpArr['listing_type'] = 'course';
		// $instiContacts = $ListingClientObj->getDraftAndLiveInstiContactIds(1,$tmpArr);

		$cmsPageArr['instiContacts']=$instiContacts;
		$cmsPageArr['coursesAlreadyAdded'] = $ListingClientObj->getCourseList('1',$instituteId,'"live","draft"');
		$cmsPageArr['skipActionUrl'] = '/enterprise/ShowForms/showMediaInstituteForm/institute/'.$instituteId;

		//echo "<pre>".print_r($listingData,true)."</pre>";
		$categoryClient = new Category_list_client();
		$catSubcatList = $categoryClient->getCatSubcatList($listingData[0]['locations'][0]['country_id'],$listingData[0]['instituteType']);
		$subCategories = array();
		foreach($catSubcatList as $value){
			foreach($value['subcategories'] as $value2){
				$subCategories[] = $value2['catId'];
			}
		}
		$courseList = $categoryClient->getSubCategoryCourses(implode(",",$subCategories));
        $this->_addCategoryNameWithLDBCourses($courseList);

		$catSubcatCourseList = array();
		foreach($catSubcatList as $key=>$value){
			$catSubcatCourseList[$key] = $value;
			foreach($value['subcategories'] as $value2){
				$catSubcatCourseList[$key]['subcategories'][$value2['catId']]['courses'] =  $courseList[$value2['catId']];
			}
		}
		$cmsPageArr['catSubcatCourseList'] = $catSubcatCourseList;

		$cmsPageArr['ldbMappedCourses'] =  $categoryClient->getLdbMappedCourses($courseId);

		//echo "<pre>".print_r($cmsPageArr['ldbMappedCourses'],true)."</pre>";
		/* Add Exam Preparation Drop down */
		$this->load->library('blog_client');
		$blogClient = new Blog_client();
		$examsList = $blogClient->getExamsForProducts($appId);

		//$itcourseslist = json_decode($categoryClient->getCourseSpecializationForCategoryIdGroups(1,7),true);
		$itcourseslist = $categoryClient->getTestPrepCoursesList(1);
		$cmsPageArr['itcourseslist'] = $itcourseslist;
		$cmsPageArr['examsList'] = $examsList;
		$cmsPageArr['prodId'] = '6';
        $cmsPageArr['coursettl'] = $listingData[0]['title'];
        $cmsPageArr['source_type'] = $listingData[0]['source_type'];
        $cmsPageArr['source_name'] = $listingData[0]['source_name'];
		// pass feestypes
		if(!empty($listingData[0]['feestypes'])) {
			$feestypes = explode(',', $listingData[0]['feestypes']);
			foreach($feestypes as $type) {
				$cmsPageArr['fees_types'][$type] = $type;
			}		
		}
		//list of restricted ldb courses
		$cmsPageArr['restrictedLDBCourse'] = $this->getRestrictedLDBCourses();
        $cmsPageArr['course_request_brochure_year'] = $listingData[0]['course_request_brochure_year']; 
	
        $this->load->library('OnlineFormEnterprise_client');
        $ofObj = new OnlineFormEnterprise_client();
        $cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);
        if($listingData[0]['instituteType'] == 1) {
               
        $this->load->library('listing/ListingProfileLib');
		$cmsPageArr['score_array']  = $this->listingprofilelib->calculateProfileCompeletion($instituteId);
        }
		/* Add Exam Preparation Drop down */
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);

		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
        
	}

	/**
	 * upgradeCourseForm
	 * Function to show Course Edit Form
	 * @access  public
	 * @param   int $courseId
	 * @return  string
	 * @ToDo
	 */
	function upgradeCourseForm($courseId='')
	{
		$startTime = microtime(true);
		/* if($courseId == ''){
		 //    echo 'pleas pass course id';
		 header("location:/enterprise/Enterprise/index/6/choose");
		 exit;
		 }*/
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		//error_log("am here gobu");
		/* START UPGRADE LISTING */
		$upgrade_onBehalfOf = $this->input->post('onBehalfOf',true);
		$upgrade_instituteId = $this->input->post('instituteId',true);
		$upgrade_flag_listing_upgrade = $this->input->post('flag_listing_upgrade',true);
		$upgrade_client_id = $this->input->post('selectedUserId',true);
		/* END UPGRADE LISTING */
		/* START UPGRADE LISTING */
		if ($upgrade_flag_listing_upgrade == '2') {
			$courseId = $upgrade_instituteId;
		}
		/* END UPGRADE LISTING */

		$ListingClientObj = new Listing_client();
		$listing_type = 'course';
		$listingData = $ListingClientObj->getListingForEdit('1',$courseId,$listing_type);

		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }
                
		if($cmsUserInfo['usergroup']!= "cms"){
			if($listingData[0]['userId'] != $cmsUserInfo['userid']){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}
		$clientId = $listingData[0]['userId'];
	 /* START UPGRADE LISTING */
		if ($upgrade_flag_listing_upgrade == '2') {
			$clientId = $upgrade_client_id;
		}
		/* END UPGRADE LISTING */


		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);
		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['clientDetails'] = $this->_userDetails($clientId);
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['pageTitle'] = "Editing ".$listingData[0]['title'].", ".$listingData[0]['institute_name'];
		$cmsPageArr['institute_name'] = $listingData[0]['institute_name'];
		$cmsPageArr['institute_location'] = $listingData[0]['locations'][0]['city_name'].((strtolower($listingData[0]['locations'][0]['country_name']) != "india")?", ". $listingData[0]['locations'][0]['country_name']:"");
		$cmsPageArr['viewType'] = 2;
		$cmsPageArr['clientId'] = $clientId;
		$categoryClient = new Category_list_client();
		$cmsPageArr['country_list'] = $categoryClient->getCountries('1');
		foreach($listingData[0] as $key => $val){
			$cmsPageArr[$key] = $val;
		}
		//-----------------------------------------------------

		foreach($listingData[0]['courseAttributes'] AS $attribute){
			$cmsPageArr[$attribute['attribute']] = $attribute['value'];
		}

		// echo "<pre>"; print_r($cmsPageArr); die;
		if(isset($cmsPageArr['SalaryMax'])){
			$salaryParts = explode(' ',$cmsPageArr['SalaryMax']);
			$cmsPageArr['max_salary'] = $salaryParts[0];
			$cmsPageArr['max_salary_unit'] = $salaryParts[1];
		}
		if(isset($cmsPageArr['SalaryAvg'])){
			$salaryParts = explode(' ',$cmsPageArr['SalaryAvg']);
			$cmsPageArr['avg_salary'] = $salaryParts[0];
			$cmsPageArr['avg_salary_unit'] = $salaryParts[1];
		}
		if(isset($cmsPageArr['SalaryMin'])){
			// echo "<pre>"; print_r($cmsPageArr['SalaryMin']); die;
			$salaryParts = explode(' ',$cmsPageArr['SalaryMin']);
			$cmsPageArr['min_salary'] = $salaryParts[0];
			$cmsPageArr['min_salary_unit'] = $salaryParts[1];
			// echo "unit = ".$salaryParts[1]; die;
		}
		foreach($listingData[0]['courseFeatures'] AS $feature){
			$cmsPageArr[$feature['feature_name']] = $feature['value'];
		}

		//----------------------------------------------------------
		$courseCategoryList = $categoryClient->getCategoryCourseList();

		foreach($courseCategoryList AS $categoryList){
			//			/$courseList[$categoryList['categoryId']][$categoryList['groupName']][] = array($categoryList['courseId'] => $categoryList['courseName']);
			//$courseList[$categoryList['categoryId']][] = array($categoryList['groupName'] => array($categoryList['courseId'] => $categoryList['courseName']));
			$catList[$categoryList['categoryId']] = $categoryList['categoryName'];
		}
		$cmsPageArr['courseList'] = $courseList;
		$cmsPageArr['categoryList'] = $catList;
		//---------------------------------------------------------------------------------------------

		$wikiFields = unserialize(base64_decode($listingData[0]['wikiFields']));
		$reformattedFields = systemAndUserFieldsDataSegregation($wikiFields);
		$cmsPageArr['systemFieldsArr'] = $reformattedFields['systemFieldsArr'];
		$cmsPageArr['userFieldsArr'] = $reformattedFields['userFieldsArr'];
		$cmsPageArr['wikiData'] = $ListingClientObj->getWikiFields('1',$listing_type);
		$cmsPageArr['flow'] = 'upgrade';
		//$cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/editCourse';
		$cmsPageArr['formPostUrl'] = '/listing/posting/CoursePost/post';
		$cmsPageArr['courseId'] = $courseId;
		$cmsPageArr['categoryForLeftPanel'] = $this->getCategories();
		$instituteId =  $listingData[0]['institute_id'];

		$tmpArr=array();
		$tmpArr['listing_type_id'] = $courseId;
		$tmpArr['listing_type'] = 'course';
		$instiContacts = $ListingClientObj->getDraftAndLiveInstiContactIds(1,$tmpArr);

		$cmsPageArr['instiContacts']=$instiContacts;
		$cmsPageArr['coursesAlreadyAdded'] = $ListingClientObj->getCourseList('1',$instituteId,'"live","draft"');
		$cmsPageArr['skipActionUrl'] = '/enterprise/ShowForms/showMediaInstituteForm/institute/'.$instituteId;

		$categoryClient = new Category_list_client();
		$catSubcatList = $categoryClient->getCatSubcatList($listingData[0]['locations'][0]['country_id'],$listingData[0]['instituteType']);
		$subCategories = array();
		foreach($catSubcatList as $value){
			foreach($value['subcategories'] as $value2){
				$subCategories[] = $value2['catId'];
			}
		}
		$courseList = $categoryClient->getSubCategoryCourses(implode(",",$subCategories));
		$this->_addCategoryNameWithLDBCourses($courseList);
		
		$catSubcatCourseList = array();
		foreach($catSubcatList as $key=>$value){
			$catSubcatCourseList[$key] = $value;
			foreach($value['subcategories'] as $value2){
				$catSubcatCourseList[$key]['subcategories'][$value2['catId']]['courses'] =  $courseList[$value2['catId']];
			}
		}
		$cmsPageArr['catSubcatCourseList'] = $catSubcatCourseList;

		$cmsPageArr['ldbMappedCourses'] =  $categoryClient->getLdbMappedCourses($courseId);

		/* Add Exam Preparation Drop down */
		$this->load->library('blog_client');
		$blogClient = new Blog_client();
		$examsList = $blogClient->getExamsForProducts($appId);
		$cmsPageArr['examsList'] = $examsList;
		$cmsPageArr['prodId'] = '6';
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$cmsPageArr['clientId']));
		//course list
		$itcourseslist = $categoryClient->getTestPrepCoursesList(1);
		$cmsPageArr['itcourseslist'] = $itcourseslist;
		$cmsPageArr['coursepackType'] = $listingData[0]['packType'];
		$cmsPageArr['count_course_locations'] = count($listingData[0]['available_locations_of_courses']);
		$cmsPageArr['upgradeCourseForm'] = 'upgradeCourseForm';
        //$cmsPageArr['insttitle'] = $listingData['0']['title'];
         $cmsPageArr['coursettl'] = $listingData[0]['title'];
		/* Add Exam Preparation Drop down */
	//list of restricted ldb courses
	$cmsPageArr['restrictedLDBCourse'] = $this->getRestrictedLDBCourses();

        $this->load->library('OnlineFormEnterprise_client');
        $ofObj = new OnlineFormEnterprise_client();
        $cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);
                        
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	/*
	 function getCourses()
	 {
		$this->init();
		$categoryId = $this->input->post('categoryId');
		$id = $this->input->post('id');
		$categoryClient = new Category_list_client();
		$courseCategoryList = $categoryClient->getCategoryCourseList($categoryId);
		$uniquegroups = array();
		echo '<select name="courseMap_'.$id.'" id="courseMap" class="sLSel" style="width: 180px;" >';
		foreach($courseCategoryList AS $categoryList){
		$groupName = $categoryList['groupName'];
		if(!in_array($groupName,$uniquegroups)){
		echo '<optgroup label="'.$groupName.'"></optgroup>';
		$uniquegroups[$groupName] = $groupName;
		}
		echo '<option value="'.$categoryList['courseId'].'">&nbsp;&nbsp;&nbsp;&nbsp;'.$categoryList['courseName'].'</option>';
		}
		echo '</select>';
		}
		*/


	function getCourses()
	{
		$startTime = microtime(true); 
		$this->init();

		$categoryId = $this->input->post('categoryId');
		$id = $this->input->post('id');

		$categoryClient = new Category_list_client();
		$shiksha_courses = $categoryClient->getShikshaCourses($categoryId);

		echo '<select name="courseMap_'.$id.'" id="courseMap" class="sLSel" style="width: 180px;" >';

		foreach($shiksha_courses AS $shiksha_course)
		{
			echo '<option value="'.$shiksha_course['courseId'].'">'.$shiksha_course['courseName'].'</option>';
		}
		echo '</select>';
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * showCourseForm
	 * Common Function to Show Course Form
	 * @access  public
	 * @param   int $instituteId
	 * @param   array $lastActionResult
	 * @return  string (view)
	 * @ToDo
	 */
	function showCourseForm($instituteId,$lastActionResult)
	{
		$startTime = microtime(true);
		$this->init();
		if($this->input->post('instituteId')!=0){
			$instituteId = $this->input->post('instituteId');
		}
		if($this->input->post('selectedListingTypeId')!=0){
			$instituteId = $this->input->post('selectedListingTypeId');
		}

		$cmsUserInfo = $this->cmsUserValidation();
        //redirecting for enterprise usergroup
        if($cmsUserInfo['usergroup'] == 'enterprise') {
            header('Location:/enterprise/Enterprise/disallowedAccess');
            exit;
        }
		if($instituteId==''){
			header("location:/enterprise/Enterprise/index/7/choose");
			exit;
		}
		$ListingClientObj = new Listing_client();
		//$listingData = $ListingClientObj->getListingForEdit('1',$instituteId,'institute');
		
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/institutedetailsmodel');
		$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
		$listingData = $instituteDetailsModel->getInstituteDetails($instituteId);
		
		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }
                
		 //echo "<pre>".print_r($listingData,true)."</pre>"; die;
		$instituteUser = $this->_userDetails($listingData[0]['userId']);

		$flag = 'disallow';
		if($cmsUserInfo['usergroup'] == 'cms'){
			if($instituteUser['usergroup'] == 'cms'){
				$flag = 'allow';
				$clientId = $cmsUserInfo['userid'];
			}else{
				$clientId = $instituteUser['userid'];
				$flag = 'allow';
			}
		}else{
			if($cmsUserInfo['userid'] == $instituteUser['userid']){
				$flag = 'allow';
				$clientId = $cmsUserInfo['userid'];
			}
		}
		if($flag != 'allow'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);
		$clientId = $cmsUserInfo['clientId'];

		$cmsPageArr = array();
		$lastActionMessage = $this->getLastActionMessage($lastActionResult);
		$cmsPageArr = array_merge($cmsUserInfo,$lastActionMessage);
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['viewType'] = 2;
		$cmsPageArr['clientId'] = $clientId;
		$cmsPageArr['institute_id'] = $instituteId;
		$cmsPageArr['institute_name'] = $listingData[0]['title'];
		$cmsPageArr['institute_location'] = $listingData[0]['locations'][0]['city_name'].((strtolower($listingData[0]['locations'][0]['country_name']) != "india")?", ". $listingData[0]['locations'][0]['country_name']:"");
		switch($listingData[0]['institute_type']){
			case 'Test_Preparatory_Institute':
				$instituteType = 2;
				break;
			default:
			case 'Academic_Institute':
				$instituteType = 1;
				break;
		}

		// Assigning Multilocations..
		$cmsPageArr['locations'] = $listingData[0]['locations'];

		$cmsPageArr['instituteType'] = $instituteType;
		$clientDetails = $this->_userDetails($clientId);

		if($clientDetails['usergroup']!='cms'){
			$clientStatus = $this->checkTotalListingsOfUser($clientId);
			$cmsPageArr['paidStatus']=$clientStatus['paidStatus'];
		}
		$cmsPageArr['clientDetails'] = $clientDetails;

		$cmsPageArr['subscriptionId'] = $this->input->post('selectedSubs');
		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientId));

		$categoryClient = new Category_list_client();
		$catSubcatList = $categoryClient->getCatSubcatList($listingData[0]['locations'][0]['country_id'],$listingData[0]['instituteType']);
		$subCategories = array();
		foreach($catSubcatList as $value){
			foreach($value['subcategories'] as $value2){
				$subCategories[] = $value2['catId'];
			}
		}
		$courseList = $categoryClient->getSubCategoryCourses(implode(",",$subCategories));
		$this->_addCategoryNameWithLDBCourses($courseList);
		$catSubcatCourseList = array();
		foreach($catSubcatList as $key=>$value){
			$catSubcatCourseList[$key] = $value;
			foreach($value['subcategories'] as $value2){
				$catSubcatCourseList[$key]['subcategories'][$value2['catId']]['courses'] =  $courseList[$value2['catId']];
			}
		}
		$cmsPageArr['catSubcatCourseList'] = $catSubcatCourseList;
		
		//list of restricted ldb courses
		$cmsPageArr['restrictedLDBCourse'] = $this->getRestrictedLDBCourses();

		$cmsPageArr['country_list'] = $categoryClient->getCountries('1');
		$ListingClientObj = new Listing_client();
		$listing_type = 'course';
		$cmsPageArr['pageTitle'] = "Adding course to ".$listingData[0]['title'].", ".$listingData[0]['locations'][0]['city_name'];
		//$cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/addCourse';
		$cmsPageArr['formPostUrl'] = '/listing/posting/CoursePost/post';
		$cmsPageArr['flow'] = 'add';
		$cmsPageArr['wikiData'] = $ListingClientObj->getWikiFields('1',$listing_type);
		$cmsPageArr['categoryForLeftPanel'] = $this->getCategories();
		$cmsPageArr['coursesAlreadyAdded'] = $ListingClientObj->getCourseList('1',$instituteId,'"live","draft"');

		$tmpArr=array();
		$tmpArr['listing_type_id'] = $instituteId;
		$tmpArr['listing_type'] = 'institute';

		// $instiContacts = $ListingClientObj->getDraftAndLiveInstiContactIds(1,$tmpArr);

		$cmsPageArr['instiContacts']=$instiContacts;

		// $cmsPageArr['contactInfoInstitute']['contact_details_id'] = $listingData[0]['contact_details_id'];

		$cmsPageArr['skipActionUrl'] = '/enterprise/ShowForms/showMediaInstituteForm/institute/'.$instituteId;
		//echo "<pre>";print_r($cmsPageArr);echo "</pre>";
		/* Add Exam Preparation Drop down */
		$this->load->library('blog_client');
		$blogClient = new Blog_client();
		$examsList = $blogClient->getExamsForProducts($appId);
		$itcourseslist = $categoryClient->getTestPrepCoursesList(1);
		$cmsPageArr['itcourseslist'] = $itcourseslist;
		$cmsPageArr['examsList'] = $examsList;
		$cmsPageArr['prodId'] = '6';

        	$this->load->library('OnlineFormEnterprise_client');
        	$ofObj = new OnlineFormEnterprise_client();
        	$cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);

                //added for profile completion data
                if($listingData[0]['instituteType'] == 1) {
                         
                	$this->load->library('listing/ListingProfileLib');
			$cmsPageArr['score_array']  = $this->listingprofilelib->calculateProfileCompeletion($instituteId);     
   		}
		/* Add Exam Preparation Drop down */
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);
	if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}


	/**
	 * postDataForCourse
	 * Common function for CRUD operation on Course related Data
	 * @access  private
	 * @param   array
	 * @return  array
	 * @ToDo
	 */
	function _postDataForCourse() {
		$startTime = microtime(true);
		$this->init();
		$salientFeatures = base64_decode($this->input->post('c_salientFeatures'));
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		$ListingClientObj = new Listing_client();
		$data = array();
		parse_str($salientFeatures,$data);
		$data['dataFromCMS']=$flagArray['moderation'];
		$data['packType']=$flagArray['packType'];
		$data['username']=$flagArray['userid'];
		$data['institute_location']=$this->input->post('instituteLocation');
		$data['institute_name']=$this->input->post('instituteName');
		$data['date_form_submission_older']=$this->input->post('dateFormSubmissionOlder');
		$data['date_result_declaration_older']=$this->input->post('dateResultDeclarationOlder');
		$data['date_course_comencement_older']=$this->input->post('dateCourseComencementOlder');
		$data['subscriptionId']=$this->input->post('selectedSubs');
		$data['clientId'] =$this->input->post('clientId');
                $data['onBehalfOf'] =$this->input->post('onBehalfOf');
		//Basic Course details
		$data['institute_id'] =$this->input->post('instituteId');
		$data['courseTitle'] =addslashes($this->input->post('c_course_title',true));

		$data['institute_location_ids'] = $this->input->post('locationInfoHiddenVar');
		$data['head_ofc_location_id'] = $this->input->post('headOfclocationIdHiddenVar');
		$data['important_date_info_location'] = $this->input->post('important_date_info_location');
		$data['course_contact_details_locationwise'] = $this->input->post('course_contact_details_locationwise');
		//mandatory comments	
		$data['mandatory_comments'] = $this->input->post('mandatory_comments');
		$data['cmsTrackUserId'] = $this->input->post('cmsTrackUserId');
		$data['cmsTrackListingId'] = $this->input->post('cmsTrackListingId');
		$data['cmsTrackTabUpdated'] = $this->input->post('cmsTrackTabUpdated');
        	$data['course_request_brochure_link_year'] =   $this->input->post('c_brochure_panel_year',true);
		$data['request_brochure_link_delete'] = $this->input->post('request_brochure_link_delete',true);		
		$innerSeparatorChar = "|=#=|";
		$outerSeparatorChar = "||++||";

		$tmpLocIdArray = explode(", ", $this->input->post('locationInfoHiddenVar'));

		$locationFeeInfoArray = explode($outerSeparatorChar, $this->input->post('locationFeeInfoHiddenVar'));
		$locationFeeIDArray = explode($outerSeparatorChar, $this->input->post('locationFeeIDHiddenVar'));

		$multilocationCount = count($tmpLocIdArray);

		// error_log("\n\n locationFeeIDArray : ".print_r($locationFeeIDArray, true)."\n\n locationFeeInfoArray = ".print_r($locationFeeInfoArray, true),3,'/home/infoedge/Desktop/log.txt');

		for($i = 0; $i < $multilocationCount; $i++) {

			$locID = $tmpLocIdArray[$i];

			$key = array_search($locID, $locationFeeIDArray);
			// error_log("\n Key =  $key, locID = $locID",3,'/home/infoedge/Desktop/log.txt');
			if($key === FALSE)
			continue;

			$locationFeeInfoSubArray[$i] = explode($innerSeparatorChar, $locationFeeInfoArray[$key]);
			$data['locationFeeInfo'][$locID]['fee_value'] = $locationFeeInfoSubArray[$i][0];
			$data['locationFeeInfo'][$locID]['fee_unit'] = $locationFeeInfoSubArray[$i][1];
			 
		}

		// error_log("\n\n Multilocation Fee info $i: ".print_r($data['locationFeeInfo'], true),3,'/home/infoedge/Desktop/log.txt');

		/*if (($this->input->post('c_approvedBy12') == "-1")||($this->input->post('c_approvedBy12') == "")) {
		 $data['approvedBy']=$this->input->post('c_approvedBy[]');
		 } else {
		 $data['approvedBy']='';
		 }*/
		$approvedBy = $this->input->post('c_approvedBy');
		if(is_array($approvedBy)){
			$data['approvedBy']=implode(',', $approvedBy);
		}
		$data['courseType']=$this->input->post('c_modeOfLearning');

		$source_type = trim($this->input->post('c_source_type', true));
		$source_name = trim($this->input->post('c_source_name', true));
	
		$flow_check = trim($this->input->post('flow',true));
		if((!empty($source_type) && !empty($source_name)) || ($flow_check=='edit' && empty($source_type) && empty($source_name))) {
				$data['source_name'] = $source_name;
				$data['source_type'] = $source_type;
		}	

		//Seo Fields
		$data['listing_seo_url'] = $this->input->post('listing_seo_url');
		$data['listing_seo_title'] = $this->input->post('listing_seo_title');
		$data['listing_seo_description'] = $this->input->post('listing_seo_description');
		$data['listing_seo_keywords'] = $this->input->post('listing_seo_keywords');
		//Course Level different cases
		//TEST PREP FOR COURSE
		$tests_prep = $this->input->post('examPrepRelatedExams',true);
		$testsprepArray = array();
		$j=0;
		$flag_test_prep_other = 0;
		$data['tests_preparation_other'] = '';
		$data['tests_required_other'] = '';
		for ($i=0;$i<count($tests_prep);$i++)
		{
			if ($tests_prep[$i]!="-1")
			{
				$testsprepArray[$j] = $tests_prep[$i];
				$j++;
			}
			else
			{
				$flag_test_prep_other = 1;
			}
		}
		$data['tests_preparation'] = implode(",",$tests_prep);
		if ($flag_test_prep_other == 1)
		{
			$data['tests_preparation_other'] = 'true';
			$data['tests_preparation_exam_name'] =  $this->input->post('examPrepRelatedExamsOther');
		}
		//TEST PREP FOR COURSE

		$data['courseLevel']=$this->input->post('course_level');
		switch(strtolower($data['courseLevel'])){
			case 'dual degree':
				$data['courseLevel_1']=$this->input->post('degree_1');
				$data['courseLevel_2']=$this->input->post('degree_2');
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'degree':
				$data['courseLevel_1']=$this->input->post('degree');
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'diploma':
				$data['courseLevel_1']=$this->input->post('diploma');
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'certification':
				$data['courseLevel_1']='';
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			case 'vocational':
				$data['courseLevel_1']='';
				$data['courseLevel_2']='';
				$data['tests_preparation'] = '';
				$data['tests_preparation_other'] = '';
				break;
			default:
				$data['courseLevel_1']='';
				$data['courseLevel_2']='';
				break;
		}
		if(strtolower($data['courseLevel'])=="exam preparation"){
			$data['practiceTestsOffered1'] = $this->input->post('c_practiceTestsOffered_1');
			$data['practiceTestsOffered2'] = $this->input->post('c_practiceTestsOffered_2');
			$data['practiceTestsOffered3'] = $this->input->post('c_practiceTestsOffered_3');
			$data['practiceTestsOffered4'] = $this->input->post('c_practiceTestsOffered_4');
			$data['practiceTestsOffered5'] = $this->input->post('c_practiceTestsOffered_5');
			$morningClasses = $this->input->post('c_morningClasses');
			$data['morningClasses'] = $morningClasses[0];
			$eveningClasses = $this->input->post('c_eveningClasses');
			$data['eveningClasses'] = $eveningClasses[0];
			$weekendClasses = $this->input->post('c_weekendClasses');
			$data['weekendClasses'] = $weekendClasses[0];
		}

		// $data['category_id']=implode(",",$this->input->post('c_categories'));
		$data['category_id'] = "";
		$catLength = count($this->input->post('c_categories'));
		$catArray = $this->input->post('c_categories');
		for($i=0; $i < $catLength; $i++) {
			if($catArray[$i] == 0)
			continue;

			if($data['category_id'] == "") {
				$data['category_id'] = $catArray[$i];
			} else {
				$data['category_id'] .= ", ".$catArray[$i];
			}
		}

		// error_log("\n\n category_id ".print_r($data['category_id'], true),3,'/home/infoedge/Desktop/log.txt');

		$data['duration_value']=$this->input->post('duration_val');
		$data['duration_unit']=$this->input->post('duration_type');
		$data['duration']=$data['duration_value']." ".$data['duration_unit'];
		$data['fees_value']=$this->input->post('c_fees_amount');
		$data['fees_unit']=$this->input->post('c_fees_currency');
		$data['fees']=$data['fees_value']." ".$data['fees_unit'];
		$data['fees_disclaimer']=$this->input->post('c_fees_disclaimer');
		$data['seats_total']=$this->input->post('seats_total');
		$data['seats_general']=$this->input->post('seats_general');
		$data['seats_reserved']=$this->input->post('seats_reserved');
		$data['seats_management']=$this->input->post('seats_management');
		$data['date_form_submission']=$this->input->post('date_form_submission');
		$data['date_result_declaration']=$this->input->post('date_result_declare');
		$data['date_course_comencement']=$this->input->post('date_course_commence');
		$data['flow']=$this->input->post('flow');
		if($cmsUserInfo['usergroup'] == 'cms'){
			$data['hiddenTags'] = $this->input->post('i_tags');
		}
		//Contact Info
		$data['contact_details_id']=$this->input->post('contact_details_id');
		$data['contact_name']=$this->input->post('contact_name');
		$data['contact_main_phone']=$this->input->post('contact_phone');
		$data['contact_cell']=$this->input->post('contact_mobile');
		$data['contact_email']=$this->input->post('contact_email');
		//Wiki Section additions
		$wikiData = $ListingClientObj->getWikiFields('1','course');
		foreach($wikiData as $wikiField){
			$wikiField_key_name = trim($this->input->post($wikiField['key_name']));
				if($data['flow'] == 'edit'){
					if(empty($wikiField_key_name)){
						$wikiField_key_name = ' ';
					}	
				}
			if(strlen($wikiField_key_name) > 0 ){
				$data['wiki'][$wikiField['key_name']]=is_text_in_html_string($wikiField_key_name)?trimmed_tidy_repair_string($wikiField_key_name):'';
			}
		}
		$userCreatedWikisCaptions = $this->input->post('wikkicontent_main');
		$userCreatedWikisDetails = $this->input->post('wikkicontent_detail');

		$data['wiki']['user_fields'] = array();
		for($i = 0; $i < count($userCreatedWikisCaptions); $i++){
			if(strlen($userCreatedWikisCaptions[$i]) >0){
				if (( $userCreatedWikisCaptions[$i] != 'Enter Title' ) && ( $userCreatedWikisDetails[$i] != 'Enter Description' ) ) {
					$data['wiki']['user_fields'][$i]['caption']=trimmed_tidy_repair_string($userCreatedWikisCaptions[$i]);
					$data['wiki']['user_fields'][$i]['caption'] = str_replace("\n", " ", $data['wiki']['user_fields'][$i]['caption']);
					$data['wiki']['user_fields'][$i]['value']=is_text_in_html_string($userCreatedWikisDetails[$i]) ?trimmed_tidy_repair_string($userCreatedWikisDetails[$i]):'';
				}
			}
		}

		//New Additions
		for($i=1; $i<=LDB_COURSE_MAPPING_LIMIT; $i++) {
			$data['courseMapId_'.$i] = $this->input->post('courseMapId_'.$i);
			$data['courseMap_'.$i] = $this->input->post('courseMap_'.$i);
		}
		
		$affiliatedTo = $this->input->post('c_affiliatedTo');
		if(is_array($affiliatedTo)){
			$data['affiliatedTo'] = implode(',', $affiliatedTo);
		}
		//$affiliatedToName = $this->input->post('c_affiliatedToName');
		//if(is_array($affiliatedToName)){
		//}
		$data['affiliatedToIndianUniName'] = $this->input->post('c_affiliatedToIndianUniName');
		$data['affiliatedToForeignUniName'] = $this->input->post('c_affiliatedToForeignUniName');
		$data['accreditedBy']=$this->input->post('c_accreditedBy');
		$data['entranceExam1']=$this->input->post('c_entranceExam_1');
		$data['entranceExam2']=$this->input->post('c_entranceExam_2');
		$data['entranceExam3']=$this->input->post('c_entranceExam_3');
		$data['entranceExam4']=$this->input->post('c_entranceExam_4');
		$data['entranceExam5']=$this->input->post('c_entranceExam_5');
		$data['entranceExamMarks1']=$this->input->post('c_entranceExamMarks_1');
		$data['entranceExamMarks2']=$this->input->post('c_entranceExamMarks_2');
		$data['entranceExamMarks3']=$this->input->post('c_entranceExamMarks_3');
		$data['entranceExamMarks4']=$this->input->post('c_entranceExamMarks_4');
		$data['entranceExamMarks5']=$this->input->post('c_entranceExamMarks_5');
		$data['entranceExamMarks5']=$this->input->post('c_entranceExamMarks_5');
		$data['entranceExamMarksType1']=$this->input->post('c_entranceExamMarksType_1');
		$data['entranceExamMarksType2']=$this->input->post('c_entranceExamMarksType_2');
		$data['entranceExamMarksType3']=$this->input->post('c_entranceExamMarksType_3');
		$data['entranceExamMarksType4']=$this->input->post('c_entranceExamMarksType_4');
		$data['entranceExamMarksType5']=$this->input->post('c_entranceExamMarksType_5');
		$data['entranceExamMarksType5']=$this->input->post('c_entranceExamMarksType_5');
		//Other
		//		$data['otherEligibilityCriteria1']=$this->input->post('c_otherEligibilityCriteria_1');
		//		$data['otherEligibilityCriteria2']=$this->input->post('c_otherEligibilityCriteria_2');
		//		$data['otherEligibilityCriteria3']=$this->input->post('c_otherEligibilityCriteria_3');
		//		$data['otherEligibilityCriteria4']=$this->input->post('c_otherEligibilityCriteria_4');
		//		$data['otherEligibilityCriteria5']=$this->input->post('c_otherEligibilityCriteria_5');

		$data['otherEligibilityCriteria']=$this->input->post('c_otherEligibilityCriteria');
		
		if($this->input->post('c_max_salary')!=''){
			$data['maxSalary']=$this->input->post('c_max_salary');//.' '.$this->input->post('c_min_salary_currency');
		}
		if($this->input->post('c_avg_salary')){
			$data['avgSalary']=$this->input->post('c_avg_salary');//.' '.$this->input->post('c_min_salary_currency');
		}
		if($this->input->post('c_min_salary')){
			$data['minSalary']=$this->input->post('c_min_salary');//.' '.$this->input->post('c_min_salary_currency');
		}

		$data['SalaryCurrency']=$this->input->post('c_min_salary_currency');

		if($this->input->post('c_freeLaptop')!=''){
			$data['freeLaptop'] = $this->input->post('c_freeLaptop');
		}
		if($this->input->post('c_foreignStudy')){
			$data['foreignStudy'] = $this->input->post('c_foreignStudy');
		}
		if($this->input->post('c_studyExchange')!=''){
			$data['studyExchange'] = $this->input->post('c_studyExchange');
		}
		if($this->input->post('c_jobAssurance')){
			$data['jobAssurance'] = $this->input->post('c_jobAssurance');
		}
		if($this->input->post('c_dualDegree')){
			$data['dualDegree'] = $this->input->post('c_dualDegree');
		}
		if($this->input->post('c_hostel')){
			$data['hostel'] = $this->input->post('c_hostel');
		}
		if($this->input->post('c_transport')){
			$data['transport'] = $this->input->post('c_transport');
		}
		if($this->input->post('c_freeTraining')){
			$data['freeTraining'] = $this->input->post('c_freeTraining');
		}
		if($this->input->post('c_wifi')){
			$data['wifi'] = $this->input->post('c_wifi');
		}
		if($this->input->post('c_acCampus')){
			$data['acCampus'] = $this->input->post('c_acCampus');
		}
		$data['cType'] = $this->input->post('courseType');
		// fees types added
		$c_feestypes = $this->input->post('c_feestypes',true) ? $this->input->post('c_feestypes',true) : array();
        $data['feestypes'] =  implode(",", $c_feestypes);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $data;
	}

	/**
	 * UploadCourseApplicationDoc
	 * function for Upload Doc in Course form
	 * @access  public
	 * @param
	 * @return
	 * @ToDo
	 */
	function UploadCourseApplicationDoc()
	{
		$startTime = microtime(true);
		$logoArr = array();
		if ( $this->input->post('applicationForm') == 'upload') {
			$this->load->library('upload_client');
			$uploadClient = new Upload_client();
			$inst_logo = "applicationDoc";
			if(isset($_FILES['course_app_form']['tmp_name'][0]) && ($_FILES['course_app_form']['tmp_name'][0] != ''))
			{
				$i_upload_logo = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","course",'course_app_form');

				if($i_upload_logo['status'] == 1)
				{
					for($k = 0;$k < $i_upload_logo['max'] ; $k++)
					{
						$tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
						list($width, $height, $type, $attr) = $tmpSize;
						$logoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
						$logoArr['url']=$i_upload_logo[$k]['imageurl'];
						$logoArr['title']=$i_upload_logo[$k]['title'];
						$logoArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
						$logoArr['width']=$width;
						$logoArr['height']=$height;
						$logoArr['type']=$type;
					}
				} else {
					$logoArr['error'] = $i_upload_logo;
					$logoArr['url'] = "";
				}
			}
			else if(!(isset($_FILES['course_app_form']['tmp_name'][0]) && ($_FILES['course_app_form']['tmp_name'][0] != '')) && ($this->input->post('applicationForm_removed')==1))
			{
				$logoArr['url'] = "";
			}

		} elseif ($this->input->post('applicationForm') == 'url') {
			$course_form_url = $this->input->post('course_form_url');
			$logoArr['url']=$course_form_url;
		}
	if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $logoArr;
	}

	/**
	 * function to check Institute Duplicacy
	 * @access  public
	 * @param int pincode
	 * @param string institute title
	 * @param int (optional) instituteID
	 * @return string duplicacy check response
	 * @ToDo
	 */
	function check_Institute_name($pinCodevalue,$instituteName,$instituteId = -1)
	{
		$startTime = microtime(true);
		$this->init();
		$ListingClientObj = new Listing_client();
		$instituteName = base64_decode($instituteName);
                $pinCodevalue = base64_decode($pinCodevalue);
		$dupData = $ListingClientObj->checkInstituteDuplicacy('1',$instituteId,$instituteName,$pinCodevalue);
		if(count($dupData) == 0){
			echo '0';
		}
		else{
			echo "We already have this institute in shiksha: '".$dupData['0']['institute_name']."'  ";
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * function to check Course Duplicacy
	 * @access  public
	 * @param int pincode
	 * @param int instituteID
	 * @param string course title
	 * @param string course type
	 * @param int (optional) instituteID
	 * @return string duplicacy check response
	 * @ToDo
	 */
	function check_course_name($courseId='-1',$instituteid,$coursetype,$courseTitle)
	{
		$startTime = microtime(true);
		$this->init();
		$ListingClientObj = new Listing_client();
		$dupData = $ListingClientObj->checkCourseDuplicacy('1',$courseId,$instituteid,$courseTitle,$coursetype);
		if(count($dupData) == 0){
			echo '0';
		}
		else{
			echo "We already have this course on shiksha. ".$dupData['0']['courseTitle'].",  ".$dupData['0']['institute_name'];
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * function to choose Subscription before posting a listing
	 * @access  public
	 * @param array $_POST
	 * @param int instituteID
	 * @param string course title
	 * @param string course type
	 * @param int (optional) instituteID
	 * @return string duplicacy check response
	 * @ToDo
	 */
	function chooseListingSubscription($instituteId="-1",$clientId="-1") {
		$startTime = microtime(true);
		//echo "<pre>";echo print_r($_POST); echo "</pre>";
		$appId = 1;

		$cmsUserInfo = $this->cmsUserValidation();
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $cmsUserInfo['userid'];
		$cmsPageArr['usergroup'] = $cmsUserInfo['usergroup'];
		$cmsPageArr['thisUrl'] = $cmsUserInfo['thisUrl'];
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$this->init();

		$clientUserId;

		if($clientId !="-1"){
			$clientUserId  = $clientId;
		}

		$onBehalfOf = $this->input->post('onBehalfOf');
		if ($onBehalfOf=="true")
		{
			$clientUserId = $this->input->post('selectedUserId',true);
		}

		$entObj = new Enterprise_client();

		$cmsPageArr['prodId'] = '23';
		$cmsPageArr['instituteId'] = $instituteId;

		$cmsPageArr['userDetails'] = $this->_userDetails($clientUserId);
		$cmsPageArr['userDetails']['clientUserId'] = $clientUserId;

		$this->load->library('sums_product_client');
		$objSumsProduct =  new Sums_Product_client();
		$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$clientUserId));
		//$cmsPageArr['userProducts'] = $this->totalListingsOfUser($clientUserId);

		/*
		 $params = array();
		 $cmsPageArr['productInfo'] = $objSumsProduct->getProductFeatures(1,$params);
		 */

		// echo "<pre>";print_r($cmsPageArr);echo "</pre>";
		$this->load->view('listing_forms/packSeletionListing',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * Total Listings of a User with Type information
	 * @access  public
	 * @param int $userid
	 * @return Array array having user details
	 * @ToDo
	 */
	function checkTotalListingsOfUser($userid){
		$startTime = microtime(true);
		$this->init();
		/*
		 $this->load->library('sums_product_client');
		 $objSumsProduct = new Sums_Product_client();
		 $userProducts = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
		 $remainingPremium = 0;
		 $remainingTrial = 0;

		 foreach ($userProducts as $product){
		 if ($product['BaseProdCategory']=="Listing")
		 {
		 if ($product['BaseProdSubCategory']=="Premium")
		 {
		 $remainingPremium += $product['RemainingQuantity'];
		 }
		 if ($product['BaseProdSubCategory']=="Trials")
		 {
		 $remainingTrial += $product['RemainingQuantity'];
		 }
		 }
		 }
		 */
		$this->load->library('sums_manage_client');
		$objSumsManage = new Sums_manage_client();
		$paidFlag = 'false';

		$clientType = $objSumsManage->findClientTypeByBaseCatAndSubCat($userid,'Listing','Gold SL');
		if($clientType['paidStatus']=='true'){
			$paidFlag = 'true';
		}

		$clientType = $objSumsManage->findClientTypeByBaseCatAndSubCat($userid,'Listing','Gold ML');
		if($clientType['paidStatus']=='true'){
			$paidFlag = 'true';
		}

		$clientType = $objSumsManage->findClientTypeByBaseCatAndSubCat($userid,'Listing','Silver');
		if($clientType['paidStatus']=='true'){
			$paidFlag = 'true';
		}

		$clientType['paidStatus'] = $paidFlag;
		/*
		 if($clientType['paidStatus']=='true'){
		 if($remainingPremium <= 0) {
		 header("location:/enterprise/Enterprise/prodAndServ");
		 exit();
		 }
		 }else if($clientType['paidStatus']=='false'){
		 if($remainingTrial<= 0) {
		 header("location:/enterprise/Enterprise/buyListing");
		 exit();
		 }
		 }
		 */
	if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		//echo "<pre>";print_r($userProducts);echo "</pre>";
		return $clientType;
	}


	/**
	 * function to get user details for a userID
	 * @access  private
	 * @param int $userid
	 * @return Array array having user details
	 * @ToDo
	 */
	function _userDetails($userId)
	{
		$this->load->library('register_client');
		$regObj = new Register_client();
		$arr = $regObj->userdetail(1,$userId);
		$userDetails = $arr[0];
		return $userDetails;
	}

	/**
	 * function to choose an Institute for posting a course
	 * @access public
	 * @param array (optional) $_POST
	 * @param int (optional) clientId
	 * @return view with institute listing for selected enterprise user
	 * @ToDo
	 */
	function chooseInstitute($clientId='-1') {
		$startTime = microtime(true);
		//echo "<pre>";echo print_r($_POST); echo "</pre>";
		$appId = 1;

		$cmsUserInfo = $this->cmsUserValidation();
		$cmsPageArr = array();
		$cmsPageArr['userid'] = $cmsUserInfo['userid'];
		$cmsPageArr['usergroup'] = $cmsUserInfo['usergroup'];
		$cmsPageArr['thisUrl'] = $cmsUserInfo['thisUrl'];
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$this->init();

		$clientUserId;

		if($clientId !="-1"){
			$clientUserId  = $clientId;
		}else if($this->input->post('selectedUserId',true)!=0){
			$clientUserId = $this->input->post('selectedUserId',true);
		}else{
			$clientUserId  = $cmsPageArr['userid'];
		}

		$cmsUserInfo = $this->checkFlowCase($clientUserId,$cmsUserInfo);
		$onBehalfOf = $cmsUserInfo['onBehalfof'];
		if ($onBehalfOf=="true")
		{
			$cmsPageArr['userDetails'] = $this->_userDetails($clientUserId);
		}

		$cmsPageArr['userid'] =  $clientUserId;
		$entObj = new Enterprise_client();
		$userArr['userid'] = $clientUserId;
		$userArr['listingType'] = 'institute';
		$userArr['startFrom'] = 0;
		$userArr['countOffset'] = 250;
		$userListings = $entObj->getListingsByClientAndType($appId,$userArr);
		if($userListings == NULL){
			header("location:/enterprise/ShowForms/showInstituteForm/$clientUserId");
		}
		$cmsPageArr['clientListings'] = $userListings;

		$cmsPageArr['prodId'] = '7';

		//echo "<pre>";echo print_r($cmsPageArr); echo "</pre>";
		$this->load->view('listing_forms/chooseInstitute',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * function to show media form (form no. 3) for a given institute id
	 * @access  public
	 * @param string $listing_type (default:institute),
	 * @param int $listing_type_id ,
	 * @return echo media form
	 * @ToDo
	 */

	function showMediaInstituteForm($listingType='institute',$listingTypeId) {
		$startTime = microtime(true);
		ini_set('memory_limit','1024M');
		$appId = '1';
		$this->init();
		if($this->input->post('instituteId')!=0){
			$instituteId = $this->input->post('instituteId');
		}
		$cmsUserInfo = $this->cmsUserValidation();
		if($listingTypeId==''){
			header("location:/enterprise/Enterprise/index/7");
			exit;
		}
        //redirecting for enterprise usergroup
        if($cmsUserInfo['usergroup'] == 'enterprise') {
            header('Location:/enterprise/Enterprise/disallowedAccess');
            exit;
        }
		
		$this->load->model('listing/posting/mediadetailsmodel');
		$mediaDetailsModel = new MediaDetailsModel();
		
		$ListingClientObj = new Listing_client();
		//$listingData = $ListingClientObj->getListingForEdit('1',$listingTypeId,'institute');
		
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/institutedetailsmodel');
		$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
		$listingData = $instituteDetailsModel->getInstituteDetails($listingTypeId);

		if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
                    show_404();
                }
                
		$instituteUser = $this->_userDetails($listingData[0]['userId']);
		
		$flag = 'disallow';
		if($cmsUserInfo['usergroup'] == 'cms'){
			if($instituteUser['usergroup'] == 'cms'){
				$flag = 'allow';
			}else{
				$clientId = $instituteUser['userid'];
				$flag = 'allow';
			}
		}else{
			if($cmsUserInfo['userid'] == $instituteUser['userid']){
				$flag = 'allow';
				$clientId = $cmsUserInfo['userid'];
			}
		}
		if($flag != 'allow'){
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}

		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);
		$clientId = $cmsUserInfo['clientId'];

		$cmsPageArr = array();
		$lastActionMessage = $this->getLastActionMessage($lastActionResult);
		$cmsPageArr = array_merge($cmsUserInfo,$lastActionMessage);
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];

		$userid = $cmsUserInfo['userid'];
		$usergroup = $cmsUserInfo['usergroup'];
		$displayData = $cmsPageArr;
		$displayData['listingType'] = $listingType;
		$displayData['listingTypeId'] = $listingTypeId;
		$displayData['viewType'] = 3;
		$instituteId = $listingTypeId;
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		//$listingMediaDetails= json_decode(base64_decode($ListingMediaClientObj->getMediaDetailsForListing($appId, $listingType, $listingTypeId)), true);
		$listingMediaDetails = $mediaDetailsModel->getMediaDetails($listingTypeId);
	
		foreach($listingMediaDetails as $mediaType => $mediaDetail) {
			$displayData[$mediaType] = $mediaDetail;
		}

		$this->load->library('listing_media_client');
		//$disLogo=$this->listing_media_client->getDistinctLogo($listingTypeId);
		$disLogo=$mediaDetailsModel->getRecruitingCompanies($listingTypeId);
	
		$indo=1;
		$logoarr = array();
		foreach($disLogo as $key=> $value)
		{
			$tempArray= array();
			$tempArrayId= array();
			$tempArrayType= array();
			foreach($value as $k=>$v)
			{
				if( $k == 'company_name')
				$tempArray['company_name']=$v;
				if( $k == 'company_order')
				$tempArray['company_order']=$v;
				if( $k == 'logo_id')
				$tempArray['logo_id']= $v;
				if( $k == 'logo_url')
				$tempArray['logo_url']= $v;
				if( $k == 'listing_ids')
				$tempArrayId= explode(",",$v);
				if( $k == 'listing_types')
				$tempArrayTypes= explode(",",$v);
			}
			$h=0;
			foreach($tempArrayTypes as $ind=> $val){
				if($val == 'institute')
				$h++;}
				if($h ==0)
				$tempArray['institute']= 0;
				else
				$tempArray['institute']= 1;
				$kin=0;
				foreach($tempArrayTypes as $indx=> $vale){
					if( $vale== 'course')
					$kin++;}

					if($kin ==0)
					$tempArray['iscourse']= 0;
					else
					$tempArray['iscourse']= 1;

					$j=1;
					$cc=0;
					$abc=0;
					foreach($tempArrayId as $ind=> $val){


						if( $tempArrayTypes[$abc] != 'institute')
						{
							$tempArray['courseId'.$j]=$val;
							$cc++;
							$j++;

						}
						$abc++;
					}
					$tempArray['course_count']=$cc;
					array_push($logoarr, $tempArray);
					$indo++;

		}
		$displayData['logoarr']=$logoarr;
		$displayData['logo_count']=$indo-1;

		$indo--;

		$this->load->library('listing_media_client');
		//$disHeader=$this->listing_media_client->getDistinctHeader($listingTypeId);
		$disHeader = $mediaDetailsModel->getHeaderImages($listingTypeId);
		$indo=1;
		$Header = array();
		foreach($disHeader as $key=> $value)
		{
			$tempArray= array();
			$tempArrayId= array();
			$tempArrayType= array();
			foreach($value as $k=>$v)
			{
				if( $k == 'name')
				$tempArray['name']=$v;
				if( $k == 'header_order')
				$tempArray['header_order']=$v;
				if( $k == 'large_url')
				$tempArray['large_url']=$v;
				if( $k == 'thumb_url')
				$tempArray['thumb_url']=$v;
				if( $k == 'listing_ids')
				$tempArrayId= explode(",",$v);
				if( $k == 'listing_types')
				$tempArrayTypes= explode(",",$v);
			}
			$h=0;
			foreach($tempArrayTypes as $ind=> $val)
			{
				if($val == 'institute')
				$h++;
			}

			if($h ==0)
			$tempArray['institute']= 0;
			else
			$tempArray['institute']= 1;

			$kin=0;
			foreach($tempArrayTypes as $indx=> $vale)
			{
				if( $vale== 'course')
				$kin++;
			}

			if($kin ==0)
			$tempArray['iscourse']= 0;
			else
			$tempArray['iscourse']= 1;

			$j=1;
			$cc=0;
			$abc=0;
			foreach($tempArrayId as $ind=> $val)
			{

				if( $tempArrayTypes[$abc] != 'institute')
				{
					$tempArray['courseId'.$j]=$val;
					$cc++;
					$j++;

				}
				$abc++;
			}

			$tempArray['course_count']=$cc;
			array_push($Header, $tempArray);
			$indo++;

		}
		$displayData['Header']=$Header;
		$displayData['header_count']=$indo-1;
		$indo--;


		if($cmsUserInfo['usergroup'] == 'cms')
		$displayData['isHeader']=1;
		else
		$displayData['isHeader']=0;

		$ListingClientObj = new Listing_client();
		$displayData['coursesForInstitute'] = $ListingClientObj->getCourseList('1',$instituteId,'"live","draft"');
		$displayData['validateuser'] = $this->userStatus;
		$displayData['prodId'] = '7';
		$displayData['pageTitle'] = "Media Manager for ".$listingData['0']['title'];
		//retrieveing company logo listing & adding it to displaydata
		$sortClass= 'All';
		$clObj = new Enterprise_client();
		$this->load->library('enterprise_client');
		$companyLogo= $this->enterprise_client->getCompanyLogo($sortClass,-1,10,$countBit);
		$displayData['companyLogoListing']=$companyLogo;
		// extract location info of institute starts here
		$multi_location = $listingData[0]['locations'];
		$locations_array = array();
		if(is_array($multi_location) && count($multi_location)>0) {
			foreach ($multi_location as $location) {
				$city_name = $location['city_name'];
				$locality_name = $location['locality'];
				$key = $city_name;
				if(!empty($locality_name)) {
					$key = $city_name."-".$locality_name;
				}
				$locations_array[$key] = array("name"=>$key,"id"=>$location['institute_location_id']);
			}
		}
		ksort($locations_array);
		$displayData['multi_locations'] = $locations_array;
		//_p($locations_array);
		// extract location info of institute ends here
		$this->load->view('listing_forms/new_homepage',$displayData);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function mapCourseCompany($data){
		$startTime = microtime(true);
		$noCompany = $_POST['noCompany'];
		$logoId= $_POST['logoId'];
		$listingType = $_POST['listingType'];
		$listingId=$_POST['listingId'];
		$order= $_POST['order'];
		$instituteId= $_POST['instituteId'];
		$companyParam= array();
		$companyParam[0]= $logoId;
		$companyParam[1]= $listingType;
		$companyParam[2]= $listingId;
		$companyParam[3]= $order;
		$companyParam[4]= $instituteId;
		$companyParam[5]= $noCompany;
		$this->load->library('listing_media_client');
		$comLogo=$this->listing_media_client->mapCourseCompany($companyParam);

		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}





	function addLogoListing($data)
	{


		$startTime = microtime(true);
		$id= $_POST['id'];
		$listingTypeId= $_POST['listingTypeId'];
		$this->load->library('enterprise_client');
		$comLogo=$this->enterprise_client->addLogoListing($id);

		foreach($comLogo as $n => $m)
		{
			foreach($m as $key => $value)
			{
				if( $key == 'id')
				$id=$value;
				if( $key == 'company_name')
				$name= $value;
				if( $key == 'logo_url')
				$url= $value;
			}

		}

		$request = json_encode($comLogo);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		echo $request;

	}

	//Uploading Header Images on the overlay through Ajax Iframe Method (AIM)
	function uploadThumbHeaderImage( $vcard = 0,$appId = 1){
		$startTime = microtime(true);

		$this->init();
		if($_FILES['myImage']['tmp_name'][0] == '')
		echo "Please select a photo to upload";
		else
		{
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$userId,"user", 'myImage');
			if(!is_array($upload))
			echo $upload;
			else
			{
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				if(!($width == 124 && $height == 104)) {
					echo 'Image size must be 124*104 px';
                                } else {
					echo $upload[0]['imageurl'];
                                }
			}
		}
	  if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}

	//Uploading Header Images on the overlay through Ajax Iframe Method (AIM)
	function uploadLargeHeaderImage( $vcard = 0,$appId = 1){
		$startTime = microtime(true);

		$this->init();
		if($_FILES['myImage']['tmp_name'][0] == '')
		echo "Please select a photo to upload";
		else
		{

			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$userId,"user", 'myImage');
			if(!is_array($upload))
			echo $upload;
			else
			{
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				if(!($width == 303 && $height == 210)) {
					echo 'Image size must be 303*210 px';
                                } else {
					echo $upload[0]['imageurl'];
                                }

			}
		}
	 if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}



	function mapHeader($data){
		$startTime = microtime(true);

		$noHeader = $_POST['noHeader'];
		$thumbURL= $_POST['thumbURL'];
		$largeURL= $_POST['largeURL'];
		$listingType = $_POST['listingType'];
		$listingId=$_POST['listingId'];
		$order= $_POST['order'];
		$instituteId= $_POST['instituteId'];
		$name=$_POST['name'];
		$companyParam= array();
		$companyParam[0] = $listingType;
		$companyParam[1]= $listingId;
		$companyParam[2]= $order;
		$companyParam[3]= $thumbURL;
		$companyParam[4]= $largeURL;
		$companyParam[5]= $name;
		$companyParam[6]= $instituteId;
		$companyParam[7]= $noHeader;
		$this->load->library('listing_media_client');
		$comLogo=$this->listing_media_client->mapHeader($companyParam);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function mapCourseHeader($data){
		$startTime = microtime(true);
		$thumbURL= $_POST['thumbURL'];
		$largeURL= $_POST['largeURL'];
		$listingType = $_POST['listingType'];
		$listingId=$_POST['listingId'];
		$order= $_POST['order'];
		$instituteId= $_POST['instituteId'];
		$name=$_POST['name'];
		$companyParam= array();
		$companyParam[0] = $listingType;
		$companyParam[1]= $listingId;
		$companyParam[2]= $order;
		$companyParam[3]= $thumbURL;
		$companyParam[4]= $largeURL;
		$companyParam[5]= $name;
		$companyParam[6]= $instituteId;
		$this->load->library('listing_media_client');
		$comLogo=$this->listing_media_client->mapCourseHeader($companyParam);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}



	/**
	 * function to upload media to media server &
	 *       put it to institute uploaded media items for linking with listings
	 * @access  public
	 * @param _FILES to be uploaded,
	 * @return echo media form
	 * @ToDo
	 */



	function uploadMedia($formId, $mediaType) {

		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$ListingClientObj = new Listing_client();
		$fileCaption= $this->input->post('fileNameCaption');
		$institute_location_id = $this->input->post('institute_location_id');
		$fileName = split("[/\\.]",$_FILES['mediaFile']['name'][0]);
		$fileExtension = $fileName[count($fileName) - 1];
		$fileCaption .= $fileExtension == '' ? '' : '.'. $fileExtension;
		$listingId = $this->input->post('listingId');
		$listingType = $this->input->post('listingType');
		$this->load->library('upload_client');
		$uploadClient = new Upload_client();
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		switch($mediaType) {
			case 'photos':
				$mediaDataType = 'image';
				$listingMediaType = 'photos';
				$FILES = $_FILES;
				break;
			case 'videos':
				$mediaDataType = 'ytvideo';
				$listingMediaType = 'videos';
				$FILES = $_POST['mediaFile'];
				break;
			case 'documents':
				$mediaDataType = 'pdf';
				$listingMediaType = 'doc';
				$FILES = $_FILES;
				break;
		}

		$upload_forms = $uploadClient->uploadFile($appId,$mediaDataType,$FILES,array($fileCaption),$listingId, $listingType,'mediaFile');
		if(is_array($upload_forms)) {
			$updateListingMedia = null;
			if($upload_forms['status'] == 1){
				for($k = 0;$k < $upload_forms['max'] ; $k++){
					//It will always be 1 :-). Added for future cases if multiple uploads will be asked in one go.
					$reqArr = array();
					$reqArr['mediaId']=$upload_forms[$k]['mediaid'];
					$reqArr['mediaUrl']=$upload_forms[$k]['imageurl'];
					$reqArr['mediaName']=$upload_forms[$k]['title'];
					$reqArr['mediaThumbUrl']=$upload_forms[$k]['thumburl'];
					$reqArr['institute_location_id'] = $institute_location_id;
					$updateListingMedia = $ListingMediaClientObj->mapMediaContentWithListing($appId,$listingId,$listingType,$listingMediaType,base64_encode(json_encode($reqArr)));
				}
			}
			$displayData['fileId'] = $reqArr['mediaId'];
			$displayData['fileName'] = $fileCaption;
			$displayData['mediaType'] = $mediaType;
			$displayData['fileUrl'] = $reqArr['mediaUrl'];




			$displayData['fileThumbUrl'] = $reqArr['mediaThumbUrl'];
		} else {
			$displayData['error'] = $upload_forms;
		}
		$displayData['formId'] = $formId;


		echo json_encode($displayData);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * function to change the caption of media item
	 * @access  public
	 * @param attribute changed & new value
	 * @return echo media form
	 * @ToDo
	 */




	function updateMediaField() {
		$startTime = microtime(true);
		$fieldName = $this->input->post('fieldName');
		$fieldValue = $this->input->post('fieldValue');
		$fileId = $this->input->post('fileId');
		$fileType = $this->input->post('fileType');
		$listingType = $this->input->post('listingType');
		$listingId = $this->input->post('listingId');

		switch($fieldName) {
			case 'fileNameCaption' : $fieldName = 'name';
			break;
			default : $fieldName = '';
		}
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		$mediaUpdateStatus = $ListingMediaClientObj->updateMediaAttributesForListing($appId, $listingType, $listingId, $fileType, $fileId, $fieldName, $fieldValue);
		echo json_encode($mediaUpdateStatus);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/**
	 * function to delete media item
	 * @access  public
	 * @param media item to be deleted for which listing
	 * @return echo media form
	 * @ToDo
	 */

	function deleteMedia($listingType, $listingId, $fileType, $fileId) {
		$startTime = microtime(true);
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		$deleteMediaStatus = $ListingMediaClientObj->removeMediaForListing($appId, $listingType, $listingId, $fileType, $fileId);
		echo json_encode($deleteMediaStatus);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function getUploadedMediaAssociation(){
		$startTime = microtime(true);
		$this->init();
		$this->load->model('ListingModel');
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		$ListingClientObj = new Listing_client();
		$data = array();
		$data['dataFromCMS']=$flagArray['moderation'];
		$data['packType']=$flagArray['packType'];
		$data['username']=$flagArray['userid'];

		$newMediaDataArray = array();
		$mediaAssociation = json_decode($this->input->post('mediaAssoc'),true);
		//   pa($mediaAssociation);


		foreach($mediaAssociation as $mediaType => $mediaTypeAssociations) {
			foreach($mediaTypeAssociations as $mediaId => $mediaIdAssociations) {
				foreach($mediaIdAssociations as $mediaIdAssociation) {
					$listingType = $mediaIdAssociation['entityName'];
					$listingId = $mediaIdAssociation['entityValue'];
					$mediaType = $this->ListingModel->getMediaType($mediaType);
					if($listingId > 0){
						$newMediaDataArray[$listingType][$listingId][$mediaType][$mediaId] = 'addition';
					}
					$listingId = $mediaIdAssociation['removedEntity'];
					if($listingId > 0){
						$newMediaDataArray[$listingType][$listingId][$mediaType][$mediaId] = 'removal';
					}
					$listingId = $mediaIdAssociation['modifiedEntity'];
					if($listingId > 0){
						$newMediaDataArray[$listingType][$listingId][$mediaType][$mediaId] = 'removal';
					}

				}
			}
		}

		//    pa($newMediaDataArray);
		//    pa($data);


		$listingType = $this->input->post('listingType');
		$listingId = $this->input->post('listingId');
		//mandatory comments
		$commentData = array();
		$commentData['mandatory_comments'] = $this->input->post('mandatory_comments');
		$commentData['cmsTrackUserId'] = $this->input->post('cmsTrackUserId');
		$commentData['cmsTrackListingId'] = $this->input->post('cmsTrackListingId');
		$commentData['cmsTrackTabUpdated'] = $this->input->post('cmsTrackTabUpdated');
		
		$this->load->library('Listing_media_client');
		$ListingMediaClientObj= new Listing_media_client();
		$updateStatus = $ListingMediaClientObj->associateMedia($appId, $listingType, $listingId, base64_encode(json_encode($newMediaDataArray)), base64_encode(json_encode($data)), base64_encode(json_encode($commentData)));
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		if($updateStatus){
			header("location:/enterprise/ShowForms/showPreviewPage/".$listingId);
		}
		else{
			header("location:/enterprise/ShowForms/showMediaInstituteForm/institute/".$listingId);
		}
	}

	function fetchPreviewPage($type,$typeId){
		$startTime = microtime(true);
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();

		$ListingClientObj = new Listing_client();
		$listing_type = 'institute';
		$listingData = $ListingClientObj->getListingForEdit('1',$typeId,$type);
		if($cmsUserInfo['usergroup']!= "cms"){
			if($listingData[0]['userId'] != $cmsUserInfo['userid']){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}

		switch($type){
			case 'institute':
				$instituteId =  $typeId;
				break;
			case 'course':
				$instituteId =  $listingData[0]['institute_id'];
				break;
		}

		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['details'] = $listingData[0];
		$cmsPageArr['type'] = $type;
		$cmsPageArr['typeId'] = $typeId;
		//        pa($cmsPageArr);
		//        pa(unserialize(base64_decode($cmsPageArr['details']['wikiFields'])));
		//        pa(unserialize(base64_decode($cmsPageArr['details']['mediaInfo'])));
		//        pa(unserialize(base64_decode($cmsPageArr['details']['courseList'])));
		$mediaArray = unserialize(base64_decode($cmsPageArr['details']['mediaInfo']));
		$photoArray = array();
		$videoArray= array();
		$docArray = array();
		for($i = 0 ; $i < count($mediaArray); $i++){
			switch($mediaArray[$i]['media_type']){
				case 'doc':
					array_push($docArray,$mediaArray[$i]);
					break;
				case 'photo':
					array_push($photoArray,$mediaArray[$i]);
					break;
				case 'video':
					array_push($videoArray,$mediaArray[$i]);
					break;
			}
		}

		$cmsPageArr['pageTitle'] = "Listing Preview Page - ".$listingData[0]['title'];
		$detailPageComponents = array();
		$i=0;
		if((count($photoArray) > 0) && ($cmsPageArr['details']['showMedia'] == 'yes')){
			$detailPageComponents[$i]['anchor'] = 'gallery';
			$detailPageComponents[$i]['title'] = 'Gallery';
			$detailPageComponents[$i]['value'] = $photoArray;
			$detailPageComponents[$i]['edit'] = '';
			$i++;
		}

		if((count($videoArray) > 0) && ($cmsPageArr['details']['showMedia'] == 'yes')){
			$detailPageComponents[$i]['anchor'] = 'videos';
			$detailPageComponents[$i]['title'] = 'Videos';
			$detailPageComponents[$i]['value'] = $videoArray;
			$detailPageComponents[$i]['edit'] = '';
			$i++;
		}

		$wikiSections = unserialize(base64_decode($cmsPageArr['details']['wikiFields']));
		if($cmsPageArr['details']['showWiki'] == 'yes'){
			for($j = 0; $j < count($wikiSections); $j++){
				$detailPageComponents[$i]['anchor'] = seo_url($wikiSections[$j]['caption']);
				$detailPageComponents[$i]['title'] = $wikiSections[$j]['caption'];
				$detailPageComponents[$i]['value'] = $wikiSections[$j]['attributeValue'];
				$detailPageComponents[$i]['edit'] = '';
				$i++;
			}
		}
		if((count($docArray) > 0) && ($cmsPageArr['details']['showMedia'] == 'yes')){
			$detailPageComponents[$i]['anchor'] = 'documents';
			$detailPageComponents[$i]['title'] = 'Brochure, Presentations & Other documents';
			$detailPageComponents[$i]['value'] = $docArray;
			$detailPageComponents[$i]['edit'] = '';
			$i++;
		}
		$cmsPageArr['detailPageComponents'] = $detailPageComponents;
		$cmsPageArr['courseList'] = unserialize(base64_decode($cmsPageArr['details']['courseList']));
		$cmsPageArr['courseEditUrlMain'] = "/enterprise/ShowForms/showCourseEditForm/".$typeId."#main";
		$cmsPageArr['courseEditUrlContact'] = "/enterprise/ShowForms/showCourseEditForm/".$typeId."#contact";
		$cmsPageArr['courseEditUrlwikki'] = "/enterprise/ShowForms/showCourseEditForm/".$typeId."#wikicontent";
		$cmsPageArr['instituteEditUrlMain'] = "/enterprise/ShowForms/editInstituteForm/".$instituteId."#main";
		$cmsPageArr['instituteEditUrlContact'] = "/enterprise/ShowForms/editInstituteForm/".$instituteId."#contact";
		$cmsPageArr['instituteEditUrlwiki'] = "/enterprise/ShowForms/editInstituteForm/".$instituteId."#wikicontent";
		$cmsPageArr['mediaUrldocs'] = "/enterprise/ShowForms/showMediaInstituteForm/institute/".$instituteId."#docs";
		$cmsPageArr['mediaUrlphotos'] = "/enterprise/ShowForms/showMediaInstituteForm/institute/".$instituteId."#photosmedia";
		$cmsPageArr['mediaUrlvideos'] = "/enterprise/ShowForms/showMediaInstituteForm/institute/".$instituteId."#videosmedia";
		//pa($cmsPageArr);
		$cmsPageArr['institute_id'] = $instituteId;
		$this->load->view('listing_forms/previewListing',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function showPreviewPage($instituteId,$type="",$typeId=""){
		if(empty($instituteId)) {
			show_404();
		}
		$startTime = microtime(true);
		$this->init();
		$this->load->helper('url');
		$cmsUserInfo = $this->cmsUserValidation();
                
        //redirecting for enterprise usergroup
        if($cmsUserInfo['usergroup'] == 'enterprise') {
            header('Location:/enterprise/Enterprise/disallowedAccess');
            exit;
        }

		$ListingClientObj = new Listing_client();
		$listing_type = 'institute';
		//$listingData = $ListingClientObj->getListingForEdit('1',$instituteId,$listing_type);
		
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/institutedetailsmodel');
		$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
		$listingData = $instituteDetailsModel->getInstituteDetails($instituteId);
		
		if($cmsUserInfo['usergroup']!= "cms"){
			if($listingData[0]['userId'] != $cmsUserInfo['userid']){
				header("location:/enterprise/Enterprise/disallowedAccess");
				exit();
			}
		}
		$clientId = $listingData[0]['userId'];
		$cmsUserInfo = $this->checkFlowCase($clientId,$cmsUserInfo);
		$cmsPageArr = array();
		$cmsPageArr = $cmsUserInfo;
		$cmsPageArr['validateuser'] = $cmsUserInfo['validity'];
		$cmsPageArr['pageTitle'] = "Publish the draft listings - ".$listingData[0]['title'];
		$cmsPageArr['institute_name'] = $listingData[0]['title'];
		$cmsPageArr['viewType'] = 4;
		$cmsPageArr['clientId'] = $clientId;
		$courseList = $ListingClientObj->getCourseList('1',$instituteId,'"draft","queued","live"');
		$cmsPageArr['listings'] =array();
		$cmsPageArr['otherListings'] =array();
		$i = 0;
		$j = 0;
		
		/**
		 * Institute to be shown in to be published state always
		 */ 
		//if($listingData[0]['status'] == 'draft' || ($listingData[0]['status'] == 'queued' && $cmsUserInfo['usergroup']=='cms')){
		if(1){	
			$cmsPageArr['listings'][0]['type'] ='institute';
			$cmsPageArr['listings'][0]['typeId'] =$instituteId;
			$cmsPageArr['listings'][0]['title'] =$listingData[0]['title'];
			$i++;
		}
		else{
			$cmsPageArr['otherListings'][0]['type'] ='institute';
			$cmsPageArr['otherListings'][0]['typeId'] =$instituteId;
			$cmsPageArr['otherListings'][0]['title'] =$listingData[0]['title'];
			$j++;
		}
		foreach($courseList as $course){
			if(stripos($course['status'],'draft') !==false || (stripos($course['status'],'queued') !==false && $cmsUserInfo['usergroup'] =='cms')){
				$cmsPageArr['listings'][$i]['type'] ='course';
				$cmsPageArr['listings'][$i]['typeId'] =$course['courseID'];
				$cmsPageArr['listings'][$i]['title'] =$course['courseName'];
				if(($type =="course") &&  ($typeId == $course['courseID'])){
					$cmsPageArr['defaultPreview']['num'] = $i+1;
					$cmsPageArr['defaultPreview']['type'] = $type;
					$cmsPageArr['defaultPreview']['typeId'] = $typeId;
				}
				$i++;
			}
			else{
				$cmsPageArr['otherListings'][$j]['type'] ='course';
				$cmsPageArr['otherListings'][$j]['typeId'] =$course['courseID'];
				$cmsPageArr['otherListings'][$j]['title'] =$course['courseName'];
				$j++;
			}
		}
		//$cmsPageArr['formPostUrl'] = '/enterprise/ShowForms/publishListings';
		$cmsPageArr['formPostUrl'] = '/listing/posting/ListingPublisher/publish';
		$this->load->view('listing_forms/new_homepage',$cmsPageArr);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}


	function publishListings(){
		$startTime = microtime(true);
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();

		$ListingClientObj = new Listing_client();
		$listings =  unserialize(base64_decode($this->input->post('listings')));
		if($cmsUserInfo['usergroup']!= "cms"){
			$listingData = $ListingClientObj->getMetaInfo('1',$listings,'"draft"');
		}else{
			$listingData = $ListingClientObj->getMetaInfo('1',$listings,'"draft","queued"');
		}
		if($cmsUserInfo['usergroup']!= "cms"){
			$numListings = count($listingData);
			for($i = 0 ;$i < $numListings ; $i++){
				if($listingData[$i]['userId'] != $cmsUserInfo['userid']){
					header("location:/enterprise/Enterprise/disallowedAccess");
					exit();
				}
			}
		}

		// Flag setting step for Actual Subscription Consumption
		$this->load->library('enterprise_client');
		$entObj = new Enterprise_client();
		$toConsumeArr = $entObj->toConsumeActualSubscriptionCheck(1,$listings);

		if($cmsUserInfo['usergroup']!= "cms"){
			$updateStatus = $ListingClientObj->makeListingsLive("1",$listings);
		}
		else{
			$audit = array();
			$audit['approvedBy'] = $cmsUserInfo['userid'];
			$audit['isApproved'] = "1";
			$audit['updateStatus'] = $updateStatus;
			$updateStatus = $ListingClientObj->makeListingsLive("1",$listings,$audit);
		}

		// Actual Subscription Consumption
		$this->load->library('enterprise_client');
		$audit = array();
		$audit['editedBy'] = $cmsUserInfo['userid'];
		$audit['toConsumeArr'] = $toConsumeArr;
		$audit['updateStatus'] = $updateStatus;
		$entObj = new Enterprise_client();
		$consumeResponse = $entObj->checkAndConsumeActualSubscription(1,$listings,$audit);

		if($updateStatus[0]['version'] > 0){
			ob_start();
			$institute_id = 0;
			$this->load->library('listing/ListingProfileLib');
			foreach($listings as $value)
			{
				if($value['type'] == "institute")
				{
					$institute_id = $value['typeId'];
					// $returnVal= $this->getDetailsForEbrouchre($value['typeId'],$value['type']);
                                    
				}
				else if($value['type'] == 'course'){
					$institute_id = $ListingClientObj->getInstituteIdForCourseId($appId,$value['typeId']);
					$this->listingprofilelib->updateCourseProfileCompletion($value['typeId']);
                                        
				}
			}
			
			if($institute_id >0) {
    				$this->listingprofilelib->updateProfileCompletion($institute_id);
			}
			if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
			header("location:/enterprise/Enterprise/index/7");
			ob_flush();
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	function getDetailPageFlags($subscriptionId=0){
		$startTime = microtime(true);
		if($subscriptionId == 0 || (strlen($subscriptionId) <=0)){
			$flags = array();
			$flags['showWiki'] = 'yes';
			$flags['showMedia'] = 'yes';
			return $flags;
		}
		$this->init();
		$this->load->library('Subscription_client');
		$subsObj = new Subscription_client();
		$features = $subsObj->getFeaturesForSubscription($subscriptionId);
		$flags = array();
		$flags['showWiki'] = strtolower($features['subsFeatures']['Property']['wiki_system']);
		$flags['showMedia'] = strtolower($features['subsFeatures']['Property']['media_display']);
		//error_log(print_r($flags,true));
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		return $flags;
	}

	function deleteDraftOrQueued(){
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$validateuser = $this->userStatus;
		$ListingClientObj = new Listing_client();
		$type_id = $this->input->post('type_id',true);
		$listing_type = $this->input->post('listing_type',true);
		$status = $this->input->post('status',true);
		$status = isset($status) && (strlen($status) > 0)?$status:"draft";

		$listings[0]['type'] =$listing_type;
		$listings[0]['typeId'] =$type_id;
		$listingDetails = $ListingClientObj->getMetaInfo($appID,$listings,'"draft","live","queued"');
		if( ($validateuser[0]['usergroup'] == "cms") ||
		(($listingDetails[0]['userId'] == $validateuser[0]['userid']) AND
		($validateuser[0]['usergroup'] == "enterprise")) )
		{
			$audit = array();
			$audit['approvedBy'] = $validateuser[0]['userid'];
			$deleteStatus = $ListingClientObj->deleteDraftOrQueued($appId,$type_id,$listing_type,$status,$audit);
			$this->load->model('ListingModel');
			$incrementResp = $this->ListingModel->incrementCountOfSubscription($deleteStatus);
		}else{
			echo "Invalid Call";
		}
	  if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}

	function getDetailsForEbrouchre($type_id, $listing_type) {
		$startTime = microtime(true);
		$appId = 1;
		$this->init();
		$displayData = array();
		$registerClient = new register_client();
		$ListingClientObj = new Listing_client();
		$alertClientObj = new Alerts_client();
		$thisUrl = $_SERVER['REQUEST_URI'];
		$fullUrl = "https://".$_SERVER['SERVER_NAME'].$_SERVER['REQUEST_URI'];
		$displayData['fullUrl'] = $fullUrl;
		$otherInstitutesCategory = '';

		$listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type,$otherInstitutesCategory,1);

		if(isset($listingDetails[0]['lastModifyDate']) && (strtotime($listingDetails[0]['lastModifyDate']) != "")){
			$listingDetails[0]['timestamp'] = $listingDetails[0]['lastModifyDate'];
		}

		$displayData['type_id'] = $type_id;
		$displayData['listing_type'] = $listing_type;
		$displayData['thisUrl'] = $thisUrl;

		$displayData['details'] = $listingDetails[0];
		$displayData['subscribeStatus'] = '';

		$displayData['ListingMode'] = 'view';
		$mediaArray = unserialize(base64_decode($displayData['details']['mediaInfo']));
		$photoArray = array();
		$videoArray= array();
		$docArray = array();
		for($i = 0 ; $i < count($mediaArray); $i++){
			switch($mediaArray[$i]['media_type']){
				case 'doc':
					array_push($docArray,$mediaArray[$i]);
					break;
				case 'photo':
					array_push($photoArray,$mediaArray[$i]);
					break;
				case 'video':
					array_push($videoArray,$mediaArray[$i]);
					break;
			}
		}

		$detailPageComponents = array();
		$i=0;

		$wikiSections = unserialize(base64_decode($displayData['details']['wikiFields']));
		if($displayData['details']['showWiki'] == 'yes'){
			for($j = 0; $j < count($wikiSections); $j++){
				$detailPageComponents[$i]['anchor'] = seo_url($wikiSections[$j]['caption']);
				$detailPageComponents[$i]['title'] = $wikiSections[$j]['caption'];
				$detailPageComponents[$i]['value'] = $wikiSections[$j]['attributeValue'];
				$detailPageComponents[$i]['edit'] = '';
				$i++;
			}
		}

		$displayData['detailPageComponents'] = $detailPageComponents;
		$displayData['courseList'] = unserialize(base64_decode($displayData['details']['courseList']));
		$displayData['docList'] = $docArray;
		$displayData['photoList'] = $photoArray;

		$brouchreContent = $this->load->view('listing/eBrouchre',$displayData,true);
		$alertClientObj = new Alerts_client();
		$attachmentResponse = $alertClientObj->createAttachment("12",$type_id,$listing_type,'E-Brochure',$brouchreContent,$displayData['details']['title'].".html",'html');
		foreach($displayData['courseList'] as $course)
		{
			$attachmentResponse = $alertClientObj->createAttachment("12",$course['course_id'],'course','E-Brochure',$brouchreContent,$displayData['details']['title'].".html",'html');
		}
		return(1);
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	public function serveFile($filePath, $serveType, $fileName = '')
	{
		$startTime = microtime(true);
		//before calling this function check user access and existance of file at $filePath.
		// $fileName: the file name user will get at his end.
		// $filePath: the actual path of the file on server.
		// $serveType: has two values 'attachment' and 'inline'.
		$filePath=base64_decode($filePath);
		//$filePath = 'http://172.16.3.227/mediadata/images/1242458755phpC4Fpq8_l.jpeg';
		$newArray=preg_split('/mediadata/',$filePath);
		if(count($newArray)<2)
		{
			header ( "HTTP/1.1 401 Unauthorized" );
			print ( "<html><body>HTTP 401 - Unauthorized </body> </html>" );
			exit ();

		}
		else
		{
			$filePath='mediadata'.$newArray[1];
			//$fileNamearray=preg_split('/'.preg_quote('/','/').'/',$newArray[1]);
			//$fileName=$fileNamearray[2];
			//$fileName="shasha";
			//echo print_r($fileNamearray,true);

			/*    $cmd = "file -i $filePath"; // to find mime-type, use php 'file' function.

			$filePathresult_i = exec ( $cmd );
			echo '<pre>';print_r($filePathresult_i);echo '</pre>';
			exit;
			list ( $tmpName, $mimeType ) = split ( ": ", $result_i );
			$cmd = "file $filePath";
			$result = exec ( $cmd ); // to check executables. Need to add check for tar and core files.
			list ( $tmpName, $checkType ) = split ( ": ", $result );
			if (Common::checkValue ( strpos ( $checkType, 'executable' ) )) {
			//If file type is a executable file. we would probably change this block.
			header ( "HTTP/1.1 401 Unauthorized" );
			print ( "<html><body>HTTP 401 - Unauthorized </body> </html>" );
			exit ();
			}*/
			header ( "Content-type: $mimeType" );
			//if (Common::checkValue ( $fileName ))
			//if (Common::checkValue ( $fileName ))
			header ( 'Content-Disposition: ' . $serveType . '; filename="' .$fileName. '";' );
			//else
			// header ( 'Content-Disposition: ' . $serveType . ';' );
			ob_clean ();
			flush ();
			return readfile ( "$filePath" );
			flush ();
			return true;
		}
	 if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}

	function downloadfile($filePath,$name='')
	{
		$startTime = microtime(true);
		// required for IE, otherwise Content-disposition is ignored
		if(ini_get('zlib.output_compression')) {
			ini_set('zlib.output_compression', 'Off');
		}
		//$filePath=base64_decode($filePath);
		$filePath = urldecode(html_entity_decode(base64_decode($filePath)));
		if (empty($name)) {
			$filename = basename($filePath);
		} else {
			$filename = $name;
		}
		$file_extension = strtolower(substr(strrchr($filename,"."),1));
		switch( $file_extension )
		{
			case "pdf": $ctype="application/pdf"; break;
			case "doc": $ctype="application/msword"; break;
			case "xls": $ctype="application/vnd.ms-excel"; break;
			case "ppt": $ctype="application/vnd.ms-powerpoint"; break;
			case "gif": $ctype="image/gif"; break;
			case "png": $ctype="image/png"; break;
			case "jpeg":$ctype="image/jpeg"; break;
			case "jpg": $ctype="image/jpg"; break;
			case "txt": $ctype="text/plain";break;
			default: $ctype="application/force-download";
		}
		header("Pragma: public");
		header("Expires: 0");
		header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
		header("Cache-Control: private",false);
		header("Content-Type: $ctype");
		header("Content-Disposition: attachment; filename=\"".$filename."\";" );
		header("Content-Transfer-Encoding: binary");
		//header("Content-Length: ".filesize($filePath));
		readfile("$filePath");
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
		exit();
	}

	/**
	 * Fetch Course Order data
	 */
	function getCourseOrdersForInstitute($instituteId){
		$startTime = microtime(true);
		$appId = 1;
		$this->init();
		$ListingClientObj = new Listing_client();
		$coursesOrder = $ListingClientObj->getCourseOrder($appId,$instituteId);
		$j = 1;
		$totalCourses = sizeof($coursesOrder);

		//--remap course ordering to 1,2,3,4...
		$courseOrderRemapped = $this->_remapCourseOrder($coursesOrder);
		 
		echo "<table style='width:100%'>";
		echo "<tr><th>Order No.</th><th>Course Name</th></tr>";
		foreach($coursesOrder AS $course){
			echo "<tr><td><select name='course_order[]'>";
			for($i = 1;$i<=$totalCourses;$i++){
				if($i == $courseOrderRemapped[$course['courseId']]){
					echo "<option value='".$i."' selected='selected'>$i</option>";
				}else{
					echo "<option value='".$i."'>$i</option>";
				}
			}
			echo "    </select></td>";
			echo "    <td>".$course['courseTitle'];
			echo "<input type='hidden' id='overlay_institute_id' name='institute_id' value='".$instituteId."'/></td>";
			echo "<input type='hidden' name='course_ids[]' value='".$course['courseId']."'/></td>";
			echo "</tr>";
			$j++;
		}
		echo "</table>";
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}

	/*
	 * Remap course order numerically
	 * By Vikas Kumar, Apr 4, 2011 against Bug#: 43318
	 * Remap course order
	 * eg input: array(array(1465,'C1',4),array(1678,'C2',3),array(1986,'C5',2))
	 * arguments above: courseId, courseTitle, courseOrder
	 * output: array(1986=>1,1678=>2,1485=>3)
	 * arguments above: courseId, remapped courseOrder
	 */

	private function _remapCourseOrder($courses)
	{
		usort($courses,array('ShowForms','usortCourses'));
		$j = 1;
		$sorted_courses = array();
		foreach($courses as $course)
		{
			$sorted_courses[$course['courseId']] = $j++;
		}
		return $sorted_courses;
	}

	public static function usortCourses($c1,$c2)
	{
		return $c1['courseOrder']-$c2['courseOrder'];
	}

	/**
	 * Save course order
	 * @param $instituteId integer
	 */
	function saveCourseOrdersForInstitute($instituteId){
		if(empty($instituteId)) {
			echo "0";
			return;
		}
		$startTime = microtime(true);
		$this->init();
		$appId = 1;
		$ListingClientObj = new Listing_client();
		$courseIds = explode(',',$this->input->post('courseIds',true));
		$courseOrders = explode(',',$this->input->post('courseOrders',true));
		$ListingClientObj->saveCourseOrder($appId,$instituteId,$courseIds,$courseOrders);
		//Code start by Ankur for HTML caching. After making the Listing live, delete the institute HTML cache, so that new HTML pages will be created
		/*$this->load->library('cacheLib');
		$cacheLib = new cacheLib();
		$key = md5('listingCache_'.$instituteId."_institute");
		$cacheLib->store($key,'false',86400,'misc');
		$key = md5('listingCache_'.$instituteId."_course");
		$cacheLib->store($key,'false',86400,'misc');*/
		$key = "listingCache_".$instituteId."_institute";
		$ListingClientObj->setListingCacheValue($appId, $key,'false');
		$key = "listingCache_".$instituteId."_course";
		$ListingClientObj->setListingCacheValue($appId, $key,'false');
		//After setting the value in DB, also delete the HTML files from all the Frontend servers
		$ListingClientObj->deleteListingCacheHTMLFile($instituteId,"institute");
		$ListingClientObj->deleteListingCacheHTMLFile($instituteId,"course");
		//Code End by Ankur for HTML caching
		echo "Ho Gaya";
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
        /**
	 * _instituteRequestBrochureUpload
	 * Function For uploading institute e brochure
	 * @access  private
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  array
	 * @ToDo
	 */
	private function _instituteRequestBrochureUpload() {
		$startTime = microtime(true);
		// check if institute brochure has been uploaded
		if(array_key_exists('i_brochure_panel', $_FILES) && !empty($_FILES['i_brochure_panel']['tmp_name'][0])) {
			$return_response_array = array();
			// load client library
			$this->load->library('upload_client');
			$uploadClient = new Upload_client();
			// get file data and type check
			$type_doc = $_FILES['i_brochure_panel']['type']['0'];
			$type_doc = explode("/", $type_doc);
			$type_doc = $type_doc['0'];
			$type = explode(".",$_FILES['i_brochure_panel']['name'][0]);
			$type = strtolower($type[count($type)-1]);
			// display error if type doesn't match with the required file types
			if(!in_array($type, array('pdf','jpeg','doc','jpg'))) {
				$return_response_array['Fail']['i_brochure_panel'] = "Only document of type .pdf,.doc and .jpeg allowed";
				return $return_response_array;
			}
			// all well, upload now
			if($type_doc == 'image') {
				$upload_array = $uploadClient->uploadFile($appId,'image',$_FILES,array(),"-1","institute",'i_brochure_panel');
			} else {
				$upload_array = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","institute",'i_brochure_panel');
			}
			// check the response from upload library
			if(is_array($upload_array) && $upload_array['status'] == 1) {
				$return_response_array = $upload_array[0]['imageurl'];
			} else {
                                if($upload_array == 'Size limit of 25 Mb exceeded') {
					$upload_array = "Please upload a brochure less than 25 MB in size";	
                                }
				$return_response_array['Fail']['i_brochure_panel'] = $upload_array;
			}
			return $return_response_array;
		} else {
			return "";
		}
	 if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}	
	}
        /**
	 * _courseRequestBrochureUpload
	 * Function For uploading institute e brochure
	 * @access  private
	 * @param   array $_POST
	 * @param   array $_FILES
	 * @return  array
	 * @ToDo
	 */
	private function _courseRequestBrochureUpload() {
		// check if institute brochure has been uploaded
		if(array_key_exists('c_brochure_panel', $_FILES) && !empty($_FILES['c_brochure_panel']['tmp_name'][0])) {
			$return_response_array = array();
			// load client library
			$this->load->library('upload_client');
			$uploadClient = new Upload_client();
			// get file data and type check
			$type_doc = $_FILES['c_brochure_panel']['type']['0'];
			$type_doc = explode("/", $type_doc);
			$type_doc = $type_doc['0'];
			$type = explode(".",$_FILES['c_brochure_panel']['name'][0]);
			$type = strtolower($type[count($type)-1]);
			// display error if type doesn't match with the required file types
			if(!in_array($type, array('pdf','jpeg','doc','jpg'))) {
				$return_response_array['Fail']['c_brochure_panel'] = "Only document of type .pdf,.doc and .jpeg allowed";
				return $return_response_array;
			}
			// all well, upload now
			if($type_doc == 'image') {
				$upload_array = $uploadClient->uploadFile($appId,'image',$_FILES,array(),"-1","course",'c_brochure_panel');
			} else {
				$upload_array = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","course",'c_brochure_panel');
			}
			// check the response from upload library
			if(is_array($upload_array) && $upload_array['status'] == 1) {
				$return_response_array = $upload_array[0]['imageurl'];
			} else {
                                if($upload_array == 'Size limit of 25 Mb exceeded') {
					$upload_array = "Please upload a brochure less than 25 MB in size";	
                                }
				$return_response_array['Fail']['c_brochure_panel'] = $upload_array;
			}
			return $return_response_array;
		} else {
			return "";
		}
	}
	/*** To retrieve the LDB courses that must not appear ***/
	private function getRestrictedLDBCourses()
	{
		
		$allow_global_registration = 'no';
		$status = 'live';

		$this->load->model('LDB/ldbcoursemodel');
		$restrictedLDBCourseRows = $this->ldbcoursemodel->getGloballyRestrictedLDBCourses($allow_global_registration ,$status );

		$dataArr =array();
		foreach($restrictedLDBCourseRows as $row)
		{
			if(count($dataArr[$row['sub_cat_id']]['blockedLDBCourse'])==0)
				$dataArr[$row['sub_cat_id']]['blockedLDBCourse'] =  array();
			array_push($dataArr[$row['sub_cat_id']]['blockedLDBCourse'],$row['ldb_course_id']);
		}
		 
		return $dataArr;
		/*** END: To retrieve the LDB courses that must not appear ***/

		
	}
	
	/*
	 * This function add Category name with LDB course.
	 * Due to addtion of wrong LDB Coures in LDBCourseToSubCatMapping it was hard to identify that which LDB course is wrong and which is not.  
	 *  For More detail :LF-2655
	 */
	
	private function _addCategoryNameWithLDBCourses(& $courseList) {
		$this->load->builder('LDBCourseBuilder','LDB');
		$LDBCourseBuilder = new LDBCourseBuilder;
		$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		
		$this->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;
		$categoryRepository = $categoryBuilder->getCategoryRepository();
				
		$subCatHavingWrongLDBCourses = array(107,70,19,139);
		foreach($subCatHavingWrongLDBCourses as $subCatID) {
			foreach($courseList[$subCatID] as $key=>$LDBCourseData) {
				$courseList[$subCatID][$key]['CourseName'] = $courseList[$subCatID][$key]['CourseName']." -(".$categoryRepository->find($LDBCourseRepository->find($LDBCourseData['SpecializationId'])->getCategoryId())->getName().")";
			}
		}
	}
}
