<?php

class LeadConsumptionReport extends MX_Controller
{    
    function __construct()
    {
        ini_set('memory_limit','2048M');
        $this->load->model('searchAgents/search_agent_main_model');
    }
	
	private function _getReportGenerator($type = NULL)
	{
		if(!$type || $type == 'main') {
			$this->load->library('searchAgents/LeadConsumptionReportGenerator/LeadConsumptionReportGeneratorMain');
			$reportGenerator = new LeadConsumptionReportGeneratorMain(new Search_agent_main_model);
		}
		
		if($type == 'abroad') {
			$this->load->library('searchAgents/LeadConsumptionReportGenerator/LeadConsumptionReportGeneratorAbroad');
			$reportGenerator = new LeadConsumptionReportGeneratorAbroad(new Search_agent_main_model);
		}
		
		return $reportGenerator;
	}

    private function _canCronRun()
    {
        /**
         * Cron will run every 15 days from 23 Jan 2015
         * Check if today is the 15th day
         */
        $startDate = '2015-01-23';
        $currentDate = date("Y-m-d");
        $daydiff = (strtotime($currentDate) - strtotime($startDate))/86400;
        if($daydiff % 15 == 0) {
            return TRUE;
        }
        else {
            return FALSE;
        }
    }
    
    function sendCSVReportEmail($type='main', $deliveryType = 'normal')
    {
		if(!$this->_canCronRun()) {
                /**
                 * Today is not the day to run this cron
                 */
                return FALSE;
        }

		$reportGenerator = $this->_getReportGenerator($type);

        if($reportGenerator){
            $reportGenerator->setDeliveryType($deliveryType);
        }
        else {
            return FALSE;
        }
        $csvReport = $reportGenerator->generateCSVReport();
		
        $reportDateFrom = date('Y-m-d',strtotime('-15 day'));
        $reportDateTo = date('Y-m-d',strtotime('-1 day'));
        
        if($deliveryType == 'porting') {
            $subject = 'Lead Porting Consumption Report, '.$reportDateFrom.' to '.$reportDateTo;
        }
        else {
            $subject = 'Lead Genie Consumption Report'.($type == 'abroad' ? 'For Abroad' : '').', '.$reportDateFrom.' to '.$reportDateTo;
        }
     
        $mime = 'application/xls';
        $fileName = 'LeadConsumptionReport.csv';
        if($type == 'abroad') {
            $fileName = 'LeadConsumptionReportAbroad.csv';
        }
        
        $emailMessage = 'Hi, <br><br> Please find attached '.strtolower($subject).'.<br><br> Regards,<br>Shiksha Team<br><br><br><br><br><br>';
        
	$from = 'Shiksha.com <noreply@shiksha.com>';
        $to = 'saurabh.gupta@shiksha.com,siddhartha.k@shiksha.com,jain.vivek@naukri.com,nishant.pandey@naukri.com,ambrish@shiksha.com,abhinav.k@shiksha.com';
        $cc = 'vikas.k@shiksha.com,gupta.himanshu@shiksha.com,karundeep.gill@shiksha.com,amit.kuksal@shiksha.com,azhar.ali@shiksha.com';       
 
        $headers = "From: ".$from."\r\n"."Cc: ".$cc;
        $semi_rand = md5(time());
        $mime_boundary = "==Multipart_Boundary_x{$semi_rand}x";

        $headers .= "\nMIME-Version: 1.0\n" .
        "Content-Type: multipart/mixed;\n" .
        " boundary=\"{$mime_boundary}\"";

        $emailMessage .= "This is a multi-part message in MIME format.\n\n" .
        "--{$mime_boundary}\n" .
        "Content-Type:text/html; charset=\"iso-8859-1\"\n" .
        "Content-Transfer-Encoding: 7bit\n\n" .
        $emailMessage .= "\n\n";

        $data = chunk_split(base64_encode($csvReport));

        $emailMessage .= "--{$mime_boundary}\n" .
        "Content-Type: {$mime};\n" .
        " name=\"{$fileName}\"\n" .
        "Content-Transfer-Encoding: base64\n\n" .
        $data .= "\n\n" .
        "--{$mime_boundary}--\n";
        
        mail($to, $subject, $emailMessage, $headers);
    }
    
    /**
     * Download report in CSV format
     */ 
    function downloadCSV($startDate,$endDate, $type='main', $deliveryType = 'normal')
    {
        // exit;
        ini_set('max_execution_time','600');
	$reportGenerator = $this->_getReportGenerator($type);
		
        if($reportGenerator){
            $reportGenerator->setDeliveryType($deliveryType);
            $reportGenerator->setDateRange($startDate,$endDate);
        }
        else {
            return FALSE;
        }

        $csvReport = $reportGenerator->generateCSVReport();

        $mime = 'text/x-csv';
        $fileName = 'LeadConsumptionReport.csv';
        
        if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header('Expires: 0');
            header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
            header("Content-Transfer-Encoding: binary");
            header('Pragma: public');
            header("Content-Length: " . strlen($csvReport));
        } else {
            header('Content-Type: "' . $mime . '"');
            header('Content-Disposition: attachment; filename="' . $fileName . '"');
            header("Content-Transfer-Encoding: binary");
            header('Expires: 0');
            header('Pragma: no-cache');
            header("Content-Length: " . strlen($csvReport));
        }
        
        echo ($csvReport);
    }
    
    /**
     * Display report in table format on screen
     */ 
    function table($startDate, $endDate, $type='main', $deliveryType = 'normal')
    {
		$reportGenerator = $this->_getReportGenerator($type);
		
        if($reportGenerator){
            $reportGenerator->setDeliveryType($deliveryType);
            $reportGenerator->setDateRange($startDate,$endDate);
        }
        else {
            return FALSE;
        }

        $headings = $reportGenerator->getFields();

        echo "<table border='1'>";
        echo "<tr>";
        foreach($headings as $heading) {
            echo "<th>".$heading."</th>";
        }
        echo "</tr>";
        
        $data = $reportGenerator->getLeadConsumptionData();
        foreach($data as $row) {
            echo "<tr>";
            foreach($row as $value) {
                echo "<td>".$value."</td>";
            }
            echo "</tr>";
        }
        
        echo "</table>";
    }
}
