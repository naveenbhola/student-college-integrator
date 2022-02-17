<?php 
if(!(isset($_COOKIE['user']) && !empty($_COOKIE['user'])) && !empty($mmpData))
{
?>
	<script>
	var destination_url = '<?php echo $mmpData['mmp_details']['destination_url']; ?>';
	var mmp_form_id_on_popup = '<?php echo $mmpData['mmp_details']['page_id'];?>';
	var mmp_page_type = '<?php echo $mmpData['mmp_details']['display_on_page'];?>';

	if(mmp_form_id_on_popup != '') {
		var customFields = {'mmpFormId':mmp_form_id_on_popup};
		var formData = {
			'trackingKeyId' : '<?php echo $mmpData['trackingKeyId'];?>',
			'customFields':customFields,
			'callbackFunction':'registrationFromMMPCallback',
			'submitButtonText':'<?php echo $mmpData['submitButtonText'];?>',
			'httpReferer':'',
			'formHelpText':<?php echo $mmpData['customHelpText'];?>
		};

		//registrationForm.showRegistrationForm(formData);

		function registrationFromMMPCallback() {
			if(destination_url != '') {
				window.location = destination_url;
			}  else {
				window.location.reload();
			}
		}
	}
	</script>
<?php 
}
?>
