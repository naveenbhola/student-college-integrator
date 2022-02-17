<?php

/**
 * File for user\Entities\tUserAttributes
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\tUserAttributes
 */
class UserAttributes
{

    /**
     * Field for id
     * @var integer $id
     */
    private $id;

    /**
     * Field for interestId
     * @var integer $userInterest
     */
    private $userInterest;

    /**
     * Field for attributeKey
     * @var integer $attributeKey
     */
    private $attributeKey;

    private $attributeValue;

    private $status;

    private $time;

    public function getId(){
      return $this->id;
    }

    /**
     * Get userId
     *
     * @return int 
     */
    
    public function setUserInterest(\user\Entities\UserInterest $userInterest = null){
        $this->userInterest = $userInterest;
        return $this;
    }

    /**
     * Get userInterest
     *
     * @return int 
     */
    public function getUserInterest(){
      return $this->userInterest;
    }

    /**
     * Set attributeKey
     *
     * @param integer $attributeKey
     * @return UserAttributes
     */
    public function setAttributeKey($attributeKey){
      $this->attributeKey = $attributeKey;
      return $this;
    }

    /**
     * Get attributeKey
     *
     * @return int 
     */
    public function getAttributeKey(){
      return $this->attributeKey;
    }

    /**
     * Set attributeValue
     *
     * @param integer $attributeValue
     * @return UserAttributes
     */
    public function setAttributeValue($attributeValue){
      $this->attributeValue = $attributeValue;
      return $this;
    }

    /**
     * Get attributeValue
     *
     * @return int 
     */
    public function getAttributeValue(){
      return $this->attributeValue;
    }

    public function setStatus($status){
      $this->status = $status;
      return $this;
    }

    public function getStatus(){
      return $this->status;
    }

    public function setTime($time){
      $this->time = $time;
      return $this;
    }

    public function getTime(){
      return $this->time;
    }

}

    