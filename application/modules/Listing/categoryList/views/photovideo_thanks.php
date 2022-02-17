<h5>Thank you for showing interest.This institute will get in touch with you shortly. </h5>
<script>
function showAbroadForm() {
	shikshaUserRegistration.setCallback(function() {window.location.reload(true);});
        shikshaUserRegistration.showUnifiedLayer({'studyAbroad' : 1});
}
if('<?php echo $user_login_is_the; ?>' == 'YES' && '<?php echo $type; ?>' == 'Photo') {
	$('photo-view-cont').style.height = "415px";
}
if(unified_registration_is_ldb_user == 'false') {
	if ($('video_unified') != null) {
		$('video_unified').style.display = 'block';
	}
}
</script>
