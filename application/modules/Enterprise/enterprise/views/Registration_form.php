<form enctype="multipart/form-data" action='<?php echo SITE_URL_HTTPS."enterprise/Enterprise/submitRegister"?>' method="post" name="RegistrationForm" id="RegistrationForm" >
   <!--<div class="lineSpace_25">&nbsp;</div>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
	 <div class="r2_2" style="color:#666666">All fields marked with <span class="redcolor">*</span> are Required</div>
	 <div class="clear_L"></div>
      </div>
   </div>
   <div class="lineSpace_25">&nbsp;</div>-->
   <input type = "hidden" name = "enterpriseurl" id = "enterpriseurl" value = ""/>
   <input type = "hidden" name = "enterpriseresolution" id = "enterpriseresolution" value = ""/>

   <div class="spacer8 clearFix"></div>
   <!--New_User_Panel-->
   <div id="entBubbleTipIEHack-hrd" class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Account Information</div>
   <div class="grayLine"></div>
   <div class="spacer20 clearFix"></div>

   <div class="row">
      <div style="display:inline;float:left;width:100%;">
	 <div class="r1_1 bld">Login Email Id:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input type="text" name="email" id="email" tip="email_id" size="30" maxlength="125" minlength="5" validate="validateEmail" blurMethod="checkAvailability(this.value,'email')" caption="Email Id"required="true"/>
	    <!--<a href="#" name="checkavailability" id="checkavailability" class="BlueFont" onClick="checkAvailability(document.getElementById('email').value,'email')" style="text-decoration:underline">Check Availability..</a>-->
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "email_error"></div>
	    <div class="clear_L"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Display Name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input name="displayname" id="displayname" type="text" size="30" tip="displayname_id1" maxlength="25" minlength="3" blurMethod="checkAvailability(this.value,'displayname')" required="true" caption="Display Name" />
	    <!--<a href="javascript:void(0);" name="checkavailability" id="checkavailability" class="BlueFont" onClick="checkAvailability(document.getElementById('displayname').value,'displayname')" style="text-decoration:underline">Check Availability..</a>-->
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "displayname_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input  name="passwordr" id="passwordr" type="password" size="30" tip="password_id" maxlength="25" minlength="6" validate="validateStr" required="true" caption="Password" />
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "passwordr_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Confirm Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input   name="confirmpassword" id="confirmpassword" type="password" size="30" maxlength="25" minlength="6" tip="confirmpassword_id" caption="Confirm Password" blurMethod="confirmPasswordEnterprise(this)"  />
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "confirmpassword_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Business Information</div>
   <div class="grayLine"></div>
   <div class="spacer20 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">College/Institute/University name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
             <input type="text" id="busiCollegeName" name="busiCollegeName" size="30" validate="validateStr" minlength="3" maxlength="100" tip="busiCollegeName" caption="College/Institute/University name" required="true">
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "busiCollegeName_error"></div>
	 </div>
      </div>
   </div>

   <div class="spacer10 clearFix"></div>
   <!--div class="row">
      <div style="display: inline;float:left; width:100%">
	 <div class="r1_1 bld">Category of Courses offered:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
		 <div id="c_countries_combo">
			<span style="padding-left:5px"><input checked type="radio" name="siI" onclick="changeCategoryTree(); " id="study_india" value="study_india" style="position:relative;top:2px" /> Study in India</span>
			<span style="padding-left:5px"><input type="radio" onclick="changeCategoryTree();" id="study_abroad" value="study_abroad"  name="siI" style="position:relative;top:2px" /> Study Abroad</span>
		</div>
	    <div style="padding-top:5px" id="c_categories_combo"></div>
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
            <div class="r2_2 errorMsg" id="c_categories_error"></div>
	 </div>
      </div>
   </div-->
   <div class="spacer10 clearFix"></div>

   <div class="spacer10 clearFix"></div>
   <div class="row">
      <div style="display: inline;float:left; width:100%">
	 <div class="r1_1 bld">Business Type:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
     <div class="r2_2">
         <select type="text" name="busiType" id="busiType" validate="validateSelect" required="true" caption="Business Type" minlength="1" maxlength="101" onchange="checkOtherBusinessType('otherBusiType');">
	       <option value="">Select</option>
	       <option value="College">College</option>
	       <option value="Institute">Institute</option>
	       <option value="University">University</option>
	       <option value="Consultant">Consultant</option>
	       <option value="Tutor">Tutor</option>
               <option value="Other">Other</option>
	    </select>
	    <input type="text" id="otherBusiType" name="otherBusiType" style="display:none" validate="validateStr" minlength="2" maxlength="20">
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id="busiType_error"></div>
	 </div>
        <div class="row errorPlace" style="margin-top:2px;">
        <div class="r1_1">&nbsp;</div>
        <div class="r2_2 errorMsg" id="otherBusiType_error"></div>
        </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>


   <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Contact Information</div>
   <div class="grayLine"></div>
   <div class="lineSpace_20">&nbsp;</div>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Contact Name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input  name="contactName" id="contactName" type="text"  size="30" tip="contactname_id" maxlength="100" minlength="3" validate="validateStr" caption="Contact Name" required="true" />
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
            <div class="r2_2 errorMsg" id= "contactName_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Contact Address:&nbsp;</div>
	 <div class="r2_2">
	    <textarea name="contact_address" id="contact_address" maxlength="500" minlength="3" validate="validateStr" caption="Contact Address"></textarea>
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "contact_address_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Country:&nbsp;</div>
	 <div class="r2_2">
             <select id="country" name="countries" onChange="setPhoneLengthAndTip();getCitiesForCountry();check_mobile_india();" style="width:100px" validate="validateSelect" minlength="1" maxlength="100" caption="Country">
	       <?php 
		  foreach($countryList as $country) :
		  $countryId = $country['countryID'];
		  $countryName = $country['countryName'];
		  if($countryId == 1) { continue; }
		  $selected = "";
		  if($countryId == 2) { $selected = "selected='selected'"; }
	       ?>
	       <option value="<?php echo $countryId; ?>" <?php  echo $selected; ?>><?php echo $countryName; ?></option>
	       <?php endforeach; ?>
	    </select>

	    <!--<select style="width:140px" id="countries" name="countries" onChange= "checkCountry('countries','cities','othercountry','othercity');" validate="validateCombo" onblur="addOnBlurValidateUser(this)"></select>-->
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
            <div class="r2_2 errorMsg" id="country_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">

	  <div class="r1_1 bld">City:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
             <select style="width:140px" id="cities" name="cities" validate="validateSelect" required="true" minlength="1" maxlength="100" caption="City"  onChange="checkCity(this, 'updateInstitutes');" >
             </select>
             <input type="text" validate="validateStr" maxlength="25" required=true  minlength="2" name="otherCity" id="cities_other" value="" style="display:none" caption="City Name"/>
	 </div>	

	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id="cities_error"></div>
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id="cities_other_error"></div>
	 </div>
	 <div class="clearFix"></div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>

    <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Pin Code:&nbsp;&nbsp;</div>
	 <div class="r2_2">
	    <input name="pincode" type="text" id="pincode" maxlength="10" minlength="5"  size="30" validate="validateInteger" caption="Pin Code" />
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "pincode_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>
 
  <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">ISD Code:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
		 <select name="isdCode" id='isdCode' onchange="setPhoneLengthAndTip();">
			<?php foreach($finalIsdCodes as $key=>$val):?>
				<option value="<?php echo $key;?>" <?php if($key == 91) echo "selected";?>><?php echo $val;?></option>
			<?php endforeach;?>
		 </select>
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "isdcode_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>
   
  <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Phone No:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input name="mobile" type="text" id="mobile"  maxlength="10" minlength="10" size="30" validate="validateMobileInteger" caption="Phone Number" tip="phone_id_india" required="true"/>
	 </div>
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "mobile_error"></div>
	 </div>
      </div>
   </div>
   <div class="spacer10 clearFix"></div>
     
     <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Privacy Settings:&nbsp;</div>
	 <div class="r2_2">
		<span style="position:relative; top:2px"><input type="checkbox" id = "viamobile" name = "viamobile" value = "mobile"/></span> Contact me on mobile for education products <br/><br/>
		<span style="position:relative; top:2px"><input type="checkbox"  id = "viaemail" name = "viaemail" value = "email"/></span> Contact me via email for education products<br/><br/>
		<span style="position:relative; top:2px"><input type = "checkbox" id = "newsletteremail" name = "newsletteremail" value = "newsletteremail"/></span> Send me Shiksha Newsletter email
	</div>	 
	 <div class="clearFix"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "mobile_error"></div>
	 </div>
      </div>
   </div>
   
   <div class="spacer10 clearFix"></div>
   <!--   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
	 <div class="r2_2 bld">Type in the characters you see in the picture below</div>
	 <div class="clear_L"></div>
      </div>
   </div>
   <div class="lineSpace_18">&nbsp;</div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
	 <div class="r2_2">
	    <img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>" width="100" height="40"  id="registerCaptacha"/>

	    <input type="text" name="securityCode" id="securityCode" validate="" maxlength="5" minlength="5" required="1"/>
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div id="securityCode_error" class="r2_2 errorMsg"></div>
	 </div>
      </div>
   </div>
   <div class="lineSpace_18">&nbsp;</div>-->

   <div class="grayLine"></div>
   <div class="spacer20 clearFix"></div>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
         <div class="r2_2 fontSize_12p">
	    <input type="checkbox" id="agree" /> <p class="cmsPolicy">Yes, I have read and provide my consent for my data to be processed for the purposes as mentioned in the
	    <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a>&nbsp;and the&nbsp;
	    <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a></p><br>
	    
	    <div class="row errorPlace" style="margin-top:2px;">
	    
	    <div id="agree_error" class="r2_2 errorMsg"></div>
	 	</div>
     <div class="spacingCol">
	    <input type="checkbox" id="agree_contacted" data-toggle ="tooltip" title="" />
           
	      <div class="cmsPolicy">I agree to be contacted for service related information and promotional purposes. <i class="d-info"></i> 
            <div class="input-helper">
			<div class="up-arrow"></div>
			<div class="helper-text">I can edit my communication preferences at any time in “My Profile“ section and/or may withdraw and/or restrict my consent in full or in part.</div>
		</div>
	      </div><br>
         	
	 	<div class="row errorPlace" style="margin-top:2px;">
	    
	    <div id="agree_error_contacted" class="r2_2 errorMsg"></div>
	</div>
	 	</div>

	 	</div>
	 <div class="clearFix"></div>
	 
	  </div>
   </div>
   <div class="spacer20 clearFix"></div>

   <div class="grayLine"></div>
   <div class="spacer20 clearFix"></div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
	 <div class="r2_2">
	    <div class="buttr3">
	       <button class="btn-submit7 w21" type="button" id="submitbutton" onclick="validateForm(this.form);">
		  <div class="btn-submit7"><p class="btn-submit8 btnTxtBlog">Create my account</p></div>
	       </button>
	    </div>
