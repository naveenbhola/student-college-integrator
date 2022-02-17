<?php
global $citiesforRegistration; 
echo "<script language=\"javascript\"> ";
echo "var REDIRECT_URL_REGISTER = '".$url."';";     
echo "</script>";	  
       
?>
<form enctype="multipart/form-data" onSubmit=" AIM.submit(this, {'onStart' : submitForm, 'onComplete' : showRegistrationResponse})"  action='<?php echo SITE_URL_HTTPS."user/Userregistration/submit"?>' method="post"  name = "RegistrationForm" id = "RegistrationForm">

<input type = "hidden" name = "success" id = "success" value = "<?php echo $success?>"/>
<input type = "hidden" name = "profession" id = "profession" value = ""/>
<input type = "hidden" name = "experience" id = "experience" value = ""/>
<input type = "hidden" name = "education" id = "education" value = ""/>
<input type = "hidden" name = "editform" id = "editform" value = ""/>
<input type = "hidden" name = "requestinfo" id = "requestinfo" value = ""/>
<input type = "hidden" name = "tempvalue" id = "tempvalue" value = ""/>
<input type = "hidden" name = "category" id = "category" value = ""/>
<input type = "hidden" name = "b_1_p" id = "b_1_p" value = "1"/>
<input type = "hidden" name = "resolutionreg" id = "resolutionreg" value = ""/>
<input type = "hidden" name = "refererreg" id = "refererreg" value = ""/>

<div id="RegistrationOverlay" style="position:absolute;display:none;z-index:1000000001;">
	<div id="shadow-container">
        <div class="shadow1">
            <div class="shadow2">
                <div class="shadow3">
                    <div class="container">
							<div style="width:300px">
							<div class="lineSpace_10">&nbsp;</div>	
							<div class="">
								<div class="mar_full_10p">
									  <div class="lineSpace_5">&nbsp;</div>
									  <div class="lineSpace_5">&nbsp;</div>
									  <div class="normaltxt_11p_blk_arial fontSize_12p" id = "responseforadd1"></div>
									  <div class="lineSpace_10">&nbsp;</div>
								</div>		
							</div>
							<div class="lineSpace_10">&nbsp;</div>
							</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
	<div class="lineSpace_8">&nbsp;</div>
	

<?php
   $thisUserGroup = (isset($validateuser[0]['orusergroup'])?$validateuser[0]['orusergroup']:"");
   if( $thisUserGroup == 'privileged'){
   ?>
   <div class="row">
      <div style="display: inline; float:left; width:100%">
         <div class="r1_1 bld">Type of Account to create:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
         <div class="r2_2">

            <input type="radio" name="usergroup" id="cms" value="cms" /> CMS User 
            <br/>
            <!--<span style="margin-left:36px"></span>-->
            <input type="radio" name="usergroup" id="user" value="user" checked /> Shiksha User <span style="margin-left:36px"></span>
            <br/>
            <!--<span style="margin-left:36px"></span>-->
            <input type="radio" name="usergroup" id="privileged" value="privileged" /> Privileged User <span style="margin-left:36px"></span>
         </div>
         <div class="clear_L"></div>
         <div class="lineSpace_8">&nbsp;</div>
      </div>
   </div>
   <?php 
   }
?>

	 <!--New_User_Panel-->
	
<?php if(isset($validateuser[0]['orusergroup']) && $validateuser[0]['orusergroup'] == "requestinfouser")
$requestuser = 1;
else
$requestuser = 0;
if(isset($validateuser[0]['orusergroup']) && ($validateuser[0]['orusergroup'] == "tempuser" ))
$tempuser = 1;
else
$tempuser = 0;
if($editform == 0){ ?>
		<div class="bld fontSize_14p OrgangeFont" style = "padding-left:10px;">Account Information</div>
<div class = "grayLine"></div>
<div class = "lineSpace_20">&nbsp;</div>
<?php } ?>


