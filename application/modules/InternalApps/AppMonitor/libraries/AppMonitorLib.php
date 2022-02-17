<?php

class AppMonitorLib
{
	public function getCronPath($cronCommand)
	{
		$cron = trim($cronCommand);
		$cron = str_replace("--run =", "--run=", $cron);
		$cron = str_replace("--run= ", "--run=", $cron);
		$cron = str_replace("--run = ", "--run=", $cron);
		
		$cronParts = explode(' ', $cron);
		$cronPath = $cronCommand;
		
		foreach($cronParts as $cronPart) {
			$cronPart = trim($cronPart);
			if(strpos($cronPart, "--run=") === 0) {
				$cronPath = substr($cronPart, 6);
			}
		}
		
		return $cronPath;
	}
	
	public function getCronTeam($cronPath)
	{
		$cronPathParts = explode('/',$cronPath);
					
		$cronModule = strtolower($cronPathParts[1]);
		$cronController = strtolower($cronPathParts[2]);
		
		return $this->getMappedTeam($cronModule, $cronController);
	}
	
	public function getMappedTeam($module, $controller)
	{
		$moduleControllerTeamMapping = $this->getModuleControllerTeamMapping(TRUE, TRUE);
	
		$module = strtolower($module);
		$controller = strtolower($controller);
	
		if(array_key_exists($module, $moduleControllerTeamMapping['global'])) {
			$controller = $module;
			$module = 'global';
		}
		
		$teamName = 'others';
		if($moduleControllerTeamMapping[$module][$controller]) {
			$teamName = $moduleControllerTeamMapping[$module][$controller];
		}
		
		return $teamName;
	}
	
	public function getModuleControllerTeamMapping($global = FALSE, $lowercase = FALSE)
	{
		$CI =& get_instance();
		$CI->load->config("app_monitor_config");
 		global $ENT_EE_MODULES_CONTROLLER_MAP;
		
 		$finalMap =  array();
 		foreach($ENT_EE_MODULES_CONTROLLER_MAP as $team=>$modules){
 			foreach($modules as $moduleName=>$controllers){
 				foreach($controllers as $controllerName){
					if($global && !trim($moduleName)) {
						$moduleName = 'global';
					}
					if($lowercase) {
						$finalMap[strtolower($moduleName)][strtolower($controllerName)] = $team;
					}
					else {
						$finalMap[$moduleName][$controllerName] = $team;
					}
 				}
 			}
 		}

 		return $finalMap;
 	}

 	public function fetchLast30DaysValue($metric,$time,$yesterdayDate){		
		
 	}

 	public function removeOutliers($inputArray){

 		sort($inputArray);
 		$median = $this->calculateMedian($inputArray);

 		$madArray = array(); 		
 		foreach ($inputArray as $value) {
			$mad = 1.4826 * abs($value - $median);
			$madArray[] =$mad;
		}
		sort($madArray);		

		$mad = $this->calculateMedian($madArray);

		foreach ($inputArray as $key=>$value) {
			$x =  ($value - $median) / $mad * 0.6745;		
			if(abs($x) > 3.5){
			    unset($inputArray[$key]);
			}
		}
		return $inputArray;

 	}

 	public function calculateAbNormality($metric,$input){

 	}

 	public function calculateMedian($arr){

 		$n = count($arr); 
	    $mid = floor(($n-1)/2); 
	    if($n % 2) { 
	        $median = $arr[$mid];
	    } else {
	        $low = $arr[$mid];
	        $high = $arr[$mid+1];
	        $median = (($low+$high)/2);
	    }
	    return $median;
 	}

 	public function calculateMean($inputArray){

		$sum = array_sum($inputArray);
		$mean = $sum/count($inputArray);
		return round($mean);
	}

	public function calculateStandardDeviation($inputArray,$mean){
		$sum = 0;
		foreach ($inputArray as $val) {
			$base = (intval($val) - intval($mean));
			$exp = 2;
			$sum += pow($base, $exp);
		}

		$std = round(sqrt($sum / (count($inputArray) - 1)));
		return $std;
	}

	public function calculateZScore($mean,$standardDeviation,$input){
		$z = ($input - $mean) / $standardDeviation;
		return $z;
	}

	public function calculateUpperAcceptibleRange($zscore,$standardDeviation,$mean){
		$res = ($zscore * $standardDeviation) + $mean;
		return $res;
	}

	function curl($url){

		if(empty($url))
			return array();

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_ENCODING, 'gzip,deflate');
        curl_setopt($ch, CURLOPT_TIMEOUT, 20);

        // execute the curl request        
        $result = array();
        $result = curl_exec($ch);
        $culrRetcode = curl_getinfo($ch,CURLINFO_HTTP_CODE);
        
