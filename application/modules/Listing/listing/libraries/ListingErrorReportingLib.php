<?php

/*  Purpose : Send mail alert if any corrupted object found.
 *   
 */

class ListingErrorReportingLib {

	private $CI;
    private $errorEntries;
    private $solrErrorEntries = array();
    private $emailIds  =  array(
				0=>array(
					'to'=>'listingstech@shiksha.com'),
				); //Listing Team
    
    
	function __construct() {
		$this->CI =& get_instance();
	}
	
	function __destruct() {
		$this->sentErrorAlert();
	}
	
	/***
	 *  Purpose : Register an error to send alert.
	 * 
	 */
	
	public function registerToSendErrorAlert($listingTypeId,$listingType,$requestURL,$errorMessage,$scope = "",$fileName = "",$lineNo = "") {
		if($scope == 'Abroad Category Page'){
			$a = array_search('satech@shiksha.com',array_map(function($elm){return $elm['to'];},$this->emailIds));
			if(empty($a)){
				$this->emailIds[] = array('to'=>'satech@shiksha.com');
			}
		}
		$this->errorEntries [] = array(
									"Listing Type Id" 	=> 	$listingTypeId,				
									"Listing Type" 	=> 	$listingType,
									"URL"        	=>  $requestURL,
									"Error Message"	=>  $errorMessage,
									"Scope"			=>  $scope,
									"File Name"		=>  $fileName,
									"Line No"		=>  $lineNo
								);
	}
	
	/**
	 *  Purpose : send email alert if there is any registered error. 
	 * 
	*/
	
	public function sentErrorAlert() {
		// no need to send email for ajax calls
		if(!empty($this->errorEntries) && $_REQUEST['AJAX'] != 1 && $_REQUEST['SORTAJAX'] != 1 ) {
			$alerts_client = $this->CI->load->library('alerts_client');
			
			$displayData['errorEntries'] = $this->errorEntries;
			$subject = "Listing Error Alert!!!  ".date("Y-m-d H:i:s");
			$content = $this->CI->load->view('listing/listingErrorReportingEmailFormat',$displayData, true);
			foreach($this->emailIds as $email) {
				$flag = $alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email['to'],$subject,$content,"html","0000-00-00 00:00:00",'n',array(),$email['cc'],$email['bcc']);
			}
			$this->errorEntries = "";
		}
	}
	
	public function registerToSendSolrError($solrURL,$CurlErrorCode,$solrResponse) {
		
		$this->solrErrorEntries [md5($solrURL)] = array(
			'SolrURL' => $solrURL,
			'RequestURL' => $_SERVER['SCRIPT_URI'],
			'QueryParam' => $_SERVER['REQUEST_URI'],
			'CurlErrorCode' => $CurlErrorCode,
			'CurlReponse' => $solrResponse
		);

		// log solr error to appmonitor
		if(!empty($this->solrErrorEntries)) {
			
			try{
				// $this->CI->load->library('DbLibCommon');
				// $dbLibObj = DbLibCommon::getInstance('AppMonitor');
	   //          $dbHandle = $dbLibObj->getWritehandle();

	            global $rtr_class;
				global $rtr_method;
				global $rtr_module;
				$isMobile = 'no';
				if(($_COOKIE['ci_mobile'] == 'mobile') || ($GLOBALS['flag_mobile_user_agent'] == 'mobile')) {
					$isMobile = 'yes';
				}
				$this->CI->load->library('AppMonitor/AppMonitorLib');
				$appMonitorLib = new AppMonitorLib();
				$rtr_team = $appMonitorLib->getMappedTeam($rtr_module, $rtr_class);

				$this->CI->config->load('amqp');
				$this->CI->load->library("common/jobserver/JobManagerFactory");
				$jobManager = JobManagerFactory::getClientInstance();

				foreach($this->solrErrorEntries as $errorEntry) {

					$solrResponse = unserialize($errorEntry['CurlReponse']);

					$dataToLog = array(
						'team' => $rtr_team,
						'module' => $rtr_module,
						'controller' => $rtr_class,
						'method' => $rtr_method,
						'solrURL' => $errorEntry['SolrURL'],
						'url' => $errorEntry['RequestURL'],
						'mobile' => $isMobile,
						'stateCode' => $errorEntry['CurlErrorCode'],
						'message' => $solrResponse['error']['msg'],
						'solrResponse' => $errorEntry['CurlReponse'],
						'pageQueryParams' => $errorEntry['QueryParam'],
						'log_time' => date('Y-m-d H:i:s')
					);

					$dataToLog['logType'] = 'SolrErrors';
					$jobManager->addBackgroundJob("LogMonitoringData", $dataToLog);
					// $dbHandle->insert('solr_errors', $data);
				}
			}
			catch(Exception $e){
				error_log("JOBQException: ".$e->getMessage());
			}
		}
	}

	public function sendMyShortlistNotificationAlert($mailAlert){

		$alerts_client = $this->CI->load->library('alerts_client');
		$subject = $mailAlert['subject'];
		$content = $mailAlert['content'];
		foreach($this->emailIds as $email) {
			$flag = $alerts_client->externalQueueAdd("12",ADMIN_EMAIL,$email['to'],$subject,$content,"html","0000-00-00 00:00:00",'n',array(),$email['cc'],$email['bcc']);
		}

	}

}