<?php


class Porting extends MX_Controller
{	
	private $portingAdminId = 2796439;

	function init()
	{
		ini_set('max_execution_time', '10000');
	}
	
    private function _getDailyLimitsForAgents($activeMastersIdsData) {
		
		foreach($activeMastersIdsData as $k=>$v){
			if(!$v){	
				return ;
			}
		}
		
		$porting_model = $this->load->model('lms/portingmodel');
		
		$activeMasterIds=array();
		foreach($activeMastersIdsData as $k=>$v){
			$activeMasterIds[]=$k;
		}
		
		$daily_items_ported_for_agents 	= $porting_model->getNumberOfItemsPortedForAgents($activeMasterIds);
		$agents_ids_reached_limit 		= array();
		
		if(count($daily_items_ported_for_agents)>0) {
			
			foreach($activeMastersIdsData as $key=>$value) {
			   if($daily_items_ported_for_agents[$key] >= $value) {
			       $agents_ids_reached_limit[] = $key;					
			   }										
			}	
		}
		
		return $agents_ids_reached_limit;
	} 

	function startPorting($type){
		$this->validateCron();
		//$file = '/tmp/porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');

		//$maxtempLMSId = $this->getLastTempLMSId();

		$this->init();
		$this->load->library(array('PortingFactory'));
		$this->load->entities(array('PortingEntity'),'lms');
		$portingRepo = PortingFactory::getPortingRepository();
		$portings = $portingRepo->getAllLivePortings($type);

		$subsArr = array();
		$activeMastersIds = array();
		foreach($portings as $porting){
			$subsArr[] = $porting->getSubscriptionId();
			$active_master_Id = $porting->getId();
			$limitingData[$active_master_Id] = $porting->getDailyLimits();
		}
		//fwrite($fp, "Porting Cron Start ==".time()."\n");
		$this->load->library('Subscription_client');
		$sumsObject = new Subscription_client();


		if(count($subsArr)> 0){
			$validSubs = $sumsObject->sgetValidSubscriptions($subsArr,0, 1);
			$subsType = $sumsObject->getPortingSubscriptionType($subsArr);
			$responseDurationPortings = array();
			$leadsDurationPortings = array();
			$leadsQuantityPortings = array();
			foreach($portings as $porting){
				if($subsType[$porting->getSubscriptionId()] == "lead_duration"){
					$leadsDurationPortings[] = $porting;
				}
				else if($subsType[$porting->getSubscriptionId()] == "lead_quantity"){
					$leadsQuantityPortings[] = $porting;
				}
				else if($subsType[$porting->getSubscriptionId()] == "response_duration"){
					$responseDurationPortings[] = $porting;
				}
			}

			$portings = array();
			$portings = $responseDurationPortings;
			foreach($leadsDurationPortings as $porting){
				$portings[] = $porting;
			}
			foreach($leadsQuantityPortings as $porting){
				$portings[] = $porting;
			}
		}

		//fwrite($fp,"Porting Cron ==".time()."\n");
		$portings_tobe_excluded = $this->_getDailyLimitsForAgents($limitingData);

		$portingIds = array();
		foreach($portings as $porting){
			if(in_array($porting->getSubscriptionId(), $validSubs)){
				
				// daily limit check
				if(in_array($porting->getId(),$portings_tobe_excluded)) {
					continue;
				}

				if(in_array($porting->getType(), array('matched_response','lead'))){
					$clientPortingMap[$porting->getClientId()][] = $porting->getId();
				}
			}
		}

		foreach($portings as $porting){
			if( (in_array($porting->getSubscriptionId(), $validSubs)) || $porting->getType() == 'response' ){
				// daily limit check
				if(in_array($porting->getId(),$portings_tobe_excluded)) {
					continue;
				}
				//echo $porting->getType();exit;
				if($porting->getType() == 'response') {
				//fwrite('PortingId=='.$porting->getId());
				//fwrite($fp, 'response time start == '.time()."\n");
				} else {
				//fwrite('PortingId=='.$porting->getId());
				//fwrite($fp, 'lead time start == '.time()."\n");
				}

				if ($porting->getType()== "oaf"){
					continue;
				}
				$porterObj = PortingFactory::getPorterObj($porting->getType());
				
				$porterObj->setPorting($porting);
				$porterObj->port($clientPortingMap[$porting->getClientId()]);
				echo "Data Porting of ID : ".$porting->getId()." complete <br/>";
			}
		}
		//fwrite($fp,"Porting Cron End==".time()."\n");

		//$this->updateLastPortedIdForResponsePorting($maxtempLMSId);
		echo 'Cron Ends';

	}

	function startExamResponsePorting(){
		$this->validateCron();
		//$file = '/tmp/exam_response_porting_time_log'.date('Y-m-d').".txt";
        //$fp = fopen($file,'a');
        //fwrite($fp, "Exam Response Porting Cron Start ==".time()."\n");
		
		$this->init();
		$this->load->library(array('PortingFactory'));
		$this->load->entities(array('PortingEntity'),'lms');
		$portingRepo = PortingFactory::getPortingRepository();
		$data = $portingRepo->getAllLiveExamResponsePortings();
		$portings = $data['portings'];
		unset($data['portings']);

		//fwrite($fp, "Portings Picked ==".time()."\n");
		foreach($portings as $porting){
			//fwrite($fp, "Exam Response Porting for porting Id : ".$porting->getId()." start ==".time()."\n");
			$porterObj = PortingFactory::getPorterObj($porting->getType());
			$porterObj->setPorting($porting);
			$porterObj->port($data);
			echo "Data Porting of ID : ".$porting->getId()." complete <br/>";
			//fwrite($fp, "Exam Response Porting for porting Id : ".$porting->getId()." end ==".time()."\n");
		}

		// update last allocation id
		if($data['maxERAllocationId'] >0){
			$portingmodel = $this->load->model('lms/portingmodel');
			$portingmodel->updateLastExamResponseProcessedId($data['maxERAllocationId'],'EXAM_RESPONSE_PORTING');	
		}
		echo 'Cron Ends';	
		//fwrite($fp, "Exam Response Porting Cron End ==".time()."\n");
	}
	
	function startEmailPorting($portingTime='real_time'){
		$this->validateCron();
		$this->init();
		$this->load->library(array('PortingFactory'));
		$this->load->entities(array('PortingEntity'),'lms');
		$portingRepo = PortingFactory::getPortingRepository();
		$portings = $portingRepo->getAllLiveEmailPortings($portingTime);
		//check portings and exclude unwanted one
		$subsArr = array();
		
		$subsArr = array();
		foreach($portings as $porting){
			$subsArr[] = $porting->getSubscriptionId();
			$active_master_Id=$porting->getId();
			$limitingData[$active_master_Id]=$porting->getDailyLimits();
		}
		
		$this->load->library('Subscription_client');
		$sumsObject = new Subscription_client();

		if(count($subsArr)> 0){
			
			$validSubs = $sumsObject->sgetValidSubscriptions($subsArr,0, 1);
			$subsType = $sumsObject->getPortingSubscriptionType($subsArr);
			$responseDurationPortings = array();
			$leadsDurationPortings = array();
			$leadsQuantityPortings = array();
			foreach($portings as $porting){
				if($subsType[$porting->getSubscriptionId()] == "lead_duration"){
					$leadsDurationPortings[] = $porting;
				}
				else if($subsType[$porting->getSubscriptionId()] == "lead_quantity"){
					$leadsQuantityPortings[] = $porting;
				}
				else if($subsType[$porting->getSubscriptionId()] == "response_duration"){
					$responseDurationPortings[] = $porting;
				}
			}
			$portings = array();
			$portings = $responseDurationPortings;
			foreach($leadsDurationPortings as $porting){
				$portings[] = $porting;
			}
			foreach($leadsQuantityPortings as $porting){
				$portings[] = $porting;
			}
		}

		$portings_tobe_excluded = $this->_getDailyLimitsForAgents($limitingData);


		foreach($portings as $porting){
			if(in_array($porting->getSubscriptionId(), $validSubs)){
			
				// daily limit check
				if(in_array($porting->getId(),$portings_tobe_excluded)) {
						continue;
				}
				$porterObj = PortingFactory::getPorterObj($porting->getType());
				$porterObj->setPorting($porting);
				$porterObj->portEmail();
			}
		}
	}
	
	function startDailyEmailPorting(){
		$this->startEmailPorting('24_hours');
	}
	
	function testPorting(){
		$myFile = "/tmp/test.txt";
		$fh = fopen($myFile, 'a') or die("can't open file");
		fwrite($fh, serialize($_REQUEST));
		fwrite($fh, "\n");
		fclose($fh);
		echo "HUSAM test1";
		exit;
	}

    function portingMis(){
        $this->_validateuser = $this->checkUserValidation();
        if (empty($this->_validateuser['0']['userid'])) {
            header('location:'.ENTERPRISE_HOME);
            exit();
        }
        $data = array();
        $data['headerContentaarray'] = $this->_loadHeaderContent(PORTING_MIS_TAB_ID);
        $data['userId'] = $this->_validateuser['0']['userid'];
        $data['usergroup'] = $this->_validateuser['0']['usergroup'];
        $this->load->view('lms/portingMis',$data);
    }

    function getExamSubscriptionbyClientId($clientid,$filledData = array()){    	
        $data = array();
        if($clientid<1){
        	$clientid = $this->input->post('clientid');
        }

        $this->ERAccessLib = $this->load->library('enterprise/examResponseAccessLib');
       	
		if(!empty($filledData)){
			$type         = 'all';
		}else{
			$type         = 'active';			
		}
		$data['type'] = $type;
        $data['subscriptionDetails'] = $this->ERAccessLib->getSubscriptionData($type, $clientid);
        $data['portingConditions']   = $filledData;
		return $this->load->view('enterprise/examResponseAccess/subscriptionDetailsForPorting',$data, true);
    }

