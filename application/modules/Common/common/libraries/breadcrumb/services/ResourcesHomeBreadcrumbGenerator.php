<?php
class ResourcesHomeBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $subCategoryId;
	function __construct($options) {
		//$this->subCategoryId  = $options['subCategoryId'];
		$this->baseCourseId   = $options['courseId'];
		$this->directoryName  = $options['directoryName'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		//$this->CI->load->library('categoryList/CategoryPageRequest');
		//$this->CategoryPageRequest = new CategoryPageRequest;
		//$this->CI->load->builder('CategoryBuilder','categoryList');
        //$categoryBuilder    = new CategoryBuilder;
        //$CategoryRepository = $categoryBuilder->getCategoryRepository();
        //$this->CategoryObject = $CategoryRepository->find($this->subCategoryId);
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
		$this->coursepagesHomepage = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		//$directoryName = $this->CategoryObject->getSeoUrlDirectoryName();
		$directoryName = $this->directoryName;
		if($directoryName != '') {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			//$this->CategoryPageRequest->setData(array("categoryId" => $this->CategoryObject->getParentId()));
			//$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrl();

			// get course homepage url
			$courseHomePageArray['base_course_id'] = $this->baseCourseId;
			$url  = $this->coursepagesHomepage->getUrlByParams($courseHomePageArray);
			$secondBreadCrumbUrl = $url;

			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);
			
			$this->Breadcrumbs->addItem(self::RESOURCE_NAME, '');

		}
		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
	}
}