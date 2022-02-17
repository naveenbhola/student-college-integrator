<?php
/**
 * Common Validation Lib Class
 * This is the class for all the API Validations throughout the Mobile App
 * @date    2015-07-16
 * @author  Ankur Gupta
 * @todo    none
*/

class APIValidationLib
{
    private $CI;

    function __construct() {
        $this->CI = &get_instance();
	$this->CI->load->helper('common_api/api_validation');
    }

    /**
     * @desc Common function checkValidations which will be called by individual Validation classes of each Module. This function will read the Params one by one and 
	     basis of their config (like mandatory, minLength,maxLength, validation), validate them
     * @param Array of all the Parameters which we need to validate
     * @return true in case no error is found
     * @return Error String in case any error is found.
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function checkValidations($paramArray){
        $returnVal = false;
       
        foreach ($paramArray as $key=>$parameter){
            if($parameter['mandatory']){
                $returnVal = $this->verifyRequiredParams($parameter['value'],$key);
            }
            else if($parameter['mandatoryArray']){
                $returnVal = $this->verifyRequiredParamsArray($parameter['value'],$key);
            }

            if($parameter['validation'] && $returnVal===true){
                $returnVal = call_user_func($parameter['validation'], $parameter['value'], $key, $parameter['minLength'], $parameter['maxLength']);
            }
	    if($returnVal!==true){	//In case of Error, we will return an array like array('email'=>'Please enter your email')
		
		$returnArray = array(
					array(
						'field' => $key, 
						'errorMessage' => $returnVal
					)
				    );
		return $returnArray;
	    }
        }
        return $returnVal;
    }

    /**
     * @desc Common function to check if the mandatory parameter is entered or not
     * @param Value of the Parameter
     * @param Caption/Name of the Parameter
     * @return true in case no error is found
     * @return Error string in case any error is found.
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function verifyRequiredParams($str, $caption) {
        if(!isset($str) || strlen(trim($str)) <=0 ){
            return "Please enter your $caption.";        
        }
        return true;
    }

    /**
     * @desc Common function to check if the mandatory parameter is entered or not
     * @param Value of the Parameter
     * @param Caption/Name of the Parameter
     * @return true in case no error is found
     * @return Error string in case any error is found.
     * @date 2015-07-16
     * @author Ankur Gupta
     */
    function verifyRequiredParamsArray($arr, $caption) {
        if(!isset($arr) || count($arr) <=0 ){
            return "Please enter your $caption.";        
        }
        return true;
    }
    
}

