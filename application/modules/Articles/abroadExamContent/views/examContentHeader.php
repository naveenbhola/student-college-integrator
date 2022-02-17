<?php
$headerComponents = array(
'cssBundle'         => 'sa-exam-content',
'canonicalURL'      => $seoData["canonicalUrl"],
'title'             => ucfirst($seoData["seoTitle"]),
'metaDescription'   => ucfirst($seoData["seoDescription"]),
'pageIdentifier'    => $beaconTrackData['pageIdentifier'],
'firstFoldCssPath'  => 'abroadExamContent/css/examContentFirstFoldCSS',
'skipCompareCode'   => true,
'trackingPageKeyId' => 698,
'deferCSS'          => true
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
if($trackForPages){
echo jsb9recordServerTime('SA_EXAM_CONTENT_PAGES', 1);
}
