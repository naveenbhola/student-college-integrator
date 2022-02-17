<?php

/**
 * File for user\Entities\tUserCourseSpecialization
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\tUserCourseSpecialization
 */
class UserCourseSpecialization
{

    /**
     * Field for id
     * @var integer $id
     */
    private $id;

    /**
     * Field for interestId
     * @var integer $interestId
     */
    private $userInterest;

    /**
     * Field for specializationId
     * @var integer $specializationId
     */
    private $specializationId;

    private $baseCourseId;

    private $courseLevel;

    private $status;

    private $time;

    public function getId(){
      return $this->id;
    }

    /**
     * Get interestId
     *
     * @return int 
     */
    public function getUserInterest(){
      return $this->userInterest;
    }

    public function setUserInterest(\user\Entities\setUserInterest $userInterest = null){
        $this->userInterest = $userInterest;
        return $this;
    }

    /**
     * Set specializationId
     *
     * @param integer $specializationId
     * @return UserCourseSpecialization
     */
    public function setSpecializationId($specializationId){
      $this->specializationId = $specializationId;
      return $this;
    }

    /**
     * Get specializationId
     *
     * @return int 
     */
    public function getSpecializationId(){
      return $this->specializationId;
    }

    /**
     * Set courseId
     *
     * @param integer $courseId
     * @return UserCourseSpecialization
     */
    public function setBaseCourseId($baseCourseId){
      $this->baseCourseId = $baseCourseId;
      return $this;
    }

    /**
     * Get courseId
     *
     * @return int 
     */
    public function getBaseCourseId(){
      return $this->baseCourseId;
    }

    public function setStatus($status){
      $this->status = $status;
      return $this;
    }

    public function getCourseLevel(){
      return $this->courseLevel;
    }

    public function setCourseLevel($courseLevel){
      $this->courseLevel = $courseLevel;
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

    