<?php 
/**
 * MyShiksha class file for User
 */

/**
 * MyShiksha class for User
 */
class MyShiksha extends MX_Controller {
	/**
	 * Field stroing the User ID
	 * @var integer
	 */
	private $userId;
	
	/**
	 * Validation  Field 
	 * @var integer
	 */
	private $validation = 1;
	private $abroadCheck = 0;
	private $validateUserData;
	/**
	 * Init F
	 */
	 private function init() {
		$this->load->helper(array('url','form','image','shikshautility'));
		$this->load->library('ajax');
		$this->load->library('myShiksha_client');	 
		if($this->validation == 1){
			$Validate = $this->checkUserValidation();
			if($Validate == "false"){
				$this->userId = '';
				if($this->abroadCheck==1){
					header('Location:'.SHIKSHA_STUDYABROAD_HOME);
					exit();
				}else{
					header('Location:/');
					exit();
				}
			} 
			else{
				if($Validate[0]['quicksignuser'] == 1)
				{			
					$base64url = base64_encode(site_url('user/MyShiksha/index').'/12');
					$url = '/user/Userregistration/index/'.$base64url.'/1';
            				header('Location:' .$url);
					exit();
				}	
				$this->userId = $Validate['0']['userid'];
				$this->displayname = $Validate['0']['displayname'];
				$this->validateUserData = $Validate;
			}
		}
	}

	/**
	 * Index function calling Init Function for initialization purposes
	 *
	 * @param integer $appID
	 */
	function index($appID=1) {
		// redirect to new profile page
		$this->_redirectToNewProfilePage();
		$this->validation = 1;
		$this->init();
		$this->showMyShikshaPage($appID, $this->userId, 'true');
	}
	
	function _redirectToNewProfilePage() {
		//if(!isMobileRequest()){
			header('Location: '.SHIKSHA_HOME.'/userprofile/edit');
			exit;
		//}
	}
	
	/**
	 * To open the user Shiksha Page
	 *
	 * @param string $displayName
	 */
	function publicProfile($displayName) {
		$this->validation = 0;
		$this->init();
		$appID = 12;
		
		$displayName = "'".addcslashes($displayName,"'\;")."'";		
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$userDetails = $registerClient->getDetailsforDisplayname($appID,$displayName);	
		$this->userId = isset($userDetails[0]['userid'])?$userDetails[0]['userid']:0;
		$redirectUrl = SHIKSHA_HOME;
		if($this->userId >0){
			$redirectUrl.= '/userprofile/'.$this->userId;
			header('Location: '.$redirectUrl);
		}else{
			header('Location: '.$redirectUrl);
		}
		exit;
		//$this->showMyShikshaPage($appID, $this->userId, 'false');
	}
 
