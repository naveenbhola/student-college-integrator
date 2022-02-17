<?php

class PaymentNotification
{
    private $client;
    private $emailClient;
    private $ci;
    
    function __construct($client = NULL)
    {
        $this->client = $client;
        
        $this->ci = & get_instance();
		$this->ci->load->library('Online/Online_form_mail_client');
        $this->emailClient = new Online_form_mail_client;
    }
    
    function update($payment)
    {
        $onlineFormId = $payment->getOnlineFormId();
        $userId = $payment->getUserId();
        $instituteId = $payment->getInstituteId();
            
        $payment_status = $payment->getStatus();
        $payment_mode = $payment->getMode();
        
        $msgId = array();
        
        if($payment_mode == 'Offline')
        {
            if($payment_status == 'Started')
            {
                $msgId[] = 31;
                $msgId[] = 23;
                $msgId[] = 29;
                $msgId[] = 40;
                $this->emailClient->run($userId,$onlineFormId,'form_successfully_submitted');
                $this->emailClient->run($userId,$onlineFormId,'form_successfully_submitted_institute');
		if($instituteId=='33857'){
                        $this->emailClient->run($userId,$onlineFormId,'liba_payment_advice');
                }

            }
        }
        else if($payment_mode == 'Online')
        {
            if($payment_status == 'Success')
            {
                $msgId[] = 20;
                $msgId[] = 28;
                $this->emailClient->run($userId,$onlineFormId,'form_successfully_submitted');
                $this->emailClient->run($userId,$onlineFormId,'form_successfully_submitted_institute');
				if($instituteId=='33857'){
                        $this->emailClient->run($userId,$onlineFormId,'liba_payment_advice');
                }

            }
            else if($payment_status == 'Failed')
            {
                $msgId[] = 30;
                $this->emailClient->run($userId,$onlineFormId,'online_payment_declined');
            }
        }
        
        if(count($msgId)>0)
        {
            foreach($msgId as $messageId) {
                $this->client->addNotification($onlineFormId,$userId,$instituteId,$messageId);
            }
        }
    }
}
