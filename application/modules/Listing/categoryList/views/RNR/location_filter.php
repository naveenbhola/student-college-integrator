<?php
// get the global values
global $filters;
global $appliedFilters;

// fetch Parent and current location filter values from locality, City and State filter object(s)
$cityWiseLocalityFilterValues = $filters['locality']->getCityWiseFilteredValues();
$cityFilterValues 		= $filters['city']->getFilteredValues();
$stateFilterValues 		= $filters['state']->getFilteredValues();
$ApplicableStates 		= array_keys($solrFacetValues['course_state_id_current']);
$ApplicableCities 		= array_keys($solrFacetValues['course_city_id_current']);
$ApplicableVirtualCities 	= array_keys($solrFacetValues['course_virtual_city_id_current']);

$locationSearchBoxText		= $_REQUEST['locationSearchBox'];
?>
<!-- Location filter Starts-->
		<div class="filter-blocks clear" id="locationFilterSection" style="position: relative;">
				<div class="mb10">
						<h3 class="filter-title flLt" style="margin-bottom:0;">
						<i class="cate-new-sprite loc-icon"></i>Location</h3>
						<a class="flRt" href="javascript:void(0);" onclick="resetCategoryFilter('locationFilterSection');"><i class="cate-new-sprite reset-icon"></i>Reset</a>
						<div class="clearfix"></div>
				</div>
			<div class="cate-filter-search">
				<i class="common-sprite cate-search-icon"></i>
					<input type="text" maxlength="22" onkeyup="filterList(this);" id="locationSearchBox" value="<?=$locationSearchBoxText?>"/>
					<span onclick="turnOffMultiLocationFiltering('locationSearchBox');" style="cursor:pointer;display: <?=trim($locationSearchBoxText) ? "inline" : "none"?>;" class="filterClear">&times;</span>
			</div>
			<div class="customInputs">
				<div class="scrollbar1" id="locationFilterScrollbar">
					<div class="scrollbar">
							<div class="track">
									<div class="thumb"></div>
							</div>
					</div>
					<div class="viewport" style="height:145px;overflow:hidden;">
						<div class="overview">
							<ul class="refine-list" id="locationSearchBoxContainer">
					<?php
						// show city filters
						foreach($cityFilterValues as $key=>$city)
						{
						    // initialize
						    $checked = '';
						    $disabled = 'disabled';
						    $countVal = 0;
						    // determine disabled state and count to be shown
						    if(in_array($key, $ApplicableCities) )
						    {
								$disabled = '';
								$countVal = $solrFacetValues['course_city_id_current'][$key] ? $solrFacetValues['course_city_id_current'][$key] : 0;
						    }
						    else if( in_array($key, $ApplicableVirtualCities))
						    {
								$disabled = '';
								$countVal = $solrFacetValues['course_virtual_city_id_current'][$key] ? $solrFacetValues['course_virtual_city_id_current'][$key] : 0;
						    }
						    // determine checked state if the checkbox
						    if(in_array($key,$appliedFilters['city'])){
								$checked = "checked";
						    }
						    else if($key == $request->getCityId() && !$request->isStatePage() && (empty($appliedFilters['city']) && !is_array($appliedFilters['city'])) && empty($appliedFilters['state']))
						    {
								$checked = "checked";
						    }
						?>
							<li <?php echo $locationSearchBoxText ? (stripos($city, $locationSearchBoxText) === 0 ? "" : "style='display:none'") : ""?>>
								<input type="checkbox" id="city_<?=$city?>" title="<?=$city?>" <?=$checked.' '.$disabled?> name="city[]" value="<?=$key?>" onclick="updateMultiLocationCookie(this,'city'); applyRnRFiltersOnCategoryPages(this); return false;" autocomplete="off">
								<label for="city_<?=$city?>">
									<span class="common-sprite"></span><p><?=$city?> <em>(<?=$countVal?>)</em>
									<?php if(!empty($cityWiseLocalityFilterValues[$key]) && $checked=='checked' && $disabled != "disabled") {
										$zoneIds = array_keys($cityWiseLocalityFilterValues[$key]);
										$localityIds = array();
										foreach($cityWiseLocalityFilterValues[$key] as $zoneId=>$zoneInfo) {
											$localityIds = array_merge($localityIds,array_keys($zoneInfo['localities']));
										} ?>
										<script>
											localityFilterValues[<?=$key?>] = <?=json_encode($cityWiseLocalityFilterValues[$key])?>;
											appliedLocality[<?=$key?>] = <?=json_encode($appliedFilters['locality'])?>;
											enabledLocalitiesWithCount[<?=$key?>] = <?=json_encode($solrFacetValues['course_locality_id_current'])?>;
											zoneIds[<?=$key?>] = <?=json_encode($zoneIds)?>;
											localityIds[<?=$key?>] = <?=json_encode($localityIds)?>;
										</script>
										<br /><a href="javascript:void(0);" id="localityLink<?=$key?>" onclick="showLocalityLayer(<?=$key?>); return false;" style="font-size:12px; font-weight: normal;">Select Localities</a></p>
									<?php } ?>
								</label>
							</li>
					<?php
						}
						
						// show state filters
						foreach($stateFilterValues as $key=>$state)
						{
						           // do not show the state with same name as a city eg. Delhi, chandigarh
						           if(in_array($state, $cityFilterValues))
								    continue;

							    $checked = '';
							    $disabled = 'disabled';
							    $countVal = 0;
							    // determine the disabled state and count to be shown
							    if(in_array($key, $ApplicableStates))
							    {
									$disabled  = '';
									$countVal = $solrFacetValues['course_state_id_current'][$key] ? $solrFacetValues['course_state_id_current'][$key] : 0;
							    }
							    // determine checked state of the checkbox
							    if(in_array($key,$appliedFilters['state'])){
									$checked = "checked";
							    }
							    else if($key == $request->getStateId() && $request->isStatePage() && empty($appliedFilters['city']) && empty($appliedFilters['state']) && !is_array($appliedFilters['state']))
							    {
									$checked = "checked";
							    }
							?>
								<li <?php echo $locationSearchBoxText ? (stripos($state, $locationSearchBoxText) === 0 ? "" : "style='display:none'") : ""?>>
										<input type="checkbox" id="state_<?=$state?>" title="<?=$state?>" <?=$checked.' '.$disabled ?> name="state[]" value="<?=$key?>" onclick="updateMultiLocationCookie(this,'state');applyRnRFiltersOnCategoryPages();" autocomplete="off">
										<label for="state_<?=$state?>">
												<span class="common-sprite"></span><p><?=$state?> <em>(<?=$countVal?>)</em></p>
										</label>
								</li>
					<?php
						}
					?>
							</ul>
						</div>
					</div>
				</div>
			</div>
			<div id="localityLayerContDiv"></div>
		</div>
<!-- Location filter Ends-->