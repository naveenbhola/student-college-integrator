<?php

/**
 * Description of crm_model
 * @author ashish mishra
 */
class crmmodel extends MY_Model {

	private $db = null;

	function __construct() {
		parent::__construct();
	}
	private function initiateModel($mode = "write", $module = '')
	{
		if($mode == 'read') {
			$this->db = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->db = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}
	}
	function getPastResponses($userid) {
		$this->initiateModel("read","User");
		$query = $this->db->query("SELECT l1.listing_title as institute, l2.listing_title as course FROM  
				`course_details` c, tempLMSTable,listings_main l1,listings_main l2 WHERE c.course_id = 
				tempLMSTable.listing_type_id AND tempLMSTable.listing_type =  'course' AND l1.status = 'live'
                                AND tempLMSTable.listing_subscription_type='paid'
				AND c.institute_id = l1.listing_type_id and l1.listing_type = 'institute' AND l2.status = 'live'
				AND c.course_id = l2.listing_type_id and l2.listing_type = 'course' 
				AND c.status =  'live' AND tempLMSTable.userid = ?",array($userid));
		$res = $query->result_array();
		$i = 0;
		$responses = array();
		foreach ($res as $row)
			$responses[] = array(
					"course" => $row["course"],
					"institute" => $row["institute"]
					);
		return $responses;
	}

	function getAllocatedUser($prefArr) {
		$stateid = $prefArr[1];
		$this->initiateModel("write","crm");
		if($stateid >0){
			$sql = "select userid from user_allocation where stateid = ".$this->db->escape($stateid);
		}
		else{
			$sql = "select userid from user_allocation where stateid = 0 ORDER BY RAND() LIMIT 1";
		}
		$query = $this->db->query($sql);
		$ret = "";
		foreach($query->result_array() as $res){
			$ret = $res["userid"];
		}
		return $ret;
	}

        function getLoginInfo($userIdArr) {
                $userId = $userIdArr[0];
                $this->initiateModel("read","User");
                $sql = "select activityTime from `tuserLoginTracking` where userid = ? order by activityTime desc limit 3";
                $query = $this->db->query($sql,array($userId));
                $ret = "";
                foreach($query->result_array() as $res){
                        $ret.= $res["activityTime"].",";
                }
		$ret = substr($ret,0,-1);
                return $ret;
        }

}

