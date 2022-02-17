<?php 
$headerComponents = array(
        'cssBundle'         => 'sa-scholarship-detail-page',
        'canonicalURL'      => $seoDetails['url'],
        'title'             => $seoDetails['seoTitle'],
        'metaDescription'   => $seoDetails['seoDescription'],
        'pageIdentifier'    => $beaconTrackData['pageIdentifier'], 
        'skipCompareCode'   => true,
        'trackingPageKeyId'	=> 1264,
        'deferCSS'          => true
);
if(!empty($seoDetails['seoKeywords'])){
    $headerComponents['metaKeywords'] = $seoDetails['seoKeywords'];
}

$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
echo jsb9recordServerTime('SA_SCHOLARSHIP_DETAILS_PAGE', 1);
?>


<!-- SCHOLARSHIP ID: <?php echo $scholarshipId;?> -->
