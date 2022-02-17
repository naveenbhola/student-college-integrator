<?php
	$widget = 'newListingsAnABottom';
	$formCustomData['widget'] = $widget;
	$formCustomData['buttonText'] = '';
	$formCustomData['customCallBack'] = '';
	
	$instituteId = $institute->getId();
	$locationId = $institute->getMainLocation()->getCountry()->getId();
	$courseId = $course->getId();
	$categoryId = 0;
	$categories = $instituteRepository->getCategoryIdsOfListing($courseId,'course');
	if(count($categories) > 0 && is_array($categories) ){
	      $categoryId = $categories[0];
	}
	  
	$FirstNameOfUser = is_array($validateuser[0])?$validateuser[0]['firstname']:"";
	$LastNameOfUser = is_array($validateuser[0])?$validateuser[0]['lastname']:"";
	$cookiStr =  is_array($validateuser[0])?$validateuser[0]['cookiestr']:"";
	$cookiStrArr = explode("|",$cookiStr);
	$emailId =	isset($cookiStrArr[0])?$cookiStrArr[0]:"";
	$contactNumber = is_array($validateuser[0])?$validateuser[0]['mobile']:"";
  
	$questionText = '';
	if(isset($_COOKIE['commentContent']) && ($questionText == '')){
		$commentContentData = $_COOKIE['commentContent'];
		if((stripos($commentContentData,'@$#@#$$') !== false) && (stripos($commentContentData,'@#@!@%@') === false)){
			$questionText = str_replace("@$#@#$$","",$commentContentData);
		}
	}
