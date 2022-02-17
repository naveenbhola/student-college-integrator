<?php 
$seoData = $courseObj->getMetaData();
$seoData['seoUrl'] = $courseObj->getUrl();
$headerComponents = array(
	'css'=>array('studyAbroadListings', 'studyAbroadCommon','studyAbroadSignUp'),
	'canonicalURL'      => $seoData['seoUrl'],	
	'title'             => $seoData['seoTitle'],
    'metaDescription'   => $seoDescription,
	'metaKeywords'      => $seoData['seoKeywords'],
	'pgType'	        => 'coursePage',
	'pageIdentifier'	=> $beaconTrackData['pageIdentifier']	
);

$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
<!-- COURSE ID: <?php echo $courseObj->getId(); ?> -->
<script>
	var showCompareLayerPage = true;
</script>