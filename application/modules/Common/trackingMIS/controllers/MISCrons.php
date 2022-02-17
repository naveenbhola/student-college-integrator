<?php
require_once('vendor/autoload.php');


class MISCrons extends MX_Controller
{
	private $trackingLib;
    	private $client;
        public static $MIS_REGISTRATIONS = array();
        public static $MIS_RESPONSES = array();

	public function __construct()
	{
		parent::__construct();
        $this->MIS_REGISTRATIONS = array(
            'indexName' => 'mis_registrations',
            'type' => 'registration'
            );

        $this->load->model('trackingMIS/miscronsmodel');
        $this->trackingModel = new miscronsmodel();

        $this->MISCronsLib = $this->load->library('trackingMIS/MISCronsLib');

        $this->clientParams = array();
        if(ENVIRONMENT == 'production') {
            $this->MIS_RESPONSES = array(
                'indexName' => 'mis_responses',
                'type' => 'response'
                );
            //$this->clientParams['hosts'] = array('10.10.16.72');
            $this->clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
        }
        else {
            $this->clientParams['hosts'] = array('172.16.3.111');
            $this->MIS_RESPONSES = array(
            'indexName' => 'mis_responses',
            'type' => 'response'
            );
        }
        //$this->client = new Elasticsearch\Client($this->clientParams);

        $ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
        $this->ESClientConn6 = $ESConnLib->getShikshaESServerConnection();
	}

    // Un Used
    function buildMISOverviewDataCache() {
        $this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
        $dateRange['endDate'] = date("Y-m-d",strtotime("-1 days"));
        $dateRange['startDate'] = date("Y-m-d",strtotime("-30 days"));
        $sourceArray = array('', 'national', 'abroad');        
        foreach($sourceArray as $source) {
			//$this->trackingLib->buildCacheForResponsesForPage($dateRange, $source);
			//$this->trackingLib->buildCacheForResponsesStats($dateRange, $source);
            /*$this->trackingLib->buildCacheForRegistrationForPage($dateRange, $source);
            $this->trackingLib->buildCacheForRegistrationForCategory($dateRange, $source);
            $this->trackingLib->buildCacheForRegistrationForSubCategory($dateRange, $source);
            $this->trackingLib->buildCacheForRegistrationForCity($dateRange, $source);
            $this->trackingLib->buildCacheForRegistrationForCountries($dateRange, $source);
            if($source == 'abroad'){
                $this->trackingLib->buildCacheForRegistrationForDesiredCountries($dateRange, $source);
            }*/
        }
    }

    // Un Used
    function MISOverview()
    {
        $date = date("Y-m-d",strtotime("-1 days"));

        $trackingIds = $this->trackingModel->getTrackingIds();

        foreach ($trackingIds as $key => $value) {
            if($value['site'] == 'Study Abroad'){
                $site = 'abroad';
            }else{
                $site = 'national'; // Domastic
            }
            $trackingArray[$value['id']] =  array(
                                            'keyName' => $value['keyName'],
                                            'conversionType' => $value['conversionType'],
                                            'site' => $site,
                                            'siteSource'=> $value['siteSource']
                                            );
            $trackingIdsDataArray[] = $value['id'];   
        }
        unset($trackingIds);
        $responsesArray = $this->trackingModel->getResponses($date,$trackingIdsDataArray);
        if(count($responsesArray)>0){
            foreach ($responsesArray as $key => $value) {
                if($value['visitorsessionid']){
                    $sessionIds[] = $value['visitorsessionid']; 
                }
            }    
        }
        $uniqueSessions = array_unique($sessionIds); 
        unset($sessionIds);

        if($uniqueSessions){
            $trafficSourceArray = $this->trackingModel->getTrafficSource($uniqueSessions,$date);
            unset($uniqueSessions);
            foreach ($trafficSourceArray as $key => $value) {
                if($value['source']){
                    $trafficSource = $value['source'];  
                }else{
                    $trafficSource = 'sourceMissing';
                }
                $sessionIdToTrafficSource[$value['sessionId']] = $trafficSource;
            }

            foreach ($responsesArray as $key => $value) {
                $temp =$trackingArray[$value['tracking_keyid']];
                if($value['visitorsessionid']){
                   $trafficSource = $sessionIdToTrafficSource[$value['visitorsessionid']]; 
                    if(!$trafficSource){
                        $trafficSource = 'sourceMissing';
                    }
                }else{
                    $trafficSource = 'emptySessions';
                }
                
                if($temp['keyName'] =='rateMyChance'){
                    $resultSet[$temp['site']][$temp['siteSource']][$trafficSource][$value['listing_subscription_type'].'RMC'] += $value['count'];
                }
                $resultSet[$temp['site']][$temp['siteSource']][$trafficSource][$value['listing_subscription_type']] += $value['count'];     
            }

            unset($responsesArray);

            $i=0;
            foreach ($resultSet as $source => $sourceArray) {
                foreach ($sourceArray as $sourceApplication => $sourceApplicationArray) {
                    foreach ($sourceApplicationArray as $trafficSource => $trafficSourceArray) {
                        $data[$i]['paidRMC'] = $trafficSourceArray['paidRMC']?$trafficSourceArray['paidRMC']:0;
                        $data[$i]['freeRMC'] = $trafficSourceArray['freeRMC']?$trafficSourceArray['freeRMC']:0;
                        $data[$i]['paidResponses'] = $trafficSourceArray['paid']?$trafficSourceArray['paid']:0;
                        $data[$i]['freeResponses'] = $trafficSourceArray['free']?$trafficSourceArray['free']:0;

                        $data[$i]['date'] = $date;
                        $data[$i]['source'] = $source;
                        $data[$i]['sourceApplication'] = $sourceApplication;
                        $data[$i]['trafficSource'] = $trafficSource;
                        $i++;
                    }
                }
            }
            $this->trackingModel->insertMISData($data);
        }
    }

    function populateResponseDataToElasticsearch(){
        $this->validateCron();
        $endDate = date("Y-m-d H:i:s",strtotime("-5 mins"));    
        $this->insertResponseDataToElasticsearch($endDate); // For inserting responses into Elastic Search.
        $this->updateResponseDataInElasticSearch($endDate); // Updating those response records for which response in upgraded in next day.
    }

    private function _checkIfResponseIsAlreadyInsertedInES($tempLMSIds){
        $tempLMSIdsArray = array();
        foreach ($tempLMSIds as $key => $value) {
            $tempLMSIdsArray[] = $value['tempLMSId'];
        }
        if(count($tempLMSIdsArray) >0){
            $params = array();
            $params['index'] = $this->MIS_RESPONSES['indexName'];
            $params['type'] = $this->MIS_RESPONSES['type'];
            $params['body']['size'] = count($tempLMSIdsArray);
            $params['body']['fields'] = array('tempLMSId');
            $params['body']['query']['filtered']['filter']['bool']['must']['terms']['tempLMSId'] = $tempLMSIdsArray;
            $search = $this->client->search($params);   
            //_p(json_encode($params));die;
            $countIdsInES = $search['hits']['total'];
            if($countIdsInES == count($tempLMSIdsArray)){
                return $tempLMSIdsArray;
            }else{
                $resultIds = $search['hits']['hits'];
                $tempIds =array();
                foreach ($resultIds as $key => $value) {
                    $tempIds[] = $value['fields']['tempLMSId'][0];
                }
                
                $IdsToInsert = array_diff($tempLMSIdsArray,$tempIds);
                //Insert These Ids Data To Elastic Search
                $this->dataToInsertInES($IdsToInsert);
                return $tempIds;   
            }
        }else{
            return array();
        }
    }