<?php $redirectLocation = "/enterprise/Enterprise";
	if ( isset($_SERVER['HTTP_REFERER']) && ($_SERVER['HTTP_REFERER']!="") )
		$redirectLocation = $_SERVER['HTTP_REFERER'];
?>
   <div class="buttr2">
       <button class="btn-submit11 w4" value="cancel" type="button" onClick="var status = confirm('Do you really like to Cancel');if(status == true){location.replace('<?php echo $redirectLocation;?>')};" >
	 <div class="btn-submit11"><p class="btn-submit12">Cancel</p></div>
      </button>
   </div>
	    <div class="clearFix"></div>
	 </div>
	 <div class="clearFix"></div>
      </div>
   </div>
   <div class="spacer20 clearFix"></div>
   <!--New_User_Panel-->
</form>

<?php
   $this->load->helper('url');
?>
<script>
	var RECAT_ENTERPRISE_REGISTRATION = 'true';
   //var completeCategoryTree=eval(<?php echo $completeCategoryTreeIndia; ?>);
   //var completeCategoryTreeIndia=eval(<?php echo $completeCategoryTreeIndia; ?>);
   //var completeCategoryTreeAbroad=eval(<?php echo $completeCategoryTreeAbroad; ?>);
   //getCategories(false);
   var SITE_URL_HTTPS = "/";
   var SITE_URL = "/";
   addOnFocusToopTip(document.getElementById('RegistrationForm'));
   addOnBlurValidate(document.getElementById('RegistrationForm'));
   fillProfaneWordsBag();
   //getCitiesForCountryListAnotherValueName(0);
   function check_mobile_india() {
      if (document.getElementById('mobile') && document.getElementById('mobile').value != '') {
		     document.getElementById('mobile').onblur();
		}
   }
   
   function setPhoneLengthAndTip() {
	  var country = document.getElementById('country').value;
	  var isd_code = $j('#isdCode').val();
	  if (isd_code == '91-2')
	  {
		 //alert(country);
		 document.getElementById('mobile').setAttribute("maxlength", 10);
		 document.getElementById('mobile').setAttribute("minlength", 10);
		 document.getElementById('mobile').setAttribute("tip", "phone_id_india");
	  }
	  else
	  {
		 //alert(country);
		 document.getElementById('mobile').setAttribute("maxlength", 20);
		 document.getElementById('mobile').setAttribute("minlength", 6);
		 document.getElementById('mobile').setAttribute("tip", "phone_id_abroad");
	  }
   }
   
   function validateForm(obj)
   {
	 document.getElementById('enterpriseurl').value = location.href;
	 document.getElementById('enterpriseresolution').value = screen.width +'X'+ screen.height;
	 checkAvailabilityEnt(document.getElementById('displayname').value,'displayname');
	 checkAvailabilityEnt(document.getElementById('email').value,'email');
	 validateForm1();
   }
   function validateForm1()
   {
	 var obj= document.getElementById('RegistrationForm');
	 var flag =  validateFields(obj);
	 var flagE = true;
	 if (document.getElementById('email_error').innerHTML!="")
	 {
	       document.getElementById('email_error').parentNode.style.display = 'inline';
		flagE = false;
	 }
	 if (document.getElementById('displayname_error')!="")
	 {
	       document.getElementById('displayname_error').parentNode.style.display = 'inline';
	       if (document.getElementById('displayname_error').style.color!="green")
	       flagE = false;
	 }
	 var flagP = confirmPasswordEnterprise(document.getElementById('confirmpassword'));
	 //var catFlag = validateCatCombo('c_categories',10,1);
	 var flagA = validateAgree();
	 if(flag==true && /*catFlag==true &&*/ flagP==true && flagE==true && flagA==true)
	 {
	       obj.submit();
	       $j('#submitbutton').attr('disabled',true);
	 }
	 else
	 {
	       return false;
	 }

   }

   function validateAgree()
   {
   	 //flag for two checkbox 
   	 var flag = 0;

	 if (document.getElementById('agree').checked==true)
	 {
	       document.getElementById('agree_error').parentNode.style.display= "none";
	       document.getElementById('agree_error').innerHTML = "";
	       flag = flag +1;
	 }
	 else
	 {
	       document.getElementById('agree_error').parentNode.style.display= "inline";
	       document.getElementById('agree_error').innerHTML = "Please agree to Terms and Conditions.";

	 }

	 if (document.getElementById('agree_contacted').checked==true)
	 {
	       document.getElementById('agree_error_contacted').parentNode.style.display= "none";
	       document.getElementById('agree_error_contacted').innerHTML = "";
	       flag = flag +1;
	 }
	 else
	 {
	       document.getElementById('agree_error_contacted').parentNode.style.display= "inline";
	       document.getElementById('agree_error_contacted').innerHTML = "Please provide permission to be contacted.";
	 }
	 if (flag==2)
	 {
	 	return true;
	 }
	 return false;
   }

   function confirmPasswordEnterprise(obj)
   {
	 if (document.getElementById('passwordr').value != obj.value)
	 {
	       document.getElementById('confirmpassword_error').parentNode.style.display = "inline";
	       document.getElementById('confirmpassword_error').innerHTML = "Password and confirmation password do not match.";
	       return false;
	 }
	 else
	 {
	       document.getElementById('confirmpassword_error').parentNode.style.display = "none";
	       document.getElementById('confirmpassword_error').innerHTML = "";
	       return true;
	 }
   }

   function checkAvailabilityEnt(name,type)
   {
	 name = trim(name);
	 name = name.toString();
     name = escapeHTML(name);
     if(name===false)
     {
         return;
     }
     type = type.toString();
     type = escapeHTML(type);
     if(type===false)
     {
         return;
     }
	 document.getElementById(type).value = name;
	 document.getElementById(type + '_error').innerHTML = "";
	 document.getElementById(type + '_error').parentNode.style.display = 'none';
	 if(name == '')
	 {
	       document.getElementById(type + '_error').innerHTML = "Please enter " + type;
	       document.getElementById(type + '_error').parentNode.style.display = 'inline';
	       document.getElementById(type + '_error').style.color = "red";
	       //validateForm1();
	       return false;
	 }
	 if(type == "displayname")
	 {
	       if(name.length < 3)
	       {
		     document.getElementById(type + '_error').innerHTML = "Displayname should be in the range of 3-25 characters";
		     document.getElementById(type + '_error').parentNode.style.display = 'inline';
		     document.getElementById(type + '_error').style.color = "red";
		     //validateForm1();
		     return false;
	       }
	 }
	 var xmlHttp = getXMLHTTPObject();
	 xmlHttp.onreadystatechange=function()
	 {
	       if(xmlHttp.readyState==4)
	       {
		     if(trim(xmlHttp.responseText) != "")
		     {
			   var result = eval("eval("+xmlHttp.responseText+")");

			   if(type == "email")
			   {
				 document.getElementById(type + '_error').innerHTML =  "Another profile with same email id exists.";
				 document.getElementById(type + '_error').style.color = "red";
			   }
			   else{
				 document.getElementById(type + '_error').innerHTML =  "Displayname already exists. Please enter a different name";
				 document.getElementById(type + '_error').style.color = "red";
			   }
			   document.getElementById(type + '_error').parentNode.style.display = 'inline';
		     }
		     else
		     {
			   if(type == "email")
			   {
				 var result = validateEmail(name);
				 if(result == true)
				 {
				       document.getElementById(type + '_error').parentNode.style.display = 'none';
				       document.getElementById(type + '_error').innerHTML = '';
				 }
				 else{
				       document.getElementById(type + '_error').parentNode.style.display = 'inline';
				       document.getElementById(type + '_error').innerHTML = "Please enter a valid email Id";
				       document.getElementById(type + '_error').style.color = "red";
				 }
			   }
			   else{
				 document.getElementById(type + '_error').parentNode.style.display = 'inline';
				 document.getElementById(type + '_error').innerHTML = "Displayname Available";
				 document.getElementById(type + '_error').style.color = "green";
			   }
		     }
		     //validateForm1();
	       }
	 };

	 url = SITE_URL_HTTPS+'user/Userregistration/checkAvailability' + '/' + name + '/' + type ;
	 xmlHttp.open("GET",url,true);
	 xmlHttp.send(null);
   }
   document.getElementById('email').focus();
   var 	QuickSignUp = 0;
   getCitiesForCountry();
</script>
