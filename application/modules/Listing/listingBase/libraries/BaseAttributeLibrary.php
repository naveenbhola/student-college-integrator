<?php
class BaseAttributeLibrary {
	function __construct() {
		$this->CI =& get_instance();
		
		//load model
		$this->baseattributemodel = $this->CI->load->model('listingBase/baseattributemodel');
	}

	/**
     * Get attribute id by attribute name
     * @param  string/array $attributeNames
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns attribute id
     */
    function getAttributeIdByAttributeName($attributeNames, $outputFormat = 'array') {
    	//validations
    	if(empty($attributeNames)) {
    		return;
    	}
    	if(!is_array($attributeNames)) {
    		$attributeNames = array($attributeNames);
    	}

    	//get data from model
    	$data = $this->getAttributeDataByNameFromModel($attributeNames);
    	
    	//format result
    	foreach ($data['model'] as $key => $value) {
    		$attrOriginal = $data['nameCaseMapping'][strtolower($value['attribute_name'])];
    		$result[$attrOriginal] = $value['attribute_id'];
    	}
    	
    	if($outputFormat == 'json') {
    		$result = json_encode($result);
    	}
    	
    	return $result;
    }

    private function getAttributeDataByNameFromModel($attributeNames = array()) {
    	//format input data
    	$attributeNamesInSmall = array();
    	foreach ($attributeNames as $key => $value) {
    		$nameInLower = strtolower($value);
    		$attributeNamesInSmall[] = $nameInLower;
    		$attributeNameCaseMapping[$nameInLower] = $value;
    	}

    	//get data
    	$data['model'] = $this->baseattributemodel->getAttributeDataByAttributeName($attributeNamesInSmall);
    	$data['nameCaseMapping'] = $attributeNameCaseMapping;

    	return $data;
    }

    /** 
     * Get all values of attribute(s) by attribute name(s)
     * @param  string/array $attributeNames
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns attribute values for multiple attributes
     */
    function getValuesForAttributeByName($attributeNames, $outputFormat = 'array') {
    	//validations
    	if(empty($attributeNames)) {
    		return;
    	}
    	if(!is_array($attributeNames)) {
    		$attributeNames = array($attributeNames);
    	}

    	//get data from model
    	$data = $this->getAttributeDataByNameFromModel($attributeNames);

    	//format result
    	foreach ($data['model'] as $key => $value) {
    		$attrOriginal = $data['nameCaseMapping'][strtolower($value['attribute_name'])];
    		$result[$attrOriginal][$value['value_id']] = $value['value_name'];
    	}
    	
    	if($outputFormat == 'json') {
    		$result = json_encode($result);
    	}

    	return $result;
    }

    /** 
     * Get all values of attribute(s) by attribute id(s)
     * @param  int/array $attributeIds
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns attribute values for multiple attributes
     */
    function getValuesForAttributeById($attributeIds, $outputFormat = 'array') {
        //validations
        if(empty($attributeIds)) {
            return;
        }
        if(!is_array($attributeIds)) {
            $attributeIds = array($attributeIds);
        }

        //get data
        $data = $this->baseattributemodel->getAttributeByAttributeId($attributeIds);

        //format result
        foreach ($data as $key => $value) {
            $result[$value['attribute_id']][$value['value_id']] = $value['value_name'];
        }
        
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        return $result;
    }

    /** 
     * Get name of the value by its value id
     * @param  int/array $valueIds
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns name of the value by its value id
     */
    function getValueNameByValueId($valueIds, $outputFormat = 'array') {
    	//validations
    	if(empty($valueIds)) {
    		return;
    	}
    	if(!is_array($valueIds)) {
    		$valueIds = array($valueIds);
    	}

    	//get data
    	$data = $this->baseattributemodel->getDataByValueId($valueIds);

    	//format result
    	foreach ($data as $key => $value) {
    		$result[$value['value_id']] = $value['value_name'];
    	}
    	
    	if($outputFormat == 'json') {
    		$result = json_encode($result);
    	}

    	return $result;
    }

    /** 
     * Get attribute name by its attribute id
     * @param  int/array $attributeIds
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns name of the attribute by its attribute id
     */
    function getAttributeNameByAttributeId($attributeIds, $outputFormat = 'array') {
    	//validations
    	if(empty($attributeIds)) {
    		return;
    	}
    	if(!is_array($attributeIds)) {
    		$attributeIds = array($attributeIds);
    	}

    	//get data
    	$data = $this->baseattributemodel->getAttributeByAttributeId($attributeIds);

    	//format result
    	foreach ($data as $key => $value) {
    		$result[$value['attribute_id']] = $value['attribute_name'];
    	}
    	
    	if($outputFormat == 'json') {
    		$result = json_encode($result);
    	}
    	
    	return $result;
    }

