<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "XAT" || obj.value == "CAT" || obj.value == "MAT" ){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	}
	else if(obj.value == "GMAT" || obj.value == "ATMA"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	}
	else if(obj.value == "CMAT"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"RankAdditional");
	}
	
	if(obj){
	      if( obj.checked == false ){
		    $(key+'1').style.display = 'none';
		    $(key+'2').style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key+'1').style.display = '';
		    $(key+'2').style.display = '';
		    //Set the required paramters when any Exam is shown
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
  }
  </script>

<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Program Applied</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Program applied for: </label>
				<div class='fieldBoxLarge' style="width:625px">
				<input type='radio' name='programAIMS' id='programAIMS0'   value='MBA' checked  onmouseover="showTipOnline('Please select your program preference.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select your program preference.',this);" onmouseout="hidetip();" >MBA</span>&nbsp;&nbsp;
				<input type='radio' name='programAIMS' id='programAIMS1'   value='PGDM'     onmouseover="showTipOnline('Please select your program preference.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select your program preference.',this);" onmouseout="hidetip();" >PGDM</span>&nbsp;&nbsp;
				<?php if(isset($programAIMS) && $programAIMS!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["programAIMS"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $programAIMS;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'programAIMS_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif; ?>

			<li> 
				<div class='additionalInfoLeftCol' style="width:930px;">
				<!---<label>Test Taken: </label>
				<div class='fieldBoxLarge'>
				<select required="true" minlength="1" maxlength="500" caption="tests" validate="validateStr" onchange="testChange(this);" name='QualifyingNameAIMS' id='QualifyingNameAIMS'  style="width:155px;"  tip="Select the test taken from the list."       onmouseover="showTipOnline('Select the test taken from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option>
					  <option value='CAT' >CAT</option>
					  <option value='MAT' >MAT</option>
					  <option value='XAT' >XAT</option>
					  <option value='CMAT' >CMAT</option>
					  <option value='GMAT' >GMAT</option>
				</select>
				-->
				
							<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input validate="validateCheckedGroup"   required="true"   caption="tests" onClick="checkTestScore(this);" type='checkbox' name='QualifyingNameAIMS[]' id='QualifyingNameAIMS0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input validate="validateCheckedGroup"   required="true"   caption="tests" onClick="checkTestScore(this);" type='checkbox' name='QualifyingNameAIMS[]' id='QualifyingNameAIMS1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input validate="validateCheckedGroup"   required="true"   caption="tests" onClick="checkTestScore(this);" type='checkbox' name='QualifyingNameAIMS[]' id='QualifyingNameAIMS2'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<input validate="validateCheckedGroup"   required="true"   caption="tests" onClick="checkTestScore(this);" type='checkbox' name='QualifyingNameAIMS[]' id='QualifyingNameAIMS3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input validate="validateCheckedGroup"   required="true"   caption="tests" onClick="checkTestScore(this);" type='checkbox' name='QualifyingNameAIMS[]' id='QualifyingNameAIMS4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input validate="validateCheckedGroup"   required="true"   caption="tests" onClick="checkTestScore(this);" type='checkbox' name='QualifyingNameAIMS[]' id='QualifyingNameAIMS5'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date, percentile and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<?php if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["QualifyingNameAIMS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$QualifyingNameAIMS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'QualifyingNameAIMS_error'></div></div>
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
		
		
		<?php
		    if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!="" && strpos($QualifyingNameAIMS,'CAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('QualifyingNameAIMS0'));
		    </script>
		<?php
		    }
		?>
	
	
	
	
	<li id="mat1" style="display:none;">
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">MAT Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='matDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
			<?php if(isset($matDateOfExaminationAdditional) && $matDateOfExaminationAdditional!=""){ ?>
					<script>
					document.getElementById("matDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $matDateOfExaminationAdditional );  ?>";
					document.getElementById("matDateOfExaminationAdditional").style.color = "";
					</script>
				  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'matDateOfExaminationAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">MAT Score: </label>
				<div class='float_L'  style="width:250px;">
					<input class="textboxSmaller" type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
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
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">MAT Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
						document.getElementById("matRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">MAT Percentile: </label>
				<div class='float_L'  style="width:250px;">
				   <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
		    if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!="" && strpos($QualifyingNameAIMS,'MAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('QualifyingNameAIMS1'));
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
		    if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!="" && strpos($QualifyingNameAIMS,'GMAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('QualifyingNameAIMS2'));
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
		
		
		<?php
		    if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!="" && strpos($QualifyingNameAIMS,'XAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('QualifyingNameAIMS3'));
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
		    if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!="" && strpos($QualifyingNameAIMS,'CMAT')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('QualifyingNameAIMS4'));
		    </script>
		<?php
		    }
		?>
				
				
				
				
				
			<!--	</div>
				</div> -->
				
  
			</li>

			<li id="atma1" style="display:none;">
			<div class="clearFix"></div>
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">ATMA Date of Examination: </label>
				<div class='float_L' style="width:125px;">
				<input class="calenderFields" type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"           onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout = "hidetip();"   onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='atmaDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($atmaDateOfExaminationAdditional) && $atmaDateOfExaminationAdditional!=""){ ?>
						<script>
						document.getElementById("atmaDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $atmaDateOfExaminationAdditional );  ?>";
						document.getElementById("atmaDateOfExaminationAdditional").style.color = "";
						</script>
					  <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'atmaDateOfExaminationAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">ATMA Score: </label>
				<div class='float_L'  style="width:250px;">
					<input class="textboxSmaller" type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'  validate="validateFloat" allowNA="true"  caption="Score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  />
					<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
							<script>
								document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
								document.getElementById("atmaScoreAdditional").style.color = "";
							</script>
							  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
			</div>
			
		</li>
		
		<li id="atma2" style="display:none;">
			<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">ATMA Roll No.: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the exam. If you do not have the roll number, enter NA"   value=''  />
				<?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
						<script>
						document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
						document.getElementById("atmaRollNumberAdditional").style.color = "";
						</script>
					  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
				</div>
			</div>
			
			<div class="additionalInfoRightCol">
				<label style="width:90px !important; font-weight:normal">ATMA Percentile: </label>
				<div class='float_L'  style="width:250px;">
				   <input class="textboxSmaller"  type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateInteger"  allowNA="true"  caption="Rank"   minlength="1"   maxlength="8"     tip="Mention your rank in the exam. If you don't know your rank, enter NA."   value=''  />
					<?php if(isset($atmaPercentileAdditional) && $atmaPercentileAdditional!=""){ ?>
							<script>
							document.getElementById("atmaPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $atmaPercentileAdditional);  ?>";
							document.getElementById("atmaPercentileAdditional").style.color = "";
							</script>
						  <?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'atmaPercentileAdditional_error'></div></div>
				</div>
			</div>
			
		</li>
		<?php
		    if(isset($QualifyingNameAIMS) && $QualifyingNameAIMS!="" && strpos($QualifyingNameAIMS,'ATMA')!==false){ ?>
		    <script>
			    checkTestScore(document.getElementById('QualifyingNameAIMS5'));
		    </script>
		<?php
		    }
		?>
		</li> 

			<!---<li> 
				<div class='additionalInfoLeftCol'> 
				<label>Percentile or Rank(in case of CMAT): </label> 
				<div class='fieldBoxLarge'> 
				<input type='text' name='percentageAIMS' id='percentageAIMS'  validate="validateFloat"   tip="Please enter your percentile or rank in the test."      caption="percentile or rank(in case of CMAT)"   minlength="1"   maxlength="8"      value=''   /> 
				<?php if(isset($percentageAIMS) && $percentageAIMS!=""){ ?> 
				  <script> 
				      document.getElementById("percentageAIMS").value = "<?php echo str_replace("\n", '\n', $percentageAIMS );  ?>"; 
				      document.getElementById("percentageAIMS").style.color = ""; 
				      document.getElementById("percentageAIMS").disabled = false; 
				  </script> 
				<?php } ?> 
				  
				<div style='display:none'><div class='errorMsg' id= 'percentageAIMS_error'></div></div> 
				</div> 
				</div> 
  
				<div class='additionalInfoRightCol'> 
				<label>Composite Score: </label>
				<div class='fieldBoxLarge'> 
				<input type='text' name='scoreAIMS' id='scoreAIMS'  validate="validateInteger"   tip="Please enter your composite score in the test."   caption="score"   minlength="2"   maxlength="6"      value=''   /> 
			-->	<?php if(isset($scoreAIMS) && $scoreAIMS!=""){ ?> 
				  <script> 
				      document.getElementById("scoreAIMS").value = "<?php echo str_replace("\n", '\n', $scoreAIMS);  ?>"; 
				      document.getElementById("scoreAIMS").style.color = ""; 
				      document.getElementById("scoreAIMS").disabled = false; 
				  </script> 
				<?php } ?> 
				  
				<div style='display:none'><div class='errorMsg' id= 'scoreAIMS_error'></div></div> 
				<!--</div> 
				</div> -->
			
			<li>
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
I understand that this registration is only to participate in AIMS selection process conducted by AIMS, Peenya, Bangalore, India. The admission 

procedures are exclusive of this and I will abide by the rules of admission, if offered admission into the specied course.<br>

I declare that the information given here is true and correct and I agree to accept the rules and regulations of the Institution and pay the prescribed 

fees in time on being admitted.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsAIMS' id='agreeToTermsAIMS' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsAIMS) && $agreeToTermsAIMS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsAIMS"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsAIMS);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsAIMS_error'></div></div>


				</div>
				</div>
			</li>

			


		</ul>
	</div>
</div><script>getCitiesForCountryOnline("",false,"",false);</script>
<script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>

  
  
  <script>
  function testChange(obj){
      var selectedPref = obj.options[obj.selectedIndex].value;
      if(selectedPref != ''){
	  $('percentageAIMS').disabled = false;
      $('percentageAIMS').setAttribute('required','true');
	  $('percentageAIMS_error').innerHTML = '';
	  $('percentageAIMS_error').parentNode.style.display = 'none';

	  $('scoreAIMS').disabled = false;
      $('scoreAIMS').setAttribute('required','true');
	  $('scoreAIMS_error').innerHTML = '';
	  $('scoreAIMS_error').parentNode.style.display = 'none';
      }
      else{
	  $('percentageAIMS').value = '';
	  $('percentageAIMS').disabled = true;
      $('percentageAIMS').removeAttribute('required');
	  $('percentageAIMS_error').innerHTML = '';
	  $('percentageAIMS_error').parentNode.style.display = 'none';

	  $('scoreAIMS').value = '';
	  $('scoreAIMS').disabled = true;
      $('scoreAIMS').removeAttribute('required');
	  $('scoreAIMS_error').innerHTML = '';
	  $('scoreAIMS_error').parentNode.style.display = 'none';
      }

      if(selectedPref == 'CAT'){
	  $('percentageAIMS').value = '<?php echo $catPercentileAdditional?>';
	  $('scoreAIMS').value = '<?php echo $catScoreAdditional?>';
      }
      else if(selectedPref == 'MAT'){
	  $('percentageAIMS').value = '<?php echo $matPercentileAdditional?>';
	  $('scoreAIMS').value = '<?php echo $matScoreAdditional?>';
      }
      else if(selectedPref == 'XAT'){
	  $('percentageAIMS').value = '<?php echo $xatPercentileAdditional?>';
	  $('scoreAIMS').value = '<?php echo $xatScoreAdditional?>';
      }

      else if(selectedPref == 'GMAT'){
	  $('percentageAIMS').value = '<?php echo $gmatPercentileAdditional?>';
	  $('scoreAIMS').value = '<?php echo $gmatScoreAdditional?>';
      }
      else if(selectedPref == 'CMAT'){
	  $('percentageAIMS').value = '<?php echo $cmatRankAdditional?>';
	  $('scoreAIMS').value = '<?php echo $cmatScoreAdditional?>';
      }else if(selectedPref == 'ATMA'){
	  $('percentageAIMS').value = '<?php echo $atmaPercentileAdditional?>';
	  $('scoreAIMS').value = '<?php echo $atmaScoreAdditional?>';
      }
      
  }
  </script>
