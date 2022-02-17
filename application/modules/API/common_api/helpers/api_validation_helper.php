<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');

if ( ! function_exists('validateStr'))
{
    function validateStr($str,$caption,$minLength,$maxLength)
    {
            $str = preg_replace('/[^(\x20-\x7E)]*/','', $str);
            if(strlen($str) > $maxLength) {
                return "Please use a maximum of ".$maxLength." characters for ".$caption;
            } else if(strlen($str)<$minLength) {
                return "The " . $caption . " must contain atleast ". $minLength ." characters.";
            } else {
                $str = strtolower($str);
                $strArray = explode(" ",$str);
                /*for($strArrayCount = 0; $strArrayCount < count($strArray); $strArrayCount++) {
                    if((strlen($strArray[$strArrayCount]) > 32) && (   strpos($strArray[$strArrayCount],'http' ) === false )) {
                        return $caption . " cannot contain any word exceeding 32 characters.";
                    }
                }*/
                return true;
            }
    }
}


if ( ! function_exists('validateStrForPosting'))
{
    function validateStrForPosting($str,$caption,$minLength,$maxLength)
    {
			$str = str_replace("\n"," " ,$str);
            $str = preg_replace('/[^(\x20-\x7E)]*/','', $str);
            if(strlen($str) > $maxLength) {
                return "Please use a maximum of ".$maxLength." characters for ".$caption;
            } else if(strlen($str)<$minLength) {
                return "The " . $caption . " must contain atleast ". $minLength ." characters.";
            } else {
                $str = strtolower($str);
                $strArray = explode(" ",$str);
                for($strArrayCount = 0; $strArrayCount < count($strArray); $strArrayCount++) {
                     if((strlen($strArray[$strArrayCount]) > 32) && (   stripos($strArray[$strArrayCount],'http' ) === false ) && (   stripos($strArray[$strArrayCount],'www.' ) === false )) {
                        return "The " . $caption . " cannot contain any word exceeding 32 characters.";
                    }
                }
                return true;
            }
    }
}


if ( ! function_exists('validateDisplayName'))
{
    function validateDisplayName($str,$caption, $minLength,$maxLength){
            $strToValidate = trim($str);
            if(strlen($strToValidate) < $minLength)
                    return $caption." should be atleast ".$minLength." characters.";

            if(strlen($strToValidate) > $maxLength)
                    return $caption." cannot exceed ".$maxLength." characters.";

            //if (!preg_match('/^([A-Za-z0-9\s](,|\.|_|-){0,2})*$#i', $strToValidate))
            if (!preg_match('/^[A-Za-z0-9,.\-_() \'\"]+$/', $strToValidate))
                    return "The " .$caption. " can not contain special characters.";

            //Check that any of the Blacklisted words do not appear in the Name
            $newA = file_get_contents("public/blacklisted.txt");
            $blacklistWords = array($newA);
            $blackListedArray = explode(',',$blacklistWords[0]);
            foreach ($blackListedArray as $word){
                $word = trim($word,"'");
                if(strpos(strtolower ($strToValidate), strtolower ($word)) !== false){
                    return "This username is not allowed.";
                }
            }

            return true;
    }
}

if ( ! function_exists('validateInteger'))
{
    function validateInteger($number, $caption, $minLength, $maxLength) {
            if (strlen($number)==0 && $minLength==0) {
                return true;
            }
	    
	    if (!preg_match('/^[0-9]*$/', $number)) {
                return 'Please fill the '.$caption.' with correct numeric value';
            }
	    
            if(strlen($number) > $maxLength || strlen($number) < $minLength) {
                if ($maxLength !== $minLength) {
                    return 'Please fill the '.$caption.' with '.$minLength.' to '.$maxLength.' digits';
                } else {
                    return 'Please fill the '.$caption .' with '.$maxLength.' digits';
                }
            }
            return true;
    }
}

if ( ! function_exists('validateFloat'))
{
    function validateFloat($number, $caption, $minLength, $maxLength) {
            if (strlen($number)==0 && $minLength==0) {
                return true;
            }
            if($number != (string) (float) $number){
                return 'Please fill the '.$caption.' with correct numeric value';
            }
            if(strlen($number) > $maxLength || strlen($number) < $minLength) {
                if ($maxLength !== $minLength) {
                    return 'Please fill the '.$caption.' with '.$minLength.' to '.$maxLength.' digits';
                } else {
                    return 'Please fill the '.$caption .' with '.$maxLength.' digits';
                }
            }
            return true;
    }
}

