<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RegnNoKIITS",key+"PercentileKIITS",key+"ScoreKIITS",key+"DateKIITS",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional",key+"RankAdditional",key+"DateOfExaminationAdditional");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
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
                       <li style="width:100%">
			   <h3>Additional qualifying examination details </h3>
                           <div class="clearFix"></div>
                            <div class='additionalInfoLeftCol' style="width:800px">
				<label>Qualifying Examinations: </label>
				<div class='fieldBoxLarge' style="width:400px">
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames0'   value='CAT'   onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames1'   value='MAT'     onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames2'   value='GMAT'     onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames3'   value='XAT'     onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames4'   value='ATMA'     onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
                                 <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames6'   value='CMAT'     onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                                <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='KIITS_testNames[]' id='KIITS_testNames5'   value='KIITEE'     onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select a qualifying examination and enter appropriate details.',this);" onmouseout="hidetip();" >KIITEE MGT</span>&nbsp;&nbsp;
				<?php if(isset($KIITS_testNames) && $KIITS_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["KIITS_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;


					      <?php $arr = explode(",",$KIITS_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'KIITS_testNames_error'></div></div>
				</div>
				</div>        
		  
			</li>
			
			 <li id="cat" style="display:none">
                       		<h3>CAT Score Details</h3>
       				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam.If not available, enter <b>NA</b>" allowNA='true'    value=''   />
				<?php if(isset($catScoreAdditional) && $catScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("catScoreAdditional").value = "<?php echo str_replace("\n", '\n', $catScoreAdditional );  ?>";
				      document.getElementById("catScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catScoreAdditional_error'></div></div>
				</div>
				</div>
				
                                <div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"    tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"         tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>." allowNA='true'  value=''   />
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

			<li id="mat" style="display:none">
				<h3>MAT Score Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"       tip="Mention how much you scored in the exam.If not available, enter <b>NA</b>" allowNA='true'    value=''   />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"        tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value=''   />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"       tip="Mention your percentile in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
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

                        <li id="cmat" style="display:none">
				<h3>CMAT Score Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'   validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"       tip="Mention how much you scored in the exam.If not available, enter <b>NA</b>" allowNA='true'    value=''   />
				<?php if(isset($cmatScoreAdditional) && $cmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $cmatScoreAdditional );  ?>";
				      document.getElementById("cmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>

				
				<div style='display:none'><div class='errorMsg' id= 'cmatScoreAdditional_error'></div></div>
				</div>
				</div>
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"        tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value=''   />
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
				<label>Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'   validate="validateFloat"    caption="Rank"   minlength="1"   maxlength="5"       tip="Mention your Rank in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
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

			<li id="xat" style="display:none;">
				<h3>XAT Score Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateKIITS' id='xatDateKIITS' readonly maxlength='10'         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateKIITS'),'xatDateKIITS_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='xatDateKIITS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateKIITS'),'xatDateKIITS_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($xatDateKIITS) && $xatDateKIITS!=""){ ?>
				  <script>
				      document.getElementById("xatDateKIITS").value = "<?php echo str_replace("\n", '\n', $xatDateKIITS );  ?>";
				      document.getElementById("xatDateKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatDateKIITS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreKIITS' id='xatScoreKIITS'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
				<?php if(isset($xatScoreKIITS) && $xatScoreKIITS!=""){ ?>
				  <script>
				      document.getElementById("xatScoreKIITS").value = "<?php echo str_replace("\n", '\n', $xatScoreKIITS );  ?>";
				      document.getElementById("xatScoreKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreKIITS_error'></div></div>
				</div>
				</div>
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRegnNoKIITS' id='xatRegnNoKIITS'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   />
				<?php if(isset($xatRegnNoKIITS) && $xatRegnNoKIITS!=""){ ?>
				  <script>
				      document.getElementById("xatRegnNoKIITS").value = "<?php echo str_replace("\n", '\n', $xatRegnNoKIITS );  ?>";
				      document.getElementById("xatRegnNoKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatRegnNoKIITS_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileKIITS' id='xatPercentileKIITS'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
				<?php if(isset($xatPercentileKIITS) && $xatPercentileKIITS!=""){ ?>
				  <script>
				      document.getElementById("xatPercentileKIITS").value = "<?php echo str_replace("\n", '\n', $xatPercentileKIITS );  ?>";
				      document.getElementById("xatPercentileKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatPercentileKIITS_error'></div></div>
				</div>
				</div>
			</li>

			<li id="gmat" style="display:none;">
				<h3>GMAT Score Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='gmatDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($gmatDateOfExaminationAdditional) && $gmatDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $gmatDateOfExaminationAdditional );  ?>";
				      document.getElementById("gmatDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'   validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"       tip="Mention how much you scored in the exam.If not available, enter <b>NA</b>" allowNA='true'    value=''   />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"       tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   />
				<?php if(isset($gmatRollNumberAdditional) && $gmatRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $gmatRollNumberAdditional );  ?>";
				      document.getElementById("gmatRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatRollNumberAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"         tip="Mention your percentile in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
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

			<li id="atma" style="display:none">
				<h3>ATMA Score Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateKIITS' id='atmaDateKIITS' readonly maxlength='10'         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateKIITS'),'atmaDateKIITS_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='atmaDateKIITS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateKIITS'),'atmaDateKIITS_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($atmaDateKIITS) && $atmaDateKIITS!=""){ ?>
				  <script>
				      document.getElementById("atmaDateKIITS").value = "<?php echo str_replace("\n", '\n', $atmaDateKIITS );  ?>";
				      document.getElementById("atmaDateKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaDateKIITS_error'></div></div>
				</div>
				</div>

			       <div class='additionalInfoRightCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreKIITS' id='atmaScoreKIITS'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
				<?php if(isset($atmaScoreKIITS) && $atmaScoreKIITS!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreKIITS").value = "<?php echo str_replace("\n", '\n', $atmaScoreKIITS );  ?>";
				      document.getElementById("atmaScoreKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreKIITS_error'></div></div>
				</div>
				</div>

                             <div class="spacer15 clearFix"></div>
			      <div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaRegnNoKIITS' id='atmaRegnNoKIITS'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   />
				<?php if(isset($atmaRegnNoKIITS) && $atmaRegnNoKIITS!=""){ ?>
				  <script>
				      document.getElementById("atmaRegnNoKIITS").value = "<?php echo str_replace("\n", '\n', $atmaRegnNoKIITS );  ?>";
				      document.getElementById("atmaRegnNoKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaRegnNoKIITS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileKIITS' id='atmaPercentileKIITS'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
				<?php if(isset($atmaPercentileKIITS) && $atmaPercentileKIITS!=""){ ?>
				  <script>
				      document.getElementById("atmaPercentileKIITS").value = "<?php echo str_replace("\n", '\n', $atmaPercentileKIITS );  ?>";
				      document.getElementById("atmaPercentileKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaPercentileKIITS_error'></div></div>
				</div>
				</div>
			</li>


                        <li id="kiitee" style="display:none">
				<h3>KIITEE MGT Score Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kiiteeDateKIITS' id='kiiteeDateKIITS' readonly maxlength='10'         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('kiiteeDateKIITS'),'kiiteeDateKIITS_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='kiiteeDateKIITS_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('kiiteeDateKIITS'),'kiiteeDateKIITS_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($kiiteeDateKIITS) && $kiiteeDateKIITS!=""){ ?>
				  <script>
				      document.getElementById("kiiteeDateKIITS").value = "<?php echo str_replace("\n", '\n', $kiiteeDateKIITS );  ?>";
				      document.getElementById("kiiteeDateKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kiiteeDateKIITS_error'></div></div>
				</div>
				</div>

			        <div class='additionalInfoRightCol'>
				<label>Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kiiteeScoreKIITS' id='kiiteeScoreKIITS'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
				<?php if(isset($kiiteeScoreKIITS) && $kiiteeScoreKIITS!=""){ ?>
				  <script>
				      document.getElementById("kiiteeScoreKIITS").value = "<?php echo str_replace("\n", '\n', $kiiteeScoreKIITS );  ?>";
				      document.getElementById("kiiteeScoreKIITS").style.color = "";

				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kiiteeScoreKIITS_error'></div></div>
				</div>
				</div>

                                 <div class="spacer15 clearFix"></div>
			         <div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kiiteeRegnNoKIITS' id='kiiteeRegnNoKIITS'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   />
				<?php if(isset($kiiteeRegnNoKIITS) && $kiiteeRegnNoKIITS!=""){ ?>
				  <script>
				      document.getElementById("kiiteeRegnNoKIITS").value = "<?php echo str_replace("\n", '\n', $kiiteeRegnNoKIITS );  ?>";
				      document.getElementById("kiiteeRegnNoKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kiiteeRegnNoKIITS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='kiiteePercentileKIITS' id='kiiteePercentileKIITS'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If not available, enter <b>NA</b>" allowNA='true'   value=''   />
				<?php if(isset($kiiteePercentileKIITS) && $kiiteePercentileKIITS!=""){ ?>
				  <script>
				      document.getElementById("kiiteePercentileKIITS").value = "<?php echo str_replace("\n", '\n', $kiiteePercentileKIITS );  ?>";
				      document.getElementById("kiiteePercentileKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'kiiteePercentileKIITS_error'></div></div>
				</div>
				</div>
			</li>
                        
                        <?php if($action != 'updateScore'):?>   
                         <li >
				<div class='additionalInfoLeftCol' style="width:100%"> 
                                <style="display:<?=($categoryKIITS=='HANDICAPPED' || $categoryKIITS=='DEFENCE')?'':'none'?>">                    
			        <h3>Additional personal Information</h3>  
				<label>Category: </label>
				<div class='fieldBoxLarge' style="width:600px">
				<input type='radio' name='categoryKIITS' id='categoryKIITS0'   value='GENERAL'  checked   onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" >General</span>&nbsp;&nbsp;
				<input type='radio' name='categoryKIITS' id='categoryKIITS1'   value='SC'     onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryKIITS' id='categoryKIITS2'   value='ST'     onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				<input type='radio' name='categoryKIITS' id='categoryKIITS3'   value='OBC'     onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the appropriate category',this);" onmouseout="hidetip();" >OBC</span>&nbsp;&nbsp;
				<?php if(isset($categoryKIITS) && $categoryKIITS!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["categoryKIITS"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $categoryKIITS;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryKIITS_error'></div></div>
				</div>
				</div>
			</li>
			
			

                          <li>
				<div class='additionalInfoLeftCol'>
				<label>Mother Tongue: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='KIITS_motherTongue' id='KIITS_motherTongue'  validate="validateStr"   required="true"   caption="Mother Tongue"   minlength="2"   maxlength="50"     tip="Enter your mother tongue, i.e. your first language."   title="Mother Tongue"  value=''   />
				<?php if(isset($KIITS_motherTongue) && $KIITS_motherTongue!=""){ ?>
				  <script>
				      document.getElementById("KIITS_motherTongue").value = "<?php echo str_replace("\n", '\n', $KIITS_motherTongue );  ?>";
				      document.getElementById("KIITS_motherTongue").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'KIITS_motherTongue_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Blood Group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='KIITS_bloodGroup' id='KIITS_bloodGroup'  validate="validateStr"   required="true"   caption="Blood Group"   minlength="2"   maxlength="50"     tip="Enter your blood group e.g. O+ive, AB-ive etc. If you don't know your blood group, simple enter <b>NA</b>" allowNA='true'   title="Blood Group"  value=''   />
				<?php if(isset($KIITS_bloodGroup) && $KIITS_bloodGroup!=""){ ?>
				  <script>
				      document.getElementById("KIITS_bloodGroup").value = "<?php echo str_replace("\n", '\n', $KIITS_bloodGroup );  ?>";
				      document.getElementById("KIITS_bloodGroup").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'KIITS_bloodGroup_error'></div></div>
				</div>
				</div>
			</li>
			
                           

                            
		         <li>
				<div class='additionalInfoLeftCol' style="width:100%">
                                <div class='fieldBoxLarge' style="width:600px">
				<label>Whether Physically Handicapped  : </label>
				<input type='radio' name='physicallyHandicappedKIITS' id='physicallyHandicappedKIITS0'   value='Yes'  <?php if($categoryKIITS=='HANDICAPPED' || $physicallyHandicappedKIITS == "Yes"){ ?> checked <?php } ?>   onmouseover="showTipOnline('If you are physically handicapped, select YES else select NO',this);" onmouseout="hidetip();" onclick="hideShowHandicappedText('Yes');"></input><span  onmouseover="showTipOnline('If you are physically handicapped, select YES else select NO',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='physicallyHandicappedKIITS' id='physicallyHandicappedKIITS1'   value='No'   <?php if($categoryKIITS!='HANDICAPPED' && $physicallyHandicappedKIITS == "No"){ ?> checked <?php } ?>  onmouseover="showTipOnline('If you are physically handicapped, select YES else select NO',this);" onmouseout="hidetip();"  onclick="hideShowHandicappedText('No');"></input><span  onmouseover="showTipOnline('If you are physically handicapped, select YES else select NO',this);" onmouseout="hidetip();"  >No</span>&nbsp;&nbsp;
				<?php if(isset($physicallyHandicappedKIITS) && $physicallyHandicappedKIITS!=""){ ?>
				  <script>
				//      radioObj = document.forms["OnlineForm"].elements["physicallyHandicappedKIITS"];
				//      var radioLength = radioObj.length;
				//      for(var i = 0; i < radioLength; i++) {
				//	      radioObj[i].checked = false;
				//	      if(radioObj[i].value == "<?php echo $physicallyHandicappedKIITS;?>") {
				//		      radioObj[i].checked = true;
				//	      }
				//      }
				  </script>
				<?php }
					
					 ?>
                                 </div>
                             </div>
                          </li>                       
                          
                          <li>
                           <label>Details(if yes):</label>
                           <div id="handicap_field" style="padding 1px 0 0 7px;display:none;"><input type="text" id='handicapKIITS' name='handicapKIITS'  style="width:400px" validate="validateStr"    caption="Details"   minlength="2"   maxlength="50"      value='' ></div>
				
				<?php if(isset($handicapKIITS) && $handicapKIITS!=""){ ?>
				  <script>
				      document.getElementById("handicapKIITS").value = "<?php echo str_replace("\n", '\n', $handicapKIITS );  ?>";
				      document.getElementById("handicapKIITS").style.color = "";
				  </script>
				<?php } ?>
                                <div class="clearFix"></div>
				<div style='display:none;padding-left:7px;clear:both;float:left'><div class='errorMsg' id= 'handicapKIITS_error'></div></div>
                            
				
			</li> 
                        <?php endif ?>		
                             
                         	                 
                          <?php if($action != 'updateScore'):?> 
             
			<li>	
                               <h3>Additional family details</h3> 
				<div class='additionalInfoLeftCol'>
				<label>Total annual household income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='incomeKIITS' id='incomeKIITS'  validate="validateInteger"   required="true"   caption="Household Income"   minlength="1"   maxlength="50"     tip="Enter your total annual household income"   value=''   />
				<?php if(isset($incomeKIITS) && $incomeKIITS!=""){ ?>
				  <script>
				      document.getElementById("incomeKIITS").value = "<?php echo str_replace("\n", '\n', $incomeKIITS );  ?>";
				      document.getElementById("incomeKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'incomeKIITS_error'></div></div>
				</div>
				</div>
			</li>
			
			 
                           
			

			<li>
				<h3>Emergency Contact</h3> 
				<div class='additionalInfoLeftCol' style="width:400px">
				<label>In case of emergency, we can contact: </label>
				<div class='fieldBoxLarge' style="width:80px">
				<select name='salutationKIITS' id='salutationKIITS'      validate="validateSelect"   required="true"   caption="Salutation"  ><option value='' selected>Select</option><option value='Mr' >Mr</option><option value='Ms' >Ms</option><option value='Mrs' >Mrs</option></select>
				<?php if(isset($salutationKIITS) && $salutationKIITS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("salutationKIITS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $salutationKIITS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'salutationKIITS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<div class='fieldBoxLarge'>
				<input type='text' name='emergencyContactKIITS' id='emergencyContactKIITS'  validate="validateStr"   required="true"   caption="Name"   minlength="1"   maxlength="50"     tip="Enter the name of the person who the Institute can contact in case of emergency"   value=''   />
				<?php if(isset($emergencyContactKIITS) && $emergencyContactKIITS!=""){ ?>
				  <script>
				      document.getElementById("emergencyContactKIITS").value = "<?php echo str_replace("\n", '\n', $emergencyContactKIITS );  ?>";
				      document.getElementById("emergencyContactKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emergencyContactKIITS_error'></div></div>
				</div>
				</div>
			</li>
			<li>
                <div class='additionalInfoLeftCol'>
				<label>Residence Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='residencePhoneKIITS' id='residencePhoneKIITS'  validate="validateInteger"    caption="Residence Phone Number"   minlength="6"   maxlength="10"     tip="Enter the home phone number of the person who the Institute can contact in case of emergency. If not available enter <b>NA</b>." allowNA='true' required='true'   value=''   />
				<?php if(isset($residencePhoneKIITS) && $residencePhoneKIITS!=""){ ?>
				  <script>
				      document.getElementById("residencePhoneKIITS").value = "<?php echo str_replace("\n", '\n', $residencePhoneKIITS );  ?>";
				      document.getElementById("residencePhoneKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'residencePhoneKIITS_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Office Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='officePhoneKIITS' id='officePhoneKIITS'  validate="validateInteger"    caption="Office Phone Number"   minlength="6"   maxlength="10"     tip="Enter the Office phone number of the person who the Institute can contact in case of emergency If not available enter <b>NA</b>." allowNA='true' required='true'    value=''   />
				<?php if(isset($officePhoneKIITS) && $officePhoneKIITS!=""){ ?>
				  <script>
				      document.getElementById("officePhoneKIITS").value = "<?php echo str_replace("\n", '\n', $officePhoneKIITS );  ?>";
				      document.getElementById("officePhoneKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'officePhoneKIITS_error'></div></div>
				</div>
				</div>
			</li>
			<li>
                <div class='additionalInfoLeftCol'>
				<label>Relation with emergency contact: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='contactRelationKIITS' id='contactRelationKIITS'  validate="validateAlphabetic"   required="true"   caption="relationship with Emergency Contact"   minlength="1"   maxlength="50"     tip="Enter your relation with the person who the Institute can contact in case of emergency"   value=''   />
				<?php if(isset($contactRelationKIITS) && $contactRelationKIITS!=""){ ?>
				  <script>
				      document.getElementById("contactRelationKIITS").value = "<?php echo str_replace("\n", '\n', $contactRelationKIITS );  ?>";
				      document.getElementById("contactRelationKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'contactRelationKIITS_error'></div></div>
				</div>
				</div>
			</li>
			
            <li>	
            	   <h3>Additional Education Info</h3> 
				<div class='additionalInfoLeftCol'>
				<label>Class 10th stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10StreamKIITS' id='class10StreamKIITS'  validate="validateStr"   required="true"   caption="Education Stream"   minlength="1"   maxlength="50"     tip="Enter education stream. If you are unsure about the stream, enter the subjects 
seperated by comma"   value=''   csv="true"/>
				<?php if(isset($class10StreamKIITS) && $class10StreamKIITS!=""){ ?>
				  <script>
				      document.getElementById("class10StreamKIITS").value = "<?php echo str_replace("\n", '\n', $class10StreamKIITS );  ?>";
				      document.getElementById("class10StreamKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10StreamKIITS_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Class 10th division/class: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10DivisonKIITS' id='class10DivisonKIITS'  validate="validateStr"   required="true"   caption="division/class"   minlength="1"   maxlength="50"     tip="Enter division or class obtained in final examination. For example 1st Division. If division or class was not awarded in your institute, just enter <b>NA.</b>"   value=''    allowNA = 'true' />
				<?php if(isset($class10DivisonKIITS) && $class10DivisonKIITS!=""){ ?>
				  <script>
				      document.getElementById("class10DivisonKIITS").value = "<?php echo str_replace("\n", '\n', $class10DivisonKIITS );  ?>";
				      document.getElementById("class10DivisonKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10DivisonKIITS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12StreamKIITS' id='class12StreamKIITS'  validate="validateStr"   required="true"   caption="Education Stream"   minlength="1"   maxlength="50"     tip="Enter education stream. If you are unsure about the stream, enter the subjects 
seperated by comma"   value=''  csv="true"/>
				<?php if(isset($class12StreamKIITS) && $class12StreamKIITS!=""){ ?>
				  <script>
				      document.getElementById("class12StreamKIITS").value = "<?php echo str_replace("\n", '\n', $class12StreamKIITS );  ?>";
				      document.getElementById("class12StreamKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12StreamKIITS_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label>Class 12th division/class: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12DivisonKIITS' id='class12DivisonKIITS'  validate="validateStr"   required="true"   caption="division/class"   minlength="1"   maxlength="50"     tip="Enter division or class obtained in final examination. For example 1st Division. If division or class was not awarded in your institute, just enter <b>NA.</b>"   value=''    allowNA = 'true' />
				<?php if(isset($class12DivisonKIITS) && $class12DivisonKIITS!=""){ ?>
				  <script>
				      document.getElementById("class12DivisonKIITS").value = "<?php echo str_replace("\n", '\n', $class12DivisonKIITS );  ?>";
				      document.getElementById("class12DivisonKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12DivisonKIITS_error'></div></div>
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
				<label><?php echo $graduationCourseName;?> stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradStreamKIITS' id='gradStreamKIITS'  validate="validateStr"   required="true"   caption="Education Stream"   minlength="1"   maxlength="50"     tip="Enter education stream for your graduation course. For example if you did BA Economics, your stream will be Economics.If you are unsure about the stream, enter the subjects seperated by comma"   value=''  csv="true"  />
				<?php if(isset($gradStreamKIITS) && $gradStreamKIITS!=""){ ?>
				  <script>
				      document.getElementById("gradStreamKIITS").value = "<?php echo str_replace("\n", '\n', $gradStreamKIITS );  ?>";
				      document.getElementById("gradStreamKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradStreamKIITS_error'></div></div>
				</div>
				</div>
                
                <div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> division/class: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradDivisonKIITS' id='gradDivisonKIITS'  validate="validateStr"   required="true"   caption="division/class"   minlength="1"   maxlength="10"     tip="Enter division or class obtained in final examination. For example 1st Division. If division or class was not awarded in your institute, just enter <b>NA.</b>"   value=''    allowNA = 'true' />
				<?php if(isset($gradDivisonKIITS) && $gradDivisonKIITS!=""){ ?>
				  <script>
				      document.getElementById("gradDivisonKIITS").value = "<?php echo str_replace("\n", '\n', $gradDivisonKIITS );  ?>";
				      document.getElementById("gradDivisonKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradDivisonKIITS_error'></div></div>
				</div>
				</div>
			</li>

			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					$pgCheckVal = $$pgCheck;
					$stream = 'otherCourseStream_mul_'.$otherCourseId;
					$streamVal = $$stream;
					$divisionORClass = 'otherCourseDivisionORClass_mul_'.$otherCourseId;
					$divisionORClassVal = $$divisionORClass;
					$i++;

			?>	<li>
				<div class='additionalInfoLeftCol'>
				<label>Is <?php echo $otherCourseName;?> a PG Course: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='<?php echo $pgCheck; ?>' id='<?php echo $pgCheck; ?>'  tip="If this is a Post Graduate course, please tick this check box."   value='1'  />
				    <?php if(isset($pgCheckVal) && $pgCheckVal!=""){ ?>
				      <script>
					  objCheckBoxes = document.forms["OnlineForm"].elements["<?php echo $pgCheck; ?>"];
					  var countCheckBoxes = 1;
					  for(var i = 0; i < countCheckBoxes; i++){ 
						    objCheckBoxes.checked = false;
						    <?php $arr = explode(",",$pgCheckVal);
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
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $pgCheck; ?>_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName;?> stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $stream;?>' id='<?php echo $stream;?>'  validate="validateStr"   required="true"   caption="Education Stream"   minlength="1"   maxlength="10"     tip="Enter education stream. If you are unsure about the stream, enter the subjects 
seperated by comma"   value=''   csv="true"/>
				<?php if(isset($streamVal) && $streamVal!=""){ ?>
				  <script>
				      document.getElementById('<?php echo $stream;?>').value = "<?php echo str_replace("\n", '\n', $streamVal );  ?>";
				      document.getElementById('<?php echo $stream;?>').style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $stream;?>_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName;?> division/class: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $divisionORClass;?>' id='<?php echo $divisionORClass;?>'  validate="validateStr"   required="true"   caption="division/class"   minlength="1"   maxlength="10"     tip="Enter division or class obtained in final examination. For example 1st Division. If division or class was not awarded in your institute, just enter <b>NA.</b>"   value=''    allowNA = 'true' />
				<?php if(isset($divisionORClassVal) && $divisionORClassVal!=""){ ?>
				  <script>
				      document.getElementById('<?php echo $divisionORClass;?>').value = "<?php echo str_replace("\n", '\n', $divisionORClassVal );  ?>";
				      document.getElementById('<?php echo $divisionORClass;?>').style.color = "";
				  </script>

				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $divisionORClass;?>_error'></div></div>
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
					$salaryFieldName = 'annualSalaryKIITS'.($workCompanyKey=='_mul_0'?'':$workCompanyKey);
					$salaryValue = $$salaryFieldName;
			?>
			<li>	<?php if($counter==1){ ?> <h3>Additional Work exp details</h3> <?php } ?> 
				<div class='additionalInfoLeftCol'>
				<label>Annual salary at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $salaryFieldName; ?>' id='<?php echo $salaryFieldName; ?>'  validate="validateInteger"   required="true"   caption="annual salary"   minlength="1"   maxlength="10" tip="Please enter your annual salary at <?php echo $workCompany; ?>"   value=''   />
				<?php if(isset($salaryValue) && $salaryValue!=""){ ?>
				  <script>
				      document.getElementById('<?php echo $salaryFieldName; ?>').value = "<?php echo str_replace("\n", '\n', $salaryValue );  ?>";
				      document.getElementById('<?php echo $salaryFieldName; ?>').style.color = "";
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
            	<h3>Essay Block</h3>
				<div class='additionalInfoLeftCol' style="width:940px">
				<label>How do you expect MBA to benefit you? (AT LEAST 60 words): </label>
				<div class='fieldBoxLarge'>
				<textarea name='essayKIITS' id='essayKIITS' style="width:600px" validate="validateStr"   required="true"   caption="short essay"   minlength="5"   maxlength="350"     tip="Write an essay on How do you expect MBA to benefit you. Please think very carefully before writing the essay. Ensure that the essay is AT LEAST 60 words long."    ></textarea>
				<?php if(isset($essayKIITS) && $essayKIITS!=""){ ?>
				  <script>
				      document.getElementById("essayKIITS").value = "<?php echo str_replace("\n", '\n', $essayKIITS );  ?>";
				      document.getElementById("essayKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'essayKIITS_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>	
				<div class='additionalInfoLeftCol' style="width:940px">
				<label>Give an example of something that you did well. Why do you think you succeeded? (AT LEAST 60 words): </label>
				<div class='fieldBoxLarge'>
				<textarea name='KIITS_essay1' id='KIITS_essay1' style="width:600px" validate="validateStr"   required="true"   caption="short essay"   minlength="5"   maxlength="350"     tip="Write an essay on something that you did well. Why do you think you succeeded. Please think very carefully before writing the essay. Ensure that the essay is AT LEAST 60 words long."    ></textarea>
				<?php if(isset($KIITS_essay1) && $KIITS_essay1!=""){ ?>
				  <script>
				      document.getElementById("KIITS_essay1").value = "<?php echo str_replace("\n", '\n', $KIITS_essay1 );  ?>";
				      document.getElementById("KIITS_essay1").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'KIITS_essay1_error'></div></div>
				</div>
				</div>
			</li>
			
			
			<li>	
				<div class='additionalInfoLeftCol' style="width:940px">
				<label>Give an example of something you have failed in. Why did you fail? (AT LEAST 60 words): </label>
				<div class='fieldBoxLarge'>
				<textarea name='KIITS_essay2' id='KIITS_essay2' style="width:600px" validate="validateStr"   required="true"   caption="short essay"   minlength="5"   maxlength="350"     tip="Write an essay on  something you have failed in. Why did you fail? Please think very carefully before writing the essay. Ensure that the essay is AT LEAST 60 words long."    ></textarea>
				<?php if(isset($KIITS_essay2) && $KIITS_essay2!=""){ ?>
				  <script>
				      document.getElementById("KIITS_essay2").value = "<?php echo str_replace("\n", '\n', $KIITS_essay2 );  ?>";
				      document.getElementById("KIITS_essay2").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'KIITS_essay2_error'></div></div>
				</div>
				</div>
			</li>


			<li>
				<div class='additionalInfoLeftCol' style="width:940px">
				<label>If you have represented in any sports at State/National level, give details: </label>
				<div class='fieldBoxLarge'>
				<textarea name='achievementKIITS' id='achievementKIITS' style="width:600px" tip="If you have taken part in any sports at state/national level, please provide full details of your representation."    ></textarea>
				<?php if(isset($achievementKIITS) && $achievementKIITS!=""){ ?>
				  <script>
				      document.getElementById("achievementKIITS").value = "<?php echo str_replace("\n", '\n', $achievementKIITS );  ?>";
				      document.getElementById("achievementKIITS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'achievementKIITS_error'></div></div>
				</div>
				</div>
			</li>

			<li>	<h3>Additional Info</h3>
				<div class='additionalInfoLeftCol'>
				<label>Do you plan to avail a study loan for the course: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='studyLoanKIITS' id='studyLoanKIITS0'   value='Yes'  checked   onmouseover="showTipOnline('Please mention if you wish to avail study loan',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you wish to avail study loan',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='studyLoanKIITS' id='studyLoanKIITS1'   value='No'     onmouseover="showTipOnline('Please mention if you wish to avail study loan',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention if you wish to avail study loan',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($studyLoanKIITS) && $studyLoanKIITS!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["studyLoanKIITS"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $studyLoanKIITS;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'studyLoanKIITS_error'></div></div>
				</div>
				</div>
			</li>

                         <?php endif; ?>
                         
                         <?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Preferred GD & PI Centres</h3>
				
				<div class='additionalInfoLeftCol'>
				<label>1st Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(1);" name='preferredGDPILocation' id='preferredGDPILocation' style="width:120px;"    onmouseover="showTipOnline('Select your 1st preference of campus from the list.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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

				<div class='additionalInfoRightCol'>
				<label>2nd Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(2);" name='pref2KIITS' id='pref2KIITS'  style="width:120px;"   tip="Select your 2nd preference of campus from the list."       onmouseover="showTipOnline('Select your 2nd preference of campus from the list.',this);" onmouseout="hidetip();" >
				  <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Delhi' >Delhi</option><option value='Kolkata' >Kolkata</option><option value='Mumbai' >Mumbai</option>
				</select>
				<?php if(isset($pref2KIITS) && $pref2KIITS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2KIITS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2KIITS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2KIITS_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>3rd Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(3);" name='pref3KIITS' id='pref3KIITS'   style="width:120px;"  tip="Select your 3rd preference of campus from the list."       onmouseover="showTipOnline('Select your 3rd preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Delhi' >Delhi</option><option value='Kolkata' >Kolkata</option><option value='Mumbai' >Mumbai</option>
				
				</select>
				<?php if(isset($pref3KIITS) && $pref3KIITS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref3KIITS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref3KIITS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref3KIITS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>4th Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(4);" name='pref4KIITS' id='pref4KIITS'  style="width:120px;"   tip="Select your 4th preference of campus from the list."       onmouseover="showTipOnline('Select your 4th preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Delhi' >Delhi</option><option value='Kolkata' >Kolkata</option><option value='Mumbai' >Mumbai</option>
				</select>
				<?php if(isset($pref4KIITS) && $pref4KIITS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref4KIITS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref4KIITS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref4KIITS_error'></div></div>
				</div>
				</div>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>5th Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(5);" name='pref5KIITS' id='pref5KIITS'  style="width:120px;"   tip="Select your 4th preference of campus from the list."       onmouseover="showTipOnline('Select your 5th preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Delhi' >Delhi</option><option value='Kolkata' >Kolkata</option><option value='Mumbai' >Mumbai</option>
				</select>
				<?php if(isset($pref5KIITS) && $pref5KIITS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref5KIITS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref5KIITS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref5KIITS_error'></div></div>
				</div>
				</div>
			</li>

                        
			<li>
				<label style="font-weight:normal; padding-top:0px">Disclaimer:</label>
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
				I, <?php echo $nameOfTheUser; ?> certify that the information furnished in this application is true to the best of my knowledge. My application may be rejected and admission shall be cancelled if any information provided herein is found to be incorrect at any time during or after admission.
				</div>
				<div class="spacer10 clearFix"></div>

				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsKIITS' id='agreeToTermsKIITS'   value='1'  required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsKIITS) && $agreeToTermsKIITS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsKIITS"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					     objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsKIITS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsKIITS_error'></div></div>
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

  </script>
<script>

function checkValidCampusPreference(id){
	if(id==1){id='preferredGDPILocation'; sId = 'pref2KIITS'; tId='pref3KIITS'; fId='pref4KIITS';  kId='pref5KIITS';}
	else if(id==2){ id='pref2KIITS'; sId = 'preferredGDPILocation'; tId = 'pref3KIITS'; fId='pref4KIITS';  kId='pref5KIITS';}
	else if(id==3){id='pref3KIITS'; sId = 'preferredGDPILocation'; tId = 'pref2KIITS';  fId='pref4KIITS';  kId='pref5KIITS';}
	else if(id==4){id='pref4KIITS'; sId = 'preferredGDPILocation'; tId = 'pref2KIITS';  fId='pref3KIITS';  kId='pref5KIITS';}
	else {id='pref5KIITS'; sId = 'preferredGDPILocation'; tId = 'pref2KIITS';  fId='pref3KIITS'; kId='pref4KIITS';}
	var selectedPrefObj = document.getElementById(id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].innerHTML;
	var selObj1 = document.getElementById(sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].innerHTML;
	var selObj2 = document.getElementById(tId); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].innerHTML;
	var selObj3 = document.getElementById(fId); 
	var selPref3 = selObj3.options[selObj3.selectedIndex].innerHTML;
	var selObj4 = document.getElementById(kId); 
	var selPref4 = selObj3.options[selObj4.selectedIndex].innerHTML;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) || (selectedPref == selPref3 && selectedPref!='' )|| (selectedPref == selPref4 && selectedPref!='' )  ){
		$(id +'_error').innerHTML = 'Same preference can’t be set.';
		$(id +'_error').parentNode.style.display = '';
		//Select the blank option
		var A= selectedPrefObj.options, L= A.length;
		while(L){
		    if (A[--L].value== ""){
			selectedPrefObj.selectedIndex= L;
			L= 0;
		    }
		}
		return false;
	}
	else{
		$(id +'_error').innerHTML = '';
		$(id +'_error').parentNode.style.display = 'none';
	}
	return true;
  }
function hideShowHandicappedText(val){
	if($('handicap_field')){
		if(val == 'Yes'){
			$('handicap_field').style.display = '';
			$('handicapKIITS').setAttribute('required','true');
		}else{
			$('handicap_field').style.display = 'none';
			$('handicapKIITS').removeAttribute('required');
			$('handicapKIITS_error').innerHTML = '';
			$('handicapKIITS').value = '';
		}
	}
}


<?php

if($categoryKIITS == 'HANDICAPPED' || $physicallyHandicappedKIITS == 'Yes'){
		echo "hideShowHandicappedText('Yes');";
	}else{
		echo "hideShowHandicappedText('no');";	
	}
?>


for(var j=0; j<7; j++){
	checkTestScore(document.getElementById('KIITS_testNames'+j));
}
</script>
