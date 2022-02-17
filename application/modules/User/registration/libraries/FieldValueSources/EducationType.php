<?php

namespace registration\libraries\FieldValueSources;

/**
 * Value source for EducationType Field
 */ 

class EducationType extends AbstractValueSource 
{
	/**
	 * Get values
	 *
	 * @param array $params baseCoursesIds (optional)
	 * @return array
	 */ 

	function getValues($params = array()){
		$this->CI->load->builder('listingBase/ListingBaseBuilder');
		$listingBase = new \ListingBaseBuilder();

		$BaseCourseRepository = $listingBase->getBaseCourseRepository();

		$this->CI->load->library('listingBase/BaseAttributeLibrary');
		$BaseAttributeLibrary = new \BaseAttributeLibrary(); 
		
		$baseCoursesIds = $params['baseCoursesIds'];
		
		$baseAttributesData = $BaseAttributeLibrary->getValuesForAttributeByName('Education Type', 'array');
			
			$parentAttributesArray = array_values($baseAttributesData['Education Type']);
			$dependentAttributesData = array();

			foreach ($parentAttributesArray as $index => $valueString) {
				$dependentAttributesData[$valueString] = $BaseAttributeLibrary->getDependentAttributesByName('Education Type', $valueString, $returnFormat);
			}

			$returnDataArray = array();
			foreach ($baseAttributesData['Education Type'] as $key => $educationType) {

				$returnDataArray[$key]['name'] = $educationType;

				if(!empty($dependentAttributesData[$educationType])) {
					
					foreach ($dependentAttributesData[$educationType] as $attrId => $attrDetails) {
						
						foreach ($attrDetails['values'] as $valueId => $values) {
							if($key == 20 && $valueId == 33) {
								continue;
							} else if($key == 21 && !in_array($valueId,array(39,37,36,35,34,33,))) {
								continue;	
							}
							$returnDataArray[$key]['children'][$valueId] = $values['name'];
						}
					}
				}
			}

			foreach($baseCoursesIds as $key=>$value){
				if($value < 0){
					unset($baseCoursesIds[$key]);
				}
			}

			if(!empty($baseCoursesIds)){
				$this->CI->load->library('user/UserLib');
	        	$userLib = new \UserLib;
	        	$hyperLocalData = $userLib->getHyperAndNonhyperCoursesCount($baseCoursesIds);
	        	if($hyperLocalData['nonHyperLocal'] < 1){
	        		unset($returnDataArray[20]);
	        	}
			}

			return $returnDataArray;
	}

}

?>
