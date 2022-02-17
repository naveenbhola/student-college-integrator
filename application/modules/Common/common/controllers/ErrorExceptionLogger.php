<?php

class ErrorExceptionLogger extends MX_Controller
{
        function sendAlert()
        {
        		$this->validateCron();
                $this->load->model('common/errorexceptionlogmodel');
                // get the objects of the model
                $this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;

                $data = $this->ErrorExceptionLogModelObj->getDataForAlert(1800);
                $numErrors = intval($data['exceptions'][0]) + intval($data['queryErrors'][0]);
		if($numErrors > 0) {

                $message = "<h3>Last 30 Minutes:</h3>";
                $message .= "<a href='http://www.shiksha.com/common/ErrorExceptionLogger/showExceptionLogs'><b>Exceptions:</b> ".$data['exceptions'][0]."</a><br /><br />".
                            "<a href='http://www.shiksha.com/common/ErrorExceptionLogger/showQueryErrorLogs'><b>Query Errors:</b> ".$data['queryErrors'][0]."</a>";

                $message .= "<h3>Last 24 Hours:</h3>";
                $message .= "<a href='http://www.shiksha.com/common/ErrorExceptionLogger/showExceptionLogs'><b>Exceptions:</b> ".$data['exceptions'][1]."</a><br /><br />".
                            "<a href='http://www.shiksha.com/common/ErrorExceptionLogger/showQueryErrorLogs'><b>Query Errors:</b> ".$data['queryErrors'][1]."</a>";

                $email_from = "Production Critical Alerts <ProductionCriticalAlerts@shiksha.com>"; // Who the email is from
                $email_subject = "Production Alert: $numErrors errors in last 30 minutes"; // The Subject of the email 
                $email_message = $message;

                $email_to = "ankur.gupta@shiksha.com,amit.kuksal@shiksha.com,vikas.k@shiksha.com,sukhdeep.kaur@99acres.com,abhinav.k@shiksha.com,aditya.roshan@shiksha.com,romil.goel@shiksha.com,pranjul.raizada@shiksha.com"; // Who the email is to
                //$email_to = "vikas.k@shiksha.com"; // Who the email is to

                $headers = "From: ".$email_from;
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                $headers .= "\nMIME-Version: 1.0\n" .
                            "Content-Type: multipart/mixed;\n" .
                            " boundary=\"{$mime_boundary}\"";

                $email_message .= "This is a multi-part message in MIME format.\n\n" .
"--{$mime_boundary}\n" .
"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" .
$email_message .= "\n\n";

                @mail($email_to, $email_subject, $email_message, $headers);

                error_log($numErrors);
		}
    }

	function showQueryErrorLogs()
	{
		$this->load->model('common/errorexceptionlogmodel');
		// get the objects of the model
		$this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;

		$filters = array();
		$isAjax                = $this->input->post("isajax");
		$filters["fromDate"] = $this->input->post("fromDate");
		$filters["toDate"]   = $this->input->post("toDate");

		$rs                = $this->ErrorExceptionLogModelObj->getQueryErrorLogs($filters);
		$displayData['rs'] = $rs;
		
		if(!$isAjax)
			$this->load->view("common/viewErrorLogListing", $displayData);
		else
			$this->load->view("common/errorTableView", $displayData);
	}
	
	function showExceptionLogs()
	{
		$this->load->model('common/errorexceptionlogmodel');
		// get the objects of the model
		$this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;
		
		$filters               = array();
		$isAjax                = $this->input->post("isajax");
		$filters["fromDate"]   = $this->input->post("fromDate");
		$filters["toDate"]     = $this->input->post("toDate");
		$filters["errorClass"] = $this->input->post("errorClass");
		
		$rs                = $this->ErrorExceptionLogModelObj->getExceptionLogs($filters);
		$displayData['rs'] = $rs;
		
		if(!$isAjax)
			$this->load->view("common/viewExceptionLogListing", $displayData);
		else
			$this->load->view("common/exceptionTableView", $displayData);
	}

	function showBadGatewayLogs($start, $end)
	{
		$this->load->model('common/errorexceptionlogmodel');
		$displayData = array();
		$displayData['badGatewayErrors'] = $this->errorexceptionlogmodel->getBadGatewayErrors($start, $end);
		$this->load->view("common/badGatewayTableView", $displayData);
	}

