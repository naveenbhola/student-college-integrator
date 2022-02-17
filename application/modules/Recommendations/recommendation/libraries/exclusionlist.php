<?php 

class ExclusionList
{
	private $_ci;
	
	function __construct()
	{
		$this->_ci = & get_instance();

		$db_handle = NULL;	
		
		$this->_ci->load->model('recommendation_model');
		$this->_ci->recommendation_model->init($db_handle);
	}
		
	/*
	 * Get exclusion list for a set of users
	 * from a set of exclusion sources
	 */
	function getExclusionList($users, $exclusion_sources = array())
	{
		$exclusion_list = array();	
        
		if(is_array($users) && count($users) && is_array($exclusion_sources) && count($exclusion_sources)) {
			foreach ($users as $user_id) {
				$exclusion_list[$user_id] = array();
			}
			
			foreach ($exclusion_sources as $exclusion_source) {
				/*
				 * Get exclusion list from this particular source
				 */
				$exclusion_list_from_source = $this->getExclusionListFromSource($users,$exclusion_source);
				
				foreach ($exclusion_list_from_source as $user_id => $user_exclusion_list) {
					$exclusion_list[$user_id] = array_merge($exclusion_list[$user_id],$user_exclusion_list);
				}
			}
		}
		
		return $exclusion_list;
	}
	
	function getExclusionListFromSource($users,$source)
	{
		$exclusion_list = array();
		
		switch($source) {
			case EXCLUSION_SOURCE_RECOMMENDATIONS:
			
				$exclusion_list = $this->getExclusionListFromRecommendations($users);
				break;		 
		}
		
		return $exclusion_list;
	}
	
	function getExclusionListFromRecommendations($users)
	{
		$exclusion_list = array();
		
		foreach ($users as $user_id) {
			$exclusion_list[$user_id] = array();
		}
		
		/*
		 * Get institutes from courses applied (from listing detail page)
		 */	
		$applied_courses = $this->_ci->recommendation_model->getAppliedCourses($users, RECO_NUM_DAYS);
    
		if(count($applied_courses)) {
			foreach ($applied_courses as $applied_course) {
				if($applied_course->institute_id) {
					$exclusion_list[$applied_course->userId][] = $applied_course->institute_id;
				}
			}
		}
		
		$recommendations_sent_last_mail = $this->_ci->recommendation_model->getRecommendationsSent($users, 19);
			
		if(is_array($recommendations_sent_last_mail) && count($recommendations_sent_last_mail)) {
			foreach ($recommendations_sent_last_mail as $recommendation) {
				if($recommendation->instituteID) {
					$exclusion_list[$recommendation->userID][] = $recommendation->instituteID;
				}
			}
		}
		
		return $exclusion_list;
	}
}
