<?php if($show12thCutoff === true && !empty($filters['12thCutoff_parent']) && $filters['12thCutoff']['min']!=$filters['12thCutoff']['max']){ ?>
<div class="newAcrdn active">
    <h2>12th Marks <i class="custm__ico"></i></h2>
    <div class="contentBlock">
    <div class="slidePlace">
<?php
    sort($pageData['appliedFilter']['class12Marks']);
    if($filters['12thCutoff']['min']!=$filters['12thCutoff']['max']){
        $minCutoff = $pageData['appliedFilter']['class12Marks'][0];
        $maxCutoff = $pageData['appliedFilter']['class12Marks'][1];
        if(is_null($minCutoff))
        {
            $minCutoff = $filters['12thCutoff']['min'];
        }
        if(is_null($maxCutoff))
        {
            $maxCutoff = $filters['12thCutoff']['max'];
        }
        // if values are beyond valid range, set as either of the limits
        if($minCutoff<$filters['12thCutoff_parent']['min'] || $minCutoff > $filters['12thCutoff_parent']['max'])
        {
            $minCutoff = $filters['12thCutoff_parent']['min'];
        }
        if($maxCutoff>$filters['12thCutoff_parent']['max'] || $maxCutoff < $filters['12thCutoff_parent']['min'])
        {
            $maxCutoff = $filters['12thCutoff_parent']['max'];
        }
        // if min value is invalid, round off to nearest step
        if(($mod = fmod($minCutoff,$filters['12thCutoff_parent']['scale'])) !=0)
        {
            if($mod>($filters['12thCutoff_parent']['scale']/2))
            {
                $minCutoff = $minCutoff + ($filters['12thCutoff_parent']['scale']-$mod);
            }else{
                $minCutoff = $minCutoff - $mod;
            }
        }
        if(($mod = fmod($maxCutoff,$filters['12thCutoff_parent']['scale'])) !=0)
        {
            if($mod>($filters['12thCutoff_parent']['scale']/2))
            {
                $maxCutoff = $maxCutoff + ($filters['12thCutoff_parent']['scale']-$mod);
            }else{
                $maxCutoff = $maxCutoff - $mod;
            }
        }
    ?>
        <div>
            <span class="leftLabel"><?php echo $minCutoff."%"; ?></span>
            <span class="rightLabel"><?php echo $maxCutoff."%".($filters['12thCutoff']['showPlus']==1?"+":""); ?></span>
            <input type="hidden" class="srchSliderMinLt" value="<?php echo $filters['12thCutoff_parent']['min']; ?>">
            <input type="hidden" class="srchSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $minCutoff; ?>" alias="">
            <input type="hidden" class="srchSliderMaxLt" value="<?php echo $filters['12thCutoff_parent']['max']; ?>">
            <input type="hidden" class="srchSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $maxCutoff; ?>" alias="">
            <input type="hidden" class="srchSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $filters['12thCutoff']['scale']; ?>">
            <input type="hidden" class="schAmountMinSlct" value="<?php echo $minCutoff; ?>">
            <input type="hidden" class="schAmountMaxSlct" value="<?php echo $maxCutoff; ?>">
        </div>
        <div id="12thCutoffSlider" class="srchSlider toggleFilter" fType="12thMarks" alias="c12" ></div>
<?php } else if($filterAjaxCall == 1) { ?>
        <div class="filterNa">This filter option is not available.</div>
<?php } ?>
    </div>
    </div>
</div>
<?php } ?>