	function sendBadGatewayAlert()
	{
		$this->validateCron();
		$this->load->model('common/errorexceptionlogmodel');
        // get the objects of the model
        $this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;

        $data = $this->ErrorExceptionLogModelObj->getBadGatewayDataAlerts(300);
        $numErrors = intval($data[0]);
        $numErrors_24 = intval($data[1]);
			
		if($numErrors > 10) {

                $message = "<h3>Last 5 Minutes: <a href='http://www.shiksha.com/common/ErrorExceptionLogger/showBadGatewayLogs'>".$numErrors."</a></h3>";
                $message .= "<h3>Last 24 Hours: <a href='http://www.shiksha.com/common/ErrorExceptionLogger/showBadGatewayLogs'>".$numErrors_24."</a></h3>";

                $email_from = "Production Critical Alerts <ProductionCriticalAlerts@shiksha.com>"; // Who the email is from
                $email_subject = "Bad Gateway Alert: $numErrors errors in last 5 minutes/$numErrors_24 in 24 hours"; // The Subject of the email 
                $email_message = $message;

                $email_to = "ShikshaProdEmergencyTeam@shiksha.com";//"ankur.gupta@shiksha.com,amit.kuksal@shiksha.com,vikas.k@shiksha.com,sukhdeep.kaur@99acres.com,abhinav.k@shiksha.com,aditya.roshan@shiksha.com,romil.goel@shiksha.com,pranjul.raizada@shiksha.com"; // Who the email is to
                //$email_to = "vikas.k@shiksha.com"; // Who the email is to

                $headers = "From: ".$email_from;
                $semi_rand = md5(time());
                $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";
                $headers .= "\nMIME-Version: 1.0\n" .
                            "Content-Type: multipart/mixed;\n" .
                            " boundary=\"{$mime_boundary}\"";

                $email_message .= "This is a multi-part message in MIME format.\n\n" .
"--{$mime_boundary}\n" .
"Content-Type:text/html; charset=\"iso-8859-1\"\n" .
"Content-Transfer-Encoding: 7bit\n\n" .
$email_message .= "\n\n";

                @mail($email_to, $email_subject, $email_message, $headers);

                error_log($numErrors);
		}
	}

	function parseErrorAndExceptionLogs(){
		$this->validateCron();
		ini_set("memory_limit","5120M");
		$scriptStartTime = microtime(true);
		$this->load->library('alerts_client');
		$alertClient = new Alerts_client();

		$dberrorCount   = $this->getServerErrorLogs();
		$exceptionCount = $this->getServerExceptionLogs();

		// truncate the log file
		exec("truncate -s 0 /tmp/exceptionLogs.log");

		$scriptEndTime = microtime(true);
		$timeTaken = ($scriptEndTime - $scriptStartTime);

		$text = " DB Errors : ".$dberrorCount." and Exceptions : ".$exceptionCount." Time Taken = ".$timeTaken." seconds";
		if(($exceptionCount+$dberrorCount) >= 10 || $exceptionCount >= 5)
			$alertClient->externalQueueAdd("12", ADMIN_EMAIL, "romil.goel@shiksha.com", "Error Exception Logs", $text, "html", '', 'n');
	}

	function getServerErrorLogs()
	{
		$output = array();
		$test   = exec("grep ".escapeshellarg(":::DB_ERROR_OCCURED:::")." /tmp/exceptionLogs.log", $output, $ret_val);
		
		$this->load->model('common/errorexceptionlogmodel');
		$this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;

		$rs = $this->ErrorExceptionLogModelObj->getLatestErrorQueryTime();
		
		if(empty($rs[0]['time']))
			$latestQuerytime = 0;
		else
		{
			$latestQuerytime = $rs[0]['time'];
			$latestQuerytime = strtotime($latestQuerytime);
		}			
			
		$finalArr = array();
		foreach($output as $value)
		{
			$tempArr = $this->parseDbErrorNew($value, $latestQuerytime);
			if($tempArr)
				$finalArr[] = $tempArr;
		}

		_p("Parsed DB Errors : ".count($finalArr));
		
		
		
		if(!empty($finalArr))
			$this->ErrorExceptionLogModelObj->logErrorQuery1($finalArr);
		_p("Inserted ".count($finalArr)." DB Errors : ");	

		return count($finalArr);
	}
	
