<?php 
$headerComponents = array(
			'css'					=> array('scholarshipCategoryPageSA'),
            'canonicalURL'          => $seoDetails['url'],
			'title'                 => $seoDetails['seoTitle'],
			'metaDescription'       => $seoDetails['seoDescription'],
            		'deferCSS'              => true,
            		'firstFoldCssPath'      => 'scholarshipPage/scholarshipCategoryPage/css/scholarshipCategoryPageFirstFoldCss'
        );
$this->load->view('commonModule/headerV2', $headerComponents);
?>
