<?php
/**
 * Validator for residence city locality field
 */ 

namespace registration\libraries\FieldValidators;

/**
 * Validator for residence city locality field
 */ 

class ResidenceCityLocality extends AbstractValidator {
    
    /**
     * Constrcutor
     */ 
    function __construct()
    {
           parent::__construct();
    }
    
    /**
     * Validate the preferred study location field
     *
     * @param object $field \registration\libraries\RegistrationFormField
     * @param mixed $value Value against which the field is to be validated
     * @param array $data Full registration data array
     * @return bool
     */ 
    public function validate(\registration\libraries\RegistrationFormField $field,$value,$data)
    {
            /**
             * For preferred study location, $value must not be an array
             */ 
            if(is_array($value)) {
                    return FALSE;
            }

            $validValues = $field->getValues();
            $cities = $this->_extractCities($validValues);

            if(in_array($value,$cities)) {
                    return TRUE;
            }
            else {
                    return FALSE;
            }

    }
    
    /**
     * Function to extract the cites data
     *
     * @param array $cityData
     * 
     * @return array
     */
    private function _extractCities($cityData)
    {
            $cities = array();
            foreach($cityData['tier1Cities'] as $city) {
                    $cities[] = $city['cityId'];
            }
            foreach($cityData['tier2Cities'] as $city) {
                    $cities[] = $city['cityId'];
            }
            foreach($cityData['tier3Cities'] as $city) {
                    $cities[] = $city['cityId'];
            }
            foreach($cityData['virtualCities'] as $Vcity) {
                foreach($Vcity['cities'] as $city) {
                    $cities[] = $city['city_id'];
                }
            }
            foreach($cityData['metroCities'] as $city) {
                    $cities[] = $city['cityId'];
            }
            foreach($cityData['stateCities'] as $statesData) {
                    foreach($statesData['cityMap'] as $city) {
                            $cities[] = $city['CityId'];
                    }
            }
            foreach($cityData['citiesByStates'] as $statesData) {
                    foreach($statesData['cityMap'] as $city) {
                            $cities[] = $city['CityId'];
                    }
            }
            return $cities;
    }
}
