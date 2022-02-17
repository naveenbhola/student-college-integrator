<?php
interface breadcrumb {
	const HOME_TEXT 	= 'Home';
	const RESOURCE_NAME = "Resources";
	public function prepareBreadcrumbHtml();
}

class BreadcrumbGenerator implements breadcrumb {
	private $generatorType;
	private $options;
	private $GeneratorService;
    public $CI;
    
	function __construct($generatorOptions) {
		$this->generatorType = $generatorOptions['generatorType'];
		$this->options = $generatorOptions['options'];
	}

	function prepareBreadcrumbHtml() { 
        $this->CI = & get_instance();
        $this->GeneratorService = $this->CI->load->library('common/breadcrumb/services/'.$this->generatorType.'BreadcrumbGenerator', $this->options);
		$BreadCrumbs = $this->GeneratorService->prepareBreadcrumbHtml();
        
        //condition for implementing common view
        if(is_array($BreadCrumbs)) {
        	if($this->generatorType == 'CoursePage'){
        		return $this->CI->load->view('common/breadcrumb/recat_breadcrumb', array('BreadCrumbs'=>$BreadCrumbs), true);
        	}else{
            	return $this->CI->load->view('common/breadcrumb/common_breadcrumb', array('BreadCrumbs'=>$BreadCrumbs), true);        		
        	}        	
        }
        //otherwise use html created from the service breadcrumb
        return $BreadCrumbs;
	}

	function getBreadcrumbHtml($BreadCrumbs) {
        return $BreadCrumbs;
    }

}