    private function dataToInsertInES($IdsToInsert){
        $trackingArray = $this->_getTrackingData(array(),'abroad');
        $trackingKeyForAbroad = array_keys($trackingArray);
    
        //Response Data Form TempLMSTable 
        $responsesData =  $this->trackingModel->getDataForIds($IdsToInsert,$trackingKeyForAbroad);
        if(count($responsesData)>0){
            foreach($responsesData as $key => $value) {
                // Visitor SessionId array
                if($value['visitorsessionid']){
                    $sessionIds[] = $value['visitorsessionid']; 
                }

                // course ids for domestic and abroad
                if($trackingArray[$value['tracking_keyid']]['site'] == "Study Abroad"){
                    $tempIdToCourseIdArray['abroad'][$value['id']] = $value['courseId'];
                }else{
                    $tempIdToCourseIdArray['domestic'][$value['id']] = $value['courseId'];
                }
            }   
        
            $uniqueSessionIds = array_unique($sessionIds); 
            unset($sessionIds);

            $courseInfo = $this->_getCourseInformation($tempIdToCourseIdArray);

            // Get source and utm data for session ids
            if($uniqueSessionIds){
                $sessionToSourceArray = $this->_getSessionInformation($uniqueSessionIds);    
            }

            $i =0;
            foreach($responsesData as $key => $value) {
                $trackingData = $trackingArray[$value['tracking_keyid']];
                $sessionId = $value['visitorsessionid'];
                $tempLMSId = $value['id'];
                if($trackingData['site'] &&($trackingData['site'] != "Study Abroad") &&($courseInfo[$tempLMSId]['categoryId'] == 14)){
                    $source = "Test Prep";
                }else{
                    $source = $trackingData['site'];
                }
                $finalDataForResponse[$i] = array(
                                                'tempLMSId' => intval($tempLMSId),
                                                'courseId' => intval($value['courseId']),
                                                'userId' => intval($value['userId']),
                                                'site' => $source,
                                                'categoryId' => intval($courseInfo[$tempLMSId]['categoryId']),
                                                'subCategoryId' => intval($courseInfo[$tempLMSId]['subCategoryId']),
                                                'ldbCourseId' => intval($courseInfo[$tempLMSId]['ldbCourseId']),
                                                'courseLevel' => $courseInfo[$tempLMSId]['courseLevel'],
                                                'listingTypeId' => intval($courseInfo[$tempLMSId]['listingId']),
                                                'listingType' => $courseInfo[$tempLMSId]['listingType'],
                                                'countryId' => intval($courseInfo[$tempLMSId]['countryId']),
                                                'cityId' => intval($courseInfo[$tempLMSId]['cityId']),
                                                'responseDate' => str_replace(' ','T', $value['responseDate']),
                                                'responseType' => $value['responseType'],
                                                'RMCResponseType' => (($trackingData['site']=='Study Abroad') &&($trackingData['keyName']=='rateMyChance') )?$value['responseType']:null,
                                                'trackingKeyId' => intval($value['tracking_keyid']),
                                                'pageIdentifier' => $trackingData['pageIdentifier'],
                                                'sourceApplication' => $trackingData['siteSource'],
                                                'conversionType' => $trackingData['conversionType'],
                                                'keyName' => $trackingData['keyName'],
                                                'widget' => $trackingData['widget'],
                                                );
        
                if($sessionId){
                    $finalDataForResponse[$i]['sessionId'] = $sessionId;
                    $finalDataForResponse[$i]['trafficSource'] = $sessionToSourceArray[$sessionId]['source'];
                    $finalDataForResponse[$i]['utmSource'] = $sessionToSourceArray[$sessionId]['utmSource'];
                    $finalDataForResponse[$i]['utmMedium'] = $sessionToSourceArray[$sessionId]['utmMedium'];
                    $finalDataForResponse[$i]['utmCampaign'] = $sessionToSourceArray[$sessionId]['utmCampaign'];
                }else{
                    $finalDataForResponse[$i]['sessionId'] = 'sessionIdMissing';
                    $finalDataForResponse[$i]['trafficSource'] = 'Other';
                    $finalDataForResponse[$i]['utmSource'] = 'Other';
                    $finalDataForResponse[$i]['utmMedium'] = 'Other';
                    $finalDataForResponse[$i]['utmCampaign'] = 'Other';
                }
                
                $i++;
                if($trackingData['site']!='Study Abroad'){
                    $userIdCount[intval($value['userId'])]=0;
                }
                unset($responsesData[$key]);    
            }
            // To push data to elastic search 
            $params = array();
            $params['body'] = array();
            $i = 1;
            foreach($finalDataForResponse as $result) {
                    $params['body'][] = array('index' => array(
                                            '_index' => $this->MIS_RESPONSES['indexName'],
                                            '_type' => $this->MIS_RESPONSES['type'],
                                        )
                                    );          
                    $params['body'][] = $result;
                    if ($i % 1000 == 0) {
                        $response = $this->client->bulk($params);
                        $params = array();
                        $params['body'] = array();
                        unset($response);
                    }
                    $i++;
            }
            if (!empty($params['body'])) {
                $response = $this->client->bulk($params);
            }
        }
    }

    function updateResponseDataInElasticSearch($endDate){
        // get tempLMSId from upgradedResponseData Table for which responses is upgraded
        ini_set('memory_limit', '1096M');
        set_time_limit(0);
        //$date = date("Y-m-d H:i:s");
        $tempIds = $this->trackingModel->getTempLMSIds($endDate);
        $tempLMSIds = $this->_checkIfResponseIsAlreadyInsertedInES($tempIds);
        if(count($tempLMSIds) > 0){

            $tempLMSIdsArray = $tempLMSIds;
            $trackingData = $this->_getTrackingData(array(), 'abroad');
            $trackingIdsDataArray = array_keys($trackingData);
            $result = $this->trackingModel->getResponseDataForUpgradation($tempLMSIdsArray, $trackingIdsDataArray);
            
            foreach ($result as $key => $value) {
                $trackingIds[] = $value['tracking_keyid'];
                $sessionIds[] = $value['visitorsessionid'];
                $resultSet[$value['id']] = array(
                                                'responseDate' => str_replace(' ','T', $value['submit_date']),
                                                'trackingKeyId' => intval($value['tracking_keyid']),
                                                'sessionId' => $value['visitorsessionid'],
                                                'responseType' => $value['responseType']
                                                );
            }
            unset($result);
            $trackingIds = array_unique($trackingIds);
            $sessionIds = array_unique($sessionIds);
            
            /*if(count($trackingIds)>0){
                $trackingData = $this->_getTrackingData($trackingIds);
            }*/
            if(count($sessionIds)>0){
                $sessionData = $this->_getSessionInformation($sessionIds);
            }
            
            foreach ($resultSet as $key => $value) {
                $trackingInfo = $trackingData[$value['trackingKeyId']];
                $sessionInfo = $sessionData[$value['sessionId']];
                $resultSet[$key]['pageIdentifier'] = $trackingInfo['pageIdentifier'];
                $resultSet[$key]['sourceApplication'] = $trackingInfo['siteSource'];
                $resultSet[$key]['conversionType'] = $trackingInfo['conversionType'];
                $resultSet[$key]['keyName'] = $trackingInfo['keyName'];
                $resultSet[$key]['widget'] = $trackingInfo['widget'];
                $resultSet[$key]['trafficSource'] = $sessionInfo['source'];
                $resultSet[$key]['utmSource'] = $sessionInfo['utmSource'];
                $resultSet[$key]['utmMedium'] = $sessionInfo['utmMedium'];
                $resultSet[$key]['utmCampaign'] = $sessionInfo['utmCampaign'];
                $resultSet[$key]['RMCResponseType'] =(($trackingInfo['site']=='Study Abroad') &&($trackingInfo['keyName']=='rateMyChance'))?$value['responseType']:null;
            }
            //get document form ES 
            $params = array();
            $params['index'] = $this->MIS_RESPONSES['indexName'];
            $params['type'] = $this->MIS_RESPONSES['type'];
            
            $tempLMSIdFilter = array();
            $tempLMSIdFilter['terms']['tempLMSId'] = $tempLMSIdsArray;
            $params['body']['size'] = count($tempLMSIdsArray);
            $params['body']['fields'] = array('tempLMSId');
            $params['body']['query']['filtered']['filter']['bool']['must'][] = $tempLMSIdFilter;
            
            $search = $this->client->search($params);
            $params['body'] = array();
            foreach($search['hits']['hits'] as $result) {
                
                $params['body'][] = array('update' => array(
                                        '_id' => $result['_id']
                                        )
                                );  
                
                $newDoc = $resultSet[$result['fields']['tempLMSId'][0]];
                $params['body'][] = array(
                                'doc_as_upsert' => true,
                                'doc' => $newDoc
                                );
            }
            
            $indexResponse = $this->client->bulk($params);
            foreach ($tempIds as $key => $value) {
                $tempIdArray[] = $value['tempLMSId'];
            }
            $this->trackingModel->updateStatusInUpgradedResponseTable($tempIdArray);
        }
    }

    private function _getTrackingData($trackingIds = array(), $site = 'all'){
        $trackingData = $this->trackingModel->getTrackingData($trackingIds,$site);
    
        foreach ($trackingData as $key => $value) {
            $siteArray = array('Study Abroad','Test Prep');
            if(!in_array($value['site'],$siteArray)){
                $value['site'] = 'Domestic';
            }
            $trackingArray[$value['id']] =  array(
                                            'keyName' => $value['keyName'],
                                            'pageIdentifier' => $value['page'],
                                            'widget'=> $value['widget'],
                                            'conversionType' => $value['conversionType'],
                                            'site' =>  $value['site'],
                                            'siteSource'=> $value['siteSource'],
                                            'pageGroup' => $value['pageGroup'],
                                            'siteSourceType' => $value['siteSourceType']
                                            );
        }
        return $trackingArray;
    }   

    function putResponseDataToElasticSearch(){
        $i =1;
        for ($i=10; $i <20 ; $i++) { 
            $date = date("Y-m-d",strtotime("-".$i." days"));
            //_p($date);
            $this->insertResponseDataToElasticsearch($date);
            error_log('date : '.$date);
        }
    }

    private function _getElasticIndexForResponse(){
        $params = array();
        $params['index'] = $this->MIS_RESPONSES['indexName'];
        $params['type'] = $this->MIS_RESPONSES['type'];
        return $params;
    }

    private function _getDateRangeForResponseCron($endDate){
        $params = array();
        $params = $this->_getElasticIndexForResponse();
        $params['body']['size'] = 1;
        $params['body']['fields'] = array('responseDate');
        $params['body']['sort']['responseDate']['order'] = 'desc';
        $search = $this->client->search($params);
        //_p(json_encode($params));die;
        //_p($search['hits']['total']);die;
        if($search['hits']['total'] >0){
            $dateRange['startDate'] = str_replace('T',' ',$search['hits']['hits'][0]['fields']['responseDate'][0]);            
        }else{
            $dateRange['startDate'] = date("Y-m-d H:i:s",strtotime("-30 mins"));
        }
        //$dateRange['startDate'] = date("Y-m-d H:i:s",strtotime("-17 day"));
        $dateRange['endDate'] = $endDate;
        //_p($dateRange);die;
        return $dateRange;
    }

