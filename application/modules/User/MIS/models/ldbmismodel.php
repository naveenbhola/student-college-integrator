<?php
/* 
    Model 
    Following is the example this model can be used in the server controllers.
    
*/


class Ldbmismodel extends MY_Model {
	private $dbHandle = '';
	function __construct(){
		parent::__construct('MIS');
	}
        		
	   /**
	   * @var object of The main CodeIgniter object.
	   */

	   var $CI;
	    	   
	
	   
	    public function getstartdate()
	   {

           $this->CI =& get_instance();
	       
	   $this->CI->config->load('Ldbmis_config',true);
			
           $this->startdate = $this->CI->config->item('startdate', 'Ldbmis_config');
	     
	   $this->enddate = $this->CI->config->item('enddate', 'Ldbmis_config');

	   
	   }	   
	
	/**
	* API to get response report
	*/
	
	


	function getresponse($clientid)
	{
		$dbHandle = $this->getReadHandle();

		$query = "SELECT COUNT( tlmst.id ) as responsesmade,  listings_main.listing_title, listings_main.username,tuser.displayname FROM tempLMSTable AS tlmst, listings_main,tuser WHERE tlmst.listing_subscription_type='paid' and tlmst.submit_date > adddate(curdate(), interval -7 day) AND tlmst.listing_type_id = listings_main.listing_type_id AND listings_main.status =  'live' AND tuser.userId = listings_main.username GROUP BY tlmst.listing_type_id";
		
		
		error_log("sssssssssssssssssss".$query);

		
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		
		
		
		
		$i = 0;
		foreach ($resultSet as $resultarray){
			
		$finalarray[$i]['ResponsesMade'] = $resultarray['responsesmade'];
		$finalarray[$i]['ListingName'] = $resultarray['listing_title'];
		$finalarray[$i]['ClientId'] = $resultarray['username'];
		$finalarray[$i]['ClientName'] = $resultarray['displayname'];

		$i++;						
		}
		
		
		return $finalarray;
        }





