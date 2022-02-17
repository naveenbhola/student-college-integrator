<?php


class responseProcessorElasticQueryGenerator {    

	function __construct() {
		$this->CI                    = & get_instance();  		
    }

    
    function prepareResponseQuery($entityIds, $userLocationIds, $dateRange, $entityType, $limit=0, $from=0, $getFields, $customData){
		$params                   = array();
		$params['index']          = LDB_RESPONSE_INDEX_NAME;
		$params['type']           = "response";
		
		if (count($getFields)>0) {
			$params['body']['_source'] = $getFields;
		}

		/*$params['body']['query']['filter_path'] = 'hits';*/

		if($customData['last_processed_id']>0){
			$mustFilter[]	 = $this->_setLastAllocationIdCond($customData['last_processed_id']);
		}

		if(count($customData['sort'])>0){
			$sortKey = $customData['sort'][0]['key'];
			$sortOrder = $customData['sort'][0]['order'];

			$params['body']['sort'][$sortKey]['order'] = $sortOrder;
		}

		if($from){
			$params['body']['from']   = $from;				
		}

		$params['body']['size']   = $limit;	

		$mustFilter = array();

		$current_year_as_pref_year = array(2018,2019);

		$mustFilter[]	 = $this->_setPrefYearCond($current_year_as_pref_year);

		$mustFilter[]	 = $this->_setResponseListingTypeCond($entityType);

		if(!empty($entityIds)){
			$mustFilter[]	 = $this->_setEntityIdCond($entityIds);
		}

		if(!empty($userLocationIds)){
			$mustFilter[]	 = $this->_setUserLocCond($userLocationIds);
		}

		if(!empty($dateRange)){
			$fromDate = $dateRange['from'];
			$toDate   = $dateRange['to'];
			$mustFilter[]	 = $this->_setResponseTimeCond($fromDate,$toDate);
		}

		// adding test user check
		$mustFilter[]	 = $this->_filterTestUser();

		$params['body']['query']['bool']['filter']['bool']['must'] = $mustFilter;		

		return $params;
    }

    private function _filterTestUser(){
    	return array(
    		"term"	=> array(
    			"is_test_user" => false
    		)
    	);
    }

    private function _setResponseListingTypeCond($entityType){
    	return array(
			'term' => array(
				'response_listing_type' => $entityType
			)
		);
    }

    private function _setEntityIdCond($entityIds){
    	return array(
				'terms' => array(
					'listing_type_id' => $entityIds
				)
			);			
    }

    private function _setUserLocCond($userLocationIds){
    	return array(
				'terms' => array(
					'user_location' => $userLocationIds
				)
			);			
    }

    private function _setResponseTimeCond($fromDate,$toDate){
    	return array(
				'range' => array(
					'response_time' => array(
						'gte' => $fromDate."T00:00:00",
						'lte' => $toDate."T23:59:59"
					)
				)
			);			
    }

    private function _setLastAllocationIdCond($last_processed_id){
    	return array(
				'range' => array(
					'temp_LMS_id' => array(
						'lte' => $last_processed_id
					)
				)
			);			
    }

    private function _setResponsePaidCond(){
    	return array(
			'term' => array(
				'is_response_paid' => true
			)
		);
    }

    private function _setResponseClientIdCond($clientId){
    	return array(
			'term' => array(
				'client_id' => $clientId
			)
		);
    }

    private function _setResponseActionCond($action){
    	return array(
			'term' => array(
				'response_action_type' => $action
			)
		);
    }

    private function _setExcludeTestUserCond(){
    	return array(
			'term' => array(
				'is_test_user' => false
			)
		);
    }

    private function _setInstituteIdCond($instituteId){
    	return array(
				'term' => array(
					'institute_id' => $instituteId
				)
			);			
    }

    private function _setInstituteLocCond($locationId){
    	return array(
				'term' => array(
					'response_listing_location_id' => $locationId
				)
			);
    }


    private function _setDateRangeCond($field,$startDate,$endDate){
    	if(!empty($startDate) && !empty($endDate)){
    		$cond = array(
    			'gte' => $startDate.'T00:00:00',
    			'lte' => $endDate.'T23:59:59'
    		);
    	}else if(!empty($startDate)){
    		$cond = array(
    			'gte' => $startDate.'T00:00:00'
    		);
    	}else if(!empty($endDate)){
    		$cond = array(
    			'lte' => $endDate.'T23:59:59'
    		);
    	}

    	return array(
    		'range' => array(
    			$field => $cond
    		)
    	);    	
    }

    private function _setIsReponseDoc(){
    	return array(
			'term' => array(
				'considered_for_response' => true
			)
		);
    }

    private function _setResponseCoursesCond($courses){
    	return array(
			'terms' => array(
				'response_listing_type_id' => $courses
			)
		);
    }

    private function _setResponseIdsCond($responseIds){
    	return array(
			'terms' => array(
				'temp_lms_id' => $responseIds
			)
		);
    }

