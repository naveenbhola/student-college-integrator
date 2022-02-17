<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			
	<?php if($action != 'updateScore'):?>
	
			<li>
				<h3 class="upperCase">Personal Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Prefix: </label>
				<div class='fieldBoxLarge'>
				<select name='Lingaya_Prefix' id='Lingaya_Prefix'    tip="Please select the appropriate prefix"   title="Prefix"   validate="validateSelect"   required="true"   caption="prefix"   onmouseover="showTipOnline('Please select the appropriate prefix',this);" onmouseout="hidetip();" ><option value='Mr.' >Mr.</option><option value='Mrs.' >Mrs.</option><option value='Ms.' >Ms.</option><option value='Dr.' >Dr.</option><option value='Prof.' >Prof.</option></select>
				<?php if(isset($Lingaya_Prefix) && $Lingaya_Prefix!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Lingaya_Prefix"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Lingaya_Prefix;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_Prefix_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you belong to schedule cast?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Lingaya_SC' id='Lingaya_SC0'   value='Yes'   title="Do you belong to schedule cast?"   onmouseover="showTipOnline('Please specify if you belong to schedule caste. If you do, then please select YES.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please specify if you belong to schedule caste. If you do, then please select YES.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Lingaya_SC' id='Lingaya_SC1'   value='No'    title="Do you belong to schedule cast?"   onmouseover="showTipOnline('Please specify if you belong to schedule caste. If you do, then please select YES.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please specify if you belong to schedule caste. If you do, then please select YES.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Lingaya_SC) && $Lingaya_SC!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Lingaya_SC"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Lingaya_SC;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_SC_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Are you Physically Handicapped? : </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Lingaya_PH' id='Lingaya_PH0'   value='Yes'   title="Are you Physically Handicapped? "   onmouseover="showTipOnline('Please specify if you have a permanent physical disability If yes, then please select YES.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please specify if you have a permanent physical disability If yes, then please select YES.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="yes or no"  name='Lingaya_PH' id='Lingaya_PH1'   value='No'    title="Are you Physically Handicapped? "   onmouseover="showTipOnline('Please specify if you have a permanent physical disability If yes, then please select YES.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please specify if you have a permanent physical disability If yes, then please select YES.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Lingaya_PH) && $Lingaya_PH!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Lingaya_PH"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Lingaya_PH;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_PH_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Education Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Lingaya_10thMarks' id='Lingaya_10thMarks'  validate="validateInteger"   required="true"   caption="marks"   minlength="2"   maxlength="4"     tip="Please enter the total marks obtained in class 10th. If you are unsure about this, please refer your class 10th marksheet."   title="Class 10th Total Marks obtained"  value=''   />
				<?php if(isset($Lingaya_10thMarks) && $Lingaya_10thMarks!=""){ ?>
				  <script>
				      document.getElementById("Lingaya_10thMarks").value = "<?php echo str_replace("\n", '\n', $Lingaya_10thMarks );  ?>";
				      document.getElementById("Lingaya_10thMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_10thMarks_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Class10th Maximum Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Lingaya_10thMaxMarks' id='Lingaya_10thMaxMarks'  validate="validateInteger"   required="true"   caption="max marks"   minlength="2"   maxlength="4"     tip="Please enter the total maximum marks for all subjects in class 10th. This will be the sum of total marks against each subject. Refer your class 10th marksheet if you are unsure about this."   title="Class10th Maximum Marks"  value=''   />
				<?php if(isset($Lingaya_10thMaxMarks) && $Lingaya_10thMaxMarks!=""){ ?>
				  <script>
				      document.getElementById("Lingaya_10thMaxMarks").value = "<?php echo str_replace("\n", '\n', $Lingaya_10thMaxMarks );  ?>";
				      document.getElementById("Lingaya_10thMaxMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_10thMaxMarks_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th CGPA : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Lingaya_10thCGPA' id='Lingaya_10thCGPA'  validate="validateStr"   required="true"   caption="CGPA"   minlength="1"   maxlength="5"     tip="Enter the CGPA obtained in class 10th. If your board did not provode CGPA, then simply enter <b>NA</b>."   title="Class 10th CGPA "  value=''    allowNA = 'true' />
				<?php if(isset($Lingaya_10thCGPA) && $Lingaya_10thCGPA!=""){ ?>
				  <script>
				      document.getElementById("Lingaya_10thCGPA").value = "<?php echo str_replace("\n", '\n', $Lingaya_10thCGPA );  ?>";
				      document.getElementById("Lingaya_10thCGPA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_10thCGPA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Lingaya_12thMarks' id='Lingaya_12thMarks'  validate="validateInteger"   required="true"   caption="marks"   minlength="2"   maxlength="4"     tip="Please enter the total marks obtained in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Class 12th Total Marks obtained"  value=''   />
				<?php if(isset($Lingaya_12thMarks) && $Lingaya_12thMarks!=""){ ?>
				  <script>
				      document.getElementById("Lingaya_12thMarks").value = "<?php echo str_replace("\n", '\n', $Lingaya_12thMarks );  ?>";
				      document.getElementById("Lingaya_12thMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_12thMarks_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Class12th Maximum Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Lingaya_12thMaxMarks' id='Lingaya_12thMaxMarks'  validate="validateInteger"   required="true"   caption="max marks"   minlength="2"   maxlength="4"     tip="Please enter the total maximum marks for all subjects in class 12th. This will be the sum of total marks against each subject. Refer your class 12th marksheet if you are unsure about this."   title="Class12th Maximum Marks"  value=''   />
				<?php if(isset($Lingaya_12thMaxMarks) && $Lingaya_12thMaxMarks!=""){ ?>
				  <script>
				      document.getElementById("Lingaya_12thMaxMarks").value = "<?php echo str_replace("\n", '\n', $Lingaya_12thMaxMarks );  ?>";
				      document.getElementById("Lingaya_12thMaxMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_12thMaxMarks_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th CGPA : </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Lingaya_12thCGPA' id='Lingaya_12thCGPA'  validate="validateStr"   required="true"   caption="CGPA"   minlength="1"   maxlength="5"     tip="Enter the CGPA obtained in class 12th. If your board did not provode CGPA, then simply enter <b>NA</b>."   title="Class 12th CGPA "  value=''    allowNA = 'true' />
				<?php if(isset($Lingaya_12thCGPA) && $Lingaya_12thCGPA!=""){ ?>
				  <script>
				      document.getElementById("Lingaya_12thCGPA").value = "<?php echo str_replace("\n", '\n', $Lingaya_12thCGPA );  ?>";
				      document.getElementById("Lingaya_12thCGPA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_12thCGPA_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Course Preference</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>First Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Lingaya_coursePref1' id='Lingaya_coursePref1'    tip="Select First Preference."   title="First Preference"   validate="validateSelect"   required="true"   caption="Preference"   onmouseover="showTipOnline('Select First Preference.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Comp. Sc.'>Comp. Sc.</option><option value='Information Technology' >Information Technology</option><option value='Electronics & Comm' >Electronics & Comm</option><option value='Mechanical' >Mechanical</option><option value='Automobile' >Automobile</option><option value='Civil' >Civil</option><option value='Electrical' >Electrical</option><option value='Electrical & Electronics' >Electrical & Electronics</option></select>
				<?php if(isset($Lingaya_coursePref1) && $Lingaya_coursePref1!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Lingaya_coursePref1"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Lingaya_coursePref1;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_coursePref1_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Second Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Lingaya_coursePref2' id='Lingaya_coursePref2'    tip="Select Second Preference."   title="Second Preference"   validate="validateSelect"   required="true"   caption="Preference"   onmouseover="showTipOnline('Select Second Preference.',this);" onmouseout="hidetip();" ><option value='' >Select</option><option value='Comp. Sc.'>Comp. Sc.</option><option value='Information Technology' >Information Technology</option><option value='Electronics & Comm' >Electronics & Comm</option><option value='Mechanical' >Mechanical</option><option value='Automobile' >Automobile</option><option value='Civil' >Civil</option><option value='Electrical' >Electrical</option><option value='Electrical & Electronics' >Electrical & Electronics</option></select>
				<?php if(isset($Lingaya_coursePref2) && $Lingaya_coursePref2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Lingaya_coursePref2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Lingaya_coursePref2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_coursePref2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Third Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Lingaya_coursePref3' id='Lingaya_coursePref3'    tip="Select Third Preference."   title="Third Preference"   validate="validateSelect"   required="true"   caption="Preference"   onmouseover="showTipOnline('Select Third Preference.',this);" onmouseout="hidetip();" ><option value='' >Select</option><option value='Comp. Sc.'>Comp. Sc.</option><option value='Information Technology' >Information Technology</option><option value='Electronics & Comm' >Electronics & Comm</option><option value='Mechanical' >Mechanical</option><option value='Automobile' >Automobile</option><option value='Civil' >Civil</option><option value='Electrical' >Electrical</option><option value='Electrical & Electronics' >Electrical & Electronics</option></select>
				<?php if(isset($Lingaya_coursePref3) && $Lingaya_coursePref3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Lingaya_coursePref3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Lingaya_coursePref3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_coursePref3_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Fourth Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Lingaya_coursePref4' id='Lingaya_coursePref4'    tip="Select Fourth Preference."   title="Fourth Preference"   validate="validateSelect"   required="true"   caption="Preference"   onmouseover="showTipOnline('Select Fourth Preference.',this);" onmouseout="hidetip();" ><option value='' >Select</option><option value='Comp. Sc.'>Comp. Sc.</option><option value='Information Technology' >Information Technology</option><option value='Electronics & Comm' >Electronics & Comm</option><option value='Mechanical' >Mechanical</option><option value='Automobile' >Automobile</option><option value='Civil' >Civil</option><option value='Electrical' >Electrical</option><option value='Electrical & Electronics' >Electrical & Electronics</option></select>
				<?php if(isset($Lingaya_coursePref4) && $Lingaya_coursePref4!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Lingaya_coursePref4"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Lingaya_coursePref4;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_coursePref4_error'></div></div>
				</div>
				</div>
			</li>

	<?php endif;?>		
			
			<li>
				<h3 class="upperCase">JEE Details</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='jeeDateOfExaminationAdditional' id='jeeDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms" required="true"    caption='date'   tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='jeeDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($jeeDateOfExaminationAdditional) && $jeeDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("jeeDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $jeeDateOfExaminationAdditional );  ?>";
				      document.getElementById("jeeDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jeeDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Paper 1 Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='jeeScoreAdditional' id='jeeScoreAdditional' required="true"    maxlength="7" minlength="1" caption="Paper 1 Marks" validate="validateInteger"   tip="Mention your Paper 1 Marks in the exam."   value=''   />
				<?php if(isset($jeeScoreAdditional) && $jeeScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("jeeScoreAdditional").value = "<?php echo str_replace("\n", '\n', $jeeScoreAdditional );  ?>";
				      document.getElementById("jeeScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jeeScoreAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' required="true" name='jeeRollNumberAdditional' id='jeeRollNumberAdditional'  maxlength="50" minlength="2" caption="Roll Number" validate="validateStr"       tip="Mention your roll number for the exam."   value=''   />
				<?php if(isset($jeeRollNumberAdditional) && $jeeRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("jeeRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $jeeRollNumberAdditional );  ?>";
				      document.getElementById("jeeRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jeeRollNumberAdditional_error'></div></div>
				</div>
				</div>
			</li>
		<?php if($action != 'updateScore'):?>	
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>Interview/Councelling Location</h3>
				<label style='font-weight:normal'>Preferred Interview/Councelling Location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred Interview/Concelling location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred Interview/Concelling location">
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
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I declare that the particulars given above are true and correct to the best of my knowledge.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='Lingaya_agreeToTerms[]' id='Lingaya_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($Lingaya_agreeToTerms) && $Lingaya_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Lingaya_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Lingaya_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'Lingaya_agreeToTerms0_error'></div></div>
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