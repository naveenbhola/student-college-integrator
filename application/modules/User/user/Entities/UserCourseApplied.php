<?php

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;


class UserCourseApplied
{

    private $id;

    private $user;

    private $courseId;

    private $courseName;

    private $courseCategory;
    
    private $courseSubCategory;

    private $LDBCourseId;

    private $universityName;

    private $scholarshipReceived;

    private $scholarshipDetails;

    private $applicationAccepted;
    
    private $AdmissionTaken;

    private $timeOfAdmission;

    private $reasonsForRejection;

    function getId(){
        return $this->id;
    }

    function setCourseId($courseId){
        $this->courseId = $courseId;
        return $this;
    }

    function getCourseId(){
        return $this->courseId;
    }

    function setCourseName($courseName){
        $this->courseName = $courseName;
        return $this;
    }

    function getcourseName(){
        return $this->courseName;
    }

    function setCourseCategory($courseCategory){
        $this->courseCategory = $courseCategory;
        return $courseCategory;
    }

    function getcourseCategory(){
        return $this->courseCategory;
    }

    function setCourseSubCategory($courseSubCategory){
        $this->courseSubCategory = $courseSubCategory;
        return $this;
    }

    function getCourseSubCategory(){
        return $this->courseSubCategory;
    }

    function setLDBCourseId($LDBCourseId){
        $this->LDBCourseId = $LDBCourseId;
        return $this;
    }

    function getLDBCourseId(){
        return $this->LDBCourseId;
    }

    function setUniversityName($universityName){
        $this->universityName = $universityName;
        return $this;
    }
    function getUniversityName(){
        return $this->universityName;
    }

    function setScholarshipReceived($scholarshipReceived){
        $this->scholarshipReceived= $scholarshipReceived;
        return $this;
    }

    function getScholarshipReceived(){
        return $this->scholarshipReceived;
    }

    function setScholarshipDetails($scholarshipDetails){
        $this->scholarshipDetails = $scholarshipDetails;
        return $this;
    }

    function getScholarshipDetails(){
        return $this->scholarshipDetails;
    }

    function setApplicationAccepted($applicationAccepted){
        $this->applicationAccepted = $applicationAccepted;
        return $this;
    }

    function getApplicationAccepted(){
        return $this->applicationAccepted;
    }

    function setAdmissionTaken($AdmissionTaken){
        $this->AdmissionTaken = $AdmissionTaken;
        return $this;
    }

    function getAdmissionTaken(){
        return $this->AdmissionTaken;
    }

    function setTimeOfAdmission($timeOfAdmission){
        $this->timeOfAdmission= $timeOfAdmission;
        return $this;
    }

    function getTimeOfAdmission(){
        return $this->timeOfAdmission;
    }

    function setReasonsForRejection($reasonsForRejection){
        $this->reasonsForRejection = $reasonsForRejection;
        return $this;
    }

    function getReasonsForRejection(){
        return $this;
    }

    public function setUser(\user\Entities\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    public function getUser()
    {
        return $this->user;
    }

}