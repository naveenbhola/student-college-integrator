<?php

class naukrileadsmodel extends MY_Model {

    /**
    * @var Object DB Handle
    */
    private $dbHandle;
    
	/**
	* Initiate the model
	*
	* @param string $operation
	*/
    private function initiateModel($mode = 'read',$module=''){
		if($mode == 'read') {
			$this->dbHandle = empty($module) ? $this->getReadHandle() : $this->getReadHandleByModule($module);
		} else {
			$this->dbHandle = empty($module) ? $this->getWriteHandle() : $this->getWriteHandleByModule($module);
		}

    }

    function getNaukriStatesList() {

    	$this->initiateModel('read');
    	$this->dbHandle->select('state_id, state_name');
    	$this->dbHandle->from('naukri_states');
    	$this->dbHandle->where("status","live");
    	$result = $this->dbHandle->get()->result_array();
    	return $result;

    }

    function saveNaukriLeadsSubscriptionData($subscriptionData){

        if(empty($subscriptionData) || !is_array($subscriptionData) && count($subscriptionData) <=0){
            return false;
        }

        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $this->dbHandle->insert('naukri_leads_subscription',$subscriptionData);
        $insertId = $this->dbHandle->insert_id();
        
        $this->dbHandle->trans_complete();
        if($this->db->trans_status() === false) {
            return false;
        } else {
            return true;
        }

    }

    function checkValidSubscriptionForClient($clientId) {

    	if(!empty($clientId) && $clientId > 0) {

    		$this->initiateModel('read');
    		$this->dbHandle->select('count(*) as count');
    		$this->dbHandle->from('naukri_leads_subscription');
    		$this->dbHandle->where('client_id',$clientId);
    		$this->dbHandle->where('status','active');
    		$result = $this->dbHandle->get()->result_array();
    		if($result[0]['count'] == 0) {
    			return true;
    		} else {
    			return false;
    		}

	    } else {
	      	return false;
	    }

    }

	public function getStateByCity($naukri_city){
        $this->initiateModel();
        
        if(empty($naukri_city)) {
           return false;
        }
        $sql = "select city_id, state_id from naukri_cities where city_name=?";
        $result = $this->dbHandle->query($sql,array($naukri_city))->result_array();
        return $result[0];
    }

    public function saveNaukriLeadsData($naukri_leads_data){
        if(empty($naukri_leads_data)){
            return false;
        }
        
        $this->initiateModel('write');
        $this->dbHandle->insert('naukri_leads_data',$naukri_leads_data);

    }
    
