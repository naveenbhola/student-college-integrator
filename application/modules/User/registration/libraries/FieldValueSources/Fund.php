<?php
/**
 * File for Value source for fund field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for fund field
 */ 
class Fund extends AbstractValueSource
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
            'own' => 'Own funds',
            'bank' => 'Education Loan',
            'other' => 'Other'
        );
    }
}