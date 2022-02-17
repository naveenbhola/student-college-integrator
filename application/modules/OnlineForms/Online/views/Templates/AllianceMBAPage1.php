<script>

  function examSelected(obj){
	if(obj.value == "GMAT" || obj.value == "MAT" || obj.value == "CAT" || obj.value == "XAT" || obj.value == "ATMA"){
	    var key = obj.value.toLowerCase();
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"PercentileAdditional",key+"ScoreAdditional");
	}
	
	
	else if(obj.value == "CMAT"){
	    var key = obj.value.toLowerCase();
	    var objects1 = new Array(key+'DateOfExaminationAdditional',key+'PercentileAdditionalAlliance',key+'ScoreAdditional');
	}

	else if(obj.value == "KMAT"){
	    var key = obj.value.toLowerCase();
	    var objects1 = new Array(key+'DateOfExaminationAdditional',key+'PercentileAdditional',key+'ScoreAdditional');
	}

	if(obj){
	      if( document.getElementById(obj.value+"Score1").style.display == ''){
		    document.getElementById(obj.value+"Score1").style.display = 'none';
		    document.getElementById(obj.value+"Score2").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    document.getElementById(obj.value+"Score1").style.display = '';
		    document.getElementById(obj.value+"Score2").style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
  }

  function setExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).setAttribute('required','true');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }

  function resetExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
	    document.getElementById(objectsArr[i]).removeAttribute('required');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }


