<?php

/**
 * File for user\Entities\UserTestPrepSpecializationPref
 */

namespace user\Entities;

use Doctrine\ORM\Mapping as ORM;

/**
 * user\Entities\UserTestPrepSpecializationPref
 */
class UserSocialProfile
{

    /**
     * Field for ID
     * @var integer $id
     */
    private $id;
        /**
     * Field for ID
     * @var integer $id
     */
    private $user;

    /**
     * Field for twitterId
     * @var string $twitterId
     */
    private $twitterId;

    /**
     * Field for facebookId
     * @var string $facebookId
     */
    private $facebookId;

    /**
     * Field for linkedinId
     * @var string $linkedinId
     */
    private $linkedinId;

    /**
     * Field for youtubeId
     * @var string $youtubeId
     */
    private $youtubeId;

    /**
     * Field for personalURL
     * @var string personalURL
     */
    private $personalURL;


    /**
     * Set status
     *
     * @param string $status
     * @return UserTestPrepSpecializationPref
     */
    public function setTwitterId($twitterId)
    {
        $this->twitterId = $twitterId;
        return $this;
    }

    /**
     * Get twitterId
     *
     * @return string 
     */
    public function getTwitterId()
    {
        return $this->twitterId;
    }

    /**
     * Set facebookId
     *
     * @param datetime $facebookId
     */
    public function setFacebookId($facebookId)
    {
        $this->facebookId = $facebookId;
        return $this;
    }

    /**
     * Get facebookId
     *
     * @return datetime 
     */
    public function getFacebookId()
    {
        return $this->facebookId;
    }

    /**
     * Set linkedinId
     *
     * @param $linkedinId
     */
    public function setLinkedinId($linkedinId)
    {
        $this->linkedinId = $linkedinId;
        return $this;
    }

    /**
     * Get linkedinId
     *
     * @return $linkedinId 
     */
    public function getLinkedinId()
    {
        return $this->linkedinId;
    }
     /**
     * Set url
     *
     * @param  $url
     */
    public function setPersonalURL($personalURL)
    {   
        $this->personalURL = $personalURL;
        return $this;
    }

    /**
     * Get url
     *
     * @return url 
     */
    public function getPersonalURL()
    {
        return $this->personalURL;
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

    public function getId(){
        return $this->id;
    }

    /*
     * Function to set youtube id
     */
    public function setYoutubeId($youtubeId){
        $this->youtubeId = $youtubeId;
        return $this;
    }

    /*
     * Function to get youtube id
     */
    public function getYoutubeId(){
        return $this->youtubeId;
    }


}