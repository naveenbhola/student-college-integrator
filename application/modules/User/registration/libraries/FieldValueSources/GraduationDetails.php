<?php
/**
 * File for Value source for graduation details field
 */ 
namespace registration\libraries\FieldValueSources;

/**
 * Value source for graduation details field
 */ 
class GraduationDetails extends AbstractValueSource
{
    /**
	 * Get values
	 *
	 * @param array $params Additional parameters
	 * @return array
	 */ 
    public function getValues($params = array())
    {
        $graduationDetails = array(
										'B.A.',
										'B.A.(Hons)',
										'B.Sc',
										'B.Sc(Gen)',
										'B.Sc(Hons)',
										'B.E./B.Tech',
										'B.Des',
										'B.Com',
										'BBA/BBM/BBS',
										'B.Ed',
										'BCA/BCM',
										'BVSc',
										'BHM',
										'BJMC',
										'BDS',
										'B.Pharma',
										'B.Arch',
										'MBBS',
										'LLB',
										'Diploma'
									);
		return array_combine($graduationDetails,$graduationDetails);
    }
}