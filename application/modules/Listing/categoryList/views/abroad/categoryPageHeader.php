<?php
$headerComponents = array(
	'css'               => array('studyAbroadCommon', 'studyAbroadCategoryPage'),
	'canonicalURL'      => $canonicalUrl,	
	'title'             => ucfirst($title),
	'metaDescription'   => ucfirst($metaDescription),
        'bannerProperties' => array(
                                    'pageId'=>'SA',
                                    'pageZone'=>'TOP',
                                    'shikshaCriteria' => $criteriaArray
                                    ),
	'metaKeywords'      => ucfirst($metaKeywords),
	'pgType'	        => 'categoryPage',
    'pageIdentifier'    => $beaconTrackData['pageIdentifier']
);

$this->load->view('common/studyAbroadHeader', $headerComponents);
?>
<script>
    var filterSelectedOrder = <?=$filterSelectionOrder?>;
    var highestOrder    = <?=$highestSelectionOrder?>;
    var categoryPageKey = "<?=$categorypageKey?>";
	var showCompareLayerPage = true;
</script>