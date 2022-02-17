<form action='/enterprise/Enterprise/submitMigrateUser' method="post" name="RegistrationForm" id="RegistrationForm" >
   <!--<div class="lineSpace_25">&nbsp;</div>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
	 <div class="r2_2" style="color:#666666">All fields marked with <span class="redcolor">*</span> are Required</div>
	 <div class="clear_L"></div>
      </div>
   </div>
   <div class="lineSpace_25">&nbsp;</div>-->
   <div class="lineSpace_8">&nbsp;</div>
   <!--New_User_Panel-->
   <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Business Information</div>
   <div class="grayLine"></div>
   <div class="lineSpace_20">&nbsp;</div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">College/Institute/University name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
             <input type="text" id="busiCollegeName" name="busiCollegeName" size="30" validate="validateStr" minlength="3" maxlength="100" tip="busiCollegeName" caption="College/Institute/University name" required="true">
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "busiCollegeName_error"></div>
	 </div>
      </div>
   </div>

   <div class="lineSpace_13">&nbsp;</div>
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
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
            <div class="r2_2 errorMsg" id="c_categories_error"></div>
	 </div>
      </div>
   </div-->
   <div class="lineSpace_13">&nbsp;</div>

   <div class="lineSpace_13">&nbsp;</div>
   <div class="row">
      <div style="display: inline;float:left; width:100%">
	 <div class="r1_1 bld">Business Type:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <select type="text" name="busiType" id="busiType" validate="validateSelect" required="true" caption="Business Type" minlength="1" maxlength="100" >
	       <option value="">Select</option>
	       <option value="College">College</option>
	       <option value="Institute">Institute</option>
	       <option value="University">University</option>
	       <option value="Consultant">Consultant</option>
	       <option value="Tutor">Tutor</option>
	       <option value="Others">Others</option>
	    </select>
	    <input type="text" id="otherBusiType" name="otherBusiType" style="display:none" validate="validateStr" minlength="2" maxlength="20">
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id="busiType_error"></div>
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id="otherBusiType_error"></div>
	 </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>


   <div class="bld fontSize_14p OrgangeFont" style="padding-left:10px;">Contact Information</div>
   <div class="grayLine"></div>
   <div class="lineSpace_20">&nbsp;</div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Contact Address:&nbsp;</div>
	 <div class="r2_2">
	    <textarea name="contact_address" id="contact_address" maxlength="500" minlength="3" validate="validateStr" caption="Contact Address"></textarea>
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "contact_address_error"></div>
	 </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

    <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Pin Code:&nbsp;&nbsp;</div>
	 <div class="r2_2">
	    <input name="pincode" type="text" id="pincode" maxlength="10" minlength="5"  size="30" validate="validateInteger" caption="Pin Code" />
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "pincode_error"></div>
	 </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>

  <!--<div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Phone No:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
	 <div class="r2_2">
	    <input name="mobile" type="text" id="mobile"  maxlength="15" minlength="5" size="30" validate="validateInteger" caption="Phone Number" tip="phone_id" required="true" value="<?php echo $user['mobile'];?>"/>
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "mobile_error"></div>
	 </div>
      </div>
   </div>
   <div class="lineSpace_13">&nbsp;</div>-->
   <!--Add Privacy checkboxes -->
	<div class="lineSpace_13">&nbsp;</div>
   
     <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1 bld">Privacy Settings:&nbsp;</div>
	 <div class="r2_2">
		<span style="position:relative; top:2px"><input type="checkbox" id = "viamobile" name = "viamobile" value = "mobile"/></span> Contact me on mobile for education products <br/><br/>
		<span style="position:relative; top:2px"><input type="checkbox"  id = "viaemail" name = "viaemail" value = "email"/></span> Contact me via email for education products<br/><br/>
		<span style="position:relative; top:2px"><input type = "checkbox" id = "newsletteremail" name = "newsletteremail" value = "newsletteremail"/></span> Send me Shiksha Newsletter email
	</div>	 
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div class="r2_2 errorMsg" id= "mobile_error"></div>
	 </div>
      </div>
   </div>
   
   <div class="lineSpace_13">&nbsp;</div>
   <!--Add Privacy checkboxes -->
   <div class="lineSpace_13">&nbsp;</div>
   <div class="grayLine"></div>
   <div class="lineSpace_18">&nbsp;</div>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
         <div class="r2_2 fontSize_12p">
	    <input type="checkbox" id="agree" /> I agree to the
	    <a href="javascript:void(0);" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a>  and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a>
	 </div>
	 <div class="clear_L"></div>
	 <div class="row errorPlace" style="margin-top:2px;">
	    <div class="r1_1">&nbsp;</div>
	    <div id="agree_error" class="r2_2 errorMsg"></div>
	 </div>
      </div>
   </div>
   <div class="lineSpace_18">&nbsp;</div>

   <div class="grayLine"></div>
   <div class="lineSpace_18">&nbsp;</div>

   <div class="row">
      <div style="display: inline; float:left; width:100%">
	 <div class="r1_1">&nbsp;</div>
	 <div class="r2_2">
	    <div class="buttr3">
	       <button class="btn-submit7 w21" type="submit" id="submitbutton" onclick="return validateForm();">
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
	    <div class="clear_L"></div>
	 </div>
	 <div class="clear_L"></div>
      </div>
   </div>
   <div class="lineSpace_18">&nbsp;</div>
   <!--New_User_Panel-->
</form>

<?php
   $this->load->helper('url');
?>
<script>
   //var completeCategoryTree=eval(<?php echo $completeCategoryTreeIndia; ?>);
   //var completeCategoryTreeIndia=eval(<?php echo $completeCategoryTreeIndia; ?>);
   //var completeCategoryTreeAbroad=eval(<?php echo $completeCategoryTreeAbroad; ?>);
   //getCategories(false);
   addOnFocusToopTip(document.getElementById('RegistrationForm'));
   addOnBlurValidate(document.getElementById('RegistrationForm'));
   fillProfaneWordsBag();
   function validateForm()
   {
	 var obj= document.getElementById('RegistrationForm');
	 var flag =  validateFields(obj);
	 var catFlag = validateCatCombo('c_categories',10,1);
	 var flagA = validateAgree();
	 if(flag==true && catFlag==true && flagA==true)
	 {
	       return true
	 }
	 else
	 {
	       return false;
	 }
   }

   function validateAgree()
   {
	 if (document.getElementById('agree').checked==true)
	 {
	       document.getElementById('agree_error').parentNode.style.display= "none";
	       document.getElementById('agree_error').innerHTML = "";
	       return true;
	 }
	 else
	 {
	       document.getElementById('agree_error').parentNode.style.display= "inline";
	       document.getElementById('agree_error').innerHTML = "Please agree to Terms and Conditions.";
	       return false;
	 }
   }
</script>
