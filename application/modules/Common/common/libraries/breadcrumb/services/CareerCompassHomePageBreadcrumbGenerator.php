<?php
class CareerCompassHomePageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $subCategoryId;
	private $currentPageName= "Career Compass";
	private $CategoryObject;
	private $CoursePagesUrlRequest;
	function __construct($options) {
		$this->subCategoryId = $options['subCategoryId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('categoryList/CategoryPageRequest');
		$this->CoursePagesUrlRequest = $this->CI->load->library('coursepages/CoursePagesUrlRequest');
		$this->CategoryPageRequest = new CategoryPageRequest;
		$this->CI->load->builder('CategoryBuilder','categoryList');
        $categoryBuilder    = new CategoryBuilder;
        $CategoryRepository = $categoryBuilder->getCategoryRepository();
        $this->CategoryObject = $CategoryRepository->find($this->subCategoryId);
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$directoryName = $this->CategoryObject->getSeoUrlDirectoryName($this->subCategoryId);
		if($directoryName != '') {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			$this->CategoryPageRequest->setData(array("categoryId" => $this->CategoryObject->getParentId()));
			$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrl();
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);
			
			$this->Breadcrumbs->addItem(self::RESOURCE_NAME, SHIKSHA_HOME.'/'.strtolower(rtrim($directoryName, '/')).'/resources');
			
			$this->Breadcrumbs->addItem($this->currentPageName);
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
		}
	}
}