<?php
/*
 * Controller for study abroad ranking pages
 *
 */

    class AbroadRanking extends MX_Controller{
    
        private $rankingLib;
        private $userStatus;
        private $validateUser;
        private $rankingPageBuilder;
        private $rankingPageRepository;
        private $checkIfLDBUser;

        public function __construct()
        {
            parent::__construct();
            $this->validateUser = $this->checkUserValidation();
            if($this->validateUser !== 'false') {
                $this->load->model('user/usermodel');
                $usermodel = new usermodel;
                
                $userId 	= $this->validateUser[0]['userid'];
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
                    $this->checkIfLDBUser = $isLoggedInLDBUser;
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
            $this->userStatus = $loggedInUserData;
            //$this->config->load('ranking_config');
            $this->load->builder('RankingPageBuilder', 'abroadRanking');
            $this->rankingPageBuilder = new RankingPageBuilder;
            // common ranking library 
            $this->rankingLib 	= $this->rankingPageBuilder->getRankingLib();
            // ranking page repository
            $this->rankingPageRepository = $this->rankingPageBuilder->getRankingPageRepository($this->rankingLib);
        }
       private function _validateRankingId($rankingId)
       {
         /*
            *validate ranking ID
            */
             if(strpos($rankingId, '-') === false) {
                $rankingId = $rankingId;
            } else {
                $rawData = explode("-", $rankingId);
                $rankingId = $rawData[0];
            }

           return $rankingId;
       }

        public function rankingPage($rankingId)
        {
            $rankingId = $this->_validateRankingId($rankingId);
           
            $rankingPageObject = $this->rankingPageRepository->find($rankingId);
            if(!$rankingPageObject){
                show_404_abroad();
            }
            $rankingPageObject = reset($rankingPageObject);
            $recommendedUrl     = $this->rankingLib->getRankingUrl($rankingPageObject);
            $this->rankingLib->validateRankingPageURL($recommendedUrl);
            
            if($this->rankingLib->isZeroResultPage($rankingPageObject))
            {
                $abroadCategoryPageReqObj       = $this->load->library('categoryList/AbroadCategoryPageRequest');
                $url = $abroadCategoryPageReqObj->getURLForCountryPage($rankingPageObject->getCountryId());
                redirect($url, 'location', 301);
            }
            
            $displayData = array();
            $displayData = array_merge($displayData,array('validateuser'=>$this->validateUser,'loggedInUserData'=>$this->userStatus,'checkIfLDBUser'=>$this->checkIfLDBUser));
            
            //Get SEO related data
            $seoData                    = $this->rankingLib->getSeoInfo($rankingPageObject);
            $seoData['canonicalUrl']    = $recommendedUrl;
            $displayData['seoData']     = $seoData;
            
            $rankingPageClassObj                  = $this->rankingPageBuilder->getAbroadRankingPage($rankingPageObject->getId());
            $displayData['rankingPageObject']     = $rankingPageObject;
            $displayData["highestSelectionOrder"] = '';
            $displayData["filterSelectionOrder"]  = '';
            $displayData['ajaxurl']               = $recommendedUrl;
            
            if($rankingPageObject->getType() != 'university')
            {
               
               $displayData['isAjaxCall']       = $this->input->post("AJAX");               
               //Handle Deleted University Case From the Ranking Page Object
               $rankingPageDataArr              =  $rankingPageObject->getRankingPageData(); 
               $this->rankingLib->deletedUniversityRemoval($rankingPageDataArr,$rankingPageObject,$rankingId);              

                //Start Load filter and sorter here
              // $this->_getFilteredData($displayData,$rankingPageObject,$rankingPageClassObj);
           
            //   $this->_getSortingData($displayData,$rankingPageObject,$rankingPageClassObj,$rankingId);
//               $this->_getFilterSelectionOrderData($displayData,$rankingPageClassObj);
               
               $displayData['rankingLibObj'] = $this->rankingLib;
               $displayData['rankingPageObject'] = $rankingPageObject;
            }
            
            $displayData['noOfCourses'] = count($rankingPageObject->getRankingPageData());

            // get country on which ranking page was created
            $country = $this->rankingLib->getCountryById($rankingPageObject->getCountryId());
            $displayData['country'] = $country;

            //breadcrumb
            $displayData['breadCrumbData'] = $this->_getBreadCrumb($rankingPageObject);
            $displayData['trackForPages'] = true;
            $displayData['googleRemarketingParams'] = $this->_getGoogleRemarketingParams($rankingPageObject);

            //tracking
            $this->_prepareTrackingData($displayData);
            $this->load->view("rankingPageOverview",$displayData);
        }


        private function _prepareTrackingData(&$displayData,$rankingId)   
        { 
            $rankType = $displayData['rankingPageObject']->getType();
            $rankId = $displayData['rankingPageObject']->getId();
            $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => $rankType.'RankingPage',
                                              'pageEntityId' => $rankId,
                                              'extraData' => array(
                                                                    'categoryId' => $displayData['rankingPageObject']->getParentCategoryId(),
                                                                    'subCategoryId' => $displayData['rankingPageObject']->getSubCategoryId(),
                                                                    'LDBCourseId' => $displayData['rankingPageObject']->getLDBCourseId(),
                                                                    //'cityId' => 0,
                                                                    //'stateId' => 0,
                                                                    'countryId' => $displayData['rankingPageObject']->getCountryId(),
                                                                    //'courseLevel' => $courseLevel
                                                                )
                                              );
        }
        
        private function _getGoogleRemarketingParams($rankingPageDataObj) {
            $googleRemarketingParams = array(
                                       "categoryId"     => 1,
                                       "subcategoryId"  => 1,
                                       "desiredCourseId"=> 1,
                                       "countryId"      => ($rankingPageDataObj->getCountryId() == '' ? 1 : $rankingPageDataObj->getCountryId()),
                                       "cityId"         => 1
                                       );
            if($rankingPageDataObj->getSubCategoryId() > 0){
                $subcategory    =   $this->rankingLib->getCategoryById($rankingPageDataObj->getSubCategoryId());
                $googleRemarketingParams['subcategoryId']   = $rankingPageDataObj->getSubCategoryId();
                $googleRemarketingParams['categoryId']      = $this->rankingLib->getCategoryById($subcategory->getParentId())->getId();
            }else{
                $googleRemarketingParams['desiredCourseId'] = $rankingPageDataObj->getLDBCourseId();
            }
            return $googleRemarketingParams;         
        }
       
        private function _getBreadCrumb($rankingPageObject){
            $categoryPageRequest = $this->load->library('categoryList/AbroadCategoryPageRequest');
            $countryObj = $this->rankingLib->getCountryById($rankingPageObject->getCountryId());
            $countyName = ($countryObj->getName() == 'All')? 'abroad':$countryObj->getName();
            
            // Inserting url for StudyAbroad home :: to be common for university and course
            $breadCrumbData[] = array('title' => 'Home' , 'url' => SHIKSHA_STUDYABROAD_HOME);
            
            // This section add new Country Home Page in breadcrumb
            $countryHomeLib	= $this->load->library('countryHome/CountryHomeLib');
            if($countryObj->getName() == 'All'){
                $countryHomeUrl = $countryHomeLib->getAllCountryHomeUrl();
                $breadCrumbData[] = array('title' => 'All countries','url' => $countryHomeUrl);
            }else{
                $countryHomeUrl = $countryHomeLib->getCountryHomeUrl($countryObj);
                $breadCrumbData[] = array('title' => 'Study in '.$countryObj->getName(),'url' => $countryHomeUrl);
            }
            
            // Inserting url for University Page :: to be common for university and course
            //$breadCrumbData[] = array('title' => 'Universities in '.$countyName , 'url' => $categoryPageRequest->getURLForCountryPage($countryObj->getId()));
            if($rankingPageObject->getType() == 'university'){
                // Last title for university ranking
                $breadCrumbData[] = array('title' => 'Top ranked universities in '.$countyName);
            }else{
                $ldbCourseName = $this->rankingLib->getLDBCourse($rankingPageObject->getLDBCourseId())->getCourseName();
                if($rankingPageObject->getSubCategoryId() == 0 && $rankingPageObject->getParentCategoryId()==0){
                    // Last title for course ranking when Desired Course is selected :: SEO recommended default title for page
                    $breadCrumbData[] = array('title' => 'Top ranked '.$ldbCourseName.' Colleges in '.$countyName);
                }elseif($rankingPageObject->getSubCategoryId() == 0 && $rankingPageObject->getParentCategoryId()!=0){

                    $categoryObj = $this->rankingLib->getCategoryById($rankingPageObject->getParentCategoryId());
                    $courseName = $categoryObj->getName();
                    $specializationName = $categoryObj->getName();
                    // Last title for course ranking when combination of course type,category and sub-category is selected :: SEO recommended default title for page
                    $breadCrumbData[] = array('title' => 'Top Colleges for '.$ldbCourseName.' of '.$courseName.' from '.$countyName);
                }
                else{
                    $subCategoryObj = $this->rankingLib->getCategoryById($rankingPageObject->getSubCategoryId());
                    $categoryObj = $this->rankingLib->getCategoryById($subCategoryObj->getParentId());
                    $courseName = $categoryObj->getName();
                    $specializationName = $subCategoryObj->getName();
                    
                    // Last title for course ranking when combination of course type,category and sub-category is selected :: SEO recommended default title for page
                    $breadCrumbData[] = array('title' => 'Top Colleges for '.$ldbCourseName.' of '.$courseName.' in '.$specializationName.' from '.$countyName);
                }
            }
            return $breadCrumbData;
        }
       
       
       function formatFiltersForRankingPage(& $displayData) {
        // get generated filters for category page
        $feeRangesForFilter 		= $displayData['filters']['fees']->getFilteredValues();
        $examsForFilters 		= $displayData['filters']['exams']->getFilteredValues();
        $specializationForFilters 	= $displayData['filters']['coursecategory']->getFilteredValues();
        $citiesForFilters 		= $displayData['filters']['city']->getFilteredValues();
        $stateForFilters 		= $displayData['filters']['state']->getFilteredValues();
        $countryForFilters 		= $displayData['filters']['country']->getFilteredValues();
        
        // get filters generated after filters are applied on category page
	if(!empty($displayData['appliedFilters']))
	{
	    $userAppliedFeeRangesForFilter 	 = $displayData['userAppliedfiltersForFees']['fees']->getFilteredValues();
            //_p($userAppliedFeeRangesForFilter);
	    $userAppliedExamsForFilters 	 = $displayData['userAppliedfiltersForExam']['exams']->getFilteredValues();
	    $userAppliedSpecializationForFilters = $displayData['userAppliedfiltersForSpecialization']['coursecategory']->getFilteredValues();
	    $userAppliedCitiesForFilters 	 = $displayData['userAppliedfiltersForLocation']['city']->getFilteredValues();
	    $userAppliedStateForFilters 	 = $displayData['userAppliedfiltersForLocation']['state']->getFilteredValues();
	    $userAppliedCountryForFilters 	 = $displayData['userAppliedfiltersForLocation']['country']->getFilteredValues();
	}
	else
	{
	    $userAppliedFeeRangesForFilter 	 = $displayData['userAppliedfilters']['fees']->getFilteredValues();
	    $userAppliedExamsForFilters 	 = $displayData['userAppliedfilters']['exams']->getFilteredValues();
	    $userAppliedSpecializationForFilters = $displayData['userAppliedfilters']['coursecategory']->getFilteredValues();
	    $userAppliedCitiesForFilters 	 = $displayData['userAppliedfilters']['city']->getFilteredValues();
	    $userAppliedStateForFilters 	 = $displayData['userAppliedfilters']['state']->getFilteredValues();
	    $userAppliedCountryForFilters 	 = $displayData['userAppliedfilters']['country']->getFilteredValues();
	}

        //Sort all the filters
	asort($feeRangesForFilter);
	asort($examsForFilters); //As exam filters will be sorted by result count
	asort($specializationForFilters);//As specialization filters will be sorted by result count 
	asort($citiesForFilters);
	asort($stateForFilters);
	asort($countryForFilters);
	
	// sort all the user applied filters
	asort($userAppliedFeeRangesForFilter);
        //asort($userAppliedExamsForFilters);  //As exam filters will be sorted by result count
        //asort($userAppliedSpecializationForFilters); //As specialization filters will be sorted by result count 
        asort($userAppliedCitiesForFilters);
        asort($userAppliedStateForFilters);
        asort($userAppliedCountryForFilters);
	
	if(!is_array($userAppliedExamsForFilters))  	$userAppliedExamsForFilters = array();
	if(!is_array($examsForFilters))    		$examsForFilters = array();
	if(!is_array($specializationForFilters))    	$specializationForFilters = array();
	if(!is_array($citiesForFilters))           	$citiesForFilters = array();
	if(!is_array($stateForFilters))           	$stateForFilters = array();
	if(!is_array($countryForFilters))           	$countryForFilters = array();
	
	if(!is_array($userAppliedExamsForFilters))  		$userAppliedExamsForFilters = array();
	if(!is_array($userAppliedSpecializationForFilters))    	$userAppliedSpecializationForFilters = array();
	if(!is_array($userAppliedCitiesForFilters))           	$userAppliedCitiesForFilters = array();
	if(!is_array($userAppliedStateForFilters))           	$userAppliedStateForFilters = array();
	if(!is_array($userAppliedCountryForFilters))           	$userAppliedCountryForFilters = array();

        // code for sorting the enabled filters to the top
	$feeRangesForFilter		= $userAppliedFeeRangesForFilter	+ $feeRangesForFilter;
	$examsForFilters 		= $userAppliedExamsForFilters 		+ $examsForFilters;
	$specializationForFilters 	= $userAppliedSpecializationForFilters 	+ $specializationForFilters;
	$citiesForFilters 		= $userAppliedCitiesForFilters 		+ $citiesForFilters;
	$stateForFilters 		= $userAppliedStateForFilters 		+ $stateForFilters;
	$countryForFilters 		= $userAppliedCountryForFilters 	+ $countryForFilters;
	
        //For the fees filter, send "Any Fees" to the bottom
        if(reset($feeRangesForFilter) == "Any Fees"){
	    $feeRangesForFilter = array_slice($feeRangesForFilter,1,count($feeRangesForFilter)-1,true) + array(reset(array_keys($feeRangesForFilter))=>reset($feeRangesForFilter));
	}
        
        //send them to view
        $displayData['feeRangesForFilter']      = $feeRangesForFilter;
        $displayData['examsForFilter']          = $examsForFilters;
        $displayData['specializationForFilters']= $specializationForFilters;
        $displayData['citiesForFilters']        = $citiesForFilters;
        $displayData['statesForFilters']        = $stateForFilters;
        $displayData['countriesForFilters']     = $countryForFilters;
        
        //code for getting the url of page for country in filter
        $abroadCategoryPageReqObj 	= $this->load->library('categoryList/AbroadCategoryPageRequest');
        foreach($displayData['countriesForFilters'] as $countryId=>$country)
        {
         //_p($displayData['rankingPageObject']);
          if($displayData['rankingPageObject']->getSubCategoryId() >0)
          {
            $subCategoryObj = $this->rankingLib->getCategoryById($displayData['rankingPageObject']->getSubCategoryId());
            $categoryObj = $this->rankingLib->getCategoryById($subCategoryObj->getParentId());
            $ldbCourseObj = $this->rankingLib->getLDBCourse($displayData['rankingPageObject']->getLDBCourseId());
            $subCategoryId = $subCategoryObj->getId();
            $categoryId = $categoryObj->getId();
            $courseLevel = $ldbCourseObj->getCourseName();
            $LDBCourseId = 1;
          }else{
            $categoryId = 1;
            $subCategoryId = 1;
            $LDBCourseId = (int) (( $displayData['rankingPageObject']->getLDBCourseId() == "" || $displayData['rankingPageObject']->getLDBCourseId()==0 )? 1 : $displayData['rankingPageObject']->getLDBCourseId());
          }
          
          $data['categoryId'] = (int) ($categoryId);
          $data['subCategoryId'] = (int) ($subCategoryId);
          $data['LDBCourseId'] = (int) ($LDBCourseId);
          $data['cityId'] = 1;
          $data['stateId'] = 1;
          $data['countryId'] = array($countryId);
          $data['pageNumber'] = 1;
          $data['courseLevel'] = $courseLevel;
          $abroadCategoryPageReqObj->setData($data);
          $countryUrl[$countryId] = $abroadCategoryPageReqObj->getURL();
        }
        $displayData['countryUrl'] = $countryUrl;
        
        $displayData['userAppliedFeeRangesForFilter']      = $userAppliedFeeRangesForFilter;
        $displayData['userAppliedExamsForFilters']         = $userAppliedExamsForFilters;
        $displayData['userAppliedSpecializationForFilters']= $userAppliedSpecializationForFilters;
        $displayData['userAppliedCitiesForFilters']        = $userAppliedCitiesForFilters;
        $displayData['userAppliedStateForFilters']         = $userAppliedStateForFilters;
        $displayData['userAppliedCountryForFilters']       = $userAppliedCountryForFilters;
    }
    
    
   
    
    public function _getFilterSelectionOrderData(& $displayData,$rankingPageClassObj)
    {
	// get filter order
	$filterSelectionOrder = $rankingPageClassObj->getFilterSelectionOrder();

	// get the present highest order of the selection order
	$highestSelectionOrder = 1;
	$filterSelectionOrderObj = json_decode($filterSelectionOrder);
	foreach( $filterSelectionOrderObj as $filtername=>$filtervalueArr)
	{
	    foreach( $filtervalueArr as $filteroptionvalue=>$optionrank)
		$highestSelectionOrder = $optionrank > $highestSelectionOrder ? $optionrank+1 : $highestSelectionOrder;
	    
	}
	
	$filterSelectionOrder = empty($filterSelectionOrder) ? "{}" : $filterSelectionOrder;
	
	$displayData["highestSelectionOrder"] = $highestSelectionOrder;
	$displayData["filterSelectionOrder"]  = $filterSelectionOrder;
    }
    
    
    
    public function _getFilteredData(& $displayData,& $rankingPageObject,& $rankingPageClassObj){
      
            $rankingPageClassObj->_loadFilters($rankingPageObject);
            $filters = $rankingPageClassObj->getFilters();
            $displayData['filters'] = $filters;
            $rankingPageClassObj->_removeUncessarySelectedFilters();
            
            // get filters seperately for each filter category
            $filterArr = array("exam","specialization","location","fees");
            $appliedFilters = $rankingPageClassObj->getAppliedFilters();
            
            foreach($filterArr as $value)
                {
                  if(empty($appliedFilters))
                          break;
                  $rankingPageObjectClone = unserialize(serialize($rankingPageObject));
                  $rankingPageClassObj->_applyUserFiltersForIndividualFilter($rankingPageObjectClone, $value);
                }
            $rankingPageClassObj->_applyUserFilters($rankingPageObject);
            
            $userAppliedfilters = $rankingPageClassObj->getUserAppliedFilters();
            // set all filters computed for individual section
            $displayData['userAppliedfilters'] 			= $userAppliedfilters;
            $displayData['userAppliedfiltersForSpecialization']	= $rankingPageClassObj->getUserAppliedFiltersForSpecialization();
            $displayData['userAppliedfiltersForExam']		= $rankingPageClassObj->getUserAppliedFiltersForExam();
            $displayData['userAppliedfiltersForLocation']	        = $rankingPageClassObj->getUserAppliedFiltersForLocation();
            $displayData['userAppliedfiltersForFees']		= $rankingPageClassObj->getUserAppliedFiltersForFees();
            $displayData['appliedFilters'] 			= $rankingPageClassObj->getAppliedFilters();
            $this->formatFiltersForRankingPage($displayData);
            $displayData['isAllCountryPage'] = $this->rankingLib->isAllCountryPage($rankingPageObject->getCountryId());
      
    }
    
    public function _getSortingData(& $displayData,& $rankingPageObject,& $rankingPageClassObj,$rankingId){
         $displayData['isSortAJAXCall'] =  $this->rankingLib->isSortAJAXCall();
         $sortingCriteria =  $this->rankingLib->getSortingCriteria($rankingId);
         if(!empty($sortingCriteria)) {
             $displayData['sortingCriteria']['sortBy'] = $sortingCriteria['sortBy'];
             $displayData['sortingCriteria']['order'] = $sortingCriteria['params']['order'];
             $rankingPageClassObj->_sortCourses($rankingPageObject,$sortingCriteria);
         }
         else{
                 //sort free Courses on view count basis
                 $sortingCriteria['sortBy'] = 'rank';
                 $sortingCriteria['params']['order'] = 'ASC';
                 $rankingPageClassObj->_sortCourses($rankingPageObject, $sortingCriteria);
         } 
    }
  
   }
?>
