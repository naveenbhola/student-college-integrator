<?php
$seoData = $departmentObj->getMetaData();
$seoData['seoUrl'] = $departmentObj->getURL();
$headerComponents = array(
	'css'=>array('studyAbroadListings', 'studyAbroadCommon'),
	// 'bannerProperties' => array('pageId'=>'CATEGORY', 'pageZone'=>$categoryData['page'] .'_TOP', 'shikshaCriteria' => $criteriaArray),
	'canonicalURL'      => $seoData['seoUrl'],	
	'title'             => $seoData['seoTitle'],
	//'metaDescription'   => $seoData['seoDescription'], // this line is commented so as to add New computed Seo-Description from controller
        'metaDescription'   => $seoDescription,
	'metaKeywords'      => $seoData['seoKeywords'],
	'pgType'	        => 'departmentPage',
	'pageIdentifier'	=> $beaconTrackData['pageIdentifier']
);

$this->load->view('common/studyAbroadHeader', $headerComponents);

// echo jsb9recordServerTime('SHIKSHA_ABROAD_COURSE_DETAIL_PAGE',1);
?>
