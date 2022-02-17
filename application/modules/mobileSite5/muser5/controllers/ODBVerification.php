<?php
class ODBVerification extends MX_Controller
{
    function _init() {
        $this->load->model('verificationmodel');
	$this->verificationmodel = new verificationmodel();
    }
    
    //////
    // getMisData()
    // update table on the basis of return value(s) from user action
    /////
    
    function getMisData() {
        $this->_init();
        $params['call_id'] = $_GET['Call_Id'];
        $params['response_code'] = $_GET['status'];
        $params['response_captured'] = $_GET['responseCaptured'];
	error_log('==userInfo=='.'response='.print_r($params,true));
        $this->verificationmodel->updateODBResponse($params);
    }      
}