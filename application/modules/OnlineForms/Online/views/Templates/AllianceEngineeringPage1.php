<script>
function calculateAvgSubject(subject){
	$('Alliance_Agg'+subject).value = Math.round($('Alliance_ObtainedMarks'+subject).value*1000/$('Alliance_MaxMarks'+subject).value)/10;
	if($('Alliance_Agg'+subject).value == 'NaN' || $('Alliance_Agg'+subject).value == 'Infinity'){
		$('Alliance_Agg'+subject).value =  '';
	}
	$('Alliance_Agg'+subject).focus();
	$('Alliance_Agg'+subject).blur();
}

function calculateAvg(subject){
	$('Alliance_12thAggregate').value = Math.round($('Alliance_12thTotalMarks').value*1000/$('Alliance_12thMaxMarks').value)/10;
	if($('Alliance_12thAggregate').value == 'NaN' || $('Alliance_12thAggregate').value == 'Infinity'){
		$('Alliance_12thAggregate').value =  '';
	}
	$('Alliance_12thAggregate').focus();
	$('Alliance_12thAggregate').blur();
}

function validateAgregate(number, caption, maxLength, minLength){
	if(number == 'NAN' || number == 'INFINITY'  || number == ''){
		return 'Unable to calculate Aggregate. Please check marks.';
	}else if(number > 100){
		return 'Percentage cannot be more than 100. Please check marks.';
	}
	return true;

}