?>
<script>
function populatUserData(){
        email_id = $('emailOfUserForAskInstitute').value;
        phone_no = $('mobileOfUserForAskInstitute').value;
        display_name = $('nameOfUserForAskInstitute').value;
        return false;
}
</script>

	<div id="ask_ana_question" class="other-details-wrap clear-width">
		<div class="ask-form">
			<h5>
				<i class="sprite-bg que-icon"></i>Ask question to <?php echo $collegeOrInstituteRNR;?>
			</h5>
			<?php 
			$formCustomData['trackingPageKeyId'] = $questionTrackingPageKeyId;
			echo Modules::run('registration/Forms/LDB',NULL,'askQuestionBottom',$formCustomData); ?>		
			<!-- <form id="form_<?=$widget?>" onsubmit="processFloatingForm('<?=$widget?>'); return false;" autocomplete="off" novalidate>
				<ul class="ask-que-list">
					<li>
						<textarea class="ask-area" autocomplete="off" onkeyup="if(event.keyCode == 13){ return false;} else { textKey(this); }" onkeypress="if(event.keyCode == 13){ return false;} else { textKey(this); }"  profanity="true" caption="Question" maxlength="140" minlength="2" required="true" id="ask_question_<?=$widget?>" name="ask_question_<?=$widget?>" validate="validateStr" onblur="national_listings_obj.showErrorMessage(this, '<?=$widget?>');"></textarea>
						<p class="char-limit"><span id="ask_question_<?=$widget?>_counter">0</span> out of 140 character</p>
						<div class="errorPlace" style="display:none">
							<div class="errorMsg" id="ask_question_<?=$widget?>_error"></div>
						</div>
					</li>
					
					<li>
						<div class="flLt ask-col-1">
							<label>
								First Name:<span>*</span>
							</label>
							<input type="text" placeholder="" class="universal-txt-field" id="usr_first_name_<?=$widget?>" name="usr_first_name_<?=$widget?>" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "First name" value="<?php echo htmlentities($FirstNameOfUser); ?>" onblur="national_listings_obj.showErrorMessage(this, '<?=$widget?>');"/>
							<div class="errorPlace" style="display:none">
								<div class="errorMsg" id="usr_first_name_<?=$widget?>_error"></div>
							</div>
						</div>
						<div class="flLt ask-col-2">
							<label>
								Last Name:<span>*</span>
							</label>
							<input type="text" placeholder="" class="universal-txt-field" id="usr_last_name_<?=$widget?>" name="usr_last_name_<?=$widget?>" validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "Last name" value="<?php echo htmlentities($LastNameOfUser); ?>" onblur="national_listings_obj.showErrorMessage(this, '<?=$widget?>');"/>
							<div class="errorPlace" style="display:none">
								<div class="errorMsg" id="usr_last_name_<?=$widget?>_error"></div>
							</div>
						</div>
					</li>
					
					<li>
						<div class="flLt ask-col-1">
							<label>
								Email:<span>*</span>
							</label>
							<input type="text" placeholder="" class="universal-txt-field" id="contact_email_<?=$widget?>"  name="contact_email_<?=$widget?>"  validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" value="<?php echo $emailId; ?>" <?php if($emailId != ""){ echo 'disabled="true"';} ?> onblur="national_listings_obj.showErrorMessage(this, '<?=$widget?>');"/>
							<div class="errorPlace" style="display:none" >
								<div class="errorMsg" id="contact_email_<?=$widget?>_error"></div>
							</div>
						</div>
						
						<div class="flLt ask-col-2">
							<label>
								Contact Number:<span>*</span>
							</label>
							<input type="text" placeholder="" class="universal-txt-field" id="mobile_phone_<?=$widget?>"  name="mobile_phone_<?=$widget?>" minlength = "10" maxlength = "10" validate = "validateMobileInteger" required = "true" caption = "mobile number" value="<?php echo $contactNumber; ?>" onblur="national_listings_obj.showErrorMessage(this, '<?=$widget?>');"/>
							<div class="errorPlace" style="display:none">
								<div class="errorMsg" id="mobile_phone_<?=$widget?>_error"></div>
							</div>
						</div>
					</li>
					
					<li style="margin-bottom:10px; <?php if($pageType == 'course') { ?> display: none<?php } ?>">
                        <select id="courseId_<?=$widget?>"  name="courseId_<?=$widget?>"  required = "true" caption = "course" class="universal-select" style="width: 195px;" validate = "validateSelect" onchange="national_listings_obj.validateDropDown(this);" onblur="national_listings_obj.validateDropDown(this); return false;"/>
								<?php if($pageType == "course") { ?>
										<option selected value='<?=$courseId?>'>CurrentCourse</option>
										<?php 	$course->setCurrentLocations($request, true);
												$localityArray[$course->getId()] = getLocationsCityWise($course->getCurrentLocations());
										?>
										<script>
												var localityArrayAskQuestion = <?=json_encode($localityArray)?>;
										</script>
								<?php }
								else { ?>
										<option value="">Please Select Course</option>
										<?php foreach($courses as $course) { ?>	
												<option value=<?php echo $course->getId(); ?>> <?=html_escape($course->getName())?></option>
												<?php 	$course->setCurrentLocations($request, true);
														$localityArray[$course->getId()] = getLocationsCityWise($course->getCurrentLocations());
												?>
										<?php } ?>
								<?php } ?>
						</select>
						<div style="display:none"><div class="errorMsg" style="padding-left:3px; clear:both; display:block" name="courseId_<?=$widget?>_error" id="courseId_<?=$widget?>_error"></div></div>
                    </li>
						<li id="locality-div_<?=$widget?>" class="localityDiv" style="display:none;"></li>
					
					<?php if(($validateuser == "false")){?>
						<li>
							<p>Type in the character you see in the picture below</p>
							<div class="sec-code-box">
								<img alt="captcha" src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>&secvariable=secCodeIndex_<?=$widget?>"  onabort="reloadCaptcha(this.id)"  id="secureCode_<?=$widget?>" align="absbottom"/>
									<input type="text" placeholder="" class="universal-txt-field" style="width: 100px" name = "homesecurityCode_<?=$widget?>" id = "homesecurityCode_<?=$widget?>" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code" onblur="national_listings_obj.showErrorMessage(this, '<?=$widget?>');"/>
							</div>
							<div style="display:none">
								<div class="errorMsg" style="padding-left:3px; clear:both; display:block" id="homesecurityCode_<?=$widget?>_error"></div>
							</div>
						</li>
					<?php }?>
					<li>
						<a href="#" class="orange-btn2" style="display: inline-block; padding: 8px 22px" onClick="processFloatingForm('<?=$widget?>'); return false;" name="submit_<?=$widget?>" id="submit_<?=$widget?>" uniqueattr="LISTING_INSTITUTE_PAGES/ListingPageNewAskNowButton">Ask Now</a>
					</li>
				</ul>
				<input type="hidden" id="instituteId_<?=$widget?>" name="instituteId_<?=$widget?>" value="<?=$instituteId?>">
				<input type="hidden" id="locationId_<?=$widget?>" name="locationId_<?=$widget?>" value="<?=$locationId?>">
				<input type="hidden" id="categoryId_<?=$widget?>"  name="categoryId"  value="<?=$categoryId?>"/>
				<input type="hidden" name="getmeCurrentCity" id="getmeCurrentCity" value="<?php echo $currentLocation->getCity()->getId();?>"/>
				<input type="hidden" name="getmeCurrentLocaLity" id="getmeCurrentLocaLity" value="<?php echo $currentLocation->getLocality()->getId();?>"/>
				<script>
						var showAsk='true';
						var reloadWidgetAskQuestionCity = false;
						//var reloadWidgetAskQuestionCity_widget = '<?=$widget?>';
				</script>
			</form> -->
			
			<script>
				var showAsk='true';
				var reloadWidgetAskQuestionCity = false;
				//var reloadWidgetAskQuestionCity_widget = '<?=$widget?>';
			</script>
			<div class="clearFix"></div>
		</div>
	</div>
<div class="clearFix spacer10"></div>
