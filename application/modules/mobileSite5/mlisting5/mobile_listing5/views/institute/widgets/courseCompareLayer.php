<?php
$compareCourses = $_COOKIE['mob-compare-global-data'];
$compareCourses = explode("|", $compareCourses);

foreach ($compareCourses as $key => $value) {
    $compareCourses[$key] = explode("::", $value);
    $compareCourses[$key] = $compareCourses[$key][0];
}
?>
<div class="popup_layer lyr1 hid" id="course-compare-layer" data-role="dialog" data-transition="none" data-enhance="false">
    <div class="hlp-popup nopadng">
            <a href="javascript:void(0);" class="hlp-rmv" onclick="closeDetailsLayer(this);" >&times;</a>
            <div class="glry-div amen-box">
                <div class="hlp-info">
                    <div class="cont loc-layer">
                        <div class="hed">
                            Select a Course to Compare
                        </div>
                        <div class="loc-list-col">
                        <ul class="prm-lst" id="course-compare-list">
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
                              <li><p><input type="checkbox" cid="<?php echo $courseObj->getId();?>" id="ccl_<?php echo $courseObj->getId();?>" <?php echo (in_array($courseObj->getId(), $compareCourses) ? 'checked' : ''); ?>> <label for="ccl_<?php echo $courseObj->getId();?>"><?php echo htmlentities($courseName);?></label></p></li>
                            <?php
                              }
                            ?>
                        </ul>
                        </div>
                    </div>
                </div>
<!-- <div class="done-btn"><input type="button" class="green-btn" value="Close" onclick="closeDetailsLayer(this);"></div> -->
                <input type="hidden" id="compare_tracking_keyid" name="compare_tracking_keyid" value="">
        </div>
    </div>
</div>