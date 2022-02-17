<?php
$headerComponents = array(
        'cssBundle'         => 'sa-scholarship-home',
        'canonicalURL'      => $seoDetails['url'],
        'title'             => $seoDetails['seoTitle'],
        'metaDescription'   => $seoDetails['seoDescription'],
        'pageIdentifier'    => $beaconTrackData['pageIdentifier'],	
        'firstFoldCssPath'  => $firstFoldCssPath,	
        'skipCompareCode'   => true,
        'trackingPageKeyId' => 1315,
        'deferCSS'          => true
		);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
if($trackForPages){
	echo jsb9recordServerTime('SA_SCHOLARSHIP_HOMEPAGE', 1);
}
?>
