<?php 
$headerComponents = array(
			'css'             => array('scholarshipPageSA'),
            'canonicalURL'    => $seoDetails['url'],
			'title'           => $seoDetails['seoTitle'],
			'metaDescription' => $seoDetails['seoDescription'],
			'metaKeywords'    => $seoDetails['seoKeywords'],
			'deferCSS' => true
        );
$this->load->view('commonModule/headerV2', $headerComponents);
?>

<!-- SCHOLARSHIP ID: <?php echo $scholarshipObj->getId();?> -->
