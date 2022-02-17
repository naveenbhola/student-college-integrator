<?php
class ErrorExceptionLogModel extends MY_Model
{
        function __construct()
        {
                parent::__construct('Listing');
        }

	public function getStatusCodeData($date)
	{
		$dbHandle = $this->getReadHandleByModule('AppMonitor');
		$sql =  "select status_code, sum(num_requests) as num_requests ".
			"from statusCodes ".
			"where date = ? ".
			"group by status_code ".
			"order by num_requests desc";
	
		$query = $dbHandle->query($sql, array($date));
		return $query->result_array();
	}

	public function getBadGatewayErrors($start, $end)
	{
		$dbHandle = $this->getReadHandleByModule('AppMonitor');
                $from_24hrs = date("Y-m-d H:i:s",strtotime("-24 hour"));

		if($start && $end) {
			$sql = "SELECT url, server, time FROM badGatewayLogs WHERE time >= ? and time <= ? order by id desc";
			$query = $dbHandle->query($sql, array($start, $end));
		}
		else {
			$sql = "SELECT url, server, time FROM badGatewayLogs WHERE time > ? order by id desc";
			$query = $dbHandle->query($sql, array($from_24hrs));
		}
		return $query->result_array();	
	}

	public function getBadGatewayDataAlerts($time)
	{
		$dbHandle = $this->getReadHandleByModule('AppMonitor');
                $from_crontime = date("Y-m-d H:i:s",strtotime("-$time second"));
                $from_24hrs = date("Y-m-d H:i:s",strtotime("-24 hour"));

		$sql = "SELECT count(id) as c FROM badGatewayLogs WHERE time > ?";
		
		$query = $dbHandle->query($sql, array($from_crontime));
                $row = $query->row_array();
                $numErrors = $row['c'];

                $query = $dbHandle->query($sql, array($from_24hrs));
                $row = $query->row_array();
                $numErrors_24Hours = $row['c'];

		return array($numErrors, $numErrors_24Hours);
	}

        public function getDataForAlert($time)
        {
                $dbHandle = $this->getReadHandleByModule('AppMonitor');
                $from_crontime = date("Y-m-d H:i:s",strtotime("-$time second"));
                $from_24hrs = date("Y-m-d H:i:s",strtotime("-24 hour"));

                $exceptionSql = "SELECT count(id) as c FROM exceptionLogs WHERE addition_date > ?";
                $queryErrorSql = "SELECT count(id) as c FROM errorQueryLogs WHERE addition_date > ?";

                $query = $dbHandle->query($exceptionSql, array($from_crontime));
                $row = $query->row_array();
                $numExceptions = $row['c'];

                $query = $dbHandle->query($exceptionSql, array($from_24hrs));
                $row = $query->row_array();
                $numExceptions_24Hours = $row['c'];

                $query = $dbHandle->query($queryErrorSql, array($from_crontime));
                $row = $query->row_array();
                $numQueryErrors = $row['c'];

                $query = $dbHandle->query($queryErrorSql, array($from_24hrs));
                $row = $query->row_array();
                $numQueryErrors_24Hours = $row['c'];

                return array('exceptions' => array($numExceptions, $numExceptions_24Hours), 'queryErrors' => array($numQueryErrors, $numQueryErrors_24Hours));
        }

	public function getErrorQueryLogs()
	{
	    	$query = "SELECT *
			  		  FROM queryFailureLogs ";
	
			$data = $this->db->query($query)->result_array();
			return $data;
	}

	public function getQueryErrorLogs($filters)
	{

		$whereClause = "";
		$dataArr = array();
		if($filters['fromDate']){
			$whereClause .= " AND DATE(addTime) >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromDate']));
		}
		if($filters['toDate']){
			$whereClause .= " AND DATE(addTime) <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['toDate']));
		}

		$query = "SELECT
				  a.id as id,
				  a.`query` as Query,
				  a.`callingScript` as CallingScript,
				  a.`lineNumber` as LineNo,
				  a.`pageURL` as URL,
				  a.`error_text` as ErrorMessage,
				  a.`errorCode` as ErrorCode,
				  count(*) as Occurences,
				  max(a.`time`) as OccurenceTime 
				  FROM `errorQueryLogs` a WHERE 1
				  ".$whereClause."
				  group by callingScript,errorCode,lineNumber
				  order by OccurenceTime desc ";

		$data = $this->db->query($query, $dataArr)->result_array();
		return $data;
	}
	
	public function getExceptionLogs($filters)
	{
		$whereClause = "";
		$dataArr = array();
		if($filters['fromDate']){
			$whereClause .= " AND DATE(addition_date) >= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['fromDate']));
		}
		if($filters['toDate']){
			$whereClause .= " AND DATE(addition_date) <= ?";
			$dataArr[] = date("Y-m-d", strtotime($filters['toDate']));
		}
		if($filters['errorClass']){
			$whereClause .= " AND error_class = ?";
			$dataArr[] = $filters['errorClass'];
		}

		$query = "SELECT
				  a.id as id,
				  a.`exception_msg` as ErrorMessage,
				  a.`source_file` as sourceFile,
				  a.`line_num` as LineNo,
				  a.`url` as URL,
				  a.`error_class` as ErrorClass,
				  a.`error_code` as ErrorCode,
				  count(*) as Occurences,
				  max(a.`addition_date`) as OccurenceTime 
				  FROM `exceptionLogs` a WHERE 1
				  ".$whereClause."
				  group by `exception_msg`,`source_file`,`error_code`
				  order by OccurenceTime desc ";

		$data = $this->db->query($query, $dataArr)->result_array();
		return $data;
	}
	
