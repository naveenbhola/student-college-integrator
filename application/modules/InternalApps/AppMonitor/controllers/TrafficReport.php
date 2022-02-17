<?php

require 'AppMonitorAbstract.php';

class TrafficReport extends AppMonitorAbstract
{
	function __construct()
	{
		parent::__construct();
	}
	
	function index($reportType = 'IP')
	{
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_TRAFFICREPORT;
		$displayData['reportType'] = $reportType;
	
        $displayData['reportLinks'] = array(
            'IP' => array('IP Report', '/AppMonitor/TrafficReport/index/IP'),
            'userAgent' => array('User Agent Report', '/AppMonitor/TrafficReport/index/userAgent'),
            'ISP' => array('ISP Report', '/AppMonitor/TrafficReport/index/ISP'),
            'organization' => array('Organization Report', '/AppMonitor/TrafficReport/index/organization')
        );
		
		$displayData['ajaxURL'] = "/AppMonitor/TrafficReport/getDetailedReportData";
        //$displayData['ajaxURL'] = "/AppMonitor/TrafficReport/IPAnalysis";
		$displayData['defaultDate'] = $this->detailedReportDate;
		$this->load->view("AppMonitor/common/detailedReport", $displayData);
	}
	
	function getDetailedReportData()
	{
		$toDate = date('Ymd',strtotime($this->input->post('todate')));
		$fromDate = date('Ymd',strtotime($this->input->post('fromdate')));	
        $reportType = $this->input->post('reportType');
		
        if($reportType == 'IP') {
            $query = "select ip, ISP, organization, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and ip <> '127.0.0.1' group by ip, ISP, organization order by `count` desc limit 100";
        }
        else if($reportType == 'userAgent') {
            $query = "select userAgent, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' group by userAgent order by `count` desc limit 100";
        }
        else if($reportType == 'ISP') {
            $query = "select ISP, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' group by ISP order by `count` desc limit 100";
        }
        else if($reportType == 'organization') {
            $query = "select organization, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' group by organization order by `count` desc limit 100";
        }
        
        $queryData = $this->getDataForQuery($query);		
		
		$data['fromDate'] = $fromDate;
		$data['toDate'] = $toDate;
		$data['reportType'] = $reportType;

        $data['result'] = $queryData;
		$this->load->view("AppMonitor/trafficReport/middlePanel",$data);
 	}
    
    function TrafficRequests()
    {
        $toDate = date('Ymd',strtotime($this->input->post('todate')));
		$fromDate = date('Ymd',strtotime($this->input->post('fromdate')));	
        $clauseType = $this->input->post('clauseType');
        $clauseValue = $this->input->post('clauseValue');
        //$ip = "66.249.69.184";
        $pageNumber = intval($this->input->post('pageNumber'));
        if(!$pageNumber) {
            $pageNumber = 1;
        }
        
        $offset = ($pageNumber-1) * 2000;
        
        if($clauseType == 'IP') {
            $query = "select count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and ip = '".$clauseValue."'";
        }
        else if($clauseType == 'userAgent') {
            $query = "select count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and userAgent = '".$clauseValue."'";
        }
        else if($clauseType == 'ISP') {
            $query = "select count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and ISP = '".$clauseValue."'";
        }
        else if($clauseType == 'organization') {
            $query = "select count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and organization = '".$clauseValue."'";
        }
        
        $queryData = $this->getDataForQuery($query);
        $totalRequests = intval($queryData[0]['count']);
        $numPages = ceil($totalRequests/2000);
        
        if($clauseType == 'IP') {
            $query = "select `datetime`, `method`, `url` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and ip = '".$clauseValue."' order by `datetime` limit 2000 offset ".$offset;
        }
        else if($clauseType == 'userAgent') {
            $query = "select `datetime`, `method`, `url` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and userAgent = '".$clauseValue."' order by `datetime` limit 2000 offset ".$offset;
        }
        else if($clauseType == 'ISP') {
            $query = "select `datetime`, `method`, `url` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and ISP = '".$clauseValue."' order by `datetime` limit 2000 offset ".$offset;
        }
        else if($clauseType == 'organization') {
            $query = "select `datetime`, `method`, `url` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and organization = '".$clauseValue."' order by `datetime` limit 2000 offset ".$offset;
        }
        
        $queryData = $this->getDataForQuery($query);
        $data['result'] = $queryData;
        
        if($clauseType == 'IP') {
            $query = "select `ISP`, `organization` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$fromDate."' and dir0 <= '".$toDate."' and ip = '".$clauseValue."' limit 1";
            $queryData = $this->getDataForQuery($query);
            $data['extraHeading'] = $queryData[0]['ISP'].":".$queryData[0]['organization'];
        }
        
        
        $data['nextPageNumber'] = $pageNumber < $numPages ? $pageNumber+1 : 0;
        $data['previousPageNumber'] = $pageNumber > 1 ? $pageNumber-1 : 0;
        $data['clauseType'] = $clauseType;
        $data['clauseValue'] = $clauseValue;
        $data['offset'] = $offset;
		$this->load->view("AppMonitor/trafficReport/trafficRequests", $data);
    }
    
