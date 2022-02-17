
<script>
function checkTestScore(obj){
	var key = obj.value.toLowerCase(); 
	var objects1 = new Array(key+"DateOfExaminationAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
		    if ($(key+"1") != null && $(key+'1') != 'undefined') {
		    $(key+"1").style.display = 'none';
		    }
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
		    if ($(key+"1") != null && $(key+'1') != 'undefined') {
		    $(key+"1").style.display = '';
		    }
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
	<div class='formSection'>
		<ul>
			<li>
				<h3 class='upperCase'>Qualifying Exams</h3>
				<label>Test appeared:</label>
				<div class='additionalInfoLeftCol'>
						<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesEIM[]' id='testNamesEIM0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
						<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesEIM[]' id='testNamesEIM1'   value='MAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
						<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesEIM[]' id='testNamesEIM2'   value='XAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
						<input onClick="checkTestScore(this);" type='checkbox' validate="validateCheckedGroup" required="true" caption="the tests" name='testNamesEIM[]' id='testNamesEIM3'   value='CMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
						<?php if(isset($testNamesEIM) && $testNamesEIM!=""){ ?>
						<script>
						    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesEIM[]"];
						    var countCheckBoxes = objCheckBoxes.length;
						    for(var i = 0; i < countCheckBoxes; i++){
						    objCheckBoxes[i].checked = false;

						    <?php $arr = explode(",",$testNamesEIM);
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
				
						<div style='display:none'><div class='errorMsg' id= 'testNamesEIM_error'></div></div>
				</div>
			</li>
			<li id='cat' style='display:none'>
				<div class='additionalInfoLeftCol'>
					<label>CAT Date:</label>
					<div class='fieldBoxLarge'>
						<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
					<label>CAT Percentile: </label>
					<div class='fieldBoxLarge'>
						<input type='text' class="textboxSmaller" name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
			<li id='cat1' style="display:none; border-bottom:1px;">
				<div class='additionalInfoLeftCol'>
					<label>CAT Score: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   value=''  />
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
			<li id='mat' style='display:none'>
				<div class='additionalInfoLeftCol'>
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
				<div class="additionalInfoRightCol">
					<label>MAT Percentile: </label>
					<div class='fieldBoxLarge'>
					        <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
			<li id='mat1' style="display:none; border-bottom:1px;">
				<div class='additionalInfoLeftCol'>
					<label>MAT Score: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
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
			<li id='xat' style="display:none">
				<div class='additionalInfoLeftCol'>
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
				<div class="additionalInfoRightCol">
					<label>XAT Percentile: </label>
					<div class='fieldBoxLarge'>
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
			<li id="xat1" style="display:none; border-bottom:1px ;">
				<div class='additionalInfoLeftCol'>
					<label>XAT Score: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
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
			<li id='cmat' style="display:none">
				<div class='additionalInfoLeftCol'>
					<label>CMAT Date: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<div class='additionalInfoRightCol'>
					<label>CMAT Score: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
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
			
			<?php if($action != 'updatescore'):?>
			<li>
				
				<h3 class=upperCase'>Declaration of Student</h3>
				<label style="font-weight:normal; padding-top:0">Undertaking:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I, hereby declare that the above particulars furnished by me are correct to the best of my knowledge, and that I will, on being admitted, abide by the Rules and Code of Conduct of Eastern Institute of Management,Kolkata.<br>
		I hold myself responsible for any and all financial obligations towards the Institute, including payment of Fees.<br>
				</div>
				<div class="spacer10 clearFix"></div>
			</li>
			<li>
				<div>
				<input type='checkbox'   required="true"    name='agreeToTermsEIM' id='agreeToTermsEIM'   value=''  checked  validate='validateChecked' caption='Please agree to the terms stated above'></input><span ></span>&nbsp;&nbsp;
				<label style="font-weight:normal;">I agree to the terms stated above: </label></div>
				
				
				<?php if(isset($agreeToTermsEIM) && $agreeToTermsEIM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsEIM[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$agreeToTermsEIM);
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
				
				<div style="display:none;"><div class='errorMsg' id= 'agreeToTermsEIM_error'></div></div>
				
			</li>
			<?php endif; ?>
		</ul>
	</div>
</div>
<script>
	for(var j=0; j<4; j++){
		checkTestScore(document.getElementById('testNamesEIM'+j));
	}
</script>