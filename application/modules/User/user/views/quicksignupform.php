<form method="post" onsubmit="new Ajax.Request('/user/Userregistration/quicksignup',{onSuccess:function(request){javascript:showQuickSignUpResponse(request.responseText);}, onFailure:function(request){javascript:showQuickSignUpResponse(request.responseText)}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="/user/Userregistration/quicksignup" id = "RegistrationForm" name = "RegistrationForm">
<input type = "hidden" name = "loginproductname" id = "loginproductname" value = ""/>
<input type = "hidden" name = "resolution" id = "resolution" value = ""/>
<input type = "hidden" name = "coordinates" id = "coordinates" value = ""/>
<input type = "hidden" name = "referer" id = "referer" value = ""/>
<div>
	<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">First Name: &nbsp;</div>
	<div style="margin-left:125px;"><input id = "quickfirstname" name = "quickfirstname" type = "text" tabindex = 101 validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "first name" style="width:170px"/></div>
</div>
<div class="errorPlace" style="margin-top:2px;">
	<div style="margin-left:125px" class="errorMsg" id= "quickfirstname_error"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
	<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Last Name: &nbsp;</div>
	<div style="margin-left:125px;"><input id = "quicklastname" name = "quicklastname" type = "text" tabindex = 101 validate = "validateDisplayName" required = "true" maxlength = "50" minlength = "1" caption = "last name" style="width:170px"/></div>
</div>
<div class="errorPlace" style="margin-top:2px;">
	<div style="margin-left:125px" class="errorMsg" id= "quicklastname_error"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
<div>
	<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Login Email Id: &nbsp;</div>
	<div style="margin-left:125px"><input type="text" id = "quickemail" name = "quickemail" tabindex = 102 validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" onblur = "checkAvailability(document.getElementById('quickemail').value,'quickemail')" style="width:170px" /></div>
</div>
<div class="errorPlace" style="margin-top:2px;">
	<div  style="margin-left:125px" class="errorMsg" id= "quickemail_error"></div>
</div>
<div class="lineSpace_10">&nbsp;</div>
                                            <div>
												<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Password: &nbsp;</div>
												<div style="margin-left:125px"><input type="password" id = "quickpassword" name = "quickpassword" tabindex = 103 validate = "validateStr" minlength = "5" maxlength = "20" required = "true" caption = "password"  style="width:170px" /></div>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                 <div  style="margin-left:125px" class="errorMsg" id= "quickpassword_error"></div>
                                            </div>
                                            <div class="lineSpace_10">&nbsp;</div>

                                            <div>
												<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Confirm Password: &nbsp;</div>
												<div style="margin-left:125px"><input id = "quickconfirmpassword" name = "quickconfirmpassword" tabindex = 104 type="password" minlength = "5" maxlength = "20" required = "true" validate = "validateStr" caption = "password again" onblur = "validatepassandconfirmpass('quickpassword','quickconfirmpassword');"  style="width:170px" /></div>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div style="margin-left:125px" class="errorMsg" id= "quickconfirmpassword_error"></div>
                                            </div>                                            
                                            <div class="lineSpace_10">&nbsp;</div>
                                            <div>
												<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Mobile: &nbsp;</div>
												<div style="margin-left:125px">
												<input id = "quickmobileno" name = "quickmobileno" type="text" minlength = "10" maxlength = "10" tabindex = 105 validate = "validateMobileInteger" caption = "mobile number" style="width:170px" /></div>
<span style="margin-left:125px;width:115px;color:#ACACAC">eg: 9876512345 </span>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div style="margin-left:125px" class="errorMsg" id= "quickmobileno_error"></div>
                                            </div>                                            
                                            <div class="lineSpace_10">&nbsp;</div>

                                            <div>
												<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Landline: &nbsp;</div>
                                                <div style="margin-left:125px">
                                                <input id = "quicklandlineext" name = "quicklandlineext" type="text" minlength = "3" maxlength = "5" tabindex = 106 validate = "validateLandlineInteger" caption = "STD code" style="width:40px" />
                                                <input id = "quicklandlineno" name = "quicklandlineno" type="text" minlength = "6" maxlength = "8" tabindex = 107 validate = "validateLandlineInteger" caption = "landline number" style="width:125px" />
