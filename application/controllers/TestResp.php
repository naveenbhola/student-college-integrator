<?php
class TestResp extends MX_Controller {

        function response(){
                error_log("testresponse here");
                $inputJSON = file_get_contents('php://input');
                $input = json_decode($inputJSON, TRUE);
                error_log("testresponse". print_r($input, true));
		error_log("careername : ".$input['queryResult']['parameters']['careername']);
		$careerName = $input['queryResult']['parameters']['careername'];
		$optionSelected = $input['queryResult']['parameters']['optionnselected'];
		$this->load->model("hackmodel");
                $arr = array();
                $agent = $input['session'];//"projects/careerassistant-f7b8e/agent/sessions/1f92d67d-0a10-2e77-61ef-343d28562686/";
		error_log("agent session : ".$agent );

                $responseText = "";
		$outputContexts = $input['queryResult']['outputContexts'];
                if($input['queryResult']['action'] == 'showstreams'){

			if(in_array($optionSelected, array(1,2,'1','2'))){
				$streamData = $this->hackmodel->getAllStreamsData();

				$streamText = "Below are some popular Area of Interests with their average Salaries(from Naukri.com).  \nPlease pick one option : \n\n";
				$counter = 1;
				foreach($streamData as $key=>$streamRow){
					$streamText .= "- ".$streamRow['name']. "(Avg Salary = ".$streamRow['avg_ctc'].") \n";
				}
				$responseText = $streamText;
				$outputContexts[] = array("name" => $agent."/contexts/selectsubstream", 'lifespanCount' => 5);
			}
			else if(in_array($optionSelected, array(3,'3'))){
				$responseText = "What is your Career ?";
				$outputContexts[] = array("name" => $agent."/contexts/careerselectstart", 'lifespanCount' => 5);
			}

                        //$responseText = "Ok. So you are not sure about your area of interest. Here are wonderful data that will help you in chosing one : 1. Engineering ( Average Salary = 5 LPA, ROI = 3)  2. Management (Average Salary = 4 LPA, ROI = 2) ";
                }

		//selectsubstream
                else  if($input['queryResult']['action'] == 'showmoresubstreams'){
                        $responseText = "3. Law ( Average Salary = 5 LPA, ROI = 3)  4. Design (Average Salary = 4 LPA, ROI = 2) ";
                }

		else if($input['queryResult']['action'] == 'choosesubstream' || $input['queryResult']['action'] =='showroles'){

                        //$streamData = $this->hackmodel->getAllStreamsData();

                        $streamText = "";
                        //$counter = 1;
                        //foreach($streamData as $key=>$streamRow){
                        //        $streamText .= "- ".$streamRow['name']. "(Avg Salary = ".$streamRow['avg_ctc'].") \n";
                        //}
                        $responseText = "Ok fine. Please Select a SubStream/Specialization to help us suggest a relavant career for you \n\n";
			$streamname = $this->getStreamNameFromContext($outputContexts);
			$substreams = $this->getHierarchy($streamname);
			$subtext = "";
			foreach($substreams as $subitem){
				$subtext .= " - ".$subitem['name']." ( Avg Salary = ".$subitem['avg_ctc'].")\n";
			}

			$responseText .= $subtext;
			
                        $outputContexts[] = array("name" => $agent."/contexts/subspecselected", 'lifespanCount' => 5);
                        //$responseText = "Ok. So you are not sure about your area of interest. Here are wonderful data that will help you in chosing one : 1. Engineering ( Average Salary = 5 LPA, ROI = 3)  2. Management (Average Salary = 4 LPA, ROI = 2) ";
                }
		//showroles
		
	
		else if($input['queryResult']['action'] == 'substreamselected')	{
			$streamname = $this->getStreamNameFromContext($outputContexts);
			$substreamname = $this->getSubStreamNameFromContext($outputContexts);
		
			error_log("streamname : ".$streamname." and substreamname : ".$substreamname);
			$careerArr = $this->hackmodel->getCareerFromHierarchy($streamname,$substreamname);
			
			$careerText = "Please select a Career and i'll show you the career path and salary ranges :  \n";
			foreach($careerArr as $career){
				$careerText .= "- ".$career."\n";
			}
			
			$responseText = $careerText;
			#$responseText = "select a career";
		}
		else if($input['queryResult']['action'] == 'showcareerdetails' || $input['queryResult']['action'] == 'careerselected'){
			
			$careerPathArr = $this->hackmodel->getCareerPath($careerName);

			$responseText = "About ".$careerName."";
                        $responseText .= "\n------------------------\n";
			$responseText .= strip_tags($careerPathArr['shortDesc']);
			

			$responseText .= "\n\nPath to ".$careerName."";
			$responseText .= "\n------------------------";
			
			$careerPathText = "\n\n";
			$j = 1;
			foreach($careerPathArr['path'] as $key=>$careerPathItem){
				$careerPathlist = array();
				foreach($careerPathItem['steps'] as $stepItem){
					$careerPathlist[] = strip_tags($stepItem['stepTitle'])." [".strip_tags($stepItem['stepDescription'])."]";
				}
				$careerPathText .= "Career Path ".$j++.": ".implode(" > ", $careerPathlist)."\n";
			}

			$responseText .= $careerPathText;	
			if($careerPathArr['minSalary'] || $careerPathArr['maxSalary']){
				$salaryData = "\n\nSalary Data : ";
				if($careerPathArr['minSalary'])	
					$salaryData .= " Min. Salary = ".$careerPathArr['minSalary']."L";
				if($careerPathArr['maxSalary'])
                                        $salaryData .= " Max. Salary = ".$careerPathArr['maxSalary']."L";
			}

			
			$responseText .= $salaryData;

			$responseText .= "\n\n Do you want to See Some Colleges/Courses for this Career ? ";
		  }
		  else if($input['queryResult']['action'] == 'careerColleges' || $input['queryResult']['action'] == 'morecareerColleges'){

			$careerName = $this->getCareerNameFromContext($outputContexts);
			$careerPathArr = $this->hackmodel->getCareerPath($careerName);
	
			$this->load->model("hackmodel");
	                $data = $this->hackmodel->getCareerHierarchy($careerPathArr['careerid']);
        	        $ar = array("streamId" => $data['stream_id'],"specializationId" => $data['specialization_id']);
                	$str = base64_encode(json_encode($ar));
	                $catPageData = $this->docurl("https://apis.shiksha.com/apigateway/commonapi/v1/info/checkIfCategoryPageExistsForFilters?data=".$str);			

			$suggest = "";
			if($catPageData && $catPageData['status'] == 'success'){

				if($input['queryResult']['action'] != 'morecareerColleges') {
					$suggest .= "\n\nColleges for ".$careerName." on Shiksha.com :\n-----------------------------\n\n";
					$suggest .= "Link : ".SHIKSHA_HOME."/".$catPageData['data'];
				}

                                $queryStr = strtok($catPageData['data'],'?');
                                $ar = array("url" => $queryStr, "ctpgRandom" => "");
                                if($data['specialization_id'])
                                        $ar['sp'] = array($data['specialization_id']);
                            
                                 $str = base64_encode(json_encode($ar));
                                
                                $catTuples = $this->docurl("https://apis.shiksha.com/apigateway/categorypageapi/v1/info/getCategoryPageTuple?data=".$str);

				if($input['queryResult']['action'] == 'morecareerColleges')
					$suggest .= " \n\nMore Colleges you can look for : \n\n";
				else
					$suggest .= " \n\nSome Colleges you can look for : \n\n";

				if($catTuples && $catTuples['status'] == 'success' && $catTuples['data']['categoryInstituteTuple']){

					$count = 1;
					$limit = 1;
					if($input['queryResult']['action'] == 'morecareerColleges')
						shuffle($catTuples['data']['categoryInstituteTuple']);
					foreach($catTuples['data']['categoryInstituteTuple'] as $tuple){
						$suggest .= "\n\n ".$count.".) College Name : ".$tuple['name']."\nCourse Name : ".$tuple['courseTupleData']['name']."\nUrl : ".SHIKSHA_HOME."/".$tuple['courseTupleData']['courseUrl'];
						if(($count++) > ($limit) ) break;
					}

					$suggest .= "\n\nDo you want to see more Colleges ?";
				}
				else{
					$suggest .= " \nData Issue. Some mappings needs to be done. Sorry";
				}

				
			}
			$responseText .= $suggest;
			//$responseText = "Ok you choose ".$input['queryResult']['parameters']['careername'];
		}


                $arr['fulfillmentText'] = $responseText;
		if($outputContexts)
			$arr['outputContexts'] = $outputContexts;
                error_log("testresponse response : ". print_r($arr, true));
                echo json_encode($arr);
        }

