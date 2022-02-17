<?php 
$headerComponents = array(
  'cssBundle'         => 'sa-search-starter-page',
  'canonicalURL'      => $canonicalURL,
  'title'             => $seoTitle,
  'metaDescription'   => $metaDescription,
  'pageType'    	  => $pageType,
  'robotsMetaTag'     => 'noindex, nofollow',
  'firstFoldCssPath'  => 'SASearch/css/searchStarterFirstFoldCss',
  'deferCSS'          => true,
  'hideHeader' => true
);

$this->load->view('studyAbroadCommon/saHeader', $headerComponents);
?>