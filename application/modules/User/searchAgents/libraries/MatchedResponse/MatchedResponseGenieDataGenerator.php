<?php

class MatchedResponseGenieDataGenerator
{
    private $CI;
    private $genies;
    private $model;
    
    function __construct()
    {
        $this->CI = & get_instance();
        
        $this->CI->load->model('searchAgents/matchedresponseagentmodel');
        $this->model = new MatchedResponseAgentModel;
    }
    
    public function set($genies)
    {
        $this->genies = $genies;
    }
    
    public function generate($genies)
    {
        $this->set($genies);
        
        if(!is_array($genies) || count($genies) == 0) {
            return array();
        }
        
         /**
         * Fetch meta data for matched genies
         */
        $genieClients = $this->model->getGenieClients($genies);
        $genieDeliveryPrefs = $this->model->getGenieDeliveryPreferences($genies);
       // $requiredCredits = $this->model->getRequiredCredits($genies);
        $dailyQuota = $this->model->getDailyQuotaForGenies($genies);
        $quotaFilled = $this->model->getQuotaFilledForGenies($genies);
        
        /**
         * Get LDB credits available with each client
         */ 
        $clientCredits = $this->model->getLDBCredits($genieClients);

        $genieData = array();
        foreach($genies as $genieId) {
            
            $genieData[$genieId]['client'] = $genieClients[$genieId];
            $genieData[$genieId]['deliveryPrefs'] = $genieDeliveryPrefs[$genieId];                
            //$genieData[$genieId]['requiredCredits'] = $requiredCredits[$genieId];
            $genieData[$genieId]['dailyQuota'] = $dailyQuota[$genieId];
            $genieData[$genieId]['quotaFilled'] = $quotaFilled[$genieId];
            
            /**
             * Will this genie be able to view/download response
             */
            $autoDownload = TRUE;
            if($genieData[$genieId]['deliveryPrefs']['autoDownload'] == 'NO') {
                $autoDownload = FALSE;
            }
            else if($genieData[$genieId]['quotaFilled']['view'] >= $genieData[$genieId]['dailyQuota']['viewLimit']) {
                $autoDownload = FALSE;
            }
            else if($clientCredits[$genieData[$genieId]['client']] < $genieData[$genieId]['requiredCredits']['pricePerLead']) {
                $autoDownload = FALSE;
            }
            
            /**
             * Will this genie be able to send auto-responder SMS
             */
            $autoResponderSMS = TRUE;
            if($genieData[$genieId]['deliveryPrefs']['autoResponderSMS'] == 'NO') {
                $autoResponderSMS = FALSE;
            }
            else if($genieData[$genieId]['quotaFilled']['sms'] >= $genieData[$genieId]['dailyQuota']['smsLimit']) {
                $autoResponderSMS = FALSE;
            }
            else if(!$autoDownload) {
                $creditsRequiredForSMS = $genieData[$genieId]['requiredCredits']['sms'];
                if($clientCredits[$genieData[$genieId]['client']] < $creditsRequiredForSMS) {
                    $autoResponderSMS = FALSE;
                }
            }
            
            /**
             * Will this genie be able to send auto-responder Email
             */
            $autoResponderEmail = TRUE;
            if($genieData[$genieId]['deliveryPrefs']['autoResponderEmail'] == 'NO') {
                $autoResponderEmail = FALSE;
            }
            else if($genieData[$genieId]['quotaFilled']['email'] >= $genieData[$genieId]['dailyQuota']['emailLimit']) {
                $autoResponderEmail = FALSE;
            }
            else if(!$autoDownload) {
                
                $creditsRequiredForEmail = $genieData[$genieId]['requiredCredits']['email'];
                $creditsAvailable = $clientCredits[$genieData[$genieId]['client']];
                if($autoResponderSMS) {
                    $creditsAvailable -= $creditsRequiredForSMS;
                }
                
                if($creditsAvailable < $creditsRequiredForSMS) {
                    $autoResponderEmail = FALSE;
                }
            }
            
            $genieData[$genieId]['autoDownload'] = $autoDownload;
            $genieData[$genieId]['autoResponderSMS'] = $autoResponderSMS;
            $genieData[$genieId]['autoResponderEmail'] = $autoResponderEmail;
            
        }
        
        return $genieData;
    }
}
