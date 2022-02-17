<?php
/* vim: set expandtab tabstop=4 shiftwidth=4:
 * $Id:$
 */
/**
* Class and Function List:
* Function list:
* - index()
* - wsValidatecmsAdminLogin()
* - dumpmatchingArray()
* - runDeliveryCronASAP()
* - updateLeftoverStatus()
* - matchingLeads()
* - consumeCredits()
* - getDeduplicatedResult()
* - mergeArr()
* - insertInDb()
* - getAppendedList()
* - getFinalClients()
* - standard_deviation_population()
* - so()
* - sofrsms()
* - sofremail()
* - getProfiles()
* - getLocationAgents()
* - matchCourse()
* - match12()
* - matchGrad()
* - matchGradYears()
* - matchDegreePref()
* - matchMode()
* - matchUserFunds()
* - matchAge()
* - matchGender()
* - matchUGCourse()
* - matchUGInstitute()
* - matchXIIStream()
* - matchExp()
* - matchCurrentLocation()
* - matchCompetition()
* - matchPrefLocation()
* - matchSpecialization()
* - wsAddSearchAgent()
* - wsgetAllSearchAgents()
* - wsgetAllDataSearchAgents()
* - SADeliveryOptions()
* - SAAutoResponder()
* - SAMISReportGenerator()
* - wsupdateSearchAgentField()
* - wsgetAllContactDetails()
* - wsvalidateAgentName()
* - wsgetSearchAgent()
* - wsgetAllCreditToConsumedDataForSearchAgents()
* - wsfilterDefaulterSearchAgents()
* - wsgetCreditConsumed()
* - wschangeStatusAutoDownload()
* - wschangeStatusAutoResponderEmail()
* - wschangeStatusAutoResponderSMS()
* - wsUpdateSearchAgent()
* - wschangeStatusSearchAgent()
* - wschangeEmailFrequencySearchAgent()
* - wschangeSmsFrequencySearchAgent()
* - wsgetSADisplayData()
* - wsSearchAgentsAllDetails()
* - wsgetSearchAgentDetail()
* - downloadCSVForActivity()
* - createUserDataArray()
* - getColumnList()
* - datediff()
* Classes list:
* - SearchAgents_Server extends MX_Controller
*/

/**
 * SearchAgents_Server Class
 * @package Package SearchAgents
 * @subpackage SearchAgents Backend
 * @category LDB
 * @author Shiksha Team
 * @link https://www.shiksha.com
 */
class SearchAgents_Server extends MX_Controller {
	/**
	 * index API
	 * @access public
	 */
	public $killNotifInterval;
	public $fileForPid;
	function index() {
		$this->dbLibObj = DbLibCommon::getInstance('SearchAgents');
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->library('listingconfig');
		$this->load->library('searchagentsconfig');
		$config['functions']['SAMISReportGenerator'] = array(
			'function' => 'SearchAgents_Server.SAMISReportGenerator'
		);
		$config['functions']['wsAddSearchAgent'] = array(
			'function' => 'SearchAgents_Server.wsAddSearchAgent'
		);
		$config['functions']['wsgetAllSearchAgents'] = array(
			'function' => 'SearchAgents_Server.wsgetAllSearchAgents'
		);
		$config['functions']['wsgetAllDataSearchAgents'] = array(
			'function' => 'SearchAgents_Server.wsgetAllDataSearchAgents'
		);
		$config['functions']['matchingLeads'] = array(
			'function' => 'SearchAgents_Server.matchingLeads'
		);
		$config['functions']['SADeliveryOptions'] = array(
			'function' => 'SearchAgents_Server.SADeliveryOptions'
		);
		$config['functions']['SAAutoResponder'] = array(
			'function' => 'SearchAgents_Server.SAAutoResponder'
		);
		/* SEARCH AGENT FORM CRUD START */
		$config['functions']['wsupdateSearchAgentField'] = array(
			'function' => 'SearchAgents_Server.wsupdateSearchAgentField'
		);
		$config['functions']['wsupdateSearchAgentDisplayData'] = array(
			'function' => 'SearchAgents_Server.wsupdateSearchAgentDisplayData'
		);
		$config['functions']['wsviewSearchAgentField'] = array(
			'function' => 'SearchAgents_Server.wsviewSearchAgentField'
		);
		$config['functions']['wschangeStatusSearchAgent'] = array(
			'function' => 'SearchAgents_Server.wschangeStatusSearchAgent'
		);
		$config['functions']['wsrunSearchAgent'] = array(
			'function' => 'SearchAgents_Server.wsrunSearchAgent'
		);
		$config['functions']['wsgetAllContactDetails'] = array(
			'function' => 'SearchAgents_Server.wsgetAllContactDetails'
		);
		$config['functions']['wsvalidateAgentName'] = array(
			'function' => 'SearchAgents_Server.wsvalidateAgentName'
		);
		$config['functions']['wsgetSADisplayData'] = array(
			'function' => 'SearchAgents_Server.wsgetSADisplayData'
		);
		$config['functions']['wsgetSearchAgent'] = array(
			'function' => 'SearchAgents_Server.wsgetSearchAgent'
		);
		$config['functions']['wsgetCreditConsumed'] = array(
			'function' => 'SearchAgents_Server.wsgetCreditConsumed'
		);
		$config['functions']['wsfilterDefaulterSearchAgents'] = array(
			'function' => 'SearchAgents_Server.wsfilterDefaulterSearchAgents'
		);
		$config['functions']['wsgetAllCreditToConsumedDataForSearchAgents'] = array(
			'function' => 'SearchAgents_Server.wsgetAllCreditToConsumedDataForSearchAgents'
		);
		$config['functions']['wsUpdateSearchAgent'] = array(
			'function' => 'SearchAgents_Server.wsUpdateSearchAgent'
		);
		$config['functions']['wschangeStatusAutoResponderSMS'] = array(
			'function' => 'SearchAgents_Server.wschangeStatusAutoResponderSMS'
		);
		$config['functions']['wschangeStatusAutoResponderEmail'] = array(
			'function' => 'SearchAgents_Server.wschangeStatusAutoResponderEmail'
		);
		$config['functions']['wschangeStatusAutoDownload'] = array(
			'function' => 'SearchAgents_Server.wschangeStatusAutoDownload'
		);
		$config['functions']['wschangeEmailFrequencySearchAgent'] = array(
			'function' => 'SearchAgents_Server.wschangeEmailFrequencySearchAgent'
		);
		$config['functions']['wschangeSmsFrequencySearchAgent'] = array(
			'function' => 'SearchAgents_Server.wschangeSmsFrequencySearchAgent'
		);
		$config['functions']['wsSearchAgentsAllDetails'] = array(
			'function' => 'SearchAgents_Server.wsSearchAgentsAllDetails'
		);
		$config['functions']['wsgetSearchAgentDetail'] = array(
			'function' => 'SearchAgents_Server.wsgetSearchAgentDetail'
		);
		$config['functions']['wsValidatecmsAdminLogin'] = array(
			'function' => 'SearchAgents_Server.wsValidatecmsAdminLogin'
		);
		$config['functions']['sgetAllMultiValuesSearchAgent'] = array(
		  'function' => 'SearchAgents_Server.sgetAllMultiValuesSearchAgent'
		  );
		/* SEARCH AGENT FORM CRUD END */
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		
		return $this->$method($args[1]);
	}



	function sgetAllMultiValuesSearchAgent($request)
	{
	  $parameters = $request->output_parameters();
	  $appId = $parameters['0'];
	  $sa_id = $parameters['1'];
	  $SADisplayArray = array();
	  error_log('SA dump:: Input Array ' . print_r($parameters, true));
	  $this->load->model('search_agent_main_model');
	  $dbHandle = $this->search_agent_main_model->getDbHandle();
	  if (!$dbHandle) {
	    log_message('error', 'can not create db handle');
	  }
	  $queryCmd = "select smvc.keyname,tcsm.SpecializationName,tcsm.CourseName,tcsm.SpecializationId from
	  SAMultiValuedSearchCriteria smvc, tCourseSpecializationMapping tcsm where
	  smvc.value = tcsm.SpecializationId and smvc.keyname in ('Specialization','desiredcourse') and
	  smvc.searchAlertId='".$sa_id."' and scope ='india' and smvc.is_active='live'";
	  error_log('SA dump:: Input Array ' . $queryCmd);
	  $query = $dbHandle->query($queryCmd);
	  foreach ($query->result() as $row) {
	    array_push($SADisplayArray, array(
	      array(
		'keyname' => $row->keyname,
		'SpecializationName' => $row->SpecializationName,
		'CourseName' => $row->CourseName,
		'SpecializationId' =>$row->SpecializationId
		) ,
		'struct'
		)); //close array_push
	  }
	  $response = array(
	    $SADisplayArray,
	    'struct'
	    );
	    error_log('SA dump:: Input Array ' . print_r($response, true));
	    return $this->xmlrpc->send_response($response);
	}
	
	function wsValidatecmsAdminLogin($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$password = $parameters['1'];
		$emailid = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle();
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$sql = "SELECT * from tuser where email = ? AND password = ? and usergroup='cms'";
		$query = $dbHandle->query($sql, array($emailid, $password));
		error_log(" ZSE " . $query->num_rows());
		if ($query->num_rows() == 1) {
			$result = 'true';
		} else {
			$result = 'false';
		}
		$response = array(
			'status' => $result
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function dumpmatchingArray($arr) {
		$str = array();
		$agents = '';
		$client_id = '';
		foreach ($arr as $key => $b) {
			$str[] = $b['agentid'];
			$client_id = $b['clientid'];
		}
		$agents = implode(",", $str);
		echo "\n" . $agents . " Agents are matched." . "\n";
	}
	function runDeliveryCronASAP() {
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		$appId = 1;
		$emailFreq = 'asap';
		$SAClientObj->SADeliveryOptions($appId, $emailFreq);
		$SAClientObj->SAAutoResponder();
	}
	
	function updateLeftoverStatus()
	{
		$this->dbLibObj = DbLibCommon::getInstance('SearchAgents');
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$shikshaDbHandle = $this->_loadDatabaseHandle('write');
		
		$flag = TRUE;
		while ($flag) {
			$sql = "select status, lastprocessedid from  `SALeadAllocationCron` where process = 'ALLOCATION'";
			$query = $shikshaDbHandle->query($sql);
			if ($query->num_rows() == 1) {
				$row = $query->result_array();
				if ($row[0]['status'] == 'ON') {
					echo "\nERROR: Lead Allocation cron already running so we can not update SALeadsLeftoverStatus @ " . date('Y-m-d h-i-s') . "\n";
					sleep(1);
				} else {
					
					$sql1 = "UPDATE SALeadsLeftoverStatus SET leads_sent_today_email = '0',leads_sent_today_sms = '0'";
                    echo "\n $sql1 \n";
					$query = $shikshaDbHandle->query($sql1);
                    $sql2 = "update `SALeadsLeftoverStatus` a, SASearchAgent b set a.leftover_leads = a.leftover_leads + (b.leads_daily_limit- a.leads_sent_today), a.leads_sent_today = 0 ,
                    a.last_sent_time = now() where a.searchagentid = b.searchagentid and b.is_active = 'live'";
                    echo "\n $sql2 \n";
					$shikshaDbHandle->query($sql2);
					
					/*******************************************************
					 * Reset leftover status for agents which do not have
					 * sufficient credits available
					 * *****************************************************/
					$agentsWithInsufficientCredits = $this->_getSearchAgentsWithInsufficientCredits();
					if(count($agentsWithInsufficientCredits) > 0) {
						$sql = "UPDATE SALeadsLeftoverStatus SET leftover_leads = '0' WHERE searchagentid IN (".implode(',',$agentsWithInsufficientCredits).")";
						$shikshaDbHandle->query($sql);
					}
				
					$flag = FALSE;
				}
			}
		}
	}
	
	/**
	 * Get search agents which do not have sufficient credits
	 * for a lead to be allocated to them
	 */ 
	private function _getSearchAgentsWithInsufficientCredits()
	{ 
        ini_set('display_errors',1);
        echo "m into bug 1 \n";
		$this->dbLibObj = DbLibCommon::getInstance('SearchAgents');
		$shikshaDbHandle = $this->_loadDatabaseHandle('write');
		echo "m into bug 2 \n";
		$sql = "SELECT searchagentid,clientid,price_per_lead FROM SASearchAgent WHERE is_active = 'live'";
		$query = $shikshaDbHandle->query($sql);
		$results = $query->result_array();
		echo "m into bug 3 \n";
		$clientIds = array();
		$agentPriceMapping = array();
		$agentClientMapping = array();
		
		foreach($results as $result) {
			$clientIds[] = $result['clientid'];
			$agentPriceMapping[$result['searchagentid']] = intval($result['price_per_lead']);
			$agentClientMapping[$result['searchagentid']] = $result['clientid'];
		}
		
		$clientIds = array_unique($clientIds);
		echo "m into bug 4 \n";
		$sumsDBHandle = $this->_loadDatabaseHandle('read','SUMS');
		echo "m into bug 5 \n";
		$sql = "SELECT S.ClientUserId,SPM.BaseProdRemainingQuantity
				FROM Subscription_Product_Mapping SPM
				INNER JOIN Subscription S ON S.SubscriptionId = SPM.SubscriptionID
				INNER JOIN Base_Products B ON SPM.BaseProductId=B.BaseProductId
				WHERE S.ClientUserId IN (".implode(',',$clientIds).")
				AND S.SubscrStatus='ACTIVE'
				AND SPM.BaseProdRemainingQuantity > 0
				AND DATE(SPM.SubscriptionEndDate) >= curdate()
				AND DATE(SPM.SubscriptionStartDate) <= curdate()
				AND SPM.Status='ACTIVE'
				AND B.BaseProdCategory = 'Lead-Search'";
		$query = $sumsDBHandle->query($sql);
		$results = $query->result_array();
		$clientCredits = array();
        echo "m into bug 6 \n";
		foreach($results as $result) {
			if(intval($result['BaseProdRemainingQuantity']) > intval($clientCredits[$result['ClientUserId']])) {
				$clientCredits[$result['ClientUserId']] = $result['BaseProdRemainingQuantity'];
			}
		}
		
		$agentsWithInsufficientCredits = array();
		foreach($agentClientMapping as $agentId => $clientId) {
			$minCreditsRequired = (int) $agentPriceMapping[$agentId];
			$maxCreditsAvailable = (int) $clientCredits[$clientId];
			
			if($maxCreditsAvailable < $minCreditsRequired) {
				$agentsWithInsufficientCredits[] = $agentId;	
			}
		}
	    echo "m into bug 7 \n";
		$this->dbLibObj = DbLibCommon::getInstance('SearchAgents');
		$shikshaDbHandle = $this->_loadDatabaseHandle('write');
        echo "m into bug 8 \n";
	
		return $agentsWithInsufficientCredits;
	}
	
function mtfl() {
	echo "===========================TIMETAKENSTART=========================<br/><br/><br/>";
	$this->benchmark->mark('code_start');
}

function dumpTimeTaken($time,$msg = "") {
	
	$this->benchmark->mark('code_end');
	echo "<br/>";
	echo $this->benchmark->elapsed_time('code_start', 'code_end');
	echo "<br/>";
	echo "===========================TIMETAKENENDFOR$msg =========================<br/><br/><br/>";
}

function oldLeadsMatching() {
	
	$this->matchingLeads('old');					
}
    
function matchingLeads($type='fresh') {

global $excludedClientsForResponseLead;

ini_set('display_errors',1);
ini_set('memory_limit','2048M');
$this->dbLibObj = DbLibCommon::getInstance('SearchAgents');

echo "\n ============= MATCH LEAD START ============= \n";
/* pick all leads which have come after last process id in db */

try {
	
	$time = $this->mtfl();
	$profiles = $this->getProfiles($type);	
	
	$this->dumpTimeTaken($time,"get user profile");
	/* Function to check db object */
	$dbHandle = $this->_loadDatabaseHandle('write');
	
	foreach($profiles as $typeLead => $leadProfiles) {
		
		foreach ($leadProfiles as $k => $v) {
			
			$this->benchmark->mark('code_start');
			
			$arr = array();
			$arr = (array)json_decode($v);
			
			$result = array();
			
			$isResponseLead = FALSE;
			foreach($arr as $leadUserId => $leadUserDetails) {
				
				if($leadUserDetails->isResponseLead == 'YES') {
					$isResponseLead = TRUE;
				}
				
			}
			
			/**
			 * If response lead, do relaxed matching
			 */ 
			if($isResponseLead) {
				
				echo "\n Match course \n";
				$time = $this->mtfl();
				$result = (array)$this->matchCourse($arr);
				$this->dumpTimeTaken($time," matchCourse");
				$time = $this->mtfl();
				
				$profileArr = array_keys($arr);
				$profileId = $profileArr[0];
			
				echo "\n Match Location Pref \n";
				$result = $this->mergeArr($result, $this->getLocationAgents($arr));
				$this->dumpTimeTaken($time," getLocationAgents");
				$time = $this->mtfl();
				
				/**
				 * Exclude clients who don't want response leads
				 */ 
				$resultAfterExclusion = array();
				foreach($result as $inResult) {
					if(!in_array($inResult['clientid'],$excludedClientsForResponseLead)) {
						$resultAfterExclusion[] = $inResult;
					}
				}
				
				$result = $resultAfterExclusion;
				
			} else {	
				
			   /**
				 * Proper lead, do full matching
				 */				 
				echo "\n Match course \n";
				$time = $this->mtfl();
				$result = $this->mergeArr((array)$this->matchCourse($arr) , (array)$this->matchAge($arr));
				$this->dumpTimeTaken($time," match course and userage");
				
				$time = $this->mtfl();
				/* Match Funds */
				echo "\n Match user funds\n";
				$result = $this->mergeArr($result, $this->matchUserFunds($arr));
				$this->dumpTimeTaken($time," matchUserFunds");
				
				$time = $this->mtfl();					
				echo "\n Match user passport\n";
				$result = $this->mergeArr($result, $this->matchPassport($arr));
				$this->dumpTimeTaken($time," matchPassport");
				
				//$time = $this->mtfl();
				//echo "\n Match user budget\n";
				//$result = $this->mergeArr($result, $this->matchBudget($arr));
				//$this->dumpTimeTaken($time," matchBudget");
				
				$time = $this->mtfl();
				echo "\n Match user plan to start\n";
				$result = $this->mergeArr($result, $this->matchPlanToStart($arr));
				$this->dumpTimeTaken($time," matchPlanToStart");
				
				$time = $this->mtfl();
				echo "\n Match abroad specialization\n";
				$result = $this->mergeArr($result, $this->matchAbroadSpecialization($arr));
				$this->dumpTimeTaken($time," matchAbroadSpecialization");
				
				$time = $this->mtfl();
				echo "\n Match gender \n";
				$result = $this->mergeArr($result, $this->matchGender($arr));
				$this->dumpTimeTaken($time," matchGender");
				
				$time = $this->mtfl();
				echo "\n Match ug course \n";
				$result = $this->mergeArr($result, $this->matchUGCourse($arr));
				$this->dumpTimeTaken($time," matchUGCourse");
				
				$time = $this->mtfl();
				echo "\n Match ug institute \n";
				$result = $this->mergeArr($result, $this->matchUGInstitute($arr));
				$this->dumpTimeTaken($time," matchUGInstitute");
				
				$time = $this->mtfl();
				echo "\n Match degree prefs \n";
				$result = $this->mergeArr($result, $this->matchDegreePref($arr));
				$this->dumpTimeTaken($time," matchDegreePref");
				
				$time = $this->mtfl();
				echo "\n Match matchModePartTime/matchModeFullTime \n";
				$result = $this->mergeArr($result, $this->matchMode($arr));
				$this->dumpTimeTaken($time," matchMode");
				
				$time = $this->mtfl();
				echo "\n Match exp \n";
				$result = $this->mergeArr($result, $this->matchExp($arr));
				$this->dumpTimeTaken($time," matchExp");
				
				$time = $this->mtfl();
				$profileArr = array_keys($arr);
				$profileId = $profileArr[0];
				/* matching competitive exams */
				echo "\n FINAL SCORES MATCHING COMPETITIVE EXAM ARRAY\n";
				$result = $this->mergeArr($result, $this->matchCompetition($profileId));
				$this->dumpTimeTaken($time," matchCompetition");
				
				$time = $this->mtfl();					
				echo "\n Match Location Pref \n";
				$result = $this->mergeArr($result, $this->getLocationAgents($arr));
				$this->dumpTimeTaken($time," getLocationAgents");
				
				//$time = $this->mtfl();					
				//echo "\n Match 12th Marks\n";
				//echo "commented code bug the found test prep"; 
				//$result = $this->mergeArr($result, $this->match12($arr));
				//$this->dumpTimeTaken($time," match12");
				
				$time = $this->mtfl();					
				echo "\n Match 12th YEAR\n";		
				$result = $this->mergeArr($result, $this->match12Year($arr));
				$this->dumpTimeTaken($time," match12Year");
				
				$time = $this->mtfl();
				echo "\n Match grad Marks \n";
				$result = $this->mergeArr($result, $this->matchGrad($arr));
				$this->dumpTimeTaken($time," matchGrad");
				
				$time = $this->mtfl();
				echo "\n Match grad Years \n";
				$result = $this->mergeArr($result, $this->matchGradYears($arr));
				$this->dumpTimeTaken($time," matchGradYears");
			}
						
			echo "\n Matching Happened Now Finally result is \n" . print_r($result, true);
			/* DUMP RESULT INTO DB */
			foreach ($arr as $k11 => $v11) {
				$profile_Id = $k11;
			}
			
			$time = $this->mtfl();
			echo "after_matiching_happened1 \n"; 
			$this->wsAddDumpMatchingLog($profile_Id,$result);
			echo "after_matiching_happened2 \n";
			$this->dumpTimeTaken($time," wsAddDumpMatchingLog");
			_p($result);
			
			$time = $this->mtfl();
			$result = $this->_removePortingAgents($result);
			echo "after_matiching_happened3 \n";
			$this->dumpTimeTaken($time," _removePortingAgents");
			_p($result);
			
			$time = $this->mtfl();				
			$result = $this->_removeAgentsWithInsufficientCredits($result);
			echo "after_matiching_happened4 \n";
			$this->dumpTimeTaken($time," _removeAgentsWithInsufficientCredits");
			_p($result);
			
			$time = $this->mtfl();
			$result = $this->_removeAlreadyAllocatedAgents($result,$profile_Id);
			echo "after_matiching_happened5 \n";
			$this->dumpTimeTaken($time," _removeAlreadyAllocatedAgents");
			_p($result);
			
			if($typeLead == 'oldActive') {
				$time = $this->mtfl();
				$result = $this->_removeAgentsNotOptedOldLeads($result);
				_p($result);
				$this->dumpTimeTaken($time," _removeAgentsNotOptedOldLeads");	
			}
			
			echo "after_matiching_happened6 \n";
			if (count($result) > 0) {
				$time = $this->mtfl();
				echo "after_matiching_happened7 \n";
				// lead allocation
				$finalAgents = $this->getFinalClients($result, $profileId);
				echo "after_matiching_happened8 \n";
				_p($finalAgents);
				
				$this->dumpTimeTaken($time," getFinalClients");				
				// DB insert and deductCredits
				if (count($finalAgents) > 0) {
				$time = $this->mtfl();	
				$finalAgents = $this->getDeduplicatedResult($finalAgents);
				$this->dumpTimeTaken($time," getDeduplicatedResult");				
								
				if (count($finalAgents) > 0) {
					
					echo " \n \n FinalAgents are as \n\n" . print_r($finalAgents, true);
					
					$queryCmd="select isNDNC from  `tuserflag` where userid = ?";
				    echo  $queryCmd;
				    
					$query = $dbHandle->query($queryCmd, array($profileId));
					foreach($query->result_array() as $row){
						$isNDNC=$row['isNDNC'];
					 }
					  
					echo $isNDNC;
					$counter=0;
					foreach($finalAgents as $temp){
					 $counter++;
					}

					for($ik=0;$ik<$counter;$ik++){
					  
					  if($isNDNC=='NO' && $finalAgents[$ik]['as']=='YES'){
						$finalAgents[$ik]['as']='YES';
					  }else{
						$finalAgents[$ik]['as']='NO';
					  }
				  
					}
					
					echo "finalAgents after ndnc are as " . print_r($finalAgents, true);
                    $time = $this->mtfl();
					$this->insertInDb($profileId, $finalAgents);						
					$this->dumpTimeTaken($time," insertInDb");
					
					$time = $this->mtfl();
					$this->runDeliveryCronASAP();
					$this->dumpTimeTaken($time," runDeliveryCronASAP");
					$time = $this->mtfl();
					$this->consumeCredits($finalAgents, $profileId);
					$this->dumpTimeTaken($time," consumeCredits");
					
					}
				}
			}
			
			
		    if($profileId>0) {
				$dbHandle->query("update `tUserPref` set is_processed = 'yes' where userid = ?", array($profileId));
		    }
			
			if(!$dbHandle || !isset($dbHandle) || $dbHandle->affected_rows() < 1){
					echo "R RAJKUMAR UPDATE FAILED.\n";
			} else {
					echo "R RAJKUMAR UPDATE DONE.\n";
			}
			
			unset($result);
			$this->benchmark->mark('code_end');
			echo "MATCHINGLEAD #singleleadtotal time".$this->benchmark->elapsed_time('code_start', 'code_end')."<br/>";
		}
	}
	
	$dbHandle->query("update `SALeadAllocationCron` set status = 'OFF' where  process = 'ALLOCATION'");
	echo "\n Mamtching lead end update \n";
	
} catch(Exception $e) {
	echo("\n ERROR OCCOURED.\n" . $e);
}

echo "\n ============= MATCH LEAD END ============= \n";

}

    private function _removePortingAgents($sa_array){
        
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'Can not create db handle');
		}
		
		$agents = array();
        $returnArr = array();
        foreach ($sa_array as $result) {
            $agents[] = $result['agentid'];
        }
        if(count($agents) >0){
            $joinedAgents = implode(",",$agents);
            
            $query = $dbHandle->query("select searchagentid from `SASearchAgent` where searchagentid in ($joinedAgents) and deliveryMethod = 'normal'");
            if ($query->num_rows() > 0) {
                $matchingAgents = array();
                foreach ($query->result_array() as $row) {
                    $matchingAgents[] = $row['searchagentid'];
                }
            }

            foreach ($sa_array as $result) {
                if(in_array($result['agentid'],$matchingAgents)){
                    $returnArr[] = $result;
                }
            }
        }
        return $returnArr;
    }
	
    private function _removeAgentsWithInsufficientCredits($sa_array)
	{    
		$agentsWithInsufficientCredits = $this->_getSearchAgentsWithInsufficientCredits();
		$mapForAgentsWithInsufficientCredits = array();
		foreach($agentsWithInsufficientCredits as $agentId) {
			$mapForAgentsWithInsufficientCredits[$agentId] = TRUE;
		}
		
        $returnArr = array();
        foreach ($sa_array as $result) {
            if(!$mapForAgentsWithInsufficientCredits[$result['agentid']]) {
				$returnArr[] = $result;
			}
        }
        return $returnArr;
    }
	
	private function _removeAlreadyAllocatedAgents($sa_array,$leadId)
	{
		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$sql = "SELECT b.clientid
				FROM SALeadAllocation a,SASearchAgent b
				WHERE a.agentid = b.searchagentid
				AND a.userid = ?";
		$query = $dbHandle->query($sql, array($leadId));
		
		$clientsAlreadyAllocated = array();
		foreach($query->result_array() as $row) {
			$clientsAlreadyAllocated[$row['clientid']] = TRUE;
		}
		
		// remove already viewed client		
		$sql = "SELECT ClientId from LDBLeadContactedTracking ".
		       "WHERE LDBLeadContactedTracking.UserId=$leadId ".
		       "AND ContactType='view' and CreditConsumed>0";
		       
		$query = $dbHandle->query($sql);
		foreach($query->result_array() as $row) {
			$clientsAlreadyAllocated[$row['ClientId']] = TRUE;
		}
		
        $returnArr = array();
        foreach ($sa_array as $result) {
            if(!$clientsAlreadyAllocated[$result['clientid']]) {
				$returnArr[] = $result;
			}
        }
	
        return $returnArr;
    }
	
	private function _removeAgentsNotOptedOldLeads($sa_array)
	{
		$dbHandle = $this->_loadDatabaseHandle('write');
		
		$agentIds = array();
		foreach ($sa_array as $result) {
            $agentIds[] = $result['agentid'];
        }
		
		$agentsOptedForOldLeads = array();
		
		if(count($agentIds) > 0) {
			$sql = "SELECT searchagentid
					FROM SASearchAgentBooleanCriteria
					WHERE includeActiveUsers = 'yes'
					AND searchagentid in (".implode(',',$agentIds).")";
			$query = $dbHandle->query($sql);
			
			foreach($query->result_array() as $row) {
				$agentsOptedForOldLeads[$row['searchagentid']] = TRUE;
			}
		}
		
        $returnArr = array();
        foreach ($sa_array as $result) {
            if($agentsOptedForOldLeads[$result['agentid']]) {
				$returnArr[] = $result;
			}
        }
	
        return $returnArr;
    }	
	
	private function _getSearchAgentsHavingLeadAsResponse($finalResult, $profileId)
	{
		$dbHandle = $this->_loadDatabaseHandle('write');
		$searchAgentIds = array();
		foreach ($finalResult as $k => $v) {
			$searchAgentIds[] = $v['id'];
		}
		
		$sql =  "SELECT s.searchagentid ".
				"FROM tempLMSTable t ".
				"INNER JOIN listings_main l ON (l.listing_type = t.listing_type AND l.listing_type_id = t.listing_type_id AND t.listing_subscription_type='paid')  ".
				"INNER JOIN SASearchAgent s ON (s.clientid = l.username) ".
				"AND s.searchagentid IN (".implode(',',$searchAgentIds).") ".
				"AND t.userId = ? ".
                                "AND t.listing_subscription_type='paid' ".
				"AND l.status = 'live'";
		$query = $dbHandle->query($sql, array($profileId));
		
		$searchAgentsHavingLeadAsResponse = array();
		foreach($query->result_array() as $result) {
			$searchAgentsHavingLeadAsResponse[] = $result['searchagentid'];
		}
		
		return $searchAgentsHavingLeadAsResponse;
	}
	