	/**
	 * To show the user Shiksha Page
	 *
	 * @param integer $appID
	 * @param integer $userId
	 * @param boolean $editFlag
	 */
	private function showMyShikshaPage($appID, $userId, $editFlag){
		$this->init();
		$this->load->library('register_client');	 
		$displayData = array();
		$tabToSelect = '';
		$mentorshipStatus = false;
		$this->load->model('CA/mentormodel');
		$mentorModel = new MentorModel();
		$mentorInformation = $mentorModel->checkIfMentorAssignedToMentee(array($userId));
		if($mentorInformation[$userId]){
			if(isset($_COOKIE['ci_mobile']) && ($_COOKIE['ci_mobile']=='mobile')){
                                header('location:'.MENTEE_MOBILE_DASHBOARD_URL); exit;
                        }
			$mentorId = $mentorInformation[$userId]['mentorId'];
			$chatId = $mentorInformation[$userId]['chatId'];
			$this->load->helper('CA/cr');
			$this->load->model('CA/camodel');
			$caModel = new CAModel();
			$mentorshipStatus	= true;
			$caDetails = $caModel->getAllCADetails($mentorId);
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
			$courseObj = $courseRepository->find($caDetails[0]['ca']['mainEducationDetails'][0]['courseId']);
			$displayData['courseObj'] = $courseObj;
			$displayData['caDetails'] = $caDetails;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$instObj = $instituteRepository->find($caDetails[0]['ca']['mainEducationDetails'][0]['instituteId']);	
			$displayData['instObj'] = $instObj;
			$mentorSlots = $mentorModel->checkMentorSlots($mentorId);
			$formattedMentorSlots  = formatMentorSlots($mentorSlots);
			$displayData['mentorSlots'] = $formattedMentorSlots;
			$displayData['mentorId'] = $mentorId;
			$displayData['chatId'] = $chatId;
			$displayData['slotData'] = $mentorModel->checkIfMenteeBookedOrRequestSlot($userId,$mentorId);
			$displayData['scheduleData'] = $mentorModel->checkIfMenteeHasAnyScheduledChat($userId,$mentorId);
			$displayData['completedChats'] = $mentorModel->getMentorshipChatHistory($mentorId, $userId);
		}
		$displayData['mentorshipStatus'] = $mentorshipStatus;
		if($this->validation == 1)
			$tabToSelect = 'myShiksha';
		$registerClient = new Register_client();
		$educationLevel = $registerClient->EducationLevel($appID,'Working');
		$displayData['educationLevel'] = $educationLevel;
		
		$userDetails = $registerClient->userdetail($appID, $userId, 'AnA');
		$displayData['userDetails'] = $userDetails[0];
        
		$userId = $this->userId;

		$myShikshaClient = new MyShiksha_client();
		$myShikshaPreferences = $myShikshaClient->getMyShiksha($appID, $userId);
		$displayData['myShikshaPreferences'] = $myShikshaPreferences;
        
	 	$displayData['pendingRequestCount'] = $this->myPendingRequestCount($userId);
		$displayData['unreadMailCount'] = $this->getUnreadMailCount($userId);
		

		$myTopics = array();
		$myTopics = $this->getMyTopics($appID, $userId);
		$displayData['myTopics'] = $myTopics;
		error_log("okay".print_r($displayData['myTopics'],true));	
		$myEvents = array();
		$myEvents = $this->loadMyEvents($userId);
		$displayData['myEvents'] = $myEvents;
		
		$myAlerts = $this->myAlerts($appID);
		$displayData['myAlerts'] = $myAlerts;
        
		$mylisting = array();
		// $mylisting = $this->mylisting($appID, $userId);
		$displayData['mylistings'] = $mylisting; 
		
		$displayData['userFriends'] = $this->getLoggedInUserFriends();
	
		$displayData['country_list'] = $this->addCollege($appID);

		$displayData['userId'] = $userId;
		$displayData['editFlag'] = $editFlag;
		$displayData['tabToSelect'] = $tabToSelect;
		$Validate = $this->checkUserValidation();
		$loggedInUserId = isset($Validate[0]['userid'])?$Validate[0]['userid']:0;
		$displayData['validateuser'] = $Validate;
		$displayData['loggedInUserId'] = $loggedInUserId;
        	$response = $this->totalUsersCollegesinUserNet($userId);
        	$displayData['totalUserCount'] = $response[0]['users'];
        	$displayData['totalcollegeCount'] = $response[0]['colleges'];
	 	$this->load->view('user/myShiksha',$displayData);	
	}
	
	/**
	 * Function getLoggedInUserFriends
	 */
	function getLoggedInUserFriends(){
	  $userFriends = array();
	  return $userFriends;
	}
	
	/**
	* Function to update the user 'Shiksha'
	*
	* @param string $appID
	* @param integer $userId
	* @param string $component
	* @param string $displayStatus
	* @param string $position
	*
	*/
	function updateComponent($appID, $component, $displayStatus, $position) {
		$this->init();
		$userId = $this->userId;
		$myShikshaClient = new MyShiksha_client();
		$response = $myShikshaClient->updateMyShiksha($appID, $userId, $component, $displayStatus, $position);
		echo $response;
	}
	
	/**
	 * Function to get the User Videos
	 * @param integer $appID
	 */
	function myVideos($appID) {
		$userId = $this->userId;
		echo $this->getMyVideos($appID, $userId);
	}
	
	/**
	 * Function to get the Unread mail count
	 *
	 * @param integer $userId
	 * @return integer 
	 */
	function getUnreadMailCount($userId)
	{
		$this->load->library('mail_client');	
		$appId = 12;
		$mail_client = new Mail_client();
		$unreadMailCount = $mail_client->unreadMails($appId,$userId);
		return $unreadMailCount;
	}
	
