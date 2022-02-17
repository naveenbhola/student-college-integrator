<?php

require APPPATH.'/modules/Listing/listing/controllers/posting/AbstractListingPost.php';

class InstitutePost extends AbstractListingPost
{
    private $institutePostModel;
	
    function __construct()
    {
		parent::__construct();
		
		$this->load->library('Subscription_client');
        $subsciptionConsumer = new Subscription_client();
		
		$this->load->model('listing/posting/institutepostmodel');
        $this->institutePostModel = new InstitutePostModel($subsciptionConsumer);
    }

    function post()
    {
    	$startTime = microtime(true);
		$cmsUserInfo = $this->cmsUserValidation();
		
		if($_POST['flow'] == 'edit') {
			$postedInstituteId = $_POST['instituteId'];
			
			$this->load->library('cacheLib');
			$cacheLib = new CacheLib;
			$this->load->model('listing/posting/institutedetailsmodel');
			$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
			$listingData = $instituteDetailsModel->getInstituteDetails($postedInstituteId);
			
			if(!is_array($listingData) && $listingData == 'NO_SUCH_LISTING_FOUND_IN_DB') {
				show_404();
			}
			
			if($cmsUserInfo['usergroup']!= "cms"){
				if($listingData[0]['userId'] != $cmsUserInfo['userid']){
					header("location:/enterprise/Enterprise/disallowedAccess");
					exit();
				}
			}
		}
		
        
		$data = array();
		$data = $this->_prepareInstituteData();
        
        $data['editedBy'] = $cmsUserInfo['userid'];
        $data['group_to_be_checked'] = $cmsUserInfo['usergroup'];
        
        $logoAndPanelResp = $this->_uploadLogoAndPanel();
	
	$data['deleteBrochureFlag'] = $this->input->post('request_brochure_link_delete');
        $requestBrochureResp = $this->_uploadInstituteBrochure();
		
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
        
        /**
		 * Lets check if the subscription selected has not been consumed while posting this listing..
		 * This is to ensure that expiry_date should get filled properly (at Listing Publishing) in listtings_main table.
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
			if(($logoAndPanelRespWidth  != 252) &&  ($logoAndPanelRespHeight != 103)) {
				$logoPanelRespArray["Fail"]['photo'] = "Please upload a photo with height equal to 103 pixels and width equal to 252 pixels";
				$exitFlag = true;
			}
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
        
        $data['crawled'] = "noncrawled";
		if ($data['dataFromCMS']=="1") {	
			$data['moderated'] = "moderated";
			$data['status'] = 'draft';
		} else {
			$data['moderated'] = "unmoderated";
			$data['status'] = 'draft';
		}

		if (isset($data['packType'])) {
			$data['packType'] = $data['packType'];
		} else {
			$data['packType'] = '-10';
		}
		
		$data['version'] = 1;

		$data['showWiki'] =  isset($data['showWiki'])?$data['showWiki']:'yes';
		$data['showMedia'] =  isset($data['showMedia'])?$data['showMedia']:'yes';

		if(!isset($data['sourceUrl']) || strlen($data['sourceUrl']) <= 5) {
			$data['sourceUrl'] = $data['url'];
		}
        
        $data['logo_link'] = $data['logoArr']['thumburl'];
        $data['featured_panel_link'] = $data['panelArr']['thumburl'];
        
		switch($data['insituteType']) {
            case 1:
                $data['instituteType'] = 'Academic_Institute';
                break;
            case 2:
                $data['instituteType'] = 'Test_Preparatory_Institute';
                break;
        }
        
		/**
		 * Add topic
		 */ 
		$this->load->library('message_board_client');
		$messageBoardClient = new Message_board_client();
		
		$topicDescription = "You can discuss about ".$data['institute_name']." here.";;
		$requestIp = S_REMOTE_ADDR;
		$topicResult = $messageBoardClient->addTopic(1,1,$topicDescription,1,S_REMOTE_ADDR,'group');
		$data['threadId']= $topicResult['ThreadID'];
		
        if($_POST['flow'] == 'edit') {
            $data['instituteId'] = $_POST['instituteId'];
        }
        
		/**
		 * Check for duplicate institute posting
		 */
		if($_POST['flow'] != 'edit' && $this->institutePostModel->isDuplicateInstitutePosting($data)) {
			echo json_encode(array('Fail' => array('common' => 'Duplicate Listing !!!')));
			return;
		}
		
		try {
			$instituteId = $this->institutePostModel->addInstitute($data);
			if(isset($_POST['nextAction']) && ($this->input->post('nextAction') == 1))
			{
			    echo json_encode(array("Success" => "/enterprise/ShowForms/showPreviewPage/".$_POST['instituteId']));
			}
			else
			{
			    echo json_encode(array("Success" => "/enterprise/ShowForms/showCourseForm/".$instituteId."/1"));
			}
		}
		catch(Exception $e) {
			echo json_encode(array('Fail' => array('common' => $e->getMessage())));
		}
		if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }
    
