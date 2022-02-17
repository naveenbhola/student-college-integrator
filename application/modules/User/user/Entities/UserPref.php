<?php
/**
 * File for user\Entities\UserPref Entity
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserPref
 */
class UserPref
{
    /**
     * Field for degreePrefAICTE
     * @var string $degreePrefAICTE
     */
    private $degreePrefAICTE;

    /**
     * Field for degreePrefUGC
     * @var string $degreePrefUGC
     */
    private $degreePrefUGC;

    /**
     * Field for degreePrefInternational
     * @var string $degreePrefInternational
     */
    private $degreePrefInternational;

    /**
     * Field for degreePrefAny
     * @var string $degreePrefAny
     */
    private $degreePrefAny;

    /**
     * Field for modeOfEducationFullTime
     * @var string $modeOfEducationFullTime
     */
    private $modeOfEducationFullTime;

    /**
     * Field for modeOfEducationPartTime
     * @var string $modeOfEducationPartTime
     */
    private $modeOfEducationPartTime;

    /**
     * Field for modeOfEducationDistance
     * @var string $modeOfEducationDistance
     */
    private $modeOfEducationDistance;

    /**
     * Field for userFundsOwn
     * @var string $userFundsOwn
     */
    private $userFundsOwn;

    /**
     * Fields for User Funds Bank
     * @var string $userFundsBank
     */
    private $userFundsBank;
    
    /**
     * Fields for budget
     * @var int $budget
     */
    private $budget;
    
    /**
     * Fields for contactByConstultant
     * @var int $contactByConsultant
     */
    private $contactByConsultant;
    
    /**
     * Fields for userFundsNone
     * @var string $userFundsNone
     */
    private $userFundsNone;

    /**
     * Fields for timeofstart
     * @var datetime $timeOfStart
     */
    private $timeOfStart;

    /**
     * Fields for prefYear
     * @var datetime $prefYear
     */
    private $prefYear;

    /**
     * Fields for serDetail
     * @var string $userDetail
     */
    private $userDetail;

    /**
     * Fields for sourceInfo
     * @var string $sourceInfo
     */
    private $sourceInfo;

    /**
     * Fields for submitdate
     * @var datetime $submitDate
     */
    private $submitDate;

    /**
     * Fields for status
     * @var string $status
     */
    private $status;

    /**
     * Fields for desiredCourse
     * @var integer $desiredCourse
     */
    private $desiredCourse;
    
    /**
     * Fields for abroadSpecialization
     * @var integer $abroadSpecialization
     */
    private $abroadSpecialization;

    /**
     * Fields for extraFlag
     * @var string $extraFlag
     */
    private $extraFlag;

    /**
     * Fields for educationLevel
     * @var string $educationLevel
     */
    private $educationLevel;

    /**
     * Fields for suitableCallPref
     * @var string $suitableCallPref
     */
    private $suitableCallPref;

    /**
     * Fields for otherFundingDetails
     * @var string $otherFundingDetails
     */
    private $otherFundingDetails;

    /**
     * Field for isProcessed
     * @var string $isProcessed
     */
    private $isProcessed;

    /**
     * Field for prefId
     * @var integer $prefId
     */
    private $prefId;

    /**
     * Field for flow
     * @var string $flow
     */
    private $flow;


    /**
     * \Doctrine\Common\Collections\ArrayCollection
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $specializationPreferences;

    /**
     * \Doctrine\Common\Collections\ArrayCollection
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $testPrepSpecializationPreferences;

    /**
     * \Doctrine\Common\Collections\ArrayCollection
     * @var \Doctrine\Common\Collections\ArrayCollection
     */
    private $locationPreferences;

    /**
     * Entity user\Entities\User
     * @var user\Entities\User
     */
    private $user;

    /**
     * Constructor Function
     */ 
    public function __construct()
    {
        $this->specializationPreferences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->testPrepSpecializationPreferences = new \Doctrine\Common\Collections\ArrayCollection();
        $this->locationPreferences = new \Doctrine\Common\Collections\ArrayCollection();
    }
    
    /**
     * Set degreePrefAICTE
     *
     * @param string $degreePrefAICTE
     * @return UserPref
     */
    public function setDegreePrefAICTE($degreePrefAICTE)
    {
        $this->degreePrefAICTE = $degreePrefAICTE;
        return $this;
    }

