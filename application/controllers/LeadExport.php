<?php

class LeadExport extends MX_Controller
{
    function exportLeads()
    {
        global $listings_with_localities;
                
        $smu_listings = $listings_with_localities['SMU'];
        $uts_listings = $listings_with_localities['UTS'];
        
        $this->load->library('LDB_Client');
                
        /*
         Export SMU Leads
        */
        $smu_leads = $this->ldb_client->getLeadsForInstitutes($smu_listings);
        
        $url = 'http://online.smude.edu.in/banner_mba2011/Banner.aspx';        
        //$post_url = SHIKSHA_HOME.'/LeadExport/processLeads';
        // $this->exportLeadsByPost($smu_leads,$post_url);

       // $this->exportLeadsByPost($smu_leads,$url);
        
        /*
         Export UTS Leads
        */
        $uts_leads = $this->ldb_client->getLeadsForInstitutes($uts_listings);
        $this->exportLeadsByCSV($uts_leads,'UTS');
    }
    
    function exportLeadsByPost($leads,$url)
    {
        require_once "globalconfig/localityCodes.php";
    
        if(is_array($leads) && count($leads))
        {
            foreach($leads as $lead)
            {
                $locality_code = $locality_codes[$lead['locality']];
                
                $fields = array(
                    'utm_source' => 'Siksha',
                    // 'utm_campaign' => 'Fall_IA',
                    // 'utm_campaign' => 'Winter_IA',
                    'utm_campaign' => 'Spring_IA',
                    'utm_medium' => 'Banner_210x431',
                    'Location' => $lead['locality'],
                    'Name' => $lead['firstname']." ".$lead['lastname'],
                    'Email' => $lead['email'],
                    'Mobilenumber' => $lead['mobile'],
                    'Course' => $lead['course'],
                    'City' => $lead['city'],
                    'lccode' => $locality_code,
                    'checkbox2' => 'Y'
                );
                
                $fields_string = '';
                
                foreach($fields as $key=>$value)
                {
                    $fields_string .= $key.'='.urlencode($value).'&';
                }
                
                $fields_string = substr($fields_string,0,strlen($fields_string)-1);

                //open connection
                $ch = curl_init();
            
                //set the url, number of POST vars, POST data
                curl_setopt($ch,CURLOPT_URL,$url.'?'.$fields_string);
                //curl_setopt($ch,CURLOPT_POST,count($fields));
                //curl_setopt($ch,CURLOPT_POSTFIELDS,$fields_string);
                
                //execute post
                $result = curl_exec($ch);
                
                //close connection
                curl_close($ch);
            }
        }
    }
    
    function exportLeadsByCSV($leads,$key)
    {
        if(is_array($leads) && count($leads))
        {
            $fh = fopen("/var/www/html/shiksha/public/".$key."_leads.csv","a");
            
            foreach($leads as $lead)
            {
                $lead['submit_date'] = date("d M Y, h:i A",strtotime($lead['submit_date']));
                
                $csv_data = array($lead['firstname']." ".$lead['lastname'],$lead['email'],$lead['mobile'],$lead['city'],$lead['locality'],$lead['course'],$lead['submit_date']);
                
                fputcsv($fh,$csv_data,";");
            }
            
            fclose($fh);
        }
    }
    
    function processLeads()
    {
        $fh = fopen("/var/www/html/shiksha/public/SMU_leads.csv","a");
        
        fwrite($fh,"POST data received at ".date("j M Y, H:i:s")."\n");
        fwrite($fh,"-------------------------------------------\n");
        
        foreach($_POST as $k => $v)
        {
            fwrite($fh,$k ." => ".$v."\n");
        }
        
        fwrite($fh,"\n");
        fclose($fh);
    }
    
    function sendCSVResponsesForNIIT()
    {
        $this->load->library('LDB_Client');
        $niit_responses = $this->ldb_client->getResponsesByClientId(1872137);
//      $niit_responses = $this->ldb_client->getResponsesByClientId(1298921);
        $leads = $this->ldb_client->createCSV($niit_responses);
        $name = "NIIT";
        $from = "leads@shiksha.com";
        $to = "imperia@niit.com";
        $cc = "mahesh.mahajan@naukri.com";
        $filename = "responses.csv";
        $this->ldb_client->sendCSVMail($leads,$name,$from,$to,$cc,$filename);
    }