	/**
	 * Function to show Pending Request count
	 * 
	 * @param integer $userId
	 * @return integer
	 */
	function myPendingRequestCount($userId)
	{
		 return 0;
	}
 
	/**
	 * Function to get the User Videos
	 * 
	 * @param integer $userId
	 * @param integer $appId
	 *
	 * @return string
	 */
	private function getMyVideos($appID, $userId) {
		$this->init();	
		$myShikshaClient = new MyShiksha_client();
		$response = $myShikshaClient->getMyVideos($appID, $userId);
		//print_r($response);
		return str_replace("\\n", "", json_encode($response));
	}
	
	/**
	 * Function to get the User Pictures
	 *
	 * @param integer $appID
	 */
	function myPictures($appID) {
		$userId = $this->userId;
		echo $this->getMyPictures($appID, $userId);
	}
	
	/**
	 * Function to get the User Pictures
	 *
	 * @param integer $appID
	 * @param integer $userId
	 */
	private function getMyPictures($appID, $userId) {
		$this->init();	
		$myShikshaClient = new MyShiksha_client();
		$response = $myShikshaClient->getMyPictures($appID, $userId);
		//print_r($response);
		return str_replace("\\n", "", json_encode($response));
	}
	
	/**
	 * Function to get the user Ratings
	 *
	 * @param integer $appID
	 */
	function myRatings($appID) {
		$userId = $this->userId;
		echo $this->getMyRatings($appID, $userId);
	}
	
	/**
	 * Function to get the user Ratings
	 *
	 * @param integer $appID
	 * @param integer $userId
	 */
	private function getMyRatings($appID, $userId) {
		/*$this->load->library('review_client');
		$categoryId = 1;
		$start = 0;
		$rows = 2;
		$countryId = 1;
		$listingType = 'course';
		$myRatings = array();
		$reviewClient = new Review_client();
		$myRatings = $reviewClient->getMyRating($appID, $categoryId, $countryId, $listingType, $userId, $start, $rows);
		//print_r($myRatings);
		return str_replace("\\n", "", json_encode($myRatings));*/
	}
	
	/**
	 * Function to get the user blogs
	 *
	 * @param integer $appID
	 */
	function myBlogs($appID) {
		$userId = $this->userId;
		echo $this->getMyBlogs($appID, $userId);
	}
	
	/**
	 * Function to get the user blogs
	 *
	 * @param integer $appID
	 * @param integer $userId
	 */
	private function getMyBlogs($appID, $userId) {
		$this->load->library('blog_client');
		$categoryId = 1;
		$start = 0;
		$rows = 2;
		$countryId = 1;
		$myBlogs = array();
		$blogClient = new Blog_client();
		$myBlogs = $blogClient->getMyBlogs($appID, $categoryId, $start, $rows, $countryId, $userId);
		$blogList = array();
		foreach($myBlogs as $myBlog){
			$myBlog['blogText'] = '';
			array_push($blogList, $myBlog);
		}
		//print_r($myBlogs);
		return str_replace("\\n", "", json_encode($blogList));
	} 
	
	/**
	 * Function to get the user Topics
	 *
	 * @param integer $appID
	 */
	function myTopics($appID) {
		$userId = $this->userId;
		echo $this->getMyTopics($appID, $userId);
	}
 
	/**
	 * Function to get the user Topics
	 *
	 * @param integer $appID
	 * @param integer $userId
	 */
	private function getMyTopics($appID, $userId) {
		$this->load->library('message_board_client');
		$categoryId = 1;
		$start = 0;
		$rows = 2;
		$countryId = 1;
		$myTopics = array();
		$msgbrdClient = new Message_board_client();
		$Result = $msgbrdClient->getMyTopics($appID,$categoryId,$userId,$start,$rows);
		$myTopics = $Result[0]['results'];
		$content = addslashes(json_encode($myTopics));
		return str_replace("\\n", "",$content);
	}
	
	/**
	 * Function to get the user Events
	 *
	 * @param integer $appID
	 * @param integer $start
	 * @param integer $rows
	 */
	function myEvents($userId,$start=0,$rows=5) {
		$reponse = array();	
		$appID = 12;	
		$reponse = $this->getMyEvents($appID,$userId,$start,$rows);
		echo json_encode($reponse);		
	}
	
