<script>
	function checkValidCoursePreference(id){
        if(id==1){ sId = 2; tId=3; }
        else if(id==2){ sId = 1; tId = 3;  }
        else if(id==3){sId = 1; tId = 2;  }
        else {sId = 1; tId = 2;   }
        var selectedPrefObj = document.getElementById('programCodeChoice'+id+'UPES'); 
        var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
        var selObj1 = document.getElementById('programCodeChoice'+sId+'UPES'); 
        var selPref1 = selObj1.options[selObj1.selectedIndex].value;
        var selObj2 = document.getElementById('programCodeChoice'+tId+'UPES');
        var selPref2 = selObj2.options[selObj2.selectedIndex].value;//alert('pref'+fId+'TSM');
        //var selObj3 = document.getElementById('pref'+fId+'TSM'); 
        //var selPref3 = selObj3.options[selObj3.selectedIndex].value;alert(selectedPref +'=='+ selPref1);
        if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) ){
                $('programCodeChoice'+id+'UPES'+'_error').innerHTML = "Same preference can't be set.";
                $('programCodeChoice'+id+'UPES'+'_error').parentNode.style.display = '';
                //Select the blank option
                var A= selectedPrefObj.options, L= A.length;
                while(L){
                    if (A[--L].value== ""){
                        selectedPrefObj.selectedIndex= L;
                        L= 0;
                    }
                }
                return false;
        }
        else{
                $('programCodeChoice'+id+'UPES'+'_error').innerHTML = '';
                $('programCodeChoice'+id+'UPES'+'_error').parentNode.style.display = 'none';
        }
        return true;
	}
	
	
	
	
	
	function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional",key+"RankAdditional",key+"PercentileAdditional",key+"PercentileUPES");
	if(obj && $(key+"1")){
	      if( obj.checked == false ){
		    $(key+"2").style.display = 'none';
			$(key+"1").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+"2").style.display = '';
			$(key+"1").style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
  }
   function setExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).setAttribute('required','true');
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }

  function resetExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).removeAttribute('required');
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }
  
	
</script>




