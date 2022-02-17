<?php
/**
 * File for Value source for gender field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for gender field
 */ 
class CurrentClass extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$vals = array(
        "11"=>"Studying in Class 11th or earlier",
        "12"=>"Studying in Class 12th",
        "12 done"=>"Completed Class 12th"
        );
        return $vals;


    }
}