<?php
/**
 * File for Populator class for UserTestPrepSpecializationPref entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserTestPrepSpecializationPref entity
 */ 
class UserTestPrepSpecializationPref extends AbstractPopulator
{
    /**
     * Constructor
     *
     * @param string $mode create|update
     */
    function __construct($mode = 'create')
    {
        parent::__construct($mode);
    }
    
    /**
     * Populate data into UserTestPrepSpecializationPref entity
     *
     * @param object $specializationPref \user\Entities\UserTestPrepSpecializationPref
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserTestPrepSpecializationPref $specializationPref,$data = array())
    {
        $this->setData($data);
        
        if(isset($specializationPref) && gettype($specializationPref) == 'object') {
		$specializationPref->setSpecializationId($data['specializationId']);
		$specializationPref->setStatus('live');
		$specializationPref->setUpdateTime(new \DateTime);
	}
    }
}