    /**
     * Get degreePrefAICTE
     *
     * @return string 
     */
    public function getDegreePrefAICTE()
    {
        return $this->degreePrefAICTE;
    }

    /**
     * Set degreePrefUGC
     *
     * @param string $degreePrefUGC
     * @return UserPref
     */
    public function setDegreePrefUGC($degreePrefUGC)
    {
        $this->degreePrefUGC = $degreePrefUGC;
        return $this;
    }

    /**
     * Get degreePrefUGC
     *
     * @return string 
     */
    public function getDegreePrefUGC()
    {
        return $this->degreePrefUGC;
    }

    /**
     * Set degreePrefInternational
     *
     * @param string $degreePrefInternational
     * @return UserPref
     */
    public function setDegreePrefInternational($degreePrefInternational)
    {
        $this->degreePrefInternational = $degreePrefInternational;
        return $this;
    }

    /**
     * Get degreePrefInternational
     *
     * @return string 
     */
    public function getDegreePrefInternational()
    {
        return $this->degreePrefInternational;
    }

    /**
     * Set degreePrefAny
     *
     * @param string $degreePrefAny
     * @return UserPref
     */
    public function setDegreePrefAny($degreePrefAny)
    {
        $this->degreePrefAny = $degreePrefAny;
        return $this;
    }

    /**
     * Get degreePrefAny
     *
     * @return string 
     */
    public function getDegreePrefAny()
    {
        return $this->degreePrefAny;
    }

    /**
     * Set modeOfEducationFullTime
     *
     * @param string $modeOfEducationFullTime
     * @return UserPref
     */
    public function setModeOfEducationFullTime($modeOfEducationFullTime)
    {
        $this->modeOfEducationFullTime = $modeOfEducationFullTime;
        return $this;
    }

    /**
     * Get modeOfEducationFullTime
     *
     * @return string 
     */
    public function getModeOfEducationFullTime()
    {
        return $this->modeOfEducationFullTime;
    }

    /**
     * Set modeOfEducationPartTime
     *
     * @param string $modeOfEducationPartTime
     * @return UserPref
     */
    public function setModeOfEducationPartTime($modeOfEducationPartTime)
    {
        $this->modeOfEducationPartTime = $modeOfEducationPartTime;
        return $this;
    }

    /**
     * Get modeOfEducationPartTime
     *
     * @return string 
     */
    public function getModeOfEducationPartTime()
    {
        return $this->modeOfEducationPartTime;
    }

    /**
     * Set modeOfEducationDistance
     *
     * @param string $modeOfEducationDistance
     * @return UserPref
     */
    public function setModeOfEducationDistance($modeOfEducationDistance)
    {
        $this->modeOfEducationDistance = $modeOfEducationDistance;
        return $this;
    }

    /**
     * Get modeOfEducationDistance
     *
     * @return string 
     */
    public function getModeOfEducationDistance()
    {
        return $this->modeOfEducationDistance;
    }

    /**
     * Set userFundsOwn
     *
     * @param string $userFundsOwn
     * @return UserPref
     */
    public function setUserFundsOwn($userFundsOwn)
    {
        $this->userFundsOwn = $userFundsOwn;
        return $this;
    }

    /**
     * Get userFundsOwn
     *
     * @return string 
     */
    public function getUserFundsOwn()
    {
        return $this->userFundsOwn;
    }

    /**
     * Set userFundsBank
     *
     * @param string $userFundsBank
     * @return UserPref
     */
    public function setUserFundsBank($userFundsBank)
    {
        $this->userFundsBank = $userFundsBank;
        return $this;
    }

    /**
     * Get userFundsBank
     *
     * @return string 
     */
    public function getUserFundsBank()
    {
        return $this->userFundsBank;
    }

    /**
     * Set userFundsNone
     *
     * @param string $userFundsNone
     * @return UserPref
     */
    public function setUserFundsNone($userFundsNone)
    {
        $this->userFundsNone = $userFundsNone;
        return $this;
    }