    function portingMisForm(){
        $this->_validateuser = $this->checkUserValidation();
        if (empty($this->_validateuser['0']['userid'])) {
            header('location:'.ENTERPRISE_HOME);
            exit();
        }
        $data = array();
        $clientid = $this->input->post('clientid');
        if($clientid>0){
            $this->load->model('lms/portingmodel');
            $portings = $this->portingmodel->getPortingsByClientId($clientid);
            
            if(!empty($portings)) {
                $subsArr = array();
                foreach($portings as $k=>$v){
                	if($v['subs'] != NULL){
                    	$subsArr[] = $v['subs'];
                	}
                }
                
                $subsType = array();
                if(count($subsArr) > 0){
                	$this->load->library('Subscription_client');
                	$sumsObject = new Subscription_client();
                	$subsType = $sumsObject->getPortingSubscriptionType($subsArr);	
                }

                foreach($portings as $k=>$v){
                	if($v['type'] != 'examResponse'){
	                    if($subsType[$v['subs']] == "lead_duration"){
	                        $data['lead_duration'][] = $v;
	                    }
	                    if($subsType[$v['subs']] == "lead_quantity"){
	                        $data['lead_quantity'][] = $v;
	                    }
	                    if($subsType[$v['subs']] == "response_duration"){
	                        $data['response_duration'][] = $v;
	                    }
	                } else {
	                	$data['exam_response'][] = $v;
	                }
                }
                $data['clientid'] = $clientid;
                $this->load->view('lms/portingMisForm',$data);
            }
        }
    }

    function misData($cronFlag = false){
        
        ini_set('memory_limit', '512M');
        
        if(!$cronFlag){
        	$this->_validateuser = $this->checkUserValidation();
        	if (empty($this->_validateuser['0']['userid'])) {
        	    header('location:'.ENTERPRISE_HOME);
        	    exit();
        	}
        }

		$clientId     = $this->input->post('clientid',true);
		$portingIds   = $this->input->post('portings',true);
		$dateFrom     = $this->input->post('timerange_from',true);
		$dateTo       = $this->input->post('timerange_to',true);
		$reportType   = $this->input->post('report_type',true);
		$reportFormat = $this->input->post('report_days',true);
		
        $this->load->model('lms/portingmodel');
        $this->portingmodel = new portingmodel();

        $excludedKeysArray = array('password','ePassword','PrefData','EducationData','ContactData','ViewCountArray');
        $misData = array();
        if($reportType == 'number'){
            foreach($portingIds as $k=>$portingId){
               $misData[$portingId] = $this->portingmodel->getMisCountData($portingId, $dateFrom, $dateTo, $reportFormat);
            }
        }
        if($reportType == 'data'){
            foreach($portingIds as $k=>$portingId){
                $misData[$portingId] = $this->portingmodel->getMisData($portingId, $dateFrom, $dateTo);
            }
            $portingFieldMappings = $this->portingmodel->getPortingFieldsMapping(($portingIds));
            
            $shikshaToClientFieldMapping = array();
            $fieldsToSend = array();
            foreach ($portingFieldMappings as $row) {
            	if($row['master_field_id'] > 0){
            		$shikshaToClientFieldMapping[$row['name']] = $row;
            		$fieldsToSend[] = $row['name'];
            	}
            	else{
            		$fieldsToSend[] = $row['client_field_name'];
            	}
            }

            foreach($misData as $portingId=>$portingData){
                foreach($portingData as $id=>$actualData){
                	// rename response field and remove extra fields
                	$misData[$portingId][$id]['Client API Response'] = $misData[$portingId][$id]['response'];
                	unset($misData[$portingId][$id]['response']);
                	unset($misData[$portingId][$id]['flag']);
                	unset($misData[$portingId][$id]['ported_item_id']);

                    $actualDataSent = preg_replace('!s:(\d+):"(.*?)";!se', "'s:'.strlen('$2').':\"$2\";'", base64_decode($actualData['sent_data']));
                    $unserDataArr = unserialize($actualDataSent);
                    unset($misData[$portingId][$id]['sent_data']);
                    foreach($unserDataArr as $key=>$value){
                    	// send only the fields asked by the client along with some hard coded specific fields
                    	if(in_array($key, $fieldsToSend)){
                    		if(!empty($shikshaToClientFieldMapping[$key])){
                    			$misData[$portingId][$id][$shikshaToClientFieldMapping[$key]['client_field_name']] = $value;
                    		}
                    		else{
                    			$misData[$portingId][$id][$key] = $value;
                    		}
                    	}
                    }
                }
            }
        }
        $finalOutput = "";
        foreach($misData as $portingId=>$data){
            $finalOutput.= $this->_createCSV($data);
        }

        $trackingSearchCriteria['start_date'] 		= $dateFrom;
        $trackingSearchCriteria['end_date'] 		= $dateTo;
        $trackingSearchCriteria['report_type'] 		= $reportType;
        if($reportFormat){
        	$trackingSearchCriteria['report_format'] 	= $reportFormat;
        }

        $enterpriseTrackingLib = $this->load->library('enterprise/enterpriseDataTrackingLib');
        foreach ($portingIds as $portingId) {
        	if(empty($misData[$portingId])){
        		continue;
        	}
        	$trackingParams                     = array();
        	$trackingParams['product']          = 'Porting';
        	$trackingParams['page_tab']         = 'Porting_MIS';
        	$trackingParams['cta']              = 'Download';
        	$trackingParams['entity_id']        = $portingId;
        	$trackingParams['records_fetched']  = count($misData[$portingId]);
        	$trackingParams['search_criteria']	= json_encode($trackingSearchCriteria);

        	$enterpriseTrackingLib->trackEnterpriseData($trackingParams);
        }

        if($cronFlag){
        	return $finalOutput;
        }
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=Shiksha-Porting-MIS.csv");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $finalOutput;
        exit;
    }

    public function sendPortingDataToSRM(){
    	$this->validateCron();

    	/* $request_data['client_id'] 		= 1056732;
    	$request_data['portingIds']		= array(327);
    	$request_data['dateTo']			= date('Y-m-d');
    	$request_data['dateFrom'] 		= date('Y-m-d', strtotime(' -1 day'));
    	
    	$request_data['reportType'] 	= 'data';
    	$request_data['csvName'] 		= 'Response_porting_report.csv';
    	$request_data['subject'] 		= ' Porting data for SRM';
    	$request_data['to_emailId'] 	= 'lingarajan.l@shiksha.com';
    	$request_data['cc_emailId'] 	= 'ruchika.rathee@shiksha.com';
    	$request_data['content'] 		= 'Please find the Porting reports for SRM university.';


    	$this->sendPortingDataToClient($request_data);
    	$request_data['portingIds'] 		= array(335);
    	$request_data['csvName'] 			= 'MR_porting_Report.csv';
    	$request_data['listing_type'] 		= 'institute';

    	$this->sendPortingDataToClient($request_data); */

    	$request_data['client_id'] 		= 3608782;
    	$request_data['portingIds']		= array(313, 314, 247, 250, 507, 509, 521, 525, 527, 537);
    	$request_data['dateTo']			= date('Y-m-d');
    	$request_data['dateFrom'] 		= date('Y-m-d', strtotime(' -1 day'));
    	$request_data['reportType'] 	= 'data';
    	$request_data['csvName'] 		= 'Porting_report.csv';
    	$request_data['subject'] 		= 'Porting data for ITM';
    	$request_data['to_emailId'] 	= 'Prashant.soni@shiksha.com';
    	$request_data['cc_emailId'] 	= 'amruta.deshmukh@shiksha.com';
    	$request_data['bcc_emailId'] 	= 'amrita.warrier@shiksha.com';
    	$request_data['content'] 		= 'Please find the Porting reports for ITM university.';


    	$this->sendPortingDataToClient($request_data);
    	
    	unset($request_data['bcc_emailId']);


    	$request_data['client_id'] 		= 5244618;
    	$request_data['portingIds']		= array(107, 252, 253, 337, 342, 349);
    	$request_data['dateTo']			= date('Y-m-d');
    	$request_data['dateFrom'] 		= date('Y-m-d', strtotime(' -1 day'));
    	$request_data['reportType'] 	= 'data';
    	$request_data['csvName'] 		= 'Manipal_Porting_report.csv';
    	$request_data['subject'] 		= 'Porting data for Manipal';
    	$request_data['to_emailId'] 	= 'sheeuli@shiksha.com';
    	$request_data['cc_emailId'] 	= 'karan.mohan@shiksha.com';
    	$request_data['content'] 		= 'Please find the Porting reports for Manipal university.';

    	//$this->sendPortingDataToClient($request_data);
    	echo 'Report Sent';
    }


    public function sendPortingDataToClient($request_data){
    	$this->load->library('alerts/Alerts_client');
		$alerts_client = new Alerts_client();
		$cronFlag = true;


    	$_POST['clientid'] 					= $request_data['client_id'];
        $_POST['portings'] 					= $request_data['portingIds'];
        $_POST['timerange_from']			= $request_data['dateFrom'];
        $_POST['timerange_to']				= $request_data['dateTo'];
        $_POST['report_type'] 				= $request_data['reportType'];
       	/*$_POST['report_days'] =  $reportFormat;*/
    	$porting_data = $this->misData($cronFlag);

    	
    	$csvName 	= $request_data['csvName'];
    	$subject 	= $request_data['subject'];
    	$emailId 	= $request_data['to_emailId'];
    	$cc_emailId = $request_data['cc_emailId'];

    	unset($bcc_emailId);
    	if($request_data['bcc_emailId']!= ''){
    		$bcc_emailId = $request_data['bcc_emailId'];
    	}

    	$content 	= $request_data['content'];
    	$client_id 	= $request_data['client_id'];
    	$listing_type ='course';

    	if($request_data['listing_type'] != ''){
    		$listing_type = $request_data['listing_type'];
    	}


    	$attachmentResponse = $alerts_client->createAttachment("12", $client_id, $listing_type, "E-Brochure", "$porting_data", $csvName, "E-Brochure");
		$attachmentResponse = $alerts_client->getAttachmentId("12", $client_id, $listing_type, "E-Brochure", $csvName);
		
		foreach ($attachmentResponse as $tempy) {
			$attachmentArray[] = $tempy['id'];
		}
		
		$response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", $emailId , $subject, $content, $contentType = "html", '', 'y', $attachmentArray,$cc_emailId, $bcc_emailId);
    }

