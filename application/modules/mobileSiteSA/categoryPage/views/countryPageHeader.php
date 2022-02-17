<?php
$headerComponents = array(
    'cssBundleMobile' => 'sa-country-page-mobile',
    'canonicalURL'          => $canonical,
    'title'                 => $title,
    'metaDescription'       => $metaDescription,
    'firstFoldCssPath'    => 'categoryPage/css/countryPageFFCssSA',
    'deferCSS' => true
);
$this->load->view('commonModule/headerV2', $headerComponents);
?>