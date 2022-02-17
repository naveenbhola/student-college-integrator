<?php

class Request implements \JsonSerializable
{
    private $text;
    private $mainAttrSet;
    private $mainAttrValue;
    private $prefLocation;
    private $name;
    private $phone;
    private $email;
    private $whichCase;
    private $requestType;
    private $customData;
    private $instituteObject;
    
    function __construct()
    {
        $this->text = "";
        $this->mainAttrSet = false;
        $this->prefLocation = false;
        $this->mainAttrValue = "";
        $this->name = "";
        $this->phone = "";
        $this->email = "";

        $this->whichCase= "";
        $this->customData = array();
        $this->CI=& get_instance();
        $this->instituteObject = $this->CI->load->library("autoAnswer/attributes/InstituteObject");
    
    }
    
   /* public function iterateObject(){
        _p($this);
    }
*/  

    public function JsonSerialize()
    {
        $vars = get_object_vars($this);

        return $vars;
    }
    
    public function getText(){
        return $this->text;
    }

    public function setText($text){
        $this->text = $text;
    }

    public function getMainAttrSet(){
        return $this->mainAttrSet;
    }

    public function setMainAttrSet($mainAttrSet){
        $this->mainAttrSet = $mainAttrSet;
    }

    public function getPrefLocation(){
        return $this->prefLocation;
    }

    public function setPrefLocation($location){
        return $prefLocation;
    }

    public function setName($name){
        $this->name = $name;
    }

    public function getName(){
        return $this->name;
    }

    public function setEmail($email){
        $this->email = $email;
    }

    public function getEmail(){
        return $this->email;
    }

    public function setPhone($phone){
        $this->phone = $phone;
    }

    public function getPhone(){
        return $this->phone;
    }

    public function setWhichCase($whichCase){
        $this->whichCase = $whichCase;
    }

    public function getWhichCase(){
        return $this->whichCase;
    }

    public function setCustomData($customData){
        $this->customData = $customData;
    }

    public function getCustomData(){
        return $this->customData;
    }

    public function setMainAttrValue($mainAttrValue){
        $this->mainAttrValue = $mainAttrValue;
    }

    public function getMainAttrValue(){
        return $this->mainAttrValue;
    }

    public function setRequestType($requestType){
        $this->requestType = $requestType;
    }

    public function getRequestType(){
        return $this->requestType;
    }

    public function getInstituteObject(){
        return $this->instituteObject;
    }

    public function setInstituteObject($instituteObject){
        $this->instituteObject = $instituteObject;
    }

}