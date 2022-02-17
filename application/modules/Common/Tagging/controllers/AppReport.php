<?php

class AppReport extends MX_Controller
{

	private $redisLib = null;

	private function _init(){
		$this->redisLib = PredisLibrary::getInstance();
	}
	function showUserHomeFeed(){

		$userId = $this->input->post("userId");
		$type   = $this->input->post("feedType");
		$isAjax = $this->input->post("isAjax");

		$this->load->library(array('v1/AnACommonLib'));
        $this->anaCommonLib = new AnACommonLib();

        if($isAjax){

        	if(empty($userId) || !is_numeric($userId)){
				die("Please enter a valid user id");
			}

			$userDetails  = $this->anaCommonLib->getUserDetails($userId);
			$homepageData = $this->anaCommonLib->getHomepageData($userId, 0, 50, $type, 1);
			// _p($userDetails);
			// _p($homepageData);die;
			$displayData = array();
			$displayData['userDetails'] = $userDetails;
			$displayData['homepageData'] = $homepageData;
			$this->load->view("homeFeedReportData", $displayData);
		}
		else{
			$this->load->view("homefeedReport", $displayData);
		}

	}

	function showAPIPerformanceReport($date = ""){
		die();
		if(empty($date)){
			$date = date("d-m-Y");
		}
		$date = $this->_getDate($date);

		$this->taggingmodel = $this->load->model("taggingmodel");
		$reportData         = $this->taggingmodel->getAPIResponseReport($date);
		$minuteWiseData     = $this->taggingmodel->getMinutewiseAPIData($date);

		$displayData                         = array();

		$this->_formatChartData($minuteWiseData, $displayData);
		
		$displayData['reportData']           = $reportData;
		$displayData['period']               = $period;
		$displayData['date']                 = $date;
		$displayData['minuteWiseData']       = $minuteWiseData;

		$this->load->view("apiPerformaceReport", $displayData);
		
	}

	function _formatChartData($minuteWiseData, &$displayData){

		$minuteArr = array();
		$avgProcessingtimeArr = array();

		foreach ($minuteWiseData as $hour => $minuteWiseHitsData) {
			foreach ($minuteWiseHitsData as $minute => $d) {
				$minuteArr[] = array($hour.":".str_pad($minute*30, 2, "0"), intVal($d['cnt']), "<p class='tooltip'>At ".$hour.":".str_pad($minute*30, 2, "0")." <br/> ".intVal($d['cnt'])." Hits</p>");
				$avgProcessingtimeArr[] = array($hour.":".str_pad($minute*30, 2, "0"), intVal($d['avg_time']), "<p class='tooltip'>At ".$hour.":".str_pad($minute*30, 2, "0")." <br/> ".intVal($d['avg_time'])." sec</p>");
			}
		}
		$minuteArr            = json_encode($minuteArr);
		$avgProcessingtimeArr = json_encode($avgProcessingtimeArr);

		$displayData['minuteArr']            = $minuteArr;
		$displayData['avgProcessingtimeArr'] = $avgProcessingtimeArr;
	}

	function _getDate($date){

		// $date = date("Y-m-d ", strtotime("-".($dateId-1)." days"));
		$date = date("Y-m-d", strtotime($date));
		return $date;
	}

	function getMethodData(){

		$controller = $this->input->post("controller");
		$method = $this->input->post("method");
		$date = $this->input->post("date");

		$date = date("Y-m-d", strtotime($date));

		$this->taggingmodel = $this->load->model("taggingmodel");
		$minuteWiseData     = $this->taggingmodel->getMinutewiseAPIData($date, $controller, $method);

		$displayData = array();
		$this->_formatChartData($minuteWiseData, $displayData);
		echo json_encode($displayData);
	}

