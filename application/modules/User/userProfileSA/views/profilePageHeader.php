<?php
$headerComponents = array(
    'cssBundle'         => 'sa-user-profile',
    'canonicalURL'      => $seoDetails['url'],
    'title'             => $seoDetails['seoTitle'],
    'metaDescription'   => $seoDetails['seoDescription'],
    'robotsMetaTag' => 'NOINDEX, NOFOLLOW',
    'firstFoldCssPath'  => $firstFoldCssPath,
    'trackingPageKeyId' => $trackingPageKeyIdForReg,
    'skipCompareCode'   => true,
    'deferCSS'          => true
);
$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
?>