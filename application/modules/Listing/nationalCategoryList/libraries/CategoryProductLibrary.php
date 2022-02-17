<?php

/**
 * Created by PhpStorm.
 * User: ankur
 * Date: 16/9/16
 * Time: 5:31 PM
 */
class CategoryProductLibrary
{
    function __construct() {
        $this->CI =& get_instance();

        $this->categoryProductModel = $this->CI->load->model('nationalCategoryList/categoryproductmodel');
        $this->ALL_INDIA_TIER = 1;

        $this->CI->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $this->instituteRepo = $instituteBuilder->getInstituteRepository();
    }

    public function getPrimaryListings($clientId=0){
        return $this->categoryProductModel->getPrimaryListings($clientId);
    }

    /**
     * @param array  $tiers
     * @param string $listingId
     *
     * @return array
     */
    public function getListingLocationByTier($tiers=array(), $listingId=''){
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

        $cityIds = array();
        $stateIds = array();
        //get cities by input tier
        if(!empty($tiers['city_tier'])){
            $cities = $locationRepository->getCitiesByMultipleTiers(array($tiers['city_tier']),2);
            foreach($cities[$tiers['city_tier']] as $oneCity){
                $cityIds[] = $oneCity->getId();
            }
        }

        //get states by input tier
        if(!empty($tiers['state_tier'])){
            $states = $locationRepository->getStatesByMultipleTiers(array($tiers['state_tier']),2);

            foreach($states[$tiers['state_tier']] as $oneState){
                $stateIds[] = $oneState->getId();
            }
        }

        //get listing locations by input listing id based on tier cities

        $listingLocations = $this->instituteRepo->getInstituteLocations(array($listingId));

        if($tiers['city_tier'] == $this->ALL_INDIA_TIER){
            $listingTierCities[] = array(
                'city_id' => 1,
                'city_name' => 'All India'
            );
        }
        
        foreach($listingLocations[$listingId] as $oneLocation){
            $cityId = $oneLocation->getCityId();
            $stateId = $oneLocation->getStateId();
            if(!empty($cityId)) {
                $cityObj = $locationRepository->findCity($cityId);
                $virtualCityId = $cityObj->getVirtualCityId();
            }

            if(in_array($cityId, $cityIds)){
                $listingTierCities[$cityId] = array(
                    'city_id' => $cityId,
                    'city_name' => $oneLocation->getCityName()
                );
            }

            if(in_array($virtualCityId, $cityIds)) {
                $virtualCityObj = $locationRepository->findCity($virtualCityId);
                $listingTierCities[$virtualCityId] = array(
                    'city_id' => $virtualCityId,
                    'city_name' => $virtualCityObj->getName()
                );
            }

            if(in_array($stateId, $stateIds)){
                $listingTierStates[$stateId] = array(
                    'state_id' => $stateId,
                    'state_name' => $oneLocation->getStateName()
                );
            }
        }
        
        $listingLocations = array(
            'cities' => array_values($listingTierCities),
            'states' => array_values($listingTierStates)
        );

        return $listingLocations;
    }


    public function getCriteriaByTier($tier=0){
        return $this->categoryProductModel->getCriteriaByTier($tier);
    }

    public function validateForm($request, $product='shoshkele') {
        // check if any of city or state is selected
        if( intval($request['cityId']) <= 0 && intval($request['stateId']) <= 0 ){
            return array(
                'result' => "Failure",
                'error' => 'Either of City ID or State ID is mandatory.'
            );
        }

        // check if both city and state are selected
        if( intval($request['cityId']) > 0 && intval($request['stateId']) > 0 ){
            return array(
                'result' => "Failure",
                'error' => 'Either of City ID or State ID (not both) needs to be selected.',
            );
        }

        if ($product != 'shoshkele') {
            // check if criterion selected from dropdown is mapped to any primary course of the institute selected from dropdown
            if (!$this->categoryProductModel->checkIfListingExists($request['criterionId'], $request['instituteId'])) {
                return array(
                    'result' => "Failure",
                    'error'  => 'There is no institute mapped to the selected criterion.'
                );
            }
        }

        // check if criterion has an entry in the table category_page_seo
        if(!$this->categoryProductModel->checkIfCategoryPageExists($request['criterionId'])){
            return array(
                'result' => "Failure",
                'error' => 'There is no category page corresponding to the selected criterion.'
            );
        }
    }