	function newgetleadviewdata($clientid)
	{
		$finalarray = array();
		$dbHandle = $this->getReadHandle();
	    $query = "SELECT count(distinct(L.UserId)) as coun,  SUM( L.CreditConsumed )  AS creditused, L.`ClientId` , t.displayname, CS.CourseName FROM LDBLeadContactedTracking AS L, tuser AS t, tUserPref AS tsup, tCourseSpecializationMapping AS CS WHERE L.activity_flag =  'LDB' AND tsup.UserId = L.UserId AND t.userid = L.ClientId and (`ExtraFlag` IS NULL  OR  `ExtraFlag` NOT IN ('testprep',  'studyabroad')) AND tsup.DesiredCourse = CS.SpecializationId AND  L.ContactDate > adddate(curdate(), interval -7 day)  GROUP BY CS.CourseName ORDER BY L.ClientId";
		error_log("ssssssss".$query);
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		$x =0;		
		foreach ($resultSet as $resultarray){
			$finalarray[$x]['Leads_viewed/sms/email'] = $resultarray['coun'];
			$finalarray[$x]['creditused'] = $resultarray['creditused'];
			$finalarray[$x]['ClientId'] = $resultarray['ClientId'];
			$finalarray[$x]['ClientName'] = $resultarray['displayname'];
			$finalarray[$x]['CourseName'] = $resultarray['CourseName'];
			$x++;
		}
		
		$query = "SELECT count(distinct(L.UserId)) as coun,  SUM( L.CreditConsumed )  AS creditused, L.`ClientId` , t.displayname, bt.blogTitle  as CourseName FROM LDBLeadContactedTracking AS L, tuser AS t, tUserPref AS tsup, tUserPref_testprep_mapping AS CS,blogTable bt WHERE  CS.blogid = bt.blogid and L.activity_flag =  'LDB' AND tsup.UserId = L.UserId AND t.userid = L.ClientId AND tsup.prefid = CS.prefid AND tsup.extraflag = 'testprep' AND L.ContactDate > adddate(curdate(), interval -7 day) AND bt.status != 'draft' GROUP BY bt.blogid ORDER BY L.ClientId";
		error_log("sssssssxxxs".$query);
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		foreach ($resultSet as $resultarray){
			$finalarray[$x]['Leads_viewed/sms/email'] = $resultarray['coun'];
			$finalarray[$x]['creditused'] = $resultarray['creditused'];
			$finalarray[$x]['ClientId'] = $resultarray['ClientId'];
			$finalarray[$x]['ClientName'] = $resultarray['displayname'];
			$finalarray[$x]['CourseName'] = $resultarray['CourseName'];
			$x++;
		}
		
		
		$query = "SELECT COUNT( DISTINCT (L.UserId) ) AS coun, SUM( L.CreditConsumed ) AS creditused, L.`ClientId` , t.displayname, CBT.name FROM LDBLeadContactedTracking AS L, tuser AS t,tUserPref AS tsup, tCourseSpecializationMapping AS CS,categoryBoardTable AS CBT WHERE L.activity_flag =  'LDB' AND tsup.UserId = L.UserId AND t.userid = L.ClientId AND ExtraFlag =  'studyabroad' AND tsup.DesiredCourse = CS.SpecializationId and CBT.boardId = CS.CategoryId AND L.ContactDate > ADDDATE( CURDATE( ) , INTERVAL -7 DAY )  GROUP BY CBT.boardId ORDER BY L.ClientId";
		error_log("sssssssxxxs".$query);
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		foreach ($resultSet as $resultarray){
			$finalarray[$x]['Leads_viewed/sms/email'] = $resultarray['coun'];
			$finalarray[$x]['creditused'] = $resultarray['creditused'];
			$finalarray[$x]['ClientId'] = $resultarray['ClientId'];
			$finalarray[$x]['ClientName'] = $resultarray['displayname'];
			$finalarray[$x]['CourseName'] = $resultarray['name'];
			$x++;
		}
		
		
		
		
		return $finalarray;
        }
	


	
	function getbranchforsalesperson($salesid)
	{
		$dbHandle = $this->getReadHandleByModule('SUMS');
		
		$query  ="SELECT `BranchName` FROM Sums_Branch_List B,Sums_User_Branch_Mapping BM WHERE B.`BranchId` = BM.`BranchId` and BM.userId =?";
		$query = $dbHandle->query($query, array($salesid));
		$resultSet = $query->result_array();
		
		
		return $resultSet;
		
		
	}




	function agetnewsearchagentsmis($clientid)
	{
            
        $dbHandle = $this->getReadHandle();
	
           
                $query = "select S.searchagentid,S.searchagentName,S.created_on,S.clientid,t.displayname from tuser AS t,SASearchAgent AS S where t.userid = ?  AND  S.created_on > adddate(curdate(), interval -7 day) AND S.is_active ='live' AND clientid =?" ;
		
		$query = $dbHandle->query($query, array($clientid, $clientid));
		error_log("11111111".$query);

		
		$resultSet = $query->result_array();
		return $resultSet;

	}
	
	
	
	
	function getdetailsforsearchagent($clientid)
	{
		
		$dbHandle = $this->getReadHandle();
	
		
		$query = "SELECT S.searchagentid, S.searchagentName, S.clientid, S.leads_daily_limit, S.price_per_lead, t.displayname, COUNT( DISTINCT LA.userid ) AS count_leads, sum(l.CreditConsumed) as cre FROM tuser AS t, SASearchAgent AS S, SALeadAllocation AS LA,LDBLeadContactedTracking l WHERE S.searchagentid IN ( SELECT searchagentid FROM SASearchAgent where is_active =  'live') AND t.userid = S.clientid AND  LA.allocationtime > adddate(curdate(), interval -7 day) AND LA.auto_download =  'YES' AND LA.agentid = S.searchagentid and LA.userid = l.userid AND S.is_active ='live' and l.activity_flag = 'SA' and l.clientid = S.ClientId  group by S.searchagentid";
		
		
		error_log("ssssssss".$query);
		
		
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		
				
		for($i=0;$i < count($resultSet);$i++)
		{
	
		
		$finalarray[$i]['searchagentid'] = $resultSet[$i]['searchagentid'];
		$finalarray[$i]['searchagentName'] = html_entity_decode($resultSet[$i]['searchagentName']);
		$finalarray[$i]['clientid'] = $resultSet[$i]['clientid'];
		$finalarray[$i]['leads_daily_limit'] = $resultSet[$i]['leads_daily_limit'];

		$finalarray[$i]['clientname'] = $resultSet[$i]['displayname'];
		$finalarray[$i]['Leadsdelivered'] = $resultSet[$i]['count_leads'];
		$finalarray[$i]['Deliverytype'] = 'download';
		$finalarray[$i]['CPL'] = $resultSet[$i]['price_per_lead'];
		
		$finalarray[$i]['creditsused'] = $resultSet[$i]['cre'];
		
		}
		

		return $finalarray;
		
	}
	
	
	
	
	
	
	
