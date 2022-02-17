<?php

class AbroadIndexerLib {

        private $_ci;
        private $listingBuilder;
		private $abroadListingCommonLib;
		private $saSearchModel;
		private $countryContinentMappings;
		private $autoSuggestorIndexData;
		private $locationSuggestions;
                private $autoSuggestorSynonym;
                public function __construct(){
				$this->_ci = & get_instance();
				$this->_ci->load->model("listing/abroadcoursemodel", "", true);
				$this->_ci->load->builder("ListingBuilder", "listing");
				$this->listingBuilder = new ListingBuilder();
				$this->abroadListingCommonLib = $this->_ci->load->library("listing/AbroadListingCommonLib");
				$this->saSearchModel = $this->_ci->load->model("SASearch/sasearchmodel");
				$this->abroadCommonLib = $this->_ci->load->library("listingPosting/AbroadCommonLib");
				$this->_ci->load->builder('CategoryBuilder','categoryList');
				$this->categoryBuilder = new CategoryBuilder;
				$this->categoryRepository = $this->categoryBuilder->getCategoryRepository();
				$this->_ci->load->config("SASearch/SASearchIndexConfig");
                $this->autoSuggestorSynonym = $this->_ci->config->item('SYNONYM_AUTOSUGGESTOR_MAPPING');
				$this->fullIndexing = false;
		}
        /*
		 * function that gets all courses of a university (all univs if "All" is passed)
		 */
		public function getDataRequiredForUniversity($univId = null)
		{
				if($univId == null)
				{
						return false;
				}
				if(is_numeric($univId))
				{
						$univId = array($univId);
				}
				// get univ Repo
				$univRepository = $this->listingBuilder->getUniversityRepository();
				// case to index all univs' courses
				if($univId == "All") 
				{
						$this->fullIndexing = true;
						$univIdWithCourseId = $this->saSearchModel->getAllAbroadUniversitiesWithCourses();
						$univId = array_keys($univIdWithCourseId);
						// get courses for each such university
						$courseIds = call_user_func_array('array_merge',$univIdWithCourseId);
				}
				else // indexing single university's courses
				{
						$courseIds = array();
						foreach($univId as $id){
								// get courses for each such university
								$courses = $this->abroadListingCommonLib->getUniversityCoursesGroupByStream($id);
								$courseIds = array_merge($courseIds, $courses['stream']['course_ids']);
						}
				}
				$univObjs = $univRepository->findMultiple($univId);
				$courseData = false;
				if(count($courseIds)>0)
				{
					$courseData = $this->getDataRequiredForCourse($courseIds,$univObjs);
				}
				return $courseData;
		}
		/*
		 * function that gets all courses asked for.
		 */
		public function getDataRequiredForCourse($courseIds = array(), $univObj = null)
		{
				if(count($courseIds ) == 0){
					return false;
				}
				$this->univAutoSuggestorIndexData = $this->locationSuggestions = $this->examAutoSuggestorIndexData = array();
				$this->countryContinentMappings = $this->saSearchModel->getCountryContinentMappings();
				$this->examMasterList = $this->abroadCommonLib->getAbroadExamsMasterList();
				$this->examMasterList = array_map(function($a){return $a['exam'];},$this->examMasterList);
				$this->desiredCourses = $this->_getDesiredCourses();
				$this->coursePopularityIndex = $this->getPopularityCountDataForCourseV2($courseIds);
				//unset($this->abroadCommonLib);
				// get course objects from the ids
				$courseRepository = $this->listingBuilder->getAbroadCourseRepository();
				$abroadCourseObjs = $courseRepository->findMultiple($courseIds);
				
				// if  university obj was not passed, get it for all courses [case of indexing all courses of a univ]
				if($univObj == null){
						$universityIds = array_unique( array_filter(array_map(function($a){return $a->getUniversityId();},$abroadCourseObjs)));
						$univRepository 	= $this->listingBuilder->getUniversityRepository();
						if(count($universityIds)==0)
						{ 
								return false;
						}
						$univObjs = $univRepository->findMultiple($universityIds);
				}
				else if(is_array($univObj) && count($univObj) > 0){
						$univObjs = $univObj ;
				}
				else{
						$univObjs = array($univObj->getId()=>$univObj);
				}
				$univIds = array_filter(array_map(function($a){return $a->getId();},$univObjs));
				// get all courseIds for these because we have to find popularity for univsersities which includes view count of ALL its courses too.
				// get view count for last 21 days --- now loaded in getPopularityIndexForUniversity below
				/* $this->univViewCount = $this->abroadListingCommonLib->getViewCountForListingsByDays($univIds,"university",21);
				$this->courseViewCount = $this->abroadListingCommonLib->getViewCountForListingsByDays($courseIds,"course",21); */
				$this->univPopularityIndex = $this->getPopularityIndexForUniversity($univIds);
				// compute university's inventory types
				$this->_getInventoryForListings($univIds);
				// get department
				$deptIds = array_unique( array_filter(array_map(function($a){return $a->getInstId();},$abroadCourseObjs)));
				$abroadInstRepository = $this->listingBuilder->getAbroadInstituteRepository();
				$abroadInstRepository->disableCaching();
				$abroadInstObjs = $abroadInstRepository->findMultiple($deptIds);
				// specializations for each course
				$courseSpecializations = $this->getSpecializationByCourseId($courseIds);
				// ranks for all courses
				$courseRankInfo = $this->abroadListingCommonLib->populateRankInfo($abroadCourseObjs,'course');
				$scholarshipInfo = $this->getScholarshipAvailability($courseIds);
				// now process these objects to prepare data to be indexed at document level
				$otherInfo = array(	'universityObjs'=>$univObjs,
												'deptObjs' => $abroadInstObjs,
												'courseSpecializations'=>$courseSpecializations,
												'scholarshipInfo'=>$scholarshipInfo,
												'courseRankInfo'=>$courseRankInfo);
				$abroadCourseDataForIndex = $this->_processData($abroadCourseObjs, $otherInfo);
				// merge with autosuggestor doc data
				if($this->fullIndexing)
				{
						$this->getCourseAutosuggestorData();
						$abroadCourseDataForIndex = array_merge($abroadCourseDataForIndex,$this->univAutoSuggestorIndexData,$this->locationSuggestions,$this->examAutoSuggestorIndexData,$this->courseAutoSuggestorIndexData);
				}else{
						$abroadCourseDataForIndex = array_merge($abroadCourseDataForIndex,$this->univAutoSuggestorIndexData,$this->locationSuggestions,$this->examAutoSuggestorIndexData);
				}
				return $abroadCourseDataForIndex;
		}

		/**
		 * This function returns the popularity for given universities
		 * NOTE : this also loads the view count for the list of university 
		 * ids supplied as input as well as view count for all its courses 
		 * into class members : univViewCount, courseViewCount
		 */
		public function getPopularityIndexForUniversity($univIds)
		{
			if(empty($univIds)){
				return array();
			}
			$univIdWithCourseId = $this->saSearchModel->getAllAbroadUniversitiesWithCourses($univIds);
			$univId = array_keys($univIdWithCourseId);
			
			$this->univViewCount = $this->abroadListingCommonLib->getViewCountForListingsByDays($univIds,'university',21);
			// get view count (21 days) for courses in these universities
			$courseIds = call_user_func_array('array_merge',$univIdWithCourseId);
			$this->courseViewCount=$this->abroadListingCommonLib->getViewCountForListingsByDays($courseIds,'course',21);

			//for each university...
			$univPopularityIndexArr = array();
			foreach($univIdWithCourseId as $univId=>$courseList)
			{
				//take the view count...
				$uniViewCount = $this->univViewCount[$univId];
				// ...pass it to calculation of univ popularity along with the list of course ids & their view count
				$univPopularityIndexArr[$univId] = $this->_calculateUniversityPopularityIndexV2($this->courseViewCount,$courseList,$uniViewCount);
			}
			return $univPopularityIndexArr;
		}
        /*
		 * function to process course, univ objects to create data to be indexed
		 */
        private function _processData($abroadCourseDataObjs = array(), $otherInfo = array()){
				$universityObjs = $otherInfo['universityObjs'];
				$deptObjs = $otherInfo['deptObjs'];
				$courseSpecializations = $otherInfo['courseSpecializations'];
				$rankInfo = $otherInfo['courseRankInfo'];
				$scholarshipInfo = $otherInfo['scholarshipInfo'];
				// check if raw data available
				if(count($abroadCourseDataObjs) == 0 || count($universityObjs) ==0)
				{
						return false;
				}
				$abroadCourses = array();
				// loop across these course objs
				foreach($abroadCourseDataObjs as $courseId=>$courseObj)
				{
						$abroadCourse = array();
						if(!($courseObj instanceof AbroadCourse))
						{
								continue;
						}
						$abroadCourse['facetype'] = 'abroadlisting';
						// university data
						$universityData =  $universityObjs[$courseObj->getUniversityId()];
						if(!is_object($universityData))
						{
							continue;
						}
						$this->_addUnivData($abroadCourse, $universityData);
						// department data
						if($universityData->getTypeOfInstitute2() == "university")
						{
								$dept = $deptObjs[$courseObj->getInstId()];
								$this->_addDeptData($abroadCourse, $dept);
						}
						$abroadCourse['saCourseId'] = $courseId;
						// course main
						$this->_addCourseData($abroadCourse, $courseObj);
						// rank
						$abroadCourse['saCourseRank'] = $rankInfo[$courseId]['rank'];
						$abroadCourse['saCourseRankName'] = $rankInfo[$courseId]['rankName'];
						$abroadCourse['saCourseRankURL'] = $rankInfo[$courseId]['rankURL'];
						// internal scholarship
						$abroadCourse['saCourseScholarship'] = (is_null($scholarshipInfo[$courseId])?'No':'Yes');
						// shiksha apply fields
						$this->_addShikshaApplyFields($abroadCourse,$courseObj);
						// unique key based on course id
						$abroadCourse['unique_id'] = 'abroadcourse' . $courseId;
						// specializations
						if($courseSpecializations[$courseId]>0)
						{		// separate doc for each specialization
								$abroadCourse['saCourseSpecializationId'] = $abroadCourse['saCourseSpecializationName'] = array();
								
								foreach($courseSpecializations[$courseId] as $specializationId => $specializationName)
								{
										$abroadCourseForAS = $abroadCourse;
										$abroadCourse['saCourseSpecializationId'][] = $abroadCourseForAS['saCourseSpecializationId'] = $specializationId;
										$abroadCourse['saCourseSpecializationName'][] = $abroadCourseForAS['saCourseSpecializationName'] = $specializationName;
										$abroadCourse['saCourseSpecializationNameSubcatIdMap'][] = $specializationName.":".$abroadCourse['saCourseSubcategoryId'];
										// location suggestion index data
										$this->_addLocationAutosuggestorIndexData($abroadCourseForAS);
										// exam suggestion index data
										$this->_getDataforExamAutoSuggestorIndex($abroadCourseForAS);
								}
						}
						else{
								// exam suggestion index data
								$this->_getDataforExamAutoSuggestorIndex($abroadCourse);
								// location suggestion index data
								$this->_addLocationAutosuggestorIndexData($abroadCourse);
						}
						$abroadCourses[] = $abroadCourse;
						unset($abroadCourseDataObjs[$courseId]);
				}
                return $abroadCourses;
        }
        /*
		 * get specializations of multiple courses together
		 */
		public function getSpecializationByCourseId($courseIds = array())
		{
				if(count($courseIds)==0)
				{
						return false;
				}
				$specializations = $this->saSearchModel->getSpecializationsByCourseId($courseIds);
				$specArr = array();
				foreach($specializations as $specialization)
				{
						if(!is_array($specArr[$specialization['clientCourseID']]))
						{
								$specArr[$specialization['clientCourseID']] = array();
						}
						$specArr[$specialization['clientCourseID']][$specialization['LDBCourseID']] = $specialization['SpecializationName'];
				}
				return $specArr;
		}
        