	/*
        function showAppStatReport($date = ""){

		$displayData            = array();

		//Active Users Data
		$deviceData = $this->_callFunctionWithCache("appActiveUsersData","getDeviceData");
                $displayData['deviceArray'] = $this->_formatStatChartData($deviceData, 'Devices');

		//API Hits Data
		$apiData = $this->_callFunctionWithCache("appAPIHitsData","getApiData");
                $displayData['apiArr'] = $this->_formatStatChartData($apiData, 'API Hits');

		//APP Reg Data
		$d1 = $this->_callFunctionWithCache("appRegistrationData","appRegData");
                $displayData['appRegArray'] = $this->_formatStatChartData($d1, 'Registrations');
		$displayData['appRegTotal'] = $this->_generateTotal($d1);

		//Total Reg Data
		$d2 = $this->_callFunctionWithCache("totalRegistrationData","totalRegData");
                $displayData['totalRegArr'] = $this->_formatStatChartData($d2, 'Registrations');
		$displayData['totalRegTotal'] = $this->_generateTotal($d2);

		//App Questions Data
		$appQuestionData = $this->_callFunctionWithCache("appQuestionsData","getAppQuestionsData");
                $displayData['appQuestionArray'] = $this->_formatStatChartData($appQuestionData, 'Questions');
		$displayData['appQTotal'] = $this->_generateTotal($appQuestionData);

		//Total Questions Data		
		$totalQuestionData = $this->_callFunctionWithCache("totalQuestionsData","getTotalQuestionsData");
                $displayData['totalQuestionArr'] = $this->_formatStatChartData($totalQuestionData, 'Questions');
		$displayData['totalQTotal'] = $this->_generateTotal($totalQuestionData);
		
		//App Answers Data
		$appAnswerData = $this->_callFunctionWithCache("appAnswersData","getAppAnswerData");
                $displayData['appAnswerArray'] = $this->_formatStatChartData($appAnswerData, 'Answers');
		$displayData['appATotal'] = $this->_generateTotal($appAnswerData);

		//Total Answers Data
		$totalAnswerData = $this->_callFunctionWithCache("totalAnswersData","getTotalAnswerData");
                $displayData['totalAnswerArr'] = $this->_formatStatChartData($totalAnswerData, 'Answers');
		$displayData['totalATotal'] = $this->_generateTotal($totalAnswerData);

		//Tag followers Data
		$d1 = $this->_callFunctionWithCache("tagFollowersData","getTagFollowData");
                $displayData['tagFollowArray'] = $this->_formatStatChartData($d1, 'Tag followers');
		$displayData['tagFollowTotal'] = $this->_generateTotal($d1);

		//User Followers data
		$d2 = $this->_callFunctionWithCache("userFollowersData","getUserFollowData");
                $displayData['userFollowArr'] = $this->_formatStatChartData($d2, 'User followers');
		$displayData['userFollowTotal'] = $this->_generateTotal($d2);

		//Performance Data
		$d1 = $this->_callFunctionWithCache("apiPerformanceData","performanceData");
                $displayData['performanceArray'] = $this->_formatStatChartData($d1, 'Server Processing time');
		
		//Sharing Data
		$d1 = $this->_callFunctionWithCache("appSharingData","getSharingData");
                $displayData['sharingArray'] = $this->_formatStatChartData($d1, 'Shares');
		$displayData['sharingTotal'] = $this->_generateTotal($d1);

		//Answer Rate
		$d1 = $this->_callFunctionWithCache("shikshaAnswerRateData","getAnswerRateOnShiksha");
		$sameDayData = $d1[0];
                $displayData['sameDayArray'] = $this->_formatStatChartData($sameDayData, '%');
		$twoDayData = $d1[1];
                $displayData['twoDayArray'] = $this->_formatStatChartData($twoDayData, '%');

		//Install Data
		$d1 = $this->_callFunctionWithCache("appInstallData","getInstallData");
                $displayData['installArray'] = $this->_formatStatChartData($d1, 'Installs');
		$displayData['installTotal'] = $this->_generateTotal($d1);
		
                $this->load->view("appStatsReport", $displayData);
        }
	*/

        function _formatStatChartData($data, $label){
                $dataArr = array();
                foreach ($data as $dataVal) {
		    $newDate = date("d M", strtotime($dataVal['creationDate']));
                    $dataArr[] = array($newDate, intVal($dataVal['dataCount']), "<p class='tooltip'>".$dataVal['dataCount']." ".$label." on ".$newDate."</p>");
                }
                $dataArr            = json_encode($dataArr);
                return $dataArr;
        }

	function _generateTotal($data){
		$total = 0;
                foreach ($data as $dataVal) {
			$total += intVal($dataVal['dataCount']);
                }
                return $total;		
	}

	function _callFunctionWithCache($key, $functionName){
                $this->statsmodel       = $this->load->model("statsmodel");
                $this->load->library('cacheLib');
		$cacheLib = new cacheLib;

		
		$dataFetched = $cacheLib->get($key);
		if($dataFetched != 'ERROR_READING_CACHE'){
			$data = $dataFetched;
		}
		else{
			$date = date("Y-m-d");
			$date = strtotime("-30 days",strtotime($date));
			$date = date ( 'Y-m-j' , $date );
			$data      = $this->statsmodel->$functionName($date);
			$cacheLib->store($key, $data, 21600);
		}
		return $data;
	}

	function redisMonitor(){
		die;
		$this->load->view('redisMonitor');
	}

	function fetchKeyData(){
		$action = $this->input->post('action');
		$key = trim($this->input->post('key'));

		if($key == ""){
			echo "-1"; // Blank Key Error
		}
		else {
			$type = $this->fetchKeyType($key);
		}
	}


	// threadContributors:thread:3163637 => set
	//userHomeFeed:user:123517 => hash
	//threadQualityProperty:thread:3345196 => hash
	//threadTags:thread:3234283 > zset
	//userHomeFeedStaticCuratedBucket => list
	//computeThreadQualityQueue => set
	//userFollowsTag:user:44290 => zset
	//userHomeFeed:user:91223 = hash
	//userNewHomeFeed:user:47225 = zset
	//tagFollowers:tag:334 => set
	//tagsTopContributors:15 => set
	//tagUnfollowers:tag:123456 => set
	//related:tags:165940 = string
	//userFollowsTagExplicitly:user:5135650 = set
	//userBackFillHomeFeed:user:5135650 = hash
	//threadQuality:thread:1234 => string
	//http://svntrac.infoedge.com:8080/Shiksha/changeset/74968

