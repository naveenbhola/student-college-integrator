<?php 

class SimilarInstitutes
{
	private $_ci;
	
	private $_results = array();
	
	function __construct()
	{
		$this->_ci = & get_instance();

		$db_handle = NULL;

		$this->_ci->load->library('logger');
		
		$this->_ci->load->model('similarinstitutes_model');
		$this->_ci->similarinstitutes_model->init($db_handle,$this->_ci->logger);
	}
	
	/*
	 * @array - seed data in format: array(0=>course_id,1=>city_id)
	 * @array - institutes to be excluded from result
	 */
	function getSimilarInstitutes($seed_data,$num_results,$exclusion_list=array())
	{
            return array();
	}		
}
