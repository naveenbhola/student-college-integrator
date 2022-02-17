<?php 
require_once dirname(__FILE__).'/../DataAbstract.php';
class CourseFeesData extends DataAbstract {
	

	public function __construct() {
		$this->_CI = & get_instance();		
		$this->_CI->load->library('listing/AbroadListingCommonLib');
		$this->abroadLib = new AbroadListingCommonLib;
	}


	public function _getDataFromObject($courseObject){	
		$courseFeesData = array();
		$locationsData = $courseObject->getLocations();
		$feesData = array();
		foreach ($locationsData as $key => $value) {
			$feesData[$key] = $courseObject->getFees($key);
		}
		return $feesData;
	}

	public function _processData($courseFeesData){
		//$courseFeesData = array_filter($courseFeesData);
		foreach ($courseFeesData as $key => $value) {
			unset($courseFeesData[$key]);
			if(!empty($value)){
				$feesUnit = $value->getFeesUnit();
				$feesValue = $value->getFeesValue();
				if($feesUnit == 1){
					$feesValue = round($feesValue);
				}else{
					$feesValue = round($this->abroadLib->convertCurrency($feesUnit,1,$feesValue));
				}
				$courseFeesData[$key]['listing_location_id'] = $key;
				if($feesValue > 0) {
					$courseFeesData[$key]['nl_course_normalised_fees'] = $feesValue;
				} else{
					$courseFeesData[$key]['nl_course_normalised_fees'] = null;
				}
				$courseFeesData[$key]['fees_unit_id'] = 1;	
			}else{
				$courseFeesData[$key]['nl_course_normalised_fees'] = null;
			}
			
		}
		return $courseFeesData;
	}

	

}

?>

