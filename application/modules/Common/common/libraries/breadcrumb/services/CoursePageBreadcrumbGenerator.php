<?php
class CoursePageBreadcrumbGenerator extends BreadcrumbGenerator {

	public $CI;
	private $currentPageName= "Course";
	
	function __construct($options) {			
		$this->displayData = &$options['displayData'];		
	}

	private function _loadDependencies() {
		$this->CI = & get_instance();
		$this->CI->load->builder("listingBase/ListingBaseBuilder");
		$this->ListingBaseBuilder          = new ListingBaseBuilder();

		$this->courseHomeLib = $this->CI->load->library('coursepages/CourseHomePageUrlGenerator');
		
		$this->Breadcrumbs = $this->CI->load->library('common/breadcrumb/system/Breadcrumbs');
	}

	public function prepareBreadcrumbHtml() {
		$this->_loadDependencies();

		$BaseCourseRepository = $this->ListingBaseBuilder->getBaseCourseRepository();
		$StreamRepository     = $this->ListingBaseBuilder->getStreamRepository();
		$SubstreamRepository  = $this->ListingBaseBuilder->getSubstreamRepository();
		$baseCourse           = $this->displayData['courseObj']->getBaseCourse();
		$baseCourseId         = $baseCourse['entry'];
		$mainLocationId       = $this->displayData['currentLocationObj']->getLocationId();		
		$primaryHierarchy     = $this->displayData['courseObj']->getPrimaryHierarchy();
		$courseName           = $this->displayData['courseName'];
		$instituteName        = $this->displayData['instituteName'];
		$instituteURL         = $this->displayData['instituteURL'];

	    if(!empty($baseCourseId)) {
			$baseCourseObj = $BaseCourseRepository->find($baseCourseId);
	    }
	    $streamObj = $StreamRepository->find($primaryHierarchy['stream_id']);
		$streamName = $streamObj->getAlias();
		if(!$streamName) {
			$streamName = $streamObj->getName();
		}
	    
		$cases  = array('popular_course', 'substream', 'stream');
		$this->Breadcrumbs->addItem(self::HOME_TEXT, SHIKSHA_HOME);		
		foreach ($cases as $value) {
			switch ($value) {
				case 'popular_course':
					if($baseCourseId) {
						if($baseCourseObj->getIsPopular()) {
							$casePassed = true;
							$baseCourseName = $baseCourseObj->getAlias();
							if(!$baseCourseName) {
								$baseCourseName = $baseCourseObj->getName();
							}
							$baseCourseUrl = $this->courseHomeLib->getUrlByParams(array('base_course_id'=>$baseCourseId));
							$this->Breadcrumbs->addItem(htmlentities($baseCourseName),$baseCourseUrl);	
						}
					}
					break;
				case 'substream':
					if($primaryHierarchy['substream_id'] > 0) {
						$substreamObj = $SubstreamRepository->find($primaryHierarchy['substream_id']);
						$substreamName = $substreamObj->getAlias();
						if(!$substreamName) {
							$substreamName = $substreamObj->getName();
						}
						$casePassed = true;
						$streamUrl = $this->courseHomeLib->getUrlByParams(array('stream_id'=>$primaryHierarchy['stream_id']));
						$substreamUrl = $this->courseHomeLib->getUrlByParams(array('stream_id'=>$primaryHierarchy['stream_id'],'substream_id'=>$primaryHierarchy['substream_id']));
						
						$this->Breadcrumbs->addItem(htmlentities($streamName),$streamUrl);					
						$this->Breadcrumbs->addItem(htmlentities($substreamName),$substreamUrl);							
					}
					break;
				case 'stream':
					$streamUrl = $this->courseHomeLib->getUrlByParams(array('stream_id'=>$primaryHierarchy['stream_id']));
					$this->Breadcrumbs->addItem(htmlentities($streamName),$streamUrl);		
					$casePassed = true;
					break;
				default:
					# code...
					break;
			}
			if($casePassed) {
				true;
				$caseName = $value;
				break;
			}
		}
		$this->Breadcrumbs->addItem("Courses");		
		$this->Breadcrumbs->addItem($courseName.", ".$instituteName);

		return $this->getBreadcrumbHtml($this->Breadcrumbs->getNamespaceBreadcrumbs());
	}
}
