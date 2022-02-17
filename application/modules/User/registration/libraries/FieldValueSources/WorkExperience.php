<?php
/**
 *File for  Value source for work experience field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for work experience field
 */ 
class WorkExperience extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        if($params['national'] == 'yes') {
            if($params['type'] == 'number') {
                return $this->_getExpNumberValues();
            } else {
                return $this->_getExpRangeValues();
            }
        } else {
            return array(
                "-1" => "No experience",
                "0"  => "< 1 year",
                "1"  => "1 - 2 years",
                "2"  => "2 - 3 years",
                "3"  => "3 - 4 years",
                "4"  => "4 - 5 years",
                "5"  => "5 - 6 years",
                "6"  => "6 - 7 years",
                "7"  => "7 - 8 years",
                "8"  => "8 - 9 years",
                "9"  => "9 - 10 years",
                "10"  => "> 10 years"
            );
        }
    }

    /**
     * Get values for SA Apply
     *
     * @param array $params Additional parameters
     * @return array
     */ 
    public function getSAValues($params = array())
    {
        /*return array(
            "0"  => "No work experience",
            "1"  => "0 - 1 year",
            "2"  => "1 - 2 years",
            "3"  => "2 - 3 years",
            "4"  => "3 - 4 years",
            "5"  => "4 - 5 years",
            "6"  => "5 - 6 years",
            "7"  => "6 - 7 years",
            "8"  => "7 - 8 years",
            "9"  => "8 - 9 years",
            "10"  => "9 - 10 years",
            "11"  => "> 10 years"
        );*/
		return array(
                "-1" => "No Experience",
                "0"  => "< 1 year",
                "1"  => "1 - 2 years",
                "2"  => "2 - 3 years",
                "3"  => "3 - 4 years",
                "4"  => "4 - 5 years",
                "5"  => "5 - 6 years",
                "6"  => "6 - 7 years",
                "7"  => "7 - 8 years",
                "8"  => "8 - 9 years",
                "9"  => "9 - 10 years",
                "10"  => "> 10 years"
            );
    }

    /**
     * Get values for Registration
     *
     * @param array $params Additional parameters
     * @return array
     */ 
    public function _getExpRangeValues($params = array())
    {
        return array(
            "0"  => "No experience",
            "1"  => "0 - 1 year",
            "2"  => "1 - 2 years",
            "3"  => "2 - 3 years",
            "4"  => "3 - 4 years",
            "5"  => "4 - 5 years",
            "6"  => "5 - 6 years",
            "7"  => "6 - 7 years",
            "8"  => "7 - 8 years",
            "9"  => "8 - 9 years",
            "10"  => "9 - 10 years",
            "11"  => "> 10 years"
        );
    }


    /**
     * Get values for MMM,LDB Search
     *
     * @param array $params Additional parameters
     * @return array
     */ 
    public function _getExpNumberValues($params = array())
    {
        return array(
            "-1"  => "No experience",
            "0"  => "0 Year",
            "1"  => "1 year",
            "2"  => "2 years",
            "3"  => "3 years",
            "4"  => "4 years",
            "5"  => "5 years",
            "6"  => "6 years",
            "7"  => "7 years",
            "8"  => "8 years",
            "9"  => "9 years",
            "10"  => ">= 10 years",
        );
    }
}
