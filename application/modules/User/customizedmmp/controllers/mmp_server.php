<?php
/*
 customizemmp_server backend controller
*/

class mmp_server extends MX_Controller {
  
	public function init() {
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		$this->load->model('customizemmp_model');
	}

	public function index() {
		$this->init();
		$this->load->library('xmlrpc');
		$this->load->library('xmlrpcs');
		
		$config['functions']['createMMPPage'] = array('function' => 'mmp_server.createMMPPage');
		$config['functions']['listCustomizedMMP'] = array('function' => 'mmp_server.listCustomizedMMP');
		$config['functions']['marketingPageDetailsById'] = array('function' => 'mmp_server.marketingPageDetailsById');
		$config['functions']['getMMPFormTypes'] = array('function' => 'mmp_server.getMMPFormTypes');
		$config['functions']['updateMMPStatus'] = array('function' => 'mmp_server.updateMMPStatus');
		$config['functions']['formCourses'] = array('function' => 'mmp_server.formCourses');	
		$config['functions']['saveCustomization'] = array('function' => 'mmp_server.saveCustomization');
		$config['functions']['getCustomizations'] = array('function' => 'mmp_server.getCustomizations');
		$config['functions']['getPageType'] = array('function' => 'mmp_server.getPageType');
        $config['functions']['getNormalForms'] = array('function' => 'mmp_server.getNormalForms');
        $config['functions']['getTestPrepCourses'] = array('function' => 'mmp_server.getTestPrepCourses');
        $config['functions']['getTestPrepGroupDetails'] = array('function' => 'mmp_server.getTestPrepGroupDetails');
		$args = func_get_args(); $method = $this->getMethod($config,$args);
		return $this->$method($args[1]);
	}


	
	public function createMMPPage($request) {
		$params = $request->output_parameters();
		$req_params =  json_decode($params[0], true);
		
		$mmpModel = new customizemmp_model();
		$latestPageId = $mmpModel->createMMPPage($req_params);
		
		$return_data = json_encode($latestPageId);
		$response = array($return_data,'int');
		return $this->xmlrpc->send_response($response);
	}
	
	public function listCustomizedMMP($request) {
		$params = $request->output_parameters();
		$mmpModel = new customizemmp_model();
		$data = $mmpModel->listCustomizedMMP();
		$return_data = json_encode($data);
		return $this->xmlrpc->send_response(array($return_data));
	}
	
	public function marketingPageDetailsById($request){
		$params = $request->output_parameters();
		$req_params =  json_decode($params[0], true);
		$page_id = $req_params['page_id'];
		$mmpModel = new customizemmp_model();
		$data = $mmpModel->marketingPageDetailsById($page_id);
		$return_data = json_encode($data);
		return $this->xmlrpc->send_response(array($return_data));
	}


    public function getNormalForms($request){
        $params = $request->output_parameters();
        $req_params =  json_decode($params[0], true);
        $mmpModel = new customizemmp_model();
        $data = $mmpModel->getNormalForms();
        $return_data = json_encode($data);
        return $this->xmlrpc->send_response(array($return_data));
    }
	
	public function getMMPFormTypes($request){
		$params = $request->output_parameters();
		$req_params =  json_decode($params[0], true);
		$mmpModel = new customizemmp_model($req_params);
		$data = $mmpModel->getMMPFormTypes();
		$return_data = json_encode($data);
		return $this->xmlrpc->send_response(array($return_data));
	}
	
	public function updateMMPStatus($request) {
		$params = $request->output_parameters();
		$req_params =  json_decode($params[0], true);
		$mmpModel = new customizemmp_model();
		$returnValue = $mmpModel->updateMMPStatus($req_params);
		$return_data = json_encode($returnValue);
		$response = array($return_data,'int');
		return $this->xmlrpc->send_response($response);
	}

	public function formCourses($request){
                $params = $request->output_parameters();
		$req_params =  json_decode($params[0], true);
		$formid = $req_params['formid'];
                $mmpModel = new customizemmp_model();
                $data = $mmpModel->getCourses($formid);

                $return_data = json_encode($data);
                return $this->xmlrpc->send_response(array($return_data));
	}


    public function getTestPrepCourses($request){
                $params = $request->output_parameters();
        $req_params =  json_decode($params[0], true);
        $formid = $req_params['formid'];
                $mmpModel = new customizemmp_model();
                $data = $mmpModel->getTestPrepCourses($formid);

                $return_data = json_encode($data);
                return $this->xmlrpc->send_response(array($return_data));
    }
    public function getTestPrepGroupDetails($request){
                $params = $request->output_parameters();
        $req_params =  json_decode($params[0], true);
        $groupId = $req_params['groupId'];
                $mmpModel = new customizemmp_model();
                $data = $mmpModel->getTestPrepGroupDetails($groupId);

                $return_data = json_encode($data);
                return $this->xmlrpc->send_response(array($return_data));
    }

	public function saveCustomization($request){
                $params = $request->output_parameters();
                $req_params =  json_decode($params[0], true);
                $formId = $req_params['formId'];
	        $fieldJson = $req_params['fieldJson'];
                $ruleJson = $req_params['ruleJson'];
                $fId = $req_params['fId'];
                $type = $req_params['type'];
		$condition = $req_params['condition'];
		$todo = $req_params['todo'];

                $mmpModel = new customizemmp_model();
                $data = $mmpModel->saveCustom($formId,$fieldJson, $ruleJson, $fId, $type,$condition,$todo);

                $return_data = json_encode($data);
                return $this->xmlrpc->send_response(array($return_data));
	}

	public function getCustomizations($request){
                $params = $request->output_parameters();
                $req_params =  json_decode($params[0], true);
                $formid = $req_params['formId'];
		$id = $req_params['id'];
		$type = $req_params['type'];
                $mmpModel = new customizemmp_model();
                $data = $mmpModel->getCustomizations($formid,$id,$type);

                $return_data = json_encode($data);
                return $this->xmlrpc->send_response(array($return_data));
	}
	
	public function getPageType($request){
                $params = $request->output_parameters();
                $req_params =  json_decode($params[0], true);
                $formId = $req_params['formId'];
                $mmpModel = new customizemmp_model();
                $data = $mmpModel->getPageType($formId);

                //$return_data = json_encode($data);
                return $this->xmlrpc->send_response(array($data));

	}
}
  