    private function _getResponseIds($date){
        $date = str_replace(' ','T',$date);
        $params = array();
        $params = $this->_getElasticIndexForResponse();
        $params['body']['size'] = 0;
        $params['body']['query']['filtered']['filter']['bool']['must']['term']['responseDate'] = $date;
        $search = $this->client->search($params);
        //_p(json_encode($params));die;
        $totalCount = $search['hits']['total'];
        if($totalCount > 0){
            $params = array();
            $params = $this->_getElasticIndexForResponse();
            $params['body']['size'] = $totalCount;
            $params['body']['fields'] = array('tempLMSId');
            $params['body']['query']['filtered']['filter']['bool']['must']['term']['responseDate'] = $date;
            $search = $this->client->search($params);
            //_p(json_encode($params));die;
            $search = $search['hits']['hits'];
            foreach ($search as $key => $value) {
                $responseIds[] = $value['fields']['tempLMSId'][0];
            }
        }else{
            $responseIds =array();
        }   
        return $responseIds;
    }

    function insertResponseDataToElasticsearch($endDate)
    {
        ini_set('memory_limit', '1096M');
        set_time_limit(0);
        //$date = date("Y-m-d",strtotime("-1 days"));
        $dateRange = $this->_getDateRangeForResponseCron($endDate);
        //Those response Ids that are already inserted
        $responseIds = array();
        $responseIds = $this->_getResponseIds($dateRange['startDate']);
        //_p($responseIds);die;

        //Those response Ids that are already inserted and than upgrade to new date with new action
        $tempLMSIds = array();
        $tempLMSIdArray = array();
        $tempLMSIds = $this->trackingModel->getTempLMSIds($dateRange['endDate']);
        foreach ($tempLMSIds as $key => $value) {
            $tempLMSIdArray[] = $value['tempLMSId'];
        }
        //_p($tempLMSIdArray);die;

        $totalResponseIds = array_merge($responseIds,$tempLMSIdArray);
        //_p($totalResponseIds);die;
        //Response Data Form TempLMSTable 
        $trackingArray = $this->_getTrackingData(array(),'abroad');
        $trackingKeyForAbroad  = array_keys($trackingArray);
        $responsesData = $this->trackingModel->getResponseData($dateRange,$trackingKeyForAbroad);
        //$tempLMSIds = $this->trackingModel->getTempLMSIds();
        
        if(count($responsesData)>0){
            foreach($responsesData as $key => $value) {
                if(in_array($value['id'],$totalResponseIds)){
                    unset($responsesData[$key]);
                }else{
                    // Visitor SessionId array
                    if($value['visitorsessionid']){
                        $sessionIds[] = $value['visitorsessionid']; 
                    }

                    // course ids for domestic and abroad
                    if($trackingArray[$value['tracking_keyid']]['site'] == "Study Abroad"){
                        $tempIdToCourseIdArray['abroad'][$value['id']] = $value['courseId'];
                    }else{
                        $tempIdToCourseIdArray['domestic'][$value['id']] = $value['courseId'];
                    }
                }
            }
        
            $uniqueSessionIds = array_unique($sessionIds); 
            unset($sessionIds);

            $courseInfo = $this->_getCourseInformation($tempIdToCourseIdArray);

            // Get source and utm data for session ids
            if($uniqueSessionIds){
                $sessionToSourceArray = $this->_getSessionInformation($uniqueSessionIds);    
            }

            $i =0;
            foreach($responsesData as $key => $value) {
                $trackingData = $trackingArray[$value['tracking_keyid']];
                $sessionId = $value['visitorsessionid'];
                $tempLMSId = $value['id'];
                if($trackingData['site'] &&($trackingData['site'] != "Study Abroad") &&($courseInfo[$tempLMSId]['categoryId'] == 14)){
                    $source = "Test Prep";
                }else{
                    $source = $trackingData['site'];
                }
                $finalDataForResponse[$i] = array(
                                                'tempLMSId' => intval($tempLMSId),
                                                'courseId' => intval($value['courseId']),
                                                'userId' => intval($value['userId']),
                                                'site' => $source,
                                                'categoryId' => intval($courseInfo[$tempLMSId]['categoryId']),
                                                'subCategoryId' => intval($courseInfo[$tempLMSId]['subCategoryId']),
                                                'ldbCourseId' => intval($courseInfo[$tempLMSId]['ldbCourseId']),
                                                'courseLevel' => $courseInfo[$tempLMSId]['courseLevel'],
                                                'listingTypeId' => intval($courseInfo[$tempLMSId]['listingId']),
                                                'listingType' => $courseInfo[$tempLMSId]['listingType'],
                                                'countryId' => intval($courseInfo[$tempLMSId]['countryId']),
                                                'cityId' => intval($courseInfo[$tempLMSId]['cityId']),
                                                'responseDate' => str_replace(' ','T', $value['responseDate']),
                                                'responseType' => $value['responseType'],
                                                'RMCResponseType' => (($trackingData['site']=='Study Abroad') &&($trackingData['keyName']=='rateMyChance') )?$value['responseType']:null,
                                                'trackingKeyId' => intval($value['tracking_keyid']),
                                                'pageIdentifier' => $trackingData['pageIdentifier'],
                                                'sourceApplication' => $trackingData['siteSource'],
                                                'conversionType' => $trackingData['conversionType'],
                                                'keyName' => $trackingData['keyName'],
                                                'widget' => $trackingData['widget'],
                                                );
        
                if($sessionId){
                    $finalDataForResponse[$i]['sessionId'] = $sessionId;
                    $finalDataForResponse[$i]['trafficSource'] = $sessionToSourceArray[$sessionId]['source'];
                    $finalDataForResponse[$i]['utmSource'] = $sessionToSourceArray[$sessionId]['utmSource'];
                    $finalDataForResponse[$i]['utmMedium'] = $sessionToSourceArray[$sessionId]['utmMedium'];
                    $finalDataForResponse[$i]['utmCampaign'] = $sessionToSourceArray[$sessionId]['utmCampaign'];
                }else{
                    $finalDataForResponse[$i]['sessionId'] = 'sessionIdMissing';
                    $finalDataForResponse[$i]['trafficSource'] = 'Other';
                    $finalDataForResponse[$i]['utmSource'] = 'Other';
                    $finalDataForResponse[$i]['utmMedium'] = 'Other';
                    $finalDataForResponse[$i]['utmCampaign'] = 'Other';
                }
                
                $i++;
                if($trackingData['site']!='Study Abroad'){
                    $userIdCount[intval($value['userId'])]=0;
                }
                unset($responsesData[$key]);    
            }
            unset($sessionToSourceArray);
            unset($courseInfo);
            unset($trackingArray);
            // To push data to elastic search 
            $params = array();
            $params['body'] = array();
            $i = 1;
            foreach($finalDataForResponse as $result) {
                    $params['body'][] = array('index' => array(
                                            '_index' => $this->MIS_RESPONSES['indexName'],
                                            '_type' => $this->MIS_RESPONSES['type'],
                                        )
                                    );          
                    $params['body'][] = $result;
                    if ($i % 1000 == 0) {
                        $response = $this->client->bulk($params);
                        $params = array();
                        $params['body'] = array();
                        unset($response);
                    }
                    $i++;
            }
            if (!empty($params['body'])) {
                $response = $this->client->bulk($params);
            }
        }
    }

    private function _getSessionInformation($uniqueSessionIds){
        $result = $this->trackingModel->getSessionInformation($uniqueSessionIds);
        foreach ($result as $key => $value) {
                $sessionInfo[$value['sessionId']] = array(
                                                        'source' => $value['source']?$value['source']:'Other',
                                                        'utmCampaign' => $value['utm_campaign']?$value['utm_campaign']:'Other',
                                                        'utmSource' => $value['utm_source']?$value['utm_source']:'Other',
                                                        'utmMedium' => $value['utm_medium']?$value['utm_medium']:'Other'
                                                        );
        }
        foreach ($uniqueSessionIds as $sessionId) {
            if(!$sessionInfo[$sessionId]){
                $sessionInfo[$sessionId] = array(
                                                'source' => 'Other',
                                                'utmCampaign' =>'Other',
                                                'utmSource' => 'Other',
                                                'utmMedium' => 'Other'
                                                );
            }
        }
        return $sessionInfo;
    }

    private function _getCourseInformation($tempIdToCourseIdArray){
        // For Abroad Course Info
        if(count($tempIdToCourseIdArray['abroad']) > 0){
            $courseIds = array_values($tempIdToCourseIdArray['abroad']);
            //_p($courseIds);die;
            $result = $this->_getAbroadCourseinformation($courseIds);            
            foreach ($tempIdToCourseIdArray['abroad'] as $key => $value) {
                $abroadCourseInfo[$key] = $result[$value];
            }
        }

        // For Domestic & Test Prep Course Info 
        if(count($tempIdToCourseIdArray['domestic']) > 0){
            //$domesticCourseInfo = $this->_getDomesticCourseinformation($tempIdToCourseIdArray['domestic']);
        }
        if($abroadCourseInfo && $domesticCourseInfo){
            $result = $abroadCourseInfo + $domesticCourseInfo;    
        }else if(!$abroadCourseInfo && $domesticCourseInfo){
            $result = $domesticCourseInfo;    
        }else if($abroadCourseInfo && !$domesticCourseInfo){
            $result = $abroadCourseInfo;
        }
        
        return $result;
    }

