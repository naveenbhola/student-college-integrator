<?php

class CategoryPageServer extends MX_Controller
{
	private $model;
	
	function index()
	{
		$this->load->model('categoryList/categorypagemodel','',TRUE);
        $this->model = $this->categorypagemodel;
		$config['functions']['getFiltersToHide'] = array('function' => 'CategoryPageServer.getFiltersToHide');
		$config['functions']['getHeaderText'] = array('function' => 'CategoryPageServer.getHeaderText');
		$config['functions']['getDynamicLDBCoursesList'] = array('function' => 'CategoryPageServer.getDynamicLDBCoursesList');
		$config['functions']['getDynamicCategoryList'] = array('function' => 'CategoryPageServer.getDynamicCategoryList');
		$config['functions']['getDynamicLocationList'] = array('function' => 'CategoryPageServer.getDynamicLocationList');
		$config['functions']['getCategoryPageParameters'] = array('function' => 'CategoryPageServer.getCategoryPageParameters');
		$config['functions']['setCategoryPageDataInCacheMemory'] = array('function' => 'CategoryPageServer.setCategoryPageDataInCacheMemory');
		$config['functions']['raiseAlert'] = array('function' => 'CategoryPageServer.raiseAlert');
		$config['functions']['trackFilters'] = array('function' => 'CategoryPageServer.trackFilters');
		$config['functions']['getCronData'] = array('function' => 'CategoryPageServer.getCronData');
		$config['functions']['registerCron'] = array('function' => 'CategoryPageServer.registerCron');
		$config['functions']['updateCron'] = array('function' => 'CategoryPageServer.updateCron');
                $config['functions']['getRegionsForCountries'] = array('function' => 'CategoryPageServer.getRegionsForCountries');
                $config['functions']['getDynamicLocationListForBrowseInstitute'] = array('function' => 'CategoryPageServer.getDynamicLocationListForBrowseInstitute');
		$args = func_get_args(); $method = $this->getMethod($config,$args);  return $this->$method($args[1]);
	}

	function getFiltersToHide($request)
	{
		$parameters = $request->output_parameters();
		$type = $parameters['0'];
		$typeId = $parameters['1'];
	    $response = $this->model->getFiltersToHide($type,$typeId);
        return $this->sendXmlRpcResponse($response);
	}
	
