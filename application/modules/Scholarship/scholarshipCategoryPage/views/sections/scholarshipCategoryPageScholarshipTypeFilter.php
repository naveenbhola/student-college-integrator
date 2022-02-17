<?php global $queryStringFieldNameToAliasMapping;
            $appliedFilters = $request->getAppliedFilters();
            $appliedType = $appliedFilters['saScholarshipType'];
?>
<li>
   <input type="checkbox" class="main__check">
   <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3">Scholarship Type</h3>
   <div>
      <ul class="sub__menu">
         <?php foreach($scholarshipData['filterData']['saScholarshipType'] as $type=>$typeCount){ ?>
            <li>
              <?php unset($scholarshipData['filterData']['saScholarshipType_parent'][$type]);
              ?>
              <a class="chek__box">
                    <div class="">
                     <input type="checkbox" id="<?php echo $type; ?>" class="inputChk" name="scholarshipType[]" value="<?php echo $type; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipType'];?>" <?php echo (in_array($type,$appliedType)?'checked':''); ?>/>
                     <label class="f12-clr3 check__opt" for="<?php echo $type; ?>">
                           <i></i>
                           <?php echo ucfirst($type); ?> Based (<?php echo $typeCount; ?>)
                     </label>
                    </div>
              </a>
            </li>
         <?php } ?>
         <?php foreach($scholarshipData['filterData']['saScholarshipType_parent'] as $type=>$typeCount){                 
             ?>
            <li>
              <a class="chek__box">
                    <div class="">
                     <input type="checkbox" disabled id="<?php echo $type; ?>" class="inputChk" name="scholarshipType[]" value="<?php echo $type; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipType'];?>" <?php echo (in_array($type,$appliedType)?'checked':''); ?>/>
                     <label class="f12-clr3 check__opt" for="<?php echo $type; ?>">
                           <i></i>
                           <?php echo ucfirst($type); ?> Based (0)
                     </label>
                    </div>
              </a>
            </li>
         <?php } ?>
      </ul>
   </div>
</li>