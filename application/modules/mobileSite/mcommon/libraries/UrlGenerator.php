<?php

class UrlGenerator {
		
     function getWapUrl($url){
    		    //call this funtion only when ensuring the browsing device is wireless, thanks. :)
	   	   //Assumption that we have a valid shiksha url.
	   if($this->isValidWapUrl($url))	          
                  return $url;//if valid desktop url return as it is.
	   
	   if($url == "www.shiksha.com" || $url == "shiksha.com" || $url == ".shiksha.com")
	   	return $url;

	   $wapUrl =$url;
	   if(($identifier= strstr($url,"cateGorypage-"))){
		$wapUrl = $this->getWapCategoryPageLink($identifier);
	   }else if($identifier= strstr($url ,"getListinGDetail/")){
		$wapUrl = $this->getWapListingDetailPageLink($identifier);
	   }else if($identifier= strstr($url,"listinGcoursetab-")){
		$wapUrl = $this->getWapListingCourseTabLink($identifier);
	   }else if($identifier= strstr($url,"listinGoverviewtab-")){
		$wapUrl = $this->getWapListingOverviewTabLink($identifier);
	   }
	   	return $wapUrl; 
    	} 
		   
   	function isValidWapUrl($url){
		return false;
   	}	         	
  	
   	function getWapCategoryPageLink($mini_url){
		$params = $this->getWapCategoryPageParams($mini_url);
		return $params;   
   	}
	function getWapCategoryPageParams($url){
		$params = substr($url,13);
		return $params;   
   	}   
	
	function getWapListingDetailPageLink($url){
		$params = $this->getWapListingDetailPageParams($url);
		return $params['listing_id'];
	}
   	function getWapListingDetailPageParams($mini_url){
		$token = strtok($mini_url, "/");
		$params = array();
		$token = strtok("/");
		$params['listing_id'] = $token;$token = strtok("/");
		$params['listing_type'] = $token;
		return $params;
   	}	
   
   	function getWapListingOverviewTabLink($url){
		$params = $this->getWapListingOverviewTabParams($url);   		
		return $params;
   	} 
 	function getWapListingOverviewTabParams($mini_url){
		$params = substr($mini_url,19);   		
		return $params;
   	}
	
  	function getWapListingCourseTabLink($url){
		$params = $this->getWapListingCourseTabParams($url);  		
		return $params;
 	}  
	function getWapListingCourseTabParams($mini_url){
  		$params = substr($mini_url,17);	
		return $params;
 	}
	
}
?>