<?php if($editform == 0){ ?>
	<div class="row">
		<div style="display: inline; float:left; width:100%">
				<div class="r1_1 bld">Login Email Id:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
				<div class="r2_2">
						<input class = "txt_1"  type="text" name = "email"  id = "email" tip="email_id" style = "width:200px" maxlength = "125" required = "1" caption = "email address" blurMethod = "checkAvailability(document.getElementById('email').value,'email')" validate = "validateEmail"/>
				</div>
				<div class="clear_L"></div>			
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1">&nbsp;</div>
					<div class="r2_2 errorMsg" id= "email_error"></div>
					<div class="clear_L"></div>		
				</div>				
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
<?php } ?>

<?php if($editform == 0){
?>
	<div class="row">
            <div style="display: inline; float:left; width:100%">
				<div class="r1_1 bld">Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
				<div class="r2_2">
					<input class = "txt_1" name = "passwordr" id = "passwordr" type = "password" style = "width:200px" tip="password_id"  caption = "password" required = "1" maxlength = "20" minlength = "5" validate = "validateStr"/>
				</div>
				<div class="clear_L"></div>		
				<div class="row errorPlace" style="margin-top:2px;">
					<div class="r1_1">&nbsp;</div>
					<div class="r2_2 errorMsg" id= "passwordr_error"></div>
				</div>		
	    	</div>			
	</div>
	<div class="lineSpace_13">&nbsp;</div>

	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Confirm Password:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<input class = "txt_1"  name = "confirmpassword" id = "confirmpassword" type = "password" validate = "validateStr" blurMethod = "validateConfirmPassword('passwordr','confirmpassword')"  required = "1" caption = "password again" style = "width:200px" maxlength = "20" tip = "confirmpassword_id"/>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "confirmpassword_error"></div>
			</div>		
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
<?php } ?>
 
<?php if($editform == 0){ ?>
	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Display Name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<input class = "txt_1" name = "displayname" id = "displayname" type = "text"  style = "width:200px" tip = "displayname_id" maxlength = "25" minlength = "3"  required = "1" caption = "display name" validate = "validateDisplayName" blurMethod = "checkAvailability(document.getElementById('displayname').value,'displayname')" />
				<a href = "#" name = "checkavailability" id = "checkavailability" class = "BlueFont" onClick = "checkAvailability(document.getElementById('displayname').value,'displayname')" style="text-decoration:underline">Check Availability..</a>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "displayname_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
    <?php }?>

		<div class="bld fontSize_14p OrgangeFont" style = "padding-left:10px;">Academic Information</div>
<div class = "grayLine"></div>
<div class = "lineSpace_20">&nbsp;</div>

<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">You are:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">

<input type="radio" name = "status" id = "School" value = "School" onClick = "openyourdiv('school')" /> School Student 
<span style="margin-left:36px"></span>
<input type="radio" name = "status" id = "College" value = "College" onClick = "openyourdiv('college')" /> College Student <span style="margin-left:36px"></span>
<input type="radio" name = "status" id = "Work" value = "Work" onClick = "openyourdiv('work')" /> Working Professional <span style="margin-left:36px"></span>
			</div>
			<div class="clear_L"></div>
<div class="lineSpace_8">&nbsp;</div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "youare"></div>
			</div>
		</div>
	</div>

<div class="lineSpace_13">&nbsp;</div>
<div class="row">
		<div style="display: inline;float:left; width:100%">
			<div class="r1_1 bld" id = "country">Location where you studied:&nbsp;<span class = "redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<select style = "width:140px" id = "countryofeducation" name = "countryofeducation" onChange= "checkCountry('countryofeducation','citiesofeducation','countryofeducation_other','citiesofeducation_other');" required = "1" validate = "validateSelect" caption = "your country of education">

<?php 
global $countries; 
					foreach($countries as $countryId => $country) {
							$countryName = isset($country['name']) ? $country['name'] : '';
							$countryValue = isset($country['value']) ? $country['value'] : '';
							$countryId = isset($country['id']) ? $country['id'] : '';
					?>
                    <option value = "<?php echo $countryId?>" countryId = "<?php echo $countryValue?>"><?php echo $countryName?></option>
                    <?php } ?>
                    <option value = "Other">Other</option>
				</select>
