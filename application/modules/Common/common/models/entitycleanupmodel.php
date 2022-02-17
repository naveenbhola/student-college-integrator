<?php
class EntityCleanupModel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('CampusAmbassador');
	}
/**
	 * returns a data base handler object
	 *
	 * @param none
	 * @return object
	 */

    private function initiateModel($operation='read'){
		if($operation=='read'){
			$this->dbHandle = $this->getReadHandle();
		}else{
	        	$this->dbHandle = $this->getWriteHandle();
		}		
	}

	function getEntityName($entityType=array()){
		$returnArr = array();
		foreach ($entityType as $key => $value) {
			if($value=='basecourse'){
				$returnArr[$value] = $this->getBaseCourseName();
			}
			else if($value=='exam'){
				$returnArr[$value] = $this->getExamName();
			}
			/*else if($value=='institute'){
				$returnArr[$value] = $this->getInstituteSynonymAndAbbreviation();	
			}*/
		}
		return $returnArr;
	}

	function getBaseCourseName(){
		$this->initiateModel('read');
		$query="select name from base_courses where status=? and name not in (?)";
		$res = $this->dbHandle->query($query,array('live' ,array('B.O.Th','M.Arch')))->result_array();
		foreach($res as $key=>$value){
			$result_array['actualName'][] = $value['name'];
		}
		return $result_array;
	}


	function getExamName(){
		$this->initiateModel('read');
		$query="select name from exampage_main where status=? and name not in (?)";
		$res = $this->dbHandle->query($query,array('live',array('SET','SEAT','MEET','GET')))->result_array();
		foreach($res as $key=>$value){
			$result_array['actualName'][] = $value['name'];
		}
		return $result_array;
	}

	function getInstituteSynonymAndAbbreviation(){
		$this->initiateModel('read');
		$query="select synonym, abbreviation from shiksha_institutes where status=? and ((abbreviation is not NULL and  abbreviation!='') or (synonym is not NULL and synonym!=''))";
		$res = $this->dbHandle->query($query,array('live'))->result_array();
		$count = 0;
		foreach($res as $key=>$value){
			if($value['synonym']!='' && $value['synonym']!=NULL && !in_array($value['synonym'], $result_array['actualName']))
			{
				$result_array['actualName'][$count] = $value['synonym'];
			}
			if($value['abbreviation']!='' && $value['abbreviation']!=NULL && !in_array($value['synonym'], $result_array['actualName']))
			{
				$count++;
				$result_array['actualName'][$count] = $value['abbreviation'];	
			}
		}
		return $result_array;	
	}
	
	function saveIntoDatabase($actualData,$processedData,$entityType){
		$this->initiateModel('write');
		$string = '';$count = 0;
		foreach ($entityType as $key => $value) {
			if($count>0){ $string .= ',';}
			foreach ($actualData[$value]['actualName'] as $k => $name) {
				$string .=  "(".$this->dbHandle->escape($name).",".$this->dbHandle->escape($processedData[$value]['modifiedName'][$k]).",'".$value."'),";
			}
			$string = trim($string,",");
			$count++;
		}
		
		$this->dbHandle->trans_start();
		$updateQuery = "update automoderationEntityCleanUp set status=?";
		$this->dbHandle->query($updateQuery,array('deleted'));

		$query = "insert into automoderationEntityCleanUp (oldName, newName, type) values ".$string;
		$this->dbHandle->query($query);
        $this->dbHandle->trans_complete();
        if ($this->dbHandle->trans_status() === FALSE) {
            throw new Exception('Transaction Failed');
        }
        return true;
	}

	function getData($entityType){
		$this->initiateModel('read');
		$query = "select oldName, newName, type from automoderationEntityCleanUp where type in (?) and status=?";
		$res = $this->dbHandle->query($query,array($entityType,'live'))->result_array();
		foreach ($res as $key => $value) {
			$returnArr[$value['type']][$key]['oldName'] = $value['oldName'];
			$returnArr[$value['type']][$key]['newName'] = $value['newName'];
		}
		return $returnArr;
	}
}
?>
