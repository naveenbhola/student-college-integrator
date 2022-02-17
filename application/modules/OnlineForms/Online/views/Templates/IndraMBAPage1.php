<script>
  function setGDPIDate(obj){
      var selectedPref = obj.options[obj.selectedIndex].value;

      var elSel = document.getElementById('gdpiDateIndra');
      elSel.options.length = 0;

      if(selectedPref=='174'){	//Pune is selected
	    if(navigator.appName=="Microsoft Internet Explorer"){
		  addOption('Select','');
		  addOption('28-November-2014','2014-11-28');
		  addOption('12-December-2014','2014-12-12');
		  addOption('26-December-2014','2014-12-26');
		  addOption('09-January-2015','2015-01-09');
		  addOption('23-January-2015','2015-01-23');	
		  addOption('13-February-2015','2015-02-13');
		  addOption('27-February-2015','2015-02-27');
		  addOption('13-March-2015','2015-03-13');
		  addOption('27-March-2015','2015-03-27');
		  addOption('19-April-2015','2015-04-19');
	    }
	    else{
		  addOption('19-April-2015','2015-04-19');
		  addOption('13-March-2015','2015-03-13');
		  addOption('27-March-2015','2015-03-27');
		  addOption('13-February-2015','2015-02-13');
		  addOption('27-February-2015','2015-02-27');
		  addOption('23-January-2015','2015-01-23');
		  addOption('09-January-2015','2015-01-09');
		  addOption('26-December-2014','2014-12-26');
		  addOption('12-December-2014','2014-12-12');
		  addOption('28-November-2014','2014-11-28');
		  addOption('Select','');
	    }
      }
      else if(selectedPref=='30' || selectedPref=='106' || selectedPref=='109' || selectedPref=='116' || selectedPref=='130' || selectedPref=='138' || selectedPref=='156' || selectedPref=='171' || selectedPref=='176' || selectedPref=='209' || selectedPref=='74'){
	    if(navigator.appName=="Microsoft Internet Explorer"){
		  addOption('Select','');
		  addOption('12-April-2015','2015-04-12');
	    }
	    else{
		  addOption('12-April-2015','2015-04-12');
		  addOption('Select','');
	    }
      }
      else{
	    if(navigator.appName=="Microsoft Internet Explorer"){
		  addOption('Select','');
		  addOption('11-April-2015','2015-04-11');
	    }
	    else{
		  addOption('11-April-2015','2015-04-11');
		  addOption('Select','');
	    }
      }

      //After adding the options, select the Default one
      var selObj = document.getElementById("gdpiDateIndra"); 
      var A= selObj.options, L= A.length;
      while(L){
	  if (A[--L].value== ""){
	      selObj.selectedIndex= L;
	      L= 0;
	  }
      }
      
      //Also clear the GDPI Date error message
      $('gdpiDateIndra_error').innerHTML = '';
      $('gdpiDateIndra_error').parentNode.style.display = 'none';
  }

  function addOption(text,value){
	    var elSel = document.getElementById('gdpiDateIndra');
	    var elOptNew = document.createElement('option');
	    elOptNew.text = text;
	    elOptNew.value = value;
	    var elOptOld = elSel.options[0];  
	    try {
	      elSel.add(elOptNew, elOptOld); // standards compliant; doesn't work in IE
	    }
	    catch(ex) {
	      elSel.add(elOptNew); // IE only
	    }
  }
  
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional",key+"RankAdditional",key+"PercentileAdditional",key+"PercentileIndra");
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
			<li>
				<h3 class="upperCase">Additional Personal Details</h3>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select name='categoryIndra' id='categoryIndra' required="true"  validate="validateSelect" caption="category"  minlength="1" maxlength="1500"      >
				    <option value='' selected>Select</option><option value='Open/General'>Open/General</option><option value='Reserved' >Reserved</option>
				</select>
				<?php if(isset($categoryIndra) && $categoryIndra!=""){ ?>
			      <script>
				  var selObj = document.getElementById("categoryIndra"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $categoryIndra;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'categoryIndra_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif; ?>
 			
			
			
			<li>
				<h3 class="upperCase">TESTS</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Indra_testNames[]' id='Indra_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Indra_testNames[]' id='Indra_testNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Indra_testNames[]' id='Indra_testNames2'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Indra_testNames[]' id='Indra_testNames3'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Indra_testNames[]' id='Indra_testNames4'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<?php if(isset($Indra_testNames) && $Indra_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Indra_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Indra_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Indra_testNames_error'></div></div>
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
			

			<li id="xat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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
			

			<li id="atma1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>ATMA REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'   tip="Mention your Registration number for the exam."    validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"     value=''   />
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
				<input type='text' name='atmaDateOfExaminationAdditional' id='atmaDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('atmaDateOfExaminationAdditional'),'atmaDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
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
				   <input class="textboxSmaller"  type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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

			<li id="cmat1" style="display:none;">
			    <div class='additionalInfoLeftCol'>
			    <label>CMAT REGN NO: </label>
			    <div class='fieldBoxLarge'>
			    <input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
			    <input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly minlength='1' maxlength='10'  validate="validateDateForms"         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Choose the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" caption='date' />
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

			<li id='cmat2' style='display:none;border-bottom:1px dotted #c0c0c0; padding-bottom:15px'>
	    
			    <div class='additionalInfoLeftCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				    <input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
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
				    <input type='text' name='cmatPercentileIndra' id='cmatPercentileIndra'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
				    <?php if(isset($cmatPercentileIndra) && $cmatPercentileIndra!=""){ ?>
				    <script>
					document.getElementById("cmatPercentileIndra").value = "<?php echo str_replace("\n", '\n', $cmatPercentileIndra );  ?>";
					document.getElementById("cmatPercentileIndra").style.color = "";
				    </script>
				    <?php } ?>
	    
				    <div style='display:none'><div class='errorMsg' id= 'cmatPercentileIndra_error'></div></div>
				</div>
			    </div>
	    
			</li>
			
			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
				<input type='text' name='matDateOfExaminationAdditional' id='matDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('matDateOfExaminationAdditional'),'matDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<input type='text' name='matScoreAdditional' id='matScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($matScoreAdditional) && $matScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("matScoreAdditional").value = "<?php echo str_replace("\n", '\n', $matScoreAdditional );  ?>";
				      document.getElementById("matScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'matScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  />
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
			
			
			
			
			
						<?php if($action != 'updateScore'):?>


			<li>
				<h3 class="upperCase">Education (additional information about your education)</h3>
				<div class='additionalInfoLeftCol'>
				<label>Qualifying Degree Status: </label>
				<div class='fieldBoxLarge'>
				<select style="width:107px;" name='qualifyDegreeIndra' id='qualifyDegreeIndra' required="true"  validate="validateSelect"  minlength="1" maxlength="1500" caption="qualifying degree" tip="Please mention the status of your qualifying degree. If you've passed the examination, select Passed."  onmouseover="showTipOnline('Please mention the status of your qualifying degree. If you've passed the examination, select Passed.',this);"  onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='Appeared'>Appeared</option><option value='Passes' >Passed</option></select>
				<?php if(isset($qualifyDegreeIndra) && $qualifyDegreeIndra!=""){ ?>
			      <script>
				  var selObj = document.getElementById("qualifyDegreeIndra"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $qualifyDegreeIndra;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qualifyDegreeIndra_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Highest Qualification: </label>
				<div class='fieldBoxLarge'>
				<select name='highestQIndra' id='highestQIndra' required="true"  validate="validateSelect"  minlength="1" maxlength="1500" caption="highest qualification"    tip="Select the highest qualification that you have attained."       onmouseover="showTipOnline('Select the highest qualification that you have attained.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='Appeared for bachelor degree'>Appeared for bachelor degree</option><option value='Bachelor Degree' >Bachelor Degree</option><option value='Master Degree' >Master Degree</option><option value='Professional' >Professional</option><option value='Doctorate' >Doctorate</option></select>
				<?php if(isset($highestQIndra) && $highestQIndra!=""){ ?>
			      <script>
				  var selObj = document.getElementById("highestQIndra"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $highestQIndra;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'highestQIndra_error'></div></div>
				</div>
				</div>

			</li>

			<script>
			  var js_course_percentage_array = new Array();
			  var js_course_university_array = new Array();
			</script>
			
			<?php
			// Find out graduation course name, if available
			$graduationCourseName = 'Graduation';
			$graduationYear = '';
			$otherCourses = array();
			$otherCoursePercentage = array();
			$otherCourseUniversity = array();
			
			if(is_array($educationDetails)) {
				//Get names of all the courses added by user
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						if($educationDetail['fieldName'] == 'graduationExaminationName') {
							$graduationCourseName = $educationDetail['value'];
						}
						else {
							for($i=1;$i<=4;$i++) {
								if($educationDetail['fieldName'] == 'graduationExaminationName_mul_'.$i) {
									$otherCourses[$i] = $educationDetail['value'];
								}
							}
						}
					}
				}
				//Now, get the University name and percentage of all the courses added by user
				foreach($educationDetails as $educationDetail) {
					if($educationDetail['value']) {
						if($educationDetail['fieldName'] == 'graduationBoard') { ?>
							<script>js_course_university_array['<?=$graduationCourseName?>'] = "<?=$educationDetail['value']?>";</script>
						<?php
						}
						else if($educationDetail['fieldName'] == 'graduationPercentage') { ?>
							<script>js_course_percentage_array['<?=$graduationCourseName?>'] = "<?=$educationDetail['value']?>";</script>
						<?php
						}
						else {
							for($i=1;$i<=4;$i++) {
								if($educationDetail['fieldName'] == 'graduationBoard_mul_'.$i) { ?>
								  <script>js_course_university_array['<?=$otherCourses[$i]?>'] = "<?=$educationDetail['value']?>";</script>
								<?php
								}
								else if($educationDetail['fieldName'] == 'graduationPercentage_mul_'.$i) { ?>
								  <script>js_course_percentage_array['<?=$otherCourses[$i]?>'] = "<?=$educationDetail['value']?>";</script>
								<?php
								}
							}
						}
					}
				}
			}
			?>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Highest Qualification Degree: </label>
				<div class='fieldBoxLarge'>
				<select style="width:107px;" onchange="changeDegreeInformation(this);" name='highestDegreeIndra' id='highestDegreeIndra' required="true"  validate="validateSelect"  minlength="1" maxlength="1500" caption="highest qualification degree"    tip="Select the highest qualification degree that you have attained."       onmouseover="showTipOnline('Select the highest qualification degree that you have attained.',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option>
				    <option value='<?=$graduationCourseName?>'><?=$graduationCourseName?></option>
				    <?php
					for ($i=1;$i<=4;$i++){
					  if(isset($otherCourses[$i]) && $otherCourses[$i]!=''){
					    echo "<option value='".$otherCourses[$i]."'>".$otherCourses[$i]."</option>";
					  }
					}
				    ?>
				</select>
				<?php if(isset($highestDegreeIndra) && $highestDegreeIndra!=""){ ?>
			      <script>
				  var selObj = document.getElementById("highestDegreeIndra"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $highestDegreeIndra;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'highestDegreeIndra_error'></div></div>
				</div>
				</div>

			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Aggregate % on Qualifying degree: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qualifyDegreePercentageIndra' id='qualifyDegreePercentageIndra'   tip="Enter the Aggregate percentage on Qualifying degree selected above."   value=''   validate="validateFloat" minlength="1" maxlength="5" caption="aggregate percentage"  required="true" />
				<?php if(isset($qualifyDegreePercentageIndra) && $qualifyDegreePercentageIndra!=""){ ?>
				  <script>
				      document.getElementById("qualifyDegreePercentageIndra").value = "<?php echo str_replace("\n", '\n', $qualifyDegreePercentageIndra);  ?>";
				      document.getElementById("qualifyDegreePercentageIndra").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qualifyDegreePercentageIndra_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>University Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='qualifyDegreeUniversityIndra' id='qualifyDegreeUniversityIndra'   tip="Enter the University Name of Qualifying degree selected above."   value=''   validate="validateStr" minlength="2" maxlength="150" caption="university name" required="true" />
				<?php if(isset($qualifyDegreeUniversityIndra) && $qualifyDegreeUniversityIndra!=""){ ?>
				  <script>
				      document.getElementById("qualifyDegreeUniversityIndra").value = "<?php echo str_replace("\n", '\n', $qualifyDegreeUniversityIndra);  ?>";
				      document.getElementById("qualifyDegreeUniversityIndra").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'qualifyDegreeUniversityIndra_error'></div></div>
				</div>
				</div>

			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Education Type: </label>
				<div class='fieldBoxLarge'>
				<select onchange="checkTypeICAP();" style="width:107px;" name='eduTypeIndra' id='eduTypeIndra' required="true"  validate="validateSelect" minlength="1" maxlength="1500" caption="education type"    tip="Select the education stream for your highest qualification."       onmouseover="showTipOnline('Select the education stream for your highest qualification.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Arts'>Arts</option><option value='Commerce' >Commerce</option><option value='Engineering' >Engineering</option><option value='Management' >Management</option><option value='Science' >Science</option><option value='Pharmacy' >Pharmacy</option><option value='Information Technology' >Information Technology</option><option value='Others' >Others</option>
				</select>
				<?php if(isset($eduTypeIndra) && $eduTypeIndra!=""){ ?>
			      <script>
				  var selObj = document.getElementById("eduTypeIndra"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $eduTypeIndra;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'eduTypeIndra_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Specify Others: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='otherEducationIndra' id='otherEducationIndra'        tip="Enter the specification for Other Education Type"   value=''   validate="validateStr" minlength="1" maxlength="50" caption="Other details"  disabled />
				<?php if(isset($otherEducationIndra) && $otherEducationIndra!=""){ ?>
				  <script>
				      document.getElementById("otherEducationIndra").disabled = false;
				      document.getElementById("otherEducationIndra").setAttribute('required','true');
				      document.getElementById("otherEducationIndra").value = "<?php echo str_replace("\n", '\n', $otherEducationIndra );  ?>";
				      document.getElementById("otherEducationIndra").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherEducationIndra_error'></div></div>
				</div>
				</div>

			</li>
		
			<li>
				<h3 class="upperCase">Test city details</h3>
			<?php if(is_array($gdpiLocations)): ?>
				<div class='additionalInfoLeftCol'>
				<label style="font-weight:normal">GD/PI City: </label>
				<div class='fieldBoxLarge'>
				<select onchange="setGDPIDate(this);" style="width:107px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="GD/PI City">
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
				<label>GD/PI Appearing Date: </label>
				<div class='fieldBoxLarge'>
				<select style="width:107px;" name='gdpiDateIndra' id='gdpiDateIndra'  required="true"  validate="validateSelect" caption="GDPI Date"  minlength="1" maxlength="1500"  tip="Please select the GD/PI appearing date"       onmouseover="showTipOnline('Please select the GD/PI appearing date',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option>
				</select>
				
				<div style='display:none'><div class='errorMsg' id= 'gdpiDateIndra_error'></div></div>
				</div>
				</div>
			</li>

			  <?php if(isset($gdpiDateIndra) && $gdpiDateIndra!=""){ ?>
			<script>
			    setGDPIDate(document.getElementById("preferredGDPILocation"));
			    var selObj = document.getElementById("gdpiDateIndra"); 
			    var A= selObj.options, L= A.length;
			    while(L){
				if (A[--L].value== "<?php echo $gdpiDateIndra;?>"){
				    selObj.selectedIndex= L;
				    L= 0;
				}
			    }
			</script>
		      <?php } ?>

			<li>
				<h3 class="upperCase">Other details</h3>
				<div class='additionalInfoLeftCol'>
				<label>How did you get to know about ICAP: </label>
				<div class='fieldBoxLarge'>
				<select onchange="checkKnowICAP();" style="width:107px;" name='knowIndra' id='knowIndra'  required="true"  validate="validateSelect" caption="an option"  minlength="1" maxlength="1500"  tip="Please select a source from where you heard about Indira"       onmouseover="showTipOnline('Please select a source from where you heard about Indira',this);" onmouseout="hidetip();" >
				    <option value='' selected>Select</option><option value='Shiksha.com'>Shiksha.com</option><option value='Friends'>Friends</option><option value='Magazines' >Magazines</option><option value='Newspaper' >Newspaper</option><option value='Radio' >Radio</option><option value='Hoarding' >Hoarding</option><option value='Website' >Website</option><option value='Other' >Other</option>
				</select>
				<?php if(isset($knowIndra) && $knowIndra!=""){ ?>
			      <script>
				  var selObj = document.getElementById("knowIndra"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $knowIndra;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'knowIndra_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>If Newspaper Specify: </label>
				<div class='fieldBoxLarge'>
				<input type='text' class="textboxLarge" name='newspaperDetailsIndra' id='newspaperDetailsIndra'         tip="Please enter the name of newspaper where you  got to know about Indira"   value='' disabled validate="validateStr" caption="newspaper details" minlength="2" maxlength="50" />
				<?php if(isset($newspaperDetailsIndra) && $newspaperDetailsIndra!=""){ ?>
				  <script>
				      document.getElementById("newspaperDetailsIndra").disabled = false;
				      document.getElementById("newspaperDetailsIndra").setAttribute('required','true');
				      document.getElementById("newspaperDetailsIndra").value = "<?php echo str_replace("\n", '\n', $newspaperDetailsIndra );  ?>";
				      document.getElementById("newspaperDetailsIndra").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'newspaperDetailsIndra_error'></div></div>
				</div>
				</div>
			</li>
			<?php endif; ?>

						<li>
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
				I hereby certify that the information furnished in the Application Form is complete, accurate and true. I have carefully read the contents of the Brochure.
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsIndra' id='agreeToTermsIndra' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsIndra) && $agreeToTermsIndra!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsIndra"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsIndra);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsIndra_error'></div></div>


				</div>
				</div>
			</li>
			

		</ul>
	</div>
</div>
			<?php endif; ?>

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
  for(var j=0; j<5; j++){
		checkTestScore(document.getElementById('Indra_testNames'+j));
	}
  
  
  function checkValidInstPreference(id){
	if(id==1){ sId = 2; tId=3;}
	else if(id==2){ sId = 1; tId = 3; }
	else {sId = 1; tId = 2;}
	var selectedPrefObj = document.getElementById('pref'+id+'Indra'); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById('pref'+sId+'Indra'); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	var selObj2 = document.getElementById('pref'+tId+'Indra'); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].value;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) ){
		$('pref'+id+'Indra'+'_error').innerHTML = 'Same preference can’t be set.';
		$('pref'+id+'Indra'+'_error').parentNode.style.display = '';
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
		$('pref'+id+'Indra'+'_error').innerHTML = '';
		$('pref'+id+'Indra'+'_error').parentNode.style.display = 'none';
	}
	return true;
  }

  function checkValidCoursePreference(id){
	if(id==1){ sId = 2; tId=3; fId=4; }
	else if(id==2){ sId = 1; tId = 3; fId=4; }
	else if(id==3){sId = 1; tId = 2;  fId=4; }
	else {sId = 1; tId = 2;  fId=3; }
	var selectedPrefObj = document.getElementById('coursePref'+id+'Indra'); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById('coursePref'+sId+'Indra'); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	var selObj2 = document.getElementById('coursePref'+tId+'Indra'); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].value;
	var selObj3 = document.getElementById('coursePref'+fId+'Indra'); 
	var selPref3 = selObj3.options[selObj3.selectedIndex].value;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) || (selectedPref == selPref3 && selectedPref!='' ) ){
		$('coursePref'+id+'Indra'+'_error').innerHTML = 'Same preference can’t be set.';
		$('coursePref'+id+'Indra'+'_error').parentNode.style.display = '';
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
		$('coursePref'+id+'Indra'+'_error').innerHTML = '';
		$('coursePref'+id+'Indra'+'_error').parentNode.style.display = 'none';
	}
	return true;
  }

  function checkKnowICAP(){
      var selectedPrefObj = document.getElementById('knowIndra'); 
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

  function checkTypeICAP(){
      var selectedPrefObj = document.getElementById('eduTypeIndra'); 
      var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
      if(selectedPref == 'Others'){
	  $('otherEducationIndra').disabled = false;
	  $('otherEducationIndra').setAttribute('required','true');
	  $('otherEducationIndra_error').innerHTML = '';
	  $('otherEducationIndra_error').parentNode.style.display = 'none';
      }
      else{
	  $('otherEducationIndra').value = '';
	  $('otherEducationIndra').disabled = true;
	  $('otherEducationIndra').removeAttribute('required');
	  $('otherEducationIndra_error').innerHTML = '';
	  $('otherEducationIndra_error').parentNode.style.display = 'none';
      }
  }

  
  
  
  function changeDegreeInformation(selectedPrefObj){
      var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
      //if(selectedPref>=0 && selectedPref<=4){
	$('qualifyDegreePercentageIndra').value = js_course_percentage_array[selectedPref];
	$('qualifyDegreePercentageIndra_error').innerHTML = '';
	$('qualifyDegreePercentageIndra_error').parentNode.style.display = 'none';

	$('qualifyDegreeUniversityIndra').value = js_course_university_array[selectedPref];
	$('qualifyDegreeUniversityIndra_error').innerHTML = '';
	$('qualifyDegreeUniversityIndra_error').parentNode.style.display = 'none';
      //}
  }
  </script>
