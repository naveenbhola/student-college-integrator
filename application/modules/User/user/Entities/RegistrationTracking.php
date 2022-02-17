<?php

/**
 * File for user\Entities\RegistrationTracking
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\RegistrationTracking
 */
class RegistrationTracking
{
	private $id;

    private $user;

    private $desiredCourse;

    private $categoryId;

    private $subCatId;

    private $blogId;

    private $city;

    private $country;

    private $prefCountry1;

    private $prefCountry2;

    private $prefCountry3;

    private $source;

    private $userType;

    private $isNewReg;

    private $submitDate;

    private $submitTime;

    private $trackingkeyId;

    private $visitorSessionId;

    private $referer;

    private $pageReferer; 
     
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

    public function setDesiredCourse($desiredCourse){
        $this->desiredCourse = $desiredCourse;
        return $this;
    }

    public function getDesiredCourse(){
        return $this->desiredCourse;
    }

    public function setCategoryId($categoryId){
        $this->categoryId = $categoryId;
        return $this;
    }

    public function getCategoryId(){
        return $this->categoryId;
    }

    public function setSubCatId($subCatId){
        $this->subCatId = $subCatId;
        return $this;
    }

    public function getSubCatId(){
        return $this->subCatId;
    }

    public function setBlogId($blogId){
        $this->blogId = $blogId;
        return $this;
    }

    public function getBlogId(){
        return $this->blogId;
    }

    public function setCity($city){
        $this->city = $city;
        return $this;
    }

    public function getCity(){
        return $this->city;
    }

    public function setCountry($country){
        $this->country = $country;
        return $this;
    }

    public function getCountry(){
        return $this->country;
    }

    public function setPrefCountry1($prefCountry1){
        $this->prefCountry1 = $prefCountry1;
        return $this;
    }

    public function getPrefCountry1(){
        return $this->prefCountry1;
    }

    public function setPrefCountry2($prefCountry2){
        $this->prefCountry2 = $prefCountry2;
        return $this;
    }

    public function getPrefCountry2(){
        return $this->prefCountry1;
    }

    public function setPrefCountry3($prefCountry3){
        $this->prefCountry3 = $prefCountry3;
        return $this;
    }

    public function getPrefCountry3(){
        return $this->prefCountry3;
    }

    public function setSource($source){
        $this->source = $source;
        return $this;
    }

    public function getSource(){
        return $this->source;
    }

    public function setUserType($userType){
        $this->userType = $userType;
        return $this;
    }

    public function getUserType(){
        return $this->userType;
    }

    public function setIsNewReg($isNewReg){
        $this->isNewReg = $isNewReg;
        return $this;
    }

    public function getIsNewReg(){
        return $this->isNewReg;
    }

    public function setSubmitDate($submitDate){
        $this->submitDate = $submitDate;
        return $this;
    }

    public function getSubmitDate(){
        return $this->submitDate;
    }

    public function setSubmitTime($submitTime){
        $this->submitTime = $submitTime;
        return $this;
    }

    public function getSubmitTime(){
        return $this->submitTime;
    }

    public function setTrackingkeyId($trackingkeyId){
        $this->trackingkeyId = $trackingkeyId;
        return $this;
    }

    public function getTrackingkeyIde(){
        return $this->trackingkeyId;
    }

    public function setVisitorSessionId($visitorSessionId){
        $this->visitorSessionId = $visitorSessionId;
        return $this;
    }

    public function getVisitorSessionId(){
        return $this->visitorSessionId;
    }

    public function setReferer($referer){
        $this->referer = $referer;
        return $this;
    }

    public function getReferer(){
        return $this->referer;
    }

    public function setPageReferer($pageReferer){
        $this->pageReferer = $pageReferer;
        return $this;
    }

    public function getPageReferer(){
        return $this->pageReferer;
    }
}
?>