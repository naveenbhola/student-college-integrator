<?php
class PageSpeedScoreCron extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->load->config('PageScoreURL');
	}
	
	function fetchPagesScore()
	{
		$this->validateCron();
		//Load the config and fetch all the URLs
		$shikshaURLs = $this->config->item('SHIKSHA_URLS');

		//Next, we will make CURL calls to google page insight and fetch the score and other values
		//Next, we will store all these values in the DB Table with the corresponding URL, Team name, Page name
		foreach ($shikshaURLs as $url){
			$this->makeGooglePageInsightCall($url);
		}

		//If we want to add any other 3rd party scores, we can add a new function here.
	}
	
	function makeGooglePageInsightCall($url){
		if(isset($url['URL'])){
			$linkAddress = $url['URL'];

			//Make the CURL call to Google for Desktop
			$result = $this->makeCurlCall("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$linkAddress&key=AIzaSyA85Vz0mtsdXUKHKJfSVgUyJh0Qzky0gV0");
			$this->parseAndStore($result, $url, 'desktop');
			
			//Make the CURL call to Google for Mobile
			$result = $this->makeCurlCall("https://www.googleapis.com/pagespeedonline/v2/runPagespeed?url=$linkAddress&strategy=mobile&key=AIzaSyA85Vz0mtsdXUKHKJfSVgUyJh0Qzky0gV0");
			$this->parseAndStore($result, $url, 'mobile');			
		}
		return true;
	}
		
	function parseAndStore($result, $urlDetails, $device){
		$responseArray = json_decode($result, TRUE);
		
		if(isset($responseArray['ruleGroups'])){
			$speedScore = $responseArray['ruleGroups']['SPEED']['score'];
			$usabilityScore = isset($responseArray['ruleGroups']['USABILITY']['score'])?$responseArray['ruleGroups']['USABILITY']['score']:0;
		}
		if(isset($responseArray['pageStats'])){
			$numberOfResources = $responseArray['pageStats']['numberResources'];
			$htmlResponseBytes = $responseArray['pageStats']['htmlResponseBytes'];
			$cssResponseBytes = $responseArray['pageStats']['cssResponseBytes'];
			$imageResponseBytes = $responseArray['pageStats']['imageResponseBytes'];
			$javascriptResponseBytes = $responseArray['pageStats']['javascriptResponseBytes'];
		}
		$ruleResultStr = '';
		$i = 1;
		if(isset($responseArray['formattedResults']['ruleResults'])){
			foreach ($responseArray['formattedResults']['ruleResults'] as $ruleResult){
				$ruleName = $ruleResult['localizedRuleName'];
				$ruleImpact = $ruleResult['ruleImpact'];
				$ruleResultStr .= "$ruleName,$ruleImpact||";
				$i++;
			}
		}
		if(isset($speedScore) && $speedScore!=''){
			$this->storeScoreInDB(array('URL'=>$urlDetails['URL'],
						    'pageName'=>$urlDetails['pageName'],
						    'teamName'=>$urlDetails['teamName'],
						    'thirdParty'=>'googlePageInsight',
						    'device'=>$device,
						    'speedScore'=>$speedScore,
						    'usabilityScore'=>$usabilityScore,
						    'numberOfResources'=>$numberOfResources,
						    'htmlResponseBytes'=>$htmlResponseBytes,
						    'cssResponseBytes'=>$cssResponseBytes,
						    'imageResponseBytes'=>$imageResponseBytes,
						    'javascriptResponseBytes'=>$javascriptResponseBytes,
						    'ruleResult'=>$ruleResultStr
						));
		}
	}

	function makeCurlCall($url){
		return file_get_contents($url);
	}
	
	function storeScoreInDB($dataArray){
		$this->load->model("AppMonitor/appmonitormodel");
		$this->model = new Appmonitormodel();
		$this->model->storePageScore($dataArray);
	}
}
