<?php
// if we need to add other elements having data-role = page it can be passed as below
    $footerComponents = array(
                                'pages'=>array('categoryPageFilters','widgets/categoryPageSorter','commonModule/layers/brochureWithRequestCallback','widgets/examScoreFilterRestrictionLayer'),
                                'trackingPageKeyIdForReg' => 483,
                                'commonJSV2'=>true,
                            );
    $this->load->view('commonModule/footerV2',$footerComponents);
?>
<script>
    var categoryPageResultCount = <?=$noOfUniversities;?>;
    var filterSelectedOrder = <?=$filterSelectionOrder?$filterSelectionOrder:'{}'?>;
    var highestOrder    = <?=$highestSelectionOrder?$highestSelectionOder:1?>;
    var categoryPageKey = "<?=$categorypageKey?>";
    var categoryPageUrl = "<?=$ajaxurl?>";
    var catPageTitle = "<?=$catPageTitle?>",requestFilterData={
		'loadFiltersViaSolrForSponsoredFlag': <?php echo ($categoryPageRequest->useSolrToBuildCategoryPage() === true /*&&
																	$categoryPageRequest->getPageNumberForPagination() == 1*/
																	?1:0);?>,
		'noOfUniversities': '<?php echo $noOfUniversities; ?>'
	};
</script>