<?php

class AllCoursesPageLib {
	function __construct() {
		$this->CI =& get_instance();
		
		$this->CI->load->config("nationalCategoryList/nationalConfig");
		$this->field_alias = $this->CI->config->item('FIELD_ALIAS');

		$this->allcoursesmodel = $this->CI->load->model('nationalCategoryList/allcoursesmodel');
	}

	function getUrl($instituteId, $instituteObj) {
		if(empty($instituteObj)) {
			//load institute object to get name and location
		}
		// $instituteName = $instituteObj->getName();
		// $instituteLocation = '';
		
		$url = base_url().'college/'.$instituteName.'-'.$instituteLocation.'/courses-'.$instituteId;

		return $url;
	}

	function getPopularCourses($instituteId) {
		$result = $this->allcoursesmodel->getPopularCourses($instituteId);
		foreach ($result as $key => $value) {
			$courseIds[] = $value['entityId'];
		}
		return $courseIds;
	}

	function getUrlForAppliedFilters($appliedFilters, $instituteObj, $isRelative = false) {
		if(!empty($appliedFilters['base_course'])) {
			if(empty($appliedFilters['base_course']['name']) && !empty($appliedFilters['base_course']['id'])) {

			}
			$baseCourseName = $appliedFilters['base_course']['name'];
			if(!$isRelative)
			    return $instituteObj->getAllContentPageUrl('courses').'/'.sanitizeUrlString($baseCourseName).'-'.$this->field_alias['base_course'];
			else
                return $instituteObj->getRelativeAllContentPageUrl('courses').'/'.sanitizeUrlString($baseCourseName).'-'.$this->field_alias['base_course'];
		}
		if(!empty($appliedFilters['stream'])) {
			// $queryParams[] = $this->field_alias['stream'].'[]='.reset($appliedFilters['stream']);
			if(empty($appliedFilters['stream']['name']) && !empty($appliedFilters['stream']['id'])) {

			}
			$streamName = $appliedFilters['stream']['name'];
            if(!$isRelative)
			    return $instituteObj->getAllContentPageUrl('courses').'/'.sanitizeUrlString($streamName).'-'.$this->field_alias['stream'];
            else
                return $instituteObj->getRelativeAllContentPageUrl('courses').'/'.sanitizeUrlString($streamName).'-'.$this->field_alias['stream'];
		}
		if(is_object($instituteObj)) {
			return $instituteObj->getAllContentPageUrl();
		}
		return implode('&', $queryParams);
	}

	function parseSelectedFilter($selectedFilter) {
		if(empty($selectedFilter)) {
			return ;
		}
		$selectedFilterArr = explode('-', $selectedFilter);
		$value = end($selectedFilterArr);
		
		if(is_numeric($value)) {
			$pageNumber = $value;
			array_pop($selectedFilterArr);
			$value = end($selectedFilterArr);
		}
		
		$this->CI->load->builder('ListingBaseBuilder','listingBase');
		$this->listingBaseBuilder    = new ListingBaseBuilder();
		array_pop($selectedFilterArr);
		switch ($value) {
			case $this->field_alias['base_course']:
				$selectedFilters['base_course'][] = $this->fetchBaseCourseId($selectedFilterArr);
				break;
			case $this->field_alias['stream']:
				$selectedFilters['stream'][] = $this->fetchStreamId($selectedFilterArr);
				break;
			default: show_404();
		}
		return $selectedFilters;
	}

	function fetchBaseCourseId($selectedFilterArr) {
		$this->baseCourseRepo = $this->listingBaseBuilder->getBaseCourseRepository();
		$allbaseCourses = $this->baseCourseRepo->getAllBaseCourses();
		$selectedFilterStr = implode('-', $selectedFilterArr);
		return $this->validateFilterAndFetchId($allbaseCourses, $selectedFilterStr);
	}

	function fetchStreamId($selectedFilterArr) {
		$HierarchyRepository = $this->listingBaseBuilder->getHierarchyRepository();
		$allStreams = $HierarchyRepository->getAllStreams();
		$selectedFilterStr = implode('-', $selectedFilterArr);
		return $this->validateFilterAndFetchId($allStreams, $selectedFilterStr);
	}

	function validateFilterAndFetchId($data, $selectedFilterStr) {
		foreach ($data as $val) {
			$urlString = sanitizeUrlString($val['name']);
			if($urlString == $selectedFilterStr) {
				$selectedId = $val['id'];
				break;
			}
		}
		//user has entered a base course which is not present in our system, so show 404 page
		if(empty($selectedId)) {
			show_404();
		}
		return $selectedId;
	}
}
