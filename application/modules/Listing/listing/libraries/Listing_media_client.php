<?php
class Listing_media_client{

	var $CI;
	function init()
	{
		$this->CI =& get_instance();
		$this->CI->load->helper('url');
		$this->CI->load->library('xmlrpc');
		$this->CI->xmlrpc->set_debug(0);
		$this->CI->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);
	}

	function associateMedia($appID, $listingType, $listingId,$mediaAssociation,$metaData,$commentData = array()){
		$this->init();
		$this->CI->xmlrpc->method('sAssociateMedia');
		$request = array($appID, $listingType, $listingId, $mediaAssociation,$metaData, $commentData);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}

	function mapMediaContentWithListing($appID,$listingId,$listingType,$listingMediaType,$mediaDetails){
		$this->init();
		$this->CI->xmlrpc->method('sMapMediaContentWithListing');
		$request = array($appID,$listingId,$listingType,$listingMediaType,$mediaDetails);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}

	function updateMediaAttributesForListing($appID, $listingType, $listingId, $fileType, $fileId, $fieldName, $fieldValue) {
		$this->init();
		$this->CI->xmlrpc->method('sUpdateListingMediaAttributes');
		$request = array($appID, $listingType, $listingId, $fileType, $fileId, $fieldName, $fieldValue);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}

	function removeMediaForListing($appID, $listingType, $listingId, $fileType, $fileId) {
		$this->init();
		$this->CI->xmlrpc->method('sRemoveMediaContent');
		$request = array($appID, $listingType, $listingId, $fileType, $fileId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}

	function getMediaDetailsForListing($appID, $listingType, $listingId) {
		$this->init();
		$this->CI->xmlrpc->method('sGetMediaDetailsForListing');
		$request = array($appID, $listingType, $listingId);
		$this->CI->xmlrpc->request($request);
		if ( ! $this->CI->xmlrpc->send_request()){
			return ($this->CI->xmlrpc->display_error());
		}else{
			return ($this->CI->xmlrpc->display_response());
		}
	}


         function mapHeader($companyParam){


               $this->init();
               $this->CI->xmlrpc->method('sMapHeader');
               $request = array($companyParam[0],$companyParam[1],$companyParam[2],$companyParam[3],$companyParam[4],$companyParam[5],$companyParam[6],$companyParam[7]);
               $this->CI->xmlrpc->request($request);
               if ( ! $this->CI->xmlrpc->send_request()){ return ($this->CI->xmlrpc->display_error());}
               else{ return ($this->CI->xmlrpc->display_response());}
         }

         function mapCourseHeader($companyParam){


               $this->init();
               $this->CI->xmlrpc->method('sMapCourseHeader');
               $request = array($companyParam[0],$companyParam[1],$companyParam[2],$companyParam[3],$companyParam[4],$companyParam[5],$companyParam[6]);
               $this->CI->xmlrpc->request($request);
               if ( ! $this->CI->xmlrpc->send_request()){ return ($this->CI->xmlrpc->display_error());}
               else{ return ($this->CI->xmlrpc->display_response());}
         }

          function mapCourseCompany($companyParam){

               $this->init();
               $this->CI->xmlrpc->method('sMapCourseCompany');
               $request = array($companyParam[0],$companyParam[1],$companyParam[2],$companyParam[3],$companyParam[4],$companyParam[5]);
               $this->CI->xmlrpc->request($request);
               if ( ! $this->CI->xmlrpc->send_request()){ return ($this->CI->xmlrpc->display_error());}
               else{ return ($this->CI->xmlrpc->display_response());}
         }



         function getDistinctLogo($institute_id,$getLiveVersion =""){

               $this->init();
               $this->CI->xmlrpc->method('sGetDistinctLogo');
               $request = array($institute_id);
               $this->CI->xmlrpc->request($request);
               if ( ! $this->CI->xmlrpc->send_request()){ return ($this->CI->xmlrpc->display_error());}
               else{
                         $response= ($this->CI->xmlrpc->display_response());
                         return $response;
                   }
         }


          function getDistinctHeader($institute_id){

               $this->init();
               $this->CI->xmlrpc->method('sGetDistinctHeader');
               $request = array($institute_id);
               $this->CI->xmlrpc->request($request);
               if ( ! $this->CI->xmlrpc->send_request()){ return ($this->CI->xmlrpc->display_error());}
               else{
                         $response= ($this->CI->xmlrpc->display_response());
                         return $response;
                   }
         }






function migrateWikiSectionsInstitute($datebase) {
    error_log("FGHJ 123");
    $this->init();
    //    error_log('getInstitutesForExam'.$appID.$examId.$instituteType.$start.$count.$relaxFlag.$countryId.$cityId);
    $this->CI->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);
    error_log ("FGHJ Listing_SERVER_URL".Listing_SERVER_URL);
    $this->CI->xmlrpc->method('migrateWikiSectionsInstitute');
    $request = array($datebase);
    error_log("FGHJ 123");
    $this->CI->xmlrpc->request($request);
    error_log("FGHJ 1234");
    $response = $this->CI->xmlrpc->display_response();
    if ( ! $this->CI->xmlrpc->send_request()){
        error_log("FGHJ 1234".$this->CI->xmlrpc->display_error());
        return ($this->CI->xmlrpc->display_error());
    }
    else{
        return $response;
    }
}

function migrateWikiSectionsCourse($datebase) {
    error_log("FGHJ 123");
    $this->init();
    //    error_log('getInstitutesForExam'.$appID.$examId.$instituteType.$start.$count.$relaxFlag.$countryId.$cityId);
    $this->CI->xmlrpc->server(Listing_SERVER_URL, Listing_SERVER_PORT);
    error_log ("FGHJ Listing_SERVER_URL".Listing_SERVER_URL);
    $this->CI->xmlrpc->method('migrateWikiSectionsCourse');
    $request = array($datebase);
    error_log("FGHJ 123".print_r($request,true));
    $this->CI->xmlrpc->request($request);
    error_log("FGHJ 1234");
    $response = $this->CI->xmlrpc->display_response();
    if ( ! $this->CI->xmlrpc->send_request()){
        error_log("FGHJ 1234".$this->CI->xmlrpc->display_error());
        error_log("FGHJ ".print_r($response,true));
        return ($this->CI->xmlrpc->display_error());
    }
    else{
        return $response;
    }
}





}
?>