    /**
     * Get userFundsNone
     *
     * @return string 
     */
    public function getUserFundsNone()
    {
        return $this->userFundsNone;
    }
    
    /**
     * Set budget
     *
     * @param int $budget
     * @return UserPref
     */
    public function setBudget($budget)
    {
        $this->budget = $budget;
        return $this;
    }

    /**
     * Get budget
     *
     * @return int 
     */
    public function getBudget()
    {
        return $this->budget;
    }

    /**
     * Set contactByConsultant
     *
     * @param string $contactByConsultant
     * @return UserPref
     */
    public function setContactByConsultant($contactByConsultant)
    {
        $this->contactByConsultant = $contactByConsultant;
        return $this;
    }

    /**
     * Get contactByConsultant
     *
     * @return string 
     */
    public function getContactByConsultant()
    {
        return $this->contactByConsultant;
    }

    /**
     * Set timeOfStart
     *
     * @param datetime $timeOfStart
     * @return UserPref
     */
    public function setTimeOfStart($timeOfStart)
    {
        $this->timeOfStart = $timeOfStart;
        return $this;
    }

    /**
     * Get timeOfStart
     *
     * @return datetime 
     */
    public function getTimeOfStart()
    {
        return $this->timeOfStart;
    }

    /**
     * Set preferredYearOfAdmission
     *
     * @param datetime $prefYear
     * @return UserPref
     */
    public function setPrefYear($PrefYear)
    {
        if(empty($PrefYear)){
            $PrefYear = NULL;
        }
        $this->prefYear = $PrefYear;
        return $this;
    }

    /**
     * Get preferredYearOfAdmission
     *
     * @return datetime 
     */
    public function getPrefYear()
    {
        return $this->prefYear;
    }


    /**
     * Set userDetail
     *
     * @param string $userDetail
     * @return UserPref
     */
    public function setUserDetail($userDetail)
    {
        $this->userDetail = $userDetail;
        return $this;
    }

    /**
     * Get userDetail
     *
     * @return string 
     */
    public function getUserDetail()
    {
        return $this->userDetail;
    }

    /**
     * Set sourceInfo
     *
     * @param string $sourceInfo
     * @return UserPref
     */
    public function setSourceInfo($sourceInfo)
    {
        $this->sourceInfo = $sourceInfo;
        return $this;
    }

    /**
     * Get sourceInfo
     *
     * @return string 
     */
    public function getSourceInfo()
    {
        return $this->sourceInfo;
    }

    /**
     * Set submitDate
     *
     * @param datetime $submitDate
     * @return UserPref
     */
    public function setSubmitDate($submitDate)
    {
        $this->submitDate = $submitDate;
        return $this;
    }

    /**
     * Get submitDate
     *
     * @return datetime 
     */
    public function getSubmitDate()
    {
        return $this->submitDate;
    }

    /**
     * Set status
     *
     * @param string $status
     * @return UserPref
     */
    public function setStatus($status)
    {
        $this->status = $status;
        return $this;
    }

    /**
     * Get status
     *
     * @return string 
     */
    public function getStatus()
    {
        return $this->status;
    }

    /**
     * Set desiredCourse
     *
     * @param integer $desiredCourse
     * @return UserPref
     */
    public function setDesiredCourse($desiredCourse)
    {
        $this->desiredCourse = $desiredCourse;
        return $this;
    }

    /**
     * Get desiredCourse
     *
     * @return integer 
     */
    public function getDesiredCourse()
    {
        return $this->desiredCourse;
    }
    
    /**
     * Set abroadSpecialization
     *
     * @param integer $abroadSpecialization
     * @return UserPref
     */
    public function setAbroadSpecialization($abroadSpecialization)
    {
        $this->abroadSpecialization = $abroadSpecialization;
        return $this;
    }

    /**
     * Get abroadSpecialization
     *
     * @return integer 
     */
    public function getAbroadSpecialization()
    {
        return $this->abroadSpecialization;
    }

    /**
     * Set extraFlag
     *
     * @param string $extraFlag
     * @return UserPref
     */
    public function setExtraFlag($extraFlag)
    {
        $this->extraFlag = $extraFlag;
        return $this;
    }

    /**
     * Get extraFlag
     *
     * @return string 
     */
    public function getExtraFlag()
    {
        return $this->extraFlag;
    }

