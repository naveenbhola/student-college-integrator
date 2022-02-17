<?php 

/*

Copyright 2007 Info Edge India Ltd

$Rev::               $:  Revision of last commit
$Author: ankurg $:  Author of last commit
$Date: 2010-08-18 08:27:32 $:  Date of last commit

Alerts controller makes call to server using XML RPC calls.

$Id: Alerts.php,v 1.30 2010-08-18 08:27:32 ankurg Exp $: 

*/
class Alerts extends MX_Controller {


	function init(){
		$this->load->helper(array('url','form','image'));	
		$this->load->library(array('miscelleneous','alerts_client','category_list_client','ajax','listing_client','register_client'));
		
		$this->userStatus = $this->checkUserValidation();
	}
		
	function alertsHome($appId)
	{
		$this->init();	
		$appId = 12;
		/*if((!is_array($this->userStatus)) && ($this->userStatus == "false"))
		{
			redirect('/','location');	
	        	exit();
		}
		else */
		if(is_array($this->userStatus) && ($this->userStatus[0]['quicksignuser'] == 1))
		{			
			$base64url = base64_encode(site_url('alerts/Alerts/alertsHome').'/12');
			$url = '/user/Userregistration/index/'.$base64url.'/1';
            		header('Location:' .$url);
			exit();	
		}
		$userId = (is_array($this->userStatus)&&(isset($this->userStatus[0]['userid'])))?$this->userStatus[0]['userid']:0;
		$data =array();
		$noOfAlerts = 5;
		$categoryClient = new Category_list_client();
		$alertClient = new Alerts_client();
		$registerClient = new register_client();
		
		$userDetails = $registerClient->userdetail($appId,$userId);
		$retArray = $alertClient->getUserAlerts($appId,$userId);	
		$userAlerts = array();
		if(is_array($retArray) && (count($retArray) >0))
		{
			foreach($retArray as $temp)
			{
			  $key = $temp['product_name'];	
			  if(!isset($userAlerts[$key]))
			  {
				$userAlerts[$key] = array();
			  } 	
			  array_push($userAlerts[$key],$temp);
			}
		}
		

		/*code for categories start here */
		$categoryList = $categoryClient->getCategoryTree($appId);
		$this->getCategoryTreeArray($category, $categoryList,0,'Root');
		$countryList = $categoryClient->getCountries($appId); // in controller
		$catTree = json_encode($category);
		/*code for categories ends here */
		
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
		
		$email = '';
		$mobile = '';	
		if(isset($userDetails[0]['email']))
		{	
		$email = $userDetails[0]['email'];
		$mobile = $userDetails[0]['mobile'];	
		}
		$data['email'] = $email;
		$data['mobile'] = $mobile;	
		$data['countryList'] = $countryList;
		$data['appId'] = $appId;
		$data['multipleCategories'] = array();
		$data['categoryList'] = $categoryList;
		$data['category_tree'] = $catTree;
		$data['noOfAlerts'] = $noOfAlerts;
		$data['userAlerts'] = $userAlerts;
		$data['userId'] = $userId;
		$data['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
		$data['validateuser'] = $this->userStatus;
		
		$this->load->view('alerts/alertsHome',$data);			
	}
	
	/* Functions for multiple categories */
function getFullPath($appId =12) {
	$this->init();
	$appId = 12;	
	$ListingClientObj = new Listing_client();	
	$response = $ListingClientObj->getFullPath($appId);				
	$leafCatArr = $this->getLeafPathIds($response);
	return $leafCatArr;
}

function getLeafPathIds($categoryPathArray) {
	$numOfLeaves = count($categoryPathArray);
	$resultArr = array();
	for($i = 0; $i < $numOfLeaves ; $i++){
		$resultArr[$i]['path'] = $categoryPathArray[$i]['name'];
		if(isset($categoryPathArray[$i][3]) && ($categoryPathArray[$i][3] != '')){
			$resultArr[$i]['id'] = $categoryPathArray[$i][3];
		}
		elseif($categoryPathArray[$i][2] != ''){
			$resultArr[$i]['id'] = $categoryPathArray[$i][2];
		}
	}
	return $resultArr;
}
	
	
function createUpdateAlert($appId)
{
		$this->init();	
		$appId = 12;	
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		$ListingClientObj = new Listing_client();	
		$registerClient = new Register_client();
		$filterId = "-1";
		if($userId != '')
		{
			$loggedInUserMobile = $this->input->post('loggedInUserMobile');
			if(isset($loggedInUserMobile))
			{
				$registerClient->updateUserAttribute($appId,$userId,'mobile',$loggedInUserMobile);
			}
			$alertValueId = "-1";
			$productId = $this->input->post('productId');
			$alertId = $this->input->post('alertId');
			$frequency = $this->input->post('frequency');
			$mail = 'on';
			$sms = isset($_REQUEST['smsCheck'])?'on':'off';
			$im = isset($_REQUEST['imCheck'])?'on':'off';
			$alertClient = new Alerts_client();
			if($alertId != '')  //Loop for update.
			{		
				$categoryId = $this->input->post('catgory');
				$alertName = "";
				$alertValueId = "";
				$updateRes = $alertClient->updateAlert($appId,$alertId,$userId,$frequency,$mail,$sms,$im);
				echo json_encode($updateRes);	
			}
			else
			{
				$productName = $this->input->post('productName');
				$categoryCrumb = $this->input->post('categoryCrumb');
				$alertType = $this->input->post('alertType');
				
				$alertName = "";
				
				if(isset($_REQUEST['institute']))
				{
					$instituteName = $this->input->post('instituteName');
					$alertName = $productName.'-'.$instituteName;
					$alertValueId = $this->input->post('institute'); // This is collge Id.	
				}	
				switch($alertType)
				{
					case 'byCountry':
							$countryName = $this->input->post('countryName');
							$alertName = $productName.'-'.$countryName;
							$alertValueId = $this->input->post('country'); // This is country Id.	
							break;
					case 'byCategory':
							$alertName = (!empty($categoryCrumb))?$productName.'-'.$categoryCrumb:'';
							$alertValueId = $this->input->post('category'); // This is category Id.	
							break;
					case 'byCollege':
							$instituteName = $this->input->post('instituteName');
							$alertValueId = $this->input->post('institute'); // This is category Id.	
							$alertName = $productName.'-'.$instituteName;
							break; 	
					case 'byLocation':	
							$countryId = $this->input->post('country'); //country id
							$filterId = $this->input->post('city'); //city id
							if(!(is_numeric($filterId)))
							{
								$requestArray = array();
								$requestArray['country_id'] = $countryId;
								$requestArray['city_name'] = trim($filterId);
								$cityId = $ListingClientObj->insertCity($appId,$requestArray);
								$filterId = $cityId;	
							}
							$locationCrumb = $this->input->post('locationCrumb');
							$alertName = $productName.'-'.$locationCrumb;
							$alertValueId = $filterId;
							break; 	
				}
				
				$state = "on";
				//echo $appId." ".$productId." ".$userId." ".$alertName." ".$alertValueId." ".$frequency." ".$alertType." ".$mail." ".$sms." ".$im." ".$state;
				
				$creationRes = $alertClient->createAlert($appId,$productId,$userId,$alertName,$alertValueId,$frequency,$alertType,$mail,$sms,$im,$state);	 	
				echo json_encode($creationRes);	
			}
		}
		else
		{
			$logOut =  array(
                        		'result'=>4,
                        		'error_msg'=>'You are logged out of system.Please login to set an alert.');
			echo json_encode($logOut);
		}
		
	}
	
	function getProductAlerts($appId,$productId,$productName,$noOfAlerts)
	{
		$this->init();
		$appId = 12;	
		$productAlerts = array();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		if($userId != '')
		{
			$alertClient = new Alerts_client();
			$productAlerts = $alertClient->getProductAlerts($appId,$userId,$productId);
			$formNumber = 1;
			if(($productId == 4) || ($productId == 5) || ($productId == 8))
			 $formNumber = 2;
			elseif($productId == 6)
			 $formNumber = 3;
			elseif($productId == 7)
			 $formNumber = 4;
			
		}
		$data['productAlerts'] = $productAlerts;
		$data['noOfAlerts'] = $noOfAlerts;
		$data['productId'] = $productId;
		$data['appId'] = $appId;
		$data['userId'] = $userId;
		$data['productName'] = $productName;
		$productContainer = $productName.'Div';
		$data['productContainer'] = $productContainer;
		$data['formNumber'] = $formNumber;
		$this->load->view('alerts/productAlerts',$data);		
	}
	
	function getAlert($appId,$alertId)
	{
		$this->init();
		$appId = 12;	
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		$countryId = '';	
		$response = array();
		$alertDetails = array();
		$alertClient = new Alerts_client();
		$listingClient = new Listing_client();
		$alertDetails = $alertClient->getAlert($appId,$alertId,$userId);
		if($alertDetails[0]['filter_id']!='')
		{
		 $countryIdArr = $listingClient->getCountryForCity($appId,$alertDetails[0]['filter_id']);
		 $countryId = $countryIdArr[0]['countryId'];	
		}
		
		$response['alertDetails'] = $alertDetails;
		$response['countryId'] = $countryId;
		echo json_encode($response);	
	}
	
	function getWidgetAlert($productId,$alertType,$alertValueId,$filterId='')
	{
		$this->init();
		$appId = 12;
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		$widgetAlert = array();
		$widgetAlert['loggedIn'] = 0;
		if($userId != '')
		{
			$alertClient = new Alerts_client();
			$widgetAlert = $alertClient->getWidgetAlert($appId,$userId,$productId,$alertType,$alertValueId,$filterId);
			$widgetAlert['loggedIn'] = 1;
		}
		echo json_encode($widgetAlert);
	}
	
	function deleteAlert($appId,$alertId,$productId,$productName,$noOfAlerts)
	{
		$this->init();
		$appId = 12;	
		$data = array();
		$userId = $this->userStatus[0]['userid'];
		$alertClient = new Alerts_client();
		$deleteAlerts = $alertClient->deleteAlert($appId,$alertId,$userId);
		$productAlerts = $alertClient->getProductAlerts($appId,$userId,$productId);
		$formNumber = 1;
		if(($productId == 4) || ($productId == 5) || ($productId == 8))
		 $formNumber = 2;
		elseif($productId == 6)
		 $formNumber = 3;
		elseif($productId == 7)
		 $formNumber = 4;
		$data['formNumber'] = $formNumber;	
		$deleteRes = $deleteAlerts['result'];
		$data['productAlerts'] = $productAlerts;
		$data['noOfAlerts'] = $noOfAlerts;
		$data['productId'] = $productId;
		$data['appId'] = $appId;
		$data['userId'] = $userId;
		$data['productName'] = $productName;
		$productContainer = $productName.'Div';
		$data['productContainer'] = $productContainer;
		$this->load->view('alerts/productAlerts',$data);	
	}
	
	function updateState($alertId,$state)
	{
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		$appId = 12;
		$noOfAlerts = 5;
		$updateRes = array();
		$updateRes['loggedIn'] = 0;
		if($userId != '')
		{
			$alertClient = new Alerts_client();
			$updateRes = $alertClient->updateState($appId,$alertId,$userId,$state);
			$updateRes['loggedIn'] = 1;
		}
		echo json_encode($updateRes);
	}	
	
	function setCommentAlert($alertValueId,$alertName,$filterId='')
	{
		$this->init();
		$userId = isset($this->userStatus[0]['userid'])?$this->userStatus[0]['userid']:'';
		$appId = 12;
		if($alertName == -1)
		 $alertName = '';
		$widget = array();
		$widget['loggedIn'] = 0;		
		
		if($userId != '')
		{
			$alertClient = new Alerts_client();
			$widget = $alertClient->createWidgetAlert($appId,$userId,8,'byComment',$alertValueId,$alertName,$filterId);
			$widget['loggedIn'] = 1;	
			if(isset($widget['alert_id']))
			{
			   $widget['state'] = 'on';
			}	
		}
		echo json_encode($widget);
	}
	
	function setAuthorAlert($alertValueId,$alertName,$filterId='')
	{
		$this->init();
		$appId = 12;
		$userId = $this->userStatus[0]['userid'];
		$alertClient = new Alerts_client();
		$widget = $alertClient->createWidgetAlert($appId,$userId,9,'byAuthor',$alertValueId,$alertName,$filterId);
		if(isset($widget['alertId']))
		{
		   $widget['state'] = 'on';
		}	
		echo json_encode($widget);
	}
	
	function sendExternalQueueAdd()
	{
		$this->init();
		$alertClient = new Alerts_client();
		$names = $this->input->post('names');
		if(isset($names))
		{
			$fromAddress = ADMIN_EMAIL;
			$toAddress = $this->input->post('names');
			$subject = $this->input->post('subject');
			$content = $this->input->post('body');
		}
		$contentType = 'html';
		if(isset($_POST['contentType'])){
			$contentType = $this->input->post('contentType');
		}
		$response=$alertClient->externalQueueAdd("12",$fromAddress,$toAddress,$subject,$content,$contentType);
		echo "true"; 
	}

	function externalQueueAdd($fromAddress,$toAddress,$subject,$content)
	{
		$this->init();
		$alertClient = new Alerts_client();
		$response=$alertClient->externalQueueAdd("12",$fromAddress,$toAddress,$subject,$content);
		echo "<pre>";
		echo $response;
		echo "</pre>";
	}

	function generateDigestEmail($appId,$noOfDays=-1,$network=1,$internalMail=1)
	{
		$this->init();
        $networkResponse = array();
        $mailResponse = array();
        $finalResponse = array();
        $alertClient = new Alerts_client();
        error_log_shiksha("At the client");
        if($network==1)
        {
            $networkResponse = $alertClient->getDigestNetwork($appId,$noOfDays);
        }
        if($internalMail==1)
        {
            $mailResponse = $alertClient->getDigestMail($appId,$noOfDays);
        }
        $mailRecord=-1;
        $networkRecord=-1;
        while(count($mailResponse)!=0 || count($networkResponse)!=0)
        {
            if($networkRecord==-1)
            {
                if(count($networkResponse)>0)
                {
                    $networkRecord=array_shift($networkResponse);
                }
                else
                {
                    if($mailRecord!=-1)
                    {
                        array_push($finalResponse,$mailRecord);
                    }
                    while(count($mailResponse)>0)
                    {
                        $mailRecord=array_shift($mailResponse);
                        array_push($finalResponse,$mailRecord);
                    }
                    break;
                }
            }
            if($mailRecord==-1)
            {
                if(count($mailResponse)>0)
                {
                    $mailRecord=array_shift($mailResponse);
                }
                else
                {
                    if($mailRecord!=-1)
                    {
                        array_push($finalResponse,$mailRecord);
                    }
                    while(count($mailResponse)>0)
                    {
                        $mailRecord=array_shift($mailResponse);
                        array_push($finalResponse,$mailRecord);
                    }
                    break;
                }
            }
            if($networkRecord['userid']<$mailRecord['userid'])
            {
                array_push($finalResponse,$networkRecord);
                $networkRecord=-1;
            }
            else if($networkRecord['userid']>$mailRecord['userid'])
            {
                array_push($finalResponse,$mailRecord);
                $mailRecord=-1;
            }
            else if($networkRecord['userid'] == $mailRecord['userid'])
            {
                array_push($finalResponse,array_merge($mailRecord,$networkRecord));
                $mailRecord=-1;
                $networkRecord=-1;
            }
        }
        foreach($finalResponse as $data)
        {
            $content=$this->load->view('alerts/dailyDigestMail',$data,true);
            $subject="Shiksha Activity Update";
            $response=$alertClient->externalQueueAdd("12",ADMIN_EMAIL,$data['email'],$subject,$content,"html");
        }

	}

	function performEmailCheck($appId=12,$noOfDays=1)
	{
		return false;
		$this->init();
        $finalResponse = array();
        $alertClient = new Alerts_client();
        $response = $alertClient->performEmailCheck($appId,$noOfDays);
        echo $response;
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
	
	
	function compareColleges($compareId,$hash){
		$this->init();
		$alertClient = new Alerts_client();
		$response = $alertClient->getCompareContent($appId,$compareId);
		$finalResponse = $response[0];
		
		$this->load->library('categoryList/categoryPageRequest');
		// for new URLs of MBA and B.E./B.Tech
		if( strpos($finalResponse['pageUrl'] , "-ctpg") !== FALSE )
		{
			$categoryPage = explode($_SERVER['SERVER_NAME']."/",$finalResponse['pageUrl']);
			$categoryPage = $categoryPage[1];
			$categoryPage = explode("-ctpg",$categoryPage);
			$request = new CategoryPageRequest($categoryPage[0],"RNRURL");
		}
		else
		{
			$categoryPage = explode("-categorypage-",$finalResponse['pageUrl']);
			$request = new CategoryPageRequest($categoryPage[1]);
		}
		
		$newHash = md5("compare-".$finalResponse['randomkey'].$compareId.$finalResponse['userId']);
		if($newHash == $hash){
			$value = $finalResponse['email'].'|'.$finalResponse['password'];
			setcookie('user','',time() - 2592000 ,'/',COOKIEDOMAIN);
			setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
			setcookie('compare-'.$request->getPageKey(),$finalResponse['userCookie'],0,'/',COOKIEDOMAIN);
			setCookie("comparelayer".$request->getPageKey(),1,0,'/',COOKIEDOMAIN);
			setCookie("userCityPreference","1:1:2",0,'/',COOKIEDOMAIN);
			header("location:".$finalResponse['pageUrl']);
			exit;
		}else{
			echo "Got You hacker!!";
		}
	}
	
	function emailListing($institute_id,$course_id = 0,$currentLocationId){
		$this->load->library('Alerts_client');
		$AlertClientObj = new Alerts_client();
		$validateuser = $this->checkUserValidation();
		$validateuser = $validateuser[0];
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$this->courseRepository = $listingBuilder->getCourseRepository();
		$institute = $this->instituteRepository->find($institute_id);
		$listing = $institute;
		if($course_id){
			$course = $this->courseRepository->find($course_id);
			$listing = $course;
		}
		$locations = $listing->getLocations();
		$currentLocation = $listing->getMainLocation();
		foreach($locations as $location){
			if($currentLocationId == $location->getLocationId()){
				$currentLocation = $location; 
			}
		}
		$email = explode("|",$validateuser['cookiestr']);
		$userEmail = $email[0];
		$fromAddress=ADMIN_EMAIL;
		$subject = "Details of ".html_escape($institute->getName());
		$contactDetails = Modules::run('listing/ListingPageWidgets/contactDetails',$institute,$course,$currentLocation,"no",false);
		$content = "Dear ".$validateuser['firstname'].",<br/>".
				   "You have requested details about <a href='".$institute->getURL()."'>".html_escape($institute->getName())."</a><br/>";
		$content .= $contactDetails."<br/>One of our Executives will get in touch with you shortly.";
		$data['usernameemail'] = $userEmail;
		$data['content'] = $content;
		$content = $this->load->view('user/PasswordChangeMail',$data,true);
		echo $content;
		$alertResponse = $AlertClientObj->externalQueueAdd(12,$fromAddress,$userEmail,$subject,$content,"html");
	}
	
	function smsListing($institute_id,$course_id = 0,$currentLocationId){
		$this->load->library('Alerts_client');
		$AlertClientObj = new Alerts_client();
		$validateuser = $this->checkUserValidation();
		$validateuser = $validateuser[0];
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$this->instituteRepository = $listingBuilder->getInstituteRepository();
		$this->courseRepository = $listingBuilder->getCourseRepository();
		$institute = $this->instituteRepository->find($institute_id);
		$listing = $institute;
		if($course_id){
			$course = $this->courseRepository->find($course_id);
			$listing = $course;
		}
		$locations = $listing->getLocations();
		$currentLocation = $listing->getMainLocation();
		foreach($locations as $location){
			if($currentLocationId == $location->getLocationId()){
				$currentLocation = $location; 
			}
		}
		$toSms = $validateuser['mobile'];
		$userid =  $validateuser['userid'];
		$content = "";
		$content .= "Dear ".preg_replace('/\s+?(\S+)?$/', '', substr($validateuser['firstname'], 0, 15)).", The requested details are - ".
		
		
		$contactDetail = $currentLocation->getContactDetail();
		$number = strstr($contactDetail->getContactNumbers(), ',', true);
		$email = $contactDetail->getContactEmail();
		$length = 50 - strlen($numbers.$email);
		$listingTitle = preg_replace("/[^a-z0-9 ]+/i", "",$listing->getName());
		if(strlen($listingTitle) > $length){
			$listingTitle = substr($listingTitle, 0, $length-3)."...";
		}
		echo strlen($listingTitle.", ".$email.", ".$number);
		$content .= $listingTitle.", ".$email.", ".$number;
		var_dump($content);
	    $alertResponse = $AlertClientObj->addSmsQueueRecord(12,$toSms,$content,$userid);
	}
	
	
    function weeklyResponseLeadReport()
	{	
		return;
		$cityList = '10224,10223,30,912,74,151,64,84,278,87,1616,95,702,106,109,63,67,130,161';
		$stateList = '126,120,121,100,105';
		$expcityList = explode(",", $cityList);
		$expstateList = explode(",", $stateList);

        $this->load->library('dbLibCommon');
        $this->dbLibObj = DbLibCommon::getInstance('Listing');
        $dbHandle = $this->dbLibObj->getReadHandle();
        $startDate = date('Y-m-d', strtotime('-7 day'));
        $endDate = date('Y-m-d', strtotime('today'));
        $endDate1 = date('Y-m-d', strtotime('-1 day'));
        $data = array();

        $sql = "select count(1) numRegistrations from tuser where usercreationdate between ? and ?";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data['Total Registrations'] = $row['numRegistrations'];
        }
        
        $sql = "select count(distinct up.UserId) numLeads from tUserPref up, tuserflag uf where up.UserId = uf.userid
            and uf.mobileverified = '1' and hardbounce = '0' and softbounce = '0'
            and abused = '0' and ownershipchallenged = '0'
            and isLDBUser = 'YES' and submitDate between ? and ?";
			
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data['Total Leads'] = $row['numLeads'];
        }
        
        $sql = "select count(distinct u.userid) numPaidRegistrations from tuser u, tusersourceInfo us where u.userid = us.userid
            and (us.referer like '%marketing/Marketing%' or us.referer like '%mmp/templateForm%' or us.referer like '%public/mmp%')
            and usercreationdate between ? and ?";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data['Total MarketingPage Registrations'] = $row['numPaidRegistrations'];
        }
        
