<?php
	$footerComponents = array(
			    'js'                => array('studyAbroadSignup','studyAbroadCommon'),
				//'asyncJs'           => array('jquery.royalslider.min','jquery.tinycarouselV2.min'),
				'hideHTML'			=> "true"
			);
	$this->load->view('common/studyAbroadFooter',$footerComponents);
?>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script type="text/javascript">
 $j(window).on('load',function() {
    abroadSignupFormObj = {};
    abroadSignupFormObj = new AbroadSignupForm();
    abroadSignupFormObj.initializeOnblurValidation();
    abroadSignupPageObj = {};
    abroadSignupPageObj = new AbroadSignupPage();
    abroadSignupPageObj.setCurrentSchoolList("<?php echo base64_encode(json_encode(array_map(function($a){return str_replace( '\\','',$a); }, $fields['currentSchool']->getValues()))); ?>");
    abroadSignupPageObj.initializeRegistrationPage();
 });
</script>