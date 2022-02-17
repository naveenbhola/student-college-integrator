<?php 

class Abroad_Recommendation_Lib
{
	private $_ci;
	
	function __construct()
	{
		$this->_ci = & get_instance();

		//$this->_ci->load->database();
		//$db_handle = $this->_ci->url_manager->CheckDB($this->_ci->db,'yes','yes');
		$db_handle = NULL;
		
		$this->_ci->load->library('recommendation_abroad/abroad_logger');
		
		$this->_ci->load->model('recommendation_abroad/abroad_recommendation_model');
		$this->_ci->abroad_recommendation_model->init($db_handle);
		
		$this->_ci->load->library(array(
											'recommendation_abroad/abroad_alsoviewed',
											'recommendation_abroad/abroad_similarinstitutes'
									   )
								);
		$this->_ci->load->helper('recommendation_abroad/abroad_recommendation');
	}
		
	/*
	 * Get category-wise seed data for given users 
	 */
	function getSeedDataForUsers($users)
	{
		$seed_data = array();
		
		$course_city_mapping = array();
		$course_country_mapping = array();
		$institute_country_mapping = array();
		$institute_course_mapping = array();
		$course_level_mapping = array();
		
		$courses_in_seed_data = array();
		$institutes_in_seed_data = array();
		
		foreach ($users as $user_id)
		{
			$seed_data[$user_id] = array();
			$seed_data[$user_id]['applied_courses'] = array();
		}
		
		$num_days = RECO_NUM_DAYS;
		global $highPriorityActions;
	
		/*
		 * S1. Applied courses
		 */
		$applied_courses = $this->_ci->abroad_recommendation_model->getAppliedCourses($users,$num_days);
		
		foreach ($applied_courses as $row)
		{
			$user_id = $row->userId;
			$course_id = $row->course_id;
			$institute_id = $row->institute_id;
			$country_id = $row->country_id;
			$city_id = $row->city_id;
			$course_level = $row->course_level;
			$action = empty($row->action) ? '' : $row->action;
			
			$seed_data[$user_id]['applied_courses'][] = $course_id;
			
			$institute_course_mapping[$institute_id] = $course_id;
			
			$course_city_mapping[$course_id] = $city_id;
			$course_country_mapping[$course_id] = $country_id;
			$institute_country_mapping[$institute_id] = $country_id;
			$course_level_mapping[$course_id] = $course_level;
			$course_action_priority_mapping[$course_id] = in_array($action, $highPriorityActions) ? true : false;
			
			$courses_in_seed_data[] = $course_id;
			$institutes_in_seed_data[] = $institute_id;
		}
				
		/*
		 * Now retrieve categories corresponding to courses found in seed data
		 */
		$course_category_mapping = array();
	
		/*
		 * First look into course mapping
		 */
		
		$mapped_courses = array();
		
		if(count($courses_in_seed_data))
		{ 
			$course_categories = $this->_ci->abroad_recommendation_model->getCategoriesFromCourseMapping($courses_in_seed_data);
		
			foreach($course_categories as $row)
			{
				if($row->category_id)
				{
					$course_category_mapping[$row->course_id][] = $row->category_id;
					$mapped_courses[] = $row->course_id;
				}
			}
		}
		
		/*
		 * If not found in course mapping,
		 * Look in listings categories
		 */
		
		$remaining_unmapped_courses = array_diff($courses_in_seed_data,$mapped_courses);
		
		if(count($remaining_unmapped_courses))
		{
			$course_categories = $this->_ci->abroad_recommendation_model->getCategoriesFromListings($remaining_unmapped_courses);
			
			foreach($course_categories as $row)
			{
				if($row->parentId)
				{
					$course_category_mapping[$row->course_id][] = $row->parentId;
				}
			}
		}
		
		/*
		 * Retrieve sub categories corresponding to courses found in seed data
		 */
		$course_subCategory_mapping = $this->_ci->abroad_recommendation_model->getSubCategoriesForCourses($courses_in_seed_data);
		
		/*
		 * Construct final seed data format
		 */
		
		$final_seed_data = array();
		
		foreach ($seed_data as $user_id => $user_seed_data)
		{
			$user_seed_data_applied_courses = array_unique($user_seed_data['applied_courses']);
			
			foreach($user_seed_data_applied_courses as $course_id)
			{
				$course_categories = $course_category_mapping[$course_id];
				foreach ($course_categories as $course_category)
				{
					$final_seed_data[$user_id][$course_category]['applied_courses'][] = array(
															'course_id' => $course_id,
															'city_id' => $course_city_mapping[$course_id],
															'country_id' => $course_country_mapping[$course_id],
															'course_level' => $course_level_mapping[$course_id],
															'sub_category' => $course_subCategory_mapping[$course_id],
															'isHighPriority' => $course_action_priority_mapping[$course_id]
														);
				}
			}
		}
		return $final_seed_data;
	}
	
