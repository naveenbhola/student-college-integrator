<?php
/**
 * File for Populator class for UserInterest entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserInterest entity
 */ 
class UserInterest extends AbstractPopulator
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
     * Populate data into UserInterest entity
     *
     * @param object $UserInterest \user\Entities\UserInterest
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserInterest $userInterest,$data = array())
    {
        $this->setData($data);
        if(isset($userInterest) && gettype($userInterest) == 'object') {

            if($data['subStream'] == 'ungrouped'){
                $data['subStream'] = 0;
            }

            if(isset($data['stream'])){
                $userInterest->setStreamId($data['stream']);
            }
            if(!empty($data['subStream'])){
                $userInterest->setSubStreamId($data['subStream']);
            }

            $userInterest->setStatus('live');
            $userInterest->setTime(new \DateTime());
	   }

    }
}
