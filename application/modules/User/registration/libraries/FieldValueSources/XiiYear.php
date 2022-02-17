<?php
/**
 * File for Value source for XII Year field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for XII Year field
 */ 
class XiiYear extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$years = range(date('Y',strtotime('+3 years')),1990);
		return array_combine($years,$years);
    }
}