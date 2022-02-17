<?php

/**
 * Class CategoryProductEnterprise
 *
 * This class is concerned with the functionality related to the Category Sponsor CMS such as:
 * <ul><li>Manage Shoshkele</li><li>Manage Sticky Listing</li><li>Couple-Decouple Interface</li><li>Post for National Client</li></ul>
 */
class CategoryProductEnterprise extends MX_Controller
{
    private $tabIdCategorySponsor = 1024;
    private $tabIdMIL = 1025;

    function __construct(){
        parent::__construct();

        $this->load->library('nationalCategoryList/CategoryProductLibrary');
        $this->categoryProductLib = new CategoryProductLibrary();

    }

    /**
     * Validate a user by matching the logged in user details
     * @return array Information about the valid logged in user
     */
    private function validateUser(){
        $cmsUserInfo = modules::run('enterprise/Enterprise/cmsUserValidation');
        if($cmsUserInfo['usergroup']!= "cms") {
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
        return $cmsUserInfo;
    }

    /**
     * Entry point for the MIL .
     * This needs to be changed to accept shoshkele / sticky listing / shoshkele-listing-coupling
     */
    public function index(){
        $cmsUserInfo = $this->validateUser();

        $data = array();
        $data['userid'] 		= 	$cmsUserInfo['userid'];
        $data['displayName']    = 	$cmsUserInfo['validity'][0]['displayname'];
        $data['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
        $data['prodId'] 		=   $this->tabIdMIL; //Tab ID

        $this->load->view('nationalCategoryList/enterprise/searchClient',$data);
    }

    /**
     * Accepts the (Ajax) request and present the list of clients and their related details.
     */
    public function getClientDetails(){
        $this->load->library('sums/Sums_manage_client');
        $objSumsManage = new Sums_Manage_client();

        $request['email'] = $this->input->post('email',true);
        $request['displayname'] = $this->input->post('displayname',true);
        $request['collegeName'] = $this->input->post('collegeName',true);
        $request['contactName'] = $this->input->post('contactName',true);
        $request['contactNumber'] = $this->input->post('contactNumber',true);
        $request['clientId'] = $this->input->post('clientId',true);

        $data['users'] =  $objSumsManage->getUserForQuotation(1, $request);

        $this->load->view('nationalCategoryList/enterprise/clientDetails', $data);
    }

    /**
     * Accepts the request from the Set Main Institute button (input is the client id) and sends the user to the listing selection page from where the main institute listing can be set
     *
     * @param string $action set or unset (case insensitive)
     * @param string $clientUserId
     */
    public function showClientInstitutes($action='set', $clientUserId='') {
        $this->load->library('register_client');
        $regObj = new Register_client();

        $this->load->library('sums/sums_product_client');
        $objSumsProduct = new Sums_Product_client();


        $data = array();

        //logged in user details
        $cmsUserInfo = $this->validateUser();
        $data['userid'] 		= 	$cmsUserInfo['userid'];
        $data['displayName']    = 	$cmsUserInfo['validity'][0]['displayname'];
        $data['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
        $data['prodId'] 		=   $this->tabIdMIL; //Tab ID

        //client user details
        if($clientUserId != '' && intval($clientUserId) > 0){
            $data['clientId'] = $clientUserId;
        } else {
            $data['clientId'] = $this->input->post('selectedUserId',true);
        }
        $clientDetail = $regObj->userdetail(1,$data['clientId']);
        $data['clientEmail'] = $clientDetail[0]['email'];
        $data['clientDisplayName'] = $clientDetail[0]['displayname'];



        if(strcasecmp($action, 'set') == 0){
            //get subscriptions for client
            $data['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$data['clientId']));

            //get primary institutes of the client
            $data['clientListings'] = $this->categoryProductLib->getPrimaryListings($data['clientId']);


            $this->load->view('nationalCategoryList/enterprise/setMainListing',$data);
        } else if(strcasecmp($action, 'unset') == 0){

            $data['clientListings'] = $this->categoryProductLib->getMainSponsoredInstitutes('main', $data['clientId']);

            $this->load->view('nationalCategoryList/enterprise/unsetMainListing',$data);
        }
    }


    /**
     * Action related to the sponsored main listings
     *
     * @param $subscriptionId
     * @param $listingId
     */
    public function getListingSubscriptionDetails($subscriptionId, $listingId)
    {
        //get tier from subscription
        $this->load->model('sums/sumsmodel');
        $sumsModel = new SumsModel();

        $tiers = $sumsModel->getTiersBasedOnsubscription($subscriptionId);

        //get location (cities / states) by tier and institute
        $data = $this->categoryProductLib->getListingLocationByTier($tiers, $listingId);

        //get criteria by tier
        $data['criteria'] = $this->categoryProductLib->getCriteriaByTier($tiers['subcat_tier']);

        //validation
        if ( ( empty($data['cities']) && empty($data['states']) ) || empty($data['criteria']) ) {
            echo json_encode(array('error' => 'No location / criteria found for this combination.'));
        } else {
            echo json_encode(array('data' => $data));
        }

    }


    /**
     * Accepts the (Ajax) request and sets a listing as main
     */
    public function setMainListing() {

        $request['instituteId']  = $this->input->post('listingTypeId', true);
        $request['subscriptionStartDate']  = $this->input->post('subsStartDate', true);
        $request['subscriptionEndDate']    = $this->input->post('subsEndDate', true);
        $request['subscriptionId'] = $this->input->post('subscriptionId', true);
        $request['clientUserId']   = $this->input->post('clientUserId', true);
        $request['loggedInUserId'] = $this->input->post('cmsUserId', true);
        $request['cityId']        = $this->input->post('cityListId', true);
        $request['criterionId'] = $this->input->post('criterionId', true);
        $request['stateId']       = $this->input->post('stateId', true);

        $validation = $this->categoryProductLib->validateForm($request);

        if($validation['result'] !== 'Failure') {
            $result = $this->categoryProductLib->setMainSponsoredInstitutes($request, 'main');
            echo json_encode($result);
        } else {
            echo json_encode($validation);
        }
    }


    /**
     * Used for unsetting a main listing for a given client
     *
     * @param string $clientId
     */
    public function selectClientMainInstitutes($clientId="") {
        $this->load->library('register_client');
        $regObj = new Register_client();


        $cmsUserInfo = $this->validateUser();
        $data['userid'] 		= 	$cmsUserInfo['userid'];
        $data['displayName']    = 	$cmsUserInfo['validity'][0]['displayname'];
        $data['headerTabs'] 	=  	$cmsUserInfo['headerTabs'];
        $data['prodId'] 		=   $this->tabIdMIL; //Tab ID


        if(!intval($clientId) <= 0) {
            $clientId = $this->input->post('selectedUserId',true);
        }

        $data['clientId'] = $clientId;

        $data['clientListings'] = $this->categoryProductLib->getMainInstitutesByClient($clientId);

        $clientDetail = $regObj->userdetail(1,$data['clientId']);
        $data['clientEmail'] = $clientDetail[0]['email'];
        $data['clientDisplayName'] = $clientDetail[0]['displayname'];

        $this->load->view('nationalCategoryList/enterprise/unsetMainListing',$data);
    }

    /**
     * Accepts the (Ajax) request and unsets a main listing for a client.
     *
     * @see \CategoryProductLibrary::unsetMainSponsoredInstitutes for the model call
     */
    public function unsetMainListing() {
        $this->validateUser();

        $clientUserId = $this->input->post('clientUserId');
        $loggedInUserId = $this->input->post('cmsUserId');
        $idsToUnset = $this->input->post('selectedInstitutesChkbox');
        $this->categoryProductLib->unsetMainSponsoredInstitutes($idsToUnset, $loggedInUserId, 'main');
        setcookie("thanksMsgCookie", "Thanks, Main Institute on the selected combination(s) has been unset successfully.", time()+8);  /* expire in 10 secs */
        redirect('/nationalCategoryList/CategoryProductEnterprise/showClientInstitutes/unset/'.$clientUserId,'location');
    }


    /**
     * Handle the sticky listing management
     *
     * @param string $clientId The client id for which the management is to be done
     *
     * @see      \CategoryProductLibrary::validateForm for the form validation logic
     * @see      \CategoryProductLibrary::setMainSponsoredInstitutes for the data saving logic
     */
    public function manageSponsor($clientId = '')
    {
        $cmsUserInfo = $this->validateUser();

        $displayData['headerTabs'] = $cmsUserInfo['headerTabs'];
        $displayData['displayName'] = $cmsUserInfo['validity'][0]['displayname'];
        $displayData['prodId'] = $this->tabIdCategorySponsor; // The tab id in tabNames
        $displayData['clientId'] = $clientId;
        $displayData['sponsorType'] = 'stickyListing';

        if(intval($clientId) > 0)
        {
            $this->load->library('sums/sums_product_client');
            $objSumsProduct = new Sums_Product_client();

            $displayData['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1,array('userId'=>$displayData['clientId']));

            $displayData['sponsoredInstitutes'] = $this->categoryProductLib->getMainSponsoredInstitutes('category_sponsor', $displayData['clientId']);;
            $displayData['clientListings'] = $this->categoryProductLib->getPrimaryListings($displayData['clientId']);
        }
        $this->load->view('nationalCategoryList/enterprise/viewStickyListing',$displayData);
    }

    /**
     * Set an input listing as sticky for a client on a city / state and for a subscription
     *
     * @see      \CategoryProductLibrary::validateForm for the form validation logic
     * @see      \CategoryProductLibrary::setMainSponsoredInstitutes for the data saving logic
     */
    public function setStickyListing(){
        $cmsUserInfo = $this->validateUser();
        $data              = array();
        $data['clientId']  = $this->input->post('clientUserId');
        $data['instituteId'] = $this->input->post('listingId');
        $data['criterionId']  = $this->input->post('criterionId');
        $data['cityId']         = $this->input->post('cities');
        $data['stateId']        = $this->input->post('states');
        $data['subscriptionId'] = $this->input->post('subscription_id');
        $data['subscriptionStartDate']  = $this->input->post('subsStartDate', true);
        $data['subscriptionEndDate']    = $this->input->post('subsEndDate', true);
        $data['loggedInUserId'] = $cmsUserInfo['userid'];

        $validation = $this->categoryProductLib->validateForm($data);

        if($validation['result'] !== 'Failure') {
            $result = $this->categoryProductLib->setMainSponsoredInstitutes($data, 'category_sponsor');
            echo json_encode($result);
        } else {
            echo json_encode($validation);
        }
    }

    /**
     * Unset a previously set listing as sticky based on the listing id (the institute id)
     *
     * @see \CategoryProductLibrary::unsetMainSponsoredInstitutes
     */
    public function unsetStickyListing(){

        $listingId = $this->input->post('listingId');
        if(intval($listingId) > 0){
            $cmsUserInfo = $this->validateUser();
            echo $this->categoryProductLib->unsetMainSponsoredInstitutes(array($listingId), $cmsUserInfo['userid'], 'category_sponsor');
        } else {

        }
    }

    /**
     * Handle the shoshkele management corresponding to input client id
     *
     * @param string $clientId The input client ID
     *
     * @see \Sums_Product_client::getAllSubscriptionsForUser for obtaining the subscriptions corresponding to the input client ID
     * @see \CategoryProductLibrary::getShoshkeles for obtaining the shoshkeles from the system
     *
     */
    public function manageShoshkele($clientId = '')
    {
        $cmsUserInfo = $this->validateUser();

        $displayData['headerTabs']  = $cmsUserInfo['headerTabs'];
        $displayData['displayName'] = $cmsUserInfo['validity'][0]['displayname'];
        $displayData['prodId']      = $this->tabIdCategorySponsor; // The tab id in tabNames
        $displayData['clientId']    = $clientId;
        $displayData['sponsorType'] = 'shoshkele';

        if (intval($clientId) > 0) {
            $this->load->library('sums/sums_product_client');
            $objSumsProduct = new Sums_Product_client();

            $displayData['subscriptionDetails'] = $objSumsProduct->getAllSubscriptionsForUser(1, array('userId' => $displayData['clientId']));

            $displayData['shoshkeleList'] = $this->categoryProductLib->getAllShoshkeles($clientId);
        }

        $this->load->view('nationalCategoryList/enterprise/viewShoshkele', $displayData);
    }

    /**
     * Perform the 3 steps :
     * 1) Hit SUMS db to find out the tier based on the subscription
     * 2) Basis the city and state id, find out the city and the state
     * 3) Basis the subcat id, find out the criterion_nickname
     *
     * @param string $subscriptionId The subscription id in question
     * @param string $clientId
     *
     * @see \CategorySponsorLib::getOptionsBasedOnSubscription
     */
    public function getSubscriptionDetails($subscriptionId=''){


        //get tier from subscription
        $this->load->model('sums/sumsmodel');
        $sumsModel = new SumsModel();

        $tiers = $sumsModel->getTiersBasedOnsubscription($subscriptionId);

        //get location (cities / states) by tier
        $data = $this->categoryProductLib->getLocationByTier($tiers);

        //get criteria by tier
        $data['criteria'] = $this->categoryProductLib->getCriteriaByTier($tiers['subcat_tier']);

        //validation
        if ( ( empty($data['cities']) && empty($data['states']) ) || empty($data['criteria']) ) {
            echo json_encode(array('error' => 'No location / criteria found for this combination.'));
        } else {
            echo json_encode(array('data' => $data));
        }
    }

    /**
     * Add a new shoshkele for a client in the system corresponding to the input city / state, client ID, subscription, banner ID
     *
     * @see \CategoryProductLibrary::validateForm for form validation logic
     * @see \CategoryProductLibrary::addShoshkele for shoshkele insertion logic for the input details
     * @see \CategoryProductEnterprise::uploadShoshkele for adding a new shoshkele just for a client (and no other details)
     */
    public function addShoshkele(){

        $cmsUserInfo = $this->validateUser();

        $data = array(
            'cityId' => $this->input->post('cities'),
            'stateId' => $this->input->post('states'),
            'bannerId' => $this->input->post('shoshkeleId'),
            'subscriptionId' => $this->input->post('subscription_id'),
            'startDate' => $this->input->post('subsStartDate'),
            'endDate' => $this->input->post('subsEndDate'),
            'criterionId' => $this->input->post('criterionId'),
            'clientUserId' => $this->input->post('clientUserId')
        );

        $validation = $this->categoryProductLib->validateForm($data);

        if($validation['result'] !== 'Failure') {
            $result = $this->categoryProductLib->addShoshkele($data, $cmsUserInfo['userid']);
            echo json_encode($result);
        } else {
            echo json_encode($validation);
        }
    }

    /**
     * Remove a shoshkele from the system.
     * This MAY result in removing the entry for the input banner id / banner link id from all the relations where it is used currently.
     *
     * @see \CategoryProductLibrary::removeShoshkele for the model call
     */
    public function removeShoshkele(){

        $cmsUserInfo = $this->validateUser();

        $type = $this->input->post('type');
        $shoshkeleId = $this->input->post('shoshkeleId');
        echo $this->categoryProductLib->removeShoshkele($type, $shoshkeleId, $cmsUserInfo['userid']);

    }


    /**
     * Upload a shoshkele to be used for some subscription
     *
     * @see \CategoryProductLibrary::uploadShoshkele for the model call
     * @see \CategoryProductEnterprise::addShoshkele for the usage of this added shoshkele against some subscription and other details
     */
    public function uploadShoshkele()
    {
        $this->init();
        $clientId = $this->input->post('clientId');
        $bannerId = $this->input->post('bannerId');
        $bannerName = $this->input->post('bannername');
        $shoshkeleUrl = $this->input->post('shoshkeleUrl');

        $shoshkeleUrl = str_replace("http:/", "https:/", $shoshkeleUrl);

        if(trim($shoshkeleUrl) == '')
            echo "Please enter iFrame URL";
        else
        {
            $response = $this->categoryProductLib->uploadShoshkele($bannerId,$shoshkeleUrl,$bannerName,$clientId);
            if(is_array($response) && !empty($response['error'])) {
                echo json_encode($response);
            }
            else {
                echo $response;
            }
        }
    }


    public function manageCoupling($clientId = '')
    {

        $cmsUserInfo = $this->validateUser();

        $displayData['headerTabs']  = $cmsUserInfo['headerTabs'];
        $displayData['displayName'] = $cmsUserInfo['validity'][0]['displayname'];
        $displayData['prodId']      = $this->tabIdCategorySponsor; // The tab id in tabNames
        $displayData['clientId']    = $clientId;
        $displayData['sponsorType'] = 'coupling';

        if (intval($clientId) > 0) {
            $this->load->library('sums/sums_product_client');

            if ($clientId != '') {



                $cityId = $this->input->post('cityId');
                $stateId = $this->input->post('stateId');
                $criterionId = $this->input->post('criterionId');


                $shoshkeleDetails = $this->categoryProductLib->getUsedShoshkeleDetails($clientId, $cityId, $stateId, $criterionId);
                // add city, state, criterion

                $displayData['shoshkeleList'] = $shoshkeleDetails['shoshkeles'];
                $displayData['cities']     = $shoshkeleDetails['cities'];
                $displayData['states']     = $shoshkeleDetails['states'];
                $displayData['criteria'] = $shoshkeleDetails['criteria'];

                if( ($cityId > 0 || $stateId > 0) && $criterionId > 0){
                    $displayData['clientListings'] = $this->categoryProductLib->getMainSponsoredInstitutes('category_sponsor', $clientId, $cityId, $stateId, $criterionId); // we are getting all listings and we would validate the entries while saving

                    $displayData['coupledData'] = $this->categoryProductLib->getCoupledData($clientId, $criterionId, $cityId, $stateId);
                    $displayData['cityId'] = $cityId;
                    $displayData['stateId'] = $stateId;
                    $displayData['criterionId'] = $criterionId;
                }
            }
        }
            // Get available Shoshkeles, the city / state and criterion for a client
            // Corresponding to the city / state and criterion, find out the sticky listings
            $this->load->view('nationalCategoryList/enterprise/manageCoupling', $displayData);
    }

    public function couple(){

        $this->validateUser(); // in order to prevent anyone with invalid credentials wreck havoc
        $listingSubscriptionId = $this->input->post('listing_subs_id');
        $bannerLinkId = $this->input->post('banner_link_id');

        $data = array(
            'cityId' => $this->input->post('city_id'),
            'stateId' => $this->input->post('state_id'),
            'criterionId' => $this->input->post('criterion_id'),
        );

        $validation = $this->categoryProductLib->validateForm($data);

        if($validation['result'] !== 'Failure') {
            echo json_encode($this->categoryProductLib->couple($listingSubscriptionId, $bannerLinkId));
        } else {
            echo json_encode($validation);
        }
    }

    public function decouple(){

        $this->validateUser(); // in order to prevent anyone with invalid credentials wreck havoc
        $couplingId = $this->input->post('coupling_id');

        echo $this->categoryProductLib->decouple($couplingId);
    }

    public function bannerKeywords() {
        $bannerKeywords = $this->categoryProductLib->getBannerKeywords();
        foreach ($bannerKeywords as $key => $value) {
            _p($value);
        }
        die;
    }
}