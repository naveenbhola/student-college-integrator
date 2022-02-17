<?php
$displayVerify = 'style="display:none;"';
$displayFail = 'style="display:none;"';
if(!empty($response)) {
    if($response == 'yes') {
	$displayVerify = '';
    }
    else if($response == 'no') {
	$displayFail = '';
    }
}

?>

<?php if($OTPVerification) { ?>
<div id="user-Authentication" <?php echo $displayVerify; ?>>
    <div class="blkRound">
	<div class="layer-title">
	    <div class="title">Verify Mobile Number</div>
	</div>
	<div class="pin-detail">
	    <p class="pin-title">PIN has been sent to your mobile to verify the mobile number</p>
	    <p>Please enter the PIN received on your mobile below</p>
	    <div>
	    <input type="text" class="universal-txt-field flLt" id="one-time-pass" maxlength="4"/><a href="javascript:void(0);" onclick="showVerificationLayer('<?=$widget?>', <?=$OTPVerification?>, <?=$ODBVerification?>, null, 1);" class="flLt" style="padding-top:15px; font-size:14px;">Resend Pin</a>
	    <div class="clearFix"></div>
	    </div>
	    <div id="pin-Error" class="errorMsg" style="margin-bottom:8px; float:left; width:100%;"></div>
	    <a href="javascript:void(0);" class="orange-button" onclick="verifyUserAuthentication('<?=$widget?>', <?=$OTPVerification?>, <?=$ODBVerification?>);" style="margin-top:10px;">Submit</a>
	</div>
    </div>
</div>
<div id="send-Failed" <?php echo $displayFail; ?>>
    <div class="blkRound">
	<div class="layer-title">
	    <div class="title">Verify Mobile Number</div>
	</div>
	<div class="pin-detail">
	    <p class="pin-title">PIN could not be sent to your mobile number</p>
	    <p>Please try resending the PIN</p>
	    <div class="clearFix"></div>
	    <a href="javascript:void(0);" class="orange-button"  onclick="showVerificationLayer('<?=$widget?>', <?=$OTPVerification?>, <?=$ODBVerification?>);" style="margin-top:10px; display: inline-block;">Resend PIN</a>
	</div>
    </div>
</div>

<script>
    document.onkeydown=function(event) {
	if($('user-Authentication').style.display !== 'none' && event.keyCode == '13') {
	    verifyUserAuthentication('<?=$widget?>', <?=$OTPVerification?>, <?=$ODBVerification?>);
	}
    }
</script>
<?php
}
else if($ODBVerification) {
?>
<div id="user-Authentication" <?php echo $displayVerify; ?>>
    <div class="blkRound">
	<div class="layer-title">
	    <div class="title">Verify Mobile Number</div>
	</div>
	<div class="pin-detail">
	    <p class="pin-title">We are calling you</p>
	    <div>
	    <div class="clearFix"></div>
	    </div>
	    <div id="pin-Error" class="errorMsg" style="margin-bottom:8px; float:left; width:100%;"></div>
	    <a href="javascript:void(0);" class="orange-button" onclick="verifyUserAuthentication('<?=$widget?>', <?=$OTPVerification?>, <?=$ODBVerification?>);" style="margin-top:10px;">Submit</a>
	</div>
    </div>
</div>
<div id="send-Failed" <?php echo $displayFail; ?>>
    <div class="blkRound">
	<div class="layer-title">
	    <div class="title">Verify Mobile Number</div>
	</div>
	<div class="pin-detail">
	    <p class="pin-title">We are calling you again</p>
	    <div class="clearFix"></div>
	</div>
    </div>
</div>
<?php } ?>