	function consumeCredits($finalResult, $profileId) {
		echo "\n consumeCredits API call START \n";
		$this->load->library('LDB_Client');
		$ldbObj = new LDB_Client();

		$dbHandle = $this->_loadDatabaseHandle('write');
		$joined = "";
		foreach ($finalResult as $k => $v) {
			$joined.= $v['id'] . ",";
		}
		$joined = substr($joined, 0, -1);
		
		$searchAgentsHavingLeadAsResponse = $this->_getSearchAgentsHavingLeadAsResponse($finalResult, $profileId);
		
		echo $sql = "select price_per_lead, searchagentid,clientid  from `SASearchAgent` where searchagentid in ($joined) and is_active='live'";
		echo "\n";
		$query = $dbHandle->query($sql);
		$creditArray = array();
		$clientArray = array();
		foreach ($query->result_array() as $row) {
			$creditArray[$row['searchagentid']] = $row['price_per_lead'];
			$clientArray[$row['searchagentid']] = $row['clientid'];
		}
		echo $sql = "SELECT DISTINCT c.actionType, c.deductcredit
			FROM `tUserPref` a, tCourseGrouping b, tGroupCreditDeductionPolicy c
			WHERE a.UserId = ?
			AND a.DesiredCourse = b.courseId
			AND b.groupId = c.groupId
			AND c.status = 'live'
			AND a.Status = 'live'
			AND b.status = 'live'
			AND (a.extraflag != 'testprep' OR a.extraflag is NULL)
			UNION
			SELECT DISTINCT d.actionType, d.deductcredit
			FROM `tUserPref` a, tUserPref_testprep_mapping b, tCourseGrouping c, tGroupCreditDeductionPolicy d
			WHERE a.UserId = ?
			AND a.prefid = b.prefid
			AND b.blogid = c.courseId
			AND c.groupId = d.groupId
			AND c.status = 'live'
			AND a.Status = 'live'
			AND b.status = 'live'
			AND d.status = 'live'
			AND a.extraflag = 'testprep'";
		$query = $dbHandle->query($sql, array($profileId, $profileId));
		foreach ($query->result_array() as $row) {
			if ($row['actionType'] == 'sms') $SMS_DED = $row['deductcredit'];
			if ($row['actionType'] == 'email') $EMAIL_DED = $row['deductcredit'];
		}
		foreach ($finalResult as $k => $v) {
			
			if(in_array($v['id'],$searchAgentsHavingLeadAsResponse)) {
				if ($v['ad'] == 'YES') {
					$ldbObj->sUpdateContactViewed(12, $clientArray[$v['id']], $profileId, 'view', 0, 0, 'SA');
					if ($v['as'] == 'YES') $ldbObj->sUpdateContactViewed(12, $clientArray[$v['id']], $profileId, 'sms', 0, 0, 'SA');
					if ($v['ae'] == 'YES') $ldbObj->sUpdateContactViewed(12, $clientArray[$v['id']], $profileId, 'email', 0, 0, 'SA');
				} else {
					if ($v['as'] == 'YES') $ldbObj->sUpdateContactViewed(12, $clientArray[$v['id']], $profileId, 'sms', 0, 0, 'SA');
					if ($v['ae'] == 'YES') $ldbObj->sUpdateContactViewed(12, $clientArray[$v['id']], $profileId, 'email', 0, 0, 'SA');
				}
			}
			else {
				if ($v['ad'] == 'YES') {
					$this->wsgetCreditConsumed($v['id'], $profileId, $creditArray[$v['id']], 'view');
					if ($v['as'] == 'YES') $this->wsgetCreditConsumed($v['id'], $profileId, 0, 'sms');
					if ($v['ae'] == 'YES') $this->wsgetCreditConsumed($v['id'], $profileId, 0, 'email');
				} else {
					if ($v['as'] == 'YES') $this->wsgetCreditConsumed($v['id'], $profileId, $SMS_DED, 'sms');
					if ($v['ae'] == 'YES') $this->wsgetCreditConsumed($v['id'], $profileId, $EMAIL_DED, 'email');
				}
			}
		}
		echo "\n consumeCredits API call END \n";
	}
	function getDeduplicatedResult($agents) {
		echo "\n getDeduplicatedResult API call START \n";
		$joined = "";
		foreach ($agents as $k => $v) {
			$joined.= $v['id'] . ",";
		}
		$joined = substr($joined, 0, -1);
		$retArr = array();
		echo $sql = "SELECT a.searchagentid FROM  SALeadsLeftoverStatus b, SASearchAgent a
	        left join SASearchAgentAutoResponder_email c
	        on a.searchagentid = c.searchagentid and c.is_active = 'live'  left join SASearchAgentAutoResponder_sms d
	        on a.searchagentid = d.searchagentid and d.is_active = 'live'  WHERE a.searchagentid = b.searchagentid
	        AND (IFNULL( a.flag_auto_download, 'history' ) = 'history'
	        or (IFNULL( a.leads_daily_limit, 0 ) + IFNULL( b.leftover_leads, 0 ) - IFNULL( b.leads_sent_today, 0 )<=0))
	        AND a.is_active = 'live' and ((c.daily_limit -leads_sent_today_email) >0 or (d.daily_limit -leads_sent_today_sms) >0)
	        AND a.searchagentid IN ( $joined )
	        AND NOT EXISTS ( SELECT *
	        FROM SALeadsLeftoverStatus h, SASearchAgent e left join SASearchAgentAutoResponder_email f
	        on e.searchagentid = f.searchagentid and f.is_active = 'live'  left join SASearchAgentAutoResponder_sms g
	        on e.searchagentid = g.searchagentid and g.is_active = 'live' WHERE IFNULL( e.flag_auto_download, 'history' ) = 'live'
	        AND e.is_active = 'live' AND e.searchagentid=h.searchagentid AND e.searchagentid IN ( $joined )
	        AND e.clientid = a.clientid and ( IFNULL( e.leads_daily_limit, 0 ) + IFNULL( h.leftover_leads, 0 ) - IFNULL( h.leads_sent_today, 0 ) >0
	        )) group by a.clientid
	        UNION SELECT a.searchagentid FROM SASearchAgent a, SALeadsLeftoverStatus b
	        WHERE a.searchagentid = b.searchagentid
	        AND a.flag_auto_download = 'live'
	        AND a.is_active = 'live' AND (IFNULL( a.leads_daily_limit, 0 ) + IFNULL( b.leftover_leads, 0 ) - IFNULL( b.leads_sent_today, 0 )>0)
	        AND a.searchagentid IN ( $joined)";
		$dbHandle = $this->_loadDatabaseHandle('write');
		$query = $dbHandle->query($sql);
		$retArr = array();
		foreach ($query->result_array() as $row) {
			$id = $row['searchagentid'];
			foreach ($agents as $k => $v) {
				if ($v['id'] == $id) {
					$retArr[] = $v;
				}
			}
		}
		echo "\n getDeduplicatedResult RETURN " . json_encode($retArr) . "\n";
		echo "\n getDeduplicatedResult API call END \n";
		return $retArr;
	}
	function mergeArr($arr1, $arr2) {
		$retArr = array();
		foreach ($arr1 as $k => $v) {
			foreach ($arr2 as $k1 => $v1) {
				if ($v['agentid'] == $v1['agentid']) {
					$retArr[] = $v;
					break;
				}
			}
		}
		return $retArr;
	}
	function insertInDb($profileId, $uniqMatchingAgents) {
		echo "\n insert into DB Final API call START \n";
		$sql = "INSERT INTO `shiksha`.`SALeadAllocation` (`id` ,`userid` ,`agentid` ,`allocationtime` ,`sms_sent` ,`email_sent` ,`auto_download` ,`auto_responder_email` ,`auto_responder_sms`
		,`auto_responder_email_sent` ,`auto_responder_sms_sent`) VALUES ";
		$joined = '';
		$joinedAS = '';
		$joinedAE = '';
		$joinedAD = '';
		$Flag_Marker = FALSE;
		foreach ($uniqMatchingAgents as $k => $v) {
			$id = $v['id'];
			$ad = $v['ad'];
			$as = $v['as'];
			$ae = $v['ae'];
			$joined.= $v['id'] . ',';
			if ($ad == 'YES') $joinedAD.= $v['id'] . ',';
			else {
				if ($as == 'YES') $joinedAS.= $v['id'] . ',';
				if ($ae == 'YES') $joinedAE.= $v['id'] . ',';
			}
			if (!($ad == 'NO' && $as == 'NO' && $ae == 'NO')) {
				$sql.= "(NULL , '$profileId', '$id', now(), 'NO', 'NO', '" . $ad . "','" . $ae . "','" . $as . "','NO', 'NO'),";
				$Flag_Marker = TRUE;
			}
		}
		echo "\n insert counters 4 AR & AD AE\n";
		echo $sql = substr($sql, 0, -1);
		$joined = substr($joined, 0, -1);
		$joinedAD = substr($joinedAD, 0, -1);
		$joinedAS = substr($joinedAS, 0, -1);
		$joinedAE = substr($joinedAE, 0, -1);
		$cnt = count($uniqMatchingAgents);
		if ($cnt > 4) {
			$dead = 'YES';
		} else {
			$dead = 'NO';
		}
		$sql2 = "INSERT INTO `shiksha`.`SAReuseCron` (`id` ,`userid` ,`assignedclientcount` ,`clienttype` ,`modifiedtime` ,`isdead`) VALUES (NULL , '1', ?, 'SHARED', NOW() , ?)";
		echo $sql3 = "update  `SALeadsLeftoverStatus` set leads_sent_today = leads_sent_today+1, last_sent_time = now() where searchagentid in ($joinedAD)";
		echo $sql6 = "update  `SALeadsLeftoverStatus` a, SASearchAgent b set leftover_leads = leftover_leads-1, last_sent_time = now() where a.searchagentid in ($joinedAD) and a.searchagentid = b.searchagentid and (b.leads_daily_limit - a.leads_sent_today) <0";
		echo "\n\n";
		echo $sql4 = "update  `SALeadsLeftoverStatus` set leads_sent_today_sms = leads_sent_today_sms+1, last_sent_time = now() where searchagentid in ($joinedAS)";
		echo "\n\n";
		echo $sql5 = "update  `SALeadsLeftoverStatus` set leads_sent_today_email = leads_sent_today_email+1, last_sent_time = now() where searchagentid in ($joinedAE)";
		
