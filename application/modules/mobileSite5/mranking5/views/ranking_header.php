<script>
	var RANKING_PAGE_MODULE = "rankingV2";
</script>

<?php
$title 	 = "";
$description = "";
$metaKeywords = "";
if(!empty($metaDetails)){
	$title = $metaDetails['title'];
	$description = $metaDetails['description'];
}

$headerComponents = array(
	'm_meta_title'=>$title,
	'm_meta_description'=>$description,
	'm_meta_keywords'=>$metaKeywords,
	'canonicalURL' => $canonical,
	'mobilecss' => array('ranking','tuple'),
	'jsMobile' => array('ranking')
);
$this->load->view('mcommon5/header',$headerComponents);
?>
