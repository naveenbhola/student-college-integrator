<?php
include_once('MailerWidgetAbstract.php');
class NationalRecommendationWidget extends MailerWidgetAbstract
{
	private $collaborativeFilter;
	
	function __construct(MailerModel $mailerModel,profile_based_collaborative_filter_lib $collaborativeFilter)
	{
		parent::__construct($mailerModel);
		$this->collaborativeFilter = $collaborativeFilter;

		$this->CI->load->builder("nationalCourse/CourseBuilder");
		$builder = new CourseBuilder();
    	$this->courseRepository = $builder->getCourseRepository();

    	$this->CI->load->builder("nationalInstitute/InstituteBuilder");
    	$instituteBuilder = new InstituteBuilder();
        $this->instituteRepository = $instituteBuilder->getInstituteRepository();
		
		$this->CI->load->model('recommendation/recommendation_model');
		$this->recommendationModel = new Recommendation_Model;
		
		$this->CI->load->library('recommendation_front_lib');
		$this->recommendationFrontLib = new Recommendation_Front_Lib();

		$this->CI->load->library('nationalCourse/CourseDetailLib');
		$this->courseDetailLib = new CourseDetailLib();
		
		$this->CI->load->library('mailer/ProductMailerConfig');

		$this->CI->load->builder('ListingBaseBuilder', 'listingBase');
		$this->listingBaseBuilder = new ListingBaseBuilder();
		$this->hierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
		$this->baseCourseRepository = $this->listingBaseBuilder->getBaseCourseRepository();
	}
	
