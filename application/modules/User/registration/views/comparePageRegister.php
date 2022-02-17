<?php
$formCustomData['regFormId']=$regFormId;
$formCustomData['trackingPageKeyId']=$trackingPageKeyId;
?>
<div class="layer-outer" style="width:640px; font-family: Trebuchet MS; padding: 0;" id="compare_login_layer_<?php echo $regFormId; ?>">
    <div class="layer-title" style="background:#e6e6e6; padding:10px;" id="layer_title_<?php echo $regFormId; ?>">
		<?php if($_REQUEST['callregisterfreeform'] != 1) { ?>
		<a class="close" title="Close" id="close" href="javascript:void(0);" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); return false;" style="right:8px; top:3px;"></a>
		<?php } ?>
   
        <div  class="title" id="registrationTitle_<?php echo $regFormId?>">Please Register to Compare Colleges </div>
    </div>
    <div class="layer-contents" id="layer-contents" style="padding:0 6px 10px 6px; background: #fff;">
    <!--<div style="float:left; font-size:14px;"><?php echo $layerHeading ? $layerHeading : "New Users, Register Free!"; ?></div>-->
    
	<?php if(!$hideLoginLink) { ?>
	<div style="float:right;"><a onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(true,'','',{'widgetName':'comparePageLogin','trackingPageKeyId':'<?php echo $trackingPageKeyId;?>'}); return false;" href="javascript:void(0);" style="margin-right:14px;">Existing Users, Sign In</a></div>    
    <?php } ?>
	
	<div class="clearFix"></div>
    
    <div id="registerFreeFormIndia" class="registration-left-col" style="margin-left: 15px; margin-top:15px; <?php if($studyAbroad) echo "display:none;"; ?>">
            <?php echo Modules::run('registration/Forms/LDB',NULL,'registerFree',$formCustomData); ?>
    </div>
    <div class="registration-form">
	<div class="registration-right-col flRt" style="width:230px;">
		<div class="reg-shortlist-title">Compare colleges on the basis of:</div>
	    <ul class="compare-college-list">
		<li><i class="common-sprite green-tick-mark"></i>College Rankings</li>
		<li><i class="common-sprite green-tick-mark"></i>Alumni Placement Data </li>
		<li><i class="common-sprite green-tick-mark"></i>Exam required & Cutoffs </li>
		<li><i class="common-sprite green-tick-mark"></i>Course fee </li>
		<li><i class="common-sprite green-tick-mark"></i>Affiliation & more </li>
	    </ul>
	</div>
	<div class="clearFix"></div>
    </div>
    <input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
    <div style="clear:both;"></div>
	</div>
	<?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
</div>