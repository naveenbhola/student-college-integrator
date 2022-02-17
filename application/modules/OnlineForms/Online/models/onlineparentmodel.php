<?php
/* 
    Model for database related operations related to Online forms.
*/

class OnlineParentModel extends MY_Model {
	protected $dbHandle = '';
	function __construct(){
		parent::__construct('OnlineForms');
	}

	protected function initiateModel($operation='read'){
		$appId = 1;	
		$this->load->library('OnlineFormConfig');
		$onlineConfig = new OnlineFormConfig();
		//$dbConfig = array( 'hostname'=>'localhost');
		//$onlineConfig->getDbConfig($appID,$dbConfig);
		//$this->dbHandle = $this->load->database($dbConfig,TRUE);

		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	        $this->dbHandle = $this->getWriteHandle();
		}		
	}
}
