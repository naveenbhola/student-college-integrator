<?php
/**
 * File for user\Entities\UserRegistrationSource
 */
namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserRegistrationSource
 */
class UserRegistrationSource
{
    /**
     * Field for key ID
     * @var integer $keyId
     */
    private $keyId;

    /** Field for cordinates
     * @var string $coordinates
     */
    private $coordinates;

    /**
     * Field for referrer
     * @var string $referer
     */
    private $referer;

    /**
     * Field for type
     * @var string $type
     */
    private $type;

    /**
     * Field for time
     * @var datetime $time
     */
    private $time;

    /**
     * Field for resolution(size)
     * @var string $resolution
     */
    private $resolution;

    /**
     * Field for landingpage
     * @var string $landingPage
     */
    private $landingPage;

    /**
     * Field for keyQuery
     * @var string $keyQuery
     */
    private $keyQuery;

    /**
     * Field for Browser
     * @var string $browser
     */
    private $browser;
    
    
    /**
     * Field for tracking_keyid
     * @var integer $tracking_keyid
     */
    private $trackingKeyid;
    
    
    /**
     * Field for sessionid
     * @var string $sessionid
     */
    private $visitorSessionId;

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;

    /**
     * Entity user\Entities\User
     * @var user\Entities\User
     */
    private $user;


    /**
     * Set keyId
     *
     * @param integer $keyId
     * @return UserRegistrationSource
     */
    public function setKeyId($keyId)
    {
        $this->keyId = $keyId;
        return $this;
    }

    /**
     * Get keyId
     *
     * @return integer 
     */
    public function getKeyId()
    {
        return $this->keyId;
    }

    /**
     * Set coordinates
     *
     * @param string $coordinates
     * @return UserRegistrationSource
     */
    public function setCoordinates($coordinates)
    {
        $this->coordinates = $coordinates;
        return $this;
    }

    /**
     * Get coordinates
     *
     * @return string 
     */
    public function getCoordinates()
    {
        return $this->coordinates;
    }

    /**
     * Set referer
     *
     * @param string $referer
     * @return UserRegistrationSource
     */
    public function setReferer($referer)
    {
        $this->referer = $referer;
        return $this;
    }

    /**
     * Get referer
     *
     * @return string 
     */
    public function getReferer()
    {
        return $this->referer;
    }

    /**
     * Set type
     *
     * @param string $type
     * @return UserRegistrationSource
     */
    public function setType($type)
    {
        $this->type = $type;
        return $this;
    }

    /**
     * Get type
     *
     * @return string 
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * Set time
     *
     * @param datetime $time
     * @return UserRegistrationSource
     */
    public function setTime($time)
    {
        $this->time = $time;
        return $this;
    }

    /**
     * Get time
     *
     * @return datetime 
     */
    public function getTime()
    {
        return $this->time;
    }

    /**
     * Set resolution
     *
     * @param string $resolution
     * @return UserRegistrationSource
     */
    public function setResolution($resolution)
    {
        $this->resolution = $resolution;
        return $this;
    }

    /**
     * Get resolution
     *
     * @return string 
     */
    public function getResolution()
    {
        return $this->resolution;
    }

    /**
     * Set landingPage
     *
     * @param string $landingPage
     * @return UserRegistrationSource
     */
    public function setLandingPage($landingPage)
    {
        $this->landingPage = $landingPage;
        return $this;
    }

    /**
     * Get landingPage
     *
     * @return string 
     */
    public function getLandingPage()
    {
        return $this->landingPage;
    }

    /**
     * Set keyQuery
     *
     * @param string $keyQuery
     * @return UserRegistrationSource
     */
    public function setKeyQuery($keyQuery)
    {
        $this->keyQuery = $keyQuery;
        return $this;
    }

    /**
     * Get keyQuery
     *
     * @return string 
     */
    public function getKeyQuery()
    {
        return $this->keyQuery;
    }

    /**
     * Set browser
     *
     * @param string $browser
     * @return UserRegistrationSource
     */
    public function setBrowser($browser)
    {
        $this->browser = $browser;
        return $this;
    }
    
    /**
     * Get browser
     *
     * @return string 
     */
    public function getBrowser()
    {
        return $this->browser;
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
     * @return UserRegistrationSource
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
     * Set tracking key id
     *
     * @param integer $tracking_keyid
     * @return UserRegistrationSource
     */
    public function setTrackingKeyid($trackingKeyid)
    {
        $this->trackingKeyid = $trackingKeyid;
        return $this;
    }
    
    /**
     * Get trackingid
     *
     * @return integer 
     */
    public function getTrackingKeyid()
    {
        return $this->trackingKeyid;
    }
    
     /**
     * Set visitorSessionId
     *
     * @param integer visitorSessionId
     * @return UserRegistrationSource
     */
    public function setVisitorSessionId($visitorSessionId)
    {
        $this->visitorSessionId = $visitorSessionId;
        return $this;
    }
    
    /**
     * Get visitorSessionId
     *
     * @return string 
     */
    public function getVisitorSessionId()
    {
        return $this->visitorSessionId;
    }
}