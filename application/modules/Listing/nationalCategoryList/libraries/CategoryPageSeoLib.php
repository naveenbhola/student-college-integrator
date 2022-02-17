<?php
class CategoryPageSEOLib {
	private static $defaultAnyValues;
	private $virtualCityToCityMapping;

	function __construct() {
		$this->CI =& get_instance();
		
		$this->logFileName = 'log_category_page_seo_'.date('y-m-d');
        $this->logFilePath = '/tmp/'.$this->logFileName;

        global $statesToIgnore;
        $this->statesToIgnore = $statesToIgnore;
        //$this->statesToIgnore = array('128', '129', '130', '131', '134', '135', '345');

		//load model
		$this->categoryPageSEOModel = $this->CI->load->model('nationalCategoryList/categorypageseomodel');

		$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$this->hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
		$this->baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();
		$this->CI->load->library('listingBase/BaseAttributeLibrary');
		$this->baseAttributeLibrary = new BaseAttributeLibrary();
		$this->CI->load->library("examPages/ExamMainLib");
		$this->examMainLib = new ExamMainLib();
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder();
		$this->locationRepository = $locationBuilder->getLocationRepository();

		$this->setDefaultValues();

		$this->CI->load->model("nationalCategoryList/CategoryPageSeoModel");
		$this->categorypageseomodel = new CategoryPageSeoModel();

		$this->listingCommonHelper = $this->CI->load->helper("listingCommon/listingcommon");

		$this->courseHomePageUrlGenerator = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
		
		//load libraries to send mail
        //$this->alertClient = $this->CI->load->library('alerts_client');
	}

	private function setDefaultValues(){
		CategoryPageSEOLib::$defaultAnyValues = array(
			'examSelection'           => array(
				'indexName'     => 'exam',
				'anyEquivalent' => categorypageseomodel::$anyExam),
			'courseLocationSelection' => array(
				'indexName'     => 'location',
				'anyEquivalent' => categorypageseomodel::$anyLocation
			),
			'specializationSelection' => array(
				'indexName'     => 'specialization',
				'anyEquivalent' => categorypageseomodel::$anySpecialization
			),
			'baseCourseSelection'     => array(
				'indexName'     => 'non_popular_course',
				'anyEquivalent' => categorypageseomodel::$anyNonPopularCourse
			),
			'deliveryMethods'         => array(
				'indexName'     => 'delivery_method',
				'anyEquivalent' => categorypageseomodel::$anyDeliveryMethod
			),
			'educationTypes'          => array(
				'indexName'     => 'education_type',
				'anyEquivalent' => categorypageseomodel::$anyEducationType
			),
			'courseCredentials'       => array(
				'indexName'     => 'credential',
				'anyEquivalent' => categorypageseomodel::$anyCredential
			),
			'popularCourses'          => array(
				'indexName'     => 'popular_course',
				'anyEquivalent' => categorypageseomodel::$anyPopularCourse
			),
			'substreamList'           => array(
				'indexName'     => 'substream',
				'anyEquivalent' => categorypageseomodel::$anySubStream
			),
			'streamList'              => array(
				'indexName'     => 'stream',
				'anyEquivalent' => categorypageseomodel::$anyStream
			)
		);
	}

	function getRulesToBeProcessed($status = 'live') {
		$time_start = microtime_float(); $start_memory = memory_get_usage();
		
		if($status == 'live') {
			$rules = $this->categoryPageSEOModel->getLiveRulesToBeProcessed($status);
		} else {
			$rules = $this->categoryPageSEOModel->getDeletedRulesToBeProcessed($status);
		}
		
		error_log("Section: ".$status." rules fetched from DB | ".getLogTimeMemStr($time_start, $start_memory)."\n", 3, $this->logFilePath);
		return $rules;
	}

