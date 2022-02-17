<?php
/**
 * File for Value source for graduation completion year field
 */
namespace registration\libraries\FieldValueSources;

/**
 * Value source for graduation completion year field
 */ 
class GraduationCompletionYear extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$years = range(date('Y',strtotime('+7 years')),date('Y',strtotime('-10 years')));
        return array_combine($years,$years);
    }
}