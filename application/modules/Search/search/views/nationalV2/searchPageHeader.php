<?php
	$k = $request->getSearchKeyword();
	if(empty($k)){
		$metaTitle = "Colleges Search - Find the Right College for You | Shiksha.com";
		$metDescription = "Search for colleges, courses, universities, exams, admission, career advice, articles, latest news, discussions, and more education resources at Shiksha.com";
	} else {
		$metaTitle = "Search Results for &#8220;".htmlspecialchars($request->getSearchKeyword())."&#8221; - Shiksha.com";
		$resultsStr = ($totalInstituteCount == 1)? 'result':'results';
		$metDescription = "$totalInstituteCount $resultsStr found for &#8220;".htmlspecialchars($request->getSearchKeyword())."&#8221;. Search for colleges, courses, universities, exams, careers, and more education resources at Shiksha.com";	
	}
	$headerComponents = array(
		'js'              			=> array('shikshaCommon'),
		'jsFooter'        			=> array('lazyload','searchResultPage'),
		'css'						=> array('searchResultPage'),
		'cssFooter'					=> array('registration', 'recommend'),
		'product'         			=> "SearchV2",
		'title'						=> $metaTitle,
		'canonicalURL' 				=> trim(SHIKSHA_HOME, "/") . "/search",
		'metaDescription' 			=> $metDescription,
		'metaKeywords'				=> $metaKeywords,
		'showBottomMargin'			=> false,
		'loadUpgradedJQUERY'		=> 'YES',
		'lazyLoadJsFiles'			=> array('multipleapply', 'processForm', 'userRegistration'),
		'noIndexFollow' 			=> TRUE
	);
	$this->load->view('common/header', $headerComponents);
	echo jsb9recordServerTime('NATIONAL_SRP',1);
?>
<script type="text/javascript">
var compareDiv = 1;
var currentPageName = 'SEARCHPAGE';
</script>