	function processRules($rules) {
		$time_start_1 = microtime_float(); $start_memory_1 = memory_get_usage();
		$id = 0;
		foreach ($rules as $key => $rule) {
			$time_start_2 = microtime_float(); $start_memory_2 = memory_get_usage();
			
			$valid = $this->validateRule($rule);
			if(!$valid) {
				error_log("Section: Rule invalid ".$rule['id'].". Skipped. | ".getLogTimeMemStr($time_start_2, $start_memory_2)."\n", 3, $this->logFilePath);
				continue;
			}

			if(!$rule['location']) {
				$this->categoryPageSEOModel->deleteLocationCombinations($rule);
			}
			
			$ruleId = $rule['id'];
			$store = array();
			
			//populate stream
			$streams = $this->populateStreams($rule['stream']);
			//_p($streams);

			//populate popular courses
			$popBaseCourses = $this->populatePopularCourse($rule['popular_course']);
			//_p($popBaseCourses);

			//populate education type
			$educationTypes = $this->populateEducationType($rule['education_type']);
			//_p($educationTypes);

			//populate delivery method
			$deliveryMethods = $this->populateDeliveryMethod($rule['delivery_method']);
			//_p($deliveryMethods);

			//populate credential
			$credentials = $this->populateCredential($rule['credential']);
			//_p($credentials);

			//populate location
			$locations = $this->populateLocation($rule['location']);
			$cities = $locations['cities'];
			$states = $locations['states'];
			
			foreach ($streams as $key => $stream) {
				$streamId = '';
				if(!empty($stream)) {
					$streamId = $stream->getId();
				}

				//populate substream
				$substreams = array();
				if(!empty($streamId) && !empty($rule['substream'])) {
					if($rule['substream'] == 'any') {
						$tree = $this->hierarchyRepository->getSubstreamSpecializationByStreamId($streamId);
						$substreamIds = array_keys($tree[$streamId]['substreams']);
						if(!empty($substreamIds)) {
							$substreams = $this->hierarchyRepository->findMultipleSubstreams($substreamIds);
						}
					} else {
						if(!empty($rule['specialization'])) {
							$tree = $this->hierarchyRepository->getSubstreamSpecializationByStreamId($streamId);
						}
						$substreams[] = $this->hierarchyRepository->findSubstream($rule['substream']);
					}
				} else {
					$substreams[] = NULL;
				}
				//_p($substreams);

				foreach ($substreams as $key => $substream) {
					$substreamId = '';
					if(!empty($substream)) {
						$substreamId = $substream->getId();
					}
					
					foreach ($popBaseCourses as $popularCourseId => $popularCourse) {
					 	//populate specialization
					 	$specializations = array();
					 	if(!empty($rule['specialization'])) {
					 		if(!empty($substream)) {
					 			$specializationIds = array_keys($tree[$streamId]['substreams'][$substreamId]['specializations']);
					 			if(!empty($specializationIds)) {
						 			$specializations = $this->hierarchyRepository->findMultipleSpecializations($specializationIds);
						 		}
							}
							elseif(!empty($streamId)) {
								$specializations = $this->hierarchyRepository->getSpecializationByStreamSubstreamId($streamId, '', 0, 'object');
							} 
							elseif(!empty($popularCourse)) {
								$courseId = $popularCourse->getId();
								$specializations = $this->baseCourseRepository->getSpecializationsByBaseCourseIds($courseId, '', 0, 'object');
							}
						} else {
							$specializations[] = NULL;
						}
						//_p($specializations);

						foreach ($specializations as $key => $specialization) {
							$specializationId = '';
							if(!empty($specialization)) {
								$specializationId = $specialization->getId();
							}

							//populate non popular base courses
							$baseCourses = array();
							if(!empty($rule['non_popular_course'])) {
								//$baseCourses = $this->baseCourseRepository->getBaseCoursesByBaseEntities($streamId, $substreamId, $specializationId, 0, 'object');
								if($rule['non_popular_course'] == 'any') {
									$baseCourses = $this->baseCourseRepository->getBaseCoursesByBaseEntities($streamId, $substreamId, $specializationId, 0, 'object');
								} else {
									// $baseCourses = $this->populateBaseCourse($rule['non_popular_course']);
									$baseCourseObj = $this->baseCourseRepository->find($rule['non_popular_course']);
									$baseCourses[$baseCourseObj->getId()] = $baseCourseObj;
								}
							} else {
								$baseCourses[] = NULL;
							}
							//_p($baseCourses);

							foreach ($baseCourses as $key => $baseCourse) {
								$baseCourseId = '';
								if(!empty($baseCourse)) {
									$baseCourseId = $baseCourse->getId();
								}
								//populate exams
								$exams = array(); $hierarchy = array();
								if(!empty($rule['exam'])) {
									if(!empty($streamId)) {
										$hierarchy[] = array('streamId'=>$streamId, 'substreamId'=>$substreamId, 'specializationId'=>$specializationId);
										$exams = $this->examMainLib->getAllMainExamsByBaseEntities($hierarchy);
									}
									elseif(!empty($popularCourseId)) {
										$data = $this->baseCourseRepository->getBaseEntitiesByBaseCourseIds($popularCourseId, '', $specializationId);
										$hierarchy = array();
										foreach ($data as $key => $value) {
											$hierarchy[] = array('streamId'=>$value['stream_id'], 'substreamId'=>$value['substream_id'], 'specializationId'=>$value['specialization_id']);
										}
										//_p($hierarchy);
										$exams = $this->examMainLib->getAllMainExamsByBaseEntities($hierarchy);
										//_p($exams);
									}
								} else {
									$exams[] = NULL;
								}
								
								foreach ($exams as $examId => $exam) {
									
									foreach ($educationTypes as $educationTypeId => $educationType) {
										
										foreach ($deliveryMethods as $deliveryMethodId => $deliveryMethod) {
											
											foreach ($credentials as $credentialId => $credential) {

												$this->data['stream'] = $stream;
												$this->data['substream'] = $substream;
												$this->data['specialization'] = $specialization;
												$this->data['popularCourse'] = $popularCourse;
												$this->data['baseCourse'] = $baseCourse;
												$this->data['exam'] = $exam;
												$this->data['educationType'] = $educationType;
												$this->data['deliveryMethod'] = $deliveryMethod;
												$this->data['credential'] = $credential;

												foreach ($cities as $key => $city) {
													$this->data['city'] = $city;

													$store[$id]['ruleId'] = $ruleId;
													$store[$id]['streamId'] = $streamId;
													$store[$id]['substreamId'] = $substreamId;
													$store[$id]['specializationId'] = $specializationId;
													if(!empty($baseCourseId)) {
														$store[$id]['baseCourseId'] = $baseCourseId;
													} else if(!empty($popularCourseId)) {
														$store[$id]['baseCourseId'] = $popularCourseId;
													}
													$store[$id]['educationTypeId'] = $educationTypeId;
													$store[$id]['deliveryMethodId'] = $deliveryMethodId;
													$store[$id]['credentialId'] = $credentialId;
													$store[$id]['examId'] = $examId;
													
													if(is_object($city)) {
														$store[$id]['cityId'] = $city->getId();
														$store[$id]['stateId'] = ($city->getStateId() == 1)?-1:$city->getStateId();
													} elseif($city) {
														$store[$id]['cityId'] = 1;
														$store[$id]['stateId'] = 1;
													}

													$store[$id]['url'] = $this->sanitizeUrl($this->replacePlaceHolders($rule['url'], 1));
													$store[$id]['hashUrl'] = murmurhash3_int($store[$id]['url']);
													$store[$id]['breadcrumb'] = $this->createBreadcrumb($rule['breadcrumb'], $rule['url']);
													$store[$id]['metaTitle'] = $this->replacePlaceHolders($rule['meta_title']);
													$store[$id]['metaDescription'] = $this->replacePlaceHolders($rule['meta_description']);
													$store[$id]['headingDesktop'] = $this->replacePlaceHolders($rule['heading_desktop']);
													$store[$id]['headingMobile'] = $this->replacePlaceHolders($rule['heading_mobile']);
													
													$id++;
												}
												
												foreach ($states as $key => $state) {
													$this->data['state'] = $state;
													$this->data['city'] = NULL;

													$store[$id]['ruleId'] = $ruleId;
													$store[$id]['streamId'] = $streamId;
													$store[$id]['substreamId'] = $substreamId;
													$store[$id]['specializationId'] = $specializationId;
													if(!empty($baseCourseId)) {
														$store[$id]['baseCourseId'] = $baseCourseId;
													} else if(!empty($popularCourseId)) {
														$store[$id]['baseCourseId'] = $popularCourseId;
													}
													$store[$id]['educationTypeId'] = $educationTypeId;
													$store[$id]['deliveryMethodId'] = $deliveryMethodId;
													$store[$id]['credentialId'] = $credentialId;
													$store[$id]['examId'] = $examId;
													$store[$id]['stateId'] = $state->getId();
													$store[$id]['cityId'] = 1;

													$store[$id]['url'] = $this->sanitizeUrl($this->replacePlaceHolders($rule['url'], 1));
													$store[$id]['hashUrl'] = murmurhash3_int($store[$id]['url']);
													$store[$id]['breadcrumb'] = $this->createBreadcrumb($rule['breadcrumb'], $rule['url']);
													$store[$id]['metaTitle'] = $this->replacePlaceHolders($rule['meta_title']);
													$store[$id]['metaDescription'] = $this->replacePlaceHolders($rule['meta_description']);
													$store[$id]['headingDesktop'] = $this->replacePlaceHolders($rule['heading_desktop']);
													$store[$id]['headingMobile'] = $this->replacePlaceHolders($rule['heading_mobile']);

													$id++;
												}
												$this->data = NULL;
											}
										}
									}
								}
							}
						}
					}
				}
			}
			error_log("Section: Url created for rule id - ".$ruleId." | ".getLogTimeMemStr($time_start_2, $start_memory_2)."\n", 3, $this->logFilePath);

			$time_start_3 = microtime_float(); $start_memory_3 = memory_get_usage();
			
			$response = $this->categoryPageSEOModel->storeProcessedDataInDb($store);
			$rulesProcessed[] = $ruleId;

			error_log("Section: Inserted in DB for rule id - ".$ruleId." | ".getLogTimeMemStr($time_start_3, $start_memory_3)."\n", 3, $this->logFilePath);
			error_log("Section: Rule ".$ruleId." processed. Status - ".$response.". Total pages created - ".($id-1)." | ".getLogTimeMemStr($time_start_2, $start_memory_2)."\n", 3, $this->logFilePath);
		}
		error_log("Section: All rules processed | ".getLogTimeMemStr($time_start_1, $start_memory_1)."\n", 3, $this->logFilePath);
		
		return $rulesProcessed;
	}

	function populateStreams($streamRule) {
		$streams = array();
		if(!empty($streamRule)) {
			if($streamRule == 'any') {
				if(empty($this->allStreams)) {
					$this->allStreams = $this->hierarchyRepository->getAllStreams('object');
				}
				$streams = $this->allStreams;
			} else {
				$streamObj = $this->hierarchyRepository->findStream($streamRule);
				$streams[] = $streamObj;
			}
		} else {
			$streams[] = NULL;
		}
		return $streams;
	}

	function populatePopularCourse($popularCourseRule) {
		$popBaseCourses = array();
		if(!empty($popularCourseRule)) {
			if($popularCourseRule == 'any') {
				if(empty($this->allPopularCourses)) {
					$this->allPopularCourses = $this->baseCourseRepository->getAllPopularCourses('object');
				}
				$popBaseCourses = $this->allPopularCourses;
			} else {
				$baseCourseObj = $this->baseCourseRepository->find($popularCourseRule);
				$popBaseCourses[$baseCourseObj->getId()] = $baseCourseObj;
			}
		} else {
			$popBaseCourses[] = NULL;
		}
		return $popBaseCourses;
	}

	function populateEducationType($educationTypeRule) {
		$educationTypes = array();
		if(!empty($educationTypeRule) && $educationTypeRule != 'none') {
			if($educationTypeRule == 'any') {
				$type = $this->baseAttributeLibrary->getValuesForAttributeByName('education type');
				$educationTypes = $type['education type'];
			} else {
				$type = $this->baseAttributeLibrary->getValueNameByValueId($educationTypeRule);
				$educationTypes[$educationTypeRule] = $type[$educationTypeRule];
			}
		} else {
			$educationTypes[] = NULL;
		}
		return $educationTypes;
	}

	function populateDeliveryMethod($deliveryMethodRule) {
		$deliveryMethods = array();
		if(!empty($deliveryMethodRule) && $deliveryMethodRule != 'none') {
			if($deliveryMethodRule == 'any') {
				$method = $this->baseAttributeLibrary->getValuesForAttributeByName('medium/delivery method');
				$deliveryMethods = $method['medium/delivery method'];
			} else {
				$method = $this->baseAttributeLibrary->getValueNameByValueId($deliveryMethodRule);
				$deliveryMethods[$deliveryMethodRule] = $method[$deliveryMethodRule];
			}
		} else {
			$deliveryMethods[] = NULL;
		}
		return $deliveryMethods;
	}

