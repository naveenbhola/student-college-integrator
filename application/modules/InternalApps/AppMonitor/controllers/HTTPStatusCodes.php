<?php

require 'AppMonitorAbstract.php';

class HTTPStatusCodes extends AppMonitorAbstract
{
    private static $statusCodeNames = array(
        "200" => "OK",
        "201" => "Created",
        "202" => "Accepted",
        "203" => "Non-authoritative Information",
        "204" => "No Content",
        "205" => "Reset Content",
        "206" => "Partial Content",
        "207" => "Multi-Status",
        "208" => "Already Reported",
        "226" => "IM Used",
        "300" => "Multiple Choices",
        "301" => "Moved Permanently",
        "302" => "Found",
        "303" => "See Other",
        "304" => "Not Modified",
        "305" => "Use Proxy",
        "307" => "Temporary Redirect",
        "308" => "Permanent Redirect",
        "400" => "Bad Request",
        "401" => "Unauthorized",
        "402" => "Payment Required",
        "403" => "Forbidden",
        "404" => "Not Found",
        "405" => "Method Not Allowed",
        "406" => "Not Acceptable",
        "407" => "Proxy Authentication Required",
        "408" => "Request Timeout",
        "409" => "Conflict",
        "410" => "Gone",
        "411" => "Length Required",
        "412" => "Precondition Failed",
        "413" => "Payload Too Large",
        "414" => "Request-URI Too Long",
        "415" => "Unsupported Media Type",
        "416" => "Requested Range Not Satisfiable",
        "417" => "Expectation Failed",
        "418" => "I'm a teapot",
        "421" => "Misdirected Request",
        "422" => "Unprocessable Entity",
        "423" => "Locked",
        "424" => "Failed Dependency",
        "426" => "Upgrade Required",
        "428" => "Precondition Required",
        "429" => "Too Many Requests",
        "431" => "Request Header Fields Too Large",
        "444" => "Connection Closed Without Response",
        "451" => "Unavailable For Legal Reasons",
        "499" => "Client Closed Request",
        "500" => "Internal Server Error",
        "501" => "Not Implemented",
        "502" => "Bad Gateway",
        "503" => "Service Unavailable",
        "504" => "Gateway Timeout",
        "505" => "HTTP Version Not Supported",
        "506" => "Variant Also Negotiates",
        "507" => "Insufficient Storage",
        "508" => "Loop Detected",
        "510" => "Not Extended",
        "511" => "Network Authentication Required",
        "599" => "Network Connect Timeout Error",
        "0" => "Unknown"
    );
    
	function __construct()
	{
		parent::__construct();
	}
	
	function trends($codeType = "5xx")
	{
        $codeTypeMapping = array(
            '5xx' => array(500, 1000),
            '4xx' => array(400, 500),
            '3xx' => array(300, 400),
            '2xx' => array(200, 300),
            '0xx' => array(0, 200)
        );
        
		$displayData['dashboardType'] = ENT_DASHBOARD_TYPE_HTTPSTATUSCODES;
		$displayData['reportType'] = $codeType;
		
		$toDate = date('Y-m-d', strtotime('-1 Day'));
        $fromDate = $this->model->getHTTPStatusCodeFromDate();
        if(!$fromDate) {
            $fromDate = date('Y-m-d',strtotime('-30 days',strtotime($toDate)));
        }
		
		$statusCodeTrends = $this->model->getHTTPStatusCodeTrends($fromDate, $toDate);
        $trends = array();
        while($fromDate <= $toDate) {
            foreach($statusCodeTrends as $code => $dateWiseTrend) {
                if($code >= $codeTypeMapping[$codeType][0] && $code < $codeTypeMapping[$codeType][1]) {
                    $trends[$code][] = array($fromDate, intval($dateWiseTrend[$fromDate]));
                }
            }
            $fromDate = date('Y-m-d',strtotime('+1 day',strtotime($fromDate)));
        }
        
		$displayData['statusCodeTrendsChartData'] = $trends;
        $displayData['reportLinks'] = array(
            '5xx' => array('Server Error (5xx)', '/AppMonitor/HTTPStatusCodes/trends/5xx'),
            '4xx' => array('Client Error (4xx)', '/AppMonitor/HTTPStatusCodes/trends/4xx'),
            '3xx' => array('Redirection (3xx)', '/AppMonitor/HTTPStatusCodes/trends/3xx'),
            '2xx' => array('Success (2xx)', '/AppMonitor/HTTPStatusCodes/trends/2xx'),
            '0xx' => array('Others', '/AppMonitor/HTTPStatusCodes/trends/0xx'),
            'detailedreport' => array('Detailed Report', '/AppMonitor/HTTPStatusCodes/detailedreport'),
        );
		
        $displayData['statusCodeNames'] = self::$statusCodeNames;
		$this->load->view("AppMonitor/httpStatusCodes/trends", $displayData);		
	}

