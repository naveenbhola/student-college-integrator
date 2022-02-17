<?php
/**
 * File for Value source for degree preference field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for degree preference field
 */ 
class DegreePreference extends AbstractValueSource
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
            'aicte' => 'AICTE Approved',
            'ugc' => 'UGC Approved',
            'international' => 'International Degree',
            'any' => 'No Preference'
        );
    }
}