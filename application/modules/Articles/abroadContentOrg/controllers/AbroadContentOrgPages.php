<?php 
class AbroadContentOrgPages extends MX_Controller {
   
   private $userStatus;
   private $checkIfLDBUser;
   private $abroadContentOrgPageLib;
   
   public function __construct(){
	parent::__construct();
	// prepare user data
        $this->userStatus = $this->prepareLoggedInUserData();
        $this->config->load('abroadContentOrgConfig');
        // common abroad content org page library 
        $this->abroadContentOrgPageLib 	= $this->load->library('abroadContentOrg/AbroadContentOrgLib');
    } 
    
    
   public function abroadContentOrgPages($paramString){  
	  $displayData['trackForPages'] = true;
      $displayData['ajaxRequest'] = $this->input->post('ajaxRequest');
      if(!$displayData['ajaxRequest']){
		 $stageInfo = $this->abroadContentOrgPageLib->validateUrlAndGetStageDetails($paramString);
		 $stageDetails = $stageInfo[0];
		 $recommendedUrl = $stageInfo[1];
		 $displayData['seoData'] = $this->_getMetaDataForContentOrgPage($stageDetails['stageId'],$recommendedUrl);
		 $displayData['breadCrumbData'] = $this->_getBreadCrumb($displayData['seoData']);
		 $displayData['stageDetails'] = $stageDetails;
		 $displayData['widgetUrl'] = $this->abroadContentOrgPageLib->getUrlsForWidget();
		 $stageName = $stageDetails['stageName'];
	  }else{
		 $stageId = $this->input->post('stageId');
		 $this->load->config('studyAbroadCommonConfig');
		 $contentCycleTagsConfig = $this->config->item('CONTENT_LIFECYCLE_TAGS');
		 $stageName = $contentCycleTagsConfig[$stageId]['LEVEL1_VALUE'];
		 $filterValue = $this->input->post('filterValues');
		 $filterValue = base64_decode($filterValue);
	  }
      $displayData['pageData'] = $this->abroadContentOrgPageLib->getContentOrgPageData($stageName,$filterValue);
      if(!$displayData['ajaxRequest']){
		 $abroadContentOrgFilterLib = $this->load->library('abroadContentOrg/AbroadContentOrgFilterLib');
		 $displayData['filterData'] = $abroadContentOrgFilterLib->prepareFilterData($displayData['pageData']['lifeCycleTagsData']['stageValues'],$stageDetails['stageId'],$displayData['pageData']['articlesData'],$stageName);
      }
      $validateuser = $this->checkUserValidation();
	  /*
		 if($validateuser !== 'false') {
		 $this->load->model('user/usermodel');
		 $usermodel = new usermodel;
		 $userId = $validateuser[0]['userid'];
		 $user = $usermodel->getUserById($userId);
		 if(!is_object($user))
		 {
			$loggedInUserData = false;
			$checkIfLDBUser = 'NO';
		 }
		 else{
			$name = $user->getFirstName().' '.$user->getLastName();
			$email = $user->getEmail();
			$userFlags = $user->getFlags();
			$isLoggedInLDBUser = $userFlags->getIsLDBUser();
			$checkIfLDBUser = $usermodel->checkIfLDBUser($userId);
			$pref = $user->getPreference();
			if(is_object($pref)){
			   $desiredCourse = $pref->getDesiredCourse();
			}else{
			   $desiredCourse = null;
			}
			$loc = $user->getLocationPreferences();
			$isLocation = count($loc);
			$loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser, 'desiredCourse' => $desiredCourse, 'isLocation'=>$isLocation);
		 }
	  }
	  else {
		 $loggedInUserData = false;
		 $checkIfLDBUser = 'NO';
	  }
	  $displayData = array_merge($displayData,array('validateuser'=>$validateuser,'loggedInUserData'=>$loggedInUserData,'checkIfLDBUser'=>$this->checkIfLDBUser));
	  */
	 
	  $displayData = array_merge($displayData,array('validateuser'=>$validateuser,'loggedInUserData'=>$this->userStatus,'checkIfLDBUser'=>$this->checkIfLDBUser));
      
      //tracking
	  $this->_prepareTrackingData($displayData);

      $this->load->view("abroadContentOrg/abroadContentOrgOverview",$displayData);
    }
    
    function _getMetaDataForContentOrgPage($stageId,$recommendedUrl)
    {
      //$this->config->load('abroadContentOrgConfig');
      $contentOrgConfig = $this->config->item('abroad_content_org_details');
      $seoData = array();
      $seoData['seoTitle'] = $contentOrgConfig[$stageId]['title'];
      $seoData['title'] = $contentOrgConfig[$stageId]['title'];
      $seoData['seoDescription'] = $contentOrgConfig[$stageId]['seoDescription'];
      $seoData['canonicalUrl'] = $recommendedUrl;
      return $seoData;
    }

    private function _prepareTrackingData(&$displayData)   
        {    
            $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'stagePage',
                                              'pageEntityId' => '0',
                                              'extraData' => null
                                              );
           
        }
    
   private function prepareLoggedInUserData()
   {
	  $loggedInUserData = $this->checkUserValidation();
	  if($loggedInUserData !== 'false') {
		 $this->load->model('user/usermodel');
		 $usermodel = new usermodel;
		 $userId 	= $loggedInUserData[0]['userid'];
		 $user 	= $usermodel->getUserById($userId);
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
			$isLoggedInLDBUser = $userFlags->getIsLDBUser();
			$this->checkIfLDBUser = $usermodel->checkIfLDBUser($userId);
			
			$pref = $user->getPreference();
			if(is_object($pref)){
			   $desiredCourse = $pref->getDesiredCourse();
			}else{
			   $desiredCourse = null;
			}
		 
			$loc = $user->getLocationPreferences();
			$isLocation = count($loc);
			$loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser, 'desiredCourse' => $desiredCourse, 'isLocation'=>$isLocation);
		 }
	  }
	  else {
		 $loggedInUserData = false;
		 $this->checkIfLDBUser = 'NO';
	  }
	  return $loggedInUserData;
   }
    
    private function _getBreadCrumb($seoData){
            
            $breadCrumbData[] = array('title' => 'Home' , 'url' => SHIKSHA_STUDYABROAD_HOME);
            $breadCrumbData[] = array('title' => $seoData['title'] , 'url' => '');
            return $breadCrumbData;
    }
	
   public function getHomePageContentOrgWidget(){
	  $urlData = $this->abroadContentOrgPageLib->getUrlsForWidget();
	  $content = $this->load->view("widget/abroadContentOrgNavigationWidgetHomePage.php",array("contentOrgData"=>$urlData));
	  echo $content;
   }
   /*This section has been permanently removed from all the content pages and listing pages as well
   public function getContentOrgWidget(){
	   $urlData = $this->abroadContentOrgPageLib->getUrlsForWidget();
	   $content = $this->load->view("widget/abroadContentOrgNavigationWidget.php",array("contentOrgData"=>$urlData));
	   echo $content;
   }*/
}

?>
