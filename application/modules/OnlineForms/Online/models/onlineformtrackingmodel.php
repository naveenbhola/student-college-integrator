<?php

class OnlineFormTrackingModel extends MY_Model
{
    /**
     * @var Object DB Handle
     */ 
    private $dbHandle;
    
    /**
     * Constructor
     */ 
    function __construct()
    {
        parent::__construct('OnlineForms');
    }
    
    /**
     * Initiate the model
     *
     * @param string $operation
     */ 
    private function initiateModel($operation = 'read')
    {
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}
		else{
        	$this->dbHandle = $this->getWriteHandle();
		}
	}
	
	public function getExistingCourseIds($courseIds, $excludePixel)
	{
		$this->initiateModel('read');
		$sql = "SELECT course_id FROM OF_ExternalForms WHERE course_id IN (?) AND status = 'live' ";
		if($excludePixel) {
			$sql .= " AND pixel_id != '".intval($excludePixel)."'";
		}
		$query = $this->dbHandle->query($sql,array($courseIds));
		return $this->getColumnArray($query->result_array(), 'course_id');
	}
    
    public function saveForm($courseIds,$OFURL,$pixelId)
    {
        $this->initiateModel('write');
		
		if($pixelId) {
			$sql = "UPDATE OF_ExternalForms SET status = 'history' WHERE pixel_id = ?";
			$this->dbHandle->query($sql,array($pixelId));
		}
		else {
			$pixelId = rand(100000000,999999999);
		}
		
		foreach($courseIds as $courseId) {
			$formData = array(
								'course_id' => $courseId,
								'url' => $OFURL,
								'date' => date('Y-m-d H:i:s'),
								'pixel_id' => $pixelId
							);
			$this->dbHandle->insert('OF_ExternalForms',$formData);
		}
    }
    
    public function getForms($numResults,$currentPage)
    {
        $this->initiateModel();
		
		$offset = "";
		if($currentPage > 1) {
			$offset = " OFFSET ".($currentPage-1) * $numResults;
		}
		
		$sql = "SELECT pixel_id, url, GROUP_CONCAT(course_id) as courses FROM OF_ExternalForms WHERE status = 'live' GROUP BY pixel_id ORDER BY date DESC LIMIT ".$numResults.$offset;
		
        $query = $this->dbHandle->query($sql);
        return $query->result_array();
    }
	
	public function getFormCount()
    {
        $this->initiateModel();
		
		$sql = "SELECT count(distinct pixel_id) as cnt FROM OF_ExternalForms WHERE status = 'live'";
        $query = $this->dbHandle->query($sql);
        $row = $query->row_array();
		return $row['cnt'];
    }
	
	public function getFormByPixel($pixelId)
    {
        $this->initiateModel();
		
		$sql = "SELECT pixel_id, url, GROUP_CONCAT(course_id) as courses FROM OF_ExternalForms WHERE pixel_id = ? AND status = 'live' GROUP BY pixel_id";
        $query = $this->dbHandle->query($sql, array($pixelId));
		$row = $query->row_array();
		return $row;
	}
	
	public function getFormByCourse($courseId)
    {
        $this->initiateModel();
        $this->dbHandle->order_by("date", "desc");
		$this->dbHandle->where("course_id", $courseId);
		$this->dbHandle->where("status", 'live');
        $query = $this->dbHandle->get('OF_ExternalForms');
		
		$row = $query->row_array();
		
		if($row['id']) {
			return $row;
		}
		
		/**
		 * Check if it's other course
		 */
		$sql =  "SELECT a.id, a.course_id, a.url, a.pixel_id, b.otherCourses ".
				"FROM OF_ExternalForms a ".
				"INNER JOIN OF_InstituteDetails b ON b.courseId = a.course_id ".
				"WHERE b.status = 'live' AND a.status = 'live'";
				
		$query = $this->dbHandle->query($sql, array($courseId));
		$results = $query->result_array();
		
		foreach($results as $row) {
			if($row['otherCourses']) {
				$otherCourses = explode(',',$row['otherCourses']);
				foreach($otherCourses as $otherCourse) {
					if(trim($otherCourse) == $courseId) {
						return $row; 
					}
				}
			}
		}
    }
    
	public function trackByPixel($pixelId,$userId,$courseId,$action)
	{
		$this->initiateModel();
		
		if($action == 'landing') {
			$action = 'landed';
		}
		if($action == 'conversion') {
			$action = 'submitted';
		}
		
		$this->track($courseId,$userId,$action);
		
		//$sql = "SELECT course_id FROM OF_ExternalForms WHERE pixel_id = ? ";
		//$query = $this->dbHandle->query($sql,array($pixelId));
		//$row = $query->row_array();
		//
		//if($row['course_id']) {
		//	$this->track($row['course_id'],$userId,$action);
		//}
	}
	
    public function track($courseId,$userId,$action)
    {
        $this->initiateModel('write');
		
		$sql = "SELECT id FROM OF_ExternalForms_Tracking WHERE course_id = ? AND user_id = ? AND action = ?";
		$query = $this->dbHandle->query($sql,array($courseId,$userId,$action));
		$row = $query->row_array();
		
		if($row['id']) {
			$sql = "UPDATE OF_ExternalForms_Tracking SET attempt = attempt + 1, `time` = '".date('Y-m-d H:i:s')."' WHERE id = ?";
			$this->dbHandle->query($sql,array($row['id']));
		}
		else {
			$ip_address = getenv("HTTP_TRUE_CLIENT_IP")?getenv("HTTP_TRUE_CLIENT_IP"):(getenv("HTTP_X_FORWARDED_FOR")?getenv("HTTP_X_FORWARDED_FOR"):getenv("REMOTE_ADDR"));
			$data = array(
				'course_id' => $courseId,
				'user_id' => $userId,
				'action' => $action,
				'attempt' => 1,
				'ip_address' => $ip_address,
				'time' => date('Y-m-d H:i:s')
			);
			$this->dbHandle->insert('OF_ExternalForms_Tracking',$data);
		}
    }

    public function trackPBTConversion($pixelId,$page,$sessionId,$visitorId,$loggedInUserId,$ip_address)
	{
		if($page == 'landing') {
			$action = 'landed';
		}
		if($page == 'conversion') {
			$action = 'submitted';
		}
		
		$this->initiateModel('write');
		
		$time = date("Y-m-d H:i:s");
		if(empty($loggedInUserId))
			$loggedInUserId = 0;

		global $isNewVisitor;
		$is_shiksha_visitor = 1;
		if($isNewVisitor)
			$is_shiksha_visitor = 0;


		$sql = "INSERT INTO OF_Conversion_Tracking(pixel_id, session_id, visitor_id, user_id, action, ip_address, is_shiksha_visitor, time) ".
                            "VALUES (".$pixelId.", '".$sessionId."', '".$visitorId."', ".$loggedInUserId.",'".$action."', '".$ip_address."', ".$is_shiksha_visitor.",'".$time."')".
                            "ON DUPLICATE KEY UPDATE attempt = attempt+1, time = '".$time."', ip_address = '".$ip_address."'";
		$this->dbHandle->query($sql,array($row['id']));
		
	}
}