	function fetchKeyType($redisKey='related:tags:165940') {

		//$this->redisLib = PredisLibrary::getInstance();		
		$this->_init();
		$redisKey = $this->input->post('key');
		$keyMapping = array(
					'hash' => 'Hash',
					'set' => 'Set',
					'zset' => 'Sorted Set',
					'list' => 'List',
					'string' => 'Simple String',
					'none' => 'Key not exists'
					);
		

		if($this->redisLib->isRedisAlive()){
			$typeObj = $this->redisLib->checkTypeOfKey($redisKey);
			$typeArr = (array)$typeObj;
			foreach ($typeArr as $key => $value) {
				$type = $value;
				break;
			}
			$displayData = $this->fetchDataBasedOnType($redisKey,$type);
			$displayData['keyMapping'] = $keyMapping;
			$displayData['ttl'] = $this->fetchTTL($redisKey);

			echo $this->load->view('redisKeyData',$displayData);
		} else {

		}

	}


	function fetchDataBasedOnType($key,$type){

		$finalData = array();
		$finalData['isKeyExist'] = true;
		$finalData['type'] = $type;
		$finalData['key'] = $key;

		$MAX_SIZE = 300;
		$finalData['MAX_SIZE'] = $MAX_SIZE;
		switch ($type) {
			case 'none':
				$finalData['isKeyExist'] = false;
				$data = array();
				break;

			case 'hash':
				$cnt = $this->redisLib->getNumberOfMembersInHash($key);
				$finalData['cnt'] = $cnt;
				if($cnt > $MAX_SIZE){
					$data = array();
					$finalData['sizeError'] = 1;
				} else {
					$data = $this->redisLib->getAllMembersOfHashWithValue($key);	
				}				
				break;


			case 'set':
				$cnt = $this->redisLib->getMembersCountOfSet($key);
				$finalData['cnt'] = $cnt;
				if($cnt > $MAX_SIZE){
					$data = array();
					$finalData['sizeError'] = 1;
				} else {
					$data = $this->redisLib->getMembersOfSet($key);	
				}
				
				break;


			case 'zset':
				$cnt = $this->redisLib->getNumberOfMembersInSortedSet($key);
				$finalData['cnt'] = $cnt;

				if($cnt > $MAX_SIZE){
					$data = array();
					$finalData['sizeError'] = 1;
				} else {
					$data = $this->redisLib->getMembersInSortedSet($key,0,-1);	
				}
				
				break;


			case 'list':
				$data = $this->redisLib->getMembersOFList($key,0,-1);
				break;


			case 'string':
				$data = $this->redisLib->getMemberOfString($key);
				
				$unserializeData = $this->isSerialized($data);
				$jsonData = $this->isJson($data);

				if($unserializeData !== false){
					$finalData['isSerialized'] = true;
					$data = $unserializeData;
				} else if($jsonData !== false){
					$finalData['isJson'] = true;
					$data = $jsonData;
				}

				unset($unserializeData);
				unset($jsonData);
				break;
		}
		$finalData['data'] = $data;
		return $finalData;
	}

	function isJson($string) {
		$data = json_decode($string);
		if(json_last_error() == JSON_ERROR_NONE)
			return $string;
		else 
			return true;
	}

	function isSerialized($data){

		$data = @unserialize($data);
		if ($data !== false) {
			return $data;
		} 
		else {
			return false;
		}
	}

	function expireRedisKey(){

		$this->_init();
		$keysToExpire = trim($this->input->post('keyToExpire'));
		$timeInSeconds = trim($this->input->post('timeInSeconds'));

		if($keysToExpire == ""){
			echo "-1"; // Blank Key Err
		}
		if(!is_numeric($timeInSeconds)){
			echo "-2"; // Invalid Time 
		}

		if($timeInSeconds > 0){
			$resp = $this->redisLib->expireKey($keysToExpire,$timeInSeconds);
			if($resp){
				echo "1";
			} else {
				echo "-1";
			}
		} else {
			$resp = $this->redisLib->deleteKey(array($keysToExpire));
		}

	}

	function fetchTTL($key){
		$this->_init();
		$res = $this->redisLib->getKeysTTL($key);
		return $res;
	}

	function removeTTLFromKey($isAjax=true){
		$this->_init();
		$key = trim($this->input->post('redisKey'));

		if($key == ""){

			if($isAjax){
				echo "Blank Key";	
			}			
			return -1;
		}
		$res = $this->redisLib->removeTTLFromKey($key);

		if($isAjax){
			if($res){
				echo "TTl Removed.";
			} else {
				echo "Some error Occured.";
			}
		} else {
			return $res;
		}

	}


}
?>

