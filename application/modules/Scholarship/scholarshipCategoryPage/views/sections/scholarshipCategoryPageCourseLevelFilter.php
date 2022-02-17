<?php global $queryStringFieldNameToAliasMapping; 
            $appliedFilters = $request->getAppliedFilters();
            $appliedLevels = $appliedFilters['saScholarshipCourseLevel'];
?>
<li>
    <input type="checkbox" class="main__check">
        <i class="custm__ico"></i>
    <h3 class="main__titl f12-clr3">Course Level</h3>
        <div>
           <ul class="sub__menu">
            <?php foreach($scholarshipData['filterData']['saScholarshipCourseLevel'] as $level => $lvlCount){ ?>
             <li>
                <?php 
                    unset($scholarshipData['filterData']['saScholarshipCourseLevel_parent'][$level]);
                ?>
                <a class="chek__box">
                  <div class="">
                      
                      <input type="checkbox" id="level_<?php echo $level; ?>" value="<?php echo strtolower($level);?>" name="scholarshipLevel[]" class="inputChk" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipLevel'];?>" <?php echo (in_array(strtolower($level),$appliedLevels)?'checked':''); ?>/>
                       <label class="f12-clr3 check__opt" for="level_<?php echo $level; ?>">
                         <i></i>
                              <?php echo $level." (".$lvlCount.")"; ?>
                       </label>
                      </div>
                </a>
              </li>
             <?php } ?>
              <?php foreach($scholarshipData['filterData']['saScholarshipCourseLevel_parent'] as $level => $lvlCount){ ?>
             <li>                
                <a class="chek__box">
                  <div class="">
                      
                      <input type="checkbox" disabled id="level_<?php echo $level; ?>" value="<?php echo strtolower($level);?>" name="scholarshipLevel[]" class="inputChk" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipLevel'];?>" <?php echo (in_array(strtolower($level),$appliedLevels)?'checked':''); ?>/>
                       <label class="f12-clr3 check__opt" for="level_<?php echo $level; ?>">
                         <i></i>
                              <?php echo $level." (0)"; ?>
                       </label>
                      </div>
                </a>
              </li>
             <?php } ?>
           </ul>
        </div>
</li>