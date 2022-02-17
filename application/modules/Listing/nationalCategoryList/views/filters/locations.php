
<li>
<a class="activeLeftNav"><?php echo $filterBucketName['location']; ?><p id="clear_loc" style="<?php echo ((count($appliedFilters['city']) > 1 && !empty($appliedFilters['city'])) || (count($appliedFilters['city']) == 1 && $appliedFilters['city'][0] != 1 && !empty($appliedFilters['city'])) || !empty($appliedFilters['locality']) || !empty($appliedFilters['state']))? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
<div class="serp-filter-search"> 
    <input type="text" onkeyup="filterSearchList(this);" id="locationSearchBox" placeholder="Search Locations" value=""/>
    <span onclick="turnOffMultiLocationFiltering('locationSearchBox');" style="cursor: pointer;display:none;" class="filterClear">×</span>
</div>
<div class="scrollbar1 searchTinyScrollbarFilter" style="width:100%;">
        <div class="scrollbar">
            <div class="track">
                <div class="thumb" style="min-height:10px !important"></div>
            </div>
        </div>
        <div class="viewport" style="height:145px;overflow:hidden;">
            <div class="overview" style="width:100%">
    			<ul class="srpsubmenu" id="locationSearchBoxContainer" data-section="location">
    				<?php foreach ($filters['location']['city'] as $value) {
    					$cityId = $value['id'];
    					$valueId = $fieldAlias['city']."_".$value['id'];
			            $checkedCityString = "";
			            if(in_array($cityId, $appliedFilters['city'])) {
			                $checkedCityString = "checked ='checked'";
			            } ?>
			            <li>
			                <a <?php echo $product == 'Category' ? 'href='.$value['url']:''; ?> class="checkboxnav">
			                    <div class="nav-checkBx">
			                        <input type="checkbox" class="nav-inputChk" <?=$checkedCityString?> data-val="<?=$valueId?>" id="<?=$valueId?>" />
			                        <label <?php echo $product != 'Category' ? "for=$valueId" : ''; ?> class="nav-heck">
			                            <i class="icons ic_checkdisable1"></i><?=$value['name']?> (<?=$value['count']?>)
			                        </label>
			                    </div>
			                </a>
			                <?php if(!empty($checkedCityString)  && !empty($filters['location']['cityWiseLocality'][$cityId])) { ?>
			                    <script>
			                        localityFilterValuesInitial[<?=$cityId?>] = <?=json_encode($filters['location']['cityWiseLocality'][$cityId])?>;
			                        appliedLocality[<?=$cityId?>] = <?=json_encode($appliedFilters['locality'])?>;
			                        localityCount[<?=$cityId?>] = <?=json_encode($filters['location']['localityCount'][$cityId])?>;
			                    </script>
			                    <div class="locality-link">
			                        <a href="javascript:void(0);" onclick="showLocalityLayerOnSearch(<?=$cityId?>);">Select Localities</a>
			                    </div>
			                <?php } ?>
			            </li>
        			<?php } ?>

        			<?php foreach ($filters['location']['state'] as $value) {
        				$valueId = $fieldAlias['state']."_".$value['id'];
				        $checkedStateString = "";
				        if(in_array($value['id'], $appliedFilters['state'])) {
				            $checkedStateString = "checked ='checked'";
				        } ?>
				        <li>
				            <a <?php echo $product == 'Category' ? 'href='.$value['url']:''; ?> class="checkboxnav">
				                <div class="nav-checkBx">
				                	<input type="checkbox" class="nav-inputChk" <?=$checkedStateString?> data-val="<?=$valueId?>" id="<?=$valueId?>">
				                    <label <?php echo $product != 'Category' ? "for=$valueId" : ''; ?> class="nav-heck">
				                        <i class="icons ic_checkdisable1"></i><?=$value['name']?> (<?=$value['count']?>)
				                    </label>
				                </div>
				            </a>
				        </li>
				    <?php } ?>
				</ul>
    		</div>
        </div>
    </div>
</li>