<?php
if($validateuser != 'false'){
    $cookiestr = explode("|",$validateuser[0]['cookiestr']);
    $email = $cookiestr[0];
	$mobile = $validateuser[0]['mobile'];
}
else{
    $email = "";
	$mobile = "";
}
?>
<!--<div class="feedback-wrapper abroad-layer">-->
        <div style="position: absolute; width: 100%; height: 100%; top: 0px; left: 0px; opacity: 0.7; background: url('/public/images/loader.gif') no-repeat scroll 50% 50% rgb(254, 255, 254); z-index: 999999; display: none;" id="AbroadAjaxLoaderFeedback"></div>
	<div class="abroad-layer-head clearfix feedback-layer-head">
        <div class="flLt">Contact us</div>
        <a href="JavaScript:void(0);" onclick="hideFeedbackLayer();" title="Close" class="close-icn flRt">&times;</a>
    </div>
    <div class="stu-helpline-sec">
		<strong class="font-14" style="display:block; margin-bottom:7px;">Student Helpline</strong>
		<div class="flLt" style="width:47%">
			<p style="margin-bottom:10px;"><i class="common-sprite call-icon"></i>
			<strong>Call : <?php echo ABROAD_STUDENT_HELPLINE; ?></strong></p>
			<p style="font-size:12px;">(09:30 AM to 06:30 PM, Monday to Friday)</p>
		</div>
		<div class="flRt" style="width:52%">
			<p><i class="common-sprite footer-email-icon" style="margin-right:5px;"></i>
			<strong>Email : studyabroad@shiksha.com</strong></p>
		</div>
		<div class="clearfix"></div>
	</div>
    <div class="abroad-layer-content clearfix" style="padding:10px 20px;">
    <div id="feedbackMain">
        <form  id = "form_feedback" >
            <ul class="feedback-layer">
                <?php
                    if($email == ""){
                ?>
				<li><strong>Or write to us with your profile details</strong></li>
                <li>
					<div class="flLt" style="width:49%">
						<p class="feedback-label">Your mobile no*</p>
                    <div>
                        <input type="text" id = "feedback_mobile" required="true" class="universal-text" value="" minlength="10" maxlength = "10" caption = "Mobile"/>
                        <div class="feedbackErrorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" required="true" id="feedback_mobile_error"></div>
                    </div>
					</div>
					<div class="flRt" style="width:49%">
						<p class="feedback-label">Your email address*</p>
                    <div>
                        <input type="text" id = "feedback_email" required="true" class="universal-text" value="" maxlength = "100" caption = "Email"/>
                        <div class="feedbackErrorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" required="true" id="feedback_email_error"></div>
                    </div>
					</div>
					<div class="clearfix"></div>

                </li>
                <?php }
                else { ?>
                        <input type="hidden" id = "feedback_email" class="universal-text" value="<?=$email?>"/>
                        <input type="hidden" id = "feedback_mobile" class="universal-text" value="<?=$mobile?>"/>
                <?php } ?>
                <li>
                    <p class="feedback-label">Tell us about your profile & education interest*</p>
                    <div>
                        <textarea rows="2" cols="2" id = "feedback_comments" maxlength="5000" class="universal-text" style="height:130px" required="true" caption = "Feedback"></textarea>
                        <div class="feedbackErrorMsg" style="display:none;color:#FF0000;font-size:11px;padding-left:3px; clear:both;" id="feedback_comments_error"></div>
                    </div>
                </li>
                <li>
                    <div class="flLt">
                        <input type = "hidden" id = "session_id" value="<?=$session_id?>">
                        <input type = "hidden" id = "source_page_url" value="<?=$source_page_url?>">
                        <a href="JavaScript:void(0);" onclick = "showFeedbackProgressLayer(); setTimeout(function () {submitFeedbackForm();},1000);" class="button-style medium-button" style="padding:8px 30px">Submit</a>
                        <a href="JavaScript:void(0);" style="margin-left:20px" onclick="hideFeedbackLayer();">Cancel</a>
                    </div>
                    <div class="flRt">
                            <img src="/public/images/abroad_mailer_logo.jpg" width="127" alt="logo" />
                    </div>
                </li>
            </ul>
        </form>
    </div>
    <div id="feedbackThanks" style = "display:none">
        <div class="abroad-layer-title clearfix" style="margin:0px 0 15px 0; font-size:14px; color:#666666; line-height:24px">Thanks for contacting us.<br />
        We will take a look at your query and get back to you.
        </div>

        <a href="JavaScript:void(0);" class="button-style medium-button" onclick="hideFeedbackLayer();" style="padding:8px 30px; background:#bebebe; color:#666666; margin-top:5px">Close</a>

    </div>
    <div id="feedbackError" style = "display:none">
        <div class="abroad-layer-title clearfix" style="margin:0px 0 15px 0; font-size:14px; color:#666666; line-height:24px">
        An Error Occured. Please check back later.
        </div>

        <a href="JavaScript:void(0);" class="button-style medium-button" onclick="hideFeedbackLayer();" style="padding:8px 30px; background:#bebebe; color:#666666; margin-top:5px">Close</a>

    </div>
</div>
<script>
    var submitFlag= false;
</script>
<!--</div>-->
