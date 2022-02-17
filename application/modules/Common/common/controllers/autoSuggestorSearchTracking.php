<?php

class autoSuggestorSearchTracking extends MX_Controller
{
    function __construct()
    {
        parent::__construct();
        $this->load->model('common/searchtrackingmodel');
        $this->searchTrackingModel = new searchTrackingModel;
    }
    
    public function campusConnectSearchTracking($data)
    {
        $this->searchTrackingModel->insertItemInSearchTracking($data);
    }
}