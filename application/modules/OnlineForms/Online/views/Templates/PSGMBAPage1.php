<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"PercentileAdditional",key+"RankAdditional",key+"DateOfExaminationAdditional");
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
  
 
  
  function setGurdian(obj,reset){
	
	
	if(obj && obj.checked){
		if(obj.value == "Other"){
			$('guardian').style.display = '';
			if(reset){
				$('PSG_guardianName').value = '';
				$('PSG_guardianOccupation').value = '';
			}
			
		}else if(obj.value == "Father"){
			$('guardian').style.display = 'none';
			$('PSG_guardianName').value = $('PSG_fatherName').value;
			$('PSG_guardianOccupation').value = $('PSG_fatherOccupation').value;
			if($('PSG_guardianOccupation').value ==  ''){
				$('guardian').style.display = '';
			}
		}else if(obj.value == "Mother"){
			$('guardian').style.display = 'none';
			$('PSG_guardianName').value = $('PSG_MotherName').value;
			$('PSG_guardianOccupation').value = $('PSG_MotherOccupation').value;
			if($('PSG_guardianOccupation').value == ''){
				$('guardian').style.display = '';
			}
		}
	}
  }
</script>

<input type="hidden" id="PSG_fatherName" name="PSG_fatherName" value="<?=$PSG_fatherName?>">
<input type="hidden" id="PSG_fatherOccupation" name="PSG_fatherOccupation" value="<?=$PSG_fatherOccupation?>">
<input type="hidden" id="PSG_MotherName" name="PSG_MotherName" value="<?=$PSG_MotherName?>">
<input type="hidden" id="PSG_MotherOccupation" name="PSG_MotherOccupation" value="<?=$PSG_MotherOccupation?>">

