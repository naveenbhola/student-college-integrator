<?php
/*
 
Copyright 2007 Info Edge India Ltd

$Rev::            $:  Revision of last commit
$Author: build $:  Author of last commit
$Date: 2010-08-27 11:19:44 $:  Date of last commit
$Id: Enterprise.php,v 1.201 2010-08-27 11:19:44 build Exp $:

*/
class Enterprise extends MX_Controller {
    function init() {
        $this->load->helper(array('form', 'url','date','image','shikshaUtility'));
        $this->load->library(array('miscelleneous','message_board_client','blog_client','event_cal_client','ajax','category_list_client','listing_client','register_client','enterprise_client','sums_manage_client','cacheLib'));
        $this->userStatus = $this->checkUserValidation();
    }
    
    function getQuestionTitleAndDiscussion(){

    $this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$moduleName = trim($this->input->post('moduleName'));
	$filter = trim($this->input->post('Filter'));
	$start=$this->input->post('startFrom');
	$rows=$this->input->post('countOffset');

	$userNameFieldData=$this->input->post('userNameFieldData');
	$userLevelFieldData=$this->input->post('userLevelFieldData');
	$reported=$this->input->post('reported');

    $this->load->library('enterprise_client');
	$parameterObj = array('question' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
	$entObj = new Enterprise_client();
	$resultQuestionInfo['info'] = json_decode($entObj->getQuestionlogInfo($appId,$loggedInUserId,$start,$rows,$moduleName,$filter,$userNameFieldData,$userLevelFieldData));
    $resultQuestionInfo['loggedInUserId'] = $loggedInUserId;
    $totalQuestionNumber = isset($resultQuestionInfo[info][0]->totalQuestionNumber[0])?$resultQuestionInfo[info][0]->totalQuestionNumber[0]:0;
	$parameterObj['question']['offset'] = 0;
	$parameterObj['question']['totalCount'] = $totalQuestionNumber;
    $resultQuestionInfo['parameterObj'] = json_encode($parameterObj);
    $resultQuestionInfo['totalQuestion'] = $totalQuestionNumber;
	$resultQuestionInfo['filterSel'] = $filter;
	$resultQuestionInfo['moduleName'] = $moduleName;
	$resultQuestionInfo['startFrom'] = $start;
	$resultQuestionInfo['countOffset'] = $rows;
	$resultQuestionInfo['userNameFieldData'] = $userNameFieldData;
	$resultQuestionInfo['userLevelFieldData'] = $userLevelFieldData;
	$resultQuestionInfo['reported'] = $reported;
    // $this->load->model('messageBoard/AnAModel');
    // $expertLevelsForFilter = $this->AnAModel->getExpertLevels('AnA');
    $this->load->helper('messageBoard/abuse');
    $expertLevelsForFilter = getExpertLevels();
    $resultQuestionInfo['expertLevelsForFilter'] = $expertLevelsForFilter;
    $arr = array();
    echo  $totalQuestionNumber."::".$this->load->view('enterprise/cms_editQuestionTitlePage',$resultQuestionInfo);
    }

    function deleteQuestionInfoInLog(){
    $this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    $info[msgTitle] = $_POST['msgTitle'];
    $info[msgId]   = $_POST['msgId'];
    $info[userId] = $_POST['userId'];
    //$info[MsgDesc] = $_POST['MsgDesc'];

   // $info[questionUserId] = $_POST['questionUserId'];
    $info[displayName] = $_POST['displayName'];

    $this->load->library('MailerClient');
	$penalty=trim($this->input->post('penalty'));
    $this->load->library('enterprise_client');
	//Set the entity as deleted in their respective tables
	$entObj = new Enterprise_client();
	//Write code to deduct points of users who have reported abuse in case of with Penalty

	//Set the entity as removed in the Abuse log table
	$status = 'deleted';
	$resultdeleteQuestionInfoInLog = json_decode($entObj->deleteQuestionInfoInLog($appId,$info[msgId],$info[userId],$info[msgTitle],$info[displayName],$status));
    //error_log("resultdeleteQuestionInfoInLog ".print_r($resultdeleteQuestionInfoInLog,true));
    $email = $resultdeleteQuestionInfoInLog[0][0][0]->resNew[0]->email;
    $displayName = $resultdeleteQuestionInfoInLog[0][0][0]->resNew[0]->displayname;
    $RP = $resultdeleteQuestionInfoInLog[0][0][0]->resRP[0]->reputationPoints;
    //error_log('toEmail is here '.print_r($toEmail,true));
    //error_log('displayName is here '.print_r($displayName,true));
    //error_log('RP is here '.print_r($RP,true));
    
    $contentArr = array();
    //$email = $resultdeleteQuestionInfoInLog[0]->email;
    //Remove Title moderation mailer
    /*
    $fromMail = "noreply@shiksha.com";
    $subject = "Your title submission is not accepted by Shiksha.com";
    $fromMail = "noreply@shiksha.com";
    $contentArr['displayName'] = $displayName;
    $contentArr['msgTitle'] = $info[msgTitle];

    $urlOfLandingPage = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome';
    $MailerClient = new MailerClient();
    $contentArr['email']=$email;
    if($RP >50 && $RP!=9999999)
    {
        $contentArr['Url'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage.'/1/4/1/untitledQuestion');
    }
    $contentArr['type'] = 'deleteTitle';
    $content = $this->load->view("search/searchMail",$contentArr,true);
	$this->load->library('alerts_client');
    $mail_client = new Alerts_client();
    $response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00','n',array());
    */
    echo "1";
  }

  function editUserQuestionTitle(){ 
    // error_log("post values ".print_r($_POST,true));
     $info[msgTitleAdmin] = $_POST['msgTitle'];
     $info[msgId]   = $_POST['msgId'];
     $info[userId] = $_POST['userId'];
     $info[questionUserId] = $_POST['questionUserId'];
     //$info[fromFront] = $_POST['fromFront'];
     $info[status] = $_POST['status'];
     $this->init(array('message_board_client'));
     $appId=12;
     $msgbrdClient = new Message_board_client();
     $topicResult = json_decode($msgbrdClient->checkInQuestionLog($appId,$info[msgId]));
     $info[msgDesc] = $topicResult[0]->description;
     echo $this->load->view('messageBoard/editTitlePage',$info);
}

function updateTitle()
{

    $this->load->library('enterprise_client');
    $this->load->library('MailerClient');
	//Set the entity as deleted in their respective tables
    $entObj = new Enterprise_client();
    $appId=12;
    $msgId = $this->input->post('msgId');
    $userId = $this->input->post('userId');
    $questionUserId = $this->input->post('questionUserId');
    $msgTitle = addslashes($this->input->post('msgTitle'));
    //$msgDescription = addslashes(base64_decode($this->input->post('msgDescription')));
    $msgDescription = addslashes($this->input->post('msgDescription'));
    $status = $this->input->post('status');


    $topicResult = json_decode($entObj->updateTitle($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription));
    //error_log('topic result is here '.print_r($topicResult,true));

    $toEmail = $topicResult[0][0][0]->resNew[0]->email;
    $displayName = $topicResult[0][0][0]->resNew[0]->displayname;
    $RP = $topicResult[0][0][0]->resRP[0]->reputationPoints;
    //error_log('toEmail is here '.print_r($toEmail,true));
    //error_log('displayName is here '.print_r($displayName,true));
    //error_log('RP is here '.print_r($RP,true));
    $urlOfLandingPage = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/discussionHome';
    $MailerClient = new MailerClient();
    // $email = $topicResult[0]->email;
    //Remove the title moderation mailer
    /*
    $contentArr['email']=$toEmail;
    if($RP >25 && $RP!=9999999){
    $contentArr['Url'] = $MailerClient->generateAutoLoginLink(1,$toEmail,$urlOfLandingPage.'/1/4/1/untitledQuestion');
    }
    $fromMail = "noreply@shiksha.com";
    $subject = "Your title submission has been accepted by Shiksha.com";

    $contentArr['displayName'] = $displayName;
    $contentArr['msgTitle'] = $msgTitle;
    $this->load->library('alerts_client');
    $mail_client = new Alerts_client();
    if($status=='live')
    $contentArr['type'] = 'liveTitle';
    else
    $contentArr['type'] = 'editTitle';
    $content = $this->load->view("search/searchMail",$contentArr,true);error_log("status ".print_r($status,true));

    $response= $mail_client->externalQueueAdd("12",$fromMail,$toEmail,$subject,$content,$contentType="html",'0000-00-00 00:00:00','n',array());
    */

    echo '2';
}
/*
function updateTitleFromAdmin(){

    $this->load->library('enterprise_client');
    $this->load->library('MailerClient');
    $appId=12;
    $msgId = $this->input->post('msgId');
    $userId = $this->input->post('userId');
    $questionUserId = $this->input->post('questionUserId');
    $msgTitle = $this->input->post('msgTitle');
    $msgDescription = $this->input->post('MessageDesc');
    $status = $this->input->post('status');
    error_log("status is ".print_r($status,true));



    $entObj = new Enterprise_client();
    $topicResult = json_decode($msgbrdClient->updateTitle($appId,$userId,$msgId,$questionUserId,$msgTitle,$msgDescription,$status));

    $email = $topicResult[0]->email;
    $urlOfLandingPage = SHIKSHA_ASK_HOME;
    $MailerClient = new MailerClient();
    $contentArr['Url'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage.'/1/5/1/untitledQuestion');

    $fromMail = "noreply@shiksha.com";
    $subject = "Your title submission is not accepted by Shiksha.com";
    $fromMail = "noreply@shiksha.com";
    $contentArr['displayName'] = $topicResult[0]->displayname;
    $contentArr['msgTitle'] = $msgTitle;
    $contentArr['email']=$email;
    $this->load->library('alerts_client');
    $mail_client = new Alerts_client();
    $contentArr['type'] = 'liveTitle';
    $content = $this->load->view("search/searchMail",$contentArr,true);
    if($userId!=$newUserId)
    $response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00','n',array());
    //header('location:'.$_SERVER['HTTP_REFERER']);
    echo '1';
}*/

function questionThanks(){
      echo $this->load->view('messageBoard/thanksPage');
}

    function cmstopinstitutes() {
    $this->init();
    $validity = $this->checkUserValidation();
    $cmsPageArr['validateuser'] = $validity;
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms')
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }

    $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
    $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
    $this->load->view('enterprise/cmsTop20',$cmsPageArr);
    }

    function setCountryPage() {
    $this->init();
    $validity = $this->checkUserValidation();
    $cmsPageArr['validateuser'] = $validity;

    $categoryClient = new Category_list_client();
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms')
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }

    $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
    $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
    $this->load->view('enterprise/cms_setCountryPage',$cmsPageArr);
    }

/*    function uploadImg()
    {
        $this->init();
        $bannerDetails = $_POST;
        $this->load->library('upload_client');
        error_log(print_r($_FILES,true));
        if($_FILES['bannerFile']['tmp_name'][0] == '')
        {
            echo 'Please choose a banner to upload';
            exit;
        }
        $type = $_FILES['bannerFile']['type'];
        error_log($type.'TyPE');
        if(is_array($type))
        {
            $type = $_FILES['bannerFile']['type'][0];
        }
        error_log($type.'TyPE');
        if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type == 'application/x-shockwave-flash'))
        {
            echo 'jpeg,png,png,swf are allowed banner types';
            exit;
        }
        if($type ==  'application/x-shockwave-flash')
        {
            $uptype = 'video';
        }
        else
        {
            $uptype = 'image';
            $tmpSize = getimagesize($_FILES['bannerFile']['tmp_name'][0]);
            error_log(print_r($tmpSize,true).'SIZE');
            list($width, $height, $type, $attr) = $tmpSize;
            error_log($width.'WIDTH'.$height.'HEIGHT');
            if($width > 220 || $height > 100)
            {
                echo 'Allowed image size is 220 * 100';
                exit;
            }
        }
        $uploadClient = new Upload_client();
        $listing_client = new Listing_client();
        $bannerDetails = json_encode($bannerDetails);
        $id = $listing_client->uploadBannerToCMS($appId,$bannerDetails);
        $this->load->library('Upload_client');
        if(is_numeric($id) || strpos($id,',') !== false)
        {
            $uploadClient = new Upload_client();
            $upload = $uploadClient->uploadFile($appId,$uptype,$_FILES,array(),$id,"categoryselector", 'bannerFile');
            error_log(print_r($upload,true).'UPLOAD');
            $bannerDetails = array();
            if(is_array($upload))
            {
                $bannerDetails['logourl'] = $upload[0]['imageurl'];// put the code for uploading image to MDB server
            }
            else
            {
                $bannerDetails['logourl'] = null;
            }
            $response = $listing_client->updateCmsBanners($appId, json_encode($bannerDetails) , $id);
            if(!is_array($upload))
            {
                echo $upload;
        if(trim($_FILES['myImage']['tmp_name'][0]) == '')
                exit;

            }
        }
        if(is_numeric($id) || strpos($id,',') !== false)
            echo 'Institute added successfully';
        else
            echo $id;
    }*/

    function uploadImg()
    {
        $startTime = microtime(true);
        $this->init();
        $appId = 1;
        $bannerId = '';
        $clientId = $this->input->post('clientId');
        $bannerId = $this->input->post('bannerId');
        $bannername = $this->input->post('bannername');
        $keyword = $this->input->post('shoshkeyword');
        $shoshkeleUrl = $this->input->post('shoshkeleUrl');

        if(trim($shoshkeleUrl) == '')
            echo "Please enter iFrame URL";
        else
        {
            if($bannerId == "")
                $response = $this->insertbannerdetails($appId, $clientId,$shoshkeleUrl,$bannername);
            else
                $response = $this->updatebannerdetails($appId,$bannerId,$shoshkeleUrl,$bannername,$clientId,$keyword);

                    echo json_decode($response,true);
        }
     if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}   
    }

    private function insertbannerdetails($appId, $clientId,$bannerUrl,$bannername)
    {
		$this->load->library('Listing_client');
		$listingClient = new Listing_client();
		return $listingClient->insertbannerdetails($appId, $clientId, $bannerUrl, $bannername);
	}

    private function updatebannerdetails($appId, $bannerId,$bannerUrl,$bannername,$clientId,$keyword)
    {
		$this->load->library('Listing_client');
		$listingClient = new Listing_client();
		return $listingClient->updatebannerdetails($appId, $bannerId, $bannerUrl,$bannername,$clientId,$keyword);
	}

	function selectnduseshoshkele()
	{
	$this->load->library('category_list_client');
	$arr = array();
	$appId = 1;
	//error_log('posting is here '.print_r($_POST,true));
	$arr['bannerid'] = $this->input->post('bannerIdsnu');
	$countrySelected = $this->input->post('locationnamesnu');
	$clientId = $this->input->post('clientIdsnu');
	$cat_type = $this->input->post('cat_type');
	$courseLevel = $this->input->post('courseLevel');
	$result = $this->checkIfShosheleIsLiveOrNot($arr['bannerid'],true);
	if(!$result) {
		echo -2;
		exit();
	}
	
	$categoryid = '-1';
	if($countrySelected == NULL || $countrySelected === '') $countrySelected = 2;
	if($countrySelected == 2)
	{
		$cityId = $this->input->post('citiesofshoshkele')?$this->input->post('citiesofshoshkele'):0;
		$stateId = $this->input->post('statesofshoshkele')?$this->input->post('statesofshoshkele'):0;
		if($cat_type == 'testprep' || $cat_type == 'onlinetest'){
			$categoryid = $this->category_list_client->getBlogId($appId, $this->input->post('tp_categorynameasl'));
		}
		if ($cat_type == 'category') {
			$categoryid = $this->input->post('nationalCategoryList')?$this->input->post('nationalCategoryList'):0;
			$subcategoryid = $this->input->post('subcategorynamesnu')?$this->input->post('subcategorynamesnu'):0;
		}
	}else{
		$cityId = 0;
		$stateId = 0;
		$categoryid = $this->input->post('saCategoryList');
	}
	$arr['countryid'] = $countrySelected;
	if($countrySelected == 2)
		$arr['product'] = 'category';
	else
		$arr['product'] = 'country';
	if($cat_type == 'testprep'){$arr['product'] = 'testprep';}
	if($cat_type == 'onlinetest'){$arr['product'] = 'onlinetest';}
	$arr['cityid'] = $cityId;
	$arr['stateid'] = $stateId;
	$arr['subscriptionid'] = $this->input->post('subscription_id');
	$this->load->library('Subscription_client');
	$objSumsManage = new Subscription_client();
	$subscriptionDetails =  $objSumsManage->getSubscriptionDetails($appId,$arr['subscriptionid']);
	error_log('posting subscriptionDetails '.print_r($subscriptionDetails,true));
	$arr['startdate'] = $subscriptionDetails[0]['SubscriptionStartDate'];
	$arr['enddate'] = $subscriptionDetails[0]['SubscriptionEndDate'];
	$baseProdId = $subscriptionDetails[0]['BaseProductId'];
	$remainingQuant = $subscriptionDetails[0]['BaseProdRemainingQuantity'];
	$sumsUserId = 2492; 
	$consumeResult=$objSumsManage->consumeSubscription($appID,$arr['subscriptionid'],$remainingQuant,$clientId,$sumsUserId,$baseProdId,1,'StickyListing',$arr['startdate'],$arr['enddate']);
	$arr['subcategoryid'] = $subcategoryid;
	$arr['categoryid'] = $categoryid;
	$arr['status'] = $countrySelected==2?'live':ENT_SA_PRE_LIVE_STATUS;
	// assign course level in case of abroad
	if($arr['product']=='country')
	{
	    $arr['course_level'] = $courseLevel;
	}
	//error_log(print_r($arr,true).'ARRAY');
	$this->load->library('Listing_client');
	$listing_client = new Listing_client();
	$response = $listing_client->selectnduseshoshkele($appId, $arr);
	echo $response;
	}


	function cmsuploadbanner($type='banner',$clientId = '',$sort = 'asc')
	{
	   $startTime = microtime(true);
        error_log($clientId.'CLIENTID');
	    error_log($sort.'SORT');
	    $this->init();
	    $this->load->model('listingPosting/abroadcmsmodel');
	    $abroadcmsmodel = new abroadcmsmodel();
	    $countries = $abroadcmsmodel->getAbroadCountries();

        unset($countries[2]);
	    $cmsPageArr['countries'] = $countries;
	    usort($cmsPageArr['countries'],function($c1,$c2){
		return (strcasecmp($c1['name'],$c2['name']));
	    }); // Sort the countries because that's how it should be. - Rahul Bhatnagar
		// dropdown for course levels
	    $cmsPageArr['courseLevels'] = $abroadcmsmodel->getAbroadCourseLevels();
	    /*
	     * Fetching various category lists based on parameters to show on pages
	     */
	    $this->load->model('categoryList/categorymodel');
	    $categorymodel = new CategoryModel();
	    $cmsPageArr['nationalMainCategoryList'] = $categorymodel->getMainCategories('national');
	    $nationalSubCategoryList = Array();
	    foreach($cmsPageArr['nationalMainCategoryList'] as $nationalMainCategory){
		$nationalSubCategoryList[$nationalMainCategory['id']] = $categorymodel->getSubCategories($nationalMainCategory['id'],'national');
	    }
	    $cmsPageArr['nationalSubCategoryList'] = $nationalSubCategoryList;
	    
	    $cmsPageArr['saMainCategoryList'] = $categorymodel->getMainCategories('studyabroad');
	    
	    $validity = $this->checkUserValidation();
	    $cmsPageArr['validateuser'] = $validity;
    
	    $listing_client = new Listing_client();
	    $testprep_category_list = $listing_client->get_exam_categories(0);
	    $categoryClient = new Category_list_client();
	    $categoryList = $categoryClient->getCategoryTree($appId, 1, 'national');
	    
	    $categoryList = Array();
	    foreach($categorymodel->getSubCategories(1,'national') as $subcategoryItem){
		array_push($categoryList,Array(
					       'categoryName' => $subcategoryItem['name'],
					       'categoryID'   => $subcategoryItem['boardId'],
					       'urlName'      => $subcategoryItem['urlName'],
					       'parentId'     => $subcategoryItem['parentId'],
					       'flag'         => $subcategoryItem['flag']
					       ));
	    }
	    foreach($cmsPageArr['nationalSubCategoryList'] as $subcategoryListItem){
		foreach($subcategoryListItem as $subcategoryItem){
		    array_push($categoryList,Array(
						   'categoryName' => $subcategoryItem['name'],
						   'categoryID'   => $subcategoryItem['boardId'],
						   'urlName'      => $subcategoryItem['urlName'],
						   'parentId'     => $subcategoryItem['parentId'],
						   'flag'         => $subcategoryItem['flag']
						   ));
		}
	    }
	    $tier1 = $categoryClient->getCitiesInTier($appId, 1,2);
	    $tier2 = $categoryClient->getCitiesInTier($appId, 2,2);
	    $tier3 = $categoryClient->getCitiesInTier($appId, 3,2);
	    $cities = array_merge($tier1,$tier2,$tier3);
	    $states = array();
	    foreach($cities as $city){
		    if($city['stateName'] != ''){
			    $tmpA['stateId'] = $city['stateId'];
			    $tmpA['stateName'] = $city['stateName'];
			    $states[$tmpA['stateId']] = $tmpA;
		    }
	    }
	    $sort_col = array();
	    foreach ($cities as $sub) $sort_col[] = $sub['cityName'];
	    array_multisort($sort_col, $cities);
	    $sort_col = array();
	    foreach ($states as $sub) $sort_col[] = $sub['stateName'];
	    array_multisort($sort_col, $states);
	    $cmsPageArr['cities'] = $cities;
	    $cmsPageArr['states'] = $states;
	    $cmsPageArr['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
	    $cmsUserInfo = $this->cmsUserValidation();
	    if($cmsUserInfo['usergroup']!='cms'){
		    header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
	    }
	    $cmsPageArr['lstype'] = $type;
	    $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
	    $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
	    $this->load->library('sums_product_client');
	    $objSumsProduct =  new Sums_Product_client();
	    if(!empty($clientId)){
		    $cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$clientId));
	    }
    
	    $arrforbanners = array();
	    $arrofinstitutes = array();
	    $cmsPageArr['clientId'] = $clientId;
	    $cmsPageArr['sortorder'] = $sort;
	    if($sort == "desc")
		    $imgname = "/public/images/arrow_down.png";
	    else
		    $imgname = "/public/images/arrow_up.png";
	    $cmsPageArr['imgname'] = $imgname;
	    if(trim($clientId) != '')
	    {
		    $listing_client = new Listing_client();
		    if($type == "banner")
		    {
			    $arrforbanners = $listing_client->getShoshkeleDetails(1,$clientId,$sort);
			    $arrforbanners = json_decode($arrforbanners,true);
			    $cmsPageArr['arrforbanners'] = $arrforbanners;
			    //echo "<pre>".print_r($arrforbanners,true)."</pre>";
		    }
		    else
		    {
			    $arrofinstitutes = $listing_client->getListingSponsorDetails(1,$clientId,$sort);
			    $cmsPageArr['arrofinstitutes'] = $arrofinstitutes;
		    }
    
	    }
	    $cmsPageArr['prodId'] = 33;
	    if($type == "banner")
	    {
		    $this->load->view('enterprise/cms_uploadBanner',$cmsPageArr);
	    }
	    else
	    {
		    $this->load->view('enterprise/cms_managelisting',$cmsPageArr);                
	    }
	   if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    function cmsaddstickylisting()
    {
    $startTime = microtime(true);
	$this->init();
	$this->load->library('category_list_client');
	$arr = array();
	$appId = 1;
	$arr['clientid'] = $this->input->post('clientIdasl');
	$arr['listingid'] = $this->input->post('listingIdasl');
	$countrySelected = $this->input->post('locationnameasl');
	if($countrySelected == 2){
	    $categoryid  = $this->input->post('nationalCategorySelect');
	    $arr['listing_type'] = 'institute';
	    $arr['course_level'] = 'All';
	}else{
	    $categoryid  = $this->input->post('saCategorySelect');
	    $arr['listing_type'] = 'university';
	    $arr['course_level'] = $this->input->post('course_level');
	}
	$categoryClient = new Category_list_client();
	$categoryList = $categoryClient->getCategoryTree($appId, 1);
	$tier1 = $categoryClient->getCitiesInTier($appId, 1,2);
	$tier2 = $categoryClient->getCitiesInTier($appId, 2,2);
	$cities = array_merge($tier1,$tier2);
	$cmsPageArr['cities'] = $cities;
	if($countrySelected == 2)
	{
	    $cityId = $this->input->post('citiesofshoshkele');
	    $pagename = 'category';
	    $subcategoryid = $this->input->post('subcategorynameasl');
	    $stateId = $this->input->post('statesofshoshkele');
	}
	else
	{
	    $cityId = 0;
	    $pagename = 'country';
	    $subcategoryid = 0;
	    $stateId = 0;
	}
	$arr['pagename'] = $pagename;
	$arr['status'] = $countrySelected==2?'live':ENT_SA_PRE_LIVE_STATUS;
	$arr['categoryid'] = $categoryid?$categoryid:0;
	$arr['subcategoryid'] = $subcategoryid?$subcategoryid:0;
	$arr['countryid'] = $countrySelected;
	$arr['cityid'] = $cityId?$cityId:0;
	$arr['stateid'] = $stateId?$stateId:0;
	$arr['subscriptionid'] = $this->input->post('subscription_id');
	
	//We will not consume any credit in case of abroad for adding sticky listing as per new product requirement.
	if($arr['subscriptionid']>0 && $arr['countryid']==2)
	{
		$this->load->library('Subscription_client');
		$objSumsManage = new Subscription_client();
		$subscriptionDetails =  $objSumsManage->getSubscriptionDetails($appId,$arr['subscriptionid']);
		$sumsUserId = 2492;
		$arr['startdate'] = $subscriptionDetails[0]['SubscriptionStartDate'];
		$arr['enddate'] = $subscriptionDetails[0]['SubscriptionEndDate'];
		$baseProdId = $subscriptionDetails[0]['BaseProductId'];
		$remainingQuant = $subscriptionDetails[0]['BaseProdRemainingQuantity'];
		$consumeResult=$objSumsManage->consumeSubscription($appID,$arr['subscriptionid'],$remainingQuant,$arr['clientid'],$sumsUserId,$baseProdId,1,'StickyListing',$arr['startdate'],$arr['enddate']);
	}else{
		$arr['startdate'] = '0000-00-00 00:00:00';
		$arr['enddate'] = '0000-00-00 00:00:00';
		$arr['subscriptionid'] = 0;
		$arr['comment'] = 'For SA, subscriptionid,end date,start date will be applied when we couple this sticky listing with Shoshkele';
	}
	
	$this->load->library('Listing_client');
	$listing_client = new Listing_client();
	$sucmsg = $listing_client->cmsaddstickylisting(1,$arr);
	
	// add this university's courses to abroadIndexLog
	$abroadPostingLib = $this->load->library('listingPosting/AbroadPostingLib');
	$abroadPostingLib->addUnivCoursesToAbroadIndexLog($arr['listingid']);
	
	echo $sucmsg;
    if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    function cmsgetlistingdetails($listingid,$listingtype)
    {
        $this->init();
	if($listingtype == 'institute'){
	    $listing_client = new Listing_client();
	    $sucmsg = $listing_client->cmsgetlistingdetails(1,$listingid);
	    echo json_encode($sucmsg);
	}
	else if($listingtype == 'university'){
	    $this->load->model('listingPosting/abroadcmsmodel');
	    $abroadcmsmodel = new abroadcmsmodel();
	    $details = $abroadcmsmodel->getListingDetails($listingid,$listingtype);
	    if(empty($details) || !($details[$listingid]['username'])){
		echo json_encode(array(
				       'username' => "",
				       'categoryids' => "",
				       'subcategoryids' => "",
				       'blogids' => "",
				       'country_id' => "",
				       'city_id' => "",
				       'state_id' => "",
				       'course_level'=>'',
				       'existingStickies' => ''
				       ));
	    }
	    else{
		$resp = array();
		$resp['username'] = $details[$listingid]['username'];
		$resp['categoryids'] = '';
		$resp['subcategoryids'] = '';
		$resp['blogids'] = '0';
		$resp['country_id'] = $details[$listingid]['countryId'];
		$resp['city_id'] = '';
		$resp['state_id'] = '';
		$resp['country_tier'] = $details[$listingid]['country_tier'];
		$resp['category_tier'] = '';
		foreach($details[$listingid]['institutes'] as $instituteItem){
		   $resp['categoryids'] .= $instituteItem['categoryId'];
		   $resp['category_tier'] .=$instituteItem['categoryTier'];
		   $resp['subcategoryids'] .= $resp['subcategoryids'];
		}
		$resp['course_level'] = $abroadcmsmodel->getCourseLevelsOfUniversity($listingid);
		$resp['existingStickies'] = $details['existingStickies'];
		trim($resp['categoryids'],',');
		trim($resp['subcategoryids'],',');
		trim($resp['category_tier'],',');
		echo json_encode($resp);
	    }
	}
	else{
	    echo json_encode(array(
				       'username' => "",
				       'categoryids' => "",
				       'subcategoryids' => "",
				       'blogids' => "",
				       'country_id' => "",
				       'city_id' => "",
				       'state_id' => "",
				       'course_level'=>''
				       ));
	}
    }
    
    function checkIfShosheleIsLiveOrNot($bannerId,$returnStatus = false)
    {
        $this->init();
        
        $listingModel = $this->load->model('listingmodel');
        $sucmsg = $listingModel->checkIfShosheleIsLiveOrNot($bannerId);
        if(!$returnStatus) {
        	echo count($sucmsg);
        } else {
        	return count($sucmsg);
        }
    
    }

    function cmsremoveshoshkele($bannerId,$tablename)
    {
    	$this->init();
    	$listing_client = new Listing_client();
    	$sucmsg = $listing_client->cmsremoveshoshkele(1,$bannerId,$tablename);
    	return $sucmsg;
    }
    
    
    function cmscoupledecouple($clientId = '',$categoryId = '',$subcategoryId = '',$countryId = '',$cityId = '',$stateId = '',$cat_type='category',$courseLevel="All",$status = '',$listingsubsid,$bannerlinkid)
    {
        $startTime = microtime(true);
	//_p("clientId:".$clientId.",categoryId:".$categoryId.",subcategoryId:".$subcategoryId.",countryId:".$countryId.",cityId:".$cityId.",stateId:".$stateId.",cat_type:".$cat_type.",courseLevel:".$courseLevel.",status:".$status.",listingsubsid:".$listingsubsid.",bannerlinkid:".$bannerlinkid);die;
        $this->init();
        $validity = $this->checkUserValidation();
        if($status != '')
        {
            $listing_client = new Listing_client();
            $arrofinstitutes = $listing_client->changeCouplingStatus(1,$listingsubsid,$bannerlinkid,$status);
            header('Location:/enterprise/Enterprise/cmscoupledecouple/'.$clientId.'/'.$categoryId.'/'.$subcategoryId.'/'.$countryId.'/'.$cityId.'/'.$stateId.'/'.$cat_type.'/'.$courseLevel);
        }
	
	/*
	 * Country list for the pages, fetched from database
	 */
	$this->load->model('listingPosting/abroadcmsmodel');
	$abroadcmsmodel = new abroadcmsmodel();
	$countries = $abroadcmsmodel->getAbroadCountries();
        unset($countries[2]);

        $cmsPageArr['countries'] = $countries;
	$cmsPageArr['courseLevels'] = array_merge( array(array("CourseName" => "All")) , $abroadcmsmodel->getAbroadCourseLevels());
	$cmsPageArr['userSelectCourseLevel'] = $courseLevel;
	usort($cmsPageArr['countries'],function($c1,$c2){
	    return (strcasecmp($c1['name'],$c2['name']));
	}); // Sort the countries because that's how it should be. - Rahul Bhatnagar
	/*
	 * Fetching various category lists based on parameters to show on pages
	 */
	$this->load->model('categoryList/categorymodel');
	$categorymodel = new CategoryModel();
	$cmsPageArr['nationalMainCategoryList'] = $categorymodel->getMainCategories('national');
	$nationalSubCategoryList = Array();
	foreach($cmsPageArr['nationalMainCategoryList'] as $nationalMainCategory){
	    $nationalSubCategoryList[$nationalMainCategory['id']] = $categorymodel->getSubCategories($nationalMainCategory['id'],'national');
	}
	$cmsPageArr['nationalSubCategoryList'] = $nationalSubCategoryList;
	$cmsPageArr['saMainCategoryList'] = $categorymodel->getMainCategories('studyabroad');
	
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['lstype'] = 'coupledecouple';

        $listing_client = new Listing_client();

        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId, 1, 'national');
        $tier1 = $categoryClient->getCitiesInTier($appId, 1,2);
        $tier2 = $categoryClient->getCitiesInTier($appId, 2,2);
		$tier3 = $categoryClient->getCitiesInTier($appId, 3,2);
        $cities = array_merge($tier1,$tier2,$tier3);
		$states = array();
		foreach($cities as $city){
			if($city['stateName'] != ''){
				$tmpA['stateId'] = $city['stateId'];
				$tmpA['stateName'] = $city['stateName'];
				$states[$tmpA['stateId']] = $tmpA;
			}
		}
		
		$sort_col = array();
		foreach ($cities as $sub) $sort_col[] = $sub['cityName'];
			array_multisort($sort_col, $cities);
			
		$sort_col = array();
		foreach ($states as $sub) $sort_col[] = $sub['stateName'];
			array_multisort($sort_col, $states);

        $cmsPageArr['cities'] = $cities;
        $cmsPageArr['states'] = $states;

        $cmsPageArr['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
		
        $cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms')
        {
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }

        $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $arrforbanners = array();
        $arrofinstitutes = array();
        $cmsPageArr['clientId'] = $clientId;
        $cmsPageArr['sortorder'] = $sort;
        if($sort == "desc")
            $imgname = "/public/images/arrow_down.png";
        else
            $imgname = "/public/images/arrow_up.png";
        $cmsPageArr['imgname'] = $imgname;
        $cmsPageArr['prodId'] = 33;
        if($clientId != '')
        {
	    $listing_client = new Listing_client();
            $arrofinstitutes = $listing_client->getListingndBannersForCoupling(1,$clientId,$countryId,$cityId,$stateId,$categoryId,$subcategoryId,$cat_type,$courseLevel);
            $cmsPageArr['arrofinstitutes'] = $arrofinstitutes;
            $cmsPageArr['countryId'] = $countryId;
            $cmsPageArr['cityId'] = $cityId;
	    $cmsPageArr['stateId'] = $stateId;
            $cmsPageArr['categoryId'] = $categoryId;
	    $cmsPageArr['subcategoryId'] = $subcategoryId;
            $cmsPageArr['cat_type'] = $cat_type;
        }
        $this->load->view('enterprise/cms_coupledecouple',$cmsPageArr);
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    function cmsCountryPage()
    {
        error_log('OKHERE');
        $this->init();
        $countryDetails = $this->input->post('countryselector');
        error_log(print_r($countryDetails,true).'COUNTRYDETAILS');

        $listing_client = new Listing_client();

        $id = $listing_client->publishCountrySelection($appId,$countryDetails);
            echo $id;
    }

    function cmsBannerUploaded()
    {
        $this->init();
        $bannerDetails = $_POST;
        $this->load->library('upload_client');
        error_log(print_r($_FILES,true));
        if($_FILES['bannerFile']['tmp_name'][0] == '')
        {
            echo 'Please choose a banner to upload';
            exit;
        }
        $type = $_FILES['bannerFile']['type'];
        error_log($type.'TyPE');
        if(is_array($type))
        {
            $type = $_FILES['bannerFile']['type'][0];
        }
        error_log($type.'TyPE');
        if(!($type== "image/gif" || $type== "image/jpeg"|| $type=="image/jpg" || $type== "image/png" || $type == 'application/x-shockwave-flash'))
        {
            echo 'jpeg,png,png,swf are allowed banner types';
            exit;
        }
        if($type ==  'application/x-shockwave-flash')
        {
            $uptype = 'video';
        }
        else
        {
            $uptype = 'image';
            $tmpSize = getimagesize($_FILES['bannerFile']['tmp_name'][0]);
            error_log(print_r($tmpSize,true).'SIZE');
            list($width, $height, $type, $attr) = $tmpSize;
            error_log($width.'WIDTH'.$height.'HEIGHT');
            if($width > 220 || $height > 100)
            {
                echo 'Allowed image size is 220 * 100';
                exit;
            }
        }
        $uploadClient = new Upload_client();
        $listing_client = new Listing_client();
        $bannerDetails = json_encode($bannerDetails);
        $id = $listing_client->uploadBannerToCMS($appId,$bannerDetails);
        $this->load->library('Upload_client');
        if(is_numeric($id) || strpos($id,',') !== false)
        {
            $uploadClient = new Upload_client();
            $upload = $uploadClient->uploadFile($appId,$uptype,$_FILES,array(),$id,"categoryselector", 'bannerFile');
            error_log(print_r($upload,true).'UPLOAD');
            $bannerDetails = array();
                if(is_array($upload))
                {
                $bannerDetails['logourl'] = $upload[0]['imageurl'];// put the code for uploading image to MDB server
                }
                else
                {
                $bannerDetails['logourl'] = null;
                }
                $response = $listing_client->updateCmsBanners($appId, json_encode($bannerDetails) , $id);
            if(!is_array($upload))
            {
                echo $upload;
                exit;

            }
        }
        if(is_numeric($id) || strpos($id,',') !== false)
            echo 'Institute added successfully';
        else
            echo $id;
    }

     function cmsUserValidation($validity_check = 'Y')
     {
        $returnArr = $this->cmsUserValidationRedirection($validity_check);
        $usergroup =  $returnArr['usergroup'];
        
        if($validity_check == 'Y') {
            $allowedUserGroupArr = array('saAdmin','saCMS','saContent','saSales','saRMS', 'saShikshaApply','saCMSLead','saCustomerDelivery','saAuditor');
            if(in_array($usergroup,$allowedUserGroupArr))
            {
                //based on usergroup redirect the user to the corresponding abroad cms interface
                $this->abroadUserCmsInterface($usergroup,$allowedUserGroupArr);

            }
        }

        $returnArr['headerTabs'] = $this->getCMSHeaderTabs($returnArr);
        $returnArr['myProducts'] = $this->getSumsProductsForUser($returnArr);
        return $returnArr;
    }

    function cmsUserValidationRedirection($validity_check = 'Y') {
        $validity = $this->checkUserValidation();
        global $logged;
        global $userid;
        global $usergroup;
        $thisUrl = $_SERVER['REQUEST_URI'];
        if(($validity == "false" )||($validity == "")) {
            $logged = "No";
            header('location:'.ENTERPRISE_HOME.'/enterprise/Enterprise/loginEnterprise');
            exit();
        }else {
            $logged = "Yes";
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
            $mailerModel = $this->load->model('mailer/mailermodel');
            $userGroupData = $mailerModel->getUserGroupInfo($validity[0]['userid']);
             if(($validity_check == 'N') && (empty($userGroupData))) {
                $validity_check = 'Y';
            }
            if($validity_check == 'Y') {
                if($usergroup == 'lead_operator'){
                    header("location:/offlineOps/index/dashboard");
                    exit;
                }
                if ($usergroup=="user" || $usergroup == "requestinfouser" || $usergroup == "quicksignupuser" || $usergroup == "tempuser" || $usergroup == "marketingPage"|| $usergroup == "veryshortregistration") {
                    header("location:/enterprise/Enterprise/migrateUser");
                    exit;
                }
                if(     !(
                            in_array($usergroup,array("cms","enterprise","sums","saAdmin","saCMS","saContent","saSales","saRMS","saShikshaApply","saCMSLead","saCustomerDelivery","saAuditor","listingAdmin",CR_MODERATOR_USER_GROUP))
                            /*($usergroup == "cms") || 
                            ($usergroup == "enterprise") || 
                            ($usergroup=="sums") || 
                            ($usergroup=="saAdmin") || 
                            ($usergroup=="saCMS") || 
                            ($usergroup=="saContent") || 
                            ($usergroup=="saSales") || 
                            ($usergroup=="saRMS") || 
                            ($usergroup=="saShikshaApply") || 
                            ($usergroup=="saCMSLead")*/
                        )
                    ){
                   header("location:/enterprise/Enterprise/unauthorizedEnt");
                    exit();
                }
            }
        }
        
        return array('validity' => $validity, 
                     'userid' => $userid, 
                     'usergroup' => $usergroup, 
                     'logged' => $logged,
                     'thisUrl' => $thisUrl);   

    }

    function getSumsProductsForUser($returnArr) {
        $this->load->library('sums_product_client');
        $objSumsProduct =  new Sums_Product_client();
        return $objSumsProduct->getProductsForUser(1,array('userId'=>$returnArr['userid']));
    }

    function getCMSHeaderTabs($returnArr) {
        $this->load->library('enterprise_client');
        $entObj = new Enterprise_client();
        return $entObj->getHeaderTabs(1,$returnArr['validity'][0]['usergroup'],$returnArr['validity'][0]['userid']);
    }

	function restrictTabs($tabs, $user)
    {
		$tabArray = $tabs;
		
		if($user[0]['usergroup'] == "enterprise") {
			
		    $smartModel = $this->load->model('smart/smartmodel');
			$clientType = $smartModel->getClientType($user[0]['userid']);
			
			if($clientType == "Abroad") {       
				$restrictedTabs = array(7,6,36);
				
				$finalTabs = array();
				
				foreach($tabs[0]['tabs'] as $tabArray) {           
					if(!in_array($tabArray['tabId'], $restrictedTabs)) {
						$finalTabs[] = $tabArray;
					}
				}
				
				$tabArray = array();		
				$tabArray[0]['tabs'] = $finalTabs;
				$tabArray[0]['selectedTab'] = $selectedTab;
			}
        }
        
        if($user[0]['usergroup']!='sums')
        {
            $restrictedTabs = array(SMART_SODETAILS_TAB_ID,ACCESS_CLIENT_LISTING_DETAILS_TAB_ID);
                
                $finalTabs = array();
                
                foreach($tabs[0]['tabs'] as $tabArray) {           
                    if(!in_array($tabArray['tabId'], $restrictedTabs)) {
                        $finalTabs[] = $tabArray;
                    }
                }
                
                $tabArray = array();        
                $tabArray[0]['tabs'] = $finalTabs;
                $tabArray[0]['selectedTab'] = $selectedTab;
        }
		return $tabArray;
    }

    // Enterprise/CMS Home Page View Load
    function index($prodId,$extraParam='',$edit = 0) {
      $startTime = microtime(true);
       switch ($prodId)
    	{
    		case '14':
    			header('Location:/enterprise/Enterprise/profile');
    			exit;
            case '31':
                header('Location:/enterprise/shikshaDB/index');
                exit;
			case '790':
				$this->responseMigration($extraParam);
                exit;
			case '817':
				$this->onlineFormTracking($extraParam);
                exit;		
            case '54':
                $this->addLocation($extraParam);
                exit;
	    	case '785':
				header('Location:/enterprise/LDBSearchTabs/ldbSearchAccessInterface');
                exit;
            case EXAM_PAGES_TAB_ID:
                header('Location:'.ENTERPRISE_HOME.'/examPages/ExamPagesCMS/addEditExamContent');
                exit;
    	}
        $cmsUserInfo = $this->cmsUserValidation();
        /*if($cmsUserInfo['headerTabs'][0]['selectedTab'] == 28 && $prodId == '') {
            header('Location:/alumni/AlumniSpeakFeedBack/showAlumFeedBacks/');
        }*/
        
    switch($prodId)
    {
        case 6: if($cmsUserInfo['usergroup'] == 'enterprise')
        {//redirecting for enterprise usergroup
            header('Location:/enterprise/Enterprise/disallowedAccess');
			exit;
		}
		break;

		case 7: if($cmsUserInfo['usergroup'] == 'enterprise')
		{//redirecting for enterprise usergroup
		 	header('Location:/enterprise/Enterprise/disallowedAccess');
		 	exit;
		}
		 break;
     }

    switch ($cmsUserInfo['headerTabs'][0]['selectedTab'])
    {
        case '25':
        header('Location:/mailer/Mailer/manageMails');
        exit;            
        case '28':
        header('Location:/alumni/AlumniSpeakFeedBack/showAlumFeedBacks/');
        exit;
        case '46':
        header('Location:/enterprise/MultipleMarketingPage/marketingPageDetails/');
        exit;
		case SMART_DASHBOARD_TAB_ID:
		if(empty($prodId)) {
			header('Location:/smart/SmartMis/viewDashboard/');
			exit;
		}
		break;
        case LMS_PORTING_TAB_ID:
        header('Location:/lms/Porting/managePortings/');
        exit;

        case CR_MODERATOR_TAB_ID:
            header('Location:/CAEnterprise/CampusAmbassadorEnterprise/getCollegeReviews');
            exit;
    }
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        // $this->load->model('messageBoard/AnAModel');
        // $expertLevelsForFilter = $this->AnAModel->getExpertLevels('AnA');
        $this->load->helper('messageBoard/abuse');
        $expertLevelsForFilter = getExpertLevels();

        $flagMedia = 1;

        $cmsPageArr = array();
        $cmsPageArr['prodId'] = $prodId;

        global $homePageMap;
        $keyPageArray = array_flip($homePageMap);
	///commented by pragya
//        if($prodId == 1) {
//            $keyPageArray[51] = 'FLAVOUR OF THE WEEK';
//	    $keyPageArray[52] = 'LATEST UPDATE';	
//        }
        $spaceNamedArray = str_replace("_"," ",$keyPageArray);
        $cmsPageArr['keyid_page_name'] = json_encode($spaceNamedArray);
        $cmsPageArr['totalKeyPageCount'] = max($homePageMap);
        $CategoryClientObj = new Category_list_client();
        $countryList = $CategoryClientObj->getCountries();
        $cmsPageArr['countryList'] = $countryList;
        $cityListTier1 = $CategoryClientObj->getCitiesInTier($appId,1,2);
        $cityListTier2 = $CategoryClientObj->getCitiesInTier($appId,2,2);
        $cityListTier3 = $CategoryClientObj->getCitiesInTier($appId,0,2);
        $cmsPageArr['cityTier1'] = $cityListTier1;
        $cmsPageArr['cityTier2'] = $cityListTier2;
        $cmsPageArr['cityTier3'] = $cityListTier3;
        $cmsPageArr['flagMedia'] = $flagMedia;
        $cmsPageArr['userid'] = $userid;
        $cmsPageArr['usergroup'] = $usergroup;
        $cmsPageArr['thisUrl'] = $thisUrl;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['extraParam'] = $extraParam;
        $cmsPageArr['expertLevelsForFilter'] = $expertLevelsForFilter;
        $entObj = new Enterprise_client();
        $cmsPageArr['searchCategories']=$entObj->getSearchSubCategories(1);

	// Start Online form change by pranjul 13/10/2011
    	$this->load->library('OnlineFormEnterprise_client');
    	$ofObj = new OnlineFormEnterprise_client();
    	$cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($userid);
    	// End Online form change by pranjul 13/10/2011
        if(!isset($prodId)){
            $cmsPageArr['prodId'] = $cmsPageArr['headerTabs'][0]['selectedTab'];
        }

        $tabFlag = false;
        if(isset($prodId)){
            foreach($cmsPageArr['headerTabs'][0]['tabs'] as $tabArr){
                if($tabArr['tabId'] == $prodId){
                    $tabFlag = true;
                }

                if($tabFlag == true || $prodId == '30' || $prodId == '35'){
                    $cmsPageArr['prodId'] = $prodId;
                }else{
                    $cmsPageArr['prodId'] = $cmsPageArr['headerTabs'][0]['selectedTab'];
                }
            }
        }
		//if ($prodId==12)
		//{
		//	$cmsPageArr['viewCount'] = $entObj->getViewCountForUserFedListings(1,$userid);
		//}
        switch($prodId){
            case '10': //Product Catalog
	      header("location:/enterprise/Enterprise/prodAndServ");
	      exit();
            break;
	    case '30':
	    	//redirecting for enterprise usergroup
	    	
	    	if($cmsUserInfo['usergroup'] == "enterprise"){
	    		header("location:/enterprise/Enterprise/disallowedAccess");
	    		exit();
	    	}	
	    	
	      $this->load->library('alumniSpeakClient');
	      $objAlumniSpeakClientObj= new AlumniSpeakClient();
	      $pageNum = 0;
	      $numRecords = 20000;
	      $sort = array('criteria_id,course_comp_year desc');
	      $response = $objAlumniSpeakClientObj->getFeedbacksForInstitute($appId, $extraParam, json_encode($sort),0, $pageNum, $numRecords);
	      $cmsPageArr['alumniReviews'] = $response;
	      $threadIdList = '';
	      for($i=0;$i<count($cmsPageArr['alumniReviews']);$i++)
	      {
		  if($cmsPageArr['alumniReviews'][$i]['thread_id'] != ''){
		    if($threadIdList=='')
		      $threadIdList .= $cmsPageArr['alumniReviews'][$i]['thread_id'];
		    else
		      $threadIdList .= ",".$cmsPageArr['alumniReviews'][$i]['thread_id'];
		    }
	      }
	      $response = $objAlumniSpeakClientObj->getRepliesForInstitute($appId,$threadIdList,$this->userStatus[0]['userid'] );
	      $cmsPageArr['alumniReviewsReply'] = $response;
	      break;
            case '35': //Report Abuse
		$this->init();
		$appId = 12;
		$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
		$totalQuestions = 0;
		$totalAnswers = 0;
		$totalQuestionAsked = 0;
		$totalQuestionAnswered = 0;
		$parameterObj = array('abuse' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));

        //Get the abuse form fields
        $this->load->library('message_board_client');
        $msgbrdClient = new Message_board_client();
        $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
        $Result = $msgbrdClient->getReportAbuseForm($appId,"QuestionAnswer");
        $abuseReasonsForFilter = $Result;


		$entObj = new Enterprise_client();
		$resultUserAbuse = $entObj->getAbuseList($appId,$loggedInUserId,0,5,"AnA","All");

		$totalAbuseReport = isset($resultUserAbuse[0]['totalAbuseReport'])?$resultUserAbuse[0]['totalAbuseReport']:0;
		$parameterObj['abuse']['offset'] = 0;
		$parameterObj['abuse']['totalCount'] = $totalAbuseReport;
		$tempArray=array();
		$abuseDetailsArray=array();
		if(isset($resultUserAbuse[0]['results'])){
			$tempArray = &$resultUserAbuse[0]['results'];
		}
		if(isset($resultUserAbuse[0]['abuseDetails'])){
			$abuseDetailsArray = &$resultUserAbuse[0]['abuseDetails'];
		}

		$abuseForm = is_array($Result)?$Result:array();
		$abuseFormFields = array();
		for($i=0;$i<count($abuseForm);$i++)
		{
		  $abuseFormFields[] = $abuseForm[$i]['Title'];
		}

		for($i=0;$i<count($tempArray);$i++)
		{
			if(is_array($tempArray[$i]['abuse'])){
				//Set the message creation date
				//$tempArray[$i]['abuse']['msgCreationDate'] = makeRelativeTime($tempArray[$i]['abuse']['msgCreationDate']);
				$dateobj = strtotime($tempArray[$i]['abuse']['msgCreationDate']);
				$tempArray[$i]['abuse']['msgCreationDate'] = date("d F, Y", $dateobj);
				//Set the URL
				$tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getTopicDetail/".$tempArray[$i]['abuse']['threadId'];
			}
		}
		for($i=0;$i<count($abuseDetailsArray);$i++)
		{
		    //Set the abuse reason text
		    $abuseReasons = explode(",",$abuseDetailsArray[$i]['abuseReason']);
		    $fields = '';
		    for($j=0;$j<count($abuseReasons);$j++){
		      $index = $abuseReasons[$j]-1;
		      if($fields == '')
			$fields .= $abuseFormFields[$index];
		      else
			$fields .= ", ".$abuseFormFields[$index];
		    }
		    $abuseDetailsArray[$i]['abuseReason'] = $fields;
		    //Set the abuse report date
		    $dateobj = strtotime($abuseDetailsArray[$i]['creationDate']);
		    $abuseDetailsArray[$i]['creationDate'] = date("d/m/y", $dateobj);
		}
		$Validate = $this->userStatus;
		$cmsPageArr['abuseReport']['validateuser'] = $Validate;
		$cmsPageArr['abuseReport']['parameterObj'] = json_encode($parameterObj);
		$cmsPageArr['abuseReport']['userAbuse'] = isset($resultUserAbuse[0]['results'])?$resultUserAbuse[0]['results']:array();
		$cmsPageArr['abuseReport']['abuseDetails'] = isset($resultUserAbuse[0]['abuseDetails'])?$resultUserAbuse[0]['abuseDetails']:array();
		$cmsPageArr['abuseReport']['totalAbuse'] = $totalAbuseReport;
		$cmsPageArr['abuseReport']['filterSel'] = "All";
		$cmsPageArr['abuseReport']['moduleName'] = "AnA";
		$cmsPageArr['abuseReport']['startFrom'] = 0;
		$cmsPageArr['abuseReport']['countOffset'] = 5;
		$answerSuggestions = isset($resultUserAbuse[0]['answerSuggestions'])?$resultUserAbuse[0]['answerSuggestions']:array();
		$cmsPageArr['abuseReport']['answerSuggestions'] = $this->convertSuggestionArray($answerSuggestions);
            break;
	    case '45':
		$spotlightEvents=$entObj->getSpotlightEvents();
		$cmsPageArr['spotlightEvents']=$spotlightEvents;
		$cmsPageArr['refererAddEvent']=$extraParam;
		break;
		case '47':
		$cmsPageArr['powerUser']['startFrom'] = 0;
		$cmsPageArr['powerUser']['countOffset'] = 5;
		break;
		case '50':
		$categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId,'','national');
		$cmsPageArr['popCourse']['catTree'] = $categoryList;
		break;
		case '51':
                    $categoryClient = new Category_list_client();
                    $categoryList = $categoryClient->getCategoryTree($appId,'', 'national');
                    $cmsPageArr['catpageHeader']['catTree'] = $categoryList;
                    $tier1 = $categoryClient->getCitiesInTier($appId, 1,2);
                    $tier2 = $categoryClient->getCitiesInTier($appId, 2,2);
                    $tier3 = $categoryClient->getCitiesInTier($appId, 3,2);
                    $cities = array_merge($tier1,$tier2,$tier3);
		            $states = array();
                    foreach($cities as $city){
                            if($city['stateName'] != ''){
                                    $tmpA['stateId'] = $city['stateId'];
                                    $tmpA['stateName'] = $city['stateName'];
                                    $states[$tmpA['stateId']] = $tmpA;
                            }
                    }
		
                    $sort_col = array();
                    foreach ($cities as $sub) $sort_col[] = $sub['cityName'];
                            array_multisort($sort_col, $cities);

                    $sort_col = array();
                    foreach ($states as $sub) $sort_col[] = $sub['stateName'];
                            array_multisort($sort_col, $states);

                    $cmsPageArr['catpageHeader']['cities'] = $cities;
                    $cmsPageArr['catpageHeader']['states'] = $states;
					$cmsPageArr['catpageHeader']['zones'] = $categoryClient->getZones('1');
					$zoneList = array();
					foreach($cmsPageArr['catpageHeader']['zones'] as $zone){
						$zoneList[$zone[0]['zoneId'][0]] = $zone[0]['zoneName'][0]; 
					}
					//_p($zoneList);
					$cmsPageArr['catpageHeader']['zones'] = $zoneList;
					$cmsPageArr['catpageHeader']['localities'] = $categoryClient->getAllLocalities();
					$cmsPageArr['catpageHeader']['localities'] = json_decode($cmsPageArr['catpageHeader']['localities'],true);
					//_p($cmsPageArr['catpageHeader']['localities']);
                    $cmsPageArr['catpageHeader']['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
		
        	    $cmsPageArr['catpageHeader']['courseList'] = $categoryClient->getSubCategoryCourses('');
		break;
        case 52:
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId,'', 'national');
        $cmsPageArr['catpageHeader']['catTree'] = $categoryList;                    
        // echo "<pre>";print_r($categoryList); die();
        $cmsPageArr['catpageHeader']['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
        $cmsPageArr['catpageHeader']['widgetType'] = $extraParam;
        break;
				
        case 53:
                     if($extraParam == 5) {
			 $data = $this->studyAbroadHelpMain($edit);
                    	 $cmsPageArr = array_merge($cmsPageArr,$data);
                         $cmsPageArr['catpageHeader']['widgetType'] = $extraParam;
                    } else {
		            $categoryClient = new Category_list_client();
		            $categoryList = $categoryClient->getCategoryTree($appId,'', 'studyabroad');
		            $cmsPageArr['catpageHeader']['catTree'] = $categoryList;                    
		            // echo "<pre>";print_r($categoryList); die();
		            $cmsPageArr['catpageHeader']['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
		            $cmsPageArr['catpageHeader']['widgetType'] = $extraParam;
		            $regions = json_decode($categoryClient->getCountriesWithRegions(),true);
		            $regionArray = array();
		            $countriesArray = array();
		            foreach($regions as $region){
		                    foreach($region as $r){
		                            if($r['regionid'])
		                                    $regionArray[$r['regionid']] = $r['regionname'];
		                            $countriesArray[$r['countryId']] = $r['name'];
		                    }
		            }
		            $cmsPageArr['catpageHeader']['countries'] = $countriesArray;
		            $cmsPageArr['catpageHeader']['regions'] = $regionArray;
		    }
                    break;
				 case 56:
					$categoryClient = new Category_list_client();
                    $categoryList = $categoryClient->getCategoryTree($appId,'', 'national');
                    $cmsPageArr['catpageHeader']['catTree'] = $categoryList;
                    $cmsPageArr['catpageHeader']['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
					$cmsPageArr['catpageHeader']['courseList'] = $categoryClient->getSubCategoryCourses('');
                    break;

                case 55:
                    /*
                    $categoryClient = new Category_list_client();
                    $categoryList = $categoryClient->getCategoryTree($appId,'', 'national');
                    $cmsPageArr['catpageHeader']['catTree'] = $categoryList;
                    // echo "<pre>";print_r($categoryList); die();
                    $cmsPageArr['catpageHeader']['categoryList'] = json_encode($this->formatCategoryTree($categoryList));
                    $cmsPageArr['catpageHeader']['widgetType'] = $extraParam;
                     * 
                     */
                    $loadViewFile = "enterprise/showListingsContactDetails";

                    break;

		case 12:
		    $smartModel = $this->load->model('smart/smartmodel');
		    $clientType = $smartModel->getClientType($userid);
		    $cmsPageArr['clientType'] = $clientType;
		    if($clientType == 'Abroad')
			$this->getSAContactAndViewCountDetails($userid, $usergroup, $cmsPageArr);
		    else
			$this->getContactAndViewCountDetails($userid, $usergroup,$cmsPageArr);
		    break;
		case 203:
				$cmsPageArr['listingMis'] = $this->getlistingMIS();
				$cmsPageArr['from']=$this->input->post('from_date');
				$cmsPageArr['to']=$this->input->post('to_date');
				break;
        default:
                if($usergroup == 'enterprise'){
                    $this->getContactAndViewCountDetails($userid, $usergroup,$cmsPageArr);
                }
        }
		
        if($cmsPageArr['prodId'] == 12) {
                $cmsPageArr['viewCount'] = $entObj->getViewCountForUserFedListings(1,$userid);
        }

        if(isset($loadViewFile) && $loadViewFile != "") {
            $this->load->view($loadViewFile,$cmsPageArr);
        } else { 
	    if($cmsUserInfo['clientType'] == "Abroad")
	    {
		header("Location:/enterprise/Enterprise/profile");
	    }else{
            $cmsPageArr['abuseReasonsForFilter'] = $abuseReasonsForFilter;
		$this->load->view('enterprise/cms_homepage',$cmsPageArr);
	    }
        }
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    //Controller API to check Product-Access right corresponding to usergroup
    function productAccessCheck($productName) { //productName as is shown in the Enterprise Tabs
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];

        foreach($cmsUserInfo['headerTabs'][0]['tabs'] as $tabArr){
            if($tabArr['tabName'] == $productName){
                $prodId = $tabArr['tabId'];
            }
        }

        if(!isset($prodId)){
//            header("location:/enterprise/Enterprise/disallowedAccess");
//            exit();
        }else{
            $cmsPageArr['prodId'] = $prodId;
        }

        return $cmsPageArr['prodId'];
    }
    /**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	private function _studyAbroadHelpinit() {
		//set user details
		$validateuser = $this->checkUserValidation();
		if(($validateuser == "false" )||($validateuser == "")) {
			header('location:'.ENTERPRISE_HOME);exit();
		}
		if(is_array($validateuser) && $validateuser['0']['usergroup']!='cms') {
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		//load the required library
		$this->load->library('Enterprise_client');
		$this->load->model('enterprise/studyabroadwidgetmodel');
		$this->studyabroadwidgetmodel = new studyabroadwidgetmodel();
    }
    	/**
	 * Default method that gets invoked
	 *
	 * @param none
	 * @return void
	 */
	public function studyAbroadHelpMain($edit =0) {
		// call init method to set basic objects
		$this->_studyAbroadHelpinit();
		$data['headerContentaarray'] = "";
		$post_array = $_POST;
		$carousel_title = addslashes(strip_tags($this->input->post('carousel_title',true)));
		$carousel_description = addslashes(strip_tags($this->input->post('carousel_description',true)));
		if(is_array($post_array) && !empty($carousel_title)) {
			$carousel_photo_data = $this->helpuploadImage($_FILES);
			if(is_array($carousel_photo_data) && !empty($carousel_photo_data['data'])) {
				$carousel_photo_url = $carousel_photo_data['data'];
			} else {
				$carousel_photo_url = '';
				$data['error_message'] = $carousel_photo_data['error_message'];
			}
			$data['carousel_title'] = $carousel_title;
			$data['carousel_description'] = $carousel_description;
			$data['carousel_photo_url'] = $carousel_photo_url;
					
			if($edit ==1 && !empty($carousel_photo_url)) {
				$date = date('y-m-d h:i:s', time());
				$array_to_update = array('registrationLayerTitle'=>$carousel_title,
				                        'registrationLayerMsg'=>$carousel_description,
				                        'registrationBannerURL'=>$carousel_photo_url,
				                        'carousle_id'=>$this->input->post('carousle_id',true),
				                        'status'=>'live',
				                        'addedOn'=>$date
										);
				$result = $this->studyabroadwidgetmodel->updateCarouselDeatils($array_to_update);
				$data['main_suc_message'] = 'Your settings have been updated';
			} else {
				if(!empty($carousel_photo_url)) {
					$result = $this->studyabroadwidgetmodel->addContentToCarouselWidget($carousel_title,$carousel_photo_url,
				$carousel_description);
				$data['main_suc_message'] = 'Your settings have been saved';
				}
			}
		}
		$data['carousel_array'] = json_decode($this->studyabroadwidgetmodel->renderCarouselDeatils(),true);
		$data['edit'] = $edit;
		if(count($data['carousel_array'])>0) {
			foreach ($data['carousel_array'] as $value) {
					$data['carousel_title'] = $value['registrationLayerTitle'];
					$data['carousel_description'] = $value['registrationLayerMsg'];
					$data['carousel_photo_url'] = $value['registrationBannerURL'];
					$data['carousle_id'] = $value['id'];
			}
		}
                return $data;
		//$this->load->view('enterprise/studyabroadPageWidget',$data);
	}
    	/**
	 * Upload and save banner image
	 *
	 * @access	private
	 * @return	array
	 */
	public function helpuploadImage($files, $vcard = 0,$appId = 1){
		$this->_init();
		if($files['myImage']['tmp_name'][0] == '')
		$data['error_message'] = "Please select a photo to upload";
		else
		{
			$this->load->library('Upload_client');
			$uploadClient = new Upload_client();
			$upload = $uploadClient->uploadFile($appId,'image',$files,array(),$this->_validateuser['0']['userid'],"user", 'myImage');
			if(!is_array($upload)) {
				$data['error_message'] =  $upload;
			} else
			{
				list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
				if(!($width == '207' && $height == '151')) {
					$data['error_message'] = "Please upload an image of size 207 * 151";
					return $data;
				}
				$data['data'] =  $upload[0]['imageurl'];
				$data['success_message'] = "Image has been successfully uploaded, please click on above save button to save the image";
			}

		}
		return $data;

	}
    //Controller API to Update CMS-<Product> Table
    function updateCmsItem($appId='1') {
        $appId = 1; // Usually URI id = 4

        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        $EnterpriseClientObj = new Enterprise_client();

        error_log_shiksha("controller's cms form data".print_r($_REQUEST,true));
        if(isset($_REQUEST['updateCms']))
        {
            //Making array which will be sent as parameter to client function
            $updateCMSData = array();

            $updateCMSData['item_type'] = isset($_REQUEST['prod_type']) ? $_REQUEST['prod_type'] : "";
            /*if($updateCMSData['item_type'] == "msgboard")
            {
                $updateCMSData['categoryId'] = isset($_REQUEST['category_id']) ? $_REQUEST['category_id'] : "";
                $updateCMSData['topicId'] = isset($_REQUEST['topic_id']) ? $_REQUEST['topic_id'] : "";
            }
            else */
           // {
	          $updateCMSData['item_id'] = isset($_REQUEST['prod_id']) ? $_REQUEST['prod_id'] : "";
           // }
            $updateCMSData['selectTabId'] = isset($_REQUEST['selectTabId']) ? $_REQUEST['selectTabId'] : "";
            $updateCMSData['totalKeyPages'] = isset($_REQUEST['totalKeyPages']) ? $_REQUEST['totalKeyPages'] : "";


            //Enabled Key Pages array
            $updateKeyPage= array();
            foreach($_REQUEST['key_page'] as $page_id){
                $updateKeyPage[$page_id.'_key_id'] = $page_id;

                if(!isset($_REQUEST['from_'.$page_id]))
                $_REQUEST['from_'.$page_id] = date('Y-m-d G:i:s');
                if(!isset($_REQUEST['to_'.$page_id]))
                $_REQUEST['to_'.$page_id] = date('Y-m-d G:i:s');

                $updateKeyPage[$page_id.'_start_date'] = $_REQUEST['from_'.$page_id];
                $updateKeyPage[$page_id.'_end_date'] = $_REQUEST['to_'.$page_id];
            }
            //error_log_shiksha("cms FORM arrays".print_r($updateCMSData,true).".....key page array".print_r($updateKeyPage,true));
            $response = $EnterpriseClientObj->updateCmsItem($appId,$updateCMSData,$updateKeyPage);
	    //echo (print_r($response,true));
	    header ("location:/enterprise/Enterprise/index/".$updateCMSData['selectTabId']);
	    exit;
        }
    }


    //Controller API to Get <Product> detail for a Key_ID from CMS Page-<Product> Table
    function getItems($appId='1',$item_type,$key_id) {
        $appId = 1;

        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        $EnterpriseClientObj = new Enterprise_client();

        if($item_type == '')
        {
            $item_type = $this->uri->segment(5);
        }

        if($key_id == '')
        {
            $key_id = $this->uri->segment(6);
        }

        $key_id = filter_var($key_id, FILTER_VALIDATE_INT);

        if($key_id)
        {
            //          error_log_shiksha("listingUpdate after REQUEST listDETAIL===>".print_r($listDetail, true));
            //			if(isset($_REQUEST['updatePageBlog']))
            {
                $clientUpdItemArr = array("item_type" => $item_type,"key_id" => $key_id);
                $response = $EnterpriseClientObj->getItems($appId,$clientUpdItemArr);
                echo (print_r($response,true));
            }
        }
        else
        {
            echo "Please supply a numeric Listing ID";
        }
    }


    //Controller API to Get Key-Pages for a Product_ID from CMS Page-<Product> Table
    function getKeyPages($appId='1',$item_type,$item_id,$topicId='1') {
        $startTime = microtime(true);
        $appId = 1;

        $cmsUserInfo = $this->cmsUserValidationRedirection();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        $EnterpriseClientObj = new Enterprise_client();

        if($item_type == '')
        {
            $item_type = $this->uri->segment(5);
        }

        if($item_id == '')
        {
            $item_id = $this->uri->segment(6);
        }
/*
        if($topicId == '')
        {
            $topicId = $this->uri->segment(7);
        }
*/
        $item_id = filter_var($item_id, FILTER_VALIDATE_INT);
//       $topicId = filter_var($topicId, FILTER_VALIDATE_INT);

        if($usergroup == "cms"){
            if($item_id)
            {
               /* if($item_type == "msgboard")
                {
                    $clientUpdItemArr = array("item_type" => $item_type,"categoryId" => $item_id,"topicId" => $topicId);
                    $response = $EnterpriseClientObj->getKeyPages($appId,$clientUpdItemArr);
                    echo json_encode($response);

                }
                else*/
                //{
                    $clientUpdItemArr = array("item_type" => $item_type,"item_id" => $item_id);
                    $response = $EnterpriseClientObj->getKeyPages($appId,$clientUpdItemArr);
                    echo json_encode($response);
                //}
            }
            else
            {
                echo "Please supply a numeric ID";
            }
        }else{
            echo"[]";
        }
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }


    function cms_blog_info($blogId,$status) {
        $appId = '1';
	    $this->load->helper(array('blogs/article'));
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        /*
	$this->load->library('upload_client');
        $BlogClientObj = new Blog_client();
        $uplaodClient = new Upload_client();

        $blogData = $BlogClientObj->getBlogInfo($appId,$blogId,'',$status);
        $blogImages = $uplaodClient->getImageInfo($appID,$blogId,"blog");
        $displayData = array();
        $displayData['blogInfo'] = $blogData;
        $displayData['blogImages'] = $blogImages;
        $displayData['validateuser'] = $this->checkUserValidation();
        $this->load->view('blogs/blogDetailPanel',$displayData);
        //echo json_encode($blogData);
	*/

        $this->load->builder('ArticleBuilder','article');
        $this->articleBuilder = new ArticleBuilder;
        $this->articleRepository = $this->articleBuilder->getArticleRepository();

        //load blog object
	    $blogObj = $this->articleRepository->find($blogId);
        if(!is_object($blogObj)) {
             $displayData['blogId']   = $blogId;
             $this->load->model('articlemodel');
             $displayData['blogStatus'] = $this->articlemodel->getBlogStatus($blogId);
        }else 
        {   
             $displayData['blogObj']  = $blogObj;
             $displayData['blogId']   = $blogObj->getId();
             $displayData['blogUserId'] = $blogObj->getCreatorId();
             $displayData['blogStatus'] = $blogObj->getStatus();
             $displayData['blogURL'] = $blogObj->getURL();
         }
	   $displayData['validateuser'] = $this->checkUserValidation();
	   $this->load->view('blogCMSButtons',$displayData);

    }

	function changeRequest_info($requestId) {
		$appId = 1;
		$this->init();
		$EnterpriseClientObj = new Enterprise_client();
		$changeRequest = $EnterpriseClientObj->getReportedChangesById($appId,$requestId);
		$displayData  = $changeRequest[0];
		$displayData['validateuser'] = $this->userStatus;
		$this->load->view('enterprise/changeRequestView',$displayData);
	}

	function blogDetails($blogId) {
		$this->init();
		$this->load->library('upload_client');
        $BlogClientObj = new Blog_client();
        $uplaodClient = new Upload_client();
        $blogData = $BlogClientObj->getBlogInfo($appId,$blogId);
        $blogImages = $uplaodClient->getImageInfo($appID,$blogId,"blog");
        $blog = array();
        $blog['blogInfo'] = $blogData;
        $blog['blogImages'] = $blogImages;
		$displayData['validateuser'] = $this->userStatus;
		$displayData['blog'] = $blog;
		$this->load->view('enterprise/showBlogDetails',$displayData);
	}
	function editBlogDetails($blogId) {
		$this->init();
		$this->load->library('upload_client');
        $BlogClientObj = new Blog_client();
        $uplaodClient = new Upload_client();
        $blogData = $BlogClientObj->getBlogInfo($appId,$blogId);
        $blogImages = $uplaodClient->getImageInfo($appID,$blogId,"blog");
        $blog = array();
        $blog['blogInfo'] = $blogData;
        $blog['blogImages'] = $blogImages;
        $categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId);
         $others = array();
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
        $displayData['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);

		$displayData['validateuser'] = $this->userStatus;
		$displayData['blog'] = $blog;
		$displayData['fromCMS'] = 1;
        $examParents = $BlogClientObj->getExams($appId);//Ashish
        $displayData['examParents'] = $examParents;
		$this->load->view('enterprise/editBlogDetails',$displayData);
	}

    function cms_msgboard_info($topicId) {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

	$appId = 12;
	$topicCountryId = 1;$closeDiscussion = 0;
	$displayData = array();
	$topic_reply = array();
	$main_message = array();
	$msgbrdClient = new Message_board_client();
	$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;

        $ResultOfDetails = $msgbrdClient->getMsgTree($appId,$topicId,0,1024);

        if(isset($ResultOfDetails[0]['MsgTree']))
		$topic_reply = $ResultOfDetails[0]['MsgTree'];


	$rows = 5;
	$displayData['topicId'] = $topicId;

	$param_arr = array(array($topicId,'int'));
	if(is_array($topic_reply) && count($topic_reply) > 0)
	{
		$topic_messages = array();
		$i = -1;
		foreach($topic_reply as $key => $temp)
		{
			if($key == 0)
			{
				if($temp['status'] == 'deleted')
					break;
				else
					continue;
			}
			$found = 0;
			if(substr_count($temp['path'],'.') == 1)
			{

				$i++;
				$topic_messages[$i] = array();
				$temp['userStatus'] = $msgbrdClient->getUserStatus($topic_reply[$i]['lastlogintime']);
				$temp['creationDate'] = $msgbrdClient->makeRelativeTime($temp['creationDate']);
				array_push($topic_messages[$i],$temp);
				$comparison_string = $temp['path'].'.';

			}
			elseif(strstr($temp['path'],$comparison_string))
			{
				$temp['userStatus'] = $msgbrdClient->getUserStatus($topic_reply[$i]['lastlogintime']);
				$temp['creationDate'] = $msgbrdClient->makeRelativeTime($temp['creationDate']);
				array_push($topic_messages[$i],$temp);
			}

		}
	   	if($temp['status'] != 'deleted')
		{
			$displayData['topic_messages'] = $topic_messages;
			$topic_reply[0]['userStatus'] = $msgbrdClient->getUserStatus($topic_reply[0]['lastlogintime']);
			$topic_reply[0]['creationDate'] = $msgbrdClient->makeRelativeTime($topic_reply[0]['creationDate']);
			$main_message = $topic_reply[0];
			if($topic_reply[0]['status'] == 'closed')
				$closeDiscussion = 1;
		}
	}
	$displayData['main_message'] = $main_message;
	$displayData['appId'] = $appId;
	$displayData['topicId'] = $topicId;
	$displayData['closeDiscussion'] = $closeDiscussion;
	$displayData['userId'] = $userId;
	$displayData['validateuser'] = $this->userStatus;
	$this->load->view('messageBoard/topicDetails_centralPanel',$displayData);
}

    function publishCountryAll()
    {
        $appId = 1;
        $this->init();
        error_log(print_r($_POST,true).'POSTARRAY');
        $i = 0;
        $countryid = $this->input->post('countryselector',true);


        foreach($_POST as $key => $institute)
        {
            $priority = split('enterid',$key);
            if(count($priority) > 1)
            {
            $finalarr[$priority[1]] = $institute;
            $i++;
            }
        }
        if($i < 20)
        {
        echo 'Please select all institutes to publish';
        exit;
        }
        error_log(print_r($finalarr,true).'FINALARR');
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->publishAll($appId,$finalarr,$categoryid);
        $response = $listingDetails;
        $response = json_decode($listingDetails,true);
        if(isset($response['error']))
        {
            $res = $response['error'];
        }
        else
        {
            $res = $response['msg'];
        }
        error_log(print_r($response,true).'RESPONSE');
        echo $res;
    }
    function publishAll()
    {
        $appId = 1;
        $this->init();
        error_log(print_r($_POST,true).'POSTARRAY');
        $i = 0;
        $categoryid = $_POST['categoryselector'];
        foreach($_POST as $key => $institute)
        {
            $priority = split('enterid',$key);
            if(count($priority) > 1)
            {
            $finalarr[$priority[1]] = $institute;
            $i++;
            }
        }
        if($i < 20)
        {
        echo 'Please select all institutes to publish';
        exit;
        }
        error_log(print_r($finalarr,true).'FINALARR');
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->publishAll($appId,$finalarr,$categoryid);
        $response = $listingDetails;
        $response = json_decode($listingDetails,true);
        if(isset($response['error']))
        {
            $res = $response['error'];
        }
        else
        {
            $res = $response['msg'];
        }
        error_log(print_r($response,true).'RESPONSE');
        echo $res;
    }
    function saveOption($instituteid,$categoryid,$statusval,$priority)
    {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->saveTopOption($appId,$instituteid,$categoryid,$statusval,$priority);
        $response = $listingDetails;
        $response = json_decode($listingDetails,true);
        $response = array('results' => $response,'totalresults'=>count($response));
        error_log(print_r($response,true).'RESPONSE');
        echo json_encode($response);
    }

    function saveCountryOption($pagename,$category,$subcategory,$country,$city,$itemid,$itemtype,$status,$priority)
    {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $reqarr = array();
                        $reqarr['pagename']=$pagename;
                        $reqarr['category']=$category;
                        $reqarr['subcategory']=$subcategory;
                        $reqarr['country']=$country;
                        $reqarr['city']=$city;
                        $reqarr['itemid']=$itemid;
                        $reqarr['itemtype']=$itemtype;
                        $reqarr['status']=$status;
                        $reqarr['priority']=$priority;
        $listingDetails = $ListingClientObj->saveCountryOption($appId,$reqarr);
        $response = $listingDetails;
        $response = json_decode($listingDetails,true);
        $response = array('results' => $response,'totalresults'=>count($response));
        error_log(print_r($response,true).'RESPONSE');
        echo json_encode($response);
    }
    function getCmsCountryOptions($countryid)
    {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getCmsCountryOptions($appId,$countryid);
        $response = $listingDetails;
        $response = json_decode($listingDetails,true);
        $response = array('results' => $response,'totalresults'=>count($response));
        error_log(print_r($response,true).'RESPONSE');
        echo json_encode($response);
    }

    function getCmsTopInstitutes($categoryid)
    {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getCmsTopInstitutes($appId,$categoryid);
        $response = $listingDetails;
        $response = json_decode($listingDetails,true);
        $response = array('results' => $response,'totalresults'=>count($response));
        error_log(print_r($response,true).'RESPONSE');
        echo json_encode($response);
    }

    function getMetaInfoForInstitutes($instituteids,$categoryid)
    {
        $appId = 1;
        $this->init();
        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getMetaInfoForInstitutes($appId,$instituteids,$categoryid);
        $response = $listingDetails;
       // $response = json_decode($listingDetails,true);
        error_log(print_r($response,true).'RESPONSE');
        echo $response;
    }

    function cmsAdmissionInfo($admitId) {
        $appId = '1';

        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        $ListingClientObj = new Listing_client();

        $listingDetails = $ListingClientObj->getListingDetails($appId,$admitId,"notification");
        $displayData = array();
        $displayData['type_id'] = $admitId;
        $displayData['listing_type'] = "notification";
        if( ($usergroup == "cms") ||
        (($listingDetails[0]['userId'] == $userid) AND
        ($usergroup == "enterprise")) )
        {
            $displayData['cmsData'] = 1;
        }else{
            $displayData['cmsData'] = '';
        }
        $displayData['cmsAjaxFetch'] = 1;
        $displayData['details'] = $listingDetails[0];
        $displayData['validateuser'] = $this->userStatus;

        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);

        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId'],$temp['urlName']);
        }
        //echo "<pre>";print_r($displayData);echo "</pre>";
        for ($i=0;$i<count($displayData['details']['categoryArr']);$i++)
        {
            $catid= $displayData['details']['categoryArr'][$i]['category_id'];
            $displayData['details']['categoryArr'][$i]['cat_name']=$categoryForLeftPanel[$catid][0];
            $displayData['details']['categoryArr'][$i]['cat_url']=$categoryForLeftPanel[$catid][2];
            $parent = $categoryForLeftPanel[$catid][1];
            $displayData['details']['categoryArr'][$i]['parent_cat_name']=$categoryForLeftPanel[$parent][0];
            $displayData['details']['categoryArr'][$i]['parent_url']=$categoryForLeftPanel[$parent][2];
            $displayData['details']['categoryArr'][$i]['parent_cat_id'] = $parent;
        }

        $this->load->view('enterprise/cmsListingDetails',$displayData);
    }


    function cmsScholarshipInfo($scholId) {
        $appId = '1';

        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        $ListingClientObj = new Listing_client();

        $listingDetails = $ListingClientObj->getListingDetails($appId,$scholId,"scholarship");
        $displayData = array();
        $displayData['type_id'] = $scholId;
        $displayData['listing_type'] = "scholarship";
        if( ($usergroup == "cms") ||
        (($listingDetails[0]['userId'] == $userid) AND
        ($usergroup == "enterprise")) )
        {
            $displayData['cmsData'] = 1;
        }else{
            $displayData['cmsData'] = '';
        }
        $displayData['cmsAjaxFetch'] = 1;
        $displayData['details'] = $listingDetails[0];
        $displayData['validateuser'] = $this->userStatus;

        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);

        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId'],$temp['urlName']);
        }
        //echo "<pre>";print_r($displayData);echo "</pre>";
        for ($i=0;$i<count($displayData['details']['categoryArr']);$i++)
        {
            $catid= $displayData['details']['categoryArr'][$i]['category_id'];
            $displayData['details']['categoryArr'][$i]['cat_name']=$categoryForLeftPanel[$catid][0];
            $displayData['details']['categoryArr'][$i]['cat_url']=$categoryForLeftPanel[$catid][2];
            $parent = $categoryForLeftPanel[$catid][1];
            $displayData['details']['categoryArr'][$i]['parent_cat_name']=$categoryForLeftPanel[$parent][0];
            $displayData['details']['categoryArr'][$i]['parent_url']=$categoryForLeftPanel[$parent][2];
            $displayData['details']['categoryArr'][$i]['parent_cat_id'] = $parent;
        }

        $this->load->view('enterprise/cmsListingDetails',$displayData);
    }


    function cms_event_info($eventId) {
        $appId = '1';
	$this->load->library('category_list_client');
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();
	$categoryClient = new Category_list_client();
        $EventCalClientObj = new Event_cal_client();
        $displayData = $EventCalClientObj->getEventDetail($appId, $eventId);
	$categoryList = $categoryClient->getCategoryTree($appID);
                $others = array();
                $categoryForLeftPanel = array();
                foreach($categoryList as $temp) {
                        if((stristr($temp['categoryName'],'Others') == false)) {
                                $categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
                        } else {
                                $others[$temp['categoryID']] =  array($temp['categoryName'],$temp['parentId']);
                        }
                }
                foreach($others as $key => $temp)  {
                        $categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
                }
        $displayData['country_list'] = $countryList;
        $displayData['categoryForLeftPanel'] = $categoryForLeftPanel;
        $Validate = $this->checkUserValidation();
        if( ($usergroup == "cms") ||
        (($displayData['user_id'] == $userid) AND
        ($usergroup == "enterprise")) )
        {
            $displayData['cmsData'] = 1;
        }else{
            $displayData['cmsData'] = '';
        }
        $displayData['validateuser'] = $Validate;

        $this->load->view('events/eventDetailPanel',$displayData);
    }


    function cmsCourseInfo($courseId, $status = "live") {
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidationRedirection();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        $ListingClientObj = new Listing_client();
        if($status == 'live'){
            $listingDetails = $ListingClientObj->getListingDetails($appId,$courseId,"course");
        }
        else{
            $listingDetails = $ListingClientObj->getListingForEdit($appId,$courseId,"course");
        }
        $displayData = array();
        $displayData['type_id'] = $courseId;
        $displayData['listing_type'] = "course";
        if( ($usergroup == "cms") ||
        (($listingDetails[0]['userId'] == $userid) AND
        ($usergroup == "enterprise")) )
        {
            $displayData['cmsData'] = 1;
        }else{
            $displayData['cmsData'] = '';
        }
        $displayData['cmsAjaxFetch'] = 1;
        $displayData['details'] = $listingDetails[0];
        $displayData['validateuser'] = $this->userStatus;
	$displayData['status'] = $status;
        $this->load->view('enterprise/fetchDetailButtons',$displayData);
    }

    function cmsCollegeInfo($collegeId,$status="live") {
        $startTime = microtime(true);
        $appId = '1';

        // $cmsUserInfo = $this->cmsUserValidation();
        $cmsUserInfo = $this->cmsUserValidationRedirection();
        
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        // $ListingClientObj = new Listing_client();

        //if($status == 'live'){
        //    $listingDetails = $ListingClientObj->getListingDetails($appId,$collegeId,"institute");
        //}
        //else{
        //    $listingDetails = $ListingClientObj->getListingForEdit($appId,$collegeId,"institute");
        //}
		
		
		$this->load->library('cacheLib');
		$cacheLib = new CacheLib;
		$this->load->model('listing/posting/institutedetailsmodel');
		$instituteDetailsModel = new InstituteDetailsModel($cacheLib);
		$listingDetails = $instituteDetailsModel->getInstituteDetails($collegeId);
		
		//_p($listingDetails);
		
        $displayData = array();
        $displayData['type_id'] = $collegeId;
        $displayData['listing_type'] = "institute";
        $displayData['cmsAjaxFetch'] = 1;
        $displayData['details'] = $listingDetails[0];

        $displayData['validateuser'] = $this->userStatus;
        if( ($usergroup == "cms") ||
        (($listingDetails[0]['userId'] == $userid) AND
        ($usergroup == "enterprise")) )
        {
            $displayData['cmsData'] = 1;
        }else{
            $displayData['cmsData'] = '';
        }

		$displayData['status'] = $status;
		$this->load->view('enterprise/fetchDetailButtons',$displayData);
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    function cmsProdList($prodId,$startFrom=0,$countOffset=7) {
       $startTime = microtime(true);
        $appId = '1';

        $cmsUserInfo = $this->cmsUserValidationRedirection();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $this->init();

        switch($prodId){
            case '1':
            /*$srchTitle = $this->input->post('titleName');
            $filter1 = $this->input->post('blogCountry');
            */
            	
            $searchType = isset($_POST['searchType'])?$this->input->post('searchType'):'';
            $searchText = isset($_POST['searchText'])?$this->input->post('searchText'):'';
		    $articleType = isset($_POST['articleType'])?$this->input->post('articleType'):'';
			$postedBy = isset($_POST['postedBy'])?$this->input->post('postedBy'):'';
			$orderType = ($_POST['orderType'])?$this->input->post('orderType'):'DESC';
			$articleStatus = isset($_POST['articleStatus'])?$this->input->post('articleStatus'):'all';
            
            $BlogClientObj = new Blog_client();
            //$popularBlogs = $BlogClientObj->getPopularBlogs($appId,$CategoryId,$start,$rows,$countryId);
            $popularBlogs = $BlogClientObj->getPopularBlogsForCMS($appId,1,$startFrom,$countOffset,1,$searchType,$searchText,$postedBy,$orderType,$userid,$articleType,$articleStatus);
            echo json_encode($popularBlogs);
            break;
            case '2':
            $EnterpriseClientObj = new Enterprise_client();
            $srchTitle = $this->input->post('titleName');
            $srchAuthor = $this->input->post('authorName');
            $filter1 = $this->input->post('forumCountry');
            $showAbuseOnly = $this->input->post('abuseOnly');
            //FIXME
            $Result = $EnterpriseClientObj->getPopularTopicsCMS($appId,1,$startFrom,$countOffset,$srchTitle,$srchAuthor,$filter1,$showAbuseOnly);
            $popularTopics = $Result[0]['results'];
            $count = $Result[0]['totalCount'];
            $msgBoardCMSarr = array();
            $msgBoardCMSarr['count_arr'] = $count;
            $msgBoardCMSarr['popularTopics'] = $popularTopics;
            echo json_encode($msgBoardCMSarr);
            break;
            case '3':
            $srchAdmitName = $this->input->post('admissionName');
            $srchYear = $this->input->post('year');
            $filter1 = $this->input->post('moderation');
            $filter2 = $this->input->post('crawlStatus');
            $filter3 = $this->input->post('liveStatus');
            $showAbuseOnly = $this->input->post('abuseOnly');
            if ($showAbuseOnly=="true")
            $filter4 = "0";

            $ListingClientObj = new Listing_client();
            $popularAdmission = $ListingClientObj->getListingsList($appId,"notification",$startFrom,$countOffset,$srchAdmitName,$srchYear,'',$filter1,$filter2,$filter3,$filter4,$usergroup,$userid);
            echo json_encode($popularAdmission);
            break;
            case '4':
            $srchScholName = $this->input->post('scholName');
            $srchInstitution = $this->input->post('institution');
            $filter1 = $this->input->post('moderation');
            $filter2 = $this->input->post('crawlStatus');
            $filter3 = $this->input->post('liveStatus');
            $showAbuseOnly = $this->input->post('abuseOnly');
            if ($showAbuseOnly=="true")
            $filter4 = "0";

            $ListingClientObj = new Listing_client();
            $popularScholarship = $ListingClientObj->getListingsList($appId,"scholarship",$startFrom,$countOffset,$srchScholName,$srchInstitution,'',$filter1,$filter2,$filter3,$filter4,$usergroup,$userid);
            echo json_encode($popularScholarship);
            break;
            case '5':
            $srchEvent = $this->input->post('eventName');
            $srchVenue = $this->input->post('eventVenue');
            $filter1 = $this->input->post('eventType');
            $filter2 = $this->input->post('eventCountry');
            $showAbuseOnly = $this->input->post('abuseOnly');

            $EventCalClientObj = new Event_cal_client();
            $recentEventsList = $EventCalClientObj->getRecentEventCMS($appId,$startFrom,$countOffset,1,$srchEvent,$srchVenue,$filter1,$filter2,$showAbuseOnly,$usergroup,$userid);
            echo json_encode($recentEventsList);
            break;
            case '6':
		$srchCourse = $this->input->post('course');
		$srchCollege = $this->input->post('college');
		$srchCity = $this->input->post('city');
		
		$moderation = $this->input->post('moderation');
		if($moderation == '1') {
		    $moderation = 'moderated';
		}
		else if($moderation == '2') {
		    $moderation = 'unmoderated';
		}
		
		$crawlStatus = $this->input->post('crawlStatus');
		if($crawlStatus == '1') {
		    $crawlStatus = 'crawled';
		}
		else if($crawlStatus == '2') {
		    $crawlStatus = 'noncrawled';
		}
		
		$status = $this->input->post('liveStatus');
		$showAbuseOnly = $this->input->post('abuseOnly');
        $direction = $this->input->post('direction');
		
		//$ListingClientObj = new Listing_client();
		//$popularCourse = $ListingClientObj->getListingsList($appId,"course",$startFrom,$countOffset,$srchCourse,$srchCollege,$srchCity,$filter1,$filter2,$filter3,$filter4,$usergroup,$userid);
		
		$this->load->model('listing/coursefindermodel');
		// $popularCourse = $this->coursefindermodel->getCoursesForEnterpriseListing($status,$moderation,$crawlStatus,$showAbuseOnly,$srchCourse,$srchCollege,$usergroup,$userid,$startFrom,$countOffset,$direction);
		
		echo json_encode($popularCourse);
		break;
            case '7':
	    
		$moderation = $this->input->post('moderation');
		if($moderation == '1') {
		    $moderation = 'moderated';
		}
		else if($moderation == '2') {
		    $moderation = 'unmoderated';
		}
		
		$crawlStatus = $this->input->post('crawlStatus');
		if($crawlStatus == '1') {
		    $crawlStatus = 'crawled';
		}
		else if($crawlStatus == '2') {
		    $crawlStatus = 'noncrawled';
		}
		
		$status = $this->input->post('liveStatus');
		$instituteType = $this->input->post('instituteType');
		$showAbuseOnly = $this->input->post('abuseOnly');
		    
		$this->load->model('listing/institutefindermodel');
		// $popularColleges = $this->institutefindermodel->getInstitutesForEnterpriseListing($status,$instituteType,$moderation,$crawlStatus,$showAbuseOnly,$usergroup,$userid,$startFrom,$countOffset);
		    
		//$popularColleges = $ListingClientObj->getListingsList($appId,"institute",$startFrom,$countOffset,$srchCollege,$srchCity,$srchCountry,$filter1,$filter2,$filter3,$filter4,$usergroup,$userid,$filter5);
		
		echo json_encode($popularColleges);
		break;
        }
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

	function getChangeRequests($listing_type='blogs',$startFrom=0,$countOffset=7) {
		$this->init();
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
	    $EnterpriseClientObj = new Enterprise_client();
	    $listing_type_id  = '';
	    error_log_shiksha("($appId,$listing_type, $listing_type_id)");
        $changeRequests = $EnterpriseClientObj->getReportedChangesForBlogs($appId,$listing_type, $listing_type_id);
        echo json_encode($changeRequests);
	}

    function addCourseCMS($prodId)
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $onBehalfOf = $this->input->post('onBehalfOf');
        if ($onBehalfOf=="true")
        {
        	$userid = $this->input->post('selectedUserId',true);
        	$this->load->library('register_client');
        	$regObj = new Register_client();
        	$arr = $regObj->userdetail($this->appId,$userid);
        	$displayData['userDetails'] = $arr[0];
        }

        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $displayData['userid'] = $userid;
        $displayData['usergroup'] = $usergroup;

        if(($usergroup != "cms") || ($onBehalfOf=="true") ){
            $displayData['userProducts'] = $this->paymentCheck($userid);
        }
        $displayData['thisUrl'] = $thisUrl;
        $displayData['validateuser'] = $validity;
        $ListingClientObj = new Listing_client();
        $countryList = $ListingClientObj->getCountries($appId);
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $displayData['completeCategoryTree'] = json_encode($categoryForLeftPanel);

        $displayData['country_list'] = $countryList;
        $displayData['listingType'] = "course";
        $displayData['prodId'] = $this->productAccessCheck("Courses"); //Name: as is shown in the Enterprise-Tabs
        $displayData['dataFromCMS'] = 1;

	$displayData['headerTabs'] =  $cmsUserInfo['headerTabs'];
	$displayData['myProducts'] = $cmsUserInfo['myProducts'];
        $displayData['onBehalfOf'] = $onBehalfOf;
	//user purchased product info
        $this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $displayData['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));
        //$displayData['productInfoDEV'] = $objSumsProduct->getProductFeatures(1,$params);
        //echo '<pre>';print_r($displayData);echo '</pre>';
        $this->load->library('blog_client');
        $blogClient = new Blog_client();
        $examsList = $blogClient->getExamsForProducts($appId);
        $displayData['examsList'] = $examsList;
        $this->load->view('enterprise/course_listing_cms',$displayData);
    }

    function cmsEditCourse($courseId) {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $upCourData = array();
        if($usergroup == "enterprise"){
            //$paymentInfo = $this->paymentCheck();
            //$upCourData['productDataList'] = $paymentInfo;
        }

        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$courseId,"course");
        if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
        }

        $userid = $listingDetails[0]['userId'];
        $upCourData['userid'] = $userid;
        $upCourData['usergroup'] = $usergroup;
        $upCourData['thisUrl'] = $thisUrl;
        $upCourData['validateuser'] = $validity;


        $ListingClientObj = new Listing_client();
        $courseData = $ListingClientObj->getListingDetails($appId,$courseId,"course");
        //echo '<pre>';print_r($courseData);echo '</pre>';

        foreach($courseData['0'] as $key => $val)
        {
            $upCourData[$key] = $val;
        }

        $countryList = $ListingClientObj->getCountries($appId);
        $upCourData['country_list'] = $countryList;
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $upCourData['completeCategoryTree'] = json_encode($categoryForLeftPanel);
        $upCourData['dataFromCMS'] = 1;
        //$upCourData['prodId'] = 6;
        $upCourData['prodId'] = $this->productAccessCheck("Courses"); //Name: as is shown in the Enterprise-Tabs
	$upCourData['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $upCourData['myProducts'] = $cmsUserInfo['myProducts'];
        $upCourData['listingType'] = "course";

        $this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $upCourData['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));
        //$upCourData['productInfoDEV'] = $objSumsProduct->getProductFeatures(1,$params);

        $this->load->library('blog_client');
        $blogClient = new Blog_client();
        $examsList = $blogClient->getExamsForProducts($appId);
        $upCourData['examsList'] = $examsList;
        //echo '<pre>';print_r($upCourData);echo '</pre>';
        $this->load->view('enterprise/editCourContainer',$upCourData);
    }

    function cmsEditInstitute($instituteId) {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $upCourData = array();
        if($usergroup == "enterprise"){
            //$paymentInfo = $this->paymentCheck();
            //$upCourData['productDataList'] = $paymentInfo;
        }

        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$instituteId,"institute");
        if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
        }

        $userid = $listingDetails[0]['userId'];
        $upCourData['userid'] = $userid;
        $upCourData['usergroup'] = $usergroup;
        $upCourData['thisUrl'] = $thisUrl;
        $upCourData['validateuser'] = $validity;


        $ListingClientObj = new Listing_client();
        $instituteData = $ListingClientObj->getListingDetails($appId,$instituteId,"institute");
        //echo '<pre>';print_r($instituteData);echo '</pre>';
        //error_log_shiksha("===SS===".print_r($courseData,true));

        foreach($instituteData['0'] as $key => $val)
        {
            $upCourData[$key] = $val;
        }

        $countryList = $ListingClientObj->getCountries($appId);
        $upCourData['country_list'] = $countryList;

	$cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $upCourData['completeCategoryTree'] = json_encode($categoryForLeftPanel);

        $upCourData['dataFromCMS'] = 1;
        //$upCourData['prodId'] = 6;
        $upCourData['prodId'] = $this->productAccessCheck("Colleges"); //Name: as is shown in the Enterprise-Tabs
        $upCourData['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $upCourData['myProducts'] = $cmsUserInfo['myProducts'];
        $upCourData['listingType'] = "institute";

	$courseCount = $ListingClientObj->editCategFormOpen($appId,$instituteId);
	$courCnt = $courseCount['courseCount'];
	if($courCnt >0){
		$formOpen = 'No';
	}else{
		$formOpen='Yes';
	}

	$upCourData['editCategFormOpen']=$formOpen;
        $this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $upCourData['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));
        //$upCourData['productInfoDEV'] = $objSumsProduct->getProductFeatures(1,$params);

        //echo '<pre>';print_r($upCourData);echo '</pre>';
        $this->load->view('enterprise/editCourContainer',$upCourData);
    }

    //mapped to function addCollegeCourseCMS() of Listing.php
    function updateCollegeCMS()
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        //echo '<pre>',print_r($_REQUEST);echo '</pre>';
        //  error_log_shiksha("controller's cms EDIT_FILE form data".print_r($_FILES,true));
        //exit;
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $flagMedia = 1;
        $this->load->library('upload_client');
        $EnterpriseClientObj = new Enterprise_client();
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();

        if ($usergroup == "cms") {
            $userPack = 0;
            $maxPhotos = 3;
            $maxVideos = 3;
            $maxDocs = 3;
            $featuredLogo = "YES";
            $featuredPanel = "YES";
        }

        $addType = $this->input->post('add_type',true);
        //$bypass = $this->input->post('bypass',true);

        $instituteData['old_institute_id']= $this->input->post('old_institute_id');

        $countries = $this->input->post('i_country_id');
        $cities = $this->input->post('i_city_id');
        $address = $this->input->post('address');
        $count = count($countries);
        for($i=0;$i<$count;$i++)
        {
            $instituteData['country_id'.$i]= $countries[$i];
            if ($cities[$i]==-1){
                $requestArray = array();
                $requestArray['country_id'] = $countries[$i];
                $requestArray['city_name'] = $this->input->post('cities'.($i+1).'_other');
                $cities[$i] = $ListingClientObj->insertCity($appId,$requestArray);                
            }
            $instituteData['city_id'.$i] = $cities[$i];
            $instituteData['address'.$i] = $address[$i];
        }
        $instituteData['numoflocations'] = $count;
        $instituteData['institute_name'] = $this->input->post('c_institute_name',true);
        $instituteData['institute_desc'] = $this->input->post('c_institute_desc',true);
        $instituteData['affiliated_to'] = $this->input->post('affiliated_to',true);
        $instituteData['establish_year'] = $this->input->post('i_establish_year');
        $instituteData['no_of_students'] = $this->input->post('i_no_of_students',true);
        $instituteData['no_of_int_students'] = $this->input->post('i_no_of_i_students',true);
        if ($usergroup == "cms") {
            $instituteData['hiddenTags'] = $this->input->post('i_tags',true);
        }

        $instituteData['contact_name'] = $this->input->post('c_cordinator_name',true);
        $instituteData['contact_cell'] = $this->input->post('c_cordinator_no',true);
        $instituteData['contact_email'] = $this->input->post('c_cordinator_email',true);
        $instituteData['url'] = $this->input->post('c_website',true);
        $instituteData['updateCategories'] = $this->input->post('updateCategories',true);
        $instituteData['category_id'] = implode(',',$this->input->post('c_categories',true));
        //print_r($this->input->post('c_categories',true));

        $j = 0;
        for($i=0;$i< count($_REQUEST['i_country_id']);$i++)
        {
            $instituteData['location'][$j] = $_REQUEST['i_country_id'][$i];
            $instituteData['location'][$j+1] = $_REQUEST['i_city_id'][$i];
            $j +=2;
        }
        $instituteData['country_id'] =implode(',',$countries);
        $instituteData['city_id'] =implode(',',$cities);

        /*	for($i = 0 ; $i < count($_REQUEST['i_country_id']); $i++)
        {
            $instituteData['country_id'.$i] = $_REQUEST['i_country_id'][$i];
            $instituteData['city_id'.$i] = $_REQUEST['i_city_id'][$i];
            $instituteData['address'.$i] = $_REQUEST['address'][$i];

        }
        $instituteData['numoflocations'] = $i;*/
        //$instituteData['username'] = $this->userStatus[0]['userid'];
        $instituteData['islisting']=true;
        //if ($addType == "new" || $this->input->post('bypass'))

        if ($addType == "new")
        {
            $instituteData['dataFromCMS'] = "dataFromCMS";
            $response = $EnterpriseClientObj->updateOldInstitute($appId,$instituteData);
            //  print_r($response);

        }
        /*        else if ($addType == "existing")
        {
            $instituteData['existing_institute_id'] = $this->input->post('existing_college');
            $response = $EnterpriseClientObj->updateAssignNewInstitute($appId,$instituteData['update_course_id'],$instituteData['existing_institute_id']);
            //  print_r($response);
        }
        */
        /* Media Content.. here Insti Logo for Update */

        if ($addType == "new" || $this->input->post('bypass')) {
            $arrCaption = $this->input->post('c_insti_logo_caption');
            $inst_logo= array();
            for($i=0;$i<count($_FILES['i_insti_logo']['name']);$i++){
                $inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_insti_logo']['name'][$i];
            }

            //error_log_shiksha("FILES DATA: ".print_r($_FILES,true));
            if(isset($_FILES['i_insti_logo']['tmp_name'][0]) && ($_FILES['i_insti_logo']['tmp_name'][0] != ''))
            {
                error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload logo called");
                $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,$response['institute_id'],"institute",'i_insti_logo');
                error_log_shiksha("ADD COURSE_COLLEGE LISTING : response".print_r($i_upload_logo,true));
                if($i_upload_logo['status'] == 1)
                {
                    //error_log_shiksha("in upload success".print_r($i_upload_logo,true));
                    for($k = 0;$k < $i_upload_logo['max'] ; $k++)
                    {
                        $reqArr = array();
                        $reqArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                        $reqArr['url']=$i_upload_logo[$k]['imageurl'];
                        $reqArr['title']=$i_upload_logo[$k]['title'];
                        $reqArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];

                        $updateInstituteData = array();
                        $updateInstituteData['institute_id'] = $response['institute_id'];
                        $updateInstituteData['logo_link'] = $i_upload_logo[0]['thumburl_m'];
                        $updateInstituteData['logo_name'] = $i_upload_logo[0]['title'];
                        error_log_shiksha("ADD COURSE_COLLEGE LISTING : update institute for logo REQUEST".print_r($updateInstituteData,true));
                        $status = $ListingClientObj->update_institute($appId,$updateInstituteData);
                        error_log_shiksha("ADD COURSE_COLLEGE LISTING : update institute RESPONSE".print_r($status,true));
                    }
                }
            }


            $arrCaption = $this->input->post('c_feat_panel_caption');
            $inst_logo= array();
            for($i=0;$i<count($_FILES['i_feat_panel']['name']);$i++){
                $inst_logo[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['i_feat_panel']['name'][$i];
            }
            if(isset($_FILES['i_feat_panel']['tmp_name'][0]) && ($_FILES['i_feat_panel']['tmp_name'][0] != ''))
            {
                error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload featured panel called");
                $i_upload_logo = $uploadClient->uploadFile($appId,'image',$_FILES,$inst_logo,$response['institute_id'],"featured",'i_feat_panel');
                error_log_shiksha("ADD COURSE_COLLEGE LISTING : response upload featured panel ".print_r($i_upload_logo,true));
                if($i_upload_logo['status'] == 1)
                {
                    //error_log_shiksha("in upload success".print_r($i_upload_logo,true));
                    for($k = 0;$k < $i_upload_logo['max'] ; $k++)
                    {
                        $reqArr = array();
                        $reqArr['mediaid']=$i_upload_logo[$k]['mediaid'];
                        $reqArr['url']=$i_upload_logo[$k]['imageurl'];
                        $reqArr['title']=$i_upload_logo[$k]['title'];
                        $reqArr['thumburl']=$i_upload_logo[$k]['thumburl_m'];

                        $updateInstituteData = array();
                        $updateInstituteData['institute_id'] = $response['institute_id'];
                        $updateInstituteData['featured_panel'] = $i_upload_logo[0]['thumburl_m'];
                        $updateInstituteData['featured_panel_name'] = $i_upload_logo[0]['title'];
                        error_log_shiksha("ADD COURSE_COLLEGE LISTING : update institute for featured logo REQUEST".print_r($updateInstituteData,true));
                        $status = $ListingClientObj->update_institute($appId,$updateInstituteData);
                        error_log_shiksha("ADD COURSE_COLLEGE LISTING : update institute RESPONSE".print_r($status,true));
                    }
                }
            }

        }



        //$media_content = $this->input->post('c_media_content');

        if (($addType =="new" || $this->input->post('bypass'))){
            error_log_shiksha("ADD COURSE_COLLEGE LISTING : uploading media for institute ".$reponse['institute_id']);
            if($maxPhotos >0)
            {
                $courImgFlag = false;
                for($i = 0;$i < $maxPhotos ; $i++){
                    if(isset($_FILES['c_images']['tmp_name'][$i]) && ($_FILES['c_images']['tmp_name'][$i] != '')){
                        $courImgFlag = true;
                        break;
                    }else{
                        $courImgFlag = false;
                    }
                }

                if($courImgFlag)
                {
                    $arrCaption = $this->input->post('c_images_caption');
                    $photoCaption = array();
                    for($i=0;$i<count($_FILES['c_images']['name']);$i++){
                        $photoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_images']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'image',$_FILES,$photoCaption,$response['institute_id'], "institute",'c_images');
                    //              print_r($upload_forms);
                    error_log_shiksha("ADD LISTING : upload response for images : ".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl_m'];
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent called REQUEST ".$reqArr);
                            $updateCoursePhotos = $ListingClientObj->updateMediaContent($appId,$response['institute_id'],'institute','photos',$reqArr);
                            //                    print_r($updateCoursePhotos);
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING: updateMediaContent RESPONSE ".print_r($updateCoursePhotos,true));
                        }
                        $numOfPhotos = $k;
                    }
                }
            }

            if($maxVideos >0)
            {
                $courVidFlag = false;
                for($i = 0;$i < $maxVideos; $i++){
                    if(isset($_FILES['c_videos']['tmp_name'][$i]) && ($_FILES['c_videos']['tmp_name'][$i] != '')){
                        $courVidFlag = true;
                        break;
                    }else{
                        $courVidFlag = false;
                    }
                }

                if($courVidFlag)
                {
                    $arrCaption = $this->input->post('c_videos_caption');
                    $videoCaption = array();
                    for($i=0;$i<count($_FILES['c_videos']['name']);$i++){
                        $videoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_videos']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'video',$_FILES,$videoCaption,$response['institute_id'], "institute",'c_videos');

                    error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload response for videos : ".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent called REQUEST ".$reqArr);
                            $updateCourseVideos = $ListingClientObj->updateMediaContent($appId,$response['institute_id'],'institute','videos',$reqArr);
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING: updateMediaContent RESPONSE ".print_r($updateCourseVideos,true));
                        }
                        $numOfVideos = $k;
                    }
                }
            }

            if($maxDocs >0)
            {
                $courDocFlag = false;
                for($i = 0;$i < $maxDocs; $i++){
                    if(isset($_FILES['c_docs']['tmp_name'][$i]) && ($_FILES['c_docs']['tmp_name'][$i] != '')){
                        $courDocFlag= true;
                        break;
                    }else{
                        $courDocFlag= false;
                    }
                }
                if($courDocFlag)
                {
                    $arrCaption = $this->input->post('c_docs_caption');
                    $docCaption = array();
                    for($i=0;$i<count($_FILES['c_docs']['name']);$i++){
                        $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_docs']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['institute_id'], "institute",'c_docs');
                    error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload response for docs : ".print_r($upload_forms,true));

                    if($upload_forms['status'] == 1){
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent called REQUEST ".$reqArr);
                            $updateCourseDocs = $ListingClientObj->updateMediaContent($appId,$response['institute_id'],'institute','doc',$reqArr);
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent RESPONSE ".print_r($updateCourseDocs,true));
                        }
                        $numOfDocs = $k;
                    }
                }
            }
        }


        $response['title'] = $instituteData['institute_name'];

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$courseData['institute_id']."/institute");

        curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
        curl_setopt($ch, CURLOPT_POST, 1);
        $result = curl_exec($ch); // run the whole process
        curl_close($ch);


        $displayData['response'] = $response;
        $displayData['fromEditCMS'] = 1;
        $displayData['type'] = "institute";
        $displayData['validateuser'] = $this->userStatus;
        $displayData['myProducts'] = $cmsUserInfo['myProducts'];
        $dataFromCMS = "dataFromCMS";
        $prodId = $this->input->post('prodId',true);
        if($dataFromCMS != "dataFromCMS"){
            $this->load->view('listing/resultPage',$displayData);
        }else{
            /*
            $displayData['prodId'] = $prodId;
            if($courseData['contact_email'] != ''){
                $mail_client = new Alerts_client();
                $mailContent =  'You have successfully added following Course Listing:<br/> Title :'.$courseData['courseTitle'].'  <br/>';
                $sendMailRes = $mail_client->externalQueueAdd($appId,ADMIN_EMAIL,$courseData['contact_email'],'Your CMS Course Listing Added Successfully',$mailContent);
                error_log_shiksha("SEEEEEEEEEEEEEEEEEEEEEDMAAAAAAAAAAAAAAAAAAAIL".print_r($sendMailRes,true));
            }
            */
            $this->load->view('enterprise/updateResultPageCMS',$displayData);
        }
    }

    function updateCourseCMS()
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        //echo '<pre>',print_r($_REQUEST);echo '</pre>';
        //  error_log_shiksha("controller's cms EDIT_FILE form data".print_r($_FILES,true));
        //exit;
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $flagMedia = 1;
        $this->load->library('upload_client');
        $EnterpriseClientObj = new Enterprise_client();
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();

        if ($usergroup == "cms") {
            $userPack = 0;
            $maxPhotos = 3;
            $maxVideos = 3;
            $maxDocs = 3;
            $featuredLogo = "YES";
            $featuredPanel = "YES";
        }

            $courseData['update_course_id']= $this->input->post('update_course_id');
            $courseData['old_institute_id']= $this->input->post('old_institute_id');
            $courseData['courseTitle'] = $this->input->post('c_course_title',true);
            $courseData['courseType'] = $this->input->post('course_type',true);

            $courseData['courseLevel'] = $this->input->post('course_level',true);
            if ($courseData['courseLevel']=="Other") $courseData['courseLevel'] = $this->input->post('other_course_level',true);
            $courseData['overview'] = $this->input->post('c_overview',true);
            $duration1 = $this->input->post('c_duration1',true);
            $duration2 = $this->input->post('c_duration2',true);
            $courseData['duration'] = $duration1." ".$duration2;
            if ($usergroup == "cms") {
                $courseData['hiddenTags'] = $this->input->post('c_course_tags',true);
            }
            $courseData['startDate'] = $this->input->post('course_start_date',true);
            $courseData['endDate'] = $this->input->post('course_end_date',true);

            $courseData['contents'] = $this->input->post('c_contents',true);
            $courseData['category_id'] = implode(',',$this->input->post('c_categories',true));
            $courseData['eligibility'] = "";
            if ( $this->input->post('eligibility1')){
                $Data['minqual'] = $this->input->post('eligibility1',true);
                $courseData['eligibility'] .= " Min Qual:".$this->input->post('eligibility1',true);
            }
            if ( $this->input->post('c_elig_marks')){
                $Data['marks'] = $this->input->post('c_elig_marks',true);
                $courseData['eligibility'] .= " Marks:".$this->input->post('c_elig_marks',true);
            }
            if ( $this->input->post('eligibility3')){
                $Data['minexp'] = $this->input->post('eligibility3',true);
                $courseData['eligibility'] .= " Min Exp.:".$this->input->post('eligibility3',true);
            }
            if ( $this->input->post('eligibility4')){
                $Data['maxexp'] = $this->input->post('eligibility4',true);
                $courseData['eligibility'] .= " Max Exp.:".$this->input->post('eligibility4',true);
            }
            if ($this->input->post('chk_elig_others')) {
                $Data['others'] = $this->input->post('other_elig',true);
                $courseData['eligibility'] .= " ".$this->input->post('other_elig',true);
            }
            //$courseData['eligibility'] = array($Data,'struct');
            //$courseData['eligibility'] = implode(',',$Data);
            $courseData['contact_name'] = $this->input->post('c_cordinator_name',true);
            $courseData['contact_cell'] = $this->input->post('c_cordinator_no',true);
            $courseData['contact_email'] = $this->input->post('c_cordinator_email',true);
            $courseData['url'] = $this->input->post('c_website',true);

            //TEST REQUIRED FOR COURSE
            $tests_required = $this->input->post('c_test',true);
            $testsArray = array();
            $j=0;
            $flag_test_required_other = 0;
            for ($i=0;$i<count($tests_required);$i++)
            {
                if ($tests_required[$i]!="-1")
                {
                    $testsArray[$j] = $tests_required[$i];
                    $j++;
                }
                else
                {
                    $flag_test_required_other = 1;
                }
            }
            $courseData['tests_required'] = implode(",",$testsArray);
            if ($flag_test_required_other == 1)
            {
                $courseData['tests_required_other'] = 'true';
                $courseData['tests_required_exam_name'] =  $this->input->post('c_testOther');
            }


            //TEST PREP FOR COURSE
            $tests_prep = $this->input->post('examPrepRelatedExams',true);
            $testsprepArray = array();
            $j=0;
            $flag_test_prep_other = 0;
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
            $courseData['tests_preparation'] = implode(",",$tests_prep);
            if ($flag_test_prep_other == 1)
            {
                $courseData['tests_preparation_other'] = 'true';
                $courseData['tests_preparation_exam_name'] =  $this->input->post('examPrepRelatedExamsOther');
            }


            $courseData['selection_criteria'] = $this->input->post('c_selection_criteria',true);
            //		$courseData['invite_emails'] = $this->input->post('i_emailids',true);

            $courseData['placements'] = $this->input->post('c_placements',true);
            if($courseData['placements'] == "yes")
            {
                $courseData['placements'] = $this->input->post('c_placements_desc',true);
            }
            $courseData['scholarships'] = $this->input->post('c_scholarships',true);
            if($courseData['scholarships'] == "yes")
            {
                $courseData['scholarships'] = $this->input->post('c_schol_exams',true);
            }
            $courseData ['hostel_facility'] = $this->input->post('c_hostel_facility',true);
            $courseData['fees'] = "";
            if ($this->input->post('c_fees_amount'))
            {
                $fees1 = $this->input->post('c_fees_currency',true);
                $fees2 = $this->input->post('c_fees_amount',true);

                $courseData['fees'] = $fees1." ".$fees2;
            }
            if ($this->input->post('chk_fees_other')) {
                $courseData['fees'] .= ";".$this->input->post('c_fees_other',true);
            }
            //$courseData['username'] = $this->userStatus[0]['userid'];

            //$media_content = $this->input->post('c_media_content');

            $courseData['dataFromCMS'] = "dataFromCMS";
            $courseData['examSelected'] = array($this->input->post('examSelected', true),'struct');//Ashish
            //$response = $ListingClientObj->add_course($appId,$courseData,$Data,$testsArray);
            $response = $EnterpriseClientObj->EditUpdateCourse($appId,$courseData,$Data,$testsArray);
            $response['title'] = $courseData['courseTitle'];


            /* COURSE Media Content Update */

            if($flagMedia != 0){
            error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload media for course ".$response['Course_id']);

            if($maxPhotos >0)
            {
                $courImgFlag = false;
                for($i = 0;$i < $maxPhotos ; $i++){
                    if(isset($_FILES['c_images']['tmp_name'][$i]) && ($_FILES['c_images']['tmp_name'][$i] != '')){
                        $courImgFlag = true;
                        break;
                    }else{
                        $courImgFlag = false;
                    }
                }

                if($courImgFlag)
                {
                    $arrCaption = $this->input->post('c_images_caption');
                    $photoCaption = array();
                    for($i=0;$i<count($_FILES['c_images']['name']);$i++){
                        $photoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_images']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'image',$_FILES,$photoCaption,$response['Course_id'], "course",'c_images');
                    //             print_r($upload_forms);
                    error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload response for images : ".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl_m'];
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent called REQUEST ".print_r($reqArr,true));
                            $updateCoursePhotos = $ListingClientObj->updateMediaContent($appId,$response['Course_id'],'course','photos',$reqArr);
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent RESPONSE ".print_r($updateCoursePhotos,true));
                            //                print_r($updateCoursePhotos);
                        }
                        $numOfPhotos = $k;
                    }
                }
            }

            if($maxVideos >0)
            {
                $courVidFlag = false;
                for($i = 0;$i < $maxVideos; $i++){
                    if(isset($_FILES['c_videos']['tmp_name'][$i]) && ($_FILES['c_videos']['tmp_name'][$i] != '')){
                        $courVidFlag = true;
                        break;
                    }else{
                        $courVidFlag = false;
                    }
                }

                if($courVidFlag)
                {
                    $arrCaption = $this->input->post('c_videos_caption');
                    $videoCaption = array();
                    for($i=0;$i<count($_FILES['c_videos']['name']);$i++){
                        $videoCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_videos']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'video',$_FILES,$videoCaption,$response['Course_id'], "course",'c_videos');

                    error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload response for videos : ".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        error_log_shiksha("ADD COURSE_COLLEGE LISTING : in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent called REQUEST ".print_r($reqArr,true));
                            $updateCourseVideos = $ListingClientObj->updateMediaContent($appId,$response['Course_id'],'course','videos',$reqArr);
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent RESPONSE ".print_r($updateCourseVideos,true));
                        }
                        $numOfVideos = $k;
                    }
                }
            }

            if($maxDocs >0)
            {
                $courDocFlag = false;
                for($i = 0;$i < $maxDocs; $i++){
                    if(isset($_FILES['c_docs']['tmp_name'][$i]) && ($_FILES['c_docs']['tmp_name'][$i] != '')){
                        $courDocFlag= true;
                        break;
                    }else{
                        $courDocFlag= false;
                    }
                }
                if($courDocFlag)
                {
                    $arrCaption = $this->input->post('c_docs_caption');
                    $docCaption = array();
                    for($i=0;$i<count($_FILES['c_docs']['name']);$i++){
                        $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['c_docs']['name'][$i];
                    }

                    $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['Course_id'], "course",'c_docs');
                    error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload response for docs : ".print_r($upload_forms,true));
                    error_log_shiksha("upload response for docs jklkl : ".print_r($upload_forms,true));

                    if($upload_forms['status'] == 1){
                        /*$upload_forms['type_id'] = $response['Admission_notification_id'];
                          $upload_forms['listing_type'] = 'notification';
                          $upload_forms['media_type'] = 'doc';*/
                        error_log_shiksha("in upload success".print_r($upload_forms,true));
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : updateMediaContent called REQUEST ".print_r($reqArr,true));

                            $updateCourseDocs = $ListingClientObj->updateMediaContent($appId,$response['Course_id'],'course','doc',$reqArr);
                            error_log_shiksha("ADD COURSE_COLLEGE LISTING : upload response for docs : ".print_r($updateCourseDocs,true));
                        }

                        $numOfDocs = $k;
                    }
                }
            }
        }

	    $ch = curl_init();
	    curl_setopt($ch, CURLOPT_URL, "https://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$courseData['update_course_id']."/course");

            curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
            curl_setopt($ch, CURLOPT_POST, 1);
            $result = curl_exec($ch); // run the whole process
            curl_close($ch);

            $displayData['response'] = $response;
            $displayData['fromEditCMS'] = 1;
            $displayData['type'] = "course";
            $displayData['validateuser'] = $this->userStatus;
            $displayData['myProducts'] = $cmsUserInfo['myProducts'];
            $dataFromCMS = "dataFromCMS";
            $prodId = $this->input->post('prodId',true);
            if($dataFromCMS != "dataFromCMS"){
                $this->load->view('listing/resultPage',$displayData);
            }else{/*
            $displayData['prodId'] = $prodId;
            if($courseData['contact_email'] != ''){
                $mail_client = new Alerts_client();
                $mailContent =  'You have successfully added following Course Listing:<br/> Title :'.$courseData['courseTitle'].'  <br/>';
                $sendMailRes = $mail_client->externalQueueAdd($appId,ADMIN_EMAIL,$courseData['contact_email'],'Your CMS Course Listing Added Successfully',$mailContent);
                error_log_shiksha("SEEEEEEEEEEEEEEEEEEEEEDMAAAAAAAAAAAAAAAAAAAIL".print_r($sendMailRes,true));
            }*/
            $this->load->view('enterprise/updateResultPageCMS',$displayData);
        }
    }

    function addScholarshipCMS($prodId)
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
	$this->init();
        $onBehalfOf = $this->input->post('onBehalfOf');
        if ($onBehalfOf=="true")
        {
        	$userid = $this->input->post('selectedUserId',true);
        	$this->load->library('register_client');
        	$regObj = new Register_client();
        	$arr = $regObj->userdetail($this->appId,$userid);
        	$displayData['userDetails'] = $arr[0];
        }

        $displayData['userid'] = $userid;
        $displayData['usergroup'] = $usergroup;

        if(($usergroup != "cms") || ($onBehalfOf=="true") ){
            $displayData['userProducts'] = $this->paymentCheck($userid);
        }
        $displayData['thisUrl'] = $thisUrl;
        $displayData['validateuser'] = $validity;
        $ListingClientObj = new Listing_client();
        $countryList = $ListingClientObj->getCountries($appId);
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $displayData['completeCategoryTree'] = json_encode($categoryForLeftPanel);

        $displayData['country_list'] = $countryList;
        $displayData['listingType'] = "scholarship";
        $displayData['prodId'] = $this->productAccessCheck("Scholarships");//Name: as is shown in the Enterprise-Tabs
	$displayData['dataFromCMS'] = 1;
	$displayData['headerTabs'] = $cmsUserInfo['headerTabs'];
        $displayData['myProducts'] = $cmsUserInfo['myProducts'];
        $displayData['onBehalfOf'] = $onBehalfOf;

	$this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $displayData['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));

        $this->load->view('enterprise/schol_listing_cms',$displayData);
    }


    function cmsEditScholarship($scholId) {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $appId = '1';

        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $flagMedia = 1;

        $upCourData = array();
        $upCourData['flagMedia'] = $flagMedia;
        $upCourData['thisUrl'] = $thisUrl;
        $upCourData['usergroup'] = $usergroup;
        $upCourData['validateuser'] = $this->userStatus;

        $ListingClientObj = new Listing_client();
        $scholData = $ListingClientObj->getListingDetails($appId,$scholId,"scholarship");
        //error_log_shiksha("===SS===".print_r($scholData,true));
        if($usergroup == "enterprise"){
            if($scholData[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
        }

        $userid = $scholData[0]['userId'];

        $upCourData['userid'] = $userid;

        foreach($scholData['0'] as $key => $val)
        {
            $upCourData[$key] = $val;
        }
        $countryList = $ListingClientObj->getCountries($appId);
        $upCourData['country_list'] = $countryList;
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $upCourData['completeCategoryTree'] = json_encode($categoryForLeftPanel);
        $upCourData['dataFromCMS'] = 1;
        $upCourData['prodId'] = 4;

	$upCourData['headerTabs'] = $cmsUserInfo['headerTabs'];
        $upCourData['myProducts'] = $cmsUserInfo['myProducts'];
        $upCourData['listingType'] = "scholarship";

        $this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $upCourData['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));
        //echo '<pre>';print_r($upCourData);echo '</pre>';

        $this->load->view('enterprise/editScholContainer',$upCourData);
    }


    function updateScholarshipCMS($appId=1)
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        error_log_shiksha("Edit SCHOLARSHIP LISTING : SCHOLARSHIP START");
        error_log_shiksha("Edit SCHOLARSHIP LISTING : data received ".print_r($_POST,true));
        global $logged;
        global $userid;
        global $usergroup;
        $paymentInfo = array();
        $validity = $this->checkUserValidation();
        $userid = $validity[0]['userid'];
        $usergroup = $validity[0]['usergroup'];
        if($usergroup != "cms"){
	   //$paymentInfo = $this->paymentCheck();
        }
        $this->init();

        global $userPack;
        $userPack = $this->input->post('userPack',true);

        $productDataList =  $paymentInfo;
/*
        if($usergroup != "cms" && !isset($productDataList[$userPack]['remaining'])) {
            error_log_shiksha("Selected Pack's no. of listing check: Selected Pack is Consumed: Buy that pack first");
            header("location:/payment/payment");
            exit();
        }

        $maxPhotos = 0;
        if (isset($productDataList[$userPack]['property']['M_Photos'])) {
            $maxPhotos = $productDataList[$userPack]['property']['M_Photos'];
        }

        $maxDocs = 0;
        if (isset($productDataList[$userPack]['property']['M_Presentation'])) {
            $maxDocs = $productDataList[$userPack]['property']['M_Presentation'];
        }
*/
//FIXME for userpack according to current pack of the listing
        if ($usergroup == "cms") {
            $userPack = 0;
            $maxPhotos = 3;
            $maxVideos = 3;
            $maxDocs = 3;
        }

        $this->load->library('upload_client');
        //$remaining = $this->paymentCheck();
        //$flagMedia = $remaining['flagMedia'];
        //global $userid;
        $uploadClient = new Upload_client();
        $ListingClientObj = new Listing_client();
        //echo "<pre>";print_r($_REQUEST);echo "</pre>";
        if(isset($_REQUEST['s_schol_name']) )
        {
            $addScholarshipData = array();
            $addScholarshipData['update_schol_id']= $this->input->post('update_schol_id');
            $addScholarshipData['packType'] = $userPack;
            $addScholarshipData['scholarship_name'] = $this->input->post('s_schol_name',true);
            $addScholarshipData['short_desc'] = $this->input->post('s_description',true);
            if ($this->input->post('s_no_of_schol'))
                $addScholarshipData['num'] = $this->input->post('s_no_of_schol',true);
            $addScholarshipData['levels'] = $this->input->post('s_level',true);
            if ($addScholarshipData['levels']=="Graduate" || $addScholarshipData['levels']=="Post-Graduate" || $addScholarshipData['levels']=="Phd/Research")
            {
                $addScholarshipData['country_id'] =implode(',',$_REQUEST['country_id']);
                $addScholarshipData['city_id'] =implode(',',$_REQUEST['city_id']);
                $addScholarshipData['institute_id'] = implode(',',$_REQUEST['institute_id']);
                for($i = 0 ; $i < count($_REQUEST['country_id']); $i++)
                {
                    $addScholarshipData['country_id'.$i] = $_REQUEST['country_id'][$i];
                    $addScholarshipData['city_id'.$i] = $_REQUEST['city_id'][$i];
                    $addScholarshipData['institute_id'.$i] = $_REQUEST['institute_id'][$i];
                }
                $addScholarshipData['numoflocations'] = $i;
            }
            $addScholarshipData['last_date_submission'] = $this->input->post('last_date_sub',true);
            $addScholarshipData['application_procedure'] = $this->input->post('s_app_desc',true);
            $addScholarshipData['selection_process'] = $this->input->post('s_sel_process',true);
            $addScholarshipData['value'] = $this->input->post('s_award_value',true);
            //$addScholarshipData['username'] = $userid;
            $addScholarshipData['contact_name'] =  $this->input->post('s_contact_name',true);
            $addScholarshipData['contact_address'] = $this->input->post('s_contact_add',true);
            $addScholarshipData['contact_cell'] =  $this->input->post('s_phone_no',true);
            $addScholarshipData['contact_email'] =  $this->input->post('s_email',true);
            $addScholarshipData['contact_fax'] = $this->input->post('s_fax_no',true);
            //Category
            if(count($_REQUEST['c_categories']) > 0)
            {
                $addScholarshipData['category_id'] = implode(',',$_REQUEST['c_categories']);
            }
            //echo "<pre>";print_r($addScholarshipData);echo "</pre>";
            //Eligibility array
            $s_eligibility = array();
            $eligText = "";
            if ($this->input->post('s_elg_gender'))
            {
                $eligText  = " Gender :".$this->input->post('s_elg_gender',true);
                $s_eligibility['gender'] = $this->input->post('s_elg_gender',true);
            }
            if ($this->input->post('s_elg_age'))
            {
                $eligText  .= " Age :".$this->input->post('s_elg_age',true);
                $s_eligibility['age'] = $this->input->post('s_elg_age',true);
            }
            if ($this->input->post('s_elg_res_stat'))
            {
                $eligText .= " Residency Status :".$this->input->post('s_elg_res_stat',true);
                $s_eligibility['res_stat'] = $this->input->post('s_elg_res_stat',true);
            }
            if ($this->input->post('s_elg_minqual'))
            {
                $eligText .= " Min Qual. :".$this->input->post('s_elg_minqual',true);
                $s_eligibility['minqual'] = $this->input->post('s_elg_minqual',true);
            }
            if ($this->input->post('s_elg_workex'))
            {
                $eligText .= " Work Experience :".$this->input->post('s_elg_workex',true);
                $s_eligibility['workex'] = $this->input->post('s_elg_workex',true);
            }
            if ($this->input->post('s_elg_marks'))
            {
                $eligText .= " Marks ".$this->input->post('s_elg_marks',true);
                $s_eligibility['marks'] = $this->input->post('s_elg_marks',true);
            }
            if ($this->input->post('s_elg_faminc'))
            {
                $eligText .= " Family Income ".$this->input->post('s_elg_faminc',true);
                $s_eligibility['faminc'] = $this->input->post('s_elg_faminc',true);
            }
            if ($this->input->post('s_elg_other'))
            {
                $eligText .= " Other Eligibility :".$this->input->post('s_elg_other',true);
                $s_eligibility['other'] = $this->input->post('s_elg_other',true);
            }

            //echo "<pre>"; print_r($s_eligibility);echo "</pre>";

            $addScholarshipData['dataFromCMS'] = $this->input->post('dataFromCMS',true);
            $addScholarshipData['requestIP'] = S_REMOTE_ADDR;
            error_log_shiksha("Edit SCHOLARSHIP LISTING : updateScholarship called ".print_r($addScholarshipData,true).print_r($s_eligibility,true));

            $EnterpriseClientObj = new Enterprise_client();
            $response = $EnterpriseClientObj->updateScholarship($appId,$addScholarshipData,$s_eligibility);
            error_log_shiksha("Edit SCHOLARSHIP LISTING :updateScholarship response ".print_r($response,true));
            //echo "<pre>";print_r($response);echo "</pre>";
            $scholarship_id = $response['type_id'];
          //  $listing_type = $response['listing_type'];
            $response['title'] = $addScholarshipData['scholarship_name'];

            if($maxDocs >0)
            {
                $scholDocFlag = false;
                for($i = 0;$i < $maxDocs; $i++){
                    if(isset($_FILES['s_upload_f']['tmp_name'][$i]) && ($_FILES['s_upload_f']['tmp_name'][$i] != '')){
                        $scholDocFlag= true;
                        break;
                    }else{
                        $scholDocFlag= false;
                    }
                }
                if($scholDocFlag)
                {
                    error_log_shiksha("ADD SCHOLARSHIP LISTING : uploadFile called");
                    $arrCaption = $this->input->post('s_upload_f_caption');
                    $docCaption = array();
                    for($i=0;$i<count($_FILES['s_upload_f']['name']);$i++){
                        $docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['s_upload_f']['name'][$i];
                    }
                    $upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$response['scholarship_id'],"scholarship",'s_upload_f');
                    error_log_shiksha("ADD SCHOLARSHIP LISTING : uploading docs response:::".print_r($upload_forms,true));
                    if($upload_forms['status'] == 1){
                        for($k = 0;$k < $upload_forms['max'] ; $k++){
                            $reqArr = array();
                            $reqArr['mediaid']=$upload_forms[$k]['mediaid'];
                            $reqArr['url']=$upload_forms[$k]['imageurl'];
                            $reqArr['title']=$upload_forms[$k]['title'];
                            $reqArr['thumburl']=$upload_forms[$k]['thumburl'];
                            error_log_shiksha("ADD SCHOLARSHIP LISTING : updating docs request:::".print_r($reqArr,true));
                            $updateScholarship = $ListingClientObj->updateMediaContent($appId,$scholarship_id,'scholarship','doc',$reqArr);
                            error_log_shiksha("ADD SCHOLARSHIP LISTING : updating docs response:::".print_r($updateScholarship,true));
                        }
                    }
                }
            }

            curl_setopt($ch, CURLOPT_URL, "https://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$addScholarshipData['update_schol_id']."/scholarship");

            curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
            curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
            curl_setopt($ch, CURLOPT_HEADER, 1);
            curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
            curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
            curl_setopt($ch, CURLOPT_POST, 1);
            $result = curl_exec($ch); // run the whole process
            curl_close($ch);

            if(isset($_REQUEST['json'])){
                echo json_encode($response);
            }
            else{
                $displayData['response'] = $response;
                $displayData['type'] = "scholarship";
                $displayData['validateuser'] = $this->userStatus;
                //            print_r($displayData,true);
                $dataFromCMS = $this->input->post('dataFromCMS',true);
                $prodId = $this->input->post('prodId',true);
                $this->load->view('enterprise/updateResultPageCMS',$displayData);

            }
            //$this->getListingDetails($appId,$addScholarshipData['listing_id']);
        }
    }

    function addAdmissionCMS($prodId)
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
	$validity = $cmsUserInfo['validity'];
	$this->init();

        $onBehalfOf = $this->input->post('onBehalfOf');
        if ($onBehalfOf=="true")
        {
        	$userid = $this->input->post('selectedUserId',true);
        	$this->load->library('register_client');
        	$regObj = new Register_client();
        	$arr = $regObj->userdetail($this->appId,$userid);
        	$displayData['userDetails'] = $arr[0];
        }

        $displayData['userid'] = $userid;
        $displayData['usergroup'] = $usergroup;

        if(($usergroup != "cms") || ($onBehalfOf=="true") ){
            $displayData['userProducts'] = $this->paymentCheck($userid);
        }
        $displayData['thisUrl'] = $thisUrl;
        $displayData['validateuser'] = $validity;
        $ListingClientObj = new Listing_client();
        $countryList = $ListingClientObj->getCountries($appId);
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }
        $displayData['completeCategoryTree'] = json_encode($categoryForLeftPanel);

        $displayData['country_list'] = $countryList;
        $displayData['listingType'] = "admission";
        $displayData['prodId'] =$this->productAccessCheck("Admission Notifications");//Name: as is shown in the Enterprise-Tabs
        $displayData['dataFromCMS'] = 1;
	$displayData['headerTabs'] = $cmsUserInfo['headerTabs'];
	$displayData['myProducts'] = $cmsUserInfo['myProducts'];
        $displayData['onBehalfOf'] = $onBehalfOf;

	$this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $displayData['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));

        $this->load->library('blog_client');
        $blogClient = new Blog_client();
        $examsList = $blogClient->getExamsForProducts(1);
        $displayData['examsList'] = $examsList;
        $this->load->view('enterprise/addadmnotCMS',$displayData);
    }

    function loginEnterprise() {
        if(ENVIRONMENT == "production" && getCurrentPageURLWithoutQueryParams() != ENTERPRISE_HOME."/enterprise/Enterprise/loginEnterprise"){
            header("location:".ENTERPRISE_HOME."/enterprise/Enterprise/loginEnterprise");
        }
        $appId = '1';
        $validity = $this->checkUserValidation();
        if(($validity == "false" )||($validity == "")) {
            $userid = '';
            $usergroup = '';
        }else{
            $userid = $validity[0]['userid'];
            $usergroup = $validity[0]['usergroup'];
        }
        $this->init();

        if(isset($usergroup) && ($usergroup == "cms" || $usergroup == "enterprise" || $usergroup == "sums" || $usergroup == 'listingAdmin')){
            header("location:/enterprise/Enterprise");
            exit();
        }

        // The beacon tracking data
        $displayData['beaconTrackData'] = array(
            'pageIdentifier' => 'enterpriseLoginPage',
            'pageEntityId' => 0,
            'extraData' => array('url'=>get_full_url())
        );
        $this->load->view('enterprise/loginEnterprise',$displayData);
    }

    function getDetailsForListingCMS() {
        $appId = 1;
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $type_id = $this->uri->segment(4);
        $listing_type = $this->uri->segment(5);
        $prodId = $this->uri->segment(6);

        $displayData = array();
        $registerClient = new register_client();
        $ListingClientObj = new Listing_client();

        $thisUrl = $_SERVER['REQUEST_URI'];
        if((!is_array($this->userStatus)) && ($this->userStatus == "false"))
        {
            $displayData['subscribeAction'] = "showuserOverlay(this,'join');";
            $loggedIn = false;
        }
        else{
            $userId = $this->userStatus[0]['userid'];
            $userDetails = $registerClient->userdetail($appId,$userId);
            $email = '';
            $mobile = '';
            if(isset($userDetails[0]['email']))
            {
                $email = $userDetails[0]['email'];
                $mobile = $userDetails[0]['mobile'];
            }
            $displayData['email'] = $email;
            $displayData['mobile'] = $mobile;
            $displayData['userId'] = $userId;
            $displayData['validateuser'] = $validity;
            $displayData['reqInfo'] = $ListingClientObj->getUserReqInfo($appId,$userId, $listing_type,$type_id);
            $displayData['subscribeAction'] = 'showAlertListingOverlay();';
        }
        /*
        echo "<pre>";
            print_r($displayData);
            echo "</pre>";*/
        error_log_shiksha("CONTROLLER getCityList APP ID=> $appId :: $type_id  $listing_type");


        $listingDetails = $ListingClientObj->getListingDetails($appId,$type_id,$listing_type);
        $displayData['type_id'] = $type_id;
        $displayData['listing_type'] = $listing_type;
        $displayData['prodId'] = $prodId;
        $displayData['thisUrl'] = $thisUrl;
        $displayData['cmsData'] = 1;
        /*		switch($listing_type){
            case "course":
            case "institute":
            $listingDetails[0]['alertProductId'] = 1;
            $listingDetails[0]['alertProductName'] = "courseAndCollege";
            break;
            case "scholarship":
            $listingDetails[0]['alertProductId'] = 2;
            $listingDetails[0]['alertProductName'] = "scholarship";
            break;
            case "notification":
            $listingDetails[0]['alertProductId'] = 3;
            $listingDetails[0]['alertProductName'] = "examForm";
            break;

        }*/
        $displayData['details'] = $listingDetails[0];
        /*        echo "<pre>";
            print_r($listingDetails);
            echo "</pre>";*/

        if(isset($listingDetails[0]['institute_id'])){
            $institute_id = $listingDetails[0]['institute_id'];
        }
        elseif(is_array($listingDetails[0]['instituteArr'])){
            $institute_id = $listingDetails[0]['instituteArr'][0]['institute_id'];
            for($i = 1; $i < count($listingDetails[0]['instituteArr']) ; $i++){

                $institute_id .= ','.$listingDetails[0]['instituteArr'][$i]['institute_id'];
            }
        }
        /*		$collegeNetWork  = $this->getCollegeNetwork($institute_id);
        $collegeNetWorkCount  = $this->showCollegeNetworkCount($institute_id);
        $displayData['collegeNetwork'] = $collegeNetWork;
        $displayData['collegeNetworkCount'] = $collegeNetWorkCount;
        if(count($collegeNetWorkCount)> 0){
            $displayData['collegeNetworkModule'] = true;
        }*/

        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);

        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId'],$temp['urlName']);
        }
        //echo "<pre>";print_r($displayData);echo "</pre>";
        for ($i=0;$i<count($displayData['details']['categoryArr']);$i++)
        {
            $catid= $displayData['details']['categoryArr'][$i]['category_id'];
            $displayData['details']['categoryArr'][$i]['cat_name']=$categoryForLeftPanel[$catid][0];
            $displayData['details']['categoryArr'][$i]['cat_url']=$categoryForLeftPanel[$catid][2];
            $parent = $categoryForLeftPanel[$catid][1];
            $displayData['details']['categoryArr'][$i]['parent_cat_name']=$categoryForLeftPanel[$parent][0];
            $displayData['details']['categoryArr'][$i]['parent_url']=$categoryForLeftPanel[$parent][2];
            $displayData['details']['categoryArr'][$i]['parent_cat_id'] = $parent;
        }

        /*echo "<pre>";
            print_r($displayData);
            echo "</pre>";*/

	$displayData['headerTabs'] = $cmsUserInfo['headerTabs'];
        $displayData['myProducts'] = $cmsUserInfo['myProducts'];
        $this->load->view('enterprise/cmsListingDetails',$displayData);
    }

    function unauthorizedEnt()
    {
        $validity = $this->checkUserValidation();
        if(($validity == "false" )||($validity == "")) {
            header('location:/enterprise/Enterprise');
            exit();
        }
        $this->init();
        $this->load->view('enterprise/unauthorizedEnt');
    }

    function disallowedAccess()
    {
        $this->init();
        $this->load->view('enterprise/disallowedAccess');
    }

    function addBlogCMS($blogId="",$status='live')
    {
        $fromWhere  = 'cms';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $board_id = 1;
        $appId = 12;
        $categoryClient = new Category_list_client();
        $blogClient = new Blog_client();
        $userId = 1;
		
        //Region Neha
        $userStatus = $this->checkUserValidation();
        if((!is_array($userStatus)) && ($userStatus == "false"))
        {
            $currentUrl = site_url('blogs/shikshaBlog/createBlog').'/'.$appId;
            redirect('user/login/userlogin/'.base64_encode($currentUrl),'location');
        }
        $userId = $userStatus[0]['userid'];
        #Region Neha Ends

        $AuthorName = "Manish-CMS";

        $rows = 5;
        $popularTopics = array();

        $betcrumb = array();
        $Validate = $this->checkUserValidation();
		$data['validateuser'] = $Validate;
		$data['prodId'] = 1;
		$data['headerTabs'] = $cmsUserInfo['headerTabs'];
        $data['myProducts'] = $cmsUserInfo['myProducts'];

        $this->load->library('blog_client');
        $blogClient = new Blog_client();
        $blogInfo = array();
        $blogImages = array();
        if($blogId != '') {
		//$blogInfo = $blogClient->getBlogInfo($appID,$blogId,'',$status);
                $this->load->model('articlemodel');
                $blogInfo = $this->articlemodel->getBlogInfo($blogId,'blogs', $pageNum = '',$status, $fromWhere);
                $blogImages = $blogClient->getBlogImages($appID,$blogId,$status, $fromWhere);
                $boardMapping = $this->articlemodel->getBoardMappingData($blogId, $fromWhere);
	    }
	
        $data['blogInfo'] = $blogInfo;
        $data['boardMapping'] = $boardMapping;
        $data['blogImages'] = $blogImages;
        $examParents =  $blogClient->getExams($appID,$status, $fromWhere);
        $data['examParents'] = $examParents;
		$data['pollJSON'] = $this->getPollsData($blogId,$status, $fromWhere);

    	//code added by pragya...
    	$EnterpriseClientObj = new Enterprise_client();
    	$clientUpdItemArr = array("item_type" => 'blog',"item_id" => $blogId);
        $response = $EnterpriseClientObj->getKeyPages($appId,$clientUpdItemArr,$fromWhere);
    	if(count($response) >0){
    	    for($i=0;$i<count($response);$i++ ){
    		if($response[$i]['KeyId']==51){
    		    $data['FlavorStartDate'] = $response[$i]['StartDate'];
    		    $data['FlavorEndDate'] = $response[$i]['EndDate'];
    		}
    	    if($response[$i]['KeyId']==52)
    		$data['LastUpdateSet'] = $response[$i]['KeyId'];
    	    }
    	}
	
	    //code ends
        if($blogId != 1){
            $data['prefilledData'] = Modules::run('common/commonHierarchyForm/getPrefilledData', 'articleCMS', array('blogId' => $blogId, 'status' => $status));
        }
		$data['suggestorPageName'] = 'CMS_suggestors';
        $this->load->view('enterprise/createBlogCMS',$data);
    }

    function prepareBoardData(){
        $type = $this->input->post('blogType');
        $this->load->config('blogs/blogsConfig');
        if($type == 'boards'){
            $boadList = $this->config->item($type);    
            $data['boardList'] = $boadList['boardName'];
            $html = $this->load->view('blogs/boardList',$data);
        }else if($type == 'coursesAfter12th'){
            $boadList = $this->config->item($type); 
            $data['boardClass'] = $boadList['class'];
            $html = $this->load->view('blogs/boardClassList',$data);
        }
        return $html;
    }

    function prepareBoardClass(){
        $this->load->config('blogs/blogsConfig');
        $boadList = $this->config->item('boards');    
        $data['boardClass'] = $boadList['class'];
        $html = $this->load->view('blogs/boardClassList',$data);
        return $html;
    }

    function validateBoardPage(){
        $typeForUrl = ($this->input->post('blogType') == 'coursesAfter12th') ? 'Courses After 12th' : $this->input->post('blogType');
        $param = array('blogType'=>$typeForUrl,'board'=>$this->input->post('boardName'),'class'=>$this->input->post('boardClass'));
        $articleUrlLib = $this->load->library('common/UrlLib');
        $articleUrl    = $articleUrlLib->getBoardUrl($param);
        $this->load->model('blogs/articlemodel', $this->articlemodel);
        $blogId = $this->articlemodel->getBlogIdByUrl($articleUrl);
        echo ($blogId) ? $blogId : 0;
    }

    function getArticleHierarchyData($blogId,$status){
        $this->load->model('articlemodel');
        $data = $this->articlemodel->getArticleHierarchyData($blogId,$status);
        return $data;
    }
	
	private function getPollsData($blogId,$status,$fromWhere){
		$this->load->model('articlemodel');
		return $this->articlemodel->getPollsData($blogId,$status,$fromWhere);
	}
	
	function formatCategoryTree($categoryList){
		$others = array();
		$categoryForLeftPanel = array();
		foreach($categoryList as $temp)
		{
			if((stristr($temp['categoryName'],'Others') == false)){
			$categoryForLeftPanel[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
			}else{
			$others[$temp['categoryID']] = array($temp['categoryName'],$temp['parentId']);
			}
		}
		foreach($others as $key => $temp)
		{
			$categoryForLeftPanel[$key] = array($temp[0],$temp[1]);
		}
		return $categoryForLeftPanel;
	}
    function getCategoryTreeArray(& $returnArray, $categoryTree,$parentId, $parentCategoryName)
    {
        $i=0;
        foreach($categoryTree as $categoryLeaf)
        {
            if($categoryLeaf['parentId'] == $parentId)
            {
                $returnArray[$parentCategoryName][$i++] =
                $categoryLeaf['categoryID'] ."<=>". $categoryLeaf['categoryName'];

                $this->getCategoryTreeArray($returnArray[$parentCategoryName],
                $categoryTree, $categoryLeaf['categoryID'],  $categoryLeaf['categoryName']);
            }
        }
        return  $returnArray;
    }

    function removeInstiLogo($instituteId){
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$instituteId,"institute");
        if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                error_log_shiksha("Failed Remove Rights check !!");
                exit();
            }
        }
        error_log_shiksha("Passed Remove Rights check !!");
        $EnterpriseClientObj = new Enterprise_client();

        $deleteResponse = $EnterpriseClientObj->removeInstiLogoCMS($appId,$instituteId);
        //      error_log_shiksha("===SS===".print_r($deleteResponse,true));
        //echo json_encode($eventData);
        echo json_encode($deleteResponse);
    }

    function removeFeaturedPanelLogo($instituteId){
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$instituteId,"institute");
        if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                error_log_shiksha("Failed Remove Rights check !!");
                exit();
            }
        }
        error_log_shiksha("Passed Remove Rights check !!");
        $EnterpriseClientObj = new Enterprise_client();

        $deleteResponse = $EnterpriseClientObj->removeFeaturedPanelLogo($appId,$instituteId);
        //      error_log_shiksha("===SS===".print_r($deleteResponse,true));
        //echo json_encode($eventData);
        echo json_encode($deleteResponse);
    }

    function removeCourseMedia($courseId,$mediaType,$courseMediaId,$listingType){
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $ListingClientObj = new Listing_client();
        if($listingType == 'course'){
            $listingDetails = $ListingClientObj->getListingDetails($appId,$courseId,"course");
        }
        if($listingType == 'institute'){
            $listingDetails = $ListingClientObj->getListingDetails($appId,$courseId,"institute");
        }
         if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
               header("location:/enterprise/Enterprise/disallowedAccess");
                error_log_shiksha("Failed Remove Rights check !!");
                exit();
            }
        }
        error_log_shiksha("Passed Remove Rights check !!");
        $EnterpriseClientObj = new Enterprise_client();

        $removeMediaArr = array();
        $removeMediaArr['userid']= $userid;
        $removeMediaArr['usergroup']= $usergroup;
        $removeMediaArr['courseId']= $courseId;
        $removeMediaArr['mediaType']= $mediaType;
        $removeMediaArr['courseMediaId']= $courseMediaId;
        $removeMediaArr['listingType']= $listingType;

        $deleteResponse = $EnterpriseClientObj->removeCourseMediaCMS($appId,$removeMediaArr);
        //      error_log_shiksha("===SS===".print_r($deleteResponse,true));
        //echo json_encode($eventData);
        echo json_encode($deleteResponse);
    }


    function deleteTopicFromCMS()
    {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $appId = 12;
        $msgId = $this->input->post('msgId');
        $msgbrdClient = new Message_board_client();
        $listingClient = new Listing_client();
        $response = $msgbrdClient->deleteTopicFromCMS($appId,$msgId);
        $result = $listingClient->deleteMsgbrdListing($appId,'msgbrd',$msgId);
        echo json_encode($response);
    }

    function deleteCommentFromCMS()
    {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $appId = 12;
        $msgId = $this->input->post('msgId');
	$threadId = $this->input->post('threadId');
	$userIdForQuestion = $this->input->post('userId');
        $msgbrdClient = new Message_board_client();
        $response = $msgbrdClient->deleteCommentFromCMS($appId,$msgId,$threadId,$userIdForQuestion);
        echo json_encode($response);
    }

    function searchLuceneCourse() {
        $startTime = microtime(true);
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();
		$appId = 12;
		$type = "institute";
        $_REQUEST['institute_rows'] = 7;
		$_REQUEST['start'] = 0;
		$searchData = Modules::run('search/Search/getCMSSearchResults');
		$displayData = array();
		$displayData['data']  = $searchData;
        //$str = "";
        //foreach ($displayData['data']['results'] as $d) {
        //    $str .= $d['userId']. ",";
        //}
        //$str = substr($str,0,strlen($str)-1);
        //$registerClient = new Register_client();
        //$userDetails = $registerClient->getDetailsforUsers($appId,$str);
        //$displayData['userDetails'] = array();
        //foreach ($userDetails as $u) {
        //    $displayData['userDetails'][$u['userid']] = $u['displayname'];
        //}
		$displayData['usergroup'] = $usergroup;
       	$this->load->view("enterprise/".$type."lucene",$displayData);
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    function saveSearchKeyword()
    {   $startTime = microtime(true);
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $appId = 12;
        $keyword = $this->input->post('keyword');
        $location = $this->input->post('location');
        $type = $this->input->post('type');
        $typeId = $this->input->post('typeId');
        $searchType = $this->input->post('searchType');
        $sponsorType = $this->input->post('sponsorType');
	$listing_client = new Listing_client();
	if ($sponsorType=="featured") {
        	$response = $listing_client->getFeaturedPanelLogo($appId,array($typeId));
		if (!(isset($response[$typeId]) && $response[$typeId]!="")) {
		   echo "Featured Panel Logo is not uploaded for this College/Institute.";
			return;
		     }
        }

	$chk = $listing_client->getSponsorListingStatusByKeyword($appId,$keyword,$location,$typeId,$type,$this->userStatus[0]['userid'],$searchType,$sponsorType);
        if ($chk!=1) {
            $res = $listing_client->addSponsorListingByKeyword($appId,$keyword,$location,$typeId,$type,$this->userStatus[0]['userid'],$searchType,$sponsorType);
        }
        echo $res['result'];
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

    function getSavedSearchKeyword()
    {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $appId = 12;
        $keyword = $this->input->post('keyword');
        $location = $this->input->post('location');
        $type = $this->input->post('type');
        $typeId = $this->input->post('typeId');
        $searchType = $this->input->post('searchType');
        $sponsorType= $this->input->post('sponsorType');
        $listing_client = new Listing_client();
        $chk = $listing_client->getSponsorListingStatusByKeyword($appId,$keyword,$location,$typeId,$type,$this->userStatus[0]['userid'],$searchType,$sponsorType);
        echo $chk;
    }

    function deleteSavedSearchKeyword()
    {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $appId = 12;
        $keyword = $this->input->post('keyword');
        $location = $this->input->post('location');
        $type = $this->input->post('type');
        $typeId = $this->input->post('typeId');
        $searchType = $this->input->post('searchType');
        $sponsorType = $this->input->post('sponsorType');
	$listing_client = new Listing_client();
        $chk = $listing_client->deleteSponsorListingByKeyword($appId,$keyword,$location,$typeId,$type,$this->userStatus[0]['userid'],$searchType,$sponsorType);
        echo json_encode($chk);
    }

    //Payment Check for Enterprise user
    function paymentCheck($userid){
    	$this->load->library('sums_product_client');
    	$objSumsProduct = new Sums_Product_client();
    	$userProducts = $objSumsProduct->getProductsForUser(1,array('userId'=>$userid));
    	$remaining = 0;
    	foreach ($userProducts as $product)
    	{
    		if ($product['BaseProdCategory']=="Listing")
    		{
				$remaining += $product['RemainingQuantity'];
    		}
    	}
    	if($remaining <= 0) {
            header("location:/enterprise/Enterprise/prodAndServ");
    		exit();
    	}

    	return $userProducts;
    }

    function MIS()
    {
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $EnterpriseClientObj = new Enterprise_client();
        $ListingClientObj = new Listing_client();

        $listingDetails = $ListingClientObj->getListingDetails($appId,$scholId,"scholarship");

        $MISdata = array();
        //$MISdata['details'] = $listingDetails[0];
        $this->load->view('enterprise/MIShome',$MISdata);
    }

    function register($redirectUrl = '')
    {
        $appId = 1;
        $this->init();
        $this->load->library('ajax');
        $this->load->helper('url');
        $this->load->library('Register_client');
        $Validate = $this->checkUserValidation();
        //_P($Validate);
        if(is_array($Validate) && $Validate[0]['userid']>0) {
			header('Location: /enterprise/Enterprise');
        	exit();
		} 
        $data['validateuser'] = $Validate;
        $url = '';
        if($redirectUrl != '')
        $url = base64_decode($redirectUrl);
        else
        $url = '/enterprise/Enterprise';
        $data['url'] = $url;
        $data['success'] = 'showRegistrationResponse';
        $isdCodeObj =  new \registration\libraries\FieldValueSources\IsdCode;
        $params['source'] = 'DB';
        // $isdCodes = $isdCodeObj->getValues($params);
        $isdCodes = $isdCodeObj->getValues();
  //       $finalIsdCodes = array();
  //       foreach($isdCodes as $val) {
		// 	$finalIsdCodes[$val['isdCode']] = $val['shiksha_countryName']."(+".$val['isdCode'].")";
		// }        
  //       asort($finalIsdCodes);
        $data['finalIsdCodes'] = $isdCodes;
		$ListingClientObj = new Listing_client();
        $data['countryList'] = $ListingClientObj->getCountries($appId);
        $this->load->view('enterprise/RegistrationEntp',$data);
    }

    function cookie($value)
    {
        setcookie('user',$value,time() + 2592000 ,'/');
    }

    function submitRegister()
    {
        //error_log_shiksha("Enterprise controller's Registration form Received data ".print_r($_REQUEST,true));
        $appId = 1;
        $this->init();
        //$usergroup = $this->input->post('usergroup');
        $usergroup = "enterprise";
        $email = trim($this->input->post('email'));
        $displayname = htmlentities(addslashes(trim($this->input->post('displayname'))));
        $password = addslashes($this->input->post('passwordr'));
        $confirmpassword = addslashes($this->input->post('confirmpassword'));
        $ePassword = sha256($password);
        $busiCollegeName= $this->input->post('busiCollegeName');
        $busiType= $this->input->post('busiType');
        if($busiType == "Other")
        {
            $busiType = $this->input->post('otherBusiType');
        }

        $contactName = htmlentities(addslashes(trim($this->input->post('contactName'))));
        $contactAddress = $this->input->post('contact_address');
        $pincode = trim($this->input->post('pincode'));
        //$mobile = trim($this->input->post('mobile'));
        $categories =  implode(',',$this->input->post('c_categories',true));

        $country = trim($this->input->post('countries'));
            $city = trim($this->input->post('cities'));
            if($city == "-1") //Case of Other city
            {
                $cityArray = array();
                $cityArray['country_id'] = trim($this->input->post('countries'));
                $cityArray['city_name'] = htmlentities(addslashes(trim($this->input->post('otherCity'))));
                $this->load->library('listing_client');
                $ListingClientObj = new Listing_client();
                $city = $ListingClientObj->insertCity($appId,$cityArray);
            }

		/* Changes done on 3rd September by Nikita Jain.
		 * When country is not india, database entry for abroad mobile will be '9999988877',
		 * 		to pass mobile validations at all other places (like request e-brochure).
		 * User's original mobile number is stored in $originalUserMobile.
		*/		
		
        $isd_code = trim($this->input->post('isdCode',true));
        $isdData  = explode('-', $isd_code);
        $isd_code = $isdData[0];
                
		//if($country == "2"){
		$mobile = trim($this->input->post('mobile'));
		$originalUserMobile = $mobile;
		/*} else {
			$originalUserMobile = trim($this->input->post('mobile'));
			$mobile = "9999988877";
		}*/	
		    

	/* Add Privacy checkboxes */

	$viamobile = $this->input->post('viamobile');
	$viamail = $this->input->post('viaemail');
	$newsletteremail = $this->input->post('newsletteremail');
	if($viamobile == "mobile")
		$viamobile = 1;
	else
		$viamobile = 0;

	if($viamail == "email")
		$viamail = 1;
	else
		$viamail = 0;

	if($newsletteremail == "newsletteremail")
		$newsletteremail = 1;
	else
		$newsletteremail = 0;

	$sourceurl = $this->input->post('enterpriseurl');
	$sourcename = 'ENTERPRISE_REGISTRATION_FORM';
	$resolution = $this->input->post('enterpriseresolution');
	$userarray['viamobile'] = $viamobile;
	$userarray['viamail'] = $viamail;
	$userarray['vianewsletteremail'] = $newsletteremail;


	/* Add Privacy checkboxes */

        $userarray['appId'] = $appId;
        $userarray['usergroup'] = $usergroup;
        $userarray['email'] = $email;
        $userarray['displayname'] = $displayname;
		$userarray['ePassword'] = $ePassword;
		$userarray['firstname'] = $contactName;
        $userarray['country'] = $country;
        $userarray['city'] = $city;
        $userarray['mobile'] = $mobile;
		$userarray['sourceurl'] = $sourceurl;
		$userarray['sourcename'] = $sourcename;
		$userarray['resolution'] = $resolution;
		$userarray['IsdCode'] = $isd_code;
		$userarray['bypassmobilecheck'] = true;

        $this->load->library('register_client');
        $regObj = new Register_client();
        $addResult = $regObj->adduser_new($userarray);
        //print_r($addResult);

        $userarray['busiCollegeName'] = $busiCollegeName;
        $userarray['busiType'] = $busiType;
        $userarray['contactName'] = $contactName;
        $userarray['contactAddress'] = $contactAddress;
        $userarray['pincode'] = $pincode;
        $userarray['mobile'] = $mobile;
        $userarray['categories'] = $categories;

	if($addResult['status'] > 0)
        {			        	
        	$userarray['userid'] = $addResult['status'];
        	$entObj = new Enterprise_client();
        	$addResult = $entObj->addEnterpriseUser($userarray);        	
        	$Validate = $this->checkUserValidation();
        	if(!isset($Validate[0]['userid'])){
				$value = $email.'|'.sha256($password) .'|' .'pendingverification';
				setcookie('user','',time() - 2592000 ,'/',COOKIEDOMAIN);
				setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);

                $_COOKIE['user'] = $value;
                $this->load->library('common/CookieBannerTrackingLib');
                $this->cookieBanner = new CookieBannerTrackingLib();
                $this->cookieBanner->newUserCookieSet();

				//changes done on 7/8/2013 by Nikita Jain
				$this->redirectEnterpriseUserToWelcomePage($userarray, $originalUserMobile);
		    	exit();		   	
        	}        	
        	
        	header('Location: /enterprise/Enterprise');
        	exit();
	     }
	     
	     //setcookie('user',$email."|".$mdpassword."|pendingverification", time() + 2592000 ,'/',COOKIEDOMAIN);
	     header('Location: /enterprise/Enterprise');
    }
    
    function redirectEnterpriseUserToWelcomePage($userarray, $originalUserMobile)
    {
	$this->load->builder("LocationBuilder", "location");
	
	$locationBuilder = new LocationBuilder();
	$locationRepo = $locationBuilder->getLocationRepository();
	
	$cityName = "";
	if(!empty($userarray['city']))
	{
	    $cityObject = $locationRepo->findCity($userarray['city']);
	    if(!empty($cityObject))
	    {
		$cityName   = $cityObject->getName();
	    }
	}

	$countryName = "";
	if(!empty($userarray['country']))
	{
	    $countryObject = $locationRepo->findCountry($userarray['country']);
	    if(!empty($countryObject))
	    {
		$countryName   = $countryObject->getName();
	    }
	}
	$userarray['city'] = $cityName;
	$userarray['country'] = $countryName;
	$userarray['originalUserMobile'] = $originalUserMobile;
	$this->mail_newEnterpriseUser($userarray);
	header('Location: /enterprise/Enterprise/welcome_user');
    }
    
    function welcome_user()
    {
	//$display['name'] = $this->input->get('name');
	//_p($display['username']);
	$this->load->view('enterprise/welcome_message');
    }

    function mail_newEnterpriseUser($data)
    {
	    //Mails sent after enterprise user registers
	    
	    $this->load->library('Alerts_client');
        $alertClient = new Alerts_client();
	    
		$missingFields = array();
		if(empty($data['originalUserMobile'])){
			$missingFields[] = "mobile";
		}
		
		if(empty($data['city'])){
			$missingFields[] = "city";
		}
		
		$missingFieldsData = array();
		$missingFieldsData['missing_fields'] = $missingFields;
		
	    // To the user
	    $user_email = $data['email'];
		$subject = "Welcome to Shiksha";
		$temp['userdata'] = $data;
		
	    $content = $this->load->view('enterprise/mail_to_user',$temp,true);
        $responsemail = $alertClient->externalQueueAdd(1,MAIL_SALES,$user_email,$subject,$content,"html");
        
		if(!empty($missingFields)){
			$subject = "Missing important information";
			$content = $this->load->view('enterprise/mail_missing_fields', $missingFieldsData, true);
			$responsemail = $alertClient->externalQueueAdd(1, MAIL_SALES, $user_email, $subject, $content,"html");
		}
		//To sales team
	    $subject = "New enterprise user registration ".$data['userid'];
        $cc = $GLOBALS['MAIL_SALES_CC'];
	    $ccString = "";
	    if(!empty($cc)){
			$ccString = implode(",", $cc);
	    }
	    	    
	    $content = $this->load->view('enterprise/mail_to_sales',$temp,true);
	    $responsemail = $alertClient->externalQueueAdd(1,MAIL_SALES,MAIL_SALES,$subject,$content,"html","0000-00-00 00:00:00",'n',array(),$ccString);
	    
	    // https://infoedge.atlassian.net/browse/LDB-4773	    
	    //To listing team
	    //$subject = "New enterprise user registration ".$data['userid'];
        //$temp['userdata']=$data;
	    //$cc = $GLOBALS['MAIL_LISTING_CC'];
	    //$ccString = "";	    	    
	    /*if(!empty($cc)){
			$ccString = implode(",", $cc);
	    }*/
	    //error_log("Mails2, ccString: ".$ccString);
	    //error_log("Mails2, cc: ".$cc);
		//$content = $this->load->view('enterprise/mail_to_listing',$temp,true);
		//$responsemail = $alertClient->externalQueueAdd(1,MAIL_SALES,MAIL_LISTING,$subject,$content,"html","0000-00-00 00:00:00",'n',array(),$ccString);
		
    }
    
    function getInstitutesForCity() {
        //$cmsUserInfo = $this->cmsUserValidation();
        $cityId = $this->input->post('cityId',true);
        $userid = $this->input->post('userId',true);
        $usergroup = $this->input->post('userGroup',true);

        $appId = 1;
        $this->init();
        $entObj = new Enterprise_client();
        $institutes = $entObj->getInstituteList($appId, $cityId,$usergroup,$userid);
        echo json_encode($institutes);
    }


    function getCitiesWithCollege(){
        //$cmsUserInfo = $this->cmsUserValidation();
        $countryId = $this->input->post('countryId',true);
        $userid = $this->input->post('userId',true);
        $usergroup = $this->input->post('userGroup',true);
        $appId = 1;
        $this->init();
        $entObj = new Enterprise_client();
        $cityList = $entObj->getCitiesWithCollege($appId,$countryId,$usergroup,$userid);
        echo json_encode($cityList);
    }

    function editNotification($notificationId)
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
        $this->init();
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $cmsPageArr['validity'] = $validity;
        $cmsPageArr['usergroup'] = $usergroup;
        $cmsPageArr['thisUrl'] = $thisUrl;
        //$cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
        $cmsPageArr['prodId'] = 3;

        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$notificationId,"notification");
        if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
        }

        $userid = $listingDetails[0]['userId'];

        $cmsPageArr['userid'] = $userid;
        $cmsPageArr['usergroup'] = $usergroup;
        $cmsPageArr['details'] = $listingDetails[0];
        $cmsPageArr['details']['sel_institute_id'] = $listingDetails[0]['instituteArr'][0]['institute_id'];
        $listingDetailsForCollege = $ListingClientObj->getListingDetails($appId,$cmsPageArr['details']['sel_institute_id'],"institute");
        $cmsPageArr['details']['sel_country_id'] = $listingDetailsForCollege[0]['locations'][0]['country_id'];
        $cmsPageArr['details']['sel_city_id'] = $listingDetailsForCollege[0]['locations'][0]['city_id'];

        //$cmsPageArr['details']['cities'] = $ListingClientObj->getCitiesWithCollege($appId,$cmsPageArr['details']['sel_country_id']);
        //$cmsPageArr['details']['institutes'] = $ListingClientObj->getInstituteList($appId,$cmsPageArr['details']['sel_city_id']);
        $entObj = new Enterprise_client();
        $cmsPageArr['details']['cities'] = $entObj->getCitiesWithCollege($appId,$cmsPageArr['details']['sel_country_id'],$usergroup,$userid);
        $cmsPageArr['details']['institutes'] = $entObj->getInstituteList($appId,$cmsPageArr['details']['sel_city_id'],$usergroup,$userid);

        //$cmsUserInfo = $this->cmsUserValidation();
        $cat_client = new Category_list_client();
        $categoryList = $cat_client->getCategoryTree($appId);
        foreach($categoryList as $temp)
        {
            $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
        }

        $cmsPageArr['details']['categories'] = $this->getCategories($categoryForLeftPanel);
        $cmsPageArr['countryList'] = $ListingClientObj->getCountries($appId);
        $cmsPageArr['listingType'] = "admission";

        $this->load->library ('sums_product_client');
        $objSumsProduct=new Sums_Product_client();
        $params = array();
        $cmsPageArr['productInfo'] = json_encode($objSumsProduct->getProductFeatures(1,$params));
        //echo '<pre>';print_r($cmsPageArr);echo '</pre>';

        $this->load->library('blog_client');
        $blogClient = new Blog_client();
        $examsList = $blogClient->getExamsForProducts($appId);
        $cmsPageArr['examsList'] = $examsList;
        $this->load->view('enterprise/editNotification',$cmsPageArr);

    }

    function getCategories($completeCategoryTree)
    {
       $arrBase = $this->getChilds($completeCategoryTree,1);
       $op = array();
       for ($i=0; $i<count($arrBase); $i++)
       {
	  array_push($op,array($arrBase[$i][0],$arrBase[$i][1],'base'));
	  $op = $this->getChildString($arrBase[$i][0],$op,$completeCategoryTree);
       }
       return $op;
    }

    function getChildString($catid,$op,$completeCategoryTree)
    {
       $options = "";
       $arrBase = $this->getChilds($completeCategoryTree,$catid);
       $other= "";
       for ($i=0; $i<count($arrBase); $i++)
       {
	  $str = $arrBase[$i][1];
	  if (substr($str,0,5) != "Other")
	  array_push($op,array($arrBase[$i][0],$arrBase[$i][1],'child'));
	  else
	  $other = array($arrBase[$i][0],$arrBase[$i][1],'child');
       }
       if (is_array($other))
       {
	  array_push($op,$other);
       }
       return $op;
    }

    function getChilds($categoryTree,$categoryId)
    {
       $arr = array();
       foreach($categoryTree as $catId=>$catIdVal)
       {
	  if($catIdVal[1] ==$categoryId)
	  {
	     array_push($arr,array($catId,$categoryTree[$catId][0]));
	  }
       }
       return $arr;
    }

    function removeScholMedia($scholarshipId,$docId){
        $appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];
        $this->init();

        $ListingClientObj = new Listing_client();
        $listingDetails = $ListingClientObj->getListingDetails($appId,$scholarshipId,"scholarship");
        if($usergroup == "enterprise"){
            if($listingDetails[0]['userId'] != $userid){
                header("location:/enterprise/Enterprise/disallowedAccess");
                error_log_shiksha("Failed Remove Rights check !!");
                exit();
            }
        }
        error_log_shiksha("Passed Remove Rights check !!");
        $EnterpriseClientObj = new Enterprise_client();

        $removeMediaArr = array();
        $removeMediaArr['userid']= $userid;
        $removeMediaArr['usergroup']= $usergroup;
        $removeMediaArr['docId']= $docId;
        $removeMediaArr['scholarshipId']= $scholarshipId;

        $deleteResponse = $EnterpriseClientObj->RemoveScholarshipDoc($appId,$removeMediaArr);
        //      error_log_shiksha("===SS===".print_r($deleteResponse,true));
        //echo json_encode($eventData);
        echo json_encode($deleteResponse);
    }

    function editNotificationSubmit()
    {
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    	$this->init();
    	$appId = '1';
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];

        $this->load->library('upload_client');
        $this->load->library('listing_client');

    	//echo "<pre>";print_r($_POST);echo "<pre>";

    	$editNotificationData['admission_notification_id'] = $this->input->post('admission_notification_id',true);
    	$editNotificationData['admission_notification_name'] = $this->input->post('a_name',true);
    	$editNotificationData['short_desc'] = $this->input->post('a_desc',true);
    	$editNotificationData['admission_year'] = $this->input->post('a_year',true);
    	$editNotificationData['application_brochure_start_date'] = $this->input->post('a_app_bro_start',true);

    	$editNotificationData['application_brochure_end_date'] = $this->input->post('a_app_bro_end',true);
    	$editNotificationData['application_end_date'] = $this->input->post('a_app_last',true);
    	$editNotificationData['application_procedure'] = $this->input->post('a_app_proc',true);
    	$editNotificationData['fees'] = $this->input->post('a_app_fees',true);
    	$editNotificationData['entrance_exam'] = $this->input->post('a_exam',true);

                if($editNotificationData['entrance_exam'] == "yes" ) {
            //TEST REQUIRED FOR COURSE
            $tests_required = $this->input->post('examSelected',true);
            $testsArray = array();
            $j=0;
            $flag_test_required_other = 0;
            for ($i=0;$i<count($tests_required);$i++)
            {
                if ($tests_required[$i]!="-1")
                {
                    $testsArray[$j] = $tests_required[$i];
                    $j++;
                }
                else
                {
                    $flag_test_required_other = 1;
                }
            }
            $editNotificationData['tests_required'] = implode(",",$testsArray);
            if ($flag_test_required_other == 1)
            {
                $editNotificationData['tests_required_other'] = 'true';
                $editNotificationData['exam_name'] = $this->input->post('a_exam_name',true);
                $editNotificationData['exam_date'] = $this->input->post('a_exam_date',true);
                $editNotificationData['exam_duration'] = $this->input->post('a_exam_duration',true);
                $editNotificationData['exam_timings'] = $this->input->post('a_exam_timing',true);
                $editNotificationData['numOfCentres'] = $this->input->post('a_num_exam_centre',true);
                $addressLine1 = $this->input->post("a_address_line1",true);
                $addressLine2 = $this->input->post("a_address_line2",true);
                $countryId = $this->input->post("a_country_id",true);
                $cityId = $this->input->post("a_city_id",true);
                $zip = $this->input->post("a_zip",true);
                for($i = 0 ; $i <  $editNotificationData['numOfCentres']; $i++){
                    $editNotificationData['address_line1'.$i] = $addressLine1[$i];
                    $editNotificationData['address_line2'.$i] = $addressLine2[$i];
                    $editNotificationData['country_id'.$i] = $countryId[$i];
                    $editNotificationData['city_id'.$i] = $cityId[$i];
                    $editNotificationData['zip'.$i] = $zip[$i];
                }
            }
        }


        $editNotificationData['main_country_id'] = $this->input->post('a_country',true);
    	$editNotificationData['main_city_id'] = $this->input->post('a_city',true);

    	$editNotificationData['category_id'] = implode(',',$this->input->post('c_categories',true));
    	//$editNotificationData['username'] = $userid;
    	$editNotificationData['institute_id'] = $this->input->post('a_institute');

    	if ($this->input->post('s_elg_minqual')) {
    		$eligibility['minqual'] = $this->input->post('s_elg_minqual',true);
    	}
    	if ($this->input->post('s_elg_age')) {
    		$eligibility['age'] = $this->input->post('s_elg_age',true);
    	}
    	if ($this->input->post('s_elg_marks')) {
    		$eligibility['marks'] = $this->input->post('s_elg_marks',true);
    	}
    	if ($this->input->post('s_elg_res_stat')) {
    		$eligibility['res_stat'] = $this->input->post('s_elg_res_stat',true);
    	}

    	$editNotificationData['contact_name'] = $this->input->post('s_contact_name');
    	$editNotificationData['contact_address'] = $this->input->post('s_contact_add');
    	$editNotificationData['contact_cell'] = $this->input->post('s_phone_no');
    	$editNotificationData['contact_email'] = $this->input->post('s_email');
    	$editNotificationData['contact_fax'] = $this->input->post('s_fax_no');

    	//echo "<pre>";print_r($editNotificationData);echo "</pre>";
	$entObj = new Enterprise_client();
	$result = $entObj->editNotification(1,$editNotificationData,$eligibility);

    	$eventIds = $entObj->getNotificationEvents(1,$editNotificationData['admission_notification_id']);
    	$this->load->library('event_cal_client');
    	$eventObj = new Event_cal_client();
    	if (is_array($eventIds)) {
	    	foreach ($eventIds as $eventId)
	    	{
				$eventObj->deleteEvent(1,$eventId);
	    	}
    	}

        $ListingClientObj = new Listing_client();
        $joinGroupInfo = $ListingClientObj->getJoinGroupInfo($appId,$editNotificationData['institute_id']);
        $locationsArr = $joinGroupInfo[0]['locations'];
        $collegeName = $joinGroupInfo[0]['instituteName'];
        $collegeName = str_replace('-',' ',$collegeName);

        $optionalArgs = array();
        for($i=0;$i<count($locationsArr);$i++) {
            $optionalArgs['location'][$i] = $locationsArr[$i]['cityName']."-".$locationsArr[$i]['countryName'];
        }

        $instituteUrl = getSeoUrl($editNotificationData['institute_id'],"institute",$joinGroupInfo[0]['instituteName'],$optionalArgs);
    	//add event if dates are set
    	if ($this->input->post('a_app_bro_start'))
    	{
    		$eventArray = array();
    		$eventArray['email'] = ($editNotificationData['contact_email']!="")?$editNotificationData['contact_email']:ADMIN_EMAIL;
            $eventArray['description'] = $editNotificationData['short_desc'];
            if(strlen($editNotificationData['application_procedure']) > 0 ){
                $eventArray['description'] .= " <br/><b>Application Process:</b> ".$this->input->post('a_app_proc',true)." <br/>";
            }
            if(strlen($editNotificationData['fees']) > 0){
                $eventArray['description'] .= " <b> Fees: </b> ".$this->input->post('a_app_fees',true)." <br/>";
            }
            if(strlen($joinGroupInfo[0]['instituteName'])>0){
                $eventArray['description'] .= " <br/> <br/> <b><a href='$instituteUrl'> ".$joinGroupInfo[0]['instituteName']."</a> </b> <br/>";
            }

    		$eventArray['user_id'] = 1;
            $cats = $_REQUEST['c_categories'];
            $eventArray['board_id'] = $cats[0];
            for($i = 1; $i < count($cats); $i++){
                $eventArray['board_id'] = ",".$cats[$i];
            }

            $eventArray['contact_person'] = $editNotificationData['contact_name'];
    		$eventArray['fax'] = $editNotificationData['contact_fax'];
    		$eventArray['phone'] = $editNotificationData['contact_cell'];
    		$eventArray['start_date'] = $this->input->post('a_app_bro_start');
    		$eventArray['end_date'] = $this->input->post('a_app_bro_end')?$this->input->post('a_app_bro_end'):$eventArray['start_date']." 00:01:00";
            $eventArray['end_date'] = $this->input->post('a_app_last')?$this->input->post('a_app_last'):$eventArray['end_date'];
    		$eventObj = new Event_cal_client();
            $eventTitle = str_replace('-',' ',$editNotificationData['admission_notification_name'])." - $collegeName - Sale of forms";
            $eventTitle .=" - ".date(' d F ',strtotime($editNotificationData['application_brochure_start_date']));
            $eventTitle = $this->input->post('a_app_last')? $eventTitle." - Submission of forms till "." - ".date(' d F ',strtotime($editNotificationData['application_end_date'])): $eventTitle;
            $eventArray['event_title'] = $eventTitle;
            $eventArray['examSelected'] = array($this->input->post('examSelected', true),'struct');//Ashish
            $locations = array();
            for($i=0;$i<count($locationsArr);$i++) {
                array_push($locations,array(
                            array(
                                'Address_Line1'=>array($locationsArr[$i]['address'],'string'),
                                'city'=>array($locationsArr[$i]['cityId'],'string'),
                                'zip'=>array($locationsArr[$i]['zip'],'string'),
                                'country'=>array($locationsArr[$i]['countryId'],'string'),
                                'email'=>array($eventArray['email'],'string'),
                                'contact_person'=>array($eventArray['contact_person'],'string'),
                                'fax' => array($editNotificationData['contact_fax'],'string'),
                                'phone' => array($editNotificationData['contact_cell'],'string')
                                ),'struct')
                        );//close array_push
            }
            $this->load->library('message_board_client');
            $msgbrdClient = new Message_board_client();
            $topicDescription = "You can discuss on this event below";
            $requestIp = S_REMOTE_ADDR;
            $topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,$eventArray['board_id'],$requestIp,'event');
            $eventArray['threadId'] = $topicResult['ThreadID'];
            $eventResponse = $eventObj->addEventNew($appId,$eventArray,1,$locations,$editNotificationData['admission_notification_id'],'notification');
        }
        if($editNotificationData['entrance_exam'] == "yes" ) {
    		if ($editNotificationData['exam_date']!="") {
    			if ($editNotificationData['numOfCentres']>0)
    			{
                    $locations = array();
                    $this->load->library('event_cal_client');
                    $eventObj = new Event_cal_client();
                    $eventArray = array();
                    $eventArray['email'] = ($editNotificationData['contact_email']!="")?$editNotificationData['contact_email']:ADMIN_EMAIL;
                    $eventArray['description'] = $editNotificationData['short_desc'];
                    if(strlen($editNotificationData['application_procedure']) > 0 ){
                        $eventArray['description'] .= " <br/><b>Application Process:</b> ".$this->input->post('a_app_proc',true)." <br/>";
                    }
                    if(strlen($editNotificationData['fees'])> 0 ){
                        $eventArray['description'] .= " <b> Fees: </b> ".$this->input->post('a_app_fees',true)." <br/>";
                    }
                    if(strlen($joinGroupInfo[0]['instituteName'])>0){
                        $eventArray['description'] .= " <br/> <br/> <b><a href='$instituteUrl'> ".$joinGroupInfo[0]['instituteName']."</a> </b> <br/>";
                    }

                    $eventArray['user_id'] = 1;
                    $eventArray['contact_person'] = $editNotificationData['contact_name'];
                    $eventArray['fax'] = $editNotificationData['contact_fax'];
                    $eventArray['phone'] = $editNotificationData['contact_cell'];
                    $cats = $_REQUEST['c_categories'];
                    $eventArray['board_id'] = $cats[0];
                    for($i = 1; $i < count($cats); $i++){
                        $eventArray['board_id'] = ",".$cats[$i];
                    }

                    $str = $editNotificationData['admission_notification_name'];
                    $exam_name = $editNotificationData['exam_name']?$editNotificationData['exam_name']:' Exam ';
                    $eventArray['event_title'] = "$exam_name - Date ".date(' d F Y ',strtotime($editNotificationData['exam_date']))." - ".str_replace('-',' ',$str)." - $collegeName";
                    $eventArray['start_date'] = $editNotificationData['exam_date'];
                    $eventArray['end_date'] = $editNotificationData['exam_date'];
                    for($i=0;$i<$editNotificationData['numOfCentres'];$i++) {
                        array_push($locations,array(
                            array(
                                'Address_Line1'=>array($editNotificationData['address_line1'.$i],'string'),
                                'city'=>array($editNotificationData['city_id'.$i],'string'),
                                'zip'=>array($editNotificationData['zip'.$i],'string'),
                                'country'=>array($editNotificationData['country_id'.$i],'string'),
                                'email'=>array($eventArray['email'],'string'),
                                'contact_person'=>array($eventArray['contact_person'],'string'),
                                'fax' => array($editNotificationData['contact_fax'],'string'),
                                'phone' => array($editNotificationData['contact_cell'],'string')
                                ),'struct')
                        );//close array_push
    				}

                    $this->load->library('message_board_client');
                    $msgbrdClient = new Message_board_client();
                    $topicDescription = "You can discuss on this event below";
                    $requestIp = S_REMOTE_ADDR;
                    $topicResult = $msgbrdClient->addTopic($appID,1,$topicDescription,$eventArray['board_id'],$requestIp,'event');
                    $eventArray['threadId'] = $topicResult['ThreadID'];

                    error_log_shiksha("NEWEVENT ".print_r($locations,true));
                    $eventResponse = $eventObj->addEventNew($appId,$eventArray,3,$locations,$editNotificationData['admission_notification_id'],'notification');
                    error_log_shiksha("NEWEVENT ADD ADMISSION LISTING : create event for exam centre RESPONSE : " .print_r($eventResponse,true));
    			}
    		}
    	}

    	$maxDocs = 3;
    	$uploadClient = new Upload_client();
    	$ListingClientObj = new Listing_client();
    	if($maxDocs>0)
    	{
    		if(($_FILES['a_app_forms']))
    		{
    			error_log_shiksha("Edit ADMISSION LISTING : upload media called ".print_r($_FILES,true));
    			$arrCaption = $this->input->post('a_app_forms_caption');
    			$docCaption = array();
    			for($i=0;$i<count($_FILES['a_app_forms']['name']);$i++){
    				$docCaption[$i] = ($arrCaption[$i]!="")?$arrCaption[$i]:$_FILES['a_app_forms']['name'][$i];
    			}

    			$upload_forms = $uploadClient->uploadFile($appId,'pdf',$_FILES,$docCaption,$editNotificationData['admission_notification_id'],"notification",'a_app_forms');
    			error_log_shiksha("ADD ADMISSION LISTING : uploading docs response:::".print_r($upload_forms,true));
    			if($upload_forms['status'] == 1){
    				for($k = 0;$k < $maxDocs ; $k++){
    					$reqArr = array();
    					$reqArr['mediaid']=$upload_forms[$k]['mediaid'];
    					$reqArr['url']=$upload_forms[$k]['imageurl'];
    					$reqArr['title']=$upload_forms[$k]['title'];
    					$reqArr['thumburl']=$upload_forms[$k]['thumburl'];
    					error_log_shiksha("ADD ADMISSION LISTING updatemediacontent before");
    					$updateAdmission = $ListingClientObj->updateMediaContent($appId,$editNotificationData['admission_notification_id'],'notification','doc',$reqArr);
    					error_log_shiksha("ADD ADMISSION LISTING : updating admissions response:::".print_r($updateAdmission,true));
    				}
    			}
    		}
    	}
	$ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://".SHIKSHACLIENTIP."/ListingScripts/indexListing/".$editNotificationData['admission_notification_id']."/notification");

        curl_setopt($ch, CURLOPT_VERBOSE, 1); // set url to post to
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); // return into a variable
        curl_setopt($ch, CURLOPT_HTTPHEADER, Array("Content-Type: text/xml"));
        curl_setopt($ch, CURLOPT_HEADER, 1);
        curl_setopt($ch, CURLOPT_TIMEOUT, 40); // times out after 4s
        curl_setopt($ch, CURLOPT_POSTFIELDS, $content); // add POST fields
        curl_setopt($ch, CURLOPT_POST, 1);
        //$result = curl_exec($ch); // run the whole process
        curl_close($ch);

    	$displayData['response']= $result;
    	$this->load->view('enterprise/updateResultPageCMS',$displayData);
    	//header('Location:/enterprise/Enterprise/index/3');
    }

    function removeNotificationDoc($docId,$notificationId)
    {
		$this->init();
		$entObj = new Enterprise_client();
		$request = array (1,$docId,$notificationId);
		$response = $entObj->removeNotificationDoc($request);
		echo json_encode($response);
    }

    function profile()
    {
    	$this->init();
    	$cmsUserInfo = $this->cmsUserValidation();
    	$cmsUserInfo['prodId'] = 14;
    	$appId =1;
	    $validity = $cmsUserInfo['validity'];
	    $cmsUserInfo['validateuser'] = $validity;

    	//echo "<pre>";print_r($cmsUserInfo);echo "</pre>";
    	$entObj = new Enterprise_client();
    	$cmsUserInfo['details'] = $entObj->getEnterpriseUserDetails(1,$cmsUserInfo['userid']);    	
    	//_P($cmsUserInfo['details']);
	// Start Online form change by pranjul 13/10/2011
    	$this->load->library('OnlineFormEnterprise_client');
    	$ofObj = new OnlineFormEnterprise_client();
    	$cmsUserInfo['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($cmsUserInfo['userid']);
    	
    	// End Online form change by pranjul 13/10/2011
    	/*$cat_client = new Category_list_client();
    	$categoryList = $cat_client->getCategoryTree($appId);
    	foreach($categoryList as $temp)
    	{
    		$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
    	}
    	$cmsUserInfo['data']['categories'] = $this->getCategories($categoryForLeftPanel);*/
    	
    	$ListingClientObj = new Listing_client();
    	$cmsUserInfo['data']['countryList'] = $ListingClientObj->getCountries($appId);    	
    	//echo $cmsUserInfo['details']['country'];    	
    	foreach ($cmsUserInfo['data']['countryList'] as $c)
    	{
    		if ($c['countryName']==$cmsUserInfo['details']['countryName']) {
				$countryId = $c['countryID'];
				break;
			}
    	}
    	
    	if(empty($countryId)) {
				$countryId=2;
		}
    	
    	if ($cmsUserInfo['details']['country']!="") {						
			$cmsUserInfo['data']['cityList'] = $ListingClientObj->getCityList($appId,$countryId);      						
		}
						
		if(is_string($cmsUserInfo['details']['city'])) {
			foreach($cmsUserInfo['data']['cityList'] as $city_list) {
					if($cmsUserInfo['details']['city'] == $city_list['cityName']) {
							$cmsUserInfo['details']['city'] = $city_list['cityID'];
							break;
					}
			}
		}
		
		if(intval($cmsUserInfo['details']['city']) == 0) {
			unset($cmsUserInfo['details']['city']);		
		}
		
    	$this->load->view('enterprise/profilePage',$cmsUserInfo);
    }

    function profileEdit()
    {
    	$this->init();

        if(!verifyCSRF()) { return false; }
    	$cmsUserInfo = $this->cmsUserValidation();
    	//echo "<pre>";print_r($_POST);echo "</pre>";
    	$country = $this->input->post('countries',true);
    	$this->load->library('listing_client');
        $ListingClientObj = new Listing_client();
        $countryList = $ListingClientObj->getCountries($appId);
		
		/*
        foreach ($countryList as $countryVal)
        {
        	if ($countryVal['countryID']==$country)
        	{
        		$country = $countryVal['countryName'];
        	}
        }
		*/
    	$request['userId'] = $cmsUserInfo['userid'];
    	$request['categories']= implode(',',$this->input->post('c_categories',true));
    	$request['contactName'] = $this->security->xss_clean($this->input->post('contactName',true));
    	$request['contactAddress'] = $this->security->xss_clean($this->input->post('contact_address',true));

        $city = trim($this->security->xss_clean($this->input->post('cities')));
		/*
        if($city == "-1") //Case of Other city
        {
            $cityArray = array();
            $cityArray['country_id'] = trim($this->input->post('countries'));
            $cityArray['city_name'] = htmlentities(addslashes(trim($this->input->post('otherCity'))));
            $this->load->library('listing_client');
            $ListingClientObj = new Listing_client();
            $city = $ListingClientObj->insertCity($appId,$cityArray);
        }
		*/
        $request['city'] = $city;
    	$request['country'] = $country;
    	$request['pincode'] = $this->security->xss_clean($this->input->post('pincode',true));
    	$request['mobile'] = $this->security->xss_clean($this->input->post('mobile',true));
    	//echo "<pre>";print_r($request);echo "</pre>";
    	$entObj = new Enterprise_client();
        $res = $entObj->updateEnterpriseUserDetails(1,$request);
    	//print_r($res);
	setcookie('profileEdit',"1",0 ,'/',COOKIEDOMAIN);
    	header("Location:/enterprise/Enterprise/profile");
    }

    function changePassword()
    {
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$cmsUserInfo['prodId'] = 14;
		$entObj = new Enterprise_client();
    	$cmsUserInfo['details'] = $entObj->getEnterpriseUserDetails(1,$cmsUserInfo['userid']);
		$this->load->view('enterprise/changePassword',$cmsUserInfo);
	}

	function changePasswordSubmit()
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$appId = 1;
		$userId = $cmsUserInfo['userid'];
		$currentPassword = $this->input->post('oldPassword',true);
		$newPassword = $this->input->post('newPassword',true);
		
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$status = $registerClient->changePassword($appId,$userId,sha256($currentPassword),sha256($newPassword),$newPassword);
		
		if ($status == 1) {
			$values = explode("|",$_COOKIE["user"]);
			$email = $values[0];
			$value = $email.'|'.sha256($newPassword) .'|' .$values[2];
			setcookie('user','',time() - 2592000 ,'/',COOKIEDOMAIN);
			setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
			$_COOKIE["user"] = $value;
			header('Location:/enterprise/Enterprise/changePasswordSuccess');
		} else {
			header('Location:/enterprise/Enterprise/changePasswordSuccess/1');
		}
	}

	function changePasswordSuccess($error=0)
	{
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
		$cmsUserInfo['prodId'] = 14;
		$entObj = new Enterprise_client();
		$cmsUserInfo['details'] = $entObj->getEnterpriseUserDetails(1,$cmsUserInfo['userid']);
		$cmsUserInfo['error'] = $error;
		$this->load->view('enterprise/changePasswordSuccess',$cmsUserInfo);
	}

	function migrateUser()
	{
		//$this->init();
		$user = $this->checkUserValidation();
		if((!is_array($user)) && ($user == "false"))
		{
			header('Location:/enterprise/Enterprise/loginEnterprise');
			exit();
		}
		$data['user'] = $user[0];
		
		/*$this->load->library('category_list_client');
		$cat_client = new Category_list_client();
    	$categoryList = $cat_client->getCategoryTree($appId);
    	foreach($categoryList as $temp)
    	{
    		$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
    	}
    	$data['categories'] = $this->getCategories($categoryForLeftPanel);
		
		$categoryList = $cat_client->getCategoryTree($appId,'','national');
		foreach($categoryList as $temp)
		{
			$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
		}
		$data['completeCategoryTreeIndia'] = json_encode($categoryForLeftPanel);

		$categoryList = $cat_client->getCategoryTree($appId,'','studyabroad');
		$categoryForLeftPanel = array();
		foreach($categoryList as $temp)
		{
			$categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId']);
		}

		$data['completeCategoryTreeAbroad'] = json_encode($categoryForLeftPanel);*/
				
		//echo "<pre>";print_r($data);echo "</pre>";
		$this->load->view('enterprise/migrateUser',$data);
	}

	function submitMigrateUser()
	{
		//echo "<pre>";print_r($_POST);echo "</pre>";
		$user = $this->checkUserValidation();
		$data['userid'] = $user[0]['userid'];
		$data['busiCollegeName'] = $this->input->post('busiCollegeName',true);
		$data['busiType'] = $this->input->post('busiType',true);
        $data['contactAddress'] = $this->input->post('contact_address',true);
        $data['pincode'] = $this->input->post('pincode',true);
        $data['categories'] = implode(',',$this->input->post('c_categories',true));
        /* Add Privacy checkboxes */
		$viamobile = $this->input->post('viamobile');
		$viamail = $this->input->post('viaemail');
		$newsletteremail = $this->input->post('newsletteremail');
		if($viamobile == "mobile")
			$viamobile = 1;
		else
			$viamobile = 0;

		if($viamail == "email")
			$viamail = 1;
		else
			$viamail = 0;

		if($newsletteremail == "newsletteremail")
			$newsletteremail = 1;
		else
			$newsletteremail = 0;

                        $tuserData['userid'] = $data['userid'];
                        $tuserData['viamobile'] = $viamobile;
                        $tuserData['viaemail'] = $viamail;
                        $tuserData['newsletteremail'] = $newsletteremail;
                        /*
                        $this->load->library('register_client');
                        $regObj = new Register_client();
                        $addResult = $regObj->updateuserinfo($data);
                        */

                        /* Add Privacy checkboxes */
                        $this->load->library('enterprise_client');
                        $entObj = new Enterprise_client();
                        $res = $entObj->addEnterpriseUser($data);
                        if (is_array($res))
                        {
                            $entObj->updateUserGroup(1,$tuserData);
                        }

	   //insert in queue for partial indexing
        $user_response_lib = $this->load->library('response/userResponseIndexingLib');                          
        $extraData = "{'personalInfo:true'}";
        $user_response_lib->insertInIndexingQueue($data['userid'], $extraData);

		//changes done by nikita jain on 04sept'13
		$userdata = $entObj->getEnterpriseUserDetails(1, $data['userid']);
		$userdata['busiCollegeName'] = $userdata['businessCollege'];
		$userdata['busiType'] 		 = $userdata['businessType'];
		$this->redirectEnterpriseUserToWelcomePage($userdata, $userdata['mobile']);
		exit();
        //header("Location:/enterprise/Enterprise");
		//exit;
	}

        function prodAndServ()
        {
	    	header ('HTTP/1.1 301 Moved Permanently');
	    	header("Location:/enterprise/Enterprise/loginEnterprise");
	    	exit;   
	    	global $logged;
            $this->init();

            $this->load->library('paymentClient');
            $validity = $this->checkUserValidation();
            $cmsUserInfo;
            if(!(($validity == "false" )||($validity == ""))) {
                error_log_shiksha("HERE");
                $cmsUserInfo = $this->cmsUserValidation();
            }
            $validateuser = $cmsUserInfo['validity'];

            $displayData = array();
            //$displayData['productDataList'] = $productDataList;
            $displayData['logged'] = $logged;
            $displayData['validateuser'] = $validateuser;
            $displayData['headerTabs'] =  $cmsUserInfo['headerTabs'];
            $displayData['myProducts'] = $cmsUserInfo['myProducts'];
            $displayData['usergroup'] = $cmsUserInfo['usergroup'];
            $displayData['prodId'] = 10;

            $this->load->view('enterprise/prodAndServ',$displayData);
        }

        function getMediaData($typeofmedia,$startfrom,$count,$startDate,$endDate)
    {
        $this->init();
        $entObj = new Enterprise_client();
        $countofMedia = $entObj->getcountofMedia(1,$typeofmedia,'user',$startDate,$endDate);
        $mediadata = $entObj->getMediaData(1,$typeofmedia,$startfrom,$count,$startDate,$endDate);
        $mediadata = array('results' =>$mediadata,'totalCount'=>$countofMedia);
        echo json_encode($mediadata);

    }

        function deleteMediaData($type,$userids)
    {
        $this->init();
        $entObj = new Enterprise_client();
        $result = $entObj->deleteMediaData(1,$type,$userids);
        echo $result;

    }

    function searchUserForListingPost($prodId=22, $extraInfoArray="", $validity_check = 'Y')
    {
        $startTime = microtime(true);
        $this->init();
        $data['cmsUserInfo'] = $this->cmsUserValidation($validity_check);
        
        if($validity_check == 'Y') {
            if($data['cmsUserInfo']['usergroup']!='cms'){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
    		
    		$isMMM = FALSE;
            $mailerModel = $this->load->model('mailer/mailermodel');
            $userData = $mailerModel->isUserinGroup($data['cmsUserInfo']['userid']);
    		if(!empty($userData)) {
    			$isMMM = TRUE;
    		}
        } else {
            $isMMM = TRUE;
        }
		
        $data['extraInfoArray'] = urlencode(htmlentities(strip_tags($extraInfoArray)));
        $data['forListingPost'] = true;
        $data['prodId'] = $prodId;
		$data['isMMM'] = $isMMM;
        $data['validity_check'] = $validity_check;
        $this->load->view('enterprise/userSelect',$data);
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

        function getUsersForQuotation()
        {
            $startTime = microtime(true);
            error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
            $validity_check = $this->input->post('validity_check',true);
            $response['validity_check'] = $validity_check;
		    $this->init();
            $request['cmsUserInfo'] = $this->cmsUserValidation($validity_check);
            if($validity_check == 'Y') {
                if($request['cmsUserInfo']['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
                }
            }
		    $request['email'] = $this->input->post('email',true);
		    $request['displayname'] = $this->input->post('displayname',true);
		    $request['collegeName'] = $this->input->post('collegeName',true);
		    $request['contactName'] = $this->input->post('contactName',true);
		    $request['contactNumber'] = $this->input->post('contactNumber',true);
            $request['clientId'] = $this->input->post('clientId',true);
		    $objSumsManage = new Sums_Manage_client();
            $response['users'] =  $objSumsManage->getUserForQuotation($this->appId,$request);
            $response['forListingPost'] = $this->input->post('forListingPost',true);
            $response['cmsUserId'] = $request['cmsUserInfo']['userid'];
        	$response['flag_listing_upgrade'] = $this->input->post('flag_listing_upgrade',true);

            $mailerModel = $this->load->model('mailer/mailermodel');
            $userData = $mailerModel->isUserinGroup($request['cmsUserInfo']['userid']);
            $response['userData'] = $userData;
            $response['usergroup'] = $request['cmsUserInfo']['usergroup'];
            $this->load->view('enterprise/usersForQuotation',$response);
	   if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
    }

        




	function paypalreturn()
	{
	     $cmsUserInfo = $this->cmsUserValidation();
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $thisUrl = $cmsUserInfo['thisUrl'];
            $validity = $cmsUserInfo['validity'];	    
	
	    $cmsPageArr = array();
	    $cmsPageArr['userid'] = $userid;
            $cmsPageArr['usergroup'] = $usergroup;
            $cmsPageArr['thisUrl'] = $thisUrl;
            $cmsPageArr['validateuser'] = $validity;
	    $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
            $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];

	    
	    $arr = split("_",$_POST['item_number']);
	    
	    $transactionid = $arr[0];
	    $returnPaymentId = $arr[1];
	    $returnPartPaymentId = $arr[2];
	    $key = $arr[3];
	    	    
	    if ($_POST['payment_status']=="Completed" || $_POST['payment_status']=="Pending" || $_POST['payment_status']=="Processed")
	    {
	      $flag =1;
	      $response['paymentData'] = array();
	      $mysqldate = date( 'Y-m-d H:i:s');
	      $this->load->library('Subscription_client');
	      $objSumsManage = new Subscription_client();
	      $response['paymentData'] =  $objSumsManage->updatePayPalPaymentDetails($returnPaymentId,$returnPartPaymentId,$_POST['item_number'],$mysqldate,$userid,$key,$flag); 
	      $cmsPageArr['paymentId'] = $returnPaymentId;
	      $this->load->view('enterprise/creditPaymentPaid',$cmsPageArr);
	    }
	    elseif($_POST['payment_status']=="Failed")
	    {
		
	      $flag =2;	
	      $mysqldate = date( 'Y-m-d H:i:s');
	      $this->load->library('Subscription_client');
	      $objSumsManage = new Subscription_client();
	      $response['paymentData'] =  $objSumsManage->updatePayPalPaymentDetails($returnPaymentId,$returnPartPaymentId,$_POST['item_number'],$mysqldate,$userid,$key,$flag); 
	    
		
		$this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);
		
	    }
	    else
	    {
		$this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);	
	    }
		
	    
	}

		
	function ipn()
	{
		// Payment has been received and IPN is verified.  This is where you
		// update your database to activate or process the order, or setup
		// the database with the user's order details, email an administrator,
		// etc. You can access a slew of information via the ipn_data() array.
 
		// Check the paypal documentation for specifics on what information
		// is available in the IPN POST variables.  Basically, all the POST vars
		// which paypal sends, which we send back for validation, are now stored
		// in the ipn_data() array.
 
		// For this example, we'll just email ourselves ALL the data.
		$to    = 'paypal@shiksha.com';    //  your email

		if ($this->paypal_lib->validate_ipn()) 
		{
			$body  = 'An instant payment notification was successfully received from ';
			$body .= $this->paypal_lib->ipn_data['payer_email'] . ' on '.date('m/d/Y') . ' at ' . date('g:i A') . "\n\n";
			$body .= " Details:\n";

			foreach ($this->paypal_lib->ipn_data as $key=>$value)
				$body .= "\n$key: $value";
	
			// load email lib and email results
			$this->load->library('email');
			$this->email->to($to);
			$this->email->from($this->paypal_lib->ipn_data['payer_email'], $this->paypal_lib->ipn_data['payer_name']);
			$this->email->subject('CI paypal_lib IPN (Received Payment)');
			$this->email->message($body);	
			$this->email->send();
		}
	}	
		

function  ccavenueindianresponse()
{
    	    
        $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];	 

        $cmsPageArr = array();
        $cmsPageArr['userid'] = $userid;
        $cmsPageArr['usergroup'] = $usergroup;
        $cmsPageArr['thisUrl'] = $thisUrl;
        $cmsPageArr['validateuser'] = $validity;
        $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];   

	    // $paymentIdArr = split("_",$_POST[Order_Id]);	    
	    // $returnPaymentId = $paymentIdArr[1];
	    // $returnPartPaymentId= $paymentIdArr[2];
	    // $key = $paymentIdArr[3];

	    // $this->load->library('ccavenue_rupeegateway_lib');
	    // $object = new ccavenue_rupeegateway_lib();
	    // $check=$object->calculateverifyCheckSum($_POST[Order_Id],$_POST[Amount],$_POST[AuthDesc],$_POST[Checksum]);

        $this->load->library('subscription_client');
        $objSumsManage =  new Subscription_client();
        $status = "Failed";

        $encResp = $this->input->post('encResp');
        $orderNo = $this->input->post('orderNo');

        if((!empty($encResp)) && (!empty($orderNo))) {

            $this->config->load('ccavenue_PaymentGatewayINR_settings',TRUE);
            $working_key = $this->config->item('working_key','ccavenue_PaymentGatewayINR_settings');            
    
            $this->load->library('Online/payment/PaymentProcessor');
            $paymentProcessor = new PaymentProcessor();
            $post_field = $paymentProcessor->decrypt($encResp, $working_key);
            $dump_post_fields = $post_field;

            $post_field = explode('&', $post_field);

            foreach ($post_field as  $value) {
                $value = explode('=', $value);
                $new_post_fields[$value[0]] = $value[1];
            }

            $orderId = explode("-",$orderNo);

            $returnTransactionId = $orderId[0];
            $returnPaymentId = $orderId[1];
            $returnPartPaymentId = $orderId[2];
            $returnLogTableId = $orderId[3];    	          

            if($returnPaymentId > 0 && $returnPartPaymentId > 0 && $returnTransactionId > 0 && $returnLogTableId > 0) {

                if ($new_post_fields['order_status'] == 'Success') {

                    $paymentdetailsarray = $objSumsManage->getCreditCardPaymentDetails($userid,$returnPaymentId,$returnPartPaymentId);

                    if(!empty($paymentdetailsarray)) {
                        foreach($paymentdetailsarray as $traversalarray){
                            if($traversalarray['Payment_Id'] == $returnPaymentId  && $traversalarray['Part_Number'] == $returnPartPaymentId) {
                                $TransactionTable_Id = $traversalarray['TransactionId'];
                                break;
                            }
                        }

                        if($TransactionTable_Id == $returnTransactionId) {
                            $status = "Success";    
                        }       
                    }    
                }     

                $response = array();
                $mysqldate = date( 'Y-m-d H:i:s');
        	    if ($status == 'Success')  {

        	        $flag =1;
            	    $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId, $returnPartPaymentId, $orderNo, $mysqldate, $userid, $returnLogTableId, $flag); 
            	    $cmsPageArr['paymentId'] = $returnPaymentId;
            	    $this->load->view('enterprise/creditPaymentPaid',$cmsPageArr);

        	    } else if($status == 'Failed')	{

        	        $flag =2;
        	        $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId, $returnPartPaymentId, $orderNo, $mysqldate,$userid, $returnLogTableId, $flag); 
        	        $this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);					

                }
            }
        }
	    // } else if( $check=="true" && $_POST[AuthDesc]=="B") {

}



        function paymentGatewayResponse($TxnNum) {

            //error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
            $appId = 1;
            $cmsUserInfo = $this->cmsUserValidation();
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $thisUrl = $cmsUserInfo['thisUrl'];
            $validity = $cmsUserInfo['validity'];

            $this->init();
            $request['cmsUserInfo'] = $this->cmsUserValidation();
            $this->load->library('enterprise_client');
            $entObj = new Enterprise_client();
            $returnArr = $entObj->getCreditTransactionStatus($TxnNum);

            $returnStatus = $returnArr['status'];
            $returnPaymentId = $returnArr['paymentId'];
            $returnPartPaymentId = $returnArr['partPaymentId'];
	    $transactionid =$returnArr['Transaction_Id'];
	    $creditCardLogsId =$returnArr['creditCardLogsId'];
            $cmsPageArr = array();

            global $homePageMap;
            $keyPageArray = array_flip($homePageMap);
            $spaceNamedArray = str_replace("_"," ",$keyPageArray);
            $cmsPageArr['keyid_page_name'] = json_encode($spaceNamedArray);
            $cmsPageArr['totalKeyPageCount'] = max($homePageMap);

            $cmsPageArr['flagMedia'] = $flagMedia;
            $cmsPageArr['userid'] = $userid;
            $cmsPageArr['usergroup'] = $usergroup;
            $cmsPageArr['thisUrl'] = $thisUrl;
            $cmsPageArr['validateuser'] = $validity;
	    $cmsPageArr['Transaction_Id'] = $transactionid;
            $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
            $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
            $entObj = new Enterprise_client();

            $cmsPageArr['prodId'] = '27';

            if($returnStatus == "Done") {
		$PaymentReceivedFlag = 1;
                $mysqldate = date( 'Y-m-d H:i:s');
                $phpdate = strtotime( $mysqldate );
                $this->load->library('subscription_client');
                $objSumsManage = new Subscription_client();
                $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId,$returnPartPaymentId,$TxnNum, $mysqldate,$userid,$creditCardLogsId,$PaymentReceivedFlag);
                $cmsPageArr['paymentId'] = $TxnNum;
                $this->load->view('enterprise/creditPaymentPaid',$cmsPageArr);

            }else {
                $this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);
            }
        }

function ccavenueresponse()
{
	    
	    $cmsUserInfo = $this->cmsUserValidation();
        $userid = $cmsUserInfo['userid'];
        $usergroup = $cmsUserInfo['usergroup'];
        $thisUrl = $cmsUserInfo['thisUrl'];
        $validity = $cmsUserInfo['validity'];	    
	    $paymentIdArr = split("_",$_POST['orderNo']);	    
	    $returnPaymentId = $paymentIdArr[1];
	    $returnPartPaymentId= $paymentIdArr[2];
	    $key = $paymentIdArr[3];
	    $cmsPageArr = array();
	    $cmsPageArr['userid'] = $userid;
        $cmsPageArr['usergroup'] = $usergroup;
        $cmsPageArr['thisUrl'] = $thisUrl;
        $cmsPageArr['validateuser'] = $validity;
	    $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
        $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
	    

	    $this->load->library('ccavenue_gateway_lib');
	    $object = new ccavenue_gateway_lib();
        $WorkingKey = $object->get_WorkingKey();
	    //$check=$object->calculateverifyCheckSum($_POST[Order_Id],$_POST[Amount],$_POST[Auth_Status],$_POST[checkSumAll]);
    	
        $encResponse = $_POST['encResp'];
        $rcvdString = $object->decrypt($encResponse,$WorkingKey); 
        $order_status = '';
        $decryptValues = explode('&', $rcvdString);
        $dataSize = sizeof($decryptValues);

        for($i = 0; $i < $dataSize; $i++) 
        {
            $information = explode('=',$decryptValues[$i]);
            if($i==3){
                $order_status = $information[1];
            }
        }
		
        if($order_status==="Success")
        {
            $flag =1;
            $response['paymentData'] = array();
            $mysqldate = date( 'Y-m-d H:i:s');
            $this->load->library('subscription_client');
            $objSumsManage = new Subscription_client();
            $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId,$returnPartPaymentId,$_POST['orderNo'],$mysqldate,$userid,$key,$flag); 
            $cmsPageArr['paymentId'] = $returnPaymentId;
            
			$this->load->view('enterprise/creditPaymentPaid',$cmsPageArr);
            
        }
        else if($order_status==="Failure")
        {
            $flag =2;
            $response['paymentData'] = array();
            $mysqldate = date( 'Y-m-d H:i:s');
            $this->load->library('subscription_client');
            $objSumsManage = new Subscription_client();
            $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId,$returnPartPaymentId,$_POST['orderNo'],$mysqldate,$userid,$key,$flag); 
            
			$this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);    
        
        }
        else if($order_status==="Aborted")
        {
            $this->load->view('enterprise/creditCardEmail',$cmsPageArr);  
        }
        else
        {
            $this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);    
        
        }
	    // if ($check=="true" && $_POST[Auth_Status]=="Y")
	    // {
	    //     $flag =1;
	    //     $response['paymentData'] = array();
	    //     $mysqldate = date( 'Y-m-d H:i:s');
     //        $this->load->library('subscription_client');
     //        $objSumsManage = new Subscription_client();
	    //     $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId,$returnPartPaymentId,$_POST[Order_Id],$mysqldate,$userid,$key,$flag); 
	    //     $cmsPageArr['paymentId'] = $returnPaymentId;
	    //     $this->load->view('enterprise/creditPaymentPaid',$cmsPageArr);
	    // }
	    // else if($check=="true" && $_POST[Auth_Status]=="N")
	    // {	
    	//     $flag =2;
    	//     $response['paymentData'] = array();
    	//     $mysqldate = date( 'Y-m-d H:i:s');
     //        $this->load->library('subscription_client');
     //        $objSumsManage = new Subscription_client();
	    //     $response['paymentData'] =  $objSumsManage->updateCreditCardPaymentDetails($returnPaymentId,$returnPartPaymentId,$_POST[Order_Id],$mysqldate,$userid,$key,$flag); 
	    //     $this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);					
	    // }
	    // else if( $check=="true" && $_POST[Auth_Status]=="B")
	    // {
	    //     $this->load->view('enterprise/creditCardEmail',$cmsPageArr);  
     //    }	
	    // else
	    // {
    	//     $this->load->view('enterprise/creditPaymentFailed',$cmsPageArr);	
	    // }

}


        function setSponsored($startFrom='0',$countOffset='400') {
            $startTime = microtime(true);
            //error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
            $appId = 1;

            $cmsUserInfo = $this->cmsUserValidation();
            if($cmsUserInfo['usergroup']!='cms'){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $thisUrl = $cmsUserInfo['thisUrl'];
            $validity = $cmsUserInfo['validity'];
            $this->init();

            $flagMedia = 1;

            $cmsPageArr = array();
            $cmsPageArr['prodId'] = $prodId;
	    $cmsPageArr['countryRequested'] = ($this->input->post('countryRequested'))?$this->input->post('countryRequested'):'national';

            global $homePageMap;
            $keyPageArray = array_flip($homePageMap);
            $spaceNamedArray = str_replace("_"," ",$keyPageArray);
            $cmsPageArr['keyid_page_name'] = json_encode($spaceNamedArray);
            $cmsPageArr['totalKeyPageCount'] = max($homePageMap);

            $cmsPageArr['flagMedia'] = $flagMedia;
            $cmsPageArr['userid'] = $userid;
            $cmsPageArr['usergroup'] = $usergroup;
            $cmsPageArr['thisUrl'] = $thisUrl;
            $cmsPageArr['validateuser'] = $validity;
            $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
            $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
            $entObj = new Enterprise_client();

            $cmsPageArr['prodId'] = '23';

            $onBehalfOf = $this->input->post('onBehalfOf');
            if ($onBehalfOf=="true")
            {
                $userid = $this->input->post('selectedUserId',true);
                $this->load->library('register_client');
                $regObj = new Register_client();
                $arr = $regObj->userdetail(1,$userid);
                $cmsPageArr['userDetails'] = $arr[0];
                $cmsPageArr['userDetails']['clientUserId'] = $userid;
            }

            $this->load->library('sums_product_client');
            $objSumsProduct =  new Sums_Product_client();
            $cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$userid));

            $userArr['userid'] = $userid;
            $userArr['startFrom'] = $startFrom;
            $userArr['countOffset'] = $countOffset;
	    $userArr['countryRequested'] = $cmsPageArr['countryRequested'];
            $userListings = $entObj->getListingsByClient($appId,$userArr);
            $cmsPageArr['clientListings'] = $userListings;

            $params = array();
            $cmsPageArr['productInfo'] = $objSumsProduct->getProductFeatures(1,$params);

            //echo "<pre>";print_r($cmsPageArr);echo "</pre>";
            $this->load->view('enterprise/setSponsored',$cmsPageArr);
            if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
        }

        function unsetMainInstitute() {
            $appId = 1;
            $cmsUserInfo = $this->cmsUserValidation();
            if($cmsUserInfo['usergroup']!='cms'){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }

            $idsToUnset = implode(", ", $this->input->post('selectedInstitutesChkbox'));
            ob_start();
            $this->load->model('ListingModel');
            $this->ListingModel->unsetMainInstitute($idsToUnset);
            setcookie("thanksMsgCookie", "Thanks, Main Institute on the selected combination(s) has been unset successfully.", time()+8);  /* expire in 10 secs */
            redirect('enterprise/Enterprise/showMainInstituteForClient/'.$this->input->post('clientUserId'),'location');
            ob_end_flush();
        }
        
        function showMainInstituteForClient($uid="") {
            $startTime = microtime(true);
            //error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
            $appId = 1;

            $cmsUserInfo = $this->cmsUserValidation();
            if($cmsUserInfo['usergroup']!='cms'){
                header("location:/enterprise/Enterprise/disallowedAccess");
                exit();
            }
            
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $thisUrl = $cmsUserInfo['thisUrl'];
            $validity = $cmsUserInfo['validity'];
            //_p($cmsUserInfo); die;
            $this->init();

            $flagMedia = 1;

            $cmsPageArr = array();
            $cmsPageArr['userid'] = $userid;
            $cmsPageArr['usergroup'] = $usergroup;
            $cmsPageArr['thisUrl'] = $thisUrl;
            $cmsPageArr['validateuser'] = $validity;
            $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
            $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
            $cmsPageArr['prodId'] = '23';
	    
	    $cmsPageArr['countryRequested'] = ($this->input->post('countryRequested'))?$this->input->post('countryRequested'):'national';

            $onBehalfOf = $this->input->post('onBehalfOf');
            if ($onBehalfOf=="true" || (is_numeric($uid) && $uid > 0))
            {
                if(is_numeric($uid) && $uid > 0) {
                    $userid = $uid;
                } else {
                    $userid = $this->input->post('selectedUserId',true);
                }
                $this->load->library('register_client');
                $regObj = new Register_client();
                $arr = $regObj->userdetail(1,$userid);
                $cmsPageArr['userDetails'] = $arr[0];
                $cmsPageArr['userDetails']['clientUserId'] = $userid;
            }
            
            $userArr['userid'] = $userid;
            $userArr['countryRequested'] = $cmsPageArr['countryRequested'];
	    
            $entObj = new Enterprise_client();
            $userListings = $entObj->getMainInstitutesByClient($appId,$userArr);
            // _p($userListings);die;
            $cmsPageArr['clientListings'] = $userListings;
            
            // echo "<pre>";print_r($cmsPageArr);echo "</pre>";die;
            $this->load->view('enterprise/removeMainInstitute',$cmsPageArr);
            if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
        }
        
        function fetchLatestSubscriptionInfo(){
            $userid = $this->input->post('userid');
            $this->load->library('sums_product_client');
            $objSumsProduct =  new Sums_Product_client();
            $resultArray = $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$userid));
            echo json_encode($resultArray);
        }

        function fetchKeyPagesForThisListing($listingTypeId,$listingType){
            $appId = '1';

            $cmsUserInfo = $this->cmsUserValidation();
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $this->init();

            global $homePageMap;
            global $categoryMap;
            //$keyPageArray = array_flip($homePageMap);

            $ListingClientObj = new Listing_client();
            $listingDetails = $ListingClientObj->getListingDetails($appId,$listingTypeId,$listingType);
            $displayData = array();
            $displayData['type_id'] = $listingTypeId;
            $displayData['listing_type'] = $listingType;
            if( ($usergroup == "cms") ||
            (($listingDetails[0]['userId'] == $userid) AND
            ($usergroup == "enterprise")) )
            {
                $displayData['cmsData'] = 1;
            }else{
                $displayData['cmsData'] = '';
            }
            $displayData['cmsAjaxFetch'] = 1;
            $displayData['details'] = $listingDetails[0];
            $displayData['validateuser'] = $this->userStatus;

            $cat_client = new Category_list_client();
            $categoryList = $cat_client->getCategoryTree($appId);

            foreach($categoryList as $temp)
            {
                $categoryForLeftPanel[$temp['categoryID']] =array($temp['categoryName'],$temp['parentId'],$temp['urlName']);
            }
            //echo "<pre>";print_r($displayData);echo "</pre>";
            for ($i=0;$i<count($displayData['details']['categoryArr']);$i++)
            {
                $catid= $displayData['details']['categoryArr'][$i]['category_id'];
                $displayData['details']['categoryArr'][$i]['cat_name']=$categoryForLeftPanel[$catid][0];
                $displayData['details']['categoryArr'][$i]['cat_url']=$categoryForLeftPanel[$catid][2];
                $parent = $categoryForLeftPanel[$catid][1];
                $displayData['details']['categoryArr'][$i]['parent_cat_name']=$categoryForLeftPanel[$parent][0];
                $displayData['details']['categoryArr'][$i]['parent_url']=$categoryForLeftPanel[$parent][2];
                $displayData['details']['categoryArr'][$i]['parent_cat_id'] = $parent;
            }

            //Parent Category Id to Key-Page Mapping
            $parentCats = array();
            foreach($displayData['details']['categoryArr'] as $cats){
                if(array_key_exists($cats['parent_cat_id'],$parentCats)){
                }else{
                    $parentCats[$cats['parent_cat_id']] = '';
                }
            }

            $FinalKeyArr = array();
            foreach($parentCats as $key=>$val){
                foreach($categoryMap as $idArr){
                    if($idArr['id']==$key){
                        $FinalKeyArr[$homePageMap[$idArr['page']]] = $idArr['page'];
                    }
                }
            }
            $displayData['FinalKeyArr'] = $FinalKeyArr;

           // echo '<pre>',print_r($displayData),'</pre>';
           //echo '<pre>',print_r($FinalKeyArr),'</pre>';
            $this->load->view('enterprise/listingCategoryToPageKeys',$displayData);
        }

        function checkUniqueTitle(){
            $appId = '1';
            error_log_shiksha((print_r($_POST,true)));
            $cmsUserInfo = $this->cmsUserValidation();
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $dataArr['title'] = htmlspecialchars_decode($this->input->post('title',true));

	    $entObj = new Enterprise_client();
            $response = $entObj->checkUniqueTitle($appId,$dataArr);
            echo json_encode($response);
        }
        function massMailSubsChoose() {
            //error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
            //echo "<pre>";print_r($_POST);echo "</pre>";
            $appId = 1;
            $validity_check = $this->input->post('validity_check',true);
            $cmsUserInfo = $this->cmsUserValidation($validity_check);
            $userid = $cmsUserInfo['userid'];
            $usergroup = $cmsUserInfo['usergroup'];
            $thisUrl = $cmsUserInfo['thisUrl'];
            $validity = $cmsUserInfo['validity'];
            $this->init();

            $cmsPageArr = array();
            $cmsPageArr['userid'] = $userid;
            $cmsPageArr['usergroup'] = $usergroup;
            $cmsPageArr['thisUrl'] = $thisUrl;
            $cmsPageArr['validateuser'] = $validity;
            $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
            $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
            $entObj = new Enterprise_client();

            $cmsPageArr['prodId'] = '25';

            $onBehalfOf = $this->input->post('onBehalfOf');
            $cmsPageArr['extraInfoArray'] = urldecode($this->input->post('extraInfoArray'));
			$isMMM = FALSE;
			
            if ($onBehalfOf=="true")
            {
                $userid = $this->input->post('selectedUserId',true);
                if($userid == '166660'){
					$isMMM = TRUE;
                    echo Modules::run('mailer/Mailer/SelectUserList');        
                }
				else {
					$this->load->library('register_client');
					$regObj = new Register_client();
					$arr = $regObj->userdetail(1,$userid);
					$cmsPageArr['userDetails'] = $arr[0];
					$cmsPageArr['userDetails']['clientUserId'] = $userid;
				}
            }

			if(!$isMMM) {	
				$this->load->library('sums_product_client');
				$objSumsProduct =  new Sums_Product_client();
				$cmsPageArr['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$userid));
/*
				$params = array();
				$cmsPageArr['productInfo'] = $objSumsProduct->getProductFeatures(1,$params);
*/
				//echo "<pre>";print_r($cmsPageArr);echo "</pre>";
				$this->load->view('enterprise/massMailSubsChoose',$cmsPageArr);
			}
        }

function moderate($prodId=27){
    $appId = '1';
    $this->init();
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }
    $data = array();
    $data['prodId'] = $prodId;
    $data['cmsUserInfo'] = $cmsUserInfo;
    $data['validateuser'] = $cmsUserInfo['validity'];
    $data['userid'] = $cmsUserInfo['userid'];
    $data['usergroup'] = $cmsUserInfo['usergroup'];
    $entObj = new Enterprise_client();
    $data['searchCategories']=$entObj->getSearchSubCategories(1);
    $this->load->view('enterprise/moderationPage',$data);
}

function moderationList($prodId,$startFrom=0,$countOffset=7)
{
    $appId = '1';
    $this->init();
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }

    $data = array();
    $data['userid'] = $cmsUserInfo['userid'];
    $data['usergroup'] = $cmsUserInfo['usergroup'];
    $data['startFrom'] = $this->input->post('startOffSet');
    $data['countOffset'] = $this->input->post('countOffSet');
    $data['instituteName'] = $this->input->post('instituteName');
    $data['location'] = $this->input->post('location');
    $data['clientEmail'] = $this->input->post('clientEmail');
    $data['clientUserid'] = $this->input->post('clientUserid');
    $data['categoryId'] = $this->input->post('categoryId');

    $ListingClientObj = new Listing_client();
    $displayArr['moderationList'] = $ListingClientObj->getModerationList($appId,$data);
    //pa($displayArr['moderationList']);exit;
    //echo json_encode($moderationList);
    $this->load->view('enterprise/moderationList',$displayArr);
}

function approveDisapproveListing($prodId=27)
{
    //echo '<pre>';print_r($_POST);echo '</pre>';
    $appId = '1';
    $this->init();
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }
    $totalSelectCount = $this->input->post('totalUserCount');
    $request['approvalAction'] = $this->input->post('approvalAction');
    $request['disapprovalComments'] = $this->input->post('disapprovalComments');
    $editingUserId = $cmsUserInfo['userid'];
    $moderateList =  $this->input->post('moderateList');
    $listingArr = array();

    foreach($moderateList as $key=>$val){
        $segregatedList = explode("#",$val);
        array_push($listingArr,$segregatedList);
    }

    $finalArr = array();
    foreach($listingArr as $key=>$val){
        $tmp = array();
        $tmp['type']=$val[0];
        $tmp['typeId']=$val[1];
        array_push($finalArr,$tmp);
    }

    $this->load->library(array('listing_client'));
    $objList = new Listing_client();

    if($request['approvalAction']=='APPROVE'){
        $audit = array();
        $audit['approvedBy'] = $editingUserId;
        $updateStatus = $objList->makeListingsLive($appId,$finalArr,$audit);
	//Code start by Ankur for HTML caching
	//$this->load->library('cacheLib');
	//$cacheLib = new cacheLib();
	foreach($listingArr as $key=>$val){
	    if($val[0] == "institute"){
		$institute_id = $value['typeId'];
	    }
	    else if($val[0] == 'course'){
		$institute_id = $objList->getInstituteIdForCourseId($appId,$val[1]);
	    }
	    //$key = md5('listingCache_'.$institute_id."_institute");
	    //$cacheLib->store($key,'false',86400,'misc');
	    //$key = md5('listingCache_'.$institute_id."_course");
	    //$cacheLib->store($key,'false',86400,'misc');
	    $key = "listingCache_".$institute_id."_institute";
	    $objList->setListingCacheValue($appId, $key,'false');
	    $key = "listingCache_".$institute_id."_course";
	    $objList->setListingCacheValue($appId, $key,'false');
	    //After setting the value in DB, also delete the HTML files from all the Frontend servers
	    $objList->deleteListingCacheHTMLFile($institute_id,"institute");
	    $objList->deleteListingCacheHTMLFile($institute_id,"course");
	}
	//Code End by Ankur for HTML caching

    }
    if($request['approvalAction']=='DISAPPROVE'){
        $audit = array();
        $audit['approvedBy'] = $editingUserId;
        $audit['comments'] = $request['disapprovalComments'];
        $updateStatus = $objList->disapproveQueuedListings($appId,$finalArr,$audit);
    }
    if($request['approvalAction']=='DELETE'){
        $audit = array();
        $audit['approvedBy'] = $editingUserId;
        $audit['comments'] = $request['disapprovalComments'];
        foreach($finalArr as $key => $val){
            $deleteStatus= $objList->deleteDraftOrQueued($appId,$val['typeId'],$val['type'],'queued',$audit);
            $this->load->model('ListingModel');
            $incrementResp = $this->ListingModel->incrementCountOfSubscription($deleteStatus);
        }
    }
    //pa($response);exit;
    $data['result'] = $finalArr;
    $data['prodId'] = $prodId;
    $data['type'] = $request['approvalAction'];
    //echo "<pre>";print_r($data);echo "</pre>";
    $this->load->view('enterprise/approvalResult',$data);

}

        function fetchLatestPseudoSubscriptionInfo(){
            $userid = $this->input->post('userid');
            $this->load->library('sums_product_client');
            $objSumsProduct =  new Sums_Product_client();
            $resultArray = $objSumsProduct->getAllPseudoSubscriptionsForUser(1,array('userId'=>$userid));
            echo json_encode($resultArray);
        }

function showEntitiesForPriorityLeads() {
    $this->init();
    $validity = $this->checkUserValidation();
    $cmsPageArr['validateuser'] = $validity;
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }

    $cmsPageArr['headerTabs'] = $cmsUserInfo['headerTabs'];
    $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts'];
    $appId = 1;
    $listing_client = new Listing_client();
    $entitiesForPriorityLeads = $listing_client->getEntitiesForPriorityLeads($appId);
    $cmsPageArr['entitiesForPriorityLeads'] = json_decode($entitiesForPriorityLeads ,true);
    $this->load->view('enterprise/priorityLeadsPage',$cmsPageArr);
}

function addEntityForPriorityLeads() {
    $this->init();
    $validity = $this->checkUserValidation();
    $cmsPageArr['validateuser'] = $validity;
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }
    $appId = 1;
    $listingId = $_REQUEST['listingId'];
    $endDate = $_REQUEST['endDate'];

    if(empty($listingId) || empty($endDate)) {
        header("location:/enterprise/Enterprise/showEntitiesForPriorityLeads");
    }
    $listing_client = new Listing_client();
    $status = $listing_client->addEntityForPriorityLeads($appId, $listingId, $endDate);
    if(is_numeric($status) && $status > 0) {
        header("location:/enterprise/Enterprise/showEntitiesForPriorityLeads");
    } else {
        echo "Entity Addition failed";
    }
}

function deleteEntityFromPriorityLeads($entityId){
    $this->init();
    $validity = $this->checkUserValidation();
    $cmsPageArr['validateuser'] = $validity;
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='cms'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }
    $appId = 1;

    if(empty($listingId) || empty($endDate)) {
    header("location:/enterprise/Enterprise/showEntitiesForPriorityLeads");
    }
    $listing_client = new Listing_client();
    $status = $listing_client->deleteEntityFromPriorityLeads($appId, $entityId);
    header("location:/enterprise/Enterprise/showEntitiesForPriorityLeads");
}

    function setAlumFeedReply($threadId)
    {
	$threadId = $_POST['threadId'];
	$criteriaId = $_POST['criteria'.$threadId];
	$hideReviewNumber = $_POST['hideReviewNumber'.$threadId];
	if($hideReviewNumber == -1)
	{
	  $secCode = "seccode".$criteriaId.$threadId;
	  $replyText = "replyText".$criteriaId.$threadId;
	}
	else
	{
	  $secCode = "seccode".$criteriaId.$hideReviewNumber;
	  $replyText = "replyText".$criteriaId.$hideReviewNumber;
	}
	if(verifyCaptcha('seccodeForInlineAnswer',$_POST[$secCode],1))
	{
            $this->load->library('message_board_client');
            $msgbrdClient = new Message_board_client();
	    $Validate = $this->checkUserValidation();
	    $userId = $Validate[0]['userid'];
	    $userName = $Validate[0]['displayname'];
	    $review = "review".$threadId;
	    $fromOthers = "fromOthers".$threadId;
	    $criteriaId = $_POST['criteria'.$threadId];
            $requestIp = S_REMOTE_ADDR;
            $topicResult = $msgbrdClient->addTopic(1,$userId,$_POST[$review],1,$requestIp,$_POST[$fromOthers],0,'',0);
            $topicThreadId = $topicResult['ThreadID'];
	    $success = false;

	    if($topicThreadId>0)
	    {
		$this->load->library('alumniSpeakClient');
		$appId = 1;
		$instituteId = $_POST['institute'.$threadId];
		$criteriaId = $_POST['criteria'.$threadId];
		$email = $_POST['email'.$threadId];
		$objAlumniSpeakClientObj= new AlumniSpeakClient();
		$response = $objAlumniSpeakClientObj->insertThreadId($appId, $instituteId,$criteriaId, $email,$topicThreadId);
		if($response==1)
		{
		  $userReply = trim($_POST[$replyText]);
		  //$userReply = $this->input->xss_clean($userReply);
		  $mbReplyResponse = $msgbrdClient->postReply(1,$userId,$userReply,$topicThreadId,$topicThreadId,$requestIp,$_POST[$fromOthers],$userName,0);
		  $msgId = $mbReplyResponse['MsgID'];
		  if($msgId>0)
		    $success = true;
		}
	    }
	    if($success)
		echo nl2br($_POST[$replyText])."::".$threadId."::".$criteriaId."::".$hideReviewNumber;
	    else
		echo "db error::".$threadId."::".$criteriaId."::".$hideReviewNumber;
	}
	else
	  echo "secimg error::".$threadId."::".$criteriaId."::".$hideReviewNumber;
    }
	function enterpriseUserQuestions($prodId,$startOffset=0,$countOffset=10) {
		$cmsUserInfo = $this->cmsUserValidation();
		$questionDetailsArr['myProducts'] =$cmsUserInfo['myProducts']; 
		$questionDetailsArr['usergroup'] = 'enterprise'; 
		$this->init();
		$validateuser = $this->checkUserValidation();
		$userId = isset($validateuser[0]['userid'])?$validateuser[0]['userid']:0;
		$questionDetailsArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
		$questionDetailsArr['prodId'] = $prodId;
		if(!isset($prodId)){
			$questionDetailsArr['prodId'] = $questionDetailsArr['headerTabs'][0]['selectedTab'];
		}
		$EnterpriseClientObj = new Enterprise_client();
		$ResultArr = $EnterpriseClientObj->getQuestionsPostedForEnterpriseUser(1,$userId,$startOffset,$countOffset);
		$questionDetailsArr['questionsArr']=$ResultArr['questions'];
		$questionDetailsArr['answerArr']=$ResultArr['answers'];
		$questionDetailsArr['totalCount']=$ResultArr['totalRows'];
		$questionDetailsArr['courseIds']=$ResultArr['courseIds'];
	        $this->load->library('CA/CADiscussionHelper');
        	$questionDetailsArr['caDiscussionHelper'] =  new CADiscussionHelper();
		$questionDetailsArr['validateuser'] = $validateuser;
		$questionDetailsArr['startOffset'] = $startOffset;
		$questionDetailsArr['countOffset'] = $countOffset;
		// Start Online form change by pranjul 13/10/2011
	    	$this->load->library('OnlineFormEnterprise_client');
	    	$ofObj = new OnlineFormEnterprise_client();
	    	$questionDetailsArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($userId);
	    	// End Online form change by pranjul 13/10/2011
		$this->load->view('enterprise/enterpriseUserQuestions',$questionDetailsArr);
	}
  function getUserAbuse(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$moduleName = trim($this->input->post('moduleName'));
	$filter = trim($this->input->post('Filter'));
	$start=$this->input->post('startFrom');
	$rows=$this->input->post('countOffset');
    $filterByReason = trim($this->input->post('filterByReason'));

	$userNameFieldData=$this->input->post('userNameFieldData');
	$userLevelFieldData=$this->input->post('userLevelFieldData');
	$reported=$this->input->post('reported');

    //Get the abuse form fields
    $this->load->library('message_board_client');
    $msgbrdClient = new Message_board_client();
    $userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
    $Result = $msgbrdClient->getReportAbuseForm($appId,"QuestionAnswer");
    if($moduleName== 'AnA')
    $abuseReasonsForFilter = $Result;

    // $this->load->model('messageBoard/AnAModel');
    // $expertLevelsForFilter = $this->AnAModel->getExpertLevels('AnA');
    $this->load->helper('messageBoard/abuse');
    $expertLevelsForFilter = getExpertLevels();

	$parameterObj = array('abuse' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
	$entObj = new Enterprise_client();
	$resultUserAbuse = $entObj->getAbuseList($appId,$loggedInUserId,$start,$rows,$moduleName,$filter,$userNameFieldData,$userLevelFieldData,$reported, $filterByReason);
	$totalAbuseReport = isset($resultUserAbuse[0]['totalAbuseReport'])?$resultUserAbuse[0]['totalAbuseReport']:0;
	$parameterObj['abuse']['offset'] = 0;
	$parameterObj['abuse']['totalCount'] = $totalAbuseReport;
	$tempArray=array();
	$abuseDetailsArray=array();
	if(isset($resultUserAbuse[0]['results'])){
		$tempArray = &$resultUserAbuse[0]['results'];
	}
	if(isset($resultUserAbuse[0]['abuseDetails'])){
		$abuseDetailsArray = &$resultUserAbuse[0]['abuseDetails'];
	}

	$abuseForm = is_array($Result)?$Result:array();
	$abuseFormFields = array();
	for($i=0;$i<count($abuseForm);$i++)
	{
	  $abuseFormFields[] = $abuseForm[$i]['Title'];
	}

	for($i=0;$i<count($tempArray);$i++)
	{
		if(is_array($tempArray[$i]['abuse'])){
			//Set the message creation date
			//$tempArray[$i]['abuse']['msgCreationDate'] = makeRelativeTime($tempArray[$i]['abuse']['msgCreationDate']);
			if($moduleName=='Events'){
			  $dateobj = strtotime($tempArray[$i]['event']['msgCreationDate']);
			  $tempArray[$i]['event']['msgCreationDate'] = date("d F, Y", $dateobj);
			 }
			else{
			  $dateobj = strtotime($tempArray[$i]['abuse']['msgCreationDate']);
			  $tempArray[$i]['abuse']['msgCreationDate'] = date("d F, Y", $dateobj);
			}
			//Set the URL
			if($moduleName == "AnA")
			  $tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getTopicDetail/".$tempArray[$i]['abuse']['threadId'];
			else if($moduleName == "Articles")
			  $tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getArticleDetail/".$tempArray[$i]['abuse']['threadId'];
			if($moduleName == "Events")
			  $tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getEventDetail/1/".$tempArray[$i]['abuse']['threadId'];
			if($moduleName == "discussion")
			  $tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getTopicDetail/".$tempArray[$i]['abuse']['threadId'];
			if($moduleName == "announcement")
			  $tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getTopicDetail/".$tempArray[$i]['abuse']['threadId'];
			if($moduleName == "review")
			  $tempArray[$i]['abuse']['url'] = SHIKSHA_HOME."/getTopicDetail/".$tempArray[$i]['abuse']['threadId'];
		}
	}
	for($i=0;$i<count($abuseDetailsArray);$i++)
	{
	    //Set the abuse reason text
	    $abuseReasons = explode(",",$abuseDetailsArray[$i]['abuseReason']);
	    $fields = '';
	    for($j=0;$j<count($abuseReasons);$j++){
	      $index = $abuseReasons[$j]-1;
	      if($fields == '')
		$fields .= $abuseFormFields[$index];
	      else
		$fields .= ", ".$abuseFormFields[$index];
	    }
	    $abuseDetailsArray[$i]['abuseReason'] = $fields;
	    //Set the abuse report date
	    $dateobj = strtotime($abuseDetailsArray[$i]['creationDate']);
	    $abuseDetailsArray[$i]['creationDate'] = date("d/m/y", $dateobj);
	}
	$Validate = $this->userStatus;
	$cmsPageArr['validateuser'] = $Validate;
	$cmsPageArr['parameterObj'] = json_encode($parameterObj);
	$cmsPageArr['userAbuse'] = isset($resultUserAbuse[0]['results'])?$resultUserAbuse[0]['results']:array();
	$cmsPageArr['abuseDetails'] = isset($resultUserAbuse[0]['abuseDetails'])?$resultUserAbuse[0]['abuseDetails']:array();
	$cmsPageArr['totalAbuse'] = $totalAbuseReport;
	$cmsPageArr['filterSel'] = $filter;
    $cmsPageArr['filterAbuseReasonSelected'] = $filterByReason;
	$cmsPageArr['moduleName'] = $moduleName;
	$cmsPageArr['startFrom'] = $start;
	$cmsPageArr['countOffset'] = $rows;

	$cmsPageArr['userNameFieldData'] = $userNameFieldData;
	$cmsPageArr['userLevelFieldData'] = $userLevelFieldData;
	$cmsPageArr['reported'] = $reported;
	$answerSuggestions = isset($resultUserAbuse[0]['answerSuggestions'])?$resultUserAbuse[0]['answerSuggestions']:array();
	$cmsPageArr['answerSuggestions'] = $this->convertSuggestionArray($answerSuggestions);
    $cmsPageArr['abuseReasonsForFilter'] = $abuseReasonsForFilter;
    $cmsPageArr['expertLevelsForFilter'] = $expertLevelsForFilter;
        echo $totalAbuseReport."::".$this->load->view('enterprise/abuseListing',$cmsPageArr);
  }

  function removeUserAbuse(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$entityId = trim($this->input->post('entityId'));
	$ownerId = trim($this->input->post('ownerId'));
	$entityType=trim($this->input->post('entityType'));
	$threadId=trim($this->input->post('threadId'));
        $penalty=trim($this->input->post('penalty'));
	$entityTypeShown = getAbuseEntityName($entityType);
	
	//Set the entity as deleted in their respective tables
	if($entityType == "Event")
	{
	    $eventObj = new Event_cal_client();
	    $deleteResult = $eventObj->deleteEvent($appId,$entityId,2,1);
	}
	else
	{
	    $msgbrdClient = new Message_board_client();
	    if($entityType == "Question")
            if($penalty == 1)
                $deleteResult = $msgbrdClient->deleteTopicFromCMS($appId,$entityId,'abused');
            else
                $deleteResult = $msgbrdClient->deleteTopicFromCMS($appId,$entityId,'abusedWithoutPenalty');
	    else
            if($penalty == 1)
                $deleteResult = $msgbrdClient->deleteCommentFromCMS($appId,$entityId,$threadId,$ownerId,'abused');
            else
                $deleteResult = $msgbrdClient->deleteCommentFromCMS($appId,$entityId,$threadId,$ownerId,'abusedWithoutPenalty');
	}

	//Set the entity as removed in the Abuse log table
	$entObj = new Enterprise_client();
    if($penalty == 1){
	$abuseStatus = 'RemovedByA';
	$resultUserAbuseRemove = $entObj->updateStatusAbuseList($appId,$loggedInUserId,$entityId,$entityType,$abuseStatus);
    
    }else{
	$abuseStatus = 'RemovedByAWithoutPenalty';
        $resultUserAbuseRemove = $entObj->updateStatusAbuseList($appId,$loggedInUserId,$entityId,$entityType,$abuseStatus);
	
    }
	
	//redis notification call
	$status = ($penalty=="1")?'Removedwp':'Removedwop';
	
	$this->load->model('messageBoard/AnAModel');
        $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
	$reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($entityId,$entityTypeShown);
	$reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($entityId);
	
	foreach($reportAbuseUserData as $key=>$val){    
	    $reportAbuser[] = $val['userId'];
	    $reportAbuseDate[] = $val['creationDate'];    
	}
	
	if($reportAbuseThreadData[0]['fromOthers'] == 'user'){
	    $threadType = 'question';
	}else{
	    $threadType = $reportAbuseThreadData[0]['fromOthers'];
	}
		
	$entityStatus = $reportAbuseEntityData[0]['status'];
	$entityTitle = $reportAbuseEntityData[0]['entityTitle'];
	$threadTitle = $reportAbuseThreadData[0]['threadTitle'];
	
	if($threadType == 'discussion'){
	    $mainDiscussionId = $threadId +1;
	    $discussionDetail = $this->AnAModel->getReportAbuseThreadData($mainDiscussionId);
	    $threadTitle = $discussionDetail[0]['threadTitle'];
	    if($threadId == $entityId){
		$entityStatus = $reportAbuseThreadData[0]['status'];
		$entityTitle = $threadTitle;
	    }
        }
	
	$this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
	$this->appNotification->addReportAbuseNotificationToRedis($entityId,$entityTypeShown,$threadId,$threadType,$threadTitle,$ownerId,$reportAbuser,$status,$entityStatus,$entityTitle,$reportAbuseDate);
	
	//Add the entry in Redis for Personalized Homepage
        $this->load->library('common/personalization/UserInteractionCacheStorageLibrary');
	if(strtolower($entityType) == 'question' || strtolower($entityType) == 'discussion'){
		$this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType);
	}else if(strtolower($entityType) == 'answer' || $entityType == 'discussion Comment'){
		$this->userinteractioncachestoragelibrary->deleteEntity($ownerId,$threadId,$threadType,$entityType);
		
	}
        

	//Send mail to the owner about the action
	$this->sendRemoveAbuseMail($ownerId,$entityId,$entityType);
	echo "1";
  }

  function republishUserAbuse(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$entityId = trim($this->input->post('entityId'));
	$ownerId = trim($this->input->post('ownerId'));
	$entityType=trim($this->input->post('entityType'));
	$threadId=trim($this->input->post('threadId'));
	$penalty=trim($this->input->post('penalty'));
	$entityTypeShown = getAbuseEntityName($entityType);
	
	$status = ($penalty=="1")?'Republishedwp':'Republishedwop';
	
	//redis notification call
	$this->load->model('messageBoard/AnAModel');
        $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
	$reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($entityId,$entityTypeShown);
	
	if($reportAbuseEntityData[0]['status'] == 'abused'){
		$reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($entityId);
		
		foreach($reportAbuseUserData as $key=>$val){    
		    $reportAbuser[] = $val['userId'];
		    $reportAbuseDate[] = $val['creationDate'];    
		}
		
		if($reportAbuseThreadData[0]['fromOthers'] == 'user'){
		    $threadType = 'question';
		}else{
		    $threadType = $reportAbuseThreadData[0]['fromOthers'];
		}
		
		$entityStatus = $reportAbuseEntityData[0]['status'];
		$entityTitle = $reportAbuseEntityData[0]['entityTitle'];
		$threadTitle = $reportAbuseThreadData[0]['threadTitle'];
		
		if($threadType == 'discussion'){
		    $mainDiscussionId = $threadId +1;
		    $discussionDetail = $this->AnAModel->getReportAbuseThreadData($mainDiscussionId);
		    $threadTitle = $discussionDetail[0]['threadTitle'];
		    if($threadId == $entityId){
			$entityStatus = $reportAbuseThreadData[0]['status'];
			$entityTitle = $threadTitle;
		    }
	       }
		
		$this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
		$this->appNotification->addReportAbuseNotificationToRedis($entityId,$entityTypeShown,$threadId,$threadType,$threadTitle,$ownerId,$reportAbuser,$status,$entityStatus,$entityTitle,$reportAbuseDate);
	}

	//Set the entity as deleted in their respective tables
	$entObj = new Enterprise_client();
	$republishResult = $entObj->republishEntity($appId,$entityId,$threadId,$ownerId,$entityType,$penalty);

	//Set the entity as removed in the Abuse log table
	$resultUserAbuseRemove = $entObj->updateStatusAbuseList($appId,$loggedInUserId,$entityId,$entityType,$status);

	echo "1";
  }

  function sendRemoveAbuseMail($ownerId,$entityId,$entityType){
        error_log_shiksha("CONTROLLER sendRemoveAbuseMail ");
	$this->init();
	$msgbrdClient = new Message_board_client();
        $userDetails = $msgbrdClient->getUserDetails(1,$ownerId);
        $details = $userDetails[0];
        $email = $details['email'];
        $fromMail = "info@shiksha.com";
	$entityTypeText = getAbuseEntityName($entityType);
	$subject = "Your ".$entityTypeText." is deleted on account of abuse reports.";
        $urlOfCommunityPage = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/communityGuideline";

        $contentArr['name'] = ($details['firstname']=='')?$details['displayname']:$details['firstname'];
        $contentArr['communityUrl'] = $urlOfCommunityPage;
	$contentArr['entityType'] = $entityTypeText;
	$contentArr['type'] = 'abuseDeleteMail';

	$entityDetails = $msgbrdClient->getMsgText(1,$entityId,$entityType);
	$this->load->library('MailerClient');
	$MailerClient = new MailerClient();
	if(is_array($entityDetails)){
	  $entityText = $entityDetails[0]['msgTxt'];
	  $entityURL = $MailerClient->generateAutoLoginLink(1,$email,$entityDetails[0]['url']);
	}
	$contentArr['entityText'] = $entityText;
	//Get the section name and url based on the entity type
	if($entityType == 'Blog Comment'){
	  $urlOfLandingPage = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';
	  $contentArr['section'] = 'Shiksha.com Articles';
	}
	else if($entityType == 'Event Comment' || $entityType == 'Event'){
	  $urlOfLandingPage = SHIKSHA_EVENTS_HOME;
	  $contentArr['section'] = 'Shiksha.com Important Dates';
	}
	else{
	  $urlOfLandingPage = SHIKSHA_ASK_HOME;
	  //$contentArr['section'] = 'Shiksha Caf&#233;';
      $contentArr['section'] = 'Shiksha Ask & Answer';
	}
	//$contentArr['sectionURL'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
    $contentArr['sectionURL'] = $urlOfLandingPage;
    $contentArr['receiverId'] = $ownerId;
    $contentArr['subject'] = $subject;
    $contentArr['fromMail'] = $fromMail;
    Modules::run('systemMailer/SystemMailer/abuseDeleteMail', $email, $contentArr);
	//End for section name

    /*$content = $this->load->view("search/searchMail",$contentArr,true);
    $this->load->library('alerts_client');
    $mail_client = new Alerts_client();
    $response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html",'0000-00-00 00:00:00','n',array(),$ccmail,$bccEmail);*/

	//Added to send the mail to People who have reported the entity as Abuse
	$msgbrdClient->sendMailToAbusePeople(1,$entityId);
  }

  function sendRepublishAbuseMail(){
        error_log_shiksha("CONTROLLER sendRepublishAbuseMail ");
	$entityId = trim($this->input->post('entityId'));
	$ownerId = trim($this->input->post('ownerId'));
	$entityType=trim($this->input->post('entityType'));
	$penalty=trim($this->input->post('penalty'));
	$this->init();
	//Send mail to owner informing that his/her content has been republished
	$msgbrdClient = new Message_board_client();
        $userDetails = $msgbrdClient->getUserDetails(1,$ownerId);
        $details = $userDetails[0];
        $email = $details['email'];
        $fromMail = "noreply@shiksha.com";
        $ccmail = "sales@shiksha.com";
	$entityTypeText = getAbuseEntityName($entityType);
        $subject = "Your ".$entityTypeText." has been republished.";
        $urlOfCommunityPage = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/communityGuideline";
        $contentArr['name'] = ($details['firstname']=='')?$details['displayname']:$details['firstname'];
        $contentArr['communityUrl'] = $urlOfCommunityPage;
	$contentArr['entityType'] = $entityTypeText;
	$contentArr['type'] = 'republishAbuseMail';
	//Get the section name and url based on the entity type
	$this->load->library('MailerClient');
	$MailerClient = new MailerClient();
	if($entityType == 'Blog Comment'){
	  $urlOfLandingPage = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';
	  $contentArr['section'] = 'Shiksha.com Articles';
	}
	else if($entityType == 'Event Comment' || $entityType == 'Event'){
	  $urlOfLandingPage = SHIKSHA_EVENTS_HOME;
	  $contentArr['section'] = 'Shiksha.com Important Dates';
	}
	else{
	  $urlOfLandingPage = SHIKSHA_ASK_HOME;
	  $contentArr['section'] = 'Shiksha Ask & Answer';
	}
	$contentArr['sectionURL'] = $urlOfLandingPage;
    $contentArr['receiverId'] = $ownerId;
    $contentArr['mail_subject'] = $subject;
	//End code for section name and url
        Modules::run('systemMailer/SystemMailer/republishAbuseEntity', $email, $contentArr);

	//Send mail to users who reported abuse on the entity
	$this->sendRejectionAbuseMail($entityId,$ownerId,$entityType,$penalty);
  }

function getResponseViewerMainPage($tabStatus) {
    $displayData                 = array();
    $validity                    = $this->checkUserValidation();
    $displayData['validateuser'] = $validity;
    $cmsUserInfo                 = $this->cmsUserValidation();
    $displayData['headerTabs']   = $cmsUserInfo['headerTabs'];
    if($cmsUserInfo['usergroup'] !='enterprise'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }
    
    $displayData['tabStatus']    = $tabStatus;
    
    $clientId                    = $validity[0]['userid'];
    
    $responseViewerLib           = $this->load->library('response/responseViewerLib');
    $listings                    = $responseViewerLib->getInstituteResponseCountForClientId($tabStatus,$clientId);

    if($listings  == 'no_listing_found'){
        $displayData['no_listing_found'] = true;
        $listings                        = array();   
    }
    
    $displayData['prodId']       = RESPONSE_VIEWER_TAB_ID;
    $displayData['listings']     = $listings;
    $displayData['myProducts']   = $cmsUserInfo['myProducts'];
    $displayData['usergroup']    = 'enterprise';
    $displayData['elasticFlag']  = COURSE_VIEWER_ELASTIC_FLAG;


    // Start Online form change by pranjul 13/10/2011
    $this->load->library('OnlineFormEnterprise_client');
    $ofObj                                      = new OnlineFormEnterprise_client();
    $displayData['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($clientId);
        

    $this->load->view('enterprise/cms_homepage', $displayData);
}

function getListingsResponsesForClient($tabStatus = 'live') {
    set_time_limit(10);
	if(strtolower($tabStatus) == 'deleted') {
		echo "Currently this functionality is disabled"."  <a href='/enterprise/Enterprise/getListingsResponsesForClient'>Back</a>";
			return;
	}
    $this->init();
    
    $validity                    = $this->checkUserValidation();
    $clientId                   = $validity[0]['userid'];

    if(COURSE_VIEWER_ELASTIC_FLAG && !in_array($clientId, array('6120188','5309872','7609007'))){    
        $this->getResponseViewerMainPage($tabStatus);
    }else{
        $displayData                 = array();
        $validity                    = $this->checkUserValidation();
        $displayData['validateuser'] = $validity;
        $cmsUserInfo = $this->cmsUserValidation();
        $displayData['headerTabs'] = $cmsUserInfo['headerTabs'];
        if($cmsUserInfo['usergroup']!='enterprise'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }

        $displayData['tabStatus']   = $tabStatus;

        $clientId                   = $validity[0]['userid'];
        
        $this->load->library('Listing_client');
        $listingClient              = new Listing_client;
        $institutesForClient        = $listingClient->getListingsByClientForType($appId, $clientId, array('institute','university_national'), 0, 1000, $tabStatus);    
        $universitiesForClient      = $listingClient->getListingsByClientForType($appId, $clientId, array('university'), 0, 1000, $tabStatus);    
        $listingsForClient          = array_merge($institutesForClient, $universitiesForClient);
        $listings = array();
        
        if(count($listingsForClient)) {
            $this->load->library('lmsLib');
            $LmsClientObj = new LmsLib();

            
            $listings = json_decode($LmsClientObj->getInstituteResponseCountForClientId($appId,$clientId,$tabStatus), true);     
                       
            
            
            $locations = array();
            foreach($listingsForClient as $listing) {           
                if($listing['listing_type'] == 'university_national') {
                    $listing['listing_type'] = 'institute';
                }
                $locations[$listing['listing_type']][$listing[$listing['listing_type'].'_location_id']] = TRUE;
            }
            //_P($locations);
            foreach($listings as $index => $listing) {
                if(!((isset($listing['institute_id']) && ($locations['institute'][$listing['locationId']] == TRUE)) || (isset($listing['university_id']) && $locations['university'][$listing['locationId']] == TRUE))) {
                    unset($listings[$index]);
                }
            }
            //_P($listings);
        }
        
        $displayData['listingsForClient'] = $listingsForClient;
        $displayData['prodId']            = RESPONSE_VIEWER_TAB_ID;
        $displayData['listings']          = $listings;
        $displayData['myProducts']        = $cmsUserInfo['myProducts'];
        $displayData['usergroup']         = 'enterprise';

       
        // Start Online form change by pranjul 13/10/2011
        $this->load->library('OnlineFormEnterprise_client');
        $ofObj                                      = new OnlineFormEnterprise_client();
        $displayData['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($clientId);
        // End Online form change by pranjul 13/10/2011
        //_P($displayData);
        $this->load->view('enterprise/cms_homepage', $displayData);
    }
}

function getResponsesForListing($listingId, $listingType, $searchCriteria = 'both', $locationId, $timeInterval = '7 day', $start=0, $count=100) {
    $this->init();
    
     if($count > 500) {
     	echo "<script>alert('You can only view a maximum of 100 responses at once.');</script>";
     	$count = 500;
     }
    
    $displayData                 = array();
    $validity                    = $this->checkUserValidation();
    $displayData['validateuser'] = $validity;
    $cmsUserInfo                 = $this->cmsUserValidation();
    $displayData['headerTabs']   = $cmsUserInfo['headerTabs'];
    $displayData['clientuserid'] = $cmsUserInfo['userid'];
    if($cmsUserInfo['usergroup']!='enterprise'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }
    
    $userId                      = $validity[0]['userid'];
    $appId                       = 1;
    $courseName                  = '';
    
    $this->load->model('listing/coursemodel');
    $this->load->model('listing/abroadcoursefindermodel');
    
    $this->load->library('Listing_client');
    $listing_client =  new Listing_client;
    
    $this->load->builder('ListingBuilder','listing');
    $listingBuilder = new ListingBuilder();
    $this->load->builder("nationalInstitute/InstituteBuilder");
    $instituteBuilder = new InstituteBuilder();
    $this->load->builder("nationalCourse/CourseBuilder");
    $courseBuilder = new CourseBuilder();
    
    $courseRepository          = $courseBuilder->getCourseRepository();
    $abroadCourseRepository    = $listingBuilder->getAbroadCourseRepository();
    $abroadInstituteRepository = $listingBuilder->getAbroadInstituteRepository();
    $instituteRepository       = $instituteBuilder->getInstituteRepository();
    $universityRepository      = $listingBuilder->getUniversityRepository();
    
    $typeId = null;
    if($listingType == 'university') {
        $universityObj = $universityRepository->find($listingId);
        $listingName   = $universityObj->getName();
        $cityId        = $universityObj->getLocation()->getCity()->getId();
        $cityName      = $universityObj->getLocation()->getCity()->getName();
        $countryId     = $universityObj->getLocation()->getCity()->getCountryId();
        $countryName   = $universityObj->getLocation()->getCountry()->getName();
        $typeId        = $listingId;
    	
    	$courses = $this->abroadcoursefindermodel->getCoursesOfferedByUniversity($listingId, 'list');
    	$courses = $courses['courses'];
    	
    	$locationDetails = array();
    	if($locationId > 1) {
            $locationDetails['cityId']      = $cityId;
            $locationDetails['cityName']    = $cityName;
            $locationDetails['countryId']   = $countryId;
            $locationDetails['countryName'] = $countryName;
    	}
    	
    } else if($listingType == 'institute') {    		
		
        $instituteObj  = $instituteRepository->find($listingId);    	
        $listingName   = $instituteObj->getName();
        $intitute_type = $instituteObj->getListingType(); 
    	if($intitute_type == 'university') {
			$listingName = $listingName."(University)";
		}
    	
        $typeId          = $listingId;
        
        $courses         = $listing_client->getCourseList($appId, $listingId, "live",$userId,FALSE);    	
        $locationDetails = array();
        if($locationId > 1) {
            $locationDetails = $listing_client->getInstituteLocationDetails($appId,$locationId);    	        	    
        }
    	
    } else if($listingType == 'course') {    		
    	$isAbroadCourse = $this->coursemodel->isStudyAboradListing($listingId, 'course');
    	
    	if($isAbroadCourse) {
    	    $courseObj = $abroadCourseRepository->find($listingId);
    	    $universityId = $courseObj->getUniversityId();
    	    $listingName = $courseObj->getUniversityName();
    	    $courseName = $courseObj->getName();
    	    $typeId = $universityId;
    	    
    	    $universityObj = $universityRepository->find($universityId);
    	    $cityId = $universityObj->getLocation()->getCity()->getId();
    	    $cityName = $universityObj->getLocation()->getCity()->getName();
    	    $countryId = $universityObj->getLocation()->getCity()->getCountryId();
    	    $countryName = $universityObj->getLocation()->getCountry()->getName();
    	    
    	    $courses = $this->abroadcoursefindermodel->getCoursesOfferedByUniversity($universityId, 'list');
    	    $courses = $courses['courses'];
    	    
    	    $locationDetails = array();
    	    if($locationId > 1) {
        		$locationDetails['cityId'] = $cityId;
        		$locationDetails['cityName'] = $cityName;
        		$locationDetails['countryId'] = $countryId;
        		$locationDetails['countryName'] = $countryName;
    	    }
    	    
	    } else {    	
    	    $courseObj = $courseRepository->find($listingId);
    	    //_P($courseObj);exit;
    	    $instituteId = $courseObj->getInstituteId();
    	    $courseName = $courseObj->getName();
    	    $listingName = $courseObj->getInstituteName();
    	    $institute_type = $courseObj->getInstituteType();
    	    if($institute_type == 'university') {
				$listingName = $listingName."(University)";
			}
    	    
    	    $typeId = $instituteId;
    	    
    	    $courses = $listing_client->getCourseList($appId, $instituteId, "live", $userId,FALSE);
    	    
    	    $locationDetails = array();
    	    if($locationId > 1) {
    		    $locationDetails = $listing_client->getInstituteLocationDetails($appId,$locationId);
    	    }
    	}
    }
    
    $paidCourses = array();    
    foreach($courses as $course) {
    	if($listingType == 'university' || $isAbroadCourse) {
    	    $courseObj = $abroadCourseRepository->find($course['courseID']);
    	}
    	else {						
			//_P($courseRepository);
    	    $courseObj = $courseRepository->find($course['courseID']);
    	    //_P($courseObj);
    	}
    	    	
    	if($courseObj->isPaid()) {
    	    $paidCourses[] = $course;
    	}
    }
    $courses = $paidCourses;
    //_P($courses);
    
    $this->load->library('lmsLib');
    $LmsClientObj = new LmsLib();
    $clientId = $validity[0]['userid'];

    if(COURSE_VIEWER_ELASTIC_FLAG && !in_array($clientId, array('6120188','5309872','7609007'))){         
        $responseViewerLib = $this->load->library('response/responseViewerLib');
        $responses         = $responseViewerLib->getResponsesForListingId($clientId,$listingId,$listingType,$searchCriteria,$timeInterval,$start,$count,$locationId);            
    }else{        
        $responses = $LmsClientObj->getResponsesForListingId($appId, $clientId, $listingId, $listingType, $searchCriteria, $timeInterval, $start, $count,$locationId);
        $responses = json_decode($responses, true);    
    }
    if($responses['userIds']!='') {
    	$this->load->model('QnAModel');
    	$responsesQuestions = $this->QnAModel->getResponseQnADetailsForListingId($responses['userIds'],$responses['courseIds']);
    	$displayData['qnaInfoForListing']=(array)($responsesQuestions);
    }

    $displayData['resultResponse']            = $responses;
    $displayData['resultResponse']['numrows'] = $responses['totalResponses'];
    $displayData['prodId']                    = RESPONSE_VIEWER_TAB_ID;
    $displayData['courseName']                = $courseName;
    $displayData['instituteName']             = $listingName;
    $displayData['instituteLocation']         = $locationDetails['countryId'] == 2 ? 'india' : 'abroad';
    $displayData['instituteId']               = $typeId;
    $displayData['listingId']                 = $listingId;
    $displayData['listingType']               = $listingType;
    $displayData['courses']                   = $courses;
    $displayData['searchCriteria']            = $searchCriteria;
    $displayData['startOffset']               = $start;
    $displayData['countOffset']               = $count;
    $displayData['timeInterval']              = $timeInterval;
    $displayData['locationId']                = $locationId;
    $displayData['locationDetails']           = $locationDetails;
    $displayData['csvURL']                    = str_replace(__FUNCTION__, 'getCSVResponsesForListing', $_SERVER['REQUEST_URI']);    
    // Start Online form change by pranjul 13/10/2011
    $this->load->library('OnlineFormEnterprise_client');
    $ofObj = new OnlineFormEnterprise_client();
    $displayData['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($clientId);
    $this->load->view('enterprise/listingsResponsesView', $displayData);
}

function getCSVResponsesForListing($listingId, $listingType, $searchCriteria = 'both', $locationId, $timeInterval = '7 day', $start=0, $count=10) {
    $this->init();
    $validity = $this->checkUserValidation();
    $cmsUserInfo = $this->cmsUserValidation();
    if($cmsUserInfo['usergroup']!='enterprise'){
        header("location:/enterprise/Enterprise/disallowedAccess");
        exit();
    }

    $extraParams = array();
    $resposneIds = $this->input->post('allocationIds',true);           
    if($resposneIds){
        $extraParams['responseIds'] = json_decode($resposneIds);        
    }

    $clientId = $validity[0]['userid'];
    $this->getResponsesCSVForListing($listingId, $listingType, $searchCriteria, $locationId, $clientId, $timeInterval, $start, $count, True, True,'','','live',$extraParams);
}

function getResponsesCSVForListing($listingId, $listingType, $searchCriteria = 'both', $locationId, $clientId, $timeInterval = '7 day', $start=0, $count=10, $showHeader=True, $export=True, $startDate = '', $endDate = '', $tabStatus = 'live',$extraParams=array()) {  
    $this->load->model('listing/coursemodel');
    $this->load->model('listing/abroadcoursefindermodel');
    $this->load->library('Listing_client');
    $listing_client   =  new Listing_client;
    $this->load->builder('ListingBuilder','listing');
    $listingBuilder   = new ListingBuilder();
    $this->load->builder("nationalInstitute/InstituteBuilder");
    $instituteBuilder = new InstituteBuilder();
    $this->load->builder("nationalCourse/CourseBuilder");
    $courseBuilder    = new CourseBuilder();
    
    $courseRepository          = $courseBuilder->getCourseRepository();
    $abroadCourseRepository    = $listingBuilder->getAbroadCourseRepository();
    $abroadInstituteRepository = $listingBuilder->getAbroadInstituteRepository();
    $instituteRepository       = $instituteBuilder->getInstituteRepository();
    $universityRepository      = $listingBuilder->getUniversityRepository();
    
    global $responseActionViewMapping;
    $appId = 1;
    $courseName = '';
    
    if($listingType == 'university') {
        $universityObj = $universityRepository->find($listingId);
        $listingName   = html_entity_decode($universityObj->getName());
        $cityId        = $universityObj->getLocation()->getCity()->getId();
        $cityName      = $universityObj->getLocation()->getCity()->getName();
        $countryId     = $universityObj->getLocation()->getCity()->getCountryId();
        $countryName   = $universityObj->getLocation()->getCountry()->getName();
    	
    	$locationDetails = array();
    	if($locationId > 1) {
    	    $locationDetails['cityId'] = $cityId;
    	    $locationDetails['cityName'] = $cityName;
    	    $locationDetails['countryId'] = $countryId;
    	    $locationDetails['countryName'] = $countryName;
    	}
    }
    else if($listingType == 'institute') {
        $instituteObj   = $instituteRepository->find($listingId);
        $listingName    = html_entity_decode($instituteObj->getName());
        $institute_type = $instituteObj->getListingType();
    	if($institute_type == 'university') {
			$listingName = $listingName."(University)";
		}

    	if(empty($listingName) || $listingName == '' ) {
            $this->load->model('nationalInstitute/institutemodel');
            $listing_details = $this->institutemodel->getInstituteBasicsDataByStatus($listingId,$tabStatus);
            $listingName     = $listing_details[$listingId]['listing_title'];
        }
        $locationDetails = array();
    	if($locationId > 1) {
    	    $locationDetails = $listing_client->getInstituteLocationDetails($appId,$locationId,$tabStatus);
    	}
    }
    else if($listingType == 'course') {
    	$isAbroadCourse = $this->coursemodel->isStudyAboradListing($listingId, 'course', $tabStatus);
    	
    	if($isAbroadCourse) {
    	    $courseObj = $abroadCourseRepository->find($listingId);
    	    $universityId = $courseObj->getUniversityId();
    	    $listingName = html_entity_decode($courseObj->getUniversityName());
    	    $courseName = $courseObj->getName();
    	    
            $universityObj = $universityRepository->find($universityId);
            $cityId        = $universityObj->getLocation()->getCity()->getId();
            $cityName      = $universityObj->getLocation()->getCity()->getName();
            $countryId     = $universityObj->getLocation()->getCity()->getCountryId();
            $countryName   = $universityObj->getLocation()->getCountry()->getName();
    	    
    	    
    	    $locationDetails = array();
    	    if($locationId > 1) {
                $locationDetails['cityId']      = $cityId;
                $locationDetails['cityName']    = $cityName;
                $locationDetails['countryId']   = $countryId;
                $locationDetails['countryName'] = $countryName;
    	    }
	   }
    	else {
            $courseObj      = $courseRepository->find($listingId);
            $courseName     = $courseObj->getName();
            $listingName    = html_entity_decode($courseObj->getInstituteName());
            $institute_type = $courseObj->getInstituteType();
    	    if($institute_type == 'university') {
    			$listingName = $listingName."(University)";
    		}
    	    
    	    $locationDetails = array();
    	    if($locationId > 1) {
    			$locationDetails = $listing_client->getInstituteLocationDetails($appId,$locationId,$tabStatus);
    	    }
    	}
    }
    
    $instituteLocation = $locationDetails['countryId'] == 2 ? 'india' : 'abroad';
    
    if(COURSE_VIEWER_ELASTIC_FLAG && !in_array($clientId, array('6120188','5309872','7609007'))){ 
        $responseViewerLib = $this->load->library('response/responseViewerLib');
        $responses         = $responseViewerLib->getResponsesForListingId($clientId,$listingId,$listingType,$searchCriteria,$timeInterval,$start,$count,$locationId,$startDate, $endDate, $tabStatus,$extraParams['responseIds']);    
    }else{
        $this->load->library('lmsLib');
        $LmsClientObj = new LmsLib();
        $responses    = $LmsClientObj->getResponsesForListingId($appId, $clientId, $listingId, $listingType, $searchCriteria, $timeInterval, $start, $count, $locationId, $startDate, $endDate, $tabStatus,$extraParams['responseIds']);    
        $responses    = json_decode($responses, true);    
    }


    $filename = $listingName;
    $filename =preg_replace('/[^A-Za-z0-9]/', '',$filename);
    if($instituteLocation == "india"){
        $csvFields = array(
                'name'           => 'Name',
                'firstname'      => 'First Name',
                'lastname'       => 'Last Name',
                'institute_name' => 'Institute Name',
                'listing_title'  => 'Response to',
                'city'           => 'City',
                'locality'       => 'Locality',
                'CurrentCity'    => 'Current Location',
                'localityName'   => 'Current Locality',
                'submit_date'    => 'Response Date',
                'action'         => 'Source',
                'email'          => 'Email',
                'IsdCode'        => 'ISD Code',
                'mobile'         => 'Mobile',
                'isNDNC'         => 'Is in NDNC List',
                'exams_taken'    => 'Exams Taken',
                'experience'     => 'Work Experience'
            );
    }
    else{
        $csvFields = array(
                'name'                 => 'Name',
                'firstname'            => 'First Name',
                'lastname'             => 'Last Name',
                'email'                => 'Email',
                'IsdCode'              => 'ISD Code',
                'mobile'               => 'Mobile',
                'institute_name'       => 'Institute Name',
                'submit_date'          => 'Response Date',
                'listing_title'        => 'Response to',
                'field_of_interest'    => 'Field of Interest',
                'desired_course_level' => 'Desired Course Level',
                'Specialization'       => 'Specialization',
                'exams_taken'          => 'Exams Taken',
                'preferred_country'    => 'Preferred Country',
                'plan_to_start'        => 'Plan to Start',
                'student_passport'     => 'Student Passport',
                'CurrentCity'          => 'Current Location',
                'isNDNC'               => 'Is in NDNC List',
                'action'               => 'Source'
		    );
    }
    
    $csv = '';
    if($showHeader) {
        foreach($csvFields as $csvField) {
            $csv .= '"'. $csvField .'",';
        }
    }
    
    $resultResponse = $responses;
    foreach($resultResponse['responses'] as $response) {
        $userId  = $response['userId'];
        $row     = $resultResponse['userIdDetails'][$userId];
        $work_ex = $resultResponse['userIdDetails'][$userId]['experience'];
        //To check highest education -- A crappy logic which will suck big time.
        $previousEducationLevel = 0;
        $highestEducation       = 'N.A.';
        foreach($row['EducationData'] as $education) {
            $educationLevels = array(
                // 0 for Crap (levels we're not catering)
                1 => '10',
                2 => '12',
                3 => 'UG',
                4 => 'PG'
            ); // Product don't want to cater anything apart from these above.
            $currentEducationLevel = array_search($education['Level'], $educationLevels);
            if($previousEducationLevel < $currentEducationLevel) {
                $highestEducation       = $education['Name'];
                $previousEducationLevel = $currentEducationLevel;
            }
        }

        $educationInterest = $row["PrefData"][0]["SpecializationPref"][0]["CategoryName"];
	    
        if($instituteLocation !== "india"){
	    
	        if($educationInterest == "All") {
                $courseNameArray     = array();
                $specializationArray = array();

                foreach($row["PrefData"][0]['SpecializationPref'] as $value){
                    if (isset($value['blogTitle'])) {
                        array_push($courseNameArray,$value['blogTitle']);
                    } 
                    else {
                        if(!in_array($value['CourseName'], $courseNameArray)){
                            array_push($courseNameArray,$value['CourseName']);
                        }
                        if(!in_array($value['SpecializationName'],$specializationArray)){
                            array_push($specializationArray,$value['SpecializationName']);
                        }
                    }
                }
		
                $educationInterest = implode(",&nbsp;<wbr/>",$courseNameArray);
            }
	    }

        $spec        = array();
        $courseLevel = "";
        $prefDetails = $row["PrefData"][0];
        $datediff    = datediff($prefDetails['TimeOfStart'],$prefDetails['SubmitDate']);
	    
        foreach($prefDetails["SpecializationPref"] as $spec_details){
            $spec[]      = $spec_details["SpecializationName"];
            $courseLevel = $spec_details['CourseLevel1'];
            if($courseLevel == 'UG') {
        		if($instituteLocation == "india"){
        		    $courseLevel = 'Graduation';
        		}
        		else {
        		    $courseLevel = 'Bachelors';
        		}
            }
            if($courseLevel == 'PG') {
        		if($instituteLocation == "india"){
        		    $courseLevel = 'Post Graduation';
        		}
        		else {
        		    $courseLevel = 'Masters';
        		}
            }
        }
        $SpecializationName = implode(", ",$spec);

        $modeArray = array();
        if($prefDetails['ModeOfEducationFullTime'] == 'yes')
            array_push($modeArray, "Full Time");
        if($prefDetails['ModeOfEducationPartTime'] == 'yes')
            array_push($modeArray, "Part Time");
        if($prefDetails['ModeOfEducationDistance'] == 'yes')
            array_push($modeArray, "Distance");
        $mode = implode(", ",$modeArray);

        $prefLocationArray = array();
        $localityArray     = array();
        foreach($prefDetails['LocationPref'] as $value) {
            if($value['CityId'] != 0 && $value['CityName'] !=""){
                    $localityArray[$value['CityName']];
                    foreach ($prefDetails['LocationPref'] as $value1 ) {
                        if ($value1['CityId'] == $value['CityId']) {
                            if ($value['LocalityName'] != '') {
                                $localityArray[$value['CityName']][] = $value['LocalityName'];
                    break;
                            } else {
                                $localityArray[$value['CityName']][] = '-1';
                    break;
                            }
                        }
                    }
                    if(!in_array($value['CityName'], $prefLocationArray))
                        array_push($prefLocationArray,$value['CityName']);
            }
            else{
                    if($value['StateId'] != 0 && $value['StateName'] != ""){
                            array_push($prefLocationArray,"Anywhere in ".$value['StateName']);
                    }
                    else{
                            if($value['CountryId'] != 0 && $value['CountryName'] !=""){
                                    array_push($prefLocationArray,"Anywhere in ".$value['CountryName']);
                            }
                    }
            }
        }
        $str = '';
        $m   = 1;
        $len = count($localityArray);
        foreach ($localityArray as $key=>$value) {
            if ($value[0] != '-1') {
                $str .= $key .' : ' . implode(", ",$value);
                $str = str_replace(', -1', '', $str);
            }
            if ($value[0] != '-1' && $m < $len) {
                $str .= ', ';
            }
            $m++;
        }

        $degreePrefArray = array();
        if($prefDetails['DegreePrefAny'] == 'yes')
            array_push($degreePrefArray, "Any");
        if($prefDetails['DegreePrefUGC'] == 'yes')
            array_push($degreePrefArray, "UGC approved");
        if($prefDetails['DegreePrefAICTE'] == 'yes')
            array_push($degreePrefArray, "AICTE approved");
        if($prefDetails['DegreePrefInternational'] == 'yes')
            array_push($degreePrefArray, "Internatonal");


        $pursuingEducation  = array();
        $completedEducation = array();
        $competitiveExam    = "";
        $xii_details        = "";
        $ug_details         = "";
        $pg_details         = "";
        $xii_year           = "";
        $ug_year            = "";
        $pg_year            = "";
        foreach($row['EducationData'] as $value){
            $divRow = array();

            if($value['Level'] == 12){
                $divRow['ValueName'] = $value['Name'];
                $divRow['Value']     = ($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';
                $completiondate      = $value['Course_CompletionDate'];
                $completiondate      = explode(" ", $completiondate);
                $divRow['Value']    .= (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                $xii_details         = $divRow['ValueName'].($divRow['Value'] != '' ? $divRow['Value'] : '');
                $xii_year            = (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )? "":date('Y',strtotime($value['Course_CompletionDate'])));
            }
            
            if($value['Level'] == "UG"){
                if($value['OngoingCompletedFlag'] == 1) {
                    $divRow['Title']     = "Pursuing ";
                    $divRow['ValueName'] = $value['Name'];
                    $divRow['Value']     = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                    $ug_details          = $divRow["Title"]." ".$divRow['ValueName'].($divRow['Value'] != '' ? $divRow['Value'] : '');
                }
                else{
                    $divRow['Title']     = "Completed ";
                    $divRow['ValueName'] = $value['Name'];
                    $divRow['Value']     = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                    $divRow['Value']    .=($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';
                }		
                $ug_details = $divRow["Title"]." ".$divRow['ValueName'].($divRow['Value'] != '' ? $divRow['Value'] : '');
                $ug_year    = (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" ) ? "" : date('Y',strtotime($value['Course_CompletionDate'])));
            }

            if($value['Level'] == "PG"){
                if($value['OngoingCompletedFlag'] == 1) {
                    $divRow['Title']     = "Pursuing ";
                    $divRow['ValueName'] = $value['Name'];
                    $divRow['Value']     = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                }
                else {
                    $divRow['Title']     = "Completed ";
                    $divRow['ValueName'] = $value['Name'];
                    $divRow['Value']     = $value['institute_name'].($value['city_name'] != ""?", ".$value['city_name']:"").(($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":" (".$value['Course_CompletionDate'].") ");
                    $divRow['Value']     .=($value['Marks']!=0)?", ".$value['Marks']." ".$value['MarksType']:'';
                }
                $pg_details = $divRow["Title"]." ".$divRow['ValueName'].($divRow['Value'] != '' ? $divRow['Value'] : '');
                $pg_year    = (($value['CourseCompletionDate']=="0000-00-00 00:00:00" || $value['CourseCompletionDate']=="" )?"":date('Y',strtotime($value['Course_CompletionDate'])));
            }

            if($value['Level'] == "Competitive exam"){
				
                $examObj              = \registration\builders\RegistrationBuilder::getCompetitiveExam($value['Name'],$value);
                if($competitiveExam) {
                    $competitiveExam.= ', ';
                }
                if($instituteLocation == "india") {
                    $competitiveExam.= $value['Name'];
                } else {
                    $competitiveExam.= $examObj->displayExam();
                }
				
            }

            if($value['OngoingCompletedFlag'] == 1){
                array_push($pursuingEducation, $divRow);
            }
            else{
                array_push($completedEducation , $divRow);
            }
        }

        $prefCountryArray= array();
        foreach($prefDetails['LocationPref'] as $value) {
            if($value['CountryId'] != 0 && $value['CountryName'] !=""){
                if(!in_array($value['CountryName'],$prefCountryArray))
                    array_push($prefCountryArray,$value['CountryName']);
            }
        }

        $sourcesFund = "";
        if($prefDetails['UserFundsOwn'] == "yes" || $prefDetails['UserFundsNone'] == "yes" || $prefDetails['UserFundsBank'] == "yes") {
            $sources     = array();
            $sources[]   = $prefDetails['UserFundsOwn'] == "yes" ? 'Own ' : '';
            $sources[]   = $prefDetails['UserFundsBank'] == "yes" ? 'Bank Loan ' : '';
            $sources[]   = $prefDetails['UserFundsNone'] == "yes" ? 'Others ' : '';
            $sourcesFund = trim(implode(', ', $sources),', ');
        }
        $plan_to_start = "";
        if($prefDetails['TimeOfStart'] != "") {
			
			if($prefDetails['YearOfStart'] == '0000') {
				$plan_to_start = "Not Sure";
			}
			else if($prefDetails['ExtraFlag'] == 'testprep') {
				$plan_to_start = ($datediff!=0)?"Within ".$datediff:"Immediately";
			}
			else if($prefDetails['ExtraFlag'] == 'studyabroad') {
			    
				if($prefDetails['YearOfStart'] == date('Y'))
				    $plan_to_start = 'Current Year';
				else if($prefDetails['YearOfStart'] == date('Y')+1)
				    $plan_to_start = 'Next Year';
				else if($prefDetails['YearOfStart'] > date('Y')+1)
				    $plan_to_start = 'Not Sure';
				    
			}	
			else {
				$plan_to_start = $prefDetails['YearOfStart'];
			}
			
            //$plan_to_start = ($prefDetails['YearOfStart']!='0000')?(($datediff!=0)?"Within ".$datediff:"Immediately"):"Not Sure";
        }
                        
        $experience = getExperienceText($row['experience']);
        
        $pref_time_to_call = "";
        if(is_numeric($prefDetails['suitableCallPref'])) {
            $preferenceCallTimeArray = array('0'=>'Do not call','1'=>'Anytime','2'=>'Morning', '3'=>'Evening');
            $pref_time_to_call       = $preferenceCallTimeArray[$prefDetails['suitableCallPref']];
        }

        global $IVR_Action_Types;
        $viewedAction = false;
        if(in_array($response['action'], $IVR_Action_Types)){
            $response['listing_title'] = Inst_Viewed_Action_Course;
            $viewedAction              = true;
        }
        
        $csv .= "\n";
        foreach ($csvFields as $csvFieldId => $csvField){
            $val = '';
            switch($csvFieldId) {
                case 'institute_name': $val = $listingName; break;
                case 'id': $val = $response['id']; break;
                case 'email': $val = $row['email']; break;
                case 'mobile': $val = $row['mobile']; break;
                case 'highestEdu': $val = $highestEducation; break;
                case 'gender': $val = $row['gender']; break;
                case 'name': $val = $row['firstname'].' '.$row['lastname']; break;
                case 'firstname': $val = $row['firstname']; break;
                case 'lastname': $val = $row['lastname'];  break;
                case 'listing_title': $val = $response['listing_title']; break;
                case 'submit_date': $val = $response['submit_date']; break;
                case 'action': $val = $responseActionViewMapping[$response['action']] ? $responseActionViewMapping[$response['action']] : $response['action']; break;
                case 'CurrentCity': $val = $row['CurrentCity']; break;
                case 'localityName': $val = $row['localityName']; break;
                case 'age': $val = !empty($row['age']) ? $row['age'] .'years' : '' ; break;
                case 'isNDNC': $val = $row['isNDNC']; break;
                case 'education_interest': $val = $educationInterest;break;
                case 'Specialization': $val = $SpecializationName;break;
                case 'Mode': $val = $mode;break;
                case 'preferred_locations': $val = implode(", ",$prefLocationArray);break;
                case 'preferred_localities': $val = $str;break;
                case 'degree_preference': $val = implode(", ",$degreePrefArray);;break;
                case 'plan_to_start': $val = $plan_to_start;break;
                case 'work_experience': $val = $experience;break;
                case 'xii_details': $val = $xii_details;break;
                case 'ug_details': $val = $ug_details;break;
                case 'pg_details': $val = $pg_details;break;
                case 'xii_year': $val = $xii_year;break;
                case 'ug_year': $val = $ug_year;break;
                case 'pg_year': $val = $pg_year;break;
                case 'exams_taken': $val = $competitiveExam;break;
                case 'preferred_country': $val = implode(', ',$prefCountryArray);break;
                case 'field_of_interest': $val = $educationInterest;break;
                case 'desired_course_level': $val = $courseLevel;break;
                case 'creation_date': $val = $row['CreationDate'];break;
                case 'sources_funding': $val = $sourcesFund;break;
                case 'pref_time_to_call': $val = $pref_time_to_call;break;
                case 'city': $val = $viewedAction?'':$locationDetails['cityName'];break;
                case 'locality': $val = $viewedAction?'':$locationDetails['localityName'];break;
                case 'student_passport': $val = $row['passport'];break;
                case 'IsdCode':$val = $row['isdCode'];break;
                case 'experience':$val = $experience;break;
                    
            }
            $csv .= '"'.$val.'",';
        }
    }

    
     $searchCriteria4Tracking = array();
     if($searchCriteria == 'courseOnly'){
        $searchCriteria4Tracking[$locationId]['course_id']      = $listingId;
        $searchCriteria4Tracking[$locationId]['course_name']    = $courseName;
     }else{
        $searchCriteria4Tracking[$locationId]['institute_id']   = $listingId;
     }
     $searchCriteria4Tracking[$locationId]['institute_name'] = $listingName;

     $searchCriteria4Tracking[$locationId]['location_id']   = $locationId;
     
     if($locationDetails['cityName'])
        $searchCriteria4Tracking[$locationId]['city_name']     = $locationDetails['cityName'];
     
     if($locationDetails['localityName'])
        $searchCriteria4Tracking[$locationId]['locality_name'] = $locationDetails['localityName'];

    if(!empty($timeInterval) && $timeInterval != 'none'){
        $searchCriteria4Tracking[$locationId]['no_of_days'] = $timeInterval.'s';        
    }
    
    if($export) {
        $trackingParams                     = array();
        $trackingParams['product']          = 'CourseResponse';
        $trackingParams['page_tab']         = 'ResponseDetailPage';
        $trackingParams['cta']              = 'Download';
        $trackingParams['entity_id']        = $listingId;
        $trackingParams['search_criteria']  = json_encode($searchCriteria4Tracking);
        $trackingParams['records_fetched']  = count($resultResponse['responses']);
        $enterpriseTrackingLib = $this->load->library('enterprise/enterpriseDataTrackingLib');
        $enterpriseTrackingLib->trackEnterpriseData($trackingParams);
        header("Content-type: text/x-csv");
        header("Content-Disposition: attachment; filename=". $filename .".csv");
        echo $csv;
    }
    else {
        return array('csv' => $csv,'searchCriteria'=>$searchCriteria4Tracking,'records_fetched' => count($resultResponse['responses']));
    }
}
	//To add spotlight events
	function addSpotlightEvent(){
	$this->init();
	$eventId1=$_REQUEST['event_id_1'];
	$eventId2=$_REQUEST['event_id_2'];
	$paidEventId=$_REQUEST['paidEventId'];
	$tillDate=$_REQUEST['till_date'];
	if($paidEventId!=null && $paidEventId!=''){
	$appId = 1;
        if($_FILES['uploadedImage']['tmp_name'][0] != '') {
           $this->load->library('upload_client');
           $uploadclient = new upload_client();
           $uploadres = $uploadclient->uploadfile($appId,'image',$_FILES,"Paid Event",$paidEventId,"event","uploadedImage");
           if(is_array($uploadres)) {
               $logolink = $uploadres[0]['thumburl_m'];
           }
      	}
	}
	$enterpriseClientObj = new Enterprise_client();
	$spotlightEventId=$enterpriseClientObj->addSpotlightEvent($appId=1,$eventId1,$eventId2,$paidEventId,$logolink,$tillDate);
	if($spotlightEventId!=-1){
	$this->index(45,$spotlightEventId);
	}else{
	$this->index(45,"updated");
	}
	}

  function archiveUserAbuse(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$entityId = trim($this->input->post('entityId'));
	$ownerId = trim($this->input->post('ownerId'));
	$entityType=trim($this->input->post('entityType'));
	$threadId=trim($this->input->post('threadId'));
	//Set the entity as removed in the Abuse log table
	$status = 'Archived';
	$entObj = new Enterprise_client();
	$resultUserAbuseRemove = $entObj->updateStatusAbuseList($appId,$loggedInUserId,$entityId,$entityType,$status);

	echo "1";
  }

  function rejectUserAbuse(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$entityId = trim($this->input->post('entityId'));
	$ownerId = trim($this->input->post('ownerId'));
	$entityType=trim($this->input->post('entityType'));
	$threadId=trim($this->input->post('threadId'));
	$penalty=trim($this->input->post('penalty'));
	$entityTypeShown = getAbuseEntityName($entityType);

	//Set the entity as deleted in their respective tables
	$entObj = new Enterprise_client();
	//Write code to deduct points of users who have reported abuse in case of with Penalty

	//Set the entity as removed in the Abuse log table
	$status = ($penalty=="1")?'Rejectwp':'Rejectwop';
	$resultUserAbuseRemove = $entObj->updateStatusAbuseList($appId,$loggedInUserId,$entityId,$entityType,$status);
	
	//redis notification call
	$this->load->model('messageBoard/AnAModel');
        $reportAbuseThreadData = $this->AnAModel->getReportAbuseThreadData($threadId);
	$reportAbuseEntityData = $this->AnAModel->getReportAbuseEntityData($entityId,$entityTypeShown);
	
	
		$reportAbuseUserData = $this->AnAModel->getReportAbuseUserData($entityId);
		
		foreach($reportAbuseUserData as $key=>$val){    
		    $reportAbuser[] = $val['userId'];
		    $reportAbuseDate[] = $val['creationDate'];    
		}
		
		if($reportAbuseThreadData[0]['fromOthers'] == 'user'){
		    $threadType = 'question';
		}else{
		    $threadType = $reportAbuseThreadData[0]['fromOthers'];
		}
		
		$entityStatus = $reportAbuseEntityData[0]['status'];
		$entityTitle = $reportAbuseEntityData[0]['entityTitle'];
		$threadTitle = $reportAbuseThreadData[0]['threadTitle'];
		
		if($threadType == 'discussion'){
		    $mainDiscussionId = $threadId +1;
		    $discussionDetail = $this->AnAModel->getReportAbuseThreadData($mainDiscussionId);
		    $threadTitle = $discussionDetail[0]['threadTitle'];
		    if($threadId == $entityId){
			$entityStatus = $reportAbuseThreadData[0]['status'];
			$entityTitle = $threadTitle;
		    }
	       }
		
		$this->appNotification = $this->load->library('Notifications/NotificationContributionLib');
		$this->appNotification->addReportAbuseNotificationToRedis($entityId,$entityTypeShown,$threadId,$threadType,$threadTitle,$ownerId,$reportAbuser,$status,$entityStatus,$entityTitle,$reportAbuseDate);
	

	//Send mail to the owner about the action
	//$this->sendRepublishAbuseMail($ownerId,$entityId,$entityType,$penalty);
	echo "1";
  }

  function sendRejectAbuseMail(){
        error_log_shiksha("CONTROLLER sendRejectAbuseMail ");
	$entityId = trim($this->input->post('entityId'));
	$ownerId = trim($this->input->post('ownerId'));
	$entityType=trim($this->input->post('entityType'));
	$threadId = trim($this->input->post('threadId'));
	$penalty=trim($this->input->post('penalty'));
	$this->init();
	//Send mail to owner informing that abuse reports on his entity has been rejected
    /***
    // Stop sending mail to owner informing that abuse reports on his entity has been rejected(MAB-1507)
	$msgbrdClient = new Message_board_client();
        $userDetails = $msgbrdClient->getUserDetails(1,$ownerId);
        $details = $userDetails[0];
        $email = $details['email'];
        $fromMail = "noreply@shiksha.com";
        $ccmail = "sales@shiksha.com";
	$entityTypeText = getAbuseEntityName($entityType);
        $subject = "Abuse report(s) on your ".$entityTypeText." have been rejected.";
        $urlOfCommunityPage = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/communityGuideline";
        $contentArr['name'] = ($details['firstname']=='')?$details['displayname']:$details['firstname'];
        $contentArr['communityUrl'] = $urlOfCommunityPage;
	$contentArr['entityType'] = $entityTypeText;
	$contentArr['type'] = 'rejectAbuseMail';
	//Get the section name and url based on the entity type
	$this->load->library('MailerClient');
	$MailerClient = new MailerClient();
	if($entityType == 'Blog Comment'){
	  $urlOfLandingPage = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';
	  $contentArr['section'] = 'Shiksha.com Articles';
	}
	else if($entityType == 'Event Comment' || $entityType == 'Event'){
	  $urlOfLandingPage = SHIKSHA_EVENTS_HOME;
	  $contentArr['section'] = 'Shiksha.com Important Dates';
	}
	else{
	  $urlOfLandingPage = SHIKSHA_ASK_HOME;
	  $contentArr['section'] = 'Shiksha Ask & Answer';
	}
	$contentArr['sectionURL'] = $urlOfLandingPage;
    $contentArr['receiverId'] = $ownerId;
    $contentArr['mail_subject'] = $subject;
	//End code for section name and url
    Modules::run('systemMailer/SystemMailer/rejectAbuseEntity', $email,$contentArr);
    */

	//Send mail to users who reported abuse on the entity and tell them that it has been rejected
	$this->sendRejectionAbuseMail($entityId,$ownerId,$entityType,$penalty);
  }

  function sendRejectionAbuseMail($entityId,$ownerId,$entityType,$penalty)
  {
	$this->init();

	//Get the section name and url based on the entity type
	$this->load->library('MailerClient');
	$MailerClient = new MailerClient();
	if($entityType == 'Blog Comment'){
	  $urlOfLandingPage = SHIKSHA_HOME.'/blogs/shikshaBlog/showArticlesList';
	  $section = 'Shiksha.com Articles';
	}
	else if($entityType == 'Event Comment' || $entityType == 'Event'){
	  $urlOfLandingPage = SHIKSHA_EVENTS_HOME;
	  $section = 'Shiksha.com Important Dates';
	}
	else{
	  $urlOfLandingPage = SHIKSHA_ASK_HOME;
	  $section = 'Shiksha Ask & Answer';
	}
	//End code for section name and url

	$msgbrdClient = new Message_board_client();
        $abuseUsersDetails = $msgbrdClient->getAbuseUsersDetails(1,$entityId,$entityType);
        $details = $abuseUsersDetails;
	foreach($abuseUsersDetails as $detail){
	    $email = $detail['email'];
	    $fromMail = "noreply@shiksha.com";
	    $ccmail = "";
	    $subject = "Your abuse report has been rejected.";
	    $urlOfCommunityPage = SHIKSHA_HOME."/shikshaHelp/ShikshaHelp/communityGuideline";
	    $contentArr['name'] = ($detail['firstname']=='')?$detail['displayname']:$detail['firstname'];
	    $contentArr['communityUrl'] = $urlOfCommunityPage;
	    $contentArr['entityType'] = getAbuseEntityName($entityType);
	    $contentArr['penalty'] = ($penalty=="1")?" and you have been penalized with 15 points for inappropriate use of your reporting rights":"";
	    $contentArr['type'] = 'republishUserAbuseMail';
	    $contentArr['sectionURL'] = $urlOfLandingPage;
	    $contentArr['section'] = $section;
	    $contentArr['entityURL'] = $detail['url'];
        $contentArr['receiverId'] = $detail['userid'];

	    Modules::run('systemMailer/SystemMailer/mailRepublishUserAbuse', $email, $contentArr);
	}
  }


 // Next six functions related to top recruitning company name & logo.
    // Function to show the home page for adding company and logo
    function add_companylogo($sortClass='All', $rstart=0, $rcount=20 ){
	ini_set('memory_limit', '512M');
        $this->init();
        $validity = $this->checkUserValidation();
        $cmsPageArr['validateuser'] = $validity;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms')
        { header("location:/enterprise/Enterprise/disallowedAccess");
        exit();}
        // Call to the DB to count the total valid tuples -1 distinguishes this count call from regular calls
        $calObj = new Enterprise_client();
        $countComLogo=$calObj->getCompanyLogo($sortClass,-1,$rcount,$countBit);
        $count=0;
        foreach ($countComLogo as $a => $b)
        $count= $count+1;
        // Call to DB to display the the result tuplewise
        $clObj = new Enterprise_client();
        $companyLogo=$clObj->getCompanyLogo($sortClass,$rstart,$rcount,$countBit);
        $paginationURL= site_url('enterprise/Enterprise/add_companylogo')."/".$sortClass."/@start@/@count@";
        //Loading the view for the add company home page
        $this->load->view('enterprise/add_companylogo',array('companyLogoListing'=>$companyLogo,'cmsUserInfo'=>$cmsUserInfo,'validateuser'=>$validity, 'sortClass'=>$sortClass, 'totalCount'=>$count, 'paginationURL'=> $paginationURL, 'rstart'=>$rstart,'rcount'=>$rcount ));
    }



    // This function facilitates the actual adding of comapny name logo and the url
    function set_companylogo($data){

        $this->init();
        $validity = $this->checkUserValidation();
        $cmsPageArr['validateuser'] = $validity;
        $name= $_POST['name'];

        $name = str_replace('"','',$name);
        $name = str_replace("'","",$name);
        $name=  ereg_replace("[^A-Za-z0-9\.\-\ ]", "", $name);
        $name = str_replace("xxxx","&",$name);
        trim($name);



        $logolink= $_POST['logo'];
        $appId = 1;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
       if($cmsUserInfo['usergroup']!='cms')
        {header("location:/enterprise/Enterprise/disallowedAccess");
            exit();}
        $alObj = new Enterprise_client();
        $companyLogo=$alObj->setCompanyLogo($name,$logolink);
        echo "done";
    }

    //Uploading comapny logo on the overlay through Ajax Iframe Method (AIM)
    function uploadFile( $vcard = 0,$appId = 1){

        $this->init();
        if($_FILES['myImage']['tmp_name'][0] == '')
        echo "Please select a photo to upload";
        else
        {       $this->load->library('Upload_client');
                $uploadClient = new Upload_client();
                $upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$userId,"user", 'myImage');
                if(!is_array($upload))
                echo $upload;
                else
                {       
                        $linkURL = addingDomainNameToUrl(array('url' => $upload[0]['imageurl'] , 'domainName' =>MEDIA_SERVER)); 
                        list($width, $height, $type, $attr) = getimagesize($linkURL);
                         if( $width != 120 || $height != 40)
                            echo 'Image size must be 120*40 px';
                         else
                            echo $linkURL;
                }
         }
    }


    // For modifying the already added company tuple
    function mod_companylogo($data){

        $this->init();
        $validity = $this->checkUserValidation();
        $cmsPageArr['validateuser'] = $validity;
        $name= $_POST['name'];
        $logolink= $_POST['logo'];
        $id=$_POST['id'];
        $appId = 1;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms')
        {   header("location:/enterprise/Enterprise/disallowedAccess");
            exit();}
        $modObj = new Enterprise_client();
        $companyLogo=$modObj->modCompanyLogo($name,$logolink,$id);
        $sortClass= 'All';
        $this->add_companylogo($sortClass);
}



 // This function facilitates the deletion of the company entry by changing the status field
 function del_companylogo($data)
 {
        $this->init();
        $validity = $this->checkUserValidation();
        $cmsPageArr['validateuser'] = $validity;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();}
        $delid=$_POST['id'];
        $delObj = new Enterprise_client();
        $companyLogo=$delObj->delCompanyLogo($delid);
        $sortClass= 'All';
        $this->add_companylogo($sortClass);
 }



  function check_deletelogo($data)
 {

        $this->init();
        $validity = $this->checkUserValidation();
        $cmsPageArr['validateuser'] = $validity;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();}
        $delid=$_POST['id'];
        $name=$_POST['name'];
        $delObj = new Enterprise_client();
        $companyLogo=$delObj->checkDeleteLogo($delid);
        if( $companyLogo ==0)
        {

            $t=array();
            $t[0]= $companyLogo;
            $t[1]= $delid;
            $t[2]= $name;
            $req= json_encode($t);
            echo $req;

        }
        else
        {

            $iname= array();
            $icount=0;
            foreach($companyLogo as $key=> $value)
            {
                        foreach($value as $k=> $v)
                        {
                                $iname[$icount]= $v;
                                $icount++;
                        }

            }

            $r= json_encode($iname);
            echo $r;
        }

 }
	function shikshaTVC()
 	{
 		$this->init();
 		$data['name'] = isset($_REQUEST['name'])?$_REQUEST['name']:'';
 		$data['email'] = isset($_REQUEST['email'])?$_REQUEST['email']:'';;
 		$data['mobile'] = isset($_REQUEST['mobile'])?$_REQUEST['mobile']:'';;
 		$data['city'] = isset($_REQUEST['city'])?$_REQUEST['city']:'';;
 		$data['page'] = isset($_REQUEST['page'])?$_REQUEST['page']:'';;
 		$this->load->view('shikshaHelp/shikshaTVC', $data);
 	}
// Next few functions are related to cms interface of adding Top Graduation Courses(Popular institutes)

 	function shiksha_TVCFormPost(){
 		$this->init();
 		$clientObj = new Enterprise_client();
 		$name = $this->input->post('uName');
 		$email = $this->input->post('uEmail');
 		$mobile = $this->input->post('uMobile');
 		$city = $this->input->post('uCity');
 		$company = $this->input->post('uCompany');
 		$page = $this->input->post('pageId');
 		$answer = $this->input->post('uAnswer'); 		
 		$result = $clientObj->addTVCUser($name, $email, $mobile, $city, $company, $page, $answer);		
 		header('Location:'.SHIKSHA_HOME);
	}
function getPopularInstitutes()
	{
		$this->init();
		$validity = $this->checkUserValidation();
		$popIns['validateuser'] =$this->checkUserValidation();
		//$categoryClient = new Category_list_client();
		$cmsUserInfo = $this->cmsUserValidation();
		if($popIns['validateuser'][0]['usergroup']!='cms')
		{ 
			header("location:/enterprise/Enterprise/disallowedAccess");
			exit();
		}
		// Call to the DB
		$calObj = new Enterprise_client();
		$popInstitutes=$calObj->getPopularInstitutes();
		$cmsUserInfo['prodId'] = 42;
		$popIns['cmsUserInfo']=$cmsUserInfo;
		$popIns['popularInsData']= $popInstitutes;
		$this->load->view('enterprise/graduation_courses_logos',$popIns);
	}

     
// This function facilitates the addition of popular institutes under top grad courses
     function setPopularInstitutes($data){
        $this->init();
        $position= $this->input->post('pos',true);
        $insId= $this->input->post('insId',true);
        $type = $this->input->post('type',true);
	    $courses = $this->input->post('courses',true);	
        // clearing the cache for popular institutes update on home page
		$key=md5('getInstituteForTabs');
		$key1=md5('getInstituteForTabs');
        $cacheLib = new cacheLib();
        $cacheLib->clearCache($key);
         $cacheLib->clearCache($key1);
        // end of cache cleareance
        $popParams= array($position, $insId,$type, $courses);
        $clienObj = new Enterprise_client();
        $setPopular=$clienObj->setPopularInstitutes($popParams);
        echo $setPopular;
    }

 //Uploading popular institute logo  through Ajax Iframe Method (AIM)
    function uploadPopLogo( $vcard = 0,$appId = 1){

        $this->init();
        if($_FILES['myImage']['tmp_name'][0] == '')
        echo "Please select a photo to upload";
        else
        {       $this->load->library('Upload_client');
                $uploadClient = new Upload_client();
                $upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$userId,"user", 'myImage');
                if(!is_array($upload))
                echo $upload;
                else
                {        list($width, $height, $type, $attr) = getimagesize($upload[0]['imageurl']);
                         if( $width != 84 || $height != 28)
                         echo 'Image size must be 84*28 px';
                         else
                         echo $upload[0]['imageurl'];}
         }
    }
	
	function getPopCourseList($pcat){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}
		$categoryClient = new Category_list_client();
        $categoryList = $categoryClient->getCategoryTree($appId, 1, 'national');
		$subcategories = array();
		$subcategoresID = array();
		foreach($categoryList as $category){
			if($category['parentId'] == $pcat){
				$subcategories[] = $category;
				$subcategoresID[] = $category['categoryID'];
			}
		}
		
		$subcategoresID = implode(",",$subcategoresID);
		$popCourses = $categoryClient->getPopularCourses($subcategoresID);
		$subCategoryCourses = $categoryClient->getSubCategoryCourses($subcategoresID);
		$dataDump['popCourses'] = $popCourses;
		$dataDump['subCategoryCourses'] = $subCategoryCourses;
		$dataDump['subcategories'] = $subcategories;
		$this->load->view('enterprise/popCourseList',$dataDump);
	}
	
	function setPopCourseList(){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}
		$categoryClient = new Category_list_client();
		$popCourses = $categoryClient->setPopularCourses($_REQUEST['selectedCourse'],$_REQUEST['unSelectedCourse']);
	}
	
	function getCategoryPageHeaderText(){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}
		if($_REQUEST['ldbcourse']){
			$page_type = 'ldbcourse';
			$type_id = $_REQUEST['ldbcourse'];
		}elseif($_REQUEST['subcategory']){
			$page_type = 'subcategory';
			$type_id = $_REQUEST['subcategory'];
		}elseif($_REQUEST['category']){
			$page_type = 'category';
			$type_id = $_REQUEST['category'];
		}
		
		if($_REQUEST['city']){
			$location_type = 'city';
			$location_id = $_REQUEST['city'];
		}elseif($_REQUEST['state']){
			$location_type = 'state';
			$location_id = $_REQUEST['state'];
		}elseif($_REQUEST['locality']){
			$location_type = 'locality';
			$location_id = $_REQUEST['locality'];
		}elseif($_REQUEST['zone']){
			$location_type = 'zone';
			$location_id = $_REQUEST['zone'];
		}
		
		
		$categoryClient = new Category_list_client();
        
		$categoryPageHeaderText = $categoryClient->getCategoryPageHeaderText($page_type,$type_id,$location_type,$location_id);
		echo $categoryPageHeaderText;
	}
	
	
	function setCategoryPageHeaderText(){
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
		}
		if($_REQUEST['ldbcourse']){
			$page_type = 'ldbcourse';
			$type_id = $_REQUEST['ldbcourse'];
		}elseif($_REQUEST['subcategory']){
			$page_type = 'subcategory';
			$type_id = $_REQUEST['subcategory'];
		}elseif($_REQUEST['category']){
			$page_type = 'category';
			$type_id = $_REQUEST['category'];
		}
		
		if($_REQUEST['city']){
			$location_type = 'city';
			$location_id = $_REQUEST['city'];
		}elseif($_REQUEST['state']){
			$location_type = 'state';
			$location_id = $_REQUEST['state'];
		}elseif($_REQUEST['locality']){
			$location_type = 'locality';
			$location_id = $_REQUEST['locality'];
		}elseif($_REQUEST['zone']){
			$location_type = 'zone';
			$location_id = $_REQUEST['zone'];
		}
		
		$text = $_REQUEST['text'];
		$categoryClient = new Category_list_client();
        
		$categoryPageHeaderText = $categoryClient->setCategoryPageHeaderText($page_type,$type_id,$location_type,$location_id,$text);
		echo $categoryPageHeaderText;
	}


    function removeCategorypageWidgetsData(){
		$this->init();
                $appId = 1;

		$cmsUserInfo = $this->cmsUserValidation();
                if($cmsUserInfo['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
		}

                if($_REQUEST['regions']){
			$regionId = $_REQUEST['regions'];
		}

                if($_REQUEST['country1']){
			$countryId = $_REQUEST['country1'];
		}

		if($_REQUEST['subcategory']){
			$categoryID = $_REQUEST['subcategory'];
		}elseif($_REQUEST['maincategory']){
			$categoryID = $_REQUEST['maincategory'];
		}

                if($_REQUEST['widgetType']){
			$widgetType = $_REQUEST['widgetType'];
		}

		$categoryClient = new Category_list_client();
                // error_log("\n\nIn enterprise.php file, widget type : ".print_r($widgetType,true),3,'/home/infoedge/Desktop/log.txt');
		$categoryPageWidgetsDataResponse = $categoryClient->removeCategorypageWidgetsDataInfo($regionId, $countryId, $categoryID, $widgetType);
		echo $categoryPageWidgetsDataResponse;
	}

        function uploadWidgetImage () {            
            $target_location = "/var/www/html/shiksha/mediadata/images/categoryPageWidgetsImages";
            if( !(file_exists($target_location) && is_dir($target_location)) ) {
                @mkdir($target_location, 0777);
            }

            $target_location = $target_location."/".$_REQUEST['categoryID']."_".$_REQUEST['name'];
            // error_log("Request: ".print_r($_REQUEST,true)."\n\n============\n: Rcvd FILE :".print_r($_FILES,true)."\n============\n: ",3,'/home/infoedge/Desktop/log.txt');
            
            if(!(move_uploaded_file($_FILES['file']['tmp_name'],$target_location))) {
               //  error_log("\n\n NOT SUCCESSFUL.",3,'/home/infoedge/Desktop/log.txt');
            } else {
                 // error_log("\n\n FULL SUCCESSFUL.",3,'/home/infoedge/Desktop/log.txt');
            }
        }

	function setCategorypageWidgetsData(){
                // error_log("\n\nIn enterprise.php file: widgetImageName = ".print_r($_REQUEST, true),3,'/home/infoedge/Desktop/log.txt');
		$this->init();
                $appId = 1;
                $widgetImageName = "";
                
		$cmsUserInfo = $this->cmsUserValidation();
                if($cmsUserInfo['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
		}

                if($_REQUEST['regions']){
			$regionId = $_REQUEST['regions'];
		}

                if($_REQUEST['country1']){
			$countryId = $_REQUEST['country1'];
		}

		if($_REQUEST['subcategory']){
			$categoryID = $_REQUEST['subcategory'];
		}elseif($_REQUEST['maincategory']){
			$categoryID = $_REQUEST['maincategory'];
		}

		if($_REQUEST['dataIDs']){
			$dataIDs = $_REQUEST['dataIDs'];
		}

                if($_REQUEST['widgetType']){
			$widgetType = $_REQUEST['widgetType'];
		}
                 
                // If picture is selected to upload..
                if($_REQUEST['isPictureSelected'] && $_REQUEST['isPictureSelected'] == 1){
			/*
                        $target_location = "/var/www/html/shiksha/mediadata/images/categoryPageWidgetsImages";
                        if( !(file_exists($target_location) && is_dir($target_location)) ) {
                            @mkdir($target_location, 0777);
                        }
			*/

                        $this->load->library('Upload_client');
			$uploadClient = new Upload_client();
                        $upload = $uploadClient->uploadFile($appId, 'image', $_FILES,array(), "-1", "cat_page_articles_widget", 'latestNewsPicture');
                        
                        if(isset($upload[0]['imageurl']) && $upload[0]['imageurl'] != "") {
                            $startPos = strrpos($upload[0]['imageurl'], '/');
                            $widgetImageName = substr($upload[0]['imageurl'],  ($startPos + 1) );
                        } else {
                            $widgetImageName = "";
                        }

                        // error_log("\n\nIn enterprise.php file: ".print_r($upload,true)." \n\n widgetImageName = ".$widgetImageName,3,'/home/infoedge/Desktop/log.txt');

                        /*                        
                        $uploadScriptUrl = "http://".MEDIA_SERVER_IP."/enterprise/Enterprise/uploadWidgetImage";

                        $post_array['file'] = "@".$_FILES['latestNewsPicture']['tmp_name'];
			$post_array['type'] = $_FILES['latestNewsPicture']['type'];
			$post_array['name'] = $_FILES['latestNewsPicture']['name'];
                        $post_array['categoryID'] = $categoryID;

                        // The curl call to media server..
                        $c = curl_init();
                        curl_setopt($c, CURLOPT_URL, $uploadScriptUrl);
                        curl_setopt($c, CURLOPT_RETURNTRANSFER, true);
                        curl_setopt($c, CURLOPT_POST, 1);
                        curl_setopt($c,CURLOPT_POSTFIELDS, $post_array);
                        $output =  curl_exec($c);
                        curl_close($c);

                        $widgetImageName = $categoryID."_".$_FILES['latestNewsPicture']['name'];
                         
                         */
                            // return $output;
                        //   error_log("\n\n+++++++++++++++++++++++++\n\nuploadScriptUrl : ".$uploadScriptUrl."\n================\n: post_array :".print_r($post_array,true)."\n=============\n: ",3,'/home/infoedge/Desktop/log1.txt');
                }

		$categoryClient = new Category_list_client();
                // error_log("\n\nIn enterprise.php file: ".print_r($dataIDs,true),3,'/home/infoedge/Desktop/log.txt');
		$categoryPageWidgetsDataResponse = $categoryClient->setCategorypageWidgetsDataInfo($regionId, $countryId, $categoryID, $dataIDs, $widgetType, $widgetImageName);
		echo $categoryPageWidgetsDataResponse;
	}


        function getCategorypageWidgetsData() {
                 // error_log("\n\nIn enterprise.php file: ".print_r($_REQUEST,true),3,'/home/infoedge/Desktop/log.txt');
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
                if($cmsUserInfo['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
		}

                if($_REQUEST['regions']){
			$regionId = $_REQUEST['regions'];
		}

                if($_REQUEST['country1']){
			$countryId = $_REQUEST['country1'];
		}

		if($_REQUEST['subcategory']){
			$categoryID = $_REQUEST['subcategory'];
		}elseif($_REQUEST['maincategory']){
			$categoryID = $_REQUEST['maincategory'];
		}

                if($_REQUEST['widgetType']){
			$widgetType = $_REQUEST['widgetType'];
		}
                // error_log("\n\nIn enterprise.php file: ".print_r($_REQUEST,true),3,'/home/infoedge/Desktop/log.txt');
		$categoryClient = new Category_list_client();

		$categoryPageWidgetsDataResponse = $categoryClient->getCategorypageWidgetsDataInfo($regionId, $countryId, $categoryID, $widgetType);
		echo $categoryPageWidgetsDataResponse;
            
        }
		
		function getSAWidgetArticles(){
			$this->init();
			$cmsUserInfo = $this->cmsUserValidation();
            if($cmsUserInfo['usergroup']!='cms'){
                header("location:/enterprise/Enterprise/disallowedAccess");
		        exit();
			}
			$categoryClient = new Category_list_client();
			//echo "hey";
			echo json_encode($categoryClient->getSAWidgetArticles($_REQUEST['location_id'], $_REQUEST['location_type'], $_REQUEST['category'], $_REQUEST['widget']));
		}
		
		function saveSAWidgetContent(){
			$this->init();
			$cmsUserInfo = $this->cmsUserValidation();
            if($cmsUserInfo['usergroup']!='cms'){
                header("location:/enterprise/Enterprise/disallowedAccess");
		        exit();
			}
			$categoryClient = new Category_list_client();
			//echo "hey";
			// clearing the cache for popular institutes update on home page
			
			$cacheLib = new cacheLib();
			$key=md5('getStudyAbroadSnippetWidget'. $_REQUEST['category'] . $_REQUEST['location_id'] . $_REQUEST['location_type']);
			$cacheLib->clearCache($key);
			$key=md5('getStudyAbroadStepsWidget'. $_REQUEST['category'] . $_REQUEST['location_id'] . $_REQUEST['location_type']);
			$cacheLib->clearCache($key);
			echo json_encode($categoryClient->saveSAWidgetContent($_REQUEST['location_id'], $_REQUEST['location_type'], $_REQUEST['category'], $_REQUEST['widget'],$_REQUEST['value'],$_REQUEST['position'],$_REQUEST['type'],$_REQUEST['image']));
		}
		function uploadSAWidgetContent(){
	
			$this->init();
			if($_FILES['myImage']['tmp_name'][0] == '')
			echo "Please select a photo to upload";
			else
			{       $this->load->library('Upload_client');
					$uploadClient = new Upload_client();
					$upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$userId,"user", 'myImage');
					if(!is_array($upload))
					echo $upload;
					else
					{echo $upload[0]['imageurl'];}
			 }
		}

/*
 * Search for Institutes based on the passed keyword
 * @param  searchedKeyword : accept as a $_REQUEST variable
 * @throws exits if no keyword found
 * @return Array of Institute with the Title and Ids
 */
        function searchInstitutesForKeyword()
        {            
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
                if($cmsUserInfo['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
		}

                if($_REQUEST['searchedKeyword'] && $_REQUEST['searchedKeyword'] != ""){
			$result_array_to_pass['searchedKeyword'] = $_REQUEST['searchedKeyword'];
		} else {
                        exit();
                }
                
                $this->load->builder('SearchBuilder', 'search');  // Load search builder
                $searchCommonLib = SearchBuilder::getSearchCommon(); // Get searchCommonLib instance
                $result_array_to_pass['result_array'] = $searchCommonLib->getSearchListingIdsByType($result_array_to_pass['searchedKeyword'], 'institute'); // Makes call to get institute title/ids.                                     
                $location_contact_container_html = $this->load->view('enterprise/searchedInstituteListings', $result_array_to_pass, true);
                echo $location_contact_container_html;
                exit();
        }

/*
 * Update Contact Details of Institute and of its LIVE courses in a go
 * @param  contact_name_location , contact_phone_location, contact_mobile_location, contact_email_location : accept as a $_REQUEST variable
 * @param locationIds_for_institute, locationIds_for_courses : accept as a $_REQUEST variable, these are the location ids to be updated.
 * @return HTML of extended Locations and their respective Contact Details (updated ones) for Institute and its all live Courses and a PUBLISH HTML pane.
 */        
        function updateListingsContactDetails() {
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
                if($cmsUserInfo['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
		}
                
                $dataArray = array();
                if($_REQUEST['contact_name_location']){
			$dataArray['contact_name_location'] = $_REQUEST['contact_name_location'];
		}                
                if($_REQUEST['contact_phone_location']){
			$dataArray['contact_phone_location'] = $_REQUEST['contact_phone_location'];
		}
                if($_REQUEST['contact_mobile_location']){
			$dataArray['contact_mobile_location'] = $_REQUEST['contact_mobile_location'];
		}
                if($_REQUEST['contact_email_location']){
			$dataArray['contact_email_location'] = $_REQUEST['contact_email_location'];
		}
                if($_REQUEST['locationIds_for_institute']){
			$dataArray['locationIds_for_institute'] = $_REQUEST['locationIds_for_institute'];
		}
                if($_REQUEST['locationIds_for_courses']){
			$dataArray['locationIds_for_courses'] = $_REQUEST['locationIds_for_courses'];
		}
                if($_REQUEST['institute_id']){
			$dataArray['institute_id'] = $_REQUEST['institute_id'];
		}

                $client_obj = new Listing_client();
                // Updating the Contact Details now..
                $client_obj->updateBulkListingsContactDetails($dataArray);
                
                // Collect the data for preview pane now..
                $previewPaneHtml = $this->generatePublishListingsPreviewPane($dataArray['institute_id']);
                echo $previewPaneHtml;
                exit();
        }

        function refreshListingsPreviewPane($institute_id) {
                $previewPaneHtml = $this->generatePublishListingsPreviewPane($institute_id);
                echo $previewPaneHtml;
                exit();
        }

/**
 * Generate HTML pane for a Institute and all its Courses those need to be Publish
 * @param  institute_id
 * @throws exits if no institute_id found
 * @return HTML pane for a Institute and all its Courses those need to be Publish with individual "listing's publish" and "publish all" button
 */
        function generatePublishListingsPreviewPane($institute_id) {
                if($institute_id == ""){
                    exit();
                }
                
                $this->init();
                $j = 0;
                $this->load->model('listingmodel');
                $model_object = new ListingModel();
                
                $insInfoArray = $model_object->getListingMaxVersionInfo($institute_id, "institute");
                if($insInfoArray[0]['status'] == 'draft') {
                    $listings[$j]['type'] = 'institute';
                    $listings[$j]['typeId'] = $institute_id;
                    $listings[$j++]['title'] = $insInfoArray[0]['listing_title'];
                }

                $client_obj = new Listing_client();
                $courses = $client_obj->getCourseList(1, $institute_id);
                $coursesCount = count($courses);
                for($i = 0; $i < $coursesCount; $i++) {
                        if(strpos($courses[$i]['status'], 'draft') !== false) {
                            $listings[$j]['type'] = 'course';
                            $listings[$j]['typeId'] = $courses[$i]['courseID'];
                            $listings[$j++]['title'] = $courses[$i]['courseName'];
                        }
                }

                $result_array_to_pass['listings'] = $listings;
                $previewPaneHtml = $this->load->view('enterprise/publishListingsPreviewPane', $result_array_to_pass, true);
                return $previewPaneHtml;
        }       

/*
 * Get Locations and location specific Contact Details for Institutes and all its live Courses with Courses' Global Contact Details as well.
 * @param  instituteId : accept as a $_REQUEST variable
 * @throws exits if no instituteId found
 * @return HTML of extended Locations and their respective Contact Details for Institute and its all live Courses
 */
        function getInstituteLocationContactDetails()
        {
		$this->init();
		$cmsUserInfo = $this->cmsUserValidation();
                if($cmsUserInfo['usergroup']!='cms'){
                    header("location:/enterprise/Enterprise/disallowedAccess");
                    exit();
		}

                if($_REQUEST['instituteId'] && $_REQUEST['instituteId'] != ""){
			$instituteId = $_REQUEST['instituteId'];
		} else {
                    exit();
                }

                $this->load->model('listingmodel');
                $model_object = new ListingModel();
                $result_array_to_pass['result_array'] = $model_object->getInstituteLocationContactDetails($instituteId);

                $location_contact_container_html = $this->load->view('enterprise/listingsContactDetails', $result_array_to_pass, true);
                
                echo $location_contact_container_html;
                exit();
        }
        
	function deleteMultipleCourses($instituteId)
	{  $startTime = microtime(true);
        $this->init();
        $validity = $this->checkUserValidation();
        $data['validateuser'] = $validity;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
		$data['cmsUserInfo']=$cmsUserInfo;
        if($cmsUserInfo['usergroup']!='cms')
        { header("location:/enterprise/Enterprise/disallowedAccess");
        exit();}
        // Call to the DB
        $calObj = new Listing_client();

        $data['courses'] = $calObj->getCourseList(1,$instituteId);
		$data['institute'] = $calObj->get_institute_name($instituteId);
		$data['instituteId'] = $instituteId;
        $this->load->view('enterprise/deleteMultipleCourses',$data);
        if(function_exists ('logPerformanceData')) { logPerformanceData($startTime);}
	}
	
	function getCatgoryPageFatFooter($entityType,$entityId,$locationFlag)
	{
        $this->init();
        $validity = $this->checkUserValidation();
        $data['validateuser'] = $validity;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
		$data['cmsUserInfo']=$cmsUserInfo;
        if($cmsUserInfo['usergroup']!='cms')
        { header("location:/enterprise/Enterprise/disallowedAccess");
        exit();}
		$this->load->model('categoryList/categorylistmodel');
        $data['fatFooter'] = $this->categorylistmodel->getCatgoryPageFatFooter($entityType,$entityId,$locationFlag);
        $this->load->view('enterprise/fatFooterContent',$data);
	}
	
	function setCatgoryPageFatFooter($entityType,$entityId,$locationFlag)
	{
        $this->init();
		$fatFooter = $_REQUEST['fatFooter'];
        $validity = $this->checkUserValidation();
        $data['validateuser'] = $validity;
        $categoryClient = new Category_list_client();
        $cmsUserInfo = $this->cmsUserValidation();
		$data['cmsUserInfo']=$cmsUserInfo;
        if($cmsUserInfo['usergroup']!='cms')
        { header("location:/enterprise/Enterprise/disallowedAccess");
        exit();}
        $this->load->model('categoryList/categorylistmodel');
		$this->categorylistmodel->setCatgoryPageFatFooter($entityType,$entityId,$locationFlag,$fatFooter);
	}

       function addLocation($extraParam){
		
			if(!$extraParam || ($extraParam == 'country' || $extraParam == 'city')) {
				$extraParam = 'locality';
			}
		
			$validateuser = $this->checkUserValidation();
			$cmsUserInfo = $this->cmsUserValidation();
			$headerComponents = array(
                'css' => array('headerCms', 'footer','mainStyle'),
                'js' => array('user','add_country','common'),
                'title' => "Add Location",
                'product' => '',
                'displayname' => (isset($validateuser[0]['displayname']) ? $validateuser[0]['displayname'] : ""),
            );
            $data = array();
            $data["tab"] = $extraParam;
            echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
            echo $this->load->view('enterprise/cmsTabs', $cmsUserInfo, true);
			
			echo Modules::run('location/Location/add',$extraParam);
        }
	
	function responseMigration($action)
	{	
		$validateuser = $this->checkUserValidation();
		$cmsUserInfo = $this->cmsUserValidation();
		$usergroup = $cmsUserInfo['usergroup'];
        
		$headerComponents = array(
			'css' => array('headerCms', 'footer','mainStyle'),
			'js' => array('user','common'),
			'title' => "Response Migration",
			'product' => '',
			'displayname' => (isset($validateuser[0]['displayname']) ? $validateuser[0]['displayname'] : ""),
		);
		$data = array();
		$data["tab"] = 790;
		
		if($usergroup != 'cms') {
		    header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
		}
		
		echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
		echo $this->load->view('enterprise/cmsTabs', $cmsUserInfo, true);
		
		if(!$action) {
			$action = 'index';
		}
		
		echo Modules::run('lms/ResponseMigration/'.$action);
	}
	
	function onlineFormTracking($action, $task='pixel')
	{	
		$validateuser = $this->checkUserValidation();
		$cmsUserInfo = $this->cmsUserValidation();
		$usergroup = $cmsUserInfo['usergroup'];
        
		$headerComponents = array(
			'css' => array('headerCms', 'footer','mainStyle', 'pbtAutomation'),
			'js' => array('user','common', 'pbtAutomation'),
			'title' => "Online Forms Conversion Tracking - Admin",
			'product' => '817',
			'prodId' => '817',
			'displayname' => (isset($validateuser[0]['displayname']) ? $validateuser[0]['displayname'] : ""),
		);
		$data = array();
		$data["tab"] = 817;
	
		if($usergroup != 'cms') {
		    header("location:/enterprise/Enterprise/disallowedAccess");
		    exit();
		}
		
		echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
		echo $this->load->view('enterprise/cmsTabs', $cmsUserInfo, true);
		
		if(!$action) {
			$action = 'index';
		}
        echo $this->load->view('enterprise/OnlineForm/onlineFormTrackingTab', array('task'=>$task), true);
        if($task == 'pixel'){
            echo Modules::run('Online/OnlineFormConversionTracking/'.$action);
        }else if($task = 'extForm'){
            echo Modules::run('onlineFormEnterprise/PBTFormsAutomation/enablePBTForm');
        }
	}	

  function getExperts(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$moduleName = trim($this->input->post('moduleName'));
	$filter = trim($this->input->post('Filter'));
	$start=$this->input->post('startFrom');
	$rows=$this->input->post('countOffset');
	$userNameFieldData=$this->input->post('userNameFieldData');
	$userLevelFieldData=$this->input->post('userLevelFieldData');

	$parameterObj = array('abuse' => array('offset'=>-1,'totalCount'=>0,'countOffset'=>5));
	$entObj = new Enterprise_client();
	$resultUserAbuse = $entObj->getExperts($appId,$loggedInUserId,$start,$rows,$moduleName,$filter,$userNameFieldData,$userLevelFieldData);
	$totalAbuseReport = isset($resultUserAbuse[0]['totalAbuseReport'])?$resultUserAbuse[0]['totalAbuseReport']:0;
	$parameterObj['abuse']['offset'] = 0;
	$parameterObj['abuse']['totalCount'] = $totalAbuseReport;
	$tempArray=array();
	$abuseDetailsArray=array();
	if(isset($resultUserAbuse[0]['results'])){
		$tempArray = &$resultUserAbuse[0]['results'];
	}

	for($i=0;$i<count($tempArray);$i++)
	{
		if(is_array($tempArray[$i]['abuse'])){
			//Set the URL
	    	  $tempArray[$i]['abuse']['url'] = SHIKSHA_ASK_HOME."/getUserProfile/".urlencode($tempArray[$i]['abuse']['displayname']);
		}
	}
	$Validate = $this->userStatus;
	$cmsPageArr['validateuser'] = $Validate;
	$cmsPageArr['parameterObj'] = json_encode($parameterObj);
	$cmsPageArr['userAbuse'] = isset($resultUserAbuse[0]['results'])?$resultUserAbuse[0]['results']:array();
	$cmsPageArr['totalAbuse'] = $totalAbuseReport;
	$cmsPageArr['filterSel'] = $filter;
	$cmsPageArr['moduleName'] = $moduleName;
	$cmsPageArr['startFrom'] = $start;
	$cmsPageArr['countOffset'] = $rows;
	$cmsPageArr['userNameFieldData'] = $userNameFieldData;
	$cmsPageArr['userLevelFieldData'] = $userLevelFieldData;
    // $this->load->model('messageBoard/AnAModel');
    // $expertLevelsForFilter = $this->AnAModel->getExpertLevels('AnA');
    $this->load->helper('messageBoard/abuse');
    $expertLevelsForFilter = getExpertLevels();
    $cmsPageArr['expertLevelsForFilter'] = $expertLevelsForFilter;
    echo $totalAbuseReport."::".$this->load->view('enterprise/expertListing',$cmsPageArr);
  }

  function actionExpert(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userId = trim($this->input->post('userId'));
	$action = trim($this->input->post('action'));

	//We need to do the following activities for the Acting on Experts.
	//1. In case an Expert is approved, make him an Advisor, change his status from draft->Live and then send him a mailer.
	//2. In case of rejecting an Expert, change his status from Draft->Deleted and then send him a mailer.
	//3. In case of removing an expert, change his status from Live->Deleted,make him a beginner.
	$entObj = new Enterprise_client();
	$result = $entObj->actionExpert($appId,$userId,$action);

	//Send mail to the owner about the action (in case of Accept/Reject)
	if($action!='remove' && $result!='duplicate'){
		//$this->sendExpertActionMailer($userId,$action);
	}
	echo "1";
  }

  function sendExpertActionMailer($userId,$action){
	$this->init();
	//Send mail to owner informing that his/her content has been republished
	$msgbrdClient = new Message_board_client();
    $userDetails = $msgbrdClient->getUserDetails(1,$userId);
    $details = $userDetails[0];
    $email = $details['email'];
    $contentArr['name'] = ($details['firstname']=='')?$details['displayname']:$details['firstname'];
	$fromMail = "noreply@shiksha.com";

	if($action=='accept'){
        $subject = "Congratulations! You are now a Shiksha Expert";       
		$contentArr['type'] = 'approveExpertMail';
	}
	else if($action=='reject'){
        $subject = "You can be a Shiksha Expert soon";        
		$contentArr['type'] = 'rejectExpertMail';
	}
	
	$this->load->library('MailerClient');
	$MailerClient = new MailerClient();
	$urlOfLandingPage = SHIKSHA_ASK_HOME.'/messageBoard/MsgBoard/expertOnboard';
	$contentArr['sectionURL'] = $MailerClient->generateAutoLoginLink(1,$email,$urlOfLandingPage);
	//End code for section name and url
    $content = $this->load->view("search/searchMail",$contentArr,true);
	$this->load->library('alerts_client');
    $mail_client = new Alerts_client();
    $response= $mail_client->externalQueueAdd("12",$fromMail,$email,$subject,$content,$contentType="html");
  }

  function removeExpertProfilePic(){
	$this->init();
	$appId = 12;
	$loggedInUserId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:0;
	$userId = trim($this->input->post('userId'));
	$entObj = new Enterprise_client();
	$result = $entObj->removeExpertProfilePic($appId,$userId);

        //After removing the image in the Expert table, we will also have to change the User's image in the user table
        $this->load->library('Register_client');
        $registerClient = new Register_client();
        $registerClient->updateUserAttribute($appId, $userId, 'avtarimageurl', '','');
	echo "1";
  }
  
	function validateListings() {
	$user_details = $this->checkUserValidation();
	if((!$user_details) || (is_array($user_details) && $user_details[0]['usergroup'] !='cms')) {
	echo "failure";
		return;
	}
	$listing_type = trim($this->input->post('listing_type',true));
	$deleted_listing_redirect_id = trim($this->input->post('deleted_listing_redirect_id',true));
	$deleted_listing_qna_id = trim($this->input->post('deleted_listing_qna_id',true));
	$deleted_listing_alumni_id = trim($this->input->post('deleted_listing_alumni_id',true));

	if(empty($deleted_listing_redirect_id)) {
		$deleted_listing_redirect_id = -1;
	}
	if(empty($deleted_listing_qna_id)) {
		$deleted_listing_qna_id = -1;
	}
	if(empty($deleted_listing_alumni_id)) {
		$deleted_listing_alumni_id = -1;
	}
	$listings_ids = array($deleted_listing_redirect_id,$deleted_listing_qna_id,$deleted_listing_alumni_id);
	$this->load->model('listingmodel');
	$model_object = new ListingModel();

	$result_array = $model_object->checkStatusOfListings($listings_ids,$listing_type);
	
    /** check if abroad institute is not in flow ***/
    if($listing_type == 'institute' && count($result_array) > 0) {
    	foreach($result_array as $instId => $resultid) {
    		$this->load->builder('ListingBuilder','listing');
    		$listingBuilder 			= new ListingBuilder;
    		$instRepo = $listingBuilder->getInstituteRepository();
    		$insObj = $instRepo->find($instId);
    		if(!($insObj instanceof Institute)) {
    			unset($result_array[$instId]);
    		}
    	}
    	
    }
	
	
	if(count($result_array) > 0) {
		echo json_encode($result_array);
	} else {
		echo "";
	}
	}
  function insertDeletedMappingAndupdateIndex() {
        $user_details = $this->checkUserValidation();
        if((!$user_details) || (is_array($user_details) && $user_details[0]['usergroup'] !='cms')) {
		echo "failure";
                return;
        }
  	$listing_type = trim($this->input->post('listing_type',true));
    	$deleted_listing_id = trim($this->input->post('deleted_listing_id',true));
  	$deleted_listing_redirect_id = trim($this->input->post('redirect_id',true));
  	$deleted_listing_qna_id = trim($this->input->post('qna_redirect_id',true));
  	$deleted_listing_alumni_id = trim($this->input->post('alumni_redirect_id',true));

  	if(empty($deleted_listing_redirect_id)) {
  		$deleted_listing_redirect_id = 0;
  	}
  	if(empty($deleted_listing_qna_id)) {
  		$deleted_listing_qna_id = 0;
  	}
  	if(empty($deleted_listing_alumni_id)) {
  		$deleted_listing_alumni_id = 0;
  	}
	$updated_institute_name = "";
	
	if($deleted_listing_alumni_id > 0 ){

		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $instituteRepository->find($deleted_listing_alumni_id);
		$updated_institute_name = $institute->getName();

	}
	
  	$this->load->model('listingmodel');
  	$model_object = new ListingModel();

  	$result_string = $model_object->insertDeletedMapping($listing_type,$deleted_listing_id,$deleted_listing_redirect_id,
  	$deleted_listing_qna_id,$deleted_listing_alumni_id,$updated_institute_name);

  	if($result_string == "success" && ($deleted_listing_alumni_id > 0))
  	{
		modules::run('listing/listing_server/buildListingCache', 'institute',$deleted_listing_alumni_id); 
	}
  //$this->buildListingCache('institute',$instituteId);

  	echo trim($result_string);
  }
  
    function saveEmailsForListing() {
        $user_details = $this->checkUserValidation();
        if((!$user_details) || (is_array($user_details) && $user_details[0]['usergroup'] !='enterprise')) {
            echo false;
            return;
        }
        $listingId = trim($this->input->post('listingId',true));
	    $listingType = trim($this->input->post('listingType',true));
        $listingLocationId = trim($this->input->post('listingLocationId',true));
        $email = trim($this->input->post('email',true));
        if(empty($listingId) || empty($listingLocationId) || empty($listingType)) {
            echo false;
            return;
        }
        $this->load->model('ldbmodel');
        $model_object = new LdbModel();
        $result_string = $model_object->saveEmailsForListing($listingId,$listingLocationId,$email,$listingType);

        $searchTrackingCriteria = array();
        $searchTrackingCriteria['institute_id'] = $listingId;
        $searchTrackingCriteria['location_id']  = $listingLocationId;
        
        $trackingParams                     = array();
        $trackingParams['product']          = 'CourseResponse';
        $trackingParams['page_tab']         = 'AllResponsePage';
        $trackingParams['cta']              = 'Email';
        $trackingParams['entity_id']        = $listingId;
        $trackingParams['search_criteria']  = json_encode($searchTrackingCriteria);
        $enterpriseTrackingLib = $this->load->library('enterprise/enterpriseDataTrackingLib');
        $enterpriseTrackingLib->trackEnterpriseData($trackingParams);

        echo trim($result_string);
    }
    

    function getDownloadResponses() {

	    ini_set("memory_limit", '2048M');
        $this->init();

        $listing_list = $this->input->post('values',true);
        $startDate    = $this->input->post('fromdate',true);
        $endDate      = $this->input->post('todate',true);
        $tabStatus    = $this->input->post('status',true);


        if($startDate == 'none') {
            $startDate = '';
        }
        if($endDate == 'none') {
            $endDate = '';
        }
        
        $validity    = $this->checkUserValidation();
        $cmsUserInfo = $this->cmsUserValidation();
        if($cmsUserInfo['usergroup']!='enterprise'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
        $clientId     = $validity[0]['userid'];
        
        $listing_list = explode(",", $listing_list);
        $listinginfo  = explode("|", array_shift($listing_list));
        $listingId    = (int) $listinginfo[0];
        $locationId   = (int) $listinginfo[1];
        $listingType  = (string) $listinginfo[2];
        
        $searchTrackingCriteria = array();      
        $resultCount            = 0;      
        $responseCSV = $this->getResponsesCSVForListing($listingId, $listingType, 'both', $locationId, $clientId, 'none', 0, 5000, True, False, $startDate, $endDate, $tabStatus);  
        $csv[]                                               = $responseCSV['csv'];
        $searchTrackingCriteria['listing_data'][$locationId] = $responseCSV['searchCriteria'][$locationId];
        $resultCount                                         = $responseCSV['records_fetched'];
        
        foreach ($listing_list as $listing)
        {
            $listinginfo                           = explode("|", $listing);
            $listingId                             = (int) $listinginfo[0];
            $locationId                            = (int) $listinginfo[1];
            $listingType                           = (string) $listinginfo[2];
            
            $responseCSV                           = $this->getResponsesCSVForListing($listingId, $listingType, 'both', $locationId, $clientId, 'none', 0, 100000, False, False, $startDate, $endDate, $tabStatus);
            $csv[]                                               = $responseCSV['csv'];
            $searchTrackingCriteria['listing_data'][$locationId] = $responseCSV['searchCriteria'][$locationId];
            $resultCount                                         = $resultCount + $responseCSV['records_fetched'];
        }


        if($startDate)
            $searchTrackingCriteria['filter']['date']['start_date'] = $startDate;

        if($endDate)
            $searchTrackingCriteria['filter']['date']['end_date']  = $endDate;

        $trackingParams                     = array();
        $trackingParams['product']          = 'CourseResponse';
        $trackingParams['page_tab']         = 'AllResponsePage';
        $trackingParams['cta']              = 'Download';
        $trackingParams['search_criteria']  = json_encode($searchTrackingCriteria);
        $trackingParams['records_fetched']  = $resultCount;
        $enterpriseTrackingLib = $this->load->library('enterprise/enterpriseDataTrackingLib');
        $enterpriseTrackingLib->trackEnterpriseData($trackingParams);

        $data = join('', $csv);
        header("Content-type: text/x-csv");
        header("Content-Disposition: attachment; filename=Responses.csv");
        echo $data;
    }

    function convertSuggestionArray($suggestionArray){
	    $suggestionFinalArray = array();
	    foreach ($suggestionArray as $suggestion){
		    $answerId = $suggestion['answerId'];
		    $instituteDetails = $this->getInstituteDetails(intval($suggestion['suggestionId']));
		    $suggestionFinalArray[$answerId][] = array($suggestion['suggestionId'],$instituteDetails[0],$instituteDetails[1]);
	    }
	    return $suggestionFinalArray;
    }

    function getInstituteDetails($instituteId){
	if($instituteId>0){
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$institute = $instituteRepository->find($instituteId);
		return array($institute->getName(),$institute->getURL());
	}
    }
/*Returns cumulative sum of insitute's and course's contact number and no. of listing views by an user. */
function getContactAndViewCountDetails($userid, $usergroup,&$cmsPageArr){

	ini_set('memory_limit','1000M');

	$cmsUserInfo = $this->cmsUserValidation();
	if($cmsUserInfo['usergroup']!='enterprise'){
		header("location:/enterprise/Enterprise/disallowedAccess");
		exit();
	}

	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder();

	$courseRepository = $listingBuilder->getCourseRepository();
	$instituteRepository = $listingBuilder->getInstituteRepository();

	$this->load->model('ListingModel');
	$this->load->model('listing/institutemodel');
	$this->load->model('listing/coursemodel');
	
	$listingIdByEnterpriseUser = $this->ListingModel->getActiveLisitingsForagroupOfOwner($userid);

	foreach($listingIdByEnterpriseUser as $listing){
		if($listing['listing_type']=='institute'){
			$instituteIdByEnterpriseUser[] = $listing['listing_type_id'];
		}
	}

	$contactCountSumArray =array();
	$viewCountSumArray = array();
	$finalInstituteArray = array();
	$finalCourseContactCount =array();	

	if(count($instituteIdByEnterpriseUser)==0)
	{
		return;
	}

	$courseListForinstitute =$this->institutemodel->getCoursesForInstitutes($instituteIdByEnterpriseUser);

	$instituteContactCount = $this->ListingModel->getContactCountForListings($instituteIdByEnterpriseUser,'institute');

	$courseTemp = "";
	foreach($courseListForinstitute as $key => $course_temp)
	{
		$courseTemp.=",".$course_temp['courseList'];
	}

	$courseList = explode(',',trim($courseTemp,','));

	$courseContactCount = $this->ListingModel->getContactCountForListings($courseList,'course');
	$total_view_count_institutes = $this->institutemodel->getInstituteViewCount($instituteIdByEnterpriseUser);

	$courses = array();
	$MAX_NUMBER_OF_COURSEIDS = 5000;
	$max_length= count($courseList);

	$start = 0;
	$limit = ($max_length) > $MAX_NUMBER_OF_COURSEIDS ? $MAX_NUMBER_OF_COURSEIDS : ($max_length);
	$courseContactCount = $this->ListingModel->getContactCountForListings($courseList,'course');
	$total_view_count_courses = $this->coursemodel->getCourseViewCount($courseList);
	
	while($start<$max_length){
		$coursesObjJunks = $courseRepository->findMultiple(array_slice($courseList,$start,$limit));
		foreach($coursesObjJunks as $course){
			$temp = array('courseTitle'=>$course->getName(),'course_id' =>$course->getId(),'viewCount' => $total_view_count_courses[$course->getId()]['viewCount'],'contact_count'=>array_key_exists($course->getId(),$courseContactCount) ? $courseContactCount[$course->getId()] : 0,'institute_id' =>$course->getInstId());	

			$finalCourseContactCount[$course->getInstId()][]= $temp;
		}
		unset($coursesObjJunks);

		$start +=$limit;
		if($max_length-$start >= $MAX_NUMBER_OF_COURSEIDS){
			$limit = $MAX_NUMBER_OF_COURSEIDS;
		}
		else{
			$limit = ($max_length-$start);
		}

	}

	unset($coursesObjJunks);

	$institutes = $instituteRepository->findMultiple($instituteIdByEnterpriseUser);

	foreach($institutes as $institute){
		$finalInstituteArray[$institute->getId()]=array(
				'institute_title'=>	$institute->getName(),
				'viewCount' =>$total_view_count_institutes[$institute->getId()]['viewCount'],
				'contactCount' => array_key_exists($institute->getId(),$instituteContactCount) ? $instituteContactCount[$institute->getId()] :0,
				'institute_id'=>$institute->getId()
				);	
	}
	foreach($finalInstituteArray as $instituteMinObj){
		$contact_sum=0;
		$view_sum=0;
		$contact_sum += $instituteMinObj['contactCount'];
		$view_sum += $instituteMinObj['viewCount'];

		foreach($finalCourseContactCount[$instituteMinObj['institute_id']] as $courseMinObj){
			$contact_sum += $courseMinObj['contact_count'];
			$view_sum += $courseMinObj['viewCount'];
		}
		$contactCountSumArray[$instituteMinObj['institute_id']] = array('totalCount'=>$contact_sum,'totalView'=>$view_sum);
	}

	$cmsPageArr['contactCountSumArray']=array($contactCountSumArray) ;
	$cmsPageArr['finalCourseContactCount']=$finalCourseContactCount;
	$cmsPageArr['finalInstituteContactCount']=$finalInstituteArray;
    }

    
    // Function remade for study abroad instead of adding code to existing function because
    // the structure for sending data shall be very different from the existing structure when universities are
    // added into the mixture and making an array structured accordingly seems more practical.
    // Author : Rahul Bhatnagar, May 2014
    function getSAContactAndViewCountDetails($userid, $usergroup,&$cmsPageArr){
	if($usergroup!='enterprise'){
		header("location:/enterprise/Enterprise/disallowedAccess");
		exit();
	}
	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder();
	//$universityRepository = $listingBuilder->getUniversityRepository();
	$departmentRepository = $listingBuilder->getAbroadInstituteRepository();
	$courseRepository = $listingBuilder->getAbroadCourseRepository();
	$this->load->model('listing/abroadlistingmodel');
	$this->load->model('listing/listingmodel');
	$this->load->model('listing/universitymodel');
	$this->load->model('listing/abroadinstitutemodel');
	$abroadInstituteModel = new AbroadInstituteModel();
	$universityModel = new UniversityModel();
	$listingModel = new ListingModel();
	$abroadListingModel = new abroadlistingmodel();
	$universityList = $abroadListingModel->getUniversitiesOfOwner($userid);
	if(count($universityList) == 0) return;
	$finalArray = array();
	$universityIds = array();
	foreach($universityList as $universityIndividual){
	    $finalArray[$universityIndividual['listing_type_id']] = array(
									  'universityId'=>$universityIndividual['listing_type_id'],
									  'universityTitle'=>$universityIndividual['listing_title'],
									  'departments' => array(),
									  'totalSubContactCount' =>0,
									  'totalSubViewCount'=>0
									  );
	    $universityIds[] = $universityIndividual['listing_type_id'];
	    $contactCount = $abroadListingModel->getContactCountForAbroadListing($universityIndividual['listing_type_id'],'university');
	    $contactCount = (reset($contactCount));
	    if($contactCount['vals'] == '') $contactCount['vals'] = 0;
	    $finalArray[$universityIndividual['listing_type_id']]['contactCount'] = $contactCount['vals'];
	    $finalArray[$universityIndividual['listing_type_id']]['totalSubContactCount']+=$contactCount['vals'];
	}
	$universityCounts = $universityModel->getViewCountOfUniversities($universityIds);
	foreach($universityCounts as $countData){
	    $finalArray[$countData['listing_type_id']]['viewCount'] = $countData['viewCount'];
	    $finalArray[$countData['listing_type_id']]['totalSubViewCount'] += $countData['viewCount'];
	}
	$departmentsOfUniversities = $universityModel->getDepartmentsOfUniversities($universityIds);
	foreach($departmentsOfUniversities as $dept){
	    $finalArray[$dept['university_id']]['departments'][$dept['institute_id']] = array();
	}
	$deptCountTotal = 0;
	$ListingTypeIdArray = array();
	foreach($finalArray as $univId=>$univItem){
	    foreach($univItem['departments'] as $key => $value){
		$departmentObj = $departmentRepository->find($key);
		$finalArray[$univId]['departments'][$key]['departmentId'] = $departmentObj->getId();
		$ListingTypeIdArray[] = $departmentObj->getId();
		$finalArray[$univId]['departments'][$key]['departmentTitle'] = $departmentObj->getName();
		$contactCount = $abroadListingModel->getContactCountForAbroadListing($departmentObj->getId(),'department');
		$contactCount = reset($contactCount);
		if($contactCount['vals'] == '') $contactCount['vals'] = 0;
		$finalArray[$univId]['departments'][$key]['contactCount'] = $contactCount['vals'];
		$finalArray[$univId]['totalSubContactCount']+=$contactCount['vals'];
		$finalArray[$univId]['departments'][$key]['Courses'] = array();
		$coursesOfDepartment = $abroadInstituteModel->getCoursesOfDepartments(array($key));
		foreach($coursesOfDepartment as $course){
		    $courseObj = $courseRepository->find($course['course_id']);
		    $finalArray[$univId]['departments'][$key]['Courses'][$course['course_id']] = array();
		    $finalArray[$univId]['departments'][$key]['Courses'][$course['course_id']]['courseId']=$courseObj->getId();
		    $ListingTypeIdArray[] = $courseObj->getId();
		    $finalArray[$univId]['departments'][$key]['Courses'][$course['course_id']]['courseTitle']=$courseObj->getName();
		    $contactCount = $abroadListingModel->getContactCountForAbroadListing($courseObj->getId(),'course');
		    $contactCount = reset($contactCount);
		    if($contactCount['vals'] == '') $contactCount['vals'] = 0;
		    $finalArray[$univId]['departments'][$key]['Courses'][$course['course_id']]['contactCount'] = $contactCount['vals'];
		    $finalArray[$univId]['totalSubContactCount'] += $contactCount['vals'];
		}
	    }
	}
	//Now to fill in viewCounts and maintain totalSubViewCount
	$viewCountArray = $abroadListingModel->getViewCountForAggregatedAbroadListings($ListingTypeIdArray);
	$viewCountArrayParsed = array();
	foreach($viewCountArray as $viewItem){
	    $viewCountArrayParsed[$viewItem['listing_type']][$viewItem['listing_type_id']] = $viewItem['viewCount'];
	}
	
	foreach($finalArray as $univId => $univItem){
	    foreach($univItem['departments'] as $deptId=>$deptItem){
		if(strpos($finalArray[$univId]['departments'][$deptId]['departmentTitle'],'_DUMMYDEPARTMENT')===false){
		    $finalArray[$univId]['departments'][$deptId]['viewCount'] = $viewCountArrayParsed['institute'][$deptId];
		    $finalArray[$univId]['totalSubViewCount']+=$viewCountArrayParsed['institute'][$deptId];
		}
		foreach($deptItem['Courses'] as $courseId=>$courseItem){
		    $finalArray[$univId]['departments'][$deptId]['Courses'][$courseId]['viewCount'] = $viewCountArrayParsed['course'][$courseId];
		    $finalArray[$univId]['totalSubViewCount']+=$viewCountArrayParsed['course'][$courseId];
		}
	    }
	}
	$cmsPageArr['tableData'] = $finalArray;
	/*_p($viewCountArray);
	_p($viewCountArrayParsed);
	_p($finalArray);die;*/
    }

/**
 * Search for Institutes based on the last modify date between from and to date inclusively.
 * @param  from date and to date.
 * @returns empty if no institute found
 * @return Array of Institute with 	institute_name ,location, flagship_course_category, totalViewCount, totalViewCountRecently, sales person, percantage_completion,last_modify_date
 */
function getlistingMIS($from ="" ,$to =""){
	$cmsUserInfo = $this->cmsUserValidation();

	if($cmsUserInfo['usergroup']!='cms'){
		header("location:/enterprise/Enterprise/disallowedAccess");
		exit();
	}

	ini_set('memory_limit','2000M');
	ini_set('max_execution_time','1000');

	if(empty($from))
	{
		$from = $this->input->post('from_date');
	}
	if(empty($to))
	{
		$to = $this->input->post('to_date');
	}	
	if(empty($from) && empty($to) || $from == 'Start Date' && $to =='End Date') {
		return array();	
	} 
	$resultArray =array();	   

	$this->load->model('listingmodel');
	$this->load->model('listing/institutemodel');
	$this->load->model('listing/listingextendedmodel');
	$this->load->model('listing/coursemodel');
	$this->load->model('user/usermodel');

	$updatedListings = $this->listingextendedmodel->getUpdatedListings($from ,$to);

	if(count($updatedListings)==0)
	{
		$resultArray['message'] = "No institute found between the selected date";
		return $resultArray;
	}
	else
	{
		$this->load->builder('ListingBuilder','listing');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->library('sums_product_client');
		$this->load->library('Subscription_client');

		$objSumsProduct =  new Sums_Product_client();
		$subscription_client = new Subscription_client();
		$listingBuilder = new ListingBuilder;
		$categoryBuilder = new CategoryBuilder;

		$categoryRepository = $categoryBuilder->getCategoryRepository();
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		
		$MAX_NUMBER_OF_LISTINGSIDS = 1000;
		$max_length= count($updatedListings);

		$start = 0;
		$limit = ($max_length) > $MAX_NUMBER_OF_LISTINGSIDS ? $MAX_NUMBER_OF_LISTINGSIDS : ($max_length);
	
		while($start<$max_length){

			$listOfCourseIds = "";
			$instituteIdsSetFirst =array();
			$instituteIdsSetSecond =array();
			$institutesObjectForSecondSet = array();
			$institutesObjectForFirstSet = array(); 
			$insituteCourseArray = array();
			$flagshipCourseArray = array();
			$categoryIdCategoryNameArray = array();	
			$hashMapForInstitutes = array();
			$updatedListings_slice = array();			
			
			$updatedListings_slice = array_slice($updatedListings,$start,$limit);

			foreach($updatedListings_slice as $resultData){
				if($resultData['listing_type']=='course'){				
					$listOfCourseIds.=$resultData['listing_type_id'].","; 
				}
				elseif($resultData['listing_type']=='institute'){
					array_push($instituteIdsSetFirst,$resultData['listing_type_id']);
					$hashMapForInstitutes[$resultData['listing_type_id']] = 1;
				}
			}

			$listOfCourseIds = trim($listOfCourseIds,',');
			
			if(strlen($listOfCourseIds)!=0)
			{
				$dbHandle = $this->listingmodel->getdbHandle();
				$insituteandCourseids = $this->listingmodel->getInstitutesForMultipleCourses($dbHandle,$listOfCourseIds);

				foreach($insituteandCourseids as $course_id => $institute_id)
				{
					if($hashMapForInstitutes[$institute_id]==1)
						continue;
						
					if(array_key_exists($institue_id,$insituteCourseArray)){			
						array_push($insituteCourseArray[$institute_id],$course_id);		
					}
					else
					{
						$insituteCourseArray[$institute_id] = array($course_id);			
					}
				}
				foreach($insituteandCourseids as $key=>$value)
				{
					if($hashMapForInstitutes[$value]==1)continue;
					array_push($instituteIdsSetSecond,$value);				
				}
				$instituteIdsSetSecond = array_unique($instituteIdsSetSecond);
				if(count($instituteIdsSetSecond)!= 0)
				{
					$institutesObjectForSecondSet = $instituteRepository->findWithCourses($insituteCourseArray);
				}
			}
			
			$instituteIdsSetFirst      = array_unique($instituteIdsSetFirst);		
			$updatedInstituteIds       = array_merge($instituteIdsSetFirst,$instituteIdsSetSecond);
			$updatedInstituteIds       = array_unique($updatedInstituteIds);
			$updatedInstituteIdsString = implode($updatedInstituteIds,',');
			
			$instituteSetFirstWithCoursesTemp = $this->institutemodel->getCoursesForInstitutes($instituteIdsSetFirst);
			$instituteSetFirstWithCourses = array();

			foreach($instituteSetFirstWithCoursesTemp as $institute_id => $course_temp)
			{
				$instituteSetFirstWithCourses[$institute_id] =explode(',',$course_temp['courseList']);
			}
			
			if(count($instituteSetFirstWithCourses)!=0)
			{
				$institutesObjectForFirstSet = $instituteRepository->findWithCourses($instituteSetFirstWithCourses);
			}

			$institutes = array_merge($institutesObjectForFirstSet,$institutesObjectForSecondSet);
	
			$percentageCompletionArray= $this->institutemodel->getProfileCompletionForaListofLisitngs($updatedInstituteIds);

			foreach($institutes as $institute)
			{
				$flagshipCourseArray[] = $institute->getFlagshipCourse()->getId();
			}

			$courseAndCategoryIds = $instituteRepository->getCategoryIdsOfListing($flagshipCourseArray,'course','true');

			foreach($courseAndCategoryIds as $categoryId)
			{
				$categoryIds[] = $categoryId;
			}

			$categoryObjects = $categoryRepository->findMultiple($categoryIds);

			foreach($categoryObjects as $category)
			{
				$categoryIdCategoryNameArray[$category->getId()] = $category->getName();
			}

			foreach($flagshipCourseArray as $course_id)
			{
				$courseIdCategoryNameMapping[$course_id] = $categoryIdCategoryNameArray[$courseAndCategoryIds[$course_id]]; 
			}

			$request = array('listing_type_id'=>$flagshipCourseArray , 'listing_type'=>'course');
			$courseClientInfo = $this->listingmodel->getListingClientInfo($request);
		
			$clientListArrayForAllFlagshipCourseIds = array();
			$clientUsernamesForListOfCLientIds = array(); 
			
			if(count($flagshipCourseArray)>1){
				foreach($courseClientInfo as $courseid => $clientInfo)
				{
					$flagshipCourseClientArray[$courseid ] = $clientInfo['userid'];
					$clientListArrayForAllFlagshipCourseIds[] = $clientInfo['userid'];
				}
			}
			
			else
			{
				$flagshipCourseClientArray[$courseClientInfo['listing_type_id']] = $clientInfo[$courseClientInfo['userid']];
				$clientListArrayForAllFlagshipCourseIds[] = $courseClientInfo['userid'];
			}
			
			$clientUsernamesForListOfCLientIds = $this->usermodel->getUserGroupForListOfUserIds($clientListArrayForAllFlagshipCourseIds);
			
			$totalRecentlyViewCount =0;
			$totalViewCount =0;
			
			$salesPersonInfoCliendIdArray = $subscription_client->sgetSalesPersonInfo(implode(',',
			$clientListArrayForAllFlagshipCourseIds));
			
			$courseSalesPersonArray = array();
	
			foreach($flagshipCourseClientArray as $key =>$value){
				
					if($clientUsernamesForListOfCLientIds[$value] == 'cms'|| $clientUsernamesForListOfCLientIds[$value] ==  'privileged')
					{
							$courseSalesPersonArray[$key] = 'cmsAdmin';							
					}
					else 
					{	
						$courseSalesPersonArray[$key] = $salesPersonInfoCliendIdArray[$value]['displayName'] ? $salesPersonInfoCliendIdArray[$value]['displayName'] :$salesPersonInfoCliendIdArray[$value]['firstname']." ".$salesPersonInfoCliendIdArray[$value]['lastname'] ;
									
					}
				
			}
	
			$totalRecentlyViewCountArray = $this->institutemodel->getRecentViewCount($updatedInstituteIds);

			$indexCourseList = $this->institutemodel->getCoursesForInstitutes($updatedInstituteIds);
	
			$modified_course_ids = '';
			foreach( $indexCourseList as $courseList)
			{
				$modified_course_ids.= ','.$courseList['courseList']; 
			}
			$modified_course_ids = trim($modified_course_ids,',');	
			$modified_course_ids = explode(',',$modified_course_ids);

			$totalRecentlyViewCountCourseArray = $this->coursemodel->getRecentViewCount($modified_course_ids);
			$total_view_count_institutes = $this->institutemodel->getInstituteViewCount($updatedInstituteIds);
			$total_view_count_courses = $this->coursemodel->getCourseViewCount($modified_course_ids);
	
			foreach($institutes as $institute)
			{
				$totalRecentlyViewCount = $totalRecentlyViewCountArray[$institute->getId()];
				$totalViewCount = $total_view_count_institutes[$institute->getId()]['viewCount'];
				$courses = $institute->getCourses();
				
				$last_modify_date_listing = $institute->getLastUpdatedDate();
				foreach($courses as $course )
				{					
					$totalRecentlyViewCount+=$totalRecentlyViewCountCourseArray[$course->getId()] ;
					$totalViewCount += $total_view_count_courses[$course->getId()]['viewCount'];
					if($course->getLastUpdatedDate() > $last_modify_date_listing)
					{
						$last_modify_date_listing = $course->getLastUpdatedDate();
					}
				}

				$temp = array('institute_name'=>$institute->getName(),
						'location' => $institute->getMainLocation()->getCity()->getName(),
						'flagship_course_category' => !empty($courseIdCategoryNameMapping[$institute->getFlagshipCourse()->getId()]) ? $courseIdCategoryNameMapping[$institute->getFlagshipCourse()->getId()] : 'Not Available',
						'totalViewCount' => $totalViewCount ? $totalViewCount :0,
						'totalViewCountRecently' => $totalRecentlyViewCount ? $totalRecentlyViewCount :0,
						'sales person' => !empty($courseSalesPersonArray[$institute->getFlagshipCourse()->getId()]) ? $courseSalesPersonArray[$institute->getFlagshipCourse()->getId()] : 'Not Available',					 
						'percantage_completion' => $percentageCompletionArray[$institute->getId()] ? $percentageCompletionArray[$institute->getId()]:0,					 
						'last_modify_date' => $last_modify_date_listing					 
					     ); 

				$resultArray[$institute->getId()] =  $temp;
			}

			$start +=$limit;
			if($max_length-$start >= $MAX_NUMBER_OF_LISTINGSIDS){
				$limit = $MAX_NUMBER_OF_LISTINGSIDS;
			}
			else{
				$limit = ($max_length-$start);
			}

			unset($instituteIdsSetFirst);
			unset($instituteIdsSetSecond);
			unset($institutesObjectForSecondSet);
			unset($institutesObjectForSecondSet );
			unset($insituteCourseArray);
			unset($flagshipCourseArray);
			unset($categoryIdCategoryNameArray);
			unset($listOfCourseIds);
			unset($flagshipCourseClientArray);
			unset($institutes);
		}
	}
	$this->makeCsvListingMis($resultArray,$from,$to);
	return $resultArray;
}

function makeCsvListingMis($resultSet,$from,$to)
{
	ob_start();
	$filename = strtotime($from).strtotime($to).'ListingMis.csv';
	$mime = 'text/x-csv';
	$columnListArray = array();
	$columnListArray[]='Institute Name';
	$columnListArray[]='Location';
	$columnListArray[]='Category of flagship course';
	$columnListArray[]='Total no. of views';
	$columnListArray[]='Views in last 45 days';
	$columnListArray[]='Name of sales person';
	$columnListArray[]='% completion';
	$columnListArray[]='Last updated on';
	$ColumnList = $columnListArray;

	$data_array[] = $columnListArray;
	foreach ($resultSet as $info) {
		$data_array[] = array($info['institute_name'],$info['location'],$info['flagship_course_category'],
				$info['totalViewCount'],$info['totalViewCountRecently'],$info['sales person'],$info['percantage_completion'],
				$info['last_modify_date']);
	}
	$file_path = "/tmp/$filename";
	$file_pointer = fopen($file_path, "w");
	foreach ($data_array as $fields) 	
	{
		fputcsv($file_pointer, $fields);
	}
	fclose($file_pointer);
	ob_end_flush();
}

function getlistingMISForDownload($from,$to){
	ob_start();	

	$filename = strtotime($from).strtotime($to).'ListingMis.csv';	
	$filepath = "/tmp/$filename";

	$csv = file_get_contents($filepath);
	$csv = trim($csv);
	if(empty($csv))
	{
		$this->getlistingMIS($from,$to);
		$this->getlistingMISForDownload($from,$to);
		return;
	}

	header("content-type:application/csv;charset=UTF-8");
	header("Content-language: en");
	header("Content-Disposition: attachment; filename=file.csv");
	header("Pragma: no-cache");
	header("Expires: 0");


	print_r($csv);
	ob_end_flush();
}


/*
 * function for ccavenue USD payment gateway
 */ 

function ccavenue()
{
    $cmsUserInfo = $this->cmsUserValidation();
    $userid = $cmsUserInfo['userid'];
    $usergroup = $cmsUserInfo['usergroup'];
    $thisUrl = $cmsUserInfo['thisUrl'];
    $validity = $cmsUserInfo['validity'];
    
    $request['paymentOption'] = $this->input->post('paymentOption',true);
    $cmsPageArr = array();
    $cmsPageArr['userid'] = $userid;
    $cmsPageArr['usergroup'] = $usergroup;
    $cmsPageArr['thisUrl'] = $thisUrl;
    $cmsPageArr['validateuser'] = $validity;
    $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
    $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
    
    $paymentIdArr = split("_",$request['paymentOption']);
    $paymentId = $paymentIdArr[0];
    $partPaymentId = $paymentIdArr[1];

    $Partially_Amount_Paid = trim($this->input->post("amount"));

    $this->load->library('subscription_client');
    $objSumsProduct = new Subscription_client();

    $Partially_Amount_Paid = number_format($Partially_Amount_Paid, 2, '.', '');
    $this->load->library('ccavenue_gateway_lib');
    $obj = new ccavenue_gateway_lib();
    $Merchant_Id = $obj->get_Merchant_Id();
    $WorkingKey = $obj->get_WorkingKey();   
    $Access_Code = $obj->get_Access_Code();      
    $redirectURL=$obj->get_redirectURL();
    // $TxnType = $obj->get_TxnType();
    // $actionID = $obj->get_actionID();       
    $response['paymentData'] = array();
    $response['paymentData'] =  $objSumsProduct->getCreditCardPaymentDetails($userid,$paymentId,$partPaymentId); 
    
    foreach($response['paymentData'] as $traversalarray){
        if($traversalarray['Payment_Id'] == $paymentId  && $traversalarray['Part_Number'] == $partPaymentId)
        {
            $Amount = $traversalarray['DueAmount'];
            $TransactionTable_Id = $traversalarray['TransactionId'];
            break;
        }
    }

//    $TransactionTable_Id = $response['paymentData'][0]['TransactionId'];
    $transactionId = ltrim($TransactionTable_Id, 0);

    if($Partially_Amount_Paid > $Amount || $Partially_Amount_Paid == '0.00') {
        $redirectUrl = 'payment/true';
        header("location:".$redirectUrl);
        exit();
    }


    $json = json_encode($response['paymentData'][0]);
    $this->load->library('enterprise_client');
    $entObj = new Enterprise_client();
    $userId = $cmsUserInfo['userid'];
    $keys = $entObj->insertCreditCardDetails('inProgress',$userId,$json, $paymentId,$partPaymentId,$transactionId,$Partially_Amount_Paid, 'CCAVENUE');
    $key = $keys['credit_log_key'];

    $Credit_Log_Table_Key = $key;

    $Order_Id= $transactionId."_".$paymentId."_".$partPaymentId."_".$key;//."_".date(Ymd);
    $checksum = $obj->calculatechecksum($Partially_Amount_Paid,$Order_Id);

    // $data=array(
    //         'Merchant_Id' => $Merchant_Id,  
    //         'Order_Id' => $Order_Id,    
    //         'TxnType' => $TxnType,
    //         'actionID' => $actionID,
    //         'Redirect_Url' => $redirectURL, 
    //         'checksum' => $checksum,    
    //         'Amount' => $Partially_Amount_Paid  
    //         );

    $data = array(
                'merchant_id' => $Merchant_Id,  
                'order_id' => $Order_Id,     
                'amount' => $Partially_Amount_Paid ,
                'currency' => 'USD',
                'redirect_url' => $redirectURL,
                'cancel_url' => $redirectUrl,
                'language' => 'EN'
                );

    foreach ($data as $key => $value){
        $merchant_data.=$key.'='.urlencode($value).'&';
    }
    
    $encrypted_data = $obj->encrypt($merchant_data,$WorkingKey);
    
    $result = array_merge((array)$data, (array)$cmsPageArr);
    $result['Access_Code'] = $Access_Code;
    $result['encrypted_data'] = $encrypted_data;
    
    $this->load->view('enterprise/ccavenuepaymentgateway_view',$result);

}

/*
 * function for ccavenue INR payment gateway
 */ 

function ccavenueindian()
{


    $cmsUserInfo = $this->cmsUserValidation();
    $userid = $cmsUserInfo['userid'];
    $usergroup = $cmsUserInfo['usergroup'];
    $thisUrl = $cmsUserInfo['thisUrl'];
    $validity = $cmsUserInfo['validity'];
    $request['paymentOption'] = $this->input->post('paymentOption',true);
    $cmsPageArr = array();
    $cmsPageArr['userid'] = $userid;
    $cmsPageArr['usergroup'] = $usergroup;
    $cmsPageArr['thisUrl'] = $thisUrl;
    $cmsPageArr['validateuser'] = $validity;
    $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
    $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
    $paymentIdArr = split("_",$request['paymentOption']);
    $paymentId = $paymentIdArr[0];
    $partPaymentId = $paymentIdArr[1];
    $Partially_Amount_Paid = trim($this->input->post("amount"));

    $this->load->library('subscription_client');
    $objSumsProduct =  new Subscription_client();
    $paymentdetailsarray['data'] = $objSumsProduct->getCreditCardPaymentDetails($userid,$paymentId,$partPaymentId);

    foreach($paymentdetailsarray['data'] as $traversalarray){
        if($traversalarray['Payment_Id'] == $paymentId  && $traversalarray['Part_Number'] == $partPaymentId)
        {
            $Amount = $traversalarray['DueAmount'];
            $TransactionTable_Id = $traversalarray['TransactionId'];
            break;
        }
    }

    $Partially_Amount_Paid = number_format($Partially_Amount_Paid, 2, '.', '');
    // $this->load->library('ccavenue_rupeegateway_lib');
    // $obj = new ccavenue_rupeegateway_lib();
   // $Merchant_Id = $obj->get_Merchant_Id();
   // $WorkingKey = $obj->get_WorkingKey();           
   // $redirectURL = $obj->get_redirectURL();
  //  $paymentGatewayURL = $obj->get_PaymentGatewayURL();
   // $accessCode = $obj->get_AccessCode();

    $response['paymentData'] = array(); 
    $response['paymentData'] =  $objSumsProduct->getCreditCardPaymentDetails($userid,$paymentId,$partPaymentId); 
    
    $transactionId = ltrim($TransactionTable_Id, 0);

    if($Partially_Amount_Paid > $Amount || $Partially_Amount_Paid == '0.00' ) {
        $redirectUrl = 'payment/true';
        header("location:".$redirectUrl);
        exit();
    }

    $json = json_encode($response['paymentData'][0]);
    $this->load->library('enterprise_client');
    $entObj = new Enterprise_client();
    $userId = $cmsUserInfo['userid'];
    $keys = $entObj->insertCreditCardDetails('inProgress',$userId,$json, $paymentId,$partPaymentId,$transactionId,$Partially_Amount_Paid, 'CCAVENUE');
    $key = $keys['credit_log_key'];

    $Credit_Log_Table_Key = $key;

    $Order_Id = $transactionId."-".$paymentId."-".$partPaymentId."-".$key;//."_".date(Ymd);
    //$checksum = $obj->calculatechecksum($Partially_Amount_Paid,$Order_Id);

    $this->load->library('Online/payment/PaymentProcessor');
    $paymentProcessor = new PaymentProcessor();
    $paymentFields = $paymentProcessor->getPaymentFields($Partially_Amount_Paid,$Order_Id, 'enterprise');

    $data=array(
            'encRequest' => $paymentFields['merchant_data'],
            'accessCode' => $paymentFields['access_code'],
            'payment_gateway_url' => $paymentFields['payment_gateway_url'] 
            // 'Merchant_Id' => $Merchant_Id,
            // 'Order_Id' => $Order_Id,
            // 'Redirect_Url' => $redirectURL, 
            // 'checksum' => $checksum,    
            // 'Amount' => $Partially_Amount_Paid  
            );       
    $result = array_merge((array)$data, (array)$cmsPageArr);

    $this->load->view('enterprise/ccavenueindianpaymentgateway_view',$result);

}


/*
 * function for paypal payment gateway
 */ 
function paypalgateway()
{
    $cmsUserInfo = $this->cmsUserValidation();
    $userid = $cmsUserInfo['userid'];
    $usergroup = $cmsUserInfo['usergroup'];
    $thisUrl = $cmsUserInfo['thisUrl'];
    $validity = $cmsUserInfo['validity'];

    $request['paymentOption'] = $this->input->post('paymentOption',true);
    $cmsPageArr = array();
    $cmsPageArr['userid'] = $userid;
    $cmsPageArr['usergroup'] = $usergroup;
    $cmsPageArr['thisUrl'] = $thisUrl;
    $cmsPageArr['validateuser'] = $validity;
    $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
    $cmsPageArr['productDetails'] = $cmsUserInfo['myProducts'];
    $paymentIdArr = split("_",$request['paymentOption']);
    $paymentId = $paymentIdArr[0];
    $partPaymentId = $paymentIdArr[1];
    $Partially_Amount_Paid = trim($this->input->post("amount"));

    $this->load->library('subscription_client');
    $objSumsProduct =  new Subscription_client();

    $response['paymentData'] = array();
    $response['paymentData'] =  $objSumsProduct->getCreditCardPaymentDetails($userid,$paymentId,$partPaymentId); 


    foreach($response['paymentData'] as $traversalarray){
        if($traversalarray['Payment_Id'] == $paymentId  && $traversalarray['Part_Number'] == $partPaymentId)
        {
            $Amount = $traversalarray['DueAmount'];
            $Transid = $traversalarray['TransactionId'];
            $json = json_encode($traversalarray);
            break;
        }
    }

    if($Partially_Amount_Paid > $Amount || $Partially_Amount_Paid == '0.00') {
        $redirectUrl = 'payment/true';
        header("location:".$redirectUrl);
        exit();
    }

    $transactionid = (int)$Transid; 
    $this->load->library('enterprise_client');
    $entObj = new Enterprise_client();
    $userId = $cmsUserInfo['userid'];
    $keys = $entObj->insertCreditCardDetails('inProgress',$userId,$json, $paymentId,$partPaymentId,$transactionid,$Partially_Amount_Paid, 'PAYPAL');
    $key = $keys['credit_log_key'];     

    $Credit_Log_Table_Key = $key;
    $order_id = $transactionid."_".$paymentId."_".$partPaymentId."_".$key;

    $this->load->library('Paypal_Lib');
    $obj = new Paypal_Lib();

    $obj->add_field('business', 'paypal@shiksha.com');
    $obj->add_field('return', site_url('enterprise/Enterprise/paypalreturn'));
    $obj->add_field('cancel_return', site_url('enterprise/Enterprise/payment'));
    //$obj->add_field('notify_url', site_url('enterprise/Enterprise/ipn')); 

    $obj->add_field('item_number', $order_id);

    $obj->add_field('amount', $Partially_Amount_Paid);
    $data['paypal_form'] = $this->paypal_lib->paypal_form();


    $obj->paypal_auto_form();

}

/*
 * function for ICICI payment gateway
 */ 

function paymentGateway() {
    //error_log_shiksha("RECEIVED POST DATA: ".print_r($_POST,true));
    $appId = 1;
    $this->init();
    $request['cmsUserInfo'] = $this->cmsUserValidation();
    $request['paymentOption'] = $this->input->post('paymentOption',true);
    $paymentIdArr = split("_",$request['paymentOption']);
    $userId = $request['cmsUserInfo']['userid'];

    $paymentId = $paymentIdArr[0];
    $partPaymentId = $paymentIdArr[1];

    $Partially_Amount_Paid = trim($this->input->post("amount"));
    $this->load->library('subscription_client');
    $objSumsManage = new Subscription_client();
    $response['paymentData'] =  $objSumsManage->getCreditCardPaymentDetails($userId,$paymentId,$partPaymentId);

    $traversalarray = array();
    foreach($response['paymentData'] as $traversalarray){
        if($traversalarray['Payment_Id'] == $paymentId  && $traversalarray['Part_Number'] == $partPaymentId)
        {
            $Transid = $traversalarray['TransactionId'];
            $Amount = $traversalarray['DueAmount'];
            $transactionid = (int)$Transid;
            $json = json_encode($traversalarray);
            break;
        }    
    }

    if($Partially_Amount_Paid > $Amount || $Partially_Amount_Paid == '0.00') {
        $redirectUrl = 'payment/true';
        header("location:".$redirectUrl);
        exit();
    }

    if($traversalarray['Payment_Mode'] == "Credit Card(Offline)") {
        $this->load->library('enterprise_client');
        $entObj = new Enterprise_client();
        $headerTabs = $entObj->insertCreditCardDetails('inProgress',$userId,$json, $paymentId,$partPaymentId,$transactionid,$Partially_Amount_Paid, 'ICICI');
        $randNum = $headerTabs['rand'];
        $Credit_Log_Table_Primarykey = $headerTabs['credit_log_key'];
        $orderId = $transactionid."_".$paymentId."_".$partPaymentId."_".$Credit_Log_Table_Primarykey;

        header("Location: ".CREDITCARD."/test/jsp/Ssl.jsp?randNumId=".$randNum."&orderid=".$orderId);
    }else {
        header("Location: /enterprise/Enterprise/payment");
    }

}

function payment($checkAmountVariable = 'false') {
    $appId = 1;    
    $cmsUserInfo = $this->cmsUserValidation();
    $userid = $cmsUserInfo['userid'];
    $usergroup = $cmsUserInfo['usergroup'];
    $thisUrl = $cmsUserInfo['thisUrl'];
    $validity = $cmsUserInfo['validity'];
    $this->init();

    $flagMedia = 1;
    $cmsPageArr = array();

    global $homePageMap;
    $keyPageArray = array_flip($homePageMap);
    $spaceNamedArray = str_replace("_"," ",$keyPageArray);
    $cmsPageArr['keyid_page_name'] = json_encode($spaceNamedArray);
    $cmsPageArr['totalKeyPageCount'] = max($homePageMap);

    $cmsPageArr['flagMedia'] = $flagMedia;
    $cmsPageArr['userid'] = $userid;
    $cmsPageArr['usergroup'] = $usergroup;
    $cmsPageArr['thisUrl'] = $thisUrl;
    $cmsPageArr['validateuser'] = $validity;
    $cmsPageArr['headerTabs'] =  $cmsUserInfo['headerTabs'];
    $cmsPageArr['myProducts'] = $cmsUserInfo['myProducts']; 
    $cmsPageArr['usergroup'] = 'enterprise';
    $entObj = new Enterprise_client();
    $cmsPageArr['prodId'] = '27';
    $cmsPageArr['checkAmountVariable'] = $checkAmountVariable;

    // Start Online form change by pranjul 13/10/2011
    $this->load->library('OnlineFormEnterprise_client');
    $ofObj = new OnlineFormEnterprise_client();
    $cmsPageArr['showOnlineFormEnterpriseTab'] = $ofObj->checkOnlineFormEnterpriseTabStatus($userid);
    // End Online form change by pranjul 13/10/2011
    $onBehalfOf = $this->input->post('onBehalfOf');
    if ($onBehalfOf=="true")
    {
        $userid = $this->input->post('selectedUserId',true);
        $this->load->library('register_client');
        $regObj = new Register_client();
        $arr = $regObj->userdetail(1,$userid);
        $cmsPageArr['userDetails'] = $arr[0];
        $cmsPageArr['userDetails']['clientUserId'] = $userid;
    }

    $this->load->library('subscription_client');
    $objSumsProduct =  new Subscription_client();
    $cmsPageArr['paymentData'] = $objSumsProduct->getCreditCardPaymentDetails($userid);

    //echo "<pre>";print_r($cmsPageArr['paymentData']);echo "</pre>";
    $this->load->view('enterprise/creditCardPayment',$cmsPageArr);
}
 
    function getPopularCourseDropDown($institute_id) {
	
	if(empty($institute_id)) {
		return '';
	}
	
    $to_return_string = "<option value=''>Select course</option>";
	$option_string = "";
    $this->load->builder("nationalInstitute/InstituteBuilder");
    $instituteBuilder = new InstituteBuilder();
    $instituteRepository = $instituteBuilder->getInstituteRepository();
    $courses_result = $instituteRepository->getCoursesOfInstitutes(array($institute_id));
    $courses = $courses_result[$institute_id]->getCourse();
    foreach ($courses as $courseId => $courseObj) {
        $course_name_array[$courseObj->getId()] = $courseObj->getName();
    }
	//$course_name_array = $courses_result[$institute_id]['course_title_list'];
    natcasesort($course_name_array);	

	if(count($course_name_array) >0){
		
		foreach($course_name_array as $course=>$course_title) {
			$option_string = $option_string."<option title='".html_escape($course_title)."' value='".$course."'>$course_title</option>";
		}
		echo $to_return_string.$option_string;

	} else {
		return "";
	}
    }


    /**
     * To add 100 bronze free subscription to all national clients
     */
    function addCreditToAllNationalUser(){
            
        ini_set("memory_limit", '-1');
        ini_set('max_execution_time', -1);

        $this->load->model('user/usermodel');
        $usermodel = new usermodel();


        // code to get the last inserted client id from log 
        $this->load->helper('file');
        $insertedClientIdsString = read_file('/tmp/clientIdsNewSubscription.txt');
        if(!empty($insertedClientIdsString)){
            $insertedClientIdsArray      = explode("\n", $insertedClientIdsString);
            
            $insertedClientIdsArrayCount = count($insertedClientIdsArray);
            $lastInsertedIdsString       = $insertedClientIdsArray[$insertedClientIdsArrayCount-2];
            $lastInsertedIds             = trim(str_replace("Client Id:", "", $lastInsertedIdsString));
            
            if(is_numeric($lastInsertedIds)){
                $lastInsertedIds = $lastInsertedIds;
            }else{
                $lastInsertedIds = "";
            }    
        }else{
            $lastInsertedIds = "";
        }    
        
        


        $nationalUserList = $usermodel->getNationalClient($lastInsertedIds);
        error_log("Adding 100 bronze National Client Count:".count($nationalUserList));
        $this->load->library('subscription_client');
        $currentDate = date('Y-m-d-H-i-s');
        rename("/tmp/clientIdsNewSubscription.txt", "/tmp/clientIdsNewSubscription$currentDate.txt");
        unlink('/tmp/clientIdsNewSubscription.txt');

        foreach($nationalUserList as $res){
            $dervdProdId              = 1;
            $param['derivedProdId']   = $dervdProdId;
            $param['derivedQuantity'] = 100; // Changing free bronze quantity to 1000 from 1 for listing revamp stage-1
            $param['clientUserId']    = $res['userid'];
            $param['sumsUserId']      = 0;
            $param['subsStartDate']   = date(DATE_ATOM);
            //error_log("Array to addFreeSubscription ".print_r($param,true));     
            $objSumsClient            = new Subscription_client();
            $respSubs                 = $objSumsClient->addFreeSubscription(1,$param);
            
            error_log("Client Id:".$res['userid'].PHP_EOL,3,'/tmp/clientIdsNewSubscription.txt');
        }

        echo 'script has been executed';
        exit;
    }

    /**
     * Main Method for Featured Content CMS tab
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-02-23
     * @param  [type]     $subcategoryId [Subcategory ID]
     * @return none
     */
    function showFeaturedContentCMS(){
        
        // validate user
        $this->validateuser     = $this->checkUserValidation();
        $this->cmsUserInfo      = $this->cmsUserValidation();
        /*$courseCommonLib=$this->load->library('coursepages/CoursePagesCommonLib');
        $COURSE_PAGES_SUB_CAT_ARRAY=$courseCommonLib->getCourseHomePageDictionary(0);
        $courseHomePageId          = $courseHomePageId ? $courseHomePageId : key($COURSE_PAGES_SUB_CAT_ARRAY);*/
       
        // show this tab to CMS users only
        if($this->cmsUserInfo['usergroup']!='cms'){
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
        // prepare the header components
        $headerComponents = array(
                        'css' => array('headerCms', 'footer','common_new', 'exampages_cms','mainStyle','cmsForms'),
                        'js' => array('user','common','header','CalendarPopup'),
                        'jsFooter' => array('scriptaculous','cmsChpContent'),
                        'title' => "",
                        'product' => '',
                        'displayname' => (isset($this->validateuser[0]['displayname']) ? $this->validateuser[0]['displayname'] : ""),
                        'isOldTinyMceNotRequired' => 1
                        );

        // tab to be selected
        $this->cmsUserInfo['prodId']    = FEATURED_ARTICLES_TAB_ID;

        /*$coursepagecmsmodel     = $this->load->model("coursepages/coursepagecmsmodel");
        $featuredArticlesData   = $coursepagecmsmodel->getFeaturedArticles($courseHomePageId);*/

        // prepare view data
        //$displayData['courseHomeId']           = $courseHomePageId;
        //$displayData['featuredArticlesData']    = $featuredArticlesData;
        //$displayData['courseHomeDictionary']=$COURSE_PAGES_SUB_CAT_ARRAY;
        //unset($displayData['courseHomeDictionary']);
        // render the view
        echo $this->load->view('enterprise/headerCMS', $headerComponents,true);
        echo $this->load->view('common/calendardiv');
        echo $this->load->view('enterprise/cmsTabs', $this->cmsUserInfo, true);
        echo $this->load->view('enterprise/addEditFeaturedArticle', $displayData, true);
    }

    /**
     * Method to save featured article data
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-02-23
     * @return none
     */
    function saveFeaturedArticle(){

        // validate user
        $cmsUserInfo     = $this->cmsUserValidation();
        $coursePageCache = $this->load->library('coursepages/cache/CoursePagesCache');
        
        if($cmsUserInfo['usergroup']!='cms'){
            return;
        }

        $coursepagecmsmodel = $this->load->model("coursepages/coursepagecmsmodel");

        $data                   = array();
        $data['courseHomePageId'] = $this->input->post('courseHomePageId');
        $data['from_date']      = $this->input->post('from_date');
        $data['to_date']        = $this->input->post('to_date');
        $data['article_id']     = $this->input->post('article_id');
        $data['created_by']     = $cmsUserInfo['userid'];
        $data['status']         = 'live';
        $data['creation_date']  = date('Y-m-d H:i:s');
        
        $data['from_date']      = date("Y-m-d", strtotime($data['from_date']));
        $data['to_date']        = date("Y-m-d", strtotime($data['to_date']));


        $articlemodel = $this->load->model('article/articlenewmodel');

        if(empty($data['article_id'])){
            echo "article_error";
            die;
        }

        $isArticleExist = $articlemodel->checkArticleExist($data['article_id']);

        if(empty($isArticleExist)){
            echo "article_error";  
            die;          
        }

        $coursepagecmsmodel->addFeaturedArticle($data);
        // delete coursepage's article data cache of given subcategory
        $coursePageCache->deleteArticlesData($data['courseHomePageId']);

        echo "1";
    }

    function getFeaturedArticles(){
        $courseHomePageId = !empty($_POST['courseHomePageId']) ? $this->input->post('courseHomePageId') : '';
        $coursepagecmsmodel     = $this->load->model("coursepages/coursepagecmsmodel");
        $featuredArticlesData   = $coursepagecmsmodel->getFeaturedArticles($courseHomePageId);
        $displayData = array();
        $displayData['featuredArticlesData'] = $featuredArticlesData;
        echo $this->load->view('enterprise/featuredArticleTable',$displayData);
    }

    /**
     * Method to delete Featured Article data
     * @author Romil Goel <romil.goel@shiksha.com>
     * @date   2015-02-23
     * @return none
     */
    function deleteFeaturedArticle(){

        // validate user
        $cmsUserInfo     = $this->cmsUserValidation();
        $coursePageCache = $this->load->library('coursepages/cache/CoursePagesCache');
        
        if($cmsUserInfo['usergroup']!='cms'){
            return;
        }

        $courseHomePageId = $this->input->post('courseHomePageId');

        $coursepagecmsmodel     = $this->load->model("coursepages/coursepagecmsmodel");
        $id                     = $this->input->post('id');

        // delete coursepage's article data cache of given subcategory
        $coursePageCache->deleteArticlesData($courseHomePageId);
        
        $coursepagecmsmodel->deleteFeaturedArticle($id);
    }

    function performanceAnalysis()
    {
    //$handle = fopen("/tmp/categoryPagePerformace.log", "r");
    $handle = fopen(LOG_PERFORMANCE_DATA_FILE_NAME, "r");
    $parentArr = array();
    if ($handle) {
    while (($line = fgets($handle)) !== false) {
    $line = trim($line);
    if(!$line)
    continue;
    
    $line = trim($line, ",");
    $line = trim($line, ")");
    $line = trim($line, "array( ");
    $line = explode(",",$line);
    
    $arr = array();
    foreach($line as $row)
    {
    $row = explode("=>", $row);
    $arr[trim($row[0])] = trim(trim($row[1]),"'");
    }
    
    if($line)
    $parentArr[$arr['section']][] = $arr;
    
    }
    }
    else {
    _p("Error opening file");
    }
    
    
    
    echo "<style type='text/css'>
    body {background:#eee; margin:0; padding:0; font:normal 14px arial;}
    table {border-left:1px solid #ccc; border-top:1px solid #ccc;}
    td {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:8px 5px; font-size:13px;}
    th {border-right:1px solid #ccc; border-bottom:1px solid #ccc; padding:5px; text-align:left; background:#f6f6f6; font-size:13px;}
    h1 {font-size:30px; margin-top: 10px;}
    a {text-decoration:none; color:#444;}
    #overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: #000;
    filter:alpha(opacity=50);
    -moz-opacity:0.5;
    -khtml-opacity: 0.5;
    opacity: 0.3;
    z-index: 10;
    }
    </style>";
    
    echo "<table width='1000' cellspacing='0' cellpadding='0'><tbody><tr><th>#</th><th> Function </th><th>Total Time</th><th>No of Request</th><th>Avg Time (Sec)</th><th>Avg Time (MS)</th><th>Avg Peak Memory Usages (MB)</th><th>Highest Peak Memory Usages (MB)</th></tr>";
    
    $count = 1;
    foreach($parentArr as $key=>$row)
    {
    $totalTime = 0;
    $TotalPeakMemoryUsages = 0;
    $highestPeakMemoryUsages = 0;
    foreach($row as $typeRow)
    {
    $totalTime += $typeRow['timetaken']; 
    $TotalPeakMemoryUsages += $typeRow['PeakMemoryUsage']; 
    if($highestPeakMemoryUsages < $typeRow['PeakMemoryUsage']) {
    $highestPeakMemoryUsages = $typeRow['PeakMemoryUsage']; 
    }
    }
    echo "      <tr>
    <td valign='top'>".$count++."</td>
    <td valign='top'>$key</td>
    <td valign='top'>$totalTime</td>
    <td valign='top'>".count($row)."</td>
    <td valign='top'>".($totalTime/count($row)) ."</td>
    <td valign='top'>".($totalTime/count($row)*1000) ."</td>
    <td valign='top'>".($TotalPeakMemoryUsages/(count($row)*1048576)) ."</td>
    <td valign='top'>".$highestPeakMemoryUsages/1048576 ."</td>
    </tr>";
    //_p("Total time for ".$key." is ".$totalTime." for ".count($row)." entries. Average = ".($totalTime/count($row)) . " sec ==> " . ($totalTime/count($row)*1000) . " ms" );
    }
    echo "</tbody></table>";
    //_p($parentArr);
    fclose($handle);
    
    }

    function abroadUserCmsInterface($usergroup,$allowedUserGroupArr)
    {
        if(in_array($usergroup,$allowedUserGroupArr))
        {
            switch($usergroup)
            {
                case "saShikshaApply":
                    header('Location:/shikshaApplyCRM/rmcPosting/');
                    exit;
                    break;
                case "saCustomerDelivery":
                    header('Location:/salesDashboard/SalesDashboard');
                    exit;
                    break;
                case "saAuditor":
                    header('Location:/shikshaApplyCRM/rmcPosting/viewRMCCandidatesTable');
                    exit;
                    break;
                default:
                    header('Location:/listingPosting/AbroadListingPosting/');
                    exit;
            }
        }
    }

    /**
     * [redirectUserToDefaultTab this function will redirect the user to his default selected tab if it is not present in index function]
     * @author Ankit Garg <g.ankit@shiksha.com>
     * @date   2015-08-12
     * @param  [type]     $cmsUserInfo [containing user info array]
     * @return [type]                  [description]
     */
    private function redirectUserToDefaultTab($cmsUserInfo, $prodId) {
        foreach ($cmsUserInfo['headerTabs'][0]['tabs'] as $tabs) {
            if((empty($prodId) || ($cmsUserInfo['headerTabs'][0]['selectedTab'] == $prodId)) 
                && $tabs['tabId'] == $cmsUserInfo['headerTabs'][0]['selectedTab'] ) {
                $userInputURL = getCurrentPageURLWithoutQueryParams();
                $userInputURL  = trim($userInputURL);
                $userInputURL  = trim($userInputURL,"/");
                $queryString = substr(strrchr($_SERVER['REQUEST_URI'], "?"), 0);
                $actualUrl = SHIKSHA_HOME.$tabs['tabUrl'];
                if($userInputURL != $actualUrl) {
                    header("location:".$actualUrl.$queryString);
                    exit();
                }
            }
        }
    }

    /*
     * Function to send CSV mail to clients who want to have Multi Location Logic on national courses
     */
    function sendCustomMultiplocationMail(){
        ini_set('memory_limit', "512M");
        ini_set('time_limit', '600');
        $this->load->library('EnterpriseLib');
        $enterpriseLib = new EnterpriseLib;

        $customTableData = $enterpriseLib->getCustomMultiplocationDetails();

        if(empty($customTableData)){
            return;
        }

        $courseIds = array_keys($customTableData);
        $courseDetails = $enterpriseLib->getNationalCourseDetails($courseIds);

        $responseData = $enterpriseLib->getResponseDataForCourses($courseIds);

        if(empty($responseData)){
            return;
        }

        $userIds = $this->_extractUserIdFromResponseData($responseData); 
        $userData = $enterpriseLib->getUserDataFromUserId($userIds);

        $userCityData = $this->_makeUserCityData($responseData, $userData);
        
        $mailData = $enterpriseLib->makeCustomMultiLocationMailData($customTableData, $courseDetails, $responseData, $userData, $userCityData);
        
        $this->load->library('CollegeReviewForm/CollegeReviewLib');
        $crLib = new CollegeReviewLib;

        $message = 'Hi,<br/><br/>New responses have been generated on your listings. Please refer to the attachment for details.<br/><br/>Regards,<br/>Shiksha';
        $subject = 'New responses on your Shiksha listings';

        foreach ($mailData as $email => $userData) {
            $mailData[$email] = $enterpriseLib->formatCustomLocationMailData($userData);
            $crLib->send_csv_mail($mailData[$email],$message, $email, $subject, 'Shiksha Responses <info@shiksha.com>');
        }
        _p($mailData);
    }

    private function _extractUserIdFromResponseData($responseData){
        $userIds = array();

        foreach ($responseData as $key => $value) {
            foreach ($value as $k => $v) {
                $userIds[] = $v['UserId'];
            }
        }
        return $userIds;
    }

    private function _makeUserCityData($responseData, $userData){
        $userCityData = array();
        foreach ($responseData as $key => $value) {
            foreach ($value as $k => $v) {
                $temp = $userData[$v['UserId']];
                $temp['action'] = $v['action'];
                if(empty($temp['City'])){
                    $temp['City'] = 0;
                }
                $userCityData[$key][ $userData[$v['UserId']]['City'] ][] = $temp;
            }
        }
        return $userCityData;
    }



    function addAdmissionProcedureCMS($universityId=0) 
        { 
          $cmsUserInfo = $this->cmsUserValidation(); 

           // show this tab to CMS users only 
           if($cmsUserInfo['usergroup']!='cms'){ 
               header("location:/enterprise/Enterprise/disallowedAccess"); 
               exit(); 
           } 
           $userid = $cmsUserInfo['userid']; 
           $usergroup = $cmsUserInfo['usergroup']; 
           $thisUrl = $cmsUserInfo['thisUrl']; 
           $validity = $cmsUserInfo['validity']; 
           $this->init(); 
           $userStatus = $this->checkUserValidation(); 
           if((!is_array($userStatus)) && ($userStatus == "false")) 
           { 
               $currentUrl = site_url('enterprise/Enterprise/addAdmissionProcedureCMS'); 
               redirect('user/login/userlogin/'.base64_encode($currentUrl),'location'); 
           } 
           $userId = $userStatus[0]['userid']; 
           #Region Neha Ends 

           $data['validateuser'] = $userStatus; 
           $data['prodId'] = 1040; 
           $data['headerTabs'] = $cmsUserInfo['headerTabs']; 
           $data['myProducts'] = $cmsUserInfo['myProducts']; 
           $data['suggestorPageName'] = 'CMS_University_Admission'; 


           $board_id = 1; 
           $appId = 12; 
           $this->load->view('enterprise/universityAdmissionCMS',$data); 
           
        } 
        
    function getAdmissionInfoDetails(){
        global $forceListingWriteHandle;
        $forceListingWriteHandle = true;
        $this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();

        $this->listingCommonLib = $this->load->library("listingCommon/ListingCommonLib");

       $cmsUserInfo = $this->cmsUserValidation();
       $userId = $cmsUserInfo['userid']; 

       $listingId = (isset($_POST['listingId']))?$this->input->post('listingId'):0;
       $dataType = (isset($_POST['dataType']))?$this->input->post('dataType'):'admission_info';  
       $isAjaxCall = (isset($_POST['isAjaxCall']))?$this->input->post('isAjaxCall'):false; 
       $status = (isset($_POST['status']))?$this->input->post('status'):false; 
       $description = (isset($_POST['description']))?$this->input->post('description'):''; 
       $updatePostedDate = (isset($_POST['updatePostedDate']))?$this->input->post('updatePostedDate'):false; 
       $defaultUpdatedDate = (isset($_POST['defaultUpdatedDate']))?$this->input->post('defaultUpdatedDate'): false;
       
       $pageH1 = (isset($_POST['pageH1']))?$this->input->post('pageH1'):'';
       $pageH1 = str_replace('&lt;','<',$pageH1);
       $pageH1 = str_replace('&gt;','>',$pageH1);

       $pageTitle = (isset($_POST['pageTitle']))?$this->input->post('pageTitle'):'';
       $pageTitle = str_replace('&lt;','<',$pageTitle);
       $pageTitle = str_replace('&gt;','>',$pageTitle);

       $pageDescription = (isset($_POST['pageDescription']))?$this->input->post('pageDescription'):'';
       $pageDescription = str_replace('&lt;','<',$pageDescription);
       $pageDescription = str_replace('&gt;','>',$pageDescription);
    

       //_P($dataType); die;
       if($listingId>0)
       { 
            $listingObj=$this->instituteRepo->find($listingId);
            $listingType=$listingObj->getType();
            $listingName=$listingObj->getName();
            $this->load->model('nationalInstitute/institutepostingmodel'); 
            $institutemodel = new institutepostingmodel(); 
            //$universityData = $institutemodel->checkWhetherUniversityExists($universityId);
               
             if(empty($listingType)){
                  echo '<p style="margin-top:10px">University or Institute does\'nt exist</p>';  
                  return;
            }
            else{
                if($isAjaxCall){ 
                     $admissionDraft = $institutemodel->getUniversityAdmissionDetails($listingId,'draft',$listingType,$dataType); 
                     if(!empty($admissionDraft)){
                         $data['admissionData'] = $admissionDraft;
                     }else{
                         $admissionLive = $institutemodel->getUniversityAdmissionDetails($listingId,'live',$listingType,$dataType);
                         $data['admissionData'] = $admissionLive;
                     }
                    $data['listingId']=$listingId;
                    $data['listingName'] =$listingName; 

                    echo $this->load->view('enterprise/AdmissionInfoTuple',$data); 
                }else{ 
                    $data['listing_id'] = $listingId; 
                    $data['listing_type'] = $listingType; 
                     
                    $data['description_type'] = $dataType; 
                    $data['status'] = $status; 
                    if($updatePostedDate) {
                        $data['posted_on'] = date('Y-m-d-H-i-s');
                    }
                    else if(!empty($defaultUpdatedDate)) {
                        $data['posted_on'] = $defaultUpdatedDate;
                    }

                    if($pageH1) {

                        $data['page_h1'] = $pageH1;
                    }
                    if($pageTitle) {
                        $data['Page_title'] = $pageTitle;
                    }
                    if($pageDescription) {
                        $data['page_description'] = $pageDescription;
                    }
                    if($description){
                        $data['description'] = $description;
                    }
                    
                    $data['updated_by'] = $userId; 
                    $heading = explode('_',$dataType);

                    if($status == 'draft'){ 
                        $fromStatus = array('draft'); 
                        $toStatus = 'history'; 
                        $msg = ucfirst($heading[0]).' information saved successfully'; 
                    }else if($status == 'live'){ 
                        $fromStatus = array('draft','live'); 
                        $toStatus = 'history'; 
                        $msg = ucfirst($heading[0]). ' information posted successfully'; 
                    }else{ 
                        $fromStatus = array('draft','live'); 
                        $toStatus = 'deleted'; 
                        $msg = ucfirst($heading[0]).' information deleted successfully'; 
                    } 
                    $institutemodel->insertUniversityAdmissionDetails($data,$fromStatus,$toStatus,$dataType); 

                    if($dataType == 'admission_info'){
                        $this->listingCommonLib->createTocForContent($listingId, "admission");
                    }

                    // invalidate institute hierarchy cache
                     $this->nationalinstitutecache = $this->load->library('nationalInstitute/cache/NationalInstituteCache');
                     $this->nationalinstitutecache->removeInstitutesCache(array($listingId));
                     $shikshamodel = $this->load->model("common/shikshamodel");
                     $arr = array("cache_type" => "htmlpage", "entity_type" => "institute", "entity_id" => $listingId, "cache_key_identifier" => "");
                     $shikshamodel->insertCachePurgingQueue($arr);
                     echo $msg; 
              }
            }     
       }
       else
       { 
           echo '<p style="margin-top:10px">University or Institute does\'nt exist</p>'; 
       } 
    } 

    function addArticleInterlinkingHTML() 
    { 
          $cmsUserInfo = $this->cmsUserValidation(); 

           // show this tab to CMS users only 
           if($cmsUserInfo['usergroup']!='cms'){ 
               header("location:/enterprise/Enterprise/disallowedAccess"); 
               exit(); 
           } 
           $userid = $cmsUserInfo['userid']; 
           $usergroup = $cmsUserInfo['usergroup']; 
           $thisUrl = $cmsUserInfo['thisUrl']; 
           $validity = $cmsUserInfo['validity']; 
           $this->init(); 
           $userStatus = $this->checkUserValidation(); 
           if((!is_array($userStatus)) && ($userStatus == "false")) 
           { 
               $currentUrl = site_url('enterprise/Enterprise/addArticleInterlinkingHTML'); 
               redirect('user/login/userlogin/'.base64_encode($currentUrl),'location'); 
           } 
           $userId = $userStatus[0]['userid']; 
           #Region Neha Ends 

           $data['validateuser'] = $userStatus; 
           $data['prodId'] = 1043; 
           $data['headerTabs'] = $cmsUserInfo['headerTabs']; 
           $data['myProducts'] = $cmsUserInfo['myProducts']; 
           $data['suggestorPageName'] = 'CMS_Article_Interlinking_HTML'; 


           $board_id = 1; 
           $appId = 12; 
           $this->load->view('enterprise/articleInterlinkingCMS',$data); 
           
        } 
        
    function getArticleInterlinkingHTMLDetails(){ 
       $cmsUserInfo = $this->cmsUserValidation();
       $userId = $cmsUserInfo['userid']; 

       $articleType = (isset($_POST['articleType']))?$this->input->post('articleType'):'boards';  
       $isAjaxCall = (isset($_POST['isAjaxCall']))?$this->input->post('isAjaxCall'):false;

	$articlemodel = $this->load->model('article/articlenewmodel');

       if($articleType == 'boards' || $articleType == 'coursesAfter12th')
       { 
            if($isAjaxCall){
                     $html = $articlemodel->getArticleInterlinkingHTML($articleType);
                     if(!empty($html)){
                         $data['html'] = $html['interlinkingHTML'];
                         $data['ampHTML'] = $html['ampHTML'];
                         $data['ampCSS'] = $html['ampCSS'];
                     }else{
                         $data['html'] = "";
                         $data['ampHTML'] = "";
                         $data['ampCSS'] = "";
                     }
                    $data['articleType']=$articleType;
                    echo $this->load->view('enterprise/ArticleInfoTuple',$data);
            }else{
                    $description = (isset($_POST['description']))?$this->input->post('description'):'';
                    $data['ampCSS'] = (isset($_POST['ampCSS']))?$this->input->post('ampCSS'):'';
                    $data['ampHTML'] = (isset($_POST['ampHTML']))?$this->input->post('ampHTML'):'';
                    $data['articleType'] = $articleType;
                    $data['description'] = $description;
                    $data['status'] = 'live';
                    $articlemodel->insertArticleInterlinkingHTML($data);
                    $msg = 'Article Interlinking Widget HTML saved successfully';
                    echo $msg;
            }
       }
       else
       { 
           echo '<p style="margin-top:10px">No article exists</p>'; 
       } 
    } 

    function addFooterLinks()
    {
          $cmsUserInfo = $this->cmsUserValidation();

           // show this tab to CMS users only
           if($cmsUserInfo['usergroup']!='cms'){
               header("location:/enterprise/Enterprise/disallowedAccess");
               exit();
           }
           $userid = $cmsUserInfo['userid'];
           $usergroup = $cmsUserInfo['usergroup'];
           $thisUrl = $cmsUserInfo['thisUrl'];
           $validity = $cmsUserInfo['validity'];
           $this->init();
           $userStatus = $this->checkUserValidation();
           if((!is_array($userStatus)) && ($userStatus == "false"))
           {
               $currentUrl = site_url('enterprise/Enterprise/addFooterLinks');
               redirect('user/login/userlogin/'.base64_encode($currentUrl),'location');
           }
           $userId = $userStatus[0]['userid'];

            //Fetch the Current Links
            $articlemodel = $this->load->model('article/articlenewmodel');
            $data['footerLinks'] = $articlemodel->getFooterLinks();
            
           $data['validateuser'] = $userStatus;
           $data['prodId'] = 1044;
           $data['headerTabs'] = $cmsUserInfo['headerTabs'];
           $data['myProducts'] = $cmsUserInfo['myProducts'];
           $data['suggestorPageName'] = 'CMS_Article_Interlinking_HTML';


           $board_id = 1;
           $appId = 12;
           
           $this->load->view('enterprise/footerLinksCMS',$data);

        }



    function storeFooterLinks(){
       $cmsUserInfo = $this->cmsUserValidation();
       $userId = $cmsUserInfo['userid'];

       $id = (isset($_POST['id']))?$this->input->post('id'):0;
       $name = (isset($_POST['name']))?$this->input->post('name'):'';
       $URL = (isset($_POST['URL']))?$this->input->post('URL'):'';
       $action = (isset($_POST['action']))?$this->input->post('action'):'';

        $articlemodel = $this->load->model('article/articlenewmodel');

       if($action == 'add' || $action == 'edit')
       {
            $articlemodel->updateFooterLinks($id, $name, $URL);
            if($id == 0){
                $msg = 'Link added successfully.';
            }
            else{
                $msg = 'Link updated successfully.';            
            }
            echo $msg;
       }
       else if($action == 'delete')
       {
            $articlemodel->deleteFooterLinks($id);
            $msg = 'Link deleted successfully.';            
            echo $msg;           
       }
    }

    function test() {
        _p($_SERVER);
    }
        
}
?>
