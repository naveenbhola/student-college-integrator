<?php
$headerComponents = array(
    'cssBundleMobile' => 'sa-search-page-mobile',
    'canonicalURL'    => $canonicalURL,
    'title'           => $seoTitle,
    'metaDescription' => $metaDescription,
    'hideSeoRevisitFlag' => true,
    'hideSeoRatingFlag' => true,
    'hideSeoPragmaFlag' => true,
    'hideSeoClassificationFlag' => true,
    'pgType'	        => $pgType,
    'metaKeywords'    => '' ,
    'addNoFollow' => $addNoFollow,
    'firstFoldCssPath'    => 'searchPage/css/searchPageFirstFoldCss',
    'deferCSS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);
?>