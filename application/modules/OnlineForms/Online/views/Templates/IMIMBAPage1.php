<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "GMAT" || obj.value == "CAT"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	}
	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    $(key+'2').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+'1').style.display = '';
		    $(key+'2').style.display = '';
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

  function calculateFees(){
	objCheckBoxes = document.forms["OnlineForm"].elements["courseIMI[]"];
	var countCheckBoxes = objCheckBoxes.length;
	var number=0;
	for(var i = 0; i < countCheckBoxes; i++){
		if(objCheckBoxes[i].checked == true){
			number++;
		}
	}
	fees = 1500 + (number*500);
	$('feesIMI').innerHTML = fees;
  }
</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Additional Personal Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select name='categoryIMI' id='categoryIMI'    tip="Please select the appropriate category for your application. If you do not have a sponsorship from your company or you are not applying as an NRI/Foreign candidate, then select Self-Sponsored candidate."       onmouseover="showTipOnline('Please select the appropriate category for your application. If you do not have a sponsorship from your company or you are not applying as an NRI/Foreign candidate, then select Self-Sponsored candidate.',this);" onmouseout="hidetip();" >
					<option value='Self-Sponsored Candidate' >Self-Sponsored Candidate</option>
					<option value='Company-Sponsored Candidate' >Company-Sponsored Candidate</option>
					<option value='Foreign or NRI Sponsored' >Foreign or NRI Sponsored</option>
				</select>
				<?php if(isset($categoryIMI) && $categoryIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("categoryIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $categoryIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryIMI_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Course Information</h3>
				<div class='additionalInfoLeftCol' style="width:600px;">
				<label>Select course: </label>
				<div class='fieldBoxLarge' style="width:290px;">
				<input onClick="calculateFees();" type='checkbox' name='courseIMI[]' id='courseIMI0'   value='PGDM-Bhubaneswar'   onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" >PGDM-Bhubaneswar</span>&nbsp;&nbsp;
				<!--<input onClick="calculateFees();" type='checkbox' name='courseIMI[]' id='courseIMI1'   value='PGDM-Delhi'     onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" >PGDM-Delhi</span>&nbsp;&nbsp;-->
				<input onClick="calculateFees();" type='checkbox' name='courseIMI[]' id='courseIMI1'   value='PGDM-Kolkata'     onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" >PGDM-Kolkata</span>&nbsp;&nbsp;
				<!--<input onClick="calculateFees();" type='checkbox' name='courseIMI[]' id='courseIMI3'   value='PGDMHR-Delhi'     onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the course according to your preference. You can apply to multiple courses. Application fee is INR 2000 for one course and INR 500 for each additional course thereafter.',this);" onmouseout="hidetip();" >PGDMHR-Delhi</span>&nbsp;&nbsp;-->
				
				<div style='display:none'><div class='errorMsg' id= 'courseIMI_error'></div></div>
				<div style="color:gray; font-size:12px;margin-top:5px;">Fees is Rs. <span id='feesIMI'>1500</span></div>
				</div>
				<?php if(isset($courseIMI) && $courseIMI!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["courseIMI[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    var number=0;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$courseIMI);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
								  number++;
							  }
					      <?php
						    }
					      ?>
				    }
				    fees = 1500 + (number*500);
				    $('feesIMI').innerHTML = fees;
				</script>
			      <?php } ?>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Academic Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th Medium of Instruction: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='class10MediumIMI' id='class10MediumIMI'    tip="Please enter the medium of instruction for your class 10th studies."    validate="validateSelect"   required="true"   caption="Medium of Instruction"   onmouseover="showTipOnline('Please enter the medium of instruction for your class 10th studies.',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='English' >English</option><option value='Hindi' >Hindi</option><option value='Regional' >Regional</option><option value='Others' >Others</option></select>
				<?php if(isset($class10MediumIMI) && $class10MediumIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class10MediumIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class10MediumIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10MediumIMI_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Medium of Instruction: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='class12MediumIMI' id='class12MediumIMI'    tip="Please enter the medium of instruction for your class 12th studies."    validate="validateSelect"   required="true"   caption="Medium of Instruction"   onmouseover="showTipOnline('Please enter the medium of instruction for your class 12th studies.',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='English' >English</option><option value='Hindi' >Hindi</option><option value='Regional' >Regional</option><option value='Others' >Others</option></select>
				<?php if(isset($class12MediumIMI) && $class12MediumIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class12MediumIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class12MediumIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12MediumIMI_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Specialization in 12th: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='class12SpecIMI' id='class12SpecIMI'    tip="Please enter the specialization or stream that you studies in your class 12th."    validate="validateSelect"   required="true"   caption="Specialization"   onmouseover="showTipOnline('Please enter the specialization or stream that you studies in your class 12th.',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='Commerce' >Commerce</option><option value='Science' >Science</option><option value='Arts' >Arts</option><option value='Others' >Others</option></select>
				<?php if(isset($class12SpecIMI) && $class12SpecIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class12SpecIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class12SpecIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SpecIMI_error'></div></div>
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
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Select the university type for your <?php echo $graduationCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='gradCollegeTypeIMI' id='gradCollegeTypeIMI'    tip="Please select the type of universtity for your <?php echo $graduationCourseName;?> course"    validate="validateSelect"   required="true"   caption="University type"   onmouseover="showTipOnline('Please select the type of universtity for your <?php echo $graduationCourseName;?> course',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='Central University' >Central University</option><option value='State University' >State University</option><option value='IIT' >IIT</option><option value='NIT' >NIT</option><option value='Private University' >Private University</option><option value='Deemed University' >Deemed University</option><option value='Foreign University' >Foreign University</option><option value='Others ' >Others </option>
				</select>
				<?php if(isset($gradCollegeTypeIMI) && $gradCollegeTypeIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradCollegeTypeIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradCollegeTypeIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradCollegeTypeIMI_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mode for your <?php echo $graduationCourseName;?> course: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='gradModeIMI' id='gradModeIMI'    tip="Please select the mode of study for your <?php echo $graduationCourseName;?> course"    validate="validateSelect"   required="true"   caption="Mode"   onmouseover="showTipOnline('Please select the mode of study for your <?php echo $graduationCourseName;?> course',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value='Part Time' >Part Time</option><option value='Correspondence' >Correspondence</option><option value='Distance' >Distance</option></select>
				<?php if(isset($gradModeIMI) && $gradModeIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradModeIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradModeIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradModeIMI_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Medium of Instruction for your <?php echo $graduationCourseName;?> course: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='gradMediumIMI' id='gradMediumIMI'    tip="Please enter the medium of insruction for your <?php echo $graduationCourseName;?> course"    validate="validateSelect"   required="true"   caption="Medium of Instruction"   onmouseover="showTipOnline('Please enter the medium of insruction for your <?php echo $graduationCourseName;?> course',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='English' >English</option><option value='Other' >Other</option></select>
				<?php if(isset($gradMediumIMI) && $gradMediumIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradMediumIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradMediumIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMediumIMI_error'></div></div>
				</div>
				</div>
			</li>

			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$collegeType = 'otherCourseCollegeType_mul_'.$otherCourseId;
					$collegeTypeVal = $$collegeType;
					$Mode = 'otherCourseMode_mul_'.$otherCourseId;
					$ModeVal = $$Mode;
					$Medium = 'otherCourseMedium_mul_'.$otherCourseId;
					$MediumVal = $$Medium;
					$i++;

			?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Select the university type for your <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='<?php echo $collegeType; ?>' id='<?php echo $collegeType; ?>'    tip="Please select the type of universtity for your <?php echo $otherCourseName;?> course"    validate="validateSelect"   required="true"   caption="University type"   onmouseover="showTipOnline('Please select the type of universtity for your <?php echo $otherCourseName;?> course',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='Central University' >Central University</option><option value='State University' >State University</option><option value='IIT' >IIT</option><option value='NIT' >NIT</option><option value='Private University' >Private University</option><option value='Deemed University' >Deemed University</option><option value='Foreign University' >Foreign University</option><option value='Others ' >Others </option>
				</select>
				<?php if(isset($collegeTypeVal) && $collegeTypeVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $collegeType; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $collegeTypeVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $collegeType; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mode for your <?php echo $otherCourseName;?> course: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='<?php echo $Mode; ?>' id='<?php echo $Mode; ?>'    tip="Please select the mode of study for your <?php echo $otherCourseName;?> course"    validate="validateSelect"   required="true"   caption="Mode"   onmouseover="showTipOnline('Please select the mode of study for your <?php echo $otherCourseName;?> course',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value='Part Time' >Part Time</option><option value='Correspondence' >Correspondence</option><option value='Distance' >Distance</option></select>
				<?php if(isset($ModeVal) && $ModeVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $Mode; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ModeVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $Mode; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Medium of Instruction for your <?php echo $otherCourseName;?> course: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='<?php echo $Medium; ?>' id='<?php echo $Medium; ?>'    tip="Please enter the medium of insruction for your <?php echo $otherCourseName;?> course"    validate="validateSelect"   required="true"   caption="Medium of Instruction"   onmouseover="showTipOnline('Please enter the medium of insruction for your <?php echo $otherCourseName;?> course',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='English' >English</option><option value='Other' >Other</option></select>
				<?php if(isset($MediumVal) && $MediumVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $Medium; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $MediumVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $Medium; ?>_error'></div></div>
				</div>
				</div>
			</li>
			<?php
				}
			}
			?>
			<?php endif; ?>
			<li>
				<h3 class="upperCase">Additional Exams Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>TESTS: </label>
				<div class='fieldBoxLarge'>
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIMI[]' id='testNamesIMI0'   value='CAT'  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIMI[]' id='testNamesIMI1'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<?php if(isset($testNamesIMI) && $testNamesIMI!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesIMI[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesIMI);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesIMI_error'></div></div>
				</div>
				</div>
			</li>

			
			<li id="cat1" style="display:none;">

				<div class='additionalInfoLeftCol'>
				<label>CAT Date of Examination: </label>
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

				<div class='additionalInfoRightCol'>
				<label>CAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
				<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
				      document.getElementById("catScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
				</div>
				</div>
				
			</li>

			<li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">

				<div class='additionalInfoLeftCol'>
				<label>CAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="roll number"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="percentile"   minlength="2"   maxlength="8"    tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA="true" />
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
			<?php
			    if(isset($testNamesIMI) && $testNamesIMI!="" && strpos($testNamesIMI,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIMI0'));
			    </script>
			<?php
			    }
			?>

			<li id="gmat1" style="display:none;">

				<div class='additionalInfoLeftCol'>
				<label>GMAT Date of Examination: </label>
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

				<div class='additionalInfoRightCol'>
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>
				
			</li>

			<li id="gmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'     validate="validateStr"    caption="roll number"   minlength="2"   maxlength="50"     tip="Mention your Registration number for the exam." value=''   />
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
				<label>GMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat"    caption="percentile"   minlength="2"   maxlength="8"        tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''  allowNA="true" />
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
			<?php
			    if(isset($testNamesIMI) && $testNamesIMI!="" && strpos($testNamesIMI,'GMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIMI1'));
			    </script>
			<?php
			    }
			?>
			

			
			<?php if($action != 'updateScore'):?>
			
			<li>
				<h3 class="upperCase">Additional Experience Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Work Experience as on 1st March 2013: </label>
				<div class='fieldBoxLarge'>
				<select name='workExIMI' id='workExIMI'    tip="Enter the correct range for your work experience"    validate="validateSelect"   required="true"   caption="Work Experience"   onmouseover="showTipOnline('Enter the correct range for your work experience',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='No Experience'>No Experience</option><option value='Less than 12 Months' >Less than 12 Months</option><option value='24 Months' >24 Months</option><option value='36 Months' >36 Months</option><option value='Greater than 36 Months' >Greater than 36 Months</option></select>
				<?php if(isset($workExIMI) && $workExIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("workExIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $workExIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'workExIMI_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'  style="width:800px;">
				<label>How did you hear about IMI: </label>
				<div class='fieldBoxLarge'  style="width:450px;">
				<input type='checkbox' name='hearIMI[]' id='hearIMI0'   value='Coaching Centre'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >Coaching Centre</span>&nbsp;&nbsp;
				<input type='checkbox' name='hearIMI[]' id='hearIMI1'   value='Family/Friends'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >Family/Friends</span>&nbsp;&nbsp;
				<input type='checkbox' name='hearIMI[]' id='hearIMI2'   value='MBA universe'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >MBA universe</span>&nbsp;&nbsp;
				<br/>
				<input type='checkbox' name='hearIMI[]' id='hearIMI3'   value='Newspapers/Magazines'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >Newspapers/Magazines</span>&nbsp;&nbsp;
				<input type='checkbox' name='hearIMI[]' id='hearIMI4'   value='Others'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<input type='checkbox' name='hearIMI[]' id='hearIMI5'   value='Pagalguy.com'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >Pagalguy.com</span>&nbsp;&nbsp;
				<br/>
				<input type='checkbox' name='hearIMI[]' id='hearIMI6'   value='Twitter/Facebook/Orkut'     onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select where you first heard about IMI. You can select multiple option.',this);" onmouseout="hidetip();" >Twitter/Facebook/Orkut</span>&nbsp;&nbsp;
				<?php if(isset($hearIMI) && $hearIMI!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["hearIMI[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$hearIMI);
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
				
				<div style='display:none'><div class='errorMsg' id= 'hearIMI_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Hostel accomodation: </label>
				<div class='fieldBoxLarge'>
				<select class="selectSmall" name='hostelIMI' id='hostelIMI'    tip="Do you require Hostel accomodation?"    validate="validateSelect"   required="true"   caption="Hostel requirement"   onmouseover="showTipOnline('Do you require Hostel accomodation?',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Yes' >Yes</option><option value='No' >No</option></select>
				<?php if(isset($hostelIMI) && $hostelIMI!=""){ ?>
			      <script>
				  var selObj = document.getElementById("hostelIMI"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $hostelIMI;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hostelIMI_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
                <h3 class="upperCase">Interview Center choice</h3>
            	<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">First Choice: </label>
				<div class='fieldBoxLarge'>
			<select blurMethod="checkValidCampusPreference(this.id);" class="selectSmall" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select First choice for the interview center',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Interview location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
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
                </div>

            	<div class="additionalInfoRightCol">
				<label style="font-weight:normal">Second Choice: </label>
				<div class='fieldBoxLarge'>
			<select blurMethod="checkValidCampusPreference(this.id);"  class="selectSmall" name='pref2IMI' id='pref2IMI' onmouseover="showTipOnline('Select Second choice for the interview center',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Interview location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
				<?php endforeach; ?>
				</select>
				<?php if(isset($pref2IMI) && $pref2IMI!=""){ ?>
				<script>
				var selObj = document.getElementById("pref2IMI"); 
				var A= selObj.options, L= A.length;
				while(L){
					if (A[--L].value== "<?php echo $pref2IMI;?>"){
					selObj.selectedIndex= L;
					L= 0;
					}
				}
				</script>
			      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'pref2IMI_error'></div></div>
				</div>
                </div>

			</li>
			<?php endif; ?>
			
			<li>
		            	<div class="additionalInfoLeftCol" style="width:950px">
				<label style="font-weight:normal; padding-top:0">Disclaimer:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I certfiy that the particulars given by me are true to the best of my knowledge and belief. I understand that IMI, India will have the right to ask me to withdraw from the programme if any discrepancies are found in the information furnished. I will also abide by the general discipline and norms of conduct during the programme.
				<div class="spacer10 clearFix"></div>
				<div>
				<input type='checkbox' name='agreeToTermsIMI' id='agreeToTermsIMI' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsIMI) && $agreeToTermsIMI!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsIMI"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsIMI);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsIMI_error'></div></div>


				</div>
				</div>
	                </div>
			</li>
			<?php endif; ?>
			
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

  function checkValidCampusPreference(id){
	if(id=='preferredGDPILocation'){ sId = 'pref2IMI';  }
	else if(id=='pref2IMI'){ sId = 'preferredGDPILocation';  }
	var selectedPrefObj = document.getElementById(id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById(sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	if( (selectedPref == selPref1 && selectedPref!='' ) ){
		$(id+'_error').innerHTML = 'Same preference can\'t be set.';
		$(id+'_error').parentNode.style.display = '';
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
		$(id+'_error').innerHTML = '';
		$(id+'_error').parentNode.style.display = 'none';
	}
	return true;
  }
  
  </script>
