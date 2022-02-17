<?php
/**
 * File for Value source for Isd Code field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for Isd Code field
 */ 
class IsdCode extends AbstractValueSource
{	
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */
    
    public function getValues($params = array()){
	    
	    if($params['source'] == 'DB'){
	    	return $this->_getValuesFromDB();
	    }else{
	    	return $this->_getValuesFromConfig();
	    }
    }

    private function _getValuesFromDB(){
    	$this->CI->load->model('registration/registrationmodel');
    	$registrationmodel = new \registrationmodel();

    	$returnData = array();
    	$isdCodes = $registrationmodel->getCompleteIsdCodeData();
    	foreach ($isdCodes as $key => $value) {
    		$returnData[$value['isdCode'].'-'.$value['shiksha_countryId']] = $value;
    	}
    	return $returnData;
    }

    private function _getValuesFromConfig(){
		require APPPATH.'modules/User/registration/config/ISDCodeConfig.php';

		/**
		 * National IsdCode
		 */ 

		$data = array();
		foreach($ISDCodesList as $country=>$value){	
			$key = $value['ISD'].'-'.$value['shikshaCountryId'];
			$value = $country.' (+'.$value['ISD'].')';
			$data[$key] = $value;
		}

		return $data;    	
    }

}