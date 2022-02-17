<?php

class LDBCourseServer extends MX_Controller
{
	function index()
	{
		$config['functions']['getLDBCourse'] = array('function' => 'LDBCourseServer.getLDBCourse');
                $config['functions']['getMutlipleLDBCourses'] = array('function' => 'LDBCourseServer.getMutlipleLDBCourses');
                $config['functions']['getLDBCoursesForSubCategory'] = array('function' => 'LDBCourseServer.getLDBCoursesForSubCategory');
		$config['functions']['getLDBCoursesForClientCourse'] = array('function' => 'LDBCourseServer.getLDBCoursesForClientCourse');
		$config['functions']['getSpecializations'] = array('function' => 'LDBCourseServer.getSpecializations');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function getLDBCourse($request)
	{
		$parameters = $request->output_parameters();
		$LDBCourseId = $parameters['0'];
	
		$this->load->model('LDB/ldbcoursemodel','',TRUE);
		
        try {
            $LDBCourse = $this->ldbcoursemodel->getLDBCourse($LDBCourseId);
            return $this->sendXmlRpcResponse($LDBCourse);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
        
        function getMutlipleLDBCourses($request)
	{
		$parameters = $request->output_parameters();
                $LDBCourseIds = utility_decodeXmlRpcResponse($parameters[0]);
		$this->load->model('LDB/ldbcoursemodel','',TRUE);
		
        try {
            $LDBCourses = $this->ldbcoursemodel->getMutlipleLDBCourses($LDBCourseIds);
            return $this->sendXmlRpcResponse($LDBCourses);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getLDBCoursesForSubCategory($request)
	{
		$parameters = $request->output_parameters();
		$subCategoryId = $parameters['0'];
	
		$this->load->model('LDB/ldbcoursemodel','',TRUE);
		
        try {
            $LDBCourses = $this->ldbcoursemodel->getLDBCoursesForSubCategory($subCategoryId);
			return $this->sendXmlRpcResponse($LDBCourses);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getLDBCoursesForClientCourse($request)
	{
		$parameters = $request->output_parameters();
		$clientCourseId = $parameters['0'];
	
		$this->load->model('LDB/ldbcoursemodel','',TRUE);
		
        try {

			$this->load->builder('categoryList/CategoryBuilder');
			$CategoryBuilder = new CategoryBuilder;
			$CategoryRepository = $CategoryBuilder->getCategoryRepository();

			$LDBCourses = $CategoryRepository->courseSubcategoryDesiredCourseMapping($clientCourseId);
			return $this->sendXmlRpcResponse($LDBCourses);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getSpecializations($request)
	{
		$parameters = $request->output_parameters();
		$LDBCourseId = $parameters['0'];
	
		$this->load->model('LDB/ldbcoursemodel','',TRUE);
		
        try {
            $specializations = $this->ldbcoursemodel->getSpecializations($LDBCourseId);
			return $this->sendXmlRpcResponse($specializations);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
}