	/**
	 * API for getting recommendations data
	 */
	public function getData($userIds, $params)
	{
		if(is_array($userIds) && count($userIds)) {
			$recommendationsArray = array();
			
			/**
			 * Create request data for recommendations
			 */
			$user_request_data = array();
			foreach ($userIds as $user) {
				$user_request_data[] = array($user,'string');
			}
			error_log("User for Recommendation==".print_r($user_request_data,true));

			/**
			 * Generate recommendations for users
			 */
			$recommendation_data = $this->recommendationFrontLib->getRecommendations($user_request_data, 1, 'no');
			$recommendations = $recommendation_data['recommendations'];
			// $recommendations['3814277']['also_viewed'][0]['courseId'] = 1656;
			// $recommendations['3814277']['also_viewed'][0]['instituteId'] = 307;

			// $recommendations['5890489']['also_viewed'][0]['courseId'] = 109604;
			// $recommendations['5890489']['also_viewed'][0]['instituteId'] = 28130;
			
			error_log("Recommendations for Users==".print_r($recommendations,true));
			unset($recommendation_data);
			
			/**
			 * Create data for recommendation widgets
			 */
			$userCourseMap = array();
			$coursesInRecommendation = array();
			$institutesInRecommendation = array();
			$finalRecommendations = array();
			foreach($recommendations as $userId => $user_recommendation_data) {
				foreach($user_recommendation_data as $algo => $recommendation_details_by_algo)	{
					if(count($recommendation_details_by_algo)) {
						$course_id = $recommendation_details_by_algo[0]['courseId'];
						$institute_id = $recommendation_details_by_algo[0]['instituteId'];
						if($course_id > 0 && $institute_id > 0) {
							$coursesInRecommendation[$course_id] = TRUE;
							$finalRecommendations[$userId][$algo] = $recommendation_details_by_algo[0];
							$userCourseMap[$course_id][] = $userId;
 							break;
						}
					}
				}				
			}
			unset($recommendations);

			/**
			* Store recommendations for users
			*/
			$recommendation_ids = $this->recommendationModel->saveRecommendations($finalRecommendations);
			$userForMPT = array();
			$userNotForMPT = array();
			if(count($coursesInRecommendation)) {
				$course_ids = array_keys($coursesInRecommendation);
				
				$courseObjects = $this->courseRepository->findMultiple($course_ids, array('eligibility','placements_internships'));
				foreach($courseObjects as $course_id => $courseObj) {
					if(is_object($courseObj)) {
						$courseInstituteId = $courseObj->getInstituteId();
						$isPaid = $courseObj->isPaid();
						foreach ($userCourseMap[$course_id] as $key => $user){
							if($isPaid===false){
								$userForMPT[] = $user;
							}
							else{
								$userNotForMPT[] = $user; 
							}
						}
						if($courseInstituteId > 0) {
							$institutesInRecommendation[$courseInstituteId] = TRUE;
						}
					}
				}
				
				if(!empty($institutesInRecommendation)) {
					$institute_ids = array_keys($institutesInRecommendation);
					
					if(count($institute_ids)) {
						$instituteObjects = $this->instituteRepository->findMultiple($institute_ids, array('media'));
					}
				}
			}
			
			$mailerType = ProductMailerConfig::getItem($params['mailer']->getId(),'UsersType');
			$userInterest = $this->mailerModel->getUsersExplicitInterest($userIds, $params);
			$interestHeaderText = $this->getUserInterestForHeaderText($courseObjects, $userInterest, $mailerType);
			$mailerName = 'DetailedRecommendationMailer';
			
			$this->CI->load->config('mailer/mailerConfig');
			$mptHtmlHashkey = $this->CI->config->item('mptHtmlHashkey');	
			$mptHTML = '';

			if(!empty($userForMPT)) {
				try {
					$mailerParams = array();
					$mailerParams['mailerDetails']['mailer_id'] = '48';
					$mailerParams['mailerDetails']['mailer_name'] = $mailerName;
					$mptHTML = Modules::run('MPT/MPTController/getMPTHtmlForUsers',$userForMPT, $mailerParams);
				} catch(Exception $e) {
					mail('teamldb@shiksha.com,mohd.alimkhan@shiksha.com','Exception in getting HTML for MPT tupple (file NationalRecommendationWidget.php) at '.date('Y-m-d H:i:s'), 'Exception: '.$e->getMessage().'<br/>Chunk:'.print_r($userForMPT, true));
				}
			}

			if(!empty($userNotForMPT)){
				$this->CI->load->library(array('MPT/mptlibrary'));
				$this->mptLib = new mptlibrary();
				$this->mptLib->writeMPTDataInFile('48',$mailerName,$userNotForMPT,"no","2","0");
			}
			
			$userHasRecommendation = array_keys($finalRecommendations);
			foreach($userIds as $user_id) {
				$mailer_html = '';
				if(in_array($user_id, $userHasRecommendation)) {
					$recommendationWidgets = array();
					$algo_used = null;
					$course_id = null;
					$institute_id = null;
					
					$user_recommendation_data = $finalRecommendations[$user_id];
					foreach($user_recommendation_data as $algo => $recommendation_details_by_algo) {
						if(!empty($recommendation_details_by_algo)) {
							$algo_used = $algo;
							$course_id = $recommendation_details_by_algo['courseId'];
							$institute_id = $recommendation_details_by_algo['instituteId'];
							
							if($course_id > 0 && $institute_id > 0) {
								break;
							}
						}
					}

					if($course_id && $institute_id) {
						switch ($mailerType) {
							case 'response':
								$interestHeaderText = $interestHeaderText[$course_id];
								break;
							
							default:
								$interestHeaderText = $interestHeaderText[$user_id];
								break;
						}

						$course = $courseObjects[$course_id];
						$institute = $instituteObjects[$institute_id];
						if(is_object($institute) && is_object($course)) {
							$recommendation_id = '';$course_url = '';
  							$recommendation_id = $recommendation_ids[$user_id][$institute_id];
  							$course_url = SHIKSHA_HOME."/nationalrecommendations/course/$recommendation_id/0/1";

							$data['interestHeaderText'] = $interestHeaderText;
							$data['userId'] = $user_id;
							//$data['courseUrl'] = $course_url;
							$data['courseObj'] = $course;
							$data['instituteObj'] = $institute;
							$data['isAbroadRecommendation'] = false;
							$data['leanHeaderFooterV2'] = 1;
							$data['listing_type_id'] = $course_id;
							$data['listing_type'] = 'course';
							$data['mailer_name'] = $mailerName;
							$data['mailer_type'] = 'MMM';
							$data['mptHtmlHashkey'] = $mptHtmlHashkey;				

							$mailer_html = Modules::run('personalizedMailer/personalizedMailer/getDataForWidgets', $mailerName, $data);

							if(!empty($mptHTML[$user_id])) {
								$mailer_html = str_replace($mptHtmlHashkey, $mptHTML[$user_id], $mailer_html);
							}
							else{
								$mailer_html = str_replace($mptHtmlHashkey, '', $mailer_html);
							}
						}
						
						if(trim($mailer_html) != '' ) {
							$recommendationsArray[$user_id]['nationalRecommendation'] = $mailer_html;
							$recommendationsArray[$user_id]['recommendedInstituteTitle'] = $institute->getName();
							$recommendationsArray[$user_id]['recommendedCourseTitle'] = $course->getName();
						}
						else {
							$recommendationsArray[$user_id]['nationalRecommendation'] = '';
							$recommendationsArray[$user_id]['recommendedInstituteTitle'] = '';
							$recommendationsArray[$user_id]['recommendedCourseTitle'] = '';
						}
					}
					else {
						$recommendationsArray[$user_id]['nationalRecommendation'] = '';
						$recommendationsArray[$user_id]['recommendedInstituteTitle'] = '';
						$recommendationsArray[$user_id]['recommendedCourseTitle'] = '';
					}
				}
				else {
					$recommendationsArray[$user_id]['nationalRecommendation'] = '';
					$recommendationsArray[$user_id]['recommendedInstituteTitle'] = '';
					$recommendationsArray[$user_id]['recommendedCourseTitle'] = '';
				}
			}
			return $recommendationsArray;
		}
	}