    private function _prepareInstituteData()
    {	
		$appId = 1;
		$cmsUserInfo = $this->cmsUserValidation();
		$flagArray = $this->setFlags($cmsUserInfo);
		
		$this->load->library(array('listing_client'));
		$ListingClientObj = new Listing_client();

		$data['dataFromCMS'] = $flagArray['moderation'];
		$data['packType'] = $flagArray['packType'];
		$data['username'] = $flagArray['userid'];
		$data['subscriptionId'] = $this->input->post('selectedSubs');
		$data['clientId'] = $this->input->post('clientId');
		$data['onBehalfOf'] = $this->input->post('onBehalfOf');
		$data['institute_request_brochure_link_year'] = $this->input->post('c_brochure_panel_year',true);
		$data['request_brochure_link_delete'] = $this->input->post('request_brochure_link_delete',true);
		
		$data['institute_name'] = $this->input->post('c_institute_name');
		
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
	
		$data['insituteType'] = $this->input->post('insituteType');

		$data['admission_counseling'] = $this->input->post('admission_counseling');
		$data['visa_assistance'] = $this->input->post('visa_assistance');

		$data['listing_seo_url'] = $this->input->post('listing_seo_url');
		$data['listing_seo_title'] = $this->input->post('listing_seo_title');
		$data['listing_seo_description'] = $this->input->post('listing_seo_description');
		$data['listing_seo_keywords'] = $this->input->post('listing_seo_keywords');

		//get institute facilities
		$postkeys = array_keys($_POST);
		$facilities = array();
		foreach($postkeys as $key){
			if(strpos($key, 'c_facility_value_') !== false){
				$temp = explode('c_facility_value_', $key);
				$facilities[$temp[1]] = $this->input->post($key);
			}
		}
		$data['institute_facilities'] = $facilities;
		
		$data['mandatory_comments'] = $this->input->post('mandatory_comments');
		$data['cmsTrackUserId'] = $this->input->post('cmsTrackUserId');
		$data['cmsTrackListingId'] = $this->input->post('cmsTrackListingId');
		$data['cmsTrackTabUpdated'] = $this->input->post('cmsTrackTabUpdated');

		/**
		 * For Multilocation Support
		 */ 
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
			$data['flow'] = $this->input->post('flow');
			
			if(isset($locationInfoSubArray[$i][9]) && $locationInfoSubArray[$i][9] != "") {
				$data['locationInfo'][$i]['institute_location_id'] = $locationInfoSubArray[$i][9];
			}
		}

		/**
		 * Why Join Institute
		 */
		$data['photo_title'] = $this->input->post('photo_title');
		$data['details'] = $this->input->post('details');
		$data['wiki'] = array();
		
		/**
		 * Wiki Section additions
		 */
		$wikiData = $ListingClientObj->getWikiFields('1','institute');
		foreach($wikiData as $wikiField){
			$wikiField_key_name = trim($this->input->post($wikiField['key_name']));
			if($data['flow'] == 'edit'){
				if(empty($wikiField_key_name)){
					$wikiField_key_name = ' ';
				}	
			}
			if(strlen($wikiField_key_name) > 0){
				try {
					$data['wiki'][$wikiField['key_name']] = is_text_in_html_string($wikiField_key_name) ? trimmed_tidy_repair_string($wikiField_key_name) : '';
				}
				catch (Exception $ex){
				}
			}
		}
		
		$userCreatedWikisCaptions = $this->input->post('wikkicontent_main');
		$userCreatedWikisDetails = $this->input->post('wikkicontent_detail');

		$data['wiki']['user_fields'] = array();
		for($i = 0; $i < count($userCreatedWikisCaptions); $i++){
			if (($userCreatedWikisCaptions[$i] != 'Enter Title' ) && ($userCreatedWikisDetails[$i] != 'Enter Description')) {
				$data['wiki']['user_fields'][$i]['caption'] = trimmed_tidy_repair_string($userCreatedWikisCaptions[$i]);
				$data['wiki']['user_fields'][$i]['caption'] = str_replace("\n", " ", $data['wiki']['user_fields'][$i]['caption']);
				$data['wiki']['user_fields'][$i]['value'] = is_text_in_html_string($userCreatedWikisDetails[$i]) ?trimmed_tidy_repair_string($userCreatedWikisDetails[$i]):'';
			}
		}
		$data['instituteSubmitDate'] = $this->input->post('instituteSubmitDate');
		$data['instituteViewCount'] = $this->input->post('instituteViewCount');
		$data['no_Of_Past_Paid_Views'] = $this->input->post('no_Of_Past_Paid_Views');
		$data['no_Of_Past_Free_Views'] = $this->input->post('no_Of_Past_Free_Views');
		
