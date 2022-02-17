<?php global $queryStringFieldNameToAliasMapping; 
            $appliedFilters = $request->getAppliedFilters();
            $appliedCitizenships = $appliedFilters['saScholarshipStudentNationality'];
?>
<li>
   <input type="checkbox" class="main__check">
   <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3 fnt-sbold">Student Citizenship</h3>
   <div id="ctznScroll" class="citizen__sc">

               <ul class="sub__menu">
                  <?php 
                  if(isset($scholarshipData['filterData']['saScholarshipStudentNationality'][2])){
                      $countryId = 2;
                  }else{
                       reset($scholarshipData['filterData']['saScholarshipStudentNationality']);
                       $countryId = key($scholarshipData['filterData']['saScholarshipStudentNationality']);
                  }
                  if($countryId){
                     $ctryData = $scholarshipData['filterData']['saScholarshipStudentNationality'][$countryId];
                     unset($scholarshipData['filterData']['saScholarshipStudentNationality_parent'][$countryId]);
                     unset($scholarshipData['filterData']['saScholarshipStudentNationality'][$countryId]);
                  ?> 
                  <li class="natRows">
                     <a class="chek__box">
                        <div class="">
                           <input type="checkbox" id="nationality_<?php echo $countryId; ?>" class="inputChk" name="studentNationality[]" value="<?php echo $countryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['studentNationality'];?>" <?php echo (in_array($countryId,$appliedCitizenships)?'checked':''); ?>/>
                           <label class="f12-clr3 check__opt" for="nationality_<?php echo $countryId; ?>">
                                 <i></i>
                                 <?php echo $ctryData['name']." (".$ctryData['count'].")"; ?>
                           </label>
                        </div>
                     </a>
                     <?php if(count($scholarshipData['filterData']['saScholarshipStudentNationality_parent'])>1 && $shownFlag !== true){ ?>
                     <a href="Javascript:void(0);" id="moreNat">+ <?php echo count($scholarshipData['filterData']['saScholarshipStudentNationality_parent'])-1; ?> more</a>
                     <?php $shownFlag = true; } ?>
                  </li>
                  <?php } ?>
                  <?php foreach($scholarshipData['filterData']['saScholarshipStudentNationality'] as $countryId => $ctryData){ 
                      unset($scholarshipData['filterData']['saScholarshipStudentNationality_parent'][$countryId]);
                  ?>
                  <li class="natRows" <?php echo ($shownFlag ===true?'style="display:none;"':''); ?>>
                     <a class="chek__box">
                        <div class="">
                           <input type="checkbox" id="nationality_<?php echo $countryId; ?>" class="inputChk" name="studentNationality[]" value="<?php echo $countryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['studentNationality'];?>" <?php echo (in_array($countryId,$appliedCitizenships)?'checked':''); ?>/>
                           <label class="f12-clr3 check__opt" for="nationality_<?php echo $countryId; ?>">
                                 <i></i>
                                 <?php echo $ctryData['name']." (".$ctryData['count'].")"; ?>
                           </label>
                        </div>
                     </a>
                  </li>
                  <?php } ?>
                  <?php foreach($scholarshipData['filterData']['saScholarshipStudentNationality_parent'] as $countryId => $ctryData){ 
                  ?>
                  <li class="natRows" <?php echo ($shownFlag ===true?'style="display:none;"':''); ?>>
                     <a class="chek__box">
                        <div class="">
                           <input type="checkbox" disabled id="nationality_<?php echo $countryId; ?>" class="inputChk" name="studentNationality[]" value="<?php echo $countryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['studentNationality'];?>" <?php echo (in_array($countryId,$appliedCitizenships)?'checked':''); ?>/>
                           <label class="f12-clr3 check__opt" for="nationality_<?php echo $countryId; ?>">
                                 <i></i>
                                 <?php echo $ctryData['name']." (0)"; ?>
                           </label>
                        </div>
                     </a>
                  </li>
                  <?php } ?>
               </ul>
            
   </div>
</li>