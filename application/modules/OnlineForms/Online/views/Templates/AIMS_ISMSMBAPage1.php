<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional",key+"RankAdditional",key+"PercentileAdditional");
	if(obj && $(key+"1")){
	      if( obj.checked == false ){
		    $(key+"2").style.display = 'none';
			$(key+"1").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+"2").style.display = '';
			$(key+"1").style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
	}
  }
  

  function setExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).setAttribute('required','true');
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }

  function resetExamFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).removeAttribute('required');
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }

  function checkSpecialisation(obj){
    if(obj.checked && obj.id=='AIMS_ISMS_course3'){
      $('specialization').style.display = '';
      /*for(i=0;i<=5;i++){
	      object = $('AIMS_ISMS_Specialisation'+i);
	      if(document.getElementById(object)){
		      document.getElementById(object).setAttribute('required','true');
	      }
      }*/
      document.getElementById('AIMS_ISMS_Specialisation_error').innerHTML = '';
      document.getElementById('AIMS_ISMS_Specialisation_error').parentNode.style.display = 'none';
    }
    else{
      $('specialization').style.display = 'none';
      /*for(i=0;i<=5;i++){
	      object = $('AIMS_ISMS_Specialisation'+i);
	      if(document.getElementById(object)){
		      document.getElementById(object).removeAttribute('required');
	      }
      }*/
      document.getElementById('AIMS_ISMS_Specialisation_error').innerHTML = '';
      document.getElementById('AIMS_ISMS_Specialisation_error').parentNode.style.display = 'none';
    }
  }
