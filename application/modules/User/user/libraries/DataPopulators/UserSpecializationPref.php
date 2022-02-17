<?php
/**
 * File for UserSpecializationPref entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserSpecializationPref entity
 */ 
class UserSpecializationPref extends AbstractPopulator
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
     * Populate data into UserSpecializationPref entity
     *
     * @param object $specializationPref \user\Entities\UserSpecializationPref
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserSpecializationPref $specializationPref,$data = array())
    {
        $this->setData($data);
        
        $specializationPref->setSpecializationId($data['specializationId']);
        $specializationPref->setStatus('live');
        $specializationPref->setSubmitDate(new \DateTime);
    }
}