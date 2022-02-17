<?php
if(isset($_COOKIE['MOB_A_C'])){
    $appliedCourseArr = explode(',',$_COOKIE['MOB_A_C']);
}
$test = true;
$courseOrInst = $collegeOrInstituteRNR;
$onclick = "$('#tracking_keyid".$course->getId()."').val('".$contactdTrackingPageKeyId."');$('#brochureForm".$course->getId()."').submit();";
if($boomr_pageid == 'listing_detail_course'){
    $test = false;
    $courseOrInst = 'course';
    $onclick = "make('".$contactdTrackingPageKeyId."');$('#request_e_brochure".$course->getId()."_another').trigger('click');";
}
if($test || checkEBrochureFunctionality($courseComplete)){ ?>
    <div style="padding-top:8px;">
    <p>For more details about this <?=$courseOrInst?>:
        <?php if(!$test && in_array($course->getId(), $appliedCourseArr)){?>
            <a class="button blue small disabled" style="margin-top: 3px !important" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>_contact"><i class="icon-pencil" aria-hidden="true"></i><span style="color:#fff;">Request E-Brochure</span></a>
        <?php }else{ ?>
            <a class="button blue small" style="margin-top: 3px !important" href="javascript:void(0);" id="request_e_brochure<?=$course->getId();?>_contact" onClick="<?=$onclick?>"><i class="icon-pencil" aria-hidden="true"></i><span style="color:#fff;">Request E-Brochure</span></a>
        <?php } ?>
    </p>
    </div>
<?php
}
?>
