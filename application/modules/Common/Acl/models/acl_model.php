<?php
class Acl_Model extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('AnA');
	}

	private function initiateModel($operation='read'){ 
		$appId = 1;	
		$this->load->library('aclconfig');
		//$dbConfig = array( 'hostname'=>'localhost');
		//$this->aclconfig->getDbConfig($appId,$dbConfig);
		//$this->dbHandle = $this->load->database($dbConfig,TRUE);
                if($operation=='read'){
                        $this->dbHandle = $this->getReadHandle();
                }
                else{
                        $this->dbHandle = $this->getWriteHandle();
                }	
	}

	function insertIntouserGroupsMappingTable($dbHandleSent,$newLevelOfUser,$userId){ error_log('aclmodelquery insertIntouserGroupsMappingTable');
		if(!is_resource($dbHandleSent)){
			$this->initiateModel('write');
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		
			$insertQueryCmd = "insert into userGroupsMappingTable (`groupId`,`userId`,`creatorId`,`status`) values (?,?,11,'live')";
			$success='Success';
		
		$query=$this->dbHandle->query($insertQueryCmd, array($newLevelOfUser,$userId));
		//Remove power user mailer
		/*
		if($newLevelOfUser==2){
                    $this->load->model('UserPointSystemModel');
        	    $result  = $this->UserPointSystemModel->sendPowerUserMailer($newLevelOfUser,$userId);
                            }
		*/
	}

	function updateIntouserGroupsMappingTable($dbHandleSent,$newLevelOfUser,$userId){ error_log('aclmodelquery updateIntouserGroupsMappingTable');
		if(!is_resource($dbHandleSent)){
			$this->initiateModel('write');
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		
			$updateQueryCmd = "update userGroupsMappingTable set `groupId`=?,`status`='live' where `userId`=?";
			$success='Success';
		
		$query=$this->dbHandle->query($updateQueryCmd, array($newLevelOfUser,$userId));
		//Remove power user mailer
		/*
		if($newLevelOfUser==2){error_log('aclmodelquery inside=='.print_r($newLevelOfUser,true));
		    $this->load->model('UserPointSystemModel');
                    $result  = $this->UserPointSystemModel->sendPowerUserMailer($newLevelOfUser,$userId);
                            }
		*/
	}
	
	function deleteIntouserGroupsMappingTable($dbHandleSent,$userId){ error_log('aclmodelquery deleteIntouserGroupsMappingTable');
		if(!is_resource($dbHandleSent)){
			$this->initiateModel('write');
		}else{
			$this->dbHandle = $dbHandleSent;
		}
		
			$deleteQueryCmd = "update userGroupsMappingTable set `status`='deleted' where `userId` = ?";			
			$success='Success';
		
		$query=$this->dbHandle->query($deleteQueryCmd, array($userId));
	}

	function checkUserRight($userId,$permissions,$dbHandle=''){
		$rights = "'" . implode("','",$permissions). "'";
		if(!is_resource($dbHandleSent)){
				$this->initiateModel();
			}else{
				$this->dbHandle = $dbHandleSent;
			}
 $queryCmd = "select * from userGroupsMappingTable ugmt ,userGroupsAccessTable ugat where ugmt.groupId = ugat.groupId and ugmt.userId=? and ugat.accessName in ($rights) and ugmt.status='live'";
 
            $resultSet = $this->dbHandle->query($queryCmd, array($userId));
			if($resultSet->num_rows()<=0){
				foreach($permissions as $an)
						$finalArray[$an] = 'False';
				return $finalArray;
			}else{
				foreach($resultSet->result_array() as $row) {
					$finalArray[$row['accessName']] = $row['status'];
				} 
				return $finalArray;
			}
		
	}

}
?>
