<?php

class Payment
{
    private $properties = array(
                                'paymentId' => 0,
                                'userId'    => 0,
                                'onlineFormId' => 0,
                                'instituteId' => 0,
                                'amount' => 0,
                                'mode' => '',
                                'status' => '',
                                'orderId' => '',
                                'bankName' => '',
                                'draftNumber' => '',
                                'draftDate' => ''
                            );

    private $client;
    private $observers = array();
    
    function __construct($paymentId=0)
    {
        $this->setPaymentId($paymentId);
    }
    
    function getDetails()
    {
        $details = NULL;
        
        if($this->getPaymentId())
        {
            $details = $this->client->getPaymentDetailsById($this->getPaymentId());
        }
        else
        {
            $details = $this->client->getPaymentDetailsByUserId($this->getUserId(),$this->getOnlineFormId());   
        }
        
        if(is_array($details) && count($details) > 0)
		{
			return $details[0];
        }
    }
    
    function addNewOnline()
    {
        $this->setMode('Online');
        $this->setStatus('Started');
        return $this->_addNew();
    }
    
    function addNewOffline()
    {
        $this->setMode('Offline');
        $this->setStatus('Started');
        return $this->_addNew();
    }
    
    private function _addNew()
    {
        if($paymentId = $this->_addNewInDB())
        {
            /*
             * Generate orderId and update in database
             */
            $this->setPaymentId($paymentId);
            if($this->_updateOrderId())
            {
                return $paymentId;
            }
        }
    }
    
    function restartOnline()
    {
        $this->setMode('Online');
        return $this->_restart();
    }
    
    function restartOffline()
    {
        $this->setMode('Offline');
        return $this->_restart();
    }
    
    function _restart()
    {
        if(!$this->canRestartPayment())
        {
            return false;
        }
        $orderId = $this->_generateOrderId();
        $this->setOrderId($orderId);
        $this->setStatus('Started');
        return $this->update();
    }
    
    function update()
    {
        $paymentData = $this->_prepareDataForDB();    
        if($this->client->updatePayment($this->getPaymentId(),$paymentData))
        {
            $this->notify();
            return TRUE;
        }
    }
    
    private function _addNewInDB()
    {
        $paymentData = $this->_prepareDataForDB();
        return $this->client->addPayment($paymentData);
    }
    
    private function _updateOrderId()
    {
        $orderId = $this->_generateOrderId();
        $this->setOrderId($orderId);
        return $this->update();
    }
        
    private function _generateOrderId()
    {
        $orderId = $this->getPaymentId().'-'.$this->getUserId().'-'.$this->getOnlineFormId();
        return $orderId;
    }
    
    private function _prepareDataForDB()
    {
        $data = array();
        foreach($this->properties as $key => $value)
        {
            $data[$key] = $value;
        }
        $data['date'] = date('Y-m-d H:i:s');
        return $data;
    }
    
    function canRestartPayment()
    {
        if($this->getMode() == 'offline' || $this->getStatus() == 'Success' || $this->getStatus() == 'Pending')
        {
            return false;
        }
        return true;
    }
    
    /*
     * Observers
     */
    
    function attach(splObserver $observer)
    {
        $this->observers[spl_object_hash($observer)] = $observer;
    }
    
    function detach(splObserver $observer)
    {
        unset($this->observers[spl_object_hash($observer)]);
    }
    
    function notify()
    {
        foreach($this->observers as $observer)
        {
            $observer->update($this);
        }
    }
    
    /*
     * Public setters
     */
    
    function setData($payment_data)
    {
        if(!is_array($payment_data))
        {
            throw new Exception('Invalid input: array required');
        }
        
        foreach($payment_data as $key => $value)
        {
            if(array_key_exists($key,$this->properties))
            {
                $this->properties[$key] = $value;
            }
            else if($key != 'date')
            {
                throw new Exception('Property '.$key.' does not exist in '.__CLASS__.' class');
            }
        }
    }
    
    function setOrderId($orderId)
    {
        $this->properties['orderId'] = $orderId;
    }

    function setPaymentId($paymentId)
    {
        $this->properties['paymentId'] = $paymentId;
    }
    
    function setStatus($status)
    {
        $this->properties['status'] = $status;
    }
    
    function setMode($mode)
    {
        $this->properties['mode'] = $mode;
    }
    
    function setClient($client)
    {
        $this->client = $client;
    }
    
    /*
     * Public getters
     */
    
    function getAmount()
    {
        return $this->properties['amount'];
    }
    
    function getOrderId()
    {
        return $this->properties['orderId'];
    }
    
    function getPaymentId()
    {
        return $this->properties['paymentId'];
    }
    
    function getStatus()
    {
        return $this->properties['status'];
    }
    
    function getMode()
    {
        return $this->properties['mode'];
    }
    
    function getOnlineFormId()
    {
        return $this->properties['onlineFormId'];
    }
    
    function getUserId()
    {
        return $this->properties['userId'];
    }
    
    function getInstituteId()
    {
        return $this->properties['instituteId'];
    }
}