    function sendCSVResponsesForSCDL()
    {
        $this->load->library('LDB_Client');
        $responses = $this->ldb_client->getResponsesByMultiLocationClientId(653124);
        $leads = $this->ldb_client->createCSV($responses);
        $name = "SCDL";
        $from = "leads@shiksha.com";
        $to = "rashmi.hendre@scdl.net";
        $cc = "prashant.soni@shiksha.com,trupti.chavan@scdl.net";
        $filename = "responses.csv";
        $this->ldb_client->sendCSVMail($leads,$name,$from,$to,$cc,$filename);
    }

        
    function sendCSVResponsesForICA()
    {
        $this->load->library('LDB_Client');
        $ica_responses = $this->ldb_client->getResponsesByClientId(485667);
        $leads = $this->ldb_client->createCSV($ica_responses);
        $name = "ICA";
        $from = "leads@shiksha.com";
        $to = "crm@icagroup.in";
        $cc = "biswakesh.tripathy@shiksha.com";
        $filename = "responses.csv";
        $this->ldb_client->sendCSVMail($leads,$name,$from,$to,$cc,$filename);
    }
    
    function sendCSVResponsesForMAAC()
    {
    	require_once FCPATH.'globalconfig/MAACCentreContacts.php';
        $this->load->library('LDB_Client');
        $maac_responses = $this->ldb_client->getResponsesByClientId(1958541);
        
        $reponse_ids = array();
        $maac_responses_map = array();
        
        foreach($maac_responses as $row){
        	$reponse_ids[] = $row['response_id'];
        	$maac_responses_map[$row['response_id']] = $row;
        }
            
        if(empty($reponse_ids)){
            return;
        }

        $localities = $this->ldb_client->getResponseLocalities($reponse_ids);
        
        $response_localities = array();
        
    	foreach($localities as $row){
        	$response_localities[$row['locality']][] = $maac_responses_map[$row['response_id']];
        }
        
        foreach($response_localities as $key=>$value){
        	if(isset($maacCentreContacts[$key]['email']))
        	{
	        	$to = $maacCentreContacts[$key]['email'];
	        	$leads = $this->ldb_client->createCSV($value);
	        	$name = "MAAC";
	        	$from = "leads@shiksha.com";
	        	$cc = "abhishek.jain@naukri.com";
	        	$filename = "responses.csv";
	        	$this->ldb_client->sendCSVMail($leads,$name,$from,$to,$cc,$filename);
        	}
        }
    }

    function sendCSVResponses()
    {
        $this->validateCron();
        $this->load->library('LDB_Client');
        $this->load->model('ldbmodel');
        $model_object = new LdbModel();
        $emailExportResults = $model_object->getListingtobeExported();
        foreach($emailExportResults as $row){
    	   	$to = $row['email'];

            $blockedEmailids = array();
            if(in_array($to, $blockedEmailids)) {
                continue;
            }

        	$name = $row['title'];
        	$from = "leads@shiksha.com";
        	
        	if($row['listingType'] == 'university_national') {
				$row['listingType'] = 'institute';
			}
			
        	$leadsResponse = modules::run('enterprise/Enterprise/getResponsesCSVForListing',$row['listingId'], $row['listingType'], 'both', $row['locationId'], $row['clientId'], '1 day', 0, 100000, True, False);        	
            $leads = $leadsResponse['csv'];
        	if(count(explode("\n", $leads)) > 1) {
        	    $filename =preg_replace('/[^A-Za-z0-9]/', '',$row['title']);
        	    $filename = $filename."_responses.csv";
        	    $this->ldb_client->sendCSVMail($leads,$name,$from,$to,$cc,$filename);
        	}
        }
    }

    public function mailMatchResponses($courseIds = array(), $quality = array(), $startDate = '', $endDate = '', $showTable = TRUE, $showDetailed = FALSE) {
    
    	$startDate = date('Y-m-d', strtotime('-1 day')) ;
    	$endDate = date("Y-m-d");
    
    	ini_set('memory_set', -1);
    
    	$courseIds = array(191623,130836,108327);
    	
    	$userArray = Modules::run("lms/lmsServer/getMatchedResponses",$courseIds, '',$startDate, $endDate);
    	
    	$newarray = $userArray['users'];
         unset($userArray);
    
    	foreach($newarray as $key=>$val){		//get all the user IDs
    		$userData[] = $key;
    	}
    
    	$userIds = $this->getNonRepeativeUserIds($userData);
    
    	if(!empty($userIds)){
    
    		$csvType = 'nationalMR';			//get csv for MR only
    		$csvData = Modules::run("enterprise/shikshaDB/getCommonCSVForLeads",$userIds,$csvType);
    			
    		if(!empty($csvData)) {
    				
    			$attachmentPath = $this->createCSV($csvData);			//get path for CSV created
    				
    			$from = 'info@shiksha.com';
    			$to = 'amit@iifp.in , simple@iifp.in';
    			$cc = 'Chawla.karan@shiksha.com';
    			$subject = 'Matched response for IIFP';
    			$message = 'Hi,<br><br>
						Please find attached the matched responses for your MBA course.
						<br><br>
						Regards,<br>
						Shiksha.com';
    			
    			$fileName = 'matched_response_'.date('Y-m-d').".zip";
    			
    			$this->load->library("common/Util");
    			$utilObj = new Util();
    			
    			$utilObj -> sendEmailWithAttachement($from,$to,$cc,$subject,$message,$attachmentPath,$fileName);
    			
    			$this->updateTempTable($userIds);
    				
    		}
    	}
    
    }
    

