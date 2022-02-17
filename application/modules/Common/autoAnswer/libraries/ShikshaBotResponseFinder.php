<?php

class ShikshaBotResponseFinder
{
		
	function __construct(){
		$this->CI=& get_instance();
		$this->htmlGeneratorLib = $this->CI->load->library('HtmlGenerator');
		$this->CI->load->config('shikshaBotMain');
		$this->CI->load->library("autoAnswer/Response");
		$this->CI->load->library("autoAnswer/Validations");
		$this->CI->load->library('autoAnswer/DataPopulators');
		$this->CI->load->library("autoAnswer/processor/InstituteLib");
		$this->response = new Response();
		$this->validationLib = new Validations();			
		$this->instituteLib = new InstituteLib();
		$this->dataPopulators = new DataPopulators();
	}

	public function findResponse($request,$postRequest){	
		

		$whichCase = $request->getWhichCase();

		$this->parseAndSetDataFromResponse($request,$this->response,$postRequest);

		if($this->response->getIsErrorInResponse()){
			$this->response->setErrorMessage($this->htmlGeneratorLib->generateHTMLForBotTextResponse($this->response->getErrorMessage()));
			$this->response->setIsCustomDataForTextRequest(true);
			return json_encode($this->response);
		}


		if($whichCase == 'startChat'){
			$supportData = $this->CI->config->item('MAIN_ATTR');
			$html = $this->htmlGeneratorLib->generateHtml($supportData);
			$this->response->setOptionResponses($html);
			$this->response->setIsOptionsResponse(true);				
			$textResponsesArray[] = 'Please tell us how can I help you?';
			$textResponsesArray[] = 'Please choose among the below options';
			$this->response->setFinalTextResponse($this->htmlGeneratorLib->generateHTMLForBotTextResponse($textResponsesArray));
			
			$this->response->setDisableSendButton(true);
			$this->response->setDisableTextBox(true);
			$this->response->setOptionsPosition('top');
			$this->response->setAttachClickhandler(true);
			$this->response->setAttachOnClass(true);
			$this->response->setNextRequestType('mainAttrInput');
			$customData['mainAttrSet'] = true;
			$this->response->setCustomData($customData);
			return json_encode($this->response);
		}
		else{
			if($request->getMainAttrSet() === false || true) {
				if(isset($postRequest['mainAttrSet']) && $postRequest['mainAttrSet'] === true){
					$request->setMainAttrSet(true);
				}	
			}

			if(/*$request->getName() == "" || */$request->getRequestType() == "mainAttrInput"){
				$textResponsesArray[] = "While we are working on your request, may I know your name? Please provide your full name like Sachin Verma";
				$this->response->setFinalTextResponse($this->htmlGeneratorLib->generateHTMLForBotTextResponse($textResponsesArray));
				$this->response->setIsTextResponse(true);
				$this->response->setNextRequestType('nameInput');
				$customData['validateResponse'] = "validateName";
				$this->response->setIsCustomDataForTextRequest(true);
				$this->response->setCustomData($customData);
				echo $this->dataPopulators->json_encode_private($this->response);	
			}

			else if(/*$request->getEmail() == "" || */$request->getRequestType() == "nameInput"){
				$textResponsesArray[] = "and Email address like sachin.verma@demo.com";
				$this->response->setFinalTextResponse($this->htmlGeneratorLib->generateHTMLForBotTextResponse($textResponsesArray));
				$this->response->setIsTextResponse(true);
				$this->response->setNextRequestType('emailInput');
				$customData['validateResponse'] = "validateEmail";
				$this->response->setIsCustomDataForTextRequest(true);
				$this->response->setCustomData($customData);
				echo $this->dataPopulators->json_encode_private($this->response);	
			}

			else if($request->getRequestType() == "emailInput"){
				$textResponsesArray = $this->findTextResponseBasedOnAttr($request,$this->response);
				echo $this->dataPopulators->json_encode_private($this->response);	
			}

			else if($request->getRequestType() == "dataInput"){
				$textResponsesArray = $this->findTextResponseBasedOnAttr($request,$this->response);
				/*_p($this->response); */
				echo $this->dataPopulators->json_encode_private($this->response);	
			}

			else if($request->getRequestType() == "phoneInput"){
				$textResponsesArray = $this->findTextResponseBasedOnAttr($request,$this->response);
				json_encode($this->response);
			}
		}

	}