	function test(){
		$this->load->model("hackmodel");
		$data = $this->hackmodel->getCareerHierarchy(4);
		_p($data);die;
		$careerPathArr = $this->hackmodel->getCareerFromHierarchy('design','fashion design');
		_p( $careerPathArr);
		$careerPathArr = $this->hackmodel->getCareerFromHierarchy('engineering');
		_p($careerPathArr);
	}

/*	public function getHierarchy($streamName = ''){
		$this->load->model('HackModel');
		$this->hackmodel = new HackModel();	
		$streamId = $this->hackmodel->getEntityIdByName('stream' , $streamName);

		$this->load->builder('ListingBaseBuilder','listingBase');
        $builder = new ListingBaseBuilder;
        $repo = $builder->getHierarchyRepository();
        $result = array();
		$streamData = array_values($repo->getSubstreamSpecializationByStreamId($streamId,1));
		if(!empty($streamData)){
			$streamData = $streamData[0];
			if(!empty($streamData['substreams'])){
				foreach ($streamData['substreams'] as $key => $value) {
					$temp = array();
					$temp['name'] = $value['name'];
					$temp['avg_ctc'] = 1;
					$result[] = $temp;
				}
			}else if(!empty($streamData['specializations'])){
				foreach ($streamData['specializations'] as $key => $value) {
					$temp = array();
					$temp['name'] = $value['name'];
					$temp['avg_ctc'] = 1;
					$result[] = $temp;
				}
			}
		}
		return $result;

	}
*/
	function getStreamNameFromContext($outputContexts){
		$streamName = "";
		foreach($outputContexts as $value){
			if($value['parameters']['streamname']){
				$streamName = $value['parameters']['streamname'];
				break;
			}
		}

		return $streamName;
	}

