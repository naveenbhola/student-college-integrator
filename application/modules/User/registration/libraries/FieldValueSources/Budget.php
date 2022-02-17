<?php
/**
 * File for Value source for when plan to go field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for when plan to go field
 */ 
class Budget extends AbstractValueSource
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
			'0'   => '0-20 Lakh',
			'20'  => '20-40 Lakh',
			'40'  => '40-60 Lakh',
			'60'  => '60-80 Lakh',
			'80'  => '80L-1 Crore',
			'100' => 'More than 1 Crore'
		    );
    }
}