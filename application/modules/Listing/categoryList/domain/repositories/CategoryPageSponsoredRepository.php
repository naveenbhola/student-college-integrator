<?php

class CategoryPageSponsoredRepository extends EntityRepository
{
	function __construct($dao, $cache) {
        parent::__construct($dao, $cache);
		$CI = & get_instance();
		$this->institutefindermodel = $CI->load->model('listing/institutefindermodel','');
		$this->locationmodel = $CI->load->model('location/locationmodel','',TRUE);
		$this->categorymodel = $CI->load->model('categoryList/categorymodel','',TRUE);
		$this->institutefindermodel->init($this->locationmodel, $this->categorymodel);
    }
	
	public function getStickyListings(CategoryPageRequest $categoryPageRequest) {
		if(empty($categoryPageRequest)){
			return array();
		}
		$stickyInstitutes = $this->cache->getCategoryPageStickyListings($categoryPageRequest);
		if(empty($stickyInstitutes)) {
			$institutes 		= $this->institutefindermodel->getStickyInstitutes($categoryPageRequest);
			$stickyInstitutes   = $this->_applyCategoryFilter($categoryPageRequest, $institutes);
			$this->cache->storeCategoryPageStickyListings($categoryPageRequest, $stickyInstitutes);	
		}
		if($stickyInstitutes == 'EMPTY RESULT') {
			$stickyInstitutes = array();
		}
		return $stickyInstitutes;
	}
	
	public function getMainListings(CategoryPageRequest $categoryPageRequest) {
		if(empty($categoryPageRequest)){
			return array();
		}
		
		$mainInstitutes 	= $this->cache->getCategoryPageMainListings($categoryPageRequest);
		if(empty($mainInstitutes)) {
			// $institutes 		= $this->institutefindermodel->getMainInstitutes($categoryPageRequest);
			$mainInstitutes   	= $this->_applyCategoryFilter($categoryPageRequest, $institutes);
			$this->cache->storeCategoryPageMainListings($categoryPageRequest, $mainInstitutes);
		}
		if($mainInstitutes == 'EMPTY RESULT') {
			$mainInstitutes = array();
		}
		return $mainInstitutes;
	}
	
	private function _applyCategoryFilter(CategoryPageRequest $request, $data) {
		$consolidatedData = array();
		if(empty($request)){
			return $consolidatedData;
		} else {
			if(isset($data['category']) || isset($data['subcategory']) || isset($data['country'])) {
				$categoryData 		= (array) $data['category'];
				$subcategoryData 	= (array) $data['subcategory'];
				$countryData 		= (array) $data['country'];
				
				if($request->isLDBCoursePage() || $request->isSubcategoryPage()) {
					$consolidatedData = $categoryData + $subcategoryData + $countryData;
				}
				if($request->isMainCategoryPage()) {
					$consolidatedData = $categoryData + $countryData;
				}
			} else {
				$consolidatedData = $data;
			}	
		}
        $returnData = array();
		foreach($consolidatedData as $instituteId => $courses) {
			if(!is_array($returnData[$instituteId])){
				$returnData[$instituteId] = array();
			}
			$returnData[$instituteId] = array_merge($returnData[$instituteId], array_keys($courses));
		}
		return $returnData;
    }
    
}