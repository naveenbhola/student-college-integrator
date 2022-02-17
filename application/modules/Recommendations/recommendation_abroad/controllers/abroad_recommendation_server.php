<?php 

class Abroad_Recommendation_Server extends MX_Controller
{	
	private $_recommendation_log = array();
	private $logger;

	/*
	 * Users for whom no recommendations or less than required are generated
	 */
	private $_recommendation_defaulters = array();

	function init()
	{
		$this->load->library(array(
										'recommendation_abroad/abroad_recommendation_lib',
										'recommendation_abroad/abroad_exclusionlist',
										'recommendation_abroad/abroad_logger',
										'xmlrpc',
										'xmlrpcs',
										'listing/NationalCourseLib'
								   )
							);

		$this->logger = $this->abroad_logger;					
							
		$this->load->helper('recommendation_abroad/abroad_recommendation');

		$this->load->model('user/userinfomodel');

		$this->load->model('recommendation_abroad/abroad_recommendation_model');
		$this->abroad_recommendation_model->init(NULL);
		
		$this->load->model('recommendation_abroad/abroad_profilebased_model');
		$this->abroad_profilebased_model->init(NULL,$this->logger);

		
		
		//registerShutdown('recommendation');
		
		set_time_limit (0);
		//ini_set('memory_limit','2048M');
		ini_set('display_errors','Off');
	}
	
