<?php
class CampusConnectHomePageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	private $currentPageName= "Campus Connect";
	function __construct($options) {
		$this->mappingData = $options['mappingData'];
		$this->mappingnameArray = $options['mappingnameArray'];
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->coursePageUrlGenerator = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
		$this->nationalCoursePageLib = $this->CI->load->library('nationalCategoryList/NationalCategoryPageLib');
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();
		$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);
		$mbaResourceUrl ='';
		if(isset($this->mappingData['stream_id'])){
			$result = $this->coursePageUrlGenerator->getUrlByParams($this->mappingData);
			if($result == ''){
				$result = $this->nationalCoursePageLib->getUrlByParams($this->mappingData);
			}
			$this->Breadcrumbs->addItem($this->mappingnameArray['title'], $result);
		}else if(isset($this->mappingData['substream_id'])){
			$inputData = array();
			$inputData = $this->mappingData;
			$inputData['stream_id'] = $this->mappingnameArray['parent_stream_id'];
			$result = $this->coursePageUrlGenerator->getUrlByParams($inputData);
			if($result == ''){
				$result = $this->nationalCoursePageLib->getUrlByParams($inputData);
			}
			$url['substreamUrl'] = $result;
			$inputData = array();
			$inputData['stream_id'] = $this->mappingnameArray['parent_stream_id'];
			$url['parentStreamUrl'] = $this->nationalCoursePageLib->getUrlByParams($inputData);

			$this->Breadcrumbs->addItem($this->mappingnameArray['parent_stream_name'], $url['parentStreamUrl']);
			$this->Breadcrumbs->addItem($this->mappingnameArray['title'], $url['substreamUrl']);
		}else if(isset($this->mappingData['base_course_id'])){
			$result = $this->coursePageUrlGenerator->getUrlByParams($this->mappingData);
			if($result == ''){
				$result = $this->nationalCoursePageLib->getUrlByParams($this->mappingData);
			}
			$this->Breadcrumbs->addItem($this->mappingnameArray['title'], $result);
			if($this->mappingData['base_course_id'] == MANAGEMENT_COURSE){
				$mbaResourceUrl = SHIKSHA_HOME."/mba/resources";
			}
		}
		$this->Breadcrumbs->addItem('Resources',$mbaResourceUrl);
		$this->Breadcrumbs->addItem($this->currentPageName);
		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
	}
}