	/**
	 * Function to get the user Subscibed Events
	 *
	 * @param integer $userId
	 * @param integer $start
	 * @param integer $rows
	 */
	function mySubscribedEvents($userId,$start=0,$rows=5) {
		error_log("inside mySubscribedEvents");
                $reponse = array();
                $appID = 12;
		if($userId>0){
	                $reponse = $this->getMySubscribedEvents($appID,$userId,$start,$rows);
        	        echo json_encode($reponse);
		}
		else{
			echo '';
		}
        }
	
	/**
	 * Function to load the User Events
	 *
	 * @param integer $userId
	 * @param integer $start
	 * @param integer $rows
	 */
	function loadMyEvents($userId,$start=0,$rows=5) {
		$reponse = array();	
		$appID = 12;	
		$reponse = $this->getMyEvents($appID,$userId,$start,$rows);
		$myEventsJson = str_replace("\\n", "", json_encode($reponse));
		return addslashes($myEventsJson);
	}
	
	/**
	 * Function to get the user Events
	 *
	 * @param integer $appID
	 * @param integer $userId
	 * @param integer $start
	 * @param integer $rows
	 */
	private function getMyEvents($appID, $userId,$start,$rows) {
		$this->load->library('event_cal_client');
		$categoryId = 1;
		$countryId = 1;
		$eventRes = array();
		$eventCount = 0;
		$eventsClient = new Event_cal_client();
		$myEvents = $eventsClient->getMyEvents($appID, $categoryId, $start, $rows, $countryId, $userId);
		if(is_array($myEvents) && isset($myEvents[0]['results']))
			$eventRes = $myEvents[0]['results'];
		
		if(is_array($myEvents) && isset($myEvents[0]['total']))
			$eventCount = $myEvents[0]['total'];
				
		$reponse = array('myEvents' => $eventRes,'myEventsCount' => $eventCount);	
		return $reponse;
		
	}
	
	/**
	 * Function to get the user Subscribed Events
	 *
	 * @param integer $appID
	 * @param integer $userId
	 * @param integer $start
	 * @param integer $rows
	 */
	private function getMySubscribedEvents($appID, $userId,$start,$rows) {
                $this->load->library('event_cal_client');
                $categoryId = 1;
                $countryId = 1;
                $eventRes = array();
                $eventCount = 0;
                $eventsClient = new Event_cal_client();
                $myEvents = $eventsClient->getMySubscribedEvents($appID, $categoryId, $start, $rows, $countryId, $userId);
                if(is_array($myEvents) && isset($myEvents[0]['results']))
                        $eventRes = $myEvents[0]['results'];

                if(is_array($myEvents) && isset($myEvents[0]['total']))
                        $eventCount = $myEvents[0]['total'];

                $reponse = array('myEvents' => $eventRes,'myEventsCount' => $eventCount);
                return $reponse;

        }
	
	/**
	 * Function to change password
	 */
	function changePassword(){
		$this->init();
		$appId = 12;
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$currentPassword = $this->input->post('currentPassword');
		$newPassword = $this->input->post('newPassword');
		$status = $registerClient->changePassword($appId,$this->userId,sha256($currentPassword),sha256($newPassword),$newPassword);
		if($status == 1)
		{
			$values = explode("|",$_COOKIE["user"]);
			$email = $values[0];
			$value = $email.'|'.sha256($newPassword) .'|' .$values[2];
			setcookie('user','',time() - 2592000 ,'/',COOKIEDOMAIN);
			setcookie('user',$value,time() + 2592000 ,'/',COOKIEDOMAIN);
		}		
		echo $status;
	}
	
	/**
	 * Function to update the User Attributes
	 * @param integer $appId
	 */
	function updateUser($appId) {
		$this->init();
		$userId = $this->userId;
		$attribute = $_POST['attributeName'];
		$attributeValue = $_POST['attributeValue'];
		echo $this->updateUserAttribute($appId, $userId, $attribute, $attributeValue,$this->displayname);
	}
	