	function populateCredential($credentialRule) {
		$credentials = array();
		if(!empty($credentialRule) && $credentialRule != 'none') {
			if($credentialRule == 'any') {
				$cred = $this->baseAttributeLibrary->getValuesForAttributeByName('credential');
				foreach ($cred['credential'] as $key => $value) {
					if(strtolower($value) == 'none') {
						unset($cred['credential'][$key]);
					}
				}
				$credentials = $cred['credential'];
			} else {
				$cred = $this->baseAttributeLibrary->getValueNameByValueId($credentialRule);
				$credentials[$credentialRule] = $cred[$credentialRule];
			}
		} else {
			$credentials[] = NULL;
		}
		
		return $credentials;
	}

	function populateLocation($locationRule) {
		$cities = array();
		$states = array();
		if(!empty($locationRule)) {
			//populate cities
			if(empty($this->allCities)) {
				$this->allCities = $this->locationRepository->getCities(2, 1); //_p($cities);
			}
			$cities = $this->allCities;
			//$cities = array($this->allCities[0], $this->allCities[1], $this->allCities[3], $this->allCities[173]);
			$cities[] = 1; // all india

			//populate states
			if(empty($this->allStates)) {
				$this->allStates = $this->locationRepository->getStatesByCountry(2);
				foreach ($this->allStates as $key => $value) {
					if(in_array($value->getId(), $this->statesToIgnore)) {
						unset($this->allStates[$key]);
					}
				}
			}
			$states = $this->allStates;
			//$states = array($this->allStates[0], $this->allStates[8], $this->allStates[9]);
		} else {
			$cities[] = 1;
		}
		
		return array('cities'=>$cities, 'states'=>$states);
	}

	function replacePlaceHolders($string, $sanitizeEntities = 0) {
		if(!empty($this->data['stream'])) {
			$streamName = $this->data['stream']->getUrlName();
			if($sanitizeEntities) {
				$streamName = seo_url_lowercase($streamName);
			}
			$string = str_replace('[stream]', $streamName, $string);
		}

		if(!empty($this->data['substream'])) {
			$substreamName = $this->data['substream']->getUrlName();
			if($sanitizeEntities) {
				$substreamName = seo_url_lowercase($substreamName);
			}
			$string = str_replace('[substream]', $substreamName, $string);
		}

		if(!empty($this->data['specialization'])) {
			$specializationName = $this->data['specialization']->getUrlName();
			if($sanitizeEntities) {
				$specializationName = seo_url_lowercase($specializationName);
			}
			$string = str_replace('[specialization]', $specializationName, $string);
		}

		if(!empty($this->data['baseCourse'])) {
			$baseCourseName = $this->data['baseCourse']->getUrlName();
			if($sanitizeEntities) {
				$baseCourseName = seo_url_lowercase($baseCourseName);
			}
			$string = str_replace('[course]', $baseCourseName, $string);
		}

		if(!empty($this->data['popularCourse'])) {
			$baseCourseName = $this->data['popularCourse']->getUrlName();
			if($sanitizeEntities) {
				$baseCourseName = seo_url_lowercase($baseCourseName);
			}
			$string = str_replace('[course]', $baseCourseName, $string);
		}

		if(!empty($this->data['exam'])) {
			$examName = $this->data['exam'];
			if($sanitizeEntities) {
				$examName = seo_url_lowercase($examName);
			}
			$string = str_replace('[exam]', $examName, $string);
		}

		if(!empty($this->data['educationType'])) {
			$educationType = $this->data['educationType'];
			if($educationType == 'Full Time' || in_array($this->data['deliveryMethod'], array('Online', 'Distance/Correspondence'))) {
				//drop education type from URL
				if(strpos($string, '-[education_type]-') !== false) {
					$string = str_replace('-[education_type]-', '', $string);
				} 
				elseif(strpos($string, '[education_type]-') !== false) {
					$string = str_replace('[education_type]-', '', $string);
				}
				elseif(strpos($string, '-[education_type]') !== false) {
					$string = str_replace('-[education_type]', '', $string);
				} else {
					$string = str_replace('[education_type]', '', $string);
				}
			} else {
				$string = str_replace('[education_type]', $educationType, $string);
			}
		}

		if(!empty($this->data['deliveryMethod'])) {
			$deliveryMethod = $this->data['deliveryMethod'];
			if($sanitizeEntities) {
				$deliveryMethod = seo_url_lowercase($deliveryMethod);
			}
			$string = str_replace('[delivery_method]', $deliveryMethod, $string);
		}

		if(!empty($this->data['credential'])) {
			$credential = $this->data['credential'];
			if($sanitizeEntities) {
				$credential = seo_url_lowercase($credential);
			}
			$string = str_replace('[credential]', $credential, $string);
		}

		if(!empty($this->data['city'])) {
			if(is_object($this->data['city'])) {
				$city = $this->data['city']->getName();
			} elseif($this->data['city']) {
				if(empty($this->data['stream']) && empty($this->data['popularCourse']) && $sanitizeEntities) { //location CTP - all india
					$city = '';
				} else {
					$city = 'India';
				}
			}
			if($sanitizeEntities) {
				$city = seo_url_lowercase($city);
			}
			$string = str_replace('[location]', $city, $string);
		}

		if(!empty($this->data['state'])) {
			$state = $this->data['state']->getName();
			if($sanitizeEntities) {
				$state = seo_url_lowercase($state);
			}
			$string = str_replace('[location]', $state, $string);
		}

		return $string;
	}

	private function createBreadcrumb($breadcrumbRule, $urlRule) {
		$breadcrumbRuleArr = explode('>', $breadcrumbRule);
		foreach ($breadcrumbRuleArr as $key => $breadcrumbPart) {
			$breadcrumbPart = trim($breadcrumbPart);
			$breadcrumbNameUrl = $this->replacePlaceHoldersForBreadcrumb($breadcrumbPart, $urlRule);
			if(!empty($breadcrumbNameUrl)) {
				$breadcrumbArr[] = $breadcrumbNameUrl;
			}
			
			/*
			if($breadcrumbPart == '[colleges]') {
				$breadcrumbPart = trim($breadcrumbRuleArr[$key+1]);
				$breadcrumbPart = str_replace('[location]', 'India', $breadcrumbPart);
				$breadcrumbUrlRule = str_replace('[location]', 'India', $breadcrumbUrlRule);
				
				if(is_object($this->data['city']) || !empty($this->data['state'])) { //if current combination is not all India, make breadcrumb for all India for this page
					$breadcrumbArr[] = $this->replacePlaceHoldersForBreadcrumb($breadcrumbPart, $breadcrumbUrlRule);
				}
			} else {
				$breadcrumbArr[] = $this->replacePlaceHoldersForBreadcrumb($breadcrumbPart);
			} */
		}
		
		return json_encode($breadcrumbArr);
	}

