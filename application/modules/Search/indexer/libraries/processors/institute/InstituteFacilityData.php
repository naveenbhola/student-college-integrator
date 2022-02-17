<?php 
require_once dirname(__FILE__).'/../DataAbstract.php';
class InstituteFacilityData extends DataAbstract{
	

	public function __construct()
	{
		$this->_CI = & get_instance();		
		$this->_CI->load->config('indexer/nationalIndexerConfig');
	}


	public function _getDataFromObject($instituteObj)
	{
		$instituteFacilityData = array();
		$faciltiesFromObject = $instituteObj->getFacilities();
		foreach ($faciltiesFromObject as $key => $value) {
			if($value->getFacilityStatus() == 1){
				$tempData = array();
				$tempData['facility_id'] = $value->getFacilityId();
				$tempData['name'] = $value->getFacilityName();
				if($tempData['name'] != ""){
					$instituteFacilityData[] = $tempData;	
				}	
			}
		}
		return $instituteFacilityData;
	}

	public function _processData($instituteFacilityData){

		$modifiedData = array();
		foreach ($instituteFacilityData as $key => $value) {
			$modifiedData['nl_institute_facilities_id'][] = $value['facility_id'];
			$modifiedData['nl_institute_facilities_name_id_map'][] = $value['name'].":".$value['facility_id'];
			$modifiedData['nl_institute_facilities_name'][] = $value['name'];
		}
		if(empty($modifiedData)){
			$modifiedData['nl_institute_facilities_id'] = null;
			$modifiedData['nl_institute_facilities_name_id_map'] = null;
			$modifiedData['nl_institute_facilities_name'] = null;
		}
		return $modifiedData;
	}


}

?>
