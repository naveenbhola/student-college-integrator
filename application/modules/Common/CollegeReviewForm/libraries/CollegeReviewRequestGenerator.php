<?php

class CollegeReviewRequestGenerator
{
    private $CI;
    private $solrServer;
    
    private $searchCriteria;
    private $requestParamsGlobal = array();

    function __construct()
    {
        $this->CI = & get_instance();
    }
    
    public function setSearchCriteria($searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;
    }

    public function generateSearchRequest($searchCriteria,$resultOffset,$numResults){
        
        unset($this->setSearchCriteria);
        unset($this->requestParamsGlobal);

	    $this->setSearchCriteria($searchCriteria);
	       
        $requestParams = array();

        /**
        * streamId - Stream
        */ 
        if(isset($this->searchCriteria['streamId'])) {
            $this->_getStreamRequestParams($this->searchCriteria['streamId']);
        }

        /**
        * courseId - Course
        */ 
        if(isset($this->searchCriteria['courseId'])) {
            $this->_getCourseRequestParams($this->searchCriteria['courseId']);
        }

        /**
        * baseCourse - Base Course
        */ 
        if(isset($this->searchCriteria['baseCourse'])) {
            $this->_getBaseCourseRequestParams($this->searchCriteria['baseCourse']);
        }   

        /**
        * isMapped - Mapped Flag
        */ 
        if($this->searchCriteria['isMapped']) {
            $this->_getMappedFlagRequestParams($this->searchCriteria['isMapped']);
        }

        /**
         * status - Status
         */
        if($this->searchCriteria['reviewStatus'] != '')
        {
            $this->_getStatusRequestParams($this->searchCriteria['reviewStatus']);
        }
	
        $request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
	    $request .= '?q=*%3A*&wt=phps&';
        if(count($this->requestParamsGlobal) > 0){
            $request = $request.implode('&', $this->requestParamsGlobal);
        }
        
        $request .= '&fl=reviewId,reviewerId,email,mobile,placement,faculty,infrastructure,otherDetails,creationDate,modificationDate,helpfulFlagCount,instituteId,instituteName,courseName,locationName,locationId,courseId,isMapped,yearOfGraduation,firstname,lastname,isdCode,facebookURL,reviewStatus&sort=yearOfGraduation+desc';

        $request .= '&start='.$resultOffset.'&rows='.$numResults;
        
        return $request;
    }
    
    function getSolrDate($date)
	{
		$date = date('Y-m-d H:i:s',strtotime($date));
		$dateParts = explode(' ',$date);
		return $dateParts[0].'T'.$dateParts[1].'Z';
	}


    private function _getStreamRequestParams($streamId){

        $requestParams = array();
        
        $finalParam ='fq=streamId:'.$streamId;   

        $requestParams[] = $finalParam;
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
        
    }

    private function _getCourseRequestParams($courseId){

        $requestParams = array();
        
        $finalParam ='fq=courseId:'.$courseId;   

        $requestParams[] = $finalParam;
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
        
    }

    private function _getMappedFlagRequestParams($isMapped){

        $requestParams = array();
        
        $finalParam ='fq=isMapped:'.$isMapped;   

        $requestParams[] = $finalParam;
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
        
    }

    private function _getBaseCourseRequestParams($baseCourseId){
        $requestParams = array();
        
        $finalParam ='fq=baseCourse:'.$baseCourseId;   

        $requestParams[] = $finalParam;

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getStatusRequestParams($status){
        $requestParams = array();
        
        $finalParam ='fq=reviewStatus:'.$status;   

        $requestParams[] = $finalParam;
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    

}
