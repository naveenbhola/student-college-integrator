<!--course list layer starts-->
<div class="layer-common" id="courselayer">
   <div class="group-card pop-div">
      <a class="cls-head" onclick="hideCourseLayer();"></a>
      <div>
         <h3 class="head-3">Select a course to compare</h3>
         <div class="custom-dropdown mrg-btm-10 gen-cat">
            <div class="dropdown-primary" id="clist" onclick="updateTinyScrollBar('#courseScrollDiv');" defaulttext="Select Course">
                    <span class="option-slctd"></span>
                    <span class="icon"></span>
                    <ul class="dropdown-nav" style="display: none;">
                    <div id="courseScrollDiv">
                    <div class="scrollbar disable" ><div class="track">
                  <div class="thumb"><div class="end"></div></div></div>
               </div>
                  <div class="viewport">
                  <div class="overview list">
                      <?php 
                        foreach ($instituteCourses as $courseData) { ?>
                        <li class="li-dropdown"><a class="li-dropdown-a" value="<?=$courseData['course_id'];?>"><?=htmlentities($courseData['course_name']);?></a></li>
                     <?php } ?>
                  </div>
                  </div>
               </div>
                    </ul>
            </div>
         </div>
         <div id="courseLayerError" style="display:none;">
            <div class="regErrorMsg">Please select the course</div>
         </div>
         <div class="algn-rt">
            <input type="button" value="Submit" class="btn-primary" onclick="submitCourseLayer();">
            <input type="hidden" value="" id="cta_trackingKeyId" name="cta_trackingKeyId">
            <input type="hidden" value="" id="courseSelectedFromLayer" name="courseSelectedFromLayer">
         </div>
      </div>
   </div>
</div>

<!--course list layer ends-->