	function getHeaderText($request)
	{
		$parameters = $request->output_parameters();
		$this->load->library('categoryList/CategoryPageRequest');
		$categoryPageRequest = unserialize(base64_decode($parameters['0']));
		$response = $this->model->getHeaderText($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getDynamicLDBCoursesList($request)
	{
		$parameters = $request->output_parameters();
		$this->load->library('categoryList/CategoryPageRequest');
		$this->load->model('location/locationmodel','',TRUE);
		$this->model->init($this->locationmodel);
		//$categoryPageRequest = unserialize(base64_decode($parameters['0']));
		$categoryPageRequest = unserialize(utility_decodeXmlRpcResponse($parameters['0']));
		$response = $this->model->getDynamicLDBCoursesList($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	function getDynamicCategoryList($request)
	{
		$parameters = $request->output_parameters();
		$this->load->library('categoryList/CategoryPageRequest');
		$this->load->model('location/locationmodel','',TRUE);
		$this->model->init($this->locationmodel);
		//$categoryPageRequest = unserialize(base64_decode($parameters['0']));
		$categoryPageRequest = unserialize(utility_decodeXmlRpcResponse($parameters['0']));
		$response = $this->model->getDynamicCategoryList($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	
	function getDynamicLocationList($request)
	{
		$parameters = $request->output_parameters();
		$this->load->library('categoryList/CategoryPageRequest');
		$this->load->model('location/locationmodel','',TRUE);
		$this->model->init($this->locationmodel);
		//$categoryPageRequest = unserialize(base64_decode($parameters['0']));
		$categoryPageRequest = unserialize(utility_decodeXmlRpcResponse($parameters['0']));
		$response = $this->model->getDynamicLocationList($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	
	
	function getCategoryPageParameters($request)
	{
		$parameters = $request->output_parameters();
		$entity = $parameters[0];
		$entityId = $parameters[1];
		$criteria = utility_decodeXmlRpcResponse($parameters[2]);
		$response = $this->model->getCategoryPageParameters($entity,$entityId,$criteria);
		return $this->sendXmlRpcResponse($response);
	}
	
	function setCategoryPageDataInCacheMemory($request)
	{
		$parameters = $request->output_parameters();
		$courseIds = utility_decodeXmlRpcResponse($parameters[0]);
		$this->model->setCategoryPageDataInCacheMemory($courseIds);
		return $this->sendXmlRpcResponse(array('Success'));
	}
	
	function raiseAlert($request)
	{
		$parameters = $request->output_parameters();
		$type = $parameters[0];
		$data = $parameters[1];
		$this->model->raiseAlert($type,$data);
		return $this->sendXmlRpcResponse(array('Success'));
	}
	
	function trackFilters($request)
	{
		$parameters = $request->output_parameters();
		
		$this->load->library('CategoryPageRequest');
		$sessionId = $parameters[0];
		$categoryPageRequest = unserialize(base64_decode($parameters[1]));
		$appliedFilters = unserialize(base64_decode($parameters[2]));
		$resultCount = $parameters[3];
		$this->model->trackFilters($sessionId,$categoryPageRequest,$appliedFilters, $resultCount);
		return $this->sendXmlRpcResponse(array('Success'));
	}
	
	/*
	 * Cron management functions
	 */ 
	function getCronData($request)
	{
		$alreadyRunningCronStatus = 'NO';
		$alreadyRunningCronPid = 0;
		$failCount = 0;
		$lastProcessedTimeWindow = '';
		$alreadyRunningCron = $this->model->getAlreadyRunningCron();

		if($alreadyRunningCron) {
			$alreadyRunningCronId = $alreadyRunningCron->id;
			$alreadyRunningCronPid = $alreadyRunningCron->pid;		
		
			$alreadyRunningCronStatus = 'YES';
			$failCount = $this->model->getCronFailCount($alreadyRunningCronId);
			
			if($failCount >= CP_MAX_CRON_FAIL_COUNT) {
				$lastProcessedTimeWindow = $this->model->getLastProcessedTimeWindow();
			}
		}
		else  {
			$lastProcessedTimeWindow = $this->model->getLastProcessedTimeWindow();
		}
		
		$response = array(
							'alreadyRunningCronStatus' => $alreadyRunningCronStatus,
							'alreadyRunningCronPid' => $alreadyRunningCronPid,
							'failCount' => $failCount,
							'lastProcessedTimeWindow' => $lastProcessedTimeWindow
						);
		return $this->sendXmlRpcResponse($response);					
	}
	
	function registerCron($request)
	{
		$parameters = $request->output_parameters();
		
		$cronPid = (int) $parameters[0];
		$status = $parameters[1];
		$ipAddress = $parameters[2];
		
		/*
		 * Register cron
		 */
		if($cronId = $this->model->registerCron($cronPid,$status,$ipAddress)) {
			$response = array($cronId,'string');
			return $this->xmlrpc->send_response($response);
		}
		else  {
			return $this->xmlrpc->send_error_message('700', 'Unable to register cron (DB error)');
		}
	}
	
	function updateCron($request)
	{
		$parameters = $request->output_parameters();
		
		$cronId = (int) $parameters[0];
		$status = $parameters[1];
		$timeWindow = $parameters[2];
		$stats = utility_decodeXmlRpcResponse($parameters[3]);
		$this->model->updateCron($cronId,$status,$timeWindow,$stats);
		$response = array('success','string');
		return $this->xmlrpc->send_response($response);
	}
         function getDynamicLocationListForBrowseInstitute($request)
	{
		$parameters = $request->output_parameters();
		$this->load->library('categoryList/CategoryPageRequest');
		$this->load->model('location/locationmodel','',TRUE);
		$this->model->init($this->locationmodel);
		$categoryPageRequest = unserialize(base64_decode($parameters['0']));
		$response = $this->model->getDynamicLocationListForBrowseInstitute($categoryPageRequest);
		return $this->sendXmlRpcResponse($response);
	}
	function getRegionsForCountries($request)
	{
		$parameters = $request->output_parameters();
		$requestparams = json_decode($parameters['0'],true);
		error_log("LOCATION_QUERY_REGION".print_r($requestparams,true));
		$response = $this->model->getRegionsForCountries($requestparams);
		return $this->sendXmlRpcResponse($response);
	}
}
