<?php

class MatchedResponseCreditDeductor
{
    private $CI;
    
    private $userId;
    private $genies;
    private $genieData;
    
    private $model;
    private $LDBClient;
    
    private $SUMSProductClient;
    private $SUMSSubscriptionClient;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->model = new MatchedResponseAgentModel;
        
        $this->CI->load->library('LDB_Client');
		$this->LDBClient = new LDB_Client();
        
        $this->CI->load->library('sums_product_client');
		$this->SUMSProductClient = new Sums_Product_client();
        
        $this->CI->load->library('subscription_client');
		$this->SUMSSubscriptionClient = new Subscription_client();
    }
    
    public function set($userId,$genies,$genieData)
    {
        $this->userId = $userId;
        $this->genies = $genies;
        $this->genieData = $genieData;
    }
    
    public function deductCredits($userId,$genies,$genieData, $actual_course_id = '',$userDataArray)
    {
        $this->set($userId,$genies,$genieData);
        
        if(!is_array($genies) || count($genies) == 0) {
            return TRUE;
        }

        foreach($genies as $genie) {

            //exclude credit deduction if already response on client course
            if( $this->model->checkReponseForClient($userId, $genieData[$genie]['client'])> 0 || $this->model->checkIfAlreadyAllocatedToClient($userId, $genieData[$genie]['client']) ){
                $pricePerLead =0;
                $pricePerEmail =0;
                $pricePerSMS = 0;
            }else{
                global $MRPricingArray;
                $streamId = $userDataArray[0]['StreamId'];

                $pricePerLead = $MRPricingArray[$streamId]['view'];
                $pricePerEmail = $MRPricingArray[$streamId]['email'];
                $pricePerSMS = $MRPricingArray[$streamId]['SMS'];
            }

            $isView = FALSE;
            
            if($this->genieData[$genie]['autoDownload']) {
                $this->_consumeCredits($genie,$userId,$pricePerLead,'view', $actual_course_id,$userDataArray);
                $isView = TRUE;
            }
            
            if($this->genieData[$genie]['autoResponderEmail']) {
                $emailCredits = $isView ? 0 : $pricePerEmail;
                $this->_consumeCredits($genie,$userId,$emailCredits,'email', $actual_course_id,$userDataArray);
            }
            
            if($this->genieData[$genie]['autoResponderSMS'] == 'YES') {
                $emailCredits = $isView ? 0 :$pricePerSMS;
                $this->_consumeCredits($genie,$userId,$emailCredits,'sms', $actual_course_id,$userDataArray);
            }
        }
    }
    
    private function _consumeCredits($genie,$userId,$creditsToBeConsumed,$action, $actual_course_id = '',$userDataArray)
    {
        $genieClientId = $this->genieData[$genie]['client'];
        
        /**
         * Fetch all LDB subscriptions for the client
         */ 
        $clientLDBSubscriptions = $this->SUMSProductClient->getAllSubscriptionsForUserLDB(1, array('userId' => $genieClientId));
        
        foreach($clientLDBSubscriptions as $subscription) {
            
            if(!$this->_isValidSubscription($subscription,$creditsToBeConsumed)) {
                continue;    
            }
            
            /**
             * Consume credits
             */ 
            $this->SUMSSubscriptionClient->consumeLDBCredits(12, $subscription['SubscriptionId'], $creditsToBeConsumed, $genieClientId, $genieClientId);
            
            /**
             * Update contact viewed
             */ 
            $contactViewedResponse = $this->LDBClient->sUpdateContactViewed(12, $genieClientId, $userId, $action, $subscription['SubscriptionId'], $creditsToBeConsumed, 'SA_MR', $actual_course_id,$userDataArray);
			
            $contactViewedResponse = json_decode($contactViewedResponse, true);
            
            /**
             * Log this subscription
             */ 
            $this->SUMSSubscriptionClient->updateSubscriptionLog(12, $subscription['SubscriptionId'], $creditsToBeConsumed, $genieClientId, $genieClientId, $subscription['BaseProductId'], $contactViewedResponse[0]['insertId'], $action, date("Y-m-d H:i:s") , date("Y-m-d H:i:s"));
            break;
        }
    }
    
    private function _isValidSubscription($subscription,$creditsToBeConsumed)
    {
        if($subscription['BaseProdCategory'] != 'Lead-Search') {
            return FALSE;    
        }
        
        $currentDate = date('Y-m-d 00:00:00');
        
        if($subscription['SubscriptionStartDate'] > $currentDate || $subscription['SubscriptionEndDate'] < $currentDate) {
            return FALSE;
        }
        
        if(intval($subscription['BaseProdRemainingQuantity']) < intval($creditsToBeConsumed)) {
            return FALSE;
        }
        
        return TRUE;
    }
}
