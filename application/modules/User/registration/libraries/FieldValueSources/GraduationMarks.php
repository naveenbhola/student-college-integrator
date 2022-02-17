<?php
/**
 * File for Value source for graduation marks field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for graduation marks field
 */ 
class GraduationMarks extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$marks = range(40,100);
        return array_combine($marks,$marks);
    }
}