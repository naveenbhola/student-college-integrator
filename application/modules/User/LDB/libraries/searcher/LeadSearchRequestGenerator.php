<?php

class LeadSearchRequestGenerator
{
    private $CI;
    private $solrServer;
    private $leadSearchModel;
	private $locationRepository;
    
    private $searchCriteria;

    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->model('LDB/leadsearchmodel');
        $this->leadSearchModel = new LeadSearchModel;
		
		$this->CI->load->builder('LocationBuilder','location');
		$locationBuilder = new LocationBuilder;
        $this->locationRepository = $locationBuilder->getLocationRepository();
    }
    
    public function setSearchCriteria($searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;
    }

    public function generate($searchCriteria,$resultOffset,$numResults,$clientUserId){
	$this->setSearchCriteria($searchCriteria);
	
	$isMRSearch = false;
	if($this->searchCriteria['responseSubmitDateStart'] && $this->searchCriteria['responseSubmitDateEnd'] && count($this->searchCriteria['matchedCourses'])) {
	    $isMRSearch = true;
	}        

	//Set request params for LDB/MR search
        $requestParams = array();
            
    	if($isMRSearch === false) {
            if(isset($this->searchCriteria['isLDBFlag']) && $this->searchCriteria['isLDBFlag'] == 'YES'){
    	       $requestParams[] = "fq=isLDBUser:YES";
            }

    	    $requestParams[] = "fq=isResponseLead:NO";    
    	}
    
        $requestParams[] = "fq=-usergroup:sums";
        $requestParams[] = "fq=-usergroup:enterprise";
        $requestParams[] = "fq=-usergroup:cms";
        $requestParams[] = "fq=-usergroup:experts";
        $requestParams[] = "fq=-usergroup:lead_operator";
        $requestParams[] = "fq=-usergroup:saAdmin";
        $requestParams[] = "fq=-usergroup:saCMS";
        $requestParams[] = "fq=-usergroup:saContent";
        $requestParams[] = "fq=-usergroup:saSales";
        $requestParams[] = 'fq=DocType:"user"';

        if($this->searchCriteria['isTestUser']){
            $requestParams[] = "fq=isTestUser:".$this->searchCriteria['isTestUser'];
        }else{
            $requestParams[] = "fq=isTestUser:NO";
        }
    	
        if($this->searchCriteria['hardbounce']){
            $requestParams[] = "fq=hardbounce:".$this->searchCriteria['hardbounce'];
        }else{
            $requestParams[] = "fq=hardbounce:0";
        }
        
        if($this->searchCriteria['softbounce']){
            $requestParams[] = "fq=softbounce:".$this->searchCriteria['softbounce'];
        }else{
            $requestParams[] = "fq=softbounce:0";
        }
       
        if($this->searchCriteria['ownershipchallenged']){
            $requestParams[] = "fq=ownershipchallenged:".$this->searchCriteria['ownershipchallenged'];
        }else{
            $requestParams[] = "fq=ownershipchallenged:0";
        }
       
        if($this->searchCriteria['abused']){
            $requestParams[] = "fq=abused:".$this->searchCriteria['abused'];
        }else{
            $requestParams[] = "fq=abused:0";
        }
       
        if(isset($this->searchCriteria['Unsubscribe']) && $this->searchCriteria['Unsubscribe'] == '0') {
            $requestParams[] = "fq=unsubscribe:0";
        }
        
        if(!(isset($this->searchCriteria['mobileVerified']) && $this->searchCriteria['mobileVerified'] == '0')) {
            $requestParams[] = "fq=mobileverified:1";
        }

        //unsubscribe preference flag
        if(isset($this->searchCriteria['email_pref_5']) && $this->searchCriteria['email_pref_5']) {
            $requestParams[] = "fq=-email_pref_5:true";
        }


        /**
        *   LDB exclusion table
        */   

        /*if(isset($this->searchCriteria['includeExcludedUsersType']) && (!empty($this->searchCriteria['includeExcludedUsersType']))) {
            
            $includeExcludedUsersType = array();$includeExcludedUsersTypes = "";
            
            foreach($this->searchCriteria['includeExcludedUsersType'] as $includeExludedUsersType) {
                $includeExcludedUsersType[] = "exclusionType:\"".$includeExludedUsersType."\"";
            }

            $includeExcludedUsersTypes = implode("%20OR%20",$includeExcludedUsersType);
            
            $requestParams[] = "fq=(*:*%20-ldbExclusion:1)%20OR%20(ldbExclusion:1%20AND%20(".$includeExcludedUsersTypes."))";

        } else {
          */

        if($this->searchCriteria['includeExcludedUsersType']) { 
        } else {
            $requestParams[] = "fq=-ldbExclusion:1";
        }


        /**
        * Stream
        */ 
        if(isset($this->searchCriteria['stream'])) {
            $requestParams = array_merge($requestParams,$this->_getStreamRequestParams($this->searchCriteria['stream']));
        }

        /**
        * Sub Stream & Specialization
        */ 
        if(((is_array($this->searchCriteria['subStreamSpecializationMapping']) && count($this->searchCriteria['subStreamSpecializationMapping']) > 0)) || (is_array($this->searchCriteria['ungroupedSpecializations']) && count($this->searchCriteria['ungroupedSpecializations']) > 0)) {
            $requestParams = array_merge($requestParams,$this->_getSubStreamSpecializationRequestParams());
        }

        /**
        * courseId - Popular Course
        */ 
        if(is_array($this->searchCriteria['courseId']) && count($this->searchCriteria['courseId']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getPopularCourseRequestParams($this->searchCriteria['courseId']));
        }

        /**
        * Attribute Ids - commenting code; as search on only attribute value is requried
        */ 
        /*if(is_array($this->searchCriteria['attributeIds']) && count($this->searchCriteria['attributeIds']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getAttributeIdsRequestParams($this->searchCriteria['attributeIds']));
        }*/


        /**
        * Attribute Values
        */ 
        if(is_array($this->searchCriteria['attributeValues']) && count($this->searchCriteria['attributeValues']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getAttributeValuesRequestParams($this->searchCriteria['attributeValues']));
        }

        /**
         * Desired course
         */ 
        if($this->searchCriteria['ExtraFlag'] == 'studyabroad' || $this->searchCriteria['ExtraFlag'] == 'testprep') {
            $requestParams = array_merge($requestParams,$this->_getDesiredCourseRequestParams());
        } else if($this->searchCriteria['ExtraFlag'] == 'national'){
            $requestParams = array_merge($requestParams,$this->_getNationalUserParams());            
        }
        
        /**
         * Course specializations
         */ 
        if(is_array($this->searchCriteria['Specialization']) && count($this->searchCriteria['Specialization']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getCourseSpecializationRequestParams());
        }

	/**
         * Abroad specializations
         */
        if(is_array($this->searchCriteria['abroadSpecializations']) && count($this->searchCriteria['abroadSpecializations']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getAbroadSpecializationRequestParams());
        }

        
        /**
         * UG course
         */ 
        if(is_array($this->searchCriteria['UGCourse']) && count($this->searchCriteria['UGCourse']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getUGCourseRequestParams());
        }
        
        /**
         * Graduation completion date
         */ 
        if($this->searchCriteria['GraduationCompletedFrom'] || $this->searchCriteria['GraduationCompletedTo']) {
            $requestParams = array_merge($requestParams,$this->_getGraduationCompletionDateRequestParams());
        }
        
        /**
         * Graduation marks
         */ 
        if($this->searchCriteria['MinGradMarks']) {
            $requestParams = array_merge($requestParams,$this->_getGraduationMarksRequestParams());
        }
        
        /**
         * Graduation result awaited i.e. status ongoing
         */
	/*
        if(!$this->searchCriteria['IncludeResultsAwaited']) {
            $requestParams = array_merge($requestParams,$this->_getGraduationResultAwaitedRequestParams());
        }
        */

        /**
        * XII completion date - not to be used
        */ 
        /*if($this->searchCriteria['XIICompletedFrom'] || $this->searchCriteria['XIICompletedTo']) {
            $requestParams = array_merge($requestParams,$this->_getXIICompletionDateRequestParams());
        }*/
	
        /**
         * XII marks - not to be used
         */
        /*if($this->searchCriteria['MinXIIMarks']) {
            $requestParams = array_merge($requestParams,$this->_getXIIMarksRequestParams());
        }*/
        
        /**
         * XII stream - Not used, to be deleted
         */
        /*if(is_array($this->searchCriteria['XIIStream']) && count($this->searchCriteria['XIIStream']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getXIIStreamRequestParams());
        }*/
        

        /**
         * Work experience
         */
        if($this->searchCriteria['MinExp'] || $this->searchCriteria['MaxExp']) {
            $requestParams = array_merge($requestParams,$this->_getWorkExperienceRequestParams());
        }
        
        /**
         * Mode - Not used, to be deleted
         */
       /* if($this->searchCriteria['ModeFullTime'] == 'yes' || $this->searchCriteria['ModePartTime'] == 'yes') {
            $requestParams = array_merge($requestParams,$this->_getModeRequestParams());
        }*/

        
        /**
         * Degree preference - Not used, to be deleted
         */
        /*if($this->searchCriteria['DegreePrefAny'] == 'yes' || $this->searchCriteria['DegreePrefAICTE'] == 'yes' || $this->searchCriteria['DegreePrefUGC'] == 'yes' || $this->searchCriteria['DegreePrefInternational'] == 'yes') {
            $requestParams = array_merge($requestParams,$this->_getDegreePrefRequestParams());
        }*/
        
        /**
         * Competitive exams
         */
        if(is_array($this->searchCriteria['ExamScore']) && count($this->searchCriteria['ExamScore']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getExamsRequestParams());
        }


        if(is_array($this->searchCriteria['exams']) && count($this->searchCriteria['exams']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getExamsNameRequestParams());
        }

        /*
        *   exclude MR profile from search
        */    
        if($this->searchCriteria['excludeMRPRofile']) {
            $requestParams = array_merge($requestParams,$this->_getExcludeMRProfileParams());
        }
        
        if($this->searchCriteria['isFTExclusion']) {
            $requestParams = array_merge($requestParams,$this->_getExcludeFullTimeProfileParams());
        }

        if($this->searchCriteria['isMRCourseExclusion']) {
            $requestParams = array_merge($requestParams,$this->_getExcludeMRCourseProfileParams());
        }

        /**
         * Location
         */
        if((is_array($this->searchCriteria['Locality']) && count($this->searchCriteria['Locality']) > 0) ||
           (is_array($this->searchCriteria['CurrentLocation']) && count($this->searchCriteria['CurrentLocation']) > 0) ||
           (is_array($this->searchCriteria['PreferredLocation']) && count($this->searchCriteria['PreferredLocation']) > 0) ||
	   (is_array($this->searchCriteria['currentLocalities']) && count($this->searchCriteria['currentLocalities']) > 0) ||
	   (is_array($this->searchCriteria['CurrentCities']) && count($this->searchCriteria['CurrentCities']) > 0))
        {
            $requestParams = array_merge($requestParams,$this->_getLocationRequestParams());
        }
        
		/**
		 * MR preferred location
		 */
		if(is_array($this->searchCriteria['MRLocation']) && count($this->searchCriteria['MRLocation']) > 0) {
			$requestParams = array_merge($requestParams, $this->_getMRPreferredLocationRequestParams());
		}
		
		/**
         * Response City location
         */
        if(is_array($this->searchCriteria['responseCities']) && count($this->searchCriteria['responseCities']) > 0) {
            $requestParams = array_merge($requestParams, $this->_getResponseCityRequestParams());
        }
		
        /**
         * Gender - Not used, to be deleted
         */
        /*if(is_array($this->searchCriteria['Gender']) && count($this->searchCriteria['Gender']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getGenderRequestParams());
        }*/
        
        /**
         * Age - Not used, to be deleted
         */
        /*if($this->searchCriteria['MinAge'] || $this->searchCriteria['MaxAge']) {
            $requestParams = array_merge($requestParams,$this->_getAgeRequestParams());
        }*/
        

        /**
         * Passport
         */
        if($this->searchCriteria['passport']) {
            $requestParams = array_merge($requestParams,$this->_getPassportRequestParams());
        }
        
        /**
         * Budget
         */
        //if(is_array($this->searchCriteria['budget']) && count($this->searchCriteria['budget']) > 0) {
        //    $requestParams = array_merge($requestParams,$this->_getBudgetRequestParams());
        //}
        
	/**
         * Plan to start
         */
        if(is_array($this->searchCriteria['planToStart']) && count($this->searchCriteria['planToStart']) > 0) {
            $requestParams = array_merge($requestParams,$this->_getPlanToStartRequestParams());
        }
	
        /**
         * Date filter
         */
        if($this->searchCriteria['DateFilterFrom'] && $this->searchCriteria['DateFilterTo']) {
            $requestParams = array_merge($requestParams,$this->_getDateRequestParams());
        }
        
        /**
         * Couseling Users filter (only for abroad MMM search)
         */
        if($this->searchCriteria['ExtraFlag'] == 'studyabroad' && $this->searchCriteria['isMMMSearch']){
            $requestParams = array_merge($requestParams,$this->_getCounselingUsersRequestParams());
        }
        
        /**
         * User profile type
         */
        if($this->searchCriteria['ProfileType']) {
            $requestParams = array_merge($requestParams,$this->_getProfileType());
        }
        
	
	    
	     /**
         * Response submit date and matched courses for MR Search
         */
        if($isMRSearch === true) {
            $requestParams = array_merge($requestParams,$this->_getMatchedCourseParams());
	        $requestParams = array_merge($requestParams,$this->_getSubmitDateRequestParams());
	    }
	
        if($this->searchCriteria['MRCourseSearch']) {
            $requestParams = array_merge($requestParams,$this->_getMatchedCourseParams());
        }
        

        $request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
	    $request .= '?q=*%3A*&wt=phps&';
        $request .= implode('&',$requestParams);

        if($this->searchCriteria['userChunkFlag']){
            $request .='&fq=userId:('.implode('%20', $this->searchCriteria['userChunk']).')';
        }

        if($this->searchCriteria['ContactedLeads']){
		$contactedLeads = $this->searchCriteria['ContactedLeads'];

            $itr= 0;

            foreach ($contactedLeads as $lead) {
                $itr++;
                $tempLeads[]= $lead;

                if($itr%500 == 0){
                    $userIdParam[] ='-userId:('.implode('%20', $tempLeads).')';
                    $tempLeads = array();
                }

            }

            if(count($tempLeads)>0){
                $userIdParam[] ='-userId:('.implode('%20', $tempLeads).')';     //to process last chunk
            }

            $tempParam =   implode("%20OR%20",$userIdParam); 
            $request .= "&fq=(".$tempParam.")";
        }

        if(($this->searchCriteria['DontShowViewed'] && $this->searchCriteria['countFlag']) || $this->searchCriteria['ExtraFlag'] =='studyabroad' || ($this->searchCriteria['isMMMSearch'])) {

            $request .= '&fl=userId&sort=submitDate+desc';
        } elseif($this->searchCriteria['userdataonly']) {

			$request .= '&fl=userId,displayData,streamId,subStreamId,specialization,attributeValues,courseId,workex,educationName,location_affinity_*'; 

		} else {

    	  $request .= '&fl=userId,displayData,streamId,subStreamId,specialization,attributeValues,courseId,workex,ViewCredit,SmsCredit,EmailCredit,ViewCount,responseCourse,response_time_*&sort=submitDate+desc';

        }

        if($this->searchCriteria['countFlag'] ){

          $request .= '&rows=0&group=true&group.field=userId&group.ngroups=true&group.sort=ViewCredit+desc';            

        } else if($this->searchCriteria['count'] > 0) {

            $request .= ',id+asc&rows='.$this->searchCriteria['count'];

        } else if($this->searchCriteria['numResults'] > 0 && $this->searchCriteria['ExtraFlag'] !='studyabroad') {

            $request .= '&start='.$this->searchCriteria['resultOffset'].'&rows='.$this->searchCriteria['numResults'];
            
            if($this->searchCriteria['grouping']){
                $request .='&group=true&group.field=userId';
            }

        } else {
            
            $request .= '&rows=100000';
        }

        return $request;
    }
    
    /**
     * Desired course search parameters
     */ 
    private function _getDesiredCourseRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['ExtraFlag'] == 'studyabroad') {
			$desiredCourseQuery = array();
			foreach($this->searchCriteria['DesiredCourseId'] as $desiredCourseId) {
                if($desiredCourseId > 0) {
				    $desiredCourseQuery[] = "desiredCourse:".intval($desiredCourseId);
                }   
			}
			$requestParams[] = "fq=".implode('%20OR%20',$desiredCourseQuery)."&extraFlag=studyabroad";
		}
        else if($this->searchCriteria['ExtraFlag'] == 'testprep') {
			$desiredCourseQuery = array();
			foreach($this->searchCriteria['testPrep_blogid'] as $desiredCourseId) {
				$desiredCourseQuery[] = "testPrepCourseId:".intval($desiredCourseId);
			}
			$requestParams[] = "fq=".implode('%20OR%20',$desiredCourseQuery)."&extraFlag=testprep";
		}
		else if($this->searchCriteria['DesiredCourse'] && $this->searchCriteria['search_category_id']) {
			if(is_array($this->searchCriteria['DesiredCourse']) && count($this->searchCriteria['DesiredCourse']) > 0) {
				$desiredCourses = $this->searchCriteria['DesiredCourse'];
			}
			else {
				$desiredCourses = array($this->searchCriteria['DesiredCourse']);
			}

            $desiredCourseIds = $this->leadSearchModel->getDesiredCoursesByName($desiredCourses,$this->searchCriteria['search_category_id']);
        
			$desiredCourseQuery = array();
			foreach($desiredCourseIds as $desiredCourseId) {
				$desiredCourseQuery[] = "desiredCourse:".intval($desiredCourseId);
			}
			
			$requestParams[] = "fq=".implode('%20OR%20',$desiredCourseQuery).'&fq=-extraFlag:[""%20TO%20*]';
		}
        
        return $requestParams;
    }
    
    /**
     * Course specialization search parameters
     */ 
    private function _getCourseSpecializationRequestParams()
    {
        $requestParams = array();
        
        $specializationQuery = array();
        foreach($this->searchCriteria['Specialization'] as $specialization) {
            if(intval($specialization)) {
                $specializationQuery[] = "specializationId:".intval($specialization);
            }
        }
		
		if(count($specializationQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$specializationQuery);
		}
        
        return $requestParams;
    }
    
    /**
     * Abroad specialization search parameters
     */
    private function _getAbroadSpecializationRequestParams()
    {
        $requestParams = array();

        $specializationQuery = array();
        foreach($this->searchCriteria['abroadSpecializations'] as $specialization) {
            if(intval($specialization)) {
                $specializationQuery[] = "abroad_subcat_id:".intval($specialization);
            }
        }

                if(count($specializationQuery) > 0) {
                        $requestParams[] = "fq=".implode('%20OR%20',$specializationQuery);
                }

        return $requestParams;
    }


    /**
     * UG course search parameters
     */ 
    private function _getUGCourseRequestParams()
    {
        $requestParams = array();
        
        $UGCourseQuery = array();
        foreach($this->searchCriteria['UGCourse'] as $UGCourse) {
            if($UGCourse) {
		$UGCourseQuery[] = "educationName:\"".urlencode($UGCourse)."\"";
            }
        }
		
		if(count($UGCourseQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$UGCourseQuery);
		}
        
        return $requestParams;
    }
    
    /**
     * Graduation completion date search parameters
     */ 
    private function _getGraduationCompletionDateRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['GraduationCompletedFrom'] && $this->searchCriteria['GraduationCompletedTo']) {
			$requestParams[] = "fq=UGCompletionDate:[".$this->getSolrDate($this->searchCriteria['GraduationCompletedFrom'])."%20TO%20".$this->getSolrDate($this->searchCriteria['GraduationCompletedTo'])."]";
		}
		else if($this->searchCriteria['GraduationCompletedFrom']) {
			$requestParams[] = "fq=UGCompletionDate:[".$this->getSolrDate($this->searchCriteria['GraduationCompletedFrom'])."%20TO%20*]";
		}
		else if($this->searchCriteria['GraduationCompletedTo']) {
			$requestParams[] = "fq=UGCompletionDate:[*%20TO%20".$this->getSolrDate($this->searchCriteria['GraduationCompletedTo'])."]";
		}
        
        return $requestParams;
    }
    
    /**
     * Graduation marks search parameters
     */ 
    private function _getGraduationMarksRequestParams()
    {
        $requestParams = array();
		$requestParams[] = "fq=UGMarks:[".$this->searchCriteria['MinGradMarks']."%20TO%20*]";
        return $requestParams;
    }
    
    /**
     * Graduation result awaited search parameters
     */ 
    private function _getGraduationResultAwaitedRequestParams()
    {
        $requestParams = array();
		$requestParams[] = "fq=-UGStatus:Ongoing";
        return $requestParams;
    }
    
    /**
     * XII completion date search parameters
     */ 
    private function _getXIICompletionDateRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['XIICompletedFrom'] && $this->searchCriteria['XIICompletedTo']) {
	    $requestParams[] = "fq=XIICompletionDate:[".$this->getSolrDate($this->searchCriteria['XIICompletedFrom'])."%20TO%20".$this->getSolrDate($this->searchCriteria['XIICompletedTo'])."]";
	}
	else if($this->searchCriteria['XIICompletedFrom']) {
	    $requestParams[] = "fq=XIICompletionDate:[".$this->getSolrDate($this->searchCriteria['XIICompletedFrom'])."%20TO%20*]";
	}
	else if($this->searchCriteria['XIICompletedTo']) {
	    $requestParams[] = "fq=XIICompletionDate:[*%20TO%20".$this->getSolrDate($this->searchCriteria['XIICompletedTo'])."]";
	}
        
        return $requestParams;
    }
    
    
    /**
     * XII marks search parameters
     */ 
    private function _getXIIMarksRequestParams()
    {
        $requestParams = array();
		$requestParams[] = "fq=XIIMarks:[".$this->searchCriteria['MinXIIMarks']."%20TO%20*]";
        return $requestParams;
    }
    
    /**
     * XII stream search parameters
     */ 
    private function _getXIIStreamRequestParams()
    {
        $requestParams = array();
        
        $XIIStreamQuery = array();
        foreach($this->searchCriteria['XIIStream'] as $XIIStream) {
            if($XIIStream) {
                $XIIStreamQuery[] = "XIIStream:".ucfirst(strtolower($XIIStream));
            }
        }
		
		if(count($XIIStreamQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$XIIStreamQuery);
		}
        
        return $requestParams;
    }
    
    /**
     * Work experience search parameters
     */ 
    private function _getWorkExperienceRequestParams()
    {
        $requestParams = array();
        if(isset($this->searchCriteria['MinExp']) && isset($this->searchCriteria['MaxExp'])) {
			$requestParams[] = "fq=workex:[".$this->searchCriteria['MinExp']."%20TO%20".$this->searchCriteria['MaxExp']."]";
		}
		else if($this->searchCriteria['MinExp']) {
			$requestParams[] = "fq=workex:[".$this->searchCriteria['MinExp']."%20TO%20*]";
		}
		else if($this->searchCriteria['MaxExp']) {
			$requestParams[] = "fq=workex:[*%20TO%20".$this->searchCriteria['MaxExp']."]";
		}
        
        return $requestParams;
    }
    
    /**
     * Mode search parameters
     */ 
    /*private function _getModeRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['ModeFullTime'] == 'yes' && $this->searchCriteria['ModePartTime'] == 'yes') {
			$requestParams[] = "fq=modeOfEducationFullTime:yes%20OR%20modeOfEducationPartTime:yes";
		}
		else if($this->searchCriteria['ModeFullTime'] == 'yes') {
			$requestParams[] = "fq=modeOfEducationFullTime:yes";
		}
		else if($this->searchCriteria['ModePartTime'] == 'yes') {
			$requestParams[] = "fq=modeOfEducationPartTime:yes";
		}
        
        return $requestParams;
    }*/
    
    /**
     * Degree preference search parameters
     */ 
    private function _getDegreePrefRequestParams()
    {
        $requestParams = array();
        
        $degreePrefQuery = array();
		if($this->searchCriteria['DegreePrefAny'] == 'yes') {
			$degreePrefQuery[] = 'degreePrefAny:yes';
		}
		if($this->searchCriteria['DegreePrefAICTE'] == 'yes') {
			$degreePrefQuery[] = 'degreePrefAICTE:yes';
		}
		if($this->searchCriteria['DegreePrefUGC'] == 'yes') {
			$degreePrefQuery[] = 'degreePrefUGC:yes';
		}
		if($this->searchCriteria['DegreePrefInternational'] == 'yes') {
			$degreePrefQuery[] = 'degreePrefInternational:yes';
		}
		if(count($degreePrefQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$degreePrefQuery);
		}
        
        return $requestParams;
    }
    
    /**
     * Competitive exams search parameters
     */ 
    private function _getExamsRequestParams()
    {
        $requestParams = array();
        
        $examQuery = array();
        foreach($this->searchCriteria['ExamScore'] as $examName => $examDetails) {
            
            $thisExamQuery = array();
            $thisExamQuery[] = "educationName:".$examName;
            
            $cleanExamName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $examName));
            
            if($examDetails['min'] && $examDetails['max']) {
                $thisExamQuery[] = $cleanExamName."_educationMarks:[".$examDetails['min']."%20TO%20".$examDetails['max']."]";
            }
            else if($examDetails['min']) {
                $thisExamQuery[] = $cleanExamName."_educationMarks:[".$examDetails['min']."%20TO%20*]";
            }
            else if($examDetails['max']) {
                $thisExamQuery[] = $cleanExamName."_educationMarks:[*%20TO%20".$examDetails['max']."]";
            }
            
            if($examDetails['year']) {
                $thisExamQuery[] = $cleanExamName."_educationCourseCompletionDate:[".$this->getSolrDate($examDetails['year'])."%20TO%20".$this->getSolrDate($examDetails['year'])."]";
            }
            
            $examQuery[] = '('.implode("%20AND%20",$thisExamQuery).')';
        }
		
		if(count($examQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$examQuery);
		}
        
        return $requestParams;
    }

    /**
    * Exam name search parameters
    */ 

    private function _getExamsNameRequestParams()
    {
        $requestParams = array();

        $examQuery = $this->searchCriteria['exams'];
        $requestParams[] = 'fq=educationName:("'.implode('"%20OR%20"', $examQuery).'")';

        return $requestParams;
    }
    
    /**
     * Degree preference search parameters
     */ 
    private function _getDateRequestParams()
    {
        $requestParams = array();

        if($this->searchCriteria['DateFilterFrom'] && $this->searchCriteria['DateFilterTo']) {

            $dateParams = 'fq=submitDate:['.$this->getSolrDate($this->searchCriteria['DateFilterFrom']).'%20TO%20'.$this->getSolrDate($this->searchCriteria['DateFilterTo']).']';
            
            if($this->searchCriteria['includeActiveUsers']) {
                $dateParams .= '%20OR%20lastlogintime:['.$this->getSolrDate($this->searchCriteria['DateFilterFrom']).'%20TO%20'.$this->getSolrDate($this->searchCriteria['DateFilterTo']).']';
            }

            if($this->searchCriteria['ExtraFlag'] == 'studyabroad'){ 
               $endTime = date('Y-m-d H:i:s', strtotime('-15 min'));                     //to exclude lead registered in last 30min  consultant response
               $dateParams .= '%20AND%20submitDate:[*%20TO%20'.$this->getSolrDate($endTime).']';
            }

			$requestParams[] = $dateParams;
		}
        
        return $requestParams;
    }

    /**
     * Counseling User Search parameters
     * @author Mansi Gupta
     * @date   2018-10-29
     */
    private function _getCounselingUsersRequestParams() {

        $requestParams = array();
        if(!$this->searchCriteria['includeCounselingUsers']) {
            $requestParams[] = "fq=-isInCounselingLoop:true";
        }
        return $requestParams;
    }
    
    /**
     * Location search parameters
     */ 
    private function _getLocationRequestParams()
    {
        $requestParams = array();
        
		 /**
		 * Current Locality and city
		 */  
		if(is_array($this->searchCriteria['CurrentCities']) && count($this->searchCriteria['CurrentCities']) > 0) {
			$CurrentCities = array();
			 for($i=0;$i<count($this->searchCriteria['CurrentCities']); $i++ ){
				if(intval($this->searchCriteria['CurrentCities'][$i]) && is_array($this->searchCriteria['currentLocalities'][$i])) {
					$localityQuery[] = "(city:".intval($this->searchCriteria['CurrentCities'][$i])."%20AND%20locality:(".implode('%20',$this->searchCriteria['currentLocalities'][$i])."))";
				}else if(intval($this->searchCriteria['CurrentCities'][$i]) && !is_array($this->searchCriteria['currentLocalities'][$i])){
				    $localityQuery[] = "(city:".intval($this->searchCriteria['CurrentCities'][$i]).")";
				}
			}
			if(count($localityQuery) > 0) {
				$requestParams[] = "fq=(".implode('%20OR%20',$localityQuery).")";
			}
		}
		
		/**
		 * Locality
		 */
		
		if(is_array($this->searchCriteria['Locality']) && count($this->searchCriteria['Locality']) > 0) {
		/*	$localityQuery = array();
			foreach($this->searchCriteria['Locality'] as $locality) {
				if(intval($locality)) {
					$localityQuery[] = "locationPrefLocalityId:".intval($locality);
				}
			}
			if(count($localityQuery) > 0) {
				$requestParams[] = "fq=".implode('%20OR%20',$localityQuery);
		
			}*/
		}
		else {
			/**
			 * Current City
			 */ 
			$currentLocationQuery = array();
			if(is_array($this->searchCriteria['CurrentLocation']) && count($this->searchCriteria['CurrentLocation']) > 0) {
				foreach($this->searchCriteria['CurrentLocation'] as $currentLocation) {
					if(intval($currentLocation)) {
						$currentLocationQuery[] = "city:".intval($currentLocation);
					}
				}
			    
			}
			
			/**
			 * Preferred Locations
			 */ 
			$preferredLocationQuery = array();
			if(is_array($this->searchCriteria['PreferredLocation']) && count($this->searchCriteria['PreferredLocation']) > 0) {
				foreach($this->searchCriteria['PreferredLocation'] as $preferredLocation) {
					if($preferredLocation) {
						$preferredLocationParts = json_decode(base64_decode($preferredLocation),TRUE);
						if(intval($preferredLocationParts['cityId'])) {
							$preferredLocationQuery[] = "locationPrefCityId:".intval($preferredLocationParts['cityId']);
						}
						else if(intval($preferredLocationParts['stateId'])) {
							$preferredLocationQuery[] = "locationPrefStateId:".intval($preferredLocationParts['stateId']);
						}
						else if(intval($preferredLocationParts['countryId'])) {
							$preferredLocationQuery[] = "locationPrefCountryId:".intval($preferredLocationParts['countryId']);
						}
					}
				}
			}
			
			if(count($currentLocationQuery) > 0 && count($preferredLocationQuery) > 0) {
				$solrRequest = "fq=(".implode('%20OR%20',$currentLocationQuery).')';
				
				if($this->searchCriteria['LocationAndOr'] == 1) {
					$solrRequest .= '%20OR%20';	
				}
				else {
					$solrRequest .= '%20AND%20';
				}
				
				$solrRequest .= '('.implode('%20OR%20',$preferredLocationQuery).')';
                $requestParams[] = $solrRequest;
			}
			else if(count($currentLocationQuery) > 0) {
				$requestParams[] = "fq=".implode('%20OR%20',$currentLocationQuery);
			}
			else if(count($preferredLocationQuery) > 0) {
				$requestParams[] = "fq=".implode('%20OR%20',$preferredLocationQuery);
			}
		}
        
        return $requestParams;
    }
    
	private function _getMRPreferredLocationRequestParams()
    {
		global $requiredAffinityForCityTier;
		
		$MRLocationQuery = array();
		$requestParams = array();
		
		if(is_array($this->searchCriteria['MRLocation']) && count($this->searchCriteria['MRLocation']) > 0) {
			$cities = array();
			foreach($this->searchCriteria['MRLocation'] as $MRLocation) {
				if(intval($MRLocation)) {
					$cities[] = intval($MRLocation);
				}
			}
			
			$cityObjs = $this->locationRepository->findMultipleCities($cities);
			foreach($cityObjs as $cityObj) {
				$tier = intval($cityObj->getTier());
				$requiredAffinity = $requiredAffinityForCityTier[$tier] ? $requiredAffinityForCityTier[$tier] : 0;
				$MRLocationQuery[] = "location_affinity_".$cityObj->getId().":[$requiredAffinity TO *]";		
			}
		}
		
		if(count($MRLocationQuery) > 0 && count($MRLocationQuery) > 0) {
			$solrRequest = "fq=(".implode('%20OR%20',$MRLocationQuery).')';
			$requestParams[] = $solrRequest;
		}
		
		return $requestParams;
	}
	
    /**
     * Degree preference search parameters
     */ 
    private function _getGenderRequestParams()
    {
        $requestParams = array();
        
        $genderQuery = array();
        foreach($this->searchCriteria['Gender'] as $gender) {
            if($gender) {
                $genderQuery[] = "gender:".$gender;
            }
        }
		
		if(count($genderQuery) > 0) {
			$requestParams[] = "fq=".implode('%20OR%20',$genderQuery);
		}
        
        return $requestParams;
    }
    
    /**
     * Age search parameters
     */ 
    private function _getAgeRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['MinAge'] && $this->searchCriteria['MaxAge']) {
			$requestParams[] = "fq=age:[".$this->searchCriteria['MinAge']."%20TO%20".$this->searchCriteria['MaxAge']."]";
		}
		else if($this->searchCriteria['MinAge']) {
			$requestParams[] = "fq=age:[".$this->searchCriteria['MinAge']."%20TO%20*]";
		}
		else if($this->searchCriteria['MaxAge']) {
			$requestParams[] = "fq=age:[*%20TO%20".$this->searchCriteria['MaxAge']."]";
		}
        
        return $requestParams;
    }
    
    /**
     * Passport search parameters
     */ 
    private function _getPassportRequestParams()
    {
        $requestParams = array();
	if($this->searchCriteria['passport'] == 'yes') {
		$requestParams[] = "fq=passport:yes";
	}
	else {
		$requestParams[] = "fq=passport:no";
	}
        return $requestParams;
    }
    
    /**
     * Budget search parameters
     */ 