<input class = "txt_1 fontSize_12p"  name = "countryofeducation_other" type = "text" value = "Enter the country" id = "countryofeducation_other"  minlength = "3" maxlength = "25" size = "20" style = "display:none;" required = "1" validate = "validateStr" onClick = "if(this.value == 'Enter the country') this.value = '';" caption = "your country of education"/>	
				<select style = "width:140px" id = "citiesofeducation" name = "citiesofeducation" onChange= "checkcity('citiesofeducation','citiesofeducation_other');" required = "1" validate = "validateSelect" caption = "your city of education" unrestricted = "true">
                    </select>
                    <script>
                    getCitiesForCountry('',false,'ofeducation');
                    </script>
<input class = "txt_1 fontSize_12p"  name = "citiesofeducation_other" value = "Enter the city" type = "text" id = "citiesofeducation_other" onClick = "if(this.value == 'Enter the city') this.value = '';" validate = "validateStr" required = "1"  minlength = "3" maxlength = "25" size = "20"  style = "display:none" tip = "" caption = "your city of education"/>	

			</div>


			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "countryofeducation_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "citiesofeducation_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "countryofeducation_other_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "citiesofeducation_other_error"></div>
			</div>
		</div>		
	</div>
	<div class="lineSpace_13">&nbsp;</div>

	<div id = "Work&College" style = "display:none">
	<div class="row" id = "Work1">
		<div style="display: inline;float:left; width:100%">
			<div class="r1_1 bld">Experience:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<select style="width:100px;display:none" id = "ExperienceCombo" name = "ExperienceCombo" onChange= "setEducationLevel('ExperienceCombo','experience')" validate = "validateSelect" required = "1" caption = "your years of experiece"></select>				
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "ExperienceCombo_error"></div>
			</div>
		</div>		
	</div>
	<div class="lineSpace_13">&nbsp;</div>
</div>

                    <script>
                    getExperience('ExperienceCombo','WorkingCombo_error',1);
                    </script>
<div id = "insti2" style = "display:none">
	<div class="row" id = "insti1">
		<div style="display: inline;float:left; width:100%">
			<div class="r1_1 bld" id = "InstituteName">School Name:&nbsp;<span class = "redcolor">*</span></div>
			<div class="r2_2">
			<select style = "width:350px" id = "schoolCombo" name = "schoolCombo" onChange= "checkInsti();" onFocus = "checkcityselected();" validate = "validateSelect" required = "1" caption = "school">
<option value = " ">Select</option>
			</select>
<input class = "txt_1  fontSize_12p"  name = "otherinstitute" type = "text" id = "otherinstitute"  maxlength = "100" minlength = "3" size = "20"  style = "display:none" tip = "" required = "1" validate = "validateStr" caption = "institute name" />		
			</div>


			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "schoolCombo_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "otherinstitute_error"></div>
			</div>
		</div>		
	</div>
	<div class="lineSpace_13">&nbsp;</div>
</div>

<div id = "Edu2" style = "display:none">
	<div class="row" id = "Edu1">
		<div style="display: inline;float:left; width:100%">
			<div class="r1_1 bld" id = "EducationLevel">Highest Education Level:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<select  id = "EducationCombo" name = "EducationCombo" required = "1" validate = "validateSelect" caption = "highest education degree"  onChange= "setEducationLevel('EducationCombo','education')"></select>
<input class = "txt_1 fontSize_12p"  name = "otherEducation" type = "text" id = "otherEducation"  style = "display:none" tip = "" validate = "validateStr" required = "1" caption = "your highest education" maxlength = "1000" minlength = "2"/>	
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "EducationCombo_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "otherEducation_error"></div>
			</div>
		</div>		
	</div>
	<div class="lineSpace_13">&nbsp;</div>
