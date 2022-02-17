<?php
	/**
	 * Controller for Compare Institute tool
	*/

	class compareInstitutes extends MX_Controller {

		/**
		* Purpose       : Initialization method for Compare Institute tool
		* Params        : 1. Reference of display data array
		* To Do         : none
		* Author        : Ankur Gupta
		*/
		function _init(& $displayData)
		{
			$this->load->helper('image');
			$this->load->helper('string');
		
			 // fetch User Login info
			$displayData['validateuser'] = $this->checkUserValidation ();
			$displayData['trackForPages'] = true;
			
			if($displayData['validateuser'] !== 'false') {
				$this->load->model('user/usermodel');
				$usermodel = new usermodel;
				$validateuser = $displayData['validateuser'][0];
				$userId = $displayData['validateuser'][0]['userid'];
				$user = $usermodel->getUserById($userId);
				if(!is_object($user) || empty($user)) 
					{ 
						$displayData['loggedInUserData'] = false; 
					}
				else{
					$name = $user->getFirstName().' '.$user->getLastName();
					$email = $user->getEmail();
					$userFlags = $user->getFlags();
					$isLoggedInLDBUser = $userFlags->getIsLDBUser();
				
					$displayData['loggedInUserData'] = array('userId' => $userId, 'name' => $name, 'email' => $email, 'isLDBUser' => $isLoggedInLDBUser);
				}
			}
			else {
				$displayData['loggedInUserData'] = false;
			}
		}
		
		/**
		* Purpose       : Main function for the Compare Institute tool
		* Params        : List of Course Ids which needs to be compared
		* To Do         : none
		* Author        : Ankur Gupta
		*/
		function compareInstitutesTool($courseIds = "")
		{
			ini_set('memory_limit', '1024M');
			$displayData = array();
			$this->_init($displayData);
			
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$courseRepository = $listingBuilder->getCourseRepository();
			$this->load->model('listing/institutemodel');
			$institutemodel = new institutemodel;
			$showFeesDiscArr = array(28499,36321);  // To show fee disclaimer for Lovely Professional Univ Courses
			$this->load->helper('url');
	    
			$data = array();
			$parameterSet = explode("-",$courseIds);
			$paramCount = count($parameterSet);
					
			$courseArray = array();
			if($paramCount>0){
				//Fetch the Course Ids
				for ($i=($paramCount-1);$i>=0;$i--){
					if(is_numeric($parameterSet[$i]) && $parameterSet[$i]!='' && count($courseArray)<4){
						array_unshift($courseArray,$parameterSet[$i]);
					}
				}
			}
			
			//Check if this is Static page
			$displayData['isStaticPage'] = false;
			if($paramCount>5){
				$displayData['isStaticPage'] = true;
				$displayData['seoDetails'] = $this->getStaticURLDetailsFromDB( $parameterSet[$paramCount-4], $parameterSet[$paramCount-3],$parameterSet[$paramCount-2], $parameterSet[$paramCount-1] );
			}
			$displayData['courseArray'] = $courseArray;
			
			//for naukri salary data, bring subcat of each course
			$displayData['subCategoryOfCourses'] = array();
			$flagForRedirection = 0;
			foreach ($courseArray as $courseId) {
				$res = $instituteRepository->getCategoryIdsOfListing($courseId, 'course', 'true', TRUE);
				//If any of the courses does not exists, throw 404
				if(!is_array($res))
				{
					$invalidCourseIds[] = $courseId;
					$flagForRedirection = 1;
					// show_404();
					// exit;
				}
				else{
					$displayData['subCategoryOfCourses'][$courseId] = array('subCat'=>$res[$courseId][0]);
				}
			}
			if($flagForRedirection == 1){
				$this->load->helper('compare');
				redirectDeadUrls($invalidCourseIds,$courseArray);
			}
			//If two or more courses are selected, fetch their details, else we will display an empty page
			if(isset($courseArray) && count($courseArray)>0){

				$i = 0;
				$instituteIDs = array();
				$courseIDs = array();
				$selectedCourseArray = array();
				$selectedCourseArray = explode("||",$selectedCourseList);
				$naukri_employees_data = array();

				//Get Sub category of the First course and check if MBA page
				$mainCourseId = $courseArray[0];
				$displayData['showMBA'] = false;
				$subCategoryCourse   = $instituteRepository->getCategoryIdsOfListing($mainCourseId, 'course', 'true', TRUE);

				if(!is_array($subCategoryCourse)){
					show_404();
					exit;
				}

				foreach ( $subCategoryCourse[$mainCourseId] as $subCatVal){
					$displayData['mainCourseSubCatId'] = $subCatVal;
					if($subCatVal=="23"){
						$displayData['showMBA'] = true;
						$displayData['mainCourseSubCatId'] = "23";
						break;
					}
				}
				
				$naukri_employees_data = array();
				$i = 0;
				foreach($courseArray as $element){
					$course = $courseRepository->find($element);
					$this->load->model('listing/coursemodel');
					$isAbroadCourse = $this->coursemodel->isStudyAboradListing($element, 'course');
					if($isAbroadCourse){
						show_404();
						exit;
					}
					//If any course is deleted, show 404
                                        if(!$course->getId()){
                                                show_404();
                                                exit;
                                        }
					$institute = $course->getInstId();
					
					$instituteCourses[$institute] = $this->getCoursesforInstitute(array($institute));  
					$data[$i]['courseId'] = $course->getId();
					$data[$i]['instituteName'] =  $course->getInstituteName();
					$data[$i]['courseName'] =  $course->getName();
					$instituteIDs[$i][$institute] = $data[$i]['courseId'];

					//using for beacon tracking purpose
					$displayData['compareInstituteID'.($i+1)]=$course->getInstId();
					$displayData['compareInstituteName'.($i+1)]=$course->getInstituteName();
					$displayData['compareCourseID'.($i+1)]=$course->getId();
					$displayData['showFeeDisc'][$course->getId()] = in_array($institute, $showFeesDiscArr) ? 1 : 0; 	

					if($displayData['showMBA']){
						//Get the Alumni companies for each Course
						$naukri_employees_data[$course->getId()] = $institutemodel->getNaukriEmployeesDataForCompare($institute,10);
						//Get the Ranking of the course, if available
						if(NEW_RANKING_PAGE) {
							$displayData['rankings'][$course->getId()] = modules::run('listing/ListingPage/getSourceWiseRankingData',$course);
						} else {
							$displayData['rankings'][$course->getId()] = $this->getRankingData($course);
						}
					}
					$i++;
				}
				
				if(NEW_RANKING_PAGE) {
					$sourceIdNameMap = array();
					$courseIdSourceMap = array();
					$courseIds = array();
					foreach($displayData['rankings'] as $courseId => $courseRanks) {
						foreach($courseRanks as $sourceRank) {
							$sourceIdNameMap[$sourceRank['source_id']] = $sourceRank['source_name'];
							$courseIdSourceMap[$courseId][] = $sourceRank['source_id'];
						}
						$courseIds[] = $courseId;
					}
					foreach($sourceIdNameMap as $sourceId => $sourceName) {
						foreach($courseIds as $courseId) {
							if(!in_array($sourceId, $courseIdSourceMap[$courseId])) {
								$displayData['rankings'][$courseId][] = array('source_id' => $sourceId, 'source_name' => $sourceIdNameMap[$sourceId], 'rank' => 'NA');
							}
						}
					}
					foreach($displayData['rankings'] as $courseId => $courseRanks) {
						//uasort($courseRanks, 'sortBySourceId');						
						uasort($courseRanks, array($this,'sortBySourceId'));
						$displayData['rankings'][$courseId] = $courseRanks;
					}
				}

				// Course Object Optimization
				foreach ($instituteIDs as $key => $value) {
					foreach ($value as $instituteId=>$val) {
						$instituteArr = array();
						 $appliedInstitutes[] = $this->getInstituteWithBasicCourseInfoObjs($instituteId, array($val)); 
					}
				}

				$this->load->library('listing/Listing_client');
				$displayData['institutes'] = $appliedInstitutes;
				$displayData['instituteList'] = $data;
				$displayData['request'] = $request;
				$displayData['naukri_employees_data'] = $naukri_employees_data;
				$validity = $this->checkUserValidation();
				if($validity != "false")
				{
					$displayData['validateuser'] = $validity;
				}

				//Get recemmendations for these courses. We also have to find the Course of these recommended institutes which are falling inside the same sub-cat
				$this->load->library('categoryList/CategoryPageRecommendations');
				$this->load->helper('listing/listing');
				$alsoViewedInstitutes = $this->categorypagerecommendations->getAlsoViewedInstitutes(array_reverse ($courseArray));

				if(is_array($alsoViewedInstitutes) && count($alsoViewedInstitutes)) {
						//Check if the Courses already added should not be there
						$checkedArray = array();
						foreach ($alsoViewedInstitutes as $key=>$value){
							if(!in_array($value,$courseArray)){
								$checkedArray[$key] = $value;
							}
						}
						$alsoViewedInstitutes = $checkedArray;
						
						$displayData['institutesRecommended'] = array_slice($instituteRepository->findWithCourses($alsoViewedInstitutes),0,5);
						
						//Now, for each of these institues, we have to find the courses which fall in the same Sub-category as the 1st Course

				}
				
				//Now, for each of these institues, we have to find the courses which fall in the same Sub-category as the 1st Course
				$courseLists = array();
				foreach ($appliedInstitutes as $instituteDetail){
					$instituteIdDetail = $instituteDetail->getId(); 
					$courseId = $instituteDetail->getFlagshipCourse()->getId(); 
					$subCatList = implode (',',$subCategoryCourse[$mainCourseId]);
					$courseLists[$courseId] = array();
					$courseLists[$courseId] = $institutemodel->getCoursesInSubCategory($subCatList,$instituteIdDetail);

					//If the selected course is not available in the list, add it in the List
					$isCourseAvailable = false;
					$refinedCourseList = array();

					foreach ($courseLists[$courseId] as $courseD){
						if($courseD['course_id'] == $courseId){
							$isCourseAvailable = true;
						}
						//Check to make sure that already compared course is not in the List.
						if(!in_array($courseD['course_id'],$courseArray) || $courseD['course_id'] == $courseId){
							$refinedCourseList[] = $courseD;
						}
					}
					$courseLists[$courseId] = $refinedCourseList;
					
					if( !$isCourseAvailable ){
						$selectedArray = array ('course_id'=>$courseId,'courseTitle'=>$instituteDetail->getFlagshipCourse()->getName() );
						array_push($courseLists[$courseId],$selectedArray);
					}

				}
				$displayData['courseLists'] = $courseLists;

              	//$votesCoursesList = $this->getPageVotes($courseArray);
            	//$displayData['votesInfoArray'] = $votesCoursesList;	
				$campusRepList = $this->getCampusRepsForCompareTool($courseArray);
        	    $displayData['campusRepList'] = $campusRepList;
        	    $flagForCampurRepExists = 0;
				for($i = 0; $i <= 3 ;$i++){
				  if(!empty($campusRepList[$i]['caInfo'])){
				    $flagForCampurRepExists ++;
				  }
				}
				$displayData['flagForCampurRepExists'] = $flagForCampurRepExists;
				
				$this->national_course_lib = $this->load->library('listing/NationalCourseLib');
				$displayData['brochureURL'] = $this->national_course_lib;				
			}
			global $listings_with_localities;
			$displayData['listings_with_localities'] = json_encode($listings_with_localities);
			
			// Check for Multilocation courses, filter courses which are multilocation
			$listingebrochuregenerator = $this->load->library('listing/ListingEbrochureGenerator');
			$multiloc = $listingebrochuregenerator->getMultilocationsForInstitute($courseArray);
			$displayData['multiLocationCourses'] = json_encode($multiloc); 
			
			$displayData['localityArray'] = array();

			// make response if course isPaid/free
			
			$displayData['responseUserData'] = $this->makeLoggedInUserResponse($courseArray);
			
			//below code used for beacon tracking
			$displayData['trackingpageIdentifier']='comparePage';
			$displayData['trackingsubCatID']=$displayData['mainCourseSubCatId'];
			$displayData['trackingcountryId']=2;
			//below line is used to store the information in beacon varaible for tracking purpose
			$this->tracking=$this->load->library('common/trackingpages');
			$this->tracking->_pagetracking($displayData);

			//below line is used for conversion tracking purpose
			$displayData['emailTrackingPageKeyId']=211;
			$displayData['qtrackingPageKeyId']=212;
			$displayData['compareHomePageKeyId']=612;
			$this->load->view('compareHomepage',$displayData);
		}
		
		private function sortBySourceId($a, $b) {
 			if($a['source_id'] > $b['source_id']) {
				return 1;
			}
			else if($a['source_id'] < $b['source_id']) {
				return -1;
			}
			else {
				return 0;
			}
		}
		
        function createBarChart($naukriDataIns, $loadView = true){
        	// added by virender
			// desciption : manage naurki data based on course subcategory allow only 23 (full time mba)
			if(count($naukriDataIns)>0){
				$this->load->library('mNaukriTool5/NaukriDataGraph');
				$salaryDataResults = $this->naukridatagraph->prepareNaukriDataGraph($naukriDataIns);
			}
			$this->load->model('listing/institutemodel');
			$institutemodel = new institutemodel;
			$data = array();

			$avgSalaryDataResults = $institutemodel->getAverageNaukriSalaryData('2-5');
			$total_employees = 0;
			$noDataFound = true;
			$bucketForExp = array();
			foreach($salaryDataResults as $key=>$value) {
                foreach($value as $k=>$salaryData){
                    $bucketForExp[$key]['exp'][]    = $salaryData['exp_bucket'];
                    $bucketForExp[$key]['instName']   = $salaryData['institute_name'];
                    $bucketForExp[$key]['instId']   = $salaryData['instId'];
                }
            }
			
			foreach($salaryDataResults as $key=>$value) { 
                if(!in_array('2-5',$bucketForExp[$key]['exp'])){
                    $NoOfEmployees_bucket2 = $salaryData['tot_emp'];
                    $data[$key]['Exp_Bucket'] = '2-5';
                    $data[$key]['AvgCTC'] = 0;
                    $data[$key]['totalAvg'] = $avgSalaryDataResults;
                    $data[$key]['institute_name'] = $bucketForExp[$key]['instName'];
                    $data[$key]['instId'] = $bucketForExp[$key]['instId'];
                }
			    foreach($value as $k=>$salaryData){
					$total_employees = $total_employees + $salaryData['tot_emp'];
					if($salaryData['exp_bucket'] == '2-5') {
					    $NoOfEmployees_bucket2 = $salaryData['tot_emp'];
					    $data[$key]['Exp_Bucket'] = $salaryData['exp_bucket'];
					    $data[$key]['AvgCTC'] = $salaryData['ctc50'];
					    $data[$key]['totalAvg'] = $avgSalaryDataResults;
					    $data[$key]['institute_name'] = $salaryData['institute_name'];
					    $data[$key]['instId'] = $salaryData['instId'];
					    $noDataFound = false;
					    break;
					}
					if($salaryData['exp_bucket'] ==''){
					    $NoOfEmployees_bucket2 = $salaryData['tot_emp'];
					    $data[$key]['Exp_Bucket'] = '2-5';
					    $data[$key]['AvgCTC'] = 0;
					    $data[$key]['totalAvg'] = $avgSalaryDataResults;
					    $data[$key]['institute_name'] = $salaryData['institute_name'];
					    $data[$key]['instId'] = $salaryData['instId'];
					}
			    }
			}

			$response = array();
			$response['data'] = $this->naukridatagraph->manageCourseIndex($naukriDataIns, $data); // return final naukri graph data
			if($loadView){
				$this->load->view('naukriData',$response);//will be using in future
            }else{
            	return $response;
            }
        }

                function getPageVotes($courseArray){
                        $this->load->model('compareInstitute/compare_model');
                        $compareModel = new compare_model;

                        //Get course vote count
                        $numberOfVotes = $compareModel->getNumberOfVotesByCourse($courseArray);
                        $displayData['numberOfVotes'] = $numberOfVotes;

                        if(isset($_COOKIE['comparePageVotes']) && $_COOKIE['comparePageVotes']!=''){
                                $votedCourses = explode('|', $_COOKIE['comparePageVotes']);
                                $displayData['votedCourses']=$votedCourses;
                        }

                        return $displayData;
                }

		function getPopularCoursesForComparision($subCategoryCourse=23){
			$this->load->model('compareInstitute/compare_model');
			$compareModel = new compare_model;
			$popularList=$compareModel->comparisionOfPopularCourses($subCategoryCourse);
			$displayData = array();
			$displayData['subCategoryCourse']=$subCategoryCourse;

                        $this->load->builder('CategoryBuilder','categoryList');
                        $categoryBuilder = new CategoryBuilder;
                        $categoryRepository = $categoryBuilder->getCategoryRepository();
                        $subCategory = $categoryRepository->find($subCategoryCourse);
                        $displayData['subCategoryName'] = $subCategory->getName();

			$displayData['popularList']=$popularList;
			if(is_array($popularList) && count($popularList)>0){
				echo $this->load->view('popularInstituteComparision',$displayData,true);
			}
	        }		

		function getInstituteCoursesAjax(){
			$instituteId = $this->input->post('instituteId');
			$firstSelectedCourseId = $this->input->post('firstCourseId');
			$secondSelectedCourseId = $this->input->post('secondCourseId');
			$thirdSelectedCourseId = $this->input->post('thirdCourseId');

			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$courseRepository = $listingBuilder->getCourseRepository();
			$this->load->model('listing/institutemodel');
			$institutemodel = new institutemodel;
			$courseLists = array();

			//Check if any of the Institute Course lies in same sub-cat as First course. If yes, simply return this course and then we can redirect to the new page
			if($firstSelectedCourseId!='' && $firstSelectedCourseId>0){
				//Get Category of the First course
				$subCategoryCourse = $instituteRepository->getCategoryIdsOfListing($firstSelectedCourseId, 'course', 'true', TRUE);
				
				//Get all the Courses of this Institute falling under these Sub-Categories
				$subCatList = implode (',',$subCategoryCourse[$firstSelectedCourseId]);
				$courseLists = $institutemodel->getCoursesInSubCategory($subCatList,$instituteId);
			}

			foreach ($courseLists as $courseVal){
				if($courseVal['course_id']!=$firstSelectedCourseId && $courseVal['course_id']!=$secondSelectedCourseId && $courseVal['course_id']!=$thirdSelectedCourseId){
					echo $courseVal['course_id'];
					return;
				}
			}
			
			//Now, get the Institute object and its courses. Then create the HTML
			$courses = $instituteRepository->getLocationwiseCourseListForInstitute($instituteId);
			$courseList = array();
			if(!(isset($courses->ERROR_MESSAGE) && $courses->ERROR_MESSAGE == "NO_DATA_FOUND")) {
				foreach($courses as $course){
					if((($_REQUEST['city'] == $course['city_id']) || !($_REQUEST['city']))
					   && (($_REQUEST['locality'] == $course['locality_id']) || !($_REQUEST['locality']) || $_REQUEST['locality'] == 'All')){
						$courseList = array_merge($courseList,$course['courselist']);
					}
				}
				array_unique($courseList);
				
				$refinedCourseList = array();
				foreach ($courseList as $courseId){
					if(!in_array($courseId,$refinedCourseList)){
						$refinedCourseList[] = $courseId;
					}
				}
				$courseList = $refinedCourseList;
			}

			$institute = $instituteRepository->findWithCourses(array($instituteId=>$courseList));
			
			if((is_array($institute) && count($institute)<0) || $institute ==''){
				return '';
			}
			
			$displayData['institute'] = $institute[$instituteId];
			$displayData['request'] = $request;
			$displayData['firstSelectedCourseId'] = $firstSelectedCourseId;
			$displayData['secondSelectedCourseId'] = $secondSelectedCourseId;
			$displayData['thirdSelectedCourseId'] = $thirdSelectedCourseId;
			$this->load->view('autoSuggestorInstituteView',$displayData);
			$this->load->view('autoSuggestorCourseListView',$displayData);

		}

		function getStaticURLDetailsFromDB($courseId1,$courseId2,$courseId3=0,$courseId4=0){
			$this->load->model('compareInstitute/compare_model');
			$compareModel = new compare_model;
			$seoData = array();
			if(!is_numeric($courseId1) && !is_numeric($courseId2)){	//Case of 2 Courses
				$courseId1 = $courseId3;
				$courseId2 = $courseId4;
				$courseId3 = 0;
				$courseId4 = 0;
			}
			else if(!is_numeric($courseId1)){	//Case of 3 COurses
                                $courseId1 = $courseId2;
                                $courseId2 = $courseId3;
                                $courseId3 = $courseId4;
                                $courseId4 = 0;
			}
			$seoData=$compareModel->getStaticURLDetailsFromDB($courseId1,$courseId2,$courseId3,$courseId4);

			if(count($seoData)>=1){
				$data = $seoData[0];
				if($data['course1_location']!=''){					
					$course1Location = " ".$data['course1_location'];
				}
				if($data['course2_location']!=''){					
					$course2Location = " ".$data['course2_location'];
				}

                                if(isset($data['course3_location']) && $data['course3_location']!=''){
                                        $course3Location = " ".$data['course3_location'];
                                }
                                if(isset($data['course4_location']) && $data['course4_location']!=''){
                                        $course4Location = " ".$data['course4_location'];
                                }

				if($data['course4_id']==0 && $data['course3_id']==0){
					$seoData['title'] = "Colleges Comparison - ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
					$seoData['description'] = "Find the colleges comparison report of ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." at Shiksha.com";
					$seoData['heading'] = "Comparing ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
					$seoData['canonical'] = SHIKSHA_HOME."/comparison-of-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-".$data['course1_id']."-".$data['course2_id'];
					$seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location;
				}
				else if($data['course4_id']==0){
        	                        $seoData['title'] = "Colleges Comparison - ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location;
                	                $seoData['description'] = "Find the colleges comparison report of ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." at Shiksha.com";
                        	        $seoData['heading'] = "Comparing ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location;
                                	$seoData['canonical'] = SHIKSHA_HOME."/comparison-of-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-vs-".str_replace(' ','-',strtolower($data['course3_name']))."-".str_replace(' ','-',strtolower($data['course3_location']))."-".$data['course1_id']."-".$data['course2_id']."-".$data['course3_id'];
                                	$seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location;
				}
				else{
                                        $seoData['title'] = "Colleges Comparison - ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location;
                                        $seoData['description'] = "Find the colleges comparison report of ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location." at Shiksha.com";
                                        $seoData['heading'] = "Comparing ".$data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location;
                                        $seoData['canonical'] = SHIKSHA_HOME."/comparison-of-".str_replace(' ','-',strtolower($data['course1_name']))."-".str_replace(' ','-',strtolower($data['course1_location']))."-vs-".str_replace(' ','-',strtolower($data['course2_name']))."-".str_replace(' ','-',strtolower($data['course2_location']))."-vs-".str_replace(' ','-',strtolower($data['course3_name']))."-".str_replace(' ','-',strtolower($data['course3_location']))."-vs-".str_replace(' ','-',strtolower($data['course4_name']))."-".str_replace(' ','-',strtolower($data['course4_location']))."-".$data['course1_id']."-".$data['course2_id']."-".$data['course3_id']."-".$data['course4_id'];
                                        $seoData['breadcrumb'] = $data['course1_name'].$course1Location." Vs ".$data['course2_name'].$course2Location." Vs ".$data['course3_name'].$course3Location." Vs ".$data['course4_name'].$course4Location;

				}
			}
			return $seoData;			
		}
		
		function getCampusRepsForCompareTool($courseArray){
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$courseRepository = $listingBuilder->getCourseRepository();
			
			$this->load->model('CA/cadiscussionmodel');
			$this->cadiscussionmodel = new CADiscussionModel();
			$campusRepArray = array();
			foreach($courseArray as $element){
				$course = $courseRepository->find($element);
				$courseId = $course->getId();
				$instituteId = $course->getInstId();
				$campusRepArray['courses'][] =$courseId;
				$campusRepArray['institute'][] =$instituteId;
				$url = Modules::run('CA/CADiscussions/getCourseUrl',$courseId);
				$campusRepArray['courseUrl'][] =$url;
				$campusRepArray[]=$this->cadiscussionmodel->getCampusRepInfoForCourse(array($courseId), "course" ,$instituteId,1);
			}
			return $campusRepArray;
		}
		
		function getQuestionFormForCampusRep() {
			$this->init();
	
			$postData = $_POST;
			$instituteId = $this->input->post('instituteId');
			$courseId = $this->input->post('courseId');
			
			
			//Get the Course object
			$this->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
	
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$institute = $instituteRepository->find($instituteId);
	
			$courseRepository = $listingBuilder->getCourseRepository();
			$course = $courseRepository->find($courseId);
	
			$data= array();
			$data['course'] = $course;
			$url = Modules::run('CA/CADiscussions/getCourseUrl',$course->getId());
			$data['coursUrl'] = $url;
			$data['institute'] = $institute;
			$data['instituteId'] = $instituteId;
			$data['$questionIds'] = '';
			$data['validateuser'] = $this->checkUserValidation ();
			$data['instituteAnAURL'] = Modules::run('CA/CampusAmbassador/getListingAnaUrl',$instituteId);
			$data['categories'] = $instituteRepository->getCategoryIdsOfListing($courseId,'course');
			$data['locationId'] = $institute->getMainLocation()->getCountry()->getId();
			$currentLocation = $course->getMainLocation();
			$data['currentLocation'] = $currentLocation;
			$national_course_lib = $this->load->library('listing/NationalCourseLib');
			$dominantDesiredCourseData = $national_course_lib->getDominantDesiredCourseForClientCourses(array($course->getId()));
			foreach ($dominantDesiredCourseData as $key => $value) {
                $dominantDesiredCourseData[$key]['name'] = $course->getName();
            }
            $data['instituteCoursesLPR']                = $dominantDesiredCourseData;
            $data['pageType']                = 'course';
			$data['currentLocation'] = $currentLocation;
			
			//below line is used for conversion tracking purpose
			if(!empty($_POST['tracking_keyid']))
			{
				$data['trackingPageKeyId']=$this->input->post('tracking_keyid');
			}

			//Now we need to display these Campus reps
			$this->load->view('compareInstitute/ask_question_form_compare',$data);
		}
		
		function trackComparePage($randNum,$pageType='dynamic',$source='desktop',$courseString='',$trackeyStr='',$defaultPageKey=null){
			$userInfo = $this->checkUserValidation();
			if($userInfo == 'false') {
			    $userId = 0;
			}
			else {
			    $userId = $userInfo[0]['userid'];
			}	    
			$this->load->model('compareInstitute/compare_model');
			$this->compare_model->trackComparePage($pageType,$source,$courseString,$userId,$trackeyStr,$defaultPageKey);
		}
		
		function popularComparisonsMIS($noOfDays = 30,$limitPerSubCat = 20){
			$this->load->model('compareInstitute/compare_model');
			$compareModel = new compare_model;
			$result = $compareModel->popularComparisonsMIS($noOfDays,$limitPerSubCat);			
		}
		
		//set the makeAutoResponse if following conditions are made and user LoggedIn
		
		function makeLoggedInUserResponse($courseArray)
		{
			$this->init();
			
			$validateuser = $this->checkUserValidation();
			
			if($validateuser != 'false')
			{  
				$this->load->model('qnAmodel');
				$this->qnamodel = new QnAModel();
				
				$this->load->model('user/usermodel');
				$usermodel = new usermodel;
				$userId = $validateuser[0]['userid'];
				$user = $usermodel->getUserById($userId);
				
				//Get the Course object
				$this->load->builder('ListingBuilder','listing');
				$listingBuilder = new ListingBuilder;	
				$courseRepository = $listingBuilder->getCourseRepository();
				
				$cookieVal = Array();
				//$compareStickyCategoryPage = 'compare-global-categoryPage';
				//if($_COOKIE[$compareStickyCategoryPage]){
				//$cookieVal = explode('|||',$_COOKIE[$compareStickyCategoryPage]);
				//}

				$cookieVal = $courseArray;
				$responseCourse = Array();
				
				if(count($cookieVal)>0)
				{
					for($i = 0; $i <  count($cookieVal); $i++)
					{
						//$institute = explode('::',$cookieVal[$i]);
						$courseId =  $cookieVal[$i];
		
						$course = $courseRepository->find($courseId);
						
						$isFullRegisteredUserSts = Modules::run('registration/Forms/isValidResponseUser', $courseId, $userId);
						$isFullRegisteredUser = ($isFullRegisteredUserSts==true || $isFullRegisteredUserSts==1)?1:0;
						
						if(is_object($course) && is_object($user))
						{
							if(!(in_array($validateuser[0]['usergroup'],array("enterprise","cms","experts","sums"))) && ($validateuser[0]['mobile'] != "") && (!($this->qnamodel->checkIfAnAExpert($dbHandle,$validateuser[0]['userid']))) && $isFullRegisteredUser == 1)
							{
								$responseCourse[] = array('firstName'=>$user->getFirstName(),'lastName'=>$user->getLastName(),'mobile'=>$user->getMobile(),'email'=>$user->getEmail(),'instituteId'=>$course->getInstId(),'instituteName'=>$course->getInstituteName(),'courseId'=>$course->getId(),'currentCityId'=>$course->getMainLocation()->getCity()->getId(),'currentLocaLityId'=>$course->getMainLocation()->getLocality()->getId());
							}
						}
						
					}	
				}
			        return $responseCourse;
			}
	        }
		
		//After user has loggedin,update userId for made response
		function getLoggedInUserForMadeResponse()
		{
			$userId = $this->input->post('userId');
			if($userId)
			{
				$this->load->model('compareInstitute/compare_model');
				$this->compare_model->updateUserIdForMadeResponse($userId);
			}
		}

		/*
		* Get multilocations for an institute/course
		* params: course Id's
		*/
		// private function getMultilocationsForCompare($courseList){
		// 	$listingebrochuregenerator = $this->load->library('listing/ListingEbrochureGenerator');
		// 	foreach ($courseList as $k => $v) {
		// 		foreach ($v as $key => $value) {
		// 		// $multiLocations[$value['course_id']] = $listingebrochuregenerator->getMultilocationsForInstitute($value['course_id']); 
		// 		$courseIds[] = $value['course_id'];
		// 		}
				
		// 	}
		// 	$multiLocations = $listingebrochuregenerator->getMultilocationsForInstitute($courseIds); 
		// 	return $multiLocations;
		// }


	/*
	* Load course objects with basic info and  associate them with institute object
	* @params : $institute_id => institute Id
	*			$courses => list all the courses in institute 
	*/
	private function getInstituteWithBasicCourseInfoObjs($institute_id,$courses) {
		$this->load->builder('listing/ListingBuilder','listing/listing');
		
		$listingbuilder = new ListingBuilder();
		$courseRepo = $listingbuilder->getCourseRepository();

		$courses = $courseRepo->getDataForMultipleCourses($courses,'basic_info|head_location|attributes|recruiting_companies|salient_features|events|exams');
		
		$instituteRepository = $listingbuilder->getInstituteRepository();
		$institute = $instituteRepository->find($institute_id);
		$institute->setCourses($courses);
		
		return $institute;
		
	}

	function removeInvalidPopularComparisons(){
		$this->validateCron();
		$this->load->model('compareInstitute/compare_model');
		$compareModel = new compare_model;
		$courseListArray = $compareModel->getCoursesOfPopularComparisons();
		$courseIdUnique = array();
		foreach ($courseListArray as $key1 => $value) {
			foreach ($value as $key => $val) {
				if(!array_key_exists($val,$courseIdUnique) && $val != 0){
					$courseIdUnique[$val] = $val;
				}
			}
		}
		if(!empty($courseIdUnique)){
			$this->load->builder("nationalCourse/CourseBuilder");
			$builder = new CourseBuilder();
			$courseRepo = $builder->getCourseRepository();
			$courseObjects = $courseRepo->findMultiple($courseIdUnique);
			foreach ($courseObjects as $key => $value) {
				$id = $value->getId();
				if(!empty($id)){
					$validCourseIds[] = $id;
				}
			}
			$invalidCourseIds = array_diff($courseIdUnique, $validCourseIds);
			if(!empty($invalidCourseIds)){
				$transaction = $compareModel->removeInvalidPopularComparisons(implode($invalidCourseIds,','));
				if($transaction){
					_p("Update successful");
				}
				else{
					_p("Update unsuccessful");
				}
			}
			else{
				_p("No invalid course exists");
			}
		}
	}
}
?>

