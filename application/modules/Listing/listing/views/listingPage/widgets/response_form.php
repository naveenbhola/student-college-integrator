<?php
if($validateuser != "false"){
	$userId = $validateuser[0]['userid'];
	$firstname = $validateuser[0]['firstname'];
	$lastname = $validateuser[0]['lastname'];
	$mobile = $validateuser[0]['mobile'];
	$cookiestr = $validateuser[0]['cookiestr'];
	$captcha_flag = false;
}
else
{
	$captcha_flag = true;
}
?>
<!--START: 1st Layer-->
<div class="contact-layer">
	<div class="layer-head">
        <p class="contact-title flLt">Get Contact Details on Email/SMS</p>
        <span class="icon-close flRt" uniqueattr="ListingPage/hideLCDForm" onclick="hideOverlayAnA(true); $j('#Button_2').show();" title="Close"></span>
    </div>
	<form id="form_<?=$widget?>" novalidate>
		<input type="hidden" id="listingTypeInfo"></input>
		<ul class="contact-form">
			<li>
				<div class="form-col flLt">
					<input class="universal-txt-field" value="<?php echo $firstname ? htmlentities($firstname) : ''; ?>" id="usr_first_name_<?=$widget?>"  caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1"  type="text" name="usr_first_name_<?=$widget?>" customplaceholder = "Your First Name" default="" onblur="checkTextElementOnTransition(this,'blur'); showErrorMessage(this, '<?=$widget?>');" onfocus="checkTextElementOnTransition(this,'focus')"/>
					<div class="clearFix"></div>
					<div style="display:none">
						<div class="errorMsg" id="usr_first_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div>
					</div>
				</div>
				
				<div class="form-col flLt">
					<input class="universal-txt-field" value="<?php echo $lastname ? htmlentities($lastname) : ''; ?>" id="usr_last_name_<?=$widget?>"  caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" type="text" name="usr_last_name_<?=$widget?>" customplaceholder="Your Last Name" default="" onblur="checkTextElementOnTransition(this,'blur'); showErrorMessage(this, '<?=$widget?>');" onfocus="checkTextElementOnTransition(this,'focus')"/>
					<div class="clearFix"></div>
					<div style="display:none"><div class="errorMsg" id="usr_last_name_<?=$widget?>_error" style="padding-left:3px; clear:both; display:block"></div></div>
				</div>
			</li>
			
			<li>
				<div class="form-col flLt">
					<input class="universal-txt-field"  value="<?php echo $mobile ? $mobile : ''; ?>" id="mobile_phone_<?=$widget?>" type="text" name="mobile_phone_<?=$widget?>" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" caption="mobile phone" customplaceholder="Mobile"  default="" onblur="checkTextElementOnTransition(this,'blur'); showErrorMessage(this, '<?=$widget?>');" onfocus="checkTextElementOnTransition(this,'focus')"/>
					<div class="clearFix"></div>
					<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="mobile_phone_<?=$widget?>_error"></div></div>
				</div>
				
				<div class="form-col flLt">
					<input class="universal-txt-field" value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "";} ?>" id="contact_email_<?=$widget?>" required="true"  type="text" name="contact_email_<?=$widget?>" validate="validateEmail"  maxlength="100" minlength="10" caption="email"  customplaceholder="Email" default="" onblur="checkTextElementOnTransition(this,'blur'); showErrorMessage(this, '<?=$widget?>');" onfocus="checkTextElementOnTransition(this,'focus');"/>
					<div class="clearFix"></div>
					<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="contact_email_<?=$widget?>_error"></div></div>
				</div>
			</li>
			
			<li>
				<div class="form-col flLt">
					<select class="universal-select" caption="course" name="selected_course_<?=$widget?>" required = "1" id="selected_course_<?=$widget?>" validate="validateSelect"  unrestricted="true" onchange="reloadWidget('<?=$widget?>'); hideError('<?=$widget?>');" onblur="validateDropDown(this); return false;">
						<?php
						if($_REQUEST['city'])
						{
							$request = new CategoryPageRequest();
							$request->setData(array(
													'cityId' => $_REQUEST['city'],
													'localityId' => $_REQUEST['locality']
													));
						}
						//$localityArray = array();
						$tempPaidCourses = array();
						$tempFreeCourses = array();
						$atleastOneCoursePaid = false;
						
						echo '<option selected value="">Please Select course</option>';
						foreach($courses as $c)
						{
							if($c->isPaid()){
								$tempPaidCourses[] =  $c->getId();
							} else {
								$tempFreeCourses[] =  $c->getId();
							}
							//if($course && ($course->getId() == $c->getId()))
							//{
							//	$selected = "";
							//}
							//else
							//{
							//	$selected = "";
							//}
							echo '<option value="'.$c->getId().'">'.html_escape($c->getName()).'</option>';
							$c->setCurrentLocations($request,true);
							//$localityArray[$c->getId()] = getLocationsCityWise($c->getCurrentLocations());
						} ?>
					</select>
					<div class="clearFix"></div>
					<div style="display:none">
						<div class="errorMsg" id="selected_course_<?=$widget?>_error" style="padding-left:3px; clear:both;"></div>
					</div>
					
					<script>
						var localityArray = <?=json_encode($localityArray)?>;
						var contactDetailsPaidCourses = <?=json_encode($tempPaidCourses)?>;
						var contactDetailsFreeCourses = <?=json_encode($tempFreeCourses)?>;
						
						$j.each(localityArray,function(index,element){
														custom_localities[index] = element;
						});
						//activatecustomplaceholder();
						//addOnBlurValidate($('form_<?=$widget?>'));
					</script>
				</div>
			</li>
			<li id="locality-div_<?=$widget?>" class="localityDiv" style="display: none"></li>
			<?php if($captcha_flag)
			{ ?>
				<li>
					<p>Type in the characters you see in the picture below</p>
					<div class="clearFix spacer10"></div>
						<div>
							<img class="vam" align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>" width="100" height="34"  id = "secureCode_<?=$widget?>"/>
							<input type="text"  style="width:100px" class="universal-txt-field" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" onblur = "showErrorMessage(this, '<?=$widget?>');"/>
							<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div></div>
						</div>
				</li>
			<?php } ?>
			<li>
				<button class="orange-button" onclick="processResponseForm('<?=$widget?>', 'onSubmitResponseFormContactDetails'); return false;">Submit <span class="btn-arrow"></span></button>
			</li>
		</ul>
		<input type="hidden" value="<?=$institute->getId()?>" id="institute_id_<?=$widget?>">
		<input type="hidden" value="<?=html_escape($institute->getName())?>" id="institute_name_<?=$widget?>">
	</form>
    <div class="clearFix"></div>
