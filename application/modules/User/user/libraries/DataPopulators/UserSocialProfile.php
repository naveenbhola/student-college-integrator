<?php
/**
 * File for Populator class for UserSocialProfile entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserSocailProfile entity
 */ 
class UserSocialProfile extends AbstractPopulator
{
    /**
     * Constructor
     *
     * @param string $mode create|update
     */
    function __construct($mode = 'create')
    {
        parent::__construct($mode);
    }
    
    /**
     * Populate data into UserSocialProfile entity
     *
     * @param object $UserSocialProfile \user\Entities\UserSocialProfile
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserSocialProfile $userSocialProfile,$data = array())
    {
        $this->setData($data);
        if(isset($userSocialProfile) && gettype($userSocialProfile) == 'object') {

    	    if(isset($data['twitterId'])){
                    $userSocialProfile->setTwitterId(trim($data['twitterId']));
            }
            if(isset($data['linkedinId'])){
                $userSocialProfile->setLinkedinId(trim($data['linkedinId']));
            }
            if(isset($data['facebookId'])){
                $userSocialProfile->setFacebookId(trim($data['facebookId']));
            }
            if(isset($data['personalURL'])){
                $userSocialProfile->setPersonalURL(trim($data['personalURL']));
            }
            if(isset($data['youtubeId'])){
                $userSocialProfile->setYoutubeId(trim($data['youtubeId']));
            }
	    }

    }
}
