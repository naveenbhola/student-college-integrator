<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"DateOfExaminationAdditional",key+"ScoreAdditional");
	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+'1').style.display = '';
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
				<h3 class="upperCase">Academic Record</h3>
				<div class='additionalInfoLeftCol'>
				<label>10th Medium of Instruction: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10thmediumOfInstructionCBS' id='class10thmediumOfInstructionCBS'  validate="validateStr"   required="true"   caption="Medium"   minlength="1"   maxlength="100"     tip="Please mention your medium of Instruction."   value=''   />
				<?php if(isset($class10thmediumOfInstructionCBS) && $class10thmediumOfInstructionCBS!=""){ ?>
				  <script>
				      document.getElementById("class10thmediumOfInstructionCBS").value = "<?php echo str_replace("\n", '\n', $class10thmediumOfInstructionCBS );  ?>";
				      document.getElementById("class10thmediumOfInstructionCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10thmediumOfInstructionCBS_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>12th Medium of Instruction: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12thmediumOfInstructionCBS' id='class12thmediumOfInstructionCBS'  validate="validateStr"   required="true"   caption="Medium"   minlength="1"   maxlength="100"     tip="Please mention your medium of Instruction."   value=''   />
				<?php if(isset($class12thmediumOfInstructionCBS) && $class12thmediumOfInstructionCBS!=""){ ?>
				  <script>
				      document.getElementById("class12thmediumOfInstructionCBS").value = "<?php echo str_replace("\n", '\n', $class12thmediumOfInstructionCBS );  ?>";
				      document.getElementById("class12thmediumOfInstructionCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12thmediumOfInstructionCBS_error'></div></div>
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
				<label><?php echo $graduationCourseName;?> Medium of Instruction: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradmediumOfInstructionCBS' id='gradmediumOfInstructionCBS'  validate="validateStr"   required="true"   caption="Medium"   minlength="1"   maxlength="100"     tip="Please mention your medium of Instruction."   value=''   />
				<?php if(isset($gradmediumOfInstructionCBS) && $gradmediumOfInstructionCBS!=""){ ?>
				  <script>
				      document.getElementById("gradmediumOfInstructionCBS").value = "<?php echo str_replace("\n", '\n', $gradmediumOfInstructionCBS );  ?>";
				      document.getElementById("gradmediumOfInstructionCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradmediumOfInstructionCBS_error'></div></div>
				</div>
				</div>

			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$medium = 'otherCourseMedium_mul_'.$otherCourseId;
					$mediumVal = $$medium;
					$i++;
		
					if($class == "additionalInfoRightCol"){
						echo "</li><li>";
						$class = "additionalInfoLeftCol";
					}else{
						$class = "additionalInfoRightCol";
					}
			?>
				<div class='<?=$class?>'>
				<label><?php echo $otherCourseName;?> Medium of Instruction: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $medium; ?>' id='<?php echo $medium; ?>'  validate="validateStr"   required="true"   caption="Medium"   minlength="2"   maxlength="100"     tip="Please mention your medium of Instruction."   value='' />
				<?php if(isset($mediumVal) && $mediumVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $medium; ?>").value = "<?php echo str_replace("\n", '\n', $mediumVal );  ?>";
				      document.getElementById("<?php echo $medium; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $medium; ?>_error'></div></div>
				</div>
				</div>
				
			

			<?php
				}
			}
			?>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Professional Degree / Diploma(Completed): </label>
				<div class='fieldBoxLarge'>
				<textarea  style="width:600px; height:74px; padding:5px" name='professionalDegreeCBS' id='professionalDegreeCBS'  validate="validateStr"  caption="Field"   minlength="1"   maxlength="1000"     tip="Please mention your Professional Degree / Diploma if completed."    ></textarea>
				<?php if(isset($professionalDegreeCBS) && $professionalDegreeCBS!=""){ ?>
				  <script>
				      document.getElementById("professionalDegreeCBS").value = "<?php echo str_replace("\n", '\n', $professionalDegreeCBS );  ?>";
				      document.getElementById("professionalDegreeCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'professionalDegreeCBS_error'></div></div>
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
			}
			

			
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$profile = 'jobProfileCBS'.$workCompanyKey;
					$profileVal = $$profile;
					$workExpWebsiteName = 'workExpWebsite'.$workCompanyKey;
					$workExpWebsiteValue = $$workExpWebsiteName;
					$j++;

			?>

			<li>
				<?php if($j==1){ ?><h3 class="upperCase">Work Experience</h3><?php } ?>
				<div class='additionalInfoLeftCol'>
				<label>Job profile at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='<?php echo $profile; ?>' id='<?php echo $profile; ?>'  validate="validateStr"   required="true"   caption="Job Profile"   minlength="1"   maxlength="100"     tip="Please mention your Job Profile in this Job."   value=''  />
				<?php if(isset($profileVal) && $profileVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $profile; ?>").value = "<?php echo str_replace("\n", '\n', $profileVal );  ?>";
				      document.getElementById("<?php echo $profile; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $profile; ?>_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>	
				<div class='additionalInfoLeftCol'>
				<label> Website of <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpWebsiteName;?>' id='<?php echo $workExpWebsiteName;?>'  minlength="1" maxlength="100" caption="Website of <?php echo $workCompany; ?>"    tip="Please mention company website."   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpWebsiteValue) && $workExpWebsiteValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpWebsiteName;?>").value = "<?php echo str_replace("\n", '\n',$workExpWebsiteValue );  ?>";
				      document.getElementById("<?php echo $workExpWebsiteName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpWebsiteName;?>_error'></div></div>
				</div>
				</div>
			</li>
			<?php
				}
			}
			?>


			<?php endif;?>
			<li>
				<h3 class="upperCase">Exam Details</h3>
				(Please attach Self-attested Photocopy of your GMAT / CAT / MAT Score)
				<div class='additionalInfoLeftCol'>
				<label>TESTS: </label>
				<div class='fieldBoxLarge'>
				<input onClick="checkTestScore(this);"  type='checkbox' name='testNamesCBS[]' id='testNamesCBS0'   value='GMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GMAT<span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);"  type='checkbox' name='testNamesCBS[]' id='testNamesCBS1'   value='CAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT<span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);"  type='checkbox' name='testNamesCBS[]' id='testNamesCBS2'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT<span>&nbsp;&nbsp;
				<?php if(isset($testNamesCBS) && $testNamesCBS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesCBS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesCBS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesCBS_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="gmat1" style="display:none">
				
				<div class='additionalInfoLeftCol'>
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
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
			</li>
			
			<?php
			    if(isset($testNamesCBS) && $testNamesCBS!="" && strpos($testNamesCBS,'GMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesCBS0'));
			    </script>
			<?php
			    }
			?>
			
			<li id="cat1"" style="display:none;">
				<div class='additionalInfoLeftCol'>
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
				
				<div class='additionalInfoRightCol'>
				<label>CAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			
			<?php
			    if(isset($testNamesCBS) && $testNamesCBS!="" && strpos($testNamesCBS,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesCBS1'));
			    </script>
			<?php
			    }
			?>

			

			<li id="mat1" style="display:none">
			
				<div class='additionalInfoLeftCol'>
				<label>MAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
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
				<label>MAT Date of Examination: </label>
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
				<?php
			    if(isset($testNamesCBS) && $testNamesCBS!=""){ 
				    $tests = explode(',',$testNamesCBS);
				    foreach ($tests as $test){
					  if($test=='MAT'){
			    ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesCBS2'));
			    </script>
			<?php }
			      }
			    }
			?>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Family Background</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fathernameCBS' id='fathernameCBS'  validate="validateStr"   required="true"   caption="Name"   minlength="2"   maxlength="50"     tip="Please mention your father's name."   value=''   />
				<?php if(isset($fathernameCBS) && $fathernameCBS!=""){ ?>
				  <script>
				      document.getElementById("fathernameCBS").value = "<?php echo str_replace("\n", '\n', $fathernameCBS );  ?>";
				      document.getElementById("fathernameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fathernameCBS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Father's Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherAgeCBS' id='fatherAgeCBS'  validate="validateInteger"   required="true"   caption="Age"   minlength="2"   maxlength="2"     tip="Please mention your father's age."   value=''   />
				<?php if(isset($fatherAgeCBS) && $fatherAgeCBS!=""){ ?>
				  <script>
				      document.getElementById("fatherAgeCBS").value = "<?php echo str_replace("\n", '\n', $fatherAgeCBS );  ?>";
				      document.getElementById("fatherAgeCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherAgeCBS_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherEducationCBS' id='fatherEducationCBS'  validate="validateStr"   required="true"   caption="Education"   minlength="1"   maxlength="100"     tip="Please mention your father's education."   value=''   />
				<?php if(isset($fatherEducationCBS) && $fatherEducationCBS!=""){ ?>
				  <script>
				      document.getElementById("fatherEducationCBS").value = "<?php echo str_replace("\n", '\n', $fatherEducationCBS );  ?>";
				      document.getElementById("fatherEducationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherEducationCBS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Father's Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherOccupatonCBS' id='fatherOccupatonCBS'  validate="validateStr"   required="true"   caption="Occupation"   minlength="2"   maxlength="50"     tip="Please mention your father's occupation."   value=''   />
				<?php if(isset($fatherOccupatonCBS) && $fatherOccupatonCBS!=""){ ?>
				  <script>
				      document.getElementById("fatherOccupatonCBS").value = "<?php echo str_replace("\n", '\n', $fatherOccupatonCBS );  ?>";
				      document.getElementById("fatherOccupatonCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherOccupatonCBS_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mothernameCBS' id='mothernameCBS'  validate="validateStr"   required="true"   caption="Name"   minlength="2"   maxlength="50"     tip="Please mention your mother's name."   value=''   />
				<?php if(isset($mothernameCBS) && $mothernameCBS!=""){ ?>
				  <script>
				      document.getElementById("mothernameCBS").value = "<?php echo str_replace("\n", '\n', $mothernameCBS );  ?>";
				      document.getElementById("mothernameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mothernameCBS_error'></div></div>
				</div>
				</div>

				
				<div class='additionalInfoRightCol'>
				<label>Mother's Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherAgeCBS' id='motherAgeCBS'  validate="validateInteger"   required="true"   caption="Age"   minlength="2"   maxlength="2"     tip="Please mention your mother's age."   value=''   />
				<?php if(isset($motherAgeCBS) && $motherAgeCBS!=""){ ?>
				  <script>
				      document.getElementById("motherAgeCBS").value = "<?php echo str_replace("\n", '\n', $motherAgeCBS );  ?>";
				      document.getElementById("motherAgeCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherAgeCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherEducationCBS' id='motherEducationCBS'  validate="validateStr"   required="true"   caption="Education"   minlength="1"   maxlength="100"     tip="Please mention your mother's education."   value=''   />
				<?php if(isset($motherEducationCBS) && $motherEducationCBS!=""){ ?>
				  <script>
				      document.getElementById("motherEducationCBS").value = "<?php echo str_replace("\n", '\n', $motherEducationCBS );  ?>";
				      document.getElementById("motherEducationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherEducationCBS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Mother's Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherOccupationCBS' id='motherOccupationCBS'  validate="validateStr"   required="true"   caption="Occupation"   minlength="2"   maxlength="50"     tip="Please mention your mother's occupation."   value=''   />
				<?php if(isset($motherOccupationCBS) && $motherOccupationCBS!=""){ ?>
				  <script>
				      document.getElementById("motherOccupationCBS").value = "<?php echo str_replace("\n", '\n', $motherOccupationCBS );  ?>";
				      document.getElementById("motherOccupationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherOccupationCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Husband/Wife's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandWifenameCBS' id='husbandWifenameCBS'  validate="validateStr"     caption="Name"   minlength="2"   maxlength="50"     tip="Please mention your husband/wife's name. Leave it blank if you are single."   value=''   />
				<?php if(isset($husbandWifenameCBS) && $husbandWifenameCBS!=""){ ?>
				  <script>
				      document.getElementById("husbandWifenameCBS").value = "<?php echo str_replace("\n", '\n', $husbandWifenameCBS );  ?>";
				      document.getElementById("husbandWifenameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandWifenameCBS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Husband/Wife's Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandWifeageCBS' id='husbandWifeageCBS'  validate="validateInteger"   caption="Age"   minlength="2"   maxlength="2"     tip="Please mention your husband/wife's age. Leave it blank if you are single."   value=''   />
				<?php if(isset($husbandWifeageCBS) && $husbandWifeageCBS!=""){ ?>
				  <script>
				      document.getElementById("husbandWifeageCBS").value = "<?php echo str_replace("\n", '\n', $husbandWifeageCBS );  ?>";
				      document.getElementById("husbandWifeageCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandWifeageCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Husband/Wife's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandWifeeducationCBS' id='husbandWifeeducationCBS'  validate="validateStr"     caption="Education"   minlength="1"   maxlength="100"     tip="Please mention your husband/wife's education. Leave it blank if you are single."   value=''   />
				<?php if(isset($husbandWifeeducationCBS) && $husbandWifeeducationCBS!=""){ ?>
				  <script>
				      document.getElementById("husbandWifeeducationCBS").value = "<?php echo str_replace("\n", '\n', $husbandWifeeducationCBS );  ?>";
				      document.getElementById("husbandWifeeducationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandWifeeducationCBS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Husband/Wife's Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='husbandWifeoccupationCBS' id='husbandWifeoccupationCBS'  validate="validateStr"     caption="Occupation"   minlength="2"   maxlength="50"     tip="Please mention your husband/wife's occupation. Leave it blank if you are single."   value=''   />
				<?php if(isset($husbandWifeoccupationCBS) && $husbandWifeoccupationCBS!=""){ ?>
				  <script>
				      document.getElementById("husbandWifeoccupationCBS").value = "<?php echo str_replace("\n", '\n', $husbandWifeoccupationCBS );  ?>";
				      document.getElementById("husbandWifeoccupationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'husbandWifeoccupationCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Brother's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='brothersnameCBS' id='brothersnameCBS'  validate="validateStr"     caption="Name"   minlength="2"   maxlength="100"     tip="Please mention your brother's name separated by comma. Leave it blank if you have no brother."   value=''   />
				<?php if(isset($brothersnameCBS) && $brothersnameCBS!=""){ ?>
				  <script>
				      document.getElementById("brothersnameCBS").value = "<?php echo str_replace("\n", '\n', $brothersnameCBS );  ?>";
				      document.getElementById("brothersnameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'brothersnameCBS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Brother's Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='brothersageCBS' id='brothersageCBS'  validate="validateStr"     caption="Age"   minlength="1"   maxlength="20"     tip="Please mention your brother's age separated by comma. Leave it blank if you have no brother."   value=''   />
				<?php if(isset($brothersageCBS) && $brothersageCBS!=""){ ?>
				  <script>
				      document.getElementById("brothersageCBS").value = "<?php echo str_replace("\n", '\n', $brothersageCBS );  ?>";
				      document.getElementById("brothersageCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'brothersageCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Brother's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='brotherseducationCBS' id='brotherseducationCBS'  validate="validateStr"     caption="Education"   minlength="1"   maxlength="200"     tip="Please mention your brother's education separated by comma. Leave it blank if you have no brother."   value=''   />
				<?php if(isset($brotherseducationCBS) && $brotherseducationCBS!=""){ ?>
				  <script>
				      document.getElementById("brotherseducationCBS").value = "<?php echo str_replace("\n", '\n', $brotherseducationCBS );  ?>";
				      document.getElementById("brotherseducationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'brotherseducationCBS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Brother's Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='brothersoccupationCBS' id='brothersoccupationCBS'  validate="validateStr"     caption="Occupation"   minlength="2"   maxlength="100"     tip="Please mention your brother's occupation separated by comma. Leave it blank if you have no brother."   value=''   />
				<?php if(isset($brothersoccupationCBS) && $brothersoccupationCBS!=""){ ?>
				  <script>
				      document.getElementById("brothersoccupationCBS").value = "<?php echo str_replace("\n", '\n', $brothersoccupationCBS );  ?>";
				      document.getElementById("brothersoccupationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'brothersoccupationCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Sister's Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='sistersnameCBS' id='sistersnameCBS'  validate="validateStr"     caption="Name"   minlength="2"   maxlength="100"     tip="Please mention your sister's name separated by comma. Leave it blank if you have no sister."   value=''   />
				<?php if(isset($sistersnameCBS) && $sistersnameCBS!=""){ ?>
				  <script>
				      document.getElementById("sistersnameCBS").value = "<?php echo str_replace("\n", '\n', $sistersnameCBS );  ?>";
				      document.getElementById("sistersnameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sistersnameCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Sister's Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='sistersageCBS' id='sistersageCBS'  validate="validateStr"     caption="Age"   minlength="1"   maxlength="20"     tip="Please mention your sister's age separated by comma. Leave it blank if you have no sister."   value=''   />
				<?php if(isset($sistersageCBS) && $sistersageCBS!=""){ ?>
				  <script>
				      document.getElementById("sistersageCBS").value = "<?php echo str_replace("\n", '\n', $sistersageCBS );  ?>";
				      document.getElementById("sistersageCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sistersageCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Sister's Education: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='sisterseducationCBS' id='sisterseducationCBS'  validate="validateStr"     caption="Education"   minlength="1"   maxlength="200"     tip="Please mention your sister's education separated by comma. Leave it blank if you have no sister."   value=''   />
				<?php if(isset($sisterseducationCBS) && $sisterseducationCBS!=""){ ?>
				  <script>
				      document.getElementById("sisterseducationCBS").value = "<?php echo str_replace("\n", '\n', $sisterseducationCBS );  ?>";
				      document.getElementById("sisterseducationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sisterseducationCBS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Sister's  Occupation: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='sistersoccupationCBS' id='sistersoccupationCBS'  validate="validateStr"     caption="Occupation"   minlength="2"   maxlength="100"     tip="Please mention your sister's occupation separated by comma. Leave it blank if you have no sister."   value=''   />
				<?php if(isset($sistersoccupationCBS) && $sistersoccupationCBS!=""){ ?>
				  <script>
				      document.getElementById("sistersoccupationCBS").value = "<?php echo str_replace("\n", '\n', $sistersoccupationCBS );  ?>";
				      document.getElementById("sistersoccupationCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'sistersoccupationCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Family Income: </label>
				<div class='fieldBoxLarge'>
				<select name='familyIncome_CBS' id='familyIncome_CBS'  validate="validateSelect"   required="true"   caption="Family Income"  minlength='1' maxlength='20'  onmouseover="showTipOnline('Please select your family income.',this);" onmouseout='hidetip();'   >
				<option value="">Select</option>
					<option value="Below Rs.5 lakh">Below Rs.5 lakh</option>
				        <option value="Rs. 5 lakh - 15 lakh">Rs. 5 lakh - 15 lakh</option>
					<option value="Rs.15 lakh -  25 lakh">Rs.15 lakh -  25 lakh</option>
					<option value="Rs.25 lakh - 35 lakh">Rs.25 lakh - 35 lakh</option>
					<option value="Rs.35 lakh - 50 lakh">Rs.35 lakh - 50 lakh</option>
					<option value="Above Rs.50 lakh">Above Rs.50 lakh</option>
				</select>
				<?php if(isset($familyIncome_CBS) && $familyIncome_CBS!=""){ ?>
				<script>
				  var selObj = document.getElementById("familyIncome_CBS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
					  if (A[--L].value== "<?php echo $familyIncome_CBS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
					}
				}
				</script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'familyIncome_CBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Scholarship / Award Received / Any other relevant information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Have you received any scholarships / awards or do you have any special achievement in academics over the years ? Mention in brief below. Attach testimonials:</label>
				<div class='fieldBoxLarge'>
				<textarea  style="width:600px; height:74px; padding:5px"  name='awardsCBS' id='awardsCBS'   required="true"   caption="awards or special achievements"   minlength="1"   maxlength="500"     tip="Mention any awards received or special achievements"    ></textarea>
				<?php if(isset($awardsCBS) && $awardsCBS!=""){ ?>
				  <script>
				      document.getElementById("awardsCBS").value = "<?php echo str_replace("\n", '\n', $awardsCBS );  ?>";
				      document.getElementById("awardsCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardsCBS_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>What have been other interests and/or extra-curricular or co-curricular activities over the years? Write in brief below. Mention if you have won any award/certificate in any such activity and also  if you have  achieved anything  significant. Attach testimonials:</label>
				<div class='fieldBoxLarge'>
				<textarea  style="width:600px; height:74px; padding:5px"  name='interestCBS' id='interestCBS' required="true"   caption="interest or exyra curricular activities"   minlength="1"   maxlength="500"     tip="Mention any interests or extra curricular activities"    ></textarea>
				<?php if(isset($interestCBS) && $interestCBS!=""){ ?>
				  <script>
				      document.getElementById("interestCBS").value = "<?php echo str_replace("\n", '\n', $interestCBS );  ?>";
				      document.getElementById("interestCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'interestCBS_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Write a short essary about  your career objectives, how would a Management Diploma help you to achieve your career objectives and why do you want to join the PGDM program at Calcutta Business School: </label>
				<div class='fieldBoxLarge'>
				<textarea  style="width:600px; height:74px; padding:5px"  name='objectivesCBS' id='objectivesCBS'  required="true"   caption="career objectives"   minlength="1"   maxlength="2000"     tip="Mention about your career objectives."    ></textarea>
				<?php if(isset($objectivesCBS) && $objectivesCBS!=""){ ?>
				  <script>
				      document.getElementById("objectivesCBS").value = "<?php echo str_replace("\n", '\n', $objectivesCBS );  ?>";
				      document.getElementById("objectivesCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'objectivesCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">1st Reference</h3>
				<div class='additionalInfoLeftCol'>
				<label>Name, Designation & Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref1NameCBS' id='ref1NameCBS'  validate="validateStr"   required="true"   caption="Name, Designation & Organization"   minlength="1"   maxlength="200"     tip="Enter Name, Designation & Organization of the reference"   value=''   />
				<?php if(isset($ref1NameCBS) && $ref1NameCBS!=""){ ?>
				  <script>
				      document.getElementById("ref1NameCBS").value = "<?php echo str_replace("\n", '\n', $ref1NameCBS );  ?>";
				      document.getElementById("ref1NameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1NameCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref1AddressCBS' id='ref1AddressCBS'  validate="validateStr"   required="true"   caption="Address"   minlength="1"   maxlength="100"     tip="Enter the complete address of this reference"   value=''   />
				<?php if(isset($ref1AddressCBS) && $ref1AddressCBS!=""){ ?>
				  <script>
				      document.getElementById("ref1AddressCBS").value = "<?php echo str_replace("\n", '\n', $ref1AddressCBS );  ?>";
				      document.getElementById("ref1AddressCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1AddressCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref1PhoneCBS' id='ref1PhoneCBS'  validate="validateMobileInteger"   required="true"   caption="Phone Number"   minlength="10"   maxlength="10"     tip="Enter the phone number of this reference"   value=''   />
				<?php if(isset($ref1PhoneCBS) && $ref1PhoneCBS!=""){ ?>
				  <script>
				      document.getElementById("ref1PhoneCBS").value = "<?php echo str_replace("\n", '\n', $ref1PhoneCBS );  ?>";
				      document.getElementById("ref1PhoneCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1PhoneCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref1EmailCBS' id='ref1EmailCBS'  validate="validateEmail" caption="reference's email"  required="true"   caption="Email"   minlength="1"   maxlength="100"     tip="Enter the email address of this reference"   value=''   />
				<?php if(isset($ref1EmailCBS) && $ref1EmailCBS!=""){ ?>
				  <script>
				      document.getElementById("ref1EmailCBS").value = "<?php echo str_replace("\n", '\n', $ref1EmailCBS );  ?>";
				      document.getElementById("ref1EmailCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1EmailCBS_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>How do you know him/her ? : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref1sourceCBS' id='ref1sourceCBS' required="true"   caption="details"   minlength="1"   maxlength="100"     tip="Please mention how you know your reference."   value=''   />
				<?php if(isset($ref1sourceCBS) && $ref1sourceCBS!=""){ ?>
				  <script>
				      document.getElementById("ref1sourceCBS").value = "<?php echo str_replace("\n", '\n', $ref1sourceCBS );  ?>";
				      document.getElementById("ref1sourceCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1sourceCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">2nd Reference</h3>
				<div class='additionalInfoLeftCol'>
				<label>Name, Designation & Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref2NameCBS' id='ref2NameCBS'  validate="validateStr"   required="true"   caption="Name, Designation & Organization"   minlength="1"   maxlength="200"     tip="Enter Name, Designation & Organization of the reference"   value=''   />
				<?php if(isset($ref2NameCBS) && $ref2NameCBS!=""){ ?>
				  <script>
				      document.getElementById("ref2NameCBS").value = "<?php echo str_replace("\n", '\n', $ref2NameCBS );  ?>";
				      document.getElementById("ref2NameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2NameCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref2AddressCBS' id='ref2AddressCBS'  validate="validateStr"   required="true"   caption="Address"   minlength="1"   maxlength="100"     tip="Enter the complete address of this reference"   value=''   />
				<?php if(isset($ref2AddressCBS) && $ref2AddressCBS!=""){ ?>
				  <script>
				      document.getElementById("ref2AddressCBS").value = "<?php echo str_replace("\n", '\n', $ref2AddressCBS );  ?>";
				      document.getElementById("ref2AddressCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2AddressCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref2PhoneCBS' id='ref2PhoneCBS'  validate="validateMobileInteger"   required="true"   caption="Phone Number"   minlength="10"   maxlength="10"     tip="Enter the phone number of this reference"   value=''   />
				<?php if(isset($ref2PhoneCBS) && $ref2PhoneCBS!=""){ ?>
				  <script>
				      document.getElementById("ref2PhoneCBS").value = "<?php echo str_replace("\n", '\n', $ref2PhoneCBS );  ?>";
				      document.getElementById("ref2PhoneCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2PhoneCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref2EmailCBS' id='ref2EmailCBS'  validate="validateEmail"   required="true" caption="reference's email"    caption="Email"   minlength="1"   maxlength="100"     tip="Enter the email address of this reference"   value=''   />
				<?php if(isset($ref2EmailCBS) && $ref2EmailCBS!=""){ ?>
				  <script>
				      document.getElementById("ref2EmailCBS").value = "<?php echo str_replace("\n", '\n', $ref2EmailCBS );  ?>";
				      document.getElementById("ref2EmailCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2EmailCBS_error'></div></div>
				</div>
				</div>
			</li>
			<li>	
				<div class='additionalInfoLeftCol'>
				<label>How do you know him/her ? : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref2sourceCBS' id='ref2sourceCBS'  required="true"   caption="details"   minlength="1"   maxlength="100"     tip="Please mention how you know your reference."   value=''   />
				<?php if(isset($ref2sourceCBS) && $ref2sourceCBS!=""){ ?>
				  <script>
				      document.getElementById("ref2sourceCBS").value = "<?php echo str_replace("\n", '\n', $ref2sourceCBS );  ?>";
				      document.getElementById("ref2sourceCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2sourceCBS_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li>
				<h3 class="upperCase">3rd Reference</h3>
				<div class='additionalInfoLeftCol'>
				<label>Name, Designation & Organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref3NameCBS' id='ref3NameCBS'  validate="validateStr"   required="true"   caption="Name, Designation & Organization"   minlength="1"   maxlength="200"     tip="Enter Name, Designation & Organization of the reference"   value=''   />
				<?php if(isset($ref3NameCBS) && $ref3NameCBS!=""){ ?>
				  <script>
				      document.getElementById("ref3NameCBS").value = "<?php echo str_replace("\n", '\n', $ref3NameCBS );  ?>";
				      document.getElementById("ref3NameCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3NameCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref3AddressCBS' id='ref3AddressCBS'  validate="validateStr"   required="true"   caption="Address"   minlength="1"   maxlength="100"     tip="Enter the complete address of this reference"   value=''   />
				<?php if(isset($ref3AddressCBS) && $ref3AddressCBS!=""){ ?>
				  <script>
				      document.getElementById("ref3AddressCBS").value = "<?php echo str_replace("\n", '\n', $ref3AddressCBS );  ?>";
				      document.getElementById("ref3AddressCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3AddressCBS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref3PhoneCBS' id='ref3PhoneCBS'  validate="validateMobileInteger"   required="true"   caption="Phone Number"   minlength="10"   maxlength="10"     tip="Enter the phone number of this reference"   value=''   />
				<?php if(isset($ref3PhoneCBS) && $ref3PhoneCBS!=""){ ?>
				  <script>
				      document.getElementById("ref3PhoneCBS").value = "<?php echo str_replace("\n", '\n', $ref3PhoneCBS );  ?>";
				      document.getElementById("ref3PhoneCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3PhoneCBS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref3EmailCBS' id='ref3EmailCBS'  validate="validateEmail" caption="reference's email"  required="true"   caption="Email"   minlength="1"   maxlength="100"     tip="Enter the email address of this reference"   value=''   />
				<?php if(isset($ref3EmailCBS) && $ref3EmailCBS!=""){ ?>
				  <script>
				      document.getElementById("ref3EmailCBS").value = "<?php echo str_replace("\n", '\n', $ref3EmailCBS );  ?>";
				      document.getElementById("ref3EmailCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3EmailCBS_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>How do you know him/her ? : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ref3sourceCBS' id='ref3sourceCBS' required="true"   caption="details"   minlength="1"   maxlength="100"     tip="Please mention how you know your reference."   value=''   />
				<?php if(isset($ref3sourceCBS) && $ref3sourceCBS!=""){ ?>
				  <script>
				      document.getElementById("ref3sourceCBS").value = "<?php echo str_replace("\n", '\n', $ref3sourceCBS );  ?>";
				      document.getElementById("ref3sourceCBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref3sourceCBS_error'></div></div>
				</div>
				</div>
			</li>



			<li>
				<h3 class="upperCase">Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I Solemnly declare that the information given above is true to the best of my knowledge. Any information given in the application form, if found incorrect on scrutiny, will render the application liable to rejection; admission, if granted, will stand cancelled. I have gone through the rules and process of admission as given in the prospectus. If admitted, I shall abide by the Rules & Regulations of Calcutta Business School (CBS) as at the time of my admission or as may be altered from time to time. I also affirm that I will not participate in/initiate any activity that may lead to disruption / damage the reputation and goodwill of the Calcutta Business School. I submit to the jurisdiction of the High Court of Calcutta, in the event of any dispute.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsCBS[]' id='agreeToTermsCBS0' checked required="true" validate="validateChecked" caption="Please agree to the terms stated above" ></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsCBS) && $agreeToTermsCBS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsCBS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$agreeToTermsCBS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsCBS0_error'></div></div>
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

  </script>
