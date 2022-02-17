<div class="popup_layer lyr1" id="shortlist-layer" style="display:none;">
    <div class="hlp-popup nopadng">
            <a href="javascript:void(0);" class="hlp-rmv" data-rel = 'back' ">&times;</a>
            <div class="glry-div amen-box">
                <div class="hlp-info">
                    <div class="cont loc-layer">
                        <div class="hed">
                            Select a Course to Shortlist
                        </div>
                        <div class="loc-list-col">
                        <ul class="prm-lst">
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
                              <li onclick="selectCourseForShortlist(this,event);">
                                <p>
                                    <input type="checkbox" cid="<?php echo $courseObj->getId();?>" id="sl_<?php echo $courseObj->getId();?>" <?php echo (in_array($courseObj->getId(), $shortlistedCourseIds) ? 'checked' : ''); ?>> <label for="sl_<?php echo $courseObj->getId();?>"><?php echo htmlentities($courseName);?></label>
                                </p>
                                </li>
                            <?php
                              }
                            ?>
                        </ul>
                        </div>
                    </div>
                </div>
                <!-- <div class="done-btn"><input type="button" class="green-btn" value="Close" onclick="closeDetailsLayer(this);"></div> -->
                <input type="hidden" id="shortlist_tracking_keyid" name="shortlist_tracking_keyid" value="">
        </div>
    </div>
</div>