    public function setMainSponsoredInstitutes($request, $product='main'){
        if($product !== 'main' && $product !== 'category_sponsor'){
            $result['result'] = 'Failure';
            return $result;
        }

        $consumeSubscriptionText = array(
            'main' => 'MainCollegeLink',
            'category_sponsor' => 'StickyListing',
        );
        $this->CI->load->library(array('sums/Subscription_client','listing/Listing_client'));
        $objSubs               = new Subscription_client();
        $subDetails            = $objSubs->getSubscriptionDetails(1, $request['subscriptionId']);
        $request['baseProdId'] = $subDetails[0]['BaseProductId'];
        $remainingQuant        = $subDetails[0]['BaseProdRemainingQuantity'];
        if ($remainingQuant == 0) {
            $result = array('error' => 'You have exceeded your subscription limit. You have no subscription left for SubscriptionId' . $request['subscriptionId'] . '!!');
        } else {
//            $result = $this->categoryProductModel->setMainSponsoredInstitutes($request['cityId'], $request['stateId'], $request['instituteId'], $request['criterionId'], $request['subscriptionStartDate'], $request['subscriptionEndDate'], $request['subscriptionId'], $request['loggedInUserId'], $product);
            $result = $this->categoryProductModel->setMainSponsoredInstitutes($request, $product);
            if ($result !== false && $result['result'] !== "Failure") {
                $consumeResult            = $objSubs->consumeSubscription(1, $request['subscriptionId'], $remainingQuant, $request['clientUserId'], $request['loggedInUserId'], $request['baseProdId'], $result['result'], $consumeSubscriptionText[$product], $request['subscriptionStartDate'], $request['subscriptionEndDate']);
//                $ListingClientObj         = new Listing_client();
//                $listingExtensionResponse = $ListingClientObj->extendExpiryDate(1, $request['instituteId'], 'institute', $request['subscriptionEndDate']);
                $result['result'] = 'Success';
            } else {
                $result['result'] = 'Failure';
            }
        }


        return $result;
    }

    private function getLocationName(& $mainInstitutes)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