    /** 
     * Get attribute name by value id
     * @param  int/array $valueIds
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns name of the attribute by its attribute id
     */
    function getAttributeNameByValueId($valueIds, $outputFormat = 'array') {
        //validations
        if(empty($valueIds)) {
            return;
        }
        if(!is_array($valueIds)) {
            $valueIds = array($valueIds);
        }

        //get data
        $data = $this->baseattributemodel->getDataByValueId($valueIds);

        //format result
        foreach ($data as $key => $value) {
            $result[$value['value_id']] = $value['attribute_name'];
        }
        
        if($outputFormat == 'json') {
            $result = json_encode($result);
        }
        
        return $result;
    }

    /** 
     * Get immediate child attributes with their values on the basis of attribute name and value combination
     * @param  string $attributeName mandatory; eg - course type
     * @param  string $valueString mandatory; eg - full time
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns immediate child attributes with their values
     */
    function getDependentAttributesByName($attributeName = '', $valueString = '', $outputFormat = 'array') {
    	//validations
    	if(empty($attributeName)) {
    		return;
    	} else {
    		$attributeNameInSmall = strtolower($attributeName);
    	}
    	if(empty($valueString)) {
    		return;
    	} else {
    		$valueStringInSmall = strtolower($valueString);
    	}

    	//get data
    	$data = $this->baseattributemodel->getDependentAttributesByName($attributeNameInSmall, $valueStringInSmall);

    	//format output data
    	$result = $this->formatOutputToGetDependentAttributes($data);

    	if($outputFormat == 'json') {
    		$result = json_encode($result);
    	}

    	return $result;
    }

    private function formatOutputToGetDependentAttributes($data) {
    	foreach ($data as $key => $value) {
    		$result[$value['attribute_id']]['id'] = $value['attribute_id'];
    		$result[$value['attribute_id']]['name'] = $value['attribute_name'];
    		if($value['value_name']) {
	    		$result[$value['attribute_id']]['values'][$value['value_id']]['id'] = $value['value_id'];
	    		$result[$value['attribute_id']]['values'][$value['value_id']]['name'] = $value['value_name'];
	    	} else {
	    		//$result[$value['attribute_id']]['values'][$value['value_id']]['id'] = $value['value_id'];
	    		$result[$value['attribute_id']]['values'] = NULL;
	    	}
    	}
    	
    	return $result;
    }

    /*
     * To get child attributes on the basis of value id(uniquely identifies attribute name & value combo)
     */
    /** 
     * Get immediate child attributes with their values on the basis of value id(uniquely identifies attribute name & value combo)
     * @param  int $valueId
     * @param  string $outputFormat 'array' or 'json'
     * @return array              returns immediate child attributes with their values
     */
    function getDependentAttributesByValueId($valueId, $outputFormat = 'array') {
		//validations
    	if(empty($valueId)) {
    		return;
    	}

    	//get data
    	$data = $this->baseattributemodel->getDependentAttributesById($valueId);

    	//format output data
    	$result = $this->formatOutputToGetDependentAttributes($data);

    	if($outputFormat == 'json') {
    		$result = json_encode($result);
    	}

    	return $result;
    }

    function getParentValueIdByValueId($valueId = array()){
        if(empty($valueId)) {
            return;
        }
        if(!is_array($valueId)) {
            $valueId = array($valueId);
        }
        
        $data = $this->baseattributemodel->getParentValueByValueId($valueId);

        //format result
        foreach ($data as $key => $value) {
            $result[$value['value_id']][] = $value['parent_value_id'];
        }
        
        return $result;
    }

    /* 
     * Input Params - Either attribute name or id is mandatory
     * Output - Will return parent attributes which should be reset on changing the attribute
     */
    function getAttributesToReset($attributeId = NULL, $attributeName = NULL) {
    	if(!empty($attributeId)) {
    		$data = $this->baseattributemodel->getParentAttributesById($attributeId);
    	} 
    	else if(!empty($attributeName)) {
    		$data = $this->baseattributemodel->getParentAttributesByName($attributeName);
    	} 
    	else {
    		return;
    	}
    	
    	//$result = array();
	    while(!empty($data)) {
	    	//$result = array_merge($result, $data);
	    	
    		//foreach ($data as $key => $value) {
	    		_p($data[0]['attribute_id']);
	    		$data = $data + $this->getAttributesToReset($data[0]['attribute_id']);
	    		_p($data);
	    		_p('-----');
	    	//}
    	}
    	
    	return $data;
    }

    function getAttributeIdByValueId($valueIds = array(), $outputFormat = 'array'){
        if(empty($valueIds)) {
            return array();
        }

        $attributeData = array();
        $returnData = array();

        $attributeData = $this->baseattributemodel->getAttributeIdByValueId($valueIds);

        foreach($attributeData as $key=>$attr){
            $returnData[$attr['value_id']] = $attr['attribute_id'];
        }

        if($outputFormat == 'json'){
            $returnData = json_encode($returnData);
        }
        
        return $returnData;
    }
}