<?php
/**
 * File for Value source for graduation status field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for graduation status field
 */ 
class GraduationStatus extends AbstractValueSource
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
            'Completed',
            'Pursuing'
        );
    }
}