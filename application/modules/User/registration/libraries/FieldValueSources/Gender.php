<?php
/**
 * File for Value source for gender field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for gender field
 */ 
class Gender extends AbstractValueSource
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
            'Male' => 'Male',
            'Female' => 'Female'
        );
    }
}