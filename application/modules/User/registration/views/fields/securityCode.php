<?php if(!($formData['userId'] || isCaptchaFreeReferer($_SERVER['HTTP_REFERER']) || $_REQUEST['shkNoCaptchafrAutomation'] == '1')) { ?>
<li id="securityCode_block_<?php echo $regFormId; ?>" visible="Yes" <?php if($context == 'twoStepRegister' ||$context == 'oneStepRegister' ||$context == 'downloadEbrochureSA') { echo 'style="margin-top:15px;"'; } ?>>
    <?php if($mmpFormId) echo "<div style='margin-left:192px;'>"; ?>
    <?php if($context == 'twoStepRegister' || $context == 'oneStepRegister' ||$context == 'downloadEbrochureSA') {?>
    Type in the characters you see 
    <?php }else{ ?>
    Type in the characters you see in the picture below
    <?php } ?>
    <div style="margin-top:<?php if($mmpFormId) echo "5px"; else echo "10px"; ?>;">
        <img align="absbottom" <?php if($context == 'twoStepRegister' || $context == 'oneStepRegister' ||$context == 'downloadEbrochureSA') echo 'class="flLt"'; ?> src="/CaptchaControl/showCaptcha?width=100&amp;height=34&amp;characters=5&amp;randomkey=<?php echo rand(); ?>&amp;secvariable=registrationCaptcha_<?php echo $regFormId; ?>" width="100" height="34" id="securityCodeImg_<?php echo $regFormId; ?>" />
        <input type="text" class="register-fields<?php if($mmpFormId) echo "2"; ?> <?php if($context == 'twoStepRegister' || $context == 'oneStepRegister' ||$context == 'downloadEbrochureSA') echo "universal-text"; else if($context == 'mobileRegistrationAbroad') echo "universal-txt";?>" style="margin-left:9px;width:<?php if($mmpFormId) echo "115px"; else echo "120px"; ?>;<?=($context == 'mobileRegistrationAbroad'?"vertical-align:top;":"")?>" name="securityCode" id="securityCode_<?php echo $regFormId; ?>" regFieldId="securityCode" maxlength="5" minlength="5" mandatory="1" label="Security Code" caption="the Security Code as shown in the image" default="" value="" />
        <input type="hidden" name="securityCodeVar" id="securityCodeVar_<?php echo $regFormId; ?>" value="registrationCaptcha_<?php echo $regFormId; ?>" />
        <div class="clearfix"></div>
        <div>
            <div <?php if($context == 'twoStepRegister' || $context == 'oneStepRegister' ||$context == 'downloadEbrochureSA' || $context == 'downloadEbrochureSABottom') { echo 'class="errorMsg"'; } else if($context == 'mobileRegistrationAbroad') {echo 'class="errorMsg error-msg"';} else { echo 'class="regErrorMsg"'; }  ?>  id="securityCode_error_<?php echo $regFormId; ?>"></div>
        </div>
    </div>
    <?php if($mmpFormId) echo "</div>"; ?>
</li>
<?php } ?>
