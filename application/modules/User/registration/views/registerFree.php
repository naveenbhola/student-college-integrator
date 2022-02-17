<?php
$formCustomData['regFormId']=$regFormId;
$formCustomData['trackingPageKeyId']=$trackingPageKeyId;

if(isset($replyMsg)){
	$formCustomData['replyContext']= $replyContext;
}

if(isset($threadId) && $threadId != '0'){
	$formCustomData['threadId']= $threadId;
}
?>
<div class="layer-outer" style="width:365px; font-family: Trebuchet MS;">
    <div class="layer-title">
		<?php if($_REQUEST['callregisterfreeform'] != 1) { ?>
		<a class="close" title="Close" id="close" href="#" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); return false;"></a>
		<?php } ?>
   
        <div  class="title" id="registrationTitle_<?php echo $regFormId?>"><?php echo $layerTitle ? $layerTitle : "Join now for free";?></div>
    </div>
    <div class="layer-contents" id="layer-contents">
    <div style="float:left; font-size:14px;"><?php echo $layerHeading ? $layerHeading : "New Users, Register Free!"; ?></div>
    
	<?php if(!$hideLoginLink) { ?>
	<div style="float:right;"><a onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(); return false;" href="javascript:void(0);">Existing Users, Sign In</a></div>    
    <?php } ?>
	
	<div class="clearFix"></div>
    
    <div id="registerFreeFormIndia" style="width:325px; margin-left: 15px; margin-top:15px; <?php if($studyAbroad) echo "display:none;"; ?>">
            <?php echo Modules::run('registration/Forms/LDB',NULL,'registerFree',$formCustomData); ?>
    </div>
	<?php if($hasCallback) { ?>
		<input type="hidden" id="registerFreeHasCallback" value="<?php echo $hasCallback; ?>" />
	<?php } ?>	
    <div style="clear:both;"></div>
	</div>
	<?php $this->load->view('registration/common/OTP/userOtpVerification'); ?>
</div>