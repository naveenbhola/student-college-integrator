<?php if($zrpByFilter){ ?>
	<div class="zrp-msg">
		<p>No results were found for selected filters.<br>
		Please clear all filters &amp; try again.</p>
		<div class="fltrDiv"><a class="clrFltrs" href="javascript:void(0);" id="zrpClrAll">Clear all filters</a></div>
	</div>
<?php } else if($zrpByOpenSearch){ ?>
	<div class="zrp-msg">
		<?php if($solrOutage==1 || $qerOutage == 1){ ?>
		<p>Something went wrong<br></p>
		<p>Please try again later</p>
		<?php }else{ ?>
		<?php if($staticSearchUrl == true) {?>
		<p>We did not find any results</p>
		<?php } else {?>
		<p>We did not find any results for<br>
		<?php  echo htmlentities($searchedTerm); ?> <?php if(!empty($searchLayerPrefillData['locationNames'])) echo "in ".implode(", ", $searchLayerPrefillData['locationNames']); ?> </p>
		<?php } ?>
		<p>Please try searching again</p>
		<?php } ?>
		<div class="fltrDiv"><a class="clrFltrs" href="#searchLayerContainer" data-transition="slide" data-rel="dialog">Search again</a></div>
	</div>
<?php } ?>