		return $data;
	}
    
    function _uploadLogoAndPanel()
    {
		$appId = 1;
		$logoArr = array();
		$panelArr = array();
		$photoArr = array();
		
		$this->load->library('upload_client');
		$uploadClient = new Upload_client();
		
		$arrCaption = $this->input->post('c_insti_logo_caption');
		$inst_logo= array();
		
		for($i=0;$i<count($_FILES['i_insti_logo']['name']);$i++){
			$inst_logo[$i] = ($arrCaption[$i]!="") ? $arrCaption[$i] : $_FILES['i_insti_logo']['name'][$i];
		}
		
		if(!(isset($_FILES['i_insti_logo']['tmp_name'][0]) && ($_FILES['i_insti_logo']['tmp_name'][0] != '')) && ($this->input->post('logoRemoved')==1)) {
			$logoArr['thumburl'] = "";
		}
		else if(isset($_FILES['i_insti_logo']['tmp_name'][0]) && ($_FILES['i_insti_logo']['tmp_name'][0] != '')) {
			$i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,"-1","institute",'i_insti_logo');
			if($i_upload_logo['status'] == 1) {
				for($k = 0;$k < $i_upload_logo['max'] ; $k++) {
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
			}
			else {
				$logoArr['error'] = $i_upload_logo;
				$logoArr['thumburl'] = "";
			}
		}

		$arrCaption = $this->input->post('c_feat_panel_caption');
		$inst_logo= array();
		for($i=0;$i<count($_FILES['i_feat_panel']['name']);$i++){
			$inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_feat_panel']['name'][$i];
		}
		
		if(!(isset($_FILES['i_feat_panel']['tmp_name'][0]) && ($_FILES['i_feat_panel']['tmp_name'][0] != '')) && ($this->input->post('panelRemoved')==1)) {
			$panelArr['thumburl'] = "";
		}
		else if(isset($_FILES['i_feat_panel']['tmp_name'][0]) && ($_FILES['i_feat_panel']['tmp_name'][0] != '')) {
			$i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,"-1","featured",'i_feat_panel');
			if($i_upload_logo['status'] == 1) {
				for($k = 0;$k < $i_upload_logo['max'] ; $k++) {
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
			}
			else{
				$panelArr['error'] = $i_upload_logo;
				$panelArr['thumburl'] = "";
			}
		}
		
		$arrCaption = $this->input->post('photo_title');
		$inst_logo= array();

		for($i=0;$i<count($_FILES['photo']['name']);$i++){
			$inst_logo[$i] = ($arrCaption!="")?$arrCaption:$_FILES['photo']['name'][$i];
		}
		if(!(isset($_FILES['photo']['tmp_name'][0])) && ($_FILES['photo']['tmp_name'][0] != '')){
			$photoArr['thumburl'] = "";
		}
		else if(isset($_FILES['photo']['tmp_name'][0]) && ($_FILES['photo']['tmp_name'][0] != '')) {
			$i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,"-1","photo",'photo');
			if($i_upload_logo['status'] == 1) {
				for($k = 0;$k < $i_upload_logo['max'] ; $k++) {
					$tmpSize = getimagesize($i_upload_logo[$k]['imageurl']);
					list($width, $height, $type, $attr) = $tmpSize;
					$photoArr['width']=$width;
					$photoArr['height']=$height;
					$photoArr['type']=$type;
					$photoArr['mediaid']=$i_upload_logo[$k]['mediaid'];
					$photoArr['url']=$i_upload_logo[$k]['imageurl'];
					$photoArr['title']=$i_upload_logo[$k]['title'];
					$photoArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];
				}
			}
			else {
				$photoArr['error'] = $i_upload_logo;
				$photoArr['thumburl'] = "";
			}
		}
		$response['logoArr'] = $logoArr;
		$response['panelArr'] = $panelArr;
		$response['photoArr'] = $photoArr;
		return $response;
	}
    
    private function _uploadInstituteBrochure()
    {
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
			}
			else {
				$upload_array = $uploadClient->uploadFile($appId,'pdf',$_FILES,array(),"-1","institute",'i_brochure_panel');
			}
			// check the response from upload library
			if(is_array($upload_array) && $upload_array['status'] == 1) {
				$return_response_array = $upload_array[0]['imageurl'];
			}
			else {
				if($upload_array == 'Size limit of 25 Mb exceeded') {
					$upload_array = "Please upload a brochure less than 25 MB in size";	
				}
				$return_response_array['Fail']['i_brochure_panel'] = $upload_array;
			}
			return $return_response_array;
		}
		else {
			return "";
		}
	}
}