	public function getSlowQueryLogs()
	{
    	$query = "SELECT *
				  FROM querySlowLogs";

		$data = $this->db->query($query)->result_array();
		return $data;
	}
	
	public function logErrorQuery1($finalArr)
	{
	    	$this->db = $this->getWriteHandle();
		$query = array();
		
		foreach($finalArr as $detailsArr)
		{
		    // value assignment
			$mysql_error_code = isset($detailsArr['error_num'])?$detailsArr['error_num']:'0';
			$mysql_error_text = isset($detailsArr['error_msg'])?$detailsArr['error_msg']:'none';
			$callingScripts   = isset($detailsArr['filename'])?$detailsArr['filename']:'none';
			$sql              = isset($detailsArr['error_query'])?$detailsArr['error_query']:'';
			$url              = isset($detailsArr['referer'])?$detailsArr['referer']:'';
			$date             = isset($detailsArr['date'])?$detailsArr['date']:'';
			$line_num         = isset($detailsArr['line_number'])?$detailsArr['line_number']:0;
		    		
		    $query[] = sprintf("('%s','%s',%d,'%s','%s',%d,'%s')",
								 mysql_escape_string($sql),
								 mysql_escape_string($callingScripts),
								 $line_num,
								 mysql_escape_string($url),
								 mysql_escape_string($mysql_error_text),
								 $mysql_error_code,
								 $date);
						}
		
		$query = implode(",", $query);
		
		$query = "INSERT INTO errorQueryLogs(query, callingScript, lineNumber, pageURL, error_text, errorCode, time ) VALUES  "
			  .$query;

		$this->db->query($query);
	    
	}
	
	public function logExceptions($finalArr)
	{
    	$this->db = $this->getWriteHandle();
		$query = array();
		foreach($finalArr as $detailsArr)
		{
		    // value assignment
			$mysql_error_code = isset($detailsArr['code'])?$detailsArr['code']:'0';
			$mysql_error_text = isset($detailsArr['exceptionMsg'])?$detailsArr['exceptionMsg']:'none';
			$callingScripts   = isset($detailsArr['file'])?$detailsArr['file']:'none';
			$error_class      = isset($detailsArr['class'])?$detailsArr['class']:'';
			$url              = isset($detailsArr['url'])?$detailsArr['url']:'';
			$date             = isset($detailsArr['date'])?$detailsArr['date']:'';
			$line_num         = isset($detailsArr['line'])?$detailsArr['line']:0;
				
		    $query[] = sprintf("('%s','%s',%d,'%s',%d,'%s','%s')",
								 mysql_escape_string($mysql_error_text),
								 mysql_escape_string($callingScripts),
								 $line_num,
								 mysql_escape_string($url),
								 $mysql_error_code,
								 mysql_escape_string($error_class),
								 $date);
		}
		
		$query = implode(",", $query);
		
		$query = "INSERT INTO exceptionLogs(exception_msg, source_file, line_num, url, error_code, error_class, addition_date ) VALUES  "
			  .$query;

		$this->db->query($query);
	}
	
	public function logSlowQuery($backtraceObj, $sql, $timeTaken, $conn_id)
	{
    	$this->db = $this->getWriteHandle();
		
		// initialization
		$host_info      = "";
		$callingScripts = array();
		$url            = "";
		
		// value calculation and assignment
		$host_info		= $conn_id->host_info;
		$url			= $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		foreach( $backtraceObj as $file )
		{
			$callingScripts[] = $file['file']. " $$ ".$file['class']. " $$ ".$file['function']. " $$ ".$file['line'];
		}
		
		$callingScripts = implode(" @@@ ",$callingScripts);
		
		$query = sprintf("INSERT INTO querySlowLogs(query, callingScript, pageURL, timeTaken, hostInfo ) VALUES ('%s','%s','%s','%s','%s')",
						 mysql_escape_string($sql),
						 mysql_escape_string($callingScripts),
						 mysql_escape_string($url),
						 $timeTaken,
						 mysql_escape_string($host_info));
		
		$this->db->query($query);
	}
	
	
	public function logErrorQuery($backtraceObj, $sql, $conn_id)
	{
    	$this->db = $this->getWriteHandle();
		
		// initialization
		$host_info        = "";
		$mysql_error_text = "";
		$mysql_error_code = "";
		$callingScripts   = array();
		$url              = "";
		
		// value calculation and assignment
		$host_info        = $conn_id->host_info;
		$mysql_error_text = $conn_id->error;
		$mysql_error_code = $conn_id->errno;
		$url              = $_SERVER['HTTP_HOST'].$_SERVER['REQUEST_URI'];
		
		foreach( $backtraceObj as $file )
		{
			$filename = $file['file'];
			$line_num = $file['line'];
			break;
		}
		
		$callingScripts = implode(" @@@ ",$callingScripts);
		
		$query = sprintf("INSERT INTO errorQueryLogs(query, callingScript, lineNumber, pageURL, error_text, errorCode, time ) VALUES ('%s','%s','%s','%s','%s',%d,%s)",
				 mysql_escape_string($sql),
				 mysql_escape_string($filename),
				 mysql_escape_string($line_num),
				 mysql_escape_string($url),
				 mysql_escape_string($mysql_error_text),
				 $mysql_error_code,
				 'now()');

		$this->db->query($query);
	}
	
	public function getLatestErrorQueryTime()
	{
	    $query = "SELECT max( `time` ) as time
				FROM `errorQueryLogs`";

	    $data = $this->db->query($query)->result_array();
	    return $data;
	}
	
	public function getLatestExceptionTime()
	{
	    $query = "SELECT max( `addition_date` ) as time
			FROM `exceptionLogs`";

	    $data = $this->db->query($query)->result_array();
	    return $data;
	}
}
