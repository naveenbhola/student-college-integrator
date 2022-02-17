<?php
/**
 * File for Value source for consultant field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for consultant field
 */ 
class ContactByConsultant extends AbstractValueSource
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
