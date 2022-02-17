<?php
class NewsArticlesPageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $CoursePagesUrlRequest;
	public $subCategoryId;
	private $currentPageName= "News & Articles";
	function __construct($options) {
		$this->CoursePagesUrlRequest 	= clone $options['request'];
		$this->subCategoryId 			= $options['subCategoryId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('categoryList/CategoryPageRequest');
		$this->CategoryPageRequest = new CategoryPageRequest;
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$directoryName = $this->CoursePagesUrlRequest->getDirectoryName($this->subCategoryId);
		if($directoryName != '') {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);

			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			$this->CategoryPageRequest->setData(array("categoryId" => $this->CoursePagesUrlRequest->CategoryObject[$this->subCategoryId]->getParentId()));
			$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrl();
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);

			$this->Breadcrumbs->addItem(self::RESOURCE_NAME, SHIKSHA_HOME.'/'.strtolower(rtrim($directoryName, '/')).'/resources');
			
			$fourthBreadCrumbName = $this->CoursePagesUrlRequest->CategoryObject[$this->subCategoryId]->getShortName();
			$this->Breadcrumbs->addItem($fourthBreadCrumbName.' '.$this->currentPageName);

		}
		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
	}
}