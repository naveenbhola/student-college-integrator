<?php
	$title 	 = "";
	$description = "";
	if(!empty($meta_details)){
		$title = $meta_details['title'];
		$description = $meta_details['description'];
	}
	$keyword = "";
	$metaKeywords = "";
	if(isset($ranking_page)){
		$rankingPageId = $ranking_page->getId();
	}
	$bannerProperties = array('pageId' => 'RANKING', 'pageZone'=>'HEADER', 'shikshaCriteria' => array('keyword' => $keyword));

	$headerComponents = array(
		'js'=>array(),
		'jsFooter' => array('lazyload','shikshaCommon','ranking_new','common'),
		'cssFooter'	=> array('registration', 'recommend'),
		'product'=> "ranking",
		'taburl' =>  site_url(),
		'title'	=>	$title,
		'searchEnable' => false,
		'canonicalURL' => $canonical,
		'metaDescription' => $description,
		'metaKeywords'	=> $metaKeywords,
		'lazyLoadJsFiles' => array('multipleapply', 'processForm','userRegistration','customCityList'),
		'rankingBannerProperties' => $bannerProperties
	);
	$this->load->view('common/header', $headerComponents);
	echo jsb9recordServerTime('NATIONAL_RANKING_NEW', 1);
	$this->load->view(RANKING_PAGE_MODULE.'/ranking_banner');
?>