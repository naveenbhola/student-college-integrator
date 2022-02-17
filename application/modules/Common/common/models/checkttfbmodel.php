<?php 
class checkttfbmodel extends MY_Model {
	
	function __construct() {
		parent::__construct('default');
	}

	private function initiateModel($mode = "write", $module = ''){
		if($mode == 'read') {
		    $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
		    $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}

	public function storeData($pageName, $site, $URL, $device, $totalTime, $nameLookUp, $connectTime, $startTransfer){
		$dataUpdated = false;		
		$this->initiateModel('write');
		$data                  		= array();
		$data['pageName']        	= $pageName;
		$data['site']        	= $site;
		$data['URL']        	= $URL;
		$data['device']        	= $device;
		$data['totalTime']        	= $totalTime * 1000;
		$data['nameLookUp']        	= $nameLookUp * 1000;
		$data['connectTime']        	= $connectTime * 1000;
		$data['startTransfer']        	= $startTransfer * 1000;
		$this->dbHandle->insert('checkPagePerformance',$data);		
	}

}
?>