<?php
class OnlineFormsPageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	private $currentPageName= "Application Forms";
	private $department;
	function __construct($options) {
		$this->department = $options['department'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->coursePageUrlGenerator = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
		$this->nationalCoursePageLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$inputArray = array();
		if($this->department == 'Management'){
			$inputArray['base_course_id'] = MANAGEMENT_COURSE;
			$url = $this->coursePageUrlGenerator->getUrlByParams($inputArray);
			$baseCourse = 'MBA';
		}else if($this->department == 'Engineering'){
			$inputArray['stream_id'] = ENGINEERING_STREAM;
			$baseCourse = 'Engineering';
			$url = $this->nationalCoursePageLib->getUrlByParams($inputArray);
		}else{
			return;
		}
		
		
		if($url != '') {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$this->Breadcrumbs->addItem($baseCourse,$url);
			
			if($this->department == 'Management'){
				$this->Breadcrumbs->addItem(self::RESOURCE_NAME, SHIKSHA_HOME.'/mba/resources');
			}else{
				$this->Breadcrumbs->addItem(self::RESOURCE_NAME);
			}
			
			$this->Breadcrumbs->addItem($this->currentPageName);
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
		}
	}
}