	function getSubStreamNameFromContext($outputContexts){
                $substreamName = "";
                foreach($outputContexts as $value){
                        if($value['parameters']['usersubstreamnew']){
                                $substreamName = $value['parameters']['usersubstreamnew'];
                                break;
                        }
                }

                return $substreamName;
        }

	function getCareerNameFromContext($outputContexts){
                $careername = "";
                foreach($outputContexts as $value){
                        if($value['parameters']['careername']){
                                $careername = $value['parameters']['careername'];
                                break;
                        }
                }

                return $careername;
        }

	public function getHierarchy($streamName = ''){
//		$streamName = "Engineering";
		$this->load->model('HackModel');
		$this->hackmodel = new HackModel();	
		$streamId = $this->hackmodel->getEntityIdByName('stream' , $streamName);


		$this->load->builder('ListingBaseBuilder','listingBase');
        $builder = new ListingBaseBuilder;
        $repo = $builder->getHierarchyRepository();
        $result = array();
		$streamData = array_values($repo->getSubstreamSpecializationByStreamId($streamId,1));
		
		$substreamIds = array();
		$specIds = array();
		if(!empty($streamData)){
			$streamData = $streamData[0];
			if(!empty($streamData['substreams'])){
				foreach ($streamData['substreams'] as $key => $value) {
					$temp = array();
					$temp['name'] = $value['name'];
					// $temp['avg_ctc'] = 1;
					$result[$value['id']] = $temp;
					$substreamIds[] = $value['id'];
				}
			}else if(!empty($streamData['specializations'])){
				foreach ($streamData['specializations'] as $key => $value) {
					$temp = array();
					$temp['name'] = $value['name'];
					$temp['avg_ctc'] = 1;
					$result[$value['id']] = $temp;
					$specIds[] = $value['id'];
				}
			}
		}

		$this->initDB('read');

		$this->dbHandle = $this->hackmodel->initDB();	
		if(!empty($substreamIds)){
			$sql = "SELECT AVG(avg_ctc) as avg_ctc, stream_id, substream_id from test_naukri_functional_salary_data_custom WHERE stream_id = $streamId and substream_id IN(".implode(',', $substreamIds).") group by stream_id, substream_id";
			$query = $this->dbHandle->query($sql);
			$result1 = $query->result_array();
			$result2 = array();
			foreach($result1 as $value){
				$result2[$value['substream_id']] = round($value['avg_ctc'],2);
			}
			
			foreach ($result as $key => $value) {
				if(!empty($result2[$key])){
					$result[$key]['avg_ctc'] = $result2[$key];
				}else{
					unset($result[$key]);
				}
			}	
		}else if(!empty($specIds)){

			$sql = "SELECT AVG(avg_ctc) as avg_ctc, stream_id, specialization_id from test_naukri_functional_salary_data_custom WHERE stream_id = $streamId and specialization_id IN(".implode(',', $specIds).") group by stream_id, specialization_id";
			$query = $this->dbHandle->query($sql);
			$result1 = $query->result_array();
			$result2 = array();
			foreach($result1 as $value){
				$result2[$value['specialization_id']] = round($value['avg_ctc'],2);
			}
			
			foreach ($result as $key => $value) {
				if(!empty($result2[$key])){
					$result[$key]['avg_ctc'] = $result2[$key];
				}else{
					unset($result[$key]);
				}
			}	

		}
	
		return $result;

	}

