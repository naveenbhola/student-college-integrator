<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	      <?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Mailing Address</h3>
				<div class='additionalInfoLeftCol'>
				<label>Fax No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='mailFaxCMD' id='mailFaxCMD'         tip="Please enter your fax number."   value=''  validate="validateInteger" caption="fax number" minlength="4" maxlength="14" />
				<?php if(isset($mailFaxCMD) && $mailFaxCMD!=""){ ?>
				  <script>
				      document.getElementById("mailFaxCMD").value = "<?php echo str_replace("\n", '\n', $mailFaxCMD );  ?>";
				      document.getElementById("mailFaxCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mailFaxCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Permanent Address</h3>
				<div class='additionalInfoLeftCol'>
				<label>Telephone No. Off: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='officePhoneCMD' id='officePhoneCMD'         tip="Please enter your official phone number."   value=''  validate="validateInteger" caption="number" minlength="4" maxlength="14" />
				<?php if(isset($officePhoneCMD) && $officePhoneCMD!=""){ ?>
				  <script>
				      document.getElementById("officePhoneCMD").value = "<?php echo str_replace("\n", '\n', $officePhoneCMD );  ?>";
				      document.getElementById("officePhoneCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'officePhoneCMD_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Fax: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='officeFaxCMD' id='officeFaxCMD'         tip="Please enter your fax number."   value='' validate="validateInteger" caption="fax number" minlength="4" maxlength="14"  />
				<?php if(isset($officeFaxCMD) && $officeFaxCMD!=""){ ?>
				  <script>
				      document.getElementById("officeFaxCMD").value = "<?php echo str_replace("\n", '\n', $officeFaxCMD );  ?>";
				      document.getElementById("officeFaxCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'officeFaxCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Family details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Name of Organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='orgnNameCMD' id='orgnNameCMD'  validate="validateStr"   required="true"   caption="organization name"   minlength="2"   maxlength="150"     tip="Please enter your father's organization name. If not applicable, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($orgnNameCMD) && $orgnNameCMD!=""){ ?>
				  <script>
				      document.getElementById("orgnNameCMD").value = "<?php echo str_replace("\n", '\n', $orgnNameCMD );  ?>";
				      document.getElementById("orgnNameCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'orgnNameCMD_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Office Address: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='officeAddressCMD' id='officeAddressCMD'  validate="validateStr"   required="true"   caption="office address"   minlength="2"   maxlength="250"     tip="Please enter your father's office address. If not applicable, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($officeAddressCMD) && $officeAddressCMD!=""){ ?>
				  <script>
				      document.getElementById("officeAddressCMD").value = "<?php echo str_replace("\n", '\n', $officeAddressCMD );  ?>";
				      document.getElementById("officeAddressCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'officeAddressCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Tel No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='telNoCMD' id='telNoCMD'         tip="Please enter your father's office phone number."   value=''  validate="validateInteger" caption="number" minlength="4" maxlength="14" />
				<?php if(isset($telNoCMD) && $telNoCMD!=""){ ?>
				  <script>
				      document.getElementById("telNoCMD").value = "<?php echo str_replace("\n", '\n', $telNoCMD );  ?>";
				      document.getElementById("telNoCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'telNoCMD_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Fax No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='faxNoCMD' id='faxNoCMD'         tip="Please enter your father's office fax number."   value=''  validate="validateInteger" caption="fax number" minlength="4" maxlength="14" />
				<?php if(isset($faxNoCMD) && $faxNoCMD!=""){ ?>
				  <script>
				      document.getElementById("faxNoCMD").value = "<?php echo str_replace("\n", '\n', $faxNoCMD );  ?>";
				      document.getElementById("faxNoCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'faxNoCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>E-mail: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='emailFatherCMD' id='emailFatherCMD'         tip="Please enter your father's office email id."   value='' validate="validateEmail" caption="email" minlength="1" maxlength="50"  />
				<?php if(isset($emailFatherCMD) && $emailFatherCMD!=""){ ?>
				  <script>
				      document.getElementById("emailFatherCMD").value = "<?php echo str_replace("\n", '\n', $emailFatherCMD );  ?>";
				      document.getElementById("emailFatherCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emailFatherCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Personal Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Are you an NRI ?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='nriCMD' id='nriCMD0'   value='Yes'     onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='nriCMD' id='nriCMD1'   value='No'  checked   onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($nriCMD) && $nriCMD!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["nriCMD"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $nriCMD;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nriCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Employment Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Are you employed ?: </label>
				<div class='fieldBoxLarge'>
				<input onClick="isEmployed(1);" type='radio' name='employedCMD' id='employedCMD0'   value='Yes'  checked   onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input onClick="isEmployed(0);" type='radio' name='employedCMD' id='employedCMD1'   value='No'     onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($employedCMD) && $employedCMD!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["employedCMD"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $employedCMD;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'employedCMD_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Total Experience (No. of yrs.): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='totalExpCMD' id='totalExpCMD'    required='true'     tip="Please enter the total years of experience you have. Enter the experience in years only."   value='' validate="validateInteger" caption="years" minlength="1" maxlength="2"  />
				<?php if(isset($totalExpCMD) && $totalExpCMD!=""){ ?>
				  <script>
                                      document.getElementById("totalExpCMD").disabled = false;
				      document.getElementById("totalExpCMD").value = "<?php echo str_replace("\n", '\n', $totalExpCMD );  ?>";
				      document.getElementById("totalExpCMD").style.color = "";
                                      document.getElementById("employedCMD0").checked = true;
				      document.getElementById("totalExpCMD").setAttribute('required','true');
				  </script>
				<?php }else if(isset($agreeToTermsCMD) && $agreeToTermsCMD!=""){ ?>
				  <script>
                                      document.getElementById("totalExpCMD").disabled = true;
                                      document.getElementById("employedCMD1").checked = true;
				      document.getElementById("totalExpCMD").removeAttribute('required');
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'totalExpCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
			      <div class='additionalInfoLeftCol'>
			      <label>Since when are you working ?: </label>
			      <div class="fieldBoxLarge">
			      <input type='text' name='sinceWhenWorkingCMD' id='sinceWhenWorkingCMD' readonly required='true' maxlength='10' minlength='10' validate="validateStr" caption="date" onmouseover="showTipOnline('Please select a date since when you are working.',this);" onmouseout="hidetip();"     default = 'dd/mm/yyyy' onfocus='checkTextElementOnTransition(this,"focus");' onblur='checkTextElementOnTransition(this,"blur");'  onClick="hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('sinceWhenWorkingCMD'),'sinceWhenWorkingCMD_dateImg','dd/MM/yyyy');" />&nbsp;
			      <img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='sinceWhenWorkingCMD_dateImg' onClick="hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('sinceWhenWorkingCMD'),'sinceWhenWorkingCMD_dateImg','dd/MM/yyyy'); return false;" />
					<script>
					    document.getElementById("sinceWhenWorkingCMD").style.color = "#ADA6AD";
					</script>
					<?php if(isset($sinceWhenWorkingCMD) && $sinceWhenWorkingCMD!=""){ ?>
					<script>
					    document.getElementById("sinceWhenWorkingCMD").disabled = false;
					    document.getElementById("sinceWhenWorkingCMD").value = "<?php echo str_replace("\n", '\n', $sinceWhenWorkingCMD);  ?>";
					    document.getElementById("sinceWhenWorkingCMD").style.color = "";
					    document.getElementById("sinceWhenWorkingCMD").setAttribute('required','true');
					</script>
					<?php }else if(isset($agreeToTermsCMD) && $agreeToTermsCMD!=""){ ?>
					  <script>
					      document.getElementById("sinceWhenWorkingCMD").disabled = true;
					      document.getElementById("employedCMD1").checked = true;
					      document.getElementById("sinceWhenWorkingCMD_dateImg").onclick = function(){return false;};
					      document.getElementById("sinceWhenWorkingCMD_dateImg").style.cursor = '';
					      document.getElementById("sinceWhenWorkingCMD").removeAttribute('required');
					  </script>

				      <?php } ?>
			      <div style='display:none'><div class='errorMsg' id= 'sinceWhenWorkingCMD_error'></div></div>
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
					$addressFieldName = 'orgnAddressCMD'.$workCompanyKey;
					$addressFieldValue = $$addressFieldName;
					$j++;

			?>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Address of <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input style="width:590px;" type='text' name='<?php echo $addressFieldName; ?>' id='<?php echo $addressFieldName; ?>'  validate="validateStr"   required="true"   caption="office address"   minlength="2"   maxlength="250"     tip="Please enter your office address."   value=''   />
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
				<h3 class="upperCase">Education Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Subjects/Area of Specialisation in 10th: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class10SubjectsCMD' id='class10SubjectsCMD'     validate="validateStr"  caption="subjects"   minlength="1"   maxlength="200"     tip="Please enter the major subjects/area of specialisation in your 10th Class."   value=''   />
				<?php if(isset($class10SubjectsCMD) && $class10SubjectsCMD!=""){ ?>
				  <script>
				      document.getElementById("class10SubjectsCMD").value = "<?php echo str_replace("\n", '\n', $class10SubjectsCMD );  ?>";
				      document.getElementById("class10SubjectsCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10SubjectsCMD_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Subjects/Area of Specialisation in 12th: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12SubjectsCMD' id='class12SubjectsCMD'    validate="validateStr"  caption="subjects"   minlength="1"   maxlength="200"       tip="Please enter the major subjects/area of specialisation in your 12th Class."   value=''   />
				<?php if(isset($class12SubjectsCMD) && $class12SubjectsCMD!=""){ ?>
				  <script>
				      document.getElementById("class12SubjectsCMD").value = "<?php echo str_replace("\n", '\n', $class12SubjectsCMD );  ?>";
				      document.getElementById("class12SubjectsCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SubjectsCMD_error'></div></div>
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
				<label> Subjects/Area of Specialisation in <?php echo $graduationCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradSubjectsCMD' id='gradSubjectsCMD'     validate="validateStr"  caption="specialization"   minlength="1"   maxlength="200"      tip="Please enter the major subjects/area of specialisation in your <?php echo $graduationCourseName;?>."   value=''   />
				<?php if(isset($gradSubjectsCMD) && $gradSubjectsCMD!=""){ ?>
				  <script>
				      document.getElementById("gradSubjectsCMD").value = "<?php echo str_replace("\n", '\n', $gradSubjectsCMD );  ?>";
				      document.getElementById("gradSubjectsCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSubjectsCMD_error'></div></div>
				</div>
				</div>
			</li>

			<?php
			if(count($otherCourses)>0) {
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$subjects = 'otherCourseSubject_mul_'.$otherCourseId;
					$subjectsVal = $$subjects;
			?>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Subjects/Area of Specialisation in <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='<?php echo $subjects; ?>' id='<?php echo $subjects; ?>'  validate="validateStr"  caption="specialization"   minlength="1"   maxlength="200"     tip="Please enter the major subjects/area of specialisation in your <?php echo $otherCourseName;?>."   value=''   />
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

			<?php
				}
			}
			?>


			<li>
				<h3 class="upperCase">Other Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Do you require Hostel Accommodation ?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='hostelCMD' id='hostelCMD0'   value='Yes'    onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='hostelCMD' id='hostelCMD1'   value='No'  checked   onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($hostelCMD) && $hostelCMD!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["hostelCMD"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $hostelCMD;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hostelCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Special Achievements (If Any): </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input type='text' name='acheivementsCMD' id='acheivementsCMD'  style="width:590px;"     tip="Mention in one line your special achivements."   value='' validate="validateStr" caption="achievements" minlength="1" maxlength="100"  />
				<?php if(isset($acheivementsCMD) && $acheivementsCMD!=""){ ?>
				  <script>
				      document.getElementById("acheivementsCMD").value = "<?php echo str_replace("\n", '\n', $acheivementsCMD );  ?>";
				      document.getElementById("acheivementsCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'acheivementsCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Extra Curricular Activities/Hobbies: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input type='text' name='extraCMD' id='extraCMD' style="width:590px;"    tip="Mention in one line your extra curricular activities/hobbies."   value=''  validate="validateStr" caption="extra curricular activities" minlength="1" maxlength="100"  />
				<?php if(isset($extraCMD) && $extraCMD!=""){ ?>
				  <script>
				      document.getElementById("extraCMD").value = "<?php echo str_replace("\n", '\n', $extraCMD );  ?>";
				      document.getElementById("extraCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'extraCMD_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Why you want to join the programme of PGDM?: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input type='text' name='whyJoinCMD' id='whyJoinCMD' style="width:590px;" tip="Write a brief note explaining why you want to join PGDM."   value=''  validate="validateStr" caption="why you want to join" minlength="1" maxlength="150"  />
				<?php if(isset($whyJoinCMD) && $whyJoinCMD!=""){ ?>
				  <script>
				      document.getElementById("whyJoinCMD").value = "<?php echo str_replace("\n", '\n', $whyJoinCMD );  ?>";
				      document.getElementById("whyJoinCMD").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'whyJoinCMD_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				I declare that the particulars given above are correct to the best of my knowledge and belief. I understand that admission on the basis of incorrect and misleading information shall be cancelled at any stage. I will, on admission, submit to the rules and discipline of CMD. I hold myself responsible for the dues and prompt payment of fees, if selected. I have noted that fees once paid are not refundable, under any circumstances.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsCMD' id='agreeToTermsCMD' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above" />&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsCMD) && $agreeToTermsCMD!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsCMD"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsCMD);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsCMD_error'></div></div>


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

  function isEmployed(val){
      if(val==1){
	  $('totalExpCMD').disabled = false;
	  $('totalExpCMD').setAttribute('required','true');

	  $('sinceWhenWorkingCMD').disabled = false;
	  $('sinceWhenWorkingCMD').setAttribute('required','true');

	  $('sinceWhenWorkingCMD_dateImg').onclick = function(){hidetip(); var cal = new CalendarPopup('calendardiv'); cal.select($('sinceWhenWorkingCMD'),'sinceWhenWorkingCMD_dateImg','dd/MM/yyyy'); return false;};
	  $('sinceWhenWorkingCMD_dateImg').style.cursor = 'pointer';
      }
      else if(val==0){
	  $('totalExpCMD').value = '';
	  $('totalExpCMD').disabled = true;
	  $('totalExpCMD').removeAttribute('required');
	  $('totalExpCMD_error').innerHTML = '';
	  $('totalExpCMD_error').parentNode.style.display = 'none';

	  $('sinceWhenWorkingCMD').value = '';
	  $('sinceWhenWorkingCMD').disabled = true;
	  $('sinceWhenWorkingCMD').removeAttribute('required');
	  $('sinceWhenWorkingCMD_error').innerHTML = '';
	  $('sinceWhenWorkingCMD_error').parentNode.style.display = 'none';

	  $('sinceWhenWorkingCMD_dateImg').onclick = function(){return false;};
	  $('sinceWhenWorkingCMD_dateImg').style.cursor = '';
      }
  }


  </script>
