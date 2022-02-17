<?php

class LogDrill extends MX_Controller
{
    private $model;
    
    function __construct()
    {
        //$this->load->model('servicemonitormodel');
        //$this->model = $this->servicemonitormodel;
        
        //$this->load->config('service_monitoring');
    }
    
    function index()
    {
        //$stats = json_decode(file_get_contents('/var/www/html/shiksha/edata'), TRUE);
        //$this->load->view('esmain', array('stats' => $stats));
        $this->load->view('ldmain');
    }
}
