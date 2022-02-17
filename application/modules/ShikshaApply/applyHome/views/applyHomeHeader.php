<?php
$headerComponents = array(
        'cssBundle'         => 'sa-apply-home',
        'canonicalURL'   	=> $seoData['canonicalUrl'],
        'title'				=> $seoData['seoTitle'],
        'metaDescription' 	=> $seoData['seoDescription'],
        'pgType'	        => 'applyHomePage',
        'pageIdentifier'    => $beaconTrackData['pageIdentifier'],
        'trackingPageKeyId' => 903,
        'firstFoldCssPath'	=> 'applyHome/css/applyHomeFirstFoldCss',
        'deferCSS'          => true
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SHIKSHA_APPLY_HOME_PAGE', 1);
?>