        curl_close($ch);
        return $result;
	}

	function formatClientSideTrackingData($response, $url, $pageName, $device){

		$data                            = array();
		
		$data['pageName']                = $pageName;
		$data['url']                     = $url;
		$data['trackingDate']            = date("Y-m-d");
		$data['device']            		 = $device;
		
		$data['speedScore']              = $response['ruleGroups']['SPEED']['score'];
		$data['usabilityScore']          = $response['ruleGroups']['USABILITY']['score'];

		$data['numberResources']         = $response['pageStats']['numberResources'];
		$data['numberHosts']             = $response['pageStats']['numberHosts'];
		$data['totalRequestBytes']       = $response['pageStats']['totalRequestBytes'];
		$data['numberStaticResources']   = $response['pageStats']['numberStaticResources'];
		$data['htmlResponseBytes']       = $response['pageStats']['htmlResponseBytes'];
		$data['cssResponseBytes']        = $response['pageStats']['cssResponseBytes'];
		$data['imageResponseBytes']      = $response['pageStats']['imageResponseBytes'];
		$data['javascriptResponseBytes'] = $response['pageStats']['javascriptResponseBytes'];
		$data['otherResponseBytes']      = $response['pageStats']['otherResponseBytes'];
		$data['numberJsResources']       = $response['pageStats']['numberJsResources'];
		$data['numberCssResources']      = $response['pageStats']['numberCssResources'];
		
		$data['response']                = json_encode($response);
		$data['status']                  = 'live';
		
		return $data;
	}
	
	/**
	 * Merge similar queries appearning in slow query log
	 */ 
	function mergeSimilarQueries($queriesData)
	{
		$mergedQueries = array();
		
		foreach ($queriesData as $queryData) {

			$query = $this->normalizeQuery($queryData['query']);
			
			if(array_key_exists($query, $mergedQueries)){
				$mergedQueries[$query]['count'] += $queryData['count'];
			}else {
				$mergedQueries[$query]['count'] = $queryData['count'];	
				$mergedQueries[$query]['server'] = $queryData['server'];	
			}
		}
		
		return $mergedQueries;
	}
	
	function normalizeQuery($query)
	{
		$query = trim(preg_replace('/[\s\t\n\r\s]+/', ' ', $query));
		$query = $this->changeInClause($query);
		
		return $query;
	}
	
	/**
	* Function to replace IN(<VARIABLE ARGUMENTS>) to IN(CLAUESE)
	* Simple Regex Find nd Replace
	*/
	function changeInClause($sql)
	{
		$s1 = 0;
		$p = "/ IN[ ]{0,1}\\([ ]{0,1}[.]{0,1}['|\"]?[N|S]['|\"]?[, \\)]/i";
		 while(preg_match($p, $sql,$matches,PREG_OFFSET_CAPTURE)){
		 	$s1 = $matches[0][1];
			$s2 = stripos($sql, ")",$s1)+1;
			$sql = substr_replace($sql, " IN[CLAUSES] ", $s1,($s2-$s1));
		}
		$p = "/repeated [0-9]{1,10} times/i";
                $sql = preg_replace($p, "repeated X times", $sql);

		return $sql;
	}


	function formatMonitoringDiffData($dateRange1Data = array(), $dateRange2Data = array())
	{
 		
		if(!empty($dateRange1Data)){
			foreach ($dateRange1Data as $key => $value) {

				$value['module-controller-method'] = trim($value['module_name'])."-".trim($value['controller_name'])."-".trim($value['method_name']);	

				if(array_key_exists($value['module-controller-method'], $data['dateRange1'])){
					$data['dateRange1'][$value['module-controller-method']]['count'] +=  $value['num_above_threshold'];
					$data['dateRange1'][$value['module-controller-method']]['team'] =  $value['team'];
				} else {	
					$data['dateRange1'][$value['module-controller-method']]['count'] =  $value['num_above_threshold'];
					$data['dateRange1'][$value['module-controller-method']]['team'] =  $value['team'];
				}
			}
		}
		
		if(!empty($dateRange2Data)){
			foreach ($dateRange2Data as $key => $value) {

				$value['module-controller-method'] = trim($value['module_name'])."-".trim($value['controller_name'])."-".trim($value['method_name']);	

				if(array_key_exists($value['module-controller-method'], $data['dateRange1'])){
					$data['dateRange2'][$value['module-controller-method']]['count'] +=  $value['num_above_threshold'];
					$data['dateRange2'][$value['module-controller-method']]['team'] =  $value['team'];
				}else {	
					$data['dateRange2'][$value['module-controller-method']]['count'] =  $value['num_above_threshold'];	
					$data['dateRange2'][$value['module-controller-method']]['team'] =  $value['team'];
				}
			}
		}
		
		// Pages Reduced (Good Thing)
		$pagesDiff1 = array_diff(array_keys($data['dateRange1']), array_keys($data['dateRange2']));
		$pagesReduced = array();
		$countArray = array();	
		foreach ($pagesDiff1 as $key => $value) {
			$pagesReduced[$value] = $data['dateRange1'][$value];
			$countArray[$value] = $data['dateRange1'][$value]['count'];
		}
		
		array_multisort($countArray, SORT_DESC, $pagesReduced);
		
		// New Pages (Bad Thing)
		$pagesDiff2 = array_diff(array_keys($data['dateRange2']), array_keys($data['dateRange1']));
		$countArray = array();
		$newSlowPages = array();
		foreach ($pagesDiff2 as $key => $value) {
			$newSlowPages[$value] = $data['dateRange2'][$value];
			$countArray[$value] = $data['dateRange2'][$value]['count'];
		}
		
		array_multisort($countArray, SORT_DESC, $newSlowPages);

		$intersectPages = array_values(array_intersect(array_keys($data['dateRange1']), array_keys($data['dateRange2'])));

		$diffArray = array();
		$pagesReducedCount = array();
		$pagesIncreasedCount = array();
		$countArray =array();
		$countArray1 =array();

		foreach ($intersectPages as $key => $value) {
			$diff = $data['dateRange1'][$value]['count'] - $data['dateRange2'][$value]['count'];
			$team = $data['dateRange1'][$value]['team'];
			if($diff > 0){
				$pagesReducedCount[$value]['count'] = $diff;
				$pagesReducedCount[$value]['team'] = $team;
				$countArray[$value] = $diff;
			}else if($diff < 0) {
				$pagesIncreasedCount[$value]['count'] = abs($diff);
				$pagesIncreasedCount[$value]['team'] = $team;
				$countArray1[$value] = abs($diff);
			}
		}

		array_multisort($countArray, SORT_DESC, $pagesReducedCount);
		array_multisort($countArray1, SORT_DESC, $pagesIncreasedCount);

		$displayData = array();
		$displayData['newSlowPages'] = $newSlowPages;
		$displayData['pagesReduced'] = $pagesReduced;
		$displayData['pagesReducedCount'] = $pagesReducedCount;
		$displayData['pagesIncreasedCount'] = $pagesIncreasedCount;
		
		$displayData['diffNew'] = count($newSlowPages);
		$displayData['diffRemoved'] = count($pagesReduced);
		$displayData['diffSame'] = count($pagesReducedCount) + count($pagesIncreasedCount);
		
		return $displayData;
 	}

	/**
	* @Description: Function to read cronfile 
	* @args: if $crontab is a filepath $isFilePath is true, if $crontab is cron file content $isFilePath is true, $type 			is set to php if cron commands are of type php, else crons are interpreted as bash commands, $server sets 				the server ip into the returning array
	* @return: n X 3 array, where n is number of cron jobs, each consisting of cron expression, command and server
	*/
	function readCronTab($isFilePath,$crontabPath,$type='php',$server){
		
		$result = '';
		if($isFilePath==true){
			$result = file($crontabPath,FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
		}
		else{
			$result = explode('\n', preg_replace('![\r\n]+!', '\n',$crontabPath));
		}

		$arr = array();$i=0;$tmpArr = array();
		if($type!='php'){
			foreach($result as $key=>$value){
				if (!preg_match('/^#/', $value, $matches)) {
					$tmstr = explode(' ',preg_replace('!\s+!',' ',$value));
					$crnExp = array_slice($tmstr,0,5);
					$crnStr = array_slice($tmstr, 5);
					$tmpArr[$i]['cronExp'] =  implode(' ',$crnExp);
					$tmpArr[$i]['cronStr'] =  implode(' ',$crnStr);
					$tmpArr[$i]['cronServer'] = $server;
					$i++;
				}
			}  
		}
		else{
			foreach($result as $key=>$value){
				if (!preg_match('/^#/', $value, $matches)) {
					$tmstr = explode(' ',preg_replace('!\s+!',' ',$value));
					$crnExp = array_slice($tmstr,0,5);
					$crnStr = array_slice($tmstr, 7);
					$crnStr = implode(' ',$crnStr);
					$crnStr = substr($crnStr, 1,-1);
					$crnStr = json_decode($crnStr)->command;
					$tmpArr[$i]['cronExp'] =  implode(' ',$crnExp);
					$tmpArr[$i]['cronStr'] =  $crnStr;
					$tmpArr[$i]['cronServer'] = $server;
					$i++;
				}
			}
		}
		return $tmpArr;
	}
	
	function getMappedTeamPwa($fileName) {
		if(preg_match('/placement|listing|childpage|institute|course|ranking/i',$fileName)){
			$team = 'listings';
		}
		else if(preg_match('/homepage|exam|predictor|socialsharing/i',$fileName)){
			$team = 'mobile_app';
		}
		else if(preg_match('/recommendation|search|categorypage/i',$fileName)){
			$team = 'search';
		}
		else if(preg_match('/responseform/i',$fileName)){
			$team = 'ldb';
		}
		return $team;
	}
}