	function testCurl(){

		$this->load->model("hackmodel");
		$data = $this->hackmodel->getCareerHierarchy(4);		
		$arr = array("streamId" => $data['stream_id'],"specializationId" => $data['specialization_id']);
		$str = base64_encode(json_encode($arr));
		$dat = $this->docurl("https://apis.shiksha.com/apigateway/commonapi/v1/info/checkIfCategoryPageExistsForFilters?data=".$str);		

		

		_p($dat);

		if($dat){
			//{"url":"/business-management-studies/colleges/colleges-bangalore","ctpgRandom":""}

			$queryStr = strtok($dat['data'],'?');
			_p($queryStr);
			$ar = array("url" => $queryStr, "ctpgRandom" => "");
			if($data['specialization_id'])
				$ar['sp'] = array($data['specialization_id']);
			_p($ar);
			 $str = base64_encode(json_encode($ar));
			_p($str);
                $data = $this->docurl("https://apis.shiksha.com/apigateway/categorypageapi/v1/info/getCategoryPageTuple?data=".$str);
		shuffle($data['data']['categoryInstituteTuple']);
		_p($data['data']['categoryInstituteTuple']);
		}
	}

	function docurl($api){
		  $ch = curl_init();
  $timeout = 5;
  curl_setopt($ch,CURLOPT_URL,$api);
  curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
  curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
  $data = curl_exec($ch);
  curl_close($ch);
  $data = json_decode($data, true);
  return $data;

	}

	function getChatResponse(){
		 error_log("getChatResponse :::  ".print_r($_REQUEST, true));
		$input = json_decode(file_get_contents('php://input'), true);

		$senderId = $input['entry'][0]['messaging'][0]['sender']['id'];
        	// Get the returned message

		// In case of ACK, do not respond to prevent infinite loop
		if(empty($input['entry'][0]['messaging'][0]['message']))
			return;
	        $message = $input['entry'][0]['messaging'][0]['message']['text'];

		if($message == 'what is the eligibility for CAT' ) {
			return;
		}


		error_log("getChatResponse ::: input :: ".print_r($input, true));
		 if($_GET['hub_mode'] == 'subscribe')
			 echo $_GET['hub_challenge'];
		else{
			// $this->sendChatResponse($senderId, $message);
			   $this->sendAutoanswerResponse($senderId, $message);
		}
	}