    private function _createCSV($misData){
        $data = "";
        $header = "";
        foreach($misData as $row){
            $newHeader = $this->_createHeader($row);
            if($header == "" || $header != $newHeader){
                $data .= $newHeader;
                $header = $newHeader;
            }
            $line = "";
            foreach($row as $key=>$value){
	
		//if(!($key == 'response')){
			if(!isset($value) || $value == ""){
				 $value = ",";
			}else{
				$value = str_replace('"', '""', $value);
				$value = '"' . $value . '"' . ",";
			}
                $line.= $value;
		//}
	    }
            $data .= trim($line)."\n";
        }
        $data = str_replace("\r", "", $data);
        return $data;
    }

    private function _createHeader($misRow){
        $header = "";
        $line = "";
        foreach($misRow as $key=>$rowData){
		//if(!($key == 'response')){
			$value = '"' . $key . '"' . ",";
			$line.= $value;
		//}
	}
        $header .= trim($line)."\n";
        return $header;
    }

    private function _validateUser() {
        $this->_validateuser = $this->checkUserValidation();
        if (empty($this->_validateuser['0']['userid'])) {
            header('location:'.ENTERPRISE_HOME);
            exit();
        }
        elseif ($this->_validateuser['0']['userid'] != LMS_PORTING_USER_ID) {
            header("location:/enterprise/Enterprise/disallowedAccess");
            exit();
        }
    }

	private function _loadHeaderContent($tabId,$isDashboard = false) {
		$headerComponents = array(
			'css'   =>  array('headerCms','raised_all','mainStyle','footer','cal_style','lms_porting'),
			'js'    =>  array('common','enterprise','home','CalendarPopup','discussion','events','listing','blog'),
			'displayname'=> (isset($this->_validateuser[0]['displayname'])?$this->_validateuser[0]['displayname']:""),
			'tabName'   =>  '',
			'taburl' => site_url('enterprise/Enterprise'),
			'metaKeywords'  =>'',
			'prodId'=>$tabId
		);
		if($isDashboard){
			$headerComponents['css'][] = 'lms_portingV2';
		} 
		$this->load->library('Enterprise_client');
		$headerTabs = $this->enterprise_client->getHeaderTabs(1,$this->_validateuser[0]['usergroup'],$this->_validateuser[0]['userid']);
		$headerComponents['headerTabs'] = $headerTabs;
		$headerCMSHTML = $this->load->view('enterprise/headerCMS', $headerComponents,true);
		$headerTABSHTML = $this->load->view('enterprise/cmsTabs',$headerComponents,true);
		return array($headerCMSHTML,$headerTABSHTML);
	}

	function managePortings()
	{
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(LMS_PORTING_TAB_ID,true);
		
		$this->load->model('lms/portingmodel');
		$portings = $this->portingmodel->getAllPortings();
		 //_p($portings);die;
		if(!empty($portings)) {
			$subsArr = array();
			foreach($portings as $porting){
				$subsArr[] = $porting['SubscriptionId'];
			}
			
			$this->load->library('Subscription_client');
			$sumsObject = new Subscription_client();
			$validSubs  = $sumsObject->sgetValidSubscriptions($subsArr,0, 1);

			$subsType   = $sumsObject->getPortingSubscriptionType($subsArr);
			

			for($i=0; $i< count($portings); $i++){
			
				if( $portings[$i]['type'] != 'examResponse' && $portings[$i]['type'] != 'response' && !in_array($portings[$i]['SubscriptionId'], $validSubs) ){
					$portings[$i]['status'] = 'expired';
				}
				
				$portings[$i]['portingType'] = $portings[$i]['type'];
				if($subsType[$portings[$i]['SubscriptionId']] == "lead_duration"){
					$portings[$i]['type'] = 'Leads (Duration)';
				}
				else if($subsType[$portings[$i]['SubscriptionId']] == "lead_quantity"){
					$portings[$i]['type'] = 'Leads (Quantity)';
				}
				else if($subsType[$portings[$i]['SubscriptionId']] == "response_duration"){
					$portings[$i]['type'] = 'Responses (Duration)';
				}else if($portings[$i]['type'] == 'examResponse'){
					$portings[$i]['type'] = 'Exam Responses';
				}
			}
		}
		$data['portings'] = $portings;
		$this->load->view('lms/managePortings',$data);
	}

	function downloadPortings()
	{
		$this->_validateUser();
		$this->load->model('lms/portingmodel');
		$portings = $this->portingmodel->getAllPortings();
		if(!empty($portings)) {
			$subsArr = array();
			foreach($portings as $porting){
				$subsArr[] = $porting['SubscriptionId'];
			}
			$this->load->library('Subscription_client');
			$sumsObject = new Subscription_client();
			$validSubs =  $sumsObject->sgetValidSubscriptions($subsArr,0, 1);
			$subsType =  $sumsObject->getPortingSubscriptionType($subsArr);
			for($i=0; $i< count($portings); $i++){
				if($portings[$i]['type'] != 'examResponse' && !in_array($portings[$i]['SubscriptionId'], $validSubs)){
					$portings[$i]['status'] = 'expired';
				}
				
				if($subsType[$portings[$i]['SubscriptionId']] == "lead_duration"){
					$portings[$i]['type'] = 'Leads (Duration)';
				}
				else if($subsType[$portings[$i]['SubscriptionId']] == "lead_quantity"){
					$portings[$i]['type'] = 'Leads (Quantity)';
				}
				else if($subsType[$portings[$i]['SubscriptionId']] == "response_duration"){
					$portings[$i]['type'] = 'Responses (Duration)';
				}else if($portings[$i]['type'] == 'examResponse'){
					$portings[$i]['type'] = 'Exam Responses';
				}
			}
		}
		$data = array();
		foreach ($portings as $key=>$porting) {
			$data[$key]['Client ID'] = $porting['client_id'];
			$data[$key]['Client Name'] = $porting['displayname'];
			$data[$key]['Porting ID'] = $porting['id'];
			$data[$key]['Porting Name'] = $porting['name'];
			$data[$key]['Porting Type'] = $porting['type'];
			$data[$key]['Porting Method'] = $porting['request_type'];
			$data[$key]['Porting URL'] = $porting['api'];
			$data[$key]['Current Status'] = $porting['status'];
			$data[$key]['Creation Date'] = $porting['create_date'];
		}
		$finalOutput = $this->_createCSV($data);
		$filename = 'Portings on '.date('Y-m-d h-i-s').'.csv';
		$mime = 'text/x-csv';
		
		if (strstr($_SERVER['HTTP_USER_AGENT'], "MSIE")) {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header('Expires: 0');
			header('Cache-Control: must-revalidate, post-check=0, pre-check=0');
			header("Content-Transfer-Encoding: binary");
			header('Pragma: public');
			header("Content-Length: ".strlen($finalOutput));
		}
		else {
			header('Content-Type: "'.$mime.'"');
			header('Content-Disposition: attachment; filename="'.$filename.'"');
			header("Content-Transfer-Encoding: binary");
			header('Expires: 0');
			header('Pragma: no-cache');
			header("Content-Length: ".strlen($finalOutput));
		}
		echo ($finalOutput);
	}

	function addNewPorting()
	{
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(LMS_PORTING_TAB_ID);
		
		$this->load->model('lms/portingmodel');
		$data['shikshaFields'] = $this->portingmodel->getShikshaFields();
		$data['portingData'] = array();
		$this->config->load('lms/portingConfig');
		$customizedPortingIds = $this->config->item('customizedButtons');
		$data['customizedButtonIds'] = $customizedPortingIds;
		unset($customizedPortingIds);
		$this->load->view('lms/addPortings',$data);
	}

	public function clonePorting($portingId){
		$this->editPorting($portingId,true);
	}

