<?php

class FailureMatrix extends MX_Controller
{
    private $model;
    
    function __construct()
    {
        $this->load->model('failurematrixmodel');
        $this->model = $this->failurematrixmodel;
        
        $this->load->config('failure_matrix');
    }
    
    function index()
    {
        $data = array();
        
        $serviceId = intval($_REQUEST['id']);
        if($serviceId) {
            $failureData = $this->model->get($serviceId);
            $data['failureData'] = array($failureData);
        }
        else {
            $filters = array();
            $service = $_REQUEST['service'];
            $host = $_REQUEST['host'];
            $failureType = $_REQUEST['failure'];
            $outageType = $_REQUEST['outage'];
            $failoverType = $_REQUEST['failover'];
            
            if($service) {
                list($host, $service) = explode("::", $service);
                $filters['host'] = $host;
                $filters['service'] = $service;
            }
            else if($host) {
                $filters['host'] = $host;
            }
            
            if($failureType) {
                $filters['failure_type'] = $failureType;
            }
            if($outageType) {
                $filters['outage_type'] = $outageType;
            }
            if($failoverType) {
                $filters['failover_type'] = $failoverType;
            }
            
            $failureData = $this->model->fetch($filters);
            $data['failureData'] = $failureData;
        }
        
        $data['hosts'] = $this->config->item('fm_hosts');
        $data['services'] = $this->config->item('fm_services');
        $data['failureTypes'] = $this->config->item('fm_failureTypes');
        $data['outageTypes'] = $this->config->item('fm_outageTypes');
        $data['failoverTypes'] = $this->config->item('fm_failoverTypes');
        
        $data['selectedService'] = $_REQUEST['service'];
        $data['selectedHost'] = $_REQUEST['host'];
        $data['selectedFailureType'] = $_REQUEST['failure'];
        $data['selectedOutageType'] = $_REQUEST['outage'];
        $data['selectedFailoverType'] = $_REQUEST['failover'];
        
        $this->load->view('failurematrix', $data);
    }
    
    function edit($id)
    {
        $data = array();
    
        if($id) {
            $data['id'] = $id;
            $failureData = $this->model->get($id);
            $data['failureData'] = $failureData;
        }
        
        $data['hosts'] = $this->config->item('fm_hosts');
        $data['services'] = $this->config->item('fm_services');
        $data['failureTypes'] = $this->config->item('fm_failureTypes');
        $data['outageTypes'] = $this->config->item('fm_outageTypes');
        $data['failoverTypes'] = $this->config->item('fm_failoverTypes');
        $data['search'] = 'no';
        
        $this->load->view('editfailurematrix', $data);
    }
    
    function save()
    {
        $this->dbLibObj = DbLibCommon::getInstance('AppMonitor');
        $dbHandle = $this->_loadDatabaseHandle('write');
        
        $data = $_POST;
        $oservice = $data['service'];
        list($host, $service) = explode("::", $oservice);
        $data['host'] = $host;
        $data['service'] = $service;
        
        $id = $this->model->save($data);
        header("Location: /FailureMatrix/FailureMatrix/index?id=".$id."&host=".$host."&service=".$oservice."&failure=".$data['failureType']);
    }
}