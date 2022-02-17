<?php 
$headerComponents = array(
      'cssBundleMobile'         => 'sa-content-guide-mobile',
      //'css'         => array('newGuidePageSA'),
      'canonicalURL'          => $canonicalURL,
      'title'                 => $seoTitle,
      'metaDescription'       => $metaDescription,
      'articleImage' => $imageUrl,
      'pgType'          => $pgType,
      'robotsMetaTag' => $robots,
      'firstFoldCssPath'    => 'contentPage/css/guidePageFirstFoldCssSA',
      'deferCSS' => true
        );
$this->load->view('commonModule/headerV2', $headerComponents);
?>