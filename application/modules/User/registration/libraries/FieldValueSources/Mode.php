<?php
/**
 * File for Value source for mode field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for mode field
 */ 
class Mode extends AbstractValueSource
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
            'fullTime' => 'Full Time',
            'partTime' => 'Part Time'
        );
    }
}