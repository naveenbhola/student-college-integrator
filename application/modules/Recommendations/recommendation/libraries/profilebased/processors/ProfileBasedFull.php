<?php

require_once __DIR__."/AbstractProfileBased.php"; 

class ProfileBasedFull extends AbstractProfileBased
{
    function __construct(ProfileBasedRecommendationGenerator $recommendationGenerator)
    {
        parent::__construct($recommendationGenerator);
    }

    public function getProfiles($profiles)
    {
        return $profiles;
    }
}
