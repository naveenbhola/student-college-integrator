<?php

class LeadDashboard extends MX_Controller {
        
        /**
         Function for Lead Dashboard Data : Leads by LDB courses for different time spans
         */
            
        function getLeadDashboardData()
        {

        //mail added for code scan safe check    
        mail('teamldb@shiksha.com', 'function -getLeadDashboardData', 'Cron to get lead data');
        return;
        
	    ini_set('memory_limit','128M');
            
	    $this->load->library('Zip');
	    $this->zip = new CI_Zip();
            
            $attachment_path = $this->_generateZip();
            $from = 'info@shiksha.com';
            $to = 'saurabh.gupta@shiksha.com';
            $cc = 'aditya.roshan@shiksha.com';
	    
            $subject = 'Lead Dashboard Data | '.date('M d, Y');
            $content = '<pre><span style="font-size: small;">Hi,</span><br /><br /><span style="font-size: small;">Please find attached the Lead Dashboard Data of the leads generated on Shiksha.com for current and last year.</span><br /><br /><span style="font-size: small;">Regards,</span><br /><span style="font-size: small;">Shiksha.com</span><br /><br /><span style="font-size: x-small;">This email has been sent to you because you have requested Leads Data on Shiksha.com.</span></pre>';
	    
	    $fileatt_name = 'LeadDashboardData_'.time().'.zip';
            $this->_sendEmailWithAttachment($from,$to,$cc,$subject,$content,$attachment_path,$fileatt_name);
	    
        }
        
        function _generateZip() {
            
            $this->getDataForCurrentYear();
            $this->getDataForLastYear();
            
            $return_path = '/tmp/LeadDashboardData_'.time().'.zip';
            $this->zip->archive($return_path);
            return $return_path;
	    
        }
	
        function _sendEmailWithAttachment($from,$to,$cc,$subject,$message,$attachment_path,$fileatt_name,$hasAttachment='yes') {
	    
            $email_message = $message;
            $headers = "From: ".$from."\r\n"."Cc: ".$cc;
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
	    
	    if($hasAttachment == 'yes') {
		$fileatt_type = "application/zip, application/octet-stream";
		//$fileatt_name = 'LeadDashboardData_'.time().'.zip';
		
		$data = chunk_split(base64_encode(file_get_contents($attachment_path)));
		$email_message .= "--{$mime_boundary}\n" .
		"Content-Type: {$fileatt_type};\n" .
		" name=\"{$fileatt_name}\"\n" .
		"Content-Transfer-Encoding: base64\n\n" .
		$data .= "\n\n" .
		"--{$mime_boundary}--\n";
	    }
	    
            $ok = mail($to,$subject, $email_message, $headers);
            error_log('mailsending '.$ok);
	    
        }
        
        function getDataForCurrentYear()
        {
            $csvForLeadsCreated = $this->getLeadsCreatedForCurrentYear();
            $csvForLeadsConsumed = $this->getLeadsConsumedForCurrentYear();
            if(!empty($csvForLeadsCreated)) {
                $fileName = '/CurrentYearData/LeadsCreatedDataCurrentYear_'.time().'.xls';       
                $this->zip->add_data($fileName, $csvForLeadsCreated);
            }
            if(!empty($csvForLeadsConsumed)) {
                $fileName = '/CurrentYearData/LeadsConsumedDataCurrentYear_'.time().'.xls';       
                $this->zip->add_data($fileName, $csvForLeadsConsumed);
            }
        }
        
        function getDataForLastYear()
        {
            $csvForLeadsCreated = $this->getLeadsCreatedForLastYear();
            $csvForLeadsConsumed = $this->getLeadsConsumedForLastYear();
            if(!empty($csvForLeadsCreated)) {
                $fileName = '/LastYearData/LeadsCreatedDataLastYear_'.time().'.xls';       
                $this->zip->add_data($fileName, $csvForLeadsCreated);
            }
            if(!empty($csvForLeadsConsumed)) {
                $fileName = '/LastYearData/LeadsConsumedDataLastYear_'.time().'.xls';       
                $this->zip->add_data($fileName, $csvForLeadsConsumed);
            }
        }
        
