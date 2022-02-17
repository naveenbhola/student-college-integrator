<?php
include_once('MailerWidgetAbstract.php');
class DetailedRecommendationWidget extends MailerWidgetAbstract
{
	private $collaborativeFilter;
	
	function __construct(MailerModel $mailerModel,profile_based_collaborative_filter_lib $collaborativeFilter)
	{
		parent::__construct($mailerModel);
		$this->collaborativeFilter = $collaborativeFilter;
	}
	
	/**
	 * API for getting recommendations data
	 */
	public function getData($userIds, $params)
	{
		if(is_array($userIds) && count($userIds)) {
			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$courseRepository = $listingBuilder->getCourseRepository();
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$universityRepository = $listingBuilder->getUniversityRepository();
			
			$this->CI->load->model('recommendation_abroad/abroad_recommendation_model');
			$recommendationModel = new Abroad_Recommendation_Model;
			
			$this->CI->load->model('CA/cadiscussionmodel');
			$cadiscussionmodel = new cadiscussionmodel;
			
			$this->CI->load->library('recommendation_abroad/abroad_recommendation_front_lib');
			$recommendationFrontLib = new Abroad_Recommendation_Front_Lib();
			
			$this->CI->load->library('listing/NaukriData');
			$naukriData = new NaukriData();
			
			$this->CI->load->library('alumni/AlumniReviewsLibrary');
			$alumniReviewsLib = new AlumniReviewsLibrary();
			
			$this->CI->load->library('listing/AbroadListingCommonLib');
			$abroadListingCommonLib = new AbroadListingCommonLib();
			
			$this->CI->config->load('studyAbroadListingConfig');
			$currencySymbolMapping = $this->CI->config->item("ENT_SA_CURRENCY_SYMBOL_MAPPING");
			
			$lastInstituteCourse = $this->mailerModel->getUsersLastInstituteAndCourse(implode(',',$userIds), $params);
			$educationInterest = $this->mailerModel->getUsersDesiredEducation(implode(',',$userIds), $params);

			$recommendationsArray = array();
			
			/**
			 * Create request data for recommendations
			 */
			$user_request_data = array();
			foreach ($userIds as $user) {
				$user_request_data[] = array($user,'string');
			}			
			
			/**
			 * Generate recommendations for users
			 */
			$recommendation_data = $recommendationFrontLib->getRecommendations($user_request_data,'no',1);
			$recommendation_data = json_decode(gzuncompress(base64_decode($recommendation_data)),true);
			$recommendations = $recommendation_data['recommendations'];
			
			/**
			* Store recommendations for users
			*/
			$recommendation_ids = $recommendationModel->saveRecommendations($recommendations);
			
			/**
			 * Create data for recommendation widgets
			 */
			$coursesInRecommendation = array();
			$institutesInRecommendation = array();
			$universitiesInRecommendation = array();
			foreach($recommendations as $userId => $user_recommendation_data) {
				$recommendations_by_category = $user_recommendation_data['recommendations'];
				foreach($recommendations_by_category as $category => $recommendation_details) {
					$category_recommendations_by_algo = $recommendation_details['recommendations'];
					foreach($category_recommendations_by_algo as $algo => $recommendation_details_by_algo)
					{
						if(count($recommendation_details_by_algo)) {
							$course_id = $recommendation_details_by_algo[0]['course_id'];
							if($course_id > 0) {
								$coursesInRecommendation[$course_id] = TRUE;
							}
						}
					}
				}
			}
			
			if(count($coursesInRecommendation)) {
				$course_ids = array_keys($coursesInRecommendation);
				
				//Get course, institute and university objects
				$courseObjects = $courseRepository->findMultiple($course_ids);
				foreach($courseObjects as $course_id => $courseObj) {
					if($courseObj instanceof Course) {
						$institutesInRecommendation[$courseObj->getInstId()] = TRUE;
					}
					else if($courseObj instanceof AbroadCourse) {
						$universitiesInRecommendation[$courseObj->getUniversityId()] = TRUE;
					}
				}
				
				$institute_ids = array_keys($institutesInRecommendation);
				$university_ids = array_keys($universitiesInRecommendation);
				
				if(count($university_ids)) {
					$universityObjects = $universityRepository->findMultiple($university_ids);
				}
				
				if(count($institute_ids)) {
					$instituteObjects = $instituteRepository->findMultiple($institute_ids);
					
					//get alumni review data
					$alumniReviewData = $alumniReviewsLib->getAlumnusRatingsForInstitutes($institute_ids);
					
					//get naukri salary data
					$naukriSalaryData = $naukriData->getNaukriSalaryData($institute_ids);
					
					//Get data for campus ambassador widget
					$campusAmbassadorData = $cadiscussionmodel->getCampusRepInfoForCourse($course_ids, 'course', null, null, true);
				}
			}
			
			$userHasRecommendation = array_keys($recommendations);
			foreach($userIds as $user_id ) {
				$mailer_html = '';
				if(in_array($user_id,$userHasRecommendation)) {
					$recommendationWidgets = array(
									'recommendationBasicInfo' => array(),
									'examsRequired' 	  => array(),
									'courseFees' 		  => array(),
									'salaryData' 		  => array(),
									'naukriSalaryData' 	  => array(),
									'recruiters' 		  => array(),
									'campusAmbassador' 	  => array(),
									'moreCourseInfo'	  => array()
								       );
					
					$recommendationWidgetPositions = array(
										'examsRequired'    => array('row' => 1, 'column' => 1),
										'courseFees'       => array('row' => 1, 'column' => 2),
										'salaryData'       => array('row' => 2, 'column' => 1),
										'naukriSalaryData' => array('row' => 2, 'column' => 1),
										'recruiters'       => array('row' => 2, 'column' => 2)
										);
					
					$algo_used = null;
					$course_id = null;
					$institute_id = null;
					
					$user_recommendation_data = $recommendations[$user_id];
					$recommendations_by_category = $user_recommendation_data['recommendations'];
					
					foreach($recommendations_by_category as $category => $recommendation_details) {
						$category_recommendations_by_algo = $recommendation_details['recommendations'];
						foreach($category_recommendations_by_algo as $algo => $recommendation_details_by_algo)
						{
							if(count($recommendation_details_by_algo)) {
								$algo_used = $algo;
								$course_id = $recommendation_details_by_algo[0]['course_id'];
								$institute_id = $recommendation_details_by_algo[0]['institute_id'];
								
								if($course_id > 0 && $institute_id > 0) {
									break;
								}
							}
						}
					}
					
					$isAbroadRecommendation = false;
					
					if($course_id && $institute_id) {
						$course = $courseObjects[$course_id];
						
						if($course instanceof AbroadCourse) {
							$isAbroadRecommendation = true;
							
							unset($recommendationWidgets['naukriSalaryData']);
							unset($recommendationWidgetPositions['naukriSalaryData']);
							
							unset($recommendationWidgets['campusAmbassador']);
							
							$universityId = $course->getUniversityId();
							$institute = $universityObjects[$universityId];
						}
						else if($course instanceof Course) {
							$institute = $instituteObjects[$institute_id];
							
							unset($recommendationWidgets['moreCourseInfo']);
						}
						
						if(is_object($institute) && is_object($course)) {
							$institute_name = $institute->getName();
							$institute_location = $institute->getMainLocation();
							$institute_country = $institute_location->getCountry();
							$institute_city = $institute_location->getCity();
							
							if($isAbroadRecommendation) {
								$recommendationWidgets['recommendationBasicInfo']['typeOfInstitute'] = ucwords($institute->getTypeOfInstitute().' university');
								$recommendationWidgets['recommendationBasicInfo']['departmentName'] = $course->getInstituteName();
								$recommendationWidgets['recommendationBasicInfo']['courseDescription'] = str_replace('&nbsp;',' ',truncate($course->getCourseDescription(), 300, '...', TRUE));
								$recommendationWidgets['recommendationBasicInfo']['hasDummyDepartment'] = $institute->getTypeOfInstitute2() == 'college' ? true : false;
							}
							else {
								$courseApprovals = $course->getApprovals();
								if(count($courseApprovals)) {
									$recommendationWidgets['recommendationBasicInfo']['courseApprovals'] = $courseApprovals[0] == 'ugc' ? 'Recognised by: '.strtoupper($courseApprovals[0]) : 'Approved by: '.strtoupper($courseApprovals[0]);
								}
								
								$alumniReviews = $alumniReviewData[$institute_id];
								$recommendationWidgets['recommendationBasicInfo']['alumniRating'] = $alumniReviews['averageRating'];
								$recommendationWidgets['recommendationBasicInfo']['alumniReviews'] = $alumniReviews['totalAlumniReviewers'];
							}
							
							$recommendationWidgets['recommendationBasicInfo']['instituteID'] = $institute_id;
							$recommendationWidgets['recommendationBasicInfo']['photoCount'] = $institute->getPhotoCount();
							$recommendationWidgets['recommendationBasicInfo']['videoCount'] = $institute->getVideoCount();
							$recommendationWidgets['recommendationBasicInfo']['instituteLogoURL'] = is_object($institute->getMainHeaderImage()) ? $institute->getMainHeaderImage()->getURL() : '';
							$recommendationWidgets['recommendationBasicInfo']['instituteName'] = $institute_name;
							$recommendationWidgets['recommendationBasicInfo']['instituteCountryName'] = $institute_country->getName();
							$recommendationWidgets['recommendationBasicInfo']['instituteCityName'] = $institute_city->getName();
							$recommendationWidgets['recommendationBasicInfo']['establishYear'] = $institute->getEstablishedYear();
							$recommendationWidgets['recommendationBasicInfo']['courseId'] = $course->getId();
							$recommendationWidgets['recommendationBasicInfo']['courseName'] = $course->getName();
							$recommendationWidgets['recommendationBasicInfo']['courseDuration'] = $course->getDuration()->getDisplayValue();
							
							
							/**
							* Get data for exam widget
							*/
							$exams = $course->getEligibilityExams();
							if(count($exams)) {
								foreach($exams as $exam) {
									if($exam->getId() > 0) {
										if($exam instanceof Exam) {
											$name = $exam->getAcronym();
											$marks = $exam->getMarks();
											$marksType = $exam->getMarksType();
										}
										else if($exam instanceof AbroadExam) {
											$name = $exam->getName();
											$marks = $exam->getCutOff();
											$maxScore = $exam->getMaxScore();
										}
										
										$examInfo = array();
										if($marks > 0 || (strlen(trim($marks)) == 1 && ctype_alpha($marks))) {
											$examInfo['marks'] = $marks;
											if(strlen($marksType)) {
												$examInfo['marksType'] = $marksType;
											}
											if($maxScore > 0) {
												$examInfo['maxScore'] = $maxScore;
											}
										}
										
										$recommendationWidgets['examsRequired'][$name] = $examInfo;
									}
								}
							}
							else {
								unset($recommendationWidgets['examsRequired']);
								unset($recommendationWidgetPositions['examsRequired']);
							}
							
							
							/**
							* Get data for fees widget
							*/
							$fees = $course->getFees()->getValue();
							if($fees > 0) {
								$feesCurrency = $course->getFees()->getCurrency();
								
								if($feesCurrency > 1) {
									$recommendationWidgets['courseFees']['fees'] = (!empty($currencySymbolMapping[$feesCurrency]) ? $currencySymbolMapping[$feesCurrency] : $course->getFees()->getCurrencyEntity()->getCode()).' '.$abroadListingCommonLib->formatMoneyAmount($fees, $feesCurrency, 1);
									$fees = $abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
								}
								
								$fees = $abroadListingCommonLib->formatMoneyAmount($fees, 1, 1);
								$recommendationWidgets['courseFees']['feesInRupee'] = $currencySymbolMapping[1].' '.$fees;
							}
							else {
								unset($recommendationWidgets['courseFees']);
								unset($recommendationWidgetPositions['courseFees']);
							}
							
							
							/**
							* Get data for salary widget
							*/
							if($isAbroadRecommendation) {
								$averageSalary = $course->getJobProfile()->getAverageSalary();
								if($averageSalary > 0) {
									$currency = $course->getJobProfile()->getAverageSalaryCurrencyId();
									
									if($currency > 1) {
										$recommendationWidgets['salaryData']['averageSalary'] = (!empty($currencySymbolMapping[$currency]) ? $currencySymbolMapping[$currency] : $course->getJobProfile()->getCurrencyEntity()->getCode()).' '.$abroadListingCommonLib->formatMoneyAmount($averageSalary, $currency, 1);
										$averageSalary = $abroadListingCommonLib->convertCurrency($currency, 1, $averageSalary);
									}
									
									$averageSalary = $abroadListingCommonLib->formatMoneyAmount($averageSalary, 1, 1);
									$recommendationWidgets['salaryData']['averageSalaryInRupees'] = $currencySymbolMapping[1].' '.$averageSalary;
								}
								else {
									unset($recommendationWidgets['salaryData']);
									unset($recommendationWidgetPositions['salaryData']);
								}
							}
							else if(!$isAbroadRecommendation) {
								$salaryData = $naukriSalaryData[$institute_id];
								if(count($salaryData)) {
									unset($recommendationWidgets['salaryData']);
									unset($recommendationWidgetPositions['salaryData']);
									
									foreach($salaryData as $bucket => $avgSalary) {
										$recommendationWidgets['naukriSalaryData'][$bucket] = $avgSalary;
									}
								}
								else {
									unset($recommendationWidgets['naukriSalaryData']);
									unset($recommendationWidgetPositions['naukriSalaryData']);
									
									if($course->isSalaryTypeExist()) {
										$salary_details = $course->getSalary();
										
										foreach($salary_details as $type => $salary) {
											if($salary > 0) {
												$recommendationWidgets['salaryData'][$type] = round($salary/100000, 2);
											}
										}
									}
									else {
										unset($recommendationWidgets['salaryData']);
										unset($recommendationWidgetPositions['salaryData']);
									}
								}
							}
							
							
							/**
							* Get data for recruiting companies widget
							*/
							if($course->hasRecruitingCompanies()) {
								$recruiters = $course->getRecruitingCompanies();
								$numOfCompanies = count($recruiters) >= 2 ? 2 : count($recruiters);
								
								for($count = 0; $count < $numOfCompanies; $count++) {
									if(is_object($recruiters[$count])) {
										$recommendationWidgets['recruiters']['companyLogo'][] = $recruiters[$count]->getLogoURL();
									}
								}
								
								$recommendationWidgets['recruiters']['count'] = count($recruiters);
							}
							else {
								unset($recommendationWidgets['recruiters']);
								unset($recommendationWidgetPositions['recruiters']);
							}
							
							
							/**
							* Get data for campus ambassador widget
							*/
							if(!$isAbroadRecommendation) {
								$campusAmbassador = $campusAmbassadorData['caInfo'][$course_id][0];
								if($campusAmbassador['userId'] > 0) {
									$recommendationWidgets['campusAmbassador']['badge'] = $campusAmbassador['badge'] == 'CurrentStudent' ? 'Current Student' : $campusAmbassador['badge'];
									$recommendationWidgets['campusAmbassador']['displayName'] = $campusAmbassador['displayName'];
									$recommendationWidgets['campusAmbassador']['displayImageURL'] = $campusAmbassador['imageURL'];
									$recommendationWidgets['campusAmbassador']['instituteName'] = $institute_name;
								}
								else {
									unset($recommendationWidgets['campusAmbassador']);
								}
							}
							
							
							/**
							* Get data for more course info widget
							*/
							if($isAbroadRecommendation) {
								$facultyInfoLink = strlen(trim($course->getFacultyInfoLink())) ? $course->getFacultyInfoLink() : '';
								$alumniInfoLink = strlen(trim($course->getAlumniInfoLink())) ? $course->getAlumniInfoLink() : '';
								$courseFAQLink = strlen(trim($course->getCourseFaqLink())) ? $course->getCourseFaqLink() : '';
								$scholarshipInfoLink = strlen(trim($course->getScholarshipLinkCourseLevel())) ? $course->getScholarshipLinkCourseLevel() : (strlen(trim($course->getScholarshipLinkDeptLevel())) ? $course->getScholarshipLinkDeptLevel() : (strlen(trim($course->getScholarshipLinkUniversityLevel())) ? $course->getScholarshipLinkUniversityLevel() : '' ));
								
								if($facultyInfoLink == '' && $alumniInfoLink == '' && $courseFAQLink == '' && $scholarshipInfoLink == '') {
									unset($recommendationWidgets['moreCourseInfo']);
								}
								else {
									$moreInfoWidgetPositions = array(
										'facultyInfo'    => array('row' => 1, 'column' => 1),
										'alumniInfo'       => array('row' => 1, 'column' => 2),
										'courseFAQ'       => array('row' => 2, 'column' => 1),
										'scholarshipInfo' => array('row' => 2, 'column' => 1)
									);
									
									if(strlen($facultyInfoLink)) {
										$recommendationWidgets['moreCourseInfo']['facultyInfo'] = array('text' => 'Faculty Information', 'link' => $facultyInfoLink);
									}
									else {
										unset($moreInfoWidgetPositions['facultyInfo']);
									}
									
									if(strlen($alumniInfoLink)) {
										$recommendationWidgets['moreCourseInfo']['alumniInfo'] = array('text' => 'Alumni Information', 'link' => $alumniInfoLink);
									}
									else {
										unset($moreInfoWidgetPositions['alumniInfo']);
									}
									
									if(strlen($courseFAQLink)) {
										$recommendationWidgets['moreCourseInfo']['courseFAQ'] = array('text' => 'Course FAQs', 'link' => $courseFAQLink);
									}
									else {
										unset($moreInfoWidgetPositions['courseFAQ']);
									}
									
									if(strlen($scholarshipInfoLink)) {
										$recommendationWidgets['moreCourseInfo']['scholarshipInfo'] = array('text' => 'Scholarship Info', 'link' => $scholarshipInfoLink);
									}
									else {
										unset($moreInfoWidgetPositions['scholarshipInfo']);
									}
									
									$widgetRow = 1;
									$widgetColumn = 1;
									foreach($moreInfoWidgetPositions as $widgetName => $widgetPosition) {
										$moreInfoWidgetPositions[$widgetName]['row'] = $widgetRow;
										$moreInfoWidgetPositions[$widgetName]['column'] = $widgetColumn++;
										
										if($widgetColumn > 2) {
											$widgetRow++;
											$widgetColumn = 1;
										}
									}
									
									$recommendationWidgets['moreCourseInfo']['moreInfoWidgetPositions'] = $moreInfoWidgetPositions;
								}
							}
							
							
							/**
							* Update widget positions
							*/
							$widgetRow = 1;
							$widgetColumn = 1;
							foreach($recommendationWidgetPositions as $widgetName => $widgetPosition) {
								$recommendationWidgetPositions[$widgetName]['row'] = $widgetRow;
								$recommendationWidgetPositions[$widgetName]['column'] = $widgetColumn++;
								
								if($widgetColumn > 2) {
									$widgetRow++;
									$widgetColumn = 1;
								}
							}
							
							$data['user_id'] = $user_id;
							$data['recommendation_ids'] = $recommendation_ids[$user_id];
							$data['mailer_id'] = $mailer_id;
							$data['isAbroadRecommendation'] = $isAbroadRecommendation;
							$data['algoUsed'] = $algo_used;
							$data['recommendationWidgets'] = $recommendationWidgets;
							$data['recommendationWidgetPositions'] = $recommendationWidgetPositions;
							$data['courseDetails'] = $lastInstituteCourse[$user_id];
							$data['educationInterest'] = $educationInterest[$user_id];
							$data['listing_type_id'] = $recommendationWidgets['recommendationBasicInfo']['courseId'];
							$data['listing_type'] = 'course';
							$data['specializationId'] = $educationInterest[$user_id]['specializationid'];
							
							$mailer_html = $this->CI->load->view('MailerWidgets/DetailedRecommendationTemplate',$data,true);	

						}
						
						if(trim($mailer_html) != '' ) {
							$recommendationsArray[$user_id]['detailedRecommendation'] = $mailer_html;
							$recommendationsArray[$user_id]['recommendedInstituteTitle'] = $recommendationWidgets['recommendationBasicInfo']['instituteName'].' in '.($isAbroadRecommendation ? $recommendationWidgets['recommendationBasicInfo']['instituteCountryName'] : $recommendationWidgets['recommendationBasicInfo']['instituteCityName']);
						}
						else {
							$recommendationsArray[$user_id]['detailedRecommendation'] = '';
							$recommendationsArray[$user_id]['recommendedInstituteTitle'] = '';
						}
					}
					else {
						$recommendationsArray[$user_id]['detailedRecommendation'] = '';
						$recommendationsArray[$user_id]['recommendedInstituteTitle'] = '';
					}
				}
				else {
					$recommendationsArray[$user_id]['detailedRecommendation'] = '';
					$recommendationsArray[$user_id]['recommendedInstituteTitle'] = '';
				}
			}
			return $recommendationsArray;
		}
	}
	
