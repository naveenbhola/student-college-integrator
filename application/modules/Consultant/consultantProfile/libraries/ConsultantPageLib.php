<?php
/*
 * Library for study abroad consultant profile page
 */

class ConsultantPageLib {
    private $CI                     ;    
    private $consultantModel        ;
    private $consultantPageBuilder  ;
    
    
    function __construct()
    {
        $this->CI =& get_instance();
        $this->_setDependecies();
    }
    
    /*
     * initialize builder & model
     */
    private function _setDependecies()
    {
        $this->consultantModel     = $this->CI->load->model('consultantProfile/consultantmodel');
        //$this->CI->load->builder("ConsultantPageBuilder", "consultantProfile");
        //$this->consultantPageBuilder   = new ConsultantPageBuilder();
    }
    
    /*
     * function to check if user is a first time visitor based on cookie
     * (used to show help arrows on consultant page if user visits first time)
     */
    public function checkIfFirstTimeVisitor(& $displayData)
    {
        $cookie_val = json_decode($_COOKIE['SACPFirstTimeVisitor'],TRUE);
        
        if($cookie_val['SAConsultantFirstTimeVisitor'] == 1){
            $showGutterHelpText = 0;
        }
        else {
            $showGutterHelpText = 1;
            $cookie_val['SAConsultantFirstTimeVisitor'] = 1;
            setcookie("SACPFirstTimeVisitor", json_encode($cookie_val), time()+60*60*24*30, "/", COOKIEDOMAIN);
        }
        return $showGutterHelpText;
    }
    
    public function calculateViewAvg($courseArr,$uniViewCount){
        $avgCount = ($uniViewCount+(array_sum($courseArr)/count($courseArr)));
        return $avgCount;
        } 
    
    public function prepareDataForCollegesRepresentedTab(& $displayData){
        
        $mappedUniversities = $displayData['consultantObj']->getUniversitiesMapped();
        $displayData['collegesRepresentedTabFlag'] = false;
        
        $univIds = array_map(function($obj){ return $obj['universityId'];},$mappedUniversities);
        $univIds = array_unique($univIds);
        if(count($univIds)>0){
            $this->CI->load->builder('ListingBuilder','listing');
            $listingBuilder 			= new ListingBuilder;
            $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
            
            
            $universityDataObj = $this->abroadUniversityRepository->findMultiple($univIds);
        
            $this->abroadListingCommonLib     = $this->CI->load->library('listing/AbroadListingCommonLib');
            $excludedCourseCommentsArr = array();
            $avgViewCount = array();
            
            $univViwCount = $this->abroadListingCommonLib->getViewCountForListingsByDays($univIds,'university',21);
            $uniCoursesIds = $this->abroadListingCommonLib->getCourseIdsOfUniversities($univIds);
            
            foreach($mappedUniversities as $key=>$value)
            {   
                $excludedCourseCommentsArr[$value['universityId']]          = $value['excludedCourseComments'];
                $viewCountArr = $this->abroadListingCommonLib->getViewCountForListingsByDays($uniCoursesIds[$value['universityId']],'course',21);
                $avgViewCountValue = $this->calculateViewAvg($viewCountArr,$univViwCount[$value['universityId']]);
                
                $avgViewCount[$value['universityId']]                       = $avgViewCountValue;
            }
            
            arsort($avgViewCount);
            
            $universityInfo = array();
            
            foreach($avgViewCount as $universityId=>$viewCount)
            {
                $universityInfo[$universityId]['univName']                  = $universityDataObj[$universityId]->getName();
                $universityInfo[$universityId]['univId']                    = $universityId;
                $universityInfo[$universityId]['logo_link']                 = $universityDataObj[$universityId]->getLogoLink();
                $universityInfo[$universityId]['listing_seo_url']           = $universityDataObj[$universityId]->getURL();
                $universityInfo[$universityId]['city']                      = $universityDataObj[$universityId]->getLocation()->getCity()->getName();
                $universityInfo[$universityId]['country']                   = $universityDataObj[$universityId]->getLocation()->getCountry()->getName();
                $universityInfo[$universityId]['countryId']                 = $universityDataObj[$universityId]->getLocation()->getCountry()->getId();
                $universityInfo[$universityId]['countryObj']                = $universityDataObj[$universityId]->getLocation()->getCountry();
                $universityInfo[$universityId]['excludeCourseComment']      = $excludedCourseCommentsArr[$universityId];
                $universityInfo[$universityId]['avgViewCount']              = $viewCount;
                
            }
            $referrerData = $this->_parseReferrerUrlAndGetUniversityId();
            
            if($referrerData['id'] >0)
            {
                if($referrerData['entityType']=='university')
                {
                    $referrerUniversityId                    = $referrerData['id'];
                    $universityRepo                          = $listingBuilder->getUniversityRepository();
                    $universityObj                           = $universityRepo->find($referrerData['id']);
                    $referrerData['referrerCountryName']     =  $universityObj->getLocation()->getCountry()->getName();
                    unset($universityObj);
                }
                elseif($referrerData['entityType']=='department')
                {
                    $abroadInstituteRepo                    = $listingBuilder->getAbroadInstituteRepository();
                    $instituteData                          = $abroadInstituteRepo->find($referrerData['id']);
                    $referrerUniversityId                   = $instituteData->getUniversityId();
                    $referrerData['referrerCountryName']    = $instituteData->getMainLocation()->getCountry()->getName();
                    
                }
                elseif($referrerData['entityType']=='course')
                {
                    $abroadCourseRepo                       = $listingBuilder->getAbroadCourseRepository();
                    $courseData                             = $abroadCourseRepo->find($referrerData['id']);
                    $referrerUniversityId                   = $courseData->getUniversityId();
                    $referrerData['referrerCountryName']    = $courseData->getCountryName();
                }
                if(array_key_exists($referrerUniversityId,$universityInfo))
                {
                    $referrerUniversityTuple[$referrerUniversityId]     = $universityInfo[$referrerUniversityId];
                    unset($universityInfo[$referrerUniversityId]);
                    $universityInfo                                     = $referrerUniversityTuple+$universityInfo;
                }
            }
           $displayData['referrerData']               = $referrerData;
            $displayData['collegesRepresentedTabFlag'] = true;
            $displayData['collegesRepresentedTabData'] = $universityInfo;
        }
   }
   
