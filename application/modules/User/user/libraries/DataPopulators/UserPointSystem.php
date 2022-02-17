<?php
/**
 * Populator class file for UserPointSystem entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserPointSystem entity
 */ 
class UserPointSystem extends AbstractPopulator
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
     * Populate data into UserPointSystem entity
     *
     * @param object $userPointSystem \user\Entities\UserPointSystem
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserPointSystem $userPointSystem,$data = array())
    {
        $this->setData($data);
        $userPointSystem->setUserPoints(100);
    }
}