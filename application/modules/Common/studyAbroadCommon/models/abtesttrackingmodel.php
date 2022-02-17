<?php
class abtesttrackingmodel extends MY_Model{
    private $ABTestTrackingTable = 'sa_abtest_tracking';
    private $ABTestCTAConversionTrackingTable = 'sa_abtest_cta_conversion_tracking';
    /**
	 * constructor method.
	*/
	function __construct()
	{ 
		parent::__construct('Listing');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

    public function insertABTestTracking($trackData){
        if(empty($trackData)){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->insert($this->ABTestTrackingTable,$trackData);
        $id = $this->dbHandle->insert_id();
        return $id;
    }
    public function insertABTestCTAConversionTracking($trackData)
    {
        if(empty($trackData)){
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->insert($this->ABTestCTAConversionTrackingTable,$trackData);
        $id = $this->dbHandle->insert_id();
        return $id;
    }
}