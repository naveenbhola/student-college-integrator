<?php

class BannerServer extends MX_Controller
{
	private $model;

	function index()
	{
		$this->load->model('listing/bannermodel','',TRUE);
		$this->model = $this->bannermodel;
		
		$config['functions']['unpublishExpiredBanners'] = array('function' => 'BannerServer.unpublishExpiredBanners');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function unpublishExpiredBanners($request)
	{
		$this->model->unpublishExpiredBanners();
		return $this->sendXmlRpcResponse(array('Success'));
	}
}