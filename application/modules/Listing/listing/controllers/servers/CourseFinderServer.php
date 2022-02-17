<?php

class CourseFinderServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('listing/coursefindermodel','',TRUE);
		$this->model = $this->coursefindermodel;
		
		$config['functions']['getCoursesByInstitute'] = array('function' => 'CourseFinderServer.getCoursesByInstitute');
		$config['functions']['getCoursesByMultipleInstitutes'] = array('function' => 'CourseFinderServer.getCoursesByMultipleInstitutes');
		$config['functions']['getModifiedCourses'] = array('function' => 'CourseFinderServer.getModifiedCourses');
		$config['functions']['getLiveCourses'] = array('function' => 'CourseFinderServer.getLiveCourses');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}
	
	function getCoursesByInstitute($request)
	{
		$parameters = $request->output_parameters();
		$instituteId = $parameters[0];
		$response = $this->model->getCoursesByInstitute($instituteId);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getCoursesByMultipleInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$instituteIds = $parameters[0];
		$response = $this->model->getCoursesByMultipleInstitutes($instituteIds);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getModifiedCourses($request)
	{
		$parameters = $request->output_parameters();
		$criteria = utility_decodeXmlRpcResponse($parameters[0]);
		$response = $this->model->getModifiedCourses($criteria);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getLiveCourses($request)
	{
		$response = $this->model->getLiveCourses();
		return $this->sendXmlRpcResponse($response);
	}
}