<?php 
$headerComponents = array(
			'css'				=> array('userProfilePageSA'),
            'canonicalURL'		=> $seoDetails['url'],
			'title'				=> $seoDetails['seoTitle'],
			'metaDescription'	=> $seoDetails['seoDescription'],
			'customSEORobotsString' => 'NOINDEX, NOFOLLOW',
			'firstFoldCssPath'	=> $firstFoldCssPath,
			'deferCSS'			=> true
        );
$this->load->view('commonModule/headerV2', $headerComponents);
?>