<?php

class nav_integration_model extends CI_Model
{
    private $_dbShiksha;
    private $_dbSums;
    
    function __construct($dbShiksha,$dbSums)
    {
        $this->_dbShiksha = $dbShiksha;
        $this->_dbSums = $dbSums;
    }
    


    public function getEnterpriseUsers($criteria,$timeWindow)
    {
        $selectCaluse = array();
	
		$this->load->database('');
	
        if($criteria == 'NEW') {
            $selectCaluse[] = " t.userCreationDate >= ? ";
            $selectCaluse[] = " t.userCreationDate < ? ";
        }
        else {
            $selectCaluse[] = " t.lastModifiedOn >= ? ";
            $selectCaluse[] = " t.lastModifiedOn < ? ";
        }
		
        $sql = "SELECT t . * , e . * , c.city_name,countryTable.name as countryname FROM shiksha.tuser t LEFT JOIN shiksha.enterpriseUserDetails e ON e.userId = t.userid LEFT JOIN shiksha.countryCityTable c ON c.city_id = t.city LEFT JOIN shiksha.countryTable ON t.country= countryTable.countryId WHERE t.usergroup = 'enterprise' and t.userid NOT IN (select userid from SUMS.Nav_Userid_Mapping) AND t.usergroup = 'enterprise' AND ".implode(' AND ',$selectCaluse);
        
        $query = $this->db->query($sql, array($timeWindow['start'], $timeWindow['end']));
        $results = $query->result_array();
        return $results;
        
    }


    
   public function getupdatedUsers()
    {
        // $selectCaluse = array();
	
		$this->load->database('');

        // $selectCaluse[] = " t.lastModifiedOn = ? ";
        
        $sql =  "SELECT Nav_Userid_Mapping.Nav_ClientId,t . * , e . * , c.city_name,countryTable.name as countryname FROM SUMS.Nav_Userid_Mapping,shiksha.tuser t LEFT JOIN shiksha.enterpriseUserDetails e ON e.userId = t.userid LEFT JOIN shiksha.countryCityTable c ON c.city_id = t.city LEFT JOIN shiksha.countryTable ON t.country= countryTable.countryId WHERE t.usergroup = 'enterprise' and t.userid = Nav_Userid_Mapping.userid AND t.usergroup = 'enterprise' AND t.lastModifiedOn = ? ";
        
        $query = $this->db->query($sql, array(date("Y-m-d")));
		$results = $query->result_array();
        return $results;
    }

   function getNewOnlinePayments($timeWindow)
   {
       $selectCaluse = array();
 
       $query = $this->_dbSums->query( "SELECT ccl.id as CclUniqueId, ccl.Partially_Paid_Amount, pd.* , p.Transaction_Id, Transaction.* FROM CreditCardLogs ccl LEFT JOIN Payment_Details pd ON ( ccl.paymentId = pd.Payment_Id AND ccl.partPaymentId = pd.Part_Number ) LEFT JOIN Payment p ON ( p.Payment_Id = pd.Payment_Id ) LEFT JOIN Transaction ON ( p.Transaction_Id = Transaction.TransactionId ) where  ccl.flag = 'Done' AND ccl.Partial_Payment_Acknowledgement = 'NotAcknowledged' AND Amount_Received != 0 AND isPaid = 'Paid' AND pd.PayGatewaymode IS NOT NULL");
       $results = $query->result_array();
       return $results;
   }

   /*
    *  UPDATE Shiksha Tables
    */

