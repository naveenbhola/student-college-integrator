<?php

abstract class AbstractProfileBased
{
    protected $recommendationGenerator;

    function __construct(ProfileBasedRecommendationGenerator $recommendationGenerator)
    {
        $this->recommendationGenerator = $recommendationGenerator;
    }

    public function process($profiles, $numResults, $exclusionList)
    {
        $profiles = $this->getProfiles($profiles);
        return $this->recommendationGenerator->generateRecommendations($profiles, $numResults, $exclusionList);
    }

    abstract function getProfiles($profiles);
}
