<?php

class SolrDataProcessor {
	
	public static function getCourseDocumentData($solrCourseDocument = array()){
		$data = array();
		$data['course_id'] 						= 	$solrCourseDocument['course_id'];
		$data['course_title'] 					= 	$solrCourseDocument['course_title'];
		$data['course_type'] 					= 	$solrCourseDocument['course_type'];
		$data['course_level'] 					= 	$solrCourseDocument['course_level'];
		$data['course_level_1'] 				= 	$solrCourseDocument['course_level_1'];
		$data['course_level_2'] 				= 	$solrCourseDocument['course_level_2'];
		$data['course_head_office'] 			=   $solrCourseDocument['course_head_office'];
		$data['course_seo_url'] 				= 	$solrCourseDocument['course_seo_url'];
		$data['course_pack_type'] 				= 	$solrCourseDocument['course_pack_type'];
		$data['course_duration_normalized'] 	= 	$solrCourseDocument['course_duration_normalized'];
		$data['course_type_cluster'] 			= 	$solrCourseDocument['course_type_cluster'];
		$data['course_level_cluster'] 			= 	$solrCourseDocument['course_level_cluster'];
		$data['course_location_cluster'] 		= 	$solrCourseDocument['course_location_cluster'];
		$data['course_institute_location_id'] 	= 	$solrCourseDocument['course_institute_location_id'];
		$data['course_document_score'] 			= 	$solrCourseDocument['score'];
		$data['course_order'] 					= 	$solrCourseDocument['course_order'];
		$data['unique_id'] 						= 	$solrCourseDocument['unique_id'];
		$data['facetype'] 						= 	$solrCourseDocument['facetype'];
		$data['course_aof_fee'] 				= 	$solrCourseDocument['course_aof_fee'];
		$data['course_aof_last_date'] 			= 	$solrCourseDocument['course_aof_last_date'];
		$data['course_aof_min_qualification'] 	= 	$solrCourseDocument['course_aof_min_qualification'];
		$data['course_aof_exams_accepted'] 		= 	$solrCourseDocument['course_aof_exams_accepted'];
		$data['course_aof_externalurl'] 		= 	$solrCourseDocument['course_aof_externalurl'];
		$data['course_featured_bmskey'] 		= 	$solrCourseDocument['course_featured_bmskey'];
		$data['course_banner_bmskey'] 			= 	$solrCourseDocument['course_banner_bmskey'];
		return $data;
	}
	
	public static function getCourseDocumentViewCount($solrCourseDocument = array()){
		$data = array();
		$data['viewCount'] 	= 	$solrCourseDocument['course_view_count'];
		return $data;
	}
	
	public static function getCourseDocumentFees($solrCourseDocument = array()){
		$data = array();
		$data['fees_unit'] 	= 	$solrCourseDocument['course_fees_unit'];
		$data['fees_value'] = 	$solrCourseDocument['course_fees_value'];
		return $data;
	}
	
	public static function getCourseDocumentDuration($solrCourseDocument = array()){
		$data = array();
		$data['duration_unit'] 	= 	$solrCourseDocument['course_duration_unit'];
		$data['duration_value'] = 	$solrCourseDocument['course_duration_value'];
		return $data;
	}
	
	public static function getCourseDocumentAttributes($solrCourseDocument = array()) {
		$courseAttributes = array();
		$attributes = $solrCourseDocument['course_attributes'];
		foreach($attributes as $attribute){
			$attributeList = array();
			$attributeKeyValuePair = explode(":", $attribute);
			$attributeList['attribute']  = $attributeKeyValuePair[0];
			$attributeList['value']  = $attributeKeyValuePair[1];
			array_push($courseAttributes, $attributeList);
		}
		return $courseAttributes;
	}
	
	public static function getCourseDocumentSalientFeatures($solrCourseDocument = array()) {
		$courseSalientFeatures = array();
		$features = $solrCourseDocument['course_salient_feature'];
		foreach($features as $feature){
			$featureList = array();
			$featureKeyValuePair = explode(":", $feature);
			$featureList['feature_name']  = $featureKeyValuePair[0];
			$featureList['value']  = $featureKeyValuePair[1];
			array_push($courseSalientFeatures, $featureList);
		}
		return $courseSalientFeatures;
	}
	
