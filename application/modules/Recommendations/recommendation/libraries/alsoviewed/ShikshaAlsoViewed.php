<?php

class ShikshaAlsoViewed
{
    private $CI;
    private $alsoViewedModel;
	
    function __construct()
    {
    	$this->CI = & get_instance();

    	$this->CI->load->model('alsoviewed_model');
    	$this->alsoViewedModel = new AlsoViewed_Model;
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

		$alsoViewedResults = $this->alsoViewedModel->getFilteredAlsoViewedCourses($courseIds);
		
		/**
		 * Compute also viewed results, grouped by institute
		 * If there are multiple courses of an institute,
		 * we will keep only one (one with highest weight)
		 */
		$finalResults = array();
		$numResults = intval($numResults) == 0 ? 10 : intval($numResults);

		foreach($alsoViewedResults as $alsoViewedResult) {

			$alsoViewedInstituteId = $alsoViewedResult['instituteId'];

			/**
			 * Remove if the institute id of recommended course
			 * is in provided exclusion list
			 */
			if(is_array($exclusionList) && in_array($alsoViewedInstituteId, $exclusionList)) {
				continue;
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