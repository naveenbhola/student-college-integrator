<?php
$headerComponents = array(
    'cssBundleMobile'=> 'sa-country-home-mobile',
    'title' => ucfirst($seoData['seoTitle']),
    'canonicalURL' => $seoData['canonicalUrl'],
    'metaDescription' => ucfirst($seoData['seoDescription']),
    'openSansFontFlag'=> true,
    'firstFoldCssPath'=> 'css/countryHomeFirstFoldCss',
	'deferCSS' => true
);

$this->load->view('commonModule/headerV2', $headerComponents);
?>
