<?php
/**
 * File for user\Entities\UserEducation Entity
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserEducation
 */
class UserEducation
{
    /**
     * Field for name
     * @var string $name
     */
    private $name;

    /**
     * Field for Institue ID
     * @var integer $instituteId
     */
    private $instituteId;

    /**
     * Field for Level
     * @var string $level
     */
    private $level;

    /**
     * Field for Marks
     * @var integer $marks
     */
    private $marks;

    /**
     * Field for MarksType
     * @var string $marksType
     */
    private $marksType;

    /**
     * Field for Course Completion Date
     * @var datetime $courseCompletionDate
     */
    private $courseCompletionDate;

    /**
     * Field for Course Specialization
     * @var integer $courseSpecialization
     */
    private $courseSpecialization;

    /**
     * Field for Ongoing Completed Flag
     * @var string $ongoingCompletedFlag
     */
    private $ongoingCompletedFlag;

    /**
     * Field for city
     * @var integer $city
     */
    private $city;

    /**
     * Field for Country
     * @var integer $country
     */
    private $country;

    /**
     * Field for Submit Date
     * @var datetime $submitDate
     */
    private $submitDate;

    /**
     * Field for status
     * @var string $status
     */
    private $status;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Field for user
     * @var user\Entities\User
     */
    private $user;
 
    /**
     * Field for board
     */
    private $board;

    /**
     * Field for subjects
     */
    private $subjects;


    /**
     * Field for instituteName
     */
    private $instituteName;

    /**
     * Field for specialization
     */
    private $specialization;

    private $examGroupId;

    /**
     * Set name
     *
     * @param string $name
     * @return UserEducation
     * 
     */

    public function setExamGroupId($examGroupId){
        $this->examGroupId = $examGroupId;
        return $this;
    }

    public function getExamGroupId(){
        return $this->examGroupId;
    }

    public function setName($name)
    {
        $this->name = $name;
        return $this;
    }

    /**
     * Get name
     *
     * @return string 
     */
    public function getName()
    {
        return $this->name;
    }

    /**
     * Set instituteId
     *
     * @param integer $instituteId
     * @return UserEducation
     */
    public function setInstituteId($instituteId)
    {
        $this->instituteId = $instituteId;
        return $this;
    }

    /**
     * Get instituteId
     *
     * @return integer 
     */
    public function getInstituteId()
    {
        return $this->instituteId;
    }

    /**
     * Set level
     *
     * @param string $level
     * @return UserEducation
     */
    public function setLevel($level)
    {
        $this->level = $level;
        return $this;
    }

    /**
     * Get level
     *
     * @return string 
     */
    public function getLevel()
    {
        return $this->level;
    }

    /**
     * Set marks
     *
     * @param integer $marks
     * @return UserEducation
     */
    public function setMarks($marks)
    {
        $this->marks = $marks;
        return $this;
    }

    /**
     * Get marks
     *
     * @return integer 
     */
    public function getMarks()
    {
        return $this->marks;
    }

    /**
     * Set marksType
     *
     * @param string $marksType
     * @return UserEducation
     */
    public function setMarksType($marksType)
    {
        $this->marksType = $marksType;
        return $this;
    }

    /**
     * Get marksType
     *
     * @return string 
     */
    public function getMarksType()
    {
        return $this->marksType;
    }

    /**
     * Set courseCompletionDate
     *
     * @param datetime $courseCompletionDate
     * @return UserEducation
     */
    public function setCourseCompletionDate($courseCompletionDate)
    {
        $this->courseCompletionDate = $courseCompletionDate;
        return $this;
    }

    /**
     * Get courseCompletionDate
     *
     * @return datetime 
     */
    public function getCourseCompletionDate()
    {
        return $this->courseCompletionDate;
    }

    /**
     * Set courseSpecialization
     *
     * @param integer $courseSpecialization
     * @return UserEducation
     */
    public function setCourseSpecialization($courseSpecialization)
    {
        $this->courseSpecialization = $courseSpecialization;
        return $this;
    }

    /**
     * Get courseSpecialization
     *
     * @return integer 
     */
    public function getCourseSpecialization()
    {
        return $this->courseSpecialization;
    }

    /**
     * Set ongoingCompletedFlag
     *
     * @param string $ongoingCompletedFlag
     * @return UserEducation
     */
    public function setOngoingCompletedFlag($ongoingCompletedFlag)
    {
        $this->ongoingCompletedFlag = $ongoingCompletedFlag;
        return $this;
    }

    /**
     * Get ongoingCompletedFlag
     *
     * @return string 
     */
    public function getOngoingCompletedFlag()
    {
        return $this->ongoingCompletedFlag;
    }

    /**
     * Set city
     *
     * @param integer $city
     * @return UserEducation
     */
    public function setCity($city)
    {
        $this->city = $city;
        return $this;
    }

    /**
     * Get city
     *
     * @return integer 
     */
    public function getCity()
    {
        return $this->city;
    }

    /**
     * Set country
     *
     * @param integer $country
     * @return UserEducation
     */
    public function setCountry($country)
    {
        $this->country = $country;
        return $this;
    }

    /**
     * Get country
     *
     * @return integer 
     */
    public function getCountry()
    {
        return $this->country;
    }

    /**
     * Set submitDate
     *
     * @param datetime $submitDate
     * @return UserEducation
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
     * @return UserEducation
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
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set user
     *
     * @param user\Entities\User $user
     * @return UserEducation
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

        /**
     * Set board
     *
     * @param string $name
     * @return UserEducation
     * 
     */
    public function setBoard($board)
    {
        $this->board = $board;
        return $this;
    }

    /**
     * Get board
     *
     * @return string 
     */
    public function getBoard()
    {
        return $this->board;
    }

    /**
     * Set instituteName
     *
     * @param string $instituteName
     * @return UserEducation
     * 
     */
    public function setInstituteName($instituteName)
    {
        $this->instituteName = $instituteName;
        return $this;
    }

    /**
     * Get instituteName
     *
     * @return string 
     */
    public function getInstituteName()
    {
        return $this->instituteName;
    }

     /**
     * Set specialization
     *
     * @param string $specialization
     * @return UserEducation
     * 
     */
    public function setSpecialization($specialization)
    {
        $this->specialization = $specialization;
        return $this;
    }

    /**
     * Get specialization
     *
     * @return string 
     */
    public function getSpecialization()
    {
        return $this->specialization;
    }

    /**
     * Set subject
     *
     * @param string $subjects
     * @return UserEducation
     * 
     */
    public function setSubjects($subjects)
    {
        $this->subjects = $subjects;
        return $this;
    }

    /**
     * Get subject
     *
     * @return string 
     */
    public function getSubjects()
    {
        return $this->subjects;
    }
    
}