	public static function getCourseDocumentRequiredExams($solrCourseDocument = array()){
		$course_required = (array)$solrCourseDocument['course_eligibility_required'];
		$exams = array();
		foreach($course_required as $acronymName){
			$exam = array();
			$exam['acronym'] = $acronymName;
			array_push($exams, $exam);
		}
		return $exams;
	}
	
	public static function getCourseDocumentTestPrepExams($solrCourseDocument = array()){
		$course_testprep = (array)$solrCourseDocument['course_eligibility_testprep'];
		$exams = array();
		foreach($course_testprep as $acronymName){
			$exam = array();
			$exam['acronym'] = $acronymName;
			array_push($exams, $exam);
		}
		return $exams;
	}
	
		
	public static function getCourseDocumentLocation($solrCourseDocument = array()){
		$data = array();
		$data['institute_location_id'] 		= $solrCourseDocument['course_institute_location_id'];
		$data['locality_id'] 				= $solrCourseDocument['course_locality_id'];
		$data['locality_name'] 				= $solrCourseDocument['course_locality_name'];
        $data['customLocalityName']         = $solrCourseDocument['course_custom_locality_name'];
		$data['localityId'] 				= $solrCourseDocument['course_locality_id'];
		$data['localityName'] 				= $solrCourseDocument['course_locality_name'];
		$data['zone'] 						= $solrCourseDocument['course_zone_id'];
		$data['zone_id'] 					= $solrCourseDocument['course_zone_id'];
		$data['zoneId'] 					= $solrCourseDocument['course_zone_id'];
		$data['zoneName'] 					= $solrCourseDocument['course_zone_name'];
		$data['virtualCityId'] 				= $solrCourseDocument['course_virtual_city_id'];
		$data['city_id'] 					= $solrCourseDocument['course_city_id'];
		$data['cityId'] 					= $solrCourseDocument['course_city_id'];
		$data['city_name'] 					= $solrCourseDocument['course_city_name'];
		$data['state_id'] 					= $solrCourseDocument['course_state_id'];
		$data['stateId'] 					= $solrCourseDocument['course_state_id'];
		$data['state_name'] 				= $solrCourseDocument['course_state_name'];
		$data['country_id'] 				= $solrCourseDocument['course_country_id'];
		$data['countryId'] 					= $solrCourseDocument['course_country_id'];
		$data['name'] 						= $solrCourseDocument['course_country_name'];
		$data['urlName'] 					= $solrCourseDocument['course_country_name'];
		$data['course_location_attributes'] = $solrCourseDocument['course_location_attributes'];
		return $data;
	}
	
	public static function getCourseOtherLocations($solrCourseDocument = array()){
		$data = array();
		$tempOtherLocations = $solrCourseDocument['course_other_locations'];
		$locationString = html_entity_decode($tempOtherLocations);
		$otherLocations = json_decode($locationString, true);
		return $otherLocations;
	}
	
	public static function getCategoryIdValuePair($categories = array(), $allCategories = array()){
		$allCategoryList = json_decode(html_entity_decode($allCategories), true);
		if(!is_array($allCategoryList)){
			$allCategoryList = json_decode($allCategoryList, true);	
		}
		$categoryList = array();
		if(!empty($categories)){
			foreach($categories as $category){
				$catInfo = array();
				if(in_array($category, array_keys($allCategoryList))){
					$catInfo['name'] = $allCategoryList[$category];
				}
				$catInfo['boardId'] = $category;
				array_push($categoryList, $catInfo);
			}
		}
		return $categoryList;
	}
	
	public static function getCourseDocumentParentCategory($solrCourseDocument = array()){
		$courseParentCategories = $solrCourseDocument['course_parent_categories'];
		$allCategories = $solrCourseDocument['course_category_info'];
		$categories = explode(",", $courseParentCategories);
		$categoryList = SolrDataProcessor::getCategoryIdValuePair($categories, $allCategories);
		return $categoryList;
	}
	