</div>

                    <script>
                    getEducationLevel('EducationCombo','otherEducation',1,'main');
                    </script>
	<div id = "PassOutdiv" style = "display:none">
    <div class = "row">
		<div style="float:left; width:100%">
			<div class="r1_1 bld" id = "GraduationYear">Pass Out Year:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<select style = "width:60px" id = "GradYear" name = "GradYear" required = "1" validate = "validateSelect" caption = "your pass-out year">
				</select>
			</div>


			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "GradYear_error"></div>
			</div>
		</div>		
	</div>
	<div class="lineSpace_13">&nbsp;</div>
    </div>

  <div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Education Interest &amp Preferred Location For Higher Studies:&nbsp;<span class="redcolor">*</span></div>
     <div class="r2_2">
        <div id="categorySelect">
        
										<select id="categoryPlace" name = "categoryPlace"style="width:200px;" validate = "validateSelect" caption = "your education interest" required = "1"  >
											<option value="">Select Category</option>
											<option value="Undecided">Presently Undecided</option>
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
													<option value="<?php echo $subCategoryId; ?>" <?php echo $selected; ?>><?php echo $subCategoryName; ?></option>													 
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
										</select>
		    <select style = "width:120px" id = "countryofhighereducation" name = "countryofhighereducation"  onChange= "checkCountry('countryofhighereducation','citiesofhighereducation');">
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
		    <select style = "width:120px" id = "citiesofhighereducation" name = "citiesofhighereducation">    
            </select>
            <script>
            getCitiesForCountry('',true,'ofhighereducation');
            </script>
        </div>
     </div>
     <div class="clear_L"></div>
      <div class="row errorPlace">
     <div class="r1_1">&nbsp;</div>
     <div class="r2_2 errorMsg" id="categoryPlace_error" ></div>
      </div>
   </div>
</div>
	<div class="lineSpace_13">&nbsp;</div>
