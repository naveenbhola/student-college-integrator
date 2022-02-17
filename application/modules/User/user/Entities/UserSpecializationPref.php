<?php
/**
 *  File for user\Entities\UserSpecializationPref
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserSpecializationPref
 */
class UserSpecializationPref
{
    /**
     * Field for specialization ID
     * @var integer $specializationId
     */
    private $specializationId;

    /**
     * Field for Submit date
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
     * Field for Preference
     * @var user\Entities\UserPref
     */
    private $preference;

    /**
     * Field for user details
     * @var user\Entities\User
     */
    private $user;


    /**
     * Set specializationId
     *
     * @param integer $specializationId
     * @return UserSpecializationPref
     */
    public function setSpecializationId($specializationId)
    {
        $this->specializationId = $specializationId;
        return $this;
    }

    /**
     * Get specializationId
     *
     * @return integer 
     */
    public function getSpecializationId()
    {
        return $this->specializationId;
    }

    /**
     * Set submitDate
     *
     * @param datetime $submitDate
     * @return UserSpecializationPref
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
     * @return UserSpecializationPref
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
     * Set preference
     *
     * @param user\Entities\UserPref $preference
     * @return UserSpecializationPref
     */
    public function setPreference(\user\Entities\UserPref $preference = null)
    {
        $this->preference = $preference;
        return $this;
    }

    /**
     * Get preference
     *
     * @return user\Entities\UserPref 
     */
    public function getPreference()
    {
        return $this->preference;
    }

    /**
     * Set user
     *
     * @param user\Entities\User $user
     * @return UserSpecializationPref
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