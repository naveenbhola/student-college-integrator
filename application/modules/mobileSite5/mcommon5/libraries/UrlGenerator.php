<?php

class UrlGenerator {

    public static $domain_mapping = array
    (
        'it.shiksha.com/' => '/information-technology-it-colleges-in-india-categorypage-10-1-1-0-0-1-1-2-0-none-1-0',
        'animation.shiksha.com/' => '/animation-gaming-courses-in-india-categorypage-12-1-1-0-0-1-1-2-0-none-1-0',
        'banking.shiksha.com/' => '/categoryList/CategoryList/categoryPage/4-1-1-0-0-1-1-2-0-none-1-0',
        'media.shiksha.com/' => '/media-film-mass-communication-colleges-in-india-categorypage-7-1-1-0-0-1-1-2-0-none-1-0',
        'professionals.shiksha.com/' => '/teaching-arts-law-colleges-in-india-categorypage-9-1-1-0-0-1-1-2-0-none-1-0',
        'arts.shiksha.com/' => '/teaching-arts-law-colleges-in-india-categorypage-9-1-1-0-0-1-1-2-0-none-1-0',
        'engineering.shiksha.com/' => '/science-engineering-colleges-in-india-categorypage-2-1-1-0-0-1-1-2-0-none-1-0',
        'hospitality.shiksha.com/' => '/toursim-aviation-hospitality-colleges-in-india-categorypage-6-1-1-0-0-1-1-2-0-none-1-0',
        'mba.shiksha.com/' => '/management-courses-in-india-categorypage-3-1-1-0-0-1-1-2-0-none-1-0',
        'medicine.shiksha.com/' => '/medical-colleges-in-india-categorypage-5-1-1-0-0-1-1-2-0-none-1-0',
        'retail.shiksha.com/' => '/retail-courses-in-india-categorypage-11-1-1-0-0-1-1-2-0-none-1-0',
        'design.shiksha.com/' => '/designing-courses-in-india-categorypage-13-1-1-0-0-1-1-2-0-none-1-0',
        'testprep.shiksha.com/' => '/testprep-colleges-in-india-categorypage-14-1-1-0-0-1-1-2-0-none-1-0',
        'canada.shiksha.com/' => '/study-abroad-in-canada-categorypage-1-1-1-0-0-1-1-8-0-none-1-0',
        'singapore.shiksha.com/' => '/study-abroad-in-singapore-categorypage-1-1-1-0-0-1-1-6-1-none-1-0',
        'usa.shiksha.com/' => '/study-abroad-in-usa-categorypage-1-1-1-0-0-1-1-3-0-none-1-0',
        'australia.shiksha.com/' => '/study-abroad-in-australia-categorypage-1-1-1-0-0-1-1-5-0-none-1-0',
        'newzealand.shiksha.com/' => '/study-abroad-in-newzealand-categorypage-1-1-1-0-0-1-1-7-0-none-1-0',
        'uk.shiksha.com/' => '/study-abroad-in-uk-categorypage-1-1-1-0-0-1-1-4-4-none-1-0',
        'germany.shiksha.com/' => '/study-abroad-in-germany-categorypage-1-1-1-0-0-1-1-9-2-none-1-0'
    );

		
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