	function getautoresponderemail($clientid)
	{
		
		$dbHandle = $this->getReadHandle();
			
		

	//	$query = "SELECT S.searchagentid, S.searchagentName, S.clientid, t.displayname, SASearchAgentAutoResponder_email.daily_limit AS dl, COUNT( DISTINCT LA.userid ) AS coun, SUM( CreditConsumed ) AS creditsused FROM SASearchAgent AS S, SALeadAllocation AS LA, SASearchAgentAutoResponder_email, tuser AS t,LDBLeadContactedTracking l WHERE S.searchagentid IN ( SELECT searchagentid FROM SASearchAgent WHERE clientid IN (".$clientid.")  ) AND t.userid = S.clientid AND DATEDIFF( CURDATE( ) , LA.allocationtime ) <=7 AND LA.agentid = S.searchagentid AND SASearchAgentAutoResponder_email.searchagentid = S.searchagentid AND SASearchAgentAutoResponder_email.is_active =  'live' AND LA.userid = l.userid AND l.ContactType =  'email' AND activity_flag =  'SA'
//AND LA.auto_download =  'NO' AND LA.auto_responder_email_sent =  'YES'";

		$query = "SELECT S.searchagentid, S.searchagentName, S.clientid, t.displayname, SASearchAgentAutoResponder_email.daily_limit AS dl, COUNT( DISTINCT LA.userid ) AS coun, SUM( CreditConsumed ) AS creditsused FROM SASearchAgent AS S, SALeadAllocation AS LA, SASearchAgentAutoResponder_email, tuser AS t, LDBLeadContactedTracking l WHERE S.searchagentid IN (SELECT searchagentid FROM SASearchAgent  ) AND t.userid = S.clientid AND LA.allocationtime > adddate(curdate(), interval -7 day) AND LA.agentid = S.searchagentid AND SASearchAgentAutoResponder_email.searchagentid = S.searchagentid AND SASearchAgentAutoResponder_email.is_active =  'live' AND LA.userid = l.userid AND l.ContactType =  'email' AND activity_flag =  'SA' AND LA.auto_download =  'NO' AND LA.auto_responder_email_sent =  'YES' AND l.ClientId = S.ClientId group by S.searchagentid";



		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();


		$i = 0;
		foreach ($resultSet as $resultarray){
			
		$finalarray[$i]['searchagentid'] = $resultarray['searchagentid'];
		$finalarray[$i]['searchagentName'] = html_entity_decode($resultarray['searchagentName']);
		$finalarray[$i]['clientid'] = $resultarray['clientid'];
		$finalarray[$i]['leads_daily_limit'] = $resultarray['dl'];
		$finalarray[$i]['clientname'] = $resultarray['displayname'];
		$finalarray[$i]['Leadsdelivered'] = $resultarray['coun'];
		$finalarray[$i]['Deliverytype'] = 'email';
		$finalarray[$i]['CPL'] = 3;
		$finalarray[$i]['creditsused'] = ($resultarray['coun']*3);


		$i++;						
		}

		return $finalarray;
		
	}

		
		
