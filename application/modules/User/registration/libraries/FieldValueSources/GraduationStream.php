<?php
/**
 * File for Value source for Graduation Stream field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for Graduation Stream field
 */ 
class GraduationStream extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        return array(
        	'Engineering' => 'Engineering', 
        	'Science' => 'Science', 
        	'Business' => 'Business', 
        	'Humanities' => 'Humanities', 
        	'Social Science' => 'Social Science', 
        	'Commerce' => 'Commerce');
    }
}