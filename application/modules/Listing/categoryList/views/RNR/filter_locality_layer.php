<!-- Locality Layer : Starts -->
<?php
	$enabledLocalities = array_keys($enabledLocalitiesWithCount);
	$zoneLocalitiesArr = array();
	foreach($localityFilterValues as $zoneId => $zone) {
		$zoneLocalitiesArr[] = array('id' => $zoneId, 'name' => $zone['name'], 'type' => 'zone');
		foreach($zone['localities'] as $localityId => $localityName) {
			$zoneLocalitiesArr[] = array('id' => $localityId, 'zoneId' => $zoneId, 'name' => $localityName, 'type' => 'locality');
		}
	}
	$totalEntries = count($zoneLocalitiesArr);
	$entriesPerColumn = 10;
?>

<div class="locality-layer" id="localityLayer" style="display: none">
	<div class="locality-head">
		<a href="javascript:void(0);" onclick="hideLocalityLayer();" class="common-sprite close"></a>
		<div class="cate-filter-search flLt">
			<i class="common-sprite cate-search-icon"></i>
			<input type="text" maxlength="28" onkeyup="searchLocalities('localitySearchBox', false);" id="localitySearchBox" value="<?=$localitySearchText?>"/>
			<span onclick="turnOffLocalitySearch('localitySearchBox')" style="cursor:pointer; display: <?=trim($localitySearchText) ? "inline" : "none"?>;" class="filterClear">&times;</span>
		</div>
		<div class="clearFix"></div>
	</div>
	
	<div class="locality-list" id="localityDiv">
		<div id="filterLocalityScrollbar" class="scrollbar1">
			<div class="viewport" style = "height: 330px; width: 940px; overflow: hidden">
				<div class="overview" id="overviewLocality">
					<?php
					$i = 0;
					$j = 0;
					while($i < ceil($totalEntries/$entriesPerColumn)) {
						$j = 0; ?>
						<div class='locality-list-col'>
							<div style="width:171px">
								<div class="customInputs">
									<?php while($j + ($i * $entriesPerColumn) < $totalEntries && $j < $entriesPerColumn) { ?>
										<?php if($zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['type'] == 'zone') { ?>
											<ul class="refine-list">
												<?php while($j < $entriesPerColumn && $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['type'] == 'zone') {
													$zoneId = $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['id'];
													$zoneName = $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['name'];
													?>
													<li style="width: 170px; margin-bottom:5px !important;">
														<input type="checkbox" onclick="applyZoneCheckboxRNR('<?=$zoneId?>'); applyRnRFiltersOnCategoryPages(this);" name="zone" id="zone_<?=$zoneId?>" value="<?=$zoneId?>">
														<label for="zone_<?=$zoneId?>">
															<span class="common-sprite"></span><p><strong><?=$zoneName?></strong></p>
														</label>
													</li>
												<?php $j++; } ?>
											</ul>
										<?php } ?>
										
										<?php if($j + ($i * $entriesPerColumn) < $totalEntries && $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['type'] == 'locality') { ?>
											<div class="sub-localities">
												<ul class="refine-list">
													<?php while($j + ($i * $entriesPerColumn) < $totalEntries && $j < $entriesPerColumn && $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['type'] == 'locality') {
														$localityId = $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['id'];
														$localityName = $zoneLocalitiesArr[$j + ($i * $entriesPerColumn)]['name'];
														
														$countVal = 0;
														$checked = '';
														$disabled = 'disabled';
														if(in_array($localityId, $enabledLocalities) ) {
															$disabled = '';
															$countVal = $enabledLocalitiesWithCount[$localityId] ? $enabledLocalitiesWithCount[$localityId] : 0;
														}
														
														if(in_array($localityId, $appliedLocality)) {
															$checked = "checked";
														} ?>
														<li>
															<input type="checkbox" <?=$checked.' '.$disabled?> class="zonelocality_<?=$zoneId?>" onclick="applyRnRFiltersOnCategoryPages(this);" id="locality_<?=$localityId?>" name="locality[]" value="<?=$localityId?>">
															<label for="locality_<?=$localityId?>">
																<span class="common-sprite"></span><p><?=$localityName?> <em>(<?=$countVal?>)</em></p>
															</label>
														</li>
													<?php $j++; } ?>
												</ul>
											</div>
										<?php }
									} ?>
								</div>
							</div>
						</div>
					<?php $i++; } ?>
				</div>
			</div>
			
			<div class="scrollbar">
				<div class="track" style="height: 2px">
					<div class="thumb" style="height: 7px; top:-3px;">
						<div class="end"></div>
					</div>
				</div>
			</div>
			
		</div>
	</div>
</div>
<!-- Locality Layer : Ends -->