	function getautorespondersms($clientid)
	{
		
		$dbHandle = $this->getReadHandle();
	
	
		

		$query = " SELECT S.searchagentid, S.searchagentName, S.clientid, t.displayname, SASearchAgentAutoResponder_sms.daily_limit AS dl, COUNT( DISTINCT LA.userid ) AS coun, SUM( CreditConsumed ) AS creditsused FROM SASearchAgent AS S, SALeadAllocation AS LA, SASearchAgentAutoResponder_sms, tuser AS t, LDBLeadContactedTracking l WHERE S.searchagentid IN ( SELECT searchagentid FROM SASearchAgent ) AND t.userid = S.clientid AND LA.allocationtime > adddate(curdate(), interval -7 day) AND LA.agentid = S.searchagentid AND SASearchAgentAutoResponder_sms.searchagentid = S.searchagentid AND SASearchAgentAutoResponder_sms.is_active =  'live' AND LA.userid = l.userid AND l.ContactType =  'sms' AND l.activity_flag =  'SA' AND LA.auto_download =  'NO' AND LA.auto_responder_sms_sent =  'YES' and l.ClientId = S.ClientId group by S.searchagentid";
		
		
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
	

		$i = 0;
		foreach ($resultSet as $resultarray){
			
		$finalarray[$i]['searchagentid'] = $resultarray['searchagentid'];
		$finalarray[$i]['searchagentName'] = html_entity_decode($resultarray['searchagentName']);
		$finalarray[$i]['clientid'] = $resultarray['clientid'];
		$finalarray[$i]['leads_daily_limit'] = $resultarray['dl'];
		$finalarray[$i]['clientname'] = $resultarray['displayname'];
		$finalarray[$i]['Deliverytype'] = 'sms';
		$finalarray[$i]['Leadsdelivered'] = $resultarray['coun'];
		$finalarray[$i]['CPL'] = 5;
		$finalarray[$i]['creditsused'] = ($resultarray['coun']*5);


		$i++;						
		}

		return $finalarray;
		
	}
	
	
	function getSAclients($salesid)
	{
		$dbHandle = $this->getReadHandleByModule('SUMS');
	
	
           
        $query = "SELECT DISTINCT (S.`ClientUserId`) FROM Subscription S, Transaction T WHERE S.TransactionId = T.TransactionId AND SubscrStatus = 'ACTIVE' AND BaseProductId =127 AND T.SalesBy =?";
		$query = $dbHandle->query($query, array($salesid));
		$resultSet = $query->result_array();
		return $resultSet;
        }
	
	function getleadsallocatedtosearchagent($searchagentid)
	{
		$dbHandle = $this->getReadHandle();

		$query = "SELECT userid FROM SALeadAllocation WHERE allocationtime > adddate(curdate(), interval -7 day) AND agentid =? AND auto_download = 'YES' " ;
		
		$query = $dbHandle->query($query, array($searchagentid));
		$resultSet = $query->result_array();
		return $resultSet;
		
		
	}
	
	
	
	
	function getcreditsremaining()
	{
		$dbHandle = $this->getReadHandleByModule('SUMS');
		
		$query  ="SELECT sum(BaseProdRemainingQuantity), ClientUserId FROM Subscription_Product_Mapping Su, Subscription S WHERE Su.SubscriptionId = S.SubscriptionId AND Su.BaseProductId =127 AND DATE( Su.SubscriptionEndDate ) >= CURDATE( ) AND DATE( Su.SubscriptionStartDate ) <= CURDATE( ) AND Su.Status =  'ACTIVE' AND S.BaseProductId =127 AND Su.BaseProdRemainingQuantity >=1 group by ClientUserId";

		
	
		
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		
		
		
		
		
		return $resultSet;
		
		
	}
		
	
	function getleadallocationtosearchagent($agentid,$startDate,$endDate,$clientid,$status='live')
	{
		$dbHandle = $this->getReadHandle();

		// $query = "SELECT LA.userid, LA.matchedFor, LA.responseTime as submitDate, S.searchagentid, S.searchagentName FROM SALeadAllocation AS LA, SASearchAgent AS S WHERE LA.agentid =?  AND MONTH(LA.allocationtime) =? AND YEAR(LA.allocationtime) =? AND S.clientid =? AND S.is_active = 'live' AND S.searchagentid =?";

		$query = "SELECT DISTINCT LA.userid, LA.matchedFor, LA.responseTime as submitDate, S.searchagentid, S.searchagentName FROM SALeadAllocation AS LA, SASearchAgent AS S WHERE LA.agentid =?  AND LA.allocationtime >=? AND LA.allocationtime <=? AND S.clientid =? AND S.is_active = ? AND S.searchagentid =? AND LA.auto_download = 'YES' ";
		
		$query = $dbHandle->query($query, array($agentid, $startDate, $endDate, $clientid, $status, $agentid));
		$resultSet = $query->result_array();
		error_log("####last query ".$dbHandle->last_query());
		return $resultSet;
		
	}