//    private function _getBudgetRequestParams()
//    {
//        $requestParams = array();
//        
//        $budgetQuery = array();
//        foreach($this->searchCriteria['budget'] as $budget) {
//            $budgetQuery[] = "budget:".$budget;
//        }
//		
//		$requestParams[] = "fq=".implode('%20OR%20',$budgetQuery);
//        return $requestParams;
//    }
    
    /**
     * Plan to start search parameters
     */ 
    private function _getPlanToStartRequestParams()
    {
        $requestParams = array();
        
        $planToStartQuery = array();
        foreach($this->searchCriteria['planToStart'] as $planToStart) {
    	    if($planToStart == 'Later'){
        		$year = date('Y',strtotime('+2 year'));
        		$startDate = $year."-01-01 00:00:00";
        		$planToStartQuery[] = "timeOfStart:[".$this->getSolrDate($startDate)."%20TO%20*]";
    	    }
    	    else {
        		$startDate = $planToStart."-01-01 00:00:00";
        		$endDate = $planToStart."-12-31 23:59:59";
        		$planToStartQuery[] = "timeOfStart:[".$this->getSolrDate($startDate)."%20TO%20".$this->getSolrDate($endDate)."]";
    	    }
        }
	
	$requestParams[] = "fq=".implode('%20OR%20',$planToStartQuery);
        return $requestParams;
    }
    
    function getSolrDate($date)
	{
		$date = date('Y-m-d H:i:s',strtotime($date));
		$dateParts = explode(' ',$date);
		return $dateParts[0].'T'.$dateParts[1].'Z';
	}

   public function generateRequestForUserDetails($userIds)
   {
	$request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
        $request .= '?q=*%3A*&wt=phps';
	$requestParams = array();
	foreach($userIds as $userId) {
		$requestParams[] = "userId:".$userId;
	}
	$requestParams = implode('%20OR%20',$requestParams);
        $request .= '&fq='.$requestParams;
        $request .= '&fl=userId,displayData&rows=1000&sort=submitDate+desc';
	
        return $request;
    }
    
    /**
     * Response submit date search parameters for MR search
     */ 
    private function _getSubmitDateRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['responseSubmitDateStart'] && $this->searchCriteria['responseSubmitDateEnd']) {
            $dateParams = 'fq=responseSubmitDate:['.$this->getSolrDate($this->searchCriteria['responseSubmitDateStart']).'%20TO%20'.$this->getSolrDate($this->searchCriteria['responseSubmitDateEnd']).']';
            $requestParams[] = $dateParams;
	}
        
        return $requestParams;
    }
    
    /**
     * Matched course search parameters for MR search
     */ 
    private function _getMatchedCourseParams()
    {
        $matchCourseSolr = $this->searchCriteria['matchedCourses']; 
        $requestParams = array();
        
        if(count($this->searchCriteria['matchedCourses'])) {

            $totalMatchedCourse = count($this->searchCriteria['matchedCourses']);

            for ($i=1; $i <= $totalMatchedCourse; $i++) {                
                $tempMatchCourseArray[] = $matchCourseSolr[$i-1];            

                if($i%500 == 0 || $i == $totalMatchedCourse){
                    $courseParams[] = 'responseCourse:('.implode('%20', $tempMatchCourseArray).')';
                    $tempMatchCourseArray = array();
                }
            }
			
             $tempParam =   implode("%20OR%20",$courseParams); 
             $finalParam = "fq=(".$tempParam.")";

            //$courseParams = 'fq=responseCourse:('.implode('%20', $this->searchCriteria['matchedCourses']).')';

            /*$match_courses = $this->searchCriteria['matchedCourses'];   //new implmentation
            $courseParams = implode("&fq=responseCourse:",$match_courses);
            $courseParams = trim("&",$courseParams);*/
            
            $requestParams[] = $finalParam;
	   }
        
        return $requestParams;
    
    }   
 
    private function _getProfileType(){
		$requestParams = array();
		$profile_type = $this->searchCriteria['ProfileType'];
        if(!empty($profile_type)) {   
			
			$finalParam ="fq=ProfileType:".'"'.$profile_type.'"';

			$requestParams[] = $finalParam;
		}
		
        return $requestParams;
        
	}
   
    private function _getStreamRequestParams($streamId){
        $requestParams = array();
        
        $finalParam ="fq=streamId:".$streamId;

        $requestParams[] = $finalParam;
        return $requestParams;
        
    }

    private function _getSubStreamSpecializationRequestParams(){
        $requestParams = array();
        $subStreamArray = array();
        $subStreamSpecializationQuery = array();

        global $noSpecId;

        if(isset($this->searchCriteria['subStreamSpecializationMapping'])) {
            foreach($this->searchCriteria['subStreamSpecializationMapping'] as $subStreamId=>$specializations) {

                if(!empty($specializations)) {

                    if(in_array($noSpecId, $specializations)){
                        $subStreamSpecializationQuery[] = '(subStreamId:'.$subStreamId.'%20AND%20-specialization:[0%20TO%20*])';
                    }
                    $subStreamSpecializationQuery[] = "(subStreamId:".$subStreamId."%20AND%20specialization:(".implode('%20',$specializations)."))";
                } else {
                    $subStreamArray[] = $subStreamId;
                }
            }
        }

        if(!empty($subStreamArray)) {
            $subStreamSpecializationQuery[] = "(subStreamId:(".implode('%20',$subStreamArray)."))";                
        }       

        if(is_array($this->searchCriteria['ungroupedSpecializations']) && count($this->searchCriteria['ungroupedSpecializations']) > 0) {
            $subStreamSpecializationQuery[] = "(specialization:(".implode('%20',$this->searchCriteria['ungroupedSpecializations'])."))";                
        }
        
        if(count($subStreamSpecializationQuery) > 0) {
            $requestParams[] = "fq=(".implode('%20OR%20',$subStreamSpecializationQuery).")";
        }
         
        return $requestParams;
    }

    private function _getPopularCourseRequestParams($popularCourseId){
        $requestParams = array();
        
        $finalParam = 'fq=courseId:('.implode('%20OR%20', $popularCourseId).')';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getAttributeIdsRequestParams($attributeIds){
        $requestParams = array();
        
        $finalParam = 'fq=attributeIds:('.implode('%20OR%20', $attributeIds).')';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getAttributeValuesRequestParams($attributeValues){
        $requestParams = array();
        
        $finalParam = 'fq=attributeValues:('.implode('%20OR%20', $attributeValues).')';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getNationalUserParams(){
        $requestParams = array();
        
        $finalParam = 'fq=-extraFlag:[""%20TO%20*]';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getExcludeMRProfileParams(){
        $requestParams = array();
        
        $finalParam = 'fq=-isMRPRofile:true';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getExcludeFullTimeProfileParams(){
        $requestParams = array();
        
        $finalParam = 'fq=-isFTExclusion:true';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getExcludeMRCourseProfileParams(){
        $requestParams = array();
        
        $finalParam = 'fq=-isMRCourseExclusion:true';

        $requestParams[] = $finalParam;
        return $requestParams;
    }

    private function _getResponseCityRequestParams()   {
        global $requiredAffinityForCityTier;
        
        $responseCityQuery = array();
        $requestParams = array();
        
        if(is_array($this->searchCriteria['responseCities']) && count($this->searchCriteria['responseCities']) > 0) {
            foreach($this->searchCriteria['responseCities'] as $responseCity) {
                $responseCityQuery[] = 'location_affinity_'.$responseCity.':[0%20TO%20*]';      
            }
        }
        
        if(count($responseCityQuery) > 0 && count($responseCityQuery) > 0) {
            $solrRequest = 'fq=('.implode('%20OR%20',$responseCityQuery).')';
            $requestParams[] = $solrRequest;
        }
        
        return $requestParams;
    }
}