    public function mailMatchResponsesGIBS($courseIds = array(), $quality = array(), $startDate = '', $endDate = '', $showTable = TRUE, $showDetailed = FALSE) {
    
        $startDate = date('Y-m-d', strtotime('-1 day')) ;
        $endDate = date("Y-m-d");
    
        ini_set('memory_set', -1);
    
        $courseIds = array(129344,129412,109872,197166,178669,166531,195360,85245,160350,89030,193042);
        
        $userArray = Modules::run("lms/lmsServer/getMatchedResponses",$courseIds, '',$startDate, $endDate);
        
        $newarray = $userArray['users'];
        unset($userArray);
    
        foreach($newarray as $key=>$val){       //get all the user IDs
            $userData[] = $key;
        }
    
        $userIds = $this->getNonRepeativeUserIds($userData);
    
        if(!empty($userIds)){
    
            $csvType = 'nationalMR';            //get csv for MR only
            $csvData = Modules::run("enterprise/shikshaDB/getCommonCSVForLeads",$userIds,$csvType);
                
            if(!empty($csvData)) {
                    
                $attachmentPath = $this->createCSV($csvData);           //get path for CSV created
                    
                $from = 'info@shiksha.com';
                $to = 'head.operations@bestmbacollege.in';
                $cc = 'Yogesh.rai@shiksha.com,pradeep@gibsbschool.com';
                $subject = 'Matched response for GIBS B School';
                $message = 'Hi,<br><br>
                        Please find attached the matched responses for MBA courses.
                        <br><br>
                        Regards,<br>
                        Shiksha.com';
                
                $fileName = 'matched_response_'.date('Y-m-d').".zip";
                
                $this->load->library("common/Util");
                $utilObj = new Util();  
                
                $utilObj->sendEmailWithAttachement($from,$to,$cc,$subject,$message,$attachmentPath,$fileName);
                
                $this->updateTempTable($userIds);
                    
            }
        }
    
    }

    /*
     * Function updates table 'manual_script_tracking' after sending email to the User Ids
     */
    public function updateTempTable($userIds){
    
    	$this->dbLibObj = DbLibCommon::getInstance('LMS');
    	$dbHandle = $this->_loadDatabaseHandle('write');
    
    	foreach($userIds as $row => $val){
    
    		$query = "Insert into manual_script_tracking
						(id, script_name, user_ids, users_count, porting_type, data_type)
						 VALUES('', 'Match Response', ?, '1', 'email', 'MR')" ;
    		$dbHandle->query($query, array($val));
    	}
    
    }
    
    /*
     *Function to check whether User Ids already exists in table 'manual_script_tracking' or not.
     *Returns non existing values.
     */
    
    public function getNonRepeativeUserIds($userIds){
    
    	$this->dbLibObj = DbLibCommon::getInstance('LMS');
    	$dbHandle = $this->_loadDatabaseHandle();
    
    	foreach($userIds as $row => $val){
    
    		$query = "select * from  manual_script_tracking where script_name='Match Response' AND user_ids =?" ;
    		$result = $dbHandle->query($query, array($val));
    
    		if(!$result->num_rows()){				//put non existing values in array
    				
    			$nonRepeativeUserIds[] = $val;
    		}
    	}
    	return $nonRepeativeUserIds;
    }
    
    public function createCSV($csvData) {
    
    	$this->load->library('Zip');
    	$this->zip = new CI_Zip();
    
    	$fileName = 'matched_response_'.time().'.csv';
    	$this->zip->add_data($fileName, $csvData);
    
    	$return_path = '/tmp/matched_response_'.time().'.zip';
    	$this->zip->archive($return_path);
    
    	return $return_path;
    }
    
}
