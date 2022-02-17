<?php

class MatchedResponseRequestGenerator
{
    private $CI;
    private $solrServer;
    private $matchedResponsemodel;
    
    private $searchCriteria;
    private $requestParamsGlobal = array();

    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->matchedResponsemodel = new MatchedResponseAgentModel;
    }
    
    public function setSearchCriteria($searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;
    }
    
    public function generateSearchRequest($searchCriteria){
		
	    $this->setSearchCriteria($searchCriteria);
        $this->requestParamsGlobal = array();
	       
        $requestParams = array();
            
        //$requestParams[] = "fq=DocType:SearchAgent";  
            
        $this->requestParamsGlobal[] = "fq=DocType:SearchAgent";
        $this->requestParamsGlobal[] = "fq=SearchAgentType:response";
    
        //exclude genie with quota reached
        if($searchCriteria['excludeSearchAgent'] and count($searchCriteria['excludeSearchAgent'])>0) {
            $this->_excludeGenieWithQuotaReached($searchCriteria['excludeSearchAgent']);
        }

        //to exclude disbale genies
        $this->_removeDisabledGenieParam();

        /**
         * Competitive exams
         */
        if(is_array($this->searchCriteria['ExamScore']) && count($this->searchCriteria['ExamScore']) > 0) {
            $this->_getExamsRequestParams();
        }else{
            $this->_getNoExamRequestParams();
        }
        
        /**
         * Current Location
         */
        if(is_array($this->searchCriteria['CurrentLocation']) && count($this->searchCriteria['CurrentLocation']) > 0) {
			$this->_getCurrentLocationRequestParams();
        }else{
            $this->_getNoCurrentLocationRequestParams();
        }
        
        /**
         * MR Preffered Location
         */
        if(is_array($this->searchCriteria['SAPreferedMRCity']) && count($this->searchCriteria['SAPreferedMRCity']) > 0) {
			$this->_getMRPrefferedLocationRequestParams();
        }else{            
            $this->_getNoMRPrefferedLocationRequestParams();
        }
        
        if(is_array($this->searchCriteria['includeSearchAgent']) && count($this->searchCriteria['includeSearchAgent']) > 0){
            $this->_getExclusiveSearchAgents();
        }
        /**
         * Date filter
         */
        /*if($this->searchCriteria['DateFilterFrom'] && $this->searchCriteria['DateFilterTo']) {
            $requestParams = array_merge($requestParams,$this->_getDateRequestParams());
        }*/
	
        $request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
	    $request .= '?q=*%3A*&wt=phps&';
        $request = $request.implode('&', $this->requestParamsGlobal);
        
        $request .= "&group=true&group.field=clientId&group.ngroups=true";

        $request .= '&fl=SearchAgentId,clientId,deliveryMethod&sort=submitDate+desc';

        $request .= '&rows=1000000';
        
        return $request;
    }
    
    /**
     * Competitive exams search parameters
     */ 
    private function _getExamsRequestParams()
    {
        $requestParams = array();
        
        $examQuery = array();
        foreach($this->searchCriteria['ExamScore'] as $examName) {            
            $thisExamQuery = array();
            $thisExamQuery[] = 'educationName:"'.urlencode($examName).'"';
            $examQuery[] = '('.implode("%20AND%20",$thisExamQuery).')';
        }
		
		if(count($examQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$examQuery)."(*:* AND -educationName:*)";
		}
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getNoExamRequestParams(){
        $this->requestParamsGlobal[] = "fq=(*:* AND -educationName:*)";
    }
    
    
    
    /**
     * Location search parameters
     */ 
    private function _getCurrentLocationRequestParams()
    {
		
		$requestParams = array();
	
		/**
		 * Current City
		 */ 
		$currentLocationQuery = array();
		if(is_array($this->searchCriteria['CurrentLocation']) && count($this->searchCriteria['CurrentLocation']) > 0) {
			foreach($this->searchCriteria['CurrentLocation'] as $currentLocation) {
				if(intval($currentLocation)) {
					$currentLocationQuery[] = "SAcurrentlocation:".intval($currentLocation);
				}
			}
			
		}
		
		if(count($currentLocationQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$currentLocationQuery)."(*:* AND -SAcurrentlocation:*)";
		}
			
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getNoCurrentLocationRequestParams(){
        $this->requestParamsGlobal[] ="fq=(*:* AND -SAcurrentlocation:*)"; 
    }
    
    
    /**
     * Location search parameters
     */ 
    private function _getMRPrefferedLocationRequestParams()
    {
		
		$requestParams = array();
	
		/**
		 * Current City
		 */ 
		$currentLocationQuery = array();
		if(is_array($this->searchCriteria['SAPreferedMRCity']) && count($this->searchCriteria['SAPreferedMRCity']) > 0) {
			
			$preffered_location = $this->searchCriteria['SAPreferedMRCity'];
			$final_city_ids = array();
			global $requiredAffinityForCityTier;					
			
			foreach($preffered_location as $city_id=>$affinity) {
					$city_ids[] = $city_id;
			}
			
			$this->CI->load->builder('LocationBuilder','location');
			$locationBuilder = new LocationBuilder;
			$this->locationRepository = $locationBuilder->getLocationRepository();					
			$cityObjs = $this->locationRepository->findMultipleCities($city_ids);
			
			foreach($cityObjs as $cityObj) {
				    $tier = intval($cityObj->getTier());
				    $requiredAffinity = $requiredAffinityForCityTier[$tier];
				    if($preffered_location[$cityObj->getId()]>=$requiredAffinity) {
						$final_preferred_location[] = $cityObj->getId();
				    }
			}
            
			foreach($final_preferred_location as $currentLocation) {
				if(intval($currentLocation)) {
					$currentLocationQuery[] = "SAPreferedMRCity:".intval($currentLocation);
				}
			}
			
		}
		
		if(count($currentLocationQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$currentLocationQuery)."(*:* AND -SAPreferedMRCity:*)";
		}else{
            $requestParams[] = "fq=(*:* AND -SAPreferedMRCity:*)";
        }

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getNoMRPrefferedLocationRequestParams(){
        $this->requestParamsGlobal[] ="fq=(*:* AND -SAPreferedMRCity:*)";
    }

    private function _getExclusiveSearchAgents(){

        $searchAgents = $this->searchCriteria['includeSearchAgent'];


        $counter = 0;
        $totalGenie = count($searchAgents);

        foreach ($searchAgents as $genieId) {
            $counter++;
            $tempGenieArray[] = $genieId;

            if($counter%1000 == 0 || $counter == $totalGenie){
                $requestParams[] = 'SearchAgentId:('.implode('%20', $tempGenieArray).')';
                $tempGenieArray = array();
            }
        }

        $tempParam =   implode("%20OR%20",$requestParams);
        $this->requestParamsGlobal[] = "fq=(".$tempParam.")";
    }

    private function _removeDisabledGenieParam(){

       $requestParams = 'fq=( ((flagAutoDownload:"live")%20OR%20(flagAutoSMS:"live")%20OR%20(flagAutoEmail:"live"))%20OR%20(deliveryMethod:"porting") )';
        
        $this->requestParamsGlobal[] = $requestParams;


    }

    private function _excludeGenieWithQuotaReached($excludeGenieIds){
        $counter = 0;

        $totalGenie = count($excludeGenieIds);

        foreach ($excludeGenieIds as $genieId) {
            $counter++;
            $tempGenieArray[] = $genieId;

            if($counter%500 == 0 || $counter == $totalGenie){
                $requestParams[] = '-SearchAgentId:('.implode('%20', $tempGenieArray).')';
                $tempGenieArray = array();
            }
        }

        $tempParam =   implode("%20AND%20",$requestParams);
        $this->requestParamsGlobal[] = "fq=(".$tempParam.")";

    }
}
