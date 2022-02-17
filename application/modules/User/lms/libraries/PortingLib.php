<?php

class PortingLib {

	private $CI;

	function __construct(){
		$this->CI = & get_instance();
		$this->portingModel = $this->CI->load->model('lms/portingmodel');
		$this->CI->load->config("lms/portingConfig");
	}


	public function checkValidEntityIds($mappings,$clientEntityIds){
		foreach ($mappings as $entityId => $entityValue) {
			if (empty($clientEntityIds[$entityId])){
				$errorLog .= $entityId. " , ";
			}
		}
		return $errorLog;
	}

	public function checkValidFirstRow($input){
		if (empty($input)){
			return array("error"=>"First row cant be empty");
		}
		$validInput = array(
							'client_id' => 'client_id',
							'entity_id' => 'entity_id',
							'entity_type' =>'entity_type',
							'entity_value' =>'entity_value'
						);

		foreach ($input as $key => $value) {
			if (empty($validInput[trim($value)])){
				$error .= $value.', ';
			}
		}
		if (!empty($error)){
			return array("error"=>"Error in column name ".$error);
		}
	}

}
?>