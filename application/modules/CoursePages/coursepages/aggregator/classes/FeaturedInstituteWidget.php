<?php

include_once '../WidgetsAggregatorInterface.php';

class FeaturedInstituteWidget implements WidgetsAggregatorInterface{

	private $_params = array();
	private $_CI = null;

	public function __construct($params) {

		$this->_params = $params;
		$this->_CI = & get_instance();
		$this->_CI->load->model('coursepages/coursepagecmsmodel');
		$this->cache = $this->_CI->load->library('coursepages/cache/CoursePagesCache');
	}

	public function getWidgetData() {

		$courseHomePageId = $this->_params["courseHomePageId"];
		//$this->cache->deleteSlideInfo($courseHomePageId);
		
		if($this->cache->isCPGSCachingOn()) {
			//var_dump($this->cache->getSlideSlotInfo($courseHomePageId));
			if(!$is_cached = $this->cache->getSlideSlotInfo($courseHomePageId)) {										
				$cache_info = $this->cache->getSlideSlotId($courseHomePageId);
				if(empty($cache_info) || empty($cache_info['data'][0])) {
					$featured_institute_slides = $this->_CI->coursepagecmsmodel->getSlides("",$courseHomePageId);
					$slide_id= "";
				} else {
					$slide_id = $cache_info['slide_id'];
					$featured_institute_slides = $cache_info['data'];
				}

				$response = $this->_reorderSlideSlot($featured_institute_slides, $slide_id);
				$featured_institute_slides = $response['data'];
					
				$this->cache->deleteSlideSlotId($courseHomePageId);
				$this->cache->setSlideSlotId($courseHomePageId,$response);
				$this->cache->setSLideSlotInfo($courseHomePageId);
				
			} else {
				$cache_info = $this->cache->getSlideSlotId($courseHomePageId);
				$featured_institute_slides = $cache_info['data']?$cache_info['data']:array();
			}			
		} else {
			$featured_institute_slides = $this->_CI->coursepagecmsmodel->getSlides("",$courseHomePageId);
		}
		
		//_P($featured_institute_slides);
		$sections_data = $this->_CI->coursepagecmsmodel->getSectionsDetails("",$courseHomePageId);

		return array('key'=>'featuredInstitutes','data'=>(array('slides'=>$featured_institute_slides,'sections'=>$sections_data)));
	}

	private function _reorderSlideSlot($featured_institute_slides,$slide_id) {

		$response_array = array();
		$modified_array = array();

		if(empty($slide_id)) {

			$slide_id = $featured_institute_slides[0]['id']?$featured_institute_slides[0]['id']:'';
			$modified_array = $featured_institute_slides;

		} else {

			$current_set_value = array();
			foreach ($featured_institute_slides as $value) {
				if($value['id'] == $slide_id) {
					$current_set_value = $value;
				} else {
					$modified_array[] = $value;
				}
			}
				
			$modified_array[] = $current_set_value;
			$slide_id = $modified_array[0]['id']?$modified_array[0]['id']:'';
		}

		$response_array['data'] = $modified_array;
		$response_array['slide_id'] = $slide_id;

		return $response_array;
	}
}