    function getSubscriptionData($type = '',$clientId = '', $subscriptionId = ''){
        if(!empty($type) && !in_array($type, array("active"))){
          return false;
        }
        
        $this->initiateModel("read");
        $this->dbHandle->select('id,client_id, client_name, account_manager_name, state_ids, start_date, end_date, quantity_expected, quantity_delivered, status, email, mobile');
        $this->dbHandle->from('naukri_leads_subscription');
        if(!empty($clientId)){
            $this->dbHandle->where('clientId',$clientId);
        }
        if($type != 'all'){
            if($type == "active"){
                $this->dbHandle->where('status','active');
            }else if(in_array($type, array("expired","inactive","deleted"))){
                $this->dbHandle->where_in('status',array('inactive','deleted'));
            }            
        }

        if(!empty($subscriptionId)){
            $this->dbHandle->where('id',$subscriptionId);
        }
        
        $this->dbHandle->order_by('creation_date','desc');

        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    function getAllStates() {
        
        $this->initiateModel("read");
        $this->dbHandle->select('state_id,state_name');
        $this->dbHandle->from('naukri_states');
		$this->dbHandle->where('status','live');        

        $result = $this->dbHandle->get()->result_array();

        return $result;
    }

    function updateSubscriptionStatus($subscriptionId, $status){
        if(empty($subscriptionId) || $subscriptionId <=0){
          return false;
        }

        if(empty($status)){
          return false;
        }

        $this->initiateModel('write');
        $this->dbHandle->trans_start();

        $data = array(
            'status'=>$status,
            'status_updated_at' => date('Y-m-d H:i:s')
        );

        $this->dbHandle->where('id',$subscriptionId);
        $this->dbHandle->update('naukri_leads_subscription',$data);

        $this->dbHandle->trans_complete();
        if($this->dbHandle->trans_status() === false){
            return false;
        }else{
            return true;
        }
    }


     public function getLastProcessedId(){
        
        $this->initiateModel();

        $query = "select lastprocessedid from SALeadAllocationCron where process='NAUKRI_LEAD_DELIVERY'";
                
        $result = $this->dbHandle->query($query)->result_array();
        
        return $result[0]['lastprocessedid'];       
    }


    public function getNewNaukriLeadSubscription(){
    	$this->initiateModel();

    	$query = "select id, client_id, state_ids, campaign_type, start_date, end_date, quantity_expected, quantity_delivered from naukri_leads_subscription where status='active' and is_processed='no'";
	    	    
	    $result = $this->dbHandle->query($query)->result_array();
	    
	    return $result;    		
    }


    public function getUsersForQuantityBased($state_id, $last_processed_id, $start_date, $quantityExpected,$client_id){
    	
    	if(empty($quantityExpected)){
    		return;
    	}

    	$this->initiateModel();

    	$state_where_clause = '';
    	$sql_input_array = array();
    	if($state_id != '' ){   		
    		//array_push($sql_input_array, $state_id);
    		$state_where_clause = "nld.state_id in ($state_id) and";
    	}

    	$query = "SELECT nld.id,nld.state_id FROM naukri_leads_data nld
					LEFT OUTER JOIN naukri_lead_allocation nla
  							ON (nld.id =nla.userId and nla.clientId =?) 
  									WHERE nla.id IS NULL and $state_where_clause   
  									nld.id <= ? and nld.creation_time>=? 
  									limit ? ";

  		array_push($sql_input_array, $client_id);
    	array_push($sql_input_array, $last_processed_id);
    	array_push($sql_input_array, $start_date);
	    array_push($sql_input_array, (int)$quantityExpected);	    
	   //_p($query);
	    $result = $this->dbHandle->query($query,$sql_input_array)->result_array();
	    return $result;    			
    }

    public function updateDeliveredCountForSubscription($matched_subscriptions, $quantity_to_add){
    	if (count($matched_subscriptions)<1) {
    		return;
    	}

    	$this->initiateModel('write');  

    	$query = "update naukri_leads_subscription set quantity_delivered = quantity_delivered + ? where id IN (?)";
	    	    
	   	$this->dbHandle->query($query,array($quantity_to_add, $matched_subscriptions));
	    

	}

	public function insertInNaukriCreditDeduction($bucket,$amount,$clientId,$subscriptionId)
	{

		$this->initiateModel('write');
		$inputData = array();
		$query = "INSERT INTO naukri_leads_credit_deduction (subscription_id,client_id,credits_deducted,user_id,sums_subscription_id) VALUES";

		foreach ($bucket as $id=>$row) {
			foreach ($row as $key => $value) {
				$query .= "(?,?,?,?,?),";
				$inputData[] = $subscriptionId;
				$inputData[] = $clientId;
				$inputData[] = $amount;
				$inputData[] = $value['id'];
				$inputData[] = $id;
			}
			
		}
		$query = substr($query, 0,-1);
		$this->dbHandle->query($query,$inputData);
	}

	public function updateSubscriptionRemainingQuantity($subscriptionArray)
	{
		
		$this->initiateModel('write','SUMS');

		$inputData = array();
		foreach ($subscriptionArray as $key => $value) {
			$query = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity - ? *".CREDITS_FOR_NAUKRI_LEADS." where SubscriptionId = ?";
			$inputData[] = $value;
			$inputData[] = $key;
			$this->dbHandle->query($query,$inputData);
		}

	}
	public function insertInSubscriptionLog($bucket,$amount,$clientId)
	{

		$this->initiateModel('write','SUMS');
		$query = "insert into SubscriptionLog (ClientUserId,SumsUserId,SubscriptionId,ConsumedBaseProductId,ConsumedId,ConsumedIdType,NumberConsumed,ConsumptionStartDate,ConsumptionEndDate) values";

		$inputData = array();
		foreach ($bucket as $subscriptionId=>$row) {
			foreach ($row as $key=>$value)
			{
				$query .= "(?,?,?,?,?,?,?,?,?),";
				$inputData[] = $clientId;
				$inputData[] = $clientId;
				$inputData[] = $subscriptionId;
				$inputData[] = 127;
				$inputData[] = $value['id']; 
				$inputData[] = 'Naukri_Lead';
				$inputData[] =  $amount;
				$inputData[] =  date("Y-m-d H:i:s");
				$inputData[] = date("Y-m-d H:i:s");	
			}
			
			
		}
		$query = substr($query, 0,-1);
		
		$this->dbHandle->query($query,$inputData);
		
	}


	public function getClientSubscription($client_id)
	{

		$this->initiateModel('read','SUMS');

		$totatCredit = $numberOfLeads*CREDITS_FOR_NAUKRI_LEADS;
		$query = "select * from Subscription_Product_Mapping spm join Subscription s on s.SubscriptionId = spm.SubscriptionId where spm.BaseProductId = 127 and spm.SubscriptionEndDate >= now() and spm.baseProdRemainingQuantity > 60 and s.subscrStatus='ACTIVE' and s.ClientUserId = ?";

		$result = $this->dbHandle->query($query,array($client_id))->result_array();
		return $result;
	}

	public function storeIndividualMatchedSubscription($inputData,$numberOfQuestionMarks){
		
		$this->initiateModel('write');  
		if (empty($inputData))
        {
            return;
        }
       
        $query = "INSERT INTO naukri_lead_allocation(subscriptionId,userId,clientId) 
				VALUES $numberOfQuestionMarks";
		$this->dbHandle->query($query,$inputData);
    }

    public function storeMatchedResponses($leads, $client_id, $subscriptionId,$campaignType,$quantity_expected=0){
    	$this->initiateModel('write');
		$inputData = array();
		$query = "INSERT INTO naukri_lead_allocation(subscriptionId, userId,clientId,mailSent) VALUES";
		$UserData = array();
		$responseUserGrpMapping = array();
		foreach ($leads as $lead){ 
			$query .= "(?,?,?,?),";
			$inputData[] = $subscriptionId;
			$inputData[] = $lead['id'];
			$inputData[] = $client_id;
			$inputData[] = 'NotToSend';
				
			
			if(!isset($responseUserGrpMapping[$lead['id']]))
			{
				$totalResponse ++;
				$responseUserGrpMapping[$lead['id']] = 1;
				if($campaignType == "quantity"){
					if($totalResponse == $quantity_expected){
						break;
					}
				}
			}
		}
		

		$query = substr($query, 0,-1);
		$this->dbHandle->query($query,$inputData);
		
    }


     public function getLeadsForDurationBased($state_ids, $last_processed_id, $start_date,$client_id){
    	$this->initiateModel();

    	if($state_ids != '' ){
    		$state_where_clause = " state_id in ($state_ids) and";
    	}
	    	    
    	$query = "SELECT nld.id,nld.state_id FROM naukri_leads_data nld
					LEFT OUTER JOIN naukri_lead_allocation nla
  							ON (nld.id =nla.userId and nla.clientId = ?) 
  									WHERE nla.id IS NULL  and $state_where_clause 
  									nld.id <= ? and nld.creation_time >= ?";

  	
	    $result = $this->dbHandle->query($query,array($client_id,$last_processed_id, $start_date))->result_array();
	    return $result;
    }

    public function markSubscriptionProcessed($subscription_id){
    	if($subscription_id<1){
    		return;
    	}

		$this->initiateModel('write');

		$query = "update naukri_leads_subscription set is_processed ='YES' where id = ?";

		$this->dbHandle->query($query,array($subscription_id));
    }

    public function getUnallocatedLeads($last_processed_id){
    	
    	$this->initiateModel();

    	$query = "select id, creation_time, city_id, state_id from naukri_leads_data where id > ?";
	    	    
	    $result = $this->dbHandle->query($query,array($last_processed_id))->result_array();
	    
	    return $result;
    }

     public function getUserAllocationData($userIds){
    	if(!is_array($userIds) || count($userIds) <1){
			return false;
		}
		//_p("writing");
		$this->initiateModel('write');
		$this->dbHandle->select('subscriptionId, userId');
		$this->dbHandle->from('naukri_lead_allocation');
		$this->dbHandle->where_in('userId',$userIds);	
		$result = $this->dbHandle->get()->result_array();
		return $result;
    }

     public function getGroupMatchedSubscriptions($submit_date){

    	if (empty($submit_date)) {
    		return ;
    	}

    	$this->initiateModel();

    	$query = "select id ,campaign_type, quantity_expected, quantity_delivered, end_date from naukri_leads_subscription where status='active' and start_date <= ?";
	    	    
	    $result = $this->dbHandle->query($query,array($submit_date))->result();

	    return $result;	
    }

    public function getCityMatchedSubscriptions($subscrption_id, $state_id){
    	$this->initiateModel();


    	if(count($subscrption_id)<1 || count($state_id)<1){
    		return;
    	}

    	$query = "select id, client_id ,state_ids from naukri_leads_subscription where  status='active' and id IN (?) ";
	    	    
	    $result = $this->dbHandle->query($query,array( $subscrption_id))->result();

	    return $result;
	    
    }

    

    public function updateLastProcessedId($recent_processed_id){
    	if($recent_processed_id<1){
    		return;
    	}

    	$this->initiateModel('write');  

    	$query = "update SALeadAllocationCron set lastprocessedid = ? where process='NAUKRI_LEAD_DELIVERY'";
	    	    
	    $result = $this->dbHandle->query($query,array($recent_processed_id));
    }

     public function getSubscription(){
    	$this->initiateModel('write');
		$this->dbHandle->select('id, campaign_type, end_date, quantity_expected, quantity_delivered');
		$this->dbHandle->from('naukri_leads_subscription');
		$this->dbHandle->where('status','active');
		$this->dbHandle->where('is_processed', 'YES');
		$result = $this->dbHandle->get()->result_array();
		return $result;
    }

     public function markSubscriptionInactive($subscriptionIds){
    	if(!is_array($subscriptionIds) || count($subscriptionIds) <1){
    		return;
    	}

    	$updateData = array(
			'status' 			=> 'inactive',
			'status_updated_at' 	=> date('Y-m-d H:i:s')
		);
    	$this->initiateModel('write');
    	$this->dbHandle->where_in('id',$subscriptionIds);
    	$this->dbHandle->update('naukri_leads_subscription',$updateData);
		
    }

    public function getDistinctSubscriptionsForDelivery(){ 
        $this->initiateModel();

        $this->dbHandle->distinct();
        $this->dbHandle->select('subscriptionId');
        $this->dbHandle->from('naukri_lead_allocation');
        $this->dbHandle->where('mailSent','NO');
        $result = $this->dbHandle->get()->result_array();

        return  $result;
    }

    public function getLeadsToDeliver($subscriptionId){
        if($subscriptionId < 1){
            return;
        }
        $this->initiateModel();
        $sql = "SELECT nla.id, nla.userId, nld.name, nld.email,nld.mobile,nld.city_id, nld.state_id,nld.course, nld.creation_time,nc.city_name,ns.state_name 
        from naukri_lead_allocation nla join naukri_leads_data nld on nld.id = nla.userId and nla.mailSent = 'NO' 
        join naukri_cities nc on nc.city_id = nld.city_id and nc.status='live' 
        join naukri_states ns on nc.state_id = ns.state_id and ns.status='live'  
        where nla.subscriptionId = ? order by nla.id asc";

        return $this->dbHandle->query($sql, array($subscriptionId))->result_array();
    }

    public function markResponsesProcessed($subscription_id, $last_processed_id, $min_processed_id){
        $this->initiateModel('write');

        $update_fields = array('mailSent'=>'YES');

        $this->dbHandle->where('subscriptionId',$subscription_id);
        $this->dbHandle->where('id <=',$last_processed_id);
        $this->dbHandle->where('id >=',$min_processed_id);
        $this->dbHandle->where('mailSent','NO');
        $this->dbHandle->update('naukri_lead_allocation', $update_fields);
     
    }
	public function deductCreditsForNaukriLeads($leads, $clientId,$amount)
    {
    	
    	$this->initiateModel('read','SUMS');
    	
    	$this->dbHandle->trans_start();

		$query = "select BaseProdRemainingQuantity,s.SubscriptionId from Subscription_Product_Mapping spm join Subscription s on s.SubscriptionId = spm.SubscriptionId where spm.BaseProductId = 127 and spm.SubscriptionEndDate >= now() and spm.baseProdRemainingQuantity >= 60 and s.subscrStatus='ACTIVE' and s.ClientUserId = ?";
	
		$valid_subscription = $this->dbHandle->query($query,$clientId)->result_array();

    	//$valid_subscription = $this->getClientSubscription($clientId);
		if(count($valid_subscription)<1)
		{
			return array();
		}
		
		$tillAllocated=0;
		$subscriptionWiseCount = array();
		foreach ($valid_subscription as $subscription)
		{
			$credit = $subscription['BaseProdRemainingQuantity'];
			$numberToAllocate = floor($credit/$amount);	
			$maximumLeadsAvailable = count($leads) - $tillAllocated;
			if($maximumLeadsAvailable == 0)
            {
                break;
            }
			if ($maximumLeadsAvailable < $numberToAllocate)
			{
				$numberToAllocate = $maximumLeadsAvailable;
			}
			if ($numberToAllocate >=1 && $tillAllocated < count($leads) )
			{
				$subscriptionWiseCount[$subscription['SubscriptionId']] = $numberToAllocate;
				$tillAllocated += $numberToAllocate;
			}
		}
		
		if (empty($subscriptionWiseCount))
		{
			return array();
		}

		foreach ($subscriptionWiseCount as $key => $value) {
            $inputData = array();

			$query = "update Subscription_Product_Mapping set BaseProdRemainingQuantity = BaseProdRemainingQuantity - ?  where SubscriptionId = ?";
			$inputData[] = $value * CREDITS_FOR_NAUKRI_LEADS;
			$inputData[] = $key;
         
			$this->dbHandle->query($query,$inputData);
            }
		$this->dbHandle->trans_complete();
    	
    	if ($this->dbHandle->trans_status() === FALSE) {
    		throw new Exception('Transaction Failed');
    	}
		return $subscriptionWiseCount;
    }
    
    public function insertInSubscriptionLogIndividual($mapping,$amount,$userId)
    {
    	
    	$this->initiateModel('write','SUMS');
		$query = "insert into SubscriptionLog (ClientUserId,SumsUserId,SubscriptionId,ConsumedBaseProductId,ConsumedId,ConsumedIdType,NumberConsumed,ConsumptionStartDate,ConsumptionEndDate) values";

		$inputData = array();
		foreach ($mapping as $subscriptionId=>$clientId)
		{
			$query .= "(?,?,?,?,?,?,?,?,?),";
			$inputData[] = $clientId[0];
			$inputData[] = $clientId[0];
			$inputData[] = $clientId[1];
			$inputData[] = 127;
			$inputData[] = $userId; 
			$inputData[] = 'Naukri_Lead';
			$inputData[] =  $amount;
			$inputData[] =  date("Y-m-d H:i:s");
			$inputData[] = date("Y-m-d H:i:s");	
		}
			
		$query = substr($query, 0,-1);
		
		$this->dbHandle->query($query,$inputData);
		
    }	

    public function insertInNaukriCreditDeductionIndividual($mapping,$amount,$userId)
    {
    	$this->initiateModel('write');
		$inputData = array();
		$query = "INSERT INTO naukri_leads_credit_deduction (subscription_id,client_id,credits_deducted,user_id,sums_subscription_id) VALUES";

		foreach ($mapping as $subscriptionId=>$clientId) {
			$query .= "(?,?,?,?,?),";
			$inputData[] = $subscriptionId;
			$inputData[] = $clientId[0];
			$inputData[] = $amount;
			$inputData[] = $userId;
			$inputData[] = $clientId[1];	
		}

		$query = substr($query, 0,-1);
		$this->dbHandle->query($query,$inputData);
		
    }

    public function getLeadData($clientId,$startDate,$endDate)
    {
        $dateClause  = "";
        $limitClause = ' limit 100';
        $inputData = array();
        $inputData[] = $clientId;
        if(!empty($startDate) && !empty($endDate))
        {
            $dateClause = 'and nld.creation_time >= ? and nld.creation_time <= ?';
            $inputData[] = $startDate;
            $inputData[] = $endDate;
            $limitClause = '';
        }

        $this->initiateModel();
        $query = "select name,email,mobile,course,city_id,state_id,creation_time,credits_deducted
                from naukri_lead_allocation nla 
                join naukri_leads_data nld on  (nla.userId = nld.id) 
                join naukri_leads_credit_deduction nlcd on (nld.id = nlcd.user_id) 
                where nla.clientId = ? $dateClause and nlcd.client_id = ? order by nld.creation_time desc $limitClause ";

       
        $inputData[] = $clientId;
        $result= $this->dbHandle->query($query,$inputData)->result_array();
        //  error_log('pulkit'.$this->dbHandle->last_query());
        return $result;
    }

    public function getCityStateMapping()
    {
        $this->initiateModel();
        $query = 'select * from naukri_cities nc join naukri_states ns on (nc.state_id=ns.state_id)';
        return $this->dbHandle->query($query)->result_array();
    }

}
