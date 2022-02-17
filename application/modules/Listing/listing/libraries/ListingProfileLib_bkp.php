
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

	var $CI;
	var $listingmodel_object;
	var $listing_client_object;
	var $listing_media_object;

	function __construct() {
		$this->CI =& get_instance();
		$this->CI->load->model('listingmodel');
		$this->CI->load->library('Listing_client');
		$this->CI->load->library('listing_media_client');
		$this->CI->load->library('listing/ListingProfileConfig');
		$this->listingmodel_object = new ListingModel();
		$this->listing_client_object = new Listing_client();
		$this->listing_media_object = new Listing_media_client();
	}

	public function updateProfileCompletion($inst_id) {

		if($inst_id >0) {

			$this->CI->load->builder('ListingBuilder','listing');
			$listingBuilder = new ListingBuilder;
			$instituteRepository = $listingBuilder->getInstituteRepository();
			$institute = $instituteRepository->find($inst_id);

			if($institute->getInstituteType() == 'Academic_Institute') {
				// update percentage sore for cms enhancements
				$completion_array = $this->calculateProfileCompeletion($inst_id);
				$this->listingmodel_object->updateInstituteColumns($inst_id,$completion_array['percentage_completion']);
			}

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
			$listingData = $this->listing_client_object->getListingForEdit('1',$institute_id,'institute','getLiveVersion');
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
		if(is_array($listingData) && !empty($listingData[0])) {

			// setting default institute_type local for fall back
			$institute_type = "local";
			$course_list = unserialize(base64_decode($listingData[0]['courseList']));

			// check if course reach is there, it means if client course is mapped with ldb course
			$course_list_redefined = array();
			foreach($course_list as $course) {
				if(array_key_exists('CourseReach',$course)) {
					$course_list_redefined[] = $course;
				}
			}
			// set institute type
			if(!empty($course_list_redefined[0]['CourseReach'])) {
				$institute_type = $course_list_redefined[0]['CourseReach'];
			}

			// cal percentage calculation API on every course
			foreach($course_list_redefined as $course) {
				$temp_array = $this->calculateProfileCompeletionForCourse($course['course_id'],$course['CourseReach']);
				if(count($temp_array)>0) {
					$courses_scores_array[$course['course_id']] = $temp_array;
					$courses_scores_array[$course['course_id']]['courseTitle'] = $course['courseTitle'];
					if(empty($course['CourseReach'])) {
						$course['CourseReach'] = "local";
					}
					$courses_scores_array[$course['course_id']]['CourseReach'] = $course['CourseReach'];
				}
			}

			// calculate percentage profile completion of Institute
			$institute_score_array = $this->calculateProfileCompeletionForInstitute($institute_type,$listingData[0]);
				
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
		$data['recruiting-companies-media-tab']=$this->listing_media_object->getDistinctLogo($data['institute_id'],'getLiveVersion');

		// predefined variables
		$institute_total_score = 0;
		$actual_institute_score = 0;
		$fields_filled_array = array();
		$score_array = array();

		// get the mapping of scores for the fields from shiksha constant
		$config_array = ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING;

		// get total max score
		foreach($config_array['institute'][$institute_type] as $key=>$value) {
			$institute_total_score = $institute_total_score + $value['max-score'];
		}

		// calculate percentage completion for every field
		foreach($data as $key=>$value) {

			// wiki fields data
			if($key == 'wikiFields' || $key == 'mediaInfo') {
				$value = unserialize(base64_decode($value));
			}

			// case for multi select fields
			if(is_array($value)) {

				// score calculation for location related fields
				if($key == 'locations' || $key == 'contactInfo') {

					$value = $value[0];
					$address1 = trim($value['address1']);
					$address2 = trim($value['address2']);

					if(!empty($address1) && !empty($address2)) {
						$actual_institute_score = $actual_institute_score + $this->addToActualScore('address','institute',$institute_type);
						$fields_filled_array['address'] = 'address';

					} else {
						foreach($value as $lock_key=>$lock_value) {
							$lock_value = trim($lock_value);
							if(!empty($lock_value)) {
								$actual_institute_score = $actual_institute_score +  $this->addToActualScore($lock_key,'institute',$institute_type);
								$fields_filled_array[$lock_key] = $lock_key;
							}
						}
					}

					// score calculation for joinreason related fields
				}else if($key == 'joinreason') {

					$value = $value[0];

					if(!empty($value['photoUrl']) && !empty($value['details'])) {
						$actual_institute_score = $actual_institute_score + $this->addToActualScore($key,'institute',$institute_type);
						$fields_filled_array[$key] = $key;
					}

					// score calculation for mediaInfo related fields
				} else if($key == 'mediaInfo') {

					if(count($value) > 0 && array_key_exists('photo-video-count', $config_array['institute'][$institute_type])) {
						$actual_institute_score = $actual_institute_score + $this->calculateScoreForMediaField('mediaInfo', count($value));
						$fields_filled_array['photo-video-count'] = count($value);
					}

					// score calculation for recruiting-companies-media-tab related fields
				}else if($key == 'recruiting-companies-media-tab') {

					if(count($value) > 0  && array_key_exists($key, $config_array['institute'][$institute_type])) {
						$actual_institute_score = $actual_institute_score + $this->calculateScoreForMediaField('recruiting-companies-media-tab', count($value));
						$fields_filled_array[$key] = count($value);
					}

				}else {
					// this calculation is for wiki fields other than join reason
					foreach($value as $value1) {
						foreach($value1 as $key1=>$value2) {
							$value2 = trim($value2);
							if(!empty($value1['attributeValue'])) {
								$actual_institute_score = $actual_institute_score + $this->addToActualScore($value1[$key1],'institute',$institute_type);
								$fields_filled_array[$value1[$key1]] = $value1[$key1];
							}
						}
					}
				}

			} else {

				// this calculation for single valued fields
				$value = trim($value);
				if(!in_array($key,array('seoListingUrl','seoListingTitle','listingSeoDescription','listingSeoKeywords')) && !empty($value)) {
					$actual_institute_score = $actual_institute_score + $this->addToActualScore($key, 'institute', $institute_type);
					$fields_filled_array[$key] = $key;
				}


			}
		}

		// score calculation for SEO related fields starts here
		$seoListingUrl = trim($data['seoListingUrl']);
		$seoListingTitle = trim($data['seoListingTitle']);
		$listingSeoDescription = trim($data['listingSeoDescription']);
		$listingSeoKeywords = trim($data['listingSeoKeywords']);

		if(!empty($seoListingTitle) && !empty($listingSeoDescription) && !empty($listingSeoKeywords)) {
			$fields_filled_array['seofieldsvalues'] = 'seofieldsvalues';
			$actual_institute_score = $actual_institute_score + $this->addToActualScore('seofieldsvalues', 'institute', $institute_type);
		}
		// score calculation for SEO related fields ends here

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
	function calculateProfileCompeletionForCourse($course_id,$course_type) {

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
		$listingData = $this->listing_client_object->getListingForEdit('1',$course_id,'course','getLiveVersion');

		// get course total score;
		// get the mapping of scores for the fields from shiksha constant
		$config_array = ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING;

		// get total max score
		foreach($config_array['course'][$course_type] as $key=>$value) {
			$course_total_score = $course_total_score + $value['max-score'];
		}

		if(is_array($listingData) && !empty($listingData[0])) {
			// calculate percentage completion for every field

			foreach($listingData[0] as $key=>$value) {

				// score calculation for wikiFields
				if($key == 'wikiFields') {

					$value = unserialize(base64_decode($value));

					if(is_array($value) && count($value) > 0) {
							
						foreach($value as $value1) {
							foreach($value1 as $key1=>$value2) {
									
								if($value1[$key1] == 'course_info') {
									$value1[$key1] = 'course_desc';
								}

								if(!empty($value1['attributeValue'])) {
									$actual_course_score = $actual_course_score + $this->addToActualScore($value1[$key1], 'course', $course_type);
									$fields_filled_array[$value1[$key1]] = $value1[$key1];
								}
							}

						}

					}
				 // score calculation for courseAttributes
				} else if($key == 'courseAttributes') {

					if(is_array($value) && count($value) > 0) {

						foreach($value as $attribute) {
							foreach($attribute as $attkey=>$attvalue) {

								$attvalue = trim($attvalue);

								if(array_key_exists($attvalue,$config_array['course'][$course_type])) {
									$actual_course_score = $actual_course_score + $this->addToActualScore($attvalue, 'course', $course_type);
									$fields_filled_array[$attvalue] = $attvalue;
								} else {

									if(in_array($attvalue,array('AICTEStatus','UGCStatus','DECStatus')) && !array_key_exists('approved_by',$fields_filled_array)) {
										$actual_course_score = $actual_course_score + $this->addToActualScore('approved_by', 'course', $course_type);
										$fields_filled_array['approved_by'] = 'approved_by';
									}

									if(stripos($attvalue,'AffiliatedTo') !== false && !array_key_exists('AffiliatedTo',$fields_filled_array)) {
										$actual_course_score = $actual_course_score + $this->addToActualScore('AffiliatedTo', 'course', $course_type);
										$fields_filled_array['AffiliatedTo'] = 'AffiliatedTo';
									}

									if($attvalue == 'SalaryMax') {
										$salary_stat1 = $attribute[value];
									} else if($attvalue == 'SalaryAvg') {
										$salary_stat2 = $attribute[value];
									} else if($attvalue == 'SalaryMin') {
										$salary_stat3 = $attribute[value];
									}

								}
							}
						}

						if(!empty($salary_stat1) && !empty($salary_stat2) && !empty($salary_stat3)) {
							$actual_course_score = $actual_course_score + $this->addToActualScore('salary_statistics', 'course', $course_type);
							$fields_filled_array['salary_statistics'] = 'salary_statistics';
						}

					}

					// score calculation for courseExams
				} else if($key == 'courseExams') {

					if(count($value)>0) {
						$actual_course_score = $actual_course_score + $this->addToActualScore($key, 'course', $course_type);
						$fields_filled_array[$key] = $key;
					}

				}else {
					$value = trim($value);
					if(!empty($value)) {
						if(array_key_exists($key, ListingProfileConfig::$INSTITUTE_COURSE_SCORES_MAPPING['course'][$course_type])) {
							$actual_course_score = $actual_course_score + $this->addToActualScore($key, 'course', $course_type);
							$fields_filled_array[$key] = $key;
						}
					}
				}
			}

			// score calculation for course contact details
			if(!empty($listingData[0]['contact_name']) && !empty($listingData[0]['contact_email']) && !empty($listingData[0]['contact_main_phone']) &&
			!empty($listingData[0]['contact_cell'])) {
				$actual_course_score = $actual_course_score + $this->addToActualScore('contact_details', 'course', $course_type);
				$fields_filled_array['contact_details'] = 'contact_details';
			}

			// score calculation for seats details
			if(!empty($listingData[0]['seats_total']) && !empty($listingData[0]['seats_general']) && !empty($listingData[0]['seats_management']) &&
			!empty($listingData[0]['seats_reserved'])) {
				$actual_course_score = $actual_course_score + $this->addToActualScore('no_of_seats', 'course', $course_type);
				$fields_filled_array['no_of_seats'] = 'no_of_seats';
			}

			// score calculation for form dates related fields
			if(!empty($listingData[0]['date_form_submission']) && !empty($listingData[0]['date_result_declaration']) && !empty($listingData[0]['date_course_comencement'])) {
				$actual_course_score = $actual_course_score +  $this->addToActualScore('important_dates', 'course', $course_type);
				$fields_filled_array['important_dates'] = 'important_dates';
			}

			// score calculation for SEO fields
			if(!empty($listingData[0]['seoListingTitle']) && !empty($listingData[0]['listingSeoDescription']) &&
			!empty($listingData[0]['listingSeoKeywords'])) {
				$actual_course_score = $actual_course_score + $this->addToActualScore('seofieldsvalues', 'course', $course_type);
				$fields_filled_array['seofieldsvalues'] = 'seofieldsvalues';
			}

			$score_array['total_score'] = $course_total_score;
			$score_array['actual_score'] = $actual_course_score;
			$score_array['field_list'] = $fields_filled_array;

		}

		return $score_array;
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
		
		$this->load->model('institutemodel');
		return $this->institutemodel->getProfileCompletionForaListofLisitngs($institute_ids_array);
	}
}