        function getLeadsCreatedForCurrentYear()
        {
            $this->load->model('leaddashboardmodel');
            $dataForLastMonth = $this->leaddashboardmodel->getLeadsCreatedByLDBCourses(date('Y-m-d H:i:s',strtotime('-1 month 00:00:00')), date('Y-m-d H:i:s',strtotime('23:59:59')));
            $dataForYearTillDate = $this->leaddashboardmodel->getLeadsCreatedByLDBCourses(date('Y-m-d H:i:s',strtotime('first day of January this year 00:00:00')), date('Y-m-d H:i:s',strtotime('23:59:59')));
            
            $allLeadsCreatedLastMonth = $dataForLastMonth['AllLeads'];
            $testPrepLeadsCreatedLastMonth = $dataForLastMonth['TestPrepLeads'];
            $testPrepCoursesLastMonth = $dataForLastMonth['TestPrepCourses'];
            $studyAbroadCoursesLastMonth = $dataForLastMonth['StudyAbroadCourses'];
            
            $allLeadsCreatedYearTillDate = $dataForYearTillDate['AllLeads'];
            $testPrepLeadsCreatedYearTillDate = $dataForYearTillDate['TestPrepLeads'];
            $testPrepCoursesYearTillDate = $dataForYearTillDate['TestPrepCourses'];
            $studyAbroadCoursesYearTillDate = $dataForYearTillDate['StudyAbroadCourses'];
            
            $LeadsCreatedArray = array();
            foreach($allLeadsCreatedLastMonth as $leadCreated){
                if($leadCreated['ExtraFlag'] == 'studyabroad' && $leadCreated['CategoryId'] !== 1 && ($leadCreated['CourseName'] == 'Bachelors' || $leadCreated['CourseName'] == 'Masters' || $leadCreated['CourseName'] == 'PhD')){
                    if(array_key_exists($leadCreated['CategoryId'],$studyAbroadCoursesLastMonth)){
                        $course = $studyAbroadCoursesLastMonth[$leadCreated['CategoryId']]['CategoryName']."-".$leadCreated['CourseName'];
                    }
                }
                else{
                    $course = $leadCreated['CourseName'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Last one month 2014']['countInitial'] += $leadCreated['UserCount'];
            }
            foreach($testPrepLeadsCreatedLastMonth as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesLastMonth)){
                        $course = $testPrepCoursesLastMonth[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Last one month 2014']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($allLeadsCreatedYearTillDate as $leadCreated){
                if($leadCreated['ExtraFlag'] == 'studyabroad' && $leadCreated['CategoryId'] !== 1 && ($leadCreated['CourseName'] == 'Bachelors' || $leadCreated['CourseName'] == 'Masters' || $leadCreated['CourseName'] == 'PhD')){
                    if(array_key_exists($leadCreated['CategoryId'],$studyAbroadCoursesYearTillDate)){
                        $course = $studyAbroadCoursesYearTillDate[$leadCreated['CategoryId']]['CategoryName']."-".$leadCreated['CourseName'];
                    }
                }
                else{
                    $course = $leadCreated['CourseName'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Year till date 2014']['countInitial'] += $leadCreated['UserCount'];
            }
            foreach($testPrepLeadsCreatedYearTillDate as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesYearTillDate)){
                        $course = $testPrepCoursesYearTillDate[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Year till date 2014']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($LeadsCreatedArray as $courseName => $data){
                $LeadsCreatedArray[$courseName]['Last one month 2014']['data'] = ($LeadsCreatedArray[$courseName]['Last one month 2014']['countInitial'] + count($LeadsCreatedArray[$courseName]['Last one month 2014']['usersInWindow']))?($LeadsCreatedArray[$courseName]['Last one month 2014']['countInitial'] + count($LeadsCreatedArray[$courseName]['Last one month 2014']['usersInWindow'])):0;
                $LeadsCreatedArray[$courseName]['Year till date 2014']['data'] = ($LeadsCreatedArray[$courseName]['Year till date 2014']['countInitial'] + count($LeadsCreatedArray[$courseName]['Year till date 2014']['usersInWindow']))?($LeadsCreatedArray[$courseName]['Year till date 2014']['countInitial'] + count($LeadsCreatedArray[$courseName]['Year till date 2014']['usersInWindow'])):0;
            }
            ksort($LeadsCreatedArray);
            
            $ColumnListArray = array();
            $ColumnListArray = array('Leads by LDB Course','Last one month 2014','Year till date 2014');
            $csv = '';
            foreach ($ColumnListArray as $ColumnName) {
                $csv .= '"' . $ColumnName . '",';
            }
            $csv .= "\n";
            foreach ($LeadsCreatedArray as $lead) {
                foreach ($ColumnListArray as $ColumnName) {
                    $csv .= '"' . $lead[$ColumnName]['data'] . '",';
                }
                $csv .= "\n";
            }
            return $csv;
        }
        
        function getLeadsConsumedForCurrentYear()
        {
            $this->load->model('leaddashboardmodel');
            $dataForLastMonth = $this->leaddashboardmodel->getLeadsConsumedByLDBCourses(date('Y-m-d H:i:s',strtotime('-1 month 00:00:00')), date('Y-m-d H:i:s',strtotime('23:59:59')));
            $dataForYearTillDate = $this->leaddashboardmodel->getLeadsConsumedByLDBCourses(date('Y-m-d H:i:s',strtotime('first day of January this year 00:00:00')), date('Y-m-d H:i:s',strtotime('23:59:59')));
            
            $allLeadsConsumedLastMonth = $dataForLastMonth['AllLeads'];
            $testPrepLeadsConsumedLastMonth = $dataForLastMonth['TestPrepLeads'];
            $testPrepCoursesLastMonth = $dataForLastMonth['TestPrepCourses'];
            $studyAbroadCoursesLastMonth = $dataForLastMonth['StudyAbroadCourses'];
            
            $allLeadsConsumedYearTillDate = $dataForYearTillDate['AllLeads'];
            $testPrepLeadsConsumedYearTillDate = $dataForYearTillDate['TestPrepLeads'];
            $testPrepCoursesYearTillDate = $dataForYearTillDate['TestPrepCourses'];
            $studyAbroadCoursesYearTillDate = $dataForYearTillDate['StudyAbroadCourses'];
            
            $LeadsConsumedArray = array();
            foreach($allLeadsConsumedLastMonth as $leadConsumed){
                if($leadConsumed['ExtraFlag'] == 'studyabroad' && $leadConsumed['CategoryId'] !== 1 && ($leadConsumed['CourseName'] == 'Bachelors' || $leadConsumed['CourseName'] == 'Masters' || $leadConsumed['CourseName'] == 'PhD')){
                    if(array_key_exists($leadConsumed['CategoryId'],$studyAbroadCoursesLastMonth)){
                        $course = $studyAbroadCoursesLastMonth[$leadConsumed['CategoryId']]['CategoryName']."-".$leadConsumed['CourseName'];
                    }
                }
                else{
                    $course = $leadConsumed['CourseName'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Last one month 2014']['countInitial'] += $leadConsumed['UserCount'];
            }
            foreach($testPrepLeadsConsumedLastMonth as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesYearTillDate)){
                        $course = $testPrepCoursesYearTillDate[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Last one month 2014']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($allLeadsConsumedYearTillDate as $leadConsumed){
                if($leadConsumed['ExtraFlag'] == 'studyabroad' && $leadConsumed['CategoryId'] !== 1 && ($leadConsumed['CourseName'] == 'Bachelors' || $leadConsumed['CourseName'] == 'Masters' || $leadConsumed['CourseName'] == 'PhD')){
                    if(array_key_exists($leadConsumed['CategoryId'],$studyAbroadCoursesYearTillDate)){
                        $course = $studyAbroadCoursesYearTillDate[$leadConsumed['CategoryId']]['CategoryName']."-".$leadConsumed['CourseName'];
                    }
                }
                else{
                    $course = $leadConsumed['CourseName'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Year till date 2014']['countInitial'] += $leadConsumed['UserCount'];
            }
            foreach($testPrepLeadsConsumedYearTillDate as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesYearTillDate)){
                        $course = $testPrepCoursesYearTillDate[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Year till date 2014']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($LeadsConsumedArray as $courseName => $data){
                $LeadsConsumedArray[$courseName]['Last one month 2014']['data'] = ($LeadsConsumedArray[$courseName]['Last one month 2014']['countInitial'] + count($LeadsConsumedArray[$courseName]['Last one month 2014']['usersInWindow']))?($LeadsConsumedArray[$courseName]['Last one month 2014']['countInitial'] + count($LeadsConsumedArray[$courseName]['Last one month 2014']['usersInWindow'])):0;
                $LeadsConsumedArray[$courseName]['Year till date 2014']['data'] = ($LeadsConsumedArray[$courseName]['Year till date 2014']['countInitial'] + count($LeadsConsumedArray[$courseName]['Year till date 2014']['usersInWindow']))?($LeadsConsumedArray[$courseName]['Year till date 2014']['countInitial'] + count($LeadsConsumedArray[$courseName]['Year till date 2014']['usersInWindow'])):0;
            }
            ksort($LeadsConsumedArray);
            
            $ColumnListArray = array();
            $ColumnListArray = array('Leads by LDB Course','Last one month 2014','Year till date 2014');
            $csv = '';
            foreach ($ColumnListArray as $ColumnName) {
                $csv .= '"' . $ColumnName . '",';
            }
            $csv .= "\n";
            foreach ($LeadsConsumedArray as $lead) {
                foreach ($ColumnListArray as $ColumnName) {
                    $csv .= '"' . $lead[$ColumnName]['data'] . '",';
                }
                $csv .= "\n";
            }
            return $csv;
        }
        
        function getLeadsCreatedForLastYear()
        {
            $this->load->model('leaddashboardmodel');
            $dataForLastMonth = $this->leaddashboardmodel->getLeadsCreatedByLDBCourses(date('Y-m-d H:i:s',strtotime('-1 month -1 year 00:00:00')), date('Y-m-d H:i:s',strtotime('-1 year 23:59:59')));
            $dataForYearTillDate = $this->leaddashboardmodel->getLeadsCreatedByLDBCourses(date('Y-m-d H:i:s',strtotime('first day of January previous year 00:00:00')), date('Y-m-d H:i:s',strtotime('-1 year 23:59:59')));
            
            $allLeadsCreatedLastMonth = $dataForLastMonth['AllLeads'];
            $testPrepLeadsCreatedLastMonth = $dataForLastMonth['TestPrepLeads'];
            $testPrepCoursesLastMonth = $dataForLastMonth['TestPrepCourses'];
            $studyAbroadCoursesLastMonth = $dataForLastMonth['StudyAbroadCourses'];
            
            $allLeadsCreatedYearTillDate = $dataForYearTillDate['AllLeads'];
            $testPrepLeadsCreatedYearTillDate = $dataForYearTillDate['TestPrepLeads'];
            $testPrepCoursesYearTillDate = $dataForYearTillDate['TestPrepCourses'];
            $studyAbroadCoursesYearTillDate = $dataForYearTillDate['StudyAbroadCourses'];
            
            $LeadsCreatedArray = array();
            foreach($allLeadsCreatedLastMonth as $leadCreated){
                if($leadCreated['ExtraFlag'] == 'studyabroad' && $leadCreated['CategoryId'] !== 1 && ($leadCreated['CourseName'] == 'Bachelors' || $leadCreated['CourseName'] == 'Masters' || $leadCreated['CourseName'] == 'PhD')){
                    if(array_key_exists($leadCreated['CategoryId'],$studyAbroadCoursesLastMonth)){
                        $course = $studyAbroadCoursesLastMonth[$leadCreated['CategoryId']]['CategoryName']."-".$leadCreated['CourseName'];
                    }
                }
                else{
                    $course = $leadCreated['CourseName'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Corresponding month 2013']['countInitial'] += $leadCreated['UserCount'];
            }
            foreach($testPrepLeadsCreatedLastMonth as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesLastMonth)){
                        $course = $testPrepCoursesLastMonth[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Corresponding month 2013']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($allLeadsCreatedYearTillDate as $leadCreated){
                if($leadCreated['ExtraFlag'] == 'studyabroad' && $leadCreated['CategoryId'] !== 1 && ($leadCreated['CourseName'] == 'Bachelors' || $leadCreated['CourseName'] == 'Masters' || $leadCreated['CourseName'] == 'PhD')){
                    if(array_key_exists($leadCreated['CategoryId'],$studyAbroadCoursesYearTillDate)){
                        $course = $studyAbroadCoursesYearTillDate[$leadCreated['CategoryId']]['CategoryName']."-".$leadCreated['CourseName'];
                    }
                }
                else{
                    $course = $leadCreated['CourseName'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Year till date 2013']['countInitial'] += $leadCreated['UserCount'];
            }
            foreach($testPrepLeadsCreatedYearTillDate as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesYearTillDate)){
                        $course = $testPrepCoursesYearTillDate[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsCreatedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsCreatedArray[$course]['Year till date 2013']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($LeadsCreatedArray as $courseName => $data){
                $LeadsCreatedArray[$courseName]['Corresponding month 2013']['data'] = ($LeadsCreatedArray[$courseName]['Corresponding month 2013']['countInitial'] + count($LeadsCreatedArray[$courseName]['Corresponding month 2013']['usersInWindow']))?($LeadsCreatedArray[$courseName]['Corresponding month 2013']['countInitial'] + count($LeadsCreatedArray[$courseName]['Corresponding month 2013']['usersInWindow'])):0;
                $LeadsCreatedArray[$courseName]['Year till date 2013']['data'] = ($LeadsCreatedArray[$courseName]['Year till date 2013']['countInitial'] + count($LeadsCreatedArray[$courseName]['Year till date 2013']['usersInWindow']))?($LeadsCreatedArray[$courseName]['Year till date 2013']['countInitial'] + count($LeadsCreatedArray[$courseName]['Year till date 2013']['usersInWindow'])):0;
            }
            ksort($LeadsCreatedArray);
            
            $ColumnListArray = array();
            $ColumnListArray = array('Leads by LDB Course','Corresponding month 2013','Year till date 2013');
            $csv = '';
            foreach ($ColumnListArray as $ColumnName) {
                $csv .= '"' . $ColumnName . '",';
            }
            $csv .= "\n";
            foreach ($LeadsCreatedArray as $lead) {
                foreach ($ColumnListArray as $ColumnName) {
                    $csv .= '"' . $lead[$ColumnName]['data'] . '",';
                }
                $csv .= "\n";
            }
            return $csv;
        }
        
        function getLeadsConsumedForLastYear()
        {
            $this->load->model('leaddashboardmodel');
            $dataForLastMonth = $this->leaddashboardmodel->getLeadsConsumedByLDBCourses(date('Y-m-d H:i:s',strtotime('-1 month -1 year 00:00:00')), date('Y-m-d H:i:s',strtotime('-1 year 23:59:59')));
            $dataForYearTillDate = $this->leaddashboardmodel->getLeadsConsumedByLDBCourses(date('Y-m-d H:i:s',strtotime('first day of January previous year 00:00:00')), date('Y-m-d H:i:s',strtotime('-1 year 23:59:59')));
            
            $allLeadsConsumedLastMonth = $dataForLastMonth['AllLeads'];
            $testPrepLeadsConsumedLastMonth = $dataForLastMonth['TestPrepLeads'];
            $testPrepCoursesLastMonth = $dataForLastMonth['TestPrepCourses'];
            $studyAbroadCoursesLastMonth = $dataForLastMonth['StudyAbroadCourses'];
            
            $allLeadsConsumedYearTillDate = $dataForYearTillDate['AllLeads'];
            $testPrepLeadsConsumedYearTillDate = $dataForYearTillDate['TestPrepLeads'];
            $testPrepCoursesYearTillDate = $dataForYearTillDate['TestPrepCourses'];
            $studyAbroadCoursesYearTillDate = $dataForYearTillDate['StudyAbroadCourses'];
            
            $LeadsConsumedArray = array();
            foreach($allLeadsConsumedLastMonth as $leadConsumed){
                if($leadConsumed['ExtraFlag'] == 'studyabroad' && $leadConsumed['CategoryId'] !== 1 && ($leadConsumed['CourseName'] == 'Bachelors' || $leadConsumed['CourseName'] == 'Masters' || $leadConsumed['CourseName'] == 'PhD')){
                    if(array_key_exists($leadConsumed['CategoryId'],$studyAbroadCoursesLastMonth)){
                        $course = $studyAbroadCoursesLastMonth[$leadConsumed['CategoryId']]['CategoryName']."-".$leadConsumed['CourseName'];
                    }
                }
                else{
                    $course = $leadConsumed['CourseName'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Corresponding month 2013']['countInitial'] += $leadConsumed['UserCount'];
            }
            foreach($testPrepLeadsConsumedLastMonth as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesLastMonth)){
                        $course = $testPrepCoursesLastMonth[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Corresponding month 2013']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($allLeadsConsumedYearTillDate as $leadConsumed){
                if($leadConsumed['ExtraFlag'] == 'studyabroad' && $leadConsumed['CategoryId'] !== 1 && ($leadConsumed['CourseName'] == 'Bachelors' || $leadConsumed['CourseName'] == 'Masters' || $leadConsumed['CourseName'] == 'PhD')){
                    if(array_key_exists($leadConsumed['CategoryId'],$studyAbroadCoursesYearTillDate)){
                        $course = $studyAbroadCoursesYearTillDate[$leadConsumed['CategoryId']]['CategoryName']."-".$leadConsumed['CourseName'];
                    }
                }
                else{
                    $course = $leadConsumed['CourseName'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Year till date 2013']['countInitial'] += $leadConsumed['UserCount'];
            }
            foreach($testPrepLeadsConsumedYearTillDate as $testprep){
                if(array_key_exists($testprep['PrefId'],$testPrepCoursesYearTillDate)){
                        $course = $testPrepCoursesYearTillDate[$testprep['PrefId']]['BlogTitle'];
                }
                $LeadsConsumedArray[$course]['Leads by LDB Course']['data'] = $course;
                $LeadsConsumedArray[$course]['Year till date 2013']['usersInWindow'][$testprep['UserId']] = TRUE;
            }
            
            foreach($LeadsConsumedArray as $courseName => $data){
                $LeadsConsumedArray[$courseName]['Corresponding month 2013']['data'] = $LeadsConsumedArray[$courseName]['Corresponding month 2013']['countInitial'] + count($LeadsConsumedArray[$courseName]['Corresponding month 2013']['usersInWindow']);
                $LeadsConsumedArray[$courseName]['Year till date 2013']['data'] = $LeadsConsumedArray[$courseName]['Year till date 2013']['countInitial'] + count($LeadsConsumedArray[$courseName]['Year till date 2013']['usersInWindow']);
            }
            ksort($LeadsConsumedArray);
            
            $ColumnListArray = array();
            $ColumnListArray = array('Leads by LDB Course','Corresponding month 2013','Year till date 2013');
            $csv = '';
            foreach ($ColumnListArray as $ColumnName) {
                $csv .= '"' . $ColumnName . '",';
            }
            $csv .= "\n";
            foreach ($LeadsConsumedArray as $lead) {
                foreach ($ColumnListArray as $ColumnName) {
                    $csv .= '"' . $lead[$ColumnName]['data'] . '",';
                }
                $csv .= "\n";
            }
            return $csv;
        }
        
        function WLCLeadPorting(){
            
            $this->load->model('listing/institutemodel');
            $this->load->model('listing/listingmodel');
             
            $configFile = APPPATH.'modules/Enterprise/enterprise/libraries/MatchedResponsesSearchConfig.php';
            require $configFile;
            
            $mailerLeadsServer = '192.168.2.178:3307';
            $mailerLeadsDb = 'asknaukri';
            $mailerLeadsPasswd = 't@ust*m5n0t';
            $mailerLeadsUser = 'lmsuser';
            
            $widgetLeadsServer = '192.168.2.178:3308';
            $widgetLeadsDb = 'WidgetsAdmin';
            $widgetLeadsPasswd = 'n0tw1dg3ts2ws';
            $widgetLeadsUser = 'lmswidgets';
            
            $SCRMServer = 'localhost';
            $SCRMdb = 'sugarcrm';
            $SCRMuser = 'root';
            $SCRMPasswd = 'Km7Iv80l';
            
            $shikshaLeadsServer = 'localhost';
            $shikshaLeadsDb = 'shiksha';
            $shikshaLeadsPasswd = 'Km7Iv80l';
            $shikshaLeadsUser = 'root';
            
            $clientId = 365701;
            $maxDate = date('Y-m-d H:m:s',strtotime('-1 hour'));
            $timeOffset = date('Y-m-d H:m:s');
            
            set_time_limit(0);
            $conn2 = mysql_connect($SCRMServer, $SCRMuser, $SCRMPasswd) or die("could not connect 1");
            mysql_select_db($SCRMdb,$conn2);
            $rs = mysql_query("select max_id,max_date from `data_fetch` where source ='shikshawlc_leads'",$conn2 );
            $row = mysql_fetch_row($rs);
            $maxFetchedId = $row[0];
            //$maxDate = $row[1];
            
            $conn1 = mysql_connect($shikshaLeadsServer,$shikshaLeadsUser,$shikshaLeadsPasswd) or die('could not connect to shiksha server');
            mysql_select_db($shikshaLeadsDb,$conn1 );
            
            //$rs = mysql_query("select userid from  `tuser` where usercreationDate < '".$timeOffset."' order by userid desc limit 1",$conn1);
            $rs = mysql_query("select max(id) from  `tempLMSTable`",$conn1);
            $row = mysql_fetch_row($rs);
            $maxIdToFetch = $row[0];
            $rs = mysql_query("select now() as currentTime",$conn1);
            $row = mysql_fetch_row($rs);
            $currentTime = $row[0];
            
            $returnData = array();
            $courseIds = array();
            $listingIds = $this->listingmodel->getActiveLisitingsForagroupOfOwner($clientId);
            
            $instituteList = array();
			foreach ($listingIds as $key => $listing) {
				if($listing['listing_type'] == 'institute') {
						$instituteList[$listing['listing_type_id']] = $listing;
				}
			}
			
			$instituteCourseMap = $this->institutemodel->getCoursesForInstitutes(array_keys($instituteList),'ALL');
			
			foreach ($instituteCourseMap as $instituteId => $instituteCourseData) {
				foreach ($instituteCourseData['course_title_list'] as $courseId => $courseTitle) {
					$mappedLDBCourseIds = $this->listingmodel->getLDBCoursesForClientCourse($courseId);
					if(!empty($mappedLDBCourseIds)) {
						foreach($coursesList as $name => $details){
							$count = $this->listingmodel->getLDBCoursesCountForSubCategory($mappedLDBCourseIds, $details['subcategory_id'], $details['actual_course_id']);
							if($count > 0 || in_array($details['actual_course_id'],$mappedLDBCourseIds)) {
								$returnData['instituteList'][$instituteId]['instituteData'] = $instituteList[$instituteId];
								$returnData['instituteList'][$instituteId]['courseList'][] = array('id' => $courseId, 'name' => $courseTitle);
								$courseIds[] = $courseId;
								$courseNames[$courseId] = $courseTitle;
								$instituteNames[$courseId] = $instituteList[$instituteId];
							}
						}
					} else {
							continue;
					}
			    }
	        }
            
          $responseData = modules::run('lms/lmsServer/getMatchedResponses', $courseIds, array(), $maxDate, $timeOffset, FALSE);
	      $responseUsers = $responseData['users'];
	      $matchedCourses = $responseData['courses'];
            
          if(count($responseUsers)) {
			$this->load->model('ldbmodel');               
            $searchResult = $this->ldbmodel->searchLeadsMR($inputArray, $clientId, array_keys($responseUsers));
			$userIds = $searchResult['userIds'];
			$totalRows = $searchResult['totalRows'];
                
            if (count($userIds) == 0) {
				$responseArray = array('error' => 'No Results Found For Your Query');
			} else {			
				$finalUsers = array();		    
				foreach($userIds as $index => $userId) {
					unset($userIds[$index]);
					$userIds[$responseUsers[$userId]['responseId']] = $userId;
				}
				
				krsort($userIds);
				$userIds = array_values($userIds);				
				foreach($userIds as $id){
					//$finalUsers[] = $id;
					//if($maxFetchedId < $maxIdToFetch) {
						$finalUsers[] = $id;
					//} else {
					//	continue;
					//}
				}
						
				$resultSet = modules::run('LDB/LDB_Server/createResultSet', $finalUsers, $this->userStatus[0]['userid']);				
				$responseArray = array(
							'numrows' => $totalRows,
							'result' => $resultSet
				);
						$data['resultResponse'] = $responseArray;
            }
            }
            
            foreach($resultSet as $userResult){
                $responseDetails = $responseUsers[$userResult['userid']]['matchedFor'];
				foreach($responseDetails as $courseId) {
					$matchedCoursesTitle[] = $courseNames[$courseId];
				}
                $name = urlencode($userResult['firstname'] . " " . $userResult['lastname']);
                $email = urlencode($userResult['email']);
                $phone = urlencode($userResult['mobile']);
                $courseTitle = urlencode(implode(',',array_values($matchedCoursesTitle)));
                $city = urlencode($userResult['CurrentCity']);
                $source = "shiksha_matched_response";
                $url = "http://ais.wlci.in/SikshaDataDisplay.aspx?displayName=".$name."&email=".$email."&contact_cell=".$phone."&listing_title=".$courseTitle."&institute_name=&listing_type=&listing_type_id=12C&query=&url=".$source."&city=".$city."&action=";
                $ch = curl_init($url);
                curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
                curl_setopt($ch, CURLOPT_MAXREDIRS, 10);
                $response = curl_exec($ch);
                echo "+++++++++";echo $response ;echo "!!!!!!!!";
                curl_close($ch);
                error_log($url."\n\n",3,'/tmp/wlc_log'.date('Y-m-d')); 
                sleep(1);	
               // error_log($userResult['email']."\n\n",3,'/tmp/wlc_log'.date('Y-m-d'));
            }
            
            $conn2 = mysql_connect($SCRMServer, $SCRMuser, $SCRMPasswd) or die("could not connect");
            mysql_select_db($SCRMdb,$conn2);
            //mysql_query("update `data_fetch` set max_id  = $maxIdToFetch, max_date='$timeOffset' where source ='shikshawlc_leads'",$conn2 );
        }
	
    /*
     *	Function for Test Prep Lead Genie Credit Deduction
     */
	public function getClientLeads() {
	    
	    $this->load->model('leaddashboardmodel');
            $clientId_leadsList = $this->leaddashboardmodel->getClientLeadDetails();
	    $clientId_leads_SubscriptionsList = $this->leaddashboardmodel->getClientLeadSubscriptionDetails($clientId_leadsList);

	    $clientId_credits_List=$this->getSubscriptionDetails($clientId_leads_SubscriptionsList);
	     $this->createCSV($clientId_credits_List);
	}
	
	/*
	*Function to get data according to no. of credits
	*Param : array of client data
	*Return : Array with Data
	*/
	public function getSubscriptionDetails($all_client_data = array()){

	    $final_leads_client_data = array();
	    
	    foreach($all_client_data as $k=>$v){
		if($v['leads'] > 0){
		    $final_leads_client_data[$k]['clientid'] = $v['client_id'];
		    //$final_leads_client_data[$k]['orderID'] = $v['orderID'];
		    $final_leads_client_data[$k]['client-name'] = $v['client-name'];
		    $final_leads_client_data[$k]['email'] = $v['email'];
		    $final_leads_client_data[$k]['mobile'] = $v['mobile'];
		    $final_leads_client_data[$k]['leads'] = $v['leads'];
		    //$final_leads_client_data[$k]['subscription_id'] = $v['subscription_id'];
		    $final_leads_client_data[$k]['remaining_credits'] = $v['remaining_credits'];
		    $final_leads_client_data[$k]['credits-to-be-deducted'] = ($v['leads']*40);
		    if($v['remaining_credits']-($v['leads']*40) >= 0){
			$final_leads_client_data[$k]['extra-credits-needed'] = 0;
		    }else{
			$final_leads_client_data[$k]['extra-credits-needed'] = ($v['leads']-($v['remaining_credits']/40))*40;
		    }
		}
	    }
	   
	    return $final_leads_client_data;
	}
	
	
	/*
	 *Function to create CSV with data obtained
	 *Param : Arrays which contain the client ID, Subscription ID, No. of Leads and Remaining Credits
	 */
	public function createCSV($clientId_credits_list = array() ){
	    
	    $this->load->library('zip');
	    
	    $fileName = "";
	    $ColumnListArray = array();
            $ColumnListArray = array('clientid','client-name','email','mobile','leads','remaining_credits','credits-to-be-deducted','extra-credits-needed');
            $csv = '';
	    
	    foreach ($ColumnListArray as $ColumnName) {
                $csv .= '"' . $ColumnName . '",';
            }
            $csv .= "\n";
	    
	    foreach ($clientId_credits_list as $clientId_leads_Subscription) {
		
		foreach ($ColumnListArray as $ColumnName) {
		    $csv .=  '"' .$clientId_leads_Subscription[$ColumnName]. '",';
		}
		$csv .= "\n";
		
		if(!empty($csv)) {
		    $fileName = '/ClientLeadsData/Clients_WithCredits'.time().'.xls';
		}
		
	    }
	    $this->zip->add_data($fileName, $csv);
	    
	    $this->zip->archive('/tmp/ClientLeads.zip'); 
            
	    $this->zip->download('ClientLeads.zip');
	    
        }
	
	
	function getMBAReportingData($fromDate, $toDate) {
	    
	    ini_set('memory_limit','128M');
	    
	    $this->load->model('leaddashboardmodel');
	    
	    if($fromDate == '') {
		
		$fromDate = date('Y-m-d H:i:s',strtotime('-1 week 00:00:00'));
		
	    } else {
		
		$fromDate = date('Y-m-d H:i:s',strtotime($fromDate.'00:00:00'));
		
	    }
            
	    if($toDate == '') {
		
		$toDate = date('Y-m-d H:i:s',strtotime('23:59:59'));
		
	    } else {
		
		$toDate = date('Y-m-d H:i:s',strtotime($toDate.'23:59:59'));
		
	    }
	    
	    $countMBAReportingData = $this->getMBALeadsAndResponsesCount($fromDate, $toDate);
	    
            if(!empty($countMBAReportingData)) {
                $leadsCount = $countMBAReportingData['Leads'][0]['UserCount'];
		$paidResponsesCount = $countMBAReportingData['Paid Responses'][0]['UserCount'];
		$freeResponsesCount = $countMBAReportingData['Free Responses'][0]['UserCount'];
            }
	    
            $from = 'info@shiksha.com';
            $to = 'ahsan.agha@shiksha.com';
            $cc = 'saurabh.gupta@shiksha.com';
	    
            $subject = 'Full-Time MBA Reporting Data | '.date('M d, Y');
	    
            $content = '<pre><span style="font-size: small;">Hi,</span><br /><br /><span style="font-size: small;">Please find below the MBA Leads and Responses Data generated on Shiksha.com for the given date range.</span><br /><br /><span style="font-size: small;">Regards,</span><br /><span style="font-size: small;">Shiksha.com</span><br /><br /><span style="font-size: x-small;">This email has been sent to you because you have requested Full-Time MBA Data on Shiksha.com.</span><br /><br /><span style="font-size: small;">Date Range:- From ' . date('M d, Y',strtotime($fromDate)) . ' Till ' . date('M d, Y',strtotime($toDate)) . ' </span><br /><br /><span style="font-size: small;">Leads Count :- ' . $leadsCount . '<br /><br /></span><span style="font-size: small;">Responses Count (Paid Listings) :- ' . $paidResponsesCount . '<br /><br /></span><span style="font-size: small;">Responses Count (Free Listings) :- ' . $freeResponsesCount . '</span></pre>';
	    
	    $this->_sendEmailWithAttachment($from,$to,$cc,$subject,$content,'','','no');
	    
	}
	
	function getMBALeadsAndResponsesCount($fromDate, $toDate) {
	    
	    $MBAListingsData = array();
	    
	    $MBAListingsData['Leads'] = $this->leaddashboardmodel->getMBALeadsGenerated($fromDate, $toDate);
	    
	    $MBAListingsData['Paid Responses'] = $this->leaddashboardmodel->getMBAResponsesGenerated('paid', $fromDate, $toDate);
	    
	    $MBAListingsData['Free Responses'] = $this->leaddashboardmodel->getMBAResponsesGenerated('free', $fromDate, $toDate);
	    
            return $MBAListingsData;
	
	}
	
}

?>
