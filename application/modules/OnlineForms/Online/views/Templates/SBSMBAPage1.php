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
			
			<?php if($action != 'updateScore'):?>			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
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
			
			<li>
				<h3 class="upperCase">RECENT COMPETITIVE EXAMINATION(S) RESULTS</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:500px">
		
			        <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames0'   value='MHCET'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MHCET</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames1'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                                <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames2'   value='CAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                                 <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames3'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                                <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames4'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                                <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames5'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
                                 
                                 <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='SBS_testNames[]' id='SBS_testNames6'   value='JMET'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >JMET</span>&nbsp;&nbsp;
		      
				
				<?php if(isset($SBS_testNames) && $SBS_testNames!=""){?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["SBS_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$SBS_testNames);

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
				
				<div style='display:none'><div class='errorMsg' id= 'SBS_testNames_error'></div></div>
				</div>
				</div>
			</li>

                           
                        <li id="mhcet1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MHCET REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='mhcetRollNumberAdditional' id='mhcetRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
				<?php if(isset($mhcetRollNumberAdditional) && $mhcetRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("mhcetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetRollNumberAdditional );  ?>";
				      document.getElementById("mhcetRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>MHCET Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mhcetDateOfExaminationAdditional' id='mhcetDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('mhcetDateOfExaminationAdditional'),'mhcetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='mhcetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('mhcetDateOfExaminationAdditional'),'mhcetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($mhcetDateOfExaminationAdditional) && $mhcetDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("mhcetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetDateOfExaminationAdditional );  ?>";
				      document.getElementById("mhcetDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
				
			</li>

			<li id="mhcet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>MHCET score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mhcetScoreAdditional' id='mhcetScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($mhcetScoreAdditional) && $mhcetScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("mhcetScoreAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetScoreAdditional );  ?>";
				      document.getElementById("mhcetScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'mhcetScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>MHCET Rank: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='mhcetRankAdditional' id='mhcetRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your Rank in the exam.</b>"   value=''   />
				<?php if(isset($mhcetRankAdditional) && $mhcetRankAdditional!=""){ ?>
				<script>
					document.getElementById("mhcetRankAdditional").value = "<?php echo str_replace("\n", '\n', $mhcetRankAdditional );  ?>";
					document.getElementById("mhcetRankAdditional").style.color = "";
				</script>
				<?php } ?>

				<div style='display:none'><div class='errorMsg' id= 'mhcetRankAdditional_error'></div></div>
				</div>
				</div>
			</li>	



                         <li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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
				<input type='text' name='catDateOfExaminationAdditional' id='catDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('catDateOfExaminationAdditional'),'catDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
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
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
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
				<input type='text' name='catPercentileAdditional' id='catPercentileAdditional'  validate="validateInteger"    caption="Percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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

		
		
		
			<li id="cmat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CMAT REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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

			<li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
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
				<input type='text' name='cmatRankAdditional' id='cmatRankAdditional'  validate="validateInteger"    caption="Rank"   minlength="1"   maxlength="7"     tip="Mention your rank in the exam. </b>"   value=''   />
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
				
				<div class='additionalInfoRightCol'>
				<label>MAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='matPercentileAdditional' id='matPercentileAdditional'  validate="validateInteger"    caption="Percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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

                        <li id="jmet1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>JMET REGN NO: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='jmetRollNumberAdditional' id='jmetRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
				<?php if(isset($jmetRollNumberAdditional) && $jmetRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("jmetRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $jmetRollNumberAdditional );  ?>";
				      document.getElementById("jmetRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jmetRollNumberAdditional_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>JMET Date: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='jmetDateOfExaminationAdditional' id='jmetDateOfExaminationAdditional' readonly maxlength='10'     validate="validateDateForms"  caption="date"     tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jmettDateOfExaminationAdditional'),'jmetDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='jmetDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jmetDateOfExaminationAdditional'),'jmetDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($jmetDateOfExaminationAdditional) && $jmetDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("jmetDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $jmetDateOfExaminationAdditional );  ?>";
				      document.getElementById("jmetDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jmetDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
				
			</li>

			<li id="jmet2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
				<div class='additionalInfoLeftCol'>
				<label>JMET score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='jmetScoreAdditional' id='jmetScoreAdditional'  validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."   value=''  allowNA="true" />
				<?php if(isset($jmetScoreAdditional) && $jmetScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("jmetScoreAdditional").value = "<?php echo str_replace("\n", '\n', $jmetScoreAdditional );  ?>";
				      document.getElementById("jmettScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'jmetScoreAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>JMET Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='jmetPercentileAdditional' id='jmetPercentileAdditional'  validate="validateInteger"    caption="Percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
				<?php if(isset($jmetPercentileAdditional) && $jmetPercentileAdditional!=""){ ?>
				<script>
					document.getElementById("jmetPercentileAdditional").value = "<?php echo str_replace("\n", '\n', $jmetPercentileAdditional );  ?>";
					document.getElementById("jmetPercentileAdditional").style.color = "";
				</script>
				<?php } ?>

				<div style='display:none'><div class='errorMsg' id= 'jmetPercentileAdditional_error'></div></div>
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
				
				<div class='additionalInfoRightCol'>
				<label>XAT Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'  validate="validateInteger"    caption="Percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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
				<input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'     validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"   tip="Mention your Registration number for the exam."    value=''   />
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
				
				<div class='additionalInfoRightCol'>
				<label>ATMA Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'  validate="validateInteger"    caption="Percentile"   minlength="1"   maxlength="7"     tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA.</b>"   value=''   allowNA='true' />
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

			<?php if($action != 'updateScore'):?>
		
			<li>	
			<h3 class="upperCase">DISCLAIMER</h3>
			<label style="font-weight:normal; padding-top:0">I hereby declare that:&nbsp;</label>
		    <div class="fieldBoxLarge" style="width:620px; color:#666666; font-style:italic">
		    
			
<ol class="decimal-items">
<li>I have read all the rules of Admission and I have consulted my parents / 
guardian and after understanding these rules, I have filled in the application for admission to the MBA 
Programme at MANAGEMENT INSITITUTES / SCHOOLS.</li>
<li>The information given by me in the application is true to the best of 
my knowledge and belief.</li>
<li>I have not been debarred from appearing at any examination held by 
any Government constituted or Statutory Authority in India or abroad.</li>
<li>I fully understand that admission once granted in particular 
category, will be final & I will not be entitled to have admission in any other category. Depending on my inter seat 
merit and availability of a seat my admission is subject to verification of my original testimonials for 
obtaining the eligibility.</li>
<li>I hold myself responsible for the dues and prompt payment of fees. I 
have noted that the fees may be refundable as per rules in case of cancellation.</li>
<li>I understand that no other document, other than those attached to the 
application form will be entertained for the purpose of claims/ concessions/weightages etc., if 
any in connection with my admission.</li>
<li>I hereby agree to abide by all rules, Acts and Laws in force by 
Government / University/ Institute/ Society and hereby undertake that so long as I am a student of the Institute I 
will do nothing either inside or outside the Institute which may result in disciplinary action against me 
under the rules, Acts and Laws.</li>
<li>I fully understand that the Director of the Institute where I would 
be admitted will have full liberty to expel me from the Institute for my infringement of the rules of conduct 
and discipline, attendance and the information given above.</li>
<li>I undertake to observe full attendance as per the University / Institute Rules, failing which I am aware that
my terms will not be granted.</li>
<li>I hereby agree that I have no objection in using my pictures in softcopy and hard print form during promotions 
of events &advertisements and institutes related activities.</li> 
<li>All disputes subject to Pune Jurisdiction only.</li>
</ol>
</div>
			</li>

				<li>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsSinhgad' id='agreeToTermsSinhgad'  value='1'  required="true" caption="Please agree to the terms stated above" validate="validateChecked"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsSinhgad) && $agreeToTermsSinhgad!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsSinhgad"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsSinhgad);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsSinhgad_error'></div></div>
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
  
   for(var j=0; j<7; j++){
   
        checkTestScore(document.getElementById('SBS_testNames'+j)); 
    } 
  </script>
