<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "XAT" || obj.value == "CAT"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional",key+"EmailId");
	}
	else if(obj.value == "GMAT"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	}
	else if(obj.value == "CMAT"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"RankAdditional");
	}

	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    $(key+'2').style.display = 'none';
		    if($(key+'3')) $(key+'3').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+'1').style.display = '';
		    $(key+'2').style.display = '';
		    if($(key+'3')) $(key+'3').style.display = '';
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
        document.getElementById(objectsArr[i]).value = '';
	    document.getElementById(objectsArr[i]).removeAttribute('required');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }

</script>


<?php //_p($this->_ci_cached_vars); ?>
<div class="formChildWrapper">
	<div class="formSection">
    	<ul>
    	<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Personal Details:</h3>
				<div class="additionalInfoLeftCol" style="width:700px;">
					<label>Age: </label>
					<div class='fieldBoxLarge' style="width:225px">
						<input type='text' name='age' id='age'  validate="validateOnlineFormAge"   required="true"   caption="age"   minlength="1"   maxlength="2"     tip="Please enter your age,in years, as on 31st December 2014."   value=''  />
						<?php if(isset($age) && $age!=""){ ?>
						<script>
							document.getElementById("age").value = "<?php echo str_replace("\n", '\n', $age );  ?>";
							document.getElementById("age").style.color = "";
						</script>
						  <?php } ?>
						<div style='display:none'><div class='errorMsg' id= 'age_error'></div></div>
					</div>
				</div>
			</li>

                        <li>
                                <div class='additionalInfoLeftCol' style="width:920px;">
                                <label>Select Identity: </label>
                                <div class='fieldBoxLarge' style="width:610px">
                                <input  validate="validateCheckedGroup"   required="true"   caption="identity"  onChange="$('idNumberSDM').value = '';" type='radio' name='idSDM' id='idSDM0'   value='Passport No.'  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" >Passport No.</span>&nbsp;&nbsp;
                                <input  validate="validateCheckedGroup"   required="true"   caption="identity" onChange="$('idNumberSDM').value = '';" type='radio' name='idSDM' id='idSDM1'   value='Voter Id No.'  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" >Voter Id No.</span>&nbsp;&nbsp;
                                <input  validate="validateCheckedGroup"   required="true"   caption="identity" onChange="$('idNumberSDM').value = '';" type='radio' name='idSDM' id='idSDM2'   value='Aadhar Card No.'  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();">Aadhar Card No.</span>&nbsp;&nbsp;
                                <input  validate="validateCheckedGroup"   required="true"   caption="identity" onChange="$('idNumberSDM').value = '';" type='radio' name='idSDM' id='idSDM3'   value='Driving Licence No.'  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" >Driving Licence No.</span>&nbsp;&nbsp;
                                <input  validate="validateCheckedGroup"   required="true"   caption="identity" onChange="$('idNumberSDM').value = '';" type='radio' name='idSDM' id='idSDM4'   value='PAN No.'  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No. / PAN No. If you have none of these, just leave it unselected.',this);" onmouseout="hidetip();" >PAN No.</span>&nbsp;&nbsp;
                                <?php if(isset($idSDM) && $idSDM!=""){ ?>
                                  <script>
                                      radioObj = document.forms["OnlineForm"].elements["idSDM"];
                                      var radioLength = radioObj.length;
                                      for(var i = 0; i < radioLength; i++) {
                                              radioObj[i].checked = false;
                                              if(radioObj[i].value == "<?php echo $idSDM;?>") {
                                                      radioObj[i].checked = true;
                                              }
                                      }
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'idSDM_error'></div></div>
                                </div>
                                </div>
                        </li>

			<li>
				<div class="additionalInfoLeftCol" style="width:700px;">
					<label>Identity No.: </label>
					<div class='fieldBoxLarge' style="width:225px">
						<input type='text' name='idNumberSDM' id='idNumberSDM'  validate="validateStr"   required="true"   caption="no."   minlength="1"   maxlength="20"     tip="Please enter one of the following: Passport No / Voter id No./Aadhar Card No / Driving Licence No./ PAN No. If you have none of these, just enter NA."   value=''  allowNA="true" />
						<?php if(isset($idNumberSDM) && $idNumberSDM!=""){ ?>
						<script>
							document.getElementById("idNumberSDM").value = "<?php echo str_replace("\n", '\n', $idNumberSDM );  ?>";
							document.getElementById("idNumberSDM").style.color = "";
						</script>
						  <?php } ?>
						<div style='display:none'><div class='errorMsg' id= 'idNumberSDM_error'></div></div>
					</div>
				</div>
			</li>

			<li>
				<div class="additionalInfoLeftCol" style="width:700px;">
					<label>Phone /Parent's Contact No.: </label>
					<div class='fieldBoxLarge' style="width:225px">
						<input type='text' name='contactNoSDM' id='contactNoSDM'  validate="validateInteger"   required="true"   caption="contact no."   minlength="5"   maxlength="20"     tip="Enter your home landline phone number or contact number of either of your parents."   value=''  />
						<?php if(isset($contactNoSDM) && $contactNoSDM!=""){ ?>
						<script>
							document.getElementById("contactNoSDM").value = "<?php echo str_replace("\n", '\n', $contactNoSDM );  ?>";
							document.getElementById("contactNoSDM").style.color = "";
						</script>
						  <?php } ?>
						<div style='display:none'><div class='errorMsg' id= 'contactNoSDM_error'></div></div>
					</div>
				</div>
			</li>
			
			<li>
				<h3 class="upperCase">Education (additional information about your education):</h3>
			</li>
			
			<?php
			$graduationCourseName = 'Graduation';
			$otherCourses = array();
			
			if(is_array($educationDetails)) {
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						if($educationDetail['fieldName'] == 'graduationExaminationName') {
							$graduationCourseName = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=4;$i++) {
								if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
									$otherCourses[$i] = $educationDetail['value'];
								}
							}
						}
					}
				}
			}
			?>
			
			<li>
				<div class="additionalInfoLeftCol">
					<label><?php echo $graduationCourseName; ?> specialization: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='graduationSpecialization' id='graduationSpecialization'  validate="validateStr"   required="true"   caption="graduation specialization"   minlength="1"   maxlength="50"     tip="Enter the spcialzation for your graduation here for example mechanical engineering, economics, commerce etc."   value=''  class="textboxLarge" />
						<?php if(isset($graduationSpecialization) && $graduationSpecialization!=""){ ?>
								<script>
									document.getElementById("graduationSpecialization").value = "<?php echo str_replace("\n", '\n', $graduationSpecialization );  ?>";
									document.getElementById("graduationSpecialization").style.color = "";
								</script>
								  <?php } ?>
						<div style='display:none'><div class='errorMsg' id= 'graduationSpecialization_error'></div></div>
					</div>
				</div>				

			<?php

			if(count($otherCourses)>0) {
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$oCourseSpecialization = 'otherCourseSpecialization_mul_'.$otherCourseId;
					$oCourseSpecializationVal = $$oCourseSpecialization;					
			?>
            <?php if($otherCourseId==2 || $otherCourseId==4){?>
            <li>
            <?php } ?>

						<div  <?php if($otherCourseId%2==1){?>class='additionalInfoRightCol'<?php }else{?> class='additionalInfoLeftCol'<?php }?>>
							<label><?php echo $otherCourseName; ?> specialization: </label>
							<div class='fieldBoxLarge'>
								<input type='text' name='<?php echo $oCourseSpecialization; ?>' id='<?php echo $oCourseSpecialization; ?>' required='true' validate="validateStr"  caption="<?php echo $otherCourseName; ?> specialization"   minlength="1"   maxlength="50"     tip="Enter the spcialzation for your <?php echo $otherCourseName; ?>"   value='' class="textboxLarge" />
								<?php if(isset($oCourseSpecializationVal) && $oCourseSpecializationVal!=""){ ?>
										<script>
											document.getElementById("<?php echo $oCourseSpecialization; ?>").value = "<?php echo str_replace("\n", '\n', $oCourseSpecializationVal );  ?>";
											document.getElementById("<?php echo $oCourseSpecialization; ?>").style.color = "";
										</script>
										  <?php } ?>
								<div style='display:none'><div class='errorMsg' id= '<?php echo $oCourseSpecialization; ?>_error'></div></div>
							</div>
						</div>
         <?php if($otherCourseId==1 || $otherCourseId==3 || $otherCourseId==4){?>
                     </li>
                     <?php } ?>

			<?php
				}
			}
			
			?>
			
			
			<?php endif;?>
			
		<li>
				<h3 class="upperCase">Qualifying Examination</h3>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesSDM[]' id='testNamesSDM0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesSDM[]' id='testNamesSDM1'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesSDM[]' id='testNamesSDM2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesSDM[]' id='testNamesSDM3'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<?php if(isset($testNamesSDM) && $testNamesSDM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesSDM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesSDM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesSDM_error'></div></div>
				</div>
				</div>
		</li>		
			
		<li id="cat1" style="display:none;">
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">CAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='catDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
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
				<label style="width:90px !important; font-weight:normal">CAT Score: </label>
				<div class='float_L'  style="width:250px;">
					<input class="textboxSmaller" type='text' name='catScoreAdditional' id='catScoreAdditional'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
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
		
		<li id="cat2" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">CAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
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
				<label style="width:90px !important; font-weight:normal">CAT Percentile: </label>
				<div class='float_L'  style="width:250px;">
				   <input class="textboxSmaller"  type='text' name='catPercentileAdditional' id='catPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
		
		<li id="cat3" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Email ID Submitted for CAT: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catEmailId' id='catEmailId'  validate="validateEmail"  allowNA="true"  caption="Email ID"   minlength="2"   maxlength="200"     tip="Please enter the email ID submitted to CAT. If this is not applicable to you, enter NA"   value=''  />
				<?php if(isset($catEmailId) && $catEmailId!=""){ ?>
						<script>
						document.getElementById("catEmailId").value = "<?php echo str_replace("\n", '\n', $catEmailId );  ?>";
						document.getElementById("catEmailId").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'catEmailId_error'></div></div>
				</div>
			</div>			
		</li>
		<?php
		    if(isset($testNamesSDM) && $testNamesSDM!="" && strpos($testNamesSDM,'CAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('testNamesSDM0'));
		    </script>
		<?php
		    }
		?>
	
	
		<li id="gmat1" style="display:none;">
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">GMAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='gmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!=""){ ?>
						<script>
						document.getElementById("gmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $gmatDateOfExaminationAdditional );  ?>";
						document.getElementById("gmatDateOfExaminationAdditional").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'gmatDateOfExaminationAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">GMAT Score: </label>
				<div class='float_L'  style="width:250px;">
					<input class="textboxSmaller" type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
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
		
		<li id="gmat2" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">GMAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
						document.getElementById("gmatRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">GMAT Percentile: </label>
				<div class='float_L'  style="width:250px;">
				   <input class="textboxSmaller"  type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don?t know your percentile, enter NA."   value=''  />
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
		    if(isset($testNamesSDM) && $testNamesSDM!="" && strpos($testNamesSDM,'GMAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('testNamesSDM1'));
		    </script>
		<?php
		    }
		?>
			
		<li id="xat1" style="display:none;">
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">XAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'   validate="validateDateForms"  caption="date"            onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
			<?php if(isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!=""){ ?>
					<script>
					document.getElementById("xatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $xatDateOfExaminationAdditional );  ?>";
					document.getElementById("xatDateOfExaminationAdditional").style.color = "";
					</script>
				  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'xatDateOfExaminationAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">XAT Score: </label>
				<div class='float_L'  style="width:250px;">
					<input class="textboxSmaller" type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"  allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
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
		
		<li id="xat2" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">XAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'  validate="validateStr"   allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
						document.getElementById("xatRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">XAT Percentile: </label>
				<div class='float_L'  style="width:250px;">
				   <input class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
		
		<li id="xat3" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Email ID Submitted for XAT: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatEmailId' id='xatEmailId'  validate="validateEmail"  allowNA="true"  caption="Email ID"   minlength="2"   maxlength="200"     tip="Please enter the email ID submitted to XAT. If this is not applicable to you, enter NA"   value=''  />
				<?php if(isset($xatEmailId) && $xatEmailId!=""){ ?>
						<script>
						document.getElementById("xatEmailId").value = "<?php echo str_replace("\n", '\n', $xatEmailId );  ?>";
						document.getElementById("xatEmailId").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'xatEmailId_error'></div></div>
				</div>
			</div>			
		</li>
		<?php
		    if(isset($testNamesSDM) && $testNamesSDM!="" && strpos($testNamesSDM,'XAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('testNamesSDM2'));
		    </script>
		<?php
		    }
		?>

		<li id="cmat1" style="display:none;">
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">CMAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!=""){ ?>
						<script>
						document.getElementById("cmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationAdditional );  ?>";
						document.getElementById("cmatDateOfExaminationAdditional").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">CMAT Score: </label>
				<div class='float_L'  style="width:250px;">
					<input class="textboxSmaller" type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
					<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
							<script>
								document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
								document.getElementById("cmatScoreAdditional").style.color = "";
							</script>
							  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
			</div>
			
		</li>
		
		<li id="cmat2" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">CMAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
						document.getElementById("cmatRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">CMAT Rank: </label>
				<div class='float_L'  style="width:250px;">
				   <input class="textboxSmaller"  type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"  allowNA="true"  caption="Rank"   minlength="1"   maxlength="8"     tip="Mention your rank in the exam. If you don't know your rank, enter NA."   value=''  />
					<?php if(isset($cmatRankAdditional) && $cmatRankAdditional!=""){ ?>
							<script>
							document.getElementById("cmatRankAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRankAdditional);  ?>";
							document.getElementById("cmatRankAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'cmatRankAdditional_error'></div></div>
				</div>
			</div>
			
		</li>
		<?php
		    if(isset($testNamesSDM) && $testNamesSDM!="" && strpos($testNamesSDM,'CMAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('testNamesSDM3'));
		    </script>
		<?php
		    }
		?>

        <?php if($action != 'updateScore'):?>
        <li>
            <h3 class="upperCase">Work Experience Details:</h3>
            <div class="additionalInfoLeftCol" style="width:700px;">
            <label>Work Experience in months: </label>
                <div class='fieldBoxLarge' style="width:225px">
                      <input type='text' name='workExSDM' id='workExSDM'  validate="validateInteger"  caption="Work Ex"   minlength="1"   maxlength="4"     tip="Enter your full time work experience in months."   value='' />
                      <?php if(isset($workExSDM) && $workExSDM!=""){ ?>
                      <script>
                      document.getElementById("workExSDM").value = "<?php echo str_replace("\n", '\n', $workExSDM );  ?>";
                      document.getElementById("workExSDM").style.color = "";
                      </script>
                      <?php } ?>
                     <div style='display:none'><div class='errorMsg' id= 'workExSDM_error'></div></div>
               </div>
               </div>
        </li>

		<li>
				<h3 class="upperCase">Extracurricular Activities</h3>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Extracurricular Activities: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input type='checkbox' name='extraCurricular_SDM[]' id='extraCurricular_SDM0'   value='Sports'    onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" >Sports</span>&nbsp;&nbsp;
				<input type='checkbox' name='extraCurricular_SDM[]' id='extraCurricular_SDM1'   value='Arts'    onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" >Arts</span>&nbsp;&nbsp;
				<input type='checkbox' name='extraCurricular_SDM[]' id='extraCurricular_SDM2'   value='Public Speaking/Writing'    onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" >Public Speaking/Writing</span>&nbsp;&nbsp;
				<input type='checkbox' name='extraCurricular_SDM[]' id='extraCurricular_SDM3'   value='Others'    onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select one/more of the following if you took part in any extracurricular activity.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($extraCurricular_SDM) && $extraCurricular_SDM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["extraCurricular_SDM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$extraCurricular_SDM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'extraCurricular_SDM_error'></div></div>
				</div>
				</div>
		</li>		
		
		<?php if(is_array($gdpiLocations)): ?>
		<li>
			<h3 class="upperCase">Preference for interview Center:</h3>
            <div class="additionalInfoLeftCol" style="width:436px">
                <label style="font-weight:normal">First preference: </label>
                <div class='float_L' style="width:125px;">
                <select blurMethod="checkValidCampusPreference(this.id);" class="selectSmall" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your first preference for interview center.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="first preference for interview center">
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
                <label style="font-weight:normal; width:90px">Second preference: </label>
                <div class='float_L' style="width:125px;">
                <select blurMethod="checkValidCampusPreference(this.id);" class="selectSmall" name='gdpiLocation2' id='gdpiLocation2' onmouseover="showTipOnline('Select your second preference for interview center.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="second preference for interview center">
                <option value=''>Select</option>
                <?php foreach($gdpiLocations as $gdpiLocation): ?>
                        <option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
                <?php endforeach; ?>
                </select>
                <?php if(isset($gdpiLocation2) && $gdpiLocation2!=""){ ?>
                <script>
                var selObj = document.getElementById("gdpiLocation2"); 
                var A= selObj.options, L= A.length;
                while(L){
                    if (A[--L].value== "<?php echo $gdpiLocation2;?>"){
                    selObj.selectedIndex= L;
                    L= 0;
                    }
                }
                </script>
                  <?php } ?>
                <div style='display:none'><div class='errorMsg' id= 'gdpiLocation2_error'></div></div>
                </div>
            </div>
		</li>
		
		
		<li>
			<label style="font-weight:normal">Third preference: </label>
			<div class='float_L'>
			<select blurMethod="checkValidCampusPreference(this.id);" class="selectSmall" name='gdpiLocation3' id='gdpiLocation3' onmouseover="showTipOnline('Select your third preference for interview center.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="third preference for interview center">
			<option value=''>Select</option>
			<?php foreach($gdpiLocations as $gdpiLocation): ?>
					<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
			<?php endforeach; ?>
			</select>
			<?php if(isset($gdpiLocation3) && $gdpiLocation3!=""){ ?>
			<script>
			var selObj = document.getElementById("gdpiLocation3"); 
			var A= selObj.options, L= A.length;
			while(L){
				if (A[--L].value== "<?php echo $gdpiLocation3;?>"){
				selObj.selectedIndex= L;
				L= 0;
				}
			}
			</script>
			  <?php } ?>
			<div style='display:none'><div class='errorMsg' id= 'gdpiLocation3_error'></div></div>
			</div>
		</li>
		
		<?php endif; ?>	
		
		<li>
		<label style="font-weight:normal">Terms:</label>
		<div class='float_L' style="width:620px; color:#666666; font-style:italic">
	    I hereby state that all the above details are true to the best of my knowledge and I will produce proof as relevant if asked.	
		<div class="spacer10 clearFix"></div>
       	<div >
        	<input type='checkbox' name='agreeToTermsSDM[]' id='agreeToTermsSDM0' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
			I agree to the terms stated above
			<?php if(isset($agreeToTermsSDM) && $agreeToTermsSDM!=""){ ?>
				<script>
		    
			objCheckBoxes = document.getElementsByName("agreeToTermsSDM[]");
		    var countCheckBoxes = objCheckBoxes.length;
			
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;

			      <?php $arr = explode(",",$agreeToTermsSDM);
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
		<div style='display:none'><div class='errorMsg' id= 'agreeToTermsSDM0_error'></div></div>
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

  function checkValidCampusPreference(id){
	if(id=='preferredGDPILocation'){ sId = 'gdpiLocation2'; tId='gdpiLocation3';  }
	else if(id=='gdpiLocation2'){ sId = 'preferredGDPILocation'; tId = 'gdpiLocation3'; }
	else if(id=='gdpiLocation3'){sId = 'preferredGDPILocation'; tId = 'gdpiLocation2'; }
	var selectedPrefObj = document.getElementById(id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById(sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	var selObj2 = document.getElementById(tId); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].value;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) ){
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
