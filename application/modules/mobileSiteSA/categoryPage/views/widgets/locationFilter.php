<?php
$countryPreSelectedFilters 		= $appliedFilters["country"];
$statePreSelectedFilters 		= $appliedFilters["state"];
$cityPreSelectedFilters 		= $appliedFilters["city"];
?>
<article class="filter-options customInputs clearfix filterTab" id="locationTab">
    <ul>
    <?php if($categoryPageRequest->isAllCountryPage()) {
        $key = 0;
        foreach($countriesForFilters as $index=>$country) {
            $checked = in_array($index, $countryPreSelectedFilters) ? "checked" : "";
            $disabled = array_key_exists($index, $userAppliedCountryForFilters) ? "" : "disabled";
            if($country) { ?>
            <li>
                <input type="checkbox"  tabname="locationTab" name="countryList[]" id="country-<?=$key?>" value="<?=$index?>" <?=$disabled?> <?=$checked?> autocomplete="off" data-role="none">
                <label for="country-<?=$key?>"><span class="sprite flLt"></span><p><?=$country?></p>
                </label>
            </li>
        <?php          }
        $key++; }
        }
        else { // show enabled state list
        $key = 0;
        foreach($statesForFilters as $index=>$state) {
            $checked = in_array($index, $statePreSelectedFilters) ? "checked" : "";
            $disabled = array_key_exists($index, $userAppliedStateForFilters) ? "" : "disabled";
            if(trim($state) && array_key_exists($index, $userAppliedStateForFilters)) { ?>
            <li>
                <input type="checkbox" tabname="locationTab" name="stateList[]" id="state-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off" data-role="none">
                <label for="state-<?=$key?>"><span class="sprite flLt"></span><p><?=$state?></p>
                </label>
            </li>
            <?php }
        $key++; }
        // show enabled city list
        $key = 0;
        foreach($citiesForFilters as $index=>$city) {
        $checked = in_array($index, $cityPreSelectedFilters) ? "checked" : "";
        $disabled = array_key_exists($index, $userAppliedCitiesForFilters) ? "" : "disabled";
        if(trim($city) && array_key_exists($index, $userAppliedCitiesForFilters)) { ?>
        <li>
            <input type="checkbox" tabname="locationTab" name="cityList[]" id="city-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off" data-role="none">
            <label for="city-<?=$key?>"><span class="sprite flLt"></span><p><?=$city?></p>
            </label>
        </li>
        <?php }
        $key++; }
        
        // show disabled state list
        $key = 0;
        foreach($statesForFilters as $index=>$state) {
        $checked = in_array($index, $statePreSelectedFilters) ? "checked" : "";
        $disabled = array_key_exists($index, $userAppliedStateForFilters) ? "" : "disabled";
        if(trim($state) && !array_key_exists($index, $userAppliedStateForFilters)) { ?>                               
        <li>
            <input type="checkbox" tabname="locationTab" name="stateList[]" id="state-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off" data-role="none">
            <label for="state-<?=$key?>"><span class="sprite flLt"></span><p><?=$state?></p>
            </label>
        </li>
        <?php }
        $key++; }
        
        // show disabled city list
        $key = 0;
        foreach($citiesForFilters as $index=>$city) {
        $checked = in_array($index, $cityPreSelectedFilters) ? "checked" : "";
        $disabled = array_key_exists($index, $userAppliedCitiesForFilters) ? "" : "disabled";
        if(trim($city) && !array_key_exists($index, $userAppliedCitiesForFilters)) { ?>
        <li>
            <input type="checkbox" tabname="locationTab" name="cityList[]" id="city-<?=$key?>" value="<?=$index?>" <?=$checked?> <?=$disabled?> autocomplete="off" data-role="none">
            <label for="city-<?=$key?>"><span class="sprite flLt"></span><p><?=$city?></p>
            </label>
        </li>
            <?php }
        $key++; }
        } ?>    
        <li class="clear-link"><a href="javascript:void(0);" onclick="resetFilterofTab('locationTab');">Clear This Filter</a></li>
    </ul>
</article>