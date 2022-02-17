<?php
$headerComponents = array(
    'cssBundleMobile' => 'sa-shipment-page-mobile',
    'canonicalURL'    => $seoDetails['url'],
    'title'           => $seoDetails['title'],
    'metaDescription' => $seoDetails['description'],
    'hideRightMenu'	  => true,
    'firstFoldCssPath'    => 'css/shipmentPageFirstFoldSA',
    'deferCSS' => true
);
$this->load->view('commonModule/headerV2',$headerComponents);
?>


