<?php
/* 
    Model for database related operations related to Online forms.
*/

class OnlineTestParentModel extends CI_Model {
	protected $dbHandle = '';
	function __construct(){
		parent::__construct();
	}

	protected function initiateModel(){
		$appId = 1;	
		$this->load->library('OnlineTestConfig');
		$onlineConfig = new OnlineTestConfig();
		$dbConfig = array( 'hostname'=>'localhost');
		$onlineConfig->getDbConfig($appID,$dbConfig);
		$this->dbHandle = $this->load->database($dbConfig,TRUE);
	}
}