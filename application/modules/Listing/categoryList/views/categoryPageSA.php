<?php

		$mediaData = $request->getMetaData();
		$titleText = $mediaData['title'];
		$requestUrl = clone $request;
		$requestUrl->setData(array('naukrilearning'=>0));
		$canonicalurl = $requestUrl->getCanonicalURL($requestUrl->getPageNumberForPagination());
		/*
		* Next page & Previous page URL generation for rel next and rel prev tags..*/
		
	        $totalResults = $categoryPage->getTotalNumberOfInstitutes();
	        $currentPage = $request->getPageNumberForPagination();
	        $totalPages = ceil($totalResults/$request->getSnippetsPerPage());
	        $previousURL = "";
	        $nextURL = "";
	        if($currentPage < $totalPages) {
		   $nextURL = $requestUrl->getUrl($currentPage+1);
	        }
	        if($currentPage > 1) {		   
		   $previousURL = $requestUrl->getUrl($currentPage-1);
	        }
                global $criteriaArray;
		global $pageName;
		$pageName = "";
		if($categoryPage->getRegion()){
			$pageName = $categoryPage->getRegion()->getName();
		}
		if($pageName == ""){
			$pageName = $categoryPage->getCountry()->getName();
		}
		global $countries;
		$countries = $request->getCountryId();
		//echo "hey".$countries;
		if($countries <= 1){
				$this->load->builder('LocationBuilder','location');
				$locationBuilder = new LocationBuilder;
				$locationRepository = $locationBuilder->getLocationRepository();
				$countriesArray = array();
				foreach($locationRepository->getCountriesByRegion($request->getRegionId()) as $country){
						$countriesArray[] = $country->getId();
				}
				$countries = implode(",",$countriesArray);
		}
		$keyword = 'BMS_'.strtoupper(str_replace('-','_',str_replace(' ','_',$pageName)));
		if($keyword == "BMS_USA"){
				$keyword = "BMS_UNITED_STATES";
		}
		/*** To add distinction between banners for ug,pg,phd ***/
		switch($_COOKIE['ug-pg-phd-catpage'])
		{
		   case 'topstudyabroadUGcourses':
						$keyword .= '_UG';
						break;
		   case 'topstudyabroadPGcourses':
						$keyword .= '_PG';
						break;
		   case 'topstudyabroadPHDcourses':
						$keyword .= '_PHD';
						break;
		}
				   
		/*** END: To add distinction between banners for ug,pg,phd ***/
		
		$criteriaArray = array(     
                'category' => $request->getCategoryId(),     
                'country' => $countries,      
                'city' => $request->getCityId(),     
                'keyword'=>  $keyword
                );
		
		$headerComponents = array(
								'js'=>array('multipleapply','category','user','customCityList','ajax-api'),
								'jsFooter' =>array('lazyload','common','onlinetooltip','processForm'),
								'product'=>'foreign',
                                'taburl' =>  site_url(),
								'bannerProperties' => array(
															'pageId'=>'CATEGORY',
															'pageZone'=>'FOREIGN_PAGE_TOP',
															'shikshaCriteria' => $criteriaArray
															),
								'title'	=>	$titleText,
                                'searchEnable' => true	,
								'canonicalURL' => $canonicalurl,
								'metaDescription' => $mediaData['description'],
								'metaKeywords'	=> $mediaData['keywords'],
                                'isCategoryPageSA' => true,
								'previousURL' => $previousURL,
                                'nextURL' => $nextURL,
				'isCategoryPage' => true
		);
		
		if($request->showGutterBanner()){
				$headerComponents['showGutterBanner'] = 1;
				$headerComponents['bannerPropertiesGutter'] = array('pageId'=>'ABROAD_CATEGORY', 'pageZone'=>'RIGHT_GUTTER');
				$headerComponents['shikshaCriteria'] = $criteriaArray;
				$headerComponents['gutterBannerAlignment'] = "top";
		}
		
		if($request->isAJAXCall()){
				$this->load->view('categoryList/categoryPageListingsSA');
				exit;
		}	

		$this->load->view('common/header', $headerComponents);		
		echo jsb9recordServerTime('SHIKSHA_NEW_STUDY_ABROAD_PAGE',1);
		
?>

<div id="mainWrapper">
	<div id="mainPage">
		<?php $this->load->view('categoryList/categoryPageRightSAHeader');?>
		<!-- Left Column Starts-->
		<?php // $this->load->view('categoryList/categoryPageHidden');
                      $this->load->view('categoryList/categoryPageHiddenSA'); ?>
		<?php $this->load->view('categoryList/categoryPageLeftSA'); ?>
		<!-- Left Column Ends-->
		<!-- Right Column Starts-->
		<?php $this->load->view('categoryList/categoryPageRightSA');  ?>
		<!-- Right Column Ends-->
       </div>
	<div class="clearFix"></div>
	</div>
	<div id="comparePage">
		<?php $this->load->view('categoryList/categoryPageCompare'); ?>
	</div> 
</div>
<div>
	<?php $this->load->view('categoryList/changeCategoryStudyAbroad');?>
</div>	
<?php
$this->load->view('common/footerNew',array('loadUpgradedJQUERY' => 'YES'));
$this->load->view('categoryList/categoryPageJquerySlider');?>
<script src="//<?php echo JSURL; ?>/public/js/<?php echo getJSWithVersion('jquery.royalslider.min'); ?>"></script>
<script>
if($j('#location_filter_div').length) {
		associateListnerToLocationFilterBox();
		reorderLocationList();
		
		if (window.location.hash == "#filters") {				
				var t=setTimeout(setLastFiltersStageOnCategoryPages,1000);				
		}
}
</script>