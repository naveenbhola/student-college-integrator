<?php
$headerComponents = array(
    'cssBundleMobile' => 'sa-rmc-success-mobile',
    'canonicalURL'    => $seoUrl,
    'title'           => $seoDetails['title'],
    'metaDescription' => $seoDetails['description'],
    'hideSeoRevisitFlag' => true,
    'hideSeoRatingFlag' => true,
    'hideSeoPragmaFlag' => true,
    'hideSeoClassificationFlag' => true,
    'pgType'	        => $pgType,
    'robotsMetaTag' => $robots,
    'metaKeywords'    => '',
    'deferCSS'         => true,
    'firstFoldCssPath' => 'rateMyChancePage/css/rmcSuccessFirstFoldCss'
);
$this->load->view('commonModule/headerV2',$headerComponents);
?>

