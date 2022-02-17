    <?php global $queryStringFieldNameToAliasMapping; 
   $appliedFilters = $request->getAppliedFilters();
   $applicability = $appliedFilters['saScholarshipCategory'];
   $appliedUniversities = $appliedFilters['saScholarshipUnivId'];
?>
<li>
   <input type="checkbox" class="main__check">
   <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3 fnt-sbold">Scholarship Applicability</h3>
   <div class="country__scroll">
      <ul class="sub__menu">
         <li>
            <?php if($scholarshipData['filterData']['saScholarshipCategory']['external']){ ?>
            <a class="chek__box">
               <div class="">
                  <input type="checkbox" id="schCategory_external" class="inputChk" name="scholarshipCategory[]" value="external" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipCategory'];?>" <?php echo (in_array("external",$applicability)?'checked':''); ?>/>
                  <label class="f12-clr3 check__opt" for="schCategory_external">
                     <i></i>
                     Not specific to a college (<?php echo $scholarshipData['filterData']['saScholarshipCategory']['external']; ?>)
                  </label>
               </div>
            </a>
            <?php } else if($scholarshipData['filterData']['saScholarshipCategory_parent']['external']){ ?>
            <a class="chek__box">
               <div class="">
                  <input type="checkbox" disabled id="schCategory_external" class="inputChk" name="scholarshipCategory[]" value="external" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipCategory'];?>" <?php echo (in_array("external",$applicability)?'checked':''); ?>/>
                  <label class="f12-clr3 check__opt" for="schCategory_external">
                     <i></i>
                     Not specific to a college (0)
                  </label>
               </div>
            </a>    
            <?php } ?>
         </li>
         <li>
            <a class="chek__box">
               <div class="">
                   <?php if(!empty($appliedUniversities)) {
                            $class = 'single_slct';
                        }else{
                            $class = 'inputChk';
                        }
                   if($scholarshipData['filterData']['saScholarshipCategory']['internal']){ ?>
                  <input type="checkbox" name="scholarshipCategory[]" id="schCategory_internal" class="<?php echo $class;?>" value="internal" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipCategory'];?>" <?php echo (in_array("internal",$applicability)?'checked':''); ?>/>
                  <label class="f12-clr3 check__opt" for="schCategory_internal">
                     <i></i>
                     College Specific (<?php echo $scholarshipData['filterData']['saScholarshipCategory']['internal']; ?>)
                  </label>
                  <?php } else if($scholarshipData['filterData']['saScholarshipCategory_parent']['internal']){ ?>
                  <input type="checkbox" disabled name="scholarshipCategory[]" id="schCategory_internal" class="<?php echo $class;?>" value="internal" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipCategory'];?>" <?php echo (in_array("internal",$applicability)?'checked':''); ?>/>
                  <label class="f12-clr3 check__opt" for="schCategory_internal">
                     <i></i>
                     College Specific (0)
                  </label>    
                  <?php } ?>
               </div>
            </a>
            <?php if(count($scholarshipData['filterData']['saScholarshipUnivIdNameMap_parent'])>0){ ?>
            <div class="show-optns applicable__sc" id="univBlock">
               <div class="search__univ">
                  <i class="srch__ico"></i>
                  <?php if(!$scholarshipData['filterData']['saScholarshipCategory']['internal']){$disabled = true;}?>
                  <input type="text" <?php if($disabled)echo "disabled "; ?>id="univSearchBox" placeholder="Search University">
               </div>
               <div id="univScroll">
                  <ul class="sub__menu">
                     <?php foreach($scholarshipData['filterData']['saScholarshipUnivIdNameMap'] as $univId => $univData){ ?>
                     <li class="univList">
                         <?php unset($scholarshipData['filterData']['saScholarshipUnivIdNameMap_parent'][$univId]); ?>
                               <a class="chek__box">
                                  <div class="">
                                     <input type="checkbox" name="scholarshipUniversity[]" id="schUniv_<?php echo $univId; ?>" class="inputChk" value="<?php echo $univId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipUniversity'];?>" <?php echo (in_array($univId,$appliedUniversities)?'checked':''); ?>/>
                                     <label class="f12-clr3 check__opt" for="schUniv_<?php echo $univId; ?>">
                                              <i></i>
                                              <span class="univName"><?php echo htmlentities($univData['name']); ?></span><?php echo " (".$univData['count'].")"; ?>
                                     </label>
                                  </div>
                               </a>
                         
                                       <input type="hidden" class="univNameReal" value="<?php echo htmlentities($univData['name']); ?>" />
                     </li>
                     <?php } ?>
                     <?php foreach($scholarshipData['filterData']['saScholarshipUnivIdNameMap_parent'] as $univId => $univData){ ?>
                     <li class="univList">
                           <a class="chek__box">
                             <div class="">
                                 <input type="checkbox" disabled name="scholarshipUniversity[]" id="schUniv_<?php echo $univId; ?>" class="inputChk" value="<?php echo $univId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipUniversity'];?>" <?php echo (in_array($univId,$appliedUniversities)?'checked':''); ?>/>
                                <label class="f12-clr3 check__opt" for="schUniv_<?php echo $univId; ?>">
                                         <i></i>
                                         <span class="univName"><?php echo htmlentities($univData['name']); ?></span> (0)
                                </label>
                             </div>
                           </a>
                           <input type="hidden" class="univNameReal" value="<?php echo htmlentities($univData['name']); ?>" />
                     </li>
                     <?php } ?>
                  </ul>
               </div>
            </div>
            <?php } ?>
         </li>
      </ul>
   </div>
</li>
