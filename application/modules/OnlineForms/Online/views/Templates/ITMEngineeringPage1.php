<script>
function validateAgeITM(number, caption, maxLength, minLength){
	var vaildateAge = validateInteger(number, caption, maxLength, minLength);
	if(vaildateAge === true){
		if(number > 21){
			return 'You are not eligible to apply as your age is more than 21 years as on 31st December 2013.';
		}else{
			return true;
		}
	}else{
		return vaildateAge;
	}
}

function calculateAvg(){
	$('ITM_12thAggregate').value = $('ITM_12thTotalMarks').value*100/$('ITM_12thMaxMarks').value;
	if($('ITM_12thAggregate').value == 'NaN' || $('ITM_12thAggregate').value == 'Infinity'){
		$('ITM_12thAggregate').value =  '';
	}
	$('ITM_12thAggregate').focus();
	$('ITM_12thAggregate').blur();
}

function validateAgregate(number, caption, maxLength, minLength){
	if(number == 'NAN' || number == 'INFINITY'  || number == ''){
		return 'Unable to calculate Aggregate. Please check marks.';
	}else if(number < 60){
		return "You are not eligible for this course as your aggregate is less than 60%";
	}else if(number > 100){
		return 'Percentage cannot be more than 100. Please check marks.';
	}
	
	return true;

}

