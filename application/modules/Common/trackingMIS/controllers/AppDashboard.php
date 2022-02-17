<?php
class AppDashboard extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
		$this->trackingLib = $this->load->library('trackingMIS/trackingMISCommonLib');
	}
	private function _loadDependecies() {
		$data['userDataArray'] = reset($this->trackingLib->checkValidUser());
		$data['misSource'] = "App";
		$this->load->config('appDashboardConfig');		
		$data['leftMenuArray'] = $this->config->item("leftMenuArray");		
		$data['charts'] = $this->config->item('charts');
		return $data;
	}
	function showReport()
	{
		$data = $this->_loadDependecies();
		$data['teamName'] = 'Mobile App';
		$data['resultsToShow'] = $this->getDataForReport();
		$this->load->view('appMetrics', $data);
	}
	function getDataForReport()
	{
		//Active Users Data
		$deviceData = $this->_callFunctionWithCache("appActiveUsersData","getDeviceData");
		$displayData['activeUsers']['data'] = json_encode($this->prepareDataForLineChart($deviceData));
		
        //$displayData['deviceArray'] = $this->_formatStatChartData($deviceData, 'Devices');
        

		//API Hits Data
		$apiData = $this->_callFunctionWithCache("appAPIHitsData","getApiData");
		$displayData['apiHits']['data'] = json_encode($this->prepareDataForLineChart($apiData));
		
                //$displayData['apiArr'] = $this->_formatStatChartData($apiData, 'API Hits');

		//APP Reg Data
		$d1 = $this->_callFunctionWithCache("appRegistrationData","appRegData");
		$displayData['appReg']['data'] = json_encode($this->prepareDataForLineChart($d1));
                //$displayData['appRegArray'] = $this->_formatStatChartData($d1, 'Registrations');
		$displayData['appReg']['totalCount'] = $this->_generateTotal($d1);

		//Total Reg Data
		$d2 = $this->_callFunctionWithCache("totalRegistrationData","totalRegData");
                //$displayData['totalRegArr'] = $this->_formatStatChartData($d2, 'Registrations');
		$displayData['totalReg']['data'] = json_encode($this->prepareDataForLineChart($d2));
		$displayData['totalReg']['totalCount'] = $this->_generateTotal($d2);

		//App Questions Data
		$appQuestionData = $this->_callFunctionWithCache("appQuestionsData","getAppQuestionsData");
                //$displayData['appQuestionArray'] = $this->_formatStatChartData($appQuestionData, 'Questions');
		$displayData['appQuestions']['data'] = json_encode($this->prepareDataForLineChart($appQuestionData));
		$displayData['appQuestions']['totalCount'] = $this->_generateTotal($appQuestionData);

		//Total Questions Data		
		$totalQuestionData = $this->_callFunctionWithCache("totalQuestionsData","getTotalQuestionsData");
                //$displayData['totalQuestionArr'] = $this->_formatStatChartData($totalQuestionData, 'Questions');
		$displayData['totalQuestions']['data'] = json_encode($this->prepareDataForLineChart($totalQuestionData));
		$displayData['totalQuestions']['totalCount'] = $this->_generateTotal($totalQuestionData);
		
		//App Answers Data
		$appAnswerData = $this->_callFunctionWithCache("appAnswersData","getAppAnswerData");
                //$displayData['appAnswerArray'] = $this->_formatStatChartData($appAnswerData, 'Answers');
		$displayData['appAnswers']['data'] = json_encode($this->prepareDataForLineChart($appAnswerData));
		$displayData['appAnswers']['totalCount'] = $this->_generateTotal($appAnswerData);

		//Total Answers Data
		$totalAnswerData = $this->_callFunctionWithCache("totalAnswersData","getTotalAnswerData");
                //$displayData['totalAnswerArr'] = $this->_formatStatChartData($totalAnswerData, 'Answers');
		$displayData['totalAnswers']['data'] = json_encode($this->prepareDataForLineChart($totalAnswerData));
		$displayData['totalAnswers']['totalCount'] = $this->_generateTotal($totalAnswerData);

		//Tag followers Data
		$d1 = $this->_callFunctionWithCache("tagFollowersData","getTagFollowData");
                //$displayData['tagFollowArray'] = $this->_formatStatChartData($d1, 'Tag followers');
		$displayData['tagFollowers']['data'] = json_encode($this->prepareDataForLineChart($d1));
		$displayData['tagFollowers']['totalCount'] = $this->_generateTotal($d1);

		//User Followers data
		$d2 = $this->_callFunctionWithCache("userFollowersData","getUserFollowData");
                //$displayData['userFollowArr'] = $this->_formatStatChartData($d2, 'User followers');
		$displayData['userFollowers']['data'] = json_encode($this->prepareDataForLineChart($d2));
		$displayData['userFollowers']['totalCount'] = $this->_generateTotal($d2);

		//Performance Data
		$d1 = $this->_callFunctionWithCache("apiPerformanceData","performanceData");
                //$displayData['performanceArray'] = $this->_formatStatChartData($d1, 'Server Processing time');
		$displayData['performance']['data'] = json_encode($this->prepareDataForLineChart($d1));
		
		//Sharing Data
		$d1 = $this->_callFunctionWithCache("appSharingData","getSharingData");
                //$displayData['sharingArray'] = $this->_formatStatChartData($d1, 'Shares');
		$displayData['appShare']['data'] = json_encode($this->prepareDataForLineChart($d1));
		$displayData['appShare']['totalCount'] = $this->_generateTotal($d1);

		//Answer Rate
		$d1 = $this->_callFunctionWithCache("shikshaAnswerRateData","getAnswerRateOnShiksha");
		$sameDayData = $d1[0];
              //  $displayData['sameDayArray'] = $this->_formatStatChartData($sameDayData, '%');
		$displayData['answer24']['data'] = json_encode($this->prepareDataForLineChart($sameDayData));
		$twoDayData = $d1[1];
                //$displayData['twoDayArray'] = $this->_formatStatChartData($twoDayData, '%');
		$displayData['answer48']['data'] = json_encode($this->prepareDataForLineChart($twoDayData));
		//Install Data
		$d1 = $this->_callFunctionWithCache("appInstallData","getInstallData");
                //$displayData['installArray'] = $this->_formatStatChartData($d1, 'Installs');
		$displayData['appInstall']['data'] = json_encode($this->prepareDataForLineChart($d1));
		$displayData['appInstall']['totalCount'] = $this->_generateTotal($d1);
		return $displayData;
	}
	function _callFunctionWithCache($key, $functionName){
                $this->statsmodel       = $this->load->model("trackingMIS/statsmodel");
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

	function _generateTotal($data){
		$total = 0;
                foreach ($data as $dataVal) {
			$total += intVal($dataVal['dataCount']);
                }
                return $total;		
	}
	function _formatStatChartData($data, $label){
	            $dataArr = array();
	            foreach ($data as $dataVal) {
		    $newDate = date("d M", strtotime($dataVal['creationDate']));
	                $dataArr[] = array($newDate, intVal($dataVal['dataCount']), "<p class='tooltip'>".$dataVal['dataCount']." ".$label." on ".$newDate."</p>");
	            }
	            $dataArr            = json_encode($dataArr);
	            return $dataArr;
	    }
	function prepareDataForLineChart($result)
    {
        $i=0;
        
        foreach($result as $key => $value)
        {
            $lineChartData[$i++]=array($value['creationDate'],$value['dataCount']);
        }
        return $lineChartData;

    }
}

?>