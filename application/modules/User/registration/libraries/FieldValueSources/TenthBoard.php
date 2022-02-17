<?php
/**
 * File for Value source for Tenth Board field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for Tenth Board field
 */ 
class TenthBoard extends AbstractValueSource
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
           'CBSE' => 'CBSE',
           'ICSE' => 'ICSE/State Boards',
           'IGCSE' => 'Cambridge IGCSE',
           'IBMYP' => 'International Baccalaureate',
           'NIOS' => 'NIOS'
        );
    }
}