<?php if($filters['duration']['min']!=$filters['duration']['max']){ ?>
<div class="newAcrdn active">
    <h2>Course Duration<i class="custm__ico"></i></h2>
    <div class="contentBlock">
    <div class="slidePlace">
<?php
    sort($pageData['appliedFilter']['courseDuration']);
    if($filters['duration']['min']!=$filters['duration']['max']){
        $minDuration = $pageData['appliedFilter']['courseDuration'][0];
        $maxDuration = $pageData['appliedFilter']['courseDuration'][1];
        if(is_null($minDuration))
        {
            $minDuration = $filters['duration']['min'];
        }
        if(is_null($maxDuration))
        {
            $maxDuration = $filters['duration']['max'];
        }
        // if values are beyond valid range, set as either of the limits
        if($minDuration<$filters['duration_parent']['min'] || $minDuration > $filters['duration_parent']['max'])
        {
            $minDuration = $filters['duration_parent']['min'];
        }
        if($maxDuration>$filters['duration_parent']['max'] || $maxDuration < $filters['duration_parent']['min'])
        {
            $maxDuration = $filters['duration_parent']['max'];
        }
        // if min value is invalid, round off to nearest step
        if(($mod = fmod($minDuration,$filters['duration_parent']['scale'])) !=0)
        {
            if($mod>($filters['duration_parent']['scale']/2))
            {
                $minDuration = $minDuration + ($filters['duration_parent']['scale']-$mod);
            }else{
                $minDuration = $minDuration - $mod;
            }
        }
        if(($mod = fmod($maxDuration,$filters['duration_parent']['scale'])) !=0)
        {
            if($mod>($filters['duration_parent']['scale']/2))
            {
                $maxDuration = $maxDuration + ($filters['duration_parent']['scale']-$mod);
            }else{
                $maxDuration = $maxDuration - $mod;
            }
        }
    ?>
        <div>
            <span class="leftLabel"><?php echo $minDuration." months"; ?></span>
            <span class="rightLabel"><?php echo $maxDuration." months"; ?></span>
            <input type="hidden" class="srchSliderMinLt" value="<?php echo $filters['duration_parent']['min']; ?>">
            <input type="hidden" class="srchSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $minDuration; ?>" alias="">
            <input type="hidden" class="srchSliderMaxLt" value="<?php echo $filters['duration_parent']['max']; ?>">
            <input type="hidden" class="srchSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $maxDuration; ?>" alias="">
            <input type="hidden" class="srchSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $filters['duration']['scale']; ?>">
            <input type="hidden" class="schAmountMinSlct" value="<?php echo $minDuration; ?>">
            <input type="hidden" class="schAmountMaxSlct" value="<?php echo $maxDuration; ?>">
        </div>
        <div id="durationSlider" class="srchSlider toggleFilter" fType="courseDuration" alias="crdr" ></div>
<?php } else if($filterAjaxCall == 1) { ?>
        <div class="filterNa">This filter option is not available.</div>
<?php } ?>
    </div>
    </div>
</div>
<?php } ?>