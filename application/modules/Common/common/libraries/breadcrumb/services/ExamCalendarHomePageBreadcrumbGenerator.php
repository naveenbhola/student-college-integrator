<?php
class ExamCalendarHomePageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	public $streamId;
	private $currentPageName= "Exam Calendar";
	function __construct($options) {
		$this->directoryName = $options['examCalendarTitle'];
		$this->streamId      = $options['streamId'];
		$this->baseCourseId  = $options['courseId'];
		$this->educationType = $options['educationTypeId'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->Breadcrumbs         = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
		$this->coursepagesHomepage = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$directoryName = $this->directoryName;
		if($directoryName != '') {
			$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
			
			$secondBreadCrumbName = $directoryName;
	
			// get course homepage url
			$courseHomePageArray['base_course_id'] = $this->baseCourseId;
			$url  = $this->coursepagesHomepage->getUrlByParams($courseHomePageArray);
			$secondBreadCrumbUrl = $url;
			$this->Breadcrumbs->addItem($secondBreadCrumbName, $secondBreadCrumbUrl);
			
			if($directoryName =='MBA'){
				$this->Breadcrumbs->addItem(self::RESOURCE_NAME, SHIKSHA_HOME.'/'.strtolower(rtrim($directoryName, '/')).'/resources');
			}else{
				$this->Breadcrumbs->addItem(self::RESOURCE_NAME);
			}
			
			$this->Breadcrumbs->addItem($this->currentPageName);
			return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
		}
	}
}