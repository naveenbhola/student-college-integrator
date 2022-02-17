<?php

class AutoAnswerRequest{

	private $requestText = "";

	function __construct($initList = array()){

		$this->requestText = "";

		if($initList['text'])
			$this->requestText = $initList['text'];
	}

	function setRequestText($text){
		$this->requestText = $text;
	}

	function getRequestText(){
		return $this->requestText;
	}
}
?>