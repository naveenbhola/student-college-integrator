<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional",key+"RankAdditional",key+"PercentileAdditional");
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
  
  function toggleSourceTextBox(objId,txt){
	if($(objId).value=='Yes'){
		$(txt+'reason_li').style.display = '';
                $(txt+'reason').setAttribute('required','true');
      }
      else{
          $(txt+'reason').value = '';
          $(txt+'reason_li').style.display = 'none';
          $(txt+'reason').removeAttribute('required');
          $(txt+'reason_error').innerHTML = '';
          $(txt+'reason_error').parentNode.style.display = 'none';
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
			<?php if($action != 'updateScore'):?>
			
                           <li>
				<h3 class="upperCase">Personal Information</h3>
			    </li>
				
			    <li>
				<div class='additionalInfoLeftCol'>
				<label>Are you a Catholic?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='SCBJA_catholic' id='SCBJA_catholic0'   value='Yes' title="Are you a Catholic?"   onmouseover="showTipOnline('Please mention if you are a Catholic. In case you are, select YES otherwise select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you are a Catholic. In case you are, select YES otherwise select NO.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='SCBJA_catholic' id='SCBJA_catholic1'   value='No'    title="Are you a Catholic?"   onmouseover="showTipOnline('Please mention if you are a Catholic. In case you are, select YES otherwise select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you are a Catholic. In case you are, select YES otherwise select NO.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($SCBJA_catholic) && $SCBJA_catholic!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SCBJA_catholic"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SCBJA_catholic;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_catholic_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				  <label>Reserved Category (Optional): </label>
				  <div class='fieldBoxLarge'>
				  <input type='radio'  caption="category"  name='SCBJA_category' id='SCBJA_category0'   value='SC' title="Category"   onmouseover="showTipOnline('If you fall into reserved category, please select the appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you fall into reserved category, please select the appropriate category.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				  <input type='radio' caption="category"  name='SCBJA_category' id='SCBJA_category1'   value='ST'    title="Category"   onmouseover="showTipOnline('If you fall into reserved category, please select the appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you fall into reserved category, please select the appropriate category.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				  <br/><input type='radio' caption="category"  name='SCBJA_category' id='SCBJA_category2'   value='OBC'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" >OBC</span>&nbsp;&nbsp;
				  <?php if(isset($SCBJA_category) && $SCBJA_category!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["SCBJA_category"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $SCBJA_category;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
				  
				  <div style='display:none'><div class='errorMsg' id= 'SCBJA_category_error'></div></div>
				  </div>
				</div>
			</li>
			
                            <li>
				<h3 class="upperCase">Additional Family Information</h3>
			    </li>
			    <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Father's Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_fOrganization' id='SCBJA_fOrganization'  validate="validateStr"   required="true"   caption="Father's Organization"   minlength="1"   maxlength="100"     tip="Enter the name of your father's organization. If it doesn't apply to you, just enter NA."   title="Father's Organization"  value=''   />
				<?php if(isset($SCBJA_fOrganization) && $SCBJA_fOrganization!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_fOrganization").value = "<?php echo str_replace("\n", '\n', $SCBJA_fOrganization );  ?>";
				      document.getElementById("SCBJA_fOrganization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_fOrganization_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Father's annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_fannualIncome' id='SCBJA_fannualIncome'  validate="validateStr"   required="true"   caption="Father's Annual Income"   minlength="1"   maxlength="100"     tip="Enter your father's annual income. If it doesn't apply to you, just enter NA.."   title="Father's Annual Income"  value=''   />
				<?php if(isset($SCBJA_fannualIncome) && $SCBJA_fannualIncome!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_fannualIncome").value = "<?php echo str_replace("\n", '\n', $SCBJA_fannualIncome );  ?>";
				      document.getElementById("SCBJA_fannualIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_fannualIncome_error'></div></div>
				</div>
				</div>
			</li>
			
			    <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Father's Cell Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_fCellnumber' id='SCBJA_fCellnumber'  validate="validateMobileInteger"   required="true"   caption="Father's Cell Number"   minlength="10"   maxlength="10"     tip="Enter your father's mobile number. If it doesn't apply to you, just enter NA."   title="Father's Cell Number"  value='' allowNA="true"  />
				<?php if(isset($SCBJA_fCellnumber) && $SCBJA_fCellnumber!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_fCellnumber").value = "<?php echo str_replace("\n", '\n', $SCBJA_fCellnumber );  ?>";
				      document.getElementById("SCBJA_fCellnumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_fCellnumber_error'></div></div>
				</div>
				</div>
				
			    </li>
			    <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Mother's Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_mOrganization' id='SCBJA_mOrganization'  validate="validateStr"   required="true"   caption="Mother's Organization"   minlength="1"   maxlength="100"     tip="Enter the name of your mother's organization. If it doesn't apply to you, just enter NA."   title="Mother's Organization"  value=''   />
				<?php if(isset($SCBJA_mOrganization) && $SCBJA_mOrganization!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_mOrganization").value = "<?php echo str_replace("\n", '\n', $SCBJA_mOrganization );  ?>";
				      document.getElementById("SCBJA_mOrganization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_mOrganization_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Mother's annual Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_mannualIncome' id='SCBJA_mannualIncome'  validate="validateStr"   required="true"   caption="Mother's Annual Income"   minlength="1"   maxlength="100"     tip="Enter your mother's annual income. If it doesn't apply to you, just enter NA."   title="Mother's Annual Income"  value=''   />
				<?php if(isset($SCBJA_mannualIncome) && $SCBJA_mannualIncome!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_mannualIncome").value = "<?php echo str_replace("\n", '\n', $SCBJA_mannualIncome );  ?>";
				      document.getElementById("SCBJA_mannualIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_mannualIncome_error'></div></div>
				</div>
				</div>
			</li>
			
			    <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Mother's Cell Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_mCellnumber' id='SCBJA_mCellnumber'  validate="validateMobileInteger"   required="true"   caption="Mother's Cell Number"   minlength="10"   maxlength="10"     tip="Enter your mother's mobile number. If it doesn't apply to you, just enter NA."   title="Mother's Cell Number"  value='' allowNA="true"  />
				<?php if(isset($SCBJA_mCellnumber) && $SCBJA_mCellnumber!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_mCellnumber").value = "<?php echo str_replace("\n", '\n', $SCBJA_mCellnumber );  ?>";
				      document.getElementById("SCBJA_mCellnumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_mCellnumber_error'></div></div>
				</div>
				</div>
				
			    </li>
			    
				    
			    <li>
				<h3 class="upperCase">Additional Education Information</h3>
			    </li>
			    <li>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th State </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_class10' id='SCBJA_class10'  validate="validateStr"   required="true"   caption="Class 10th State"   minlength="1"   maxlength="100"     tip="Enter the name of the state from where you completed your class 10th."   title="Class 10th State"  value=''   />
				<?php if(isset($SCBJA_class10) && $SCBJA_class10!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_class10").value = "<?php echo str_replace("\n", '\n', $SCBJA_class10 );  ?>";
				      document.getElementById("SCBJA_class10").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_class10_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				  <label>Mode of study 10th: </label>
				  <div class='fieldBoxLarge'>
				  <input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Mode of study 10th"  name='SCBJA_mode10' id='SCBJA_mode100'   value='CBSE' title="Category"   onmouseover="showTipOnline('Select the board through which you completed your class 10th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the board through which you completed your class 10th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" >CBSE</span>&nbsp;&nbsp;
				  <input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Mode of study 10th"  name='SCBJA_mode10' id='SCBJA_mode101'   value='ICSE'    title="Category"   onmouseover="showTipOnline('Select the board through which you completed your class 10th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the board through which you completed your class 10th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" >ICSE</span>&nbsp;&nbsp;
				  <br/><input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Mode of study 10th"  name='SCBJA_mode10' id='SCBJA_mode102'   value='Other States'    title="Category"   onmouseover="showTipOnline('Select the board through which you completed your class 10th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the board through which you completed your class 10th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" >State Board</span>&nbsp;&nbsp;
				
				  <?php if(isset($SCBJA_mode10) && $SCBJA_mode10!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["SCBJA_mode10"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $SCBJA_mode10;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
				  
				  <div style='display:none'><div class='errorMsg' id= 'SCBJA_mode10_error'></div></div>
				  </div>
				</div>
			</li>
			    
			  <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Class 12th State </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_class12' id='SCBJA_class12'  validate="validateStr"   required="true"   caption="Class 12th State"   minlength="1"   maxlength="100"     tip="Enter the name of the state from where you completed your class 12th."   title="Class 12th State"  value=''   />
				<?php if(isset($SCBJA_class12) && $SCBJA_class12!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_class12").value = "<?php echo str_replace("\n", '\n', $SCBJA_class12 );  ?>";
				      document.getElementById("SCBJA_class12").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_class12_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				  <label>Mode of study 12th: </label>
				  <div class='fieldBoxLarge'>
				  <input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Mode of study 12th"  name='SCBJA_mode12' id='SCBJA_mode120'   value='CBSE' title="Category"   onmouseover="showTipOnline('Select the board through which you completed your class 12th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the board through which you completed your class 12th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" >CBSE</span>&nbsp;&nbsp;
				  <input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Mode of study 12th"  name='SCBJA_mode12' id='SCBJA_mode121'   value='ICSE'    title="Category"   onmouseover="showTipOnline('Select the board through which you completed your class 12th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the board through which you completed your class 12th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" >ICSE</span>&nbsp;&nbsp;
				  <br/><input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Mode of study 12th"  name='SCBJA_mode12' id='SCBJA_mode122'   value='Other States'    title="Category"   onmouseover="showTipOnline('Select the board through which you completed your class 12th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the board through which you completed your class 12th. If your board is not listed, select State Board.',this);" onmouseout="hidetip();" >State Board</span>&nbsp;&nbsp;
				
				  <?php if(isset($SCBJA_mode12) && $SCBJA_mode12!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["SCBJA_mode12"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $SCBJA_mode12;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
				  
				  <div style='display:none'><div class='errorMsg' id= 'SCBJA_mode12_error'></div></div>
				  </div>
				</div>
			</li>
                        
      <?php
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
	}?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label><?=$graduationCourseName;?> State </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_bachelorDegreeState' id='SCBJA_bachelorDegreeState'  validate="validateStr"   required="true"   caption="Bachelor Degree State"   minlength="1"   maxlength="100"     tip="Enter the name of the state from where you completed your Bachelor Degree."   title="Bachelor Degree State"  value=''   />
				<?php if(isset($SCBJA_bachelorDegreeState) && $SCBJA_bachelorDegreeState!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_bachelorDegreeState").value = "<?php echo str_replace("\n", '\n', $SCBJA_bachelorDegreeState );  ?>";
				      document.getElementById("SCBJA_bachelorDegreeState").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_bachelorDegreeState_error'></div></div>
				</div>
				</div>
				
			<div class='additionalInfoRightCol'>
				  <label>Mode of study <?=$graduationCourseName;?>: </label>
				  <div class='fieldBoxLarge'>
				  <input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Full Time"  name='SCBJA_BachelorDegreemodeofStudy' id='SCBJA_BachelorDegreemodeofStudy0'   value='Full Time' title="Mode of study"   onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($graduationCourseName);?> .',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($graduationCourseName);?>.',this);" onmouseout="hidetip();" >Full Time</span>&nbsp;&nbsp;
				  <input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Part Time"  name='SCBJA_BachelorDegreemodeofStudy' id='SCBJA_BachelorDegreemodeofStudy1'   value='Part Time'    title="Mode of study"   onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($graduationCourseName);?>.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($graduationCourseName);?>.',this);" onmouseout="hidetip();" >Part Time</span>&nbsp;&nbsp;
				  <br/><input type='radio'  validate="validateCheckedGroup"   required="true"   caption="Correspondence"  name='SCBJA_BachelorDegreemodeofStudy' id='SCBJA_BachelorDegreemodeofStudy2'   value='Correspondence'    title="Mode of study"   onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($graduationCourseName); ?>.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($graduationCourseName);?>.',this);" onmouseout="hidetip();" > Correspondence</span>&nbsp;&nbsp;
				
				  <?php if(isset($SCBJA_BachelorDegreemodeofStudy) && $SCBJA_BachelorDegreemodeofStudy!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["SCBJA_BachelorDegreemodeofStudy"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $SCBJA_BachelorDegreemodeofStudy;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
				  
				  <div style='display:none'><div class='errorMsg' id= 'SCBJA_BachelorDegreemodeofStudy_error'></div></div>
				  </div>
				</div>
                       </li>
			 
				 
			   <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Result 1st Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem1BachelorDegree' id='SCBJA_Sem1BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 1 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the first semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 1 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem1BachelorDegree) && $SCBJA_Sem1BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem1BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem1BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem1BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem1BachelorDegree_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Result 2nd Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem2BachelorDegree' id='SCBJA_Sem2BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 2 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the second semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 2 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem2BachelorDegree) && $SCBJA_Sem2BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem2BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem2BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem2BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem2BachelorDegree_error'></div></div>
				</div>
				</div>
			</li>
			   <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Result 3st Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem3BachelorDegree' id='SCBJA_Sem3BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 3 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the third semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 3 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem3BachelorDegree) && $SCBJA_Sem3BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem3BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem3BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem3BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem3BachelorDegree_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Result 4nd Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem4BachelorDegree' id='SCBJA_Sem4BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 4 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the fourth semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 4 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem4BachelorDegree) && $SCBJA_Sem4BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem4BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem4BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem4BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem4BachelorDegree_error'></div></div>
				</div>
				</div>
			</li>
			   <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Result 5st Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem5BachelorDegree' id='SCBJA_Sem5BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 5 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the fifth semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 5 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem5BachelorDegree) && $SCBJA_Sem5BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem5BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem5BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem5BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem5BachelorDegree_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Result 6nd Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem6BachelorDegree' id='SCBJA_Sem6BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 6 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the sixth semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 6 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem6BachelorDegree) && $SCBJA_Sem6BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem6BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem6BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem6BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem6BachelorDegree_error'></div></div>
				</div>
				</div>
			</li>
			   <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Result 7st Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem7BachelorDegree' id='SCBJA_Sem7BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 7 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the seventh semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 7 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem7BachelorDegree) && $SCBJA_Sem7BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem7BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem7BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem7BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem7BachelorDegree_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Result 8nd Semester - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem8BachelorDegree' id='SCBJA_Sem8BachelorDegree'  validate="validateStr"   required="true"   caption="Semester 8 <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the result (percentage or grades) for the eighth semester for your <?=$graduationCourseName;?>. If your course didn't have this semester, just enter NA."   title="Semester 8 <?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem8BachelorDegree) && $SCBJA_Sem8BachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem8BachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem8BachelorDegree );  ?>";
				      document.getElementById("SCBJA_Sem8BachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem8BachelorDegree_error'></div></div>
				</div>
				</div>
			</li>
                         
                          <li>
			    
				<div class='additionalInfoLeftCol'>
				<label>Result Aggregate <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_SemAggrBachelorDegree' id='SCBJA_SemAggrBachelorDegree'  validate="validateStr"   required="true"   caption="Result Aggregate - <?=$graduationCourseName;?>"   minlength="1"   maxlength="5"     tip="Enter the Aggregate result (percentage or grades) for your <?=$graduationCourseName;?>."   title="Result Aggregate <?=$graduationCourseName;?> "  value=''   />
				<?php if(isset($SCBJA_SemAggrBachelorDegree) && $SCBJA_SemAggrBachelorDegree!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_SemAggrBachelorDegree").value = "<?php echo str_replace("\n", '\n', $SCBJA_SemAggrBachelorDegree );  ?>";
				      document.getElementById("SCBJA_SemAggrBachelorDegree").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_SemAggrBachelorDegree_error'></div></div>
				</div>
				</div>
			   </li>
			
			   <?php
			    $i=0;
			    if(count($otherCourses)>0) { 
				    foreach($otherCourses as $otherCourseId => $otherCourseName) {
					    $pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					    $pgCheckVal = $$pgCheck;
					    $yoa = 'otherCourseYoa_mul_'.$otherCourseId;
					    $yoaVal = $$yoa;
					    $moi = 'otherCourseMoi_mul_'.$otherCourseId;
					    $moiVal = $$moi;
					    $i++;
    
			    ?>

			    <li>
				
				<div class='additionalInfoLeftCol'>
				<label>Is <?php echo $otherCourseName;?> PG? </label>
				<div class='fieldBoxLarge'>
				  <input type='radio' name='<?php echo $pgCheck; ?>' id='<?php echo $pgCheck.'0'; ?>'   value='Yes'   onmouseover="showTipOnline('Was <?=html_escape($otherCourseName);?> a post graduate course?',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('Was <?=html_escape($otherCourseName);?> a post graduate course?',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='<?php echo $pgCheck; ?>' id='<?php echo $pgCheck.'1'; ?>'   value='No'     onmouseover="showTipOnline('Was <?=html_escape($otherCourseName);?> a post graduate course?',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('Was <?=html_escape($otherCourseName);?> a post graduate course?',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($pgCheckVal) && $pgCheckVal!=""){  ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["<?php echo $pgCheck; ?>"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					   
					      if(radioObj[i].value == "<?php echo $pgCheckVal; ?>") {
						      radioObj[i].checked = true;
					      }
				      }
				      
				      
				  </script>
				<?php } ?>
			
				<div style='display:none'><div class='errorMsg' id= '<?php echo $pgCheck; ?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label> <?php echo $otherCourseName;?> State </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $moi;?>' id='<?php echo $moi;?>'    validate="validateStr"   required="true"   caption="<?php echo $otherCourseName;?>: State"   minlength="1"   maxlength="50"          tip="Enter the name of the state from where you completed your <?=html_escape($otherCourseName);?>"   value=''   allowNA='yes'/>
				<?php if(isset($moiVal) && $moiVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $moi;?>").value = "<?php echo str_replace("\n", '\n', $moiVal );  ?>";
				      document.getElementById("<?php echo $moi;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $moi;?>_error'></div></div>
				</div>
			    </li>
			  <li>
			  
			  <div class='additionalInfoLeftCol'>
				<label>Mode of study <?php echo $otherCourseName;?> </label>
				<div class='fieldBoxLarge'>
				      <input type='radio'  validate="validateCheckedGroup"   required="true" value="Full Time" caption="Full Time"  name='<?php echo $yoa;?>' id="<?php echo $yoa.'0';?>"   value='Full Time' title="Mode of study"   onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($otherCourseName);?> .',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select your mode of study for your <?php echo $otherCourseName;?>',this);" onmouseout="hidetip();" >Full Time</span>&nbsp;&nbsp;
				     <input type='radio'  validate="validateCheckedGroup"   required="true"   value="Part Time" caption="Part Time"  name='<?php echo $yoa;?>' id="<?php echo $yoa.'1';?>"   value='Part Time'    title="Mode of study"   onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($otherCourseName);?>.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($otherCourseName);?>.',this);" onmouseout="hidetip();" >Part Time</span>&nbsp;&nbsp;
				     <br/><input type='radio'  validate="validateCheckedGroup"   required="true"  value="Correspondence" caption="Correspondence"  name='<?php echo $yoa;?>' id="<?php echo $yoa.'2';?>"    value='Correspondence'    title="Mode of study"   onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($otherCourseName); ?>.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select your mode of study for your <?=html_escape($otherCourseName);?>.',this);" onmouseout="hidetip();" > Correspondence</span>&nbsp;&nbsp;
			     
				<?php if(isset($yoaVal) && $yoaVal!=""){ ?>
				  <script>
				    
				       radioObj = document.forms["OnlineForm"].elements["<?php echo $yoa; ?>"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      console.log(radioObj[i].value);
					      if(radioObj[i].value == "<?php echo $yoaVal;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $yoa;?>_error'></div></div>
				</div>
			  </li>	
				<?php
				}
			
			  }
			?>
		      <li id="Sem_1">
			  <div class='additionalInfoLeftCol' style= "width:470px;">
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge' style= "width:165px;" >
				<input type='text' name='SCBJA_Sem1BachelorDegreeR' id='SCBJA_Sem1BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem1BachelorDegreeR) && $SCBJA_Sem1BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem1BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem1BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem1BachelorDegreeR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem1BachelorDegreeR_error'></div></div>
				</div>
			  </div>
			  
			    <div class='additionalInfoRightCol' id="add_more">
				<label style="text-align: left;"><a href="javascript::void(0);"  onclick="openBox(); return false;">[+] Add More</a></label>	
			    </div>
			</li>
			<li style="display: none;" id="Sem_2">
			      <div class='additionalInfoLeftCol'  >
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem2BachelorDegreeR' id='SCBJA_Sem2BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem2BachelorDegreeR) && $SCBJA_Sem2BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem2BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem2BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem2BachelorDegreeR").style.color = "";
				       document.getElementById("Sem_2").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem2BachelorDegreeR_error'></div></div>
				</div>
			      </div>
			</li>
			<li style="display: none;" id="Sem_3">
			    <div class='additionalInfoLeftCol' >
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem3BachelorDegreeR' id='SCBJA_Sem3BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem3BachelorDegreeR) && $SCBJA_Sem3BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem3BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem3BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem3BachelorDegreeR").style.color = "";
				       document.getElementById("Sem_3").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem3BachelorDegreeR_error'></div></div>
				</div>
				</div>
			</li>
			<li style="display: none;" id="Sem_4">
			      <div class='additionalInfoLeftCol' >
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem4BachelorDegreeR' id='SCBJA_Sem4BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem4BachelorDegreeR) && $SCBJA_Sem4BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem4BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem4BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem4BachelorDegreeR").style.color = "";
				       document.getElementById("Sem_4").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem4BachelorDegreeR_error'></div></div>
				</div>
				</div>
			</li>
			
			
		      	<li style="display: none;" id="Sem_5">
			  <div class='additionalInfoLeftCol'>
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem5BachelorDegreeR' id='SCBJA_Sem5BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem5BachelorDegreeR) && $SCBJA_Sem5BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem5BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem5BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem5BachelorDegreeR").style.color = "";
				      document.getElementById("Sem_5").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem5BachelorDegreeR_error'></div></div>
				</div>
				</div>
			</li>
			<li style="display: none;" id="Sem_6">
			      <div class='additionalInfoLeftCol' >
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem6BachelorDegreeR' id='SCBJA_Sem6BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem6BachelorDegreeR) && $SCBJA_Sem6BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem6BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem6BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem6BachelorDegreeR").style.color = "";
				       document.getElementById("Sem_6").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem6BachelorDegreeR_error'></div></div>
				</div>
				</div>
			</li>
			
			<li  style="display: none;" id="Sem_7">
			  <div class='additionalInfoLeftCol'>
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem7BachelorDegreeR' id='SCBJA_Sem7BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem7BachelorDegreeR) && $SCBJA_Sem7BachelorDegreeR!=""){ ?>
<script>
				      document.getElementById("SCBJA_Sem7BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem7BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem7BachelorDegreeR").style.color = "";
				       document.getElementById("Sem_7").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem7BachelorDegreeR_error'></div></div>
				</div>
			      </div>
			</li>
			<li  style="display: none;" id="Sem_8">
			      <div class='additionalInfoLeftCol'>
				<label>Subject Repeated in - <?=$graduationCourseName;?> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SCBJA_Sem8BachelorDegreeR' id='SCBJA_Sem8BachelorDegreeR'  validate="validateStr"    caption="<?=$graduationCourseName;?>"   minlength="1"   maxlength="100"     tip="If you have repeated any subjects in your graduation, please indicate here."    title="<?=$graduationCourseName;?>"  value=''   />
				<?php if(isset($SCBJA_Sem8BachelorDegreeR) && $SCBJA_Sem8BachelorDegreeR!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Sem8BachelorDegreeR").value = "<?php echo str_replace("\n", '\n', $SCBJA_Sem8BachelorDegreeR );  ?>";
				      document.getElementById("SCBJA_Sem8BachelorDegreeR").style.color = "";
				       document.getElementById("Sem_8").style.display = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Sem8BachelorDegreeR_error'></div></div>
				</div>
				</div>
			</li>
			
		
			
			
			<li>
			  <div class='additionalInfoLeftCol'>
				<label>Is there any break in your studies since SSLC ?</label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='SCBJA_break' id='SCBJA_break0'   value='Yes' title="Is there any break in your studies since SSLC ?"  onClick="toggleSourceTextBox(this,'SCBJA_break');"  onmouseover="showTipOnline('Please indicate if there has been any break in your studies since SSLC.',this);" onmouseout="hidetip();" ></input>
				<span  onmouseover="showTipOnline('Please indicate if there has been any break in your studies since SSLC',this);" onmouseout="hidetip(); "  >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='SCBJA_break' id='SCBJA_break1' onClick="toggleSourceTextBox(this,'SCBJA_break');"   value='No'  title="Is there any break in your studies since SSLC ?"   onmouseover="showTipOnline('Please indicate if there has been any break in your studies since SSLC.',this);" onmouseout="hidetip();" ></input>
				<span  onmouseover="showTipOnline('Please indicate if there has been any break in your studies since SSLC',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
			
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_break_error'></div></div>
				</div>
				</div>
			</li>
			
			<li  id='SCBJA_breakreason_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
				<label>Reason for break </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SCBJA_breakreason' id='SCBJA_breakreason'  style="width:318px; height:74px; padding:5px;"    tip="Please state in detail the reason for your break in studies."  validate="validateStr" caption="reason for break" minlength="1" maxlength="100" ></textarea>
				<?php if(isset($SCBJA_breakreason) && $SCBJA_breakreason!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_breakreason").value = "<?php echo str_replace("\n", '\n', $SCBJA_breakreason );  ?>";
				      document.getElementById("SCBJA_breakreason").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_breakreason_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($SCBJA_break) && $SCBJA_break!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SCBJA_break"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SCBJA_break;?>") {
						      radioObj[i].checked = true;
						      if(radioObj[i].value=='Yes'){
							document.getElementById('SCBJA_breakreason_li').style.display='';
						      }
						      
					      }
				      }
				  </script>
				<?php } ?>
			</li>
			
			<?php endif; ?>
			<li>
				<h3 class="upperCase">Admission Test Results</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Examination Taken: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SCBJA_testNames[]' id='SCBJA_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SCBJA_testNames[]' id='SCBJA_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SCBJA_testNames[]' id='SCBJA_testNames3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='SCBJA_testNames[]' id='SCBJA_testNames2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='SCBJA_testNames[]' id='SCBJA_testNames4'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				
				<?php if(isset($SCBJA_testNames) && $SCBJA_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["SCBJA_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$SCBJA_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_testNames_error'></div></div>
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
			

			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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
				
			</li>

			<li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>MAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label>MAT Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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
			

			<li id="xat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
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

			<li id="cmat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'         tip="Mention your roll number for the exam."   validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"    value=''   />
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
				<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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

			<li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($cmatRankAdditional) && $cmatRankAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRankAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRankAdditional );  ?>";
				      document.getElementById("cmatRankAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankAdditional_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="gmat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>GMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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
			
			<li id="atma1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>ATMA REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
				<?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
				      document.getElementById("atmaRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>ATMA Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='atmaDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($atmaDateOfExaminationAdditional) && $atmaDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $atmaDateOfExaminationAdditional );  ?>";
				      document.getElementById("atmaDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="atma2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>ATMA Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
				      document.getElementById("atmaScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>ATMA Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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
			
			<?php  if($action!='updateScore'):?>
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
						if($educationDetail['fieldName'] == 'weFrom') {
							$workCompaniesExpFrom['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weFrom_mul_'.$i) {
									$workCompaniesExpFrom['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
				
						if($educationDetail['fieldName'] == 'weTill') {
							$workCompaniesExpTill['_mul_0'] = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=2;$i++) {
								if($educationDetail['fieldName'] == 'weTill_mul_'.$i) {
									$workCompaniesExpTill['_mul_'.$i] = $educationDetail['value'];
								}
							}
						}
					}
				}
			} //print_r($workCompaniesExpTill);
			
		
			if(count($workCompanies) > 0) {
		           ?> <li>
                                <h3 class="upperCase">Additional work experience block</h3>
                            </li>
				<?php
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$grossSalaryFieldName = 'annualSalarySCBJA'.$workCompanyKey;
					$grossSalaryFieldValue = $$grossSalaryFieldName;
					$workExpInMonthsName = 'workExpInMonths'.$workCompanyKey;
					$workExpInMonthsValue = $$workExpInMonthsName;
					$j++;
					
			?>
			
	  
			<li>
				
				<div class='additionalInfoLeftCol'>
				<label>No. of months at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpInMonthsName;?>' id='<?php echo $workExpInMonthsName;?>'  validate="validateFloat" minlength="1" maxlength="10" caption="No. of months at <?php echo $workCompany; ?>"    tip="Enter the number of months you worked at  <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpInMonthsValue) && $workExpInMonthsValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpInMonthsName;?>").value = "<?php echo str_replace("\n", '\n', $workExpInMonthsValue );  ?>";
				      document.getElementById("<?php echo $workExpInMonthsName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpInMonthsName;?>_error'></div></div>
				</div>
				
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Monthly Remuneration at <?php echo $workCompany; ?>:</label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $grossSalaryFieldName; ?>' id='<?php echo $grossSalaryFieldName; ?>'  validate="validateFloat" minlength="1" maxlength="10" caption="salary"    tip="Enter the monthly salary at <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>&nbsp;
				<?php if(isset($grossSalaryFieldValue) && $grossSalaryFieldValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $grossSalaryFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $grossSalaryFieldValue );  ?>";
				      document.getElementById("<?php echo $grossSalaryFieldName; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $grossSalaryFieldName; ?>_error'></div></div>
				</div>
				</div>
				</div>
			</li>
			<?php
				}
			}
			?>	
                      
                           
			     <li>
				<h3 class="upperCase">YOUR PARTICIPATION IN EXTRA CURRICULAR ACTIVITIES:  College Level onwards</h3>
				
			    </li>
				
			    <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Sports and games / NSS / NCC </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SCBJA_Games' id='SCBJA_Games'  style="width:318px; height:74px; padding:5px"    tip="If you participated in Sports and games / NSS / NCC, enter the details here."  validate="validateStr" caption="Sports and games / NSS / NCC" minlength="2" maxlength="100" ></textarea>
				<?php if(isset($SCBJA_Games) && $SCBJA_Games!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Games").value = "<?php echo str_replace("\n", '\n', $SCBJA_Games );  ?>";
				      document.getElementById("SCBJA_Games").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Games_error'></div></div>
				</div>
				</div>
				
			      </li>
			
			   <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Debates / Quiz  </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SCBJA_Debate' id='SCBJA_Debate'   style="width:318px; height:74px; padding:5px"    tip="If you participated in Debates / Quiz, enter the details here."  validate="validateStr" caption="Debates / Quiz" minlength="2" maxlength="100" ></textarea>
				<?php if(isset($SCBJA_Debate) && $SCBJA_Debate!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Debate").value = "<?php echo str_replace("\n", '\n', $SCBJA_Debate );  ?>";
				      document.getElementById("SCBJA_Debate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Debate_error'></div></div>
				</div>
				</div>
				
			      </li>
			    
			      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Any other</label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SCBJA_Others' id='SCBJA_Others'  style="width:318px; height:74px; padding:5px"    tip="If you participated in any other extra curricular activity, enter the details here."  validate="validateStr" caption="Any Other" minlength="2" maxlength="100" ></textarea>
				<?php if(isset($SCBJA_Others) && $SCBJA_Others!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_Others").value = "<?php echo str_replace("\n", '\n', $SCBJA_Others );  ?>";
				      document.getElementById("SCBJA_Others").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_Others_error'></div></div>
				</div>
				</div>
				
			      </li>
			
			     <li>
				<h3 class="upperCase">Short Essay</h3>
			    </li>
			    <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Write in about 100 words what you expect from this course. </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SCBJA_essay' id='SCBJA_essay' required="true"  style="width:618px; height:180px; padding:5px"    tip="Write in about 100 words what you expect from this course"  validate="validateStr" caption="essay" minlength="1" maxlength="1000" ></textarea>
				<?php if(isset($SCBJA_essay) && $SCBJA_essay!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_essay").value = "<?php echo str_replace("\n", '\n', $SCBJA_essay );  ?>";
				      document.getElementById("SCBJA_essay").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_essay_error'></div></div>
				</div>
				</div>
				
			</li>
			
			    <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>How do you propose to finance your studies?  </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SCBJA_finance' id='SCBJA_finance' required="true"  style="width:318px; height:74px; padding:5px"    tip="Please state how do you propose to finance your studies?" validate="validateStr" caption="How do you propose to finance your studies?" minlength="1" maxlength="100" ></textarea>
				<?php if(isset($SCBJA_finance) && $SCBJA_finance!=""){ ?>
				  <script>
				      document.getElementById("SCBJA_finance").value = "<?php echo str_replace("\n", '\n', $SCBJA_finance );  ?>";
				      document.getElementById("SCBJA_finance").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_finance_error'></div></div>
				</div>
				</div>
				
			</li>
			    
			    <li>
                                <h3 class="upperCase" style="margin-bottom:0">NAME, DESIGNATION, ADDRESS AND CONTACT NO. OF TWO REFEREES:</h3>
                             </li>
			     <li>
				If you are employed for more than one year submit reference letter from a) your immediate supervisor and b) employer.
In other cases, reference letters should be from your college teachers.
To be submitted in sealed envelopes.
			     </li>
                            <li>
				
                                <div class='additionalInfoLeftCol'>
                                <label>Reference 1 Name</label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='SCBJA_Ref1' id='SCBJA_Ref1'  validate="validateStr" required="true"    caption="Refree 1 Name"   minlength="2"   maxlength="100"     tip="Enter the full name of your first reference."   title="Refree 1 Name"  value=''   />
                                <?php if(isset($SCBJA_Ref1) && $SCBJA_Ref1!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Ref1").value = "<?php echo str_replace("\n", '\n', $SCBJA_Ref1 );  ?>";
                                      document.getElementById("SCBJA_Ref1").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Ref1_error'></div></div>
                                </div>
                                </div>

  		
                                <div class='additionalInfoRightCol'>
                                <label>Reference 1 Designation </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='SCBJA_Desg1' id='SCBJA_Desg1'  validate="validateStr"    required="true" caption="Refree 1 Designation "   minlength="2"   maxlength="100"     tip="Enter the designation of your first reference."   title="Refree 1 Designation "  value=''   />
                                <?php if(isset($SCBJA_Desg1) && $SCBJA_Desg1!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Desg1").value = "<?php echo str_replace("\n", '\n', $SCBJA_Desg1 );  ?>";
                                      document.getElementById("SCBJA_Desg1").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Desg1_error'></div></div>
                                </div>
                                </div>
			</li>
			
			<li style= "width:100%">

                                <div class='additionalInfoLeftCol' style="width:950px;">
                                <label>Refree 1 Address </label>
                                <div class='fieldBoxLarge' style="width:630px">
                                <textarea name='SCBJA_Add1' style="width:318px; height:74px; padding:5px"  id='SCBJA_Add1'  validate="validateStr"  required="true"  caption="Refree 1 Address "   minlength="2"   maxlength="100"     tip="Enter the complete address of your first reference"   title="Refree 1 Address "  value=''   /></textarea>
                                <?php if(isset($SCBJA_Add1) && $SCBJA_Add1!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Add1").value = "<?php echo str_replace("\n", '\n', $SCBJA_Add1 );  ?>";
                                      document.getElementById("SCBJA_Add1").style.color = "";
                                  </script>
                                <?php } ?>
				
                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Add1_error'></div></div>
                                </div>
                                </div>
			</li>
			<li>
			<div class='additionalInfoLeftCol'>
                                <label>Reference 1 Contact Number </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='SCBJA_Cont1' id='SCBJA_Cont1'  validate="validateMobileInteger"     required="true" caption="Refree 1 Contact Number "   minlength="10"   maxlength="10"     tip="Enter the contact number of your first reference."   title="Refree 1 Contact Number "  value=''   />
                                <?php //if(isset($SCBJA_Cont1 && $SCBJA_Cont1!=""){ ?>
				     <?php if(isset($SCBJA_Cont1) && $SCBJA_Cont1!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Cont1").value = "<?php echo str_replace("\n", '\n', $SCBJA_Cont1 );  ?>";
                                      document.getElementById("SCBJA_Cont1").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Cont1_error'></div></div>
                                </div>
                                </div>

			</li>	
                       
			<li>
			 <div class='additionalInfoLeftCol'>
                                <label>Refree 2 Name</label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='SCBJA_Ref2' id='SCBJA_Ref2'  validate="validateStr"   required="true"  caption="Refree 2 Name"   minlength="2"   maxlength="100"     tip="Enter the full name of your first reference."   title="Refree 2 Name"  value=''   />
                                <?php if(isset($SCBJA_Ref2) && $SCBJA_Ref2!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Ref2").value = "<?php echo str_replace("\n", '\n', $SCBJA_Ref2 );  ?>";
                                      document.getElementById("SCBJA_Ref2").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Ref2_error'></div></div>
                                </div>
                                </div>

                                <div class='additionalInfoRightCol'>
                                <label>Reference 2 Designation </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='SCBJA_Desg2' id='SCBJA_Desg2'  validate="validateStr"  required="true"   caption="Refree 2 Designation "   minlength="2"   maxlength="100"     tip="Enter the designation of your first reference."   title="Refree 2 Designation "  value=''   />
                                <?php if(isset($SCBJA_Desg2) && $SCBJA_Desg2!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Desg2").value = "<?php echo str_replace("\n", '\n', $SCBJA_Desg2 );  ?>";
                                      document.getElementById("SCBJA_Desg2").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Desg2_error'></div></div>
                                </div>
                                </div>
			</li>
			
			<li>

                                <div class='additionalInfoLeftCol' style="width:950px;">
                                <label>Reference 2 Address </label>
                                <div class='fieldBoxLarge' style="width:630px">
                                <textarea name='SCBJA_Add2' style="width:318px; height:74px; padding:5px"  id='SCBJA_Add2'  validate="validateStr"  required="true"  caption="Refree 2 Address "   minlength="2"   maxlength="100"     tip="Enter the complete address of your first reference"   title="Refree 2 Address "  value=''   /></textarea>
                                <?php if(isset($SCBJA_Add2) && $SCBJA_Add2!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Add2").value = "<?php echo str_replace("\n", '\n', $SCBJA_Add2 );  ?>";
                                      document.getElementById("SCBJA_Add2").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Add2_error'></div></div>
                                </div>
                                </div>
			
			</li>
			
			<li>
			  <div class='additionalInfoLeftCol'>
                                <label>Reference 2 Contact Number </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='SCBJA_Cont2' id='SCBJA_Cont2'  validate="validateMobileInteger"     required="true" caption="Refree 2 Contact Number "   minlength="10"   maxlength="10"     tip="Enter the contact number of your second reference."   title="Refree 2 Contact Number "  value=''   />
                                <?php if(isset($SCBJA_Cont2) && $SCBJA_Cont2!=""){ ?>
                                  <script>
                                      document.getElementById("SCBJA_Cont2").value = "<?php echo str_replace("\n", '\n', $SCBJA_Cont2 );  ?>";
                                      document.getElementById("SCBJA_Cont2").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'SCBJA_Cont2_error'></div></div>
                                </div>
                                </div>
			</li>
                      
                        <li>
				<div class='additionalInfoLeftCol'>
				<label>How did you come to know about SJCBA?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="options"  name='SCBJA_know' id='SCBJA_know0'   value='Shiksha.com' title="Select how you came to know about SCBJA?"   onmouseover="showTipOnline('Select how you came to know about SCBJA.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how you came to know about SJCBA.',this);" onmouseout="hidetip();" >Shiksha.com </span>&nbsp;&nbsp;

				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="options"  name='SCBJA_know' id='SCBJA_know1'   value='Internet'    title="Select how you came to know about SCBJA?"   onmouseover="showTipOnline('Select how you came to know about SCBJA.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how you came to know about SJCBA.',this);" onmouseout="hidetip();" >Internet</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="options"  name='SCBJA_know' id='SCBJA_know2'   value='Others'    title="Select how you came to know about SCBJA?"   onmouseover="showTipOnline('Select how you came to know about SCBJA.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select how you came to know about SJCBA.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($SCBJA_know) && $SCBJA_know!=""){ ?>
				
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SCBJA_know"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SCBJA_know;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_know_error'></div></div>
				</div>
				</div>
			 </li>
			
			
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option selected value="<?php echo $gdpiLocation['city_id']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
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
	
			<?php endif; ?>
			
			
			
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I have read the rules and regulations governing the PGDM Course and hereby undertake to follow them and any other the college may enact, and assure that I will participate effectively in the Course.
				I certify that the particulars given by me in this application form are true to the best of my knowledge and belief.</div>

				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='SCBJA_agreeToTerms[]' id='SCBJA_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($SCBJA_agreeToTerms) && $SCBJA_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["SCBJA_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$SCBJA_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'SCBJA_agreeToTerms0_error'></div></div>
				</div>
				</div>
				
			</li>
			
			<?php endif; ?>
		</ul>

</div>
</div>
<script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
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
  
    for(var j=0; j<5; j++){
	  checkTestScore(document.getElementById('SCBJA_testNames'+j));
    }
	
    count=2;
    new_count = 1 ;
      function openBox(){
         if(new_count<=8){ 
	 	new_count++; 
	 	while(count<8){ 
		  if($('Sem_'+count).style.display=='') 
		    count++; 
		  else 
		    break; 
	        } 
		$('Sem_'+count).style.display=''; 
		if((new_count==8)) 
		    $('add_more').style.display='none'; 
	 } 
	 	        else 
	 	          return false; 
      } 
    
    window.onload = function(e){
      for (var i  = 2 ; i <= 8 ; i ++ ) {
	if( $('SCBJA_Sem'+i+'BachelorDegreeR').value != '') {
	  new_count++ ;
	}
      }
      if(new_count == 8) {
	$('add_more').style.display='none';
      }
    }
    
  </script>
	
