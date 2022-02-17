<?php

class baseattributemodel extends MY_Model {
	function __construct() {
		parent::__construct('Listing');
    }

    private function initiateModel($mode = "write") {
		if($this->dbHandle && $this->dbHandleMode == 'write') {
		    return;
		}
		
		$this->dbHandleMode = $mode;
		$this->dbHandle = NULL;
		if($mode == 'read') {
			$this->dbHandle = $this->getReadHandle();
		} else {
			$this->dbHandle = $this->getWriteHandle();
		}
    }

    function getAttributeDataByAttributeName($attributeNamesInSmall) {
    	$this->initiateModel('read');
    	
    	$sql = "SELECT * FROM base_attribute_list WHERE status = 'live' AND LOWER(attribute_name) IN (?)";
    	$data = $this->dbHandle->query($sql,array($attributeNamesInSmall))->result_array();
    	
    	return $data;
    }

    function getDataByValueId($valueIds) {
    	$this->initiateModel('read');
    	$sql = "SELECT * FROM base_attribute_list WHERE status = 'live' AND value_id IN (?)";
    	$data = $this->dbHandle->query($sql,array($valueIds))->result_array();
    	
    	return $data;
    }

    function getParentValueByValueId($valueIds) {
        $this->initiateModel('read');
        
        $sql = "SELECT * FROM attribute_parent_child_mapping where status = 'live' AND value_id IN (?)";
        $data = $this->dbHandle->query($sql,array($valueIds))->result_array();
        
        return $data;
    }

    function getValueIdByValueName($valueNamesInSmall) {
        
    	$this->initiateModel('read');
    	
    	$sql = "SELECT * FROM base_attribute_list WHERE status = 'live' AND LOWER(value_name) IN  (?)";
    	$data = $this->dbHandle->query($sql,array($valueNamesInSmall))->result_array();
    	
    	return $data;
    }

    function getAttributeByAttributeId($attrIds) {
    	$this->initiateModel('read');
    	
    	$sql = "SELECT * FROM base_attribute_list WHERE status = 'live' AND attribute_id IN (?)";
    	$data = $this->dbHandle->query($sql,array($attrIds))->result_array();
    	
    	return $data;
    }

    function getDependentAttributesByName($attributeNameInSmall, $valueStringInSmall) {
    	$this->initiateModel('read');
    	
    	$sql = "SELECT bal_child.attribute_name, bal_child.attribute_id, bal_child.value_name, bal_child.value_id ".
    			"FROM base_attribute_list bal ".
    			"INNER JOIN attribute_parent_child_mapping apcm ON apcm.parent_value_id = bal.value_id AND apcm.status = 'live' ".
    			"INNER JOIN base_attribute_list bal_child ON bal_child.value_id = apcm.value_id AND bal_child.status = 'live' ".
    			"WHERE bal.status = 'live' ".
    			"AND LOWER(bal.attribute_name) = ? ".
    			"AND LOWER(bal.value_name) = ? ";

    	$data = $this->dbHandle->query($sql,array($attributeNameInSmall,$valueStringInSmall))->result_array();
    	
    	return $data;
    }

    function getDependentAttributesById($valueId) {
    	$this->initiateModel('read');
    	
    	$sql = "SELECT bal.attribute_name, bal.attribute_id, bal.value_name, bal.value_id ".
    			"FROM base_attribute_list bal ".
    			"INNER JOIN attribute_parent_child_mapping apcm ON apcm.value_id = bal.value_id AND apcm.status = 'live' ".
    			"WHERE bal.status = 'live' ".
    			"AND apcm.parent_value_id = ?";
    	$data = $this->dbHandle->query($sql,array($valueId))->result_array();
    	
    	return $data;
    }

    function getParentAttributesById($attributeId) {
    	$this->initiateModel('read');
    	
    	$sql = "SELECT DISTINCT bal.attribute_name, bal.attribute_id ".
    			"FROM base_attribute_list bal ".
    			"INNER JOIN attribute_parent_child_mapping apcm ON apcm.parent_value_id = bal.value_id AND apcm.status = 'live' ".
    			"WHERE bal.status = 'live' ".
    			"AND apcm.attribute_id = ?";
    	$data = $this->dbHandle->query($sql,array($attributeId))->result_array();
    	
    	return $data;
    }

    function getAttributeIdByValueId($valueIds){
        $this->initiateModel('read');

        $sql = 'SELECT attribute_id, value_id FROM base_attribute_list WHERE value_id IN (?)';
        return $this->dbHandle->query($sql,array($valueIds))->result_array();
    }
}