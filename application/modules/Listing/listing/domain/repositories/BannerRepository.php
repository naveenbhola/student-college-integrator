<?php

class BannerRepository extends EntityRepository
{
    private $bannerFinderDao;
    
    function __construct($dao,$cache,$bannerFinderDao)
    {
        parent::__construct($dao,$cache);
        $this->bannerFinderDao = $bannerFinderDao;
        
        $this->CI->load->entity('Banner','listing');
    }
    
    public function getCategoryPageBanners(CategoryPageRequest $categoryPageRequest)
    {
        // load the models
	$this->CI->load->model('listing/bannerfindermodel');
	$bannerFinderModelObj   = new BannerFinderModel;
        $categoryPageBanners    = $bannerFinderModelObj->getCategoryPageBanners($categoryPageRequest);
        
        //$categoryPageBanners = $this->bannerFinderDao->getCategoryPageBanners($categoryPageRequest);
        
        $bannersForCategory = $this->_load($categoryPageBanners['category']);
        $bannersForSubcategory = $this->_load($categoryPageBanners['subcategory']);
        $bannersForCountry = $this->_load($categoryPageBanners['country']);
        
        $categoryPageBanners = array(
                                        'category' => $bannersForCategory,
                                        'subcategory' => $bannersForSubcategory,
                                        'country' => $bannersForCountry
                                    );
        return $categoryPageBanners;
    }
    
    public function getAbroadCategoryPageBanners(AbroadCategoryPageRequest $categoryPageRequest)
    {
    	// load the models
    	$this->CI->load->model('listing/bannerfindermodel');
    	$bannerFinderModelObj   = new BannerFinderModel;
    	$categoryPageBanners    = $bannerFinderModelObj->getCategoryPageBannersForAbroad($categoryPageRequest);
    	$banners = $this->_load($categoryPageBanners);
    	
    	return $banners;
    }
    
    public function getExpiredBanners($numDays)
    {
        return $this->bannerFinderDao->getExpiredBanners($numDays);
    }
    
    public function getModifiedBanners($criteria)
    {
        return $this->bannerFinderDao->getModifiedBanners($criteria);
    }
    
    public function unpublishExpiredBanners()
    {
        return $this->dao->unpublishExpiredBanners();
    }
    
    /*
     * ORM Functions
     */ 
    private function _load($results)
    {
        $banners = array();
        
        if(is_array($results) && count($results)) {
            foreach($results as $bannerKey => $result) {
                $banner = $this->_createBanner($result);
                $banners[$bannerKey] = $banner;
            }
        }
        
        return $banners;
    }
    
    private function _createBanner($result)
    {
        $banner = new Banner;
        $this->fillObjectWithData($banner,$result);
        return $banner;
    }
}