	function replacePlaceHoldersForBreadcrumb($breadcrumbPart, $urlRule) {
		if($breadcrumbPart == 'Home') {
			$breadcrumb['name'] = "Home";
		    $breadcrumb['url'] = '/'; //can add this at the time of front end as well
		}

		elseif($breadcrumbPart == '[stream]') {
			$breadcrumb['name'] = $this->replacePlaceHolders($breadcrumbPart);
			$breadcrumb['url'] = $this->courseHomePageUrlGenerator->getUrlByParams(array('stream_id'=>$this->data['stream']->getId()));
		    //$breadcrumb['url'] = ''; //stream homepage url, stream obj is in - $this->data['stream']
		}

		elseif($breadcrumbPart == '[substream]') {
			$breadcrumb['name'] = $this->replacePlaceHolders($breadcrumbPart);
			$breadcrumb['url'] = $this->courseHomePageUrlGenerator->getUrlByParams(array('stream_id'=>$this->data['stream']->getId(), 'substream_id'=>$this->data['substream']->getId()));
		    //$breadcrumb['url'] = ''; //substream homepage url, substream obj is in - $this->data['substream']
		}

		elseif($breadcrumbPart == '[course]') {
			$breadcrumb['name'] = $this->replacePlaceHolders($breadcrumbPart);
			if(!empty($this->data['baseCourse'])) {
				$breadcrumb['url'] = $this->courseHomePageUrlGenerator->getUrlByParams(array('base_course_id'=>$this->data['baseCourse']->getId()));
			}
			elseif(!empty($this->data['popularCourse'])) {
				$breadcrumb['url'] = $this->courseHomePageUrlGenerator->getUrlByParams(array('base_course_id'=>$this->data['popularCourse']->getId()));
			}
		    //$breadcrumb['url'] = ''; //course homepage url, course obj is in - $this->data['popularCourse']
		}

		elseif($breadcrumbPart == 'colleges') {
			$breadcrumb['name'] = "Colleges in India";
			if(is_object($this->data['city']) || !empty($this->data['state'])) { //if current combination is not all India, make breadcrumb for all India for this page
				if(empty($this->data['stream']) && empty($this->data['popularCourse'])) { //location CTP - all india CTP url
					$ruleForBreadCrumbUrl = str_replace('[location]', '', $urlRule);
				} else {
					$ruleForBreadCrumbUrl = str_replace('[location]', 'India', $urlRule);
				}

				$breadcrumb['url'] = '/'.$this->sanitizeUrl($this->replacePlaceHolders($ruleForBreadCrumbUrl, 1));
			} else {
				$breadcrumb['url'] = '';
			}
		}

		else {
			if(is_object($this->data['city']) || !empty($this->data['state'])) { //if current combination is not all India, make breadcrumb for all India for this page
				$breadcrumbPart = "Colleges in [location]";
				$breadcrumb['name'] = $this->replacePlaceHolders($breadcrumbPart);
				$breadcrumb['url'] = ''; //for static texts and last node of breadcrumb - no need of link
			}
		}
		
		if(!empty($breadcrumb['url'])) {
			$breadcrumb['url'] = removeDomainFromUrl($breadcrumb['url']);
		}

		return $breadcrumb;
	}

	function sanitizeUrl($url) {
		$url = strtolower($url);
		$url = str_replace(" ", "-", $url);
		$url = str_replace("&", "-", $url);
		$url = str_replace("(", "-", $url);
		$url = str_replace(")", "-", $url);
		$url = str_replace("'", "", $url);
		$url = str_replace(".", "", $url);
		$url = preg_replace("/[-]+/", "-", $url);
		$url = trim($url, "-");
		$url = trim($url, "/");
		return $url;
	}
	/*
	function sanitizeEntity($entityString) {
		$entityString = strtolower($entityString);
		$entityString = str_replace(" ", "-", $entityString);
		$entityString = str_replace("/", "-", $entityString);
		$entityString = str_replace("&", "-", $entityString);
		$entityString = str_replace("#", "-", $entityString);
		$entityString = str_replace("+", "-", $entityString);
		$entityString = str_replace("(", "-", $entityString);
		$entityString = str_replace(")", "-", $entityString);
		$entityString = str_replace("'", "", $entityString);
		$entityString = str_replace(".", "", $entityString);
		$entityString = str_replace(",", "", $entityString);
		$entityString = preg_replace("/[-]+/", "-", $entityString);
		$entityString = trim($entityString, "-");
		return $entityString;
	}
	*/
	function deleteProcessedRules($rules) {
		foreach ($rules as $key => $rule) {
			//if 404 or 301 without placeholder
			if($rule['show_404'] || (strpos($rule['301_url'], '[') === false)) {
				//update row with status deleted and show 404 or static 301
				$this->categoryPageSEOModel->deleteProcessedDataByRuleId($rule);
			}

			//if any placeholder exists in 301
			elseif(!empty($rule['301_url']) && (strpos($rule['301_url'], '[') !== false)) {
				$data = $this->categoryPageSEOModel->getProcessedDataToBeDeleted($rule['id']);
				foreach ($data as $key => $value) {
					if(!empty($value['stream_id'])) {
						$this->data['stream'] = $this->hierarchyRepository->findStream($value['stream_id']);
					}
					if(!empty($value['substream_id'])) {
						$this->data['substream'] = $this->hierarchyRepository->findSubstream($value['substream_id']);
					}
					if(!empty($value['specialization_id'])) {
						$this->data['specialization'] = $this->hierarchyRepository->findSpecialization($value['specialization_id']);
					}
					if(!empty($value['base_course_id'])) {
						$this->data['baseCourse'] = $this->baseCourseRepository->find($value['base_course_id']);
					}
					if(!empty($value['exam_id'])) {
						$examName = $this->examMainLib->getExamDetailsByIds($value['exam_id']);
						$this->data['exam'] = $examName[$value['exam_id']]['examName'];
					}
					if(!empty($value['education_type'])) {
						$this->data['educationType'] = $this->baseAttributeLibrary->getValueNameByValueId($value['education_type']);
					}
					if(!empty($value['delivery_method'])) {
						$this->data['deliveryMethod'] = $this->baseAttributeLibrary->getValueNameByValueId($value['delivery_method']);
					}
					if(!empty($value['credential'])) {
						$this->data['credential'] = $this->baseAttributeLibrary->getValueNameByValueId($value['credential']);
					}
					if(!empty($value['city_id'])) {
						if($value['city_id'] == 1) {
							$this->data['city'] = 1;
						} else {
							$this->data['city'] = $this->locationRepository->findCity($value['city_id']);
						}
					}
					if(!empty($value['state_id']) && $value['state_id'] != -1) {
						if($value['state_id'] != 1) {
							$this->data['state'] = $this->locationRepository->findState($value['state_id']);
						}
					}
					$store[$value['id']]['301_url'] = $this->sanitizeUrl($this->replacePlaceHolders($rule['301_url']));
				}

				$this->categoryPageSEOModel->deleteProcessedDataWith301($store, $rule['id']);
			}
			$rulesProcessed[] = $rule['id'];
		}
		return $rulesProcessed;
	}

	public function getCategorySitemapURLs(){
		$result = $this->categoryPageSEOModel->getCategorySitemapURLs();
		if ($result == categorypageseomodel::$notFound) {
			return array(
				'data' => array(
					'found'  => 'no',
					'result' => $result
				)
			);
		} else {
			return array(
				'data' => array(
					'found'  => 'yes',
					'result' => $result
				)
			);
		}
	}

	/**
	 * Check if the database contains some data based on the input rules.
	 * So, if the form contains fields having value as none, NULL value is passed to the model method. Similarly, if the form field contains 'any', a corresponding code is sent to the database.
	 *
	 * @param array $inputCombinations An array containing the input rules (a.k.a. the combinations)
	 * @return bool|mixed False if the data does not exist in the database table <code>category_page_seo_rules</code> OR the data contained in the table if it exists
	 * @see \categorypageseomodel::getSEODetails which is called from the current context to look up the data in the database table
	 * @see \categorypageseomodel for a list of the valid codes
	 */
	public function getSEODetails($inputCombinations)
	{

		$filteredValues = function ($setOfValues) {

			foreach ($setOfValues as $oneKey => $oneValue) {

				if (array_key_exists($oneKey, CategoryPageSEOLib::$defaultAnyValues)) {
					$indexName = CategoryPageSEOLib::$defaultAnyValues[ $oneKey ]['indexName'];
					if ($oneValue != '' && $oneValue != 'none') {
						$indexValue = '';
						if ($oneValue == 'any') {
							$indexValue = CategoryPageSEOLib::$defaultAnyValues[ $oneKey ]['anyEquivalent'];
						} else {
							$indexValue = $oneValue;
						}
						$filteredValues[ $indexName ] = $indexValue;
					} else {
						$filteredValues[ $indexName ] = NULL;
					}
				}
			}

			return $filteredValues;
		};

		$testRules = $filteredValues($inputCombinations);
		if($this->validateRule($testRules)){
			$result = $this->categoryPageSEOModel->getSEODetails($testRules);
		} else {
			return array(
				'data' => array(
					'found'  => 'no',
					'result' => 'Invalid combination selection'
				)
			);
		}
		if ($result == categorypageseomodel::$notFound) { // Exact match not found

			$matchCriteria = $this->getMatchCriteria($inputCombinations);
			$nearestMatch = $this->categoryPageSEOModel->findMatchingSEODetails($matchCriteria); // Attempt a nearest match after changing the inputs

			return array(
				'data' => array(
					'found'  => 'match',
					'result' => $nearestMatch
				)
			);
		} else {
			return array(
				'data' => array(
					'found'  => 'yes',
					'result' => $result
				)
			);
		}
	}


