<?php 
class examupdatepopupmodel extends MY_Model{
    private $examScoreUpdationTrackingTable = 'examScoreUpdationPopupTracking';
	function __construct()
	{ 
		parent::__construct('CategoryList');
	}
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	public function getPopupShownInfo($userIds = array()){
        if(empty($userIds)){
        	return false;
        }
        $this->initiateModel();
        $this->dbHandle->select('userId,shownAt');
        $this->dbHandle->from($this->examScoreUpdationTrackingTable);
        $this->dbHandle->where('isLatest',1);
        $this->dbHandle->where_in('userId',$userIds);
        $result = $this->dbHandle->get()->result_array();
        $finalRes = array();
        foreach ($result as $value) {
        	$finalRes[$value['userId']] = $value['shownAt'];
        }
        return $finalRes;
    }

    public function addPopupTracking($tData){
    	if(empty($tData)){
        	return false;
        }
        $this->initiateModel('write');
        //set all isLatest = 0 for this user
        $this->dbHandle->update($this->examScoreUpdationTrackingTable, array('isLatest' => 0), array('userId' => $tData['userId'], 'isLatest' => 1));
        //insert new entry
        $this->dbHandle->insert($this->examScoreUpdationTrackingTable, $tData); 
    }
    public function getTrackingKeyForExamScoreUpdate($pageIdentifier, $isMobile)
    {
        if(is_null($pageIdentifier))
        {
            return NULL;
        }
        $this->initiateModel('read');
        $this->dbHandle->select('id');
        $this->dbHandle->from('tracking_pagekey');
        $this->dbHandle->where('page',$pageIdentifier);
        $this->dbHandle->where('conversionType','examScoreUpdate');
        if($isMobile && $pageIdentifier !="schedulePickupPage"){ // we show same page on mobile
            $this->dbHandle->where('siteSource','Mobile');
        }else{
            $this->dbHandle->where('siteSource','Desktop');
        }
        $res = $this->dbHandle->get()->result_array();
        return $res[0]['id'];
    }
}
?>