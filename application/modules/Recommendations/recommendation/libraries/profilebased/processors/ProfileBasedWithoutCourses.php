<?php

require_once __DIR__."/AbstractProfileBased.php"; 

class ProfileBasedWithoutCourses extends AbstractProfileBased
{
    function __construct(ProfileBasedRecommendationGenerator $recommendationGenerator)
    {
        parent::__construct($recommendationGenerator);
    }

    public function getProfiles($profiles)
    {
        $relaxedProfiles = array();
        foreach ($profiles as $profile) {
            /**
             * Remove courses
             */ 
            unset($profile['courses']);
            $relaxedProfiles[] = $profile;
        }

        return $relaxedProfiles;
    }
}
