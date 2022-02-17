<?php
   $awardFilter = $scholarshipData['filterData']['saScholarshipAwardsCount'];
   global $queryStringFieldNameToAliasMapping;
   $appliedAwards = $scholarshipData['appliedFilterData']['saScholarshipAwardsCount'];
   if(!is_null($appliedAwards['min']) && $appliedAwards['min']>=$awardFilter['min'])
    {
         $awardFilterMin = $appliedAwards['min'];
         $awardFilter['minLabel'] = $appliedAwards['minLabel'];
    }else{
         $awardFilterMin = $awardFilter['min'];
    }

    if(!is_null($appliedAwards['max']) && $appliedAwards['max']<=$awardFilter['max'])
    {
         $awardFilterMax = $appliedAwards['max'];
         $awardFilter['maxLabel'] = $appliedAwards['maxLabel'];
    }else{
         $awardFilterMax = $awardFilter['max'];
    }
?>
<li class = "sliderSec">
   <input type="checkbox" class="main__check">
   <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3 fnt-sbold">Number of students to be awarded</h3>
   <div>
      <ul class="sub__menu">
         <li>
            <?php if($awardFilter["min"]!=$awardFilter["max"]){ ?>
            <div>
               <span class="leftLabel"><?php echo $awardFilter["minLabel"]; ?></span>
               <span class="rightLabel"><?php echo $awardFilter["maxLabel"]; ?></span>
               <input type="hidden" class="schSliderMinLt" value="<?php echo $awardFilter["min"]; ?>">
               <input type="hidden" class="schSliderMin" name="schAwardMin" id="schAwardMin" value="<?php echo $awardFilterMin; ?>"  alias="<?php echo $queryStringFieldNameToAliasMapping['schAwardMin'];?>">
               <input type="hidden" class="schSliderMaxLt" value="<?php echo $awardFilter["max"]; ?>">
               <input type="hidden" class="schSliderMax" name="schAwardMax" id="schAwardMax" value="<?php echo $awardFilterMax; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schAwardMax'];?>">
               <input type="hidden" class="schSliderInterval" name="schAwardInterval" id="schAwardInterval" value="<?php echo $awardFilter["interval"]; ?>">
            </div>
           <div id="awardSlider" class="schSlider" alias="<?php echo $queryStringFieldNameToAliasMapping['schAwardMin'];?>"></div>
            <?php } else if($request->isAjaxCall() || $request->isFilterAjaxCall()){?>
                <div class="awardNA filterNA" >This filter option is not available.</div>
            <?php }?>
         </li>
      </ul>
   </div>
</li>