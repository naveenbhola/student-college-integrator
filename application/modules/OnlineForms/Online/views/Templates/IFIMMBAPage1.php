<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Program Opted for</h3>
				<div class="additionalInfoLeftCol" style="width:100%">
				<label>Program Preference: </label>
				<div class='flLt' style="line-height:25px">
				<input type='checkbox' name='programIFIM[]' id='programIFIM0'   value='POST GRADUATE DIPLOMA IN MANAGEMENT (PGDM)'  onmouseover="showTipOnline('Select the program that you wish to apply for.',this);" onmouseout="hidetip();"  />&nbsp;<span  onmouseover="showTipOnline('Select the program that you wish to apply for.',this);" onmouseout="hidetip();" >POST GRADUATE DIPLOMA IN MANAGEMENT (PGDM)</span><br/>
				<input type='checkbox' name='programIFIM[]' id='programIFIM1'   value='POST GRADUATE DIPLOMA IN MANAGEMENT - FINANCE (PGDM-FINANCE)'     onmouseover="showTipOnline('Select the program that you wish to apply for.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('Select the program that you wish to apply for.',this);" onmouseout="hidetip();" >POST GRADUATE DIPLOMA IN MANAGEMENT - FINANCE (PGDM-FINANCE)</span>
				<?php if(isset($programIFIM) && $programIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["programIFIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$programIFIM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'programIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Category</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Category: </label>
				<div class='flLt'>
				<input type='radio' name='categoryIFIM' id='categoryIFIM0'   value='SELF - SPONSORED'  checked   onmouseover="showTipOnline('Please select this option if you are a self sponsored candidate.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('Please select this option if you are a self sponsored candidate.',this);" onmouseout="hidetip();" >Self - Sponsored</span>&nbsp;&nbsp;
				<input type='radio' name='categoryIFIM' id='categoryIFIM1'   value='COMPANY - SPONSORED'     onmouseover="showTipOnline('Please select this option if you are a company sponsored candidate.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('Please select this option if you are a company sponsored candidate.',this);" onmouseout="hidetip();" >Company - Sponsored</span>&nbsp;&nbsp;
				<?php if(isset($categoryIFIM) && $categoryIFIM!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["categoryIFIM"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $categoryIFIM;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Personal details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Blood Group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='bloodGroupIFIM' id='bloodGroupIFIM'     validate="validateStr" caption="blood group" minlength="1" maxlength="3"    tip="Enter your blood group. If you do not know your blood group, leave this field blank."   value=''   />
				<?php if(isset($bloodGroupIFIM) && $bloodGroupIFIM!=""){ ?>
				  <script>
				      document.getElementById("bloodGroupIFIM").value = "<?php echo str_replace("\n", '\n', $bloodGroupIFIM );  ?>";
				      document.getElementById("bloodGroupIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'bloodGroupIFIM_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif;?>

			<li>
				<h3 class="upperCase">Qualifying Examination</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Name of Entrance Test: </label>
				<div class='flLt'>
				<input onClick="showExam(this.value);" type='radio' name='entranceIFIM' id='entranceIFIM0'   value='CAT'  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="showExam(this.value);" type='radio' name='entranceIFIM' id='entranceIFIM1'   value='XAT'     onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="showExam(this.value);" type='radio' name='entranceIFIM' id='entranceIFIM2'   value='GMAT'     onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<input onClick="showExam(this.value);" type='radio' name='entranceIFIM' id='entranceIFIM3'   value='IIFT'     onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" >IIFT</span>&nbsp;&nbsp;
				<input onClick="showExam(this.value);" type='radio' name='entranceIFIM' id='entranceIFIM4'   value='MAT'     onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="showExam(this.value);" type='radio' name='entranceIFIM' id='entranceIFIM5'   value='ATMA'     onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the entrance examination you have appeared for and the scores of which you wish to apply through.',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<?php if(isset($entranceIFIM) && $entranceIFIM!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["entranceIFIM"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $entranceIFIM;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'entranceIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li style="display:none" id="catDetail">
				<div class='additionalInfoLeftCol'>
				<label>Reg. No. or Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"   caption="roll number"   minlength="2"   maxlength="50"     tip="Enter the roll number or registration number for the entrance exam selected."   value=''    allowNA = 'true' />
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
				<label>Percentile Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'  validate="validateFloat"   caption="score"   minlength="1"   maxlength="5"     tip="Enter the score you got in this test."   value=''    allowNA = 'true' />
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

			<li style="display:none" id="matDetail">
				<div class='additionalInfoLeftCol'>
				<label>Reg. No. or Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"    caption="roll number"   minlength="2"   maxlength="50"     tip="Enter the roll number or registration number for the entrance exam selected."   value=''    allowNA = 'true' />
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
				<label>Percentile Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"     caption="score"   minlength="1"   maxlength="5"     tip="Enter the score you got in this test."   value=''    allowNA = 'true' />
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

			<li style="display:none" id="gmatDetail">
				<div class='additionalInfoLeftCol'>
				<label>Reg. No. or Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"     caption="roll number"   minlength="2"   maxlength="50"     tip="Enter the roll number or registration number for the entrance exam selected."   value=''    allowNA = 'true' />
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
				<label>Percentile Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"     caption="score"   minlength="1"   maxlength="5"     tip="Enter the score you got in this test."   value=''    allowNA = 'true' />
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

			<li style="display:none" id="xatDetail">
				<div class='additionalInfoLeftCol'>
				<label>Reg. No. or Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRollNumberGAdditional' id='xatRollNumberGAdditional'    validate="validateStr"   caption="roll number"   minlength="1"   maxlength="20"     tip="Enter the roll number or registration number for the entrance exam selected."   value=''   />
				<?php if(isset($xatRollNumberGAdditional) && $xatRollNumberGAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberGAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberGAdditional );  ?>";
				      document.getElementById("xatRollNumberGAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberGAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Percentile Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'     validate="validateFloat"     caption="score"   minlength="1"   maxlength="5"    tip="Enter the score you got in this test."   value=''   />
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

			<li style="display:none" id="iiftDetail">
				<div class='additionalInfoLeftCol'>
				<label>Reg. No. or Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='iiftRollNumberAdditional' id='iiftRollNumberAdditional'    validate="validateStr"   caption="roll number"   minlength="1"   maxlength="20"     tip="Enter the roll number or registration number for the entrance exam selected."   value=''   />
				<?php if(isset($iiftRollNumberAdditional) && $iiftRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("iiftRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $iiftRollNumberAdditional );  ?>";
				      document.getElementById("iiftRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'iiftRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Percentile Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='iiftScoreAdditional' id='iiftScoreAdditional'     validate="validateFloat"     caption="score"   minlength="1"   maxlength="5"    tip="Enter the score you got in this test."   value=''   />
				<?php if(isset($iiftScoreAdditional) && $iiftScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("iiftScoreAdditional").value = "<?php echo str_replace("\n", '\n', $iiftScoreAdditional );  ?>";
				      document.getElementById("iiftScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'iiftScoreAdditional_error'></div></div>
				</div>
				</div>
			</li>
			

			<li style="display:none" id="atmaDetail">
				<div class='additionalInfoLeftCol'>
				<label>Reg. No. or Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'    validate="validateStr"   caption="roll number"   minlength="1"   maxlength="20"     tip="Enter the roll number or registration number for the entrance exam selected."   value=''   />
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
				<label>Percentile Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'     validate="validateFloat"     caption="score"   minlength="1"   maxlength="5"    tip="Enter the score you got in this test."   value=''   />
				<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
				      document.getElementById("atmaScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
				</div>
			</li>

		
			<li style="display:none" id="otherDetail">
				<div class='additionalInfoLeftCol'>
				<label>Year in which taken: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='yearEntranceIFIM' id='yearEntranceIFIM'  validate="validateInteger"   required="true"   caption="year"   minlength="4"   maxlength="4"     tip="Enter the year in which you took the entrance test."   value=''   />
				<?php if(isset($yearEntranceIFIM) && $yearEntranceIFIM!=""){ ?>
				  <script>
				      document.getElementById("yearEntranceIFIM").value = "<?php echo str_replace("\n", '\n', $yearEntranceIFIM );  ?>";
				      document.getElementById("yearEntranceIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearEntranceIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select name='numberOfAttempsIFIM' id='numberOfAttempsIFIM'  style="width: 75px;"  tip="Enter the total number of attempts that you took."     required="true"    onmouseover="showTipOnline('Enter the total number of attempts that you took.',this);" onmouseout="hidetip();" ><option value='1' selected>1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option></select>
				<?php if(isset($numberOfAttempsIFIM) && $numberOfAttempsIFIM!=""){ ?>
			      <script>
				  var selObj = document.getElementById("numberOfAttempsIFIM"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $numberOfAttempsIFIM;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'numberOfAttempsIFIM_error'></div></div>
				</div>
				</div>
			</li>

	<?php if($action != 'updateScore'):?>

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
				<h3 class="upperCase">Education (additional information about your education)</h3>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> duration (in years): </label>
				<div class='fieldBoxLarge'>
				<select name='gradDurationIFIM' id='gradDurationIFIM' onchange="changeYears(this);" style="width: 75px;"  tip="Select the duration in years for your graduation course. If it was a 3 years course, select 3 otherwise select 4"     required="true"    onmouseover="showTipOnline('Select the duration in years for your graduation course. If it was a 3 years course, select 3 otherwise select 4',this);" onmouseout="hidetip();" ><option value='3' selected>3</option><option value='4' >4</option></select>
				<?php if(isset($gradDurationIFIM) && $gradDurationIFIM!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradDurationIFIM"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradDurationIFIM;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradDurationIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> First year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradFirstPassingIFIM' id='gradFirstPassingIFIM'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for your first year of graduation"   value=''   />
				<?php if(isset($gradFirstPassingIFIM) && $gradFirstPassingIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradFirstPassingIFIM").value = "<?php echo str_replace("\n", '\n', $gradFirstPassingIFIM );  ?>";
				      document.getElementById("gradFirstPassingIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradFirstPassingIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> First year percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradFirstPercentageIFIM' id='gradFirstPercentageIFIM'  validate="validateFloat"   required="true"   caption="percentage"   minlength="1"   maxlength="6"     tip="Enter your first year percentage/CGPA or class whichever is applicable to your course."   value=''   />
				<?php if(isset($gradFirstPercentageIFIM) && $gradFirstPercentageIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradFirstPercentageIFIM").value = "<?php echo str_replace("\n", '\n', $gradFirstPercentageIFIM );  ?>";
				      document.getElementById("gradFirstPercentageIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradFirstPercentageIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Second year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradSecondPassingIFIM' id='gradSecondPassingIFIM'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for your second year of graduation"   value=''   />
				<?php if(isset($gradSecondPassingIFIM) && $gradSecondPassingIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradSecondPassingIFIM").value = "<?php echo str_replace("\n", '\n', $gradSecondPassingIFIM );  ?>";
				      document.getElementById("gradSecondPassingIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSecondPassingIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Second year percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradSecondPercentageIFIM' id='gradSecondPercentageIFIM'  validate="validateFloat"   required="true"   caption="percentage"   minlength="1"   maxlength="6"     tip="Enter your second year percentage/CGPA or class whichever is applicable to your course."   value=''   />
				<?php if(isset($gradSecondPercentageIFIM) && $gradSecondPercentageIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradSecondPercentageIFIM").value = "<?php echo str_replace("\n", '\n', $gradSecondPercentageIFIM );  ?>";
				      document.getElementById("gradSecondPercentageIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSecondPercentageIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Third year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradThirdPassingIFIM' id='gradThirdPassingIFIM'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for your third year of graduation"   value=''   />
				<?php if(isset($gradThirdPassingIFIM) && $gradThirdPassingIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradThirdPassingIFIM").value = "<?php echo str_replace("\n", '\n', $gradThirdPassingIFIM );  ?>";
				      document.getElementById("gradThirdPassingIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradThirdPassingIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Third year percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradThirdPercentageIFIM' id='gradThirdPercentageIFIM'  validate="validateFloat"   required="true"   caption="percentage"   minlength="1"   maxlength="6"     tip="Enter your third year percentage/CGPA or class whichever is applicable to your course."   value=''   />
				<?php if(isset($gradThirdPercentageIFIM) && $gradThirdPercentageIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradThirdPercentageIFIM").value = "<?php echo str_replace("\n", '\n', $gradThirdPercentageIFIM );  ?>";
				      document.getElementById("gradThirdPercentageIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradThirdPercentageIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li id="fourthYearSection" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Fourth year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradFourthPassingIFIM' id='gradFourthPassingIFIM'  validate="validateInteger"   required="true"   caption="year of passing"   minlength="4"   maxlength="4"     tip="Enter the year of passing for your fourth year of graduation."   value=''  />
				<?php if(isset($gradFourthPassingIFIM) && $gradFourthPassingIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradFourthPassingIFIM").value = "<?php echo str_replace("\n", '\n', $gradFourthPassingIFIM );  ?>";
				      document.getElementById("gradFourthPassingIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradFourthPassingIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Fourth year percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradFourthPercentageIFIM' id='gradFourthPercentageIFIM'  validate="validateFloat"   required="true"   caption="percentage"   minlength="1"   maxlength="6"     tip="Enter your fourth year percentage/CGPA or class whichever is applicable to your course."   value='' />
				<?php if(isset($gradFourthPercentageIFIM) && $gradFourthPercentageIFIM!=""){ ?>
				  <script>
				      document.getElementById("gradFourthPercentageIFIM").value = "<?php echo str_replace("\n", '\n', $gradFourthPercentageIFIM );  ?>";
				      document.getElementById("gradFourthPercentageIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradFourthPercentageIFIM_error'></div></div>
				</div>
				</div>
			</li>


			<?php
			if(count($otherCourses)>0) {
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					$pgCheckVal = $$pgCheck;
			?>

			<li>
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

			<?php
				}
			}
			?>

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
					$grossSalaryFieldName = 'annualSalaryIFIM'.$workCompanyKey;
					$grossSalaryFieldValue = $$grossSalaryFieldName;
					$addressFieldName = 'companyAddressIFIM'.$workCompanyKey;
					$addressFieldValue = $$addressFieldName;
					$j++;

			?>

			<li>
				<?php if($j==1){ ?><h3 class="upperCase">Work experience additional details</h3><?php } ?>
				<div class='additionalInfoLeftCol'>
				<label>Annual Salary at <?php echo $workCompany; ?>: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='<?php echo $grossSalaryFieldName; ?>' id='<?php echo $grossSalaryFieldName; ?>' tip="Enter your annual salary at this company, in Rupees."  validate="validateFloat"  caption="salary"   minlength="2"   maxlength="10" value=''  />
						<?php if(isset($grossSalaryFieldValue) && $grossSalaryFieldValue!=""){ ?>
								<script>
									document.getElementById("<?php echo $grossSalaryFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $grossSalaryFieldValue );  ?>";
									document.getElementById("<?php echo $grossSalaryFieldName; ?>").style.color = "";
								</script>
								  <?php } ?>
						<div style='display:none'><div class='errorMsg' id= '<?php echo $grossSalaryFieldName; ?>_error'></div></div>
					</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Address of <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge'>
				<textarea name='<?php echo $addressFieldName; ?>' id='<?php echo $addressFieldName; ?>' tip="Enter the complete address of the company" ></textarea>
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



			<li>
				<h3 class="upperCase">Others</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Achievements(Academic / Sports / Scholarships / Awards / Banks etc.): </label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='acheivements1IFIM' id='acheivements1IFIM'  tip="Enter your acedemic or sports achievements. If you have received scholarships, awards you can mention that as well."  style="width:600px; height:74px; padding:5px" validate="validateStr" caption="Acedemic or sports achievements" minlenght="1" maxlength="500" ></textarea>
				<?php if(isset($acheivements1IFIM) && $acheivements1IFIM!=""){ ?>
				  <script>
				      document.getElementById("acheivements1IFIM").value = "<?php echo str_replace("\n", '\n', $acheivements1IFIM );  ?>";
				      document.getElementById("acheivements1IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'acheivements1IFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label></label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='acheivements2IFIM' id='acheivements2IFIM'  tip="Enter your acedemic or sports achievements. If you have received scholarships, awards you can mention that as well."  style="width:600px; height:74px; padding:5px" validate="validateStr" caption="Acedemic or sports achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($acheivements2IFIM) && $acheivements2IFIM!=""){ ?>
				  <script>
				      document.getElementById("acheivements2IFIM").value = "<?php echo str_replace("\n", '\n', $acheivements2IFIM );  ?>";
				      document.getElementById("acheivements2IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'acheivements2IFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label></label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='acheivements3IFIM' id='acheivements3IFIM'  tip="Enter your acedemic or sports achievements. If you have received scholarships, awards you can mention that as well." style="width:600px; height:74px; padding:5px" validate="validateStr" caption="Acedemic or sports achievements" minlenght="1" maxlength="500"   ></textarea>
				<?php if(isset($acheivements3IFIM) && $acheivements3IFIM!=""){ ?>
				  <script>
				      document.getElementById("acheivements3IFIM").value = "<?php echo str_replace("\n", '\n', $acheivements3IFIM );  ?>";
				      document.getElementById("acheivements3IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'acheivements3IFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Extra-curricular / Co-curricular Achievements: </label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='extra1IFIM' id='extra1IFIM' tip="Enter your extra curricular/co-curricular achievements here, if any" style="width:600px; height:74px; padding:5px" validate="validateStr" caption="Extra curricular/co-curricular achievements" minlenght="1" maxlength="500" ></textarea>
				<?php if(isset($extra1IFIM) && $extra1IFIM!=""){ ?>
				  <script>
				      document.getElementById("extra1IFIM").value = "<?php echo str_replace("\n", '\n', $extra1IFIM );  ?>";
				      document.getElementById("extra1IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'extra1IFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>&nbsp;</label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='extra2IFIM' id='extra2IFIM' tip="Enter your extra curricular/co-curricular achievements here, if any"  style="width:600px; height:74px; padding:5px" validate="validateStr" caption="Extra curricular/co-curricular achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($extra2IFIM) && $extra2IFIM!=""){ ?>
				  <script>
				      document.getElementById("extra2IFIM").value = "<?php echo str_replace("\n", '\n', $extra2IFIM );  ?>";
				      document.getElementById("extra2IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'extra2IFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>&nbsp;</label>
				<div class='fieldBoxLarge' style="width:620px">
				<textarea name='extra3IFIM' id='extra3IFIM'  tip="Enter your extra curricular/co-curricular achievements here, if any"  style="width:600px; height:74px; padding:5px" validate="validateStr" caption="Extra curricular/co-curricular achievements" minlenght="1" maxlength="500"  ></textarea>
				<?php if(isset($extra3IFIM) && $extra3IFIM!=""){ ?>
				  <script>
				      document.getElementById("extra3IFIM").value = "<?php echo str_replace("\n", '\n', $extra3IFIM );  ?>";
				      document.getElementById("extra3IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'extra3IFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Language Proficiency</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Language: </label>
				<div class='fieldBoxMed'>
				<input type='text' name='language1IFIM' id='language1IFIM'         tip="Enter the language that you have proficiency in."   value=''   />
				<?php if(isset($language1IFIM) && $language1IFIM!=""){ ?>
				  <script>
				      document.getElementById("language1IFIM").value = "<?php echo str_replace("\n", '\n', $language1IFIM );  ?>";
				      document.getElementById("language1IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'language1IFIM_error'></div></div>
				</div>
				
            	<div class='fieldBoxLarge' style="width:360px">
				<input type='checkbox' name='language1checksIFIM[]' id='language1checksIFIM0'   value='Can read'  onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();"  />&nbsp;<span  onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" >Can read</span>&nbsp;&nbsp;
				<input type='checkbox' name='language1checksIFIM[]' id='language1checksIFIM1'   value='Can write'     onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" >Can write</span>&nbsp;&nbsp;
				<input type='checkbox' name='language1checksIFIM[]' id='language1checksIFIM2'   value='Can speak'     onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" >Can speak</span>&nbsp;&nbsp;
				<?php if(isset($language1checksIFIM) && $language1checksIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["language1checksIFIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$language1checksIFIM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'language1checksIFIM_error'></div></div>
				</div>    
                </div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Language: </label>
				<div class='fieldBoxMed'>
				<input type='text' name='language2IFIM' id='language2IFIM'         tip="Enter the language that you have proficiency in."   value=''   />
				<?php if(isset($language2IFIM) && $language2IFIM!=""){ ?>
				  <script>
				      document.getElementById("language2IFIM").value = "<?php echo str_replace("\n", '\n', $language2IFIM );  ?>";
				      document.getElementById("language2IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'language2IFIM_error'></div></div>
				</div>
                
                <div class='fieldBoxLarge' style="width:360px">
				<input type='checkbox' name='language2checksIFIM[]' id='language2checksIFIM0'   value='Can read'     onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" >Can read</span>&nbsp;&nbsp;
				<input type='checkbox' name='language2checksIFIM[]' id='language2checksIFIM1'   value='Can write'     onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" >Can write</span>&nbsp;&nbsp;
				<input type='checkbox' name='language2checksIFIM[]' id='language2checksIFIM2'   value='Can speak'     onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" >Can speak</span>&nbsp;&nbsp;
				<?php if(isset($language2checksIFIM) && $language2checksIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["language2checksIFIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$language2checksIFIM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'language2checksIFIM_error'></div></div>
				</div>
				</div>

				
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Language: </label>
				<div class='fieldBoxMed'>
				<input type='text' name='language3IFIM' id='language3IFIM'         tip="Enter the language that you have proficiency in."   value=''   />
				<?php if(isset($language3IFIM) && $language3IFIM!=""){ ?>
				  <script>
				      document.getElementById("language3IFIM").value = "<?php echo str_replace("\n", '\n', $language3IFIM );  ?>";
				      document.getElementById("language3IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'language3IFIM_error'></div></div>
				</div>
				
                <div class='fieldBoxLarge' style="width:360px">
				<input type='checkbox' name='language3checksIFIM[]' id='language3checksIFIM0'   value='Can read'     onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" >Can read</span>&nbsp;&nbsp;
				<input type='checkbox' name='language3checksIFIM[]' id='language3checksIFIM1'   value='Can write'     onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" >Can write</span>&nbsp;&nbsp;
				<input type='checkbox' name='language3checksIFIM[]' id='language3checksIFIM2'   value='Can speak'     onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" >Can speak</span>&nbsp;&nbsp;
				<?php if(isset($language3checksIFIM) && $language3checksIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["language3checksIFIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$language3checksIFIM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'language3checksIFIM_error'></div></div>
				</div>
                </div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Language: </label>
				<div class='fieldBoxMed'>
				<input type='text' name='language4IFIM' id='language4IFIM'         tip="Enter the language that you have proficiency in."   value=''   />
				<?php if(isset($language4IFIM) && $language4IFIM!=""){ ?>
				  <script>
				      document.getElementById("language4IFIM").value = "<?php echo str_replace("\n", '\n', $language4IFIM );  ?>";
				      document.getElementById("language4IFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'language4IFIM_error'></div></div>
				</div>
				<div class='fieldBoxLarge' style="width:360px">
				<input type='checkbox' name='language4checksIFIM[]' id='language4checksIFIM0'   value='Can read'    onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can read this language, select this option.',this);" onmouseout="hidetip();" >Can read</span>&nbsp;&nbsp;
				<input type='checkbox' name='language4checksIFIM[]' id='language4checksIFIM1'   value='Can write'     onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can write this language, select this option.',this);" onmouseout="hidetip();" >Can write</span>&nbsp;&nbsp;
				<input type='checkbox' name='language4checksIFIM[]' id='language4checksIFIM2'   value='Can speak'     onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" />&nbsp;<span  onmouseover="showTipOnline('If you can speak this language, select this option.',this);" onmouseout="hidetip();" >Can speak</span>&nbsp;&nbsp;
				<?php if(isset($language4checksIFIM) && $language4checksIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["language4checksIFIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$language4checksIFIM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'language4checksIFIM_error'></div></div>
				</div>
                </div>
				
			</li>

			<li>
				<h3 class="upperCase">Additional Family Details</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Annual Income: </label>
				<div class='flLt'>
				<input type='radio' name='annualIncomeIFIM' id='annualIncomeIFIM0' checked  value='Rs. 2,00,000 p.a'     onmouseover="showTipOnline('Select you annual family income from one of these options.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select you annual family income from one of these options.',this);" onmouseout="hidetip();" >&lt; Rs. 2,00,000 p.a</span>&nbsp;&nbsp;
				<input type='radio' name='annualIncomeIFIM' id='annualIncomeIFIM1'   value='Rs. 2,00,000 to Rs. 4,00,000 p.a'     onmouseover="showTipOnline('Select you annual family income from one of these options.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select you annual family income from one of these options.',this);" onmouseout="hidetip();" >Rs. 2,00,000 to Rs. 4,00,000 p.a</span>&nbsp;&nbsp;
				<input type='radio' name='annualIncomeIFIM' id='annualIncomeIFIM2'   value='Rs. 4,00,000 p.a.'     onmouseover="showTipOnline('Select you annual family income from one of these options.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select you annual family income from one of these options.',this);" onmouseout="hidetip();" >&gt; Rs. 4,00,000 p.a.</span>&nbsp;&nbsp;
				<?php if(isset($annualIncomeIFIM) && $annualIncomeIFIM!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["annualIncomeIFIM"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $annualIncomeIFIM;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'annualIncomeIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Mobile Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherMobileIFIM' id='fatherMobileIFIM'  validate="validateMobileInteger"   required="true"   caption="fathers mobile"   minlength="10"   maxlength="10"     tip="Enter your father's mobile number. If your father doesn't own a mobile, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($fatherMobileIFIM) && $fatherMobileIFIM!=""){ ?>
				  <script>
				      document.getElementById("fatherMobileIFIM").value = "<?php echo str_replace("\n", '\n', $fatherMobileIFIM );  ?>";
				      document.getElementById("fatherMobileIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherMobileIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mothers Mobile Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherMobileIFIM' id='motherMobileIFIM'  validate="validateMobileInteger"   required="true"   caption="mothers mobile"   minlength="10"   maxlength="10"     tip="Enter your Mothers mobile number. If your mother doesn't own a mobile, enter <b>NA</b>."   value='' allowNA="true"  />
				<?php if(isset($motherMobileIFIM) && $motherMobileIFIM!=""){ ?>
				  <script>
				      document.getElementById("motherMobileIFIM").value = "<?php echo str_replace("\n", '\n', $motherMobileIFIM );  ?>";
				      document.getElementById("motherMobileIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherMobileIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<?php if($genderIFIM=='FEMALE' && $maritalStatusIFIM=='MARRIED'){ ?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Husband's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandNameIFIM' id='husbandNameIFIM'         tip="Enter your husband's name."   value=''   />
				<?php if(isset($husbandNameIFIM) && $husbandNameIFIM!=""){ ?>
				  <script>
				      document.getElementById("husbandNameIFIM").value = "<?php echo str_replace("\n", '\n', $husbandNameIFIM );  ?>";
				      document.getElementById("husbandNameIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandNameIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Husband's Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandOccupationIFIM' id='husbandOccupationIFIM'         tip="Type in the occupation of your Husband here or nature of his work, such as Engineer or Business Owner. If this is not applicable in your case, just enter <b>NA</b>."   value='' allowNA="true"  />
				<?php if(isset($husbandOccupationIFIM) && $husbandOccupationIFIM!=""){ ?>
				  <script>
				      document.getElementById("husbandOccupationIFIM").value = "<?php echo str_replace("\n", '\n', $husbandOccupationIFIM );  ?>";
				      document.getElementById("husbandOccupationIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandOccupationIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Husband's Mobile Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandMobileIFIM' id='husbandMobileIFIM'         tip="Enter your husband's mobile number. If your husband doesn't own a mobile, enter <b>NA</b>."   value='' allowNA="true"  />
				<?php if(isset($husbandMobileIFIM) && $husbandMobileIFIM!=""){ ?>
				  <script>
				      document.getElementById("husbandMobileIFIM").value = "<?php echo str_replace("\n", '\n', $husbandMobileIFIM );  ?>";
				      document.getElementById("husbandMobileIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandMobileIFIM_error'></div></div>
				</div>
				</div>
			</li>
			<?php } ?>

			<li>
				<h3 class="upperCase">Pan Card Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's PAN Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherPANIFIM' id='fatherPANIFIM'  validate="validateStr"   required="true"   caption="PAN number"   minlength="6"   maxlength="13"     tip="Please enter you father's PAN card number. If he doesn't have a PAN card, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($fatherPANIFIM) && $fatherPANIFIM!=""){ ?>
				  <script>
				      document.getElementById("fatherPANIFIM").value = "<?php echo str_replace("\n", '\n', $fatherPANIFIM );  ?>";
				      document.getElementById("fatherPANIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherPANIFIM_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's PAN Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherPANIFIM' id='motherPANIFIM'  validate="validateStr"   required="true"   caption="PAN number"   minlength="6"   maxlength="13"     tip="Please enter you mother's PAN card number. If she doesn't have a PAN card, enter <b>NA</b>."   value='' allowNA="true"  />
				<?php if(isset($motherPANIFIM) && $motherPANIFIM!=""){ ?>
				  <script>
				      document.getElementById("motherPANIFIM").value = "<?php echo str_replace("\n", '\n', $motherPANIFIM );  ?>";
				      document.getElementById("motherPANIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherPANIFIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Your PAN Card number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='studentPANIFIM' id='studentPANIFIM'  validate="validateStr"   required="true"   caption="PAN number"   minlength="6"   maxlength="13"     tip="Please enter you Your PAN card number. If you don't have a PAN card, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($studentPANIFIM) && $studentPANIFIM!=""){ ?>
				  <script>
				      document.getElementById("studentPANIFIM").value = "<?php echo str_replace("\n", '\n', $studentPANIFIM );  ?>";
				      document.getElementById("studentPANIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'studentPANIFIM_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<h3 class="upperCase">I got to know about IFIM through</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>I got to know about IFIM through: </label>
				<div class='fieldBoxLarge' style="width:615px;">
				<input type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM0'   value='News Paper' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();"/>&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" >News Paper</span>
                <div class="spacer5 clearFix"></div>
                <div style="width:100%;">
				<div class="flLt"><input onClick="refStudent(this);" type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM1' value='Referred to by IFIM student' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" />&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();">Referred to by IFIM student</span></div>
                
                <div class='' style="width:410px; float:left">
				<label style="width:170px">Name of the student: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='refStudentNameIFIM' id='refStudentNameIFIM'   disabled    validate="validateStr" caption="name"   minlength="2"   maxlength="25"   value='' class="textboxLarge" />
				<?php if(isset($refStudentNameIFIM) && $refStudentNameIFIM!=""){ ?>
				  <script>
                                      document.getElementById("refStudentNameIFIM").disabled = false;
				      document.getElementById("refStudentNameIFIM").value = "<?php echo str_replace("\n", '\n', $refStudentNameIFIM );  ?>";
				      document.getElementById("refStudentNameIFIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'refStudentNameIFIM_error'></div></div>
				</div>
				<div class="spacer5 clearFix"></div>
                <label style="width:170px">Batch: </label>
				<div class='fieldBoxSmall' style="width:220px">
				<input type='text' name='refStudentBatchIFIM' id='refStudentBatchIFIM'    disabled   validate="validateStr" caption="Batch"   minlength="2"   maxlength="8"   value=''   />
				<?php if(isset($refStudentBatchIFIM) && $refStudentBatchIFIM!=""){ ?>
				  <script>
                                      document.getElementById("refStudentBatchIFIM").disabled = false;
				      document.getElementById("refStudentBatchIFIM").value = "<?php echo str_replace("\n", '\n', $refStudentBatchIFIM );  ?>";
				      document.getElementById("refStudentBatchIFIM").style.color = "";
                                      document.getElementById("knowAboutIFIM1").checked = true;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'refStudentBatchIFIM_error'></div></div>
				</div>
                </div>
                
                
                </div>
                <div class="spacer5 clearFix"></div>
				<input  type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM2'   value='Education fair' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" />&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" >Education fair</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM3'   value='Radio' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" />&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();">Radio</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM4'   value='MAT Bulletin' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" />&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();">MAT Bulletin</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM5'   value='ATMA Bulletin' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();"/>&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();">ATMA Bulletin</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM6'   value='XAT advertisement' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();"/>&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();">XAT advertisement</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM7'   value='CAT advertisement' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" />&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();">CAT advertisement</span>
                <div class="spacer5 clearFix"></div>
                <div style="width:100%">
				<div class="flLt">
                <input onClick="refStaff(this); " type='checkbox' name='knowAboutIFIM[]' id='knowAboutIFIM8'   value='Faculty/Staff of IFIM' onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();"/>&nbsp;<span onmouseover="showTipOnline('Please select a source where you first got to know about IFIM.',this);" onmouseout="hidetip();" >Faculty/Staff of IFIM</span></div>
                <div class='' style="width:460px; float:left">
                <label style="width:217px">Name of the Faculty/Staff: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='refStaffNameIFIM' id='refStaffNameIFIM'      disabled  validate="validateStr" caption="name"   minlength="2"   maxlength="25"  value=''   />
				<?php if(isset($refStaffNameIFIM) && $refStaffNameIFIM!=""){ ?>
				  <script>
                                      document.getElementById("refStaffNameIFIM").disabled = false;
				      document.getElementById("refStaffNameIFIM").value = "<?php echo str_replace("\n", '\n', $refStaffNameIFIM );  ?>";
				      document.getElementById("refStaffNameIFIM").style.color = "";
                                      document.getElementById("knowAboutIFIM8").checked = true;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'refStaffNameIFIM_error'></div></div>
				</div>
				</div>
                
                </div>
                
                
				<?php if(isset($knowAboutIFIM) && $knowAboutIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["knowAboutIFIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$knowAboutIFIM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'knowAboutIFIM_error'></div></div>
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
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				I hereby certify that the information furnished in the Application Form is complete, accurate and true. I have carefully read the contents of the Brochure. If admitted, I agree to abide by the rules and regulations of IFIM Business School as may be in force from time to time. I understand that any information furnished falsely and/or a misrepresentation is a sufficient ground for summarily canceling my admission and/or will result in the expulsion from IFIM Business School.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsIFIM' id='agreeToTermsIFIM' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsIFIM) && $agreeToTermsIFIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsIFIM"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsIFIM);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsIFIM_error'></div></div>


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

  function showExam(examVal){
	hideAllExams();
	if(examVal == 'CAT'){
		$('catDetail').style.display = '';
	}
	else if(examVal == 'MAT'){
		$('matDetail').style.display = '';
	}
	else if(examVal == 'GMAT'){
		$('gmatDetail').style.display = '';
	}
	else{
		if(examVal == 'XAT'){
			$('xatDetail').style.display = '';
		}
		else if(examVal == 'ATMA'){
			$('atmaDetail').style.display = '';
		}
		else{
			$('iiftDetail').style.display = '';
		}
			
		//Now, we will prefill these values only if the user is filling the form for first time
		if(<?php if(isset($programIFIM) && $programIFIM!="") echo 'false'; else echo 'true'; ?>){
			if(examVal == 'XAT'){
				$('xatRollNumberGAdditional').value = '<?php echo $xatRollNumberGAdditional;?>';
				$('xatScoreAdditional').value = '<?php echo $xatScoreAdditional;?>';
			}
			else if(examVal == 'ATMA'){
				$('atmaRollNumberAdditional').value = '<?php echo $atmaRollNumberAdditional;?>';
				$('atmaScoreAdditional').value = '<?php echo $atmaScoreAdditional;?>';
			}
			else if(examVal == 'IIFT'){	
				$('iiftRollNumberAdditional').value = '<?php echo $iiftRollNumberAdditional;?>';
				$('iiftScoreAdditional').value = '<?php echo $iiftScoreAdditional;?>';
			}
		}
	}
	$('otherDetail').style.display = '';
	applyValidationChecks(examVal);
  }

  function hideAllExams(){
	$('catDetail').style.display = 'none';
	$('matDetail').style.display = 'none';
	$('gmatDetail').style.display = 'none';
	$('xatDetail').style.display = 'none';
	$('atmaDetail').style.display = 'none';
	$('iiftDetail').style.display = 'none';
	$('otherDetail').style.display = 'none';
  }

  function applyValidationChecks(examVal){
	clearValidationsCheck();
	if(examVal == 'CAT'){
		$('catRollNumberAdditional').setAttribute('required','true');
		$('catScoreAdditional').setAttribute('required','true');
	}
	else if(examVal == 'MAT'){
		$('matRollNumberAdditional').setAttribute('required','true');
		$('matScoreAdditional').setAttribute('required','true');
	}
	else if(examVal == 'GMAT'){
		$('gmatRollNumberAdditional').setAttribute('required','true');
		$('gmatScoreAdditional').setAttribute('required','true');
	}else if(examVal == 'ATMA'){
		$('atmaRollNumberAdditional').setAttribute('required','true');
		$('atmaScoreAdditional').setAttribute('required','true');
	}
	else if(examVal == 'XAT'){
		$('xatRollNumberGAdditional').setAttribute('required','true');
		$('xatScoreAdditional').setAttribute('required','true');
	}
	else{
		$('iiftRollNumberAdditional').setAttribute('required','true');
		$('iiftScoreAdditional').setAttribute('required','true');
	}
	$('yearEntranceIFIM').setAttribute('required','true');
	$('numberOfAttempsIFIM').setAttribute('required','true');
  }

  function clearValidationsCheck(){

	  var elementsArr = new Array('yearEntranceIFIM','numberOfAttempsIFIM','catRollNumberAdditional','catScoreAdditional','matRollNumberAdditional','matScoreAdditional','gmatRollNumberAdditional','gmatScoreAdditional','atmaRollNumberAdditional','atmaScoreAdditional','xatRollNumberGAdditional','xatScoreAdditional','iiftRollNumberAdditional','iiftScoreAdditional');
		for(i=0;i<elementsArr.length;i++){
		
		$(elementsArr[i]).removeAttribute('required');
		$(elementsArr[i]+'_error').innerHTML = '';
		$(elementsArr[i]+'_error').parentNode.style.display = 'none';
	  }
  }

  if('<?php echo $entranceIFIM;?>'=='CAT' || '<?php echo $entranceIFIM;?>'=='XAT' || '<?php echo $entranceIFIM;?>'=='MAT' || '<?php echo $entranceIFIM;?>'=='GMAT' || '<?php echo $entranceIFIM;?>'=='IIFT' || '<?php echo $entranceIFIM;?>'=='ATMA')
      showExam("<?php echo $entranceIFIM;?>");

  function refStaff(objId){
      if(objId.checked==true){
	  $('refStaffNameIFIM').disabled = false;
	  $('refStaffNameIFIM').setAttribute('required','true');
      }
      else{
	  $('refStaffNameIFIM').value = '';
	  $('refStaffNameIFIM').disabled = true;
	  $('refStaffNameIFIM').removeAttribute('required');
	  $('refStaffNameIFIM_error').innerHTML = '';
	  $('refStaffNameIFIM_error').parentNode.style.display = 'none';
      }
  }

  function refStudent(objId){
      if(objId.checked==true){
	  $('refStudentNameIFIM').disabled = false;
	  $('refStudentNameIFIM').setAttribute('required','true');

	  $('refStudentBatchIFIM').disabled = false;
	  $('refStudentBatchIFIM').setAttribute('required','true');
      }
      else{
	  $('refStudentNameIFIM').value = '';
	  $('refStudentNameIFIM').disabled = true;
	  $('refStudentNameIFIM').removeAttribute('required');
	  $('refStudentNameIFIM_error').innerHTML = '';
	  $('refStudentNameIFIM_error').parentNode.style.display = 'none';

	  $('refStudentBatchIFIM').value = '';
	  $('refStudentBatchIFIM').disabled = true;
	  $('refStudentBatchIFIM').removeAttribute('required');
	  $('refStudentBatchIFIM_error').innerHTML = '';
	  $('refStudentBatchIFIM_error').parentNode.style.display = 'none';
      }
  }

  function changeYears(obj){
      if(obj){
      if(obj.value == '4'){
	  $('fourthYearSection').style.display = '';

	  $('gradFourthPassingIFIM').setAttribute('required','true');
	  $('gradFourthPassingIFIM_error').innerHTML = '';
	  $('gradFourthPassingIFIM_error').parentNode.style.display = 'none';

	  $('gradFourthPercentageIFIM').setAttribute('required','true');
	  $('gradFourthPercentageIFIM_error').innerHTML = '';
	  $('gradFourthPercentageIFIM_error').parentNode.style.display = 'none';
      }
      else if(obj.value == '3'){
	  $('fourthYearSection').style.display = 'none';

	  $('gradFourthPassingIFIM').removeAttribute('required');
	  $('gradFourthPassingIFIM_error').innerHTML = '';
	  $('gradFourthPassingIFIM_error').parentNode.style.display = 'none';

	  $('gradFourthPercentageIFIM').removeAttribute('required');
	  $('gradFourthPercentageIFIM_error').innerHTML = '';
	  $('gradFourthPercentageIFIM_error').parentNode.style.display = 'none';
      }
      }
  }

  changeYears($('gradDurationIFIM'));
  </script>
