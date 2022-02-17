<?php
$widget = "common";
$thisCI = &get_instance();
$validateuser = $thisCI->checkUserValidation();
if($validateuser != "false"){
	$displayForm = "none";
	$firstname = $validateuser[0]['firstname'];
	$lastname = $validateuser[0]['lastname'];
	$mobile = $validateuser[0]['mobile'];
	$cookiestr = $validateuser[0]['cookiestr'];
	$loggedIn = "true";
}else{
	$displayForm = "";
	$loggedIn = "false";
}

$instituteId 	= (int) $this->input->post('instituteId');
$isStudyAbroad 	= (int) $this->input->post('studyAbroad');
// get the widget name and course name picklist data for institute pages 
$widgetName 	= $this->input->post('widgetName');
$listData 	= $this->input->post('listData');
$listData 	= unserialize(base64_decode($listData));

if( !empty($listData) ){
	$displayForm = "";
}

$studyAbroadResponse = FALSE;
if($isStudyAbroad) {
	$studyAbroadResponse = TRUE;
}
else if($instituteId) {
	$this->load->builder('ListingBuilder','listing');
	$listingBuilder = new ListingBuilder;
	$instituteService = $listingBuilder->getInstituteService();
	$studyAbroadResponse = $instituteService->isInstituteStudyAbroad($instituteId);
}
?>

<div style="width:400px;margin: 0pt auto;display:<?=$displayForm?>;" id="mainApplyDiv">

<?php
	$data = array();
	$data['widget'] = $widget;
	$this->load->view('common/userAuthentication', $data);
?>

<form id="form_<?=$widget?>" onsubmit="authenticateUser(); return false;" novalidate>
	<div class="blkRound">
		<div class="layer-title">
            	<a title="Close" href="javascript:void(0);" class="flRt close" onclick="$('DHTMLSuite_modalBox_transparentDiv').style.display = 'none';closeMessage();"></a>
                <div class="title" id="formTitle">Register here</div>
		<?php if($validateuser == "false"){ ?>
				<div style="float:right;margin:10px 0;"><a href="javascript:void(0);" onclick="shikshaUserRegistration.closeRegisterFreeLayer(); shikshaUserRegistration.showLoginLayer(); return false;">Existing Users, Sign In</a></div>
		<?php } ?>

        	</div>
		<div class="whtRound">
            
			<ul>
				<li style="margin-bottom:15px">
				<div>
					<input class="universal-txt-field" style="width:300px" value="<?php echo $firstname?htmlentities($firstname):"Your First Name";?>" id="usr_first_name_<?=$widget?>"  tip="multipleapply_name" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_first_name_<?=$widget?>" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your First Name"/>
					<div style="display:none"><div class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
				</div>
				</li>
				<li style="margin-bottom:15px">
				<div>
					<input class="universal-txt-field" style="width:300px" value="<?php echo $lastname?htmlentities($lastname):"Your Last Name";?>" id="usr_last_name_<?=$widget?>"  tip="multipleapply_name" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_last_name_<?=$widget?>" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Last Name"/>
					<div style="display:none"><div class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
				</div>
				</li>
				<li style="margin-bottom:15px">
				<div>
					<input class="universal-txt-field" style="width:300px"  value="<?php echo $mobile?$mobile:"Mobile";?>" profanity="true" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile" />
					<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="mobile_phone_<?=$widget?>_error"></div></div>
				</div>
				</li>
				<li style="margin-bottom:15px">
				<div>
					<input class="universal-txt-field" style="width:300px"  value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";} ?>" id="contact_email_<?=$widget?>" type="text" validate="validateEmail"  maxlength="100" minlength="10" caption="email"  blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Email" />
					<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="contact_email_<?=$widget?>_error"></div></div>
				</div>
				</li>
				
				<?php
                // show the list of courses for institute pages
				if( !empty($listData) )
				{
					$picklistData  = "";
					$picklistData .= "<li style='margin-bottom:15px'>";
					
					//$picklistData .= "<select id='selected_course_".$widget."' class='universal-select' style='width:312px;' onchange='reloadWidget(\"".$widget."\")'>";
					$picklistData .= '<select class="universal-select" caption="course" name="selected_course_'.$widget.'" id="selected_course_'.$widget.'" style="width:312px;" validate="validateSelect" onchange="reloadWidget(\''.$widget.'\'); national_listings_obj.hideError(\''.$widget.'\');" onblur="national_listings_obj.validateDropDown(this); return false;" required="true">';
					$picklistData .= "<option value=''>Please Select Course</option>";
					foreach( $listData as $courseid=>$coursename )
						$picklistData .= "<option value='".$courseid."'>".$coursename."</option>";
					$picklistData .= "</select>";
					$picklistData .= '<div class="clearFix"></div>';
					$picklistData .= '<div style="display: none">';
					$picklistData .= '<div class="errorMsg" style="padding-left: 3px; clear: both; display: block" id="selected_course_'.$widget.'_error"></div>';
					$picklistData .= '</div>';
					$picklistData .= "</li>";
					echo $picklistData;
				}
				
				?>
				<li  style='margin-bottom:15px;display:none;' class="layer-hidden-fields" id="locality-div_<?=$widget?>">
				</li>
				<?php
				if($studyAbroadResponse) {
					echo Modules::run('MultipleApply/MultipleApply/getExtraFieldsForStudyAbroadResponseForm','CommonResponseLayer',$widget);	
				}
				?>
				
				<?php if($validateuser == "false" && !($OTPVerification || $ODBVerification)){ ?>
				<li style="margin-bottom:15px">
					<p>Type in the characters you see in the picture below</p>
					<div class="clearFix spacer10"></div>
					<div>
						<img class="vam" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" width="100" height="34"  id = "secureCode_<?=$widget?>"/>
						<input type="text"  style="width:100px !important; display:<?=$displayForm?>;" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
						<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>
					</div>
				</li>
				<?php } ?>
				<li>
					<input type="submit" onclick="$j(this).parent().parent().parent().trigger('submit'); return false;" class="orange-button"  id="submit_<?=$widget?>" value="Register"/>
					<img src= "/public/images/loader_hpg.gif" style="display:none" align="absmiddle" id="loader"/>
					
				</li>
			</ul>
			<input type="hidden" value="0" id="institute_id_<?=$widget?>">
			<input type="hidden" value="0" id="institute_name_<?=$widget?>">
			<?php
			//  do not create this hidden element if course picklist data is available
			if( empty($listData) )
			{
			?>
			<input type="hidden" value="0" id="selected_course_<?=$widget?>">
			<?php
			}
			?>
		</div>
	</div>
	</form>