    private function _getDomesticCourseinformation($inputArray){
        $courseInformationArray = $inputArray;
        $tempLMSIds = array_keys($inputArray);
        $courseIds = array();
        $courseIds = array_values($inputArray);
        $courseIds = array_unique($courseIds);
        
        // Get course institute Id and city Id
        $locationData = $this->_getLocationData($tempLMSIds);
        
        $testPrepCourseIds =  $this->trackingModel->getTestPrepCourse($courseIds);
        $testPrepIds = array();
        foreach ($testPrepCourseIds as $key => $value) {
            $testPrepIds[] = $value['course_id'];
        }
        $nonTestPrepCourseIds = array_diff($courseIds, $testPrepIds);
        // Now filter data for Test-Prep and Domestic for getting (subCat, courseLevel)
        $courseInformationData = $this->getDomesticCourseData($testPrepIds,$nonTestPrepCourseIds);

        // Course Level
        //_p($courseInformationData);die;
        foreach ($courseInformationArray as $key => $value) {
            $courseInformation[$key] = $locationData[$key];
            $courseInformation[$key]['categoryId'] = $courseInformationData[$value]['categoryId'];
            $courseInformation[$key]['subCategoryId'] = $courseInformationData[$value]['subCategoryId'];
            $courseInformation[$key]['ldbCourseId'] = $courseInformationData[$value]['ldbCourseId'];
            $courseInformation[$key]['courseLevel'] = $courseInformationData[$value]['courseLevel'];
        }
        return $courseInformation;
    }

    function getDomesticCourseData($testPrepCourseIds,$domesticCourseIds){

        //For Domestic
        if(count($domesticCourseIds)>0){
            // Get Course dominant SubCategory Id
            $this->nationalCourseLib = $this->load->library('listing/NationalCourseLib');
            $result = $this->nationalCourseLib->getCourseDominantSubCategoryDB($domesticCourseIds);
            foreach ($result['subCategoryInfo'] as $key => $value) {
                $courseInformationData[$key]['subCategoryId'] = $value['dominant'];
                $subCategoryIds[] = $value['dominant'];
            }
            
            $subCategoryIds = array_unique($subCategoryIds);
            if(count($subCategoryIds)>0){
                $categoryIds = $this->trackingModel->getCategoryForCourse($subCategoryIds);
            
                foreach ($categoryIds as $key => $value) {
                    $subCatToCatMapping[$value['boardId']] = $value['parentId'];
                }

                foreach ($courseInformationData as $key => $value) {
                    $courseInformationData[$key]['categoryId'] = $subCatToCatMapping[$value['subCategoryId']];
                }
            
                $ldbCourseIds =  $this->trackingModel->getLDBCourseIdForCourse($subCategoryIds);        
            
                foreach ($ldbCourseIds as $key => $value) {
                    $subCatToldbCourseId[$value['categoryID']] = $value['ldbCourseID'];
                    $ldbCourseId[] = $value['ldbCourseID'];
                }

                $ldbCourseId = array_unique($ldbCourseId);
            
                foreach ($courseInformationData as $key => $value) {
                    $courseInformationData[$key]['ldbCourseId'] = $subCatToldbCourseId[$value['subCategoryId']];
                }
            
                //Course Level
                if(count($ldbCourseId)>0){
                    $result = $this->trackingModel->getCourseLevelForCourse($ldbCourseId);
                }
                foreach ($result as $key => $value) {
                    $ldbCourseIdToCourseLevel[$value['SpecializationId']] = $value['CourseName'];
                }
                
                foreach ($courseInformationData as $key => $value) {
                    $courseInformationData[$key]['courseLevel'] = $ldbCourseIdToCourseLevel[$value['ldbCourseId']];
                }
            }
        }
        //For Test Prep
        if(count($testPrepCourseIds)>0){
            // Get Blog Id
            
            $blogIdsArray = $this->trackingModel->getBlogIdsForCourse($testPrepCourseIds);    
            
            foreach ($blogIdsArray as $key => $value) {
                $courseToBlogId[$value['clientCourseID']] = $value['blogId'];
                $blogIds[] = $value['blogId'];
            }
            if(count($blogIds)>0){
                $result = $this->trackingModel->getCourseSubCatCourseLevelInfo($blogIds);
            }
            
            foreach ($result as $key => $value) {
                $ldbCourseIdToSubCatMapping[$value['blogId']] =  array(
                                                                        'subCategoryId' => $value['boardId'],
                                                                        'courseLevel' => $value['blogTypeValues']
                                                                        );
            }
            
            foreach ($testPrepCourseIds as $key => $value) {
                $courseInformationData[$value]['categoryId'] = 14;
                $courseInformationData[$value]['ldbCourseId'] = $courseToBlogId[$value];
                $courseInformationData[$value]['courseLevel'] = $ldbCourseIdToSubCatMapping[$courseToBlogId[$value]]['courseLevel'];
                $courseInformationData[$value]['subCategoryId'] = $ldbCourseIdToSubCatMapping[$courseToBlogId[$value]]['subCategoryId'];
            }
        }   
        return $courseInformationData;
    }

    // For Domestic Course Location Information
    private function _getLocationData($tempLMSIds){
        $result =  $this->trackingModel->getInstituteLocationIds($tempLMSIds);
        foreach ($result as $key => $value) {
            $instituteLocationIds[] = $value['instituteLocationId'];
            $tempLMSIdArray[$value['responseId']] = $value['instituteLocationId'];
        }
        $instituteLocationIds =array_unique($instituteLocationIds);
        if(count($instituteLocationIds)>0){
            $result = $this->trackingModel->getLocationIdForCourse($instituteLocationIds);
        }
        foreach ($result as $key => $value) {
            $instituteLocationIdArray[$value['institute_location_id']] = array(
                                                                                'listingId' => $value['institute_id'],
                                                                                'listingType' => 'institute',
                                                                                'cityId' => $value['city_id'],
                                                                                'countryId' => 2
                                                                                );
        }

        foreach ($tempLMSIdArray as $key => $value) {
            if(!$instituteLocationIdArray[$value]){
                $tempLMSIdArray[$key] =  array(
                                            'listingId' => '',
                                            'listingType' => 'institute',
                                            'cityId' => '',
                                            'countryId' => 2
                                            );
            }else{
                $tempLMSIdArray[$key] = $instituteLocationIdArray[$value];    
            }
        }

        return $tempLMSIdArray;   
    }

    // For Abroad Course Information
    public function _getAbroadCourseinformation($courseIds){
        $this->abroadCommonLib = $this->load->library('listingPosting/AbroadCommonLib');
        $desiredCourses = $this->abroadCommonLib->getAbroadMainLDBCourses();    // popular ldb courses
        foreach ($desiredCourses as $key => $value) {
            $desiredCourseIds[] = $value['SpecializationId'];
        }
        $result = $this->trackingModel->getAbroadCourseInformation($courseIds,$desiredCourseIds);
        foreach ($result as $key => $value) {
            $aboradCourseData[$value['course_id']] = array(
                                                            'categoryId' => $value['category_id'],
                                                            'subCategoryId' => $value['sub_category_id'],
                                                            'ldbCourseId' => $value['ldb_course_id'],
                                                            'courseLevel' => $value['course_level'],
                                                            'listingId' => $value['university_id'],
                                                            'listingType' => 'university',
                                                            'countryId' => $value['country_id'],
                                                            'cityId' => $value['city_id'],
                                                            );
        }
        return $aboradCourseData;
    }

    /*
    Cron Function To Populate Registration Data To Elastic Search
    1. registration cron run in every half an hour.
    2. if previous cron is some how fail than next cron pic data of previous cron
    3. there is no updation flow in registration like response
    4. Condition : 
        a. check last entry in Elastic search
        b. endDate = "now()-half an hour
        c. startdate = last entry in elastic search date time
        d. for last date time with have to skip all entry that are previously added in ES
        e. take only registration date not leads data
    */

    function putRegistrationDataToElasticSearch(){
        $i =1;
        for ($i=1; $i <20 ; $i++) { 
            $date = date("Y-m-d",strtotime("-".$i." days"));
            $dateRange['startDate'] = $date.' 00:00:00';
            $dateRange['endDate'] = $date.' 23:59:59';
            $this->populateRegistrationDataToElasticSearch($dateRange);
            error_log('date range: '.$dateRange['startDate'].'-'.$dateRange['endDate']);
        }
    }

