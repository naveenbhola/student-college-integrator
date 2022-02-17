<?php
class ConsultantLocation
{
    private $consultantId;
    private $consultantLocationId;
    private $contactName;
    private $defaultPhone;
    private $shikshaPRINumber;
    private $displayPRINumber;
    private $email;
    private $cityId;
    private $cityName;
    private $localityId;
    private $localityName;
    private $locationAddress;
    private $pincode;
    private $latitude;
    private $longitude;
    private $defaultBranch;
    private $headOffice;
    private $contactHours;
    private $phone1;
    private $phone2;
    private $phone3;
    private $phone4;
    
    public function __construct(){
    }
    
    public function getId(){
        return $this->consultantLocationId;
    }
    
    public function getConsultantId(){
        return $this->consultantId;
    }

    public function getContactName(){
        return $this->contactName;
    }
    
    public function getDefaultPhoneNo(){
        return $this->defaultPhone;
    }
    
    public function getOtherPhoneNos(){
        return array($this->phone1, $this->phone2, $this->phone3, $this->phone4);
    }
    
    public function getShikshaPRINumber($formatFlag = FALSE){
        if(strpos($this->shikshaPRINumber,'+') === FALSE && $formatFlag){
            $this->shikshaPRINumber = '+'.$this->shikshaPRINumber;
        }
        return $this->shikshaPRINumber;
    }
    public function getDisplayPRINumber(){
        return $this->displayPRINumber;
    }
    public function getEmail(){
        return $this->email;
    }
    
    public function getCityId(){
        return $this->cityId;
    }
    
    public function getCityName(){
        return $this->cityName;
    }
    
    public function getLocalityId(){
        return $this->localityId;
    }
    
    public function getLocalityName(){
        return $this->localityName;
    }
    
    public function getLocationAddress(){
        return $this->locationAddress;
    }
    
    public function getLatLongCoordinates(){
        return array('latitude'=> $this->latitude, 'longitude'=> $this->longitude);
    }
    
    public function isDefaultBranch(){
        return $this->defaultBranch;
    }
    
    public function isHeadOffice(){
        return $this->headOffice;
    }
    
    public function getContactHours()
    {
        return $this->contactHours;
    }
    
    public function getPinCode()
    {
        return $this->pincode;
    }
    
    public function __set($property,$value) {
        $this->$property = $value;
    }
}	
	