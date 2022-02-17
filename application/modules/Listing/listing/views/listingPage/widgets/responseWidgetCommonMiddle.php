<?php
if($validateuser != "false"){
	$firstname = $validateuser[0]['firstname'];
	$lastname = $validateuser[0]['lastname'];
	$mobile = $validateuser[0]['mobile'];
	$cookiestr = $validateuser[0]['cookiestr'];
}
?>
<li>
	<div class="flLt" style="width:230px; padding:0 15px 12px 0;">
		<input class="universal-txt-field" value="<?php echo $firstname?htmlentities($firstname):"";?>" id="usr_first_name_<?=$widget?>"  tip="multipleapply_name" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_first_name_<?=$widget?>"  customplaceholder="Your First Name"/>
		<div class="clearFix"></div>
		<div style="display:none"><div class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
	</div>
	<div class="flLt" style="width:230px; padding:0 15px 12px 0">
		<input class="universal-txt-field" value="<?php echo $lastname?htmlentities($lastname):"";?>" id="usr_last_name_<?=$widget?>"  tip="multipleapply_name" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_last_name_<?=$widget?>"  customplaceholder="Your Last Name"/>
		<div class="clearFix"></div>
		<div style="display:none"><div class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
	</div>
</li>

<li>
	<div class="flLt" style="width:230px; padding:0 15px 12px 0">
		<input class="universal-txt-field"  value="<?php echo $mobile?$mobile:"";?>" profanity="true" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone"  customplaceholder="Mobile" />
		<div class="clearFix"></div>
		<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="mobile_phone_<?=$widget?>_error"></div></div>
	</div>
	<div class="flLt" style="width:230px; padding:0 15px 12px 0">
		<input class="universal-txt-field" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "";} ?>" id="contact_email_<?=$widget?>"   profanity="true" required="true"  type="text" validate="validateEmail"  maxlength="100" minlength="10" caption="email"   customplaceholder="Email" />
		<div class="clearFix"></div>
		<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="contact_email_<?=$widget?>_error"></div></div>
	</div>
</li>

<li>
	<div class="flLt" style="width:230px; padding:0 15px 12px 0">
		<select class="universal-select" id="selected_course_<?=$widget?>" validation="false"  onchange="reloadWidget('<?=$widget?>')">
			<?php
					if($_REQUEST['city']){
						$request = new CategoryPageRequest();
						$request->setData(array(
											'cityId' => $_REQUEST['city'],
											'localityId' => $_REQUEST['locality']
											));
					}
					//$localityArray = array();
				foreach($courses as $c){
					if($course && ($course->getId() == $c->getId())){
						$selected = "selected";
					}else{
						$selected = "";
					}
					echo '<option '.$selected.' value="'.$c->getId().'">'.html_escape($c->getName()).'</option>';
					$c->setCurrentLocations($request,true);
					//_p($c->getCurrentLocations());
					//die("");
					//$localityArray[$c->getId()] = getLocationsCityWise($c->getCurrentLocations());
				}
			?>
		</select>
		<div class="clearFix"></div>
		<div style="display:none"><div class="errorMsg" id="selected_course_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
		
		<script>
			var localityArray = <?=json_encode($localityArray)?>;
			$j.each(localityArray,function(index,element){
				custom_localities[index] = element;
			});
			activatecustomplaceholder();
			addOnBlurValidate($('form_<?=$widget?>'));
		</script>
	</div>
	
	<?php
	if($studyAbroad) {
		echo Modules::run('MultipleApply/MultipleApply/getExtraFieldsForStudyAbroadResponseForm',ucfirst($widget),$widget);
	}
	?>
</li>
<li id="locality-div_<?=$widget?>" class="localityDiv"></li>