<div class='formChildWrapper'>

	<div class='formSection'>

		<ul>
			<?php if($action != 'updateScore'):?>
			<li>

				<h3 class="upperCase">Course Selection</h3>

				</li>

				<li>

				<div class='additionalInfoLeftCol' style="width: 600px;">

				<label>Course Applied For: </label>

				<div class='fieldBoxLarge' style="width: 270px;">

				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="courses you want to apply for"   name='PSG_course[]' id='PSG_course0'   value='PGDM'   title="Course Applied For"   onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" >PGDM</span>&nbsp;&nbsp;

				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="courses you want to apply for"   name='PSG_course[]' id='PSG_course1'   value='MBA'    title="Course Applied For"   onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" >MBA</span>&nbsp;&nbsp;

                                <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="courses you want to apply for"   name='PSG_course[]' id='PSG_course2'   value='MBA - Part Time'    title="Course Applied For"   onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" >MBA - Part Time</span>&nbsp;&nbsp;

				<?php if(isset($PSG_course) && $PSG_course!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["PSG_course[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$PSG_course);
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

				

				<div style='display:none'><div class='errorMsg' id= 'PSG_course_error'></div></div>

				</div>

				</div>
				
				<div class='additionalInfoRightCol' style="width:310px">

				
				<div class='fieldBoxLarge'  style="width:400px">
					<b>Note: </b>Fees for one course is Rs. 1000, two courses<br/> is Rs. 2000 and three courses is Rs. 3000.				</div>
				</div>

			</li>



			<li>

				<h3 class="upperCase">Personal Information</h3>

				</li>

				<li>

				<div class='additionalInfoLeftCol'>

				<label>Legal Guardian: </label>

				<div class='fieldBoxLarge'>

				<input type='radio' onclick="setGurdian(this,true);"  validate="validateCheckedGroup"   required="true"   caption="legal guardian"  name='PSG_guardian' id='PSG_guardian0'   value='Father'  title="Legal Guardian"   onmouseover="showTipOnline('Please select who is your legal guardian.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select who is your legal guardian.',this);" onmouseout="hidetip();" >Father</span>&nbsp;&nbsp;

				<br><input type='radio' onclick="setGurdian(this,true);"  validate="validateCheckedGroup"   required="true"   caption="legal guardian"  name='PSG_guardian' id='PSG_guardian1'   value='Mother'    title="Legal Guardian"   onmouseover="showTipOnline('Please select who is your legal guardian.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select who is your legal guardian.',this);" onmouseout="hidetip();" >Mother</span>&nbsp;&nbsp;

				<br><input type='radio' onclick="setGurdian(this,true);"  validate="validateCheckedGroup"   required="true"   caption="legal guardian"  name='PSG_guardian' id='PSG_guardian2'   value='Other'    title="Legal Guardian"   onmouseover="showTipOnline('Please select who is your legal guardian.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select who is your legal guardian.',this);" onmouseout="hidetip();" >Other</span>&nbsp;&nbsp;

				<?php if(isset($PSG_guardian) && $PSG_guardian!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["PSG_guardian"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $PSG_guardian;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>

				

				<div style='display:none'><div class='errorMsg' id= 'PSG_guardian_error'></div></div>

				</div>

				</div>

			</li>



			<li id="guardian" style="display:none">

				<div class='additionalInfoLeftCol'>

				<label>Name of Guardian: </label>

				<div class='fieldBoxLarge'>

				<input type='text' name='PSG_guardianName' id='PSG_guardianName'  validate="validateStr"   required="true"   caption="guardian name"   minlength="2"   maxlength="50"     tip="Please enter the name of your legal guardian."   title="Name of Guardian"  value=''   />

				<?php if(isset($PSG_guardianName) && $PSG_guardianName!=""){ ?>
				  <script>
				      document.getElementById("PSG_guardianName").value = "<?php echo str_replace("\n", '\n', $PSG_guardianName );  ?>";
				      document.getElementById("PSG_guardianName").style.color = "";
				  </script>
				<?php } ?>

				

				<div style='display:none'><div class='errorMsg' id= 'PSG_guardianName_error'></div></div>

				</div>

				</div>

			
				<div class='additionalInfoRightCol'>

				<label>Occupation of guardian: </label>

				<div class='fieldBoxLarge'>

				<input type='text' name='PSG_guardianOccupation' id='PSG_guardianOccupation'  validate="validateStr"   required="true"   caption="guardian occupation"   minlength="2"   maxlength="50"     tip="Please enter the occupation of your legal guardian."   title="Occupation of guardian"  value=''   />

				<?php if(isset($PSG_guardianOccupation) && $PSG_guardianOccupation!=""){ ?>
				  <script>
				      document.getElementById("PSG_guardianOccupation").value = "<?php echo str_replace("\n", '\n', $PSG_guardianOccupation );  ?>";
				      document.getElementById("PSG_guardianOccupation").style.color = "";
				  </script>
				<?php } ?>

				

				<div style='display:none'><div class='errorMsg' id= 'PSG_guardianOccupation_error'></div></div>

				</div>

				</div>

			</li>



			<li>

				<h3 class="upperCase">Course Selection</h3>

				</li>

				<li>

				<div class='additionalInfoLeftCol'>

				<label>Community: </label>

				<div class='fieldBoxLarge'>

				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="community"   name='PSG_community[]' id='PSG_community0'   value='FC'  title="Community"   onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" >FC</span>&nbsp;&nbsp;

				<span style="margin-left:7px">&nbsp;</span><input type='radio'  validate="validateCheckedGroup"   required="true"   caption="community"   name='PSG_community[]' id='PSG_community1'   value='BC'    title="Community"   onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" >BC</span>&nbsp;&nbsp;

				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="community"   name='PSG_community[]' id='PSG_community2'   value='MBC'    title="Community"   onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" >MBC</span>&nbsp;&nbsp;

				<br/><input type='radio'  validate="validateCheckedGroup"   required="true"   caption="community"   name='PSG_community[]' id='PSG_community3'   value='DNC'    title="Community"   onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" >DNC</span>&nbsp;&nbsp;

				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="community"   name='PSG_community[]' id='PSG_community4'   value='SC'    title="Community"   onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;

				<input type='radio'  validate="validateCheckedGroup"   required="true"   caption="community"   name='PSG_community[]' id='PSG_community5'   value='ST'    title="Community"   onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your community.<br> <b>FC: </b>Forward Class<br> <b>BC: </b>Backward class<br> <b>MBC: </b>Most Backward class<br> <b>DNC: </b>Denotified Class<br> <b>SC: </b>Scheduled Caste<br> <b>ST: </b>Scheduled Tribe',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;

				<?php if(isset($PSG_community) && $PSG_community!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["PSG_community[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$PSG_community);
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

				

				<div style='display:none'><div class='errorMsg' id= 'PSG_community_error'></div></div>

				</div>

				</div>

			</li>
			<?php endif; ?>
			<li>
				<h3 class="upperCase">Entrance Score (Marks)</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:400px">
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='PSG_testNames[]' id='PSG_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='PSG_testNames[]' id='PSG_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='PSG_testNames[]' id='PSG_testNames2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<?php if(isset($PSG_testNames) && $PSG_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["PSG_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$PSG_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'PSG_testNames_error'></div></div>
				</div>
				</div>
			</li>
				
			<li id="cat" style="display:none">
		
		
				<div class='additionalInfoLeftCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'     required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  allowNA="true"  />
				<?php if(isset($catPercentileAdditional) && $catPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("catPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $catPercentileAdditional );  ?>";
				      document.getElementById("catPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'catPercentileAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
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
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'     required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''   allowNA="true" />
				<?php if(isset($matPercentileAdditional) && $matPercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("matPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $matPercentileAdditional );  ?>";
				      document.getElementById("matPercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matPercentileAdditional_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
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


			<li id="cmat" style="display:none">
				
				<div class='additionalInfoLeftCol'>
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'      validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"      tip="Mention your Rank in the exam. If you don't know your rank, enter <b>NA</b>."   value=''  allowNA="true"  />
				<?php if(isset($cmatRankAdditional) && $cmatRankAdditional!=""){ ?>
				  <script>
				      document.getElementById("cmatRankAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRankAdditional );  ?>";
				      document.getElementById("cmatRankAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatRankAdditional_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
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
			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>If selected for interview please select your preferred centre</h3>
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

			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I declare that the particulars given above are true and correct to the best of my knowledge.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='PSG_agreeToTerms[]' id='PSG_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($PSG_agreeToTerms) && $PSG_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["PSG_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$PSG_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'PSG_agreeToTerms0_error'></div></div>
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
  
	for(var j=0; j<3; j++){
		checkTestScore(document.getElementById('PSG_testNames'+j));
	}
	for(var j=0; j<3; j++){
		setGurdian(document.getElementById('PSG_guardian'+j,false));
	}
	
	
	
  

  </script>
