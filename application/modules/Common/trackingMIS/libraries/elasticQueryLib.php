<?php
require_once('vendor/autoload.php');
class elasticQueryLib {
	private $CI;
	private $elasticQuery;
	public function __construct(){
        $this->CI = & get_instance();
    }

    public function convertDateToUTC($date){
		$given = new DateTime($date);
		$given->setTimezone(new DateTimeZone("UTC"));
		return $given->format("Y-m-d H:i:s");
	}

    //Date Range Filter
    private function _prepareDateRangeQuery($inputRequest, $aspect, $pivot){
    	$timeField = "startTime";
    	if($aspect == 'session'){
    		$timeField = "startTime";
            if($pivot == "exitPage."){
                $timeField = "exitPage.visitTime";
            }
    	}else if($aspect == 'pageview'){
    		$timeField = "visitTime";
    	}
        if($inputRequest['dateRange']['startDate'] != ''){
        	$startDate = $this->convertDateToUTC($inputRequest['dateRange']['startDate']." 00:00:00");
        	$startDate = str_replace(" ", "T", $startDate);
        	$endDate = $this->convertDateToUTC($inputRequest['dateRange']['endDate']." 23:59:59");
        	$endDate = str_replace(" ", "T", $endDate);
            return array(
                'range' => array(
                    $timeField => array(
                        'gte' => $startDate,
                        'lte' => $endDate
                    )
                )
            );
        }
    }
  
