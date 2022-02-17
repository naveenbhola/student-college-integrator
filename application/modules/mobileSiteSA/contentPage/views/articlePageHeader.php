<?php
$headerComponents = array(
    'cssBundleMobile'         => 'sa-content-article-mobile',
    'canonicalURL'          => $canonicalURL,
    'title'                 => $seoTitle,
    'metaDescription'       => $metaDescription,
    'articleImage' => $imageUrl,
    'pgType'          => $pgType,
    'robotsMetaTag' => $robots,
    'firstFoldCssPath'    => 'contentPage/css/articlePageFirstFoldCssSA',
    'deferCSS' => true
);
$this->load->view('commonModule/headerV2', $headerComponents);
?>