<?php

class CourseServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('listing/coursemodel','',TRUE);
		$this->model = $this->coursemodel;
		
		$config['functions']['getDataForCourse'] = array('function' => 'CourseServer.getDataForCourse');
		$config['functions']['getDataForMultipleCourses'] = array('function' => 'CourseServer.getDataForMultipleCourses');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function getDataForCourse($request)
	{
		$parameters = $request->output_parameters();
		$courseId = $parameters['0'];
		$filters = $parameters['1'];
	
        try {
            $response = $this->model->getData($courseId,$filters);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getDataForMultipleCourses($request)
	{
		$parameters = $request->output_parameters();
		$courseIds = utility_decodeXmlRpcResponse($parameters['0']);
		$filters = $parameters['1'];
		
		$isCustomFilter = $parameters['2'];
	
        try {
            $response = $this->model->getDataForMultipleCourses($courseIds,$filters,$isCustomFilter);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
}