<?php

class ShikshaBotModel extends MY_Model {
	
	private $CI;
	function __construct(){
		parent::__construct('AnA');
		$this->CI = &get_instance();
		
	}

	private function initiateModel($operation='read'){
		$appId = 1;	
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}

	public function fetchInstituteData($txt){
		$this->initiateModel('read');
		$txt = str_replace(array(",",'"'),"", $txt);
		$sql = "SELECT institute_name,institute_id from institute where institute_name like '".$txt."%' and status = 'live' limit 10";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
	}
				
}

?>
