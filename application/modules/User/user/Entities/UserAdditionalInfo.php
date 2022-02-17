<?php

/**
 * File for user\Entities\UserAdditionalInfo
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserAdditionalInfo
 */
class UserAdditionalInfo
{


    private $id;

    private $user;

    private $currentClass;

    private $currentSchool;

    private $bookedExamDate;
	
    private $gradUniversity;

    private $gradCollege;

    private $extracurricular;

    private $specialConsiderations;

    private $preferences;

    private $aboutMe;

    private $bio;

    private $studentEmail;

    private $maritalStatus;

    private $employmentStatus;

    /**
     * @return mixed
     */
    public function getEmploymentStatus()
    {
        return $this->employmentStatus;
    }

    /**
     * @param mixed $employmentStatus
     */
    public function setEmploymentStatus($employmentStatus)
    {
        $this->employmentStatus = $employmentStatus;
    }

    /**
     * @return mixed
     */
    public function getMaritalStatus()
    {
        return $this->maritalStatus;
    }

    /**
     * @param mixed $maritalStatus
     */
    public function setMaritalStatus($maritalStatus)
    {
        $this->maritalStatus = $maritalStatus;
    }

    public function getId(){
      return $this->id;
    }

    public function setUser(\user\Entities\User $user = null){
        $this->user = $user;
        return $this;
    }

    public function getUser(){
        return $this->user;
    }

    public function setCurrentClass($currentClass){
      $this->currentClass = $currentClass;
      return $this;
    }
	
	public function setCurrentSchool($currentSchool){
      $this->currentSchool = $currentSchool;
      return $this;
    }
	
	public function setBookedExamDate($bookedExamDate){
      $this->bookedExamDate = $bookedExamDate;
      return $this;
    }
	
   public function setGradUniversity($gradUniversity){
   		$this->gradUniversity = $gradUniversity;
   		return $this;
	}

   public function getCurrentClass(){
      return $this->currentClass;
   }
   
   public function getCurrentSchool(){
      return $this->currentSchool;
   }
   
   public function getBookedExamDate(){
      return $this->bookedExamDate;
   }

   public function getGradUniversity(){
   		return $this->gradUniversity;
   }

   public function setGradCollege($gradCollege){
   		$this->gradCollege = $gradCollege;
   		return $this;
   }

   public function getGradCollege(){
   		return $this->gradCollege;
   }

   public function setExtracurricular($extracurricular){
   		$this->extracurricular = $extracurricular;
   		return $this;
   }

   public function getExtracurricular(){
   		return $this->extracurricular;
   }

   public function setSpecialConsiderations($specialConsiderations){
   		$this->specialConsiderations = $specialConsiderations;
   		return $this;
   }

   public function getSpecialConsiderations(){
   		return $this->specialConsiderations;
   }

   public function setPreferences($preferences){
	   	$this->preferences = $preferences;
	   	return $this;
   }

   public function getPreferences(){
   		return $this->preferences;
   }

   public function setAboutMe($aboutMe){
   		$this->aboutMe = strip_tags($aboutMe);
   		return $this;
   }

   public function getAboutMe(){
   		return $this->aboutMe;
   }

   public function setBio($bio){
      $this->bio = strip_tags($bio);
      return $this;
   }

   public function getBio(){
      return $this->bio;
   }

   public function getStudentEmail(){
      return $this->studentEmail;
   }

   public function setStudentEmail($studentEmail){
      $this->studentEmail = $studentEmail;
      return $this;
   }
}

    