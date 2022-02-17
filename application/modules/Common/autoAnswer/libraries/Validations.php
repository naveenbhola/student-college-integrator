<?php

class Validations {

	function validateName($subject){

		$subject = trim($subject);
		$pattern = '/[a-zA-z]{1,10}[ ]([a-zA-z]{1,10}[ ]){1,2}/';
		if(preg_match($pattern, $subject." ") && str_word_count($subject) <= 3){
			return true;
		}
		return "Name is Incorrect. Please enter the proper name like Sachin Verma";
	}

	function validateEmail($subject){
		if (!filter_var($subject, FILTER_VALIDATE_EMAIL) === false) {
		  return true;
		} else {
		  return false;
		}
	}

	function yesNoTypeResponseValidate($txt){
		$txt = trim($txt);
		$txt = strtolower($txt);
		if($txt == "yes" || $txt == "no"){
			return true;
		}else 
			return "Incorerct text. Please type yes/no.";
	}

	function validateMobileNumber($txt){
		$txt = trim($txt);
		$txt = str_replace(" ","", $txt);
		$txtArray = explode("-",$txt);
		if(count($txtArray) > 2){
			return "Incorrect Phone Number(More that two hyphen)";
		} else if(count($txtArray) < 2){
			return "Incorrect Number. Please provide us your Mobile Number to proceed WITH country code.(ex. +91-88888888888)";
		}
		else {
			$countryCode = $txtArray[0];
			if(!ctype_digit(str_replace("+", "", $countryCode))) {
				return "Incorrect Country Code";
			}
			$mainPhoneNumber = $txtArray[1];
			if(!ctype_digit($mainPhoneNumber)){
				return "Incorrect Phone Number";
			}
			if($countryCode == "+91"){
				if(strlen($mainPhoneNumber) != 10){
					return "Phone Number should be of 10 digits";
				}
			}else{
				if(strlen($mainPhoneNumber) <6 || strlen($mainPhoneNumber) > 20){
					return "Phone Number should be of length 6 to 20";
				}
			}
		}
		return true;
	}

	function validateOTP($txt){
		$txt = trim($txt);
		$expectedOTP = $_SESSION['OTP'];
		if($txt == $expectedOTP){
			return true;
		}else{
			return "Incorrect OTP";
		}
	}
	
}