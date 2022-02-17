<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 *Copyright : 2015-16 Info Edge India Ltd
 *$Author   : Akhter (UGC Team)
 *$Id       : Redirection
 * Description : redirect only for mba domain to main domain and old url structure to new url structure
 **/

class Redirection
{
    function validateRedirection(array $param){
	$currentUrl = 'http://'.$_SERVER['HTTP_HOST'].$_SERVER['PHP_SELF'];
        switch ($param['pageName'])
	{
	    case "eventCalendar":
		$urlString = strpos($currentUrl,$param['oldUrl']);
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{ 
		    return true;
		}else if($subDomain == TRUE || $urlString != -1){
		    
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break;  
		
		case "onlineForm":
		$urlString = strpos($currentUrl,$param['oldUrl']);
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{
		    return true;
		}else if($subDomain == TRUE || $urlString != -1){
			
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break; 

		case "collegeReviews":
		$urlString = strpos($currentUrl,$param['oldUrl']);
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{
		    return true;
		}else if($subDomain == TRUE || $urlString != -1){
			
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break;  
		case "campusConnect":
		case "careerCompass":
		$urlString = strpos($currentUrl,$param['oldUrl']);
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{	 
			return true;
		}else if($subDomain == TRUE || $urlString != -1){
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break;    
	    case "campusConnectIntermediatePage":
	    $urlString = strpos($currentUrl,$param['oldUrl']);
		$interMedUrl = strpos($currentUrl, 'ask-current-mba-students');
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{	 
			return true;
		}else if($subDomain == TRUE || ($urlString != -1 && $interMedUrl === false)){
			
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break;    
	    case "campusConnectMobile":
		$urlString = strpos($currentUrl,$param['oldUrl']);
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{	 
			return true;
		}else if($subDomain == TRUE || $urlString != -1){
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break;    
	    case "campusConnectIntermediatePageMobile":
	    $urlString = strpos($currentUrl,$param['oldUrl']);
		$interMedUrl = strpos($currentUrl, 'ask-current-mba-students');
		$subDomain = $this->validateSubDomain($param['oldDomainName']);
		if($urlString === false && $subDomain == FALSE)
		{	 
			return true;
		}else if($subDomain == TRUE || ($urlString != -1 && $interMedUrl === false)){
			
		    $this->redirectUrl($param['newUrl'],$param['redirectRule']);
		}
		break;
	       
	    default:
		return true;
	}
    }
    
    function redirectUrl($url,$redirectRule){
	redirect($url,'location',$redirectRule);exit();
    }
    
    function validateSubDomain($domainArr){
	return in_array('http://'.$_SERVER['HTTP_HOST'], $domainArr);
    }
}
?>
