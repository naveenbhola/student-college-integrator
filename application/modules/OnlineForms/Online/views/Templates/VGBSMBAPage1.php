<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional",key+"RankAdditional",key+"PercentileAdditional",key+"PercentileUSB");
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
	<div class='formSection'>
		 <ul>
			<?php if($action != 'updateScore'):?>
			
			
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
			
			<li style="width:100%">
				<h3 class="upperCase">Entrance Test Appeared</h3>
                        <div class="clearFix"></div>
                        
                        <div class="additionalInfoLeftCol" style="width:100%">
			<label>Qualifying examinations:&nbsp;</label>
			<div class='fieldBoxLarge' style="width:600px">
                        <input name='courseVGBS[]' value='CAT' id='courseVGBS0' type="checkbox" onClick="toggleExamDetails('cat','0');" validate="validateCheckedGroup"   required="true"   caption="exam" /> CAT &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseVGBS[]' value='MAT' id="courseVGBS1" type="checkbox" onClick="toggleExamDetails('mat','1');" validate="validateCheckedGroup"   required="true"   caption="exam"  /> MAT &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseVGBS[]' value='XAT' id="courseVGBS2" type="checkbox" onClick="toggleExamDetails('xat','2');" validate="validateCheckedGroup"   required="true"   caption="exam" /> XAT &nbsp;&nbsp;&nbsp;&nbsp;  
			<input name='courseVGBS[]' value='ATMA' id="courseVGBS3" type="checkbox" onClick="toggleExamDetails('atma','3');" validate="validateCheckedGroup"   required="true"   caption="exam" /> ATMA &nbsp;&nbsp;&nbsp;&nbsp;
                        <input name='courseVGBS[]'  value='CMAT' id="courseVGBS4" type="checkbox" onClick="toggleExamDetails('cmat','4');" validate="validateCheckedGroup"   required="true"   caption="exam" /> CMAT &nbsp;&nbsp;&nbsp;&nbsp;
			<input name='courseVGBS[]'  value='GMAT' id="courseVGBS5" type="checkbox" onClick="toggleExamDetails('gmat','5');" validate="validateCheckedGroup"   required="true"   caption="exam" /> GMAT &nbsp;&nbsp;&nbsp;&nbsp;
			<div class="clearFix"></div>
                    	<div style='display:none'><div class='errorMsg' id= 'courseVGBS_error'></div></div>
                    </div>

		   
			</div>
			</li>
			<li id="catDetails" style="display:none;">
				<h3>CAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date'/>
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
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''/>
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
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"    tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"         tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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

			<li id="matDetails" style="display:none;">
				<h3>MAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''/>
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
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"        tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''    allowNA='true'/>
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
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"       tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter <b>NA.</b>"   value=''    allowNA='true'/>
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
			<li id="xatDetails" style="display:none;">
				<h3>XAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');"  caption='date' />
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
				
				<div class='additionalInfoRightCol'>
				<label>XAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''/>
				<?php if(isset($xatScoreAdditional) && $xatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("xatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $xatScoreAdditional );  ?>";
				      document.getElementById("xatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'xatScoreAdditional_error'></div></div>
				</div>
				</div>
			
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  allowNA='true'  />
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

			

			<li id="atmaDetails" style="display:none">
				<h3>ATMA Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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
				
				<div class='additionalInfoRightCol'>
				<label>ATMA Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''/>
				<?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
				      document.getElementById("atmaScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
				</div>
				</div>
				
				
                <div class="spacer15 clearFix"></div>
			<div class='additionalInfoLeftCol'>
				<label>Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''  allowNA='true'  />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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
			<li id="cmatDetails" style="display:none">
				<h3>CMAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
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
				
				<div class='additionalInfoRightCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''/>
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
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<label>Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatPercentileAdditionalVGBS' id='cmatPercentileAdditionalVGBS'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($cmatPercentileAdditionalVGBS) && $cmatPercentileAdditionalVGBS!=""){ ?>
				  <script>
				      document.getElementById("cmatPercentileAdditionalVGBS").value = "<?php echo str_replace("\n", '\n', $cmatPercentileAdditionalVGBS );  ?>";
				      document.getElementById("cmatPercentileAdditionalVGBS").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatPercentileAdditionalVGBS_error'></div></div>
				</div>
				</div>
			</li>

			
			<li id="gmatDetails" style="display:none">
				<h3>GMAT Exam Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Choose the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''/>
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
				<input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'  validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I declare that all information in my application is complete, factually correct and honestly presented. I have read the rules and regulations mentioned in the admission brochure and will adhere to the same. I also understand that fees once paid will not be refunded under any circumstances.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='declarationVignana[]' id='declarationVignana0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($declarationVignana) && $declarationVignana!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["declarationVignana[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$declarationVignana);
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
				<div style='display:none'><div class='errorMsg' id= 'declarationVignana0_error'></div></div>
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


function toggleExamDetails(id,num){
	
	cb = $('courseVGBS'+num);
	if(cb.checked) {
		if($(id+'Details')) {
			$(id+'Details').style.display = '';
			if(id=='cat' || id=='mat' || id=='gmat' || id=='xat' || id=='atma'){
				$(id+'RollNumberAdditional').setAttribute('required','true');
				$(id+'PercentileAdditional').setAttribute('required','true');
				$(id+'DateOfExaminationAdditional').setAttribute('required','true');
			}else if(id=='cmat'){
				$(id+'RollNumberAdditional').setAttribute('required','true');
				$(id+'PercentileAdditionalVGBS').setAttribute('required','true');
				$(id+'DateOfExaminationAdditional').setAttribute('required','true');
			}
		}
	}
	else {
		if($(id+'Details')) {
				$(id+'Details').style.display = 'none';
		}		
		if(id=='cat' || id=='mat' || id=='gmat' || id=='xat' || id=='atma'){		
				if($(id+'DateOfExaminationAdditional')) {
					$(id+'DateOfExaminationAdditional').value = '';
				}
				if($(id+'RollNumberAdditional')) {
					$(id+'RollNumberAdditional').value = '';
				}
				if($(id+'PercentileAdditional')) {
					$(id+'PercentileAdditional').value = '';
				}
				$(id+'RollNumberAdditional').removeAttribute('required');
				$(id+'PercentileAdditional').removeAttribute('required');
				$(id+'DateOfExaminationAdditional').removeAttribute('required');
		}else if(id=='cmat'){
				if($(id+'DateOfExaminationAdditional')) {
					$(id+'DateOfExaminationAdditional').value = '';
				}
				if($(id+'RollNumberAdditional')) {
					$(id+'RollNumberAdditional').value = '';
				}
				if($(id+'PercentileAdditionalVGBS')) {
					$(id+'PercentileAdditionalVGBS').value = '';
				}
				$(id+'RollNumberAdditional').removeAttribute('required');
				$(id+'PercentileAdditionalVGBS').removeAttribute('required');
				$(id+'DateOfExaminationAdditional').removeAttribute('required');
		}
	}
}
  </script>

<?php
if(isset($courseVGBS) && $courseVGBS!="" && strpos($courseVGBS,'CAT')!==false){
   echo "<script>document.getElementById('courseVGBS0').checked = true; toggleExamDetails('cat','0');</script>";
}
if(isset($courseVGBS) && $courseVGBS!="" && strpos($courseVGBS,'XAT')!==false){
   echo "<script>document.getElementById('courseVGBS2').checked = true; toggleExamDetails('xat','2');</script>";
}
if(isset($courseVGBS) && $courseVGBS!=""){
   $tests = explode(',',$courseVGBS);
   foreach ($tests as $test){
       if($test=='MAT'){
               echo "<script>document.getElementById('courseVGBS1').checked = true; toggleExamDetails('mat','1');</script>";
       }
   }
}
if(isset($courseVGBS) && $courseVGBS!="" && strpos($courseVGBS,'ATMA')!==false){
   echo "<script>document.getElementById('courseVGBS3').checked = true; toggleExamDetails('atma','3');</script>";
}
if(isset($courseVGBS) && $courseVGBS!="" && strpos($courseVGBS,'CMAT')!==false){
   echo "<script>document.getElementById('courseVGBS4').checked = true; toggleExamDetails('cmat','4');</script>";
}
if(isset($courseVGBS) && $courseVGBS!="" && strpos($courseVGBS,'GMAT')!==false){
   echo "<script>document.getElementById('courseVGBS5').checked = true; toggleExamDetails('gmat','5');</script>";
}

?>