<?php if($tempuser == 0){?>

				<div class="bld fontSize_14p OrgangeFont" style = "padding-left:10px;">Personal Information</div>
<div class = "grayLine"></div>
<div class = "lineSpace_20">&nbsp;</div>
<?php if($requestuser == 0){ ?>
<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Name:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<input class = "txt_1" name = "firstname" id = "firstname" type = "text"  size = "30" tip = "firstname_id" maxlength = "25" minlength = "3" validate = "validateStr" required = "1" caption = "name"/>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "firstname_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>

<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Age:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
				<input style="width:75px;" type="text" required="true" validate="validateAge" name="DOB" id="DOB"  minlength = "2" maxlength="2" size="15" class="" caption="age field"/>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "DOB_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>


    <div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Gender:&nbsp;<span class="redcolor">*</span>&nbsp;</div>
			<div class="r2_2">
<input type="radio" name = "usergender" id = "Female" value = "Female" />F&nbsp;&nbsp; 
<input type="radio" name = "usergender" id = "Male" value = "Male"/>M
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "gender_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>

<?php } ?>
<?php if($requestuser == 0){ ?>
<div class="row">
		<div style="display: inline;float:left; width:100%">
			<div class="r1_1 bld" id = "country">Residence Location:&nbsp;<span class="redcolor">*</span></div>
			<div class="r2_2">
				<select style = "width:140px" id = "countryofresidence" name = "countryofresidence" onChange= "checkCountry('countryofresidence','citiesofresidence','countryofresidence_other','citiesofresidence_other');" validate = "validateSelect">
<?php 
global $countries; 
					foreach($countries as $countryId => $country) {
							$countryName = isset($country['name']) ? $country['name'] : '';
							$countryValue = isset($country['value']) ? $country['value'] : '';
							$countryId = isset($country['id']) ? $country['id'] : '';
					?>
                    <option value = "<?php echo $countryId?>" countryId = "<?php echo $countryValue?>"><?php echo $countryName?></option>
                    <?php } ?>
                    <option value = "Other">Other</option>
				</select>
<input class = "txt_1 fontSize_12p"  name = "countryofresidence_other" type = "text" id = "countryofresidence_other" value = "Enter the country"  minlength = "3" maxlength = "25" size = "20" validate = "validateStr" required = "1" style = "display:none" onClick = "if(this.value == 'Enter the country') this.value = '';" tip = "" caption = "country of residence"/>	

				<select style = "width:140px" id = "citiesofresidence" name = "citiesofresidence" onChange= "checkcity('citiesofresidence','citiesofresidence_other');" validate = "validateSelect" required = "1" caption = "your city of residence">
				</select>
            <script>
            getCitiesForCountry('',false,'ofresidence');
            </script>
<input class = "txt_1 fontSize_12p"  name = "citiesofresidence_other" type = "text" id = "citiesofresidence_other"  value = "Enter the city" minlength = "3" validate = "validateStr" required = "1" maxlength = "25" size = "20"  onClick = "if(this.value == 'Enter the city') this.value = '';" style = "display:none" tip = "" caption = "your city of residence"/>	
			</div>


			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "countryofresidence_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "citiesofresidence_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "citiesofresidence_other_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "countryofresidence_other_error"></div>
			</div>
		</div>		
	</div>
	<div class="lineSpace_13">&nbsp;</div>

	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Mobile Number:&nbsp;</div>
			<div class="r2_2">
				<input class = "txt_1"  name = "mobile" type = "text" id = "mobile" minlength = "10"  maxlength = "10" size = "30" validate = "validateMobileInteger" tip = "mobile_id" caption = "mobile number"/>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "mobile_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>
    
    <div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Landline Number:&nbsp;</div>
			<div class="r2_2">
                <input id = "landlineext" name = "landlineext" type="text" minlength = "3" maxlength = "5" validate = "validateLandlineInteger" caption = "STD code" style="width:40px" />
				<input class = "txt_1"  name = "landlineno" type = "text" id = "landlineno" minlength = "6"  maxlength = "8" size = "22" validate = "validateLandlineInteger" tip = "landline_id" caption = "landline number"/>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "landlineext_error"></div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "landlineno_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>

<?php } ?>



	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld">Profile Photo:&nbsp;</div>
			<div class="r2_2">

<input type="radio" name = "avtar" id = "upload" value = "upload" onClick = "opendiv(this.value)" tip = "avatar_id"/> Upload Image <span style="margin-left:36px"></span>
<input type="radio" name = "avtar" id = "profilepic" value = "profilepic" onClick = "opendiv(this.value)" tip = "avatar_id" /> Avatar Image <span style="margin-left:36px"></span>
			</div>
			<div class="clear_L"></div>
<div class="lineSpace_8">&nbsp;</div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "avtar_error"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_13">&nbsp;</div>

	<div class="row" id = "selectpic" style = "display:none">
		<div style="display: inline; float:left; width:100%">
<div class="r1_1 bld lightBlack" >Select Avatar from the following&nbsp;</div><br/>
			<div class="r2_2">
<input type="radio" name = "avtar1" id = "profilepic1" value = "/public/images/girlav1.gif" checked />
<img src = "/public/images/girlav1.gif">
<input type="radio" name = "avtar1" id = "profilepic2" value = "/public/images/girlav2.gif"/>
<img src = "/public/images/girlav2.gif">
<input type="radio" name = "avtar1" id = "profilepic4" value = "/public/images/girlav4.gif"/>
<img src = "/public/images/girlav4.gif">
<input type="radio" name = "avtar1" id = "profilepic5" value = "/public/images/girlav5.gif"/>
<img src = "/public/images/girlav5.gif">
<input type="radio" name = "avtar1" id = "profilepic6" value = "/public/images/girlav6.gif"/>
<img src = "/public/images/girlav6.gif">
<div class="lineSpace_13">&nbsp;</div>
<input type="radio" name = "avtar1" id = "profilepic7" value = "/public/images/men3.gif"/>
<img src = "/public/images/men3.gif">
<input type="radio" name = "avtar1" id = "profilepic8" value = "/public/images/men6.gif"/>
<img src = "/public/images/men6.gif">
<input type="radio" name = "avtar1" id = "profilepic9" value = "/public/images/men1.gif"/>
<img src = "/public/images/men1.gif">
<input type="radio" name = "avtar1" id = "profilepic10" value = "/public/images/men4.gif"/>
<img src = "/public/images/men4.gif">
<input type="radio" name = "avtar1" id = "profilepic11" value = "/public/images/men5.gif"/>
<img src = "/public/images/men5.gif">
<div class="lineSpace_13">&nbsp;</div>
			</div>
			<div class="clear_L"></div>
			</div>
	</div>

<div class="row" id = "uploadpic" style = "display:none">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1 bld lightBlack">Upload Photo:&nbsp;</div>
			<div class="r2_2">
<input type="file" name = "myImage[]" id = "myImage"/>
<div class="lineSpace_13">&nbsp;</div>
<div class="normaltxt_11p_blk mar_full_10p">
<div class="lightBlack float_L lineSpace_28">(Maximum size of 1MB)</div>
<div class="buttr2">
								</div>
								<div class="clear_L"></div>
							</div>
			</div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
				<div class="r2_2 errorMsg" id= "mobile_error"></div>
			</div>
		</div>
	</div>
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
		</div>		
	</div>
	<div class="lineSpace_18">&nbsp;</div>	
	
	<div class="grayLine"></div>
	<div class="lineSpace_18">&nbsp;</div>
<?php } ?>	
	
	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1">&nbsp;</div>
			<div class="r2_2 bld">Type in the characters you see in the picture below:</div>
			<div class="clear_L"></div>
		</div>		
	</div>
	<div class="lineSpace_18">&nbsp;</div>
	                
	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1">&nbsp;</div>
          	<div class="r2_2">
		  		<img src="/CaptchaControl/showCaptcha?width=100&height=40&characters=5&randomkey=<?php echo rand(); ?>&secvariable=seccode1" width="100" height="40"  id = "registerCaptacha"/>

				<input type="text" name = "securityCode" id = "securityCode" maxlength = "5" required = "1"/>
            </div>
			<div class="clear_L"></div>
			<div class="row errorPlace" style="margin-top:2px;">
				<div class="r1_1">&nbsp;</div>
			    <div id="securityCode_error" class="r2_2 errorMsg"></div>
			</div>
		</div>
	</div>
	<div class="lineSpace_18">&nbsp;</div>

	<div class="row">
		<div style="display: inline; float:left; width:100%">
			<div class="r1_1">&nbsp;</div>
			<div class="r2_2">
				<input type="checkbox" checked id = "agree"/> I agree to the 
                <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/termCondition')">Terms and Conditions</a> and <a href="javascript:" onclick="return popitup('/shikshaHelp/ShikshaHelp/privacyPolicy')">Privacy Policy</a>
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
				<input type="submit" onclick = "return validateUser(this.form);" value="Create my account" class="homepageJoinNowBtn" style="border:0  none;background-position:-108px -256px;width:126px" />				
			</div>
			<div class="clear_L"></div>
		</div>		
	</div>
	<div class="lineSpace_18">&nbsp;</div>

	<!--New_User_Panel-->
</form>

<?php 
$this->load->helper('url');
     echo "<script language=\"javascript\"> ";
     echo "document.getElementById('editform').value = ".$editform.";";
     echo "document.getElementById('requestinfo').value = ".$requestuser.";";
     echo "document.getElementById('tempvalue').value = ".$tempuser.";";
if($editform == 0)
echo "document.getElementById('email').focus();";

     echo "</script>";
    ?>
<script>
var completeCategoryTree = eval(<?php echo $completeCategoryTree; ?>);
var SITE_URL = '<?php echo base_url()."/"; ?>';
var SITE_URL_HTTPS = '<?php echo SITE_URL_HTTPS?>';
try{
    addOnBlurValidate(document.getElementById('RegistrationForm'));
    addOnFocusToopTip(document.getElementById('RegistrationForm'));
} catch (ex) {
    // throw ex;
}    
fillProfaneWordsBag();
</script>
