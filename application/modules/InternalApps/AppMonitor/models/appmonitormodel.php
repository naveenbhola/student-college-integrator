<?php

class Appmonitormodel extends MY_Model
{
	private $cronsForLagMonitoring = array(
        'LeadAllocation' => array('name' => 'Lead Allocation', 'lagLimit' => 3600),
        'LeadDeliveryEmail' => array('name' => 'Lead Delivery By Email', 'lagLimit' => 3600),
        'LeadDeliverySMS' => array('name' => 'Lead Delivery By SMS', 'lagLimit' => 3600),
        'ShikshaEmailDelivery' => array('name' => 'Shiksha Email Delivery', 'lagLimit' => 3600),
        'ShikshaSMSDelivery' => array('name' => 'Shiksha SMS Delivery', 'lagLimit' => 3600),
        'HourlyResponseDelivery' => array('name' => 'Hourly Response Delivery', 'lagLimit' => 10800),
        'MMMClientMailerProcessing' => array('name' => 'MMM Client Mailer Processing', 'lagLimit' => 86400),
        'MMMProductMailerProcessing' => array('name' => 'MMM Product Mailer Processing', 'lagLimit' => 10800),
        'MMMEmailDelivery' => array('name' => 'MMM Email Delivery', 'lagLimit' => 10800)
    );
    
    /*private $crons_exclude_array = array(
		"'/searchAgents/searchAgents_Server/runDeliveryCronASAP'",
		"'/response/Response/createResponseByCron'",
		"'/searchAgents/MatchedResponseAgent/run'",
		"'/searchAgents/searchAgents_Server/matchingLeads'",
		"'/user/UserIndexer/indexQueuedUsers'",
		"'/index.php/SMS/SMS/performMobileCheck/12/1'",
		"'/lms/Porting/startPorting'",
		"'/response/Response/sendResponseMailByCron'",
		"'/user/UserIndexer/indexQueuedSearchAgent'",
		"'/SMS/SMS/shikshaSmsAlert/12'",
		"'/mailer/MailerProcessor/processOtherAbroadProductMailers'",
		"'/MultipleApply/MultipleApply/generateBulkLead'",
		"'/mailer/Mailer/cronAddCampaignInSmsQueue'",
		"'/lms/Porting/startPorting'",
		"'/searchAgents/searchAgents_Server/oldLeadsMatching'",
		"'/user/UserIndexer/optimizeCallToLDBSolr'",
		"'/sums/Nav_Integreation/exportToNAV'",
		"'/mailer/MailerProcessor/processMailers/6'",
		"'/response/Response/compareAutoResponses'",
		"'/sums/Nav_Integreation/exportModifiedEnterpriseUsers'",
		"'/enterprise/EnterpriseServer/sendMailForAbuseActivity'",
		"'/LeadExport/sendCSVResponses'",
		"'/beacon/Beacon_server/viewsDecayHammer'",
		"'/beacon/Beacon_server/trigger_to_update_pastViews'",
		"'/beacon/Beacon_server/storePreComputedAlsoViewedCourses/'",
		"'/searchAgents/searchAgents_Server/updateLeftoverStatus'",
		"'/searchAgents/searchAgents_Server/getSearchAgentAlertMail'",
		"'/beacon/Beacon_server/updateAlsoViewedTables'",
		"'/common/HardBounceTracking/updateHardBounceStatus/daily'",
		"'/searchAgents/MatchedResponseAgent/cronMatchingGenie'",
		"'/CollegeReviewForm/CollegeReviewCrons/sendDailyReport'",
		"'/ShikshaPopularity/ShikshaPopularityController/startPopularityCron'",
		"'/CollegeReviewForm/CollegeReviewCrons/reviewsIPTracking'",
		"'/CollegeReviewForm/CollegeReviewController/sendWeeklyDashboard'",
		"'/response/Response/createAutoResponses'",
		"'/response/Response/createResponseByCron'",
		"'/response/Response/sendResponseMailByCron'",
		"'/recommendation/AlsoViewedPrecomputation/compute'",
		"'/user/UserIndexer/indexQueuedSearchAgent'"
		);*/

	function __construct()
	{
		parent::__construct('AppMonitor');
    }

	function logHttpStatusCodes($httpStatusCodes, $host, $date)
        {
                $db = $this->getWriteHandle();
                foreach($httpStatusCodes as $code => $num) {
                        $data = array(
                                'status_code' => $code,
                                'num_requests' => $num,
                                'host' => $host,
                                'date' => $date
                        );
                        $db->insert('statusCodes', $data);
                }
        }

    function logHttpStatusCodesDetails($data){
    	//_p($data);die;
        $db = $this->getWriteHandle();
        $db->insert_batch("status_codes_details",$data);
        $db->last_query();
    }

	function insertTimeTakenLogs($data)
	{
		$db = $this->getWriteHandle();

		$sql = "select max(id) as maxid from slowPagesMaster";
		$maxIdData = $db->query($sql)->row_array();
		if(empty($maxIdData) || empty($maxIdData['maxid']))
			$maxid = 1;
		else
			$maxid = $maxIdData['maxid'];

		$dataRow = array();
		foreach ($data as $value) {

			$dataRow[] = array(
						'id'			  => ++$maxid,
						'module_name' 	  => $value['module_name'],
						'controller_name' => $value['controller_name'],
						'method_name'     => $value['method_name'],
						'total_hits'      => $value['total_hits'],
						'average_time'    => $value['average_time'],
						"is_mobile"       => $value['isMobile'],
						'bucket_data'     => $value['bucket_data'],
						'log_date'        => $value['log_date']
				);

			// $id = $db->insert_id();
			$loadDataArr = array();
			foreach ($value['data'] as $logDataRow) {
				$loadDataArr[] = array('slowPageId'   => $maxid,
									'time_taken'     => $logDataRow['time_taken'],
									'url'            => $logDataRow['url'],
									'referrer'       => $logDataRow['referrer'],
									'server'         => $value['server'],
									'occurence_time' => $logDataRow['occurence_time']);
			}
			$db->insert_batch("slowPagesLogDetails", $loadDataArr);
		}

		$db->insert_batch('slowPagesMaster', $dataRow);
		
	}


	function insertHighMemoryPagesLogs($data){
		$db = $this->getWriteHandle();

		$sql = "select max(id) as maxid from highMemoryPagesMaster";
		$maxIdData = $db->query($sql)->row_array();
		if(empty($maxIdData) || empty($maxIdData['maxid']))
			$maxid = 1;
		else
			$maxid = $maxIdData['maxid'];

		$dataRow = array();
		foreach ($data as $value) {

			$dataRow[] = array(
						'id'		      => ++$maxid,
						'module_name' 	  => $value['module_name'],
						'controller_name' => $value['controller_name'],
						'method_name'     => $value['method_name'],
						'total_hits'      => $value['total_hits'],
						'average_memory'  => $value['average_memory'],
						"is_mobile"       => $value['isMobile'],
						'bucket_data'     => $value['bucket_data'],
						'log_date'        => $value['log_date']
				);

			$lodDataArr = array();
			foreach ($value['data'] as $logDataRow) {
				$lodDataArr[] = array('pageId' => $maxid,
							'memory_occupied' => $logDataRow['memory_occupied'],
							'url'             => $logDataRow['url'],
							'referrer'        => $logDataRow['referrer'],
							'server'          => $value['server'],
							'occurence_time'  => $logDataRow['occurence_time']);
			}
			$db->insert_batch("highMemoryPagesLogDetails", $lodDataArr);
		}

		$db->insert_batch('highMemoryPagesMaster', $dataRow);
		
	}

