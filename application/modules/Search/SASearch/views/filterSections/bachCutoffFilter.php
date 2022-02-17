<?php if($showBachCutoff === true && !empty($filters['UGCutoff_parent']) && $filters['UGCutoff']['min']!=$filters['UGCutoff']['max']){ ?>
<div class="newAcrdn active">
    <h2>Bachelors Marks <i class="custm__ico"></i></h2>
    <div class="contentBlock">
    <div class="slidePlace">
<?php
    sort($pageData['appliedFilter']['ugMarks']);
    if($filters['UGCutoff']['min']!=$filters['UGCutoff']['max']){
        $minCutoff = $pageData['appliedFilter']['ugMarks'][0];
        $maxCutoff = $pageData['appliedFilter']['ugMarks'][1];
        if(is_null($minCutoff))
        {
            $minCutoff = $filters['UGCutoff']['min'];
        }
        if(is_null($maxCutoff))
        {
            $maxCutoff = $filters['UGCutoff']['max'];
        }
        // if values are beyond valid range, set as either of the limits
        if($minCutoff<$filters['UGCutoff_parent']['min'] || $minCutoff > $filters['UGCutoff_parent']['max'])
        {
            $minCutoff = $filters['UGCutoff_parent']['min'];
        }
        if($maxCutoff>$filters['UGCutoff_parent']['max'] || $maxCutoff < $filters['UGCutoff_parent']['min'])
        {
            $maxCutoff = $filters['UGCutoff_parent']['max'];
        }
        // if min value is invalid, round off to nearest step
        if(($mod = fmod($minCutoff,$filters['UGCutoff_parent']['scale'])) !=0)
        {
            if($mod>($filters['UGCutoff_parent']['scale']/2))
            {
                $minCutoff = $minCutoff + ($filters['UGCutoff_parent']['scale']-$mod);
            }else{
                $minCutoff = $minCutoff - $mod;
            }
        }
        if(($mod = fmod($maxCutoff,$filters['UGCutoff_parent']['scale'])) !=0)
        {
            if($mod>($filters['UGCutoff_parent']['scale']/2))
            {
                $maxCutoff = $maxCutoff + ($filters['UGCutoff_parent']['scale']-$mod);
            }else{
                $maxCutoff = $maxCutoff - $mod;
            }
        }
     ?>
        <div>
            <span class="leftLabel"><?php echo $minCutoff."%"; ?></span>
            <span class="rightLabel"><?php echo $maxCutoff."%".($filters['UGCutoff']['showPlus']==1?"+":""); ?></span>
            <input type="hidden" class="srchSliderMinLt" value="<?php echo $filters['UGCutoff_parent']['min']; ?>">
            <input type="hidden" class="srchSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $minCutoff; ?>" alias="">
            <input type="hidden" class="srchSliderMaxLt" value="<?php echo $filters['UGCutoff_parent']['max']; ?>">
            <input type="hidden" class="srchSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $maxCutoff; ?>" alias="">
            <input type="hidden" class="srchSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $filters['UGCutoff']['scale']; ?>">
            <input type="hidden" class="schAmountMinSlct" value="<?php echo $minCutoff; ?>">
            <input type="hidden" class="schAmountMaxSlct" value="<?php echo $maxCutoff; ?>">
        </div>
        <div id="bachCutoffSlider" class="srchSlider toggleFilter" fType="bachelorMarks" alias="ug" ></div>
<?php } else if($filterAjaxCall == 1) { ?>
        <div class="filterNa">This filter option is not available.</div>
<?php } ?>
    </div>
    </div>
</div>
<?php } ?>