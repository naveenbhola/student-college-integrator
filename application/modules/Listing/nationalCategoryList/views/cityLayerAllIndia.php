

<div class="locality-srch-layer allind-lyr" city='<?=$cityId?>'>
    <div class="locat_hed">
      <div class="locat_tl"><p class="locat_titl">Select your location</p></div>
      <div class="locat_clos"><a href="javascript:void(0);" onclick="hideAllIndiaCityLayer();" >&times;</a></div>
   </div>
    <div class="locality-srch-head">
    <p class="loct-txt">To see relevant colleges, select preferred location(s):</p>
        <div class="cate-filter-search flLt">
    		<i class="locality-sprite cate-search-icon"></i>
    		<input type="text" placeholder="Search Cities" onkeyup="filterList(this, true);" class="localitySearchBox" id='citySearchBox'  value="<?=$localitySearchBoxhText?>">
    	    
            <span onclick="filterList(this, false);" style="cursor:pointer; display:none;" class="filterClear">x</span>
        </div>
        <div class="clr"></div> 
    </div>
                <div class="loc-hgt">
                    <ul class="srpsubmenu all-chk" data-section="location" id='allIndiaCityData'>
                        <?php foreach ($filters['location']['city'] as $value) {
                            $cityId = $value['id'];
                            $valueId = $fieldAlias['city']."_".$value['id'];
                            $checkedCityString = "";
                            if(in_array($cityId, $appliedFilters['city'])) {
                                $checkedCityString = "checked ='checked'";
                            } ?>
                            <li>
                                <a class="checkboxnav">
                                    <div class="nav-checkBx">
                                        <input type="checkbox" class="nav-inputChk" <?=$checkedCityString?> data-val="<?=$valueId?>"  />
                                        <label class="nav-heck slt_city">
                                            <i class="icons ic_checkdisable1"></i><?=$value['name']?> 
                                        </label>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>

                        <?php foreach ($filters['location']['state'] as $value) {
                            $valueId = $fieldAlias['state']."_".$value['id'];
                            $checkedStateString = "";
                            if(in_array($value['id'], $appliedFilters['state'])) {
                                $checkedStateString = "checked ='checked'";
                            } ?>
                            <li>
                                <a class="checkboxnav">
                                    <div class="nav-checkBx">
                                        <input type="checkbox" class="nav-inputChk" <?=$checkedStateString?> data-val="<?=$valueId?>" >
                                        <label class="nav-heck slt_city">
                                            <i class="icons ic_checkdisable1"></i><?=$value['name']?> 
                                        </label>
                                    </div>
                                </a>
                            </li>
                        <?php } ?>
                    </ul>
</div>
<a href="javascript:void(0)" class="lyr-dn all_india_submit_button">Done</a>
</div>
<div class='all-Bglyr'></div>

<!-- Locality Layer : Ends -->