		$dbHandle = $this->_loadDatabaseHandle('write');
		if (trim($joinedAD) != '') {
			$dbHandle->query($sql3);
			//$dbHandle->query($sql6);

		}
		if (trim($joinedAS) != '') {
			$dbHandle->query($sql4);
		}
		if (trim($joinedAE) != '') {
			$dbHandle->query($sql5);
		}
		if ($Flag_Marker) {
			$query = $dbHandle->query($sql);
		}
		$query = $dbHandle->query($sql2, array($cnt, $dead));
		echo "\n insert into DB Final API call END \n";
	}
	function getAppendedList($ids, $clientArr, $profileId, $SMS_LIMIT, $EMAIL_LIMIT) {
		echo "\n getAppendedList API call START \n";
		$retArr = array();
		$counter = 0;
		foreach ($clientArr as $k => $v) {
			$clientArr[$k]['fr_sms'] = ($v['today_sms'] + .001) / ($v['sms_quota'] + $v['left']);
			$clientArr[$k]['fr_email'] = ($v['today_email'] + .001) / ($v['email_quota'] + $v['left']);
		}
		usort($clientArr, array(
			$this,
			"sofr"
		));
		foreach ($ids as $k => $v) {
			$retArr[$counter]['id'] = $v;
			$retArr[$counter]['ad'] = 'YES';
			$retArr[$counter]['ae'] = 'YES';
			$retArr[$counter]['as'] = 'YES';
			$agIds[] = $v;
			$counter++;
		}
		// check the daily limit for client for sending email/sms
		foreach ($clientArr as $k => $v) {
			$retArr[$counter]['id'] = $v['id'];
			$retArr[$counter]['ad'] = 'NO';
			if ($v['remainingAS'] > 0) $retArr[$counter]['as'] = 'YES';
			else $retArr[$counter]['as'] = 'NO';
			if ($v['remainingAE'] > 0) $retArr[$counter]['ae'] = 'YES';
			else $retArr[$counter]['ae'] = 'NO';
			$counter++;
			$agIds[] = $v['id'];
		}
		echo "daily limit email/sms\n";
		print_r($retArr);
		print_r($clientArr);
		echo "\n";
		// check the credits of clients
		echo "\n input data::" . json_encode($agIds) . " & ProfileId:::" . $profileId;
		$creditArray = $this->wsgetAllCreditToConsumedDataForSearchAgents($agIds, $profileId);
		echo "\n" . "@@@ getAppendedList get all credit to consume SAs @@@";
		print_r($creditArray);
		echo "\n";
		foreach ($creditArray as $k => $v) {
			foreach ($retArr as $k1 => $v1) {
				if ($v1['id'] == $v['id']) {
					if ($v['auto_sms'] == 'NO') $retArr[$k]['as'] = 'NO';
					if ($v['auto_email'] == 'NO') $retArr[$k]['ae'] = 'NO';
				}
			}
		}
		// check the daily limit of getting sms for lead
		$smscounter = 0;
		$emailcounter = 0;
		$counter = 0;
		foreach ($clientArr as $k => $v) {
			if ($retArr[$counter]['as'] == 'YES') $smscounter++;
			if ($smscounter <= $SMS_LIMIT) {
			} else {
				$retArr[$counter]['as'] = 'NO';
			}
			if ($retArr[$counter]['ae'] == 'YES') $emailcounter++;
			if ($emailcounter <= $EMAIL_LIMIT) {
			} else {
				$retArr[$counter]['ae'] = 'NO';
			}
		}
		echo "daily limit email/sms\n";
		print_r($retArr);
		print_r($clientArr);
		echo "\n";
		echo "\n getAppendedList API RETURN " . json_encode($retArr) . "\n";
		echo "\n getAppendedList API call END \n";
		return $retArr;
	}
	function getFinalClients($clients, $profileId) {
		echo "\n getFinalClients API call START \n";
		echo "\n getFinalClients::Total clients are :::" . json_encode($clients);
		echo "\n getFinalClients::profileId is :::" . $profileId;
		$dbHandle = $this->_loadDatabaseHandle('write');
		/* one lead can go to the maximum these many shared clients */
		$MAX_SHARED_CLIENTS = 0;
		$MAX_PREMIUM_CLIENTS = 0;
		$SMS_DED = 0;
		$EMAIL_DED = 0;
		$GLOBAL_LIMIT = 0;
		/* No of smses which can be sent to one lead */
		$SMS_LIMIT = 0;
		$EMAIL_LIMIT = 0;
		/* fulfilment ratio */
		$FF = 5;
		echo $sql = "SELECT DISTINCT c.actionType, c.deductcredit
			FROM `tUserPref` a, tCourseGrouping b, tGroupCreditDeductionPolicy c
			WHERE a.UserId = ?
			AND a.DesiredCourse = b.courseId
			AND b.groupId = c.groupId
			AND c.status = 'live'
			AND a.Status = 'live'
			AND b.status = 'live'
			AND (a.extraflag != 'testprep' OR a.extraflag is NULL)
			UNION
			SELECT DISTINCT d.actionType, d.deductcredit
			FROM `tUserPref` a, tUserPref_testprep_mapping b, tCourseGrouping c, tGroupCreditDeductionPolicy d
			WHERE a.UserId = ?
			AND a.prefid = b.prefid
			AND b.blogid = c.courseId
			AND c.groupId = d.groupId
			AND c.status = 'live'
			AND a.Status = 'live'
			AND b.status = 'live'
			AND d.status = 'live'
			AND a.extraflag = 'testprep'";
		$query = $dbHandle->query($sql, array($profileId, $profileId));
		foreach ($query->result_array() as $row) {
			if ($row['actionType'] == 'shared_view_limit') $MAX_SHARED_CLIENTS = $row['deductcredit'];
			if ($row['actionType'] == 'premimum_view_limit') $MAX_PREMIUM_CLIENTS = $row['deductcredit'];
			if ($row['actionType'] == 'sms') $SMS_DED = $row['deductcredit'];
			if ($row['actionType'] == 'email') $EMAIL_DED = $row['deductcredit'];
			if ($row['actionType'] == 'sms_limit') $SMS_LIMIT = $row['deductcredit'];
			if ($row['actionType'] == 'email_limit') $EMAIL_LIMIT = $row['deductcredit'];
			if ($row['actionType'] == 'view_limit') $GLOBAL_LIMIT = $row['deductcredit'];
		}
		if($GLOBAL_LIMIT > 0) {
			echo $sql = "select ViewCount from LDBLeadViewCount where UserId = ?";
			$query = $dbHandle->query($sql, array($profileId));
			foreach ($query->result_array() as $row) {
				$GLOBAL_LIMIT = $GLOBAL_LIMIT - $row['ViewCount'];
			}
			if($MAX_SHARED_CLIENTS > $GLOBAL_LIMIT) {
				$MAX_SHARED_CLIENTS = $GLOBAL_LIMIT;
			}
			if($MAX_PREMIUM_CLIENTS > $GLOBAL_LIMIT) {
				$MAX_PREMIUM_CLIENTS = $GLOBAL_LIMIT;
			}
		}
		$joined = "";
		foreach ($clients as $k => $v) {
			$agIds[] = $v['agentid'];
			$joined.= $v['agentid'] . ",";
		}
		$joined = substr($joined, 0, -1);
		echo "\n Filter Defaulter SAs \n";
		echo "\n Before API CALL SAs ARE ::: \n" . json_encode($agIds);
		$agentsWithCreditCapacity = $this->wsfilterDefaulterSearchAgents($agIds);
		echo "\n After API CALL SAs ARE ::: \n" . json_encode($agentsWithCreditCapacity);
		echo "\n";
		//		echo $sql = "select a.searchagentid, IFNULL(leads_daily_limit,0) as leads_daily_limit, IFNULL(price_per_lead,0) as price_per_lead, IFNULL(pref_lead_type,'shared') as pref_lead_type,IFNULL(leads_sent_today,0) as leads_sent_today, IFNULL(leads_sent_today_sms,0) as leads_sent_today_sms,IFNULL(leads_sent_today_email,0) as leads_sent_today_email,IFNULL(leftover_leads,0) as leftover_leads, IFNULL(flag_auto_download,'history') as flag_auto_download, IFNULL(c.daily_limit,0) as email_limit, IFNULL(d.daily_limit,0) as sms_limit, (IFNULL(leads_daily_limit,0) +IFNULL(leftover_leads,0) - IFNULL(leads_sent_today,0)) as remainingAD, (c.daily_limit -leads_sent_today_email) as remainingAE,(d.daily_limit -leads_sent_today_sms) as remainingAS, IFNULL(c.is_active,'NO') as email_active, IFNULL(d.is_active,'NO') as sms_active   FROM `SASearchAgent` a left join SASearchAgentAutoResponder_email c on a.searchagentid = c.searchagentid and c.is_active = 'live' left join SASearchAgentAutoResponder_sms d on a.searchagentid = d.searchagentid and d.is_active = 'live', `SALeadsLeftoverStatus` b where a.searchagentid = b.searchagentid and a.is_active = 'live' and a.searchagentid in ($joined) and  !(IFNULL(a.flag_auto_download,'history') = 'history' and IFNULL(a.flag_auto_responder_email,'history') = 'history' and IFNULL(a.flag_auto_responder_sms,'history') = 'history' )GROUP BY a.searchagentid";
		echo $sql = "select  a.clientid, a.searchagentid, IFNULL(leads_daily_limit,0) as leads_daily_limit, IFNULL(price_per_lead,0) as price_per_lead, IFNULL(a.pref_lead_type,'shared') as
pref_lead_type,IFNULL(leads_sent_today,0) as leads_sent_today, IFNULL(leads_sent_today_sms,0) as leads_sent_today_sms,IFNULL(leads_sent_today_email,0) as
leads_sent_today_email,IFNULL(leftover_leads,0) as leftover_leads, IFNULL(flag_auto_download,'history') as flag_auto_download, IFNULL(c.daily_limit,0) as
email_limit, IFNULL(d.daily_limit,0) as sms_limit, (IFNULL(leads_daily_limit,0) +IFNULL(leftover_leads,0) - IFNULL(leads_sent_today,0)) as remainingAD,
(c.daily_limit -leads_sent_today_email) as remainingAE,(d.daily_limit -leads_sent_today_sms) as remainingAS, IFNULL(c.is_active,'NO') as email_active,
IFNULL(d.is_active,'NO') as sms_active   FROM `SASearchAgent` a left join SASearchAgentAutoResponder_email c on a.searchagentid = c.searchagentid and
c.is_active = 'live' left join SASearchAgentAutoResponder_sms d on a.searchagentid = d.searchagentid and d.is_active = 'live', `SALeadsLeftoverStatus` b ,

(SELECT max( IFNULL( `price_per_lead` , 0 ) ) AS maxp, `clientid` , `pref_lead_type`
FROM `SASearchAgent` a, SALeadsLeftoverStatus b
WHERE a.`is_active` = 'live'
AND a.searchagentid
IN ($joined)
AND a.searchagentid = b.searchagentid
AND a.flag_auto_download='live'
AND (IFNULL( leads_daily_limit, 0 ) + IFNULL( leftover_leads, 0 ) - IFNULL( leads_sent_today, 0 )) >0
GROUP BY `clientid` , pref_lead_type) AS test

where a.searchagentid = b.searchagentid and IFNULL(a.price_per_lead,0) = test.maxp
AND a.clientid = test.clientid
AND a.pref_lead_type = test.pref_lead_type and a.is_active = 'live' and a.searchagentid in ($joined)
and  !(IFNULL(a.flag_auto_download,'history') = 'history' and IFNULL(a.flag_auto_responder_email,'history') = 'history' and
IFNULL(a.flag_auto_responder_sms,'history') = 'history' ) and (IFNULL( leads_daily_limit, 0 ) + IFNULL( leftover_leads, 0 ) - IFNULL( leads_sent_today, 0 )) >0   GROUP BY a.clientid, a.pref_lead_type

union

select  a.clientid, a.searchagentid, IFNULL(leads_daily_limit,0) as leads_daily_limit, IFNULL(price_per_lead,0) as price_per_lead, IFNULL(a.pref_lead_type,'shared') as
pref_lead_type,IFNULL(leads_sent_today,0) as leads_sent_today, IFNULL(leads_sent_today_sms,0) as leads_sent_today_sms,IFNULL(leads_sent_today_email,0) as
leads_sent_today_email,IFNULL(leftover_leads,0) as leftover_leads, IFNULL(flag_auto_download,'history') as flag_auto_download, IFNULL(c.daily_limit,0) as
email_limit, IFNULL(d.daily_limit,0) as sms_limit, (IFNULL(leads_daily_limit,0) +IFNULL(leftover_leads,0) - IFNULL(leads_sent_today,0)) as remainingAD,
(c.daily_limit -leads_sent_today_email) as remainingAE,(d.daily_limit -leads_sent_today_sms) as remainingAS, IFNULL(c.is_active,'NO') as email_active,
IFNULL(d.is_active,'NO') as sms_active   FROM `SASearchAgent` a left join SASearchAgentAutoResponder_email c on a.searchagentid = c.searchagentid and
c.is_active = 'live' left join SASearchAgentAutoResponder_sms d on a.searchagentid = d.searchagentid and d.is_active = 'live', `SALeadsLeftoverStatus` b


where a.searchagentid = b.searchagentid
and (IFNULL(a.flag_auto_download,'history') = 'history' or  (IFNULL( leads_daily_limit, 0 ) + IFNULL( leftover_leads, 0 ) - IFNULL( leads_sent_today, 0 )) <=0)
and a.is_active = 'live' and a.searchagentid in ($joined)
and  !(IFNULL(a.flag_auto_download,'history') = 'history' and IFNULL(a.flag_auto_responder_email,'history') = 'history' and
IFNULL(a.flag_auto_responder_sms,'history') = 'history' )    GROUP BY a.searchagentid";
		$query = $dbHandle->query($sql);
		$counter1 = 0;
		$counter2 = 0;
		$counter3 = 0;
		$counter4 = 0;
		//Global quota section for auto download starts here
		//declared variable which will be used to calculate leads sent to all SA for a client
		$leadsSentToAllAgents = 0;
		foreach ($query->result_array() as $row) {
			//global quota query
			$globalQuotaQuery = "SELECT leads_sent_today FROM SALeadsLeftoverStatus WHERE searchagentid in (select searchagentid from SASearchAgent where clientid=? and is_active='live') and  (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(last_sent_time))<'86400'";

			$globalQuotaResult = $dbHandle->query($globalQuotaQuery, array($row['clientid']));
			foreach ($globalQuotaResult->result() as $row_global) {
				$leadsSentToAllAgents = $leadsSentToAllAgents + $row_global->leads_sent_today;
			}
			//Global quota section for auto download ends here
			if (in_array($row['searchagentid'], $agentsWithCreditCapacity) && $row['leads_daily_limit'] > 0 && $row['remainingAD'] > 0 && $leadsSentToAllAgents < 1500) {
				if ($row['pref_lead_type'] == 'shared' && $row['flag_auto_download'] == 'live') {
					$sharedClientArr[$counter1]['id'] = $row['searchagentid'];
					$sharedClientArr[$counter1]['cost'] = $row['price_per_lead'];
					$sharedClientArr[$counter1]['quota'] = $row['leads_daily_limit'];
					$sharedClientArr[$counter1]['email_quota'] = $row['email_limit'];
					$sharedClientArr[$counter1]['sms_quota'] = $row['sms_limit'];
					$sharedClientArr[$counter1]['today'] = $row['leads_sent_today'];
					$sharedClientArr[$counter1]['today_email'] = $row['leads_sent_today_email'];
					$sharedClientArr[$counter1]['today_sms'] = $row['leads_sent_today_sms'];
					$sharedClientArr[$counter1]['left'] = $row['leftover_leads'];
					$sharedClientArr[$counter1]['remainingAE'] = $row['remainingAE'];
					$sharedClientArr[$counter1]['remainingAS'] = $row['remainingAS'];
					$counter1++;
				} else if ($row['pref_lead_type'] == 'premium' && $row['flag_auto_download'] == 'live') {
					$exclClientArr[$counter2]['id'] = $row['searchagentid'];
					$exclClientArr[$counter2]['cost'] = $row['price_per_lead'];
					$exclClientArr[$counter2]['quota'] = $row['leads_daily_limit'];
					$exclClientArr[$counter2]['sms_quota'] = $row['sms_limit'];
					$exclClientArr[$counter2]['email_quota'] = $row['email_limit'];
					$exclClientArr[$counter2]['today'] = $row['leads_sent_today'];
					$exclClientArr[$counter2]['today_email'] = $row['leads_sent_today_email'];
					$exclClientArr[$counter2]['today_sms'] = $row['leads_sent_today_sms'];
					$exclClientArr[$counter2]['left'] = $row['leftover_leads'];
					$exclClientArr[$counter2]['remainingAE'] = $row['remainingAE'];
					$exclClientArr[$counter2]['remainingAS'] = $row['remainingAS'];
					$counter2++;
				}
				if ($row['flag_auto_download'] == 'live') {
					$totalClientArr[$counter3]['id'] = $row['searchagentid'];
					$totalClientArr[$counter3]['cost'] = $row['price_per_lead'];
					$totalClientArr[$counter3]['quota'] = $row['leads_daily_limit'];
					$totalClientArr[$counter3]['sms_quota'] = $row['sms_limit'];
					$totalClientArr[$counter3]['email_quota'] = $row['email_limit'];
					$totalClientArr[$counter3]['today'] = $row['leads_sent_today'];
					$totalClientArr[$counter3]['today_email'] = $row['leads_sent_today_email'];
					$totalClientArr[$counter3]['today_sms'] = $row['leads_sent_today_sms'];
					$totalClientArr[$counter3]['left'] = $row['leftover_leads'];
					$totalClientArr[$counter3]['remainingAE'] = $row['remainingAE'];
					$totalClientArr[$counter3]['remainingAS'] = $row['remainingAS'];
					$counter3++;
				}
			} else if ($row['flag_auto_download'] == 'live') {
				$clientArr[$counter4]['id'] = $row['searchagentid'];
				$clientArr[$counter4]['quota'] = $row['leads_daily_limit'];
				$clientArr[$counter4]['email_quota'] = $row['email_limit'];
				$clientArr[$counter4]['sms_quota'] = $row['sms_limit'];
				$clientArr[$counter4]['today'] = $row['leads_sent_today'];
				$clientArr[$counter4]['today_email'] = $row['leads_sent_today_email'];
				$clientArr[$counter4]['today_sms'] = $row['leads_sent_today_sms'];
				$clientArr[$counter4]['left'] = $row['leftover_leads'];
				$clientArr[$counter4]['remainingAE'] = $row['remainingAE'];
				$clientArr[$counter4]['remainingAS'] = $row['remainingAS'];
				$counter4++;
			}
			if ($row['flag_auto_download'] == 'history') {
				$clientArr[$counter4]['id'] = $row['searchagentid'];
				$clientArr[$counter4]['quota'] = $row['leads_daily_limit'];
				$clientArr[$counter4]['sms_quota'] = $row['sms_limit'];
				$clientArr[$counter4]['email_quota'] = $row['email_limit'];
				$clientArr[$counter4]['today'] = $row['leads_sent_today'];
				$clientArr[$counter4]['today_email'] = $row['leads_sent_today_email'];
				$clientArr[$counter4]['today_sms'] = $row['leads_sent_today_sms'];
				$clientArr[$counter4]['left'] = $row['leftover_leads'];
				$clientArr[$counter4]['remainingAE'] = $row['remainingAE'];
				$clientArr[$counter4]['remainingAS'] = $row['remainingAS'];
				$counter4++;
			}
		}
		if (count($totalClientArr) > 0) {
			if (count($exclClientArr) == 0 && count($sharedClientArr) <= $MAX_SHARED_CLIENTS) {
				$ids = array();
				foreach ($sharedClientArr as $k => $v) {
					$ids[] = $v['id'];
				}
				$ids = $this->getAppendedList($ids, $clientArr, $profileId, $SMS_LIMIT, $EMAIL_LIMIT);
				return $ids;
			} else if (count($sharedClientArr) == 0 && count($exclClientArr) <= $MAX_PREMIUM_CLIENTS) {
				$ids = array();
				foreach ($exclClientArr as $k => $v) {
					$ids[] = $v['id'];
				}
				$ids = $this->getAppendedList($ids, $clientArr, $profileId, $SMS_LIMIT, $EMAIL_LIMIT);
				return $ids;
			} else {
				$sum = 0;
				$avg = 0;
				$maxShared = 0;
				$maxExcl = 0;
				$sumCpl = 0;
				$avgCpl = 0;
				$stdDevCpl = 0;
				$sumDev = (float)0.0;
				$sumDev2 = (float)0.0;
				foreach ($totalClientArr as $k => $v) {
					$tempX = ($v['today'] + .001) / ($v['quota'] + $v['left']);
					$sum = $sum + $tempX;
					$sumCpl = $sumCpl + $v['cost'];
				}
				echo "@@@@";
				echo "avgFr is ::" . $avg = $sum / count($totalClientArr);
				echo "\n";
				echo "avgCPL is ::" . $avgCpl = $sumCpl / count($totalClientArr);
				echo "\n";
				echo "@@@@";
				foreach ($totalClientArr as $k => $v) {
					$temp11 = ($v['cost'] - $avgCpl);
					$temp12 = pow($temp11, 2);
					$sumDev = $sumDev + $temp12;
					$temp21 = (float)($v['today'] + .001) / ($v['quota'] + $v['left']);
					$temp22 = (float)$temp21 - $avg;
					$temp23 = pow($temp22, 2);
					$sumDev2 = $sumDev2 + $temp23;
					$ffArr[] = (float)($v['today'] + .001) / ($v['quota'] + $v['left']);
					$cplArr[] = $v['cost'];
				}
				$sumDev2;
				echo "@@@@";
				echo "stdDevCPL is ::" . $stdDevCpl = $this->standard_deviation_population($cplArr);
				echo "\n";
				echo "stdDevFR is ::" . $stdDev = $this->standard_deviation_population($ffArr);
				echo "@@@@";
				if (count($exclClientArr) > 0) {
					foreach ($exclClientArr as $k => $v) {
						echo "\n";
						echo "fr for each " . $fr = ($v['today'] + .001) / ($v['quota'] + $v['left']);
						echo "\n";
						$tempV = ($fr - $avg);
						echo "ded for each" . $temp = $tempV * $FF * $stdDevCpl / (10 * ($stdDev + 0.0001));
						echo "\n";
						$exclClientArr[$k]['adjustedCPL'] = $v['cost'] - $temp;
					}
				}
				if (count($sharedClientArr) > 0) {
					foreach ($sharedClientArr as $k => $v) {
						$fr = ($v['today'] + .001) / ($v['quota'] + $v['left']);
						$sharedClientArr[$k]['adjustedCPL'] = $v['cost'] - (float)($fr - $avg) * $FF * $stdDevCpl / (10 * ($stdDev + 0.0001));
					}
				}
				if (is_array($sharedClientArr)) usort($sharedClientArr, array(
					$this,
					"so"
				));
				if (is_array($exclClientArr)) usort($exclClientArr, array(
					$this,
					"so"
				));
				echo "\n";
				echo "Printing shared client Array ::";
				print_r($sharedClientArr);
				echo "\n";
				echo "Printing excl client Array ::";
				print_r($exclClientArr);
				echo "\n";
				$maxSharedGain = 0;
				$maxExclGain = 0;
				if (count($sharedClientArr) > $MAX_SHARED_CLIENTS) {
					for ($i = 0; $i < $MAX_SHARED_CLIENTS; $i++) {
						$maxSharedGain = $maxSharedGain + $sharedClientArr[$i]['adjustedCPL'];
					}
				} else {
					for ($i = 0; $i < count($sharedClientArr); $i++) {
						$maxSharedGain = $maxSharedGain + $sharedClientArr[$i]['adjustedCPL'];
					}
				}
				if (count($exclClientArr) > $MAX_PREMIUM_CLIENTS) {
					for ($i = 0; $i < $MAX_PREMIUM_CLIENTS; $i++) {
						$maxExclGain = $maxExclGain + $exclClientArr[$i]['adjustedCPL'];
					}
				} else {
					for ($i = 0; $i < count($exclClientArr); $i++) {
						$maxExclGain = $maxExclGain + $exclClientArr[$i]['adjustedCPL'];
					}
				}
				echo "\n\nmax shared gain :" . $maxSharedGain;
				echo "\n\nmax excl gain :" . $maxExclGain;
				if ($maxSharedGain > $maxExclGain) {
					$ids = array();
					if (count($sharedClientArr) > $MAX_SHARED_CLIENTS) {
						for ($i = 0; $i < $MAX_SHARED_CLIENTS; $i++) {
							$ids[] = $sharedClientArr[$i]['id'];
						}
						for ($i = $MAX_SHARED_CLIENTS; $i < count($sharedClientArr); $i++) {
							$clientArr[$counter4]['id'] = $sharedClientArr[$i]['id'];
							$clientArr[$counter4]['quota'] = $sharedClientArr[$i]['quota'];
							$clientArr[$counter4]['email_quota'] = $sharedClientArr[$i]['email_quota'];
							$clientArr[$counter4]['sms_quota'] = $sharedClientArr[$i]['sms_quota'];
							$clientArr[$counter4]['today'] = $sharedClientArr[$i]['today'];
							$clientArr[$counter4]['today_email'] = $sharedClientArr[$i]['today_email'];
							$clientArr[$counter4]['today_sms'] = $sharedClientArr[$i]['today_sms'];
							$clientArr[$counter4]['left'] = $sharedClientArr[$i]['left'];
							$clientArr[$counter4]['remainingAE'] = $sharedClientArr[$i]['remainingAE'];
							$clientArr[$counter4]['remainingAS'] = $sharedClientArr[$i]['remainingAS'];
							$counter4++;
						}
						for ($i = 0; $i < count($exclClientArr); $i++) {
							$clientArr[$counter4]['id'] = $exclClientArr[$i]['id'];
							$clientArr[$counter4]['quota'] = $exclClientArr[$i]['quota'];
							$clientArr[$counter4]['email_quota'] = $exclClientArr[$i]['email_quota'];
							$clientArr[$counter4]['sms_quota'] = $exclClientArr[$i]['sms_quota'];
							$clientArr[$counter4]['today'] = $exclClientArr[$i]['today'];
							$clientArr[$counter4]['today_sms'] = $exclClientArr[$i]['today_sms'];
							$clientArr[$counter4]['today_email'] = $exclClientArr[$i]['today_email'];
							$clientArr[$counter4]['left'] = $exclClientArr[$i]['left'];
							$clientArr[$counter4]['remainingAE'] = $exclClientArr[$i]['remainingAE'];
							$clientArr[$counter4]['remainingAS'] = $exclClientArr[$i]['remainingAS'];
							$counter4++;
						}
					} else {
						for ($i = 0; $i < count($sharedClientArr); $i++) {
							$ids[] = $sharedClientArr[$i]['id'];
						}
					}
					$ids = $this->getAppendedList($ids, $clientArr, $profileId, $SMS_LIMIT, $EMAIL_LIMIT);
					return $ids;
				} else {
					$ids = array();
					if (count($exclClientArr) > $MAX_PREMIUM_CLIENTS) {
						for ($i = 0; $i < $MAX_PREMIUM_CLIENTS; $i++) {
							$ids[] = $exclClientArr[$i]['id'];
						}
						for ($i = $MAX_PREMIUM_CLIENTS; $i < count($exclClientArr); $i++) {
							$clientArr[$counter4]['id'] = $exclClientArr[$i]['id'];
							$clientArr[$counter4]['quota'] = $exclClientArr[$i]['quota'];
							$clientArr[$counter4]['sms_quota'] = $exclClientArr[$i]['sms_quota'];
							$clientArr[$counter4]['email_quota'] = $exclClientArr[$i]['email_quota'];
							$clientArr[$counter4]['today'] = $exclClientArr[$i]['today'];
							$clientArr[$counter4]['today_email'] = $exclClientArr[$i]['today_email'];
							$clientArr[$counter4]['today_sms'] = $exclClientArr[$i]['today_sms'];
							$clientArr[$counter4]['left'] = $exclClientArr[$i]['left'];
							$clientArr[$counter4]['remainingAE'] = $exclClientArr[$i]['remainingAE'];
							$clientArr[$counter4]['remainingAS'] = $exclClientArr[$i]['remainingAS'];
							$counter4++;
						}
						for ($i = 0; $i < count($sharedClientArr); $i++) {
							$clientArr[$counter4]['id'] = $sharedClientArr[$i]['id'];
							$clientArr[$counter4]['quota'] = $sharedClientArr[$i]['quota'];
							$clientArr[$counter4]['sms_quota'] = $sharedClientArr[$i]['sms_quota'];
							$clientArr[$counter4]['email_quota'] = $sharedClientArr[$i]['email_quota'];
							$clientArr[$counter4]['today'] = $sharedClientArr[$i]['today'];
							$clientArr[$counter4]['today_email'] = $sharedClientArr[$i]['today_email'];
							$clientArr[$counter4]['today_sms'] = $sharedClientArr[$i]['today_sms'];
							$clientArr[$counter4]['left'] = $sharedClientArr[$i]['left'];
							$clientArr[$counter4]['remainingAE'] = $sharedClientArr[$i]['remainingAE'];
							$clientArr[$counter4]['remainingAS'] = $sharedClientArr[$i]['remainingAS'];
							$counter4++;
						}
					} else {
						for ($i = 0; $i < count($exclClientArr); $i++) {
							$ids[] = $exclClientArr[$i]['id'];
						}
					}
					$ids = $this->getAppendedList($ids, $clientArr, $profileId, $SMS_LIMIT, $EMAIL_LIMIT);
					echo "\n getFinalClients API call RETURN " . json_encode($ids) . "\n";
					echo "\n getFinalClients API call END \n";
					return $ids;
				}
			}
		} else {
			$arr = array();
			$ids = $this->getAppendedList($arr, $clientArr, $profileId, $SMS_LIMIT, $EMAIL_LIMIT);
			echo "\n getFinalClients API call RETURN " . json_encode($ids) . "\n";
			echo "\n getFinalClients API call END \n";
			return $ids;
		}
	}
	function standard_deviation_population($a) {
		//variable and initializations
		$the_standard_deviation = 0.0;
		$the_variance = 0.0;
		$the_mean = 0.0;
		$the_array_sum = array_sum($a); //sum the elements
		$number_elements = count($a); //count the number of elements
		//calculate the mean
		$the_mean = $the_array_sum / $number_elements;
		//calculate the variance
		for ($i = 0; $i < $number_elements; $i++) {
			//sum the array
			$the_variance = $the_variance + ($a[$i] - $the_mean) * ($a[$i] - $the_mean);
		}
		$the_variance = $the_variance / $number_elements;
		//calculate the standard deviation
		$the_standard_deviation = pow($the_variance, 0.5);
		//return the variance
		return $the_standard_deviation;
	}
	static function so($a, $b) {
		return ($a['adjustedCPL'] < $b['adjustedCPL']);
	}
	static function sofrsms($a, $b) {
		return ($a['fr_sms'] < $b['fr_sms']);
	}
	static function sofremail($a, $b) {
		return ($a['fr_email'] < $b['fr_email']);
	}
    function checkDbToDoTask()
    {
        /*
        $dbHandle = $this->url_manager->CheckDB(null, $dbgroup = 'shiksha',      $flag_connect = 'yes', $flag_alert = 'yes');
        echo "<pre>";print_r($dbHandle);echo "</pre>";

        $this->load->library('listingconfig');
        $this->listingconfig->getDbConfig_test($appId, $dbConfig);
        $dbHandle = $this->load->database($dbConfig, TRUE);
        echo "<pre>";print_r($dbHandle);echo "</pre>";
        */
    }
    
	function getProfiles($type) {
		
	$this->killNotifInterval = 20;
	$this->failureNotifInterval = 6;
	/* Path Shud be writable 777 */
	$this->fileForPid = '/var/www/html/shiksha/system/logs/sa_cron.lock';
	
	echo "\n getProfiles API call START \n";
	$this->load->library('LDB_Client');
	$categoryClient = new LDB_Client();
	$dbHandle = $this->_loadDatabaseHandle('write');
	
	$sql = "select status,shell_pid,failed_count from  `SALeadAllocationCron` where process = 'ALLOCATION'";
	$query = $dbHandle->query($sql);
	
	/* There should be only one row present in Table for 'ALLOCATION' */
	if ($query->num_rows() == 1) {
		
		$row = $query->result_array();
		/* if cron is still running */
		if ($row[0]['status'] == 'ON' && $type=='fresh') {
			
			/* check pid is exist ?? */
			shell_exec('rm -f ' . $this->fileForPid);
			$shellCommand = 'ps ' . $row[0]['shell_pid'] . ' | grep ' . $row[0]['shell_pid'];
			$shellOutput = shell_exec($shellCommand);
			
			if (shell_exec($shellCommand)) {
				
				/* pid exist now check failed_count value */
				if ($row[0]['failed_count'] == $this->killNotifInterval) {
					
					$pid = $row[0]['shell_pid'];
					shell_exec('kill -9 ' . $pid);
					$sql = "Update `SALeadAllocationCron` set status='OFF', shell_pid =null,failed_count='0' where process = 'ALLOCATION'";
					$query = $dbHandle->query($sql);
					/* send mail */
					$this->load->library('Alerts_client');
					$alertClient = new Alerts_client();
					$subject = "Alert Mail re.Search Agent";
					$htmlTemplate = "SA Lead matching , allocation Cron has failed " . $this->killNotifInterval . " times successively. Killing existing process";
					$time = date('Y-m-d h-i-s');
					$senderMail = 'info@shiksha.com';
					$alertClient->externalQueueAdd("1", $senderMail, "ankur.gupta@shiksha.com", $subject, $htmlTemplate, "html", $time);
					$alertClient->externalQueueAdd("1", $senderMail, "sachin.singhal@naukri.com", $subject, $htmlTemplate, "html", $time);
					$alertClient->externalQueueAdd("1", $senderMail, "ravi.raj@shiksha.com", $subject, $htmlTemplate, "html", $time);
					$alertClient->externalQueueAdd("1", $senderMail, "vikas.k@shiksha.com", $subject, $htmlTemplate, "html", $time);
					
				} else if ((($row[0]['failed_count']) % ($this->failureNotifInterval) == 0) && ($row[0]['failed_count'] > 0)) {
					
					$newCount = $row[0]['failed_count'] + 1;
					$sql = "Update `SALeadAllocationCron` set status = 'ON' ,failed_count=? where process = 'ALLOCATION'";
					$query = $dbHandle->query($sql, array($newCount));
					/* send mail */
					$this->load->library('Alerts_client');
					$alertClient = new Alerts_client();
					$subject = "Alert Mail re.Search Agent";
					$htmlTemplate = "SA lead allocation and matching Cron has failed " . $row[0]['failed_count'] . " times successively";
					$time = date('Y-m-d h-i-s');
					$senderMail = 'info@shiksha.com';
					$alertClient->externalQueueAdd("1", $senderMail, "ankur.gupta@shiksha.com", $subject, $htmlTemplate, "html", $time);
					$alertClient->externalQueueAdd("1", $senderMail, "sachin.singhal@naukri.com", $subject, $htmlTemplate, "html", $time);
					$alertClient->externalQueueAdd("1", $senderMail, "ravi.raj@shiksha.com", $subject, $htmlTemplate, "html", $time);
					$alertClient->externalQueueAdd("1", $senderMail, "vikas.k@shiksha.com", $subject, $htmlTemplate, "html", $time);
					
				} else {
					
					$newCount = $row[0]['failed_count'] + 1;
					$sql = "Update `SALeadAllocationCron` set status = 'ON' ,failed_count=? where process = 'ALLOCATION'";
					$query = $dbHandle->query($sql, array($newCount));
					
				}
			} else {
				
				/* No pid exist !!! update DB */
				$sql = "Update `SALeadAllocationCron` set status='OFF', shell_pid =null,failed_count='0' where process = 'ALLOCATION'";
				$query = $dbHandle->query($sql);
				
			}
			
			exit();
			
		} else if (($row[0]['status'] == 'OFF' && $type=='fresh') || $type=='old') {
			
			if($type == 'fresh') {
				/* if cron is not running */
				shell_exec('ps ux | awk \'/matchingLeads/ && !/awk/ {print $2}\' >' . $this->fileForPid);
				$Handle = fopen($this->fileForPid, 'r');
				$pid = fread($Handle, 5);
				fclose($Handle);
				$sql = "Update `SALeadAllocationCron` set status='ON', shell_pid =?,failed_count='0' where process = 'ALLOCATION'";
				$query = $dbHandle->query($sql, array($pid));
			}
			/*
			$sql2 = "select a.userid from tuser a, tuserflag b, tUserPref c where a.userid = b.userId and a.userid = c.userid and b.mobileverified='1' and b.hardbounce!='1' and b.ownershipchallenged!='1' and
			DATEDIFF(IFNULL(c.TimeOfStart,'0000-00-00 00:00:00'),now()) <= 366 and
			c.TimeOfStart != '0000-00-00 00:00:00' and b.abused!='1' and
			b.softbounce!='1' and c.is_processed = 'no' and (UNIX_TIMESTAMP(now()) - UNIX_TIMESTAMP(c.submitdate)) <  86400 group by a.userid order by c.submitdate";
			$sql2 = "select a.userid from tuser a, tuserflag b, tUserPref c where a.userid = b.userId and a.userid = c.userid and b.mobileverified='1' and b.hardbounce!='1' and b.ownershipchallenged!='1' and c.TimeOfStart <= now() + interval 1 year and c.TimeOfStart != '0000-00-00 00:00:00' and b.abused!='1' and b.softbounce!='1' and c.is_processed = 'no' and c.submitdate > now() - interval 1 day group by a.userid order by c.submitdate";
			 */
			$this->benchmark->mark('start'); 			
			$sql2 = "SELECT a.userid from tuser a LEFT JOIN SALeadAllocation sl ".
			         "ON a.userid = sl.userid, tuserflag b, tUserPref c ".
			         "WHERE a.userid = b.userId AND a.userid = c.userid ".
			         "AND b.mobileverified='1' AND b.hardbounce!='1' ".
			         "AND b.ownershipchallenged!='1' AND b.isTestUser = 'NO' ".
			         "AND ((c.TimeOfStart <= now() + interval 1 year ".
			         "AND c.TimeOfStart != '0000-00-00 00:00:00') OR (c.TimeOfStart IS NULL)) ".
			         "AND b.abused!='1' AND b.softbounce!='1' AND c.is_processed = 'no' ".
			         "AND b.isLDBUser = 'YES' AND c.submitdate > now() - interval 1 day ".
			         "AND c.desiredcourse not in(2,52) ".
			         "AND sl.userid is null group by a.userid order by c.submitdate";
			
			echo "<br/>";
			echo "freshleadsql".$sql2."<br/>";
			
			$query2 = $dbHandle->query($sql2);
			$this->benchmark->mark('end');
			echo "getProfiles freshleads query time".($this->benchmark->elapsed_time('end', 'start'))."<br/>";
			 
			$this->benchmark->mark('start'); 
			$freshLeads = array();
			$fresh_temp = $query2->result_array(); 
			foreach ($fresh_temp as $row2) {
				$freshLeads[] = $row2['userid'];
			}
			$this->benchmark->mark('end');
			echo "getProfiles freshleads execution time".($this->benchmark->elapsed_time('end', 'start'))."<br/>";
			
			if($type == 'old') {
				$oldActiveLeads = $this->_getOldActiveLeads($freshLeads);
			}
			
			$profiles = array();
			$profiles['fresh'] = array();
			$profiles['oldActive'] = array();
			
			if($type == 'old') {
				unset($freshLeads);					
			}
			
			if (count($freshLeads) > 0 || count($oldActiveLeads) > 0) {
				
				foreach ($freshLeads as $leadId) {
					$profiles['fresh'][] = $categoryClient->sgetUserDetails(1, $leadId);
				}
				
				foreach ($oldActiveLeads as $leadId) {
					$profiles['oldActive'][] = $categoryClient->sgetUserDetails(1, $leadId);
				}
				
			} else {
				
				echo "\n" . strftime('%c') . "INFO: No Leads to process";
				return;
			}
			
		} else {
			
			echo "\n Check SALeadAllocationCron Table Entries.\n"."<br/>";
			return;
		}
		
	} else {
		echo "\n Check SALeadAllocationCron Table Entries.\n"."<br/>";
		return;
	}
	
	echo "\n getProfiles API call RETURN count" . count($profiles) . "\n"."<br/>";
	echo "\n getProfiles API call END \n"."<br/>";
	return $profiles;
	
	}
	
	private function _getOldActiveLeads($exclude)
	{
				
		$oldActiveLeads = array();
		
		$dbHandle = $this->_loadDatabaseHandle('write');
		
		/*$sql = "select a.userid,c.DesiredCourse,c.ExtraFlag,t.blogId,d.ViewCount 
				from tuser a 
				inner join tuserflag b on a.userid = b.userId 
				inner join tUserPref c on a.userid = c.userid 
				left join tUserPref_testprep_mapping t on t.prefid = c.prefid 
				left join LDBLeadViewCount d on d.UserId = a.userid 
				where a.usergroup = 'user' and b.mobileverified='1' and b.hardbounce!='1' and b.isLDBUser = 'YES' and b.ownershipchallenged!='1' and b.isTestUser = 'NO' and c.TimeOfStart <= now() + interval 1 year and c.TimeOfStart != '0000-00-00 00:00:00' and b.abused!='1' and b.softbounce!='1' and a.lastlogintime > now() - interval 1 day ".(is_array($exclude) && count($exclude) > 0 ? " and a.userid not in (".implode(',',$exclude).") " : "")." group by a.userid";*/
				
	   $sql = "select a.userid,c.DesiredCourse,c.ExtraFlag,t.blogId,d.ViewCount 
				from tuser a 
				inner join tuserflag b on a.userid = b.userId 
				inner join tUserPref c on a.userid = c.userid 
				left join tUserPref_testprep_mapping t on t.prefid = c.prefid 
				left join LDBLeadViewCount d on d.UserId = a.userid 
				where c.desiredcourse not in(2,52) AND a.usergroup = 'user' and b.mobileverified='1' and b.hardbounce!='1' and b.isLDBUser = 'YES' and b.ownershipchallenged!='1' and b.isTestUser = 'NO' and ((c.TimeOfStart <= now() + interval 1 year and c.TimeOfStart != '0000-00-00 00:00:00') OR (c.TimeOfStart IS NULL)) and b.abused!='1' and b.softbounce!='1' and a.lastlogintime > now() - interval 1 day ".(is_array($exclude) && count($exclude) > 0 ? " and a.userid not in (".implode(',',$exclude).") " : "")." ";	 		
	   
	   $results = array();
	   $query = $dbHandle->query($sql);
	   $temp_results = $query->result_array();
	   foreach($temp_results as $row) {
		   $results[$row['userid']] = $row;
	   }
	   	   
	   $this->benchmark->mark('end');
	   echo "getProfiles old leads first query time".($this->benchmark->elapsed_time('end', 'start'))."<br/>";
	   
	   $this->benchmark->mark('start');
	   if(count($results) > 0) {
			$sql = "SELECT a.courseId,a.extraFlag,b.deductcredit 
					 FROM  `tCourseGrouping` a, tGroupCreditDeductionPolicy b
					 WHERE a.groupid = b.groupid
					 AND a.status =  'live'
					 AND b.actionType =  'view_limit'
					 AND b.status =  'live'";
			$query = $dbHandle->query($sql);
			$viewLimits = array();
			foreach($query->result_array() as $row) {
				$viewLimits[$row['extraFlag']][$row['courseId']] = $row['deductcredit'];
			}
			
			$validActiveUsers = array();
			foreach($results as $result) {
				$viewLimit = $result['ExtraFlag'] == 'testprep' ? $viewLimits['testprep'][$result['blogId']] : $viewLimits['course'][$result['DesiredCourse']];
				if($result['ViewCount'] < $viewLimit) {
					$oldActiveLeads[] = $result['userid'];
				}
			}
		}
		
		$this->benchmark->mark('end');
		echo "getProfiles old leads second query time".($this->benchmark->elapsed_time('end', 'start'))."<br/>";
		return $oldActiveLeads;
	}
	
	function getLocationAgents($arr) {
		
		$retArr = array();
	
		$isResponseLead = FALSE;
        $isNational_lead = FALSE;
		foreach($arr as $leadProfileId => $leadProfile) {
			if($leadProfile->isResponseLead == 'YES') {
				$isResponseLead = TRUE;
			}
		}
		
		if($isResponseLead) {
			$prefAgents = $this->matchPrefLocation($arr);
			$finalArr = array();
			foreach ($prefAgents as $k => $v) {
				$finalArr[] = $v;
			}
		}
		else {
			
			$prefAgents = $this->matchPrefLocation($arr);
			
			foreach($arr as $leadProfileId => $leadProfile) {
				 $pref_data = $leadProfile->PrefData;
				 $pref_data_object = $pref_data[0];
				 //_P($leadProfile);
				 //echo "soenfjhshsdsdksdshdsdsd______________".$pref_data_object->ExtraFlag;exit;
				//mail("aditya.roshan@shiksha.com","loactheeeeeeeeee bug",$leadProfile->userid."_____".$pref_data_object->ExtraFlag);
			    if($pref_data_object->ExtraFlag !='studyabroad') {
					$prefAgents = array();
                    $isNational_lead = TRUE;
					break;
				} 				
			}
			
			$currentAgents = $this->matchCurrentLocation($arr);
			$currentLocalityAgents = $this->matchCurrentLocality($arr);
			$finalArr = array();
			foreach ($prefAgents as $k => $v) {
				$finalArr[] = $v;
			}
			foreach ($currentAgents as $k => $v) {
				$finalArr[] = $v;
			}
			foreach ($currentLocalityAgents as $k => $v) {
				$finalArr[] = $v;
			}
		}
		
		if(count($finalArr) == 0) {
			echo "NO MATCHING LOCATION FOUND:::";
			print_r($arr);
			error_log("NO MATCHING LOCATION FOUND:::".print_r($arr,TRUE),3,'/tmp/saLog.log');
			return array();
		}
		
		$joinedList = '';
		foreach ($finalArr as $kl => $vl) {
			$joinedList.= $vl . ',';
		}
		$joinedList = substr($joinedList, 0, -1);
		$this->load->library('LDB_Client');
		$categoryClient = new LDB_Client();
		$dbHandle = $this->_loadDatabaseHandle('write');
		echo $sql = "select a.searchagentid, a.clientid, IFNULL(b.locationandor,'and') as locationandor from SASearchAgent a left outer join SASearchAgentBooleanCriteria b on a.searchagentid = b.searchagentid where
			a.searchagentid in ($joinedList) and a.is_active = 'live'";
		$query = $dbHandle->query($sql);
		
		$andAgents = array();
		$otherAgents = array();
		
		$i = 0;
		$j = 0;
		foreach ($query->result_array() as $row) {
			
			if($isResponseLead == TRUE || $isNational_lead == TRUE) {
				$otherAgents[$j]['agentid'] = $row['searchagentid'];
				$otherAgents[$j]['clientid'] = $row['clientid'];
				$j++;
			}
			else {
				if (isset($row['locationandor']) && trim($row['locationandor']) == 'and') {
					$andAgents[$i]['agentid'] = $row['searchagentid'];
					$andAgents[$i]['clientid'] = $row['clientid'];
					$i++;
				} else {
					$otherAgents[$j]['agentid'] = $row['searchagentid'];
					$otherAgents[$j]['clientid'] = $row['clientid'];
					$j++;
				}	
			}
		}
		echo "\n and agents \n";
		$this->dumpmatchingArray($andAgents);
		echo "\n";
		echo "\n or agents \n";
		$this->dumpmatchingArray($otherAgents);
		echo "\n";
		foreach ($andAgents as $k => $v) {
			if (in_array($v['agentid'], $prefAgents) && in_array($v['agentid'], $currentAgents)) $retArr[] = $v;
		}
		foreach ($otherAgents as $k => $v) {
			$retArr[] = $v;
		}
		echo "\n";
		$this->dumpmatchingArray($retArr);
		echo "\n";
		return $retArr;
	}
	
