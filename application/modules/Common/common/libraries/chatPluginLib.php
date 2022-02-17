<?php
class chatPluginLib{
	function __construct() {
		$this->CI                    = & get_instance();
		ini_set("memory_limit","1024M");
    }

    private function _prepareConversationHistoryView($totalDoc, $conversationHistoryDoc){
    	$data = array();

    	foreach ($conversationHistoryDoc as $key => $doc) {
    		$response = $this->prepareAnswerView($doc['_source']['apiResponseCode'], $doc['_source']['apiResponseData'], false);
            $response['queryTime'] = base64_encode(strtotime(str_replace("T", " ", $doc['_source']['queryTime'])));
    		$data[] = array(
    			"userQuery" => $doc['_source']['userQuery'],
    			"response" => $response
    		);
    	}
		return $data;
    }

    public function prepareAnswerView($culrRetcode, $result, $isJsonData = true, $requestDetails){
    	// check if any error occured
    	$response = array();
    	$response['error'] = 0;
    	// $replyStartingTexts = array("Here you go", "Here's what I found", "Ok. I found this:");
    	$promptTitle = "People are also asking :";
        $promptTextMessage = "";
        $promptType = "";
        $isSmallTalkResponse = 0;
        $recommendations = array(
        	array("text" => "When is Jee Mains", "value" => "When is Jee Mains"),
             array("text" => "Fees of IIM A", "value" => "Fees of IIM A"), 
             array("text" => "Facilities at IIT Delhi", "value" => "Facilities at IIT Delhi"), 
             array("text" => "top mba colleges in pune", "value" => "top mba colleges in pune"),
             array("text" => "exam pattern of cat exam", "value" => "exam pattern of cat exam")
         );

    	if($culrRetcode >= 400){
        	$response['error'] = 1;
        	$response['answer'] = "Sorry. Something went wrong.";
        }
        else{
        	if($isJsonData == true){
        		$result = json_decode($result, true);	
        	}

            if($result['status'] != "success" || empty($result['data']) ){
            	$response['error'] = 1;
        		$response['answer'] = "Sorry. No Answer Found.<br/>Can you please re-phrase your query ?";
            }else{
                $response['answer'] =  $this->CI->load->view("chatplugin/richCard", $result,true);
                if(strpos($response['answer'], "touple-block") !== false){
                    $response['addClass'] = "tuples";
                }
                if(strpos($response['answer'], "accordian-box") !== false){
                    $response['addClass'] = "accordian";
                }
                // $key1 = array_rand($replyStartingTexts);
                // $startConversation = $replyStartingTexts[$key1];
                // $response['startConversation'] = $startConversation; // conversationStartMsg

                // error_log("sapresponsedata : ".print_r($result, true));

                if(!empty($result['data']['state']) && !empty($result['data']['state']['currentIntent'])){
                    if($result['data']['state']['currentIntent']['attribute'] == 'smalltalk'){
                        $isSmallTalkResponse = 1;
                    }
                }                

                if(!empty($result['data']['promptResponse']) && !empty($result['data']['promptResponse']['promptCards'])){
                    $recommendations = array();   
                    foreach ($result['data']['promptResponse']['promptCards'] as $promptCards) {
                        $recomRow = array();
                        
                        $recomRow["text"] = $promptCards['name'];
                        $recomRow["value"] = $promptCards['id']."::".$promptCards['type'];
                        if($promptCards['type'] == 'tag_closure'){
                            $recomRow['cssClass'] = "closure-intent";
                            $response['closureIntent'] = $recomRow;
                        }
                        else{
                            $recommendations[] = $recomRow;
                        }
                    }
                    $promptTitle = $result['data']['promptResponse']['promptSelectMessage'];
                    $promptTextMessage = $result['data']['promptResponse']['promptTextMessage'];
                    $promptType = $result['data']['promptResponse']['promptType'];
                }
                $response['prompts'] = $recommendations;
                $response['promptTitle'] = $promptTitle;
                $response['promptTextMessage'] = $promptTextMessage;
                
                if($promptType == 'disambiguation')
                    $response['promptClass'] = "disambiguity";

            	if(
                    !empty($result['data']['responses']) || 
                    (!empty($result['data']['promptResponse']) && $result['data']['promptResponse']['promptType'] == 'disambiguation')) {
            	
                    $response['startConversation'] = $result['data']['conversationStartMsg']; 
                    foreach ($result['data']['responses'] as $resp) {
                            if(!empty($resp['links'])){
                                    $response['redirecturl'] = $resp['links'][0]['url'];
                                    // $response['redirecturl'] = str_replace("+", " ", $response['redirecturl']);            
                                    break;
                        }
                    }	
                }
                else if(!empty($requestDetails['tagSelected']) && $requestDetails['tagSelected'] ='tag_closure'){

                    $response['answer'] = "Ok.";
                }
                else{
                    $response['error'] = 1;
                    $response['answer'] = "Sorry. No Answer Found.";
                    unset($response['prompts']);
                }


                if($isSmallTalkResponse){
                    unset($response['startConversation']);
                    unset($response['prompts']);
                }
            }

        }
        return $response;
    }

