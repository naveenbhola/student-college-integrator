<?php
class kipoints_model extends MY_Model
{
	/*
	 * constructor method.
	 *
	 * @param array
	 * @return array
 	*/
	
	private function initiateModel($operation='read'){
		if($operation=='read'){ 
			$this->dbHandle = $this->getReadHandle();
		}else{
		    $this->dbHandle = $this->getWriteHandle();
		}		
	}

	function getTrackingKeys(){
		$this->initiateModel('read');
		$sql = "select * from tracking_pagekey";
		$result = $this->dbHandle->query($sql)->result_array();
		return $result;
	}

	function insertRow($row){
		$this->initiateModel('write');
		$sql = "insert into tracking_pagekey(keyName,page,widget,conversionType,site,siteSource) values('".$row['keyName']."','".$row['page']."','".$row['widget']."','".$row['conversionType']."','".$row['site']."','".$row['siteSource']."')";
		$this->dbHandle->query($sql,array());
		$id = $this->dbHandle->insert_id();
		return array('id'=>$id,'sql'=>$sql);
    }

    function insertDataForuserTracking($name,$localIP,$actionType,$key){
    	$array = array('name'=>$name,'localIP'=>$localIP,'actionType'=>$actionType,'keyId'=>$key);
    	$this->initiateModel('write');
    	$this->dbHandle->insert('kiPointUserTracking',$array);
    }
    
    function checkIfRowAlreadyExists($row){
    	$this->initiateModel('read');
    	$this->dbHandle->select('id');
    	$this->dbHandle->where($row);
    	$res = $this->dbHandle->get('tracking_pagekey')->result_array();
    	return reset(reset($res));
    }
}