    /**
     * Set educationLevel
     *
     * @param string $educationLevel
     * @return UserPref
     */
    public function setEducationLevel($educationLevel)
    {
        $this->educationLevel = $educationLevel;
        return $this;
    }

    /**
     * Get educationLevel
     *
     * @return string 
     */
    public function getEducationLevel()
    {
        return $this->educationLevel;
    }

    /**
     * Set suitableCallPref
     *
     * @param string $suitableCallPref
     * @return UserPref
     */
    public function setSuitableCallPref($suitableCallPref)
    {
        $this->suitableCallPref = $suitableCallPref;
        return $this;
    }

    /**
     * Get suitableCallPref
     *
     * @return string 
     */
    public function getSuitableCallPref()
    {
        return $this->suitableCallPref;
    }

    /**
     * Set otherFundingDetails
     *
     * @param string $otherFundingDetails
     * @return UserPref
     */
    public function setOtherFundingDetails($otherFundingDetails)
    {
        $this->otherFundingDetails = $otherFundingDetails;
        return $this;
    }

    /**
     * Get otherFundingDetails
     *
     * @return string 
     */
    public function getOtherFundingDetails()
    {
        return $this->otherFundingDetails;
    }

    /**
     * Set isProcessed
     *
     * @param string $isProcessed
     * @return UserPref
     */
    public function setIsProcessed($isProcessed)
    {
        $this->isProcessed = $isProcessed;
        return $this;
    }

    /**
     * Get isProcessed
     *
     * @return string 
     */
    public function getIsProcessed()
    {
        return $this->isProcessed;
    }

    /**
     * Get prefId
     *
     * @return integer 
     */
    public function getPrefId()
    {
        return $this->prefId;
    }

    public function getFlow(){
        return $this->flow;
    }

    public function setFlow($flow){
        $this->flow = $flow;
    }

    /**
     * Add specializationPreferences
     *
     * @param user\Entities\UserSpecializationPref $specializationPreferences
     * @return UserPref
     */
    public function addUserSpecializationPref(\user\Entities\UserSpecializationPref $specializationPreferences)
    {
        $this->specializationPreferences[] = $specializationPreferences;
        return $this;
    }

    /**
     * Get specializationPreferences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getSpecializationPreferences()
    {
        return $this->specializationPreferences;
    }

    /**
     * Add testPrepSpecializationPreferences
     *
     * @param user\Entities\UserTestPrepSpecializationPref $testPrepSpecializationPreferences
     * @return UserPref
     */
    public function addUserTestPrepSpecializationPref(\user\Entities\UserTestPrepSpecializationPref $testPrepSpecializationPreferences)
    {
        $this->testPrepSpecializationPreferences[] = $testPrepSpecializationPreferences;
        return $this;
    }

    /**
     * Update testPrepSpecializationPreferences
     *
     * @param user\Entities\UserTestPrepSpecializationPref $testPrepSpecializationPreferences
     * @return UserPref
     */
    public function updateUserTestPrepSpecializationPref($testPrepSpecializationPreferences)
    {
        $this->testPrepSpecializationPreferences =  $testPrepSpecializationPreferences;
    }
    
    /**
     * Get testPrepSpecializationPreferences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getTestPrepSpecializationPreferences()
    {
        return $this->testPrepSpecializationPreferences;
    }

    /**
     * Add locationPreferences
     *
     * @param user\Entities\UserLocationPref $locationPreferences
     * @return UserPref
     */
    public function addUserLocationPref(\user\Entities\UserLocationPref $locationPreferences)
    {
        $this->locationPreferences[] = $locationPreferences;
        return $this;
    }

    /**
     * Get locationPreferences
     *
     * @return Doctrine\Common\Collections\Collection 
     */
    public function getLocationPreferences()
    {
        return $this->locationPreferences;
    }

    /**
     * Set user
     *
     * @param user\Entities\User $user
     * @return UserPref
     */
    public function setUser(\user\Entities\User $user = null)
    {
        $this->user = $user;
        return $this;
    }

    /**
     * Get user
     *
     * @return user\Entities\User 
     */
    public function getUser()
    {
        return $this->user;
    }
}