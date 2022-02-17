<?php
/**
 * Populator class file for UserLocationPref entity
 */ 
namespace user\libraries\DataPopulators;

/**
 * Populator class for UserLocationPref entity
 */ 
class UserLocationPref extends AbstractPopulator
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
     * Populate data into UserLocationPref entity
     *
     * @param object $locationPref \user\Entities\UserLocationPref
     * @param array $data Data to be populated in
     */
    public function populate(\user\Entities\UserLocationPref $locationPref,$data = array())
    {
        $this->setData($data);
        
        $this->CI->load->builder('LocationBuilder','location');
        $locationBuilder = new \LocationBuilder;
        $locationRepository = $locationBuilder->getLocationRepository();
        
        $localityId = $cityId = $stateId = $countryId = 0;
        
        if($data['countryId']) {
            
            $country = $locationRepository->findCountry($data['countryId']);
            if(!$country->getId()) {
                throw new \Exception("Invalid country id ".$data['countryId']." in preferred study locations");
            }
            
            $countryId = $country->getId();
        }
        else if($data['stateId']) {
            
            $state = $locationRepository->findState($data['stateId']);
            if(!$state->getId()) {
                throw new \Exception("Invalid state id ".$data['stateId']." in preferred study locations");
            }
            
            $stateId = $state->getId();
            $countryId = $state->getCountryId();
        }
        else if($data['cityId']) {
            
            $city = $locationRepository->findCity($data['cityId']);
            if(!$city->getId()) {
                throw new \Exception("Invalid city id ".$data['cityId']." in preferred study locations");
            }
            
            $cityId = $city->getId();
            $stateId = $city->getStateId();
            $countryId = $city->getCountryId();
        }
        else if($data['localityId']) {
            
            $locality = $locationRepository->findLocality($data['localityId']);
            if(!$locality->getId()) {
                throw new \Exception("Invalid locality id ".$data['localityId']." in preferred study locations");
            }
            
            $localityId = $locality->getId();
            $cityId = $locality->getCityId();
            $stateId = $locality->getStateId();
            $countryId = $locality->getCountryId();
        }
        
        $locationPref->setCityId($cityId);
        $locationPref->setStateId($stateId);
        $locationPref->setCountryId($countryId);
        $locationPref->setLocalityId($localityId);
        $locationPref->setSubmitDate(new \DateTime);
        $locationPref->setStatus('live');
    }
}