<?php
   class CountryHomePage extends MX_Controller{
      
      private $countryHomeLib;
      private $widgetConfigData;
      private $countryObj;
      private $userStatus;
      private $checkIfLDBUser;
      
      public function __construct(){
         parent::__construct();
         // prepare user data
         $this->userStatus = $this->prepareLoggedInUserData();
         $this->countryHomeLib = $this->load->library('countryHome/CountryHomeLib');
         $this->countryHomeSolrRequestGenerator = $this->load->library('countryHome/solr/countryHomeSolrRequestGenerator');
         $this->countryHomeSolrResponseParser = $this->load->library('countryHome/solr/countryHomeSolrResponseParser');
      }
	  
      private function _prepareTrackingData(&$displayData)   
      {      
        if($displayData['breadcrumbData']['1']['title']=='All countries')
        { 
          $pageEntityId = '0';
        }
        else
        { 
          $pageEntityId = $displayData['countryObj']->getId() ;
        }
        //country ID , ldbcourseID 
        $displayData['beaconTrackData'] = array(
                                              'pageIdentifier' => 'countryHomePage',
                                              'pageEntityId' => $pageEntityId,
                                              'extraData' => array(
                                                                    'countryId' => $pageEntityId,
                                                                    'LDBCourseId'=>$displayData['activeCourseId']
                                                                  )
                                                );
        }
		
      public function allCountryHome(){
         // validate URL
         $countryObject = $this->countryHomeLib->validateUrlAndGetCountryInfo('all');
         
         $abroadCategoryPageLib = $this->load->library('categoryList/AbroadCategoryPageLib');
         $abroadCountriesData = $abroadCategoryPageLib->getCountriesHavingUniversities();
         foreach($abroadCountriesData as &$data){
            $abroadCountryObject = $this->countryHomeLib->getCountry(strtolower($data['name']));
            $countryHomeUrl = $this->countryHomeLib->getCountryHomeUrl($abroadCountryObject);
            $data['countryHomeUrl'] = $countryHomeUrl;
         }
         
         $displayData = array();
         $displayData['loggedInUserData']    =  $this->userStatus;
         $displayData['seoData']             = $this->countryHomeLib->getCountryHomeSeoData($countryObject);
         $displayData['breadcrumbData']      = $this->_getBreadCrumb($countryObject->getName());
         $displayData['abroadCountriesData'] = $abroadCountriesData;
         $displayData['trackForPages'] = true;
		
		//tracking
         $this->_prepareTrackingData($displayData);
         $this->load->view('countryHomeAllCountryPage',$displayData);
      }
            
      /*
       * function to get data for all the widgets that are supposed to be rendered on the front end
       */ 
      public function getDataForWidgets($widgetParams)
      {
         // load the config ...
         $this->load->config('abroadCountryHomePageConfig');
         $countryPageWidgetPlacementList = $this->config->item('country_page_widget_placement');
         
         // process the list of widgets to be rendered (against country / manual switching )..
         $countryPageWidgetPlacementList = $this->countryHomeLib->processWidgetList($countryPageWidgetPlacementList,$this->countryObj);
         // save this for later
         $this->widgetConfigData = $countryPageWidgetPlacementList;
         // get relevant data for each widget
         $this->load->aggregator('AbroadWidgetsAggregator','countryHome');
         $widgetAggregator = new AbroadWidgetsAggregator();
         // get widget classes
         $classesArray = array_keys($countryPageWidgetPlacementList);
         $this->load->aggregatorClasses($classesArray,'countryHome');
         
         $this->_setAggregatorSources($widgetAggregator, $countryPageWidgetPlacementList, $widgetParams);
         // get aggregated data from aggregator
         $widgetData = $widgetAggregator->getData();
         // remove keys from array used for rendering widget views
         $removed_keys = array_diff(array_keys($this->widgetConfigData),array_keys($widgetData));
         foreach($removed_keys as $removedKey)
         {
            unset($this->widgetConfigData[$removedKey]);
         }

         return $widgetData;
      }
      
      /*
       * function to set parameters required to get data for each widget
       */ 
      private function _setAggregatorSources($aggregator, $widget_list, $widgetParams) {
	 //$params = array("subCatId" => $subCatId, "userInfo" => $loggedinUserInfo);
         foreach ($widget_list as $key => $widget_object) {
            $widget_class = $key;
            if(class_exists($widget_class)) {
                    $aggregator->addDataSource(new $widget_class($widgetParams));
            }
         }
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
   
   /*
    * controller function for revamped country home pages
    */

    public function combinedAbroadCountryHome($param){
        $this->abroadCommonLib = $this->load->library("listingPosting/AbroadCommonLib");
        // Validate URL
        $this->countryObj = $this->countryHomeLib->validateUrlAndGetCountryInfo($param);
        if( $this->countryObj == NULL ||  $this->countryObj->getId() <= 2){
            show_404_abroad();
        }
        $countryId =  $this->countryObj->getId();
        $displayData = array();
        $displayData['seoData']    = $this->countryHomeLib->getCountryHomeSeoData($this->countryObj);
        $displayData['countryObj']       = $this->countryObj;
        $desiredCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
        $desiredCoursesList = array_column($desiredCourses,"SpecializationId");
        $coursesOnPage = $this->countryHomeLib->getCountryCourseOrder($countryId,$desiredCoursesList);
        $this->_validateCourses($coursesOnPage);
        $displayData['coursesOnPage'] = $coursesOnPage;
        $displayData['activeCourseId'] = reset($coursesOnPage);
        $courseNames = array();
        foreach($desiredCourses as $desiredCourse){
            $courseNames[$desiredCourse['SpecializationId']] = $desiredCourse['CourseName'];
        }
        $displayData['activeCourseName'] = str_replace('BE/Btech', 'BTech', $courseNames[$displayData['activeCourseId']]);
        $displayData['courseNames'] = $courseNames;
        $displayData['countryOverview'] =   $this->countryHomeLib->prepareDataForCountryOverviewWidget($countryId);
        $displayData['trackForPages'] = true; //For JSB9 Tracking
        $this->_prepareTrackingData($displayData);
        $validateuser = $this->checkUserValidation();
        $displayData = array_merge($displayData,array('validateuser'=>$validateuser,'loggedInUserData'=>$this->userStatus,'checkIfLDBUser'=>$this->checkIfLDBUser));

        $popularCourses = array();
        foreach($coursesOnPage as $course){
            $displayData['coursesData'][$course] = $this->abroadCountryHome($course,$courseNames,$coursesOnPage);
            $popularCourses = array_merge($popularCourses, $displayData['coursesData'][$course]['widgetData']['countryHomePopularUniversities'][$course]);
        }
        //make solr call for course listings
        //$this->benchmark->mark('start');
        //_p($popularCourses);die;
        $popularCourses = array_filter($popularCourses);
        $solrReqUrl = $this->countryHomeSolrRequestGenerator->getCourseDataRequestUrl($popularCourses,count($coursesOnPage)*4);
        if($solrReqUrl !== false && !empty($solrReqUrl)){
          $solrClient = $this->load->library("SASearch/AutoSuggestorSolrClient");
          $courseData = $solrClient->getCategoryPageResults($solrReqUrl,'countryHome');
          $courseData = $this->countryHomeSolrResponseParser->parseCourseData($courseData);
          //_p($courseData);
          //$this->benchmark->mark('end');
          //echo "Time Taken : ".$this->benchmark->elapsed_time("start","end");
          $this->addCourseDataWithUniversityData($displayData, $coursesOnPage, $courseData);
        }

        $this->load->view('countryHomePage/countryHomeOverview',$displayData);
    }

    private function _validateCourses($courses){
      if(empty($courses)){
        $allCountryUrl = $this->countryHomeLib->getAllCountryHomeUrl();
        redirect($allCountryUrl, 'location');
      }
    }

    private function addCourseDataWithUniversityData(&$displayData, $courses, $dataFromSolrData){
        foreach ($courses as $course) 
        {
          foreach ($displayData['coursesData'][$course]['widgetData']['countryHomePopularUniversities']['universityData'] as $univId=>&$value) 
          {
            $value['courseName'] = $dataFromSolrData[$value['courseId']]['saCourseName'];
            //$value['url'] .="?refCourseId=".$value['courseId'];
            $value['courseLink'] = $dataFromSolrData[$value['courseId']]['saCourseSeoUrl'];
            $this->countryHomeLib->sortEligibilityExamForCountryHomeCourses($dataFromSolrData);
            $exams = $dataFromSolrData[$value['courseId']]['saCourseEligibilityExams'];
            $count = 0;
            foreach($exams as $exam)
            {
              if($count==2){ break; }
              $examScore = $dataFromSolrData[$value['courseId']]['sa'.$exam.'ExamScore'];
              $value['courseExams'][] = $exam." : ".($examScore == "-1"?"Accepted":$examScore);
              $count++;
            }
            $value['courseFee']  = $dataFromSolrData[$value['courseId']]['saCourseTotalFees'] - $dataFromSolrData[$value['courseId']]['saCourseRemainingFees'];

            $value['courseFee'] = getIndianDisplableAmount($value['courseFee'], 2);
//            $value['courseFee'] = str_replace(array('Thousand','Lakhs','Lakh'), array('K','Lacs','Lac'), $value['courseFee']);
          }
        }
      }

   public function abroadCountryHome($courseId, $courseNames, $coursesOnPage){
         // Parameters required to get widget data
         // $courseId = $this->input->get('course');
         $courseId = $this->_validateCourseId($courseId,$coursesOnPage);
         if($courseId == -1){
            return array();
         }
         $widgetParams = array(
                               'countryId'      => $this->countryObj->getId(),
                               'countryHomeLib' => $this->countryHomeLib,
                               'countryObj'     => $this->countryObj,
                               'courseId'       => $courseId,
                               'courseName'     => $courseNames[$courseId],
             'desiredCoursesOnPage'=>$coursesOnPage
                               );
         
         // Get data for all the widgets	 
         $widgetData = $this->getDataForCountryHomeWidgets($widgetParams);
         $this->countryHomeLib->checkWidgetData($widgetData);
       
         $displayData['widgetData']= $widgetData;
         $displayData['ldbCourseID']=$courseId;
         // $displayData['courseURLs'] = $this->_prepareCountryCourseURLs($coursesOnPage,$this->countryObj);
         
		//tracking
		 $displayData['countryRecommendations'] = $this->countryHomeLib->getRecommendedStudyDestinations($widgetParams);
         // $this->load->view('countryHomePage/countryHomeOverview',$displayData);
         return $displayData;
      }
	  
	  private function _validateCourseId($courseId,$coursesOnPage){
         if(empty($courseId)){
            $courseId = reset($coursesOnPage);
            if(empty($courseId)){
               show_404_abroad();
            }
         }else{
            /*if(!in_array($courseId, $coursesOnPage)){
               $pageURL = $this->countryHomeLib->getCountryHomeUrl($this->countryObj);
               redirect($pageURL,'location',301);
            }*/
            if(!in_array($courseId, $coursesOnPage)){
                $courseId = -1;
            }
         }
         return $courseId;
      }
	  /*
       * function to get data for all the widgets that are supposed to be rendered on the front end
       */ 
      public function getDataForCountryHomeWidgets($widgetParams)
      {
         // load the config ...
         $this->load->config('abroadCountryHomePageConfig');
         $this->widgetConfigData = $this->config->item('country_home_widget_list');
         
         // process the list of widgets to be rendered (against country / manual switching )..
		 $this->widgetConfigData = array_flip(array_keys($this->widgetConfigData, true));
         
         // get relevant data for each widget
         $this->load->aggregator('AbroadWidgetsAggregator','countryHome');
         $widgetAggregator = new AbroadWidgetsAggregator();
         // get widget classes
         $classesArray = array_keys($this->widgetConfigData);
         $this->load->aggregatorClasses($classesArray,'countryHome');
         
         $this->_setAggregatorSources($widgetAggregator, $this->widgetConfigData, $widgetParams);
         // get aggregated data from aggregator
         $widgetData = $widgetAggregator->getData();
         return $widgetData;
      }

      private function _prepareCountryCourseURLs($validCourses,$countryObj){
         $pageURL = $this->countryHomeLib->getCountryHomeUrl($countryObj);
         $courseURLs = array();
         foreach($validCourses as $courseId){
            $courseURLs[$courseId] = $pageURL."?course=".$courseId;
         }
         return $courseURLs;
      }
   }
?>
