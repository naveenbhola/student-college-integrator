<?php

class OnlineFormSecurityModel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('OnlineForms');
	}

    private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
        	$this->dbHandle = $this->getWriteHandle();
		}		
	}
	
	public function checkCourse($courseId,$userId){
		$this->initiateModel();
		$queryCmd = "SELECT count(*) as count
					FROM `listings_main`,tuser
					WHERE `listing_type_id` = ?
					AND `status` = 'live'
					AND username = userid
					AND userid = ?
					AND `listing_type` = 'course'";
		$query = $this->dbHandle->query($queryCmd,array($courseId,$userId));
		$result = $query->result_array();
		return $result[0]['count'];
	}
	
	public function checkForm($formId,$userId){
		$this->initiateModel();
		$queryCmd = "SELECT courseId,userId
						FROM `OF_UserForms`
						WHERE `onlineFormId` = ?";
		$query = $this->dbHandle->query($queryCmd,array($formId));
		$result = $query->result_array();
		if(count($result) == 0){
			return false;
		}elseif($userId == $result[0]['userId']){
			return true;
		}else{
			return $this->checkCourse($result[0]['courseId'],$userId);
		}
	}

        public function checkValidCourse($courseId,$checkForExpire,$checkForInternal){
                $this->initiateModel();
		$checkExpire = '';
		$checkInternal = '';
		if($checkForExpire=='true'){
			$checkExpire = ' AND last_date>=DATE(now()) ';
		}
                if($checkForInternal=='true'){
                        $checkInternal = ' AND (externalURL = "" OR externalURL IS NULL) ';
                }

                $queryCmd = "SELECT count(*) as count
                                        FROM OF_InstituteDetails 
                                        WHERE courseId = '$courseId' or otherCourses LIKE ? $checkExpire $checkInternal
                                        AND `status` = 'live'";
                $query = $this->dbHandle->query($queryCmd,array('%'.$courseId.'%'));
                $result = $query->result_array();
                return $result[0]['count'];
        }
	
}
