<script>
    var localityFilterValuesInitial = new Array();
    var appliedLocality = new Array();
    var localityCount = new Array();
</script>
<li><a class="activeLeftNav">Location<p id="clearloc" style="<?php echo (count($appliedFilters['city']) > 0 || count($appliedFilters['locality']) > 0 || count($appliedFilters['state']) > 0)? '':'display:none'; ?>" class="clearFilter"><span>Clear</span></p><i class="icons ic_down"></i></a>
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
        <div class="viewport" style="height:140px;overflow:hidden;">
            <div class="overview" style="width:100%">
    <ul class="srpsubmenu" id="locationSearchBoxContainer" data-section="locations">
    <?php foreach ($data['city'] as $key => $city) {
        $cityId = $city['id'];
        $checkedCityString = "";
        if(in_array($city['id'], $appliedFilters['city'])){
            $checkedCityString = "checked ='chechked'";
        } ?>
        <li>
            <a class="checkboxnav">
                <div class="nav-checkBx">
                    <input type="checkbox" class="nav-inputChk" <?=$checkedCityString?> data-val="<?=$key?>" id="<?=$key?>">
                    <label for="<?=$key?>" class="nav-heck">
                        <i class="icons ic_checkdisable1"></i><?=$city['name']?> (<?=$city['count']?>)
                    </label>
                </div>
            </a>
            <?php if(!empty($checkedCityString) && !empty($data['locality']['cityWiseLocality'][$cityId])) { ?>
                <script>
                    localityFilterValuesInitial[<?=$cityId?>] = <?=json_encode($data['locality']['cityWiseLocality'][$cityId])?>;
                    appliedLocality[<?=$cityId?>] = <?=json_encode($appliedFilters['locality'])?>;
                    localityCount[<?=$cityId?>] = <?=json_encode($data['locality']['localityCount'][$cityId])?>;
                    //zoneIds[<?=$cityId?>] = <?=json_encode($data['locality'][$cityId]['zoneIds'])?>;
                    //localityIds[<?=$cityId?>] = <?=json_encode($data['locality'][$cityId])?>;
                </script>
                <div class="locality-link">
                    <a href="javascript:void(0);" onclick="showLocalityLayerOnSearch(<?=$cityId?>);">Select Localities</a>
                </div>
            <?php } ?>
        </li>
    <?php }?>

       <?php foreach ($data['state'] as $stateId => $state) {
        $checkedStateString = "";
        if(in_array($state['id'], $appliedFilters['state'])){
            $checkedStateString = "checked ='chechked'";
        }

        ?>
        <li>
            <a class="checkboxnav">
                <div class="nav-checkBx">
                    <input type="checkbox" class="nav-inputChk" <?=$checkedStateString?> data-val="<?=$stateId?>" id="<?=$stateId?>">
                    <label for="<?=$stateId?>" class="nav-heck">
                        <i class="icons ic_checkdisable1"></i><?=$state['name']?> (<?=$state['count']?>)
                    </label>
                </div>
            </a>
        </li>
    <?php }?>


        
    </ul>
    </div>
        </div>
    </div>
</li>