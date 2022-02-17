<?php

class Contract
{
    public static function mustBeNumericValueGreaterThanZero($value,$identifier)
    {
        if(!is_numeric($value) || $value < 1) {
            self::throwException($identifier.' must be integer value greater than 0');
        }
    }
    
    public static function mustBeNonEmptyArrayOfIntegerValues($value,$identifier)
    {
        if(!is_array($value) || count($value) == 0) {
            self::throwException($identifier.' must be non-empty array of integer values');
        }
        
        foreach($value as $currentValue) {
             if(!is_numeric($currentValue)) {
                self::throwException($identifier.' must be non-empty array of integer values');
            }
        }
    }
    
    public static function throwException($msg)
    {
        $trace=debug_backtrace();
        $caller = $trace[2];
        
        $callerDetails = isset($caller['class'])?$caller['class'].'::':'';
        $callerDetails .= $caller['function'];
        
        throw new InvalidArgumentException('Invalid Argument in '.$callerDetails.' - '.$msg);
    }
    
    public static function mustBeNumericValue($value,$identifier){
    	if(!is_numeric($value)){
    		self::throwException($identifier.' must be integer value');
    	}
    }
    
    public static function mustBeNonEmptyArray($value,$identifier){
    	if(!is_array($value)){
    		self::throwException($identifier.' must be an array');
    	}else{
    		$value = array_filter($value,function ($value){
    			return ($value !== NULL && $value !== FALSE && $value !== '');
    		});
    		if(count($value) == 0){
    			self::throwException($identifier.' must be non empty array');
    		}
    	}
    }
    
    public static function mustBeNonEmptyVariable($value,$identifier){
    	if(is_array($value) || $value === NULL || $value === FALSE || $value === ''){
    		self::throwException($identifier.' must be non empty value');
    	}
    }

    public static function mustBeANonNullObject($value, $identifier){
        if(!is_object($value) || $value === NULL || $value === FALSE){
            self::throwException($identifier.' must be an object');
        }
    }
}