<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			
	
			
			<li>
				<h3 class="upperCase">Education Details</h3>
			</li>	
			<li>
				<div class='additionalInfoLeftCol' style="width:830px">
				<label>Graduation details: </label>
				<div class='fieldBoxLarge' style="width: 500px;">
				<input type='radio'   required="true"   name='graduationDetailsUPES' id='graduationDetailsUPES0'   value='Final Year/Semester'   onmouseover="showTipOnline('Please enter your graduation details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your graduation details.',this);"  onmouseout="hidetip();">Final Year/Semester</span>&nbsp;&nbsp;
				
				<input type='radio'   required="true"   name='graduationDetailsUPES' id='graduationDetailsUPES1'   value='Completed'  checked   onmouseover="showTipOnline('Please enter your graduation details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your graduation details.',this);"  onmouseout="hidetip();" >Completed</span>&nbsp;&nbsp;
				
				<?php if(isset($graduationDetailsUPES) && $graduationDetailsUPES!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["graduationDetailsUPES"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $graduationDetailsUPES;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationDetailsUPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:830px">
				<label>Admission Pathway: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='radio'   required="true"   name='admissionPathwayUPES' id='admissionPathwayUPES0'   value='UPES Exam'  checked   onmouseover="showTipOnline('Please enter the admission pathway.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter the admission pathway.',this);"   onmouseout="hidetip();" >UPES Exam</span>&nbsp;&nbsp;
				
				<input type='radio'   required="true"   name='admissionPathwayUPES' id='admissionPathwayUPES1'   value='Non-UPES Exam'     onmouseover="showTipOnline('Please enter the admission pathway.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter the admission pathway.',this);" onmouseout="hidetip();" >Non-UPES Exam</span>&nbsp;&nbsp;
				<?php if(isset($admissionPathwayUPES) && $admissionPathwayUPES!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["admissionPathwayUPES"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $admissionPathwayUPES;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'admissionPathwayUPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of school last attended: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='lastSchoolNameUPES' id='lastSchoolNameUPES'  caption="name of school last attended" required="true" validate="validateStr" minlength="2" maxlength="30"       tip="Please enter the name of the school/college last attended."   value=''   />
				<?php if(isset($lastSchoolNameUPES) && $lastSchoolNameUPES!=""){ ?>
				  <script>
				      document.getElementById("lastSchoolNameUPES").value = "<?php echo str_replace("\n", '\n', $lastSchoolNameUPES );  ?>";
				      document.getElementById("lastSchoolNameUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'lastSchoolNameUPES_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>School city: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='schoolCityUPES' id='schoolCityUPES'         tip="Please enter the city where your school/college was located."   value=''   />
				<?php if(isset($schoolCityUPES) && $schoolCityUPES!=""){ ?>
				  <script>
				      document.getElementById("schoolCityUPES").value = "<?php echo str_replace("\n", '\n', $schoolCityUPES );  ?>";
				      document.getElementById("schoolCityUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'schoolCityUPES_error'></div></div>
				</div>
				</div>
			
				
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>School phone number with STD code: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='schoolPhoneNumberUPES' id='schoolPhoneNumberUPES'         tip="Please enter your school/college phone number with STD code if available."   value=''   />
				<?php if(isset($schoolPhoneNumberUPES) && $schoolPhoneNumberUPES!=""){ ?>
				  <script>
				      document.getElementById("schoolPhoneNumberUPES").value = "<?php echo str_replace("\n", '\n', $schoolPhoneNumberUPES );  ?>";
				      document.getElementById("schoolPhoneNumberUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'schoolPhoneNumberUPES_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>School PINCODE: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='schoolPincodeUPES' id='schoolPincodeUPES'         tip="Please enter your school/college city pincode if available."   value=''   />
				<?php if(isset($schoolPincodeUPES) && $schoolPincodeUPES!=""){ ?>
				  <script>
				      document.getElementById("schoolPincodeUPES").value = "<?php echo str_replace("\n", '\n', $schoolPincodeUPES );  ?>";
				      document.getElementById("schoolPincodeUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'schoolPincodeUPES_error'></div></div>
				</div>
				</div>

				
				
			
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of college last attended: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='lastCollegeNameUPES' id='lastCollegeNameUPES'  caption="name of college last attended" required="true" validate="validateStr" minlength="2" maxlength="30"  tip="Please enter the name of the school/college last attended."   value=''   />
				<?php if(isset($lastCollegeNameUPES) && $lastCollegeNameUPES!=""){ ?>
				  <script>
				      document.getElementById("lastCollegeNameUPES").value = "<?php echo str_replace("\n", '\n', $lastCollegeNameUPES );  ?>";
				      document.getElementById("lastCollegeNameUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'lastCollegeNameUPES_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>College city: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='collegeCityUPES' id='collegeCityUPES'         tip="Please enter the city where your school/college was located."   value=''   />
				<?php if(isset($collegeCityUPES) && $collegeCityUPES!=""){ ?>
				  <script>
				      document.getElementById("collegeCityUPES").value = "<?php echo str_replace("\n", '\n', $collegeCityUPES );  ?>";
				      document.getElementById("collegeCityUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'collegeCityUPES_error'></div></div>
				</div>
				</div>
				
				
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>College phone number with STD code: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='collegePhoneNumberUPES' id='collegePhoneNumberUPES'         tip="Please enter your school/college phone number with STD code if available."   value=''   />
				<?php if(isset($collegePhoneNumberUPES) && $collegePhoneNumberUPES!=""){ ?>
				  <script>
				      document.getElementById("collegePhoneNumberUPES").value = "<?php echo str_replace("\n", '\n', $collegePhoneNumberUPES );  ?>";
				      document.getElementById("collegePhoneNumberUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'collegePhoneNumberUPES_error'></div></div>
				</div>
				</div>				
				
				<div class='additionalInfoRightCol'>
				<label>College PINCODE: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='collegePincodeUPES' id='collegePincodeUPES'         tip="Please enter your school/college city pincode if available."   value=''   />
				<?php if(isset($collegePincodeUPES) && $collegePincodeUPES!=""){ ?>
				  <script>
				      document.getElementById("collegePincodeUPES").value = "<?php echo str_replace("\n", '\n', $collegePincodeUPES );  ?>";
				      document.getElementById("collegePincodeUPES").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'collegePincodeUPES_error'></div></div>
				</div>
				</div>
			</li>

			
			<li>
				<h3 class="upperCase">Program Details</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Program Code Choice 1: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCoursePreference(1)" style="width: 200px" name='programCodeChoice1UPES' id='programCodeChoice1UPES'    validate="validateSelect" minlength="1"   maxlength="1500"  required="true"  caption="your 1st preferred choice."   onmouseover="showTipOnline('Please select your preference for the programs.',this);" onmouseout="hidetip();"><option value='' >Select</option>
					<option value='MBA Oil & Gas Management' >MBA Oil & Gas Management 12001</option>
					<option value='MBA Energy Trading' >MBA Energy Trading 12002</option>
					<option value='MBA Power Management' >MBA Power Management 12003</option>
					<option value='MBA Port & Shipping Management' >MBA Port & Shipping Management 12004</option>
					<option value='MBA Logistics & Supply Chain Management' >MBA Logistics & Supply Chain Management 12005</option>
					<option value='MBA International Business' >MBA International Business 12006</option>
					<option value='MBA Aviation Management' >MBA Aviation Management 12007</option>
					<option value='MBA Infrastructure Management' >MBA Infrastructure Management 12008</option>
					<option value='MBA Business Analytics' >MBA Business Analytics 12009</option>
				</select>
				<?php if(isset($programCodeChoice1UPES) && $programCodeChoice1UPES!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programCodeChoice1UPES"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programCodeChoice1UPES;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programCodeChoice1UPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Program Code Choice 2: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCoursePreference(2)" style="width: 200px" name='programCodeChoice2UPES' id='programCodeChoice2UPES'  validate="validateSelect" minlength="1"   maxlength="1500"  required="true"  caption="your 2nd preferred choice."    onmouseover="showTipOnline('Please select your preference for the programs.',this);" onmouseout="hidetip();"><option value='' >Select</option>
					<option value='MBA Oil & Gas Management' >MBA Oil & Gas Management 12001</option>
					<option value='MBA Energy Trading' >MBA Energy Trading 12002</option>
					<option value='MBA Power Management' >MBA Power Management 12003</option>
					<option value='MBA Port & Shipping Management' >MBA Port & Shipping Management 12004</option>
					<option value='MBA Logistics & Supply Chain Management' >MBA Logistics & Supply Chain Management 12005</option>
					<option value='MBA International Business' >MBA International Business 12006</option>
					<option value='MBA Aviation Management' >MBA Aviation Management 12007</option>
					<option value='MBA Infrastructure Management' >MBA Infrastructure Management 12008</option>
					<option value='MBA Business Analytics' >MBA Business Analytics 12009</option>
				</select>
				<?php if(isset($programCodeChoice2UPES) && $programCodeChoice2UPES!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programCodeChoice2UPES"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programCodeChoice2UPES;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programCodeChoice2UPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Program Code Choice 3: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCoursePreference(3)" style="width: 200px" required='true' name='programCodeChoice3UPES' id='programCodeChoice3UPES'  validate="validateSelect" minlength="1"   maxlength="1500"  required="true" caption="your 3rd preferred choice."  onmouseover="showTipOnline('Please select your preference for the programs.',this);" onmouseout="hidetip();" ><option value='' >Select</option>
					<option value='MBA Oil & Gas Management' >MBA Oil & Gas Management 12001</option>
					<option value='MBA Energy Trading' >MBA Energy Trading 12002</option>
					<option value='MBA Power Management' >MBA Power Management 12003</option>
					<option value='MBA Port & Shipping Management' >MBA Port & Shipping Management 12004</option>
					<option value='MBA Logistics & Supply Chain Management' >MBA Logistics & Supply Chain Management 12005</option>
					<option value='MBA International Business' >MBA International Business 12006</option>
					<option value='MBA Aviation Management' >MBA Aviation Management 12007</option>
					<option value='MBA Infrastructure Management' >MBA Infrastructure Management 12008</option>
					<option value='MBA Business Analytics' >MBA Business Analytics 12009</option>
				</select>
				<?php if(isset($programCodeChoice3UPES) && $programCodeChoice3UPES!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programCodeChoice3UPES"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programCodeChoice3UPES;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programCodeChoice3UPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Personal Details</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Bonafide Resident of Uttarakhand ?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='bonafideResidentUPES' id='bonafideResidentUPES0'   value='Yes'  checked   onmouseover="showTipOnline('Please select if you are a bonafide resident of uttarakhand.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you are a bonafide resident of uttarakhand.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				
				<input type='radio'   required="true"   name='bonafideResidentUPES' id='bonafideResidentUPES1'   value='No'  onmouseover="showTipOnline('Please select if you are a bonafide resident of uttarakhand.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you are a bonafide resident of uttarakhand.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($bonafideResidentUPES) && $bonafideResidentUPES!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["bonafideResidentUPES"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $bonafideResidentUPES;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'bonafideResidentUPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>How did you come to know about UPES ?: </label>
				<div class='fieldBoxLarge' style= "width:545px;">
				<input type='checkbox'     name='knowAboutUPES[]' id='knowAboutUPES0'   value='Newspaper/Magazine' checked validate="validateCheckedGroup" required="true" caption="one of the fields." onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" ></input><span style="display:inline-block; width:150px;" onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" >Newspaper/Magazine</span>&nbsp;&nbsp;
				<input type='checkbox'     name='knowAboutUPES[]' id='knowAboutUPES1'   value='Internet/Google search' validate="validateCheckedGroup" required="true" caption="one of the fields."   onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" ></input><span style="display:inline-block; width:150px;" onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" >Internet/Google search</span>&nbsp;&nbsp;
				<input type='checkbox'     name='knowAboutUPES[]' id='knowAboutUPES2'   value='Edu./ Other websites'  validate="validateCheckedGroup" required="true" caption="one of the fields."   onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" ></input><span  style="display:inline-block; width:150px;" onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" >Edu./ Other websites</span>&nbsp;&nbsp;
				<input type='checkbox'     name='knowAboutUPES[]' id='knowAboutUPES3'   value='Coaching center' validate="validateCheckedGroup" required="true" caption="one of the fields."  onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" ></input><span  style="display:inline-block; width:150px;" onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" >Coaching center</span>&nbsp;&nbsp;
				<input style="" type='checkbox'     name='knowAboutUPES[]' id='knowAboutUPES4'   value='Book seller'   onmouseover="showTipOnline('Please select your choice',this);"  validate="validateCheckedGroup" required="true" caption="one of the fields." onmouseout="hidetip();" ></input><span  style="display:inline-block; width:150px;" onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" >Book seller</span>&nbsp;&nbsp;
				<input style="" type='checkbox'     name='knowAboutUPES[]' id='knowAboutUPES5'   value='Friends/Relatives'  validate="validateCheckedGroup" required="true" caption="one of the fields." onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" ></input><span  style="display:inline-block; width:150px;" onmouseover="showTipOnline('Please select your choice',this);" onmouseout="hidetip();" >Friends/Relatives</span>&nbsp;&nbsp;
				
				<?php if(isset($knowAboutUPES) && $knowAboutUPES!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["knowAboutUPES[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$knowAboutUPES);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'knowAboutUPES_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select name='categoryUPES' id='categoryUPES' validate="validateSelect" minlength="1"   maxlength="1500"  required="true" caption="your category."  onmouseover="showTipOnline('Please select your  category.',this);" onmouseout="hidetip();" ><option value='' >Select</option>
					<option value='General' >General</option>
					<option value='SC' >SC</option>
					<option value='ST' >ST</option>
					<option value='OBC' >OBC</option>
					<option value='Phy. Handicapped' >Phy. Handicapped</option>
					<option value='NRI' >NRI</option>
					<option value='International Student' >International Student</option>
				</select>
				<?php if(isset($categoryUPES) && $categoryUPES!=""){ ?>
			      <script>
				  var selObj = document.getElementById("categoryUPES"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $categoryUPES;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryUPES_error'></div></div>
				</div>
				</div>
			</li>
			


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father/Guardian's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherName' id='fatherName'  validate="validateStr"   required="true"   caption="Father's Name"   minlength="3"   maxlength="50" onmouseover="showTipOnline('Please enter your Father/Guardian`s Name.',this);" onmouseout="hidetip();" value=''   />
				<?php if(isset($fatherName) && $fatherName!=""){ ?>
				  <script>
				      document.getElementById("fatherName").value = "<?php echo str_replace("\n", '\n', $fatherName );  ?>";
				      document.getElementById("fatherName").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherName_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:830px">
				<label>Gender: </label>
				<div class='fieldBoxLarge'style="width:500px">
				<input type='radio'   required="true"   name='gender' id='gender0'   value='Male'  checked   onmouseover="showTipOnline('Please select your Gender.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your Gender.',this);" onmouseout="hidetip();" >Male</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='gender' id='gender1'   value='Female'     onmouseover="showTipOnline('Please select your Gender.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your Gender.',this);" onmouseout="hidetip();" >Female</span>&nbsp;&nbsp;
				
				<input type='radio'   required="true"   name='gender' id='gender2'   value='Transgender'     onmouseover="showTipOnline('Please select your Gender.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your Gender.',this);" onmouseout="hidetip();" >Transgender</span>&nbsp;&nbsp;
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
				</div>
				</div>
			</li>
			
			
			
			
			
			<li>
				<h3 class="upperCase">TESTS</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='UPES_testNames[]' id='UPES_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='UPES_testNames[]' id='UPES_testNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='UPES_testNames[]' id='UPES_testNames2'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='UPES_testNames[]' id='UPES_testNames3'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='UPES_testNames[]' id='UPES_testNames4'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='UPES_testNames[]' id='UPES_testNames5'   value='UPESMET'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >UPESMET</span>&nbsp;&nbsp;
				<?php if(isset($UPES_testNames) && $UPES_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["UPES_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$UPES_testNames);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'UPES_testNames_error'></div></div>
				</div>
				</div>
			</li>
			
			
			

			<li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
				<?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
				      document.getElementById("catRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>CAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='catDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("catDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $catDateOfExaminationAdditional );  ?>";
				      document.getElementById("catDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>CAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
				      document.getElementById("catScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
				<?php if(isset($catPercentileAdditional) && $catPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("catPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $catPercentileAdditional );  ?>";
				      document.getElementById("catPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			

			<li id="xat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
				<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
				      document.getElementById("xatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>XAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $xatDateOfExaminationAdditional );  ?>";
				      document.getElementById("xatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
				
			</li>

			<li id="xat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>XAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
				      document.getElementById("xatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label>XAT Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
					<?php if(isset($xatPercentileAdditional) && $xatPercentileAdditional!=""){ ?>
							<script>
							document.getElementById("xatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $xatPercentileAdditional );  ?>";
							document.getElementById("xatPercentileAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'xatPercentileAdditional_error'></div></div>
				</div>
			      </div>
			</li>
			

			<li id="gmat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
				<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
				      document.getElementById("gmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>GMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='gmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $gmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("gmatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="gmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label>GMAT Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
					<?php if(isset($gmatPercentileAdditional) && $gmatPercentileAdditional!=""){ ?>
							<script>
							document.getElementById("gmatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $gmatPercentileAdditional );  ?>";
							document.getElementById("gmatPercentileAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'gmatPercentileAdditional_error'></div></div>
				</div>
			      </div>
			</li>

			<li id="cmat1" style="display:none;">
			    <div class='additionalInfoLeftCol'>
			    <label>CMAT REGN NO: </label>
			    <div class='fieldBoxLarge'>
			    <input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
			    <?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
			      <script>
				  document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
				  document.getElementById("cmatRollNumberAdditional").style.color = "";
			      </script>
			    <?php } ?>
	    
			    <div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
			    </div>
			    </div>
	    
			    <div class='additionalInfoRightCol'>
			    <label>CMAT Date: </label>
			    <div class='fieldBoxLarge'>
			    <input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
			    &nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
			    <?php if(isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!=""){ ?>
			      <script>
				  document.getElementById("cmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationAdditional );  ?>";
				  document.getElementById("cmatDateOfExaminationAdditional").style.color = "";
			      </script>
	    
			    <?php } ?>
	    
			    <div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationAdditional_error'></div></div>
			    </div>
			    </div>
	    
				    </li>

			<li id='cmat2' style='display:none;border-bottom:1px dotted #c0c0c0; padding-bottom:15px'>
	    
			    <div class='additionalInfoLeftCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				    <input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
				    <?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				    <script>
					document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
					document.getElementById("cmatScoreAdditional").style.color = "";
				    </script>
				    <?php } ?>
	    
				    <div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
			    </div>
	    
			    <div class='additionalInfoRightCol'>
				<label>CMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				    <input type='text' name='cmatPercentileUPES' id='cmatPercentileUPES'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
				    <?php if(isset($cmatPercentileUPES) && $cmatPercentileUPES!=""){ ?>
				    <script>
					document.getElementById("cmatPercentileUPES").value = "<?php echo str_replace("\n", '\n', $cmatPercentileUPES );  ?>";
					document.getElementById("cmatPercentileUPES").style.color = "";
				    </script>
				    <?php } ?>
	    
				    <div style='display:none'><div class='errorMsg' id= 'cmatPercentileUPES_error'></div></div>
				</div>
			    </div>
	    
			</li>
			
			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
				<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
				      document.getElementById("matRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>MAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='matDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("matDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $matDateOfExaminationAdditional );  ?>";
				      document.getElementById("matDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>MAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
				<?php if(isset($matPercentileAdditional) && $matPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("matPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $matPercentileAdditional );  ?>";
				      document.getElementById("matPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li id="upesmet1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>UPESMET REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='upesmetRollNumberAdditional' id='upesmetRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
				<?php if(isset($upesmetRollNumberAdditional) && $upesmetRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("upesmetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $upesmetRollNumberAdditional );  ?>";
				      document.getElementById("upesmetRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upesmetRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>UPESMET Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upesmetDateOfExaminationAdditional' id='upesmetDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('upesmetDateOfExaminationAdditional'),'upesmetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='upesmetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('upesmetDateOfExaminationAdditional'),'upesmetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($upesmetDateOfExaminationAdditional) && $upesmetDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("upesmetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $upesmetDateOfExaminationAdditional );  ?>";
				      document.getElementById("upesmetDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upesmetDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="upesmet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>UPESMET Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upesmetScoreAdditional' id='upesmetScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($upesmetScoreAdditional) && $upesmetScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("upesmetScoreAdditional").value = "<?php echo str_replace("\n", '\n', $upesmetScoreAdditional );  ?>";
				      document.getElementById("upesmetScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upesmetScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>UPESMET Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upesmetPercentileAdditional' id='upesmetPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
				<?php if(isset($upesmetPercentileAdditional) && $upesmetPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("upesmetPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $upesmetPercentileAdditional );  ?>";
				      document.getElementById("upesmetPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upesmetPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value="<?php echo $gdpiLocation['city_id']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
				<?php endforeach; ?>
				</select>
				<?php if(isset($preferredGDPILocation) && $preferredGDPILocation!=""){ ?>
				<script>
				var selObj = document.getElementById("preferredGDPILocation"); 
				var A= selObj.options, L= A.length;
				while(L){
					if (A[--L].value== "<?php echo $preferredGDPILocation;?>"){
					selObj.selectedIndex= L;
					L= 0;
					}
				}
				</script>
				  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'preferredGDPILocation_error'></div></div>
				</div>
			</li>
			<?php endif; ?>
			
			
			
			
			
			
			
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I hereby declare that all the information given in this application form is true to the best of my knowledge and belief. In the event of any information being found false or incorrect or ineligibility being detected before or after the subsequent process, the cadidature is liable to be cancelled by the university.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='declarationUPES[]' id='declarationUPES0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($declarationUPES) && $declarationUPES!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["declarationUPES[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$declarationUPES);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'declarationUPES0_error'></div></div>
				</div>
				</div>
			</li>

		</ul>
	</div>
</div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
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
  <?php } ?><?php if(isset($Ccity) && $Ccity!=""){ ?>
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
  <?php } ?><script>
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
	
	for(var j=0; j<6; j++){
		checkTestScore(document.getElementById('UPES_testNames'+j));
	}

  

  </script>