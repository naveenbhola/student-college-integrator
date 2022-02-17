<?php

require 'AppMonitorAbstract.php';

class competitorsPage extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function displayPageScores($moduleName = ''){
                $displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_COMPSCORE;
		$displayData['reportType'] = 'trends';
		
                global $ENT_EE_MODULES;
                $modules = $ENT_EE_MODULES;
                $selectedModule = "";
                if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
                        $selectedModule = $moduleName;
                }
                else {
                        $selectedModule = "shiksha";
                }

                $displayData['selectedModule'] = $selectedModule;
                $this->getModulesLinks($displayData, '/AppMonitor/competitorsPage', 'displayPageScores');
		
		
		$pageScores = $this->model->getLatestCompetitorPageScore();
		if(isset($pageScores[0])){
			$displayData['pageScores'] = $this->parseResult($pageScores[0]);
			$avgScoresWeek = $this->parseResult($pageScores[1]);
			$avgScoresLastWeek = $this->parseResult($pageScores[2]);
			$displayData['weekStats'] = $this->compareWeeksData($avgScoresWeek, $avgScoresLastWeek);
		}
		
                if($selectedModule == 'shiksha') {
                        $modules[] = 'Overall';
                }
                else {
                        $modules = array($selectedModule);
                }
                $displayData['modules'] = $modules;
                $displayData['moduleName'] = $selectedModule;
		$displayData['platformFilter'] = isset($_REQUEST['platform']) ? $_REQUEST['platform'] : 'all';
		$displayData['fieldFilter'] = isset($_REQUEST['field'])? $_REQUEST['field'] : 'all';
		$displayData['teamFilter'] = isset($_REQUEST['team_name'])? $_REQUEST['team_name'] : 'all';
		//_p($displayData);
		$this->load->view("AppMonitor/compScore/pageScore", $displayData);				
	}
	
	function parseResult($resultArray){
		$returnArray = array();
		foreach ($resultArray as $result){
			$pageName = $result['pageName'];
			$team_name=$result['team_name'];
			$site = $result['site'];
			$device = $result['device'];
			
			if($site == 'Shiksha'){
				$returnArray[$team_name][$pageName]['URL'] = $result['URL'];
			}
			$returnArray[$team_name][$pageName][$device]['speedScore'][$site] = round($result['speedScore']);
			$returnArray[$team_name][$pageName][$device]['fcp'][$site] = intval($result['fcpVal']);
			$returnArray[$team_name][$pageName][$device]['fcpCat'][$site] = $result['fcpCat'];
			$returnArray[$team_name][$pageName][$device]['dcl'][$site] = intval($result['dclVal']);
			$returnArray[$team_name][$pageName][$device]['dclCat'][$site] = $result['dclCat'];
			$returnArray[$team_name][$pageName][$device]['srtVal'][$site] = intval($result['srtVal']);
			$returnArray[$team_name][$pageName][$device]['htmlBytes'][$site] = intval($result['htmlBytes']/1024);
			$returnArray[$team_name][$pageName][$device]['jsBytes'][$site] = intval($result['jsBytes']/1024);
			$returnArray[$team_name][$pageName][$device]['cssBytes'][$site] = intval($result['cssBytes']/1024);
		}
		//_p($returnArray);
		return $returnArray;
	}
	
	function compareWeeksData($avgScoresWeek, $avgScoresLastWeek){
		$returnArray = array();
		if(is_array($avgScoresWeek) && is_array($avgScoresLastWeek)){
			foreach ($avgScoresWeek as $team => $tValue){
				foreach ($tValue as $page => $pValue){
					foreach ($pValue as $device => $dValue){
						foreach ($dValue as $stat => $sValue){
							foreach ($sValue as $site => $siteValue){
								$returnArray[$team][$page][$device][$stat][$site] = 'None';
								
								//Get Last Week value
								$lastWeekValue = $avgScoresLastWeek[$team][$page][$device][$stat][$site];
								$percentageDiff = (($lastWeekValue - $siteValue) / $lastWeekValue) * 100;
								
								//Check now if the value difference this week and last week is greater than 5% or less than 5%
								if($percentageDiff >= 5){
									$returnArray[$team][$page][$device][$stat][$site] = 'Down';
								}
								else if($percentageDiff <= -5){
									$returnArray[$team][$page][$device][$stat][$site] = 'Up';
								}
							}
						}
					}
				}
			}
		}
		return $returnArray;
	}
	
	function displayPageDetails($pageName = '', $device = 'desktop'){
		
                $displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_COMPSCORE;
		$displayData['reportType'] = 'trends';
		
                global $ENT_EE_MODULES;
                $modules = $ENT_EE_MODULES;
                $selectedModule = "";
                if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
                        $selectedModule = $moduleName;
                }
                else {
                        $selectedModule = "shiksha";
                }

                $displayData['selectedModule'] = $selectedModule;
                $this->getModulesLinks($displayData, '/AppMonitor/competitorsPage', 'displayPageScores');

                $toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
                $fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-90 day"));		
		$displayData['pageName'] = $pageName = str_replace('-',' ',$pageName);
		$displayData['device'] = $device;
		
		$pageDetails = $this->model->getCompetitorPageDetails($pageName, $device, $fromDate, $toDate);	
		$displayData['pageDetails'] = $pageDetails;
		
		$pageDetails = $this->parseDateResults($pageDetails);

		$displayData['siteList'] = $this->_getSiteList($pageDetails);
		
		$displayData['speedScoreDetails'] = $this->_formatSizeData($pageDetails, 'speedScore', $displayData['siteList']);
		$displayData['srtDetails'] = $this->_formatSizeData($pageDetails, 'srt', $displayData['siteList']);
		$displayData['fcpDetails'] = $this->_formatSizeData($pageDetails, 'fcp', $displayData['siteList']);
		$displayData['dclDetails'] = $this->_formatSizeData($pageDetails, 'dcl', $displayData['siteList']);
		$displayData['httpRequestsDetails'] = $this->_formatSizeData($pageDetails, 'numberOfResources', $displayData['siteList']);
		$displayData['htmlSizeDetails'] = $this->_formatSizeData($pageDetails, 'htmlBytes', $displayData['siteList']);
		$displayData['jsSizeDetails'] = $this->_formatSizeData($pageDetails, 'javascriptResponseBytes', $displayData['siteList']);
		$displayData['cssSizeDetails'] = $this->_formatSizeData($pageDetails, 'cssResponseBytes', $displayData['siteList']);
		
                if($selectedModule == 'shiksha') {
                        $modules[] = 'Overall';
                }
                else {
                        $modules = array($selectedModule);
                }
                $displayData['modules'] = $modules;
                $displayData['moduleName'] = $selectedModule;

                $displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
                $displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		
		$this->load->view("AppMonitor/compScore/pageDetails", $displayData);				
	}

	function parseDateResults($resultArray){
		$returnArray = array();
		foreach ($resultArray as $result){
			$date = substr($result['creationDate'],0,10);
			$site = $result['site'];
			$returnArray[$date]['speedScore'][$site] = round($result['speedScore']);
			$returnArray[$date]['htmlBytes'][$site] = intval($result['htmlResponseBytes']/1024);
			$returnArray[$date]['fcp'][$site] = round($result['fcp']);
			$returnArray[$date]['dcl'][$site] = round($result['dcl']);
			$returnArray[$date]['srt'][$site] = round($result['serverResponseTime']);
			$returnArray[$date]['numberOfResources'][$site] = round($result['numberOfResources']);
			$returnArray[$date]['cssResponseBytes'][$site] = intval($result['cssResponseBytes']/1024);
			$returnArray[$date]['javascriptResponseBytes'][$site] = intval($result['javascriptResponseBytes']/1024);
		}
		return $returnArray;
	}
	
	function _formatSizeData($data, $entity, $siteList){
                $dataArr = array();
                foreach ($data as $key=>$dataVal) {
			$newDate = date("d M,Y", strtotime($key));
			$row = array($newDate);
			foreach ($siteList as $siteName){
				array_push($row, $dataVal[$entity][$siteName]);
			}
			$dataArr[] = $row;
                }
                $dataArr            = json_encode($dataArr);
                return $dataArr;		
	}
	
	function _getSiteList($data){
                $dataArr = array();
                foreach ($data as $key=>$dataVal) {
			foreach ($dataVal as $key1 => $entity){
				foreach ($entity as $site => $value){
					$dataArr[] = $site;
				}
			}
                }
                return array_unique($dataArr);				
	}
	
	function remove_outliers($array) {
	    if(count($array) == 0) {
	      return $array;
	    }
	    $ret = array();
	    $mean = array_sum($array)/count($array);
	    $stddev = stats_standard_deviation($array);
	    $outlier = 3 * $stddev;
	    foreach($array as $a) {
		if(!abs($a - $mean) > $outlier) {
		    $ret[] = $a;
		}
	    }
	    return $ret;
	}	
	
	function displayPageScoresLH($moduleName = ''){
        $displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_COMPSCORELH;
		$displayData['reportType'] = 'trendsLH';
		
        global $ENT_EE_MODULES;
        $modules = $ENT_EE_MODULES;
        $selectedModule = "";
        if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
                $selectedModule = $moduleName;
        }
        else {
                $selectedModule = "shiksha";
        }

        $displayData['selectedModule'] = $selectedModule;
        $this->getModulesLinks($displayData, '/AppMonitor/competitorsPage', 'displayPageScores');
		
		
		$pageScores = $this->model->getLatestCompetitorPageScoreLH();
		if(isset($pageScores[0])){
			$displayData['pageScores'] = $this->parseResultLH($pageScores[0]);
			$avgScoresWeek = $this->parseResultLH($pageScores[1]);
			$avgScoresLastWeek = $this->parseResultLH($pageScores[2]);
			$displayData['weekStats'] = $this->compareWeeksData($avgScoresWeek, $avgScoresLastWeek);
		}
		
        if($selectedModule == 'shiksha') {
            $modules[] = 'Overall';
        }
        else {
            $modules = array($selectedModule);
        }
        $displayData['modules'] = $modules;
        $displayData['moduleName'] = $selectedModule;
		$displayData['platformFilter'] = isset($_REQUEST['platform']) ? $_REQUEST['platform'] : 'all';
		$displayData['fieldFilter'] = isset($_REQUEST['field'])? $_REQUEST['field'] : 'all';
		$displayData['teamFilter'] = isset($_REQUEST['team_name'])? $_REQUEST['team_name'] : 'all';
		$displayData['subteamFilter'] = isset($_REQUEST['subteam'])? $_REQUEST['subteam'] : 'all';

		$this->load->config('CompetitorsURL');
		$displayData['teamsLinking'] = $this->config->item('teams');
		$this->load->view("AppMonitor/compScore/pageScoreLighthouse", $displayData);				
	}
	
	function parseResultLH($resultArray){
		$returnArray = array();
		foreach ($resultArray as $result){
			$pageName = $result['pageName'];
			$team_name=$result['team_name'];
			$site = $result['site'];
			$device = $result['device'];
			
			if($site == 'Shiksha'){
				$returnArray[$team_name][$pageName]['URL'] = $result['URL'];
			}
			$returnArray[$team_name][$pageName][$device]['googleScore'][$site] = intval($result['googleScore']);
			$returnArray[$team_name][$pageName][$device]['fcp'][$site] = intval($result['fcp']);
			$returnArray[$team_name][$pageName][$device]['fmp'][$site] = intval($result['fmp']);
			$returnArray[$team_name][$pageName][$device]['speedIndex'][$site] = intval($result['speedIndex']);
			$returnArray[$team_name][$pageName][$device]['firstCPUIdle'][$site] = intval($result['firstCPUIdle']);
			$returnArray[$team_name][$pageName][$device]['mainThreadBreakdown'][$site] = intval($result['mainThreadBreakdown']);
			$returnArray[$team_name][$pageName][$device]['interactive'][$site] = intval($result['interactive']);
			$returnArray[$team_name][$pageName][$device]['inputLatency'][$site] = intval($result['inputLatency']);
			$returnArray[$team_name][$pageName][$device]['bootupTime'][$site] = intval($result['bootupTime']);
			$returnArray[$team_name][$pageName][$device]['ttfb'][$site] = intval($result['ttfb']);
			$returnArray[$team_name][$pageName][$device]['domSize'][$site] = intval($result['domSize']);
		}
		return $returnArray;
	}
	
	function displayPageDetailsLH($pageName = '', $device = 'desktop'){
		
        $displayData = array();
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_COMPSCORELH;
		$displayData['reportType'] = 'trendsLH';
		
        global $ENT_EE_MODULES;
        $modules = $ENT_EE_MODULES;
        $selectedModule = "";
        if(array_key_exists($moduleName, $ENT_EE_MODULES)) {
            $selectedModule = $moduleName;
        }
        else {
            $selectedModule = "shiksha";
        }

        $displayData['selectedModule'] = $selectedModule;
        $this->getModulesLinks($displayData, '/AppMonitor/competitorsPage', 'displayPageScores');

        $toDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d");
        $fromDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-90 day"));		
        if(!strpos($pageName,'---')){
                $displayData['pageName'] = $pageName = str_replace('-',' ',$pageName);
        }
        else{
                $displayData['pageName'] = $pageName = str_replace('-',' ',$pageName);
                $displayData['pageName'] = $pageName = str_replace('   ',' - ',$pageName);
        }
		$displayData['device'] = $device;
		
		$pageDetails = $this->model->getCompetitorPageDetailsLH($pageName, $device, $fromDate, $toDate);	
		$displayData['pageDetails'] = $pageDetails;
		
		$pageDetails = $this->parseDateResultsLH($pageDetails);

		$displayData['siteList'] = $this->_getSiteList($pageDetails);
		
		$displayData['googleScoreDetails'] = $this->_formatSizeData($pageDetails, 'googleScore', $displayData['siteList']);
		$displayData['fmpDetails'] = $this->_formatSizeData($pageDetails, 'fmp', $displayData['siteList']);
		$displayData['fcpDetails'] = $this->_formatSizeData($pageDetails, 'fcp', $displayData['siteList']);
		$displayData['speedIndexDetails'] = $this->_formatSizeData($pageDetails, 'speedIndex', $displayData['siteList']);
		$displayData['firstCPUIdleDetails'] = $this->_formatSizeData($pageDetails, 'firstCPUIdle', $displayData['siteList']);
		$displayData['mainThreadBreakdownDetails'] = $this->_formatSizeData($pageDetails, 'mainThreadBreakdown', $displayData['siteList']);
		$displayData['interactiveDetails'] = $this->_formatSizeData($pageDetails, 'interactive', $displayData['siteList']);
		$displayData['inputLatencyDetails'] = $this->_formatSizeData($pageDetails, 'inputLatency', $displayData['siteList']);
		$displayData['bootupTimeDetails'] = $this->_formatSizeData($pageDetails, 'bootupTime', $displayData['siteList']);
		$displayData['ttfbDetails'] = $this->_formatSizeData($pageDetails, 'ttfb', $displayData['siteList']);
		$displayData['domSizeDetails'] = $this->_formatSizeData($pageDetails, 'domSize', $displayData['siteList']);
		
        if($selectedModule == 'shiksha') {
            $modules[] = 'Overall';
        }
        else {
            $modules = array($selectedModule);
        }
        $displayData['modules'] = $modules;
        $displayData['moduleName'] = $selectedModule;

        $displayData['trendStartDate'] = date("m/d/Y", strtotime($fromDate));
        $displayData['trendEndDate'] = date("m/d/Y", strtotime($toDate));
		
		$this->load->view("AppMonitor/compScore/pageDetailsLighthouse", $displayData);				
	}

	function parseDateResultsLH($resultArray){
		$returnArray = array();
		foreach ($resultArray as $result){
			$date = substr($result['creationDate'],0,10);
			$site = $result['site'];

			$returnArray[$date]['googleScore'][$site] = intval($result['googleScore']);
			$returnArray[$date]['fcp'][$site] = intval($result['fcp']);
			$returnArray[$date]['fmp'][$site] = intval($result['fmp']);
			$returnArray[$date]['speedIndex'][$site] = intval($result['speedIndex']);
			$returnArray[$date]['firstCPUIdle'][$site] = intval($result['firstCPUIdle']);
			$returnArray[$date]['mainThreadBreakdown'][$site] = intval($result['mainThreadBreakdown']);
			$returnArray[$date]['interactive'][$site] = intval($result['interactive']);
			$returnArray[$date]['inputLatency'][$site] = intval($result['inputLatency']);
			$returnArray[$date]['bootupTime'][$site] = intval($result['bootupTime']);
			$returnArray[$date]['ttfb'][$site] = intval($result['ttfb']);
			$returnArray[$date]['domSize'][$site] = intval($result['domSize']);

		}
		return $returnArray;
	}
	
}
