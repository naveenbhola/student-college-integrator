<?php
$headerComponents = array(
	'css'   => array('applyHomePageSA'),
    'title' => ucfirst($seoData['seoTitle']),
    'canonicalURL'     => $seoData['canonicalUrl'],
    'metaDescription'  => ucfirst($seoData['seoDescription']),
    'deferCSS'         => true,
    'firstFoldCssPath' => 'applyHomePage/css/applyPageFirstFoldCssSA'
);
$this->load->view('commonModule/headerV2', $headerComponents);
?>
