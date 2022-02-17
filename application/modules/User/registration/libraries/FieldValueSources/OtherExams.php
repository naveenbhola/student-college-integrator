<?php

/**
 * File for Value source for other exams field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for other exams field
 */ 
class OtherExams extends AbstractValueSource
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
				'Management' => array('CAT', 'MAT', 'XAT', 'UGAT'),
				'Engineering' => array('IITJEE', 'GATE'),
				'International Exams' => array('TOEFL', 'IELTS', 'GRE', 'GMAT'),
        );
    }
}