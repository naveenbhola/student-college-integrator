<?php
class RankingWidgetBackend_Model extends MY_Model {
    private $dbHandle = '';
    private $dbHandleMode = '';

	function __construct(){
		parent::__construct('AnA');
	}

    
    private function initiateModel($mode = "write"){
        if($this->dbHandle && $this->dbHandleMode == 'write')
            return;
        
        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        
        if($mode == 'read') {
            $this->dbHandle = $this->getReadHandle();
        } else {
            $this->dbHandle = $this->getWriteHandle();
        }
    }
    
    function rankingWidgetUpdateStatus(){
	$this->initiateModel('write');
	$sql = "UPDATE mobileRankingWidgetBackend SET status = 'history' where status='live' ";
        $this->dbHandle->query($sql);
	
    }
    
    function rankingWidgetMobileHomeModel($course,$title,$description,$link){
        $this->initiateModel('write');
        echo $insertData = array(
		'course_name' => $course,
		'course_title' => $title,
		'course_description'=>$description,
		'link'=>$link,
		'creation_date'=>date('Y-m-d H:i:s'),
		'status'=>'live'
	);
	$this->dbHandle->insert('mobileRankingWidgetBackend',$insertData);	
	return $this->dbHandle->insert_id();
    }
    
    function getDataForMobileRankingWidget(){
	$this->initiateModel('read');
	$sql ="select * from mobileRankingWidgetBackend where status='live'";
	$queryRes = $this->dbHandle->query($sql);
	return $queryRes->result_array();
    }
    
}
