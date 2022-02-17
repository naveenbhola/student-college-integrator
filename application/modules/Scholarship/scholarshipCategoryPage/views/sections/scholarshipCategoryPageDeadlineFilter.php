<?php global $queryStringFieldNameToAliasMapping;
   $deadlineFilter = $scholarshipData['filterData']['saScholarshipApplicationEndDate'];
   $appliedDeadline = $scholarshipData['appliedFilterData']['saScholarshipApplicationEndDate'];
   $data = array();
   if(!is_null($appliedDeadline)){
      if(!is_null($appliedDeadline['min']) && ($appliedDeadline['min']+$diffInMonths0) >=$deadlineFilter['min'])
      {
           $deadlineFilterMin = $appliedDeadline['min'] ;
           $deadlineFilter['minLabel'] = $appliedDeadline['minLabel'] ;
      }else{
         $deadlineFilterMin = $deadlineFilter['min'];
      }

      if(!is_null($appliedDeadline['max'] ) && $appliedDeadline['max'] <=$deadlineFilter['max'])
      {
         $deadlineFilterMax = $appliedDeadline['max'] ;
         $deadlineFilter['maxLabel'] = $appliedDeadline['maxLabel'] ;
      }else{
         $deadlineFilterMax = $deadlineFilter['max'];
      }
   }else{
         $deadlineFilterMin = $deadlineFilter['min'] ;
         $deadlineFilterMax = $deadlineFilter['max'] ;
   }
?>
<li class = "sliderSec">
  <input type="checkbox" class="main__check">
  <i class="custm__ico"></i>
  <h3 class="main__titl f12-clr3 fnt-sbold">deadline</h3>
  <div class="">
     <ul class="sub__menu">
           <li>
            <?php if($deadlineFilter["min"]!=$deadlineFilter["max"]){ ?>
            <div>
              <span class="leftLabel"><?php echo $deadlineFilter["minLabel"]; ?></span>
              <span class="rightLabel"><?php echo $deadlineFilter["maxLabel"]; ?></span>
              <input type="hidden" class="schSliderMinLt" value="<?php echo $deadlineFilter["min"]; ?>">
              <input type="hidden" class="schSliderMin" name="schDeadlineMin" id="schDeadlineMin" value="<?php echo $deadlineFilterMin; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schDeadlineMin'];?>">
              <input type="hidden" class="schSliderMaxLt" value="<?php echo $deadlineFilter["max"]; ?>">
              <input type="hidden" class="schSliderMax" name="schDeadlineMax" id="schDeadlineMax" value="<?php echo $deadlineFilterMax; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schDeadlineMax'];?>">
              <input type="hidden" class="schSliderInterval" name="schDeadlineInterval" id="schDeadlineInterval" value="<?php echo $deadlineFilter["interval"]; ?>">
              <input type="hidden" id="schSliderBaseYear" value="<?php echo $deadlineFilter["baseYear"]; ?>">
            </div>
            <div id="deadlineSlider" class="schSlider" alias="<?php echo $queryStringFieldNameToAliasMapping['schDeadlineMin'];?>"></div>
            <?php } else if($request->isAjaxCall() || $request->isFilterAjaxCall()){?>
            <div class="deadlineNA filterNA">This filter option is not available.</div>
            <?php } ?>
           </li>
     </ul>
  </div>
</li>