<?php

exit();
require_once(FCPATH.'vendor/autoload.php');

class ESNational extends MX_Controller
{
    private $dbHandle;
    
    function __construct() 
    {
        parent::__construct();
        $this->dbLibObj = DbLibCommon::getInstance('User');
        $this->dbHandle = $this->_loadDatabaseHandle();
    }
    
    private function getCategoryMapping()
    {
        $sql = "select * from base_entity_mapping";
        $query = $this->dbHandle->query($sql);
        $results = $query->result_array();
        
        $categoryMapping = array();
        foreach($results as $result) {
            
            $key = intval($result['oldcategory_id']).":".intval($result['oldsubcategory_id']).":".intval($result['oldspecializationid']);
            $categoryMapping[$key] = array(
                'stream_id' => $result['stream_id'],
                'substream_id' => $result['substream_id'],
                'specialization_id' => $result['specialization_id'],
                'base_course_id' => $result['base_course_id'],
                'education_type' => $result['education_type'],
                'delivery_method' => $result['delivery_method'],
                'credential' => $result['credential'],
                'level' => $result['level']
            );
        }
        return $categoryMapping;
    }
    
    private function getSubCatMapping()
    {
        $sql = "select boardId, parentId from categoryBoardTable where parentId > 1 and flag != 'studyabroad'";
        $query = $this->dbHandle->query($sql);
        $results = $query->result_array();
        
        $subCatMapping = array();
        foreach($results as $result) {
            $subCatMapping[$result['boardId']] = $result['parentId'];
        }
        return $subCatMapping;
    }
    
    private function getLDBCourseMapping()
    {
        $sql = "select ldbCourseID, categoryID, parentId from LDBCoursesToSubcategoryMapping a, categoryBoardTable b where a.categoryID = b.boardId and b.flag != 'studyabroad'";
        $query = $this->dbHandle->query($sql);
        $results = $query->result_array();
        
        $LDBCourseMapping = array();
        foreach($results as $result) {
            $LDBCourseMapping[$result['ldbCourseID']] = array($result['categoryID'], $result['parentId']);
        }
        return $LDBCourseMapping;
    }
    
