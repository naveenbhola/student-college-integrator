<?php
if($validateuser != "false"){
	$firstname = $validateuser[0]['firstname'];
	$lastname = $validateuser[0]['lastname'];
	$mobile = $validateuser[0]['mobile'];
	$cookiestr = $validateuser[0]['cookiestr'];
}

if($courseType == "freeCourse" ) {
	$finalCoursesArray = $freeCourses;	
} else {
	$finalCoursesArray = $courses;	
}	
?>
<li><input class="universal-txt-field" value="<?php echo $firstname?htmlentities($firstname):"";?>" id="usr_first_name_<?=$widget?>" tip="multipleapply_name" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_first_name_<?=$widget?>" customplaceholder="Your First Name" />
	<div class="clearFix"></div>
	<div style="display: none">
		<div class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left: 3px; clear: both; display: block"></div>
	</div>
</li>
<li><input class="universal-txt-field" value="<?php echo $lastname?htmlentities($lastname):"";?>" id="usr_last_name_<?=$widget?>" tip="multipleapply_name" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_last_name_<?=$widget?>" customplaceholder="Your Last Name" />
	<div class="clearFix"></div>
	<div style="display: none">
		<div class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left: 3px; clear: both; display: block"></div>
	</div>
</li>

<li><input class="universal-txt-field" value="<?php echo $mobile?$mobile:"";?>" profanity="true" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" customplaceholder="Mobile" />
	<div class="clearFix"></div>
	<div style="display: none">
		<div class="errorMsg" style="padding-left: 3px; clear: both; display: block" id="mobile_phone_<?=$widget?>_error"></div>
	</div>
</li>
<li>
<input class="universal-txt-field" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "";} ?>" id="contact_email_<?=$widget?>" profanity="true" required="true" type="text" validate="validateEmail" maxlength="100" minlength="10" caption="email" customplaceholder="Email" />
	<div class="clearFix"></div>
	<div style="display: none">
		<div class="errorMsg" style="padding-left: 3px; clear: both; display: block" id="contact_email_<?=$widget?>_error"></div>
	</div>
</li>


<?php
if( $pageType == 'course')
{
?>
<li style="display:<?=($showBrochureListOnCoursePageFlag ? "" : "none")?>">
<select class="universal-select" id="selected_course_<?=$widget?>" caption="course" validation="true" onchange="reloadWidget('<?=$widget?>')" validate="validateSelect" required="true">
<?php
	echo '<option selected value="">Please Select Course</option>';
	foreach($courseListForBrochure as $courseid=>$coursename){
			if($course && ($course->getId() == $courseid)){
				$selected = "selected";
			}else{
				$selected = "";
			}
		echo '<option '.$selected.' value="'.$courseid.'">'.html_escape($coursename).'</option>';
	}

?>
</select>

<div class="clearFix"></div>
        <div style="display: none">
                <div class="errorMsg" id="selected_course_<?=$widget?>_error" style="padding-left: 3px; clear: both; display: block"></div>
        </div>
</li>

<?php
}
else if( $pageType == 'institute')
{
?>
	<li style="display: <?=($pageType == 'institute'?'':'none')?>;">
		<!-- <select class="universal-select" id="selected_course_<?=$widget?>" name="selected_course_<?=$widget?>" onchange=" national_listings_obj.validateDropDown(this); return false;" caption="Course" profanity="true" validation="validateSelect" required="true" > -->
		<select class="universal-select" caption="course" name="selected_course_<?=$widget?>" id="selected_course_<?=$widget?>" validate="validateSelect" onchange="reloadWidget('<?=$widget?>'); national_listings_obj.hideError('<?=$widget?>');" onblur="national_listings_obj.validateDropDown(this); return false;" required="true">
			<?php
				echo '<option selected value="">Please Select Course</option>';
				foreach($courseListForBrochure as $courseid=>$coursename){
						//if($course && ($course->getId() == $courseid)){
						//	$selected = "selected";
						//}else{
						//	$selected = "";
						//}
					echo '<option '.$selected.' value="'.$courseid.'">'.html_escape($coursename).'</option>';
				}
			?>
		</select>
		<div class="clearFix"></div>
		<div style="display: none">
			<div class="errorMsg" style="padding-left: 3px; clear: both; display: block" id="selected_course_<?=$widget?>_error"></div>
		</div>
	</li>
<?php
}
?>
<li id="locality-div_<?=$widget?>" style="margin-bottom: 0 !important;"></li>

<script>

var localityArray = <?=json_encode($localityArray)?>;
var course_reb_url = '<?php echo $course_reb_url;?>';
</script>