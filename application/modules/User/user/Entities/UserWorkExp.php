<?php

/**
 * File for user\Entities\UserWorkExp
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserWorkExp
 */
class UserWorkExp
{
  
    private $id;

    private $user;

    private $employer;

    private $designation;

    private $department;

    private $startDate;

    private $endDate;

    private $currentJob;

    private $bio;

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

    public function setEmployer($employer){
		$this->employer = $employer;
		return $this;
    }

    public function getEmployer(){
    		return $this->employer;
    }

    public function setDesignation($designation){
    		$this->designation = $designation;
    		return $this;
    }

    public function getDesignation(){
    		return $this->designation;
    }

    public function setDepartment($department){
    		$this->department = $department;
    		return $this;
    }

    public function getDepartment(){
    		return $this->department;
    }

    public function setStartDate($startDate){
    		$this->startDate = $startDate;
    		return $this;
    }

    public function getStartDate(){
    		return $this->startDate;
    }

    public function setEndDate($endDate){
     	$this->endDate = $endDate;
     	return $this;
    }

    public function getEndDate(){
    		return $this->endDate;
    }

    public function setCurrentJob($currentJob){
    		$this->currentJob = $currentJob;
    		return $this;
    }

    public function getCurrentJob(){
    		return $this->currentJob;
    }   
}