	function parseErrorMsg($errorMsg)
	{
		$seperator = "error_seperator";
		$finalArrDetails = array();
		
		$error = explode(":::error:::A Database Error Occurred---", $errorMsg);
		
		// get the date
		$date = explode("[error]", $error[0]);
		$date = str_replace(array(']','['), "", $date[0]);
		$date = date("Y-m-d H:i:s",strtotime($date));
		$finalArrDetails['date'] = $date;
		$finalArrDetails['timestamp'] = strtotime($date);
		
		$errorDetails = $error[1];
		
		// extract the referer from the error message
		$errorDetails  = explode("referer:", $errorDetails);
		$referer = $errorDetails[1];
		
		$errorDetails = $errorDetails[0];
		$errorDetails = str_replace(array('[0]','[1]','[2]','[3]','[4]','[5]'),$seperator, $errorDetails);
		$errorDetails = str_replace(array('\n','\t')," ", $errorDetails);
		$errorDetails = explode($seperator." =>", $errorDetails);
		$finalArrDetails['referer'] = $referer;
		
		if(strpos($errorDetails[1],"Error Number:"))
		{
			// get the error number
			$err_num = explode("Error Number:", $errorDetails[1]);
			$err_num = trim($err_num[1]);
			$finalArrDetails['error_num'] = $err_num;
			
			// get the error message
			$err_msg = trim($errorDetails[2]);
			$finalArrDetails['error_msg'] = $err_msg;
			
			// error query
			$error_query = $errorDetails[3];
			$finalArrDetails['error_query'] = $error_query;
			
			// Filename
			$filename = explode("Filename:", $errorDetails[4]);
			$filename = trim($filename[1]);
			$finalArrDetails['filename'] = $filename;
			
			//Line Number
			$line_num = explode("Line Number:", $errorDetails[5]);
			$line_num = trim($line_num[1]);
			$finalArrDetails['line_number'] = intVal($line_num);
		}
		else
		{
			// get the error message
			$err_msg = trim($errorDetails[1]);
			$finalArrDetails['error_msg'] = $err_msg;
			
			// Filename
			$filename = explode("Filename:", $errorDetails[2]);
			$filename = trim($filename[1]);
			$finalArrDetails['filename'] = $filename;
			
			//Line Number
			$line_num = explode("Line Number:", $errorDetails[3]);
			$line_num = trim($line_num[1]);
			$finalArrDetails['line_number'] = intVal($line_num);
			

		}
		
		return $finalArrDetails;
	}
	
	function print_r_reverse($in)
	{
		$lines = explode("\\n", trim($in));
		
		if (trim($lines[0]) != 'Array')
		{
			// bottomed out to something that isn't an array
			return $in;
		}
		else
		{
			// this is an array, lets parse it
			if (preg_match("/(\s{5,})\(/", $lines[1], $match)) {
			// this is a tested array/recursive call to this function
			// take a set of spaces off the beginning
			$spaces = $match[1];
			$spaces_length = strlen($spaces);
			$lines_total = count($lines);
			for ($i = 0; $i < $lines_total; $i++) {
				if (substr($lines[$i], 0, $spaces_length) == $spaces) {
				$lines[$i] = substr($lines[$i], $spaces_length);
				}
			}
			}
			array_shift($lines); // Array
			array_shift($lines); // (
			array_pop($lines); // )
			$in = implode("\n", $lines);
			// make sure we only match stuff with 4 preceding spaces (stuff for this array and not a nested one)
			preg_match_all("/^\s{4}\[(.+?)\] \=\> /m", $in, $matches, PREG_OFFSET_CAPTURE | PREG_SET_ORDER);
			$pos = array();
			$previous_key = '';
			$in_length = strlen($in);
			// store the following in $pos:
			// array with key = key of the parsed array's item
			// value = array(start position in $in, $end position in $in)
			foreach ($matches as $match) {
			$key = $match[1][0];
			$start = $match[0][1] + strlen($match[0][0]);
			$pos[$key] = array($start, $in_length);
			if ($previous_key != '') $pos[$previous_key][1] = $match[0][1] - 1;
			$previous_key = $key;
			}
			$ret = array();
			foreach ($pos as $key => $where) {
			// recursively see if the parsed out value is an array too
			$ret[$key] = $this->print_r_reverse(substr($in, $where[0], $where[1] - $where[0]));
			}
			return $ret;
		}

	} 

