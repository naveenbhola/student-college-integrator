<?php	
$defaultJs = array('common','processForm','category','ajax-api');
if($js){
	$defaultJs = array_merge($defaultJs,$js);
}

if(isset($coursePagesSeoDetails[$selectedTab]['CANONICAL_URL']) && $coursePagesSeoDetails[$selectedTab]['CANONICAL_URL'] != "") {
	$canonicalUrl = $coursePagesSeoDetails[$selectedTab]['CANONICAL_URL'];	
} else {
	$canonicalUrl = $coursePagesSeoDetails[$selectedTab]['URL'];	
}

$shikshaCriteria = 'BMS_'.strtoupper(preg_replace("/[^a-z0-9_]+/i", "_",$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'])).'_CHP';

$headerComponents = array(
	'js'=>$defaultJs,
	'product'=>'coursePagesHeader',	
	'title'	=> $coursePagesSeoDetails[$selectedTab]['TITLE'],
	'canonicalURL' => $canonicalUrl,
	'metaDescription' => $coursePagesSeoDetails[$selectedTab]['DESCRIPTION'],
	'metaKeywords'	=> $coursePagesSeoDetails[$selectedTab]['KEYWORDS'],
	'indentCPGSHeader'=> true,
	'searchEnable' => true,
	'showGutterBanner'=> $showGutterBanner,
	'bannerPropertiesGutter'=> array('pageId'=>'COURSE', 'pageZone'=>'RIGHT_GUTTER'),
	'shikshaCriteria'=> array('keyword'=> $shikshaCriteria),
	'hideSeoRevisitFlag' => true
);

if(empty($coursePagesSeoDetails[$selectedTab]) && !empty($allTabsSeoDetails[$selectedTab])){
	// If we have the information from other places:
	$headerComponents['title'] = $allTabsSeoDetails[$selectedTab]['TITLE'];
	$headerComponents['canonicalURL'] = $allTabsSeoDetails[$selectedTab]['URL'];
	$headerComponents['metaDescription'] = $allTabsSeoDetails[$selectedTab]['DESCRIPTION'];
	$headerComponents['metaKeywords'] = $allTabsSeoDetails[$selectedTab]['KEYWORDS'];
}

/*
 *	If the url entered by user is not the same as the Canonical url of the page then do redirect it..
 */
// $cpgsUrl = $canonicalUrl;
// if($cpgsUrl != "") {
// 	$userEnteredURL = trim($_SERVER['SCRIPT_URI']);
// 	$pos = strpos($cpgsUrl, "#");
// 	if($pos !== false) {			
// 		$len = strlen(substr($cpgsUrl, $pos));			
// 		$cpgsUrl = substr($cpgsUrl, 0, -$len);
// 		$data['myCanonical'] = trim($cpgsUrl);
// 	}
	
// 	if($userEnteredURL != $cpgsUrl) {
// 		redirect($canonicalUrl, 'location', 301);
// 	}
// }

$this->load->view('common/header', $headerComponents);
?>
<div id="course-wrapper">
	<?php if($course_pages_tabselected == 'Home') { ?>
		<h1 class="course-page-title-2" style=""><span><?=$COURSE_HOME_PAGES_LIST[$courseHomePageId]['Name'];?></span> in India</h1>
	<?php } ?>
	<?php //$this->load->view('coursepages/coursePagesTabsBar');	?>
	<!--	Here comes the Tab Bar that will float across the page -->
	<?php //$this->load->view('coursepages/coursePagesFloatingTabsBar'); ?>
	<!--	End of the Tab Bar that will float across the page -->
