<?php $widget = 'incorrectInfo'.rand();?>
<div id="incorrectScholarshipContainer" data-role="page" data-enhance="false" tabindex="0">
    <div class="layer-header">
        <a id="back-button" href="#wrapper" data-rel="back" data-transition="slide" class="back-box" ><i class="sprite back-icn"></i></a>
            <p id="">Scholarship</p>
    </div>
    <div class="suggest__layer">
    <h4 class="fnt__14__bold clr__3 m__all">Report incorrect information</h4>
    <p class="fnt__12 clr__6">if you found any error in the information of this  scholarship, please write below</p>
   <div class="">
    <form class="form__field" name="incorrectScholarshipForm" action="">
        <input type="text" id="incorrectEmail" name="incorrectEmail"  placeholder="Your email id (optional)" class="layer__input fnt__12" value="<?=$email?>">
        <input type="hidden" id="incorrectSessionId" name="incorrectSessionId" class="universal-text" value="<?=sessionId()?>"/>
        <textarea id="incorrectComments" name="incorrectComments" value="" class="layer__txtarea" placeholder="Write Here" caption="text before proceeding" required="true"  maxlength="5000"></textarea>
        <div class="feedbackErrorMsg errorMsg" id="incorrectCommentsError"></div>
        <div style="margin:0px 0 10px;">
            <img class="vam flLt" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&secvariable=incorrectInfo_<?=$widget?>&randomkey=<?php echo rand(); ?>" align="absbottom" width="100" height="34" id = "incorrectInfoIMG_<?=$widget?>"/>
            <input type="text" alt="secret code" class="register-fields universal-text" style="margin:0 0 8px 9px;width:120px;padding:7px;border:1px solid #ccc;"  name = "incorrectInfo_<?=$widget?>" id = "incorrectInfo_<?=$widget?>" required = "true" validate = "validateSecurityCode" maxlength = "5" minlength = "5"  caption = "security code"/>
            <input type="hidden" value="incorrectInfo_<?=$widget?>" id="securityCodeVar_<?=$widget?>" name="securityCodeVar">
            <div class="errorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" id="incorrectInfo_<?=$widget?>_error"></div>
            <div class="clearfix"></div>
        </div>
        <a id="incorrectScholarshipFeedbackLink" scholarshipId="<?php echo $scholarshipObj->getId(); ?>" incorrectFormSuffix="<?= $widget;?>" secvarible="<?="incorrectInfo_".$widget;?>" href="javascript:void(0)" class="btn btn-default btn-full">Submit</a>
    </form>
   </div>
    </div>
</div>