    /*
     * function to check if services offered tab must be displayed or not
     * prepare data if required
     * @params : any associative array 
     */
    public function prepareDataForServicesOfferedTab(& $data)
    {
        if($data['consultantObj']->hasPaidServices() == 'yes' ||
           $data['consultantObj']->hasTestPrepServices() == 'yes' ||
           $data['consultantObj']->getCEOName() != '' )
        {
            $data['servicesOfferedTabFlag'] = true;
        }
        else{
            $data['servicesOfferedTabFlag'] = false;
        }
    }
   
   public function _parseReferrerUrlAndGetUniversityId()
   {
       $userAgentLib = $this->CI->load->library('user_agent');
        $referrerUrl = $userAgentLib->referrer();
        
        if($referrerUrl !='')
        {
            $referrerUrlArr = explode("?",$referrerUrl);
            $urlElementsArr = explode("-",$referrerUrlArr[0]);
            $universityPageKeyWord = (count($urlElementsArr)>2)?$urlElementsArr[count($urlElementsArr)-2]:'';
            $id = 0;
            $entityType = '';
            if(strtolower($universityPageKeyWord)=='univlisting')
            {
               $entityType = 'university';
               $id  = $urlElementsArr[count($urlElementsArr)-1];
               
            }
            elseif(strtolower($universityPageKeyWord)=='deptlisting'){
                $entityType = 'department';
               $id  = $urlElementsArr[count($urlElementsArr)-1];
                
            }elseif(strtolower($universityPageKeyWord)=='courselisting'){
                $entityType = 'course';
               $id  = $urlElementsArr[count($urlElementsArr)-1];
            }
        }
        return array('entityType'=>$entityType,'id'=>$id);
   }
   