        /*
		 * function get all desired course id & name
		 */
        private function _getDesiredCourses(){
                $desiredCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();
				$result = array();
				foreach($desiredCourses as $desiredCourse)
				{
						$result[$desiredCourse['SpecializationId']] = str_replace("Btech","BTech",$desiredCourse['CourseName']);
				}
                return $result;
        }
                
        /*
		 * function to prepare attributes for multivalued attribute
		 */ 
        private function _getCourseAttributeList($courseObj){
				$attributes = array();
				foreach($courseObj->getAttributes() as $attribute){
					array_push($attributes, trim($attribute->getValue()));
				}
				return $attributes;
		}
       
        /*
		 * prepare exams for multiivalued attribute
		 */
        private function _getCourseEligibility(& $abroadCourse, $courseObj)
		{
				$abroadCourse['saCourseEligibilityExamsIdMap'] = $abroadCourse['saCourseEligibilityExams'] = array();
				foreach($courseObj->getEligibilityExams() as $exam){
						// exams that are in exam master list and are not custome exams
						if(in_array($exam->getName(),$this->examMasterList) && $exam->getId() != -1)
						{		// cutoff is :: score accepted
								if($exam->getCutOff()=='N/A')
								{
										if($exam->getName() == "CAE"){// for CAE it should be string
												$abroadCourse['sa'.$exam->getName().'ExamScore'] = "-1.0";
										}
										else if($exam->getName() == "IELTS")
										{ // for others it should be float
												$abroadCourse['sa'.$exam->getName().'ExamScore'] = -1.0;
												$abroadCourse['sa'.$exam->getName().'StrExamScore'] = "-1.0";
												$abroadCourse['sa'.$exam->getName().'DescExamScore'] = -1.0;
												$abroadCourse['sa'.$exam->getName().'AscExamScore'] = 9999.0;
										}
										else{ // for others it should be float
												$abroadCourse['sa'.$exam->getName().'ExamScore'] = -1;
												$abroadCourse['sa'.$exam->getName().'StrExamScore'] = "-1";
												$abroadCourse['sa'.$exam->getName().'DescExamScore'] = -1;
												$abroadCourse['sa'.$exam->getName().'AscExamScore'] = 9999;
										}
								}
								else
								{
										$abroadCourse['sa'.$exam->getName().'ExamScore'] = $exam->getCutOff();
										$abroadCourse['sa'.$exam->getName().'StrExamScore'] = (string)$exam->getCutOff();
										$abroadCourse['sa'.$exam->getName().'DescExamScore'] = $exam->getCutOff();
										$abroadCourse['sa'.$exam->getName().'AscExamScore'] = $exam->getCutOff();
								}							
								$abroadCourse['saCourseEligibilityExams'][] = $exam->getName();
								$abroadCourse['saCourseEligibilityExamsIdMap'][] = $exam->getName().":".$exam->getId();
						}
				}
		}
		/*
		 * function to collect department data for index
		 */
		private  function _addDeptData(& $abroadCourse, $dept)
		{
				if($dept instanceof AbroadInstitute)
				{
						$abroadCourse['saDeptAbbreviation'] = $dept->getAbbreviation();
						$abroadCourse['saDeptId']	= $dept->getId();
						$abroadCourse['saDeptName'] = $dept->getName();
						$abroadCourse['saDeptType'] = $dept->getInstituteType();
				}
		}
        /*
		 * collect univ data for index
		 */
		private function _addUnivData(& $abroadCourse, $universityData)
		{
				$abroadCourse['saUnivId'] = $universityData->getId();
				$abroadCourse['saUnivName'] = $universityData->getName();
				$abroadCourse['saUnivAcronym'] = $universityData->getAcronym();
				$abroadCourse['saUnivWhyjoin'] = $universityData->getWhyJoin();
				$abroadCourse['saUnivEstablishyear'] = $universityData->getEstablishedYear();
				$abroadCourse['saUnivType'] = $universityData->getTypeOfInstitute();
				$abroadCourse['saUnivType2'] = $universityData->getTypeOfInstitute2();
				$abroadCourse['saUnivAffiliation'] = $universityData->getAffiliation();
				$abroadCourse['saUnivAccreditation'] = $universityData->getAccreditation();
				$abroadCourse['saUnivViewCount'] = $this->univViewCount[$universityData->getId()]>0?$this->univViewCount[$universityData->getId()]:0;
				$abroadCourse['saUnivPopularityIndex'] = $this->univPopularityIndex[$universityData->getId()]>0?$this->univPopularityIndex[$universityData->getId()]:0;
				$abroadCourse['saUnivSeoUrl'] = $universityData->getURL();
				$univPhotos = $universityData->getPhotos();			
		        if(count($univPhotos)) {
		                $imgUrl = $univPhotos['0']->getThumbURL();
		        } else {
		                $imgUrl = SHIKSHA_HOME."/public/images/univDefault.jpg";
		        }
				$abroadCourse['saUnivLogoLink'] = $imgUrl;
				$abroadCourse['saUnivFunding'] = $universityData->isPublicalyFunded()?"Yes":"No";
				// location data
				$univLocation = $universityData->getMainLocation();
				$abroadCourse['saUnivRegion'] = $univLocation->getRegion()->getId();
				$abroadCourse['saUnivCountryId'] = $univLocation->getCountry()->getId();
				$abroadCourse['saUnivCountryName'] = $univLocation->getCountry()->getName();
				if($this->countryContinentMappings[$abroadCourse['saUnivCountryId']]['id']>0)
				{
						$abroadCourse['saUnivContinentId'] = $this->countryContinentMappings[$abroadCourse['saUnivCountryId']]['id'];
						$abroadCourse['saUnivContinentName'] = $this->countryContinentMappings[$abroadCourse['saUnivCountryId']]['name'];
				}
				$abroadCourse['saUnivStateId'] = $univLocation->getState()->getId();
				$abroadCourse['saUnivStateName'] = $univLocation->getState()->getName();
				$abroadCourse['saUnivCityId'] = $univLocation->getCity()->getId();
				$abroadCourse['saUnivCityName'] = $univLocation->getCity()->getName();
				$univCampusAccomodation = $universityData->getCampusAccommodation();
				$abroadCourse['saUnivAccommodationLink'] = $univCampusAccomodation->getAccommodationWebsiteURL();
				$abroadCourse['saUnivAccommodationAvailable'] = ($abroadCourse['saUnivAccommodationLink']==""?"No":"Yes");
				$abroadCourse['saUnivLivingExpense'] = round($this->abroadListingCommonLib->convertCurrency($univCampusAccomodation->getLivingExpenseCurrency(), 1, $univCampusAccomodation->getLivingExpenses()));
				// university rank
				$universityRank = $this->abroadListingCommonLib->populateRankInfo(array($universityData),"university");
				$abroadCourse['saUnivRanking'] = $universityRank[$universityData->getId()]['rank'];
				$abroadCourse['saUnivRankingName'] = $universityRank[$universityData->getId()]['rankName'];
				$abroadCourse['saUnivRankingURL'] = $universityRank[$universityData->getId()]['rankURL'];
				$this->_getDataForUnivAutosuggestorIndex($universityData);
		}
        
