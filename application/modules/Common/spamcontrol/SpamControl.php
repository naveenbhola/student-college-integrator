<?php
/**
* Spam Control Startegy Interface
*/

interface SCStrategy
{
    public function execute($parameters);
}
/**
* Spam Control Factory Interface
*/
interface SCFactory
{
    public function createInstance($parameters);
    
}
/**
* Spam Control Factory to Create Object at Runtime
*/
class SpamControlFactory implements SCFactory{
    public function createInstance($parameters)
    {
        foreach($parameters as $parameter){
            switch ($parameter) {
            case $parameter:
                $this->$parameter = new $parameter;
                break;
                default:
                break;
            }
        }
        return $this;
    }
}
/**
* Spam Control Startery Class to call execute method for class
* for different Classes.
*/
class SpamControlStrategy
{
    private $scstrategy;
    
    public function __construct($type,$parameters)
    {
        $scstrategy = new SpamControlFactory();
        $this->type = $type;
        $this->ref = $scstrategy->createInstance($type);
        $this->parameters = $parameters;
    }
    
    public function execute($parameters)
    {
        $i=0;
        $j=1;
        foreach($this->ref as $ref){
            $result[$this->type[$i]] = $ref->execute($this->parameters[$i]);
            $i++;
            $j++;
        }
        return $result;
    }
}

/**
* Class use for access Random Code.
* Validation of this code
*Validation Function Signature function validation($valueInHiddenField,$actualValue)
* @author Pranjul Raizada
*/
class HiddenCodeGenerator implements SCStrategy
{
    private $code;
    private $message;
    public function __construct()
    {
        
    }
    
    function setCode($parameter)
    {
        $this->code = $parameter;
    }
    
    function getCode()
    {
        return $this->code;
    }
    
    function generateCode($characters)
    {
        /* list all possible characters, similar looking characters and vowels have been removed */
        $possible = '23456789bcdfghjkmnpqrstvwxyz';
        $code = '';
        $i = 0;
        while ($i < $characters) {
            $code .= substr($possible, mt_rand(0, strlen($possible)-1), 1);
            $i++;
        }
        $this->setcode($code);
        return $code;
    }
    
    function execute($parameters)
    {
        return $this->validation($parameters);
    }
    
    function validation($parameters)
    {
        $codeSendByUser = $parameters[0];
        $acutalCode     = $parameters[1];
        $result = array();
        if ($codeSendByUser == $acutalCode) {
            $result['status'] = 'true';
            $result['msg'] = '';
        } else {
            $result['status'] = 'false';
            $result['msg'] = 'Wrong Code has been entered.';
        }
        return $result;
    }
}

/**
* Class use for access Hidden URl.
* Validation of this URL
*Validation Function Signature function validation($valueForHiddenUrl)
* @author Pranjul Raizada
*/

class HiddenUrlGenerator implements SCStrategy
{
    private $message;
    public function __construct()
    {
        
    }
    
    public function createHiddenUrl($label,$type,$name,$id,$value){
        $str = "<span style='display:none;'><Label>$label</Label><input type='$type' name='$name' id='$id' value='$value'/></span>";
        return $str;
    }
    
    public function execute($parameters)
    {
        return $this->validation($parameters);
    }
    
    public function validation($parameters){
        $hiddenUrl = $parameters[0];
        if (empty($hiddenUrl)) {
            $result['status'] = 'true';
            $result['msg'] = '';
        } else {
            $result['status'] = 'false';
            $result['msg'] = 'URl field should empty.';
        }
        return $result;
    }
}


/**
* Class use for Set and Get Time Limit.
* Validation For Start and End time difference with this time limit.
*Validation Function Signature function validation($startTime,$endTime,$timeLimit)
*
* @author Pranjul Raizada
*/

class TimeLimit implements SCStrategy
{
    private $timeLimit;
    public function __construct()
    {
        
    }
    
    public function setTimeLimit($parameter){
        $this->timeLimit = $parameter;
    }
    
    public function getTimeLimit(){
        return $this->timeLimit;
    }
    
    public function execute($parameters)
    {
        return $this->validation($parameters);
    }
    
    public function validation($parameters){
        $startTime = $parameters[0];
        $endTime   = $parameters[1];
        $timeLimit = $parameters[2];
        $timeDifference = $endTime - $startTime;
        if ($timeDifference > $timeLimit) {
            $result['status'] = 'true';
            $result['msg'] = '';
        } else {
            $result['status'] = 'false';
            $result['msg'] = 'Duration must be less than the time limit.';
        }
        return $result;
    }
}

/**
*To use this for validation ,you need to create Instance of SpamControlStrategy class and pass two parameters in form of array in its constructor
*Parameters in first array are the name of Validations that we need to validate.
*Parameters in second array are values corresponding to validation function and also there is a fixed order of values in this array.
*e.g. $sc = new SpamControlStrategy(array('HiddenCodeGenerator','HiddenUrlGenerator','TimeLimit'),array(array($valueInHiddenField,$actualValue),array($valueForHiddenUrl),array($startTime,$endTime,$timeLimit)));
$res = $sc->execute();
**/



