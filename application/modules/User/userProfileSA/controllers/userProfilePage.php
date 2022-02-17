<?php 
class userProfilePage extends MX_Controller {
    private $userProfilePageLib;
    function __construct(){
        $this->userProfilePageLib = $this->load->library('userProfilePage/userProfilePageLib');
    }

    private function _init(&$displayData){
        $displayData['profileDisplayName'] = $this->security->xss_clean($displayData['profileDisplayName']);
        $displayData['validateuser'] = $this->checkUserValidation();
        if(isset($displayData['validateuser'][0]['displayname']) && (strtolower($displayData['validateuser'][0]['displayname']) == strtolower($displayData['profileDisplayName'])) && $displayData['profilePage'] == 'viewPage') {
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
        $displayData['editProfilePageUrl'] = $this->userProfilePageLib->getUserProfilePageURL($displayData['profileDisplayName'], 'editPage', false);
        $displayData['editProfilePageUrl'] = json_decode($displayData['editProfilePageUrl'], true)['url'];
        $displayData['firstFoldCssPath'] = 'userProfileSA/css/viewProfilePageFirstFoldCss';
        $displayData['trackingPageKeyIdForReg'] = 1419;
        //_p($displayData);die;
        $this->load->view("userProfileSA/viewUserProfilePageOverview", $displayData);
    }

    public function editUserProfile($profileDisplayName){
        
        $displayData = array();
        $displayData['profileDisplayName'] = $profileDisplayName;
        $displayData['profilePage'] = 'editPage';
        $this->_init($displayData);
        $displayData['profileUserData'] = $this->userProfilePageLib->validateProfilePage($displayData['profileDisplayName'],$validateUser);
        if($displayData['validateuser'] === false || $displayData['profileUserData'] === false) // logged in only page
        {
            show_404_abroad();
        }
        else if($displayData['profileUserData']['userid'] != $displayData['validateuser'][0]['userid']){ 
            //user should match
            redirect(SHIKSHA_STUDYABROAD_HOME.'/users/'.$displayData['profileDisplayName']);
        }
        $displayData['userMobile'] = $displayData['validateuser'][0]['mobile'];
        $displayData['profileUserId'] = $displayData['profileUserData']['userid'];
        $this->userProfilePageLib->prepareDataToPrefillForm($displayData, $displayData['validateuser']);
        $this->userProfilePageLib->setMISTrackingDetails($displayData, 'editProfile');
        $this->userProfilePageLib->setSEODetails($displayData, 'editProfile');
        $displayData['firstFoldCssPath'] = 'userProfilePage/css/editProfilePageFirstFoldCss';
        //_p($displayData);die;
        $displayData['hideFooter'] = true;
        $displayData['isEditProfile'] = true;
        $displayData['trackingPageKeyIdForReg'] = 1419;
        $displayData['trackingPageKeyId'] = 1443;
        $this->load->view("userProfileSA/editUserProfilePageOverview", $displayData);
    }

    public function saveUnsubscribeMappingData()
    {
        // get logged in user id 
        $validateUser = $this->checkUserValidation();
        // if no one is logged in, give auth access error
        if($validateUser == false)
        {
            echo "AUTH_ERR";
            return false;
        }
        // get post data
        $state = $this->input->post("state",true);
        $unsubscribeCategory = $this->input->post("unsubscribeCategory",true);
        // pull the list of all mailer categories
        $this->load->config('user/unsubscribeConfig');
        $mailerCategory = $this->config->item('mailerCategory');
        if(!in_array($unsubscribeCategory,array_keys($mailerCategory)) || is_null($state))
        {
            echo "ERR";return false;
        }
        // try to save
        try{
            $userProfileLib = $this->load->library('userProfile/UserProfileLib');
            $userProfileLib->userUnsubscribeMapping($validateUser[0]['userid'],$state,$unsubscribeCategory);
            //positive response
            echo "SUCCESS";return true;
        }catch(Exception $e)
        {
            // if it doesn't, give something went wrong as response
            echo "ERR";return false;
        }
    }
}
