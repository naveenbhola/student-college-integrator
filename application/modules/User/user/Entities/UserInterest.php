<?php

/**
 * File for user\Entities\tUserInterest
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\tUserInterest
 */
class UserInterest
{

    /**
     * Field for interestId
     * @var integer $interestId
     */
    private $interestId;

    private $user;

    /**
     * Field for userId
     * @var integer $userId
     */
    private $userId;

    /**
     * Field for streamId
     * @var integer $streamId
     */
    private $streamId;

    /**
     * Field for subStreamId
     * @var integer $subStreamId
     */
    private $subStreamId;

    private $status;
    
    private $time;

    /**
    * \Doctrine\Common\Collections\ArrayCollection
    * @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $userAttributes;

    /**
    * \Doctrine\Common\Collections\ArrayCollection
    * @var \Doctrine\Common\Collections\ArrayCollection
    */
    private $userCourseSpecialization;


    /**
     * Constructor Function
     */ 
    function __contruct(){
        $this->userCourseSpecialization = new \Doctrine\Common\Collections\ArrayCollection();
        $this->userAttributes = new \Doctrine\Common\Collections\ArrayCollection();
    }

     /**
     * Add userCourseSpecialization
     *
     * @param user\Entities\UserCourseSpecialization $userCourseSpecialization
     * @return UserInterest
     */
    public function addUserCourseSpecialization(\user\Entities\UserCourseSpecialization $userCourseSpecialization)
    {
        $this->userCourseSpecialization[] = $userCourseSpecialization;
        return $this;
    }

    /**
     * Get userCourseSpecialization
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUserCourseSpecialization()
    {
        return $this->userCourseSpecialization;
    }


    /**
     * Add UserAttributes
     *
     * @param user\Entities\UserAttributes $userAttributes
     * @return UserInterest
     */
    public function addUserAttributes(\user\Entities\UserAttributes $userAttributes)
    {
        $this->userAttributes[] = $userAttributes;
        return $this;
    }

    /**
     * Get userAttributes
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getUserAttributes()
    {
        return $this->userAttributes;
    }

    /**
     * Get interestId
     *
     * @return int 
     */
    public function getInterestId(){
      return $this->interestId;
    }

    public function setUser(\user\Entities\User $user = null){
        $this->user = $user;
        return $this;
    }

    /**
     * Set userId
     *
     * @param integer $userId
     * @return UserCourseSpecialization
     */
    public function setUserId($userId){
      $this->userId = $userId;
      return $this;
    }

    /**
     * Get userId
     *
     * @return int 
     */
    public function getUserId(){
      return $this->userId;
    }

    /**
     * Set streamId
     *
     * @param integer $streamId
     * @return UserCourseSpecialization
     */
    public function setStreamId($streamId){
      $this->streamId = $streamId;
      return $this;
    }

    /**
     * Get streamId
     *
     * @return int 
     */
    public function getStreamId(){
      return $this->streamId;
    }

    /**
     * Set subStreamId
     *
     * @param integer $subStreamId
     * @return UserCourseSpecialization
     */
    public function setSubStreamId($subStreamId){
      $this->subStreamId = $subStreamId;
      return $this;
    }

    /**
     * Get subStreamId
     *
     * @return int 
     */
    public function getSubStreamId(){
      return $this->subStreamId;
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

    