        foreach($mainInstitutes as $oneIndex => $oneResult){

            if($oneResult['city_id'] > 0){
                if($oneResult['city_id'] == 1){
                    $mainInstitutes[$oneIndex]['location'] = "All India";
                } else {
                    $city = $locationRepository->findCity($oneResult['city_id']);
                    $mainInstitutes[$oneIndex]['location'] = $city->getName();
                }
            }

            if($oneResult['state_id'] > 0){
                $state = $locationRepository->findState($oneResult['state_id']);
                $mainInstitutes[$oneIndex]['location'] = $state->getName();
            }
        }

    }

    public function unsetMainSponsoredInstitutes($idsToUnset=array(), $userId=0, $product='main')
    {
        return $this->categoryProductModel->unsetMainSponsoredInstitutes($product, $idsToUnset, $userId);
    }

    public function getMainSponsoredInstitutes($product, $clientId, $cityId=0, $stateId=0, $criterionId=0)
    {
        $instituteDetails =  $this->categoryProductModel->getMainSponsoredInstitutes($product, $clientId, $cityId, $stateId, $criterionId);
        $this->getLocationName($instituteDetails);

        foreach($instituteDetails as $oneIndex => $oneInstitute){
            $institute = $this->instituteRepo->find($oneInstitute['institute_id']);
            $instituteDetails[$oneIndex]['institute_name'] = $institute->getName();
        }

        return $instituteDetails;
    }

    public function addStickyListing($data, $loggedInUserId)
    {
        $appId = 1;
        $this->CI->load->library('Subscription_client');
        $objSumsManage       = new Subscription_client();
        $subscriptionDetails = $objSumsManage->getSubscriptionDetails($appId, $data['subscriptionid']);
        $sumsUserId          = 2492;
        $data['startdate']    = $subscriptionDetails[0]['SubscriptionStartDate'];
        $data['enddate']      = $subscriptionDetails[0]['SubscriptionEndDate'];
        $baseProdId          = $subscriptionDetails[0]['BaseProductId'];
        $remainingQuant      = $subscriptionDetails[0]['BaseProdRemainingQuantity'];
        $consumeResult       = $objSumsManage->consumeSubscription($appId, $data['subscriptionid'], $remainingQuant, $data['clientid'], $sumsUserId, $baseProdId, 1, 'StickyListing', $data['startdate'], $data['enddate']);

        return $this->categoryProductModel->addStickyListing($data, $loggedInUserId);
    }

    public function getAllShoshkeles($clientId)
    {
        $shoshkeleDetails = $this->categoryProductModel->getAllShoshkeles($clientId);
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

        $shoshkeleList = array();
        foreach($shoshkeleDetails as $oneIndex => $oneResult){ // Make entries for a banner id grouped as members of array

            if($oneResult['city_id'] > 0){
                if($oneResult['city_id'] == 1){
                    $shoshkeleDetails[$oneIndex]['location'] = "All India";
                } else {
                    $city = $locationRepository->findCity($oneResult['city_id']);
                    $shoshkeleDetails[$oneIndex]['location'] = $city->getName();
                }
            }

            if($oneResult['state_id'] > 0){
                $state = $locationRepository->findState($oneResult['state_id']);
                $shoshkeleDetails[$oneIndex]['location'] = $state->getName();
            }

            if(!array_key_exists($oneResult['bannerid'], $shoshkeleList)){
                $shoshkeleList[$oneResult['bannerid']][0] = $shoshkeleDetails[$oneIndex];
            } else {
                $shoshkeleList[$oneResult['bannerid']][] = $shoshkeleDetails[$oneIndex];
            }
        }

        unset($shoshkeleDetails);
        return $shoshkeleList;
    }

    public function getLocationByTier($tiers)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

        $cityIds = array();
        $stateIds = array();
        //get cities by input tier
        if(!empty($tiers['city_tier'])){
            $cities = $locationRepository->getCitiesByMultipleTiers(array($tiers['city_tier']),2);
            if($tiers['city_tier'] == $this->ALL_INDIA_TIER){
                $cityIds[1] = array(
                    'city_id' => 1,
                    'city_name' => 'All India'
                );
            }
            foreach($cities[$tiers['city_tier']] as $oneCity){
                $cityIds[$oneCity->getId()] = array(
                    'city_id' => $oneCity->getId(),
                    'city_name' => $oneCity->getName()
                );
            }
        }

        //get states by input tier
        if(!empty($tiers['state_tier'])){
            $states = $locationRepository->getStatesByMultipleTiers(array($tiers['state_tier']),2);

            foreach($states[$tiers['state_tier']] as $oneState){
                $stateIds[] = array(
                    'state_id' => $oneState->getId(),
                    'state_name' => $oneState->getName()
                );
            }
        }

        return array(
            'cities' => array_values($cityIds),
            'states' => array_values($stateIds)
        );
    }

    public function addShoshkele($data, $userId)
    {
        $appId = 1;
        $this->CI->load->library('Subscription_client');
        $objSumsManage       = new Subscription_client();
        $subscriptionDetails = $objSumsManage->getSubscriptionDetails($appId, $data['subscriptionId']);
        $data['startDate']     = $subscriptionDetails[0]['SubscriptionStartDate'];
        $data['endDate']       = $subscriptionDetails[0]['SubscriptionEndDate'];
        $baseProdId           = $subscriptionDetails[0]['BaseProductId'];
        $remainingQuantity       = $subscriptionDetails[0]['BaseProdRemainingQuantity'];
        if($remainingQuantity > 0){

            $result = $this->categoryProductModel->addShoshkele($data, $userId);
            if ($result !== false && $result['result'] !== "Failure") {
                $consumeResult        = $objSumsManage->consumeSubscription($appId, $data['subscriptionId'], $remainingQuantity, $data['clientId'], $userId, $baseProdId, 1, 'StickyListing', $data['startDate'], $data['endDate']);
                $result['result'] = 'Success';
            } else {
                $result['result'] = 'Failure';
            }
        } else {
            $result = array('error' => 'You have exceeded your subscription limit. You have only' . $remainingQuantity . ' subscriptions left in SubscriptionId' . $data['subscriptionId'] . '!!');
        }
        return $result;
    }

    public function uploadShoshkele($bannerId, $shoshkeleUrl, $bannerName, $clientId)
    {
        return $this->categoryProductModel->uploadShoshkele($bannerId, $shoshkeleUrl, $bannerName, $clientId);
    }

    public function removeShoshkele($type, $shoshkeleId, $userid)
    {
        return $this->categoryProductModel->removeShoshkele($type, $shoshkeleId, $userid) === true ? 'success' : 'failure';
    }

    public function getUsedShoshkeleDetails($clientId, $cityId, $stateId, $criterionId)
    {
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();

        $shoshkeleDetails = $this->categoryProductModel->getUsedShoshkeleDetails($clientId);

        $cities = array();
        $states = array();
        $criteria = array();
        $shoshkeles = array();

        foreach($shoshkeleDetails as $oneIndex => $oneShoshkele){
            if($oneShoshkele['city_id'] > 0){
                if($oneShoshkele['city_id'] == 1){
                    $cityName = "All India";
                } else {
                    $city = $locationRepository->findCity($oneShoshkele['city_id']);
                    $cityName = $city->getName();

                }
                $cities[$oneShoshkele['city_id']] = array(
                    'city_id' => $oneShoshkele['city_id'],
                    'city_name' => $cityName
                );
            }

            if($oneShoshkele['state_id'] > 0){
                $state = $locationRepository->findState($oneShoshkele['state_id']);
                $states[$oneShoshkele['state_id']] = array(
                    'state_id' => $oneShoshkele['state_id'],
                    'state_name' => $state->getName()
                );
            }

            $criteria[$oneShoshkele['criterion_id']] = array(
                'criterion_id' => $oneShoshkele['criterion_id'],
                'criterion_name' => $oneShoshkele['criterion_name']
            );

            if( ( $cityId == $oneShoshkele['city_id'] || $stateId == $oneShoshkele['state_id'] ) && $criterionId == $oneShoshkele['criterion_id']){
                $shoshkeles[$oneShoshkele['banner_link_id']] = array(
                    'shoshkele_id' => $oneShoshkele['banner_link_id'],
                    'shoshkele_name' => $oneShoshkele['bannername']
                );
            }
        }

        return array(
            'criteria' => array_values($criteria),
            'states' => array_values($states),
            'cities' => array_values($cities),
            'shoshkeles' => array_values($shoshkeles)
        );
    }

    public function getCoupledData($clientId, $criterionId, $cityId, $stateId)
    {

        $coupledData = $this->categoryProductModel->getCoupledData($clientId, $cityId, $stateId, $criterionId);

        foreach($coupledData as $oneIndex => $oneInstitute){
            $institute = $this->instituteRepo->find($oneInstitute['institute_id']);
            $coupledData[$oneIndex]['institute_name'] = $institute->getName();
        }

        return $coupledData;
    }

    public function couple($listingSubscriptionId, $bannerLinkId)
    {
        return $this->categoryProductModel->couple($listingSubscriptionId, $bannerLinkId);
    }

    public function decouple($couplingId)
    {
        return $this->categoryProductModel->decouple($couplingId);
    }

    public function getBannerKeywords() {
        return $this->categoryProductModel->getBannerKeywords();
    }
}