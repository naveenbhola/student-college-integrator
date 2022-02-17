<?php
/**
 * File for Value source for preferred year of admission field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for preferred year of admission field
 */ 
class PrefYear extends AbstractValueSource
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
			    date('Y',strtotime('+0 year')) => date('Y',strtotime('+0 year')),
			    date('Y',strtotime('+1 year')) => date('Y',strtotime('+1 year')),
			    '4000' => 'Later'
			);
    }
}