	function scanpv($startDate, $endDate)
	{
		$logFile = "/home/vikasa/esrun/logs/log.".$startDate;
	
        $categoryMapping = $this->getCategoryMapping();
        $subCatMapping = $this->getSubCatMapping();
        $LDBCourseMapping = $this->getLDBCourseMapping();
        
        
        //$startDate = '2016-05-08';
        //$endDate = '2016-06-01';
        
		ini_set('memory_limit', '4096M');
		set_time_limit(0);

		$clientParams = array();
		$clientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$client = new Elasticsearch\Client($clientParams);

		$localClientParams = array();
		$localClientParams['hosts'] = array(ELASTIC_SEARCH_HOST);
		$localClient = new Elasticsearch\Client($localClientParams);

        $start = microtime(true);

		/**
         * Process one day at a time
         */ 
        $currentDate = $startDate;
        while($currentDate < $endDate) {
            
            $nextDate = date('Y-m-d', strtotime("+1 day", strtotime($currentDate)));
			error_log("Processing ".$currentDate." to ".$nextDate."\n", 3, $logFile);
            
            $batchSize = 3000;
            $offset = 0;
    
            $indexParams = array();
            $indexParams['body'] = array();
            
            while(true) {
				error_log("Processing offset ".$offset."\n", 3, $logFile);
                $params = array();
                $params['index'] = 'trafficdata_pageviews_2';
                $params['type'] = 'pageview';
        
                $startDateFilter = array();
                $endDateFilter = array();
                $abroadFilter = array();
        
                $startDateFilter['range']['visitTime']['gte'] = $currentDate;
                $endDateFilter['range']['visitTime']['lt'] = $nextDate;
                
                $abroadFilter['match']['isStudyAbroad'] = 'yes';

				$params['body']['query']['filtered']['filter']['bool']['must'][] = $startDateFilter;
                $params['body']['query']['filtered']['filter']['bool']['must'][] = $endDateFilter;
                
                $params['body']['query']['filtered']['filter']['bool']['must_not'][] = $abroadFilter;
        
                $params['body']['sort']['visitTime']['order'] = 'asc';
        
                $params["size"] = $batchSize;
                $params["from"] = $offset * $batchSize;
                $search = $client->search($params);
                
                if(count($search['hits']['hits']) > 0) {
                    foreach($search['hits']['hits'] as $result) {
                        $indexParams['body'][] = array('index' => array(
                                    '_index' => 'shiksha_trafficdata_pageviews',
                                    '_type' => 'pageview',
                                    )
                            );
                        
                        $doc = $result['_source'];

                        if(array_key_exists('categoryId', $doc)) {
                            $docCategoryId = intval($doc['categoryId']);
                            $docSubCategoryId = intval($doc['subCategoryId']);
                            $docLDBCourseId = intval($doc['LDBCourseId']);
                        }
                        else if(array_key_exists('subCategoryId', $doc)) {
                            $docSubCategoryId = intval($doc['subCategoryId']);
                            $docLDBCourseId = intval($doc['LDBCourseId']);
                            $docCategoryId = $subCatMapping[$docSubCategoryId];
                        }
                        else if(array_key_exists('LDBCourseId', $doc)) {
                            $docLDBCourseId = intval($doc['LDBCourseId']);
                            $docSubCategoryId = $LDBCourseMapping[$docLDBCourseId][0];
                            $docCategoryId = $LDBCourseMapping[$docLDBCourseId][1];
                        }
                        
                        $newMapping = array();
                        
                        $key1 = $docCategoryId.":".$docSubCategoryId.":".$docLDBCourseId;
                        $key2 = $docCategoryId.":".$docSubCategoryId.":0";
                        $key3 = $docCategoryId.":0:0";
                        
                        foreach(array($key1, $key2, $key3) as $key) {
                            if(array_key_exists($key, $categoryMapping)) {
                                $newMapping = $categoryMapping[$key];
                                break;
                            }    
                        }
                        
                        if(count($newMapping) > 0) {
                            $doc['streamMapped'] = 'yes';
                        }
                        else {
                            $doc['streamMapped'] = 'no';
                        }
                        
                        if($newMapping['stream_id']) {
                            $doc['hierarchy']['streamId'] = $newMapping['stream_id'];
                        }
                        if($newMapping['substream_id']) {
                            $doc['hierarchy']['substreamId'] = $newMapping['substream_id'];
                        }
                        if($newMapping['specialization_id']) {
                            $doc['hierarchy']['specializationId'] = $newMapping['specialization_id'];
                        }
                        if($newMapping['base_course_id']) {
                            $doc['baseCourseId'] = $newMapping['base_course_id'];
                        }
                        if($newMapping['education_type']) {
                            $doc['educationType'] = $newMapping['education_type'];
                        }
                        if($newMapping['delivery_method']) {
                            $doc['deliveryMethod'] = $newMapping['delivery_method'];
                        }
                        if($newMapping['credential']) {
                            $doc['credential'] = $newMapping['credential'];
                        }
                        if($newMapping['level']) {
                            $doc['level'] = $newMapping['level'];
                        }
                        
                        $indexParams['body'][] = $doc;
                    }
        
                    $indexResponse = $localClient->bulk($indexParams);
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                }
                else {
                    break;
                }
                error_log("Processed offset ".$offset."\n", 3, $logFile);
                $offset++;
                //exit();
			}
			error_log("Processed ".$currentDate." to ".$nextDate."\n", 3, $logFile);
            $currentDate = $nextDate;
        }
        
        $end = microtime(true);
        error_log("\n\n".($end-$start)."\n", 3, $logFile);
	}
}	
