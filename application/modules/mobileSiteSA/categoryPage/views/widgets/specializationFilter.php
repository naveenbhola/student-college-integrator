<?php
$specializationPreSelectedFilters 	= $appliedFilters["specialization"];
?>
<article class="filter-options customInputs clearfix filterTab" id="specializationTab">
    <ul>
        <?php $key = 0;
        foreach($specializationForFilters as $index=>$specialization) {
            if(trim($specialization)) {
                    $checked = in_array($index, $specializationPreSelectedFilters)? "checked" : "";
                    $disabled = in_array($specialization, $userAppliedSpecializationForFilters) ? "" : "disabled";
            ?>
        <li>
            <input type="checkbox" tabname="specializationTab" name="course[]" id="operation-<?=$key?>" value="<?=$index?>"<?=$checked?> autocomplete="off" <?=$disabled?> data-role="none">
            <label for="operation-<?=$key?>"><span class="sprite flLt"></span><p><?=$specialization?></p>
            </label>
        </li>
        <?php }
        $key++; } ?>
        <li class="clear-link"><a href="javascript:void(0);" onclick="resetFilterofTab('specializationTab');">Clear This Filter</a></li>
    </ul>
</article>