<?php
class tusereducation extends MY_Model
{
	function __construct(){ 
		parent::__construct('tusereducation');
	}

	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	public function updateExamDataInProfile(){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch('tUserEducation',$data);
	}
}
?>