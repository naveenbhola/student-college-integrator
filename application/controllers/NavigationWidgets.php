<?php

class NavigationWidgets extends MX_Controller{
	
	public function locationLayerBySubcategory($subCatId){
		$this->load->library('categoryList/categoryPageRequest');
		$this->load->builder('LocationBuilder','location');
		$this->load->builder('CategoryBuilder','categoryList');
		$this->load->library('categoryList/clients/CategoryPageClient');
		$this->dao = $this->categorypageclient;
        
        $this->load->library('categoryList/cache/CategoryPageCache');
        $this->cache = $this->categorypagecache;
        
		$locationBuilder = new LocationBuilder;
		$categoryBuilder = new CategoryBuilder;
		$data['categoryRepository'] = $categoryBuilder->getCategoryRepository();
		$data['locationRepository'] = $locationBuilder->getLocationRepository();
		$subCategory = $data['categoryRepository']->find($subCatId);
		$this->request = new CategoryPageRequest();
        $this->request->setData(array("categoryId"=>$subCategory->getParentId(), 'subCategoryId' => $subCatId, 'LDBCourseId' => 1, 'cityId' => 1, 'stateId' => 1, 'countryId' => 2, 'regionId' => 0));
        $data['dynamicLocationList'] = $this->getDynamicLocationList();
		$data['requestWidget'] = $this->request;
 		$this->load->view('common/navigationWidgets/locationLayerBySubcategory',$data);
	}
	
	private function getDynamicLocationList()
    {
		if($data = $this->cache->getDynamicLocationList($this->request)) {
            return $data;
        }
        $data = $this->dao->getDynamicLocationList($this->request);
        $this->cache->storeDynamicLocationList($this->request,$data);
        return $data;
    }
    
    public function aboutMbaWidgetBySubcategory($parentCat,$subCatId,$countryId,$regionId){
		$this->Blog_client = $this->load->library('Blog_client');
		$data['articleWidgetsData'] = $this->Blog_client->getArticleWidgetsData('quick_links', $parentCat, $subCatId, $countryId, $regionId);
		$data['parentCat'] = $parentCat;
		$this->load->builder('LDBCourseBuilder','LDB');
             	$LDBCourseBuilder = new LDBCourseBuilder;
             	$LDBCourseRepository = $LDBCourseBuilder->getLDBCourseRepository();
		$LDBCoursesOfthisSubcategory = $LDBCourseRepository->getLDBCoursesForSubCategory($subCatId);
		$this->load->view('common/navigationWidgets/aboutSubCategoryWidget',$data);
    }
	
	public function newsNUpdatesWidget($parentCat,$subCatId,$countryId,$regionId){
		$this->Blog_client = $this->load->library('Blog_client');
		$data['articleWidgetsData'] = $this->Blog_client->getArticleWidgetsData('latest_news', $parentCat, $subCatId, $countryId, $regionId);
		if($data['articleWidgetsData']){
			$this->load->view('common/navigationWidgets/newsNUpdatesWidget',$data);
		}
	}
	
	
	public function restorePersonalizationToDefault(){
		$this->load->library('common/Personalizer');
		$this->personalizer->resetPersonalization();
		echo "<script>location.reload();</script>";
	}
    
    
}
