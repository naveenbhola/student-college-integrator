<script>
  function checkExams(){
      var selectedPrefObj = document.getElementById('gradDurationMITSOB'); 
      var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
      if(selectedPref == '04'){
	  displayExamSet(4);
	  hideExamSet(5);
      }
      else if(selectedPref == '05'){
	  displayExamSet(4);
	  displayExamSet(5);
      }
      else if(selectedPref == '03'){
	  hideExamSet(4);
	  hideExamSet(5);
      }
  }

  function displayExamSet(id){
	  $('grad'+id+'Details').style.display = '';
	  $('gradYear'+id+'MarksMITSOB').setAttribute('required','true');
	  $('gradYear'+id+'MarksMITSOB_error').innerHTML = '';
	  $('gradYear'+id+'MarksMITSOB_error').parentNode.style.display = 'none';
	  $('gradYear'+id+'TotalMarksMITSOB').setAttribute('required','true');
	  $('gradYear'+id+'TotalMarksMITSOB_error').innerHTML = '';
	  $('gradYear'+id+'TotalMarksMITSOB_error').parentNode.style.display = 'none';
  }

  function hideExamSet(id){
	  $('grad'+id+'Details').style.display = 'none';
	  $('gradYear'+id+'MarksMITSOB').removeAttribute('required');
	  $('gradYear'+id+'MarksMITSOB_error').innerHTML = '';
	  $('gradYear'+id+'MarksMITSOB_error').parentNode.style.display = 'none';
	  $('gradYear'+id+'TotalMarksMITSOB').removeAttribute('required');
	  $('gradYear'+id+'TotalMarksMITSOB_error').innerHTML = '';
	  $('gradYear'+id+'TotalMarksMITSOB_error').parentNode.style.display = 'none';
  }

  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	$('examDetails1').style.display = '';
	$('examDetails2').style.display = '';
	if(obj.value == "CET" || '<?php echo $scoreMITSOB;?>'!=''){
	    $('scoreMITSOB').value = '<?php echo $scoreMITSOB;?>';
	    $('percentileMITSOB').value = '<?php echo $percentileMITSOB;?>';
	}
	else if(obj.value == "XAT"){
	    $('scoreMITSOB').value = '<?php echo $xatScoreAdditional;?>';
	    $('percentileMITSOB').value = '<?php echo $xatPercentileAdditional;?>';
	}
	else if(obj.value == "MAT"){
	    $('scoreMITSOB').value = '<?php echo $matScoreAdditional;?>';
	    $('percentileMITSOB').value = '<?php echo $matPercentileAdditional;?>';
	}
	else if(obj.value == "CAT"){
	    $('scoreMITSOB').value = '<?php echo $catScoreAdditional;?>';
	    $('percentileMITSOB').value = '<?php echo $catPercentileAdditional;?>';
	}
	else if(obj.value == "ATMA"){
	    $('scoreMITSOB').value = '<?php echo $atmaScoreAdditional;?>';
	    $('percentileMITSOB').value = '<?php echo $atmaPercentileAdditional;?>';
	}
  }

