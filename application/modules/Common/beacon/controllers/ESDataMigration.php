<?php 

require_once(FCPATH.'vendor/autoload.php');

class ESDataMigration extends MX_Controller {

    function getESConnection($host){
		$clientParams = array();
		$clientParams['hosts'] = array($host);
        $clientParams['guzzleOptions']['curl.options'][CURLOPT_CONNECTTIMEOUT] = 60;
        $clientParams['guzzleOptions']['curl.options'][CURLOPT_TIMEOUT] = 60;
		$ESConnection = new Elasticsearch\Client($clientParams);
		return $ESConnection;
	}

	function convertDateToUTC($date= "2018-11-27 20:15:41"){
        $dataToLog  = "************************START*********************************\n";
                $dataToLog .= "QUERY TIME : ".date("Y-m-d H:i:s")."\n";_p($dataToLog);die;
        //_p(convertDateISTtoUTC("2019-01-04 12:40:00")); // 2019-01-04 06:19:38
        _p(convertDateUTCtoIST("2019-01-10 06:46:00")); // 2019-01-04 06:19:38
        _p(convertDateUTCtoIST("2019-01-04 06:19:00"));die;  // 2019-01-04 06:19:04

        


        _p(convertDateISTtoUTC("2019-01-04 12:40:00")); // 2019-01-04 06:19:38
        _p(convertDateISTtoUTC("2019-01-04 11:49:04"));die;  // 2019-01-04 06:19:04
        //convertDateUTCtoIST
        /*$clientParams = array();
        $clientParams['hosts'] = array("172.16.3.111");
        $clientParams['guzzleOptions']['curl.options'][CURLOPT_CONNECTTIMEOUT] = 60;
        $clientParams['guzzleOptions']['curl.options'][CURLOPT_TIMEOUT] = 60;
        $this->elasticClient = new Elasticsearch\Client($clientParams);
        $info = $this->elasticClient->info();
        _p($info['version']['number']);die;*/
		$given = new DateTime($date);
		$given->setTimezone(new DateTimeZone("Asia/Kolkata"));
        //_p($given->format("Y-m-d H:i:s"));die;
		return $given->format("Y-m-d H:i:s");
	}
    //beacon/ESDataMigration/migrateSessionIndex

