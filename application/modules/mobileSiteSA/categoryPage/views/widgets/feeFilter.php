<?php
$feesPreSelectedFilters = $appliedFilters["fees"];
?>
<article class="filter-options customInputs clearfix filterTab" id="feeTab">
    <ul>
        <?php
            $key = 0;
            foreach($feeRangesForFilter as $feeskey=>$fees){
                if(trim($fees)){
                    $checked = in_array($feeskey, $feesPreSelectedFilters) ? "checked" : "";
                    $disabled = in_array($fees, $userAppliedFeeRangesForFilter) ? "" : "disabled";
                }
        ?>
                <li data-enchance="false">
                    <input type="radio" onchange="manageFeeCheckbox();" name="fee[]" id="fee-<?=$key?>" data-role="none" value="<?=$feeskey?>" <?=$checked?> autocomplete="off" <?=$disabled?> >
                    <label for="fee-<?=$key?>">
                        <span class="sprite flLt"></span>
                        <p><?=str_replace("Lacs","Lakhs",$fees)?></p>
                    </label>
                </li>
        <?php
            $key++;
            }
        ?>
    </ul>
</article>
