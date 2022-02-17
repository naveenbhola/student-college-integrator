<?php
if(isset($fields['specialization']) && !empty($fields['specialization'])){
    $specializations = $fields['specialization']->getValues(array('desiredCourse' => $desiredCourse));
    if(is_array($specializations) && count($specializations) > 0) {
    ?>
    <li <?php echo $registrationHelper->getBlockCustomAttributes('specialization'); ?>>
        <label>Desired Specializations:</label>
        <div style="height:93px; overflow-y: auto; background:#f7f7f7; border:1px solid #ccc; padding:5px; font-size:12px">
        <?php foreach($specializations as $specializationValue => $specializationText) { ?>
            <input type="checkbox" name="specialization[]" class="specialization__<?php echo $regFormId; ?>" id="specialization_<?php echo $specializationValue; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('specialization'); ?>  value="<?php echo $specializationValue; ?>"> <?php echo $specializationText; ?> <br />
        <?php } ?>
        </div>
        <div>
            <div class="regErrorMsg" id="specialization_error_<?php echo $regFormId; ?>"></div>
        </div>
    </li>
    <?php
    }
}
?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('password'); ?>>
    <input type="text" id="passwordText_<?php echo $regFormId; ?>" regFieldId="passwordText" class="register-fields" value="New Password" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" />
    <input type="password" name="password" id="password_<?php echo $regFormId; ?>" class="register-fields" maxlength="25" <?php echo $registrationHelper->getFieldCustomAttributes('password'); ?> style="display: none;" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
    <div>
        <div class="regErrorMsg" id="password_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>

<li <?php echo $registrationHelper->getBlockCustomAttributes('confirmPassword'); ?>>
    <input type="text" id="confirmPasswordText_<?php echo $regFormId; ?>" regFieldId="confirmPasswordText" class="register-fields" value="Confirm Password" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" />
    <input type="password" name="confirmPassword" id="confirmPassword_<?php echo $regFormId; ?>" class="register-fields" maxlength="25" <?php echo $registrationHelper->getFieldCustomAttributes('confirmPassword'); ?> style="display:none;" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
    <div>
        <div class="regErrorMsg" id="confirmPassword_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>

<?php if(isset($fields['age']) && !empty($fields['age'])){ ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('age','float:left !important; width:70px;'); ?>>
    <input type="text" name="age" id="age_<?php echo $regFormId; ?>" class="register-fields" maxlength="2" value="Age" default="Age" <?php echo $registrationHelper->getFieldCustomAttributes('age'); ?> style="width:50px;" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);" />
</li>
<?php } ?>

<?php if(isset($fields['gender']) && !empty($fields['gender'])){ ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('gender','float:left !important; width:200px; padding-top:8px; clear:none;'); ?>>
    <label style="float:left; width:50px;">Gender:</label>
	<?php foreach($fields['gender']->getValues() as $genderValue => $genderText) { ?>
        <input type="radio" name="gender" class="gender__<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('gender'); ?>  value="<?php echo $genderValue; ?>" /> <?php echo $genderText; ?>
    <?php } ?>
	<div>
        <div class="regErrorMsg" id="gender_error_<?php echo $regFormId; ?>"></div>
    </div>  
</li>
<?php } ?>
<li style="margin:0 !important; padding:0 !important">
    <div style="padding-bottom: 10px; display:none;">
        <div class="regErrorMsg" id="age_error_<?php echo $regFormId; ?>"></div>
    </div>    
