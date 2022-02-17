<?php

class BannerFinderServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('listing/bannerfindermodel','',TRUE);
		$this->model = $this->bannerfindermodel;
		
		$config['functions']['getCategoryPageBanners'] = array('function' => 'BannerFinderServer.getCategoryPageBanners');
		$config['functions']['getExpiredBanners'] = array('function' => 'BannerFinderServer.getExpiredBanners');
		$config['functions']['getModifiedBanners'] = array('function' => 'BannerFinderServer.getModifiedBanners');
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
	
	function getCategoryPageBanners($request)
	{
		$categoryPageRequest = $this->_loadCategoryPageRequest($request);
		$response = $this->model->getCategoryPageBanners($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getExpiredBanners($request)
	{
		$parameters = $request->output_parameters();
		$numDays = $parameters[0];
		$response = $this->model->getExpiredBanners($numDays);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getModifiedBanners($request)
	{
		$parameters = $request->output_parameters();
		$criteria = utility_decodeXmlRpcResponse($parameters[0]);
		$responce = $this->model->getModifiedBanners($criteria);
		return $this->sendXmlRpcResponse($responce);
	}
}