    function populateRegistrationDataToElasticSearch(){
        $this->validateCron();
        ini_set('memory_limit', '2096M');
        set_time_limit(0);
        $dateRange = $this->_getDateRange();
        //error_log("REG MIS :: Date Range : ".print_r($dateRange,true)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        $registrationIds = array();
        $registrationIds = $this->_getRegistrationIds($dateRange['startDate']);
        //error_log("REG MIS :: already in ES : ".count($registrationIds)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        $registrationData = $this->trackingModel->getRegistrationdataFromDB($dateRange);
        //error_log("REG MIS :: New Data : ".count($registrationData)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        if(count($registrationData)>0){
            $trackingArray = $this->_getTrackingData();
        
            $userIds = array();
            $blogIds =array();
            $desiredCourseIds =array();
            $nationalUserIds = array();
            $abroadUserIds = array();
            $testPrepUserIds = array();
            foreach ($registrationData as $key => $value) {
                if(in_array($value['id'],$registrationIds)){
                    unset($registrationData[$key]);
                    continue;
                }
                // get vistorSessionIds
                if($value['visitorSessionId']){
                    $sessionIds[$value['visitorSessionId']] = 0; 
                }
                
                // in case of abroad we have to fetch courseLevel based on desired course. in future may be also user in domestic
                // courseLevel only for abroad
                switch ($value['userType']) {
                    case 'national':
                        $nationalUserIds[$value['userId']] = strtotime($value['usercreationDate']);
                        break;

                    case 'abroad':
                        if($value['desiredCourse']){
                            $desiredCourseIds[$value['desiredCourse']] =0 ;
                        }
                        $abroadUserIds[] = $value['userId'];
                        break;

                    case 'testPrep':
                        $testPrepUserIds[] = $value['userId'];
                        break;                        
                }        
            }

            if(count($testPrepUserIds) >0 ){
                mail('praveen.singhal@99acres.com','Test Pref User Data Found on '.date('Y-m-d H:i:s'), 'User Ids: '.'<br/>'.print_r($testPrepUserIds, true));
            }

            if(count($abroadUserIds) > 0){
                $userExamMapping = $this->MISCronsLib->getUserWithExamMapping($abroadUserIds);
            }

            $userDataAttributes = array();
            if(count($nationalUserIds) > 0){
                $userDataAttributes = $this->MISCronsLib->getUserDataAttributes($nationalUserIds);
                $userDataAttributes = $this->_filterNullValues($userDataAttributes);
            }

            //error_log("REG MIS :: User Ids : ".print_r(array_keys($nationalUserIds),true)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
            //error_log("REG MIS :: User Ids : ".count(array_keys($nationalUserIds))."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));

            // Get trafficSource and utm data for session ids
            if(count($sessionIds) >0){
                $uniqueSessionIds = array_keys($sessionIds);
                unset($sessionIds);
                $sessionToSourceArray = $this->_getSessionInformationFromES($uniqueSessionIds);
                unset($uniqueSessionIds);
            }

            // get courseLevel for abroad
            $result = $this->_getCourseLevelForDesiredCourse($desiredCourseIds);
            $desiredCourseToCourseLevel = $result['desiredCourseToCourseLevel'];
            $blogIdToCourseLevel = $result['blogIdToCourseLevel'];
            unset($result);
            $finalDataForRegistration = array();
            $index =0;
            //error_log("REG MIS :: Registration Data : ".count($registrationData)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
            foreach($registrationData as $key => $value) {
                $trackingData = $trackingArray[$value['trackingkeyId']];
                if($value['visitorSessionId']){
                    $sessionIdData = $sessionToSourceArray[$value['visitorSessionId']];
                }
                $courseLevel = '';
                if($value['userType'] == 'abroad'){
                    $courseLevel = $desiredCourseToCourseLevel[$value['desiredCourse']];
                }

                $siteArray = array('Study Abroad','Test Prep');
                if($trackingData['site'] && (!in_array($trackingData['site'],$siteArray))){
                    $source = "Domestic";
                }else{
                    $source = $trackingData['site'];
                }
                $finalDataForRegistration[$index] = array(
                    'registrationId' => intval($value['id']),
                    'userId' => intval($value['userId']),
                    'source' => $value['source'],
                    'userType' => $value['userType'],
                    'isNewReg' => $value['isNewReg'],
                    'registrationDate' => str_replace(' ','T', $value['usercreationDate']),
                    'countryId' => $value['country']?intval($value['country']):'',
                    'cityId' => $value['city']?intval($value['city']):'',
                    'trackingKeyId' => intval($value['trackingkeyId']),
                    'pageIdentifier' => $trackingData['pageIdentifier'],
                    'sourceApplication' => $trackingData['siteSource'],
                    'conversionType' => $trackingData['conversionType'],
                    'keyName' => $trackingData['keyName'],
                    'widget' => $trackingData['widget'],
                    'site' => $source,
                    'sessionId' => $value['visitorSessionId']?$value['visitorSessionId']:'sessionIdMissing',
                    'trafficSource' => $value['visitorSessionId'] ? $sessionIdData['source']:'Other',
                    'utmSource' => $value['visitorSessionId'] ? $sessionIdData['utmSource']:'Other',
                    'utmMedium' => $value['visitorSessionId'] ? $sessionIdData['utmMedium']:'Other',
                    'utmCampaign' => $value['visitorSessionId'] ? $sessionIdData['utmCampaign']:'Other',
                    'geocountry' => $value['visitorSessionId'] ? $sessionIdData['geocountry']:'Unknown',
                    'geocity' => $value['visitorSessionId'] ? $sessionIdData['geocity']:'Unknown'
                    );

                if($value['userType'] == 'abroad'){
                    $finalDataForRegistration[$index]['courseLevel'] = $courseLevel;
                    $finalDataForRegistration[$index]['categoryId'] = $value['categoryId']?intval($value['categoryId']):'';
                    $finalDataForRegistration[$index]['subCategoryId'] = $value['subCatId']?intval($value['subCatId']):'';
                    $finalDataForRegistration[$index]['desiredCourse'] = $value['desiredCourse']?intval($value['desiredCourse']):'';

                    $finalDataForRegistration[$index]['prefCountry1'] = $value['prefCountry1']?intval($value['prefCountry1']):'';
                    $finalDataForRegistration[$index]['prefCountry2'] = $value['prefCountry2']?intval($value['prefCountry2']):'';
                    $finalDataForRegistration[$index]['prefCountry3'] = $value['prefCountry3']?intval($value['prefCountry3']):'';
                }else if($value['userType'] == 'national'){
                    $finalDataForRegistration[$index]['stream']         = $userDataAttributes[$value['userId']]['streamIds'];

                    if(is_array($userDataAttributes[$value['userId']]['subStreamIds'])){
                        $finalDataForRegistration[$index]['substream']      = $userDataAttributes[$value['userId']]['subStreamIds'];    
                    }
                    
                    if(is_array($userDataAttributes[$value['userId']]['specializationIds'])){
                        $finalDataForRegistration[$index]['specialization'] = $userDataAttributes[$value['userId']]['specializationIds'];    
                    }
                    
                    $finalDataForRegistration[$index]['baseCourse']     = $userDataAttributes[$value['userId']]['baseCourseIds'];
                    $finalDataForRegistration[$index]['mode']           = $userDataAttributes[$value['userId']]['mode'];
                }

                $registrationDateUTC = convertDateISTtoUTC($value['usercreationDate']);
                $finalDataForRegistration[$index]['time']                = date("H:i:s",strtotime($registrationDateUTC));
                $finalDataForRegistration[$index]['registrationDateUTC'] = str_replace(' ','T', $registrationDateUTC);

                if(in_array($value['userId'], $abroadUserIds)){
                    $finalDataForRegistration[$index]['exam'] = $userExamMapping[$value['userId']];
                }
                $index++;
                
                unset($registrationData[$key]);    
            }
            //_p($finalDataForRegistration);die;
            unset($sessionToSourceArray);
            unset($desiredCourseToCourseLevel);
            unset($blogIdToCourseLevel);
            //echo 'Just before Insertion in elastic search';die;
            // To push data to elastic search 
            $params = array();
            $params['body'] = array();
            $i = 1;
            echo 'Total Data :'.count($finalDataForRegistration).'</br>';

            //$actualIndexedUserIds = array();
            foreach($finalDataForRegistration as $result) {
                    $params['body'][] = array('index' => array(
                                            '_index' => $this->MIS_REGISTRATIONS['indexName'],
                                            '_type' =>  $this->MIS_REGISTRATIONS['type'],
                                            '_id' => "registration_".$result['userId']
                                        )
                                    );          
                    $params['body'][] = $result;
                    //$actualIndexedUserIds[] = $result['userId'];
                    if ($i % 1000 == 0) {
                        $registrationData = $this->client->bulk($params);
                        $params = array();
                        $params['body'] = array();
                        unset($registrationData);
                    }
                    $i++;
            }
            if (!empty($params['body'])) {
                $response = $this->client->bulk($params);
            }

            //error_log("REG MIS :: Indexed User Ids : ".print_r($actualIndexedUserIds,true)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
            //error_log("REG MIS :: Indexed User Ids count : ".count($actualIndexedUserIds)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        }
    }

    private function _getElasticIndexForRegistraion(){
        $params = array();
        $params['index'] = $this->MIS_REGISTRATIONS['indexName'];
        $params['type'] = $this->MIS_REGISTRATIONS['type'];
        return $params;
    }

    private function _getDateRange(){
        $params = array();
        $params = $this->_getElasticIndexForRegistraion();
        $params['type'] = $this->MIS_REGISTRATIONS['type'];
        $params['body']['size'] = 1;
        $params['body']['fields'] = array('registrationDate');
        $params['body']['sort']['registrationDate']['order'] = 'desc';
        $search = $this->client->search($params);
        //_p(json_encode($params));die;
        //_p($search['hits']['total']);die;
        if($search['hits']['total'] >0){
        $dateRange['startDate'] = str_replace('T',' ',$search['hits']['hits'][0]['fields']['registrationDate'][0]);            
        }else{
            $dateRange['startDate'] = date("Y-m-d H:i:s",strtotime("-30 mins"));
        }
        $dateRange['endDate'] = date("Y-m-d H:i:s",strtotime(date()."-5 minute"));
        //_p($dateRange);die;
        return $dateRange;
    }