	function index()
	{
		$this->init();

		$config['functions']['getRecommendations'] = array('function' => 'Abroad_Recommendation_Server.getRecommendations');
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);
	}

	/** 
     * Get recommendations for a set of users  
     */
	function getRecommendations($request)
	{
		$parameters = $request->output_parameters();
		$users = $parameters[0];
		$disableExclusionList = $parameters[1];
		$numResultsPerUser = $parameters[2];
		
		$this->logger->logToFile("Recommendations started with ".count($users)." users",2);
	
		if(count($users))
		{
			$user_profile_info = $this->abroad_profilebased_model->getUserProfileInfo($users);
			$seed_data = $this->abroad_recommendation_lib->getSeedDataForUsers($users);
			
			if($disableExclusionList == 'yes') {
				foreach($users as $userId) {
					$exclusion_list[$userId] = array();
				}
			}
			else {
				$exclusion_list = $this->abroad_exclusionlist->getExclusionList($users,array(EXCLUSION_SOURCE_RECOMMENDATIONS));
			}
			
			$last_response = $this->userinfomodel->getDesiredCourseFromLastResponse($users);
			$users_last_applied_category = array();
			foreach($last_response as $user_id => $last_response_data) {
				$users_last_applied_category[$user_id] = $last_response_data['categoryId'];
			}
		
			$recommendations = array();
			
			foreach($users as $user)
			{
				$recommendations_for_user = $this->getRecommendationsForUser($user,$user_profile_info[$user],$seed_data[$user],$exclusion_list[$user],$users_last_applied_category[$user], $numResultsPerUser);
				
				if(is_array($recommendations_for_user) && count($recommendations_for_user))
				{
					$recommendations[$user] = $recommendations_for_user;
				}	
			}	

			$this->logger->logToFile("Recommendations successfully retrieved for ".count($recommendations)." users");
		
			$recommendation_data = array();
			
			if(count($recommendations))
			{
				$listing_details = $this->abroad_recommendation_model->getListingDetails($recommendations);
				
				$coursesInReco = array();
				foreach($listing_details as $instituteId => $instituteDetail) {
					if($instituteDetail['course_id'] >0) {
						$coursesInReco[] = $instituteDetail['course_id'];
					}
				}
				
				$brochureURL = $this->nationalcourselib->getMultipleCoursesBrochure($coursesInReco);
				
				foreach($listing_details as $instituteId => $instituteDetail) {
					$courseId = $instituteDetail['course_id'];
					$listing_details[$instituteId]['hasBrochure'] = $brochureURL[$courseId] == '' ? false : true;
				}
				
				$category_details = $this->abroad_recommendation_model->getCategoryDetails($recommendations);
				$random_si_seed_institutes = $this->abroad_recommendation_model->getRandomSISeedInstitutes($recommendations);
				
				$recommendation_data = rh_abroad_buildRecommendationData($recommendations,$listing_details,$category_details,$user_profile_info,$random_si_seed_institutes);
			}	
			
			$response_data = array(
									'recommendations' => $recommendation_data,
									'defaulters' => $this->_recommendation_defaulters,
									'log' => $this->_recommendation_log
								);
			
			$response_string = base64_encode(gzcompress(json_encode($response_data)));
		
			$response = array($response_string,'string');
			return $this->xmlrpc->send_response($response);
		}
		else 
		{
			return $this->xmlrpc->send_error_message('123', 'Users not available');
		}
	}
	
	/** 
	* Get recommendations for a particular user 
	*/
	function getRecommendationsForUser($user_id,$user_profile_info,$seed_data,$exclusion_list,$last_applied_category,$numResultsPerUser)
	{
		$this->logger->reset();
		$this->logger->log('User Info',$user_profile_info,'userinfo');
		$this->logger->log("Exclusion List",$exclusion_list,'array');
		$this->logger->log("No. of Seed data categories",count($seed_data));
		$this->logger->log("Algo Scheme",$algo_scheme,'array');
		
		if($last_applied_category && isset($seed_data[$last_applied_category]))
		{
			$user_seed_data = array();
			$user_seed_data[$last_applied_category] = $seed_data[$last_applied_category];
			$seed_data = $user_seed_data;
			
			$this->logger->log("User last applied category",$last_applied_category);
		}
		
		$recommendations = array();
		$profilebased_done = false;
		
		/**
		 * No. of recommendations to be generated for each user
		 */ 
		$numResultsPerUser = intval($numResultsPerUser);
		if(!$numResultsPerUser) {
			$numResultsPerUser = RECO_NUM_RESULTS;
		}
		
		/*
		 * Run recommendation algos for each category in seed data 
		 */
		if(is_array($seed_data) && count($seed_data) > 0)
		{
			foreach($seed_data as $category => $category_seed_data)
			{
				$this->logger->log('CATEGORY # '.$category,$category_seed_data,'seed_data');
				
				$country_for_category = rh_abroad_getCountryForCategory($category_seed_data);
				$category_seed_data = rh_abroad_filterCategorySeedDataByCountry($category_seed_data,$country_for_category);
				
				$category_recommendations = array();
				$num_results = $numResultsPerUser;
				
				/*
				 * Also Viewed
				 */
				
				$also_viewed_results = $this->abroad_recommendation_lib->getAlsoViewedResults($category_seed_data,$num_results,$exclusion_list);
				
				if(is_array($also_viewed_results) && count($also_viewed_results))
				{
					$category_recommendations = $also_viewed_results;
					
					foreach($also_viewed_results as $also_viewed_result)
					{
						$exclusion_list[] = (int) $also_viewed_result['institute_id'];
					}
				}
				
				/*
				 * Similar Institutes
				 */
				if(count($category_recommendations) < $numResultsPerUser)
				{
					$num_results = $numResultsPerUser - count($category_recommendations);
					$similar_institutes = $this->abroad_recommendation_lib->getSimilarInstitutes($category_seed_data,$exclusion_list,$num_results);
				
					if(is_array($similar_institutes) && count($similar_institutes))
					{
						$category_recommendations = array_merge($category_recommendations,$similar_institutes);
						
						foreach($similar_institutes as $similar_institute)
						{
							$exclusion_list[] = (int) $similar_institute['institute_id'];
						}
					}
				}
				
				/*
				 * There must be at least RECO_MIN_REQUIRED_RESULTS recommendations for each category
				 */
				if(count($category_recommendations) >= RECO_MIN_REQUIRED_RESULTS)
				{
					$recommendations[$category] = array(
															'recommendations' => $category_recommendations,
															'country' => $country_for_category
														);
				}
			
				$this->logger->addSeparatorRow('medium');
			}
		}
		
		$recommendation_log = base64_encode(gzcompress($this->logger->getLog()));
		$this->_recommendation_log[$user_id] = $recommendation_log;
		
		/*
		 * Select top categories for recommendation
		 * after sorting by number of recommendations in each category
		 */
		if(count($recommendations))
		{		
			uasort($recommendations,array('Abroad_Recommendation_Server','sortRecommendationByNumberOfResults'));

			if(count($recommendations) > RECO_MAX_CATEGORIES)
			{
				$recommendations = array_slice($recommendations,0,RECO_MAX_CATEGORIES,true);
			}
			
			/*
			 * Remove country from recommendations
			 * It was just used for sorting
			 */
			$recommendations_sans_country = array();
			
			foreach ($recommendations as $category_id => $recommendation)
			{
				$recommendations_sans_country[$category_id] = $recommendation['recommendations'];
			}
			return $recommendations_sans_country;
		}
		else
		{
			$this->_recommendation_defaulters[] = $user_id;
		}
	}
	
	public static function sortRecommendationByNumberOfResults($r1,$r2)
	{
		$r1_country = $r1['country'];	
		$r1_recommendations = $r1['recommendations'];
		
		$r2_country = $r2['country'];	
		$r2_recommendations = $r2['recommendations'];
	
		if($r1_country == '2')
		{
			if($r2_country == '2')
			{
				return count($r2_recommendations) - count($r1_recommendations);
			}
			else 
			{
				return -1;
			}
		}
		else if($r2_country == '2')
		{
			return 1;
		}
		else 
		{
			return count($r2_recommendations) - count($r1_recommendations);	
		}
	}
	
}
