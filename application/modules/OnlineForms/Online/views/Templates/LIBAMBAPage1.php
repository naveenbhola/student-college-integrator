<div class='formChildWrapper'> 
	<div class='formSection'>
		<ul> 
	
			<li>
				<div class='additionalInfoLeftCol'>
				<label>XAT ID: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatIdLIBA' id='xatIdLIBA'  validate="validateStr"   required="true"   caption="XAT ID"   minlength="2"   maxlength="30"     tip="Enter the XAT ID in this field. If you have not appeared for XAT, enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($xatIdLIBA) && $xatIdLIBA!=""){ ?>
				  <script>
				      document.getElementById("xatIdLIBA").value = "<?php echo str_replace("\n", '\n', $xatIdLIBA );  ?>";
				      document.getElementById("xatIdLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatIdLIBA_error'></div></div>
				</div>
				</div>

				<div class="additionalInfoRightCol">
				    <label style="font-weight:normal">CAT ID: </label>
				    <div class='fieldBoxLarge'>
				    <input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"  required="true" allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Enter the CAT ID in this field. If you have not appeared for CAT, enter <b>NA</b>."   value=''  />
				    <?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
					    <script>
					    document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
					    document.getElementById("catRollNumberAdditional").style.color = "";
					    </script>
					  <?php } ?>
				    <div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
				    </div>
				</div>

			</li>
                       <?php if($action != 'updateScore'):?>
			<li>

				<div class='additionalInfoLeftCol'>
				<label>Mother tongue: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherTongueLIBA' id='motherTongueLIBA'  validate="validateStr"   required="true"   caption="mother tongue"   minlength="1"   maxlength="50"     tip="Enter your mother tongue. It is usually the language that you have grown up speaking."   value=''   />
				<?php if(isset($motherTongueLIBA) && $motherTongueLIBA!=""){ ?>
				  <script>
				      document.getElementById("motherTongueLIBA").value = "<?php echo str_replace("\n", '\n', $motherTongueLIBA );  ?>";
				      document.getElementById("motherTongueLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherTongueLIBA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Domicile: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='domicileLIBA' id='domicileLIBA'  validate="validateStr"   required="true"   caption="domicile"   minlength="1"   maxlength="50"     tip="Enter the state of your domicile or native place i.e. the state where you belong to. For example, if you are a domicile of Maharashtra, enter Maharashtra."   value=''   />
				<?php if(isset($domicileLIBA) && $domicileLIBA!=""){ ?>
				  <script>
				      document.getElementById("domicileLIBA").value = "<?php echo str_replace("\n", '\n', $domicileLIBA );  ?>";
				      document.getElementById("domicileLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'domicileLIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Religion: </label>
				<div class='fieldBoxLarge'>
				<select onchange="religionCheck();" style="width:147px;" name='religionLIBA' id='religionLIBA'    tip="Please select your Religion"     required="true"    onmouseover="showTipOnline('Please select your Religion',this);" onmouseout="hidetip();" >
				      <option value='HINDU' selected>HINDU</option><option value='Catholic Christian' >Catholic Christian</option><option value='Other Christian' >Other Christian</option><option value='Dalit Christian' >Dalit Christian</option><option value='Muslim' >Muslim</option><option value='Jain' >Jain</option><option value='Sikh' >Sikh</option><option value='Others' >Others</option>
				</select>
				<?php if(isset($religionLIBA) && $religionLIBA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("religionLIBA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $religionLIBA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'religionLIBA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Other Religion: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='otherReligionLIBA' id='otherReligionLIBA'     disabled  tip="Please enter your Religion"   value='' validate="validateStr"    caption="religion"   minlength="1"   maxlength="100"   />
				<?php if(isset($otherReligionLIBA) && $otherReligionLIBA!=""){ ?>
				  <script>
				      document.getElementById("otherReligionLIBA").disabled = false;
				      document.getElementById("otherReligionLIBA").value = "<?php echo str_replace("\n", '\n', $otherReligionLIBA );  ?>";
				      document.getElementById("otherReligionLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherReligionLIBA_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Community: </label>
				<div class='fieldBoxLarge'>
				<select onchange="communityCheck();" name='communityLIBA' id='communityLIBA'    tip="Please select your Community"     required="true"    onmouseover="showTipOnline('Please select your Community',this);" onmouseout="hidetip();" >
				    <option value='Forward Class' selected>Forward Class</option><option value='Backward Caste' >Backward Caste</option><option value='Most Backward Caste' >Most Backward Caste</option><option value='Schedule Caste' >Schedule Caste</option><option value='Schedule Tribe' >Schedule Tribe</option><option value='Catholic Dalit' >Catholic Dalit</option><option value='Others' >Others</option>
				</select>
				<?php if(isset($communityLIBA) && $communityLIBA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("communityLIBA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $communityLIBA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'communityLIBA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Other Community: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='otherCommunityLIBA' id='otherCommunityLIBA'   disabled      tip="Please enter your Community"   value=''  validate="validateStr"    caption="community"   minlength="1"   maxlength="100"  />
				<?php if(isset($otherCommunityLIBA) && $otherCommunityLIBA!=""){ ?>
				  <script>
				      document.getElementById("otherCommunityLIBA").disabled = false;
				      document.getElementById("otherCommunityLIBA").value = "<?php echo str_replace("\n", '\n', $otherCommunityLIBA );  ?>";
				      document.getElementById("otherCommunityLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherCommunityLIBA_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<h3 class="upperCase">Additional Family Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherOrganizationLIBA' id='fatherOrganizationLIBA'  validate="validateStr"    caption="father organization"   minlength="1"   maxlength="100"     tip="Enter the name of organization where your father works."   value=''   />
				<?php if(isset($fatherOrganizationLIBA) && $fatherOrganizationLIBA!=""){ ?>
				  <script>
				      document.getElementById("fatherOrganizationLIBA").value = "<?php echo str_replace("\n", '\n', $fatherOrganizationLIBA );  ?>";
				      document.getElementById("fatherOrganizationLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherOrganizationLIBA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Father's Designation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherDesignationLIBA' id='fatherDesignationLIBA'  validate="validateStr"    caption="father designation"   minlength="1"   maxlength="100"     tip="Type in the designation of your father here or the exact post he holds, such as Executive Engineer in Indian Oil."   value=''   />
				<?php if(isset($fatherDesignationLIBA) && $fatherDesignationLIBA!=""){ ?>
				  <script>
				      document.getElementById("fatherDesignationLIBA").value = "<?php echo str_replace("\n", '\n', $fatherDesignationLIBA );  ?>";
				      document.getElementById("fatherDesignationLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherDesignationLIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherOrganizationLIBA' id='motherOrganizationLIBA'  validate="validateStr"    caption="mother organization"   minlength="1"   maxlength="100"     tip="Enter the name of organization where your mother works."   value=''   />
				<?php if(isset($motherOrganizationLIBA) && $motherOrganizationLIBA!=""){ ?>
				  <script>
				      document.getElementById("motherOrganizationLIBA").value = "<?php echo str_replace("\n", '\n', $motherOrganizationLIBA );  ?>";
				      document.getElementById("motherOrganizationLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherOrganizationLIBA_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's Designation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherDesignationLIBA' id='motherDesignationLIBA'  validate="validateStr"    caption="mother designation"   minlength="1"   maxlength="100"     tip="Type in the designation of your mother here or the exact post she holds, such as School Teacher in Kendriya Vidyalaya."   value=''   />
				<?php if(isset($motherDesignationLIBA) && $motherDesignationLIBA!=""){ ?>
				  <script>
				      document.getElementById("motherDesignationLIBA").value = "<?php echo str_replace("\n", '\n', $motherDesignationLIBA );  ?>";
				      document.getElementById("motherDesignationLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherDesignationLIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Annual Family Income: </label>
				<div class='fieldBoxLarge'>
				<select class="textboxLarge" name='familyIncomeLIBA' id='familyIncomeLIBA'    tip="Enter your approximate annual family income."     required="true"    onmouseover="showTipOnline('Enter your approximate annual family income.',this);" onmouseout="hidetip();" ><option value='Less than Rs.2 Lakh' selected>Less than Rs.2 Lakh</option><option value='Rs.2 to 4 Lakh' >Rs.2 Lakh to 4 Lakh</option><option value='Rs.4 Lakh to 6 Lakh' >Rs.4 Lakh to 6 Lakh</option><option value='More than Rs.6 Lakh' >More than Rs.6 Lakh</option></select>
				<?php if(isset($familyIncomeLIBA) && $familyIncomeLIBA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("familyIncomeLIBA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $familyIncomeLIBA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'familyIncomeLIBA_error'></div></div>
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
				<h3 class="upperCase">Education (additional information about your education)</h3>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Stream: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradStreamLIBA' id='gradStreamLIBA'  validate="validateStr"   required="true"   caption="stream"   minlength="1"   maxlength="250"     tip="Enter education stream or specialization. For example, if you did B.A. Honors in Economics, your stream will be Economics, If you did BTECH in Mechanical Engineering, you stream will be Mechanical."   value=''   />
				<?php if(isset($gradStreamLIBA) && $gradStreamLIBA!=""){ ?>
				  <script>
				      document.getElementById("gradStreamLIBA").value = "<?php echo str_replace("\n", '\n', $gradStreamLIBA );  ?>";
				      document.getElementById("gradStreamLIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradStreamLIBA_error'></div></div>
				</div>
				</div>
			</li>

			<?php
			if(count($otherCourses)>0) {
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
				
					$streamFrom = 'otherCourseStream_mul_'.$otherCourseId;
					$streamFromVal = $$streamFrom;
			?>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName; ?> Stream: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='<?php echo $streamFrom; ?>' id='<?php echo $streamFrom; ?>'  validate="validateStr"   required="true"   caption="stream"   minlength="1"   maxlength="250"     tip="Enter education stream or specialization. For example, if you did B.A. Honors in Economics, your stream will be Economics, If you did BTECH in Mechanical Engineering, you stream will be Mechanical."   value=''   />
				<?php if(isset($streamFromVal) && $streamFromVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $streamFrom; ?>").value = "<?php echo str_replace("\n", '\n', $streamFromVal );  ?>";
				      document.getElementById("<?php echo $streamFrom; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $streamFrom; ?>_error'></div></div>
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

                        for($i=0;$i<count($workCompaniesExpFrom);$i++){
                                $durationFrom = $workCompaniesExpFrom['_mul_'.$i];
                                $durationTo = trim($workCompaniesExpTill['_mul_'.$i])?$workCompaniesExpTill['_mul_'.$i]:'Till date';
                                if($durationFrom) {
                                        $startDate = getStandardDate($durationFrom);
                                        $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
                                        $totalDuration = getTimeDifference($startDate,$endDate);
                                        $companyWorkExDuration['durationLIBA'.$i] = ($totalDuration['months']<0)?0:$totalDuration['months'];
                                }
                        }
			
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$grossSalaryFieldName = 'annualSalaryLIBA'.$workCompanyKey;
					$grossSalaryFieldValue = $$grossSalaryFieldName;
					$fullTimeFieldName = 'fullTimeLIBA'.$workCompanyKey;
					$fullTimeFieldValue = $$fullTimeFieldName;

                                        $durationFieldName = 'durationLIBA'.$workCompanyKey;
                                        $durationValue = $companyWorkExDuration['durationLIBA'.$j];

					$j++;

			?>
			<input type='hidden' name='<?php echo $durationFieldName; ?>' id='<?php echo $durationFieldName; ?>' value='<?=$durationValue?>'  />

			<li>
				<?php if($j==1){ ?><h3 class="upperCase">Employment (additional information about employment)</h3><?php } ?>
				<div class='additionalInfoLeftCol'>
				<label>Annual Salary at <?php echo $workCompany; ?>: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='<?php echo $grossSalaryFieldName; ?>' id='<?php echo $grossSalaryFieldName; ?>' tip="Enter you annual salary at this company."  validate="validateFloat"  caption="salary"   minlength="2"   maxlength="10" value=''  />
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
				<label>Was your job at <?php echo $workCompany; ?> a full time job?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='<?=$fullTimeFieldName?>' id='<?=$fullTimeFieldName?>0'   value='Yes' checked    onmouseover="showTipOnline('Please specify if this was a full time job. If it was, select Yes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please specify if this was a full time job. If it was, select Yes',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='<?=$fullTimeFieldName?>' id='<?=$fullTimeFieldName?>1'   value='No'     onmouseover="showTipOnline('Please specify if this was a full time job. If it was, select Yes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please specify if this was a full time job. If it was, select Yes',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($fullTimeFieldValue) && $fullTimeFieldValue!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["<?=$fullTimeFieldName?>"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $fullTimeFieldValue;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?=$fullTimeFieldName?>_error'></div></div>
				</div>
				</div>
			</li>
			

			<?php
				}
			}
			?>


			<li>
				<div class='additionalInfoLeftCol' style="width:700px;">
				<label>Select and upload your scanned signature: </label>
				<div class='fieldBoxLarge' style="width:310px;">
				<input type='file' name='userApplicationfile[]' id='signatureImageLIBA'  size="30" required="true"  onmouseover="showTipOnline('Your signature is required with this form. If you do not have an electronic copy of your signature, sign on a paper and scan it. Then upload the scanned image file here.',this);"  onmouseout = "hidetip();" />
				<input type='hidden' name='signatureImageLIBAValid' value='extn:jpg,jpeg,png|size:5'>
				<br/><span class="imageSizeInfo">(Image dimention :4.5 X 3.5 cm, Image Size : Maximum 2 MB)</span>
				<div style='display:none'><div class='errorMsg' id= 'signatureImageLIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Will you require hostel if selected to this institute?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='hostelRequiredLIBA' id='hostelRequiredLIBA0'   value='Yes'     onmouseover="showTipOnline('Enter your accomodation preference. Will you require a hostel if selected to this Institute?',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your accomodation preference. Will you require a hostel if selected to this Institute?',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='hostelRequiredLIBA' id='hostelRequiredLIBA1'   value='No'  checked   onmouseover="showTipOnline('Enter your accomodation preference. Will you require a hostel if selected to this Institute?',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your accomodation preference. Will you require a hostel if selected to this Institute?',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($hostelRequiredLIBA) && $hostelRequiredLIBA!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["hostelRequiredLIBA"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $hostelRequiredLIBA;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hostelRequiredLIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Are you a Dalit Candidate?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='dalitLIBA' id='dalitLIBA0'   value='Yes'     onmouseover="showTipOnline('If you are a Dalit candidate, please select this option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you are a Dalit candidate, please select this option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='dalitLIBA' id='dalitLIBA1'   value='No'  checked   onmouseover="showTipOnline('If you are a Dalit candidate, please select this option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you are a Dalit candidate, please select this option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($dalitLIBA) && $dalitLIBA!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["dalitLIBA"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $dalitLIBA;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'dalitLIBA_error'></div></div>
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
				I hereby declare that the particulars given in this application form are true and correct, and they will be supported by original documents when asked for. I am also fully aware that in the event of any information being found incorrect or misleading, my candidature shall be liable to cancellation by the Institute at any time.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsLIBA' id='agreeToTermsLIBA' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsLIBA) && $agreeToTermsLIBA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsLIBA"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsLIBA);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsLIBA_error'></div></div>


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

  function religionCheck() {
	  var selectedPrefObj = document.getElementById('religionLIBA'); 
	  var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	  if(selectedPref == 'Others'){
		$('otherReligionLIBA').disabled = false;
		$('otherReligionLIBA').setAttribute('required','true');
	  }
	  else{
		$('otherReligionLIBA').value = '';
		$('otherReligionLIBA').disabled = true;
		$('otherReligionLIBA').removeAttribute('required');
		$('otherReligionLIBA_error').innerHTML = '';
		$('otherReligionLIBA_error').parentNode.style.display = 'none';
	  }
	  return true;
  }

  function communityCheck() {
	  var selectedPrefObj = document.getElementById('communityLIBA'); 
	  var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	  if(selectedPref == 'Others'){
		$('otherCommunityLIBA').disabled = false;
		$('otherCommunityLIBA').setAttribute('required','true');
	  }
	  else{
		$('otherCommunityLIBA').value = '';
		$('otherCommunityLIBA').disabled = true;
		$('otherCommunityLIBA').removeAttribute('required');
		$('otherCommunityLIBA_error').innerHTML = '';
		$('otherCommunityLIBA_error').parentNode.style.display = 'none';
	  }
	  return true;
  }

  </script>
