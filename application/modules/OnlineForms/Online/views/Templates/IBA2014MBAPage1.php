<script>
	
  function toggleSourceTextBox(objId,txt){
	if(objId.checked==true){
		$(txt+'SourceIBA').disabled = false;
                $(txt+'SourceIBA').setAttribute('required','true');
      }
      else{
          $(txt+'SourceIBA').value = '';
          $(txt+'SourceIBA').disabled = true;
          $(txt+'SourceIBA').removeAttribute('required');
          $(txt+'SourceIBA_error').innerHTML = '';
          $(txt+'SourceIBA_error').parentNode.style.display = 'none';
      }
  }
  
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "MAT" || obj.value == "XAT" || obj.value == "ATMA" || obj.value == "CAT" || obj.value == "GMAT"){ 
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	    
	}
	if(obj.value == "CMAT"){
		 var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditionalIBA",key+"RankAdditional");
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
			$(key+'3').style.display = '';
		    //Set the required paramters when any Exam is shown
		    setExamFields(objects1);
	      }
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
	    document.getElementById(objectsArr[i]).removeAttribute('required');
	    document.getElementById(objectsArr[i]+'_error').innerHTML = '';
	    document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
	}
  }

  function checkValidCampusPreference(id){
	if(id==1){ id='preferredGDPILocation'; sId = 'pref2IBA';}
	else if(id==2){ id='pref2IBA'; sId = 'preferredGDPILocation';}
	var selectedPrefObj = document.getElementById(id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].innerHTML;
	var selObj1 = document.getElementById(sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].innerHTML;
	if( (selectedPref == selPref1 && selectedPref!='' )){
		$(id+'_error').innerHTML = 'Same preference can\'t be set.';
		$(id+'_error').parentNode.style.display = '';
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
		$(id+'_error').innerHTML = '';
		$(id+'_error').parentNode.style.display = 'none';
	}
	return true;
  }
</script>
<?php foreach($basicInformation as $key=>$value){
	if($value['fieldName']=='gender' ){
		$gender = $value['value'];
		break;
	}
}
if($gender=='MALE'){
	$salutationIBA = 'Mr.';
}else{
	$salutationIBA = 'Ms.';
}
?>