    function getleadsallocatedtoclient($clientid, $days)
	{
		$dbHandle = $this->getReadHandle();


		$query = "SELECT LA.userid, S.searchagentid, S.searchagentName FROM SALeadAllocation AS LA, SASearchAgent AS S WHERE LA.agentid =S.searchagentid AND S.clientid =? AND S.is_active = 'live'";
		
		if ( ! empty($days))
		{
			$query = $query." AND LA.allocationtime > adddate(curdate(), interval -".$dbHandle->escape($days)." day)";
		}
		
		error_log("leadsallocated ".$query);
		$query = $dbHandle->query($query, array($clientid));
		$resultSet = $query->result_array();
		return $resultSet;
		
	}
	
	function getnewsearchagents()
	{
		
		$dbHandle = $this->getReadHandle();

		
		 $query = "SELECT S.searchagentid, S.searchagentName, S.created_on, S.clientid, BranchName, t.displayname, x.displayname AS sales,x.email as salesemail FROM tuser AS t, SASearchAgent AS S, SUMS.Subscription Su, tuser AS x, SUMS.Transaction T, SUMS.Sums_Branch_List B, SUMS.Sums_User_Branch_Mapping BM WHERE t.userid = S.clientid AND S.created_on > ADDDATE( CURDATE( ) , INTERVAL -7 DAY ) AND S.is_active = 'live' AND S.clientid = T.clientuserid AND BM.`BranchId` AND BM.userId = salesby AND Su.TransactionId = T.TransactionId AND BaseProductId =127 AND x.userid = T.salesby GROUP BY searchagentid order by T.salesby";
		
		$query = $dbHandle->query($query);
		error_log("11111111".$query);

		$resultSet = $query->result_array();
		return $resultSet;
		
	}
	
	
	function getleadviewdata()
	{
		$dbHandle = $this->getReadHandle();

		
		$query = "SELECT COUNT( DISTINCT (L.UserId) ) AS coun, SUM( L.CreditConsumed ) AS creditused, L.`ClientId` , t.displayname, CS.CourseName, Ta.salesby,  `BranchName` ,x.displayname as sales, x.email FROM LDBLeadContactedTracking AS L, tuser AS t, tUserPref AS tsup, tCourseSpecializationMapping AS CS, SUMS.Transaction Ta,  SUMS.Sums_Branch_List B, SUMS.Sums_User_Branch_Mapping BM, tuser AS x WHERE L.activity_flag =  'LDB' AND tsup.UserId = L.UserId AND t.userid = L.ClientId AND tsup.DesiredCourse = CS.SpecializationId AND L.ContactDate > ADDDATE( CURDATE( ) , INTERVAL -7 DAY ) AND L.ClientId = Ta.clientuserid AND B.`BranchId` = BM.`BranchId` AND BM.userId = salesby AND x.userid = salesby AND Ta.salesby IN (SELECT DISTINCT (salesby) FROM SUMS.Transaction) GROUP BY CS.CourseName, L.ClientId ORDER BY salesby";
		
		
		
		
		$query = $dbHandle->query($query);
		error_log("11111111".$query);

		
		$resultSet = $query->result_array();

		
		$x =0;		
		foreach ($resultSet as $resultarray){
			$finalarray[$x]['Leads_viewed/sms/email'] = $resultarray['coun'];
			$finalarray[$x]['creditused'] = $resultarray['creditused'];
			$finalarray[$x]['ClientId'] = $resultarray['ClientId'];
			$finalarray[$x]['displayname'] = $resultarray['displayname'];
			$finalarray[$x]['CourseName'] = $resultarray['CourseName'];
			$finalarray[$x]['BranchName'] = $resultarray['BranchName'];
			$finalarray[$x]['salesperson'] = $resultarray['sales'];
			$finalarray[$x]['email'] = $resultarray['email'];

			
			
			$x++;
		
		
		}
		
		return $finalarray;
		
		
		
		
		
	}
	
	
	