   public function prepareDataForStudentAdmittedTab(& $displayData){
    $studentprofiles = $displayData['consultantObj']->getConsultantStudentProfiles();
    $displayData['studentAdmittedTabFlag'] = false;
    if(count($studentprofiles)>0){
        $displayData['studentAdmittedTabFlag'] = true;
        
        $universityArray = array();
        $profileIdsByUniversity = array();
        
        //Get Exams Master list and arrange them by Exam ID
        $this->abroadListingCommonLib     = $this->CI->load->library('listing/AbroadListingCommonLib');
        $masterExamList = $this->abroadListingCommonLib->getAbroadExamsMasterListFromCache();
        $masterExamListById = array();
        foreach($masterExamList as $key=>$value){
            $masterExamListById[$value['examId']] = $value;
        }
        
        $cityIds = array();
        foreach($studentprofiles as $studentTuple){
            
            $examList = $studentTuple->getProfileExamMapping();
            $sortExamListWithExamName = $this->sortExamMapping($masterExamListById,$examList);
            $studentTuple->__set('profileExamMapping',$sortExamListWithExamName); 
            
            $cityIds[] = $studentTuple->getResidenceCityId();
            
            $graduationMappingData = $studentTuple->getProfileGraduationMapping();
            foreach($graduationMappingData as $key=>$value){
                $cityIds[] = $value['graduationCityId'];
            }
            
            $universityMappingData = $studentTuple->getProfileUniversityMapping();
            foreach($universityMappingData as $key=>$value){
                $universityArray[] = $value['universityId'];
                $profileIdsByUniversity[$value['universityId']][] = $studentTuple->getId();
            }
        }
        $universityArray = array_unique($universityArray);
        $this->CI->load->builder('ListingBuilder','listing');
        $listingBuilder 			= new ListingBuilder;
        $this->abroadUniversityRepository 	= $listingBuilder->getUniversityRepository();
        $mappingUniversityDataObj = $this->abroadUniversityRepository->findMultiple($universityArray);
        $mappingUniversityData = array();
        foreach($mappingUniversityDataObj as $key=>$value){
            $mappingUniversityData[$key]['universityName'] =  $value->getName();
            $mappingUniversityData[$key]['city'] = $value->getLocation()->getCity()->getName();
            $mappingUniversityData[$key]['country'] = $value->getLocation()->getCountry()->getName();
            $mappingUniversityData[$key]['countryId'] = $value->getLocation()->getCountry()->getId();
                
        }
        $displayData['studentAdmittedMappingUniversityData'] = $mappingUniversityData;
        unset($mappingUniversityDataObj);
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder 			= new LocationBuilder;
        $this->locationRepository 	= $locationBuilder->getLocationRepository();
        
        $cityDetails = $this->locationRepository->findMultipleCities($cityIds);
        $displayData['studentAdmittedCityData'] = $cityDetails;
        
        //Now Sort Student Profile By University View Counts
        $universityViwCount = $this->abroadListingCommonLib->getViewCountForListingsByDays($universityArray,'university',21);
        arsort($universityViwCount);
        $this->_sortStudentProfileByuniversityViewCount($universityViwCount,$profileIdsByUniversity,$displayData);
    }    
   }
   
   function _sortStudentProfileByuniversityViewCount($universityViwCount,$profileIdsByUniversity,& $displayData){
    $studentprofiles = $displayData['consultantObj']->getConsultantStudentProfiles();
        $sortedStudentIds = array();
        foreach($universityViwCount as $key=>$value)
        {
           foreach($profileIdsByUniversity[$key] as $profileKeys=>$profileIds)
           {
                $sortedStudentIds[] = $profileIds;
           } 
        }
        $sortedStudentIds = array_unique($sortedStudentIds);
        $newStudentProfileObj = array();
        foreach($sortedStudentIds as $key=>$profileId){
            $studentUniversityMappingData = $studentprofiles[$profileId]->getProfileUniversityMapping();
            
            usort($studentUniversityMappingData,function($c1,$c2) use ($universityViwCount){
                $un1Value = $universityViwCount[$c1['universityId']];
                $un2Value = $universityViwCount[$c2['universityId']];
                return -1*($un1Value - $un2Value);
            });
            $studentprofiles[$profileId]->__set('profileUniversityMapping',$studentUniversityMappingData);
            $newStudentProfileObj[$profileId] = $studentprofiles[$profileId];
        }
        $displayData['consultantObj']->__set('consultantStudentProfiles',$newStudentProfileObj); 
        
   }
   
   public function sortExamMapping($masterExamListById,$examList){
    
    
    $examListWithExamName = array();
    foreach($examList as $key=>$value){
        if($value['examId']=="9999"){
            $value['examName']  = "No Exam Given";
            $value['examScore'] = '';
        }
        else if($value['examId']=="9998"){
            $value['examName']  = "Exam info not available";
            $value['examScore'] = '';
        }
        else{
            $value['examName']  = $masterExamListById[$value['examId']]['exam'];
        }
        $examListWithExamName[$key] = $value;
    }
    $examOrder = array(
                        'SAT' =>  1,
                        'GMAT' =>  2,
                        'GRE'   =>  3,
                        'IELTS'   =>  4,
                        'TOEFL'  =>  5,
                        'PTE'   =>  6,
                        'CAE' =>  7,
                        'CAEL'   =>  8,
                        'MELAB'  =>  9,
                        'No Exam Given'  =>  10
                    );
    usort($examListWithExamName, function ($a,$b) use($examOrder){
                    if($examOrder[$a['examName']] > $examOrder[$b['examName']]){
                        return 1;
                    }else{
                        return -1;
                    }
                });
    return  $examListWithExamName;
   }
	
