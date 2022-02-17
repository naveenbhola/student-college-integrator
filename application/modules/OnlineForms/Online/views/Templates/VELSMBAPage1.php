<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");

	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    $(key+'2').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
		    <?php if($action == 'updateScore'){ ?>
		    document.getElementById('examsVELS_error').innerHTML = 'ONLY THE EXAMS CHECKED WILL BE UPDATED.';
		    document.getElementById('examsVELS_error').parentNode.style.display = '';
		    <?php } ?>
	      }
	      else{
		    $(key+'1').style.display = '';
		    $(key+'2').style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
	
  }

  function nationalityCheck(obj){
	var objects1 = new Array("passportVELS","visaPeriodVELS","validTillVELS");
	if(obj.value=='INDIAN'){
		$('passportInfo').style.display = 'none';
		$('passportInfo1').style.display = 'none';
		resetExamFields(objects1);
	}
	else if(obj.value=='FOREIGNER'){
	        $('passportInfo').style.display = '';
		$('passportInfo1').style.display = '';
	        setExamFields(objects1);
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

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>

			<li>
				<h3 class="upperCase">Course Preference</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Course Preference: </label>
				<div class='fieldBoxLarge' style="width:620px">
				<input type='checkbox' name='coursesVELS[]' id='coursesVELS0'   value='MBA in HR'   onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" ><span  onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" >MBA in HR, Marketing, Finance, Systems & Production</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='coursesVELS[]' id='coursesVELS1'   value='MBA in Logistics & Shipping Management'     onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" ><span  onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" >MBA in Logistics & Shipping Management (in association with IIL)</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='coursesVELS[]' id='coursesVELS2'   value='MBA in Logistics & Supply Chain Management'     onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" ><span  onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" >MBA in Logistics & Supply Chain Management (in association with CII)</span>
                <div class="spacer5 clearFix"></div>
				<input type='checkbox' name='coursesVELS[]' id='coursesVELS3'   value='MBA in International Travel & Tourism Management'     onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" ><span  onmouseover="showTipOnline('Please select the specialization that you are interested in. You can select multiple specializations.',this);" onmouseout="hidetip();" >MBA in International Travel & Tourism Management (in association with Kuoni Academy)</span>
                <div class="clearFix"></div>
				<?php if(isset($coursesVELS) && $coursesVELS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["coursesVELS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$coursesVELS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'coursesVELS_error'></div></div>
				</div>
				</div>
			</li>
	
			<li>
				<h3 class="upperCase">Additional Personal information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Blood group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='bloodGroupVELS' id='bloodGroupVELS'  validate="validateStr"   required="true"   caption="blood group"   minlength="2"   maxlength="4"     tip="Please enter your blood group. If you do not know your blood group, just enter <b>NA</b>"   value=''    allowNA = 'true' />
				<?php if(isset($bloodGroupVELS) && $bloodGroupVELS!=""){ ?>
				  <script>
				      document.getElementById("bloodGroupVELS").value = "<?php echo str_replace("\n", '\n', $bloodGroupVELS );  ?>";
				      document.getElementById("bloodGroupVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'bloodGroupVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother Tongue: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='motherTongueVELS' id='motherTongueVELS'  validate="validateStr"   required="true"   caption="mother tongue"   minlength="2"   maxlength="50"     tip="Please enter your mother tongue i.e. the language that you have grown up using."   value=''   />
				<?php if(isset($motherTongueVELS) && $motherTongueVELS!=""){ ?>
				  <script>
				      document.getElementById("motherTongueVELS").value = "<?php echo str_replace("\n", '\n', $motherTongueVELS );  ?>";
				      document.getElementById("motherTongueVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherTongueVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Religion: </label>
				<div class='fieldBoxLarge'>
				<select onchange="religionChange(this);" style="width:155px;" name='religionVELS' id='religionVELS'    tip="Please select your religion from the list. If you select Other, enter the specific value in the additional box."    validate="validateSelect"   required="true"   caption="religion"   onmouseover="showTipOnline('Please select your religion from the list. If you select Other, enter the specific value in the additional box.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Hindu' >Hindu</option><option value='Christian' >Christian</option><option value='Muslim' >Muslim</option><option value='Other' >Other</option></select>
				<?php if(isset($religionVELS) && $religionVELS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("religionVELS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $religionVELS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'religionVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Other Religion: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" disabled type='text' name='otherReligionVELS' id='otherReligionVELS'  validate="validateStr"    caption="religion"   minlength="2"   maxlength="50"      value=''   />
				<?php if(isset($otherReligionVELS) && $otherReligionVELS!=""){ ?>
				  <script>
				      document.getElementById('otherReligionVELS').disabled = false;
				      document.getElementById('otherReligionVELS').setAttribute('required','true');
				      document.getElementById("otherReligionVELS").value = "<?php echo str_replace("\n", '\n', $otherReligionVELS );  ?>";
				      document.getElementById("otherReligionVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherReligionVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Community: </label>
				<div class='fieldBoxLarge'>
				<select style="width:155px;" name='communityVELS' id='communityVELS'    tip="If you fall under any backward community, please select your community from the choices given."    validate="validateSelect"    caption="community"   onmouseover="showTipOnline('If you fall under any backward community, please select your community from the choices given.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='OC' >OC</option><option value='BC' >BC</option><option value='OBC' >OBC</option><option value='MBC' >MBC</option><option value='DNC' >DNC</option><option value='SC' >SC</option><option value='ST' >ST</option></select>
				<?php if(isset($communityVELS) && $communityVELS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("communityVELS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $communityVELS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'communityVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Caste: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='casteVELS' id='casteVELS'  validate="validateStr"   required="true"   caption="caste"   minlength="2"   maxlength="50"     tip="Please enter an appropriate caste. If this is not applicable in your case, just enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($casteVELS) && $casteVELS!=""){ ?>
				  <script>
				      document.getElementById("casteVELS").value = "<?php echo str_replace("\n", '\n', $casteVELS );  ?>";
				      document.getElementById("casteVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'casteVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Are you a handicapped: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='handicappedVELS' id='handicappedVELS0'   value='Yes'     onmouseover="showTipOnline('If you are a person with disabilities or handicaps, please select yes, otherwise select no.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you are a person with disabilities or handicaps, please select yes, otherwise select no.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='handicappedVELS' id='handicappedVELS1'   value='No'  checked   onmouseover="showTipOnline('If you are a person with disabilities or handicaps, please select yes, otherwise select no.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you are a person with disabilities or handicaps, please select yes, otherwise select no.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($handicappedVELS) && $handicappedVELS!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["handicappedVELS"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $handicappedVELS;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'handicappedVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Nationality: </label>
				<div class='fieldBoxLarge'>
				<input onClick="nationalityCheck(this);" type='radio' checked name='nationalityVELS' id='nationalityVELS0'   value='INDIAN'     onmouseover="showTipOnline('Select this option if you are an Indian national.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select this option if you are an Indian national.',this);" onmouseout="hidetip();" >INDIAN</span>&nbsp;&nbsp;
				<input onClick="nationalityCheck(this);" type='radio' name='nationalityVELS' id='nationalityVELS1'   value='FOREIGNER'  onmouseover="showTipOnline('Select this option if you are a foreign national. You will have to provide your Passport and Visa information.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select this option if you are a foreign national. You will have to provide your Passport and Visa information.',this);" onmouseout="hidetip();" >FOREIGNER</span>&nbsp;&nbsp;
				<div style='display:none'><div class='errorMsg' id= 'nationalityVELS_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="passportInfo" style="display:none;">
				<h3 class="upperCase">For foreign students</h3>
				<div class='additionalInfoLeftCol'>
				<label>Passport number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='passportVELS' id='passportVELS'  validate="validateStr"   required="true"   caption="passport number"   minlength="6"   maxlength="12"     tip="Enter your valid passport number."   value=''   />
				<?php if(isset($passportVELS) && $passportVELS!=""){ ?>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'passportVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Visa period: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='visaPeriodVELS' id='visaPeriodVELS'  validate="validateStr"   required="true"   caption="visa period"   minlength="2"   maxlength="20"     tip="Enter your visa period, example 6 months, 1 year, 3 years etc."   value=''   />
				<?php if(isset($visaPeriodVELS) && $visaPeriodVELS!=""){ ?>
				  <script>
				      document.getElementById("visaPeriodVELS").value = "<?php echo str_replace("\n", '\n', $visaPeriodVELS );  ?>";
				      document.getElementById("visaPeriodVELS").style.color = "";
				  </script>
				  <script>
				      document.getElementById("passportVELS").value = "<?php echo str_replace("\n", '\n', $passportVELS );  ?>";
				      document.getElementById("passportVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'visaPeriodVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li  id="passportInfo1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Valid Till: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='validTillVELS' id='validTillVELS' readonly validate="validateDateForms"   maxlength='10'   required="true"    caption="validity"    tip="Select the date till when your visa is valid"     onmouseover="showTipOnline('Select the date till when your visa is valid',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('validTillVELS'),'validTillVELS_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='validTillVELS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('validTillVELS'),'validTillVELS_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($validTillVELS) && $validTillVELS!=""){ ?>
				  <script>
				      document.getElementById("validTillVELS").value = "<?php echo str_replace("\n", '\n', $validTillVELS );  ?>";
				      document.getElementById("validTillVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'validTillVELS_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(isset($nationalityVELS) && $nationalityVELS!=""){ ?>
			  <script>
			      radioObj = document.forms["OnlineForm"].elements["nationalityVELS"];
			      var radioLength = radioObj.length;
			      for(var i = 0; i < radioLength; i++) {
				      radioObj[i].checked = false;
				      if(radioObj[i].value == "<?php echo $nationalityVELS;?>") {
					      radioObj[i].checked = true;
				      }
			      }
			      if("<?php echo $nationalityVELS;?>" == 'INDIAN'){
				      nationalityCheck(document.getElementById('nationalityVELS0'));
			      }
			      else if("<?php echo $nationalityVELS;?>" == 'FOREIGNER'){
				      nationalityCheck(document.getElementById('nationalityVELS1'));
			      }
			  </script>
			<?php } ?>

			<!-- First time when the form is getting filled -->
			<?php  if(isset($passportVELS) && $passportVELS!="" && !isset($nationalityVELS)){ ?>
			  <script>
				if("<?php echo $passportVELS;?>" == 'INDIAN'){
				      nationalityCheck(document.getElementById('nationalityVELS0'));
					document.getElementById('nationalityVELS0').checked = true;				
				}
				else if("<?php echo $passportVELS;?>" != 'INDIAN'){
				      nationalityCheck(document.getElementById('nationalityVELS1'));
					document.getElementById('nationalityVELS1').checked = true;				
				}
			  </script>
			<?php } ?>


			<li>
				<h3 class="upperCase">Additional Family Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Father's date of birth: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherDOBVELS' id='fatherDOBVELS' readonly validate="validateParentDateForms"   maxlength='10'   required="true"    caption="date of birth"    tip="Enter your father\'s date of birth"     onmouseover="showTipOnline('Enter your father\'s date of birth',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fatherDOBVELS'),'fatherDOBVELS_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='fatherDOBVELS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('fatherDOBVELS'),'fatherDOBVELS_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($fatherDOBVELS) && $fatherDOBVELS!=""){ ?>
				  <script>
				      document.getElementById("fatherDOBVELS").value = "<?php echo str_replace("\n", '\n', $fatherDOBVELS );  ?>";
				      document.getElementById("fatherDOBVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherDOBVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother's date of birth: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherDOBVELS' id='motherDOBVELS' readonly validate="validateParentDateForms"  maxlength='10'   required="true"     caption="date of birth"   tip="Enter your mother\'s date of birth"     onmouseover="showTipOnline('Enter your mother\'s date of birth',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('motherDOBVELS'),'motherDOBVELS_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='motherDOBVELS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('motherDOBVELS'),'motherDOBVELS_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($motherDOBVELS) && $motherDOBVELS!=""){ ?>
				  <script>
				      document.getElementById("motherDOBVELS").value = "<?php echo str_replace("\n", '\n', $motherDOBVELS );  ?>";
				      document.getElementById("motherDOBVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherDOBVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Is your Father Employed?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='fatherEmployedVELS' id='fatherEmployedVELS0'   value='Yes'  checked   onmouseover="showTipOnline('Please select an appropriate value for your father\'s employment status.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an appropriate value for your father\'s employment status.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='fatherEmployedVELS' id='fatherEmployedVELS1'   value='No'     onmouseover="showTipOnline('Please select an appropriate value for your father\'s employment status.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an appropriate value for your father\'s employment status.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;

				<?php if(isset($fatherEmployedVELS) && $fatherEmployedVELS!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["fatherEmployedVELS"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $fatherEmployedVELS;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherEmployedVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Father's annual income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherIncomeVELS' id='fatherIncomeVELS'  validate="validateStr"   required="true"   caption="income"   minlength="2"   maxlength="15"     tip="If your father is employed, please enter his annual income. Else enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($fatherIncomeVELS) && $fatherIncomeVELS!=""){ ?>
				  <script>
				      document.getElementById("fatherIncomeVELS").value = "<?php echo str_replace("\n", '\n', $fatherIncomeVELS );  ?>";
				      document.getElementById("fatherIncomeVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherIncomeVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Education (additional information about your education)</h3>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th month of passing: </label>
				<div class='fieldBoxLarge'>
				<select style="width:155px;" name='class10MonthVELS' id='class10MonthVELS'    tip="Please select the month of passing your class 10th examination. If you are unsure about this, refer your passing certificate or marksheet."    validate="validateSelect"   required="true"   caption="month"   onmouseover="showTipOnline('Please select the month of passing your class 10th examination. If you are unsure about this, refer your passing certificate or marksheet.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='01' >01</option><option value='02' >02</option><option value='03' >03</option><option value='04' >04</option><option value='05' >05</option><option value='06' >06</option><option value='07' >07</option><option value='08' >08</option><option value='09' >09</option><option value='10' >10</option><option value='11' >11</option><option value='12' >12</option></select>
				<?php if(isset($class10MonthVELS) && $class10MonthVELS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class10MonthVELS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class10MonthVELS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10MonthVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 10th Certificate number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10CertNoVELS' id='class10CertNoVELS'  validate="validateStr"   required="true"   caption="certificate number"   minlength="2"   maxlength="20"     tip="Please enter the certificate of marksheet serial number for this examination. Refer your marksheet or certificate for this number."   value=''   />
				<?php if(isset($class10CertNoVELS) && $class10CertNoVELS!=""){ ?>
				  <script>
				      document.getElementById("class10CertNoVELS").value = "<?php echo str_replace("\n", '\n', $class10CertNoVELS );  ?>";
				      document.getElementById("class10CertNoVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10CertNoVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th month of passing: </label>
				<div class='fieldBoxLarge'>
				<select style="width:155px;" name='class12MonthVELS' id='class12MonthVELS'    tip="Please select the month of passing your class 12th examination. If you are unsure about this, refer your passing certificate or marksheet."    validate="validateSelect"   required="true"   caption="month"   onmouseover="showTipOnline('Please select the month of passing your class 12th examination. If you are unsure about this, refer your passing certificate or marksheet.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='01' >01</option><option value='02' >02</option><option value='03' >03</option><option value='04' >04</option><option value='05' >05</option><option value='06' >06</option><option value='07' >07</option><option value='08' >08</option><option value='09' >09</option><option value='10' >10</option><option value='11' >11</option><option value='12' >12</option></select>
				<?php if(isset($class12MonthVELS) && $class12MonthVELS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class12MonthVELS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class12MonthVELS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12MonthVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 12th Certificate number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12CertNoVELS' id='class12CertNoVELS'  validate="validateStr"   required="true"   caption="certificate number"   minlength="2"   maxlength="20"     tip="Please enter the certificate of marksheet serial number for this examination. Refer your marksheet or certificate for this number."   value=''   />
				<?php if(isset($class12CertNoVELS) && $class12CertNoVELS!=""){ ?>
				  <script>
				      document.getElementById("class12CertNoVELS").value = "<?php echo str_replace("\n", '\n', $class12CertNoVELS );  ?>";
				      document.getElementById("class12CertNoVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12CertNoVELS_error'></div></div>
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
				<label><?php echo $graduationCourseName;?> month of passing: </label>
				<div class='fieldBoxLarge'>
				<select style="width:155px;" name='gradMonthVELS' id='gradMonthVELS'    tip="Please select the month of passing your <?php echo $graduationCourseName;?> examination. If you are unsure about this, refer your passing certificate or marksheet."    validate="validateSelect"   required="true"   caption="month"   onmouseover="showTipOnline('Please select the month of passing your <?php echo $graduationCourseName;?> examination. If you are unsure about this, refer your passing certificate or marksheet.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='01' >01</option><option value='02' >02</option><option value='03' >03</option><option value='04' >04</option><option value='05' >05</option><option value='06' >06</option><option value='07' >07</option><option value='08' >08</option><option value='09' >09</option><option value='10' >10</option><option value='11' >11</option><option value='12' >12</option></select>
				<?php if(isset($gradMonthVELS) && $gradMonthVELS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradMonthVELS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradMonthVELS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMonthVELS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Certificate number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradCertNoVELS' id='gradCertNoVELS'  validate="validateStr"   required="true"   caption="certificate number"   minlength="2"   maxlength="20"     tip="Please enter the certificate of marksheet serial number for this examination. Refer your marksheet or certificate for this number."   value=''   />
				<?php if(isset($gradCertNoVELS) && $gradCertNoVELS!=""){ ?>
				  <script>
				      document.getElementById("gradCertNoVELS").value = "<?php echo str_replace("\n", '\n', $gradCertNoVELS );  ?>";
				      document.getElementById("gradCertNoVELS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradCertNoVELS_error'></div></div>
				</div>
				</div>
			</li>


			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$month = 'otherCoursePGMonth_mul_'.$otherCourseId;
					$monthVal = $$month;
					$number = 'otherCourseNumber_mul_'.$otherCourseId;
					$numberVal = $$number;
					$i++;

			?>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName;?> month of passing: </label>
				<div class='fieldBoxLarge'>
				<select style="width:155px;" name='<?php echo $month; ?>' id='<?php echo $month; ?>'    tip="Please select the month of passing your <?php echo $otherCourseName;?> examination. If you are unsure about this, refer your passing certificate or marksheet."    validate="validateSelect"   required="true"   caption="month"   onmouseover="showTipOnline('Please select the month of passing your <?php echo $otherCourseName;?> examination. If you are unsure about this, refer your passing certificate or marksheet.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='01' >01</option><option value='02' >02</option><option value='03' >03</option><option value='04' >04</option><option value='05' >05</option><option value='06' >06</option><option value='07' >07</option><option value='08' >08</option><option value='09' >09</option><option value='10' >10</option><option value='11' >11</option><option value='12' >12</option>
				</select>
				<?php if(isset($monthVal) && $monthVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $month; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $monthVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $month; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $otherCourseName;?> Certificate number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $number; ?>' id='<?php echo $number; ?>'  validate="validateStr"   required="true"   caption="certificate number"   minlength="2"   maxlength="20"     tip="Please enter the certificate of marksheet serial number for this examination. Refer your marksheet or certificate for this number."   value=''   />
				<?php if(isset($numberVal) && $numberVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $number; ?>").value = "<?php echo str_replace("\n", '\n', $numberVal );  ?>";
				      document.getElementById("<?php echo $number; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $number; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<?php
				}
			}
			?>

			<?php endif; ?>

			<li>
				<h3 class="upperCase">Qualifying Examinations</h3>
				<div class='additionalInfoLeftCol'>
				<label>Exams: </label>
				<div class='fieldBoxLarge'>
				<input onclick="checkTestScore(this);" type='checkbox' name='examsVELS[]' id='examsVELS0'   value='CAT'    onmouseover="showTipOnline('Select the exam you have taken.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the exam you have taken.',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onclick="checkTestScore(this);" type='checkbox' name='examsVELS[]' id='examsVELS1'   value='MAT'     onmouseover="showTipOnline('Select the exam you have taken.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the exam you have taken.',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<?php if(isset($examsVELS) && $examsVELS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["examsVELS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$examsVELS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'examsVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'  validate="validateDateForms"  caption="date"        tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

				<div class='additionalInfoRightCol'>
				<label>CAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat" minlength="2" maxLength="8" caption="score"     allowNA='true'   tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''   />
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
				<div class='additionalInfoLeftCol'>
				<label>CAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   validate="validateStr" minlength="2" maxLength="20" caption="roll number"         tip="Mention your roll number for the exam."   value=''   />
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
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" minlength="2" maxLength="5" caption="percentile"    allowNA='true'     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''   />
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

			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

				<div class='additionalInfoRightCol'>
				<label>MAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat" minlength="2" maxLength="8" caption="score"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' />
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

			<li id="mat2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   validate="validateStr" minlength="2" maxLength="20" caption="roll number"         tip="Mention your roll number for the exam."   value=''   />
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
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat" minlength="2" maxLength="5" caption="percentile"         tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value='' allowNA='true'  />
				<?php if(isset($matPercentileAdditional) && $matPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("matPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $matPercentileAdditional );  ?>";
				      document.getElementById("matPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			<?php
			    if(isset($examsVELS) && $examsVELS!="" && strpos($examsVELS,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examsVELS0'));
			    </script>
			<?php
			    }
			?>
			<?php
			    if(isset($examsVELS) && $examsVELS!="" && strpos($examsVELS,'MAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('examsVELS1'));
			    </script>
			<?php
			    }
			?>

			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Additional extra curricular details</h3>
				<div class='additionalInfoLeftCol' style="width:100%;">
				<label>Your participation in extra curricular activities: </label>
				<div class='fieldBoxLarge' style="width:625px">
				<input type='checkbox' name='participationVELS[]' id='participationVELS0'   value='Sports & Games'  onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" >Sports & Games</span>&nbsp;&nbsp;
				<input type='checkbox' name='participationVELS[]' id='participationVELS1'   value='NCC'     onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" >NCC</span>&nbsp;&nbsp;
				<input type='checkbox' name='participationVELS[]' id='participationVELS2'   value='NSS'     onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" >NSS</span>&nbsp;&nbsp;
				<input type='checkbox' name='participationVELS[]' id='participationVELS3'   value='Scouts'     onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an approprite option for your participation in extra curricular activities. If you didn’t take part in any activity, just leave this unselected.',this);" onmouseout="hidetip();" >Scouts</span>&nbsp;&nbsp;
				<?php if(isset($participationVELS) && $participationVELS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["participationVELS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$participationVELS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'participationVELS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Do you require hostel facility: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='hostelVELS' id='hostelVELS0'   value='Yes'     onmouseover="showTipOnline('If you require hostel fecility, select YES. Otherwise select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you require hostel fecility, select YES. Otherwise select NO.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='hostelVELS' id='hostelVELS1'   value='No'  checked   onmouseover="showTipOnline('If you require hostel fecility, select YES. Otherwise select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('If you require hostel fecility, select YES. Otherwise select NO.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($hostelVELS) && $hostelVELS!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["hostelVELS"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $hostelVELS;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'hostelVELS_error'></div></div>
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
				<h3 class="upperCase">Disclaimer</h3>
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				I hereby affirm that the particulars given in this application form are true and correct to the best of my knowledge. If it is found at any stage that there is suppression, distortion, incorrect or false statement of data, I am aware of the fact that this may lead to my dismissal from the University and I would also be liable to make good any loss that may be caused to covert action. I also agree that I would lose all rights and claims consequently whatsoever. I further state that I shall not partake in any strike, demonstration or political activity. I agree that all disputes are subject to the jurisdiction of the court in Chennai only.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsVELS' id='agreeToTermsVELS' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsVELS) && $agreeToTermsVELS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsVELS"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsVELS);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsVELS_error'></div></div>


				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Which of your parent agrees to this disclaimer?: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsParentsVELS[]' id='agreeToTermsParentsVELS0'   value='Father'  checked   onmouseover="showTipOnline('Please select which of your parent agrees to this disclamer',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select which of your parent agrees to this disclamer',this);" onmouseout="hidetip();" >Father</span>&nbsp;&nbsp;
				<input type='checkbox' name='agreeToTermsParentsVELS[]' id='agreeToTermsParentsVELS1'   value='Mother'     onmouseover="showTipOnline('Please select which of your parent agrees to this disclamer',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select which of your parent agrees to this disclamer',this);" onmouseout="hidetip();" >Mother</span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsParentsVELS) && $agreeToTermsParentsVELS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsParentsVELS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$agreeToTermsParentsVELS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsParentsVELS_error'></div></div>
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

  function religionChange(obj){
      var selectedPref = obj.options[obj.selectedIndex].value;
      if(selectedPref == 'Other'){
	  $('otherReligionVELS').disabled = false;
	  $('otherReligionVELS').setAttribute('required','true');
	  $('otherReligionVELS_error').innerHTML = '';
	  $('otherReligionVELS_error').parentNode.style.display = 'none';
      }
      else{
	  $('otherReligionVELS').value = '';
	  $('otherReligionVELS').disabled = true;
	  $('otherReligionVELS').removeAttribute('required');
	  $('otherReligionVELS_error').innerHTML = '';
	  $('otherReligionVELS_error').parentNode.style.display = 'none';
      }

  }

  function validateParentDateForms(dateStr, caption, maxLength) {
      if(dateStr == ''){
	  return "Please select the "+ caption;
      } else if(!validateStr(dateStr,10)){
	  return "Please select date in correct format of dd/mm/yyyy";
      }
      var dateArray = dateStr.split("/");
      if(dateArray.length < 3 ) {
	  return "Please select date in correct format of dd/mm/yyyy";
      }
      var eneterdYear = dateArray[2];
      var eneterdMonth = dateArray[1];
      var eneterdDay = dateArray[0];
      if((validateInteger(eneterdYear,4) != true) ||( validateInteger(eneterdMonth,2) != true) || ( validateInteger(eneterdDay,2) != true)) {
	  return "Please select date in correct format of dd/mm/yyyy with all the values as numbers";
      }
      var currentDate = new Date();
      var currentYear = parseInt(currentDate.getFullYear());
	
      if((currentYear - eneterdYear) < 30) {
	  return "Age should be above 30";
      }
      return true;
  }

  </script>
