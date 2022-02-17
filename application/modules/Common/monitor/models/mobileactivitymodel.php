<?php

class MobileActivityModel extends MY_Model
{
    private $dbHandle = '';
    private $dbHandleMode = '';
    
    function __construct(){
        parent::__construct('Consultant');
    }
    
    private function initiateModel($mode = "read"){
        if($this->dbHandle && $this->dbHandleMode == 'read')
            return;

        $this->dbHandleMode = $mode;
        $this->dbHandle = NULL;
        if($mode == 'read') {
                $this->dbHandle = $this->getReadHandle();
        } else {
                $this->dbHandle = $this->getWriteHandle();
        }
    }
    
     public function getDataForMobileActivityReport(){
	$this->initiateModel("read");
	
	//SELECT `boomr_pageid`,count(*),DATE_FORMAT(logged_at,'%d-%m-%Y') as date  FROM `mobile_activity_log` WHERE `sourceSite` = 'abroad' and logged_at > CURDATE() -INTERVAL+30 Day group by `boomr_pageid`,DATE_FORMAT(logged_at,'%d-%m-%Y') order by boomr_pageid,logged_at desc
	
	$this->dbHandle->select("boomr_pageid,count(1) as TotalViewCount,DATE_FORMAT(logged_at,'%d-%b') as date",false);
	//$this->dbHandle->select("avg(perceived_loadtime_page) as avgPageLoadTime",false);
	$this->dbHandle->select("avg(server_p_time) as avgServerProcessingTime ",false);
	//$this->dbHandle->select("avg(time_head_page_ready) as avgHeadLoadTime",false);
	$this->dbHandle->from('mobile_activity_log',false);
	$this->dbHandle->where('sourceSite','abroad');
	$startDate = date("Y-m-d 00:00:00",time()-60*60*24*15);
	$endDate   = date("Y-m-d 00:00:00");
	$this->dbHandle->where("logged_at > '".$startDate."'");
	$this->dbHandle->where("logged_at < '".$endDate."'");
	$this->dbHandle->group_by('boomr_pageid');
	$this->dbHandle->group_by("date",false);
	$this->dbHandle->order_by("logged_at ASC",false);
	$result = $this->dbHandle->get()->result_array();
	//echo $this->dbHandle->last_query();
	//die;
	return $result;
	
    }
    
    public function getCoverPageData($yesterday){
	$this->initiateModel("read");
	$this->dbHandle->select("count(1) as vCount");
	$this->dbHandle->select("sourceSite");
	$this->dbHandle->select("session_id");
	$this->dbHandle->from("mobile_activity_log");
	//$this->dbHandle->where("date(logged_at)",$yesterday);
	$startDate = $yesterday." 00:00:00";
	$endDate   = $yesterday." 23:59:59";
	$this->dbHandle->where("logged_at > '".$startDate."'");
	$this->dbHandle->where("logged_at < '".$endDate."'");
	$this->dbHandle->group_by("sourceSite, session_id");
	$data = $this->dbHandle->get()->result_array();
	//error_log($this->dbHandle->last_query());
	return $data;
    }
    
}
