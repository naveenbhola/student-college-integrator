
            <div class="formGreyBox">
                
                <div class="greyBoxCols">
		      <label>First Name: <input type='text' name='firstName' id='firstName'  validate="validateDisplayName"   required="true"   caption="First Name"   minlength="2"   maxlength="25"     tip="First Name"   title="Enter First Name"  value='First Name'   default = 'First Name' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></label>
		      <script>
			  document.getElementById("firstName").style.color = "#ADA6AD";
		      </script>
		      <?php if(isset($firstName) && $firstName!=""){ ?>
		      <script>
			  document.getElementById("firstName").value = "<?php echo str_replace("\n", '\n', $firstName );  ?>";
			  document.getElementById("firstName").style.color = "";
		      </script>
		    <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'firstName_error'></div></div>
                </div>

                <div class="greyBoxCols">
		    <label>Middle Name: <input type='text' name='middleName' id='middleName'  validate="validateDisplayName"   required="true"   caption="Middle Name"   minlength="2"   maxlength="25"      title="Enter Middle Name"  value='Middle Name'   default = 'Middle Name' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></label>
		    <script>
			document.getElementById("middleName").style.color = "#ADA6AD";
		    </script>
		    <?php if(isset($middleName) && $middleName!=""){ ?>
		    <script>
			document.getElementById("middleName").value = "<?php echo str_replace("\n", '\n', $middleName );  ?>";
			document.getElementById("middleName").style.color = "";
		    </script>
		  <?php } ?>
		  <div style='display:none'><div class='errorMsg' id= 'middleName_error'></div></div>
                </div>

                <div class="greyBoxRtCols">
		    <label>Last Name: <input type='text' name='lastName' id='lastName'  validate="validateDisplayName"   required="true"   caption="Last Name"   minlength="2"   maxlength="25"      title="Enter Last Name"  value='Last Name'   default = 'Last Name' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></label><script>
					document.getElementById("lastName").style.color = "#ADA6AD";
				    </script>
		    <?php if(isset($lastName) && $lastName!=""){ ?>
				    <script>
					document.getElementById("lastName").value = "<?php echo str_replace("\n", '\n', $lastName );  ?>";
					document.getElementById("lastName").style.color = "";
				    </script>
				  <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'lastName_error'></div></div>
                </div>
		<div class="clearFix"></div>
         </div>



     	<div class="formChildWrapper">
     		<ul>
            	<li>

		    <label>Gender: </label><input type='radio' name='gender' id='gender0'   value='Male'  checked  title="Select Gender" ></input>Male&nbsp;&nbsp;<input type='radio' name='gender' id='gender1'   value='Female'    title="Select Gender" ></input>Female&nbsp;&nbsp;
		    <?php if(isset($gender) && $gender!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["gender"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $gender;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'gender_error'></div></div>
                </li>
                
                <li>
		      <label>Date of Birth: </label>
		      <input type='text' name='dateOfBirth' id='dateOfBirth' readonly maxlength='10'  validate="validateDate"   required="true"   caption="Birth Date"       tip="Enter your Birth Date"   title="Enter your Birth Date"   default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="cal.select($('dateOfBirth'),'sd','yyyy-MM-dd');" />
		      <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="var caldateOfBirth = new CalendarPopup('calendardiv'); caldateOfBirth.select($('dateOfBirth'),'sd','yyyy-MM-dd');" />
		      <script>
					  document.getElementById("dateOfBirth").style.color = "#ADA6AD";
			     </script>
		      <?php if(isset($dateOfBirth) && $dateOfBirth!=""){ ?>
				      <script>
					  document.getElementById("dateOfBirth").value = "<?php echo str_replace("\n", '\n', $dateOfBirth );  ?>";
					  document.getElementById("dateOfBirth").style.color = "";
				      </script>
				    <?php } ?>
		      <div style='display:none'><div class='errorMsg' id= 'dateOfBirth_error'></div></div>
                </li>
                
                <li>

		      <label>Email Address: </label>
		      <input type='text' name='email' id='email'  validate="validateEmail"   required="true"   caption="Email Address"   minlength="2"   maxlength="200"      title="Enter your Email Address"  value=''  />
		      <?php if(isset($email) && $email!=""){ ?>
				      <script>
					  document.getElementById("email").value = "<?php echo str_replace("\n", '\n', $email );  ?>";
					  document.getElementById("email").style.color = "";
				      </script>
				    <?php } ?>
		      <div style='display:none'><div class='errorMsg' id= 'email_error'></div></div>

		      <label class="labelAuto">Alternate Email:</label> 
		      <input type='text' name='altEmail' id='altEmail'  validate="validateEmail"   required="true"   caption="Alternate Email"   minlength="2"   maxlength="200"      title="Enter your Alternate Email Address"  value=''  />
		      <?php if(isset($altEmail) && $altEmail!=""){ ?>
				      <script>
					  document.getElementById("altEmail").value = "<?php echo str_replace("\n", '\n', $altEmail );  ?>";
					  document.getElementById("altEmail").style.color = "";
				      </script>
				    <?php } ?>

		      <div style='display:none'><div class='errorMsg' id= 'altEmail_error'></div></div>
                </li>

                <?php if($courseId > 0){ ?>
                <li>
			<label>Course Applied For:</label>
			<span>Course Name: <input type='text' name='courseName' id='courseName'  validate="validateStr"   required="true"   caption="Course Name"   minlength="2"   maxlength="200"      title="Enter the course name you want to apply"  value=''  readonly=""  /></span>
			<?php if(isset($courseName) && $courseName!=""){ ?>
					<script>
					    document.getElementById("courseName").value = "<?php echo str_replace("\n", '\n', $courseName );  ?>";
					    document.getElementById("courseName").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'courseName_error'></div></div>
			<span class="labelSpacer">Course Code: <input type='text' name='courseCode' id='courseCode'  validate="validateStr"   required="true"   caption="Course Code"   minlength="2"   maxlength="200"      title="Enter the Course Code you want to apply"  value=''  readonly=""  /></span>
			<?php if(isset($courseCode) && $courseCode!=""){ ?>
					<script>
					    document.getElementById("courseCode").value = "<?php echo str_replace("\n", '\n', $courseCode );  ?>";
					    document.getElementById("courseCode").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'courseCode_error'></div></div>
                	
                </li>
                <?php } ?>
                
                <li>
			<label>Marital Status: </label><input type='radio' name='maritalStatus' id='maritalStatus0'   value='Single'  checked ></input>Single&nbsp;&nbsp;<input type='radio' name='maritalStatus' id='maritalStatus1'   value='Married'   ></input>Married&nbsp;&nbsp;<input type='radio' name='maritalStatus' id='maritalStatus2'   value='Separated'   ></input>Separated&nbsp;&nbsp;
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
                
                <li>
			<label>Application Category: </label><input type='radio' name='applicationCategory' id='applicationCategory0'   value='SC'    title="Enter your category" ></input>SC&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory1'   value='ST'    title="Enter your category" ></input>ST&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory2'   value='OBC'    title="Enter your category" ></input>OBC&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory3'   value='Defence'    title="Enter your category" ></input>Defence&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory4'   value='Handicapped'    title="Enter your category" ></input>Handicapped&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory5'   value='General'  checked  title="Enter your category" ></input>General&nbsp;&nbsp;
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
			<label>Nationality: </label><select class="selectMedium" name='nationality' id='nationality'    tip="Enter your nationality"   title="Enter your nationality"    required="true"  ><option value='Indian' selected>Indian</option><option value='Chinese' >Chinese</option><option value='Japanese' >Japanese</option><option value='US' >US</option><option value='British' >British</option><option value='European' >European</option></select>
			<?php if(isset($nationality) && $nationality!=""){ ?>
					<script>
					    var selObj = document.getElementById("nationality"); 
					    var A= selObj.options, L= A.length;
					    while(L){
						if (A[--L].value== "<?php echo $nationality;?>"){
						    selObj.selectedIndex= L;
						    L= 0;
						}
					    }
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'nationality_error'></div></div>
                </li>
                
                <li>

			<label>Religion: </label><input type='text' name='religion' id='religion'  validate="validateStr"   required="true"   caption="Religion"   minlength="2"   maxlength="200"      title="Enter your Religion"  value=''  />
			<?php if(isset($religion) && $religion!=""){ ?>
					<script>
					    document.getElementById("religion").value = "<?php echo str_replace("\n", '\n', $religion );  ?>";
					    document.getElementById("religion").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'religion_error'></div></div>
                </li>
                
                <li>
			<label>Add Photo: </label>
			<span class="flLt">
			    <input type='file' size="30" name='userApplicationfile[]' id='profileImage'   required="true"   caption="Image"        title="Recent color photograph with White background"  />&nbsp;<a href="#" class="imgGuideline">View image guidelines</a><br />
			    <input type='hidden' name='profileImageValid' value='extn:jpg,jpeg,png|size:5'>
			    <span class="imageSizeInfo">(Image Size : Maximum 2 MB)</span>
			    <div style='display:none'><div class='errorMsg' id= 'profileImage_error'></div></div>
			</span>
                </li>
            </ul>
     	</div>






<script>getCitiesForCountryOnline("",false,"",false);</script>
<script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>

<?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	var A= selObj.options, L= A.length;
	while(L){
	    if (A[--L].innerHTML == "<?php echo $city;?>"){
		selObj.selectedIndex= L;
		L= 0;
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
  }function copyAddressFields(){
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