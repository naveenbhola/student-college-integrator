<?php

class PaymentLog
{
    private $client;
    
    function __construct($client = NULL)
    {
        $this->client = $client;
        $this->ci = & get_instance();
    }
    
    function update($payment)
    {
        $log = '';
        if($payment->getStatus()=="Success" || $payment->getStatus()=="Failed")
        {
            $this->ci->config->load('ccavenue_PaymentGatewayINR_settings',TRUE);
            $working_key = $this->ci->config->item('working_key','ccavenue_PaymentGatewayINR_settings');

            $paymentProcessor = $this->ci->load->library('Online/payment/PaymentProcessor');          

            $log_fields = $this->ci->input->post('encResp');
            $log_fields = $paymentProcessor->decrypt($log_fields, $working_key);
            $log = $log_fields;
        }

        $log = json_encode($log);
        $status = "";
        if($payment->getStatus()=="")
        {
            $status = "Started";
        }
        else
        {
            $status = $payment->getStatus();
        }
        
        $paymentLogData = array(
            'paymentId' => $payment->getPaymentId(),
            'orderId'   => $payment->getOrderId(),
            'status'    => $status,
            'mode'      => $payment->getMode(),
            'date'      => date('Y-m-d H:i:s'),
            'log'       => $log
        );
        $this->client->addPaymentLog($paymentLogData);
    }
}
