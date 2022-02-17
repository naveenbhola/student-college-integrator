<?php error_log("RHODES".$isSAComparePage);
$trackingBottomWidgetComparePage= " e-brochure-compare";
if($isSAComparePage==1){
		$trackingBottomWidgetComparePage="e-brochure-compare-abroad";
	}?>
<div id="thanks-box" style="display:<?php if($_COOKIE['compare_bottom_widget']){ echo 'block';}else{echo 'none';} ?>">
            	<h2>Thank you for showing your interest. You will be shortly contacted by these institute(s).</h2>
</div>
<form id="requestEbrochure_w" name="myForm_w" onSubmit="orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',true);validate_form_w(); return false;" novalidate>
<input type = "hidden" name = "resolution" id = "resolution_w" value = ""/>
<input type = "hidden" name = "coordinates" id = "coordinates_w" value = ""/>
<input type = "hidden" name = "referer" id = "referer_w" value = ""/>
<input type = "hidden" name = "loginproductname" id = "loginproductname_w" value = ""/>
<div id="course-box">
	<h2>I want to know more about these courses:
	<span style="display:none;color:#FF0000;font-weight:normal" id="flag_totalChecked_error_w">
		ERROR! Please select atleast one Institute to proceed
	</span>
    </h2>
	<ul>
		<?php
		$position = 0;
		//$localityArray = array();
		foreach($institutes as $i){
			$course = $i->getFlagshipCourse();
			$course->setCurrentLocations($request);
		?>
		<li class="<?=$liClass?>">
			<div id="ticklabel<?=$i->getId()?>" class="course-name" style="width:100%">
			<label style="width:90%">
				<span>
					<input id="reqEbrOverlay_w<?=$i->getId()?>" type="checkbox" checked value="<?=$i->getId()?>" name="overlay_w[]" onchange="hideShowLocalityLayer(<?=$i->getId()?>);" onclick="hideShowLocalityLayer(<?=$i->getId()?>);"/>
				</span>
				<b id="reqEbr_w_<?=$i->getId()?>" title="<?php echo htmlspecialchars($i->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?>"><?php echo htmlspecialchars($i->getName().", ".$course->getCurrentMainLocation()->getCity()->getName());?></b>
			</label>
		    <b class="course-fields" style="margin-left:20px;margin-top:10px;display:block;float:left;font-weight:normal">
				<select id="apply_w<?=$i->getId()?>" class="universal-select" style="width:300px">
			<?php	
				foreach($instituteList[$position]['courseList'] as $c){
					$c->setCurrentLocations($request);
					$courseLocations =  $c->getCurrentLocations();
					//$localityArray[$c->getId()] = getLocationsCityWise($courseLocations);
			?>
				<option title="<?=$c->getName()?>" value="<?=$c->getId()?>"><?=$c->getName()?></option>
			<?php
			}
			?>
				</select>
			</b>
			<b id="locality_w<?=$i->getId()?>" style="margin-left:20px;margin-top:10px;display:block;float:left;font-weight:normal"></b>
			</div>
			<script>
				//populateLocations("apply_w<?=$i->getId()?>", "locality_w<?=$i->getId()?>",<?=$i->getId()?>, "w_");
				$j('#apply_w<?=$i->getId()?>').change(function(){
					populateLocations("apply_w<?=$i->getId()?>", "locality_w<?=$i->getId()?>", <?=$i->getId()?>, "w_","new");
				});
			</script>

		</li>
		<?php $position++;if($liClass == ""){$liClass = "alt-bg";}else{$liClass = "";}}
		?>
	</ul>
	
	<ul class="course-rows">
		<li>
			<div class="personal-details-col">
				<input class="universal-txt-field" value="<?php echo $firstname?htmlentities($firstname):"Your First Name";?>" id="usr_first_name_w"  tip="multipleapply_name" caption="First Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_first_name_w" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your First Name"/>
				<div style="display:none"><div class="errorMsg" id="usr_first_name_w_error" style="padding-left:3px; clear:both; display:block">aaaa</div></div>
			</div>
			<div class="personal-details-col">
				<input class="universal-txt-field" value="<?php echo $lastname?htmlentities($lastname):"Your Last Name";?>" id="usr_last_name_w"  tip="multipleapply_name" caption="Last Name" validate="validateDisplayName" required="true" maxlength="50" minlength="1" profanity="true" type="text" name="usr_last_name_w" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Your Last Name"/>
				<div style="display:none"><div class="errorMsg" id="usr_last_name_w_error" style="padding-left:3px; clear:both; display:block">aaaa</div></div>
			</div>
			<div class="clearFix"></div>
			<br>
			<div class="personal-details-col">
				<input class="universal-txt-field"  value="<?php echo $mobile?$mobile:"Mobile";?>" profanity="true" id="mobile_phone_w" type="text" name="mobile_phone_w" validate="validateMobileInteger" required="true" maxlength="10" minlength="10" tip="multipleapply_cell" caption="mobile phone" blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Mobile" />
				<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="mobile_phone_w_error"></div></div>
			</div>
			<div class="personal-details-col">
				<input class="universal-txt-field"  value="<?php if(!empty($cookiestr)) { $a = $cookiestr; $b = explode('|',$a); echo $b[0]; }else{ echo "Email";} ?>" id="contact_email_w" type="text" validate="validateEmail"  maxlength="100" minlength="10" caption="email"  blurMethod="checkTextElementOnTransition(this,'blur')" onfocus="checkTextElementOnTransition(this,'focus')" default="Email" />
				<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="contact_email_w_error"></div></div>
			</div>
		</li>
		
		<?php
		if($isSAComparePage) {
			echo Modules::run('MultipleApply/MultipleApply/getExtraFieldsForStudyAbroadResponseForm','CompareLayer','compareMultiple');		
		}
		?>
		
		<?php if (empty($displayname)) { ?>

		<li>
			<script type="text/javascript">
    		flagSignedUser = true; 
			</script> 
			<p>Type in the character you see in the picture below</p>
			<div class="spacer5 clearFix"></div>
			<span class="flLt"><img align = "top" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_w" width="100" height="34"  id = "secureCode_w"/></span>
			<div class="captcha-field"><input type="text" class="universal-txt-field"  name = "homesecurityCode_w" id = "homesecurityCode_w" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/></div>
            <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px;">
				<div style="margin-left:3px; clear:both" class="errorMsg" id= "homesecurityCode_w_error"></div>
			</div>
			
		</li>
		
		<li><input type="checkbox" name="cAgree" id="requestEbrochure_cAgree_w" checked />
			I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms of Services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div class="errorMsg" id="requestEbrochure_cAgree_w_error" style="padding-left:4px;"></div>
			</div>
		</li>
		<?php } ?>
		<li>
			<input type="submit" value="Submit" id="submitRegisterFormButton_w"  uniquesattr = "<?=$trackingBottomWidgetComparePage?>" class="orange-button" /><br />
			<div class="spacer5 clearFix"></div>
			<strong><em>You will be contacted by the institutes you have selected</em></strong>
		</li>
	</ul>
	<div class="clearFix"></div>
