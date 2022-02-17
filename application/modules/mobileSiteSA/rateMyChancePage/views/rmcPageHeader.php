<?php
$headerComponents = array(
    'cssBundleMobile'         => 'sa-rmc-mobile',
    'jsRequiredInHeader'=> array("userRegistration","ajax-api","commonSA","registrationSA"),
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
    'firstFoldCssPath'    => 'rateMyChancePage/css/rateMyChanceFirstFoldCss',
    'deferCSS' => true,
    'dontLoadRegistrationJS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);
?>