    private function _getRegistrationIds($date){
        $date = str_replace(' ','T',$date);
        $params = array();
        $params = $this->_getElasticIndexForRegistraion();
        $params['body']['size'] = 0;
        $params['body']['query']['filtered']['filter']['bool']['must']['term']['registrationDate'] = $date;
        $search = $this->client->search($params);
        //_p(json_encode($params));die;
        $totalCount = $search['hits']['total'];
        if($totalCount > 0){
            $params = array();
            $params = $this->_getElasticIndexForRegistraion();
            $params['body']['size'] = $totalCount;
            $params['body']['fields'] = array('registrationId');
            $params['body']['query']['filtered']['filter']['bool']['must']['term']['registrationDate'] = $date;
            $search = $this->client->search($params);
            //_p(json_encode($params));die;
            $search = $search['hits']['hits'];
            foreach ($search as $key => $value) {
                $registrationIds[] = $value['fields']['registrationId'][0];
            }
        }else{
            $registrationIds =array();
        }   
        return $registrationIds;
    }

    private function _getCourseLevelForDesiredCourse($desiredCourseIds){
        if(count($desiredCourseIds) > 0){
            $abroadDesiredCourseIds = array_keys($desiredCourseIds);
            //_p($abroadDesiredCourseIds);die;
            $result = $this->trackingModel->getDesiredCourseToCourseLevel($abroadDesiredCourseIds,'abroad');
            foreach ($result as $key => $value) {
                $resultSet['desiredCourseToCourseLevel'][$value['SpecializationId']] = $value['CourseName']?$value['CourseName']:'';
            }
        }
        
        return $resultSet;
    }

    /* 
        this is one time script.
        used to add new field "exam" in abroad registration data in Elastic search
        exam field values : 
            1.  yes
            2.  no
            3.  booked
    */    
    public function addExamFieldInAbroadRegistrationDataToES(){
        mail('praveen.singhal@99acres.com','addExamFieldInAbroadRegistrationDataToES function called on '.date('Y-m-d H:i:s'), "");
        return;
        ini_set('memory_limit', '256M');
        $chunkSize = 5000;
        $offset = 0;
        $size = $chunkSize;
        do{
            $userData = $this->_getUserIdsFromRegistrationESIndex($offset,$size);
            $docCount = count($userData);
            $userExamMapping = $this->MISCronsLib->getUserWithExamMapping(array_keys($userData));

            $elasticQuery = array();
            $elasticQuery['index'] = REGISTRATION_INDEX_NAME;
            $elasticQuery['type'] = 'registration';
            foreach ($userData as $userId => $ESRegDocId) {
                $elasticQuery['body'][] = array(
                                                'update'    => array(
                                                        '_id' => $ESRegDocId
                                                    )
                                                );
                $elasticQuery['body'][] = array(
                                            'doc_as_upsert' => true,
                                            'doc'           => array('exam' => $userExamMapping[$userId])
                                            );
                unset($userData[$userId]);
                unset($userExamMapping[$userId]);
            }
            $indexResponse = $this->client->bulk($elasticQuery);
            if($docCount < $chunkSize){
                break;
            }else{
                $offset += $size;
            }
        }while(!($docCount < $chunkSize));
    }

    private function _getUserIdsFromRegistrationESIndex($offset,$size){
        $elasticQuery = array();
        $elasticQuery['index'] = REGISTRATION_INDEX_NAME;
        $elasticQuery['type'] ='registration';
        $elasticQuery['body']['fields'] = array("userId");
        $elasticQuery['body']['from'] = $offset;
        $elasticQuery['body']['size'] =$size;
        $elasticQuery['body']['query']['filtered']['filter']['bool']['must'][]['term']['site'] = "Study Abroad";
        $result = $this->client->search($elasticQuery);
        $userIds = array();
        if(!empty($result['hits']['hits'])){
            foreach ($result['hits']['hits'] as $key => $ElasticsearchDoc) {
                $userIds[$ElasticsearchDoc['fields']['userId'][0]] = $ElasticsearchDoc['_id'];
                unset($result['hits']['hits'][$key]);
            }
        }
        return $userIds;
    }

    function migrateNationalRegToES(){
        $date = "2017-03-01";
        for ($i=1; $i <=100 ; $i++) {
            $date = date('Y-m-d',strtotime('+1 day'.$date));
            $dateRange['startDate'] = $date.' 00:00:00';
            $dateRange['endDate'] = $date.' 23:59:59';
            error_log('date range: '.$dateRange['startDate'].' =================  '.$dateRange['endDate']);
            die;
            $this->migrateNationalReg($dateRange);
        }
    }

    function migrateNationalReg($dateRange){
        //$this->validateCron();
        ini_set('memory_limit', '2096M');
        set_time_limit(0);

        $registrationData = $this->trackingModel->getNationalData($dateRange);
        //_p($registrationData);die;
        error_log("Total Reg Count : ".count($registrationData));
        if(count($registrationData)>0){
            $trackingArray = $this->_getTrackingData();

            $userIds = array();
            $desiredCourseIds =array();
            $nationalUserIds = array();
            $abroadUserIds = array();
            $testPrepUserIds = array();

            foreach ($registrationData as $key => $value) {
                // get vistorSessionIds
                if($value['visitorSessionId']){
                    $sessionIds[$value['visitorSessionId']] = 0; 
                }

                switch ($value['userType']) {
                    case 'national':
                        $nationalUserIds[$value['userId']] = strtotime($value['usercreationDate']);
                        break;

                    case 'abroad':
                        $abroadUserIds[] = $value['userId'];
                        break;

                    case 'testPrep':
                        $testPrepUserIds[] = $value['userId'];
                        break;                        
                }
            }

            if(count($testPrepUserIds) >0 ){
                //mail('praveen.singhal@99acres.com','Test Pref User Data Found on '.date('Y-m-d H:i:s'), 'User Ids: '.'<br/>'.print_r($testPrepUserIds, true));
                error_log(" Test Pref User ids : ".print_r($testPrepUserIds,true));
            }

            if(count($abroadUserIds) > 0){
                error_log(" Abroad User ids : ".print_r($testPrepUserIds,true));
            }

            $userDataAttributes = array();
            if(count($nationalUserIds) > 0){
                $userDataAttributes = $this->MISCronsLib->getUserDataAttributes($nationalUserIds);
                $userDataAttributes = $this->_filterNullValues($userDataAttributes);
            }

            // need to change this function
            if(count($sessionIds) >0){
                $uniqueSessionIds = array_keys($sessionIds);
                //_p($uniqueSessionIds);die;
                unset($sessionIds);
                $sessionToSourceArray = $this->_getSessionInformationFromES($uniqueSessionIds);
                unset($uniqueSessionIds);
            }
            
            $finalDataForRegistration = array();
            $index =0;
            
            foreach($registrationData as $key => $value) {
                if($value['userType'] == "abroad"){
                    error_log(" Abroad User Data : ".print_r($value,true));
                    continue;
                }

                $trackingData = $trackingArray[$value['trackingkeyId']];
                if($value['visitorSessionId']){
                    $sessionIdData = $sessionToSourceArray[$value['visitorSessionId']];
                }
                
                $siteArray = array('Study Abroad','Test Prep');
                if($trackingData['site'] && (!in_array($trackingData['site'],$siteArray))){
                    $source = "Domestic";
                }else{
                    $source = $trackingData['site'];
                }

                $finalDataForRegistration[$index] = array(
                    'registrationId' => intval($value['id']),
                    'userId' => intval($value['userId']),
                    'source' => $value['source'],
                    'userType' => $value['userType'],
                    'isNewReg' => $value['isNewReg'],
                    'registrationDate' => str_replace(' ','T', $value['usercreationDate']),
                    'countryId' => $value['country']?intval($value['country']):'',
                    'cityId' => $value['city']?intval($value['city']):'',
                    'trackingKeyId' => intval($value['trackingkeyId']),
                    'pageIdentifier' => $trackingData['pageIdentifier'],
                    'sourceApplication' => $trackingData['siteSource'],
                    'conversionType' => $trackingData['conversionType'],
                    'keyName' => $trackingData['keyName'],
                    'widget' => $trackingData['widget'],
                    'site' => $source,
                    'sessionId' => $value['visitorSessionId']?$value['visitorSessionId']:'sessionIdMissing',
                    'trafficSource' => $value['visitorSessionId'] ? $sessionIdData['source']:'Other',
                    'utmSource' => $value['visitorSessionId'] ? $sessionIdData['utmSource']:'Other',
                    'utmMedium' => $value['visitorSessionId'] ? $sessionIdData['utmMedium']:'Other',
                    'utmCampaign' => $value['visitorSessionId'] ? $sessionIdData['utmCampaign']:'Other',
                    'geocountry' => $value['visitorSessionId'] ? $sessionIdData['geocountry']:'Unknown',
                    'geocity' => $value['visitorSessionId'] ? $sessionIdData['geocity']:'Unknown'
                );

                if($value['userType'] != 'abroad'){
                    $finalDataForRegistration[$index]['stream']         = $userDataAttributes[$value['userId']]['streamIds'];

                    if(is_array($userDataAttributes[$value['userId']]['subStreamIds'])){
                        $finalDataForRegistration[$index]['substream']      = $userDataAttributes[$value['userId']]['subStreamIds'];    
                    }
                    
                    if(is_array($userDataAttributes[$value['userId']]['specializationIds'])){
                        $finalDataForRegistration[$index]['specialization'] = $userDataAttributes[$value['userId']]['specializationIds'];    
                    }
                    
                    if(is_array($userDataAttributes[$value['userId']]['baseCourseIds'])){
                        $finalDataForRegistration[$index]['baseCourse']     = $userDataAttributes[$value['userId']]['baseCourseIds'];    
                    }

                    $finalDataForRegistration[$index]['mode']           = $userDataAttributes[$value['userId']]['mode'];
                }

                $registrationDateUTC = convertDateISTtoUTC($value['usercreationDate']);
                $finalDataForRegistration[$index]['time']                = date("H:i:s",strtotime($registrationDateUTC));
                $finalDataForRegistration[$index]['registrationDateUTC'] = str_replace(' ','T', $registrationDateUTC);
                $index++;
                
                unset($registrationData[$key]);    
            }
            //_p($finalDataForRegistration);die;
            unset($sessionToSourceArray);
            unset($desiredCourseToCourseLevel);
            unset($blogIdToCourseLevel);
            //echo 'Just before Insertion in elastic search';die;
            // To push data to elastic search 
            $params = array();
            $params['body'] = array();
            $i = 1;
            error_log('Total Data :'.count($finalDataForRegistration));

            $this->clientParams = array();
            $this->clientParams['hosts'] = array('10.10.82.12');
            $ESConnFor82 = new Elasticsearch\Client($this->clientParams);

            $actualIndexedUserIds = array();
            foreach($finalDataForRegistration as $result) {
                    $params['body'][] = array('index' => array(
                                            '_index' => $this->MIS_REGISTRATIONS['indexName'],
                                            '_type' =>  $this->MIS_REGISTRATIONS['type'],
                                            '_id' => "registration_".$result['userId']
                                        )
                                    );          
                    $params['body'][] = $result;
                    $actualIndexedUserIds[] = $result['userId'];
                    if ($i % 100 == 0) {
                        $registrationData = $ESConnFor82->bulk($params);
                        $params = array();
                        $params['body'] = array();
                        unset($registrationData);
                    }
                    $i++;
            }
            //_p($params);die;
            if (!empty($params['body'])) {
                $response = $ESConnFor82->bulk($params);
            }
        }
    }

