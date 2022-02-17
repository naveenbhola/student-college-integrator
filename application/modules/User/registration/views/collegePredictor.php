<style>
ul.get-more-list{width:100%; margin-left:15px;}
ul.get-more-list li{margin-bottom:10px; font-size:14px;color:#f76840 !important; list-style: disc !important;}
ul.get-more-list li p{color:#4a4a4a;}
</style>

<?php
$formCustomData['regFormId']=$regFormId;
$formCustomData['trackingPageKeyId']=$trackingPageKeyId;
?>
<div class="layer-outer" style="width:640px; font-family: Trebuchet MS; padding: 0;" id="compare_login_layer_<?php echo $regFormId; ?>">
    <div class="layer-title" style="background:#e6e6e6; padding:10px;" id="layer_title_<?php echo $regFormId; ?>">
		<?php if($_REQUEST['callregisterfreeform'] != 1) { ?>
		<a class="close" title="Close" id="close" href="javascript:void(0);" onclick="setCookie('collegepredictor_search_desktop_'+examName,'',1,'/',COOKIEDOMAIN); shikshaUserRegistration.closeRegisterFreeLayer(); return false;" style="right:8px; top:3px;"></a>
		<?php } ?>
   
        <div  class="title" id="registrationTitle_<?php echo $regFormId?>">Enter your details to get search details & more... </div>
    </div>
    <div class="layer-contents" id="layer-contents" style="padding:0 6px 10px 6px; background: #fff;">
    <!--<div style="float:left; font-size:14px;"><?php echo $layerHeading ? $layerHeading : "New Users, Register Free!"; ?></div>-->
    
	<?php if(!$hideLoginLink) { ?>
	<div style="float:right;"><a onclick="shikshaUserRegistration.showLoginLayer(true,'','',{'widgetName':'collegePredictor'});" href="javascript:void(0);" style="margin-right:14px;">Already registered?</a></div>    
    <?php } ?>
	
	<div class="clearFix"></div>
    
    <div id="registerFreeFormIndia" class="registration-left-col" style="margin-left: 15px; margin-top:15px; <?php if($studyAbroad) echo "display:none;"; ?>">
            
            <?php  echo Modules::run('registration/Forms/LDB',NULL,'registerFree',$formCustomData); ?>
    </div>
    <div class="registration-form">
	<div class="registration-right-col flRt" style="width:230px;">
		<div class="reg-shortlist-title" style="border-bottom:3px solid #f78640; padding-bottom:8px;">What more you get?</div>
	    <ul class="get-more-list">
		<li><p>Exam updates</p></li>
		<li><p>Admission alerts</p></li>
		<li><p>Top ranked colleges</p></li>
		<li><p>Placement data</p></li>
		<li><p>Whatsapp session with colleges</p></li>
		<li><p>College predictor for all major exams</p></li>
	    </ul>
	</div>
	<div class="clearFix"></div>
    </div>
    <input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
    <div style="clear:both;"></div>
	</div>
	<?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
</div>
