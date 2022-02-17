<?php 
class userProfilePage extends ShikshaMobileWebSite_Controller{
    private $userProfilePageLib;
    function __construct(){
        $this->userProfilePageLib = $this->load->library('userProfilePage/userProfilePageLib');
    }

    private function _init(&$displayData){
        $displayData['profileDisplayName'] = $this->security->xss_clean($displayData['profileDisplayName']);
        $displayData['validateuser'] = $this->checkUserValidation();
        if(strtolower($displayData['validateuser'][0]['displayname']) == strtolower($displayData['profileDisplayName']) && $displayData['profilePage'] == 'viewPage'){
            $displayData['selfProfile'] = true;
        }else{
            $displayData['selfProfile'] = false;
        }
        //check for valid url
        $urlData = $this->userProfilePageLib->getUserProfilePageURL($displayData['profileDisplayName'], $displayData['profilePage'], false, true);
        if($urlData['url'] != getCurrentPageURLWithoutQueryParams()){
            redirect($urlData['url'], 'location');
        }
        $displayData['profileUserData'] = $this->userProfilePageLib->validateProfilePage($displayData['profileDisplayName']);
        if($displayData['profileUserData'] == false){
            show_404_abroad();
        }
        $displayData['profileUserId'] = $displayData['profileUserData']['userid'];
    }

    public function viewUserProfile($profileDisplayName){
        $displayData = array();
        $displayData['profileDisplayName'] = $profileDisplayName;
        $displayData['profilePage'] = 'viewPage';
        $this->_init($displayData);
        $this->userProfilePageLib->setMISTrackingDetails($displayData, 'viewProfile');
        $this->userProfilePageLib->setSEODetails($displayData, 'viewProfile');
        $this->userProfilePageLib->viewUserProfile($displayData);
        $displayData['firstFoldCssPath'] = 'userProfilePage/css/viewProfilePageFirstFoldCss';
        $displayData['trackingPageKeyIdForReg'] = 1421;
        $this->load->view("userProfilePage/viewUserProfilePageOverview", $displayData);
    }

    public function editUserProfile($profileDisplayName){
        $validateUser = $this->checkUserValidation();
        $displayData = array();
        $displayData['profileDisplayName'] = $profileDisplayName;
        $displayData['profilePage'] = 'editPage';
        $this->_init($displayData);
        $displayData['profileUserData'] = $this->userProfilePageLib->validateProfilePage($displayData['profileDisplayName'],$validateUser);
        if($validateUser === false || $displayData['profileUserData'] === false) // logged in only page
        {
            show_404_abroad();
        }
        else if($displayData['profileUserData']['userid'] != $validateUser[0]['userid']){ 
            //user should match
            redirect(SHIKSHA_STUDYABROAD_HOME.'/users/'.$displayData['profileDisplayName']);
        }
        $displayData['userMobile'] = $validateUser[0]['mobile'];
        $displayData['profileUserId'] = $displayData['profileUserData']['userid'];
        $this->userProfilePageLib->prepareDataToPrefillForm($displayData, $validateUser);
        $this->userProfilePageLib->setMISTrackingDetails($displayData, 'editProfile');
        $this->userProfilePageLib->setSEODetails($displayData, 'editProfile');
        $displayData['firstFoldCssPath'] = 'userProfilePage/css/editProfilePageFirstFoldCss';
        //_p($displayData);die;
        $displayData['hideFooter'] = true;
        $displayData['isEditProfile'] = true;
        $displayData['trackingPageKeyIdForReg'] = 1419;
        $displayData['trackingPageKeyId'] = 1441;
        $this->load->view("userProfilePage/editUserProfilePageOverview", $displayData);
    }
}
