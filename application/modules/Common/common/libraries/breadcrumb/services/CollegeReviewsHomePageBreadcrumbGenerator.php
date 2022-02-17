<?php
class CollegeReviewsHomePageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $baseCourse;
	public $educationType;
	private $currentPageName= "College Reviews";
	private $CategoryObject;
	private $CoursePagesUrlRequest;
	function __construct($options) {
		$this->baseCourse = $options['base_course_id'];
		$this->educationType = $options['education_type'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
		//$this->CoursePagesUrlRequest = $this->CI->load->library('coursepages/CoursePagesUrlRequest');
		$this->CategoryPageRequest = new NationalCategoryPageLib;
		$this->CI->load->builder("listingBase/ListingBaseBuilder");
		$baseCourseBuilder = new ListingBaseBuilder();
		$baseCourseRepo = $baseCourseBuilder->getBaseCourseRepository();
		$this->baseCourseObj = $baseCourseRepo->find($this->baseCourse);
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$directoryName = $this->baseCourseObj->getUrlName();
		if($directoryName != '') {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = strtoupper(rtrim($directoryName, '/'));
			$secondBreadCrumbUrl = $this->CategoryPageRequest->getUrlByParams(array("base_course_id" => $this->baseCourse,"education_type" => $this->educationType));
			
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);
			
			$this->Breadcrumbs->addItem(self::RESOURCE_NAME, SHIKSHA_HOME.'/'.strtolower(rtrim($directoryName, '/')).'/resources');
			
			$this->Breadcrumbs->addItem($this->currentPageName);
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
		}
	}
}