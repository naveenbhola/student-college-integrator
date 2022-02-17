<?php 
$headerComponents = array(
			'css'					=> array('scholarshipHomePageSA'),
            'canonicalURL'          => $seoDetails['url'],
			'title'                 => $seoDetails['seoTitle'],
			'metaDescription'       => $seoDetails['seoDescription'],
			'firstFoldCssPath'		=> 'scholarshipHomePage/css/FirstFoldCssSA',
			'deferCSS' => true
        );
$this->load->view('commonModule/headerV2', $headerComponents);
?>
