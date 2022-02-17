<?php 
if(isset($staticSearchUrl) && $staticSearchUrl == true){
	$robotMeta = 'index, follow';
}else{
	$robotMeta = 'noindex, nofollow';
}

$headerComponents = array(
	'cssBundle'                   => 'sa-search-page',
	'canonicalURL'                => $canonicalURL,
	'title'                       => $seoTitle,
	'metaDescription'             => $metaDescription,
	'pageIdentifier'              => $beaconTrackData['pageIdentifier'],
	'robotsMetaTag'               => $robotMeta,
	'trackingPageKeyId'           => 475,
	'firstFoldCssPath'            => 'SASearch/css/searchV2FirstFoldCss',
	'deferCSS'                    => true,
	'compareButtonTrackingId'     => 552,
	'compareCookiePageTitle'      => $seoTitle,
	'compareOverlayTrackingKeyId' => 591
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SA_SEARCH_PAGE', 1);
?>