	/**
	 * Get all of the rules saved in the database table <code>category_page_seo_rules</code>.
	 * <br/><br/><strong>P.S.</strong> While returning the results, the ids are changed with corresponding names.If the placeholder [course] appears with the placeholder [stream], it is assumed that the course is non-popular
	 *
	 * @return array Containing the URL rules
	 * @see \categorypageseomodel::getCategoryURLs which is called from the current context to fetch the data from the table <code>category_page_seo_rules</code>
	 * @see \HierarchyRepository::findStream to view how stream name is obtained
	 * @see \HierarchyRepository::findSubstream to view how substream name is obtained
	 * @see \BaseCourseRepository::find to view how course name is obtained
	 * @see \BaseAttributeLibrary::getValueNameByValueId to view how names corresponding to <code>education_type</code>, <code>delivery_method</code> and <code>credential</code> are obtained
	 */
	public function getCategoryURLs($combinations)
	{
		if ($combinations) {
			if(in_array('[stream]', $combinations) && in_array('[course]', $combinations)){

				$index = array_search('[course]', $combinations);
				if($index !== false){
					$combinations[$index] = '[non_popular_course]';
				}
			} else if (in_array('[course]', $combinations)){
				$index = array_search('[course]', $combinations);
				if($index !== false){
					$combinations[$index] = '[popular_course]';
				}
			}



			$filteredValues = function($combinations){
				$fieldNames = array();
				$combinationData = array();
				$ruleId = $combinations['ruleId'];

				foreach($combinations as $oneCombination){
					$oneCombination = preg_replace('/\[|\]/', '', $oneCombination); // remove the starting and the ending square braces
					$fieldNames[$oneCombination] = $oneCombination;
				}

				foreach(CategoryPageSEOLib::$defaultAnyValues as $oneDefaultValue){

					if(in_array($oneDefaultValue['indexName'], $fieldNames)){
						$combinationData[$oneDefaultValue['indexName']] = $oneDefaultValue['indexName'] . " IS NOT NULL";
					} else {
						$combinationData[$oneDefaultValue['indexName']] = $oneDefaultValue['indexName'] . " IS NULL";
					}
				}

				$combinationData['id'] = "id != $ruleId";
				return $combinationData;
			};

			$result = $this->categoryPageSEOModel->getCategoryURLs($filteredValues($combinations));
			if ($result == categorypageseomodel::$notFound) {
				return array(
					'data' => array(
						'found'  => 'no',
						'result' => $result
					)
				);
			} else {
				return array(
					'data' => array(
						'found'  => 'yes',
						'result' => $result
					)
				);
			}

		} else {
			$result = $this->categoryPageSEOModel->getCategoryURLs();
			if ($result == categorypageseomodel::$notFound) {
				return array(
					'data' => array(
						'found'  => 'no',
						'result' => $result
					)
				);
			} else {
				foreach ($result as $oneIndex => $oneResult) {
					if ($oneResult['stream'] != null && $oneResult['stream'] != 'any') {
						$streamInformation                 = $this->hierarchyRepository->findStream($oneResult['stream']);
						$result[ $oneIndex ]['streamName'] = $streamInformation->getUrlName();
					}

					if ($oneResult['substream'] != null && $oneResult['substream'] != 'any') {
						$subStreamInformation                 = $this->hierarchyRepository->findSubstream($oneResult['substream']);
						$result[ $oneIndex ]['substreamName'] = $subStreamInformation->getUrlName();
					}

					if ($oneResult['popular_course'] != null && $oneResult['popular_course'] != 'any') {
						$popularCourseInformation                 = $this->baseCourseRepository->find($oneResult['popular_course']);
						$result[ $oneIndex ]['popularCourseName'] = $popularCourseInformation->getUrlName();
					}

					$attributes = $this->baseAttributeLibrary;//getValueNameByValueId
					if ($oneResult['education_type'] != null && $oneResult['education_type'] != 'any') {
						$educationTypeInformation                 = $attributes->getValueNameByValueId($oneResult['education_type']);
						$result[ $oneIndex ]['educationTypeName'] = $educationTypeInformation[ $oneResult['education_type'] ];
					}

					if ($oneResult['credential'] != null && $oneResult['credential'] != 'any') {
						$credentialInformation                 = $attributes->getValueNameByValueId($oneResult['credential']);
						$result[ $oneIndex ]['credentialName'] = $credentialInformation[ $oneResult['credential'] ];
					}

					if ($oneResult['delivery_method'] != null && $oneResult['delivery_method'] != 'any') {
						$deliveryMethodInformation                 = $attributes->getValueNameByValueId($oneResult['delivery_method']);
						$result[ $oneIndex ]['deliveryMethodName'] = $deliveryMethodInformation[ $oneResult['delivery_method'] ];
					}
				}

				return array(
					'data' => array(
						'found'  => 'yes',
						'result' => $result
					)
				);
			}
		}

	}