	public function migrateSessionIndex($date = array()){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.12";
    	//$host1 = "10.10.82.14";
        $host1 = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        
        $dataChunk = 1000;
        $scroll_time = "5m";
        $date = array('startDate' => "2018-11-13 13:15:50", "endDate" => "2018-11-20 15:40:00");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"],
            "query" => array(
                "filtered"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "startTime" => array(
                                            "gte"=> "2019-01-04T11:49:00",
                                            "lte"=> "2019-01-04T12:40:00"
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            ),
            "sort" => array(
                "startTime" => "desc"
            )
        );
        /*$params['body'] = array(
        	"size"  => $dataChunk
		);*/
		//_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        _p("ddfdf");die;
        $scroll_id = $response['_scroll_id'];
        $i=1;
        while ($i) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            	if(count($response['hits']['hits']) > 0) {
                    $i=0;
        			foreach($response['hits']['hits'] as $row) {
            			$indexParams['body'][] = array('index' => array(
                    			'_index' => $index."_realtime",
                    			'_type' => $type,
                                '_id' => $row['_id']
                    			)
                		);

            			$startTime = $row['_source']['startTime'];
                    	$startTime = str_replace("T", " ", $startTime);
                    	$startTime = $this->convertDateToUTC($startTime);
                    	$row['_source']['time'] = date("H:i:s",strtotime($startTime));
                    	$startTime = str_replace(" ", "T", $startTime);
                    	$row['_source']['startTime'] = $startTime;

                        if(isset($row['_source']['landingPageDoc'])){
                            $startTime = $row['_source']['landingPageDoc']['visitTime'];
                            $startTime = str_replace("T", " ", $startTime);
                            $startTime = $this->convertDateToUTC($startTime);
                            $startTime = str_replace(" ", "T", $startTime);
                            $row['_source']['landingPageDoc']['visitTime'] = $startTime;
                        }

                        if(isset($row['_source']['exitPage'])){
                            $startTime = $row['_source']['exitPage']['visitTime'];
                            $startTime = str_replace("T", " ", $startTime);
                            $startTime = $this->convertDateToUTC($startTime);
                            $row['_source']['time'] = date("H:i:s",strtotime($startTime));
                            $startTime = str_replace(" ", "T", $startTime);
                            $row['_source']['exitPage']['visitTime'] = $startTime;
                        }
	            		$indexParams['body'][] = $row['_source'];
        			}
	        		/*_p($indexParams);
                    _p($ESConnection1);
                    break;*/
        			$indexResponse = $ESConnection1->bulk($indexParams);
        			//_p($indexResponse);die;
        			$indexParams = array();
        			$indexParams['body'] = array();
        			unset($indexResponse);
        			error_log("Batch Indexed");
                    break;
	    		}
	    		else {
					break;
	   			}

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migratePageviewIndex($date = array()){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.12";
    	$host1 = "10.10.82.14";
        //$host1 = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_pageviews";
        $type = "pageview";
        
        $dataChunk = 5000;
        $scroll_time = "5m";
        $date = array('startDate' => "2018-11-16 00:00:00", "endDate" => "2018-11-20 15:40:00");
        //$date = array('startDate' => "2018-11-16 00:00:00");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        //$params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
        	"size"  => $dataChunk
		);

        $params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"],
            "query" => array(
                "filtered"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "visitTime" => array(
                                            "gte"=> "2019-01-04T11:49:00",
                                            "lt"=> "2019-01-04T12:40:00"
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );

		//_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        $i=1;
        while ($i) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

            	if(count($response['hits']['hits']) > 0) {
                    $i=0;
        			foreach($response['hits']['hits'] as $row) {
            			$indexParams['body'][] = array('index' => array(
                    			'_index' => "shiksha_trafficdata_pageviews",
                    			'_type' => $type,
                                '_id' => $row['_id']
                    			)
                		);

	            		$indexParams['body'][] = $row['_source'];
        			}
	        		//_p($indexParams);die;
                    $t1 = time();
        			$indexResponse = $ESConnection1->bulk($indexParams);
                    $t2 = time();
                    error_log("DDDDD1 Indexing time : ".($t2-$t1));
        			_p($indexResponse);die;
        			$indexParams = array();
        			$indexParams['body'] = array();
        			unset($indexResponse);
        			error_log("DDDDD Batch Indexed");
	    		}
	    		else {
					break;
	   			}

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migratePageviewIndex1($date = array()){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.14";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        //$ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_pageviews_1";
        $type = "pageview";
        
        $dataChunk = 4000;
        $scroll_time = "5m";
        $date = array('startDate' => "2018-11-16 00:00:00", "endDate" => "2018-11-20 15:40:00");
        //$date = array('startDate' => "2018-11-16 00:00:00");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        //$params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk
        );
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => "shiksha_trafficdata_pageviews_3",
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );

                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($indexParams);die;
                    $t1 = time();
                    $indexResponse = $ESConnection->bulk($indexParams);
                    $t2 = time();
                    error_log("DDDDD3 Indexing time 3: ".($t2-$t1));
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("DDDDD Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateLDBResponseIndex($date = array()){  // 6:30pm  : total time 5 mins
        echo "Start Time : ".date("Y-m-d H:i:s")."  ===========<br>";
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.12";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "ldb_response";
        $type = "response";
        
        $dataChunk = 3000;
        $scroll_time = "5m";
        //$date = array('startDate' => "2018-03-01 00:00:00", "endDate" => "2018-10-31 23:59:59");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        /*$params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"],
            "query" => array(
                "filtered"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                               
                                array(
                                    "term" => array(
                                        "temp_LMS_id" => 25282646
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );*/
        $params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"]
        );
        
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => $index,
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );

                        $row['_source']['is_test_user']        = ($row['_source']['is_test_user'] == 1)?true:false;
                        $row['_source']['is_ndnc']             = ($row['_source']['is_ndnc'] == 1)?true:false;
                        $row['_source']['is_ldb_user']         = ($row['_source']['is_ldb_user'] == 1)?true:false;
                        $row['_source']['is_response_paid']    = ($row['_source']['is_response_paid'] === "false")?false:true;
                        $row['_source']['unsubscribe']         = ($row['_source']['unsubscribe'] == 1)?true:false;
                        $row['_source']['softbounce']          = ($row['_source']['softbounce'] == 1)?true:false;
                        $row['_source']['hardbounce']          = ($row['_source']['hardbounce'] == 1)?true:false;
                        $row['_source']['ownershipchallenged'] = ($row['_source']['ownershipchallenged'] == 1)?true:false;
                        $row['_source']['abused']              = ($row['_source']['abused'] == 1)?true:false;
                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($indexParams);die;
                    $indexResponse = $ESConnection1->bulk($indexParams);
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
        echo "End Time : ".date("Y-m-d H:i:s")."  ===========<br>";
    }

	public function updateSessionIndex(){

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.14";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        
        $dataChunk = 1000;
        $scroll_time = "5m";
        $date = array('startDate' => "2018-09-01 00:00:00", "endDate" => "2018-10-31 23:59:59");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("referralURL"),
            "query" => array(
                "bool"=> array(
                    "must"=> array(
                        "wildcard" => array(
                            "referralURL" => "*google.co*"
                        )
                    )
                )
            )
        );
        /*$params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"]
        );*/
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //_p($response);die;
        $scroll_id = $response['_scroll_id'];
        $count = 0;
        $indexParams = array();
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;
                        
                        $referralURL = $row["_source"]["referralURL"];
                        $referralURL = urldecode($referralURL);
                        $parts = parse_url($referralURL);
                        parse_str($parts['query'], $searchQuery);
                        $searchQueryTerams = '';

                        $row["_source"] = array();

                        $seo_search_engine = '';
                        $seo_search_query = '';

                        if(!empty($searchQuery["q"])){
                            $seo_search_query = strtolower($searchQuery[q]);
                            if(!(strpos($referralURL, "google.co") === false)){
                                $seo_search_engine = 'google';
                            }else if(!(strpos($referralURL, "bing.com") === false)){
                                $seo_search_engine = 'bing';
                            }else if(!(strpos($referralURL, "ask.com/web") === false)){
                                $seo_search_engine = 'ask';
                            }else if(!(strpos($referralURL, "aol.com") === false)){
                                $seo_search_engine = 'aol';
                            }else if(!(strpos($referralURL, "duckduckgo.com") === false)){
                                $seo_search_engine = 'duckduckgo';
                            }
                        }else if(!empty($searchQuery["p"])){
                            $seo_search_query = strtolower($searchQuery[q]);
                            if(!(strpos($referralURL, "yahoo.com") === false)){
                                $seo_search_engine = 'yahoo';
                            }
                        }

                        if(!empty($seo_search_engine)){
                            $indexParams['body'][] = array('update' => array(
                                '_id' => $row['_id']
                                )
                            );
                    
                            $indexParams['body'][] = array(
                                'doc_as_upsert' => true,
                                'doc' => array("seo_search_query" => $seo_search_query, "seo_search_engine" => $seo_search_engine)
                            );
                        }
                    }
                    //_p($indexParams);die;
                    if(count($indexParams)>0){
                        $indexResponse = $ESConnection1->bulk($indexParams);    
                    }
                    
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function updatePageIdentifierData(){

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.14";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_pageviews";
        $type = "pageview";
        
        $dataChunk = 1000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("pageIdentifier"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool" => array(
                            "must" => array(
                                "terms" => array(
                                    "pageIdentifier" => array("ampCourseDetailPage","ampInstituteListingPage","ampUniversityListingPage","registrationAMPPage","responseExamAMPPage","responseAMPPage","loginAMPPage")
                                )
                            )
                        )
                    )
                )
            )
        );
        /*$params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"]
        );*/
        _p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //_p($response);die;
        $scroll_id = $response['_scroll_id'];
        $count = 0;
        $indexParams = array();
        while (true) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        //_p($row['_source']['pageIdentifier']);die;
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;
                        $pageIdentifier = '';
                        if($row['_source']['pageIdentifier'] == "ampCourseDetailPage"){
                            $pageIdentifier = 'courseDetailsPage';
                        }else if($row['_source']['pageIdentifier'] == "ampInstituteListingPage"){
                            $pageIdentifier = 'instituteListingPage';
                        }else if($row['_source']['pageIdentifier'] == "ampUniversityListingPage"){
                            $pageIdentifier = 'universityListingPage';
                        }else if($row['_source']['pageIdentifier'] == "registrationAMPPage"){
                            $pageIdentifier = 'registrationPage';
                        }else if($row['_source']['pageIdentifier'] == "responseExamAMPPage"){
                            $pageIdentifier = 'responseExamPage';
                        }else if($row['_source']['pageIdentifier'] == "responseAMPPage"){
                            $pageIdentifier = 'responsePage';
                        }else if($row['_source']['pageIdentifier'] == "loginAMPPage"){
                            $pageIdentifier = 'loginPage';
                        }
                      
                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                
                        $indexParams['body'][] = array(
                            'doc_as_upsert' => true,
                            'doc' => array("pageIdentifier" => $pageIdentifier, "isAmpPage" => true)
                        );
                    }
                    //_p($indexParams);die;
                    if(count($indexParams)>0){
                        $indexResponse = $ESConnection1->bulk($indexParams);    
                    }
                    
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("Batch Indexed");
                }
                else {
                    break;
                }

                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function updateLandingPageData(){

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.14";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        
        $dataChunk = 2000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("landingPageDoc.pageIdentifier"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool" => array(
                            "must" => array(
                                "terms" => array(
                                    "landingPageDoc.pageIdentifier" => array("ampCourseDetailPage","ampInstituteListingPage","ampUniversityListingPage","registrationAMPPage","responseExamAMPPage","responseAMPPage","loginAMPPage")
                                )
                            )
                        )
                    )
                )
            )
        );
        /*$params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"]
        );*/
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //_p($response);die;
        $scroll_id = $response['_scroll_id'];
        $count = 0;
        $indexParams = array();
        while (true) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        //_p($row['_source']['landingPageDoc']['pageIdentifier']);die;
                        $oldPageIdentifier = $row['_source']['landingPageDoc']['pageIdentifier'];

                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;
                        $pageIdentifier = '';
                        if($oldPageIdentifier == "ampCourseDetailPage"){
                            $pageIdentifier = 'courseDetailsPage';
                        }else if($oldPageIdentifier == "ampInstituteListingPage"){
                            $pageIdentifier = 'instituteListingPage';
                        }else if($oldPageIdentifier == "ampUniversityListingPage"){
                            $pageIdentifier = 'universityListingPage';
                        }else if($oldPageIdentifier == "registrationAMPPage"){
                            $pageIdentifier = 'registrationPage';
                        }else if($oldPageIdentifier == "responseExamAMPPage"){
                            $pageIdentifier = 'responseExamPage';
                        }else if($oldPageIdentifier == "responseAMPPage"){
                            $pageIdentifier = 'responsePage';
                        }else if($oldPageIdentifier == "loginAMPPage"){
                            $pageIdentifier = 'loginPage';
                        }
                        
                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                
                        $indexParams['body'][] = array(
                            'doc_as_upsert' => true,
                            'doc' => array(
                                "landingPageDoc" => array(
                                    "pageIdentifier"=>$pageIdentifier,
                                    "isAmpPage" => true
                                )
                            )
                        );
                    }
                    //_p($indexParams);die;
                    if(count($indexParams)>0){
                        $indexResponse = $ESConnection1->bulk($indexParams);    
                    }
                    
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("Batch Indexed");
                }
                else {
                    break;
                }

                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
                $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function updateExitPageData(){

        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.14";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        
        $dataChunk = 1000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("exitPage.pageIdentifier"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool" => array(
                            "must" => array(
                                "terms" => array(
                                    "exitPage.pageIdentifier" => array("ampCourseDetailPage","ampInstituteListingPage","ampUniversityListingPage","registrationAMPPage","responseExamAMPPage","responseAMPPage","loginAMPPage")
                                )
                            )
                        )
                    )
                )
            )
        );
        /*$params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"]
        );*/
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //_p($response);die;
        $scroll_id = $response['_scroll_id'];
        $count = 0;
        $indexParams = array();
        while (true) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        //_p($row['_source']['exitPage']['pageIdentifier']);die;
                        $oldPageIdentifier = $row['_source']['exitPage']['pageIdentifier'];

                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;
                        $pageIdentifier = '';
                        if($oldPageIdentifier == "ampCourseDetailPage"){
                            $pageIdentifier = 'courseDetailsPage';
                        }else if($oldPageIdentifier == "ampInstituteListingPage"){
                            $pageIdentifier = 'instituteListingPage';
                        }else if($oldPageIdentifier == "ampUniversityListingPage"){
                            $pageIdentifier = 'universityListingPage';
                        }else if($oldPageIdentifier == "registrationAMPPage"){
                            $pageIdentifier = 'registrationPage';
                        }else if($oldPageIdentifier == "responseExamAMPPage"){
                            $pageIdentifier = 'responseExamPage';
                        }else if($oldPageIdentifier == "responseAMPPage"){
                            $pageIdentifier = 'responsePage';
                        }else if($oldPageIdentifier == "loginAMPPage"){
                            $pageIdentifier = 'loginPage';
                        }
                        
                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                
                        $indexParams['body'][] = array(
                            'doc_as_upsert' => true,
                            'doc' => array(
                                "exitPage" => array(
                                    "pageIdentifier"=>$pageIdentifier,
                                    "isAmpPage" => true
                                )
                            )
                        );
                    }
                    //_p($indexParams);die;
                    if(count($indexParams)>0){
                        $indexResponse = $ESConnection1->bulk($indexParams);    
                    }
                    
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("Batch Indexed");
                }
                else {
                    break;
                }

                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateShikhsaResponseIndex($date = array()){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.16.101";
        $host1 = "172.16.3.109";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_response";
        $type = "response";
        
        $dataChunk = 2000;
        $scroll_time = "5m";
        $date = array('startDate' => "2018-11-16 00:00:00", "endDate" => "2018-11-20 15:40:00");
        //$date = array('startDate' => "2018-11-16 00:00:00");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        //$params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        /*$params['body'] = array(
            "size"  => $dataChunk
        );*/

        $params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"],
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "response_time" => array(
                                            "gte"=> "2017-07-17T11:56:28"
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );

        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => "shiksha_response",
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );

                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($ESConnection1);die;
                    $t1 = time();
                    $indexResponse = $ESConnection1->bulk($indexParams);
                    $t2 = time();
                    error_log("DDDDD1 Indexing time : ".($t2-$t1));
                    //_p($indexResponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("DDDDD Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateResponseData(){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.16.91";
        $ESConnection = $this->getESConnection($host);

        $lib = $this->load->library('trackingMIS/elasticSearch/ESConnectionLib');
        $ESConnection1 = $lib->getESServerConnectionWithCredentials();
        //_p($ESConnection1);die;
        $index = "mis_responses";
        $type = "response";

        $dataChunk = 100;
        $scroll_time = "5m";

        $fields = array("tempLMSId","categoryId","subCategoryId","courseLevel","countryId","listingTypeId","ldbCourseId","listingType");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"],
            "fields" => $fields,
            "query" => array(
                "filtered"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "term" => array(
                                        "site" => "Study Abroad"
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
                //_p($response);die;
                if(count($response['hits']['hits']) > 0) {
                    $dataMapping = array();
                    $tempLMSIdArray = array();

                    foreach($response['hits']['hits'] as $row) {
                        $rowData = array();
                        $rowData = $row['fields'];
                        $dataMapping[$rowData['tempLMSId'][0]] = array(
                            'response_category_id' => $rowData['categoryId'][0],
                            'response_sub_category_id' => $rowData['subCategoryId'][0],
                            'response_course_level' => $rowData['courseLevel'][0],
                            'response_country_id' => $rowData['countryId'][0],
                            'response_university_id' => $rowData['listingTypeId'][0],
                            'response_desired_course_id' => $rowData['ldbCourseId'][0]
                        );
                        $tempLMSIdArray[] = $rowData['tempLMSId'][0];
                        //_p($dataMapping);die;
                    }

                    $numTempLMSIds = count($tempLMSIdArray);
                    $batchSize = 20;
                    for($i=0;$i<$numTempLMSIds;$i+=$batchSize){
                        $slice = array_slice($tempLMSIdArray, $i, $batchSize);
                        $params = array();
                        $params['index'] = "shiksha_response";
                        $params['type'] = 'response';
                        $params['body']['_source'] = array("temp_lms_id");
                        $params['body']['size'] = 100000;
                        $params['body']['query']['bool']['filter']['bool']['must']['terms']['temp_lms_id'] = $slice;
                        //_p($ESConnection1);die;
                        //_p(json_encode($params));die;
                        $search = $ESConnection1->search($params);
                        $results = $search['hits']['hits'];
                        $ESIdToDataMapping = array();
                        if(count($results) >0){
                            foreach ($results as $key => $value) {
                                $ESIdToDataMapping[$value['_id']] = $dataMapping[$value['_source']['temp_lms_id']];
                            }
                            //_p($ESIdToDataMapping);die;
                            $this->updateESResponsedata($ESIdToDataMapping, $ESConnection1);
                        }
                    }
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    function updateESResponsedata($data, $ESConnection1){
        //_p($data);die;
        $params = array();
        $params = array();
        $params['index'] = "shiksha_response";
        $params['type'] = "response";

        foreach($data as $key => $result) {
            $params['body'][] = array('update' => array(
                                        '_id' => $key
                                    )
                                );  
            
            $params['body'][] = array(
                                'doc_as_upsert' => false,
                                'doc' => $result
                            );
        }
        //_p($params);die;
        
        $ESConnection2 = $this->getESConnection("http://elastic:changeme@172.16.3.109:9200");
        //_p($ESConnection2);die;
        $indexResponse = $ESConnection2->bulk($params);
        
        foreach ($indexResponse['items'] as $key => $response) {
            if($response['update']['status'] == "404"){
                error_log(json_encode(array("_id"=> $response['update']['_id'],"error"=>$response['update']['error']['type']))."\n", 3, "/tmp/responseMigration.log");
            }
        }
        _p($ESConnection2);die;
    }

    public function migrateShikhsaRegistrationIndex($date = array()){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.82.12";
        $host1 = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "mis_registrations";
        $type = "registration";
        
        $dataChunk = 2000;
        $scroll_time = "5m";
        //$date = array('startDate' => "2018-12-13T22:00:00", "endDate" => "2018-12-14T09:59:59");
        //$date = array('startDate' => "2018-11-16 00:00:00");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        //$params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk
        );        

        /*$params['body'] = array(
            "size"  => $dataChunk,
            "sort" => ["_doc"],
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "registrationDate" => array(
                                            "gte"=> $date["startDate"],
                                            "lte" => $date["endDate"]
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );*/

        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );

                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => $index,
                                '_type' => $type,
                                '_id' => "registration_".$row['_source']['userId']
                                )
                        );

                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($ESConnection1);die;
                    $t1 = time();
                    //_p($indexParams);die;
                    $indexResponse = $ESConnection1->bulk($indexParams);
                    $t2 = time();
                    error_log("DDDDD1 Indexing time : ".($t2-$t1));
                    //_p($indexResponse['items']);die;
                    /*foreach ($indexResponse['items'] as $key => $response) {
                        foreach ($response as $key1 => $value) {
                            if(!($value['_shards']['successful'] ==1)){
                                _p($value);
                            }
                        }
                    }*/
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("DDDDD Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateShikhsaResponseIndex1($date = array()){
        ini_set('memory_limit', '1024M');
        set_time_limit(0);

        $host = "10.10.16.101";
        $host1 = "172.16.3.109";
        $ESConnection = $this->getESConnection($host);
        $ESConnection1 = $this->getESConnection($host1);
        $index = "shiksha_response";
        $type = "response";
        
        $dataChunk = 2000;
        $scroll_time = "5m";
        //$date = array('startDate' => "2018-12-13T22:00:00", "endDate" => "2018-12-14T09:59:59");
        //$date = array('startDate' => "2018-11-16 00:00:00");

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        //$params['search_type'] = 'scan';
        $params['scroll'] = $scroll_time;

        /*$params['body'] = array(
            "size"  => $dataChunk
        );*/        

        $params['body'] = array(
            "size"  => $dataChunk,
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "response_time" => array(
                                            "gte"=> "2019-03-01T00:00:00",
                                            "lt" => "2019-04-01T00:00:00"
                                        )
                                    )
                                ),
                                array(
                                    "terms" => array(
                                        "tracking_id" => array(257,258,259,260,261,262,263,264,265,266,267,274,275,276,277,278,279,280,281,282,283,284,285,1288,1289,1290,1291,1292,1293,1294,1295,1296,1297,1298,1299,1300,1301,1302,1303,1304,1305,1306,1307,1308,1309,1310,1311,1312,1313,1314,1445,1447,1485,1487,1505,1507,1509,1519,1521,1523,1525,1527,1529,1531,1533,1745,1747,1749,1751,1753,1911,2123,2125,2135,2137,2139,2141,2143,2145,2147,2149,2151,2153,2155,2157,2159,2161,2163,2165,2167,2169,2171,2173,2175,2177,2179,2181,2183,2185,2187,2189,2191,2193,2195,2197,2199,2201,2203,2205,2207,2209,2211,2213,2215,2217,2219,2221,2223,2225,2227,2229,2231,2233,2235,2237,2239,2241,2243,2245,2247,2249,2251,2253,2255,2257,2259,2261,2263,2265,2267,2269,2271,2273,2275,2277,2279,2281,2283,2285,2287,2289,2291,2293,2295,2297,2299,2301,2303,2305,2307,2309,2311,2313,2315,2317,2319,2321,2323,2325,2327,2329,2331,2333,2335,2337,2339,2341,2343,2345,2347,2349,2351,2353,2355,2357,2359,2361,2363,2365,2367,2369,2371,2373,2375,2377,2379,2381,2383,2385,2387,2389,2391,2393,2395,2397,2399,2401,2403,2405,2407,2409,2411,2413,2415,2417,2419,2421,2423,2425,2427,2429,2431,2433,2435,2437,2439,2441,2469,2471,2473,2475,2477,2479,2481,2483,2485,2487,2489,2491,2493,2495,2497,2499,2501,2503,2505,2507,2509,2511,2513,2515,2517,2519,2521,2523,2525,2527,2529,2531,2533,2535,2537,2539,2541,2543,2545,2547,2549,2551,2553,2555,2557,2559,2561,2563,2565,2567,2569,2571,2573,2575,2577,2579,2581,2583,2585,2587,2589,2591,2593,2595,2597,2599,2601,2603,2605,2607,2609,2611,2613,2615,2617,2619,2621,2623,2625,2627,2629,2631,2633,2635,2637,2639,2641,2643,2645,2647,2649,2651,2653,2655,2657,2659,2661,2663,2665,2667,2669,2671,2673,2675,2677,2679,2681,2683,2685,2687,2689,2691,2693,2695,2697,2699,2701,2703,2705,2707,2709,2711,2713,2715,2717,2719,2721,2723,2725,2727,2729,2731,2733,2735,2737,2739,2741,2743,2745,2747,2749,2751,2753,2755,2757,2759,2761,2763,2765,2767,2769,2771,2773,2775,2777,2779,2781,2783,2785,2787,2789,2791,2793,2795,2797,2799,2801,2803,2805,2807,2809,2811,2813,2815,2817,2819,2821,2823,2825,2827,2829,2831,2833,2835,2837,2839,2841,2843,2845,2847,2849,2851,2853,2855,2857,2859,2861,2863,2865,2867,2869,2871,2873,2875,2877,2879,2881,2883,2885,2887,2889,2891,2893,2895,2897,2899,2901,2903,2905,2907,2909,2911,2913,2915,2917,2919,2921,2923,2925,2927,2945,2947,2949,2951,2953,2955,2957,2959,2961,2963,2965,2967,2969,2971,2973,2975,2977,2979,2981,2983,2985,2987,2989,2991,2993,2995,2997,2999,3001,3003,3005,3007,3009,3011,3013,3015,3017,3019,3021,3023,3025,3027,3029,3031,3033,3035,3037,3039,3041,3043,3045,3047,3049,3051,3053,3055,3057,3059,3061,3063,3065,3067,3069,3071,3073,3075,3077,3079,3081,3083,3085,3087,3089,3091,3093,3095,3097,3099,3101,3103,3105,3107,3109,3111,3113,3115,3117,3119,3121,3123,3125,3127,3129,3131,3133,3135,3137,3139,3141,3143,3145,3147,3149,3151,3153,3155,3157,3159,3161,3163,3165,3167,3169,3171)
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );
        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $scroll_id = $response['_scroll_id'];
        while (true) {
            try{
                $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
                //_p($response);die;
                if(count($response['hits']['hits']) > 0) {
                    foreach($response['hits']['hits'] as $row) {
                        //_p($row);die;
                        $indexParams['body'][] = array('index' => array(
                                '_index' => $index,
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );

                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($ESConnection1);die;
                    $t1 = time();
                    //_p($indexParams);die;
                    $indexResponse = $ESConnection1->bulk($indexParams);
                    $t2 = time();
                    error_log("DDDDD1 Indexing time : ".($t2-$t1));
                    //_p($indexResponse['items']);die;
                    /*foreach ($indexResponse['items'] as $key => $response) {
                        foreach ($response as $key1 => $value) {
                            if(!($value['_shards']['successful'] ==1)){
                                _p($value);
                            }
                        }
                    }*/
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($indexResponse);
                    error_log("DDDDD Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateRegistrationIndex($date = array()){
        $this->validateCron();
        // For exam page now
        // get tracking ids for exam pages
        $lib = $this->load->library('trackingMIS/MISCronsLib');
        $trackingData = $lib->getTrackingIdsForPageGroup("examPageMain");
        
        $trackingIds = array_keys($trackingData);

        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);

        $index = "mis_registrations";
        $type = "registration";
        $dataChunk = 200;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("trackingKeyId"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "terms"=> array(
                                        "trackingKeyId" => $trackingIds
                                    )
                                )
                            ),
                            "must_not"=> array(
                                array(
                                    "exists"=> array(
                                        "field" => "sourceApplicationType"
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );

        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //$scroll_id = $response['_scroll_id'];
        while (count($response['hits']['hits']) > 0) {
            try{
                //_p($response);
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                        
                        $dataToInsert = array(
                            "sourceApplication"     => $trackingData[$row["_source"]["trackingKeyId"]]["siteSource"],
                            "sourceApplicationType" => $trackingData[$row["_source"]["trackingKeyId"]]["siteSourceType"],
                            "pageGroup"             => $trackingData[$row["_source"]["trackingKeyId"]]["pageGroup"],
                            "pageIdentifier"        => $trackingData[$row["_source"]["trackingKeyId"]]["page"]
                        );

                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }
                    //_p($indexParams);
                    $esresponse = $ESConnection->bulk($indexParams);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    //_p($response);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateResponseIndex($date = array()){
        // For exam page now
        // get tracking ids for exam pages
        $lib = $this->load->library('trackingMIS/MISCronsLib');
        $trackingData = $lib->getTrackingIdsForPageGroup("rankingPage");
        //_p($trackingData);die;
        $trackingIds = array_keys($trackingData);
        error_log(count($trackingIds));
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.16.101";
        $ESConnection = $this->getESConnection($host);

        $index = "shiksha_response";
        $type = "response";
        $dataChunk = 2000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("tracking_id"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "terms"=> array(
                                        "tracking_id" => $trackingIds
                                    )
                                )
                            ),
                            'must_not' => array(
                                array(
                                    "exists"=> array(
                                        "field" => "deviceType"
                                    )
                                )  
                            )
                        )
                    )
                )
            )
        );
        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //$scroll_id = $response['_scroll_id'];
        while (count($response['hits']['hits']) > 0) {
            try{
                /*$response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );*/
                //_p($response);;
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                        
                        $dataToInsert = array(
                            "device"     => $trackingData[$row["_source"]["tracking_id"]]["siteSource"],
                            "deviceType" => $trackingData[$row["_source"]["tracking_id"]]["siteSourceType"],
                            "pageGroup"  => $trackingData[$row["_source"]["tracking_id"]]["pageGroup"],
                            "page"       => $trackingData[$row["_source"]["tracking_id"]]["page"],
                            "site"       => $trackingData[$row["_source"]["tracking_id"]]["site"]
                        );

                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }
                    //_p($indexParams);die;
                    $esresponse = $ESConnection->bulk($indexParams);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    //_p($response);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed");
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migratePageviewIndex2($date = array()){
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);

        $index = "shiksha_trafficdata_pageviews";
        $type = "pageview";
        $dataChunk = 500;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("sessionId"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "term"=> array(
                                        "pageIdentifier" => "allExamPage"
                                    )
                                ),
                                array(
                                    "term"=> array(
                                        "isStudyAbroad" => "no"
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                //_p($response);die;
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                        
                        $dataToInsert = array(
                            "pageIdentifier"     => "examPageMain",
                            "childPageIdentifier" => "allExamPage"
                        );

                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }
                    //_p($indexParams);die;
                    $esresponse = $ESConnection->bulk($indexParams);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateSessionIndex2($date = array()){
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);

        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        $dataChunk = 1;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("sessionId"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "term"=> array(
                                        "exitPage.pageIdentifier" => "allExamPage"
                                    )
                                ),
                                array(
                                    "term"=> array(
                                        "exitPage.isStudyAbroad" => "no"
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                //_p($response);die;
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                        
                        $dataToInsert = array(
                            "exitPage" => array(
                                "pageIdentifier"     => "examPageMain",
                                "childPageIdentifier" => "allExamPage"
                            )
                        );

                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }
                    //_p($indexParams);die;
                    $esresponse = $ESConnection->bulk($indexParams);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    _p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migratePageviewDataIndex3($year=2018){
        ///usr/local/php/bin/php /var/www/html/shiksha/cron.php --run=/beacon/ESDataMigration/migratePageviewDataIndex3
        $counter = 1;
        $startDate = "2019-01-01";
        while ($counter <2) {
            $date = array();
            $date['startDate'] = $startDate." 00:00:00"; 
            //$endDate = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 day'.$startDate))));
            $date['endDate'] =   $startDate." 23:59:59";
            $year = date("Y",strtotime($startDate));
            $month = date("m",strtotime($startDate));
            //_p($date);
            $t1 = time();
            error_log("Start Date : ".print_r($date,true));
            _p($date);
            $this->migratePageviewIndex3Final($date, $year, $month);
            $t2 = time();
            error_log("DDDDD1 Indexing time : ".($t2-$t1));
            $startDate = date('Y-m-d',strtotime('+1 day'.$startDate));
            //_p($t2-$t1);
            $counter ++;
        }    
    }

    public function migratePageviewDataIndex333($year=2018){
        ///usr/local/php/bin/php /var/www/html/shiksha/cron.php --run=/beacon/ESDataMigration/migratePageviewDataIndex3
        $dateArray = array(
            /*array("startDate" => "2018-03-06 00:00:00","endDate" => "2018-03-07 23:59:59"),
            array("startDate" => "2018-03-14 00:00:00","endDate" => "2018-03-14 23:59:59"),
            array("startDate" => "2018-04-06 00:00:00","endDate" => "2018-04-06 23:59:59"),
            array("startDate" => "2018-04-20 00:00:00","endDate" => "2018-04-20 23:59:59"),
            array("startDate" => "2018-04-23 00:00:00","endDate" => "2018-04-30 23:59:59"),
            array("startDate" => "2018-05-06 00:00:00","endDate" => "2018-05-07 23:59:59"),
            array("startDate" => "2018-05-31 00:00:00","endDate" => "2018-05-31 23:59:59"),
            array("startDate" => "2018-06-01 00:00:00","endDate" => "2018-06-01 23:59:59"),
            array("startDate" => "2018-06-05 00:00:00","endDate" => "2018-06-05 23:59:59"),
            array("startDate" => "2018-06-08 00:00:00","endDate" => "2018-06-08 23:59:59"),
            array("startDate" => "2018-06-22 00:00:00","endDate" => "2018-06-22 23:59:59"),
            array("startDate" => "2018-06-25 00:00:00","endDate" => "2018-06-25 23:59:59"),
            array("startDate" => "2018-06-28 00:00:00","endDate" => "2018-06-28 23:59:59"),
            array("startDate" => "2018-07-20 00:00:00","endDate" => "2018-07-20 23:59:59"),
            array("startDate" => "2018-08-18 00:00:00","endDate" => "2018-08-18 23:59:59"),
            array("startDate" => "2018-08-29 00:00:00","endDate" => "2018-08-29 23:59:59"),
            array("startDate" => "2018-09-15 00:00:00","endDate" => "2018-09-17 23:59:59"),
            array("startDate" => "2018-09-19 00:00:00","endDate" => "2018-09-19 23:59:59"),
            array("startDate" => "2018-12-19 00:00:00","endDate" => "2018-12-19 23:59:59"),
            array("startDate" => "2018-12-27 00:00:00","endDate" => "2018-12-28 23:59:59"),
            array("startDate" => "2018-12-30 00:00:00","endDate" => "2018-12-31 23:59:59")
            array("startDate" => "2019-01-03 00:00:00","endDate" => "2019-01-03 23:59:59"),
            array("startDate" => "2019-01-07 00:00:00","endDate" => "2019-01-09 23:59:59"),
            array("startDate" => "2019-01-16 00:00:00","endDate" => "2019-01-16 23:59:59"),
            array("startDate" => "2019-01-19 00:00:00","endDate" => "2019-01-19 23:59:59"),
            array("startDate" => "2019-01-27 00:00:00","endDate" => "2019-01-27 23:59:59"),
            array("startDate" => "2019-01-30 00:00:00","endDate" => "2019-01-30 23:59:59"),
            array("startDate" => "2019-02-04 00:00:00","endDate" => "2019-02-04 23:59:59"),
            array("startDate" => "2019-02-14 00:00:00","endDate" => "2019-02-16 23:59:59"),
            array("startDate" => "2019-02-18 00:00:00","endDate" => "2019-02-18 23:59:59"),
            array("startDate" => "2019-02-22 00:00:00","endDate" => "2019-02-23 23:59:59"),
            array("startDate" => "2019-02-27 00:00:00","endDate" => "2019-02-27 23:59:59"),
            array("startDate" => "2019-03-01 00:00:00","endDate" => "2019-03-01 23:59:59"),
            array("startDate" => "2019-03-14 00:00:00","endDate" => "2019-03-14 23:59:59"),
            array("startDate" => "2019-03-22 00:00:00","endDate" => "2019-03-22 23:59:59"),
            array("startDate" => "2019-03-30 00:00:00","endDate" => "2019-03-31 23:59:59"),
            array("startDate" => "2019-04-11 00:00:00","endDate" => "2019-04-11 23:59:59"),
            array("startDate" => "2019-04-19 00:00:00","endDate" => "2019-04-21 23:59:59"),
            array("startDate" => "2019-04-24 00:00:00","endDate" => "2019-04-25 23:59:59"),
            array("startDate" => "2019-04-27 00:00:00","endDate" => "2019-04-27 23:59:59"),
            array("startDate" => "2019-05-02 00:00:00","endDate" => "2019-05-02 23:59:59"),
            array("startDate" => "2019-05-04 00:00:00","endDate" => "2019-05-04 23:59:59"),
            array("startDate" => "2019-05-11 00:00:00","endDate" => "2019-05-11 23:59:59"),
            array("startDate" => "2019-05-15 00:00:00","endDate" => "2019-05-15 23:59:59"),
            array("startDate" => "2019-06-07 00:00:00","endDate" => "2019-06-07 23:59:59"),
            array("startDate" => "2019-06-14 00:00:00","endDate" => "2019-06-15 23:59:59"),
            array("startDate" => "2019-06-17 00:00:00","endDate" => "2019-06-18 23:59:59"),
            array("startDate" => "2019-06-22 00:00:00","endDate" => "2019-06-25 23:59:59"),
            array("startDate" => "2019-06-28 00:00:00","endDate" => "2019-06-29 23:59:59"),
            array("startDate" => "2019-07-06 00:00:00","endDate" => "2019-07-06 23:59:59"),
            array("startDate" => "2019-07-09 00:00:00","endDate" => "2019-07-09 23:59:59"),
            array("startDate" => "2019-07-10 00:00:00","endDate" => "2019-07-10 23:59:59"),
            array("startDate" => "2019-07-18 00:00:00","endDate" => "2019-07-20 23:59:59")*/
            /*array("startDate" => "2018-07-20 00:00:00","endDate" => "2018-07-20 23:59:59"),
            array("startDate" => "2018-12-30 00:00:00","endDate" => "2018-12-31 23:59:59"),
            array("startDate" => "2019-01-27 00:00:00","endDate" => "2019-01-27 23:59:59"),
            array("startDate" => "2019-02-27 00:00:00","endDate" => "2019-02-27 23:59:59"),
            array("startDate" => "2019-06-14 00:00:00","endDate" => "2019-06-15 23:59:59"),
            array("startDate" => "2019-06-24 00:00:00","endDate" => "2019-06-24 23:59:59"),
            array("startDate" => "2019-06-29 00:00:00","endDate" => "2019-06-29 23:59:59"),
            array("startDate" => "2019-07-09 00:00:00","endDate" => "2019-07-09 23:59:59"),*/
            array("startDate" => "2019-07-23 00:00:00","endDate" => "2019-07-23 23:59:59")
        );

        foreach ($dateArray as $key => $dateData) {
            $date = array();
            $date = array("startDate" => $dateData["startDate"],"endDate" => $dateData["endDate"]);
            $year = date("Y",strtotime($dateData["startDate"]));
            $month = date("m",strtotime($dateData["startDate"]));
            error_log("Start Date 1: ".print_r($date,true));
            //$this->migratePageviewIndex3Final($date, $year, $month);
            error_log("Start Date 2: ".print_r($date,true));
        }
    }


    public function migratePageviewIndex3FinalTest($date = array()){
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        //$host = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);
        $host = "172.16.3.248";
        //$ESConnection1 = $this->getESConnection($host);

        $index = "shiksha_trafficdata_pageviews";
        $type = "pageview";
        $dataChunk = 1000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "visitTime" => array(
                                            "gte" => $date['startDate'],
                                            "lte" => $date['endDate']
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);

        //_p($response);
        
        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => "test_shikshatrafficdatapageviews",
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );
                        if($row['_source']['isStudyAbroad'] == "no"){
                            $row['_source']["childPageIdentifier"] = "MMP";
                            $row['_source']["pageIdentifier"]     = "examPageMain";
                        }
                            

                        $indexParams['body'][] = $row['_source'];

                    }
                    //_p($indexParams);die;
                    //die("Don't remove this");
                    $esresponse = $ESConnection->bulk($indexParams);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['index']['_shards']['failed'] == 0)){
                            _p($value1);
                            error_log($value1);
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migratePageviewIndex3Final($date = array(), $year, $month){
        $newIndex = "shiksha_pageviews_m".$year.$month;
        $utcDateRange = array();
        $utcDateRange['startDate'] = str_replace(" ", "T", convertDateISTtoUTC($date['startDate']));
        $utcDateRange['endDate'] = str_replace(" ", "T", convertDateISTtoUTC($date['endDate']));
        
        $date['startDate'] = str_replace(" ", "T", $date['startDate']);
        $date['endDate'] = str_replace(" ", "T", $date['endDate']);
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        //$host = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);
        $index = "shiksha_trafficdata_pageviews";
        $type = "pageview";
        $dataChunk = 2;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "visitTime" => array(
                                            "gte" => $utcDateRange['startDate'],
                                            "lte" => $utcDateRange['endDate']
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);

        //_p($response);die;
        
        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => $newIndex,
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );
                        if($row['_source']['isStudyAbroad'] == "no"){
                            $pageGroupData = $this->_getChildPageIdentifier($row);
                            $row['_source']["childPageIdentifier"] = $pageGroupData['childPageIdentifier'];
                            $row['_source']["pageIdentifier"]     = $pageGroupData['pageIdentifier'];
                        }
                        $row['_source']['visitTimeIST'] = str_replace(" ", "T", convertDateUTCtoIST(str_replace("T", " ", $row["_source"]["visitTime"])));
                        $row['_source']['timeIST'] = date("H:i:s",strtotime($row['_source']['visitTimeIST']));
                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($indexParams);die;
                    //die("Don't remove this");
                    $esresponse = $ESConnection->bulk($indexParams);
                    //error_log("=======Start======================"."\n".print_r($esresponse,true)."\n", 3, "/data/app_logs/pvdatamigration.log_".date('Y-m-d'));
                    usleep(100);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['index']['_shards']['failed'] == 0)){
                            _p($value1);
                            error_log("ERROR IN RESPONSE  : ".print_r($value1,true));
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateSessionDataIndex3($year=2018){
        //error_log("uncomment it");
        //die("uncomment it");
        ///usr/local/php/bin/php /var/www/html/shiksha/cron.php --run=/beacon/ESDataMigration/migratePageviewDataIndex3
        $counter = 1;
        $startDate = "2016-01-01";
        while ($counter <2) {
            $date = array();
            $date['startDate'] = $startDate." 00:00:00"; 
            //$endDate = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 day'.$startDate))));
            $date['endDate'] =   $startDate." 23:59:59";
            $year = date("Y",strtotime($startDate));
            $month = date("m",strtotime($startDate));
            //_p($date);
            $t1 = time();
            error_log("Start Date : ".print_r($date,true));
            $this->migrateSessionIndex3Final($date, $year, $month);
            $t2 = time();
            error_log("DDDDD1 Indexing time : ".($t2-$t1));
            $startDate = date('Y-m-d',strtotime('+1 day'.$startDate));
            //_p($t2-$t1);
            $counter ++;
        }    
    }

    public function migrateSessionIndex3Final($date = array(), $year, $month){
        $newIndex = "shiksha_sessions_m".$year.$month;
        $utcDateRange = array();
        $utcDateRange['startDate'] = str_replace(" ", "T", convertDateISTtoUTC($date['startDate']));
        $utcDateRange['endDate'] = str_replace(" ", "T", convertDateISTtoUTC($date['endDate']));
        
        $date['startDate'] = str_replace(" ", "T", $date['startDate']);
        $date['endDate'] = str_replace(" ", "T", $date['endDate']);
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        //$host = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);
        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        $dataChunk = 1000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "range"=> array(
                                        "startTime" => array(
                                            "gte" => $utcDateRange['startDate'],
                                            "lte" => $utcDateRange['endDate']
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);
        //_p($response);die;
        $elasticCommonLib = $this->load->library('trackingMIS/elasticSearch/elasticCommonLib');

        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        $indexParams['body'][] = array('index' => array(
                                '_index' => $newIndex,
                                '_type' => $type,
                                '_id' => $row['_id']
                                )
                        );
                        //_p($row['_source']);die;
                        if($row['_source']['landingPageDoc']['isStudyAbroad'] == "no"){
                            $tempData = array();
                            $tempData = array(
                                    "pageIdentifier" => $row['_source']['landingPageDoc']['pageIdentifier'],
                                    "pageURL" => $row['_source']['landingPageDoc']['pageURL'],
                                    "childPageIdentifier" => $row['_source']['landingPageDoc']['childPageIdentifier']
                            );
                            $pageGroupData = array();
                            $pageGroupData = $elasticCommonLib->getPageIdentifierMapping($tempData['pageIdentifier'], $tempData['pageURL'],$tempData['childPageIdentifier']);
                            
                            $row['_source']["landingPageDoc"]["childPageIdentifier"] = $pageGroupData['childPageIdentifier'];
                            $row['_source']["landingPageDoc"]["pageIdentifier"]     = $pageGroupData['pageIdentifier'];
                        }
                        if($row['_source']['exitPage']['isStudyAbroad'] == "no"){
                            $tempData = array();
                            $tempData = array(
                                "pageIdentifier" => $row['_source']['exitPage']['pageIdentifier'],
                                "pageURL" => $row['_source']['exitPage']['pageURL'],
                                "childPageIdentifier" => $row['_source']['exitPage']['childPageIdentifier']
                            );

                            $pageGroupData = array();
                            $pageGroupData = $elasticCommonLib->getPageIdentifierMapping($tempData['pageIdentifier'], $tempData['pageURL'],$tempData['childPageIdentifier']);

                            $row['_source']["exitPage"]["childPageIdentifier"] = $pageGroupData['childPageIdentifier'];
                            $row['_source']["exitPage"]["pageIdentifier"]     = $pageGroupData['pageIdentifier'];
                        }

                        $row['_source']['startTimeIST'] = str_replace(" ", "T", convertDateUTCtoIST(str_replace("T", " ", $row["_source"]["startTime"])));
                        $row['_source']['timeIST'] = date("H:i:s",strtotime($row['_source']['startTimeIST']));

                        // for landinpage
                        $row['_source']['landingPageDoc']['visitTimeIST'] = str_replace(" ", "T", convertDateUTCtoIST(str_replace("T", " ", $row["_source"]["landingPageDoc"]["visitTime"])));
                        $row['_source']['landingPageDoc']['timeIST'] = date("H:i:s",strtotime($row['_source']['landingPageDoc']['visitTimeIST']));

                        // for exitpage
                        $row['_source']['exitPage']['visitTimeIST'] = str_replace(" ", "T", convertDateUTCtoIST(str_replace("T", " ", $row["_source"]["exitPage"]["visitTime"])));
                        $row['_source']['exitPage']['timeIST'] = date("H:i:s",strtotime($row['_source']['exitPage']['visitTimeIST']));


                        $indexParams['body'][] = $row['_source'];
                    }
                    //_p($indexParams);die;
                    //die("Don't remove this");
                    $esresponse = $ESConnection->bulk($indexParams);
                    //_p($esresponse);die;
                    //error_log("=======Start======================"."\n".print_r($esresponse,true)."\n", 3, "/data/app_logs/pvdatamigration.log_".date('Y-m-d'));
                    usleep(1000);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['index']['_shards']['failed'] == 0)){
                            _p($value1);
                            error_log("ERROR IN RESPONSE  : ".print_r($value1,true));
                            error_log(" ERROR IN RESPONSE : ".print_r(json_encode($value1),true)."\n", 3, "/data/app_logs/migrateSessinIndex.log_".date('Y-m-d'));
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function prepareDateForMigrateExamPageData(){
        // done till 05-2018
        /*$data = array(
            array(
                'index' => "shiksha_pageviews_m201510",
                'date' => "2015-10-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201511",
                'date' => "2015-11-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201512",
                'date' => "2015-12-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201601",
                'date' => "2016-01-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201602",
                'date' => "2016-02-01",
                'count' => 28
            ),
            array(
                'index' => "shiksha_pageviews_m201603",
                'date' => "2016-03-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201604",
                'date' => "2016-04-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201605",
                'date' => "2016-05-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201606",
                'date' => "2016-06-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201607",
                'date' => "2016-07-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201608",
                'date' => "2016-08-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201609",
                'date' => "2016-09-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201610",
                'date' => "2016-10-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201611",
                'date' => "2016-11-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201612",
                'date' => "2016-12-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201701",
                'date' => "2017-01-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201702",
                'date' => "2017-02-01",
                'count' => 28
            ),
            array(
                'index' => "shiksha_pageviews_m201703",
                'date' => "2017-03-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201704",
                'date' => "2017-04-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201705",
                'date' => "2017-05-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201706",
                'date' => "2017-06-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201707",
                'date' => "2017-07-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201708",
                'date' => "2017-08-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201709",
                'date' => "2017-09-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201710",
                'date' => "2017-10-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201711",
                'date' => "2017-11-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201712",
                'date' => "2017-12-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201801",
                'date' => "2018-01-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201802",
                'date' => "2018-02-01",
                'count' => 28
            ),
            array(
                'index' => "shiksha_pageviews_m201803",
                'date' => "2018-03-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201804",
                'date' => "2018-04-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201805",
                'date' => "2018-05-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201806",
                'date' => "2018-06-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201807",
                'date' => "2018-07-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201808",
                'date' => "2018-08-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201809",
                'date' => "2018-09-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201810",
                'date' => "2018-10-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201811",
                'date' => "2018-11-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201812",
                'date' => "2018-12-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201901",
                'date' => "2019-01-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201902",
                'date' => "2019-02-01",
                'count' => 28
            ),
            array(
                'index' => "shiksha_pageviews_m201903",
                'date' => "2019-03-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201904",
                'date' => "2019-04-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201905",
                'date' => "2019-05-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201906",
                'date' => "2019-06-01",
                'count' => 30
            )
        );*/
        $data = array(            
            array(
                'index' => "shiksha_pageviews_m201806",
                'date' => "2018-06-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201807",
                'date' => "2018-07-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201808",
                'date' => "2018-08-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201809",
                'date' => "2018-09-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201810",
                'date' => "2018-10-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201811",
                'date' => "2018-11-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201812",
                'date' => "2018-12-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201901",
                'date' => "2019-01-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201902",
                'date' => "2019-02-01",
                'count' => 28
            ),
            array(
                'index' => "shiksha_pageviews_m201903",
                'date' => "2019-03-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201904",
                'date' => "2019-04-01",
                'count' => 30
            ),
            array(
                'index' => "shiksha_pageviews_m201905",
                'date' => "2019-05-01",
                'count' => 31
            ),
            array(
                'index' => "shiksha_pageviews_m201906",
                'date' => "2019-06-01",
                'count' => 30
            )
        );
        foreach ($data as $data1) {
            $this->migrateExamPageData($data1['date'],$data1['index'],$data1['count']);
        }
    }

    public function migrateExamPageData($startDate, $indexname, $count){
        $counter = 1;
        //$startDate = "2019-01-01";
        //echo $indexname."  ::: ".$startDate." ::::".$count."<br>";
        while ($counter <=$count) {
            $date = array();
            $date['startDate'] = $startDate."T00:00:00"; 
            //$endDate = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 day'.$startDate))));
            $date['endDate'] =   $startDate."T23:59:59";                        
            error_log("Start Date : ".print_r($date,true));
            _p($date);
            $this->migratePageviewIndex3($date, $indexname);
            error_log("DDDDD1 Indexing time : ");
            $startDate = date('Y-m-d',strtotime('+1 day'.$startDate));
            //_p($t2-$t1);
            $counter ++;
        }
    }

    public function migratePageviewIndex3($date = array(), $indexname){
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        //$host = "172.16.3.248";
        $ESConnection = $this->getESConnection($host);

        $index = $indexname;//"shiksha_pageviews_m201901";
        $type = "pageview";
        $dataChunk = 1000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "term"=> array(
                                        "pageIdentifier" => "examPageMain"
                                    )
                                ),
                                array(
                                    "term"=> array(
                                        "isStudyAbroad" => "no"
                                    )
                                ),
                                array(
                                    "term"=> array(
                                        "childPageIdentifier" => "examPageMain"
                                    )
                                ),
                                array(
                                    "range"=> array(
                                        "visitTimeIST" => array(
                                            "gte" => $date['startDate'],
                                            "lte" => $date['endDate']
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        _p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);

        //_p($response);
        
        $indexCount = 1;
        $elasticCommonLib = $this->load->library('trackingMIS/elasticSearch/elasticCommonLib');
        while (count($response['hits']['hits']) > 0) {
            try{
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        //_p($response['hits']['hits']);die;
                        //echo $row['_source']['pageURL']."<br>";
                        //_p($row);die;
                        //_p($row['_source']['pageURL'] = "https://www.shiksha.com/mba/cat-exam-cutoff");
                        $childPageIdentifier ="";
                        $data = $elasticCommonLib->getPageIdentifierMapping($row['_source']['pageIdentifier'], $row['_source']['pageURL'],$row['_source']['childPageIdentifier']);
                        $childPageIdentifier = $data['childPageIdentifier'];
                        
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );

                        $dataToInsert = array(
                            "childPageIdentifier" => $childPageIdentifier
                        );
                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }
                    _p($indexParams);die;
                    //die("Don't remove this");
                    $esresponse = $ESConnection->bulk($indexParams);
                    _p($esresponse);die;
                    usleep(100);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    private function _getChildPageIdentifier($row){
        //_p($row['_source']['pageURL']);
        $data = array();
        $elasticCommonLib = $this->load->library('trackingMIS/elasticSearch/elasticCommonLib');
        $data = $elasticCommonLib->getPageIdentifierMapping($row['_source']['pageIdentifier'], $row['_source']['pageURL'],$row['_source']['childPageIdentifier']);
        /*switch ($row['_source']['pageIdentifier']) {
            case "mobileVerificationSMS":
            case "mobileVerificationSMSExam":
            case "smsResponse":
            case "hamburgerRightPanel":
            case "nationalSite":
            case "commonHeader":
            case "any":
            case "enterpriseCMS":
            case "hamburgerLeftPanelAMP":
            case "shiksha":
            case "facebook":
            case "rightHandLayerMobile":
            case "hamburgerLeftPanel":
            case "registrationLayer":
            case "Domestic":
            case "ContactUs":
            case "Student_Helpline":
            case "loginPage":
            case "eventCalendar":
            case "tagDetailPage":
            case "compareMobile":
            case "compareDesktop":
            case "comparePage":
            case "discussionTagDetailPage":
            case "unansweredTagDetailPage":
            case "allTagFollow":
            case "discussionTab":
            case "unansweredTab":
            case "getMentorPage":
            case "advisoryBoard":
            case "answerViewMorePage":
            case "commentViewMorePage":
            case "commentReplyPage":
            case "answerCommentPage":
            case "userProfileEditPage":
            case "mentorshipPage":
            case "campusRepresentativeIntermediatePage":
            case "careerCompasPage":
            case "campusRepresentative":
            case "campusAmbassadorForm":
            case "mentorshipForm":
            case "courseFaqHomePage":
            case "forgotPasswordPage":
            case "privacyPolicyPage":
            case "careersListPage":
            case "termsConditionsPage":
            case "userPublicProfilePage":
            case "contactUsPage":
            case "coursePageQuestionPosting":
            case "studentHelpLinePage":
            case "courseFaqPage":
            case "aboutUsPage":
            case "eventDetailsPage":
            case "viewAllTagsPage":
            case "articleAuthorProfilePage":
            case "shikshaAuthorsProfilePage":
            case "ShikshaEnterprise":
            case "shikshaComplaintPage":
            case "managementTeamPage":
                $data = array("pageIdentifier" => "others", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;
            
            case "mobileVerificationMailer":
            case "mobileVerificationMailerExam":
            case "clientMailers":
            case "mailers":
                $data = array("pageIdentifier" => "mailers", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "univeristyEntityListingPage":
            case "instituteEntityListingPage":
            case "examEntityTrendPage":
                $data = array("pageIdentifier" => "shikshaTrends", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "collegeReviewPage":
            case "collegeReviewIntermediatePage":
            case "collegeReviewRatingForm":
                $data = array("pageIdentifier" => "collegeReviews", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;


            case "MMP":
                $data = array("pageIdentifier" => "MMP", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "careerDetailPage":
            case "careerCounselling":
            case "careerOpportunities":
            case "careerHomePage":
                $data = array("pageIdentifier" => "careerCentral", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "shortlistPage":
            case "myShortlist":
            case "shortlistedColleges":
                $data = array("pageIdentifier" => "shortlistPage", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "qnaPage":
            case "discussionPage":
            case "unansweredPage":
            case "allDiscussionsPage":
            case "questionDetailPage":
            case "discussionDetailPage":
            case "userDetailPage":
            case "announcementPage":
            case "cafeBuzzPage":
            case "announcementDetailPage":
            case "advisoryBoardPage":
            case "questionIntermediatePage":
            case "unAnsweredPage":
                $data = array("pageIdentifier" => "AnA", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "allQuestionsPage":
                if(!(strpos($row['_source']['pageURL'], "ask.shiksha.com") === false)){
                    $data = array("pageIdentifier" => "AnA", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                }else{
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                }
                break;

            case "onlineFormDashboard":
            case "onlineFormPage":
            case "studentFormsDashBoardPage":
            case "onlineApplicationForm":
            case "onlineDisplayFormPage":
                $data = array("pageIdentifier" => "onlineForms", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "allCollegePredictorPage":
            case "clatpredictor":
            case "collegePredictor":
            case "iimPredictorInput":
            case "mahcetpredictor":
            case "nchmctpredictor":
            case "niftpredictor":
            case "iimPredictorOutput":
                $data = array("pageIdentifier" => "collegePredictor", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "rankPredictor":
            case "CatPercentilePredictor":
                $data = array("pageIdentifier" => "rankPredictor", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "homePage":
                $data = array("pageIdentifier" => "homePage", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "androidApp":
                $data = array("pageIdentifier" => "androidApp", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "examPageMain":
                $childPageIentifier = "examPage";
                $urlParts = parse_url($row['_source']['pageURL']."/");
                $requestPath = $urlParts['path'];
                $requestPath = rtrim($requestPath,"/");
                $requestPath = explode("/", $requestPath);
                $parts = count($requestPath);
                //_p($requestPath);
                $requestPath = $requestPath[$parts-1];

                if(!(strpos($requestPath, "admit-card") === false)){
                    $childPageIentifier = "examAdmitCardPage";
                }else if(!(strpos($requestPath, "answer-key")=== false)){
                    $childPageIentifier = "examAnswerKeyPage";
                }else if(!(strpos($requestPath, "dates") === false )){
                    $childPageIentifier = "examImportantDatesPage";
                }else if(!(strpos($requestPath, "application-form") === false )){
                    $childPageIentifier = "examApplicationFormPage";
                }else if(!(strpos($requestPath, "counselling") === false )){
                    $childPageIentifier = "examCounsellingPage";
                }else if(!(strpos($requestPath, "cutoff") === false )){
                    $childPageIentifier = "examCutOffPage";
                }else if(!(strpos($requestPath, "pattern") === false )){
                    $childPageIentifier = "examPatternPage";
                }else if(!(strpos($requestPath, "results") === false )){
                    $childPageIentifier = "examResultsPage";
                }else if(!(strpos($requestPath, "question-papers") === false )){
                    $childPageIentifier = "examQuestionPapersPage";
                }else if(!(strpos($requestPath, "slot-booking") === false )){
                    $childPageIentifier = "examSlotBookingPage";
                }else if(!(strpos($requestPath, "syllabus") === false )){
                    $childPageIentifier = "examSyllabusPage";
                }else if(!(strpos($requestPath, "vacancies") === false )){
                    $childPageIentifier = "examVacanciesPage";
                }else if(!(strpos($requestPath, "call-letter") === false )){
                    $childPageIentifier = "examCallLettersPage";
                }else if(!(strpos($requestPath, "news") === false )){
                    $childPageIentifier = "examNewsPage";
                }else if(!(strpos($requestPath, "preptips") === false )){
                    $childPageIentifier = "examPrepTipsPage";
                }else if(!(strpos($requestPath, "-exam") === false )){
                    $childPageIentifier = "examPageMain";
                }else{
                    $childPageIentifier = "examPageMain";
                }
                $data = array("pageIdentifier" => "examPageMain", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "allExamPage":
            case "examLandingPage":
                $data = array("pageIdentifier" => "examPageMain", "childPageIdentifier" => "allExamPage");
                break;

            case "articlePage":
            case "articleDetailPage":
            case "viteeeResults":
            case "srmResults":
            case "allArticlePage":
            case "boards":
            case "coursesAfter12th":
                $data = array("pageIdentifier" => "articlePage", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "courseHomePage":
                $data = array("pageIdentifier" => "courseHomePage", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "courseListingPage":
            case "courseDetailsPage":
                $data = array("pageIdentifier" => "CLP", "childPageIdentifier" => "courseListingPage");
                break;

            case "rankingPage":
                $data = array("pageIdentifier" => "rankingPage", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "categoryPage":
            case "searchPage":
            case "searchV2Page":
            case "SRP":
            case "Global_Search":
            case "qnaSRPPage":
            case "collegeSRPPage":
            case "examSRPPage":
                $data = array("pageIdentifier" => "search", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "instituteListingPage":
            case "allCoursePage":
            case "allReviewsPage":
            case "allArticlesPage":
            case "universityListingPage":
            case "cutOffPage":
            case "placementPage":
                $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => $row['_source']['pageIdentifier']);
                break;

            case "admissionPage":
                if(!(strpos($row['_source']['pageURL'], "/admission") === false)){
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "admissionPage");
                }else{
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "scholarshipPage");
                }                
                break;

            case "scholarshipPage":
                if(!(strpos($row['_source']['pageURL'], "/scholarships") === false)){
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "scholarshipPage");
                }else{
                    $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "admissionPage");
                }                
                break;

            case "allCoursesPage":
                $data = array("pageIdentifier" => "UILP", "childPageIdentifier" => "allCoursePage");
                break;
            
            default:
                $data = array("pageIdentifier" => $row['_source']['pageIdentifier'], "childPageIdentifier" => "unknown");
                break;
        }*/
          
        //echo $requestPath."  :::::::::::::::::::::::::::::::::::::::::".$childPageIentifier."<br>";
        #_p($childPageIentifier);
        return $data;
    }

    public function migrateSessionsExitPageIndex3($year=2018){
        $counter = 1;
        $startDate = "2018-12-01";
        while ($counter <700) {
            $date = array();
            $date['startDate'] = $startDate."T00:00:00"; 
            //$endDate = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 month'.$startDate))));
            $date['endDate'] =   $startDate."T23:59:59";
            $startDate = date('Y-m-d',strtotime('+1 day'.$startDate));
            //_p($date);
            $this->migrateSessionsIndex3($date);
            $counter ++;
        }    
    }

    public function migrateSessionsIndex3($date = array()){
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);

        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        $dataChunk = 100;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("exitPage.pageURL"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "term"=> array(
                                        "exitPage.pageIdentifier" => "MMP"
                                    )
                                ),
                                array(
                                    "term"=> array(
                                        "exitPage.isStudyAbroad" => "no"
                                    )
                                ),
                                array(
                                    "range"=> array(
                                        "exitPage.visitTime" => array(
                                            "gte" => $date['startDate'],
                                            "lte" => $date['endDate']
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);

        //_p($response);
        
        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                //_p($response);die;//4031007
                //_p($response['hits']['hits']);die;
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        //echo $row['_source']['pageURL']."<br>";
                        $childPageIdentifier = "";
                        //$childPageIdentifier = $this->_getChildPageIdentifierForSession($row);
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                        
                        if($childPageIdentifier == "examPage"){
                            $dataToInsert = array(
                                "exitPage" => array(
                                    "pageIdentifier"     => "examPage"
                                )
                            );
                        }else if($childPageIdentifier == "examPageMain"){
                            $dataToInsert = array(
                                "exitPage" => array(
                                    "pageIdentifier"     => $childPageIdentifier,
                                )  
                            );
                        }else{
                            $dataToInsert = array(
                                "exitPage" => array(
                                    //"pageIdentifier"     => "examPageMain",
                                "childPageIdentifier" => "MMP"
                                )
                            );
                        }

                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }

                    //_p($indexParams);
                    //die("Don't remove this");
                    $esresponse = $ESConnection->bulk($indexParams);
                    usleep(100);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    public function migrateSessionsLandingPageIndex3($year=2018){
        $counter = 1;
        $startDate = "2018-06-01";
        while ($counter <10) {
            $date = array();
            $date['startDate'] = $startDate."T00:00:00"; 
            $endDate = date('Y-m-d',strtotime('-1 day'.date('Y-m-d',strtotime('+1 month'.$startDate))));
            $date['endDate'] =   $endDate."T23:59:59";
            $startDate = date('Y-m-d',strtotime('+1 month'.$startDate));
            //_p($date);
            $this->migrateSessionsLandingPageDocIndex3($date);
            $counter ++;
        }
    }

    public function migrateSessionsLandingPageDocIndex3($date = array()){
        // For exam page now
        // get tracking ids for all exam pages
        ini_set('memory_limit', '1024M');
        set_time_limit(0);
        $host = "10.10.82.14";
        $ESConnection = $this->getESConnection($host);

        $index = "shiksha_trafficdata_sessions";
        $type = "session";
        $dataChunk = 1000;
        $scroll_time = "5m";

        $params = array();
        $params['index'] = $index;
        $params['type'] = $type;
        $params['scroll'] = $scroll_time;

        $params['body'] = array(
            "size"  => $dataChunk,
            "_source" => array("landingPageDoc.pageURL"),
            "query" => array(
                "bool"=> array(
                    "filter"=> array(
                        "bool"=> array(
                            "must"=> array(
                                array(
                                    "term"=> array(
                                        "landingPageDoc.pageIdentifier" => "examPage"
                                    )
                                ),
                                array(
                                    "term"=> array(
                                        "landingPageDoc.isStudyAbroad" => "no"
                                    )
                                ),
                                array(
                                    "range"=> array(
                                        "landingPageDoc.visitTime" => array(
                                            "gte" => $date['startDate'],
                                            "lte" => $date['endDate']
                                        )
                                    )
                                )
                            )
                        )
                    )
                )
            )
        );


        //_p($params);die;
        //_p(json_encode($params));die;
        $response = $ESConnection->search($params);

        //_p($response);
        
        $indexCount = 1;
        while (count($response['hits']['hits']) > 0) {
            try{
                //_p($response);die;//4031007
                //_p($response['hits']['hits']);die;
                if(count($response['hits']['hits']) > 0) {
                    $indexParams = array();
                    foreach($response['hits']['hits'] as $row) {
                        //echo $row['_source']['pageURL']."<br>";
                        
                        $childPageIdentifier = $this->_getChildPageIdentifierForLandingPageSession($row);
                        $indexParams['index'] = $index;
                        $indexParams['type'] = $type;

                        $indexParams['body'][] = array('update' => array(
                            '_id' => $row['_id']
                            )
                        );
                        
                        if($childPageIdentifier == "examPage"){
                            $dataToInsert = array(
                                "landingPageDoc" => array(
                                    "pageIdentifier"     => "examPage"
                                )
                            );
                        }else if($childPageIdentifier == "examPageMain"){
                            $dataToInsert = array(
                                "landingPageDoc" => array(
                                    "pageIdentifier"     => $childPageIdentifier,
                                )  
                            );
                        }else{
                            $dataToInsert = array(
                                "landingPageDoc" => array(
                                    "pageIdentifier"     => "examPageMain",
                                "childPageIdentifier" => $childPageIdentifier
                                )
                            );
                        }

                        $indexParams['body'][] = array(
                            'doc_as_upsert' => false,
                            'doc' => $dataToInsert
                        );
                    }

                    //_p($indexParams);die;
                    //die("Don't remove this");
                    $esresponse = $ESConnection->bulk($indexParams);
                    usleep(100);
                    foreach ($esresponse['items'] as $key1 => $value1) {
                        if(!($value1['update']['_shards']['failed'] == 0)){
                            _p($value1);
                        }
                    }
                    //_p($esresponse);die;
                    $indexParams = array();
                    $indexParams['body'] = array();
                    unset($esresponse);
                    error_log("DDDDD Batch Indexed  :  ".$indexCount++);
                }
                else {
                    break;
                }

               $scroll_id = $response['_scroll_id'];
               $response = array();
               $response['hits']['hits'] = 0;
               $response = $ESConnection->scroll(
                    array(
                        "scroll_id" => $scroll_id,  
                        "scroll" => $scroll_time
                    )
                );
            }catch(Exception $e) {
                 _p('Message: ');
                 _p($e->getMessage());
            }
        }
    }

    private function _getChildPageIdentifierForSession($row){
        //_p($row['_source']['exitPage']['pageURL']);
        $childPageIentifier = "examPage";
        $urlParts = parse_url($row['_source']['exitPage']['pageURL']."/");
        $requestPath = $urlParts['path'];
        $requestPath = rtrim($requestPath,"/");
        $requestPath = explode("/", $requestPath);
        $parts = count($requestPath);
        //_p($requestPath);
        $requestPath = $requestPath[$parts-1];

        if(!(strpos($requestPath, "admit-card") === false)){
            $childPageIentifier = "examAdmitCardPage";
        }else if(!(strpos($requestPath, "answer-key")=== false)){
            $childPageIentifier = "examAnswerKeyPage";
        }else if(!(strpos($requestPath, "dates") === false )){
            $childPageIentifier = "examImportantDatesPage";
        }else if(!(strpos($requestPath, "application-form") === false )){
            $childPageIentifier = "examApplicationFormPage";
        }else if(!(strpos($requestPath, "counselling") === false )){
            $childPageIentifier = "examCounsellingPage";
        }else if(!(strpos($requestPath, "cutoff") === false )){
            $childPageIentifier = "examCutOffPage";
        }else if(!(strpos($requestPath, "pattern") === false )){
            $childPageIentifier = "examPatternPage";
        }else if(!(strpos($requestPath, "results") === false )){
            $childPageIentifier = "examResultsPage";
        }else if(!(strpos($requestPath, "question-papers") === false )){
            $childPageIentifier = "examQuestionPapersPage";
        }else if(!(strpos($requestPath, "slot-booking") === false )){
            $childPageIentifier = "examSlotBookingPage";
        }else if(!(strpos($requestPath, "syllabus") === false )){
            $childPageIentifier = "examSyllabusPage";
        }else if(!(strpos($requestPath, "vacancies") === false )){
            $childPageIentifier = "examVacanciesPage";
        }else if(!(strpos($requestPath, "call-letter") === false )){
            $childPageIentifier = "examCallLettersPage";
        }else if(!(strpos($requestPath, "news") === false )){
            $childPageIentifier = "examNewsPage";
        }else if(!(strpos($requestPath, "preptips") === false )){
            $childPageIentifier = "examPrepTipsPage";
        }else if(!(strpos($requestPath, "-exam") === false )){
            $childPageIentifier = "examPageMain";
        }else{
            $childPageIentifier = "examPageMain";
        }
        //echo $row['_source']['exitPage']['pageURL']." ::::::::: ".$requestPath."  :::::::::::::::::::::::::::::::::::::::::".$childPageIentifier."<br>";
        #_p($childPageIentifier);
        return $childPageIentifier;
    }

    private function _getChildPageIdentifierForLandingPageSession($row){
        //_p($row['_source']['landingPageDoc']['pageURL']);
        $childPageIentifier = "examPage";
        $urlParts = parse_url($row['_source']['landingPageDoc']['pageURL']."/");
        $requestPath = $urlParts['path'];
        $requestPath = rtrim($requestPath,"/");
        $requestPath = explode("/", $requestPath);
        $parts = count($requestPath);
        //_p($requestPath);
        $requestPath = $requestPath[$parts-1];

        if(!(strpos($requestPath, "admit-card") === false)){
            $childPageIentifier = "examAdmitCardPage";
        }else if(!(strpos($requestPath, "answer-key")=== false)){
            $childPageIentifier = "examAnswerKeyPage";
        }else if(!(strpos($requestPath, "dates") === false )){
            $childPageIentifier = "examImportantDatesPage";
        }else if(!(strpos($requestPath, "application-form") === false )){
            $childPageIentifier = "examApplicationFormPage";
        }else if(!(strpos($requestPath, "counselling") === false )){
            $childPageIentifier = "examCounsellingPage";
        }else if(!(strpos($requestPath, "cutoff") === false )){
            $childPageIentifier = "examCutOffPage";
        }else if(!(strpos($requestPath, "pattern") === false )){
            $childPageIentifier = "examPatternPage";
        }else if(!(strpos($requestPath, "results") === false )){
            $childPageIentifier = "examResultsPage";
        }else if(!(strpos($requestPath, "question-papers") === false )){
            $childPageIentifier = "examQuestionPapersPage";
        }else if(!(strpos($requestPath, "slot-booking") === false )){
            $childPageIentifier = "examSlotBookingPage";
        }else if(!(strpos($requestPath, "syllabus") === false )){
            $childPageIentifier = "examSyllabusPage";
        }else if(!(strpos($requestPath, "vacancies") === false )){
            $childPageIentifier = "examVacanciesPage";
        }else if(!(strpos($requestPath, "call-letter") === false )){
            $childPageIentifier = "examCallLettersPage";
        }else if(!(strpos($requestPath, "news") === false )){
            $childPageIentifier = "examNewsPage";
        }else if(!(strpos($requestPath, "preptips") === false )){
            $childPageIentifier = "examPrepTipsPage";
        }else if(!(strpos($requestPath, "-exam") === false )){
            $childPageIentifier = "examPageMain";
        }else{
            $childPageIentifier = "examPageMain";
        }
        //echo $row['_source']['landingPageDoc']['pageURL']." ::::::::: ".$requestPath."  :::::::::::::::::::::::::::::::::::::::::".$childPageIentifier."<br>";
        #_p($childPageIentifier);
        return $childPageIentifier;
    }

    /*
        examVacanciesPage
        examSyllabusPage
        examSlotBookingPage
        examResultsPage
        examQuestionPapersPage
        examPrepTipsPage
        examPatternPage
            examPageMain
        examNewsPage
        examImportantDatesPage
        examCutOffPage
        examCounsellingPage
        examCallLettersPage
        examApplicationFormPage
        examAnswerKeyPage
        examAdmitCardPage
        
    */

}
?>