</div>
<?php if ($loggedIn == "true") { ?>
<script>
document.getElementById('contact_email_<?=$widget?>').setAttribute('readonly','readonly');
</script>
<?php } ?>
<script>
	var isListCourseDataEmpty = <?=(empty($listData)?1:0) ;?> ;
	addOnBlurValidate($('form_<?=$widget?>'));
	$j('#formTitle').html(shortRegistrationFormHeader);
	$j('#submit_<?=$widget?>').val(shortRegistrationFormButton);
	if(shortRegistrationFormResponse){
		$j('#institute_id_<?=$widget?>').val(shortRegistrationFormResponse['instituteId']);
		$j('#selected_course_<?=$widget?>').val(shortRegistrationFormResponse['courseId']);
		$j('#institute_name_<?=$widget?>').val(shortRegistrationFormResponse['instituteName']);
		addOnBlurValidate($('form_<?=$widget?>'));
		var tempHTML = addLocalityInApplyForm(shortRegistrationFormResponse['instituteId'],'<?=$widget?>',shortRegistrationFormResponse['courseId'],"new");
		$j('#locality-div_<?=$widget?>').html(tempHTML);
		$city = $j('#preferred_city_category_<?=$widget?>'+shortRegistrationFormResponse['instituteId']);
		$city.trigger('change');
	}
	isUserLoggedIn = <?=$loggedIn?>;
	<?php
	if (!empty($widget))
	{
	?>
		reloadWidget('<?=$widget?>');
	<?php
	}
	?>
	if(isUserLoggedIn && isListCourseDataEmpty){
		processOverlayForm('<?=$widget?>');
	}
</script>

<script>
	function authenticateUser() {
	    var OTPVerification = <?=$OTPVerification?>;
	    var ODBVerification = <?=$ODBVerification?>;
	    
	    if (OTPVerification || ODBVerification) {
		if(validateShortForm('<?=$widget?>', OTPVerification, ODBVerification)) {
			showVerificationLayer('<?=$widget?>', OTPVerification, ODBVerification);
		}
	    }
	    else {
		processData();
	    }
	}
	
        function processData() {			
		processOverlayForm('<?=$widget?>', null, <?=$OTPVerification?>, <?=$ODBVerification?>);
	}
	
</script>

<style type="text/css">
.layer-hidden-fields div.flLt{width:330px !important;}
.layer-hidden-fields .universal-select{width:311px !important; padding:5px !important}
</style>
