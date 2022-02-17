<?php global $queryStringFieldNameToAliasMapping; 
            $appliedFilters = $request->getAppliedFilters();
            $appliedStream = $appliedFilters['saScholarshipCategoryId'];
?>
<li>
    <input type="checkbox" class="main__check">
    <i class="custm__ico"></i>
   <h3 class="main__titl f12-clr3">Course Stream Applicable</h3>
   <div id = "streamScroll" class="course__stream">
      
               <ul class="sub__menu">
                      <?php foreach( $scholarshipData['filterData']['saScholarshipCategoryId'] as $categoryId => $catData){ ?>
                     <li>
                       <?php 
                            unset($scholarshipData['filterData']['saScholarshipCategoryId_parent'][$categoryId]);
                       ?>
                       <a class="chek__box">
                             <div class="">
                             <input type="checkbox" id="<?php echo strtolower($catData['name']); ?>" class="inputChk" name="scholarshipStream[]" value="<?php echo $categoryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipStream'];?>" <?php echo (in_array($categoryId,$appliedStream)?'checked':''); ?>/>
                              <label class="f12-clr3 check__opt" for="<?php echo strtolower($catData['name']); ?>">
                                    <i></i>
                                    <?php echo $catData['name']." (".$catData['count'].")"; ?>
                              </label>
                             </div>
                       </a>
                     </li>
                     <?php } ?>
                     <?php foreach( $scholarshipData['filterData']['saScholarshipCategoryId_parent'] as $categoryId => $catData){ ?>
                     <li>
                       <a class="chek__box">
                             <div class="">
                             <input type="checkbox" disabled id="<?php echo strtolower($catData['name']); ?>" class="inputChk" name="scholarshipStream[]" value="<?php echo $categoryId; ?>" alias="<?php echo $queryStringFieldNameToAliasMapping['scholarshipStream'];?>" <?php echo (in_array($categoryId,$appliedStream)?'checked':''); ?>/>
                              <label class="f12-clr3 check__opt" for="<?php echo strtolower($catData['name']); ?>">
                                    <i></i>
                                    <?php echo $catData['name']." (0)"; ?>
                              </label>
                             </div>
                       </a>    
                     </li>
                     <?php } ?>
               </ul>
            
   </div>
</li>