	function getSlowPages($selectedModule, $fromDate, $toDate)
	{	
		$db = $this->getReadHandle();

		$modulesClause = $this->_getModuleClause($selectedModule);

		$sql = "SELECT `module_name`,`controller_name`,`method_name`, avg(average_time) as average FROM slowPagesMaster spm 
				WHERE 1 "
				.$modulesClause."
				AND log_date = CURDATE() - INTERVAL 5 DAY
				group by module_name,controller_name,method_name 
				ORDER BY average desc limit 20";
//AND log_date BETWEEN '".date("Y-m-d", strtotime($toDate))."' AND '".date("Y-m-d", strtotime($fromDate))."'

		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getHighMemoryPages($selectedModule, $fromDate, $toDate){
		
		$db = $this->getReadHandle();

		$modulesClause = $this->_getModuleClause($selectedModule);
		

		$sql = "SELECT `module_name`,`controller_name`,`method_name`, avg(average_memory)/1048576 as average FROM highMemoryPagesMaster spm 
				WHERE 1 "
				.$modulesClause."
				AND log_date = CURDATE() - INTERVAL 5 DAY
				group by module_name,controller_name,method_name 
				ORDER BY average desc limit 20";

		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getSlowPagesDailyReport($selectedModule, $mainmodules, $fromDate, $toDate, $getPageWiseAvg = 0)
	{
		$db = $this->getReadHandle();

		$moduleWhereClause = array();
		foreach ($mainmodules as $value) {
			$moduleWhereClause[] = "( controller_name = '".$value[0]."' AND method_name = '".$value[1]."')";
		}
		$moduleWhereClause = implode(" OR ", $moduleWhereClause);

		$modulesClause = $this->_getModuleClause($selectedModule);

		$groupClause = "";
		if($getPageWiseAvg){
			$groupClause = " group by controller_name, method_name ";
		}else{
			$groupClause = " group by l_date, controller_name, method_name ";
		}

		$sql = "SELECT controller_name, method_name, DATE_FORMAT(log_date,'%d-%b-%y') as l_date, AVG(average_time) as avg, bucket_data FROM `slowPagesMaster` WHERE 1
				".$modulesClause."
				AND log_date <= '".date("Y-m-d", strtotime($fromDate))."' AND log_date >= '".date("Y-m-d", strtotime($toDate))."'
				AND (".$moduleWhereClause." )
				".$groupClause."
				order by log_date asc";
				// _p($sql);

		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getHighMemoryPagesDailyReport($selectedModule, $mainmodules, $fromDate, $toDate, $getPageWiseAvg = 0)
	{
		$db = $this->getReadHandle();

		$moduleWhereClause = array();
		foreach ($mainmodules as $value) {
			$moduleWhereClause[] = "( controller_name = '".$value[0]."' AND method_name = '".$value[1]."')";
		}
		$moduleWhereClause = implode(" OR ", $moduleWhereClause);

		$modulesClause = $this->_getModuleClause($selectedModule);

		$groupClause = "";
		if($getPageWiseAvg){
			$groupClause = " group by controller_name, method_name ";
		}else{
			$groupClause = " group by l_date, controller_name, method_name ";
		}

		$sql = "SELECT controller_name, method_name, DATE_FORMAT(log_date,'%d-%b-%y') as l_date, AVG(average_memory)/1048576 as avg, bucket_data FROM `highMemoryPagesMaster` WHERE 1
				".$modulesClause."
				AND log_date <= '".date("Y-m-d", strtotime($fromDate))."' AND log_date >= '".date("Y-m-d", strtotime($toDate))."'
				AND (".$moduleWhereClause." ) 
				".$groupClause."
				order by log_date asc";


		$data = $db->query($sql)->result_array();
		return $data;
	}

	function _getModuleClause($selectedModule)
	{
		global $ENT_EE_MODULES_CONTROLLER_MAP;

		$modulesClause = "";
		$subModules = array();
		if(array_key_exists($selectedModule, $ENT_EE_MODULES_CONTROLLER_MAP)){
			$subModules = $ENT_EE_MODULES_CONTROLLER_MAP[$selectedModule];
			foreach($subModules as $moduleName=>$controllers){
				$modulesClause[] = " (module_name = '".$moduleName."' AND controller_name IN ('".implode("','", $controllers)."'))";	
			}
			$modulesClause = implode(" OR ",$modulesClause);
			if(!empty($modulesClause))
				$modulesClause = " AND (".$modulesClause.")";
			
		}
		else if($selectedModule == "others"){
			$modulesClause = array();
			foreach ( $ENT_EE_MODULES_CONTROLLER_MAP as $subModules) {
				foreach($subModules as $moduleName=>$controllers){
					$modulesClause[] = " (module_name = '".$moduleName."' AND controller_name IN ('".implode("','", $controllers)."'))";	
				}
			}
			$modulesClause = implode(" OR ",$modulesClause);
			if(!empty($modulesClause))
				$modulesClause = " AND !(".$modulesClause.")";
		}

		return $modulesClause;
	}

	function getSlowpagesURLs($controllerName, $methodName, $selecteddate, $todate, $module )
	{
		$db = $this->getReadHandle();
		
		$dataArr = array();
		$whereClauses = array();
		
		if($controllerName == 'All' && $methodName == 'All') {
			if($module != 'shiksha') {
				$whereClauses[] = 'spm.team = ?';
				$dataArr[] = $module;
			}
		}
		else {
			$whereClauses[] = 'spm.controller_name = ?';
			$dataArr[] = $controllerName;
			$whereClauses[] = 'spm.method_name = ?';
			$dataArr[] = $methodName;
		}
		
		if($todate) {
			$whereClauses[] = 'DATE(spld.occurence_time) >= ?';
			$dataArr[] = $selecteddate;
			$whereClauses[] = 'DATE(spld.occurence_time) <= ?';
			$dataArr[] = $todate;
		}
		else {
			$whereClauses[] = 'DATE(spld.occurence_time) = ?';
			$dataArr[] = $selecteddate;
		}
				
		$sql = "SELECT spld.time_taken, spld.url, spld.referrer, spld.is_mobile FROM 
				slowPagesMaster spm
				INNER JOIN
				slowPagesLogDetails spld
				ON(spm.id = spld.slowPageId)
				WHERE 
				".implode(' AND ', $whereClauses)."
				ORDER BY spld.time_taken desc limit 1000";	
		$data = $db->query($sql, $dataArr)->result_array();
		error_log($db->last_query());
		return $data;
	}
	
	function getHighMemorypagesURLs($controllerName, $methodName, $selecteddate, $todate, $module )
	{
		$db = $this->getReadHandle();
		
		$dataArr = array();
		$whereClauses = array();
		
		if($controllerName == 'All' && $methodName == 'All') {
			if($module != 'shiksha') {
				$whereClauses[] = 'hpm.team = ?';
				$dataArr[] = $module;
			}
		}
		else {
			$whereClauses[] = 'hpm.controller_name = ?';
			$dataArr[] = $controllerName;
			$whereClauses[] = 'hpm.method_name = ?';
			$dataArr[] = $methodName;
		}
		
		if($todate) {
			$whereClauses[] = 'DATE(hpld.occurence_time) >= ?';
			$dataArr[] = $selecteddate;
			$whereClauses[] = 'DATE(hpld.occurence_time) <= ?';
			$dataArr[] = $todate;
		}
		else {
			$whereClauses[] = 'DATE(hpld.occurence_time) = ?';
			$dataArr[] = $selecteddate;
		}
				
		$sql = "SELECT hpld.memory_occupied, hpld.url, hpld.referrer, hpld.is_mobile FROM 
				highMemoryPagesMaster hpm
				INNER JOIN
				highMemoryPagesLogDetails hpld
				ON(hpm.id = hpld.pageId)
				WHERE 
				".implode(' AND ', $whereClauses)."
				ORDER BY hpld.memory_occupied desc limit 1000";
			
		$data = $db->query($sql, $dataArr)->result_array();
		return $data;
	}
	
	function getHighCachePagesURLs($controllerName, $methodName, $selecteddate, $todate, $module )
	{
		$db = $this->getReadHandle();
		
		$dataArr = array();
		$whereClauses = array();
		
		if($controllerName == 'All' && $methodName == 'All') {
			if($module != 'shiksha') {
				$whereClauses[] = 'hpm.team = ?';
				$dataArr[] = $module;
			}
		}
		else {
			$whereClauses[] = 'hpm.controller_name = ?';
			$dataArr[] = $controllerName;
			$whereClauses[] = 'hpm.method_name = ?';
			$dataArr[] = $methodName;
		}
		
		if($todate) {
			$whereClauses[] = 'DATE(hpld.occurence_time) >= ?';
			$dataArr[] = $selecteddate;
			$whereClauses[] = 'DATE(hpld.occurence_time) <= ?';
			$dataArr[] = $todate;
		}
		else {
			$whereClauses[] = 'DATE(hpld.occurence_time) = ?';
			$dataArr[] = $selecteddate;
		}
				
		$sql = "SELECT hpld.cache_size, hpld.url, hpld.referrer, hpld.is_mobile FROM 
				highCachePagesMaster hpm
				INNER JOIN
				highCachePagesLogDetails hpld
				ON(hpm.id = hpld.pageId)
				WHERE 
				".implode(' AND ', $whereClauses)."
				ORDER BY hpld.cache_size desc limit 1000";
		error_log($sql);	
		$data = $db->query($sql, $dataArr)->result_array();
		return $data;
	}

	function getHighMemorypagesURLs1($controllerName, $methodName, $selecteddate )
	{
		$db = $this->getReadHandle();

		$sql = "SELECT id FROM highMemoryPagesMaster WHERE 1
				and controller_name = ? 
				and method_name = ? 
				and log_date = ? ";

		$data = $db->query($sql, array($controllerName, $methodName, $selecteddate))->result_array();
		
		$rowIdsArr = array();
		foreach($data as $rowId){
			$rowIdsArr[] = $rowId['id'];
		}

		if(!empty($rowIdsArr)){
			$sql = "SELECT memory_occupied, url FROM `highMemoryPagesLogDetails` WHERE 1
					and pageId IN (".implode(",", $rowIdsArr).")
					ORDER BY memory_occupied desc 
					limit 1000 ";

			$data1 = $db->query($sql, array($controllerName, $methodName, $selecteddate))->result_array();
		}
		return $data1;
	}

	function getAllSlowPagesMethods($selectedModule)
	{
		$db = $this->getReadHandle();

		$modulesClause = $this->_getModuleClause($selectedModule);

		$sql = "SELECT distinct controller_name,method_name FROM slowPagesMaster WHERE 1
				".$modulesClause."
				order by controller_name,method_name";
		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getAllHighMemoryPagesMethods($selectedModule)
	{
		$db = $this->getReadHandle();

		$modulesClause = $this->_getModuleClause($selectedModule);

		$sql = "SELECT distinct controller_name,method_name FROM highMemoryPagesMaster WHERE 1
				".$modulesClause."
				order by controller_name,method_name";

		$data = $db->query($sql)->result_array();

		return $data;
	}

	function getDayWiseExceptions($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(addition_date) as date, count(*) as count FROM exceptionLogs where addition_date >= '".$fromDate."' and addition_date <= '".$toDate."' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." group by team, date order by date ";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	function getUniqueDayWiseExceptions($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";
		
		$sql = "SELECT team, d AS date, COUNT(*) as count
				FROM (				
					SELECT DATE( addition_date ) AS d, team, module_name, controller_name, method_name
					FROM  `exceptionLogs`
					WHERE addition_date >= '".$fromDate."'
					AND addition_date <= '".$toDate."' ".
					($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")."
					GROUP BY d, team, module_name, controller_name, method_name
				) t
				GROUP BY t.team, t.d
				ORDER BY t.d";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}

	function getDayWiseSolrErrors($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(time) as date, count(*) as count FROM solrErrors where time >= '".$fromDate."' and time <= '".$toDate."' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." group by team, date order by date ";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}

	function getUniqueDayWiseSolrErrors($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";
		
		$sql = "SELECT team, d AS date, COUNT(*) as count
				FROM (				
					SELECT DATE( time ) AS d, team, module_name, controller_name, method_name
					FROM  `solrErrors`
					WHERE time >= '".$fromDate."'
					AND time <= '".$toDate."' ".
					($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")."
					GROUP BY d, team, module_name, controller_name, method_name
				) t
				GROUP BY t.team, t.d
				ORDER BY t.d";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}

	function getDayWiseDbErrors($selectedModule, $fromDate, $toDate)
	{	
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(addition_date) as date, count(*) as count FROM errorQueryLogs where addition_date > '".$fromDate."' and addition_date < '".$toDate."' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." group by team, date order by date ";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	function getUniqueDayWiseDBErrors($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";
		
		$sql = "SELECT team, d AS date, COUNT(*) as count
				FROM (				
					SELECT DATE( addition_date ) AS d, team, module_name, controller_name, method_name
					FROM  `errorQueryLogs`
					WHERE addition_date >= '".$fromDate."'
					AND addition_date <= '".$toDate."' ".
					($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")."
					GROUP BY d, team, module_name, controller_name, method_name
				) t
				GROUP BY t.team, t.d
				ORDER BY t.d";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}

	function getExceptionCount($selectedModule, $fromDate, $toDate){
		
		$db = $this->getReadHandle();

		$modulesClause = $this->_getModuleClause($selectedModule);

		$sql = "SELECT  COUNT(*) as total_sum FROM exceptionLogs spm 
				WHERE 1 "
				.$modulesClause."
				AND DATE(addition_date) <= '".date("Y-m-d", strtotime($fromDate))."' AND DATE(addition_date) >= '".date("Y-m-d", strtotime($toDate))."'";

		$data = $db->query($sql)->row_array();
		return $data['total_sum'];
	}

	function getDBErrorCount($selectedModule, $fromDate, $toDate){
		
		$db = $this->getReadHandle();

		$modulesClause = $this->_getModuleClause($selectedModule);

		$sql = "SELECT  COUNT(*) as total_sum FROM errorQueryLogs spm 
				WHERE 1 "
				.$modulesClause."
				AND DATE(addition_date) <= '".date("Y-m-d", strtotime($fromDate))."' AND DATE(addition_date) >= '".date("Y-m-d", strtotime($toDate))."'";

		$data = $db->query($sql)->row_array();
		return $data['total_sum'];
	}

	function getTopExceptions($selectedModule, $date, $realtimeDataParams)
	{	
		$db = $this->getReadHandle();
				
		$sql =  "SELECT id, `exception_msg` as msg,`source_file` as file,`line_num`,`url`,
						`addition_date`, team, isMobile,referrer , server, stack_trace, identifier ".
				"FROM exceptionLogs ".
				"WHERE addition_date > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND addition_date <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."' " : " ").
				"ORDER BY addition_date ".$realtimeDataParams['order']."
				".($realtimeDataParams['limit'] ? "LIMIT ".$realtimeDataParams['limit'] : "");
				
		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getTopDbErrors($selectedModule, $date, $realtimeDataParams)
	{	
		$db = $this->getReadHandle();
		
		$sql =  "SELECT `error_msg` as msg, `filename` as file, `line_num`, `url`,
						`addition_date`, team, is_mobile as isMobile , query, referrer ".
				"FROM errorQueryLogs ".
				"WHERE addition_date > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND addition_date <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."' " : " ").
				"ORDER BY addition_date ".$realtimeDataParams['order']."
				".($realtimeDataParams['limit'] ? "LIMIT ".$realtimeDataParams['limit'] : "");

		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getTopSolrErrors($selectedModule, $date, $realtimeDataParams)
	{	
		$db = $this->getReadHandle();
				
		$sql =  "SELECT id, `errorMsg` as msg,`pageURL`, solrURL ,`returnCode`,
						`time`, module_name, controller_name,method_name,team, isMobile ".
				"FROM solrErrors ".
				"WHERE time > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND time <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."' " : " ").
				"ORDER BY time ".$realtimeDataParams['order']."
				".($realtimeDataParams['limit'] ? "LIMIT ".$realtimeDataParams['limit'] : "");
				
		$data = $db->query($sql)->result_array();
		return $data;
	}

	function getSolrErrorResponseDetails($id)
	{	
		$db = $this->getReadHandle();
				
		$sql =  "SELECT solrResponse ".
				"FROM solrErrors ".
				"WHERE id= ?";
				
		$data = $db->query($sql, array($id))->row_array();
		return $data;
	}

	function getCronsForLagMonitoring()
	{
        $this->initiateModel('read','User');
		return $this->cronsForLagMonitoring;

	}

	public function getLag($cron)
    {
		$this->initiateModel('write','User');
		
		$tenDaysAgo = date('Y-m-d',strtotime('-10 days'));
		$fiveDaysAgo = date('Y-m-d',strtotime('-5 days'));
		
		if($cron == 'LeadAllocation') {
			$sql =  "SELECT allocationtime as time ".
					"FROM SALeadAllocation ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'LeadDeliveryEmail') {
			$sql =  "SELECT createdTime  as time ".
					"FROM tMailQueue ".
					"WHERE subject LIKE '%New leads from Shiksha.com' ".
					"AND createdTime > '$tenDaysAgo' AND isSent = 'sent' ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'LeadDeliverySMS') {
			$sql =  "SELECT createdDate  as time ".
					"FROM smsQueue ".
					"WHERE `text` LIKE 'Lead-%' ".
					"AND createdDate > '$tenDaysAgo' AND status = 'processed' ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'ShikshaEmailDelivery') {
			 $sql =  "SELECT createdTime  as time ".
                                        "FROM tMailQueue ".
                                        "WHERE createdTime > '$tenDaysAgo' AND isSent = 'sent' ".
                                        "ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'ShikshaSMSDelivery') {
			$sql =  "SELECT createdDate  as time ".
					"FROM smsQueue ".
					"WHERE createdDate > '$tenDaysAgo' AND status = 'processed' ".
					"ORDER BY id DESC LIMIT 1";
		}
		if($cron == 'HourlyResponseDelivery') {
			$sql =  "SELECT submit_date  as time ".
					"FROM tempLmsRequest ".
					"WHERE mailSent = 'yes' AND submit_date > '$fiveDaysAgo' ".
					"ORDER BY id DESC LIMIT 1";
		}                 
		if($cron == 'MMMClientMailerProcessing') {
			$sql =  "SELECT createdtime  as time ".
					"FROM mailQueue ".
					"WHERE mailertype IN ('mmm' ,'csv') ".
					"ORDER BY mailid DESC LIMIT 1";
					
			$this->initiateModel('write','Mailer');		
		}
		if($cron == 'MMMProductMailerProcessing') {
			$sql =  "SELECT createdtime  as time ".
					"FROM mailQueue ".
					"WHERE mailertype = 'product' ".
					"ORDER BY mailid DESC LIMIT 1";
					
			$this->initiateModel('write','Mailer');				
		}
		if($cron == 'MMMEmailDelivery') {
			$sql =  "SELECT createdtime  as time ".
					"FROM mailQueue ".
					"WHERE  issent = 'yes' ".
					"ORDER BY mailid DESC LIMIT 1";
					
			$this->initiateModel('write','Mailer');				
		}
		
		$query = $this->dbHandle->query($sql);

        $result = $query->row_array();
        $cronTime = strtotime($result['time']);
        
		$currentTime = time();
        $lag = $currentTime - $cronTime;
        return $lag;
    }

	public function getCronErrors($moduleName, $date, $realtimeDataParams)
	{
		$this->initiateModel('read');
		
		$moduleWhereClause = "";

		if($moduleName != "shiksha"){
			$moduleWhereClause = "team = '".$moduleName."' AND ";
		}
		
		$sql =  "SELECT *,date(time) as date ".
				"FROM cron_php_errors ".
				"WHERE ".$moduleWhereClause.
				"time > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND time <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				"ORDER BY time ".$realtimeDataParams['order']." ".($realtimeDataParams['limit'] ? "LIMIT ".$realtimeDataParams['limit'] : "");

        $query = $this->dbHandle->query($sql);
        return $query->result_array();
	}
	
	public function getCronDetailedErrors($moduleName, $fromDate, $toDate, $order = 'DESC', $limit = FALSE)
	{
		$this->initiateModel('read');
		$toDate .= " 23:59:59";

		$sql = "SELECT *,date(time) as date FROM cron_php_errors where time >= '$fromDate' and time <= '$toDate' ".($moduleName != 'shiksha' ? " AND team = '".$moduleName."'" : "")." ORDER BY time $order ".($limit ? "LIMIT $limit" : "");
        $query = $this->dbHandle->query($sql);
        return $query->result_array();
	}
	
	public function getCronRealTimeData($moduleName, $date)
	{
		$this->initiateModel('read');
		
		$sql = "SELECT HOUR(time) as h, MINUTE(time) as m, COUNT(id) as count FROM cron_php_errors where DATE(`time`) = '$date' ".($moduleName != 'shiksha' ? " AND team = '".$moduleName."'" : "")." GROUP BY h, m";
        $query = $this->dbHandle->query($sql);
        return $query->result_array();
	}

	public function getCronErrorsTrends($module, $fromDate,$toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(time) as date, count(*) as count FROM cron_php_errors where time > '".$fromDate."' and time < '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getUniqueCronErrorsTrends($module, $fromDate,$toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";
		
		$sql = "SELECT team, d AS date, COUNT(*) as count
				FROM (				
					SELECT DATE( time ) AS d, team, cron
					FROM  `cron_php_errors`
					WHERE time >= '".$fromDate."'
					AND time <= '".$toDate."' ".
					($module != 'shiksha' ? " AND team = '".$module."'" : "")."
					GROUP BY d, team, cron
				) t
				GROUP BY t.team, t.d
				ORDER BY t.d";
				
		$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}

	public function getSlowQueriesTrend($fromDate,$toDate,$server='shiksha')
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		//$fromDate .= " 00:00:00";
		//$toDate .=  " 23:59:59";

		if($server != "shiksha") {
			$serverWhereClause = "server = '".$server."' AND";
		}
		
		$sql = "SELECT date, server, SUM(`count`) as count FROM  slow_queries". 
			   " WHERE ".$serverWhereClause." `date` >= '".$fromDate."' AND `date` <= '".$toDate."'".
			   " GROUP BY `date`,server order by `date`";

	 	$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
		$trends = array();
		foreach($result as $row) {
			$trends[$row['date']][$row['server']] = $row['count'];
		}
	
        return $trends;
	}
	
	public function getSpearAlertTrend($fromDate, $toDate, $server='shiksha')
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		if($server != "shiksha") {
			$serverWhereClause = "host = '".$server."' AND";
		}
		
		$sql = "SELECT DATE(alertTime) as `date`, host as server, COUNT(id) as count FROM  alerts". 
			   " WHERE ".$serverWhereClause." `alertTime` >= '".$fromDate."' AND `alertTime` <= '".$toDate."'".
			   " GROUP BY `date`, server order by `date`";

	 	$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
		$trends = array();
		foreach($result as $row) {
			$trends[$row['date']][$row['server']] = $row['count'];
		}
	
        return $trends;
	}
    
    public function getBotTrends($fromDate, $toDate, $status = 'all')
	{
		$this->initiateModel('read');
		$statusWhereClause = "";

		if($status != "all") {
			$statusWhereClause = "status = '".$status."' AND";
		}
		
		$sql = "SELECT DATE(created) as `date`, status, COUNT(id) as count FROM botdetection". 
			   " WHERE ".$statusWhereClause." `created` >= '".$fromDate."' ".
               " AND `created` <= '".$toDate." 23:59:59' AND status IS NOT NULL ".
               " AND `action` = 'show_captcha' ".
			   " GROUP BY `date`, status order by `date`";

        //echo $sql; exit();

	 	$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
		$trends = array();
		foreach($result as $row) {
			$trends[$row['date']][$row['status']] = $row['count'];
		}
	
        return $trends;
	}
    
    public function getCaptchaTrends($fromDate, $toDate, $status = 'all')
	{
		$this->initiateModel('read');
		$statusWhereClause = "";

        if($status == "all") {
            $sql = "SELECT DATE(created) as `date`, action, COUNT(id) as count FROM botdetection". 
                   " WHERE ".$statusWhereClause." `created` >= '".$fromDate."' ".
                   " AND `created` <= '".$toDate." 23:59:59' AND action IS NOT NULL ".
                   " GROUP BY `date`, action order by `date`";
                   
            $query = $this->dbHandle->query($sql);
            $result = $query->result_array();
            
            $trends = array();
            foreach($result as $row) {
                $trends[$row['date']][$row['action']] = $row['count'];
            }
        }
        else {
            /**
             * Get show_captcha numbers
             */ 
            $sql = "SELECT DATE(created) as `date`, action, COUNT(id) as count FROM botdetection ". 
                   "WHERE status = '".$status."' ".
                   "AND `created` >= '".$fromDate."' ".
                   "AND `created` <= '".$toDate." 23:59:59' ".
                   "AND action = 'show_captcha' ".
                   "GROUP BY `date`, action order by `date`";
                   
            $query = $this->dbHandle->query($sql);
            $result = $query->result_array();
            
            $trends = array();
            foreach($result as $row) {
                $trends[$row['date']][$row['action']] = $row['count'];
            }
            
            /**
             * Get verify_captcha numbers
             */
            $sql = "SELECT DATE(b.created) as `date`, a.action, COUNT(*) as count ".
                   "FROM botdetection a ".
                   "INNER JOIN botdetection b ON b.sessionid = a.sessionid ". 
                   "WHERE b.status = '".$status."' ".
                   "AND b.`created` >= '".$fromDate."' ".
                   "AND b.`created` <= '".$toDate." 23:59:59' ".
                   "AND a.action = 'verify_captcha' ".
                   "AND b.action = 'show_captcha' ".
                   "GROUP BY `date`, a.action order by `date`";
                   
            $query = $this->dbHandle->query($sql);
            $result = $query->result_array();
            
            foreach($result as $row) {
                $trends[$row['date']][$row['action']] = $row['count'];
            }  
        }
	
        return $trends;
	}
	
	public function getGoogleWebLightTrends($fromDate, $toDate)
	{
		$this->initiateModel('read');
		
		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT date, numSessions, numGoogleWebLightSessions FROM session_data". 
			   " WHERE date >= '".$fromDate."' AND date <= '".$toDate."' AND pageType = ?".
			   " order by date";

	 	$query = $this->dbHandle->query($sql, array("all"));
	 	$result = $query->result_array();	
        
		$trends = array();
		foreach($result as $row) {
			$trends[] = array($row['date'], intval($row['numSessions']), intval($row['numGoogleWebLightSessions']));
		}
	
        return $trends;
	}

	public function getGoogleWebLightTrendsForPage($fromDate, $toDate, $pageType)
	{
		$this->initiateModel('read');
		
		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT date, numSessions, numGoogleWebLightSessions FROM session_data". 
			   " WHERE date >= '".$fromDate."' AND date <= '".$toDate."' AND pageType = ? ".
			   " order by date";

	 	$query = $this->dbHandle->query($sql, array($pageType));
	 	$result = $query->result_array();	
        
		$trends = array();
		foreach($result as $row) {
			$trends[] = array($row['date'], intval($row['numSessions']), intval($row['numGoogleWebLightSessions']));
		}
	
        return $trends;
	}
    
    public function getHTTPStatusCodeTrends($fromDate, $toDate)
	{
		$this->initiateModel('read');
		
		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT date, status_code, sum(num_requests) as num FROM statusCodes". 
			   " WHERE date >= '".$fromDate."' AND date <= '".$toDate."'".
			   " group by date, status_code order by status_code, date";

	 	$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();	
        
		$trends = array();
		foreach($result as $row) {
			$trends[$row['status_code']][$row['date']] = intval($row['num']);
		}
	
        return $trends;
	}

	function getHTTPStatusCodeDetailedData($filters){
		//var_dump($filters);die;
    	$this->initiateModel('read');
    	$this->dbHandle->select('status_code, transaction_id, request_method, host, request_uri, request_time');
    	$this->dbHandle->from('status_codes_details');

    	if($filters['fromdate']){
			$this->dbHandle->where("request_time >= ",$filters['fromdate']." 00:00:00");
		}

		if($filters['todate']){
			$this->dbHandle->where("request_time <= ",$filters['todate']." 23:59:59");
		}

		if(($filters['statusCode'] != "" && $filters['statusCode'] != "All")){
			if($filters['statusCode'] == "4xx"){
				$this->dbHandle->where("status_code >= ",400);
				$this->dbHandle->where("status_code <= ",499);
			}else if($filters['statusCode'] == "5xx"){
				$this->dbHandle->where("status_code >= ",500);
				$this->dbHandle->where("status_code <= ",999);
			}else{
				$this->dbHandle->where("status_code = ",intval($filters['statusCode']));
			}
		}

		if($filters['frondEndServer']){
			$this->dbHandle->where("host = ",intval($filters['frondEndServer']));
		}

    	$result = $this->dbHandle->get()->result_array();
    	//echo $this->dbHandle->last_query();
    	//_p($result);die;
    	return $result;
	}
    
    public function getHTTPStatusCodeFromDate()
	{
		$this->initiateModel('read');
		
		$sql = "SELECT MIN(date) as date FROM statusCodes ";

	 	$query = $this->dbHandle->query($sql);
	 	$result = $query->row_array();	
        
        return $result['date'];
	}
	
	public function getUniqueSlowQueriesTrend($fromDate,$toDate,$server='shiksha')
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		if($server != "shiksha") {
			$serverWhereClause = "server = '".$server."' AND";
		}
		
		$sql = "SELECT date,server,COUNT(id) as count FROM  slow_queries". 
			   " WHERE ".$serverWhereClause." date >= '".$fromDate."' AND date <= '".$toDate."'".
			   " GROUP BY date,server order by date";

	 	$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
		$trends = array();
		foreach($result as $row) {
			$trends[$row['date']][$row['server']] = $row['count'];
		}
		
        return $trends;
	}
	
	public function getCacheTrends($module, $toDate,$fromDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(log_date) as date, SUM(num_above_threshold) as count FROM highCachePagesMaster where log_date > '".$fromDate."' and log_date < '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getUniqueCacheTrends($module, $toDate,$fromDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(log_date) as date, COUNT(id) as count FROM highCachePagesMaster where log_date >= '".$fromDate."' and log_date <= '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." and num_above_threshold > 0 group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getSlowPageTrends($module, $toDate,$fromDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(log_date) as date, SUM(num_above_threshold) as count FROM slowPagesMaster where log_date > '".$fromDate."' and log_date < '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getUniqueSlowPageTrends($module, $toDate,$fromDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(log_date) as date, COUNT(id) as count FROM slowPagesMaster where log_date >= '".$fromDate."' and log_date <= '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." and num_above_threshold > 0 group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getHighMemoryTrends($module, $toDate,$fromDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(log_date) as date, SUM(num_above_threshold) as count FROM highMemoryPagesMaster where log_date > '".$fromDate."' and log_date < '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getUniqueHighMemoryTrends($module, $toDate,$fromDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT team, date(log_date) as date, COUNT(id) as count FROM highMemoryPagesMaster where log_date > '".$fromDate."' and log_date < '".$toDate."' ".($module != 'shiksha' ? " AND team = '".$module."'" : "")." and num_above_threshold > 0 group by team, date order by date ";
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	public function getSlowQueriesDashboard()
	{
		// Hardcoded, need to be removed
		//$fromDate = date('Y-m-d');
		$fromDate = "2013-07-23";
		$sql = "SELECT host,count(*) as count from slow_queries_live WHERE record_time > '".$fromDate."' group by host";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		
		return $result;
	}
	
	public function getSlowQueriesRealTimeStats($server='shiksha', $date, $mysqlLockQueries, $mysqlVerySlow)
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		global $ENT_EE_VERYSLOW_SQL;
		if($server != "shiksha"){
			$serverWhereClause .= " host = '".$server."' AND ";
		}

		if($mysqlLockQueries){
			$serverWhereClause .= " lock_time != 0.000 AND ";	
		}

		if($mysqlVerySlow){
			$serverWhereClause .= " query_time > ".$ENT_EE_VERYSLOW_SQL." AND ";	
		}
		
		$sql =  "SELECT id, HOUR(record_time) as h, MINUTE(record_time) as m, count(id) as count from slow_queries_live ".
				"WHERE ".$serverWhereClause." record_time >= '".$date."' AND record_time <= '".$date." 23:59:59' ".
				"GROUP BY h, m";
		
		error_log($sql);
		
		$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
        return $result;
	}
	
	public function getSpearAlertsRealTimeStats($server='shiksha', $date)
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		if($server != "shiksha"){
			$serverWhereClause = "host = '".$server."' AND";
		}
		
		$sql =  "SELECT id, HOUR(alertTime) as h, MINUTE(alertTime) as m, count(id) as count from alerts ".
				"WHERE ".$serverWhereClause." alertTime >= '".$date."' AND alertTime <= '".$date." 23:59:59' ".
				"GROUP BY h, m";
		
		error_log($sql);
		
		$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
        return $result;
	}
	
    public function getBotReportRealTimeStats($status = 'all', $date)
	{
		$this->initiateModel('read');
		$statusWhereClause = "";

		if($status != "all"){
			$statusWhereClause = "status = '".$status."' AND ";
		}
		
		$sql =  "SELECT id, HOUR(created) as h, MINUTE(created) as m, count(id) as count from botdetection ".
				"WHERE ".$statusWhereClause.
                "created >= '".$date."' AND created <= '".$date." 23:59:59' ".
                "AND action = 'show_captcha' ".
				"GROUP BY h, m";
		
		error_log("botreport: ".$sql);
		
		$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
        return $result;
	}
    
	public function getSlowQueriesRealTimeData($server = 'shiksha', $date, $realtimeDataParams, $mysqlLockQueries, $mysqlVerySlow)
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		global $ENT_EE_VERYSLOW_SQL;
		if($server != "shiksha"){
			$serverWhereClause .= " host = '".$server."' AND ";
		}
		if($mysqlLockQueries){
			$serverWhereClause .= " lock_time != 0.000 AND ";	
		}

		if($mysqlVerySlow){
			$serverWhereClause .= " query_time > ".$ENT_EE_VERYSLOW_SQL." AND ";	
		}
		
		$sql =  "SELECT host,source,query_time,lock_time,rows_sent,rows_examined,record_time,query ".
				"FROM slow_queries_live ".
				"WHERE ".$serverWhereClause.
				"record_time > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND record_time <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				($realtimeDataParams['yesterday'] ? "ORDER BY query_time DESC " : " ORDER BY record_time ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " LIMIT ".$realtimeDataParams['limit'] : "");
		$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
        return $result;
	}
	
	public function getSpearAlertsRealTimeData($server = 'shiksha', $date, $realtimeDataParams)
	{
		$this->initiateModel('read');
		$serverWhereClause = "";

		if($server != "shiksha"){
			$serverWhereClause = " host = '".$server."' AND ";
		}
		
		$sql =  "SELECT host, alertId, alertMsg, alertTime ".
				"FROM alerts ".
				"WHERE ".$serverWhereClause.
				"alertTime > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND alertTime <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				($realtimeDataParams['yesterday'] ? "ORDER BY alertTime DESC " : " ORDER BY alertTime ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " LIMIT ".$realtimeDataParams['limit'] : "");
		$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
        return $result;
	}
    
    public function getBotReportRealTimeData($status = 'all', $date, $realtimeDataParams)
	{
		$this->initiateModel('read');
		$statusWhereClause = "";

		if($status != "all"){
			$statusWhereClause = " status = '".$status."' AND ";
		}
		
		$sql =  "SELECT ip, useragent, status, created ".
				"FROM botdetection ".
				"WHERE ".$statusWhereClause.
				"created > '".$date." ".$realtimeDataParams['timeStart']."' ".
				"AND created <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
                "AND action = 'show_captcha' ".
				($realtimeDataParams['yesterday'] ? "ORDER BY created ASC " : " ORDER BY created ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " LIMIT ".$realtimeDataParams['limit'] : "");
                
        error_log("botreport:: ".$sql);        
                
		$query = $this->dbHandle->query($sql);
	 	$result = $query->result_array();
		
        return $result;
	}

	public function getSlowQueriesDetail($server,$fromDate,$toDate,$sorter,$avgTime=0)
	{
        $this->initiateModel('read');

        $sortClause = '';
	    if($sorter == 'occurrence') {
		$sortClause = 'ORDER BY `count` DESC';
	    } 	
	    else if($sorter == 'numrows') {
	        $sortClause = ' ORDER BY `avgRows` DESC';
	    }
	    else if($sorter == 'time') {
	        $sortClause = ' ORDER BY `avgTime` DESC';
	    } 

	    $dateWhereClause = "";
		if($fromDate != "" && $toDate != ""){
			$fromDate .= " 00:00:00";
			$toDate .=  " 23:59:59";	
			$dateWhereClause = "AND date >= '".$fromDate."' AND date <= '".$toDate."' ";
		}

	    $serverWhereClause = "AND server = '".$server."' ";
	    if($server == 'shiksha'){
	    	$serverWhereClause = "";
	    }
		$avgTimeWhereClause = "";
	    if(!empty($avgTime)){
	    	$avgTimeWhereClause = " AND avgTime > ".$avgTime." ";
	    }
	    
      	$sql = "SELECT * FROM slow_queries WHERE 1 ".$serverWhereClause.$dateWhereClause.$avgTimeWhereClause.$sortClause;
     
        $query = $this->dbHandle->query($sql);
        return $query->result_array();
	}

    private function initiateModel($mode = "write", $module = '')
    {
        if($mode == 'read') {
            $this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
        } else {
            $this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
        }
    }

    function getTotalRealtimeSlowPages($moduleName, $date, $realtimeDataParams, $excludeCron = 0)
	{
		$this->initiateModel('read');

		if($excludeCron == 1){
			$excludeCronWhere = " and is_cron = 'no' ";
		}
		
		$sql =  "SELECT team, module_name , controller_name , method_name , url, time_taken, log_time, is_mobile,log_details ".
				"from slowpages_live ".
				"where log_time >= '".$date." ".$realtimeDataParams['timeStart']."' ".
				//"and url not in (".implode(",",$this->crons_exclude_array).")  ".
				"and log_time <= '".$date." ".$realtimeDataParams['timeEnd']."' ". $excludeCronWhere.
				($moduleName != 'shiksha' ? " and team = '".$moduleName."' " : ""). 
				($realtimeDataParams['yesterday'] ? " order by time_taken desc " : "order by log_time ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " limit ".$realtimeDataParams['limit'] : "");
						
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }
	
	
	
	 function getTotalRealtimeCachePages($moduleName, $date, $realtimeDataParams, $excludeCron = 0)
	{
		$this->initiateModel('read');

		if($excludeCron == 1){
			$excludeCronWhere = " and is_cron = 'no' ";
		}
		
		$sql =  "SELECT team, module_name , controller_name , method_name , url, cachesize, log_time, is_mobile ".
				"from heavycachepages_live ".
				"where log_time >= '".$date." ".$realtimeDataParams['timeStart']."' ".
				"and log_time <= '".$date." ".$realtimeDataParams['timeEnd']."' ". $excludeCronWhere.
				($moduleName != 'shiksha' ? " and team = '".$moduleName."' " : "").
				($realtimeDataParams['yesterday'] ? " order by cachesize desc " : "order by log_time ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " limit ".$realtimeDataParams['limit'] : "");
						
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }
	
	function getSlowPageURLList($module, $controller, $method, $fromdate, $todate)
	{
		$this->initiateModel('read');
		
		$sql =  "SELECT team, module_name , controller_name , method_name , url, time_taken, log_time ".
				"from slowpages_live ".
				"where log_time >= '".$date." ".$realtimeDataParams['timeStart']."' ".
				//"and url not in (".implode(",",$this->crons_exclude_array).")  ".
				"and log_time <= '".$date." ".$realtimeDataParams['timeEnd']."' ".
				($moduleName != 'shiksha' ? " and team = '".$moduleName."' " : "").
				"order by log_time ".$realtimeDataParams['order'].
				($realtimeDataParams['limit'] ? " limit ".$realtimeDataParams['limit'] : "");
						
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }

    function getTotalRealtimeHighMemoryPage($moduleName, $date, $realtimeDataParams, $excludeCron = 0)
	{
		$this->initiateModel('read');

		if($excludeCron == 1){
			$excludeCronWhere = " AND is_cron = 'no' ";
		}
		
		$sql =  "SELECT team, module_name , controller_name , method_name , url, memory_consumed, log_time, is_mobile ".
				"from memorypages_live ".
				"where log_time >= '".$date." ".$realtimeDataParams['timeStart']."' ".
				"and log_time <= '".$date." ".$realtimeDataParams['timeEnd']."' ". $excludeCronWhere.
				($moduleName != 'shiksha' ? " and team = '".$moduleName."' " : "").
				($realtimeDataParams['yesterday'] ? "order by memory_consumed desc" : "order by log_time ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " limit ".$realtimeDataParams['limit'] : "");
	
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }

    function getTotalRealtimeHighSQLQueries($moduleName, $date, $realtimeDataParams, $excludeCron = 0)
	{
		$this->initiateModel('read');

		if($excludeCron == 1){
			$excludeCronWhere = " AND is_cron = 'no' ";
		}
		
		$sql =  "SELECT team, module_name , controller_name , method_name , url, total_queries,queries_split, log_time, is_mobile ".
				"from highSQLQueries_live ".
				"where log_time >= '".$date." ".$realtimeDataParams['timeStart']."' ".
				"and log_time <= '".$date." ".$realtimeDataParams['timeEnd']."' ".$excludeCronWhere.
				($moduleName != 'shiksha' ? " and team = '".$moduleName."' " : "").
				($realtimeDataParams['yesterday'] ? "order by total_queries desc" : "order by log_time ".$realtimeDataParams['order']).
				($realtimeDataParams['limit'] ? " limit ".$realtimeDataParams['limit'] : "");
	
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }
	
	function getRealTimeMemoryData($selectedModule, $date, $excludeCron = 0)
	{
		$this->initiateModel('read');

		if($excludeCron == 1){
			$excludeCronWhere = " AND is_cron = 'no' ";
		}

		$sql = "SELECT HOUR(log_time) h, MINUTE(log_time) m, count(id) as count FROM memorypages_live WHERE 1 AND log_time >= '".$date."' $excludeCronWhere AND log_time <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }

    function getRealTimeHighSQLData($selectedModule, $date, $excludeCron = 0)
	{
		$this->initiateModel('read');

		$excludeCronWhere = "";
		if($excludeCron == 1){
			$excludeCronWhere = " AND is_cron = 'no' ";
		}
		$sql = "SELECT HOUR(log_time) h, MINUTE(log_time) m, count(id) as count FROM highSQLQueries_live WHERE 1 AND log_time >= '".$date."' $excludeCronWhere AND log_time <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);

		$result = $query->result_array();
		return $result;
    }
	
	function getRealTimeSlowPageData($selectedModule, $date, $excludeCron = 0)
	{
		$this->initiateModel('read');
		if($excludeCron == 1){
			$excludeCronWhere = " AND is_cron = 'no' ";
		}

		$sql = "SELECT HOUR(log_time) h, MINUTE(log_time) m, count(id) as count FROM slowpages_live WHERE 1 AND  log_time >= '".$date."'  $excludeCronWhere AND log_time <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." GROUP BY h, m";

		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }
	
	function getRealTimeCacheData($selectedModule, $date, $excludeCron = 0)
	{
		$this->initiateModel('read');
		if($excludeCron == 1){
			$excludeCronWhere = " AND is_cron = 'no' ";
		}

		$sql = "SELECT HOUR(log_time) h, MINUTE(log_time) m, count(id) as count FROM heavycachepages_live WHERE 1 AND log_time >= '".$date."' $excludeCronWhere AND log_time <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }
    
    function getRealTimeExceptionData($selectedModule, $date)
	{
		$this->initiateModel('read');
		$sql = "SELECT HOUR(addition_date) h, MINUTE(addition_date) m, count(id) as count FROM exceptionLogs WHERE 1 AND addition_date >= '".$date."' AND addition_date <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }

    function getRealTimeDBQueryErrorData($moduleName, $date)
	{
		$this->initiateModel('read');		
		$sql = "SELECT HOUR(addition_date) h, MINUTE(addition_date) m, count(id) as count FROM errorQueryLogs WHERE 1 AND addition_date >= '".$date."' AND addition_date <= '".$date." 23:59:59' ".($moduleName != 'shiksha' ? " AND team = '".$moduleName."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }

    function getRealTimeSolrErrorData($selectedModule, $date)
	{
		$this->initiateModel('read');
		$sql = "SELECT HOUR(time) h, MINUTE(time) m, count(id) as count FROM solrErrors WHERE 1 AND time >= '".$date."' AND time <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND team = '".$selectedModule."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    }

	function getCachePagesDetailedData($filters)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by log_date desc";
		
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND log_date >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND log_date <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['module'] && $filters['module'] != 'shiksha'){
			$whereClause .= " AND team = ?";
			$dataArr[] = $filters['module'];
		}
		if($filters['orderby'] == 'size') {
			$orderby = " ORDER by average_cache desc";
		}
		else if($filters['orderby'] == 'hits') {
			$orderby = " ORDER by total_hits desc";
		}
		else if($filters['orderby'] == 'threshold') {
			$orderby = " ORDER by num_above_threshold desc";
		}
		
		$sql =  "SELECT team, module_name, controller_name, method_name, total_hits, average_cache, num_above_threshold, log_date ".
				"from highCachePagesMaster ".
				"where 1 ".
				$whereClause.$orderby;
						
		$query = $this->dbHandle->query($sql, $dataArr);
		$result = $query->result_array();
		return $result;
    }
	
	function getSlowPagesDetailedData($filters)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by OccurenceTime desc";
		
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND log_date >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND log_date <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['module'] && $filters['module'] != 'shiksha'){
			$whereClause .= " AND team = ?";
			$dataArr[] = $filters['module'];
		}
		if($filters['orderby'] == 'time') {
			$orderby = " ORDER by average_time desc";
		}
		else if($filters['orderby'] == 'hits') {
			$orderby = " ORDER by total_hits desc";
		}
		else if($filters['orderby'] == 'threshold') {
			$orderby = " ORDER by num_above_threshold desc";
		}
		
		$sql =  "SELECT team, module_name, controller_name, method_name, total_hits, average_time, num_above_threshold, log_date ".
				"from slowPagesMaster ".
				"where 1 ".
				$whereClause.$orderby;
						
		$query = $this->dbHandle->query($sql, $dataArr);
		$result = $query->result_array();
		return $result;
    }
	
	function getHighMemoryDetailedData($filters)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by log_date desc";
		
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND log_date >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND log_date <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['module'] && $filters['module'] != 'shiksha'){
			$whereClause .= " AND team = ?";
			$dataArr[] = $filters['module'];
		}
		if($filters['orderby'] == 'memory') {
			$orderby = " ORDER by average_memory desc";
		}
		else if($filters['orderby'] == 'hits') {
			$orderby = " ORDER by total_hits desc";
		}
		else if($filters['orderby'] == 'threshold') {
			$orderby = " ORDER by num_above_threshold desc";
		}
		
		$sql =  "SELECT team, module_name, controller_name, method_name, total_hits, average_memory, num_above_threshold, log_date ".
				"from highMemoryPagesMaster ".
				"where 1 ".
				$whereClause.$orderby;
						
		$query = $this->dbHandle->query($sql, $dataArr);
		$result = $query->result_array();
		return $result;
    }
	
	function getSpearAlertDetailedData($serverName, $fromDate, $toDate, $orderby)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by Occurences desc";
		
		$dataArr = array();
		if($fromDate){
			$whereClause .= " AND alertTime >= ?";
			$dataArr[] = date("Y-m-d 00:00:00", strtotime($fromDate));
		}
		if($toDate){
			$whereClause .= " AND alertTime <= ?";
			$dataArr[] = date("Y-m-d 23:59:59", strtotime($toDate));
		}
		if($serverName && $serverName != 'shiksha'){
			$whereClause .= " AND host = ?";
			$dataArr[] = $serverName;
		}
		
		if($orderby == 'occurrence') {
			$orderby = " ORDER by Occurences desc";
		}
		
		$query = "SELECT
				  a.id,
				  a.host,
				  a.alertId,
				  a.alertMsg,
				  count(*) as Occurences,
				  max(a.`alertTime`) as OccurenceTime 
				  FROM `alerts` a WHERE 1
				  ".$whereClause."
				  group by host, alertId ".$orderby ;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }
    
    function getBotReportDetailedData($statusName, $fromDate, $toDate, $orderby)
	{
        error_log("botreport:: ".$orderby);
        
    	$this->initiateModel('read');

		$whereClause = "";
		$orderbyClause = " ORDER by status asc";
		
		$dataArr = array();
		if($fromDate){
			$whereClause .= " AND created >= ?";
			$dataArr[] = date("Y-m-d 00:00:00", strtotime($fromDate));
		}
		if($toDate){
			$whereClause .= " AND created <= ?";
			$dataArr[] = date("Y-m-d 23:59:59", strtotime($toDate));
		}
		if($statusName && $statusName != 'all'){
			$whereClause .= " AND status = ?";
			$dataArr[] = $statusName;
		}
		
		if($orderby == 'ip') {
			$orderbyClause = " ORDER by ip asc";
		}
        else if($orderby == 'ua') {
			$orderbyClause = " ORDER by useragent asc";
		}
		
        error_log("botreport:: ".$orderby);
        
		$query = "SELECT
				  a.id,
				  a.ip,
				  a.useragent,
				  a.status
				  FROM `botdetection` a WHERE action = 'show_captcha'
				  ".$whereClause.$orderbyClause ;
                  
        error_log("botreport:: ".$query);
		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }
	
    function getExceptionDetailedData($filters)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by OccurenceTime desc";
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND DATE(addition_date) >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND DATE(addition_date) <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['module'] && $filters['module'] != 'shiksha'){
			$whereClause .= " AND team <= ?";
			$dataArr[] = $filters['module'];
		}
		if($filters['orderby'] =='time') {
			$orderby = " ORDER by OccurenceTime desc";
		}
		else if($filters['orderby'] =='occurrence') {
			$orderby = " ORDER by Occurences desc";
		}
		
		$query = "SELECT
				  a.id as id,
				  a.module_name,
				  a.controller_name,
				  a.method_name,
				  a.`exception_msg` as exception_msg,
				  a.`source_file` as source_file,
				  a.`line_num` as line_num,
				  a.`url` as url,
				  a.`error_class` as error_class,
				  a.`error_code` as error_code,
				  count(*) as Occurences,
				  max(a.`addition_date`) as OccurenceTime 
				  FROM `exceptionLogs` a WHERE 1
				  ".$whereClause."
				  group by controller_name,method_name,source_file,error_code,line_num ".$orderby ;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }

    function getSolrErrorDetailedData($filters)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by OccurenceTime desc";
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND DATE(time) >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND DATE(time) <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['module'] && $filters['module'] != 'shiksha'){
			$whereClause .= " AND team <= ?";
			$dataArr[] = $filters['module'];
		}
		if($filters['orderby'] =='time') {
			$orderby = " ORDER by OccurenceTime desc";
		}
		else if($filters['orderby'] =='occurrence') {
			$orderby = " ORDER by Occurences desc";
		}
		
		$query = "SELECT
				  a.id as id,
				  a.module_name,
				  a.controller_name,
				  a.method_name,
				  a.`solrURL` as exception_msg,
				  a.`pageURL` as url,
				  count(*) as Occurences,
				  max(a.`time`) as OccurenceTime 
				  FROM `solrErrors` a WHERE 1
				  ".$whereClause."
				  group by controller_name,method_name,pageURL ".$orderby ;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }

    function getExceptionpagesURL($controllerName, $methodName, $selecteddate, $todate, $module, $sourceFile, $lineNum)
	{
    	$this->initiateModel('read');


    	$dataArr = array();
		$whereClauses = array();
		
		if($controllerName == 'All' && $methodName == 'All') {
			if($module != 'shiksha') {
				$whereClauses[] = 'team = ?';
				$dataArr[] = $module;
			}
		}
		else {
			$whereClauses[] = ' controller_name = ?';
			$dataArr[] = $controllerName;
			$whereClauses[] = ' method_name = ?';
			$dataArr[] = $methodName;
		}
		
		if($todate) {
			$whereClauses[] = 'DATE(addition_date) >= ?';
			$dataArr[] = $selecteddate;
			$whereClauses[] = 'DATE(addition_date) <= ?';
			$dataArr[] = $todate;
		}
		else {
			$whereClauses[] = 'DATE(addition_date) = ?';
			$dataArr[] = $selecteddate;
		}

		if($sourceFile){
			$whereClauses[] = 'source_file = ?';
			$dataArr[] = $sourceFile;
		}

		if($lineNum){
			$whereClauses[] = 'line_num = ?';
			$dataArr[] = $lineNum;
		}
		
		$query = "SELECT
				  a.id as id,
				  a.`exception_msg` as exception_msg,
				  a.`source_file` as source_file,
				  a.`line_num` as line_num,
				  a.`url` as url,
				  a.`error_class` as error_class,
				  a.`error_code` as error_code,
				  a.`referrer`
				  FROM `exceptionLogs` a WHERE 
				  ".implode(" AND ", $whereClauses)."
				   ORDER by id asc " ;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }

	function getDBErrorpagesURL($controllerName, $methodName, $selecteddate, $todate, $module, $sourceFile, $lineNum)
	{
    	$this->initiateModel('read');


    	$dataArr = array();
		$whereClauses = array();
		
		if($controllerName == 'All' && $methodName == 'All') {
			if($module != 'shiksha') {
				$whereClauses[] = 'team = ?';
				$dataArr[] = $module;
			}
		}
		else {
			$whereClauses[] = ' controller_name = ?';
			$dataArr[] = $controllerName;
			$whereClauses[] = ' method_name = ?';
			$dataArr[] = $methodName;
		}
		
		if($todate) {
			$whereClauses[] = 'DATE(addition_date) >= ?';
			$dataArr[] = $selecteddate;
			$whereClauses[] = 'DATE(addition_date) <= ?';
			$dataArr[] = $todate;
		}
		else {
			$whereClauses[] = 'DATE(addition_date) = ?';
			$dataArr[] = $selecteddate;
		}

		if($sourceFile){
			$whereClauses[] = 'filename = ?';
			$dataArr[] = $sourceFile;
		}

		if($lineNum){
			$whereClauses[] = 'line_num = ?';
			$dataArr[] = $lineNum;
		}
		
		$query = "SELECT
				  a.id as id,
				  a.`error_msg` as error_msg,
				  a.`filename` as filename,
				  a.`line_num` as line_num,
				  a.`url` as url,
				  a.`error_msg` as error_msg,
				  a.`error_code` as error_code,
				  a.`referrer`
				  FROM `errorQueryLogs` a WHERE 
				  ".implode(" AND ", $whereClauses)."
				   ORDER by id asc " ;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }

    function getDbErrorsDetailedData($filters)
	{
    	$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by OccurenceTime desc";
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND DATE(addition_date) >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND DATE(addition_date) <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['orderby'] =='time')
			$orderby = " ORDER by OccurenceTime desc";
		else if($filters['orderby'] =='occurrence')
			$orderby = " ORDER by Occurences desc";

		$query = "SELECT
				  a.id as id,
				  a.module_name,
				  a.controller_name,
				  a.method_name,
				  a.`query` as Query,
				  a.`filename` as filename,
				  a.`line_num` as line_num,
				  a.`url` as url,
				  a.`error_msg` as error_msg,
				  a.`error_code` as error_code,
				  count(*) as Occurences,
				  max(a.`addition_date`) as OccurenceTime 
				  FROM `errorQueryLogs` a WHERE 1
				  ".$whereClause."
				  group by controller_name,method_name,filename,error_code,line_num ".$orderby;
		
		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
	}
	
	public function getAllCronErrors()
	{
        $this->initiateModel('read');
		$sql = "SELECT id, cron FROM cron_php_errors";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		
		return $results;
	}
	
	public function updateCronTeam($cronId, $teamName, $cronPath)
	{
		$this->initiateModel('write');
		//echo $cronId." -- ".$teamName." -- ".$cronPath."<br />";
		$sql = "UPDATE cron_php_errors SET cron = ?, team = ? WHERE id = ?";
		$this->dbHandle->query($sql, array($cronPath, $teamName, $cronId));	
	}
	
	public function getAllHighMemory()
	{
        $this->initiateModel('read');
		$sql = "SELECT id, module_name, controller_name FROM memorypages_live";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		
		return $results;
	}
	
	public function updateMemoryTeam($id, $teamName)
	{
		$this->initiateModel('write');
		//$sql = "UPDATE cron_php_errors SET team = ? WHERE id = ?";
		//$this->dbHandle->query($sql, array($teamName, $cronId));
		
		$sql = "UPDATE memorypages_live SET team = ? WHERE id = ?";
		$this->dbHandle->query($sql, array($teamName, $id));
	}
	
	public function getAllSlowPages()
	{
        $this->initiateModel('read');
		$sql = "SELECT id, module_name, controller_name FROM slowpages_live";
		$query = $this->dbHandle->query($sql);
		$results = $query->result_array();
		
		return $results;
	}
	
	public function updateSlowTeam($id, $teamName)
	{
		$this->initiateModel('write');
		//$sql = "UPDATE cron_php_errors SET team = ? WHERE id = ?";
		//$this->dbHandle->query($sql, array($teamName, $cronId));
		
		$sql = "UPDATE slowpages_live SET team = ? WHERE id = ?";
		$this->dbHandle->query($sql, array($teamName, $id));
	}

	public function getAllExceptions()
        {
        	$this->initiateModel('read');
                $sql = "SELECT id, module_name, controller_name FROM exceptionLogs";
                $query = $this->dbHandle->query($sql);
                $results = $query->result_array();

                return $results;
        }

	public function updateExceptionTeam($id, $teamName)
        {
                $this->initiateModel('write');
                //$sql = "UPDATE cron_php_errors SET team = ? WHERE id = ?";
                //$this->dbHandle->query($sql, array($teamName, $cronId));

                $sql = "UPDATE exceptionLogs SET team = ? WHERE id = ?";
                $this->dbHandle->query($sql, array($teamName, $id));
        }

	function getDashboardSlowPageStats($date)
	{
		
		$this->initiateModel('read');
	    $nextDate = date('Y-m-d',strtotime("+1 day",strtotime($date)));
        $sql = "SELECT team, count(*) as count from slowpages_live where log_time > '".$date."' AND log_time < '".$nextDate."'"." and is_cron = 'no' group by team";                       
		//$sql = "SELECT team, count(*) as count from slowpages_live where DATE(log_time) = '".$date."' group by team";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    	}

    function getDashboardHighSQLStats($date)
	{
		
		$this->initiateModel('read');
	    $nextDate = date('Y-m-d',strtotime("+1 day",strtotime($date)));
        $sql = "SELECT team, count(*) as count from highSQLQueries_live where log_time > '".$date."' AND log_time < '".$nextDate."'"."  and is_cron ='no'  group by team";                       
		//$sql = "SELECT team, count(*) as count from slowpages_live where DATE(log_time) = '".$date."' group by team";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		return $result;
    	}

	function getDashboardHighMemoryStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT team, count(*) as count from memorypages_live where log_time >= '".$date." 00:00:00' and log_time <= '".$date." 23:59:59' and is_cron = 'no' group by team";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }
		
		function getDashboardHighCacheStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT team, count(*) as count from heavycachepages_live where log_time >= '".$date." 00:00:00' and log_time <= '".$date." 23:59:59' and is_cron = 'no' group by team";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }

	function getDashboardCronErrorStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT team, count(*) as count from cron_php_errors where time >= '".$date." 00:00:00' and time <= '".$date." 23:59:59' group by team";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }


	function getDashboardDBErrorStats($date)
        {
                $this->initiateModel('read');
        
                $sql = "SELECT team, count(*) as count from errorQueryLogs where addition_date >= '".$date."' AND addition_date <= '".$date." 23:59:59' group by team";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }  
    function getDashboardSolrErrorStats($date)
        {
                $this->initiateModel('read');
        
                $sql = "SELECT team, count(*) as count from solrErrors where time >= '".$date."' AND time <= '".$date." 23:59:59' group by team";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }  

	function getDashboardExceptionStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT team, count(*) as count from exceptionLogs where addition_date >= '".$date."' AND addition_date <= '".$date." 23:59:59' group by team";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }

	function getDashboardSpearAlertStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT host, count(*) as count from alerts where alertTime >= '".$date."' AND alertTime <= '".$date." 23:59:59' group by host";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }	
		
    function getDashboardBotReportStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT status, count(*) as count from botdetection where created >= '".$date."' AND created <= '".$date." 23:59:59' AND action = 'show_captcha' group by status";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }	


	function getDashboardSlowQueryStats($date)
        {
                $this->initiateModel('read');

                $sql = "SELECT host, count(*) as count from slow_queries_live where record_time >= '".$date." 00:00:00' and record_time <= '".$date." 23:59:59' group by host";
                $query = $this->dbHandle->query($sql);
                $result = $query->result_array();
                return $result;
        }
	
	function logExceptions($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('exceptionLogs', $data);
	}
	
	function logDBErrors($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('errorQueryLogs', $data);
	}
	
	function logSlowPagesMasterData($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('slowPagesMaster', $data);
		return $this->dbHandle->insert_id();
	}
	
	function logSlowPagesDetailsData($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('slowPagesLogDetails', $data);
	}
	
	function logHighMemoryMasterData($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('highMemoryPagesMaster', $data);
		return $this->dbHandle->insert_id();
	}
	
	function logHighMemoryDetailsData($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('highMemoryPagesLogDetails', $data);
	}
	
	function logHighCacheMasterData($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('highCachePagesMaster', $data);
		return $this->dbHandle->insert_id();
	}
	
	function logHighCacheDetailsData($data)
	{
		$this->initiateModel('write');
		$this->dbHandle->insert('highCachePagesLogDetails', $data);
	}

	function fetchLastNDaysValue($time,$yesterdayDate,$n){
		$this->initiateModel('read');
		
		$endDate = $yesterdayDate;
		$startDate = date('Y-m-d',strtotime("-$n Days",strtotime($yesterdayDate)));
		
		$sql = "SELECT count,type FROM minuteWiseData where DATE(datetime)>= '".$startDate."' and DATE(datetime) <=  '".$endDate."' and datetime LIKE '%$time'";
		
		$query = $this->dbHandle->query($sql);
		
		return $query->result_array();

	}

	function dataEveryFiveMinutesAbnormality($dbColumnMapping,$metrices,$metricesTableMapping,$currentDate){
		$this->initiateModel('read');

		 $sql = 'SELECT EXTRACT(YEAR FROM '.$dbColumnMapping[$metrices].') AS year, EXTRACT( MONTH FROM '.$dbColumnMapping[$metrices].' )'.
		            ' AS MONTH , EXTRACT( DAY FROM '.$dbColumnMapping[$metrices].' ) AS DAY , EXTRACT( HOUR FROM '.$dbColumnMapping[$metrices].' )'.
		            ' AS HOUR , EXTRACT(MINUTE FROM '.$dbColumnMapping[$metrices].' ) AS MINUTE , count( * ) AS cnt, '.
		            ' CONCAT( DATE_FORMAT( '.$dbColumnMapping[$metrices].', "%Y-%m-%d %H:%i" ) , ":00" ) AS formated_date '.
		            ' FROM  '.$metricesTableMapping[$metrices].' WHERE date( '.$dbColumnMapping[$metrices].' ) = "'.$currentDate.'"'.
		            ' GROUP BY year, MONTH , DAY , HOUR , MINUTE';

        $query = $this->dbHandle->query($sql);
        
        return $query->result_array();
	}


	function insertMinuteWiseData($data){
		$this->initiateModel('write');
		$this->dbHandle->insert_batch("minuteWiseData", $data);
	}

	function trackClientSidePerformanceMetrics($data){

		$this->initiateModel('write');
		$this->dbHandle->insert("clientSidePerformanceDetails", $data);	
	}

	function aggregateDailyClientSideAggregateData(){

		$this->initiateModel('write');
		
		$date = date('Y-m-d',strtotime("0 Days"));

		$sql = "SELECT `pageName`,`trackingDate`,`device`,
				AVG(`speedScore`) as avg_speedScore,
				AVG(`usabilityScore`) as avg_usabilityScore ,
				AVG(`numberResources`) as avg_numberResources,
				AVG(`numberHosts`) as avg_numberHosts,
				AVG(`totalRequestBytes`) as avg_totalRequestBytes ,
				AVG(`numberStaticResources`) as avg_numberStaticResources,
				AVG(`htmlResponseBytes`) as avg_htmlResponseBytes,
				AVG(`cssResponseBytes`) as avg_cssResponseBytes,
				AVG(`imageResponseBytes`) as avg_imageResponseBytes,
				AVG(`javascriptResponseBytes`) as avg_javascriptResponseBytes,
				AVG(`otherResponseBytes`) as avg_otherResponseBytes,
				AVG(`numberJsResources`) as avg_numberJsResources,
				AVG(`numberCssResources`) as avg_numberCssResources 
				FROM `clientSidePerformanceDetails` WHERE trackingDate='".$date."' group by `device`,`pageName`";

		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();

		$this->dbHandle->where('trackingDate',$date);
		$this->dbHandle->update("clientSidePerformanceTable", array('status' => 'history'));

		$this->initiateModel('write');

		foreach ($result as $value) {

			$data = array();
			$data['pageName']                = $value['pageName'];
			$data['trackingDate']            = $value['trackingDate'];
			$data['device']            		 = $value['device'];
			$data['speedScore']              = $value['avg_speedScore'];
			$data['usabilityScore']          = $value['avg_usabilityScore'];
			$data['numberResources']         = $value['avg_numberResources'];
			$data['numberHosts']             = $value['avg_numberHosts'];
			$data['totalRequestBytes']       = $value['avg_totalRequestBytes'];
			$data['numberStaticResources']   = $value['avg_numberStaticResources'];
			$data['htmlResponseBytes']       = $value['avg_htmlResponseBytes'];
			$data['cssResponseBytes']        = $value['avg_cssResponseBytes'];
			$data['imageResponseBytes']      = $value['avg_imageResponseBytes'];
			$data['javascriptResponseBytes'] = $value['avg_javascriptResponseBytes'];
			$data['otherResponseBytes']      = $value['avg_otherResponseBytes'];
			$data['numberJsResources']       = $value['avg_numberJsResources'];
			$data['numberCssResources']      = $value['avg_numberCssResources'];
			$data['numberCssResources']      = $value['avg_numberCssResources'];
			$data['status']                  = 'live';

			$this->dbHandle->insert("clientSidePerformanceTable", $data);
			$id                              = $this->dbHandle->insert_id();

			$this->dbHandle->where('trackingDate',$date);
			$this->dbHandle->where('status','live');
			$this->dbHandle->where('pageName',$value['pageName']);
			$this->dbHandle->where('device',$value['device']);
			$this->dbHandle->update("clientSidePerformanceDetails", array('trackingId' => $id));
		}
	}

	function getDailyPerformanceData($page){
		
		$this->initiateModel('read');
		$sql = "SELECT * FROM `clientSidePerformanceTable` WHERE status='live' and pageName = ? and trackingDate > (NOW() - INTERVAL 20 DAY) group by `pageName`,`trackingDate`,`device` order by trackingDate asc";

		$query = $this->dbHandle->query($sql, array($page));
		$result = $query->result_array();

		return $result;
	}

	function fetchData($startDate,$endDate,$metric='slowquery',$server='')
	{
		$this->initiateModel('read');
		$result = array();

		if($metric == 'slowquery'){
			$serverWhere = "";
			if($server != "shiksha"){
				 $serverWhere = " AND server = '".$server."'";
			}
			$sql = "SELECT query,count,date,server FROM slow_queries where date BETWEEN ? AND ? $serverWhere";
			$query = $this->dbHandle->query($sql,array($startDate,$endDate));
			error_log($this->dbHandle->last_query());
			$result = $query->result_array();
		}
		return $result;
	}

	function getDataForMonitoringDiff($startDate, $endDate, $module, $dashboardType){
		$this->initiateModel('read');

		$whereClause = "";
		
		$dataArr = array();
		if($startDate){
			$whereClause .= " AND log_date >= ?";
			$dataArr[] = date("Y-m-d", strtotime($startDate));
		}
		if($endDate){
			$whereClause .= " AND log_date <= ?";
			$dataArr[] = date("Y-m-d", strtotime($endDate));
		}
		if($module && $module != 'shiksha'){
			$whereClause .= " AND team = ?";
			$dataArr[] = $module;
		}

		if($dashboardType == 'SlowPages'){
			$tableName = 'slowPagesMaster';
		}
		else if($dashboardType == 'HighMemoryPages'){
			$tableName = 'highMemoryPagesMaster';
		}
		else if($dashboardType == 'CacheHeavyPages'){
			$tableName = 'highCachePagesMaster';
		}
		
		if($tableName){
			$sql =  "SELECT team, module_name, controller_name, method_name, num_above_threshold ".
					" FROM ". $tableName.
					" WHERE num_above_threshold > 0 ".
					$whereClause;
							
			$query = $this->dbHandle->query($sql, $dataArr);
			
			$result = $query->result_array();
			return $result;
		}
	}
	function getDashBoardJSErrorStats($date)
	{
		$this->initiateModel('read');
		$sql = "SELECT teamName as team,count(*) as count FROM jsErrors WHERE addition_date >='".$date." 00:00:00' AND addition_date <='".$date." 23:59:59' group by teamName";
		$result = $this->dbHandle->query($sql);

		return $result->result_array();
	}
	function getRealTimeJSErrorData($selectedModule, $date)
	{
		$this->initiateModel('read');
		$sql = "SELECT HOUR(addition_date) h, MINUTE(addition_date) m, count(id) as count FROM jsErrors WHERE 1 AND addition_date >= '".$date."' AND addition_date <= '".$date." 23:59:59' ".($selectedModule != 'shiksha' ? " AND teamName = '".$selectedModule."'" : "")." GROUP BY h, m";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();

		return $result;
    }
    function getTopJSErrors($selectedModule, $date, $realtimeDataParams)
    {
		$db = $this->getReadHandle();
				
		$sql =  "SELECT `message` as msg,`jsPath` as file,`line_num`,col_num,`url`,`exception`,`addition_date`, teamName as team, isMobile,userAgent"." FROM jsErrors "."WHERE addition_date > '".$date." ".$realtimeDataParams['timeStart']."' "."AND addition_date <= '".$date." ".$realtimeDataParams['timeEnd']."' ".($selectedModule != 'shiksha' ? " AND teamName = '".$selectedModule."' " : " ")."ORDER BY addition_date ".$realtimeDataParams['order']." ".($realtimeDataParams['limit'] ? "LIMIT ".$realtimeDataParams['limit'] : "");
				
		$data = $db->query($sql)->result_array();
		return $data;
    }

	function getJSErrorsDetailedReportData($filters, $pageNumber=1, $rows=500)
	{
		$this->initiateModel('read');

		$whereClause = "";
		$orderby = " ORDER by OccurenceTime desc";
		$dataArr = array();
		if($filters['fromdate']){
			$whereClause .= " AND DATE(addition_date) >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromdate']));
		}
		if($filters['todate']){
			$whereClause .= " AND DATE(addition_date) <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['todate']));
		}
		if($filters['module'] && $filters['module'] != 'shiksha'){
			$whereClause .= " AND teamName = ?";
			$dataArr[] = $filters['module'];
		}
		if($filters['orderby'] =='time') {
			$orderby = " ORDER by OccurenceTime desc";
		}
		else if($filters['orderby'] =='occurrence') {
			$orderby = " ORDER by Occurences desc";
		}
		$limit = " LIMIT ".(($pageNumber-1)*$rows).",".$rows;
		
		$query = "SELECT SQL_CALC_FOUND_ROWS e.`id` as id,
				  e.`message` as errmsg,
				  e.`jsPath` as jsPath,
				  e.`line_num` as line_num,
				  e.`col_num` as col_num,
				  count(*) as Occurences,
				  max(e.`addition_date`) as OccurenceTime 
				  FROM `jsErrors` e WHERE 1
				  ".$whereClause."
				  group by errmsg,jsPath,line_num,col_num ".$orderby .$limit;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		$data['result'] = $result;

		$sql = "SELECT FOUND_ROWS() as totalRows";
		$query = $this->dbHandle->query($sql);
		$result = $query->result_array();
		$data['totalResults'] = $result[0]['totalRows'];

		return $data;
	}
	function getDayWiseJSErrors($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";

		$sql = "SELECT teamName as team, date(addition_date) as date, count(*) as count FROM jsErrors where addition_date >= '".$fromDate."' and addition_date <= '".$toDate."' ".($selectedModule != 'shiksha' ? " AND teamName = '".$selectedModule."'" : "")." group by team, date order by date ";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	
	function getUniqueDayWiseJSError($selectedModule, $fromDate, $toDate)
	{
		$this->initiateModel('read');

		$fromDate .= " 00:00:00";
		$toDate .=  " 23:59:59";
		
		$sql = "SELECT team, d AS date, COUNT(*) as count
				FROM (				
					SELECT DATE( addition_date ) AS d, teamName as team, message, jsPath, line_num,col_num
					FROM  `jsErrors`
					WHERE addition_date >= '".$fromDate."'
					AND addition_date <= '".$toDate."' ".
					($selectedModule != 'shiksha' ? " AND teamName = '".$selectedModule."'" : "")."
					GROUP BY d, team, message, jsPath, line_num,col_num
				) t
				GROUP BY t.team, t.d
				ORDER BY t.d";
		
	 	$query = $this->dbHandle->query($sql);
	 	$results = $query->result_array();
		$trends = array();
		foreach($results as $result) {
			$trends[$result['date']][$result['team']] = $result['count'];
		}

        return $trends;
	}
	function getJSErrorPageUrls($msg,$jsPath,$lineNum,$col_num,$selecteddate,$todate,$module)
	{
    	$this->initiateModel('read');


    	$dataArr = array();
		$whereClauses = array();
		
		if($module != 'shiksha') {
				$whereClauses[] = 'teamName = ?';
				$dataArr[] = $module;
		}

		if($todate) {
			$whereClauses[] = 'DATE(addition_date) >= ?';
			$dataArr[] = $selecteddate;
			$whereClauses[] = 'DATE(addition_date) <= ?';
			$dataArr[] = $todate;
		}
		else {
			$whereClauses[] = 'DATE(addition_date) = ?';
			$dataArr[] = $selecteddate;
		}

		if($lineNum){
			$whereClauses[] = 'line_num = ?';
			$dataArr[] = $lineNum;
		}
		if($col_num)
		{
			$whereClauses[] = 'col_num = ?';
			$dataArr[] = $col_num;
		}
		if(!empty($msg))
		{
			$whereClauses[] = 'message = ?';
			$dataArr[] = $msg;
		}
		if( !empty($jsPath))
		{
			$whereClauses[] = 'jsPath = ?';
			$dataArr[] = $jsPath;
		}
		
		$query = "SELECT
				  e.`id` as id,
				  e.`message` as msg,
				  e.`url` as url,
				  e.`userAgent` as userAgent,
				  e.`isMobile` as is_mobile
				  FROM `jsErrors` e WHERE 
				  ".implode(" AND ", $whereClauses)."
				   ORDER by id asc " ;

		$query = $this->dbHandle->query($query, $dataArr);
		$result = $query->result_array();
		return $result;
    }

	function storePageScore($data){
		$db = $this->getWriteHandle();
		$db->insert("pageSpeedScore", $data);
	}
	
	function getLatestPageScore($teamName){
		$db = $this->getReadHandle();
		
		//Get 24 hours earlier Date. Then, find all the entries for all pages made yesterday and display it.
		$todayDate = date("Y-m-d H:m:s");
		$date = date ( 'Y-m-j H:m:s' , strtotime("-24 hours",strtotime($todayDate)) );
		
		if($teamName!=''){
			$teamNameClause = " AND teamName = '$teamName' ";
		}
		$query = "SELECT pageName, teamName, URL, thirdParty, device, AVG(speedScore) as speedScore, AVG(usabilityScore) as usabilityScore FROM pageSpeedScore WHERE creationDate >= '$date' $teamNameClause GROUP BY pageName, device ORDER BY speedScore DESC";
		$query = $db->query($query);
		$result = $query->result_array();
		
		//Also, find avg page score for all entries in the past 7 days.
		$date = date ( 'Y-m-j H:m:s' , strtotime("-7 days",strtotime($todayDate)) );
		$query = "SELECT pageName, thirdParty, device, creationDate, AVG(speedScore) as speedScore, AVG(usabilityScore) as usabilityScore FROM pageSpeedScore WHERE creationDate >= '$date' $teamNameClause GROUP BY DATE(creationDate), pageName, device ORDER BY creationDate";
		$query = $db->query($query);
		$resultMulti = $query->result_array();

		return array($result,$resultMulti);
	}
	
	function getPageDetails($pageName, $device, $fromDate, $toDate){
		$db = $this->getReadHandle();
		$query = "SELECT * FROM pageSpeedScore WHERE DATE(creationDate) >= ? AND DATE(creationDate) <= ? AND pageName = ? AND device = ? ORDER BY id";
		$query = $db->query($query, array($fromDate, $toDate, $pageName, $device));
		$result = $query->result_array();
		return $result;		
	}

	/**
	* @Description: Function to store competitors score in DB
	* @args: Array with values that need to be stored
	* @return: None
	*/
	function storeCompetitorPageScore($data){
		$db = $this->getWriteHandle();
		$db->insert("pageCompetitorSpeedScore", $data);
	}

	/**
	* @Description: Function to store competitors score in DB with Google V5 (lighthouse)
	* @args: Array with values that need to be stored
	* @return: None
	*/
	function storeCompetitorPageScoreLH($data){
		$db = $this->getWriteHandle();
		$db->insert("pageCompetitorSpeedScoreLighthouse", $data);
	}
	
	function getLatestCompetitorPageScore($site){
		$db = $this->getReadHandle();
		
		//Get 24 hours earlier Date. Then, find all the entries for all pages made yesterday and display it.
		$todayDate = date("Y-m-d H:m:s");
		$date = date ( 'Y-m-j H:m:s' , strtotime("-24 hours",strtotime($todayDate)) );
		
		if($site!=''){
			$siteNameClause = " AND site = '$site' ";
		}

		$query = "SELECT team_name,pageName, site, URL, thirdParty, device, AVG(htmlResponseBytes) htmlBytes, AVG(fcp) as fcpVal, fcpCat, AVG(dcl) as dclVal, dclCat, AVG(speedScore) as speedScore, AVG(usabilityScore) as usabilityScore, AVG(serverResponseTime) as srtVal, AVG(javascriptResponseBytes) as jsBytes, AVG(cssResponseBytes) as cssBytes FROM pageCompetitorSpeedScore WHERE creationDate >= '$date' $siteNameClause GROUP BY site, pageName, device ORDER BY id ASC";
		$query = $db->query($query);
		$result = $query->result_array();
		
		//Also, find avg page score for all entries in the past 07 days.
		$date = date ( 'Y-m-j H:m:s' , strtotime("-7 days",strtotime($todayDate)) );
		$query = "SELECT team_name,pageName, site, thirdParty, device, AVG(htmlResponseBytes) htmlBytes, AVG(fcp) as fcpVal, fcpCat, AVG(dcl) as dclVal, dclCat, AVG(speedScore) as speedScore, AVG(usabilityScore) as usabilityScore, AVG(serverResponseTime) as srtVal, AVG(javascriptResponseBytes) as jsBytes, AVG(cssResponseBytes) as cssBytes FROM pageCompetitorSpeedScore WHERE creationDate >= '$date' $siteNameClause GROUP BY site, pageName, device ORDER BY id ASC";
		$query = $db->query($query);
		$resultWeek = $query->result_array();

		//Also, find avg page score for all entries in the past week
		$lastWeek = date ( 'Y-m-j H:m:s' , strtotime("-7 days",strtotime($date)) );
		$query = "SELECT team_name,pageName, site, thirdParty, device, AVG(htmlResponseBytes) htmlBytes, AVG(fcp) as fcpVal, fcpCat, AVG(dcl) as dclVal, dclCat, AVG(speedScore) as speedScore, AVG(usabilityScore) as usabilityScore, AVG(serverResponseTime) as srtVal, AVG(javascriptResponseBytes) as jsBytes, AVG(cssResponseBytes) as cssBytes FROM pageCompetitorSpeedScore WHERE creationDate < '$date' AND creationDate >= '$lastWeek' $siteNameClause GROUP BY site, pageName, device ORDER BY id ASC";
		$query = $db->query($query);
		$resultLastWeek = $query->result_array();

		return array($result,$resultWeek,$resultLastWeek);
	}
	
	function getCompetitorPageDetails($pageName, $device, $fromDate, $toDate){
		$db = $this->getReadHandle();
		$query = "SELECT team_name,pageName, site, creationDate, URL, thirdParty, device, ruleResult, AVG(numberOfResources) as numberOfResources, AVG(htmlResponseBytes) htmlResponseBytes, AVG(fcp) as fcp, fcpCat, AVG(dcl) as dcl, dclCat, AVG(speedScore) as speedScore, AVG(usabilityScore) as usabilityScore, AVG(serverResponseTime) as serverResponseTime, AVG(javascriptResponseBytes) as javascriptResponseBytes, AVG(cssResponseBytes) as cssResponseBytes FROM pageCompetitorSpeedScore WHERE DATE(creationDate) >= ? AND DATE(creationDate) <= ? AND pageName = ? AND device = ? GROUP BY DATE(creationDate),site ORDER BY id";
		$query = $db->query($query, array($fromDate, $toDate, $pageName, $device));
		$result = $query->result_array();
		return $result;		
	}

	function getLatestCompetitorPageScoreLH($site){
		$db = $this->getReadHandle();
		
		//Get 24 hours earlier Date. Then, find all the entries for all pages made yesterday and display it.
		$todayDate = date("Y-m-d H:m:s");
		$date = date ( 'Y-m-j H:m:s' , strtotime("-24 hours",strtotime($todayDate)) );
		
		if($site!=''){
			$siteNameClause = " AND site = '$site' ";
		}

		$query = "SELECT team_name,pageName, site, URL, thirdParty, device, AVG(googleScore) googleScore, AVG(fcp) as fcp, AVG(fmp) as fmp, AVG(speedIndex) as speedIndex, AVG(firstCPUIdle) as firstCPUIdle, AVG(mainThreadBreakdown) as mainThreadBreakdown, AVG(interactive) as interactive, AVG(inputLatency) as inputLatency, AVG(bootupTime) as bootupTime, AVG(ttfb) as ttfb, domSize FROM pageCompetitorSpeedScoreLighthouse WHERE creationDate >= '$date' $siteNameClause GROUP BY site, pageName, device ORDER BY id ASC";
		$query = $db->query($query);
		$result = $query->result_array();
		
		//Also, find avg page score for all entries in the past 07 days.
		$date = date ( 'Y-m-j H:m:s' , strtotime("-7 days",strtotime($todayDate)) );
		$query = "SELECT team_name,pageName, site, thirdParty, device, AVG(googleScore) googleScore, AVG(fcp) as fcp, AVG(fmp) as fmp, AVG(speedIndex) as speedIndex, AVG(firstCPUIdle) as firstCPUIdle, AVG(mainThreadBreakdown) as mainThreadBreakdown, AVG(interactive) as interactive, AVG(inputLatency) as inputLatency, AVG(bootupTime) as bootupTime, AVG(ttfb) as ttfb, domSize FROM pageCompetitorSpeedScoreLighthouse WHERE creationDate >= '$date' $siteNameClause GROUP BY site, pageName, device ORDER BY id ASC";
		$query = $db->query($query);
		$resultWeek = $query->result_array();

		//Also, find avg page score for all entries in the past week
		$lastWeek = date ( 'Y-m-j H:m:s' , strtotime("-7 days",strtotime($date)) );
		$query = "SELECT team_name,pageName, site, thirdParty, device, AVG(googleScore) googleScore, AVG(fcp) as fcp, AVG(fmp) as fmp, AVG(speedIndex) as speedIndex, AVG(firstCPUIdle) as firstCPUIdle, AVG(mainThreadBreakdown) as mainThreadBreakdown, AVG(interactive) as interactive, AVG(inputLatency) as inputLatency, AVG(bootupTime) as bootupTime, AVG(ttfb) as ttfb, domSize FROM pageCompetitorSpeedScoreLighthouse WHERE creationDate < '$date' AND creationDate >= '$lastWeek' $siteNameClause GROUP BY site, pageName, device ORDER BY id ASC";
		$query = $db->query($query);
		$resultLastWeek = $query->result_array();

		return array($result,$resultWeek,$resultLastWeek);
	}
	
	function getCompetitorPageDetailsLH($pageName, $device, $fromDate, $toDate){
		$db = $this->getReadHandle();
		$query = "SELECT team_name,pageName, site, creationDate, URL, thirdParty, device, ruleResult, AVG(googleScore) googleScore, AVG(fcp) as fcp, AVG(fmp) as fmp, AVG(speedIndex) as speedIndex, AVG(firstCPUIdle) as firstCPUIdle, AVG(mainThreadBreakdown) as mainThreadBreakdown, AVG(interactive) as interactive, AVG(inputLatency) as inputLatency, AVG(bootupTime) as bootupTime, AVG(ttfb) as ttfb, domSize FROM pageCompetitorSpeedScoreLighthouse WHERE DATE(creationDate) >= ? AND DATE(creationDate) <= ? AND pageName = ? AND device = ? GROUP BY DATE(creationDate),site ORDER BY id";
		$query = $db->query($query, array($fromDate, $toDate, $pageName, $device));
		$result = $query->result_array();
		return $result;		
	}

    /**
    * @Description: Function to get data for last 1 Week of Lighthouse
    * @args: None
    * @return: Array with the data
    */
    function getDataForMIS($teamName){
            $db = $this->getReadHandle();

            //Get 7 Days earlier Date.
            $todayDate = date("Y-m-d H:m:s");
            $date = $todayDate;

            //Also, find avg page score for all entries in the past week
            $lastWeek = date ( 'Y-m-j H:m:s' , strtotime("-7 days",strtotime($date)) );
            $query = "SELECT team_name, DATE(creationDate), pageName, site, thirdParty, device, AVG(googleScore) googleScore, AVG(fcp) as fcp, AVG(fmp) as fmp, AVG(speedIndex) as speedIndex, AVG(firstCPUIdle) as firstCPUIdle, AVG(mainThreadBreakdown) as mainThreadBreakdown, AVG(interactive) as interactive, AVG(inputLatency) as inputLatency, AVG(bootupTime) as bootupTime, AVG(ttfb) as ttfb, domSize FROM pageCompetitorSpeedScoreLighthouse WHERE creationDate < '$date' AND creationDate >= '$lastWeek' AND team_name = '$teamName' GROUP BY DATE(creationDate), site, pageName, device ORDER BY id ASC";
            $query = $db->query($query);
            $resultLastWeek = $query->result_array();

            return $resultLastWeek;
    }

	/**
	* @Description: Function to get crons from db
	* @args: $server is server ip. $status can be live or deleted else all are returned
	* @return: $crons is n X 3 array, where n is number of cron jobs, each consisting of cron expression, command and 				server
	*/
	public function getCronReport($server='Shiksha',$status='live')
	{
		$this->initiateModel('read');
		
		if($server != "Shiksha") {
			$this->dbHandle->where('serverIp', $server);
		}

		if($status=='live' || $status=='deleted'){
			$this->dbHandle->where('status', $status);
		}
		
		$rows = $this->dbHandle->select('cronExpression, cronCommand, serverIp');
		$query = $this->dbHandle->get('cronTab');
		$result = $query->result_array();
		
		$crons = array();
		$i=0;
		foreach($result as $row) {
			$crons[$i]['cronExp'] = $row['cronExpression'];
			$crons[$i]['cronStr'] = $row['cronCommand'];
			$crons[$i]['cronServer'] = $row['serverIp'];
			$i++;
		}
		return $crons;
	}


	/**
	* @Description: Function to update db with cron entries, previous ones corresponding to the server are marked 						deleted and new are inserted. Assumes all entries in $cronArr correspond to same server
	* @args: $cronArr is n X 3 array, where n is number of cron jobs, each consisting of cron expression, command and 				server
	* @return: Array where rowsDeleted represents no. of crons marked deleted in db, and rowsInserted represents no. of 			crons Inserted in db
	*/
	public function updateCronReport($cronArr)
	{
	
		$this->initiateModel('write');
		$this->dbHandle->trans_start();
		
		$rowsDeleted = "";$rowsInserted = "";

		if(sizeof($cronArr)>0){
			$this->dbHandle->where('serverIp', $cronArr[0]['cronServer']);
			$rowsDeleted = $this->dbHandle->update('cronTab', array('status' => 'deleted' ));
			if($rowsDeleted){
				$rowsDeleted = $this->dbHandle->affected_rows();
			}
		}

		$sqlArr = Array();
		foreach ($cronArr as $key => $row) {
			array_push($sqlArr, array('cronExpression'=>$row['cronExp'],'cronCommand'=>$row['cronStr'],
										'status'=>"live",'serverIp'=>$row['cronServer']));  
		}
		$rowsInserted = $this->dbHandle->insert_batch('cronTab', $sqlArr);
		if($rowsInserted){
			$rowsInserted = sizeof($sqlArr);
			//$rowsInserted = $this->dbHandle->affected_rows();//returns rows%100
		}
		
		$this->dbHandle->trans_complete();

		if ($this->dbHandle->trans_status() === FALSE) {
			throw new Exception('Transaction Failed');
		}

		return array('rowsDeleted' => $rowsDeleted, 'rowsInserted' => $rowsInserted);
	}

	function getDashBoardSQLiStats($date)
	{
		$this->initiateModel('read');
		$sql = "SELECT team,count(*) as count FROM sqliMaster WHERE status='pending' group by team";
		$result = $this->dbHandle->query($sql);

		return $result->result_array();
	}

	function getVulnerableCrons($date){

		$this->initiateModel('read');
		$sql = "SELECT team, module_name, controller_name, method_name, count(*) as cnt from cron_vulnerability where log_time >= '".$date." 00:00:00' and log_time <= '".$date." 23:59:59' group by controller_name,method_name order by cnt desc";
		$result = $this->dbHandle->query($sql);

		return $result->result_array();	
	}

	public function getExceptionDetails($id)
    {
        $result = array();
        if(empty($id)){
                return $result;
        }

        $this->initiateModel('read');
        $sql = "SELECT * FROM exceptionLogs where id=?";
        $query = $this->dbHandle->query($sql, array($id));
        $results = $query->row_array();

        return $results;
    }

    function getDashboardHTTPStatusCodeStats($date, $statusCode){
        $this->initiateModel('read');
        $this->dbHandle->select("status_code, sum(num_requests) count");
        $this->dbHandle->from("statusCodes");
        $this->dbHandle->where("date = ",$date);
        $this->dbHandle->where("status_code >= ",$statusCode);
        $this->dbHandle->group_by("status_code");
        $result = $this->dbHandle->get()->result_array();
        //echo $this->dbHandle->last_query();die;
        return $result;
    }

}
