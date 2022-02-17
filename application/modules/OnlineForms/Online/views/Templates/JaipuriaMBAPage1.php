<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"PercentileAdditional",key+"RankAdditional",key+"DateOfExaminationAdditional");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
			$(key+"1").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
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
  
  function checkProgram(obj){
	var key = obj.value;
	var objects1 = new Array("Jaipuria_"+key+"_Lucknow","Jaipuria_"+key+"_Noida","Jaipuria_"+key+"_Jaipur","Jaipuria_"+key+"_Indore");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
			if($(key+"1")){
				$(key+"1").style.display = 'none';
			}
		    //Set the required paramters when any Exam is hidden
		    resetProgramFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
			if($(key+"1")){
				$(key+"1").style.display = '';
			}
			setProgramFields(objects1);
	      }
	}
  }
  
  function setProgramFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }

  function resetProgramFields(objectsArr){
	for(i=0;i<objectsArr.length;i++){
		if(document.getElementById(objectsArr[i])){
			document.getElementById(objectsArr[i]).value = '';
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }
  
  function checkHow(obj){
	var key = obj.value;
	if(obj && $(key)){
		if( obj.checked == false ){
		    $(key).style.display = 'none';
			$('Jaipuria_how'+key).removeAttribute('required');
			$('Jaipuria_how'+key).value = "";
			$('Jaipuria_how'+key+'_error').innerHTML = '';
			$('Jaipuria_how'+key+'_error').parentNode.style.display = 'none';
	      }
	      else{
		    $(key).style.display = '';
		    $('Jaipuria_how'+key).setAttribute('required','true');
			$('Jaipuria_how'+key+'_error').innerHTML = '';
			$('Jaipuria_how'+key+'_error').parentNode.style.display = 'none';
	      }
	}
  }
  
  function validateJaipuriaPref(number, caption, maxLength, minLength) {
	var errorMessage = validateInteger(number, caption, maxLength, minLength);
	
		if(typeof(errorMessage) == 'string'){
			return errorMessage;
		}else{
			number = parseInt(number);
			if(number > 8 || number < 1){
				return "Preference should be between 1 and 8.";
			}else{
				var prefArray = new Array();
				var objectsArr = new Array("Jaipuria_PGDM_Lucknow","Jaipuria_PGDM_Noida","Jaipuria_PGDM_Jaipur","Jaipuria_PGDM_Indore","Jaipuria_PGDM_Financial_Sevices_Lucknow","Jaipuria_PGDM_Retail_Management_Lucknow","Jaipuria_PGDM_Marketing_Noida","Jaipuria_PGDM_Services_Noida");
				maxPref = 0;
				for(var i=0;i<objectsArr.length;i++){
					if($(objectsArr[i])){
						if($(objectsArr[i]).value != ""){
							if(typeof(prefArray[$(objectsArr[i]).value]) != 'undefined'){
								return "All Preference should be unique.";
							}else{
								prefArray[$(objectsArr[i]).value] = $(objectsArr[i]).value;
							}
							
						}
					}
				}
				
				for(var i=1;i<=(prefArray.length-1);i++){
					if(typeof(prefArray[i]) == 'undefined'){
						return "All Preference should be in a sequence. Don't skip numbers in between.";
					}
				}
			}
		}
		return true;
  }
  
  
</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Program Preference</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label>Choose program(s)  (Indicate your preference from 1 to 8): </label>
				<div class='fieldBoxLarge' style="width:400px">
				<div><input onclick="checkProgram(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="program"   name='Jaipuria_program[]' id='Jaipuria_program0'   value='PGDM'  title="Choose Program (Indicate your preference from 1 to 8)"   onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" >PGDM</span>&nbsp;&nbsp;
				</div><div><input onclick="checkProgram(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="program"   name='Jaipuria_program[]' id='Jaipuria_program1'   value='PGDM_Financial_Sevices'    title="Choose Program (Indicate your preference from 1 to 8)"   onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" >PGDM (Financial Services)</span>&nbsp;&nbsp;
				</div><div><input onclick="checkProgram(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="program"   name='Jaipuria_program[]' id='Jaipuria_program2'   value='PGDM_Retail_Management'    title="Choose Program (Indicate your preference from 1 to 8)"   onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" >PGDM (Retail Management)</span>&nbsp;&nbsp;
				</div><div><input onclick="checkProgram(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="program"   name='Jaipuria_program[]' id='Jaipuria_program3'   value='PGDM_Marketing'    title="Choose Program (Indicate your preference from 1 to 8)"   onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" >PGDM (Marketing)</span>&nbsp;&nbsp;
				</div><div><input onclick="checkProgram(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="program"   name='Jaipuria_program[]' id='Jaipuria_program4'   value='PGDM_Services'    title="Choose Program (Indicate your preference from 1 to 8)"   onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice.',this);" onmouseout="hidetip();" >PGDM (Service Management)</span>&nbsp;&nbsp;
				</div>
				<?php if(isset($Jaipuria_program) && $Jaipuria_program!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Jaipuria_program[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Jaipuria_program);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_program_error'></div></div>
				</div>
				</div>
			</li>

			<li id="PGDM" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>PGDM Lucknow: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Lucknow' id='Jaipuria_PGDM_Lucknow'  validate="validateJaipuriaPref"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Lucknow"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Lucknow) && $Jaipuria_PGDM_Lucknow!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Lucknow").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Lucknow );  ?>";
				      document.getElementById("Jaipuria_PGDM_Lucknow").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Lucknow_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>PGDM Noida: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Noida' id='Jaipuria_PGDM_Noida'  validate="validateJaipuriaPref"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Noida"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Noida) && $Jaipuria_PGDM_Noida!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Noida").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Noida );  ?>";
				      document.getElementById("Jaipuria_PGDM_Noida").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Noida_error'></div></div>
				</div>
				</div>
			</li>

			<li id="PGDM1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>PGDM Jaipur: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Jaipur' id='Jaipuria_PGDM_Jaipur'  validate="validateJaipuriaPref"     caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Jaipur"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Jaipur) && $Jaipuria_PGDM_Jaipur!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Jaipur").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Jaipur );  ?>";
				      document.getElementById("Jaipuria_PGDM_Jaipur").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Jaipur_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>PGDM Indore: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Indore' id='Jaipuria_PGDM_Indore'  validate="validateJaipuriaPref"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Indore"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Indore) && $Jaipuria_PGDM_Indore!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Indore").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Indore );  ?>";
				      document.getElementById("Jaipuria_PGDM_Indore").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Indore_error'></div></div>
				</div>
				</div>
			</li>

			<li id="PGDM_Financial_Sevices" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>PGDM (Financial Services) Lucknow: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Financial_Sevices_Lucknow' id='Jaipuria_PGDM_Financial_Sevices_Lucknow'  validate="validateJaipuriaPref"    caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Lucknow"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Financial_Sevices_Lucknow) && $Jaipuria_PGDM_Financial_Sevices_Lucknow!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Financial_Sevices_Lucknow").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Financial_Sevices_Lucknow );  ?>";
				      document.getElementById("Jaipuria_PGDM_Financial_Sevices_Lucknow").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Financial_Sevices_Lucknow_error'></div></div>
				</div>
				</div>
			</li>

			<li id="PGDM_Retail_Management" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>PGDM (Retail Management) Lucknow: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Retail_Management_Lucknow' id='Jaipuria_PGDM_Retail_Management_Lucknow'  validate="validateJaipuriaPref" caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Lucknow"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Retail_Management_Lucknow) && $Jaipuria_PGDM_Retail_Management_Lucknow!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Retail_Management_Lucknow").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Retail_Management_Lucknow );  ?>";
				      document.getElementById("Jaipuria_PGDM_Retail_Management_Lucknow").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Retail_Management_Lucknow_error'></div></div>
				</div>
				</div>
			</li>

			<li id="PGDM_Marketing" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>PGDM (Marketing) Noida: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Marketing_Noida' id='Jaipuria_PGDM_Marketing_Noida'  validate="validateJaipuriaPref"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Noida"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Marketing_Noida) && $Jaipuria_PGDM_Marketing_Noida!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Marketing_Noida").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Marketing_Noida );  ?>";
				      document.getElementById("Jaipuria_PGDM_Marketing_Noida").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Marketing_Noida_error'></div></div>
				</div>
				</div>
			</li>

			<li id="PGDM_Services" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>PGDM (Service Management) Noida: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_PGDM_Services_Noida' id='Jaipuria_PGDM_Services_Noida'  validate="validateJaipuriaPref"   caption="preference"   minlength="1"   maxlength="1"     tip="Please enter your preference for programmes in order of prority, 1 being the highest and 8 being the lowest. Select at least one choice."   title="Noida"  value=''   />
				<?php if(isset($Jaipuria_PGDM_Services_Noida) && $Jaipuria_PGDM_Services_Noida!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_PGDM_Services_Noida").value = "<?php echo str_replace("\n", '\n', $Jaipuria_PGDM_Services_Noida );  ?>";
				      document.getElementById("Jaipuria_PGDM_Services_Noida").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_PGDM_Services_Noida_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label></label>
				<div class='fieldBoxLarge'>
					<div style='display:none'><div class='errorMsg' id= 'Jaipuria_program_error1'></div></div>
				</div>
				</div>
			</li>
			<?php endif;?>
			<li>
				<h3 class="upperCase">Aptitute Test Details</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:400px">
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Jaipuria_testNames[]' id='Jaipuria_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Jaipuria_testNames[]' id='Jaipuria_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Jaipuria_testNames[]' id='Jaipuria_testNames2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Jaipuria_testNames[]' id='Jaipuria_testNames3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<?php if(isset($Jaipuria_testNames) && $Jaipuria_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Jaipuria_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Jaipuria_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_testNames_error'></div></div>
				</div>
				</div>
			</li>
				
			<li id="cat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'   required="true"         validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value=''  allowNA='true' />
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
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'     required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  allowNA='true'  />
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

			<li id="cat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'      readonly maxlength='10'  validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

			<li id="mat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>MAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'   required="true"         validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value='' allowNA='true'  />
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
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'     required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  allowNA='true'  />
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

			<li id="mat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>MAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'      readonly maxlength='10'  validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

			<li id="xat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>XAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   required="true"         validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value=''  allowNA='true' />
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
				<label>XAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'     required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value='' allowNA='true'  />
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

			<li id="xat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>XAT Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatDateOfExaminationAdditional' id='xatDateOfExaminationAdditional' readonly maxlength='10'      readonly maxlength='10'  validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('xatDateOfExaminationAdditional'),'xatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

			<li id="cmat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'          validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value=''  allowNA='true'  />
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
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'      validate="validateStr"    caption="Rank"   minlength="2"   maxlength="50"      tip="Mention your Rank in the exam. If you don't know your rank, enter <b>NA</b>".   value=''  allowNA='true'   />
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

			<li id="cmat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Date of Examination: </label>
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
			
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Contact Details</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Telephone Number (Office): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_telOffice' id='Jaipuria_telOffice'  validate="validateStr"   required="true"   caption="number"   minlength="2"   maxlength="20"     tip="Please enter the office telephone number. If this does not apply to you, enter <b>NA</b>."   title="Telephone Number (Office)"  value=''    allowNA = 'true' />
				<?php if(isset($Jaipuria_telOffice) && $Jaipuria_telOffice!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_telOffice").value = "<?php echo str_replace("\n", '\n', $Jaipuria_telOffice );  ?>";
				      document.getElementById("Jaipuria_telOffice").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_telOffice_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Telephone Number (Residence): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_telRes' id='Jaipuria_telRes'  validate="validateStr"   required="true"   caption="number"   minlength="2"   maxlength="20"     tip="Please enter the residence telephone number. If this does not apply to you, enter <b>NA</b>."   title="Telephone Number (Residence)"  value=''    allowNA = 'true' />
				<?php if(isset($Jaipuria_telRes) && $Jaipuria_telRes!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_telRes").value = "<?php echo str_replace("\n", '\n', $Jaipuria_telRes );  ?>";
				      document.getElementById("Jaipuria_telRes").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_telRes_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Additional Education Info</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th Subjects/Stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_10Stream' id='Jaipuria_10Stream'  validate="validateStr"   required="true"   caption="stream"   minlength="2"   maxlength="100"     tip="Enter the stream/subjects you had in class 10th. For eg: English, Hindi, Science etc. Enter all the subjects seperated by comma."   title="Class 10th Subjects/Stream"  value=''   />
				<?php if(isset($Jaipuria_10Stream) && $Jaipuria_10Stream!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_10Stream").value = "<?php echo str_replace("\n", '\n', $Jaipuria_10Stream );  ?>";
				      document.getElementById("Jaipuria_10Stream").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_10Stream_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Class 10th class/division: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_10Div' id='Jaipuria_10Div'  validate="validateStr"   required="true"   caption="division"   minlength="2"   maxlength="50"     tip="Enter the class or division that you scored in 10th board. For 1st division, enter 1st. If class/division is not applicable in your case, just enter <b>NA</b>."   title="Class 10th class/division"  value=''    allowNA = 'true' />
				<?php if(isset($Jaipuria_10Div) && $Jaipuria_10Div!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_10Div").value = "<?php echo str_replace("\n", '\n', $Jaipuria_10Div );  ?>";
				      document.getElementById("Jaipuria_10Div").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_10Div_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Subjects/Stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_12Stream' id='Jaipuria_12Stream'  validate="validateStr"   required="true"   caption="stream"   minlength="2"   maxlength="100"     tip="Enter the stream/subjects you had in class 12th. For eg: physics, Chemisty, Maths etc. Enter all the subjects seperated by comma."   title="Class 12th Subjects/Stream"  value=''   />
				<?php if(isset($Jaipuria_12Stream) && $Jaipuria_12Stream!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_12Stream").value = "<?php echo str_replace("\n", '\n', $Jaipuria_12Stream );  ?>";
				      document.getElementById("Jaipuria_12Stream").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_12Stream_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Class 12th class/division: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_12Div' id='Jaipuria_12Div'  validate="validateStr"   required="true"   caption="division"   minlength="2"   maxlength="50"     tip="Enter the class or division that you scored in 12th board. For 1st division, enter 1st. If class/division is not applicable in your case, just enter <b>NA</b>."   title="Class 12th class/division"  value=''    allowNA = 'true' />
				<?php if(isset($Jaipuria_12Div) && $Jaipuria_12Div!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_12Div").value = "<?php echo str_replace("\n", '\n', $Jaipuria_12Div );  ?>";
				      document.getElementById("Jaipuria_12Div").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_12Div_error'></div></div>
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
					}
				}
			}
			?>
			<li>
				<div class='additionalInfoLeftCol'>
				<label><?=$graduationCourseName?> Subjects/Stream: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_gradStream' id='Jaipuria_gradStream'  validate="validateStr"   required="true"   caption="stream"   minlength="2"   maxlength="100"     tip="Enter the stream/subjects you had in your graduation course. For eg: Mechanical Engineering, English Literature, Applied Mathematics etc. Enter all the subjects seperated by comma."   title="Graduation Subjects/Stream"  value=''   />
				<?php if(isset($Jaipuria_gradStream) && $Jaipuria_gradStream!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_gradStream").value = "<?php echo str_replace("\n", '\n', $Jaipuria_gradStream );  ?>";
				      document.getElementById("Jaipuria_gradStream").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_gradStream_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label><?=$graduationCourseName?> class/division: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_gradDiv' id='Jaipuria_gradDiv'  validate="validateStr"   required="true"   caption="division"   minlength="2"   maxlength="50"     tip="Enter the class or division that you scored in your graduation course. For 1st division, enter 1st. If class/division is not applicable in your case, just enter <b>NA</b>."   title="Graduation class/division"  value=''    allowNA = 'true' />
				<?php if(isset($Jaipuria_gradDiv) && $Jaipuria_gradDiv!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_gradDiv").value = "<?php echo str_replace("\n", '\n', $Jaipuria_gradDiv );  ?>";
				      document.getElementById("Jaipuria_gradDiv").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_gradDiv_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Professional Qualification Details</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Professional Qualification (eg: CA / ICWA / ICSI etc): </label>
				<div class='fieldBoxLarge'>
				<textarea name='Jaipuria_profQual' id='Jaipuria_profQual'  validate="validateStr"    caption="professional qualification"   minlength="50"   maxlength="1000"     tip="If you hold anly professional qualification like CA / ICWA etc, enter the details of your professional qualification like Institute name, qualification name, marks, percentage etc. If you DO NOT have a professional qualification, leave this field blank."   title="Professional Qualification (eg: CA / ICWA / ICSI etc)"   ></textarea>
				<?php if(isset($Jaipuria_profQual) && $Jaipuria_profQual!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_profQual").value = "<?php echo str_replace("\n", '\n', $Jaipuria_profQual );  ?>";
				      document.getElementById("Jaipuria_profQual").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_profQual_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Acedemic/Professional Accomplishment (Awards/Medals/Prizes/Scholarships/Certificates/Honours Etc)(Optional)</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of award(1st): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardName1' id='Jaipuria_awardName1'  validate="validateStr"    caption="award name"   minlength="2"   maxlength="50"     tip="Enter the name of award or recognition that you received. If this does not apply to you, leave this field blank."   title="Name of award"  value=''   />
				<?php if(isset($Jaipuria_awardName1) && $Jaipuria_awardName1!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardName1").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardName1 );  ?>";
				      document.getElementById("Jaipuria_awardName1").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardName1_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardInstitute1' id='Jaipuria_awardInstitute1'  validate="validateStr"    caption="award institute"   minlength="2"   maxlength="50"     tip="Enter the name of the awarding institution that gave you this award. If this does not apply to you, leave this field blank."   title="Awarding Institution"  value=''   />
				<?php if(isset($Jaipuria_awardInstitute1) && $Jaipuria_awardInstitute1!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardInstitute1").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardInstitute1 );  ?>";
				      document.getElementById("Jaipuria_awardInstitute1").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardInstitute1_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardYear1' id='Jaipuria_awardYear1'  validate="validateInteger"    caption="award year"   minlength="4"   maxlength="4"     tip="Enter the year when you received this award/recognition. If this does not apply to you, leave this field blank."   title="Year"  value=''   />
				<?php if(isset($Jaipuria_awardYear1) && $Jaipuria_awardYear1!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardYear1").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardYear1 );  ?>";
				      document.getElementById("Jaipuria_awardYear1").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardYear1_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of award(2nd): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardName2' id='Jaipuria_awardName2'  validate="validateStr"    caption="award name"   minlength="2"   maxlength="50"     tip="Enter the name of award or recognition that you received. If this does not apply to you, leave this field blank."   title="Name of award"  value=''   />
				<?php if(isset($Jaipuria_awardName2) && $Jaipuria_awardName2!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardName2").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardName2 );  ?>";
				      document.getElementById("Jaipuria_awardName2").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardName2_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardInstitute2' id='Jaipuria_awardInstitute2'  validate="validateStr"    caption="award institute"   minlength="2"   maxlength="50"     tip="Enter the name of the awarding institution that gave you this award. If this does not apply to you, leave this field blank."   title="Awarding Institution"  value=''   />
				<?php if(isset($Jaipuria_awardInstitute2) && $Jaipuria_awardInstitute2!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardInstitute2").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardInstitute2 );  ?>";
				      document.getElementById("Jaipuria_awardInstitute2").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardInstitute2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardYear2' id='Jaipuria_awardYear2'  validate="validateInteger"    caption="award year"   minlength="4"   maxlength="4"     tip="Enter the year when you received this award/recognition. If this does not apply to you, leave this field blank."   title="Year"  value=''   />
				<?php if(isset($Jaipuria_awardYear2) && $Jaipuria_awardYear2!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardYear2").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardYear2 );  ?>";
				      document.getElementById("Jaipuria_awardYear2").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardYear2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Name of award(3rd): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardName3' id='Jaipuria_awardName3'  validate="validateStr"    caption="award name"   minlength="2"   maxlength="50"     tip="Enter the name of award or recognition that you received. If this does not apply to you, leave this field blank."   title="Name of award"  value=''   />
				<?php if(isset($Jaipuria_awardName3) && $Jaipuria_awardName3!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardName3").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardName3 );  ?>";
				      document.getElementById("Jaipuria_awardName3").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardName3_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardInstitute3' id='Jaipuria_awardInstitute3'  validate="validateStr"    caption="award institute"   minlength="2"   maxlength="50"     tip="Enter the name of the awarding institution that gave you this award. If this does not apply to you, leave this field blank."   title="Awarding Institution"  value=''   />
				<?php if(isset($Jaipuria_awardInstitute3) && $Jaipuria_awardInstitute3!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardInstitute3").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardInstitute3 );  ?>";
				      document.getElementById("Jaipuria_awardInstitute3").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardInstitute3_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_awardYear3' id='Jaipuria_awardYear3'  validate="validateInteger"    caption="award year"   minlength="4"   maxlength="4"     tip="Enter the year when you received this award/recognition. If this does not apply to you, leave this field blank."   title="Year"  value=''   />
				<?php if(isset($Jaipuria_awardYear3) && $Jaipuria_awardYear3!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_awardYear3").value = "<?php echo str_replace("\n", '\n', $Jaipuria_awardYear3 );  ?>";
				      document.getElementById("Jaipuria_awardYear3").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_awardYear3_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class="upperCase">Extra Info</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Extra Curricular Activities/Hobbies: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Jaipuria_activities' id='Jaipuria_activities'  validate="validateStr"    caption="activities"   minlength="50"   maxlength="1000"     tip="Enter any Extra curricular/ Community/ Cultural Activities/ Sports/ Games or Hobbies that you pursued. Indicate position or office held if any. Eg: Captain of university debating team from year 2009-2010 etc. If this does not apply to you, leave this field blank."   title="Extra Curricular Activities/Hobbies"  ></textarea>
				<?php if(isset($Jaipuria_activities) && $Jaipuria_activities!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_activities").value = "<?php echo str_replace("\n", '\n', $Jaipuria_activities );  ?>";
				      document.getElementById("Jaipuria_activities").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_activities_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Why do you want to join Jaipuria?: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Jaipuria_whyJoin' id='Jaipuria_whyJoin'  validate="validateStr"   required="true"   caption="why do you want to join Jaipuria"   minlength="50"   maxlength="1000"     tip="Explain in detail why do you want to join Jaipuria Institute. Write a detail not of not less than 50 words, explaining your reasons for choosing Jaipuria."   title="Why do you want to join Jaipuria?"   ></textarea>
				<?php if(isset($Jaipuria_whyJoin) && $Jaipuria_whyJoin!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_whyJoin").value = "<?php echo str_replace("\n", '\n', $Jaipuria_whyJoin );  ?>";
				      document.getElementById("Jaipuria_whyJoin").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_whyJoin_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>What are your strengths? List at least 2 strengths: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Jaipuria_strengths' id='Jaipuria_strengths'  validate="validateStr"   required="true"   caption="strengths"   minlength="50"   maxlength="1000"     tip="Explain in detail what are your strengths and how they can help you in progressing in life."   title="What are your strengths? List at least 2 strengths"   ></textarea>
				<?php if(isset($Jaipuria_strengths) && $Jaipuria_strengths!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_strengths").value = "<?php echo str_replace("\n", '\n', $Jaipuria_strengths );  ?>";
				      document.getElementById("Jaipuria_strengths").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_strengths_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>What are your weaknesses? List at least 2 weaknesses: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Jaipuria_weakness' id='Jaipuria_weakness'  validate="validateStr"   required="true"   caption="weaknesses"   minlength="50"   maxlength="1000"     tip="Explain in detail what are your weaknesses"   title="What are your weaknesses? List at least 2 weaknesses"   ></textarea>
				<?php if(isset($Jaipuria_weakness) && $Jaipuria_weakness!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_weakness").value = "<?php echo str_replace("\n", '\n', $Jaipuria_weakness );  ?>";
				      document.getElementById("Jaipuria_weakness").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_weakness_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>What challenges do you foresee as a manager in emerging global environment?: </label>
				<div class='fieldBoxLarge'>
				<textarea name='Jaipuria_challenges' id='Jaipuria_challenges'  validate="validateStr"   required="true"   caption="challenges"   minlength="50"   maxlength="1000"     tip="Write a detailed essay of not less than 100 words about the challenges that you foresee as a manager in emerging global environment."   title="What challenges do you foresee as a manager in emerging global environment?"   ></textarea>
				<?php if(isset($Jaipuria_challenges) && $Jaipuria_challenges!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_challenges").value = "<?php echo str_replace("\n", '\n', $Jaipuria_challenges );  ?>";
				      document.getElementById("Jaipuria_challenges").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_challenges_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Would you require hostel accomodation at Jaipuria? Hostel is compulsary for outstation candidates: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="hostel"  name='Jaipuria_hostel' id='Jaipuria_hostel0'   value='Yes'  title="Would you require hostel accomodation at Jaipuria? Hostel is compulsary for outstation candidates"   onmouseover="showTipOnline('Please mention your choice for hostel. If you are outstation candidate, select YES.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention your choice for hostel. If you are outstation candidate, select YES.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="hostel"  name='Jaipuria_hostel' id='Jaipuria_hostel1'   value='No'    title="Would you require hostel accomodation at Jaipuria? Hostel is compulsary for outstation candidates"   onmouseover="showTipOnline('Please mention your choice for hostel. If you are outstation candidate, select YES.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please mention your choice for hostel. If you are outstation candidate, select YES.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($Jaipuria_hostel) && $Jaipuria_hostel!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["Jaipuria_hostel"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $Jaipuria_hostel;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_hostel_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>How did you find out about Jaipuria?: </label>
				<div class='fieldBoxLarge'>
				<div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how0'   value='Alumni'  title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Alumni</span>&nbsp;&nbsp;
				</div><div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how1'   value='FriendRelativeParent'    title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Friend/Relative/Parent</span>&nbsp;&nbsp;
				</div><div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how2'   value='Website'    title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Website</span>&nbsp;&nbsp;
				</div><div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how3'   value='CoachingInstitute'    title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Coaching Institute</span>&nbsp;&nbsp;
				</div><div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how4'   value='Newspaper'    title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Newspaper</span>&nbsp;&nbsp;
				</div><div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how5'   value='Magazine'    title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Magazine</span>&nbsp;&nbsp;
				</div><div><input onclick="checkHow(this)" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="how"   name='Jaipuria_how[]' id='Jaipuria_how6'   value='Others'    title="How did you find out about Jaipuria?"   onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter the source(s) from where you heard about Jaipuria. Please mention detail about your source.',this);" onmouseout="hidetip();" >Others (pls specify)</span>&nbsp;&nbsp;
				</div>
				<?php if(isset($Jaipuria_how) && $Jaipuria_how!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Jaipuria_how[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Jaipuria_how);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_how_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Alumni" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Alumni: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howAlumni' id='Jaipuria_howAlumni'  validate="validateStr"   required="true"   caption="name of alumni"   minlength="2"   maxlength="50"     tip=" Please mention the alumni name"   title="Alumni"  value=''   />
				<?php if(isset($Jaipuria_howAlumni) && $Jaipuria_howAlumni!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howAlumni").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howAlumni );  ?>";
				      document.getElementById("Jaipuria_howAlumni").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howAlumni_error'></div></div>
				</div>
				</div>
			</li>

			<li id="FriendRelativeParent" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Friend/Relative/Parent: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howFriendRelativeParent' id='Jaipuria_howFriendRelativeParent'  validate="validateStr"   required="true"   caption="name of friend/relative"   minlength="2"   maxlength="50"     tip=" Please mention the name of Friend/Relative/Parent"   title="Friend/Relative/Parent"  value=''   />
				<?php if(isset($Jaipuria_howFriendRelativeParent) && $Jaipuria_howFriendRelativeParent!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howFriendRelativeParent").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howFriendRelativeParent );  ?>";
				      document.getElementById("Jaipuria_howFriendRelativeParent").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howFriendRelativeParent_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Website" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Website: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howWebsite' id='Jaipuria_howWebsite'  validate="validateStr"   required="true"   caption="name of website"   minlength="2"   maxlength="50"     tip=" Please mention the name of Website"   title="Website"  value=''   />
				<?php if(isset($Jaipuria_howWebsite) && $Jaipuria_howWebsite!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howWebsite").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howWebsite );  ?>";
				      document.getElementById("Jaipuria_howWebsite").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howWebsite_error'></div></div>
				</div>
				</div>
			</li>

			<li id="CoachingInstitute" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Coaching Institute: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howCoachingInstitute' id='Jaipuria_howCoachingInstitute'  validate="validateStr"   required="true"   caption="name of institute"   minlength="2"   maxlength="50"     tip=" Please mention the name of Coaching Institute"   title="Coaching Institute"  value=''   />
				<?php if(isset($Jaipuria_howCoachingInstitute) && $Jaipuria_howCoachingInstitute!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howCoachingInstitute").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howCoachingInstitute );  ?>";
				      document.getElementById("Jaipuria_howCoachingInstitute").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howCoachingInstitute_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Newspaper" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Newspaper: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howNewspaper' id='Jaipuria_howNewspaper'  validate="validateStr"   required="true"   caption="name of newspaper"   minlength="2"   maxlength="50"     tip=" Please mention the name of Newspaper"   title="Newspaper"  value=''   />
				<?php if(isset($Jaipuria_howNewspaper) && $Jaipuria_howNewspaper!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howNewspaper").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howNewspaper );  ?>";
				      document.getElementById("Jaipuria_howNewspaper").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howNewspaper_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Magazine" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Magazine: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howMagazine' id='Jaipuria_howMagazine'  validate="validateStr"   required="true"   caption="name of magazine"   minlength="2"   maxlength="50"     tip=" Please mention the name of Magazine"   title="Magazine"  value=''   />
				<?php if(isset($Jaipuria_howMagazine) && $Jaipuria_howMagazine!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howMagazine").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howMagazine );  ?>";
				      document.getElementById("Jaipuria_howMagazine").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howMagazine_error'></div></div>
				</div>
				</div>
			</li>

			<li id="Others" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>Others (pls specify): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Jaipuria_howOthers' id='Jaipuria_howOthers'  validate="validateStr"   required="true"   caption="name of source"   minlength="2"   maxlength="50"     tip=" Please mention Others"   title="Others (pls specify)"  value=''   />
				<?php if(isset($Jaipuria_howOthers) && $Jaipuria_howOthers!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_howOthers").value = "<?php echo str_replace("\n", '\n', $Jaipuria_howOthers );  ?>";
				      document.getElementById("Jaipuria_howOthers").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_howOthers_error'></div></div>
				</div>
				</div>
			</li>
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>CA & PI CENTRE</h3>
				<label style='font-weight:normal'>Preferred CA & PI CENTRE: </label>
				<div class='fieldBoxLarge' style="width:600px">
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
			<li>
				<div><i> The selected venue is tentative and may subject to change. Refer website for latest update. <a href="http://www.jaipuria.ac.in/" target="_blank">www.jaipuria.ac.in</a> </i></div>
			</li>
			<?php endif; ?>
	
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I certify that the particulars given by me are true to the best of my knowledge and belief. I understand that Jaipuria Institute of Management will have the right to cancel my admission and ask me to withdraw from the programme if any discrepancies are found in the information furnished. I will also abide by the general discipline and norms of conduct during the programme.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='Jaipuria_agreeToTerms[]' id='Jaipuria_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ><span>&nbsp;&nbsp;
				<?php if(isset($Jaipuria_agreeToTerms) && $Jaipuria_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Jaipuria_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Jaipuria_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'Jaipuria_agreeToTerms0_error'></div></div>
				</div>
				</div>
			</li>
			

			<li>
				<input type='hidden' name='Jaipuria_Course_Pref_1' id='Jaipuria_Course_Pref_1'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_1) && $Jaipuria_Course_Pref_1!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_1").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_1 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_1").style.color = "";
				  </script>
				<?php } ?>
		
				<input type='hidden' name='Jaipuria_Course_Pref_2' id='Jaipuria_Course_Pref_2'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_2) && $Jaipuria_Course_Pref_2!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_2").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_2 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_2").style.color = "";
				  </script>
				<?php } ?>
				
		
				<input type='hidden' name='Jaipuria_Course_Pref_3' id='Jaipuria_Course_Pref_3'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_3) && $Jaipuria_Course_Pref_3!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_3").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_3 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_3").style.color = "";
				  </script>
				<?php } ?>
				
			
				<input type='hidden' name='Jaipuria_Course_Pref_4' id='Jaipuria_Course_Pref_4'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_4) && $Jaipuria_Course_Pref_4!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_4").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_4 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_4").style.color = "";
				  </script>
				<?php } ?>
				
			
				<input type='hidden' name='Jaipuria_Course_Pref_5' id='Jaipuria_Course_Pref_5'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_5) && $Jaipuria_Course_Pref_5!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_5").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_5 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_5").style.color = "";
				  </script>
				<?php } ?>
			
				<input type='hidden' name='Jaipuria_Course_Pref_6' id='Jaipuria_Course_Pref_6'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_6) && $Jaipuria_Course_Pref_6!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_6").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_6 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_6").style.color = "";
				  </script>
				<?php } ?>
			
				<input type='hidden' name='Jaipuria_Course_Pref_7' id='Jaipuria_Course_Pref_7'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_7) && $Jaipuria_Course_Pref_7!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_7").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_7 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_7").style.color = "";
				  </script>
				<?php } ?>
				
				
				<input type='hidden' name='Jaipuria_Course_Pref_8' id='Jaipuria_Course_Pref_8'    value=''  />
				<?php if(isset($Jaipuria_Course_Pref_8) && $Jaipuria_Course_Pref_8!=""){ ?>
				  <script>
				      document.getElementById("Jaipuria_Course_Pref_8").value = "<?php echo str_replace("\n", '\n', $Jaipuria_Course_Pref_8 );  ?>";
				      document.getElementById("Jaipuria_Course_Pref_8").style.color = "";
				  </script>
				<?php } ?>
				
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
	for(var j=0; j<4; j++){
		checkTestScore(document.getElementById('Jaipuria_testNames'+j));
	}
	for(var j=0; j<7; j++){
		checkHow(document.getElementById('Jaipuria_how'+j));
	}
	for(var j=0; j<5; j++){
		checkProgram(document.getElementById('Jaipuria_program'+j));
	}
	
	
	
  </script>
