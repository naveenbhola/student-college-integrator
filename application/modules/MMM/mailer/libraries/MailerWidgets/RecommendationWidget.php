<?php
include_once('MailerWidgetAbstract.php');
class RecommendationWidget extends MailerWidgetAbstract
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
		$lastInstituteCourse = $this->mailerModel->getUsersLastInstituteAndCourse(implode(',',$userIds), $params);
		$educationInterest = $this->mailerModel->getUsersDesiredEducation(implode(',',$userIds), $params);
		$studyAbroadEducationInterest = $this->mailerModel->getSAUsersCategoryAndPrefLocation(implode(',',$userIds), $params);
		
		$recommendationsArray = array();
		
		$this->CI->load->library('recommendation_front_lib');
		$obj = new Recommendation_Front_Lib();
		
		$this->CI->load->library('listing/AbroadListingCommonLib');
		$abroadListingCommonLib = new AbroadListingCommonLib();
		
		$this->CI->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$courseRepository = $listingBuilder->getCourseRepository();
		$universityRepository = $listingBuilder->getUniversityRepository();
		
		$this->CI->load->builder('CategoryBuilder','categoryList');
		$categoryBuilder = new CategoryBuilder;	
		$categoryRepository = $categoryBuilder->getCategoryRepository();
		
		if(is_array($userIds) && count($userIds))
		{
			$user_request_data = array();
			foreach ($userIds as $user)
			{
				$user_request_data[] = array($user,'string');
			}
			
			/**
			 * Prepare data for collaborative filtering recommendation algo
			 */
			$collaborativeFilterBasedDataSet = array();
			if(USE_PROFILE_BASED_COLLABORATIVE_FILTERING)
			{
				$collaborativeFilter = profile_based_collaborative_filter_lib::getUniqueInstance();
				$start = microtime_float(TRUE);
				$collaborativeFilterBasedDataSet = $collaborativeFilter->prepareUserBucketsForCollaborativeFiltering();
				$end = microtime_float(TRUE);
				error_log("total time taken in collaborative filtering".($end-$start));
			}
			
			if($this->mailer->getId() == 5915) {
				$recommendation_data = $obj->getRecommendations($user_request_data,$collaborativeFilterBasedDataSet,'no',3);
			}
			else {
				$recommendation_data = $obj->getRecommendations($user_request_data,$collaborativeFilterBasedDataSet);
			}
			$recommendation_data = json_decode(gzuncompress(base64_decode($recommendation_data)),true);
			$recommendations = $recommendation_data['recommendations'];
			
			$modelobj = $this->CI->load->model('recommendation/recommendation_model');
			$modelobj->init(NULL);
			$recommendation_ids = $modelobj->saveRecommendations($recommendations);
			
			/*
			 * Generate recommendation mailer for each user and 
			 * store in tMailQueue
			 */
			$algo_verbiage = array(
					'also_viewed' => 'Institutes popular amongst other admission seekers',
					'similar_institutes' => 'Institutes similar to ',
					'profile_based' => 'Institutes that match your profile'	
					);
			
			$recommendationuseridskeys = array_keys($recommendations);
			foreach($userIds as $user_id ){
				if(in_array($user_id,$recommendationuseridskeys))
				{
					$recommended_course_ids = array();
					$abroadCourseData = array();
					$recommendationCategoryId = null;
					
					$user_recommendation_data = $recommendations[$user_id];
					$recommendations_by_category = $user_recommendation_data['recommendations'];
					foreach($recommendations_by_category as $category => $recommendation_details) {
						$recommendationCategoryId = $category;
						$category_recommendations_by_algo = $recommendation_details['recommendations'];
						foreach($category_recommendations_by_algo as $algo => $recommendation_details_by_algo)
						{
							foreach($recommendation_details_by_algo as $recommendation_course_details)
							{
								if($recommendation_course_details['course_id']) {
									$recommended_course_ids[] = $recommendation_course_details['course_id'];
								}
							}
						}
						break;
					}
					
					$isStudyAbroadRecommendation = $categoryRepository->find($recommendationCategoryId)->getFlag() == 'studyabroad' ? true : false;
					
					if($isStudyAbroadRecommendation) {
						$courseObjs = $courseRepository->findMultiple($recommended_course_ids);
						
						foreach($courseObjs as $course) {
							if($course instanceof AbroadCourse) {
								$courseId = $course->getId();
								$courseName = $course->getName();
								
								$universityId = $course->getUniversityId();
								$universityName = $course->getUniversityName();
								
								$fees = $course->getFees()->getValue();
								if($fees){
								    $feesCurrency = $course->getFees()->getCurrency();
								    $courseFees = $abroadListingCommonLib->convertCurrency($feesCurrency, 1, $fees);
								    $courseFees = $abroadListingCommonLib->getIndianDisplableAmount($courseFees, 1);
								}
								
								$exams = $course->getEligibilityExams();
								$courseExams = array();
								foreach($exams as $examObj) {
								    if($examObj->getId() == -1) {
									continue;
								    }
								    else {
									$courseExams[] = $examObj->getName();
								    }
								}
								
								$cityName = $course->getMainLocation()->getCity()->getName();
								$countryName = $course->getMainLocation()->getCountry()->getName();
								$courseLocation = $cityName.', '.$countryName;
								
								$university = $universityRepository->find($universityId);
								$universityPhotos = $university->getPhotos();
								if(count($universityPhotos)) {
								    $universityImageURL = $universityPhotos['0']->getThumbURL('172x115');
								} else {
								    $universityImageURL = SHIKSHA_HOME."/public/images/defaultCatPage1.jpg";
								}
								
								//create array of course data
								$abroadCourseData[$courseId] = array (
												'courseName' => $courseName,
												'courseFees' => $courseFees,
												'courseExam' => $courseExams,
												'courseLocation' => $courseLocation,
												'universityName' => $universityName,
												'universityImageURL' => $universityImageURL
											       );
							}
							
						}
						
					}
					
					$recommendation_ids_for_user = $recommendation_ids[$user_id];
					
					$data['user_id'] = $user_id;
					$data['data'] = $user_recommendation_data;
					$data['recommendation_ids'] = $recommendation_ids_for_user;
					$data['mailer_id'] = $mailer_id;
					$data['algo_verbiage'] = $algo_verbiage;
					$data['institute_details'] = $lastInstituteCourse[$user_id];
					$data['educationInterest'] = $educationInterest[$user_id];
					$data['studyAbroadEducationInterest'] = $studyAbroadEducationInterest[$user_id];
					$data['isStudyAbroadRecommendation'] = $isStudyAbroadRecommendation;
					$data['abroadCourseData'] = $abroadCourseData;
					
					$mailer_html = $this->CI->load->view('MailerWidgets/RecommendationTemplate',$data,true);
					if(trim($mailer_html) != '' ){
						$recommendationsArray[$user_id]['recommendation'] = $mailer_html;
					}
					else{
						$recommendationsArray[$user_id]['recommendation'] = "";
					}
				}
				else{
					$recommendationsArray[$user_id]['recommendation'] = "";
				}
			} 
			return $recommendationsArray;
		}
	}
}