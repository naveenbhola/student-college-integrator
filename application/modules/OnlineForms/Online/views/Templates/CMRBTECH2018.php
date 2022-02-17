<div class='formChildWrapper cmrform-wrapper'>

	<div id="instructions" style="font: normal 12px Arial, Helvetica, Sans Sarif; padding: 12px;">
	    <h3 style="margin: 0;background: transparent;padding: 0px;">Eligibility</h3>
	    <ul>
			<li>
			1. Passed in 2nd PUC/ 12th Std / Equivalent Exam with English as one of the Languages and obtained a minimum of 45% of marks in aggregate in Physics and Mathematics along with Chemistry / Biotechnology / Biology / Electronics/ Computers (40% for reserved category candidates).
			</li>
	        <li>
	    	2. A valid all India admission test like CET/Comed-K/JEE Mains/CMRUAT‌-2019 score as defined in qualifying exams section. The candidates can register for CMRUAT through the link: http://www.cmr.edu.in/cmruat2019-2/.
	        </li>
			<li>
			3. A candidate who opts for offline application fee payment, shall send the printout of the application form duly signed along with the DD for Rs.750/- as above through speed post/courier to The Admissions Department, CMR University (Main Campus), School of Engineering and Technology, Off Hennur, Bagalur Main Road, Chagalatti, Bangalore 562149, Karnataka, India.
			</li>       
	        <li>
	    	4. Applications received without mark sheets will be kept pending and not processed until they are properly submitted as per the guidelines given above. 
	    	</li>
			<li>
			5. Application fee is non-refundable and no case of refund will be entertained.
			</li>
			
	    </ul>
	</div>

	<div class='formSection'>
		<?php if($action != 'updateScore'){?>
		<ul>
	
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<option value="<?php echo $gdpiLocation['city_id']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
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
				<div class='additionalInfoLeftCol ful-col'>
				<label>Select Specialization (You can select multiple courses)
					<span style="font-style: italic;font-size: 12px;color: grey">(Press and hold Ctrl key for multiple select)</span>: <span style="color: red">*</span></label>
				<div class='fieldBoxLarge'>
				<select name='clientCoursesCMRBtech2018[]' validate="multipleSelect" caption="atleast one course"  required="true" id='clientCoursesCMRBtech2018'      MULTIPLE SIZE=5><option value='Civil' selected>Civil</option><option value='Computer Science' >Computer Science</option><option value='Electronics & Communication' >Electronics & Communication</option><option value='Information Technology' >Information Technology</option><option value='Mechanical' >Mechanical</option></select>
				<?php if(isset($clientCoursesCMRBtech2018) && $clientCoursesCMRBtech2018!=""){ ?>
			    <script>
				var select = document.getElementById("clientCoursesCMRBtech2018"); 
				var optionsToSelect = Array();
				    <?php $arr = explode(",",$clientCoursesCMRBtech2018); 
				      for($i=0;$i<count($arr);$i++){ ?>
					  optionsToSelect[<?php echo $i;?>] = "<?php echo $arr[$i];?>";
				    <?php
				      }
				    ?>
				    for ( var i = 0, l = select.options.length, o; i < l; i++ )
				    {
				      o = select.options[i];
				      o.selected = false;
				      if ( optionsToSelect.indexOf( o.text ) != -1 )
				      {
					o.selected = true;
				      }
				    }
			    </script>
			  <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'clientCoursesCMRBtech2018_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class="cmr_title">
					<h2>Personal Information</h2>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Domicile:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='DomicileCMRBtech2018' id='DomicileCMRBtech2018'  validate="validateStr"   required="true"   caption="domicile"   minlength="1"   maxlength="25"      value=''   />
				<?php if(isset($DomicileCMRBtech2018) && $DomicileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("DomicileCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $DomicileCMRBtech2018 );  ?>";
				      document.getElementById("DomicileCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'DomicileCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Passport Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PassportNumberCMRBtech2018' id='PassportNumberCMRBtech2018'  validate="validateStr"    caption="passport number"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($PassportNumberCMRBtech2018) && $PassportNumberCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PassportNumberCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PassportNumberCMRBtech2018 );  ?>";
				      document.getElementById("PassportNumberCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PassportNumberCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Date of Issue: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='dateOfIssuePassportCMRBtech2018' id='dateOfIssuePassportCMRBtech2018' readonly maxlength='10' onchange='futureDate()'           onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('dateOfIssuePassportCMRBtech2018'),'dateOfIssuePassportCMRBtech2018_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='dateOfIssuePassportCMRBtech2018_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('dateOfIssuePassportCMRBtech2018'),'dateOfIssuePassportCMRBtech2018_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($dateOfIssuePassportCMRBtech2018) && $dateOfIssuePassportCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("dateOfIssuePassportCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $dateOfIssuePassportCMRBtech2018 );  ?>";
				      document.getElementById("dateOfIssuePassportCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style="display: none;" id='dateOfIssuePassportCMRBtech2018_errorhideshow'>
					<div class='errorMsg' id= 'dateOfIssuePassportCMRBtech2018_error'"></div>
				</div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Caste: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CasteCMRBtech2018' id='CasteCMRBtech2018'  validate="validateStr"    caption="caste"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($CasteCMRBtech2018) && $CasteCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("CasteCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $CasteCMRBtech2018 );  ?>";
				      document.getElementById("CasteCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CasteCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Place of Birth:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PlaceOfBirthCMRBtech2018' id='PlaceOfBirthCMRBtech2018'  validate="validateStr"   required="true"   caption="place of birth"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($PlaceOfBirthCMRBtech2018) && $PlaceOfBirthCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PlaceOfBirthCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PlaceOfBirthCMRBtech2018 );  ?>";
				      document.getElementById("PlaceOfBirthCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PlaceOfBirthCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Citizenship:<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PlaceOfIssueCMRBtech2018' id='PlaceOfIssueCMRBtech2018'  validate="validateStr"   required="true"   caption="citizenship"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($PlaceOfIssueCMRBtech2018) && $PlaceOfIssueCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PlaceOfIssueCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PlaceOfIssueCMRBtech2018 );  ?>";
				      document.getElementById("PlaceOfIssueCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PlaceOfIssueCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Expiry Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ExpiryDateCMRBtech2018' id='ExpiryDateCMRBtech2018' readonly maxlength='10'            onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('ExpiryDateCMRBtech2018'),'ExpiryDateCMRBtech2018_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='ExpiryDateCMRBtech2018_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('ExpiryDateCMRBtech2018'),'ExpiryDateCMRBtech2018_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($ExpiryDateCMRBtech2018) && $ExpiryDateCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("ExpiryDateCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $ExpiryDateCMRBtech2018 );  ?>";
				      document.getElementById("ExpiryDateCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ExpiryDateCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Place of Issue: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CitizenShipCMRBtech2018' id='CitizenShipCMRBtech2018'  validate="validateStr"    caption="place of issue"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($CitizenShipCMRBtech2018) && $CitizenShipCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("CitizenShipCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $CitizenShipCMRBtech2018 );  ?>";
				      document.getElementById("CitizenShipCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CitizenShipCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Alternate Contact Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='AlternateMobileNumberCMRBtech2018' id='AlternateMobileNumberCMRBtech2018'  validate="validateMobileInteger"    caption="mobile number"   minlength="10"   maxlength="10"      value=''   />
				<?php if(isset($AlternateMobileNumberCMRBtech2018) && $AlternateMobileNumberCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("AlternateMobileNumberCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $AlternateMobileNumberCMRBtech2018 );  ?>";
				      document.getElementById("AlternateMobileNumberCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AlternateMobileNumberCMRBtech2018_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class="cmr_title">
					<h2>Parents Information</h2>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Father's Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='FathersEmailIdCMRBtech2018' id='FathersEmailIdCMRBtech2018'  validate="validateEmail"    caption="email"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($FathersEmailIdCMRBtech2018) && $FathersEmailIdCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("FathersEmailIdCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $FathersEmailIdCMRBtech2018 );  ?>";
				      document.getElementById("FathersEmailIdCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'FathersEmailIdCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Father's Qualification: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='FatherQualificationCMRBtech2018' id='FatherQualificationCMRBtech2018'  validate="validateStr"    caption="qualification"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($FatherQualificationCMRBtech2018) && $FatherQualificationCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("FatherQualificationCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $FatherQualificationCMRBtech2018 );  ?>";
				      document.getElementById("FatherQualificationCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'FatherQualificationCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Father's Annual Income (in Rs. Monthly): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='FatherAnnualIncomeCMRBtech2018' id='FatherAnnualIncomeCMRBtech2018'  validate="validateInteger"    caption="annual income"   minlength="1"   maxlength="20"      value=''   />
				<?php if(isset($FatherAnnualIncomeCMRBtech2018) && $FatherAnnualIncomeCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("FatherAnnualIncomeCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $FatherAnnualIncomeCMRBtech2018 );  ?>";
				      document.getElementById("FatherAnnualIncomeCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'FatherAnnualIncomeCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Father's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='FathersPhoneNumberCMRBtech2018' id='FathersPhoneNumberCMRBtech2018'  validate="validateMobileInteger"    caption="mobile number"   minlength="10"   maxlength="10"      value=''   />
				<?php if(isset($FathersPhoneNumberCMRBtech2018) && $FathersPhoneNumberCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("FathersPhoneNumberCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $FathersPhoneNumberCMRBtech2018 );  ?>";
				      document.getElementById("FathersPhoneNumberCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'FathersPhoneNumberCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Mother's Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MothersEmailIdCMRBtech2018' id='MothersEmailIdCMRBtech2018'  validate="validateEmail"    caption="email"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($MothersEmailIdCMRBtech2018) && $MothersEmailIdCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MothersEmailIdCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MothersEmailIdCMRBtech2018 );  ?>";
				      document.getElementById("MothersEmailIdCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MothersEmailIdCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Mother's Qualification: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MotherQualificationCMRBtech2018' id='MotherQualificationCMRBtech2018'  validate="validateStr"    caption="qualification"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($MotherQualificationCMRBtech2018) && $MotherQualificationCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MotherQualificationCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MotherQualificationCMRBtech2018 );  ?>";
				      document.getElementById("MotherQualificationCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MotherQualificationCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Mother's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MothersPhoneNumberCMRBtech2018' id='MothersPhoneNumberCMRBtech2018'  validate="validateMobileInteger"    caption="mobile number"   minlength="10"   maxlength="10"      value=''   />
				<?php if(isset($MothersPhoneNumberCMRBtech2018) && $MothersPhoneNumberCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MothersPhoneNumberCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MothersPhoneNumberCMRBtech2018 );  ?>";
				      document.getElementById("MothersPhoneNumberCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MothersPhoneNumberCMRBtech2018_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoLeftCol'></div>
				<div class="cmr_title">
					<h2>Guardian Information</h2>
				</div>
			
				<div class='additionalInfoLeftCol' style="margin-top: 20px;">
				<label>Guardian Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EmergencyContactNameCMRBtech2018' id='EmergencyContactNameCMRBtech2018'  validate="validateStr"    caption="name"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($EmergencyContactNameCMRBtech2018) && $EmergencyContactNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("EmergencyContactNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $EmergencyContactNameCMRBtech2018 );  ?>";
				      document.getElementById("EmergencyContactNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EmergencyContactNameCMRBtech2018_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoLeftCol' style="margin-top: 20px;">
				<label>Relationship with Student: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EmergencyRelationshipCMRBtech2018' id='EmergencyRelationshipCMRBtech2018'  validate="validateStr"    caption="relationship"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($EmergencyRelationshipCMRBtech2018) && $EmergencyRelationshipCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("EmergencyRelationshipCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $EmergencyRelationshipCMRBtech2018 );  ?>";
				      document.getElementById("EmergencyRelationshipCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EmergencyRelationshipCMRBtech2018_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoLeftCol'>
				<label>Guardian's Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='AlternateEmailCMRBtech2018' id='AlternateEmailCMRBtech2018'  validate="validateEmail"    caption="email"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($AlternateEmailCMRBtech2018) && $AlternateEmailCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("AlternateEmailCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $AlternateEmailCMRBtech2018 );  ?>";
				      document.getElementById("AlternateEmailCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AlternateEmailCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Guardian's Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Address1CMRBtech2018' id='Address1CMRBtech2018'  validate="validateStr"    caption="address"   minlength="1"   maxlength="200"      value=''   />
				<?php if(isset($Address1CMRBtech2018) && $Address1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("Address1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $Address1CMRBtech2018 );  ?>";
				      document.getElementById("Address1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Address1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Guardian's Town/City: </label>
				<div class='fieldBoxLarge'>
				<select name='CityCMRBtech2018' id='CityCMRBtech2018'      validate="validateSelect"    caption="please select city"  ><option value='' selected>Select</option><option value='Abhayapuri' >Abhayapuri</option><option value='Abiramam' >Abiramam</option><option value='Abohar' >Abohar</option><option value='Abrama' >Abrama</option><option value='Abu Road' >Abu Road</option><option value='Achabal' >Achabal</option><option value='Achalpur' >Achalpur</option><option value='Achampet' >Achampet</option><option value='Achampudur' >Achampudur</option><option value='Acharapakkam' >Acharapakkam</option><option value='Acharipallam' >Acharipallam</option><option value='Achhalda' >Achhalda</option><option value='Achhnera' >Achhnera</option><option value='Achipatti' >Achipatti</option><option value='Adalaj' >Adalaj</option><option value='Adampur' >Adampur</option><option value='Adari' >Adari</option><option value='Addanki' >Addanki</option><option value='Adikaratti' >Adikaratti</option><option value='Adilabad' >Adilabad</option><option value='Adimaly' >Adimaly</option><option value='Adiramapattinam' >Adiramapattinam</option><option value='Adityana' >Adityana</option><option value='Adityanagar' >Adityanagar</option><option value='Adityapatna' >Adityapatna</option><option value='Adityapur' >Adityapur</option><option value='Adivivaram' >Adivivaram</option><option value='Adoni' >Adoni</option><option value='Adoor' >Adoor</option><option value='Adra' >Adra</option><option value='Adur' >Adur</option><option value='Aduturai' >Aduturai</option><option value='Advana' >Advana</option><option value='Adyar' >Adyar</option><option value='Afzalgarh' >Afzalgarh</option><option value='Afzalpur' >Afzalpur</option><option value='Aganampudi' >Aganampudi</option><option value='Agar' >Agar</option><option value='Agaram' >Agaram</option><option value='Agartala' >Agartala</option><option value='Agarwal Mandi' >Agarwal Mandi</option><option value='Agasthiswaram' >Agasthiswaram</option><option value='Agethi' >Agethi</option><option value='Agra' >Agra</option><option value='Agra Cantonment' >Agra Cantonment</option><option value='Aheri' >Aheri</option><option value='Ahiwara' >Ahiwara</option><option value='Ahmadnagar Cantonment' >Ahmadnagar Cantonment</option><option value='Ahmedabad' >Ahmedabad</option><option value='Ahmedgarh' >Ahmedgarh</option><option value='Ahmednagar' >Ahmednagar</option><option value='Ahraura' >Ahraura</option><option value='Ahwa' >Ahwa</option><option value='Aiho' >Aiho</option><option value='Ailum' >Ailum</option><option value='Air Force Area' >Air Force Area</option><option value='Aistala' >Aistala</option><option value='Aizawl' >Aizawl</option><option value='Ajaigarh' >Ajaigarh</option><option value='Ajhuwa' >Ajhuwa</option><option value='Ajjampur' >Ajjampur</option><option value='Ajjaram' >Ajjaram</option><option value='Ajmer' >Ajmer</option><option value='Ajnala' >Ajnala</option><option value='Ajra' >Ajra</option><option value='Akalgarh' >Akalgarh</option><option value='Akalkot' >Akalkot</option><option value='Akaltara' >Akaltara</option><option value='Akathiyur' >Akathiyur</option><option value='Akbarpur' >Akbarpur</option><option value='Akhnur' >Akhnur</option><option value='Akividu' >Akividu</option><option value='Akkalkuwa' >Akkalkuwa</option><option value='Akkaraipettai' >Akkaraipettai</option><option value='Akkarampalle' >Akkarampalle</option><option value='Akkayapalle' >Akkayapalle</option><option value='Akkireddipalem' >Akkireddipalem</option><option value='Aklera' >Aklera</option><option value='Akoda' >Akoda</option><option value='Akodia' >Akodia</option><option value='Akola' >Akola</option><option value='Akot' >Akot</option><option value='Alagappapuram' >Alagappapuram</option><option value='Alagapuri' >Alagapuri</option><option value='Alampalayam' >Alampalayam</option><option value='Aland' >Aland</option><option value='Alandi' >Alandi</option><option value='Alandur' >Alandur</option><option value='Alang' >Alang</option><option value='Alangad' >Alangad</option><option value='Alanganallur' >Alanganallur</option><option value='Alangayam' >Alangayam</option><option value='Alangudi' >Alangudi</option><option value='Alangulam' >Alangulam</option><option value='Alanthurai' >Alanthurai</option><option value='Alapakkam' >Alapakkam</option><option value='Alappuzha' >Alappuzha</option><option value='Alapur' >Alapur</option><option value='Alawalpur' >Alawalpur</option><option value='Aldona' >Aldona</option><option value='Alibag' >Alibag</option><option value='Aliganj' >Aliganj</option><option value='Aligarh' >Aligarh</option><option value='Alipur Duar' >Alipur Duar</option><option value='Alipur Duar Railway Junction' >Alipur Duar Railway Junction</option><option value='Alirajpur' >Alirajpur</option><option value='Allahabad' >Allahabad</option><option value='Allahabad Cantonment' >Allahabad Cantonment</option><option value='Allahganj' >Allahganj</option><option value='Allapalli' >Allapalli</option><option value='Allapuram' >Allapuram</option><option value='Almatti Sitimani' >Almatti Sitimani</option><option value='Almora' >Almora</option><option value='Almora Cantonment' >Almora Cantonment</option><option value='Alnavar' >Alnavar</option><option value='Along' >Along</option><option value='Alore' >Alore</option><option value='Alot' >Alot</option><option value='Alpur' >Alpur</option><option value='Altinho' >Altinho</option><option value='Aluva' >Aluva</option><option value='Alwar' >Alwar</option><option value='Alwar Tirunagari' >Alwar Tirunagari</option><option value='Alwarkurichi' >Alwarkurichi</option><option value='Amalapuram' >Amalapuram</option><option value='Amalhara' >Amalhara</option><option value='Amalner' >Amalner</option><option value='Amanganj' >Amanganj</option><option value='Amanpur' >Amanpur</option><option value='Amarkantak' >Amarkantak</option><option value='Amarpatan' >Amarpatan</option><option value='Amarwara' >Amarwara</option><option value='Ambad' >Ambad</option><option value='Ambada' >Ambada</option><option value='Ambagarh Chauki' >Ambagarh Chauki</option><option value='Ambah' >Ambah</option><option value='Ambahta' >Ambahta</option><option value='Ambaji' >Ambaji</option><option value='Ambajogai' >Ambajogai</option><option value='Ambala' >Ambala</option><option value='Ambala Cantt' >Ambala Cantt</option><option value='Ambaliyasan' >Ambaliyasan</option><option value='Ambasamudram' >Ambasamudram</option><option value='Ambassa' >Ambassa</option><option value='Ambedkar Nagar' >Ambedkar Nagar</option><option value='Ambernath' >Ambernath</option><option value='Ambikanagara' >Ambikanagara</option><option value='Ambivali Tarf Wankhal' >Ambivali Tarf Wankhal</option><option value='Ambur' >Ambur</option><option value='Amet' >Amet</option><option value='Amethi' >Amethi</option><option value='Amgaon' >Amgaon</option><option value='Amguri' >Amguri</option><option value='Amila' >Amila</option><option value='Amilo' >Amilo</option><option value='Aminagar Sarai' >Aminagar Sarai</option><option value='Aminagar Urf Bhurbaral' >Aminagar Urf Bhurbaral</option><option value='Amini' >Amini</option><option value='Amkula' >Amkula</option><option value='Amla' >Amla</option><option value='Amlabad' >Amlabad</option><option value='Amlagora' >Amlagora</option><option value='Amlai' >Amlai</option><option value='Amli' >Amli</option><option value='Amloh' >Amloh</option><option value='Ammainaickanur' >Ammainaickanur</option><option value='Ammaparikuppam' >Ammaparikuppam</option><option value='Ammapettai' >Ammapettai</option><option value='Ammavarikuppam' >Ammavarikuppam</option><option value='Ammur' >Ammur</option><option value='Amod' >Amod</option><option value='Amodghata' >Amodghata</option><option value='Amraudha' >Amraudha</option><option value='Amravati' >Amravati</option><option value='Amrawati' >Amrawati</option><option value='Amreli' >Amreli</option><option value='Amreli District' >Amreli District</option><option value='Amritsar' >Amritsar</option><option value='Amritsar Cantonment' >Amritsar Cantonment</option><option value='Amroha' >Amroha</option><option value='Amroli' >Amroli</option><option value='Amtala' >Amtala</option><option value='Amudalavalasa' >Amudalavalasa</option><option value='Amur' >Amur</option><option value='Anaimalai' >Anaimalai</option><option value='Anaiyur' >Anaiyur</option><option value='Anakapalle' >Anakapalle</option><option value='Anakaputhur' >Anakaputhur</option><option value='Anand' >Anand</option><option value='Anand Nagar' >Anand Nagar</option><option value='Anandapur' >Anandapur</option><option value='Anandnagar' >Anandnagar</option><option value='Anandpur Sahib' >Anandpur Sahib</option><option value='Anantapur' >Anantapur</option><option value='Ananthapuram' >Ananthapuram</option><option value='Anantnag' >Anantnag</option><option value='Ancharakandy' >Ancharakandy</option><option value='Andada' >Andada</option><option value='Andanappettai' >Andanappettai</option><option value='Andipalayam' >Andipalayam</option><option value='Andippatti' >Andippatti</option><option value='Andole' >Andole</option><option value='Andro' >Andro</option><option value='Androth Island' >Androth Island</option><option value='Andul' >Andul</option><option value='Anekal' >Anekal</option><option value='Angamaly' >Angamaly</option><option value='Angarpathar' >Angarpathar</option><option value='Angul' >Angul</option><option value='Anjad' >Anjad</option><option value='Anjangaon' >Anjangaon</option><option value='Anjar' >Anjar</option><option value='Anjaw' >Anjaw</option><option value='Anjugramam' >Anjugramam</option><option value='Anklav' >Anklav</option><option value='Ankleshwar' >Ankleshwar</option><option value='Anklesvar INA' >Anklesvar INA</option><option value='Ankola' >Ankola</option><option value='Anksa' >Anksa</option><option value='Ankurhati' >Ankurhati</option><option value='Annamalainagar' >Annamalainagar</option><option value='Annavasal' >Annavasal</option><option value='Annigeri' >Annigeri</option><option value='Annur' >Annur</option><option value='Anpara' >Anpara</option><option value='Antah' >Antah</option><option value='Antaliya' >Antaliya</option><option value='Anthiyur' >Anthiyur</option><option value='Antri' >Antri</option><option value='Antu' >Antu</option><option value='Anup Nagar' >Anup Nagar</option><option value='Anupgarh' >Anupgarh</option><option value='Anuppur' >Anuppur</option><option value='Anupshahr' >Anupshahr</option><option value='Aonla' >Aonla</option><option value='Appakudal' >Appakudal</option><option value='Aquem' >Aquem</option><option value='Arachalur' >Arachalur</option><option value='Arakandanallur' >Arakandanallur</option><option value='Arakonam' >Arakonam</option><option value='Aralvaimozhi' >Aralvaimozhi</option><option value='Arambagh' >Arambagh</option><option value='Arambhada' >Arambhada</option><option value='Arang' >Arang</option><option value='Arani' >Arani</option><option value='Arani Road' >Arani Road</option><option value='Arantangi' >Arantangi</option><option value='Araria' >Araria</option><option value='Arasiramani' >Arasiramani</option><option value='Aravakurichi' >Aravakurichi</option><option value='Aravankadu' >Aravankadu</option><option value='Arcot' >Arcot</option><option value='Areraj' >Areraj</option><option value='Argari' >Argari</option><option value='Ariankuppam' >Ariankuppam</option><option value='Arimalam' >Arimalam</option><option value='Ariyalur' >Ariyalur</option><option value='Ariyappampalayam' >Ariyappampalayam</option><option value='Ariyur' >Ariyur</option><option value='Arkalgud' >Arkalgud</option><option value='Arki' >Arki</option><option value='Armapur Estate' >Armapur Estate</option><option value='Armoor' >Armoor</option><option value='Arni' >Arni</option><option value='Arnia' >Arnia</option><option value='Aron' >Aron</option><option value='Aroor' >Aroor</option><option value='Arpora' >Arpora</option><option value='Arsha' >Arsha</option><option value='Arsikere' >Arsikere</option><option value='Arukutti' >Arukutti</option><option value='Arulmigu Thirumuruganpundi' >Arulmigu Thirumuruganpundi</option><option value='Arumanai' >Arumanai</option><option value='Arumbavur' >Arumbavur</option><option value='Arumuganeri' >Arumuganeri</option><option value='Aruppukkottai' >Aruppukkottai</option><option value='Arvi' >Arvi</option><option value='Asan Khurd' >Asan Khurd</option><option value='Asandh' >Asandh</option><option value='Asansol' >Asansol</option><option value='Asarganj' >Asarganj</option><option value='Asarma' >Asarma</option><option value='Ashok Nagar' >Ashok Nagar</option><option value='Ashokapuram' >Ashokapuram</option><option value='Ashoknagar' >Ashoknagar</option><option value='Ashoknagar Kalyangarh' >Ashoknagar Kalyangarh</option><option value='Ashokpuram' >Ashokpuram</option><option value='Ashrafpur Kichhauchha' >Ashrafpur Kichhauchha</option><option value='Ashti' >Ashti</option><option value='Asifabad' >Asifabad</option><option value='Asind' >Asind</option><option value='Aska' >Aska</option><option value='Atarra' >Atarra</option><option value='Atasu' >Atasu</option><option value='Ateli' >Ateli</option><option value='Athani' >Athani</option><option value='Athanur' >Athanur</option><option value='Athgarh' >Athgarh</option><option value='Athimarapatti' >Athimarapatti</option><option value='Athipattu' >Athipattu</option><option value='Athmallik' >Athmallik</option><option value='Athni' >Athni</option><option value='Athur' >Athur</option><option value='Atmakur' >Atmakur</option><option value='Atrauli' >Atrauli</option><option value='Atraulia' >Atraulia</option><option value='Attayyampatti' >Attayyampatti</option><option value='Attili' >Attili</option><option value='Attingal' >Attingal</option><option value='Attur' >Attur</option><option value='Atul' >Atul</option><option value='Aurad' >Aurad</option><option value='Auraiya' >Auraiya</option><option value='Aurangabad' >Aurangabad</option><option value='Aurangabad' >Aurangabad</option><option value='Aurangabad Bangar' >Aurangabad Bangar</option><option value='Aurangabad Cantonment' >Aurangabad Cantonment</option><option value='Auras' >Auras</option><option value='Auroville' >Auroville</option><option value='Ausa' >Ausa</option><option value='Avadattur' >Avadattur</option><option value='Avadi' >Avadi</option><option value='Avalpundurai' >Avalpundurai</option><option value='Avaniapuram' >Avaniapuram</option><option value='Avanigadda' >Avanigadda</option><option value='Avinashi' >Avinashi</option><option value='Avinissery' >Avinissery</option><option value='Awagarh' >Awagarh</option><option value='Awantipora' >Awantipora</option><option value='Ayakudi' >Ayakudi</option><option value='Ayanadaippu' >Ayanadaippu</option><option value='Aygudi' >Aygudi</option><option value='Ayodhya' >Ayodhya</option><option value='Ayothiapattinam' >Ayothiapattinam</option><option value='Ayyalur' >Ayyalur</option><option value='Ayyampalayam' >Ayyampalayam</option><option value='Ayyampettai' >Ayyampettai</option><option value='Azamgarh' >Azamgarh</option><option value='Azhagiapandiapuram' >Azhagiapandiapuram</option><option value='Azhikode North' >Azhikode North</option><option value='Azhikode South' >Azhikode South</option><option value='Azhiyur' >Azhiyur</option><option value='Azizpur' >Azizpur</option><option value='Azmatgarh' >Azmatgarh</option><option value='Babai' >Babai</option><option value='Babarpur Ajitmal' >Babarpur Ajitmal</option><option value='Baberu' >Baberu</option><option value='Babhulgaon' >Babhulgaon</option><option value='Babina' >Babina</option><option value='Babiyal' >Babiyal</option><option value='Bablari Dewanganj' >Bablari Dewanganj</option><option value='Babra' >Babra</option><option value='Babrala' >Babrala</option><option value='Babua Kalan' >Babua Kalan</option><option value='Babugarh' >Babugarh</option><option value='Bachhiowan' >Bachhiowan</option><option value='Bachhraon' >Bachhraon</option><option value='Bad' >Bad</option><option value='Bada Malhera' >Bada Malhera</option><option value='Badagaon' >Badagaon</option><option value='Badagavettu' >Badagavettu</option><option value='Badagoan' >Badagoan</option><option value='Badami' >Badami</option><option value='Badami Bagh' >Badami Bagh</option><option value='Badarpur' >Badarpur</option><option value='Badarpur Railway Town' >Badarpur Railway Town</option><option value='Badarwas' >Badarwas</option><option value='Badaun' >Badaun</option><option value='Badawada' >Badawada</option><option value='Baddi' >Baddi</option><option value='Bade Bacheli' >Bade Bacheli</option><option value='Badepalli' >Badepalli</option><option value='Badepally' >Badepally</option><option value='Badgam' >Badgam</option><option value='Badhagachhi' >Badhagachhi</option><option value='Badharghat' >Badharghat</option><option value='Badhni Kalan' >Badhni Kalan</option><option value='Badi' >Badi</option><option value='Badkuhi' >Badkuhi</option><option value='Badlapur' >Badlapur</option><option value='Badnagar' >Badnagar</option><option value='Badnawar' >Badnawar</option><option value='Badod' >Badod</option><option value='Badoda' >Badoda</option><option value='Badra' >Badra</option><option value='Badrinathpuri' >Badrinathpuri</option><option value='Baduria' >Baduria</option><option value='Badvel' >Badvel</option><option value='Bag' >e</option><option value='Bagaha' >Bagaha</option><option value='Bagalkot' >Bagalkot</option><option value='Bagar' >Bagar</option><option value='Bagasara' >Bagasara</option><option value='Bagbahara' >Bagbahara</option><option value='Bagbahra' >Bagbahra</option><option value='Bagepalli' >Bagepalli</option><option value='Bageshwar' >Bageshwar</option><option value='Bagh' >Bagh</option><option value='Bagh Purana' >Bagh Purana</option><option value='Baghdogra' >Baghdogra</option><option value='Baghmara' >Baghmara</option><option value='Baghpat' >Baghpat</option><option value='Bagli' >Bagli</option><option value='Bagnan' >Bagnan</option><option value='Bagpat' >Bagpat</option><option value='Bagra' >Bagra</option><option value='Bagru' >Bagru</option><option value='Bagula' >Bagula</option><option value='Bah' >Bah</option><option value='Bah Bazar' >Bah Bazar</option><option value='Bahadarpar' >Bahadarpar</option><option value='Bahadurgarh' >Bahadurgarh</option><option value='Baharampur' >Baharampur</option><option value='Bahbari Gaon' >Bahbari Gaon</option><option value='Baheri' >Baheri</option><option value='Bahirgram' >Bahirgram</option><option value='Bahjoi' >Bahjoi</option><option value='Bahraich' >Bahraich</option><option value='Bahror' >Bahror</option><option value='Bahsuma' >Bahsuma</option><option value='Bahua' >Bahua</option><option value='Bahula' >Bahula</option><option value='Baidyabati' >Baidyabati</option><option value='Baihar' >Baihar</option><option value='Bailhongal' >Bailhongal</option><option value='Baindur' >Baindur</option><option value='Bairabi' >Bairabi</option><option value='Bairatisal' >Bairatisal</option><option value='Bairgania' >Bairgania</option><option value='Baj Baj' >Baj Baj</option><option value='Bajala' >Bajala</option><option value='Bajipura' >Bajipura</option><option value='Bajna' >Bajna</option><option value='Bajpe' >Bajpe</option><option value='Bajva' >Bajva</option><option value='Bakani' >Bakani</option><option value='Bakewar' >Bakewar</option><option value='Bakhtiyarpur' >Bakhtiyarpur</option><option value='Bakiabad' >Bakiabad</option><option value='Bakloh' >Bakloh</option><option value='Bakreswar' >Bakreswar</option><option value='Bakswaha' >Bakswaha</option><option value='Balachaur' >Balachaur</option><option value='Balaghat' >Balaghat</option><option value='Balagoda' >Balagoda</option><option value='Balakrishnampatti' >Balakrishnampatti</option><option value='Balakrishnapuram' >Balakrishnapuram</option><option value='Balangir' >Balangir</option><option value='Balapallam' >Balapallam</option><option value='Balaram Pota' >Balaram Pota</option><option value='Balarampur' >Balarampur</option><option value='Balasamudram' >Balasamudram</option><option value='Balasinor' >Balasinor</option><option value='Balasore' >Balasore</option><option value='Baldeo' >Baldeo</option><option value='Baldeogarh' >Baldeogarh</option><option value='Baleshwar' >Baleshwar</option><option value='Baleswar' >Baleswar</option><option value='Bali' >Bali</option><option value='Bali Chak' >Bali Chak</option><option value='Baliapur' >Baliapur</option><option value='Baliari' >Baliari</option><option value='Balimeta' >Balimeta</option><option value='Balkundra' >Balkundra</option><option value='Ballabgarh' >Ballabgarh</option><option value='Ballarpur' >Ballarpur</option><option value='Ballavpur' >Ballavpur</option><option value='Ballia' >Ballia</option><option value='Bally' >Bally</option><option value='Balod' >Balod</option><option value='Baloda' >Baloda</option><option value='Baloda Bazar' >Baloda Bazar</option><option value='Balotra' >Balotra</option><option value='Balrampur' >Balrampur</option><option value='Balugaon' >Balugaon</option><option value='Balurghat' >Balurghat</option><option value='Balussery' >Balussery</option><option value='Bamaniya' >Bamaniya</option><option value='Bambolim' >Bambolim</option><option value='Bamhani' >Bamhani</option><option value='Bamor' >Bamor</option><option value='Bamora' >Bamora</option><option value='Bamun Sualkuchi' >Bamun Sualkuchi</option><option value='Bamunari' >Bamunari</option><option value='Banapur' >Banapur</option><option value='Banarhat Tea Garden' >Banarhat Tea Garden</option><option value='Banarsi' >Banarsi</option><option value='Banaskantha' >Banaskantha</option><option value='Banat' >Banat</option><option value='Banaur' >Banaur</option><option value='Banavar' >Banavar</option><option value='Banbasa' >Banbasa</option><option value='Banda' >Banda</option><option value='Bandarulanka' >Bandarulanka</option><option value='Bandel' >Bandel</option><option value='Bandhgora' >Bandhgora</option><option value='Bandia' >Bandia</option><option value='Bandikui' >Bandikui</option><option value='Bandipore' >Bandipore</option><option value='Bandipur' >Bandipur</option><option value='Bandora' >Bandora</option><option value='Banga' >Banga</option><option value='Bangalore Rural District' >Bangalore Rural District</option><option value='Bangalore Urban District' >Bangalore Urban District</option><option value='Banganapalle' >Banganapalle</option><option value='Bangaon' >Bangaon</option><option value='Bangarapet' >Bangarapet</option><option value='Bangarmau' >Bangarmau</option><option value='Bangawan' >Bangawan</option><option value='Bangramanjeshwar' >Bangramanjeshwar</option><option value='Bangura' >Bangura</option><option value='Banihal' >Banihal</option><option value='Banjar' >Banjar</option><option value='Banka' >Banka</option><option value='Bankapura' >Bankapura</option><option value='Bankra' >Bankra</option><option value='Bankura' >Bankura</option><option value='Banmankhi' >Banmankhi</option><option value='Bannur' >Bannur</option><option value='Banposh' >Banposh</option><option value='Bansatar Kheda' >Bansatar Kheda</option><option value='Bansbaria' >Bansbaria</option><option value='Bansda' >Bansda</option><option value='Bansdih' >Bansdih</option><option value='Bansgaon' >Bansgaon</option><option value='Banshra' >Banshra</option><option value='Bansi' >Bansi</option><option value='Banswada' >Banswada</option><option value='Banswara' >Banswara</option><option value='Bantva' >Bantva</option><option value='Bantwal' >Bantwal</option><option value='Banupur' >Banupur</option><option value='Banur' >Banur</option><option value='Bapatla' >Bapatla</option><option value='Bapulapadu' >Bapulapadu</option><option value='Bar Bigha' >Bar Bigha</option><option value='Bara Bamonia' >Bara Bamonia</option><option value='Barabanki' >Barabanki</option><option value='Baragaon' >Baragaon</option><option value='Baraily' >Baraily</option><option value='Barajamda' >Barajamda</option><option value='Barakpur' >Barakpur</option><option value='Barakpur Cantonment' >Barakpur Cantonment</option><option value='Baramati' >Baramati</option><option value='Baramula' >Baramula</option><option value='Baramulla' >Baramulla</option><option value='Baran' >Baran</option><option value='Baranagar' >Baranagar</option><option value='Barasat' >Barasat</option><option value='Barauli' >Barauli</option><option value='Barauni Oil Township' >Barauni Oil Township</option><option value='Baraut' >Baraut</option><option value='Barbari' >Barbari</option><option value='Barbil' >Barbil</option><option value='Barddhaman' >Barddhaman</option><option value='Bardez' >Bardez</option><option value='Bardhaman' >Bardhaman</option><option value='Bardoli' >Bardoli</option><option value='Bareilly' >Bareilly</option><option value='Bareilly Cantonment' >Bareilly Cantonment</option><option value='Barela' >Barela</option><option value='Baretta' >Baretta</option><option value='Bargarh' >Bargarh</option><option value='Barghat' >Barghat</option><option value='Bargi' >Bargi</option><option value='Bargur' >Bargur</option><option value='Barh' >Barh</option><option value='Barhalganj' >Barhalganj</option><option value='Barhani' >Barhani</option><option value='Barhapur' >Barhapur</option><option value='Barhiya' >Barhiya</option><option value='Bari' >Bari</option><option value='Bari Brahmana' >Bari Brahmana</option><option value='Bari Sadri' >Bari Sadri</option><option value='Bariapur' >Bariapur</option><option value='Barigarh' >Barigarh</option><option value='Barijhati' >Barijhati</option><option value='Baripada' >Baripada</option><option value='Bariwala' >Bariwala</option><option value='Barjora' >Barjora</option><option value='Barka Kana' >Barka Kana</option><option value='Barkhera' >Barkhera</option><option value='Barki Saraiya' >Barki Saraiya</option><option value='Barkot' >Barkot</option><option value='Barmer' >Barmer</option><option value='Barnala' >Barnala</option><option value='Barpali' >Barpali</option><option value='Barpathar' >Barpathar</option><option value='Barpeta' >Barpeta</option><option value='Barpeta Road' >Barpeta Road</option><option value='Barrackpore' >Barrackpore</option><option value='Barsana' >Barsana</option><option value='Barshi' >Barshi</option><option value='Barughutu' >Barughutu</option><option value='Baruihuda' >Baruihuda</option><option value='Baruipur' >Baruipur</option><option value='Barunda' >Barunda</option><option value='Baruni' >Baruni</option><option value='Barva Sagar' >Barva Sagar</option><option value='Barwadih' >Barwadih</option><option value='Barwaha' >Barwaha</option><option value='Barwani' >Barwani</option><option value='Barwar' >Barwar</option><option value='Basar' >Basar</option><option value='Basaria' >Basaria</option><option value='Basavakalyan' >Basavakalyan</option><option value='Basavana Bagevadi' >Basavana Bagevadi</option><option value='Bashohli' >Bashohli</option><option value='Basi' >Basi</option><option value='Basirhat' >Basirhat</option><option value='Baska' >Baska</option><option value='Basmat' >Basmat</option><option value='Basna' >Basna</option><option value='Basni Belima' >Basni Belima</option><option value='Basoda' >Basoda</option><option value='Bassi Pathana' >Bassi Pathana</option><option value='Bastar' >Bastar</option><option value='Basti' >Basti</option><option value='Basudebpur' >Basudebpur</option><option value='Basugaon' >Basugaon</option><option value='Basukinath' >Basukinath</option><option value='Baswa' >Baswa</option><option value='Batala' >Batala</option><option value='Bathinda' >Bathinda</option><option value='Batote' >Batote</option><option value='Baudh' >Baudh</option><option value='Bawal' >Bawal</option><option value='Bawani Khera' >Bawani Khera</option><option value='Bayad' >Bayad</option><option value='Bayana' >Bayana</option><option value='Bazpur' >Bazpur</option><option value='Beawar' >Beawar</option><option value='Bechar' >Bechar</option><option value='Bedi' >Bedi</option><option value='Beed' >Beed</option><option value='Begamganj' >Begamganj</option><option value='Begampur' >Begampur</option><option value='Begowal' >Begowal</option><option value='Begumabad Budhana' >Begumabad Budhana</option><option value='Begun' >Begun</option><option value='Begusarai' >Begusarai</option><option value='Behat' >Behat</option><option value='Behea' >Behea</option><option value='Behrampur' >Behrampur</option><option value='Behta Hajipur' >Behta Hajipur</option><option value='Bela' >Bela</option><option value='Belagachhia' >Belagachhia</option><option value='Belagula' >Belagula</option><option value='Belaguntha' >Belaguntha</option><option value='Belakavadiq' >Belakavadiq</option><option value='Belampalli' >Belampalli</option><option value='Beldanga' >Beldanga</option><option value='Beldubi' >Beldubi</option><option value='Belebathan' >Belebathan</option><option value='Belgaum' >Belgaum</option><option value='Belgaum Cantonment' >Belgaum Cantonment</option><option value='Beliator' >Beliator</option><option value='Bellampalli' >Bellampalli</option><option value='Bellary' >Bellary</option><option value='Belluru' >Belluru</option><option value='Belonia' >Belonia</option><option value='Belpahar' >Belpahar</option><option value='Belsand' >Belsand</option><option value='Beltangadi' >Beltangadi</option><option value='Belthara' >Belthara</option><option value='Belvata' >Belvata</option><option value='Bemetra' >Bemetra</option><option value='Benaulim' >Benaulim</option><option value='Bengaluru' >Bengaluru</option><option value='Beniganj' >Beniganj</option><option value='Beohari' >Beohari</option><option value='Berasia' >Berasia</option><option value='Berhampur' >Berhampur</option><option value='Berhatty' >Berhatty</option><option value='Beri' >Beri</option><option value='Bermo' >Bermo</option><option value='Bestavaripeta' >Bestavaripeta</option><option value='Beswan' >Beswan</option><option value='Betamcherla' >Betamcherla</option><option value='Betma' >Betma</option><option value='Betora' >Betora</option><option value='Bettiah' >Bettiah</option><option value='Betul' >Betul</option><option value='Betul Bazar' >Betul Bazar</option><option value='Bewar' >Bewar</option><option value='Beypur' >Beypur</option><option value='Beyt' >Beyt</option><option value='Bhabat' >Bhabat</option><option value='Bhabua' >Bhabua</option><option value='Bhachau' >Bhachau</option><option value='Bhadarsa' >Bhadarsa</option><option value='Bhadasar' >Bhadasar</option><option value='Bhaderwah' >Bhaderwah</option><option value='Bhadohi' >Bhadohi</option><option value='Bhadra' >Bhadra</option><option value='Bhadrachalam' >Bhadrachalam</option><option value='Bhadrak' >Bhadrak</option><option value='Bhadreswar' >Bhadreswar</option><option value='Bhadur' >Bhadur</option><option value='Bhagalpur' >Bhagalpur</option><option value='Bhagatdih' >Bhagatdih</option><option value='Bhagur' >Bhagur</option><option value='Bhagwantnagar' >Bhagwantnagar</option><option value='Bhainsa' >Bhainsa</option><option value='Bhainsdehi' >Bhainsdehi</option><option value='Bhalariya' >Bhalariya</option><option value='Bhalki' >Bhalki</option><option value='Bhamodi' >Bhamodi</option><option value='Bhandara' >Bhandara</option><option value='Bhandardaha' >Bhandardaha</option><option value='Bhander' >Bhander</option><option value='Bhangar Raghunathpur' >Bhangar Raghunathpur</option><option value='Bhangri Pratham Khanda' >Bhangri Pratham Khanda</option><option value='Bhanjanagar' >Bhanjanagar</option><option value='Bhankharpur' >Bhankharpur</option><option value='Bhanowara' >Bhanowara</option><option value='Bhanpura' >Bhanpura</option><option value='Bhanpuri' >Bhanpuri</option><option value='Bhanvad' >Bhanvad</option><option value='Bharatganj' >Bharatganj</option><option value='Bharatpur' >Bharatpur</option><option value='Bhargain' >Bhargain</option><option value='Bharoli Kalan' >Bharoli Kalan</option><option value='Bharthana' >Bharthana</option><option value='Bharuch' >Bharuch</option><option value='Bharuch INA' >Bharuch INA</option><option value='Bharuhana' >Bharuhana</option><option value='Bharveli' >Bharveli</option><option value='Bharwari' >Bharwari</option><option value='Bhasawar' >Bhasawar</option><option value='Bhatapara' >Bhatapara</option><option value='Bhatgaon' >Bhatgaon</option><option value='Bhatkal' >Bhatkal</option><option value='Bhatni Bazar' >Bhatni Bazar</option><option value='Bhatpar Rani' >Bhatpar Rani</option><option value='Bhatpara' >Bhatpara</option><option value='Bhattiprolu' >Bhattiprolu</option><option value='Bhaurah' >Bhaurah</option><option value='Bhaurasa' >Bhaurasa</option><option value='Bhavani' >Bhavani</option><option value='Bhavnagar' >Bhavnagar</option><option value='Bhavra' >Bhavra</option><option value='Bhawan Bahadurnagar' >Bhawan Bahadurnagar</option><option value='Bhawani Mandi' >Bhawani Mandi</option><option value='Bhawanigarh' >Bhawanigarh</option><option value='Bhawanipatna' >Bhawanipatna</option><option value='Bhawanisagar' >Bhawanisagar</option><option value='Bhawri' >Bhawri</option><option value='Bhayavadar' >Bhayavadar</option><option value='Bhedaghat' >Bhedaghat</option><option value='Bhestan' >Bhestan</option><option value='Bhigvan' >Bhigvan</option><option value='Bhikangaon' >Bhikangaon</option><option value='Bhikhi' >Bhikhi</option><option value='Bhikhiwind' >Bhikhiwind</option><option value='Bhilai' >Bhilai</option><option value='Bhilakhedi' >Bhilakhedi</option><option value='Bhilwara' >Bhilwara</option><option value='Bhim Tal' >Bhim Tal</option><option value='Bhimarayanagudi' >Bhimarayanagudi</option><option value='Bhimavaram' >Bhimavaram</option><option value='Bhimnagar' >Bhimnagar</option><option value='Bhimunipatnam' >Bhimunipatnam</option><option value='Bhind' >Bhind</option><option value='Bhindar' >Bhindar</option><option value='Bhinga' >Bhinga</option><option value='Bhingar' >Bhingar</option><option value='Bhinmal' >Bhinmal</option><option value='Bhisiana' >Bhisiana</option><option value='Bhitarwar' >Bhitarwar</option><option value='Bhiwadi' >Bhiwadi</option><option value='Bhiwandi' >Bhiwandi</option><option value='Bhiwani' >Bhiwani</option><option value='Bhogadi' >Bhogadi</option><option value='Bhogpur' >Bhogpur</option><option value='Bhojpur' >Bhojpur</option><option value='Bhojpur Dharampur' >Bhojpur Dharampur</option><option value='Bhojudih' >Bhojudih</option><option value='Bhokarhedi' >Bhokarhedi</option><option value='Bhokhardan' >Bhokhardan</option><option value='Bholar Dabri' >Bholar Dabri</option><option value='Bhongaon' >Bhongaon</option><option value='Bhongir' >Bhongir</option><option value='Bhopal' >Bhopal</option><option value='Bhor' >Bhor</option><option value='Bhosari' >Bhosari</option><option value='Bhota' >Bhota</option><option value='Bhowali' >Bhowali</option><option value='Bhuban' >Bhuban</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Bhuch' >Bhuch</option><option value='Bhuibandh' >Bhuibandh</option><option value='Bhuj' >Bhuj</option><option value='Bhulath' >Bhulath</option><option value='Bhulepur' >Bhulepur</option><option value='Bhuli' >Bhuli</option><option value='Bhum' >Bhum</option><option value='Bhuntar' >Bhuntar</option><option value='Bhupalpally' >Bhupalpally</option><option value='Bhusawal' >Bhusawal</option><option value='Bhuvanagiri' >Bhuvanagiri</option><option value='Biaora' >Biaora</option><option value='Biate' >Biate</option><option value='Bicholim' >Bicholim</option><option value='Bid' >Bid</option><option value='Bidar' >Bidar</option><option value='Bidhannagar' >Bidhannagar</option><option value='Bidhuna' >Bidhuna</option><option value='Bidyadharpur' >Bidyadharpur</option><option value='Bighapur' >Bighapur</option><option value='Bihar' >Bihar</option><option value='Bihar Sharif' >Bihar Sharif</option><option value='Bihariganj' >Bihariganj</option><option value='Bihpuria' >Bihpuria</option><option value='Bijapur' >Bijapur</option><option value='Bijawar' >Bijawar</option><option value='Bijbiara' >Bijbiara</option><option value='Bijeypur' >Bijeypur</option><option value='Bijni' >Bijni</option><option value='Bijnor' >Bijnor</option><option value='Bijoliya Kalan' >Bijoliya Kalan</option><option value='Bijoy Govinda' >Bijoy Govinda</option><option value='Bijpur' >Bijpur</option><option value='Bijrauni' >Bijrauni</option><option value='Bijuri' >Bijuri</option><option value='Bikaner' >Bikaner</option><option value='Bikapur' >Bikapur</option><option value='Biki Hakola' >Biki Hakola</option><option value='Bikketti' >Bikketti</option><option value='Bikramganj' >Bikramganj</option><option value='Bilandapur' >Bilandapur</option><option value='Bilara' >Bilara</option><option value='Bilari' >Bilari</option><option value='Bilasipara' >Bilasipara</option><option value='Bilaspur' >Bilaspur</option><option value='Bilaspur' >Bilaspur</option><option value='Bilaua' >Bilaua</option><option value='Bilgi' >Bilgi</option><option value='Bilgram' >Bilgram</option><option value='Bilha' >Bilha</option><option value='Bilhaur' >Bilhaur</option><option value='Bilimora' >Bilimora</option><option value='Bilkha' >Bilkha</option><option value='Billawar' >Billawar</option><option value='Billimora' >Billimora</option><option value='Biloli' >Biloli</option><option value='Bilpahari' >Bilpahari</option><option value='Bilpura' >Bilpura</option><option value='Bilram' >Bilram</option><option value='Bilrayaganj' >Bilrayaganj</option><option value='Bilsanda' >Bilsanda</option><option value='Bilsi' >Bilsi</option><option value='Bina Railway Colony' >Bina Railway Colony</option><option value='Bina' >Etawa</option><option value='Bindki' >Bindki</option><option value='Binika' >Binika</option><option value='Bipra Noapara' >Bipra Noapara</option><option value='Birbhum' >Birbhum</option><option value='Birgaon' >Birgaon</option><option value='Birlapur' >Birlapur</option><option value='Birmitrapur' >Birmitrapur</option><option value='Birnagar' >Birnagar</option><option value='Birpur' >Birpur</option><option value='Birsinghpur' >Birsinghpur</option><option value='Birur' >Birur</option><option value='Birwadi' >Birwadi</option><option value='Birwah' >Birwah</option><option value='Bisalpur' >Bisalpur</option><option value='Bisanda Buzurg' >Bisanda Buzurg</option><option value='Bisarpara' >Bisarpara</option><option value='Bisauli' >Bisauli</option><option value='Bishama Katek' >Bishama Katek</option><option value='Bisharatganj' >Bisharatganj</option><option value='Bishna' >Bishna</option><option value='Bishnupur' >Bishnupur</option><option value='Bisokhar' >Bisokhar</option><option value='Bissau' >Bissau</option><option value='Biswan' >Biswan</option><option value='Biswanath Chariali' >Biswanath Chariali</option><option value='Bithur' >Bithur</option><option value='Bobbili' >Bobbili</option><option value='Boda' >Boda</option><option value='Bodakdev' >Bodakdev</option><option value='Bodeli' >Bodeli</option><option value='Bodh Gaya' >Bodh Gaya</option><option value='Bodhan' >Bodhan</option><option value='Bodinayakkanur' >Bodinayakkanur</option><option value='Bodri' >Bodri</option><option value='Bohori' >Bohori</option><option value='Boisar' >Boisar</option><option value='Bokajan' >Bokajan</option><option value='Bokaro' >Bokaro</option><option value='Bokokhat' >Bokokhat</option><option value='Bolangir' >Bolangir</option><option value='Bollaram' >Bollaram</option><option value='Bolpur' >Bolpur</option><option value='Bommanahalli' >Bommanahalli</option><option value='Bommasandra' >Bommasandra</option><option value='Bommuru' >Bommuru</option><option value='Bondila' >Bondila</option><option value='Bongaigaon' >Bongaigaon</option><option value='Bongaigaon Petro' >chemical Town</option><option value='Bongaon' >Bongaon</option><option value='Bop Khel' >Bop Khel</option><option value='Bopal' >Bopal</option><option value='Borgolai' >Borgolai</option><option value='Boria' >Boria</option><option value='Boriavi' >Boriavi</option><option value='Borio Bazar' >Borio Bazar</option><option value='Borkhera' >Borkhera</option><option value='Borsad' >Borsad</option><option value='Botad' >Botad</option><option value='Boudh' >Boudh</option><option value='Bowali' >Bowali</option><option value='Brahmakulam' >Brahmakulam</option><option value='Brahmana Periya Agraharam' >Brahmana Periya Agraharam</option><option value='Brahmapur' >Brahmapur</option><option value='Brahmapuri' >Brahmapuri</option><option value='Brajrajnagar' >Brajrajnagar</option><option value='Budaun' >Budaun</option><option value='Budgam' >Budgam</option><option value='Budha Theh' >Budha Theh</option><option value='Budhgaon' >Budhgaon</option><option value='Budhlada' >Budhlada</option><option value='Budhni' >Budhni</option><option value='Budhpura' >Budhpura</option><option value='Bugganipalle' >Bugganipalle</option><option value='Bugrasi' >Bugrasi</option><option value='Buguda' >Buguda</option><option value='Bulandshahar' >Bulandshahar</option><option value='Bulandshahr' >Bulandshahr</option><option value='Buldana' >Buldana</option><option value='Buldhana' >Buldhana</option><option value='Bundi' >Bundi</option><option value='Bundu' >Bundu</option><option value='Burdwan' >Burdwan</option><option value='Burhana' >Burhana</option><option value='Burhanpur' >Burhanpur</option><option value='Burhar' >Burhar</option><option value='Buria' >Buria</option><option value='Burla' >Burla</option><option value='Buthapandi' >Buthapandi</option><option value='Buthipuram' >Buthipuram</option><option value='Butibori' >Butibori</option><option value='Buxar' >Buxar</option><option value='Byadgi' >Byadgi</option><option value='Byasanagar' >Byasanagar</option><option value='Byatarayanapura' >Byatarayanapura</option><option value='Cachar' >Cachar</option><option value='Calapor' >Calapor</option><option value='Cambay' >Cambay</option><option value='Candolim' >Candolim</option><option value='Canning' >Canning</option><option value='Caranzalem' >Caranzalem</option><option value='Carapur' >Carapur</option><option value='Cart Road' >Cart Road</option><option value='Central Delhi' >Central Delhi</option><option value='Chabua' >Chabua</option><option value='Chachanda' >Chachanda</option><option value='Chachaura Binaganj' >Chachaura Binaganj</option><option value='Chaibasa' >Chaibasa</option><option value='Chail' >Chail</option><option value='Chaitudih' >Chaitudih</option><option value='Chak Bankola' >Chak Bankola</option><option value='Chak Enayetnagar' >Chak Enayetnagar</option><option value='Chak Imam Ali' >Chak Imam Ali</option><option value='Chak Kashipur' >Chak Kashipur</option><option value='Chakalampur' >Chakalampur</option><option value='Chakan' >Chakan</option><option value='Chakbansberia' >Chakbansberia</option><option value='Chakdaha' >Chakdaha</option><option value='Chakeri' >Chakeri</option><option value='Chakghat' >Chakghat</option><option value='Chaklasi' >Chaklasi</option><option value='Chakpara' >Chakpara</option><option value='Chakradharpur' >Chakradharpur</option><option value='Chakranagar Colony' >Chakranagar Colony</option><option value='Chakrata' >Chakrata</option><option value='Chakulia' >Chakulia</option><option value='Chalakudi' >Chalakudi</option><option value='Chalala' >Chalala</option><option value='Chalisgaon' >Chalisgaon</option><option value='Challakere' >Challakere</option><option value='Challapalle' >Challapalle</option><option value='Chalthan' >Chalthan</option><option value='Chamarajnagar' >Chamarajnagar</option><option value='Chamba' >Chamba</option><option value='Chamoli' >Chamoli</option><option value='Chamoli and Gopeshwar' >Chamoli and Gopeshwar</option><option value='Champa' >Champa</option><option value='Champahati' >Champahati</option><option value='Champawat' >Champawat</option><option value='Champdani' >Champdani</option><option value='Champhai' >Champhai</option><option value='Champua' >Champua</option><option value='Chamrail' >Chamrail</option><option value='Chamrajnagar' >Chamrajnagar</option><option value='Chamundi Betta' >Chamundi Betta</option><option value='Chanasma' >Chanasma</option><option value='Chandameta Butar' >Chandameta Butar</option><option value='Chandannagar' >Chandannagar</option><option value='Chandapur' >Chandapur</option><option value='Chandauli' >Chandauli</option><option value='Chandaur' >Chandaur</option><option value='Chandausi' >Chandausi</option><option value='Chandbali' >Chandbali</option><option value='Chandel' >Chandel</option><option value='Chanderi' >Chanderi</option><option value='Chandia' >Chandia</option><option value='Chandigarh' >Chandigarh</option><option value='Chandil' >Chandil</option><option value='Chandili' >Chandili</option><option value='Chandisar' >Chandisar</option><option value='Chandkheda' >Chandkheda</option><option value='Chandla' >Chandla</option><option value='Chandrakona' >Chandrakona</option><option value='Chandrapur' >Chandrapur</option><option value='Chandrapur Bagicha' >Chandrapur Bagicha</option><option value='Chandrapura' >Chandrapura</option><option value='Chandur Bazar' >Chandur Bazar</option><option value='Chandvad' >Chandvad</option><option value='Changanacheri' >Changanacheri</option><option value='Changlang' >Changlang</option><option value='Channagiri' >Channagiri</option><option value='Channapatna' >Channapatna</option><option value='Channarayapatna' >Channarayapatna</option><option value='Chanod' >Chanod</option><option value='Chanpatia' >Chanpatia</option><option value='Chapar' >Chapar</option><option value='Chapari' >Chapari</option><option value='Chapui' >Chapui</option><option value='Char Brahmanagar' >Char Brahmanagar</option><option value='Char Maijdia' >Char Maijdia</option><option value='Charari Sharief' >Charari Sharief</option><option value='Charcha' >Charcha</option><option value='Charibatia' >Charibatia</option><option value='Charka' >Charka</option><option value='Charkhari' >Charkhari</option><option value='Charkhi Dadri' >Charkhi Dadri</option><option value='Charoda' >Charoda</option><option value='Charthawal' >Charthawal</option><option value='Chas' >Chas</option><option value='Chata Kalikapur' >Chata Kalikapur</option><option value='Chatakonda' >Chatakonda</option><option value='Chatra' >Chatra</option><option value='Chatrapatti' >Chatrapatti</option><option value='Chatrapur' >Chatrapur</option><option value='Chatsu' >Chatsu</option><option value='Chauhati' >Chauhati</option><option value='Chaumuhan' >Chaumuhan</option><option value='Chaupal' >Chaupal</option><option value='Chaurai Khas' >Chaurai Khas</option><option value='Chauwara' >Chauwara</option><option value='Chavakkad' >Chavakkad</option><option value='Chaya' >Chaya</option><option value='Checha Khata' >Checha Khata</option><option value='Chechat' >Chechat</option><option value='Chekonidhara' >Chekonidhara</option><option value='Chelad' >Chelad</option><option value='Chelakkara' >Chelakkara</option><option value='Chelora' >Chelora</option><option value='Chembarambakkam' >Chembarambakkam</option><option value='Chemmumiahpet' >Chemmumiahpet</option><option value='Chenani' >Chenani</option><option value='Chendamangalam' >Chendamangalam</option><option value='Chengalpattu' >Chengalpattu</option><option value='Chengam' >Chengam</option><option value='Chengamanad' >Chengamanad</option><option value='Chengannur' >Chengannur</option><option value='Chennai' >Chennai</option><option value='Chennasamudram' >Chennasamudram</option><option value='Chennimalai' >Chennimalai</option><option value='Chenpur' >Chenpur</option><option value='Cheranallur' >Cheranallur</option><option value='Cheranmadevi' >Cheranmadevi</option><option value='Cheriyakadavu' >Cheriyakadavu</option><option value='Cherrapunji' >Cherrapunji</option><option value='Cherthala' >Cherthala</option><option value='Cherukunnu' >Cherukunnu</option><option value='Cheruthazham' >Cheruthazham</option><option value='Cheruvanki' >Cheruvanki</option><option value='Cheruvannur' >Cheruvannur</option><option value='Cheruvattur' >Cheruvattur</option><option value='Chetpet' >Chetpet</option><option value='Chettiarpatti' >Chettiarpatti</option><option value='Chettipalaiyam' >Chettipalaiyam</option><option value='Chettipalayam Cantonment' >Chettipalayam Cantonment</option><option value='Chettithangal' >Chettithangal</option><option value='Chevvur' >Chevvur</option><option value='Cheyur' >Cheyur</option><option value='Cheyyar' >Cheyyar</option><option value='Chhabra' >Chhabra</option><option value='Chhachhrauli' >Chhachhrauli</option><option value='Chhapar' >Chhapar</option><option value='Chhapi' >Chhapi</option><option value='Chhapra' >Chhapra</option><option value='Chhaprabhatha' >Chhaprabhatha</option><option value='Chhaprauli' >Chhaprauli</option><option value='Chhara Rafatpur' >Chhara Rafatpur</option><option value='Chharprauli' >Chharprauli</option><option value='Chhata' >Chhata</option><option value='Chhatapur' >Chhatapur</option><option value='Chhatari' >Chhatari</option><option value='Chhatarpur' >Chhatarpur</option><option value='Chhatatanr' >Chhatatanr</option><option value='Chhatral' >Chhatral</option><option value='Chhibramau' >Chhibramau</option><option value='Chhindwara' >Chhindwara</option><option value='Chhipa Barod' >Chhipa Barod</option><option value='Chhora' >Chhora</option><option value='Chhota Chhindwara' >Chhota Chhindwara</option><option value='Chhota Udepur' >Chhota Udepur</option><option value='Chhotaputki' >Chhotaputki</option><option value='Chhoti Sadri' >Chhoti Sadri</option><option value='Chhuikhadan' >Chhuikhadan</option><option value='Chhutmalpur' >Chhutmalpur</option><option value='Chicalim' >Chicalim</option><option value='Chichli' >Chichli</option><option value='Chicholi' >Chicholi</option><option value='Chickballapur' >Chickballapur</option><option value='Chidambaram' >Chidambaram</option><option value='Chidiga' >Chidiga</option><option value='Chik Ballapur' >Chik Ballapur</option><option value='Chikballapur' >Chikballapur</option><option value='Chikhala' >Chikhala</option><option value='Chikhaldara' >Chikhaldara</option><option value='Chikitigarh' >Chikitigarh</option><option value='Chikkaballapur' >Chikkaballapur</option><option value='Chikmagalur' >Chikmagalur</option><option value='Chiknayakanhalli' >Chiknayakanhalli</option><option value='Chikodi' >Chikodi</option><option value='Chikrand' >Chikrand</option><option value='Chilakaluripet' >Chilakaluripet</option><option value='Chilkana Sultanpur' >Chilkana Sultanpur</option><option value='Chiloda' >Chiloda</option><option value='Chima' >Chima</option><option value='Chimakurthy' >Chimakurthy</option><option value='Chimbel' >Chimbel</option><option value='Chinagadila' >Chinagadila</option><option value='Chinagantyada' >Chinagantyada</option><option value='Chinalapatti' >Chinalapatti</option><option value='Chinchani' >Chinchani</option><option value='Chinchinim' >Chinchinim</option><option value='Chincholi' >Chincholi</option><option value='Chinchwad' >Chinchwad</option><option value='Chinna Anuppanadi' >Chinna Anuppanadi</option><option value='Chinna Salem' >Chinna Salem</option><option value='Chinnachawk' >Chinnachawk</option><option value='Chinnakkampalayam' >Chinnakkampalayam</option><option value='Chinnammanur' >Chinnammanur</option><option value='Chinnampalaiyam' >Chinnampalaiyam</option><option value='Chinnasekkadu' >Chinnasekkadu</option><option value='Chinnavedampatti' >Chinnavedampatti</option><option value='Chintalavalasa' >Chintalavalasa</option><option value='Chintamani' >Chintamani</option><option value='Chiplun' >Chiplun</option><option value='Chipurupalle' >Chipurupalle</option><option value='Chirakkal' >Chirakkal</option><option value='Chirala' >Chirala</option><option value='Chirawa' >Chirawa</option><option value='Chirgaon' >Chirgaon</option><option value='Chiria' >Chiria</option><option value='Chirkunda' >Chirkunda</option><option value='Chirmiri' >Chirmiri</option><option value='Chit Baragaon' >Chit Baragaon</option><option value='Chita' >Chita</option><option value='Chitaguppa' >Chitaguppa</option><option value='Chitapur' >Chitapur</option><option value='Chitlapakkam' >Chitlapakkam</option><option value='Chitradurga' >Chitradurga</option><option value='Chitrakonda' >Chitrakonda</option><option value='Chitrakoot' >Chitrakoot</option><option value='Chitrakut' >Chitrakut</option><option value='Chitrakut Dham' >Chitrakut Dham</option><option value='Chittaranjan' >Chittaranjan</option><option value='Chittaurgarh' >Chittaurgarh</option><option value='Chittodu' >Chittodu</option><option value='Chittoor' >Chittoor</option><option value='Chittorgarh' >Chittorgarh</option><option value='Chittur' >Chittur</option><option value='Chockli' >Chockli</option><option value='Chodavaram' >Chodavaram</option><option value='Chohal' >Chohal</option><option value='Cholapuram' >Cholapuram</option><option value='Chomun' >Chomun</option><option value='Chopan' >Chopan</option><option value='Chopda' >Chopda</option><option value='Chorvad' >Chorvad</option><option value='Chotila' >Chotila</option><option value='Choto Haibor' >Choto Haibor</option><option value='Choubepur Kalan' >Choubepur Kalan</option><option value='Choudwar' >Choudwar</option><option value='Choutuppal' >Choutuppal</option><option value='Chuari Khas' >Chuari Khas</option><option value='Chumukedima' >Chumukedima</option><option value='Chunar' >Chunar</option><option value='Chunchupalle' >Chunchupalle</option><option value='Churachandpur' >Churachandpur</option><option value='Churhat' >Churhat</option><option value='Churi' >Churi</option><option value='Churk Ghurma' >Churk Ghurma</option><option value='Churnikkara' >Churnikkara</option><option value='Churu' >Churu</option><option value='Clement Town' >Clement Town</option><option value='Coimbatore' >Coimbatore</option><option value='Colgong' >Colgong</option><option value='Colonelganj' >Colonelganj</option><option value='Colvale' >Colvale</option><option value='Contai' >Contai</option><option value='Cooch Behar' >Cooch Behar</option><option value='Coonoor' >Coonoor</option><option value='Coorg' >Coorg</option><option value='Corlim' >Corlim</option><option value='Cortalim' >Cortalim</option><option value='Courtalam' >Courtalam</option><option value='Cuddalore' >Cuddalore</option><option value='Cuddapah' >Cuddapah</option><option value='Cumbum' >Cumbum</option><option value='Cuncolim' >Cuncolim</option><option value='Curchorem' >Curchorem</option><option value='Curti' >Curti</option><option value='Cuttack' >Cuttack</option><option value='Dabhoi' >Dabhoi</option><option value='Dabhol' >Dabhol</option><option value='Daboh' >Daboh</option><option value='Dabra' >Dabra</option><option value='Dabwali' >Dabwali</option><option value='Dadara' >Dadara</option><option value='Dadhapatna' >Dadhapatna</option><option value='Dadri' >Dadri</option><option value='Dagshai' >Dagshai</option><option value='Dahance' >Dahance</option><option value='Dahanu' >Dahanu</option><option value='Daharu' >Daharu</option><option value='Dahod' >Dahod</option><option value='Dainhat' >Dainhat</option><option value='Daitari' >Daitari</option><option value='Dakor' >Dakor</option><option value='Dakshin Baguan' >Dakshin Baguan</option><option value='Dakshin Dinajpur' >Dakshin Dinajpur</option><option value='Dakshin Jhapardaha' >Dakshin Jhapardaha</option><option value='Dakshin Rajyadharpur' >Dakshin Rajyadharpur</option><option value='Dakshin Raypur' >Dakshin Raypur</option><option value='Dakshina Kannada' >Dakshina Kannada</option><option value='Dalavaipatti' >Dalavaipatti</option><option value='Dalhousie' >Dalhousie</option><option value='Dalhousie Cantonment' >Dalhousie Cantonment</option><option value='Dalkola' >Dalkola</option><option value='Dalmau' >Dalmau</option><option value='Dalsingh Sarai' >Dalsingh Sarai</option><option value='Daltenganj' >Daltenganj</option><option value='Dalurband' >Dalurband</option><option value='Daman' >Daman</option><option value='Damanjodi' >Damanjodi</option><option value='Damnagar' >Damnagar</option><option value='Damoh' >Damoh</option><option value='Damtal' >Damtal</option><option value='Damua' >Damua</option><option value='Dandeli' >Dandeli</option><option value='Danguwapasi' >Danguwapasi</option><option value='Dankaur' >Dankaur</option><option value='Dantewada' >Dantewada</option><option value='Daosa' >Daosa</option><option value='Dapoli Camp' >Dapoli Camp</option><option value='Daporijo' >Daporijo</option><option value='Darap Pur' >Darap Pur</option><option value='Darasuram' >Darasuram</option><option value='Darbhanga' >Darbhanga</option><option value='Dargajogihalli' >Dargajogihalli</option><option value='Dari' >Dari</option><option value='Dariba' >Dariba</option><option value='Dariyabad' >Dariyabad</option><option value='Darjeeling' >Darjeeling</option><option value='Darjiling' >Darjiling</option><option value='Darlawn' >Darlawn</option><option value='Darnakal' >Darnakal</option><option value='Darrang' >Darrang</option><option value='Darwa' >Darwa</option><option value='Daryapur' >Daryapur</option><option value='Dasarahalli' >Dasarahalli</option><option value='Dasna' >Dasna</option><option value='Dasnapur' >Dasnapur</option><option value='Dasuya' >Dasuya</option><option value='Dataganj' >Dataganj</option><option value='Datia' >Datia</option><option value='Dattapur' >Dattapur</option><option value='Daudnagar' >Daudnagar</option><option value='Dauleshwaram' >Dauleshwaram</option><option value='Daund' >Daund</option><option value='Daurala' >Daurala</option><option value='Dausa' >Dausa</option><option value='Davanagere' >Davanagere</option><option value='Davangere' >Davangere</option><option value='Davlameti' >Davlameti</option><option value='Davorlim' >Davorlim</option><option value='Dayal Bagh' >Dayal Bagh</option><option value='Debagarh' >Debagarh</option><option value='Debipur' >Debipur</option><option value='Deesa' >Deesa</option><option value='Defahat' >Defahat</option><option value='Deglur' >Deglur</option><option value='Dehra Dun Cantonment' >Dehra Dun Cantonment</option><option value='Dehradun' >Dehradun</option><option value='Dehrakhas' >Dehrakhas</option><option value='Dehri' >Dehri</option><option value='Dehu Road' >Dehu Road</option><option value='Delhi' >Delhi</option><option value='Delvada' >Delvada</option><option value='Denkanikottai' >Denkanikottai</option><option value='Deoband' >Deoband</option><option value='Deodara' >Deodara</option><option value='Deogarh' >Deogarh</option><option value='Deoghar' >Deoghar</option><option value='Deolali' >Deolali</option><option value='Deolali Pravara' >Deolali Pravara</option><option value='Deomali' >Deomali</option><option value='Deora' >Deora</option><option value='Deoranian' >Deoranian</option><option value='Deori Khas' >Deori Khas</option><option value='Deoria' >Deoria</option><option value='Deorikalan' >Deorikalan</option><option value='Depalpur' >Depalpur</option><option value='Dera Baba Nanak' >Dera Baba Nanak</option><option value='Dera Bassi' >Dera Bassi</option><option value='Dera Gopipur' >Dera Gopipur</option><option value='Deracolliery' >Deracolliery</option><option value='Dergaon' >Dergaon</option><option value='Desaiganj' >Desaiganj</option><option value='Deshnok' >Deshnok</option><option value='Desur' >Desur</option><option value='Deulgaon Raja' >Deulgaon Raja</option><option value='Deulia' >Deulia</option><option value='Devadanapatti' >Devadanapatti</option><option value='Devadurga' >Devadurga</option><option value='Devagiri' >Devagiri</option><option value='Devakkottai' >Devakkottai</option><option value='Devakottai' >Devakottai</option><option value='Devanangurichi' >Devanangurichi</option><option value='Devanhalli' >Devanhalli</option><option value='Devaprayag' >Devaprayag</option><option value='Devarkonda' >Devarkonda</option><option value='Devarshola' >Devarshola</option><option value='Devasthanam' >Devasthanam</option><option value='Devendranagar' >Devendranagar</option><option value='Devgadh Baria' >Devgadh Baria</option><option value='Devgarh' >Devgarh</option><option value='Devghar' >Devghar</option><option value='Devhara' >Devhara</option><option value='Devli' >Devli</option><option value='Devsar' >Devsar</option><option value='Dewa' >Dewa</option><option value='Dewas' >Dewas</option><option value='Dewhadi' >Dewhadi</option><option value='Dhaka' >Dhaka</option><option value='Dhakuria' >Dhakuria</option><option value='Dhalai' >Dhalai</option><option value='Dhalavoipuram' >Dhalavoipuram</option><option value='Dhali' >Dhali</option><option value='Dhaliyur' >Dhaliyur</option><option value='Dhalli' >Dhalli</option><option value='Dhaluwala' >Dhaluwala</option><option value='Dhamanagar' >Dhamanagar</option><option value='Dhamdha' >Dhamdha</option><option value='Dhamnod' >Dhamnod</option><option value='Dhampur' >Dhampur</option><option value='Dhamtari' >Dhamtari</option><option value='Dhana' >Dhana</option><option value='Dhanauha' >Dhanauha</option><option value='Dhanaula' >Dhanaula</option><option value='Dhanauli' >Dhanauli</option><option value='Dhanaura' >Dhanaura</option><option value='Dhanbad' >Dhanbad</option><option value='Dhandadihi' >Dhandadihi</option><option value='Dhandera' >Dhandera</option><option value='Dhandhuka' >Dhandhuka</option><option value='Dhanera' >Dhanera</option><option value='Dhangdhra' >Dhangdhra</option><option value='Dhanpuri' >Dhanpuri</option><option value='Dhansura' >Dhansura</option><option value='Dhanwar' >Dhanwar</option><option value='Dhanyakuria' >Dhanyakuria</option><option value='Dhar' >Dhar</option><option value='Dharam Kot' >Dharam Kot</option><option value='Dharamjaigarh' >Dharamjaigarh</option><option value='Dharampur' >Dharampur</option><option value='Dharampuri' >Dharampuri</option><option value='Dharamshala' >Dharamshala</option><option value='Dharangaon' >Dharangaon</option><option value='Dharapadavedu' >Dharapadavedu</option><option value='Dharapur' >Dharapur</option><option value='Dharapuram' >Dharapuram</option><option value='Dharchula' >Dharchula</option><option value='Dharchula Dehat' >Dharchula Dehat</option><option value='Dhari' >Dhari</option><option value='Dhariawad' >Dhariawad</option><option value='Dhariwal' >Dhariwal</option><option value='Dharmabad' >Dharmabad</option><option value='Dharmadam' >Dharmadam</option><option value='Dharmanagar' >Dharmanagar</option><option value='Dharmapur' >Dharmapur</option><option value='Dharmapuri' >Dharmapuri</option><option value='Dharmavaram' >Dharmavaram</option><option value='Dharoti Khurd' >Dharoti Khurd</option><option value='Dharuhera' >Dharuhera</option><option value='Dharur' >Dharur</option><option value='Dharwad' >Dharwad</option><option value='Dharwar' >Dharwar</option><option value='Dhatau' >Dhatau</option><option value='Dhatri Gram' >Dhatri Gram</option><option value='Dhaulpur' >Dhaulpur</option><option value='Dhaunsar' >Dhaunsar</option><option value='Dhauratanda' >Dhauratanda</option><option value='Dhaurhra' >Dhaurhra</option><option value='Dhekiajuli' >Dhekiajuli</option><option value='Dhemaji' >Dhemaji</option><option value='Dhenkanal' >Dhenkanal</option><option value='Dhilwan' >Dhilwan</option><option value='Dhing' >Dhing</option><option value='Dhola' >Dhola</option><option value='Dholka' >Dholka</option><option value='Dholka Rural' >Dholka Rural</option><option value='Dholpur' >Dholpur</option><option value='Dhone' >Dhone</option><option value='Dhoraji' >Dhoraji</option><option value='Dhrangadhra' >Dhrangadhra</option><option value='Dhrol' >Dhrol</option><option value='Dhubri' >Dhubri</option><option value='Dhuburi' >Dhuburi</option><option value='Dhuilya' >Dhuilya</option><option value='Dhulagari' >Dhulagari</option><option value='Dhule' >Dhule</option><option value='Dhulian' >Dhulian</option><option value='Dhupdal' >Dhupdal</option><option value='Dhupgari' >Dhupgari</option><option value='Dhuri' >Dhuri</option><option value='Dhusaripara' >Dhusaripara</option><option value='Dhuva' >Dhuva</option><option value='Dhuwaran' >Dhuwaran</option><option value='Diamond Harbour' >Diamond Harbour</option><option value='Dibai' >Dibai</option><option value='Dibang Valley' >Dibang Valley</option><option value='Dibiyapur' >Dibiyapur</option><option value='Dibrugarh' >Dibrugarh</option><option value='Didihat' >Didihat</option><option value='Didwana' >Didwana</option><option value='Dig' >Dig</option><option value='Digapahandi' >Digapahandi</option><option value='Digboi' >Digboi</option><option value='Digboi Oil Town' >Digboi Oil Town</option><option value='Digdoh' >Digdoh</option><option value='Digha' >Digha</option><option value='Dighawani' >Dighawani</option><option value='Dighwara' >Dighwara</option><option value='Diglur' >Diglur</option><option value='Dignala' >Dignala</option><option value='Digras' >Digras</option><option value='Digvijaygram' >Digvijaygram</option><option value='Diken' >Diken</option><option value='Dildarnagar Fatehpur' >Dildarnagar Fatehpur</option><option value='Dimapur' >Dimapur</option><option value='Dimaruguri' >Dimaruguri</option><option value='Dinanagar' >Dinanagar</option><option value='Dinapur' >Dinapur</option><option value='Dinapur Cantonment' >Dinapur Cantonment</option><option value='Dindigul' >Dindigul</option><option value='Dindori' >Dindori</option><option value='Dineshpur' >Dineshpur</option><option value='Dinhata' >Dinhata</option><option value='Diphu' >Diphu</option><option value='Dipka' >Dipka</option><option value='Dirba' >Dirba</option><option value='Disa' >Disa</option><option value='Dispur' >Dispur</option><option value='Diu' >Diu</option><option value='Do Ghat' >Do Ghat</option><option value='Doboka' >Doboka</option><option value='Dod Ballapur' >Dod Ballapur</option><option value='Doda' >Doda</option><option value='Dohrighat' >Dohrighat</option><option value='Doiwala' >Doiwala</option><option value='Dokmoka' >Dokmoka</option><option value='Dola' >Dola</option><option value='Doman Hill Colliery' >Doman Hill Colliery</option><option value='Dombivli' >Dombivli</option><option value='Dommara Nandyal' >Dommara Nandyal</option><option value='Dona Paula' >Dona Paula</option><option value='Dondaicha' >Dondaicha</option><option value='Dongargaon' >Dongargaon</option><option value='Dongragarh' >Dongragarh</option><option value='Donimalai' >Donimalai</option><option value='Donkamokan' >Donkamokan</option><option value='Doraha' >Doraha</option><option value='Dostpur' >Dostpur</option><option value='Dowlaiswaram' >Dowlaiswaram</option><option value='Dubrajpur' >Dubrajpur</option><option value='Dudhani' >Dudhani</option><option value='Dudhinagar' >Dudhinagar</option><option value='Dugadda' >Dugadda</option><option value='Dugda' >Dugda</option><option value='Dulhipur' >Dulhipur</option><option value='Duliagaon' >Duliagaon</option><option value='Duliajan' >Duliajan</option><option value='Duliajan No.1' >Duliajan No.1</option><option value='Dum Duma' >Dum Duma</option><option value='Dumar Kachhar' >Dumar Kachhar</option><option value='Dumarkunda' >Dumarkunda</option><option value='Dumjor' >Dumjor</option><option value='Dumka' >Dumka</option><option value='Dumra' >Dumra</option><option value='Dumraon' >Dumraon</option><option value='Dundahera' >Dundahera</option><option value='Dundwaraganj' >Dundwaraganj</option><option value='Dungamal' >Dungamal</option><option value='Dungapur' >Dungapur</option><option value='Dungar' >Dungar</option><option value='Dungargarh' >Dungargarh</option><option value='Dungariya Chhapara' >Dungariya Chhapara</option><option value='Dungra' >Dungra</option><option value='Durg' >Durg</option><option value='Durga Nagar' >Durga Nagar</option><option value='Durllabhganj' >Durllabhganj</option><option value='Duru' >Verinag</option><option value='Dusi' >Dusi</option><option value='Dwarahat' >Dwarahat</option><option value='Dwarka' >Dwarka</option><option value='Dyane' >Dyane</option><option value='East Delhi' >East Delhi</option><option value='East Garo Hills' >East Garo Hills</option><option value='East Godavari' >East Godavari</option><option value='East Godavari Dist.' >East Godavari Dist.</option><option value='East Kameng' >East Kameng</option><option value='East Khasi Hills' >East Khasi Hills</option><option value='East Sikkim' >East Sikkim</option><option value='Edaganasalai' >Edaganasalai</option><option value='Edaikodu' >Edaikodu</option><option value='Edakalinadu' >Edakalinadu</option><option value='Edandol' >Edandol</option><option value='Edappal' >Edappal</option><option value='Edathala' >Edathala</option><option value='Eddumailaram' >Eddumailaram</option><option value='Edulapuram' >Edulapuram</option><option value='Egarkunr' >Egarkunr</option><option value='Egra' >Egra</option><option value='Ekambara kuppam' >Ekambara kuppam</option><option value='Ekdil' >Ekdil</option><option value='Eklahare' >Eklahare</option><option value='Eksara' >Eksara</option><option value='Elathur' >Elathur</option><option value='Elayavur' >Elayavur</option><option value='Elayirampannai' >Elayirampannai</option><option value='Ellenabad' >Ellenabad</option><option value='Elumalai' >Elumalai</option><option value='Elur' >Elur</option><option value='Eluru' >Eluru</option><option value='Enikapadu' >Enikapadu</option><option value='Eral' >Eral</option><option value='Eranholi' >Eranholi</option><option value='Eraniel' >Eraniel</option><option value='Erattupetta' >Erattupetta</option><option value='Erich' >Erich</option><option value='Eriodu' >Eriodu</option><option value='Ernakulam' >Ernakulam</option><option value='Erode' >Erode</option><option value='Erumaipatti' >Erumaipatti</option><option value='Eruvadi' >Eruvadi</option><option value='Eruvatti' >Eruvatti</option><option value='Etah' >Etah</option><option value='Etawah' >Etawah</option><option value='Ethapur' >Ethapur</option><option value='Ettaiyapuram' >Ettaiyapuram</option><option value='Ettimadai' >Ettimadai</option><option value='Ettumanoor' >Ettumanoor</option><option value='Ezhudesam' >Ezhudesam</option><option value='Faizabad' >Faizabad</option><option value='Faizabad Cantonment' >Faizabad Cantonment</option><option value='Faizganj' >Faizganj</option><option value='Faizpur' >Faizpur</option><option value='Fakirtakya' >Fakirtakya</option><option value='Falakata' >Falakata</option><option value='Falna' >Falna</option><option value='Farah' >Farah</option><option value='Farakhpur' >Farakhpur</option><option value='Farakka' >Farakka</option><option value='Faridabad' >Faridabad</option><option value='Faridkot' >Faridkot</option><option value='Faridnagar' >Faridnagar</option><option value='Faridpur' >Faridpur</option><option value='Faridpur Cantonment' >Faridpur Cantonment</option><option value='Fariha' >Fariha</option><option value='Farooqnagar' >Farooqnagar</option><option value='Farrukhabad' >Farrukhabad</option><option value='Fateh Nangal' >Fateh Nangal</option><option value='Fatehabad' >Fatehabad</option><option value='Fatehganj Pashchimi' >Fatehganj Pashchimi</option><option value='Fatehganj Purvi' >Fatehganj Purvi</option><option value='Fatehgarh' >Fatehgarh</option><option value='Fatehgarh Churian' >Fatehgarh Churian</option><option value='Fatehgarh Sahib' >Fatehgarh Sahib</option><option value='Fatehnagar' >Fatehnagar</option><option value='Fatehpur' >Fatehpur</option><option value='Fatehpur Chaurasi' >Fatehpur Chaurasi</option><option value='Fatehpur Sikri' >Fatehpur Sikri</option><option value='Fatellapur' >Fatellapur</option><option value='Fatwa' >Fatwa</option><option value='Fazilka' >Fazilka</option><option value='Fekari' >Fekari</option><option value='Feroke' >Feroke</option><option value='Fertilizer Corporation of Indi' >Fertilizer Corporation of Indi</option><option value='Firozabad' >Firozabad</option><option value='Firozpur' >Firozpur</option><option value='Firozpur Cantonment' >Firozpur Cantonment</option><option value='Firozpur Jhirka' >Firozpur Jhirka</option><option value='Flelanganj' >Flelanganj</option><option value='Forbesganj' >Forbesganj</option><option value='Fort Gloster' >Fort Gloster</option><option value='Frezarpur' >Frezarpur</option><option value='Gabberia' >Gabberia</option><option value='Gadag' >Gadag</option><option value='Gadarpur' >Gadarpur</option><option value='Gadarwara' >Gadarwara</option><option value='Gadchiroli' >Gadchiroli</option><option value='Gaddiannaram' >Gaddiannaram</option><option value='Gadhda' >Gadhda</option><option value='Gadhinghaj' >Gadhinghaj</option><option value='Gadhra' >Gadhra</option><option value='Gadigachha' >Gadigachha</option><option value='Gadwal' >Gadwal</option><option value='Gagret' >Gagret</option><option value='Gairatganj' >Gairatganj</option><option value='Gairkata' >Gairkata</option><option value='Gajapathinagaram' >Gajapathinagaram</option><option value='Gajapati' >Gajapati</option><option value='Gajendragarh' >Gajendragarh</option><option value='Gajraula' >Gajraula</option><option value='Gajsinghpur' >Gajsinghpur</option><option value='Gajularega' >Gajularega</option><option value='Gajuvaka' >Gajuvaka</option><option value='Gajwel' >Gajwel</option><option value='Gakulnagar' >Gakulnagar</option><option value='Galiakot' >Galiakot</option><option value='Ganapathipuram' >Ganapathipuram</option><option value='Gandai' >Gandai</option><option value='Gandarbat' >Gandarbat</option><option value='Gandevi' >Gandevi</option><option value='Gandhi Sagar Hydel Colony' >Gandhi Sagar Hydel Colony</option><option value='Gandhidham' >Gandhidham</option><option value='Gandhigram' >Gandhigram</option><option value='Gandhinagar' >Gandhinagar</option><option value='Ganeshgudi' >Ganeshgudi</option><option value='Ganeshpur' >Ganeshpur</option><option value='Ganga Ghat' >Ganga Ghat</option><option value='Gangaikondan' >Gangaikondan</option><option value='Gangakher' >Gangakher</option><option value='Ganganagar' >Ganganagar</option><option value='Gangarampur' >Gangarampur</option><option value='Gangavalli' >Gangavalli</option><option value='Gangawati' >Gangawati</option><option value='Gangoh' >Gangoh</option><option value='Gangoli' >Gangoli</option><option value='Gangotri' >Gangotri</option><option value='Gangtok' >Gangtok</option><option value='Ganguvarpatti' >Ganguvarpatti</option><option value='Ganj Muradabad' >Ganj Muradabad</option><option value='Ganjam' >Ganjam</option><option value='Ganjbasoda' >Ganjbasoda</option><option value='Gannaur' >Gannaur</option><option value='Gannavaram' >Gannavaram</option><option value='Garalgachha' >Garalgachha</option><option value='Garautha' >Garautha</option><option value='Garbeta Amlagora' >Garbeta Amlagora</option><option value='Gardhiwala' >Gardhiwala</option><option value='Garhakota' >Garhakota</option><option value='Garhbeta' >Garhbeta</option><option value='Garhi Malhara' >Garhi Malhara</option><option value='Garhi Pukhta' >Garhi Pukhta</option><option value='Garhmukteshwar' >Garhmukteshwar</option><option value='Garhshankar' >Garhshankar</option><option value='Garhwa' >Garhwa</option><option value='Gariaband' >Gariaband</option><option value='Gariadhar' >Gariadhar</option><option value='Garimellapadu' >Garimellapadu</option><option value='Garoth' >Garoth</option><option value='Garshyamnagar' >Garshyamnagar</option><option value='Garui' >Garui</option><option value='Garulia' >Garulia</option><option value='Garwa' >Garwa</option><option value='Gauchar' >Gauchar</option><option value='Gaura Barahaj' >Gaura Barahaj</option><option value='Gaurela' >Gaurela</option><option value='Gauri Bazar' >Gauri Bazar</option><option value='Gauribidanur' >Gauribidanur</option><option value='Gauripur' >Gauripur</option><option value='Gausganj' >Gausganj</option><option value='Gautam Buddha Nagar' >Gautam Buddha Nagar</option><option value='Gautapura' >Gautapura</option><option value='Gawan' >Gawan</option><option value='Gaya' >Gaya</option><option value='Gayespur' >Gayespur</option><option value='Gazipur' >Gazipur</option><option value='Gelhapani' >Gelhapani</option><option value='Gevrai' >Gevrai</option><option value='Gezing' >Gezing</option><option value='Ghagga' >Ghagga</option><option value='Ghamarwin' >Ghamarwin</option><option value='Ghanaur' >Ghanaur</option><option value='Ghansor' >Ghansor</option><option value='Ghantapada' >Ghantapada</option><option value='Gharghoda' >Gharghoda</option><option value='Ghatal' >Ghatal</option><option value='Ghatampur' >Ghatampur</option><option value='Ghatanji' >Ghatanji</option><option value='Ghatkesar' >Ghatkesar</option><option value='Ghatsila' >Ghatsila</option><option value='Ghaziabad' >Ghaziabad</option><option value='Ghazipur' >Ghazipur</option><option value='Ghiror' >Ghiror</option><option value='Gho Manhasan' >Gho Manhasan</option><option value='Ghogha' >Ghogha</option><option value='Ghoghardiha' >Ghoghardiha</option><option value='Ghorabandha' >Ghorabandha</option><option value='Ghorawal' >Ghorawal</option><option value='Ghorsala' >Ghorsala</option><option value='Ghosi' >Ghosi</option><option value='Ghosia Bazar' >Ghosia Bazar</option><option value='Ghoti' >Ghoti</option><option value='Ghraunda' >Ghraunda</option><option value='Ghughuli' >Ghughuli</option><option value='Ghugus' >Ghugus</option><option value='Ghulewadi' >Ghulewadi</option><option value='Ghuwara' >Ghuwara</option><option value='Gidam' >Gidam</option><option value='Giddalur' >Giddalur</option><option value='Giddarbaha' >Giddarbaha</option><option value='Gidi' >Gidi</option><option value='Gingi' >Gingi</option><option value='Giridih' >Giridih</option><option value='Goa' >Goa</option><option value='Goaljan' >Goaljan</option><option value='Goalpara' >Goalpara</option><option value='Goasafat' >Goasafat</option><option value='Gobardanga' >Gobardanga</option><option value='Gobindapur' >Gobindapur</option><option value='Gobindgarh' >Gobindgarh</option><option value='Gobindpur' >Gobindpur</option><option value='Gobra Nawapara' >Gobra Nawapara</option><option value='Godavarikhani' >Godavarikhani</option><option value='Godda' >Godda</option><option value='Godhar' >Godhar</option><option value='Godhra' >Godhra</option><option value='Godoli' >Godoli</option><option value='Gogapur' >Gogapur</option><option value='Gogri Jamalpur' >Gogri Jamalpur</option><option value='Gohad' >Gohad</option><option value='Gohana' >Gohana</option><option value='Gohand' >Gohand</option><option value='Gohpur' >Gohpur</option><option value='Gokak' >Gokak</option><option value='Gokak Falls' >Gokak Falls</option><option value='Gokul' >Gokul</option><option value='Gola Bazar' >Gola Bazar</option><option value='Gola Gokarannath' >Gola Gokarannath</option><option value='Golaghat' >Golaghat</option><option value='Golakganj' >Golakganj</option><option value='Golphalbari' >Golphalbari</option><option value='Gomoh' >Gomoh</option><option value='Gonda' >Gonda</option><option value='Gondal' >Gondal</option><option value='Gondia' >Gondia</option><option value='Gondiya' >Gondiya</option><option value='Goniana' >Goniana</option><option value='Gonikoppal' >Gonikoppal</option><option value='Gopalapatnam' >Gopalapatnam</option><option value='Gopalasamudram' >Gopalasamudram</option><option value='Gopalganj' >Gopalganj</option><option value='Gopalur' >Gopalur</option><option value='Gopamau' >Gopamau</option><option value='Gopichettipalaiyam' >Gopichettipalaiyam</option><option value='Gopiganj' >Gopiganj</option><option value='Gopinathpur' >Gopinathpur</option><option value='Gora Bazar' >Gora Bazar</option><option value='Gorah Salathian' >Gorah Salathian</option><option value='Gorakhpur' >Gorakhpur</option><option value='Goraya' >Goraya</option><option value='Goredi Chancha' >Goredi Chancha</option><option value='Gorkakhpur' >Gorkakhpur</option><option value='Gormi' >Gormi</option><option value='Gorrekunta' >Gorrekunta</option><option value='Gorur' >Gorur</option><option value='Gosainganj' >Gosainganj</option><option value='Gossaigaon' >Gossaigaon</option><option value='Gothra' >Gothra</option><option value='Gottikere' >Gottikere</option><option value='Govardhan' >Govardhan</option><option value='Greater Noida' >Greater Noida</option><option value='GSFC Complex' >GSFC Complex</option><option value='Gua' >Gua</option><option value='Gubbi' >Gubbi</option><option value='Gudalur' >Gudalur</option><option value='Gudari' >Gudari</option><option value='Gudibanda' >Gudibanda</option><option value='Gudivada' >Gudivada</option><option value='Gudiyattam' >Gudiyattam</option><option value='Gudur' >Gudur</option><option value='Guduvanchery' >Guduvanchery</option><option value='Guhagar' >Guhagar</option><option value='Guirim' >Guirim</option><option value='Gulabpura' >Gulabpura</option><option value='Gulaothi' >Gulaothi</option><option value='Gulariya' >Gulariya</option><option value='Gulariya Bhindara' >Gulariya Bhindara</option><option value='Gulbarga' >Gulbarga</option><option value='Guledgudda' >Guledgudda</option><option value='Gulmarg' >Gulmarg</option><option value='Guma' >Guma</option><option value='Gumia' >Gumia</option><option value='Gumla' >Gumla</option><option value='Gummidipoondi' >Gummidipoondi</option><option value='Guna' >Guna</option><option value='Gundlupet' >Gundlupet</option><option value='Gunnaur' >Gunnaur</option><option value='Guntakal' >Guntakal</option><option value='Guntur' >Guntur</option><option value='Gunupur' >Gunupur</option><option value='Gurdaha' >Gurdaha</option><option value='Gurdaspur' >Gurdaspur</option><option value='Gurgaon' >Gurgaon</option><option value='Gurh' >Gurh</option><option value='Guriahati' >Guriahati</option><option value='Gurmatkal' >Gurmatkal</option><option value='Gursahaiganj' >Gursahaiganj</option><option value='Gursarai' >Gursarai</option><option value='Guru Har Sahai' >Guru Har Sahai</option><option value='Guruvayur' >Guruvayur</option><option value='Guskhara' >Guskhara</option><option value='Guti' >Guti</option><option value='Guwahati' >Guwahati</option><option value='Gwalior' >Gwalior</option><option value='Gyanpur' >Gyanpur</option><option value='Habibpur' >Habibpur</option><option value='Habra' >Habra</option><option value='Hadgaon' >Hadgaon</option><option value='Hafizpur' >Hafizpur</option><option value='Haflong' >Haflong</option><option value='Haidergarh' >Haidergarh</option><option value='Hailakandi' >Hailakandi</option><option value='Haileymandi' >Haileymandi</option><option value='Hajan' >Hajan</option><option value='Hajira INA' >Hajira INA</option><option value='Haldaur' >Haldaur</option><option value='Haldia' >Haldia</option><option value='Haldibari' >Haldibari</option><option value='Haldwani' >Haldwani</option><option value='Halisahar' >Halisahar</option><option value='Haliyal' >Haliyal</option><option value='Halol' >Halol</option><option value='Haludbani' >Haludbani</option><option value='Halvad' >Halvad</option><option value='Hamirpur' >Hamirpur</option><option value='Hamirpur' >Hamirpur</option><option value='Hamren' >Hamren</option><option value='Handia' >Handia</option><option value='Handiaya' >Handiaya</option><option value='Handwara' >Handwara</option><option value='Hangal' >Hangal</option><option value='Hansi' >Hansi</option><option value='Hansot' >Hansot</option><option value='Hanumana' >Hanumana</option><option value='Hanumangarh' >Hanumangarh</option><option value='Hanumanthampatti' >Hanumanthampatti</option><option value='Haora' >Haora</option><option value='Hapur' >Hapur</option><option value='Harda' >Harda</option><option value='Hardoi' >Hardoi</option><option value='Harduaganj' >Harduaganj</option><option value='Hargaon' >Hargaon</option><option value='Harharia Chak' >Harharia Chak</option><option value='Hariana' >Hariana</option><option value='Haridwar' >Haridwar</option><option value='Harihar' >Harihar</option><option value='Hariharpur' >Hariharpur</option><option value='Harij' >Harij</option><option value='Harindanga' >Harindanga</option><option value='Haringhata' >Haringhata</option><option value='Haripad' >Haripad</option><option value='Haripur' >Haripur</option><option value='Harishpur' >Harishpur</option><option value='Harnai Beach' >Harnai Beach</option><option value='Harpalpur' >Harpalpur</option><option value='Harpanahalli' >Harpanahalli</option><option value='Harrai' >Harrai</option><option value='Harraiya' >Harraiya</option><option value='Harsud' >Harsud</option><option value='Harur' >Harur</option><option value='Harveypatti' >Harveypatti</option><option value='Hasayan' >Hasayan</option><option value='Hassan' >Hassan</option><option value='Hastinapur' >Hastinapur</option><option value='Hata' >Hata</option><option value='Hatgachha' >Hatgachha</option><option value='Hathin' >Hathin</option><option value='Hathras' >Hathras</option><option value='Hatibandha' >Hatibandha</option><option value='Hatkachora' >Hatkachora</option><option value='Hatod' >Hatod</option><option value='Hatpipalya' >Hatpipalya</option><option value='Hatsimla' >Hatsimla</option><option value='Hatta' >Hatta</option><option value='Hatti' >Hatti</option><option value='Hatti Gold Mines' >Hatti Gold Mines</option><option value='Hauli' >Hauli</option><option value='Hauraghat' >Hauraghat</option><option value='Haveri' >Haveri</option><option value='Haveri District' >Haveri District</option><option value='Hazaribag' >Hazaribag</option><option value='Hazaribagh' >Hazaribagh</option><option value='Hebbagodi' >Hebbagodi</option><option value='Hebbalu' >Hebbalu</option><option value='Hebri' >Hebri</option><option value='Heggadadevanakote' >Heggadadevanakote</option><option value='Herbertpur' >Herbertpur</option><option value='Heriok' >Heriok</option><option value='Herohalli' >Herohalli</option><option value='Hesla' >Hesla</option><option value='Hidkal' >Hidkal</option><option value='Highways' >Highways</option><option value='Hijuli' >Hijuli</option><option value='Hilsa' >Hilsa</option><option value='Himatnagar' >Himatnagar</option><option value='Hindalgi' >Hindalgi</option><option value='Hindaun' >Hindaun</option><option value='Hindoria' >Hindoria</option><option value='Hindupur' >Hindupur</option><option value='Hindustan Cables Town' >Hindustan Cables Town</option><option value='Hinganghat' >Hinganghat</option><option value='Hingoli' >Hingoli</option><option value='Hinjilikatu' >Hinjilikatu</option><option value='Hirakud' >Hirakud</option><option value='Hiranagar' >Hiranagar</option><option value='Hirapur' >Hirapur</option><option value='Hirekerur' >Hirekerur</option><option value='Hiriyur' >Hiriyur</option><option value='Hisar' >Hisar</option><option value='Hissar' >Hissar</option><option value='Hisua' >Hisua</option><option value='Hnahthial' >Hnahthial</option><option value='Hodal' >Hodal</option><option value='Hojai' >Hojai</option><option value='Holalkere' >Holalkere</option><option value='Hole Narsipur' >Hole Narsipur</option><option value='Homnabad' >Homnabad</option><option value='Honavar' >Honavar</option><option value='Honnali' >Honnali</option><option value='Hooghly' >Hooghly</option><option value='Hosabettu' >Hosabettu</option><option value='Hosakote' >Hosakote</option><option value='Hosanagara' >Hosanagara</option><option value='Hosangadi' >Hosangadi</option><option value='Hosdurga' >Hosdurga</option><option value='Hoshangabad' >Hoshangabad</option><option value='Hoshiarpur' >Hoshiarpur</option><option value='Hoskote' >Hoskote</option><option value='Hospet' >Hospet</option><option value='Hosur' >Hosur</option><option value='Howrah' >Howrah</option><option value='Hubbathala' >Hubbathala</option><option value='Hubli' >Hubli</option><option value='Hugli' >Chunchura</option><option value='Hukeri' >Hukeri</option><option value='Hukumpeta' >Hukumpeta</option><option value='Huligal' >Huligal</option><option value='Humaipur' >Humaipur</option><option value='Hunasagi' >Hunasagi</option><option value='Hunasamaranahalli' >Hunasamaranahalli</option><option value='Hungund' >Hungund</option><option value='Hunsur' >Hunsur</option><option value='Hupari' >Hupari</option><option value='Husainabad' >Husainabad</option><option value='Hussainpur' >Hussainpur</option><option value='Huvina Hadagalli' >Huvina Hadagalli</option><option value='Hyderabad' >Hyderabad</option><option value='Ibrahimpur' >Ibrahimpur</option><option value='Ichalkaranji' >Ichalkaranji</option><option value='Ichchapuram' >Ichchapuram</option><option value='Ichchhapor' >Ichchhapor</option><option value='Ichha Pur Defence Estate' >Ichha Pur Defence Estate</option><option value='Ichhawar' >Ichhawar</option><option value='Idappadi' >Idappadi</option><option value='Idar' >Idar</option><option value='Idikarai' >Idikarai</option><option value='Idukki' >Idukki</option><option value='Igatpuri' >Igatpuri</option><option value='Iglas' >Iglas</option><option value='Ikauna' >Ikauna</option><option value='Iklehra' >Iklehra</option><option value='Ilampillai' >Ilampillai</option><option value='Ilanji' >Ilanji</option><option value='Ilkal' >Ilkal</option><option value='Iltifatganj Bazar' >Iltifatganj Bazar</option><option value='Iluppaiyurani' >Iluppaiyurani</option><option value='Iluppur' >Iluppur</option><option value='Imphal' >Imphal</option><option value='Imphal East' >Imphal East</option><option value='Imphal West' >Imphal West</option><option value='Inam Karur' >Inam Karur</option><option value='Indapur' >Indapur</option><option value='Indergarh' >Indergarh</option><option value='Indi' >Indi</option><option value='Indian Telephone Industry Mank' >Indian Telephone Industry Mank</option><option value='Indore' >Indore</option><option value='Indragarh' >Indragarh</option><option value='Indranagar' >Indranagar</option><option value='Indri' >Indri</option><option value='Ingraj Bazar' >Ingraj Bazar</option><option value='Injambakkam' >Injambakkam</option><option value='Iringaprom' >Iringaprom</option><option value='Irinjalakuda' >Irinjalakuda</option><option value='Iriveri' >Iriveri</option><option value='Irugur' >Irugur</option><option value='Isagarh' >Isagarh</option><option value='Islamnagar' >Islamnagar</option><option value='Isnapur' >Isnapur</option><option value='Isri' >Isri</option><option value='Itanagar' >Itanagar</option><option value='Itarsi' >Itarsi</option><option value='Itaunja' >Itaunja</option><option value='Itimadpur' >Itimadpur</option><option value='Jabalpur' >Jabalpur</option><option value='Jabalpur Cantonment' >Jabalpur Cantonment</option><option value='Jabalpur G.C.F' >Jabalpur G.C.F</option><option value='Jadugora' >Jadugora</option><option value='Jafarpur' >Jafarpur</option><option value='Jaffrabad' >Jaffrabad</option><option value='Jafrabad' >Jafrabad</option><option value='Jagadanandapur' >Jagadanandapur</option><option value='Jagadhri' >Jagadhri</option><option value='Jagalur' >Jagalur</option><option value='Jagannathpur' >Jagannathpur</option><option value='Jagathala' >Jagathala</option><option value='Jagatsinghapur' >Jagatsinghapur</option><option value='Jagatsinghpur' >Jagatsinghpur</option><option value='Jagdalpur' >Jagdalpur</option><option value='Jagdishpur' >Jagdishpur</option><option value='Jagdispur' >Jagdispur</option><option value='Jaggayyapeta' >Jaggayyapeta</option><option value='Jagiroad' >Jagiroad</option><option value='Jagiroad Paper Mill' >Jagiroad Paper Mill</option><option value='Jagner' >Jagner</option><option value='Jagraon' >Jagraon</option><option value='Jagtaj' >Jagtaj</option><option value='Jagtial' >Jagtial</option><option value='Jahangirabad' >Jahangirabad</option><option value='Jahangirpur' >Jahangirpur</option><option value='Jahazpur' >Jahazpur</option><option value='Jaintia Hills' >Jaintia Hills</option><option value='Jaipur' >Jaipur</option><option value='Jairampur' >Jairampur</option><option value='Jais' >Jais</option><option value='Jaisalmer' >Jaisalmer</option><option value='Jaiselmer' >Jaiselmer</option><option value='Jaisinghnagar' >Jaisinghnagar</option><option value='Jaisinghpur' >Jaisinghpur</option><option value='Jaitaran' >Jaitaran</option><option value='Jaithara' >Jaithara</option><option value='Jaithari' >Jaithari</option><option value='Jaitu' >Jaitu</option><option value='Jaitwara' >Jaitwara</option><option value='Jajapur' >Jajapur</option><option value='Jajpur' >Jajpur</option><option value='Jakhal Mandi' >Jakhal Mandi</option><option value='Jala Kendua' >Jala Kendua</option><option value='Jalakandapuram' >Jalakandapuram</option><option value='Jalali' >Jalali</option><option value='Jalalpore' >Jalalpore</option><option value='Jalalpur' >Jalalpur</option><option value='Jalandhar' >Jalandhar</option><option value='Jalandhar Cantonment' >Jalandhar Cantonment</option><option value='Jalaun' >Jalaun</option><option value='Jalda' >Jalda</option><option value='Jaldhaka' >Jaldhaka</option><option value='Jalesar' >Jalesar</option><option value='Jaleswar' >Jaleswar</option><option value='Jalgaon' >Jalgaon</option><option value='Jalkhura' >Jalkhura</option><option value='Jalladiampet' >Jalladiampet</option><option value='Jallaram Kamanpur' >Jallaram Kamanpur</option><option value='Jalna' >Jalna</option><option value='Jalore' >Jalore</option><option value='Jalpaiguri' >Jalpaiguri</option><option value='Jamadoba' >Jamadoba</option><option value='Jamai' >Jamai</option><option value='Jamalpur' >Jamalpur</option><option value='Jambai' >Jambai</option><option value='Jambusar' >Jambusar</option><option value='Jamhaur' >Jamhaur</option><option value='Jamjodhpur' >Jamjodhpur</option><option value='Jamkhandi' >Jamkhandi</option><option value='Jamkhed' >Jamkhed</option><option value='Jammalamadugu' >Jammalamadugu</option><option value='Jammu' >Jammu</option><option value='Jammu Cantonment' >Jammu Cantonment</option><option value='Jammu Tawi' >Jammu Tawi</option><option value='Jamnagar' >Jamnagar</option><option value='Jamshedpur' >Jamshedpur</option><option value='Jamshila' >Jamshila</option><option value='Jamtara' >Jamtara</option><option value='Jamui' >Jamui</option><option value='Jamuria' >Jamuria</option><option value='Janakpur Road' >Janakpur Road</option><option value='Jandiala' >Jandiala</option><option value='Jangampalli' >Jangampalli</option><option value='Jangaon' >Jangaon</option><option value='Janjgir' >Champa</option><option value='Janpur' >Janpur</option><option value='Jansath' >Jansath</option><option value='Jaora' >Jaora</option><option value='Jarangdih' >Jarangdih</option><option value='Jaridih' >Jaridih</option><option value='Jarjapupeta' >Jarjapupeta</option><option value='Jarwal' >Jarwal</option><option value='Jasdan' >Jasdan</option><option value='Jashpur' >Jashpur</option><option value='Jashpurnagar' >Jashpurnagar</option><option value='Jasidih' >Jasidih</option><option value='Jaspur' >Jaspur</option><option value='Jasrana' >Jasrana</option><option value='Jaswantnagar' >Jaswantnagar</option><option value='Jatachhapar' >Jatachhapar</option><option value='Jatara' >Jatara</option><option value='Jatari' >Jatari</option><option value='Jatni' >Jatni</option><option value='Jaunpur' >Jaunpur</option><option value='Jaunpur District' >Jaunpur District</option><option value='Jawad' >Jawad</option><option value='Jawaharnagar' >Jawaharnagar</option><option value='Jawai' >Jawai</option><option value='Jawala Mukhi' >Jawala Mukhi</option><option value='Jawar' >Jawar</option><option value='Jawhar' >Jawhar</option><option value='Jayankondam' >Jayankondam</option><option value='Jaygaon' >Jaygaon</option><option value='Jaynagar' >Jaynagar</option><option value='Jaynagar' >Majilpur</option><option value='Jaypur' >Jaypur</option><option value='Jaysingpur' >Jaysingpur</option><option value='Jehanabad' >Jehanabad</option><option value='Jejuri' >Jejuri</option><option value='Jemari' >Jemari</option><option value='Jemari Township' >Jemari Township</option><option value='Jena' >Jena</option><option value='Jeronkhalsa' >Jeronkhalsa</option><option value='Jetalsar' >Jetalsar</option><option value='Jetia' >Jetia</option><option value='Jetpur' >Jetpur</option><option value='Jevargi' >Jevargi</option><option value='Jewar' >Jewar</option><option value='Jeypore' >Jeypore</option><option value='Jha Jha' >Jha Jha</option><option value='Jhabrera' >Jhabrera</option><option value='Jhabua' >Jhabua</option><option value='Jhagrakhand' >Jhagrakhand</option><option value='Jhajjar' >Jhajjar</option><option value='Jhalawar' >Jhalawar</option><option value='Jhalida' >Jhalida</option><option value='Jhalrapatan' >Jhalrapatan</option><option value='Jhalu' >Jhalu</option><option value='Jhanjharpur' >Jhanjharpur</option><option value='Jhansi' >Jhansi</option><option value='Jhansi Cantonment' >Jhansi Cantonment</option><option value='Jhansi Railway Settlement' >Jhansi Railway Settlement</option><option value='Jhargram' >Jhargram</option><option value='Jharia' >Jharia</option><option value='Jharia Khas' >Jharia Khas</option><option value='Jharsuguda' >Jharsuguda</option><option value='Jhinjhak' >Jhinjhak</option><option value='Jhinjhana' >Jhinjhana</option><option value='Jhinkpani' >Jhinkpani</option><option value='Jhorhat' >Jhorhat</option><option value='Jhumpura' >Jhumpura</option><option value='Jhumri Tilaiya' >Jhumri Tilaiya</option><option value='Jhundpura' >Jhundpura</option><option value='Jhunjhunun' >Jhunjhunun</option><option value='Jhusi' >Jhusi</option><option value='Jhusi Kohna' >Jhusi Kohna</option><option value='Jiaganj' >Azimganj</option><option value='Jind' >Jind</option><option value='Jintur' >Jintur</option><option value='Jiran' >Jiran</option><option value='Jirapur' >Jirapur</option><option value='Jiribam' >Jiribam</option><option value='Jiyanpur' >Jiyanpur</option><option value='Jobat' >Jobat</option><option value='Jobner' >Jobner</option><option value='Joda' >Joda</option><option value='Jodhpur' >Jodhpur</option><option value='Jodiya' >Jodiya</option><option value='Jog Falls' >Jog Falls</option><option value='Jogbani' >Jogbani</option><option value='Jogendranagar' >Jogendranagar</option><option value='Jogighopa' >Jogighopa</option><option value='Jogindarnagar' >Jogindarnagar</option><option value='Joka' >Joka</option><option value='Jolarpet' >Jolarpet</option><option value='Jonai Bazar' >Jonai Bazar</option><option value='Jorapokhar' >Jorapokhar</option><option value='Jorethang' >Jorethang</option><option value='Jorhat' >Jorhat</option><option value='Joshimath' >Joshimath</option><option value='Joshipura' >Joshipura</option><option value='Jot Kamal' >Jot Kamal</option><option value='Joura' >Joura</option><option value='Jourian' >Jourian</option><option value='Joya' >Joya</option><option value='Jua' >Jua</option><option value='Jubbal' >Jubbal</option><option value='Jugial' >Jugial</option><option value='Jugsalai' >Jugsalai</option><option value='Juhnjhunun' >Juhnjhunun</option><option value='Julana' >Julana</option><option value='Junagadh' >Junagadh</option><option value='Junagarh' >Junagarh</option><option value='Junnar' >Junnar</option><option value='Jutogh' >Jutogh</option><option value='Jyoti Khuria' >Jyoti Khuria</option><option value='Jyotiba Phule Nagar' >Jyotiba Phule Nagar</option><option value='Kabini Colony' >Kabini Colony</option><option value='Kabnur' >Kabnur</option><option value='Kabrai' >Kabrai</option><option value='Kachhauna Patseni' >Kachhauna Patseni</option><option value='Kachhla' >Kachhla</option><option value='Kachhwa' >Kachhwa</option><option value='Kachnal Gosain' >Kachnal Gosain</option><option value='Kachu Pukur' >Kachu Pukur</option><option value='Kadachira' >Kadachira</option><option value='Kadalundi' >Kadalundi</option><option value='Kadamakkudy' >Kadamakkudy</option><option value='Kadambur' >Kadambur</option><option value='Kadapa' >Kadapa</option><option value='Kadathur' >Kadathur</option><option value='Kadaura' >Kadaura</option><option value='Kadayal' >Kadayal</option><option value='Kadayampatti' >Kadayampatti</option><option value='Kadayanallur' >Kadayanallur</option><option value='Kadi' >Kadi</option><option value='Kadiapatti' >Kadiapatti</option><option value='Kadipur' >Kadipur</option><option value='Kadiri' >Kadiri</option><option value='Kadirur' >Kadirur</option><option value='Kadodara' >Kadodara</option><option value='Kadungallur' >Kadungallur</option><option value='Kadur' >Kadur</option><option value='Kagal' >Kagal</option><option value='Kagaznagar' >Kagaznagar</option><option value='Kaikalur' >Kaikalur</option><option value='Kailaras' >Kailaras</option><option value='Kailasahar' >Kailasahar</option><option value='Kailashpur' >Kailashpur</option><option value='Kailudih' >Kailudih</option><option value='Kaimganj' >Kaimganj</option><option value='Kaimur' >Kaimur</option><option value='Kairana' >Kairana</option><option value='Kaithal' >Kaithal</option><option value='Kaithun' >Kaithun</option><option value='Kajora' >Kajora</option><option value='Kakarhati' >Kakarhati</option><option value='Kakching' >Kakching</option><option value='Kakching Khunou' >Kakching Khunou</option><option value='Kakdihi' >Kakdihi</option><option value='Kakdwip' >Kakdwip</option><option value='Kakgaina' >Kakgaina</option><option value='Kakinada' >Kakinada</option><option value='Kakkodi' >Kakkodi</option><option value='Kakod' >Kakod</option><option value='Kakori' >Kakori</option><option value='Kakrala' >Kakrala</option><option value='Kala Amb' >Kala Amb</option><option value='Kaladungi' >Kaladungi</option><option value='Kalady' >Kalady</option><option value='Kalagarh' >Kalagarh</option><option value='Kalahandi' >Kalahandi</option><option value='Kalaikunda' >Kalaikunda</option><option value='Kalakkad' >Kalakkad</option><option value='Kalamassery' >Kalamassery</option><option value='Kalamb' >Kalamb</option><option value='Kalambur' >Kalambur</option><option value='Kalamnuri' >Kalamnuri</option><option value='Kalanaur' >Kalanaur</option><option value='Kalangat' >Kalangat</option><option value='Kalanur' >Kalanur</option><option value='Kalanwali' >Kalanwali</option><option value='Kalapatti' >Kalapatti</option><option value='Kalappanaickenpatti' >Kalappanaickenpatti</option><option value='Kalara' >Kalara</option><option value='Kalas' >Kalas</option><option value='Kalavad' >Kalavad</option><option value='Kalavai' >Kalavai</option><option value='Kalayat' >Kalayat</option><option value='Kalghatgi' >Kalghatgi</option><option value='Kali' >Kali</option><option value='Kaliawadi' >Kaliawadi</option><option value='Kalichhapar' >Kalichhapar</option><option value='Kalikapur' >Kalikapur</option><option value='Kalimpong' >Kalimpong</option><option value='Kalinagar' >Kalinagar</option><option value='Kalinjur' >Kalinjur</option><option value='Kaliyaganj' >Kaliyaganj</option><option value='Kaliyakkavilai' >Kaliyakkavilai</option><option value='Kalka' >Kalka</option><option value='Kallakkurichi' >Kallakkurichi</option><option value='Kallakudi' >Kallakudi</option><option value='Kalliasseri' >Kalliasseri</option><option value='Kallidaikurichchi' >Kallidaikurichchi</option><option value='Kallukuttam' >Kallukuttam</option><option value='Kallupatti' >Kallupatti</option><option value='Kallur' >Kallur</option><option value='Kalmeshwar' >Kalmeshwar</option><option value='Kalna' >Kalna</option><option value='Kalol' >Kalol</option><option value='Kalol INA' >Kalol INA</option><option value='Kalpa' >Kalpa</option><option value='Kalpakkam' >Kalpakkam</option><option value='Kalpetta' >Kalpetta</option><option value='Kalpi' >Kalpi</option><option value='Kalugumalai' >Kalugumalai</option><option value='Kalundre' >Kalundre</option><option value='Kalwakurthy' >Kalwakurthy</option><option value='Kalyan' >Kalyan</option><option value='Kalyandurg' >Kalyandurg</option><option value='Kalyani' >Kalyani</option><option value='Kamakhyanagar' >Kamakhyanagar</option><option value='Kamalganj' >Kamalganj</option><option value='Kamalpur' >Kamalpur</option><option value='Kaman' >Kaman</option><option value='Kamareddi' >Kamareddi</option><option value='Kamareddy' >Kamareddy</option><option value='Kamarhati' >Kamarhati</option><option value='Kamayagoundanpatti' >Kamayagoundanpatti</option><option value='Kambainallur' >Kambainallur</option><option value='Kambam' >Kambam</option><option value='Kampil' >Kampil</option><option value='Kampli' >Kampli</option><option value='Kampur Town' >Kampur Town</option><option value='Kamrup' >Kamrup</option><option value='Kamthi' >Kamthi</option><option value='Kamthi Cantonment' >Kamthi Cantonment</option><option value='Kamuthi' >Kamuthi</option><option value='Kanad' >Kanad</option><option value='Kanadukathan' >Kanadukathan</option><option value='Kanaipur' >Kanaipur</option><option value='Kanakapura' >Kanakapura</option><option value='Kanakkampalayam' >Kanakkampalayam</option><option value='Kanakpur' >Kanakpur</option><option value='Kanam' >Kanam</option><option value='Kanapaka' >Kanapaka</option><option value='Kanchanpur' >Kanchanpur</option><option value='Kanchipuram' >Kanchipuram</option><option value='Kanchrapara' >Kanchrapara</option><option value='Kandanur' >Kandanur</option><option value='Kandari' >Kandari</option><option value='Kandhamal' >Kandhamal</option><option value='Kandhar' >Kandhar</option><option value='Kandhla' >Kandhla</option><option value='Kandi' >Kandi</option><option value='Kandla' >Kandla</option><option value='Kandra' >Kandra</option><option value='Kandri' >Kandri</option><option value='Kandri II' >Kandri II</option><option value='Kandwa' >Kandwa</option><option value='Kangayam' >Kangayam</option><option value='Kangayampalayam' >Kangayampalayam</option><option value='Kangeyanallur' >Kangeyanallur</option><option value='Kangra' >Kangra</option><option value='Kangrali BK' >Kangrali BK</option><option value='Kangrali KH' >Kangrali KH</option><option value='Kanhan' >Kanhan</option><option value='Kanhangad' >Kanhangad</option><option value='Kanhirode' >Kanhirode</option><option value='Kanigiri' >Kanigiri</option><option value='Kanina' >Kanina</option><option value='Kanithi' >Kanithi</option><option value='Kaniyur' >Kaniyur</option><option value='Kanjari' >Kanjari</option><option value='Kanjikkuzhi' >Kanjikkuzhi</option><option value='Kanjikode' >Kanjikode</option><option value='Kanjikoil' >Kanjikoil</option><option value='Kanjirappalli' >Kanjirappalli</option><option value='Kankavli' >Kankavli</option><option value='Kanke' >Kanke</option><option value='Kanker' >Kanker</option><option value='Kanki' >Kanki</option><option value='Kankipadu' >Kankipadu</option><option value='Kankon' >Kankon</option><option value='Kankroli' >Kankroli</option><option value='Kankuria' >Kankuria</option><option value='Kannad' >Kannad</option><option value='Kannadendal' >Kannadendal</option><option value='Kannadiparamba' >Kannadiparamba</option><option value='Kannamangalam' >Kannamangalam</option><option value='Kannampalayam' >Kannampalayam</option><option value='Kannangad' >Kannangad</option><option value='Kannankurichi' >Kannankurichi</option><option value='Kannapalaiyam' >Kannapalaiyam</option><option value='Kannapuram' >Kannapuram</option><option value='Kannauj' >Kannauj</option><option value='Kannivadi' >Kannivadi</option><option value='Kannod' >Kannod</option><option value='Kannur' >Kannur</option><option value='Kannur Cantonment' >Kannur Cantonment</option><option value='Kanodar' >Kanodar</option><option value='Kanor' >Kanor</option><option value='Kanpur' >Kanpur</option><option value='Kanpur Dehat' >Kanpur Dehat</option><option value='Kanpur Nagar' >Kanpur Nagar</option><option value='Kansepur' >Kansepur</option><option value='Kanshiram Nagar' >Kanshiram Nagar</option><option value='Kant' >Kant</option><option value='Kantabamsuguda' >Kantabamsuguda</option><option value='Kantabanji' >Kantabanji</option><option value='Kantaphod' >Kantaphod</option><option value='Kanth' >Kanth</option><option value='Kanti' >Kanti</option><option value='Kantilo' >Kantilo</option><option value='Kantlia' >Kantlia</option><option value='Kanuru' >Kanuru</option><option value='Kanyakumari' >Kanyakumari</option><option value='Kanyanagar' >Kanyanagar</option><option value='Kapadwanj' >Kapadwanj</option><option value='Kapasan' >Kapasan</option><option value='Kappiyarai' >Kappiyarai</option><option value='Kaprain' >Kaprain</option><option value='Kaptanganj' >Kaptanganj</option><option value='Kapurthala' >Kapurthala</option><option value='Karachiya' >Karachiya</option><option value='Karad' >Karad</option><option value='Karaikal' >Karaikal</option><option value='Karaikkudi' >Karaikkudi</option><option value='Karamadai' >Karamadai</option><option value='Karambakkam' >Karambakkam</option><option value='Karambakkudi' >Karambakkudi</option><option value='Karamsad' >Karamsad</option><option value='Karanje Tarf' >Karanje Tarf</option><option value='Karanpura' >Karanpura</option><option value='Karaon' >Karaon</option><option value='Karari' >Karari</option><option value='Karauli' >Karauli</option><option value='Karbi Anglong' >Karbi Anglong</option><option value='Kardhan' >Kardhan</option><option value='Kareli' >Kareli</option><option value='Karera' >Karera</option><option value='Kargil' >Kargil</option><option value='Karhal' >Karhal</option><option value='Kari' >Kari</option><option value='Kariamangalam' >Kariamangalam</option><option value='Kariapatti' >Kariapatti</option><option value='Karimganj' >Karimganj</option><option value='Karimnagar' >Karimnagar</option><option value='Karimpur' >Karimpur</option><option value='Karivali' >Karivali</option><option value='Karjan' >Karjan</option><option value='Karjat' >Karjat</option><option value='Karkala' >Karkala</option><option value='Karmala' >Karmala</option><option value='Karnal' >Karnal</option><option value='Karnaprayang' >Karnaprayang</option><option value='Karnawad' >Karnawad</option><option value='Karnawal' >Karnawal</option><option value='Karnul' >Karnul</option><option value='Karoran' >Karoran</option><option value='Karrapur' >Karrapur</option><option value='Karsiyang' >Karsiyang</option><option value='Kartarpur' >Kartarpur</option><option value='Karugampattur' >Karugampattur</option><option value='Karumandi Chellipalayam' >Karumandi Chellipalayam</option><option value='Karumathampatti' >Karumathampatti</option><option value='Karumbakkam' >Karumbakkam</option><option value='Karunagappally' >Karunagappally</option><option value='Karungal' >Karungal</option><option value='Karunguzhi' >Karunguzhi</option><option value='Karuppur' >Karuppur</option><option value='Karur' >Karur</option><option value='Karuvamyhuruthy' >Karuvamyhuruthy</option><option value='Karwar' >Karwar</option><option value='Kasara Budruk' >Kasara Budruk</option><option value='Kasaragod' >Kasaragod</option><option value='Kasargod' >Kasargod</option><option value='Kasauli' >Kasauli</option><option value='Kasganj' >Kasganj</option><option value='Kashinagara' >Kashinagara</option><option value='Kashipur' >Kashipur</option><option value='Kashirampur' >Kashirampur</option><option value='Kasimbazar' >Kasimbazar</option><option value='Kasipalaiyam' >Kasipalaiyam</option><option value='Kasipalayam G' >Kasipalayam G</option><option value='Kasrawad' >Kasrawad</option><option value='Katai' >Katai</option><option value='Kataiya' >Kataiya</option><option value='Kataka' >Kataka</option><option value='Katangi' >Katangi</option><option value='Katariya' >Katariya</option><option value='Katghar Lalganj' >Katghar Lalganj</option><option value='Katghora' >Katghora</option><option value='Kathera' >Kathera</option><option value='Katheru' >Katheru</option><option value='Kathial' >Kathial</option><option value='Kathirvedu' >Kathirvedu</option><option value='Kathor' >Kathor</option><option value='Kathua' >Kathua</option><option value='Kathujuganapalli' >Kathujuganapalli</option><option value='Katihar' >Katihar</option><option value='Katkar' >Katkar</option><option value='Katni' >Katni</option><option value='Katol' >Katol</option><option value='Katpadi' >Katpadi</option><option value='Katpar' >Katpar</option><option value='Katra Medniganj' >Katra Medniganj</option><option value='Katras' >Katras</option><option value='Kattappana' >Kattappana</option><option value='Kattivakkam' >Kattivakkam</option><option value='Kattumannarkoil' >Kattumannarkoil</option><option value='Kattupakkam' >Kattupakkam</option><option value='Kattuputhur' >Kattuputhur</option><option value='Katwa' >Katwa</option><option value='Kaugachhi' >Kaugachhi</option><option value='Kauriaganj' >Kauriaganj</option><option value='Kausani' >Kausani</option><option value='Kaushambi' >Kaushambi</option><option value='Kavali' >Kavali</option><option value='Kavant' >Kavant</option><option value='Kavaratti' >Kavaratti</option><option value='Kaveripakkam' >Kaveripakkam</option><option value='Kaveripattinam' >Kaveripattinam</option><option value='Kavisuryanagar' >Kavisuryanagar</option><option value='Kavundampalaiyam' >Kavundampalaiyam</option><option value='Kavundampalayam' >Kavundampalayam</option><option value='Kawardha' >Kawardha</option><option value='Kawnpui' >Kawnpui</option><option value='Kayalpattinam' >Kayalpattinam</option><option value='Kayamkulam' >Kayamkulam</option><option value='Kayattar' >Kayattar</option><option value='Kazipet' >Kazipet</option><option value='Kedamangalam' >Kedamangalam</option><option value='Kedarnath' >Kedarnath</option><option value='Kedla' >Kedla</option><option value='Kegaon' >Kegaon</option><option value='Kekri' >Kekri</option><option value='Kelakhera' >Kelakhera</option><option value='Kelamangalam' >Kelamangalam</option><option value='Kelambakkam' >Kelambakkam</option><option value='Kelhauri' >Kelhauri</option><option value='Kembainaickenpalayam' >Kembainaickenpalayam</option><option value='Kemminja' >Kemminja</option><option value='Kemri' >Kemri</option><option value='Kenda' >Kenda</option><option value='Kendra Khottamdi' >Kendra Khottamdi</option><option value='Kendrapara' >Kendrapara</option><option value='Kendua' >Kendua</option><option value='Kenduadih' >Kenduadih</option><option value='Kendujhar' >Kendujhar</option><option value='Kengeri' >Kengeri</option><option value='Keonjhar' >Keonjhar</option><option value='Kerakat' >Kerakat</option><option value='Kerur' >Kerur</option><option value='Kesabpur' >Kesabpur</option><option value='Keshod' >Keshod</option><option value='Keshorai Patan' >Keshorai Patan</option><option value='Kesinga' >Kesinga</option><option value='Kesrisinghpur' >Kesrisinghpur</option><option value='Kethi' >Kethi</option><option value='Kevadiya' >Kevadiya</option><option value='Khachrod' >Khachrod</option><option value='Khadda' >Khadda</option><option value='Khadkale' >Khadkale</option><option value='Khadki' >Khadki</option><option value='Khaga' >Khaga</option><option value='Khagaria' >Khagaria</option><option value='Khagaul' >Khagaul</option><option value='Khagrabari' >Khagrabari</option><option value='Khailar' >Khailar</option><option value='Khair' >Khair</option><option value='Khairabad' >Khairabad</option><option value='Khairthal' >Khairthal</option><option value='Khajuraho' >Khajuraho</option><option value='Khalia' >Khalia</option><option value='Khaliapali' >Khaliapali</option><option value='Khalikote' >Khalikote</option><option value='Khalilabad' >Khalilabad</option><option value='Khalor' >Khalor</option><option value='Khamanon' >Khamanon</option><option value='Khambhaliya' >Khambhaliya</option><option value='Khambhat' >Khambhat</option><option value='Khamgaon' >Khamgaon</option><option value='Khamhria' >Khamhria</option><option value='Khammam' >Khammam</option><option value='Khan Sahib' >Khan Sahib</option><option value='Khanapur' >Khanapur</option><option value='Khanapuram Haveli' >Khanapuram Haveli</option><option value='Khanauri' >Khanauri</option><option value='Khand' >Khand</option><option value='Khandaparha' >Khandaparha</option><option value='Khandela' >Khandela</option><option value='Khandra' >Khandra</option><option value='Khandwa' >Khandwa</option><option value='Khaniyadhana' >Khaniyadhana</option><option value='Khanna' >Khanna</option><option value='Khantora' >Khantora</option><option value='Khapa' >Khapa</option><option value='Kharadi' >Kharadi</option><option value='Kharaghoda' >Kharaghoda</option><option value='Kharagpur Railway Settlement' >Kharagpur Railway Settlement</option><option value='Kharakvasla' >Kharakvasla</option><option value='Khardaha' >Khardaha</option><option value='Kharela' >Kharela</option><option value='Khargapur' >Khargapur</option><option value='Khargone' >Khargone</option><option value='Khargupur' >Khargupur</option><option value='Kharhial' >Kharhial</option><option value='Kharhial Road' >Kharhial Road</option><option value='Khari Mala Khagrabari' >Khari Mala Khagrabari</option><option value='Kharijapikon' >Kharijapikon</option><option value='Khariya' >Khariya</option><option value='Kharkhari' >Kharkhari</option><option value='Kharod' >Kharod</option><option value='Kharsarai' >Kharsarai</option><option value='Kharsawan' >Kharsawan</option><option value='Kharsia' >Kharsia</option><option value='Kharupetia' >Kharupetia</option><option value='Khatauli' >Khatauli</option><option value='Khatauli Rural' >Khatauli Rural</option><option value='Khategaon' >Khategaon</option><option value='Khatiguda' >Khatiguda</option><option value='Khatima' >Khatima</option><option value='Khatra' >Khatra</option><option value='Khawhai' >Khawhai</option><option value='Khawzawl' >Khawzawl</option><option value='Khed' >Khed</option><option value='Khed Brahma' >Khed Brahma</option><option value='Kheda' >Kheda</option><option value='Khekra' >Khekra</option><option value='Khelari' >Khelari</option><option value='Khem Karan' >Khem Karan</option><option value='Kheralu' >Kheralu</option><option value='Kherdi' >Kherdi</option><option value='Kheri' >Kheri</option><option value='Kheri Sampla' >Kheri Sampla</option><option value='Kherli' >Kherli</option><option value='Kherliganj' >Kherliganj</option><option value='Kherwara Chhaoni' >Kherwara Chhaoni</option><option value='Kheta Sarai' >Kheta Sarai</option><option value='Khetia' >Khetia</option><option value='Khetri' >Khetri</option><option value='Khilchipur' >Khilchipur</option><option value='Khirkiya' >Khirkiya</option><option value='Khodarampur' >Khodarampur</option><option value='Khonga Pani' >Khonga Pani</option><option value='Khongman' >Khongman</option><option value='Khoni' >Khoni</option><option value='Khonsa' >Khonsa</option><option value='Khopoli' >Khopoli</option><option value='Khordha' >Khordha</option><option value='Khour' >Khour</option><option value='Khowai' >Khowai</option><option value='Khrew' >Khrew</option><option value='Khudaganj' >Khudaganj</option><option value='Khujner' >Khujner</option><option value='Khuldabad' >Khuldabad</option><option value='Khunti' >Khunti</option><option value='Khurai' >Khurai</option><option value='Khurda' >Khurda</option><option value='Khurja' >Khurja</option><option value='Khusrupur' >Khusrupur</option><option value='Khutar' >Khutar</option><option value='Kichha' >Kichha</option><option value='Kilakarai' >Kilakarai</option><option value='Kilampadi' >Kilampadi</option><option value='Kilkulam' >Kilkulam</option><option value='Kilkunda' >Kilkunda</option><option value='Killiyur' >Killiyur</option><option value='Killlai' >Killlai</option><option value='Kilpennathur' >Kilpennathur</option><option value='Kilvelur' >Kilvelur</option><option value='Kinathukadavu' >Kinathukadavu</option><option value='Kinnaur' >Kinnaur</option><option value='Kinwat' >Kinwat</option><option value='Kiramangalam' >Kiramangalam</option><option value='Kirandu' >Kirandu</option><option value='Kirandul' >Kirandul</option><option value='Kiranipura' >Kiranipura</option><option value='Kiranur' >Kiranur</option><option value='Kiraoli' >Kiraoli</option><option value='Kiratpur' >Kiratpur</option><option value='Kiri Buru' >Kiri Buru</option><option value='Kiriburu' >Kiriburu</option><option value='Kiripatti' >Kiripatti</option><option value='Kirtinagar' >Kirtinagar</option><option value='Kishanganj' >Kishanganj</option><option value='Kishangarh' >Kishangarh</option><option value='Kishangarh Ranwal' >Kishangarh Ranwal</option><option value='Kishanpur' >Kishanpur</option><option value='Kishni' >Kishni</option><option value='Kishtwar' >Kishtwar</option><option value='Kithaur' >Kithaur</option><option value='Kizhapavur' >Kizhapavur</option><option value='Kmarasamipatti' >Kmarasamipatti</option><option value='Koath' >Koath</option><option value='Kochadai' >Kochadai</option><option value='Kochi' >Kochi</option><option value='Kochinda' >Kochinda</option><option value='Kochpara' >Kochpara</option><option value='Kodada' >Kodada</option><option value='Kodagu' >Kodagu</option><option value='Kodaikanal' >Kodaikanal</option><option value='Kodala' >Kodala</option><option value='Kodalia' >Kodalia</option><option value='Kodambakkam' >Kodambakkam</option><option value='Kodamthuruthu' >Kodamthuruthu</option><option value='Kodar' >Kodar</option><option value='Kodarma' >Kodarma</option><option value='Kodavasal' >Kodavasal</option><option value='Koderma' >Koderma</option><option value='Kodigenahalli' >Kodigenahalli</option><option value='Kodinar' >Kodinar</option><option value='Kodiyal' >Kodiyal</option><option value='Kodlipet' >Kodlipet</option><option value='Kodoli' >Kodoli</option><option value='Kodumudi' >Kodumudi</option><option value='Kodungallur' >Kodungallur</option><option value='Koduvally' >Koduvally</option><option value='Koduvayur' >Koduvayur</option><option value='Kohima' >Kohima</option><option value='Kohka' >Kohka</option><option value='Koilwar' >Koilwar</option><option value='Koiripur' >Koiripur</option><option value='Kokkothamangalam' >Kokkothamangalam</option><option value='Kokrajhar' >Kokrajhar</option><option value='Kolachal' >Kolachal</option><option value='Kolaghat' >Kolaghat</option><option value='Kolaghat Thermal Power Project' >Kolaghat Thermal Power Project</option><option value='Kolappalur' >Kolappalur</option><option value='Kolar' >Kolar</option><option value='Kolaras' >Kolaras</option><option value='Kolasib' >Kolasib</option><option value='Kolathupalayam' >Kolathupalayam</option><option value='Kolathur' >Kolathur</option><option value='Kolazhy' >Kolazhy</option><option value='Kolhapur' >Kolhapur</option><option value='Kolkata' >Kolkata</option><option value='Kollam' >Kollam</option><option value='Kollankodu' >Kollankodu</option><option value='Kollankoil' >Kollankoil</option><option value='Kollapur' >Kollapur</option><option value='Kollegal' >Kollegal</option><option value='Kolvi Rajendrapura' >Kolvi Rajendrapura</option><option value='Komalapuram' >Komalapuram</option><option value='Komaralingam' >Komaralingam</option><option value='Komarapalayam' >Komarapalayam</option><option value='Kombai' >Kombai</option><option value='Kon' >Kon</option><option value='Konakkarai' >Konakkarai</option><option value='Konanakunte' >Konanakunte</option><option value='Konanur' >Konanur</option><option value='Konardihi' >Konardihi</option><option value='Konark' >Konark</option><option value='Konavattam' >Konavattam</option><option value='Konch' >Konch</option><option value='Kondagaon' >Kondagaon</option><option value='Kondalampatti' >Kondalampatti</option><option value='Kondapalem' >Kondapalem</option><option value='Kondapalle' >Kondapalle</option><option value='Kondukur' >Kondukur</option><option value='Kondumal' >Kondumal</option><option value='Konganapuram' >Konganapuram</option><option value='Konnogar' >Konnogar</option><option value='Konnur' >Konnur</option><option value='Koothattukulam' >Koothattukulam</option><option value='Kopaganj' >Kopaganj</option><option value='Kopargaon' >Kopargaon</option><option value='Kopharad' >Kopharad</option><option value='Koppa' >Koppa</option><option value='Koppal' >Koppal</option><option value='Kora Jahanabad' >Kora Jahanabad</option><option value='Koradacheri' >Koradacheri</option><option value='Koradi' >Koradi</option><option value='Korampallam' >Korampallam</option><option value='Koraput' >Koraput</option><option value='Koratagere' >Koratagere</option><option value='Koratla' >Koratla</option><option value='Koratty' >Koratty</option><option value='Korba' >Korba</option><option value='Korea' >Korea</option><option value='Koregaon' >Koregaon</option><option value='Koria Block' >Koria Block</option><option value='Koriya' >Koriya</option><option value='Korochi' >Korochi</option><option value='Korwa' >Korwa</option><option value='Kosamba' >Kosamba</option><option value='Kosgi' >Kosgi</option><option value='Kosi Kalan' >Kosi Kalan</option><option value='Kot Fatta' >Kot Fatta</option><option value='Kot Isa Khan' >Kot Isa Khan</option><option value='Kot Kapura' >Kot Kapura</option><option value='Kot Khai' >Kot Khai</option><option value='Kot Putli' >Kot Putli</option><option value='Kota' >Kota</option><option value='Kotagiri' >Kotagiri</option><option value='Kotaparh' >Kotaparh</option><option value='Kotar' >Kotar</option><option value='Kotdwara' >Kotdwara</option><option value='Kotekara' >Kotekara</option><option value='Kothamangalam' >Kothamangalam</option><option value='Kothavalasa' >Kothavalasa</option><option value='Kothi' >Kothi</option><option value='Kothinallur' >Kothinallur</option><option value='Kothnur' >Kothnur</option><option value='Kotkapura' >Kotkapura</option><option value='Kotma' >Kotma</option><option value='Kotra' >Kotra</option><option value='Kottagudem' >Kottagudem</option><option value='Kottaiyur' >Kottaiyur</option><option value='Kottakuppam' >Kottakuppam</option><option value='Kottapalli' >Kottapalli</option><option value='Kottarakkara' >Kottarakkara</option><option value='Kottaram' >Kottaram</option><option value='Kottayam' >Kottayam</option><option value='Kottayam Malabar' >Kottayam Malabar</option><option value='Kottivakkam' >Kottivakkam</option><option value='Kottur' >Kottur</option><option value='Kotturu' >Kotturu</option><option value='Kottuvally' >Kottuvally</option><option value='Kotwa' >Kotwa</option><option value='Kovilpatti' >Kovilpatti</option><option value='Kovur' >Kovur</option><option value='Kovurpalle' >Kovurpalle</option><option value='Kovvur' >Kovvur</option><option value='Koyampattur' >Koyampattur</option><option value='Koyilandi' >Koyilandi</option><option value='Kozhikode' >Kozhikode</option><option value='Krishna' >Krishna</option><option value='Krishnagiri' >Krishnagiri</option><option value='Krishnanagar' >Krishnanagar</option><option value='Krishnapur' >Krishnapur</option><option value='Krishnapura' >Krishnapura</option><option value='Krishnarajanagar' >Krishnarajanagar</option><option value='Krishnarajapura' >Krishnarajapura</option><option value='Krishnarajasagara' >Krishnarajasagara</option><option value='Krishnarajpet' >Krishnarajpet</option><option value='Krishnarayapuram' >Krishnarayapuram</option><option value='Krishnasamudram' >Krishnasamudram</option><option value='Kshidirpur' >Kshidirpur</option><option value='Kshirpai' >Kshirpai</option><option value='Kuchaman' >Kuchaman</option><option value='Kuchanur' >Kuchanur</option><option value='Kuchera' >Kuchera</option><option value='Kud' >Kud</option><option value='Kudal' >Kudal</option><option value='Kudappanakunnu' >Kudappanakunnu</option><option value='Kudchi' >Kudchi</option><option value='Kudligi' >Kudligi</option><option value='Kudlu' >Kudlu</option><option value='Kudremukh' >Kudremukh</option><option value='Kuhalur' >Kuhalur</option><option value='Kuju' >Kuju</option><option value='Kukernag' >Kukernag</option><option value='Kukshi' >Kukshi</option><option value='Kulasekarappattinam' >Kulasekarappattinam</option><option value='Kulasekarapuram' >Kulasekarapuram</option><option value='Kulgam' >Kulgam</option><option value='Kulihanda' >Kulihanda</option><option value='Kulithalai' >Kulithalai</option><option value='Kullu' >Kullu</option><option value='Kulpahar' >Kulpahar</option><option value='Kulti' >Kulti</option><option value='Kulu' >Kulu</option><option value='Kumar Kaibarta Gaon' >Kumar Kaibarta Gaon</option><option value='Kumarakom' >Kumarakom</option><option value='Kumarapalaiyam' >Kumarapalaiyam</option><option value='Kumarapalayam' >Kumarapalayam</option><option value='Kumarapuram' >Kumarapuram</option><option value='Kumarghat' >Kumarghat</option><option value='Kumbakonam' >Kumbakonam</option><option value='Kumbhalgarh' >Kumbhalgarh</option><option value='Kumbhkot' >Kumbhkot</option><option value='Kumbhraj' >Kumbhraj</option><option value='Kumbi' >Kumbi</option><option value='Kumhari' >Kumhari</option><option value='Kumher' >Kumher</option><option value='Kumily' >Kumily</option><option value='Kumsi' >Kumsi</option><option value='Kumta' >Kumta</option><option value='Kumud Katta' >Kumud Katta</option><option value='Kunda' >Kunda</option><option value='Kundalwadi' >Kundalwadi</option><option value='Kundapura' >Kundapura</option><option value='Kundarki' >Kundarki</option><option value='Kundgol' >Kundgol</option><option value='Kundla' >Kundla</option><option value='Kundli' >Kundli</option><option value='Kundrathur' >Kundrathur</option><option value='Kunigal' >Kunigal</option><option value='Kuniyamuthur' >Kuniyamuthur</option><option value='Kunjaban' >Kunjaban</option><option value='Kunnamangalam' >Kunnamangalam</option><option value='Kunnamkulam' >Kunnamkulam</option><option value='Kunnathur' >Kunnathur</option><option value='Kunur' >Kunur</option><option value='Kunustara' >Kunustara</option><option value='Kunwargaon' >Kunwargaon</option><option value='Kunzer' >Kunzer</option><option value='Kuperskem' >Kuperskem</option><option value='Kuppam' >Kuppam</option><option value='Kupwara' >Kupwara</option><option value='Kuraikundu' >Kuraikundu</option><option value='Kurali' >Kurali</option><option value='Kurandvad' >Kurandvad</option><option value='Kurara' >Kurara</option><option value='Kurasia' >Kurasia</option><option value='Kurawali' >Kurawali</option><option value='Kurduvadi' >Kurduvadi</option><option value='Kurgunta' >Kurgunta</option><option value='Kurichi' >Kurichi</option><option value='Kurikkad' >Kurikkad</option><option value='Kurinjippadi' >Kurinjippadi</option><option value='Kurkkanchery' >Kurkkanchery</option><option value='Kurmannapalem' >Kurmannapalem</option><option value='Kurnool' >Kurnool</option><option value='Kurpania' >Kurpania</option><option value='Kursath' >Kursath</option><option value='Kurthi Jafarpur' >Kurthi Jafarpur</option><option value='Kurud' >Kurud</option><option value='Kurudampalaiyam' >Kurudampalaiyam</option><option value='Kurukshetra' >Kurukshetra</option><option value='Kurumbalur' >Kurumbalur</option><option value='Kurumbapet' >Kurumbapet</option><option value='Kurwai' >Kurwai</option><option value='Kusgaon Budruk' >Kusgaon Budruk</option><option value='Kushalgarh' >Kushalgarh</option><option value='Kushalnagar' >Kushalnagar</option><option value='Kushinagar' >Kushinagar</option><option value='Kushtagi' >Kushtagi</option><option value='Kusmara' >Kusmara</option><option value='Kustai' >Kustai</option><option value='Kutch' >Kutch</option><option value='Kuthalam' >Kuthalam</option><option value='Kuthappar' >Kuthappar</option><option value='Kuthuparamba' >Kuthuparamba</option><option value='Kutiyana' >Kutiyana</option><option value='Kuttakulam' >Kuttakulam</option><option value='Kuttalam' >Kuttalam</option><option value='Kuttanallur' >Kuttanallur</option><option value='Kuttikkattur' >Kuttikkattur</option><option value='Kuttur' >Kuttur</option><option value='Kuzhithurai' >Kuzhithurai</option><option value='Kwakta' >Kwakta</option><option value='Kyathampalle' >Kyathampalle</option><option value='Kyathanahalli' >Kyathanahalli</option><option value='Labbaikudikadu' >Labbaikudikadu</option><option value='Lachhmangarh' >Lachhmangarh</option><option value='Ladnun' >Ladnun</option><option value='Ladrawan' >Ladrawan</option><option value='Ladwa' >Ladwa</option><option value='Lahar' >Lahar</option><option value='Laharpur' >Laharpur</option><option value='Lahaul and Spiti' >Lahaul and Spiti</option><option value='Lakarka' >Lakarka</option><option value='Lakhenpur' >Lakhenpur</option><option value='Lakheri' >Lakheri</option><option value='Lakhimpur' >Lakhimpur</option><option value='Lakhimpur Kheri' >Lakhimpur Kheri</option><option value='Lakhipur' >Lakhipur</option><option value='Lakhisarai' >Lakhisarai</option><option value='Lakhna' >Lakhna</option><option value='Lakhnadon' >Lakhnadon</option><option value='Lakhtar' >Lakhtar</option><option value='Lakkampatti' >Lakkampatti</option><option value='Laksar' >Laksar</option><option value='Lakshettipet' >Lakshettipet</option><option value='Lakshmeshwar' >Lakshmeshwar</option><option value='Lala' >Lala</option><option value='Lalbahadur Nagar' >Lalbahadur Nagar</option><option value='Lalgudi' >Lalgudi</option><option value='Lalitpur' >Lalitpur</option><option value='Lalkuan' >Lalkuan</option><option value='Lalpet' >Lalpet</option><option value='Lalpur' >Lalpur</option><option value='Lalru' >Lalru</option><option value='Lalsot' >Lalsot</option><option value='Lamai' >Lamai</option><option value='Lambha' >Lambha</option><option value='Lamjaotongba' >Lamjaotongba</option><option value='Lamshang' >Lamshang</option><option value='Landaura' >Landaura</option><option value='Landhaura Cantonment' >Landhaura Cantonment</option><option value='Lanja' >Lanja</option><option value='Lanjigarh' >Lanjigarh</option><option value='Lanka' >Lanka</option><option value='Lapanga' >Lapanga</option><option value='Lar' >Lar</option><option value='Lasalgaon' >Lasalgaon</option><option value='Latehar' >Latehar</option><option value='Lateri' >Lateri</option><option value='Lathi' >Lathi</option><option value='Lattikata' >Lattikata</option><option value='Latur' >Latur</option><option value='Laundi' >Laundi</option><option value='Lauthaha' >Lauthaha</option><option value='Lawar' >Lawar</option><option value='Lawngtlai' >Lawngtlai</option><option value='Ledwa Mahuwa' >Ledwa Mahuwa</option><option value='Leh' >Leh</option><option value='Lehra Gaga' >Lehra Gaga</option><option value='Lengpui' >Lengpui</option><option value='Lensdaun' >Lensdaun</option><option value='Lidhora Khas' >Lidhora Khas</option><option value='Lido Tikok' >Lido Tikok</option><option value='Lido Town' >Lido Town</option><option value='Lilong' >Lilong</option><option value='Limbdi' >Limbdi</option><option value='Limla' >Limla</option><option value='Lingiyadih' >Lingiyadih</option><option value='Lingsugur' >Lingsugur</option><option value='Llayangudi' >Llayangudi</option><option value='Lodhian Khas' >Lodhian Khas</option><option value='Lodhikheda' >Lodhikheda</option><option value='Logahat' >Logahat</option><option value='Loha' >Loha</option><option value='Loharda' >Loharda</option><option value='Lohardaga' >Lohardaga</option><option value='Loharu' >Loharu</option><option value='Lohegaon' >Lohegaon</option><option value='Lohit' >Lohit</option><option value='Lohta' >Lohta</option><option value='Loiya' >Loiya</option><option value='Lonar' >Lonar</option><option value='Lonavala' >Lonavala</option><option value='Londa' >Londa</option><option value='Longowal' >Longowal</option><option value='Loni' >Loni</option><option value='Lormi' >Lormi</option><option value='Losal' >Losal</option><option value='Loutulim' >Loutulim</option><option value='Lower Subansiri' >Lower Subansiri</option><option value='Loyabad' >Loyabad</option><option value='Lucknow' >Lucknow</option><option value='Ludhiana' >Ludhiana</option><option value='Lumding' >Lumding</option><option value='Lumding Railway Colony' >Lumding Railway Colony</option><option value='Lunavada' >Lunavada</option><option value='Lunglei' >Lunglei</option><option value='Machalpur' >Machalpur</option><option value='Machavaram' >Machavaram</option><option value='Macherla' >Macherla</option><option value='Machhiwara' >Machhiwara</option><option value='Machhlishahr' >Machhlishahr</option><option value='Machilipatnam' >Machilipatnam</option><option value='Madambakkam' >Madambakkam</option><option value='Madanapalle' >Madanapalle</option><option value='Madanganj' >Madanganj</option><option value='Madanpur' >Madanpur</option><option value='Madanrting' >Madanrting</option><option value='Madanur' >Madanur</option><option value='Madaram' >Madaram</option><option value='Madathukulam' >Madathukulam</option><option value='Maddur' >Maddur</option><option value='Madgaon' >Madgaon</option><option value='Madhapar' >Madhapar</option><option value='Madhavaram' >Madhavaram</option><option value='Madhavnagar' >Madhavnagar</option><option value='Madhepura' >Madhepura</option><option value='Madhira' >Madhira</option><option value='Madhoganj' >Madhoganj</option><option value='Madhubani' >Madhubani</option><option value='Madhugiri' >Madhugiri</option><option value='Madhupur' >Madhupur</option><option value='Madhuravada' >Madhuravada</option><option value='Madhusudanpur' >Madhusudanpur</option><option value='Madhyamgram' >Madhyamgram</option><option value='Madikeri' >Madikeri</option><option value='Madikonda' >Madikonda</option><option value='Madippakkam' >Madippakkam</option><option value='Madugule' >Madugule</option><option value='Madukkarai' >Madukkarai</option><option value='Madukkur' >Madukkur</option><option value='Madurai' >Madurai</option><option value='Maduranthakam' >Maduranthakam</option><option value='Maduravoyal' >Maduravoyal</option><option value='Maflipur' >Maflipur</option><option value='Magadi' >Magadi</option><option value='Magam' >Magam</option><option value='Maghar' >Maghar</option><option value='Magod Falls' >Magod Falls</option><option value='Mahabaleshwar' >Mahabaleshwar</option><option value='Mahabalipuram' >Mahabalipuram</option><option value='Mahaban' >Mahaban</option><option value='Mahabubabad' >Mahabubabad</option><option value='Mahabubnagar' >Mahabubnagar</option><option value='Mahad' >Mahad</option><option value='Mahadeswara Hills' >Mahadeswara Hills</option><option value='Mahadevapura' >Mahadevapura</option><option value='Mahadula' >Mahadula</option><option value='Mahalingpur' >Mahalingpur</option><option value='Maham' >Maham</option><option value='Mahamaya Nagar' >Mahamaya Nagar</option><option value='Maharajganj' >Maharajganj</option><option value='Maharajpur' >Maharajpur</option><option value='Mahasamund' >Mahasamund</option><option value='Mahbubabad' >Mahbubabad</option><option value='Mahbubnagar' >Mahbubnagar</option><option value='Mahe' >Mahe</option><option value='Mahemdavad' >Mahemdavad</option><option value='Mahendragarh' >Mahendragarh</option><option value='Mahesh Mundi' >Mahesh Mundi</option><option value='Maheshtala' >Maheshtala</option><option value='Maheshwar' >Maheshwar</option><option value='Mahiari' >Mahiari</option><option value='Mahidpur' >Mahidpur</option><option value='Mahikpur' >Mahikpur</option><option value='Mahilpur' >Mahilpur</option><option value='Mahira' >Mahira</option><option value='Mahishadal' >Mahishadal</option><option value='Mahmudabad' >Mahmudabad</option><option value='Mahnar Bazar' >Mahnar Bazar</option><option value='Mahoba' >Mahoba</option><option value='Maholi' >Maholi</option><option value='Mahona' >Mahona</option><option value='Mahroni' >Mahroni</option><option value='Mahu Kalan' >Mahu Kalan</option><option value='Mahua Dabra Haripura' >Mahua Dabra Haripura</option><option value='Mahua Kheraganj' >Mahua Kheraganj</option><option value='Mahudha' >Mahudha</option><option value='Mahur' >Mahur</option><option value='Mahuva' >Mahuva</option><option value='Mahuvar' >Mahuvar</option><option value='Mahwa' >Mahwa</option><option value='Maibong' >Maibong</option><option value='Maihar' >Maihar</option><option value='Mailani' >Mailani</option><option value='Mainaguri' >Mainaguri</option><option value='Maindargi' >Maindargi</option><option value='Mainpuri' >Mainpuri</option><option value='Mairang' >Mairang</option><option value='Mairwa' >Mairwa</option><option value='Maisuru' >Maisuru</option><option value='Maisuru Cantonment' >Maisuru Cantonment</option><option value='Maithon' >Maithon</option><option value='Majalgaon' >Majalgaon</option><option value='Majgaon' >Majgaon</option><option value='Majhara Pipar Ehatmali' >Majhara Pipar Ehatmali</option><option value='Majhauli Raj' >Majhauli Raj</option><option value='Majholi' >Majholi</option><option value='Majitha' >Majitha</option><option value='Makarba' >Makarba</option><option value='Makardaha' >Makardaha</option><option value='Makarpura' >Makarpura</option><option value='Makassar' >Makassar</option><option value='Makhdumpur' >Makhdumpur</option><option value='Makhu' >Makhu</option><option value='Makkinanpatti' >Makkinanpatti</option><option value='Makrana' >Makrana</option><option value='Makronia' >Makronia</option><option value='Maksi' >Maksi</option><option value='Maktampur' >Maktampur</option><option value='Makum' >Makum</option><option value='Makundapur' >Makundapur</option><option value='Mal' >Mal</option><option value='Malaj Khand' >Malaj Khand</option><option value='Malanpur' >Malanpur</option><option value='Malappuram' >Malappuram</option><option value='Malaut' >Malaut</option><option value='Malavalli' >Malavalli</option><option value='Malda' >Malda</option><option value='Malegaon' >Malegaon</option><option value='Malerkotla' >Malerkotla</option><option value='Malgaon' >Malgaon</option><option value='Malhargarh' >Malhargarh</option><option value='Malia' >Malia</option><option value='Malihabad' >Malihabad</option><option value='Malkajgiri' >Malkajgiri</option><option value='Malkangiri' >Malkangiri</option><option value='Malkapur' >Malkapur</option><option value='Malkera' >Malkera</option><option value='Mallamuppampatti' >Mallamuppampatti</option><option value='Mallankinaru' >Mallankinaru</option><option value='Mallanwam' >Mallanwam</option><option value='Mallappally' >Mallappally</option><option value='Mallapuram' >Mallapuram</option><option value='Mallar' >Mallar</option><option value='Mallasamudram' >Mallasamudram</option><option value='Mallur' >Mallur</option><option value='Maloud' >Maloud</option><option value='Malpe' >Malpe</option><option value='Malpur' >Malpur</option><option value='Malpura' >Malpura</option><option value='Malur' >Malur</option><option value='Malwan' >Malwan</option><option value='Mamallapuram' >Mamallapuram</option><option value='Mamilapalle' >Mamilapalle</option><option value='Mamit' >Mamit</option><option value='Mamsapuram' >Mamsapuram</option><option value='Manachanallur' >Manachanallur</option><option value='Manadur' >Manadur</option><option value='Manalmedu' >Manalmedu</option><option value='Manalurpet' >Manalurpet</option><option value='Manamadurai' >Manamadurai</option><option value='Manapakkam' >Manapakkam</option><option value='Manapparai' >Manapparai</option><option value='Manasa' >Manasa</option><option value='Manavadar' >Manavadar</option><option value='Manavalakurichi' >Manavalakurichi</option><option value='Manawar' >Manawar</option><option value='Manchar' >Manchar</option><option value='Manchenahalli' >Manchenahalli</option><option value='Mancheral' >Mancheral</option><option value='Mancherial' >Mancherial</option><option value='Mandaikadu' >Mandaikadu</option><option value='Mandalgarh' >Mandalgarh</option><option value='Mandamarri' >Mandamarri</option><option value='Mandapam' >Mandapam</option><option value='Mandapeta' >Mandapeta</option><option value='Mandarbani' >Mandarbani</option><option value='Mandasa' >Mandasa</option><option value='Mandav' >Mandav</option><option value='Mandi' >Mandi</option><option value='Mandi Gobindgarh' >Mandi Gobindgarh</option><option value='Mandideep' >Mandideep</option><option value='Mandla' >Mandla</option><option value='Mandleshwar' >Mandleshwar</option><option value='Mandsaur' >Mandsaur</option><option value='Mandvi' >Mandvi</option><option value='Mandwa' >Mandwa</option><option value='Mandya' >Mandya</option><option value='Manegaon' >Manegaon</option><option value='Maner' >Maner</option><option value='Mangadu' >Mangadu</option><option value='Mangalagiri' >Mangalagiri</option><option value='Mangalam' >Mangalam</option><option value='Mangalampet' >Mangalampet</option><option value='Mangaldai' >Mangaldai</option><option value='Mangalore' >Mangalore</option><option value='Mangaluru' >Mangaluru</option><option value='Mangalvedhe' >Mangalvedhe</option><option value='Mangan' >Mangan</option><option value='Mangawan' >Mangawan</option><option value='Manglaur' >Manglaur</option><option value='Manglaya Sadak' >Manglaya Sadak</option><option value='Mango' >Mango</option><option value='Mangrul Pir' >Mangrul Pir</option><option value='Manihari' >Manihari</option><option value='Manikpur' >Manikpur</option><option value='Manimutharu' >Manimutharu</option><option value='Manipal' >Manipal</option><option value='Maniyar' >Maniyar</option><option value='Manjeri' >Manjeri</option><option value='Manjeshwar' >Manjeshwar</option><option value='Manjhanpur' >Manjhanpur</option><option value='Mankachar' >Mankachar</option><option value='Mankapur' >Mankapur</option><option value='Manmad' >Manmad</option><option value='Mannancherry' >Mannancherry</option><option value='Mannar' >Mannar</option><option value='Mannarakkat' >Mannarakkat</option><option value='Mannargudi' >Mannargudi</option><option value='Manohar Thana' >Manohar Thana</option><option value='Manor' >Manor</option><option value='Manpur' >Manpur</option><option value='Mansa' >Mansa</option><option value='Mansar' >Mansar</option><option value='Mansinhapur' >Mansinhapur</option><option value='Mant Khas' >Mant Khas</option><option value='Manthani' >Manthani</option><option value='Manuguru' >Manuguru</option><option value='Manvi' >Manvi</option><option value='Manwath' >Manwath</option><option value='Mappilaiurani' >Mappilaiurani</option><option value='Mapuca' >Mapuca</option><option value='Mapusa' >Mapusa</option><option value='Maradu' >Maradu</option><option value='Maraimalai Nagar' >Maraimalai Nagar</option><option value='Marakkanam' >Marakkanam</option><option value='Maramangalathupatti' >Maramangalathupatti</option><option value='Marandahalli' >Marandahalli</option><option value='Marathakkara' >Marathakkara</option><option value='Marehra' >Marehra</option><option value='Margao' >Margao</option><option value='Margaon' >Margaon</option><option value='Margherita' >Margherita</option><option value='Marhaura' >Marhaura</option><option value='Mariahu' >Mariahu</option><option value='Mariani' >Mariani</option><option value='Marigaon' >Marigaon</option><option value='Markapur' >Markapur</option><option value='Markayankottai' >Markayankottai</option><option value='Marma' >Marma</option><option value='Marturu' >Marturu</option><option value='Maruadih' >Maruadih</option><option value='Marudur' >Marudur</option><option value='Marungur' >Marungur</option><option value='Marutharod' >Marutharod</option><option value='Marwar' >Marwar</option><option value='Masaurhi' >Masaurhi</option><option value='Masila' >Masila</option><option value='Masinigudi' >Masinigudi</option><option value='Maski' >Maski</option><option value='Maslandapur' >Maslandapur</option><option value='Mastikatte Colony' >Mastikatte Colony</option><option value='Masuri' >Masuri</option><option value='Maswasi' >Maswasi</option><option value='Mataundh' >Mataundh</option><option value='Mathabhanga' >Mathabhanga</option><option value='Matheran' >Matheran</option><option value='Mathigiri' >Mathigiri</option><option value='Mathu' >Mathu</option><option value='Mathura' >Mathura</option><option value='Mathura Cantonment' >Mathura Cantonment</option><option value='Mattan' >Mattan</option><option value='Mattannur' >Mattannur</option><option value='Mattur' >Mattur</option><option value='Mau' >Mau</option><option value='Mau Aima' >Mau Aima</option><option value='Maudaha' >Maudaha</option><option value='Mauganj' >Mauganj</option><option value='Maur' >Maur</option><option value='Mauranipur' >Mauranipur</option><option value='Maurawan' >Maurawan</option><option value='Mavelikara' >Mavelikara</option><option value='Mavilayi' >Mavilayi</option><option value='Mavur' >Mavur</option><option value='Mawana' >Mawana</option><option value='Mawlai' >Mawlai</option><option value='Mayakonda' >Mayakonda</option><option value='Mayang Imphal' >Mayang Imphal</option><option value='Mayiladuthurai' >Mayiladuthurai</option><option value='Mayurbhanj' >Mayurbhanj</option><option value='Mecheri' >Mecheri</option><option value='Medak' >Medak</option><option value='Medchal' >Medchal</option><option value='Meerut' >Meerut</option><option value='Meghahatuburu Forest village' >Meghahatuburu Forest village</option><option value='Meghnagar' >Meghnagar</option><option value='Meghraj' >Meghraj</option><option value='Mehara Gaon' >Mehara Gaon</option><option value='Mehatpur Basdehra' >Mehatpur Basdehra</option><option value='Mehgaon' >Mehgaon</option><option value='Mehkar' >Mehkar</option><option value='Mehmand' >Mehmand</option><option value='Mehnagar' >Mehnagar</option><option value='Mehndawal' >Mehndawal</option><option value='Mehsana' >Mehsana</option><option value='Mekliganj' >Mekliganj</option><option value='Melacheval' >Melacheval</option><option value='Melachokkanathapuram' >Melachokkanathapuram</option><option value='Melagaram' >Melagaram</option><option value='Melamadai' >Melamadai</option><option value='Melamaiyur' >Melamaiyur</option><option value='Melanattam' >Melanattam</option><option value='Melathiruppanthuruthi' >Melathiruppanthuruthi</option><option value='Melattur' >Melattur</option><option value='Melmananbedu' >Melmananbedu</option><option value='Melpattampakkam' >Melpattampakkam</option><option value='Melukote' >Melukote</option><option value='Melur' >Melur</option><option value='Melvisharam' >Melvisharam</option><option value='Memari' >Memari</option><option value='Mendarla' >Mendarla</option><option value='Mendu' >Mendu</option><option value='Mera' >Mera</option><option value='Merta' >Merta</option><option value='Meru' >Meru</option><option value='Methala' >Methala</option><option value='Metpalli' >Metpalli</option><option value='Mettupalayam' >Mettupalayam</option><option value='Mettur' >Mettur</option><option value='Mewat' >Mewat</option><option value='Meyyanur' >Meyyanur</option><option value='Mhasla' >Mhasla</option><option value='Mhaswad' >Mhaswad</option><option value='Mhaugaon' >Mhaugaon</option><option value='Mhow' >Mhow</option><option value='Midnapore' >Midnapore</option><option value='Midnapur' >Midnapur</option><option value='Mihijam' >Mihijam</option><option value='Mihona' >Mihona</option><option value='Milak' >Milak</option><option value='Milavittan' >Milavittan</option><option value='Minakshipuram' >Minakshipuram</option><option value='Minambakkam' >Minambakkam</option><option value='Mindi' >Mindi</option><option value='Minicoy' >Minicoy</option><option value='Minjur' >Minjur</option><option value='Mira Bhayandar' >Mira Bhayandar</option><option value='Miraj' >Miraj</option><option value='Miramar' >Miramar</option><option value='Miranpur' >Miranpur</option><option value='Mirat' >Mirat</option><option value='Mirat Cantonment' >Mirat Cantonment</option><option value='Mirik' >Mirik</option><option value='Mirpet' >Mirpet</option><option value='Miryalaguda' >Miryalaguda</option><option value='Mirzapur' >Mirzapur</option><option value='Misrikh' >Misrikh</option><option value='Mithapur' >Mithapur</option><option value='Modak' >Modak</option><option value='Modakurichi' >Modakurichi</option><option value='Modasa' >Modasa</option><option value='Modinagar' >Modinagar</option><option value='Moga' >Moga</option><option value='Mogra Badshahpur' >Mogra Badshahpur</option><option value='Mogravadi' >Mogravadi</option><option value='Mohali' >Mohali</option><option value='Mohan' >Mohan</option><option value='Mohanpur' >Mohanpur</option><option value='Mohanpur Mohammadpur' >Mohanpur Mohammadpur</option><option value='Mohanur' >Mohanur</option><option value='Mohgaon' >Mohgaon</option><option value='Mohiuddinagar' >Mohiuddinagar</option><option value='Mohiuddinpur' >Mohiuddinpur</option><option value='Mohpa' >Mohpa</option><option value='Mohpada' >Mohpada</option><option value='Moirang' >Moirang</option><option value='Mokama' >Mokama</option><option value='Mokokchung' >Mokokchung</option><option value='Molakalmuru' >Molakalmuru</option><option value='Mon' >Mon</option><option value='Mongra' >Mongra</option><option value='Monoharpur' >Monoharpur</option><option value='Moonak' >Moonak</option><option value='Mopperipalayam' >Mopperipalayam</option><option value='Moradabad' >Moradabad</option><option value='Moragudi' >Moragudi</option><option value='Moram' >Moram</option><option value='Moran' >Moran</option><option value='Moranhat' >Moranhat</option><option value='Morar' >Morar</option><option value='Morbi' >Morbi</option><option value='Moreh' >Moreh</option><option value='Morena' >Morena</option><option value='Morinda' >Morinda</option><option value='Morjim' >Morjim</option><option value='Mormugao' >Mormugao</option><option value='Morshi' >Morshi</option><option value='Morvi' >Morvi</option><option value='Morwa' >Morwa</option><option value='Moth' >Moth</option><option value='Mothugudam' >Mothugudam</option><option value='Motihari' >Motihari</option><option value='Motipur' >Motipur</option><option value='Mount Abu' >Mount Abu</option><option value='Mowa' >Mowa</option><option value='Mowad' >Mowad</option><option value='Mrigala' >Mrigala</option><option value='Mubarakpur' >Mubarakpur</option><option value='Mudalgi' >Mudalgi</option><option value='Mudalur' >Mudalur</option><option value='Mudbidri' >Mudbidri</option><option value='Muddebihal' >Muddebihal</option><option value='Mudgal' >Mudgal</option><option value='Mudhol' >Mudhol</option><option value='Mudichur' >Mudichur</option><option value='Mudigere' >Mudigere</option><option value='Mudkhed' >Mudkhed</option><option value='Mudukulathur' >Mudukulathur</option><option value='Mudushedde' >Mudushedde</option><option value='Mughal Sarai' >Mughal Sarai</option><option value='Mughal Sarai Railway Settlemen' >Mughal Sarai Railway Settlemen</option><option value='Mugma' >Mugma</option><option value='Muhamma' >Muhamma</option><option value='Muhammadabad' >Muhammadabad</option><option value='Muhammadi' >Muhammadi</option><option value='Mukandgarh' >Mukandgarh</option><option value='Mukasipidariyur' >Mukasipidariyur</option><option value='Mukatsar' >Mukatsar</option><option value='Mukerian' >Mukerian</option><option value='Mukhed' >Mukhed</option><option value='Mukhiguda' >Mukhiguda</option><option value='Mukkudal' >Mukkudal</option><option value='Mukrampur Khema' >Mukrampur Khema</option><option value='Muktsar' >Muktsar</option><option value='Mul' >Mul</option><option value='Mulagumudu' >Mulagumudu</option><option value='Mulakaraipatti' >Mulakaraipatti</option><option value='Mulanur' >Mulanur</option><option value='Mulavukad' >Mulavukad</option><option value='Mulbagal' >Mulbagal</option><option value='Mulgund' >Mulgund</option><option value='Mulki' >Mulki</option><option value='Mullakkadu' >Mullakkadu</option><option value='Mullanpur Dakha' >Mullanpur Dakha</option><option value='Mullanpur Garibdas' >Mullanpur Garibdas</option><option value='Mulshi' >Mulshi</option><option value='Multai' >Multai</option><option value='Mulur' >Mulur</option><option value='Mumbai' >Mumbai</option><option value='Mumbai City' >Mumbai City</option><option value='Mumbai suburban' >Mumbai suburban</option><option value='Munak' >Munak</option><option value='Mundakayam' >Mundakayam</option><option value='Mundargi' >Mundargi</option><option value='Munderi' >Munderi</option><option value='Mundgod' >Mundgod</option><option value='Mundi' >Mundi</option><option value='Mundia' >Mundia</option><option value='Mundora' >Mundora</option><option value='Mundra' >Mundra</option><option value='Mundwa' >Mundwa</option><option value='Mungaoli' >Mungaoli</option><option value='Mungeli' >Mungeli</option><option value='Munger' >Munger</option><option value='Muni Ki Reti' >Muni Ki Reti</option><option value='Munirabad' >Munirabad</option><option value='Munnar' >Munnar</option><option value='Munnur' >Munnur</option><option value='Muradnagar' >Muradnagar</option><option value='Muradpura' >Muradpura</option><option value='Muragachha' >Muragachha</option><option value='Murbad' >Murbad</option><option value='Murgathaul' >Murgathaul</option><option value='Murgud' >Murgud</option><option value='Muri' >Muri</option><option value='Murliganj' >Murliganj</option><option value='Mursan' >Mursan</option><option value='Murshidabad' >Murshidabad</option><option value='Murtijapur' >Murtijapur</option><option value='Murud' >Murud</option><option value='Murudeshwara' >Murudeshwara</option><option value='Muruganpalayam' >Muruganpalayam</option><option value='Murwara' >Murwara</option><option value='Musafirkhana' >Musafirkhana</option><option value='Mushabani' >Mushabani</option><option value='Musiri' >Musiri</option><option value='Mustafabad' >Mustafabad</option><option value='Muthakunnam' >Muthakunnam</option><option value='Muthupet' >Muthupet</option><option value='Muthur' >Muthur</option><option value='Muttayyapuram' >Muttayyapuram</option><option value='Muttupet' >Muttupet</option><option value='Muvarasampettai' >Muvarasampettai</option><option value='Muvattupuzha' >Muvattupuzha</option><option value='Muzaffarnagar' >Muzaffarnagar</option><option value='Muzaffarpur' >Muzaffarpur</option><option value='Muzhappilangad' >Muzhappilangad</option><option value='Myladi' >Myladi</option><option value='Mylapore' >Mylapore</option><option value='Mysore' >Mysore</option><option value='Nabadhai Dutta Pukur' >Nabadhai Dutta Pukur</option><option value='Nabagram' >Nabagram</option><option value='Nabarangpur' >Nabarangpur</option><option value='Nabgram' >Nabgram</option><option value='Nabha' >Nabha</option><option value='Nabinagar' >Nabinagar</option><option value='Nachane' >Nachane</option><option value='Nachhratpur Katabari' >Nachhratpur Katabari</option><option value='Nadapuram' >Nadapuram</option><option value='Nadathara' >Nadathara</option><option value='Nadaun' >Nadaun</option><option value='Nadbai' >Nadbai</option><option value='Nadia' >Nadia</option><option value='Nadiad' >Nadiad</option><option value='Nadigaon' >Nadigaon</option><option value='Nadukkuthagai' >Nadukkuthagai</option><option value='Naduvattam' >Naduvattam</option><option value='Naenwa' >Naenwa</option><option value='Nagai Chaudhry' >Nagai Chaudhry</option><option value='Nagamangala' >Nagamangala</option><option value='Nagaon' >Nagaon</option><option value='Nagapattinam' >Nagapattinam</option><option value='Nagar' >Nagar</option><option value='Nagar Karnul' >Nagar Karnul</option><option value='Nagardeole' >Nagardeole</option><option value='Nagari' >Nagari</option><option value='Nagaur' >Nagaur</option><option value='Nagavakulam' >Nagavakulam</option><option value='Nagda' >Nagda</option><option value='Nagercoil' >Nagercoil</option><option value='Nagina' >Nagina</option><option value='Nagireddipalle' >Nagireddipalle</option><option value='Nagla' >Nagla</option><option value='Nagod' >Nagod</option><option value='Nagojanahalli' >Nagojanahalli</option><option value='Nagothane' >Nagothane</option><option value='Nagpur' >Nagpur</option><option value='Nagram' >Nagram</option><option value='Nagri' >Nagri</option><option value='Nagri Kalan' >Nagri Kalan</option><option value='Nagrota' >Nagrota</option><option value='Nahan' >Nahan</option><option value='Naharkatia' >Naharkatia</option><option value='Naharlagun' >Naharlagun</option><option value='Nai Bazar' >Nai Bazar</option><option value='Naigarhi' >Naigarhi</option><option value='Naihati' >Naihati</option><option value='Nailajanjgir' >Nailajanjgir</option><option value='Naina Devi' >Naina Devi</option><option value='Nainana Jat' >Nainana Jat</option><option value='Nainital' >Nainital</option><option value='Nainital Cantonment' >Nainital Cantonment</option><option value='Nainpur' >Nainpur</option><option value='Najibabad' >Najibabad</option><option value='Nakoda' >Nakoda</option><option value='Nakodar' >Nakodar</option><option value='Nakrekal' >Nakrekal</option><option value='Nakur' >Nakur</option><option value='Nalagarh' >Nalagarh</option><option value='Nalanda' >Nalanda</option><option value='Nalasopara' >Nalasopara</option><option value='Nalbari' >Nalbari</option><option value='Nalco' >Nalco</option><option value='Naldurg' >Naldurg</option><option value='Nalgonda' >Nalgonda</option><option value='Nalhati' >Nalhati</option><option value='Naliya' >Naliya</option><option value='Nalkheda' >Nalkheda</option><option value='Nallampatti' >Nallampatti</option><option value='Nallur' >Nallur</option><option value='Namagiripettai' >Namagiripettai</option><option value='Namakkal' >Namakkal</option><option value='Nambiyur' >Nambiyur</option><option value='Nambol' >Nambol</option><option value='Nambutalai' >Nambutalai</option><option value='Namchi' >Namchi</option><option value='Namli' >Namli</option><option value='Namna Kalan' >Namna Kalan</option><option value='Namrup' >Namrup</option><option value='Namsai' >Namsai</option><option value='Nanakvada' >Nanakvada</option><option value='Nanaunta' >Nanaunta</option><option value='Nandambakkam' >Nandambakkam</option><option value='Nandaprayang' >Nandaprayang</option><option value='Nanded' >Nanded</option><option value='Nandej' >Nandej</option><option value='Nandesari' >Nandesari</option><option value='Nandesari INA' >Nandesari INA</option><option value='Nandigama' >Nandigama</option><option value='Nandikotkur' >Nandikotkur</option><option value='Nandivaram' >Nandivaram</option><option value='Nandura' >Nandura</option><option value='Nandurbar' >Nandurbar</option><option value='Nandyal' >Nandyal</option><option value='Nangal' >Nangal</option><option value='Nangavalli' >Nangavalli</option><option value='Nangavaram' >Nangavaram</option><option value='Nanguneri' >Nanguneri</option><option value='Nanjangud' >Nanjangud</option><option value='Nanjikottai' >Nanjikottai</option><option value='Nannilam' >Nannilam</option><option value='Nanpara' >Nanpara</option><option value='Naoriya Pakhanglakpa' >Naoriya Pakhanglakpa</option><option value='Napasar' >Napasar</option><option value='Naragund' >Naragund</option><option value='Naraina' >Naraina</option><option value='Naraini' >Naraini</option><option value='Naranammalpuram' >Naranammalpuram</option><option value='Naranapuram' >Naranapuram</option><option value='Narasannapeta' >Narasannapeta</option><option value='Narasapur' >Narasapur</option><option value='Narasaraopet' >Narasaraopet</option><option value='Narasimhanaickenpalayam' >Narasimhanaickenpalayam</option><option value='Narasimharajapura' >Narasimharajapura</option><option value='Narasingapuram' >Narasingapuram</option><option value='Narasojipatti' >Narasojipatti</option><option value='Narath' >Narath</option><option value='Narauli' >Narauli</option><option value='Naraura' >Naraura</option><option value='Naravarikuppam' >Naravarikuppam</option><option value='Naravi' >Naravi</option><option value='Narayanavanam' >Narayanavanam</option><option value='Narayanpet' >Narayanpet</option><option value='Narayanpur' >Narayanpur</option><option value='Naregal' >Naregal</option><option value='Narendranagar' >Narendranagar</option><option value='Narkanda' >Narkanda</option><option value='Narkatiaganj' >Narkatiaganj</option><option value='Narkhed' >Narkhed</option><option value='Narmada' >Narmada</option><option value='Narnaul' >Narnaul</option><option value='Narnaund' >Narnaund</option><option value='Naroda' >Naroda</option><option value='Narsampet' >Narsampet</option><option value='Narsapur' >Narsapur</option><option value='Narsimhapur' >Narsimhapur</option><option value='Narsinghpur' >Narsinghpur</option><option value='Narsingi' >Narsingi</option><option value='Narsipatnam' >Narsipatnam</option><option value='Narwana' >Narwana</option><option value='Narwar' >Narwar</option><option value='Nashik' >Nashik</option><option value='Nasirabad' >Nasirabad</option><option value='Nasiyanur' >Nasiyanur</option><option value='Naspur' >Naspur</option><option value='Nasra' >Nasra</option><option value='Nasriganj' >Nasriganj</option><option value='Nasrullaganj' >Nasrullaganj</option><option value='Natham' >Natham</option><option value='Nathampannai' >Nathampannai</option><option value='Nathayyapalem' >Nathayyapalem</option><option value='Nathdwara' >Nathdwara</option><option value='Natibpur' >Natibpur</option><option value='Natrampalli' >Natrampalli</option><option value='Nattakam' >Nattakam</option><option value='Nattam' >Nattam</option><option value='Nattapettai' >Nattapettai</option><option value='Nattarasankottai' >Nattarasankottai</option><option value='Natwar' >Natwar</option><option value='Naubaisa Gaon' >Naubaisa Gaon</option><option value='Naudhia' >Naudhia</option><option value='Naugachhia' >Naugachhia</option><option value='Naugaon' >Naugaon</option><option value='Naugawan Sadat' >Naugawan Sadat</option><option value='Naupala' >Naupala</option><option value='Naurangapur' >Naurangapur</option><option value='Naurozabad' >Naurozabad</option><option value='Naushehra' >Naushehra</option><option value='Nautanwa' >Nautanwa</option><option value='Navadwip' >Navadwip</option><option value='Navagadh' >Navagadh</option><option value='Navagam Ghed' >Navagam Ghed</option><option value='Navalgund' >Navalgund</option><option value='Navalpattu' >Navalpattu</option><option value='Navapur' >Navapur</option><option value='Navelim' >Navelim</option><option value='Navi Mumbai' >Navi Mumbai</option><option value='Navi Mumbai Panvel' >Navi Mumbai Panvel</option><option value='Navsari' >Navsari</option><option value='Nawa' >Nawa</option><option value='Nawabganj' >Nawabganj</option><option value='Nawada' >Nawada</option><option value='Nawalgarh' >Nawalgarh</option><option value='Nawan Shehar' >Nawan Shehar</option><option value='Nawashahr' >Nawashahr</option><option value='Naya Baradwar' >Naya Baradwar</option><option value='Naya Bazar' >Naya Bazar</option><option value='Naya Nangal' >Naya Nangal</option><option value='Nayagarh' >Nayagarh</option><option value='Nayudupeta' >Nayudupeta</option><option value='Nazarethpettai' >Nazarethpettai</option><option value='Nazerath' >Nazerath</option><option value='Nazira' >Nazira</option><option value='Nebadhai Duttapukur' >Nebadhai Duttapukur</option><option value='Nedumangad' >Nedumangad</option><option value='Neem Ka Thana' >Neem Ka Thana</option><option value='Neemrana' >Neemrana</option><option value='Neemuch' >Neemuch</option><option value='Nehon' >Nehon</option><option value='Neikkarapatti' >Neikkarapatti</option><option value='Neiyyur' >Neiyyur</option><option value='Nelimaria' >Nelimaria</option><option value='Nellikkuppam' >Nellikkuppam</option><option value='Nelliyalam' >Nelliyalam</option><option value='Nellore' >Nellore</option><option value='Nelmangala' >Nelmangala</option><option value='Nemili' >Nemili</option><option value='Nemilicheri' >Nemilicheri</option><option value='Nenmenikkara' >Nenmenikkara</option><option value='Nepa Nagar' >Nepa Nagar</option><option value='Neral' >Neral</option><option value='Neripperichal' >Neripperichal</option><option value='Nerkunram' >Nerkunram</option><option value='Nerkuppai' >Nerkuppai</option><option value='Nerunjipettai' >Nerunjipettai</option><option value='Netarhat' >Netarhat</option><option value='Neuton Chikhli Kalan' >Neuton Chikhli Kalan</option><option value='New Barrackpore' >New Barrackpore</option><option value='New Bongaigaon Railway Colony' >New Bongaigaon Railway Colony</option><option value='New Delhi' >New Delhi</option><option value='New Mahe' >New Mahe</option><option value='Newa Talai' >Newa Talai</option><option value='Neykkarappatti' >Neykkarappatti</option><option value='Neyveli' >Neyveli</option><option value='Neyyattinkara' >Neyyattinkara</option><option value='Ni Barakpur' >Ni Barakpur</option><option value='Nibra' >Nibra</option><option value='Nichlaul' >Nichlaul</option><option value='Nicobar' >Nicobar</option><option value='Nidadavole' >Nidadavole</option><option value='Nidamangalam' >Nidamangalam</option><option value='Nidhauli Kalan' >Nidhauli Kalan</option><option value='Nigdi' >Nigdi</option><option value='Nihtaur' >Nihtaur</option><option value='Nilakkottai' >Nilakkottai</option><option value='Nilanga' >Nilanga</option><option value='Nilankarai' >Nilankarai</option><option value='Nildoh' >Nildoh</option><option value='Nileshwar' >Nileshwar</option><option value='Nilokheri' >Nilokheri</option><option value='Nimach' >Nimach</option><option value='Nimaj' >Nimaj</option><option value='Nimaparha' >Nimaparha</option><option value='Nimbahera' >Nimbahera</option><option value='Nimbhore' >Nimbhore</option><option value='Nindaura' >Nindaura</option><option value='Ningthoukhong' >Ningthoukhong</option><option value='Nipani' >Nipani</option><option value='Nirmal' >Nirmal</option><option value='Nirmali' >Nirmali</option><option value='Nirsa' >Nirsa</option><option value='Nitte' >Nitte</option><option value='Niwai' >Niwai</option><option value='Niz' >Hajo</option><option value='Nizamabad' >Nizamabad</option><option value='No City' >No City</option><option value='Noamundi' >Noamundi</option><option value='Noapara' >Noapara</option><option value='Nohar' >Nohar</option><option value='Noida' >Noida</option><option value='Nokpul' >Nokpul</option><option value='Nongmynsong' >Nongmynsong</option><option value='Nongpoh' >Nongpoh</option><option value='Nongstoin' >Nongstoin</option><option value='Nongthymmai' >Nongthymmai</option><option value='North 24 Parganas' >North 24 Parganas</option><option value='North and Middle Andaman' >North and Middle Andaman</option><option value='North Barakpur' >North Barakpur</option><option value='North Cachar Hills' >North Cachar Hills</option><option value='North Delhi' >North Delhi</option><option value='North East Delhi' >North East Delhi</option><option value='North Goa' >North Goa</option><option value='North Guwahati' >North Guwahati</option><option value='North Sikkim' >North Sikkim</option><option value='North Tripura' >North Tripura</option><option value='North Vanlaiphai' >North Vanlaiphai</option><option value='North West Delhi' >North West Delhi</option><option value='Northern Railway Colony' >Northern Railway Colony</option><option value='Nuapada' >Nuapada</option><option value='Nuapatna' >Nuapatna</option><option value='Nuh' >Nuh</option><option value='Numaligarh' >Numaligarh</option><option value='Nurmahal' >Nurmahal</option><option value='Nuzvid' >Nuzvid</option><option value='Nyamati' >Nyamati</option><option value='Nyoria Husenpur' >Nyoria Husenpur</option><option value='Nyotini' >Nyotini</option><option value='Obedullaganj' >Obedullaganj</option><option value='Obra' >Obra</option><option value='OCL Industrialship' >OCL Industrialship</option><option value='Odaipatti' >Odaipatti</option><option value='Odaiyakulam' >Odaiyakulam</option><option value='Oddanchatram' >Oddanchatram</option><option value='Ode' >Ode</option><option value='Odlabari' >Odlabari</option><option value='Odugathur' >Odugathur</option><option value='Oel Dhakwa' >Oel Dhakwa</option><option value='Oggiyamduraipakkam' >Oggiyamduraipakkam</option><option value='Oinam' >Oinam</option><option value='Ojhar' >Ojhar</option><option value='Okaf' >Okaf</option><option value='Okha' >Okha</option><option value='Okni' >Okni</option><option value='Olagadam' >Olagadam</option><option value='Olavanna' >Olavanna</option><option value='Old Maldah' >Old Maldah</option><option value='Olpad' >Olpad</option><option value='Omalur' >Omalur</option><option value='Omerkhan daira' >Omerkhan daira</option><option value='Omkareshwar' >Omkareshwar</option><option value='Ondal' >Ondal</option><option value='One SGM' >One SGM</option><option value='Ongole' >Ongole</option><option value='Ooty' >Ooty</option><option value='Orachha' >Orachha</option><option value='Orai' >Orai</option><option value='Oran' >Oran</option><option value='Orathanadu' >Orathanadu</option><option value='Ordinance Factory Itarsi' >Ordinance Factory Itarsi</option><option value='Ordinance Factory Muradnagar' >Ordinance Factory Muradnagar</option><option value='Orla' >Orla</option><option value='Osmanabad' >Osmanabad</option><option value='Osmania University' >Osmania University</option><option value='Othakadai' >Othakadai</option><option value='Othakalmandapam' >Othakalmandapam</option><option value='Others' >Others</option><option value='Ottapalam' >Ottapalam</option><option value='Ottappalam' >Ottappalam</option><option value='Ottapparai' >Ottapparai</option><option value='Ozhukarai' >Ozhukarai</option><option value='Pachgaon' >Pachgaon</option><option value='Pachmarhi' >Pachmarhi</option><option value='Pachmarhi Cantonment' >Pachmarhi Cantonment</option><option value='Pachora' >Pachora</option><option value='Pachore' >Pachore</option><option value='Pachperwa' >Pachperwa</option><option value='Pacode' >Pacode</option><option value='Padagha' >Padagha</option><option value='Padaividu' >Padaividu</option><option value='Paddhari' >Paddhari</option><option value='Padianallur' >Padianallur</option><option value='Padirikuppam' >Padirikuppam</option><option value='Padmanabhapuram' >Padmanabhapuram</option><option value='Padra' >Padra</option><option value='Padrauna' >Padrauna</option><option value='Padririvedu' >Padririvedu</option><option value='Padu' >Padu</option><option value='Paduvilayi' >Paduvilayi</option><option value='Pahalgam' >Pahalgam</option><option value='Paharpur' >Paharpur</option><option value='Pahasu' >Pahasu</option><option value='Paintepur' >Paintepur</option><option value='Pairagachha' >Pairagachha</option><option value='Paithan' >Paithan</option><option value='Pakala' >Pakala</option><option value='Pakaur' >Pakaur</option><option value='Pakur' >Pakur</option><option value='Palaganangudy' >Palaganangudy</option><option value='Palai' >Palai</option><option value='Palaimpatti' >Palaimpatti</option><option value='Palakkad' >Palakkad</option><option value='Palakkodu' >Palakkodu</option><option value='Palakole' >Palakole</option><option value='Palakurthi' >Palakurthi</option><option value='Palamau' >Palamau</option><option value='Palamedu' >Palamedu</option><option value='Palampur' >Palampur</option><option value='Palamu' >Palamu</option><option value='Palani' >Palani</option><option value='Palani Chettipatti' >Palani Chettipatti</option><option value='Palanpur' >Palanpur</option><option value='Palasa' >Palasa</option><option value='Palasbari' >Palasbari</option><option value='Palashban' >Palashban</option><option value='Palavakkam' >Palavakkam</option><option value='Palavansathu' >Palavansathu</option><option value='Palawa' >Palawa</option><option value='Palayad' >Palayad</option><option value='Palayakayal' >Palayakayal</option><option value='Palayam' >Palayam</option><option value='Palayamkottai' >Palayamkottai</option><option value='Palchorai' >Palchorai</option><option value='Palda' >Palda</option><option value='Pale' >Pale</option><option value='Palej' >Palej</option><option value='Palempalle' >Palempalle</option><option value='Palera' >Palera</option><option value='Palghar' >Palghar</option><option value='Pali' >Pali</option><option value='Palia Kalan' >Palia Kalan</option><option value='Palissery' >Palissery</option><option value='Palitana' >Palitana</option><option value='Paliyad' >Paliyad</option><option value='Palkonda' >Palkonda</option><option value='Palladam' >Palladam</option><option value='Pallapalayam' >Pallapalayam</option><option value='Pallapatti' >Pallapatti</option><option value='Pallattur' >Pallattur</option><option value='Pallavaram' >Pallavaram</option><option value='Pallikaranai' >Pallikaranai</option><option value='Pallikkunnu' >Pallikkunnu</option><option value='Pallikonda' >Pallikonda</option><option value='Pallipalaiyam' >Pallipalaiyam</option><option value='Pallipalaiyam Agraharam' >Pallipalaiyam Agraharam</option><option value='Pallipattu' >Pallipattu</option><option value='Palmaner' >Palmaner</option><option value='Paluvai' >Paluvai</option><option value='Palwal' >Palwal</option><option value='Palwancha' >Palwancha</option><option value='Pammal' >Pammal</option><option value='Pampore' >Pampore</option><option value='Pamur' >Pamur</option><option value='Panagar' >Panagar</option><option value='Panagudi' >Panagudi</option><option value='Panaimarathupatti' >Panaimarathupatti</option><option value='Panaji' >Panaji</option><option value='Panapakkam' >Panapakkam</option><option value='Panara' >Panara</option><option value='Panboli' >Panboli</option><option value='Panchet' >Panchet</option><option value='Panchgani' >Panchgani</option><option value='Panchgram' >Panchgram</option><option value='Panchkula' >Panchkula</option><option value='Panchla' >Panchla</option><option value='Panchmahal' >Panchmahal</option><option value='Panchpara' >Panchpara</option><option value='Pandamangalam' >Pandamangalam</option><option value='Pandaria' >Pandaria</option><option value='Pandariya' >Pandariya</option><option value='Pandavapura' >Pandavapura</option><option value='Pandesara' >Pandesara</option><option value='Pandhakarwada' >Pandhakarwada</option><option value='Pandhana' >Pandhana</option><option value='Pandharpur' >Pandharpur</option><option value='Pandhurna' >Pandhurna</option><option value='Pandoh' >Pandoh</option><option value='Pandua' >Pandua</option><option value='Pangachhiya' >Pangachhiya</option><option value='Panhala' >Panhala</option><option value='Paniara' >Paniara</option><option value='Panihati' >Panihati</option><option value='Panipat' >Panipat</option><option value='Panipat Taraf Ansar' >Panipat Taraf Ansar</option><option value='Panipat Taraf Makhdum Zadgan' >Panipat Taraf Makhdum Zadgan</option><option value='Panipat Taraf Rajputan' >Panipat Taraf Rajputan</option><option value='Panjim' >Panjim</option><option value='Panna' >Panna</option><option value='Pannaikadu' >Pannaikadu</option><option value='Pannaipuram' >Pannaipuram</option><option value='Panniyannur' >Panniyannur</option><option value='Pannuratti' >Pannuratti</option><option value='Panoli' >Panoli</option><option value='Panrra' >Panrra</option><option value='Panruti' >Panruti</option><option value='Pansemal' >Pansemal</option><option value='Pantalam' >Pantalam</option><option value='Panthiramkavu' >Panthiramkavu</option><option value='Panuhat' >Panuhat</option><option value='Panur' >Panur</option><option value='Panvel' >Panvel</option><option value='Paonta Sahib' >Paonta Sahib</option><option value='Papampeta' >Papampeta</option><option value='Papanasam' >Papanasam</option><option value='Pappankurichi' >Pappankurichi</option><option value='Papparapatti' >Papparapatti</option><option value='Pappinisseri' >Pappinisseri</option><option value='Pappireddipatti' >Pappireddipatti</option><option value='Papum Pare' >Papum Pare</option><option value='Par Beliya' >Par Beliya</option><option value='Paradip' >Paradip</option><option value='Paradwip' >Paradwip</option><option value='Paramakkudi' >Paramakkudi</option><option value='Paramankurichi' >Paramankurichi</option><option value='Paramathi' >Paramathi</option><option value='Paranda' >Paranda</option><option value='Parangippettai' >Parangippettai</option><option value='Parasamba' >Parasamba</option><option value='Parashkol' >Parashkol</option><option value='Parasi' >Parasi</option><option value='Parassala' >Parassala</option><option value='Paratdih' >Paratdih</option><option value='Paravai' >Paravai</option><option value='Paravur' >Paravur</option><option value='Parbbatipur' >Parbbatipur</option><option value='Parbhani' >Parbhani</option><option value='Parcem' >Parcem</option><option value='Pardi' >Pardi</option><option value='Parganas' >Parganas</option><option value='Parichha' >Parichha</option><option value='Parichhatgarh' >Parichhatgarh</option><option value='Parlakimidi' >Parlakimidi</option><option value='Parli' >Parli</option><option value='Parnera' >Parnera</option><option value='Parola' >Parola</option><option value='Parole' >Parole</option><option value='Parra' >Parra</option><option value='Parsadepur' >Parsadepur</option><option value='Partapur' >Partapur</option><option value='Partur' >Partur</option><option value='Parui' >Parui</option><option value='Parvat' >Parvat</option><option value='Parvatipuram' >Parvatipuram</option><option value='Parvatsar' >Parvatsar</option><option value='Parwanoo' >Parwanoo</option><option value='Parwanu' >Parwanu</option><option value='Pasan' >Pasan</option><option value='Paschim Jitpur' >Paschim Jitpur</option><option value='Paschim Punro Para' >Paschim Punro Para</option><option value='Pashchim Champaran' >Pashchim Champaran</option><option value='Pashchim Singhbhum' >Pashchim Singhbhum</option><option value='Pasighat' >Pasighat</option><option value='Pasoond' >Pasoond</option><option value='Pasthal' >Pasthal</option><option value='Pasur' >Pasur</option><option value='Patala' >Patala</option><option value='Patamundai' >Patamundai</option><option value='Patan' >Patan</option><option value='Patancheru' >Patancheru</option><option value='Patdi' >Patdi</option><option value='Pathalgaon' >Pathalgaon</option><option value='Pathamadai' >Pathamadai</option><option value='Pathanamthitta' >Pathanamthitta</option><option value='Pathanapuram' >Pathanapuram</option><option value='Pathankot' >Pathankot</option><option value='Pathardi' >Pathardi</option><option value='Pathardih' >Pathardih</option><option value='Patharia' >Patharia</option><option value='Pathiriyad' >Pathiriyad</option><option value='Pathri' >Pathri</option><option value='Pathsala' >Pathsala</option><option value='Patiala' >Patiala</option><option value='Patiyali' >Patiyali</option><option value='Patna' >Patna</option><option value='Patnagarh' >Patnagarh</option><option value='Patrasaer' >Patrasaer</option><option value='Patratu' >Patratu</option><option value='Pattabong Tea Garden' >Pattabong Tea Garden</option><option value='Pattambi' >Pattambi</option><option value='Pattan' >Pattan</option><option value='Pattanagere' >Pattanagere</option><option value='Pattinam' >Pattinam</option><option value='Pattiom' >Pattiom</option><option value='Pattiviranpatti' >Pattiviranpatti</option><option value='Pattran' >Pattran</option><option value='Pattukkottai' >Pattukkottai</option><option value='Patuli' >Patuli</option><option value='Patulia' >Patulia</option><option value='Patur' >Patur</option><option value='Pauri' >Pauri</option><option value='Pauri Garhwal' >Pauri Garhwal</option><option value='Pavagada' >Pavagada</option><option value='Pavaratty' >Pavaratty</option><option value='Pawai' >Pawai</option><option value='Pawayan' >Pawayan</option><option value='Pawni' >Pawni</option><option value='Payakaraopet' >Payakaraopet</option><option value='Payal' >Payal</option><option value='Payyannur' >Payyannur</option><option value='Pazhugal' >Pazhugal</option><option value='Pedagantyada' >Pedagantyada</option><option value='Pedana' >Pedana</option><option value='Peddapalli' >Peddapalli</option><option value='Peddapuram' >Peddapuram</option><option value='Peermade' >Peermade</option><option value='Pehowa' >Pehowa</option><option value='Pen' >Pen</option><option value='Pendra' >Pendra</option><option value='Pendurthi' >Pendurthi</option><option value='Penha de Franca' >Penha de Franca</option><option value='Pennadam' >Pennadam</option><option value='Pennagaram' >Pennagaram</option><option value='Pennathur' >Pennathur</option><option value='Penugonda' >Penugonda</option><option value='Penukonda' >Penukonda</option><option value='Peraiyur' >Peraiyur</option><option value='Perakam' >Perakam</option><option value='Peralam' >Peralam</option><option value='Peralasseri' >Peralasseri</option><option value='Perambalur' >Perambalur</option><option value='Peranamallur' >Peranamallur</option><option value='Peravurani' >Peravurani</option><option value='Peringathur' >Peringathur</option><option value='Perinthalmanna' >Perinthalmanna</option><option value='Periyakodiveri' >Periyakodiveri</option><option value='Periyakulam' >Periyakulam</option><option value='Periyanayakkanpalaiyam' >Periyanayakkanpalaiyam</option><option value='Periyanegamam' >Periyanegamam</option><option value='Periyapatti' >Periyapatti</option><option value='Periyasemur' >Periyasemur</option><option value='Pernambut' >Pernambut</option><option value='Pernem' >Pernem</option><option value='Perole' >Perole</option><option value='Perumagalur' >Perumagalur</option><option value='Perumandi' >Perumandi</option><option value='Perumanna' >Perumanna</option><option value='Perumbaikadu' >Perumbaikadu</option><option value='Perumbavoor' >Perumbavoor</option><option value='Perumuchi' >Perumuchi</option><option value='Perundurai' >Perundurai</option><option value='Perungalathur' >Perungalathur</option><option value='Perungudi' >Perungudi</option><option value='Perungulam' >Perungulam</option><option value='Perur' >Perur</option><option value='Perur Chettipalaiyam' >Perur Chettipalaiyam</option><option value='Pethampalayam' >Pethampalayam</option><option value='Pethanaickenpalayam' >Pethanaickenpalayam</option><option value='Pethumri' >Pethumri</option><option value='Petlad' >Petlad</option><option value='Petlawad' >Petlawad</option><option value='Petrochemical Complex' >Petrochemical Complex</option><option value='Phagwara' >Phagwara</option><option value='Phalauda' >Phalauda</option><option value='Phalna' >Phalna</option><option value='Phalodi' >Phalodi</option><option value='Phaltan' >Phaltan</option><option value='Phaphund' >Phaphund</option><option value='Phek' >Phek</option><option value='Phillaur' >Phillaur</option><option value='Phirangipuram' >Phirangipuram</option><option value='Phulabani' >Phulabani</option><option value='Phulera' >Phulera</option><option value='Phulia' >Phulia</option><option value='Phulpur' >Phulpur</option><option value='Phulwari' >Phulwari</option><option value='Phulwaria' >Phulwaria</option><option value='Phunderdihari' >Phunderdihari</option><option value='Phuph Kalan' >Phuph Kalan</option><option value='Phusro' >Phusro</option><option value='Pichhore' >Pichhore</option><option value='Pihani' >Pihani</option><option value='Pilani' >Pilani</option><option value='Pilerne' >Pilerne</option><option value='Pilibanga' >Pilibanga</option><option value='Pilibhit' >Pilibhit</option><option value='Pilkana' >Pilkana</option><option value='Pilkhuwa' >Pilkhuwa</option><option value='Pillanallur' >Pillanallur</option><option value='Pimpri' >Pimpri</option><option value='Pinahat' >Pinahat</option><option value='Pinarayi' >Pinarayi</option><option value='Pindwara' >Pindwara</option><option value='Pinjaur' >Pinjaur</option><option value='Pipalia Kalan' >Pipalia Kalan</option><option value='Pipalsana Chaudhari' >Pipalsana Chaudhari</option><option value='Pipar' >Pipar</option><option value='Pipariya' >Pipariya</option><option value='Pipiganj' >Pipiganj</option><option value='Pipili' >Pipili</option><option value='Pipliya Mandi' >Pipliya Mandi</option><option value='Piploda' >Piploda</option><option value='Pipraich' >Pipraich</option><option value='Pipri' >Pipri</option><option value='Piravam' >Piravam</option><option value='Pirawa' >Pirawa</option><option value='Piriyapatna' >Piriyapatna</option><option value='Pirkankaranai' >Pirkankaranai</option><option value='Piro' >Piro</option><option value='Pissurlem' >Pissurlem</option><option value='Pithampur' >Pithampur</option><option value='Pithapuram' >Pithapuram</option><option value='Pithora' >Pithora</option><option value='Pithoragarh' >Pithoragarh</option><option value='Pithoragharh' >Pithoragharh</option><option value='Podara' >Podara</option><option value='Pokaran' >Pokaran</option><option value='Poladpur' >Poladpur</option><option value='Polasara' >Polasara</option><option value='Polay Kalan' >Polay Kalan</option><option value='Polichalur' >Polichalur</option><option value='Pollachi' >Pollachi</option><option value='Polur' >Polur</option><option value='Ponda' >Ponda</option><option value='Pondar Kanali' >Pondar Kanali</option><option value='Pondicherry' >Pondicherry</option><option value='Ponmani' >Ponmani</option><option value='Ponnamaravathi' >Ponnamaravathi</option><option value='Ponnampatti' >Ponnampatti</option><option value='Ponnampet' >Ponnampet</option><option value='Ponnani' >Ponnani</option><option value='Ponneri' >Ponneri</option><option value='Ponnur' >Ponnur</option><option value='Poonch' >Poonch</option><option value='Porbandar' >Porbandar</option><option value='Porompat' >Porompat</option><option value='Porsa' >Porsa</option><option value='Porur' >Porur</option><option value='Porvorim' >Porvorim</option><option value='Pothanur' >Pothanur</option><option value='Pothatturpettai' >Pothatturpettai</option><option value='Pothinamallayyapalem' >Pothinamallayyapalem</option><option value='Pottore' >Pottore</option><option value='Prakasam' >Prakasam</option><option value='Prantij' >Prantij</option><option value='Prasadampadu' >Prasadampadu</option><option value='Prasantinilayam' >Prasantinilayam</option><option value='Pratapgarh' >Pratapgarh</option><option value='Pratapgarh' >Pratapgarh</option><option value='Pratapsasan' >Pratapsasan</option><option value='Pratitnagar' >Pratitnagar</option><option value='Prayagpur' >Prayagpur</option><option value='Prithvipur' >Prithvipur</option><option value='Proddatur' >Proddatur</option><option value='Puducherry' >Puducherry</option><option value='Pudukad' >Pudukad</option><option value='Pudukadai' >Pudukadai</option><option value='Pudukkottai' >Pudukkottai</option><option value='Pudukkottai Cantonment' >Pudukkottai Cantonment</option><option value='Pudukottai' >Pudukottai</option><option value='Pudupalaiyam Aghraharam' >Pudupalaiyam Aghraharam</option><option value='Pudupalayam' >Pudupalayam</option><option value='Pudupatti' >Pudupatti</option><option value='Pudupattinam' >Pudupattinam</option><option value='Pudur' >Pudur</option><option value='Puduvayal' >Puduvayal</option><option value='Pujali' >Pujali</option><option value='Pukhrayan' >Pukhrayan</option><option value='Pulambadi' >Pulambadi</option><option value='Pulampatti' >Pulampatti</option><option value='Pulgaon' >Pulgaon</option><option value='Pulivendla' >Pulivendla</option><option value='Puliyampatti' >Puliyampatti</option><option value='Puliyankudi' >Puliyankudi</option><option value='Puliyur' >Puliyur</option><option value='Pullampadi' >Pullampadi</option><option value='Puluvapatti' >Puluvapatti</option><option value='Pulwama' >Pulwama</option><option value='Punahana' >Punahana</option><option value='Punalur' >Punalur</option><option value='Punamalli' >Punamalli</option><option value='Punch' >Punch</option><option value='Pundri' >Pundri</option><option value='Pune' >Pune</option><option value='Pune Cantonment' >Pune Cantonment</option><option value='Punganuru' >Punganuru</option><option value='Punjai Puliyampatti' >Punjai Puliyampatti</option><option value='Punjai Thottakurichi' >Punjai Thottakurichi</option><option value='Punjaipugalur' >Punjaipugalur</option><option value='Puranattukara' >Puranattukara</option><option value='Puranpur' >Puranpur</option><option value='Purba Champaran' >Purba Champaran</option><option value='Purba Medinipur' >Purba Medinipur</option><option value='Purba Singhbhum' >Purba Singhbhum</option><option value='Purba Tajpur' >Purba Tajpur</option><option value='Purdil Nagar' >Purdil Nagar</option><option value='Puri' >Puri</option><option value='Purna' >Purna</option><option value='Purnia' >Purnia</option><option value='Purqazi' >Purqazi</option><option value='Purulia' >Purulia</option><option value='Purushottamnagar' >Purushottamnagar</option><option value='Purushottampur' >Purushottampur</option><option value='Purwa' >Purwa</option><option value='Pusa' >Pusa</option><option value='Pusad' >Pusad</option><option value='Pushkar' >Pushkar</option><option value='Puthalam' >Puthalam</option><option value='Puthunagaram' >Puthunagaram</option><option value='Puthuppariyaram' >Puthuppariyaram</option><option value='Putteri' >Putteri</option><option value='Puvalur' >Puvalur</option><option value='Puzhal' >Puzhal</option><option value='Puzhathi' >Puzhathi</option><option value='Puzhithivakkam' >Puzhithivakkam</option><option value='Pynthorumkhrah' >Pynthorumkhrah</option><option value='Qadian' >Qadian</option><option value='Qasimpur' >Qasimpur</option><option value='Qazigund' >Qazigund</option><option value='Quepem' >Quepem</option><option value='Queula' >Queula</option><option value='Qutubullapur' >Qutubullapur</option><option value='Rabkavi' >Rabkavi</option><option value='Rabupura' >Rabupura</option><option value='Radaur' >Radaur</option><option value='Radha Kund' >Radha Kund</option><option value='Radhanpur' >Radhanpur</option><option value='Rae Bareilly' >Rae Bareilly</option><option value='Rae Bareli' >Rae Bareli</option><option value='Rafiganj' >Rafiganj</option><option value='Raghogarh' >Raghogarh</option><option value='Raghudebbati' >Raghudebbati</option><option value='Raghudebpur' >Raghudebpur</option><option value='Raghunathchak' >Raghunathchak</option><option value='Raghunathpur' >Dankuni</option><option value='Raghunathpur' >Magra</option><option value='Raha' >Raha</option><option value='Rahatgarh' >Rahatgarh</option><option value='Rahimatpur' >Rahimatpur</option><option value='Rahon' >Rahon</option><option value='Rahta Pimplas' >Rahta Pimplas</option><option value='Rahuri' >Rahuri</option><option value='Raia' >Raia</option><option value='Raichur' >Raichur</option><option value='Raigachhi' >Raigachhi</option><option value='Raigad' >Raigad</option><option value='Raiganj' >Raiganj</option><option value='Raigarh' >Raigarh</option><option value='Raikot' >Raikot</option><option value='Raipur' >Raipur</option><option value='Raipur Rani' >Raipur Rani</option><option value='Rairangpur' >Rairangpur</option><option value='Raisen' >Raisen</option><option value='Raisinghnagar' >Raisinghnagar</option><option value='Raiwala' >Raiwala</option><option value='Raiya' >Raiya</option><option value='Raj Gangpur' >Raj Gangpur</option><option value='Raja Ka Rampur' >Raja Ka Rampur</option><option value='Raja Sansi' >Raja Sansi</option><option value='Rajahmundry' >Rajahmundry</option><option value='Rajakhedi' >Rajakhedi</option><option value='Rajakhera' >Rajakhera</option><option value='Rajaldesar' >Rajaldesar</option><option value='Rajamahendri' >Rajamahendri</option><option value='Rajampet' >Rajampet</option><option value='Rajapalayam' >Rajapalayam</option><option value='Rajarhat Gopalpur' >Rajarhat Gopalpur</option><option value='Rajauri' >Rajauri</option><option value='Rajendranagar' >Rajendranagar</option><option value='Rajgamar' >Rajgamar</option><option value='Rajgarh' >Rajgarh</option><option value='Rajgir' >Rajgir</option><option value='Rajgurunagar' >Rajgurunagar</option><option value='Rajhara' >Rajhara</option><option value='Rajkot' >Rajkot</option><option value='Rajmahal' >Rajmahal</option><option value='Rajnagar' >Rajnagar</option><option value='Rajnandgaon' >Rajnandgaon</option><option value='Rajoli' >Rajoli</option><option value='Rajpipla' >Rajpipla</option><option value='Rajpura' >Rajpura</option><option value='Rajsamand' >Rajsamand</option><option value='Rajula' >Rajula</option><option value='Rajur' >Rajur</option><option value='Rajura' >Rajura</option><option value='Ram Das' >Ram Das</option><option value='Ramachandrapuram' >Ramachandrapuram</option><option value='Ramagundam' >Ramagundam</option><option value='Raman' >Raman</option><option value='Ramanagara' >Ramanagara</option><option value='Ramanagaram' >Ramanagaram</option><option value='Ramanathapuram' >Ramanathapuram</option><option value='Ramanattukara' >Ramanattukara</option><option value='Ramanayyapeta' >Ramanayyapeta</option><option value='Ramanuj Ganj' >Ramanuj Ganj</option><option value='Ramarajupalli' >Ramarajupalli</option><option value='Ramavarappadu' >Ramavarappadu</option><option value='Ramban' >Ramban</option><option value='Rambha' >Rambha</option><option value='Ramchandrapur' >Ramchandrapur</option><option value='Ramdurg' >Ramdurg</option><option value='Ramganj Mandi' >Ramganj Mandi</option><option value='Ramgarh' >Ramgarh</option><option value='Ramjibanpur' >Ramjibanpur</option><option value='Ramkola' >Ramkola</option><option value='Ramod' >Ramod</option><option value='Rampachodavaram' >Rampachodavaram</option><option value='Rampur' >Rampur</option><option value='Rampur Baghelan' >Rampur Baghelan</option><option value='Rampur Bhawanipur' >Rampur Bhawanipur</option><option value='Rampur Hat' >Rampur Hat</option><option value='Rampur Karkhana' >Rampur Karkhana</option><option value='Rampur Maniharan' >Rampur Maniharan</option><option value='Rampur Naikin' >Rampur Naikin</option><option value='Ramtek' >Ramtek</option><option value='Ranaghat' >Ranaghat</option><option value='Ranapur' >Ranapur</option><option value='Ranavav' >Ranavav</option><option value='Ranbirsingh Pora' >Ranbirsingh Pora</option><option value='Ranchi' >Ranchi</option><option value='Ranga Reddy district' >Ranga Reddy district</option><option value='Rangapara' >Rangapara</option><option value='Rangareddi' >Rangareddi</option><option value='Rangat' >Rangat</option><option value='Rangia' >Rangia</option><option value='Rangpo' >Rangpo</option><option value='Rani' >Rani</option><option value='Rania' >Rania</option><option value='Ranibennur' >Ranibennur</option><option value='Raniganj' >Raniganj</option><option value='Ranikhet' >Ranikhet</option><option value='Ranipet' >Ranipet</option><option value='Ranipura' >Ranipura</option><option value='Ranir Bazar' >Ranir Bazar</option><option value='Raniwara' >Raniwara</option><option value='Ranoli' >Ranoli</option><option value='Rapar' >Rapar</option><option value='Rashidpur Garhi' >Rashidpur Garhi</option><option value='Rasipuram' >Rasipuram</option><option value='Rasra' >Rasra</option><option value='Rasulabad' >Rasulabad</option><option value='Ratan Nagar' >Ratan Nagar</option><option value='Ratanpur' >Ratanpur</option><option value='Rath' >Rath</option><option value='Ratibati' >Ratibati</option><option value='Ratiya' >Ratiya</option><option value='Ratlam' >Ratlam</option><option value='Ratlam Kasba' >Ratlam Kasba</option><option value='Ratnagiri' >Ratnagiri</option><option value='Rau' >Rau</option><option value='Raurkela' >Raurkela</option><option value='Raurkela Civil Township' >Raurkela Civil Township</option><option value='Ravalgaon' >Ravalgaon</option><option value='Raver' >Raver</option><option value='Ravulapalam' >Ravulapalam</option><option value='Rawalsar' >Rawalsar</option><option value='Rawatbhata' >Rawatbhata</option><option value='Rawatsar' >Rawatsar</option><option value='Raxaul' >Raxaul</option><option value='Ray' >Ray</option><option value='Raya' >Raya</option><option value='Rayachoti' >Rayachoti</option><option value='Rayadrug' >Rayadrug</option><option value='Rayagada' >Rayagada</option><option value='Rayagiri' >Rayagiri</option><option value='Raybag' >Raybag</option><option value='Raypur' >Raypur</option><option value='Rayya' >Rayya</option><option value='Razam' >Razam</option><option value='Razole' >Razole</option><option value='Reasi' >Reasi</option><option value='Redhakhol' >Redhakhol</option><option value='Rehambal' >Rehambal</option><option value='Rehla' >Rehla</option><option value='Rehli' >Rehli</option><option value='Rehti' >Rehti</option><option value='Reis Magos' >Reis Magos</option><option value='Religara' >Religara</option><option value='Remuna' >Remuna</option><option value='Rengali' >Rengali</option><option value='Renigunta' >Renigunta</option><option value='Renukut' >Renukut</option><option value='Reoti' >Reoti</option><option value='Repalle' >Repalle</option><option value='Resubelpara' >Resubelpara</option><option value='Revadanda' >Revadanda</option><option value='Revelganj' >Revelganj</option><option value='Rewa' >Rewa</option><option value='Rewari' >Rewari</option><option value='Ri' >Bhoi</option><option value='Richha' >Richha</option><option value='Rikhabdev' >Rikhabdev</option><option value='Ringas' >Ringas</option><option value='Rishikesh' >Rishikesh</option><option value='Rishikesh Cantonment' >Rishikesh Cantonment</option><option value='Rishikonda' >Rishikonda</option><option value='Rishra' >Rishra</option><option value='Rishra Cantonment' >Rishra Cantonment</option><option value='Risia Bazar' >Risia Bazar</option><option value='Risod' >Risod</option><option value='Rithapuram' >Rithapuram</option><option value='Rithora' >Rithora</option><option value='Robertsganj' >Robertsganj</option><option value='Robertsonpet' >Robertsonpet</option><option value='Roha Ashtami' >Roha Ashtami</option><option value='Rohraband' >Rohraband</option><option value='Rohru' >Rohru</option><option value='Rohtak' >Rohtak</option><option value='Rohtas' >Rohtas</option><option value='Roing' >Roing</option><option value='Ron' >Ron</option><option value='Roorkee' >Roorkee</option><option value='Ropar' >Ropar</option><option value='Rosalpatti' >Rosalpatti</option><option value='Rourkela' >Rourkela</option><option value='Roza' >Roza</option><option value='Rudarpur' >Rudarpur</option><option value='Rudauli' >Rudauli</option><option value='Rudayan' >Rudayan</option><option value='Rudraprayag' >Rudraprayag</option><option value='Rudrapur' >Rudrapur</option><option value='Rudravathi' >Rudravathi</option><option value='Ruiya' >Ruiya</option><option value='Rupnagar' >Rupnagar</option><option value='Rura' >Rura</option><option value='Rurki' >Rurki</option><option value='Rurki Cantonment' >Rurki Cantonment</option><option value='Rurki Kasba' >Rurki Kasba</option><option value='Rusera' >Rusera</option><option value='Rustamnagar Sahaspur' >Rustamnagar Sahaspur</option><option value='Sabalgarh' >Sabalgarh</option><option value='Sabarkantha' >Sabarkantha</option><option value='Sabathu' >Sabathu</option><option value='Sabatwar' >Sabatwar</option><option value='Sabrum' >Sabrum</option><option value='Sadabad' >Sadabad</option><option value='Sadalgi' >Sadalgi</option><option value='Sadasivpet' >Sadasivpet</option><option value='Sadat' >Sadat</option><option value='Sadauri' >Sadauri</option><option value='Sadayankuppam' >Sadayankuppam</option><option value='Sadri' >Sadri</option><option value='Sadulshahar' >Sadulshahar</option><option value='Safidon' >Safidon</option><option value='Safipur' >Safipur</option><option value='Sagar' >Sagar</option><option value='Sagar Cantonment' >Sagar Cantonment</option><option value='Sagauli' >Sagauli</option><option value='Sagwara' >Sagwara</option><option value='Sahajadpur' >Sahajadpur</option><option value='Sahanpur' >Sahanpur</option><option value='Sahapur' >Sahapur</option><option value='Saharanpur' >Saharanpur</option><option value='Saharsa' >Saharsa</option><option value='Sahaspur' >Sahaspur</option><option value='Sahaswan' >Sahaswan</option><option value='Sahawar' >Sahawar</option><option value='Sahibabad' >Sahibabad</option><option value='Sahibganj' >Sahibganj</option><option value='Sahij' >Sahij</option><option value='Sahjanwa' >Sahjanwa</option><option value='Sahnewal' >Sahnewal</option><option value='Sahnidih' >Sahnidih</option><option value='Sahpau' >Sahpau</option><option value='Saidpur' >Saidpur</option><option value='Saiha' >Saiha</option><option value='Sailana' >Sailana</option><option value='Saint Thomas Mount' >Saint Thomas Mount</option><option value='Sainthal' >Sainthal</option><option value='Sainthia' >Sainthia</option><option value='Sairang' >Sairang</option><option value='Saitul' >Saitul</option><option value='Saiyadraja' >Saiyadraja</option><option value='Sakhanu' >Sakhanu</option><option value='Sakit' >Sakit</option><option value='Sakleshpur' >Sakleshpur</option><option value='Sakri' >Sakri</option><option value='Sakti' >Sakti</option><option value='Salakati' >Salakati</option><option value='Salangapalayam' >Salangapalayam</option><option value='Salap' >Salap</option><option value='Salarpur Khadar' >Salarpur Khadar</option><option value='Salaya' >Salaya</option><option value='Salcette' >Salcette</option><option value='Salem' >Salem</option><option value='Saligao' >Saligao</option><option value='Saligram' >Saligram</option><option value='Salimpur' >Salimpur</option><option value='Salon' >Salon</option><option value='Salumbar' >Salumbar</option><option value='Salur' >Salur</option><option value='Samalapuram' >Samalapuram</option><option value='Samalkha' >Samalkha</option><option value='Samalkot' >Samalkot</option><option value='Samana' >Samana</option><option value='Samastipur' >Samastipur</option><option value='Samathur' >Samathur</option><option value='Samba' >Samba</option><option value='Sambalpur' >Sambalpur</option><option value='Sambavar Vadagarai' >Sambavar Vadagarai</option><option value='Sambhal' >Sambhal</option><option value='Sambhar' >Sambhar</option><option value='Sambhawali' >Sambhawali</option><option value='Samdari' >Samdari</option><option value='Samdhan' >Samdhan</option><option value='Samrala' >Samrala</option><option value='Samthar' >Samthar</option><option value='Samurou' >Samurou</option><option value='Sanand' >Sanand</option><option value='Sanaur' >Sanaur</option><option value='Sanawad' >Sanawad</option><option value='Sanchi' >Sanchi</option><option value='Sanchor' >Sanchor</option><option value='Sancoale' >Sancoale</option><option value='Sandi' >Sandi</option><option value='Sandila' >Sandila</option><option value='Sandor' >Sandor</option><option value='Sandur' >Sandur</option><option value='Sangamner' >Sangamner</option><option value='Sangareddy' >Sangareddy</option><option value='Sangariya' >Sangariya</option><option value='Sangat' >Sangat</option><option value='Sangli' >Sangli</option><option value='Sangod' >Sangod</option><option value='Sangole' >Sangole</option><option value='Sangrur' >Sangrur</option><option value='Sanguem' >Sanguem</option><option value='Sanivarsante' >Sanivarsante</option><option value='Sankaramanallur' >Sankaramanallur</option><option value='Sankarankoil' >Sankarankoil</option><option value='Sankarapuram' >Sankarapuram</option><option value='Sankari' >Sankari</option><option value='Sankarnagar' >Sankarnagar</option><option value='Sankarpur' >Sankarpur</option><option value='Sankeshwar' >Sankeshwar</option><option value='Sankheda' >Sankheda</option><option value='Sankhol' >Sankhol</option><option value='Sankrail' >Sankrail</option><option value='Sanquelim' >Sanquelim</option><option value='Sansarpur' >Sansarpur</option><option value='Sant Kabir Nagar' >Sant Kabir Nagar</option><option value='Sant Ravidas Nagar' >Sant Ravidas Nagar</option><option value='Santokhgarh' >Santokhgarh</option><option value='Santoshpur' >Santoshpur</option><option value='Santrampur' >Santrampur</option><option value='Sanvordem' >Sanvordem</option><option value='Sanwer' >Sanwer</option><option value='Sao Jose' >de</option><option value='Saontaidih' >Saontaidih</option><option value='Sapatgram' >Sapatgram</option><option value='Sarahan' >Sarahan</option><option value='Sarai akil' >Sarai akil</option><option value='Sarai Mir' >Sarai Mir</option><option value='Saraidhela' >Saraidhela</option><option value='Saraikela' >Saraikela</option><option value='Saraipali' >Saraipali</option><option value='Sarajpur' >Sarajpur</option><option value='Saran' >Saran</option><option value='Sarangarh' >Sarangarh</option><option value='Sarangpur' >Sarangpur</option><option value='Sarapaka' >Sarapaka</option><option value='Sarauli' >Sarauli</option><option value='Saravanampatti' >Saravanampatti</option><option value='Sarcarsamakulam' >Sarcarsamakulam</option><option value='Sardarpur' >Sardarpur</option><option value='Sardarshahr' >Sardarshahr</option><option value='Sardhana' >Sardhana</option><option value='Sardulgarh' >Sardulgarh</option><option value='Sarenga' >Sarenga</option><option value='Sargur' >Sargur</option><option value='Saribujrang' >Saribujrang</option><option value='Sarigam INA' >Sarigam INA</option><option value='Sarila' >Sarila</option><option value='Sarjamda' >Sarjamda</option><option value='Sarka Ghat' >Sarka Ghat</option><option value='Sarni' >Sarni</option><option value='Sarpi' >Sarpi</option><option value='Sarsawan' >Sarsawan</option><option value='Sarthebari' >Sarthebari</option><option value='Sarupathar' >Sarupathar</option><option value='Sarupathar Bengali' >Sarupathar Bengali</option><option value='Sarwar' >Sarwar</option><option value='Sasaram' >Sasaram</option><option value='Sasauli' >Sasauli</option><option value='Sasni' >Sasni</option><option value='Sasti' >Sasti</option><option value='Sasvad' >Sasvad</option><option value='Satai' >Satai</option><option value='Satal Kheri' >Satal Kheri</option><option value='Satana' >Satana</option><option value='Satara' >Satara</option><option value='Sathiyavijayanagaram' >Sathiyavijayanagaram</option><option value='Sathupalle' >Sathupalle</option><option value='Sathuvachari' >Sathuvachari</option><option value='Sathyamangala' >Sathyamangala</option><option value='Sathyamangalam' >Sathyamangalam</option><option value='Satigachha' >Satigachha</option><option value='Satna' >Satna</option><option value='Satrikh' >Satrikh</option><option value='Sattankulam' >Sattankulam</option><option value='Sattari' >Sattari</option><option value='Sattenapalle' >Sattenapalle</option><option value='Sattur' >Sattur</option><option value='Satwas' >Satwas</option><option value='Saunda' >Saunda</option><option value='Saundatti Yellamma' >Saundatti Yellamma</option><option value='Saunkh' >Saunkh</option><option value='Saurikh' >Saurikh</option><option value='Sausar' >Sausar</option><option value='Savantvadi' >Savantvadi</option><option value='Savanur' >Savanur</option><option value='Savda' >Savda</option><option value='Savner' >Savner</option><option value='Sawai Madhopur' >Sawai Madhopur</option><option value='Sawari Jawharnagar' >Sawari Jawharnagar</option><option value='Sayalgudi' >Sayalgudi</option><option value='Sayan' >Sayan</option><option value='Sayapuram' >Sayapuram</option><option value='Sayla' >Sayla</option><option value='Secunderabad' >Secunderabad</option><option value='Sedam' >Sedam</option><option value='Seetharampuram' >Seetharampuram</option><option value='Sehore' >Sehore</option><option value='Seithur' >Seithur</option><option value='Sekmai Bazar' >Sekmai Bazar</option><option value='Selu' >Selu</option><option value='Semaria' >Semaria</option><option value='Sembakkam' >Sembakkam</option><option value='Semmipalayam' >Semmipalayam</option><option value='Senapati' >Senapati</option><option value='Senchoagaon' >Senchoagaon</option><option value='Sendhwa' >Sendhwa</option><option value='Sennirkuppam' >Sennirkuppam</option><option value='Senthamangalam' >Senthamangalam</option><option value='Sentharapatti' >Sentharapatti</option><option value='Senur' >Senur</option><option value='Seohara' >Seohara</option><option value='Seondha' >Seondha</option><option value='Seoni' >Seoni</option><option value='Seoni Malwa' >Seoni Malwa</option><option value='Seppa' >Seppa</option><option value='Seraikela and Kharsawan' >Seraikela and Kharsawan</option><option value='Serchhip' >Serchhip</option><option value='Serilungampalle' >Serilungampalle</option><option value='Serpur' >Serpur</option><option value='Serula' >Serula</option><option value='Sethia' >Sethia</option><option value='Sethiathoppu' >Sethiathoppu</option><option value='Sevilimedu' >Sevilimedu</option><option value='Sevugampatti' >Sevugampatti</option><option value='Sewai' >Sewai</option><option value='Sewal Khas' >Sewal Khas</option><option value='Sewan Kalan' >Sewan Kalan</option><option value='Sewarhi' >Sewarhi</option><option value='Shahabad A.C.C.' >Shahabad A.C.C.</option><option value='Shahada' >Shahada</option><option value='Shahdol' >Shahdol</option><option value='Shahganj' >Shahganj</option><option value='Shahgarh' >Shahgarh</option><option value='Shahi' >Shahi</option><option value='Shahjahanpur' >Shahjahanpur</option><option value='Shahjahanpur Cantonment' >Shahjahanpur Cantonment</option><option value='Shahkot' >Shahkot</option><option value='Shahwadi' >Shahwadi</option><option value='Shaikhpura' >Shaikhpura</option><option value='Shajapur' >Shajapur</option><option value='Shaktigarh' >Shaktigarh</option><option value='Shaktinagar' >Shaktinagar</option><option value='Sham Churasi' >Sham Churasi</option><option value='Shamgarh' >Shamgarh</option><option value='Shamli' >Shamli</option><option value='Shamsabad' >Shamsabad</option><option value='Shankarampet' >Shankarampet</option><option value='Shankargarh' >Shankargarh</option><option value='Shankhanagar' >Shankhanagar</option><option value='Shantipur' >Shantipur</option><option value='Shapar' >Shapar</option><option value='Shar' >Shar</option><option value='Shegaon' >Shegaon</option><option value='Sheikhpura' >Sheikhpura</option><option value='Shekhpura' >Shekhpura</option><option value='Shelar' >Shelar</option><option value='Shenbakkam' >Shenbakkam</option><option value='Shencottai' >Shencottai</option><option value='Shendurjana' >Shendurjana</option><option value='Shenkottai' >Shenkottai</option><option value='Sheoganj' >Sheoganj</option><option value='Sheohar' >Sheohar</option><option value='Sheopur' >Sheopur</option><option value='Shergarh' >Shergarh</option><option value='Sherghati' >Sherghati</option><option value='Sherkot' >Sherkot</option><option value='Shiggaon' >Shiggaon</option><option value='Shikohabad' >Shikohabad</option><option value='Shillong' >Shillong</option><option value='Shillong Cantonment' >Shillong Cantonment</option><option value='Shimla' >Shimla</option><option value='Shimoga' >Shimoga</option><option value='Shirdi' >Shirdi</option><option value='Shirgaon' >Shirgaon</option><option value='Shirhatti' >Shirhatti</option><option value='Shirpur' >Shirpur</option><option value='Shirur' >Shirur</option><option value='Shirwal' >Shirwal</option><option value='Shisgarh' >Shisgarh</option><option value='Shivatkar' >Shivatkar</option><option value='Shivdaspur' >Shivdaspur</option><option value='Shivhar' >Shivhar</option><option value='Shivli' >Shivli</option><option value='Shivpuri' >Shivpuri</option><option value='Shivrinarayan' >Shivrinarayan</option><option value='Shohratgarh' >Shohratgarh</option><option value='Sholavandan' >Sholavandan</option><option value='Sholinganallur' >Sholinganallur</option><option value='Sholingur' >Sholingur</option><option value='Sholur' >Sholur</option><option value='Shoranur' >Shoranur</option><option value='Shorapur' >Shorapur</option><option value='Shravanabelagola' >Shravanabelagola</option><option value='Shravasti' >Shravasti</option><option value='Shrigonda' >Shrigonda</option><option value='Shrirampur Rural' >Shrirampur Rural</option><option value='Shrirangapattana' >Shrirangapattana</option><option value='Shujalpur' >Shujalpur</option><option value='Shupiyan' >Shupiyan</option><option value='Sibsagar' >Sibsagar</option><option value='Siddapur' >Siddapur</option><option value='Siddhanur' >Siddhanur</option><option value='Siddhapur' >Siddhapur</option><option value='Siddharthnagar' >Siddharthnagar</option><option value='Siddipet' >Siddipet</option><option value='Sidhauli' >Sidhauli</option><option value='Sidhi' >Sidhi</option><option value='Sidhpur' >Sidhpur</option><option value='Sidhpura' >Sidhpura</option><option value='Sidlaghatta' >Sidlaghatta</option><option value='Siduli' >Siduli</option><option value='Sihor' >Sihor</option><option value='Sihora' >Sihora</option><option value='Sijhua' >Sijhua</option><option value='Sijua' >Sijua</option><option value='Sika' >Sika</option><option value='Sikandarabad' >Sikandarabad</option><option value='Sikandarpur' >Sikandarpur</option><option value='Sikandra' >Sikandra</option><option value='Sikandra Rao' >Sikandra Rao</option><option value='Sikar' >Sikar</option><option value='Sikhong Sekmai' >Sikhong Sekmai</option><option value='Sikkarayapuram' >Sikkarayapuram</option><option value='Sikkim' >Sikkim</option><option value='Silao' >Silao</option><option value='Silapathar' >Silapathar</option><option value='Silchar' >Silchar</option><option value='Silchar Part' >X</option><option value='Siliguri' >Siliguri</option><option value='Sillewada' >Sillewada</option><option value='Sillod' >Sillod</option><option value='Silvassa' >Silvassa</option><option value='Simdega' >Simdega</option><option value='Simga' >Simga</option><option value='Simla' >Simla</option><option value='Sinapali' >Sinapali</option><option value='Sindari' >Sindari</option><option value='Sindgi' >Sindgi</option><option value='Sindhnur' >Sindhnur</option><option value='Sindhudurg' >Sindhudurg</option><option value='Sindi' >Sindi</option><option value='Sindi Turf Hindnagar' >Sindi Turf Hindnagar</option><option value='Sindkhed Raja' >Sindkhed Raja</option><option value='Sinduria' >Sinduria</option><option value='Singahi Bhiraura' >Singahi Bhiraura</option><option value='Singampuneri' >Singampuneri</option><option value='Singanallur' >Singanallur</option><option value='Singaperumalkoil' >Singaperumalkoil</option><option value='Singapur' >Singapur</option><option value='Singarayakonda' >Singarayakonda</option><option value='Singarva' >Singarva</option><option value='Singnapur' >Singnapur</option><option value='Singolo' >Singolo</option><option value='Singrauli' >Singrauli</option><option value='Singtam' >Singtam</option><option value='Singur' >Singur</option><option value='Sinhasa' >Sinhasa</option><option value='Sini' >Sini</option><option value='Sinnar' >Sinnar</option><option value='Sinor' >Sinor</option><option value='Sinquerim' >Sinquerim</option><option value='Siolim' >Siolim</option><option value='Sira' >Sira</option><option value='Sirakoppa' >Sirakoppa</option><option value='Sirapalli' >Sirapalli</option><option value='Sirathu' >Sirathu</option><option value='Sircilla' >Sircilla</option><option value='Sirgiti' >Sirgiti</option><option value='Sirgora' >Sirgora</option><option value='Sirhind' >Sirhind</option><option value='Sirka' >Sirka</option><option value='Sirkali' >Sirkali</option><option value='Sirmaur' >Sirmaur</option><option value='Sirohi' >Sirohi</option><option value='Sironj' >Sironj</option><option value='Sirpur' >Sirpur</option><option value='Sirsa' >Sirsa</option><option value='Sirsaganj' >Sirsaganj</option><option value='Sirsha' >Sirsha</option><option value='Sirsilla' >Sirsilla</option><option value='Sirugamani' >Sirugamani</option><option value='Siruguppa' >Siruguppa</option><option value='Sirumugai' >Sirumugai</option><option value='Sirur' >Sirur</option><option value='Sisauli' >Sisauli</option><option value='Siswa Bazar' >Siswa Bazar</option><option value='Sitamarhi' >Sitamarhi</option><option value='Sitamau' >Sitamau</option><option value='Sitapur' >Sitapur</option><option value='Sitarganj' >Sitarganj</option><option value='Sitasawangi' >Sitasawangi</option><option value='Sithayankottai' >Sithayankottai</option><option value='Sithurajapuram' >Sithurajapuram</option><option value='Siuliban' >Siuliban</option><option value='Siuri' >Siuri</option><option value='Sivaganga' >Sivaganga</option><option value='Sivagangai' >Sivagangai</option><option value='Sivagiri' >Sivagiri</option><option value='Sivakasi' >Sivakasi</option><option value='Sivanthipuram' >Sivanthipuram</option><option value='Sivur' >Sivur</option><option value='Siwan' >Siwan</option><option value='Siwana' >Siwana</option><option value='Siwani' >Siwani</option><option value='Siyana' >Siyana</option><option value='Sobhaganj' >Sobhaganj</option><option value='Sodpur' >Sodpur</option><option value='Sogariya' >Sogariya</option><option value='Sohagpur' >Sohagpur</option><option value='Sohna' >Sohna</option><option value='Sojat' >Sojat</option><option value='Sojat Road' >Sojat Road</option><option value='Sojitra' >Sojitra</option><option value='Sola' >Sola</option><option value='Solan' >Solan</option><option value='Solapur' >Solapur</option><option value='Solon' >Solon</option><option value='Som' >Som</option><option value='Someshwar' >Someshwar</option><option value='Sompeta' >Sompeta</option><option value='Somvarpet' >Somvarpet</option><option value='Sonai' >Sonai</option><option value='Sonamukhi' >Sonamukhi</option><option value='Sonamura' >Sonamura</option><option value='Sonari' >Sonari</option><option value='Sonatikiri' >Sonatikiri</option><option value='Sonbhadra' >Sonbhadra</option><option value='Sonegaon' >Sonegaon</option><option value='Sonepat' >Sonepat</option><option value='Songadh' >Songadh</option><option value='Sonipat' >Sonipat</option><option value='Sonitpur' >Sonitpur</option><option value='Sonkatch' >Sonkatch</option><option value='Sopur' >Sopur</option><option value='Sorab' >Sorab</option><option value='Sorada' >Sorada</option><option value='Soranjeri' >Soranjeri</option><option value='Sorbhog' >Sorbhog</option><option value='Soro' >Soro</option><option value='Soron' >Soron</option><option value='South 24 Parganas' >South 24 Parganas</option><option value='South Andaman' >South Andaman</option><option value='South Delhi' >South Delhi</option><option value='South Garo Hills' >South Garo Hills</option><option value='South Goa' >South Goa</option><option value='South Kannanur' >South Kannanur</option><option value='South Kodikulam' >South Kodikulam</option><option value='South Sikkim' >South Sikkim</option><option value='South Tripura' >South Tripura</option><option value='South West Delhi' >South West Delhi</option><option value='Soyagaon' >Soyagaon</option><option value='Soyatkalan' >Soyatkalan</option><option value='Sri Hargobindpur' >Sri Hargobindpur</option><option value='Sri Madhopur' >Sri Madhopur</option><option value='Sriganganagar' >Sriganganagar</option><option value='Sriharikota' >Sriharikota</option><option value='Srikakulam' >Srikakulam</option><option value='Srikalahasti' >Srikalahasti</option><option value='Srikantabati' >Srikantabati</option><option value='Srimushnam' >Srimushnam</option><option value='Srinagar' >Srinagar</option><option value='Sringeri' >Sringeri</option><option value='Srinivaspur' >Srinivaspur</option><option value='Sriperumpudur' >Sriperumpudur</option><option value='Sriramapuram' >Sriramapuram</option><option value='Sriramnagar' >Sriramnagar</option><option value='Srirampur' >Srirampur</option><option value='Sriramsagar' >Sriramsagar</option><option value='Srirangam' >Srirangam</option><option value='Srisailam' >Srisailam</option><option value='Srisailamgudem Devasthanam' >Srisailamgudem Devasthanam</option><option value='Srivaikuntam' >Srivaikuntam</option><option value='Srivardhan' >Srivardhan</option><option value='Srivilliputtur' >Srivilliputtur</option><option value='Sualkuchi' >Sualkuchi</option><option value='Suar' >Suar</option><option value='Subarnapur' >Subarnapur</option><option value='Suchindram' >Suchindram</option><option value='Sugnu' >Sugnu</option><option value='Suhagi' >Suhagi</option><option value='Sujangarh' >Sujangarh</option><option value='Sujanpur' >Sujanpur</option><option value='Sukdal' >Sukdal</option><option value='Suket' >Suket</option><option value='Sukhmalpur Nizamabad' >Sukhmalpur Nizamabad</option><option value='Sukhrali' >Sukhrali</option><option value='Suliswaranpatti' >Suliswaranpatti</option><option value='Sultanganj' >Sultanganj</option><option value='Sultanpur' >Sultanpur</option><option value='Sultanpur Lodhi' >Sultanpur Lodhi</option><option value='Sultans Battery' >Sultans Battery</option><option value='Sulthan Bathery' >Sulthan Bathery</option><option value='Sulur' >Sulur</option><option value='Sulurpeta' >Sulurpeta</option><option value='Sulya' >Sulya</option><option value='Sumbal' >Sumbal</option><option value='Sunabeda' >Sunabeda</option><option value='Sunam' >Sunam</option><option value='Sundarapandiam' >Sundarapandiam</option><option value='Sundarapandiapuram' >Sundarapandiapuram</option><option value='Sundargarh' >Sundargarh</option><option value='Sundarnagar' >Sundarnagar</option><option value='Sunderbani' >Sunderbani</option><option value='Sundernagar' >Sundernagar</option><option value='Sunel' >Sunel</option><option value='Suntikopa' >Suntikopa</option><option value='Supaul' >Supaul</option><option value='Suraj Karadi' >Suraj Karadi</option><option value='Surajgarh' >Surajgarh</option><option value='Surampatti' >Surampatti</option><option value='Surandai' >Surandai</option><option value='Surat' >Surat</option><option value='Suratgarh' >Suratgarh</option><option value='Surendranagar' >Surendranagar</option><option value='Surgana' >Surgana</option><option value='Surguja' >Surguja</option><option value='Suriapet' >Suriapet</option><option value='Suriyampalayam' >Suriyampalayam</option><option value='Suriyawan' >Suriyawan</option><option value='Surubera' >Surubera</option><option value='Suryapet' >Suryapet</option><option value='Suryaraopet' >Suryaraopet</option><option value='Susner' >Susner</option><option value='Suthaliya' >Suthaliya</option><option value='Swamibagh' >Swamibagh</option><option value='Swamimalai' >Swamimalai</option><option value='Swaroopganj' >Swaroopganj</option><option value='Tadepalle' >Tadepalle</option><option value='Tadepalligudem' >Tadepalligudem</option><option value='Tadpatri' >Tadpatri</option><option value='Taherpur' >Taherpur</option><option value='Tajpur' >Tajpur</option><option value='Takhatgarh' >Takhatgarh</option><option value='Takhatpur' >Takhatpur</option><option value='Taki' >Taki</option><option value='Tal' >Tal</option><option value='Talai' >Talai</option><option value='Talaja' >Talaja</option><option value='Talala' >Talala</option><option value='Talbahat' >Talbahat</option><option value='Talbandha' >Talbandha</option><option value='Talcher' >Talcher</option><option value='Talcher Thermal Power Station' >Talcher Thermal Power Station</option><option value='Talegaon Dabhade' >Talegaon Dabhade</option><option value='Taleigao' >Taleigao</option><option value='Talen' >Talen</option><option value='Talgram' >Talgram</option><option value='Talikota' >Talikota</option><option value='Talipparamba' >Talipparamba</option><option value='Tallapalle' >Tallapalle</option><option value='Talod' >Talod</option><option value='Taloda' >Taloda</option><option value='Taloja' >Taloja</option><option value='Talwade' >Talwade</option><option value='Talwandi Bhai' >Talwandi Bhai</option><option value='Tambaram' >Tambaram</option><option value='Tambaur' >Tambaur</option><option value='Tamenglong' >Tamenglong</option><option value='Tamluk' >Tamluk</option><option value='Tanakpur' >Tanakpur</option><option value='Tanda' >Tanda</option><option value='Tandur' >Tandur</option><option value='Tangla' >Tangla</option><option value='Tankara' >Tankara</option><option value='Tanuku' >Tanuku</option><option value='Taoru' >Taoru</option><option value='Tappa' >Tappa</option><option value='Tarabha' >Tarabha</option><option value='Tarakeswar' >Tarakeswar</option><option value='Taramangalam' >Taramangalam</option><option value='Tarana' >Tarana</option><option value='Taranagar' >Taranagar</option><option value='Taraori' >Taraori</option><option value='Tarapur' >Tarapur</option><option value='Taricharkalan' >Taricharkalan</option><option value='Tarikera' >Tarikera</option><option value='Tarn Taran' >Tarn Taran</option><option value='Tarsali' >Tarsali</option><option value='Tasgaon' >Tasgaon</option><option value='Tatarpur Lallu' >Tatarpur Lallu</option><option value='Tathavade' >Tathavade</option><option value='Tati' >Tati</option><option value='Tattayyangarpettai' >Tattayyangarpettai</option><option value='Tauru' >Tauru</option><option value='Tawang' >Tawang</option><option value='Tayilupatti' >Tayilupatti</option><option value='Teghra' >Teghra</option><option value='Tehri' >Tehri</option><option value='Tehri Garhwal' >Tehri Garhwal</option><option value='Tekadi' >Tekadi</option><option value='Tekanpur' >Tekanpur</option><option value='Tekari' >Tekari</option><option value='Tekkalakota' >Tekkalakota</option><option value='Tekkali' >Tekkali</option><option value='Telgaon' >Telgaon</option><option value='Telhara' >Telhara</option><option value='Teliamura' >Teliamura</option><option value='Tenali' >Tenali</option><option value='Tendukheda' >Tendukheda</option><option value='Tenkasi' >Tenkasi</option><option value='Tensa' >Tensa</option><option value='Tentulberia' >Tentulberia</option><option value='Tentulkuli' >Tentulkuli</option><option value='Tenudam' >Tenudam</option><option value='Teonthar' >Teonthar</option><option value='Terdal' >Terdal</option><option value='Tetribazar' >Tetribazar</option><option value='Tezpur' >Tezpur</option><option value='Tezu' >Tezu</option><option value='Thadikombu' >Thadikombu</option><option value='Thaikkad' >Thaikkad</option><option value='Thakkolam' >Thakkolam</option><option value='Thakurdwara' >Thakurdwara</option><option value='Thakurganj' >Thakurganj</option><option value='Thalainayar' >Thalainayar</option><option value='Thalakudi' >Thalakudi</option><option value='Thalassery' >Thalassery</option><option value='Thamaraikulam' >Thamaraikulam</option><option value='Thammampatti' >Thammampatti</option><option value='Thana Bhawan' >Thana Bhawan</option><option value='Thanamandi' >Thanamandi</option><option value='Thandia' >Thandia</option><option value='Thane' >Thane</option><option value='Thanesar' >Thanesar</option><option value='Thangadh' >Thangadh</option><option value='Thanjavur' >Thanjavur</option><option value='Thannirmukkam' >Thannirmukkam</option><option value='Thanthoni' >Thanthoni</option><option value='Tharad' >Tharad</option><option value='Tharangambadi' >Tharangambadi</option><option value='Thasra' >Thasra</option><option value='The Dangs' >The Dangs</option><option value='The Nilgiris' >The Nilgiris</option><option value='Thedavur' >Thedavur</option><option value='Thenambakkam' >Thenambakkam</option><option value='Thengampudur' >Thengampudur</option><option value='Theni' >Theni</option><option value='Theni Allinagaram' >Theni Allinagaram</option><option value='Thenkarai' >Thenkarai</option><option value='Thenthamaraikulam' >Thenthamaraikulam</option><option value='Thenthiruperai' >Thenthiruperai</option><option value='Thenzawl' >Thenzawl</option><option value='Theog' >Theog</option><option value='Thermal Power Project' >Thermal Power Project</option><option value='Thesur' >Thesur</option><option value='Thevaram' >Thevaram</option><option value='Thevur' >Thevur</option><option value='Theyyalingal' >Theyyalingal</option><option value='Thiagadurgam' >Thiagadurgam</option><option value='Thiagarajar Colony' >Thiagarajar Colony</option><option value='Thingalnagar' >Thingalnagar</option><option value='Thiriya Nizamat Khan' >Thiriya Nizamat Khan</option><option value='Thiruchirapalli' >Thiruchirapalli</option><option value='Thirukarungudi' >Thirukarungudi</option><option value='Thirukazhukundram' >Thirukazhukundram</option><option value='Thirumalayampalayam' >Thirumalayampalayam</option><option value='Thirumazhisai' >Thirumazhisai</option><option value='Thirunagar' >Thirunagar</option><option value='Thirunageswaram' >Thirunageswaram</option><option value='Thirunindravur' >Thirunindravur</option><option value='Thirunirmalai' >Thirunirmalai</option><option value='Thiruparankundram' >Thiruparankundram</option><option value='Thiruparappu' >Thiruparappu</option><option value='Thiruporur' >Thiruporur</option><option value='Thiruppanandal' >Thiruppanandal</option><option value='Thirupuvanam' >Thirupuvanam</option><option value='Thiruthangal' >Thiruthangal</option><option value='Thiruthuraipundi' >Thiruthuraipundi</option><option value='Thiruvaivaru' >Thiruvaivaru</option><option value='Thiruvalam' >Thiruvalam</option><option value='Thiruvalla' >Thiruvalla</option><option value='Thiruvallur' >Thiruvallur</option><option value='Thiruvananthapuram' >Thiruvananthapuram</option><option value='Thiruvankulam' >Thiruvankulam</option><option value='Thiruvarur' >Thiruvarur</option><option value='Thiruvattaru' >Thiruvattaru</option><option value='Thiruvenkatam' >Thiruvenkatam</option><option value='Thiruvennainallur' >Thiruvennainallur</option><option value='Thiruvithankodu' >Thiruvithankodu</option><option value='Thisayanvilai' >Thisayanvilai</option><option value='Thittacheri' >Thittacheri</option><option value='Thodupuzha' >Thodupuzha</option><option value='Thokur' >Thokur</option><option value='Thondamuthur' >Thondamuthur</option><option value='Thongkhong Laxmi Bazar' >Thongkhong Laxmi Bazar</option><option value='Thoothukudi' >Thoothukudi</option><option value='Thorapadi' >Thorapadi</option><option value='Thottada' >Thottada</option><option value='Thottipalayam' >Thottipalayam</option><option value='Thottiyam' >Thottiyam</option><option value='Thoubal' >Thoubal</option><option value='Three STR' >Three STR</option><option value='Thrippunithura' >Thrippunithura</option><option value='Thrissur' >Thrissur</option><option value='Thudiyalur' >Thudiyalur</option><option value='Thumbe' >Thumbe</option><option value='Thuthipattu' >Thuthipattu</option><option value='Thuvakudi' >Thuvakudi</option><option value='Tigalapahad' >Tigalapahad</option><option value='Tihu' >Tihu</option><option value='Tijara' >Tijara</option><option value='Tikaitnagar' >Tikaitnagar</option><option value='Tikamgarh' >Tikamgarh</option><option value='Tikri' >Tikri</option><option value='Tildanewra' >Tildanewra</option><option value='Tilhar' >Tilhar</option><option value='Tilpat' >Tilpat</option><option value='Timarni' >Timarni</option><option value='Timiri' >Timiri</option><option value='Tindivanam' >Tindivanam</option><option value='Tindwari' >Tindwari</option><option value='Tinnanur' >Tinnanur</option><option value='Tinsukia' >Tinsukia</option><option value='Tiptur' >Tiptur</option><option value='Tira Sujanpur' >Tira Sujanpur</option><option value='Tirap' >Tirap</option><option value='Tirira' >Tirira</option><option value='Tirodi' >Tirodi</option><option value='Tirthahalli' >Tirthahalli</option><option value='Tiruchanur' >Tiruchanur</option><option value='Tiruchchendur' >Tiruchchendur</option><option value='Tiruchengode' >Tiruchengode</option><option value='Tiruchirappalli' >Tiruchirappalli</option><option value='Tirukkalukkundram' >Tirukkalukkundram</option><option value='Tirukkattuppalli' >Tirukkattuppalli</option><option value='Tirukkoyilur' >Tirukkoyilur</option><option value='Tirumakudal Narsipur' >Tirumakudal Narsipur</option><option value='Tirumala' >Tirumala</option><option value='Tirumangalam' >Tirumangalam</option><option value='Tirumullaivasal' >Tirumullaivasal</option><option value='Tirumuruganpundi' >Tirumuruganpundi</option><option value='Tirunageswaram' >Tirunageswaram</option><option value='Tirunelveli' >Tirunelveli</option><option value='Tirupathur' >Tirupathur</option><option value='Tirupati' >Tirupati</option><option value='Tirupattur' >Tirupattur</option><option value='Tiruppur' >Tiruppur</option><option value='Tiruppuvanam' >Tiruppuvanam</option><option value='Tirupur' >Tirupur</option><option value='Tirur' >Tirur</option><option value='Tirusulam' >Tirusulam</option><option value='Tiruttani' >Tiruttani</option><option value='Tiruvallur' >Tiruvallur</option><option value='Tiruvannamalai' >Tiruvannamalai</option><option value='Tiruverambur' >Tiruverambur</option><option value='Tiruverkadu' >Tiruverkadu</option><option value='Tiruvethipuram' >Tiruvethipuram</option><option value='Tiruvidaimarudur' >Tiruvidaimarudur</option><option value='Tiruvottiyur' >Tiruvottiyur</option><option value='Tirvuru' >Tirvuru</option><option value='Tirwaganj' >Tirwaganj</option><option value='Tisra' >Tisra</option><option value='Titabor' >Titabor</option><option value='Titagarh' >Titagarh</option><option value='Titlagarh' >Titlagarh</option><option value='Titron' >Titron</option><option value='Tittakudi' >Tittakudi</option><option value='Tivim' >Tivim</option><option value='Tlabung' >Tlabung</option><option value='TNPL Pugalur' >TNPL Pugalur</option><option value='Toda Bhim' >Toda Bhim</option><option value='Toda Raisingh' >Toda Raisingh</option><option value='Todra' >Todra</option><option value='Tohana' >Tohana</option><option value='Tondi' >Tondi</option><option value='Tonk' >Tonk</option><option value='Tonse' >Tonse</option><option value='Topa' >Topa</option><option value='Topchanchi' >Topchanchi</option><option value='Torban' >Torban</option><option value='Tori Fatehpur' >Tori Fatehpur</option><option value='Tosham' >Tosham</option><option value='Totaladoh' >Totaladoh</option><option value='Tral' >Tral</option><option value='Trimbak' >Trimbak</option><option value='Trimulgherry' >Trimulgherry</option><option value='Tuensang' >Tuensang</option><option value='Tufanganj' >Tufanganj</option><option value='Tuljapur' >Tuljapur</option><option value='Tulsipur' >Tulsipur</option><option value='Tumkur' >Tumkur</option><option value='Tumsar' >Tumsar</option><option value='Tundla' >Tundla</option><option value='Tundla Kham' >Tundla Kham</option><option value='Tundla Railway Colony' >Tundla Railway Colony</option><option value='Tuni' >Tuni</option><option value='Tura' >Tura</option><option value='Turaiyur' >Turaiyur</option><option value='Turangi' >Turangi</option><option value='Turuvekere' >Turuvekere</option><option value='Tuticorin' >Tuticorin</option><option value='Uchana' >Uchana</option><option value='Uchgaon' >Uchgaon</option><option value='Udagamandalam' >Udagamandalam</option><option value='Udagamandalam Valley' >Udagamandalam Valley</option><option value='Udaipur' >Udaipur</option><option value='Udaipura' >Udaipura</option><option value='Udala' >Udala</option><option value='Udalguri' >Udalguri</option><option value='Udankudi' >Udankudi</option><option value='Udayagiri' >Udayagiri</option><option value='Udayarpalayam' >Udayarpalayam</option><option value='Udgir' >Udgir</option><option value='Udham Singh Nagar' >Udham Singh Nagar</option><option value='Udhampur' >Udhampur</option><option value='Udma' >Udma</option><option value='Udpura' >Udpura</option><option value='Udumalaipettai' >Udumalaipettai</option><option value='Udumalpet' >Udumalpet</option><option value='Udupi' >Udupi</option><option value='Udyognagar' >Udyognagar</option><option value='Ugu' >Ugu</option><option value='Ujhani' >Ujhani</option><option value='Ujhari' >Ujhari</option><option value='Ujjain' >Ujjain</option><option value='Ukai' >Ukai</option><option value='Ukhra' >Ukhra</option><option value='Ukhrul' >Ukhrul</option><option value='Ukkayapalli' >Ukkayapalli</option><option value='Ukkunagaram' >Ukkunagaram</option><option value='Uklana Mandi' >Uklana Mandi</option><option value='Ukwa' >Ukwa</option><option value='Ula' >Ula</option><option value='Ulhasnagar' >Ulhasnagar</option><option value='Ullal' >Ullal</option><option value='Ullur' >Ullur</option><option value='Ulubaria' >Ulubaria</option><option value='Ulundurpettai' >Ulundurpettai</option><option value='Umarga' >Umarga</option><option value='Umaria' >Umaria</option><option value='Umarkhed' >Umarkhed</option><option value='Umarkot' >Umarkot</option><option value='Umarsara' >Umarsara</option><option value='Umbar Pada Nandade' >Umbar Pada Nandade</option><option value='Umbergaon' >Umbergaon</option><option value='Umbergaon INA' >Umbergaon INA</option><option value='Umrala' >Umrala</option><option value='Umrangso' >Umrangso</option><option value='Umred' >Umred</option><option value='Umreth' >Umreth</option><option value='Umri' >Umri</option><option value='Umri Kalan' >Umri Kalan</option><option value='Umri Pragane Balapur' >Umri Pragane Balapur</option><option value='Una' >Una</option><option value='Uncha Siwana' >Uncha Siwana</option><option value='Unchahar' >Unchahar</option><option value='Unchahara' >Unchahara</option><option value='Unhel' >Unhel</option><option value='Uniara' >Uniara</option><option value='Unjalaur' >Unjalaur</option><option value='Unjha' >Unjha</option><option value='Unnamalaikadai' >Unnamalaikadai</option><option value='Unnao' >Unnao</option><option value='Upleta' >Upleta</option><option value='Uppal Kalan' >Uppal Kalan</option><option value='Upper Sileru' >Upper Sileru</option><option value='Upper Subansiri' >Upper Subansiri</option><option value='Upper Tadong' >Upper Tadong</option><option value='Uppidamangalam' >Uppidamangalam</option><option value='Uppiliapuram' >Uppiliapuram</option><option value='Urachikkottai' >Urachikkottai</option><option value='Uran' >Uran</option><option value='Uran Islampur' >Uran Islampur</option><option value='Urapakkam' >Urapakkam</option><option value='Uravakonda' >Uravakonda</option><option value='Uri' >Uri</option><option value='Urla' >Urla</option><option value='Urmar Tanda' >Urmar Tanda</option><option value='Usaihat' >Usaihat</option><option value='Usawan' >Usawan</option><option value='Usilampatti' >Usilampatti</option><option value='Utekhol' >Utekhol</option><option value='Uthangarai' >Uthangarai</option><option value='Uthayendram' >Uthayendram</option><option value='Uthiramerur' >Uthiramerur</option><option value='Uthukkottai' >Uthukkottai</option><option value='Utran' >Utran</option><option value='Utraula' >Utraula</option><option value='Uttamapalaiyam' >Uttamapalaiyam</option><option value='Uttar Dinajpur' >Uttar Dinajpur</option><option value='Uttar Durgapur' >Uttar Durgapur</option><option value='Uttar Goara' >Uttar Goara</option><option value='Uttar Kalas' >Uttar Kalas</option><option value='Uttar Kamakhyaguri' >Uttar Kamakhyaguri</option><option value='Uttar Krishnapur Part' >I</option><option value='Uttar Latabari' >Uttar Latabari</option><option value='Uttar Mahammadpur' >Uttar Mahammadpur</option><option value='Uttar Pirpur' >Uttar Pirpur</option><option value='Uttar Raypur' >Uttar Raypur</option><option value='Uttara Kannada' >Uttara Kannada</option><option value='Uttarahalli' >Uttarahalli</option><option value='Uttarkashi' >Uttarkashi</option><option value='Uttarpara' >Kotrung</option><option value='Uttarsanda' >Uttarsanda</option><option value='Uttukkuli' >Uttukkuli</option><option value='V.U. Nagar' >V.U. Nagar</option><option value='V.V. Nagar' >V.V. Nagar</option><option value='Vada' >Vada</option><option value='Vadakara' >Vadakara</option><option value='Vadakarai Kizhpadugai' >Vadakarai Kizhpadugai</option><option value='Vadakkanandal' >Vadakkanandal</option><option value='Vadakku Valliyur' >Vadakku Valliyur</option><option value='Vadalur' >Vadalur</option><option value='Vadamadurai' >Vadamadurai</option><option value='Vadavalli' >Vadavalli</option><option value='Vadgaon' >Vadgaon</option><option value='Vadgaon Kasba' >Vadgaon Kasba</option><option value='Vadia' >Vadia</option><option value='Vadipatti' >Vadipatti</option><option value='Vadla' >Vadla</option><option value='Vadlapudi' >Vadlapudi</option><option value='Vadnagar' >Vadnagar</option><option value='Vadodara' >Vadodara</option><option value='Vadugapatti' >Vadugapatti</option><option value='Vaghodia INA' >Vaghodia INA</option><option value='Vaijapur' >Vaijapur</option><option value='Vaikam' >Vaikam</option><option value='Vairengte' >Vairengte</option><option value='Vaishali' >Vaishali</option><option value='Vaithiswarankoil' >Vaithiswarankoil</option><option value='Valangaiman' >Valangaiman</option><option value='Valapattam' >Valapattam</option><option value='Valasaravakkam' >Valasaravakkam</option><option value='Valavanur' >Valavanur</option><option value='Valbhipur' >Valbhipur</option><option value='Vallabh Vidyanagar' >Vallabh Vidyanagar</option><option value='Vallachira' >Vallachira</option><option value='Vallam' >Vallam</option><option value='Valparai' >Valparai</option><option value='Valpoi' >Valpoi</option><option value='Valsad' >Valsad</option><option value='Valsad INA' >Valsad INA</option><option value='Valvaithankoshtam' >Valvaithankoshtam</option><option value='Vanasthali' >Vanasthali</option><option value='Vanavasi' >Vanavasi</option><option value='Vandalur' >Vandalur</option><option value='Vandavasi' >Vandavasi</option><option value='Vandiyur' >Vandiyur</option><option value='Vaniputhur' >Vaniputhur</option><option value='Vaniyambadi' >Vaniyambadi</option><option value='Vanthali' >Vanthali</option><option value='Vanvadi' >Vanvadi</option><option value='Vaparala' >Vaparala</option><option value='Vapi' >Vapi</option><option value='Vapi INA' >Vapi INA</option><option value='Varadarajanpettai' >Varadarajanpettai</option><option value='Varadharajapuram' >Varadharajapuram</option><option value='Varam' >Varam</option><option value='Varanasi' >Varanasi</option><option value='Varanasi Cantonment' >Varanasi Cantonment</option><option value='Varangaon' >Varangaon</option><option value='Varappuzha' >Varappuzha</option><option value='Varca' >Varca</option><option value='Varkala' >Varkala</option><option value='Vartej' >Vartej</option><option value='Vasad' >Vasad</option><option value='Vasai' >Vasai</option><option value='Vasantnagar' >Vasantnagar</option><option value='Vasco' >Vasco</option><option value='Vashind' >Vashind</option><option value='Vasna Borsad INA' >Vasna Borsad INA</option><option value='Vaso' >Vaso</option><option value='Vasudevanallur' >Vasudevanallur</option><option value='Vathirairuppu' >Vathirairuppu</option><option value='Vattalkundu' >Vattalkundu</option><option value='Vayalar' >Vayalar</option><option value='Vazhakkala' >Vazhakkala</option><option value='Vazhapadi' >Vazhapadi</option><option value='Vedapatti' >Vedapatti</option><option value='Vedaranniyam' >Vedaranniyam</option><option value='Vedasandur' >Vedasandur</option><option value='Vehicle Factory Jabalpur' >Vehicle Factory Jabalpur</option><option value='Velampalaiyam' >Velampalaiyam</option><option value='Velankanni' >Velankanni</option><option value='Vellakinar' >Vellakinar</option><option value='Vellakoil' >Vellakoil</option><option value='Vellalapatti' >Vellalapatti</option><option value='Vellalur' >Vellalur</option><option value='Vellanur' >Vellanur</option><option value='Vellimalai' >Vellimalai</option><option value='Vellore' >Vellore</option><option value='Vellottamparappu' >Vellottamparappu</option><option value='Velluru' >Velluru</option><option value='Vemalwada' >Vemalwada</option><option value='Vemulawada' >Vemulawada</option><option value='Vengampudur' >Vengampudur</option><option value='Vengathur' >Vengathur</option><option value='Vengavasal' >Vengavasal</option><option value='Venghatur' >Venghatur</option><option value='Vengurla' >Vengurla</option><option value='Venkarai' >Venkarai</option><option value='Venkatagiri' >Venkatagiri</option><option value='Venkatapura' >Venkatapura</option><option value='Venkatapuram' >Venkatapuram</option><option value='Venmanad' >Venmanad</option><option value='Vennanthur' >Vennanthur</option><option value='Vepagunta' >Vepagunta</option><option value='Veppathur' >Veppathur</option><option value='Veraval' >Veraval</option><option value='Verkilambi' >Verkilambi</option><option value='Verna' >Verna</option><option value='Vetapalem' >Vetapalem</option><option value='Vettaikaranpudur' >Vettaikaranpudur</option><option value='Vettavalam' >Vettavalam</option><option value='Vidisha' >Vidisha</option><option value='Vidyanagar' >Vidyanagar</option><option value='Vidyavihar' >Vidyavihar</option><option value='Vijaigarh' >Vijaigarh</option><option value='Vijainagar' >Vijainagar</option><option value='Vijalpor' >Vijalpor</option><option value='Vijapur' >Vijapur</option><option value='Vijayapura' >Vijayapura</option><option value='Vijayapuri South' >Vijayapuri South</option><option value='Vijayawada' >Vijayawada</option><option value='Vijaypur' >Vijaypur</option><option value='Vijayraghavgarh' >Vijayraghavgarh</option><option value='Vikarabad' >Vikarabad</option><option value='Vikasnagar' >Vikasnagar</option><option value='Vikramasingapuram' >Vikramasingapuram</option><option value='Vikrampur' >Vikrampur</option><option value='Vikravandi' >Vikravandi</option><option value='Vilangudi' >Vilangudi</option><option value='Vilankurichi' >Vilankurichi</option><option value='Vilapakkam' >Vilapakkam</option><option value='Vilathikulam' >Vilathikulam</option><option value='Vilavur' >Vilavur</option><option value='Villianur' >Villianur</option><option value='Villiappally' >Villiappally</option><option value='Villukuri' >Villukuri</option><option value='Villupuram' >Villupuram</option><option value='Vinchhiya' >Vinchhiya</option><option value='Vinukonda' >Vinukonda</option><option value='Vinzol' >Vinzol</option><option value='Viraganur' >Viraganur</option><option value='Virakeralam' >Virakeralam</option><option value='Virakkalpudur' >Virakkalpudur</option><option value='Virapandi' >Virapandi</option><option value='Virapandi Cantonment' >Virapandi Cantonment</option><option value='Virappanchatram' >Virappanchatram</option><option value='Virar' >Virar</option><option value='Virarajendrapet' >Virarajendrapet</option><option value='Viratnagar' >Viratnagar</option><option value='Viravanallur' >Viravanallur</option><option value='Virbhadra' >Virbhadra</option><option value='Virpur' >Virpur</option><option value='Virudambattu' >Virudambattu</option><option value='Virudhachalam' >Virudhachalam</option><option value='Virudhunagar' >Virudhunagar</option><option value='Virupakshipuram' >Virupakshipuram</option><option value='Visakhapatnam' >Visakhapatnam</option><option value='Visapur' >Visapur</option><option value='Visavadar' >Visavadar</option><option value='Vishakhapatnam' >Vishakhapatnam</option><option value='Vishrampur' >Vishrampur</option><option value='Visnagar' >Visnagar</option><option value='Viswanatham' >Viswanatham</option><option value='Vite' >Vite</option><option value='Vithalwadi' >Vithalwadi</option><option value='Vizianagaram' >Vizianagaram</option><option value='Vriddhachalam' >Vriddhachalam</option><option value='Vrindavan' >Vrindavan</option><option value='Vuyyuru' >Vuyyuru</option><option value='Vyara' >Vyara</option><option value='Wadhwan' >Wadhwan</option><option value='Wadi A.C.C.' >Wadi A.C.C.</option><option value='Waghai' >Waghai</option><option value='Waghapur' >Waghapur</option><option value='Waghodia' >Waghodia</option><option value='Wai' >Wai</option><option value='Wajegaon' >Wajegaon</option><option value='Walajabad' >Walajabad</option><option value='Walajapet' >Walajapet</option><option value='Walani' >Walani</option><option value='Wanadongri' >Wanadongri</option><option value='Wanaparthy' >Wanaparthy</option><option value='Wangjing' >Wangjing</option><option value='Wangoi' >Wangoi</option><option value='Wani' >Wani</option><option value='Wankaner' >Wankaner</option><option value='Wanparti' >Wanparti</option><option value='Warangal' >Warangal</option><option value='Waraseoni' >Waraseoni</option><option value='Wardha' >Wardha</option><option value='Waris Aliganj' >Waris Aliganj</option><option value='Warora' >Warora</option><option value='Warthi' >Warthi</option><option value='Warud' >Warud</option><option value='Washim' >Washim</option><option value='Wayanad' >Wayanad</option><option value='Wazirganj' >Wazirganj</option><option value='Wellington' >Wellington</option><option value='Wer' >Wer</option><option value='West Delhi' >West Delhi</option><option value='West Garo Hills' >West Garo Hills</option><option value='West Godavari' >West Godavari</option><option value='West Godavari Dist.' >West Godavari Dist.</option><option value='West Kameng' >West Kameng</option><option value='West Khasi Hills' >West Khasi Hills</option><option value='West Sikkim' >West Sikkim</option><option value='West Tripura' >West Tripura</option><option value='Williamnagar' >Williamnagar</option><option value='Wokha' >Wokha</option><option value='Yadagiri' >Yadagiri</option><option value='Yadagirigutta' >Yadagirigutta</option><option value='Yadgir' >Yadgir</option><option value='Yairipok' >Yairipok</option><option value='Yamuna Nagar' >Yamuna Nagar</option><option value='Yamunanagar' >Yamunanagar</option><option value='Yanam' >Yanam</option><option value='Yarada' >Yarada</option><option value='Yaval' >Yaval</option><option value='Yavatmal' >Yavatmal</option><option value='Yelahanka' >Yelahanka</option><option value='Yelandur' >Yelandur</option><option value='Yelbarga' >Yelbarga</option><option value='Yellamanchili' >Yellamanchili</option><option value='Yellandu' >Yellandu</option><option value='Yellapur' >Yellapur</option><option value='Yemmiganur' >Yemmiganur</option><option value='Yenagudde' >Yenagudde</option><option value='Yenamalakudru' >Yenamalakudru</option><option value='Yendada' >Yendada</option><option value='Yeola' >Yeola</option><option value='Yercaud' >Yercaud</option><option value='Yerkheda' >Yerkheda</option><option value='Yerraguntla' >Yerraguntla</option><option value='Yol' >Yol</option><option value='Zafarabad' >Zafarabad</option><option value='Zahirabad' >Zahirabad</option><option value='Zaidpur' >Zaidpur</option><option value='Zalod' >Zalod</option><option value='Zamania' >Zamania</option><option value='Zamin Uthukuli' >Zamin Uthukuli</option><option value='Zawlnuam' >Zawlnuam</option><option value='Zira' >Zira</option><option value='Zirakpur' >Zirakpur</option><option value='Ziro' >Ziro</option><option value='Zunheboto' >Zunheboto</option></select>
				<?php if(isset($CityCMRBtech2018) && $CityCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("CityCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $CityCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CityCMRBtech2018_error'></div></div>
				</div>
				</div>
			
			
				<div class='additionalInfoLeftCol'>
				<label>Pincode: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PincodeCMRBtech2018' id='PincodeCMRBtech2018'  validate="validateInteger"    caption="pincode"   minlength="6"   maxlength="6"      value=''   />
				<?php if(isset($PincodeCMRBtech2018) && $PincodeCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PincodeCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PincodeCMRBtech2018 );  ?>";
				      document.getElementById("PincodeCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PincodeCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Guardian's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EmergencyPhoneNumberCMRBtech2018' id='EmergencyPhoneNumberCMRBtech2018'  validate="validateMobileInteger"    caption="mobile number"   minlength="10"   maxlength="10"      value=''   />
				<?php if(isset($EmergencyPhoneNumberCMRBtech2018) && $EmergencyPhoneNumberCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("EmergencyPhoneNumberCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $EmergencyPhoneNumberCMRBtech2018 );  ?>";
				      document.getElementById("EmergencyPhoneNumberCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EmergencyPhoneNumberCMRBtech2018_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class="sub-section"><h3>Education Details</h3></div>
				<div class="cmr_title">
					<h2>10th/12th Details</h2>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Mode of Study(10th):<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<select name='ModeOfStudySSLCCMRBtech2018' id='ModeOfStudySSLCCMRBtech2018'      validate="validateSelect"   required="true"   caption="mode of study"  ><option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value=' Open School' > Open School</option><option value=' Other' > Other</option></select>
				<?php if(isset($ModeOfStudySSLCCMRBtech2018) && $ModeOfStudySSLCCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ModeOfStudySSLCCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ModeOfStudySSLCCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ModeOfStudySSLCCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Registration Number(10th):<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='RegistrationNumber1CMRBtech2018' id='RegistrationNumber1CMRBtech2018'  validate="validateStr"   required="true"   caption="registration number"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($RegistrationNumber1CMRBtech2018) && $RegistrationNumber1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("RegistrationNumber1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $RegistrationNumber1CMRBtech2018 );  ?>";
				      document.getElementById("RegistrationNumber1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'RegistrationNumber1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Is your result out?(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"  divName = '12th' name='IsXIIResultOutCMRBtech2018' id='IsXIIResultOutCMRBtech20180' onclick="hideDiv(this,'No')"  value='Yes'    ></input><span >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"  divName = '12th' name='IsXIIResultOutCMRBtech2018' id='IsXIIResultOutCMRBtech20181' onclick="hideDiv(this,'Yes')"  value='No'  checked  ></input><span >No</span>&nbsp;&nbsp;
				<?php if(isset($IsXIIResultOutCMRBtech2018) && $IsXIIResultOutCMRBtech2018!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["IsXIIResultOutCMRBtech2018"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $IsXIIResultOutCMRBtech2018;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'IsXIIResultOutCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Mode of Study(12th):<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<select name='ModeOfStudyXIICMRBtech2018' id='ModeOfStudyXIICMRBtech2018'      validate="validateSelect"   required="true"   caption="mode of study"  ><option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value=' Open School' > Open School</option><option value=' Other' > Other</option></select>
				<?php if(isset($ModeOfStudyXIICMRBtech2018) && $ModeOfStudyXIICMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ModeOfStudyXIICMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ModeOfStudyXIICMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ModeOfStudyXIICMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Registration Number(12th):<span style="color: red">*</span> </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='RegistrationNumber2CMRBtech2018' id='RegistrationNumber2CMRBtech2018'  validate="validateStr"   required="true"   caption="registration number"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($RegistrationNumber2CMRBtech2018) && $RegistrationNumber2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("RegistrationNumber2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $RegistrationNumber2CMRBtech2018 );  ?>";
				      document.getElementById("RegistrationNumber2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'RegistrationNumber2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Evaluation Type (Grade/Percentage): </label>
				<div class='fieldBoxLarge'>
				<input type='radio'    name='EvaluationTypeXIICMRBtech2018' id='EvaluationTypeXIICMRBtech20180' value='Grade'    ></input><span >Grade</span>&nbsp;&nbsp;
				<input type='radio'    name='EvaluationTypeXIICMRBtech2018' id='EvaluationTypeXIICMRBtech20181'   value='Percentage'    checked></input><span >Percentage</span>&nbsp;&nbsp;
				<?php if(isset($EvaluationTypeXIICMRBtech2018) && $EvaluationTypeXIICMRBtech2018!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["EvaluationTypeXIICMRBtech2018"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $EvaluationTypeXIICMRBtech2018;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EvaluationTypeXIICMRBtech2018_error'></div></div>
				</div>
				</div>
			</li>

			<li>

			<div class="twe" id="12th_Yes" style="display: none;">
              <div class="cmr_box">
				<div class='additionalInfoLeftCol'>
				<label>Subject(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectXII1CMRBtech2018' id='SubjectXII1CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Subject 1"  />
				<?php if(isset($SubjectXII1CMRBtech2018) && $SubjectXII1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectXII1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectXII1CMRBtech2018 );  ?>";
				      document.getElementById("SubjectXII1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectXII1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkXII1CMRBtech2018' id='MaxMarkXII1CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 1" />
				<?php if(isset($MaxMarkXII1CMRBtech2018) && $MaxMarkXII1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkXII1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkXII1CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkXII1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkXII1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks/Grade Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksXII1CMRBtech2018' id='MarksXII1CMRBtech2018'  validate="validateStr"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Subject 1"  />
				<?php if(isset($MarksXII1CMRBtech2018) && $MarksXII1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksXII1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksXII1CMRBtech2018 );  ?>";
				      document.getElementById("MarksXII1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksXII1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageXII1CMRBtech2018' id='PercentageXII1CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 1" />
				<?php if(isset($PercentageXII1CMRBtech2018) && $PercentageXII1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageXII1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageXII1CMRBtech2018 );  ?>";
				      document.getElementById("PercentageXII1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageXII1CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">	
			
				<div class='additionalInfoLeftCol'>
				<label>Subject(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectXII2CMRBtech2018' id='SubjectXII2CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Subject 2" />
				<?php if(isset($SubjectXII2CMRBtech2018) && $SubjectXII2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectXII2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectXII2CMRBtech2018 );  ?>";
				      document.getElementById("SubjectXII2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectXII2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkXII2CMRBtech2018' id='MaxMarkXII2CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Subject 2"  />
				<?php if(isset($MaxMarkXII2CMRBtech2018) && $MaxMarkXII2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkXII2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkXII2CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkXII2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkXII2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks/Grade Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksXII2CMRBtech2018' id='MarksXII2CMRBtech2018'  validate="validateStr"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Subject 2"   />
				<?php if(isset($MarksXII2CMRBtech2018) && $MarksXII2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksXII2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksXII2CMRBtech2018 );  ?>";
				      document.getElementById("MarksXII2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksXII2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageXII2CMRBtech2018' id='PercentageXII2CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 2" />
				<?php if(isset($PercentageXII2CMRBtech2018) && $PercentageXII2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageXII2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageXII2CMRBtech2018 );  ?>";
				      document.getElementById("PercentageXII2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageXII2CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectXII3CMRBtech2018' id='SubjectXII3CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Subject 3" />
				<?php if(isset($SubjectXII3CMRBtech2018) && $SubjectXII3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectXII3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectXII3CMRBtech2018 );  ?>";
				      document.getElementById("SubjectXII3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectXII3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkXII3CMRBtech2018' id='MaxMarkXII3CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Subject 3"  />
				<?php if(isset($MaxMarkXII3CMRBtech2018) && $MaxMarkXII3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkXII3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkXII3CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkXII3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkXII3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks/Grade Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksXII3CMRBtech2018' id='MarksXII3CMRBtech2018'  validate="validateStr"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Subject 3"  />
				<?php if(isset($MarksXII3CMRBtech2018) && $MarksXII3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksXII3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksXII3CMRBtech2018 );  ?>";
				      document.getElementById("MarksXII3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksXII3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageXII3CMRBtech2018' id='PercentageXII3CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Subject 3"  />
				<?php if(isset($PercentageXII3CMRBtech2018) && $PercentageXII3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageXII3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageXII3CMRBtech2018 );  ?>";
				      document.getElementById("PercentageXII3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageXII3CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectXII4CMRBtech2018' id='SubjectXII4CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Subject 4"  />
				<?php if(isset($SubjectXII4CMRBtech2018) && $SubjectXII4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectXII4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectXII4CMRBtech2018 );  ?>";
				      document.getElementById("SubjectXII4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectXII4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkXII4CMRBtech2018' id='MaxMarkXII4CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 4" />
				<?php if(isset($MaxMarkXII4CMRBtech2018) && $MaxMarkXII4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkXII4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkXII4CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkXII4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkXII4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks/Grade Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksXII4CMRBtech2018' id='MarksXII4CMRBtech2018'  validate="validateStr"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Subject 4"  />
				<?php if(isset($MarksXII4CMRBtech2018) && $MarksXII4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksXII4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksXII4CMRBtech2018 );  ?>";
				      document.getElementById("MarksXII4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksXII4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageXII4CMRBtech2018' id='PercentageXII4CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 4" />
				<?php if(isset($PercentageXII4CMRBtech2018) && $PercentageXII4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageXII4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageXII4CMRBtech2018 );  ?>";
				      document.getElementById("PercentageXII4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageXII4CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectXII5CMRBtech2018' id='SubjectXII5CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Subject 5" />
				<?php if(isset($SubjectXII5CMRBtech2018) && $SubjectXII5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectXII5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectXII5CMRBtech2018 );  ?>";
				      document.getElementById("SubjectXII5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectXII5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkXII5CMRBtech2018' id='MaxMarkXII5CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 5" />
				<?php if(isset($MaxMarkXII5CMRBtech2018) && $MaxMarkXII5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkXII5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkXII5CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkXII5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkXII5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks/Grade Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksXII5CMRBtech2018' id='MarksXII5CMRBtech2018'  validate="validateStr"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Subject 5"  />
				<?php if(isset($MarksXII5CMRBtech2018) && $MarksXII5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksXII5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksXII5CMRBtech2018 );  ?>";
				      document.getElementById("MarksXII5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksXII5CMRBtech2018_error'></div></div>
				</div>
			</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage Obtained(12th): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageXII5CMRBtech2018' id='PercentageXII5CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Subject 5" />
				<?php if(isset($PercentageXII5CMRBtech2018) && $PercentageXII5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageXII5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageXII5CMRBtech2018 );  ?>";
				      document.getElementById("PercentageXII5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageXII5CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
		</div>
			</li>

			<li>
				<div class="cmr_title">
					<h2>Graduation Details</h2>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Do you have Graduation/Diploma details?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true" divName = 'graduationshow'  name='IsDegreeDeplomaDoneCMRBtech2018' id='IsDegreeDeplomaDoneCMRBtech20180' onclick="hideDiv(this,'No')"  value='Yes'    ></input><span >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"  divName = 'graduationshow' name='IsDegreeDeplomaDoneCMRBtech2018' id='IsDegreeDeplomaDoneCMRBtech20181' onclick="hideDiv(this,'Yes')"   value='No'  checked  ></input><span >No</span>&nbsp;&nbsp;
				<?php if(isset($IsDegreeDeplomaDoneCMRBtech2018) && $IsDegreeDeplomaDoneCMRBtech2018!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["IsDegreeDeplomaDoneCMRBtech2018"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $IsDegreeDeplomaDoneCMRBtech2018;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'IsDegreeDeplomaDoneCMRBtech2018_error'></div></div>
				</div>
				</div>
			
			<div class="graduationshow" id="graduationshow_Yes" style="display: none;">
				
				<div class='additionalInfoLeftCol' style="margin-top: 20px">
				<label>Degree or Diploma: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'    name='GraduationTypeCMRBtech2018' id='GraduationTypeCMRBtech20180'   value='Degree'  checked  ></input><span >Degree</span>&nbsp;&nbsp;
				<input type='radio'    name='GraduationTypeCMRBtech2018' id='GraduationTypeCMRBtech20181'   value='Diploma'    ></input><span >Diploma</span>&nbsp;&nbsp;
				<?php if(isset($GraduationTypeCMRBtech2018) && $GraduationTypeCMRBtech2018!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["GraduationTypeCMRBtech2018"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $GraduationTypeCMRBtech2018;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationTypeCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Name of Degree/Diploma: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='GraduationNameCMRBtech2018' id='GraduationNameCMRBtech2018'  validate="validateStr"    caption="name"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($GraduationNameCMRBtech2018) && $GraduationNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("GraduationNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $GraduationNameCMRBtech2018 );  ?>";
				      document.getElementById("GraduationNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationNameCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Area of Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='GraduationSpecializatonCMRBtech2018' id='GraduationSpecializatonCMRBtech2018'  validate="validateStr"    caption="area of specialization"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($GraduationSpecializatonCMRBtech2018) && $GraduationSpecializatonCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("GraduationSpecializatonCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $GraduationSpecializatonCMRBtech2018 );  ?>";
				      document.getElementById("GraduationSpecializatonCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationSpecializatonCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Name of College: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='GraduationInstituteNameCMRBtech2018' id='GraduationInstituteNameCMRBtech2018'  validate="validateStr"    caption="institute name"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($GraduationInstituteNameCMRBtech2018) && $GraduationInstituteNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("GraduationInstituteNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $GraduationInstituteNameCMRBtech2018 );  ?>";
				      document.getElementById("GraduationInstituteNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationInstituteNameCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Name of University/Technical Board: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='GraduationUniversityNameCMRBtech2018' id='GraduationUniversityNameCMRBtech2018'  validate="validateStr"    caption="university name"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($GraduationUniversityNameCMRBtech2018) && $GraduationUniversityNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("GraduationUniversityNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $GraduationUniversityNameCMRBtech2018 );  ?>";
				      document.getElementById("GraduationUniversityNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationUniversityNameCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Year of Completion: </label>
				<div class='fieldBoxLarge'>
				<select name='GraduationYearOfPassingCMRBtech2018' id='GraduationYearOfPassingCMRBtech2018'      validate="validateSelect"    caption="year of completion"  ><option value='' selected>Select</option><option value=' 1970' > 1970</option><option value=' 1971' > 1971</option><option value=' 1972' > 1972</option><option value=' 1973' > 1973</option><option value=' 1974' > 1974</option><option value=' 1975' > 1975</option><option value=' 1976' > 1976</option><option value=' 1977' > 1977</option><option value=' 1978' > 1978</option><option value=' 1979' > 1979</option><option value=' 1980' > 1980</option><option value=' 1981' > 1981</option><option value=' 1982' > 1982</option><option value=' 1983' > 1983</option><option value=' 1984' > 1984</option><option value=' 1985' > 1985</option><option value=' 1986' > 1986</option><option value=' 1987' > 1987</option><option value=' 1988' > 1988</option><option value=' 1989' > 1989</option><option value=' 1990' > 1990</option><option value=' 1991' > 1991</option><option value=' 1992' > 1992</option><option value=' 1993' > 1993</option><option value=' 1994' > 1994</option><option value=' 1995' > 1995</option><option value=' 1996' > 1996</option><option value=' 1997' > 1997</option><option value=' 1998' > 1998</option><option value=' 1999' > 1999</option><option value=' 2000' > 2000</option><option value=' 2001' > 2001</option><option value=' 2002' > 2002</option><option value=' 2003' > 2003</option><option value=' 2004' > 2004</option><option value=' 2005' > 2005</option><option value=' 2006' > 2006</option><option value=' 2007' > 2007</option><option value=' 2008' > 2008</option><option value=' 2009' > 2009</option><option value=' 2010' > 2010</option><option value=' 2011' > 2011</option><option value=' 2012' > 2012</option><option value=' 2013' > 2013</option><option value=' 2014' > 2014</option><option value=' 2015' > 2015</option><option value=' 2016' > 2016</option><option value=' 2017' > 2017</option><option value=' 2018' > 2018</option></select>
				<?php if(isset($GraduationYearOfPassingCMRBtech2018) && $GraduationYearOfPassingCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("GraduationYearOfPassingCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $GraduationYearOfPassingCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationYearOfPassingCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Mode of Study: </label>
				<div class='fieldBoxLarge'>
				<select name='GraduationModeOfStudyCMRBtech2018' id='GraduationModeOfStudyCMRBtech2018'   validate="validateSelect"    caption="mode of study"  ><option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value=' Open School' > Open School</option><option value=' Other' > Other</option></select>
				<?php if(isset($GraduationModeOfStudyCMRBtech2018) && $GraduationModeOfStudyCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("GraduationModeOfStudyCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $GraduationModeOfStudyCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'GraduationModeOfStudyCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Examination Type: </label>
				<div class='fieldBoxLarge'>
				<select name='ExaminationTypeCMRBtech2018' id='ExaminationTypeCMRBtech2018'      validate="validateSelect"  onchange="UGModeOfStudy();"   caption="education type"  ><option value='' selected>Select</option><option value='Semester' >Semester</option><option value='Year' >Year</option></select>
				<?php if(isset($ExaminationTypeCMRBtech2018) && $ExaminationTypeCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ExaminationTypeCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ExaminationTypeCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ExaminationTypeCMRBtech2018_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoLeftCol'></div>

				<div class="graduationyearshow" id="graduationyearshow" style="display: none;">
				<div class="cmr_box">

				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeYear1CMRBtech2018' id='SubjectDegreeYear1CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 1"   />
				<?php if(isset($SubjectDegreeYear1CMRBtech2018) && $SubjectDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeYear1CMRBtech2018' id='MaxMarkDegreeYear1CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Year 1" />
				<?php if(isset($MaxMarkDegreeYear1CMRBtech2018) && $MaxMarkDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeYear1CMRBtech2018' id='MarksDegreeYear1CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Year 1" />
				<?php if(isset($MarksDegreeYear1CMRBtech2018) && $MarksDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeYear1CMRBtech2018' id='PercentageDegreeYear1CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Year 1" />
				<?php if(isset($PercentageDegreeYear1CMRBtech2018) && $PercentageDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeYear2CMRBtech2018' id='SubjectDegreeYear2CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 2"  />
				<?php if(isset($SubjectDegreeYear2CMRBtech2018) && $SubjectDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeYear2CMRBtech2018' id='MaxMarkDegreeYear2CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Year 2" />
				<?php if(isset($MaxMarkDegreeYear2CMRBtech2018) && $MaxMarkDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeYear2CMRBtech2018' id='MarksDegreeYear2CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Year 2" />
				<?php if(isset($MarksDegreeYear2CMRBtech2018) && $MarksDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeYear2CMRBtech2018' id='PercentageDegreeYear2CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Year 2"  />
				<?php if(isset($PercentageDegreeYear2CMRBtech2018) && $PercentageDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeYear3CMRBtech2018' id='SubjectDegreeYear3CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 3"  />
				<?php if(isset($SubjectDegreeYear3CMRBtech2018) && $SubjectDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeYear3CMRBtech2018' id='MaxMarkDegreeYear3CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Year 3" />
				<?php if(isset($MaxMarkDegreeYear3CMRBtech2018) && $MaxMarkDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeYear3CMRBtech2018' id='MarksDegreeYear3CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Year 3" />
				<?php if(isset($MarksDegreeYear3CMRBtech2018) && $MarksDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeYear3CMRBtech2018' id='PercentageDegreeYear3CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Year 3" />
				<?php if(isset($PercentageDegreeYear3CMRBtech2018) && $PercentageDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeYear4CMRBtech2018' id='SubjectDegreeYear4CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Year 4" />
				<?php if(isset($SubjectDegreeYear4CMRBtech2018) && $SubjectDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeYear4CMRBtech2018' id='MaxMarkDegreeYear4CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Year 4"  />
				<?php if(isset($MaxMarkDegreeYear4CMRBtech2018) && $MaxMarkDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeYear4CMRBtech2018' id='MarksDegreeYear4CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Year 4"  />
				<?php if(isset($MarksDegreeYear4CMRBtech2018) && $MarksDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeYear4CMRBtech2018' id='PercentageDegreeYear4CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Year 4"  />
				<?php if(isset($PercentageDegreeYear4CMRBtech2018) && $PercentageDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			

			</div>
			<div class='graduationsemestershow'  id="graduationsemestershow" style="display: none;">
			<div class="cmr_box">
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem1CMRBtech2018' id='SubjectDegreeSem1CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Semester 1"  />
				<?php if(isset($SubjectDegreeSem1CMRBtech2018) && $SubjectDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem1CMRBtech2018' id='MaxMarkDegreeSem1CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 1"  />
				<?php if(isset($MaxMarkDegreeSem1CMRBtech2018) && $MaxMarkDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem1CMRBtech2018' id='MarksDegreeSem1CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 1" />
				<?php if(isset($MarksDegreeSem1CMRBtech2018) && $MarksDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem1CMRBtech2018' id='PercentageDegreeSem1CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 1"  />
				<?php if(isset($PercentageDegreeSem1CMRBtech2018) && $PercentageDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem2CMRBtech2018' id='SubjectDegreeSem2CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 2" />
				<?php if(isset($SubjectDegreeSem2CMRBtech2018) && $SubjectDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem2CMRBtech2018' id='MaxMarkDegreeSem2CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 2"  />
				<?php if(isset($MaxMarkDegreeSem2CMRBtech2018) && $MaxMarkDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem2CMRBtech2018' id='MarksDegreeSem2CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 2"  />
				<?php if(isset($MarksDegreeSem2CMRBtech2018) && $MarksDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem2CMRBtech2018' id='PercentageDegreeSem2CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 2" />
				<?php if(isset($PercentageDegreeSem2CMRBtech2018) && $PercentageDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem3CMRBtech2018' id='SubjectDegreeSem3CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 3" />
				<?php if(isset($SubjectDegreeSem3CMRBtech2018) && $SubjectDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem3CMRBtech2018' id='MaxMarkDegreeSem3CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 3" />
				<?php if(isset($MaxMarkDegreeSem3CMRBtech2018) && $MaxMarkDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem3CMRBtech2018' id='MarksDegreeSem3CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 3"  />
				<?php if(isset($MarksDegreeSem3CMRBtech2018) && $MarksDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem3CMRBtech2018' id='PercentageDegreeSem3CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 3" />
				<?php if(isset($PercentageDegreeSem3CMRBtech2018) && $PercentageDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem4CMRBtech2018' id='SubjectDegreeSem4CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 4" />
				<?php if(isset($SubjectDegreeSem4CMRBtech2018) && $SubjectDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem4CMRBtech2018' id='MaxMarkDegreeSem4CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 4" />
				<?php if(isset($MaxMarkDegreeSem4CMRBtech2018) && $MaxMarkDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem4CMRBtech2018' id='MarksDegreeSem4CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 4"   />
				<?php if(isset($MarksDegreeSem4CMRBtech2018) && $MarksDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem4CMRBtech2018' id='PercentageDegreeSem4CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 4"  />
				<?php if(isset($PercentageDegreeSem4CMRBtech2018) && $PercentageDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem5CMRBtech2018' id='SubjectDegreeSem5CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 5" />
				<?php if(isset($SubjectDegreeSem5CMRBtech2018) && $SubjectDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem5CMRBtech2018' id='MaxMarkDegreeSem5CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 5" />
				<?php if(isset($MaxMarkDegreeSem5CMRBtech2018) && $MaxMarkDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem5CMRBtech2018' id='MarksDegreeSem5CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 5"  />
				<?php if(isset($MarksDegreeSem5CMRBtech2018) && $MarksDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem5CMRBtech2018' id='PercentageDegreeSem5CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 5"  />
				<?php if(isset($PercentageDegreeSem5CMRBtech2018) && $PercentageDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem6CMRBtech2018' id='SubjectDegreeSem6CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 6" />
				<?php if(isset($SubjectDegreeSem6CMRBtech2018) && $SubjectDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem6CMRBtech2018' id='MaxMarkDegreeSem6CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 6" />
				<?php if(isset($MaxMarkDegreeSem6CMRBtech2018) && $MaxMarkDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem6CMRBtech2018' id='MarksDegreeSem6CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 6"  />
				<?php if(isset($MarksDegreeSem6CMRBtech2018) && $MarksDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem6CMRBtech2018' id='PercentageDegreeSem6CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 6"  />
				<?php if(isset($PercentageDegreeSem6CMRBtech2018) && $PercentageDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem7CMRBtech2018' id='SubjectDegreeSem7CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 7" />
				<?php if(isset($SubjectDegreeSem7CMRBtech2018) && $SubjectDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem7CMRBtech2018' id='MaxMarkDegreeSem7CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 7" />
				<?php if(isset($MaxMarkDegreeSem7CMRBtech2018) && $MaxMarkDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem7CMRBtech2018' id='MarksDegreeSem7CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 7"  />
				<?php if(isset($MarksDegreeSem7CMRBtech2018) && $MarksDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem7CMRBtech2018' id='PercentageDegreeSem7CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 7"  />
				<?php if(isset($PercentageDegreeSem7CMRBtech2018) && $PercentageDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectDegreeSem8CMRBtech2018' id='SubjectDegreeSem8CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 8" />
				<?php if(isset($SubjectDegreeSem8CMRBtech2018) && $SubjectDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("SubjectDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkDegreeSem8CMRBtech2018' id='MaxMarkDegreeSem8CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 8"  />
				<?php if(isset($MaxMarkDegreeSem8CMRBtech2018) && $MaxMarkDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksDegreeSem8CMRBtech2018' id='MarksDegreeSem8CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 8"  />
				<?php if(isset($MarksDegreeSem8CMRBtech2018) && $MarksDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("MarksDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentageDegreeSem8CMRBtech2018' id='PercentageDegreeSem8CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 8"  />
				<?php if(isset($PercentageDegreeSem8CMRBtech2018) && $PercentageDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentageDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentageDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("PercentageDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentageDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			</div>
		</div>
			</li>

			<li>
				<div class="cmr_title">
					<h2>Post Graduation Details</h2>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Do you have Post Graduation/Diploma details?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='IsPGDegreeDeplomaDoneCMRBtech2018' divName = 'postgraduationshow' id='IsPGDegreeDeplomaDoneCMRBtech20180'  onclick="hideDiv(this,'No')" value='Yes'    ></input><span >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='IsPGDegreeDeplomaDoneCMRBtech2018'  divName = 'postgraduationshow' id='IsPGDegreeDeplomaDoneCMRBtech20181' onclick="hideDiv(this,'Yes')"  value='No'  checked  ></input><span >No</span>&nbsp;&nbsp;
				<?php if(isset($IsPGDegreeDeplomaDoneCMRBtech2018) && $IsPGDegreeDeplomaDoneCMRBtech2018!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["IsPGDegreeDeplomaDoneCMRBtech2018"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $IsPGDegreeDeplomaDoneCMRBtech2018;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'IsPGDegreeDeplomaDoneCMRBtech2018_error'></div></div>
				</div>
				</div>
			
			<div class="postgraduationshow" id="postgraduationshow_Yes" style="display: none;">

				<div class='additionalInfoLeftCol'>
				<label>Degree or Diploma: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'    name='PostGraduationTypeCMRBtech2018' id='PostGraduationTypeCMRBtech20180'   value='Degree'  checked  ></input><span >Degree</span>&nbsp;&nbsp;
				<input type='radio'    name='PostGraduationTypeCMRBtech2018' id='PostGraduationTypeCMRBtech20181'   value='Diploma'    ></input><span >Diploma</span>&nbsp;&nbsp;
				<?php if(isset($PostGraduationTypeCMRBtech2018) && $PostGraduationTypeCMRBtech2018!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["PostGraduationTypeCMRBtech2018"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $PostGraduationTypeCMRBtech2018;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PostGraduationTypeCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Name of Degree/Diploma: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PostGraduationNameCMRBtech2018' id='PostGraduationNameCMRBtech2018'  validate="validateStr"    caption="name"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($PostGraduationNameCMRBtech2018) && $PostGraduationNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PostGraduationNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PostGraduationNameCMRBtech2018 );  ?>";
				      document.getElementById("PostGraduationNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PostGraduationNameCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Area of Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PGSpecializatonCMRBtech2018' id='PGSpecializatonCMRBtech2018'  validate="validateStr"    caption="area of specialization"   minlength="1"   maxlength="50"      value=''   />
				<?php if(isset($PGSpecializatonCMRBtech2018) && $PGSpecializatonCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PGSpecializatonCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PGSpecializatonCMRBtech2018 );  ?>";
				      document.getElementById("PGSpecializatonCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PGSpecializatonCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Name of College: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PGInstituteNameCMRBtech2018' id='PGInstituteNameCMRBtech2018'  validate="validateStr"    caption="institute name"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($PGInstituteNameCMRBtech2018) && $PGInstituteNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PGInstituteNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PGInstituteNameCMRBtech2018 );  ?>";
				      document.getElementById("PGInstituteNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PGInstituteNameCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Name of University/Technical Board: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PGUniversityNameCMRBtech2018' id='PGUniversityNameCMRBtech2018'  validate="validateStr"    caption="university name"   minlength="1"   maxlength="100"      value=''   />
				<?php if(isset($PGUniversityNameCMRBtech2018) && $PGUniversityNameCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PGUniversityNameCMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PGUniversityNameCMRBtech2018 );  ?>";
				      document.getElementById("PGUniversityNameCMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PGUniversityNameCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Year of Completion: </label>
				<div class='fieldBoxLarge'>
				<select name='PGYearOfPassingCMRBtech2018' id='PGYearOfPassingCMRBtech2018'      validate="validateSelect"    caption="year of completion"  ><option value='' selected>Select</option><option value=' 1970' > 1970</option><option value=' 1971' > 1971</option><option value=' 1972' > 1972</option><option value=' 1973' > 1973</option><option value=' 1974' > 1974</option><option value=' 1975' > 1975</option><option value=' 1976' > 1976</option><option value=' 1977' > 1977</option><option value=' 1978' > 1978</option><option value=' 1979' > 1979</option><option value=' 1980' > 1980</option><option value=' 1981' > 1981</option><option value=' 1982' > 1982</option><option value=' 1983' > 1983</option><option value=' 1984' > 1984</option><option value=' 1985' > 1985</option><option value=' 1986' > 1986</option><option value=' 1987' > 1987</option><option value=' 1988' > 1988</option><option value=' 1989' > 1989</option><option value=' 1990' > 1990</option><option value=' 1991' > 1991</option><option value=' 1992' > 1992</option><option value=' 1993' > 1993</option><option value=' 1994' > 1994</option><option value=' 1995' > 1995</option><option value=' 1996' > 1996</option><option value=' 1997' > 1997</option><option value=' 1998' > 1998</option><option value=' 1999' > 1999</option><option value=' 2000' > 2000</option><option value=' 2001' > 2001</option><option value=' 2002' > 2002</option><option value=' 2003' > 2003</option><option value=' 2004' > 2004</option><option value=' 2005' > 2005</option><option value=' 2006' > 2006</option><option value=' 2007' > 2007</option><option value=' 2008' > 2008</option><option value=' 2009' > 2009</option><option value=' 2010' > 2010</option><option value=' 2011' > 2011</option><option value=' 2012' > 2012</option><option value=' 2013' > 2013</option><option value=' 2014' > 2014</option><option value=' 2015' > 2015</option><option value=' 2016' > 2016</option><option value=' 2017' > 2017</option><option value=' 2018' > 2018</option></select>
				<?php if(isset($PGYearOfPassingCMRBtech2018) && $PGYearOfPassingCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("PGYearOfPassingCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $PGYearOfPassingCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PGYearOfPassingCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Mode of Study: </label>
				<div class='fieldBoxLarge'>
				<select name='PGModeOfStudyCMRBtech2018' id='PGModeOfStudyCMRBtech2018'      validate="validateSelect"    caption="mode of study"  ><option value='' selected>Select</option><option value='Full Time' >Full Time</option><option value=' Open School' > Open School</option><option value=' Other' > Other</option></select>
				<?php if(isset($PGModeOfStudyCMRBtech2018) && $PGModeOfStudyCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("PGModeOfStudyCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $PGModeOfStudyCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PGModeOfStudyCMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Examination Type: </label>
				<div class='fieldBoxLarge'>
				<select name='PGExaminationTypeCMRBtech2018' id='PGExaminationTypeCMRBtech2018'      validate="validateSelect" onchange="PGModeOfStudy();"   caption="education type"  ><option value='' selected>Select</option><option value='Semester' >Semester</option><option value='Year' >Year</option></select>
				<?php if(isset($PGExaminationTypeCMRBtech2018) && $PGExaminationTypeCMRBtech2018!=""){ ?>
			      <script>
				  var selObj = document.getElementById("PGExaminationTypeCMRBtech2018"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $PGExaminationTypeCMRBtech2018;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PGExaminationTypeCMRBtech2018_error'></div></div>
				</div>
				</div>
			
			<div  class="postgraduationyearshow" id="postgraduationyearshow" style="display: none;">

				<div class="cmr_box">

				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeYear1CMRBtech2018' id='SubjectPGDegreeYear1CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 1"  />
				<?php if(isset($SubjectPGDegreeYear1CMRBtech2018) && $SubjectPGDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeYear1CMRBtech2018' id='MaxMarkPGDegreeYear1CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Year 1"  />
				<?php if(isset($MaxMarkPGDegreeYear1CMRBtech2018) && $MaxMarkPGDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeYear1CMRBtech2018' id='MarksPGDegreeYear1CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Year 1"  />
				<?php if(isset($MarksPGDegreeYear1CMRBtech2018) && $MarksPGDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeYear1CMRBtech2018' id='PercentagePGDegreeYear1CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Year 1"  />
				<?php if(isset($PercentagePGDegreeYear1CMRBtech2018) && $PercentagePGDegreeYear1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeYear1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeYear1CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeYear1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeYear1CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeYear2CMRBtech2018' id='SubjectPGDegreeYear2CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 2"  />
				<?php if(isset($SubjectPGDegreeYear2CMRBtech2018) && $SubjectPGDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeYear2CMRBtech2018' id='MaxMarkPGDegreeYear2CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Year 2" />
				<?php if(isset($MaxMarkPGDegreeYear2CMRBtech2018) && $MaxMarkPGDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeYear2CMRBtech2018' id='MarksPGDegreeYear2CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Year 2"  />
				<?php if(isset($MarksPGDegreeYear2CMRBtech2018) && $MarksPGDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeYear2CMRBtech2018' id='PercentagePGDegreeYear2CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Year 2"  />
				<?php if(isset($PercentagePGDegreeYear2CMRBtech2018) && $PercentagePGDegreeYear2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeYear2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeYear2CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeYear2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeYear2CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeYear3CMRBtech2018' id='SubjectPGDegreeYear3CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 3"  />
				<?php if(isset($SubjectPGDegreeYear3CMRBtech2018) && $SubjectPGDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeYear3CMRBtech2018' id='MaxMarkPGDegreeYear3CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Year 3" />
				<?php if(isset($MaxMarkPGDegreeYear3CMRBtech2018) && $MaxMarkPGDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeYear3CMRBtech2018' id='MarksPGDegreeYear3CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Year 3"  />
				<?php if(isset($MarksPGDegreeYear3CMRBtech2018) && $MarksPGDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeYear3CMRBtech2018' id='PercentagePGDegreeYear3CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Year 3"  />
				<?php if(isset($PercentagePGDegreeYear3CMRBtech2018) && $PercentagePGDegreeYear3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeYear3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeYear3CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeYear3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeYear3CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeYear4CMRBtech2018' id='SubjectPGDegreeYear4CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Year 4"  />
				<?php if(isset($SubjectPGDegreeYear4CMRBtech2018) && $SubjectPGDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeYear4CMRBtech2018' id='MaxMarkPGDegreeYear4CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Year 4"  />
				<?php if(isset($MaxMarkPGDegreeYear4CMRBtech2018) && $MaxMarkPGDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeYear4CMRBtech2018' id='MarksPGDegreeYear4CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Year 4"  />
				<?php if(isset($MarksPGDegreeYear4CMRBtech2018) && $MarksPGDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeYear4CMRBtech2018' id='PercentagePGDegreeYear4CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Year 4"  />
				<?php if(isset($PercentagePGDegreeYear4CMRBtech2018) && $PercentagePGDegreeYear4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeYear4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeYear4CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeYear4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeYear4CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			
			</div>

			<div class='additionalInfoLeftCol'></div>

			<div  class="postgraduationsemestershow" id="postgraduationsemestershow" style="display: none;">

				<div class="cmr_box">

				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem1CMRBtech2018' id='SubjectPGDegreeSem1CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 1" />
				<?php if(isset($SubjectPGDegreeSem1CMRBtech2018) && $SubjectPGDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem1CMRBtech2018' id='MaxMarkPGDegreeSem1CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 1"  />
				<?php if(isset($MaxMarkPGDegreeSem1CMRBtech2018) && $MaxMarkPGDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem1CMRBtech2018' id='MarksPGDegreeSem1CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 1" />
				<?php if(isset($MarksPGDegreeSem1CMRBtech2018) && $MarksPGDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem1CMRBtech2018' id='PercentagePGDegreeSem1CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 1" />
				<?php if(isset($PercentagePGDegreeSem1CMRBtech2018) && $PercentagePGDegreeSem1CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem1CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem1CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem1CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem1CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem2CMRBtech2018' id='SubjectPGDegreeSem2CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Semester 2"  />
				<?php if(isset($SubjectPGDegreeSem2CMRBtech2018) && $SubjectPGDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem2CMRBtech2018' id='MaxMarkPGDegreeSem2CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 2"  />
				<?php if(isset($MaxMarkPGDegreeSem2CMRBtech2018) && $MaxMarkPGDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem2CMRBtech2018' id='MarksPGDegreeSem2CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 2" />
				<?php if(isset($MarksPGDegreeSem2CMRBtech2018) && $MarksPGDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem2CMRBtech2018' id='PercentagePGDegreeSem2CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''placeholder="Semester 2"   />
				<?php if(isset($PercentagePGDegreeSem2CMRBtech2018) && $PercentagePGDegreeSem2CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem2CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem2CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem2CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem2CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem3CMRBtech2018' id='SubjectPGDegreeSem3CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Semester 3"  />
				<?php if(isset($SubjectPGDegreeSem3CMRBtech2018) && $SubjectPGDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem3CMRBtech2018' id='MaxMarkPGDegreeSem3CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 3" />
				<?php if(isset($MaxMarkPGDegreeSem3CMRBtech2018) && $MaxMarkPGDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem3CMRBtech2018' id='MarksPGDegreeSem3CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 3"  />
				<?php if(isset($MarksPGDegreeSem3CMRBtech2018) && $MarksPGDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem3CMRBtech2018' id='PercentagePGDegreeSem3CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 3"  />
				<?php if(isset($PercentagePGDegreeSem3CMRBtech2018) && $PercentagePGDegreeSem3CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem3CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem3CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem3CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem3CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem4CMRBtech2018' id='SubjectPGDegreeSem4CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Semester 4"  />
				<?php if(isset($SubjectPGDegreeSem4CMRBtech2018) && $SubjectPGDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem4CMRBtech2018' id='MaxMarkPGDegreeSem4CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 4"  />
				<?php if(isset($MaxMarkPGDegreeSem4CMRBtech2018) && $MaxMarkPGDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem4CMRBtech2018' id='MarksPGDegreeSem4CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 4" />
				<?php if(isset($MarksPGDegreeSem4CMRBtech2018) && $MarksPGDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem4CMRBtech2018' id='PercentagePGDegreeSem4CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 4"  />
				<?php if(isset($PercentagePGDegreeSem4CMRBtech2018) && $PercentagePGDegreeSem4CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem4CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem4CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem4CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem4CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem5CMRBtech2018' id='SubjectPGDegreeSem5CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 5" />
				<?php if(isset($SubjectPGDegreeSem5CMRBtech2018) && $SubjectPGDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem5CMRBtech2018' id='MaxMarkPGDegreeSem5CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 5" />
				<?php if(isset($MaxMarkPGDegreeSem5CMRBtech2018) && $MaxMarkPGDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem5CMRBtech2018' id='MarksPGDegreeSem5CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 5" />
				<?php if(isset($MarksPGDegreeSem5CMRBtech2018) && $MarksPGDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem5CMRBtech2018' id='PercentagePGDegreeSem5CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 5"  />
				<?php if(isset($PercentagePGDegreeSem5CMRBtech2018) && $PercentagePGDegreeSem5CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem5CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem5CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem5CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem5CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem6CMRBtech2018' id='SubjectPGDegreeSem6CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Semester 6"  />
				<?php if(isset($SubjectPGDegreeSem6CMRBtech2018) && $SubjectPGDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem6CMRBtech2018' id='MaxMarkPGDegreeSem6CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 6" />
				<?php if(isset($MaxMarkPGDegreeSem6CMRBtech2018) && $MaxMarkPGDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem6CMRBtech2018' id='MarksPGDegreeSem6CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 6"  />
				<?php if(isset($MarksPGDegreeSem6CMRBtech2018) && $MarksPGDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem6CMRBtech2018' id='PercentagePGDegreeSem6CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value='' placeholder="Semester 6"  />
				<?php if(isset($PercentagePGDegreeSem6CMRBtech2018) && $PercentagePGDegreeSem6CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem6CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem6CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem6CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem6CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem7CMRBtech2018' id='SubjectPGDegreeSem7CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value='' placeholder="Semester 7"  />
				<?php if(isset($SubjectPGDegreeSem7CMRBtech2018) && $SubjectPGDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem7CMRBtech2018' id='MaxMarkPGDegreeSem7CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 7"  />
				<?php if(isset($MaxMarkPGDegreeSem7CMRBtech2018) && $MaxMarkPGDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem7CMRBtech2018' id='MarksPGDegreeSem7CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 7" />
				<?php if(isset($MarksPGDegreeSem7CMRBtech2018) && $MarksPGDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem7CMRBtech2018' id='PercentagePGDegreeSem7CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 7" />
				<?php if(isset($PercentagePGDegreeSem7CMRBtech2018) && $PercentagePGDegreeSem7CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem7CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem7CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem7CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem7CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			<div class="cmr_box">
			
				<div class='additionalInfoLeftCol'>
				<label>Subject: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='SubjectPGDegreeSem8CMRBtech2018' id='SubjectPGDegreeSem8CMRBtech2018'  validate="validateStr"    caption="subject"   minlength="1"   maxlength="50"      value=''  placeholder="Semester 8" />
				<?php if(isset($SubjectPGDegreeSem8CMRBtech2018) && $SubjectPGDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("SubjectPGDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $SubjectPGDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("SubjectPGDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SubjectPGDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Max Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MaxMarkPGDegreeSem8CMRBtech2018' id='MaxMarkPGDegreeSem8CMRBtech2018'  validate="validateInteger"    caption="max marks"   minlength="1"   maxlength="5"      value='' placeholder="Semester 8"  />
				<?php if(isset($MaxMarkPGDegreeSem8CMRBtech2018) && $MaxMarkPGDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MaxMarkPGDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MaxMarkPGDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("MaxMarkPGDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MaxMarkPGDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MarksPGDegreeSem8CMRBtech2018' id='MarksPGDegreeSem8CMRBtech2018'  validate="validateInteger"    caption="marks obtained"   minlength="1"   maxlength="5"      value='' placeholder="Semester 8"  />
				<?php if(isset($MarksPGDegreeSem8CMRBtech2018) && $MarksPGDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("MarksPGDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $MarksPGDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("MarksPGDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MarksPGDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoLeftCol'>
				<label>Percentage: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='PercentagePGDegreeSem8CMRBtech2018' id='PercentagePGDegreeSem8CMRBtech2018'  validate="validateFloat"    caption="percentage"   minlength="1"   maxlength="5"      value=''  placeholder="Semester 8" />
				<?php if(isset($PercentagePGDegreeSem8CMRBtech2018) && $PercentagePGDegreeSem8CMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PercentagePGDegreeSem8CMRBtech2018").value = "<?php echo str_replace("\n", '\n', $PercentagePGDegreeSem8CMRBtech2018 );  ?>";
				      document.getElementById("PercentagePGDegreeSem8CMRBtech2018").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'PercentagePGDegreeSem8CMRBtech2018_error'></div></div>
				</div>
				</div>
			</div>
			</div>
		</div>
			</li>

			<li>
				<div class="cmr_title">
					<h2>Documents Checklist</h2>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Upload SSLC/10th Marks Card: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='tenthFileCMRBtech2018'          />
				<input type='hidden' name='tenthFileCMRBtech2018Valid' value='extn:jpg,jpeg,png|size:5'>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				
				<div style='display:none'>
					<div class='errorMsg' id= 'tenthFileCMRBtech2018_error'>
						
					</div>
				</div>
				<label id="tenth_file">
					<?php if(isset($tenthFileCMRBtech2018) && $tenthFileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("tenth_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Upload II PUC/12th Marks Card: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='twelfthFileCMRBtech2018'          />
				<input type='hidden' name='twelfthFileCMRBtech2018Valid' value='extn:jpg,jpeg,png|size:5'>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				
				<div style='display:none'>
					<div class='errorMsg' id= 'twelfthFileCMRBtech2018_error'>
						
					</div>
				</div>
				<label id="twelfth_file">
					<?php if(isset($twelfthFileCMRBtech2018) && $twelfthFileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("twelfth_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Upload UG Passing Certificate or Marks Card: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='UGFileCMRBtech2018'          />
				<input type='hidden' name='UGFileCMRBtech2018Valid' value='extn:jpg,jpeg,png|size:5'>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				
				<div style='display:none'>
					<div class='errorMsg' id= 'UGFileCMRBtech2018_error'>
						
					</div>
				</div>
				<label id="UG_file">
					<?php if(isset($UGFileCMRBtech2018) && $UGFileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("UG_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Upload PG Passing Certificate or Marks Card: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='PGFileCMRBtech2018'          />
				<input type='hidden' name='PGFileCMRBtech2018Valid' value='extn:jpg,jpeg,png|size:5'>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				
				<div style='display:none'>
					<div class='errorMsg' id= 'PGFileCMRBtech2018_error'>
						
					</div>
				</div>
				<label id="PG_file">
					<?php if(isset($PGFileCMRBtech2018) && $PGFileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("PG_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Upload ID Proof: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='IDProofFileCMRBtech2018'          />
				<input type='hidden' name='IDProofFileCMRBtech2018Valid' value='extn:jpg,jpeg,png|size:5'>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				
				<div style='display:none'><div class='errorMsg' id= 'IDProofFileCMRBtech2018_error'></div></div>
				<label id="ID_file">
					<?php if(isset($IDProofFileCMRBtech2018) && $IDProofFileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("ID_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Upload Transfer Certificate: </label>
				<div class='fieldBoxLarge'>
				<input type='file' name='userApplicationfile[]' id='TCFileCMRBtech2018'          />
				<input type='hidden' name='TCFileCMRBtech2018Valid' value='extn:jpg,jpeg,png|size:5'>
				<span class="imageSizeInfo">Maximum Upload File Size 2 MB</span>
				
				<div style='display:none'><div class='errorMsg' id= 'TCFileCMRBtech2018_error'></div></div>
				<label id="TC_file">
					<?php if(isset($TCFileCMRBtech2018) && $TCFileCMRBtech2018!=""){ ?>
				  <script>
				      document.getElementById("TC_file").innerHTML = "<span style= 'color:green;'>File Uploaded.</span><span style='font-size:12px;color:grey'>Upload again if you want to replace existing file.</span>";
				  </script>
				<?php } ?>
				</label>
				</div>
				</div>
			</li>

			<li>
				<div class="cmr_title">
					<h2>Declaration</h2>
				</div>
					<div class='fieldBoxLarge' style="width:100%; color:#666666; font-style:italic; ">
						<ul>
						  <li> I do hereby confirm that the information provided above is true and correct. Further, in the event of my taking up admission at CMR University, I agree to abide by all the rules and regulations that may be in force at the institution. </li>
						</ul>
					</div>
					
					<div class='additionalInfoLeftCol'>
						
						<label style="width: 100%;text-align: left;">

							<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='CMR_agreeToTerms[]' id='CMR_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" >
								
							</input> I agree to the above Terms & Conditions
							<span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" >
								
							</span>&nbsp;&nbsp;

							<?php if(isset($CMR_agreeToTerms) && $CMR_agreeToTerms!=""){ ?>
							<script>
							    objCheckBoxes = document.forms["OnlineForm"].elements["CMR_agreeToTerms[]"];
							    var countCheckBoxes = objCheckBoxes.length;
							    for(var i = 0; i < countCheckBoxes; i++){
								      objCheckBoxes[i].checked = false;

								      <?php $arr = explode(",",$CMR_agreeToTerms);
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
							<div style='display:none'>
								<div class='errorMsg' id= 'CMR_agreeToTerms0_error' style="width: 365px;">
									
								</div>
							</div>
						</label>
					</div>
				</li>

		</ul>
	<?php }
	else
		{?>
		<label>You can not edit this form anymore.</label>
		<?php }
		?>
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


  function graduationShow(str)
  {
  	if(str=='Yes')
  	{
  		document.getElementById('graduationshow_Yes').style.display = 'block';
  	}
  	else
  	{
  		document.getElementById('graduationshow_Yes').style.display = 'none';
  		document.getElementById('graduationshow_Yes').classList.add('clearFields');
  	}
  }

  function UGModeOfStudy()
  {
  	var sel = document.getElementById("ExaminationTypeCMRBtech2018");
	var value = sel.options[sel.selectedIndex].value;
	if(value=="Semester")
	{
		document.getElementById('graduationyearshow').style.display = "none";
		document.getElementById('graduationsemestershow').style.display = "block";
		document.getElementById('graduationyearshow').classList.add('clearFields');
		document.getElementById('graduationsemestershow').classList.remove('clearFields');
	}
	else if(value=="Year")
	{
		document.getElementById('graduationyearshow').style.display = "block";
		document.getElementById('graduationsemestershow').style.display = "none";
		document.getElementById('graduationsemestershow').classList.add('clearFields');
		document.getElementById('graduationyearshow').classList.remove('clearFields');
	}
	else
	{
		document.getElementById('graduationyearshow').style.display = "none";
		document.getElementById('graduationsemestershow').style.display = "none";
		document.getElementById('graduationyearshow').classList.add('clearFields');
		document.getElementById('graduationsemestershow').classList.add('clearFields');
	}
  }

  function postGraduationShow(str)
  {
  	if(str=='Yes')
  	{
  		document.getElementById('postgraduationshow_Yes').style.display = 'block';
  	}
  	else
  	{
  		document.getElementById('postgraduationshow_Yes').style.display = 'none';
  		document.getElementById('postgraduationshow_Yes').classList.add('clearFields');
  	}
  }

  function PGModeOfStudy()
  {
  	var sel = document.getElementById("PGExaminationTypeCMRBtech2018");
	var value = sel.options[sel.selectedIndex].value;
	if(value=="Semester")
	{
		document.getElementById('postgraduationyearshow').style.display = "none";
		document.getElementById('postgraduationsemestershow').style.display = "block";
		document.getElementById('postgraduationyearshow').classList.add('clearFields');
		document.getElementById('postgraduationsemestershow').classList.remove('clearFields');
	}
	else if(value=="Year")
	{
		document.getElementById('postgraduationyearshow').style.display = "block";
		document.getElementById('postgraduationsemestershow').style.display = "none";
		document.getElementById('postgraduationyearshow').classList.remove('clearFields');
		document.getElementById('postgraduationsemestershow').classList.add('clearFields');
	}
	else
	{
		document.getElementById('postgraduationyearshow').style.display = "none";
		document.getElementById('postgraduationsemestershow').style.display = "none";
		document.getElementById('postgraduationyearshow').classList.add('clearFields');
		document.getElementById('postgraduationsemestershow').classList.add('clearFields');		
	}
  }

  function Result(str){  	
	if(str=="Yes")
  	{
  		document.getElementById('12th_Yes').style.display = "block";
  	}
  	else
  	{
  		document.getElementById('12th_Yes').style.display = "none";
  		document.getElementById('12th_Yes').classList.add('clearFields');
  	}
  }

  (function()
  {
  	Result("<?php echo $IsXIIResultOutCMRBtech2018;?>");
   	postGraduationShow("<?php echo $IsPGDegreeDeplomaDoneCMRBtech2018;?>");
   	graduationShow("<?php echo $IsDegreeDeplomaDoneCMRBtech2018;?>");
   	UGModeOfStudy();
   	PGModeOfStudy();

  })();

  function futureDate()
  {
  	var x = document.getElementById('dateOfIssuePassportCMRBtech2018').value;
  	var today = new Date();
	var dd = today.getDate();
	var mm = today.getMonth() + 1;

	var yyyy = today.getFullYear();
	if (dd < 10) {
	  dd = '0' + dd;
	} 
	if (mm < 10) {
	  mm = '0' + mm;
	} 
	var today = dd + '/' + mm + '/' + yyyy;
	console.log(today);

	if(!validateStr(x,10)){
        document.getElementById('dateOfIssuePassportCMRBtech2018_error').value = "Please select date in correct format of dd/mm/yyyy";
        document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "block";
    }

	var dateArray = x.split("/");
    if(dateArray.length < 3 ) {
        document.getElementById('dateOfIssuePassportCMRBtech2018_error').value = "Please select date in correct format of dd/mm/yyyy";
        document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "block";
    }
    var eneterdYear = dateArray[2];
    var eneterdMonth = dateArray[1];
    var eneterdDay = dateArray[0];
    if((validateInteger(eneterdYear,4) != true) ||( validateInteger(eneterdMonth,2) != true) || ( validateInteger(eneterdDay,2) != true)) {
        document.getElementById('dateOfIssuePassportCMRBtech2018_error').value = "Please select date in correct format of dd/mm/yyyy with all the values as numbers";
        document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "block";
    }
	
	if(yyyy>=eneterdYear)
	{
		if(yyyy==eneterdYear)
		{
			if(mm>=eneterdMonth)
			{
				if(mm==eneterdMonth)
				{
					if(dd>=eneterdDay)
					{
						document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "none";
					}
					else
					{
						document.getElementById('dateOfIssuePassportCMRBtech2018').value = '';
						document.getElementById('dateOfIssuePassportCMRBtech2018_error').innerHTML = "Please select date less than current date";
        				document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "block";
					}
				}
				else
				{
					document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "none";
				}
			}
			else
			{
				document.getElementById('dateOfIssuePassportCMRBtech2018').value = '';
				document.getElementById('dateOfIssuePassportCMRBtech2018_error').innerHTML = "Please select date less than current date";
        		document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "block";
			}
		}
		else
		{
			document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "none";
		}
	}
	else
	{
		document.getElementById('dateOfIssuePassportCMRBtech2018').value = '';
		document.getElementById('dateOfIssuePassportCMRBtech2018_error').innerHTML = "Please select date less than current date";
        document.getElementById('dateOfIssuePassportCMRBtech2018_errorhideshow').style.display = "block";
	}
  }

  </script>

  <style type="text/css">
  	.graduationshow .additionalInfoLeftCol{margin-bottom: 20px;min-height: 23px;}

  	.graduationyearshow .additionalInfoLeftCol{margin-bottom: 20px;}

  	.graduationsemestershow .additionalInfoLeftCol{margin-bottom: 20px;}

  	.postgraduationshow .additionalInfoLeftCol{margin-bottom: 20px;min-height: 23px;}

  	.postgraduationyearshow .additionalInfoLeftCol{margin-bottom: 20px;}

  	.postgraduationsemestershow .additionalInfoLeftCol{margin-bottom: 20px;}

  	.twe .additionalInfoLeftCol{margin-bottom: 20px;}
  	#appsFormWrapper .fieldBoxLarge input[type='text']{padding: 5px 3px;border-radius: 2px; }
  	.cmrform-wrapper .additionalInfoLeftCol {float: none;width: 458px;margin-bottom: 15px;display: inline-block;vertical-align: middle;}
  	.cmrform-wrapper .fieldBoxLarge{display: inline-block;float: none;vertical-align: middle;}
  	.cmrform-wrapper .fieldBoxLarge input[type='text']{padding: 5px 10px;}
  	.cmrform-wrapper .additionalInfoLeftCol label{width: 186px;text-align: left;}
  	.cmr_box {
    display: block;
}
.postgraduationyearshow{float: left;width: 100%;}
.postgraduationyearshow:before, .postgraduationyearshow:after, .cmr_box:before, .cmr_box:after {
    display: table;
    content: '';
}

.cmr_box:after, .postgraduationyearshow:after {
    clear: both;
}

.cmr_box .additionalInfoLeftCol {
    width: 22%;
    margin-right: 24px;
}

.cmr_box .additionalInfoLeftCol .fieldBoxLarge {
    width: 100%;
}

.cmr_box .additionalInfoLeftCol:last-child {
    margin-right: 0;
}

.cmr_box .additionalInfoLeftCol .fieldBoxLarge input[type='text'] {width: 100%;box-sizing: border-box;}
.cmr_box .additionalInfoLeftCol label {margin-bottom: 8px;width: 200px;}
.cmr_title h2{font-weight: 600;
    font-size: 16px;
    color: #000;
    background: #f9f9f9;
    padding: 10px 3px 10px 7px;}
 #appsFormWrapper .sub-section h3 {
    border: none;
    margin: 0 0 10px;
    font-size: 17px;
    font-weight: 600;
    padding: 10px 0px;
    text-align: left;
    border-top: 1px solid #e6e6e6;
}
.cmr_title {
    margin: 0 -10px;box-sizing: border-box;
}
#appsFormWrapper  .cmrform-wrapper select{width: 190px;background-color: #fff;padding: 5px 0px}
.cmrform-wrapper .additionalInfoLeftCol.ful-col{width: 100%;}
.cmrform-wrapper .additionalInfoLeftCol.ful-col label{width: 355px;}
#appsFormWrapper .cmrform-wrapper .additionalInfoLeftCol.ful-col select{width: 221px;}

.additionalInfoLeftCol label span{color: red;}
.additionalInfoRightCol label span{color: red;}
  </style>


