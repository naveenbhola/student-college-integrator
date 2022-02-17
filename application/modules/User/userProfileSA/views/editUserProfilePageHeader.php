<?php
$headerComponents = array(
        'cssBundle'         => 'sa-edit-profile',
        'cssBundleMobile'   => 'sa-edit-profile-mobile',
        'canonicalURL'      => $seoDetails['url'],
        'title'             => $seoDetails['seoTitle'],
        'metaDescription'   => $seoDetails['seoDescription'],
        //'pageIdentifier'    => $beaconTrackData['pageIdentifier'],	
        'firstFoldCssPath'  => $firstFoldCssPath,	
        'skipCompareCode'   => true,
        'hideGNB'           => 'true',
        'hideLoginSignupBar' => 'true',
        'deferCSS'          => true
		);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
if($trackForPages){
	//echo jsb9recordServerTime('', 1);
}
?>