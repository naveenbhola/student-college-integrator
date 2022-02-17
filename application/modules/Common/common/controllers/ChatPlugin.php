<?php
if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class ChatPlugin extends MX_Controller{

	function __construct() {
	
	}

	function chatPlugin(){
		$displayData['validateuser'] = $this->checkUserValidation();
        $this->load->config("chatPluginConfig");
        $displayData['configData'] = $this->config->item("variables");
		$this->load->view("chatplugin/mainContainer", $displayData);
	}

    function getUserConversationHistory(){
        $lastDocTime =  $this->input->post('lastDocTime',true);
        $size =         $this->input->post('count',true);
        $order =        $this->input->post('order',true);

        // get user chat history
        $validateuser = $this->checkUserValidation();

        // by passing for internal release
        if($validateuser == 'false' || 1) { 
            $userId = 0;
        }
        else{
            $userId = $validateuser[0]['userid'];
        }

        $chatPluginLib = $this->load->library("common/chatPluginLib");
        $response = $chatPluginLib->getUserConversationHistory($lastDocTime, $size, $order, $userId);
        echo json_encode($response);
    }

    function chatBox(){

        $this->load->helper("security");
        $referrerTitle = $_REQUEST['t'];//$this->input->get("t");
        $referrerTitle = xss_clean($referrerTitle);


        $displayData['validateuser'] = $this->checkUserValidation();
        $displayData['referrerTitle'] = $referrerTitle;

        $this->load->config("chatPluginConfig");
        $displayData['configData'] = $this->config->item("variables");

        // get user chat history
        $chatPluginLib = $this->load->library("common/chatPluginLib");
        $displayData['conversionHistory'] = $chatPluginLib->getUserConversationHistory();
        $this->load->view("chatplugin/chatbox", $displayData);
    }

	function getResponse(){

        $validateuser = $this->checkUserValidation();

        $queryText = $this->input->post("question");
        $queryValue = $this->input->post("value");
		$queryType = $this->input->post("type");

        // for data tracking
        $chatArr = array();
        $chatArr['queryTime'] = str_replace(" ", "T", date("Y-m-d H:i:s"));
        $chatArr['queryTimeUTC'] = convertDateISTtoUTC(str_replace("T", " ", $chatArr['queryTime']));
        $chatArr['time'] = date("H:i:s",strtotime($chatArr['queryTimeUTC']));
        $chatArr['queryTimeUTC'] = str_replace(" ", "T", $chatArr['queryTimeUTC']);


        if(empty($queryText))
            $queryText = null;

        $requestData['userInput']["userInputText"] = $queryText;
        $requestData['userInput']['userInputEntity'] = array();
        $entityDetails = array();
        $requestDetails = array();
        if(!empty($queryValue) && $queryType == 'quickreply'){

            $queryValueArr = explode("::", $queryValue);

            $requestDetails['tagSelected'] = $queryValueArr[1];
            if(!in_array($queryValueArr[1], array("tag_closure"))){
                if(!empty($queryValueArr[0]) && !empty($queryValueArr[1])){
                    $requestData['userInput']['userInputEntity'][] = array("id" => $queryValueArr[0], "type" => $queryValueArr[1], "name"=> "aa");

                    if(!in_array($queryValueArr[1], array("tag")))
                        $requestData['userInput']["userInputText"] = null;
                }

                if(!empty($queryValueArr[0]) && !empty($queryValueArr[1])){
                    $entityDetails[] = array('entityType' => $queryValueArr[1], 'entityId' => $queryValueArr[0]);
                }
            }
        }


        $requestData['userInput'] = json_encode($requestData['userInput']);
		$requestData["visitorId"] = getVisitorSessionId();
        // _p($requestData);
		// $a = http_build_query($requestData, null, null, PHP_QUERY_RFC3986);
		// error_log("query  : ".print_r($a, true));
	
	    $headers = array('authrequest' => 'INFOEDGE_SHIKSHA');
		// start curl session
        $ch = curl_init();
		 // set Request Params in case of POST
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        // curl_setopt($ch, CURLOPT_SAFE_UPLOAD, false);
        // curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        // curl_setopt($ch, CURLOPT_URL, "http://apis.shiksha.com/autoanswer/api/v1/query");
        curl_setopt($ch, CURLOPT_URL, SHIKSHA_ASSISTANT_SERVICE."/autoanswer/api/v1/converse");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
		curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout in seconds

        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        // execute the curl request        
        $result = array();
        $result = curl_exec($ch);
        $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);

        $chatPluginLib = $this->load->library("common/chatPluginLib");
        $response = array();
        $response = $chatPluginLib->prepareAnswerView($culrRetcode, $result ,true ,$requestDetails);
        // error_log("result : ".print_r($response, true));


        // by passing for internal release
        if($validateuser == 'false') { 
            $chatArr['userId'] = 0;
        }
        else{
            $chatArr['userId'] = $validateuser[0]['userid'];
        }
        $chatArr['userIdActual'] = $chatArr['userId'];
        $chatArr['visitorId'] = getVisitorId();
        $chatArr['sessionId'] = getVisitorSessionId();      
        $chatArr['userQuery'] = $queryText;
        $chatArr['queryType'] = $queryType;
        $chatArr['entityDetails'] = $entityDetails;
        $chatArr['apiResponseCode'] = $culrRetcode;

        $apiResponseJson = json_decode($result, true);

        if($apiResponseJson && !empty($apiResponseJson['data']['resolvedEntity']))
            unset($apiResponseJson['data']['resolvedEntity']);

        if($apiResponseJson && !empty($apiResponseJson['data']['state']))
            unset($apiResponseJson['data']['state']);

        $chatArr['apiResponseData'] = $apiResponseJson;
        
        //error_log("QUEUE_DATA : ".print_r($chatArr,true));
        $rabbitmqData = array("trackingType" => "AssistantConversations", "data" => json_encode($chatArr));
        try{
            // get rabbit mq client instance
            $this->config->load('amqp');
            $this->load->library("common/jobserver/JobManagerFactory");
            $jobManager = JobManagerFactory::getClientInstance();
            $jobManager->addBackgroundJob("AssistantConversations", $rabbitmqData);
        }
        catch(Exception $e){
            error_log("JOBQException: ".$e->getMessage());
        }

        $response['queryTime'] = base64_encode(strtotime(str_replace("T", " ", $chatArr['queryTime']))); 
        echo json_encode($response);

		// exec("curl 'https://apis.shiksha.com/autoanswer/api/v1/query' -H 'ding: gzip, deflate, br' -H 'Accept-Language: en-US,en;q=0.9' -H 'User-Agent: Mozilla/5.0 (X11; Linux x86_64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/70.0.3538.102 Safari/537.36' -H 'Content-Type: application/x-www-form-urlencoded; charset=UTF-8' -H 'Accept: */*' -H 'Cache-Control: no-cache' -H 'Authrequest: INFOEDGE_SHIKSHA' -H 'X-Requested-With: XMLHttpRequest' -H 'Connection: keep-alive' --data 'question=admission iim a' --compressed --insecure");
	}
	
    function getInitialSuggestions(){

        $suggestionsLimit = 6;
        $referrerTitle = $this->input->post("t");
        $referrerTitle = base64_decode($referrerTitle);

        error_log("referrerTitle : ".$referrerTitle);

        $requestData['userInput']["userInputText"] = $referrerTitle;
        $requestData['userInput']['userInputEntity'] = array();
        $requestData['userInput'] = json_encode($requestData['userInput']);
        $requestData["visitorId"] = getVisitorSessionId();
        $headers = array('authrequest' => 'INFOEDGE_SHIKSHA');
        // start curl session
        $ch = curl_init();
         // set Request Params in case of POST
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $requestData);
        curl_setopt($ch, CURLOPT_URL, SHIKSHA_ASSISTANT_SERVICE."/autoanswer/api/v1/recommendationByText");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_HTTPHEADER, $headers);
        curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 0); 
        curl_setopt($ch, CURLOPT_TIMEOUT, 20); //timeout in seconds
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');

        // execute the curl request        
        $result = array();
        $result = curl_exec($ch);
        $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        $result = json_decode($result, true);   

        if($result['status'] == "success" && !empty($result['data']) && !empty($result['data']['promptCards'])){
            $response = array();
            $response['error'] = 0;

            $recommendations = array();
            foreach($result['data']['promptCards'] as $recoTag){

                if($recoTag['type'] == 'tag'){
                    $recommendations[] = array("text" => $recoTag['name'], "value" => $recoTag['id']."::".$recoTag['type']);
                }
            }
            $response['prompts'] = array_splice($recommendations, 0, $suggestionsLimit);
            $response['promptTitle'] = "People are also asking : ";
            $response['startConversation'] = "To help break ice, here are some topics to quickly get started -";
        }
        else{
            $response = array();
            $response['error'] = 0;

            $recommendations = array(array("text" => "How is IIT Delhi ?", "value" => "How is IIT Delhi ?"),
                                     array("text" => "Fees of IIM A", "value" => "Fees of IIM A"), 
                                     array("text" => "Scholarship at IIM C", "value" => "Scholarship at IIM C"), 
                                     array("text" => "Amity University Admission", "value" => "Amity University Admission")
                                 );

            $response['prompts'] = array_splice($recommendations, 0, $suggestionsLimit);
            $response['promptTitle'] = "People are also asking : ";

            $response['startConversation'] = "Here are something you can try asking to get started.";
        }
        echo json_encode($response);
    }


    function processChatHistory(){

        ini_set("max_execution_time","-1");
        $this->config->load('amqp');
        $this->load->library("common/jobserver/JobManagerFactory");
        $jobManager = JobManagerFactory::getWorkerInstance();

        $this->amqp_host = $this->config->item('amqp_server');
        $this->amqp_port = $this->config->item('amqp_port');
        $this->amqp_user = $this->config->item('amqp_user');
        $this->amqp_pass = $this->config->item('amqp_pass');
        $worker = new PhpAmqpLib\Connection\AMQPConnection($this->amqp_host, $this->amqp_port, $this->amqp_user, $this->amqp_pass);

        $channel = $worker->channel();

        $channel->queue_declare('AssistantConversations', false, true, false, null);

        error_log(' * Waiting for messages. To exit press CTRL+C');

        $this->indexer = $this->load->library('beacon/TrafficIndexer',array("ESHost"=>"ES5"));
        $callback = function($msg){

            try{
                $data = json_decode($msg->body, true);
                $data = $data['payLoad'];
                $indexData = json_decode($data['data'], true);
                if($data['trackingType'] == "AssistantConversations"){
                    $this->indexer->indexChatHistory($indexData);
                }else if($data['trackingType'] == "abTest"){
                    $this->indexer->indexABTestValue($indexData);
                }

                $msg->delivery_info['channel']->basic_ack($msg->delivery_info['delivery_tag']); 
                error_log("Processed ");
            }
            catch(Exception $e){
                error_log("JOBQException: ".$e->getMessage());
                error_log(" * Exception Occured ".print_r($data));
                mail("romil.goel@shiksha.com", "AssistantConversations Worker Error", "Beacon Data : \n\n".print_r($data));
            }
        };

        $channel->basic_qos(null, 1, null);
        
        $channel->basic_consume('AssistantConversations', '', false, false, false, false, $callback);

        while(count($channel->callbacks)) {
            $channel->wait();
        }
    }

    function processConversationHistoryESIndex(){
        $this->validateCron();
        ini_set('memory_limit','4096M');        
        $ESConnLib = $this->load->library("trackingMIS/elasticSearch/ESConnectionLib");
        $ESClientConn = $ESConnLib->getESServerConnectionWithCredentials();

        /* get sessions from shiksha_assistant_conversations
            .   for last 2 days
            .   isProcessed field not exist
            .   userId > 0
        */

        $dateRange = array(
            'startDate' => date("Y-m-d",strtotime(date("Y-m-d")."- 1 days"))."T00:00:00",
            'endDate'   =>date("Y-m-d\TH:i:s")
        );

        $isDocCount = false;
        $checkSize = 10000;
        $maxLoops = 10;  // how many time this process will run
        $sessionChunkSize = 100;

        while($maxLoops >0){
            $maxLoops--;
            $ESDocIdToDataMapping = array();
            $sessionIds = array();
            $result = $this->_getRowDataFromElasticsearch($ESClientConn, $dateRange, $checkSize, $isDocCount);
            if($result['hits']['total'] >0){
                foreach ($result['hits']['hits'] as $key => $doc) {
                    $ESDocIdToDataMapping[$doc['_source']['sessionId']] = array(
                        'userId' => $doc["_source"]['userId'],
                        'queryTime' => strtotime(str_replace("T", " ", $doc["_source"]['queryTime'])),
                        'docId' => $doc['_id']
                    );
                    $sessionIds[$doc["_source"]['sessionId']] = 1;
                }
                unset($result);
                $sessionIds = array_keys($sessionIds);

                // fetch all data to these session ids
                $sessionIdsChunk = array_chunk($sessionIds, $sessionChunkSize);

                foreach ($sessionIdsChunk as $sessionChunk) {
                    $docToBeUpdated = array();
                    //echo "chunk  session ids : ";_p($sessionChunk);
                    $queryResult = $this->_getDocumentBySessionIds($ESClientConn, $sessionIds);
                    foreach ($queryResult['hits']['hits'] as $key => $doc) {
                        if(strtotime(str_replace("T", " ", $doc["_source"]['queryTime'])) <= $ESDocIdToDataMapping[$doc['_source']['sessionId']]['queryTime']){
                            $docToBeUpdated[$doc['_id']] = $ESDocIdToDataMapping[$doc['_source']['sessionId']]['userId'];
                        }
                    }
                    if(count($docToBeUpdated) >0){
                        // update these document
                        $this->_updateDocInElasticsearch($ESClientConn, $docToBeUpdated);
                    }
                }
            }else{
                break;
            }
        }
    }

    private function _updateDocInElasticsearch($ESClientConn, $docToBeUpdated){
        $indexParams = array();
        $indexParams['index'] = SHIKSHA_ASSISTANT_INDEX_NAME;
        $indexParams['type'] = 'chat';
        $indexParams['body'] = array();
        $cnt =0;

        foreach($docToBeUpdated as $docId => $userId) {
            $indexParams['body'][] = array(
                'update' => array(
                    '_id' => $docId
                )
            );
                    
            $indexParams['body'][] = array(
                'doc_as_upsert' => false,
                'doc' => array(
                    'userId' => $userId,
                    'isProcessed' => true
                )
            );
            
            if($cnt++ == 500) {
                $ESClientConn->bulk($indexParams);
                $indexParams['body'] = array();
            }
        }
        
        if(count($indexParams['body']) > 0) {
            $ESClientConn->bulk($indexParams);
            $indexParams['body'] = array();
        }
    }

    private function _getDocumentBySessionIds($ESClientConn, $sessionIds){
        $params = array();
        $params['index'] = SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH;
        $params['type'] = 'chat';
        $params['body']['size'] = 1000000;
        $params['filter_path'] =array("hits.hits._source","hits.hits._id","hits.total");
        $params['body']['_source'] = array('sessionId' ,'queryTime', 'userId');

        $mustFilters = array();
        $mustFilters[] = array('terms' => array("sessionId" => $sessionIds));

        $params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
        $params['body']['sort']['queryTime']['order'] = "asc";
        //_p($params);die;
        //_p(json_encode($params));die;
        $search = $ESClientConn->search($params);
        //_p($search);die;
        return $search;
    }

    private function _getRowDataFromElasticsearch($ESClientConn,$dateRange,  $checkSize=10000, $isDocCount = true){
        $docCount = $isDocCount == true?0:$checkSize;
        //_p($docCount);_p($dateRange);

        $params = array();
        $params['index'] = SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH;
        $params['type'] = 'chat';
        $params['body']['size'] = $docCount;
        $params['filter_path'] =array("hits.hits._source","hits.hits._id","hits.total");
        $params['body']['_source'] = array('sessionId' ,'queryTime', 'userId');

        $mustFilters = array();
        $mustNotFilters = array();

        $dateRangeFilter = array("range" => array("queryTime" => array("gte" => $dateRange['startDate'], "lte" => $dateRange['endDate'])));

        $mustFilters[] = $dateRangeFilter;

        $mustNotFilters[] = array('exists' => array("field" => "isProcessed"));
        $mustNotFilters[] = array('term' => array("userId" => 0));

        $params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;
        $params['body']['query']['bool']['filter']['bool']['must_not'] = $mustNotFilters;
        $params['body']['sort']['queryTime']['order'] = "asc";
        //_p($params);die;
        //_p(json_encode($params));die;
        $search = $ESClientConn->search($params);
        //_p($search);die;
        return $search;
    }

    function saveSAabValue($isource, $abTestValue){
        //_p($isource);_p($abTestValue);die;
        // save to rabbitmq
        if($isource != "" && $abTestValue != ""){
            $startTime = str_replace(" ", "T", date("Y-m-d H:i:s"));
            $startTimeUTC = convertDateISTtoUTC(str_replace("T", " ", $startTime));
            $time = date("H:i:s",strtotime($startTimeUTC));
            $startTimeUTC = str_replace(" ", "T", $startTimeUTC);

            $data = array(
                "isource"      => $isource,
                "abVarient"    => $abTestValue,
                "sessionId"    => getVisitorSessionId(),
                "startTime"    => $startTime,
                "startTimeUTC" => $startTimeUTC,
                "time"         => $time
            );

            $rabbitmqData = array("trackingType" => "abTest", "data" => json_encode($data));

            try{
                // get rabbit mq client instance
                $this->config->load('amqp');
                $this->load->library("common/jobserver/JobManagerFactory");
                $jobManager = JobManagerFactory::getClientInstance();
                $jobManager->addBackgroundJob("AssistantConversations", $rabbitmqData);
            }
            catch(Exception $e){
                error_log("JOBQException: ".$e->getMessage());
            }
        }else{
            return;
        }
    }
}
