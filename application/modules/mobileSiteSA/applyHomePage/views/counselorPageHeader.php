<?php
$headerComponents = array(
        'css'               => array('counselorPageSA'),
        'title'             => ucfirst($seoData['seoTitle']),
        'canonicalURL'      => $seoData['canonicalUrl'],
        'metaDescription'   => ucfirst($seoData['seoDescription']),
        'deferCSS' => true,
	'customSEORobotsString'=> 'NOINDEX, NOFOLLOW'
);

$this->load->view('commonModule/headerV2', $headerComponents);
?>