	function editPorting($portingId,$clonePorting=false)
	{
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(LMS_PORTING_TAB_ID);
		
		if(!empty($portingId)) {
			$this->load->model('lms/portingmodel');
			$data['portingData'] = $this->portingmodel->getPortingMain($portingId);
			
			if(!empty($data['portingData'])) {
				$data['editPortedValues']     = 1;
				$data['activeSubscriptionId'] = $this->portingmodel->getPortingActiveSubscription($portingId);
				$data['clientFields']         = $this->portingmodel->getClientFields($portingId);
				$data['portingConditions']    = $this->portingmodel->getPortingConditions($portingId);
					

				if($data['portingData']['type'] == 'lead' || $data['portingData']['type'] == 'matched_response') {
					$portingType = 'lead';
				} elseif($data['portingData']['type'] == 'examResponse'){
					$portingType = 'exam';
				} else {
					$portingType = 'response';
				}
				$data['shikshaFields'] = $this->portingmodel->getShikshaFields($portingType);
				
				if(!$clonePorting){
					$this->load->library('sums_product_client');
					$sumsProductClient      =  new Sums_Product_client();
					//$subscriptionDetails    = $sumsProductClient->getAllSubscriptionsForUser(1,array('userId'=>$data['portingData']['client_id']));

					$subscriptionDetails = $this->getSubscriptionsForPorting($data['portingData']['client_id']);	

					$allSubscriptionDetails = array();
					foreach($subscriptionDetails as $key=>$vals){
						if($vals['BaseProductId'] == RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $vals['BaseProductId'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID) {
							$allSubscriptionDetails[$key] = $vals;
						}
					}
					$allSubscriptionDetails['RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID'] = RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID;
					$allSubscriptionDetails['LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID']     = LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID;
					$allSubscriptionDetails['LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID']     = LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID;
					$allSubscriptionDetails['GOLD_SL_LISTINGS_BASE_PRODUCT_ID'] 			   = GOLD_SL_LISTINGS_BASE_PRODUCT_ID;
					$allSubscriptionDetails['GOLD_ML_LISTINGS_BASE_PRODUCT_ID'] 			   = GOLD_ML_LISTINGS_BASE_PRODUCT_ID;
					

					$data['subscriptions']                                                     = $allSubscriptionDetails;
					

				}
				
				$extraFlag = $this->portingmodel->getUserPrefAbroadStatus($data['portingData']['client_id']);

				$data['extraFlag'] = $extraFlag;

				if($data['portingData']['type'] == 'response') {					
					$this->load->model('listing/abroadcoursefindermodel');
					$this->load->model('listingCommon/listingcommonmodel');

					$listingIds     = $this->listingcommonmodel->getActiveListingsForClients(array($data['portingData']['client_id']));
					$instituteList  = array();
					$universityList = array();
					foreach ($listingIds as $key => $listing) {
						if($listing['listing_type'] == 'institute' || $listing['listing_type'] == 'university_national') {
							$instituteList[$listing['listing_type_id']] = $listing;
						}
						if($listing['listing_type'] == 'university' && $data['extraFlag'] == 'studyabroad') {
							$universityList[$listing['listing_type_id']] = $listing;
						}
					}
					
					if(count($universityList)) {
						$universityCourseMap = array();
						$universityCourseMap = $this->abroadcoursefindermodel->getCoursesOfferedByMultipleUniversities(array_keys($universityList),'PAID');
						for($i=0; $i<count($universityCourseMap['course_ids']); $i++) {
								$data['universityList'][$universityCourseMap['courses'][$i]['universityID']]['universityDetails'] = $universityList[$universityCourseMap['courses'][$i]['universityID']];
								$data['universityList'][$universityCourseMap['courses'][$i]['universityID']]['courseList'][$universityCourseMap['course_ids'][$i]] = $universityCourseMap['courses'][$i]['courseName'];
						}
					}
					if(count($instituteList)) {
						$data['instituteList'] = $this->getNationalListingsDetailsForClient(array($data['portingData']['client_id']));
					}
					$data['dataported'] = ($this->load->view('lms/responseDetails',$data,true));
					
					$responseSubscriptions = array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID);

					$considerSubscription = true;
					$responseSubscriptionDetails = array();
					foreach($subscriptionDetails as $key=>$vals){
						if($vals['BaseProductId'] == RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $vals['BaseProductId'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID) {

							if(in_array($vals['BaseProductId'], $responseSubscriptions)){
								if(!$considerSubscription){
									continue;
								}

								$considerSubscription = false;						
							}

							$responseSubscriptionDetails[$key] = $vals;
						}
					}
					$data['subscriptionDetails'] = $responseSubscriptionDetails;
				}
				elseif($data['portingData']['type'] == 'lead' || $data['portingData']['type'] == 'matched_response') {
					$data['leadGenieDetails'] = $this->portingmodel->getAllPortingSearchAgents($data['portingData']['client_id']);
					$data['dataported'] = ($this->load->view('lms/leadGenieDetails',$data,true));
					
					$leadSubscriptionDetails = array();
					foreach($subscriptionDetails as $key=>$vals){
						if($vals['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID) {
							$leadSubscriptionDetails[$key] = $vals;
						}
					}
					$data['subscriptionDetails'] = $leadSubscriptionDetails;
				}
				elseif($data['portingData']['type'] == 'examResponse'){
					$data['examSubscriptionDetails']  = $this->getExamSubscriptionbyClientId($data['portingData']['client_id'],$data['portingConditions']);

				}
				
				if($clonePorting){
					unset($data['activeSubscriptionId']);
					unset($data['portingData']['id']);
					unset($data['portingData']['name']);
					unset($data['portingData']['type']);
					unset($data['portingData']['last_ported_id']);
					unset($data['portingData']['create_date']);
					unset($data['portingData']['firsttime_startdate']);
					unset($data['portingData']['isrun_firsttime']);
					unset($data['portingData']['client_id']);
					unset($data['portingData']['duration']);
					unset($data['subscriptions']);
					unset($data['subscriptionDetails']);
				}
				$this->config->load('lms/portingConfig');
				$customizedPortingIds = $this->config->item('customizedButtons');
				$data['customizedButtonIds'] = $customizedPortingIds;
				unset($customizedPortingIds);
				$this->load->view('lms/addPortings',$data);
				return true;
			}
		}
		
		header('location:'.ENTERPRISE_HOME);
		exit();
	}

	function getDetailsForClientId()
	{
		$this->_validateUser();
		
		$userid = $this->input->post('client_id');
		$this->load->library('sums_product_client');
		$this->load->model('lms/portingmodel');
		$this->load->model('listing/abroadcoursefindermodel');
		$this->load->model('listingCommon/listingcommonmodel');

		/*$sumsProductClient =  new Sums_Product_client();
		$subscriptionDetails = $sumsProductClient->getAllSubscriptionsForUser(1,array('userId'=>$userid));
		*/


		$subscriptionDetails = $this->getSubscriptionsForPorting($userid);	
	
		$extraFlag = $this->portingmodel->getUserPrefAbroadStatus($userid);
		
		if(!empty($subscriptionDetails)) {
			$finalData = array();
			$data = array();
			$allSubscriptionDetails = array();
			$considerSubscription = true;

			$responseSubscriptions = array(GOLD_SL_LISTINGS_BASE_PRODUCT_ID, GOLD_ML_LISTINGS_BASE_PRODUCT_ID);

			foreach($subscriptionDetails as $key=>$vals){
				if($vals['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $vals['BaseProductId'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID) {

					if(in_array($vals['BaseProductId'], $responseSubscriptions)){
						if(!$considerSubscription){
							continue;
						}
						$considerSubscription = false;						
					}

					$allSubscriptionDetails[$key] = $vals;

				}
			}

			if(!empty($allSubscriptionDetails)) {
				$data['extraFlag'] = $extraFlag;
				$data['subscriptionDetails'] = $allSubscriptionDetails;
				$allSubscriptionDetails['RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID'] = RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID;
				$allSubscriptionDetails['LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID'] = LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID;
				$allSubscriptionDetails['LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID'] = LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID;
				$allSubscriptionDetails['GOLD_SL_LISTINGS_BASE_PRODUCT_ID'] = GOLD_SL_LISTINGS_BASE_PRODUCT_ID;
				$allSubscriptionDetails['GOLD_ML_LISTINGS_BASE_PRODUCT_ID'] = GOLD_ML_LISTINGS_BASE_PRODUCT_ID;
				
				$finalData['subscriptions'] = $allSubscriptionDetails;
				$data['leadGenieDetails'] = $this->portingmodel->getAllPortingSearchAgents($userid);
				
				$listingIds = $this->listingcommonmodel->getActiveListingsForClients(array($userid));
        		
				$instituteList = array();
				$universityList = array();
				foreach ($listingIds as $key => $listing) {
					if($listing['listing_type'] == 'institute' || $listing['listing_type'] == 'university_national') {
		                $instituteList[$listing['listing_type_id']] = $listing;
		            }
					if($listing['listing_type'] == 'university' && $data['extraFlag'] == 'studyabroad') {
						$universityList[$listing['listing_type_id']] = $listing;
					}
				}
				if(count($universityList)) {
					$universityCourseMap = array();
					$universityCourseMap = $this->abroadcoursefindermodel->getCoursesOfferedByMultipleUniversities(array_keys($universityList),'PAID');
					for($i=0; $i<count($universityCourseMap['course_ids']); $i++) {
							$data['universityList'][$universityCourseMap['courses'][$i]['universityID']]['universityDetails'] = $universityList[$universityCourseMap['courses'][$i]['universityID']];
							$data['universityList'][$universityCourseMap['courses'][$i]['universityID']]['courseList'][$universityCourseMap['course_ids'][$i]] = $universityCourseMap['courses'][$i]['courseName'];
					}
				}
				
				if(count($instituteList)) {

					$data['instituteList'] = $this->getNationalListingsDetailsForClient(array($userid));

				}

				//$finalData['examSubscriptionHtml'] = $this->getExamSubscriptionbyClientId($userid);


				$finalData['subscriptionsHtml'] = ($this->load->view('lms/subscriptionDetails',$data,true));
				$finalData['responseData'] = ($this->load->view('lms/responseDetails',$data,true));
				$finalData['leadGenies'] = ($this->load->view('lms/leadGenieDetails',$data,true));
				//echo json_encode($finalData);
			}else{
				$finalData['subscriptionsHtml'] = 'No Subscriptions';	
			}
		}else{
			$finalData['subscriptionsHtml'] = 'No Subscriptions';
		}

		$finalData['examSubscriptionHtml'] = $this->getExamSubscriptionbyClientId($userid);
		echo json_encode($finalData);

	}

	function addPorting()
	{
		$this->_validateUser();
		$this->load->model('lms/portingmodel');
		$this->portingmodel->addPorting($_POST);
		echo 'success';
	}

	function updatePorting()
	{
		$this->_validateUser();
		$this->load->model('lms/portingmodel');
		$this->portingmodel->updatePorting($_POST);
		echo 'success';
	}

	function updateProtingStatus()
	{
		$this->_validateUser();
		
		$portingId = $this->input->post('porting_id');
		$status = $this->input->post('status');
		$portingType = $this->input->post('portingType');
		$this->load->model('lms/portingmodel');
		if($this->portingmodel->changePortingStatus($portingId,$status, $portingType)) {
			echo 'success';
		}
	}
	
	public function getCourseDetails($client_id, $customField,$entity_id_text,$entity_name_text,$validation=false)
	{	
		$this->load->model('listing/coursemodel');
		$this->load->model('lms/portingmodel');
		
		$client_course_index_array = array();
		$courseNameMapping = array();

		$extraFlag = $this->portingmodel->getUserPrefAbroadStatus($client_id);
		
		if($extraFlag == 'studyabroad'){

			$client_course_index_array = $this->coursemodel->getCoursesForClients(array($client_id));
			$courseNameMapping = $this->coursemodel->getCourseNamesForCourseIds($client_course_index_array[$client_id]);

		} else {

			$allCoursesArray = array();
			$listingsDetails = $this->getNationalListingsDetailsForClient(array($client_id),$customField);

			$allEntity = array();
			foreach ($listingsDetails as $instituteId => $instituteDetails) 
			{
				foreach ($instituteDetails['courseList'] as $courseId => $courseName) 
				{
					$allEntity[] = $courseId;
					$allCoursesArray[] = $courseId;
					$courseNameMapping[$courseId] = $courseName;
				}

				$instituteNameMapping[$instituteId] = $instituteDetails['listing_title'];

				$allEntity[] = $instituteId;
			}
			
			$client_course_index_array[$client_id] = implode(',',$allCoursesArray);

		}

		$allEntity = implode(',', $allEntity);
		
		$this->load->model('lms/portingmodel');
		$mappedFields                      = array();
		$mappedFields                      = $this->portingmodel->getCustomizedMappedFields($allEntity, $customField,'single',$client_id);

		$dummyMappedFields                 = array();
		$dummyMappedFields                 = $this->portingmodel->getDummyCustomizedMappedFields($client_id,$customField);
		$data                              = array();
		$data['clientId']                  = $client_id;
		$data['clientToEntityMapping']     = $client_course_index_array;
		$data['entityNameMapping']         = $courseNameMapping;
		if ($customField =='course_name' || $customField == 'course_level' ){
			$data['instituteNameMapping']      = $instituteNameMapping;
		}
		$data['entity_id_text']            = $entity_id_text;
		$data['entity_name_text']          = $entity_name_text;
		$data['customField']               = $customField;
		$data['mappedFields']              = $mappedFields;
		$data['dummyMappedFields']         = $dummyMappedFields;

		if($validation){
			return $data;
		}
		$html = $this->load->view('lms/commonCustomizationDetails', $data, true);
		
		return $html;
	}
	
	public function setCustomizedFields()
	{
		$clientId = $this->input->post('clientId');
		$customFieldIds = $this->input->post('customFieldId');
		$customField = $this->input->post('customField');
		$entityIds = $this->input->post('entityId');
		
		$this->load->model('lms/portingmodel');
		$this->portingmodel->setCustomizedMappedFields($clientId, $entityIds, $customFieldIds, $customField);
		

		$instiEntityIds 	= array();
		$insticustomFieldId = array();

		$insticustomField 			= $this->input->post('insticustomField');
		if($insticustomField){
			$entityIds 				= $this->input->post('instituteId');
			$customFieldIds 		= $this->input->post('insticustomFieldId');
			if ($customField == 'course_level'){
				$customField = 'course_level_ivr';
			}
			else{
				$customField = 'course_name_ivr';
			}

			$this->portingmodel->setCustomizedMappedFields($clientId, $entityIds, $customFieldIds, $customField);
		}

		
		echo "Success";
	}

	/**
	 *Function to add custom location to course
	 *@param : none
	 *return : none
	 */
	public function addCustomLocation(){

		return;                      // done for temporarily removing Add Custom Location Tab 
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(LMS_PORTING_CUSTOM_LOCATION_ID);
		
		$this->load->view('lms/addCustomLocation',$data);
	}
	
	/*
	 *Function to get courses and subscriptions for the client Id
	 *Param: client ID, header to be loaded(yes or no), message
	 *Return: Loads the view for adding custom location for the provided client ID. Also displays
	 *the existing courses of the client.
	 */
	public function getCourseListAndSubscriptionByClientID($client,$loadHeader="no",$messagetext=""){
		
		$this->_validateUser();
	        $data = array();
		$porting_model = $this->load->model('lms/portingmodel');
		$this->load->library('sums_product_client');
		$course_model = $this->load->model('listing/coursemodel');
		$listing_model = $this->load->model('listing/listingmodel');
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		
		if($loadHeader == "yes"){
			$data['headerContentaarray'] = $this->_loadHeaderContent(LMS_PORTING_CUSTOM_LOCATION_ID);
		}
		
		if($messagetext!="" || $messagetext!=null){ 
			$data['messagetext']=$messagetext;
		}
		
		$allSubscriptionDetails = array();
		$courseList = array();
		$course_name = array();
		$institute_name = array();
		$instituteList = array();
		$courseIdIfCourseExistsInCustomLoactionTable = array();
		$sumsProductClient =  new Sums_Product_client();
		$subscriptionDetails = $sumsProductClient->getAllSubscriptionsForUser(1,array('userId'=>$client));
		if(!empty($subscriptionDetails)){
			$listingIds = $listing_model->getActiveLisitingsForagroupOfOwner($client);
			
			foreach ($listingIds as $key => $listing) {
							if($listing['listing_type'] == 'institute' && $data['extraFlag'] !== 'studyabroad') {
								$instituteList[$listing['listing_type_id']] = $listing;
							}
			}
			
			$instituteCourseMap = $this->institutemodel->getCoursesForInstitutes(array_keys($instituteList),'PAID');
			
			foreach ($instituteCourseMap as $instituteId => $instituteCourseData) {
				
				foreach($instituteCourseData['course_title_list'] as $courseId => $courseTitle){
					$courseList[$courseId] = $courseTitle;
					$data['courseList'] = $courseList;
					$data['instituteList'][$instituteId]['instituteData'] = $instituteList[$instituteId];
					$data['instituteList'][$instituteId]['courseList'][] = array('id' => $courseId, 'name' => $courseTitle);		
				} 
			}
			
			foreach($subscriptionDetails as $key=>$vals){
				if($vals['BaseProductId'] == RESPONSE_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == LEAD_PORTING_DURATION_BASED_BASE_PRODUCT_ID || $vals['BaseProductId'] == LEAD_PORTING_QUANTITY_BASED_BASE_PRODUCT_ID) {
					$allSubscriptionDetails[$key] = $vals;
				}
			}
			
			$data['subscriptions'] = $allSubscriptionDetails;
			$data['clientID'] = $client;
			
			if(!empty($courseList) && !empty($allSubscriptionDetails)){
			
				$courseIdIfCourseExistsInCustomLoactionTable = $porting_model->existingCourseIdInCustomLocationTable($courseList);
			
				foreach($courseIdIfCourseExistsInCustomLoactionTable as $courseId){		
					$course_object = $courseRepository->find($courseId);
					$course_name[] = $course_object->getName();
					$institute_id = $course_object->getInstId();
					$institute_object = $instituteRepository->find($institute_id);
					$institute_name[] = $institute_object->getName();
				}
			}else{
				return;
			}
			$data['collegeName'] = $institute_name;
			$data['courseName'] = $course_name;
			$data['courseID'] = $courseIdIfCourseExistsInCustomLoactionTable;
			$this->load->view('lms/addCustomLocationByClient',$data);
		}else{
			return;
		}
	}
	
	/*
	 *Function to upload CSV and insert data from the same into CustomLocationTable
	 */
	public function uploadCSVForCustomLocation(){
		ini_set("memory_limit","-1");
		$this->load->library('common/reader');
		include_once(APPPATH.'/modules/Common/common/libraries/PHPExcel/Reader/Excel2007.php');
		$this->load->library('common/PHPExcel/IOFactory');
		$porting_model = $this->load->model('lms/portingmodel');
		$this->load->builder('ListingBuilder','listing');
		$listingBuilder = new ListingBuilder;
		$instituteRepository = $listingBuilder->getInstituteRepository();
		$courseRepository = $listingBuilder->getCourseRepository();
		$client=$this->input->post('clientid',true);
		$courseID=$this->input->post('course',true);
		
		if($courseID>0){
			$course_object = $courseRepository->find($courseID);
			$institute_id = $course_object->getInstId();
		}
		
		//Function to check if any file has been uploaded
		if(count($_FILES)>0){
			
			//Undefined | Multiple Files | $_FILES Corruption Attack
			//If this request falls under any of them, treat it invalid.
			if(!$_FILES['spreadsheet']['error']){
			        $inputFile = $_FILES['spreadsheet']['name'];
				$extension = strtoupper(pathinfo($inputFile, PATHINFO_EXTENSION));
				
				//check the extension of the uploaded file
				if($extension == 'XLSX' || $extension == 'ODS' || $extension == 'XLS'){
					
					//Read spreadsheeet workbook
					try {
						$inputFile = $_FILES['spreadsheet']['tmp_name'];
						$inputFileType = PHPExcel_IOFactory::identify($inputFile);
						$objReader = PHPExcel_IOFactory::createReader($inputFileType);
						$objPHPExcel = $objReader->load($inputFile);
						$total_sheets=$objPHPExcel->getSheetCount(); 
						$allSheetName=$objPHPExcel->getSheetNames();
						
					
					} catch(Exception $e) {
						die($e->getMessage());
					}
	
					//Get worksheet dimensions
					$sheet = $objPHPExcel->getSheet(0);												
					$highestRow = $sheet->getHighestRow(); 
					$highestColumn = $sheet->getHighestColumn();
					$allData=array();
					$excelData=array();
					
					//Read a row of data into an array
					$rowData = $sheet->rangeToArray('A' . 1 . ':' . $highestColumn . 1, NULL, TRUE, FALSE);
					
					if($extension == "XLSX" || $extension == "XLS"){
						
						$number_column = 0;

						foreach($rowData[0] as $row) {
							if(!empty($row)) {
								$number_column++;
							}
						}

						if($number_column==3) {
							$highestColumn = 'C';	
						} else {
						        $messagetext="Please check the fields in your file. There should be only 3 fields.";
							echo "Please check the fields in your file. They should be : City Locality PCode.";
							redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
						}
					}
					

					if($highestColumn == 'C'){
						
						if(strtoupper($rowData[0][0])=="CITY" && strtoupper($rowData[0][1])=="LOCALITY" && strtoupper($rowData[0][2])=="PCODE"){							
							for($row = 2; $row <= $highestRow; $row++){
								$rowDataValues = $sheet->rangeToArray('A' . $row . ':' . $highestColumn . $row, NULL, TRUE, FALSE);
								
								if(empty($rowDataValues[0][0]) || empty($rowDataValues[0][1])) {
									continue;
								}
							
								$allData[] = $rowDataValues;
								
							}
							
							$number_row = count($allData);
							if($number_row == 0) {
								$messagetext="Please check the fields in your file. City or Locality cannot be empty.";
								redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
							}

						}else{
							
							$messagetext="Please check the fields in your file. They should be : City, Locality, PCode.";
							redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
					
						}
					}else{
						
						$messagetext="Please check the fields in your file. There should be only 3 fields.";
						redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
					
					}
					
					foreach($allData as $k=>$vi){
						foreach($vi as $ki=>$v){
							$excelData[] = $v;
						}
					}
					
						//Insert into database
						$flagForUpdateOrInsert = $porting_model->insertInCustomLocationTable($excelData,$courseID,$institute_id);
						if($flagForUpdateOrInsert == 1){
							$messagetext = "Action has been performed succesfully.";
							redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
						}else{
							$messagetext = "Action could not be performed.";
							redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
						}
				
				}else{
					$messagetext="Please upload an XLSX or XLS or ODS file";
					redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);
				
				}
			    
			}
			
		}else{			               

					$messagetext="Please select a file";
					redirect("/lms/Porting/getCourseListAndSubscriptionByClientID/".$client."/yes/".$messagetext);

		}	
	}	

	/*
	 *Function to delete a course tupple from CustomLocationTable
	 */
	function deleteCourseFromCustomLocationTable(){
		
		$porting_model = $this->load->model('lms/portingmodel');
		$courseId=$this->input->get('courseId',true);
		$client=$this->input->get('client',true);		
		$deleteFlag = $porting_model->deleteCourseEntryFromCustomLocationTable($courseId);
		
		if($deleteFlag == 0){
			$messagetext = "This entry was not found.";			
		}else{
			$messagetext = "Deleted succesfully";
			
		}
		
		echo $messagetext;
	}

	/**
	* Function for taking date input for admin report
	* 
	* @param : none
	* @return : none
	*/
	public function portingReport($showAlert = '0'){
		$this->load->library('session');
		$this->_validateUser();
		$data = array();
		$data['headerContentaarray'] = $this->_loadHeaderContent(ADMIN_REPORT_TAB_ID);
		$data['showAlert'] = $showAlert;
		$this->load->view('portingReport',$data);
		
	}

	/**
	* Function to generate Admin Report with all portings
	* 
	* @param : none
	* @return : csv file
	*/
	public function generatePortingReport(){
		
		$dateFrom = $_POST['timerange_from'];
        $dateTo = $_POST['timerange_to'];
        
        
        $this->load->library('session');
        $porting_model = $this->load->model('lms/portingmodel');
        $reportData = $porting_model->getReportData($dateFrom,$dateTo);

        if(empty($reportData)){
        	$this->session->set_flashdata('from',$dateFrom);
			$this->session->set_flashdata('to',$dateTo);	
        	redirect("/lms/Porting/portingReport/1");
        	
        }
        
        $finalOutput="";	
       
        $finalOutput.= $this->_createCSV($reportData);
        
        $filename = 'Admin-Report_'.$dateFrom.'_'.$dateTo.'.csv';
        
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename=\"" . basename($filename) . "\"");
        header("Pragma: no-cache");
        header("Expires: 0");
        echo $finalOutput;
        
        exit;
	}
	
	public function consumeCredit() {
		
		/*$this->load->library('Subscription_client');
		$sumsObject = new Subscription_client();
		$sumsObject->sdeductLeadPortingCredits($subs_id, $required_credit);*/
	}

	function getNationalListingsDetailsForClient($clientIdsArray = array(),$customField='') {

		$this->load->model('listingCommon/listingcommonmodel');
		$this->load->builder("nationalInstitute/InstituteBuilder");
        $instituteBuilder = new InstituteBuilder();
        $instituteRepo = $instituteBuilder->getInstituteRepository();
        
        $this->load->builder("nationalCourse/CourseBuilder");
        $courseBuilder = new CourseBuilder();
        $courseRepo = $courseBuilder->getCourseRepository();
        
        $this->load->library("nationalInstitute/InstituteDetailLib");
        $lib = new InstituteDetailLib();

        $listingIds = $this->listingcommonmodel->getActiveListingsForClients($clientIdsArray);

        $instituteList = array();
        $data = array();
		
		foreach ($listingIds as $key => $listing) {
			if($listing['listing_type'] == 'institute' || $listing['listing_type'] == 'university_national') {
                $instituteList[$listing['listing_type_id']] = $listing;
            }
		}
		
		if(count($instituteList)) {

			$allInstitutes = $instituteRepo->findMultiple(array_keys($instituteList));

			foreach ($allInstitutes as $instituteId => $instituteObject) {

				$type = $instituteObject->getType();
	            $instituteCourseMap = $lib->getInstituteCourseIds($instituteId, $type);
	            
	            if(!empty($instituteCourseMap['courseIds'])) {

	            	$allCourses = $courseRepo->findMultiple($instituteCourseMap['courseIds']);
	            	$data['instituteList'][$instituteId]['courseList'] = array();
	            	foreach ($allCourses as $courseId => $courseObject) {
	                	if(in_array($courseId, $instituteCourseMap['instituteWiseCourses'][$instituteId])){
	                		
                            if($customField=='MRCourse') {
                            	
                            	$this->getMRCourses($courseObject,$data);

                            } else if($courseObject->isPaid()) {

	                			$courseArray = array();
	                			$courseArray['id'] = $courseObject->getId();
								$courseArray['name'] = $courseObject->getName();
                               	$data['instituteList'][$instituteId]['courseList'][$courseArray['id']] = $courseArray['name'];

                            }

	                	}

	                }

	                if(!empty($data['instituteList'][$instituteId]['courseList'])) {

		                $data['instituteList'][$instituteId]['listing_type_id'] = $instituteId;
		                $data['instituteList'][$instituteId]['listing_type'] = $instituteObject->getType();
		                switch ($instituteObject->getType()) {
		                    case 'institute':
		                        $data['instituteList'][$instituteId]['listing_title'] = $instituteObject->getName()." (College)";
		                        break;

		                    case 'university':
		                        $data['instituteList'][$instituteId]['listing_title'] = $instituteObject->getName()." (University)";
		                        break;
		                    
		                    default:
		                        $data['instituteList'][$instituteId]['listing_title'] = $instituteObject->getName();
		                        break;
		                }
		                
		                $data['instituteList'][$instituteId]['pack_type'] = $instituteObject->getPackType();

		            } else {
		                unset($data['instituteList'][$instituteId]);
		            }

	            }

			}

		}
		return $data['instituteList'];
	}

	public function portDataFromExcel(){
		//note :  delete genie after creating
		// stop : matching cron , porting cron
		// require a searchagentid before executing this script
		//Need to add this genie id in script before executing it.
		//Dummpy_Genie_For_manual_Download

		return;
		$this->validateCron();
		$this->benchmark->mark('code_start');

		ini_set('memory_limit', '500M');
		ini_set('max_execution_time', '10000');
		$parameters = array(
			'limit'      => 6000,
			'portingId'  => 337,
			'clientId' => 5244618,
			'filePath'   => '/tmp/MU leads 19jan18 to 8feb18.xlsx',
			'portingUrl' => 'https://manipal.edu/bin/manipal/components/generate/thirdpartyrfileads',
			'searchAgentId' => '12345', // need 
			'streamId' => 2,
			'subStreamId' => 0,
			'profileType' => 'implicit'
		);

		$this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($parameters['filePath']);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);

        $emailIds = array();
        for($row = 2; $row <= $highestRow; ++$row) {
            $emailId = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
            if(!empty($emailId)){
		    	$emailIds[] = trim($emailId);
		    }
        }
        //_p($emailIds);die;
	    // get user id for email
	    if(count($emailIds) >0){
	    	$portingModel = $this->load->model('lms/portingmodel');
		    $result = $portingModel->getUserIdsForEmail($emailIds);
		    $emailIdToUserIdMapping = array();
		    $userIds = array();
		    foreach ($result as $key => $row) {
		    	$userIds[] = $row['userid'];
		    	$emailIdToUserIdMapping[$row['email']] = $row['userid'];
		    }
		    
		    $emailIdToUserIdMappingFinal = array();
		    foreach ($emailIds as $key => $value) {
		    	if(!empty($emailIdToUserIdMapping[$value])){
		    		$emailIdToUserIdMappingFinal[$value] = $emailIdToUserIdMapping[$value];	
		    	}
		    }

		    $emailIdToUserIdMapping = $emailIdToUserIdMappingFinal;
		    unset($emailIdToUserIdMappingFinal);
			$result = $portingModel->filterLeadFromSALeadMatchingLog($parameters['portingId'],$userIds);
			$alreadyPortedUserIds = array();
			foreach ($result as $key => $leadDetails) {
				$alreadyPortedUserIds[$leadDetails['leadid']] = 1;
			}

			foreach($emailIdToUserIdMapping as $emailId => $userId) {
				if($alreadyPortedUserIds[$userId] == 1){
					unset($emailIdToUserIdMapping[$emailId]);
				}
			}
			$emailIdToUserIdMapping = array_slice($emailIdToUserIdMapping, 0, $parameters['limit']);
			//_p($emailIdToUserIdMapping);die;

			$stateMapping = $portingModel->getStateForLDBUser($emailIdToUserIdMapping, array());
			foreach ($stateMapping as $userId => $stateData) {
				$stateMapping[$userId] = $stateData['Residence_State'];
			}
			//_p($stateMapping);die;
			error_log("PORTING_LOG total record : ".count($emailIdToUserIdMapping));

			// add these users in SALeadMatching log
			$matchingLogDataMapping = $portingModel->addUsersToSALeadMatcingLog($emailIdToUserIdMapping, $parameters['searchAgentId'], $parameters['clientId'],$parameters['streamId'],$parameters['subStreamId'],$parameters['profileType']);
			//_p($matchingLogDataMapping);die;

			$portingData = array();
			//$maxPortedId =0 ;
			$processedCount = 0;
			for($row = 2; $row <= $highestRow; ++$row) {
	            $email = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
				$userId = $emailIdToUserIdMapping[$email];
		    	if(!empty($userId) && $userId >0){
		    		$processedCount = $processedCount+1;

		    		$portingUserName = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
			    	$portingEmailId = $objWorksheet->getCellByColumnAndRow(4, $row)->getValue();
			    	$portingMobileNo = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
			    	$portingCity = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();

		    		$portingData = array(
						'StudentName'    => empty($portingUserName)?"N.A.":$portingUserName,
						'email'          => empty($portingEmailId)?"N.A.":$portingEmailId,
						'mobileNumber'   => empty($portingMobileNo)?"N.A.":$portingMobileNo,
						'courseId'       => '5',
						'cityId'         => '1',
						'stateId'        => '1',
						'utm_sitetarget' => '',
						'utm_term'       => empty($stateMapping[$userId])?"N.A.":$stateMapping[$userId],
						'utm_source'     => 'media',
						'utm_medium'     => 'shiksha',
						'utm_network'    => empty($portingCity)?"N.A.":$portingCity,
						'url'            => 'www.shiksha.com',
						'ip'             => '0.0.0.0',
						'utm_content'    => 'ManipalUniversity',
						'utm_campaign'   => 'shiksha'
			    		);
			    	
			    	$retArr = "";
			    	foreach($portingData as $key=>$value){
		                $retArr.= urlencode($key)."=".urlencode($value)."&";
		            }

		            $retArr = substr($retArr, 0,-1);
			    	$response = $this->_makeCurlRequest($parameters['portingUrl'] ,$retArr);
			    	
			    	$portingModel->updatePortingStatus($matchingLogDataMapping[$userId], $parameters['portingId'], $response, 'regular', serialize($portingData),'live');
			    	/*if($maxPortedId<$matchingLogDataMapping[$userId]){
			    		$maxPortedId = $matchingLogDataMapping[$userId];
			    	}*/
			    	$retArr = "";
		            $portingData = array();
		            error_log("PORTING_LOG last ported data- processed no :".$processedCount.", userId : ".$userId.", MatchingLogId : ".$matchingLogDataMapping[$userId]);
		    	}
	        }
	        //$portingModel->updateLastPortedId($parameters['portingId'], $maxPortedId);
	    }

	    $this->benchmark->mark('code_end');
	    error_log("PORTING_LOG Total time taken : ".print_r($this->benchmark->elapsed_time('code_start', 'code_end'),true));	
	}