<input type="hidden" value="<?php echo $salutationIBA;?>" name="salutationIBA" id="salutationIBA" />
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			
                        <li>
				<h3 class="upperCase">Course Selection</h3>
                        </li>


                        <li>

                                <div class='additionalInfoLeftCol' style="width: 580px;">
                                <label>Course Applied For: </label>
                                <div class='fieldBoxLarge' style="width: 250px;">
                                <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="courses you want to apply for"   name='IBA_course[]' id='IBA_course0'   value='PGDM'   title="Course Applied For"   onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" >PGDM</span>&nbsp;&nbsp;
                                <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="courses you want to apply for"   name='IBA_course[]' id='IBA_course1'   value='MBA'    title="Course Applied For"   onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select courses you want to apply for',this);" onmouseout="hidetip();" >MBA</span>&nbsp;&nbsp;
                                <?php if(isset($IBA_course) && $IBA_course!=""){ ?>
                                <script>
                                    objCheckBoxes = document.forms["OnlineForm"].elements["IBA_course[]"];
                                    var countCheckBoxes = objCheckBoxes.length;
                                    for(var i = 0; i < countCheckBoxes; i++){
                                              objCheckBoxes[i].checked = false;

                                              <?php $arr = explode(",",$IBA_course);
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

                                <div style='display:none'><div class='errorMsg' id= 'IBA_course_error'></div></div>
                                </div>
                                </div>
                                <div class='additionalInfoRightCol' style="width:310px">
					<div class='fieldBoxLarge'  style="width:400px">
                                       <b>Note: </b>Fees for one course is Rs. 750 and two courses<br/> is Rs. 810 only.
					</div>
                                </div>
                        </li>
			
			<li>
				<h3 class="upperCase">Age</h3>
				<div class='additionalInfoLeftCol'>
				<label>Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ageIBA' id='ageIBA'  required="true"  tip="Enter you age in years."  validate="validateInteger"   required="true"   caption="year"   minlength="2"   maxlength="2" value=""  />
				<?php if(isset($ageIBA) && $ageIBA!=""){ ?>
				  <script>
				      document.getElementById("ageIBA").value = "<?php echo str_replace("\n", '\n', $ageIBA );  ?>";
				      document.getElementById("ageIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%">
				<h3 class="upperCase">Category</h3>
				<div class='additionalInfoLeftCol'  style="width:930px;">
				<label>Category: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input type='radio'   required="true"   name='categoryIBA' id='categoryIBA0'   value='GEN'  checked   onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >General</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='categoryIBA' id='categoryIBA1'   value='SC'   onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='categoryIBA' id='categoryIBA2'   value='ST'   onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='categoryIBA' id='categoryIBA3'   value='OBC'  onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >OBC</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='categoryIBA' id='categoryIBA4'   value='PH'   onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category that applies to you.',this);" onmouseout="hidetip();" >PH</span>&nbsp;&nbsp;
				<?php if(isset($categoryIBA) && $categoryIBA!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["categoryIBA"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $categoryIBA;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryIBA_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif;?>
			<li style="width:100%">
				<h3 class="upperCase">Aptitude Test Appeared</h3>
				<div class='additionalInfoLeftCol'  style="width:930px;">
				<label>TESTS: </label>
				<div class='fieldBoxLarge'  style="width:590px;">
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIBA[]' id='testNamesIBA0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIBA[]' id='testNamesIBA1'   value='XAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIBA[]' id='testNamesIBA2'   value='ATMA'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIBA[]' id='testNamesIBA3'   value='MAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIBA[]' id='testNamesIBA4'   value='CMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesIBA[]' id='testNamesIBA5'   value='GMAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<?php if(isset($testNamesIBA) && $testNamesIBA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesIBA[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesIBA);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesIBA_error'></div></div>
				</div>
				</div>
			</li>
			<li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
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
				
				<div class='additionalInfoRightCol'>
				<label>CAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  />
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
			    if(isset($testNamesIBA) && $testNamesIBA!="" && strpos($testNamesIBA,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIBA0'));
			    </script>
			<?php
			    }
			?>
			<li id="xat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
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
				
				<div class="additionalInfoRightCol">
				<label>XAT Percentile: </label>
				<div class='float_L'>
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
			    if(isset($testNamesIBA) && $testNamesIBA!="" && strpos($testNamesIBA,'XAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIBA1'));
			    </script>
			<?php
			    }
			?> 
			<li id='atma1' style="display:none;">
				<div class="additionalInfoLeftCol">
                                <label style="font-weight:normal">ATMA REGN NO: </label>
                                <div class='fieldBoxLarge'>
                                <input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the ATMA exam. If you do not have the roll number, enter NA"   value=''  />
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
				<label>ATMA Exam Date: </label>
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
                                <input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
                                <?php if(isset($atmaScoreAdditional) && $atmaScoreAdditional!=""){ ?>
                                  <script>
                                      document.getElementById("atmaScoreAdditional").value = "<?php echo str_replace("\n", '\n', $atmaScoreAdditional );  ?>";
                                      document.getElementById("atmaScoreAdditional").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'atmaScoreAdditional_error'></div></div>
                                </div>
				</div>
				
				<div class="additionalInfoRightCol">
                                <label>ATMA Percentile: </label>
                                <div class='float_L'>
                                   <input class="textboxSmaller"  type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateFloat"  allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the atma exam. If you don't know your percentile, enter NA."   value=''  />
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
			<?php
			    if(isset($testNamesIBA) && $testNamesIBA!="" && strpos($testNamesIBA,'ATMA')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIBA2'));
			    </script>
			<?php
			    }
			?>
			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true'    value=''   />
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
				
				<div class="additionalInfoRightCol">
				<label>MAT Percentile: </label>
				<div class='float_L'>
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
			<?php
			    if(isset($testNamesIBA) && $testNamesIBA!="" && strpos($testNamesIBA,'MAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIBA3'));
			    </script>
			<?php
			    }
			?>
			<li id="cmat1" style="display:none;">
				<div class="additionalInfoLeftCol">
                                <label>CMAT REGN NO: </label>
                                <div class='fieldBoxLarge'>
                                <input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"  allowNA="true"  caption="Roll Number"   minlength="1"   maxlength="20"     tip="Mention your roll number for the CMAT exam. If you do not have the roll number, enter NA"   value=''  />
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
			</li>
			<li id="cmat2" style="display:none;">
				<div class='additionalInfoLeftCol'>
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
				
				<div class='additionalInfoRightCol'>
				<label>CMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatPercentileAdditionalIBA' id='cmatPercentileAdditionalIBA'  validate="validateFloat"    caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($cmatPercentileAdditionalIBA) && $cmatPercentileAdditionalIBA!=""){ ?>
				  <script>
				      document.getElementById("cmatPercentileAdditionalIBA").value = "<?php echo str_replace("\n", '\n', $cmatPercentileAdditionalIBA);  ?>";
				      document.getElementById("cmatPercentileAdditionalIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'cmatPercentileAdditionalIBA_error'></div></div>
				</div>
				</div>
				</li>
							
				<li id="cmat3" style="display:none ; border-bottom:1px dotted #c0c0c0; padding-bottom:15px;">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateFloat"    caption="rank"   minlength="1"   maxlength="5"     tip="Mention your rank in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
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
				
				
				
			
						
				
				
			
			
			
			<?php
			    if(isset($testNamesIBA) && $testNamesIBA!="" && strpos($testNamesIBA,'CMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIBA4'));
			    </script>
			<?php
			    }
			?>
			<li id="gmat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     tip="Mention your Registration number for the exam.If you do not have the roll number, enter NA."  allowNA='true' value=''   />
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
				<label>GMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
			</li>
			<li id="gmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true" />
				<?php if(isset($gmatScoreAdditional) && $gmatScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("gmatScoreAdditional").value = "<?php echo str_replace("\n", '\n', $gmatScoreAdditional );  ?>";
				      document.getElementById("gmatScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gmatScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>GMAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatPercentileAdditional' id='gmatPercentileAdditional'  validate="validateFloat" caption="percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, you can leave this field blank, enter NA."   value=''    allowNA = 'true' />
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
			    if(isset($testNamesIBA) && $testNamesIBA!="" && strpos($testNamesIBA,'GMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesIBA5'));
			    </script>
			<?php
			    }
			?>
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Academic Background</h3>
				<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Medium of Instruction 10th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10mediumIBA' id='class10mediumIBA'   validate="validateStr"   required="true"   caption="class 10th medium of instruction"   minlength="1"   maxlength="50"        tip="Enter your class 10th medium of instruction. Example: For english medium schools, enter English.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($class10mediumIBA) && $class10mediumIBA!=""){ ?>
				  <script>
				      document.getElementById("class10mediumIBA").value = "<?php echo str_replace("\n", '\n', $class10mediumIBA );  ?>";
				      document.getElementById("class10mediumIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10mediumIBA_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Year of Admission 10th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10YOAIBA' id='class10YOAIBA'   validate="validateInteger"   required="true"   caption="class 10th admission year"   minlength="2"   maxlength="4"         tip="Enter the year of admission or the year of commencement of 10th standard.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($class10YOAIBA) && $class10YOAIBA!=""){ ?>
				  <script>
				      document.getElementById("class10YOAIBA").value = "<?php echo str_replace("\n", '\n', $class10YOAIBA );  ?>";
				      document.getElementById("class10YOAIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10YOAIBA_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Division / Class 10th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10DivisionIBA' id='class10DivisionIBA'   validate="validateStr" caption="class 10th division"  minlength="3" maxlength="100" required='true'     tip="Enter the division or class that you earned in class 10th for e.g. 1st division, 2nd division.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($class10DivisionIBA) && $class10DivisionIBA!=""){ ?>
				  <script>
				      document.getElementById("class10DivisionIBA").value = "<?php echo str_replace("\n", '\n', $class10DivisionIBA );  ?>";
				      document.getElementById("class10DivisionIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10DivisionIBA_error'></div></div>
				</div>
				</div>
				<input type='hidden' name='class10SubjectsIBA' id='class10SubjectsIBA' value='NA'   />
				</div>
			</li>

			<li>
				<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Medium of Instruction 12th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12mediumIBA' id='class12mediumIBA'   validate="validateStr"   required="true"   caption="class 12th medium of instruction"   minlength="1"   maxlength="50"           tip="Enter your class 12th medium of instruction. Example: For english medium schools, enter English.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($class12mediumIBA) && $class12mediumIBA!=""){ ?>
				  <script>
				      document.getElementById("class12mediumIBA").value = "<?php echo str_replace("\n", '\n', $class12mediumIBA );  ?>";
				      document.getElementById("class12mediumIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12mediumIBA_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Year of Admission 12th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12YOAIBA' id='class12YOAIBA'  validate="validateInteger"   required="true"   caption="class 12th admission year"   minlength="2"   maxlength="4"        tip="Enter the year of admission or the year of commencement of 12th standard.If it is not available, enter NA."   value='' allowNA='true'  />
				<?php if(isset($class12YOAIBA) && $class12YOAIBA!=""){ ?>
				  <script>
				      document.getElementById("class12YOAIBA").value = "<?php echo str_replace("\n", '\n', $class12YOAIBA );  ?>";
				      document.getElementById("class12YOAIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12YOAIBA_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Division / Class 12th: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12DivisionIBA' id='class12DivisionIBA'    validate="validateStr" caption="class 12th division"  minlength="3" maxlength="100" required='true'    tip="Enter the division or class that you earned in class 12th for e.g. 1st division, 2nd division.If it is not available, enter NA."   value='' allowNA='true'  />
				<?php if(isset($class12DivisionIBA) && $class12DivisionIBA!=""){ ?>
				  <script>
				      document.getElementById("class12DivisionIBA").value = "<?php echo str_replace("\n", '\n', $class12DivisionIBA );  ?>";
				      document.getElementById("class12DivisionIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12DivisionIBA_error'></div></div>
				</div>
				</div>
				<input type='hidden' name='class12SubjectsIBA' id='class12SubjectsIBA' value='NA'   />
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
			<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYearSubjectsIBA' id='gradYearSubjectsIBA'   validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"       tip="Enter your specialization or stream for <?php echo $graduationCourseName;?>. For example if you did BA in Economics, enter Economics.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($gradYearSubjectsIBA) && $gradYearSubjectsIBA!=""){ ?>
				  <script>
				      document.getElementById("gradYearSubjectsIBA").value = "<?php echo str_replace("\n", '\n', $gradYearSubjectsIBA );  ?>";
				      document.getElementById("gradYearSubjectsIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYearSubjectsIBA_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Medium of Instruction <?php echo $graduationCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYearMOIIBA' id='gradYearMOIIBA'    validate="validateStr"   required="true"   caption="medium of instruction"   minlength="1"   maxlength="50"           tip="Enter medium of instruction for <?php echo $graduationCourseName;?> for e.g. English, Hindi.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($gradYearMOIIBA) && $gradYearMOIIBA!=""){ ?>
				  <script>
				      document.getElementById("gradYearMOIIBA").value = "<?php echo str_replace("\n", '\n', $gradYearMOIIBA );  ?>";
				      document.getElementById("gradYearMOIIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYearMOIIBA_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year of Admission <?php echo $graduationCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYearYOAIBA' id='gradYearYOAIBA'  validate="validateInteger"   required="true"   caption="admission year"   minlength="2"   maxlength="4"      tip="Enter the year of admission or the year of commencement of <?php echo $graduationCourseName;?>.If it is not available, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($gradYearYOAIBA) && $gradYearYOAIBA!=""){ ?>
				  <script>
				      document.getElementById("gradYearYOAIBA").value = "<?php echo str_replace("\n", '\n', $gradYearYOAIBA );  ?>";
				      document.getElementById("gradYearYOAIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYearYOAIBA_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Division / Class <?php echo $graduationCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYearDivisionIBA' id='gradYearDivisionIBA'   validate="validateStr" caption="division"  minlength="3" maxlength="100" required='true'  tip="Enter the division or class that you earned in class <?php echo $graduationCourseName;?> for e.g. 1st division, 2nd division. If you haven't received the result, enter NA."   value=''   allowNA='true'/>
				<?php if(isset($gradYearDivisionIBA) && $gradYearDivisionIBA!=""){ ?>
				  <script>
				      document.getElementById("gradYearDivisionIBA").value = "<?php echo str_replace("\n", '\n', $gradYearDivisionIBA );  ?>";
				      document.getElementById("gradYearDivisionIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYearDivisionIBA_error'></div></div>
				</div>
				</div>
			</div>
			</li>
			
			<?php
			$i=0;
			if(count($otherCourses)>0) { 
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
					$pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					$pgCheckVal = $$pgCheck;
					$yoa = 'otherCourseYoa_mul_'.$otherCourseId;
					$yoaVal = $$yoa;
					$subjects = 'otherCourseSubjects_mul_'.$otherCourseId;
					$subjectsVal = $$subjects;
					$division = 'otherCourseDivision_mul_'.$otherCourseId;
					$divisionVal = $$division;
					$moi = 'otherCourseMoi_mul_'.$otherCourseId;
					$moiVal = $$moi;
					$isPGName = 'otherCourseIsPG_mul_'.$otherCourseId;
					$isPGVal = $$isPGName;
					$i++;

			?>
			<li>
				<div class="semesterDetailsBox">
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
				<label><?php echo $otherCourseName;?> Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $subjects;?>' id='<?php echo $subjects;?>'   validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"      tip="Enter your specialization or stream for <?php echo $otherCourseName;?>. For example if you did BA in Economics, enter Economics.If it is not available, enter NA."   value=''   allowNA='yes'/>
				<?php if(isset($subjectsVal) && $subjectsVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $subjects;?>").value = "<?php echo str_replace("\n", '\n', $subjectsVal );  ?>";
				      document.getElementById("<?php echo $subjects;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $subjects;?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Medium of Instruction <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $moi;?>' id='<?php echo $moi;?>'    validate="validateStr"   required="true"   caption="medium of instruction"   minlength="1"   maxlength="50"          tip="Enter medium of instruction for <?php echo $otherCourseName;?> for e.g. English, Hindi.If it is not available, enter NA."   value=''   allowNA='yes'/>
				<?php if(isset($moiVal) && $moiVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $moi;?>").value = "<?php echo str_replace("\n", '\n', $moiVal );  ?>";
				      document.getElementById("<?php echo $moi;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $moi;?>_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year of Admission <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $yoa;?>' id='<?php echo $yoa;?>'  validate="validateInteger"   required="true"   caption="admission year"   minlength="2"   maxlength="4"    tip="Enter the year of admission or the year of commencement of <?php echo $otherCourseName;?>.If it is not available, enter NA."   value=''  allowNA='yes' />
				<?php if(isset($yoaVal) && $yoaVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $yoa;?>").value = "<?php echo str_replace("\n", '\n', $yoaVal );  ?>";
				      document.getElementById("<?php echo $yoa;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $yoa;?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Division / Class <?php echo $otherCourseName;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $division;?>' id='<?php echo $division;?>'    validate="validateStr" caption="division"  minlength="3" maxlength="100" required='true'        tip="Enter the division or class that you earned in class <?php echo $otherCourseName;?> for e.g. 1st division, 2nd division. If you haven't received the result, enter NA."   value=''  allowNA='true' />
				<?php if(isset($divisionVal) && $divisionVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $division;?>").value = "<?php echo str_replace("\n", '\n', $divisionVal );  ?>";
				      document.getElementById("<?php echo $division;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $division;?>_error'></div></div>
				</div>
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
			for($i=0;$i<count($workCompaniesExpFrom);$i++){
				$durationFrom = $workCompaniesExpFrom['_mul_'.$i];
	 		        $durationTo = trim($workCompaniesExpTill['_mul_'.$i])?$workCompaniesExpTill['_mul_'.$i]:'Till date';
				if($durationFrom) {
                                        $startDate = getStandardDate($durationFrom);
                                        $endDate = $durationTo == 'Till date'?date('Y-m-d'):getStandardDate($durationTo);
                                        $totalDuration = getTimeDifference($startDate,$endDate);
                                        $companyWorkExDuration['workExpIBA'.$i] = ($totalDuration['months']<0)?0:$totalDuration['months'];
					$total += $totalDuration['months'];
                                }
			}
		
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$grossSalaryFieldName = 'annualSalaryIBA'.$workCompanyKey;
					$grossSalaryFieldValue = $$grossSalaryFieldName;
					//$workExpFieldName = 'workExpIBA'.$j;
					//$workExpFieldValue = $companyWorkExDuration[$workExpFieldName];
					$workExpInMonthsName = 'workExpInMonths'.$workCompanyKey;
					$workExpInMonthsValue = $$workExpInMonthsName;
					$totalWorkExpFieldName = 'totalWorkExpIBA'.$workCompanyKey;
					$totalWorkExpFieldValue = $$totalWorkExpFieldName;
					$j++;
					//$totalWorkExp += $workExpFieldValue;

			?>
			<?php if($j==1){ ?>
			<li>
				<h3 class="upperCase">Work Experience</h3>
				<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Total work experience (in months): </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $totalWorkExpFieldName;?>' id='<?php echo $totalWorkExpFieldName;?>'  validate="validateFloat" minlength="1" maxlength="10" caption="total experience in months"    tip="Please enter the total work experience in months till date.If it is not available, enter NA."   value=''   required="true"  allowNA = 'true'/>
				<?php if(isset($totalWorkExpFieldValue) && $totalWorkExpFieldValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $totalWorkExpFieldName;?>").value = "<?php echo str_replace("\n", '\n', $totalWorkExpFieldValue );  ?>";
				      document.getElementById("<?php echo $totalWorkExpFieldName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $totalWorkExpFieldName;?>_error'></div></div>
				</div>
				</div>
				</div>
			</li>
			<?php } ?>
			<li>
				<div class='semesterDetailsBox'>
				<div class='additionalInfoLeftCol'>
				<label>Work Experience (in months) at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $workExpInMonthsName;?>' id='<?php echo $workExpInMonthsName;?>'  validate="validateFloat" minlength="1" maxlength="10" caption="experience in months"    tip="Please enter the work experience (in months) at <?php echo $workCompany; ?>.If it is not available, enter NA."   value=''   required="true" allowNA='true'/>
				<?php if(isset($workExpInMonthsValue) && $workExpInMonthsValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $workExpInMonthsName;?>").value = "<?php echo str_replace("\n", '\n', $workExpInMonthsValue );  ?>";
				      document.getElementById("<?php echo $workExpInMonthsName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $workExpInMonthsName;?>_error'></div></div>
				</div>
				</div>
				<div class="spacer15 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>Annual Salary (in lakhs) at <?php echo $workCompany; ?>:</label>
				<div class='fieldBoxLarge' >
				<input class="textboxLarge" type='text' name='<?php echo $grossSalaryFieldName; ?>' id='<?php echo $grossSalaryFieldName; ?>'  validate="validateFloat" minlength="1" maxlength="10" caption="salary"    tip="Please enter the Annual salary (in lakhs) at <?php echo $workCompany; ?>.If it is not available, enter NA."   value=''   required="true" allowNA='true'/>&nbsp;
				<?php if(isset($grossSalaryFieldValue) && $grossSalaryFieldValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $grossSalaryFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $grossSalaryFieldValue );  ?>";
				      document.getElementById("<?php echo $grossSalaryFieldName; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $grossSalaryFieldName; ?>_error'></div></div>
				</div>
				</div>
				</div>
			</li>
			<?php
				}
			}
			?>
			<input type="hidden" name="hasWorkExperienceIBA" id="hasWorkExperienceIBA" value="<?php if(count($workCompanies)>0){ echo 'YES';}else{ echo 'NO';}?>" />
			<li style="width:100%">
				<h3 class="upperCase">Statement Of Purpose</h3>
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Please write a short essay on '<strong>What is your Career Vision and how PGDM/MBA Programme is meaningful for you? What do you Expect to Gain by Studying at IBA?</strong>' This essay (between 200 to 250 words) will be considered as an input in the Selection Process: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='shortEssayIBA' id='shortEssayIBA'   style="width:618px; height:74px; padding:5px"    tip="Please write a short essay on 'What is your Career Vision and how PGDM/MBA Programme is meaningful for you? What do you Expect to Gain by Studying at IBA?'."  validate="validateStr" required="true" caption="short essay" minlength="10" maxlength="1250" ></textarea>
				<?php if(isset($shortEssayIBA) && $shortEssayIBA!=""){ ?>
				  <script>
				      document.getElementById("shortEssayIBA").value = "<?php echo str_replace("\n", '\n', $shortEssayIBA );  ?>";
				      document.getElementById("shortEssayIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'shortEssayIBA_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%">    
			<h3 class="upperCase">Family Background</h3>
				<label style="font-weight:normal;150px;">Family Annual Income: </label>
				<div class='additionalInfoLeftCol' style="width:600px;">
				<div class='fieldBoxLarge'  style="width:590px;">
				<input type='radio' name='faiIBA' id='faiIBA1'   value='Less than 3 lakhs'    onmouseover="showTipOnline('Please select you family\'s annual income',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select you family\'s annual income',this);" onmouseout="hidetip();" >Less than 3,00,000 p.a.</span>&nbsp;&nbsp;
				<input type='radio' name='faiIBA' id='faiIBA2'   value='3-5 lakhs'     onmouseover="showTipOnline('Please select you family\'s annual income',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select you family\'s annual income',this);" onmouseout="hidetip();" >3,00,000 to 5,00,000 p.a.</span>&nbsp;&nbsp;
				<input type='radio' name='faiIBA' id='faiIBA3'   value='More than 5 lakhs'     onmouseover="showTipOnline('Please select you family\'s annual income',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select you family\'s annual income',this);" onmouseout="hidetip();" >More than 5,00,000 p.a.</span>&nbsp;&nbsp;
				<?php if(isset($faiIBA) && $faiIBA!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["faiIBA"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $faiIBA?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'faiIBA_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Contact Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherNumIBA' id='fatherNumIBA' validate="validateInteger"   required="true"   caption="father's contact number"   minlength="8"   maxlength="15"    tip="Please enter your father's contact number. If the number is not available, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($fatherNumIBA) && $fatherNumIBA!=""){ ?>
				  <script>
				      document.getElementById("fatherNumIBA").value = "<?php echo str_replace("\n", '\n', $fatherNumIBA);  ?>";
				      document.getElementById("fatherNumIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherNumIBA_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Mother's Contact Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherNumIBA' id='motherNumIBA'  validate="validateInteger"   required="true"   caption=" mother's contact number"   minlength="8"   maxlength="15"     tip="Please enter your mother's contact number. If the number is not available, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($motherNumIBA) && $motherNumIBA!=""){ ?>
				  <script>
				      document.getElementById("motherNumIBA").value = "<?php echo str_replace("\n", '\n', $motherNumIBA);  ?>";
				      document.getElementById("motherNumIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherNumIBA_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Father's Organization Name (if applicable): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='fatherHusOrgNameIBA' id='fatherHusOrgNameIBA'  validate="validateStr"   required="true"   caption=" Father's Organization Name"   minlength="2"   maxlength="50"     tip="Please enter your father's organization name. If it is not available, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($fatherHusOrgNameIBA) && $fatherHusOrgNameIBA!=""){ ?>
				  <script>
				      document.getElementById("fatherHusOrgNameIBA").value = "<?php echo str_replace("\n", '\n', $fatherHusOrgNameIBA);  ?>";
				      document.getElementById("fatherHusOrgNameIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'fatherHusOrgNameIBA_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>Alternate Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='alterNumIBA' id='alterNumIBA'  validate="validateInteger" required="false" caption=" Alternate number"   minlength="8"   maxlength="15" tip="Please enter your alternate contact number. If the number is not available, enter NA."   value=''  allowNA = 'true' />
				<?php if(isset($alterNumIBA) && $alterNumIBA!=""){ ?>
				  <script>
				      document.getElementById("alterNumIBA").value = "<?php echo str_replace("\n", '\n', $alterNumIBA);  ?>";
				      document.getElementById("alterNumIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'alterNumIBA_error'></div></div>
				</div>
				</div>
				
				
			</li>
						<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother's Organization Name (if applicable): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherWifeOrgNameIBA' id='motherWifeOrgNameIBA'  validate="validateStr"   required="true"   caption="  Mother's Organization Name"   minlength="2"   maxlength="50"     tip="Please enter your mother's organization name. If it is not available, enter NA."   value=''    allowNA = 'true' />
				<?php if(isset($motherWifeOrgNameIBA) && $motherWifeOrgNameIBA!=""){ ?>
				  <script>
				      document.getElementById("motherWifeOrgNameIBA").value = "<?php echo str_replace("\n", '\n', $motherWifeOrgNameIBA);  ?>";
				      document.getElementById("motherWifeOrgNameIBA").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherWifeOrgNameIBA_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<h3 class="upperCase">Preferred GD & PI Centres<sup>**</sup></h3>
				
				<div class='additionalInfoLeftCol'>
				<label>1st Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(1);" name='preferredGDPILocation' id='preferredGDPILocation' style="width:120px;"    onmouseover="showTipOnline('Select your 1st preference of campus from the list.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
				<select blurMethod="checkValidCampusPreference(2);" name='pref2IBA' id='pref2IBA'  style="width:120px;"   tip="Select your 2nd preference of campus from the list."       onmouseover="showTipOnline('Select your 2nd preference of campus from the list.',this);" onmouseout='hidetip();' validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location" >
				  <option value='' selected>Select</option><option value='Ahmedabad' >Ahmedabad</option><option value='Bangalore' >Bangalore</option><option value='Bhopal' >Bhopal</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Chandigarh' >Chandigarh</option><option value='Chennai' >Chennai</option><option value='Coimbatore' >Coimbatore</option><option value='Delhi' >Delhi</option><option value='Dehradun' >Dehradun</option><option value='Ernakulum' >Ernakulum</option><option value='Guwahati' >Guwahati</option><option value='Hyderabad' >Hyderabad</option><option value='Indore' >Indore</option><option value='Jaipur' >Jaipur</option><option value='Jamshedpur' >Jamshedpur</option><option value='Kanpur' >Kanpur</option><option value='Kolkata' >Kolkata</option><option value='Lucknow' >Lucknow</option><option value='Mumbai' >Mumbai</option><option value='Nagpur' >Nagpur</option><option value='Patna' >Patna</option><option value='Pune' >Pune</option><option value='Ranchi' >Ranchi</option><option value='Raipur' >Raipur</option><option value='Varanasi' >Varanasi</option>
				</select>
				
				<?php if(isset($pref2IBA) && $pref2IBA!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2IBA"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2IBA;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2IBA_error'></div></div>
				</div>
				<br>
				<p style="float:right ; color:#666666; margin-top:15px;"><I>**subject to change</I></p>
				</div>
			</li>
			<li>
                                <h3 class="upperCase">Source of Information on IBA</h3>
				<div class='additionalInfoLeftCol' style="width:922px">
                                <label>Source of Information on IBA: </label>
                                <div class='fieldBoxLarge' style="width:605px">
                                <input type='checkbox' name='cameToKnowAboutIBA[]' id='cameToKnowAboutIBA0'   value='Shiksha' ></input><span>Shiksha.com</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
                                <input type='checkbox' name='cameToKnowAboutIBA[]' id='cameToKnowAboutIBA2'   value='Friend'    ></input><span >Friend</span>&nbsp;&nbsp;
			        <input type='checkbox' name='cameToKnowAboutIBA[]' id='cameToKnowAboutIBA4'   value='Mailer'    ></input><span >Mailer</span>&nbsp;&nbsp;
				<div class="spacer10 clearFix"></div>
				<div style="float: left;width:150px">
					<input type='checkbox' onClick="toggleSourceTextBox(this,'newspaper');" name='cameToKnowAboutIBA[]' id='cameToKnowAboutIBA1'   value='Newspaper' ></input><span>Newspaper Advt.</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
				<div class='fieldBoxLarge'>
					<input type='text' name='newspaperSourceIBA' id='newspaperSourceIBA'  validate="validateStr"   caption="Newpaper Source"   minlength="2"   maxlength="250"     tip="Please specify Newpaper Source."   value='' disabled  />
					<?php if(isset($newspaperSourceIBA) && $newspaperSourceIBA!=""){ ?>
					  <script>
					      document.getElementById("newspaperSourceIBA").disabled = false;
					      document.getElementById("newspaperSourceIBA").value = "<?php echo str_replace("\n", '\n', $newspaperSourceIBA );  ?>";
					      document.getElementById("newspaperSourceIBA").style.color = "";
					      document.getElementById("cameToKnowAboutIBA1").checked = true;
					  </script>
					<?php } ?>
	
					<div style='display:none'><div class='errorMsg' id= 'newspaperSourceIBA_error'></div></div>
				</div>
				<div class="spacer10 clearFix"></div>
				<div style="float: left;width:150px">
					<input type='checkbox' onClick="toggleSourceTextBox(this,'internet');" name='cameToKnowAboutIBA[]' id='cameToKnowAboutIBA5'   value='Internet'    ></input><span >Internet Portal</span>&nbsp;&nbsp;
				</div>
								<div class='fieldBoxLarge'>
                                <input type='text' name='internetSourceIBA' id='internetSourceIBA'  validate="validateStr"   caption="Internet Portal Source"   minlength="2"   maxlength="250"     tip="Please specify Internet Portal Source."   value='' disabled  />
                                <?php if(isset($internetSourceIBA) && $internetSourceIBA!=""){ ?>
                                  <script>
                                      document.getElementById("internetSourceIBA").disabled = false;
                                      document.getElementById("internetSourceIBA").value = "<?php echo str_replace("\n", '\n', $internetSourceIBA );  ?>";
                                      document.getElementById("internetSourceIBA").style.color = "";
                                      document.getElementById("cameToKnowAboutIBA5").checked = true;
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'internetSourceIBA_error'></div></div>
                                </div>
				<div class="spacer10 clearFix"></div>
				<div style="float: left;width:150px">				
					<input type='checkbox' onClick="toggleSourceTextBox(this,'alumni');" name='cameToKnowAboutIBA[]' id='cameToKnowAboutIBA3'   value='Alumni'    ></input><span >Alumni/Student</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				</div>
								<div class='fieldBoxLarge'>
                                <input type='text' name='alumniSourceIBA' id='alumniSourceIBA'  validate="validateStr"   caption="Alumni Source"   minlength="2"   maxlength="250"     tip="Please specify Alumni Source."   value='' disabled  />
                                <?php if(isset($alumniSourceIBA) && $alumniSourceIBA!=""){ ?>
                                  <script>
                                      document.getElementById("alumniSourceIBA").disabled = false;
                                      document.getElementById("alumniSourceIBA").value = "<?php echo str_replace("\n", '\n', $alumniSourceIBA );  ?>";
                                      document.getElementById("alumniSourceIBA").style.color = "";
                                      document.getElementById("cameToKnowAboutIBA3").checked = true;
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'alumniSourceIBA_error'></div></div>
                                </div>
				
		      <?php if(isset($cameToKnowAboutIBA) && $cameToKnowAboutIBA!=""){ ?>
                                <script>
                                    objCheckBoxes = document.forms["OnlineForm"].elements["cameToKnowAboutIBA[]"];
                                    var countCheckBoxes = objCheckBoxes.length;
                                    for(var i = 0; i < countCheckBoxes; i++){
                                              objCheckBoxes[i].checked = false;

                                              <?php $arr = explode(",",$cameToKnowAboutIBA);
                                                    for($x=0;$x<count($arr);$x++){ ?>
                                                          if(objCheckBoxes[i].value == "<?php echo $arr[$x];?>") {
                                                                objCheckBoxes[i].checked = true;
								if(objCheckBoxes[i].value=='Newspaper'){
									toggleSourceTextBox($('cameToKnowAboutIBA1'),'newspaper');	
								}
								if(objCheckBoxes[i].value=='Newspaper'){
									toggleSourceTextBox($('cameToKnowAboutIBA5'),'internet');	
								}
								if(objCheckBoxes[i].value=='Alumni'){
									toggleSourceTextBox($('cameToKnowAboutIBA3'),'alumni');	
								}
                                                          }
                                              <?php
                                                    }
                                              ?>
                                    }
                                </script>
                              <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'cameToKnowAboutXIME_error'></div></div>
                                </div>
                                </div>
                        </li>
			<li>
				<div class="additionalInfoLeftCol" style="width:950px">
						<label style="font-weight:normal; padding-top:0">Terms:</label>
						<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
						I hereby declare that I have provided correct, complete and accurate information in this application form. I have carefully read the Information and Guidelines to Applicants. I understand and agree that any misrepresentation, false information or omission of facts in my application will lead to the denial of admission, cancellation of admission or expulsion from the PGDM/MBA Programme offered at IBA at any stage.
						<div class="spacer10 clearFix"></div>
						<div>
						<input type='checkbox' name='agreeToTermsIBA' id='agreeToTermsIBA' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above
		
					      <?php if(isset($agreeToTermsIBA) && $agreeToTermsIBA!=""){ ?>
						<script>
						    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsIBA"];
						    var countCheckBoxes = 1;
						    for(var i = 0; i < countCheckBoxes; i++){ 
							      objCheckBoxes.checked = false;
							      <?php $arr = explode(",",$agreeToTermsIBA);
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
						<div style='display:none'><div class='errorMsg' id= 'agreeToTermsIBA_error'></div></div>
		
		
						</div>
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

  </script>
