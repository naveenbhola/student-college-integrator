<?php
$headerComponents = array(
        'cssBundle'         =>  'sa-scholarship-cat-page',
        'canonicalURL'      =>  $seoDetails['url'],
        'title'             =>  $seoDetails['seoTitle'],
        'metaDescription'   =>  $seoDetails['seoDescription'],
        'pageIdentifier'    =>  $beaconTrackData['pageIdentifier'],	
        'skipCompareCode'   => true,
        'trackingPageKeyId' => 1273,
        'deferCSS'          => true
);

$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SA_SCHOLARSHIP_CATEGORY_PAGE', 1);
$this->load->view('listing/abroad/widget/breadCrumbs');
?>
