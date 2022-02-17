<?php
$title 	 = "";
$description = "";
if(!empty($meta_details)){
	$title = $meta_details['title'];
	$description = $meta_details['description'];
}

$keyword = "";
$metaKeywords = "";
$rankingPageId = $ranking_page->getId();
$bannerProperties = array();
if($rankingPageId == 2){
	$bannerProperties = array('pageId' => 'RANKING', 'pageZone'=>'HEADER');
}

$headerComponents = array(
	'js'=>array('common', 'multipleapply','ajax-api', 'processForm', 'customCityList', 'ranking', 'category'),
	'jsFooter' => array('lazyload'),
	'product'=> "ranking",
	'taburl' =>  site_url(),
	'title'	=>	$title,
	'searchEnable' => false,
	'canonicalURL' => $current_page_url,
	'metaDescription' => $description,
	'metaKeywords'	=> $metaKeywords,
	'rankingBannerProperties' => $bannerProperties
);
$this->load->view('common/header', $headerComponents);
?>