<?php
/**
 * If user is not logged in and it's a valid marketing mailer form
 */
if($logged == 'No' && $marketingFormRegistrationData['id']) {
    
/**
 * Redirection URLs
 * If specified in marketing form, use that URL
 * If not:
 *  In case of registration, use the URL computed for user
 *  Else redirect to home
 */
?>

<script>
	var mfRedirectURL = "<?php echo $marketingFormRegistrationData['formData']['redirectURL']; ?>";
	var homeURL = "<?php echo SHIKSHA_HOME; ?>";
	var preferredRedirectURL = mfRedirectURL ? mfRedirectURL : homeURL;
</script>    

<?php
/**
 * If initial processing failed (due to some validation)
 * throw registration form again
 */
if($marketingFormRegistrationData['status'] == 'failed') {
?>
<script>
	shikshaUserRegistration.showRegisterFreeLayer({"layerTitle": "The details entered by you are incorrect.","layerHeading": "Please fill in the form below to get started.", "hideLoginLink" : "1",  "callback" : function (registerResponse) {
        if (mfRedirectURL) {
            window.location.href = mfRedirectURL;
        }
        else if(registerResponse.redirectURL) {
            window.location.href = responseData.redirectURL;
        }
        else {
            window.location.href = homeURL;
        }
    }});
</script>
<?php } ?>

<?php
/**
 * If user trying to register already exists, show login layer
 */ 
if($marketingFormRegistrationData['status'] == 'user_exists') {
?>
<script>
	shikshaUserRegistration.setCallback(function() {
        window.location.href = preferredRedirectURL;
    });
    shikshaUserRegistration.showLoginLayer(false,'<?php echo $marketingFormRegistrationData['email']; ?>',true);
</script>
<?php } ?>

<?php
/**
 * If user data was ok, show OTP layer
 * If user fills OTP, do actual registration
 */ 
if($marketingFormRegistrationData['status'] == 'otp') {
    
    /**
     * Email & mobile must be present
     */ 
    if($marketingFormRegistrationData['email'] && $marketingFormRegistrationData['formData']['mobile']) {
?>

<script>
	var customVerificationData = {"email" : "<?php echo $marketingFormRegistrationData['email']; ?>", "mobile" : "<?php echo $marketingFormRegistrationData['formData']['mobile']; ?>", "callback" : "handleOTPResponse", "changeMobileLink" : "no"};
	shikshaUserRegistrationForm['otpvfx'] = new ShikshaUserRegistrationForm('otpvfx');
	shikshaUserRegistrationForm['otpvfx'].showVerificationLayer('true',customVerificationData);
	
	function handleOTPResponse(response)
	{
		response = response.response;
		if (response == 'failed') {
			window.location.href = preferredRedirectURL;
		}
		else if (response == 'exists') {
			shikshaUserRegistration.setCallback(function() {
				window.location.href = preferredRedirectURL;
			});
			shikshaUserRegistration.showLoginLayer(false,'<?php echo $marketingFormRegistrationData['email']; ?>',true);
		}
		else if (response == 'skip' || response == 'yes') {
			shikshaUserRegistration.registerMarketingFormUser('<?php echo $marketingFormRegistrationData['uniqId']; ?>',function(responseData) {
				responseData = eval("("+responseData+")");
				if (mfRedirectURL) {
					window.location.href = mfRedirectURL;
				}
				else if(responseData.redirectURL) {
					window.location.href = responseData.redirectURL;
				}
				else {
					window.location.href = homeURL;
				}
			});
		}
	}
</script>
<?php } ?>
<?php } ?>
<?php } ?>
