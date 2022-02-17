<?php if($filters['fees']['min']!=$filters['fees']['max']){ //now we are skipping the unavailable filter altogether ?>
<div class="newAcrdn active">
    <h2>Fees & Expenses <i class="custm__ico"></i></h2>
    <div class="contentBlock">
    <div class="slidePlace">
<?php
    sort($pageData['appliedFilter']['courseFee']);
    if($filters['fees']['min']!=$filters['fees']['max']){
        $minFees = $pageData['appliedFilter']['courseFee'][0];
        $maxFees = $pageData['appliedFilter']['courseFee'][1];
        if(is_null($minFees))
        {
            $minFees = $filters['fees']['min'];
        }
        if(is_null($maxFees))
        {
            $maxFees = $filters['fees']['max'];
        }
        // if values are beyond valid range, set as either of the limits
        if($minFees<$filters['fees_parent']['min'] || $minFees > $filters['fees_parent']['max'])
        {
            $minFees = $filters['fees_parent']['min'];
        }
        if($maxFees>$filters['fees_parent']['max'] || $maxFees < $filters['fees_parent']['min'])
        {
            $maxFees = $filters['fees_parent']['max'];
        }
        // if min value is invalid, round off to nearest step
        if(($mod = fmod($minFees,$filters['fees_parent']['scale'])) !=0)
        {
            if($mod>($filters['fees_parent']['scale']/2))
            {
                $minFees = $minFees + ($filters['fees_parent']['scale']-$mod);
            }else{
                $minFees = $minFees - $mod;
            }
        }
        if(($mod = fmod($maxFees,$filters['fees_parent']['scale'])) !=0)
        {
            if($mod>($filters['fees_parent']['scale']/2))
            {
                $maxFees = $maxFees + ($filters['fees_parent']['scale']-$mod);
            }else{
                $maxFees = $maxFees - $mod;
            }
        }
    ?>
        <div>
            <span class="leftLabel"><?php echo "Rs. ".$minFees." L"; ?></span>
            <span class="rightLabel"><?php echo "Rs. ".$maxFees." L".($maxFees==50?"+":""); ?></span>
            <input type="hidden" class="srchSliderMinLt" value="<?php echo $filters['fees_parent']['min']; ?>">
            <input type="hidden" class="srchSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $minFees; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schAmountMin'];?>">
            <input type="hidden" class="srchSliderMaxLt" value="<?php echo $filters['fees_parent']['max']; ?>">
            <input type="hidden" class="srchSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $maxFees; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schAmountMax'];?>">
            <input type="hidden" class="srchSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $filters['fees']['scale']; ?>">
            <input type="hidden" class="schAmountMinSlct" value="<?php echo $minFees; ?>">
            <input type="hidden" class="schAmountMaxSlct" value="<?php echo $maxFees; ?>">
        </div>
        <div id="feesSlider" class="srchSlider toggleFilter" fType="courseFees" alias="cf" ></div>
<?php } else if($filterAjaxCall == 1) { ?>
        <div class="amountNA filterNa">This filter option is not available.</div>
<?php } ?>
    </div>
    </div>
</div>
<?php } ?>