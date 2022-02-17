<?php
class scholarshipcategorypagemodel extends MY_Model {
    private $dbHandle = '';
   
    function __construct() {
        parent::__construct('ShikshaApply');
    }

    private function initiateModel($operation='read'){
        if($operation=='read'){ 
            $this->dbHandle = $this->getReadHandle();
        }else{
            $this->dbHandle = $this->getWriteHandle();
        }		
    }
    public function insertSearchScholarshipTrackingData($data){
        if(empty($data) || !$data['userId'] || !$data['MISTrackingId'] || !$data['courseLevel'] || !$data['catPageUrl'] || !$data['sourceApplication']){
            return false;
        }
        if(!$data['noOfRecords']){
            $data['noOfRecords'] = 0;
        }
        $this->initiateModel('write');
        $this->dbHandle->insert('studyAbroadSHPFindScholarshipTracking', $data);
    }
    /*
     * insert a single filter application in scholarshipCTPGFiltersTrackingBaseTable
     */ 
    public function trackFilterApplication($trackingData=array())
    {
        if(count($trackingData) == 0)
        {
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->trans_start();
        
        $this->dbHandle->insert('scholarshipCTPGFiltersTrackingBaseTable', $trackingData);
        $insertId = $this->dbHandle->insert_id();
        
        /*$this->dbHandle->trans_complete();
	    if ($this->dbHandle->trans_status() === FALSE) {
		    throw new Exception('Transaction Failed');
	    }*/ // we end this in function below
        return $insertId;
    }
    /*
     * insert each of filters applied at the time of filter application in scholarshipCTPGFilterDataTracking
     */ 
    public function trackFilterApplicationValues($trackingData=array())
    {
        if(count($trackingData) == 0)
        {   // this transaction.. is over
            $this->dbHandle->trans_complete();
            if ($this->dbHandle->trans_status() === FALSE) {
                throw new Exception('Transaction Failed');
            }
            return false;
        }
        $this->initiateModel('write');
        $this->dbHandle->insert_batch('scholarshipCTPGFilterDataTracking', $trackingData);
        
        $this->dbHandle->trans_complete();
	    if ($this->dbHandle->trans_status() === FALSE) {
		    throw new Exception('Transaction Failed');
	    }
        return true;
    }
}
?>