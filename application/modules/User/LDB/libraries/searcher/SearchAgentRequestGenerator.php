<?php

class SearchAgentRequestGenerator
{
    private $CI;
    private $solrServer;
    private $leadSearchModel;
    
    private $searchCriteria;
    private $requestParamsGlobal = array();

    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->builder('SearchBuilder','search');
        $this->solrServer = SearchBuilder::getSearchServer();
        
        $this->CI->load->model('LDB/leadsearchmodel');
        $this->leadSearchModel = new LeadSearchModel;
    }
    
    public function setSearchCriteria($searchCriteria)
    {
        $this->searchCriteria = $searchCriteria;
    }

    public function getUserDataForCSV($userId,$searchCriteria,$portingFlag=FALSE){

        $request = SOLR_LDB_SEARCH_SELECT_URL_BASE;
        $request .= '?q=*%3A*&wt=phps&';

        if($searchCriteria['streamId']>0){
            $request .= 'fq=streamId:'.$searchCriteria['streamId'];
        }


        if(isset($searchCriteria['subStreamId']) && !empty($searchCriteria['subStreamId'])){
            $request .= '&fq=subStreamId:('.implode('%20OR%20', $searchCriteria['subStreamId']).')';
        }
        
        if($searchCriteria['subStreamId'] == 0 && $portingFlag == TRUE){
            $request .= '&fq=-subStreamId:*';
        }

        if($searchCriteria['excludeMRPRofile']){
            $request .= '&fq=-isMRPRofile:true';
        }    

        if(isset($searchCriteria['ProfileType']) && !empty($searchCriteria['ProfileType'])){
            $request .= '&fq=ProfileType:'.$searchCriteria['ProfileType'];
        }

        if(count($searchCriteria['leadProfileTypeMap'])>0){
            $userProfileSubQuery ='';
            $userProfileSubArray = array();

            foreach ($searchCriteria['leadProfileTypeMap'] as $leadId => $profileType) {
                $userProfileSubArray[] = '((userId:'.$leadId.')%20AND%20(ProfileType:"'.$profileType.'"))';
            }

            $request .= '&fq=('.implode('%20OR%20', $userProfileSubArray).')';

        }else if(count($searchCriteria['leadProfileDataMap'])>0){
            $userProfileSubQuery ='';
            $userProfileSubArray = array();

            foreach ($searchCriteria['leadProfileDataMap'] as $leadId => $profile) {
				$query ='';
                $query = '((userId:'.$leadId.')%20AND%20(ProfileType:"'.$profile['ProfileType'].'")%20AND%20(streamId:'.$profile['stream'].')';

                if($profile['substream'] >0){
                    $query .= '%20AND%20(subStreamId:'.$profile['substream'].'))';
                }else{
                    $query .= '%20AND(*:* AND -subStreamId:*))';
                }

                $userProfileSubArray[] = $query;
            }

            $request .= '&fq=('.implode('%20OR%20', $userProfileSubArray).')';


        } else{

            $itr= 0;

            foreach ($userId as $user) {
                $itr++;

                $tempUser[]= $user;

                if($itr%500 == 0){
                    $userIdParam[] ='userId:('.implode('%20', $tempUser).')';
                    $tempUser = array();
                }

            }

            if(count($tempUser)>0){
                $userIdParam[] ='userId:('.implode('%20', $tempUser).')';     //to process last chunk
            }

            $tempParam =   implode("%20OR%20",$userIdParam); 
            $request .= "&fq=(".$tempParam.")";


            // $request .= '&fq=userId:('.implode('%20OR%20', $userId).')';

        }   

        if($searchCriteria['Viewed'] || $searchCriteria['Emailed'] || $searchCriteria['Smsed']) {

            $request .= '&fl=userId,displayData,specialization,courseId,attributeValues,interestTime,streamId,subStreamId,educationName,ViewCount,ViewCredit,EmailCredit,SmsCredit,responseCourse,response_time_*&group=true&group.field=userId&group.sort=submitDate+desc';            
            
        } else {
            
            $request .= '&fl=userId,displayData,specialization,courseId,attributeValues,interestTime,streamId,subStreamId,educationName,ViewCount,ViewCredit,EmailCredit,SmsCredit,responseCourse,response_time_*&group=true&group.field=userId&group.sort=ViewCredit+desc';
            
        }


        $request .= '&rows=100000';

        return $request;
    }

    public function getUserDataForGenieCSV($userIdsArray = array()){
        $userIdSolrString = $this->prepareUserIdSolrRequest($userIdsArray);
        $request          = SOLR_LDB_SEARCH_SELECT_URL_BASE;
        $request         .= '?q=*%3A*&wt=phps&';
        $request         .= $userIdSolrString;
        
        $request         .= '&fl=userId,displayData,educationName&group=true&group.field=userId&sort=submitDate+desc';
        $request         .= '&rows=5000';

        return $request;
    }

    private function prepareUserIdSolrRequest($userIdsArray){
        $counter      = 0;
        $totalUserIds = count($userIdsArray);

        foreach ($userIdsArray as $userId) {
            $counter++;
            $tempUserArray[] = $userId;

            if($counter%1000 == 0 || $counter == $totalUserIds){
                $requestParams[] = 'userId:('.implode('%20', $tempUserArray).')';
                $tempUserArray   = array();
            }
        }

        
        $tempParam =   implode("%20OR%20",$requestParams);
        return "fq=(".$tempParam.")";
    }

    public function generateSearchRequest($searchCriteria){
        
        unset($this->setSearchCriteria);
        unset($this->requestParamsGlobal);

	    $this->setSearchCriteria($searchCriteria);
	       
        $requestParams = array();
            
        //$requestParams[] = "fq=DocType:SearchAgent";  
            
        $this->requestParamsGlobal[] = "fq=DocType:SearchAgent";
        $this->requestParamsGlobal[] = "fq=SearchAgentType:lead";
        
        //to exclude disbale genies
        $this->_removeDisabledGenieParam();

        if($searchCriteria['excludeGenieIds'] and count($searchCriteria['excludeGenieIds'])>0) {
            $this->_excludeGenieWithQuotaReached($searchCriteria['excludeGenieIds']);
        }

        /**
        * Stream
        */ 
        if(isset($this->searchCriteria['streamId'])) {
            $this->_getStreamRequestParams($this->searchCriteria['streamId']);
        }

        /**
        * Sub Stream and Specialization
        */ 
        if( (is_array($this->searchCriteria['specialization']) && count($this->searchCriteria['specialization']) > 0) || is_array($this->searchCriteria['subStreamId']) && count($this->searchCriteria['subStreamId']) > 0) {

            $this->_getSpeclizationRequestParams($this->searchCriteria['subStreamId'] , $this->searchCriteria['specialization']);
        }else{
            $this->_getOnlyStreamRequestParams();
        }

        /**
        * courseId - Popular Course
        */ 
        if(is_array($this->searchCriteria['courseId']) && count($this->searchCriteria['courseId']) > 0 ) {
            $this->_getPopularCourseRequestParams($this->searchCriteria['courseId']);
        }else{
            $this->_getNoPopularCourseRequestParams();
        }

        

        /**
        * mode
        */ 
        if(is_array($this->searchCriteria['attributeValues']) && count($this->searchCriteria['attributeValues']) > 0 ) { 

            $this->_getModeRequestParams($this->searchCriteria['attributeValues']);
        }else{
            $this->_getNoModeRequestParams();
        }
       

        /**
         * Desired course
         */ 
        if($this->searchCriteria['extraFlag'] == 'studyabroad' || $this->searchCriteria['extraFlag'] == 'testprep') {
            $this->_getDesiredCourseRequestParams();
        } 

        /**
         * Course specializations
         */ 
        if(is_array($this->searchCriteria['Specialization']) && count($this->searchCriteria['Specialization']) > 0) {
            $this->_getCourseSpecializationRequestParams();
        }

	   /**
         * Abroad specializations
         */
        if($this->searchCriteria['abroad_subcat_id'] > 0 ) {
            $this->_getAbroadSpecializationRequestParams();
        }

        
        /**
         * UG course
         */ 
       /* if(is_array($this->searchCriteria['UGCourse']) && count($this->searchCriteria['UGCourse']) > 0) {
            $this->_getUGCourseRequestParams();
        }*/
        
        /**
         * Graduation completion date
         */ 
        /*if($this->searchCriteria['GraduationCompletedFrom'] || $this->searchCriteria['GraduationCompletedTo']) {
            $this->_getGraduationCompletionDateRequestParams();
        }*/
        
        /**
         * Graduation marks
         */ 
       /* if($this->searchCriteria['MinGradMarks']) {
            $this->_getGraduationMarksRequestParams();
        }*/
        
       
        /**
         * Work experience
         */
        if($this->searchCriteria['workex']) {
            $this->_getWorkExperienceRequestParams();
        }else{
            $this->_getNoWorkExperienceRequestParams();
        }
        
        if($this->searchCriteria['educationName'] && !($this->searchCriteria['extraFlag'] == 'studyabroad')){
            $this->nationalExamMatching();
        }else if(!($this->searchCriteria['extraFlag'] == 'studyabroad')){
            $this->_getNonationalExamMatching();
        }
        
        /**
         * Competitive exams
         */
        if(is_array($this->searchCriteria['educationName']) && count($this->searchCriteria['educationName']) > 0 && $this->searchCriteria['extraFlag'] == 'studyabroad' ) {
            $this->_getExamsRequestParams();
        }
        
        /**
         * Location
         */
        if((is_array($this->searchCriteria['Locality']) && count($this->searchCriteria['Locality']) > 0) ||
           (is_array($this->searchCriteria['CurrentLocation']) && count($this->searchCriteria['CurrentLocation']) > 0) ||
           (is_array($this->searchCriteria['PreferredLocation']) && count($this->searchCriteria['PreferredLocation']) > 0) ||
	   (isset($this->searchCriteria['locality']) && $this->searchCriteria['locality'] != '') ||
	   (isset($this->searchCriteria['city']) && $this->searchCriteria['city']!= '' ) )
        {
            $this->_getLocationRequestParams();
        }
        

        /**
         * Passport
         */
        if($this->searchCriteria['passport']) {
            $this->_getPassportRequestParams();
        }
        
        /**
         * Preferred Country
         */
        if($this->searchCriteria['locationPrefCountryId'] && count($this->searchCriteria['locationPrefCountryId'])>0) {
            $this->_getPreferredCountryParams();
        }
        
        
	   /**
         * Plan to start
         */
        if(isset($this->searchCriteria['planToStart'])) {
            $this->_getYearOfStartRequestParams($this->searchCriteria['planToStart']);
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

        $request .= '&fl=SearchAgentId,clientId,deliveryMethod,includeActiveUsers&sort=submitDate+desc';

        $request .= '&rows=1000000';
        
        return $request;
    }
    
    /**
     * Desired course search parameters
     */ 
    private function _getDesiredCourseRequestParams()
    {
        $requestParams = array();
        
        if($this->searchCriteria['extraFlag'] == 'studyabroad') {
			$desiredCourseQuery = array();
			foreach($this->searchCriteria['desiredCourse'] as $desiredCourseId) {
				$desiredCourseQuery[] = "desiredCourse:".intval($desiredCourseId);
			}
			$requestParams[] = "fq=".implode('%20OR%20',$desiredCourseQuery);
		}
        else if($this->searchCriteria['extraFlag'] == 'testprep') {
			$desiredCourseQuery = array();
			foreach($this->searchCriteria['testPrep_blogid'] as $desiredCourseId) {
				$desiredCourseQuery[] = "testPrepCourseId:".intval($desiredCourseId);
			}
			$requestParams[] = "fq=".implode('%20OR%20',$desiredCourseQuery);
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
			
			$requestParams[] = "fq=".implode('%20OR%20',$desiredCourseQuery);
		}
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
    /**
     * Course specialization search parameters -- used to capture specialization id through MMP national form
     */ 
    /*private function _getCourseSpecializationRequestParams()
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
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }*/
    
    /**
     * Abroad specialization search parameters
     */
    private function _getAbroadSpecializationRequestParams()
    {
        
        $finalParam = 'fq=(SAAbroadSpecialization:'.$this->searchCriteria['abroad_subcat_id'].')';
       
        $finalParam .= "(*:* AND -SAAbroadSpecialization:*)";

        $this->requestParamsGlobal[] = $finalParam;

    }


    /**
     * UG course search parameters
     */ 
   /* private function _getUGCourseRequestParams()
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
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }*/
    
    /**
     * Graduation completion date search parameters
     */ 
   /* private function _getGraduationCompletionDateRequestParams()
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
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }*/
    
    /**
     * Graduation marks search parameters
     */ 
    /*private function _getGraduationMarksRequestParams()
    {
        $requestParams = array();
		$requestParams[] = "fq=UGMarks:[".$this->searchCriteria['MinGradMarks']."%20TO%20*]";

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }*/
    
    /**
     * Graduation result awaited search parameters
     */ 
    private function _getGraduationResultAwaitedRequestParams()
    {
        $requestParams = array();
		$requestParams[] = "fq=-UGStatus:Ongoing";

        $this->requestParamsGlobal[] = $requestParams[0];
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
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
    
    /**
     * XII marks search parameters
     */ 
    private function _getXIIMarksRequestParams()
    {
        $requestParams = array();
		$requestParams[] = "fq=XIIMarks:[".$this->searchCriteria['MinXIIMarks']."%20TO%20*]";

        $this->requestParamsGlobal[] = $requestParams[0];
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
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
    /**
     * Work experience search parameters
     */ 
    private function _getWorkExperienceRequestParams()
    {
        $requestParams = array();

        $finalParam = "fq=(maxWorkEx:[".$this->searchCriteria['workex']."%20TO%20*]%20AND%20minWorkEx:[*%20TO%20".$this->searchCriteria['workex']."])(*:* AND -maxWorkEx:*)(*:* AND -minWorkEx:*)";

        $this->requestParamsGlobal[] = $finalParam;

        return $requestParams;
    }
    
  
    private function _getNoWorkExperienceRequestParams(){
         $requestParams = array();

         $finalParam = "fq=((*:* AND -maxWorkEx:*)AND(*:* AND -minWorkEx:*))";

         $this->requestParamsGlobal[] = $finalParam;

         return $requestParams;

    }
    
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
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
    /**
     * Competitive exams search parameters - abroad
     */ 
    private function _getExamsRequestParams()
    {
        $requestParams = array();
        
        $examQuery = array();
        foreach($this->searchCriteria['educationName'] as $examName) {
            
            $thisExamQuery = array();
            $thisExamQuery[] = "educationName:".$examName;
            
            $examName = strtolower(preg_replace('/[^a-zA-Z0-9]/', '', $examName));
            $marks = $this->searchCriteria[$examName.'_educationMarks'];

            $thisExamQuery[] = $examName."_SAEducationMaxMarks:[".$marks."%20TO%20*]";
            $thisExamQuery[] = $examName."_SAEducationMinMarks:[*%20TO%20".$marks."]";

            $examQuery[] = '('.implode("%20AND%20",$thisExamQuery).')';
        }
		
		if(count($examQuery) > 0) {
			$requestParams[] = "fq=(".implode('%20OR%20',$examQuery).")(*:* AND -educationName:*)";
		}
    
        $this->requestParamsGlobal[] = $requestParams[0];
        
    }

    private function _getNonationalExamMatching(){
         $requestParams = array();

         $finalParam = "fq=(*:* AND -educationName:*)";

         $this->requestParamsGlobal[] = $finalParam;

         return $requestParams;
    }

    private function nationalExamMatching()
    {
        $requestParams = array();

        foreach ($this->searchCriteria['educationName'] as $exam) {
            $tempExam[] = '"'.urlencode($exam).'"';
        }

        $this->searchCriteria['educationName'] = $tempExam;
        unset($tempExam);

        $finalParam = 'fq=educationName:('.implode('%20OR%20', $this->searchCriteria['educationName']).')';
        $finalParam .= "(*:* AND -educationName:*)";  

        $this->requestParamsGlobal[] = $finalParam;
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

            if($this->searchCriteria['extraFlag'] == 'studyabroad'){ 
               $endTime = date('Y-m-d H:i:s', strtotime('-15 min'));                     //to exclude lead registered in last 30min  consultant response
               $dateParams .= '%20AND%20submitDate:[*%20TO%20'.$this->getSolrDate($endTime).']';
            }

			$requestParams[] = $dateParams;
		}
        
        $this->requestParamsGlobal[] = $requestParams[0];
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

		if(isset($this->searchCriteria['city']) && $this->searchCriteria['city'] != '') {
			$CurrentCities = array();


			if( $this->searchCriteria['city'] && isset($this->searchCriteria['locality']) ) {
				$localityQuery[] = "(SAcurrentlocation:".intval($this->searchCriteria['city'])."%20AND%20SAcurrentlocality:(".intval($this->searchCriteria['locality']).") )";

                $localityQuery[] = "(SAcurrentlocation:".intval($this->searchCriteria['city'])."%20AND%20(*:* AND -SAcurrentlocality:*) )";

			}else {
			    $localityQuery[] = "(SAcurrentlocation:".intval($this->searchCriteria['city']).")";
			}

            $localityQuery[] = "( (*:* AND -SAcurrentlocation:*) AND (*:* AND -SAcurrentlocality:*)  )";

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
			/*$currentLocationQuery = array();
			if(is_array($this->searchCriteria['CurrentLocation']) && count($this->searchCriteria['CurrentLocation']) > 0) {
				foreach($this->searchCriteria['CurrentLocation'] as $currentLocation) {
					if(intval($currentLocation)) {
						$currentLocationQuery[] = "SAcurrentlocation:".intval($currentLocation);
					}
				}
			    
			}*/
			
			/**
			 * Preferred Locations
			 */ 
			/*$preferredLocationQuery = array();
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
			}*/
			
			/*if(count($currentLocationQuery) > 0 && count($preferredLocationQuery) > 0) {
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
			}*/
		}
        
        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
    
    /**
     * Passport search parameters
     */ 
    private function _getPassportRequestParams(){

        $requestParams = array();
    	if($this->searchCriteria['passport'] == 'yes') {
    		$requestParams[] = "fq=(passport:yes)(*:* AND -passport:*)";
    	}
    	else {
    		$requestParams[] = "fq=(passport:no)(*:* AND -passport:*)";
    	}

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
     /**
     * Preferred Country search parameters
     */ 
    private function _getPreferredCountryParams(){

        $requestParams = array();

        $finalParam = 'fq=(SAPrefCountry:('.implode('%20OR%20', $this->searchCriteria['locationPrefCountryId']).'))';
        $finalParam .= "(*:* AND -SAPrefCountry:*)"; 

        $this->requestParamsGlobal[] = $finalParam;
        return $requestParams;
    }


    /**
     * Plan to start search parameters
     */ 
    private function _getPlanToStartRequestParams(){
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

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
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

    private function _getOnlyStreamRequestParams(){
         $onlyStreamQuery = "fq=((*:* AND -subStreamId:*)AND(*:* AND -specialization:*))";

         $this->requestParamsGlobal[] = $onlyStreamQuery;
    }

    private function _getSpeclizationRequestParams($subStreamId,$specializationId){
        $requestParams = array();
        
        if(count($subStreamId) >0 && count($specializationId) >0){
            $subStreamSpecializationQuery[] = "(subStreamId:".$subStreamId[0]."%20AND%20specialization:(".implode('%20',$specializationId)."))";
        }

        if(count($subStreamId) >0  && count($specializationId) <1 ){
            $subStreamSpecializationQuery[] = "((subStreamId:".$subStreamId[0].")AND(*:* AND -specialization:*))";
        }

        if(count($specializationId) >0 && count($subStreamId) <1 ){
             $subStreamSpecializationQuery[] = "((*:* AND -subStreamId:*)AND(specialization:(".implode('%20',$specializationId).")))";
        }
        
        $subStreamSpecializationQuery[] = "((*:* AND -subStreamId:*)AND(*:* AND -specialization:*))";

        if(count($subStreamSpecializationQuery) > 0) {
            $requestParams[] = "fq=(".implode('%20OR%20',$subStreamSpecializationQuery).")";
        }

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }


    private function _getPopularCourseRequestParams($popularCourseId){
        $requestParams = array();
        
        $finalParam = 'fq=courseId:('.implode('%20OR%20', $popularCourseId).')';

        $finalParam .= "(*:* AND -courseId:*)";
        $requestParams[] = $finalParam;

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getNoPopularCourseRequestParams(){
        $finalParam .= "(*:* AND -courseId:*)";

        $this->requestParamsGlobal[] = $finalParam;
    }

    private function _getLevelRequestParams($level){
        $requestParams = array();
        
        $finalParam = 'fq=level:('.implode('%20OR%20', $level).')';

        $requestParams[] = $finalParam;

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getModeRequestParams($mode){
        $requestParams = array();
        
        $finalParam = 'fq=attributeValues:('.implode('%20OR%20', $mode).')';
        $finalParam .= "(*:* AND -attributeValues:*)";
        $requestParams[] = $finalParam;

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }
    
    private function _getNoModeRequestParams(){
        $finalParam .= "(*:* AND -attributeValues:*)";

        $this->requestParamsGlobal[] = $finalParam;
    }

    private function _getCredentialRequestParams($credential){
        $requestParams = array();
        
        $finalParam = 'fq=credential:('.implode('%20OR%20', $credential).')';

        $requestParams[] = $finalParam;

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
    }

    private function _getYearOfStartRequestParams($yearOfStart){
        $requestParams = array();
        
        $finalParam = 'fq=(SAPlanToStart:'.$yearOfStart.')';
        $finalParam .= "(*:* AND -SAPlanToStart:*)";

        $requestParams[] = $finalParam;

        $this->requestParamsGlobal[] = $requestParams[0];
        return $requestParams;
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