   public function updateShikshaExpiryDate($subscriptions,$subscriptionStartDate,$subscriptionEndDate)
   {
	   $subsStartDate = date('Y-m-d H:i:s', strtotime($subscriptionStartDate));
	   $subsEndDate = date('Y-m-d H:i:s', strtotime($subscriptionEndDate));

	   foreach($subscriptions as $subs){
		   $subscriptionId = (int)$subs['subsId'];
		   
		   $querytlistingsubscription = $this->_dbShiksha->query("UPDATE tlistingsubscription ".
				   "SET startdate = ?,enddate = ? ".
				   "WHERE subscriptionid = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );

		   $querylistings_main = $this->_dbShiksha->query("UPDATE listings_main ".
				   "SET expiry_date = ? ".
				   "WHERE subscriptionId = ?", array($subsEndDate, $subscriptionId)
				   );

		   $queryPageCollegeDb = $this->_dbShiksha->query("UPDATE PageCollegeDb ".
				   "SET StartDate = ?, EndDate = ? ".
				   "WHERE subscriptionid = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );

		   $querySearch_sponsored_listings = $this->_dbShiksha->query("UPDATE search_sponsored_listings ".
				   "SET subscription_start_date = ?, subscription_end_date = ? ".
				   "WHERE subscription_id = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );

		   $queryranking_banner_products = $this->_dbShiksha->query("UPDATE ranking_banner_products ".
				   "SET subscription_start_time = ?,subscription_expire_time = ?".
				   "WHERE subscription_id = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );

		   $querytSponsoredListingByKeyword = $this->_dbShiksha->query("UPDATE tSponsoredListingByKeyword ".
				   "SET set_time = ?,unset_time = ? ".
				   "WHERE subscriptionId = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );

		   $querytbannerlinks = $this->_dbShiksha->query("UPDATE tbannerlinks ".
				   "SET startdate = ?,enddate = ? ".
				   "WHERE subscriptionid = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );

           $category_banners = $this->_dbShiksha->query("UPDATE category_banners ".
				   "SET start_date = ?,end_date = ? ".
				   "WHERE subscription_id = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );				   
			
			$category_products = $this->_dbShiksha->query("UPDATE category_products ".
				   "SET start_date = ?,end_date = ? ".
				   "WHERE subscription_id = ?", array($subsStartDate, $subsEndDate, $subscriptionId)
				   );
				   
	   }
    }


	/*
	 * Cron specific functions*/
	  
	public function getAlreadyRunningCron()
	{
		$sql =  "SELECT id,pid ".
				"FROM NAV_Integration_Cron ".
				"WHERE status = '".NAV_CRON_ON."' ";
		return $this->_dbSums->query($sql)->row();
	}
	
		
	function registerCron($pid,$status,$ipAddress)
	{
		$pid = (int) $pid;
		if($pid > 0) {
			if($status == NAV_CRON_TERMINATE) {
				$this->_dbSums->query("UPDATE NAV_Integration_Cron ".
								"SET status = '".NAV_CRON_TERMINATE."' ".
								"WHERE status = '".NAV_CRON_ON."' ");
				$status = NAV_CRON_ON;
			}
		
			$data = array(
						   'pid' => $pid,
						   'startTime' => date('Y-m-d H:i:s'),
						   'status' => $status,
						   'ipAddress' => $ip_address
						);
			if($this->_dbSums->insert('NAV_Integration_Cron', $data)) {
				$cronId = $this->_dbSums->insert_id();
				return $cronId;
			}
			else  {
				return false;
			}
		}
		else  {
			return false;
		}
	}
		
	
	
	function updateCron($cronId,$status,$timeWindow,$stats)
	{
		$endTime = '0000-00-00 00:00:00';	
		if($status == NAV_CRON_OFF) {
			$endTime = date('Y-m-d H:i:s');
		}
		
		$query = $this->_dbSums->query("UPDATE NAV_Integration_Cron ".
                                        "SET status = ?,lastProcessedTimeWindow = ?,endTime = ? ".
                                        "WHERE id = ? ", array($status, $timeWindow, $endTime, $cronId)
                                      );
	}
	
	function getCronFailCount($cronId)
	{
		$query = $this->_dbSums->query("SELECT count(*) as failCount ".
								  "FROM NAV_Integration_Cron ".
								  "WHERE startTime > (SELECT startTime FROM NAV_Integration_Cron WHERE id = ?) ". 
								  "AND status = ? ", array($cronId, NAV_CRON_FAIL)
								);
		$row = $query->row();
		return $row->failCount;
	}
	
	function getLastProcessedTimeWindow()
	{
		$query = $this->_dbSums->query("SELECT lastProcessedTimeWindow ".
								  "FROM NAV_Integration_Cron ".
								  "WHERE lastProcessedTimeWindow != '0000-00-00 00:00:00' ".
								  "ORDER BY startTime DESC ".
								  "LIMIT 1 ");
		$row = $query->row();
		return $row->lastProcessedTimeWindow;
	}

	public function getDerivedProductIdFromBaseId($baseProdId, $currency) 
	{
		$sql = "SELECT DerivedProductId FROM `Derived_Products_Mapping` where BaseProductId = ? and CurrencyId = ? ";

		$query = $this->_dbSums->query($sql,array($baseProdId, $currency));
		$row = $query->row();
		return $row->DerivedProductId;

	}

	public function updateTaxAndPaymentDetails() 
	{

		$sqlGetTransactions = "SELECT P.`Payment_Id`, P.`Transaction_Id`, PD.`Part_Number`, PD.`DueAmount`, PD.`Part_Service_tax_Percentage`, T.`TotalTransactionPrice` 
								FROM Payment P, Payment_Details PD, Transaction T 
								WHERE PD.`Payment_Id` = P.`Payment_Id`
								AND P.`Transaction_Id` = T.`TransactionId` 
								AND PD.`DueAmount` > 0
								AND P.`Transaction_Id` > 0 ";

		$resultGetTransactions = $this->_dbSums->query($sqlGetTransactions)->result_array();
		
		$paymentArray = array();
		$paramsArray  = array();

		foreach ($resultGetTransactions as $key => $paymentDetail) {
			
			$paymentArray[$paymentDetail['Transaction_Id']]['Transaction Id'] = $paymentDetail['Transaction_Id'];
			$transactionIdsArray[] = $paymentDetail['Transaction_Id'];
			$paymentArray[$paymentDetail['Transaction_Id']]['Total Transaction Price'] = $paymentDetail['TotalTransactionPrice'];

			$paymentArray[$paymentDetail['Transaction_Id']]['Payment Id'][] = $paymentDetail['Payment_Id'];
			$paymentIdsArray[] = $paymentDetail['Payment_Id'];
			$paymentArray[$paymentDetail['Transaction_Id']]['Part Number'][] = $paymentDetail['Part_Number'];
			$paymentArray[$paymentDetail['Transaction_Id']]['Due Amount'][] = $paymentDetail['DueAmount'];
			$paymentArray[$paymentDetail['Transaction_Id']]['Tax Percentage'][] = $paymentDetail['Part_Service_tax_Percentage'];

			// $newDueAmount = floatval(($paymentDetail['DueAmount']/1.1236) * 1.14);
			// $newDueAmount = floatval(($paymentDetail['DueAmount']/1.14) * 1.145);
			// $newDueAmount = floatval(($paymentDetail['DueAmount']/1.145) * 1.15);
			$newDueAmount = floatval(($paymentDetail['DueAmount']/1.15) * 1.18);
			$paymentArray[$paymentDetail['Transaction_Id']]['New Due Amount'][] = $newDueAmount;
			$paymentArray[$paymentDetail['Transaction_Id']]['New Tax Percentage'][] = 18;
			$paymentArray[$paymentDetail['Transaction_Id']]['Difference'][] = $newDueAmount - $paymentDetail['DueAmount'];
			$paymentArray[$paymentDetail['Transaction_Id']]['Additional Transaction Price'] += floatval($newDueAmount - $paymentDetail['DueAmount']);

			$updatePaymentDetailsClause .= " WHEN `Payment_Id` = ? AND `Part_Number` = ? THEN ? ";
			
			$paramsArray[] = $paymentDetail['Payment_Id'];
			$paramsArray[] = $paymentDetail['Part_Number'];
			$paramsArray[] = round($newDueAmount,2);							 

		}
		
		// $paymentIds = implode(',', $paymentIdsArray);
		// $transactionIds = implode(',', $transactionIdsArray);

		$sqlUpdatePaymentDetails = "UPDATE Payment_Details 
									SET `Part_Service_tax_Percentage` = 18, `DueAmount` = 
									CASE 
									" . $updatePaymentDetailsClause . "
									END 
									WHERE `DueAmount` > 0 
									AND `Payment_Id` IN ( ? ) " ;
		
		$paramsArray[] = $paymentIdsArray;
		$queryUpdatePaymentDetails = $this->_dbSums->query($sqlUpdatePaymentDetails,$paramsArray);
		unset($paramsArray);
		
		$paramsArray = array();
		foreach ($paymentArray as $transactionId => $details) {
			$newTransactionPrice = floatval($details['Total Transaction Price'] + $details['Additional Transaction Price']);
			$updateTransactionDetailsClause .= " WHEN `TransactionId` = ? THEN ? ";
			
			$paramsArray[] = $transactionId;
			$paramsArray[] = round($newTransactionPrice,2);

		}

		$sqlUpdateTransactionTable = "UPDATE Transaction 
									SET `TotalTransactionPrice` = CASE 
									" . $updateTransactionDetailsClause . "
									ELSE `TotalTransactionPrice`
									END 
									WHERE `TransactionId` IN ( ? ) ";

		$paramsArray[] = $transactionIdsArray;
		$queryUpdateTransactionDetails = $this->_dbSums->query($sqlUpdateTransactionTable,$paramsArray);
		unset($paramsArray);
		
	}

	function getClientTransactionDetails($clientId) {
		
		$queryCmd = "SELECT TransactionId, ClientUserId, SalesBy as oldSalesBy 
                    FROM Transaction 
                  	WHERE ClientUserId = ? 
                    ORDER BY TransactionId DESC LIMIT 1";

        $query = $this->_dbSums->query($queryCmd,array($clientId));
        error_log("######Query 2 ".$this->_dbSums->last_query());
        $result = $query->result_array();
        error_log("######Result 2 ".print_r($result,true));
        return $result;

	}

	function getSalesPersonDetailsByEmailId($emailIds) {

		$this->load->database('');

		$queryCmd = "SELECT sud.EmployeeId as SalesBy  
					FROM shiksha.tuser tu LEFT JOIN SUMS.Sums_User_Details sud ON tu.userid = sud.userId 
					WHERE tu.email = ? ";

		$query = $this->db->query($queryCmd,array($emailIds));
        error_log("######Query ".$this->db->last_query());
        $result = $query->result_array();
        error_log("######Result ".print_r($result,true));
        return $result;

	}

	function updateSalesPersonCode($transactionId, $oldSalesBy, $newSalesBy) {

		$sqlUpdateTransaction = "UPDATE Transaction SET SalesBy = ? WHERE TransactionId = ? ";
		$queryUpdateTransaction = $this->_dbSums->query($sqlUpdateTransaction,array($newSalesBy, $transactionId));
		error_log("######Query ".$this->_dbSums->last_query());

		$sqlTrackUpdate = "INSERT INTO updateContactCardTracking (TransactionId,OldSalesBy,SalesBy) 
							VALUES (?, ?, ?) ";
		$queryTrackUpdate = $this->_dbSums->query($sqlTrackUpdate,array($transactionId,$oldSalesBy,$newSalesBy));

		error_log("######Query ".$this->_dbSums->last_query());
		
		return true;

	}

    function getNavUseridMapping($Nav_ClientId) {
		
		if(!empty($Nav_ClientId)) {
			
			$sql = "SELECT * FROM Nav_Userid_Mapping WHERE Nav_ClientId = ? ";
			$queryNavMapping = $this->_dbSums->query($sql,array($Nav_ClientId));
			$results = $queryNavMapping->result_array();	
			return $results;
		}
		
    }

    public function validateClientByEmail($email = '') {
    	
    	if(!empty($email)){
			$sql    = "SELECT userid, usergroup,mobile FROM tuser where email = ?";
			$query  = $this->_dbShiksha->query($sql, array($email));
			$result = $query->result_array();
    		return $result[0];
    	}

    }

    public function getReverseNavUseridMapping($userid, $navId) {
		
		if(!empty($userid)) {
			
			$sql             = "SELECT * FROM Nav_Userid_Mapping WHERE userid = ? AND Nav_ClientId != ?";
			$queryNavMapping = $this->_dbSums->query($sql,array($userid, $navId));
			$results         = $queryNavMapping->result_array();
			return $results;

		}
		
    }

    public function insertNavClientMapping($userid, $navId) {
    	
    	if(!empty($userid) && !empty($navId)) {
			
			$details                 = array();
			$details['userid']       = $userid;
			$details['Nav_ClientId'] = $navId;
			$queryCmd                = $this->_dbSums->insert_string('Nav_Userid_Mapping',$details);
			$query                   = $this->_dbSums->query($queryCmd);
			$response                = $this->_dbSums->insert_id();
			return $response;

		}

    }

    public function updateNavClientUserGroupByUserId($userId){
    	if(empty($userId)){
    		return false;
    	}
    	$sql = "UPDATE tuser set usergroup = 'enterprise' where userid = ?";
    	$query  = $this->_dbShiksha->query($sql, array($userId));
		
		return true;			
    }

    public function getCityIdByName($city_name){
     	if($city_name == ''){
     		return 0;
     	}

        $dbLibObj = DbLibCommon::getInstance('SUMSShiksha');
		$dbHandleShiksha = $dbLibObj->getReadHandle();

        $sql = " SELECT city_id FROM countryCityTable where upper(city_name) =?";
        $result = $dbHandleShiksha->query($sql,array($city_name))->result_array();
        return $result[0]['city_id']; 
    }

    public function getAllNavUseridMapping(){
    	$sql = 'select userid,Nav_ClientId from Nav_Userid_Mapping';
    	$dbLibObj = DbLibCommon::getInstance('SUMS');
		$dbHandleShiksha = $dbLibObj->getReadHandle();
    	$result = $dbHandleShiksha->query($sql)->result_array();
    	return $result;
    }

    public function getUserData($userIds){
    	if (empty($userIds)){
    		return;
    	}
    	$sql = "SELECT t.*,e.*,c.city_name,countryTable.name AS countryname FROM shiksha.tuser t LEFT JOIN shiksha.enterpriseUserDetails e ON e.userId = t.userid LEFT JOIN shiksha.countryCityTable c ON c.city_id = t.city LEFT JOIN shiksha.countryTable ON t.country = countryTable.countryId WHERE
			t.userid in (?) AND t.usergroup = 'enterprise' AND t.lastModifiedOn = ?";
    	$dbLibObj = DbLibCommon::getInstance('User');
		$dbHandleShiksha = $dbLibObj->getReadHandle();
    	$result = $dbHandleShiksha->query($sql,array($userIds,date('Y-m-d')))->result_array();
    	return $result;
    }
}

