
<div>First Name: <input type='text' name='firstName' id='firstName'  validate="validateDisplayName"   required="true"   caption="First Name"   minlength="2"   maxlength="25"     tip="First Name"   title="Enter First Name"  value='First Name'   default = 'First Name' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></div><script>
		    document.getElementById("firstName").style.color = "#ADA6AD";
		</script>
<?php if(isset($firstName) && $firstName!=""){ ?>
		<script>
		    document.getElementById("firstName").value = "<?php echo $firstName; ?>";
		    document.getElementById("firstName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'firstName_error'></div></div>
<div class='lineSpace_10'></div>
<div>Middle Name: <input type='text' name='middleName' id='middleName'  validate="validateDisplayName"   required="true"   caption="Middle Name"   minlength="2"   maxlength="25"      title="Enter Middle Name"  value='Middle Name'   default = 'Middle Name' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></div><script>
		    document.getElementById("middleName").style.color = "#ADA6AD";
		</script>
<?php if(isset($middleName) && $middleName!=""){ ?>
		<script>
		    document.getElementById("middleName").value = "<?php echo $middleName; ?>";
		    document.getElementById("middleName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'middleName_error'></div></div>
<div class='lineSpace_10'></div>
<div>Last Name: <input type='text' name='lastName' id='lastName'  validate="validateDisplayName"   required="true"   caption="Last Name"   minlength="2"   maxlength="25"      title="Enter Last Name"  value='Last Name'   default = 'Last Name' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");' /></div><script>
		    document.getElementById("lastName").style.color = "#ADA6AD";
		</script>
<?php if(isset($lastName) && $lastName!=""){ ?>
		<script>
		    document.getElementById("lastName").value = "<?php echo $lastName; ?>";
		    document.getElementById("lastName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'lastName_error'></div></div>
<div class='lineSpace_10'></div>
<div>Gender: <input type='radio' name='gender' id='gender0'   value='Male'  checked  title="Select Gender" ></input>Male&nbsp;&nbsp;<input type='radio' name='gender' id='gender1'   value='Female'    title="Select Gender" ></input>Female&nbsp;&nbsp;</div>
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
<div class='lineSpace_10'></div>
<div>Date of Birth: <input type='text' name='dateOfBirth' id='dateOfBirth' readonly maxlength='10'  validate="validateDate"   required="true"   caption="Birth Date"       tip="Enter your Birth Date"   title="Enter your Birth Date"   default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="cal.select($('dateOfBirth'),'sd','yyyy-MM-dd');" /></div><img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sd' onClick="cal.select($('dateOfBirth'),'sd','yyyy-MM-dd');" /><script>
		    document.getElementById("dateOfBirth").style.color = "#ADA6AD";
		</script>
<?php if(isset($dateOfBirth) && $dateOfBirth!=""){ ?>
		<script>
		    document.getElementById("dateOfBirth").value = "<?php echo $dateOfBirth; ?>";
		    document.getElementById("dateOfBirth").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'dateOfBirth_error'></div></div>
<div class='lineSpace_10'></div>
<div style='display:none'><div class='errorMsg' id= '_error'></div></div>
<div class='lineSpace_10'></div>
<div>Email Address: <input type='text' name='email' id='email'  validate="validateEmail"   required="true"   caption="Email Address"   minlength="2"   maxlength="200"      title="Enter your Email Address"  value=''  /></div>
<?php if(isset($email) && $email!=""){ ?>
		<script>
		    document.getElementById("email").value = "<?php echo $email; ?>";
		    document.getElementById("email").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'email_error'></div></div>
<div class='lineSpace_10'></div>
<div>Alternate Email: <input type='text' name='altEmail' id='altEmail'  validate="validateEmail"   required="true"   caption="Alternate Email"   minlength="2"   maxlength="200"      title="Enter your Alternate Email Address"  value=''  /></div>
<?php if(isset($altEmail) && $altEmail!=""){ ?>
		<script>
		    document.getElementById("altEmail").value = "<?php echo $altEmail; ?>";
		    document.getElementById("altEmail").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'altEmail_error'></div></div>
<div class='lineSpace_10'></div>
<div>Course Name: <input type='text' name='courseName' id='courseName'  validate="validateStr"   required="true"   caption="Course Name"   minlength="2"   maxlength="200"      title="Enter the course name you want to apply"  value=''  readonly=""  /></div>
<?php if(isset($courseName) && $courseName!=""){ ?>
		<script>
		    document.getElementById("courseName").value = "<?php echo $courseName; ?>";
		    document.getElementById("courseName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'courseName_error'></div></div>
<div class='lineSpace_10'></div>
<div>Course Code: <input type='text' name='courseCode' id='courseCode'  validate="validateStr"   required="true"   caption="Course Code"   minlength="2"   maxlength="200"      title="Enter the Course Code you want to apply"  value=''  readonly=""  /></div>
<?php if(isset($courseCode) && $courseCode!=""){ ?>
		<script>
		    document.getElementById("courseCode").value = "<?php echo $courseCode; ?>";
		    document.getElementById("courseCode").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'courseCode_error'></div></div>
<div class='lineSpace_10'></div>
<div>Marital Status: <input type='radio' name='maritalStatus' id='maritalStatus0'   value='Single'  checked ></input>Single&nbsp;&nbsp;<input type='radio' name='maritalStatus' id='maritalStatus1'   value='Married'   ></input>Married&nbsp;&nbsp;<input type='radio' name='maritalStatus' id='maritalStatus2'   value='Separated'   ></input>Separated&nbsp;&nbsp;</div>
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
<div class='lineSpace_10'></div>
<div>Application Category: <input type='radio' tip='SC' name='applicationCategory' id='applicationCategory0'   value='SC'    title="Enter your category" ></input>SC&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory1'   tip='ST' value='ST'    title="Enter your category" ></input>ST&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory2'   value='OBC'   tip='OBC' title="Enter your category" ></input>OBC&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory3'   value='Defence'    title="Enter your category" ></input>Defence&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory4'   value='Handicapped'    title="Enter your category" ></input>Handicapped&nbsp;&nbsp;<input type='radio' name='applicationCategory' id='applicationCategory5'   value='General'  checked  title="Enter your category" tip='General'></input>General&nbsp;&nbsp;</div>
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
<div class='lineSpace_10'></div>
<div>Nationality: <select name='nationality' id='nationality'    tip="Enter your nationality"   title="Enter your nationality" ><option value='Indian' selected>Indian</option><option value='Chinese' >Chinese</option><option value='Japanese' >Japanese</option><option value='US' >US</option><option value='British' >British</option><option value='European' >European</option></select></div>
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
<div class='lineSpace_10'></div>
<div>Religion: <input type='text' name='religion' id='religion'  validate="validateStr"   required="true"   caption="Religion"   minlength="2"   maxlength="200"      title="Enter your Religion"  value=''  /></div>
<?php if(isset($religion) && $religion!=""){ ?>
		<script>
		    document.getElementById("religion").value = "<?php echo $religion; ?>";
		    document.getElementById("religion").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'religion_error'></div></div>
<div class='lineSpace_10'></div>
<div>Add Photo: <input type='file' name='userApplicationfile[]' id='profileImage'   required="true"   caption="Image"        title="Recent color photograph with White background"  /></div><input type='hidden' name='profileImageValid' value='extn:jpg,jpeg,png|size:5'><div style='display:none'><div class='errorMsg' id= 'profileImage_error'></div></div>
<div class='lineSpace_10'></div><script>
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
  }

  if(document.getElementById('courseCode')){
	document.getElementById('courseCode').readonly = true;
  }
  </script>