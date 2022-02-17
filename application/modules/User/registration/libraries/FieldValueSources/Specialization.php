<?php

namespace registration\libraries\FieldValueSources;

/**
 * Value source for specialization field
 */ 
class Specialization extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$values = array();
		
        $desiredCourse = (int) $params['desiredCourse'];
		if($desiredCourse) {
			
			$this->CI->load->builder('LDBCourseBuilder','LDB');
			$LDBCourseBuilder = new \LDBCourseBuilder;
			$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
			
			$specializations = $LDBCourseRepository->getSpecializations($desiredCourse);
			foreach($specializations as $specialization) {
				$values[$specialization->getId()] = $specialization->getSpecialization();
			}
		}
		return $values;
    }
}