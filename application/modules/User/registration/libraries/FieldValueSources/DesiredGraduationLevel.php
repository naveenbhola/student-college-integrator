<?php
/**
 * File for Value source for desired graduation level field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for desired graduation level field
 */ 
class DesiredGraduationLevel extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        STUDY_ABROAD_NEW_REGISTRATION;
	if(STUDY_ABROAD_NEW_REGISTRATION) {
            $abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
            $desiredGraduationLevel = $abroadCommonLib->getAbroadCourseLevelsForRegistrationForms();
            // foreach ($desiredGraduationLevel as $key => $value) {
            //     if($value['CourseName'] == 'Certificate/Diploma' || $value['CourseName'] == 'Certificate - Diploma'){
            //         unset($desiredGraduationLevel[$key]);
            //     }
            // }
            return $desiredGraduationLevel;
        }
        
        return array(
            'UG' => 'UG',
            'PG' => 'PG'
        );
    }
}