	function getServerExceptionLogs()
	{
		//$output = array();
		//$test = exec("grep ".escapeshellarg(":::error:::A Database Error Occurred")." /home/romil/Documents/Programs/nodejs/test.log", $output, $ret_val);
		//$test = exec("grep ".escapeshellarg(":::EXCEPTION_OCCURED:::")." /home/romil/Documents/Programs/nodejs/exceptionlog.log", $output, $ret_val);
		//$test = exec("grep ".escapeshellarg("ERROR")." /home/romil/Documents/Programs/nodejs/exceptionlog.log", $output, $ret_val);
		//$test = exec("grep ".escapeshellarg(":::EXCEPTION_IDENTIFIER:::")." /var/log/apache2/shiksha.com-error.log 2>&1", $output, $ret_val);
		//$test = exec("id 2>&1", $output, $ret_val);
		$test = exec("grep ".escapeshellarg(":::EXCEPTION_OCCURED:::")." /tmp/exceptionLogs.log", $output, $ret_val);
		$this->load->model('common/errorexceptionlogmodel');
		$this->ErrorExceptionLogModelObj = new ErrorExceptionLogModel;
		
		$rs = $this->ErrorExceptionLogModelObj->getLatestExceptionTime();
			
		if(empty($rs[0]['time']))
			$latestQuerytime = 0;
		else
		{
			$latestQuerytime = $rs[0]['time'];
			$latestQuerytime = strtotime($latestQuerytime);
		}
		
		$finalArr = array();
		foreach( $output as $value )
		{
			$res = $this->parseException($value, $parsedVal, $latestQuerytime);
			
			if($res)
				$finalArr[] = $parsedVal;
			
		}
	
		_p("Parsed Exceptions : ".count($finalArr));
		
		if(!empty($finalArr))
			$this->ErrorExceptionLogModelObj->logExceptions($finalArr);

		_p("Inserted ".count($finalArr)." Exceptions : ");

		return count($finalArr);
	
	}
	
	function parseException($value, &$parsedVal, $latestQuerytime)
	{
		// _p("<b>For : ".$value."</b>");
		$errorMsg = $value;
	
		$seperator = "error_seperator";
		$finalArrDetails = array();
		//_p($errorMsg);
		$error = explode(":::EXCEPTION_OCCURED:::", $errorMsg);
		
		// get the date
		$date = explode("[error]", $error[0]);
		$date = str_replace(array(']','['), "", $date[0]);
		$date = date("Y-m-d H:i:s",strtotime($date));
		$finalArrDetails['date'] = $date;
		$finalArrDetails['timestamp'] = strtotime($date);
		
		if($latestQuerytime && $latestQuerytime >= $finalArrDetails['timestamp'] )
			return 0;
	
		$error = explode(":CLASS:", $error[1]);
		$error = explode(":CODE:" , $error[1]);
		$finalArrDetails['class'] = $error[0];

		$error = explode(":FILE:" , $error[1]);
		$finalArrDetails['code'] = $error[0];			

		$error = explode(":LINE:" , $error[1]);
		
		
		// 
		$finalArrDetails['file'] = $error[0];
		
		$error = explode(":URL:" , $error[1]);
		$finalArrDetails['line'] = $error[0];

		$error = explode(":EXCP_MSG:" , $error[1]);
		$finalArrDetails['url'] = $error[0];
		$finalArrDetails['exceptionMsg'] = $error[1];

		$parsedVal = $finalArrDetails;
		
		return 1;
	}
	
	function logException()
	{
		error_log("Insde11111");
		$test = $this->input->post("exceptionMsg");
		error_log("Insde");
		error_log(print_r($test,true));
	}

	function parseDbErrorNew($value, $latestQuerytime)
	{
		_p("<b>For : ".$value."</b>");
		$errorMsg = $value;
	
		$seperator = "error_seperator";
		$finalArrDetails = array();

		$error = explode(":::DB_ERROR_OCCURED:::", $errorMsg);
		
		// get the date
		$date = explode("[error]", $error[0]);
		$date = str_replace(array(']','['), "", $date[0]);
		$date = date("Y-m-d H:i:s",strtotime($date));
		$finalArrDetails['date'] = $date;
		$finalArrDetails['timestamp'] = strtotime($date);
		
		if($latestQuerytime && $latestQuerytime >= $finalArrDetails['timestamp'] )
		{
			return 0;
		}
	
		$error = explode(":CODE:" , $error[1]);
		$error = explode(":FILE:" , $error[1]);
		$finalArrDetails['error_num'] = $error[0];
		
		$error = explode(":LINE:" , $error[1]);
		$finalArrDetails['filename'] = $error[0];
		
		$error = explode(":URL:" , $error[1]);
		$finalArrDetails['line_number'] = $error[0];

		$error = explode(":EXCP_MSG:" , $error[1]);
		$finalArrDetails['referer'] = $error[0];

		$error = explode(":EXCP_MSG:" , $error[1]);
		
		$finalArrDetails['error_msg'] = $error[0];

		$error = explode(":SQL:" , $error[0]);

		$finalArrDetails['error_query'] = $error[1];
		
		return $finalArrDetails;
	}
};