	public static function getCourseChildCategory($solrCourseDocument = array()){
		$courseChildCategories = $solrCourseDocument['course_category_ids'];
		$allCategories = $solrCourseDocument['course_category_info'];
		$categories = explode(",", $courseChildCategories);
		$categoryList = SolrDataProcessor::getCategoryIdValuePair($categories, $allCategories);
		return $categoryList;
	}
	
	public static function getInstituteDocumentData($solrCourseDocument = array()){
		$data = array();
		$data['institute_id'] 	= $solrCourseDocument['institute_id'];
		$data['institute_name'] = $solrCourseDocument['institute_title'];
		$data['photo_count'] 	= $solrCourseDocument['institute_photo_count'];
		$data['video_count'] 	= $solrCourseDocument['institute_video_count'];
		$data['logo_link'] 		= $solrCourseDocument['institute_display_logo'];
		$data['pack_type'] 		= $solrCourseDocument['institute_pack_type'];
		$data['abbreviation'] 	= $solrCourseDocument['institute_abbreviation'];
		$data['aima_rating'] 	= $solrCourseDocument['institute_aima_rating'];
		$data['usp'] 			= $solrCourseDocument['institute_usp'];
		$data['alumni_rating'] 	= $solrCourseDocument['institute_alumni_rating'];
		$data['institute_type'] = $solrCourseDocument['institute_type'];
		$data['establish_year'] = $solrCourseDocument['institute_established_year'];
		$data['wiki_content'] 	= $solrCourseDocument['institute_wiki_content'];
		
		return $data;
	}
	
	public static function getInstituteHeaderImages($solrCourseDocument = array()){
		$data = array();
		$data['full_url'] 		= $solrCourseDocument['institute_display_logo'];
		$data['thumb_url'] 		= $solrCourseDocument['institute_display_logo'];
		$data['institute_id'] 	= $solrCourseDocument['institute_id'];
		return $data;
	}
	
	public static function getInstituteViewCountDetails($solrCourseDocument = array()){
		$data = array();
		$data['viewCount'] = $solrCourseDocument['institute_view_count'];
		return $data;
	}
	
	public static function getQuestionDocumentData($solrContentDocument = array()){
		$data = array();
		$data['id'] 						= $solrContentDocument['question_id'];
		$data['description'] 				= $solrContentDocument['question_description'];
		$data['title'] 						= $solrContentDocument['question_title'];
		$data['thread_id'] 					= $solrContentDocument['question_thread_id'];
		$data['unique_id'] 					= $solrContentDocument['unique_id'];
		$data['facetype'] 					= $solrContentDocument['facetype'];
		$data['created_time'] 				= $solrContentDocument['question_created_time'];
		$data['score'] 						= $solrContentDocument['score'];
		$data['user_id'] 					= $solrContentDocument['question_user_id'];
		$data['user_display_name'] 			= $solrContentDocument['question_user_displayname'];
		$data['answer_user_id'] 			= $solrContentDocument['answer_user_id'];
		$data['answer_user_display_name'] 	= $solrContentDocument['answer_user_display_name'];
		$data['answer_user_image_url'] 		= $solrContentDocument['answer_user_image_url'];
		$data['answer_title'] 				= $solrContentDocument['answer_title'];
		$data['answer_id'] 					= $solrContentDocument['answer_id'];
		$data['answer_created_time'] 		= $solrContentDocument['answer_created_time'];
		$data['question_answer_count'] 		= $solrContentDocument['question_answer_count'];
		$data['question_answers_count']     = $solrContentDocument['question_answers_count'];
		$data['question_view_count'] 		= $solrContentDocument['question_view_count'];
		$data['question_comment_count'] 	= $solrContentDocument['question_comment_count'];
		$data['question_institute_id'] 		= $solrContentDocument['question_institute_id'];
		$data['question_institute_title'] 	= $solrContentDocument['question_institute_title'];
	        $data['question_bestAnswerId'] 		= $solrContentDocument['question_bestAnswerId'];
		$data['question_inMasterList'] 	= $solrContentDocument['question_inMasterList'];
		
		return $data;
	}
	
