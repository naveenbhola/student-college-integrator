<?php

class PaymentBuilder
{
    private $ci;
    private $client;
    private $payment;
    
    function __construct($client = NULL)
    {
        $this->ci = & get_instance();
        $this->client = $client;
        
        $this->ci->load->library('Online/payment/Payment');
        $this->payment = new Payment();
        $this->payment->setClient($this->client);
    }
    
    function setObservers($observers = array())
    {
        if(is_array($observers))
        {
            foreach($observers as $observer)
            {
                $this->ci->load->library('Online/payment/observers/'.$observer);
                $observerObj = new $observer($this->client);
                $this->payment->attach($observerObj);
            }
        }
    }
    
    function getPayment()
    {
        return $this->payment;
    }
}