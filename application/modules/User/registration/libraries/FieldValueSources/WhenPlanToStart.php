<?php

/**
 * File for Value source for when plan to start field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for when plan to start field
 */ 
class WhenPlanToStart extends AbstractValueSource
{
	/**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
		$form = $params['form'];
		$groupType = $form->getGroupType();
		
		if($groupType == 'localUG' || $groupType == 'localPG' || $groupType == 'localUGSpecial' || $groupType == 'localPGSpecial'  || $groupType == 'localMBA') {
			return array(
							'immediately' => "Immediately",
							'within2Months' => "Within 2 Months",
							'within3Months' => "Within 3 Months",
							'notSure' => 'Not Sure'
			);
		}
		else {
			return array(
							'thisYear' => date('Y',strtotime('+0 year')),
							'nextYear' => date('Y',strtotime('+1 year')),
							'nextToNextYear' => date('Y',strtotime('+2 year')),
							'notSure' => 'Not Sure'
			);	
		}
    }
}