	function sumsarray()
	{
		
		$dbHandle = $this->getReadHandleByModule('SUMS');
	
	
           
        $query = "SELECT DISTINCT (`ClientUserId`), salesby, BranchName, x.email, x.displayname FROM  `Transaction` , Sums_Branch_List B,Sums_User_Branch_Mapping BM, shiksha.tuser AS x WHERE  `SalesBy` IN (SELECT DISTINCT (SalesBy) FROM Transaction) AND B.`BranchId` = BM.`BranchId` AND BM.userId = salesby AND x.userid = salesby ORDER BY salesby";
		
		
		
		
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		
		$x =0;
		
		for($i =0;$i < count($resultSet);$i++)
		{	
			
			$resultarray = $resultSet[$i];
			$finalarray[$resultarray['salesby']][$x]['ClientUserId'] = $resultarray['ClientUserId'];
			$finalarray[$resultarray['salesby']][$x]['BranchName'] = $resultarray['BranchName'];
			$finalarray[$resultarray['salesby']][$x]['email'] = $resultarray['email'];
			$finalarray[$resultarray['salesby']][$x]['displayname'] = $resultarray['displayname'];
			
			
			$x++;
		
		
		}
		
		
		
		
		
		
		return $finalarray;
		
		

	
	}
	
	
	
	function sums()
	{
		
		
		$dbHandle = $this->getReadHandleByModule('SUMS');
		
	
	
           
                $query = "SELECT DISTINCT (`ClientUserId`), salesby, BranchName, x.email, x.displayname FROM  `Transaction` , Sums_Branch_List B,Sums_User_Branch_Mapping BM, shiksha.tuser AS x WHERE  `SalesBy` IN (SELECT DISTINCT (SalesBy) FROM Transaction) AND B.`BranchId` = BM.`BranchId` AND BM.userId = salesby AND x.userid = salesby ORDER BY salesby";
		
		
		
		
		$query = $dbHandle->query($query);
		$resultSet = $query->result_array();
		
		return $resultSet;
		
		
		
	}
	
	function getLeadGenieScope($searchAgentId)
	{
		$dbHandle = $this->getReadHandle();
                $query = "SELECT b.scope FROM `SAMultiValuedSearchCriteria` a, tCourseSpecializationMapping b
				WHERE a.value = b.SpecializationId
				AND a.keyname = 'desiredcourse'
				AND a.`searchAlertId` = ? LIMIT 1";
		
		$query = $dbHandle->query($query, array($searchAgentId));
		
		$resultSet = $query->result_array();
		$scope = $resultSet[0]['scope'];
		return $scope;
	}
	
	function getSearchAgentType($searchAgentId,$status='live')
	{
		$dbHandle = $this->getReadHandle();
        
        $query = "SELECT type FROM `SASearchAgent` WHERE  is_active = ? AND searchagentid = ? ORDER BY id desc LIMIT 1 ";
		
		$query = $dbHandle->query($query, array($status,$searchAgentId));
		error_log("#### query ".$dbHandle->last_query());
		$resultSet = $query->result_array();
		$type = $resultSet[0]['type'];
		return $type;
	}
	
	
}
