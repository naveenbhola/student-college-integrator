<?php

class FilterExamModel extends MY_Model {

     /**
     * @var Object DB Handle
     */
    private $dbHandle;
    
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
	
    // entering new fields to the table
    function setExamlist($examName,$courseId){
	
	$this->initiateModel('write');
	$query="INSERT INTO `shiksha`.`SelectExamListCMS` (`Id`, `CourseId`, `ExamName`, `Status`, `TimeStamp`) VALUES (NULL, ?, ?, 'live', CURRENT_TIMESTAMP)";
	$sql=$this->dbHandle->query($query, array($courseId, $examName));
	
    }
    // reset all the fields to history
    function resetExamList(){
	$this->initiateModel('write');
	$query="UPDATE `shiksha`.`SelectExamListCMS` SET `Status` = 'history'";
	$sql=$this->dbHandle->query($query);
	
    }
    // update the new status
    function updateExamList($id){
	$this->initiateModel('write');
	$query="UPDATE `shiksha`.`SelectExamListCMS` SET `Status` = 'live' WHERE `SelectExamListCMS`.`Id` = ? ";
	$sql=$this->dbHandle->query($query,array($id));
    }
    // get initially selected course
    function getSelectedExamListDB(){
	$this->initiateModel();
	$query="SELECT Id,ExamName,CourseId FROM `shiksha`.`SelectExamListCMS` where `Status`='live'";
	$sql=$this->dbHandle->query($query);
	return $sql->result();	
    }
    
    function getAllExams(){
	$this->initiateModel();
	$query="SELECT Id,CourseId,ExamName FROM `shiksha`.`SelectExamListCMS`";
	$sql=$this->dbHandle->query($query);
	return $sql->result();
    }
    
    function getDesiredLiveExams($des){
	$this->initiateModel();
	$query="SELECT ExamName FROM `SelectExamListCMS` WHERE `CourseId` = ? AND `Status` = 'live'";
	$sql=$this->dbHandle->query($query,array($des));
	$response = array();
	foreach($sql->result_array() as $row) {
	    $response[] = $row['ExamName'];
	}
	return $response;
    }
}