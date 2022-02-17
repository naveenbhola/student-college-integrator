<?php
/**
 * ListingProfileCompletion Controller Class
 *
 * This library is will be used across the site to get percentage completion for a lisiting
 *
 * @package     Enterprise
 * @subpackage  Libraries
 * @author      Aditya Roshan
 *
 */
class ListingProfileLib {

	private $CI;
	private $course_model_object = NULL;
	private $institute_repository = NULL;
	private $course_repository = NULL;
	private $listingmodel_object = NULL;

	function __construct() {

		$this->CI =& get_instance();
		$this->CI->load->library('listing/ListingProfileConfig');

		$this->CI->load->builder('ListingBuilder','listing');
		$listingbuilder = new ListingBuilder();
		$this->institute_repository = $listingbuilder->getInstituteRepository();
		$this->course_repository = $listingbuilder->getCourseRepository();

		$this->CI->load->model('coursemodel');
		$this->course_model_object = new CourseModel();

		$this->CI->load->model('listingmodel');	
		$this->listingmodel_object = new ListingModel();
	}

	public function updateProfileCompletion($inst_id) {

		if($inst_id >0) {

			$institute = $this->institute_repository->find($inst_id);

			if($institute->getInstituteType() == 'Academic_Institute') {
				// update percentage sore for cms enhancements
				$completion_array = $this->calculateProfileCompeletion($inst_id);
				$this->listingmodel_object->updateInstituteColumns($inst_id,$completion_array['percentage_completion']);
			}

		}

	}

	public function updateCourseProfileCompletion($course_id,$course_reach = array()) {

		if($course_id >0) {
	
			if(count($course_reach) == 0) {
				$course_reach = $this->course_model_object->getCourseReachForCourses(array($course_id));
			}

			$course_type = "local";
			if(is_array($course_reach) && !empty($course_reach[$course_id])) {
				$course_type = $course_reach[$course_id];
			}
			// update percentage sore for cms enhancements
			$score_array = $this->calculateProfileCompeletionForCourse($course_id,$course_type);
			$final_actual_score = $score_array['actual_score'];
			$final_total_score = $score_array['total_score'] ;
			$percentage_score = round(($final_actual_score/$final_total_score)*100,2);
			$this->course_model_object->updateProfileCompletion($course_id, $percentage_score);
			return $percentage_score;
		}

	}