</li>
<?php if(isset($fields['xiiStream']) && !empty($fields['xiiStream'])){ ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('xiiStream'); ?>>
    <label>Std. XII Stream:</label>
    <div>
    <?php foreach($fields['xiiStream']->getValues() as $xiiStreamValue) { ?>
        <input type="radio" name="xiiStream" class="xiiStream_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiStream'); ?>  value="<?php echo $xiiStreamValue; ?>" /> <?php echo $xiiStreamValue; ?> &nbsp;
    <?php } ?>
    </div>
    <div>
        <div id= "xiiStream_error_<?php echo $regFormId; ?>" class="regErrorMsg"></div>
    </div>
</li>
<?php } ?>
<?php if(isset($fields['xiiYear']) && !empty($fields['xiiYear'])){ ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('xiiYear'); ?>>
	<label>Std. XII Details:</label>
    <div style="float:left; width:90px;">
    <select name="xiiYear" id="xiiYear_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiYear'); ?> style="width:80px !important;">
        <option value="">Year</option>
        <?php foreach($fields['xiiYear']->getValues() as $xiiYear) { ?>
            <option value='<?php echo $xiiYear; ?>'><?php echo $xiiYear; ?></option>
        <?php } ?>	
    </select>
    </div>
    
    <div style="float:left; width:90px;">
        <select name="xiiMarks" id="xiiMarks_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('xiiMarks'); ?> style="width:80px !important;">
            <option value="">Marks</option>
            <?php foreach($fields['xiiMarks']->getValues() as $xiiMarks) { ?>
                <option value='<?php echo $xiiMarks; ?>'><?php echo $xiiMarks; ?></option>
            <?php } ?>	
        </select>
    </div>
    <div class="clearFix"></div>
    <div>
        <div class="regErrorMsg" id="xiiYear_error_<?php echo $regFormId; ?>"></div>
    </div>
    <div>
        <div class="regErrorMsg" id="xiiMarks_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<?php } ?>

<li <?php echo $registrationHelper->getBlockCustomAttributes('otherDetails'); ?>>
    <label>Other Details:</label>
    <textarea name="otherDetails" id="otherDetails_<?php echo $regFormId; ?>" rows="2" class="register-fields" profanity="1" <?php echo $registrationHelper->getFieldCustomAttributes('otherDetails'); ?> default="Specify any other detail about your institute and course preference." style="height:40px;" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this); shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].validateField(this);">Specify any other detail about your institute and course preference.</textarea>
    <div>
        <div class="regErrorMsg" id="otherDetails_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>

<?php if(isset($fields['otherExams']) && !empty($fields['otherExams'])){ ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('otherExams','background:#f6f6f6; padding:10px;'); ?>>
    <label>Other Exams Taken:</label>
    
    <?php for($i=1;$i<=5;$i++) { ?>
        <div id="otherExam<?php echo $i; ?>_block_<?php echo $regFormId; ?>" style="margin-bottom: 5px; <?php if($i>1) echo "display:none;"; ?>">
            <select name="otherExams[]" class="otherExams_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('otherExams'); ?> style="width:150px !important;">
                <option value="">Exam</option>
                <?php foreach($fields['otherExams']->getValues() as $examType => $exams) { ?>
                    <optgroup label="<?php echo $examType; ?>">
                    <?php foreach($exams as $exam) { ?>    
                        <option value='<?php echo $exam; ?>'><?php echo $exam; ?></option>
                    <?php } ?>    
                    </optgroup>
                <?php } ?>	
            </select>
            &nbsp;
            <input type="text" name="otherExamScore[]" class="register-fields" value="Score" default="Score" id="otherExamScore<?php echo $i; ?>_<?php echo $regFormId; ?>" regFieldId="otherExamScore<?php echo $i; ?>" style="width:120px;" maxlength="10" onfocus="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].removeHelpText(this)" onblur="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].restoreHelpText(this);" />
        </div>
    <?php } ?>
    <a href="#" id="addExam_<?php echo $regFormId; ?>" style="font-size:11px;" onclick="shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].addExam(this); return false">Add More Exams</a>
</li>
<?php } ?>

<?php if(isset($fields['privacySettings']) && !empty($fields['privacySettings'])){ ?>
<li <?php echo $registrationHelper->getBlockCustomAttributes('privacySettings'); ?>>
    <label>Privacy Setting:</label>
    <div style="font-size:12px">
    <input type="checkbox" name="privacySettings[]" class="privacySettings_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('privacySettings'); ?>  value="viaEmail" checked="checked" /> Contact me via email for educational products <br />
    <input type="checkbox" name="privacySettings[]" class="privacySettings_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('privacySettings'); ?>  value="viaMobile" checked="checked" /> Contact me on mobile for educational products <br />
    <input type="checkbox" name="privacySettings[]" class="privacySettings_<?php echo $regFormId; ?>" <?php echo $registrationHelper->getFieldCustomAttributes('privacySettings'); ?>  value="newsletter" checked="checked" /> Send me Shiksha newsletter email <br />
    </div>
    <div>
        <div class="regErrorMsg" id="privacySettings_error_<?php echo $regFormId; ?>"></div>
    </div>
</li>
<?php } ?>