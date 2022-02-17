<?php
$headerComponents = array(
    'jsRequiredInHeader'=> array('searchStarterPageSA','commonSA','jquery-ui.min'),
    'canonicalURL'    => $canonicalURL,
    'title'           => $seoTitle,
    'metaDescription' => $metaDescription,
    'hideSeoRevisitFlag' => true,
    'hideSeoRatingFlag' => true,
    'hideSeoPragmaFlag' => true,
    'hideSeoClassificationFlag' => true,
    'metaKeywords'    => '' ,
    'pageType'        => $pageType,
    'customSEORobotsString' => 'noindex, nofollow',
    'firstFoldCssPath' => 'searchPage/css/searchStarterPageFirstFoldCss',
    'deferCSS' => true,
    'dontLoadRegistrationJS' => true,
    'hideHeader' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);
?>