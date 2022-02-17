<?php if($showWorkXP === true && $filters['WorkExperience']['min']!=$filters['WorkExperience']['max']){ ?>
<div class="newAcrdn active">
    <h2>Work experience <i class="custm__ico"></i></h2>
    <div class="contentBlock">
    <div class="slidePlace">
<?php
    sort($pageData['appliedFilter']['workExperience']);
    if($filters['WorkExperience']['min']!=$filters['WorkExperience']['max']){
        $minXP = $pageData['appliedFilter']['workExperience'][0];
        $maxXP = $pageData['appliedFilter']['workExperience'][1];
        if(is_null($minXP))
        {
            $minXP = $filters['WorkExperience']['min'];
        }
        if(is_null($maxXP))
        {
            $maxXP = $filters['WorkExperience']['max'];
        }
        // if values are beyond valid range, set as either of the limits
        if($minXP<$filters['WorkExperience_parent']['min'] || $minXP > $filters['WorkExperience_parent']['max'])
        {
            $minXP = $filters['WorkExperience_parent']['min'];
        }
        if($maxXP>$filters['WorkExperience_parent']['max'] || $maxXP < $filters['WorkExperience_parent']['min'])
        {
            $maxXP = $filters['WorkExperience_parent']['max'];
        }
        // if min value is invalid, round off to nearest step
        if(($mod = fmod($minXP,$filters['WorkExperience_parent']['scale'])) !=0)
        {
            if($mod>($filters['WorkExperience_parent']['scale']/2))
            {
                $minXP = $minXP + ($filters['WorkExperience_parent']['scale']-$mod);
            }else{
                $minXP = $minXP - $mod;
            }
        }
        if(($mod = fmod($maxXP,$filters['WorkExperience_parent']['scale'])) !=0)
        {
            if($mod>($filters['WorkExperience_parent']['scale']/2))
            {
                $maxXP = $maxXP + ($filters['WorkExperience_parent']['scale']-$mod);
            }else{
                $maxXP = $maxXP - $mod;
            }
        }
    ?>
        <div>
            <span class="leftLabel"><?php echo $minXP." year(s)"; ?></span>
            <span class="rightLabel"><?php echo $maxXP." year(s)".($filters['WorkExperience']['showPlus']==1?"+":""); ?></span>
            <input type="hidden" class="srchSliderMinLt" value="<?php echo $filters['WorkExperience_parent']['min']; ?>">
            <input type="hidden" class="srchSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $minXP; ?>" alias="">
            <input type="hidden" class="srchSliderMaxLt" value="<?php echo $filters['WorkExperience_parent']['max']; ?>">
            <input type="hidden" class="srchSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $maxXP; ?>" alias="">
            <input type="hidden" class="srchSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $filters['WorkExperience']['scale']; ?>">
            <input type="hidden" class="schAmountMinSlct" value="<?php echo $minXP; ?>">
            <input type="hidden" class="schAmountMaxSlct" value="<?php echo $maxXP; ?>">
        </div>
        <div id="workXPSlider" class="srchSlider toggleFilter" fType="workExp" alias="we" ></div>
<?php } else if($filterAjaxCall == 1) { ?>
        <div class="filterNa">This filter option is not available.</div>
<?php } ?>
    </div>
    </div>
</div>
<?php } ?>