	/**
	 * Function to check Availability
	 */
	function checkAvailability()
	{
		$this->init();
		$appId = 12;
		$this->load->library('Register_client');
		$registerClient = new Register_client();
		$displayName = $this->input->post('displayName');
		$available = $registerClient->checkAvailability($appId,$displayName,'displayname');	
		echo $available;
	}
	
	/**
	 * Function to update the User Attributes
	 *
	 * @param integer $appId
	 * @param integer $userId
	 * @param string $attribute
	 * @param string $attributeValue
	 * @param string $displayname
	 */
	private function updateUserAttribute($appId, $userId, $attribute, $attributeValue,$displayname){
		$this->load->library('Register_client');
		$userFieldsMap = array(
		'emailId' => 'email',
		'displayName' => 'displayname',
		'mobile' => 'mobile',
		//'newPassword' => 'password',
		'userCity' => 'city',
		'userProfession' => 'profession',
		'contactByMobile' => 'viamobile',
		'contactByEmail' => 'viaemail',
		'userImage' => 'avtarimageurl',
		'sendNewsLetterByEmail' => 'newsletteremail',
		'publishInstituteFollowing' => 'publishInstituteFollowing',
		'publishInstituteUpdates' => 'publishInstituteUpdates',
		'publishRequestEBrochure' => 'publishRequestEBrochure',
		//'publishAnaActivity' => 'publishAnaActivity',
                'publishQuestionOnFB' => 'publishQuestionOnFB',
                'publishAnswerOnFB' => 'publishAnswerOnFB',
                'publishDiscussionOnFB' => 'publishDiscussionOnFB',
                'publishAnnouncementOnFB' => 'publishAnnouncementOnFB',
		'publishBestAnswerAndLevelActivity' => 'publishBestAnswerAndLevelActivity',
		'publishArticleFollowing' => 'publishArticleFollowing'
		);
		if(!array_key_exists($attribute, $userFieldsMap)) {
			return '0';
		}
		$columnName = $userFieldsMap[$attribute];
		$registerClient = new Register_client();
		//error_log(print_r($columnName.$attributeValue,true),3,'/home/aakash/Desktop/error.log');
		return $registerClient->updateUserAttribute($appId, $userId, $columnName, $attributeValue,$displayname);
	}
	
	/**
	 * Function to display My Listings
	 * 
	 * @param integer $appID
	 * @param integer $userId
	 */
	function mylisting($appID, $userId) {
		$country_id =1;
		$category_id =1;
		$this->init();
		$this->load->library('category_list_client');

		$this->load->library('listing_client');
		$this->load->library('listingCommon');
		$displayData = array();
		$categoryClient = new Category_list_client();	 	
		$countryList = $categoryClient->getCountries($appID);
		$categoryList = $categoryClient->getCategoryTree($appID);
		$this->getCategoryTreeArray($category, $categoryList,0,'Root');
		$displayData['categoryList'] = $categoryList;
		$categoryTree = json_encode($category);
		$displayData['country_id'] = $country_id;
		$displayData['category_id'] = $category_id;
		$displayData['category_tree'] = $categoryTree;
		$displayData['country_list'] = $countryList;
		$displayData['myShiksha'] = 'true';
		$displayData['usernameL'] = $userId;
		$displayData['countOffset'] = 50;

		$listingCommonClient = new ListingCommon();	
		$displayData['mycourse'] = $listingCommonClient->getMyListings($appID,$category_id, $userId, 'course',0,$displayData['countOffset']);
		return $displayData;
	} 

	/**
	 *Controller API to get Categories in a Tree Format (parent-child)
	 */
	function getCategoryTreeArray(& $returnArray, $categoryTree, $parentId, $parentCategoryName) {
		if(is_array($categoryTree)) {
			$i=0;
			foreach($categoryTree as $categoryLeaf) {
				if($categoryLeaf['parentId'] == $parentId) {
					$returnArray[$parentCategoryName][$i++] = $categoryLeaf['categoryID'] ."<=>". $categoryLeaf['categoryName'];
					$this->getCategoryTreeArray($returnArray[$parentCategoryName], $categoryTree, $categoryLeaf['categoryID'], $categoryLeaf['categoryName']);
				}
			}
		}
		return $returnArray;
	}

