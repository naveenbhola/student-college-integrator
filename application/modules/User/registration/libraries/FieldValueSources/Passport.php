<?php
/**
 * File for Value source for specialization field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for specialization field
 */ 
class Passport extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$values = array('yes','no');	
		return $values;
    }
}