</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			
	
			<li>
				<h3 class="upperCase">Course Selection</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Course Applied For: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<input type='radio'  onClick="checkSpecialisation(this);" validate="validateCheckedGroup"   required="true"   caption="programme"   name='AIMS_ISMS_course[]' id='AIMS_ISMS_course0'   value='Extended Diploma in Strategic Management & Leadership (EDSML) - M.B.A (International) progression'   onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" >Extended Diploma in Strategic Management & Leadership (EDSML) - M.B.A (International) progression</span>&nbsp;&nbsp;
				<br/><input type='radio' onClick="checkSpecialisation(this);" validate="validateCheckedGroup"   required="true"   caption="programme"   name='AIMS_ISMS_course[]' id='AIMS_ISMS_course1'   value='Post Graduation Program (Extended) in Management with M.B.A   PGP(e) - MBA (UGC Approved)'     onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" >Post Graduation Program (Extended) in Management with M.B.A   PGP(e) - MBA (UGC Approved)</span>&nbsp;&nbsp;
				<br/><input type='radio' onClick="checkSpecialisation(this);"  validate="validateCheckedGroup"   required="true"   caption="programme"   name='AIMS_ISMS_course[]' id='AIMS_ISMS_course2'   value='Post Graduation Program in Management with M.B.A   PGP - MBA ( UGC Approved)'     onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" >Post Graduation Program in Management with M.B.A   PGP - MBA ( UGC Approved)</span>&nbsp;&nbsp;
				<br/><input type='radio' onClick="checkSpecialisation(this);" validate="validateCheckedGroup"   required="true"   caption="programme"   name='AIMS_ISMS_course[]' id='AIMS_ISMS_course3'   value='M.B.A PLUS (AICTE/UGC Approved)'     onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" >M.B.A PLUS (AICTE/UGC Approved)</span>&nbsp;&nbsp;
				    <div id="specialization" style="display: none;">
				    <br/><span style='margin-left:20px;'><input type='checkbox'  caption="Specialisation"   name='AIMS_ISMS_Specialisation[]' id='AIMS_ISMS_Specialisation0'   value='Marketing Management (MKT)'   onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" >Marketing Management (MKT)</span>&nbsp;&nbsp;</span>
				    <br/><span style='margin-left:20px;'><input type='checkbox'  caption="Specialisation"   name='AIMS_ISMS_Specialisation[]' id='AIMS_ISMS_Specialisation1'   value='Financial Management (FIN)'     onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" >Financial Management (FIN)</span>&nbsp;&nbsp;</span>
				    <br/><span style='margin-left:20px;'><input type='checkbox'  caption="Specialisation"   name='AIMS_ISMS_Specialisation[]' id='AIMS_ISMS_Specialisation2'   value='Info. Technology Management (IT)'     onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" >Info. Technology Management (IT)</span>&nbsp;&nbsp;</span>
				    <br/><span style='margin-left:20px;'><input type='checkbox'  caption="Specialisation"   name='AIMS_ISMS_Specialisation[]' id='AIMS_ISMS_Specialisation3'   value='Operations Management (OPE)'     onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" >Operations Management (OPE)</span>&nbsp;&nbsp;</span>
				    <br/><span style='margin-left:20px;'><input type='checkbox'  caption="Specialisation"   name='AIMS_ISMS_Specialisation[]' id='AIMS_ISMS_Specialisation4'   value='Intnl Business Management (IB)'     onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" >Intnl Business Management (IB)</span>&nbsp;&nbsp;</span>
				    <br/><span style='margin-left:20px;'><input type='checkbox'  caption="Specialisation"   name='AIMS_ISMS_Specialisation[]' id='AIMS_ISMS_Specialisation5'   value='H.R Management (HR)'     onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select specialisation.',this);" onmouseout="hidetip();" >H.R Management (HR)</span>&nbsp;&nbsp;</span>
				    <div style='display:none;margin-left:20px;'><div class='errorMsg' id= 'AIMS_ISMS_Specialisation_error'></div></div>
				    </div>
				<br/><input type='radio' onClick="checkSpecialisation(this);" validate="validateCheckedGroup"   required="true"   caption="programme"   name='AIMS_ISMS_course[]' id='AIMS_ISMS_course4'   value='Higher National Diploma - B.B.A (International) progression'     onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select course.',this);" onmouseout="hidetip();" >Higher National Diploma - B.B.A (International) progression</span>&nbsp;&nbsp;
				<?php if(isset($AIMS_ISMS_course) && $AIMS_ISMS_course!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["AIMS_ISMS_course[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$AIMS_ISMS_course);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }

				    objCheckBoxes = document.forms["OnlineForm"].elements["AIMS_ISMS_Specialisation[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$AIMS_ISMS_Specialisation);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes[i].checked = true;
							  }
					      <?php
						    }
					      ?>
				    }
				    
				    checkSpecialisation($('AIMS_ISMS_course3'));
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AIMS_ISMS_course_error'></div></div>
				</div>
				</div>
			</li>
			
			
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
	
			<?php endif; ?>
			<li>
				<h3 class="upperCase">Admission Test Results</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Examination Taken: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='AIMS_ISMS_testNames[]' id='AIMS_ISMS_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='AIMS_ISMS_testNames[]' id='AIMS_ISMS_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='AIMS_ISMS_testNames[]' id='AIMS_ISMS_testNames3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='AIMS_ISMS_testNames[]' id='AIMS_ISMS_testNames2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='AIMS_ISMS_testNames[]' id='AIMS_ISMS_testNames4'   value='MHCET'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MHCET</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='AIMS_ISMS_testNames[]' id='AIMS_ISMS_testNames5'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<?php if(isset($AIMS_ISMS_testNames) && $AIMS_ISMS_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["AIMS_ISMS_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$AIMS_ISMS_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'AIMS_ISMS_testNames_error'></div></div>
				</div>
				</div>
			</li>
			
			
			

			<li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
				<label>CAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

			<li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>CAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
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
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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
				<label>MAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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
				<label>MAT Date: </label>
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

			<li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>MAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label>MAT Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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
			

			<li id="xat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
				<?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
				      document.getElementById("xatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>XAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($xatDateOfExaminationAdditional) && $xatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $xatDateOfExaminationAdditional );  ?>";
				      document.getElementById("xatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="xat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>XAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
				      document.getElementById("xatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class="additionalInfoRightCol">
				<label>XAT Percentile: </label>
				<div class='float_L'>
				   <input class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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

			<li id="cmat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'         tip="Mention your roll number for the exam."   validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"    value=''   />
				<?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
				      document.getElementById("cmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>CMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='cmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($cmatDateOfExaminationAdditional) && $cmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $cmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("cmatDateOfExaminationAdditional").style.color = "";
				  </script>

				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="cmat2" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
				      document.getElementById("cmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($cmatRankAdditional) && $cmatRankAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRankAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRankAdditional );  ?>";
				      document.getElementById("cmatRankAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankAdditional_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="mhcet1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MHCET REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='mhcetRollNumberAdditional' id='mhcetRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
				<?php if(isset($mhcetRollNumberAdditional) && $mhcetRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("mhcetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetRollNumberAdditional );  ?>";
				      document.getElementById("mhcetRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>MHCET Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mhcetDateOfExaminationAdditional' id='mhcetDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('mhcetDateOfExaminationAdditional'),'mhcetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='mhcetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('mhcetDateOfExaminationAdditional'),'mhcetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($mhcetDateOfExaminationAdditional) && $mhcetDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("mhcetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetDateOfExaminationAdditional );  ?>";
				      document.getElementById("mhcetDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="mhcet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>MHCET Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mhcetScoreAdditional' id='mhcetScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($mhcetScoreAdditional) && $mhcetScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("mhcetScoreAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetScoreAdditional );  ?>";
				      document.getElementById("mhcetScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>MHCET Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mhcetPercentileAdditionalISMS' id='mhcetPercentileAdditionalISMS'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
				<?php if(isset($mhcetPercentileAdditionalISMS) && $mhcetPercentileAdditionalISMS!=""){ ?>
				  <script>
				      document.getElementById("mhcetPercentileAdditionalISMS").value = "<?php echo str_replace("\n", '\n', $mhcetPercentileAdditionalISMS );  ?>";
				      document.getElementById("mhcetPercentileAdditionalISMS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetPercentileAdditionalISMS_error'></div></div>
				</div>
				</div>
			</li>
			
			<li id="atma1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>ATMA REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
				<?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
				      document.getElementById("atmaRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>ATMA Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='atmaDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($atmaDateOfExaminationAdditional) && $atmaDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $atmaDateOfExaminationAdditional );  ?>";
				      document.getElementById("atmaDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="atma2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>ATMA Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
				      document.getElementById("atmaScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>ATMA Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
				<?php if(isset($atmaPercentileAdditional) && $atmaPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $atmaPercentileAdditional );  ?>";
				      document.getElementById("atmaPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaPercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
(i) I have read and understood all the rules and regulations of the admission and filled up the application for my admissions to the institute.
<br/>(ii) I understand that the course(s) that i am being enrolled for are combination programmes for which the institution, International School of
Management Studies, Pune has either a direct affiliation and accreditation or have associations with institutions which are approved and
affiliated by the concerned authorities / awarding bodies / universities.
<br/>(iii) The course fee for all courses include the prescribed fee structure by the respective certifying authorities/University/Institution and the
additional cost for the extra modules/educational services provided to the students.
<br/>(iv) The information furnished by me in the application is true to the best of my knowledge and belief. I understand that giving false or
incomplete information may lead to the refusal of my application or cancellation of the admission.
<br/>(v) I have not been debarred from appearing to any examination held by any government constituted or statutory authority in India or Abroad.
<br/>(vi) I hereby agree to abide by all rules, acts and laws in force by the government/university/institute and hereby undertake that as long
as i am student of the institute, I will do nothing inside or outside the institute which is against the rules and regulations of the institution
and may result in disciplinary action against me as per the prevailing rules, acts and laws.
<br/>(vii) I fully understand that the director of the Institute where i would be admitted will have full liberty to expel me from the institute for my
infringement of the rules of conduct and discipline, attendance and any of the information(s) given above and provided to me.
<br/>(viii) I give permission to the institution to obtain records and information from any educational institution previously attended by me.
<br/>(ix) I understand that the facility of accommodation provided by the institution is on a contract basis for a minimum duration of one academic
year and that if availed, I am liable to pay the fee for the whole period of contract even if i leave the premises provided to me in between my
term of contract.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='AIMS_ISMS_agreeToTerms[]' id='AIMS_ISMS_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($AIMS_ISMS_agreeToTerms) && $AIMS_ISMS_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["AIMS_ISMS_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$AIMS_ISMS_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'AIMS_ISMS_agreeToTerms0_error'></div></div>
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
  
    for(var j=0; j<6; j++){
		checkTestScore(document.getElementById('AIMS_ISMS_testNames'+j));
	}

  </script>