    public function getRegionBasedOnIP() {
        if (!empty($_SERVER['HTTP_CLIENT_IP'])) {
            $ip = $_SERVER['HTTP_CLIENT_IP'];
        } elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR'])) {
            //$ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
            $ipList = explode(",", $_SERVER['HTTP_X_FORWARDED_FOR']);
            $ip = $ipList[0];
        } else {
            $ip = $_SERVER['REMOTE_ADDR'];
        }
        //_p($_SERVER);die;
        //$ip = '218.248.72.34';
        if($ip){
            $locationDetection = $this->CI->load->library('location/LocationDetection',$ip);
            $locationData = $locationDetection->getLocation();
        }else{
            return array(   'regionId'      => 1,
                            'regionName'    => 'Delhi/NCR'
                        );
        }
        if(empty($locationData)){
            return array(   'regionId'      => 1,
                            'regionName'    => 'Delhi/NCR'
                        );
        }
        //_p($locationData);
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        $citiesArray = $locationRepository->getCities(2,TRUE);
        //_p($citiesArray);
        foreach($citiesArray as $cityObject){
            if(strcasecmp($locationData['city'], $cityObject->getName()) === 0){
                $userCity = $cityObject;
                break;
            }
        }
        
        $consultantPostingLib = $this->CI->load->library('consultantPosting/ConsultantPostingLib');
        $regionMappingData = $consultantPostingLib->getRegionsMappingData();
            
        if(isset($userCity)){ // case when userCity exist in shiksha database
            return array(   'regionId'      => $regionMappingData[$userCity->getId()]['regionId'],
                            'regionName'    => $regionMappingData[$userCity->getId()]['regionName']
                        );
        }elseif($locationData['countryIsoCode'] == 'IN'){ // case when userCity do not exist in shiksha database
            // array with stateId mapped to any one city ID belongs to that state
            $stateMapping = array(  'AN'        =>  10210,
                                    'AP'        =>  197,
                                    'AR'        =>  107,
                                    'AS'        =>  96,
                                    'BR'        =>  171,
                                    'CH'        =>  63,
                                    'CT'        =>  176,
                                    'DN'        =>  70,
                                    'DD'        =>  72,
                                    'DL'        =>  74,
                                    'Goa'       =>  210,
                                    'GJ'        =>  30,
                                    'HR'        =>  36,
                                    'HP'        =>  186,
                                    'JK'        =>  191,
                                    'JH'        =>  180,
                                    'KA'        =>  278,
                                    'KL'        =>  757,
                                    //'Lakshadweep'=>  1980,
                                    'MP'        =>  55,
                                    'MH'        =>  151,
                                    'MN'        =>  105,
                                    'ML'        =>  185,
                                    'MZ'        =>  1100,
                                    'NL'        =>  1918,
                                    'OR'        =>  912,
                                    'PY'        =>  172,
                                    'PB'        =>  37,
                                    'RJ'        =>  109,
                                    'SK'        =>  86,
                                    'TN'        =>  64,
                                    //'Telangana' =>  702,
                                    'TR'        =>  28,
                                    'UP'        =>  138,
                                    'UL'        =>  173,
                                    //'Uttaranchal'=>  '',
                                    'WB'        =>  130
                                    );
            $userCity = $stateMapping[$locationData['mostSpecificSubdivisionIsoCode']];
            if($userCity){ // case handled for Telanagana because in for Telanagan no isoCode found for mostSpecificSubdivisionIsoCode
                if($locationData['mostSpecificSubdivision'] == 'Telangana'){
                    $userCity = 702;
                }
            }
            return array(   'regionId'      => $regionMappingData[$userCity]['regionId'],
                            'regionName'    => $regionMappingData[$userCity]['regionName']
                        );
            
        }else{ // if IP address is detected for Country other than India
            return array(   'regionId'      => 1,
                            'regionName'    => 'Delhi/NCR'
                        );
        }
            