function matchCourse($profile) {
	
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$profileId = $k1;
	}
	
	$sql = "select extraflag, desiredcourse from tUserPref where UserId = ?";
	$query = $dbHandle->query($sql, array($profileId));
	
	if ($query->num_rows() > 0) {
		
		foreach ($query->result_array() as $row) {
			$flag = $row['extraflag'];
		}
		
	}
	
	if ($flag == 'testprep') {
		
		echo "\n\n-------TESTPREP MATCHING------------------\n\n"."<br/>";
		
		$sql = "SELECT DISTINCT searchAlertId as id, clientid
	        FROM SAMultiValuedSearchCriteria a, SASearchAgent b, tUserPref_testprep_mapping c, tUserPref d
	        WHERE d.UserId = ? AND d.PrefId = c.prefid
	        AND c.blogid = a.value
	        AND a.searchAlertId = b.searchagentid
	        AND a.keyname = 'testprep'
	        AND d.ExtraFlag = 'testprep'
		AND b.is_active= 'live' AND b.type = 'lead'
	        UNION
	        SELECT DISTINCT b.searchagentid as id, clientid
	        FROM SASearchAgent b
	        LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid
	        AND (a.keyname = 'testprep' or a.keyname = 'Specialization' or a.keyname = 'desiredcourse')
	        WHERE a.id IS NULL AND b.is_active= 'live' AND b.type = 'lead'";
	        
	    echo $sql."<br/>";
	        
		$query = $dbHandle->query($sql, array($profileId));
		
		if ($query->num_rows() > 0) {
			
			$i = 0;
			foreach ($query->result_array() as $row) {
				$returnArr[$i]['agentid'] = $row['id'];
				$returnArr[$i]['clientid'] = $row['clientid'];
				$i++;
			}
			
		}
		
		$this->dumpmatchingArray($returnArr);
		return $returnArr;
		
	} else {
					
		$sql = "select distinct searchAlertId as id , clientid from SAMultiValuedSearchCriteria a , SASearchAgent b , tUserSpecializationPref c ,tUserPref d
		where
		a.searchAlertId = b.searchagentid
		and c.PrefId = d.prefid
		and c.SpecializationId = a.value
		and a.keyname = 'Specialization'
		and b.is_active = 'live'  AND b.type = 'lead'
		and d.UserId = ?";
		
		echo $sql."<br/>";
		
		$query = $dbHandle->query($sql, array($profileId));
		
		$i = 0;
		
		if ($query->num_rows() > 0) {
			
			foreach ($query->result_array() as $row) {
				$returnArr[$i]['agentid'] = $row['id'];
				$returnArr[$i]['clientid'] = $row['clientid'];
				$i++;
			}
			
		}
		// if we get any match for specialization we append the list of SA which have not filled specialization or desiredcourse or testprep
		if ($i > 0) {
			
			echo "\n\n-------SPECIALIZATION MATCHING------------------\n\n"."<br/>";
										
			$sql = "SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND
			(a.keyname ='testprep' or a.keyname ='Specialization' or a.keyname = 'desiredcourse') WHERE a.id IS NULL AND b.is_active= 'live' AND b.type = 'lead'";
			
			echo $sql."<br/>";
			$query = $dbHandle->query($sql);
			
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
			
			$this->dumpmatchingArray($returnArr);
			return $returnArr;
			
		}
		// else we check for desiredcourse
		else {
			
			echo "\n\n-------DESIREDCOURSE MATCHING------------------\n\n"."<br/>";
			
			$sql = "select distinct searchAlertId as id , clientid from SAMultiValuedSearchCriteria a , SASearchAgent b ,tUserPref d left join tUserSpecializationPref c on
			c.PrefId = d.PrefId
			where
			a.searchAlertId = b.searchagentid
			and a.value = d.DesiredCourse
			and a.keyname = 'desiredcourse'
			and b.is_active = 'live' AND b.type = 'lead'
			and c.PrefId is NULL
			and d.UserId =?

			union
			SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND (a.keyname
			= 'testprep' or	a.keyname = 'Specialization' or a.keyname = 'desiredcourse') WHERE a.id IS NULL AND b.is_active= 'live' AND b.type = 'lead'";
			echo $sql."<br/>";
			
			$query = $dbHandle->query($sql, array($profileId));
			
			$i = 0;
			
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
			
			if ($i > 0) {
				$this->dumpmatchingArray($returnArr);
				return $returnArr;
			}
			
		}
	}
	
}
	
function match12($profile) {
	
	$dbHandle = $this->_loadDatabaseHandle('write');
	$dbKeyname = '12';
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['EducationData'])) {
			$sql = '';
			foreach ($finalArr['EducationData'] as $k2 => $v2) {
				$eduArr = (array)$v2;
				echo "eduArr is as " . print_r($eduArr, true);
				if ($eduArr['Level'] == $dbKeyname) {
					$marks = $eduArr['Marks'];
					if ($eduArr['Name'] == 'science' || $eduArr['Name'] == 'arts' || $eduArr['Name'] == 'commerce') {
						$stream = $eduArr['Name'];
						echo "\n stream is as $stream \n";
						$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b, SAMultiValuedSearchCriteria c  where
													a.searchagentid=b.searchagentid and a.searchagentid=c.searchAlertId and a.is_active = 'live' and IFNULL(b.minXIIMarksObtained,0) <= ".$dbHandle->escape($marks)." and c.keyname='XIIStream' and c.value=".$dbHandle->escape($stream);
						$sql.= " UNION SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a, SAMultiValuedSearchCriteria c where a.searchagentid =
													b.searchagentid and a.searchagentid=c.searchAlertId and b.is_active= 'live' and c.is_active='live' and (IFNULL(a.minXIIMarksObtained,0) <= ".$dbHandle->escape($marks).") and b.searchagentid not in(select distinct searchAlertId from SAMultiValuedSearchCriteria where keyname='XIIStream' and is_active='live')";
						echo "\n sql with stream is as " . " $sql \n";
					} else {
						echo "\n marks are as $marks \n";
						$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b where
						a.searchagentid=b.searchagentid and a.is_active = 'live' and IFNULL(b.minXIIMarksObtained,0) <= ".$dbHandle->escape($marks)." ";
						$sql.= " UNION SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid =
						b.searchagentid and b.is_active= 'live' and (IFNULL(a.minXIIMarksObtained,0) <= ".$dbHandle->escape($marks).")";
					}
					echo "\n sql is as" . " $sql \n";
				}
			}
			if (trim($sql) == '') {
				echo "\n trimSql is null \n";
				echo $sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
					and b.is_active= 'live' and (a.minXIIMarksObtained is NULL or a.minXIIMarksObtained = 0)";
			}
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
	}
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