		/*
		 * function to get custom fees as multivalued attr
		 */
        private function _getCustomFees($courseObj)
		{
				$customFees = $courseObj->getCustomFees();
				$courseCustomFees = array();
				foreach($customFees as $fee)
				{
						array_push($courseCustomFees, $fee['caption'] . ',' . round($this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,$fee['value'])));
				}
				return $courseCustomFees;
        }
		/*
		 * function to get remaining fee (consists of every type of fee other than tuition fee)
		 */
		private function _getRemainingFees($courseObj)
		{
				$customFees = $courseObj->getCustomFees();
				$courseCustomFee = 0;
				foreach($customFees as $fee)
				{
						$courseCustomFee += $fee['value'];
				}
				$totalRemainingFees = 
				($courseObj->getRoomBoard()>0?$courseObj->getRoomBoard():0)+
				($courseObj->getInsurance()>0?$courseObj->getInsurance():0)+
				($courseObj->getTransportation()>0?$courseObj->getTransportation():0)+
				($courseCustomFee>0?$courseCustomFee:0);
				return $totalRemainingFees;
		}

		private function _getTotal1stYearFee($courseObj){
			$remainingFee = $this->_getRemainingFees($courseObj);
			$totalFees = $remainingFee + $courseObj->getFees()->getValue();
			return $totalFees;
		}
		/*
		 * get shiksha apply fields
		 */
		private function _addShikshaApplyFields(& $abroadCourse, $courseObj)
		{
				$courseApplicationData = $this->abroadListingCommonLib->getApplicationProcessData($courseObj->getId());
				$courseApplicationEligibilityData=$this->abroadListingCommonLib->getCourseApplicationEligibilityData($courseObj->getId());
                                $abroadCourse['saCourseShikshaApply'] = $courseObj->getCourseApplicationDetail()>0?"Yes":"No";
				$abroadCourse['saCourseSOPRequired'] = $courseApplicationData[0]['sopRequired']; 
				$abroadCourse['saCourseLORRequired'] = $courseApplicationData[0]['lorRequired'];
				$abroadCourse['saCourseInterviewRequired'] = $courseApplicationData[0]['isInterviewRequired'];
				$abroadCourse['saCourseApplicationSubmissionDeadline'] = array();
				foreach($courseApplicationData['submissionDateData'] as $dateData)
				{
						$abroadCourse['saCourseApplicationSubmissionDeadline'][] = $dateData['applicationSubmissionLastDate']."T00:00:00Z" ;
						$abroadCourse['saCourseApplicationSubmissionIntake'][] = $dateData['applicationSubmissionName'] ;
				}
                $abroadCourse['saCourseWorkXPRequired'] = ($courseApplicationEligibilityData[0]['isWorkExperinceRequired']==1?"Yes":"No");
				if (count($courseApplicationEligibilityData) > 0) 
				{
                    $abroadCourse['saCourse12thCutoff'] = $courseApplicationEligibilityData[0]['12thCutoff'];
                    $bachelorCutOff=(float)$courseApplicationEligibilityData[0]['bachelorCutoff'];
                    $abroadCourse['saCourseUgCutoff'] = (float)$courseApplicationEligibilityData[0]['bachelorCutoff'];
                    $abroadCourse['saCourseUgCutoffUnit'] = $courseApplicationEligibilityData[0]['bachelorScoreUnit'];
                    $gpaToPercentageMapping=$this->_ci->config->item('GPA_TO_PERCENTAGE_MAPPING');
                    $bachelorMarksInPercentage=0;
                    if($bachelorCutOff>4){
                        $bachelorCutOffToBeUsedInCalculation=  round(($bachelorCutOff/2.5), 1);
                    }else{
                        $bachelorCutOffToBeUsedInCalculation=  $bachelorCutOff;
                    }
                    if($abroadCourse['saCourseUgCutoffUnit']=='GPA'){
                            foreach ($gpaToPercentageMapping as $gpaPercentageMap){
                            $startLimit=$gpaPercentageMap['start'];
                            $endLimit=$gpaPercentageMap['end'];
                            $conversionFactor=$gpaPercentageMap['conversionFactor'];
                            if($bachelorCutOffToBeUsedInCalculation>=$startLimit && $bachelorCutOffToBeUsedInCalculation<=$endLimit){
                                $bachelorMarksInPercentage=$bachelorCutOffToBeUsedInCalculation*$conversionFactor;
                                break;
                            }
                        }
                    }else{
                        $bachelorMarksInPercentage=$bachelorCutOff;
                    }
                    $abroadCourse['saCourseUgCutoffConverted']=$bachelorMarksInPercentage;
                    $abroadCourse['saCoursePgCutoff'] = $courseApplicationEligibilityData[0]['pgCutoff'];
                    $abroadCourse['saCourseWorkXP'] = ($abroadCourse['saCourseWorkXPRequired']=="Yes"?($courseApplicationEligibilityData[0]['workExperniceValue']>0?$courseApplicationEligibilityData[0]['workExperniceValue']:0):0);
                }
		}
		/*
		 * add course data to the data to be indexed
		 */
		private function _addCourseData(& $abroadCourse, $courseObj)
		{
				$abroadCourse['saCourseName'] = $courseObj->getName();
				//$abroadCourse['saCoursePackType'] = $courseObj->getCoursePackType();
				$abroadCourse['saCourseLevel1'] = $courseObj->getCourseLevel1Value();
				$abroadCourse['saCourseLevel2'] = $courseObj->getCourseLevel2Value();
				$abroadCourse['saCourseDescription'] = $courseObj->getCourseDescription();
				$abroadCourse['saCourseDurationUnit'] = $courseObj->getDuration()->getDurationUnit();
				$abroadCourse['saCoursePopularityIndex'] = $this->coursePopularityIndex[$courseObj->getId()]>0?$this->coursePopularityIndex[$courseObj->getId()]:0;
				$durationValue = $courseObj->getDuration()->getDurationValue();
				if($abroadCourse['saCourseDurationUnit']!="Months")
				{
						$abroadCourse['saCourseDurationValue'] = ($abroadCourse['saCourseDurationUnit']=="Years"?(float)$durationValue*12:(float)$durationValue/4);
						$abroadCourse['saCourseDurationUnit']="Months";
				}
				else{
						$abroadCourse['saCourseDurationValue'] = (float)$durationValue;
				}
				
				$abroadCourse['saCourseViewCount'] = $this->courseViewCount[$courseObj->getId()]>0?$this->courseViewCount[$courseObj->getId()]:0;
				$this->_getCourseEligibility($abroadCourse, $courseObj);
				$abroadCourse['saCourseAttributes'] = $this->_getCourseAttributeList($courseObj);
				//$abroadCourse['saCourseScholarship'] = $courseObj->isOfferingScholarship()?"Yes":"No";
				$abroadCourse['saCourseSubcategoryId'] = $courseObj->getCourseSubCategory();
				if($abroadCourse['saCourseSubcategoryId']!='' && is_numeric($abroadCourse['saCourseSubcategoryId'])){
				$subCategoryObj = $this->categoryRepository->find($abroadCourse['saCourseSubcategoryId']);
				$abroadCourse['saCourseSubcategoryName'] = $subCategoryObj->getName();
				$abroadCourse['saCourseParentCategoryId'] = $subCategoryObj->getParentId();
				if(is_numeric($abroadCourse['saCourseParentCategoryId'])){
				$categoryObj = $this->categoryRepository->find($abroadCourse['saCourseParentCategoryId']);
				$abroadCourse['saCourseParentCategoryName'] = $categoryObj->getName();
				}
				}	
				$abroadCourse['saCourseDesiredCourseId'] = $courseObj->getDesiredCourseId();
				$abroadCourse['saCourseDesiredCourseName'] = $this->desiredCourses[$abroadCourse['saCourseDesiredCourseId']] ;
				$abroadCourse['saCourseSeoUrl'] = $courseObj->getURL();
				$abroadCourse['saCourseInventoryType'] = $this->_getInventoryTypeForCourse($courseObj,$abroadCourse);
				
				// 4 job profile fields
				$abroadCourse['saCourseAverageSalary'] = round($this->abroadListingCommonLib->convertCurrency($courseObj->getJobProfile()->getAverageSalaryCurrencyId(),1,$courseObj->getJobProfile()->getAverageSalary()));
				$abroadCourse['saCoursePercentageEmployed'] = $courseObj->getJobProfile()->getPercentageEmployed();
				$abroadCourse['saCoursePopularSectors'] = $courseObj->getJobProfile()->getPopularSectors();
				$abroadCourse['saCourseInternships'] = $courseObj->getJobProfile()->getInternships();
				// fees
				$abroadCourse['saCourseRoomBoardFees'] = round($this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,($courseObj->getRoomBoard()>0?$courseObj->getRoomBoard():0)));
				$abroadCourse['saCourseInsuranceFees'] = round($this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,($courseObj->getInsurance()>0?$courseObj->getInsurance():0)));
				$abroadCourse['saCourseTransportationFees'] = round($this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,($courseObj->getTransportation()>0?$courseObj->getTransportation():0)));
				$abroadCourse['saCourseCustomFees'] = $this->_getCustomFees($courseObj);
				$abroadCourse['saCourseRemainingFees'] = round($this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,$this->_getRemainingFees($courseObj)));
				$abroadCourse['saCourseTotal1stYearFees'] = round($this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,$this->_getTotal1stYearFee($courseObj)));
                $abroadCourse['saCourseFees'] = (int)$this->abroadListingCommonLib->convertCurrency($courseObj->getFees()->getCurrency(),1,$this->_getTotal1stYearFee($courseObj));
                $abroadCourse['saCourseFees'] = ($abroadCourse['saCourseFees'] =='' || $abroadCourse['saCourseFees'] =='0'?1:$abroadCourse['saCourseFees']);

		}
		/*
		 * function that collects data for univ autosuggestor
		 */
		private function _getDataForUnivAutosuggestorIndex($univObj)
		{
				if(
						(!$univObj instanceof University) ||
						(count($this->univAutoSuggestorIndexData[$univObj->getId()])>0)
				  )
				{
						return false;
				}
				
				$this->univAutoSuggestorIndexData[$univObj->getId()]['facetype'] = 'saUnivAutosuggestor';
				$this->univAutoSuggestorIndexData[$univObj->getId()]['saAutosuggestUnivId'] = $univObj->getId();
				$this->univAutoSuggestorIndexData[$univObj->getId()]['saAutosuggestUnivViewCount'] = $this->univViewCount[$univObj->getId()]>0?$this->univViewCount[$univObj->getId()]:0;
				$this->univAutoSuggestorIndexData[$univObj->getId()]['saAutosuggestUnivName'] = str_replace(' & ',' and ',$univObj->getName());
				$this->univAutoSuggestorIndexData[$univObj->getId()]['saAutosuggestUnivAccronym'] = str_replace(' & ',' and ',$univObj->getAcronym());
				$this->univAutoSuggestorIndexData[$univObj->getId()]['unique_id'] = 'saUnivAutoSuggestor'.$univObj->getId();
		}
		/*
		 * function that derives autosuggestor doc data from abrodlisting doc data
		 */
		private function _processCourseDataforAutoSuggestorIndex($abroadCourse, $specializationFlag = false)
		{
				// combinations to be analyzed for course
				// 1. cat name with level
				if(!$specializationFlag && $abroadCourse['saCourseParentCategoryId']>0)
				{
						$uniqueKeySuffix = str_replace(' ','_',$abroadCourse['saCourseLevel1'])."_".$abroadCourse['saCourseParentCategoryId'];
						if(count($this->courseAutoSuggestorIndexData[$uniqueKeySuffix])==0)
						{
								// being added first time
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix] = array('facetype'=>'saCourseAutosuggestor');
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCategoryId'] = $abroadCourse['saCourseParentCategoryId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelCategoryName'] = $abroadCourse['saCourseLevel1']." of ".$abroadCourse['saCourseParentCategoryName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseFacet'] = $this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelCategoryName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelCategoryNameIdMap'] = $abroadCourse['saCourseLevel1'].":".$abroadCourse['saCourseParentCategoryName'].":".$abroadCourse['saCourseParentCategoryId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['unique_id'] = 'saCourseAutosuggestor'.$uniqueKeySuffix;
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount'] = 1;
								$docData = $abroadCourse;
								unset($docData['saCourseSubcategoryId']);
								unset($docData['saCourseSpecializationId']);
								$synonym=$this->_getAutoSuggestorSynonymForDocument($docData);
								if($synonym!=''){
										$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSynonym'] = $synonym;
										$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSynonymKeywordNGram'] = $synonym;
								}
						}
						else{
								// if it was already present, just update the course count
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount']++;
						}
				}
				
				// 2. subcat name with level
				if(!$specializationFlag && $abroadCourse['saCourseSubcategoryId']>0)
				{
						$uniqueKeySuffix = str_replace(' ','_',$abroadCourse['saCourseLevel1'])."_".$abroadCourse['saCourseSubcategoryId'];
						if(count($this->courseAutoSuggestorIndexData[$uniqueKeySuffix])==0)
						{
								// being added first time
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix] = array('facetype'=>'saCourseAutosuggestor','saAutosuggestCourseCount'=>1);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSubcategoryId'] = $abroadCourse['saCourseSubcategoryId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelSubcategoryName'] = str_replace(' & ',' and ',$abroadCourse['saCourseLevel1']." of ".$abroadCourse['saCourseSubcategoryName']);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseFacet'] = $this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelSubcategoryName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelSubcategoryNameIdMap'] = $abroadCourse['saCourseLevel1'].":".$abroadCourse['saCourseSubcategoryName'].":".$abroadCourse['saCourseSubcategoryId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['unique_id'] = 'saCourseAutosuggestor'.$uniqueKeySuffix;
								$docData = $abroadCourse;
								unset($docData['saCourseSpecializationId']);
								$synonym=$this->_getAutoSuggestorSynonymForDocument($docData);
								if($synonym!=''){
										$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSynonym'] = $synonym;
										$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSynonymKeywordNGram'] = $synonym;
								}
						}
						else{
								// if it was already present, just update the course count
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount']++;
						}
				}
				
				// 3. specialization name with level ( we dont save this if subcat and specialization name is same)
				if($specializationFlag && $abroadCourse['saCourseSpecializationId'] > 0 && $abroadCourse['saCourseSpecializationName'] != $abroadCourse['saCourseSubcategoryName'])
				{
						$uniqueKeySuffix = str_replace(' ','_',$abroadCourse['saCourseLevel1'])."_".$abroadCourse['saCourseSpecializationId'];
						if(count($this->courseAutoSuggestorIndexData[$uniqueKeySuffix])==0)
						{
								// being added first time
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix] = array('facetype'=>'saCourseAutosuggestor','saAutosuggestCourseCount'=>1);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSpecializationId'] = $abroadCourse['saCourseSpecializationId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelSpecializationName'] = str_replace(' & ',' and ',$abroadCourse['saCourseLevel1']." of ".$abroadCourse['saCourseSpecializationName']." (in ".$abroadCourse['saCourseSubcategoryName'].")");
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseFacet'] = $this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelSpecializationName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseLevelSpecializationNameIdMap'] = $abroadCourse['saCourseLevel1'].":".$abroadCourse['saCourseSpecializationName'].":".$abroadCourse['saCourseSpecializationId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['unique_id'] = "saCourseAutosuggestor".$abroadCourse['saCourseLevel1']."_".$abroadCourse['saCourseSpecializationId'];
								$docData = $abroadCourse;
								$synonym=$this->_getAutoSuggestorSynonymForDocument($docData);
								if($synonym!=''){
										$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSynonym'] = $synonym;
										$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestSynonymKeywordNGram'] = $synonym;
								}
						}
						else{
								// if it was already present, just update the course count
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount']++;
						}
				}
				
				// 4.a) specialization name with level -->
				if($specializationFlag && $abroadCourse['saCourseDesiredCourseId']>0 && $abroadCourse['saCourseSpecializationId']>0 && $abroadCourse['saCourseSpecializationName'] != $abroadCourse['saCourseSubcategoryName'])
				{
						$uniqueKeySuffix = str_replace(' ','_',$abroadCourse['saCourseDesiredCourseId'])."_".$abroadCourse['saCourseSpecializationId'];
						if(count($this->courseAutoSuggestorIndexData[$uniqueKeySuffix])==0)
						{
								// being added first time
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix] = array('facetype'=>'saCourseAutosuggestor','saAutosuggestCourseCount'=>1);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseId'] = $abroadCourse['saCourseDesiredCourseId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseSpecializationName'] = str_replace(' & ',' and ',$abroadCourse['saCourseDesiredCourseName']." in ".$abroadCourse['saCourseSpecializationName']." (in ".$abroadCourse['saCourseSubcategoryName'].")");
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseFacet'] = $this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseSpecializationName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseSpecializationNameIdMap'] = $abroadCourse['saCourseDesiredCourseId'].":".$abroadCourse['saCourseSpecializationName'].":".$abroadCourse['saCourseSpecializationId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['unique_id'] = "saCourseAutosuggestor".$abroadCourse['saCourseDesiredCourseId']."_".$abroadCourse['saCourseSpecializationId'];
                                                             
						}
						else{
								// if it was already present, just update the course count
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount']++;
						}
				}
				// 4.b) subcategory name with level -->
				if(!$specializationFlag && $abroadCourse['saCourseSubcategoryId']>0 && $abroadCourse['saCourseDesiredCourseId']>0)
				{
						$uniqueKeySuffix = str_replace(' ','_',$abroadCourse['saCourseDesiredCourseId'])."_".$abroadCourse['saCourseSubcategoryId'];
						if(count($this->courseAutoSuggestorIndexData[$uniqueKeySuffix])==0)
						{
								// being added first time
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix] = array('facetype'=>'saCourseAutosuggestor','saAutosuggestCourseCount'=>1);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseSubcategoryName'] = str_replace(' & ',' and ',$abroadCourse['saCourseDesiredCourseName']." in ".$abroadCourse['saCourseSubcategoryName']);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseFacet'] = $this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseSubcategoryName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseSubcategoryNameIdMap'] = $abroadCourse['saCourseDesiredCourseId'].":".$abroadCourse['saCourseSubcategoryName'].":".$abroadCourse['saCourseSubcategoryId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['unique_id'] = "saCourseAutosuggestor".$abroadCourse['saCourseDesiredCourseId']."_".$abroadCourse['saCourseSubcategoryId'];
                                                              
						}
						else{
								// if it was already present, just update the course count
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount']++;
						}
				}
				// 4.c) desired course itself -->
				if(!$specializationFlag && $abroadCourse['saCourseDesiredCourseId']>0)
				{
						$uniqueKeySuffix = $abroadCourse['saCourseDesiredCourseId'];
						if(count($this->courseAutoSuggestorIndexData[$uniqueKeySuffix])==0)
						{
								// being added first time
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix] = array('facetype'=>'saCourseAutosuggestor','saAutosuggestCourseCount'=>1);
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseName'] = $abroadCourse['saCourseDesiredCourseName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseFacet'] = $this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseName'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestDesiredCourseNameIdMap'] = $abroadCourse['saCourseDesiredCourseName'].":".$abroadCourse['saCourseDesiredCourseId'];
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['unique_id'] = "saCourseAutosuggestor".$abroadCourse['saCourseDesiredCourseId'];
                                        	}
						else{
								// if it was already present, just update the course count
								$this->courseAutoSuggestorIndexData[$uniqueKeySuffix]['saAutosuggestCourseCount']++;
						}
				}
		}
		/*
		 *  get location autosugggestion Data
		 */
		private function _addLocationAutosuggestorIndexData($abroadCourse)
		{
				$locationAutoSuggestorIndexData = array();
				$locationAutoSuggestorIndexData['facetype']  = "saLocationAutosuggestor";
				$locationAutoSuggestorIndexData['saAutosuggestUnivId'] = $abroadCourse['saUnivId'];
				$locationAutoSuggestorIndexData['saAutosuggestCourseId'] = $abroadCourse['saCourseId'];
				if($abroadCourse['saCourseParentCategoryId'] !="")
				{
						$locationAutoSuggestorIndexData['saAutosuggestCategoryId'] = $abroadCourse['saCourseParentCategoryId'];
				}
				if($abroadCourse['saCourseSubcategoryId'] !="")
				{
						$locationAutoSuggestorIndexData['saAutosuggestSubcategoryId'] = $abroadCourse['saCourseSubcategoryId'];
				}
				if($abroadCourse['saCourseSpecializationId']!="")
				{
						$locationAutoSuggestorIndexData['saAutosuggestSpecializationId'] = $abroadCourse['saCourseSpecializationId'];
				}
				if($abroadCourse['saCourseDesiredCourseId']!="")
				{
						$locationAutoSuggestorIndexData['saAutosuggestDesiredCourseId'] = $abroadCourse['saCourseDesiredCourseId'];
				}
				if($abroadCourse['saCourseLevel1']!=""){
						$locationAutoSuggestorIndexData['saCourseLevel1'] = $abroadCourse['saCourseLevel1'];
				}
				$conf = $this->_ci->config->item('LOCATION_SYNONYMS');
				// first index city docs
				$parent = empty($abroadCourse['saUnivStateName'])?$abroadCourse['saUnivCountryName']:$abroadCourse['saUnivStateName'];
				$locationAutoSuggestorIndexData['saAutosuggestLocationFacet'] = $abroadCourse['saUnivCityName']." (".$parent.")";
				$locationAutoSuggestorIndexData['saAutosuggestCityName'] = $abroadCourse['saUnivCityName'];
				$locationAutoSuggestorIndexData['saAutosuggestCityNameIdMap'] = $abroadCourse['saUnivCityName'].":".$abroadCourse['saUnivCityId'];
				$locationAutoSuggestorIndexData['saAutosuggestLocationId'] = $abroadCourse['saUnivCityId'];
				$locationAutoSuggestorIndexData['saAutosuggestCityNameSynonyms'] = $conf['CITIES'][$abroadCourse['saUnivCityId']];
				$locationAutoSuggestorIndexData['unique_id'] = "abroadLocationAutosuggestor_city_".$abroadCourse['saCourseId'].($abroadCourse['saCourseSpecializationId']!=""?"_".$abroadCourse['saCourseSpecializationId']:"");
				$this->locationSuggestions[] = $locationAutoSuggestorIndexData;
				unset($locationAutoSuggestorIndexData['saAutosuggestCityName']);
				unset($locationAutoSuggestorIndexData['saAutosuggestCityNameIdMap']);
				unset($locationAutoSuggestorIndexData['saAutosuggestCityNameSynonyms']);
				
				if($abroadCourse['saUnivStateName']!=""){
						$locationAutoSuggestorIndexData['saAutosuggestLocationFacet'] = $abroadCourse['saUnivStateName']." (".$abroadCourse['saUnivCountryName'].")";
						$locationAutoSuggestorIndexData['saAutosuggestStateName'] = $abroadCourse['saUnivStateName'];
						$locationAutoSuggestorIndexData['saAutosuggestStateNameIdMap'] = $abroadCourse['saUnivStateName'].":".$abroadCourse['saUnivStateId'];
						$locationAutoSuggestorIndexData['saAutosuggestLocationId'] = $abroadCourse['saUnivStateId'];
						$locationAutoSuggestorIndexData['saAutosuggestStateNameSynonyms'] = $conf['STATES'][$abroadCourse['saUnivStateId']];
						$locationAutoSuggestorIndexData['unique_id'] = "abroadLocationAutosuggestor_state_".$abroadCourse['saCourseId'].($abroadCourse['saCourseSpecializationId']!=""?"_".$abroadCourse['saCourseSpecializationId']:"");
						$this->locationSuggestions[] = $locationAutoSuggestorIndexData;
						unset($locationAutoSuggestorIndexData['saAutosuggestStateName']);
						unset($locationAutoSuggestorIndexData['saAutosuggestStateNameIdMap']);
						unset($locationAutoSuggestorIndexData['saAutosuggestStateNameSynonyms']);
				}

				$locationAutoSuggestorIndexData['saAutosuggestLocationFacet'] = $abroadCourse['saUnivCountryName'];				
				$locationAutoSuggestorIndexData['saAutosuggestCountryName'] = $abroadCourse['saUnivCountryName'];
				$locationAutoSuggestorIndexData['saAutosuggestCountryNameIdMap'] = $abroadCourse['saUnivCountryName'].":".$abroadCourse['saUnivCountryId'];
				$locationAutoSuggestorIndexData['saAutosuggestLocationId'] = $abroadCourse['saUnivCountryId'];
				$locationAutoSuggestorIndexData['saAutosuggestCountryNameSynonyms'] = $conf['COUNTRIES'][$abroadCourse['saUnivCountryId']];
				$locationAutoSuggestorIndexData['unique_id'] = "abroadLocationAutosuggestor_country_".$abroadCourse['saCourseId'].($abroadCourse['saCourseSpecializationId']!=""?"_".$abroadCourse['saCourseSpecializationId']:"");
				$this->locationSuggestions[] = $locationAutoSuggestorIndexData;
				unset($locationAutoSuggestorIndexData['saAutosuggestCountryName']);
				unset($locationAutoSuggestorIndexData['saAutosuggestCountryNameIdMap']);
				unset($locationAutoSuggestorIndexData['saAutosuggestCountryNameSynonyms']);

				$locationAutoSuggestorIndexData['saAutosuggestLocationFacet'] = $abroadCourse['saUnivContinentName'];				
				$locationAutoSuggestorIndexData['saAutosuggestContinentName'] = $abroadCourse['saUnivContinentName'];
				$locationAutoSuggestorIndexData['saAutosuggestContinentNameIdMap'] = $abroadCourse['saUnivContinentName'].":".$abroadCourse['saUnivContinentId'];
				$locationAutoSuggestorIndexData['saAutosuggestLocationId'] = $abroadCourse['saUnivContinentId'];
				$locationAutoSuggestorIndexData['saAutosuggestContinentNameSynonyms'] = $conf['CONTINENTS'][$abroadCourse['saUnivContinentId']];
				$locationAutoSuggestorIndexData['unique_id'] = "abroadLocationAutosuggestor_continent_".$abroadCourse['saCourseId'].($abroadCourse['saCourseSpecializationId']!=""?"_".$abroadCourse['saCourseSpecializationId']:"");
				$this->locationSuggestions[] = $locationAutoSuggestorIndexData;
		}
		/*
		 * function that derives autosuggestor doc data for exams
		 */
		private function _getDataforExamAutoSuggestorIndex($abroadCourse)
		{
				$autoSuggestorIndexData = array();
				$autoSuggestorIndexData['facetype'] = 'saExamAutosuggestor';
				$autoSuggestorIndexData['saAutosuggestUnivId'] = $abroadCourse['saUnivId'];
				$autoSuggestorIndexData['saAutosuggestCourseId'] = $abroadCourse['saCourseId'];
				foreach($abroadCourse['saCourseEligibilityExamsIdMap'] as $examNameWithId)
				{
						$autoSuggestorIndexData['saAutosuggestExamNameIdMap'] = $examNameWithId;
						$examNameWithId = explode(':',$examNameWithId);
						$autoSuggestorIndexData['saAutosuggestExamName'] = $examNameWithId[0];
						$autoSuggestorIndexData['unique_id'] = "abroadExamAutosuggestor".$abroadCourse['saCourseId']."_".$examNameWithId[1];
						// add it to main autosuggestor array
						$this->examAutoSuggestorIndexData[] = $autoSuggestorIndexData;
				}
		}
		
		private function genratePopilarityIndexForCourse($responseCount,$postedDate){
			$multiplyBy = 10000;
			$popularityIndex = 0;
			if($responseCount>0 && $postedDate!='')
			{
				$timeStampDiff = strtotime(date('Y-m-d')) - strtotime($postedDate);
				//echo "<br/>".$timeStampDiff;
				$noOfDays = (int)($timeStampDiff/(60*60*24));
				
				$popularityIndex = ($responseCount/$noOfDays)*$multiplyBy;
				//echo "==days==".$noOfDays."=====".$popularityIndex."<br/>";
			}
			
			return $popularityIndex;
				
		}

		public function getPopularityCountDataForCourseV2($courseIds = array()){
			$ESConnectionLib = $this->_ci->load->library('trackingMIS/elasticSearch/ESConnectionLib');
            $esconn = $ESConnectionLib->getESServerConnectionWithCredentials();
            $params = array();
	        $params['index'] = SHIKSHA_RESPONSE_INDEX_NAME;
	        $params['type'] = 'response';
	        $params['body']['size'] = 0;
	        $filterArray = array();
	        $keyNames = array("rateMyChance","courseCompare","shortlist","downloadBrochure");
	        $fromDate = date('Y-m-d', strtotime('-21 days')).'T00:00:00';
			if(count($courseIds)>0)
			{
				$filterArray[] = array(
					array('term' => array('site' => "Study Abroad")),
					array('term' => array('considered_for_response' => 1)),
					array('term' => array('response_listing_type' => "course")),
					array('terms'=> array('source' => $keyNames)),
					array('range'=> array('response_time' => array('gte' => $fromDate))),
					array('terms'=> array('response_listing_type_id'=> $courseIds))
				);
			}else{
				$filterArray[] = array(
				  array('term' => array('site' => "Study Abroad")),
				  array('term' => array('considered_for_response' => 1)),
				  array('term' => array('response_listing_type' => "course")),
				  array('terms' => array('source' => $keyNames)),
				  array('range' => array('response_time' => array('gte' => $fromDate)))
				);
			}
	        $params['body']['query']['bool']['filter']['bool']['must'] = $filterArray;
	        $params['body']['aggs']['CourseWise']['terms'] = array('field'=>'response_listing_type_id','size'=>ELASTIC_AGGS_SIZE);
			$result = $esconn->search($params);
	        if($result['hits']['total'] > 0){
	        	return $this->_formatCoursePopularityCountData($result['aggregations']['CourseWise']['buckets']);
	        }
		}

		private function _formatCoursePopularityCountData($courseData){
			$finalCourseResponseCountData = array();
			foreach ($courseData as $value) {
				$finalCourseResponseCountData[$value['key']] = $value['doc_count'];
			}
			return $finalCourseResponseCountData;
		}
		
		public function getPopularityCountDataForCourse(){
			
			$result = $this->saSearchModel->getPopularityCountDataForCourses();
			
			$popularityIndexArr = array();
			foreach($result as $key=>$row){
				$popularityIndexArr[$row['courseId']] = $this->genratePopilarityIndexForCourse($row['totalCount'],$row['coursePostedDate']);
			}
			return $popularityIndexArr;
		}
		
		private function _getDataInChunks($pushArray,$batchsize = 1000){
				$data = array();$count = count($pushArray);
				for($i=0,$z=0;$i<$count;$i++){
					if($i%$batchsize == 0 && $i!= 0){
						$z++;
					}
					$data[$z][] = $pushArray[$i];
				}
				return $data;
		}

		private function _calculateUniversityPopularityIndexV2($courseCountPast21Days,$courseList,$uniViewCount)
		{
			foreach($courseList as $key=>$courseId)
			{
				$uniViewCount += (int)$courseCountPast21Days[$courseId];
			}
			return $uniViewCount;
		}
		
		
		private function _preparePopularityIndexFinalArray($univIdWithCourseId,$univPopularityIndexArr,$coursePopularityIndexs,$univViewCount,$courseViewCount){
				
				$finalArrayToIndex = array();
				foreach($univIdWithCourseId as $univId=>$courseIds)
				{
						foreach($courseIds as $key=>$courseId)
						{
								$abroadCourse = array();
								$abroadCourse['facetype'] 	 = 'abroadlisting';
								$abroadCourse['saUnivPopularityIndex'] 	 = ($univPopularityIndexArr[$univId]>0)?$univPopularityIndexArr[$univId]:0;
								$abroadCourse['saCoursePopularityIndex'] = ($coursePopularityIndexs[$courseId]>0)?$coursePopularityIndexs[$courseId]:0;
								$abroadCourse['saUnivViewCount'] 		 = ($univViewCount[$univId]>0)?$univViewCount[$univId]:0;
								$abroadCourse['saCourseViewCount'] 		 = ($courseViewCount[$courseId]>0)?$courseViewCount[$courseId]:0;
								$abroadCourse['unique_id'] = 'abroadcourse' . $courseId;
								$finalArrayToIndex[] = $abroadCourse;
						}
				}
				return $finalArrayToIndex;
		}
		

		public function getPopularityIndexData(){
				error_log("indexPopularityCount : university with course Id fetching start ".date("H:i:s"));
				$univIdWithCourseId = $this->saSearchModel->getAllAbroadUniversitiesWithCourses();
				error_log("indexPopularityCount : university with course Id fetching Done ".date("H:i:s"));
				
				error_log("indexPopularityCount : university view count and submit date fetch start ".date("H:i:s"));
				$univId = array_keys($univIdWithCourseId);
                $univCountPast21Days=$this->abroadListingCommonLib->getViewCountForListingsByDays($univId,'university');
		        error_log("indexPopularityCount : university view count and submit date fetch done ".date("H:i:s"));
				
				error_log("indexPopularityCount : course view count and submit date fetch done ".date("H:i:s"));
				$courseIds = call_user_func_array('array_merge',$univIdWithCourseId);
                $courseCountPast21Days=$this->abroadListingCommonLib->getViewCountForListingsByDays($courseIds,'course');
			    error_log("indexPopularityCount : course view count and submit date fetch done ".date("H:i:s"));
				
				error_log("indexPopularityCount : university popularity index calculation start ".date("H:i:s"));
				$univPopularityIndexArr = array();
				foreach($univIdWithCourseId as $univId=>$courseList)
				{
					$uniViewCount = $univCountPast21Days[$univId];
					$univPopularityIndexArr[$univId] = $this->_calculateUniversityPopularityIndexV2($courseCountPast21Days,$courseList,$uniViewCount);
				}
				error_log("indexPopularityCount : university popularity index calculation done ".date("H:i:s"));
				
				error_log("indexPopularityCount : course popularity index calculation start ".date("H:i:s"));
				$coursePopularityIndexs 	= $this->getPopularityCountDataForCourseV2();
				error_log("indexPopularityCount : course popularity index calculation end ".date("H:i:s"));
				
				$finalArrayToIndex = $this->_preparePopularityIndexFinalArray($univIdWithCourseId,$univPopularityIndexArr,$coursePopularityIndexs,$univCountPast21Days,$courseCountPast21Days);
				error_log("indexPopularityCount : final array created".date("H:i:s"));
				return $finalArrayToIndex;
		}

		function getScholarshipPopularityIndexData(){
			error_log("indexScholarshipPopularityCount : scholarship id and date fetching start ".date("H:i:s"));
			$this->scholarshipCronsModel = $this->_ci->load->model("scholarshipsDetailPage/scholarshipcronsmodel");
			$scholarshipDateData = $this->scholarshipCronsModel->getScholarshipAddedDate();
            $scholarshipPopularityIndexArr = $this->_generateScholarshipPopularityIndex($scholarshipDateData);
            error_log("indexScholarshipPopularityCount : get number of days since scholarship posted Done".date("H:i:s"));
            $formattedDataToIndex = $this->_prepareScholarshipPopularityIndexFinalArray($scholarshipPopularityIndexArr);
            return $formattedDataToIndex;			
		}

		function _getResultsFromElasticSearchQuery($clientCon,$scholarshipIds){
			$params = array();
	        $params['index'] = PAGEVIEW_INDEX_NAME;
	        $params['type'] = 'pageview';
	        $params['body'] = array(
                            "size"  => 0,
                            "query" => array(
                            	"bool"=> array(
	                                "filter"=> array(
	                                    "bool"=> array(
	                                    	"must"=> array(
	                                    		array(
									            	"terms"=> array(
									                	"pageEntityId"=> $scholarshipIds
									            	)
									            ),
									            array(
									            	"term"=> array(
										                "isStudyAbroad"=>"yes"
										            )
										        ),
										        array(
									            	"term"=> array(
									                	"pageIdentifier"=> "scholarshipDetailPage"
									            	)
									            )
									            
									        )
	                                	)
	                                )
                                )
                            ),
                            "aggregations"=> array(
							    "scshipwise"=> array(
							      "terms"=> array(
							        "field"=> "pageEntityId",
							        "size"=> ELASTIC_AGGS_SIZE
							    	)
							    )
							)
						);
	        $result = $clientCon->search($params);
	        $viewCountArr = $result['aggregations']['scshipwise']['buckets'];
	        $retArr = array();
	        foreach ($viewCountArr as $key => $viewCount) {
	        	$retArr[$viewCount['key']] = $viewCount['doc_count'];
	        }
	        return $retArr;
        }

        private function _prepareScholarshipPopularityIndexFinalArray($scholarshipPopularityIndexArr){
        	$finalArrayToIndex = array();
			foreach($scholarshipPopularityIndexArr as $scholarshipId=>$popularityIndex)
			{
				$scholarship = array();
				$scholarship['facetype'] 	 = 'abroadScholarship';
				$scholarship['saScholarshipPopularityIndex'] 	 = ($popularityIndex>0)?$popularityIndex:0;
				$scholarship['unique_id'] = 'saScholarship_'.$scholarshipId;
				$finalArrayToIndex[] = $scholarship;
			}
			return $finalArrayToIndex;
        }
		
		/*
		 * get records from abroad index log & index / delete their documents
		 * @return val: course ids that were to be indexed & deleted
		 */
		public function processAbroadIndexLog($listingType = '', $forDR="false")
		{
			$status = "pending";
			$startDate = '';
			if($forDR == "true"){
				$status = "";
				$startDate = date("Y-m-d H:i:s",strtotime(date()." - ".SOLR_INDEXING_INTERVAL." hours"));
			}
			
			if($listingType == 'scholarship'){
				return $this->saSearchModel->getAbroadScholarshipIndexLogData($status, $startDate);
			}else{
				return $this->saSearchModel->getAbroadIndexLogData($status, $startDate);
			}
		}
		/*
		 * update abroadindexlog to add index start time
		 */
		public function startIndexing($listingTypeAndIds)
		{
				if(count($listingTypeAndIds) == 0)
				{
						return false;
				}
				foreach($listingTypeAndIds as $row)
				{
						$data = array(
												'listingType'=>		$row['listingType'],
												'listingTypeId'=>	$row['listingTypeId'],
												'indexingStartAt'=> date("Y-m-d H:i:s")
												);
						$this->saSearchModel->updateAbroadIndexLog($data);
				}
		}
		/*
		 * update abroadindexlog to add index end time and completion status
		 */
		public function finishIndexing($listingType, $listingTypeId, $operation, $status)
		{
				if(!($listingTypeId >0))
				{
						return false;
				}
				$data = array(
										'listingType'=>$listingType,
										'listingTypeId'=>$listingTypeId,
										'operation'=>$operation,
										'indexingEndAt'=> date("Y-m-d H:i:s"),
										'status'=>$status
										);
				$this->saSearchModel->updateAbroadIndexLog($data);
		}
		/*
		 * function to get autosuggestor data for all combinations in the system
		 */
		public function getCourseAutosuggestorData()
		{
				$this->courseAutoSuggestorIndexData = array();
				error_log("COURSEAS: course cat details fetching start ".date("H:i:s"));
				// get cat, subcat, level, dc for all courses
				$this->_ci->benchmark->mark("start");
				$courseCategoryDetails = $this->abroadListingCommonLib->getAllCourseCategoryDetails();
				$this->_ci->benchmark->mark("end");
				error_log("COURSEAS: course cat details fetching Done ".date("H:i:s")." in ".$this->_ci->benchmark->elapsed_time('start','end'));
				//get specializations
				error_log("COURSEAS: course specialization & DC fetch start ".date("H:i:s"));
				$this->_ci->benchmark->mark("start2");
				$courseIds = array_filter(array_map(function($a){return $a['course_id'];},$courseCategoryDetails));
				error_log("COURSEAS: num(course)".count($courseIds));
				$courseSpecializations = $this->getSpecializationByCourseId($courseIds);
				$desiredCourses = $this->_getDesiredCourses();
				$this->_ci->benchmark->mark("end2");
				error_log("COURSEAS: course specialization & DC fetch done ".date("H:i:s")." in ".$this->_ci->benchmark->elapsed_time('start2','end2'));
				
				$this->_ci->benchmark->mark("start3");
				// process records to remove 0 from ldb_course_id, get names for each of these cats, subcats, dc...
				foreach($courseCategoryDetails as $courseCategoryRow)
				{
						$courseDocData = array();
						$courseCat = $this->categoryRepository->find($courseCategoryRow['category_id']);
						$courseSubCat = $this->categoryRepository->find($courseCategoryRow['sub_category_id']);
						$courseDocData['saCourseParentCategoryId'] = $courseCat->getId();
						$courseDocData['saCourseParentCategoryName'] = $courseCat->getName();
						$courseDocData['saCourseSubcategoryId'] = $courseSubCat->getId();
						$courseDocData['saCourseSubcategoryName'] = $courseSubCat->getName();
						$courseDocData['saCourseLevel1'] = $courseCategoryRow['course_level'];
						$ldbCourseId = explode(',',$courseCategoryRow['ldb_course_id']);
						$ldbCourseId = array_filter($ldbCourseId,function($a){return ($a>0?true:false);});
						$ldbCourseId = reset($ldbCourseId);
						if($ldbCourseId !== false){
								$courseDocData['saCourseDesiredCourseId'] = $ldbCourseId;
								$courseDocData['saCourseDesiredCourseName'] = $desiredCourses[$courseDocData['saCourseDesiredCourseId']];
						}
                                                
						// for those that do not involve specialization
						$this->_processCourseDataforAutoSuggestorIndex($courseDocData,false);
						if(count($courseSpecializations[$courseCategoryRow['course_id']])>0){
								//... add to autosuggestor doc along the way
								foreach($courseSpecializations[$courseCategoryRow['course_id']] as $courseSpecId => $courseSpecName)
								{
										$courseDocData['saCourseSpecializationId'] = $courseSpecId;
										$courseDocData['saCourseSpecializationName'] = $courseSpecName;
										// only for combinations that involve specializations (since they can be multiple others need not be created again) 
										$this->_processCourseDataforAutoSuggestorIndex($courseDocData,true);
								}
						}
				}
				$this->_ci->benchmark->mark("end3");
				error_log("COURSEAS : final array(".count($this->courseAutoSuggestorIndexData).") created".date("H:i:s")." in ".$this->_ci->benchmark->elapsed_time('start3','end3'));
                                //echo "See below<br>";_p($this->courseAutoSuggestorIndexData);die;
                                
                                return $this->courseAutoSuggestorIndexData;
		}
                
		private function _getAutoSuggestorSynonymForDocument($courseDocData)
		{
				//echo "<hr>"; _p($courseDocData);echo "<hr>";
				// config entries
				$autoSuggestorSynonymConfig= $this->autoSuggestorSynonym;
				$courseLevel=$courseDocData['saCourseLevel1'];
				$categoryId=$courseDocData['saCourseParentCategoryId'];
				$subCatId=$courseDocData['saCourseSubcategoryId'];
				$specializationId=$courseDocData['saCourseSpecializationId'];
				$autoSuggestorSynonymArray=array();
				
				foreach ($autoSuggestorSynonymConfig as $synonymConfigItemVal)
				{
						//echo "<br>";var_dump($synonymConfigItemVal);
						//  .. and the course level ,category Id match
						if(
						   ($synonymConfigItemVal['courseLevel'] == $courseLevel || $synonymConfigItemVal['courseLevel'] == "All") && 
						   ($synonymConfigItemVal['categoryId'] == $categoryId || $synonymConfigItemVal['categoryId'] == "All") &&
						   ($synonymConfigItemVal['subCatId'] == $subCatId || $synonymConfigItemVal['subCatId'] == "All") &&
						   (in_array($specializationId,$synonymConfigItemVal['specialization']) || $synonymConfigItemVal['specialization'] == "All")
						  )
						{ //echo "<br>::::::::::Y";
								// add the synonym
								array_push($autoSuggestorSynonymArray, $synonymConfigItemVal['synonym']);
						}
				}
			  return implode(" ", $autoSuggestorSynonymArray);
		}
		/*
		 * gets inventoryType (of university) for given course
		 */
		private function _getInventoryTypeForCourse($courseObj, $abroadCourse)
		{
				// check if cat sponsor
				//if($this->catSponsorInventory[$courseObj->getUniversityId()])
				if($this->checkIfCourseHasCatSponsorInventory($courseObj, $abroadCourse))
				{
						return $this->_ci->config->item('INVENTORY_TYPE_CAT_SPONSOR');
				}
				// check if main institute(university)
				else if($this->checkIfCourseHasMILInventory($courseObj, $abroadCourse))
				{
						return $this->_ci->config->item('INVENTORY_TYPE_MAIN');
				}
				// check if paid
				else if(in_array($courseObj->getCoursePackType(),array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID)))
				{
						return $this->_ci->config->item('INVENTORY_TYPE_PAID');
				}
				// check if free
				else {
						return $this->_ci->config->item('INVENTORY_TYPE_FREE');
				}
				
		}
		/*
		 * function to get list of cat sponsor, main universities
		 */
		private function _getInventoryForListings($universityIds=array())
		{
				//$this->catSponsorInventory = $this->saSearchModel->getCatSponsorUnivs($universityIds);
				$this->catSponsorInventory = $this->saSearchModel->getCatSponsorUnivsWithDetails($universityIds);

				$this->mainInventory = $this->saSearchModel->getMainUnivsWithDetails($universityIds);
		}
		/*
		 * this function prepares documents for updation of saCourseInventoryType
		 * Note: This is part of a one time script. inventory type is updated regularly via incremental indexing for documents already having this value
		 */
		private function _prepareDocDataForInventoryType($univIdWithCourseId){
				$courseRepository = $this->listingBuilder->getAbroadCourseRepository();
				
				$finalArrayToIndex = array();
				foreach($univIdWithCourseId as $univId=>$courseIds)
				{
						$courseObjs = $courseRepository->findMultiple($courseIds);
						foreach($courseObjs as $courseId=>$courseObj)
						{
								$abroadCourse = array('saCourseInventoryType'=>0);
								// check if cat sponsor
								//if($this->catSponsorInventory[$univId])
								if($this->checkIfCourseHasCatSponsorInventory($courseObj))
								{
										$abroadCourse['saCourseInventoryType'] = $this->_ci->config->item('INVENTORY_TYPE_CAT_SPONSOR');
								}
								// check if main institute(university)
								else if($this->checkIfCourseHasMILInventory($courseObj))
								{
										$abroadCourse['saCourseInventoryType'] = $this->_ci->config->item('INVENTORY_TYPE_MAIN');
								}
								else{
										// find pack type
										$abroadCourse['saCourseInventoryType'] = (in_array($courseObj->getCoursePackType(), array( GOLD_SL_LISTINGS_BASE_PRODUCT_ID,SILVER_LISTINGS_BASE_PRODUCT_ID,GOLD_ML_LISTINGS_BASE_PRODUCT_ID ))? $this->_ci->config->item('INVENTORY_TYPE_PAID'):$this->_ci->config->item('INVENTORY_TYPE_FREE'));
								}
								// dont need it now
								//$abroadCourse['saCourseInventoryType'] = 1;
								$abroadCourse['facetype'] = 'abroadlisting';
								$abroadCourse['unique_id'] = 'abroadcourse' . $courseId;
								$finalArrayToIndex[] = $abroadCourse;
						}
						unset($courseObjs);
				}
				return $finalArrayToIndex;
		}
		/*
		 * function to get check if course has cat-sponsor inventory
		 * - checks all instances of cat spon applied on a course's univ
		 * - returns true if this particular course statisfies the additional criteria of matching level,country,category
		 */
		public function checkIfCourseHasCatSponsorInventory($courseObj, $abroadCourse = NULL)
		{
				$catSponApplied = $this->catSponsorInventory[$courseObj->getUniversityId()];
				$matchFlag = false;
				if(count($catSponApplied) == 0)
				{
						return $matchFlag;
				}else{
						foreach($catSponApplied as $row)
						{
								if(is_null($abroadCourse))
								{
										//user course obj to match
										$courseObj->getCourseSubCategory();
										$subCategoryObj = $this->categoryRepository->find($courseObj->getCourseSubCategory());
										if(
										   $row['categoryId'] == $subCategoryObj->getParentId() &&
										   ($row['courseLevel'] == $courseObj->getCourseLevel1Value() || $row['courseLevel'] == "All") &&
										   $row['countryId'] == $courseObj->getCountryId()
										   )
										{
												$matchFlag = true;
												break;
										}
								}else{
										if(
										   $row['categoryId'] == 	$abroadCourse['saCourseParentCategoryId'] &&
										   ($row['courseLevel'] == 	$abroadCourse['saCourseLevel1'] || $row['courseLevel'] == "All") &&
										   $row['countryId'] == 	$abroadCourse['saUnivCountryId']
										   )
										{
												$matchFlag = true;
												break;
										}
								}
						}
						return $matchFlag;
				}
		}
		/*
		 * function to get check if course has MIL inventory
		 * - checks all instances of MIL applied on a course's univ
		 * - returns true if this particular course statisfies the additional criteria of matching country,category
		 */
		public function checkIfCourseHasMILInventory($courseObj, $abroadCourse = NULL)
		{
				$MILApplied = $this->mainInventory[$courseObj->getUniversityId()];
				$matchFlag = false;
				$packType = $courseObj->getCoursePackType();
			
				if(count($MILApplied) == 0 || $packType != 1)
				{
						return $matchFlag;
				}else{
						foreach($MILApplied as $row)
						{
								if(is_null($abroadCourse))
								{
										//user course obj to match
										$courseObj->getCourseSubCategory();
										$subCategoryObj = $this->categoryRepository->find($courseObj->getCourseSubCategory());
										if(
										   $row['categoryId'] == $subCategoryObj->getParentId() &&
										   $row['countryId'] == $courseObj->getCountryId()
										   )
										{
												$matchFlag = true;
												break;
										}
								}else{
										if(
										   $row['categoryId'] == 	$abroadCourse['saCourseParentCategoryId'] &&
										   $row['countryId'] == 	$abroadCourse['saUnivCountryId']
										   )
										{
												$matchFlag = true;
												break;
										}
								}
						}
						return $matchFlag;
				}
		}
		public function getInventoryTypeIndexData(){
				error_log("InventoryTypeIndexing : university with course Id fetching start ".date("H:i:s"));
				$univIdWithCourseId = $this->saSearchModel->getAllAbroadUniversitiesWithCourses();
				error_log("InventoryTypeIndexing : university with course Id fetching Done ".date("H:i:s"));
				
				error_log("InventoryTypeIndexing : get inventory type(cat spon / main) ".date("H:i:s"));
				$this->_getInventoryForListings();
				error_log("InventoryTypeIndexing : get inventory type(cat spon / main) end ".date("H:i:s"));
				
				$finalArrayToIndex = $this->_prepareDocDataForInventoryType($univIdWithCourseId);
				
				error_log("InventoryTypeIndexing : final array created".date("H:i:s"));
				
				return $finalArrayToIndex;
		}
		
		/*
		 * function to get unis that were cat spon & main but have expired last day
		 */
		public function getExpiredUnivs()
		{
				$lastDate =  date('Y-m-d', strtotime(' -1 day'));
				$csUnivs = $this->saSearchModel->getExpiredCategorySponsorUniv($lastDate);
				$mainUnivs = $this->saSearchModel->getExpiredMainUniv($lastDate);
				return array_merge($csUnivs,$mainUnivs);
		}

		/*
		 * function to get all scholarship data for indexing
		 */
		public function getDataRequiredForScholarship($scholarshipIdArr){
			if($scholarshipIdArr == 'All'){
				$this->scholarshipCronsModel = $this->_ci->load->model("scholarshipsDetailPage/scholarshipcronsmodel");
				$scholarshipIdArr = $this->scholarshipCronsModel->getScholarshipIds();
			}else if(is_numeric($scholarshipIdArr) && $scholarshipIdArr > 0){
				$scholarshipIdArr = array($scholarshipIdArr);
			}else if(!is_array($scholarshipIdArr)){
				return false;
			}
			$scholarshipPopularityIndex = $this->_getPopularityIndexForScholarships($scholarshipIdArr);
			$abroadScholarshipDataForIndex = array();
			if(count($scholarshipIdArr) > 0){
				$this->_ci->load->builder('scholarshipsDetailPage/scholarshipBuilder');
				$this->scholarshipBuilder    = new scholarshipBuilder();
      			$this->scholarshipRepository = $this->scholarshipBuilder->getScholarshipRepository();
      			$sections = array(
      				'basic' => array('name', 'type', 'category'),
      				'amount' => array('convertedTotalAmountPayout'),
      				'eligibility' => array('applicableCountries', 'applicableNationalities', 'intakeYear'),
      				'hierarchy' => array('courseLevel', 'parentCategory', 'course', 'university'),
      				'deadline' => array('applicationEndDate', 'numAwards'),
      				'specialRestrictions' => array('specialRestriction'),
      				);
      			$scholarshipObjs = $this->scholarshipRepository->findMultiple($scholarshipIdArr, $sections);
      			$abroadScholarshipDataForIndex = $this->_processScholarshipData($scholarshipObjs,$scholarshipPopularityIndex);
			}
			return $abroadScholarshipDataForIndex;
		}
		private function _getPopularityIndexForScholarships($scholarshipIds = array()){
			if(count($scholarshipIds) == 0)
			{
				return array();
			}
			$this->scholarshipCronsModel = $this->_ci->load->model("scholarshipsDetailPage/scholarshipcronsmodel");
			$scholarshipDateData = $this->scholarshipCronsModel->getScholarshipAddedDate($scholarshipIds);

			$scholarshipPopularityIndexArr = $this->_generateScholarshipPopularityIndex($scholarshipDateData);
			
            return $scholarshipPopularityIndexArr;	
		}


		private function _generateScholarshipPopularityIndex($scholarshipDateData)
		{
			$scholarshipIds = array_keys($scholarshipDateData);
			$ESConnectionLib = $this->_ci->load->library('trackingMIS/elasticSearch/ESConnectionLib');
            $clientCon = $ESConnectionLib->getShikshaESServerConnection();
            $viewCountData = $this->_getResultsFromElasticSearchQuery($clientCon,$scholarshipIds);

			$scholarshipPopularityIndexArr = array();
            foreach ($scholarshipDateData as $scholarshipId => $postedDate) {
				
            	$tempDate = strtotime($postedDate);
				$NewDate = date('M j, Y', $tempDate);
            	$numDaysPosted =  date_diff(date_create($NewDate),date_create(date("M j, Y")));
            	$numDays = 1+$numDaysPosted->format('%R%a days');
            	$scholarshipPopularityIndexArr[$scholarshipId] = $viewCountData[$scholarshipId]/$numDays;
            }
			return $scholarshipPopularityIndexArr;
		}

		private function _processScholarshipData(& $scholarshipObjs, $scholarshipPopularityIndex = array()){
			$scholarshipIndexFinalData = array();
			$courseRepository = $this->listingBuilder->getAbroadCourseRepository();
			$universityRepository   = $this->listingBuilder->getUniversityRepository();
			foreach ($scholarshipObjs as $scholarshipId => $scholarshipObj) {
				$indexData = array();
				$indexData['facetype'] = 'abroadScholarship';
				$indexData['unique_id'] = 'saScholarship_'.$scholarshipId;

				$indexData['saScholarshipId'] = $scholarshipId;
				$indexData['saScholarshipName'] = $scholarshipObj->getName();
				$indexData['saScholarshipType'] = $scholarshipObj->getScholarshipType();
				$indexData['saScholarshipCategory'] = $scholarshipObj->getCategory();
				$indexData['saScholarshipPopularityIndex'] = ($scholarshipPopularityIndex[$scholarshipId] > 0 ? $scholarshipPopularityIndex[$scholarshipId] : 0);
				
				if($scholarshipObj->getAmount()->getConvertedTotalAmountPayout() != ''){
					$indexData['saScholarshipAmount'] = $scholarshipObj->getAmount()->getConvertedTotalAmountPayout();
				}
				$courseIdArr = $courseLevel = $courseCategory = array();
				if($indexData['saScholarshipCategory'] == 'external'){
					//course level info and category Id info
					$courseLevel = $scholarshipObj->getHierarchy()->getCourseLevel();
					$courseCategory = $scholarshipObj->getHierarchy()->getParentCategory();

					//applicable country info
					$applicableCountries = $scholarshipObj->getApplicationData()->getApplicableCountries();
					if($applicableCountries[0] != -1){
						$indexData['saScholarshipCountryId'] = $applicableCountries;
					}else{
						$indexData['saScholarshipCountryId'] = array(1);
					}

				}else{
					//course level info and category Id info
					$mappedCourses = $courseRepository->findMultiple($scholarshipObj->getHierarchy()->getCourses());
					foreach ($mappedCourses as $obj) {
						$courseIdArr[] = $obj->getId();
						$courseLevel[] = $obj->getCourseLevel1Value();
						
						$subCatObj = $obj->getCourseSubCategory();
						$subCategoryObj = $this->categoryRepository->find($subCatObj);
						$courseCategory[] = $subCategoryObj->getParentId();
		            }
		            if(!empty($courseIdArr)){
		            	$indexData['saScholarshipCourseId'] = $courseIdArr;
		            }

		            //applicable country info and university info
		            $indexData['saScholarshipUnivId'] = $scholarshipObj->getHierarchy()->getUniversity();
		            $university = $universityRepository->find($indexData['saScholarshipUnivId']);
		            $univCountryId = reset($university->getLocations())->getCountry()->getId();
		            $indexData['saScholarshipCountryId'] = array($univCountryId);
		            $indexData['saScholarshipUnivName'] = $university->getName();
		            $indexData['saScholarshipUnivIdNameMap'] = $indexData['saScholarshipUnivName'].':'.$indexData['saScholarshipUnivId']; 


				}

				if(!empty($courseLevel)){
					$indexData['saScholarshipCourseLevel'] = array_unique($courseLevel);
				}
				if(!empty($courseCategory)){
					$indexData['saScholarshipCategoryId'] = array_unique($courseCategory);
				}
				
				$applicableEndDate = $scholarshipObj->getDeadline()->getApplicationEndDate();
				if(!empty($applicableEndDate)){
					$indexData['saScholarshipApplicationEndDate'] = date('Y-m-d', strtotime($applicableEndDate)).'T23:59:59Z';
				}

				$applicableNationalities = $scholarshipObj->getApplicationData()->getApplicableNationalities();
				if(!empty($applicableNationalities)){
					if($applicableNationalities[0] != -1){
						$indexData['saScholarshipStudentNationality'] = $applicableNationalities;
					}else{
						$indexData['saScholarshipStudentNationality'] = array(1);
					}
				}

				$intakeYears = $scholarshipObj->getApplicationData()->getIntakeYears();
				if(!empty($intakeYears)){
                                    $years = array();
					foreach ($intakeYears as &$value) {
						$value .= 'T00:00:00Z';
                                                $years[] = date('Y', strtotime($value));
					}
                                        $years = array_unique($years);
					$indexData['saScholarshipIntakeDate'] = $intakeYears;
                                        $indexData['saScholarshipIntakeYear'] = $years;
				}
				
				$specialRestriction = $scholarshipObj->getSpecialRestrictions()->getRestrictions();
				if(!empty($specialRestriction)){
					$indexData['saScholarshipSpecialRestriction'] = $specialRestriction;
				}
				

				$awardCount = $scholarshipObj->getDeadline()->getNumAwards();
				if(!empty($awardCount)){
					$indexData['saScholarshipAwardsCount'] = $awardCount;
				}
				//$indexData['saScholarshipPopularityIndex'] = '';
				
				$scholarshipIndexFinalData[] = $indexData;
			}
			return $scholarshipIndexFinalData;
		}
		/*
		 * check if internal scholarship is available on each course that is to be indexed
		 */
		public function getScholarshipAvailability($courseIds=array())
		{
			$scholarshipInfo = $this->saSearchModel->checkScholarshipsByCourseId($courseIds);
			return $scholarshipInfo;
		}

		public function getCourseIndexDataByModifiedCurrency()
		{
			//courses whose fees currency exch rate was changed in past 24 hours
			$courseIds = $this->saSearchModel->getCourseIdsForModifiedFeesCurrencyRate();
			if(count($courseIds) == 0)
			{
				return $courseIds;
			}
			$indexData = $this->getDataRequiredForCourse($courseIds);
			return $indexData;
		}
		
}

