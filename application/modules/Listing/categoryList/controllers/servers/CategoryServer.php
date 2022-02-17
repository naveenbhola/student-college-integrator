<?php

class CategoryServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('categoryList/categorymodel','',TRUE);
		$this->model = $this->categorymodel;
		
		$config['functions']['getCategory'] = array('function' => 'CategoryServer.getCategory');
		$config['functions']['getMultipleCategories'] = array('function' => 'CategoryServer.getMultipleCategories');
		$config['functions']['getSubCategories'] = array('function' => 'CategoryServer.getSubCategories');
		$config['functions']['getCategoryByLDBCourse'] = array('function' => 'CategoryServer.getCategoryByLDBCourse');
		$config['functions']['getCrossPromotionMappedCategory'] = array('function' => 'CategoryServer.getCrossPromotionMappedCategory');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function getCategory($request)
	{
		$parameters = $request->output_parameters();
		$categoryId = $parameters['0'];

        try {
            $response = $this->model->getCategory($categoryId);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getMultipleCategories($request)
	{
		$parameters = $request->output_parameters();
		$categoryIds = $parameters['0'];
	
        try {
            $response = $this->model->getMultipleCategories($categoryIds);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getSubCategories($request)
	{
		$parameters = $request->output_parameters();
		$categoryId = $parameters['0'];
		$flag = $parameters['1'];
	
        try {
            $response = $this->model->getSubCategories($categoryId,$flag);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getCategoryByLDBCourse($request)
	{
		$parameters = $request->output_parameters();
		$LDBCourseId = $parameters['0'];
	
        try {
            $response = $this->model->getCategoryByLDBCourse($LDBCourseId);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
	
	function getCrossPromotionMappedCategory($request)
	{
		$parameters = $request->output_parameters();
		$categoryId = $parameters['0'];
	
        try {
            $response = $this->model->getCrossPromotionMappedCategory($categoryId);
            return $this->sendXmlRpcResponse($response);
        }
        catch(Exception $e) {
            return $this->sendXmlRpcError($e->getMessage());
        }
	}
}