<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	if(obj.value == "MAT" || obj.value == "CAT" || obj.value == "XAT"){ 
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional",key+"PercentileAdditional");
	    
	}
	else if(obj.value == "GRE"){
	    var key = obj.value.toLowerCase();
	    var objects1 = new Array(key+'RegnNoAmrita',key+'DateAmrita',key+'ScoreAmrita');
	}else if(obj.value == "GMAT"){
	    var objects1 = new Array(key+"DateOfExaminationAdditional",key+"RollNumberAdditional",key+"ScoreAdditional");
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

</script>


<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>


			<li style="width:100%">
				<h3 class="upperCase">Qualifying Examination</h3>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesAmrita[]' id='testNamesAmrita0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesAmrita[]' id='testNamesAmrita1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesAmrita[]' id='testNamesAmrita2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesAmrita[]' id='testNamesAmrita3'   value='GRE'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GRE</span>&nbsp;&nbsp;
				<input onClick="checkTestScore(this);" type='checkbox' name='testNamesAmrita[]' id='testNamesAmrita4'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the registration number, test date and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<?php if(isset($testNamesAmrita) && $testNamesAmrita!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesAmrita[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesAmrita);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesAmrita_error'></div></div>
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
			    if(isset($testNamesAmrita) && $testNamesAmrita!="" && strpos($testNamesAmrita,'CAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesAmrita0'));
			    </script>
			<?php
			    }
			?>

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
			    if(isset($testNamesAmrita) && $testNamesAmrita!=""){ 
				    $tests = explode(',',$testNamesAmrita);
				    foreach ($tests as $test){
					  if($test=='MAT'){
			    ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesAmrita1'));
			    </script>
			<?php }
			      }
			    }
			?>

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
			    if(isset($testNamesAmrita) && $testNamesAmrita!="" && strpos($testNamesAmrita,'XAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesAmrita2'));
			    </script>
			<?php
			    }
			?>

			<li id="gre1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GRE REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='greRegnNoAmrita' id='greRegnNoAmrita'  tip="Mention your Registration number for the exam."   validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"       value=''   />
				<?php if(isset($greRegnNoAmrita) && $greRegnNoAmrita!=""){ ?>
				  <script>
				      document.getElementById("greRegnNoAmrita").value = "<?php echo str_replace("\n", '\n', $greRegnNoAmrita );  ?>";
				      document.getElementById("greRegnNoAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'greRegnNoAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>GRE Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='greDateAmrita' id='greDateAmrita' readonly maxlength='10'     validate="validateDateForms"  caption="date"    tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"      onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('greDateAmrita'),'greDateAmrita_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='greDateAmrita_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('greDateAmrita'),'greDateAmrita_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($greDateAmrita) && $greDateAmrita!=""){ ?>
				  <script>
				      document.getElementById("greDateAmrita").value = "<?php echo str_replace("\n", '\n', $greDateAmrita );  ?>";
				      document.getElementById("greDateAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'greDateAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li id="gre2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>GRE Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='greScoreAmrita' id='greScoreAmrita'     validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"      value='' tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."  allowNA="true"   />
				<?php if(isset($greScoreAmrita) && $greScoreAmrita!=""){ ?>
				  <script>
				      document.getElementById("greScoreAmrita").value = "<?php echo str_replace("\n", '\n', $greScoreAmrita );  ?>";
				      document.getElementById("greScoreAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'greScoreAmrita_error'></div></div>
				</div>
				</div>
	    		</li>
			<?php
			    if(isset($testNamesAmrita) && $testNamesAmrita!="" && strpos($testNamesAmrita,'GRE')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesAmrita3'));
			    </script>
			<?php
			    }
			?>

			<li id="gmat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     tip="Mention your Registration number for the exam." value=''   />
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
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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

			</li>

			<?php
			    if(isset($testNamesAmrita) && $testNamesAmrita!="" && strpos($testNamesAmrita,'GMAT')!==false){ ?>
			    <script>
				    checkTestScore(document.getElementById('testNamesAmrita4'));
			    </script>
			<?php
			    }
			?>



			<?php if($action != 'updateScore'):?>
	
			<li style="width:100%;">
				<h3 class="upperCase">Additional personal details</h3>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Community / Caste: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input type='radio' name='categoryAmrita' id='categoryAmrita0'   value='OC'   onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >OC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryAmrita' id='categoryAmrita1'   value='BC'     onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >BC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryAmrita' id='categoryAmrita2'   value='OBC'     onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >OBC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryAmrita' id='categoryAmrita3'   value='MBC'     onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >MBC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryAmrita' id='categoryAmrita4'   value='SC'     onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >SC</span>&nbsp;&nbsp;
				<input type='radio' name='categoryAmrita' id='categoryAmrita5'   value='ST'     onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >ST</span>&nbsp;&nbsp;
                                <input type='radio' name='categoryAmrita' id='categoryAmrita6'   value='GENERAL' checked    onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select your community/caste from the options.',this);" onmouseout="hidetip();" >General</span>&nbsp;&nbsp;
				<?php if(isset($categoryAmrita) && $categoryAmrita!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["categoryAmrita"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $categoryAmrita;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mother tongue: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='motherTongueAmrita' id='motherTongueAmrita'  validate="validateStr"   required="true"   caption="mother tongue"   minlength="2"   maxlength="50"     tip="Enter your mother tongue i.e. the language that you have grown up speaking."   value=''   />
				<?php if(isset($motherTongueAmrita) && $motherTongueAmrita!=""){ ?>
				  <script>
				      document.getElementById("motherTongueAmrita").value = "<?php echo str_replace("\n", '\n', $motherTongueAmrita );  ?>";
				      document.getElementById("motherTongueAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherTongueAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Blood group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='bloodGroupAmrita' id='bloodGroupAmrita'  validate="validateStr"   required="true"   caption="blood group"   minlength="2"   maxlength="4"     tip="Enter your blood group. If you are not sure about your blood group, just enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($bloodGroupAmrita) && $bloodGroupAmrita!=""){ ?>
				  <script>
				      document.getElementById("bloodGroupAmrita").value = "<?php echo str_replace("\n", '\n', $bloodGroupAmrita );  ?>";
				      document.getElementById("bloodGroupAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'bloodGroupAmrita_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol' style="width:650px">
				<label>Age: </label>
				<div class='fieldBoxLarge' style="width:335px">
				<div style="width: 75px; float: left;">
				<input class="textboxLarge" type='text' name='ageYearAmrita' id='ageYearAmrita'  validate="validateInteger"   required="true"   caption="year"   minlength="2"   maxlength="2"     tip="Enter your Age in year and months"   value=''   style="width: 70px;"/>
				<span style="color:#cacaca; font-size: 12px; text-align: center; display: block">Year</span>
				</div>
				<div style="width: 75px; float: left; margin-left: 5px">
				<input class="textboxLarge" type='text' name='ageMonthAmrita' id='ageMonthAmrita'  validate="validateMonth"   required="true"   caption="month"   minlength="1"   maxlength="2"     tip="Enter your Age in year and months"   value=''   style="width: 70px;"/>
				<span style="color:#cacaca; font-size: 12px; text-align: center; display: block">Month</span>
				</div>
				<p style="color:#cacaca;">&nbsp;(Ex: 21 Year and 6 Months)</p>
				<div class='clearFix'></div>
				<?php if(isset($ageYearAmrita) && $ageYearAmrita!=""){ ?>
				  <script>
				      document.getElementById("ageYearAmrita").value = "<?php echo str_replace("\n", '\n', $ageYearAmrita );  ?>";
				      document.getElementById("ageYearAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<?php if(isset($ageMonthAmrita) && $ageMonthAmrita!=""){ ?>
				  <script>
				      document.getElementById("ageMonthAmrita").value = "<?php echo str_replace("\n", '\n', $ageMonthAmrita );  ?>";
				      document.getElementById("ageMonthAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageYearAmrita_error'></div></div>
				<div style='display:none'><div class='errorMsg' id= 'ageMonthAmrita_error'></div></div>
				</div>
				</div>

				
			</li>
			                       <li>
                                <h3 class="upperCase">Parent details</h3>
                                <div class='additionalInfoLeftCol'>
                                <label>Father's Age: </label>
                                <div class='fieldBoxLarge'>
                                <input class="textboxLarge" type='text' name='fatherAgeAmrita' id='fatherAgeAmrita'  validate="validateInteger"   required="true"   caption="father's age"   minlength="2"   maxlength="3"     tip="Enter your father's age."   value=''   />
                                <?php if(isset($fatherAgeAmrita) && $fatherAgeAmrita!=""){ ?>
                                  <script>
                                      document.getElementById("fatherAgeAmrita").value = "<?php echo str_replace("\n", '\n', $fatherAgeAmrita );  ?>";
                                      document.getElementById("fatherAgeAmrita").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'fatherAgeAmrita_error'></div></div>
                                </div>
                                </div>

                                <div class='additionalInfoRightCol'>
                                <label>Mother's Age: </label>
                                <div class='fieldBoxLarge'>
                                <input type='text' name='motherAgeAmrita' id='motherAgeAmrita'  validate="validateInteger"   required="true"   caption="mother's age"   minlength="2"   maxlength="4"     tip="Enter your mother's age."   value='' />
                                <?php if(isset($motherAgeAmrita) && $motherAgeAmrita!=""){ ?>
                                  <script>
                                      document.getElementById("motherAgeAmrita").value = "<?php echo str_replace("\n", '\n', $motherAgeAmrita );  ?>";
                                      document.getElementById("motherAgeAmrita").style.color = "";
                                  </script>
                                <?php } ?>

                                <div style='display:none'><div class='errorMsg' id= 'motherAgeAmrita_error'></div></div>
                                </div>
                                </div>
                        </li>

			<li>
				<h3 class="upperCase">PREFERENCE OF CAMPUSES</h3>
				<div class='additionalInfoLeftCol'>
				<label>1st Preference: </label>
				<div class='fieldBoxLarge'>
				<select onchange="$('extra1Details').style.display = '';" blurMethod="checkValidCampusPreference(1);" name='pref1Amrita' id='pref1Amrita'  style="width:120px;"  tip="Select your 1st preference of campus from the list."       onmouseover="showTipOnline('Select your 1st preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Amritapuri' >Amritapuri</option><option value='Bengaluru' >Bengaluru</option><option value='Coimbatore' >Coimbatore</option><option value='Kochi' >Kochi</option>
				</select>
				<?php if(isset($pref1Amrita) && $pref1Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref1Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref1Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref1Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>2nd Preference: </label>
				<div class='fieldBoxLarge'>
				<select onchange="$('extra1Details').style.display = '';" blurMethod="checkValidCampusPreference(2);" name='pref2Amrita' id='pref2Amrita'  style="width:120px;"   tip="Select your 2nd preference of campus from the list."       onmouseover="showTipOnline('Select your 2nd preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Amritapuri' >Amritapuri</option><option value='Bengaluru' >Bengaluru</option><option value='Coimbatore' >Coimbatore</option><option value='Kochi' >Kochi</option>
				</select>
				<?php if(isset($pref2Amrita) && $pref2Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2Amrita_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>3rd Preference: </label>
				<div class='fieldBoxLarge'>
				<select onchange="$('extra1Details').style.display = '';" blurMethod="checkValidCampusPreference(3);" name='pref3Amrita' id='pref3Amrita'   style="width:120px;"  tip="Select your 3rd preference of campus from the list."       onmouseover="showTipOnline('Select your 3rd preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Amritapuri' >Amritapuri</option><option value='Bengaluru' >Bengaluru</option><option value='Coimbatore' >Coimbatore</option><option value='Kochi' >Kochi</option>
				</select>
				<?php if(isset($pref3Amrita) && $pref3Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref3Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref3Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref3Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>4th Preference: </label>
				<div class='fieldBoxLarge'>
				<select onchange="$('extra1Details').style.display = '';" blurMethod="checkValidCampusPreference(4);" name='pref4Amrita' id='pref4Amrita'  style="width:120px;"   tip="Select your 4th preference of campus from the list."       onmouseover="showTipOnline('Select your 4th preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Amritapuri' >Amritapuri</option><option value='Bengaluru' >Bengaluru</option><option value='Coimbatore' >Coimbatore</option><option value='Kochi' >Kochi</option>
				</select>
				<?php if(isset($pref4Amrita) && $pref4Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref4Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref4Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref4Amrita_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Residential Status Preferred: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='residentAmrita' id='residentAmrita1'   value='Residential'    onmouseover="showTipOnline('Select you residential preference. If you need  hostel, select Residential',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select you residential preference. If you need  hostel, select Residential',this);" onmouseout="hidetip();" >Residential</span>&nbsp;&nbsp;
				<input type='radio' name='residentAmrita' id='residentAmrita2'   value='Day scholar'     onmouseover="showTipOnline('Select you residential preference. If you need  hostel, select Residential',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Select you residential preference. If you need  hostel, select Residential',this);" onmouseout="hidetip();" >Day scholar</span>&nbsp;&nbsp;
				<?php if(isset($residentAmrita) && $residentAmrita!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["residentAmrita"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $residentAmrita;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'residentAmrita_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif; ?>
			
		</ul>

	        <?php if($action != 'updateScore'):?>		
		<ul id="extra1Details" style="display:none;">
			<li>
				<h3 class="upperCase">Additional educational details</h3>
				<div class="semesterDetailsBox">
                <strong>Class 10th details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='class10AttemptsAmrita' id='class10AttemptsAmrita'  style="width: 120px;"  tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option>
				</select>
				<?php if(isset($class10AttemptsAmrita) && $class10AttemptsAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class10AttemptsAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class10AttemptsAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10AttemptsAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 10th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='class10SubjectsAmrita' id='class10SubjectsAmrita'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Enter the subjects that you studied in this course. The name of the subjects should be seperated by a comma."   value=''  csv="true" />
				<?php if(isset($class10SubjectsAmrita) && $class10SubjectsAmrita!=""){ ?>
				  <script>
				      document.getElementById("class10SubjectsAmrita").value = "<?php echo str_replace("\n", '\n', $class10SubjectsAmrita );  ?>";
				      document.getElementById("class10SubjectsAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10SubjectsAmrita_error'></div></div>
				</div>
				</div>
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Class 10th Division: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='class10DivisionAmrita' id='class10DivisionAmrita'   style="width: 120px;"    tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable.',this);" onmouseout="hidetip();" >		
				    <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option>
				</select>
				<?php if(isset($class10DivisionAmrita) && $class10DivisionAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class10DivisionAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class10DivisionAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10DivisionAmrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<strong>Class 12th details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='class12AttemptsAmrita' id='class12AttemptsAmrita'    style="width: 120px;"  tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option>
				</select>
				<?php if(isset($class12AttemptsAmrita) && $class12AttemptsAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class12AttemptsAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class12AttemptsAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12AttemptsAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Class 12th Subjects: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='class12SubjectsAmrita' id='class12SubjectsAmrita'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Enter the subjects that you studied in this course. The name of the subjects should be seperated by a comma."   value=''   />
				<?php if(isset($class12SubjectsAmrita) && $class12SubjectsAmrita!=""){ ?>
				  <script>
				      document.getElementById("class12SubjectsAmrita").value = "<?php echo str_replace("\n", '\n', $class12SubjectsAmrita );  ?>";
				      document.getElementById("class12SubjectsAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SubjectsAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Class 12th Division: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='class12DivisionAmrita' id='class12DivisionAmrita'  style="width:120px;"  tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option>
				</select>
				<?php if(isset($class12DivisionAmrita) && $class12DivisionAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("class12DivisionAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $class12DivisionAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12DivisionAmrita_error'></div></div>
				</div>
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
            	<div class="semesterDetailsBox">
				<strong><?php echo $graduationCourseName;?> First year details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear1PassingAmrita' id='gradYear1PassingAmrita'  validate="validateInteger"   required="true"   caption="year"   minlength="4"   maxlength="4"     tip="Enter the year of passing for UG Degree I year"   value=''   />
				<?php if(isset($gradYear1PassingAmrita) && $gradYear1PassingAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear1PassingAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear1PassingAmrita );  ?>";
				      document.getElementById("gradYear1PassingAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1PassingAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='gradYear1AttemptsAmrita' id='gradYear1AttemptsAmrita'  style="width:120px;"  tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option></select>
				<?php if(isset($gradYear1AttemptsAmrita) && $gradYear1AttemptsAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear1AttemptsAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear1AttemptsAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1AttemptsAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Subjects/Specialization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear1SubjectsAmrita' id='gradYear1SubjectsAmrita'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Enter the subjects or specialization for this course. For example Specialization for BA Economics will be <b>Economics</b> or <b>Mechanical Engineering</b> in case of engineering."   value=''  csv="true" />
				<?php if(isset($gradYear1SubjectsAmrita) && $gradYear1SubjectsAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear1SubjectsAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear1SubjectsAmrita );  ?>";
				      document.getElementById("gradYear1SubjectsAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1SubjectsAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label> Percentage/G.P.A.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear1PercentageAmrita' id='gradYear1PercentageAmrita'  validate="validateFloat"   required="true"   caption="percentage"   minlength="2"   maxlength="7"     tip="Enter the Percentage/G.P.A. for UG Degree I year"   value=''   />
				<?php if(isset($gradYear1PercentageAmrita) && $gradYear1PercentageAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear1PercentageAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear1PercentageAmrita );  ?>";
				      document.getElementById("gradYear1PercentageAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1PercentageAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Division: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='gradYear1DivisionAmrita' id='gradYear1DivisionAmrita'    tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option></select>
				<?php if(isset($gradYear1DivisionAmrita) && $gradYear1DivisionAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear1DivisionAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear1DivisionAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear1DivisionAmrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<strong><?php echo $graduationCourseName;?> Second year details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear2PassingAmrita' id='gradYear2PassingAmrita'  validate="validateInteger"   required="true"   caption="year"   minlength="4"   maxlength="4"     tip="Enter the year of passing for UG Degree II year"   value=''   />
				<?php if(isset($gradYear2PassingAmrita) && $gradYear2PassingAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear2PassingAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear2PassingAmrita );  ?>";
				      document.getElementById("gradYear2PassingAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2PassingAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='gradYear2AttemptsAmrita' id='gradYear2AttemptsAmrita'  style="width:120px;"  tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option></select>
				<?php if(isset($gradYear2AttemptsAmrita) && $gradYear2AttemptsAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear2AttemptsAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear2AttemptsAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2AttemptsAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Subjects/Specialization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear2SubjectsAmrita' id='gradYear2SubjectsAmrita'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Enter the subjects or specialization for this course. For example Specialization for BA Economics will be <b>Economics</b> or <b>Mechanical Engineering</b> in case of engineering."   value=''  csv="true" />
				<?php if(isset($gradYear2SubjectsAmrita) && $gradYear2SubjectsAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear2SubjectsAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear2SubjectsAmrita );  ?>";
				      document.getElementById("gradYear2SubjectsAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2SubjectsAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label> Percentage/G.P.A.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear2PercentageAmrita' id='gradYear2PercentageAmrita'  validate="validateFloat"   required="true"   caption="percentage"   minlength="2"   maxlength="7"     tip="Enter the Percentage/G.P.A. for UG Degree II year"   value=''   />
				<?php if(isset($gradYear2PercentageAmrita) && $gradYear2PercentageAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear2PercentageAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear2PercentageAmrita );  ?>";
				      document.getElementById("gradYear2PercentageAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2PercentageAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Division: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='gradYear2DivisionAmrita' id='gradYear2DivisionAmrita'  style="width:120px"  tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option></select>
				<?php if(isset($gradYear2DivisionAmrita) && $gradYear2DivisionAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear2DivisionAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear2DivisionAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear2DivisionAmrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<strong><?php echo $graduationCourseName;?> Third year details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear3PassingAmrita' id='gradYear3PassingAmrita'  validate="validateInteger"   required="true"   caption="year"   minlength="4"   maxlength="4"     tip="Enter the year of passing for UG Degree III year"   value=''   />
				<?php if(isset($gradYear3PassingAmrita) && $gradYear3PassingAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear3PassingAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear3PassingAmrita );  ?>";
				      document.getElementById("gradYear3PassingAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3PassingAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='gradYear3AttemptsAmrita' id='gradYear3AttemptsAmrita'   style='width:120px' tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option></select>
				<?php if(isset($gradYear3AttemptsAmrita) && $gradYear3AttemptsAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear3AttemptsAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear3AttemptsAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3AttemptsAmrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Subjects/Specialization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear3SubjectsAmrita' id='gradYear3SubjectsAmrita'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150" tip="Enter the subjects or specialization for this course. For example Specialization for BA Economics will be <b>Economics</b> or <b>Mechanical Engineering</b> in case of engineering."   value=''  csv="true" />
				<?php if(isset($gradYear3SubjectsAmrita) && $gradYear3SubjectsAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear3SubjectsAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear3SubjectsAmrita );  ?>";
				      document.getElementById("gradYear3SubjectsAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3SubjectsAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label> Percentage/G.P.A.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear3PercentageAmrita' id='gradYear3PercentageAmrita'  validate="validateFloat"   required="true"   caption="percentage"   minlength="2"   maxlength="7"     tip="Enter the Percentage/G.P.A. for UG Degree III year"   value=''   />
				<?php if(isset($gradYear3PercentageAmrita) && $gradYear3PercentageAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear3PercentageAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear3PercentageAmrita );  ?>";
				      document.getElementById("gradYear3PercentageAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3PercentageAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Division: </label>
				<div class='fieldBoxLarge'>
				<select onchange="$('extra2Details').style.display = '';" required="true"  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='gradYear3DivisionAmrita' id='gradYear3DivisionAmrita'  style="width:120px;"  tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable.',this);" onmouseout="hidetip();" >
				  <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option></select>
				<?php if(isset($gradYear3DivisionAmrita) && $gradYear3DivisionAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear3DivisionAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear3DivisionAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear3DivisionAmrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<strong><?php echo $graduationCourseName;?> Fourth year details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear4PassingAmrita' id='gradYear4PassingAmrita'  validate="validateInteger"    caption="year"   minlength="4"   maxlength="4"     tip="Enter the year of passing for UG Degree IV year.  If yours was a 3 year degree, leave this field blank."   value=''   />
				<?php if(isset($gradYear4PassingAmrita) && $gradYear4PassingAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear4PassingAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear4PassingAmrita );  ?>";
				      document.getElementById("gradYear4PassingAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4PassingAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='gradYear4AttemptsAmrita' id='gradYear4AttemptsAmrita'  style="width:120px;"  tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.  If yours was a 3 year degree, leave this field blank."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.  If yours was a 3 year degree, leave this field blank.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option></select>
				<?php if(isset($gradYear4AttemptsAmrita) && $gradYear4AttemptsAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear4AttemptsAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear4AttemptsAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4AttemptsAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Subjects/Specialization: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='gradYear4SubjectsAmrita' id='gradYear4SubjectsAmrita'  validate="validateStr"   caption="subjects"   minlength="2"   maxlength="150"     tip="Enter the subjects or specialization for this course. For example Specialization for BA Economics will be <b>Economics</b> or <b>Mechanical Engineering</b> in case of engineering.  If yours was a 3 year degree, leave this field blank."   value=''  csv="true" />
				<?php if(isset($gradYear4SubjectsAmrita) && $gradYear4SubjectsAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear4SubjectsAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear4SubjectsAmrita );  ?>";
				      document.getElementById("gradYear4SubjectsAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4SubjectsAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label> Percentage/G.P.A.: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYear4PercentageAmrita' id='gradYear4PercentageAmrita'  validate="validateFloat"   caption="percentage"   minlength="2"   maxlength="7"     tip="Enter the Percentage/G.P.A. for UG Degree IV year.  If yours was a 3 year degree, leave this field blank."   value=''   />
				<?php if(isset($gradYear4PercentageAmrita) && $gradYear4PercentageAmrita!=""){ ?>
				  <script>
				      document.getElementById("gradYear4PercentageAmrita").value = "<?php echo str_replace("\n", '\n', $gradYear4PercentageAmrita );  ?>";
				      document.getElementById("gradYear4PercentageAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4PercentageAmrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Division: </label>
				<div class='fieldBoxLarge'>
				<select  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='gradYear4DivisionAmrita' id='gradYear4DivisionAmrita'  style='width:120px;'  tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable. If yours was a 3 year degree, leave this field blank."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable. If yours was a 3 year degree, leave this field blank.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option></select>
				<?php if(isset($gradYear4DivisionAmrita) && $gradYear4DivisionAmrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("gradYear4DivisionAmrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $gradYear4DivisionAmrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYear4DivisionAmrita_error'></div></div>
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
					$attempts = 'otherCourseAttempts_mul_'.$otherCourseId;
					$attemptsVal = $$attempts;
					$subjects = 'otherCourseSubjects_mul_'.$otherCourseId;
					$subjectsVal = $$subjects;
					$division = 'otherCourseDivision_mul_'.$otherCourseId;
					$divisionVal = $$division;
					$i++;

			?>

			<li>
            	<div class="semesterDetailsBox">
				<strong><?php echo $otherCourseName;?> details:</strong>
                <div class="clearFix spacer5"></div>
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
				<label>Number of attempts: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="attempts"  minlength="1" maxlength="1500" name='<?php echo $attempts; ?>' id='<?php echo $attempts; ?>'  style="width:120px;"  tip="Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1."       onmouseover="showTipOnline('Select the number of attempts you took to clear this examination. For eg: if you cleared this exam in one attempt, select 1.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option></select>
				<?php if(isset($attemptsVal) && $attemptsVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $attempts; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $attemptsVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $attempts; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label> Subjects/Specialization: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $subjects; ?>' id='<?php echo $subjects; ?>'  validate="validateStr"   required="true"   caption="subjects"   minlength="2"   maxlength="150"     tip="Enter the subjects or specialization for this course. For example Specialization for BA Economics will be <b>Economics</b> or <b>Mechanical Engineering</b> in case of engineering."   value=''  csv="true" />
				<?php if(isset($subjectsVal) && $subjectsVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $subjects; ?>").value = "<?php echo str_replace("\n", '\n', $subjectsVal );  ?>";
				      document.getElementById("<?php echo $subjects; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $subjects; ?>_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label> Division: </label>
				<div class='fieldBoxLarge'>
				<select required="true"  validate="validateSelect" caption="division"  minlength="1" maxlength="1500" name='<?php echo $division; ?>' id='<?php echo $division; ?>'  style='width:120px;'  tip="Select the divisions that you got in this course. If your course did not award a division, select Not Applicable."       onmouseover="showTipOnline('Select the divisions that you got in this course. If your course did not award a division, select Not Applicable.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='First' >First</option><option value='Second' >Second</option><option value='Third' >Third</option><option value='Not Applicable' >Not Applicable</option></select>
				<?php if(isset($divisionVal) && $divisionVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $division; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $divisionVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $division; ?>_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<?php
				}
			}
			?>


			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Please account for breaks in your academic career, if any: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='breakAmrita' id='breakAmrita'   style="width:618px; height:74px; padding:5px"    tip="If you have taken a break in your academic career, where you were not enrolled into any course, describe the reason here. If not, leave this field blank."  validate="validateStr" caption="break" minlength="1" maxlength="1000" ></textarea>
				<?php if(isset($breakAmrita) && $breakAmrita!=""){ ?>
				  <script>
				      document.getElementById("breakAmrita").value = "<?php echo str_replace("\n", '\n', $breakAmrita );  ?>";
				      document.getElementById("breakAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'breakAmrita_error'></div></div>
				</div>
				</div>
			</li>

		</ul>
		<ul id="extra2Details" style="display:none;">

			<li id="achievements1">
				<h3 class="upperCase">Achievements (if applicable)</h3>
                <div class="semesterDetailsBox" >
				<strong>Achievement Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardName1Amrita' id='awardName1Amrita'         tip="Enter the name of meritorious award that your received."   value=''  validate="validateStr" caption="name" minlength="2" maxlength="50" />
				<?php if(isset($awardName1Amrita) && $awardName1Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardName1Amrita").value = "<?php echo str_replace("\n", '\n', $awardName1Amrita );  ?>";
				      document.getElementById("awardName1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardName1Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardInst1Amrita' id='awardInst1Amrita'         tip="Enter the awarding institution for this award."   value=''  validate="validateStr" caption="institution" minlength="2" maxlength="50" />
				<?php if(isset($awardInst1Amrita) && $awardInst1Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardInst1Amrita").value = "<?php echo str_replace("\n", '\n', $awardInst1Amrita );  ?>";
				      document.getElementById("awardInst1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardInst1Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='awardLevel1Amrita' id='awardLevel1Amrita'  style="width:120px;"   tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($awardLevel1Amrita) && $awardLevel1Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("awardLevel1Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $awardLevel1Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardLevel1Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Basis of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardBasis1Amrita' id='awardBasis1Amrita'    validate="validateStr" caption="basis of award" minlength="2" maxlength="100"     tip="Enter the basis for this award or what was the criteria for selecting the awardees."   value=''   />
				<?php if(isset($awardBasis1Amrita) && $awardBasis1Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardBasis1Amrita").value = "<?php echo str_replace("\n", '\n', $awardBasis1Amrita );  ?>";
				      document.getElementById("awardBasis1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardBasis1Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='awardYear1Amrita' id='awardYear1Amrita'    validate="validateInteger" caption="year" minlength="4" maxlength="4"     tip="Enter the year when you received this award."   value=''   />
				<?php if(isset($awardYear1Amrita) && $awardYear1Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardYear1Amrita").value = "<?php echo str_replace("\n", '\n', $awardYear1Amrita );  ?>";
				      document.getElementById("awardYear1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardYear1Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li id="achievements2" style="display:none;">
            	<div class="semesterDetailsBox" >
				<strong>Achievement Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardName2Amrita' id='awardName2Amrita'   validate="validateStr" caption="name" minlength="2" maxlength="50"      tip="Enter the name of meritorious award that your received."   value=''   />
				<?php if(isset($awardName2Amrita) && $awardName2Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardName2Amrita").value = "<?php echo str_replace("\n", '\n', $awardName2Amrita );  ?>";
				      document.getElementById("awardName2Amrita").style.color = "";
				      $('achievements2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardName2Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardInst2Amrita' id='awardInst2Amrita'     validate="validateStr" caption="institution" minlength="2" maxlength="50"    tip="Enter the awarding institution for this award."   value=''   />
				<?php if(isset($awardInst2Amrita) && $awardInst2Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardInst2Amrita").value = "<?php echo str_replace("\n", '\n', $awardInst2Amrita );  ?>";
				      document.getElementById("awardInst2Amrita").style.color = "";
				      $('achievements2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardInst2Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='awardLevel2Amrita' id='awardLevel2Amrita'  style="width:120px;"   tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" >
					<option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($awardLevel2Amrita) && $awardLevel2Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("awardLevel2Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $awardLevel2Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardLevel2Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Basis of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardBasis2Amrita' id='awardBasis2Amrita'     validate="validateStr" caption="basis of award" minlength="2" maxlength="100"    tip="Enter the basis for this award or what was the criteria for selecting the awardees."   value=''   />
				<?php if(isset($awardBasis2Amrita) && $awardBasis2Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardBasis2Amrita").value = "<?php echo str_replace("\n", '\n', $awardBasis2Amrita );  ?>";
				      document.getElementById("awardBasis2Amrita").style.color = "";
				      $('achievements2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardBasis2Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='awardYear2Amrita' id='awardYear2Amrita'     validate="validateInteger" caption="year" minlength="4" maxlength="4"    tip="Enter the year when you received this award."   value=''   />
				<?php if(isset($awardYear2Amrita) && $awardYear2Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardYear2Amrita").value = "<?php echo str_replace("\n", '\n', $awardYear2Amrita );  ?>";
				      document.getElementById("awardYear2Amrita").style.color = "";
				      $('achievements2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardYear2Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li id="achievements3" style="display:none;">
            	<div class="semesterDetailsBox" >
				<strong>Achievement Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardName3Amrita' id='awardName3Amrita'    validate="validateStr" caption="name" minlength="2" maxlength="50"     tip="Enter the name of meritorious award that your received."   value=''   />
				<?php if(isset($awardName3Amrita) && $awardName3Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardName3Amrita").value = "<?php echo str_replace("\n", '\n', $awardName3Amrita );  ?>";
				      document.getElementById("awardName3Amrita").style.color = "";
				      $('achievements3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardName3Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardInst3Amrita' id='awardInst3Amrita'     validate="validateStr" caption="institution" minlength="2" maxlength="50"    tip="Enter the awarding institution for this award."   value=''   />
				<?php if(isset($awardInst3Amrita) && $awardInst3Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardInst3Amrita").value = "<?php echo str_replace("\n", '\n', $awardInst3Amrita );  ?>";
				      document.getElementById("awardInst3Amrita").style.color = "";
				      $('achievements3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardInst3Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='awardLevel3Amrita' id='awardLevel3Amrita' style="width:120px;"    tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($awardLevel3Amrita) && $awardLevel3Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("awardLevel3Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $awardLevel3Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardLevel3Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Basis of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardBasis3Amrita' id='awardBasis3Amrita'    validate="validateStr" caption="basis of award" minlength="2" maxlength="100"     tip="Enter the basis for this award or what was the criteria for selecting the awardees."   value=''   />
				<?php if(isset($awardBasis3Amrita) && $awardBasis3Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardBasis3Amrita").value = "<?php echo str_replace("\n", '\n', $awardBasis3Amrita );  ?>";
				      document.getElementById("awardBasis3Amrita").style.color = "";
				      $('achievements3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardBasis3Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='awardYear3Amrita' id='awardYear3Amrita'      validate="validateInteger" caption="year" minlength="4" maxlength="4"   tip="Enter the year when you received this award."   value=''   />
				<?php if(isset($awardYear3Amrita) && $awardYear3Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardYear3Amrita").value = "<?php echo str_replace("\n", '\n', $awardYear3Amrita );  ?>";
				      document.getElementById("awardYear3Amrita").style.color = "";
				      $('achievements3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardYear3Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li id="achievements4" style="display:none;">
            	<div class="semesterDetailsBox">
				<strong>Achievement Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardName4Amrita' id='awardName4Amrita'    validate="validateStr" caption="name" minlength="2" maxlength="50"     tip="Enter the name of meritorious award that your received."   value=''   />
				<?php if(isset($awardName4Amrita) && $awardName4Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardName4Amrita").value = "<?php echo str_replace("\n", '\n', $awardName4Amrita );  ?>";
				      document.getElementById("awardName4Amrita").style.color = "";
				      $('achievements4').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardName4Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Awarding Institution: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardInst4Amrita' id='awardInst4Amrita'    validate="validateStr" caption="institution" minlength="2" maxlength="50"     tip="Enter the awarding institution for this award."   value=''   />
				<?php if(isset($awardInst4Amrita) && $awardInst4Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardInst4Amrita").value = "<?php echo str_replace("\n", '\n', $awardInst4Amrita );  ?>";
				      document.getElementById("awardInst4Amrita").style.color = "";
				      $('achievements4').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardInst4Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='awardLevel4Amrita' id='awardLevel4Amrita'  style="width:120px;"   tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($awardLevel4Amrita) && $awardLevel4Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("awardLevel4Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $awardLevel4Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardLevel4Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Basis of Award: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='awardBasis4Amrita' id='awardBasis4Amrita'    validate="validateStr" caption="basis of award" minlength="2" maxlength="100"     tip="Enter the basis for this award or what was the criteria for selecting the awardees."   value=''   />
				<?php if(isset($awardBasis4Amrita) && $awardBasis4Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardBasis4Amrita").value = "<?php echo str_replace("\n", '\n', $awardBasis4Amrita );  ?>";
				      document.getElementById("awardBasis4Amrita").style.color = "";
				      $('achievements4').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardBasis4Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='awardYear4Amrita' id='awardYear4Amrita'    validate="validateInteger" caption="year" minlength="4" maxlength="4"     tip="Enter the year when you received this award."   value=''   />
				<?php if(isset($awardYear4Amrita) && $awardYear4Amrita!=""){ ?>
				  <script>
				      document.getElementById("awardYear4Amrita").value = "<?php echo str_replace("\n", '\n', $awardYear4Amrita );  ?>";
				      document.getElementById("awardYear4Amrita").style.color = "";
				      $('achievements4').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'awardYear4Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li id="achievementsAdd">
			  <a href="javascript:void(0);" onClick="addAchievements();">Add More &#187;</a>
			</li>
			<script>
  			if($('achievements2').style.display != 'none' && $('achievements3').style.display != 'none' && $('achievements4').style.display != 'none'){
			  $('achievementsAdd').style.display = 'none';
			}
			</script>
			
			
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
				foreach($workCompanies as $workCompanyKey => $workCompany) {
					$turnover = 'orgnTurnoverAmrita'.$workCompanyKey;
					$turnoverVal = $$turnover;
					$size = 'orgnSizeAmrita'.$workCompanyKey;
					$sizeVal = $$size;
					$category = 'orgnCategoryAmrita'.$workCompanyKey;
					$categoryVal = $$category;
					$otherC = 'orgnOtherAmrita'.$workCompanyKey;
					$otherCVal = $$otherC;
					$reporting = 'orgnReportingAmrita'.$workCompanyKey;
					$reportingVal = $$reporting;
					$salary = 'orgnSalaryAmrita'.$workCompanyKey;
					$salaryVal = $$salary;
					$j++;

			?>

			<li>
				<?php if($j==1){ ?><h3 class="upperCase">Work experience (if applicable)</h3><?php } ?>
                <div class="semesterDetailsBox">
				<strong><?php echo $workCompany; ?> details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $workCompany; ?> turnover in lakhs: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $turnover; ?>' id='<?php echo $turnover; ?>'         tip="Enter the annual turnover of this organization in Rs. Lakhs. If you do not know this information, enter <b>NA</b>."   value='' allowNA="true" validate="validateFloat" minlength="1" maxlength="10" caption="turnover" />
				<?php if(isset($turnoverVal) && $turnoverVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $turnover; ?>").value = "<?php echo str_replace("\n", '\n', $turnoverVal );  ?>";
				      document.getElementById("<?php echo $turnover; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $turnover; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $workCompany; ?> size (no. of employees): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $size; ?>' id='<?php echo $size; ?>'         tip="Enter the approximate number of employees in this organization. If you do not know the size of this organization, enter <b>NA</b>."   value=''  allowNA="true" validate="validateInteger" minlength="1" maxlength="10" caption="size" />
				<?php if(isset($sizeVal) && $sizeVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $size; ?>").value = "<?php echo str_replace("\n", '\n', $sizeVal );  ?>";
				      document.getElementById("<?php echo $size; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $size; ?>_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Your category at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge'>
				<select onchange="checkEmployeeCategory('<?php echo $workCompanyKey;?>');" name='<?php echo $category; ?>' id='<?php echo $category; ?>'    tip="Select the type of your employment with this organization."       onmouseover="showTipOnline('Select the type of your employment with this organization.',this);" onmouseout="hidetip();" >
				    <option value='Full Time' selected>Full Time</option><option value='Apprenticeship or Vocational' >Apprenticeship or Vocational</option><option value='Others' >Others</option></select>
				<?php if(isset($categoryVal) && $categoryVal!=""){ ?>
			      <script>
				  var selObj = document.getElementById("<?php echo $category; ?>"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $categoryVal;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $category; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Other (please specify): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $otherC; ?>' id='<?php echo $otherC; ?>'          value=''  disabled validate="validateStr"  caption="other category" minlength="2" maxlength="50" />
				<?php if(isset($otherCVal) && $otherCVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $otherC; ?>").disabled = false;
				      document.getElementById("<?php echo $otherC; ?>").setAttribute('required','true');
				      document.getElementById("<?php echo $otherC; ?>").value = "<?php echo str_replace("\n", '\n', $otherCVal );  ?>";
				      document.getElementById("<?php echo $otherC; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $otherC; ?>_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Reporting to at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $reporting; ?>' id='<?php echo $reporting; ?>'         tip="Enter the designation of person who you were reporting to. For example Managing Director, Marketing Head etc."   value='' validate="validateStr" minlength="2" maxlength="50" caption="name"  />
				<?php if(isset($reportingVal) && $reportingVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $reporting; ?>").value = "<?php echo str_replace("\n", '\n', $reportingVal );  ?>";
				      document.getElementById("<?php echo $reporting; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $reporting; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Monthly Salary at <?php echo $workCompany; ?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $salary; ?>' id='<?php echo $salary; ?>'         tip="Enter your monthly salary."   value=''  validate="validateFloat" minlength="1" maxlength="10" caption="salary" />
				<?php if(isset($salaryVal) && $salaryVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $salary; ?>").value = "<?php echo str_replace("\n", '\n', $salaryVal );  ?>";
				      document.getElementById("<?php echo $salary; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $salary; ?>_error'></div></div>
				</div>
				</div>
                </div>
			</li>
			<?php
				}
			}
			?>


			<li>
				<h3 class="upperCase">MAJOR CO-CURRICULAR ACTIVITIES</h3>
                <div class="semesterDetailsBox">
				<strong>Co-Curricular Activity Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Activity: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type="text" name='activity1Amrita' id='activity1Amrita'   validate="validateStr" caption="activity" minlength="2" maxlength="100"      tip="Enter the name of co-curricular activity for which you were given an award or honour. For example Singing,Painting, Cricket"    />
				<?php if(isset($activity1Amrita) && $activity1Amrita!=""){ ?>
				  <script>
				      document.getElementById("activity1Amrita").value = "<?php echo str_replace("\n", '\n', $activity1Amrita );  ?>";
				      document.getElementById("activity1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'activity1Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Role: </label>
				<div class='fieldBoxLarge'>
				<select name='role1Amrita' id='role1Amrita' style="width:120px;"   tip="Enter your role in this co-curricular activity."       onmouseover="showTipOnline('Enter your role in this co-curricular activity.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='Team Member' >Team Member</option><option value='Captain' >Captain</option><option value='Leader' >Leader</option><option value='Head' >Head</option></select>
				<?php if(isset($role1Amrita) && $role1Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("role1Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $role1Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'role1Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='level1Amrita' id='level1Amrita'  style="width:120px;"   tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($level1Amrita) && $level1Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("level1Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $level1Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'level1Amrita_error'></div></div>
				</div>
				</div>

				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year From: </label>
				<div class='fieldBoxLarge'>
				<select name='yearFrom1Amrita' id='yearFrom1Amrita'  style="width:120px;"   tip="Enter the year since you were associated with this co-curricular activity"       onmouseover="showTipOnline('Enter the year since you were associated with this co-curricular activity',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='2012' >2012</option><option value='2011' >2011</option><option value='2010' >2010</option><option value='2009' >2009</option><option value='2008' >2008</option><option value='2007' >2007</option><option value='2006' >2006</option><option value='2005' >2005</option><option value='2004' >2004</option><option value='2003' >2003</option><option value='2002' >2002</option><option value='2001' >2001</option><option value='2000' >2000</option><option value='1999' >1999</option><option value='1998' >1998</option><option value='1997' >1997</option><option value='1996' >1996</option><option value='1995' >1995</option><option value='1994' >1994</option><option value='1993' >1993</option><option value='1992' >1992</option></select>
				<?php if(isset($yearFrom1Amrita) && $yearFrom1Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("yearFrom1Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $yearFrom1Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearFrom1Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Year To: </label>
				<div class='fieldBoxLarge'>
				<select name='yearTo1Amrita' id='yearTo1Amrita'  style="width:120px;"   tip="Enter the year till you were associated with this co-curricular activity"       onmouseover="showTipOnline('Enter the year till you were associated with this co-curricular activity',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='2012' >2012</option><option value='2011' >2011</option><option value='2010' >2010</option><option value='2009' >2009</option><option value='2008' >2008</option><option value='2007' >2007</option><option value='2006' >2006</option><option value='2005' >2005</option><option value='2004' >2004</option><option value='2003' >2003</option><option value='2002' >2002</option><option value='2001' >2001</option><option value='2000' >2000</option><option value='1999' >1999</option><option value='1998' >1998</option><option value='1997' >1997</option><option value='1996' >1996</option><option value='1995' >1995</option><option value='1994' >1994</option><option value='1993' >1993</option><option value='1992' >1992</option></select>
				<?php if(isset($yearTo1Amrita) && $yearTo1Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("yearTo1Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $yearTo1Amrita;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearTo1Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Honours: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='honour1Amrita' id='honour1Amrita'    validate="validateStr" caption="honours" minlength="2" maxlength="100"     tip="Enter any honours or awards that you earned during your association with this activity."   value=''   />
				<?php if(isset($honour1Amrita) && $honour1Amrita!=""){ ?>
				  <script>
				      document.getElementById("honour1Amrita").value = "<?php echo str_replace("\n", '\n', $honour1Amrita );  ?>";
				      document.getElementById("honour1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'honour1Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Remarks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='remarks1Amrita' id='remarks1Amrita'    validate="validateStr" caption="remarks" minlength="2" maxlength="100"     tip="Enter any remarks that you feel are important for our institute to know about this activity."   value=''   />
				<?php if(isset($remarks1Amrita) && $remarks1Amrita!=""){ ?>
				  <script>
				      document.getElementById("remarks1Amrita").value = "<?php echo str_replace("\n", '\n', $remarks1Amrita );  ?>";
				      document.getElementById("remarks1Amrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'remarks1Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>
			
			<li id="curricular2" style="display:none;">
            	<div class="semesterDetailsBox">
				<strong>Co-Curricular Activity Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Activity: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type="text" name='activity2Amrita' id='activity2Amrita'    validate="validateStr" caption="activity" minlength="2" maxlength="100"     tip="Enter the name of co-curricular activity for which you were given an award or honour. For example Singing,Painting, Cricket"    />
				<?php if(isset($activity2Amrita) && $activity2Amrita!=""){ ?>
				  <script>
				      document.getElementById("activity2Amrita").value = "<?php echo str_replace("\n", '\n', $activity2Amrita );  ?>";
				      document.getElementById("activity2Amrita").style.color = "";
				      $('curricular2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'activity2Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Role: </label>
				<div class='fieldBoxLarge'>
				<select name='role2Amrita' id='role2Amrita'  style="width:120px;"   tip="Enter your role in this co-curricular activity."       onmouseover="showTipOnline('Enter your role in this co-curricular activity.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='Team Member' >Team Member</option><option value='Captain' >Captain</option><option value='Leader' >Leader</option><option value='Head' >Head</option></select>
				<?php if(isset($role2Amrita) && $role2Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("role2Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $role2Amrita;?>"){
					  $('curricular2').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'role2Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='level2Amrita' id='level2Amrita'  style="width:120px;"   tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($level2Amrita) && $level2Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("level2Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $level2Amrita;?>"){
					  $('curricular2').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'level2Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Year From: </label>
				<div class='fieldBoxLarge'>
				<select name='yearFrom2Amrita' id='yearFrom2Amrita'  style="width:120px;"   tip="Enter the year since you were associated with this co-curricular activity"       onmouseover="showTipOnline('Enter the year since you were associated with this co-curricular activity',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='2012' >2012</option><option value='2011' >2011</option><option value='2010' >2010</option><option value='2009' >2009</option><option value='2008' >2008</option><option value='2007' >2007</option><option value='2006' >2006</option><option value='2005' >2005</option><option value='2004' >2004</option><option value='2003' >2003</option><option value='2002' >2002</option><option value='2001' >2001</option><option value='2000' >2000</option><option value='1999' >1999</option><option value='1998' >1998</option><option value='1997' >1997</option><option value='1996' >1996</option><option value='1995' >1995</option><option value='1994' >1994</option><option value='1993' >1993</option><option value='1992' >1992</option></select>
				<?php if(isset($yearFrom2Amrita) && $yearFrom2Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("yearFrom2Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $yearFrom2Amrita;?>"){
					  $('curricular2').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearFrom2Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Year To: </label>
				<div class='fieldBoxLarge'>
				<select name='yearTo2Amrita' id='yearTo2Amrita' style="width:120px;"    tip="Enter the year till you were associated with this co-curricular activity"       onmouseover="showTipOnline('Enter the year till you were associated with this co-curricular activity',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='2012' >2012</option><option value='2011' >2011</option><option value='2010' >2010</option><option value='2009' >2009</option><option value='2008' >2008</option><option value='2007' >2007</option><option value='2006' >2006</option><option value='2005' >2005</option><option value='2004' >2004</option><option value='2003' >2003</option><option value='2002' >2002</option><option value='2001' >2001</option><option value='2000' >2000</option><option value='1999' >1999</option><option value='1998' >1998</option><option value='1997' >1997</option><option value='1996' >1996</option><option value='1995' >1995</option><option value='1994' >1994</option><option value='1993' >1993</option><option value='1992' >1992</option></select>
				<?php if(isset($yearTo2Amrita) && $yearTo2Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("yearTo2Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $yearTo2Amrita;?>"){
					  $('curricular2').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearTo2Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Honours: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='honour2Amrita' id='honour2Amrita'     validate="validateStr" caption="honours" minlength="2" maxlength="100"    tip="Enter any honours or awards that you earned during your association with this activity."   value=''   />
				<?php if(isset($honour2Amrita) && $honour2Amrita!=""){ ?>
				  <script>
				      document.getElementById("honour2Amrita").value = "<?php echo str_replace("\n", '\n', $honour2Amrita );  ?>";
				      document.getElementById("honour2Amrita").style.color = "";
				      $('curricular2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'honour2Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Remarks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='remarks2Amrita' id='remarks2Amrita'     validate="validateStr" caption="remarks" minlength="2" maxlength="100"    tip="Enter any remarks that you feel are important for our institute to know about this activity."   value=''   />
				<?php if(isset($remarks2Amrita) && $remarks2Amrita!=""){ ?>
				  <script>
				      document.getElementById("remarks2Amrita").value = "<?php echo str_replace("\n", '\n', $remarks2Amrita );  ?>";
				      document.getElementById("remarks2Amrita").style.color = "";
				      $('curricular2').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'remarks2Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li id="curricular3" style="display:none;">
            	<div class="semesterDetailsBox">
				<strong>Co-Curricular Activity Details:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Activity: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type="text" name='activity3Amrita' id='activity3Amrita'    validate="validateStr" caption="activity" minlength="2" maxlength="100"     tip="Enter the name of co-curricular activity for which you were given an award or honour. For example Singing,Painting, Cricket"    />
				<?php if(isset($activity3Amrita) && $activity3Amrita!=""){ ?>
				  <script>
				      document.getElementById("activity3Amrita").value = "<?php echo str_replace("\n", '\n', $activity3Amrita );  ?>";
				      document.getElementById("activity3Amrita").style.color = "";
				      $('curricular3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'activity3Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Role: </label>
				<div class='fieldBoxLarge'>
				<select name='role3Amrita' id='role3Amrita'  style="width:120px;"   tip="Enter your role in this co-curricular activity."       onmouseover="showTipOnline('Enter your role in this co-curricular activity.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Team Member' >Team Member</option><option value='Captain' >Captain</option><option value='Leader' >Leader</option><option value='Head' >Head</option></select>
				<?php if(isset($role3Amrita) && $role3Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("role3Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $role3Amrita;?>"){
					  $('curricular3').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'role3Amrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Level: </label>
				<div class='fieldBoxLarge'>
				<select name='level3Amrita' id='level3Amrita'  style="width:120px;"   tip="Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>."       onmouseover="showTipOnline('Enter the level or position you secured in this award for example if you received the  first prize or gold medal, select 1. If there were no levels or positions in this award, select <b>NA</b>.',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='1' >1</option><option value='2' >2</option><option value='3' >3</option><option value='4' >4</option><option value='5' >5</option><option value='6' >6</option><option value='NA' >NA</option></select>
				<?php if(isset($level3Amrita) && $level3Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("level3Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $level3Amrita;?>"){
					  $('curricular3').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'level3Amrita_error'></div></div>
				</div>
				</div>

				<div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Year From: </label>
				<div class='fieldBoxLarge'>
				<select name='yearFrom3Amrita' id='yearFrom3Amrita'  style="width:120px;"   tip="Enter the year since you were associated with this co-curricular activity"       onmouseover="showTipOnline('Enter the year since you were associated with this co-curricular activity',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='2012' >2012</option><option value='2011' >2011</option><option value='2010' >2010</option><option value='2009' >2009</option><option value='2008' >2008</option><option value='2007' >2007</option><option value='2006' >2006</option><option value='2005' >2005</option><option value='2004' >2004</option><option value='2003' >2003</option><option value='2002' >2002</option><option value='2001' >2001</option><option value='2000' >2000</option><option value='1999' >1999</option><option value='1998' >1998</option><option value='1997' >1997</option><option value='1996' >1996</option><option value='1995' >1995</option><option value='1994' >1994</option><option value='1993' >1993</option><option value='1992' >1992</option></select>
				<?php if(isset($yearFrom3Amrita) && $yearFrom3Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("yearFrom3Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $yearFrom3Amrita;?>"){
					  $('curricular3').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearFrom3Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Year To: </label>
				<div class='fieldBoxLarge'>
				<select name='yearTo3Amrita' id='yearTo3Amrita'  style="width:120px;"   tip="Enter the year till you were associated with this co-curricular activity"       onmouseover="showTipOnline('Enter the year till you were associated with this co-curricular activity',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='2012' >2012</option><option value='2011' >2011</option><option value='2010' >2010</option><option value='2009' >2009</option><option value='2008' >2008</option><option value='2007' >2007</option><option value='2006' >2006</option><option value='2005' >2005</option><option value='2004' >2004</option><option value='2003' >2003</option><option value='2002' >2002</option><option value='2001' >2001</option><option value='2000' >2000</option><option value='1999' >1999</option><option value='1998' >1998</option><option value='1997' >1997</option><option value='1996' >1996</option><option value='1995' >1995</option><option value='1994' >1994</option><option value='1993' >1993</option><option value='1992' >1992</option></select>
				<?php if(isset($yearTo3Amrita) && $yearTo3Amrita!=""){ ?>
			      <script>
				  var selObj = document.getElementById("yearTo3Amrita"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $yearTo3Amrita;?>"){
					  $('curricular3').style.display = '';
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearTo3Amrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Honours: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='honour3Amrita' id='honour3Amrita'      validate="validateStr" caption="honours" minlength="2" maxlength="100"   tip="Enter any honours or awards that you earned during your association with this activity."   value=''   />
				<?php if(isset($honour3Amrita) && $honour3Amrita!=""){ ?>
				  <script>
				      document.getElementById("honour3Amrita").value = "<?php echo str_replace("\n", '\n', $honour3Amrita );  ?>";
				      document.getElementById("honour3Amrita").style.color = "";
				      $('curricular3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'honour3Amrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Remarks: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='remarks3Amrita' id='remarks3Amrita'      validate="validateStr" caption="remarks" minlength="2" maxlength="100"   tip="Enter any remarks that you feel are important for our institute to know about this activity."   value=''   />
				<?php if(isset($remarks3Amrita) && $remarks3Amrita!=""){ ?>
				  <script>
				      document.getElementById("remarks3Amrita").value = "<?php echo str_replace("\n", '\n', $remarks3Amrita );  ?>";
				      document.getElementById("remarks3Amrita").style.color = "";
				      $('curricular3').style.display = '';
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'remarks3Amrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li id="curricularAdd">
			  <a href="javascript:void(0);" onClick="addCurricular();">Add More &#187;</a>
			</li>
			<script>
  			if($('curricular2').style.display != 'none' && $('curricular3').style.display != 'none'){
			  $('curricularAdd').style.display = 'none';
			}
			</script>
			
			<li>
				<h3 class="upperCase">PLEASE FURNISH TWO REFERENCES (SHOULD NOT BE CLOSE RELATIVES)</h3>
                <div class="semesterDetailsBox">
                <strong>First:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1NameAmrita' id='ref1NameAmrita'  validate="validateStr"   required="true"   caption="name"   minlength="2"   maxlength="50"     tip="Enter Full Name of the reference"   value=''   />
				<?php if(isset($ref1NameAmrita) && $ref1NameAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1NameAmrita").value = "<?php echo str_replace("\n", '\n', $ref1NameAmrita );  ?>";
				      document.getElementById("ref1NameAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1NameAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Occupation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1OccupationAmrita' id='ref1OccupationAmrita'  validate="validateStr"   required="true"   caption="occupation"   minlength="2"   maxlength="50"     tip="Enter the occupation of the reference. For example General Manager in HCL, Head of department in Delhi University etc."   value=''   />
				<?php if(isset($ref1OccupationAmrita) && $ref1OccupationAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1OccupationAmrita").value = "<?php echo str_replace("\n", '\n', $ref1OccupationAmrita );  ?>";
				      document.getElementById("ref1OccupationAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1OccupationAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1AddressAmrita' id='ref1AddressAmrita'  validate="validateStr"   required="true"   caption="address"   minlength="2"   maxlength="150"     tip="Enter the complete address of this reference"   value=''   />
				<?php if(isset($ref1AddressAmrita) && $ref1AddressAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1AddressAmrita").value = "<?php echo str_replace("\n", '\n', $ref1AddressAmrita );  ?>";
				      document.getElementById("ref1AddressAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1AddressAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Pin: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1PinAmrita' id='ref1PinAmrita'  validate="validateInteger"   required="true"   caption="pincode"   minlength="5"   maxlength="7"     tip="Enter the PIN code of this reference."   value=''   />
				<?php if(isset($ref1PinAmrita) && $ref1PinAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1PinAmrita").value = "<?php echo str_replace("\n", '\n', $ref1PinAmrita );  ?>";
				      document.getElementById("ref1PinAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1PinAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>STD Code: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1STDAmrita' id='ref1STDAmrita'  validate="validateInteger"   required="true"   caption="STD code"   minlength="2"   maxlength="5"     tip="Enter the STD code of this reference"   value=''   />
				<?php if(isset($ref1STDAmrita) && $ref1STDAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1STDAmrita").value = "<?php echo str_replace("\n", '\n', $ref1STDAmrita );  ?>";
				      document.getElementById("ref1STDAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1STDAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1PhoneAmrita' id='ref1PhoneAmrita'  validate="validateInteger"   required="true"   caption="phone"   minlength="6"   maxlength="10"     tip="Enter the phone number of this reference"   value='' blurMethod="checkRefPhone(1);"   />
				<?php if(isset($ref1PhoneAmrita) && $ref1PhoneAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1PhoneAmrita").value = "<?php echo str_replace("\n", '\n', $ref1PhoneAmrita );  ?>";
				      document.getElementById("ref1PhoneAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1PhoneAmrita_error'></div></div>
				</div>
				</div>
				<div class="clearFix spacer15"></div>
                
				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref1EmailAmrita' id='ref1EmailAmrita'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="50"     tip="Enter the email address of this reference"   value=''  blurMethod="checkRefEmail(1);"  />
				<?php if(isset($ref1EmailAmrita) && $ref1EmailAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref1EmailAmrita").value = "<?php echo str_replace("\n", '\n', $ref1EmailAmrita );  ?>";
				      document.getElementById("ref1EmailAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref1EmailAmrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<strong>Second:</strong>
                <div class="clearFix spacer5"></div>
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2NameAmrita' id='ref2NameAmrita'  validate="validateStr"   required="true"   caption="name"   minlength="2"   maxlength="50"     tip="Enter Full Name of the reference"   value=''   />
				<?php if(isset($ref2NameAmrita) && $ref2NameAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2NameAmrita").value = "<?php echo str_replace("\n", '\n', $ref2NameAmrita );  ?>";
				      document.getElementById("ref2NameAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2NameAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Occupation: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2OccupationAmrita' id='ref2OccupationAmrita'  validate="validateStr"   required="true"   caption="occupation"   minlength="2"   maxlength="50"     tip="Enter the occupation of the reference. For example General Manager in HCL, Head of department in Delhi University etc."   value=''   />
				<?php if(isset($ref2OccupationAmrita) && $ref2OccupationAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2OccupationAmrita").value = "<?php echo str_replace("\n", '\n', $ref2OccupationAmrita );  ?>";
				      document.getElementById("ref2OccupationAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2OccupationAmrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2AddressAmrita' id='ref2AddressAmrita'  validate="validateStr"   required="true"   caption="address"   minlength="2"   maxlength="150"     tip="Enter the complete address of this reference"   value=''   />
				<?php if(isset($ref2AddressAmrita) && $ref2AddressAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2AddressAmrita").value = "<?php echo str_replace("\n", '\n', $ref2AddressAmrita );  ?>";
				      document.getElementById("ref2AddressAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2AddressAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Pin: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2PinAmrita' id='ref2PinAmrita'  validate="validateInteger"   required="true"   caption="pincode"   minlength="5"   maxlength="7"     tip="Enter the PIN code of this reference."   value=''   />
				<?php if(isset($ref2PinAmrita) && $ref2PinAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2PinAmrita").value = "<?php echo str_replace("\n", '\n', $ref2PinAmrita );  ?>";
				      document.getElementById("ref2PinAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2PinAmrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>STD Code: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2STDAmrita' id='ref2STDAmrita'  validate="validateInteger"   required="true"   caption="STD code"   minlength="2"   maxlength="5"     tip="Enter the STD code of this reference"   value=''   />
				<?php if(isset($ref2STDAmrita) && $ref2STDAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2STDAmrita").value = "<?php echo str_replace("\n", '\n', $ref2STDAmrita );  ?>";
				      document.getElementById("ref2STDAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2STDAmrita_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2PhoneAmrita' id='ref2PhoneAmrita'  validate="validateInteger"   required="true"   caption="phone"   minlength="6"   maxlength="10"     tip="Enter the phone number of this reference"   value=''  blurMethod="checkRefPhone(2);"  />
				<?php if(isset($ref2PhoneAmrita) && $ref2PhoneAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2PhoneAmrita").value = "<?php echo str_replace("\n", '\n', $ref2PhoneAmrita );  ?>";
				      document.getElementById("ref2PhoneAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2PhoneAmrita_error'></div></div>
				</div>
				</div>
				
                <div class="clearFix spacer15"></div>
				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='ref2EmailAmrita' id='ref2EmailAmrita'  validate="validateEmail"   required="true"   caption="email"   minlength="2"   maxlength="50"     tip="Enter the email address of this reference"   value='' blurMethod="checkRefEmail(2);"  />
				<?php if(isset($ref2EmailAmrita) && $ref2EmailAmrita!=""){ ?>
				  <script>
				      document.getElementById("ref2EmailAmrita").value = "<?php echo str_replace("\n", '\n', $ref2EmailAmrita );  ?>";
				      document.getElementById("ref2EmailAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ref2EmailAmrita_error'></div></div>
				</div>
				</div>
                </div>
			</li>
		      <li style="width:100%">
				<h3 class="upperCase">REFLECTIVE ESSAYS (if you called for the interview process then you have to answer (hand written) these subjective questions and bring it to the interview venue)</h3>
				<div class='additionalInfoLeftCol' style="width:950px">
				  How will you introduce yourself to your classmates when you join the MBA class of 2013 at ASB?
				</div>
		      </li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				  What are your career objectives and what do you need to learn at ASB to accomplish them?
				</div>
		      </li>
		      <li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				  What are your career objectives and what do you need to learn at ASB to accomplish them?
				</div>
		      </li>
			<!--<li style="width:100%">
				<h3 class="upperCase">General</h3>
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>How will you introduce yourself to your classmates when you join the MBA class of 2013 at ASB?: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='introduceAmrita' id='introduceAmrita'  validate="validateStr"   required="true"   caption="introduction"   minlength="400"   maxlength="2000"     tip="Please write a 400 words essay on How will you introduce yourself to your classmates when you join the MBA class of 2013 at ASB."  style="width:618px; height:74px; padding:5px"     ></textarea>
				<?php if(isset($introduceAmrita) && $introduceAmrita!=""){ ?>
				  <script>
				      document.getElementById("introduceAmrita").value = "<?php echo str_replace("\n", '\n', $introduceAmrita );  ?>";
				      document.getElementById("introduceAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'introduceAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>What are your career objectives and what do you need to learn at ASB to accomplish them?: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='careerAmrita' id='careerAmrita'  validate="validateStr"   required="true"   caption="career objectives"   minlength="400"   maxlength="2000"     tip="Please write a 400 words essay on What are your career objectives and what do you need to learn at ASB to accomplish them."  style="width:618px; height:74px; padding:5px"     ></textarea>
				<?php if(isset($careerAmrita) && $careerAmrita!=""){ ?>
				  <script>
				      document.getElementById("careerAmrita").value = "<?php echo str_replace("\n", '\n', $careerAmrita );  ?>";
				      document.getElementById("careerAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'careerAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Tell us about a mistake you made in your life and what you learned from that experience?: </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='mistakeAmrita' id='mistakeAmrita'  validate="validateStr"   required="true"   caption="mistake"   minlength="400"   maxlength="2000"     tip="Please write a 400 words essay about a mistake you made in your life and what you learned from that experience."  style="width:618px; height:74px; padding:5px"     ></textarea>
				<?php if(isset($mistakeAmrita) && $mistakeAmrita!=""){ ?>
				  <script>
				      document.getElementById("mistakeAmrita").value = "<?php echo str_replace("\n", '\n', $mistakeAmrita );  ?>";
				      document.getElementById("mistakeAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mistakeAmrita_error'></div></div>
				</div>
				</div>
			</li>
-->
			<li style="width:100%;">
				<h3 class="upperCase">How did you come to know of ASB?</h3>
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>CAT Coaching Centres: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input type='checkbox' name='coachingAmrita[]' id='coachingAmrita0'   value='IMS'  onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" >IMS</span>&nbsp;&nbsp;
				<input type='checkbox' name='coachingAmrita[]' id='coachingAmrita1'   value='T.I.M.E.'     onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" >T.I.M.E.</span>&nbsp;&nbsp;
				<input type='checkbox' name='coachingAmrita[]' id='coachingAmrita2'   value='PT Education'     onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" >PT Education</span>&nbsp;&nbsp;
				<input type='checkbox' name='coachingAmrita[]' id='coachingAmrita3'   value='Career Launcher'     onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" >Career Launcher</span>&nbsp;&nbsp;
				<input onClick="checkCoachingSource(this);" type='checkbox' name='coachingAmrita[]' id='coachingAmrita4'   value='Others'     onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a coaching center where you heard about ASB.',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($coachingAmrita) && $coachingAmrita!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["coachingAmrita[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$coachingAmrita);
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
				
				<div style='display:none'><div class='errorMsg' id= 'coachingAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%;">
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Other (please specify): </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input style="width:300px" type='text' name='coachingOtherAmrita' id='coachingOtherAmrita'      disabled validate="validateStr" minlength="2" maxlength="50" caption="other sources"     value=''   />
				<?php if(isset($coachingOtherAmrita) && $coachingOtherAmrita!=""){ ?>
				  <script>
				      document.getElementById("coachingOtherAmrita").disabled = false;
				      document.getElementById("coachingOtherAmrita").setAttribute('required','true');
				      document.getElementById("coachingOtherAmrita").value = "<?php echo str_replace("\n", '\n', $coachingOtherAmrita );  ?>";
				      document.getElementById("coachingOtherAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'coachingOtherAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%;">
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Newspaper: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input type='checkbox' name='newspaperAmrita[]' id='newspaperAmrita0'   value='Hindustan Times'    onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" >Hindustan Times</span>&nbsp;&nbsp;
				<input type='checkbox' name='newspaperAmrita[]' id='newspaperAmrita1'   value='Indian Express'     onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" >Indian Express</span>&nbsp;&nbsp;
				<input type='checkbox' name='newspaperAmrita[]' id='newspaperAmrita2'   value='Times of India'     onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" >Times of India</span>&nbsp;&nbsp;
				<input type='checkbox' name='newspaperAmrita[]' id='newspaperAmrita3'   value='The Hindu'     onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" >The Hindu</span>&nbsp;&nbsp;
				<input type='checkbox' name='newspaperAmrita[]' id='newspaperAmrita4'   value='Telegraph'     onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a newspaper where you got to know about ASB',this);" onmouseout="hidetip();" >Telegraph</span>&nbsp;&nbsp;
				<?php if(isset($newspaperAmrita) && $newspaperAmrita!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["newspaperAmrita[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$newspaperAmrita);
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
				
				<div style='display:none'><div class='errorMsg' id= 'newspaperAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Internet: </label>
				<div class='fieldBoxLarge' style="width:620px;">
				<input type='checkbox' name='internetAmrita[]' id='internetAmrita0'   value='Shiksha'    onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >Shiksha</span>&nbsp;&nbsp;
				<input type='checkbox' name='internetAmrita[]' id='internetAmrita1'   value='Cool avenues.com'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >Cool avenues.com</span>&nbsp;&nbsp;
				<input type='checkbox' name='internetAmrita[]' id='internetAmrita2'   value='AIMA'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >AIMA</span>&nbsp;&nbsp;
				<input type='checkbox' name='internetAmrita[]' id='internetAmrita3'   value='Amrita'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >Amrita</span>&nbsp;&nbsp;
				<input type='checkbox' name='internetAmrita[]' id='internetAmrita4'   value='IIMs'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >IIMs</span>&nbsp;&nbsp;

				<input type='checkbox' name='internetAmrita[]' id='internetAmrita5'   value='MBA Universe'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >MBA Universe</span>&nbsp;&nbsp;
                <br />
				<input type='checkbox' name='internetAmrita[]' id='internetAmrita6'   value='PagalGuy'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >PagalGuy</span>&nbsp;&nbsp;
				<input onClick="checkInternetSource(this);" type='checkbox' name='internetAmrita[]' id='internetAmrita7'   value='Others'     onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please select a website where you got to know about ASB',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($internetAmrita) && $internetAmrita!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["internetAmrita[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$internetAmrita);
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
				
				<div style='display:none'><div class='errorMsg' id= 'internetAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%;">
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Other (please specify): </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input style="width:300px" type='text' name='internetOtherAmrita' id='internetOtherAmrita'    disabled validate="validateStr" minlength="2" maxlength="50" caption="other sources"       value=''   />
				<?php if(isset($internetOtherAmrita) && $internetOtherAmrita!=""){ ?>
				  <script>
				      document.getElementById("internetOtherAmrita").disabled = false;
				      document.getElementById("internetOtherAmrita").setAttribute('required','true');
				      document.getElementById("internetOtherAmrita").value = "<?php echo str_replace("\n", '\n', $internetOtherAmrita );  ?>";
				      document.getElementById("internetOtherAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'internetOtherAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%;">
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Other sources: </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input type='checkbox' name='otherSourcesAmrita[]' id='otherSourcesAmrita0'   value='Alumni'    onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" >Alumni</span>&nbsp;&nbsp;
				<input type='checkbox' name='otherSourcesAmrita[]' id='otherSourcesAmrita1'   value='Amrita University'     onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" >Amrita University</span>&nbsp;&nbsp;
				<input type='checkbox' name='otherSourcesAmrita[]' id='otherSourcesAmrita2'   value='Friends'     onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" >Friends</span>&nbsp;&nbsp;
				<input type='checkbox' name='otherSourcesAmrita[]' id='otherSourcesAmrita3'   value='M.A. MATH'     onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" >M.A. MATH</span>&nbsp;&nbsp;
				<input type='checkbox' name='otherSourcesAmrita[]' id='otherSourcesAmrita4'   value='Relatives'     onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" ><span  onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" >Relatives</span>&nbsp;&nbsp;
				<input onClick="checkOtherSource(this);" type='checkbox' name='otherSourcesAmrita[]' id='otherSourcesAmrita5'   value='Others'     onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Please specify of you heard about ASB from other sources',this);" onmouseout="hidetip();" >Others</span>&nbsp;&nbsp;
				<?php if(isset($otherSourcesAmrita) && $otherSourcesAmrita!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["otherSourcesAmrita[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$otherSourcesAmrita);
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
				
				<div style='display:none'><div class='errorMsg' id= 'otherSourcesAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<li style="width:100%;">
				<div class='additionalInfoLeftCol' style="width:930px;">
				<label>Other (please specify): </label>
				<div class='fieldBoxLarge' style="width:590px;">
				<input style="width:300px" type='text' name='otherSourcesOtherAmrita' id='otherSourcesOtherAmrita'   disabled validate="validateStr" minlength="2" maxlength="50" caption="other sources"      value=''   />
				<?php if(isset($otherSourcesOtherAmrita) && $otherSourcesOtherAmrita!=""){ ?>
				  <script>
				      document.getElementById("otherSourcesOtherAmrita").disabled = false;
				      document.getElementById("otherSourcesOtherAmrita").setAttribute('required','true');
				      document.getElementById("otherSourcesOtherAmrita").value = "<?php echo str_replace("\n", '\n', $otherSourcesOtherAmrita );  ?>";
				      document.getElementById("otherSourcesOtherAmrita").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherSourcesOtherAmrita_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations)): ?>
			<li>
            	<div class="additionalInfoLeftCol" style="width: 100%;">
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge' style="width: 617px;">
			<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				 <?php foreach($gdpiLocations as $gdpiLocation): ?>
                                                <option value='<?php echo $gdpiLocation['city_id']; ?>'><?php if($gdpiLocation['city_name']=='Delhi'){ echo 'New Delhi';}else if($gdpiLocation['city_id']=='278'){ echo 'Bengaluru';}else{ echo $gdpiLocation['city_name'];}  ?></option>
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
				<div style="margin-left: 10px; width: 501px; float: right;color:#666666; font-style:italic">Management has the right to change the locations based on requirements.</div>
				<div style='display:none'><div class='errorMsg' id= 'preferredGDPILocation_error'></div></div>
				</div>
                </div>
			</li>
			<?php endif; ?>


			<li>
            	<div class="additionalInfoLeftCol" style="width:950px">
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				All entries made in the application form are true to the best of my knowledge and belief. I am willing to produce original certificates on demand at any time. I also undertake that I shall abide by the rules and regulations of ASB and the University.
				<div class="spacer10 clearFix"></div>
				<div>
				<input type='checkbox' name='agreeToTermsAmrita' id='agreeToTermsAmrita' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsAmrita) && $agreeToTermsAmrita!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsAmrita"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsAmrita);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsAmrita_error'></div></div>


				</div>
				</div>
                </div>
			</li>
			</ul> <!-- Close extraDetails2 ul -->  
	    <?php endif; ?>
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

  function checkCoachingSource(objId){
      if(objId.checked==true){
	  $('coachingOtherAmrita').disabled = false;
	  $('coachingOtherAmrita').setAttribute('required','true');
      }
      else{
	  $('coachingOtherAmrita').value = '';
	  $('coachingOtherAmrita').disabled = true;
	  $('coachingOtherAmrita').removeAttribute('required');
	  $('coachingOtherAmrita_error').innerHTML = '';
	  $('coachingOtherAmrita_error').parentNode.style.display = 'none';
      }
  }

  function checkInternetSource(objId){
      if(objId.checked==true){
	  $('internetOtherAmrita').disabled = false;
	  $('internetOtherAmrita').setAttribute('required','true');
      }
      else{
	  $('internetOtherAmrita').value = '';
	  $('internetOtherAmrita').disabled = true;
	  $('internetOtherAmrita').removeAttribute('required');
	  $('internetOtherAmrita_error').innerHTML = '';
	  $('internetOtherAmrita_error').parentNode.style.display = 'none';
      }
  }

  function checkOtherSource(objId){
      if(objId.checked==true){
	  $('otherSourcesOtherAmrita').disabled = false;
	  $('otherSourcesOtherAmrita').setAttribute('required','true');
      }
      else{
	  $('otherSourcesOtherAmrita').value = '';
	  $('otherSourcesOtherAmrita').disabled = true;
	  $('otherSourcesOtherAmrita').removeAttribute('required');
	  $('otherSourcesOtherAmrita_error').innerHTML = '';
	  $('otherSourcesOtherAmrita_error').parentNode.style.display = 'none';
      }
  }

  function checkValidCampusPreference(id){
	if(id==1){ sId = 2; tId=3; fId=4; }
	else if(id==2){ sId = 1; tId = 3; fId=4; }
	else if(id==3){sId = 1; tId = 2;  fId=4; }
	else {sId = 1; tId = 2;  fId=3; }
	var selectedPrefObj = document.getElementById('pref'+id+'Amrita'); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById('pref'+sId+'Amrita'); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	var selObj2 = document.getElementById('pref'+tId+'Amrita'); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].value;
	var selObj3 = document.getElementById('pref'+fId+'Amrita'); 
	var selPref3 = selObj3.options[selObj3.selectedIndex].value;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) || (selectedPref == selPref3 && selectedPref!='' ) ){
		$('pref'+id+'Amrita'+'_error').innerHTML = 'Same preference can’t be set.';
		$('pref'+id+'Amrita'+'_error').parentNode.style.display = '';
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
		$('pref'+id+'Amrita'+'_error').innerHTML = '';
		$('pref'+id+'Amrita'+'_error').parentNode.style.display = 'none';
	}
	return true;
  }

  function checkActivitiesLevel(id){
      var selectedPrefObj = document.getElementById('levelOther'+id+'Amrita'); 
      var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
      if(selectedPref == 'Newspaper'){
	  $('newspaperDetailsIndra').disabled = false;
	  $('newspaperDetailsIndra').setAttribute('required','true');
	  $('newspaperDetailsIndra_error').innerHTML = '';
	  $('newspaperDetailsIndra_error').parentNode.style.display = 'none';
      }
      else{
	  $('newspaperDetailsIndra').value = '';
	  $('newspaperDetailsIndra').disabled = true;
	  $('newspaperDetailsIndra').removeAttribute('required');
	  $('newspaperDetailsIndra_error').innerHTML = '';
	  $('newspaperDetailsIndra_error').parentNode.style.display = 'none';
      }
  }

  function checkEmployeeCategory(key){
      var selectedPrefObj = document.getElementById('orgnCategoryAmrita'+key); 
      var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
      if(selectedPref == 'Others'){
	  $('orgnOtherAmrita'+key).disabled = false;
	  $('orgnOtherAmrita'+key).setAttribute('required','true');
	  $('orgnOtherAmrita'+key+'_error').innerHTML = '';
	  $('orgnOtherAmrita'+key+'_error').parentNode.style.display = 'none';
      }
      else{
	  $('orgnOtherAmrita'+key).value = '';
	  $('orgnOtherAmrita'+key).disabled = true;
	  $('orgnOtherAmrita'+key).removeAttribute('required');
	  $('orgnOtherAmrita'+key+'_error').innerHTML = '';
	  $('orgnOtherAmrita'+key+'_error').parentNode.style.display = 'none';
      }
  }

  function checkRefEmail(objNumber){
      if(objNumber==1) sId = 2; else sId = 1;
      if($('ref'+objNumber+'EmailAmrita').value == $('ref'+sId+'EmailAmrita').value && $('ref'+objNumber+'EmailAmrita').value!=''){
	  $('ref'+objNumber+'EmailAmrita').value = '';
	  $('ref'+objNumber+'EmailAmrita_error').innerHTML = 'The Emails of references cannot be same';
	  $('ref'+objNumber+'EmailAmrita_error').parentNode.style.display = '';
	  return false;
      }
      else{
	  $('ref'+objNumber+'EmailAmrita_error').innerHTML = '';
	  $('ref'+objNumber+'EmailAmrita_error').parentNode.style.display = 'none';
	  return true;
      }
  }

  function checkRefPhone(objNumber){
      if(objNumber==1) sId = 2; else sId = 1;
      if($('ref'+objNumber+'PhoneAmrita').value == $('ref'+sId+'PhoneAmrita').value && $('ref'+objNumber+'PhoneAmrita').value!=''){
	  $('ref'+objNumber+'PhoneAmrita').value = '';
	  $('ref'+objNumber+'PhoneAmrita_error').innerHTML = 'The Phone number of references cannot be same';
	  $('ref'+objNumber+'PhoneAmrita_error').parentNode.style.display = '';
	  return false;
      }
      else{
	  $('ref'+objNumber+'PhoneAmrita_error').innerHTML = '';
	  $('ref'+objNumber+'PhoneAmrita_error').parentNode.style.display = 'none';
	  return true;
      }
  }

  </script>

  <script>
  function addAchievements(){
      if($('achievements2').style.display == 'none'){
	$('achievements2').style.display = '';
      }
      else if($('achievements3').style.display == 'none'){
	$('achievements3').style.display = '';
      }
      else if($('achievements4').style.display == 'none'){
	$('achievements4').style.display = '';
	$('achievementsAdd').style.display = 'none';
      }
  }
  
  function addCurricular(){
      if($('curricular2').style.display == 'none'){
	$('curricular2').style.display = '';
      }
      else if($('curricular3').style.display == 'none'){
	$('curricular3').style.display = '';
	$('curricularAdd').style.display = 'none';
      }
  }
  
  if($('gradYear3DivisionAmrita').value!=''){
      $('extra2Details').style.display = '';
  }
  if($('pref1Amrita').value!=''){
      $('extra1Details').style.display = '';
  }
  
  </script>
