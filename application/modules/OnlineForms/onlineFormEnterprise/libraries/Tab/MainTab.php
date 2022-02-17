<?php

namespace onlineFormEnterprise\libraries\Tab;

class MainTab extends AbstractTab{
	
	function __construct($tabId){
        
		parent::__construct($tabId);
    }
	public function _setData(){
		$this->name = "Main Tab";
		$filters = json_decode(url_base64_decode($_COOKIE['filters-'.$this->key]),true);
		$sorter  = json_decode($_COOKIE['sorter-'.$this->key],true);
		$this->filterService->setFilters($filters);
		$this->sorterService->setSorter($sorter);
		$this->exclusionService->setExcludedFields(array());
		$this->exclusionService->setExcludedForms(array());
		return true;
	}
	
}