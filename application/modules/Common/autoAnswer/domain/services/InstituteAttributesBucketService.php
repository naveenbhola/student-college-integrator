<?php

class InstituteAttributesBucketService extends AbstractBucketService{

	private $request;
	private $CI;

	function __construct($request){

		global $bucketsConfig;
		$this->request = $request;
		$this->bucketPattern = $bucketsConfig['institute_attributes'];
		$this->match = false;
		$CI = & get_instance();
		$this->lib = $CI->load->library('autoAnswer/AutoAnsweringLib');
	}

	function isApplicable(){
		
		$this->match = $this->findPattern($this->bucketPattern, $this->request);
		return $this->match;
	}

	function getReponse(){

		$input = $this->request->getRequestText();
		$url = "http://".$this->lib->serverIP.":".$this->lib->serverPort."/CleanDemo/detect";
		$customData['postData']['sentence'] = $input;
		$response = $this->lib->sendCurlRequest($url,$customData);
		echo $response;
	}
}
?>