function calulateTotalMarks(){
	$('ITM_MaxMarksTotal').value = parseInt($('ITM_MaxMarksMaths').value) + parseInt($('ITM_MaxMarksPhysics').value) + parseInt($('ITM_MaxMarksChemistry').value) + parseInt($('ITM_MaxMarksEnglish').value);
	if($('ITM_MaxMarksTotal').value == 'NaN' || $('ITM_12thAggregate').value == 'Infinity'){
		$('ITM_MaxMarksTotal').value =  '';
	}
}
function calulateObtainedMarks(){
	$('ITM_ObtainedMarksTotal').value = parseInt($('ITM_ObtainedMarksMaths').value) + parseInt($('ITM_ObtainedMarksPhysics').value) + parseInt($('ITM_ObtainedMarksChemistry').value) + parseInt($('ITM_ObtainedMarksEnglish').value);
	if($('ITM_ObtainedMarksTotal').value == 'NaN' || $('ITM_ObtainedMarksTotal').value == 'Infinity'){
		$('ITM_ObtainedMarksTotal').value =  '';
	}
}
function openCloseOthers(obj){
	if(obj){
		if(obj.checked){
		$('others').style.display = '';
		$('ITM_howKnowOthers').setAttribute('required','true');
		}else{
			$('others').style.display = 'none';
			$('ITM_howKnowOthers').removeAttribute('required');
		}
	}
}
</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	<?php if($action != 'updateScore'):?>
			<?php if(is_array($gdpiLocations) && count($gdpiLocations) > 1): ?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
						<optionvalue="<?php echo $gdpiLocation['city_id']; ?>"><?php echo $gdpiLocation['city_name']; ?></option>
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
				<h3 class="upperCase">Personal Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Age (as on 31st December 2013): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ITM_Age' id='ITM_Age' validate="validateAgeITM" maxlength="2" minlength="2" caption='age'   required="true"        tip="Please enter your age in years as on 31st December 2013. The age shall not be more than 21 years as on 31st December 2013."   title="Age (as on 31st December 2013)"  value=''   />
				<?php if(isset($ITM_Age) && $ITM_Age!=""){ ?>
				  <script>
				      document.getElementById("ITM_Age").value = "<?php echo str_replace("\n", '\n', $ITM_Age );  ?>";
				      document.getElementById("ITM_Age").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_Age_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Annual family income: </label>
				<div class='fieldBoxLarge'>
				<select name='ITM_annualIncome' id='ITM_annualIncome' validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="income"  tip="Please select your annual family income."   title="Annual family income"    required="true"    onmouseover="showTipOnline('Please select your annual family income.',this);" onmouseout="hidetip();" >
					<option value=''>Select</option><option value='Below Rs. 2 Lakh'>Below Rs. 2 Lakh</option><option value='Rs. 2 to 5 Lakhs' >Rs. 2 to 5 Lakhs</option><option value='Rs. 5 to 10 Lakhs' >Rs. 5 to 10 Lakhs</option><option value='Above Rs. 10 Lakhs' >Above Rs. 10 Lakhs</option></select>
				<?php if(isset($ITM_annualIncome) && $ITM_annualIncome!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_annualIncome"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_annualIncome;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_annualIncome_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Educational Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Board: </label>
				<div class='fieldBoxLarge'>
				<select name='ITM_12thboard' id='ITM_12thboard'    tip="Please select your class 12th board of examination from the list."   title="Class 12th Board "    required="true"    onmouseover="showTipOnline('Please select your class 12th board of examination from the list.',this);" onmouseout="hidetip();" ><option value='CBSE'>CBSE</option><option value='Foreign Borad' >Foreign Borad</option><option value='HSB' >HSB</option><option value='Other Indian Board' >Other Indian Board</option></select>
				<?php if(isset($ITM_12thboard) && $ITM_12thboard!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_12thboard"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_12thboard;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_12thboard_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 12th Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ITM_12thRollNumber' id='ITM_12thRollNumber'   required="true"    validate= 'validateInteger' maxlength="10" minlength='1'      tip="Please enter your class 12th roll number. If you're unsure about your roll number, refer your marksheet."   title="Class 12th Roll Number"  value=''   />
				<?php if(isset($ITM_12thRollNumber) && $ITM_12thRollNumber!=""){ ?>
				  <script>
				      document.getElementById("ITM_12thRollNumber").value = "<?php echo str_replace("\n", '\n', $ITM_12thRollNumber );  ?>";
				      document.getElementById("ITM_12thRollNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_12thRollNumber_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Status: </label>
				<div class='fieldBoxLarge'>
				<select name='ITM_12thStatus' id='ITM_12thStatus'    tip="Please mention your class 12th result status."   title="Class 12th Status"    required="true"    onmouseover="showTipOnline('Please mention your class 12th result status.',this);" onmouseout="hidetip();" ><option value='Pass'>Pass</option><option value='Fail' >Fail</option><option value='Compartment' >Compartment</option></select>
				<?php if(isset($ITM_12thStatus) && $ITM_12thStatus!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_12thStatus"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_12thStatus;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_12thStatus_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Class 12th Maximum Obtainable Marks: </label>
				<div class='fieldBoxLarge'>
				<input  onchange="calculateAvg();" type='text' name='ITM_12thMaxMarks' id='ITM_12thMaxMarks'   required="true"   validate= 'validateInteger' maxlength="4" minlength='1'  caption='max marks' required="true"           tip="Please enter the total maximum marks for all subjects in class 12th. This will be the sum of total marks against each subject. Refer your class 12th marksheet if you are unsure about this."   title="Class 12th Maximum Obtainable Marks"  value=''   />
				<?php if(isset($ITM_12thMaxMarks) && $ITM_12thMaxMarks!=""){ ?>
				  <script>
				      document.getElementById("ITM_12thMaxMarks").value = "<?php echo str_replace("\n", '\n', $ITM_12thMaxMarks );  ?>";
				      document.getElementById("ITM_12thMaxMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_12thMaxMarks_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input onchange="calculateAvg();" type='text' name='ITM_12thTotalMarks' id='ITM_12thTotalMarks' validate= 'validateInteger' maxlength="4" minlength='1'  caption='marks' required="true"        tip="Please enter the total marks obtained in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Class 12th Total Marks obtained"  value=''   />
				<?php if(isset($ITM_12thTotalMarks) && $ITM_12thTotalMarks!=""){ ?>
				  <script>
				      document.getElementById("ITM_12thTotalMarks").value = "<?php echo str_replace("\n", '\n', $ITM_12thTotalMarks );  ?>";
				      document.getElementById("ITM_12thTotalMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_12thTotalMarks_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Aggregate % marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ITM_12thAggregate' readonly id='ITM_12thAggregate'   validate= 'validateAgregate' maxlength="4" minlength='1'  caption='agg' required="true"         title="Aggregate % marks"  value=''   />
				<?php if(isset($ITM_12thAggregate) && $ITM_12thAggregate!=""){ ?>
				  <script>
				      //document.getElementById("ITM_12thAggregate").value = "<?php echo str_replace("\n", '\n', $ITM_12thAggregate );  ?>";
				      document.getElementById("ITM_12thAggregate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_12thAggregate_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Subject Details</h3>
			</li>
			
			<li>
				<table border="0" width="100%" cellspacing="0" cellpadding="10">
					<tr>
						<td valign="top">Subject</td>
						<td valign="top">Maths</td>
						<td valign="top">Physics</td>
						<td valign="top">Chemistry</td>
						<td valign="top">English</td>
						<td valign="top">Total of 4 Subjects</td>
					</tr>
					<tr>
						<td valign="top">Maximum Marks</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateTotalMarks();" type='text' name='ITM_MaxMarksMaths' id='ITM_MaxMarksMaths'   required="true"   validate= 'validateInteger' maxlength="4" minlength='1'  caption='max marks'     tip="Please enter the total maximum marks for Maths in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks Maths"  value=''   />
							<?php if(isset($ITM_MaxMarksMaths) && $ITM_MaxMarksMaths!=""){ ?>
							  <script>
								  document.getElementById("ITM_MaxMarksMaths").value = "<?php echo str_replace("\n", '\n', $ITM_MaxMarksMaths );  ?>";
								  document.getElementById("ITM_MaxMarksMaths").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_MaxMarksMaths_error'></div></div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateTotalMarks();" type='text' name='ITM_MaxMarksPhysics' id='ITM_MaxMarksPhysics'   required="true"   validate= 'validateInteger' maxlength="4" minlength='1'  caption='max marks'     tip="Please enter the total maximum marks for Physics in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks Physics"  value=''   />
							<?php if(isset($ITM_MaxMarksPhysics) && $ITM_MaxMarksPhysics!=""){ ?>
							  <script>
								  document.getElementById("ITM_MaxMarksPhysics").value = "<?php echo str_replace("\n", '\n', $ITM_MaxMarksPhysics );  ?>";
								  document.getElementById("ITM_MaxMarksPhysics").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_MaxMarksPhysics_error'></div></div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateTotalMarks();" type='text' name='ITM_MaxMarksChemistry' id='ITM_MaxMarksChemistry'   required="true"   validate= 'validateInteger' maxlength="4" minlength='1'  caption='max marks'     tip="Please enter the total maximum marks for Chemistry in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks Chemistry"  value=''   />
							<?php if(isset($ITM_MaxMarksChemistry) && $ITM_MaxMarksChemistry!=""){ ?>
							  <script>
								  document.getElementById("ITM_MaxMarksChemistry").value = "<?php echo str_replace("\n", '\n', $ITM_MaxMarksChemistry );  ?>";
								  document.getElementById("ITM_MaxMarksChemistry").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_MaxMarksChemistry_error'></div></div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateTotalMarks();" type='text' name='ITM_MaxMarksEnglish' id='ITM_MaxMarksEnglish'   required="true"   validate= 'validateInteger' maxlength="4" minlength='1'  caption='max marks'     tip="Please enter the total maximum marks for English in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks English"  value=''   />
							<?php if(isset($ITM_MaxMarksEnglish) && $ITM_MaxMarksEnglish!=""){ ?>
							  <script>
								  document.getElementById("ITM_MaxMarksEnglish").value = "<?php echo str_replace("\n", '\n', $ITM_MaxMarksEnglish );  ?>";
								  document.getElementById("ITM_MaxMarksEnglish").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_MaxMarksEnglish_error'></div></div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input type='text' name='ITM_MaxMarksTotal' id='ITM_MaxMarksTotal'   readonly  title="Max Marks Total"  value=''   />
							<?php if(isset($ITM_MaxMarksTotal) && $ITM_MaxMarksTotal!=""){ ?>
							  <script>
								  document.getElementById("ITM_MaxMarksTotal").value = "<?php echo str_replace("\n", '\n', $ITM_MaxMarksTotal );  ?>";
								  document.getElementById("ITM_MaxMarksTotal").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_MaxMarksTotal_error'></div></div>
							</div>
						</td>
					</tr>
					<tr>
						<td valign="top">Marks Obtained</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateObtainedMarks();"  type='text' name='ITM_ObtainedMarksMaths' id='ITM_ObtainedMarksMaths'   required="true"  validate= 'validateInteger' maxlength="4" minlength='1'  caption='marks'      tip="Please enter the total obtained marks for Maths in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks Maths"  value=''   />
							<?php if(isset($ITM_ObtainedMarksMaths) && $ITM_ObtainedMarksMaths!=""){ ?>
							  <script>
								  document.getElementById("ITM_ObtainedMarksMaths").value = "<?php echo str_replace("\n", '\n', $ITM_ObtainedMarksMaths );  ?>";
								  document.getElementById("ITM_ObtainedMarksMaths").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_ObtainedMarksMaths_error'></div></div>
							</div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateObtainedMarks();"   type='text' name='ITM_ObtainedMarksPhysics' id='ITM_ObtainedMarksPhysics'   required="true"  validate= 'validateInteger' maxlength="4" minlength='1'  caption='marks'      tip="Please enter the total obtained marks for Physics in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks Physics"  value=''   />
							<?php if(isset($ITM_ObtainedMarksPhysics) && $ITM_ObtainedMarksPhysics!=""){ ?>
							  <script>
								  document.getElementById("ITM_ObtainedMarksPhysics").value = "<?php echo str_replace("\n", '\n', $ITM_ObtainedMarksPhysics );  ?>";
								  document.getElementById("ITM_ObtainedMarksPhysics").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_ObtainedMarksPhysics_error'></div></div>
							</div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateObtainedMarks();" type='text' name='ITM_ObtainedMarksChemistry' id='ITM_ObtainedMarksChemistry'   required="true"  validate= 'validateInteger' maxlength="4" minlength='1'  caption='marks'      tip="Please enter the total obtained marks for Chemistry in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks Chemistry"  value=''   />
							<?php if(isset($ITM_ObtainedMarksChemistry) && $ITM_ObtainedMarksChemistry!=""){ ?>
							  <script>
								  document.getElementById("ITM_ObtainedMarksChemistry").value = "<?php echo str_replace("\n", '\n', $ITM_ObtainedMarksChemistry );  ?>";
								  document.getElementById("ITM_ObtainedMarksChemistry").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_ObtainedMarksChemistry_error'></div></div>
							</div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input onchange="calulateObtainedMarks();"  type='text' name='ITM_ObtainedMarksEnglish' id='ITM_ObtainedMarksEnglish'   required="true"  validate= 'validateInteger' maxlength="4" minlength='1'  caption='marks'      tip="Please enter the total obtained marks for English in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks English"  value=''   />
							<?php if(isset($ITM_ObtainedMarksEnglish) && $ITM_ObtainedMarksEnglish!=""){ ?>
							  <script>
								  document.getElementById("ITM_ObtainedMarksEnglish").value = "<?php echo str_replace("\n", '\n', $ITM_ObtainedMarksEnglish );  ?>";
								  document.getElementById("ITM_ObtainedMarksEnglish").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_ObtainedMarksEnglish_error'></div></div>
							</div>
							</div>
						</td>
						<td valign="top">
							<div class='fieldBoxLarge'>
							<input  type='text' name='ITM_ObtainedMarksTotal' id='ITM_ObtainedMarksTotal'     title="Obtained Marks Total"   readonly value=''   />
							<?php if(isset($ITM_ObtainedMarksTotal) && $ITM_ObtainedMarksTotal!=""){ ?>
							  <script>
								  document.getElementById("ITM_ObtainedMarksTotal").value = "<?php echo str_replace("\n", '\n', $ITM_ObtainedMarksTotal );  ?>";
								  document.getElementById("ITM_ObtainedMarksTotal").style.color = "";
							  </script>
							<?php } ?>
							
							<div style='display:none'><div class='errorMsg' id= 'ITM_ObtainedMarksTotal_error'></div></div>
							</div>
							</div>
						</td>
					</tr>
					
				</table>
			
			</li>

			<li>
				<h3 class="upperCase">Course Preference</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>First Preference: </label>
				<div class='fieldBoxLarge'>
				<select  name='ITM_coursePref1' id='ITM_coursePref1'    tip="Please select your first preference. If you do not have any preference for a course, select \'Not preferred\'"   title="First Preference"    required="true"    onmouseover="showTipOnline('Please select your first preference. If you do not have any preference for a course, select \'Not preferred\'',this);" onmouseout="hidetip();" ><option value='1'>Comp. Science & Engg</option><option value='2' >Electronics & Comm Engg</option><option value='3' >Mechanical Engineering</option><option value='4' >Information Technology</option><option value='5' >Civil Engineering</option><option value='6' >Electrical & Electronics</option><option value='Not Preferred' selected>Not Preferred</option></select>
				<?php if(isset($ITM_coursePref1) && $ITM_coursePref1!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_coursePref1"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_coursePref1;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref1_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Second Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='ITM_coursePref2' id='ITM_coursePref2'    tip="Please select your second preference. If you do not have any preference for a course, select \'Not preferred\'"   title="Second Preference"    required="true"    onmouseover="showTipOnline('Please select your second preference. If you do not have any preference for a course, select \'Not preferred\'',this);" onmouseout="hidetip();" ><option value='1'>Comp. Science & Engg</option><option value='2' >Electronics & Comm Engg</option><option value='3' >Mechanical Engineering</option><option value='4' >Information Technology</option><option value='5' >Civil Engineering</option><option value='6' >Electrical & Electronics</option><option value='Not Preferred' selected>Not Preferred</option></select>
				<?php if(isset($ITM_coursePref2) && $ITM_coursePref2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_coursePref2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_coursePref2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Third Preference: </label>
				<div class='fieldBoxLarge'>
				<select  name='ITM_coursePref3' id='ITM_coursePref3'    tip="Please select your third preference. If you do not have any preference for a course, select \'Not preferred\'."   title="Third Preference"    required="true"    onmouseover="showTipOnline('Please select your third preference. If you do not have any preference for a course, select \'Not preferred\'.',this);" onmouseout="hidetip();" ><option value='1'>Comp. Science & Engg</option><option value='2' >Electronics & Comm Engg</option><option value='3' >Mechanical Engineering</option><option value='4' >Information Technology</option><option value='5' >Civil Engineering</option><option value='6' >Electrical & Electronics</option><option value='Not Preferred' selected>Not Preferred</option></select>
				<?php if(isset($ITM_coursePref3) && $ITM_coursePref3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_coursePref3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_coursePref3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref3_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Fourth Preference: </label>
				<div class='fieldBoxLarge'>
				<select  name='ITM_coursePref4' id='ITM_coursePref4'    tip="Please select your fourth preference. If you do not have any preference for a course, select \'Not preferred\'."   title="Fourth Preference"    required="true"    onmouseover="showTipOnline('Please select your fourth preference. If you do not have any preference for a course, select \'Not preferred\'.',this);" onmouseout="hidetip();" ><option value='1'>Comp. Science & Engg</option><option value='2' >Electronics & Comm Engg</option><option value='3' >Mechanical Engineering</option><option value='4' >Information Technology</option><option value='5' >Civil Engineering</option><option value='6' >Electrical & Electronics</option><option value='Not Preferred' selected>Not Preferred</option></select>
				<?php if(isset($ITM_coursePref4) && $ITM_coursePref4!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_coursePref4"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_coursePref4;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref4_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Fifth Preference: </label>
				<div class='fieldBoxLarge'>
				<select  name='ITM_coursePref5' id='ITM_coursePref5'    tip="Please select your fifth preference. If you do not have any preference for a course, select \'Not preferred\'."   title="Fifth Preference"    required="true"    onmouseover="showTipOnline('Please select your fifth preference. If you do not have any preference for a course, select \'Not preferred\'.',this);" onmouseout="hidetip();" ><option value='1'>Comp. Science & Engg</option><option value='2' >Electronics & Comm Engg</option><option value='3' >Mechanical Engineering</option><option value='4' >Information Technology</option><option value='5' >Civil Engineering</option><option value='6' >Electrical & Electronics</option><option value='Not Preferred' selected>Not Preferred</option></select>
				<?php if(isset($ITM_coursePref5) && $ITM_coursePref5!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_coursePref5"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_coursePref5;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref5_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Sixth Preference: </label>
				<div class='fieldBoxLarge'>
				<select  name='ITM_coursePref6' id='ITM_coursePref6'    tip="Please select your sixth preference. If you do not have any preference for a course, select \'Not preferred\'."   title="Sixth Preference"     onmouseover="showTipOnline('Please select your sixth preference. If you do not have any preference for a course, select \'Not preferred\'.',this);" onmouseout="hidetip();" ><option value='1'>Comp. Science & Engg</option><option value='2' >Electronics & Comm Engg</option><option value='3' >Mechanical Engineering</option><option value='4' >Information Technology</option><option value='5' >Civil Engineering</option><option value='6' >Electrical & Electronics</option><option value='Not Preferred' selected>Not Preferred</option></select>
				<?php if(isset($ITM_coursePref6) && $ITM_coursePref6!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ITM_coursePref6"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ITM_coursePref6;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref6_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li>


				<div style='display:none'><div class='errorMsg' id= 'ITM_coursePref7_error'></div></div>

			</li>

			<li>
				<div style="margin-bottom:20px">
					<strong>Note:</strong> In Mechanical Engineering, students have a choice to opt for the 4 year B. Tech programme or the B.Tech. - M.Tech. 5 year dual degree programme.The 5 year dual degree programme will run only if 25 students opt for it. If less than 25 students opt for the said dual degree, the programme will run as a 4 year B.Tech Mechanical Engineering degree
				</div>
				<div class='additionalInfoLeftCol'>
				<label>Are you interested in dual degree in Mechanical Engineering(ME)?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true" validate='validateCheckedGroup'   caption='yes or no'   name='ITM_dualDegree' id='ITM_dualDegree0'   value='Y'  title="Are you interested in dual degree in Mechanical Engineering(ME)?"   onmouseover="showTipOnline('If you are interested in dual degree qualification please selct yes. Please first read the note at the beginning of this section about the details of dual degree admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you are interested in dual degree qualification please selct yes. Please first read the note at the beginning of this section about the details of dual degree admission.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'   required="true"  validate='validateCheckedGroup'   caption='yes or no'   name='ITM_dualDegree' id='ITM_dualDegree1'   value='N'    title="Are you interested in dual degree in Mechanical Engineering(ME)?"   onmouseover="showTipOnline('If you are interested in dual degree qualification please selct yes. Please first read the note at the beginning of this section about the details of dual degree admission.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you are interested in dual degree qualification please selct yes. Please first read the note at the beginning of this section about the details of dual degree admission.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($ITM_dualDegree) && $ITM_dualDegree!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ITM_dualDegree"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ITM_dualDegree;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_dualDegree_error'></div></div>
				</div>
				
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Category under which admission is sought: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"  validate='validateCheckedGroup'   caption='category'   name='ITM_category' id='ITM_category0'   value='All India'  title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" >All India</span>&nbsp;&nbsp;
				<br/><input type='radio'   required="true"  validate='validateCheckedGroup'   caption='category'   name='ITM_category' id='ITM_category1'   value='Haryana General'    title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" >Haryana General</span>&nbsp;&nbsp;
				<br/><input type='radio'   required="true"   validate='validateCheckedGroup'   caption='category'  name='ITM_category' id='ITM_category2'   value='Haryana SC'    title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" >Haryana SC</span>&nbsp;&nbsp;
				<br/><input type='radio'   required="true"  validate='validateCheckedGroup'   caption='category'   name='ITM_category' id='ITM_category3'   value='Kashmiri Migrants'    title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category. A Haryana domicile candidate may apply in All India Category if he/ she wishes to. However he/she can apply only under one Category. Terms, conditions, numbers and concessions of selected category will apply.',this);" onmouseout="hidetip();" >Kashmiri Migrants</span>&nbsp;&nbsp;
				<?php if(isset($ITM_category) && $ITM_category!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ITM_category"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ITM_category;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_category_error'></div></div>
				</div>
				</div>
				
			</li>

			<li>
				<h3 class="upperCase">Other Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you ever been convicted for any Criminal Offense?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' validate='validateCheckedGroup' caption='yes or no'    required="true"   name='ITM_criminal' id='ITM_criminal0'   value='Y'  title="Have you ever been convicted for any Criminal Offense?"   onmouseover="showTipOnline('Please mention if you have ever been convicted for any Criminal Offense. If not, select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you have ever been convicted for any Criminal Offense. If not, select NO.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' validate='validateCheckedGroup'  caption='yes or no'   required="true"   name='ITM_criminal' id='ITM_criminal1'   value='N'    title="Have you ever been convicted for any Criminal Offense?"   onmouseover="showTipOnline('Please mention if you have ever been convicted for any Criminal Offense. If not, select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you have ever been convicted for any Criminal Offense. If not, select NO.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($ITM_criminal) && $ITM_criminal!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ITM_criminal"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ITM_criminal;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_criminal_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Is there any case pending against you before Court/ Police/ School/ University?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate='validateCheckedGroup'   caption='yes or no'  required="true"   name='ITM_casePending' id='ITM_casePending0'   value='Y'  title="Is there any case pending against you before Court/ Police/ School/ University?"   onmouseover="showTipOnline('Please mention if there is any case pending against you before Court/ Police/ School/ University. If not, select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if there is any case pending against you before Court/ Police/ School/ University. If not, select NO.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate='validateCheckedGroup'   caption='yes or no'  required="true"   name='ITM_casePending' id='ITM_casePending1'   value='N'    title="Is there any case pending against you before Court/ Police/ School/ University?"   onmouseover="showTipOnline('Please mention if there is any case pending against you before Court/ Police/ School/ University. If not, select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if there is any case pending against you before Court/ Police/ School/ University. If not, select NO.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($ITM_casePending) && $ITM_casePending!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["ITM_casePending"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $ITM_casePending;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_casePending_error'></div></div>
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
			<li>
				<h3 class="upperCase">How did you know about us?</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>How did you know about us?: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'   validate='validateCheckedGroup'  caption='source'  required="true"    name='ITM_howKnow[]' id='ITM_howKnow0'   value='Alumni'  title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Alumni</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'  caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow1'   value='Counselor'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Counselor</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow2'   value='Current student of ITM'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Current student of ITM</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow3'   value='Friends'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Friends</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow4'   value='Relative'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Relative</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow5'   value='Internet'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Internet</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow6'   value='ITM Web-Site'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >ITM Web-Site</span>&nbsp;&nbsp;
				<br/><input type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow7'   value='News Paper'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >News Paper</span>&nbsp;&nbsp;
				<br/><input onchange="openCloseOthers(this);" type='checkbox' validate='validateCheckedGroup'   caption='source' required="true"    name='ITM_howKnow[]' id='ITM_howKnow8'   value='Others'    title="How did you know about us?"   onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your source to know us.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($ITM_howKnow) && $ITM_howKnow!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ITM_howKnow[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ITM_howKnow);
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
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_howKnow_error'></div></div>
				</div>
				</div>
			</li>

			<li id="others" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>If Others: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ITM_howKnowOthers' id='ITM_howKnowOthers' caption="source"  validate="validateStr" maxlength="50" minlength="2"      tip="Please enter other source."   title="How did you know about us?"  value=''   />
				<?php if(isset($ITM_howKnowOthers) && $ITM_howKnowOthers!=""){ ?>
				  <script>
				      document.getElementById("ITM_howKnowOthers").value = "<?php echo str_replace("\n", '\n', $ITM_howKnowOthers );  ?>";
				      document.getElementById("ITM_howKnowOthers").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ITM_howKnowOthers_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class=upperCase'>DECLARATION</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					1. I, hereby declare that all the particulars stated in this Application Form are true to the best of my knowledge and belief. 
<br/>2. I also affirm that I have read in detail the Admission Policy & Selection Procedure 2013 of ITM University including its fee structure and refund & cancellation policy before submitting this application and agree to unconditionally abide by the same. 
<br/>3. I understand that the decision of the University is final with regard to my admission. 
<br/>4. I promise to abide by the rules and regulations of the University as existing and as would be amended from time to time. 
<br/>5. If it is proved that I was admitted on false particulars and / or documents provided by me or my antecedents prove that my continuance in this University is not desirable, the University shall have the right to expel me from the University, besides being liable for legal action against me, at my cost. 
<br/>6. I agree that all disputes are subject to the jurisdiction of the court at Gurgaon only. 
<br/>7. I also understand that a student from a University / Board not recognized by CBSE/AICTE/UGC/AIU/MHRD or any other statutory body of Government of India shall not be eligible for admission.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked  required="true" caption="Please check to accept terms"   name='ITM_agreeToTerms[]' id='ITM_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($ITM_agreeToTerms) && $ITM_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ITM_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ITM_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'ITM_agreeToTerms0_error'></div></div>
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
  if($('ITM_howKnow8')){
 openCloseOthers($('ITM_howKnow8'));
 calculateAvg();
  }
  </script>
  <style>
  td{
	text-align:center;
	width:15%;
  }
  td .fieldBoxLarge{
	width: 150px !important;
  }
 
  </style>