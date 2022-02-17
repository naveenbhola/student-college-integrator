<?php
 if(!empty($consultantRelatedData['consultantData'])){
?>
<article class="content-inner clearfix" style=" display: none;padding-top: 0px !important;" id="consultantLayer_<?=$regFormId?>">
   <ul class="form-display customInputs">
    <li style="margin:15px 0;">
            <p class="form-label consultant-text">A Shiksha Verified counsultant can help you with admission related guidance for <?= htmlentities($universityName);?></p>
            <div class="flLt" style="width:100%; margin-bottom:8px;">
                <input type="radio" id="consultantRequired_yes_<?=$regFormId?>" class="consultantRequired" name="consultantRequired" value="yes" label="Consultant Required" checked=false onclick="toggleConsultantsList('<?= $regFormId; ?>',this);">
                <label for="consultantRequired_yes_<?=$regFormId?>">
                    <span class="sprite"></span>
                    <strong style="margin-left:5px;">Yes, I want a consultant to get in touch with me</strong>
                </label>
            </div>  <br>
    
            <div class="flLt" style="width:100%;">
                <input type="radio" id="consultantRequired_no_<?=$regFormId?>" class="consultantRequired" name="consultantRequired" value="no" label="Consultant Required" checked="false" onclick="toggleConsultantsList('<?= $regFormId; ?>',this);">
                <label for="consultantRequired_no_<?=$regFormId?>">
                    <span class="sprite"></span>
                    <strong style="margin-left:5px;">No, I don't need help</strong>
                </label>
            </div>
            <div id="selectConsultantsList_<?= $regFormId; ?>_error" class="errorMsg error-msg" style="display: none"></div>
        </li>                      
        <li id="consultant-ins-list-<?= $regFormId; ?>" style="display:none;">
            <p class="form-label">Select consultants below</p>
            <div class="consultant-detail-sec">
                <input type="checkbox" id="consultant1_<?= $regFormId; ?>" name="consultantList[]" value="">
                    <label for="consultant1_<?= $regFormId; ?>"><span class="sprite flLt"></span>
                        <div class="consultant-details">
                        <div class="consultantNameStrong">ED Wise International</div>
                        <strong class="consultantOfficeAddress">Lajpat Nager, New Delhi</strong>
                        </div>
                    </label>
            </div>
            <div class="consultant-detail-sec">
                <input type="checkbox" id="consultant2_<?= $regFormId; ?>" name="consultantList[]" value="">
                    <label for="consultant2_<?= $regFormId; ?>"><span class="sprite flLt"></span>
                    <div class="consultant-details">
                        <div class="consultantNameStrong">Ace Abroad Education Consultant</div>
                        <strong class="consultantOfficeAddress">Lajpat Nager, New Delhi</strong></div>                            		</label>
            </div>
            <div class="consultant-detail-sec">
                <input type="checkbox" id="consultant3_<?= $regFormId; ?>" name="consultantList[]" value="">
                    <label for="consultant3_<?= $regFormId; ?>"><span class="sprite flLt"></span>
                    <div class="consultant-details">
                    <div class="consultantNameStrong">Brainstrom Consultancy Pvt. Ltd.</div>
                    <strong class="consultantOfficeAddress">Lajpat Nager, New Delhi</strong></div>                            		</label>
            </div>
            <div id="requiredConsultantsList_<?= $regFormId; ?>_error" class="errorMsg error-msg" style="display: none"></div>
        </li> 
   </ul>
   <input type="hidden" value="1" id="consultantSelectedFlag_<?= $regFormId; ?>" name="consultantSelectedFlag">
   <input type="hidden" value="" id="userRegion_<?= $regFormId; ?>" name="userRegion">
    <script>
        var consultantInRegistration    = true;
        var consultantList              = <?=  json_encode($consultantRelatedData['consultantData'])?>;
        var consultantCityRegionData    = <?=  json_encode($consultantRelatedData['regionMappingData'])?>;
        var loggedInUserCity            = <?=$consultantRelatedData['userCity']?>;
        var excludedCourses             = <?=  json_encode($consultantRelatedData['excludedCourses'])?>;
        var consutantForCourseId        = <?=($courseId)?$courseId:0?>;
    </script>
</article>
<?php }?>