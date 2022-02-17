<?php
$headerComponents = array(
        'cssBundleMobile' 		=> 'sa-content-exam-mobile',
        'canonicalURL'   	   	=> $seoData['canonicalUrl'],
        'title'					=> ucfirst($seoData['seoTitle']),
        'metaDescription' 		=> ucfirst(strip_tags($seoData['seoDescription'])),
        'firstFoldCssPath'      => 'contentPage/css/examContentFirstFoldCssSA',
        'deferCSS' => true
);
$this->load->view('commonModule/headerV2', $headerComponents);
?>
