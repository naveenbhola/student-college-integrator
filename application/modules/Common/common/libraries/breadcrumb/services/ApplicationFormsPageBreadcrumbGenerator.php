<?php
class ApplicationFormsPageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	private $currentPageName= "Application Forms";
	private $instituteId;
	function __construct($options) {
		$this->instituteId = $options['instituteId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->builder("nationalInstitute/InstituteBuilder");
		$instituteBuilder = new InstituteBuilder();
		$this->instituteRepository = $instituteBuilder->getInstituteRepository();
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		if($this->instituteId <= 0){
			return;
		}

		$instituteObj = $this->instituteRepository->find($this->instituteId);
		$url = $instituteObj->getURL();
		
		$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
		
		if($url != '') {
			$this->Breadcrumbs->addItem($instituteObj->getName(),$url);
		}else{
			$this->Breadcrumbs->addItem($instituteObj->getName());
		}

		$this->Breadcrumbs->addItem('Application Forms');
		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
	}
}