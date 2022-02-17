<?php

/**
 * Validator File for Resident country field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for Resident country field
 */ 
class ResidentCountry extends AbstractValidator
{
	/**
	 * Constrcutor
	 */ 
	function __construct()
	{
		parent::__construct();
	}
	
    /**
	 * Validate the Resident country field
	 *
	 * @param object $field \registration\libraries\RegistrationFormField
	 * @param mixed $value Value against which the field is to be validated
	 * @param array $data Full registration data array
	 * @return bool
	 */ 
	public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
		if(empty($value)) {
			return TRUE;
		}
		
		/**
		 * For Resident country, $value must be an array
		 */ 
		if(!is_array($value)) {
			return FALSE;
		}
                
        // if(STUDY_ABROAD_NEW_REGISTRATION){
			$validValues = $field->getValues();
			$countries = array();
			foreach ($validValues as $validValue) {
				$countries[] = $validValue->getId();
			}
			foreach ($value as $id) {
				if(!in_array($id,$countries)) {
					return FALSE;
				}
			}
			return TRUE;
                // }
                
		$validValues = $field->getValues();
		$countries = $this->_extractCountries($validValues);
		
		/**
		 * Check every value in the $value array for validity
		 */
		foreach($value as $countryValue) {
			$countriesInValue = explode(',',$countryValue);
			foreach($countriesInValue as $countryInValue) {
				if(!in_array($countryInValue,$countries)) {
					return FALSE;
				}
			}
		}
		
		return TRUE;
    }
	 /**
	 * Validate the destination country field
	 *
	 * @param array $countryData 
	 * @return array $countries
	 */ 
	private function _extractCountries($countryData)
	{
		$countries = array();
		foreach($countryData['countries'] as $regionId => $countriesInRegion) {
			foreach($countriesInRegion as $country) {
				$countries[] = $country->getId();
			}
		}
		return $countries;
	}
}