    private function _setPrefYearCond($pref_year){
    	return array(
			'terms' => array(
				'pref_year' => $pref_year
			)
		);
    }

    function fetchListingResCountByClientId($clientId,$courses){
    	$params                   = array();
		$params['index']          = SHIKSHA_RESPONSE_INDEX_NAME;
		$params['type']           = "response";
		
		if (count($getFields)>0) {
			$params['body']['fields'] = $getFields;
		}

		$params['body']['size']   = 0;
		$params['body']['_source'] = array('response_city_name','response_locality_name')	;
		$mustFilter    = array();
		$mustNotFilter = array();

		$mustFilter[]	 = $this->_setResponseListingTypeCond('course');
		if($courses)
			$mustFilter[]	 = $this->_setResponseCoursesCond($courses);

		$mustFilter[]	 = $this->_setResponsePaidCond();
		$mustFilter[]	 = $this->_setResponseClientIdCond($clientId);
		$mustFilter[]	 = $this->_setExcludeTestUserCond();
		$mustFilter[]	 = $this->_setIsReponseDoc();

		$mustNotFilter[] = $this->_setResponseActionCond('marketingPage');
		$mustNotFilter[] = $this->_setExludeRdcUser();

		$params['body']['query']['bool']['filter']  =$mustFilter;
		$params['body']['query']['bool']['must_not']=$mustNotFilter;
		$aggQuery = array(
							'instituteId' => 	array(
									'terms' => array(
										'field' => "institute_id",
										"size" => 100000
									),
									'aggs' => array(
										'responseCount' => 	array(
											'terms' => array(
												'field' => "response_listing_location_id"
											)
											// ,'aggs' =>array(
											// 	'city'=>array(
											// 		'terms' => array(
											// 			'field' => "response_city_name"
											// 		),'aggs'=>array(
											// 			'locality'=>array(
											// 				'terms' => array(
											// 					'field' => "response_locality_name"
											// 				)
											// 			)
											// 		)
											// 	)
											// )
										)

									)
								)
						);
		$params['body']['aggs'] = $aggQuery;	
		return $params;		

    }

    function fetchResponseByListingId($clientId,$listingId,$listingType,$timeInterval,$start,$count,$startDate,$endDate,$locationId,$courseList,$responseIds){
    	$params                    = array();
		$params['index']           = SHIKSHA_RESPONSE_INDEX_NAME;
		$params['type']            = "response";

		if($count > 10000){
			$count = 10000;
			$params['scroll'] = '1m';
		}
		
		if(empty($responseIds)){
			$params['body']['from']    = $start;
		}
		$params['body']['size']    = $count;			

		$params['body']['_source'] = array('temp_lms_id','user_id','response_action_type','response_listing_type_id','response_listing_type','response_time');
		$params['body']['sort']    = array('response_time'=> array('order'=>'desc'));

		$mustFilter     = array();
		$mustNotFilter  = array();

		$mustFilter[]	 = $this->_setResponseListingTypeCond('course');
		$mustFilter[]	 = $this->_setResponsePaidCond();

		if($responseIds)
			$mustFilter[]	 = $this->_setResponseIdsCond($responseIds);

		if($courseList)
			$mustFilter[]	 = $this->_setResponseCoursesCond($courseList);

		$mustFilter[]	 = $this->_setExcludeTestUserCond();
		$mustFilter[]	 = $this->_setIsReponseDoc();
		$mustFilter[]	 = $this->_setResponseClientIdCond($clientId);
		
		if($listingId)
			$mustFilter[]	 = $this->_setInstituteIdCond($listingId);

		if($locationId)
			$mustFilter[]	 = $this->_setInstituteLocCond($locationId);

		$mustNotFilter[] = $this->_setResponseActionCond('marketingPage');		
		$mustNotFilter[] = $this->_setExludeRdcUser();
		
		$params['body']['query']['bool']['filter']  	= $mustFilter;
		$params['body']['query']['bool']['must_not']	= $mustNotFilter;
		if(!empty($startDate) || !empty($endDate)){
			$mustDateFilter                             = $this->_setDateRangeCond('response_time',$startDate,$endDate);			
			$params['body']['query']['bool']['must']    = $mustDateFilter;			
		}else{
			$startDate = '2008-01-01';
			$mustDateFilter                             = $this->_setDateRangeCond('response_time',$startDate);			
			$params['body']['query']['bool']['must']    = $mustDateFilter;			
		}

		if($timeInterval != 'none'){
			$timeIntervalDate                        = date('Y-m-d', strtotime("-".$timeInterval));
			$mustDateFilter                          = $this->_setDateRangeCond('response_time',$timeIntervalDate);			
			$params['body']['query']['bool']['must'] = $mustDateFilter;				
		}

		// $aggQuery = array(
		// 					'responseCount' => 	array(
		// 							'terms' => array(
		// 								'field' => "response_listing_location_id"
		// 							)
		// 					)
		// 				);

		// $params['body']['aggs'] = $aggQuery;
		// _p($params);die;

		return $params;		
    }


