<?php
/*
 * Controller for study abroad consultant profile page
 */
class ConsultantPage extends MX_Controller
{
    private $consultantLib;
    private $userStatus;
    private $consultantPageBuilder;
    private $consultantRepository;
            
    public function __construct(){
        parent::__construct();
        $this->userStatus = $this->checkUserValidation();
        if($this->userStatus !== 'false') {
            $this->load->model('user/usermodel');
            $usermodel = new usermodel;
            $userId 	= $this->userStatus[0]['userid'];
            $user 	= $usermodel->getUserById($userId);
            if(!is_object($user))
            {
                $loggedInUserData = false;
            }
            else
            {
                $name = $user->getFirstName().' '.$user->getLastName();
                $email = $user->getEmail();
                $userFlags = $user->getFlags();
                $isLoggedInLDBUser = $userFlags->getIsLDBUser();
                $loggedInUserData = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
                
                $pref = $user->getPreference();
                $loc = $user->getLocationPreferences();
                $isLocation = count($loc);
                if(is_object($pref)){
                    $desiredCourse = $pref->getDesiredCourse();
                }else{
                    $desiredCourse = null;
                }
                $loggedInUserData['desiredCourse'] = $desiredCourse;
                $loggedInUserData['isLocation'] = $isLocation;
            }
        }
        else
        {
            $loggedInUserData = false;
        }
        
        $this->userStatus = $loggedInUserData;
        $this->load->builder('ConsultantPageBuilder', 'consultantProfile');
        $this->consultantPageBuilder    = new ConsultantPageBuilder;
        // consultant page repository
        $this->consultantRepository = $this->consultantPageBuilder->getConsultantRepository();
        // common consultant page library 
        $this->consultantPageLib = $this->load->library('ConsultantPageLib');
    }
    
    /*
     * controller function for consultantPage
     */
    public function consultantPage($consultantId)
    {
        // validate consultant ID
        $consultantObj = $this->_validateConsultant($consultantId);
	//_p($consultantObj); die;
	
        // pass consultantObj further
        $displayData['consultantObj'] = $consultantObj;
        
        // URL Validation and redirection
        $this->_validateUrl($consultantObj->getCanonicalUrl());
	
	$displayData['hasValidSubscription'] = $this->consultantPageLib->validateSubscriptionData($consultantId);		
    $displayData['trackForPages'] = true;
	$locationDetails = $this->consultantPageLib->formatLocationData($consultantObj->getConsultantLocations());
	$displayData['locations'] = $locationDetails['formatedLocationObjects'];
	$displayData['headOfcCityId'] = $locationDetails['headOfcCityId'];
        
        //get user validation
        $validateuser                       = $this->checkUserValidation();
        $displayData['validateuser']        = $validateuser;
        $displayData['loggedInUserData']    = $this->userStatus;
        
        // Get SEO related data
        $displayData['seoData']                     = $displayData['consultantObj']->getSeoInfo();
        $displayData['seoData']['canonicalUrl']     = $recommendedUrl;
        
        // check if user visited consultant page first time
        $displayData['showGutterHelpText']          = $this->consultantPageLib->checkIfFirstTimeVisitor();
        
        //colleges represented widget
        $this->consultantPageLib->prepareDataForCollegesRepresentedTab($displayData);
        //countries represented widget
        if($displayData['collegesRepresentedTabFlag'])
        {
            $this->consultantPageLib->prepareDataForCountriesRepresentedTab($displayData);    
        }
        // services offered widget
        $this->consultantPageLib->prepareDataForServicesOfferedTab($displayData);
        // students admitted widget
        $this->consultantPageLib->prepareDataForStudentAdmittedTab($displayData);

        // prepare country ids for google remarketing tracking
        $countryIds = array_map(function($a){return $a['countryId'];},$displayData['collegesRepresentedTabData']);
        $displayData['googleRemarketingParams'] = array('countryId'=>implode(',',array_unique($countryIds)));
        //tracking         
        $this->_prepareTrackingData($displayData);
        //load view
        $this->load->view("consultantPageOverview",$displayData);
    }

    private function _prepareTrackingData(&$displayData) 
    {      
        $displayData['beaconTrackData'] = array(
                                                'pageIdentifier' => 'consultantPage',
                                                'pageEntityId' => $displayData['consultantObj']->getId(),
                                                'extraData' => null  
                                                );  
        //_p($displayData['beaconTrackData']);die;
    }




    /*
     * function to validate consultantId & redirect to 404 page accordingly
     */
    private function _validateConsultant($consultantId)
    {
        if($consultantId > 0)
        {
            $consultantObj = $this->consultantRepository->find($consultantId);
            $consultantObj = reset($consultantObj);
            // check if the consultantobj is a valid one & that consultant has a location & has one university mapped to itself
            if(!$consultantObj || count($consultantObj->getUniversitiesMapped()) == 0 || count($consultantObj->getConsultantLocations()) == 0)
            {
                show_404_abroad();
            }
            else
            {
                return $consultantObj;
            }
        }
        else
        {
            show_404_abroad();
        }
    }
    /*
     * validate consultant page url
     */
    private function _validateUrl($recommendedUrl)
    {
        $userEnteredURL = trim($_SERVER['SCRIPT_URI']);	
        if($userEnteredURL != $recommendedUrl && $recommendedUrl != "")
        {
            redirect($recommendedUrl, 'location', 301);
        }
    }
}