</div>
<span style="margin-left:125px;width:115px;color:#ACACAC">eg: 011 </span>
<span style="margin-left:15px;width:115px;color:#ACACAC">eg: 43434343 </span>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div style="margin-left:125px" class="errorMsg" id= "quicklandlineext_error"></div>
                                            </div>                                            
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div style="margin-left:125px" class="errorMsg" id= "quicklandlineno_error"></div>
                                            </div>                                            
                                            <div class="lineSpace_10">&nbsp;</div>
		<!-- Residence_Location -->
		<div>
	        <div class="float_L txt_align_r" style="width:125px;font-size:11px;line-height:20px;text-align:right;">Residence Location:&nbsp;</div>
			<div style = "margin-left:125px">
				<select style = "width:70px" class = "normaltxt_11p_blk_arial fontSize_12p" id = "countryofquickreg" name = "countryofquickreg" tabindex = 108 validate = "validateSelect" required = "true" caption = "country" onChange = "getCitiesForCountry('',false,'ofquickreg')">
				<?php
					global $countries; 
					foreach($countries as $countryId => $country) {
							$countryName = isset($country['name']) ? $country['name'] : '';
							$countryValue = isset($country['value']) ? $country['value'] : '';
							$countryId = isset($country['id']) ? $country['id'] : '';
					?>
                    <option value = "<?php echo $countryId?>" countryId = "<?php echo $countryValue?>"><?php echo $countryName?></option>
                    <?php } ?>
				</select>
				<select style = "width:100px" class = "normaltxt_11p_blk_arial fontSize_12p" id = "citiesofquickreg" name = "citiesofquickreg" tabindex = 109 validate = "validateSelect" required = "true" caption = "your city of residence">
				</select>
			</div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "countryofquickreg_error"></div>			
				<div  style="margin-left:125px" class="errorMsg" id= "citiesofquickreg_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
		<!--End_Residence_Location-->
                                            <div>
												<div class="float_L txt_align_r" style="width:120px;font-size:11px;line-height:20px">Age: &nbsp;</div>
												<div style="margin-left:125px"><input id = "quickage" name = "quickage" type="text" minlength = "2" tabindex = 110 maxlength = "2" required = "true" validate = "validateAge" caption = "age" style="width:20px" />
                                               <span style="font-size:11px"> Gender:<span>
<input type="radio" name = "quickgender" tabindex = 111 id = "qFemale" value = "Female" />F 
<input type="radio" name = "quickgender" tabindex = 112 id = "qMale" value = "Male"/>M
                                                </div>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div style="margin-left:125px" class="errorMsg" id= "quickage_error"></div>
                                            </div>                                            
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div style="margin-left:125px" class="errorMsg" id= "quickgender_error"></div>
                                            </div>                                            
                                            <div class="lineSpace_10">&nbsp;</div>
		<!--Highest_Education_Level-->
		<div>
	        <div class="float_L txt_align_r" style="width:125px;font-size:11px;line-height:15px;">Highest Education:&nbsp;</div>
			<div style="margin-left:125px">                                     
				<select tabindex = 113 class = "normaltxt_11p_blk_arial fontSize_12p" style = "width:145px;" id = "quickeducation" name = "quickeducation" validate = "validateSelect" required = "true" caption = "your highest education">
				</select>
             </div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "quickeducation_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
        <!-- Highest Education Level Ends -->

        <!--Education_Interest-->
		<div>
	        <div class="float_L txt_align_r" style="width:125px;font-size:11px;line-height:15px;">Education Interest:&nbsp;</div>
			<div style="margin-left:125px">                                     
				<select tabindex = 114 class = "normaltxt_11p_blk_arial fontSize_12p" style = "width:145px;" id = "quickinterest" name = "quickinterest" validate = "validateSelect" required = "true" caption = "your education interest">
					<option value="">Select Category</option>
					<option value="Study Abroad">Study Abroad</option>
	<?php
    global $categoryParentMap;
	foreach ($categoryParentMap as $categoryName => $category)
	{ ?>
			<option value = "<?php echo $categoryParentMap[$categoryName]['id']?>"><?php echo $categoryName?></option>
<?php } ?>
					<option value="Undecided">Presently Undecided</option>
				</select>
             </div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "quickinterest_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
        <!--Education Interest Level Ends -->
                                            <div>
												<div class="txt_align_r mar_right_10p" style="font-size:11px;">Type in the characters you see in the picture below:</div>
                                            </div>
                                            <div class="lineSpace_10">&nbsp;</div>  
											<div class="txt_align_r mar_right_10p">
												<img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=30&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode1" width="100" height="30"  id = "registerCaptacha"/>
												<input tabindex = 115 type="text" size="18" id = "securityCode" name = "securityCode"/>
											</div>
                                            <div class="errorPlace" style="margin-top:2px;">
                                                  <div class="errorMsg" id= "securityCode_error"></div>
                                            </div>
                                            <div class="lineSpace_10">&nbsp;</div>  
<!-- Agreement -->
			<div style="font-size:11px">
				<input type="checkbox" tabindex = 116 checked id = "quickagree"/> I agree to the 
                <a href="javascript:" tabindex = 117 onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a> and <a href="javascript:" tabindex = 118 onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a>
			</div>
			<div class="errorPlace" style="margin-top:2px;">
				<div class="errorMsg" id = "quickagree_error">&nbsp;</div>
			</div>
			<div class="errorPlace" style="margin-top:2px;">
				<div class="errorMsg bld" id = "quickerror">&nbsp;</div>
			</div>
    <div class="lineSpace_18">&nbsp;</div>

<!-- Agreement -->
											<div class="txt_align_c">
												<input type="submit" onclick = "return validatequickSignUp(this.form)" value="Join Now For Free" class="rgsBtnJoin bld whiteColor" tabindex = 119/>
												<!--<button type="submit" onclick = "return validatequickSignUp(this.form)" value="" class="btn-submit19 w20">
													<div class="btn-submit19" id = "OpenAccount"><p style="padding: 15px 8px 15px 5px;" class="btn-submit20 btnTxtBlog">Join Now For Free</p></div>
				 								</button>-->
											</div>
                                        </form>										
           <script>
           addOnBlurValidate(document.getElementById('quicksignupForm'));
           </script>
