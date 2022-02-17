<?php
	$canonicalUrl = $metadata['canonical'];
	if($product == "Category"){
		global $criteriaArray;
		$bannerProperties = array('pageId'=>'CATEGORY', 'pageZone'=>'HEADER','shikshaCriteria' => $criteriaArray);	
	}
	
	$headerComponents = array(
		'js'              			=> array('shikshaCommon'),
		'jsFooter'        			=> array('searchResultPage'),
		'css'						=> array('categoryPageRecat','searchTuple'),
		/*'cssFooter'					=> array('registration', 'recommend'),*/
		'product'         			=> $product,
		'canonicalURL' 				=> $canonicalUrl,
		'title'						=> $metadata['title'],
		'metaDescription' 			=> $metadata['description'],
		'showBottomMargin'			=> false,
		'loadUpgradedJQUERY'		=> 'YES',
        'previousURL' 				=> $metadata['previousURL'],
        'nextURL' 					=> $metadata['nextURL'],
		'lazyLoadJsFiles'			=> array('multipleapply', 'processForm'),
		'bannerProperties' 			=> $bannerProperties,
		'trackForPages'				=> true
	);

	if($showGutterBanner){
		$headerComponents['showGutterBanner'] = 1;
		$headerComponents['bannerPropertiesGutter'] = array('pageId'=>'CATEGORY', 'pageZone'=>'RIGHT_GUTTER','shikshaCriteria' => $criteriaArray);
	}
	if($product == "SearchV2"){
		$headerComponents['noIndexFollow'] = TRUE;
		$headerComponents['jsFooter'][] = 'ana_desktop';
	}
	if($product == "Category" && empty($totalInstituteCount)){
		$headerComponents['noIndexFollow'] = TRUE;
	}
	/*if($product == "AllCoursesPage" && empty($totalCourseCount)){
		$headerComponents['noIndexFollow'] = TRUE;
	}*/
	
	$this->load->view('common/header', $headerComponents);

	if($product == "SearchV2"){
		echo jsb9recordServerTime('NATIONAL_SRP',1);
	}
	else if($product == "Category"){
		echo jsb9recordServerTime('SHIKSHA_RNR_CATEGORY_PAGE',1);
	}
	else if($product == 'AllCoursesPage') {
		echo jsb9recordServerTime('ALL_COURSES_PAGE',1);
	}
?>
<script type="text/javascript">
var compareDiv = 1;
var currentPageName = 'CATEGORYPAGE';
var abTestVersion = '<?php echo $abTestVersion;?>';
// var SEARCH_PAGE_URL_PREFIX = 'http://localshiksha.com/hospitality/colleges/colleges-anantapur';
//var CATPAGE_URL_PREFIX = base_url().'/nationalCategoryList/NationalCategoryList';
var CATPAGE_URL_PREFIX = '<?=base_url();?>' + '/nationalCategoryList/NationalCategoryList';
</script>