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
            document .getElementById(objectsArr[i]+'_error').innerHTML = '';
            document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
        }
    }
  }
  
</script><div class='formChildWrapper'>
	<div class='formSection'>
		<ul> 
			
			<li style="width:100%">
		<h3 class="upperCase">Examination Details:</h3>
		<div class='additionalInfoLeftCol' style="width:930px;">
		<label>TESTS: </label>
		<div class='fieldBoxLarge' style="width:590px;">
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='SHANTIBSCHOOL_testNames[]' id='SHANTIBSCHOOL_testNames0'   value='CAT'    onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='SHANTIBSCHOOL_testNames[]' id='SHANTIBSCHOOL_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
		
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='SHANTIBSCHOOL_testNames[]' id='SHANTIBSCHOOL_testNames2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;

                <input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='SHANTIBSCHOOL_testNames[]' id='SHANTIBSCHOOL_testNames3'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
		
		<input validate="validateCheckedGroup" required="true" caption="Exams" onClick="checkTestScore(this);" type='checkbox' name='SHANTIBSCHOOL_testNames[]' id='SHANTIBSCHOOL_testNames4'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" /><span  onmouseover="showTipOnline('Tick the appropriate box and provide the roll number, test date and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;		

		<?php if(isset($SHANTIBSCHOOL_testNames) && $SHANTIBSCHOOL_testNames!=""){ ?>
		<script>
		    objCheckBoxes = document.forms["OnlineForm"].elements["SHANTIBSCHOOL_testNames[]"];
		    var countCheckBoxes = objCheckBoxes.length;
		    for(var i = 0; i < countCheckBoxes; i++){
			      objCheckBoxes[i].checked = false;
			      <?php $arr = explode(",",$SHANTIBSCHOOL_testNames);
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
		
		<div style='display:none'><div class='errorMsg' id= 'SHANTIBSCHOOL_testNames_error'></div></div>
		</div>
		</div>
	</li>

	<li id="cat1" style="display:none;">

		<div class='additionalInfoLeftCol'>
		<label>CAT Date of Examination: </label>
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

		<div class='additionalInfoRightCol'>
		<label>CAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='catScoreAdditional' id='catScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"  allowNA="true"   value=''  />
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

	<li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>CAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
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
		<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'   validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"      tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  />
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
	    if(isset($SHANTIBSCHOOL_testNames) && $SHANTIBSCHOOL_testNames!="" && strpos($SHANTIBSCHOOL_testNames,'CAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('SHANTIBSCHOOL_testNames0'));
	    </script>
	<?php
	    }
	?>
	

	<li id="mat1" style="display:none;">
		<div class='additionalInfoLeftCol'>
		<label>MAT Date of Examination: </label>
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

		<div class='additionalInfoRightCol'>
		<label>MAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='matScoreAdditional' id='matScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''  allowNA="true" />
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

	<li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>MAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'     validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"   tip="Mention your Roll number for the exam."    value=''   />
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
		<label>MAT Percentile: </label>
		<div class='float_L'>
		   <input class="textboxSmaller"  type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  />
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
	    if(isset($SHANTIBSCHOOL_testNames) && $SHANTIBSCHOOL_testNames!=""){ 
		    $tests = explode(',',$SHANTIBSCHOOL_testNames);
		    foreach ($tests as $test){
			  if($test=='MAT'){
	    ?>
	    <script>
		    checkTestScore(document.getElementById('SHANTIBSCHOOL_testNames1'));
	    </script>
	<?php }
	      }
	    }
	?>


	

	<li id="xat1" style="display:none;">
		<div class='additionalInfoLeftCol'>
		<label>XAT Date of Examination: </label>
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

		<div class='additionalInfoRightCol'>
		<label>XAT Score: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''  allowNA="true" />
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

	<li id="xat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>XAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   tip="Mention your Roll number for the exam."    validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"     value=''   />
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
		<label>XAT Percentile: </label>
		<div class='float_L'>
		   <input class="textboxSmaller"  type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateFloat" allowNA="true"  caption="Percentile"   minlength="1"   maxlength="5"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''  />
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
	    if(isset($SHANTIBSCHOOL_testNames) && $SHANTIBSCHOOL_testNames!="" && strpos($SHANTIBSCHOOL_testNames,'XAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('SHANTIBSCHOOL_testNames2'));
	    </script>
	<?php
	    }
	?>



    <li id="cmat1" style="display:none;">

		<div class='additionalInfoLeftCol'>
		<label>CMAT Date of Examination: </label>
		<div class='fieldBoxLarge'>
		<input type='text' name='cmatDateOfExaminationAdditional' id='cmatDateOfExaminationAdditional' readonly maxlength='10'    validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('cmatDateOfExaminationAdditional'),'cmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
		<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"  allowNA="true"   value=''  />
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

	<li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
		<div class='additionalInfoLeftCol'>
		<label>CMAT Roll No.: </label>
		<div class='fieldBoxLarge'>
		<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
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
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your Rank in the exam. If you don't know your Rank, enter <b>NA.</b>"   value=''   allowNA='true' />
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
	    if(isset($SHANTIBSCHOOL_testNames) && $SHANTIBSCHOOL_testNames!="" && strpos($SHANTIBSCHOOL_testNames,'CMAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('SHANTIBSCHOOL_testNames3'));
	    </script>
	<?php
	    }
	?>
	<li id="gmat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatDateOfExaminationAdditional' id='gmatDateOfExaminationAdditional' readonly maxlength='10'  validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('gmatDateOfExaminationAdditional'),'gmatDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''    allowNA = 'true' />
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
	    if(isset($SHANTIBSCHOOL_testNames) && $SHANTIBSCHOOL_testNames!="" && strpos($SHANTIBSCHOOL_testNames,'GMAT')!==false){ ?>
	    <script>
		    checkTestScore(document.getElementById('SHANTIBSCHOOL_testNames4'));
	    </script>
	<?php
	    }
	?>
			</li>
			<?php if($action != 'updateScore'):?>			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>Centres for group discussion and personal interview</h3>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
			</li>
			<?php endif; ?><?php endif; ?>	
			<?php if($action != 'updateScore'):?>
				<li>
				
			<h3 class="upperCase">DISCLAIMER</h3>
            	<div class="additionalInfoLeftCol" style="width:950px">
			<label style="font-weight:normal; padding-top:0">I hereby declare that:&nbsp;</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I certify that all the information furnished by me is correct and to the best of my knowledge. I agree to abide by all the rule & regulations specified by the institute from time to time.
				<div class="spacer10 clearFix"></div>
				<div>
				<input type='checkbox' name='SHANTIBSCHOOL_agreeToTerms' id='SHANTIBSCHOOL_agreeToTerms' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above

			      <?php if(isset($SHANTIBSCHOOL_agreeToTerms) && $SHANTIBSCHOOL_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["SHANTIBSCHOOL_agreeToTerms"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$SHANTIBSCHOOL_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'SHANTIBSCHOOL_agreeToTerms_error'></div></div>


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
  
   for(var j=0; j<1; j++){
   
        checkTestScore(document.getElementById('SBS_testNames'+j)); 
    } 
  </script>