</div>

<script>
	function hideError(widget)
	{
		$j('#selected_course_'+widget+'_error').hide();
	}
	function validateDropDown(obj)
	{
		message = validateSelect(obj.value, obj.getAttribute('caption'), obj.getAttribute('maxlength'), obj.getAttribute('minlength'));
		var errorId = obj.getAttribute('name') + '_error';
		if (message != true)
		{
			$(errorId).innerHTML = message;
			$j('#' + errorId).parent().show();
			$j('#' + errorId).show();
		}
		else
			$j('#' + errorId).parent().hide();
		return false;
	}
	
	function showErrorMessage(obj, widget)
	{
		if (obj.getAttribute('name') == ('usr_first_name_' + widget) || obj.getAttribute('name') == ('usr_last_name_' + widget))
		{
			message = validateDisplayName(obj.value, obj.getAttribute('caption'), obj.getAttribute('maxlength'), obj.getAttribute('minlength'));
		}
		if (obj.getAttribute('name') == ('mobile_phone_' + widget))
		{
			message = validateMobileInteger(obj.value, obj.getAttribute('caption'), obj.getAttribute('maxlength'), obj.getAttribute('minlength'), obj.getAttribute('required'));
		}
		if (obj.getAttribute('name') == ('contact_email_' + widget))
		{
			message = validateEmail(obj.value, obj.getAttribute('caption'), obj.getAttribute('maxlength'), obj.getAttribute('minlength'));
		}
		if (obj.getAttribute('name') == ('homesecurityCode_' + widget))
		{
			message = validateSecurityCode(obj.value, obj.getAttribute('caption'), obj.getAttribute('maxlength'), obj.getAttribute('minlength'));
			if (message != true)
			{
				if(obj.value.length == 5)
				{
					message = true;
				}
			}
		}
		if (obj.getAttribute('name') == ('contact_email_' + widget))
		{
			message = validateEmail(obj.value, obj.getAttribute('caption'), obj.getAttribute('maxlength'), obj.getAttribute('minlength'));
		}
		
		var errorId = obj.getAttribute('name') + '_error';
		if (message != true)
		{
			$(errorId).innerHTML = message;
			$j('#' + errorId).parent().show();
		}
		else
			$j('#' + errorId).parent().hide();
		return false;
	}
	var userId = "<?=$userId?>";
</script>
