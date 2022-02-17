<?php

class Location extends MX_Controller
{
    private $_repository;
    private $_cache;
    
    function __construct()
    {
        $this->load->builder('LocationBuilder','location');
        $locationBuilder = new LocationBuilder;
        $this->_repository = $locationBuilder->getLocationRepository();
        $this->_cache = $locationBuilder->getLocationCache();
    }
    
    public function add($locationType = 'country')
    {
        $data = array();
        $data['locationType'] = 'country';
        $this->load->view('location/add_location_menu',$data);
        
        switch($locationType) {
            case 'country':
                $this->_addCountry(); 
                break;
            case 'city':
                $this->_addCity();
                break;
            case 'locality':
                $this->_addLocality();
                break;
            case 'zone':
                $this->_addZone();
                break;
            
        }
    }
    
    private function _addCountry()
    {
        $this->load->library('common/form');
        $form = new Form('addCountry');
        
        $data = array();
        $data['regions'] = $this->_repository->getRegions();
               
        $data['validationErrors'] = $form->getValidationErrors();
        $data['formData'] = $form->getFormPostData();
        $data['statusMessage'] = $form->getMessage();
        
        $this->load->view('location/add_country',$data);
    }
    
    private function _addCity()
    {
        $this->load->library('common/form');
        $form = new Form('addCountry');
        
        $data = array();
        $data['countries'] = $this->_repository->getCountries();
        $data['validationErrors'] = $form->getValidationErrors();
        $data['formData'] = $form->getFormPostData();
        $data['statusMessage'] = $form->getMessage();
        $this->load->view('location/add_city',$data);
    }
    
    /*
     *Function to give interface to add location
     */
     
     private function _addLocality()
    {
        $this->load->library('common/form');
        $form = new Form('addLocality');
        
        $data = array();
        $data['cities'] = $this->_repository->getCitiesHavingZones();
        $data['zones'] = $this->_repository->getZones();

        $zonemapping = array();
        foreach($data['zones'] as $zone) {
            if(!isset($zonemapping[$zone->getCityId()]))$zonemapping[$zone->getCityId()]=array();
            $zonemapping[$zone->getCityId()][$zone->getId()] = $zone->getName();    
        }

        $data['zonesJson'] = json_encode($zonemapping);
        $data['validationErrors'] = $form->getValidationErrors();
        $data['formData'] = $form->getFormPostData();
        $data['statusMessage'] = $form->getMessage();
        //_p($data);
        $this->load->view('location/add_locality',$data);
    }
    
     /*
     *Function to give interaface to add zone
     */
    private function _addZone()
    {
        $this->load->library('common/form');
        $form = new Form('addZone');
        $data = array();
        $data['cities'] = $this->_repository->getCitiesHavingZones();
        
        $data['validationErrors'] = $form->getValidationErrors();
        $data['formData'] = $form->getFormPostData();
        $data['statusMessage'] = $form->getMessage();
        $this->load->view('location/add_zone',$data);
    }
        
    public function saveCountry()
    {
        /*
         * Validate input post data
         */
        $this->load->library('common/form');
        $form = new Form('addCountry');        
        
        if(!$form->validate()) {
            header("location: /enterprise/Enterprise/index/54/country");
            exit();
        }
        
        $country = trim($this->input->post("country"));
        $region = $this->input->post("region");
        $capital = trim($this->input->post("capital"));
        
        if(!$countryId = $this->_repository->saveCountry($country,$region)) {
            $form->setMessage('Error',"Unable to add country '".$country."'. Please try again.");
            header("location: /enterprise/Enterprise/index/54/country");
            exit();
        }
        
        if(!$cityId = $this->_repository->saveCity($capital,-1,$countryId)) {
            $form->setMessage('Error',"Successfully added country '".$country."', but unable to add city '".$capital."'. Please add the city from 'Add City' interface.");
            header("location: /enterprise/Enterprise/index/54/country");
            exit();
        }
        
        $this->_notifyObservers(array('LocationCountryMapUpdater','CountriesArrayUpdater','HomePageHTMLCacheRemover','LocationCacheInvalidator'));
        
        $form->setMessage('Success',"Successfully added country '".$country."' and city '".$capital."'.");
        header("location: /enterprise/Enterprise/index/54/country");
    }
        
    public function saveLocality()
    {
        
        /*
         * Validate input post data
         */
        $this->load->library('common/form');
        $form = new Form('addLocality');
        
        if(!$form->validate()) {
            header("location: /enterprise/Enterprise/index/54/locality");
            exit();
        }
        
        $Locality = trim($this->input->post("Locality"));
        $Zone = $this->input->post("Zone");
        $City = $this->input->post("City");
        
        if(!$localityId = $this->_repository->saveLocality($Locality,$Zone,$City)) {
            $form->setMessage('Error',"Unable to add locality '".$Locality."'. Please try again.");
            header("location: /enterprise/Enterprise/index/54/locality");
            exit();
        }
                
        $form->setMessage('Success',"Confirmation message: The locality $Locality has been added successfully. ");
        
                
        $this->load->library('location/cache/LocationCache');
        $locationCache = new LocationCache;
        $locationCache->clearCache();
        
        header("location: /enterprise/Enterprise/index/54/locality");
    }    
        
