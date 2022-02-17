<?php
$headerComponents = array(
        'cssBundleMobile'         => 'sa-apply-content-mobile',
        'canonicalURL'   	   	=> $canonicalURL,
        'title'					=> $seoDetails['title'],
        'metaDescription' 		=> $seoDetails['description'],
        'firstFoldCssPath'    => 'contentPage/css/applyContentFirstFoldCssSA',
        'deferCSS' => true
);

$this->load->view('commonModule/headerV2',$headerComponents);

?>