        /*    
        return array(   'regionId'      => 4,
                        'regionName'    => 'West Bengal & NE'
                    );
         * 
         */
    }

    /*
     * function to get consultant location objects by consultant location id
     */
    public function getConsultantLocationsById($consultantId, $consultantLocationIds = array())
    {
        if(!is_array($consultantLocationIds) || count($consultantLocationIds) == 0 || !($consultantId>0))
        {
            return false;
        }
        // load builder
        $this->CI->load->builder('ConsultantPageBuilder', 'consultantProfile');
        $consultantPageBuilder    = new ConsultantPageBuilder;
        // consultant location repository
        $consultantLocationRepository = $consultantPageBuilder->getConsultantLocationRepository();
        // find locations
        return $consultantLocationRepository->findMultipleLocations(array($consultantId=> $consultantLocationIds));
    }

    public function prepareDataForCountriesRepresentedTab(& $displayData)
   {
    
      $countryRep       = array();
      $univCount        = array();
      $countryHomeUrl   = array();
          
      //find distinct countries represented by the consultant and fill the countryRep array
      foreach($displayData['collegesRepresentedTabData'] as $value)
      {
      
        if(!array_key_exists($value['countryId'],$countryRep))
	{
            $countryRep[$value['countryId']]            = $value['country'];
            //$countryHomeUrl[$value['countryId']]        = $countryHomeLib->getCountryHomeUrl($value['countryObj']);     
            $univCount[$value['countryId']]             = 0;
        }
	
        //bucket the universities and count them
        $univCount[$value['countryId']]++;
        
      }      
      //Now sort the countries based on the number of available consultants in those countries besides the given consultant
       $sortedCountryRep =$this->consultantModel->prepareDataForCountriesRepresentedTab($countryRep,$univCount);
               
        //Rearrange the sorting incase we have a referral
        if(!empty($displayData['referrerData']))
        {
            $country = $displayData['referrerData']['referrerCountryName'];
            $country =  strtoupper($country);
            //if the referrer country exists in the array unset it and prepend it to the front
            
            $aux = $sortedCountryRep;
            
            foreach($aux as $k=>$v)
            {
                $aux[$k] = strtoupper($v);
            }
            
            $countryId = array_search($country, $aux);
            
            //change the order incase of referred country
            if($countryId!='')
            {
                $aux = array($countryId => $sortedCountryRep[$countryId]);
                unset($sortedCountryRep[$countryId]);
                $sortedCountryRep = $aux+ $sortedCountryRep;
            }
        }
       //Prepare the display data
       $displayData['countriesRepresentedTabData']['countriesRepresented'] = $sortedCountryRep;
       $displayData['countriesRepresentedTabData']['universitiesCount']    = $univCount;
   }       
    
    /*
     * Sort the array of location objects by the City name alphabatically and group them by City id..
     */
    public function formatLocationData($locationObjectsArray) {
	usort($locationObjectsArray, array('ConsultantPageLib','sortLocationsOnCityName'));

	foreach($locationObjectsArray as $key => $consultantLocation) {
	    $location[$consultantLocation->getCityId()][] = $consultantLocation;
	    if($consultantLocation->isHeadOffice() == "yes") {
		$headOfcCityId = $consultantLocation->getCityId();
	    }
	}    
	
	// Sorting on Locality Name as well..
	foreach($location as $key => $locationObjArray) {
	    if(count($locationObjArray) > 1) {
		usort($locationObjArray, array('ConsultantPageLib','sortLocationsOnLocalityName'));
	    }
	    $finalLocationArray[$key] = $locationObjArray;
	}
	
	unset($location);
	unset($locationObjectsArray);
	return array('headOfcCityId' => $headOfcCityId, 'formatedLocationObjects' => $finalLocationArray);    
    }   
   
    public function sortLocationsOnCityName($a, $b)
    {	
	return $a->getCityName() > $b->getCityName();
    }
    
    public function sortLocationsOnLocalityName($a, $b)
    {	
	return $a->getLocalityName() > $b->getLocalityName();
    }   

    /*
     * function to validate subscription  data & check if consultant has enough credits left(> CPR)
     * @param: consultantId
     */
    public function validateSubscriptionData($consultantId)
    {
        $consultantSubscription = $this->consultantModel->getConsultantSubscriptionDetails($consultantId);
        $this->CI->load->library('subscription_client');
        $objSumsProduct =  new Subscription_client();
        // get its subscription details ..
        $subscriptionDetails = $objSumsProduct->getMultipleSubscriptionDetails(CONSULTANT_CLIENT_APP_ID,array($consultantSubscription['subscriptionId']));
        $subscriptionDetails = reset($subscriptionDetails);
        if(count($subscriptionDetails) && $subscriptionDetails['BaseProdRemainingQuantity'] < $consultantSubscription['costPerResponse']) {
            return false;
        }
        else{
            return $consultantSubscription;
        }
    }
}