function match12Year($profile) {
	
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		$EducationData = $finalArr['EducationData'];		
		//echo "the data the education";
		//_P($EducationData);
		if (is_array($EducationData) && count($EducationData)>0) {			
			foreach ($EducationData as $k2 => $v2) {
				
				$eduArr = (array)$v2;							
				//echo "kunkunnnnnn".$eduArr['Level']."___".$eduArr['CourseCompletionDate'];
				
				if ($eduArr['Level'] == 12 && !empty($eduArr['CourseCompletionDate']) && $eduArr['CourseCompletionDate']!='0000-00-00 00:00:00') {
					
					$time=strtotime($eduArr['CourseCompletionDate']);					
					$year=date("Y",$time);	
					$year = intval($year);
				    //echo "kunkunnnnnn1----".$eduArr['Level']."___".$eduArr['CourseCompletionDate'];						
					$sql = "(select distinct searchAlertId as id, clientid from SARangedSearchCriteria a,   ".
					       "SASearchAgent b where a.searchAlertId = b.searchagentid and ".
					        "a.keyname ='XIICompleted' and a.is_active = 'live' and b.is_active = 'live' ".
					        "and IF(rangeStart =0,1970,rangeStart)<= ? and IF(rangeEnd =0,2070,rangeEnd) >= ?) ".
					        "UNION (SELECT b.searchagentid as id, clientid ".
					        "FROM SASearchAgent b LEFT JOIN SARangedSearchCriteria a ON a.searchAlertId = b.searchagentid ".
					        "AND a.keyname = 'XIICompleted' WHERE a.id IS NULL  and b.is_active = 'live')";	
					        			    
					$query = $dbHandle->query($sql, array($year, $year));
					//echo "2httthhhththttttttttttttttttt".$sql;
					//var_dump($year);
					if ($query->num_rows() > 0) {
						$i = 0;
						foreach ($query->result_array() as $row) {
							$returnArr[$i]['agentid'] = $row['id'];
							$returnArr[$i]['clientid'] = $row['clientid'];
							$i++;
						}
					}
					
		        } else {
					
						$sql = "SELECT b.searchagentid , clientid FROM SASearchAgent b ".
						       "LEFT JOIN SARangedSearchCriteria a ON a.searchAlertId = b.searchagentid ".
						        "AND a.keyname = 'XIICompleted' WHERE a.id IS NULL  and b.is_active = 'live'";
						        
						$query = $dbHandle->query($sql);
						if ($query->num_rows() > 0) {
							$i = 0;
							foreach ($query->result_array() as $row) {
								$returnArr[$i]['agentid'] = $row['searchagentid'];
								$returnArr[$i]['clientid'] = $row['clientid'];
								$i++;
							}
						}
				}	
	        } 
	        
		} else {
			
			$sql = " SELECT b.searchagentid , clientid FROM SASearchAgent b ".
			       "LEFT JOIN SARangedSearchCriteria a ON a.searchAlertId = b.searchagentid ".
			       "AND a.keyname = 'XIICompleted' WHERE a.id IS NULL  and b.is_active = 'live'";
			       
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
	}
	
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

function matchGrad($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$dbKeyname = 'UG';
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['EducationData'])) {
			$sql = '';
			foreach ($finalArr['EducationData'] as $k2 => $v2) {
				$eduArr = (array)$v2;
				if ($eduArr['Level'] == $dbKeyname) {
					$marks = $eduArr['Marks'];
					$includeawaited = $eduArr['OngoingCompletedFlag'];
					if ($includeawaited > 0) {
						$ongoingStatus = 'yes';
					} else {
						$ongoingStatus = 'no';
					}
					$degree = $eduArr['Name'];
				/*
					$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b, SAMultiValuedSearchCriteria c where
						a.searchagentid=b.searchagentid and a.searchagentid=c.searchAlertId and a.is_active = 'live' and c.keyname = 'UGCourse' and c.value = '$degree' and (IFNULL(b.minGradMarksObtained,0) <= '$marks' or
IFNULL(b.includeResultsAwaited,'no') ='$ongoingStatus')";
					$sql.= " UNION SELECT distinct b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
and b.is_active= 'live' and (IFNULL(a.minGradMarksObtained,0) <= '$marks') and b.searchagentid not in(select distinct searchAlertId from SAMultiValuedSearchCriteria where keyname='UGCourse')";
				*/
				$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a left join SAMultiValuedSearchCriteria c on a.searchagentid=c.searchAlertId and c.keyname = 'UGCourse', SASearchAgentBooleanCriteria b where a.searchagentid=b.searchagentid and a.is_active = 'live' and b.is_active = 'live' and (c.value = ".$dbHandle->escape($degree)." or c.value is null) and (IFNULL(b.minGradMarksObtained,0) <= ".$dbHandle->escape($marks)." or IFNULL(b.includeResultsAwaited,'no') =".$dbHandle->escape($ongoingStatus).")";
				}
			}
			if (trim($sql) == '') {
				$sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
and b.is_active= 'live' and (a.minGradMarksObtained is NULL or a.minGradMarksObtained = 0)";
			}
			echo "\n $sql \n";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
	}
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchGradYears($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$dbKeyname = 'UG';
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['EducationData'])) {
			$sql = '';
			foreach ($finalArr['EducationData'] as $k2 => $v2) {
				$eduArr = (array)$v2;
				if ($eduArr['Level'] == $dbKeyname) {
					$CourseCompletionDate = !empty($eduArr['CourseCompletionDate']) ? $eduArr['CourseCompletionDate'] : '0000-00-00 00:00:00';
					$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b where
a.searchagentid=b.searchagentid and a.is_active = 'live'
and
(
( IFNULL(b.graduationCompletedFrom,'0000-00-00 00:00:00') <= '".$dbHandle->escape_str($CourseCompletionDate)."') and
( IFNULL(b.graduationCompletedTo,'2070-01-01 00:00:00') > '".$dbHandle->escape_str($CourseCompletionDate)."')
)";
					echo "\n $sql \n";
				}
			}
			if (trim($sql) == '') {
				echo $sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
and b.is_active= 'live' and (a.graduationCompletedTo is NULL and a.graduationCompletedFrom is NULL)";
			}
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
	}
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchDegreePref($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['PrefData'])) {
			foreach ($finalArr['PrefData'] as $k2 => $v2) {
				$prefArr = (array)$v2;
				$prefCourseA = $prefArr['DegreePrefAICTE'];
				$prefCourseU = $prefArr['DegreePrefUGC'];
				$prefCourseI = $prefArr['DegreePrefInternational'];
				$prefCourseany = $prefArr['DegreePrefAny'];
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid AND ((a.degreePrefAny = 'yes' OR a.degreePrefAICTE = 'yes'
OR a.degreePrefUGC = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid AND ((a.degreePrefAICTE = 'yes'
OR a.degreePrefUGC = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid AND ((a.degreePrefAny = 'yes' OR a.degreePrefAICTE = 'yes'
OR a.degreePrefUGC = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAICTE = 'yes'
OR a.degreePrefUGC = 'yes' ) OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny = 'yes' OR a.degreePrefAICTE = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND
a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAICTE = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny = 'yes' OR a.degreePrefAICTE = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'yes' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAICTE = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny = 'yes' or a.degreePrefUGC = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND
a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefUGC = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny = 'yes' or a.degreePrefUGC = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND
a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'yes' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefUGC = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny = 'yes' or a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'yes' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefInternational = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'yes') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny = 'yes') OR (a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				if (trim($prefCourseA) == 'no' && trim($prefCourseU) == 'no' && trim($prefCourseI) == 'no' && trim($prefCourseany) == 'no') echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =
b.searchagentid
AND ((a.degreePrefAny IS NULL and a.degreePrefAICTE IS NULL and a.degreePrefUGC IS NULL AND a.degreePrefInternational IS NULL)) AND
b.is_active = 'live'";
				echo 'is the sql'.$sql;
				if(!empty($sql)){
				$query = $dbHandle->query($sql);
				if ($query->num_rows() > 0) {
					$i = 0;
					foreach ($query->result_array() as $row) {
						$returnArr[$i]['agentid'] = $row['searchagentid'];
						$returnArr[$i]['clientid'] = $row['clientid'];
						$i++;
					}
				}
			  }
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." DegreePrefAICTE agents";

	}
	echo "\n Match Degree Pref Final Result::\n";
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchMode($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['PrefData'])) {
			foreach ($finalArr['PrefData'] as $k2 => $v2) {
				$prefArr = (array)$v2;
				$prefmodefull = $prefArr['ModeOfEducationFullTime'];
				$prefmodepart = $prefArr['ModeOfEducationPartTime'];
				if (trim($prefmodefull) == 'yes' && $prefmodepart == 'yes') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid = b.searchagentid AND ((a.modePartTime = 'yes' OR a.modeFullTime = 'yes') OR (a.modePartTime IS NULL AND a.modeFullTime IS NULL)) AND b.is_active = 'live'";
				}
				if (trim($prefmodefull) == 'yes' && $prefmodepart == 'no') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid = b.searchagentid AND ((a.modeFullTime = 'yes' ) OR (a.modePartTime IS NULL AND a.modeFullTime IS NULL)) AND b.is_active = 'live'";
				}
				if (trim($prefmodefull) == 'no' && $prefmodepart == 'yes') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid = b.searchagentid AND ((a.modePartTime = 'yes') OR (a.modePartTime IS NULL AND a.modeFullTime IS NULL)) AND b.is_active = 'live'";
				}
				if (trim($prefmodefull) == 'no' && $prefmodepart == 'no') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid = b.searchagentid AND ((a.modePartTime IS NULL AND a.modeFullTime IS NULL)) AND b.is_active = 'live'";
				}
				if(!empty($sql)) {
				$query = $dbHandle->query($sql);
				if ($query->num_rows() > 0) {
					$i = 0;
					foreach ($query->result_array() as $row) {
						$returnArr[$i]['agentid'] = $row['searchagentid'];
						$returnArr[$i]['clientid'] = $row['clientid'];
						$i++;
					}
				}
			  }
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." modeFullTime agents";

	}
	//print_r($returnArr);
	echo "\nMatch Full Time result set\n";
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchUserFunds($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['PrefData'])) {
			foreach ($finalArr['PrefData'] as $k2 => $v2) {
				$prefArr = (array)$v2;
				echo $ufo = $prefArr['UserFundsOwn'];
				echo $ufb = $prefArr['UserFundsBank'];
				echo $ufn = $prefArr['UserFundsNone'];
				if (trim($ufo) == 'yes' && trim($ufb) == 'yes' && trim($ufn) == 'yes') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsBank = 'yes' or a.userFundsOwn = 'yes' or a.userFundsNone = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if (trim($ufo) == 'yes' && trim($ufb) == 'yes' && (trim($ufn) == 'no' || trim($ufn) == '')) {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsOwn = 'yes' or a.userFundsBank = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if (trim($ufo) == 'yes' && (trim($ufb) == 'no' || trim($ufb) == '') && trim($ufn) == 'yes') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsOwn = 'yes' or a.userFundsNone = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if (trim($ufo) == 'yes' && (trim($ufb) == 'no' || trim($ufb) == '') && (trim($ufn) == 'no' || trim($ufn) == '')) {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsOwn = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if ((trim($ufo) == 'no' || trim($ufo) == '') && trim($ufb) == 'yes' && trim($ufn) == 'yes') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsBank = 'yes' or a.userFundsNone = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if ((trim($ufo) == 'no' || trim($ufo) == '') && trim($ufb) == 'yes' && (trim($ufn) == 'no' || trim($ufn) == '')) {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsBank = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if ((trim($ufo) == 'no' || trim($ufo) == '') && (trim($ufb) == 'no' || trim($ufb) == '') && trim($ufn) == 'yes') {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsNone = 'yes') or (a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				if ((trim($ufo) == 'no' || trim($ufo) == '') && (trim($ufb) == 'no' || trim($ufb) == '') && (trim($ufn) == 'no' || trim($ufn) == '')) {
					echo $sql = "SELECT DISTINCT a.searchagentid, clientid FROM `SASearchAgentBooleanCriteria` a, SASearchAgent b WHERE a.searchagentid =b.searchagentid AND ((a.userFundsBank is NULL and a.userFundsOwn is NULL and a.userFundsNone is NULL)) AND b.is_active = 'live'";
				}
				$query = $dbHandle->query($sql);
				if ($query->num_rows() > 0) {
					$i = 0;
					foreach ($query->result_array() as $row) {
						$returnArr[$i]['agentid'] = $row['searchagentid'];
						$returnArr[$i]['clientid'] = $row['clientid'];
						$i++;
					}
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." UserFundsOwn agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchAge($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		$age = $finalArr['age'];
		if (isset($age) && trim($age) != '') {
			$sql = "select distinct searchAlertId as id, clientid from SARangedSearchCriteria a,   SASearchAgent b where a.searchAlertId = b.searchagentid and a.keyname =
'age' and a.is_active = 'live' and b.is_active = 'live' and rangeStart <= ? and rangeEnd >= ?";
			echo $sql.= " union SELECT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SARangedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'age' WHERE a.id IS NULL  and b.is_active = 'live'";
			$query = $dbHandle->query($sql, array($age, $age));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		} else {
			echo $sql = " SELECT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SARangedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'age' WHERE a.id IS NULL  and b.is_active = 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." Age agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchGender($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		$gender = $finalArr['gender'];
		if (isset($gender) && trim($gender) != '') {
			$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'Gender' and b.is_active = 'live' and a.value = ?";
			echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId =
b.searchagentid AND a.keyname = 'Gender' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql, array($gender));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		} else {
			echo $sql = " SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId =
b.searchagentid AND a.keyname = 'Gender' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." gender agents";
	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

function matchPassport($profile)
{
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	
	foreach ($profile as $k1 => $v1) {
		
		$finalArr = (array)$v1;
		$passport = $finalArr['passport'];
		
		if (isset($passport) && trim($passport) != '') {
			
			$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'Passport' and b.is_active = 'live' and a.value = ?";
			echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'Passport' WHERE a.id IS NULL AND b.is_active= 'live'";
			
			$query = $dbHandle->query($sql, array($passport));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		} else {
			echo $sql = " SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'Passport' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." gender agents";
	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

//function matchBudget($profile)
//{
//	$dbHandle = $this->_loadDatabaseHandle('write');
//	$returnArr = array();
//	
//	foreach ($profile as $k1 => $v1) {
//		
//		$finalArr = (array)$v1;
//		$prefData = (array) $finalArr['PrefData'][0];
//		$budget = $prefData['program_budget'];
//		
//		if (isset($budget) && trim($budget) != '') {
//			
//			$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'Budget' and b.is_active = 'live' and a.value = ?";
//			echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'Budget' WHERE a.id IS NULL AND b.is_active= 'live'";
//			
//			$query = $dbHandle->query($sql, array($budget));
//			if ($query->num_rows() > 0) {
//				$i = 0;
//				foreach ($query->result_array() as $row) {
//					$returnArr[$i]['agentid'] = $row['id'];
//					$returnArr[$i]['clientid'] = $row['clientid'];
//					$i++;
//				}
//			}
//		} else {
//			echo $sql = " SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'Budget' WHERE a.id IS NULL AND b.is_active= 'live'";
//			$query = $dbHandle->query($sql);
//			if ($query->num_rows() > 0) {
//				$i = 0;
//				foreach ($query->result_array() as $row) {
//					$returnArr[$i]['agentid'] = $row['searchagentid'];
//					$returnArr[$i]['clientid'] = $row['clientid'];
//					$i++;
//				}
//			}
//		}
//		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." gender agents";
//	}
//	//print_r($returnArr);
//	$this->dumpmatchingArray($returnArr);
//	return $returnArr;
//}

function matchPlanToStart($profile)
{
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	
	foreach ($profile as $k1 => $v1) {
		
		$finalArr = (array)$v1;
		$prefData = (array) $finalArr['PrefData'][0];
		if($prefData['YearOfStart'] >= date('Y',strtotime('+2 year'))){
			$planToStart = 'Later';
		}
		else {
			$planToStart = $prefData['YearOfStart'];
		}
		if (isset($planToStart) && trim($planToStart) != '') {
			
			$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'PlanToStart' and b.is_active = 'live' and a.value = ?";
			echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'PlanToStart' WHERE a.id IS NULL AND b.is_active= 'live'";
			
			$query = $dbHandle->query($sql, array($planToStart));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		} else {
			echo $sql = " SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'PlanToStart' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		error_log("###".print_r($sql,true));
	}
	
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

function matchAbroadSpecialization($profile)
{
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	
	foreach ($profile as $k1 => $v1) {
		
		$finalArr = (array)$v1;
		$prefData = (array) $finalArr['PrefData'][0];
		$abroadSpecialization = $prefData['abroad_subcat_id'];
		
		if (isset($abroadSpecialization) && trim($abroadSpecialization) != '') {
			
			$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'AbroadSpecialization' and b.is_active = 'live' and a.value = ?";
			echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'AbroadSpecialization' WHERE a.id IS NULL AND b.is_active= 'live'";
			
			$query = $dbHandle->query($sql, array($abroadSpecialization));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		} else {
			echo $sql = " SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid AND a.keyname = 'AbroadSpecialization' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." gender agents";
	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}


function matchUGCourse($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	$dbKeyname = 'UG';
	$keyname = 'UGCourse';
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['EducationData'])) {
			$sql = '';
			foreach ($finalArr['EducationData'] as $k2 => $v2) {
				$eduArr = (array)$v2;
				if ($eduArr['Level'] == $dbKeyname) {
					$name = $eduArr['Name'];
					if (isset($name) && trim($name) != '') {
						$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = " . $dbHandle->escape($keyname) . " and b.is_active = 'live' and a.value = " . $dbHandle->escape($name);
						echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON
a.searchAlertId = b.searchagentid AND a.keyname = " . $dbHandle->escape($keyname) . " WHERE a.id IS NULL AND b.is_active= 'live'";
					}
				}
			}
			if (trim($sql) == '') {
				echo $sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid
AND a.keyname = " . $dbHandle->escape($keyname) . " WHERE a.id IS NULL AND b.is_active= 'live'";
			}
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." ".$keyname." agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchUGInstitute($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	$dbKeyname = 'UG';
	$keyname = 'uginstitute';
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['EducationData'])) {
			$sql = '';
			foreach ($finalArr['EducationData'] as $k2 => $v2) {
				$eduArr = (array)$v2;
				if ($eduArr['Level'] == $dbKeyname) {
					$name = $eduArr['InstituteId'];
					if (isset($name) && trim($name) != '') {
						$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = " . $dbHandle->escape($keyname) . " and b.is_active = 'live' and a.value = " . $dbHandle->escape($name);
						echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON
a.searchAlertId = b.searchagentid AND a.keyname = " . $dbHandle->escape($keyname) . " WHERE a.id IS NULL AND b.is_active= 'live'";
					}
				}
			}
			if (trim($sql) == '') {
				echo $sql = "SELECT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid
AND a.keyname = " . $dbHandle->escape($keyname) . " WHERE a.id IS NULL AND b.is_active= 'live'";
			}
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." ".$keyname." agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchXIIStream($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	$dbKeyname = '12';
	$keyname = 'XIIStream';
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['EducationData'])) {
			$sql = '';
			foreach ($finalArr['EducationData'] as $k2 => $v2) {
				$eduArr = (array)$v2;
				if ($eduArr['Level'] == $dbKeyname) {
					$name = $eduArr['Name'];
					if (isset($name) && trim($name) != '') {
						$sql = "select distinct searchAlertId as id, clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = " . $dbHandle->escape($keyname) . " and b.is_active = 'live' and a.value = " . $dbHandle->escape($name);
						echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON
a.searchAlertId = b.searchagentid AND a.keyname = " . $dbHandle->escape($keyname) . " WHERE a.id IS NULL AND b.is_active= 'live'";
					}
				}
			}
			if (trim($sql) == '') {
				echo $sql = "SELECT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid
AND a.keyname = " . $dbHandle->escape($keyname) . " WHERE a.id IS NULL AND b.is_active= 'live'";
			}
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." ".$keyname." agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchExp($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		$exp = $finalArr['experience'];
		if (isset($exp) && trim($exp) != '') {
			$sql = "select distinct searchAlertId as id, clientid from SARangedSearchCriteria a,   SASearchAgent b where a.searchAlertId = b.searchagentid and a.keyname =
'exp' and a.is_active = 'live' and b.is_active = 'live' and rangeStart <= ? and rangeEnd > ?";
			echo $sql.= " union SELECT b.searchagentid as id , clientid FROM SASearchAgent b LEFT JOIN SARangedSearchCriteria a ON
a.searchAlertId = b.searchagentid AND a.keyname = 'exp' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql, array($exp, $exp));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['id'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		} else {
			echo $sql = " SELECT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SARangedSearchCriteria a ON a.searchAlertId = b.searchagentid AND
a.keyname = 'exp' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i]['agentid'] = $row['searchagentid'];
					$returnArr[$i]['clientid'] = $row['clientid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." Exp agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

function matchCurrentLocation($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		$city = $finalArr['city'];
		$locality = $finalArr['Locality'];
		if (isset($city) && trim($city) != '') {
			$sql = "select distinct searchAlertId , clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'currentlocation' and b.is_active = 'live' and a.value = ?";
			echo $sql.= " union SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId = b.searchagentid
AND a.keyname = 'currentlocation' WHERE a.id IS NULL AND b.is_active= 'live'";
			
			$query = $dbHandle->query($sql, array($city));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i] = $row['searchAlertId'];
					$i++;
				}
				
				$locality_agents = $this->getAllAgentsWithLocality($city);
				if(count($locality_agents)>0) {
					$returnArr = array_diff($returnArr,$locality_agents);
				}
			}
		} else {
			echo $sql = " SELECT DISTINCT b.searchagentid , clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON a.searchAlertId =
b.searchagentid AND a.keyname = 'currentlocation' WHERE a.id IS NULL AND b.is_active= 'live'";
			$query = $dbHandle->query($sql);
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i] = $row['searchagentid'];
					$i++;
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." Current Location agents";

	}
	echo join(',', $returnArr);
	//$this->dumpmatchingArray($returnArr);
	
	return $returnArr;
}

function getAllAgentsWithLocality($city) {

    $dbHandle = $this->_loadDatabaseHandle('write');
    $returnArr = array();
    $this->load->builder('LocationBuilder','location');
    $locationBuilder = new LocationBuilder;
    $location_repo = $locationBuilder->getLocationRepository();
    $localities = $location_repo->getLocalitiesByCity($city);
   
    if(count($localities) >0) {
        $localities_ids = array();
        foreach($localities as $locality) {
            $localities_ids[] = $locality->getId();
        }
        $sql = "select distinct searchAlertId from SAMultiValuedSearchCriteria a , ".
               "SASearchAgent b where  a.searchAlertId = b.searchagentid and ".
               "a.keyname = 'currentlocality' and b.is_active = 'live' AND a.value in (".implode(',', $localities_ids).")";
	
        $query = $dbHandle->query($sql);
        if ($query->num_rows() > 0) {
            $i = 0;
            foreach ($query->result_array() as $row) {
                $returnArr[$i] = $row['searchAlertId'];
                $i++;
            }
        }
    }
    return $returnArr;
}


function matchCurrentLocality($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		$locality = $finalArr['Locality'];
		if (isset($locality) && trim($locality) != '') {
			$sql = "select distinct searchAlertId , clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'currentlocality' and b.is_active = 'live' and a.value = ?";
			echo $sql;
			$query = $dbHandle->query($sql, array($locality));
			if ($query->num_rows() > 0) {
				$i = 0;
				foreach ($query->result_array() as $row) {
					$returnArr[$i] = $row['searchAlertId'];
					$i++;
				}
			}
		}
	}
	echo join(',', $returnArr);
	return $returnArr;
}

function matchCompetition($profileId) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	
	$sql = "SELECT searchagentid, clientid
			FROM SASearchAgent a, SAExamCriteria b, tUserEducation c
			WHERE
			a.searchagentid = b.searchalertid
			AND b.examName = c.Name
			AND c.Level = 'Competitive exam'
			AND c.marks >= b.minScore
			AND c.marks <= b.maxScore
			AND c.CourseCompletionDate = b.passingYear
			AND a.is_active =  'live'
			AND c.userid = ?
			
			UNION
			
			SELECT searchagentid, clientid
			FROM SASearchAgent a, SAExamCriteria b, tUserEducation c
			WHERE
			a.searchagentid = b.searchalertid
			AND b.examName = c.Name
			AND c.Level = 'Competitive exam'
			AND c.marks >= b.minScore
			AND c.marks <= b.maxScore
			AND b.passingYear = '0000-00-00 00:00:00'
			AND a.is_active =  'live'
			AND c.userid = ?
			
			UNION
			
			SELECT searchagentid, clientid
			FROM SASearchAgent a
			LEFT JOIN SAExamCriteria b ON a.searchagentid = b.searchalertid
			WHERE a.is_active =  'live'
			AND b.searchalertid IS NULL";
			
	echo "\n\n COMPTETIVE EXAM SQL \n\n" . $sql . "\n\n";
	$query = $dbHandle->query($sql, array($profileId, $profileId));
	$i = 0;
	if ($query->num_rows() > 0) {
		foreach ($query->result_array() as $row) {
			$returnArr[$i]['agentid'] = $row['searchagentid'];
			$returnArr[$i]['clientid'] = $row['clientid'];
			$i++;
		}
	}
	
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}

function matchPrefLocation($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		
		$isResponseLead = $finalArr['isResponseLead'] == 'YES' ? TRUE : FALSE;
		
		if (is_array($finalArr['PrefData'])) {
			foreach ($finalArr['PrefData'] as $k2 => $v2) {
				$prefArr = (array)$v2;
				$lp = (array)$prefArr['LocationPref'];
				if (count($lp) > 0) {
					$i = 0;
					foreach ($lp as $k3 => $v3) {
						$v3 = (array)$v3;
						if ($v3['LocalityId'] > 0) {
							$sql = "select distinct searchAgentId as id , clientid from `SAPreferedLocationSearchCriteria` a , SASearchAgent b, SAMultiValuedSearchCriteria c where
								a.searchAlertId = b.searchagentid and b.is_active = 'live' and
								c.searchAlertId = b.searchagentid and c.is_active = 'live' and
								((c.value = " . $dbHandle->escape($v3['LocalityId']) . " and c.keyname='Locality') or
								(a.city = " . $dbHandle->escape($v3['CityId']) . " and b.searchAgentId not in (select distinct searchAlertId from SAMultiValuedSearchCriteria where keyname='Locality' and is_active='live')) or
								(a.state = " . $dbHandle->escape($v3['StateId']) . " and b.searchAgentId not in (select distinct searchAlertId from SAMultiValuedSearchCriteria where keyname='Locality' and is_active='live') and a.city = 0) or
								(a.country = " . $dbHandle->escape($v3['CountryId']) . " and b.searchAgentId not in (select distinct searchAlertId from SAMultiValuedSearchCriteria where keyname='Locality' and is_active='live') and a.city = 0 and a.state = 0))";
							
							if(!$isResponseLead) {	
								echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN `SAPreferedLocationSearchCriteria` a ON a.searchAlertId = b.searchagentid WHERE a.id IS NULL  AND b.is_active= 'live'";
							}	
						} else if ($v3['CityId'] > 0) {
							$sql = "select distinct searchAlertId as id , clientid from `SAPreferedLocationSearchCriteria` a , SASearchAgent b where
								a.searchAlertId = b.searchagentid and b.is_active = 'live' and
								(a.city = " . $dbHandle->escape($v3['CityId']) . " or
								(a.state = " . $dbHandle->escape($v3['StateId']) . " and a.locality = 0 and a.city = 0) or
								(a.country = " . $dbHandle->escape($v3['CountryId']) . " and a.locality = 0 and a.city = 0 and a.state = 0))";
								
							if(!$isResponseLead) {	
								echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN `SAPreferedLocationSearchCriteria` a ON a.searchAlertId = b.searchagentid WHERE a.id IS NULL  AND b.is_active= 'live'";
							}
						} else if ($v3['StateId'] > 0) {
							$sql = "select distinct searchAlertId as id , clientid from `SAPreferedLocationSearchCriteria` a , SASearchAgent b where
								a.searchAlertId = b.searchagentid and b.is_active = 'live' and
								(a.state = " . $dbHandle->escape($v3['StateId']) . " or
								(a.country = " . $dbHandle->escape($v3['CountryId']) . " and a.locality = 0 and a.city = 0 and a.state = 0))";
								
							if(!$isResponseLead) {	
								echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN `SAPreferedLocationSearchCriteria` a ON a.searchAlertId = b.searchagentid WHERE a.id IS NULL AND b.is_active= 'live'";
							}
						} else if ($v3['CountryId'] > 0) {
							$sql = "select distinct searchAlertId as id , clientid from `SAPreferedLocationSearchCriteria` a , SASearchAgent b where
								a.searchAlertId = b.searchagentid and b.is_active = 'live' and
								(a.country = " . $dbHandle->escape($v3['CountryId']) . ")";
								
							if(!$isResponseLead) {	
								echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN `SAPreferedLocationSearchCriteria` a ON a.searchAlertId = b.searchagentid WHERE a.id IS NULL and b.is_active= 'live'";
							}
						} else {
							if(!$isResponselead) {
								$sql = "SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN `SAPreferedLocationSearchCriteria` a ON a.searchAlertId = b.searchagentid WHERE a.id IS NULL and b.is_active= 'live'";
							}
						}
						
						if($sql) {
							$query = $dbHandle->query($sql);
							if ($query->num_rows() > 0) {
								foreach ($query->result_array() as $row) {
									$returnArr[$i] = $row['id'];
									$i++;
								}
							}
						}
					}
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." pref location agents";
	}
	echo join(',', $returnArr);
	//$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
function matchSpecialization($profile) {
	$dbHandle = $this->_loadDatabaseHandle('write');
	$returnArr = array();
	foreach ($profile as $k1 => $v1) {
		$finalArr = (array)$v1;
		if (is_array($finalArr['PrefData'])) {
			foreach ($finalArr['PrefData'] as $k2 => $v2) {
				$prefArr = (array)$v2;
				$sp = (array)$prefArr['SpecializationPref'];
				if (count($sp) > 0) {
					$i = 0;
					foreach ($sp as $k3 => $v3) {
						$v3 = (array)$v3;
						if (trim($v3['CourseName']) != '') {
							$sql = "select distinct searchAlertId as id , clientid from SAMultiValuedSearchCriteria a , SASearchAgent b where  a.searchAlertId = b.searchagentid and a.keyname = 'Specialization' and b.is_active = 'live' and a.value = " . $dbHandle->escape($v3['CourseName']);
							echo $sql.= " union SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a
ON a.searchAlertId = b.searchagentid AND a.keyname = 'Specialization' WHERE a.id IS NULL AND b.is_active= 'live'";
						} else {
							echo $sql = " SELECT DISTINCT b.searchagentid as id, clientid FROM SASearchAgent b LEFT JOIN SAMultiValuedSearchCriteria a ON
a.searchAlertId = b.searchagentid AND a.keyname = 'Specialization' WHERE a.id IS NULL AND b.is_active= 'live'";
						}
						$query = $dbHandle->query($sql);
						if ($query->num_rows() > 0) {
							foreach ($query->result_array() as $row) {
								$returnArr[$i]['agentid'] = $row['id'];
								$returnArr[$i]['clientid'] = $row['clientid'];
								$i++;
							}
						}
					}
				}
			}
		}
		//echo "\n".strftime('%c')."INFO: ".$k1." was matched against ".count($returnArr)." specialization agents";

	}
	//print_r($returnArr);
	$this->dumpmatchingArray($returnArr);
	return $returnArr;
}
	/*
	function match12($profile)
	{
	               $dbConfig = array( 'hostname'=>'localhost');
	               $this->load->library('listingconfig');
	               $this->listingconfig->getDbConfig_test($appId,$dbConfig);
	               $dbHandle = $this->load->database($dbConfig,TRUE);
	               $dbKeyname = '12';
	               $returnArr = array();
	                       foreach($profile as $k1=>$v1)
	                       {
	                               $finalArr = (array)$v1;
				if(is_array($finalArr['EducationData']))
				{
					$sql = '';
	                                foreach($finalArr['EducationData'] as $k2=>$v2)
	                                {
		                                $eduArr = (array) $v2;

		                                if($eduArr['Level'] == $dbKeyname)
		                                {
		                                	$marks = $eduArr['Marks'];
							$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b where
							a.searchagentid=b.searchagentid and a.is_active = 'live' and IFNULL(b.minXIIMarksObtained,0) <= '$marks' ";
							$sql.= " UNION SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid =
							b.searchagentid and b.is_active= 'live' and (a.minXIIMarksObtained is NULL or a.minXIIMarksObtained = 0)";
	                                               }
							echo "\n $sql \n";
		                        }
		                        if(trim($sql) == ''){
		                        	echo $sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
						and b.is_active= 'live' and (a.minXIIMarksObtained is NULL or a.minXIIMarksObtained = 0)";
		                        }

		                        $query = $dbHandle->query($sql);
					if ($query->num_rows() > 0)
					{
						$i= 0;
						foreach ($query->result_array() as $row)
						{
							$returnArr[$i]['agentid'] =  $row['id'];
							$returnArr[$i]['clientid'] =  $row['clientid'];
							$i++;
						}
		                        }


		                }
	                       }
		$this->dumpmatchingArray($returnArr);
	               return $returnArr;
	       }


	function matchGrad($profile)
	{
	               $dbConfig = array( 'hostname'=>'localhost');
	               $this->load->library('listingconfig');
	               $this->listingconfig->getDbConfig_test($appId,$dbConfig);
	               $dbHandle = $this->load->database($dbConfig,TRUE);
	               $dbKeyname = 'UG';
	               $returnArr = array();
	                       foreach($profile as $k1=>$v1)
	                       {
	                               $finalArr = (array)$v1;
				if(is_array($finalArr['EducationData']))
				{
					$sql = '';
	                                foreach($finalArr['EducationData'] as $k2=>$v2)
	                                {
		                                $eduArr = (array) $v2;

		                                if($eduArr['Level'] == $dbKeyname)
		                                {
		                                	$marks = $eduArr['Marks'];
							$includeawaited = $eduArr['OngoingCompletedFlag'];
							if($includeawaited > 0){
								$ongoingStatus = 'yes';
							}
							else {
								$ongoingStatus = 'no';
							}
							echo $sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b where
							a.searchagentid=b.searchagentid and a.is_active = 'live' and IFNULL(b.minGradMarksObtained,0) <= '$marks' and
	IFNULL(b.includeResultsAwaited,'no') ='$ongoingStatus'";
						$sql.= " UNION SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
	and b.is_active= 'live' and (a.minGradMarksObtained is NULL or a.minGradMarksObtained = 0)";
	                                               }
		                        }
		                        if(trim($sql) == ''){
		                        	$sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
	and b.is_active= 'live' and (a.minGradMarksObtained is NULL or a.minGradMarksObtained = 0)";
		                        }
					echo "\n $sql \n";
		                        $query = $dbHandle->query($sql);
					if ($query->num_rows() > 0)
					{
						$i= 0;
						foreach ($query->result_array() as $row)
						{
							$returnArr[$i]['agentid'] =  $row['id'];
							$returnArr[$i]['clientid'] =  $row['clientid'];
							$i++;
						}
		                        }
		                }
	                       }
		$this->dumpmatchingArray($returnArr);
	               return $returnArr;
	       }

	function matchGradYears($profile){
	               $dbConfig = array( 'hostname'=>'localhost');
	               $this->load->library('listingconfig');
	               $this->listingconfig->getDbConfig_test($appId,$dbConfig);
	               $dbHandle = $this->load->database($dbConfig,TRUE);
	               $dbKeyname = 'UG';
	               $returnArr = array();
	                       foreach($profile as $k1=>$v1)
	                       {
	                               $finalArr = (array)$v1;
				if(is_array($finalArr['EducationData']))
				{
					$sql = '';
	                                foreach($finalArr['EducationData'] as $k2=>$v2)
	                                {
		                                $eduArr = (array) $v2;

		                                if($eduArr['Level'] == $dbKeyname)
		                                {
		                                	$CourseCompletionDate = !empty($eduArr['CourseCompletionDate'])?$eduArr['CourseCompletionDate']:'0000-00-00 00:00:00';
							$sql = "select a.searchagentid as id, a.clientid as clientid from SASearchAgent a , SASearchAgentBooleanCriteria b where
	a.searchagentid=b.searchagentid and a.is_active = 'live'
	and
	(
	( IFNULL(b.graduationCompletedFrom,'0000-00-00 00:00:00') <= '$CourseCompletionDate') and
	( IFNULL(b.graduationCompletedTo,'0000-00-00 00:00:00') > '$CourseCompletionDate')
	)";
							$sql.= " UNION SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid =b.searchagentid and b.is_active= 'live' and (a.graduationCompletedTo is NULL and a.graduationCompletedFrom is NULL)";
							echo "\n $sql \n";
	                                               }
		                        }
		                        if(trim($sql) == ''){
		                        	echo $sql = "SELECT b.searchagentid as id , clientid FROM SASearchAgent b ,SASearchAgentBooleanCriteria a where a.searchagentid = b.searchagentid
	and b.is_active= 'live' and (a.graduationCompletedTo is NULL and a.graduationCompletedFrom is NULL)";
		                        }
		                        $query = $dbHandle->query($sql);
					if ($query->num_rows() > 0)
					{
						$i= 0;
						foreach ($query->result_array() as $row)
						{
							$returnArr[$i]['agentid'] =  $row['id'];
							$returnArr[$i]['clientid'] =  $row['clientid'];
							$i++;
						}
		                        }


		                }
	                       }

		$this->dumpmatchingArray($returnArr);
	               return $returnArr;
	       }
	*/
    function wsAddDumpMatchingLog($leadid,$sa_array)
    {
        $this->load->model('search_agent_main_model');
        $dbHandle = $this->search_agent_main_model->getDbHandle('write');
        if (!$dbHandle) {
            log_message('error', 'Can not create db handle');
        }
	
        foreach ($sa_array as $result) {
            $array = array();
            $array['leadid'] = $leadid;
            $array['searchAgentid'] = $result['agentid'];
            $array['clientid'] = $result['clientid'];
            $array['matchingTime'] = date("Y-m-d H:i:s");
            $queryCmd = $dbHandle->insert_string('SALeadMatchingLog',$array);
            //error_log('SA :: SALeadMatchingLog SQL ' . $queryCmd);
            $query = $dbHandle->query($queryCmd);
        }
    }
	/**
	 * wsAddSearchAgent API
	 * @access public
	 * @param array
	 * @return string
	 */
	function wsAddSearchAgent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$arr = json_decode($parameters['1'], true);
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'Can not create db handle');
		}
		// check last search agent id
		$queryCmd = 'SELECT searchagentid FROM SASearchAgent ORDER BY searchagentid DESC limit 1';
		$query = $dbHandle->query($queryCmd);
		error_log('SA :: num rows ' . $query->num_rows());
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$searchagentid = $row['searchagentid'] + 1;
			}
		} else {
			$searchagentid = 1;
		}
		error_log('SA :: New search agent id is ' . $searchagentid);
		$arr['search_agent']['searchagentName'] = $arr['search_agent']['searchagentName'] . " (" . $searchagentid . ")";
		$arr['search_agent']['searchagentid'] = $searchagentid;
		// insert search agent
		$arr['search_agent']['created_on'] = date("Y-m-d H:i:s");
		$arr['search_agent']['updated_on'] = date("Y-m-d H:i:s");
		$queryCmd = $dbHandle->insert_string('SASearchAgent', $arr['search_agent']);
		error_log('SA :: New search agent SASearchAgent SQL ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		/* INSERT INTO SALeadsLeftoverStatus START */
		$array_leads = array();
		$array_leads['searchagentid'] = $searchagentid;
		$array_leads['leftover_leads'] = 0;
		$array_leads['leads_sent_today'] = 0;
		$array_leads['last_sent_time'] = date("Y-m-d H:i:s");
		$queryCmd = $dbHandle->insert_string('SALeadsLeftoverStatus', $array_leads);
		error_log('SA :: New search agent SALeadsLeftoverStatus SQL ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		/* INSERT INTO SALeadsLeftoverStatus END */
		// update email ids
		$arr['auto_responder_email']['searchagentid'] = $searchagentid;
		$queryCmd = $dbHandle->insert_string('SASearchAgentAutoResponder_email', $arr['auto_responder_email']);
		error_log('SA :: New search agent SASearchAgentAutoResponder_email SQL ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		// update phone nos
		$arr['auto_responder_sms']['searchagentid'] = $searchagentid;
		$queryCmd = $dbHandle->insert_string('SASearchAgentAutoResponder_sms', $arr['auto_responder_sms']);
		error_log('SA :: New search agent SASearchAgentAutoResponder_sms SQL ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		// Email ids
		if (is_array($arr['contact_details']['email_ids']) && count($arr['contact_details']['email_ids']) > 0) {
			for ($i = 0; $i < count($arr['contact_details']['email_ids']); $i++) {
				$array = array();
				$array['searchagentid'] = $searchagentid;
				$array['contactType'] = 'email';
				$array['contactValue'] = $arr['contact_details']['email_ids'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SASearchAgent_contactDetails', $array);
				error_log('SA :: New search agent SASearchAgent_contactDetails SQL ' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		// Mobile nos
		if (is_array($arr['contact_details']['mobile_nos']) && count($arr['contact_details']['mobile_nos']) > 0) {
			for ($i = 0; $i < count($arr['contact_details']['mobile_nos']); $i++) {
				$array = array();
				$array['searchagentid'] = $searchagentid;
				$array['contactType'] = 'mobile';
				$array['contactValue'] = $arr['contact_details']['mobile_nos'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SASearchAgent_contactDetails', $array);
				error_log('SA :: New search agent SASearchAgent_contactDetails SQL ' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		error_log(":::::::::::::::: SA :::::::::::::::::::::::" . print_r($arr['inputArray'],true));
		//#####################################################
		//######## 	SEARCH FORM START	##############
		//#####################################################

		$search_category_id = $arr['inputArray']['search_category_id'];
		
		if (count($arr['inputArray']['Specialization']) > 0) {
			for ($i = 0; $i < count($arr['inputArray']['Specialization']); $i++) {
				if (!empty($arr['inputArray']['Specialization'][$i])) {
					$array = array();
					$array['searchAlertId'] = $searchagentid;
					$array['keyname'] = 'Specialization';
					$array['value'] = $arr['inputArray']['Specialization'][$i];
					$array['is_active'] = 'live';
					$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
					error_log('SA :: New search agent SQL ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
				}
			}
		}
		
		/**
		 * Abroad Specializations
		 */ 
		if (count($arr['inputArray']['abroadSpecializations']) > 0) {
			for ($i = 0; $i < count($arr['inputArray']['abroadSpecializations']); $i++) {
				if (!empty($arr['inputArray']['abroadSpecializations'][$i])) {
					$array = array();
					$array['searchAlertId'] = $searchagentid;
					$array['keyname'] = 'AbroadSpecialization';
					$array['value'] = $arr['inputArray']['abroadSpecializations'][$i];
					$array['is_active'] = 'live';
					$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
					error_log('SA :: New search agent SQL ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
				}
			}
		}
		
		/**
		 * Budget
		 */ 
		//if (count($arr['inputArray']['budget']) > 0) {
		//	for ($i = 0; $i < count($arr['inputArray']['budget']); $i++) {
		//		if ($arr['inputArray']['budget'][$i] >= 0) {
		//			$array = array();
		//			$array['searchAlertId'] = $searchagentid;
		//			$array['keyname'] = 'Budget';
		//			$array['value'] = $arr['inputArray']['budget'][$i];
		//			$array['is_active'] = 'live';
		//			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
		//			error_log('SA :: New search agent SQL ' . $queryCmd);
		//			$query = $dbHandle->query($queryCmd);
		//		}
		//	}
		//}
		
		/**
		 * Plan to Start
		 */ 
		if (count($arr['inputArray']['planToStart']) > 0) {
			for ($i = 0; $i < count($arr['inputArray']['planToStart']); $i++) {
				if ($arr['inputArray']['planToStart'][$i] >= 0) {
					$array = array();
					$array['searchAlertId'] = $searchagentid;
					$array['keyname'] = 'PlanToStart';
					$array['value'] = $arr['inputArray']['planToStart'][$i];
					$array['is_active'] = 'live';
					$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
					error_log('SA :: New search agent SQL ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
				}
			}
		}
		
		/**
		 * Passport
		 */ 
		if (!empty($arr['inputArray']['passport'])) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'Passport';
			$array['value'] = $arr['inputArray']['passport'];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		
		
		if (is_array($arr['inputArray']['DesiredCourse']) && count($arr['inputArray']['DesiredCourse']) > 0) {
			
			for($y=0;$y<count($arr['inputArray']['DesiredCourse']);$y++) {
				$arr['inputArray']['DesiredCourse'][$y] = $dbHandle->escape_str($arr['inputArray']['DesiredCourse'][$y]);
			}
			
			$csv_str = implode("','", $arr['inputArray']['DesiredCourse']);
			$sql = " SELECT SpecializationId FROM tCourseSpecializationMapping WHERE CourseName IN ( '" .
			$csv_str . "')  AND SpecializationName = 'All' AND CategoryId = ? AND scope='india' AND Status='live'";
			$query = $dbHandle->query($sql, array($search_category_id));
			error_log('SA1 :: New search agent SAMultiValuedSearchCriteria SQL111 ' . $sql);
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$array = array();
					$array['searchAlertId'] = $searchagentid;
					$array['keyname'] = 'desiredcourse';
					$array['value'] = $row['SpecializationId'];
					$array['is_active'] = 'live';
					$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
					error_log('SA1 :: New search agent SAMultiValuedSearchCriteria SQL111 ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
				}
			}
		}
		/* condition will be true where course is coming with spez. in LDB search form */
		elseif(!is_array($arr['inputArray']['DesiredCourse']))
		{
			// get it is mba page or distance mba page
			$csv_str = $dbHandle->escape_str($arr['inputArray']['tab_course_name']);
			// check Specialization shud be empty
                        
                        /* below code commented 
                         * to stop specialization entry 
                         * in case of All checkbox is selected
                         */
			/*if ($arr['inputArray']['Specialization'] == '') {
			  $sql = " SELECT SpecializationId FROM tCourseSpecializationMapping WHERE CourseName ='"
			  . $csv_str . "' AND SpecializationName != 'All' AND CategoryId = '".$search_category_id."' AND
scope='india' AND Status='live'";
				$query = $dbHandle->query($sql);
				error_log('SA1 :: New search agent SAMultiValuedSearchCriteria SQL111 ' . $sql);
				if ($query->num_rows() > 0) {
					foreach ($query->result_array() as $row) {
						$array = array();
						$array['searchAlertId'] = $searchagentid;
						$array['keyname'] = 'Specialization';
						$array['value'] = $row['SpecializationId'];
						$array['is_active'] = 'live';
						$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria',
$array);
error_log('SA1 :: New search agent SAMultiValuedSearchCriteria SQL111 '
. $queryCmd);
						$query = $dbHandle->query($queryCmd);
					}
				}
			}
                         */
			$sql = " SELECT SpecializationId FROM tCourseSpecializationMapping WHERE CourseName ='" .
			$csv_str . "' AND SpecializationName = 'All' AND CategoryId = ? AND scope='india' AND Status='live'";
			$query = $dbHandle->query($sql, array($search_category_id));
			error_log('SA1 :: New search agent SAMultiValuedSearchCriteria SQL111 ' . $sql);
			if ($query->num_rows() > 0) {
				foreach ($query->result_array() as $row) {
					$array = array();
					$array['searchAlertId'] = $searchagentid;
					$array['keyname'] = 'desiredcourse';
					$array['value'] = $row['SpecializationId'];
					$array['is_active'] = 'live';
					$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
					error_log('SA1 :: New search agent SAMultiValuedSearchCriteria SQL111 ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
				}
			}
		}
		// Study Abroad DesiredCourse start
		if (isset($arr['inputArray']['DesiredCourseId']) && count($arr['inputArray']['DesiredCourseId']) > 0) {
			for ($i = 0; $i < count($arr['inputArray']['DesiredCourseId']); $i++) {
				$array = array();
				$array['searchAlertId'] = $searchagentid;
				$array['keyname'] = 'desiredcourse';
				$array['value'] = $arr['inputArray']['DesiredCourseId'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
				error_log('SA :: New search agent SAMultiValuedSearchCriteria SQL ' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		// Study Abroad DesiredCourse end
		
		// Client Course start
		if (isset($arr['inputArray']['course_id']) && count($arr['inputArray']['course_id']) > 0) {
			for ($i = 0; $i < count($arr['inputArray']['course_id']); $i++) {
				$array = array();
				$array['searchAlertId'] = $searchagentid;
				$array['keyname'] = 'clientcourse';
				$array['value'] = $arr['inputArray']['course_id'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
				error_log('SA :: New search agent SAMultiValuedSearchCriteria SQL ' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		// Client Course end
		for ($i = 0; $i < count($arr['inputArray']['Locality']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'Locality';
			$array['value'] = $arr['inputArray']['Locality'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SAMultiValuedSearchCriteria SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		for ($i = 0; $i < count($arr['inputArray']['UGCourse']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'UGCourse';
			$array['value'] = $arr['inputArray']['UGCourse'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		/* Range Search Agent start */
		if (isset($arr['inputArray']['MinAge']) || isset($arr['inputArray']['MaxAge'])) {
			$minAge = isset($arr['inputArray']['MinAge']) ? $arr['inputArray']['MinAge'] : 0;
			$maxAge = isset($arr['inputArray']['MaxAge']) ? $arr['inputArray']['MaxAge'] : 99;
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'age';
			$array['rangeStart'] = (int)$minAge;
			$array['rangeEnd'] = (int)$maxAge;
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SARangedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		if (isset($arr['inputArray']['MinExp']) || isset($arr['inputArray']['MaxExp'])) {
			$minExp = isset($arr['inputArray']['MinExp']) ? $arr['inputArray']['MinExp'] : 0;
			$maxExp = isset($arr['inputArray']['MaxExp']) ? $arr['inputArray']['MaxExp'] : 99;
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'exp';
			$array['rangeStart'] = (int)$minExp;
			$array['rangeEnd'] = (int)$maxExp;
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SARangedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		if (isset($arr['inputArray']['ExamScore'])) {
			foreach ($arr['inputArray']['ExamScore'] as $key => $value) {
				$max_exm_value = !empty($value['max']) ? $value['max'] : 99999;
				$array = array();
				$array['searchAlertId'] = $searchagentid;
				$array['examName'] = $key;
				$array['minScore'] = (int)$value['min'];
				$array['maxScore'] = $max_exm_value;
				$array['passingYear'] = $value['year'] ? $value['year'] : '0000-00-00 00:00:00';
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SAExamCriteria', $array);
				error_log('SA :: New search agent SQL ' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		/* Range Search Agent end */
		// 		if (!empty($arr['inputArray']['GraduationCompletedFrom']) && !empty($arr['inputArray']['GraduationCompletedTo']))
		// 		{
		// 			$array = array();
		// 			$array['searchAlertId'] = $searchagentid;
		// 			$array['keyname'] = 'GraduationCompleted';
		// 			$array['rangeStart'] = $arr['inputArray']['GraduationCompletedFrom'];
		// 			$array['rangeEnd'] =  $arr['inputArray']['GraduationCompletedTo'];
		// 			$array['is_active'] = 'live';
		// 			$queryCmd = $dbHandle->insert_string('SARangedSearchCriteria',$array);
		// 			error_log('SA :: New search agent SQL '.$queryCmd);
		// 			$query = $dbHandle->query($queryCmd);
		// 		}

		if ((isset($arr['inputArray']['XIICompletedFrom'])) || (isset($arr['inputArray']['XIICompletedTo'])))		{
				$array = array();
				$array['searchAlertId'] = $searchagentid;
				$array['keyname'] = 'XIICompleted';
				$array['rangeStart'] = $arr['inputArray']['XIICompletedFrom'] != '' ? date("Y",strtotime($arr['inputArray']['XIICompletedFrom'])) : 0;
				$array['rangeEnd'] =  $arr['inputArray']['XIICompletedTo'] != '' ? date("Y",strtotime($arr['inputArray']['XIICompletedTo'])) : 0;
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SARangedSearchCriteria',$array);
				error_log('SA :: New search agent SQL '.$queryCmd);
				$query = $dbHandle->query($queryCmd);
		}
		for ($i = 0; $i < count($arr['inputArray']['XIIStream']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'XIIStream';
			$array['value'] = $arr['inputArray']['XIIStream'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		for ($i = 0; $i < count($arr['inputArray']['Gender']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'Gender';
			$array['value'] = $arr['inputArray']['Gender'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		for ($i = 0; $i < count($arr['inputArray']['CurrentLocation']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'currentlocation';
			$array['value'] = $arr['inputArray']['CurrentLocation'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		for ($i = 0; $i < count($arr['inputArray']['CurrentCities']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'currentlocation';
			$array['value'] = $arr['inputArray']['CurrentCities'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		for ($i = 0; $i < count($arr['inputArray']['currentLocalities']); $i++) {
			for ($j = 0; $j < count($arr['inputArray']['currentLocalities'][$i]); $j++) {
				if(isset($arr['inputArray']['currentLocalities'][$i][$j])) {
					$array = array();
					$array['searchAlertId'] = $searchagentid;
					$array['keyname'] = 'currentlocality';
					$array['value'] = $arr['inputArray']['currentLocalities'][$i][$j];
					$array['is_active'] = 'live';
					$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
					error_log('SA :: New search agent SQL ' . $queryCmd);
					$query = $dbHandle->query($queryCmd);
				}
			}
		}
		for ($i = 0; $i < count($arr['inputArray']['UGInstitute']); $i++) {
			$array = array();
			$array['searchAlertId'] = $searchagentid;
			$array['keyname'] = 'uginstitute';
			$array['value'] = $arr['inputArray']['UGInstitute'][$i];
			$array['is_active'] = 'live';
			$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
			error_log('SA :: New search agent SQL ' . $queryCmd);
			$query = $dbHandle->query($queryCmd);
		}
		$MinXIIMarks = !empty($arr['inputArray']['MinXIIMarks']) ? $arr['inputArray']['MinXIIMarks'] : NULL;
		$locationandor = ($arr['inputArray']['LocationAndOr'] == '1') ? 'or' : 'and';
		$includeResultsAwaited = ($arr['inputArray']['IncludeResultsAwaited'][0] == '1') ? 'yes' : NULL;
		$minGradMarksObtained = !empty($arr['inputArray']['MinGradMarks']) ? $arr['inputArray']['MinGradMarks'] : NULL;
		$minXIIMarksObtained = !empty($arr['inputArray']['MinXIIMarks']) ? $arr['inputArray']['MinXIIMarks'] : NULL;
		$userFundsOwn = ($arr['inputArray']['UserFundsOwn'] == 'yes') ? 'yes' : NULL;
		$userFundsBank = ($arr['inputArray']['UserFundsBank'] == 'yes') ? 'yes' : NULL;
		$userFundsNone = ($arr['inputArray']['UserFundsNone'] == 'yes') ? 'yes' : NULL;
		$degreePrefAny = ($arr['inputArray']['DegreePrefAny'] == 'yes') ? 'yes' : NULL;
		$degreePrefAICTE = ($arr['inputArray']['DegreePrefAICTE'] == 'yes') ? 'yes' : NULL;
		$degreePrefUGC = ($arr['inputArray']['DegreePrefUGC'] == 'yes') ? 'yes' : NULL;
		$degreePrefInternational = ($arr['inputArray']['DegreePrefInternational'] == 'yes') ? 'yes' : NULL;
		$graduationCompletedFrom = !empty($arr['inputArray']['GraduationCompletedFrom']) ? $arr['inputArray']['GraduationCompletedFrom'] : NULL;
		$graduationCompletedTo = !empty($arr['inputArray']['GraduationCompletedTo']) ? $arr['inputArray']['GraduationCompletedTo'] : NULL;
		$modeFullTime = ($arr['inputArray']['ModeFullTime'] == 'yes') ? 'yes' : NULL;
		$modePartTime = ($arr['inputArray']['ModePartTime'] == 'yes') ? 'yes' : NULL;
		$date_filter = !empty($arr['inputArray']['DateFilter']) ? $arr['inputArray']['DateFilter'] : NULL;
		$includeActiveUsers = $arr['inputArray']['includeActiveUsers'] ? 'yes' : 'no';
		/* boolean start */
		$array = array();
		$array['searchagentid'] = $searchagentid;
		$array['locationandor'] = $locationandor;
		$array['includeResultsAwaited'] = $includeResultsAwaited;
		$array['minGradMarksObtained'] = $minGradMarksObtained;
		$array['minXIIMarksObtained'] = $minXIIMarksObtained;
		$array['userFundsOwn'] = $userFundsOwn;
		$array['userFundsBank'] = $userFundsBank;
		$array['userFundsNone'] = $userFundsNone;
		$array['degreePrefAny'] = $degreePrefAny;
		$array['degreePrefAICTE'] = $degreePrefAICTE;
		$array['degreePrefUGC'] = $degreePrefUGC;
		$array['degreePrefInternational'] = $degreePrefInternational;
		$array['graduationCompletedFrom'] = $graduationCompletedFrom;
		$array['graduationCompletedTo'] = $graduationCompletedTo;
		$array['modeFullTime'] = $modeFullTime;
		$array['modePartTime'] = $modePartTime;
		$array['date_filter'] = $date_filter;
		$array['is_active'] = 'live';
		$array['includeActiveUsers'] = $includeActiveUsers;
		error_log('SA :: boolean ' . print_r($array, true));
		$queryCmd = $dbHandle->insert_string('SASearchAgentBooleanCriteria', $array);
		error_log('SA :: boolean SASearchAgentBooleanCriteria SQL' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		/* boolean end */
		if (isset($arr['inputArray']['PreferredLocation'])) {
			for ($i = 0; $i < count($arr['inputArray']['PreferredLocation']); $i++) {
				$tmp_array = json_decode(base64_decode($arr['inputArray']['PreferredLocation'][$i]) , true);
				$city = $tmp_array['cityId'];
				$state = $tmp_array['stateId'];
				$country = $tmp_array['countryId'];
				$array = array();
				$array['searchAlertId'] = $searchagentid;
				$array['locality'] = 0;
				$array['city'] = $city;
				$array['state'] = $state;
				$array['country'] = $country;
				$array['location_type'] = 'preferred';
				$array['is_active'] = 'live';
				error_log('SA :: pref location ' . print_r($array, true));
				$queryCmd = $dbHandle->insert_string('SAPreferedLocationSearchCriteria', $array);
				error_log('SA :: pref SAPreferedLocationSearchCriteria sql' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		/* add test prep array if exist */
		if (isset($arr['inputArray']['ExtraFlag']) && ($arr['inputArray']['ExtraFlag'] == 'testprep')) {
			for ($i = 0; $i < count($arr['inputArray']['testPrep_blogid']); $i++) {
				$array = array();
				$array['searchAlertId'] = $searchagentid;
				$array['keyname'] = 'testprep';
				$array['value'] = $arr['inputArray']['testPrep_blogid'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SAMultiValuedSearchCriteria', $array);
				error_log('SA :: New search agent SAMultiValuedSearchCriteria TESTPREP SQL ' . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		//#####################################################
		//######## 	SEARCH FORM END		##############
		//#####################################################
		/* insert SA extra info. */
		$array = array();
		$array['searchagentid'] = $searchagentid;
		$array['displaydata'] = $arr['search_agent_data']['SA_displayData_Search_Agent'];
		$array['inputdata'] = $arr['search_agent_data']['SA_inputData_Search_Agent'];
		$array['inputhtml'] = $arr['search_agent_data']['SA_inputHTMLData_Search_Agent'];
		$array['updateTime'] = date("Y-m-d H:i:s");
		$array['is_active'] = 'live';
		$queryCmd = $dbHandle->insert_string('SASearchAgentDisplayData', $array);
		error_log('SA :: New search agent SASearchAgentDisplayData SQL ' . $queryCmd);
		$query = $dbHandle->query($queryCmd);
		//return $this->xmlrpc->send_error_message('123', 'done');
		$response = array(
			array(
				'status' => 'success'
			) ,
			'struct'
		);
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * wsgetAllSearchAgents API
	 * @access public
	 * @param array
	 * @return string
	 */
	function wsgetAllSearchAgents($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$deliveryMethod = $parameters['2'];
		//error_log('SA :: input array '.print_r($parameters,true));
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = array();
		$sql = "SELECT searchagentid , searchagentName, type FROM SASearchAgent
			WHERE clientid = ? AND is_active='live' AND deliveryMethod = ? order by searchagentName";
		$query = $dbHandle->query($sql, array($clientId, $deliveryMethod));
		if ($query->num_rows() > 0) {
			foreach ($query->result_array() as $row) {
				$results[] = array(
					$row['searchagentid'],
					$row['searchagentName'],
					$row['type']
				);
			}
		}
		//error_log('SA :: Result Array '.print_r($results,true));
		$response = array(
			base64_encode(json_encode($results)) ,
			'string'
		);
		return $this->xmlrpc->send_response($response);
	}
	/**
	 * wsgetAllDataSearchAgents API
	 * @access public
	 * @param array
	 * @return string
	 */
	function wsgetAllDataSearchAgents($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = array();
		$query = $dbHandle->query('SELECT * FROM SASearchAgent,SASearchAgentAutoResponder_email,SASearchAgentAutoResponder_sms,SASearchAgent_contactDetails WHERE SASearchAgentAutoResponder_email.searchagentid = SASearchAgent.searchagentid AND
		SASearchAgentAutoResponder_sms.searchagentid = SASearchAgent.searchagentid AND
		SASearchAgent_contactDetails.searchagentid = SASearchAgent.searchagentid
		AND SASearchAgent.clientid = ? AND SASearchAgent.is_active = "live"', array($clientId));
		$results = $query->result_array();
		error_log('SA :: Result Array ' . print_r($results, true));
		$response = array(
			base64_encode(json_encode($results)) ,
			'string'
		);
		return $this->xmlrpc->send_response($response);
	}


	function SADeliveryOptions($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$emailFreq = $parameters['1'];
		$emailFreqType = "DELIVERY_" . strtoupper($emailFreq);
		$lastProcessedId = - 1;
		$today = date("d-m-Y");
		//connect DB
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			echo('event SADeliveryOptions can not create db handle');
		}
		$this->load->library('LDB_Client');
		$ldbClient = new LDB_Client();
		$this->load->library('Alerts_client');
		$alerts_client = new Alerts_client();
		$this->load->library('SearchAgents_client');
		$SAClientObj = new SearchAgents_client();
		       
		//if email_freq is 'asap' i.e. cron is 'asap'
		if ($emailFreq == 'asap') {
			$queryCmd = "select distinct sla.id, sla.userid, sla.agentid, sla.auto_download, sla.auto_responder_email, sla.auto_responder_sms,  sla.email_sent, sla.sms_sent, sa.searchagentname, sa.clientid, sa.type, CONCAT(u.firstname,' ',u.lastname) as firstname, sa.leads_daily_limit, sa.price_per_lead, sa.pref_lead_type, sa.email_freq, sa.sms_freq, sa.is_active,lac.lastprocessedid, sla.allocationtime, los.leads_sent_today, los.leads_sent_today_email, los.leads_sent_today_sms, los.leftover_leads from  SALeadAllocation sla, SASearchAgent sa, SALeadAllocationCron lac, tuser u, SALeadsLeftoverStatus los where sla.agentid=sa.searchagentid and u.userid=sa.clientid and sa.searchagentid=los.searchagentid and lac.process=? and (sa.email_freq is NULL or sa.email_freq=? or sa.sms_freq=?) and lac.status='OFF' and sla.id>lac.lastprocessedid and datediff(sla.allocationtime,current_date)>=0 and sa.is_active='live' order by sla.id";
		} else {
			$queryCmd = "select distinct sla.id, sla.userid, sla.agentid, sla.auto_download, sla.auto_responder_email, sla.auto_responder_sms,  sla.email_sent, sla.sms_sent, sa.searchagentname, sa.clientid, sa.type, CONCAT(u.firstname,' ',u.lastname) as firstname, sa.leads_daily_limit, sa.price_per_lead, sa.pref_lead_type, sa.email_freq, sa.sms_freq, sa.is_active,lac.lastprocessedid, sla.allocationtime, los.leads_sent_today, los.leads_sent_today_email, los.leads_sent_today_sms, los.leftover_leads from  SALeadAllocation sla, SASearchAgent sa, SALeadAllocationCron lac, tuser u, SALeadsLeftoverStatus los where sla.agentid=sa.searchagentid and u.userid=sa.clientid and sa.searchagentid=los.searchagentid and lac.process=? and (sa.email_freq=? or sa.sms_freq=?) and lac.status='OFF' and sla.id>lac.lastprocessedid and datediff(sla.allocationtime,current_date)>=0 and sa.is_active='live' order by sla.id";
		}
		$SADetailsArray = array();
		$query = $dbHandle->query($queryCmd, array($emailFreqType, $emailFreq, $emailFreq));
		foreach ($query->result() as $row) {
			array_push($SADetailsArray, array(
				'id' => $row->id,
				'user_id' => $row->userid,
				'agent_id' => $row->agentid,
				'agent_type' => $row->type,
				'auto_download' => $row->auto_download,
				'auto_responder_email' => $row->auto_responder_email,
				'auto_responder_sms' => $row->auto_responder_sms,
				'email_sent' => $row->email_sent,
				'sms_sent' => $row->sms_sent,
				'agent_name' => $row->searchagentname,
				'client_id' => $row->clientid,
				'client_name' => $row->firstname,
				'leads_daily_limit' => $row->leads_daily_limit,
				'price_per_lead' => $row->price_per_lead,
				'pref_lead_type' => $row->pref_lead_type,
				'email_freq' => $row->email_freq,
				'sms_freq' => $row->sms_freq,
				'is_active' => $row->is_active,
				'lastprocessedid' => $row->lastprocessedid,
				'leads_sent_today' => $row->leads_sent_today,
				'leads_sent_today_email' => $row->leads_sent_today_email,
				'leads_sent_today_sms' => $row->leads_sent_today_sms,
				'leftover_leads' => $row->leftover_leads
			)); //close array_push
			$lastProcessedId = $row->id;
		}
		error_log("####SADetailsArray".print_r($SADetailsArray,true));
		//declared variable which will be used to calculate leads sent to all SA for a clinet
		$leadsSentToAllAgents = 0;
		//declared variable to track email or sms sending frequency
		$send_sms_mail = 'NO';
		$uniqueArray = array();
		$leadsData = array();
		//push unique search agent ids
		foreach ($SADetailsArray as $temp) {
			array_push($uniqueArray, $temp['agent_id']);
		}
		$uniqueArray = array_unique($uniqueArray);
		//for each unique search agent
		foreach ($uniqueArray as $agent_id_temp) { //1st For Loop
			
			$leadsArrayADEmail = array();
			$leadsArrayADSms = array();
			$idsArrayADEmail = array();
			$idsArrayADSms = array();
			$leadDetailsArrayEmail = array();
			
			foreach ($SADetailsArray as $tempArray) {
				if ($tempArray['agent_id'] == $agent_id_temp) {
					$searchAgentId = $tempArray['agent_id'];
					$searchAgentName = $tempArray['agent_name'];
					$searchAgentType = $tempArray['agent_type'];
					$SAAutoDownload = $tempArray['auto_download'];
					$email_sent = $tempArray['email_sent'];
					$sms_sent = $tempArray['sms_sent'];
					$clientId = $tempArray['client_id'];
					$clientName = $tempArray['client_name'];
					$leadId = $tempArray['user_id'];
					$id = $tempArray['id'];
					$email_freq = $tempArray['email_freq'];
					$sms_freq = $tempArray['sms_freq'];
					$leads_sent_today_email = $tempArray['leads_sent_today_email'];
					$leads_sent_today_sms = $tempArray['leads_sent_today_sms'];
					echo("lead id is as " . $leadId);
					//push all leads for auto download
					if ($SAAutoDownload == 'YES' && $email_freq == $emailFreq && $email_sent == 'NO') {
						array_push($leadsArrayADEmail, $leadId);
						array_push($idsArrayADEmail, $id);
					}
					if ($SAAutoDownload == 'YES' && $sms_freq == $emailFreq && $sms_sent == 'NO') {
						array_push($leadsArrayADSms, $leadId);
						array_push($idsArrayADSms, $id);
					}
					//if daily limit reached
					if ($tempArray['leads_sent_today'] >= ($tempArray['leads_daily_limit'] + $tempArray['leftover_leads'])) {
						$dailyQuota = 'reached';
					} else {
						$dailyQuota = 'unreached';
					}
					//if global limit reached
					//commented below code for bugzilla 40327
					/*if($tempArray['leads_sent_today']>=5000){
					$globalQuota='reached';
					}*/
					//aded below code for bugzilla 40327 to implement global quota
					$globalQuotaQuery = "SELECT leads_sent_today FROM SALeadsLeftoverStatus WHERE searchagentid in (select searchagentid from SASearchAgent where clientid=? and is_active='live') and  (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(last_sent_time))<'86400'";
					echo('globalquota query' . $globalQuotaQuery);
					$globalQuotaResult = $dbHandle->query($globalQuotaQuery, array($clientId));
					foreach ($globalQuotaResult->result() as $row) {
						$leadsSentToAllAgents = $leadsSentToAllAgents + $row->leads_sent_today;
					}
					//error_log('lead sent to all agents'.$leadsSentToAllAgents);
					if ($leadsSentToAllAgents >= 1500) {
						$globalQuota = 'reached';
						//query to check entry into SA_GLOBALQUOTA_SMS_MAIL_SETTINGS table if it is not done for a client
						$select_query = 'select * from SA_GLOBALQUOTA_SMS_MAIL_SETTINGS where client_id=?';
						$result = $dbHandle->query($select_query, array($clientId));
						$result = $result->result();
						if (!empty($result)) {
							//query to pickup client who has got limit mail or sms 24 hours back
							$select_query = 'select * from SA_GLOBALQUOTA_SMS_MAIL_SETTINGS where (UNIX_TIMESTAMP()-UNIX_TIMESTAMP(sms_email_sent_time))>"86400" and client_id=?';
							$result = $dbHandle->query($select_query, array($clientId));
							$result = $result->result();
							if (!empty($result)) {
								//error_log('result is'.print_r($result,true));
								//Update entry for the client
								$dbHandle->query('update SA_GLOBALQUOTA_SMS_MAIL_SETTINGS set sms_email_sent ="YES", sms_email_sent_time=CURRENT_TIMESTAMP where client_id=?', array($clientId));
								$send_sms_mail = 'YES';
							}
						} else {
							//insert new row for the client who has not yet received global quota limit mail or sms
							$dbHandle->query('INSERT INTO SA_GLOBALQUOTA_SMS_MAIL_SETTINGS (client_id, sms_email_sent, sms_email_sent_time) VALUES (?, "YES", CURRENT_TIMESTAMP)', array($clientId));
							$send_sms_mail = 'YES';
						}
					} else {
						$globalQuota = 'unreached';
					}
				}
			}
			//convert all into CSV string
			$leadsArrayADCSVEmail = implode(",", $leadsArrayADEmail);
			$leadsArrayADCSVSms = implode(",", $leadsArrayADSms);
			$idsArrayADCSVEmail = implode(",", $idsArrayADEmail);
			$idsArrayADCSVSms = implode(",", $idsArrayADSms);
				
			$search_agents_display_array = $SAClientObj->getSADisplayData($appId, $agent_id_temp);
			$inputArray = json_decode(base64_decode($search_agents_display_array[0]['inputdata']), true);
			$displayArray = json_decode(base64_decode($search_agents_display_array[0]['displaydata']), true);
			if(count($inputArray['course_id'])) {
				$dataArrayMR = array();
				$dataArrayMR['courses'] = $inputArray['course_id'];
				$dataArrayMR['startDate'] = date('Y-m-d',strtotime('-1 Month'));
				$dataArrayMR['endDate'] = date('Y-m-d');
			}
			    
			if(count($dataArrayMR['courses'] && !empty($dataArrayMR['startDate']) && !empty($dataArrayMR['endDate']))) {	    
				$responseData = modules::run('lms/lmsServer/getMatchedResponses', $dataArrayMR['courses'], array(), $dataArrayMR['startDate'], $dataArrayMR['endDate'], FALSE);
				$responseUsers = $responseData['users'];
				$matchedCourses = $responseData['courses'];
			}
				
			if (!empty($leadsArrayADCSVEmail)) {
				//get all leads details for all leads of auto download
				$userCompleteDetailsADEmail = $ldbClient->sgetUserDetails(1, $leadsArrayADCSVEmail);
				$leadDetailsADEmail = json_decode($userCompleteDetailsADEmail, true);
				//formatize leads details array
				if($searchAgentType == 'response') {
					$leadDetailsArrayEmail = $this->createUserDataArray($leadDetailsADEmail, $responseUsers, $displayArray);
				}
				else {
					$leadDetailsArrayEmail = $this->createUserDataArray($leadDetailsADEmail);
				}
				//count of leads for auto download
				$countEmail = count($leadDetailsArrayEmail);
			}
			if (!empty($leadsArrayADCSVSms)) {
				//get all leads details for all leads of auto download
				$userCompleteDetailsADSms = $ldbClient->sgetUserDetails(1, $leadsArrayADCSVSms);
				$leadDetailsADSms = json_decode($userCompleteDetailsADSms, true);
				//formatize leads details array
				$leadDetailsArraySms = $this->createUserDataArray($leadDetailsADSms);
				//count of leads for auto download
				$countSms = count($leadDetailsArraySms);
			}
			//get list of columns for CSV
			$ColumnList = $this->getColumnList($csvType, $searchAgentType);
				
			//set all data to array $data
			$data['count'] = $countEmail;
			$data['ColumnList'] = $ColumnList;
			$data['leadDetailsArray'] = $leadDetailsArrayEmail;
			$data['userpasswordemail'] = "shiksha";
			$data['searchAgentName'] = $searchAgentName;
			$data['searchAgentType'] = $searchAgentType;
			$data['clientName'] = $clientName;
			//set content for email
			if ($emailFreq == 'asap') {
				$subject = "$searchAgentName: New leads from Shiksha.com";
				$content = $this->load->view("search/searchMail", array(
					'contentArray' => $data,
					'type' => 'deliveryOptionsasap'
				) , true);
			}
			if ($emailFreq != 'asap' && $count <= 3) {
				$subject = "$searchAgentName: New leads from Shiksha.com";
				$content = $this->load->view("search/searchMail", array(
					'contentArray' => $data,
					'type' => 'deliveryOptions'
				) , true);
			}
			if ($emailFreq != 'asap' && $count > 3) {
				$subject = "$searchAgentName: New leads from Shiksha.com";
				$content = $this->load->view("search/searchMail", array(
					'contentArray' => $data,
					'type' => 'delOptionsMailCSV'
				) , true);
				//file name generated for CSV
				$csvName = "SearchAgent-" . substr($searchAgentName, 0, 7) . "-" . $agent_id_temp . "-" . $today . ".csv";
				//convert into CSV
				$leadsContentCSVEmail = $this->downloadCSVForActivity($leadsArrayADCSVEmail, $csvName, $searchAgentType, $responseUsers, $displayArray);
				//create attachment
				$attachmentResponse = $alerts_client->createAttachment("12", 0, "course", "E-Brochure", "$leadsContentCSVEmail", $csvName, "E-Brochure");
				$attachmentResponse = $alerts_client->getAttachmentId("12", 0, "course", "E-Brochure", $csvName);

				foreach ($attachmentResponse as $tempy) {
					$attachmentId = $tempy['id'];
				}
				$attachmentArray = array();
				array_push($attachmentArray, $attachmentId);
			}
			$data['content'] = $content;
			//get all contact details of unique search agent
			$clientDetailsArray = array();
			$queryCmd = "select searchagentid, contactType, contactValue from SASearchAgent_contactDetails where searchAgentId=? and is_active='live'";

			$query = $dbHandle->query($queryCmd, array($agent_id_temp));
			foreach ($query->result() as $row) {
				array_push($clientDetailsArray, array(
					'searchagentid' => $row->searchagentid,
					'contactType' => $row->contactType,
					'contactValue' => $row->contactValue
				)); //close array_push

			}
			//in case of no contact details found for unique search agent
			if (empty($clientDetailsArray)) {
				array_push($clientDetailsArray, array(
					'searchagentid' => $agent_id_temp,
					'contactType' => 'email',
					'contactValue' => "info@shiksha.com"
				)); //close array_push
				array_push($clientDetailsArray, array(
					'searchagentid' => $agent_id_temp,
					'contactType' => 'mobile',
					'contactValue' => "TD-SHIKSHA"
				)); //close array_push

			}
			$flagEmail = true;
			$flagSMS = true;
			foreach ($clientDetailsArray as $tempDetails) {
				if ($tempDetails['contactType'] == 'email') {
					//			if($globalQuota!='reached'){
					if ($emailFreq == 'asap') {
						//send mails for auto download
						if (!empty($leadsArrayADCSVEmail)) {
							$response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subject, $content, $contentType = "html");
						}

					}
					if ($emailFreq != 'asap' && $count <= 3) {
						if (!empty($leadsArrayADCSVEmail)) {
							$response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subject, $content, $contentType = "html");
						}

					}
					if ($emailFreq != 'asap' && $count > 3) {
						error_log("attachmentArray is as " . print_r($attachmentArray, true));
						if (!empty($leadsArrayADCSVEmail)) {
							$response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subject, $content, $contentType = "html", '', 'y', $attachmentArray);
						}

					}
					if ($globalQuota == 'reached' && $send_sms_mail == 'YES') {
						$subjectGQMail = "Your maximum quota of leads has reached.";
						$contentGQMail = $this->load->view("search/searchMail", array(
							'contentArray' => $data,
							'type' => 'globalQuota'
						) , true);
						$responseGQMail = $alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subjectGQMail, $contentGQMail, $contentType = "html");
						error_log("hhh mail sent for globalQuota reached to" . $tempDetails['contactValue']);
					}
					if (!empty($leadsArrayADCSVEmail)) {
						$queryCmd = "update SALeadAllocation set email_sent='YES' where agentid=".$dbHandle->escape($agent_id_temp)." and id in($idsArrayADCSVEmail)";
						$query = $dbHandle->query($queryCmd);
					}
				} else if ($tempDetails['contactType'] == 'mobile') {
					//			if($globalQuota!='reached'){
					//send sms
					if ($count <= 3) {
						foreach ($leadDetailsArraySms as $tempLeads) {
      
							$contentOfSms = "Lead- " . $tempLeads['Mobile'] . ", " . substr($tempLeads['Email'],0,35) . ", " . substr($tempLeads['FirstName'],0,50). "," . substr($tempLeads['Desired Course'],0,50);
							$contentOfSms = $contentOfSms;
							if (!empty($leadsArrayADCSVSms)) {
								$responseOfSms = $alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentOfSms, "$clientId");
							}
						}
					} else {
						$contentOfSms = $count . " new leads matching your search agent: " . $agent_id_temp . " have been sent to your registered email. ";
						if (!empty($leadsArrayADCSVSms)) {
							$responseOfSms = $alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentOfSms, "$clientId");
						}
					}
					if ($globalQuota == 'reached' && $send_sms_mail == 'YES') {
						$contentOfSmsGQ = "Daily quota of auto download of leads has reached. You would be able to get more leads in your email tomorrow.";
						$responseOfSms = $alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentOfSmsGQ, "$clientId");

					}
					if (!empty($leadsArrayADCSVSms)) {
						$queryCmd = "update SALeadAllocation set sms_sent='YES' where agentid=".$dbHandle->escape($agent_id_temp)." and id in($idsArrayADCSVSms)";
						$query = $dbHandle->query($queryCmd);
					}
				}
				if ($dailyQuota == 'reached') {
					//added condition !empty($leadsArrayADEmail) to check whether AD is set or not, for bugzilla 40289
					if (!empty($leadsArrayADEmail) && $tempDetails['contactType'] == 'email') {
						$subjectDQMail = "Daily limit of leads as set by you for Search Agent $searchAgentName has reached";
						$contentDQMail = $this->load->view("search/searchMail", array(
							'contentArray' => $data,
							'type' => 'dailyQuota'
						) , true);
						//uncommented below code for bugzilla 40289
						$responseDQMail = $alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subjectDQMail, $contentDQMail, $contentType = "html");

					}
					//added condition !empty($leadsArrayADSms) to check whether AD is set or not, for bugzilla 40227
					if (!empty($leadsArrayADSms) && $tempDetails['contactType'] == 'mobile') {
						$contentOfSmsDQ = "Daily limit of leads as set by you for Search Agent $agent_id_temp has reached. You would be able to get more leads in your email tomorrow.";
						//uncommented below code for bugzilla 40227
						$responseOfSms = $alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentOfSmsDQ, "$clientId");

					}
				}
			}
		} //1st for loop
		//update last processed id for particular frequency i.e. 'asap' or 'everyhour'
		if ($lastProcessedId != - 1) {
			$queryCmd = "update SALeadAllocationCron set lastprocessedid=$lastProcessedId where process='$emailFreqType'";
			$query = $dbHandle->query($queryCmd);
		}
	}
	
	function SAAutoResponder($request) {

		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$emailFreqType = "DELIVERY_AUTORESPONDER";
		$lastProcessedId = - 1;
		$today = date("d-m-Y");
		//connect DB
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			error_log('event SADeliveryOptions can not create db handle');
		}
		$this->load->library('LDB_Client');
		$ldbClient = new LDB_Client();
		$this->load->library('Alerts_client');
		$alerts_client = new Alerts_client();
		$queryCmd = "select distinct sla.id, sla.userid, sla.agentid, sla.auto_download, sla.auto_responder_email, sla.auto_responder_sms, sa.searchagentname, sa.clientid, CONCAT(u.firstname,' ',u.lastname) as firstname, sa.leads_daily_limit, sa.price_per_lead, sa.pref_lead_type, sa.email_freq, sa.sms_freq, sa.is_active, sa.flag_auto_download, lac.lastprocessedid, sla.allocationtime, los.leftover_leads, los.leads_sent_today, los.leads_sent_today_email, los.leads_sent_today_sms from  SALeadAllocation sla, SASearchAgent sa, SALeadAllocationCron lac, tuser u, SALeadsLeftoverStatus los where sla.agentid=sa.searchagentid and u.userid=sa.clientid and sa.searchagentid=los.searchagentid and lac.process=? and lac.status='OFF' and sla.id>lac.lastprocessedid and datediff(sla.allocationtime,current_date)>=0 and sa.is_active='live' order by sla.id";

		$SADetailsArray = array();
		$query = $dbHandle->query($queryCmd, array($emailFreqType));
		foreach ($query->result() as $row) {
			array_push($SADetailsArray, array(
				'id' => $row->id,
				'user_id' => $row->userid,
				'agent_id' => $row->agentid,
				'auto_download' => $row->auto_download,
				'auto_responder_email' => $row->auto_responder_email,
				'auto_responder_sms' => $row->auto_responder_sms,
				'agent_name' => $row->searchagentname,
				'client_id' => $row->clientid,
				'client_name' => $row->firstname,
				'leads_daily_limit' => $row->leads_daily_limit,
				'price_per_lead' => $row->price_per_lead,
				'pref_lead_type' => $row->pref_lead_type,
				'email_freq' => $row->email_freq,
				'sms_freq' => $row->sms_freq,
				'is_active' => $row->is_active,
				'flag_auto_download' => $row->flag_auto_download,
				'lastprocessedid' => $row->lastprocessedid,
				'leftover_leads' => $row->leftover_leads,
				'leads_sent_today' => $row->leads_sent_today,
				'leads_sent_today_email' => $row->leads_sent_today_email,
				'leads_sent_today_sms' => $row->leads_sent_today_sms
			)); //close array_push
			$lastProcessedId = $row->id;
		}
		$uniqueArray = array();
		$leadsData = array();
		//push unique search agent ids
		foreach ($SADetailsArray as $temp) {
			array_push($uniqueArray, $temp['agent_id']);
		}
		$uniqueArray = array_unique($uniqueArray);

		//for each unique search agent
		foreach ($uniqueArray as $agent_id_temp) { //1st For Loop

			$leadsArrayAD = array();
			$leadsArrayARE = array();
			$leadsArrayARS = array();
			$idsArrayAD = array();
			$idsArrayARE = array();
			$idsArrayARS = array();
			$leadDetailsArray = array();
			foreach ($SADetailsArray as $tempArray) {
				if ($tempArray['agent_id'] == $agent_id_temp) {
					$searchAgentName = $tempArray['agent_name'];
					$SAAutoDownload = $tempArray['auto_download'];
					$SAAutoResponderEmail = $tempArray['auto_responder_email'];
					$SAAutoResponderSms = $tempArray['auto_responder_sms'];
					$clientId = $tempArray['client_id'];
					$clientName = $tempArray['client_name'];
					$leadId = $tempArray['user_id'];
					$id = $tempArray['id'];
					$leftover_leads = $tempArray['leftover_leads'];
					$flag_auto_download = $tempArray['flag_auto_download'];
					$leads_daily_limit = 0;
					if ($flag_auto_download == 'live') {
						$leads_daily_limit = $tempArray['leads_daily_limit'] + $leftover_leads;
					}
					$leads_sent_today = $tempArray['leads_sent_today'];
					$leads_sent_today_email = $tempArray['leads_sent_today_email'];
					$leads_sent_today_sms = $tempArray['leads_sent_today_sms'];
					//$auto_download_limit = $tempArray['leads_daily_limit'];
					error_log("lead id is as " . $leadId);
					//push all leads for auto responder email
					if ($SAAutoResponderEmail == 'YES') {
						array_push($leadsArrayARE, $leadId);
						array_push($idsArrayARE, $id);
					}
					//push all leads for auto responder sms
					if ($SAAutoResponderSms == 'YES') {
						array_push($leadsArrayARS, $leadId);
						array_push($idsArrayARS, $id);
					}
					//if daily limit reached
					//uncommented below code for bugzilla 40289
					if ($tempArray['leads_sent_today'] >= $tempArray['leads_daily_limit']) {
						$dailyQuota = 'reached';
					} else {
						$dailyQuota = 'unreached';
					}
					//if global limit reached
					if ($tempArray['leads_sent_today'] >= 5000) {
						$globalQuota = 'reached';
					} else {
						$globalQuota = 'unreached';
					}
				}
			}
			//convert all into CSV string
			//		$leadsArrayADCSV=implode(",",$leadsArrayAD);
			$leadsArrayARECSV = implode(",", $leadsArrayARE);
			$leadsArrayARSCSV = implode(",", $leadsArrayARS);
			//		$idsArrayADCSV=implode(",",$idsArrayAD);
			$idsArrayARECSV = implode(",", $idsArrayARE);
			$idsArrayARSCSV = implode(",", $idsArrayARS);
			//similarly for leads of auto responder email
			if (!empty($leadsArrayARECSV)) {
				$userCompleteDetailsARE = $ldbClient->sgetUserDetails(1, $leadsArrayARECSV);
				$leadDetailsARE = json_decode($userCompleteDetailsARE, true);
				$leadDetailsAREArray = $this->createUserDataArray($leadDetailsARE);
			}
			//similarly for leads of auto responder sms
			if (!empty($leadsArrayARSCSV)) {
				$userCompleteDetailsARS = $ldbClient->sgetUserDetails(1, $leadsArrayARSCSV);
				$leadDetailsARS = json_decode($userCompleteDetailsARS, true);
				$leadDetailsARSArray = $this->createUserDataArray($leadDetailsARS);
			}
			//set content for email
			$data['searchAgentName'] = $searchAgentName;
			$data['clientName'] = $clientName;
			//get all contact details of unique search agent
			$clientDetailsArray = array();
			$queryCmd = "select searchagentid, contactType, contactValue from SASearchAgent_contactDetails where searchAgentId=? and is_active='live'";

			$query = $dbHandle->query($queryCmd, array($agent_id_temp));
			foreach ($query->result() as $row) {
				array_push($clientDetailsArray, array(
					'searchagentid' => $row->searchagentid,
					'contactType' => $row->contactType,
					'contactValue' => $row->contactValue
				)); //close array_push

			}
			//in case of no contact details found for unique search agent
			if (empty($clientDetailsArray)) {
				array_push($clientDetailsArray, array(
					'searchagentid' => $agent_id_temp,
					'contactType' => 'email',
					'contactValue' => "info@shiksha.com"
				)); //close array_push
				array_push($clientDetailsArray, array(
					'searchagentid' => $agent_id_temp,
					'contactType' => 'mobile',
					'contactValue' => "TD-SHIKSHA"
				)); //close array_push

			}
			$flagEmail = true;
			$flagSMS = true;
			foreach ($clientDetailsArray as $tempDetails) {
				if ($flagEmail) {
					//get all data for auto responder email
					$queryCmd = "select msg,daily_limit,subject,from_emailid,from_name from SASearchAgentAutoResponder_email where searchagentid=? and is_active='live'";
					error_log("searchagentid is as " . $tempDetails['searchagentid']);
					$autoResponderEmail = array();
					$autoResponderEmail = $dbHandle->query($queryCmd, array($agent_id_temp));
					foreach ($autoResponderEmail->result_array() as $autoResponderEmailRow) {
						$daily_limit_Email = $autoResponderEmailRow['daily_limit'];
						//uncommented below if condition for bugzilla 40289
						//	if($daily_limit_Email>$leads_sent_today_email){
						foreach ($leadDetailsAREArray as $tempLeads) {
							$msg_Email = $autoResponderEmailRow['msg'];
							$subject_Email = $autoResponderEmailRow['subject'];
							$from_emailid = $autoResponderEmailRow['from_emailid'];
							$from_name = $autoResponderEmailRow['from_name'];
							$tempTempFirstname = $tempLeads['Name'];
							if ($tempDetails['contactType'] == 'email') {
								$subject_Email = str_replace("@NAME", $tempTempFirstname, $subject_Email);
								$msg_Email = str_replace("@NAME", $tempTempFirstname, $msg_Email);
								$responseAREmail = $alerts_client->externalQueueAdd("12", strtolower($from_emailid) , strtolower($tempLeads['Email']) , $subject_Email, $msg_Email, $contentType = "html", '', '', '', '', '', $from_name);

								//update flags for auto responder email
								if (!empty($leadsArrayARECSV)) {
									$queryCmd = "update SALeadAllocation set auto_responder_email_sent='YES' where agentid=".$dbHandle->escape($agent_id_temp)." and id in($idsArrayARECSV)";
                                    $query = $dbHandle->query($queryCmd);
								}

								//$query = $dbHandle->query($queryCmd);
								//added below code for bugzilla 42899
								$flagEmail = false;
							}
							//uncommented below brace for bugzilla 40289
							//	}

						}
					}
					//commented below code for bugzilla 40289
					//$flagEmail=false;

				} //If Auto Responder Email is set to YES
				//added condition isset($daily_limit_Email) and !empty($leadDetailsAREArray) for bugzilla 40289
				if (!empty($leadDetailsAREArray) && isset($daily_limit_Email) && $daily_limit_Email != 0 && ($leads_sent_today_email + $leads_sent_today >= $daily_limit_Email + $leads_daily_limit)) {
					if ($tempDetails['contactType'] == 'email') {
						$subjectDQMailMail = "Daily limit of auto responder email for Search Agent $searchAgentName has reached";
						$contentDQMailMail = $this->load->view("search/searchMail", array(
							'contentArray' => $data,
							'type' => 'dailyQuotaMail'
						) , true);
						//uncommented below code for bugzilla 40289
						$alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subjectDQMailMail, $contentDQMailMail, $contentType = "html");

					}
					
					
					if ($tempDetails['contactType'] == 'mobile') {
												
						$contentDQMailSms = "Daily limit of auto responder email for Search Agent $agent_id_temp has reached. You would be able to contact more students via email tomorrow.";						//uncommented below code for bugzilla 40289
						$alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentDQMailSms, "$clientId");
					}
					
				}
				//get all data for auto responder sms
				if ($flagSMS) {
					$queryCmd = "select msg,daily_limit from SASearchAgentAutoResponder_sms where searchagentid=? and is_active='live'";

					$autoResponderSMS = array();
					$autoResponderSMS = $dbHandle->query($queryCmd, array($agent_id_temp));
					foreach ($autoResponderSMS->result_array() as $autoResponderSMSRow) {
						$daily_limit_SMS = $autoResponderSMSRow['daily_limit'];
						foreach ($leadDetailsARSArray as $tempLeads) {
							$msg_SMS = $autoResponderSMSRow['msg'];
							$tempTempFirstnameMobile = $tempLeads['Name'];
							if ($tempDetails['contactType'] == 'mobile') {
								$msg_SMS = str_replace("@NAME", $tempTempFirstnameMobile, $msg_SMS);
								$responseARSms = $alerts_client->addSmsQueueRecord("12", $tempLeads['Mobile'], $msg_SMS, "$clientId","","user-defined");

								//update all flags for auto responder sms
								if (!empty($leadsArrayARSCSV)) {
									$queryCmd = "update SALeadAllocation set auto_responder_sms_sent='YES' where agentid=$agent_id_temp and id in($idsArrayARSCSV)";
                                    $query = $dbHandle->query($queryCmd);
								}

								//$query = $dbHandle->query($queryCmd);
								//commented below code for bugzilla 40289
								$flagSMS = false;
							}
						}
					}
				} //If Auto Responder SMS is set to YES
				//added condition isset($daily_limit_SMS) and !empty($leadDetailsARSArray) for bugzilla 40289
				if (!empty($leadDetailsARSArray) && isset($daily_limit_SMS) && $daily_limit_SMS != 0 && ($leads_sent_today_sms + $leads_sent_today >= $daily_limit_SMS + $leads_daily_limit)) {
					if ($tempDetails['contactType'] == 'email') {
						$subjectDQSmsMail = "Daily limit of auto responder sms as set by you for Search Agent $searchAgentName has reached";
						$contentDQSmsMail = $this->load->view("search/searchMail", array(
							'contentArray' => $data,
							'type' => 'dailyQuotaSms'
						) , true);
						//uncommented below brace for bugzilla 40289
						$alerts_client->externalQueueAdd("12", "info@shiksha.com", strtolower($tempDetails['contactValue']) , $subjectDQSmsMail, $contentDQSmsMail, $contentType = "html");

					}
					if ($tempDetails['contactType'] == 'mobile') {
						$contentDQSmsSms = "Daily limit of auto responder sms as set by you for Search Agent $agent_id_temp has
reached. You would be able to contact more students via sms tomorrow."; 
						//uncommented below brace for bugzilla 40289
						$alerts_client->addSmsQueueRecord("12", $tempDetails['contactValue'], $contentDQSmsSms, "$clientId");

					}
				}
			}
		} //1st for loop
		//update last processed id for particular frequency i.e. 'asap' or 'everyhour'

		if ($lastProcessedId != - 1) {
			$queryCmd = "update SALeadAllocationCron set lastprocessedid=".$dbHandle->escape($lastProcessedId)." where process=".$dbHandle->escape($emailFreqType);
		

		$query = $dbHandle->query($queryCmd);
        } 
	}
	
	/**
	 * It generates MIS report for search agents, after generating report it inserts data in tMailQueue
	 *
	 * @return	void
	 */
	function SAMISReportGenerator() {
		//load search agent main model
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		//How many leads were allocated to agents within 24 hours query
		$query_to_get_leads_allocated = 'SELECT count(distinct userid) as count_leads from SALeadAllocation where TIMEDIFF(now(),allocationtime)<time("23:59:59")';
		error_log('query is' . $query_to_get_leads_allocated);
		$query = $dbHandle->query($query_to_get_leads_allocated);
		$result = $query->result();
		$count_leads_allocated = $result['0']->count_leads;
		error_log('result came as ' . $count_leads_allocated);
		//How many agents had leads allocated to them within 24 hours query
		$query_to_get_agents = 'SELECT count(distinct agentid) as count_agents from SALeadAllocation where TIMEDIFF(now(),allocationtime)<time("23:59:59")';
		error_log('query is' . $query_to_get_agents);
		$query = $dbHandle->query($query_to_get_agents);
		$result = $query->result();
		$count_agents = $result['0']->count_agents;
		error_log('result came as ' . $count_agents);
		//How many agents had leads not allocated to them within 24 hours query
		$query_to_get_agents_leads_not_allocated = 'select count(searchagentid) as count_agents from SASearchAgent where is_active="live" and searchagentid not in (select distinct agentid from SALeadAllocation where TIMEDIFF(now(),allocationtime) < time("23:59:59"))';
		error_log('query is' . $query_to_get_agents_leads_not_allocated);
		$query = $dbHandle->query($query_to_get_agents_leads_not_allocated);
		$result = $query->result();
		$count_agents_didnt_get_lead = $result['0']->count_agents;
		error_log('result came as ' . $count_agents_didnt_get_lead);
		//How many agents had leads delivered to them within 24 hours query
		$query_to_get_agents_leads_delivered = 'SELECT count(distinct agentid) as count_agents from SALeadAllocation where TIMEDIFF(now(),allocationtime)<time("23:59:59") and ((sms_sent="YES" and email_sent="YES" and auto_download="YES" and auto_responder_email="YES" and auto_responder_sms="YES" and auto_responder_email_sent="YES" and auto_responder_sms_sent="YES") or (sms_sent="YES" and email_sent="YES" and auto_download="YES" and auto_responder_email="NO" and auto_responder_sms="NO" and auto_responder_email_sent="NO" and auto_responder_sms_sent="NO") or (sms_sent="NO" and email_sent="NO" and auto_download="NO" and auto_responder_email="YES" and auto_responder_sms="YES" and auto_responder_email_sent="YES" and auto_responder_sms_sent="YES"))';
		error_log('query is' . $query_to_get_agents_leads_delivered);
		$query = $dbHandle->query($query_to_get_agents_leads_delivered);
		$result = $query->result();
		$count_agents_lead_delivered = $result['0']->count_agents;
		error_log('result came as ' . $count_agents_lead_delivered);
		$subject_mis_email = 'Daily email to get MIS report for search agent';
		$content_mis_email = 'Hi,<br/>
		Please find below the MIS report of the day<br/>' . '1. Count of leads allocated to agents within 24 hours: ' . $count_leads_allocated . '<br/>' . '2. Count of search agents who has leads allocated to them within 24 hours: ' . $count_agents . '<br/>' . '3. Count of search agents who has not allocated any leads within 24 hour: ' . $count_agents_didnt_get_lead . '<br/>' . '4. Count of search agents whose leads are delivered to them within 24 hours: ' . $count_agents_lead_delivered . '<br/>' . '<br/>
		Thanks and Regards<br/>
		info@shiksha.com
		';
		$this->load->library('Alerts_client');
		$alerts_client = new Alerts_client();
		$email_ids_array = array(
			'aditya.roshan@shiksha.com',
			'abhishek.jain@naukri.com',
			'sachin.singhal@brijj.com',
			'saurabh.gupta@shiksha.com',
			'ravi.raj@shiksha.com',
			'pankaj.thareja@shiksha.com'
		);
		$count_ids = count($email_ids_array);
		for ($email_id_index = 0; $email_id_index < $count_ids; $email_id_index++) {
			$alerts_client->externalQueueAdd("12", "info@shiksha.com", $email_ids_array[$email_id_index], $subject_mis_email, $content_mis_email, $contentType = "html");
		}
	}
	function wsupdateSearchAgentField($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$fieldName = $parameters['2'];
		$fieldvalue = json_decode($parameters['3'], true);
		$actiontype = $parameters['4'];
		$fieldtype = $parameters['5'];
		$clientId = $parameters['6'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
			return $this->xmlrpc->send_error_message('123', 'can not create db handle');
		}
		if ($actiontype == 'update') {
			if ($fieldName == 'email') {
				if (count($fieldvalue) > 15) {
					return $this->xmlrpc->send_error_message('123', 'email ids can not be more than 15');
				}
				$queryCmd = "SELECT SASearchAgent_contactDetails.id,contactValue
					FROM SASearchAgent_contactDetails , SASearchAgent
					WHERE SASearchAgent_contactDetails.is_active = 'live'
					AND SASearchAgent_contactDetails.contactType = 'email'
					AND SASearchAgent_contactDetails.searchagentid = SASearchAgent.searchagentid
					AND SASearchAgent.clientid =?
					AND SASearchAgent_contactDetails.searchagentid =?";
				$resultSet = $dbHandle->query($queryCmd, array($clientId, $sa_id));
				if (count($resultSet->result_array()) > 0) {
					foreach ($resultSet->result_array() as $result) {
						$queryCmd = "UPDATE SASearchAgent_contactDetails SET is_active = 'history' WHERE id =?";
						$dbHandle->query($queryCmd, array($result['id']));
						error_log(" HHHH query 1" . $queryCmd);
					}
					foreach ($fieldvalue as $value) {
						$array = array();
						$array['searchagentid'] = $sa_id;
						$array['contactType'] = 'email';
						$array['is_active'] = 'live';
						$array['contactValue'] = $value;
						$queryCmd = $dbHandle->insert_string('SASearchAgent_contactDetails', $array);
						error_log(" HHHH query 1" . $queryCmd);
						$query = $dbHandle->query($queryCmd);
					}
				}
			}
		}
		$response = array(
			'status' => 'success'
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wsgetAllContactDetails($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$contactType = $parameters['2']; // email or mobile
		if ($contactType != "email" || $contactType != "mobile") {
			$contactType = 'email';
		}
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = array();
		$query = $dbHandle->query("SELECT DISTINCT contactValue
					FROM SASearchAgent_contactDetails, SASearchAgent
					WHERE SASearchAgent_contactDetails.is_active = 'live'
					AND SASearchAgent_contactDetails.contactType = ?
					AND SASearchAgent_contactDetails.searchagentid = SASearchAgent.searchagentid
					AND SASearchAgent.clientid =?", array($contactType, $clientId));
		$results = $query->result_array();
		error_log('SA :: Result Array ' . print_r($results, true));
		$response = array(
			base64_encode(json_encode($results)) ,
			'string'
		);
		return $this->xmlrpc->send_response($response);
	}
	function wsvalidateAgentName($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$searchagentName = $parameters['2'];
		error_log(' HHHH ' . print_r($parameters, true));
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$query = $dbHandle->get_where('SASearchAgent', array(
			'clientid' => $clientId,
			'searchagentName' => $searchagentName,
			'is_active' => 'live'
		));
		if (count($query->result_array()) <= 0) {
			$response = true; //array('status'=>'true');
			
		} else {
			$response = false; //array('status'=>'false');
			
		}
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wsgetSearchAgent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$sa_id = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = array();
		$query = $dbHandle->query('SELECT *, SASearchAgentAutoResponder_email.daily_limit as sa_auto_res_email_limit,SASearchAgentAutoResponder_sms.daily_limit as sa_auto_res_sms_limit, SASearchAgentAutoResponder_email.msg as sa_auto_res_email_msg,SASearchAgentAutoResponder_sms.msg as sa_auto_res_sms_msg FROM SASearchAgent,SASearchAgentAutoResponder_email,SASearchAgentAutoResponder_sms,SASearchAgent_contactDetails WHERE
		SASearchAgentAutoResponder_email.searchagentid = SASearchAgent.searchagentid AND
		SASearchAgentAutoResponder_sms.searchagentid = SASearchAgent.searchagentid AND
		SASearchAgent_contactDetails.searchagentid = SASearchAgent.searchagentid
		AND
		SASearchAgent.clientid = ?
		AND SASearchAgent.is_active = "live"
		AND SASearchAgentAutoResponder_email.is_active = "live"
		AND SASearchAgentAutoResponder_sms.is_active = "live"
		AND SASearchAgent_contactDetails.is_active = "live"
		AND SASearchAgent.searchagentid =? order by SASearchAgent_contactDetails.contactType', array($clientId, $sa_id));
		$results = $query->result_array();
		error_log(' HHHH ' . print_r($parameters, true));
		error_log(' HHHH ' . count($results));
		$response = array(
			base64_encode(json_encode($results)) ,
			'string'
		);
		return $this->xmlrpc->send_response($response);
	}
	function wsgetAllCreditToConsumedDataForSearchAgents($searchAgentIds, $userId) {
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$appId = 1;
		$ExtraFlag = 'false';
		$query = $dbHandle->query("SELECT ExtraFlag FROM tUserPref where UserId=? AND  Status = 'live'", array($userId));
		foreach ($query->result() as $row) {
			$ExtraFlagStr = $row->ExtraFlag;
		}
		if ($ExtraFlagStr == 'testprep') {
			$ExtraFlag = 'true';
		}
		echo "\n Extra Flag is set as :::" . $ExtraFlag . "\n";
		$results = array();
		$this->load->library('LDB_Client');
		$objLDBClient = new LDB_Client();
		$this->load->library('sums_product_client');
		$objSumsProduct = new Sums_Product_client();
		echo "\n INPUT SA IDs \n";
		print_r($searchAgentIds);
		$k = 0;
		foreach ($searchAgentIds as $searchAgentId) {
			$query = $dbHandle->query("SELECT clientid,IFNULL(flag_auto_responder_sms,'history') as flag_auto_responder_sms ,IFNULL(flag_auto_responder_email,'history') as flag_auto_responder_email FROM SASearchAgent where is_active='live' and searchagentid=?",array($searchAgentId));
			foreach ($query->result_array() as $row) {
				$clientId = $row['clientid'];
				$flag_auto_responder_sms = $row['flag_auto_responder_sms'];
				$flag_auto_responder_email = $row['flag_auto_responder_email'];
				$view = $objLDBClient->sgetCreditToConsume(1, array(
					$userId
				) , 'view', $clientId, $ExtraFlag);
				$email = $objLDBClient->sgetCreditToConsume(1, array(
					$userId
				) , 'email', $clientId, $ExtraFlag);
				$sms = $objLDBClient->sgetCreditToConsume(1, array(
					$userId
				) , 'sms', $clientId, $ExtraFlag);
				/*
				foreach ($view as $key => $value) {
					$view = (int)$value;
				}
				foreach ($email as $key => $value) {
					$email = (int)$value;
				}
				foreach ($sms as $key => $value) {
					$sms = (int)$value;
				}
				*/
				$view = $view[$userId][$userId]; 
				$email = $email[$userId][$userId]; 
				$sms = $sms[$userId][$userId]; 
				$SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array(
					'userId' => $clientId
				));
				echo "\n Required CRs for view/email/sms \n";
				echo "\n " . $view . '&' . $email . "&" . $sms . "\n";
				$results[$k]['id'] = $searchAgentId;
				$results[$k]['auto_sms'] = 'NO';
				$results[$k]['auto_download'] = 'NO';
				$results[$k]['auto_email'] = 'NO';
				$sumTotal = 0;
				foreach ($SubscriptionArray as $subscription) {
					if ($subscription['BaseProdCategory'] == 'Lead-Search') {
						$sumTotal+= $subscription['BaseProdRemainingQuantity'];
					}
				}
				echo "\n Total base-product \n" . $sumTotal;
				if ((int)$sumTotal < (int)$email) {
					$results[$k]['id'] = $searchAgentId;
					$results[$k]['auto_email'] = 'NO';
				} else {
					$results[$k]['id'] = $searchAgentId;
					$results[$k]['auto_email'] = 'YES';
				}
				if ((int)$sumTotal < (int)$view) {
					$results[$k]['id'] = $searchAgentId;
					$results[$k]['auto_download'] = 'NO';
				} else {
					$results[$k]['id'] = $searchAgentId;
					$results[$k]['auto_download'] = 'YES';
				}
				if ((int)$sumTotal < (int)$sms) {
					$results[$k]['id'] = $searchAgentId;
					$results[$k]['auto_sms'] = 'NO';
				} else {
					$results[$k]['id'] = $searchAgentId;
					$results[$k]['auto_sms'] = 'YES';
				}
				if ($flag_auto_responder_sms == 'history') {
					$results[$k]['auto_sms'] = 'NO';
				}
				if ($flag_auto_responder_email == 'history') {
					$results[$k]['auto_email'] = 'NO';
				}
				$k++;
			}
		}
		echo "\n GET SAs and Validate Required CRs \n";
		sdump($results);
		echo "\n";
		return $results;
	}
	function wsfilterDefaulterSearchAgents($searchAgentIds) {
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = array();
		$this->load->library('sums_product_client');
		$objSumsProduct = new Sums_Product_client();
		foreach ($searchAgentIds as $searchAgentId) {
			$query = $dbHandle->query("SELECT clientid,price_per_lead FROM SASearchAgent where is_active='live' and searchagentid=?",array($searchAgentId));
			foreach ($query->result_array() as $row) {
				$clientId = $row['clientid'];
				$cpl = $row['price_per_lead'];
				$SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array(
					'userId' => $clientId
				));
				$sumTotal = 0;
				foreach ($SubscriptionArray as $subscription) {
					if ($subscription['BaseProdCategory'] == 'Lead-Search') {
						$sumTotal+= $subscription['BaseProdRemainingQuantity'];
					}
				}
				echo "\n" . "Filter SAs's Total Credits are:::" . $sumTotal . "and CPL IS ::::" . $cpl . "\n";
				if ((int)$sumTotal >= (int)$cpl) {
					array_push($results, $searchAgentId);
				}
			}
		}
		return $results;
	}
	function wsgetCreditConsumed($searchagent_id, $userId, $creditfordeduct, $action) {
		$this->load->library('subscription_client');
		$this->load->library('LDB_Client');
		$this->load->library('sums_product_client');
		$objSumsProduct = new Sums_Product_client();
		$subsObj = new Subscription_client();
		$ldbObj = new LDB_Client();
		$appID = 1;
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$query = $dbHandle->query("SELECT clientid FROM SASearchAgent where searchagentid = ? AND is_active = 'live'", array($searchagent_id));
		foreach ($query->result() as $row) {
			$clientId = $row->clientid;
		}
		$ExtraFlag = 'false';
		$query = $dbHandle->query("SELECT ExtraFlag FROM tUserPref where UserId=? AND  Status = 'live'", array($userId));
		foreach ($query->result() as $row) {
			$ExtraFlagStr = $row->ExtraFlag;
		}
		if ($ExtraFlagStr == 'testprep') {
			$ExtraFlag = 'true';
		}
		$userIdList = $userId;
		$contactType = $action;
		$SubscriptionArray = $objSumsProduct->getAllSubscriptionsForUserLDB(1, array(
			'userId' => $clientId
		));
		$objLDBClient = new LDB_Client();
		$creditConsumeArray1 = $objLDBClient->sgetCreditToConsume('12', array(
			$userIdList
		) , $action, $clientId, $ExtraFlag);
		$creditConsumeArray = $creditConsumeArray1[$userId];
		unset($creditConsumeArray['countNdnc']);
		$creditCount = 0;
		$countToConsume = array_sum($creditConsumeArray);
		$subscriptionDetailMapping = array();
		foreach ($creditConsumeArray as $userId => $userCreditdeduct) {
			foreach ($SubscriptionArray as $subscription) {
				if ($subscription['BaseProdCategory'] == 'Lead-Search') {
					$subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProdRemainingQuantity'] = $subscription['BaseProdRemainingQuantity'];
					$subscriptionDetailMapping[$subscription['SubscriptionId']]['BaseProductId'] = $subscription['BaseProductId'];
					$subscriptionDetailMapping[$subscription['SubscriptionId']]['countLeft'] = $subscription['BaseProdRemainingQuantity'] - $subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed'];
					if ($userCreditdeduct <= $subscriptionDetailMapping[$subscription['SubscriptionId']]['countLeft']) {
						$subscriptionDetailMapping[$subscription['SubscriptionId']]['countConsumed']+= $userCreditdeduct;
						$subscriptionDetailMapping[$subscription['SubscriptionId']]['userList'][$userId] = $userCreditdeduct;
						break;
					}
				}
			}
		}
		$sumConsumed = 0;
		$sumTotal = 0;
		foreach ($subscriptionDetailMapping as $subscriptionId => $details) {
			$subscriptionConsumptionArray[] = array(
				'subscriptionId' => $subscriptionId,
				'creditToConsume' => $details['countConsumed'],
				'BaseProductId' => $details['BaseProductId'],
				'userList' => $details['userList']
			);
			$sumConsumed+= $details['countConsumed'];
		}
		foreach ($SubscriptionArray as $subscription) {
			if ($subscription['BaseProdCategory'] == 'Lead-Search') $sumTotal+= $subscription['BaseProdRemainingQuantity'];
		}
		if ($creditfordeduct > $sumTotal) {
			$resultText = "You dont have sufficient Credits for this action";
			echo "\n" . $resultText . "\n";
			return FALSE;
		} else {
			$resultText = "You have $sumTotal Credits. $creditfordeduct credits will be used for this action.";
			echo "\n" . $resultText . "\n";
			$subscriptionList = array(
				'result' => $resultText,
				'subArray' => $subscriptionConsumptionArray,
				'CreditCount' => $sumTotal,
				'CreditsForAction' => $sumConsumed
			);
		}
		if (count($subscriptionList['subArray']) != 0) {
			$returnArray = array();
			foreach ($subscriptionList['subArray'] as $subscription) {
				$returnval = $subsObj->consumeLDBCredits(12, $subscription['subscriptionId'], $creditfordeduct, $clientId, $clientId);
				foreach ($subscription['userList'] as $userId => $creditdeduct) {
					$returnval = $ldbObj->sUpdateContactViewed(12, $clientId, $userId, $contactType, $subscription['subscriptionId'], $creditfordeduct, 'SA');
					$returnArrayElement = json_decode($returnval, true);
					$returnArray[] = $returnArrayElement;
					$returnval = $subsObj->updateSubscriptionLog(12, $subscription['subscriptionId'], $creditfordeduct, $clientId, $clientId, $subscription['BaseProductId'], $returnArrayElement[0]['insertId'], $contactType, date("Y-m-d H:i:s") , date("Y-m-d H:i:s"));
				}
			}
			$status = TRUE;
		} else {
			$status = FALSE;
		}
		return $status;
	}
	function wschangeStatusAutoDownload($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$status = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$query = $dbHandle->get_where('SASearchAgent', array(
			'searchagentid' => $sa_id,
			'is_active' => 'live'
		));
		$result = 'false';
		error_log(" ZZZZ " . count($query->result_array()));
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$data = array(
					'is_active' => 'history'
				);
				$where = "searchagentid = $sa_id AND is_active = 'live'";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				$query1 = $dbHandle->query($str1);
				$row['updated_on'] = date("Y-m-d H:i:s");
				$row['flag_auto_download'] = $status;
				unset($row['id']);
				$str2 = $dbHandle->insert_string('SASearchAgent', $row);
				error_log(" ZZZZ " . $str2);
				$query2 = $dbHandle->query($str2);
				if ($query2) {
					$result = 'true';
				}
			}
		}
		$response = array(
			'status' => $result
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wschangeStatusAutoResponderEmail($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$status = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$query = $dbHandle->get_where('SASearchAgent', array(
			'searchagentid' => $sa_id,
			'is_active' => 'live'
		));
		$result = 'false';
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$data = array(
					'is_active' => 'history'
				);
				$where = "searchagentid = $sa_id AND is_active = 'live'";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				$query1 = $dbHandle->query($str1);
				$row['updated_on'] = date("Y-m-d H:i:s");
				$row['flag_auto_responder_email'] = $status;
				unset($row['id']);
				$str2 = $dbHandle->insert_string('SASearchAgent', $row);
				error_log(" ZZZZ " . $str2);
				$query2 = $dbHandle->query($str2);
				if ($query2) {
					$result = 'true';
				}
			}
		}
		$response = array(
			'status' => $result
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wschangeStatusAutoResponderSMS($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$status = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$query = $dbHandle->get_where('SASearchAgent', array(
			'searchagentid' => $sa_id,
			'is_active' => 'live'
		));
		$result = 'false';
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$data = array(
					'is_active' => 'history'
				);
				$where = "searchagentid = $sa_id AND is_active = 'live'";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				$query1 = $dbHandle->query($str1);
				$row['updated_on'] = date("Y-m-d H:i:s");
				$row['flag_auto_responder_sms'] = $status;
				unset($row['id']);
				$str2 = $dbHandle->insert_string('SASearchAgent', $row);
				error_log(" ZZZZ " . $str2);
				$query2 = $dbHandle->query($str2);
				if ($query2) {
					$result = 'true';
				}
			}
		}
		$response = array(
			'status' => $result
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wsUpdateSearchAgent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$arr = json_decode($parameters['2'], true);
		error_log(" rr_dd wsUpdateSearchAgent " . print_r($parameters, true));
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$searchagentid = $sa_id;
		// always active SA will be edit here
		$query = $dbHandle->get_where('SASearchAgent', array(
			'searchagentid' => $searchagentid,
			'is_active' => 'live'
		));
		// Main SA Table
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$data = array(
					'is_active' => 'history'
				);
				$where = "searchagentid = $sa_id AND is_active = 'live'";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				error_log(" rr_dd wsUpdateSearchAgent update " . $str1);
				$query1 = $dbHandle->query($str1);
				$created_on = $row['created_on'];
			}
			$arr['search_agent']['updated_on'] = date("Y-m-d H:i:s");
			$arr['search_agent']['created_on'] = $created_on;
			$str2 = $dbHandle->insert_string('SASearchAgent', $arr['search_agent']);
			error_log(" rr_dd wsUpdateSearchAgent insert " . $str2);
			$query2 = $dbHandle->query($str2);
		}
		$query = $dbHandle->get_where('SASearchAgentAutoResponder_email', array(
			'searchagentid' => $searchagentid
		));
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$data = array(
					'is_active' => 'history'
				);
				$where = "searchagentid = $sa_id AND is_active = 'live'";
				$str1 = $dbHandle->update_string('SASearchAgentAutoResponder_email', $data, $where);
				$query1 = $dbHandle->query($str1);
			}
			if (is_array($arr['auto_responder_email']) && !empty($arr['auto_responder_email'])) {
				$arr['auto_responder_email']['updated_on'] = date("Y-m-d H:i:s");
				$arr['auto_responder_email']['searchagentid'] = $sa_id;
				$str2 = $dbHandle->insert_string('SASearchAgentAutoResponder_email', $arr['auto_responder_email']);
				error_log(" rr_dd wsUpdateSearchAgent auto_responder email::" . $str2);
				$query2 = $dbHandle->query($str2);
			}
		}
		$query = $dbHandle->get_where('SASearchAgentAutoResponder_sms', array(
			'searchagentid' => $searchagentid
		));
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$data = array(
					'is_active' => 'history'
				);
				$where = "searchagentid = $sa_id AND is_active = 'live'";
				$str1 = $dbHandle->update_string('SASearchAgentAutoResponder_sms', $data, $where);
				$query1 = $dbHandle->query($str1);
			}
			if (is_array($arr['auto_responder_sms']) && !empty($arr['auto_responder_sms'])) {
				$arr['auto_responder_sms']['updated_on'] = date("Y-m-d H:i:s");
				$arr['auto_responder_sms']['searchagentid'] = $sa_id;
				$str2 = $dbHandle->insert_string('SASearchAgentAutoResponder_sms', $arr['auto_responder_sms']);
				error_log(" rr_dd wsUpdateSearchAgent auto_responder SMS::" . $str2);
				$query2 = $dbHandle->query($str2);
			}
		}
		/* Mark all contact details as history START */
		$data = array(
			'is_active' => 'history'
		);
		$where = "searchagentid = $sa_id";
		$str1 = $dbHandle->update_string('SASearchAgent_contactDetails', $data, $where);
		error_log(" rr_dd wsUpdateSearchAgent update all contacts" . $str1);
		$query1 = $dbHandle->query($str1);
		/* Mark all contact details as history END */
		// Email ids
		if (is_array($arr['contact_details']['email_ids']) && count($arr['contact_details']['email_ids']) > 0) {
			for ($i = 0; $i < count($arr['contact_details']['email_ids']); $i++) {
				$array = array();
				$array['searchagentid'] = $searchagentid;
				$array['contactType'] = 'email';
				$array['contactValue'] = $arr['contact_details']['email_ids'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SASearchAgent_contactDetails', $array);
				error_log(" rr_dd wsUpdateSearchAgent update all contacts email" . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		// Mobile nos
		if (is_array($arr['contact_details']['mobile_nos']) && count($arr['contact_details']['mobile_nos']) > 0) {
			for ($i = 0; $i < count($arr['contact_details']['mobile_nos']); $i++) {
				$array = array();
				$array['searchagentid'] = $searchagentid;
				$array['contactType'] = 'mobile';
				$array['contactValue'] = $arr['contact_details']['mobile_nos'][$i];
				$array['is_active'] = 'live';
				$queryCmd = $dbHandle->insert_string('SASearchAgent_contactDetails', $array);
				error_log(" rr_dd wsUpdateSearchAgent update all contacts mobile" . $queryCmd);
				$query = $dbHandle->query($queryCmd);
			}
		}
		$response = array(
			array(
				'status' => 'success'
			) ,
			'struct'
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wschangeStatusSearchAgent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$status = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$query = $dbHandle->query("SELECT * FROM SASearchAgent WHERE searchagentid =? and is_active='live'", array($sa_id));
		$result = 'false';
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$row_id = $row['id'];
				$data = array(
					'is_active' => 'history'
				);
				$where = "id = $row_id";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				error_log(" ZZZZ update" . $str1);
				$query1 = $dbHandle->query($str1);
				$row['updated_on'] = date("Y-m-d H:i:s");
				$row['is_active'] = $status;
				unset($row['id']);
				$str2 = $dbHandle->insert_string('SASearchAgent', $row);
				error_log(" ZZZZ insert" . $str2);
				$query2 = $dbHandle->query($str2);
				if ($query2) {
					$result = 'true';
				}
			}
		}
		$response = array(
			'status' => $result
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wschangeEmailFrequencySearchAgent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$status = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		if ($status == 'asap') {
			$return = 'asap';
		} else {
			$return = 'everyhour';
		}
		$query = $dbHandle->query("SELECT * FROM SASearchAgent WHERE searchagentid =? and is_active='live'", array($sa_id));
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$row_id = $row['id'];
				$data = array(
					'is_active' => 'history'
				);
				$where = "id = $row_id";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				$query1 = $dbHandle->query($str1);
				$row['updated_on'] = date("Y-m-d H:i:s");
				$row['email_freq'] = $status;
				unset($row['id']);
				$str2 = $dbHandle->insert_string('SASearchAgent', $row);
				$query2 = $dbHandle->query($str2);
			}
		}
		$response = array(
			'status' => $return
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wschangeSmsFrequencySearchAgent($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		$status = $parameters['2'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		if ($status == 'asap') {
			$return = 'asap';
		} else {
			$return = 'everyhour';
		}
		$query = $dbHandle->query("SELECT * FROM SASearchAgent WHERE searchagentid =? and is_active='live'", array($sa_id));
		if (count($query->result_array()) > 0) {
			foreach ($query->result_array() as $row) {
				$row_id = $row['id'];
				$data = array(
					'is_active' => 'history'
				);
				$where = "id = $row_id";
				$str1 = $dbHandle->update_string('SASearchAgent', $data, $where);
				$query1 = $dbHandle->query($str1);
				$row['updated_on'] = date("Y-m-d H:i:s");
				$row['sms_freq'] = $status;
				unset($row['id']);
				$str2 = $dbHandle->insert_string('SASearchAgent', $row);
				$query2 = $dbHandle->query($str2);
			}
		}
		$response = array(
			'status' => $return
		);
		return $this->xmlrpc->send_response(json_encode($response));
	}
	function wsgetSADisplayData($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$searchagentid = $parameters['1'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$SADisplayArray = array();
		$queryCmd = "SELECT * FROM SASearchAgentDisplayData where searchagentid=?";
		error_log("queryCmd is as " . $queryCmd);
		$query = $dbHandle->query($queryCmd, array($searchagentid));
		foreach ($query->result() as $row) {
			array_push($SADisplayArray, array(
				array(
					'id' => $row->id,
					'searchagentid' => $row->searchagentid,
					'displaydata' => $row->displaydata,
					'inputdata' => $row->inputdata,
					'inputhtml' => $row->inputhtml,
					'updatedtime' => $row->updatedtime,
					'is_active' => $row->is_active
				) ,
				'struct'
			)); //close array_push

		}
		$response = array(
			$SADisplayArray,
			'struct'
		);
		return $this->xmlrpc->send_response($response);
	}
	function wsSearchAgentsAllDetails($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$clientId = $parameters['1'];
		$startFrom = $parameters['2'];
		$count = $parameters['3'];
		$deliveryMethod = $parameters['4'];
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = $this->search_agent_main_model->SearchAgentsAllDetails($dbHandle, $clientId, $startFrom, $count,$deliveryMethod);
		error_log('SA :: Result Array ' . print_r($results, true));
		$response = array(
			base64_encode(json_encode($results)) ,
			'string'
		);
		return $this->xmlrpc->send_response($response);
	}
	function wsgetSearchAgentDetail($request) {
		$parameters = $request->output_parameters();
		$appId = $parameters['0'];
		$sa_id = $parameters['1'];
		error_log('SA :: Input Array ' . print_r($parameters, true));
		$this->load->model('search_agent_main_model');
		$dbHandle = $this->search_agent_main_model->getDbHandle('write');
		if (!$dbHandle) {
			log_message('error', 'can not create db handle');
		}
		$results = $this->search_agent_main_model->SearchAgentDetail($dbHandle, $sa_id);
		error_log('SA :: Result Array ' . print_r($results, true));
		$response = array(
			base64_encode(json_encode($results)) ,
			'string'
		);
		return $this->xmlrpc->send_response($response);
	}
	function downloadCSVForActivity($leadIds, $filename, $searchAgentType, $responseUsers = NULL, $displayArray = NULL) {
		error_log("inside downloadCSVForActivity **********");
		//        $responseUserDetails=$this->consumeCreditsForActivity($clientId,$activityId);
		$this->load->library('LDB_Client');
		$ldbObj = new LDB_client();
		$UserDetailsArray = $ldbObj->sgetUserDetails(1, $leadIds);
		$UserDataArray = $this->createUserDataArray(json_decode($UserDetailsArray, true), $responseUsers, $displayArray);
		$leads = $UserDataArray;
		$ColumnList = $this->getColumnList($csvType,$searchAgentType);
		$csv = '';
		foreach ($ColumnList as $ColumnName) {
			$csv.= '"' . $ColumnName . '",';
		}
		$csv.= "\n";
		foreach ($leads as $lead) {
			foreach ($ColumnList as $ColumnName) {
				$csv.= '"' . $lead[$ColumnName] . '",';
			}
			$csv.= "\n";
		}
		//        $ldbObj->updateActivityStatus(1,$responseUserDetails['activityId'],'done');
		return $csv;
	}
	
	
	
	function createUserDataArray($UserDetailsArray, $responseUsers = NULL, $displayArray = NULL) {
		error_log("inside createUserDataArray " . print_r($UserDetailsArray, true));
		$LocalCourseArray = array();
		foreach ($UserDetailsArray as $userDetails) {
			$formattedUserDetails = array();
			$formattedUserDetails['FirstName'] = $userDetails['firstname'].' '.$userDetails['lastname'];
			$formattedUserDetails['Name'] = $userDetails['firstname'].' '.$userDetails['lastname'];
			$formattedUserDetails['Gender'] = $userDetails['gender'];
			$formattedUserDetails['Age'] = $userDetails['age'];
			global $studyAbroadPopularCourses;
			if(array_key_exists($userDetails["PrefData"][0]['DesiredCourse'],$studyAbroadPopularCourses)) {
				$formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
			} else {
				$formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'] . " in " . $userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName'];
			}
			//For Study Abroad Desired Course is Desired Course Level
			$formattedUserDetails['Desired Course Level'] = $formattedUserDetails['Desired Course'];
			if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
				$courseLevel = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseLevel1'];
				if($courseLevel == 'UG') {
					$formattedUserDetails['Desired Course Level'] = 'Bachelors';
				}
				if($courseLevel == 'PG') {
					$formattedUserDetails['Desired Course Level'] = 'Masters';
				}
			}
			$formattedUserDetails['Field of Interest'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
			if ($userDetails['PrefData'][0]['ExtraFlag'] == 'testprep') {
				$formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CourseName'];
			} else if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
				global $studyAbroadPopularCourses;
				if($formattedUserDetails['Field of Interest'] == "All") {
					$desiredCourseId = $userDetails["PrefData"][0]['DesiredCourse'];
					if(array_key_exists($desiredCourseId,$studyAbroadPopularCourses)) {
						$formattedUserDetails['Field of Interest'] = $studyAbroadPopularCourses[$desiredCourseId];
					}
					//$formattedUserDetails['Desired Course'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['CategoryName'];
				}
				$formattedUserDetails['Desired Specialization'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName'];
				for($x=1;$x<count($userDetails['PrefData'][0]['SpecializationPref']);$x++)
					$formattedUserDetails['Desired Specialization'] .= ", ".$userDetails['PrefData'][0]['SpecializationPref'][$x]['SpecializationName'];
			} else {
				$formattedUserDetails['Desired Specialization'] = $userDetails['PrefData'][0]['SpecializationPref'][0]['SpecializationName'];
				for($x=1;$x<count($userDetails['PrefData'][0]['SpecializationPref']);$x++)
					$formattedUserDetails['Desired Specialization'] .= ", ".$userDetails['PrefData'][0]['SpecializationPref'][$x]['SpecializationName'];
			}
			//display full time, part time or both based on user selection
			//below modification is done for bug id 40313
			if ((($userDetails['PrefData'][0]['ModeOfEducationFullTime'] == "yes") && ($userDetails['PrefData'][0]['ModeOfEducationPartTime'] == "yes"))) $formattedUserDetails['Mode'] = "Full Time, Part Time";
			else $formattedUserDetails['Mode'] = ($userDetails['PrefData'][0]['ModeOfEducationFullTime'] == "yes") ? "Full Time" : (($userDetails['PrefData'][0]['ModeOfEducationPartTime'] == "yes") ? "Part Time" : "");
			$prefDetails = $userDetails['PrefData'][0];
			$datediff = $this->datediff($prefDetails['TimeOfStart'], $prefDetails['SubmitDate']);
			$formattedUserDetails['Plan to start'] = ($prefDetails['YearOfStart'] != '0000') ? (($datediff != 0) ? "Within " . $datediff : "Immediately") : "Not Sure";
			if($prefDetails['ExtraFlag'] == 'studyabroad') {
				if($prefDetails['YearOfStart'] == date('Y'))
				    $formattedUserDetails['Plan to start'] = 'Current Year';
				else if($prefDetails['YearOfStart'] == date('Y')+1)
				    $formattedUserDetails['Plan to start'] = 'Next Year';
				else if($prefDetails['YearOfStart'] > date('Y')+1)
				    $formattedUserDetails['Plan to start'] = 'Later';
			}	
			$formattedUserDetails['Plan to go'] = $formattedUserDetails['Plan to start'];
			$formattedUserDetails['Work Experience'] = ($userDetails['experience'] > 0) ? ($userDetails['experience'] . " Years") : (($userDetails['experience'] === "0") ? "Less Than 1 Year" : "No Experience");
			//making check if current city is empty then set LocationPref as current city
			//below modification is done for bug id 40228
			if (!empty($userDetails['CurrentCity'])) {
				$formattedUserDetails['Current Location'] = $userDetails['CurrentCity'];
			} else {
				/*$currentCity = '';
				$count = count($userDetails['PrefData'][0]['LocationPref']);
				if ($count >= 1) {
					$currentCity = $currentCity . $userDetails['PrefData'][0]['LocationPref'][0]['CityName'];
					for ($index = 0; $index < $count; $index++) {
						if (($index > 0 && ($userDetails['PrefData'][0]['LocationPref'][$index]['CityId'] != $userDetails['PrefData'][0]['LocationPref'][$index - 1]['CityId']))) {
							$currentCity = $currentCity . ', ';
							$currentCity = $currentCity . $userDetails['PrefData'][0]['LocationPref'][$index]['CityName'];
						}
						$formattedUserDetails['Current Location'] = $currentCity;
						error_log('current city' . $currentCity);
					}
				}*/
			}
			//if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') {
			//	$userBudget = (int)$userDetails['PrefData'][0]['program_budget'];
			//	global $budgetToTextArray;
			//	if(key_exists($userBudget, $budgetToTextArray)) {
			//		$userBudget = $budgetToTextArray[$userBudget];
			//	}
			//	$formattedUserDetails['Budget'] = $userBudget;
			//}
			if (!empty($userDetails['localityName'])) {
				$formattedUserDetails['Current Locality'] = $userDetails['localityName'];
			} 
			$formattedUserDetails['Valid Passport'] = $userDetails['passport'];
			$sourceoffunding = array();
			if ($userDetails['PrefData'][0]['UserFundsOwn'] == "yes") {
				$sourceoffunding[] = "Own";
			}
			if ($userDetails['PrefData'][0]['UserFundsBank'] == "yes") {
				$sourceoffunding[] = "Bank";
			}
			if ($userDetails['PrefData'][0]['UserFundsNone'] == "yes") {
				$sourceoffunding[] = "Other:" . $userDetails['PrefData'][0]['otherFundingDetails'];
			}
			$formattedUserDetails['Source of Funding'] = implode("/", $sourceoffunding);
			$preferenceCallTimeArray = array(
				'0' => 'Do not call',
				'1' => 'Anytime',
				'2' => 'Morning',
				'3' => 'Evening'
			);
			$formattedUserDetails['Preferred Time to call'] = (is_numeric($prefDetails['suitableCallPref'])) ? ($preferenceCallTimeArray[$prefDetails['suitableCallPref']]) : "";
			$i = 0;
			foreach ($userDetails['EducationData'] as $educationData) {
				if ($educationData['Level'] == 'UG') {
					$formattedUserDetails['Graduation Status'] = ($educationData['OngoingCompletedFlag'] == 1) ? "Pursuing" : "Completed";
					$formattedUserDetails['Graduation Course'] = $educationData['Name'];
					$formattedUserDetails['Graduation Marks'] = ($educationData['OngoingCompletedFlag'] == 1) ? "" : $educationData['Marks'];
					list($formattedUserDetails['Graduation Month'], $formattedUserDetails['Graduation Year']) = split(" ", $educationData['Course_CompletionDate']);
				} else if ($educationData['Level'] == '12') {
					$formattedUserDetails['Std XII Stream'] = $educationData['Name'];
					$formattedUserDetails['Std XII Marks'] = $educationData['Marks'];
					$XIICompletionDate = split(" ", $educationData['Course_CompletionDate']);
					$formattedUserDetails['Std XII Year'] = $XIICompletionDate[1];
				} else if ($educationData['Level'] == 'Competitive exam') {
					$examObj = \registration\builders\RegistrationBuilder::getCompetitiveExam($educationData['Name'],$educationData);
					if($formattedUserDetails['Exams Taken']) {
					    $formattedUserDetails['Exams Taken'] .= ', ';
					}
					$formattedUserDetails['Exams Taken'] .= $examObj->displayExam();
				}
			}
			$locationPrefData = $userDetails['PrefData'][0]['LocationPref'];
			$formattedUserDetails['Preferred Locations'] = '';
			$formattedUserDetails['Desired Countries'] = '';
			$specialisationPrefData = $userDetails['PrefData'][0]['SpecializationPref'][0];
			//corrected bad code :)
			$count = count($locationPrefData);
			for ($i = 0; $i < $count; $i++) {
				$key = 'Location Preference ' . ($i + 1);
				//added check for local course
				if ((25 <= $specialisationPrefData['SpecializationId'] && $specialisationPrefData['SpecializationId'] <= 34) || ($specialisationPrefData['CourseReach'] == 'local') || ($specialisationPrefData['CourseName'] == 'Distance BCA') || ($specialisationPrefData['CourseName'] == 'Distance MCA') || ($userDetails['PrefData'][0]['ExtraFlag'] == 'testprep')) {
					$formattedUserDetails[$key] = $locationPrefData[$i]['CityName'];
					if (!empty($locationPrefData[$i]['LocalityName'])) {
						$formattedUserDetails[$key] = $formattedUserDetails[$key] . "-" . $locationPrefData[$i]['LocalityName'];
					}
				}
				$cityName = "";
				if ($locationPrefData[$i]['CityName'] != "" && $locationPrefData[$i]['CityId'] != 0) {
					$cityName = $locationPrefData[$i]['CityName'];
				}
				else if($locationPrefData[$i]['CityId'] == 0 && $locationPrefData[$i]['StateId'] != 0){
					$cityName = "Anywhere in ".$locationPrefData[$i]['StateName'];
				}
				else if($locationPrefData[$i]['CityId'] == 0 && $locationPrefData[$i]['StateId'] == 0 && $locationPrefData[$i]['CountryId'] != 0){
					$cityName = "Anywhere in ".$locationPrefData[$i]['CountryName'];
				}

				if ($i == 0) $formattedUserDetails['Preferred Locations'] = $cityName;
				else $formattedUserDetails['Preferred Locations'].= (($i > 0) ? "," : "") . $cityName;
				if ($userDetails['PrefData'][0]['ExtraFlag'] == 'studyabroad') $formattedUserDetails['Desired Countries'].= (($i > 0) ? "," : "") . $locationPrefData[$i]['CountryName'];
			}
			$formattedUserDetails['User Comments'] = $userDetails['PrefData'][0]['UserDetail'];
			$formattedUserDetails['Created On'] = $userDetails['CreationDate'];
			$formattedUserDetails['Is in NDNC list'] = $userDetails['isNDNC'];
			$formattedUserDetails['Email'] = $userDetails['email'];
			$formattedUserDetails['Mobile'] = $userDetails['mobile'];
			$formattedUserDetails['Last Login Time'] = $userDetails['LastLoginDate'];
			$formattedUserDetails['Extra Flag'] = $userDetails['PrefData'][0]['ExtraFlag'];
			$formattedUserDetails['Response Date'] = ($responseUsers[$userDetails['userid']]['submitDate']) ? date('d M Y',strtotime($responseUsers[$userDetails['userid']]['submitDate'])) : "";
			$responseDetails = ($responseUsers[$userDetails['userid']]['matchedFor']) ? ($responseUsers[$userDetails['userid']]['matchedFor']) : '';
			if(!empty($responseDetails)){
				foreach($responseDetails as $courseId) {
					$matchedCourses[$courseId]['CourseName'] = $displayArray['matchedCourses'][$courseId];
					$matchedCourses[$courseId]['InstituteName'] = $displayArray['matchedCoursesInstitute'][$courseId];
				}
				foreach($matchedCourses as $matchedCourseId=>$matchedCourse) {
					$displayMatchedCourses[] = $matchedCourse['CourseName'].', '.$matchedCourse['InstituteName'];
				}
				$formattedUserDetails['Matched Response For'] = implode('; ',array_values($displayMatchedCourses));
			}
			$LocalCourseArray[] = $formattedUserDetails;
		}
		return $LocalCourseArray;
	}
	
	function getColumnList($csvType = null, $searchAgentType = null) {
		if($searchAgentType == 'response'){
			$columnListArray = array();
			$columnListArray[] = 'Name';
			$columnListArray[] = 'Email';
			$columnListArray[] = 'Mobile';
			$columnListArray[] = 'Matched Response For';
			$columnListArray[] = 'Graduation Year';
			$columnListArray[] = 'Std XII Year';
			$columnListArray[] = 'Exams Taken';
			$columnListArray[] = 'Current Location';
			$columnListArray[] = 'Current Locality';
			$columnListArray[] = 'Response Date';
			$columnListArray[] = 'Is in NDNC list';
		}
		else {
			$columnListArray = array();
			$columnListArray[] = 'Name';
			$columnListArray[] = 'Email';
			$columnListArray[] = 'Mobile';
			$columnListArray[] = 'Gender';
			$columnListArray[] = 'Age';
			$columnListArray[] = 'Field of Interest';
			$columnListArray[] = 'Desired Course';
			$columnListArray[] = 'Desired Specialization';
			$columnListArray[] = 'Mode';
			$columnListArray[] = 'Source of Funding';
			$columnListArray[] = 'Plan to start';
			$columnListArray[] = 'Work Experience';
			$columnListArray[] = 'Graduation Status';
			$columnListArray[] = 'Graduation Course';
			$columnListArray[] = 'Graduation Marks';
			$columnListArray[] = 'Graduation Month';
			$columnListArray[] = 'Graduation Year';
			$columnListArray[] = 'Std XII Stream';
			$columnListArray[] = 'Std XII Marks';
			$columnListArray[] = 'Std XII Year';
			$columnListArray[] = 'Exams Taken';
			$columnListArray[] = 'Current Location';
			$columnListArray[] = 'Preferred Locations';
			$columnListArray[] = 'Desired Countries';
			$columnListArray[] = 'Location Preference 1';
			$columnListArray[] = 'Location Preference 2';
			$columnListArray[] = 'Location Preference 3';
			$columnListArray[] = 'Location Preference 4';
			$columnListArray[] = 'Preferred Time to call';
			$columnListArray[] = 'User Comments';
			$columnListArray[] = 'Created On';
			//$columnListArray[] = 'Budget';
			$columnListArray[] = 'Valid Passport';
			$columnListArray[] = 'Is in NDNC list';
		}
		return $columnListArray;
	}

    function getSearchAgentAlertMail() {

        $this->load->model('search_agent_main_model');
	    $dbHandle = $this->search_agent_main_model->getDbHandle();
        $sql = "SELECT * FROM `SALeadAllocation` ORDER BY `allocationtime` DESC LIMIT 1";
        $content = "";
        $query = $dbHandle->query($sql);

        foreach($query->result() as $row) {
            $content = "Most recent lead allocation: agent id $row->agentid and time is $row->allocationtime";
        }
       
        $this->load->library('Alerts_client');
		$alerts_client = new Alerts_client();
        $response = $alerts_client->externalQueueAdd("12", "info@shiksha.com", "aditya.roshan@shiksha.com" , "searchagentalert", $content, $contentType = "text");                        
    }
}
/* End of file searchAgents_Server.php */
/* Location: ./system/application/controllers/searchAgents/searchAgents_Server.php */