    function detailedreport($statusCode = '')
    {
        //http://localshiksha.com/AppMonitor/HTTPStatusCodes/detailedreport
        $displayData = array();
        
        $displayData['dashboardType'] = ENT_DASHBOARD_TYPE_HTTPSTATUSCODES;
        $displayData['reportType'] = 'detailedreport';
        $displayData['reportLinks'] = array(
            '5xx' => array('Server Error (5xx)', '/AppMonitor/HTTPStatusCodes/trends/5xx'),
            '4xx' => array('Client Error (4xx)', '/AppMonitor/HTTPStatusCodes/trends/4xx'),
            '3xx' => array('Redirection (3xx)', '/AppMonitor/HTTPStatusCodes/trends/3xx'),
            '2xx' => array('Success (2xx)', '/AppMonitor/HTTPStatusCodes/trends/2xx'),
            '0xx' => array('Others', '/AppMonitor/HTTPStatusCodes/trends/0xx'),
            'detailedreport' => array('Detailed Report', '/AppMonitor/HTTPStatusCodes/detailedreport'),
        );
        
        $displayData['ajaxURL'] = "/AppMonitor/HTTPStatusCodes/getDetailedReportData/".$statusCode;
        $displayData['defaultDate'] = $this->detailedReportDate;
        $displayData['statusCode'] = $statusCode;
        $statusCodeNames = self::$statusCodeNames;
        //$displayData['httpCodes'] = array("" => "All")+self::$statusCodeNames;
        $displayData['statusCodes'] = array("All" => "All");
        $displayData['statusCodes']["4xx"] = "Client Error";
        $displayData['statusCodes']["5xx"] = "Server Error";
        foreach ($statusCodeNames as $statusCode => $statusCodeName) {
            if($statusCode >=400 && $statusCode !=404){
                $displayData['statusCodes'][$statusCode] = $statusCodeName;
            }
        }
        $displayData['frontEndServers'] = array("" => "All","91" => "91","92" => "92","93" => "93");
        $this->load->view("AppMonitor/common/detailedReport", $displayData);
    }

    function getDetailedReportData($statusCode =''){
        $filters['fromdate'] = date("Y-m-d", strtotime($this->input->post("fromdate",true)));
        $filters['todate'] = date("Y-m-d", strtotime($this->input->post("todate",true)));
        $filters['statusCode'] = $this->input->post("statusCode",true);
        $filters['frondEndServer'] = $this->input->post("frondEndServer",true);

        if($filters['statusCode'] == ""){
            $filters['statusCode'] = $statusCode;
        }
        
        $data = $this->model->getHTTPStatusCodeDetailedData($filters);
        $groupedData = array();
        $sumHits = 0;
        $sumTime = 0;
        $sumAboveThreshold = 0;
        foreach($data as $row) {
            //_p($row);
            $key = $row['status_code']."~".$row['request_method']."~".$row['host']."~".$row['request_uri'];
            $groupedData[$key]['hits'] += 1;
        }
        //_p($groupedData);die;
        $displayData = array();
        $displayData['data'] = $finalData;
        $displayData['dashboard'] = ENT_DASHBOARD_TYPE_SLOWPAGES;
        $displayData['filters'] = $filters;
        $displayData['selectedModule'] = $module;
        $displayData['ajaxURLList'] = "/AppMonitor/SlowPages/getURLList";
        $displayData['data'] = $data;
    
        $this->load->view("AppMonitor/httpStatusCodes/detailedReport", $displayData);
    }
}