	/**
	 * Save the category SEO data in the data store.
	 *
	 * @param array   $categoryData The data for the category page to be created
	 * @param integer $loggedInUser The user id of the logged in user .
	 *
	 * @param string  $mode Delete or Write. Both cases perform the same action except for a difference in the status field. Delete:- <code>status = 'delete'</code> Write:- <code>status = 'live'</code>
	 *
	 * @return array The status of the operation (to be used by the API consumer)
	 * @see \categorypageseomodel::submitSEODetails
	 */
	public function submitSEODetails($categoryData, $loggedInUser, $mode='write'){

		$filteredValues = function($categoryData){

			$filteredValue = array();
			$combinationData = array();

			/**
			 * Loop through the default values and prepare either the combination to be searched AFTER having inserting the data or the filtered values to be used WHILE inserting the data
			 *
			 * @param array $data The data to be inserted in raw form
			 * @param array $defaultValues A mapping of the default values and the input form values
			 * @param string $type The type of operation to be done while looping. At present, this assumes any of the values (case-sensitive): <b>combination</b> or <b>filter</b> or <b>deduplicate</b>
			 * @param array $array A reference to some array in the parent function.
			 */
			$loop = function($data, $defaultValues, $type, & $array){
				foreach($data as $oneIndex => $oneValue){
					if (array_key_exists($oneIndex, $defaultValues)) {
						$indexName = $defaultValues[ $oneIndex ]['indexName'];
						if ($type == 'combination') {
							if ($oneValue != '' && $oneValue != 'none') {
								/**
								 * @see https://github.com/bcit-ci/CodeIgniter/issues/3194
								 */
								$array[ $indexName ] = $indexName . ' IS NOT NULL'; // Since CI DB Driver does not support IS NOT NULL queries without this way
							} else {
								$array[ $indexName ] = $indexName . ' IS NULL'; // To keep the matching logic uniform
							}
						} else if($type == 'filter') {
							if ($oneValue != '' && $oneValue != 'none') {
								if ($oneValue == 'any') {
									$indexValue = $defaultValues[ $oneIndex ]['anyEquivalent'];
								} else {
									$indexValue = $oneValue;
								}
								$array[ $indexName ] = $indexValue;
							}
						} else {
							if ($oneValue == '' || $oneValue == 'none') {
								$array[ $indexName ] = NULL;
							} else if($oneValue == 'any'){
								$array[ $indexName ] = $defaultValues[ $oneIndex ]['anyEquivalent'];
							} else {
								$array[ $indexName ] = $oneValue;
							}
						}
					}
				}
			};

			$defaultAnyValues = CategoryPageSEOLib::$defaultAnyValues + array(
					'url'         => array(
						'indexName'     => 'url',
						'anyEquivalent' => ''
					),
					'breadcrumb'  => array(
						'indexName'     => 'breadcrumb',
						'anyEquivalent' => ''
					),
					'title'       => array(
						'indexName'     => 'meta_title',
						'anyEquivalent' => ''
					),
					'description' => array(
						'indexName'     => 'meta_description',
						'anyEquivalent' => ''
					),
					'h1Desktop'   => array(
						'indexName'     => 'heading_desktop',
						'anyEquivalent' => ''
					),
					'h1Mobile'    => array(
						'indexName'     => 'heading_mobile',
						'anyEquivalent' => ''
					),
					'301_url'    => array(
						'indexName'     => '301_url',
						'anyEquivalent' => ''
					),
					'show_404'    => array(
						'indexName'     => 'show_404',
						'anyEquivalent' => ''
					),
				); // Add text box mappings as well
			$loop($categoryData, $defaultAnyValues, 'filter', $filteredValue); // find the filtered value

			$returnValue = new stdClass();
			if($categoryData['id']){
				$filteredValue['id'] = $categoryData['id'];
			}
			$returnValue->filteredValue = $filteredValue;
			return $returnValue;
		};

		$filtered = $filteredValues($categoryData);
		// _p($filtered);die;
		$testRules = (array) $filtered;
		if(!$this->validateRule($testRules['filteredValue'])){
			return array(
				'data' => array(
					'status'  => 'fail',
					'result' => 'Invalid combination selected'
				)
			);
		}
		$filtered->combinationData = $this->getMatchCriteria($categoryData,'parentAndSelfOrNotNullIfAny');
		// _p($filtered);die;
		$submitData = $this->categoryPageSEOModel->submitSEODetails($filtered, $loggedInUser, $mode);

	 	if($submitData !== false) {
	 		$combinations = $submitData['combinations'];
			foreach ($combinations as $oneIndex => $oneResult) {
				$combination = array(); // To store the combinations to be used in the display
				if ( $oneResult['stream'] != null ) {
					if($oneResult['stream'] == 'any'){
						$combination[] = 'Any Stream';
					} else {
						$streamInformation                 = $this->hierarchyRepository->findStream($oneResult['stream']);
						$combination[] = 'Stream : '. $streamInformation->getUrlName();
					}
				}


				if ( $oneResult['substream'] != null ) {
					if($oneResult['substream'] == 'any'){
						$combination[] = 'Any Sub-stream';
					} else {
						$subStreamInformation                 = $this->hierarchyRepository->findSubstream($oneResult['substream']);
						$combination[] = 'Sub-stream : '. $subStreamInformation->getUrlName();
					}
				}


				if ( $oneResult['popular_course'] != null ) {
					if($oneResult['popular_course'] == 'any'){
						$combination[] = 'Any Popular Course';
					} else {
						$popularCourseInformation                 = $this->baseCourseRepository->find($oneResult['popular_course']);
						$combination[] = 'Popular Course : '. $popularCourseInformation->getUrlName();
					}
				}


				$attributes = $this->baseAttributeLibrary;//getValueNameByValueId
				if ( $oneResult['education_type'] != null ) {
					if($oneResult['education_type'] == 'any'){
						$combination[] = 'Any Education Type';
					} else {
						$educationTypeInformation                 = $attributes->getValueNameByValueId($oneResult['education_type']);
						$combination[] = 'Education Type : '. $educationTypeInformation[ $oneResult['education_type'] ];
					}
				}


				if ( $oneResult['credential'] != null ) {
					if($oneResult['credential'] == 'any'){
						$combination[] = 'Any Credential';
					} else {
						$credentialInformation                 = $attributes->getValueNameByValueId($oneResult['credential']);
						$combination[] = 'Credential : '. $credentialInformation[ $oneResult['credential'] ];
					}
				}


				if ( $oneResult['delivery_method'] != null ) {
					if($oneResult['delivery_method'] == 'any'){
						$combination[] = 'Any Delivery Method';
					} else {
						$deliveryMethodInformation                 = $attributes->getValueNameByValueId($oneResult['delivery_method']);
						$combination[] = 'Delivery Method : '. $deliveryMethodInformation[ $oneResult['delivery_method'] ];
					}
				}

				if ( $oneResult['non_popular_course'] != null ) {
					if($oneResult['non_popular_course'] == 'any'){
						$combination[] = 'Any Base course';
					}
					else{
						$popularCourseInformation = $this->baseCourseRepository->find($oneResult['non_popular_course']);
						$combination[] = 'Base Course : '. $popularCourseInformation->getUrlName();
					}
				}

				if ( $oneResult['specialization'] != null ) {
					$combination[] = 'Any Specialization';
				}

				if ( $oneResult['location'] != null ) {
					$combination[] = 'Any Location';
				}

				if ( $oneResult['exam'] != null ) {
					$combination[] = 'Any Exam';
				}


				$combinations[$oneIndex]['combination'] = implode(", ", $combination);
				$combinations[$oneIndex]['new_priority'] = $combinations[$oneIndex]['priority'];
			}
			$result = array();
			$result['combinations'] = $combinations;
			$result['ruleId'] = $submitData['ruleId'];

			return array(
				'data' => array(
					'status'  => 'ok',
					'result' => $result,
				)
			);
		} else {
			return array(
				'data' => array(
					'status'  => 'fail',
					'result' => 'Internal Error'
				)
			);
		}
	}


	public function deleteSEODetails($categoryData, $loggedInUser){
		$result = $this->categoryPageSEOModel->deleteSEODetails($categoryData, $loggedInUser);
		if($result) {
			return array(
				'data' => array(
					'status'  => 'ok',
					'result' => $result
				)
			);
		} else {
			return array(
				'data' => array(
					'status'  => 'fail',
					'result' => 'Either there was no change in data OR there was an error. Hence the operation failed...'
				)
			);
		}
	}

	function validateRule($rule) {
		$validity = true;
		
		//Validation 1: Rule should be on either stream or popular course. Not both.
		// if(empty($rule['stream']) && empty($rule['popular_course'])) {
		// 	$validity = false;
		// }
		if(!empty($rule['stream']) && !empty($rule['popular_course'])) {
			$validity = false;
		}

		//Validation 2: Either popular course or non popular course can be in the rule. Not both.
		if(!empty($rule['popular_course']) && !empty($rule['non_popular_course'])) {
			$validity = false;
		}
		return $validity;
	}

	/**
	 * Update the entries in the database table <code>category_page_seo_rules</code> once the rearrangement has taken place.
	 *
	 * @param array $orderedURL Containing the id, url, priority
	 *
	 * @return array The status of the insertion operation.
	 *
	 * @see \categorypageseomodel::submitOrderedURLs for the database insertion operation
	 * @deprecated The rearrangement does not take place now
	 */
	public function submitOrderedURLs($orderedURL, $loggedInUser){
		// _p($orderedURL);die;

		$result = $this->categoryPageSEOModel->submitOrderedURLs($orderedURL, $loggedInUser);
		if($result) {
			return array(
				'data' => array(
					'status'  => 'ok',
					'result' => $result
				)
			);
		} else {
			return array(
				'data' => array(
					'status'  => 'fail',
					'result' => 'Either there was no change in data OR there was an error. Hence the operation failed'
				)
			);
		}

	}

	private function getMatchCriteria($inputCombinations,$type = 'parentAndSelf')
	{
		$modifiedInput = array();

		if($type == 'parentAndSelf'){
			foreach ($inputCombinations as $key => $value) {
				if (array_key_exists($key, CategoryPageSEOLib::$defaultAnyValues)) {
					$indexName = CategoryPageSEOLib::$defaultAnyValues[$key]['indexName'];
					if(!empty($value) && $value != 'none'){
						$anyEquivalent             = CategoryPageSEOLib::$defaultAnyValues[ $key ]['anyEquivalent'];
						$modifiedInput[$indexName] = "($indexName = '$value' OR $indexName = '$anyEquivalent')";
					}
					else{
						$modifiedInput[ $indexName ] = "$indexName IS NULL";
					}
				}
			}
		}
		else if($type == 'parentAndSelfOrNotNullIfAny'){
			foreach ($inputCombinations as $key => $value) {
				if (array_key_exists($key, CategoryPageSEOLib::$defaultAnyValues)) {
					$indexName = CategoryPageSEOLib::$defaultAnyValues[$key]['indexName'];
					if(!empty($value) && $value != 'none'){
						if($value == 'any'){
							$modifiedInput[ $indexName ] = "$indexName IS NOT NULL";
						}
						else{
							$anyEquivalent             = CategoryPageSEOLib::$defaultAnyValues[ $key ]['anyEquivalent'];
							$modifiedInput[$indexName] = "($indexName = '$value' OR $indexName = '$anyEquivalent')";
						}
					}
					else{
						$modifiedInput[ $indexName ] = "$indexName IS NULL";
					}
				}
			}
		}

		// foreach ($inputCombinations as $oneKey => $oneInput) {
		// 	if (array_key_exists($oneKey, CategoryPageSEOLib::$defaultAnyValues)) {
		// 		$indexName = CategoryPageSEOLib::$defaultAnyValues[ $oneKey ]['indexName'];
		// 		if ($oneInput != NULL && $oneInput != 'none' && $oneInput != 'any') {
		// 			$anyEquivalent               = CategoryPageSEOLib::$defaultAnyValues[ $oneKey ]['anyEquivalent'];
		// 			$modifiedInput[ $indexName ] = "($indexName = $oneInput OR $indexName = '$anyEquivalent')";
		// 		} else {
		// 			$twoValuedElements = array(
		// 				'exam',
		// 				'location',
		// 				'specialization',
		// 			);

		// 			if (in_array($indexName, $twoValuedElements) && $oneInput != 'none') {
		// 				$anyEquivalent               = CategoryPageSEOLib::$defaultAnyValues[ $oneKey ]['anyEquivalent'];
		// 				$modifiedInput[ $indexName ] = "$indexName = $anyEquivalent";
		// 			} else {
		// 				$multiValuedAnyElements = array(
		// 					'delivery_method',
		// 					'credential',
		// 					'non_popular_course',
		// 				);

		// 				if(in_array($indexName, $multiValuedAnyElements) && $oneInput != ''){
		// 					$modifiedInput[ $indexName ] = "$indexName = '$anyEquivalent'";
		// 				} else {
		// 					$modifiedInput[ $indexName ] = "$indexName IS NULL";
		// 				}
		// 			}
		// 		}
		// 	}
		// }

		return $modifiedInput;
	}