	public static function getQuestionUserData($solrContentDocument = array()){
		$data = array();
		$data['id'] 			= $solrCourseDocument['question_user_id'];
		$data['displayName'] 	= $solrCourseDocument['question_user_displayname'];
		$data['avtarImageURL'] 	= $solrCourseDocument['question_user_image_url'];
		return $data;
	}
	
	public static function getQuestionCategory($solrContentDocument = array()){
		$categoryList = $solrContentDocument['question_category_ids'];
		$categories = explode(",", $categoryList);
		$categoryList = SolrDataProcessor::getCategoryIdValuePair($categories);
		return $categoryList;
	}
	
	public static function getArticleDocumentData($solrContentDocument = array()){
		$data = array();
		$data['id'] 				= $solrContentDocument['article_id'];
		$data['body'] 				= $solrContentDocument['article_body'];
		$data['title'] 				= $solrContentDocument['article_title'];
		$data['image_url'] 			= $solrContentDocument['article_image_url'];
		$data['summary'] 			= $solrContentDocument['article_summary'];
		$data['unique_id'] 			= $solrContentDocument['unique_id'];
		$data['facetype'] 			= $solrContentDocument['facetype'];
		$data['created_time'] 		= $solrContentDocument['article_created_time'];
		$data['score'] 				= $solrContentDocument['score'];
		$data['user_id'] 			= $solrContentDocument['article_user_id'];
		$data['user_display_name'] 	= $solrContentDocument['article_user_displayname'];
		$data['user_image_url'] 	= $solrContentDocument['article_user_image_url'];
		$data['country_id'] 		= $solrContentDocument['article_country_id'];
		$data['country_name'] 		= $solrContentDocument['article_country_name'];
		$data['url'] 				= $solrContentDocument['article_url'];
		$data['view_count'] 		= $solrContentDocument['article_view_count'];
		$data['comment_count'] 		= $solrContentDocument['article_comment_count'];
		return $data;
	}
	
	public static function getArticleCategory($solrContentDocument = array()){
		$categoryList = $solrContentDocument['article_category_ids'];
		$allCategories = $solrContentDocument['article_category_info'];
		$categories = explode(",", $categoryList);
		$categoryList = SolrDataProcessor::getCategoryIdValuePair($categories, $allCategories);
		return $categoryList;
	}
	
	public static function getDiscussionDocumentData($solrContentDocument = array()) {
		$data = array();
		$data['id'] 						= $solrContentDocument['discussion_id'];
		$data['description'] 				= $solrContentDocument['discussion_description'];
		$data['title'] 						= $solrContentDocument['discussion_title'];
		$data['thread_id'] 					= $solrContentDocument['discussion_thread_id'];
		$data['unique_id'] 					= $solrContentDocument['unique_id'];
		$data['facetype'] 					= $solrContentDocument['facetype'];
		$data['created_time'] 				= $solrContentDocument['discussion_created_time'];
		$data['score'] 						= $solrContentDocument['score'];
		$data['user_id'] 					= $solrContentDocument['discussion_creator_userid'];
		$data['user_display_name'] 			= $solrContentDocument['discussion_creator_displayname'];
		$data['user_image_url'] 			= $solrContentDocument['discussion_creator_image_url'];
		$data['selected_comment_index'] 	= $solrContentDocument['selected_comment_index'];
		$data['comments_json'] 				= $solrContentDocument['discussion_commments_json'];
		$data['discussion_comment_count'] 	= $solrContentDocument['discussion_comment_count'];
		$data['selected_comment_text'] 		= $solrContentDocument['selected_comment_text'];
		return $data;
	}

	public static function getTagDocumentData($solrContentDocument = array()){
		$data = array();
		$data['id']             = $solrContentDocument['tag_id'];
		$data['name']           = html_entity_decode($solrContentDocument['tag_name']);
		$data['type']           = $solrContentDocument['tag_entity'];
		$data['quality_factor'] = $solrContentDocument['tag_quality_factor'];
		$data['description']    = $solrContentDocument['tag_description'];
		return $data;
	}
	
}

?>
