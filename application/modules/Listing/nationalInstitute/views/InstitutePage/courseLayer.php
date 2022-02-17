<!--course list layer starts-->
<div class="layer-common" id="courselayer">
   <div class="group-card pop-div">
      <a class="cls-head" onclick="hideCourseLayer();"></a>
      <div>
         <h3 class="head-3">Select a course</h3>
         <div class="custom-dropdown mrg-btm-10 gen-cat">
            <div class="dropdown-primary" id="clist" defaulttext="Select Course">
                    <span class="option-slctd"></span>
                    <span class="icon"></span>
                    <ul class="dropdown-nav" style="display: none;">
                    <div id="courseScrollDiv">
                    
                  
                  <div class="list crselyr">
                           <?php 
                        foreach ($coursesWidgetData['allCourses'] as $courseObj) {
                            $instituteName = $courseObj->getOfferedByShortName();
                            $instituteName = $instituteName ? $instituteName : $instituteObj->getShortName();
                            $instituteName = $instituteName ? $instituteName : $instituteObj->getName();

                            $courseName = $courseObj->getName();
                            if($listing_type == 'university'){
                              $courseName .= ", ".$instituteName;
                            }
                     ?>
                        <li class="li-dropdown"><a class="li-dropdown-a" value="<?php echo $courseObj->getId();?>"><?php echo htmlentities($courseName);?></a></li>
                     <?php } ?>
                  </div>
               </div>
                    </ul>
            </div>
         </div>
         <div id="courseLayerError" style="display:none;">
            <div class="regErrorMsg">Please select the course</div>
         </div>
         <div class="algn-rt">
            <input type="button" value="Submit" class="button button--orange" onclick="submitCourseLayer();">
            <input type="hidden" value="" id="cta_trackingKeyId" name="cta_trackingKeyId">
            <input type="hidden" value="" id="courseSelectedFromLayer" name="courseSelectedFromLayer">
         </div>
      </div>
   </div>
</div>


<!--course list layer ends-->