</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

			<li>
				<h3 class="upperCase">Qualifying Exams</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Details of appearance at any of the qualifying examination (Please attach a scanned copy of score card): </label>
				<div class='fieldBoxLarge'  style="width:600px">
				<input onClick="checkTestScore(this);" type='radio' name='examMITSOB' id='examMITSOB0'   value='CAT'  onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='radio' name='examMITSOB' id='examMITSOB1'   value='MAT'     onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='radio' name='examMITSOB' id='examMITSOB2'   value='CET'     onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" >CET</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='radio' name='examMITSOB' id='examMITSOB3'   value='XAT'     onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='radio' name='examMITSOB' id='examMITSOB4'   value='ATMA'     onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the qualifying examination that you appeared for and enter the test score details.',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<?php if(isset($examMITSOB) && $examMITSOB!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["examMITSOB"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $examMITSOB;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'examMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li id="examDetails1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='scoreMITSOB' id='scoreMITSOB'  validate="validateFloat"   required="true"   caption="score"   minlength="1"   maxlength="8"     tip="Enter the score you got in this test."   value=''   />
				<?php if(isset($scoreMITSOB) && $scoreMITSOB!=""){ ?>
				  <script>
				      document.getElementById("scoreMITSOB").value = "<?php echo str_replace("\n", '\n', $scoreMITSOB );  ?>";
				      document.getElementById("scoreMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'scoreMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='yearEntranceMITSOB' id='yearEntranceMITSOB'  validate="validateInteger"   required="true"   caption="year"   minlength="4"   maxlength="4"     tip="Enter the year in which you took the entrance test."   value=''   />
				<?php if(isset($yearEntranceMITSOB) && $yearEntranceMITSOB!=""){ ?>
				  <script>
				      document.getElementById("yearEntranceMITSOB").value = "<?php echo str_replace("\n", '\n', $yearEntranceMITSOB );  ?>";
				      document.getElementById("yearEntranceMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearEntranceMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li id="examDetails2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='percentileMITSOB' id='percentileMITSOB'  validate="validateFloat"   required="true"   caption="percentile"   minlength="1"   maxlength="5"     tip="Enter the percentile you got in this test."   value=''   />
				<?php if(isset($percentileMITSOB) && $percentileMITSOB!=""){ ?>
				  <script>
				      document.getElementById("percentileMITSOB").value = "<?php echo str_replace("\n", '\n', $percentileMITSOB );  ?>";
				      document.getElementById("percentileMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'percentileMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<?php
			    if(isset($examMITSOB) && $examMITSOB!="" && strpos($examMITSOB,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examMITSOB0'));
			    </script>
			<?php
			    }
			    else if(isset($examMITSOB) && $examMITSOB!="" && strpos($examMITSOB,'MAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examMITSOB1'));
			    </script>
			<?php
			    }
			    if(isset($examMITSOB) && $examMITSOB!="" && strpos($examMITSOB,'CET')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examMITSOB2'));
			    </script>
			<?php
			    }
			    if(isset($examMITSOB) && $examMITSOB!="" && strpos($examMITSOB,'XAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examMITSOB3'));
			    </script>
			<?php
			    }
			    if(isset($examMITSOB) && $examMITSOB!="" && strpos($examMITSOB,'ATMA')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examMITSOB4'));
			    </script>
			<?php
			    }
			?>

	
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Course Preference</h3>
				<div class='additionalInfoLeftCol'>
				<label>Course First Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(1);" name='programMITSOB1' id='programMITSOB1'    tip="Please select the course of your choice."    validate="validateSelect"   required="true"   caption="course"   onmouseover="showTipOnline('Please select the course of your choice.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='PGDM' >PGDM</option><option value='PGDM-Marketing' >PGDM-Marketing</option><option value='PGDM-Finance' >PGDM-Finance</option><option value='PGDM-Human Resource' >PGDM-Human Resource</option>
				</select>
				<?php if(isset($programMITSOB1) && $programMITSOB1!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programMITSOB1"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programMITSOB1;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programMITSOB1_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Course Second Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(2);" name='programMITSOB2' id='programMITSOB2'    tip="Please select the course of your choice."    validate="validateSelect"   required="true"   caption="course"   onmouseover="showTipOnline('Please select the course of your choice.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='PGDM' >PGDM</option><option value='PGDM-Marketing' >PGDM-Marketing</option><option value='PGDM-Finance' >PGDM-Finance</option><option value='PGDM-Human Resource' >PGDM-Human Resource</option>
				</select>
				<?php if(isset($programMITSOB2) && $programMITSOB2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programMITSOB2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programMITSOB2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programMITSOB2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Course Third Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(3);" name='programMITSOB3' id='programMITSOB3'    tip="Please select the course of your choice."    validate="validateSelect"   required="true"   caption="course"   onmouseover="showTipOnline('Please select the course of your choice.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='PGDM' >PGDM</option><option value='PGDM-Marketing' >PGDM-Marketing</option><option value='PGDM-Finance' >PGDM-Finance</option><option value='PGDM-Human Resource' >PGDM-Human Resource</option>
				</select>
				<?php if(isset($programMITSOB3) && $programMITSOB3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programMITSOB3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programMITSOB3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programMITSOB3_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Course Fourth Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(4);" name='programMITSOB4' id='programMITSOB4'    tip="Please select the course of your choice."    validate="validateSelect"   required="true"   caption="course"   onmouseover="showTipOnline('Please select the course of your choice.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='PGDM' >PGDM</option><option value='PGDM-Marketing' >PGDM-Marketing</option><option value='PGDM-Finance' >PGDM-Finance</option><option value='PGDM-Human Resource' >PGDM-Human Resource</option>
				</select>
				<?php if(isset($programMITSOB4) && $programMITSOB4!=""){ ?>
			      <script>
				  var selObj = document.getElementById("programMITSOB4"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $programMITSOB4;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programMITSOB4_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional personal info</h3>
				<div class='additionalInfoLeftCol'>
				<label>Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ageMITSOB' id='ageMITSOB'  validate="validateInteger"   required="true"   caption="age"   minlength="2"   maxlength="3"     tip="Enter your age in years"   value=''   />
				<?php if(isset($ageMITSOB) && $ageMITSOB!=""){ ?>
				  <script>
				      document.getElementById("ageMITSOB").value = "<?php echo str_replace("\n", '\n', $ageMITSOB );  ?>";
				      document.getElementById("ageMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Caste: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='casteMITSOB' id='casteMITSOB'  validate="validateStr"   required="true"   caption="caste"   minlength="1"   maxlength="50"     tip="Enter your caste. If it is not applicable in your case, just enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($casteMITSOB) && $casteMITSOB!=""){ ?>
				  <script>
				      document.getElementById("casteMITSOB").value = "<?php echo str_replace("\n", '\n', $casteMITSOB );  ?>";
				      document.getElementById("casteMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'casteMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Applicant category: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input type='radio' name='categoryMITSOB' id='categoryMITSOB0'   value='Open'  checked   onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" >Open</span>&nbsp;&nbsp;
				<input type='radio' name='categoryMITSOB' id='categoryMITSOB1'   value='SC'     onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryMITSOB' id='categoryMITSOB2'   value='ST'     onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				<input type='radio' name='categoryMITSOB' id='categoryMITSOB3'   value='NT'     onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" >NT</span>&nbsp;&nbsp;
				<input type='radio' name='categoryMITSOB' id='categoryMITSOB4'   value='OBC'     onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" >OBC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryMITSOB' id='categoryMITSOB5'   value='Others'     onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the category that is applicable to you.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($categoryMITSOB) && $categoryMITSOB!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["categoryMITSOB"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $categoryMITSOB;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Emergency contact number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='emergencyMITSOB' id='emergencyMITSOB'  validate="validateInteger"   required="true"   caption="number"   minlength="6"   maxlength="13"     tip="Please enter an emergency contact number, i.e. a contact number of a person to whom the Institute can contact in case of an emergency."   value=''   />
				<?php if(isset($emergencyMITSOB) && $emergencyMITSOB!=""){ ?>
				  <script>
				      document.getElementById("emergencyMITSOB").value = "<?php echo str_replace("\n", '\n', $emergencyMITSOB );  ?>";
				      document.getElementById("emergencyMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emergencyMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Family Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherEduMITSOB' id='fatherEduMITSOB'  validate="validateStr"   required="true"   caption="qualification"   minlength="1"   maxlength="50"     tip="Enter your father's highest qualification. For Example BA, MA, M.Com, MBBS etc. "   value=''   />
				<?php if(isset($fatherEduMITSOB) && $fatherEduMITSOB!=""){ ?>
				  <script>
				      document.getElementById("fatherEduMITSOB").value = "<?php echo str_replace("\n", '\n', $fatherEduMITSOB );  ?>";
				      document.getElementById("fatherEduMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherEduMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Father's annual income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherIncomeMITSOB' id='fatherIncomeMITSOB'  validate="validateInteger"   required="true"   caption="income"   minlength="2"   maxlength="10"     tip="Enter your father's annual income in Rupees per year. If your father doesn't earn, enter NA."   value=''   allowNA = 'true'/>
				<?php if(isset($fatherIncomeMITSOB) && $fatherIncomeMITSOB!=""){ ?>
				  <script>
				      document.getElementById("fatherIncomeMITSOB").value = "<?php echo str_replace("\n", '\n', $fatherIncomeMITSOB );  ?>";
				      document.getElementById("fatherIncomeMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherIncomeMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherEduMITSOB' id='motherEduMITSOB'  validate="validateStr"   required="true"   caption="qualification"   minlength="1"   maxlength="50"     tip="Enter your mother's highest qualification. For Example BA, MA, M.Com, MBBS etc. "   value=''   />
				<?php if(isset($motherEduMITSOB) && $motherEduMITSOB!=""){ ?>
				  <script>
				      document.getElementById("motherEduMITSOB").value = "<?php echo str_replace("\n", '\n', $motherEduMITSOB );  ?>";
				      document.getElementById("motherEduMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherEduMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's annual income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherIncomeMITSOB' id='motherIncomeMITSOB'  validate="validateInteger"   required="true"   caption="income"   minlength="2"   maxlength="10"     tip="Enter your mother's annual income in Rupees per year. If your mother doesn't earn, enter NA."   value=''  allowNA = 'true' />
				<?php if(isset($motherIncomeMITSOB) && $motherIncomeMITSOB!=""){ ?>
				  <script>
				      document.getElementById("motherIncomeMITSOB").value = "<?php echo str_replace("\n", '\n', $motherIncomeMITSOB );  ?>";
				      document.getElementById("motherIncomeMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherIncomeMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Brother/Sister Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='broEduMITSOB' id='broEduMITSOB'  validate="validateStr"   required="true"   caption="qualification"   minlength="1"   maxlength="50"     tip="Enter your Brother/Sister highest qualification. For Example BA, MA, M.Com, MBBS etc. "   value=''   />
				<?php if(isset($broEduMITSOB) && $broEduMITSOB!=""){ ?>
				  <script>
				      document.getElementById("broEduMITSOB").value = "<?php echo str_replace("\n", '\n', $broEduMITSOB );  ?>";
				      document.getElementById("broEduMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'broEduMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Brother/Sister annual income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='broIncomeMITSOB' id='broIncomeMITSOB'  validate="validateInteger"   required="true"   caption="income"   minlength="2"   maxlength="10"     tip="Enter your Brother/Sister annual income in Rupees per year. If your Brother/Sister doesn't earn, enter NA."   value=''  allowNA = 'true' />
				<?php if(isset($broIncomeMITSOB) && $broIncomeMITSOB!=""){ ?>
				  <script>
				      document.getElementById("broIncomeMITSOB").value = "<?php echo str_replace("\n", '\n', $broIncomeMITSOB );  ?>";
				      document.getElementById("broIncomeMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'broIncomeMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Brother/Sister Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='broOccupationMITSOB' id='broOccupationMITSOB'  validate="validateStr"   required="true"   caption="occupation"   minlength="1"   maxlength="50"     tip="Enter your Brother/Sister Occupation. If your Brother/Sister doesn't work, enter NA."   value=''   />
				<?php if(isset($broOccupationMITSOB) && $broOccupationMITSOB!=""){ ?>
				  <script>
				      document.getElementById("broOccupationMITSOB").value = "<?php echo str_replace("\n", '\n', $broOccupationMITSOB);  ?>";
				      document.getElementById("broOccupationMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'broOccupationMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Academic Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in class 10th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10MarksMITSOB' id='class10MarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in class 10th. Refer your class 10th marksheet for details."   value=''   />
				<?php if(isset($class10MarksMITSOB) && $class10MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("class10MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $class10MarksMITSOB );  ?>";
				      document.getElementById("class10MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10TotalMarksMITSOB' id='class10TotalMarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your class 10th marksheet for details."   value=''   />
				<?php if(isset($class10TotalMarksMITSOB) && $class10TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("class10TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $class10TotalMarksMITSOB );  ?>";
				      document.getElementById("class10TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10TotalMarksMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in class 12th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12MarksMITSOB' id='class12MarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in class 12th. Refer your class 12th marksheet for details."   value=''   />
				<?php if(isset($class12MarksMITSOB) && $class12MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("class12MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $class12MarksMITSOB );  ?>";
				      document.getElementById("class12MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12TotalMarksMITSOB' id='class12TotalMarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your class 12th marksheet for details."   value=''   />
				<?php if(isset($class12TotalMarksMITSOB) && $class12TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("class12TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $class12TotalMarksMITSOB );  ?>";
				      document.getElementById("class12TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12TotalMarksMITSOB_error'></div></div>
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
				<label><?php echo $graduationCourseName;?> duration: </label>
				<div class='fieldBoxLarge'>
				<select onchange="checkExams();" name='gradDurationMITSOB' id='gradDurationMITSOB'    tip="Select the duration for your graduation course."     onmouseover="showTipOnline('Select the duration for your graduation course.',this);" onmouseout="hidetip();" >
				    <option value='03' selected>03</option><option value='04' >04</option><option value='05' >05</option>
				</select>
				<?php if(isset($gradDurationMITSOB) && $gradDurationMITSOB!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradDurationMITSOB"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradDurationMITSOB;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradDurationMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in <?php echo $graduationCourseName;?> 1st year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear1MarksMITSOB' id='gradYear1MarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in <?php echo $graduationCourseName;?>. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear1MarksMITSOB) && $gradYear1MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear1MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear1MarksMITSOB );  ?>";
				      document.getElementById("gradYear1MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear1TotalMarksMITSOB' id='gradYear1TotalMarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear1TotalMarksMITSOB) && $gradYear1TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear1TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear1TotalMarksMITSOB );  ?>";
				      document.getElementById("gradYear1TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1TotalMarksMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in <?php echo $graduationCourseName;?> 2nd year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear2MarksMITSOB' id='gradYear2MarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in <?php echo $graduationCourseName;?>. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear2MarksMITSOB) && $gradYear2MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear2MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear2MarksMITSOB );  ?>";
				      document.getElementById("gradYear2MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear2TotalMarksMITSOB' id='gradYear2TotalMarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear2TotalMarksMITSOB) && $gradYear2TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear2TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear2TotalMarksMITSOB );  ?>";
				      document.getElementById("gradYear2TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2TotalMarksMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in <?php echo $graduationCourseName;?> 3rd year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear3MarksMITSOB' id='gradYear3MarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in <?php echo $graduationCourseName;?>. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear3MarksMITSOB) && $gradYear3MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear3MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear3MarksMITSOB );  ?>";
				      document.getElementById("gradYear3MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear3TotalMarksMITSOB' id='gradYear3TotalMarksMITSOB'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear3TotalMarksMITSOB) && $gradYear3TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear3TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear3TotalMarksMITSOB );  ?>";
				      document.getElementById("gradYear3TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3TotalMarksMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li id="grad4Details" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in <?php echo $graduationCourseName;?> 4th year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear4MarksMITSOB' id='gradYear4MarksMITSOB'  validate="validateFloat"    caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in <?php echo $graduationCourseName;?>. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear4MarksMITSOB) && $gradYear4MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear4MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear4MarksMITSOB );  ?>";
				      document.getElementById("gradYear4MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear4TotalMarksMITSOB' id='gradYear4TotalMarksMITSOB'  validate="validateFloat"    caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear4TotalMarksMITSOB) && $gradYear4TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear4TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear4TotalMarksMITSOB );  ?>";
				      document.getElementById("gradYear4TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4TotalMarksMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li id="grad5Details" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in <?php echo $graduationCourseName;?> 5th year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear5MarksMITSOB' id='gradYear5MarksMITSOB'  validate="validateFloat"    caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in <?php echo $graduationCourseName;?>. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear5MarksMITSOB) && $gradYear5MarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear5MarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear5MarksMITSOB );  ?>";
				      document.getElementById("gradYear5MarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear5MarksMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear5TotalMarksMITSOB' id='gradYear5TotalMarksMITSOB'  validate="validateFloat"    caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your <?php echo $graduationCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($gradYear5TotalMarksMITSOB) && $gradYear5TotalMarksMITSOB!=""){ ?>
				  <script>
				      document.getElementById("gradYear5TotalMarksMITSOB").value = "<?php echo str_replace("\n", '\n', $gradYear5TotalMarksMITSOB );  ?>";
				      document.getElementById("gradYear5TotalMarksMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear5TotalMarksMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<script>checkExams(); </script>

			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$marks = 'otherCourseMarks_mul_'.$otherCourseId;
					$marksVal = $$marks;
					$tMarks = 'otherCoursetMarks_mul_'.$otherCourseId;
					$tMarksVal = $$tMarks;
					$isPGVarName = 'isPG_mul_'.$otherCourseId;
					$isPGValue = $$isPGVarName;
					$i++;

			?>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total marks obtained in <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $marks; ?>' id='<?php echo $marks; ?>'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total marks obtained in <?php echo $otherCourseName;?>. Refer your <?php echo $otherCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($marksVal) && $marksVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $marks; ?>").value = "<?php echo str_replace("\n", '\n', $marksVal );  ?>";
				      document.getElementById("<?php echo $marks; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $marks; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Out of: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $tMarks; ?>' id='<?php echo $tMarks; ?>'  validate="validateFloat"   required="true"   caption="marks"   minlength="1"   maxlength="8"     tip="Enter the total maximum marks for all subjects combined. Refer your <?php echo $otherCourseName;?> marksheet for details."   value=''   />
				<?php if(isset($tMarksVal) && $tMarksVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $tMarks; ?>").value = "<?php echo str_replace("\n", '\n', $tMarksVal );  ?>";
				      document.getElementById("<?php echo $tMarks; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $tMarks; ?>_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Is <?php echo $otherCourseName; ?> a Post Graduate (PG) course?: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='<?php echo $isPGVarName; ?>' id='<?php echo $isPGVarName; ?>' value='1' <?php if($isPGValue) echo "checked='checked'"; ?>  /> 
				<div style='display:none'><div class='errorMsg' id= '<?php echo $isPGVarName; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<?php
				}
			}
			?>

			<li>
				<h3 class="upperCase">How did you come to know about the course?</h3>
				<div class='additionalInfoLeftCol'>
				<label>Through press advertisement, newspaper: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='pressAdMITSOB' id='pressAdMITSOB'  validate="validateStr"    caption="name"   minlength="1"   maxlength="50"     tip="Enter the name of publication or news paper"   value=''   />
				<?php if(isset($pressAdMITSOB) && $pressAdMITSOB!=""){ ?>
				  <script>
				      document.getElementById("pressAdMITSOB").value = "<?php echo str_replace("\n", '\n', $pressAdMITSOB );  ?>";
				      document.getElementById("pressAdMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pressAdMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Through your visit to the institute: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='instituteVisitMITSOB[]' id='instituteVisitMITSOB0'   value='1'  onmouseover="showTipOnline('Select this option if you came to know about this institute during campus visit.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select this option if you came to know about this institute during campus visit.',this);" onmouseout="hidetip();" ><span>&nbsp;&nbsp;
				<?php if(isset($instituteVisitMITSOB) && $instituteVisitMITSOB!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["instituteVisitMITSOB[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$instituteVisitMITSOB);
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
				
				<div style='display:none'><div class='errorMsg' id= 'instituteVisitMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Word of mouth: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mouthMITSOB' id='mouthMITSOB'  validate="validateStr"    caption="details"   minlength="1"   maxlength="50"     tip="Please specify where did you hear about this Institute."   value=''   />
				<?php if(isset($mouthMITSOB) && $mouthMITSOB!=""){ ?>
				  <script>
				      document.getElementById("mouthMITSOB").value = "<?php echo str_replace("\n", '\n', $mouthMITSOB );  ?>";
				      document.getElementById("mouthMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mouthMITSOB_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Website: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='websiteMITSOB' id='websiteMITSOB'  validate="validateStr"    caption="website"   minlength="1"   maxlength="50"     tip="Please specify where did you hear about this Institute."   value=''   />
				<?php if(isset($websiteMITSOB) && $websiteMITSOB!=""){ ?>
				  <script>
				      document.getElementById("websiteMITSOB").value = "<?php echo str_replace("\n", '\n', $websiteMITSOB );  ?>";
				      document.getElementById("websiteMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'websiteMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Others: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='otherMITSOB' id='otherMITSOB'  validate="validateStr"    caption="other details"   minlength="1"   maxlength="50"     tip="Please specify where did you hear about this Institute."   value=''   />
				<?php if(isset($otherMITSOB) && $otherMITSOB!=""){ ?>
				  <script>
				      document.getElementById("otherMITSOB").value = "<?php echo str_replace("\n", '\n', $otherMITSOB );  ?>";
				      document.getElementById("otherMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Essay</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Why did you choose to study at MITSOB. What are your career plans?: </label>
				<div class='fieldBoxLarge' style="width:610px">
				<textarea name='chooseMITSOB' id='chooseMITSOB'  validate="validateStr" style="width:600px; height:74px; padding:5px"  required="true"   caption="essay"   minlength="1"   maxlength="300"     tip="Please write a short essay on why you chose MITSOB and what are your career goals."    ></textarea>
				<?php if(isset($chooseMITSOB) && $chooseMITSOB!=""){ ?>
				  <script>
				      document.getElementById("chooseMITSOB").value = "<?php echo str_replace("\n", '\n', $chooseMITSOB );  ?>";
				      document.getElementById("chooseMITSOB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'chooseMITSOB_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='float_L'>
			<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option selected value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
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
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				  I have read and understood details of PGDM program mentioned in institutes brochure. I hereby declare that all statements made in this application are true, complete and correct. In the event of any information being found false or incorrect ineligiblity being detected before or after selection, action may be taken by the Institute as deemed fit against me.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsMITSOB' id='agreeToTermsMITSOB' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsMITSOB) && $agreeToTermsMITSOB!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsMITSOB"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsMITSOB);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsMITSOB_error'></div></div>


				</div>
				</div>
			</li>


			<!--<li>
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"   required="true"   caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
				      document.getElementById("xatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat"   required="true"   caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
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

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   required="true"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''   />
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

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   required="true"        tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''   />
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

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   required="true"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''   />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   required="true"        tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''   />
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

			<li>
				<div class='additionalInfoLeftCol'>
				
				<div style='display:none'><div class='errorMsg' id= '_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				
				<div style='display:none'><div class='errorMsg' id= '_error'></div></div>
				</div>
				</div>
			</li>-->
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

  function checkValidCampusPreference(id){
	if(id==1){ sId = 2; tId=3; fId=4; }
	else if(id==2){ sId = 1; tId = 3; fId=4; }
	else if(id==3){sId = 1; tId = 2;  fId=4; }
	else {sId = 1; tId = 2;  fId=3; }
	var selectedPrefObj = document.getElementById('programMITSOB'+id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById('programMITSOB'+sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	var selObj2 = document.getElementById('programMITSOB'+tId); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].value;
	var selObj3 = document.getElementById('programMITSOB'+fId); 
	var selPref3 = selObj3.options[selObj3.selectedIndex].value;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) || (selectedPref == selPref3 && selectedPref!='' ) ){
		$('programMITSOB'+id+'_error').innerHTML = 'Same preference can’t be set.';
		$('programMITSOB'+id+'_error').parentNode.style.display = '';
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
		$('programMITSOB'+id+'_error').innerHTML = '';
		$('programMITSOB'+id+'_error').parentNode.style.display = 'none';
	}
	return true;
  }

  </script>