    private function _getSessionInformationFromES($sessionIds){
        $ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
        $ESConnection = $ESConnLib->getShikshaESServerConnection();
        
        $sessionDataMap = array();

        $sessionIdsChunk = array_chunk($sessionIds, 100);

        foreach ($sessionIdsChunk as $key => $chunk) {
            $params = array();
            $params['index'] = SESSION_INDEX_NAME_REALTIME_SEARCH;
            $params['type'] = 'session';
            $params['body']['size'] = 100000;
            $params['body']['_source'] = array('sessionId','source','utm_source','utm_medium','utm_campaign','geocountry','geocity','mmpId','isMobile');
            $params['body']['query']['bool']['filter']['bool']['must']['terms']['sessionId'] = $chunk;
            $result = $ESConnection->search($params);

            $tempSessionMapping = array();
            foreach ($result['hits']['hits'] as $key => $esData) {
                $tempSessionMapping[$esData['_source']['sessionId']]['source']       = $esData['_source']['source'];
                $tempSessionMapping[$esData['_source']['sessionId']]['utm_campaign'] = $esData['_source']['utm_campaign'];
                $tempSessionMapping[$esData['_source']['sessionId']]['utm_source']   = $esData['_source']['utm_source'];
                $tempSessionMapping[$esData['_source']['sessionId']]['utm_medium']   = $esData['_source']['utm_medium'];
                $tempSessionMapping[$esData['_source']['sessionId']]['geocountry']   = $esData['_source']['geocountry'];
                $tempSessionMapping[$esData['_source']['sessionId']]['geocity']      = $esData['_source']['geocity'];
                $tempSessionMapping[$esData['_source']['sessionId']]['isMobile']      = $esData['_source']['isMobile'];
                if(isset($esData['_source']['mmpId'])){
                    $tempSessionMapping[$esData['_source']['sessionId']]['mmpId']      = $esData['_source']['mmpId'];
                }
            }

            foreach ($chunk as $key => $sessionId) {
                if(isset($tempSessionMapping[$sessionId])){
                    $sessionDataMap[$sessionId] = array(
                        'source'      => $tempSessionMapping[$sessionId]['source']?$tempSessionMapping[$sessionId]['source']:'Other',
                        'utmCampaign' => $tempSessionMapping[$sessionId]['utm_campaign']?$tempSessionMapping[$sessionId]['utm_campaign']:'Other',
                        'utmSource'   => $tempSessionMapping[$sessionId]['utm_source']?$tempSessionMapping[$sessionId]['utm_source']:'Other',
                        'utmMedium'   => $tempSessionMapping[$sessionId]['utm_medium']?$tempSessionMapping[$sessionId]['utm_medium']:'Other',
                        'geocountry'  => $tempSessionMapping[$sessionId]['geocountry']?$tempSessionMapping[$sessionId]['geocountry']:'Unknown',
                        'geocity'     => $tempSessionMapping[$sessionId]['geocity']?$tempSessionMapping[$sessionId]['geocity']:'Unknown',
                        'isMobile'     => $tempSessionMapping[$sessionId]['isMobile']?$tempSessionMapping[$sessionId]['isMobile']:'Unknown',
                    );
                    if(isset($tempSessionMapping[$sessionId]['mmpId'])){
                        $sessionDataMap[$sessionId]['mmpId']      = $tempSessionMapping[$sessionId]['mmpId'];
                    }

                }else{
                    $sessionDataMap[$sessionId] = array(
                                                'source'      => 'Other',
                                                'utmCampaign' =>'Other',
                                                'utmSource'   => 'Other',
                                                'utmMedium'   => 'Other',
                                                'geocountry'  => 'Unknown',
                                                'geocity'     => 'Unknown',
                                                'isMobile'     => 'Unknown'
                                                );
                }
            }
        }
        return $sessionDataMap;
    }

    function _filterNullValues($userDataAttributes){
        //_p($userDataAttributes);die;
        foreach ($userDataAttributes as $userId => $userData) {
                if(is_array($userData['subStreamIds'])){
                    foreach ($userData['subStreamIds'] as $key => $value) {
                        if($value == ""){
                            unset($userDataAttributes[$userId]['subStreamIds'][$key]);
                        }else{
                            $userDataAttributes[$userId]['subStreamIds'][$key] = intval($value);
                        }
                    }
                    $userDataAttributes[$userId]['subStreamIds'] = array_values($userDataAttributes[$userId]['subStreamIds']);
                }

                if(is_array($userData['specializationIds'])){
                    foreach ($userData['specializationIds'] as $key => $value) {
                        if($value == ""){
                            unset($userDataAttributes[$userId]['specializationIds'][$key]);
                        }else{
                            $userDataAttributes[$userId]['specializationIds'][$key] = intval($value);
                        }
                    }
                    $userDataAttributes[$userId]['specializationIds'] = array_values($userDataAttributes[$userId]['specializationIds']);
                }

                if(is_array($userData['baseCourseIds'])){
                    foreach ($userData['baseCourseIds'] as $key => $value) {
                        if($value == ""){
                            unset($userDataAttributes[$userId]['baseCourseIds'][$key]);
                        }else{
                            $userDataAttributes[$userId]['baseCourseIds'][$key] = intval($value);
                        }
                    }
                    $userDataAttributes[$userId]['baseCourseIds'] = array_values($userDataAttributes[$userId]['baseCourseIds']);

                }
        }
        return $userDataAttributes;
    }

    private function _getDateRangeToFetchData(){
        $params = array();
        $params = $this->_getElasticIndexForRegistraion();
        $params['type'] = $this->MIS_REGISTRATIONS['type'];
        $params['body']['size'] = 1;
        $params['body']['_source'] = array('registrationDate');
        $params['body']['sort']['registrationDate']['order'] = 'desc';
        //_p($params);die;
        $search = $this->ESClientConn6->search($params);

        if($search['hits']['total'] >0){
        $dateRange['startDate'] = str_replace('T',' ',$search['hits']['hits'][0]['_source']['registrationDate']);
        }else{
            $dateRange['startDate'] = date("Y-m-d H:i:s",strtotime("-30 mins"));
        }
        $dateRange['endDate'] = date("Y-m-d H:i:s",strtotime(date()."-5 minute"));
        return $dateRange;
    }

    private function _getRegistrationIdsForElasticsearch($date){
        $date = str_replace(' ','T',$date);
        $params = array();
        $params = $this->_getElasticIndexForRegistraion();
        $params['body']['size'] = 0;
        $params['body']['query']['bool']['filter']['bool']['must']['term']['registrationDate'] = $date;
        $search = $this->ESClientConn6->search($params);
        //_p(json_encode($params));die;
        $totalCount = $search['hits']['total'];
        if($totalCount > 0){
            $params = array();
            $params = $this->_getElasticIndexForRegistraion();
            $params['body']['size'] = $totalCount;
            $params['body']['_source'] = array('registrationId');
            $params['body']['query']['bool']['filter']['bool']['must']['term']['registrationDate'] = $date;
            $search = $this->ESClientConn6->search($params);
            //_p(json_encode($params));die;
            $search = $search['hits']['hits'];
            foreach ($search as $key => $value) {
                $registrationIds[] = $value['_source']['registrationId'];
            }
        }else{
            $registrationIds =array();
        }
        return $registrationIds;
    }

