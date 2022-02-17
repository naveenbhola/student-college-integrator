<?php
    //_p($consultantData);
//_p($consultantRelatedData['excludedCourse']);die;
    if(!empty($consultantRelatedData['consultantData'])){

// change background color according to the parent form        
if(!isset($brochureDataObj))
{
    $mainBackgroundStyle= "background: #fff; margin:0; border: 0;display: none;";
    $consultantTupleStyle = "background:#f8f8f8;";
}else
{
    $mainBackgroundStyle = "display: none;";
    $consultantTupleStyle = "";
}        
?>
<div id="consultantLayer_<?=$regFormId?>" class="verified-box clearwidth" style="<?= $mainBackgroundStyle;?>">
    <strong>A Shiksha Verified consultant can help you with admission related guidance for this university</strong>
    <div class="clearfix"></div>
    <ul class="bro-form-list customInputs" style="width:100%">
        <li style="margin-bottom:0;">
            <div class="flLt signUp-child-wrap" style="padding:0; width:100%">
                <div class="columns" style="width:60%">
                    <input type="radio" id="consultantRequired_yes_<?=$regFormId?>" class="consultantRequired" name="consultantRequired" value="yes" label="Consultant Required" onclick="toggleConsultantsList('<?= $regFormId; ?>',this);">
                    <label for="consultantRequired_yes_<?=$regFormId?>">
                        <span class="common-sprite"></span>
                        <p style="margin-top:0"><strong style="font-size:12px !important;">Yes, I want a consultant to get in touch with me</strong></p>
                    </label>
                </div>
                <div class="columns" style="width:40%">
                    <input type="radio" id="consultantRequired_no_<?=$regFormId?>" class="consultantRequired" name="consultantRequired" value="no" label="Consultant Required" onclick="toggleConsultantsList('<?= $regFormId; ?>',this);">
                    <label for="consultantRequired_no_<?=$regFormId?>">
                        <span class="common-sprite"></span>
                        <p style="margin-top:0"><strong style="font-size:12px !important;">No, I don't need help</strong></p>
                    </label>
                </div>
                <div id="selectConsultantsList_<?= $regFormId; ?>_error" class="errorMsg" style="display: none"></div>
                <div class="clearfix"></div>
            </div>
        </li>
    </ul>
    <div class="clearfix"></div>
    <div id="requiredConsultantsList_<?= $regFormId; ?>" style="display: none">
        <p class="consultant-text" style="margin-bottom:8px;"><strong>Select consultants below </strong>(showing consultants based on your current city)</p>
        <ul class="consultant-ins-list customInputs-large-2" id="consultant-ins-list-<?= $regFormId; ?>">
            <li style="<?= $consultantTupleStyle;?>">
                <div class="flLt" style="margin:3px 0px;">
                    <input type="checkbox" id="consultant1_<?= $regFormId; ?>" name="consultantList[]" value=""/>
                    <label for="consultant1_<?= $regFormId; ?>">
                        <span class="common-sprite"></span>
                    </label>
                </div>
                <div class="consultant-info-box">
                    <a href="javascript:void(0)" target="_blank"><strong class="consultantNameStrong" style="word-wrap:break-word">Aec Abroad Education Consultants</strong></a>
                    <p class="font-11 consultantOfficeAddressP">Lajpat Nagar, New Delhi</p>
                </div>
            </li>
            <li style="<?= $consultantTupleStyle;?>">
                <div class="flLt" style="margin:3px 0px;">
                    <input type="checkbox" id="consultant2_<?= $regFormId; ?>" name="consultantList[]" value=""/>
                    <label for="consultant2_<?= $regFormId; ?>">
                        <span class="common-sprite"></span>
                    </label>
                </div>
                <div class="consultant-info-box">
                    <a href="javascript:void(0)" target="_blank"><strong class="consultantNameStrong" style="word-wrap:break-word">Aec Abroad Education Consultants</strong></a>
                    <p class="font-11 consultantOfficeAddressP">Lajpat Nagar, New Delhi</p>
                </div>
            </li>
            <li class="noMargin" style="<?= $consultantTupleStyle;?>">
                <div class="flLt" style="margin:3px 0;">
                    <input type="checkbox" id="consultant3_<?= $regFormId; ?>" name="consultantList[]" value=""/>
                    <label for="consultant3_<?= $regFormId; ?>">
                        <span class="common-sprite"></span>
                    </label>
                </div>
                <div class="consultant-info-box">
                    <a href="javascript:void(0)" target="_blank"><strong class="consultantNameStrong" style="word-wrap:break-word">Aec Abroad Education Consultants</strong></a>
                    <p class="font-11 consultantOfficeAddressP">Lajpat Nagar, New Delhi</p>
                </div>
            </li>
        </ul>
        <div id="requiredConsultantsList_<?= $regFormId; ?>_error" class="errorMsg" style="display: none"></div>
    </div>
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
</div>
    <?php }?>