	private function _makeCurlRequest($portingUrl,$str=""){
		if (ENVIRONMENT == 'production'){
            $url = $portingUrl;
        }else{
        	$url = "http://localshiksha.com/lms/Porting/portDataFromExcelTest";
        }

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL,            $url );
        curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 10);
        curl_setopt($ch, CURLOPT_TIMEOUT,        10);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true );
        curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
        curl_setopt($ch, CURLOPT_MAXREDIRS, 10);

        curl_setopt ($ch, CURLOPT_POST, 1);
        curl_setopt ($ch, CURLOPT_POSTFIELDS, $str);
        curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'apikey: 52f204f9-1577-46c9-bd59-7d98a6cc5cf2'
        ));

        $response = curl_exec($ch);
        curl_close($ch);
        return $response;
    }
    public function getInstituteDetails($client_id, $customField,$entity_id_text,$entity_name_text,$validation=false)
	{
		$this->load->model('listing/coursemodel');
		$this->load->model('lms/portingmodel');
		$client_institute_index_array = array();
		$instituteNameMapping = array();
		$allCoursesArray = array();
		$listingsDetails = $this->getNationalListingsDetailsForClient(array($client_id));
		foreach ($listingsDetails as $instituteId => $instituteDetails) {
			foreach ($instituteDetails['courseList'] as $courseId => $courseName) {
				$allCoursesArray[] = $courseId;
					//$instituteNameMapping[$courseId] = $courseName;
			}
		}
		if ($customField == "parent_institute" || $customField == "ldb_parent_institute")
		{
			$ParentDetails = $this->getParentInstitute($allCoursesArray);
			$institute_id = array();
			foreach ($ParentDetails as $key=>$value)
			{
				$institute_id[] = $value['parent_id'];
				$instituteNameMapping[$value['parent_id']] = $value['name']; 
					
			}
			$client_institute_index_array[$client_id] = implode(',',$institute_id);
		}
		else
		{
			$PrimaryDetails = $this->getPrimaryInstitute($allCoursesArray);
			$institute_id = array();
			foreach ($PrimaryDetails as $key=>$value)
			{
				$institute_id[] = $value['primary_id'];
				$instituteNameMapping[$value['primary_id']] = $value['name']; 	
			}
			$client_institute_index_array[$client_id] = implode(',',$institute_id);
		}

		$this->load->model('lms/portingmodel');
		$mappedFields                      = array();
		$mappedFields                      = $this->portingmodel->getCustomizedMappedFields($client_institute_index_array[$client_id], $customField,'single',$client_id);
		$dummyMappedFields                 = array();
		$dummyMappedFields                 = $this->portingmodel->getDummyCustomizedMappedFields($client_id,$customField);
		$data                              = array();
		$data['clientId']                  = $client_id;
		$data['clientToEntityMapping']     = $client_institute_index_array;
		$data['entityNameMapping']         = $instituteNameMapping;
		$data['entity_id_text']            = $entity_id_text;
		$data['entity_name_text']          = $entity_name_text;
		$data['customField']               = $customField;
		$data['mappedFields']              = $mappedFields;
		$data['dummyMappedFields']         = $dummyMappedFields;
		//_p($client_id);
		
		if ($validation){
			return $data;
		}

		$html = $this->load->view('lms/commonCustomizationDetails', $data, true);
		
		return $html;
	}
	public function getParentInstitute($allCoursesArray)
	{
		$data = $this->coursemodel->getInstituteDetails($allCoursesArray,'parent_id');
		return $data;
		
	}
	public function getPrimaryInstitute($allCoursesArray)
	{
		$data = $this->coursemodel->getInstituteDetails($allCoursesArray,'primary_id');
		return $data;
	}


	public function getMRCourses($courseObject,&$data)
	{
		global $managementStreamMR;
        global $mbaBaseCourse;
        global $engineeringtStreamMR;
        global $btechBaseCourse;
        global $fullTimeEdType;

		$courseArray = array();
		$courseArray['id'] = $courseObject->getId();
		$courseArray['name'] = $courseObject->getName();
		$education_type = $courseObject->getEducationType();
		$courseTypeInformationObject = $courseObject->getCourseTypeInformation();
		$entryCourseObj = $courseTypeInformationObject['entry_course'];
		if(!empty($education_type))
		{
			$education_type_id = $education_type->getId();
			if(!empty($entryCourseObj))
			{
				$courseHierarchies = $entryCourseObj->getHierarchies();
				$base_course = $entryCourseObj->getBaseCourse();
				foreach ($courseHierarchies as $hierarchy) 
				{
					$stream_id = $hierarchy['stream_id'];
					if(($stream_id == $managementStreamMR && $base_course == $mbaBaseCourse && $education_type_id == $fullTimeEdType) || ($stream_id == $engineeringtStreamMR && $base_course == $btechBaseCourse && $education_type_id == $fullTimeEdType))
					{
						$data['instituteList'][$instituteId]['courseList'][$courseArray['id']] = $courseArray['name'];
						break;

					}

				}
			}
		}
	}


	public function portResponseDataFromExcel(){
		return;
		//$this->validateCron();

		ini_set('memory_limit', '5000M');
		ini_set('max_execution_time', '1000000');
		$parameters = array(
			'filePath'   => '/home/naveen/Desktop/sheeulidatanew.xlsx',
			'portingUrl' => 'https://api.nopaperforms.com/dataPorting/207/shiksha'
		);

		$this->load->library('common/PHPExcel');
        $objPHPExcel    = new PHPExcel();
        $objReader = PHPExcel_IOFactory::createReader('Excel2007');
        $objReader->setReadDataOnly(true);
        $objPHPExcel  = $objReader->load($parameters['filePath']);
        $objWorksheet = $objPHPExcel->getActiveSheet();
        $highestRow    = $objWorksheet->getHighestRow();
        $highestColumn = $objWorksheet->getHighestColumn();
        $highestColumnIndex = PHPExcel_Cell::columnIndexFromString($highestColumn);


		for($row = 2; $row <= $highestRow; ++$row) {
            $name = $objWorksheet->getCellByColumnAndRow(0, $row)->getValue();
    		$email = $objWorksheet->getCellByColumnAndRow(1, $row)->getValue();
	    	$mobile = $objWorksheet->getCellByColumnAndRow(2, $row)->getValue();
	    	$state_city = $objWorksheet->getCellByColumnAndRow(3, $row)->getValue();
	    	$date = '29/03/2019';
	    	$time = '11:34:17';
	    	$state = $objWorksheet->getCellByColumnAndRow(6, $row)->getValue();
	    	$campus = $objWorksheet->getCellByColumnAndRow(7, $row)->getValue();
	    	$course = $objWorksheet->getCellByColumnAndRow(8, $row)->getValue();
	    	$source = $objWorksheet->getCellByColumnAndRow(9, $row)->getValue();
	    	$college_id = $objWorksheet->getCellByColumnAndRow(10, $row)->getValue();
	    	$secret_key = $objWorksheet->getCellByColumnAndRow(11, $row)->getValue();

    		$portingData = array(
				'name'    => $name,
				'email'   => $email,
				'mobile'   => $mobile,
				'state_city'       => $state_city,
				'date'         => $date,
				'time'        => $time,
				'state' => $state,
				'campus'       => $campus,
				'course'     => $course,
				'source'     => $source,
				'college_id'    => $college_id,
				'secret_key'  => $secret_key
	    		);
	    	
	    	$retArr = "";
	    	foreach($portingData as $key=>$value){
                $retArr.= urlencode($key)."=".urlencode($value)."&";
            }

            $retArr = substr($retArr, 0,-1);
            //echo $retArr;
	    	$response = $this->_makeCurlRequest($parameters['portingUrl'] ,$retArr);
	    	echo $email.'=='.$response.'<br/>';
error_log('naveen=='.$email.'=='.$response.'<br/>');
	    	
          
    	}
        
	    
	}
	public function getCommonCustomizationDetails($client_id, $selectedField){

		$this->config->load('lms/portingConfig');
		$customizedFieldMapping = $this->config->item('customizedFieldMapping');
		$type = $customizedFieldMapping[$selectedField]['type'];
		$customField = $customizedFieldMapping[$selectedField]['customField'];
		$entity_id_text = $customizedFieldMapping[$selectedField]['entity_id_text'];
		$entity_name_text = $customizedFieldMapping[$selectedField]['entity_name_text'];

		if($type=='stream' || $type=='basecourse'){
			$html = $this->getStreamBaseCourseDetails($client_id, $customField,$entity_id_text,$entity_name_text);
		}
		else if($type=='course'){
			$html = $this->getCourseDetails($client_id, $customField,$entity_id_text,$entity_name_text);
		}
		else if($type=='institute'){
			$html = $this->getInstituteDetails($client_id, $customField,$entity_id_text,$entity_name_text);
		}

		echo $html;
	}

	public function getStreamBaseCourseDetails($client_id, $customField,$entity_id_text,$entity_name_text,$validation=false){
		$this->load->model('listing/coursemodel');
		$this->load->model('lms/portingmodel');
		
		$client_course_index_array = array();

        $entityNameMapping = array();
        $entityIdArray = array();

        if($customField=='stream'){

        	$allStreams = $this->portingmodel->getAllStreams();
        	foreach ($allStreams as $stream){
	        	$entityIdArray[] = $stream['stream_id'];
	        	$entityNameMapping[$stream['stream_id']] = $stream['name'];
	        }
        }
        else if($customField=='base_course'){
        	$allBaseCourses = $this->portingmodel->getAllBaseCourses();
	        foreach ($allBaseCourses as $baseCourse){
	        	$entityIdArray[] = $baseCourse['base_course_id'];
	        	$entityNameMapping[$baseCourse['base_course_id']] = $baseCourse['name'];
	        }
        }

        $client_entity_index_array[$client_id] = implode(',',$entityIdArray);

		$mappedFields                      = array();
		$mappedFields                      = $this->portingmodel->getCustomizedMappedFields($client_entity_index_array[$client_id], $customField,'single',$client_id);
		$data                              = array();
		$data['entity_id_text']            = $entity_id_text;
		$data['entity_name_text']          = $entity_name_text;
		$data['clientId']                  = $client_id;
		$data['clientToEntityMapping']     = $client_entity_index_array;
		$data['entityNameMapping']         = $entityNameMapping;
		$data['customField']               = $customField;
		$data['mappedFields']              = $mappedFields;

		if($validation){
			return $data;
		}
		$html = $this->load->view('lms/commonCustomizationDetails', $data, true);
		
		return $html;
	}

	function updateLastPortedId()
	{	
		$this->_validateUser();
		$userId = $this->_validateuser[0]['userid'];
		if($userId == $this->portingAdminId){
			$portingId = $this->input->post('portingId');
			$last_ported_item_date = $this->input->post('date');
			$date_limit = date("Y-m-d",strtotime("-3 months"));
			if($last_ported_item_date >= $date_limit){
				$last_ported_item_date =  $last_ported_item_date." 00:00:00";
				$type = $this->input->post('type');
				$portingId = $this->input->post('portingId');
				$this->load->model('lms/portingmodel');
				$last_ported_id = $this->portingmodel->getPortingIdByDate($last_ported_item_date, $type);
				if($last_ported_id!= null){
					$this->portingmodel->updateLastPortedId($portingId,$last_ported_id);
					$this->portingmodel->makePortingTrackingEntry($portingId,$last_ported_id,$last_ported_item_date);
					echo "success";
				} 
			} else {
				echo "dateLimitExceeded";
			}
		} else {
			echo "accessDenied";
		}
	}

	private function getSubscriptionsForPorting($clientId){
		$this->load->model('sums/sumsmodel');
		$subscriptionDetails =	$this->sumsmodel->getAllSubscriptionsForListings($clientId);

		$response = array();
		foreach ($subscriptionDetails as $row)
		{

			if( $row['BaseProductId'] == GOLD_SL_LISTINGS_BASE_PRODUCT_ID || $row['BaseProductId'] == GOLD_ML_LISTINGS_BASE_PRODUCT_ID || ($row['BaseProdRemainingQuantity']>0 && $row['Status'] =='ACTIVE'  && $row['SubscrStatus'] =='ACTIVE' ) ){

				$response[$row['SubscriptionId']] = $row;
			}

		}
	
		return $response;
	}

	private function getLastTempLMSId(){
		$this->load->model('lms/portingmodel');
		$maxtempLMSId = $this->portingmodel->getLastTempLMSId();

		return $maxtempLMSId;
	}

	private function updateLastPortedIdForResponsePorting($maxtempLMSId){
		$this->load->model('lms/portingmodel');
		$maxtempLMSId = $this->portingmodel->updateLastPortedIdForResponsePorting($maxtempLMSId);		
	}

	public function uploadShikshaClientMapping($error){
		// $this->load->library('session');
		$this->_validateUser();
		$data = array();
		$data['error']= $error;
		$data['headerContentaarray'] = $this->_loadHeaderContent(PORTING_UPLOAD_CSV_TAB_ID);
		$this->load->view('shikshaClientMapping',$data);
		
	}

	public function uploadShikshaClientMap(){
		if ($_POST["downloadCSVFlag"] ==1){
			$this->downloadShikshaClientTemplate();
			return;
		}
		$tempFileName = $_FILES['fileToUpload']['tmp_name'][0];
		if(empty($tempFileName)){
			$this->uploadShikshaClientMapping('Please select a File');
			return;
		}

		$fileLines = file($tempFileName);
		$portingLib = $this->load->library('lms/PortingLib');
		$data = $this->validateUploadFile($fileLines);
		if (!empty($data['errorLog'])){
			$this->uploadShikshaClientMapping($data['errorLog']);
			return;
		}
		else{
			$clientId = $data['clientId'];
		  	$this->load->model('lms/portingmodel');
			foreach ($data['clientMapping'] as $entityType => $entityIdValueMapping) {
				$this->portingmodel->updateClientDataFromCSV($clientId,$entityType,array_keys($entityIdValueMapping),$entityIdValueMapping);
			}
		}
		$this->uploadShikshaClientMapping('CSV succesfully uploaded!!!!');
	}


	public function validateUploadFile($file_lines){
		$validInputFirstRow = 0;
		$row = 0; 
		foreach ($file_lines as $line) {
    		// Max 1000 rows allowed
    		$row++;
			$line = explode(',',$line,4);
    		if(empty(trim($line[0]))){
    			continue;
    		}
    		if ($row ==1000){
    			break;
    		}
    		if ($validInputFirstRow==0){
    			$validation = $this->checkValidFirstRow($line);
    			$errorLog .= $validation['error'];
    			$validInputFirstRow=1;
    		}
    		else{
    			$line[3] = trim($line[3]);
    			if ($line[3][0]=='"'){
					$line[3] = substr($line[3],1);
				}
				if ($line[3][strlen($line[3])-1] == '"'){
					$line[3] = substr($line[3],0,-1);
				}
				$clientId[$line[0]] = $line[0];
    			$clientMapping[$line[2]][$line[1]] = $line[3]; 
    		}

		}

		if (empty($clientMapping)){
			$errorLog .= "Empty csv file provided.";
		}
		
		if(count($clientId)>1){
			$errorLog .= "\n Client id should be the same in all rows";

		}

		$clientId = array_keys($clientId)[0];

		if(empty($errorLog)){
			$validateClient = $this->checkValidClient($clientId);
		}
		if ($validateClient != 1){
			$errorLog .= $validateClient;
		}

		if(empty($errorLog)){
			foreach ($clientMapping as $entityType => $value) {
				$errorLog .= $this->validateEntityType($clientId,$entityType,$value); 
			}
		}

		$returnArray = array(
							"clientId"=>$clientId,
							"clientMapping" => $clientMapping,
							"errorLog" => $errorLog
							);

		return $returnArray;
	}

	private function validateEntityType($clientId,$entityType,$mappings){
		$this->load->config('lms/portingConfig');
		$validEntityType = $this->config->item('validEntityType');
		if (!$validEntityType[trim($entityType)]){
			return $entityType." is invalid entity type \n";
		}
		
		switch ($validEntityType[$entityType]) {
			case 'course_level':
			case 'course_name':
			case 'mr_course':
			case 'ldb_course_level':
				$validEntityIds = $this->getCourseDetails($clientId,$entityType,"","",true);
				break;
			case 'parent_institute':
			case 'primary_institute':
			case 'ldb_parent_institute':
			case 'ldb_primary_institute':
				$validEntityIds = $this->getInstituteDetails($clientId,$entityType,"","",true);
				break;
			case 'stream':
			case 'base_course':
				$validEntityIds = $this->getStreamBaseCourseDetails($clientId,$entityType,"","",true);
				break;
			case 'oaf_paymentmode':
			case 'oaf_course':
			case 'oaf_gender':
				$oafValidIds = Modules::run('oafPorting/OafPorting/getEntityIdsForClient',$clientId,$entityType);
				$validEntityIds['entityNameMapping'] = array_fill_keys($oafValidIds,'1');
			default:
				break;
		}
		$portingLib = $this->load->library('lsm/PortingLib');
		$entityError = $portingLib->checkValidEntityIds($mappings,$validEntityIds['entityNameMapping']);
		if (!empty($entityError)){
			return $validEntityType[$entityType]."( ".substr($entityError,0,-2).") \n ";	
		}
	}

	public function checkValidClient($clientId){
		$this->load->model('lms/portingmodel');
		$clientDetails = $this->portingmodel->checkValidClient($clientId);
		$usergroup = $clientDetails[0]['usergroup'];
		if ($usergroup == "enterprise"){
			return 1;
		}
		else{
			return "Invalid Client Id provided.";
		}
	}

	public function downloadShikshaClientTemplate(){
		$fileName = "/var/www/html/shiksha/application/modules/User/lms/templates/shikshaClientMappingTemplate.csv";
		$file = fopen($fileName,"r");
		while(! feof($file))
	  	{
  			$data .= implode(",",(fgetcsv($file)))."\n";
  		}
  		$mime = 'text/x-csv';
  		header('Content-Type: "'.$mime.'"');
		header('Content-Disposition: attachment; filename="template.csv"');
		header("Content-Transfer-Encoding: binary");
		header('Expires: 0');
		header('Pragma: no-cache');
		header("Content-Length: ".strlen($data));
		echo $data;
	
	}

	public function startLeadPorting()
	{
		$this->validateCron();
		$this->startPorting("lead");
	}
}	
