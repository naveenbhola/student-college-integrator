<?php

namespace onlineFormEnterprise\libraries\Tab;

class CustomTab extends AbstractTab{
	
	function __construct($tabId){
        
		parent::__construct($tabId);
    }
	
	public function _setData(){
		
		$tab = $this->dao->checkTab($this->tabId,$this->courseId);
		if(count($tab) == "0"){
			return false;
		}
		$this->name = $tab['tabName'];
		
		$filters = url_base64_decode($_COOKIE['filters-'.$this->key]);
		
		$sorter  = $_COOKIE['sorter-'.$this->key];
		
		if($filters == NULL || $filters == ""){
			$filters = $this->dao->getFilterData($this->tabId);
			$_COOKIE['filters-'.$this->key] = url_base64_encode($filters);
			setcookie('filters-'.$this->key,$_COOKIE['filters-'.$this->key],0,'/',COOKIEDOMAIN);
		}else{
			$this->dao->saveFilterData($filters,$this->tabId);
		}
		
		if($sorter == NULL){
			$sorter = $this->dao->getSorterData($this->tabId);
		}else{
			$this->dao->saveSorterData($sorter,$this->tabId);
		}
		
		$sorter  = json_decode($sorter,true);
		$filters  = json_decode($filters,true);
		$excludedList = json_decode($this->dao->getExclusionList($this->tabId),true);
		if(!$excludedList){
			$excludedList =  array();
		}
		if(!$excludedList['forms']){
			$excludedList['forms'] = array();
		}
		if(!$excludedList['fields']){
			$excludedList['fields'] = array();
		}
		$this->filterService->setFilters($filters);
		$this->sorterService->setSorter($sorter);
		$this->exclusionService->setExcludedFields($excludedList['fields']);
		$this->exclusionService->setExcludedForms($excludedList['forms']);
		return true;
	}
	
	
	public function getAnalyticsData($forms,$headings){
		
		$analyticsData = array();
		foreach($forms as $form){
			foreach($headings as $key=>$field){
				$ff = trim(strtoupper("_".$form['fields'][$key]['value']));
				if(!$ff || $ff == "_"){
					$ff = "_Empty";
				}
				if($analyticsData['forms'][$key]['value'][$ff] == NULL){
					$analyticsData['forms'][$key]['value'][$ff] = 1;
				}else{
					$val=$analyticsData['forms'][$key]['value'][$ff];
					$analyticsData['forms'][$key]['value'][$ff] = $val + 1;
				}
				$analyticsData['forms'][$key]['name'] = $field['name'];
			}
			
		}
		//_p($analyticsData);
		$analyticsData['graphs'] = json_decode($this->dao->getAnalyticsData($this->tabId),true);
		return $analyticsData;
	}
	
	
}