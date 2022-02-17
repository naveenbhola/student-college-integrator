<?php
class competitorsPageCron extends MX_Controller
{
	public $apiKey = array("AIzaSyCh0VAZuCTMbenULHqGSZZ3Jqnkp8Nl-W0","AIzaSyDldSH85m3JGsNztA2SyetfgRf8OWRiJCY","AIzaSyCXzLfXFmrDktSZmNDRdVx_iWl7mQe92xQ","AIzaSyD-ruJD0TEReMCAJIfZADBK2moqgAlhGkM","AIzaSyAXHhpT8tuRBAJ3zcYEKcToy7BKGPJhzZQ");
	
	function __construct()
	{
		parent::__construct();
		$this->load->config('CompetitorsURL');
	}
	
	function fetchPagesScore()
	{
		$this->validateCron();
		//Load the config and fetch all the URLs
		$shikshaURLs = $this->config->item('URLS');

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
			$indexNo = rand(0,3);
			
			//Make the CURL call to Google for Desktop
			$result = $this->makeCurlCall("https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url=$linkAddress&key=".$this->apiKey[$indexNo]);
			$this->parseAndStore($result, $url, 'desktop');
			
			//Make the CURL call to Google for Mobile
			$result = $this->makeCurlCall("https://www.googleapis.com/pagespeedonline/v4/runPagespeed?url=$linkAddress&strategy=mobile&key=".$this->apiKey[$indexNo]);
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
		if(isset($responseArray['loadingExperience'])){
			$fcp = $responseArray['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['median'];
			$fcpCat = $responseArray['loadingExperience']['metrics']['FIRST_CONTENTFUL_PAINT_MS']['category'];
			$dcl = $responseArray['loadingExperience']['metrics']['DOM_CONTENT_LOADED_EVENT_FIRED_MS']['median'];
			$dclCat = $responseArray['loadingExperience']['metrics']['DOM_CONTENT_LOADED_EVENT_FIRED_MS']['category'];
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
		$serverResponseTime = 100;
		if(isset($responseArray['formattedResults']['ruleResults'])){
			foreach ($responseArray['formattedResults']['ruleResults'] as $key=>$ruleResult){
				$ruleName = $ruleResult['localizedRuleName'];
				$ruleImpact = $ruleResult['ruleImpact'];
				$ruleResultStr .= "$ruleName,$ruleImpact||";
				
				if($key == "MainResourceServerResponseTime"){
					if(isset($ruleResult['summary']['args'][0]['value'])){
						if(strpos($ruleResult['summary']['args'][0]['value'],'sec') > 0){
							$serverResponseTime = explode(" ",$ruleResult['summary']['args'][0]['value']);
							$serverResponseTime = $serverResponseTime[0] * 1000;
						}
					}
				}
				$i++;
			}
		}
		if(isset($speedScore) && $speedScore!=''){
			$this->storeScoreInDB(array('URL'=>$urlDetails['URL'],
						    'pageName'=>$urlDetails['pageName'],
                            'team_name'=>$urlDetails['team_name'],
						    'site'=>$urlDetails['site'],
						    'thirdParty'=>'googlePageInsight',
						    'device'=>$device,
						    'speedScore'=>$speedScore,
						    'usabilityScore'=>$usabilityScore,
						    'numberOfResources'=>$numberOfResources,
						    'htmlResponseBytes'=>$htmlResponseBytes,
						    'cssResponseBytes'=>$cssResponseBytes,
						    'imageResponseBytes'=>$imageResponseBytes,
						    'javascriptResponseBytes'=>$javascriptResponseBytes,
						    'ruleResult'=>$ruleResultStr,
						    'fcp'=>$fcp,
						    'fcpCat'=>$fcpCat,
						    'dcl'=>$dcl,
						    'dclCat'=>$dclCat,
						    'serverResponseTime'=>$serverResponseTime
						));
		}
	}

	function makeCurlCall($url){
		return file_get_contents($url);
	}
	
	function storeScoreInDB($dataArray){
		$this->load->model("AppMonitor/appmonitormodel");
		$this->model = new Appmonitormodel();
		$this->model->storeCompetitorPageScore($dataArray);
	}
	
	function fetchPagesScoreLH()
	{
		$this->validateCron();
		//Load the config and fetch all the URLs
		$shikshaURLs = $this->config->item('URLS');

		//Next, we will make CURL calls to google page insight and fetch the score and other values
		//Next, we will store all these values in the DB Table with the corresponding URL, Team name, Page name
		foreach ($shikshaURLs as $url){
			$this->makeGooglePageInsightCallLH($url);
		}

	}
	
	function makeGooglePageInsightCallLH($url){
		if(isset($url['URL'])){
			$linkAddress = $url['URL'];
			$indexNo = rand(0,1);
			
			//Make the CURL call to Google for Desktop
			$result = $this->makeCurlCall("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$linkAddress&key=".$this->apiKey[$indexNo]);
			$this->parseAndStoreLH($result, $url, 'desktop');
			
			//Make the CURL call to Google for Mobile
			$result = $this->makeCurlCall("https://www.googleapis.com/pagespeedonline/v5/runPagespeed?url=$linkAddress&strategy=mobile&key=".$this->apiKey[$indexNo]);
			$this->parseAndStoreLH($result, $url, 'mobile');			
		}
		return true;
	}
		
	function parseAndStoreLH($result, $urlDetails, $device){
		$responseArray = json_decode($result, TRUE);
		
		if(isset($responseArray['lighthouseResult']['audits'])){
			$speedIndex = round($responseArray['lighthouseResult']['audits']['metrics']['details']['items'][0]['speedIndex']);
			$firstCPUIdle = round($responseArray['lighthouseResult']['audits']['metrics']['details']['items'][0]['firstCPUIdle']);
			$fcp = round($responseArray['lighthouseResult']['audits']['metrics']['details']['items'][0]['firstContentfulPaint']);
			$fmp = round($responseArray['lighthouseResult']['audits']['metrics']['details']['items'][0]['firstMeaningfulPaint']);
			$interactive = round($responseArray['lighthouseResult']['audits']['metrics']['details']['items'][0]['interactive']);
			$inputLatency = round($responseArray['lighthouseResult']['audits']['metrics']['details']['items'][0]['estimatedInputLatency']);

			$mainThreadBreakdown = explode('s',$responseArray['lighthouseResult']['audits']['mainthread-work-breakdown']['displayValue']);
			$mainThreadBreakdown = $mainThreadBreakdown[0] * 1000;
			$domSize = explode('nodes',$responseArray['lighthouseResult']['audits']['dom-size']['displayValue']);
			$domSize = str_replace( ',', '', $domSize[0]);
			$ttfb = explode(' ',$responseArray['lighthouseResult']['audits']['time-to-first-byte']['displayValue']);
			$ttfb = $ttfb[count($ttfb) - 1];
			$ttfb = explode('ms',$ttfb);
			$ttfb = str_replace( ',', '', $ttfb[0]);
			$bootupTime = explode('s',$responseArray['lighthouseResult']['audits']['bootup-time']['displayValue']);
			$bootupTime = $bootupTime[0] * 1000;
			$googleScore = $responseArray['lighthouseResult']['categories']['performance']['score'] * 100;
		}
		$ruleResultStr = '';
		$i = 1;
		$serverResponseTime = 100;
		if(isset($speedIndex) && $speedIndex!=''){
			$this->storeScoreInDBLH(array('URL'=>$urlDetails['URL'],
						    'pageName'=>$urlDetails['pageName'],
						    'team_name'=>$urlDetails['team_name'],
						    'site'=>$urlDetails['site'],
						    'thirdParty'=>'googlePageInsight',
						    'device'=>$device,
						    'speedIndex'=>$speedIndex,
						    'firstCPUIdle'=>$firstCPUIdle,
						    'mainThreadBreakdown'=>$mainThreadBreakdown,
						    'domSize'=>$domSize,
						    'interactive'=>$interactive,
						    'inputLatency'=>$inputLatency,
						    'bootupTime'=>$bootupTime,
						    'googleScore'=>$googleScore,
						    'fcp'=>$fcp,
						    'fmp'=>$fmp,
						    'ttfb'=>$ttfb
						));
		}
	}

	function storeScoreInDBLH($dataArray){
		$this->load->model("AppMonitor/appmonitormodel");
		$this->model = new Appmonitormodel();
		$this->model->storeCompetitorPageScoreLH($dataArray);
	}	

	function generateLighthouseReport(){
		$this->validateCron();
		
		//1. Get the Data from Model
		$this->load->model("AppMonitor/appmonitormodel");
		$this->model = new Appmonitormodel();
		$teamName = 'Domestic';
		$data = $this->model->getDataForMIS($teamName);
		
		//Now, Write this data in an Excel
		if(!empty($data) && count($data)>0) {
			$filename = date(Ymdhis).'data.csv';
			$mime = 'text/x-csv';
			
			//Add First Row
			$csv = '';
			for ($i = 0; $i < 31; $i++){
				switch ($i){
					case 4: $csv .= '"Google Score",'; break;
					case 7: $csv .= '"FCP (in ms)",'; break;
					case 10: $csv .= '"FMP (in ms)",'; break;
					case 13: $csv .= '"Speed Index (in ms)",'; break;
					case 16: $csv .= '"Interactive (in ms)",'; break;
					case 19: $csv .= '"First CPU Idle (in ms)",'; break;
					case 22: $csv .= '"Input Latency (in ms)",'; break;
					case 25: $csv .= '"TTFB (in ms)",'; break;
					case 28: $csv .= '"DOM Size",'; break;
					default : $csv .= '"",'; break;
				}
			}
			$csv .= "\n";
			
			//Add Second Row
			$columnListArray = array();
			$columnListArray[]='S.No.';
			$columnListArray[]='PageName';
			$columnListArray[]='Platform';
			$columnListArray[]='Date';
			$ColumnList = $columnListArray;
			foreach ($ColumnList as $ColumnName){
				$csv .= '"'.$ColumnName.'",';
			}
			for ($i = 0; $i < 9; $i++){
				$csv .= '"Shiksha",';
				$csv .= '"CollegeDunia",';
				$csv .= '"Careers360",';
			}
			$csv .= "\n";
			
			//Convert the data as per Date/Platform/Page/Site
			$finalArray = array();
			foreach ($data as $row){
				$date = $row['DATE(creationDate)'];
				$platform = $row['device'];
				$pageName = $row['pageName'];
				$site = $row['site'];
				$finalArray[$date][$pageName][$platform]['googleScore'][$site] = $row['googleScore'];
				$finalArray[$date][$pageName][$platform]['fcp'][$site] = $row['fcp'];
				$finalArray[$date][$pageName][$platform]['fmp'][$site] = $row['fmp'];
				$finalArray[$date][$pageName][$platform]['speedIndex'][$site] = $row['speedIndex'];
				$finalArray[$date][$pageName][$platform]['firstCPUIdle'][$site] = $row['firstCPUIdle'];
				$finalArray[$date][$pageName][$platform]['interactive'][$site] = $row['interactive'];
				$finalArray[$date][$pageName][$platform]['inputLatency'][$site] = $row['inputLatency'];
				$finalArray[$date][$pageName][$platform]['ttfb'][$site] = $row['ttfb'];
				$finalArray[$date][$pageName][$platform]['domSize'][$site] = $row['domSize'];
			}

			//Now Add the Data in CSV
			$i = 1;
			foreach ($finalArray as $date => $value){					
				$stringVal = '';
				foreach ($value as $pageName => $value1){
					foreach ($value1 as $platform => $value2){
						$pageString = $pageName;
						if($pageName == "UILP - ACP,BIP,SIP"){
							$pageString = "UILP - ACP/BIP/SIP)";
						}
						$stringVal = "$i,$pageString,$platform,".date("j F", strtotime($date));
						foreach ($value2 as $parameter => $value3){
							$stringVal .= ",".round($value3['Shiksha']).",".round($value3['CollegeDunia']).",".round($value3['Careers360']);
						}
						$csv .= $stringVal;
						$csv .= "\n";
						$i++;
					}
				}
			}
		}
		
		//Now mail this Excel to all parties		
		$this->load->library('alerts_client');
		$alertClientObj = new Alerts_client();
		$type_id = time();
		$date = date("d-m-Y");
		$content = "<p>Hi,</p> <p>Please find the attached report for Shiksha Google Lighthouse Report</p><p>- Shiksha Tech.</p>";
		$subject = 'Google Score (LH) Report for last 7 days';
		$email   = array('anisha.jain@shiksha.com');
		$attachmentArray = array();
		for($i=0;$i<count($email);$i++){
			if(count($attachmentArray)==0){
				$attachmentResponse = $alertClientObj->createAttachment("12",$type_id,'COURSE','E-Brochure',$csv,$filename,'text');
				$attachmentId = $attachmentResponse;
				$attachmentArray=array();
				array_push($attachmentArray,$attachmentId);
			}
			$response = $alertClientObj->externalQueueAdd("12","info@shiksha.com",$email[$i],$subject,$content,$contentType="html",'','y',$attachmentArray);
		}
		
	}
	
}