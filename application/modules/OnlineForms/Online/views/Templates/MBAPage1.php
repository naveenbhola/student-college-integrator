
            <div class="formGreyBox">
                
                <div class="greyBoxCols">

		      <label>First Name: </label>
		      <div class="fieldBoxSmall">
			  <input type='text' name='firstName' id='firstName'  validate="validateDisplayName"   required="true"   caption="First Name"   minlength="1"   maxlength="25"     tip="Enter your first name here. This is the name by which you are known."   value=''  />
			  <?php if(isset($firstName) && $firstName!=""){ ?>
					  <script>
					      document.getElementById("firstName").value = "<?php echo str_replace("\n", '\n', $firstName );  ?>";
					      document.getElementById("firstName").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'firstName_error'></div></div>
		      </div>

                </div>

                <div class="greyBoxCols">
		      <label>Middle Name: </label>
		      <div class="fieldBoxSmall">
		      <input type='text' name='middleName' id='middleName'  validate="validateDisplayName"    caption="Middle Name"   minlength="1"   maxlength="25"     tip="Enter your middle name here. If you do not have a middle name, leave this field blank."   value=''  />
		      <?php if(isset($middleName) && $middleName!=""){ ?>
				      <script>
					  document.getElementById("middleName").value = "<?php echo str_replace("\n", '\n', $middleName );  ?>";
					  document.getElementById("middleName").style.color = "";
				      </script>
				    <?php } ?>
		      <div style='display:none'><div class='errorMsg' id= 'middleName_error'></div></div>
		      </div>
                </div>

                <div class="greyBoxRtCols">
		      <label>Last Name: </label>
		      <div class="fieldBoxSmall">
		      <input type='text' name='lastName' id='lastName'  validate="validateDisplayName"   required="true"   caption="Last Name"   minlength="1"   maxlength="25"     tip="Enter your last name here. This is your family name or surname."   value=''  />
		      <?php if(isset($lastName) && $lastName!=""){ ?>
				      <script>
					  document.getElementById("lastName").value = "<?php echo str_replace("\n", '\n', $lastName );  ?>";
					  document.getElementById("lastName").style.color = "";
				      </script>
				    <?php } ?>
		      <div style='display:none'><div class='errorMsg' id= 'lastName_error'></div></div>
		      </div>
                </div>
		<div class="clearFix"></div>
         </div>


		<div class="clearFix spacer10"></div>
     	<div class="formChildWrapper">
     		<ul>

		    <li>
			<label>Gender: </label>
			<div class="inputRadioSpacer">
			    <input class="radio" type='radio' name='gender' id='gender0'   value='MALE'  checked tip="Choose this radio button, if you are a male."></input>
			    <span onmouseover="showTipOnline('Choose this radio button, if you are a male.',this);" onmouseout="hidetip();">Male</span>
			</div>
			<div class="inputRadioSpacer">
			    <input class="radio" type='radio' name='gender' id='gender1'   value='FEMALE'   tip="Choose this radio button, if you are a female."></input>
			    <span onmouseover="showTipOnline('Choose this radio button, if you are a female.',this);" onmouseout="hidetip();">Female</span>
			</div>
			<div class="inputRadioSpacer">
			    <input class="radio" type='radio' name='gender' id='gender2'   value='TRANSGENDER'   tip="Choose this radio button, if you are a transgender."></input>
			    <span onmouseover="showTipOnline('Choose this radio button, if you are a transgender.',this);" onmouseout="hidetip();">Transgender</span>
			</div>
			<?php if(isset($gender) && $gender!=""){ ?>
					<script>
					    radioObj = document.forms["OnlineForm"].elements["gender"];
					    var radioLength = radioObj.length;
					    for(var i = 0; i < radioLength; i++) {
						    radioObj[i].checked = false;
						    if(radioObj[i].value == "<?php echo strtoupper($gender);?>") {
							    radioObj[i].checked = true;
						    }
					    }
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'gender_error'></div></div>
		    </li>

		    <li><label>Date of Birth: </label>
			  <div class="fieldBoxSmall">
			  <input type='text' name='dateOfBirth' id='dateOfBirth' style="width:100px" readonly maxlength='10' validate="validateOnlineDate"  required="true" caption="Date of Birth" onmouseover="showTipOnline('Enter your Date of Birth',this);" onmouseout="hidetip();"     default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('dateOfBirth'),'dateOfBirth_dateImg','dd/MM/yyyy');" />&nbsp;
			  <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='dateOfBirth_dateImg' onClick="hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('dateOfBirth'),'dateOfBirth_dateImg','dd/MM/yyyy'); return false;" /><script>
					document.getElementById("dateOfBirth").style.color = "#ADA6AD";
				    </script>
		    <?php if(isset($dateOfBirth) && $dateOfBirth!=""){ ?>
				    <script>
					document.getElementById("dateOfBirth").value = "<?php echo str_replace("\n", '\n', $dateOfBirth );  ?>";
					document.getElementById("dateOfBirth").style.color = "";
				    </script>
				  <?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'dateOfBirth_error'></div></div>
			  </div>
		    <label class="labelAuto">Age: </label>
			  <div class="fieldBoxSmall">
			  <input type='text' name='age' id='age' maxlength='2' readonly  style="width:100px"    caption="Age"     default = ''  onClick="hidetip();" />&nbsp;
		    <?php if(isset($age) && $age!=""){ ?>
				    <script>
					document.getElementById("age").value = "<?php echo str_replace("\n", '\n', $age );  ?>";
					document.getElementById("age").style.color = "";
				    </script>
				  <?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'age_error'></div></div>
			  </div>
		    </li>

		    <li>
		    <label>Email Address: </label>
		    <div class="fieldBoxSmall">
			<input type='text' name='email' id='email'  validate="validateEmail"   required="true"   caption="Email Address"   minlength="2"   maxlength="200"        value=''  readonly/>
			<?php if(isset($email) && $email!=""){ ?>
					<script>
					    document.getElementById("email").value = "<?php echo str_replace("\n", '\n', $email );  ?>";
					    document.getElementById("email").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'email_error'></div></div>
		    </div>

		    <label class="labelAuto" style="font-weight: normal">Alternate Email:<span style="color:#808080">(optional)</span></label>
		    <div class="fieldBoxSmall">
			<input type='text' name='altEmail' id='altEmail'  validate="validateEmail"    caption="Alternate Email"   minlength="2"   maxlength="200"       value='' />
			<?php if(isset($altEmail) && $altEmail!=""){ ?>
					<script>
					    document.getElementById("altEmail").value = "<?php echo str_replace("\n", '\n', $altEmail );  ?>";
					    document.getElementById("altEmail").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'altEmail_error'></div></div>
		    </div>
		    </li>
                

                <?php if($courseId > 0 && $courseId!=280191 && $courseId!=244277){ ?>

		    <li>
		    	<label>Course Applied For:</label>
		    	<div class="flLt" style="padding-top:3px"> 
                    <span style="max-width:375px;" class="flLt" name='courseName' id='courseName' caption="Course Name"  title="Course name">
                        <?php echo str_replace("\n", '\n', $courseName );  ?>
                    </span>
					<input type='hidden' name='courseName' id='courseName'  value="<?php echo str_replace("\n", '\n', $courseName );  ?>" />
            		<div style='display:none'><div class='errorMsg' id= 'courseName_error'></div></div>
                </div>
		    	
				<?php if($courseCode) { ?>
                <div class="labelSpacer flLt" style="padding-top:3px">
            		<strong class="flLt">Code: &nbsp;</strong>
            		<span class="flLt" name='courseCode' id='courseCode'  caption="Course Code"   title="Course Code">
                		<?php echo str_replace("\n", '\n', $courseCode );  ?>
                	</span>
                    <div style='display:none'><div class='errorMsg' id= 'courseCode_error'></div></div>
              	</div>
				<?php } ?>
				<input type='hidden' name='courseCode' id='courseCode'  value="<?php echo str_replace("\n", '\n', $courseCode );  ?>" />
		    </li>

                <?php } ?>


		    <li><label>Marital Status: </label>
			<div class="inputRadioSpacer">
			    <input class="radio" type='radio' name='maritalStatus' id='maritalStatus0'   value='SINGLE'  checked tip="If you are not married yet or are divorced, choose this radio button."></input>
			    <span onmouseover="showTipOnline('If you are not married yet or are divorced, choose this radio button.',this);" onmouseout="hidetip();">Single</span>
			</div>
			<div class="inputRadioSpacer">
			    <input class="radio" type='radio' name='maritalStatus' id='maritalStatus1'   value='MARRIED'  tip="If you are married, choose this radio button." ></input>
			    <span onmouseover="showTipOnline('If you are married, choose this radio button.',this);" onmouseout="hidetip();">Married</span>
			</div>
			<div class="inputRadioSpacer">
			    <input class="radio" type='radio' name='maritalStatus' id='maritalStatus2'   value='SEPARATED'  tip="If you are not living with your spouse." ></input>
			    <span onmouseover="showTipOnline('If you are not living with your spouse.',this);" onmouseout="hidetip();">Separated</span>
			</div>
		    <?php if(isset($maritalStatus) && $maritalStatus!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["maritalStatus"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $maritalStatus;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'maritalStatus_error'></div></div>
		    </li>
		    <li><label>Application Category: </label>
			<div class="inputRadioSpacer">
			  <input class="radio" type='radio' name='applicationCategory' id='applicationCategory0'   value='SC'    tip="Students, who belong to Scheduled Castes, choose this radio button."></input><span onmouseover="showTipOnline('Students, who belong to Scheduled Castes, choose this radio button.',this);" onmouseout="hidetip();">SC</span>
			</div>
			<div class="inputRadioSpacer">
			  <input class="radio" type='radio' name='applicationCategory' id='applicationCategory1'   value='ST'     tip="Students, who belong to Scheduled Tribes, choose this radio button."></input><span onmouseover="showTipOnline('Students, who belong to Scheduled Tribes, choose this radio button.',this);" onmouseout="hidetip();">ST</span>
			</div>
			<div class="inputRadioSpacer">
			  <input class="radio" type='radio' name='applicationCategory' id='applicationCategory2'   value='OBC'     tip="Students, who belong to Other Backward Classes, choose this radio button."></input><span onmouseover="showTipOnline('Students, who belong to Other Backward Classes, choose this radio button.',this);" onmouseout="hidetip();">OBC</span>
			</div>
			<div class="inputRadioSpacer">
			  <input class="radio" type='radio' name='applicationCategory' id='applicationCategory3'   value='DEFENCE'     tip="Students, who belong to any of the following categories, choose this radio button:<br/>- Widows/wards of Defence personnel killed in action.<br/>- Wards of serving personnel and ex-servicemen disabled in action.<br/>- Widows/wards of Defence personnel who died in peace- time with death attributable to military service.<br/>- Wards of Defence personnel disabled in peace- time with disability attributable to military service.<br/>- Wards of ex-servicemen and serving personnel who are in receipt of Gallantry Award.<br/>- Wards of ex-servicemen.<br/>- Wards of serving personnel."></input><span onmouseover="showTipOnline('Students, who belong to any of the following categories, choose this radio button:<br/>- Widows/wards of Defence personnel killed in action.<br/>- Wards of serving personnel and ex-servicemen disabled in action.<br/>- Widows/wards of Defence personnel who died in peace- time with death attributable to military service.<br/>- Wards of Defence personnel disabled in peace- time with disability attributable to military service.<br/>- Wards of ex-servicemen and serving personnel who are in receipt of Gallantry Award.<br/>- Wards of ex-servicemen.<br/>- Wards of serving personnel.',this);" onmouseout="hidetip();">Defence</span>
			</div>
			<div class="inputRadioSpacer">
			  <input class="radio" type='radio' name='applicationCategory' id='applicationCategory4'   value='HANDICAPPED'     tip="Students, who have physical or sensory impairment, choose this radio button."></input><span onmouseover="showTipOnline('Students, who have physical or sensory impairment, choose this radio button.',this);" onmouseout="hidetip();">Handicapped</span>
			</div>
			<div class="inputRadioSpacer">
			  <input class="radio" type='radio' name='applicationCategory' id='applicationCategory5'   value='GENERAL'  checked  tip=""></input><span>General</span>
			</div>
		    <?php if(isset($applicationCategory) && $applicationCategory!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["applicationCategory"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $applicationCategory;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'applicationCategory_error'></div></div>
		    </li>
			
		    <li>
			<label>Nationality: </label>
			<div class="float_L">
			    <select name='nationality' id='nationality'    onmouseover="showTipOnline('Mention the nationality mentioned in your passport or nationality corresponding to the country you are a citizen of.',this);" onmouseout='hidetip();'     required="true"  >
			    <?php foreach($nationalities as $anationality): ?>
				<option value='<?php echo strtoupper($anationality); ?>' <?php if(strtoupper($anationality) == 'INDIAN') echo "selected='selected'"; ?>><?php echo strtoupper($anationality); ?></option>
				<?php endforeach; ?>
				</select>
			    <?php if(isset($nationality) && $nationality!=""){ ?>
				    <script>
					var selObj = document.getElementById("nationality"); 
					var A= selObj.options, L= A.length;
					while(L){
					    if (A[--L].value == "<?php echo $nationality;?>"){
						selObj.selectedIndex= L;
						L= 0;
					    }
					}
				    </script>
				  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'nationality_error'></div></div>
			</div>
		    </li>

		    <li>
			  <label>Religion: </label>
			  <div class="fieldBoxSmall">
				<?php
					createStructuredField('religion','Choose the religion you adhere to at the moment.','Religion',2,200,'true',134,$religion,$religion_array);
				?>
			  </div>
		    </li>
			
			 <li>  <label class="">Blood Group: </label>
			  <div class="fieldBoxSmall">
			  <input type='text' name='bloodGroup' id='bloodGroup' required="true" maxlength='20' minlength='2' validate="validateStr" style="width:100px"   caption="Blood Group" onmouseover="showTipOnline('Enter your Blood Group. Enter NA if you don\'t know the blood group',this);" onmouseout="hidetip();"     default = ''  onClick="hidetip();" />&nbsp;
		    <?php if(isset($bloodGroup) && $bloodGroup!=""){ ?>
				    <script>
					document.getElementById("bloodGroup").value = "<?php echo str_replace("\n", '\n', $bloodGroup );  ?>";
					document.getElementById("bloodGroup").style.color = "";
				    </script>
				  <?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'bloodGroup_error'></div></div>
			  </div>
		    <label class="labelAuto">Mother Tongue: </label>
			  <div class="fieldBoxSmall">
			  <input type='text' name='motherTongue' id='motherTongue' maxlength='50' minlength='2'  validate="validateStr" style="width:100px"   required="true" caption="mother tongue langauge" onmouseover="showTipOnline('Enter your mother tongue langauge',this);" onmouseout="hidetip();"     default = ''  onClick="hidetip();" />&nbsp;
		    <?php if(isset($motherTongue) && $motherTongue!=""){ ?>
				    <script>
					document.getElementById("motherTongue").value = "<?php echo str_replace("\n", '\n', $motherTongue );  ?>";
					document.getElementById("motherTongue").style.color = "";
				    </script>
				  <?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'motherTongue_error'></div></div>
			  </div>
		    </li>
                
		    <li>
			<label>Add Photo: </label>
			<span class="flLt">
			    <input type='file' name='userApplicationfile[]' id='profileImage'    caption="Image"     onmouseover="showTipOnline('Post your recent color photograph on white background here. The maximum image size allowed is 2 MB.',this);" onmouseout="hidetip();"   size="30"/>
			    <input type='hidden' name='profileImageValid' value='extn:jpg,jpeg,png|size:5'>
			    &nbsp;<a id="imageGuideline" href="javascript:void(0)" class="imgGuideline" onmouseover="showTipOnline('- Image specifications: Passport size colour photograph (4.5 X 3.5 cm). <br>- The image size should not be more than 2 MB.',this);" onmouseout="hidetip();">View image guidelines</a><br />
			    <span class="imageSizeInfo">(Image dimention :4.5 X 3.5 cm, Image Size : Maximum 2 MB)</span>
			    <div style='display:none'><div class='errorMsg' id= 'profileImage_error'></div></div>
			</span>
		</li>
		
		
		
		
		<li>
		    <label>Other colleges you are applying to: </label>
			    <div class="float_L mr8" style="width:200px;">
			    <input style="width:100%;" class="textBoxLarge" type='text' name='otherCollege1' id='otherCollege1' maxlength='100' minlength='2' validate="validateStr"    caption="College name"   tip="Please enter 3 other colleges where you are applying to"  value='' />
					<?php if(isset($otherCollege1) && $otherCollege1!=""){ ?>
						<script>
						    document.getElementById("otherCollege1").value = "<?php echo str_replace("\n", '\n', $otherCollege1 );  ?>";
						    document.getElementById("otherCollege1").style.color = "";
						</script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= 'otherCollege1_error'></div></div>
		    </div>

			    <div class="float_L mr8" style="width:200px;">
			    <input style="width:100%;" class="textboxLarge" type='text' name='otherCollege2' id='otherCollege2' maxlength='100' minlength='2' validate="validateStr"    caption="College name"  tip="Please enter 3 other colleges where you are applying to"   value=''  />
			    <?php if(isset($otherCollege2) && $otherCollege2!=""){ ?>
			    
					    <script>
						document.getElementById("otherCollege2").value = "<?php echo str_replace("\n", '\n', $otherCollege2 );  ?>";
						document.getElementById("otherCollege2").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'otherCollege2_error'></div></div>
			    </div>

			    <div class="float_L mr8" style="width:200px;">
			    <input style="width:100%;" class="textboxLarge"  type='text' name='otherCollege3' id='otherCollege3' maxlength='100' minlength='2' validate="validateStr"    caption="College name"      tip="Please enter 3 other colleges where you are applying to"   value=''  />
				<?php if(isset($otherCollege3) && $otherCollege3!=""){ ?>
						<script>
						    document.getElementById("otherCollege3").value = "<?php echo str_replace("\n", '\n', $otherCollege3 );  ?>";
						    document.getElementById("otherCollege3").style.color = "";
						</script>
					      <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'otherCollege3_error'></div></div>
			    </div>
			    
			     
			    
			    <div style='display:none'><div class="fieldBoxLarge">
			    <input class="textboxLarge"  type='text' name='otherCollege4' id='otherCollege4' value=''  />
			    
			   
				<?php if(isset($otherCollege4) && $otherCollege4!=""){ ?>
						<script>
						    document.getElementById("otherCollege4").value = "<?php echo str_replace("\n", '\n', $otherCollege4 );  ?>";
						    document.getElementById("otherCollege4").style.color = "";
						</script>
					      <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'otherCollege4_error'></div></div>
			   

                        </div></div>
                    </li>
		    
		    
		    
		    
		    

            </ul>
     	</div>

    <script>
	getCitiesForCountryOnline("",false,"",false);
    </script>
    <script>
    getCitiesForCountryOnlineCorrespondence("",false,"",false);
    </script>

    <?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $city;?>" || A[L].value == "<?php echo $city;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
    <?php } ?>

    <?php if(isset($Ccity) && $Ccity!=""){ ?>
    <script>
	var selObj = document.getElementById("Ccity"); 
	if(selObj){
	      var A= selObj.options, L= A.length;
	      while(L){
		  L = L-1;
		  if (A[L].innerHTML == "<?php echo $Ccity;?>" || A[L].value == "<?php echo $Ccity;?>"){
		      selObj.selectedIndex= L;
		      L= 0;
		  }
	      }
	}
    </script>
    <?php } ?>

    <script>
  function showMoreGroups(groupId,maxAllowed){
	if(document.getElementById(groupId)){
	      for(i=1;i<=maxAllowed;i++){
		  if(document.getElementById(String(groupId)+String(i)).style.display == 'none'){
		      document.getElementById(String(groupId)+String(i)).style.display = '';
		      break;
		  }
	      }
	      if(i==maxAllowed){
		  document.getElementById('showMore'+groupId).style.display = 'none';
	      }
	}
  }if(document.getElementById('courseCode')){
	document.getElementById('courseCode').readonly = true;
  }

  function copyAddressFields(){
	if(document.getElementById('city') && document.getElementById('Ccity')){
		document.getElementById('ChouseNumber').value = document.getElementById('houseNumber').value;
		document.getElementById('CstreetName').value = document.getElementById('streetName').value;
		document.getElementById('Carea').value = document.getElementById('area').value;
		document.getElementById('Cpincode').value = document.getElementById('pincode').value;

		var sel = document.getElementById('country');
		var countrySelected = sel.options[sel.selectedIndex].value;
		var selObj = document.getElementById('Ccountry'); 
		var A= selObj.options, L= A.length;
		while(L){
		    if (A[--L].value== countrySelected){
			selObj.selectedIndex= L;
			L= 0;
		    }
		}

		getCitiesForCountryOnlineCorrespondence("",false,"",false);

		var sel = document.getElementById('city');
		var citySelected = sel.options[sel.selectedIndex].value;
		var selObj = document.getElementById('Ccity'); 
		var A= selObj.options, L= A.length;
		while(L){
		    if (A[--L].value== citySelected){
			selObj.selectedIndex= L;
			L= 0;
		    }
		}

	}
  }
    

  </script>
