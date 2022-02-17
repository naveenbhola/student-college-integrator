<?php
class statictagurlmapmodel extends MY_Model
{ 
	private $dbHandle = '';

	/**
	* Constructor Function 
	*/	
	function __construct(){
		parent::__construct('User');
	}

	
	/**
	* To Initiate the dbHandler instance
	*/
	private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
			$this->dbHandle = $this->getWriteHandle();
		}		
	}


	/**
	* Function to find the parent tags of Input Tags
	* @param array $tags
	*/
	public function findTagUrl($tags = array())
	{
		if(empty($tags)){
			return;
		}
		$this->initiateModel();
		$sql = "SELECT * FROM StaticTagUrlMap WHERE tagId IN (?)";
		$query = $this->dbHandle->query($sql, array($tags));
		$finalResult = $query->result_array();
		return $finalResult;
	}
}