	function sendAutoanswerResponse($senderId, $message){
	


		error_log("getChatResponse ::: Stage 1");	
		$url = 'https://apis.shiksha.com/autoanswer/api/v1/query';
                //Initiate cURL.
                $ch = curl_init($url);

                //Tell cURL that we want to send a POST request.
                curl_setopt($ch, CURLOPT_POST, 1);
                curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
                curl_setopt($ch, CURLOPT_POSTFIELDS, "question=".urlencode($message));//Does the question paper pattern of JEE Mains get repeated?");
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'AUTHREQUEST: INFOEDGE_SHIKSHA'));
                $result = curl_exec($ch);

                $statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		$finalResponse = "Something went wrong";
                if($statusCode == 200) {
			error_log("getChatResponse ::: Stage 2"); 
                        $result = json_decode($result, true);
                        $finalResponse = $result['data']['answer'];
                }

		if(empty($finalResponse)){
			$finalResponse = "No Answer.";
		}

		error_log("getChatResponse ::: Stage 3 : ".$finalResponse); 
	
		$replyData = array();
                $replyData['recipient']['id'] = $senderId;
                //$replyData['message']['text'] = substr($finalResponse, 0 , 2000);
                $replyData['message'] = $this->getFormatedResponse($result);

		error_log("senderId : ".$senderId);

                #$replyData['message']['quick_replies'][] = array("content_type" => "text", "title"=>"Sport", "payload" => "sport");
                #$replyData['message']['quick_replies'][] = array("content_type" => "location");
                #$replyData['message']['quick_replies'][] = array("content_type" => "user_phone_number");
                #$replyData['message']['quick_replies'][] = array("content_type" => "user_email");
                //echo json_encode($replyData);

                $token = "EAAHzQ3YZBZBBcBAPkmwvaQl2dQ59uK6iLzmsrfkWZBWXRolPo0W8kZAoqY7ZAp6CL4S3vD0f5ecUqShiAWMBU49XCZAczoBvAnWne5JD40A4nlIGKUPaGEh1UNBCRbhkGIEQIfjGlaMbkG7RfLPjrIJZBSAA9cbLR7jBSGRNwOTS7DJKYrS60mQ";
                //"EAAL2whU3CdMBAHVCPtugy95YoqqmZB2peLGtg8wETfyQi7PPUU44gZAgNl28cOQZBx17YSCZATFo3jWMZBgOwvIaFdIQQtDWMDzlGmkADslEISm5905xk6iLZBc4xByQHhkaiPHxI1ACCaZCTIEoNSB0OZCdqZAOOW6onZAGQDlPQOIQZDZD";
                // prepare reply
                $url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$token;
                //Initiate cURL.
                $ch = curl_init($url);

                //Tell cURL that we want to send a POST request.
                curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
                //Attach our encoded JSON string to the POST fields.
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($replyData));
                //Set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                //Execute the request but first check if the message is not empty.
                if (!empty($message)) {
                            $result = curl_exec($ch);
                }	
		header("HTTP/1.1 200 OK");

		error_log("getChatResponse ::: final response :  ".print_r($replyData, true));
	}

	function sendChatResponse($senderId, $message){
	
		$api = "https://api.dialogflow.com/v1/query?v=20170712";	
		$requestData = array();
		$requestData['lang'] = 'en';
		$requestData['sessionId'] = "12345";
		$requestData['timezone'] = "Asia/Kathmandu";
		$requestData['query'] = $message;

		error_log("getChatResponse Stage 1");
		$ch = curl_init();
		$timeout = 5;
		curl_setopt($ch,CURLOPT_URL,$api);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);
		curl_setopt($ch,CURLOPT_CONNECTTIMEOUT,$timeout);
		curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch, CURLOPT_POSTFIELDS,json_encode($requestData));
		curl_setopt($ch, CURLOPT_HTTPHEADER, array(
		    'Authorization: Bearer 0eebd8adb6af4ee9a6164e87babb2dc9',
		    'Content-Type: application/json'
		));

		$data = curl_exec($ch);
		curl_close($ch);
		$data = json_decode($data, true);

		error_log("getChatResponse ::: output :: ".print_r($data, true));

		if(!empty($data['result']['fulfillment']['speech']))
			$reply = $data['result']['fulfillment']['speech'];
		else
			$reply = $data['result']['fulfillment']['messages'][0]['speech'];
		
		$replyData = array();
		$replyData['recipient']['id'] = $senderId;
		$replyData['message']['text'] = "hello";//$reply;

		#$replyData['message']['quick_replies'][] = array("content_type" => "text", "title"=>"Sport", "payload" => "sport");
		#$replyData['message']['quick_replies'][] = array("content_type" => "location");
		#$replyData['message']['quick_replies'][] = array("content_type" => "user_phone_number");
		#$replyData['message']['quick_replies'][] = array("content_type" => "user_email");
		//echo json_encode($replyData);

		$token = "EAAHzQ3YZBZBBcBAPkmwvaQl2dQ59uK6iLzmsrfkWZBWXRolPo0W8kZAoqY7ZAp6CL4S3vD0f5ecUqShiAWMBU49XCZAczoBvAnWne5JD40A4nlIGKUPaGEh1UNBCRbhkGIEQIfjGlaMbkG7RfLPjrIJZBSAA9cbLR7jBSGRNwOTS7DJKYrS60mQ";
		//"EAAL2whU3CdMBAHVCPtugy95YoqqmZB2peLGtg8wETfyQi7PPUU44gZAgNl28cOQZBx17YSCZATFo3jWMZBgOwvIaFdIQQtDWMDzlGmkADslEISm5905xk6iLZBc4xByQHhkaiPHxI1ACCaZCTIEoNSB0OZCdqZAOOW6onZAGQDlPQOIQZDZD";
		// prepare reply
        	$url = 'https://graph.facebook.com/v2.6/me/messages?access_token='.$token;
	        //Initiate cURL.
        	$ch = curl_init($url);
		
		//Tell cURL that we want to send a POST request.
	        curl_setopt($ch, CURLOPT_POST, 1);
		//Attach our encoded JSON string to the POST fields.
	        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($replyData));
		//Set the content type to application/json
	        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
		//Execute the request but first check if the message is not empty.
	        if (!empty($message)) {
		            $result = curl_exec($ch);
	        }

	/*	
		sleep(2);

		$ch = curl_init($url);
		$replyData['message']['text'] = "response sent";
                //Tell cURL that we want to send a POST request.
                curl_setopt($ch, CURLOPT_POST, 1);
                //Attach our encoded JSON string to the POST fields.
                curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($replyData));
                //Set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json'));
                //Execute the request but first check if the message is not empty.
                if (!empty($message)) {
                            $result = curl_exec($ch);
                }
	
	*/		
		//$replyData['message']['text'] = "response sent";
		//curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($replyData));
		//$result = curl_exec($ch);

		error_log("getChatResponse ::: reply :: ".$reply);
	}

	function getFormatedResponse($response){
		
		$result = array();

		
		$finalResponse = $response['data']['answer'];
		if(empty($finalResponse)){
                        $finalResponse = "No Answer.";
                }

		$result['text'] = substr($finalResponse, 0 , 2000);

		return $result;

		$responseArr = $response['data']['responses'];
		
		################################################
		
		$predictedTripletsList = $response['data']['predictedTripletsList'];

		error_log("getChatResponse ::: predictedTripletsList :: ".print_r($predictedTripletsList, true));
		$opinionFactual = "";
		$attribute = "";
		$entity = "";

		if(is_array($predictedTripletsList) && !empty($predictedTripletsList)){
			$opinionFactual = $predictedTripletsList[0]['opinionFactual'];
			$attribute = $predictedTripletsList[0]['attribute'];
		}

		
		error_log("getChatResponse ::: opinionFactual :: ".$opinionFactual);
		error_log("getChatResponse ::: attribute :: ".$attribute);
				
		if($opinionFactual == 'factual' && !empty($responseArr) && in_array($attribute, array('ranking'))){
			
			if($attribute == 'ranking') {
			
				$elements = array();
				$elements['title'] = "Top ".$responseArr[0]['urls'][0]['title']." Colleges";
				//$elements['subtitle'] = "Click on Below Link";
				$elements['buttons'][] = array("type" => "web_url", "url" => $responseArr[0]['urls'][0]['url'], "title" => "Show Rankings");
				
	
				$payload = array();
				$payload['template_type'] = 'generic';
				$payload['elements'][] = $elements;
				$result['attachment']['type'] = 'template';
				$result['attachment']['payload'] = $payload;

			}

		} else{
			$result['text'] = substr($finalResponse, 0 , 2000);
		}

		return $result;
	}

	function testdata(){
	
		$url = 'https://apis.shiksha.com/autoanswer/api/v1/query';
                //Initiate cURL.
                $ch = curl_init($url);

                //Tell cURL that we want to send a POST request.
                curl_setopt($ch, CURLOPT_POST, 1);
		curl_setopt($ch,CURLOPT_RETURNTRANSFER,1);


                //Attach our encoded JSON string to the POST fields.
                curl_setopt($ch, CURLOPT_POSTFIELDS, "question=Does the question paper pattern of JEE Mains get repeated?");
                //Set the content type to application/json
                curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/x-www-form-urlencoded', 'AUTHREQUEST: INFOEDGE_SHIKSHA'));
                //Execute the request but first check if the message is not empty.
                $result = curl_exec($ch);

		$statusCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
		if($statusCode == 200) {
			$result = json_decode($result, true);
			echo "<pre>";
			_p($result['data']['answer']);
		}

	}	
}
?>