</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			<?php if($action != 'updateScore'):?>
			<li>
				<div class='additionalInfoLeftCol' style="width:100%;">
				<label style="width:41%;">Course applied for: Master of Business Administration (MBA)</label>
				
				</div>
			</li>

			<li>
				<h3 class="upperCase">Applicant's Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Blood Group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='bloodGroupAlliance' id='bloodGroupAlliance'  validate="validateStr"   required="true"   caption="blood group"   minlength="2"   maxlength="4"     tip="Please enter your blood group"   value=''   />
				<?php if(isset($bloodGroupAlliance) && $bloodGroupAlliance!=""){ ?>
				  <script>
				      document.getElementById("bloodGroupAlliance").value = "<?php echo str_replace("\n", '\n', $bloodGroupAlliance );  ?>";
				      document.getElementById("bloodGroupAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'bloodGroupAlliance_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol' style="width:100%;">
				<label>Select your nationality: </label>
				<div class='fieldBoxLarge' style="width: 625px;">
				<input onClick="checkNationality(0);" type='radio' name='nationalityAlliance' id='nationalityAlliance0'   value='INDIAN'  <?php if(isset($nDetailsAlliance) && $nDetailsAlliance=='INDIAN'){echo 'checked';} ?>   ></input><span >Indian</span>&nbsp;&nbsp;
				<input onClick="checkNationality(0);" type='radio' name='nationalityAlliance' id='nationalityAlliance1'   value='NRI'    ></input><span >NRI</span>&nbsp;&nbsp;
				<input onClick="checkNationality(1);" type='radio' name='nationalityAlliance' id='nationalityAlliance2'   value='Foreign National'  <?php if(isset($nDetailsAlliance) && $nDetailsAlliance!='INDIAN'){echo 'checked';} ?>  ></input><span >Foreign National</span>&nbsp;&nbsp;
				<?php if(isset($nationalityAlliance) && $nationalityAlliance!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["nationalityAlliance"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $nationalityAlliance;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nationalityAlliance_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Nationality: </label>
				<div class='fieldBoxLarge'>
				<select name='nDetailsAlliance' id='nDetailsAlliance'    <?php if(isset($nDetailsAlliance) && $nDetailsAlliance=='INDIAN'){echo 'disabled';} ?>  validate="validateStr" maxlength="500" minlength="2" caption="nationality">
				      <option value='' selected>Select</option>
				      <?php foreach($nationalities as $anationality): ?>
					  <?php if(strtoupper($anationality) != 'INDIAN'){ ?>
					  <option value='<?php echo strtoupper($anationality); ?>' ><?php echo strtoupper($anationality); ?></option>
					  <?php } ?>
					  <?php endforeach; ?>

				</select>
				<?php if(isset($nDetailsAlliance) && $nDetailsAlliance!="" && $nDetailsAlliance!="INDIAN"){ 
				  if($nationalityAlliance!='INDIAN' && $nationalityAlliance!='NRI'){?>
			      <script>
				  var selObj = document.getElementById("nDetailsAlliance"); 
				  selObj.disabled = false;
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $nDetailsAlliance;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php }else{ ?>
			      <script>
				  var selObj = document.getElementById("nDetailsAlliance"); 
				  selObj.disabled = true;
			      </script>
			    <?php }} ?>

				<?php if($nationalityAlliance=='INDIAN' || $nationalityAlliance=='NRI'){?>
			      <script>
				  var selObj = document.getElementById("nDetailsAlliance"); 
				  selObj.disabled = true;
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nDetailsAlliance_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li>
				<div class='additionalInfoLeftCol' style="width:100%;">
				<label>Session: </label>
				<div class='fieldBoxLarge' style="width: 625px;">
				<!--<input onclick="checkSession(0);" type='radio' name='sessionAlliance' id='sessionAlliance0'   value='Jan'></input><span >Jan</span>&nbsp;&nbsp;-->
				<input onclick="checkSession(1);" type='radio' name='sessionAlliance' id='sessionAlliance1'   value='July' checked   ></input><span >July</span>&nbsp;&nbsp;
				<?php if(isset($sessionAlliance) && $sessionAlliance!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["sessionAlliance"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $sessionAlliance;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sessionAlliance_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<h3 class="upperCase">Parent's contact details for correspondence</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='fatherEmailAlliance' id='fatherEmailAlliance' tip="Please enter your father's email id"   value=''  caption="email" validate="validateEmail" minlength="3" maxlength="100"/>
				<?php if(isset($fatherEmailAlliance) && $fatherEmailAlliance!=""){ ?>
				  <script>
				      document.getElementById("fatherEmailAlliance").value = "<?php echo str_replace("\n", '\n', $fatherEmailAlliance );  ?>";
				      document.getElementById("fatherEmailAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherEmailAlliance_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Father's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherMobileNumberAlliacne' id='fatherMobileNumberAlliacne'  validate="validateMobileInteger"   required="true"   caption="father's Mob No."   minlength="8"   maxlength="15"     tip="Please enter father's mobile number."   value=''   />
				<?php if(isset($fatherMobileNumberAlliacne) && $fatherMobileNumberAlliacne!=""){ ?>
				  <script>
				      document.getElementById("fatherMobileNumberAlliacne").value = "<?php echo str_replace("\n", '\n', $fatherMobileNumberAlliacne );  ?>";
				      document.getElementById("fatherMobileNumberAlliacne").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherMobileNumberAlliacne_error'></div></div>
				</div>
				</div>
				
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Company: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='fatherCompanyAlliance' id='fatherCompanyAlliance' tip="Please enter your father's company name"   value=''  caption="company name" validate="validateStr" minlength="2" maxlength="100"/>
				<?php if(isset($fatherCompanyAlliance) && $fatherCompanyAlliance!=""){ ?>
				  <script>
				      document.getElementById("fatherCompanyAlliance").value = "<?php echo str_replace("\n", '\n', $fatherCompanyAlliance );  ?>";
				      document.getElementById("fatherCompanyAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherCompanyAlliance_error'></div></div>
				</div>
				</div>
			</li>
		
<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='motherEmailAlliance' id='motherEmailAlliance' tip="Please enter your mother's email id"   value=''  caption="email" validate="validateEmail" minlength="3" maxlength="100" />
				<?php if(isset($motherEmailAlliance) && $motherEmailAlliance!=""){ ?>
				  <script>
				      document.getElementById("motherEmailAlliance").value = "<?php echo str_replace("\n", '\n', $motherEmailAlliance );  ?>";
				      document.getElementById("motherEmailAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherEmailAlliance_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Mother's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherMobileNumberAlliacne' id='motherMobileNumberAlliacne'  validate="validateMobileInteger"   caption="Mob No."   minlength="8"   maxlength="15"     tip="Please enter mother's mobile number."   value=''   />
				<?php if(isset($motherMobileNumberAlliacne) && $motherMobileNumberAlliacne!=""){ ?>
				  <script>
				      document.getElementById("motherMobileNumberAlliacne").value = "<?php echo str_replace("\n", '\n', $motherMobileNumberAlliacne );  ?>";
				      document.getElementById("motherMobileNumberAlliacne").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherMobileNumberAlliacne_error'></div></div>
				</div>
				</div>
				
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Company: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='motherCompanyAlliance' id='motherCompanyAlliance' tip="Please enter your mother's company name"   value=''  caption="company name" validate="validateStr" minlength="2" maxlength="100"/>
				<?php if(isset($motherCompanyAlliance) && $motherCompanyAlliance!=""){ ?>
				  <script>
				      document.getElementById("motherCompanyAlliance").value = "<?php echo str_replace("\n", '\n', $motherCompanyAlliance );  ?>";
				      document.getElementById("motherCompanyAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherCompanyAlliance_error'></div></div>
				</div>
				</div>
			</li>

			<li>
			<div class='additionalInfoLeftCol'>
				<label>Father's / Mother's Office Tel: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='fatherNoAlliance' id='fatherNoAlliance'         tip="Please enter your father's office telephone number"   value=''  caption="phone number" validate="validateInteger" minlength="6" maxlength="14" />
				<?php if(isset($fatherNoAlliance) && $fatherNoAlliance!=""){ ?>
				  <script>
				      document.getElementById("fatherNoAlliance").value = "<?php echo str_replace("\n", '\n', $fatherNoAlliance );  ?>";
				      document.getElementById("fatherNoAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherNoAlliance_error'></div></div>
				</div>
				</div>			
			</li>

	<?php
	// Find out graduation course name, if available
	$graduationCourseName = 'Graduation';
	$graduationYear = '';
	$otherCourses = array();
	$otherCourseYears = array();
	
	if(is_array($educationDetails)) {
		foreach($educationDetails as $educationDetail) {
			if($educationDetail['value']) {
				if($educationDetail['fieldName'] == 'graduationExaminationName') {
					$graduationCourseName = $educationDetail['value'];
				}
				else if($educationDetail['fieldName'] == 'graduationYear') {
					$graduationYear = $educationDetail['value'];
				}
				else {
					for($i=1;$i<=4;$i++) {
						if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
							$otherCourses[$i] = $educationDetail['value'];
						}
						else if($educationDetail['fieldName'] == 'graduationYear_mul_'.$i) {
							$otherCourseYears[$i] = $educationDetail['value'];
						}
					}
				}
			}
		}
	}

	if(isset($graduationEndDate) && $graduationYear!="") {
		$graduationYear = $graduationEndDate;
	}
	
    ?>

			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					$pgCheckVal = $$pgCheck;
					$state = 'otherCourseState_mul_'.$otherCourseId;
					$stateVal = $$state;
					$subjects = 'otherCourseSubjects_mul_'.$otherCourseId;
					$subjectsVal = $$subjects;
					$mode = 'otherCourseMode_mul_'.$otherCourseId;
					$modeVal = $$mode;
					$i++;

			?>

			<li>
				<?php if($i==1){ ?><h3 class="upperCase">Education qualifications</h3><?php } ?>
				<b><?php echo $otherCourseName;?> details:</b><div class="clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Is <?php echo $otherCourseName;?> a PG Course: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='<?php echo $pgCheck; ?>' id='<?php echo $pgCheck; ?>'  tip="If this is a Post Graduate course, please tick this check box."   value='1'  />
				    <?php if(isset($pgCheckVal) && $pgCheckVal!=""){ ?>
				      <script>
					  objCheckBoxes = document.forms["OnlineForm"].elements["<?php echo $pgCheck; ?>"];
					  var countCheckBoxes = 1;
					  for(var i = 0; i < countCheckBoxes; i++){ 
						    objCheckBoxes.checked = false;
						    <?php $arr = explode(",",$pgCheckVal);
							  for($x=0;$x<count($arr);$x++){ ?>
								if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
									objCheckBoxes.checked = true;
								}
						    <?php
							  }
						    ?>
					  }
				      </script>
				    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $pgCheck; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>State: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $state; ?>' id='<?php echo $state; ?>'  validate="validateStr"   required="true"   caption="state"   minlength="2"   maxlength="50"     tip="Please enter <?php echo $otherCourseName;?> Institute's state name. E.g. Haryana, Punjab, Maharashtra  etc."   value=''   />
				<?php if(isset($stateVal) && $stateVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $state; ?>").value = "<?php echo str_replace("\n", '\n', $stateVal );  ?>";
				      document.getElementById("<?php echo $state; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $state; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Main subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $subjects; ?>' id='<?php echo $subjects; ?>'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Please enter the main subjects you studied in <?php echo $otherCourseName;?>"   value='' csv="true"    />
				<?php if(isset($subjectsVal) && $subjectsVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $subjects; ?>").value = "<?php echo str_replace("\n", '\n', $subjectsVal );  ?>";
				      document.getElementById("<?php echo $subjects; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $subjects; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Mode of study: </label>
				<div class='fieldBoxLarge' style="width:625px">
				<input type='radio' name='<?php echo $mode; ?>' id='<?php echo $mode; ?>0'   value='Full-time'  checked  ></input><span >Full-time</span>&nbsp;&nbsp;
				<input type='radio' name='<?php echo $mode; ?>' id='<?php echo $mode; ?>1'   value='Part-time'    ></input><span >Part-time</span>&nbsp;&nbsp;
				<input type='radio' name='<?php echo $mode; ?>' id='<?php echo $mode; ?>2'   value='Correspondence'    ></input><span >Correspondence</span>&nbsp;&nbsp;
				<?php if(isset($modeVal) && $modeVal!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["<?php echo $mode; ?>"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $modeVal;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $mode; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<?php
				}
			}
			?>


			<li>
				<?php if($i==0){ ?><h3 class="upperCase">Education qualifications</h3><?php } ?>
                
                <div class="clearFix"></div>
                <div class="form-box-wrapper">
				<b style="padding-left:10px">Graduation details:</b>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>State: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradStateAlliance' id='gradStateAlliance'  validate="validateStr"   required="true"   caption="state"   minlength="2"   maxlength="50"     tip="Please enter your UG Institute's state name. E.g. Haryana, Punjab, Maharashtra  etc."   value=''   />
				<?php if(isset($gradStateAlliance) && $gradStateAlliance!=""){ ?>
				  <script>
				      document.getElementById("gradStateAlliance").value = "<?php echo str_replace("\n", '\n', $gradStateAlliance );  ?>";
				      document.getElementById("gradStateAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradStateAlliance_error'></div></div>
				</div>
				</div>
                
				<div class="spacer20 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Marks (Semester I): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem1Alliance' id='gradMarksSem1Alliance'  validate="validateFloat"   required="true"   caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester I"   value=''   />
				<?php if(isset($gradMarksSem1Alliance) && $gradMarksSem1Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem1Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem1Alliance );  ?>";
				      document.getElementById("gradMarksSem1Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem1Alliance_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Marks (Semester II): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem2Alliance' id='gradMarksSem2Alliance'  validate="validateFloat"   required="true"   caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester II"   value=''   />
				<?php if(isset($gradMarksSem2Alliance) && $gradMarksSem2Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem2Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem2Alliance );  ?>";
				      document.getElementById("gradMarksSem2Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem2Alliance_error'></div></div>
				</div>
				</div>
				
                <div class="spacer20 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Marks (Semester III): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem3Alliance' id='gradMarksSem3Alliance'  validate="validateFloat"  caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester III"   value=''   />
				<?php if(isset($gradMarksSem3Alliance) && $gradMarksSem3Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem3Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem3Alliance );  ?>";
				      document.getElementById("gradMarksSem3Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem3Alliance_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Marks (Semester IV): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem4Alliance' id='gradMarksSem4Alliance'  validate="validateFloat"  caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester IV"   value=''   />
				<?php if(isset($gradMarksSem4Alliance) && $gradMarksSem4Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem4Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem4Alliance );  ?>";
				      document.getElementById("gradMarksSem4Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem4Alliance_error'></div></div>
				</div>
				</div>
				<div class="spacer20 clearFix"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Marks (Semester V): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem5Alliance' id='gradMarksSem5Alliance'  validate="validateFloat"   caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester V"   value=''   />
				<?php if(isset($gradMarksSem5Alliance) && $gradMarksSem5Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem5Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem5Alliance );  ?>";
				      document.getElementById("gradMarksSem5Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem5Alliance_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Marks (Semester VI): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem6Alliance' id='gradMarksSem6Alliance'  validate="validateFloat"   caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester VI"   value=''   />
				<?php if(isset($gradMarksSem6Alliance) && $gradMarksSem6Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem6Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem6Alliance );  ?>";
				      document.getElementById("gradMarksSem6Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem6Alliance_error'></div></div>
				</div>
				</div>
				<div class="spacer20 clearFix"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Marks (Semester VII): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem7Alliance' id='gradMarksSem7Alliance'  validate="validateFloat"      caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester VII. If yours was a three year course, leave this Blank."   value=''   />
				<?php if(isset($gradMarksSem7Alliance) && $gradMarksSem7Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem7Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem7Alliance );  ?>";
				      document.getElementById("gradMarksSem7Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem7Alliance_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Marks (Semester VIII): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMarksSem8Alliance' id='gradMarksSem8Alliance'  validate="validateFloat"      caption="marks"   minlength="2"   maxlength="7"     tip="Please enter your marks in semester VIII. If yours was a three year course, leave this Blank."   value=''   />
				<?php if(isset($gradMarksSem8Alliance) && $gradMarksSem8Alliance!=""){ ?>
				  <script>
				      document.getElementById("gradMarksSem8Alliance").value = "<?php echo str_replace("\n", '\n', $gradMarksSem8Alliance );  ?>";
				      document.getElementById("gradMarksSem8Alliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMarksSem8Alliance_error'></div></div>
				</div>
				</div>
				<div class="spacer20 clearFix"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Main subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradSubjectsAlliance' id='gradSubjectsAlliance'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Please enter the main subjects you studied in Graduation"   value=''  csv="true"   />
				<?php if(isset($gradSubjectsAlliance) && $gradSubjectsAlliance!=""){ ?>
				  <script>
				      document.getElementById("gradSubjectsAlliance").value = "<?php echo str_replace("\n", '\n', $gradSubjectsAlliance );  ?>";
				      document.getElementById("gradSubjectsAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSubjectsAlliance_error'></div></div>
				</div>
				</div>
				<div class="spacer20 clearFix"></div>
                
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Mode of study: </label>
				<div class='fieldBoxLarge' style="width:625px">
				<input type='radio' name='gradModeAlliance' id='gradModeAlliance0'   value='Full-time'  checked  ></input><span >Full-time</span>&nbsp;&nbsp;
				<input type='radio' name='gradModeAlliance' id='gradModeAlliance1'   value='Part-time'    ></input><span >Part-time</span>&nbsp;&nbsp;
				<input type='radio' name='gradModeAlliance' id='gradModeAlliance2'   value='Correspondence'    ></input><span >Correspondence</span>&nbsp;&nbsp;
				<?php if(isset($gradModeAlliance) && $gradModeAlliance!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["gradModeAlliance"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $gradModeAlliance;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradModeAlliance_error'></div></div>
				</div>
				</div>
                <div class="clearFix"></div>
                </div>
			</li>

			<li>
            	<div class="form-box-wrapper">
				<b style="padding-left:10px">XII details:</b>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>State: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12StateAlliance' id='class12StateAlliance'  validate="validateStr"   required="true"   caption="state"   minlength="2"   maxlength="50"     tip="Please enter your XII Institute's state name. E.g. Haryana, Punjab, Maharashtra  etc."   value=''   />
				<?php if(isset($class12StateAlliance) && $class12StateAlliance!=""){ ?>
				  <script>
				      document.getElementById("class12StateAlliance").value = "<?php echo str_replace("\n", '\n', $class12StateAlliance );  ?>";
				      document.getElementById("class12StateAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12StateAlliance_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Main subjects: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12SubjectsAlliance' id='class12SubjectsAlliance'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Please enter the main subjects you studied in XII"   value='' csv="true"    />
				<?php if(isset($class12SubjectsAlliance) && $class12SubjectsAlliance!=""){ ?>
				  <script>
				      document.getElementById("class12SubjectsAlliance").value = "<?php echo str_replace("\n", '\n', $class12SubjectsAlliance );  ?>";
				      document.getElementById("class12SubjectsAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SubjectsAlliance_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer20"></div>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Mode of study: </label>
				<div class='fieldBoxLarge' style="width:625px">
				<input type='radio' name='class12ModeAlliance' id='class12ModeAlliance0'   value='Full-time'  checked  ></input><span >Full-time</span>&nbsp;&nbsp;
				<input type='radio' name='class12ModeAlliance' id='class12ModeAlliance1'   value='Part-time'    ></input><span >Part-time</span>&nbsp;&nbsp;
				<input type='radio' name='class12ModeAlliance' id='class12ModeAlliance2'   value='Correspondence'    ></input><span >Correspondence</span>&nbsp;&nbsp;
				<?php if(isset($class12ModeAlliance) && $class12ModeAlliance!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["class12ModeAlliance"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $class12ModeAlliance;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12ModeAlliance_error'></div></div>
				</div>
				</div>
                <div class="clearFix"></div>
                </div>
			</li>

			<li>
            	<div class="form-box-wrapper">
				<b style="padding-left:10px">X details:</b>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>State: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class10StateAlliance' id='class10StateAlliance'  validate="validateStr"   required="true"   caption="state"   minlength="2"   maxlength="50"     tip="Please enter your X Institute's state name. E.g. Haryana, Punjab, Maharashtra  etc."   value=''   />
				<?php if(isset($class10StateAlliance) && $class10StateAlliance!=""){ ?>
				  <script>
				      document.getElementById("class10StateAlliance").value = "<?php echo str_replace("\n", '\n', $class10StateAlliance );  ?>";
				      document.getElementById("class10StateAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10StateAlliance_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Main subjects: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class10SubjectsAlliance' id='class10SubjectsAlliance'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Please enter the main subjects you studied in X"   value=''  csv="true"   />
				<?php if(isset($class10SubjectsAlliance) && $class10SubjectsAlliance!=""){ ?>
				  <script>
				      document.getElementById("class10SubjectsAlliance").value = "<?php echo str_replace("\n", '\n', $class10SubjectsAlliance );  ?>";
				      document.getElementById("class10SubjectsAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10SubjectsAlliance_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer20"></div>
                
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Mode of study: </label>
				<div class='fieldBoxLarge' style="width:625px">
				<input type='radio' name='class10ModeAlliance' id='class10ModeAlliance0'   value='Full-time'  checked  ></input><span >Full-time</span>&nbsp;&nbsp;
				<input type='radio' name='class10ModeAlliance' id='class10ModeAlliance1'   value='Part-time'    ></input><span >Part-time</span>&nbsp;&nbsp;
				<input type='radio' name='class10ModeAlliance' id='class10ModeAlliance2'   value='Correspondence'    ></input><span >Correspondence</span>&nbsp;&nbsp;
				<?php if(isset($class10ModeAlliance) && $class10ModeAlliance!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["class10ModeAlliance"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $class10ModeAlliance;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10ModeAlliance_error'></div></div>
				</div>
				</div>
                <div class="clearFix"></div>
                </div>
			</li>
			<?php endif;?>


			<li>
				<h3 class="upperCase">Aptitude Score details</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Select:</label>
				<div class='fieldBoxLarge' style="width:625px">
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance0'   value='GMAT' validate="validateCheckedGroup"   required="true"   caption="tests"  ></input><span >GMAT</span>&nbsp;&nbsp;
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance1'   value='CAT'  validate="validateCheckedGroup"   required="true"   caption="tests"  ></input><span >CAT</span>&nbsp;&nbsp;
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance2'   value='XAT'  validate="validateCheckedGroup"   required="true"   caption="tests"  ></input><span >XAT</span>&nbsp;&nbsp;
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance3'   value='MAT'  validate="validateCheckedGroup"   required="true"   caption="tests"  ></input><span >MAT</span>&nbsp;&nbsp;
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance4'   value='ATMA' validate="validateCheckedGroup"   required="true"   caption="tests"   ></input><span >ATMA</span>&nbsp;&nbsp;
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance5'   value='CMAT' validate="validateCheckedGroup"   required="true"   caption="tests"   ></input><span >CMAT</span>&nbsp;&nbsp;
				<input onClick="examSelected(this);" type='checkbox' name='testsAlliance[]' id='testsAlliance6'   value='KMAT'  validate="validateCheckedGroup"   required="true"   caption="tests"  ></input><span >KMAT</span>&nbsp;&nbsp;
				
				<div style='display:none'><div class='errorMsg' id= 'testsAlliance_error'></div></div>
				</div>
				</div>
			</li>

			<li id="GMATScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'  validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
		      
				<div class='additionalInfoRightCol'>
				<label>GMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat" caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
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
<li id="GMATScore2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>
				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'GMAT')!==false){ ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance0'));
				    </script>
				<?php
				    }
				?>
			</li>

			<li id="CATScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'   validate="validateDateForms"  caption="date"       tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			
			
				<div class='additionalInfoRightCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'  validate="validateFloat"   caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''    allowNA = 'true' />
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
			
			<li id="CATScore2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'  validate="validateFloat"  caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
				      document.getElementById("catScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
				</div>
				</div>
				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'CAT')!==false){ ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance1'));
				    </script>
				<?php
				    }
				?>
			</li>

			<li id="XATScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Please enter your XAT examination date"     onmouseover="showTipOnline('Please enter your XAT examination date',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			
				<div class='additionalInfoRightCol'>
				<label>XAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'     validate="validateFloat"   caption="percentile"   minlength="1"   maxlength="5"     tip="Please enter your XAT percentile"   value=''   />
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
			
			<li id="XATScore2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'     validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"      tip="Please enter your XAT composite score"   value=''   />
				<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
				      document.getElementById("xatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
				</div>
				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'XAT')!==false){ ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance2'));
				    </script>
				<?php
				    }
				?>
			</li>

			<li id="MATScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			

			
				<div class='additionalInfoRightCol'>
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
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
			<li id="MATScore2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'MAT')!==false){
				      $examArray = explode(',',$testsAlliance);
				      if(in_array('MAT',$examArray)){
				      ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance3'));
				    </script>
				<?php
				      }
				    }
				?>
			</li>

			<li id="ATMAScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>ATMA Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Please enter your ATMA examination date"     onmouseover="showTipOnline('Please enter your ATMA examination date',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateAlliance'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='atmaDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateAlliance'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($atmaDateOfExaminationAdditional) && $atmaDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $atmaDateOfExaminationAdditional );  ?>";
				      document.getElementById("atmaDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			

				<div class='additionalInfoRightCol'>
				<label>ATMA Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'    validate="validateFloat"   caption="percentile"   minlength="1"   maxlength="5"      tip="Please enter your ATMA percentile"   value=''   />
				<?php if(isset($atmaPercentileAdditional) && $atmaPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $atmaPercentileAdditional );  ?>";
				      document.getElementById("atmaPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			<li id="ATMAScore2" style="display:none;">

				<div class='additionalInfoLeftCol'>
				<label>ATMA Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'      validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Please enter your ATMA composite score"   value=''   />
				<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
				      document.getElementById("atmaScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
				</div>

				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'ATMA')!==false){ ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance4'));
				    </script>
				<?php
				    }
				?>
			</li>

			<li id="CMATScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			

				<div class='additionalInfoRightCol'>
				<label>CMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatPercentileAdditionalAlliance' id='cmatPercentileAdditionalAlliance'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($cmatPercentileAdditionalAlliance) && $cmatPercentileAdditionalAlliance!=""){ ?>
				  <script>
				      document.getElementById("cmatPercentileAdditionalAlliance").value = "<?php echo str_replace("\n", '\n', $cmatPercentileAdditionalAlliance);  ?>";
				      document.getElementById("cmatPercentileAdditionalAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatPercentileAdditionalAlliance_error'></div></div>
				</div>
				</div>
			</li>
			<li id="CMATScore2" style="display:none;">

				<div class='additionalInfoLeftCol'>
				<label>CMAT Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
				      document.getElementById("cmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
				</div>
				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'CMAT')!==false){ ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance5'));
				    </script>
				<?php
				    }
				?>
			</li>
			
			<li id="KMATScore1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>KMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kmatDateOfExaminationAdditional' id='kmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('kmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='kmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('kmatDateOfExaminationAdditional'),'kmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($kmatDateOfExaminationAdditional) && $kmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("kmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $kmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("kmatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			

			
				<div class='additionalInfoRightCol'>
				<label>KMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kmatPercentileAdditional' id='kmatPercentileAdditional'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($kmatPercentileAdditional) && $kmatPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("kmatPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $kmatPercentileAdditional);  ?>";
				      document.getElementById("kmatPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kmatPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			<li id="KMATScore2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>KMAT Composite Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kmatScoreAdditional' id='kmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($kmatScoreAdditional) && $kmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("kmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $kmatScoreAdditional );  ?>";
				      document.getElementById("kmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kmatScoreAdditional_error'></div></div>
				</div>
				</div>
				<?php
				    if(isset($testsAlliance) && $testsAlliance!="" && strpos($testsAlliance,'KMAT')!==false){ ?>
				    <script>
					    examSelected(document.getElementById('testsAlliance6'));
				    </script>
				<?php
				    }
				?>
			</li>
			
			

			<?php if(isset($testsAlliance) && $testsAlliance!=""){ ?>
			<script>
			    objCheckBoxes = document.forms["OnlineForm"].elements["testsAlliance[]"];
			    var countCheckBoxes = objCheckBoxes.length;
			    for(var i = 0; i < countCheckBoxes; i++){
				      objCheckBoxes[i].checked = false;
				      <?php $arr = explode(",",$testsAlliance);
					    for($x=0;$x<count($arr);$x++){ ?>
						  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
							  objCheckBoxes[i].checked = true;
							  //examSelected(objCheckBoxes[i]);
						  }
				      <?php
					    }
				      ?>
			    }
			</script>
			<?php } ?>



			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Statement of purpose</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>What motivates you to apply to the Alliance School of Business, Alliance University?: </label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='motivatesAlliance' id='motivatesAlliance'  validate="validateStr"   required="true"   caption="Motivation"   minlength="2"   maxlength="750"     tip="Write a short paragraph of not more than 150 words"  style="width:600px; height:74px; padding:5px"   ></textarea>
				<?php if(isset($motivatesAlliance) && $motivatesAlliance!=""){ ?>
				  <script>
				      document.getElementById("motivatesAlliance").value = "<?php echo str_replace("\n", '\n', $motivatesAlliance );  ?>";
				      document.getElementById("motivatesAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motivatesAlliance_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>What is your career vision and why is this choice meaningful to you?: </label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='careerVisionAlliance' id='careerVisionAlliance'  validate="validateStr"   required="true"   caption="Career Vision"   minlength="2"   maxlength="750"     tip="Write a short paragraph of not more than 150 words"  style="width:600px; height:74px; padding:5px"   ></textarea>
				<?php if(isset($careerVisionAlliance) && $careerVisionAlliance!=""){ ?>
				  <script>
				      document.getElementById("careerVisionAlliance").value = "<?php echo str_replace("\n", '\n', $careerVisionAlliance );  ?>";
				      document.getElementById("careerVisionAlliance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'careerVisionAlliance_error'></div></div>
				</div>
				</div>
			</li>


			<?php
			$workCompanies = array();
			if(is_array($educationDetails)) {
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						if($educationDetail['fieldName'] == 'weCompanyName') {
							$workCompanies['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weCompanyName_mul_'.$i) {
									$workCompanies['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
					}
				}
			}
			
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$addressFieldName = 'orgnAddressAlliance'.$workCompanyKey;
					$addressFieldValue = $$addressFieldName;
					$telFieldName = 'telNumAlliance'.$workCompanyKey;
					$telFieldvalue = $$telFieldName;
					$emailFieldName = 'emailNumAlliance'.$workCompanyKey;
					$emailFieldvalue = $$emailFieldName;
					$mobFieldName = 'mobNumAlliance'.$workCompanyKey;
					$mobFieldvalue = $$mobFieldName;
					$j++;

			?>

				<li id="companyHistory3">
				<?php if($j==1){ ?><h3 class="upperCase">Employment History</h3><?php } ?>                
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Address of <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input style="width:590px;" type='text' name='<?php echo $addressFieldName; ?>' id='<?php echo $addressFieldName; ?>'  validate="validateStr"  caption="address"   minlength="2"   maxlength="250"     tip="Please enter the address of <?php echo $workCompany; ?>"   value=''   />
				<?php if(isset($addressFieldValue) && $addressFieldValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $addressFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $addressFieldValue );  ?>";
				      document.getElementById("<?php echo $addressFieldName; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $addressFieldName; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<?php
				}
			}
			?>

			<li id="hostelAccom">
				<h3 class="upperCase">Hostel Accommodation</h3>
				<div class='additionalInfoLeftCol'>
				<label>Do you need Hostel Accommodation ?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='hostelAlliance' id='hostelAlliance0'   value='Yes'    ></input><span >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='hostelAlliance' id='hostelAlliance1'   value='No'  checked  ></input><span >No</span>&nbsp;&nbsp;
				<?php if(isset($hostelAlliance) && $hostelAlliance!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["hostelAlliance"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $hostelAlliance;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hostelAlliance_error'></div></div>
				</div>
				</div>
			</li>
			


		<?php if(is_array($gdpiLocations)): ?>
			<li  id="gdpiLocationli">    
			<h3 class="upperCase">GD/PI location</h3>
				<label style="font-weight:normal">GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation){
				    if($sessionAlliance == 'Jan' && in_array($gdpiLocation['city_id'], array(180,138)))
				    {
				      continue;
				    }
				  ?>
						<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
				<?php } ?>
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
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				<ul>
                            	<li style="margin-bottom:5px;">I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and other important information as indicated in the prospectus.</li>
                                <li style="margin-bottom:5px;">I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I understand that any false or misleading information given by me may lead to the cancellation of admission or expulsion from the course at any stage.</li>
                                <li style="margin-bottom:5px;">I undertake to abide by the rules and regulations of Alliance University School of Business as prescribed from time to time. If I violate at any given point of time any of the stipulated rules and regulations, the University is free to initiate appropriate disciplinary action against me.</li>
                            </ul>
				<div class="spacer10 clearFix"></div>
				<div>
				<input type='checkbox' name='agreeToTermsAlliance' id='agreeToTermsAlliance' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsAlliance) && $agreeToTermsAlliance!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsAlliance"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsAlliance);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				</script>
			      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsAlliance_error'></div></div>


				</div>
				</div>
			</li>
			<?php endif;?>
			

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
  
  function checkNationality(id)
  {
      if(id == 1){
	  $('nDetailsAlliance').disabled = false;
	  $('nDetailsAlliance').setAttribute('required','true');
	  $('nDetailsAlliance_error').innerHTML = '';
	  $('nDetailsAlliance_error').parentNode.style.display = 'none';

      }
      else{
	  var selObj = document.getElementById("nDetailsAlliance"); 
	  selObj.selectedIndex= 0;
	  $('nDetailsAlliance').disabled = true;
	  $('nDetailsAlliance').removeAttribute('required');
	  $('nDetailsAlliance_error').innerHTML = '';
	  $('nDetailsAlliance_error').parentNode.style.display = 'none';
      }
  }
  
  function checkSession(id)
  {
      if(id == 0){
      dropdownElement = $j("#preferredGDPILocation");
      dropdownElement.find('option[value=180]').remove();
      dropdownElement.find('option[value=138]').remove();
      }
      else if(id == 1){
	      dropdownElement = $j("#preferredGDPILocation");
	if (dropdownElement.find('option[value=180]').length == 0 && dropdownElement.find('option[value=138]').length == 0 ) {
	var Option1 = "<option value='"+"180"+"'>Ranchi</option>"; 
	$j("#preferredGDPILocation").append(Option1);
	var Option2 = "<option value='"+"138"+"'>Lucknow</option>"; 
	$j("#preferredGDPILocation").append(Option2);
	}
	



      }
  }

  
  
  
  

function checkRefEmail(objNumber){
      if(objNumber==1) sId = 2; else sId = 1;
      if($('ref'+objNumber+'EmailAlliance').value == $('ref'+sId+'EmailAlliance').value && $('ref'+objNumber+'EmailAlliance').value!=''){
	  $('ref'+objNumber+'EmailAlliance').value = '';
	  $('ref'+objNumber+'EmailAlliance_error').innerHTML = 'The Emails of references cannot be same';
	  $('ref'+objNumber+'EmailAlliance_error').parentNode.style.display = '';
	  return false;
      }
      else{
	  $('ref'+objNumber+'EmailAlliance_error').innerHTML = '';
	  $('ref'+objNumber+'EmailAlliance_error').parentNode.style.display = 'none';
	  return true;
      }
  }

  function checkRefPhone(objNumber){
      if(objNumber==1) sId = 2; else sId = 1;
      if($('ref'+objNumber+'PhoneAlliance').value == $('ref'+sId+'PhoneAlliance').value && $('ref'+objNumber+'PhoneAlliance').value!=''){
	  $('ref'+objNumber+'PhoneAlliance').value = '';
	  $('ref'+objNumber+'PhoneAlliance_error').innerHTML = 'The Phone number of references cannot be same';
	  $('ref'+objNumber+'PhoneAlliance_error').parentNode.style.display = '';
	  return false;
      }
      else{
	  $('ref'+objNumber+'PhoneAlliance_error').innerHTML = '';
	  $('ref'+objNumber+'PhoneAlliance_error').parentNode.style.display = 'none';
	  return true;
      }
  }

function addRemoveReqAndShowhide(val){
	if(val==0){
		for(var i=1;i<=2;i++){ 
			$('references'+i).style.display='none';
			$('accomplishments'+i).style.display='none';
			$('ref'+i+'NameAlliance').removeAttribute('required');
			$('ref'+i+'OccupationAlliance').removeAttribute('required');
			$('ref'+i+'AddressAlliance').removeAttribute('required');
			$('ref'+i+'PhoneAlliance').removeAttribute('required');
			$('ref'+i+'EmailAlliance').removeAttribute('required');
			$('pc'+i+'Alliance').removeAttribute('required');
			$('ref'+i+'NameAlliance').value='';
			$('ref'+i+'OccupationAlliance').value='';
			$('ref'+i+'AddressAlliance').value='';
			$('ref'+i+'PhoneAlliance').value='';
			$('ref'+i+'EmailAlliance').value='';
			$('pc'+i+'Alliance').value='';
		}
	}else{
		for(var i=1;i<=2;i++){
			$('references'+i).style.display='';
			$('accomplishments'+i).style.display='';
			$('ref'+i+'NameAlliance').setAttribute('required','true');
			$('ref'+i+'OccupationAlliance').setAttribute('required','true');
			$('ref'+i+'AddressAlliance').setAttribute('required','true');
			$('ref'+i+'PhoneAlliance').setAttribute('required','true');
			$('ref'+i+'EmailAlliance').setAttribute('required','true');
			$('pc'+i+'Alliance').setAttribute('required','true');
		}
	}

}
function showhideHostelAccom(action){
	if(action=='show'){
		$('hostelAccom').style.display='';
	}
	else{
		$('hostelAccom').style.display='none';
		$('hostelAlliance0').value='';
		$('hostelAlliance1').value='';
	}
}

function showhideGDPI(action){
	$('preferredGDPILocation').selectedIndex='0';
	if(action=='show'){
		$('gdpiLocationli').style.display='';
		$('preferredGDPILocation').setAttribute('required','true');
	}
	else{
		$('gdpiLocationli').style.display='none';
		$('preferredGDPILocation').removeAttribute('required');
	}
}

function showhideACPCourse(action){
 	  var objCheckBoxes = document.forms["OnlineForm"].elements["coursesAllianceACP[]"];
	  var countCheckBoxes = objCheckBoxes.length;
	  for(var i = 0; i < countCheckBoxes; i++){
		objCheckBoxes[i].checked = false;
	  }
	
	if(action == 'show')
		$('acpCourses').style.display='';
	else		
		$('acpCourses').style.display='none';
}

function showhideCompanyHistory(action){
	if(action=='show'){
		$('companyHistory1').style.display='';
		$('companyHistory2').style.display='';
		$('companyHistory3').style.display='';
		for(var i=0;i<2;i++){
			if($('telNumAlliance_mul_'+i))
				$('telNumAlliance_mul_'+i).setAttribute('required','true');
			if($('emailNumAlliance_mul_'+i))
				$('emailNumAlliance_mul_'+i).setAttribute('required','true');
			if($('mobNumAlliance_mul_'+i))
				$('mobNumAlliance_mul_'+i).setAttribute('required','true');
			if($('orgnAddressAlliance_mul_'+i))
				$('orgnAddressAlliance_mul_'+i).setAttribute('required','true');
		}
	}
	else{
		$('companyHistory1').style.display='none';
		$('companyHistory2').style.display='none';
		$('companyHistory3').style.display='none';
		for(var i=0;i<2;i++){
			if($('telNumAlliance_mul_'+i))
				$('telNumAlliance_mul_'+i).removeAttribute('required');
			if($('emailNumAlliance_mul_'+i))
				$('emailNumAlliance_mul_'+i).removeAttribute('required');
			if($('mobNumAlliance_mul_'+i))
				$('mobNumAlliance_mul_'+i).removeAttribute('required');
			if($('orgnAddressAlliance_mul_'+i))
				$('orgnAddressAlliance_mul_'+i).removeAttribute('required');
		}
	}
}
  </script>

<?php if(isset($coursesAlliance) && $coursesAlliance!='MBA'):?>
<script>
for(var i=1;i<=2;i++){
	$('references'+i).style.display='';
	$('accomplishments'+i).style.display='';
	$('ref'+i+'NameAlliance').setAttribute('required','true');
	$('ref'+i+'OccupationAlliance').setAttribute('required','true');
	$('ref'+i+'AddressAlliance').setAttribute('required','true');
	$('ref'+i+'PhoneAlliance').setAttribute('required','true');
	$('ref'+i+'EmailAlliance').setAttribute('required','true');
	$('pc'+i+'Alliance').setAttribute('required','true');
}
</script>
<?php endif;?>
<?php if(!isset($coursesAlliance) || $coursesAlliance=='MBA'):?>
<script>
$('preferredGDPILocation').setAttribute('required','true');
</script>
<?php endif;?>
<?php if(isset($coursesAlliance) && $coursesAlliance!='MBA'):?>
<script>
for(var i=0;i<2;i++){
if($('telNumAlliance_mul_'+i))
	$('telNumAlliance_mul_'+i).setAttribute('required','true');
if($('emailNumAlliance_mul_'+i))
	$('emailNumAlliance_mul_'+i).setAttribute('required','true');
if($('mobNumAlliance_mul_'+i))
	$('mobNumAlliance_mul_'+i).setAttribute('required','true');
if($('orgnAddressAlliance_mul_'+i))
	$('orgnAddressAlliance_mul_'+i).setAttribute('required','true');
}
</script>
<?php endif;?>
