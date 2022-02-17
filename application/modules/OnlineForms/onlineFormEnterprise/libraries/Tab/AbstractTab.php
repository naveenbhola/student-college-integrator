<?php
namespace onlineFormEnterprise\libraries\Tab;

abstract class AbstractTab{
	protected $courseId;
	protected $filterService;
	protected $sorterService;
	protected $exclusionService;
	protected $name;
	protected $tabId;
	protected $key;
	
	protected $CI;
	protected $dao;
	
	function __construct($tabId)
    {
        $this->CI = & get_instance();
		$this->tabId = $tabId;
        $this->CI->load->model('onlineFormEnterprise/onlineformenterprise_model');
        $this->dao = new \onlineformenterprise_model();
		
		$this->filterService = new \onlineFormEnterprise\services\FilterService();
		$this->sorterService = new \onlineFormEnterprise\services\SorterService();
		$this->exclusionService = new \onlineFormEnterprise\services\ExclusionService();
    }
	
	public function populateTabData($courseId){
		
		$this->courseId = $courseId;
		$this->key = $this->courseId."-".$this->tabId;
		return $this->_setData();
		
	}
	
	public function getCourseId(){
		
		return $this->courseId;
	}
	
	public function getFilterService(){
		
		return $this->filterService;
	}
	
	public function getSorterService(){
		
		return $this->sorterService;
	}
	
	public function getExclusionSevice(){
		
		return $this->exclusionService;
	}
	
	public function getName(){
		
		return $this->name;
	}
	
	public function getHeadings(){
		
		return $this->headings;
	}
	
	public function getKey(){
		
		return $this->key;
	}
	
	public function getAnalyticsData(){}
	
	
}
