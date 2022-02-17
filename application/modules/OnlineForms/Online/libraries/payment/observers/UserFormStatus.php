<?php

class UserFormStatus
{
    private $client;
    
    function __construct($client = NULL)
    {
        $this->client = $client;
    }
    
    function update($payment)
    {
        $onlineFormId = $payment->getOnlineFormId();
        $userId = $payment->getUserId();
            
        $payment_status = $payment->getStatus();
        $payment_mode = $payment->getMode();
        $institute_id = $payment->getInstituteId();
        
        $form_status = '';
        
        if($payment_mode == 'Offline')
        {
            if($payment_status == 'Started')
            {
                $form_status = 'draft';
            }
            else if($payment_status == 'Success')
            {
                $form_status = 'paid';
            }
        }
        else if($payment_status == 'Success')
        {
            $form_status = 'paid';   
        }
        
        if($form_status)
        {
            $this->client->setFormStatus(1,$onlineFormId,$userId,$form_status);
        }
	//After the Payment is Done, we also have to set the Institutes assigned number to the form
	if( ($payment_status == 'Success' && $payment_mode!='Offline') || $payment_status == 'Started'){
	      $this->client->setInstituteSpecId($onlineFormId,$userId,$institute_id);
	}
	//End code for Institute Spec ID

    }
}
