<!-- Locality Layer : Starts -->
<?php
    $totalEntries = count($localityFilterValues);
    $entriesPerColumn = 10;
?>

<div class="locality-srch-layer" city='<?=$cityId?>' >
    <div class="locality-srch-head">
        <a href="javascript:void(0);" onclick="hideSearchLocalityLayer();" class="locality-sprite close"></a>
        <div class="cate-filter-search flLt">
    		<i class="locality-sprite cate-search-icon"></i>
    		<input type="text" placeholder="search" maxlength="28" onkeyup="searchInLocalities('<?=$cityId?>', false);" class="localitySearchBox" value="<?=$localitySearchText?>">
    	    <span onclick="searchInLocalities('<?=$cityId?>', true);" style="cursor:pointer; display:none;" class="filterClear">x</span>
        </div>
        <div class="clr"></div> 
    </div>
    <!--head close-->

    <div class="locality-srch-list">
        <div>
            <!-- Scrollbar starts -->
            <div class="scrollbar1">
                <div class="viewport" style = "height: 330px; width: 700px; overflow: hidden">
                    <div class="overview">
                        <?php
                        $i = 0;
                        $j = 0;
                        while($i < ceil($totalEntries/$entriesPerColumn)) {
                            $j = 0; ?>
                            <!-- Column start -->
                            <div class="locality-srch-list-col">
                                <div class="main-inpts">
                                    <?php while($j + ($i * $entriesPerColumn) < $totalEntries && $j < $entriesPerColumn) { ?>
                                        <?php if($localityFilterValues[$j + ($i * $entriesPerColumn)]['type'] == 'zone') { ?>
                                            <ul class="locality-list-head">
                                                <?php while($j < $entriesPerColumn && $localityFilterValues[$j + ($i * $entriesPerColumn)]['type'] == 'zone') {
                                                    $zoneId = $localityFilterValues[$j + ($i * $entriesPerColumn)]['id'];
                                                    $zoneName = $localityFilterValues[$j + ($i * $entriesPerColumn)]['name']; ?>
                                                    <li>
                                                        <input type="checkbox" onclick="applyZoneCheckBoxOnSearch(<?=$zoneId?>, <?=$cityId?>);" class="nav-inputChk zones" id="zone_<?=$zoneId?>">
                                                            <label for="zone_<?=$zoneId?>" class="nav-heck">
                                                                <i class="icons ic_checkdisable1"></i>
                                                                <strong><?=$zoneName?></strong>
                                                            </label>
                                                    </li>
                                                <?php $j++; } ?>
                                            </ul>
                                        <?php } ?>

                                        <?php if($j + ($i * $entriesPerColumn) < $totalEntries && $localityFilterValues[$j + ($i * $entriesPerColumn)]['type'] == 'locality') { ?>
                                            <div class="sub-localities">
                                                <ul class="locality-list-head" data-section="locality">
                                                    <?php while($j + ($i * $entriesPerColumn) < $totalEntries && $j < $entriesPerColumn && $localityFilterValues[$j + ($i * $entriesPerColumn)]['type'] == 'locality') {
                                                        $localityId = $localityFilterValues[$j + ($i * $entriesPerColumn)]['id'];
                                                        $localityName = $localityFilterValues[$j + ($i * $entriesPerColumn)]['name'];
                                                        $currentLocalityCount = $localityCount[$localityId];
                                                        $checked = '';
                                                        if(in_array($localityId, $appliedLocality)) {
                                                            $checked = "checked='checked'";
                                                        } ?>
                                                        <li>
                                                            <input type="checkbox" class="nav-inputChk localities zonelocality_<?=$zoneId?>" <?=$checked?> zone="<?=$zoneId?>" id="loc_<?=$localityId?>" data-val="lo_<?=$localityId?>">
                                                                <label for="loc_<?=$localityId?>" class="nav-heck">
                                                                    <i class="icons ic_checkdisable1"></i>
                                                                    <p><?=$localityName?> (<?=$currentLocalityCount?>)</p>
                                                                </label>
                                                        </li>
                                                    <?php $j++; } ?>
                                                </ul>
                                            </div>
                                        <?php }
                                    } ?>
                                </div>
                            </div>
                            <!-- Column end -->
                        <?php $i++; } ?>
                        <input type="checkbox" style="display:none" class="localities dummy_locality">
                    </div>
                </div>
                
                <div class="scrollbar">
                    <div class="track" style="height: 2px">
                        <div class="thumb" style="height: 9px; top:-3px;">
                            <div class="end"></div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Scrollbar ends -->
        </div>
    </div>
    <script>
        zoneIds[<?=$cityId?>] = <?=json_encode($zoneIds)?>;
        //localityIds[<?=$cityId?>]   = <?=json_encode($data['locality'][$cityId])?>;
    </script>
</div>
<!-- Locality Layer : Ends -->