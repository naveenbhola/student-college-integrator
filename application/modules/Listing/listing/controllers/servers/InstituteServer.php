<?php

class InstituteServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('listing/institutemodel','',TRUE);
		$this->model = $this->institutemodel;
		
		$config['functions']['getDataForInstitute'] = array('function' => 'InstituteServer.getDataForInstitute');
		$config['functions']['getDataForMultipleInstitutes'] = array('function' => 'InstituteServer.getDataForMultipleInstitutes');
		$config['functions']['unpublishExpiredStickyInstitutes'] = array('function' => 'InstituteServer.unpublishExpiredStickyInstitutes');
		$config['functions']['unpublishExpiredMainInstitutes'] = array('function' => 'InstituteServer.unpublishExpiredMainInstitutes');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function getDataForInstitute($request)
	{
		$parameters = $request->output_parameters();
		$instituteId = $parameters['0'];
		$filters = $parameters['1'];
	
        try {
            $response = $this->model->getData($instituteId,$filters);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getDataForMultipleInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$instituteIds = utility_decodeXmlRpcResponse($parameters['0']);
		$filters = $parameters['1'];
	
        try {
            $response = $this->model->getDataForMultipleInstitutes($instituteIds,$filters);
     		return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	public function unpublishExpiredStickyInstitutes()
	{
		$this->model->unpublishExpiredStickyInstitutes();
		return $this->sendXmlRpcResponse(array('Success'));
	}
	
	public function unpublishExpiredMainInstitutes()
	{
		$this->model->unpublishExpiredMainInstitutes();
		return $this->sendXmlRpcResponse(array('Success'));
	}
}