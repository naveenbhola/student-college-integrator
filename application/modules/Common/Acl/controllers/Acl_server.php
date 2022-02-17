<?php
class Acl_server extends MX_Controller {

    function index() {
        ini_set('max_execution_time', '1800000');
        $this->load->library('xmlrpc');
        $this->load->library('xmlrpcs');
        $this->load->library('aclconfig');
        $this->load->helper('date');
        $this->load->helper('url');
        $this->load->helper('shikshaUtility');
        $config['functions']['checkUserRight'] = array('function' => 'Acl_server.checkUserRight');
        $config['functions']['insertIntouserGroupsMappingTable'] = array('function' => 'Acl_server.insertIntouserGroupsMappingTable');
        $args = func_get_args(); $method = $this->getMethod($config,$args);
        return $this->$method($args[1]);
    }

    function checkUserRight($request) {
        $parameters = $request->output_parameters();
        $appID='12';
        $userId=$parameters['0'];
        $type=$parameters['1'];
	    $this->load->model('acl_model');
	    $finalArr = $this->acl_model->checkUserRight($userId,$type);
        $response = array($finalArr,'struct');
        return $this->xmlrpc->send_response($response);
    }


}
?>
