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
  
</script>
<div class='formChildWrapper'>
  
  <div id="instructions" style="font: normal 12px Arial, Helvetica, Sans Sarif; padding: 12px;">
            <h3>Eligibility</h3>
            <ul>
		<li>1. Consistent academic record with X, XII and UG > 60% and preferably no history of arrears in UG program.</li>
                <li>2. The normal minimum scores required in admission tests – CAT/XAT/CMAT > 60 percentile overall, MAT/ATMA> 80 percentile overall, TANCET > Normalized score will be fixed based on TANCET 2018 results.</li>
		<li>3. Exceptions, if any, can be made by Institution at it’s discretion based on overall profile of candidate.</li>
                <li>4. A scanned copy of mark sheets (X, XII and UG semester-wise) should be sent to mba.admissions@ssn.edu.in with 'online registration application number' in the subject of email. This should be done as soon as online application is registered.<br/>

Submission of X, XII and UG mark sheets (semester-wise) is mandatory. For those graduating in 2018, mark sheets should be sent up to pre-final semester. If the University has not issued the mark sheet, a web downloaded mark sheet is also accepted.<br/>

If unable to send photocopies of mark sheets by email, photocopies can be sent with a covering letter containing Shiksha registration number to:SSN School of Management
Old Mahabalipuram Road<br/>
Kalavakkam 603110 Tamil Nadu. </li>
		<li>5. Candidates with no history of arrears are given preference for GD/PI process. Candidates with up to two history of arrears can apply. However the invitation for GD/PI is at the sole discretion of the institution.</li>
		<li>6. Candidates with more than two history of arrears can write to mba.admissions@ssn.edu.in to get their academic credentials verified and seek advisory on their application process before initiating the online application. Institution reserves the right to allow or not allow application in such cases based on the past academic credentials.</li>
		<li>7. Several rounds of GD/PI will be held starting from December 2017 for admission to 2018 Batch. Please apply at the earliest to participate in GD/PI process. Do not wait for the last date for sending completed application.</li>
		<li>8. Applications received without mark sheets will be kept pending and not processed until they are properly submitted as per the guidelines given above. </li>
		<li>9. Application fee is non-refundable and no case of refund will be entertained.</li>
	    </ul>
  </div>

	<div class='formSection'>
		<ul>

			<li>
				<h3 class="upperCase">Entrance Test Scores</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SSN_testNames[]' id='SSN_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test Registration no. ,score ,percentile and date of examination (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SSN_testNames[]' id='SSN_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SSN_testNames[]' id='SSN_testNames2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='SSN_testNames[]' id='SSN_testNames3'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='SSN_testNames[]' id='SSN_testNames4'   value='TANCET'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" >TANCET</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='SSN_testNames[]' id='SSN_testNames5'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration ,score ,percentile and date of examination  (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				
				<?php if(isset($SSN_testNames) && $SSN_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["SSN_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$SSN_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_testNames_error'></div></div>
				</div>
				</div>
			</li>
			

			<li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
			
			<li id="atma1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>ATMA REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
			

			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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
				<div class='fieldBoxLarge'>
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
				<input type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
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
				<div class='fieldBoxLarge'>
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
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'         tip="Mention your regn no. for the exam."   validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"    value=''   />
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

			<li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
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
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your rank in the exam. If you don't know your rank, enter <b>NA.</b>"   value=''   allowNA='true' />
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
			
			<li id="tancet1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>TANCET REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='tancetRollNumberAdditional' id='tancetRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
				<?php if(isset($tancetRollNumberAdditional) && $tancetRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("tancetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $tancetRollNumberAdditional );  ?>";
				      document.getElementById("tancetRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'tancetRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>TANCET Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='tancetDateOfExaminationAdditional' id='tancetDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('tancetDateOfExaminationAdditional'),'tancetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='tancetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('tancetDateOfExaminationAdditional'),'tancetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($tancetDateOfExaminationAdditional) && $tancetDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("tancetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $tancetDateOfExaminationAdditional );  ?>";
				      document.getElementById("tancetDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'tancetDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="tancet2"  style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>TANCET Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='tancetScoreAdditional' id='tancetScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($tancetScoreAdditional) && $tancetScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("tancetScoreAdditional").value = "<?php echo str_replace("\n", '\n', $tancetScoreAdditional );  ?>";
				      document.getElementById("tancetScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'tancetScoreAdditional_error'></div></div>
				</div>
				</div>
				
			</li>
			
			
		      </ul>
		</div>
			
		<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<h3 class="upperCase">GD/PI Location</h3>
			
				<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select style="width:100px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
				</div>
			</li>
			<?php endif; ?>
        
			<li>
				<h3 class="upperCase">Personal Information</h3>
				</li>
			
			<li>
			  <div class='additionalInfoLeftCol' style="width:98%">
				<label>Community: </label>
				<div class='fieldBoxLarge' style="width:550px">
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='SSN_category' id='SSN_category0'   value='SC' title="Category"   onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='SSN_category' id='SSN_category1'   value='MBC'    title="Category"   onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" >MBC</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='SSN_category' id='SSN_category2'   value='ST'    title="Category"   onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='SSN_category' id='SSN_category3'   value='OC' title="Category"   onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" >OC</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the category"  name='SSN_category' id='SSN_category4'   value='BC' title="Category"   onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your appropriate community.',this);" onmouseout="hidetip();" >BC</span>&nbsp;&nbsp;
				
				<?php if(isset($SSN_category) && $SSN_category!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SSN_category"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SSN_category;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_category_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>ANNUAL INCOME OF THE FAMILY Rs: </label>
				<div class='fieldBoxSmall' style="width:190px;">
				<input class="textboxSmall" type='text' name='SSN_familyIncome' id='SSN_familyIncome'  validate="validateInteger"   required="true"   caption="annual family income"   minlength="2"   maxlength="10"     tip="Please enter your family's annual income"   title=" Annual Family Income"  value=''   />
				<?php if(isset($SSN_familyIncome) && $SSN_familyIncome!=""){ ?>
				  <script>
				      document.getElementById("SSN_familyIncome").value = "<?php echo str_replace("\n", '\n', $SSN_familyIncome );  ?>";
				      document.getElementById("SSN_familyIncome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_familyIncome_error'></div></div>
				</div>
				
		      </li>
			<li>
				<h3 class="upperCase">ACADEMIC INFORMATION regarding qualifying exam: </h3>
		      </li>
			
			<li>
			  <div class='additionalInfoLeftCol' style="width:98%">
				<div class='fieldBoxLarge' style="width:550px;margin-left:150px;">
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the type"  name='SSN_passed' id='SSN_passed0'   value='Passed' title="Passed"   onmouseover="showTipOnline('Please select your appropriate option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select appropriate option.',this);" onmouseout="hidetip();" >Passed</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the type"  name='SSN_passed' id='SSN_passed1'   value='Appearing' title="Passed"   onmouseover="showTipOnline('Please select your appropriate option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select appropriate option.',this);" onmouseout="hidetip();" >Appearing in Apr / May 2018</span>&nbsp;&nbsp;
				
				<?php if(isset($SSN_passed) && $SSN_passed!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SSN_passed"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SSN_passed;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_passed_error'></div></div>
				</div>
				</div>
			</li>
			<li>
			  <div class='additionalInfoLeftCol' style="width:98%">
				<div class='fieldBoxLarge' style="width:550px;margin-left:150px;">
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName0'   value='BE' title="BE"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);"></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BE</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName1'   value='BTech' title="BTech"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);"></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BTech</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName2'   value='BSc' title="BSc"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);" ></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BSc</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName3'   value='BCom' title="BCom"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);" ></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BCom</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName4'   value='BA' title="BA"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);" ></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BA</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName5'   value='BCA' title="BCA"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);" ></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BCA</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName6'   value='BBA' title="BBA"   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);" ></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >BBA</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the course name"  name='SSN_courseName' id='SSN_courseName7'   value='Others' title=""   onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" onclick="showOtherCourseField(this);"></input><span  onmouseover="showTipOnline('Please select appropriate course name.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				
				<?php if(isset($SSN_courseName) && $SSN_courseName!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SSN_courseName"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SSN_courseName;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_courseName_error'></div></div>
				</div>
				</div>
			</li>
			<li style="display: none;" id="SSN_otherSource_li">
				<div class='additionalInfoLeftCol' style="width:500px;">
				<label>Name of the other course: </label>
				<div class='fieldBoxLarge' style="width:190px;">
				<input class="textboxLarge" type='text' name='SSN_otherCourse' id='SSN_otherCourse'  validate="validateStr"  caption="name of other course"   minlength="2"   maxlength="100"     tip="Please enter name of other course."   title="Name of other course  "  value=''   />
				<?php if(isset($SSN_otherCourse) && $SSN_otherCourse!=""){ ?>
				  <script>
				      document.getElementById("SSN_otherCourse").value = "<?php echo str_replace("\n", '\n', $SSN_otherCourse );  ?>";
				      document.getElementById("SSN_otherCourse").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_otherCourse_error'></div></div>
				</div>
				</div>
			   
			</li>
			<li>
			  <div class='additionalInfoLeftCol'>
				<div class='fieldBoxLarge' style="margin-left:150px">
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the type"  name='SSN_courseType' id='SSN_courseType0'   value='Full Time' title="Full Time"   onmouseover="showTipOnline('Please select appropriate option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select appropriate option.',this);" onmouseout="hidetip();" >Full Time</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="the type"  name='SSN_courseType' id='SSN_courseType1'   value='Part Time' title="Part Time"   onmouseover="showTipOnline('Please select appropriate option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select appropriate option.',this);" onmouseout="hidetip();" >Part Time</span>&nbsp;&nbsp;
				
				<?php if(isset($SSN_courseType) && $SSN_courseType!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["SSN_courseType"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $SSN_courseType;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_courseType_error'></div></div>
				</div>
				</div>
			</li>
		      
		      <li>
				<div class='additionalInfoLeftCol' style="width:500px;">
				<label>UG Arrears: </label>
				<div class='fieldBoxSmall' style="width:190px;">
				<input class="textboxSmall" type='text' name='SSN_arrearGrad' id='SSN_arrearGrad'  validate="validateInteger" required="true"   caption="no of arrears in graduation"   minlength="1"   maxlength="2"     tip="Please enter your graduation arrears"   title="Graduation Arrears"  value=''   />
				<?php if(isset($SSN_arrearGrad) && $SSN_arrearGrad!=""){ ?>
				  <script>
				      document.getElementById("SSN_arrearGrad").value = "<?php echo str_replace("\n", '\n', $SSN_arrearGrad );  ?>";
				      document.getElementById("SSN_arrearGrad").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_arrearGrad_error'></div></div>
				</div>
				</div>
				
		      </li>
      
		     <li>
				<p style="color:red">Candidates with no history of arrears are given preference for GD/PI process. Candidates with up to two history of arrears can apply. However the invitation for GD/PI is at the sole discretion of the institution.</p>
		      </li>
		     
                      <li>
				<h3 class="upperCase">Work Experience</h3>
				</li>
			
			<li>
				
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Total Work Experience(in years): </label>
				<div class='fieldBoxSmall' style="width:190px;">
				<input class="textboxLarge" type='text' name='SSN_totalExp' id='SSN_totalExp'  validate="validateFloat"   required="true"   caption="total work experience"   minlength="1"   maxlength="10"     tip="Please enter your total work experience in years"   title=" Total Work Experience"  value=''   />
				<?php if(isset($SSN_totalExp) && $SSN_totalExp!=""){ ?>
				  <script>
				      document.getElementById("SSN_totalExp").value = "<?php echo str_replace("\n", '\n', $SSN_totalExp );  ?>";
				      document.getElementById("SSN_totalExp").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_totalExp_error'></div></div>
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
			} //print_r($workCompaniesExpTill);
			
		      if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					
					$workExpWorkNatureName = 'workExpWorkNature'.$workCompanyKey;
					$workExpWorkNatureValue = $$workExpWorkNatureName;
					$j++;
					
			?>

                  
		      <li >
				<div class='additionalInfoLeftCol'>
				<label>Nature of Work at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpWorkNatureName;?>' id='<?php echo $workExpWorkNatureName;?>'  validate="validateStr" minlength="2" maxlength="1000" caption="nature of work at <?php echo $workCompany; ?>"    tip="Enter the nature of work at  <?php echo $workCompany; ?>"   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpWorkNatureValue) && $workExpWorkNatureValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpWorkNatureName;?>").value = "<?php echo str_replace("\n", '\n',$workExpWorkNatureValue );  ?>";
				      document.getElementById("<?php echo $workExpWorkNatureName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpWorkNatureName;?>_error'></div></div>
				</div>
				</div>
		      </li>
		      
			<?php
				}
			}
			?>	
		      
		      
		      <li>
				<h3 class="upperCase">Extra-curricular/Co-curricular/ Sports Achievements</h3>
		      </li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>1.</label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SSN_1extraCurr' id='SSN_1extraCurr'   style="width:618px; height:74px; padding:5px"    tip="Enter the extra-curricular/Co-curricular/ sports achievements."  validate="validateStr"  minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($SSN_1extraCurr) && $SSN_1extraCurr!=""){ ?>
				  <script>
				      document.getElementById("SSN_1extraCurr").value = "<?php echo str_replace("\n", '\n', $SSN_1extraCurr );  ?>";
				      document.getElementById("SSN_1extraCurr").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='SSN_1extraCurr_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>2.</label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SSN_2extraCurr' id='SSN_2extraCurr'   style="width:618px; height:74px; padding:5px"    tip="Enter the extra-curricular/Co-curricular/ sports achievements."  validate="validateStr"  minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($SSN_2extraCurr) && $SSN_2extraCurr!=""){ ?>
				  <script>
				      document.getElementById("SSN_2extraCurr").value = "<?php echo str_replace("\n", '\n', $SSN_2extraCurr );  ?>";
				      document.getElementById("SSN_2extraCurr").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='SSN_2extraCurr_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>3.</label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SSN_3extraCurr' id='SSN_3extraCurr'   style="width:618px; height:74px; padding:5px"    tip="Enter the extra-curricular/Co-curricular/ sports achievements."  validate="validateStr"  minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($SSN_3extraCurr) && $SSN_3extraCurr!=""){ ?>
				  <script>
				      document.getElementById("SSN_3extraCurr").value = "<?php echo str_replace("\n", '\n', $SSN_3extraCurr );  ?>";
				      document.getElementById("SSN_3extraCurr").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='SSN_3extraCurr_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>4.</label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SSN_4extraCurr' id='SSN_4extraCurr'   style="width:618px; height:74px; padding:5px"    tip="Enter the extra-curricular/Co-curricular/ sports achievements."  validate="validateStr"  minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($SSN_4extraCurr) && $SSN_4extraCurr!=""){ ?>
				  <script>
				      document.getElementById("SSN_4extraCurr").value = "<?php echo str_replace("\n", '\n', $SSN_4extraCurr );  ?>";
				      document.getElementById("SSN_4extraCurr").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='SSN_4extraCurr_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>
		      
		      <li>
				<h3 class="upperCase">Project work in UG</h3>
		      </li>
		      <li>
				<div class='additionalInfoLeftCol' style="width:800px;">
				<label>Title of the Project: </label>
				<div class='fieldBoxLarge' style="width:190px;">
				<input class="textboxLarge" type='text' name='SSN_projTitle' id='SSN_projTitle'  validate="validateStr"   required="true"   caption="title of the project"   minlength="2"   maxlength="100"     tip="Please enter title of the project"   title="Title of the Project"  value=''   />
				<?php if(isset($SSN_projTitle) && $SSN_projTitle!=""){ ?>
				  <script>
				      document.getElementById("SSN_projTitle").value = "<?php echo str_replace("\n", '\n', $SSN_projTitle );  ?>";
				      document.getElementById("SSN_projTitle").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'SSN_projTitle_error'></div></div>
				</div>
		      </li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Description of the Project:</label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='SSN_projDesc' id='SSN_projDesc'   style="width:618px; height:74px; padding:5px"    tip="Enter the description of the project."  validate="validateStr" required="true"   caption="description of the project" minlength="2" maxlength="2000" ></textarea>
				<?php if(isset($SSN_projDesc) && $SSN_projDesc!=""){ ?>
				  <script>
				      document.getElementById("SSN_projDesc").value = "<?php echo str_replace("\n", '\n', $SSN_projDesc );  ?>";
				      document.getElementById("SSN_projDesc").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id='SSN_projDesc_error'></div></div>
				<div class="spacer15 clearFix"></div>
				</div>
				
				</div>
			</li>

	  
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				<ul>
				  <li> I do hereby confirm that the information provided above is true and correct. Further, in the event of my taking up admission at SSN SoM, I agree to abide by all the rules and regulations that may be in force at the institution. </li>
				</ul>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the following Terms & Conditions: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='SSN_agreeToTerms[]' id='SSN_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($SSN_agreeToTerms) && $SSN_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["SSN_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$SSN_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'SSN_agreeToTerms0_error'></div></div>
				</div>
				</div>
			</li>
			
			<?php endif; ?>
		</ul>
	</div>
</div>

<script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
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
		checkTestScore(document.getElementById('SSN_testNames'+j));
	}
	
	function showOtherCourseField(obj)
	{
	  if (obj.value=="Others") {
	     $('SSN_otherSource_li').style.display = 'block';
	      $('SSN_otherCourse').setAttribute('required','true');
	  }else{
	     $('SSN_otherSource_li').style.display = 'none';
	     $('SSN_otherCourse').value='';
	    $('SSN_otherCourse').removeAttribute('required');
	   $('SSN_otherCourse_error').innerHTML = '';
	   $('SSN_otherCourse_error').parentNode.style.display = 'none';
	  }
	}
	
	
	var otherCourse='<?php echo $SSN_courseName;?>';
	if (otherCourse=="Others") {
	  $('SSN_otherSource_li').style.display = 'block';
	 
	}else{
	  $('SSN_otherCourse').value='';
	  
	}
	
	

  </script>