    public function getUserConversationHistory($lastDocTime, $size, $order, $userId){
    	$historyData = null;
    	// for now we are using visitor id to get user conversion history
    	$ESConnLib = $this->CI->load->library("trackingMIS/elasticSearch/ESConnectionLib");
    	$ESClient = $ESConnLib->getESServerConnectionWithCredentials(true);

    	$sessionId = getVisitorSessionId();

    	// Elasticsearch Query
        $inputArray = array(
            'sessionId'   => $sessionId,
            'lastDocTime' => $lastDocTime,
            'size'        => $size,
            'order'       => $order,
            'userId'      => $userId
        );
    	$ESQuery = $this->_prepareESQuery($inputArray);
        if($ESQuery == ""){
            return $historyData;
        }
    	

        try {
            $search = $ESClient->search($ESQuery);
        }
        catch(Exception $e) {
            error_log("ElasticException:: ".$e->getMessage());
            mail('praveen.singhal@99acres.com,romil.goel@shiksha.com', 'timeout while quering assistant index', print_r($e->getMessage(),true));
        }



    	//_p($search['hits']['hits']);die;
        if($order == "asc"){
            usort($search['hits']['hits'],function ($item1, $item2) {
                return strtotime(str_replace("T", " ", $item1['_source']['queryTime'])) > strtotime(str_replace("T", " ", $item2['_source']['queryTime'])) ? 1 : -1;
            });    
        }
    	
    	if($search['hits']['total'] >0){
    		$historyData =  $this->_prepareConversationHistoryView($search['hits']['total'], $search['hits']['hits']);	
    	}
    	return $historyData;
    }

    private function _prepareESQuery($inputArray){
        $params = '';
        //_p($inputArray);
        if(!isset($inputArray['size']) || !is_numeric($inputArray['size'])){
            $inputArray['size'] = 10;
        }

        if(!isset($inputArray['sessionId']) || $inputArray['sessionId'] == ""){
            $inputArray['sessionId'] = getVisitorSessionId();//getVisitorId();
        }

        if(!isset($inputArray['order']) || $inputArray['order'] == ""){
            $inputArray['order'] = "asc";
        }

        if(!isset($inputArray['lastDocTime']) || $inputArray['lastDocTime'] == ""){
            $inputArray['lastDocTime'] = "";
        }else{
            $inputArray['lastDocTime'] = str_replace(" ", "T", date("Y-m-d H:i:s",base64_decode($inputArray['lastDocTime'])));
            if (!preg_match("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])T(0[1-9]|[1][0-9]|2[0-3]):(0[1-9]|[1-5][0-9]):(0[1-9]|[1-5][0-9])$/",$inputArray['lastDocTime'])) {
                return $params;
            }
        }

        if($inputArray['userId'] == ""){
            $inputArray['userId'] =0;
        }
        //_p($inputArray); die;

    	$params = array();
    	$params['index'] = SHIKSHA_ASSISTANT_INDEX_NAME_REALTIME_SEARCH;
    	$params['type'] = 'chat';
    	$params['body']['size'] = $inputArray['size'];
    	$params['filter_path'] =array("hits.hits._source","hits.total");
    	$params['body']['_source'] = array('queryTime', 'userQuery', 'apiResponseData', 'apiResponseCode');

    	$shouldFilters = array();
    	$shouldFilters[] = array('term' => array("sessionId" => $inputArray['sessionId']));
        
        // for now
        // if($inputArray['userId'] >0){
        //     $shouldFilters[] = array('term' => array("userId" => $inputArray['userId']));    
        // }
        
        if($inputArray['lastDocTime'] != ""){
            $mustFilters[] = array('range' => array("queryTime" => array("lt" => $inputArray['lastDocTime'])));
        }

        if(count($shouldFilters) >0){
            $params['body']['query']['bool']['filter']['bool']['should'] = $shouldFilters;
        }
        if(count($mustFilters) >0){
            $params['body']['query']['bool']['filter']['bool']['must'] = $mustFilters;    
        }
        
    	$params['body']['sort']['queryTime']['order'] = $inputArray['order'];
    	//_p(json_encode($params));die;
    	return $params;
    }

}
