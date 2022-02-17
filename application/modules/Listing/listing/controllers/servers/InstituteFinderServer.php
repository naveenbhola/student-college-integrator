<?php

class InstituteFinderServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('listing/institutefindermodel','',TRUE);
		$this->model = $this->institutefindermodel;
		
		$config['functions']['getCategoryPageInstitutes'] = array('function' => 'InstituteFinderServer.getCategoryPageInstitutes');
		$config['functions']['getTopInstitutesInCategory'] = array('function' => 'InstituteFinderServer.getTopInstitutesInCategory');
		$config['functions']['getModifiedInstitutes'] = array('function' => 'InstituteFinderServer.getModifiedInstitutes');
		$config['functions']['getExpiredStickyInstitutes'] = array('function' => 'InstituteFinderServer.getExpiredStickyInstitutes');
		$config['functions']['getModifiedStickyInstitutes'] = array('function' => 'InstituteFinderServer.getModifiedStickyInstitutes');
		$config['functions']['getExpiredMainInstitutes'] = array('function' => 'InstituteFinderServer.getExpiredMainInstitutes');
		$config['functions']['getModifiedMainInstitutes'] = array('function' => 'InstituteFinderServer.getModifiedMainInstitutes');
		$config['functions']['getLiveInstitutes'] = array('function' => 'InstituteFinderServer.getLiveInstitutes');
		$config['functions']['getCategoryPageStickyInstitutes'] = array('function' => 'InstituteFinderServer.getCategoryPageStickyInstitutes');
		$config['functions']['getCategoryPageMainInstitutes'] = array('function' => 'InstituteFinderServer.getCategoryPageMainInstitutes');
		
		
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	private function _loadCategoryPageRequest($request)
	{
		$parameters = $request->output_parameters();
		$this->load->library('categoryList/CategoryPageRequest');
		$categoryPageRequest = unserialize(base64_decode($parameters['0']));
		if(!($categoryPageRequest instanceof CategoryPageRequest)){
			return $this->sendXmlRpcError("Input is in wrong format : Provide CategoryPageRequest Object");
		}
		return $categoryPageRequest;
	}
	
	function getCategoryPageInstitutes($request)
	{
		$categoryPageRequest = $this->_loadCategoryPageRequest($request);
	
		$this->load->model('location/locationmodel','',TRUE);
		$this->load->model('categoryList/categorymodel','',TRUE);
		$this->load->model('categoryList/categorypagemodel','',TRUE);
		$this->model->init($this->locationmodel,$this->categorymodel,$this->categorypagemodel);
		
		$response = $this->model->getCategoryPageInstitutes($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getTopInstitutesInCategory($request)
	{
		$parameters = $request->output_parameters();
		$categoryId = $parameters[0];
		$response = $this->model->getTopInstitutesInCategory($categoryId);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getModifiedInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$criteria = utility_decodeXmlRpcResponse($parameters[0]);
		$response = $this->model->getModifiedInstitutes($criteria);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getExpiredStickyInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$numDays = $parameters[0];
		$response = $this->model->getExpiredStickyInstitutes($numDays);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getModifiedStickyInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$criteria = utility_decodeXmlRpcResponse($parameters[0]);
		$response = $this->model->getModifiedStickyInstitutes($criteria);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getExpiredMainInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$numDays = $parameters[0];
		$response = $this->model->getExpiredMainInstitutes($numDays);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getModifiedMainInstitutes($request)
	{
		$parameters = $request->output_parameters();
		$criteria = utility_decodeXmlRpcResponse($parameters[0]);
		$response = $this->model->getModifiedMainInstitutes($criteria);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getLiveInstitutes($request)
	{
		$response = $this->model->getLiveInstitutes();
		return $this->sendXmlRpcResponse($response);
	}
	
	function getCategoryPageStickyInstitutes($request)
	{
		$categoryPageRequest = $this->_loadCategoryPageRequest($request);
		$this->load->model('location/locationmodel','',TRUE);
		$this->load->model('categoryList/categorymodel','',TRUE);
		$this->load->model('categoryList/categorypagemodel','',TRUE);
		$this->model->init($this->locationmodel,$this->categorymodel,$this->categorypagemodel);
		$response = $this->model->getStickyInstitutes($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getCategoryPageMainInstitutes($request)
	{
		$categoryPageRequest = $this->_loadCategoryPageRequest($request);
		$this->load->model('location/locationmodel','',TRUE);
		$this->load->model('categoryList/categorymodel','',TRUE);
		$this->load->model('categoryList/categorypagemodel','',TRUE);
		$this->model->init($this->locationmodel,$this->categorymodel,$this->categorypagemodel);
		// $response = $this->model->getMainInstitutes($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	
}