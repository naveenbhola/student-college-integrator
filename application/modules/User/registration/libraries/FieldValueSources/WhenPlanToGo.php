<?php
/**
 * File for Value source for when plan to go field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for when plan to go field
 */ 
class WhenPlanToGo extends AbstractValueSource
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
	    if(date('m',strtotime('now')) > 9) {
		return array(
			    'in1Year' => date('Y',strtotime('+1 year')),
			    'in2Years' => date('Y',strtotime('+2 year')),
			    'later' => 'Later'
			);
	    }
	    else {
		return array(
			    'thisYear' => date('Y',strtotime('+0 year')),
			    'in1Year' => date('Y',strtotime('+1 year')),
			    'later' => 'Later'
			);
	    }
	}
	else {
	    return array(
			    'thisYear' => date('Y',strtotime('+0 year')),
			    'in1Year' => date('Y',strtotime('+1 year')),
			    'in2Years' => date('Y',strtotime('+2 year')),
			    'later' => 'Later'
			);
	}
    }
}