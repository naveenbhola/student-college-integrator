<?php

class LocationDetection{
    private $reader;
    private $ip;
    private $record;
    function __construct($ip) {
        require_once APPPATH.'third_party/maxmind/autoload.php';
        $this->reader = new GeoIp2\Database\Reader(APPPATH.'third_party/maxmind/database/GeoIP2-City-Asia-Pacific.mmdb');
        $this->ip = $ip;
        $this->record = $this->reader->city($this->ip);
    }
    
    public function getLocation(){
        //($this->record->city->name);
        $locationResponse = array();
        if($this->record){
            $locationResponse['city']                           = $this->record->city->name;
            $locationResponse['country']                        = $this->record->country->name;
            $locationResponse['countryIsoCode']                 = $this->record->country->isoCode;
            $locationResponse['postalCode']                     = $this->record->postal->code;
            $locationResponse['latitude']                       = $this->record->location->latitude;
            $locationResponse['longitude']                      = $this->record->location->longitude;
            $locationResponse['mostSpecificSubdivision']        = $this->record->mostSpecificSubdivision->name;
            $locationResponse['mostSpecificSubdivisionIsoCode'] = $this->record->mostSpecificSubdivision->isoCode;
        }
        return $locationResponse;
    }
}