if ( ! function_exists('validateMobileInteger'))
{
    function validateMobileInteger($number,$caption='mobile',$minlength=10,$maxlength=10,$required=true) {

        if($number != '')
        {
                    if(!ctype_digit($number)){
                        return $caption." can only have digits";
                    }
                    if(!is_int(intval($number))){
                            return "Please enter your correct " .$caption;
                    }
                    
                    if(($number[0] != 9) && ($number[0] != 8) && ($number[0] != 7))
                    return "The mobile number can start with 9 or 8 or 7 only.";

                    /*if(strlen(intval($number)) != strlen($number)){
                        return $caption." can only have digits" ;
                    }*/

                    if($maxlength == $minlength && (strlen($number) > $maxlength || strlen($number) < $minlength))
                            return "The mobile number must have " . $maxlength . " digits only";
                    if(strlen($number) < $minlength){
                            return "The mobile number must contain minimum " . $minlength . " digits";
                    }
                    if(strlen($number) > $maxlength){
                            return "The mobile number can contain maximum " . $maxlength . " digits";
                    }
                    return true;
        }
        if($number == '' && $required) {
                    return "Please enter your "+ caption;
        }
        return true;
    }
}

if ( ! function_exists('validateEmail'))
{
    function validateEmail($str, $caption='Email', $minLength=1000, $maxLength=5) {
        error_log("14july:::inside validate email function");
        $res = preg_match('/^\S+@[\w\d.-]{2,}\.[\w]{2,6}$/iU', $str);
        if(!$res){
                    return "Please enter correct ".$caption;
        }
        if(strlen($str) < $minLength){
                    return "The " . $caption . " must contain minimum " .$minLength . " characters";
        }
        if(strlen($str) > $maxLength){
                    return "The " .$caption . " can contain maximum " . $maxLength ." characters";
        }
        return true;
    }
}

// in YYYY-MM-DD Format
if ( ! function_exists('validateDate'))
{
    function validateDate($str, $caption='Date', $minLength=10, $maxLength=10) {

        if (preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$str))
        {
            return true;
        }else{
            return "Invalid ".$caption;
        }
    }
}


    if ( ! function_exists('validateFacebookURL'))
    {
        function validateFacebookURL($str, $caption='facebook url', $minLength=0, $maxLength=100) {

            $str = strtolower($str);
            if(strpos($str, "facebook.com") === false && strpos($str, "fb.com") === false){
                return "Invalid facebook link";
            }
            return true;
        }
    }

    if ( ! function_exists('validateLinkedInURL'))
    {
        function validateLinkedInURL($str, $caption='linkedin url', $minLength=0, $maxLength=100) {

            $str = strtolower($str);
            if(strpos($str, "linkedin.com") === false){
                return "Invalid linkedin link";
            }
            return true;
        }
    }
    if ( ! function_exists('validateTwitterURL'))
    {
        function validateTwitterURL($str, $caption='Twitter url', $minLength=0, $maxLength=100) {

            $str = strtolower($str);
            if(strpos($str, "twitter.com") === false){
                return "Invalid twitter link";
            }
            return true;
        }
    }
     if ( ! function_exists('validateYoutubeURL'))
    {
        function validateYoutubeURL($str, $caption='Youtube url', $minLength=0, $maxLength=100) {

            $str = strtolower($str);
            if(strpos($str, "youtube.com") === false){
                return "Invalid youtube link";
            }
            return true;
        }
    }

    if ( ! function_exists('validateFBAccessToken'))
    {
        function validateFBAccessToken($str,$caption,$minLength,$maxLength)
        {
                $str = preg_replace('/[^(\x20-\x7E)]*/','', $str);
                if(strlen($str) > $maxLength) {
                    return "Please use a maximum of ".$maxLength." characters for ".$caption;
                } else if(strlen($str)<$minLength) {
                    return "The " . $caption . " must contain atleast ". $minLength ." characters.";
                } 
                return true;
        }
    }





?>
