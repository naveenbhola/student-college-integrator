<?php
/**
 * File for user\Entities\UserLocationPref Entity
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserLocationPref
 */
class UserLocationPref
{
    /**
     * Field for CountryID
     * @var integer $countryId
     */
    private $countryId;

    /**
     * Field for stateID
     * @var integer $stateId
     */
    private $stateId;

    /**
     * Field for City ID
     * @var integer $cityId
     */
    private $cityId;

    /**
     * Field for Submit Date
     * @var datetime $submitDate
     */
    private $submitDate;

    /**
     * Field for Status
     * @var string $status
     */
    private $status;

    /**
     * Field for Locality ID
     * @var integer $localityId
     */
    private $localityId;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Entity user\Entities\UserPref
     * @var user\Entities\UserPref
     */
    private $preference;

    /**
     * Entity user\Entities\User
     * @var user\Entities\User
     */
    private $user;


    /**
     * Set countryId
     *
     * @param integer $countryId
     * @return UserLocationPref
     */
    public function setCountryId($countryId)
    {
        $this->countryId = $countryId;
        return $this;
    }

    /**
     * Get countryId
     *
     * @return integer 
     */
    public function getCountryId()
    {
        return $this->countryId;
    }

    /**
     * Set stateId
     *
     * @param integer $stateId
     * @return UserLocationPref
     */
    public function setStateId($stateId)
    {
        $this->stateId = $stateId;
        return $this;
    }

    /**
     * Get stateId
     *
     * @return integer 
     */
    public function getStateId()
    {
        return $this->stateId;
    }

    /**
     * Set cityId
     *
     * @param integer $cityId
     * @return UserLocationPref
     */
    public function setCityId($cityId)
    {
        $this->cityId = $cityId;
        return $this;
    }

    /**
     * Get cityId
     *
     * @return integer 
     */
    public function getCityId()
    {
        return $this->cityId;
    }

    /**
     * Set submitDate
     *
     * @param datetime $submitDate
     * @return UserLocationPref
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
     * @return UserLocationPref
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
     * Set localityId
     *
     * @param integer $localityId
     * @return UserLocationPref
     */
    public function setLocalityId($localityId)
    {
        $this->localityId = $localityId;
        return $this;
    }

    /**
     * Get localityId
     *
     * @return integer 
     */
    public function getLocalityId()
    {
        return $this->localityId;
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
     * @return UserLocationPref
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
     * @return UserLocationPref
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