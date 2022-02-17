<?php
class ExamPageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $subCategoryId;
	private $currentPageName= "Exam";
	private $CategoryObject;
	private $request;
	function __construct($options) {
		$this->examName = $options['examName'];
		$this->sectionName = $options['sectionName'];
		$this->sectionNameMappings = $options['sectionNameMappings'];
		$this->request = $options['request'];
		$this->subCategoryId = $options['subCategoryId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('categoryList/CategoryPageRequest');
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
		if(!empty($directoryName)) {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			$this->CategoryPageRequest->setData(array("categoryId" => $this->CategoryObject->getParentId()));
			$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrl();
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);
			
			$this->Breadcrumbs->addItem($this->currentPageName, SHIKSHA_HOME.'/'.strtolower($directoryName).'/exam');
			
			//change for new text for breadcrumb
			if(isset($this->sectionName) && !empty($this->sectionName) && $this->sectionName != 'home'){
				$this->Breadcrumbs->addItem(strtoupper($this->examName), $this->request->getUrl('home',true));
			}
			else{
				$this->Breadcrumbs->addItem(strtoupper($this->examName));
			}
			
			if(isset($this->sectionName) && !empty($this->sectionName)){
				if($this->sectionName != 'home'){
					$this->Breadcrumbs->addItem(ucfirst($this->sectionNameMappings[$this->sectionName]));
				}
			}
			
			
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
		}
	}
}