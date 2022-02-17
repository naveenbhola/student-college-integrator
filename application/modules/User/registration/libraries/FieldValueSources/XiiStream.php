<?php
/**
 * File for Value source for XII stream field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for XII stream field
 */ 
class XiiStream extends AbstractValueSource
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
                        'Science',
                        'Arts',
                        'Commerce'
                    );
    }
}