    public function saveZone()
    {
        /*
         * Validate input post data
         */
        $this->load->library('common/form');
        $form = new Form('addZone');
        
        if(!$form->validate()) {
            header("location: /enterprise/Enterprise/index/54/zone");
            exit();
        }
        
        
        $zone = trim($this->input->post("zone"));
        $City = $this->input->post("City");
        $Locality = trim($this->input->post("Locality"));
                
        if(!$Zone = $this->_repository->saveZone($zone)) {
            $form->setMessage('Error',"Unable to add zone '".$zone."'. Please try again.");
            header("location: /enterprise/Enterprise/index/54/zone");
            exit();
        }
        
        
        if(!$localityId = $this->_repository->saveLocality($Locality,$Zone,$City)) {
            $form->setMessage('Error',"Unable to add locality '".$Locality."'. Please try again.");
            header("location: /enterprise/Enterprise/index/54/locality");
            exit();
        }
        
        $this->load->library('location/cache/LocationCache');
        $locationCache = new LocationCache;
        $locationCache->clearCache();
        
        $form->setMessage('Success',"Confirmation message: The zone $zone and Locality $Locality has been added successfully. ");
        header("location: /enterprise/Enterprise/index/54/zone");
    }
        
    public function saveCity()
    {
        /*
         * Validate input post data
         */
        $this->load->library('common/form');
        $form = new Form('addCity');
        
        if(!$form->validate()) {
            header("location: /enterprise/Enterprise/index/54/city");
            exit();
        }
        
        $countryId = $this->input->post("country");
        
        $successfullySavedCities = array();
        $citiesNotSaved = array();
        
        $numCities = $this->input->post("numCities");
        
        for($i=1;$i<=$numCities;$i++) {
            if($city = trim($this->input->post("city".$i))) {
                
                if($cityId = $this->_repository->saveCity($city,-1,$countryId)) {
                   $successfullySavedCities[] = $city; 
                }
                else {
                    $citiesNotSaved[] = $city;
                }
            }
        }
        
        $message = array();
        
        if(count($successfullySavedCities) > 0) {
            $message[] = "Cities ".implode(", ",$successfullySavedCities)." successfully added.";
        }
        if(count($citiesNotSaved) > 0) {
            $message[] = "Error in adding cities ".implode(", ",$citiesNotSaved).".";
        }
        
        $this->_notifyObservers(array('LocationCacheInvalidator'));
        
        $form->setMessage('Success',implode('<br />',$message));
        header("location: /enterprise/Enterprise/index/54/city");
    }
    
    public function getCitiesForCountry($countryId, $include_virtual=False)
    {
        $cities = $this->_repository->getCities($countryId, $include_virtual);
        
        $cityMapping = array();
        foreach($cities as $city) {
            $cityMapping[$city->getId()] = $city->getName();    
        }
        asort($cityMapping);
        echo json_encode($cityMapping);
    }

    public function getStatesByCountry($countryId)
    {
        $states = $this->_repository->getStatesByCountry($countryId);
        _p($states); die;
    }
    
    private function _notifyObservers($observers)
    {
        foreach($observers as $observer) {
            
            $this->load->library('location/observers/'.$observer);
            $observerObj = new $observer;
            $observerObj->update();
        }
    }
    
    /*
     * Purpose : Method to refresh the locality cache of the provided locality with refershing all dependent cache
     * Params  : 1. Lcoality id
     * Author  : Romil Goel
    */
    public function refreshLocalityCache($locality_id)
    {
        if(!$locality_id)
        {
            echo "Please provide a valid locality id";
            exit(0);
        }
        
        $this->_repository->disableCaching();
        $localityObj = $this->_repository->findLocality($locality_id);
        
        $this->_repository->getLocalities();
        
        if($localityObj->getZoneId())
        {
            $this->_repository->getLocalitiesByZone($localityObj->getZoneId());
        }
        
        if($localityObj->getCityId())
        {
            $this->_repository->getLocalitiesByCity($localityObj->getCityId());
        }
        
        echo "Done";
    }
    /*
     * to refresh abroad location objects
     */
    public function refreshAbroadLocations()
    {   
        
        $this->_repository->disableCaching();
        // get countries
        $countries = $this->_repository->getAbroadCountries();
        
        $cities = array();
        foreach($countries as $countryObj)
        {
            $countryId = $countryObj->getId();
            if($countryId <3)
            {
                continue;
            }
            echo $countryId.'<br>';
            $citiesByCountry = $this->_repository->getCities($countryId);
            //_p($citiesByCountry);
            $states = $this->_repository->getStatesByCountry($countryId);
            

            
            foreach($states as $stateObj)
            {
                //if($countryId == 9)
                //{ echo "<br>SS:::";var_dump($stateObj); }
                if($stateObj){
                    $cities[] = $this->_repository->getCitiesByState($stateObj->getId());
                }
            }
        }
        //_p($countries);
        //_p($states);
        //echo "ASD";
        //_p($cities);
        echo "Updation Complete";
    }
}