	/**
	 * Function to get the user Alerts
	 *
	 * @param integer $appId
	 *
	 */
	function myAlerts($appId)
	{
		$this->init();
		$userId = $this->userId;
		$this->load->library('alerts_client');
		$this->load->library('category_list_client');	
		$this->load->library('listing_client');	
        $this->load->library('register_client');
		$data = array();
		$noOfAlerts = 1;
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
		
		$this->getCategoryTreeArray($category, $categoryList,0,'Root');
		$countryList = $categoryClient->getCountries($appId); // in controller
		$catTree = json_encode($category);
		/*code for categories ends here */
		
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
		$data['categoryList'] = $categoryList;
		$data['categoryForLeftPanel'] = json_encode($categoryForLeftPanel);
		$data['category_tree'] = $catTree;
		$data['noOfAlerts'] = $noOfAlerts;
		$data['userAlerts'] = $userAlerts;
		$data['userId'] = $userId;
		return $data;
	}
	
	/**
	 * Function to get list of countries
	 *
	 * @param integer $appId
	 * @return array
	 */
	function addCollege($appId) {
		$this->init();
		$this->load->library('category_list_client');
		$categoryClient = new Category_list_client();	 	
		$countryList = $categoryClient->getCountries($appId);
		return $countryList;
	}	
	
	
	/**
	 * Function to add college
	 *
	 * @param integer $appId
	 */
	function collegeAdd($appId){
		$this->init();
		$collegeAddRes = '';
		$captchaResult = 0;
		$result = array('captchaResult' => $captchaResult,'result' => $collegeAddRes);
		echo json_encode($result);
	}
	/**
	 * Function to upload the file
	 *
	 * @param integer $vcard
	 * @param integer $appId
	 */
 function uploadFile( $vcard = 0,$appId = 1, $isImageExist = 0){
	$this->init();
	$userId = $this->userId ;
    $imageUrl = $this->input->post('changeavtar');

    // re-index user
    if($userId){
	    $this->load->model('user/usermodel');
	    $usermodel = new usermodel();
	    $usermodel->addUserToIndexingQueue($userId);

	    // delete validateuser key from memcached
	    $this->load->library('cacheLib');
		$this->cacheLib = new cacheLib();
	    $cookie = '';
	    $cookie = isset($_COOKIE['user'])?$_COOKIE['user']:'';
	    $key = "lu_".md5('validateuser'.$cookie.'on');
	    $this->cacheLib->clearCacheForKey($key);
	    $keyToDelete = "lu_".md5("validateuser_".$userId);
	    $this->cacheLib->clearCacheForKey($keyToDelete);
	}

	if($imageUrl == "upload")  {
		if($_FILES['myImage']['tmp_name'][0] == '')
	    echo "Please select a photo to upload";
	    else
    	{
		    $this->load->library('Upload_client');
			$uploadClient = new Upload_client();	
			$upload = $uploadClient->uploadFile($appId,'image',$_FILES,array(),$userId,"newProfilePage", 'myImage');
		   
		    if(!is_array($upload)) 
		    {
		    	echo $upload;
		    }
		    else
		    {
				if($vcard==0)
			 		$response = $this->updateUserAttribute($appId, $userId, 'userImage',$upload[0]['imageurl'],$this->displayname);
			    
		   		$upload[0]['imageurl'] = addingDomainNameToUrl(array('url'=>$upload[0]['imageurl'],'domainName'=>MEDIA_SERVER));
			    echo $upload[0]['imageurl'];

			    if(($isImageExist == 0) && (!empty($upload[0]['imageurl']))) {

			    	$userpoint = new \user\libraries\RegistrationObservers\UserPointUpdation;
        			$userpoint->updateUserProfilePicPoints($userId);

				}
		    }
		}
    }
    else
    {

		$url = $this->input->post('changeavtar1');
		if($vcard==0)
			$response = $this->updateUserAttribute($appId, $userId, 'userImage',$url,$this->displayname);
    		
		echo $url;
		if(($isImageExist == 0) && (!empty($url))) {
	       	$userpoint = new \user\libraries\RegistrationObservers\UserPointUpdation;
        	$userpoint->updateUserProfilePicPoints($userId);
		}
    }
}
	
/**
 * Function to get the total colleges in user network
 * @param integer $userId
 */
     function totalUsersCollegesinUserNet($userId)
     {
	  return array(
						"colleges"=> 0,
						"users"=> 0
					     );
     }
	 
	
	function accountSettingAbroad(){
		// redirect home
		header('Location: '.SHIKSHA_STUDYABROAD_HOME);
		exit;
		//redirect to new profile page
		//$this->_redirectToNewProfilePage();
		$this->abroadCheck = 1;
		$this->init();
		if($this->validation !== 'false') {
                $this->load->model('user/usermodel');
                $usermodel = new usermodel;
                
                $userId 	= $this->validateUserData[0]['userid'];
                $user 	= $usermodel->getUserById($userId);
				//_p($user);
                if(!is_object($user))
                {
                     $loggedInUserData = false;
                     $this->checkIfLDBUser = 'NO';
                }
                else
                {
                    $name = $user->getFirstName().' '.$user->getLastName();
                    $email = $user->getEmail();
                    $userFlags = $user->getFlags();
					$userCity = $user->getCity();
                    $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                    $this->checkIfLDBUser = $isLoggedInLDBUser;
                    $pref = $user->getPreference();
					//_p($pref);
                    if(is_object($pref)){
                        $desiredCourse = $pref->getDesiredCourse();
                    	$abroadSpecialization = $pref->getAbroadSpecialization();
                    }else{
                        $desiredCourse = null;
                    }
                    $loc = $user->getLocationPreferences();
                    $isLocation = count($loc);
                    $loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser, 'desiredCourse' => $desiredCourse,'abroadSpecialization'=>$abroadSpecialization,'isLocation'=>$isLocation,'userCity'=>$userCity);
                }
            }
            else {
                $loggedInUserData = false;
                $this->checkIfLDBUser = 'NO';
            }
			