	/*
	 * MailerType = 'response' or 'register'
	 */
	private function getUserInterestForHeaderText($courseObjects, $userInterest, $mailerType) {
		switch ($mailerType) {
			case 'response':
				foreach ($courseObjects as $courseId => $courseObj) {
					$courseTypeInfo = $courseObj->getCourseTypeInformation();
					$baseCourseId 	= $courseTypeInfo['entry_course']->getBaseCourse();
					$courseLevel 	= $courseTypeInfo['entry_course']->getCourseLevel();
					$credential 	= $courseTypeInfo['entry_course']->getCredential();
					$hierarchies 	= $courseTypeInfo['entry_course']->getHierarchies();
					
					foreach ($hierarchies as $key => $hierarchy) {
						if($hierarchy['primary_hierarchy']) {
							if(!empty($hierarchy['specialization_id'])) {
								$specializationObj = $this->hierarchyRepository->findSpecialization($hierarchy['specialization_id']);
								$hierarchyName = $specializationObj->getName();
							}
							else if (!empty($hierarchy['substream_id'])) {
								$substreamObj = $this->hierarchyRepository->findSubstream($hierarchy['substream_id']);
								$hierarchyName = $substreamObj->getName();
							}
							else {
								$streamObj = $this->hierarchyRepository->findStream($hierarchy['stream_id']);
								$hierarchyName = $streamObj->getName();
							}
							break;
						}
					}
					
					$locationObj = $courseObj->getMainLocation();
					if(!empty($locationObj)) {
						$cityName = $locationObj->getCityName();
					}
					$headerText = '';
					if(!empty($baseCourseId)) {
						$baseCourseObj = $this->baseCourseRepository->find($baseCourseId);
						$baseCourseName = $baseCourseObj->getName();
						if(!empty($cityName)) {
							$headerText = $baseCourseName.' in '.$hierarchyName.' in '.$cityName;
						} else {
							$headerText = $baseCourseName.' in '.$hierarchyName;
						}
					} else {

						if($courseLevel->getId() != NONE_COURSE_LEVEL) {
							$headerText = $courseLevel->getName(). ' in ';
						}
						if($credential->getId() != NONE_CREDENTIAL) {
							$headerText .= $credential->getName(). ' in ';
						}
						if(!empty($cityName)) {
							$headerText .= $hierarchyName.' in '.$cityName;
						} else {
							$headerText .= $hierarchyName;
						}
					}
					$courseHeaderText[$courseObj->getId()] = $headerText;
				}
				break;
			
			default:

				foreach ($userInterest as $userId => $interest) {
					$headerText = '';
					if($interest['level'] != 'None') {
						$headerText = $interest['level'];
					} else {
						$headerText = 'Certificate';
					}

					if(!empty($interest['stream'])) {
						$headerText .= ' in '.$interest['stream'];
					}

					if($interest['city'] != NULL) {
                        $headerText .= ' in '.$interest['city'].', India ';
                    }
                    $courseHeaderText[$userId] = $headerText;
				}
				break;
		}
		
		return $courseHeaderText;
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