    public function generateCountQueryForListing($listingId,$clientId){
    	$params                   = array();
		$params['index']          = SHIKSHA_RESPONSE_INDEX_NAME;
		$params['type']           = "response";
    	
        $params['body']['query']['bool']['must'][] = $this->_setResponseCoursesCond(array($listingId));
        $params['body']['query']['bool']['must'][] = $this->_setResponseListingTypeCond('course');
        $params['body']['query']['bool']['must_not'][] = $this->_setResponseClientIdCond($clientId);
        return $params;
    }
    
    public function addSourceAndSize($source,$size,$params){
    	$params['body']['_source'] = $source;
        $params['body']['size'] = $size;
        return $params;
    }

    public function updateClientFromDocument($documentId, $clientId){
    	$updateParams = [
                                    'index' => SHIKSHA_RESPONSE_INDEX_NAME,
                                    'type'  => 'response',
                                    'id'    => $documentId,
                                    'body' => [
                                        'doc' => [
                                            'client_id' => $clientId
                                        ]
                                    ]
                                ];
        return $updateParams;
    }

    private function _setExludeRdcUser(){
    	return array(
			'term' => array(
				'is_client_response' => false
			)
		);
    }

    function getResponsesDataByListingIds($data){
    	$params                   = array();
		$params['index']          = SHIKSHA_RESPONSE_INDEX_NAME;
		$params['type']           = "response";
		
		if (count($getFields)>0) {
			$params['body']['fields'] = $getFields;
		}

		$params['body']['size']   = $data['size'];
		$params['body']['_source'] = array('user_id');

		if($data['scroll']) {
			$params['scroll'] = $data['scroll'];
		}

		$mustFilter    = array();
		$mustFilter[]	 = $this->_setResponseListingTypeCond($data['entityType']);

		if(!empty($data['entityIds']))
			$mustFilter[]	 = $this->_setResponseCoursesCond($data['entityIds']);

		$mustFilter[]	 = $this->_setExcludeTestUserCond();
		$mustFilter[]	 = $this->_setIsReponseDoc();
		$mustFilter[]	 = $this->_setUserHardBounce();

		if(!empty($data['cityIds'])){
			$mustFilter[]	 = $this->_setUserCities($data['cityIds']);
		}

		$params['body']['query']['bool']['filter']  =$mustFilter;

		if(!empty($data['startDate']) || !empty($data['endDate'])){
			$mustDateFilter                             = $this->_setDateRangeCond('response_time',$data['startDate'],$data['endDate']);
			$params['body']['query']['bool']['must']    = $mustDateFilter;			
		}

		$mustNotFilter = array();
		$mustNotFilter[] = $this->_setUserGroup($data['userGroups']);
		$mustNotFilter[] = $this->_setExcludeUnsubscribeUser();
		$params['body']['query']['bool']['must_not']    = $mustNotFilter;			
	
		if($data['sort']) {
			$params['body']['sort'] = $data['sort'];
		}

		if($data['aggQuery']) {
			$params['body']['aggs'] = $data['aggQuery'];	
		}
		if($data['filterPath']) {
			$params['filter_path'] = $data['filterPath'];
		}
		
		return $params;		

    }

    private function _setUserGroup($userGroups) {
    	return array(
			'terms' => array(
				'user_group' => $userGroups
			)
		);
    }

    private function _setUserHardBounce() {
    	return array(
			'term' => array(
				'hardbounce' => 0
			)
		);
    }

    private function _setUserCities($userCityIds){
    	return array(
				'terms' => array(
					'user_city_id' => $userCityIds
				)
			);			
    }

    private function _setExcludeUnsubscribeUser() {
    	return array(
			'term' => array(
				'is_unsubscribed_category_5' => true
			)
		);
    }

    public function getUserResponsesQuery($data = array()) {
	    if(empty($data)) {
	        return array();
        }
        $params                   = array();
        $params['index']          = SHIKSHA_RESPONSE_INDEX_NAME;
        $params['type']           = "response";

        $params['body']['size']   = $data['size'];
        $params['body']['_source'] = $data['fields'];
        if($data['sort']){
            $sortKey = $data['sort']['key'];
            $sortOrder = $data['sort']['order'];
            $params['body']['sort'][$sortKey]['order'] = $sortOrder;
        }

        $mustFilter    = array();
        if(!empty($data['site'])){
            $mustFilter[]	 = $this->_setSiteType($data['site']);
        }
        if(!empty($data['userId'])) {
            $mustFilter[]	 = $this->_setUserId($data['userId']);
        }
        $mustFilter[]	 = $this->_setIsReponseDoc();

        $params['body']['query']['bool']['must']  =$mustFilter;

        return $params;
    }

    private function _setSiteType($site) {
        return array(
            'term' => array(
                'site' => $site
            )
        );
    }

    private function _setUserId($userId) {
        return array(
            'term' => array(
                'user_id' => $userId
            )
        );
    }
}
