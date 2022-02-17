<?php
$url = site_url('user/Userregistration/userfromhome/'.$prefix.'seccodehome');
?>
<style type="text/css">
.redcolor{color:#FF0000}
</style>
<form method="post" onsubmit="new Ajax.Request('<?php echo $url?>',{onSuccess:function(request){javascript:newuserresponse<?php echo $prefix;?>(request.responseText);}, evalScripts:true, parameters:Form.serialize(this)}); return false;" action="<?php echo $url?>" id = "<?php echo $prefix; ?>reqInfoUser" name = "reqInfoUser">
<input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
<input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>
<div class="lineSpace_5">&nbsp;</div>
<div class="mar_full_10p">
		<!--Name-->
		<div>
			<div class="float_L fontSize_11p" style="width:125px; line-height:20px">Name:&nbsp;<span class = "redcolor">*</span></div>
			<div style="margin-left:125px"><input type="text" id = "<?php echo $prefix; ?>homename" name = "homename" class = "fontSize_10p" validate = "validateDisplayName" maxlength = "25" minlength = "3" required = "true" caption = "name" style = "width:140px;height:15px;font-size:12px"/></div>   
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div style="margin-left:125px;line-height:15px" class="errorMsg" id= "<?php echo $prefix; ?>homename_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
		<!--Name Ends-->
		<!--Email-->
		<div>
			<div class="float_L fontSize_11p" style="width:125px; line-height:20px">Login Email Id:&nbsp;<span class = "redcolor">*</span></div>
			<div style="margin-left:125px"><input type="text" id = "<?php echo $prefix; ?>homeemail" name = "homeemail" validate = "validateEmail" required = "true" caption = "email address" maxlength = "125" style = "width:140px;height:15px;font-size:12px"/></div>
		</div>
		<div>
	        <div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homeemail_error"></div>	
	       </div>
		</div>
        <div class="lineSpace_8">&nbsp;</div>
        <!--Email Ends-->
		<!--Password-->
		<div>
	        <div class="float_L fontSize_11p" style="width:125px;line-height:20px">Password:&nbsp;<span class = "redcolor">*</span></div>
			<div style="margin-left:125px"><input type="password" id = "<?php echo $prefix; ?>homepassword" name = "homepassword" validate = "validateStr" required = "true" caption = "password" minlength = "5"  maxlength = "20" style = "width:140px;height:15px;font-size:11px;"/></div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homepassword_error">cv</div>	
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
		<!--End_Password-->

		<!--Contact_Number-->
		<div>
			<div class="float_L fontSize_11p" style="width:125px; line-height:20px">Mobile Number:&nbsp;<span class = "redcolor">*</span></div>
			<div style="margin-left:125px"><input type="text" id = "<?php echo $prefix; ?>homephone" name = "homephone" validate = "validateMobileInteger" required = "true" caption = "mobile number" maxlength = "10" minlength = "10" style="width:140px;height:15px;font-size:12px" /></div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homephone_error"></div>	
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>		
        <!--Contact_Number_Ends-->

		<!-- Education Interest -->
		<div>
			<div class="float_L fontSize_11p" style="width:125px; line-height:20px">Education Interest:&nbsp;<span class = "redcolor">*</span></div>
			<div style = "margin-left:125px" id = "<?php echo $prefix; ?>homeeducationinterest" name = "homeeducationinterest">
				<select class="normaltxt_11p_blk_arial fontSize_11p" name="board_id" id="<?php echo $prefix; ?>board_id" style="width:145px;" tip="course_categories" validate = "validateSelect" required = "true" caption = "your education interest">;
					<option value="">Select Category</option>
					<option value="Study Abroad">Study Abroad</option>
						<?php 	 
							if(isset($subCategories) && is_array($subCategories)) {
							$otherElementId = '';
								foreach($subCategories as $subCategory) {
									$subCategoryId = $subCategory['boardId'];
									$subCategoryName = $subCategory['name'];
									if(strpos($subCategoryName,'Others..') !== false){
										$otherElementId = $subCategoryId ;
										continue;
									}
									if($subCategoryId == $categoryId) {
										 $selected = 'selected';
									} else {
										 $selected = '';
									}
						?>
						<option value="<?php echo $subCategoryId; ?>" <?php echo $selected; ?> title = "<?php echo $subCategoryName ?>"><?php echo $subCategoryName; ?></option>
						<?php
								 }
								 if($otherElementId != '') {
									if($otherElementId == $categoryId) {
										 $selected = 'selected'; 
									 } else{
											$selected = '';
									 }
						?>														 
						<option value="<?php echo $otherElementId ; ?>" <?php echo $selected ;?>>Others..</option>
						<?php
								}
							}
						?>
					<option value="Undecided">Presently Undecided</option>
				</select>
			</div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>board_id_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
        <!--Education_Interest_Ends-->
		<!--Highest_Education_Level-->
		<div>
	        <div class="float_L fontSize_11p" style="width:125px; line-height:15px">Highest Education:&nbsp;<span class = "redcolor">*</span></div>
			<div style="margin-left:125px">                                     
				<select class = "normaltxt_11p_blk_arial fontSize_11p" style = "width:145px;" id = "<?php echo $prefix; ?>homehighesteducationlevel" name = "homehighesteducationlevel" validate = "validateSelect" required = "true" caption = "your highest education">
				</select>
             </div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homehighesteducationlevel_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
        <!-- Highest Education Level Ends -->
         <script>getEducationLevel('<?php echo $prefix; ?>homehighesteducationlevel','',1,'reqInfo');</script>
		<!-- Residence_Location -->
		<div>
	        <div class="float_L fontSize_11p" style="width:125px; line-height:20px">Residence Location:&nbsp;<span class = "redcolor">*</span></div>
			<div style = "margin-left:125px">
				<select style = "width:50px" class = "normaltxt_11p_blk_arial fontSize_11p" id = "country<?php echo $prefix; ?>" name = "countryofresidence1" validate = "validateSelect" required = "true" caption = "country" onChange = "getCitiesForCountry('',false,'<?php echo $prefix; ?>')">
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
				<?php global $citiesforRegistration; ?>
				<script>var citiesarray = <?php echo json_encode($citiesforRegistration)?>;</script>
				<select style = "width:87px" class = "normaltxt_11p_blk_arial fontSize_11p" id = "cities<?php echo $prefix; ?>" name = "citiesofresidence1" validate = "validateSelect" required = "true" caption = "your city of residence">
				</select>
                <script>getCitiesForCountry('',false,'<?php echo $prefix; ?>');</script>
			</div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "country<?php echo $prefix; ?>_error"></div>			
				<div  style="margin-left:125px" class="errorMsg" id= "cities<?php echo $prefix; ?>_error"></div>
			</div>
		</div>
		<div class="lineSpace_8">&nbsp;</div>
		<!--End_Residence_Location-->
		<script>
			var todaydate =new Date();
			var year = todaydate.getFullYear()-48;
			var datetoset = year + '-01-01';
		</script>
		<div>
		    <div class="float_L fontSize_11p" style="width:125px; line-height:20px">Age:&nbsp;<span class = "redcolor">*</span></div>
			<div style = "margin-left:125px">
<!--				<input style="width:75px;height:15px" type="text" required="true" required="true" validate="checkDOB" name="homeYOB" id="<?php echo $prefix; ?>homeYOB" value="" readonly maxlength="10" size="15" class="" onchange="checkDOBChange(this.value);" onClick="<?php echo $prefix; ?>cal.select($('<?php echo $prefix; ?>homeYOB'),'<?php echo $prefix; ?>sd','yyyy-MM-dd',$('<?php echo $prefix; ?>homeYOB').value=='' ? datetoset : null);" caption="Date Of Birth"  onblur = "checkDOBChange(this.value,'<?php echo $prefix; ?>homeYOB_error');"/>-->
<!--				<span class="calenderIcon" id="<?php echo $prefix; ?>sd" onClick="<?php echo $prefix; ?>cal.select($('<?php echo $prefix; ?>homeYOB'),'<?php echo $prefix; ?>sd','yyyy-MM-dd',$('<?php echo $prefix; ?>homeYOB').value=='' ? datetoset : null);">&nbsp;</span>-->
<input id = "<?php echo $prefix?>homeYOB" name = "homeYOB" type="text" minlength = "2" maxlength = "2" required = "true" validate = "validateAge" caption = "age field" style="width:20px" />&nbsp;&nbsp;
<span>Gender:<span><input type="radio" name = "homegender" id = "<?php echo $prefix?>Female" value = "Female"/>F 
<input type="radio" name = "homegender" id = "<?php echo $prefix?>Male" value = "Male"/>M
			</div>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homeYOB_error"></div>	
			</div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div style="margin-left:125px" class="errorMsg" id= "homegender_error"></div>	
			</div>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<!--Year_of_Birth_Ends-->
		<div>
			<div class="fontSize_11p" style="line-height:15px">Type in the characters you see in the picture below</div>
		</div>
		<div class="lineSpace_4">&nbsp;</div>
		<div>
            <img align = "absmiddle" src="/CaptchaControl/showCaptcha?width=100&height=34&characters=5&randomkey=<?php echo rand(); ?>&secvariable=<?php echo $prefix; ?>seccodehome" width="100" height="34"  id = "<?php echo $prefix; ?>secureCode"/>
			<input type="text" style="margin-left:20px;width:135px;height:15px;font-size:12px" name = "homesecurityCode" id = "<?php echo $prefix; ?>homesecurityCode" validate = "validateSecurityCode" maxlength = "5" minlength = "5" required = "1" caption = "Security Code"/>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div  style="margin-left:125px" class="errorMsg" id= "<?php echo $prefix; ?>homesecurityCode_error"></div>	
			</div>
		</div>
		<div class="lineSpace_4">&nbsp;</div>
		
		<div class="row">
			<input type="checkbox" name="cAgree" id="<?php echo $prefix; ?>cAgree" />
			I agree to the <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">terms of services</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">privacy policy</a>
		</div>
		<div>
			<div class="errorPlace" style="margin-top:2px;display:none;line-height:15px">
				<div class="errorMsg" id= "<?php echo $prefix; ?>cAgree_error"></div>												
			</div>
		</div>
		<div class="lineSpace_5">&nbsp;</div>
		<div class="txt_align_c">
			<input type="submit" onclick="return sendReqInfo<?php echo $prefix;?>(this.form);" value="Join Now" class="homepageJoinNowBtn" style="border:0  none" />
            <!--<button onclick="return sendReqInfo<?php echo $prefix;?>(this.form);" type="submit" title="Join now for free" class="homepageJoinNowBtn">Join Now</button>-->
		</div>
		<div class="lineSpace_5">&nbsp;</div>
</div>

</form>
<script>
fillProfaneWordsBag();
function newuserresponse<?php echo $prefix;?>(responseText)
{
    reloadCaptcha('<?php echo $prefix;?>secureCode','<?php echo $prefix; ?>seccodehome');
    if((trim(responseText) == 'both') || (trim(responseText) == 'email') || (trim(responseText) == 'false')){
        document.getElementById('<?php echo $prefix; ?>homeemail_error').innerHTML = 'Email Already exists !!';
        document.getElementById('<?php echo $prefix; ?>homeemail_error').parentNode.style.display = 'inline';
        return;
    }
    if(trim(responseText) == 'code')
    {

        var securityCodeErrorPlace = '<?php echo $prefix; ?>homesecurityCode_error';
        document.getElementById(securityCodeErrorPlace).parentNode.style.display = 'inline';
        document.getElementById(securityCodeErrorPlace).innerHTML = 'Please enter the Security Code as shown in the image.';	
    }
    else
    {
        if(document.getElementById('userLoginOverlay')){
            document.getElementById('userLoginOverlay').style.display = 'none';
        }
        var divX = document.body.offsetWidth/2 - 150;
        var   divY = screen.height/2 - 200;
        var  h = document.documentElement.scrollTop;
        divY = divY + h;
        Message = 'Congratulations you have successfully registered on shiksha.com.';
        showConfirmation(divX,divY,Message);
    }
}

function sendReqInfo<?php echo $prefix; ?>(objForm){
    document.getElementById('refererreg').value = location.href;
    document.getElementById('resolutionreg').value = screen.width +'X'+ screen.height;
    var flag = validateFields(objForm);
    var flag1 = validateGender('homepageMale','homepageFemale','homegender_error');
    var flag2 = true;
    if(trim(document.getElementById("<?php echo $prefix?>" + "homephone").value) == "")
    {
        document.getElementById("<?php echo $prefix?>"+"homephone_error").innerHTML = "Please enter your correct mobile number";
        document.getElementById("<?php echo $prefix?>"+"homephone_error").parentNode.style.display = "inline";
        flag2 = false;
    }
    if(flag != true || flag1 != true || flag2 != true){
        return false;
    }
    else{
        var checkboxAgree = document.getElementById('<?php echo $prefix;?>cAgree');
        if(checkboxAgree.checked != true)
        {
            document.getElementById('<?php echo $prefix;?>cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
            document.getElementById('<?php echo $prefix;?>cAgree_error').parentNode.style.display = 'inline';
            return false;
        }
        else {
            document.getElementById('<?php echo $prefix;?>cAgree_error').innerHTML = 'Please agree to Terms & Conditions.';
            document.getElementById('<?php echo $prefix;?>cAgree_error').parentNode.style.display = 'none';
            return true;
        }
        
    }
}


</script>

