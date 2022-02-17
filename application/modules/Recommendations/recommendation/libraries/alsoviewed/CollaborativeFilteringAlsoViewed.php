<?php 

class CollaborativeFilteringAlsoViewed
{
	private $CI;
	private $cfModel;
	
	function __construct()
	{		
		$this->CI = & get_instance();
		
		$this->CI->load->model('recommendation/collaborativefiltering_model');
		$this->cfModel = new CollaborativeFiltering_Model;
	}
	
	/*
	 * @array - seed course ids
	 * @int - number of institutes in the result
	 * @array - institutes to be excluded from result
	 */
	function getAlsoViewedCourses($courseIds, $numResults = 10, $exclusionList = array())
	{
		if(!is_array($courseIds) || count($courseIds) == 0) {
			return array();
		}
		
		/**
		 * Get collaborative filtered also viewed results
		 * Results are sorted by weight
		 */ 
		$alsoViewedResults = $this->cfModel->getCollaborativeFilteredCourses($courseIds, $exclusionList);
		
		/**
		 * Group by institute
		 * If there are multiple courses of an institute,
		 * we will keep one with highest weight
		 */ 
		$finalResults = array();
		$numResults = intval($numResults) == 0 ? 10 : intval($numResults);
		
		foreach($alsoViewedResults as $alsoViewedResult) {
	
			$alsoViewedInstituteId = $alsoViewedResult['instituteId'];
			
			if($_REQUEST['disableRecoLimit'] == 1) {
				if(is_array($exclusionList) && in_array($alsoViewedInstituteId, $exclusionList)) { 
	                    continue; 
	            } 
			}

			/**
			 * If we have already seen this institute in also viewed results
			 * then skip it
			 */						
			if($finalResults[$alsoViewedInstituteId]) {
				continue;
			}
			
			$finalResults[$alsoViewedInstituteId] = $alsoViewedResult;	
			
			if(count($finalResults) == $numResults) {
				break;
			}
		}
		
		return array_values($finalResults);
	}
}