</div>
</form>
<script>
	var localityArray = <?=json_encode($localityArray)?>;
	$j.each(localityArray,function(index,element){
		custom_localities[index] = element;
	});
	addOnBlurValidate($('requestEbrochure_w'));
	function validate_form_w() {		
		trackEventByGA('LinkClick', STUDY_ABROAD_TRACKING_KEYWORD_PREFIX+'CATEGORY_COMPARE_BOTTOM_WIDGET');
		try{
			
			if (document.getElementById('helpbubble')) {
				document.getElementById('helpbubble').style.display='none';
			}
			var checks = document.getElementsByName('overlay_w[]');
			var boxLength = checks.length;
			var flag_totalChecked = 0;
			for ( j=0; j < boxLength; j++ )
			{
				if ( checks[j].checked == true )
				flag_totalChecked++;
			}
			if (flag_totalChecked == 0 || flag_totalChecked ==undefined){
				document.getElementById('flag_totalChecked_error_w').style.display = 'inline';
				orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
				return false;
			} else {
				document.getElementById('flag_totalChecked_error_w').style.display = 'none';
			}
			var flag = validateFields(document.getElementById('requestEbrochure_w'));
			var flagAbroadFields = validateStudyAbroadResponseFormFields('compareMultiple');
			
			if (flagSignedUser == true ) {
				var checkboxAgree = document.getElementById('requestEbrochure_cAgree_w');
				var flag2 = true;
				if(checkboxAgree.checked != true)
				{
					var flag2 = false;
					document.getElementById('requestEbrochure_cAgree_w_error').innerHTML = 'Please agree to Terms & Conditions.';
					document.getElementById('requestEbrochure_cAgree_w_error').parentNode.style.display = 'inline';
					orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
					return false;
				}
				else
				{
					document.getElementById('requestEbrochure_cAgree_w_error').innerHTML = '';
					document.getElementById('requestEbrochure_cAgree_w_error').parentNode.style.display = 'none';
				}
			}
			email_id = document.getElementById('contact_email_w').value;
			phone_no = document.getElementById('mobile_phone_w').value;
			//display_name = document.getElementById('usr_name_w').value;

			if (flagSignedUser == true ) {
				if((flag == true) && (flag2 == true) && (flagAbroadFields == true)) {
					validateCaptcha1_w('homesecurityCode_w','secCodeIndex_w','secureCode_w');
				} else {
					orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
					return false;
				}
			} else {
				if((flag == true) && (flagAbroadFields == true)) {
					processData_w();
				} else {
					orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
					return false;
				}
			}
		} catch (ex){
			if (debugMode){
				throw ex;
			} else {
				logJSErrors(ex);
			}
		}          
	}
	recommendation_json = {};
	function processData_w()
	{
		
		category_course_base_url = "COMPARE_2";
		try{
			orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
			var sJSON = '{';
			var sJSONArray = [];
			var localityJSON = '{';
			var checks = document.getElementsByName('overlay_w[]');
			var flag_count = 1;
			var locality_flag_count = 1;
			for (var i=0; i < checks.length; i++)
			{
				if (checks[i].checked)
				{
					var a = 'reqEbr_w_'+checks[i].value;
					var b = 'apply_w'+checks[i].value;
					var sJSONT = "";
					sJSONT += '"'+checks[i].value+'": [ "';
					sJSONT += encodeURIComponent(document.getElementById(a).getAttribute('url'));
					sJSONT += '", "';
					sJSONT += encodeURIComponent(document.getElementById(a).innerHTML);
					sJSONT += '", "';
					sJSONT += document.getElementById(a).getAttribute('type');
					sJSONT += '", "';
					sJSONT += document.getElementById(b).value;
					sJSONT += '", "';
					sJSONT += 'course';
					sJSONT += '" ]';
					sJSONArray.push(sJSONT);
					recommendation_json[checks[i].value] = document.getElementById(b).value;
					setCookie("applied_"+document.getElementById(b).value,1);
					/*
					 Add locality info
					 By Vikas K
					*/
					if(document.getElementById('preferred_city_category_w_'+checks[i].value))
					{
						if (locality_flag_count > 1)
						{
							localityJSON += ',';
						}
						
						var sb_preferred_city = document.getElementById('preferred_city_category_w_'+checks[i].value);
						var sb_preferred_locality = document.getElementById('preferred_locality_category_w_'+checks[i].value);
						
						var preferred_city = sb_preferred_city.options[sb_preferred_city.selectedIndex].value;
						var preferred_locality = sb_preferred_locality.options[sb_preferred_locality.selectedIndex].value;
						
						localityJSON += '"'+checks[i].value+'": [ "'+preferred_city+'", "'+preferred_locality+'" ]';
						locality_flag_count++;
					}
					flag_count++;
				}
			}
			sJSON += sJSONArray.join(",");
			sJSON += '}';
			localityJSON += '}';
			documentOverlay.show();
			trackEventByGA('LinkClick', STUDY_ABROAD_TRACKING_KEYWORD_PREFIX+'CATEGORY_COMPARE_BOTTOM_WIDGET_FINAL_APPLY/'+flag_count);
			var paraString = "reqInfofirstName="+document.getElementById('usr_first_name_w').value+"&reqInfolastName="+document.getElementById('usr_last_name_w').value+"&reqInfoPhNumber="+document.getElementById('mobile_phone_w').value+"&reqInfoEmail="+document.getElementById('contact_email_w').value+"&jSON="+sJSON+"&localityJSON="+localityJSON+"&resolution="+document.getElementById('resolution_w').value+"&coordinates="+document.getElementById('coordinates_w').value+"&loginproductname="+document.getElementById('loginproductname_w').value+"&referer="+document.getElementById('referer_w').value;
			if (flagSignedUser == true ) {
				paraString +="&captchatext="+document.getElementById("homesecurityCode_w").value;
			}
			var securityCodeVar = 'secCodeIndex_w';
			var securityCode = $j('#homesecurityCode_w').val();
			var registrationSource  = "comparePageWidget";
			
			var referrer = $j('link[rel=canonical]').attr('href',$j('base').attr('href'));
                        if(typeof(referrer) == 'object') {
				referrer = $j('link[rel=canonical]').attr('href');
                        }
			var categoryId = 0;
			var desiredCourse = 0;
			if(typeof($categorypage) !== "undefined"){
				if(typeof($categorypage.categoryId) !== "undefined"){
					categoryId = $categorypage.categoryId;
				}
				if(typeof($categorypage.LDBCourseId) !== "undefined"){
					desiredCourse = $categorypage.LDBCourseId;
				}
			}
			
			var dataForRegistration = 	{
				"firstName" : document.getElementById('usr_first_name_w').value,
				"lastName" : document.getElementById('usr_last_name_w').value,
				"email" : document.getElementById('contact_email_w').value,
				"mobile" : document.getElementById('mobile_phone_w').value,
				"securityCodeVar" : securityCodeVar,
				"securityCode" : securityCode,
				"registrationSource" : registrationSource,
				"studyAbroad" : studyAbroad,
				"referrer" : referrer,
				"categoryId" : categoryId,
				"desiredCourse" : desiredCourse
			};
			
			var studyAbroadFieldValues = getStudyAbroadResponseFormFieldValues('compareMultiple');
			for(skey in studyAbroadFieldValues) {
				dataForRegistration[skey] = studyAbroadFieldValues[skey];
			}
			
			shikshaUserRegistration.registerUser(dataForRegistration,false,function(response){
				if(response.status == "SUCCESS"){
				        //since we aren't downloading anything here,we dont need download tracking.
					//Hence we pass a flag to indicate that the download is not happening
					var noTrackForDownload = 1;
					var url = "/MultipleApply/MultipleApply/getBrochureRequest" ;
					paraString += "&noTrackForDownload="+noTrackForDownload;
					new Ajax.Request(url, { method:'post', parameters: (paraString), onSuccess:function (request){
						closeMessage();
						displayMessage('/MultipleApply/MultipleApply/showoverlay/19',500,50);
						documentOverlay.hide();
					}});
					
				}else{
					documentOverlay.hide();
				}
			});
			
		} catch (ex){
			alert("abc"+ex);
			if (debugMode){
				throw ex;
			} else {
				logJSErrors(ex);
			}
		}          
	}
	function hideShowLocalityLayer(instituteId){
		
		if($('preferred_city_category_w_'+instituteId)){
			if($('reqEbrOverlay_w'+instituteId).checked){
				$j('#apply_w'+instituteId).trigger('change');
				$('locality_w'+instituteId).style.display = "block";
			}else{
				$j('#apply_w'+instituteId).trigger('change');
				$('preferred_city_category_w_'+instituteId).style.display = "none";
				$('preferred_locality_category_w_'+instituteId).style.display = "none";
				$('preferred_city_category_w_'+instituteId+'_error').parentNode.style.display = 'none';
				$('preferred_locality_category_w_'+instituteId+'_error').parentNode.style.display = 'none';
				$('locality_w'+instituteId).style.display = "none";
			}
		}
	}
	function validateCaptcha1_w(secCodeTextid,secodeIndex,captchaId)
	{
		try{
			if(document.getElementById(secCodeTextid)){
				var ObjectOfSecCode = document.getElementById(secCodeTextid);
				var caption = ObjectOfSecCode.getAttribute('caption');
				var url = "/MultipleApply/MultipleApply/get_free_alerts/"+ObjectOfSecCode.value+"/"+secodeIndex;
			
			new Ajax.Request(url, { method:'post',onSuccess:function (request){
						if(trim(request.responseText)=='true'){ 
							processData_w();
							return true;
						}
						else {
							reloadCaptcha(captchaId,secodeIndex);
							orangeButtonDisableEnableWithEffect('submitRegisterFormButton_w',false);
							document.getElementById(secCodeTextid+'_error').parentNode.style.display = 'inline';
							document.getElementById(secCodeTextid+'_error').innerHTML = "Please enter the "+caption+" as shown in the image";
							return false;
						}
					} 
					});
				return false;
			}
		} catch (ex){
			if (debugMode){
				throw ex;
			} else {
				logJSErrors(ex);
			}
		}          
	}
	document.getElementById('resolution_w').value = getCoordinates(); 
    document.getElementById('coordinates_w').value = getResolution(document.getElementById('requestEbrochure_w'));
    document.getElementById('loginproductname_w').value = 'COMPARE_PAGE_WIDGET';
    document.getElementById('referer_w').value = location.href;  
	if($('contact_email_w').value != "" && $('contact_email_w').value != "Email"){
		userlogin = true;
		$('contact_email_w').disabled = true;
	}else{
		userlogin = false;
	}
	$j('#requestEbrochure_w').find("select").trigger('change');

</script>
