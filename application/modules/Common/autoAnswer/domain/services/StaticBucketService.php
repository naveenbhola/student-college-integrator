<?php

class StaticBucketService extends AbstractBucketService{

	private $request;

	function __construct($request){

		global $bucketsConfig;
		$this->request = $request;
		$this->bucketPattern = $bucketsConfig['static'];
		$this->match = false;
	}

	function isApplicable(){
		
		$this->match = $this->findPattern($this->bucketPattern, $this->request);
		return $this->match;
	}

	function getReponse(){

		if($this->match !== false){
			return $this->bucketPattern[$this->match]['response'];
		}
		else{
			return NO_RESPONSE;
		}
	}
}
?>