	function getAlsoViewedResults($category_seed_data,$num_results,$exclusion_list = array())
	{
		/*
		 * Prepare seed data for "Also Viewed" algo
		 */
		$institutes = array();
		$courses = array();
		
		$courseIds['highPriority'] = array();
		$courseIds['lowPriority'] = array();
				
		if(isset($category_seed_data['applied_courses']) && is_array($category_seed_data['applied_courses']))
		{
			$courses = $category_seed_data['applied_courses'];
		}
		
		foreach($courses as $course) {
			if($course['isHighPriority']) {
				$courseIds['highPriority'][] = $course['course_id'];
			}
			else {
				$courseIds['lowPriority'][] = $course['course_id'];
			}
		}
		
		$highPriorityCourseIds = $courseIds['highPriority'];
		$lowPriorityCourseIds = $courseIds['lowPriority'];
		
		$recommendations = array();
		
		if(count($highPriorityCourseIds))
		{
			$alsoviewed_results = $this->_ci->abroad_alsoviewed->getAlsoViewedListings($highPriorityCourseIds,$num_results,$exclusion_list,$num_results);
			
			if(count($alsoviewed_results))
			{
				foreach ($alsoviewed_results as $recommendation)
				{
					$recommendations[] = array(
									'algo' => 'also_viewed',
									'institute_id' => $recommendation[0],
									'course_id' => $recommendation[1],
								);
					
					$exclusion_list[] = $recommendation[0];
				}
			}
		}
		
		if((count($recommendations) < $num_results) && count($lowPriorityCourseIds)) {
			$num_results -= count($recommendations);
			$alsoviewed_results = $this->_ci->abroad_alsoviewed->getAlsoViewedListings($lowPriorityCourseIds,$num_results,$exclusion_list,$num_results);
			if(count($alsoviewed_results))
			{
				foreach ($alsoviewed_results as $recommendation)
				{
					if(!in_array($recommendation[0], $exclusion_list)) {
						$recommendations[] = array(
										'algo' => 'also_viewed',
										'institute_id' => $recommendation[0],
										'course_id' => $recommendation[1],
									);
					}
				}
			}
		}
		return $recommendations;	
	}
	
	function getSimilarInstitutes($category_seed_data,$exclusion_list = array(),$num_results)
	{
		/*
		 * Prepare seed data for "Similar Institutes" algo
		 */
		$courses = array();
		$courseData['highPriority'] = array();
		$courseData['lowPriority'] = array();
		
		if(isset($category_seed_data['applied_courses']) && is_array($category_seed_data['applied_courses']))
		{
			$courses = $category_seed_data['applied_courses'];
		}
		
		foreach($courses as $course) {
			if($course['isHighPriority']) {
				$courseData['highPriority'][] = $course;
			}
			else {
				$courseData['lowPriority'][] = $course;
			}
		}
		
		$recommendations = array();
		
		if(count($courseData['highPriority']))
		{
			$similarinstitutes_results = $this->_ci->abroad_similarinstitutes->getSimilarInstitutes($courseData['highPriority'],$num_results,$exclusion_list);
	
			if(count($similarinstitutes_results))
			{
				foreach ($similarinstitutes_results as $recommendation)
				{
					$recommendations[] = array(
									'algo' => 'similar_institutes',
									'institute_id' => $recommendation[0],
									'course_id' => $recommendation[1],
									'random_si_seed_course' => $recommendation[2]
								);
					
					$exclusion_list[] = $recommendation[0];
				}
			}
		}
		
		if((count($recommendations) < $num_results) && count($courseData['lowPriority'])) {
			$num_results -= count($recommendations);
			$similarinstitutes_results = $this->_ci->abroad_similarinstitutes->getSimilarInstitutes($courseData['lowPriority'],$num_results,$exclusion_list);
			if(count($similarinstitutes_results))
			{
				foreach ($similarinstitutes_results as $recommendation)
				{
					if(!in_array($recommendation[0], $exclusion_list)) {
						$recommendations[] = array(
										'algo' => 'similar_institutes',
										'institute_id' => $recommendation[0],
										'course_id' => $recommendation[1],
										'random_si_seed_course' => $recommendation[2]
									);
					}
				}
			}
		}
		
		return $recommendations;	
	}
}