function openCitizenship(obj){
	if(obj){
		if(obj.value == "FOREIGN NATIONAL"){
			$('citizenship').style.display = '';
			$('Alliance_citizenship').setAttribute('required','true');
		}else{
			$('citizenship').style.display = 'none';
			$('Alliance_citizenship').removeAttribute('required');
		}
	}
}
</script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Course Preference</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>First Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Alliance_coursePref1' validate="validateSelect" caption="first preference" id='Alliance_coursePref1'    tip="Please select your first preference for the engineering stream."   title="First Preference"    required="true"    onmouseover="showTipOnline('Please select your first preference for the engineering stream.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Civil' >Civil</option><option value='Mechanical' >Mechanical</option><option value='Electrical and Electronics' >Electrical and Electronics</option><option value='Electronics and Communication' >Electronics and Communication</option><option value='Aerospace' >Aerospace</option><option value='Computer Science and Engineering' >Computer Science and Engineering</option><option value='Information Technology' >Information Technology</option></select>
				<?php if(isset($Alliance_coursePref1) && $Alliance_coursePref1!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_coursePref1"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_coursePref1;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_coursePref1_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Second Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Alliance_coursePref2' id='Alliance_coursePref2' validate="validateSelect" caption="second preference"    tip="Please select your second preference for the engineering stream."   title="Second Preference"    required="true"    onmouseover="showTipOnline('Please select your second preference for the engineering stream.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Civil' >Civil</option><option value='Mechanical' >Mechanical</option><option value='Electrical and Electronics' >Electrical and Electronics</option><option value='Electronics and Communication' >Electronics and Communication</option><option value='Aerospace' >Aerospace</option><option value='Computer Science and Engineering' >Computer Science and Engineering</option><option value='Information Technology' >Information Technology</option></select>
				<?php if(isset($Alliance_coursePref2) && $Alliance_coursePref2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_coursePref2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_coursePref2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_coursePref2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Third Preference: </label>
				<div class='fieldBoxLarge'>
				<select name='Alliance_coursePref3' id='Alliance_coursePref3' validate="validateSelect" caption="third preference"    tip="Please select your third preference for the engineering stream."   title="Third Preference"    required="true"    onmouseover="showTipOnline('Please select your third preference for the engineering stream.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Civil' >Civil</option><option value='Mechanical' >Mechanical</option><option value='Electrical and Electronics' >Electrical and Electronics</option><option value='Electronics and Communication' >Electronics and Communication</option><option value='Aerospace' >Aerospace</option><option value='Computer Science and Engineering' >Computer Science and Engineering</option><option value='Information Technology' >Information Technology</option></select>
				<?php if(isset($Alliance_coursePref3) && $Alliance_coursePref3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_coursePref3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_coursePref3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_coursePref3_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Entry Type: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   validate='validateCheckedGroup'  caption='entry type' required="true"   name='Alliance_entryType' id='Alliance_entryType0'   value='Direct'   title="Entry Type"   onmouseover="showTipOnline('Please select the entry type. If you want lateral entry or direct entry.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the entry type. If you want lateral entry or direct entry.',this);" onmouseout="hidetip();" >Direct</span>&nbsp;&nbsp;
				<input type='radio'   validate='validateCheckedGroup'  caption='entry type' required="true"   name='Alliance_entryType' id='Alliance_entryType1'   value='Lateral'    title="Entry Type"   onmouseover="showTipOnline('Please select the entry type. If you want lateral entry or direct entry.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the entry type. If you want lateral entry or direct entry.',this);" onmouseout="hidetip();" >Lateral</span>&nbsp;&nbsp;
				<?php if(isset($Alliance_entryType) && $Alliance_entryType!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Alliance_entryType"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Alliance_entryType;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_entryType_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Nationality: </label>
				<div class='fieldBoxLarge'>
				<select name='Alliance_nationality' onchange="openCitizenship(this);" id='Alliance_nationality'    tip="Please select your nationality."   title="Nationality"    required="true"    onmouseover="showTipOnline('Please select your nationality.',this);" onmouseout="hidetip();" ><option value='INDIAN' selected>INDIAN</option><option value='NRI' >NRI</option><option value='FOREIGN NATIONAL' >FOREIGN NATIONAL</option></select>
				<?php
				if(!in_array($Alliance_nationality,array('INDIAN','NRI','FOREIGN NATIONAL'))){
					$Alliance_nationality1 = 'FOREIGN NATIONAL';
				}
				if(isset($Alliance_nationality1) && $Alliance_nationality1!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_nationality"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_nationality1;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php }
					
				?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_nationality_error'></div></div>
				</div>
				
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Category under which admission is sought: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate='validateCheckedGroup'  caption='category'   required="true"   name='Alliance_category' id='Alliance_category0'   value='SC'   title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio'  validate='validateCheckedGroup'  caption='category'   required="true"   name='Alliance_category' id='Alliance_category1'   value='ST'    title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				<input type='radio'   validate='validateCheckedGroup'  caption='category'  required="true"   name='Alliance_category' id='Alliance_category2'   value='Others'    title="Category under which admission is sought"   onmouseover="showTipOnline('Please select your appropriate category. You can select only one category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate category. You can select only one category.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($Alliance_category) && $Alliance_category!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Alliance_category"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Alliance_category;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_category_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol' style="display:none" >
				<label>Citizenship: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_country' id='Alliance_country'  readonly    title="Citizenship"  value=''   />
				<?php if(isset($Alliance_country) && $Alliance_country!=""){ ?>
				  <script>
				      document.getElementById("Alliance_country").value = "<?php echo str_replace("\n", '\n', $Alliance_country );  ?>";
				      document.getElementById("Alliance_country").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_country_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol' style="display:none" id="citizenship">
				<label>Citizenship: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_citizenship' id='Alliance_citizenship'  validate="validateStr" maxlength="100" minlength="2" caption="citizenship"   title="Citizenship"  value=''   />
				<?php if(isset($Alliance_citizenship) && $Alliance_citizenship!=""){ ?>
				  <script>
				      document.getElementById("Alliance_citizenship").value = "<?php echo str_replace("\n", '\n', $Alliance_citizenship );  ?>";
				      document.getElementById("Alliance_citizenship").style.color = "";
				  </script>
				<?php }else{ ?>
				   <script>
					document.getElementById("Alliance_citizenship").value = '<?=$Alliance_nationality?>';
				   </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_citizenship_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Personal Information</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_fatherOrganization' maxlength="50" minlength="2" validate="validateStr" caption="father's organization" allowNA='true' id='Alliance_fatherOrganization'   required="true"        tip="Please enter your father's organization's name. If your father is self employed or if it does not apply to you, just enter <b>NA</b>."   title="Father's organization"  value=''   />
				<?php if(isset($Alliance_fatherOrganization) && $Alliance_fatherOrganization!=""){ ?>
				  <script>
				      document.getElementById("Alliance_fatherOrganization").value = "<?php echo str_replace("\n", '\n', $Alliance_fatherOrganization );  ?>";
				      document.getElementById("Alliance_fatherOrganization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_fatherOrganization_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Father's email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_fatherEmail'  maxlength="50" minlength="2" validate="validateEmail" caption="father's email" allowNA='true'  id='Alliance_fatherEmail'   required="true"        tip="Please enter your father's email address. If your father doesn't have an email address, just enter <b>NA</b>."   title="Father's email"  value=''   />
				<?php if(isset($Alliance_fatherEmail) && $Alliance_fatherEmail!=""){ ?>
				  <script>
				      document.getElementById("Alliance_fatherEmail").value = "<?php echo str_replace("\n", '\n', $Alliance_fatherEmail );  ?>";
				      document.getElementById("Alliance_fatherEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_fatherEmail_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_fatherMobile' id='Alliance_fatherMobile'  maxlength="10" minlength="10" validate="validateMobileInteger" caption="father's mobile"    required="true"        tip="Please enter the 10 digit mobile number of your father."   title="Father's Mobile"  value=''   />
				<?php if(isset($Alliance_fatherMobile) && $Alliance_fatherMobile!=""){ ?>
				  <script>
				      document.getElementById("Alliance_fatherMobile").value = "<?php echo str_replace("\n", '\n', $Alliance_fatherMobile );  ?>";
				      document.getElementById("Alliance_fatherMobile").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_fatherMobile_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's organization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_motherOrganization' id='Alliance_motherOrganization'   required="true"   maxlength="50" minlength="2" validate="validateStr" caption="mother's organization"  allowNA='true'     tip="Please enter your mother's organization's name. If your mother is self employed or if it does not apply to you, just enter <b>NA</b>."   title="Mother's organization"  value=''   />
				<?php if(isset($Alliance_motherOrganization) && $Alliance_motherOrganization!=""){ ?>
				  <script>
				      document.getElementById("Alliance_motherOrganization").value = "<?php echo str_replace("\n", '\n', $Alliance_motherOrganization );  ?>";
				      document.getElementById("Alliance_motherOrganization").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_motherOrganization_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Mother's email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_motherEmail' id='Alliance_motherEmail'  maxlength="50" minlength="2" validate="validateEmail" caption="mother's email" allowNA='true'  required="true"        tip="Please enter your mother's email address. If your mother doesn't have an email address, just enter <b>NA</b>."   title="Mother's email"  value=''   />
				<?php if(isset($Alliance_motherEmail) && $Alliance_motherEmail!=""){ ?>
				  <script>
				      document.getElementById("Alliance_motherEmail").value = "<?php echo str_replace("\n", '\n', $Alliance_motherEmail );  ?>";
				      document.getElementById("Alliance_motherEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_motherEmail_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Mobile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_motherMobile' id='Alliance_motherMobile'   required="true"     maxlength="10" minlength="10" validate="validateMobileInteger" caption="mother's mobile" allowNA="true"    tip="Please enter the 10 digit mobile number of your mother. In case your mother doesn't have a mobile number, just enter <b>NA</b>."   title="Mother's Mobile"  value=''   />
				<?php if(isset($Alliance_motherMobile) && $Alliance_motherMobile!=""){ ?>
				  <script>
				      document.getElementById("Alliance_motherMobile").value = "<?php echo str_replace("\n", '\n', $Alliance_motherMobile );  ?>";
				      document.getElementById("Alliance_motherMobile").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_motherMobile_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional education details</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>State from where you did class 10th?: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_10thState' id='Alliance_10thState'  maxlength="50" minlength="2" validate="validateStr" caption="state"   required="true"        tip="Please enter the name of state where you did your class 10th. For example Maharashtra, Madhya Pradesh etc."   title="State from where you did class 10th?"  value=''   />
				<?php if(isset($Alliance_10thState) && $Alliance_10thState!=""){ ?>
				  <script>
				      document.getElementById("Alliance_10thState").value = "<?php echo str_replace("\n", '\n', $Alliance_10thState );  ?>";
				      document.getElementById("Alliance_10thState").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_10thState_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Class 10th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_10thSubjects' id='Alliance_10thSubjects'  maxlength="200" minlength="2" validate="validateStr" caption="subjects"   required="true"        tip="Please enter the name of subjects that you studied in class 10th, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="Class 10th Subjects"  value=''   />
				<?php if(isset($Alliance_10thSubjects) && $Alliance_10thSubjects!=""){ ?>
				  <script>
				      document.getElementById("Alliance_10thSubjects").value = "<?php echo str_replace("\n", '\n', $Alliance_10thSubjects );  ?>";
				      document.getElementById("Alliance_10thSubjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_10thSubjects_error'></div></div>
				</div>
				</div>
			</li>
				
			<li>
				<div class='additionalInfoLeftCol'>
				<label>State from where you did class 12th?: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_12thState' id='Alliance_12thState'  maxlength="50" minlength="2" validate="validateStr" caption="state"   required="true"        tip="Please enter the name of state where you did your class 12th. For example Maharashtra, Madhya Pradesh etc."   title="State from where you did class 12th?"  value=''   />
				<?php if(isset($Alliance_12thState) && $Alliance_12thState!=""){ ?>
				  <script>
				      document.getElementById("Alliance_12thState").value = "<?php echo str_replace("\n", '\n', $Alliance_12thState );  ?>";
				      document.getElementById("Alliance_12thState").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thState_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<?php
			
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
	foreach($otherCourses as $key=>$course){
	?>
	<li>
				<div class='additionalInfoleftCol'>
				<label>State from where you did <?=$course?>?: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_mul_<?=$key?>_State' id='Alliance_mul_<?=$key?>_State'  maxlength="50" minlength="2" validate="validateStr" caption="state"   required="true"        tip="Please enter the name of state where you did your <?=$course?>. For example Maharashtra, Madhya Pradesh etc."   title="State from where you did <?=$course?>?"  value=''   />
				<?php if(${'Alliance_mul_'.$key.'_State'}){ ?>
				  <script>
				      document.getElementById("Alliance_mul_<?=$key?>_State").value = "<?php echo str_replace("\n", '\n', ${'Alliance_mul_'.$key.'_State'} );  ?>";
				      document.getElementById("Alliance_mul_<?=$key?>_State").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_mul_<?=$key?>_State_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label><?=$course?> Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_mul_<?=$key?>_Subjects' id='Alliance_mul_<?=$key?>_Subjects'  maxlength="200" minlength="2" validate="validateStr" caption="subjects"   required="true"        tip="Please enter the name of subjects that you studied in <?=$course?>, seperated by a comma. For example English, Hindi, Mathematics, Science etc."   title="<?=$course?> Subjects"  value=''   />
				<?php if(${'Alliance_mul_'.$key.'_Subjects'}){ ?>
				  <script>
				      document.getElementById("Alliance_mul_<?=$key?>_Subjects").value = "<?php echo str_replace("\n", '\n', ${'Alliance_mul_'.$key.'_Subjects'} );  ?>";
				      document.getElementById("Alliance_mul_<?=$key?>_Subjects").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_mul_<?=$key?>_Subjects_error'></div></div>
				</div>
				</div>
	</li>
	<?php
	}
	?>
	
	


			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Board: </label>
				<div class='fieldBoxLarge'>
				<select name='Alliance_12thboard' id='Alliance_12thboard' validate="validateSelect" caption="12th board"   tip="Please select your class 12th board of examination from the list."   title="Class 12th Board "    required="true"    onmouseover="showTipOnline('Please select your class 12th board of examination from the list.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='CBSE' >CBSE</option><option value='Foreign Board' >Foreign Board</option><option value='HSB' >HSB</option><option value='Other Indian Board' >Other Indian Board</option></select>
				<?php if(isset($Alliance_12thboard) && $Alliance_12thboard!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_12thboard"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_12thboard;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thboard_error'></div></div>
				</div>
				</div>
	
				<div class='additionalInfoRightCol'>
				<label>Class 12th Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_12thRollNumber' id='Alliance_12thRollNumber'   maxlength="10" minlength="2" validate="validateInteger" caption="roll number"  required="true"        tip="Please enter your class 12th roll number. If you're unsure about your roll number, refer your marksheet."   title="Class 12th Roll Number"  value=''   />
				<?php if(isset($Alliance_12thRollNumber) && $Alliance_12thRollNumber!=""){ ?>
				  <script>
				      document.getElementById("Alliance_12thRollNumber").value = "<?php echo str_replace("\n", '\n', $Alliance_12thRollNumber );  ?>";
				      document.getElementById("Alliance_12thRollNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thRollNumber_error'></div></div>
				</div>
				</div>
			</li>

			

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Status: </label>
				<div class='fieldBoxLarge'>
				<select name='Alliance_12thStatus' id='Alliance_12thStatus'    tip="Please mention your class 12th result status."  validate="validateSelect" caption="status" title="Class 12th Status"    required="true"    onmouseover="showTipOnline('Please mention your class 12th result status.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Pass' >Pass</option><option value='Fail' >Fail</option><option value='Compartment' >Compartment</option></select>
				<?php if(isset($Alliance_12thStatus) && $Alliance_12thStatus!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_12thStatus"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_12thStatus;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thStatus_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Class 12th Maximum Obtainable Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_12thMaxMarks' onchange="calculateAvg();" id='Alliance_12thMaxMarks'   required="true"     maxlength="4" minlength="1" validate="validateInteger" caption="max marks"     tip="Please enter the total maximum marks for all subjects in class 12th. This will be the sum of total marks against each subject. Refer your class 12th marksheet if you are unsure about this."   title="Class 12th Maximum Obtainable Marks"  value=''   />
				<?php if(isset($Alliance_12thMaxMarks) && $Alliance_12thMaxMarks!=""){ ?>
				  <script>
				      document.getElementById("Alliance_12thMaxMarks").value = "<?php echo str_replace("\n", '\n', $Alliance_12thMaxMarks );  ?>";
				      document.getElementById("Alliance_12thMaxMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thMaxMarks_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_12thTotalMarks' onchange="calculateAvg()" id='Alliance_12thTotalMarks'   required="true"      maxlength="4" minlength="1" validate="validateInteger" caption="total marks"    tip="Please enter the total marks obtained in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Class 12th Total Marks obtained"  value=''   />
				<?php if(isset($Alliance_12thTotalMarks) && $Alliance_12thTotalMarks!=""){ ?>
				  <script>
				      document.getElementById("Alliance_12thTotalMarks").value = "<?php echo str_replace("\n", '\n', $Alliance_12thTotalMarks );  ?>";
				      document.getElementById("Alliance_12thTotalMarks").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thTotalMarks_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Aggregate % marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' validate="validateAgregate" name='Alliance_12thAggregate' id='Alliance_12thAggregate'   title="Aggregate % marks" readonly   />
				<?php if(isset($Alliance_12thAggregate) && $Alliance_12thAggregate!=""){ ?>
				  <script>
				      //document.getElementById("Alliance_12thAggregate").value = "<?php echo str_replace("\n", '\n', $Alliance_12thAggregate );  ?>";
				      document.getElementById("Alliance_12thAggregate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_12thAggregate_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<h3 class="upperCase">CLASS XII SUBJECT DETAILS</h3>
			</li>
			<table style="border:1px solid #000" width="100%">
				<tr class="cutom-tr">
					<td>
						Subject
					</td>
					<td>
						Max. Marks
					</td>
					<td>
						Marks Obtained
					</td>
					<td>
						%
					</td>
				</tr>
				<tr class="cutom-tr">
					<td>
						Physics
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' onchange="calculateAvgSubject('Physics');"  maxlength="4" minlength="1" validate="validateInteger" caption="max marks"  name='Alliance_MaxMarksPhysics' id='Alliance_MaxMarksPhysics'   required="true"        tip="Please enter the total maximum marks for Physics in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks Physics"  value=''   />
				<?php if(isset($Alliance_MaxMarksPhysics) && $Alliance_MaxMarksPhysics!=""){ ?>
				  <script>
				      document.getElementById("Alliance_MaxMarksPhysics").value = "<?php echo str_replace("\n", '\n', $Alliance_MaxMarksPhysics );  ?>";
				      document.getElementById("Alliance_MaxMarksPhysics").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_MaxMarksPhysics_error'></div></div>
				</div>	
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' onchange="calculateAvgSubject('Physics');" name='Alliance_ObtainedMarksPhysics' id='Alliance_ObtainedMarksPhysics'   required="true"      maxlength="4" minlength="1" validate="validateInteger" caption="obtained marks"     tip="Please enter the total obtained marks for Physics in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks Physics"  value=''   />
				<?php if(isset($Alliance_ObtainedMarksPhysics) && $Alliance_ObtainedMarksPhysics!=""){ ?>
				  <script>
				      document.getElementById("Alliance_ObtainedMarksPhysics").value = "<?php echo str_replace("\n", '\n', $Alliance_ObtainedMarksPhysics );  ?>";
				      document.getElementById("Alliance_ObtainedMarksPhysics").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_ObtainedMarksPhysics_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' validate="validateAgregate" name='Alliance_AggPhysics' id='Alliance_AggPhysics'  readonly        title="Agg Physics"  value=''   />
				<?php if(isset($Alliance_AggPhysics) && $Alliance_AggPhysics!=""){ ?>
				  <script>
				      document.getElementById("Alliance_AggPhysics").value = "<?php echo str_replace("\n", '\n', $Alliance_AggPhysics );  ?>";
				      document.getElementById("Alliance_AggPhysics").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_AggPhysics_error'></div></div>
				</div>
					</td>

				</tr>
				<tr class="cutom-tr">
					<td>
						Mathematics
					</td>
					<td>
						<div class='fieldBoxLarge'>
						<input type='text'  onchange="calculateAvgSubject('Maths');" name='Alliance_MaxMarksMaths' id='Alliance_MaxMarksMaths'   maxlength="4" minlength="1" validate="validateInteger" caption="max marks"   required="true"        tip="Please enter the total maximum marks for Maths in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks Maths"  value=''   />
						<?php if(isset($Alliance_MaxMarksMaths) && $Alliance_MaxMarksMaths!=""){ ?>
						  <script>
							  document.getElementById("Alliance_MaxMarksMaths").value = "<?php echo str_replace("\n", '\n', $Alliance_MaxMarksMaths );  ?>";
							  document.getElementById("Alliance_MaxMarksMaths").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'Alliance_MaxMarksMaths_error'></div></div>
						</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_ObtainedMarksMaths' onchange="calculateAvgSubject('Maths');" id='Alliance_ObtainedMarksMaths'   required="true"   maxlength="4" minlength="1" validate="validateInteger" caption="obtained marks"        tip="Please enter the total obtained marks for Maths in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks Maths"  value=''   />
				<?php if(isset($Alliance_ObtainedMarksMaths) && $Alliance_ObtainedMarksMaths!=""){ ?>
				  <script>
				      document.getElementById("Alliance_ObtainedMarksMaths").value = "<?php echo str_replace("\n", '\n', $Alliance_ObtainedMarksMaths );  ?>";
				      document.getElementById("Alliance_ObtainedMarksMaths").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_ObtainedMarksMaths_error'></div></div>
				</div>
					</td>
					<td>
										<div class='fieldBoxLarge'>
				<input type='text' validate="validateAgregate" name='Alliance_AggMaths' id='Alliance_AggMaths' readonly title="Agg Maths"  value=''   />
				<?php if(isset($Alliance_AggMaths) && $Alliance_AggMaths!=""){ ?>
				  <script>
				      document.getElementById("Alliance_AggMaths").value = "<?php echo str_replace("\n", '\n', $Alliance_AggMaths );  ?>";
				      document.getElementById("Alliance_AggMaths").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_AggMaths_error'></div></div>
				</div>
					</td>

				</tr>
				<tr class="cutom-tr">
					<td>
						<div class='fieldBoxLarge'>
				<select name='Alliance_3rdSubject' id='Alliance_3rdSubject'  validate="validateSelect" caption="3rd subject"  tip="Please select 3rd subject out of Chemistry / Electronics/ Computer Science."   title="3rd Subject"    required="true"    onmouseover="showTipOnline('Please select 3rd subject out of Chemistry / Electronics / Computer Science.',this);" onmouseout="hidetip();" ><option value=''>Select</option><option value='Chemistry' >Chemistry</option><option value='Electronics' >Electronics</option><option value='Computer Science' >Computer Science</option></select>
				<?php if(isset($Alliance_3rdSubject) && $Alliance_3rdSubject!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Alliance_3rdSubject"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Alliance_3rdSubject;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_3rdSubject_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_MaxMarks3rdSubject' onchange="calculateAvgSubject('3rdSubject');" id='Alliance_MaxMarks3rdSubject'  maxlength="4" minlength="1" validate="validateInteger" caption="max marks"    required="true"        tip="Please enter the total maximum marks for 3rd Subject in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks 3rd Subject"  value=''   />
				<?php if(isset($Alliance_MaxMarks3rdSubject) && $Alliance_MaxMarks3rdSubject!=""){ ?>
				  <script>
				      document.getElementById("Alliance_MaxMarks3rdSubject").value = "<?php echo str_replace("\n", '\n', $Alliance_MaxMarks3rdSubject );  ?>";
				      document.getElementById("Alliance_MaxMarks3rdSubject").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_MaxMarks3rdSubject_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_ObtainedMarks3rdSubject' onchange="calculateAvgSubject('3rdSubject');" id='Alliance_ObtainedMarks3rdSubject'   required="true"     maxlength="4" minlength="1" validate="validateInteger" caption="obtained marks"      tip="Please enter the total obtained marks for 3rd Subject in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks 3rd Subject"  value=''   />
				<?php if(isset($Alliance_ObtainedMarks3rdSubject) && $Alliance_ObtainedMarks3rdSubject!=""){ ?>
				  <script>
				      document.getElementById("Alliance_ObtainedMarks3rdSubject").value = "<?php echo str_replace("\n", '\n', $Alliance_ObtainedMarks3rdSubject );  ?>";
				      document.getElementById("Alliance_ObtainedMarks3rdSubject").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_ObtainedMarks3rdSubject_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' validate="validateAgregate" name='Alliance_Agg3rdSubject' id='Alliance_Agg3rdSubject'   readonly      title="Agg 3rd Subject"  value=''   />
				<?php if(isset($Alliance_Agg3rdSubject) && $Alliance_Agg3rdSubject!=""){ ?>
				  <script>
				      document.getElementById("Alliance_Agg3rdSubject").value = "<?php echo str_replace("\n", '\n', $Alliance_Agg3rdSubject );  ?>";
				      document.getElementById("Alliance_Agg3rdSubject").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_Agg3rdSubject_error'></div></div>
				</div>
					</td>

				</tr>
				<tr class="cutom-tr">
					<td>
						English
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_MaxMarksEnglish' id='Alliance_MaxMarksEnglish'  onchange="calculateAvgSubject('English');" required="true"     maxlength="4" minlength="1" validate="validateInteger" caption="max marks"      tip="Please enter the total maximum marks for English in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Max Marks English"  value=''   />
				<?php if(isset($Alliance_MaxMarksEnglish) && $Alliance_MaxMarksEnglish!=""){ ?>
				  <script>
				      document.getElementById("Alliance_MaxMarksEnglish").value = "<?php echo str_replace("\n", '\n', $Alliance_MaxMarksEnglish );  ?>";
				      document.getElementById("Alliance_MaxMarksEnglish").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_MaxMarksEnglish_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_ObtainedMarksEnglish' id='Alliance_ObtainedMarksEnglish'   onchange="calculateAvgSubject('English');" required="true"      maxlength="4" minlength="1" validate="validateInteger" caption="obtained marks"     tip="Please enter the total obtained marks for English in class 12th. If you are unsure about this, please refer your class 12th marksheet."   title="Obtained Marks English"  value=''   />
				<?php if(isset($Alliance_ObtainedMarksEnglish) && $Alliance_ObtainedMarksEnglish!=""){ ?>
				  <script>
				      document.getElementById("Alliance_ObtainedMarksEnglish").value = "<?php echo str_replace("\n", '\n', $Alliance_ObtainedMarksEnglish );  ?>";
				      document.getElementById("Alliance_ObtainedMarksEnglish").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_ObtainedMarksEnglish_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' validate="validateAgregate" name='Alliance_AggEnglish' id='Alliance_AggEnglish'   readonly     title="Agg English"  value=''   />
				<?php if(isset($Alliance_AggEnglish) && $Alliance_AggEnglish!=""){ ?>
				  <script>
				      document.getElementById("Alliance_AggEnglish").value = "<?php echo str_replace("\n", '\n', $Alliance_AggEnglish );  ?>";
				      document.getElementById("Alliance_AggEnglish").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_AggEnglish_error'></div></div>
				</div>
				</td>

				</tr>
			</table>

			<li>
				
			</li>
	<?php endif; ?>		
			<li>
				<h3 class="upperCase">Qualifying exam details</h3>
			</li>
			
			<table style="border:1px solid #000" width="100%">
				<tr class="cutom-tr">
					<td>
						Examination
					</td>
					<td>
						Date
					</td>
					<td>
						Registration Number<br/>(if any)
					</td>
					<td>
						Score/Rank Obtained<br/>(Attach copy of rank certificate)
					</td>
				</tr>
				
				<tr class="cutom-tr">
					<td>
						JEE Main
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='jeeDateOfExaminationAdditional' id='jeeDateOfExaminationAdditional' readonly maxlength='10'  minlength='1' maxlength='10'  validate="validateDateForms" required="true"    caption='date'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='jeeDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($jeeDateOfExaminationAdditional) && $jeeDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("jeeDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $jeeDateOfExaminationAdditional );  ?>";
				      document.getElementById("jeeDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jeeDateOfExaminationAdditional_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='jeeRollNumberAdditional' id='jeeRollNumberAdditional'     maxlength="50" minlength="2" caption="Roll Number" validate="validateStr"  required="true"      tip="Mention your roll number for the exam."   value=''   />
				<?php if(isset($jeeRollNumberAdditional) && $jeeRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("jeeRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $jeeRollNumberAdditional );  ?>";
				      document.getElementById("jeeRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jeeRollNumberAdditional_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='jeeScoreAdditional' id='jeeScoreAdditional'     maxlength="7" minlength="1" caption="Paper 1 Marks" validate="validateInteger"   required="true"     tip="Mention your Paper 1 Marks in the exam."   value=''   />
				<?php if(isset($jeeScoreAdditional) && $jeeScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("jeeScoreAdditional").value = "<?php echo str_replace("\n", '\n', $jeeScoreAdditional );  ?>";
				      document.getElementById("jeeScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jeeScoreAdditional_error'></div></div>
				</div>
					</td>
				</tr>
				
				<tr class="cutom-tr">
					<td>
						JEE (Advanced)
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_jeeAdvancedDate' id='Alliance_jeeAdvancedDate' readonly maxlength='10'      minlength='1' maxlength='10'  validate="validateDateForms"    caption='date'     tip="Please enter JEE Advanced date, If applicable."   title="JEE Advanced Date"    onmouseover="showTipOnline('Please enter JEE Advanced date, If applicable.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('Alliance_jeeAdvancedDate'),'Alliance_jeeAdvancedDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='Alliance_jeeAdvancedDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('Alliance_jeeAdvancedDate'),'Alliance_jeeAdvancedDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($Alliance_jeeAdvancedDate) && $Alliance_jeeAdvancedDate!=""){ ?>
				  <script>
				      document.getElementById("Alliance_jeeAdvancedDate").value = "<?php echo str_replace("\n", '\n', $Alliance_jeeAdvancedDate );  ?>";
				      document.getElementById("Alliance_jeeAdvancedDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_jeeAdvancedDate_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_jeeAdvancedRegistrationNumber' id='Alliance_jeeAdvancedRegistrationNumber'        maxlength="50" minlength="2" caption="Roll Number" validate="validateStr"   tip="Please enter JEE Advanced Registration Number, If applicable."   title="JEE Advanced Registration Number"  value=''   />
				<?php if(isset($Alliance_jeeAdvancedRegistrationNumber) && $Alliance_jeeAdvancedRegistrationNumber!=""){ ?>
				  <script>
				      document.getElementById("Alliance_jeeAdvancedRegistrationNumber").value = "<?php echo str_replace("\n", '\n', $Alliance_jeeAdvancedRegistrationNumber );  ?>";
				      document.getElementById("Alliance_jeeAdvancedRegistrationNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_jeeAdvancedRegistrationNumber_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_jeeAdvancedScore' id='Alliance_jeeAdvancedScore'     maxlength="7" minlength="1" caption="score" validate="validateInteger"      tip="Please enter JEE Advanced Score, If applicable."   title="JEE Advanced Score"  value=''   />
				<?php if(isset($Alliance_jeeAdvancedScore) && $Alliance_jeeAdvancedScore!=""){ ?>
				  <script>
				      document.getElementById("Alliance_jeeAdvancedScore").value = "<?php echo str_replace("\n", '\n', $Alliance_jeeAdvancedScore );  ?>";
				      document.getElementById("Alliance_jeeAdvancedScore").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_jeeAdvancedScore_error'></div></div>
				</div>
					</td>
				</tr>
				
				<tr class="cutom-tr">
					<td>
						AUEET
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_AUEETDate' id='Alliance_AUEETDate' readonly maxlength='10'     minlength='1' maxlength='10'  validate="validateDateForms"  caption='date'      tip="Please enter AUEET date, If applicable."   title="AUEET Date"    onmouseover="showTipOnline('Please enter AUEET date, If applicable.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('Alliance_AUEETDate'),'Alliance_AUEETDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='Alliance_AUEETDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('Alliance_AUEETDate'),'Alliance_AUEETDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($Alliance_AUEETDate) && $Alliance_AUEETDate!=""){ ?>
				  <script>
				      document.getElementById("Alliance_AUEETDate").value = "<?php echo str_replace("\n", '\n', $Alliance_AUEETDate );  ?>";
				      document.getElementById("Alliance_AUEETDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_AUEETDate_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_AUEETRegistrationNumber' id='Alliance_AUEETRegistrationNumber'         tip="Please enter AUEET Registration Number, If applicable."  maxlength="50" minlength="2" caption="Roll Number" validate="validateStr"    title="AUEET Registration Number"  value=''   />
				<?php if(isset($Alliance_AUEETRegistrationNumber) && $Alliance_AUEETRegistrationNumber!=""){ ?>
				  <script>
				      document.getElementById("Alliance_AUEETRegistrationNumber").value = "<?php echo str_replace("\n", '\n', $Alliance_AUEETRegistrationNumber );  ?>";
				      document.getElementById("Alliance_AUEETRegistrationNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_AUEETRegistrationNumber_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_AUEETRank' id='Alliance_AUEETRank'      maxlength="7" minlength="1" caption="rank" validate="validateInteger"     tip="Please enter AUEET Rank, If applicable."   title="AUEET Score"  value=''   />
				<?php if(isset($Alliance_AUEETRank) && $Alliance_AUEETRank!=""){ ?>
				  <script>
				      document.getElementById("Alliance_AUEETRank").value = "<?php echo str_replace("\n", '\n', $Alliance_AUEETRank );  ?>";
				      document.getElementById("Alliance_AUEETRank").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_AUEETRank_error'></div></div>
				</div>
					</td>
				</tr>
				
				<tr class="cutom-tr">
					<td>
						Karnataka CET
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_KCETDate' id='Alliance_KCETDate' readonly maxlength='10'   minlength='1' maxlength='10'  validate="validateDateForms"     caption='date'        tip="Please enter KCET date, If applicable."   title="KCET Date"    onmouseover="showTipOnline('Please enter KCET date, If applicable.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('Alliance_KCETDate'),'Alliance_KCETDate_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='Alliance_KCETDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('Alliance_KCETDate'),'Alliance_KCETDate_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($Alliance_KCETDate) && $Alliance_KCETDate!=""){ ?>
				  <script>
				      document.getElementById("Alliance_KCETDate").value = "<?php echo str_replace("\n", '\n', $Alliance_KCETDate );  ?>";
				      document.getElementById("Alliance_KCETDate").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_KCETDate_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_KCETRegistrationNumber' id='Alliance_KCETRegistrationNumber'         tip="Please enter KCET Registration Number, If applicable."  maxlength="50" minlength="2" caption="Roll Number" validate="validateStr"   title="KCET Registration Number"  value=''   />
				<?php if(isset($Alliance_KCETRegistrationNumber) && $Alliance_KCETRegistrationNumber!=""){ ?>
				  <script>
				      document.getElementById("Alliance_KCETRegistrationNumber").value = "<?php echo str_replace("\n", '\n', $Alliance_KCETRegistrationNumber );  ?>";
				      document.getElementById("Alliance_KCETRegistrationNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_KCETRegistrationNumber_error'></div></div>
				</div>
					</td>
					<td>
						<div class='fieldBoxLarge'>
				<input type='text' name='Alliance_KCETRank' id='Alliance_KCETRank'       maxlength="7" minlength="1" caption="rank" validate="validateInteger"    tip="Please enter KCET Rank, If applicable."   title="KCET Score"  value=''   />
				<?php if(isset($Alliance_KCETRank) && $Alliance_KCETRank!=""){ ?>
				  <script>
				      document.getElementById("Alliance_KCETRank").value = "<?php echo str_replace("\n", '\n', $Alliance_KCETRank );  ?>";
				      document.getElementById("Alliance_KCETRank").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_KCETRank_error'></div></div>
				</div>
					</td>
				</tr>
			</table>

	<li>
			
				</li>
<?php if($action != 'updateScore'):?>

			<li>
				<h3 class="upperCase">Statement of purpose</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>What motivates you to apply to the Alliance College of Engineering and Design?: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Alliance_motivation' id='Alliance_motivation'   validate="validateStr" caption="motivation" maxlength="2000" minlength="20"  required="true"        tip="Please write a short essay on what motivates you to apply to the Alliance College of Engineering and Design?"   title="What motivates you to apply to the Alliance College of Engineering and Design?"   ></textarea>
				<?php if(isset($Alliance_motivation) && $Alliance_motivation!=""){ ?>
				  <script>
				      document.getElementById("Alliance_motivation").value = "<?php echo str_replace("\n", '\n', $Alliance_motivation );  ?>";
				      document.getElementById("Alliance_motivation").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_motivation_error'></div></div>
				</div>
				</div>
			</li>



			<li>
				<div class='additionalInfoLeftCol'>
				<label>What is your career vision and why is this choice meaningful to you?: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Alliance_vision' id='Alliance_vision' validate="validateStr" caption="career vision" maxlength="2000" minlength="20"  required="true"        tip="Please write a short essay on what is your career vision and why is this choice meaningful to you?"   title="What is your career vision and why is this choice meaningful to you?"   ></textarea>
				<?php if(isset($Alliance_vision) && $Alliance_vision!=""){ ?>
				  <script>
				      document.getElementById("Alliance_vision").value = "<?php echo str_replace("\n", '\n', $Alliance_vision );  ?>";
				      document.getElementById("Alliance_vision").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_vision_error'></div></div>
				</div>
				</div>
			</li>



			<li>
				<h3 class="upperCase">Hostel accomodation</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you need hostel accomodation?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' validate="validateCheckedGroup" caption="yes or no"  required="true"   name='Alliance_hostel' id='Alliance_hostel0'   value='Yes'   title="Do you need hostel accomodation?"   onmouseover="showTipOnline('Please mention if you need hostel accomodation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you need hostel accomodation.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' validate="validateCheckedGroup" caption="yes or no"  required="true"   name='Alliance_hostel' id='Alliance_hostel1'   value='No'    title="Do you need hostel accomodation?"   onmouseover="showTipOnline('Please mention if you need hostel accomodation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you need hostel accomodation.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Alliance_hostel) && $Alliance_hostel!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Alliance_hostel"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Alliance_hostel;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Alliance_hostel_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class=upperCase'>DECLARATION</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					1. I have read and understood the full requirements of the course, eligibility criteria, terms and conditions and
other important information as indicated in the Prospectus/website.<br/>
2. I confirm that the information furnished by me in this Application Form is true to the best of my knowledge. I
understand that any false or misleading statement given by me may lead to the cancellation of admission or
expulsion fromthe course at any stage.<br/>
3. I undertake to abide by the rules and regulations of the Alliance College of Engineering and Design as
prescribed from time to time. If I violate at any point of time any of the stipulated rules and regulations, then the
University is free to initiate appropriate disciplinary action against me.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked  required="true" caption="Please check to accept terms"   name='Alliance_agreeToTerms[]' id='Alliance_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($Alliance_agreeToTerms) && $Alliance_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Alliance_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Alliance_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'Alliance_agreeToTerms0_error'></div></div>
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
 if($('Alliance_12thAggregate')){

 calculateAvg();
 openCitizenship($('Alliance_nationality'));
  }
  </script>
  <style>
  .cutom-tr td{width:25%;vertical-align:top}
  .cutom-tr{
	height:50px;
  }
  </style>