	/**
	 * Calculate final percentage completion for listing
	 * @access  default
	 * @param   int $institute_type
	 * @return  array $data
	 * @ToDo
	 */
	function calculateProfileCompeletion($institute_id,$no_of_empty_fields = '',$listingData = array()) {
		// get the score mapping array
		$config_array = ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING;
		// get data for institute
		if(count($listingData) == 0) {
			$listingData = $this->institute_repository->findInstituteWithValueObjects($institute_id,array('description','joinreason'));
		}
		// score calculation starts here
		// predefined variables
		$score_array = array();
		$institute_score_array = array();
		$courses_scores_array = array();
		$final_actual_score = 0;
		$final_total_score = 0;
		$final_field_list_array = array();
		$final_field_list_array_scores = array();

		// check if data is there
		if(gettype($listingData) == 'object') {

			// setting default institute_type local for fall back
			$institute_type = "local";
			$live_courses = $this->institute_repository->getCoursesOfInstitutes(array($institute_id));
			
			if(count($live_courses)) {
				$courses = explode(",", $live_courses[$institute_id]['courseList']);
				$course_title_list = $live_courses[$institute_id]['course_title_list'];
				$course_reach = $this->course_model_object->getCourseReachForCourses($courses);
				//_P($course_reach);
	
				// check if course reach is there, it means if client course is mapped with ldb course
				$course_list_redefined = array();
				foreach($courses as $course) {
					if(!empty($course_reach[$course])) {
						$course_list_redefined[] = $course;
					}
				}
				// set institute type
				if(!empty($course_list_redefined[0])) {
					$institute_type = $course_reach[$course_list_redefined[0]];
				}
				// cal percentage calculation API on every course
				foreach($course_list_redefined as $course) {
					$temp_array = $this->calculateProfileCompeletionForCourse($course,$course_reach[$course]);
					if(count($temp_array)>0) {
						$courses_scores_array[$course] = $temp_array;
						$courses_scores_array[$course]['courseTitle'] = $course_title_list[$course];
						if(empty($course_reach[$course])) {
							$CourseReach = "local";
						} else {
							$CourseReach = $course_reach[$course];
						}
	
						$courses_scores_array[$course]['CourseReach'] = $CourseReach;
					}
				}
				
			}	// End of if(count($live_courses)).		

			//_P($course_title_list);exit;
			// calculate percentage profile completion of Institute
			$institute_score_array = $this->calculateProfileCompeletionForInstitute($institute_type,$listingData);
			//_P($courses_scores_array[142687]);

			// calculate final total score, which is equal to score obtained by institute + courses
			$final_total_score = $final_total_score + $institute_score_array['total_score'];
			$final_actual_score = $final_actual_score + $institute_score_array['actual_score'];

			// calculate empty fields institute + courses
			$institute_empty_fields_array = array_diff_key($config_array['institute'][$institute_type], $institute_score_array['field_list']);

			if(array_key_exists('photo-video-count',$institute_score_array['field_list']) &&
			$institute_score_array['field_list']['photo-video-count'] <= ListingProfileConfig::$MEDIA_MAX_COUNT) {
				$institute_empty_fields_array['photo-video-count'] = $config_array['institute'][$institute_type]['photo-video-count'];
				$score  = $this->calculateScoreForMediaField('mediaInfo',$institute_score_array['field_list']['photo-video-count']);
				$institute_empty_fields_array['photo-video-count']['max-score'] = $institute_empty_fields_array['photo-video-count']['max-score'] -  $score;
			}

			if(array_key_exists('recruiting-companies-media-tab',$institute_score_array['field_list']) &&
			$institute_score_array['field_list']['recruiting-companies-media-tab'] <= ListingProfileConfig::$MEDIA_MAX_COUNT) {
				$institute_empty_fields_array['recruiting-companies-media-tab'] = $config_array['institute'][$institute_type]['recruiting-companies-media-tab'];
				$score  = $this->calculateScoreForMediaField('recruiting-companies-media-tab',$institute_score_array['field_list']['recruiting-companies-media-tab']);
				$institute_empty_fields_array['recruiting-companies-media-tab']['max-score'] = $institute_empty_fields_array['recruiting-companies-media-tab']['max-score'] -  $score;
			}

			foreach ($courses_scores_array as $course_key=>$course_score) {
				$final_total_score = $final_total_score + $course_score['total_score'];
				$final_actual_score = $final_actual_score + $course_score['actual_score'];
				$empty_fields_array = array_diff_key($config_array['course'][$course_score['CourseReach']], $course_score['field_list']);
				foreach ($empty_fields_array as $key=>$field) {
					$key = $key.ListingProfileConfig::$KEY_SEPERATOR.$course_score['courseTitle'];
					$final_field_list_array[$key] = $field;
				}
			}
			
			// sort empty fields array by highest marks
			$final_field_list_array = $institute_empty_fields_array + $final_field_list_array;

			uasort($final_field_list_array, function ($a, $b) {
				if ($a['max-score'] == $b['max-score']) {
					return 0;
				}
				return ($a['max-score'] < $b['max-score']) ? 1 : -1;
			}
			);

			// send final results to calling API
			$slace_length = ListingProfileConfig::$NO_OF_FIELDS_TO_DISPLAY;
			if(!empty($no_of_empty_fields)) {
				$slace_length = $no_of_empty_fields;
			}

			$score_array['fields_list'] = array_slice($final_field_list_array, 0,$slace_length);
			$score_array['agrregate_score_actual'] = $final_actual_score;
			$score_array['agrregate_score_total'] = $final_total_score;
			$score_array['percentage_completion'] = round(($final_actual_score/$final_total_score)*100,2);
		}


		return $score_array;

	}
	/**
	 * Calculate percentage completion for institute
	 * @access  default
	 * @param   int $institute_type
	 * @return  array $data
	 * @ToDo
	 */
	function calculateProfileCompeletionForInstitute($institute_type,$data) {

		// get top recruiting-companies-media-tab data
		//$data['recruiting-companies-media-tab']=$this->listing_media_object->getDistinctLogo($data['institute_id'],'getLiveVersion');

		// predefined variables
		$institute_total_score = 0;
		$actual_institute_score = 0;
		$fields_filled_array = array();
		$score_array = array();

		// get the mapping of scores for the fields from shiksha constant
		$config_array = ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING;

		$institute_config_array = $config_array['institute'][$institute_type];


		// get total max score
		foreach($config_array['institute'][$institute_type] as $key=>$value) {
			$institute_total_score = $institute_total_score + $value['max-score'];
		}

		foreach ($institute_config_array as $item_key =>$item) {
			$temp = "";
			$temp_value = "";
			switch ($item_key) {
				case 'abbreviation' :
					$temp_value = $data->getAbbreviation();
					break;
				case 'establish_year' :
					$temp_value = $data->getEstablishedYear();
					break;
				case 'usp' :
					$temp_value = $data->getUsp();
					break;
				case 'institute_logo' :
					$temp_value = $data->getLogo();
					break;
				case 'institute_request_brochure_link' :
					$temp_value = $data->getRequestBrochure();
					break;
				case 'contact_cell' :
					$temp = $data->getMainLocation()->getContactDetail();
					$contact_cell =  $temp->getContactCell();
					if(!empty($contact_cell)) {
						$temp_value = TRUE;
					}
					break;
				case 'contact_main_phone' :
					$temp = $data->getMainLocation()->getContactDetail();
					$contact_phone = $temp->getContactMainPhone();
					if(!empty($contact_phone)) {
						$temp_value = TRUE;
					}
					break;
				case 'contact_email' :
					$temp = $data->getMainLocation()->getContactDetail();
					$contact_email = $temp->getContactEmail();
					if(!empty($contact_email)) {
						$temp_value = TRUE;
					}
					break;
				case 'website' :
					$temp = $data->getMainLocation()->getContactDetail();
					$website = $temp->getContactWebsite();
					if(!empty($website)) {
						$temp_value = TRUE;
					}
					break;
				case 'address' :
					$temp = $data->getMainLocation();
					$address1 = $temp->getAddress1();
					$address2 = $temp->getAddress2();
					if(!empty($address1) && !empty($address2)) {
						$temp_value = TRUE;
					}
					break;
				case 'joinreason' :
					$temp = $data->getJoinReason();
					$url = $temp->getPhotoUrl();
					$details = $temp->getDetails();
					if(!empty($url) && $details) {
						$temp_value = TRUE;
					}
					break;
				case 'insti_desc' :
					$key_to_check = "Institute Description";
					$temp_value = $this->checkListingAttributes($data,$key_to_check);
					//_P($data->getDescriptionAttributes());exit;
					break;
				case 'rankings' :
					$key_to_check = "Rankings & Awards";
					$temp_value = $this->checkListingAttributes($data,$key_to_check);
					break;
				case 'recruiting_companies' :
					$key_to_check = "Top Recruiting Companies";
					$temp_value = $this->checkListingAttributes($data,$key_to_check);
					break;
				case 'infrastructure' :
					$key_to_check = "Infrastructure / Teaching Facilities";
					$temp_value = $this->checkListingAttributes($data,$key_to_check);
					//$atrri = $data->getDescriptionAttributes();
					//_P($atrri);
					break;
				case 'hostel_details' :
					$key_to_check = "Hostel Details";
					$temp_value = $this->checkListingAttributes($data,$key_to_check);
					break;
				case 'faculty' :
					$key_to_check = "Top Faculty";
					$temp_value = $this->checkListingAttributes($data,$key_to_check);
					break;
				// case 'seofieldsvalues' :
				// 	$temp = $data->getMetaData();
				// 	if(!empty($temp['seoTitle']) && !empty($temp['seoKeywords']) && !empty($temp['seoKeywords'])) {
				// 		$temp_value = TRUE;
				// 	}
				// 	break;
				case 'photo-video-count' :
					$photos = count($data->getPhotos());
					$videos = count($data->getVideos());
					$total_media = $photos + $videos;
					$actual_institute_score = $actual_institute_score + $this->calculateScoreForMediaField('mediaInfo', $total_media);
					$fields_filled_array[$item_key] = $total_media;
					break;
				case 'recruiting-companies-media-tab' :
					$this->CI->load->library('listing_media_client');
					$media_object = new Listing_media_client();
					$media_list_count = count($media_object->getDistinctLogo($data->getId(),'getLiveVersion'));
					$actual_institute_score = $actual_institute_score + $this->calculateScoreForMediaField('recruiting-companies-media-tab', $media_list_count);
					$fields_filled_array[$item_key] = $media_list_count;
					break;
						


			}
				
			if(!empty($temp_value) && !in_array($item_key, array('photo-video-count','recruiting-companies-media-tab'))) {
				$actual_institute_score = $actual_institute_score + $this->addToActualScore($item_key, 'institute',$institute_type);
				$fields_filled_array[$item_key] = $item_key;
			}
				
		}

		$score_array['total_score'] = $institute_total_score;
		$score_array['actual_score'] = $actual_institute_score;
		$score_array['field_list'] = $fields_filled_array;

		return $score_array;
		

	}
	/**
	 * Calculate percentage completion for course
	 * @access  default
	 * @param   int $course_id
	 * @return  string $course_type
	 * @ToDo
	 */
	function calculateProfileCompeletionForCourse($course_id,$course_type,$listingData = "") {

		// set course type -- local/national
		$course_type = trim($course_type);
		// set it as local if course type is empty
		if(empty($course_type)) {
			$course_type = "local";
		}
		// predefined variables
		$course_total_score = 0;
		$actual_course_score = 0;
		$fields_filled_array = array();
		$score_array = array();

		// get data for course
		if(empty($listingData)) {
			$listingData = $this->course_repository->findCourseWithValueObjects($course_id,array('description'));

		}

		// get course total score;
		// get the mapping of scores for the fields from shiksha constant
		$config_array = ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING;

		// get total max score
		foreach($config_array['course'][$course_type] as $key=>$value) {
			$course_total_score = $course_total_score + $value['max-score'];
		}

		$course_config_array = $config_array['course'][$course_type];

		foreach ($course_config_array as $item_key=>$item) {
			$temp = "";
			$temp_value = "";
			switch ($item_key) {

				case 'fees_value' :
					$temp = $listingData->getFees(0);
					$temp_value = $temp->getValue();

					if(empty($temp_value)) {
						$temp = $listingData->getFees($listingData->getMainLocation()->getLocationId());
						$temp_value = $temp->getValue();
					}

					break;

				case 'AffiliatedTo' :
					$temp = $listingData->getAffiliations();
					if(count($temp)>0) {
						$temp_value = TRUE;
					}

					break;

				case 'approved_by' :
					$temp = $listingData->getApprovals();
					if(count($temp)>0) {
						$temp_value = TRUE;
					}

					break;

				case  'AccreditedBy' :
					$temp_value = $listingData->getAccredited();
					break;

				case 'courseExams' :
					$temp = $listingData->getEligibilityExams();
					if(count($temp)>0){
						$temp_value = TRUE;
					}

					break;

				case 'otherEligibilityCriteria' :
					$temp_value = $listingData->getOtherEligibilityCriteria();
					break;

				case 'no_of_seats' :
					$temp = $listingData->getSalary();
					if(!empty($temp['avg']) && !empty($temp['min']) && !empty($temp['max'])) {
						$temp_value = TRUE;
					}

					break;


				case 'important_dates' :
					$form_sub = $listingData->getDateOfFormSubmission();
					$result_decla = $listingData->getDateOfResultDeclaration();
					$course_commence = $listingData->getDateOfCourseComencement();
					if(!empty($form_sub) && !empty($result_decla) && !empty($course_commence)) {
						$temp_value = TRUE;
					}

					break;

				case 'course_request_brochure_link' :
					$temp_value = $listingData->getRequestBrochure();

					break;

				case 'salary_statistics' :
					$temp = $listingData->getSalary();
					if(!empty($temp['avg']) && !empty($temp['min']) && !empty($temp['max'])) {
						$temp_value = TRUE;
					}

					break;

				case 'contact_details' :
					$temp = $listingData->getMainLocation()->getContactDetail();
					//_P($temp);exit;
					$contact_person =  $temp->getContactPerson();
					$contact_email = $temp->getContactEmail();
					$contact_phone = $temp->getContactMainPhone();
					$contact_cell =  $temp->getContactCell();
					if(gettype($temp) == 'object' && !empty($contact_person) && !empty($contact_email) && !empty($contact_phone)
					&& !empty($contact_cell)) {
						$temp_value = TRUE;
					}

					break;

				case 'course_desc' :
					$key_to_check = "Course Description";
					$temp_value = $this->checkListingAttributes($listingData,$key_to_check);
					break;

				case 'eligibility':
					$key_to_check = "Eligibility";
					$temp_value = $this->checkListingAttributes($listingData,$key_to_check);
					break;

				case 'admission_procedure':
					$key_to_check = "Admission Procedure";
					$temp_value = $this->checkListingAttributes($listingData,$key_to_check);
					break;

				case 'faculty' :
					$key_to_check =  'Faculty';
					$temp_value = $this->checkListingAttributes($listingData,$key_to_check);
					break;

				case 'syllabus' :
					$key_to_check =  'Syllabus';
					$temp_value = $this->checkListingAttributes($listingData,$key_to_check);
					break;

				// case 'seofieldsvalues' :
				// 	$temp = $listingData->getMetaData();
				// 	if(!empty($temp['seoTitle']) && !empty($temp['seoKeywords']) && $temp['seoDescription']) {
				// 		$temp_value  = TRUE;
				// 	}
				// 	break;


			}

			if(!empty($temp_value)) {
				$actual_course_score = $actual_course_score + $this->addToActualScore($item_key, 'course', $course_type);
				$fields_filled_array[$item_key] = $item_key;
			}
		}

		$score_array['total_score'] = $course_total_score;
		$score_array['actual_score'] = $actual_course_score;
		$score_array['field_list'] = $fields_filled_array;
		return $score_array;
	}

