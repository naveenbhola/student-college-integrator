<div class='formChildWrapper'> 
	<div class='formSection'>
		<ul>
                <?php if($action != 'updateScore'):?>
			<li>
            	<h3 class="upperCase">Course Preference</h3>
                <div style="margin-bottom: 10px;"><b>Please indicate your choice of Programmes in order of preference:</b></div>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label style="width:600px">Post Graduate Diploma in Management: PGDM (2 Year Full-time) :</label>
				<div class='fieldBoxSmall'>
				<input class="textboxLarge" style="width:50px" type='text' name='prefPGDM' id='prefPGDM' blurMethod="checkValidPreference(1);" validate="validateInteger"   required="true"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for the courses. Use numbers 1 to 6 for the same."   value=''   />
				<?php if(isset($prefPGDM) && $prefPGDM!=""){ ?>
				  <script>
				      document.getElementById("prefPGDM").value = "<?php echo str_replace("\n", '\n', $prefPGDM );  ?>";
				      document.getElementById("prefPGDM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'prefPGDM_error'></div></div>
				</div>
				</div>
                </li>
                <li>
                <div class='additionalInfoLeftCol' style="width:800px">
				<label style="width:600px">Post Graduate Diploma in Management- Marketing: PGDM - Mkt. (2 Year Full-time) : </label>
				<div class='fieldBoxSmall'>
				<input class="textboxLarge" style="width:50px" type='text' name='prefPGDMMkt' id='prefPGDMMkt' blurMethod="checkValidPreference(2);" validate="validateInteger"   required="true"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for the courses. Use numbers 1 to 6 for the same."   value=''   />
				<?php if(isset($prefPGDMMkt) && $prefPGDMMkt!=""){ ?>
				  <script>
				      document.getElementById("prefPGDMMkt").value = "<?php echo str_replace("\n", '\n', $prefPGDMMkt );  ?>";
				      document.getElementById("prefPGDMMkt").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'prefPGDMMkt_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label style="width:600px">Post Graduate Diploma in Management- International Business: PGDM - IB (2 Year Full-time) : </label>
				<div class='fieldBoxSmall'>
				<input class="textboxLarge" style="width:50px" type='text' name='prefPGDMIB' id='prefPGDMIB' blurMethod="checkValidPreference(3);" validate="validateInteger"   required="true"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for the courses. Use numbers 1 to 6 for the same."   value=''   />
				<?php if(isset($prefPGDMIB) && $prefPGDMIB!=""){ ?>
				  <script>
				      document.getElementById("prefPGDMIB").value = "<?php echo str_replace("\n", '\n', $prefPGDMIB );  ?>";
				      document.getElementById("prefPGDMIB").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'prefPGDMIB_error'></div></div>
				</div>
				</div>
                </li>
                <li>
                <div class='additionalInfoLeftCol' style="width:800px">
				<label style="width:600px">Post Graduate Diploma in Management- Banking &amp; Financial Services: PGDM - BFS (2 Year Full-time) : </label>
				<div class='fieldBoxSmall'>
				<input class="textboxLarge" style="width:50px" type='text' name='prefPGDMBFS' id='prefPGDMBFS' blurMethod="checkValidPreference(4);" validate="validateInteger"   required="true"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for the courses. Use numbers 1 to 6 for the same."   value=''   />
				<?php if(isset($prefPGDMBFS) && $prefPGDMBFS!=""){ ?>
				  <script>
				      document.getElementById("prefPGDMBFS").value = "<?php echo str_replace("\n", '\n', $prefPGDMBFS );  ?>";
				      document.getElementById("prefPGDMBFS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'prefPGDMBFS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label style="width:600px">Executive Post Graduate Diploma in Management: EX PGDM (3 Year Part-time) : </label>
				<div class='fieldBoxSmall'>
				<input class="textboxLarge" style="width:50px" type='text' name='prefPGDMEx' id='prefPGDMEx' blurMethod="checkValidPreference(5);" validate="validateInteger"   required="true"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for the courses. Use numbers 1 to 6 for the same."   value=''   />
				<?php if(isset($prefPGDMEx) && $prefPGDMEx!=""){ ?>
				  <script>
				      document.getElementById("prefPGDMEx").value = "<?php echo str_replace("\n", '\n', $prefPGDMEx );  ?>";
				      document.getElementById("prefPGDMEx").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'prefPGDMEx_error'></div></div>
				</div>
				</div>
                </li>
                <li>
                <div class='additionalInfoLeftCol' style="width:800px">
				<label style="width:600px">Executive Post Graduate Diploma in Management - Marketing: EX PGDM - Mkt. (3 Year Part-time) : </label>
				<div class='fieldBoxSmall'>
				<input class="textboxLarge" style="width:50px" type='text' name='prefPGDMExMkt' id='prefPGDMExMkt' blurMethod="checkValidPreference(6);" validate="validateInteger"   required="true"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for the courses. Use numbers 1 to 6 for the same."   value=''   />
				<?php if(isset($prefPGDMExMkt) && $prefPGDMExMkt!=""){ ?>
				  <script>
				      document.getElementById("prefPGDMExMkt").value = "<?php echo str_replace("\n", '\n', $prefPGDMExMkt );  ?>";
				      document.getElementById("prefPGDMExMkt").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'prefPGDMExMkt_error'></div></div>
				</div>
				</div>
			</li>
                       <?php endif;?>
			<li>
            	<h3 class="upperCase">Qualifying Exams</h3>
				<div class='additionalInfoLeftCol'>
				<label>CAT Score (Percentile) : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAIM' id='catScoreAIM'  validate="validateFloat"   required="true"   caption="CAT Score (Percentile) "   minlength="1"   maxlength="5"     tip="Enter your CAT Score in percentile. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($catScoreAIM) && $catScoreAIM!=""){ ?>
				  <script>
				      document.getElementById("catScoreAIM").value = "<?php echo str_replace("\n", '\n', $catScoreAIM );  ?>";
				      document.getElementById("catScoreAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAIM_error'></div></div>
				</div>
				</div>
                <div class='additionalInfoRightCol'>
				<label>Enrollment No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAIM' id='catRollNumberAIM'   required="true"  validate="validateStr" caption="Enrollment No " minlength="2"   maxlength="50"    tip="Enter your CAT ID. Enter NA if you haven't taken the test."   value=''   />
				<?php if(isset($catRollNumberAIM) && $catRollNumberAIM!=""){ ?>
				  <script>
				      document.getElementById("catRollNumberAIM").value = "<?php echo str_replace("\n", '\n', $catRollNumberAIM );  ?>";
				      document.getElementById("catRollNumberAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catRollNumberAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>GMAT Score (Composite) : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAIM' id='gmatScoreAIM'  validate="validateFloat"   required="true"   caption="GMAT Score (Composite) "   minlength="1"   maxlength="5"     tip="Enter your GMAT Score. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($gmatScoreAIM) && $gmatScoreAIM!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAIM").value = "<?php echo str_replace("\n", '\n', $gmatScoreAIM );  ?>";
				      document.getElementById("gmatScoreAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Enrollment No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAIM' id='gmatRollNumberAIM'  validate="validateStr"   required="true"   caption="Enrollment No "   minlength="2"   maxlength="50"     tip="Enter your GMAT ID. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($gmatRollNumberAIM) && $gmatRollNumberAIM!=""){ ?>
				  <script>
				      document.getElementById("gmatRollNumberAIM").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAIM );  ?>";
				      document.getElementById("gmatRollNumberAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>MAT Score (Composite) : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAIM' id='matScoreAIM'  validate="validateFloat"   required="true"   caption="MAT Score (Composite) "   minlength="1"   maxlength="5"     tip="Enter your MAT Score. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($matScoreAIM) && $matScoreAIM!=""){ ?>
				  <script>
				      document.getElementById("matScoreAIM").value = "<?php echo str_replace("\n", '\n', $matScoreAIM );  ?>";
				      document.getElementById("matScoreAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAIM_error'></div></div>
				</div>
				</div>
			
            	<div class='additionalInfoRightCol'>
				<label>Enrollment No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAIM' id='matRollNumberAIM'  validate="validateStr"   required="true"   caption="Enrollment No"   minlength="2"   maxlength="50"     tip="Enter your MAT ID. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($matRollNumberAIM) && $matRollNumberAIM!=""){ ?>
				  <script>
				      document.getElementById("matRollNumberAIM").value = "<?php echo str_replace("\n", '\n', $matRollNumberAIM );  ?>";
				      document.getElementById("matRollNumberAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matRollNumberAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>XAT Score (Composite): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAIM' id='xatScoreAIM'  validate="validateFloat"   required="true"   caption="XAT score"   minlength="1"   maxlength="5"     tip="Enter your XAT Score. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($xatScoreAIM) && $xatScoreAIM!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAIM").value = "<?php echo str_replace("\n", '\n', $xatScoreAIM );  ?>";
				      document.getElementById("xatScoreAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Enrollment No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAIM' id='xatRollNumberAIM'  validate="validateStr"   required="true"   caption="Enrollment No"   minlength="2"   maxlength="50"     tip="Enter your XAT ID. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($xatRollNumberAIM) && $xatRollNumberAIM!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberAIM").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAIM );  ?>";
				      document.getElementById("xatRollNumberAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAIM_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>CMAT Score (Composite) : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"   required="true"   caption="CMAT Score (Composite) "   minlength="1"   maxlength="5"     tip="Enter your CMAT Score. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional);  ?>";
				      document.getElementById("cmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Enrollment No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"   required="true"   caption="Enrollment No "   minlength="2"   maxlength="50"     tip="Enter your CMAT ID. Enter NA if you haven't taken the test."   value=''    allowNA = 'true' />
				<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional);  ?>";
				      document.getElementById("cmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
				</div>
				</div>
			</li>
			
<?php if($action != 'updateScore'):?>
			<li>
            	<h3 class="upperCase">Personal Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Salutation: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='salutationAIM' id='salutationAIM0'   value='Mr.'  checked   onmouseover="showTipOnline('Please select your salutation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your salutation.',this);" onmouseout="hidetip();" >Mr.</span>&nbsp;&nbsp;
				<input type='radio' name='salutationAIM' id='salutationAIM1'   value='Ms.'     onmouseover="showTipOnline('Please select your salutation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your salutation.',this);" onmouseout="hidetip();" >Ms.</span>&nbsp;&nbsp;
				<input type='radio' name='salutationAIM' id='salutationAIM2'   value='Mrs.'     onmouseover="showTipOnline('Please select your salutation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your salutation.',this);" onmouseout="hidetip();" >Mrs.</span>&nbsp;&nbsp;
				<?php if(isset($salutationAIM) && $salutationAIM!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["salutationAIM"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $salutationAIM;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'salutationAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Blood Group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='bloodGroupAIM' id='bloodGroupAIM'  minlength="1" maxlength="10"       tip="Please enter your Blood Group."   value=''   />
				<?php if(isset($bloodGroupAIM) && $bloodGroupAIM!=""){ ?>
				  <script>
				      document.getElementById("bloodGroupAIM").value = "<?php echo str_replace("\n", '\n', $bloodGroupAIM );  ?>";
				      document.getElementById("bloodGroupAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'bloodGroupAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
            	<h3 class="upperCase">Family Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's Tel No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherTelephoneNumber' id='fatherTelephoneNumber'   validate="validateInteger"   required="true"   caption="number"   minlength="8"   maxlength="15"      tip="Please enter the phone number of your father. <?php echo $NAText; ?>" allowNA="true"  value=''   />
				<?php if(isset($fatherTelephoneNumber) && $fatherTelephoneNumber!=""){ ?>
				  <script>
				      document.getElementById("fatherTelephoneNumber").value = "<?php echo str_replace("\n", '\n', $fatherTelephoneNumber );  ?>";
				      document.getElementById("fatherTelephoneNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherTelephoneNumber_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Father's Mob No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherMobileNumberAIM' id='fatherMobileNumberAIM'  validate="validateMobileInteger"   required="true"   caption="Father's Mob No."   minlength="8"   maxlength="15"     tip="Please enter the mobile number of your father."   value=''   />
				<?php if(isset($fatherMobileNumberAIM) && $fatherMobileNumberAIM!=""){ ?>
				  <script>
				      document.getElementById("fatherMobileNumberAIM").value = "<?php echo str_replace("\n", '\n', $fatherMobileNumberAIM );  ?>";
				      document.getElementById("fatherMobileNumberAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherMobileNumberAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherEmailAIM' id='fatherEmailAIM'  validate="validateEmail"  required="true"  caption="email address"   minlength="2"   maxlength="200"     tip="Please enter the email id of your father. <?php echo $NAText; ?>"   value=''    allowNA = 'true' />
				<?php if(isset($fatherEmailAIM) && $fatherEmailAIM!=""){ ?>
				  <script>
				      document.getElementById("fatherEmailAIM").value = "<?php echo str_replace("\n", '\n', $fatherEmailAIM );  ?>";
				      document.getElementById("fatherEmailAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherEmailAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Mother's Mob No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherMobileAIM' id='motherMobileAIM'  validate="validateMobileInteger"   required="true"   caption="Mother's Mob No."   minlength="8"   maxlength="15"     tip="Please enter the mobile number of your mother. <?php echo $NAText; ?>" allowNA="true"  value=''   />
				<?php if(isset($motherMobileAIM) && $motherMobileAIM!=""){ ?>
				  <script>
				      document.getElementById("motherMobileAIM").value = "<?php echo str_replace("\n", '\n', $motherMobileAIM );  ?>";
				      document.getElementById("motherMobileAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherMobileAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father’s/Husband’s Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherHusbandNameAIM' id='fatherHusbandNameAIM'  validate="validateStr"    caption="Father’s/Husband’s Name"   minlength="2"   maxlength="200"     tip="Please enter Father’s/Husband’s Name"   value='' required="true"/>
				<?php if(isset($fatherHusbandNameAIM) && $fatherHusbandNameAIM!=""){ ?>
				  <script>
				      document.getElementById("fatherHusbandNameAIM").value = "<?php echo str_replace("\n", '\n', $fatherHusbandNameAIM );  ?>";
				      document.getElementById("fatherHusbandNameAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherHusbandNameAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Father’s/Mother’s/Husband’s Profession/Designation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fmhProfessionDesignationAIM' id='fmhProfessionDesignationAIM'  validate="validateStr"   required="true"   caption="Profession/Designation"   minlength="2"   maxlength="20"     tip="Please enter Father’s/Mother’s/Husband’s Profession/Designation" value=''   />
				<?php if(isset($fmhProfessionDesignationAIM) && $fmhProfessionDesignationAIM!=""){ ?>
				  <script>
				      document.getElementById("fmhProfessionDesignationAIM").value = "<?php echo str_replace("\n", '\n', $fmhProfessionDesignationAIM );  ?>";
				      document.getElementById("fmhProfessionDesignationAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fmhProfessionDesignationAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Father’s/Mother’s/Husband’s Organisation &amp; Address: </label>
				<div class='fieldBoxLarge' style="width:490px;">
				<textarea style="width:470px; height:75px" name='fatherOrganizationAIM' id='fatherOrganizationAIM'  validate="validateStr"   required="true"   caption="Organisation & Address"   minlength="2"   maxlength="300"     tip="Please enter your Father’s / Mother’s / Husband’s Organisation & Address."    ></textarea>
				<?php if(isset($fatherOrganizationAIM) && $fatherOrganizationAIM!=""){ ?>
				  <script>
				      document.getElementById("fatherOrganizationAIM").value = "<?php echo str_replace("\n", '\n', $fatherOrganizationAIM );  ?>";
				      document.getElementById("fatherOrganizationAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherOrganizationAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Tel./ Mobile No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherOrganizationMobileAIM' id='fatherOrganizationMobileAIM'  validate="validateStr"   required="true"   caption="Tel./ Mobile No."   minlength="8"   maxlength="50"     tip="Please enter your father's organization's telephone number. <?php echo $NA; ?>" allowNA="true"  value=''   />
				<?php if(isset($fatherOrganizationMobileAIM) && $fatherOrganizationMobileAIM!=""){ ?>
				  <script>
				      document.getElementById("fatherOrganizationMobileAIM").value = "<?php echo str_replace("\n", '\n', $fatherOrganizationMobileAIM );  ?>";
				      document.getElementById("fatherOrganizationMobileAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherOrganizationMobileAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='fatherOrganizationEmailAIM' id='fatherOrganizationEmailAIM'  validate="validateEmail"    caption="email"   minlength="2"   maxlength="200"  required="true" tip="Please enter your father's organization's email id. <?php echo $NAText; ?>"    value=''    allowNA = 'true' />
				<?php if(isset($fatherOrganizationEmailAIM) && $fatherOrganizationEmailAIM!=""){ ?>
				  <script>
				      document.getElementById("fatherOrganizationEmailAIM").value = "<?php echo str_replace("\n", '\n', $fatherOrganizationEmailAIM );  ?>";
				      document.getElementById("fatherOrganizationEmailAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherOrganizationEmailAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Annual Family Income: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='annualIncomeFamily' id='annualIncomeFamily'  validate="validateStr"   required="true"   caption="Annual Family Income"   minlength="1"   maxlength="10"     tip="Please enter annual income of your family."   value=''   />
				<?php if(isset($annualIncomeFamily) && $annualIncomeFamily!=""){ ?>
				  <script>
				      document.getElementById("annualIncomeFamily").value = "<?php echo str_replace("\n", '\n', $annualIncomeFamily );  ?>";
				      document.getElementById("annualIncomeFamily").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'annualIncomeFamily_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Caste: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='casteAIM' id='casteAIM'  validate="validateStr"   required="true"   caption="caste"   minlength="1"   maxlength="50"     tip="Please enter the social caste you belong to."   value=''   />
				<?php if(isset($casteAIM) && $casteAIM!=""){ ?>
				  <script>
				      document.getElementById("casteAIM").value = "<?php echo str_replace("\n", '\n', $casteAIM );  ?>";
				      document.getElementById("casteAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'casteAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
            	<h3 class="upperCase">Secondary (10) School Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Year From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10YearFromAIM' id='class10YearFromAIM'  validate="validateInteger"   required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Secondary (10)."   value=''   />
				<?php if(isset($class10YearFromAIM) && $class10YearFromAIM!=""){ ?>
				  <script>
				      document.getElementById("class10YearFromAIM").value = "<?php echo str_replace("\n", '\n', $class10YearFromAIM );  ?>";
				      document.getElementById("class10YearFromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10YearFromAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Subjects Studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class10SubjectsAIM' id='class10SubjectsAIM'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="200"   csv="true"  tip="Enter the major subjects you studies in Secondary (10)."   value=''   />
				<?php if(isset($class10SubjectsAIM) && $class10SubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("class10SubjectsAIM").value = "<?php echo str_replace("\n", '\n', $class10SubjectsAIM );  ?>";
				      document.getElementById("class10SubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10SubjectsAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10DivisionAIM' id='class10DivisionAIM'  validate="validateStr"   required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Secondary (10). <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($class10DivisionAIM) && $class10DivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("class10DivisionAIM").value = "<?php echo str_replace("\n", '\n', $class10DivisionAIM );  ?>";
				      document.getElementById("class10DivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10DivisionAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
            	<h3 class="upperCase">Sr. Secondary (10+2) School Details</h3>
            	<div class='additionalInfoLeftCol'>
				<label>Year From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12YearFromAIM' id='class12YearFromAIM'  validate="validateInteger"   required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Sr. Secondary (10+2)."   value=''   />
				<?php if(isset($class12YearFromAIM) && $class12YearFromAIM!=""){ ?>
				  <script>
				      document.getElementById("class12YearFromAIM").value = "<?php echo str_replace("\n", '\n', $class12YearFromAIM );  ?>";
				      document.getElementById("class12YearFromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12YearFromAIM_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Subjects Studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12SubjectsAIM' id='class12SubjectsAIM'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="200"  csv="true"   tip="Enter the major subjects you studies in Sr. Secondary (10+2)."   value=''   />
				<?php if(isset($class12SubjectsAIM) && $class12SubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("class12SubjectsAIM").value = "<?php echo str_replace("\n", '\n', $class12SubjectsAIM );  ?>";
				      document.getElementById("class12SubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SubjectsAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
            	<div class='additionalInfoLeftCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12DivisionAIM' id='class12DivisionAIM'  validate="validateStr"   required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Sr. Secondary (10+2). <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($class12DivisionAIM) && $class12DivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("class12DivisionAIM").value = "<?php echo str_replace("\n", '\n', $class12DivisionAIM );  ?>";
				      document.getElementById("class12DivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12DivisionAIM_error'></div></div>
				</div>
				</div>
            </li>
            <li>
            	<h3 class="upperCase">Graduation Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Type of graduation degree: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='typeOfGraduationDegree' id='typeOfGraduationDegree0'   value='Hons'  checked   onmouseover="showTipOnline('Please choose the type of your graduation degree.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please choose the type of your graduation degree.',this);" onmouseout="hidetip();" >Hons</span>&nbsp;&nbsp;
				<input type='radio' name='typeOfGraduationDegree' id='typeOfGraduationDegree1'   value='Pass'     onmouseover="showTipOnline('Please choose the type of your graduation degree.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please choose the type of your graduation degree.',this);" onmouseout="hidetip();" >Pass</span>&nbsp;&nbsp;
				<?php if(isset($typeOfGraduationDegree) && $typeOfGraduationDegree!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["typeOfGraduationDegree"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $typeOfGraduationDegree;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'typeOfGraduationDegree_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you completed your graduation?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='isGraduationCompleted' id='isGraduationCompleted0'   value='Yes'  checked   onmouseover="showTipOnline('Have you completed your graduation?',this);" onmouseout="hidetip();" onclick="toggleGraduationCompleted('Yes','No');" ></input><span  onmouseover="showTipOnline('Have you completed your graduation?',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='isGraduationCompleted' id='isGraduationCompleted1'   value='No'     onmouseover="showTipOnline('Have you completed your graduation?',this);" onmouseout="hidetip();" onclick="toggleGraduationCompleted('No','Yes');" ></input><span  onmouseover="showTipOnline('Have you completed your graduation?',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($isGraduationCompleted) && $isGraduationCompleted!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["isGraduationCompleted"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $isGraduationCompleted;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'isGraduationCompleted_error'></div></div>
				</div>
				</div>
			</li>

			<li id="graduationCompleted_Yes">
				<div class='additionalInfoLeftCol'>
				<label>Year From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYearFromAIM' id='graduationYearFromAIM'  validate="validateInteger"   required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Graduation."   value=''   />
				<?php if(isset($graduationYearFromAIM) && $graduationYearFromAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYearFromAIM").value = "<?php echo str_replace("\n", '\n', $graduationYearFromAIM );  ?>";
				      document.getElementById("graduationYearFromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYearFromAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Subjects studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='graduationSubjectsAIM' id='graduationSubjectsAIM'  validate="validateStr"   required="true"   caption="subjects "   minlength="1"   maxlength="200"  csv="true"   tip="Enter the major subjects you studies in Graduation."   value=''   />
				<?php if(isset($graduationSubjectsAIM) && $graduationSubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationSubjectsAIM").value = "<?php echo str_replace("\n", '\n', $graduationSubjectsAIM );  ?>";
				      document.getElementById("graduationSubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationSubjectsAIM_error'></div></div>
				</div>
				</div>
				
				<div class="spacer20 clearFix"></div>
				
				<div class='additionalInfoLeftCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationDivisionAIM' id='graduationDivisionAIM'  validate="validateStr"   required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Graduation. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationDivisionAIM) && $graduationDivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationDivisionAIM").value = "<?php echo str_replace("\n", '\n', $graduationDivisionAIM );  ?>";
				      document.getElementById("graduationDivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationDivisionAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li id="graduationCompleted_No">
            	<div class="semesterDetailsBox">
            	<div class='additionalInfoLeftCol'>
				<label>Graduation Year 1 - From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear1FromAIM' id='graduationYear1FromAIM'  validate="validateInteger"   required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Graduation Year 1."   value=''   />
				<?php if(isset($graduationYear1FromAIM) && $graduationYear1FromAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear1FromAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear1FromAIM );  ?>";
				      document.getElementById("graduationYear1FromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear1FromAIM_error'></div></div>
				</div>
				</div>
                
				<div class='additionalInfoRightCol'>
				<label>Graduation Year 1 - To: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear1ToAIM' id='graduationYear1ToAIM'  validate="validateInteger"   required="true"   caption="year to"   minlength="4"   maxlength="4"     tip="Enter the year when you completed your Graduation Year 1."   value=''   />
				<?php if(isset($graduationYear1ToAIM) && $graduationYear1ToAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear1ToAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear1ToAIM );  ?>";
				      document.getElementById("graduationYear1ToAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear1ToAIM_error'></div></div>
				</div>
				</div>
				
				<div class="spacer20 clearFix"></div>

				<div class='additionalInfoLeftCol'>
				<label>Subjects studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='graduationYear1SubjectsAIM' id='graduationYear1SubjectsAIM'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="200"  csv="true"   tip="Enter the major subjects you studies in Graduation Year 1."   value=''   />
				<?php if(isset($graduationYear1SubjectsAIM) && $graduationYear1SubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear1SubjectsAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear1SubjectsAIM );  ?>";
				      document.getElementById("graduationYear1SubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear1SubjectsAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear1DivisionAIM' id='graduationYear1DivisionAIM'  validate="validateStr"   required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Graduation Year 1. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear1DivisionAIM) && $graduationYear1DivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear1DivisionAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear1DivisionAIM );  ?>";
				      document.getElementById("graduationYear1DivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear1DivisionAIM_error'></div></div>
				</div>
				</div>
				
				<div class="spacer20 clearFix"></div>

				<div class='additionalInfoLeftCol'>
				<label>% of Marks : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear1MarksAIM' id='graduationYear1MarksAIM'  validate="validateFloat"   required="true"   caption="% of Marks "   minlength="1"   maxlength="5"     tip="Enter the % of marks you obtained in your Graduation Year 1.
"   value=''   />
				<?php if(isset($graduationYear1MarksAIM) && $graduationYear1MarksAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear1MarksAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear1MarksAIM );  ?>";
				      document.getElementById("graduationYear1MarksAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear1MarksAIM_error'></div></div>
				</div>
				</div>
                <div class="clearFix"></div>
				</div>
				<div class="spacer20 clearFix"></div>
				
                <div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Graduation Year 2 - From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear2FromAIM' id='graduationYear2FromAIM'  validate="validateInteger"   required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Graduation Year 2."   value=''   />
				<?php if(isset($graduationYear2FromAIM) && $graduationYear2FromAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear2FromAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear2FromAIM );  ?>";
				      document.getElementById("graduationYear2FromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear2FromAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Graduation Year 2 -  To: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear2ToAIM' id='graduationYear2ToAIM'  validate="validateInteger"   required="true"   caption="year to"   minlength="4"   maxlength="4"     tip="Enter the year when you completed your Graduation Year 2."   value=''   />
				<?php if(isset($graduationYear2ToAIM) && $graduationYear2ToAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear2ToAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear2ToAIM );  ?>";
				      document.getElementById("graduationYear2ToAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear2ToAIM_error'></div></div>
				</div>
				</div>
				<div class="spacer20 clearFix"></div>
			
				<div class='additionalInfoLeftCol'>
				<label>Subjects studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='graduationYear2SubjectsAIM' id='graduationYear2SubjectsAIM'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="200"  csv="true"   tip="Enter the major subjects you studies in Graduation Year 2."   value=''   />
				<?php if(isset($graduationYear2SubjectsAIM) && $graduationYear2SubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear2SubjectsAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear2SubjectsAIM );  ?>";
				      document.getElementById("graduationYear2SubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear2SubjectsAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear2DivisionAIM' id='graduationYear2DivisionAIM'  validate="validateStr"   required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Graduation Year 2. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear2DivisionAIM) && $graduationYear2DivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear2DivisionAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear2DivisionAIM );  ?>";
				      document.getElementById("graduationYear2DivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear2DivisionAIM_error'></div></div>
				</div>
				</div>
				
				<div class="spacer20 clearFix"></div>
				
				<div class='additionalInfoLeftCol'>
				<label>% of Marks : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear2MarksAIM' id='graduationYear2MarksAIM'  validate="validateFloat"   required="true"   caption="% of Marks "   minlength="1"   maxlength="5"     tip="Enter the % of marks you obtained in your Graduation Year 2."   value=''   />
				<?php if(isset($graduationYear2MarksAIM) && $graduationYear2MarksAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear2MarksAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear2MarksAIM );  ?>";
				      document.getElementById("graduationYear2MarksAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear2MarksAIM_error'></div></div>
				</div>
				</div>
				<div class="clearFix"></div>
                </div>
				<div class="spacer20 clearFix"></div>
			
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Graduation Year 3 - From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear3FromAIM' id='graduationYear3FromAIM'  validate="validateInteger"  allowNA="true" required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Graduation Year 3. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear3FromAIM) && $graduationYear3FromAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear3FromAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear3FromAIM );  ?>";
				      document.getElementById("graduationYear3FromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear3FromAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Graduation Year 3 - To: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear3ToAIM' id='graduationYear3ToAIM'  validate="validateInteger" allowNA="true"  required="true"   caption="year to"   minlength="4"   maxlength="4"     tip="Enter the year when you completed your Graduation Year 3. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear3ToAIM) && $graduationYear3ToAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear3ToAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear3ToAIM );  ?>";
				      document.getElementById("graduationYear3ToAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear3ToAIM_error'></div></div>
				</div>
				</div>
				
				<div class="spacer20 clearFix"></div>
				
				<div class='additionalInfoLeftCol'>
				<label>Subjects studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='graduationYear3SubjectsAIM' id='graduationYear3SubjectsAIM' allowNA="true" validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="200"  csv="true"   tip="Enter the major subjects you studies in Graduation Year 3. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear3SubjectsAIM) && $graduationYear3SubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear3SubjectsAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear3SubjectsAIM );  ?>";
				      document.getElementById("graduationYear3SubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear3SubjectsAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear3DivisionAIM' id='graduationYear3DivisionAIM'  validate="validateStr" allowNA="true"  required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Graduation Year 3. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear3DivisionAIM) && $graduationYear3DivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear3DivisionAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear3DivisionAIM );  ?>";
				      document.getElementById("graduationYear3DivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear3DivisionAIM_error'></div></div>
				</div>
				</div>
			
				<div class="spacer20 clearFix"></div>
			
				<div class='additionalInfoLeftCol'>
				<label>% of Marks : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear3MarksAIM' id='graduationYear3MarksAIM'  validate="validateFloat" allowNA="true"  required="true"   caption="% of Marks "   minlength="1"   maxlength="5"     tip="Enter the % of marks you obtained in your Graduation Year 3. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear3MarksAIM) && $graduationYear3MarksAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear3MarksAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear3MarksAIM );  ?>";
				      document.getElementById("graduationYear3MarksAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear3MarksAIM_error'></div></div>
				</div>
				</div>
                <div class="clearFix"></div>
				</div>
				<div class="spacer20 clearFix"></div>
				
                <div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Graduation Year 4 - From: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear4FromAIM' id='graduationYear4FromAIM'  validate="validateInteger"   required="true"   caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your Graduation Year 4. <?php echo $NAText; ?>" allowNA="true"  value=''   />
				<?php if(isset($graduationYear4FromAIM) && $graduationYear4FromAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear4FromAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear4FromAIM );  ?>";
				      document.getElementById("graduationYear4FromAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear4FromAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Graduation Year 4 - To: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear4ToAIM' id='graduationYear4ToAIM'  validate="validateInteger"   required="true"   caption="year to"   minlength="4"   maxlength="4"     tip="Enter the year when you completed your Graduation Year 4. <?php echo $NAText; ?>"  allowNA="true" value=''   />
				<?php if(isset($graduationYear4ToAIM) && $graduationYear4ToAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear4ToAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear4ToAIM );  ?>";
				      document.getElementById("graduationYear4ToAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear4ToAIM_error'></div></div>
				</div>
				</div>
			
				<div class="spacer20 clearFix"></div>
			
				<div class='additionalInfoLeftCol'>
				<label>Subjects studied: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='graduationYear4SubjectsAIM' id='graduationYear4SubjectsAIM'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="200"  csv="true"   tip="Enter the major subjects you studies in Graduation Year 4. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear4SubjectsAIM) && $graduationYear4SubjectsAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear4SubjectsAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear4SubjectsAIM );  ?>";
				      document.getElementById("graduationYear4SubjectsAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear4SubjectsAIM_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Division Awarded: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear4DivisionAIM' id='graduationYear4DivisionAIM'  validate="validateStr"   required="true"   caption="division awarded"   minlength="1"   maxlength="30"     tip="Enter the division awarded in Graduation Year 4. <?php echo $NAText; ?>"   value=''   />
				<?php if(isset($graduationYear4DivisionAIM) && $graduationYear4DivisionAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear4DivisionAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear4DivisionAIM );  ?>";
				      document.getElementById("graduationYear4DivisionAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear4DivisionAIM_error'></div></div>
				</div>
				</div>
			
				<div class="spacer20 clearFix"></div>
			
				<div class='additionalInfoLeftCol'>
				<label>% of Marks : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationYear4MarksAIM' id='graduationYear4MarksAIM'  validate="validateFloat"   required="true"   caption="% of Marks "   minlength="1"   maxlength="5"     tip="Enter the % of marks you obtained in your Graduation Year 4. <?php echo $NAText; ?>"  allowNA="true" value=''   />
				<?php if(isset($graduationYear4MarksAIM) && $graduationYear4MarksAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationYear4MarksAIM").value = "<?php echo str_replace("\n", '\n', $graduationYear4MarksAIM );  ?>";
				      document.getElementById("graduationYear4MarksAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationYear4MarksAIM_error'></div></div>
				</div>
				</div>
                <div class="clearFix"></div>
                </div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Total Aggregate of Marks in Graduation (as on date) : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='graduationAggregateMarksAIM' id='graduationAggregateMarksAIM'  validate="validateFloat"   required="true"   caption="Total Aggregate of Marks"   minlength="1"   maxlength="10"     tip="Enter the total aggregate of marks (as on date) in your graduation."   value=''   />
				<?php if(isset($graduationAggregateMarksAIM) && $graduationAggregateMarksAIM!=""){ ?>
				  <script>
				      document.getElementById("graduationAggregateMarksAIM").value = "<?php echo str_replace("\n", '\n', $graduationAggregateMarksAIM );  ?>";
				      document.getElementById("graduationAggregateMarksAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'graduationAggregateMarksAIM_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php
			$otherCourses = array();
			
			if(is_array($educationDetails)) {
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						for($i=1;$i<=4;$i++) {
							if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
								$otherCourses[$i] = $educationDetail['value'];
							}
						}
					}
				}
			}
			
			if(count($otherCourses) > 0){
				$counter = 1;
				foreach($otherCourses as $otherCourseKey => $otherCourse) {
					$isPGVarName = 'isPG_mul_'.$otherCourseKey;
					$isPGValue = $$isPGVarName;
					
					$pgYearFromVarName = 'pgYearFrom_mul_'.$otherCourseKey;
					$pgYearFromValue = $$pgYearFromVarName;
					
					$pgSubjectsVarName = 'pgSubjects_mul_'.$otherCourseKey;
					$pgSubjectsValue = $$pgSubjectsVarName;
					
					$pgDivisionVarName = 'pgDivision_mul_'.$otherCourseKey;
					$pgDivisionValue = $$pgDivisionVarName;
			?>
				<li>
					<?php if($counter == 1) { ?>
					<h3 class="upperCase">Post Graduation Details</h3>
					<?php $counter++; } ?>
					<div class='additionalInfoLeftCol'>
					<label>Is <?php echo $otherCourse; ?> a Post Graduate (PG) course?: </label>
					<div class='fieldBoxLarge'>
					<input type='checkbox' name='<?php echo $isPGVarName; ?>' id='<?php echo $isPGVarName; ?>' value='1' <?php if($isPGValue) echo "checked='checked'"; ?> onclick="togglePGBlock('<?php echo $otherCourseKey; ?>');" /> 
					<div style='display:none'><div class='errorMsg' id= '<?php echo $isPGVarName; ?>_error'></div></div>
					</div>
					</div>
				</li>
				
				<li id="pgBlock<?php echo $otherCourseKey; ?>">
					<div class='additionalInfoLeftCol'>
					<label>Year From: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $pgYearFromVarName; ?>' id='<?php echo $pgYearFromVarName; ?>'  validate="validateInteger"  caption="year from"   minlength="4"   maxlength="4"     tip="Enter the year when you started your <?php echo $otherCourse; ?>."   value=''   />
					<?php if(isset($pgYearFromValue) && $pgYearFromValue!=""){ ?>
					  <script>
						  document.getElementById("<?php echo $pgYearFromVarName; ?>").value = "<?php echo str_replace("\n", '\n', $pgYearFromValue );  ?>";
						  document.getElementById("<?php echo $pgYearFromVarName; ?>").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= '<?php echo $pgYearFromVarName; ?>_error'></div></div>
					</div>
					</div>

					<div class='additionalInfoRightCol'>
					<label>Subjects studied: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $pgSubjectsVarName; ?>' id='<?php echo $pgSubjectsVarName; ?>'  caption="subjects "  tip="Enter the major subjects you studied in <?php echo $otherCourse; ?>." minlength="1"   maxlength="200" csv="true" validate="validateStr"  value=''   />
					<?php if(isset($pgSubjectsValue) && $pgSubjectsValue!=""){ ?>
					  <script>
						  document.getElementById("<?php echo $pgSubjectsVarName; ?>").value = "<?php echo str_replace("\n", '\n', $pgSubjectsValue );  ?>";
						  document.getElementById("<?php echo $pgSubjectsVarName; ?>").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= '<?php echo $pgSubjectsVarName; ?>_error'></div></div>
					</div>
					</div>
				
					<div class="spacer20 clearFix"></div>
				
					<div class='additionalInfoLeftCol'>
					<label>Division Awarded: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $pgDivisionVarName; ?>' id='<?php echo $pgDivisionVarName; ?>'  caption="division awarded" validate="validateStr" minlength="1"   maxlength="30"   tip="Enter the division awarded in <?php echo $otherCourse; ?>. <?php echo $NAText; ?>"   value=''   />
					<?php if(isset($pgDivisionValue) && $pgDivisionValue!=""){ ?>
					  <script>
						  document.getElementById("<?php echo $pgDivisionVarName; ?>").value = "<?php echo str_replace("\n", '\n', $pgDivisionValue );  ?>";
						  document.getElementById("<?php echo $pgDivisionVarName; ?>").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= '<?php echo $pgDivisionVarName; ?>_error'></div></div>
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
				$counter = 1;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$salaryFieldName = 'salaryAIM'.($workCompanyKey=='_mul_0'?'':$workCompanyKey);
					$salaryValue = $$salaryFieldName;
			?>
				<li>
					<?php if($counter == 1) { ?>
						<h3 class="upperCase">Additional Work Experience Details</h3>
					<?php $counter++; } ?>
					<div class='additionalInfoLeftCol'>
					<label>Salary Drawn at <?php echo $workCompany; ?>: </label>
					<div class='fieldBoxLarge'>
					<input type='text' name='<?php echo $salaryFieldName; ?>' id='<?php echo $salaryFieldName; ?>' tip="Enter your last annual salary at <?php echo $workCompany; ?> (if applicable)."   value=''   />
					<?php if(isset($salaryValue) && $salaryValue!=""){ ?>
					  <script>
						  document.getElementById("<?php echo $salaryFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $salaryValue );  ?>";
						  document.getElementById("<?php echo $salaryFieldName; ?>").style.color = "";
					  </script>
					<?php } ?>
					
					<div style='display:none'><div class='errorMsg' id= '<?php echo $salaryFieldName; ?>_error'></div></div>
					</div>
					</div>
				</li>
			<?php
				}
			}
			?>
			

			<li>
            	<h3 class="upperCase">Extra Information</h3>
				<div class='additionalInfoLeftCol' style="width:810px">
				<label>Extra Curricular Activities/Sports (with details of prizes won, if any): </label>
				<div class='fieldBoxLarge' style="width:500px">
				<textarea style="width:490px; height:75px" name='extraCurricularAIM' id='extraCurricularAIM'  maxlength="1500"       tip="Briefly explain your extra curricular/sports achievements. Also, list down the prized that you won."   validate="validateStr"></textarea>
				<?php if(isset($extraCurricularAIM) && $extraCurricularAIM!=""){ ?>
				  <script>
				      document.getElementById("extraCurricularAIM").value = "<?php echo str_replace("\n", '\n', $extraCurricularAIM );  ?>";
				      document.getElementById("extraCurricularAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'extraCurricularAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:810px">
				<label>Why do you want a career in Management and why do you think you are suitable for it? (Answer in 50 words): </label>
				<div class='fieldBoxLarge' style="width:500px">
				<textarea style="width:490px; height:75px" name='careerEssayAIM' id='careerEssayAIM'  validate="validateStr"  maxlength="1500" required="true"   caption="short essay"   minlength="1"   maxlength="500"   validate="validateStr"  tip="Write a short essay explaining why do you want to pursue Management & why are you suitable for the same."    ></textarea>
				<?php if(isset($careerEssayAIM) && $careerEssayAIM!=""){ ?>
				  <script>
				      document.getElementById("careerEssayAIM").value = "<?php echo str_replace("\n", '\n', $careerEssayAIM );  ?>";
				      document.getElementById("careerEssayAIM").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'careerEssayAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you require Hostel accommodation?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='isHostelRequiredAIM[]' id='isHostelRequiredAIM0'   value='Yes'  onmouseover="showTipOnline('Please select if you require a hostel.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you require a hostel.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='isHostelRequiredAIM[]' id='isHostelRequiredAIM1'   value='No'     onmouseover="showTipOnline('Please select if you require a hostel.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you require a hostel.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($isHostelRequiredAIM) && $isHostelRequiredAIM!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["isHostelRequiredAIM[]"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $isHostelRequiredAIM;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'isHostelRequiredAIM_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you require Educational Loan?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='isEducationLoadRequired[]' id='isEducationLoadRequired0'   value='Yes'  onmouseover="showTipOnline('Please select if you want to take education loan for your studies.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you want to take education loan for your studies.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='isEducationLoadRequired[]' id='isEducationLoadRequired1'   value='No'     onmouseover="showTipOnline('Please select if you want to take education loan for your studies.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you want to take education loan for your studies.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($isEducationLoadRequired) && $isEducationLoadRequired!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["isEducationLoadRequired[]"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $isEducationLoadRequired;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'isEducationLoadRequired_error'></div></div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol' style="width:100%">
                
				<label>How did you come to know about Asia-Pacific Institute of Management : </label>
				<div class="spacer5 clearFix"></div>
                <label><strong>Through advertisement : </strong></label>				
				<div class="spacer5 clearFix"></div>
                <label>Newspaper : </label>
				<div class='fieldBoxLarge' style="width:40px; padding-top:3px">
				<input type='checkbox' name='newsPaper[]' id='newsPaper0'   value='1'  onclick="toggleSourceOfInfo('newsPaper')"  ></input>
				<?php if(isset($newsPaper) && $newsPaper!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("newsPaper[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$newsPaper);
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
				
				<div style='display:none'><div class='errorMsg' id= 'newsPaper_error'></div></div>
				</div>
                
                <div class='additionalInfoLeftCol' id="newsPaperName">
				<label style="width:122px; color:#999">Specify Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='newsPaperDesc' id='newsPaperDesc' maxlength="50" tip="Please specify the newspaper from where you heard about Asia Pacific."   value=''   />
				<?php if(isset($newsPaperDesc) && $newsPaperDesc!=""){ ?>
				  <script>
				      document.getElementById("newsPaperDesc").value = "<?php echo str_replace("\n", '\n', $newsPaperDesc );  ?>";
				      document.getElementById("newsPaperDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'newsPaperDesc_error'></div></div>
				</div>
				</div>
				</div>
			
				
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Magazine: </label>
				<div class='fieldBoxLarge' style="width:40px; padding-top:3px">
				<input type='checkbox' name='magazine[]' id='magazine0'   value='1'  onclick="toggleSourceOfInfo('magazine')"  ></input>
				<?php if(isset($magazine) && $magazine!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("magazine[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$magazine);
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
				
				<div style='display:none'><div class='errorMsg' id= 'magazine_error'></div></div>
				</div>
                <div class='additionalInfoLeftCol' id='magazineName'>
				<label style="width:122px; color:#999">Specify Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='magazineDesc' id='magazineDesc'   maxlength="50"      tip="Please specify the magazine from where you heard about Asia Pacific."   value=''   />
				<?php if(isset($magazineDesc) && $magazineDesc!=""){ ?>
				  <script>
				      document.getElementById("magazineDesc").value = "<?php echo str_replace("\n", '\n', $magazineDesc );  ?>";
				      document.getElementById("magazineDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'magazineDesc_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Online: www: </label>
				<div class='fieldBoxLarge' style="width:40px; padding-top:3px">
				<input type='checkbox' name='onlineWWW[]' id='onlineWWW0'   value='1'  onclick="toggleSourceOfInfo('onlineWWW')"  ></input>
				<?php if(isset($onlineWWW) && $onlineWWW!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("onlineWWW[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$onlineWWW);
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
				
				<div style='display:none'><div class='errorMsg' id= 'onlineWWW_error'></div></div>
				</div>
                <div class='additionalInfoLeftCol' id='onlineWWWName'>
				<label style="width:122px; color:#999">Specify Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='onlineWWWDesc' id='onlineWWWDesc'    maxlength="50"     tip="Please specify the website from where you heard about Asia Pacific."   value=''   />
				<?php if(isset($onlineWWWDesc) && $onlineWWWDesc!=""){ ?>
				  <script>
				      document.getElementById("onlineWWWDesc").value = "<?php echo str_replace("\n", '\n', $onlineWWWDesc );  ?>";
				      document.getElementById("onlineWWWDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'onlineWWWDesc_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>TV: </label>
				<div class='fieldBoxLarge' style="width:40px; padding-top:3px">
				<input type='checkbox' name='television[]' id='television0'   value='1' onclick="toggleSourceOfInfo('television')"   ></input>
				<?php if(isset($television) && $television!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("television[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$television);
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
				
				<div style='display:none'><div class='errorMsg' id= 'television_error'></div></div>
				</div>
                
                <div class='additionalInfoLeftCol' id='televisionName'>
				<label style="width:122px; color:#999">Specify Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='televisionDesc' id='televisionDesc'   maxlength="50"      tip="Please specify"   value=''   />
				<?php if(isset($televisionDesc) && $televisionDesc!=""){ ?>
				  <script>
				      document.getElementById("televisionDesc").value = "<?php echo str_replace("\n", '\n', $televisionDesc );  ?>";
				      document.getElementById("televisionDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'televisionDesc_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Radio: </label>
				<div class='fieldBoxLarge' style="width:40px; padding-top:3px">
				<input type='checkbox' name='radio[]' id='radio0'   value='1' onclick="toggleSourceOfInfo('radio')"   ></input>
				<?php if(isset($radio) && $radio!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("radio[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$radio);
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
				
				<div style='display:none'><div class='errorMsg' id= 'radio_error'></div></div>
				</div>
                <div class='additionalInfoLeftCol' id='radioName'>
				<label style="width:122px; color:#999">Specify Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='radioDesc' id='radioDesc'    maxlength="50"     tip="Please specify"   value=''   />
				<?php if(isset($radioDesc) && $radioDesc!=""){ ?>
				  <script>
				      document.getElementById("radioDesc").value = "<?php echo str_replace("\n", '\n', $radioDesc );  ?>";
				      document.getElementById("radioDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'radioDesc_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Hoarding: </label>
				<div class='fieldBoxLarge' style="width:40px; padding-top:3px">
				<input type='checkbox' name='hoarding[]' id='hoarding0'   value='1' onclick="toggleSourceOfInfo('hoarding')"   ></input>
				<?php if(isset($hoarding) && $hoarding!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("hoarding[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$hoarding);
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
				
				<div style='display:none'><div class='errorMsg' id= 'hoarding_error'></div></div>
				</div>
                <div class='additionalInfoLeftCol' id='hoardingName'>
				<label style="width:122px; color:#999">Specify Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='hoardingDesc' id='hoardingDesc'     maxlength="50"    tip="Please specify"   value=''   />
				<?php if(isset($hoardingDesc) && $hoardingDesc!=""){ ?>
				  <script>
				      document.getElementById("hoardingDesc").value = "<?php echo str_replace("\n", '\n', $hoardingDesc );  ?>";
				      document.getElementById("hoardingDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hoardingDesc_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol'>
                                <label><strong>Through reference :</strong> </label>				
				<div class="spacer5 clearFix"></div>
				<label>Parent: </label>
				<div class='fieldBoxLarge' style="padding-top:3px">
				<input type='checkbox' name='heardFromParent[]' id='heardFromParent0'   value='1'    ></input>
				<?php if(isset($heardFromParent) && $heardFromParent!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("heardFromParent[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$heardFromParent);
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
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromParent_error'></div></div>
				</div>
				</div>
			</li>
            <li style="margin-bottom:10px">
            	<div class='additionalInfoLeftCol'>
				<label>Friends: </label>
				<div class='fieldBoxLarge' style="padding-top:3px">
				<input type='checkbox' name='heardFromFriends[]' id='heardFromFriends0'   value='1'    ></input>
				<?php if(isset($heardFromFriends) && $heardFromFriends!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("heardFromFriends[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$heardFromFriends);
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
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromFriends_error'></div></div>
				</div>
				</div>
            </li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol'>
				<label>Alumni/Current Student of Asia-Pacific: </label>
				<div class='fieldBoxLarge' style="padding-top:3px">
				<input type='checkbox' name='heardFromAlmuni[]' id='heardFromAlmuni0'   value='1'    ></input>
				<?php if(isset($heardFromAlmuni) && $heardFromAlmuni!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("heardFromAlmuni[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$heardFromAlmuni);
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
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromAlmuni_error'></div></div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol'>
				<label>Peers/Colleagues: </label>
				<div class='fieldBoxLarge' style="padding-top:3px">
				<input type='checkbox' name='heardFromPeers[]' id='heardFromPeers0'   value='1'    ></input>
				<?php if(isset($heardFromPeers) && $heardFromPeers!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("heardFromPeers[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$heardFromPeers);
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
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromPeers_error'></div></div>
				</div>
				</div>
			</li>

			<li style="margin-bottom:10px">
				<div class='additionalInfoLeftCol'>
				<label>Others: </label>
				<div class='fieldBoxLarge' style="padding-top:3px">
				<input type='checkbox' name='heardFromOthers[]' id='heardFromOthers0'   value='1'    ></input>
				<?php if(isset($heardFromOthers) && $heardFromOthers!=""){ ?>
				<script>
				    objCheckBoxes = document.getElementsByName("heardFromOthers[]");
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$heardFromOthers);
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
				
				<div style='display:none'><div class='errorMsg' id= 'heardFromOthers_error'></div></div>
				</div>
				</div>
			</li>

			<li>
            	<h3 class="upperCase">References</h3>
				
				<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1Name' id='ref1Name'  validate="validateStr"   required="true"   caption="name"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($ref1Name) && $ref1Name!=""){ ?>
				  <script>
				      document.getElementById("ref1Name").value = "<?php echo str_replace("\n", '\n', $ref1Name );  ?>";
				      document.getElementById("ref1Name").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1Name_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Designation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1Designation' id='ref1Designation'  validate="validateStr"   required="true"   caption="designation"   minlength="1"   maxlength="20"      value=''   />
				<?php if(isset($ref1Designation) && $ref1Designation!=""){ ?>
				  <script>
				      document.getElementById("ref1Designation").value = "<?php echo str_replace("\n", '\n', $ref1Designation );  ?>";
				      document.getElementById("ref1Designation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1Designation_error'></div></div>
				</div>
				</div>
				
			
				<div class="spacer20 clearFix"></div>
			
				<div class='additionalInfoLeftCol'>
				<label>Organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1Organization' id='ref1Organization'  validate="validateStr"   required="true"   caption="organization"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($ref1Organization) && $ref1Organization!=""){ ?>
				  <script>
				      document.getElementById("ref1Organization").value = "<?php echo str_replace("\n", '\n', $ref1Organization );  ?>";
				      document.getElementById("ref1Organization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1Organization_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Mobile No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1MobileNumber' id='ref1MobileNumber'  validate="validateMobileInteger"   required="true"   caption="reference person's mobile no."   minlength="8"   maxlength="15"      value=''   />
				<?php if(isset($ref1MobileNumber) && $ref1MobileNumber!=""){ ?>
				  <script>
				      document.getElementById("ref1MobileNumber").value = "<?php echo str_replace("\n", '\n', $ref1MobileNumber );  ?>";
				      document.getElementById("ref1MobileNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1MobileNumber_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<li>
				<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2Name' id='ref2Name'  validate="validateStr"   required="true"   caption="name"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($ref2Name) && $ref2Name!=""){ ?>
				  <script>
				      document.getElementById("ref2Name").value = "<?php echo str_replace("\n", '\n', $ref2Name );  ?>";
				      document.getElementById("ref2Name").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2Name_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Designation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2Designation' id='ref2Designation'  validate="validateStr"   required="true"   caption="designation"   minlength="1"   maxlength="20"      value=''   />
				<?php if(isset($ref2Designation) && $ref2Designation!=""){ ?>
				  <script>
				      document.getElementById("ref2Designation").value = "<?php echo str_replace("\n", '\n', $ref2Designation );  ?>";
				      document.getElementById("ref2Designation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2Designation_error'></div></div>
				</div>
				</div>
			
				<div class="spacer20 clearFix"></div>
			
				<div class='additionalInfoLeftCol'>
				<label>Organization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2Organization' id='ref2Organization'  validate="validateStr"   required="true"   caption="organization"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($ref2Organization) && $ref2Organization!=""){ ?>
				  <script>
				      document.getElementById("ref2Organization").value = "<?php echo str_replace("\n", '\n', $ref2Organization );  ?>";
				      document.getElementById("ref2Organization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2Organization_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Mobile No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2MobileNumber' id='ref2MobileNumber'  validate="validateMobileInteger"   required="true"   caption="reference person's mobile no."   minlength="8"   maxlength="15"      value=''   />
				<?php if(isset($ref2MobileNumber) && $ref2MobileNumber!=""){ ?>
				  <script>
				      document.getElementById("ref2MobileNumber").value = "<?php echo str_replace("\n", '\n', $ref2MobileNumber );  ?>";
				      document.getElementById("ref2MobileNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2MobileNumber_error'></div></div>
				</div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations)):
			//_p($gdpiLocations); die;?>
			<li>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
				<label style="font-weight:normal; padding-top:0px">Terms:</label>
				<div class='float_L' style="width:600px; color:#666666; font-style:italic; float:left">
				<?php
				$nameOfTheUser = array();
				foreach($basicInformation as $info) {
					if($info['fieldName'] == 'firstName' || $info['fieldName'] == 'middleName' || $info['fieldName'] == 'lastName') {
						$nameOfTheUser[$info['fieldName']] .= $info['value'];
					}
				}
				$nameOfTheUser = $nameOfTheUser['firstName'].' '.$nameOfTheUser['middleName'].' '.$nameOfTheUser['lastName'];
				?>
				I, <?php echo $nameOfTheUser; ?> declare that the particulars given above are correct to the best of my knowledge and belief. If, at any stage it is found that any of the information provided is incorrect then I will withdraw from the programme.
<ul class="termsList"><li>I accept that, on admission I shall be responsible for all my dues and for making timely payment of fees.</li>
<li>All disputes are subject to the jurisdiction of the competent court of Delhi only.</li></ul>
				
				<div class="spacer10 clearFix"></div>
				<div >
					<input type='checkbox' name='agreeToTermsTapmi[]' checked id='agreeToTermsTapmi0' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
					I agree to the terms stated above
					<?php if(isset($agreeToTermsTapmi) && $agreeToTermsTapmi!=""){ ?>
						<script>
					
					objCheckBoxes = document.getElementsByName("agreeToTermsTapmi[]");
					var countCheckBoxes = objCheckBoxes.length;
					
					for(var i = 0; i < countCheckBoxes; i++){
						  objCheckBoxes[i].checked = false;
		
						  <?php $arr = explode(",",$agreeToTermsTapmi);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsTapmi0_error'></div></div>
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
  
  function toggleGraduationCompleted(showKey,hideKey){
	var showBlock = $('graduationCompleted_'+showKey);
	var hideBlock = $('graduationCompleted_'+hideKey);
	if(showBlock && hideBlock){
	showBlock.style.display = '';
	hideBlock.style.display = 'none';
	
	var inputElementsInShowBlock = showBlock.getElementsByTagName('input');
	for(var i=0;i<inputElementsInShowBlock.length;i++) {
		var inputElement = inputElementsInShowBlock[i];
		inputElement.setAttribute('required','true');
	}
	
	var inputElementsInHideBlock = hideBlock.getElementsByTagName('input');
	for(var i=0;i<inputElementsInHideBlock.length;i++) {
		var inputElement = inputElementsInHideBlock[i];
		inputElement.removeAttribute('required');
		inputElement.value = '';
	}
	}
  }
  
	function togglePGBlock(pgBlockKey) {
	  var cb = $('isPG_mul_'+pgBlockKey);
	  if(cb) {
		  var pgBlock = $('pgBlock'+pgBlockKey);
		  if(cb.checked) {
			  pgBlock.style.display = '';
		  }
		  else {
				pgBlock.style.display = 'none';
				var inputElementsInPGBlock = pgBlock.getElementsByTagName('input');
				for(var i=0;i<inputElementsInPGBlock.length;i++) {
					var inputElement = inputElementsInPGBlock[i];
					inputElement.value='';
				}
		  }
	  }
	}
	
	function toggleSourceOfInfo(sourceKey) {
		var cb = $(sourceKey+'0');
		if(cb) {
			var sourceOfInfoBlock = $(sourceKey+'Name');
			if(cb.checked) {
				sourceOfInfoBlock.style.display = '';
			}
			else {
				sourceOfInfoBlock.style.display = 'none';
				var inputElementsInBlock = sourceOfInfoBlock.getElementsByTagName('input');
				for(var i=0;i<inputElementsInBlock.length;i++) {
					var inputElement = inputElementsInBlock[i];
					inputElement.value='';
				}
			}
		}
	}

	function checkValidPreference(id) {
		var preferences = ['PGDM','PGDMMkt','PGDMIB','PGDMBFS','PGDMEx','PGDMExMkt'];
		var selectedPrefId = preferences[id-1];
		var selectedPref = parseInt($('pref'+selectedPrefId).value);
		
		if(isNaN(selectedPref) || selectedPref<1 || selectedPref>6){
			$('pref'+selectedPrefId).value = '';
			$('pref'+selectedPrefId+'_error').innerHTML = 'Please fill the preference with correct numeric value (between 1 to 6)';
			$('pref'+selectedPrefId+'_error').parentNode.style.display = '';
			return false;
		}
		
		for(var i=0;i<6;i++) {
			if((i+1) != id) {
				var currentPrefId = preferences[i];
				var currentPref = $('pref'+currentPrefId).value;
				
				if(currentPref == selectedPref) {
					$('pref'+selectedPrefId).value = '';
					$('pref'+selectedPrefId+'_error').innerHTML = 'Same preference can’t be set to more than 1 course.';
					$('pref'+selectedPrefId+'_error').parentNode.style.display = '';
					return false;
				}
			}
		}
		return true;
	}

	toggleGraduationCompleted('<?php echo $isGraduationCompleted == 'No'?'No':'Yes'; ?>','<?php echo $isGraduationCompleted == 'No'?'Yes':'No'; ?>');	
	<?php
	for($i=1;$i<=4;$i++) {
		echo "togglePGBlock('$i');";
	}
	$sourcesOfInfo = array('newsPaper','magazine','onlineWWW','television','radio','hoarding');
	foreach($sourcesOfInfo as $soi) {
		echo "toggleSourceOfInfo('$soi');";
	}
	?>
	</script>
