<?php

class Recommendation_Lib
{
	private $_ci;

	function __construct()
	{
		$this->_ci = & get_instance();

		$db_handle = NULL;

		$this->_ci->load->model('recommendation/recommendation_model');
		$this->_ci->recommendation_model->init($db_handle);

		$this->_ci->load->library(array(
											'recommendation/alsoviewed',
											'recommendation/similar',
											'recommendation/logger'
									   )
								);
		$this->_ci->load->helper('recommendation/recommendation');
	}

	/*
	 * Register apply on a recommendation
	 * Apply can either be on institute (request e-brochure button) or course (apply from listing detail page)
	 * For course -> register in both recommendations_info & recommendations_courses_info
	 * For institute -> register only in recommendations_info
	 * Called from controllers/lms/lmsserver.php -> insertLead()
	 */

	function registerApplyOnRecommendation($user_id,$listing_id,$listing_type,$action)
	{
		if($user_id) {
            if($listing_type == 'course') {
				$institute_id = $this->_ci->recommendation_model->getInstituteFromCourse($listing_id,true);
				$this->_ci->recommendation_model->registerApplyOnRecommendation($institute_id,'institute',$user_id);
				$this->_ci->recommendation_model->registerApplyOnRecommendation($listing_id,'course',$user_id);
			}
            else if($listing_type == 'institute') {
				$this->_ci->recommendation_model->registerApplyOnRecommendation($listing_id,'institute',$user_id);
			}
		}
	}

	/*
	 * Get seed data for given users
	 * Seed data is the responses made by users in last RECO_NUM_DAYS
	 * Only high-intent responses are considered
	 * Also user can create responses across multiple hierarchies (stream-substream)
	 * (also called different implicit profiles from user's perspective)
	 * We pick the hierarchy (implicit profile) where last response was created e.g.
	 * User => U creates responses as
	 * Profile 1 (Stream1 - Substream1) => C1 (2016-10-12 12:10:12), C2 (2016-11-01 11:50:10)
	 * Profile 2 (Stream2 - Substream2) => C3 (2016-09-05 10:10:34), C4 (2016-09-27 07:40:10), C5 (2016-11-03 22:10:50)
	 *
	 * Profile 2 will be picked as last response was created in profile 2
	 * and seed courses will be (C3, C4, C5)
	 */
	function getSeedDataForUsers($users)
	{
		$seedData = array();
		foreach($users as $userId) {
			$this->_ci->load->builder('SearchBuilder','search');
			$searchServer = SearchBuilder::getSearchServer();
			
			$solrURL = SOLR_LDB_SEARCH_SELECT_URL_BASE;
			$request = "q=*%3A*&wt=phps";
			$request .= "&fq=userId:".intval($userId);
			
			/**
			 * Filter implicit profiles
			 */ 
			$request .= "&fq=ProfileType:implicit";
			
			/**
			 * Sort by when last high intent response was created in desc
			 */ 
			$request .= "&sort=highProfileResponseTime%20desc";
			
			/**
			 * Pick the top profile e.g. where last high intent response was created
			 */ 
			$request .= "&rows=1";
			
			$request .= "&fl=highProfileResponse,responseSubmitDate";
            
			$response = $searchServer->leadSearchCurl($solrURL, $request);
			$response = unserialize($response);
            
			$seedDataForUser = array();
		
			if($response['response']['numFound'] > 0) {
				foreach($response['response']['docs'] as $doc) {
					$responseCourseIds = $doc['highProfileResponse'];
					$responseTimes = $doc['responseSubmitDate'];
					for($i=0;$i<count($responseTimes);$i++) {
						
						/**
						 * Filter responses which are more than RECO_NUM_DAYS old
						 */ 
						if(rh_daysSince($responseTimes[$i]) <= RECO_NUM_DAYS) {
							if(!empty($responseCourseIds[$i])) {
								$seedDataForUser[] = $responseCourseIds[$i];
							}
						}
					}
				}
			}
			
			if(!empty($seedDataForUser)) {			
				$seedData[$userId] = $seedDataForUser;
			}
		}
	
		return $seedData;
	}

	function getAlsoViewedResults($seed_data, $num_results, $exclusion_list = array())
	{
		$recommendations = array();
		$alsoviewed_results = $this->_ci->alsoviewed->getAlsoViewedCourses($seed_data, $num_results, $exclusion_list);
		if(count($alsoviewed_results)) {
			foreach ($alsoviewed_results as $recommendation) {
				$recommendations[] = array(
								'algo' => 'also_viewed',
								'institute_id' => $recommendation['instituteId'],
								'course_id' => $recommendation['courseId'],
							);
			}
		}
		return $recommendations;
	}

	function getSimilarInstitutes($seed_data, $exclusion_list = array(), $num_results)
	{
		$recommendations = array();

		$similarinstitutes_results = $this->_ci->similar->getSimilarCourses($seed_data, $num_results, $exclusion_list);
		if(count($similarinstitutes_results)) {
			foreach($similarinstitutes_results as $recommendation) {
				$recommendations[] = array(
								'algo' => 'similar_institutes',
								'institute_id' => $recommendation['instituteId'],
								'course_id' => $recommendation['courseId']
							);

			}
		}

		return $recommendations;
	}

	function getProfileBasedResults($user_id, $exclusion_list, $num_results)
	{
		$this->_ci->load->library('recommendation/profilebased/ProfileGenerator');
		$this->_ci->load->library('recommendation/profilebased/ProfileBasedRecommendationGenerator');
		$this->_ci->load->library('recommendation/profilebased/ProfileBasedPipeline');
		
		$this->_ci->load->library('recommendation/profilebased/processors/ProfileBasedFull');
        $this->_ci->load->library('recommendation/profilebased/processors/ProfileBasedWithoutCourses');
        $this->_ci->load->library('recommendation/profilebased/processors/ProfileBasedWithoutCredentials');
        $this->_ci->load->library('recommendation/profilebased/processors/ProfileBasedWithoutSpecializations');

		$profileGenerator = new ProfileGenerator();
		$profiles = $profileGenerator->generateProfiles($user_id);
        
		$recommendationGenerator = new ProfileBasedRecommendationGenerator();

		$pipeline = new ProfileBasedPipeline($profiles, $num_results, $exclusion_list);
		
		$pipeline->addProcessor(new ProfileBasedFull($recommendationGenerator));
		$pipeline->addProcessor(new ProfileBasedWithoutCourses($recommendationGenerator));
		$pipeline->addProcessor(new ProfileBasedWithoutCredentials($recommendationGenerator));
		$pipeline->addProcessor(new ProfileBasedWithoutSpecializations($recommendationGenerator));
		
		$profileBasedResults = $pipeline->execute();
	
		$recommendations = array();

		if(count($profileBasedResults) > 0)
		{
			foreach ($profileBasedResults as $recommendation)
			{
				$recommendations[] = array(
											'algo' => 'profile_based',
											'institute_id' => $recommendation['instituteId'],
											'course_id' => $recommendation['courseId'],
										);
			}
		}
		
		return $recommendations;
	}
}
