<?php
/**
 * This class returns the required data to the server
 *
 * @author     Aditya <aditya.roshan@shiksha.com>
 * @version
 */
class StudentDashBoardModel extends MY_Model
{
	/**
	 * constructor method.
	 *
	 * @param array
	 * @return array
	 */
	function __construct(){
		parent::__construct('OnlineForms');
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function returnOfInstitutesOfferandOtherDetails($institute_ids, $department='')
	{
		$dbHandle = $this->_getDbHandle();
		if($department==''){
			$sql = "SELECT id,instituteId,courseId,fees,discount,last_date,min_qualification,exams_required,courses_available,externalURL,otherCourses FROM OF_InstituteDetails WHERE instituteId IN (?) AND status = 'live' and last_date >= DATE(now())";
			$query = $dbHandle->query($sql,array($institute_ids));			
		}
		else{
			$sql = "SELECT id,instituteId,courseId,fees,discount,last_date,min_qualification,exams_required,courses_available,externalURL,otherCourses FROM OF_InstituteDetails WHERE instituteId IN (?) AND status = 'live' and last_date >= DATE(now()) and departmentName=?";
			$query = $dbHandle->query($sql, array($institute_ids,$department));
		}
		$rows = $query->result();
		return $rows;
	}
	/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */
	private function _getDbHandle($operation='read'){
		//connect DB
		$appId = 1;
		//$this->load->library('listingconfig');
		//$dbConfig = array('hostname'=>'localhost');
		//$this->listingconfig->getDbConfig_test($appId,$dbConfig);
		//$dbHandle = $this->load->database($dbConfig,TRUE);
                if($operation=='read'){
                        $dbHandle = $this->getReadHandle();
                }
                else{
                        $dbHandle = $this->getWriteHandle();
                }
		if($dbHandle == ''){
			error_log('error can not create db handle');
		}
		return $dbHandle;
	}

	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getCourseIdForInstituteId($institute_id_array, $department='')
	{
		$dbHandle = $this->_getDbHandle();
		if($department==''){
			$sql = "SELECT courseId, instituteId FROM OF_InstituteDetails WHERE status = 'live' and instituteId IN (?)";			
			$query = $dbHandle->query($sql,array($institute_id_array));
		}
		else{
			$sql = "SELECT courseId, instituteId FROM OF_InstituteDetails WHERE status = 'live' and instituteId IN (?) and departmentName=?";
			$query = $dbHandle->query($sql,array($institute_id_array,$department));
		}
		$rows = $query->result_array();
		return $rows;
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function checkDocumentTitle($doc_title,$user_id)
	{
		$doc_title = addslashes(strip_tags($doc_title));
		$user_id = addslashes(strip_tags($user_id));
		$sql = "SELECT count(id) as count FROM OF_user_documents_table WHERE status = 'live' and document_title=? and userId=?";
		$dbHandle = $this->_getDbHandle();
		$query = $dbHandle->query($sql,array($doc_title,$user_id));
		$rows = $query->result();
		return $rows;
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function insertDocument($document_title,$document_saved_path,$instituteId,$status,$user_id,$doc_type)
	{
		if(!$instituteId) {
			$instituteId = 'NULL';
		}
		$document_title = addslashes(strip_tags($document_title));
		$document_saved_path = addslashes(strip_tags($document_saved_path));
		$dbHandle = $this->_getDbHandle('write');
		//Make the queries SQL Injection free
		//$sql = "insert into OF_user_documents_table (userId,document_title,document_saved_path,instituteId,status,doc_type) values(".$user_id.","."'".$document_title."'".","."'".$document_saved_path."'".",".$instituteId.","."'".$status."'".",'".$doc_type."'".")";
		//$query = $dbHandle->query($sql);
		$sql = "insert into OF_user_documents_table (userId,document_title,document_saved_path,instituteId,status,doc_type) values(?,?,?,?,?,?)";
		$query = $dbHandle->query($sql,array($user_id,$document_title,$document_saved_path,$instituteId,$status,$doc_type));
		return $dbHandle->insert_id(); 
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function getDocumentDetails($type ="all",$userid)
	{
		$dbHandle = $this->_getDbHandle();
		if($type == "all") {
			$clause = "";
		} else {
			$clause = " and id=".$dbHandle->escape($type);
		}
		$sql = "select id,userId,document_title,document_saved_path,instituteId,status,doc_type from OF_user_documents_table where userId= ? and status='live'".$clause;
		$query = $dbHandle->query($sql,array($userid));
		$rows = $query->result();
		return $rows;
	}
	/**
	 * this method takes an array of institues ids and returns information
	 * related to discount offer and last dates.
	 *
	 * @param array
	 * @return array
	 */
	public function updateDocumentDetails($column_name,$value,$id)
	{
		$column_name = addslashes(strip_tags($column_name));
		$value = addslashes(strip_tags($value));
		$id = addslashes(strip_tags($id));
		$dbHandle = $this->_getDbHandle('write');
		//Make the queries SQL Injection free
		//$sql = "update OF_user_documents_table set ".$column_name."="."'".$value."'"." where status='live' and id=".$id." AND $id not in (select documentId from OF_user_documents_mapping where status ='live')";
		//$query = $dbHandle->query($sql);
		$sql = "update OF_user_documents_table set $column_name=? where status='live' and id=? AND $id not in (select documentId from OF_user_documents_mapping where status ='live')";
		$query = $dbHandle->query($sql,array($value,$id));
		$rows = $dbHandle->affected_rows();
		return $rows;
	}
}
