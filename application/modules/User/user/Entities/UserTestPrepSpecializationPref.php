<?php

/**
 * File for user\Entities\UserTestPrepSpecializationPref
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserTestPrepSpecializationPref
 */
class UserTestPrepSpecializationPref
{
    /**
     * Field for Specilization ID
     * @var integer $specializationId
     */
    private $specializationId;

    /**
     * Field for Specilization ID
     * @var string $status
     */
    private $status;

    /**
     * Field for Update Time
     * @var datetime $updateTime
     */
    private $updateTime;

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
     * Set specializationId
     *
     * @param integer $specializationId
     * @return UserTestPrepSpecializationPref
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
     * Set status
     *
     * @param string $status
     * @return UserTestPrepSpecializationPref
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
     * Set updateTime
     *
     * @param datetime $updateTime
     * @return UserTestPrepSpecializationPref
     */
    public function setUpdateTime($updateTime)
    {
        $this->updateTime = $updateTime;
        return $this;
    }

    /**
     * Get updateTime
     *
     * @return datetime 
     */
    public function getUpdateTime()
    {
        return $this->updateTime;
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
     * @return UserTestPrepSpecializationPref
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
}