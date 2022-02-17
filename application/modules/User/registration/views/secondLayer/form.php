<div id="registration-box" style="width:300px; font-size:13px;">
<form action="/registration/Registration/updateUser" id="registrationForm_<?php echo $regFormId; ?>" novalidate="novalidate" method="post">
<ul>
<li>Login Email id:&nbsp; <?php echo $formData['email']; ?></li>

<?php $this->load->view('registration/fields/secondLayer/default'); ?>

<li>
    <input type="button" id="registrationSubmit_<?php echo $regFormId; ?>" value="Submit" class="orange-button" onclick="return shikshaUserRegistrationForm['<?php echo $regFormId; ?>'].submitForm()" />
</li>
<div class="clearFix"></div>
</ul>
<input type='hidden' id='regFormId' name='regFormId' value='<?php echo $regFormId; ?>' />
<input type="hidden" id="secondLayer" name="secondLayer" value="1" />
<input type="hidden" id="redirectURL" value="<?php echo $redirectURL; ?>" />
<?php
    $CI = & get_instance();
    $CI->load->library('security');
    $CI->security->setCSRFToken();
?>
<input type="hidden" id="shiksha_auth_token" name="<?php echo $CI->security->csrf_token_name;?>" value="<?php echo $CI->security->csrf_hash;?>" />
</form>
</div>
<div style="clear:both;"></div>
<?php $this->load->view('registration/common/jsInitialization'); ?>