    //Site Filter
    private function _prepareSiteFilter($inputRequest, $pivot){
        $fieldName = (empty($pivot) || $pivot== "landingPageDoc.")?'isStudyAbroad':$pivot.'isStudyAbroad';
    	if ($inputRequest['misSource'] != 'SHIKSHA') {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $teamFilter = 'yes';
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $teamFilter = 'no';
            }
            return array(
            	"term" => array(
            		$fieldName => $teamFilter
				)
            );
        }
    }

    private function _prepareUTMSourceFilter($inputRequest){

    	if (isset($inputRequest['utmSourceFilter']) && $inputRequest['utmSourceFilter'] != "") {
            return array(
            	"term" => array(
            		"source" => $inputRequest['utmSourceFilter']
				)
            );
        }	
    }

    //Page Filter
    private function _preparePageFilter($inputRequest, $pivot=''){
        if (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $pageNameFilter = array(
                                'term' => array(
                                    $pivot.'pageIdentifier' => 'universityRankingPage'
                                )
                            );
                        } else if ($inputRequest['pageType'] == 2) {
                            $pageNameFilter = array(
                                'term' => array(
                                    $pivot.'pageIdentifier' => 'courseRankingPage'
                                )
                            );
                        } else {
                            $pageNameFilter = array(
                                'terms' => array(
                                    $pivot.'pageIdentifier' => array(
                                        'courseRankingPage',
                                        'universityRankingPage'
                                    )
                                )
                            );
                        }                
                    }else{
                        $pageNameFilter = array(
                            'term' => array(
                                $pivot.'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
                    }
            }else{
                $pageNameFilter = array(
                            'term' => array(
                                $pivot.'pageIdentifier' => $inputRequest['pageName']
                            )
                        );
            }
            return $pageNameFilter;
        }else if(isset($inputRequest['shikshaPages']) && is_array($inputRequest['shikshaPages']) && count($inputRequest['shikshaPages']) >0){
            if($inputRequest['shikshaPages'][0] != "all"){
                $pageNameFilter = array('terms' => array($pivot.'childPageIdentifier' => $inputRequest['shikshaPages']));
                return $pageNameFilter;
            }
        }
    }

    // Source Application Filter
    private function _prepareSourceApplicationFilter($inputRequest, $pivot=''){
    	if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
            if (
                strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 ||
                strcasecmp($inputRequest['sourceApplication'], 'androidApp') == 0 ||
                strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0
            ) {
                $fieldName = (empty($pivot) || $pivot== "landingPageDoc.")?'isMobile':$pivot.'isMobile';
                $fieldValue = '';
                if($inputRequest['sourceApplication'] == 'androidApp'){
                    $fieldValue = "androidApp";
                }else if($inputRequest['sourceApplication'] == 'desktop'){
                    $fieldValue = "no";
                }else{
                    $fieldValue = "yes";
                }

                $sourceApplicationFilter = array(
                    'term' => array(
                        $fieldName => $fieldValue
                    )
                );
                return $sourceApplicationFilter;
            }
        }
    }

    // User filter
    private function _prepareUserFilter($inputRequest, $pivot=''){
    	$response = array();
        if(!empty($inputRequest['userFilter'])){
            $fieldName = (empty($pivot) || $pivot== "landingPageDoc.")?'userId':$pivot.'userId';
            if($inputRequest['userFilter'] == 'loggedIn'){
                $userFilter = array(
                    'term'  =>  array(
                        $fieldName    => 0
                        )
                    );
                $response = array("key"=>"must_not_filter");
            }else if($inputRequest['userFilter'] == 'nonLoggedIn'){
                $userFilter = array(
                    'term'  =>  array(
                        $fieldName    => 0
                        )
                    );
                $response = array("key"=>"must_filter");
            }
            $response["query"] = $userFilter;
            return $response;
        }
        //_p($this->elasticQuery);die;
    }

    // stream  substream  specialization  baseCourse  credential  baseCourseLevel 
    private function _prepareHierarchyAndBaseCourseFilter($inputRequest, &$mustFilters, $pivot=""){
    	$nestedQuery = array();
    	if($inputRequest['stream'] >0){
    		$termFilters = array();
    		$termFilters[$pivot."hierarchy.streamId"] = $inputRequest['stream'];

    		if($inputRequest['substream'] >0 || $inputRequest['substream'] == "none"){
    			$termFilters[$pivot."hierarchy.substreamId"] = ($inputRequest['substream'] == "none")?0:$inputRequest['substream'];
    		}

    		if($inputRequest['specialization'] >0 || $inputRequest['specialization'] == "none"){
    			$termFilters[$pivot."hierarchy.specializationId"] = ($inputRequest['specialization'] == "none")?0:$inputRequest['specialization'];
    		}

    		$mustFiltersForNestedQuery = array();
    		foreach ($termFilters as $key => $value) {
	    		$mustFiltersForNestedQuery[] = array("term" => array($key => $value));
	    	}

	    	$nestedQuery = array(
    				"path" => $pivot."hierarchy",
    				"query" => array(
    					"bool" => array(
    						"must" => $mustFiltersForNestedQuery
    					)
    				)
    			
    		);

			if($inputRequest['baseCourse'] >0){
				$termFilters = array();
				$termFilters[$pivot."baseCourseId"] = $inputRequest['baseCourse'];

				if($inputRequest['credential'] >0){
					$termFilters[$pivot."credential"] = $inputRequest['credential'];
				}

				if($inputRequest['baseCourseLevel'] >0){
					$termFilters[$pivot."level"] = $inputRequest['baseCourseLevel'];
				}

				foreach ($termFilters as $key => $value) {
		    		$mustFilters[] = array("term" => array($key => $value));
		    	}
			}
    	}

    	return $nestedQuery;
    }

    // Education Type and Delivery Method Filter
    private function _prepareEducationTypeAndDeliveryMethodFilter($inputRequest, &$mustFilters, $pivot){
    	if($inputRequest["educationType"] > 0){
        	$mustFilters[] = array(
        		"term" => array(
        			$pivot."educationType" => $inputRequest["educationType"]
        		)
        	);

        	if($inputRequest["deliveryMethod"] > 0){
				$mustFilters[] = array(
	        		"term" => array(
	        			$pivot."deliveryMethod" => $inputRequest["deliveryMethod"]
	        		)
	        	);        		
        	}
        }
    }

    private function _prepareAbroadSpecificFilter($inputRequest, &$mustFilters, &$mustNotFilters, $pivot){
        if($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['category'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['category'],$ldbCourseIds)){
                    $mustFilters[] = array('term' => array($pivot."LDBCourseId" => $inputRequest['category']));
                }else{
                    $mustFilters[] = array('term' => array($pivot."categoryId" => $inputRequest['category']));
                    if($inputRequest['courseLevel'] != '0'){
                        $mustFilters[] = array('term' => array($pivot."courseLevel" => $inputRequest['courseLevel']));
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) &&  $inputRequest['courseLevel'] != '0'){
                $mustFilters[] = array('term' => array($pivot."courseLevel" => $inputRequest['courseLevel']));
            }
            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $mustFilters[] = array('term' => array($pivot."countryId" => $inputRequest['country']));
            }
        }
    }


    public function prepareElasticSearchQuery($inputRequest, $aspect = '', $pivot = ''){
        //_p($pivot);
    	if($aspect == "session"){
    		$index = SESSION_INDEX_NAME;
    		$type = 'session';
            if($pivot == 'exitPage'){
                $pivot='exitPage.';
            }else{
                $pivot='landingPageDoc.';
            }
    	}else if($aspect == "pageview"){
    		$index = PAGEVIEW_INDEX_NAME;
    		$type = 'pageview';
    	}else{
    		return array();
    	}

        $this->elasticQuery = array(
            'index' => $index,
            'type'  => $type,
            'body'  => array(
                'size' => 0
            )
        );

        $mustFilters = array();
        $mustNotFilters = array();

        //Date Range Filter
        if($inputRequest['dateRange']['startDate'] != ''){
        	$mustFilters[] = $this->_prepareDateRangeQuery($inputRequest, $aspect, $pivot);
        }

        if ($inputRequest['misSource'] != 'SHIKSHA') {
        	$mustFilters[] = $this->_prepareSiteFilter($inputRequest, $pivot);	
        }

        if (
            (strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0)  ||
            (isset($inputRequest['shikshaPages']) && is_array($inputRequest['shikshaPages']) && count($inputRequest['shikshaPages']) >0 && $inputRequest['shikshaPages'][0] != "all")
        ) {
        	$mustFilters[] = $this->_preparePageFilter($inputRequest, $pivot);
        }

        if (isset($inputRequest['shikshaPageGroups']) && is_array($inputRequest['shikshaPageGroups']) && count($inputRequest['shikshaPageGroups']) >0){
            if($inputRequest['shikshaPageGroups'][0] != "all"){
                $mustFilters[] = array('terms' => array($pivot.'pageIdentifier' => $inputRequest['shikshaPageGroups']));    
            }
        }

        if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
        	$sourceApplicationFilter = $this->_prepareSourceApplicationFilter($inputRequest, $pivot);
            if($sourceApplicationFilter != ""){
                $mustFilters[] = $sourceApplicationFilter;
            }
        }

        $trafficSourceFilter = $this->_prepareTrafficSourceFilter($inputRequest, $aspect, $pivot);
        if($trafficSourceFilter !== false){
            $mustFilters[] = $trafficSourceFilter;
        }

        if (isset($inputRequest['utmSourceFilter']) && $inputRequest['utmSourceFilter'] != "") {
        	$mustFilters[] = $this->_prepareUTMSourceFilter($inputRequest);
        }

        if($inputRequest['assistantFilter'] == "withAssistant"){
            $mustFilters[] = array('range' => array('SAMessageCount' => array('gt' => 0)));
        }else if($inputRequest['assistantFilter'] == "withoutAssistant"){
            $mustFilters[] = array('term' => array('SAMessageCount' => 0));
            //$mustFilters[] = array('term' => array('SAabVarient' => 2));
        }

        if(!empty($inputRequest['userFilter'])){
        	$response = $this->_prepareUserFilter($inputRequest, $pivot);
        	if($response["key"] == "must_not_filter"){
        		$mustNotFilters[] = $response["query"];
        	}else if($response["key"] == "must_filter"){
        		$mustFilters[] = $response["query"];
        	}
        }

        // page group filter
        if (isset($inputRequest['groupPageList']) && is_array($inputRequest['groupPageList']) && count($inputRequest['groupPageList']) >0){
            if($inputRequest['groupPageList'][0] != "all"){
                $mustFilters[] = array('terms' => array($pivot.'childPageIdentifier' => $inputRequest['groupPageList']));
            }
        }

        // education-type delivery-method
        $this->_prepareEducationTypeAndDeliveryMethodFilter($inputRequest, $mustFilters, $pivot);

        // prepare stream substream specialization base-course credential course-level
        $nestedQuery = $this->_prepareHierarchyAndBaseCourseFilter($inputRequest, $mustFilters, $pivot);

        if($inputRequest['misSource'] == 'STUDY ABROAD') {
            $this->_prepareAbroadSpecificFilter($inputRequest, $mustFilters, $mustNotFilters, $pivot);
        }

        $hasNestedQuery = 0;
        if(count($nestedQuery) > 0){
        	$hasNestedQuery = 1;
        	$this->elasticQuery['body']['query']['bool']['filter'][]['nested'] = $nestedQuery;
        }

        if($aspect !="pageview"){
            if (strcasecmp($inputRequest['sessionFilter'], 'all') != 0) {
                if($inputRequest['sessionFilter'] == "sassistant"){
                    //$mustFilters[] = array('term' => array('SAabVarient' => 2));
                }else if($inputRequest['sessionFilter'] == "nonsassistant"){
                    //$mustFilters[] = array('term' => array('SAabVarient' => 1));
                }
            }
        }

        if($inputRequest['assistantFilter'] == "assistantPageviews"){
            $mustFilters[] = array('term' => array('isource' => 'sa'));    
        }
        

        if ($inputRequest['misSource'] == 'DOMESTIC') {
            if (strcasecmp($inputRequest['userUsedSassistant'], 'all') != 0 && $inputRequest['userUsedSassistant'] != "") {
                if($aspect =="pageview"){
                    if($inputRequest['userUsedSassistant'] == "clickSassistant"){
                        $mustFilters[] = array('term' => array('isource' => 'sa'));
                    }else{
                        $mustFilters[] = array('term' => array('SAMessageCount' => 0));
                    }
                }else{
                    if($inputRequest['userUsedSassistant'] == "userUsedAssistant"){
                        $mustFilters[] = array('range' => array('SAMessageCount' => array("gte" =>1)));
                    }else if($inputRequest['userUsedSassistant'] == "userNotUsedAssistant"){
                        $mustFilters[] = array('term' => array('SAMessageCount' => 0));
                    }else if($inputRequest['userUsedSassistant'] == "clickSassistant"){
                        $mustFilters[] = array('range' => array('SAMessageCount' => array("gte" =>1)));
                    }
                }
            }else{
                if($aspect =="pageview"){
                    if (strcasecmp($inputRequest['sessionFilter'], 'all') != 0 && $inputRequest['sessionFilter'] != "") {
                        //$mustFilters[] = array('term' => array('SAabVarient' => 1));
                    }
                }
            }
        }

        if(count($mustFilters) >0 && count($mustNotFilters) >0){
        	$this->elasticQuery['body']['query']['bool']['filter'][]['bool'] = array("must" =>$mustFilters, "must_not" => $mustNotFilters);
        }else if(count($mustFilters) >0){
            $this->elasticQuery['body']['query']['bool']['filter'][]['bool']['must'] = $mustFilters;
        }else if(count($mustNotFilters) >0){
            $this->elasticQuery['body']['query']['bool']['filter'][]['bool']['must_not'] = $mustNotFilters;
        }

        //_p($this->elasticQuery);die;
        //error_log("ELASTICQUERY : ".json_encode($this->elasticQuery));
        return array("elasticQuery" => $this->elasticQuery, "hasNestedQuery" => $hasNestedQuery);
        //_p(json_encode($this->elasticQuery));die;      
    }

    public function prepareQueryForResponse($inputRequest,$extraData = array()){
        //_p($inputRequest);die;
        $this->elasticQuery = array(
            'index' => SHIKSHA_RESPONSE_INDEX_NAME,
            'type'  => 'response',
            'body'  => array(
                'size' => 0
            )
        );

        $mustFilters = array();
        $mustNotFilters = array();

        $mustFilters[] = array("term" => array("considered_for_response" => true));
        $mustFilters[] = array("term" => array("is_test_user" => 0));

        if (isset($inputRequest['responseListingType']) && strcasecmp($inputRequest['responseListingType'], 'all') != 0) {
            $mustFilters[] = array("term" => array("response_listing_type" => $inputRequest['responseListingType']));
        }else{
            $mustFilters[] = array("terms" => array("response_listing_type" => array("course", "exam")));
        }

        if(isset($inputRequest['stream']) && $inputRequest['stream'] >0){
            $mustFilters[] = array("term" => array("response_stream_id" => $inputRequest['stream']));
        }
        
        if(isset($inputRequest['substream']) && $inputRequest['substream'] >0){
            $mustFilters[] = array("term" => array("response_sub_stream_id" => $inputRequest['substream']));
        }
        
        if(isset($inputRequest['specialization']) && $inputRequest['specialization'] >0){
            $mustFilters[] = array("term" => array("response_specialization_id" => $inputRequest['specialization']));
        }

        if(isset($inputRequest['baseCourse']) && $inputRequest['baseCourse'] >0){
            $mustFilters[] = array("term" => array("response_base_course_id" => $inputRequest['baseCourse']));
        }

        if(isset($inputRequest['mode']) && $inputRequest['mode'] >0){
            $mustFilters[] = array("term" => array("response_mode_id" => $inputRequest['mode']));
        }

        if (isset($inputRequest['courseListings']) && is_array($inputRequest['courseListings']) && count($inputRequest['courseListings']) >0 && $inputRequest['responseListingType'] != "exam"){
            if($inputRequest['courseListings'][0] != "all"){
                $mustFilters[] = array('terms' => array('response_listing_type_id' => $inputRequest['courseListings']));
            }
        }

        if(!empty($inputRequest['clientList']) && strcasecmp($inputRequest['clientList'], 'all') != 0 && $inputRequest['responseListingType'] != "exam"){
            $mustFilters[] = array("term" => array("client_id" => $inputRequest['clientList']));
        }

        if(!empty($inputRequest['isourceFilter']) && strcasecmp($inputRequest['isourceFilter'], 'all') != 0){
            if($inputRequest['isourceFilter'] == "assistantUsed"){
                $mustFilters[] = array("term" => array("isource" => 'sa'));
            }else if($inputRequest['isourceFilter'] == "assistantNotUsed"){
                $mustNotFilters[] = array("exists" => array("field" => 'isource'));
            }
                
        }

        if (isset($inputRequest['utmSourceFilter']) && $inputRequest['utmSourceFilter'] != "") {
            $mustFilters[] = array("term" => array("response_source" => $inputRequest['utmSourceFilter']));
        }

        if($inputRequest['dateRange']['startDate'] != '' && $inputRequest['dateRange']['endDate'] != ''){
            $mustFilters[] = array(
                'range' => array(
                    'response_time' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00',
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
        }

        if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
            if (strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 || strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0) {
                $mustFilters[] = array(
                    'term' => array(
                        'device' => ucfirst(strtolower($inputRequest['sourceApplication']))
                    )
                );
            }
        }
        
        if (isset($inputRequest['shikshaPages']) && is_array($inputRequest['shikshaPages']) && count($inputRequest['shikshaPages']) >0){
            if($inputRequest['shikshaPages'][0] != "all"){
                if(in_array("CHP", $inputRequest['shikshaPages'])){
                    $inputRequest['shikshaPages'][] = "courseHomePage";
                }
                $mustFilters[] = array('terms' => array('page' => $inputRequest['shikshaPages']));
            }
        }

        if (isset($inputRequest['shikshaPageGroups']) && is_array($inputRequest['shikshaPageGroups']) && count($inputRequest['shikshaPageGroups']) >0){
            if($inputRequest['shikshaPageGroups'][0] != "all"){
                $mustFilters[] = array('terms' => array('pageGroup' => $inputRequest['shikshaPageGroups']));    
            }
        }

        // source application type filter
        if(!empty($inputRequest['sourceApplicationType']) && $inputRequest['sourceApplicationType'] != "all"){
            $mustFilters[] = array('term' => array('deviceType' => $inputRequest['sourceApplicationType']));
        }

        // page group filter
        if (isset($inputRequest['groupPageList']) && is_array($inputRequest['groupPageList']) && count($inputRequest['groupPageList']) >0){
            if($inputRequest['groupPageList'][0] != "all"){
                $mustFilters[] = array('terms' => array('page' => $inputRequest['groupPageList']));
            }
        }

        if (isset($inputRequest['responseWarmth']) && strcasecmp($inputRequest['responseWarmth'], 'all') != 0){
            $this->CI->load->config('globalTrackingMISConfig');
            $viewedActionList = $this->CI->config->item("VIEWED_ACTION_LIST");
            $viewedActionList = $viewedActionList[$inputRequest['misSource']];
            if($inputRequest['responseWarmth'] == "viewed"){
                $mustFilters[] = array('terms' => array('response_action_type' => $viewedActionList));
            }else{
                $mustNotFilters[] = array('terms' => array('response_action_type' => $viewedActionList));
            }
        }

        $trafficSourceFilter = $this->_prepareTrafficSourceFilter($inputRequest, "response");
        if($trafficSourceFilter !== false){
            $mustFilters[] = $trafficSourceFilter;
        }

        if ($inputRequest['misSource'] != 'SHIKSHA') {
            $teamFilter = array('term' => array('site' => 'Study Abroad'));
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $mustFilters[] = $teamFilter;
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $mustNotFilters[] = $teamFilter;
            }
        }

        if (isset($inputRequest['pageName']) && strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $mustFilters[] = array('term' => array('page' => 'universityRankingPage'));
                        } else if ($inputRequest['pageType'] == 2) {
                            $mustFilters[] = array('term' => array('page' => 'courseRankingPage'));
                        } else {
                            $mustFilters[] = array('terms' => array('page' => array('courseRankingPage','universityRankingPage')));
                        }
                    } else if ($inputRequest['pageName'] == 'categoryPage'){
                        $mustFilters[] = array('terms' => array('page' => array('categoryPage','savedCoursesTab')));
                    }else{
                        $mustFilters[] = array('term' => array('page' => $inputRequest['pageName']));
                    }
            }else{
                if($inputRequest['pageGroup'] == "examPageMain"){
                    $mustFilters[] = array('term' => array('pageGroup' => $inputRequest['pageName']));
                }else{
                    $mustFilters[] = array('term' => array('page' => $inputRequest['pageName']));
                }
            }
        }
        
        if ($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['category'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['category'],$ldbCourseIds)){
                    $mustFilters[] = array('term' => array('response_desired_course_id' => $inputRequest['category']));
                }else{
                    $mustFilters[] = array('term' => array('response_category_id' => $inputRequest['category']));
                    if($inputRequest['courseLevel'] != '0'){
                        $mustFilters[] = array('term' => array('response_course_level' => $inputRequest['courseLevel']));
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                $mustFilters[] = array('term' => array('response_course_level' => $inputRequest['courseLevel']));
            }

            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $mustFilters[] = array('term' => array('response_country_id' => $inputRequest['country']));
            }
        }

        // response paid free check
        if (strcasecmp($inputRequest['responseType'], 'all') != 0) {
            if($inputRequest['responseType'] == 'paid'){
                $mustFilters[] = array('term' => array('is_response_paid' => '1'));
            }else if($inputRequest['responseType'] == 'free'){
                $mustFilters[] = array('term' => array('is_response_paid' => '0'));
            }
        }

        //  rmc response check
        if($inputRequest['metric'] == "RMC"){
            if(isset($extraData['trackingIds']) && count($extraData['trackingIds']) >0){
                $mustFilters[] = array('terms' => array('tracking_id' => $extraData['trackingIds']));
            }else{
                $trackingIds = $this->getTrackingKeys($inputRequest, array('keyName' => 'rateMyChance'));
                if(count($trackingIds) > 0){
                    $mustFilters[] = array('terms' => array('tracking_id' => $trackingIds));
                }
            }
        }

        //_p($mustNotFilters);_p($mustFilters);die;
        if(count($mustFilters) >0){
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;    
        }

        if(count($mustNotFilters) >0){
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'] = $mustNotFilters;
        }

        //_p(json_encode($this->elasticQuery));die;
        //error_log("ELASTICQUERY : ".json_encode($this->elasticQuery));
        return $this->elasticQuery;
    }

    private function _prepareTrafficSourceFilter($inputRequest, $aspect, $pivot){
        if (!empty($inputRequest['trafficSourceType']) && strcasecmp($inputRequest['trafficSourceType'], 'all') != 0) {
            if($aspect == "registration"){
                $fieldName = "trafficSource";
                //$fieldName = (empty($pivot) || $pivot== "landingPageDoc.")?'isMobile':$pivot.'isMobile';
            }else if($aspect == "response"){
                $fieldName = "response_source";
            }else if($aspect == "pageview" || $aspect == "session"){
                $fieldName = (empty($pivot) || $pivot== "landingPageDoc.")?'source':$pivot.'source';
            }
            return array("term" => array($fieldName => $inputRequest['trafficSourceType']));
        }
        return false;
    }

    public function prepareQueryForRegistration($inputRequest,$extraData = array()){
        //_p($inputRequest);die;
        $this->elasticQuery = array(
            'index' => REGISTRATION_INDEX_NAME,
            'type'  => 'registration',
            'body'  => array(
                'size' => 0
            )
        );

        $mustFilters = array();
        $shouldFilters = array();
        $mustNotFilters = array();

        $mustFilters[] = array("term" => array("isNewReg" => "yes"));
        
        if(isset($inputRequest['stream']) && $inputRequest['stream'] >0){
            $mustFilters[] = array("term" => array("stream" => $inputRequest['stream']));
        }
        
        if(isset($inputRequest['substream']) && $inputRequest['substream'] >0){
            $mustFilters[] = array("term" => array("substream" => $inputRequest['substream']));
        }
        
        if(isset($inputRequest['specialization']) && $inputRequest['specialization'] >0){
            $mustFilters[] = array("term" => array("specialization" => $inputRequest['specialization']));
        }

        if(isset($inputRequest['baseCourse']) && $inputRequest['baseCourse'] >0){
            $mustFilters[] = array("term" => array("baseCourse" => $inputRequest['baseCourse']));
        }

        if(isset($inputRequest['mode']) && $inputRequest['mode'] >0){
            $mustFilters[] = array("term" => array("mode" => $inputRequest['mode']));
        }

        if (isset($inputRequest['utmSourceFilter']) && $inputRequest['utmSourceFilter'] != "") {
            $mustFilters[] = array("term" => array("trafficSource" => $inputRequest['utmSourceFilter']));
        }

        if($inputRequest['dateRange']['startDate'] != '' && $inputRequest['dateRange']['endDate'] != ''){
            $mustFilters[] = array(
                'range' => array(
                    'registrationDate' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00',
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
        }

        if (strcasecmp($inputRequest['sourceApplication'], 'all') != 0) {
            if (strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 || strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0) {
                $mustFilters[] = array(
                    'term' => array(
                        'sourceApplication' => ucfirst(strtolower($inputRequest['sourceApplication']))
                    )
                );
            }
        }

        if (isset($inputRequest['shikshaPages']) && is_array($inputRequest['shikshaPages']) && count($inputRequest['shikshaPages']) >0){
            if($inputRequest['shikshaPages'][0] != "all"){
                if(in_array("CHP", $inputRequest['shikshaPages'])){
                    $inputRequest['shikshaPages'][] = "courseHomePage";
                }
                $mustFilters[] = array('terms' => array('pageIdentifier' => $inputRequest['shikshaPages']));    
            }
        }

        if (isset($inputRequest['shikshaPageGroups']) && is_array($inputRequest['shikshaPageGroups']) && count($inputRequest['shikshaPageGroups']) >0){
            if($inputRequest['shikshaPageGroups'][0] != "all"){
                $mustFilters[] = array('terms' => array('pageGroup' => $inputRequest['shikshaPageGroups']));    
            }
        }

        $trafficSourceFilter = $this->_prepareTrafficSourceFilter($inputRequest, "registration");
        if($trafficSourceFilter !== false){
            $mustFilters[] = $trafficSourceFilter;
        }
        
        if ($inputRequest['misSource'] != 'SHIKSHA') {
            $teamFilter = array('term' => array('site' => 'Study Abroad'));
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                $mustFilters[] = $teamFilter;
            } else if ($inputRequest['misSource'] == 'DOMESTIC') {
                $mustNotFilters[] = $teamFilter;
            }
        }

        if (isset($inputRequest['pageName']) && strlen($inputRequest['pageName']) > 0 && strcasecmp($inputRequest['pageName'], 'all') != 0) {
            if ($inputRequest['misSource'] == 'STUDY ABROAD') {
                if ($inputRequest['pageName'] == 'rankingPage') {
                        if ($inputRequest['pageType'] == 1) {
                            $mustFilters[] = array('term' => array('pageIdentifier' => 'universityRankingPage'));
                        } else if ($inputRequest['pageType'] == 2) {
                            $mustFilters[] = array('term' => array('pageIdentifier' => 'courseRankingPage'));
                        } else {
                            $mustFilters[] = array('terms' => array('pageIdentifier' => array('courseRankingPage','universityRankingPage')));
                        }
                    }else if ($inputRequest['pageName'] == 'categoryPage'){
                        $mustFilters[] = array('terms' => array('pageIdentifier' => array('categoryPage','savedCoursesTab')));
                    }else{
                        $mustFilters[] = array('term' => array('pageIdentifier' => $inputRequest['pageName']));
                    }
            }else{
                if($inputRequest['pageGroup'] == "examPageMain"){
                    $mustFilters[] = array('term' => array('pageGroup' => $inputRequest['pageName']));
                }else{
                    $mustFilters[] = array('term' => array('pageIdentifier' => $inputRequest['pageName']));
                }
                
            }
        }
        
        if ($inputRequest['misSource'] == 'STUDY ABROAD') {
            if($inputRequest['category'] >0){
                $this->abroadCommonLib = $this->CI->load->library('listingPosting/AbroadCommonLib');
                $ldbCourseIdsArray = $this->abroadCommonLib->getAbroadMainLDBCourses();
                foreach ($ldbCourseIdsArray as $key => $value) {
                    $ldbCourseIds[]= $value['SpecializationId'];
                }
                if(in_array($inputRequest['category'],$ldbCourseIds)){
                    $mustFilters[] = array('term' => array('desiredCourse' => $inputRequest['category']));
                }else{
                    $mustFilters[] = array('term' => array('categoryId' => $inputRequest['category']));
                    if($inputRequest['courseLevel'] != '0'){
                        $mustFilters[] = array('term' => array('courseLevel' => $inputRequest['courseLevel']));
                    }
                }
            }else if(!empty($inputRequest['courseLevel']) && $inputRequest['courseLevel'] != '0'){
                $mustFilters[] = array('term' => array('courseLevel' => $inputRequest['courseLevel']));
            }

            if(!empty($inputRequest['country']) && $inputRequest['country'] != '0'){
                $shouldFilters[] = array('term' => array('prefCountry1' => $inputRequest['country']));
                $shouldFilters[] = array('term' => array('prefCountry2' => $inputRequest['country']));
                $shouldFilters[] = array('term' => array('prefCountry3' => $inputRequest['country']));
            }
        }

        if(in_array($inputRequest['abroadExam'], array('yes','no','booked'))){
            $mustFilters[] = array('term' => array('exam' => $inputRequest['abroadExam']));
        }

        if (isset($inputRequest['groupPageList']) && is_array($inputRequest['groupPageList']) && count($inputRequest['groupPageList']) >0){
            if($inputRequest['groupPageList'][0] != "all"){
                $mustFilters[] = array('terms' => array('pageIdentifier' => $inputRequest['groupPageList']));
            }
        }
        
        // source application type filter
        if(!empty($inputRequest['sourceApplicationType']) && $inputRequest['sourceApplicationType'] != "all"){
            $mustFilters[] = array('term' => array('sourceApplicationType' => $inputRequest['sourceApplicationType']));
        }

        //_p($mustNotFilters);_p($mustFilters);_p($shouldFilters);die;
        if(count($mustFilters) > 0){
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;    
        }

        if(count($mustNotFilters) > 0){
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'] = $mustNotFilters;
        }      

        if(count($shouldFilters) > 0){
            $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'] = $shouldFilters;
        }  
        //_p($this->elasticQuery);die;
        //_p((json_encode($this->elasticQuery)));die;
        //error_log("ELASTICQUERY : ".json_encode($this->elasticQuery));
        return $this->elasticQuery;
    }

    public function getTrackingKeys($inputRequest, $extraData = array()){
        $OverviewModel = $this->CI->load->model('overview_model');
        if($inputRequest['misSource'] == 'STUDY ABROAD'){
            $team = "abroad";
        }else if($inputRequest['misSource'] == 'DOMESTIC'){
            $team = "domestic";
        }
        
        if(strcasecmp($inputRequest['sourceApplication'], 'desktop') == 0 || strcasecmp($inputRequest['sourceApplication'], 'mobile') == 0){
            $deviceType = ucfirst(strtolower($inputRequest['sourceApplication']));
        }
        $result = $OverviewModel->getTrackingKeys($team,$deviceType,$extraData);
        $trackingIds = array();
        foreach ($result as $key => $trackingData) {
            $trackingIds[] = $trackingData->id;
        }
        return $trackingIds;
    }

    function prepareQueryForAssistantESIndex($inputRequest){
        //echo "sd";_p($inputRequest);die;

        //_p($inputRequest);die;
        $this->elasticQuery = array(
            'index' => SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH,
            'type'  => 'chat',
            'body'  => array(
                'size' => 0
            )
        );

        $mustFilters = array();
        $shouldFilters = array();
        $mustNotFilters = array();        

        if($inputRequest['dateRange']['startDate'] != '' && $inputRequest['dateRange']['endDate'] != ''){
            $mustFilters[] = array(
                'range' => array(
                    'queryTime' => array(
                        'gte' => $inputRequest['dateRange']['startDate'] . 'T00:00:00',
                        'lte' => $inputRequest['dateRange']['endDate'] . 'T23:59:59'
                    ),
                )
            );
        }
        //_p($mustNotFilters);_p($mustFilters);_p($shouldFilters);die;
        $hasNestedQuery = 0;
        if($inputRequest['donutChart'] != ""){
            if($inputRequest['donutChart'] == "quickReplyTopQueries"){
                $mustFilters[] = array('term' => array('queryType' => 'quickreply'));
            }else if($inputRequest['donutChart'] == "userTopQueries"){
                $mustFilters[] = array('term' => array('queryType' => 'text'));
            }else if($inputRequest['donutChart'] == "topIntentAnswered"){
                $hasNestedQuery = 1;
                $nestedQuery = array(
                    "path" => "apiResponseData",
                    "query" => array(
                        "bool" => array(
                            "must" => array(
                                "exists" => array(
                                    "field" => "apiResponseData.data.responses"
                                )
                            )
                        )
                    )
                );
                $this->elasticQuery['body']['query']['bool']['filter'][]['nested'] = $nestedQuery;
            }else if($inputRequest['donutChart'] == "topIntentUnAnswered"){
                $hasNestedQuery = 1;
                $nestedQuery = array(
                    "path" => "apiResponseData",
                    "query" => array(
                        "bool" => array(
                            "must_not" => array(
                                "exists" => array(
                                    "field" => "apiResponseData.data.responses"
                                )
                            )
                        )
                    )
                );
                $this->elasticQuery['body']['query']['bool']['filter'][]['nested'] = $nestedQuery;
            }
        }

        if($inputRequest['assistantFilter'] == "answeredQueries"){
                $hasNestedQuery = 1;
                $nestedQuery = array(
                    "path" => "apiResponseData",
                    "query" => array(
                        "bool" => array(
                            "should" => array(
                                array(
                                    "exists" => array(
                                        "field" => "apiResponseData.data.responses"
                                    )
                                ),
                                array(
                                    "term" => array(
                                        "apiResponseData.data.promptResponse.promptType" => "disambiguation"
                                    )
                                )
                            )
                        )
                    )
                );
                $this->elasticQuery['body']['query']['bool']['filter'][]['nested'] = $nestedQuery;
            }

        if($hasNestedQuery == 1){
            if(count($mustFilters) > 0){
                $this->elasticQuery['body']['query']['bool']['filter'][1]['bool']['must'] = $mustFilters;    
            }

            if(count($mustNotFilters) > 0){
                $this->elasticQuery['body']['query']['bool']['filter'][1]['bool']['must_not'] = $mustNotFilters;
            }      

            if(count($shouldFilters) > 0){
                $this->elasticQuery['body']['query']['bool']['filter'][1]['bool']['should'] = $shouldFilters;
            }
        }else{
            if(count($mustFilters) > 0){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;    
            }

            if(count($mustNotFilters) > 0){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['must_not'] = $mustNotFilters;
            }      

            if(count($shouldFilters) > 0){
                $this->elasticQuery['body']['query']['bool']['filter']['bool']['should'] = $shouldFilters;
            }
        }
              
        //_p($this->elasticQuery);die;
        //_p((json_encode($this->elasticQuery)));die;
        //error_log("ELASTICQUERY : ".json_encode($this->elasticQuery));
        return $this->elasticQuery;
    }
}