		$displayData['beaconTrackData'] = array(
                                                'pageIdentifier' => 'profileSettingPage',
                                                'pageEntityId' => 0,
                                                'extraData' => null
                                            );
		$displayData['validateuser']     = $this->validateUserData;
		$displayData['loggedInUserData'] = $loggedInUserData;
		
		$this->load->view('user/abroadUserSetting/userProfile',$displayData);	
		} 

		
	public function studyAbroadInLineLogin(){
		
		$Validate = $this->checkUserValidation();
		if($Validate !== 'false') {
                $userId 	= $Validate[0]['userid'];
				$shikshaApplyCommonLib		= $this->load->library('rateMyChances/ShikshaApplyCommonLib');
				$RMCCourseAndUnivObj = $shikshaApplyCommonLib->getRMCCoursesAndUniversitiesByUser($userId);
				if(count($RMCCourseAndUnivObj['courses'])>0){
					$url = SHIKSHA_STUDYABROAD_HOME.'/my-saved-courses';
				}else{
					$url = SHIKSHA_STUDYABROAD_HOME;
				}
				redirect($url, 'location');
		}else{
			$displayData;
			$displayData['hideTrackingFields'] = true;
			$displayData['hideLoginSignupBar'] = 'true';
			$displayData['loggedInUserData'] = false;
			$displayData['hideGNB']          = 'true';
			$displayData['hideHTML']		 = 'true';
			$displayData['logoCustomCSS']	 = 'text-align: center;width: 100%;';	

			$displayData['beaconTrackData'] = array(
										            'pageIdentifier' => 'loginPage',
										            'pageEntityId' => 0,
										            'extraData' => null
										        );
			$signupFormOptionLib = $this->load->library('studyAbroadCommon/signUpFormOptionLib');
			$displayData['skipSignupLink'] = $signupFormOptionLib->checkIfAlreadyRegisteredCase();
			//cookie
			$signupFormLib = $this->load->library('studyAbroadCommon/AbroadSignupLib');
			$signupFormLib->getSignupFormParams($cookieData);
			
			if(!is_null($cookieData)){
			   $displayData = array_merge($displayData,$cookieData);
			}
			$displayData['newSAOverlay'] = true;
			if(!empty($_COOKIE['applicationProcessSignUp'])){
				$displayData['trackingPageKeyId'] = $_COOKIE['applicationProcessSignUp'];
			}
			
			$this->load->view('user/studyAbroadInlineLogin',$displayData);
		}
	} 	
}
?>
