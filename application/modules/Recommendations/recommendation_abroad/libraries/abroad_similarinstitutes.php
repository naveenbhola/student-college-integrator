<?php 

class Abroad_SimilarInstitutes
{
	private $_ci;
	
	private $_results = array();
	
	function __construct()
	{
		$this->_ci = & get_instance();
		$this->_ci->load->model('recommendation_abroad/abroad_similarinstitutes_model');
	}
	
	/*
	 * @array - seed data in format: array(0=>course_id,1=>city_id)
	 * @array - institutes to be excluded from result
	 */
	function getSimilarInstitutes($seed_data,$num_results,$exclusion_list=array())
	{
		$results = array();
		
		foreach($seed_data as $seed_data_item)
		{
			$similar_institutes = $this->_ci->abroad_similarinstitutes_model->getSimilarInstitutes($seed_data_item,$num_results,$exclusion_list);
			
			if(count($similar_institutes))
			{
				$results = array_merge($results,$similar_institutes);
			}
		}
		
		$final_results = array();
		
		if(count($results))
		{
			$this->_results = $results;
		
			$this->_applySorting();
			
			$final_results = $this->_results;	 
			
			if(count($final_results) > $num_results)
			{
				$final_results = array_slice($final_results,0,$num_results);
			}
		}
		
		return $final_results;
	}
		
	/*
	 * Sort by view count desc and apply limit
	 * Input format  -> Array of Objects: (institute_id,course_id)
	 * Output format -> Array: 0 => institute id, 1 => course_id 
	 */
	private function _applySorting()
	{
		$results = $this->_results;
		
		/*
		 * Remove duplicates
		 */
		$filtered_results = array();
		foreach($results as $result)
		{
			if(!array_key_exists($result->institute_id,$filtered_results))
			{
				$filtered_results[$result->institute_id] = $result;
			}
		}
	
		usort($filtered_results,array('SimilarInstitutes','sortResultsByViewCount'));
		
		/*
		 * Remove view count from sorted results
		 * and prepare standard return format
		 */
		
		$random_seed_course = $this->_ci->abroad_similarinstitutes_model->getRandomSeedCourse();
		
		$final_results = array();
		
		foreach($filtered_results as $result)
		{
			$final_results[] = array(
										$result->institute_id,
										$result->course_id,
										$random_seed_course
							);
		}
		
		$this->_results = $final_results;
			
		return $this;
	}
	
	public static function sortResultsByViewCount($result1,$result2)
	{
		return intval($result2->view_count) - intval($result1->view_count);
	}
}
