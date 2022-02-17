<?php

	$headerComponents = array(
            'mobilecss'                 =>   array('mcourse'),
            'm_meta_title'              =>   $seoTitle,
            'm_meta_description'        =>   $metaDescription,
            'canonicalURL'              =>   $canonicalURL,
            'product'					=> 	 'mListingDetailPage',
            'mobilePageName'  			=>   'mCourseDetailPage',
            'ampUrl'                    => $amphtmlUrl,
            'm_meta_keywords'           => $m_meta_keywords
	);
	$this->load->view('/mcommon5/header',$headerComponents);
	echo jsb9recordServerTime('SHIKSHA_MOB_NATIONAL_COURSE_LISTINGS',1);
?>
<link href="https://fonts.googleapis.com/css?family=Open+Sans:300,300italic,400,400italic,600,600italic,700,700italic,800,800italic" rel="stylesheet" type="text/css">
