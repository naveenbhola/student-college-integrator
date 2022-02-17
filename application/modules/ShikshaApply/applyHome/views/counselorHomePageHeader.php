<?php
$headerComponents = array(
        'cssBundle'            	=> 'sa-counselor-review-page',
        'canonicalURL'   	   	=> $seoData['canonicalUrl'],
        'title'					=> $seoData['seoTitle'],
        'metaDescription' 		=> $seoData['seoDescription'],
        'pgType'	        	=> 'counselorHomePage',
        'pageIdentifier'    	=> $beaconTrackData['pageIdentifier'],
        'skipCompareCode'       => true,
        'trackingPageKeyId'		=> 917,
		'robotsMetaTag'		=> 'NOINDEX, NOFOLLOW',
        'deferCSS'              => true
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_COUNSELOR_HOME_PAGE', 1);
?>