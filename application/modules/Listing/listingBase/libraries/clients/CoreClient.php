<?php

class CoreClient {
	private $CI;
	private $model;


	public function manageDataLayer($tableName,$id = NULL){
		$this->CI = & get_instance();
		$this->model = $this->CI->load->model('core/coremodel');
		$data = $this->model->findAll($tableName,$id);
	
		$dataWithKey = array();
		foreach ($data as $key => $value) {
			$dataWithKey[$value[substr($tableName,0,-1)."_id"]] = $value;
		}
		
		return $dataWithKey;
	}
}