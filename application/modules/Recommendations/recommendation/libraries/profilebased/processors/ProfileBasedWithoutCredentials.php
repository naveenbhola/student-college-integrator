<?php

require_once __DIR__."/AbstractProfileBased.php"; 

class ProfileBasedWithoutCredentials extends AbstractProfileBased
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

            /**
             * Remove credentials from levelAndCredentials
             */ 
            $relaxedLevelAndCredentials = array();
            foreach ($profile['levelAndCredentials'] as $levelAndCredentials) {
                unset($levelAndCredentials['credential']);
                $relaxedLevelAndCredentials[] = $levelAndCredentials;
            }
            $profile['levelAndCredentials'] = $relaxedLevelAndCredentials;
            $relaxedProfiles[] = $profile;
        }

        return $relaxedProfiles;
    }
}
