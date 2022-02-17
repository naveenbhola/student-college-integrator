<?php
$headerComponents = array(
        'css'               => array('studyAbroadCommon','studyAbroadAccountSetting','studyAbroadListings','studyAbroadCategoryPage','studyAbroadSuccessPageApplyHome'),
        'canonicalURL'      => $seoUrl,
        'title' 	        => $seoDetails['title'],
        'metaDescription'   => $seoDetails['description'],
        'hideGNB'           => 'true',
);

$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<script>
	var showCompareLayerPage = true;
</script>