        $sql = "select count(1) totalNumResponses, count(distinct userid) totalRespondents from tempLMSTable
            where listing_subscription_type='paid' and submit_date between ? and ?";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data['Total Responses'] = $row['totalNumResponses'];
            $data['Total Respondents'] = $row['totalRespondents'];
            $data['Responses/Respondents'] = round($row['totalNumResponses']/$row['totalRespondents'],2);
        }
        
        $sql = "select count(1) numPaidInstitutes from listings_main where listing_type = 'institute' and status = 'live' and pack_type in (1,2,375)";
        $query = $dbHandle->query($sql);
        foreach($query->result_array() as $row){
            $data['Total Paid Institutes'] = $row['numPaidInstitutes'];
            $data['Response/Paid Institute'] = round($data['Total Responses']/$row['numPaidInstitutes'],2);
        }
        
	$sql = "select boardId,name,count(distinct id) numResponses from (select t.id,ccm.LDBCourseID,lsm.categoryid, cbt.`name`, cbt.`boardId`
        from tempLMSTable t,  clientCourseToLDBCourseMapping ccm, LDBCoursesToSubcategoryMapping lsm,
        categoryBoardTable cbt where lsm.categoryid = cbt.boardid
        and  ccm.ldbcourseid = lsm.ldbcourseid and  ccm.status = 'live'
        and lsm.status = 'live' and clientCourseID = t.listing_type_id
        and t.listing_type = 'course' and t.submit_date between ? and ? 
        and t.listing_subscription_type='paid'   
        group by t.id, clientCourseID) x group by categoryid";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data[$row['boardId'].' '.$row['name'].' Responses'] = $row['numResponses'];
        }
        
        $sql = "select boardId,name,count(distinct id) numResponses from (select t.id,ccm.sub_category_id, cbt.`name`, cbt.`boardId`
        from tempLMSTable t,  abroadCategoryPageData ccm,
        categoryBoardTable cbt where ccm.status = 'live'
        and ccm.course_id = t.listing_type_id and ccm.sub_category_id = cbt.boardid
        and t.listing_type = 'course' and t.submit_date between ? and ? 
        and t.listing_subscription_type='paid'   
        group by t.id, ccm.course_id) x group by sub_category_id";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data[$row['boardId'].' '.$row['name'].' Responses (Abroad)'] = $row['numResponses'];
        }

        $sql = "select count(distinct id) numResponses from (select t.id,ccm.LDBCourseID,lsm.categoryid, cbt.`name`
        from tempLMSTable t,  clientCourseToLDBCourseMapping ccm, LDBCoursesToSubcategoryMapping lsm,
        categoryBoardTable cbt where lsm.categoryid = cbt.boardid
        and  ccm.ldbcourseid = lsm.ldbcourseid and  ccm.status = 'live'
        and lsm.status = 'live' and clientCourseID = t.listing_type_id
        and t.listing_subscription_type='paid'                                                
        and t.listing_type = 'course' and t.submit_date between ? and ?
        group by t.id, clientCourseID) x
        where categoryid in (
            select boardId from categoryBoardTable where parentid = 10
            and boardId not in (23,24,56,28,30,99,100,89,97,98)
            )";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data['Remaining IT subcat Responses'] = $row['numResponses'];
        }
        
        $sql = "select city_name, count(distinct uf.userid) numLeads from tuserflag uf, tUserPref up,
        tUserLocationPref ulp, countryCityTable cct where uf.userid = up.userid
        and up.userid = ulp.userid and up.submitdate between ? and ?
        and mobileverified = '1' and abused = '0' and ownershipchallenged = '0'
        and hardbounce = '0' and softbounce = '0' and isLDBUser = 'YES' and ulp.cityid = cct.city_id
        and ulp.cityId in (?)
        group by ulp.cityid";
        $query = $dbHandle->query($sql, array($startDate, $endDate, $expcityList));
        foreach($query->result_array() as $row){
            $data[$row['city_name'].' Leads'] = $row['numLeads'];
        }
        
        $sql = "select state_name, count(distinct uf.userid) numLeads from tuserflag uf, tUserPref up,
        tUserLocationPref ulp, stateTable st where uf.userid = up.userid and up.userid = ulp.userid
        and up.submitdate between ? and ? and mobileverified = '1' and abused = '0'
        and ownershipchallenged = '0' and hardbounce = '0' and softbounce = '0' and isLDBUser = 'YES'
        and ulp.stateid = st.state_id and ulp.cityId not in (?)
        and ulp.stateid in (?) group by ulp.stateid";
        $query = $dbHandle->query($sql, array($startDate, $endDate, $expcityList, $expstateList));
        foreach($query->result_array() as $row){
            $data[$row['state_name'].'(Rest) Leads'] = $row['numLeads'];
        }
        
        $sql = "select cct.city_name,count(distinct l.listing_type_id) numPaidInstitutes from listings_main l, institute_location_table ilt, countryCityTable cct
        where l.listing_type = 'institute' and listing_type_id = institute_id
        and ilt.city_id = cct.city_id
        and l.status = 'live' and ilt.status = 'live' and l.status = 'live'
        and l.version = ilt.version and l.pack_type in (1,2,375)
        and ilt.city_id in (?)
        group by ilt.city_id";
        $query = $dbHandle->query($sql, array($expcityList));
        foreach($query->result_array() as $row){
            $data[$row['city_name'].' Paid Institutes'] = $row['numPaidInstitutes'];
        }
        
        $sql = "select st.state_name,count(distinct l.listing_type_id) numPaidInstitutes from listings_main l, institute_location_table ilt,
        countryCityTable cct, stateTable st
        where l.listing_type = 'institute' and listing_type_id = institute_id
        and ilt.city_id = cct.city_id
        and cct.state_id = st.state_id 
        and l.status = 'live' and ilt.status = 'live' and l.status = 'live'
        and l.version = ilt.version and l.pack_type in (1,2,375)
        and ilt.city_id not in (?)
        and cct.state_id in (?)
        group by cct.state_id";
        $query = $dbHandle->query($sql, array($expcityList, $expstateList));
        foreach($query->result_array() as $row){
            $data[$row['state_name'].'(Rest) Paid Institutes'] = $row['numPaidInstitutes'];
        }
        
		$sql = "select ct.name,count(distinct l.listing_type_id) numPaidInstitutes from listings_main l, institute_location_table ilt, countryTable ct
        where l.listing_type = 'institute' and listing_type_id = institute_id
        and ilt.country_id = ct.countryId
        and l.status = 'live' and ilt.status = 'live' and l.status = 'live'
        and l.version = ilt.version and l.pack_type in (1,2,375) and ilt.country_id != 2
        group by ilt.country_id";
        $query = $dbHandle->query($sql);
        foreach($query->result_array() as $row){
            $data[$row['name'].' Paid Institutes'] = $row['numPaidInstitutes'];
        }
		
        //category-wise paid institutes
        $sql = "select lct.category_id, cbt.name, count(1) numInstitutes from listings_main l,
        listing_category_table lct, categoryBoardTable cbt where l.listing_type = 'institute'
        and l.listing_type_id = lct.listing_type_id and lct.listing_type = 'institute'
        and lct.status = 'live' and l.status = 'live' and l.pack_type in (1,2,375)
        and cbt.boardId = lct.category_id group by lct.category_id";
        $query = $dbHandle->query($sql);
        foreach($query->result_array() as $row){
            $data[$row['name'].' Paid Institutes'] = $row['numInstitutes'];
        }
        
		
