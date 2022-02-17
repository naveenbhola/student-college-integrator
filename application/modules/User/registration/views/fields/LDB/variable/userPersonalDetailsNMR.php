<?php 
$preFilledObj = $customFormData['customFields'];
?>

<li <?php if($preFilledObj['email']['hidden'] == "1"){ echo $registrationHelper->getBlockCustomAttributes('email', '', array('style'=>'display:none')); }else{ echo $registrationHelper->getBlockCustomAttributes('email'); } ?> >
    <input type="text" caption="your email address" label="email" mandatory="1" default="Email" maxlength="125" class="NewReg-field registerEmailIdField <?php if(!empty($formData['email'])){ echo 'focusColor'; } ?>" value="<?php if(!empty($formData['email'])){ echo $formData['email']; }else{ echo 'Email'; } ?>" regfieldid="email" id="email_<?php echo $regFormId; ?>" name="email" <?php if($preFilledObj['email']['disabled'] == "1"){ echo 'readonly'; } ?> >
    <div>
        <div class="errorMsg" id="email_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<li <?php if($preFilledObj['isdCode']['hidden'] == "1"){ echo $registrationHelper->getBlockCustomAttributes('isdCode', '', array('style'=>'display:none')); }else{ echo $registrationHelper->getBlockCustomAttributes('isdCode'); } ?> >
    <?php 
    if(!empty($formData['isdCode'])){ 
        $formData['isdCode'] =  $formData['isdCode']; 
    }else{
        $formData['isdCode'] = '91-2';
    }
    $ISDCodeValues = $fields['isdCode']->getValues(); ?>
    <div class="reg-dropdwn ssLayer" id="isdCode_<?php echo $regFormId; ?>_input" searchRequired="1">
        <span class="textHolder" style="color:#111111"><?php echo $ISDCodeValues[$formData['isdCode']];?></span>
        <i class="rightPointer"></i>
    </div>
    <div style="display:none" class="select-Class">
        <select style="display:none" name="isdCode" id="isdCode_<?php echo $regFormId; ?>" onchange="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].changeDesiredCourse();" regFieldId="isdCode" minlength="2" maxlength="4" value="ISD Code" default="ISD Code" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('isdCode'); ?>>
            <?php foreach($ISDCodeValues as $key=>$value){ ?>
            <option value="<?php echo $key; ?>" <?php if($formData['isdCode'] == $key){ echo 'selected';} ?>> <?php echo $value; ?> </option>
            <?php } ?>
        </select>
    </div>
    <div>
        <div class="errorMsg" id="isdCode_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<li <?php if($preFilledObj['mobile']['hidden'] == "1"){ echo $registrationHelper->getBlockCustomAttributes('mobile', '', array('style'=>'display:none')); }else{ echo $registrationHelper->getBlockCustomAttributes('mobile'); } ?> >
    <input type="number" name="mobile" id="mobile_<?php echo $regFormId; ?>" regFieldId="mobile" class="NewReg-field <?php if(!empty($formData['mobile'])){ echo 'focusColor'; } else { echo 'mobilefocusColor';} ?>" maxlength="10" value="<?php if(!empty($formData['mobile'])){ echo $formData['mobile']; } ?>" minlength="10" default="Mobile Number" placeholder="Mobile Number" <?php echo $registrationHelper->getFieldCustomAttributes('mobile'); ?> <?php if($preFilledObj['mobile']['disabled'] == "1"){ echo 'readonly'; } ?> />
    <div>
        <div class="errorMsg" id="mobile_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<li <?php if($preFilledObj['firstName']['hidden'] == "1"){ echo $registrationHelper->getBlockCustomAttributes('firstName', '', array('style'=>'display:none')); }else{ echo $registrationHelper->getBlockCustomAttributes('firstName'); } ?> >
    <input type="text" name="firstName" id="firstName_<?php echo $regFormId; ?>" regFieldId="firstName" class="NewReg-field <?php if(!empty($formData['firstName'])){ echo 'focusColor'; } ?>" value="<?php if(!empty($formData['firstName'])){ echo $formData['firstName']; }else{ echo 'First Name'; } ?>" minlength="1" maxlength="50" default="First Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('firstName'); ?> <?php if($preFilledObj['firstName']['disabled'] == "1"){ echo 'readonly'; } ?> />
    <div>
        <div class="errorMsg" id="firstName_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<li <?php if($preFilledObj['lastName']['hidden'] == "1"){ echo $registrationHelper->getBlockCustomAttributes('lastName', '', array('style'=>'display:none')); }else{ echo $registrationHelper->getBlockCustomAttributes('lastName'); } ?> >
    <input type="text" name="lastName" id="lastName_<?php echo $regFormId; ?>" regFieldId="lastName" class="NewReg-field <?php if(!empty($formData['lastName'])){ echo 'focusColor'; } ?>" value="<?php if(!empty($formData['lastName'])){ echo $formData['lastName']; }else{ echo 'Last Name'; } ?>" minlength="1" maxlength="50" default="Last Name" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('lastName'); ?> <?php if($preFilledObj['lastName']['disabled'] == "1"){ echo 'readonly'; } ?> />
    <div>
        <div class="errorMsg" id="lastName_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>