	function categoryPageCountCronData(){

		echo " **********Fetching hierarchy **********\n";
		error_log(" **********Fetching hierarchy **********");
		$hierarcyData = $this->categorypageseomodel->fetchHierachiesData();
		$processedData = $this->_processHierarchyData($hierarcyData);
		unset($hierarcyData);

		$mainData['streamData'] = $processedData['streamData'];
		$mainData['substreamData'] = $processedData['substreamData'];
		$mainData['specData'] = $processedData['specData'];
		$mainData['baseCourseData'] = $processedData['baseCourseData'];
		$mainData['credentailData'] = $processedData['credentailData'];
		unset($processedData);

		echo " **********Fetching Basic **********\n";
		error_log(" **********Fetching Basic **********");
		$basicData = $this->categorypageseomodel->fetchBasicData();
		$processedData = $this->_processBasicData($basicData);
		unset($basicData);

		$mainData['educationTypeData'] = $processedData['educationType'];
		$mainData['deliveryMethodData'] = $processedData['deliveryMethod'];
		$mainData['parentMappingData'] = $processedData['parentMapping'];
		unset($processedData);

		echo " **********Fetching Exams **********\n";
		error_log(" **********Fetching Exams **********");
		$examsData = $this->categorypageseomodel->fetchExamsData();
		$processedData = $this->_processExamsData($examsData);
		unset($examsData);
		$mainData['examsData'] = $processedData['examsData'];
		unset($processedData);

		echo " **********Fetching Locations **********\n";
		error_log(" **********Fetching Locations **********");
		$locationsData = $this->categorypageseomodel->fetchLocationsData();
		// _P($locationsData);
		// die;
		$processedData = $this->_processLocationData($locationsData);
		unset($locationsData);

		$mainData['locationsData'] = $processedData['locationsData'];
		unset($processedData);
		// _p($mainData['locationsData']);die;

		echo "\n===== ITERATING ==== 0 \n";
		error_log(" **********Iteraating 0 **********");
		$batchSize = 5000;
		$dataToProcess = $this->categorypageseomodel->findCategoryPageData(0,$batchSize);
		
		$count = 1;
		$start = $batchSize;

		while (!empty($dataToProcess)) {
			$this->_processDataForCategoryPageCount($dataToProcess, $mainData);	
			$dataToProcess = $this->categorypageseomodel->findCategoryPageData($start,$batchSize);
			error_log("===== ITERATING ==== ".$count);
			echo "\n===== ITERATING ==== ".$count."\n";
			$count++;
			$start += $batchSize;
		}
		// generate interlinking cache
		//$interlinkingLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageInterLinking');
		//$interlinkingLib->generateInterlinkingCacheForCTPGs();
	}


	function categoryPageCountCronDataForId($catPageId){

		echo " **********Fetching hierarchy **********\n";
		error_log(" **********Fetching hierarchy **********");
		$hierarcyData = $this->categorypageseomodel->fetchHierachiesData();
		$processedData = $this->_processHierarchyData($hierarcyData);
		unset($hierarcyData);

		$mainData['streamData'] = $processedData['streamData'];
		$mainData['substreamData'] = $processedData['substreamData'];
		$mainData['specData'] = $processedData['specData'];
		$mainData['baseCourseData'] = $processedData['baseCourseData'];
		$mainData['credentailData'] = $processedData['credentailData'];
		unset($processedData);

		echo " **********Fetching Basic **********\n";
		error_log(" **********Fetching Basic **********");
		$basicData = $this->categorypageseomodel->fetchBasicData();
		$processedData = $this->_processBasicData($basicData);
		unset($basicData);

		$mainData['educationTypeData'] = $processedData['educationType'];
		$mainData['deliveryMethodData'] = $processedData['deliveryMethod'];
		$mainData['parentMappingData'] = $processedData['parentMapping'];
		unset($processedData);

		echo " **********Fetching Exams **********\n";
		error_log(" **********Fetching Exams **********");
		$examsData = $this->categorypageseomodel->fetchExamsData();
		$processedData = $this->_processExamsData($examsData);
		unset($examsData);
		$mainData['examsData'] = $processedData['examsData'];
		unset($processedData);

		echo " **********Fetching Locations **********\n";
		error_log(" **********Fetching Locations **********");
		$locationsData = $this->categorypageseomodel->fetchLocationsData();
		// _P($locationsData);
		// die;
		$processedData = $this->_processLocationData($locationsData);
		unset($locationsData);

		$mainData['locationsData'] = $processedData['locationsData'];
		unset($processedData);
		
		$batchSize = 5000;
		$dataToProcess = $this->categorypageseomodel->findCategoryPageDataForId($catPageId);
		
		$this->_processDataForCategoryPageCount($dataToProcess, $mainData);	
		
	}

