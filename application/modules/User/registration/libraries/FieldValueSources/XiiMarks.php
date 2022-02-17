<?php
/**
 * File for Value source for XII marks field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for XII marks field
 */ 
class XiiMarks extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$marks = range(100,33);
        return array_combine($marks,$marks);
    }
}