//		/**
//		 * Responses made on course
//		 */ 
//        $sql = "select cct.city_name,count(distinct t.id) numResponses from tempLMSTable t, course_location_attribute cla,
//        institute_location_table ilt, countryCityTable cct
//        where t.listing_type_id = cla.course_id and t.listing_type = 'course' and cct.city_id = ilt.city_id
//        and ilt.institute_location_id = cla.institute_location_id and cla.attribute_type = 'Head Office'
//        and ilt.status = 'live' and cla.status = 'live' and submit_date between '$startDate' and '$endDate'
//        and ilt.city_id in ($cityList)
//        group by ilt.city_id";
//        $query = $dbHandle->query($sql);
//        foreach($query->result_array() as $row){
//            $data[$row['city_name'].' Responses'] = intval($row['numResponses']);
//        }
//        
//		/**
//		 * Responses made on institutes
//		 */ 
//        $sql = "select cct.city_name,count(1) numResponses from tempLMSTable t, institute_location_table ilt,
//        countryCityTable cct  where t.listing_type_id = ilt.institute_id and t.listing_type = 'institute'
//        and ilt.status = 'live' and cct.city_id = ilt.city_id and submit_date between '$startDate' and '$endDate'
//        and ilt.city_id in ($cityList)
//        group by ilt.city_id";
//        $query = $dbHandle->query($sql);
//        foreach($query->result_array() as $row){
//			if($data[$row['city_name'].' Responses']) {
//				$data[$row['city_name'].' Responses'] += intval($row['numResponses']);
//			}
//			else {
//				$data[$row['city_name'].' Responses'] = intval($row['numResponses']);
//			}
//        }

	
		$sql = "select cct.city_name,count(distinct t.id) as numResponses
		from `tempLMSTable` t,responseLocationTable r,institute_location_table ilt,countryCityTable cct
		where r.responseId = t.id
                and t.listing_subscription_type='paid' 
		and ilt.institute_location_id = r.instituteLocationId
		and ilt.city_id = cct.city_id
		and ilt.status = 'live'
		and submit_date between ? and ?
		and ilt.country_id = 2
		group by cct.city_id";
		$query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row) {
            $data[$row['city_name'].' Responses'] = $row['numResponses'];
        }
        
        $sql = "select cbt.`name`,count(1) numLeads from tUserPref up, tCourseSpecializationMapping tcsm,
        tuserflag uf, categoryBoardTable cbt where up.userid = uf.userid and uf.mobileverified = '1'
        and uf.abused = '0' and uf.ownershipchallenged = '0' and uf.hardbounce = '0'
        and uf.softbounce = '0' and tcsm.categoryid = cbt.boardid
        and up.desiredCourse = tcsm.specializationid and up.submitdate between ? and ?
        and tcsm.status = 'live' and uf.isLDBUser = 'YES'
		and (up.extraflag IS NULL OR up.extraflag =  '' OR up.extraflag = 'undecided')
        group by categoryid";
		
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data[$row['name'].' Leads'] = $row['numLeads'];
        }
        
        // studyabroad category-courselevel-wise leads
        $sql = "select `name`,categoryid, coursename, count(distinct up.userid) numLeads from tUserPref up, tuserflag uf,
        tCourseSpecializationMapping tcsm, categoryBoardTable cbt where up.userid = uf.userid and mobileverified = '1' and abused = '0'
        and ownershipchallenged = '0' and hardbounce = '0' and softbounce = '0' and isLDBUser = 'YES'
        and up.submitdate between ? and ?
        and extraflag = 'studyabroad' and scope = 'abroad' and up.desiredcourse = tcsm.specializationid
        and cbt.boardId = tcsm.categoryId
        group by categoryid, coursename";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data[$row['name'].' '.$row['coursename'].' Leads'] = $row['numLeads'];
        }
        
        //studyabroad country-wise responses
        $sql = "select ilt.country_id,`name`,count(1) numResponses from tempLMSTable t,
        course_location_attribute cla, institute_location_table ilt, countryTable ct
        where t.listing_type = 'course' and t.listing_type_id = cla.course_id
        and t.listing_subscription_type='paid'  
        and ilt.institute_location_id = cla.institute_location_id
        and cla.attribute_type = 'Head Office' and cla.status = 'live' and ilt.status = 'live'
        and submit_date between ? and ? and ilt.country_id != 2
        and ilt.country_id = ct.countryId group by ilt.country_id";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data[$row['name'].' Responses'] = intval($row['numResponses']);
        }
        
		/**
		 * Add responses made on institutes
		 */ 
		$sql = "select ilt.country_id,`name`,count(1) numResponses from tempLMSTable t,
        institute_location_table ilt, countryTable ct
        where t.listing_type = 'institute' and t.listing_type_id = ilt.institute_id
        and t.listing_subscription_type = 'paid' 
        and ilt.status = 'live'
        and submit_date between ? and ? and ilt.country_id != 2
        and ilt.country_id = ct.countryId group by ilt.country_id";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
			if($data[$row['name'].' Responses']) {
				$data[$row['name'].' Responses'] += intval($row['numResponses']);
			}
			else {
				$data[$row['name'].' Responses'] = intval($row['numResponses']);
			}
        }
		
		
        //studyabroad courselevel-wise responses
        $sql = "select if(course_level_1 like 'Under%' or course_level_1 like 'Diploma%' or course_level_1 = '', 'UG', if(course_level_1 like 'Post G%','PG','Doctorate')) courseLevel,
        count(1) numResponses from tempLMSTable t, course_location_attribute cla,
        institute_location_table ilt, course_details cd
        where t.listing_type = 'course' and t.listing_type_id = cla.course_id
        and t.listing_subscription_type = 'paid' 
        and ilt.institute_location_id = cla.institute_location_id
        and cla.attribute_type = 'Head Office' and cla.status = 'live'
        and ilt.status = 'live' and t.listing_type_id = cd.course_id
        and t.submit_date between ? and ?
        and cd.status = 'live' and ilt.country_id != 2 group by courseLevel";
        $query = $dbHandle->query($sql, array($startDate, $endDate));
        foreach($query->result_array() as $row){
            $data[$row['courseLevel'].' Responses'] = $row['numResponses'];
        }
        
        
        $report = "<table>";
        foreach($data as $key=>$count) {
            $report .= "<tr><td>$key</td><td>$count</td></tr>\n";
        }
        $report .= "</table>";
        $subject = "Lead response report for $startDate to $endDate1";

        $this->load->library("alerts_client");
        $alertClient = new Alerts_client();
        $alertClient->externalQueueAdd("12",ADMIN_EMAIL,"saurabh.gupta@shiksha.com",$subject,$report,"html");
		$alertClient->externalQueueAdd("12",ADMIN_EMAIL,"satendra.kumar@naukri.com",$subject,$report,"html");
		$alertClient->externalQueueAdd("12",ADMIN_EMAIL,"prateek.malpani@shiksha.com",$subject,$report,"html");
		$alertClient->externalQueueAdd("12",ADMIN_EMAIL,"vikas.k@shiksha.com",$subject,$report,"html");
    }

	
	function developer() {
        setcookie("shikshaDeveloper","yes",time() + (10 * 365 * 24 * 60 * 60),"/",COOKIEDOMAIN);
    }

    function noDeveloper() {
        setcookie("shikshaDeveloper","",time() - (10 * 365 * 24 * 60 * 60),"/",COOKIEDOMAIN);
    }
}
?>
