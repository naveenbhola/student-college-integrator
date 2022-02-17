<?php
$moreOptionsPreSelectedFilters 		= $appliedFilters["moreoption"];
$degreeKey = array_search('DEGREE_COURSE',$moreOptionsPreSelectedFilters);
if (count($moreOptionsPreSelectedFilters) == 1 &&
     $degreeKey !== false &&
	$categoryPageRequest->checkIfCertDiplomaCountryCatPage())
{
    unset($moreOptionsPreSelectedFilters[$degreeKey]);
}
?>
<article class="filter-options customInputs clearfix filterTab" id="moreOptionTab">
    <ul>
        <?php $key = 0;
        foreach($moreOptionsForFilters as $moreoptionKey=>$moreOptions) {
                $checked = in_array($moreoptionKey, $moreOptionsPreSelectedFilters)? "checked" : "";
                $disabled = in_array($moreOptions, $userAppliedMoreOptionsForFilters) ? "" : "disabled";
                if(trim($moreOptions)) {?>
                        <li>
                            <input type="checkbox" tabname="moreOptionTab" name="moreopt[]" id="scholarship-<?=$key?>" value="<?=$moreoptionKey?>" <?=$checked?> autocomplete="off" <?=$disabled?> data-role="none">
                            <label for="scholarship-<?=$key?>"><span class="sprite flLt"></span><p><?=$moreOptions?></p>
                            </label>
                        </li>
                <?php }
        $key++; }
        ?>
        <li class="clear-link"><a href="javascript:void(0);" onclick="resetFilterofTab('moreOptionTab');">Clear This Filter</a></li>
    </ul>
</article>