	public function findTextResponseBasedOnAttr($request,$response){

		$mainAttrValue = $request->getMainAttrValue();
		$textResponse = array();
		switch ($mainAttrValue) {
			case 'institute':
				$textResponse = $this->instituteLib->findResponseForInstituteAttr($request,$response);
				break;
			default:
				# code...
				break;
		}

		$response->setNextRequestType("dataInput");
		$response->setFinalTextResponse($this->htmlGeneratorLib->generateHTMLForBotTextResponse($textResponse));
		$response->setIsTextResponse(true);
	}

	public function parseAndSetDataFromResponse($request,$response,$postRequest){
		$result = $this->validateRequest($request,$response,$postRequest);
		error_log("requestType == ".$postRequest['requestType']);
		if($result  === true){
			$requestType = $request->getRequestType();
			switch ($requestType) {
				case 'mainAttrInput':
					$txt = $postRequest['optionsData'];
					//$this->CI->session->set_userdata('mainAttrValue',$txt);
					//$this->CI->session->set_userdata('mainAttrSet',true);
					$_SESSION['mainAttrValue'] = $txt;
					$_SESSION['mainAttrSet'] = true;
					break;

				case 'nameInput':	
					$txt = $request->getText();
					$_SESSION['name'] = $txt;
					$request->setName($txt);
					$response->setNextRequestType('emailInput');
					/*$isNameValidated = $this->validationLib->validateName($txt);
					if(!$isNameValidated){
						$response->setIsErrorInResponse(true);
						$response->setErrorMessage("Name is Incorrect. Please enter the proper name like Sachin Verma");
						$response->setNextRequestType('nameInput');
						$finalResult = false;
					}else{
						//$this->CI->session->set_userdata('name', $txt);
						$_SESSION['name'] = $txt;
						$request->setName($txt);
						$response->setNextRequestType('emailInput');
					}*/
					break;

				case 'emailInput':
					$txt = $request->getText();
					$_SESSION['email'] = $txt;
					$request->setEmail($txt);
					$response->setNextRequestType('dataInput');
					/*$isEmailValidated = $this->validationLib->validateEmail($txt);
					if(!$isEmailValidated){
						$response->setIsErrorInResponse(true);
						$response->setErrorMessage("Email is Incorrect. Please enter the proper email like sachin.verma@demo.com");
						$response->setNextRequestType('emailInput');
					}else{
						//$this->CI->session->set_userdata('email', $txt);
						$_SESSION['email'] = $txt;
						$request->setEmail($txt);
						$response->setNextRequestType('dataInput');
					}*/

				default:
					break;
			}
		}else{
			$response->setIsErrorInResponse(true);
			$response->setErrorMessage($result);
			$response->setNextRequestType($request->getRequestType());
			$this->populateResponseFromPost($response);
		}
		return $finalResult;
	}

	public function validateRequest($request,$response,$postRequest){

		if(isset($_POST['validateResponse'])){
			$fName = $_POST['validateResponse'];
			$text = $request->getText();
			$returnValue = $this->validationLib->$fName($text);			
			return $returnValue;

		}
		elseif (isset($_POST['otpVerificationForInstitute'])) {
			$fName = $_POST['validateResponse'];
		}
		else{
			return true;	
		}
	}

	public function populateResponseFromPost($response){
		$customData = $response->getCustomData();
		foreach ($_POST as $key => $value) {
			if($key == "requestType" || $key == "text") continue;
			$customData[$key] = $value;
		}
		$response->setCustomData($customData);
	}

	

}
?>