	public function checkListingAttributes($listingData,$key_to_check) {

		$atrri = $listingData->getDescriptionAttributes();
		foreach ($atrri as $att) {
			$name = $att->getName();
			$value = $att->getValue();
			if($name == $key_to_check && !empty($value)) {
				return TRUE;
				break;
			}
		}
	}

	public function addToActualScore($key,$key1,$key2) {

		if(array_key_exists($key, ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING[$key1][$key2])) {

			return ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING[$key1][$key2][$key]['max-score'];
		}

		return 0;
	}

	public function calculateScoreForMediaField($media_type,$media_count) {
		if($media_type == 'mediaInfo') {
			if($media_count <= 3) {
				return 5;
			} else if($media_count > 3 && $media_count <= ListingProfileConfig::$MEDIA_MAX_COUNT) {
				return (5 + ($media_count - 3)*2);
			} else if($media_count >5) {
				return 10;
			}

		} else if($media_type == 'recruiting-companies-media-tab') {

			if($media_count <= 3) {
				return 3;
			} else if($media_count > 3 && $media_count <= ListingProfileConfig::$MEDIA_MAX_COUNT) {
				return (3 + ($media_count - 3)*1);
			} else if($media_count >5) {
				return 6;
			}

		}
	}

	public function getProfileCompletionForaListofLisitngs($institute_ids_array = array()) {

		if(count($institute_ids_array) == 0) {
			return array();
		}

		$this->CI->load->model('institutemodel');
		$institute_model_object = new InstituteModel();
		return $institute_model_object->getProfileCompletionForaListofLisitngs($institute_ids_array);
	}
}
