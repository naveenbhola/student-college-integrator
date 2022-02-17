<?php

class jsb9Tracking extends MX_Controller
{
	function __construct()
	{
		parent::__construct();
	}

	private function _init(){
		// validation check required
		$this->load->config("JSB9Tracking/JSB9TrackingConfig");
		$this->load->library('JSB9TrackingLib');
		$this->JSBTrackingLib = new JSB9TrackingLib();
	}

	function pagetrends($userEnteredSite="",$moduleName = ""){
		$this->_init();
		$siteList = $this->config->item('SITE_LIST');
		if(!empty($userEnteredSite) && in_array(strtolower($userEnteredSite),array_map('strtolower',$siteList))){
			$userEnteredSite = strtolower($userEnteredSite);
			$selectedSite = '';
			foreach ($siteList as $site) {
				if($userEnteredSite == strtolower($site)){
					$selectedSite = $site;
				}
			}
			$displayData = array();
			if(!empty($selectedSite)){
				if($moduleName ==""){
					$moduleName = $selectedSite;
				}
				$trendStartDate = $_REQUEST['trendStartDate'] ? $_REQUEST['trendStartDate'] : date("Y-m-d", strtotime("-7 day"));
				$trendEndDate = $_REQUEST['trendEndDate'] ? $_REQUEST['trendEndDate'] : date("Y-m-d", strtotime("-1 day"));
				$sourceApplication = $_REQUEST['sourceApplication'] ? $_REQUEST['sourceApplication'] : 'all';
				$teamPageMap = $this->config->item(strtoupper($selectedSite).'_TEAM_PAGE_MAP');
				$selectedModulePages = $this->JSBTrackingLib->getSelectedModulePages($teamPageMap,$moduleName,$sourceApplication,$selectedSite);
				if(count($selectedModulePages) > 0){
					$jsb9TrackingData = $this->JSBTrackingLib->getJSB9DataForSelectedModule($selectedModulePages,$trendEndDate,$trendStartDate,$selectedSite);
					if($jsb9TrackingData){
						$pageAttributes = $this->config->item('PAGE_ATTRIBUTES');
						$jsb9TrackingFormattedData = $this->JSBTrackingLib->formatJSB9TrackingData($jsb9TrackingData, $pageAttributes);
						$displayData['dailyAverageData'] = $jsb9TrackingFormattedData;
						$displayData['pageAttributes'] = array_keys($pageAttributes);
						$displayData['sourceApplicationFilter'] = $this->config->item('SOURCE_APPLICATION');
						$displayData['sourceApplication'] = $sourceApplication;
						$displayData['trendStartDate'] = date("m/d/Y", strtotime($trendStartDate));
						$displayData['trendEndDate'] = date("m/d/Y", strtotime($trendEndDate));

						$displayData['selectedModule'] = $moduleName;
						$displayData['colors'] = $this->config->item('ATTRIBUTE_COLORS');
						$this->load->view("JSB9Tracking/pagetrends", $displayData);
					}
				}
			}
		}
	}
}
