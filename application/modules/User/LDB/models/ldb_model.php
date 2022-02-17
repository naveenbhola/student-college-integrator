<?php
class LdbModel extends CI_Model {
	function __construct(){
		parent::__construct();
	}

	/**
	 * @param object $appId default 1
	 * @param object listingconfig
	 * @return object
	 */

	function getDbHandle() {
		// Reserved Keyword for SHIKSHA Tech. will be used later
		$appId = 1; 
		// $obj =& get_instance();
		$this->load->library('listingconfig');
		$dbConfig = array( 'hostname'=>'localhost');
		$this->listingconfig->getDbConfig_test($appId,$dbConfig);
		$dbHandle = $this->load->database($dbConfig,TRUE);
		if($dbHandle == ''){
			error_log_shiksha('error occurred...can not create db handle');
		}
		return $dbHandle;
	}

	function getSpecializationList($courseName)
	{
		$result=array();
		$queryCmd="select * from tCourseSpecializationMapping where CourseName=? and SpecializationName!='All'";
		$dbHandle=$this->getDbHandle();
		$query=$dbHandle->query($queryCmd, array($courseName));
		foreach($query->result_array() as $row)
		{
			$result[]=$row;
		}
		return $result;
	}

	function getCourseList($categoryId='')
	{
		$result=array();
		$queryCmd="select * from tCourseSpecializationMapping where SpecializationName='All' and CourseName!='All'";
		if($categoryId!='')
		{
			$queryCmd.=" and CategoryId=".$categoryId;
		}
		$dbHandle=$this->getDbHandle();
		$query=$dbHandle->query($queryCmd);
		foreach($query->result_array() as $row)
		{
			$result[]=$row;
		}
		return $result;
	}
	function getCityStateList()
	{
		$result=array();
		$queryCmd="select city_id,city_name,state_id from countryCityTable where enabled=1 and countryId=2";
		$dbHandle=$this->getDbHandle();
		$query=$dbHandle->query($queryCmd);
		foreach($query->result_array() as $row)
		{
			$result['Name']=$row['city_name'];
			$result['CityId']=$row['city_id'];
			$result['StateId']=$row['state_id'];
		}
		$queryCmd="select state_id,state_name from stateTable where enabled=1 and countryId=2";
		$query=$dbHandle->query($queryCmd);
		foreach($query->result_array() as $row)
		{
			$result['Name']=$row['state_name'];
			$result['CityId']=0;
			$result['StateId']=$row['state_id'];
		}
		return $result;

	} 
}
?>