	private function _processDataForCategoryPageCount($dataToProcess, $mainData){

		$finalResult = array();
		$innCount = 1;
		$startTime = microtime(true);
		foreach ($dataToProcess as $key => $value) {
			if($innCount % 500 == 0){
				$endTime = microtime(true);
				$diff = $endTime - $startTime;
				error_log("== INNER COUNT === ".$innCount."=====".$diff);
				$startTime = microtime(true);
			}
			$innCount++;
			$streamId = $value['stream_id'];
			$substreamId = $value['substream_id'];
			$specializationId = $value['specialization_id'];
			$baseCourse = $value['base_course_id'];
			$educationType = $value['education_type'];
			$deliveryMethod = $value['delivery_method'];
			$credential = $value['credential'];
			$examId = $value['exam_id'];
			$actualCityId = $cityId = $value['city_id'];
			$actualStateId = $stateId = $value['state_id'];
			$matchA = array();


			if(!empty($examId)){
				$matchExams = $mainData['examsData'][$examId];
			}
			if(!empty($streamId)){
				$matchStreams = $mainData['streamData'][$streamId];
			}
			if(!empty($substreamId)){
				$matchsubStreams = $mainData['substreamData'][$substreamId];
			}
			if(!empty($specializationId)){
				$matchSpecialization = $mainData['specData'][$specializationId];
			}
			if(!empty($baseCourse)){
				$matchBaseCourse = $mainData['baseCourseData'][$baseCourse];
			}
			if(!empty($educationType)){
				$matchEducationType = $mainData['educationTypeData'][$educationType];
			}
			if(!empty($deliveryMethod)){
				$matchDeliveryMethod = $mainData['deliveryMethodData'][$deliveryMethod];
			}
			if(!empty($credential)){
				$matchCredential = $mainData['credentailData'][$credential];
			}
			// $end = microtime(true);
			// $diff = ($end - $start);
			// error_log("=== DIFF1 ==".$diff);
			if(!empty($stateId) && $stateId != 1){
				// Virtual City Case
				if($stateId == -1){
					// error_log("=== VIRTAL CITY CASE===");
					$matchedStateData = array();
					if(empty($this->virtualCityToCityMapping[$cityId])){
						$mainCityFromVirtualCity = $this->locationRepository->getCitiesByVirtualCity($cityId);
						$this->virtualCityToCityMapping[$cityId] = $mainCityFromVirtualCity;
					}else{
						$mainCityFromVirtualCity = $this->virtualCityToCityMapping[$cityId];
					}
					
					foreach ($mainCityFromVirtualCity as $cityObject) {
						$cityId = $cityObject->getId();
						$stateId = $cityObject->getStateId();					
						if(!empty($stateId) && !empty($cityId)){
							// echo $stateId."\n".$cityId;
							$tempData = $mainData['locationsData'][$stateId][$cityId];
							$tempData = array_unique($tempData);
							// _p($tempData);die;
							 $matchedStateData = array_merge($matchedStateData, $tempData);
							//$matchedStateData = $this->imitateMerge($matchedStateData, $tempData);
							
							// $a = 10;
						}
						
					}
					
					$matchedStateData = array_values(array_unique($matchedStateData));
				}else{ // Non Virtual City Case
					if($cityId == 1){
						$tempData = array();
						$tempData = $mainData['locationsData'][$stateId];
						$matchedStateData = array();
						foreach ($tempData as $cityId => $courseIdsList) {
							$matchedStateData = array_merge($matchedStateData, $courseIdsList);
							//$matchedStateData = $this->imitateMerge($matchedStateData, $tempData);
						}

						$matchedStateData = array_values(array_unique($matchedStateData));
					}else{
						$matchedStateData = $mainData['locationsData'][$stateId][$cityId];
					}
				}
			} elseif(empty($streamId) && empty($baseCourse) && $stateId == 1) { //All India location CTP
				$matchedStateData = array();
				foreach ($mainData['locationsData'] as $stateId => $cityData) {
					foreach ($cityData as $cityId => $courseIdsList) {
						$matchedStateData = array_merge($matchedStateData, $courseIdsList);
					}
				}
				$matchedStateData = array_values(array_unique($matchedStateData));
			}
			// $end1 = microtime(true);
			// $diff = ($end1 - $end);
			// error_log("=== DIFF2 ==".$diff);
			
			$tempArray = array();

			if (!empty($streamId)) $tempArray[] = $matchStreams;	
			if (!empty($substreamId)) $tempArray[] = $matchsubStreams;
			if (!empty($specializationId)) $tempArray[] = $matchSpecialization;
			if (!empty($baseCourse)) $tempArray[] = $matchBaseCourse;
			if (!empty($educationType)) $tempArray[] = $matchEducationType;
			if (!empty($deliveryMethod)) $tempArray[] = $matchDeliveryMethod;
			if (!empty($credential)) $tempArray[] = $matchCredential;
			if (!empty($examId)) $tempArray[] = $matchExams;
			if ($actualCityId != 1 || $actualStateId != 1) $tempArray[] = $matchedStateData;
			if(empty($streamId) && empty($baseCourse) && $actualStateId == 1) { //All India location CTP
				$tempArray[] = $matchedStateData;
			}
			
			if(count($tempArray) == 0){
				$intersect = array();
			}else if(count($tempArray) == 1){
				$intersect = $tempArray[0];
			}else{
				$intersect = call_user_func_array('array_intersect', $tempArray);	
			}
			$finalIntersect = array();
			$finalCount = 0;
			foreach ($intersect as $courseId) {
				$x = $mainData['parentMappingData'][$courseId];
				$finalIntersect[$x] = 1;
			}
			// $end2 = microtime(true);
			// $diff = ($end2 - $end1);
			// error_log("=== DIFF3 ==".$diff);
			// error_log("=====444");
			$finalCount = count($finalIntersect);
			// _p("=== coumt for =  ".$value['id']."== is ".count($finalIntersect));
			//$metaDescription = str_replace("[n]", $finalCount, $value['meta_description']);
			$finalResult[$value['id']] = array('count' => $finalCount/*, 'meta_description' => $metaDescription*/);
		}

		//invalidate category page interlinking widget caching 
		//$this->inValidateCategoryPageInterLinkingCache(array_keys($finalResult));

		echo "\n===== RUNING UPDATE ========\n";
		error_log ("===== RUNING UPDATE START========");
		$this->categorypageseomodel->updateCountForCategoryPage($finalResult);
		error_log ("===== RUNING UPDATE END========");
			// 		$end2 = microtime(true);
			// $diff = ($end2 - $end1);
			// error_log("=== DIFF1 ==".$diff);
		// die;
	}

	

	function _processHierarchyData($hierarcyData){
		$streamSubStreamSpecData = array();
		
		foreach ($hierarcyData as $key => $value) {
			$streamData[$value['stream_id']][] = $value['course_id'];
			$substreamData[$value['substream_id']][] = $value['course_id'];
			$specData[$value['specialization_id']][] = $value['course_id'];
			$baseCourseData[$value['base_course']][] = $value['course_id'];
			$credentailData[$value['credential']][] = $value['course_id'];
		}
		$finalResult['streamData'] = $streamData;
		$finalResult['substreamData'] = $substreamData;
		$finalResult['specData'] = $specData;
		$finalResult['baseCourseData'] = $baseCourseData;
		$finalResult['credentailData'] = $credentailData;
		return $finalResult;
	}

	function _processBasicData($basicData){
		
		// _p($basicData);die;
		foreach ($basicData as $key => $value) {
			if(empty($value['delivery_method'])){
				$value['delivery_method'] = 33;
			}
			$educationType[$value['education_type']][] = $value['course_id'];
			$deliveryMethod[$value['delivery_method']][] = $value['course_id'];
			$parentMapping[$value['course_id']] = $value['primary_id'];
		}

		$finalResult['educationType'] = $educationType;
		$finalResult['deliveryMethod'] = $deliveryMethod;
		$finalResult['parentMapping'] = $parentMapping;
		return $finalResult;
	}

	function _processExamsData($examsData){

		$processedExamsData = array();
		foreach ($examsData as $key => $value) {
			if(empty($value['exam_id'])) continue;
			$processedExamsData[$value['exam_id']][] = $value['course_id'];
		}
		$finalResult['examsData'] = $processedExamsData;
		return $finalResult;
	}

	function _processLocationData($locationsData){
		$processedLocationsData = array();
		foreach ($locationsData as $key => $value) {
			$processedLocationsData[$value['state_id']][$value['city_id']][] = $value['course_id'];
		}
		$finalResult['locationsData'] = $processedLocationsData;
		return $finalResult;
	}

	function imitateMerge($array1, $array2) {
	    foreach($array2 as $i) {
	        $array1[] = $i;
	    }
	    return $array1;
	}

	function categoryPageSeoURLHash(){
		
		$batchSize = 5000;
		$dataToProcess = $this->categorypageseomodel->findCategoryPageData(0,$batchSize);
		$count = 1;
		
		$start = $batchSize;
		while (!empty($dataToProcess)) {
			$this->_processDataForCategoryPageUrlHash($dataToProcess);	
			$dataToProcess = $this->categorypageseomodel->findCategoryPageData($start,$batchSize);
			error_log("===== ITERATING ==== ".$count);
			echo "\n===== ITERATING ==== ".$count."\n";
			$count++;
			$start += $batchSize;
		}
	}

	function _processDataForCategoryPageUrlHash($dataToProcess){
		$finalResult = array();
		foreach ($dataToProcess as $key => $value) {
			$hash = murmurhash3_int($value['url']);
			
			$finalResult[$value['id']] = array('hash_url' => $hash);	
		}
		echo "\n===== RUNING UPDATE ========\n";
		error_log ("===== RUNING UPDATE START========");
		$this->categorypageseomodel->updateHashForCategoryPage($finalResult);
		error_log ("===== RUNING UPDATE END========");
	}

	function inValidateCategoryPageInterLinkingCache($categoryPageIds){
		if(empty($categoryPageIds))
			return;

		error_log("===========REMOVE CAT PAGE INTERLINKING CACHE=========");
		echo "\n===== REMOVE CAT PAGE INTERLINKING CACHE ========\n";
		$nationalCategoryListCache     = $this->CI->load->library('nationalCategoryList/cache/NationalCategoryListCache');  
		$nationalCategoryListCache->removeCategoryPageRelatedLinksCache($categoryPageIds);
	}

	function inValidateCatPageInterLinkingCacheByRuleId($ruleIds){
		error_log("===========REMOVE CAT PAGE INTERLINKING CACHE By RULE CRON=========");
		$categoryPageUniqueIdsData     = $this->categorypageseomodel->findCategoryPageDataForRuleId($ruleIds);
        $catPageUniqueIds = array();
        foreach($categoryPageUniqueIdsData as $value){
                $catPageUniqueIds[] = $value['id'];
        }
        $this->inValidateCategoryPageInterLinkingCache($catPageUniqueIds);
	}
}
