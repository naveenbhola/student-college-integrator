<?php
class ExamHomePageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $subCategoryId;
	private $currentPageName= "Exams";
	private $CategoryObject;
	private $request;
	function __construct($options) {
		$this->courseName = $options['courseName'];
		$this->categoryId = $options['categoryId'];
		$this->subCategoryId = $options['subCategoryId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('categoryList/categoryPageRequest');
		$this->urlRequest = new CategoryPageRequest();

		$this->urlRequest->setData(
			array(
				'categoryId'    => $this->categoryId,
			)
		);
		if(strcasecmp($this->courseName, 'law')){ // for law set to law subcategory category page
			$this->urlRequest->setData(array('subCategoryId' => $this->subCategoryId));
		}
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);

		$this->Breadcrumbs->addItem($this->courseName, $this->urlRequest->getURL());
			
		$this->Breadcrumbs->addItem($this->courseName . ' Exams', '');
			
		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
	}
}