    function populateRegistrationDataToElasticSearch6(){
        $this->validateCron();
        ini_set('memory_limit', '2096M');
        set_time_limit(0);
        $dateRange = $this->_getDateRangeToFetchData();

        //error_log("REG MIS :: Date Range : ".print_r($dateRange,true)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        $registrationIds = array();
        $registrationIds = $this->_getRegistrationIdsForElasticsearch($dateRange['startDate']);
        //error_log("REG MIS :: already in ES : ".count($registrationIds)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        $registrationData = $this->trackingModel->getRegistrationdataFromDB($dateRange);
        //_p($registrationData);die;
        //error_log("REG MIS :: New Data : ".count($registrationData)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));

        if(count($registrationData)>0){
            $trackingArray = $this->_getTrackingData();
        
            $userIds = array();
            $blogIds =array();
            $desiredCourseIds =array();
            $nationalUserIds = array();
            $abroadUserIds = array();
            $testPrepUserIds = array();
            foreach ($registrationData as $key => $value) {
                if(in_array($value['id'],$registrationIds)){
                    unset($registrationData[$key]);
                    continue;
                }

                // get vistorSessionIds
                if($value['visitorSessionId']){
                    $sessionIds[$value['visitorSessionId']] = 0; 
                }
                
                // in case of abroad we have to fetch courseLevel based on desired course. in future may be also user in domestic
                // courseLevel only for abroad
                switch ($value['userType']) {
                    case 'national':
                        $nationalUserIds[$value['userId']] = strtotime($value['usercreationDate']);
                        break;

                    case 'abroad':
                        if($value['desiredCourse']){
                            $desiredCourseIds[$value['desiredCourse']] =0 ;
                        }
                        $abroadUserIds[] = $value['userId'];
                        break;

                    case 'testPrep':
                        $testPrepUserIds[] = $value['userId'];
                        break;                        
                }        
            }

            if(count($testPrepUserIds) >0 ){
                mail('praveen.singhal@99acres.com','Test Pref User Data Found on '.date('Y-m-d H:i:s'), 'User Ids: '.'<br/>'.print_r($testPrepUserIds, true));
            }

            if(count($abroadUserIds) > 0){
                $userExamMapping = $this->MISCronsLib->getUserWithExamMapping($abroadUserIds);
            }

            $userDataAttributes = array();
            if(count($nationalUserIds) > 0){
                $userDataAttributes = $this->MISCronsLib->getUserDataAttributes($nationalUserIds);
                $userDataAttributes = $this->_filterNullValues($userDataAttributes);
            }

            //error_log("REG MIS :: User Ids : ".print_r(array_keys($nationalUserIds),true)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
            //error_log("REG MIS :: User Ids : ".count(array_keys($nationalUserIds))."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));

            // Get trafficSource and utm data for session ids
            if(count($sessionIds) >0){
                $uniqueSessionIds = array_keys($sessionIds);
                unset($sessionIds);
                $sessionToSourceArray = $this->_getSessionInformationFromES($uniqueSessionIds);
                unset($uniqueSessionIds);
            }

            // get courseLevel for abroad
            $result = $this->_getCourseLevelForDesiredCourse($desiredCourseIds);
            $desiredCourseToCourseLevel = $result['desiredCourseToCourseLevel'];
            $blogIdToCourseLevel = $result['blogIdToCourseLevel'];
            unset($result);
            $finalDataForRegistration = array();
            $index =0;
            //error_log("REG MIS :: Registration Data : ".count($registrationData)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
            foreach($registrationData as $key => $value) {
                $trackingData = $trackingArray[$value['trackingkeyId']];
                if($value['visitorSessionId']){
                    $sessionIdData = $sessionToSourceArray[$value['visitorSessionId']];
                }
                $courseLevel = '';
                if($value['userType'] == 'abroad'){
                    $courseLevel = $desiredCourseToCourseLevel[$value['desiredCourse']];
                }

                $siteArray = array('Study Abroad','Test Prep');
                if($trackingData['site'] && (!in_array($trackingData['site'],$siteArray))){
                    $source = "Domestic";
                }else{
                    $source = $trackingData['site'];
                }
                $finalDataForRegistration[$index] = array(
                    'registrationId' => intval($value['id']),
                    'userId' => intval($value['userId']),
                    'source' => $value['source'],
                    'userType' => $value['userType'],
                    'isNewReg' => $value['isNewReg'],
                    'registrationDate' => str_replace(' ','T', $value['usercreationDate']),
                    'countryId' => $value['country']?intval($value['country']):'',
                    'cityId' => $value['city']?intval($value['city']):'',
                    'trackingKeyId' => intval($value['trackingkeyId']),
                    'pageIdentifier' => $trackingData['pageIdentifier'],
                    'sourceApplication' => $trackingData['siteSource'],
                    'conversionType' => $trackingData['conversionType'],
                    'keyName' => $trackingData['keyName'],
                    'widget' => $trackingData['widget'],
                    'site' => $source,
                    'sessionId' => $value['visitorSessionId']?$value['visitorSessionId']:'sessionIdMissing',
                    'trafficSource' => $value['visitorSessionId'] ? $sessionIdData['source']:'Other',
                    'utmSource' => $value['visitorSessionId'] ? $sessionIdData['utmSource']:'Other',
                    'utmMedium' => $value['visitorSessionId'] ? $sessionIdData['utmMedium']:'Other',
                    'utmCampaign' => $value['visitorSessionId'] ? $sessionIdData['utmCampaign']:'Other',
                    'geocountry' => $value['visitorSessionId'] ? $sessionIdData['geocountry']:'Unknown',
                    'geocity' => $value['visitorSessionId'] ? $sessionIdData['geocity']:'Unknown'
                    );

                if($trackingData['site'] == "Domestic"){
                    $finalDataForRegistration[$index]['sourceApplicationType'] = $trackingData['siteSourceType'];
                    $finalDataForRegistration[$index]['pageGroup'] = $trackingData['pageGroup'];
                }

                if(isset($sessionIdData['mmpId'])){
                    $finalDataForRegistration[$index]['mmpId'] = $sessionIdData['mmpId'];
                }

                if(isset($sessionIdData['isMobile']) && $sessionIdData['isMobile'] == "androidApp"){
                    $finalDataForRegistration[$index]['sourceApplication'] = $sessionIdData['isMobile'];
                }

                if($value['userType'] == 'abroad'){
                    $finalDataForRegistration[$index]['courseLevel'] = $courseLevel;
                    $finalDataForRegistration[$index]['categoryId'] = $value['categoryId']?intval($value['categoryId']):'';
                    $finalDataForRegistration[$index]['subCategoryId'] = $value['subCatId']?intval($value['subCatId']):'';
                    $finalDataForRegistration[$index]['desiredCourse'] = $value['desiredCourse']?intval($value['desiredCourse']):'';

                    $finalDataForRegistration[$index]['prefCountry1'] = $value['prefCountry1']?intval($value['prefCountry1']):'';
                    $finalDataForRegistration[$index]['prefCountry2'] = $value['prefCountry2']?intval($value['prefCountry2']):'';
                    $finalDataForRegistration[$index]['prefCountry3'] = $value['prefCountry3']?intval($value['prefCountry3']):'';
                }else if($value['userType'] == 'national'){
                    $finalDataForRegistration[$index]['stream']         = $userDataAttributes[$value['userId']]['streamIds'];

                    if(is_array($userDataAttributes[$value['userId']]['subStreamIds'])){
                        $finalDataForRegistration[$index]['substream']      = $userDataAttributes[$value['userId']]['subStreamIds'];    
                    }
                    
                    if(is_array($userDataAttributes[$value['userId']]['specializationIds'])){
                        $finalDataForRegistration[$index]['specialization'] = $userDataAttributes[$value['userId']]['specializationIds'];    
                    }
                    
                    $finalDataForRegistration[$index]['baseCourse']     = $userDataAttributes[$value['userId']]['baseCourseIds'];
                    $finalDataForRegistration[$index]['mode']           = $userDataAttributes[$value['userId']]['mode'];
                }

                $registrationDateUTC = convertDateISTtoUTC($value['usercreationDate']);
                $finalDataForRegistration[$index]['time']                = date("H:i:s",strtotime($registrationDateUTC));
                $finalDataForRegistration[$index]['registrationDateUTC'] = str_replace(' ','T', $registrationDateUTC);

                if(in_array($value['userId'], $abroadUserIds)){
                    $finalDataForRegistration[$index]['exam'] = $userExamMapping[$value['userId']];
                }
                $index++;
                
                unset($registrationData[$key]);    
            }
            //_p($finalDataForRegistration);die;
            unset($sessionToSourceArray);
            unset($desiredCourseToCourseLevel);
            unset($blogIdToCourseLevel);
            //echo 'Just before Insertion in elastic search';die;
            // To push data to elastic search 
            $params = array();
            $params['body'] = array();
            $i = 1;
            echo 'Total Data :'.count($finalDataForRegistration).'</br>';
            //$actualIndexedUserIds = array();
            foreach($finalDataForRegistration as $result) {
                    $params['body'][] = array('index' => array(
                                            '_index' => $this->MIS_REGISTRATIONS['indexName'],
                                            '_type' =>  $this->MIS_REGISTRATIONS['type'],
                                            '_id' => "registration_".$result['userId']
                                        )
                                    );          
                    $params['body'][] = $result;
                    //$actualIndexedUserIds[] = $result['userId'];
                    if ($i % 1000 == 0) {
                        $registrationData = $this->ESClientConn6->bulk($params);
                        $params = array();
                        $params['body'] = array();
                        unset($registrationData);
                    }
                    $i++;
            }
            if (!empty($params['body'])) {
                $response = $this->ESClientConn6->bulk($params);
            }

            //error_log("REG MIS :: Indexed User Ids : ".print_r($actualIndexedUserIds,true)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
            //error_log("REG MIS :: Indexed User Ids count : ".count($actualIndexedUserIds)."\n", 3, "/tmp/regIndexing.log_".date('Y-m-d'));
        }
    }
}
	
?>	