    function drilldown()
    {
        $clauseType = $this->input->post('clauseType');
        $clauseValue = $this->input->post('clauseValue');
        $startDate = $this->input->post('startDate');
        $endDate = $this->input->post('endDate');
        
        $reportMap = array(
            'userAgent' => array('heading' => 'User Agents', 'column' => 'User Agent'),
            'IP' => array('heading' => 'IPs', 'column' => 'IP'),
            'method' => array('heading' => 'HTTP Methods', 'column' => 'Method')
        );
        
        $reports = array();
        
        if($clauseType == 'IP') {
        
            $query = "select userAgent, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and ip = '".$clauseValue."' group by userAgent order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['userAgent'] = $this->getRowMap($queryData, 'userAgent');
            
            $query = "select `method`, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and ip = '".$clauseValue."' group by `method` order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['method'] = $this->getRowMap($queryData, 'method');
        }
        else if($clauseType == 'userAgent') {
            
            $query = "select ip, ISP, organization, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and userAgent = '".$clauseValue."' group by ip, ISP, organization order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            
            $reports['IP'] = $this->getMultiColumnRowMap($queryData, 'ip', array('ISP', 'organization'));
            
            $query = "select `method`, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and userAgent = '".$clauseValue."' group by `method` order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['method'] = $this->getRowMap($queryData, 'method');
        }
        else if($clauseType == 'ISP') {
            $query = "select ip, ISP, organization, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and ISP = '".$clauseValue."' group by ip, ISP, organization order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            
            $reports['IP'] = $this->getMultiColumnRowMap($queryData, 'ip', array('ISP', 'organization'));
            
            $query = "select userAgent, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and ISP = '".$clauseValue."' group by userAgent order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['userAgent'] = $this->getRowMap($queryData, 'userAgent');
            
            $query = "select `method`, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and ISP = '".$clauseValue."' group by `method` order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['method'] = $this->getRowMap($queryData, 'method');
        }
        else if($clauseType == 'organization') {
            $query = "select ip, ISP, organization, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and organization = '".$clauseValue."' group by ip, ISP, organization order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            
            $reports['IP'] = $this->getMultiColumnRowMap($queryData, 'ip', array('ISP', 'organization'));
            
            $query = "select userAgent, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and organization = '".$clauseValue."' group by userAgent order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['userAgent'] = $this->getRowMap($queryData, 'userAgent');
            
            $query = "select `method`, count(*) `count` from dfs.logdrill.`apache/accesslogs` where dir0 >= '".$startDate."' and dir0 <= '".$endDate."' and organization = '".$clauseValue."' group by `method` order by `count` desc limit 10";
            $queryData = $this->getDataForQuery($query);
            $reports['method'] = $this->getRowMap($queryData, 'method');
        }
        
        $data['reports'] = $reports;
        $data['reportMap'] = $reportMap;
        
        $this->load->view("AppMonitor/trafficReport/drilldown", $data);
    }
    
    function getRowMap($rows, $columnName, $otherColumns)
    {
        $rowMap = array();
        foreach($rows as $row) {
            if(is_array($otherColumns) && count($otherColumns) > 0) {
                    
            }
            else {
                $rowMap[$row[$columnName]] = $row['count'];
            }
        }
        return $rowMap;
    }
    
    function getMultiColumnRowMap($rows, $columnName, $otherColumns)
    {
        $rowMap = array();
        foreach($rows as $row) {
            $thisRowMap = array();
            $thisRowMap['count'] = $row['count'];
            foreach($otherColumns as $column) {
                $thisRowMap[$column] = $row[$column];
            }
            $rowMap[$row[$columnName]] = $thisRowMap;
        }
        return $rowMap;
    }
    
    function getDataForQuery($query)
    {
        $url = "http://10.10.16.101:8047/query.json";
        $data = array(
                      "queryType" => "SQL",
                      "query" => $query
                    );
        $data_json = json_encode($data);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json','Content-Length: ' . strlen($data_json)));
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data_json);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $response = curl_exec($ch);
        $response = json_decode($response, true);
        return $response['rows'];
    }
}