	private function truncate($text, $length, $suffix = '', $isHTML = true) {
		$i = 0;
		$simpleTags=array('br'=>true,'hr'=>true,'input'=>true,'image'=>true,'link'=>true,'meta'=>true);
		$tags = array();
		if($isHTML){
		    preg_match_all('/<[^>]+>([^<]*)/', $text, $m, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
		    foreach($m as $o){
			if($o[0][1] - $i >= $length)
			    break;
			$t = substr(strtok($o[0][0], " \t\n\r\0\x0B>"), 1);
			// test if the tag is unpaired, then we mustn't save them
			if($t[0] != '/' && (!isset($simpleTags[$t])))
			    $tags[] = $t;
			elseif(end($tags) == substr($t, 1))
			    array_pop($tags);
			$i += $o[1][1] - $o[0][1];
		    }
		}
	    
		// output without closing tags
		$output = substr($text, 0, $length = min(strlen($text),  $length + $i));
		// closing tags
		$output2 = (count($tags = array_reverse($tags)) ? '</' . implode('></', $tags) . '>' : '');
	    
		// Find last space or HTML tag (solving problem with last space in HTML tag eg. <span class="new">)
		$pos = (int)end(end(preg_split('/<.*>| /', $output, -1, PREG_SPLIT_OFFSET_CAPTURE)));
		// Append closing tags to output
		$output.=$output2;
	    
		// Get everything until last space
		$one = substr($output, 0, $pos);
		// Get the rest
		$two = substr($output, $pos, (strlen($output) - $pos));
		// Extract all tags from the last bit
		preg_match_all('/<(.*?)>/s', $two, $tags);
		// Add suffix if needed
		if (strlen($text) > $length) { $one .= $suffix; }
		// Re-attach tags
		$output = $one . implode($tags[0]);
	    
		//added to remove  unnecessary closure
		$output = str_replace('</!-->','',$output); 
	    
		return $output;
	}

}
