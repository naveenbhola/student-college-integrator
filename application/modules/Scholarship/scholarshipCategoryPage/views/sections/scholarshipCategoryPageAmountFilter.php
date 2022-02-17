<?php
    global $queryStringFieldNameToAliasMapping;
    $amountFilter = $scholarshipData['filterData']['saScholarshipAmount'];
    $appliedAmount = $scholarshipData['appliedFilterData']['saScholarshipAmount'];
    if(!is_null($appliedAmount['min']) && $appliedAmount['min']>=$amountFilter['min'])
    {
        $amountFilterMin = $appliedAmount['min'];
        $amountFilter['minLabel'] = $appliedAmount['minLabel'];
    }else{
        $amountFilterMin = $amountFilter['min'];
    }

    if(!is_null($appliedAmount['max']) && $appliedAmount['max']<=$amountFilter['max'])
    {
        $amountFilterMax = $appliedAmount['max'];
    	$amountFilter['maxLabel'] = $appliedAmount['maxLabel'];
    }else{
        $amountFilterMax = $amountFilter['max'];
    }
?>
<li class = "sliderSec">
   <input type="checkbox" class="main__check">
   <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3">Scholarship Amount</h3>
   <div>
      <ul class="sub__menu">
         <li>
             <?php if($amountFilter["min"]!=$amountFilter["max"]){?>
            <div>
              <span class="leftLabel"><?php echo $amountFilter["minLabel"]; ?></span>
              <span class="rightLabel"><?php echo $amountFilter["maxLabel"]; ?></span>
              <input type="hidden" class="schSliderMinLt" value="<?php echo $amountFilter["min"]; ?>">
              <input type="hidden" class="schSliderMin" name="schAmountMin" id="schAmountMin" value="<?php echo $amountFilterMin; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schAmountMin'];?>">
              <input type="hidden" class="schSliderMaxLt" value="<?php echo $amountFilter["max"]; ?>">
              <input type="hidden" class="schSliderMax" name="schAmountMax" id="schAmountMax" value="<?php echo $amountFilterMax; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['schAmountMax'];?>">
              <input type="hidden" class="schSliderInterval" name="schAmountInterval" id="schAmountInterval" value="<?php echo $amountFilter["interval"]; ?>">
            </div>
            <div id="amountSlider" class="schSlider" alias="<?php echo $queryStringFieldNameToAliasMapping['schAmountMin'];?>"></div>
             <?php } else if($request->isAjaxCall() || $request->isFilterAjaxCall()){?>
            <div class="amountNA filterNA">This filter option is not available.</div>
             <?php } ?>
         </li>
      </ul>
   </div>
</li>