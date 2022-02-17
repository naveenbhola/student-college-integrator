<?php
/**
 * File for Populator class for UserAttributes entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserAttributes entity
 */ 
class UserAttributes extends AbstractPopulator
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
     * Populate data into UserAttributes entity
     *
     * @param object $UserAttributes \user\Entities\UserAttributes
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserAttributes $userAttributes, $attrKey, $attrValue)
    {
        $this->setData($data);
        if(isset($userAttributes) && gettype($userAttributes) == 'object') {



            $data['attributeKey'] = 10;
            $data['attributeValue'] = 11;
            if(isset($attrKey)){
                $userAttributes->setAttributeKey($attrKey);
            }
            if(isset($attrValue)){
                $userAttributes->setAttributeValue($attrValue);
            }
            $userAttributes->setStatus('live');
            $userAttributes->setTime(new \DateTime());
	   }

    }
}
