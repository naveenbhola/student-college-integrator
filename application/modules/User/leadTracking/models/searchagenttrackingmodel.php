<?php

class SearchAgentTrackingModel extends MY_Model {
	function __construct(){
		parent::__construct('LDB');
	}

	function getDbHandle($operation='read') {
		if($operation=='read'){
			return $this->getReadHandle();
		}
		else{
        	return $this->getWriteHandle();
		}
	}

	public function getSearchAgentData($search_agent_id){
        $this->dbHandle = $this->getDbHandle();

        $sql = 'select sms.daily_limit as daily_sms_limit,email.daily_limit as daily_email_limit, sa.clientid, sa.deliveryMethod, sa.leads_daily_limit, sa.email_freq, sa.sms_freq, sa.flag_auto_download, sa.flag_auto_responder_sms, sa.flag_auto_responder_email,sa.created_on, sa.type from SASearchAgent sa join SASearchAgentAutoResponder_sms sms on sa.searchagentid= sms.searchagentid join SASearchAgentAutoResponder_email email on email.searchagentid=sa.searchagentid  where sa.SearchAgentId=? and sa.is_active="live"';
        $result_array = $this->dbHandle->query($sql,array($search_agent_id))->result_array();
        return $result_array[0];

    }

    public function getSearchAgentCriteria($search_agent_id){
        $this->dbHandle = $this->getDbHandle();

        $sql = 'select sam.keyname, sam.value,includeActiveUsers  from  SAMultiValuedSearchCriteria sam left join SASearchAgentBooleanCriteria saBool on saBool.searchagentid=sam.searchAlertId where sam.searchAlertId=?';
        $result_array = $this->dbHandle->query($sql,array($search_agent_id))->result_array();
        return $result_array;

    }

    public function getSearchAgentAllocationData($search_agent_id, $start_date, $end_date){
        $this->dbHandle = $this->getDbHandle();

        $sql = 'select auto_download, auto_responder_email, auto_responder_sms  from  SALeadAllocation  where allocationtime >= ? and allocationtime <= ? and agentid=?';
        $result_array = $this->dbHandle->query($sql,array($start_date, $end_date, $search_agent_id))->result_array();

        return $result_array;
    }

    public function getSearchAgentForIndexing($start_date){
        $this->dbHandle = $this->getDbHandle();

        $sql = 'select distinct SearchAgentId as agentid, clientId from  SASearchAgent  where is_active="live"  order by SearchAgentId';

        $result_array = $this->dbHandle->query($sql)->result_array();
        return $result_array;
    }

    public function getRemainingCreditsForGenie($clientId){

        $dbHandle = $this->getDbHandle();       //put SUMS db handle

        $sql = "SELECT SUM(SPM.BaseProdRemainingQuantity) as credits
                FROM SUMS.Subscription_Product_Mapping SPM
                INNER JOIN SUMS.Subscription S ON S.SubscriptionId = SPM.SubscriptionID
                INNER JOIN SUMS.Base_Products B ON SPM.BaseProductId=B.BaseProductId
                WHERE S.ClientUserId = ?
                AND S.SubscrStatus='ACTIVE'
                AND DATE(SPM.SubscriptionEndDate) >= curdate()
                AND DATE(SPM.SubscriptionStartDate) <= curdate()
                AND SPM.Status='ACTIVE'
                AND B.BaseProdCategory = 'Lead-Search'";

        $query = $dbHandle->query($sql,array($clientId));

        $results = $query->result_array();

        return  $results[0]['credits'];
    
    }
}


?>

