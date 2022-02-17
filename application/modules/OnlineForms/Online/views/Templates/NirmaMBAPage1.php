<div class="formChildWrapper">
	<div class="formSection">
    	<ul>
    	<?php if($action != 'updateScore'):?>
			<li>
            	<h3 class="upperCase">Additional Personal Details:</h3>

                <div class="additionalInfoLeftCol">
                <label>Blood Group: </label>
                <div class='fieldBoxLarge'>
		    <input type='text' name='bloodGroupNirma' id='bloodGroupNirma'  validate="validateStr"    caption="blood group"   minlength="2"   maxlength="3"     tip="Enter your blood group here. If you are unsure about your blood group, just enter NA."   value=''  class="textboxSmall"/>                    
		    <?php if(isset($bloodGroupNirma) && $bloodGroupNirma!=""){ ?>
				    <script>
					document.getElementById("bloodGroupNirma").value = "<?php echo str_replace("\n", '\n', $bloodGroupNirma );  ?>";
					document.getElementById("bloodGroupNirma").style.color = "";
				    </script>
				  <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'bloodGroupNirma_error'></div></div>
                </div>
		</div>
            
		<div class="additionalInfoRightCol">
		    <label>Age as on May 31, 2014: </label>
		    <div class='fieldBoxLarge'>
			<input type='text' name='ageNirma' id='ageNirma'  validate="validateOnlineFormAge"   required="true"   caption="age"   minlength="2"   maxlength="2"     tip="Please enter your age, in years, as on May 31 2013.  "   value=''  class="textboxSmall"/>
			<?php if(isset($ageNirma) && $ageNirma!=""){ ?>
					<script>
					    document.getElementById("ageNirma").value = "<?php echo str_replace("\n", '\n', $ageNirma );  ?>";
					    document.getElementById("ageNirma").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'ageNirma_error'></div></div>
		    </div>
		
		</div>

	</li>

	<li>
        	<h3 class="upperCase">School Education (additional information):</h3>

    		<div class="additionalInfoLeftCol">
		    <label>Class 10th stream: </label>
		    <div class='fieldBoxLarge'>
			<input type='text' name='class10streamNirma' id='class10streamNirma'  validate="validateStr"   required="true"   caption="class 10th stream"   minlength="1"   maxlength="50"     tip="Enter education stream. If you are unsure about the stream, enter the subjects seperated by comma"   value=''  class="textboxLarge"/>
			<?php if(isset($class10streamNirma) && $class10streamNirma!=""){ ?>
					<script>
					    document.getElementById("class10streamNirma").value = "<?php echo str_replace("\n", '\n', $class10streamNirma );  ?>";
					    document.getElementById("class10streamNirma").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'class10streamNirma_error'></div></div>
		    </div>
		</div>
        
		<div class="additionalInfoRightCol">
			<label>Class 12th stream: </label>
		    <div class='fieldBoxLarge'>
			<input type='text' name='class12streamNirma' id='class12streamNirma'  validate="validateStr"   required="true"   caption="class 12th stream"   minlength="1"   maxlength="50"     tip="Enter education stream. If you are unsure about the stream, enter the subjects seperated by comma"   value='' class="textboxLarge" />
			<?php if(isset($class12streamNirma) && $class12streamNirma!=""){ ?>
					<script>
					    document.getElementById("class12streamNirma").value = "<?php echo str_replace("\n", '\n', $class12streamNirma );  ?>";
					    document.getElementById("class12streamNirma").style.color = "";
					</script>
				      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'class12streamNirma_error'></div></div>
		    </div>
		</div>
	</li>

	<li>
		<div class="additionalInfoLeftCol">
		    <label>Medium of instruction 10th: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='class10mediumNirma' id='class10mediumNirma'  validate="validateStr"   required="true"   caption="class 10th medium of instruction"   minlength="1"   maxlength="50"     tip="Enter your class 10th medium of instruction. Example: For english medium schools, enter English"   value='' class="textboxLarge" />
			  <?php if(isset($class10mediumNirma) && $class10mediumNirma!=""){ ?>
					  <script>
					      document.getElementById("class10mediumNirma").value = "<?php echo str_replace("\n", '\n', $class10mediumNirma );  ?>";
					      document.getElementById("class10mediumNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'class10mediumNirma_error'></div></div>
		    </div>
		</div>
		<div class="additionalInfoRightCol">
			<label>Medium of instruction 12th: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='class12mediumNirma' id='class12mediumNirma'  validate="validateStr"   required="true"   caption="class 12th medium of instruction"   minlength="1"   maxlength="50"     tip="Enter your class 12th medium of instruction. Example: For english medium schools, enter English"   value='' class="textboxLarge" />
			  <?php if(isset($class12mediumNirma) && $class12mediumNirma!=""){ ?>
					  <script>
					      document.getElementById("class12mediumNirma").value = "<?php echo str_replace("\n", '\n', $class12mediumNirma );  ?>";
					      document.getElementById("class12mediumNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'class12mediumNirma_error'></div></div>
		    </div>
		</div>
	</li>


	<li>
		<div class="additionalInfoLeftCol">
		    <label>Aggregate (%) 10th: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='class10totalMarksNirma' id='class10totalMarksNirma'  validate="validateFloat"   required="true"   caption="class 10th total marks"   minlength="1"   maxlength="5"     tip="Enter the total aggregate marks that you got in class 10th examination. Refer your class10th marksheet to know the exact marks. "   value='' class="textboxSmall" />
			  <?php if(isset($class10totalMarksNirma) && $class10totalMarksNirma!=""){ ?>
					  <script>
					      document.getElementById("class10totalMarksNirma").value = "<?php echo str_replace("\n", '\n', $class10totalMarksNirma );  ?>";
					      document.getElementById("class10totalMarksNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'class10totalMarksNirma_error'></div></div>
		    </div>
		</div>
		<div class="additionalInfoRightCol">
			<label>Aggregate (%) 12th: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='class12totalMarksNirma' id='class12totalMarksNirma'  validate="validateFloat"   required="true"   caption="class 12th total marks"   minlength="1"   maxlength="5"     tip="Enter the total aggregate marks that you got in class 12th examination. Refer your class12th marksheet to know the exact marks. "   value='' class="textboxSmall" />
			  <?php if(isset($class12totalMarksNirma) && $class12totalMarksNirma!=""){ ?>
					  <script>
					      document.getElementById("class12totalMarksNirma").value = "<?php echo str_replace("\n", '\n', $class12totalMarksNirma );  ?>";
					      document.getElementById("class12totalMarksNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'class12totalMarksNirma_error'></div></div>
		    </div>
		</div>
	</li>

<!--
	<li>
		<div class="additionalInfoLeftCol">
		    <label>Maximum total marks 10th: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='class10maxMarksNirma' id='class10maxMarksNirma'  validate="validateFloat"   required="true"   caption="class 10th max marks"   minlength="1"   maxlength="5"     tip='Enter the maximum total marks for all subject combined. Usually maximum marks are 100 for each subject. Refer your class 10th marksheet for details.'   value='' class="textboxSmall"  />
			  <?php if(isset($class10maxMarksNirma) && $class10maxMarksNirma!=""){ ?>
					  <script>
					      document.getElementById("class10maxMarksNirma").value = "<?php echo str_replace("\n", '\n', $class10maxMarksNirma );  ?>";
					      document.getElementById("class10maxMarksNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'class10maxMarksNirma_error'></div></div>
		    </div>
		</div>
		<div class="additionalInfoRightCol">
			<label>Maximum total marks 12th: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='class12maxMarksNirma' id='class12maxMarksNirma'  validate="validateFloat"   required="true"   caption="class 12th max marks"   minlength="1"   maxlength="5"     tip='Enter the maximum total marks for all subject combined. Usually maximum marks are 100 for each subject. Refer your class 12th marksheet for details.'   value='' class="textboxSmall" />
			  <?php if(isset($class12maxMarksNirma) && $class12maxMarksNirma!=""){ ?>
					  <script>
					      document.getElementById("class12maxMarksNirma").value = "<?php echo str_replace("\n", '\n', $class12maxMarksNirma );  ?>";
					      document.getElementById("class12maxMarksNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'class12maxMarksNirma_error'></div></div>
		    </div>
		</div>
	</li>

-->
	<!--<li>
		<div class="additionalInfoLeftCol">
		    <label>Aggregate % 10th: </label>
		    <div class='fieldBoxLarge'>
			    <div id="10thaggregate" style="font-size:12px; padding-top:4px; color:#696969">(Total marks/maximum marks) *100</div>
		    </div>
		</div>
		<div class="additionalInfoRightCol">
			<label>Aggregate % 12th: </label>
		    <div class='fieldBoxLarge'>
			    <div id="12thaggregate" style="font-size:12px; padding-top:4px; color:#696969">(Total marks/maximum marks) *100</div>
		    </div>
		</div>
	</li>-->



	<li>
		<div class="additionalInfoLeftCol">
		    <label>Pass attempt 10th: </label>
		    <div class='fieldBoxLarge'>
			    <input type='radio' name='class10passAttemptNirma' id='class10passAttemptNirma0'   value='First' onmouseover="showTipOnline('In how many attempts you cleared class 10th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();" checked ></input><span onmouseover="showTipOnline('In how many attempts you cleared class 10th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();">First</span>&nbsp;&nbsp;
			    <input type='radio' name='class10passAttemptNirma' id='class10passAttemptNirma1'   value='more'  onmouseover="showTipOnline('In how many attempts you cleared class 10th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('In how many attempts you cleared class 10th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();">more</span>&nbsp;&nbsp;
			    <?php if(isset($class10passAttemptNirma) && $class10passAttemptNirma!=""){ ?>
					    <script>
						radioObj = document.forms["OnlineForm"].elements["class10passAttemptNirma"];
						var radioLength = radioObj.length;
						for(var i = 0; i < radioLength; i++) {
							radioObj[i].checked = false;
							if(radioObj[i].value == "<?php echo $class10passAttemptNirma;?>") {
								radioObj[i].checked = true;
							}
						}
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'class10passAttemptNirma_error'></div></div>
		    </div>
		</div>
        
        <div class="additionalInfoRightCol">
		    <label>Pass attempt 12th: </label>
		    <div class='fieldBoxLarge'>
			    <input type='radio' name='class12passAttemptNirma' id='class12passAttemptNirma0'   value='First' onmouseover="showTipOnline('In how many attempts you cleared class 12th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();" checked ></input><span onmouseover="showTipOnline('In how many attempts you cleared class 12th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();">First</span>&nbsp;&nbsp;
			    <input type='radio' name='class12passAttemptNirma' id='class12passAttemptNirma1'   value='more'  onmouseover="showTipOnline('In how many attempts you cleared class 12th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('In how many attempts you cleared class 12th exam. Select First if you cleared the exam in one attempt.',this);" onmouseout="hidetip();">more</span>&nbsp;&nbsp;
			    <?php if(isset($class12passAttemptNirma) && $class12passAttemptNirma!=""){ ?>
					    <script>
						radioObj = document.forms["OnlineForm"].elements["class12passAttemptNirma"];
						var radioLength = radioObj.length;
						for(var i = 0; i < radioLength; i++) {
							radioObj[i].checked = false;
							if(radioObj[i].value == "<?php echo $class12passAttemptNirma;?>") {
								radioObj[i].checked = true;
							}
						}
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'class12passAttemptNirma_error'></div></div>
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
	<h3 class="upperCase">College education (additional information):</h3>
        <div class="clearFix"></div>

    	<div class="additionalInfoLeftCol" style="width:570px">
		    <label>Have you completed your Bachelor's Degree? :</label>
		    <div class='fieldBoxLarge' style="width: 236px;">
			  <input type='radio' name='statusNirma' id='statusNirma0'   value='Completed'  checked onmouseover="showTipOnline('Enter the status of your graduation degree. If you have completed the course, select Completed. If you are still pursuing this course, select Not completed.',this);" onmouseout="hidetip();"></input><span onmouseover="showTipOnline('Enter the status of your graduation degree. If you have completed the course, select Completed. If you are still pursuing this course, select Not completed.',this);" onmouseout="hidetip();">Yes</span>&nbsp;&nbsp;
			  <input type='radio' name='statusNirma' id='statusNirma1'   value='Not Completed'  onmouseover="showTipOnline('Enter the status of your graduation degree. If you have completed the course, select Completed. If you are still pursuing this course, select Not completed.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('Enter the status of your graduation degree. If you have completed the course, select Completed. If you are still pursuing this course, select Not completed.',this);" onmouseout="hidetip();">No</span>&nbsp;&nbsp;
			  <?php if(isset($statusNirma) && $statusNirma!=""){ ?>
					  <script>
					      radioObj = document.forms["OnlineForm"].elements["statusNirma"];
					      var radioLength = radioObj.length;
					      for(var i = 0; i < radioLength; i++) {
						      radioObj[i].checked = false;
						      if(radioObj[i].value == "<?php echo $statusNirma;?>") {
							      radioObj[i].checked = true;
						      }
					      }
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'statusNirma_error'></div></div>
		    </div>
		</div>
    </li>

	<li>
   
		<div class="additionalInfoLeftCol" style="width:570px">
		    <label>Mode for <?php echo $graduationCourseName;?>: </label>
		    <div class='fieldBoxLarge' style="width:260px">
			  <input type='radio' name='modeCourseNirma' id='modeCourseNirma0'   value='Full Time' onmouseover="showTipOnline('Enter the mode for <?php echo $graduationCourseName;?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();" checked ></input><span onmouseover="showTipOnline('Enter the mode for <?php echo $graduationCourseName;?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();">Full Time</span>&nbsp;&nbsp;
			  <input type='radio' name='modeCourseNirma' id='modeCourseNirma1'   value='Distance'  onmouseover="showTipOnline('Enter the mode for <?php echo $graduationCourseName;?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('Enter the mode for <?php echo $graduationCourseName;?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();">Distance</span>&nbsp;&nbsp;
			  <input type='radio' name='modeCourseNirma' id='modeCourseNirma2'   value='Others'   onmouseover="showTipOnline('Enter the mode for <?php echo $graduationCourseName;?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();"></input><span onmouseover="showTipOnline('Enter the mode for <?php echo $graduationCourseName;?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();">Others</span>&nbsp;&nbsp;
			  <?php if(isset($modeCourseNirma) && $modeCourseNirma!=""){ ?>
					  <script>
					      radioObj = document.forms["OnlineForm"].elements["modeCourseNirma"];
					      var radioLength = radioObj.length;
					      for(var i = 0; i < radioLength; i++) {
						      radioObj[i].checked = false;
						      if(radioObj[i].value == "<?php echo $modeCourseNirma;?>") {
							      radioObj[i].checked = true;
						      }
					      }
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'modeCourseNirma_error'></div></div>
		    </div>
		</div>
	</li>

	<li>
		<div class="additionalInfoLeftCol">
		    <label><?php echo $graduationCourseName;?> Stream: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='streamNirma' id='streamNirma'  validate="validateStr"   required="true"   caption="stream"   minlength="1"   maxlength="100"     tip="Enter education stream or specialization. For example, if you did B.A. Honors in Economics, your stream will be Economics, If you did BTECH in Mechanical Engineering, you stream will be Mechanical."   value='' class="textboxLarge" />
			  <?php if(isset($streamNirma) && $streamNirma!=""){ ?>
					  <script>
					      document.getElementById("streamNirma").value = "<?php echo str_replace("\n", '\n', $streamNirma );  ?>";
					      document.getElementById("streamNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'streamNirma_error'></div></div>
		    </div>
		</div>
		<div class="additionalInfoRightCol">
			<label>Medium of instruction: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='mediumNirma' id='mediumNirma'  validate="validateStr"   required="true"   caption="medium of instruction"   minlength="1"   maxlength="50"     tip="Enter the medium of instruction i.e. the language in which you were tought this course. Example: For english medium, enter English"   value='' class="textboxLarge" />
			  <?php if(isset($mediumNirma) && $mediumNirma!=""){ ?>
					<script>
					    document.getElementById("mediumNirma").value = "<?php echo str_replace("\n", '\n', $mediumNirma );  ?>";
					    document.getElementById("mediumNirma").style.color = "";
					</script>
				      <?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'mediumNirma_error'></div></div>
		    </div>
		</div>
	</li>


	<li>
		<!--<div class="additionalInfoLeftCol">
		    <label>Examination system :</label>
		    <div class='fieldBoxLarge'>
			  <input type='radio' name='examSystemNirma' id='examSystemNirma0'   value='Annual'  checked onmouseover="showTipOnline('Enter the education system for this course. If you had examinations every semeter, select Semester. If you had yearly examinations, select Annual.',this);" onmouseout="hidetip();"></input><span onmouseover="showTipOnline('Enter the education system for this course. If you had examinations every semeter, select Semester. If you had yearly examinations, select Annual.',this);" onmouseout="hidetip();">Annual</span>&nbsp;&nbsp;
			  <input type='radio' name='examSystemNirma' id='examSystemNirma1'   value='Semester'  onmouseover="showTipOnline('Enter the education system for this course. If you had examinations every semeter, select Semester. If you had yearly examinations, select Annual.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('Enter the education system for this course. If you had examinations every semeter, select Semester. If you had yearly examinations, select Annual.',this);" onmouseout="hidetip();">Semester</span>&nbsp;&nbsp;
			  <?php if(isset($examSystemNirma) && $examSystemNirma!=""){ ?>
					  <script>
					      radioObj = document.forms["OnlineForm"].elements["examSystemNirma"];
					      var radioLength = radioObj.length;
					      for(var i = 0; i < radioLength; i++) {
						      radioObj[i].checked = false;
						      if(radioObj[i].value == "<?php echo $examSystemNirma;?>") {
							      radioObj[i].checked = true;
						      }
					      }
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'examSystemNirma_error'></div></div>
		    </div>
		</div>-->
<div class="additionalInfoLeftCol">
		    <label>Year of Admission:</label>
		    <div class='smallTxtBoxWrap'>
			  <input class="textboxSmall" type='text' name='graduationYearAdmissionNirma' id='graduationYearAdmissionNirma'  validate="validateInteger"   required="true"   caption="admission year"   minlength="2"   maxlength="4"     tip="Enter the Admission Year of Graduation."   value='' />
			  <?php if(isset($graduationYearAdmissionNirma) && $graduationYearAdmissionNirma!=""){ ?>
					  <script>
					      document.getElementById("graduationYearAdmissionNirma").value = "<?php echo str_replace("\n", '\n', $graduationYearAdmissionNirma );  ?>";
					      document.getElementById("graduationYearAdmissionNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'graduationYearAdmissionNirma_error'></div></div>
		    </div>
		</div>
<div class="additionalInfoRightCol">
		    <label>Aggregate percentage:</label>
		    <div class='smallTxtBoxWrap'>
			  <input class="textboxSmall" type='text' name='graduationAggPercentageNirma' id='graduationAggPercentageNirma'  validate="validateFloat"   required="true"   caption="aggregate percentage"   minlength="2"   maxlength="5"     tip="Enter the Aggregate percentage for Graduation."   value='' />
			  <?php if(isset($graduationAggPercentageNirma) && $graduationAggPercentageNirma!=""){ ?>
					  <script>
					      document.getElementById("graduationAggPercentageNirma").value = "<?php echo str_replace("\n", '\n', $graduationAggPercentageNirma );  ?>";
					      document.getElementById("graduationAggPercentageNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'graduationAggPercentageNirma_error'></div></div>
		    </div>
		</div>
    </li>
    

  <!--  <li>
	    <div class="semesterDetailsBox">
		<strong>First Year</strong>
		<div class="spacer10 clearFix"></div>
		<div class="additionalInfoLeftCol">
		      <label>First Semester Aggregate Marks (%): </label>
		      <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='firstYearFirstSemAggregateNirma' id='firstYearFirstSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for first semeter for first year. If you did not have a semeter system, enter NA"   value='' allowNA="true" />
		      <?php if(isset($firstYearFirstSemAggregateNirma) && $firstYearFirstSemAggregateNirma!=""){ ?>
				      <script>
					  document.getElementById("firstYearFirstSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $firstYearFirstSemAggregateNirma );  ?>";
					  document.getElementById("firstYearFirstSemAggregateNirma").style.color = "";
				      </script>
				    <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'firstYearFirstSemAggregateNirma_error'></div></div>
			</div>
		</div>

		<div class="additionalInfoRightCol">
		    <label>Second Semester Aggregate Marks (%): </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='firstYearSecondSemAggregateNirma' id='firstYearSecondSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for second semeter for first year. If you did not have a semeter system, enter NA"   value='' allowNA="true" />
		    <?php if(isset($firstYearSecondSemAggregateNirma) && $firstYearSecondSemAggregateNirma!=""){ ?>
			    <script>
				document.getElementById("firstYearSecondSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $firstYearSecondSemAggregateNirma );  ?>";
				document.getElementById("firstYearSecondSemAggregateNirma").style.color = "";
			    </script>
			      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'firstYearSecondSemAggregateNirma_error'></div></div>
		    </div>
		</div>
		<div class="spacer10 clearFix"></div>
		<div class="additionalInfoLeftCol">
			<label>Overall Aggregate Marks (%): </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='firstYearOverallAggregateNirma' id='firstYearOverallAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for first year."   value='' allowNA="true" />
		<?php if(isset($firstYearOverallAggregateNirma) && $firstYearOverallAggregateNirma!=""){ ?>
				<script>
				    document.getElementById("firstYearOverallAggregateNirma").value = "<?php echo str_replace("\n", '\n', $firstYearOverallAggregateNirma );  ?>";
				    document.getElementById("firstYearOverallAggregateNirma").style.color = "";
				</script>
			      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'firstYearOverallAggregateNirma_error'></div></div>
			</div>
		</div>

		<div class="additionalInfoRightCol">
		    <label>Year of Passing: </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='firstYearpassingYearNirma' id='firstYearpassingYearNirma'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for first year."   value='' allowNA="true" />
		    <?php if(isset($firstYearpassingYearNirma) && $firstYearpassingYearNirma!=""){ ?>
			    <script>
				document.getElementById("firstYearpassingYearNirma").value = "<?php echo str_replace("\n", '\n', $firstYearpassingYearNirma );  ?>";
				document.getElementById("firstYearpassingYearNirma").style.color = "";
			    </script>
			      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'firstYearpassingYearNirma_error'></div></div>
		    </div>
		</div>
	</div>
</li>

<li>
	<div class="semesterDetailsBox">
    <strong>Second Year</strong>
    <div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
    <label>First Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='secondYearFirstSemAggregateNirma' id='secondYearFirstSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for first semeter for second year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
<?php if(isset($secondYearFirstSemAggregateNirma) && $secondYearFirstSemAggregateNirma!=""){ ?>
		<script>
		    document.getElementById("secondYearFirstSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $secondYearFirstSemAggregateNirma );  ?>";
		    document.getElementById("secondYearFirstSemAggregateNirma").style.color = "";
		</script>
	      <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'secondYearFirstSemAggregateNirma_error'></div></div>
	</div>
</div>

<div class="additionalInfoRightCol">
    <label>Second Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='secondYearSecondSemAggregateNirma' id='secondYearSecondSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for second semeter for second year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
    <?php if(isset($secondYearSecondSemAggregateNirma) && $secondYearSecondSemAggregateNirma!=""){ ?>
            <script>
                document.getElementById("secondYearSecondSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $secondYearSecondSemAggregateNirma );  ?>";
                document.getElementById("secondYearSecondSemAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'secondYearSecondSemAggregateNirma_error'></div></div>
    </div>
</div>
<div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
    <label>Overall Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='secondYearOverallAggregateNirma' id='secondYearOverallAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for second year."   value='' allowNA='true' />
    <?php if(isset($secondYearOverallAggregateNirma) && $secondYearOverallAggregateNirma!=""){ ?>
            <script>
                document.getElementById("secondYearOverallAggregateNirma").value = "<?php echo str_replace("\n", '\n', $secondYearOverallAggregateNirma );  ?>";
                document.getElementById("secondYearOverallAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'secondYearOverallAggregateNirma_error'></div></div>
    </div>
    </div>

		<div class="additionalInfoRightCol">
		    <label>Year of Passing: </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='secondYearpassingYearNirma' id='secondYearpassingYearNirma'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for second year."   value='' allowNA="true" />
		    <?php if(isset($secondYearpassingYearNirma) && $secondYearpassingYearNirma!=""){ ?>
			    <script>
				document.getElementById("secondYearpassingYearNirma").value = "<?php echo str_replace("\n", '\n', $secondYearpassingYearNirma );  ?>";
				document.getElementById("secondYearpassingYearNirma").style.color = "";
			    </script>
			      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'secondYearpassingYearNirma_error'></div></div>
		    </div>
		</div>

</div>
</li>


<li>
<div class="semesterDetailsBox">
    <strong>Third Year</strong>
    <div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
    <label>First Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='thirdYearFirstSemAggregateNirma' id='thirdYearFirstSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for first semeter for third year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
    <?php if(isset($thirdYearFirstSemAggregateNirma) && $thirdYearFirstSemAggregateNirma!=""){ ?>
            <script>
                document.getElementById("thirdYearFirstSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $thirdYearFirstSemAggregateNirma );  ?>";
                document.getElementById("thirdYearFirstSemAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'thirdYearFirstSemAggregateNirma_error'></div></div>
    </div>
</div>

<div class="additionalInfoRightCol">
    <label>Second Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='thirdYearSecondSemAggregateNirma' id='thirdYearSecondSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for second semeter for third year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
    <?php if(isset($thirdYearSecondSemAggregateNirma) && $thirdYearSecondSemAggregateNirma!=""){ ?>
            <script>
                document.getElementById("thirdYearSecondSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $thirdYearSecondSemAggregateNirma );  ?>";
                document.getElementById("thirdYearSecondSemAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'thirdYearSecondSemAggregateNirma_error'></div></div>
    </div>
	</div>
<div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
    <label>Overall Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='thirdYearOverallAggregateNirma' id='thirdYearOverallAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for third year."   value='' allowNA='true' />
    <?php if(isset($thirdYearOverallAggregateNirma) && $thirdYearOverallAggregateNirma!=""){ ?>
            <script>
                document.getElementById("thirdYearOverallAggregateNirma").value = "<?php echo str_replace("\n", '\n', $thirdYearOverallAggregateNirma );  ?>";
                document.getElementById("thirdYearOverallAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'thirdYearOverallAggregateNirma_error'></div></div>
    </div>
</div>

		<div class="additionalInfoRightCol">
		    <label>Year of Passing: </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='thirdYearpassingYearNirma' id='thirdYearpassingYearNirma'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for third year."   value='' allowNA="true" />
		    <?php if(isset($thirdYearpassingYearNirma) && $thirdYearpassingYearNirma!=""){ ?>
			    <script>
				document.getElementById("thirdYearpassingYearNirma").value = "<?php echo str_replace("\n", '\n', $thirdYearpassingYearNirma );  ?>";
				document.getElementById("thirdYearpassingYearNirma").style.color = "";
			    </script>
			      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'thirdYearpassingYearNirma_error'></div></div>
		    </div>
		</div>

</div>
</li>


<li>
<div class="semesterDetailsBox">
    <strong>Fourth Year</strong>
    <div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
    <label>First Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fourthYearFirstSemAggregateNirma' id='fourthYearFirstSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for first semeter for fourth year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
    <?php if(isset($fourthYearFirstSemAggregateNirma) && $fourthYearFirstSemAggregateNirma!=""){ ?>
            <script>
                document.getElementById("fourthYearFirstSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $fourthYearFirstSemAggregateNirma );  ?>";
                document.getElementById("fourthYearFirstSemAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'fourthYearFirstSemAggregateNirma_error'></div></div>
    </div>
	</div>
<div class="additionalInfoRightCol">
    <label>Second Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fourthYearSecondSemAggregateNirma' id='fourthYearSecondSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for second semeter for fourth year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
    <?php if(isset($fourthYearSecondSemAggregateNirma) && $fourthYearSecondSemAggregateNirma!=""){ ?>
            <script>
                document.getElementById("fourthYearSecondSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $fourthYearSecondSemAggregateNirma );  ?>";
                document.getElementById("fourthYearSecondSemAggregateNirma").style.color = "";
            </script>
              <?php } ?>
    <div style='display:none'><div class='errorMsg' id= 'fourthYearSecondSemAggregateNirma_error'></div></div>
    </div>
    </div>
<div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
    <label>Overall Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fourthYearOverallAggregateNirma' id='fourthYearOverallAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for fourth year."   value='' allowNA='true' />
<?php if(isset($fourthYearOverallAggregateNirma) && $fourthYearOverallAggregateNirma!=""){ ?>
		<script>
		    document.getElementById("fourthYearOverallAggregateNirma").value = "<?php echo str_replace("\n", '\n', $fourthYearOverallAggregateNirma );  ?>";
		    document.getElementById("fourthYearOverallAggregateNirma").style.color = "";
		</script>
	      <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'fourthYearOverallAggregateNirma_error'></div></div>
	</div>
</div>

		<div class="additionalInfoRightCol">
		    <label>Year of Passing: </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fourthYearpassingYearNirma' id='fourthYearpassingYearNirma'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for fourth year."   value='' allowNA="true" />
		    <?php if(isset($fourthYearpassingYearNirma) && $fourthYearpassingYearNirma!=""){ ?>
			    <script>
				document.getElementById("fourthYearpassingYearNirma").value = "<?php echo str_replace("\n", '\n', $fourthYearpassingYearNirma );  ?>";
				document.getElementById("fourthYearpassingYearNirma").style.color = "";
			    </script>
			      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'fourthYearpassingYearNirma_error'></div></div>
		    </div>
		</div>

</div>
</li>

<li>
<div class="semesterDetailsBox">
    <strong>Fifth Year</strong>
    <div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
	<label>First Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fifthYearFirstSemAggregateNirma' id='fifthYearFirstSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for first semeter for fifth year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
<?php if(isset($fifthYearFirstSemAggregateNirma) && $fifthYearFirstSemAggregateNirma!=""){ ?>
		<script>
		    document.getElementById("fifthYearFirstSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $fifthYearFirstSemAggregateNirma );  ?>";
		    document.getElementById("fifthYearFirstSemAggregateNirma").style.color = "";
		</script>
	      <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'fifthYearFirstSemAggregateNirma_error'></div></div>
	</div>
</div>

<div class="additionalInfoRightCol">
    <label>Second Semester Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fifthYearSecondSemAggregateNirma' id='fifthYearSecondSemAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for second semeter for fifth year. If you did not have a semeter system, enter NA"   value='' allowNA='true' />
    <?php if(isset($fifthYearSecondSemAggregateNirma) && $fifthYearSecondSemAggregateNirma!=""){ ?>
            <script>
                document.getElementById("fifthYearSecondSemAggregateNirma").value = "<?php echo str_replace("\n", '\n', $fifthYearSecondSemAggregateNirma );  ?>";
                document.getElementById("fifthYearSecondSemAggregateNirma").style.color = "";
            </script>
              <?php } ?>
        <div style='display:none'><div class='errorMsg' id= 'fifthYearSecondSemAggregateNirma_error'></div></div>
        </div>
    </div>
<div class="spacer10 clearFix"></div>
<div class="additionalInfoLeftCol">
	<label>Overall Aggregate Marks (%): </label>
    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fifthYearOverallAggregateNirma' id='fifthYearOverallAggregateNirma'  validate="validateFloat"   required="true"   caption="aggregate marks"   minlength="1"   maxlength="5"     tip="Enter the aggregate marks for fifth year."   value='' allowNA='true' />
<?php if(isset($fifthYearOverallAggregateNirma) && $fifthYearOverallAggregateNirma!=""){ ?>
		<script>
		    document.getElementById("fifthYearOverallAggregateNirma").value = "<?php echo str_replace("\n", '\n', $fifthYearOverallAggregateNirma );  ?>";
		    document.getElementById("fifthYearOverallAggregateNirma").style.color = "";
		</script>
	      <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'fifthYearOverallAggregateNirma_error'></div></div>
		</div>
	</div>

		<div class="additionalInfoRightCol">
		    <label>Year of Passing: </label>
		    <div class='smallTxtBoxWrap'><input class="textboxSmall" type='text' name='fifthYearpassingYearNirma' id='fifthYearpassingYearNirma'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for fifth year."   value='' allowNA="true" />
		    <?php if(isset($fifthYearpassingYearNirma) && $fifthYearpassingYearNirma!=""){ ?>
			    <script>
				document.getElementById("fifthYearpassingYearNirma").value = "<?php echo str_replace("\n", '\n', $fifthYearpassingYearNirma );  ?>";
				document.getElementById("fifthYearpassingYearNirma").style.color = "";
			    </script>
			      <?php } ?>
		    <div style='display:none'><div class='errorMsg' id= 'fifthYearpassingYearNirma_error'></div></div>
		    </div>
		</div>

</div>
</li>

<li>
	<div class="additionalInfoLeftCol">
		    <label>No. of Subjects studied and results are available:</label>
		    <div class='smallTxtBoxWrap'>
			  <input class="textboxSmall" type='text' name='subjectsStudiedNirma' id='subjectsStudiedNirma'  validate="validateInteger"   required="true"   caption="subjects studied"   minlength="1"   maxlength="3"     tip="Enter the total number of subjects studied during your graduation. Only numeric values"   value='' />
			  <?php if(isset($subjectsStudiedNirma) && $subjectsStudiedNirma!=""){ ?>
					  <script>
					      document.getElementById("subjectsStudiedNirma").value = "<?php echo str_replace("\n", '\n', $subjectsStudiedNirma );  ?>";
					      document.getElementById("subjectsStudiedNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'subjectsStudiedNirma_error'></div></div>
		    </div>
		</div>


		<div class="additionalInfoRightCol">
		    <label>Total Marks obtained:</label>
		    <div class='smallTxtBoxWrap'>
			  <input class="textboxSmall" type='text' name='graduationTotalMarksNirma' id='graduationTotalMarksNirma'  validate="validateInteger"   required="true"   caption="total marks"   minlength="1"   maxlength="4"     tip="Enter the total marks obtained in Graduation."   value='' />
			  <?php if(isset($graduationTotalMarksNirma) && $graduationTotalMarksNirma!=""){ ?>
					  <script>
					      document.getElementById("graduationTotalMarksNirma").value = "<?php echo str_replace("\n", '\n', $graduationTotalMarksNirma );  ?>";
					      document.getElementById("graduationTotalMarksNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'graduationTotalMarksNirma_error'></div></div>
		    </div>
		</div>
    </li>

    <li>
		<!--<div class="additionalInfoLeftCol">
		    <label>Maximum Total Marks :</label>
		    <div class='smallTxtBoxWrap'>
			  <input class="textboxSmall" type='text' name='graduationMaxMarksNirma' id='graduationMaxMarksNirma'  validate="validateInteger"   required="true"   caption="maximum total marks"   minlength="1"   maxlength="4"     tip="Enter the Maximum total marks in Graduation."   value='' />
			  <?php if(isset($graduationMaxMarksNirma) && $graduationMaxMarksNirma!=""){ ?>
					  <script>
					      document.getElementById("graduationMaxMarksNirma").value = "<?php echo str_replace("\n", '\n', $graduationMaxMarksNirma );  ?>";
					      document.getElementById("graduationMaxMarksNirma").style.color = "";
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'graduationMaxMarksNirma_error'></div></div>
		    </div>
		</div>

		

    </li>
-->     
    <li>
    <div class="additionalInfoLeftCol">
		    <label>Have you passed all the examinations in single attempt?:</label>
		    <div class='flLt'>
			  <input type='radio' name='passAttempNirma' id='passAttempNirma0'   value='Yes'  checked onmouseover="showTipOnline('Select Yes is you have passed all the examination in your graduation course in single attempt and No if you had to give certain exams again.',this);" onmouseout="hidetip();"></input><span onmouseover="showTipOnline('Select Yes is you have passed all the examination in your graduation course in single attempt and No if you had to give certain exams again.',this);" onmouseout="hidetip();">Yes</span>&nbsp;&nbsp;
			  <input type='radio' name='passAttempNirma' id='passAttempNirma1'   value='No'  onmouseover="showTipOnline('Select Yes is you have passed all the examination in your graduation course in single attempt and No if you had to give certain exams again.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('Select Yes is you have passed all the examination in your graduation course in single attempt and No if you had to give certain exams again.',this);" onmouseout="hidetip();">No</span>&nbsp;&nbsp;
			  <?php if(isset($passAttempNirma) && $passAttempNirma!=""){ ?>
					  <script>
					      radioObj = document.forms["OnlineForm"].elements["passAttempNirma"];
					      var radioLength = radioObj.length;
					      for(var i = 0; i < radioLength; i++) {
						      radioObj[i].checked = false;
						      if(radioObj[i].value == "<?php echo $passAttempNirma;?>") {
							      radioObj[i].checked = true;
						      }
					      }
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= 'passAttempNirma_error'></div></div>
		    </div>
		</div>
    </li>



	<?php
	if(count($otherCourses)>0) {
		foreach($otherCourses as $otherCourseId => $otherCourseName) {
		
			$oMode = 'otherCourseMode_mul_'.$otherCourseId;
			$oModeVal = $$oMode;
			$oStream = 'otherCourseStream_mul_'.$otherCourseId;
			$oStreamVal = $$oStream;
			
	?>

	<li>

		<div class="additionalInfoLeftCol" style="width:570px">
		    <label>Mode for <?php echo $otherCourseName; ?>: </label>
		    <div class='fieldBoxLarge' style="width:260px">
			  <input type='radio' name='<?php echo $oMode; ?>' id='<?php echo $oMode; ?>0'   value='Full Time'  checked onmouseover="showTipOnline('Enter the mode for <?php echo $otherCourseName; ?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();"></input><span onmouseover="showTipOnline('Enter the mode for <?php echo $otherCourseName; ?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();">Full Time</span>&nbsp;&nbsp;
			  <input type='radio' name='<?php echo $oMode; ?>' id='<?php echo $oMode; ?>1'   value='Distance'   onmouseover="showTipOnline('Enter the mode for <?php echo $otherCourseName; ?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();"></input><span onmouseover="showTipOnline('Enter the mode for <?php echo $otherCourseName; ?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();">Distance</span>&nbsp;&nbsp;
			  <input type='radio' name='<?php echo $oMode; ?>' id='<?php echo $oMode; ?>2'   value='Others'  onmouseover="showTipOnline('Enter the mode for <?php echo $otherCourseName; ?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();" ></input><span onmouseover="showTipOnline('Enter the mode for <?php echo $otherCourseName; ?>. If it was full time course select full time or select the relevant mode that applies to you.',this);" onmouseout="hidetip();">Others</span>&nbsp;&nbsp;

			  <?php if(isset($oModeVal) && $oModeVal!=""){ ?>
					  <script>
					      radioObj = document.forms["OnlineForm"].elements["<?php echo $oMode; ?>"];
					      var radioLength = radioObj.length;
					      for(var i = 0; i < radioLength; i++) {
						      radioObj[i].checked = false;
						      if(radioObj[i].value == "<?php echo $oModeVal;?>") {
							      radioObj[i].checked = true;
						      }
					      }
					  </script>
					<?php } ?>
			  <div style='display:none'><div class='errorMsg' id= '<?php echo $oMode; ?>_error'></div></div>

		    </div>
		</div>
	</li>
	<li>
		<div class="additionalInfoLeftCol">
		    <label>Stream of <?php echo $otherCourseName; ?>: </label>
		    <div class='fieldBoxLarge'>
			  <input type='text' name='<?php echo $oStream; ?>' id='<?php echo $oStream; ?>'  validate="validateStr"   required="true"   caption="Stream for <?php echo $otherCourseName; ?>"   minlength="2" maxlength="50" tip="Enter education stream or specialization. For example, if you did B.A. Honors in Economics, your stream will be Economics, If you did BTECH in Mechanical Engineering, you stream will be Mechanical."   value='' class="textboxLarge" />
			<?php if(isset($oStreamVal) && $oStreamVal!=""){ ?>
			<script>
			  document.getElementById("<?php echo $oStream; ?>").value = "<?php echo str_replace("\n", '\n', $oStreamVal );  ?>";
			  document.getElementById("<?php echo $oStream; ?>").style.color = "";
		      </script>
			<?php } ?>
			<div style='display:none'><div class='errorMsg' id= '<?php echo $oStream; ?>_error'></div></div>
		    </div>
		</div>

	</li>

	<?php
		}
	}
	?>

   <?php endif;?>
	<li>
    	<h3 class="upperCase">CAT Examination Details:</h3>
        <div class="clearFix"></div>
        <div class="additionalInfoLeftCol">
    		<label style="font-weight:normal">Date of Examination: </label>
        	<div class='float_L' style="width:125px;">
        	<input class="calenderFields" type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'         onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='catDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
		<?php if(isset($catDateOfExaminationAdditional) && $catDateOfExaminationAdditional!=""){ ?>
				<script>
				document.getElementById("catDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $catDateOfExaminationAdditional );  ?>";
				document.getElementById("catDateOfExaminationAdditional").style.color = "";
				</script>
			  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'catDateOfExaminationAdditional_error'></div></div>
			</div>
        </div>
        
        <div class="additionalInfoRightCol">
            <label style="width:90px !important; font-weight:normal">Score: </label>
            <div class='float_L'  style="width:250px;">
                <input class="textboxSmaller" type='text' name='catScoreAdditional' id='catScoreAdditional'  validate="validateFloat" required="true" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
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
        <div class="additionalInfoLeftCol">
    		<label style="font-weight:normal">Roll No.: </label>
            <div class='fieldBoxLarge'>
            <input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"  required="true" allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
            <?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
                    <script>
                    document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
                    document.getElementById("catRollNumberAdditional").style.color = "";
                    </script>
                  <?php } ?>
            <div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
            </div>
        </div>
        
        <div class="additionalInfoRightCol">
            <label style="width:90px !important; font-weight:normal">Percentile: </label>
            <div class='float_L'  style="width:250px;">
               <input class="textboxSmaller"  type='text' name='catPercentileAdditional' id='catPercentileAdditional'  validate="validateFloat" required="true" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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


   <?php if($action != 'updateScore'):?>
	<!--<li>

    	<label style="font-weight:normal">Extra-Curricular Activities (If any): </label>
        <div class='float_L'>
        	<textarea  name='extraCurricularNirma' id='extraCurricularNirma'         tip="Enter here if you took part in any extra curricular activities. Eg: Listening to music, Western dance, Playing cricket. List all the extra curricular activities seperated by a comma. If you do not have any extra curricular activity, enter NA"  style="width:613px" minlength='2' maxlength='1000' validate="validateStr" caption="extra-curricular activities"></textarea>
		<?php if(isset($extraCurricularNirma) && $extraCurricularNirma!=""){ ?>
				<script>
				    document.getElementById("extraCurricularNirma").value = "<?php echo str_replace("\n", '\n', $extraCurricularNirma );  ?>";
				    document.getElementById("extraCurricularNirma").style.color = "";
				</script>
			      <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'extraCurricularNirma_error'></div></div>
		</div>
	</li>

	<li>
    	<label style="font-weight:normal">Achievements (If any): </label>
        <div class='float_L'>
        	<textarea  name='achievementsNirma' id='achievementsNirma'         tip="Briefly mention any achievements in you life that you would like to share. These could be acedemic as well as non-acedemic achievments. If you do not wish to state anything, just enter NA." style="width:613px" minlength='2' maxlength='1000' validate="validateStr" caption="achievements" ></textarea>
		<?php if(isset($achievementsNirma) && $achievementsNirma!=""){ ?>
				<script>
				    document.getElementById("achievementsNirma").value = "<?php echo str_replace("\n", '\n', $achievementsNirma );  ?>";
				    document.getElementById("achievementsNirma").style.color = "";
				</script>
			      <?php } ?>
		<div style='display:none'><div class='errorMsg' id= 'achievementsNirma_error'></div></div>
	</div>
	</li>
-->	<?php
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
			for($i=0;$i<count($workCompaniesExpFrom);$i++){
				$durationFrom = $workCompaniesExpFrom['_mul_'.$i];
	 		        $durationTo = trim($workCompaniesExpTill['_mul_'.$i])?$workCompaniesExpTill['_mul_'.$i]:'Till date';
				if($durationFrom) {
                                        $startDate = getStandardDate($durationFrom);
                                        $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
                                        $totalDuration = getTimeDifference($startDate,$endDate);
                                        $companyWorkExDuration['workExpNirma'.$i] = ($totalDuration['months']<0)?0:$totalDuration['months'];
					$total += $totalDuration['months'];
                                }
			}

		
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$workExpFieldName = 'workExpNirma'.$j;
					$workExpFieldValue = $companyWorkExDuration[$workExpFieldName];
					$j++;
					$totalWorkExp += $workExpFieldValue;

			?>
			<li style="display:none;">
				<input type='hidden' name='<?php echo $workExpFieldName; ?>' id='<?php echo $workExpFieldName; ?>' value='<?php echo $workExpFieldValue;?>'   />
			</li>
			
			<?php
				}
			}
			?>
			<li style="display:none;">

				<input type='hidden' name='totalExpNirma' id='totalExpNirma' value='<?php echo $totalWorkExp;?>'/>
			</li>
	<li>
	    	<h3 class="upperCase">General</h3>
		<div class="clearFix"></div>
	      <label style="font-weight:normal">Sources of information about Nirma: </label>
	      <div class='float_L'>
		      <input type='text' name='sourseOfInformationNirma' id='sourseOfInformationNirma'  validate="validateStr"   required="true"   caption="source of information"   minlength="1"   maxlength="250"     tip="Please enter where you heard about Institute of Management Nirma University. For example friends, advertisement, internet etc."   value='' class="textboxLarger" />
		      <?php if(isset($sourseOfInformationNirma) && $sourseOfInformationNirma!=""){ ?>
				      <script>
					  document.getElementById("sourseOfInformationNirma").value = "<?php echo str_replace("\n", '\n', $sourseOfInformationNirma );  ?>";
					  document.getElementById("sourseOfInformationNirma").style.color = "";
				      </script>
				    <?php } ?>
		      <div style='display:none'><div class='errorMsg' id= 'sourseOfInformationNirma_error'></div></div>
	      </div>
	</li>
	
	<?php if(is_array($gdpiLocations)): ?>
	<li>
		<label style="font-weight:normal">Preferred GD/PI location: </label>
		<div class='float_L'>
        <select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
	</li>
	<?php endif; ?>

	<li>
		<label style="font-weight:normal">Terms:</label>
		<div class='float_L' style="width:620px; color:#666666; font-style:italic">
		I <student_first_name> <middle_name> <last_name> have carefully read the instructions and agree to abide by the decision of the University regarding my selection to the programme. I certify that the information furnished in this application form is correct to the best of my knowledge and belief.
		
		<div class="spacer10 clearFix"></div>
       	<div >
        	<input type='checkbox' name='agreeToTermsNirma' id='agreeToTermsNirma' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
		I agree to the terms stated above

	      <?php if(isset($agreeToTermsNirma) && $agreeToTermsNirma!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsNirma"];
		    var countCheckBoxes = 1;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes.checked = false;

			      <?php $arr = explode(",",$agreeToTermsNirma);
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
		<div style='display:none'><div class='errorMsg' id= 'agreeToTermsNirma_error'></div></div>


		</div>
	</div>
	</li>
	<?php endif;?>
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

  </script>


  <script>
/*	function calculate10thAggregate(){
		  if($('class10totalMarksNirma') && $('class10totalMarksNirma').value!='' && $('class10maxMarksNirma') && $('class10maxMarksNirma').value!=''){
		      var avgVal = ( parseInt($('class10totalMarksNirma').value) * 100 ) / parseInt($('class10maxMarksNirma').value);
		      avgVal = avgVal.toFixed(2);
		      $('10thaggregate').innerHTML = avgVal;
		  }
	}

	function calculate12thAggregate(){
		  if($('class12totalMarksNirma') && $('class12totalMarksNirma').value!='' && $('class12maxMarksNirma') && $('class12maxMarksNirma').value!=''){
		      var avg = ( parseInt($('class12totalMarksNirma').value) * 100 ) / parseInt($('class12maxMarksNirma').value);
		      avgVal = avgVal.toFixed(2);
		      $('12thaggregate').innerHTML = avgVal;
		  }
	}
  calculate10thAggregate();
  calculate12thAggregate();
*/
  </script>
