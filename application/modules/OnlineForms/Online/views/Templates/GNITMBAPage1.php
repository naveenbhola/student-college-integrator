<script>
      function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	console.log(key); console.log(obj);
	var objects1 = new Array(key+"RollNumberAdditional",key+"PercentileAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional");
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


            <li>
                <h3 class="upperCase">Qualifying Examination</h3>
                <div class='additionalInfoLeftCol' style="width:800px">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:400px">
                        <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='GNIT_testNames[]' id='GNIT_testNames0'   value='CAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='GNIT_testNames[]' id='GNIT_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
			 <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='GNIT_testNames[]' id='GNIT_testNames2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
			  <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='GNIT_testNames[]' id='GNIT_testNames3'   value='UPSEE'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >UPSEE</span>&nbsp;&nbsp;
                        <?php if(isset($GNIT_testNames) && $GNIT_testNames!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["GNIT_testNames[]"]; 
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){  
                                objCheckBoxes[i].checked = false;

                                <?php  $arr = explode(",",$GNIT_testNames);
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

                        <div style='display:none'><div class='errorMsg' id= 'GNIT_testNames_error'></div></div>
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
				
				<div class='additionalInfoRightCol'>
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
				
				<div class='additionalInfoRightCol'>
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
				
				<div class='additionalInfoRightCol'>
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
				
			</li>
			
			<li id="upsee" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>UPSEE Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upseeRollNumberAdditional' id='upseeRollNumberAdditional'   required="true"         validate="validateStr"    caption="Roll Number"   minlength="2"   maxlength="50"     tip="Mention your roll number for the exam. If you have not appeared for this examination enter <b>NA</b>."   value=''  allowNA='true' />
				<?php if(isset($upseeRollNumberAdditional) && $upseeRollNumberAdditional!=""){ ?>
				  <script>
				      document.getElementById("upseeRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $upseeRollNumberAdditional );  ?>";
				      document.getElementById("upseeRollNumberAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upseeRollNumberAdditional_error'></div></div>
				</div>
				</div>
		
				<div class='additionalInfoRightCol'>
				<label>UPSEE Percentile: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upseePercentileAdditional' id='upseePercentileAdditional'     required="true"        validate="validateFloat"    caption="Percentile"   minlength="1"   maxlength="5"    tip="Mention your percentile in the exam. If you don't know your percentile, enter <b>NA</b>."   value=''  allowNA='true'  />
				<?php if(isset($upseePercentileAdditional) && $upseePercentileAdditional!=""){ ?>
				  <script>
				      document.getElementById("upseePercentileAdditional").value = "<?php echo str_replace("\n", '\n', $upseePercentileAdditional );  ?>";
				      document.getElementById("upseePercentileAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upseePercentileAdditional_error'></div></div>
				</div>
				</div>
			</li>

			<li id="upsee1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>UPSEE Date of Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upseeDateOfExaminationAdditional' id='upseeDateOfExaminationAdditional' readonly maxlength='10'      readonly maxlength='10'  validate="validateDateForms"  caption="date"      tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('upseeDateOfExaminationAdditional'),'upseeDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
				&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='upseeDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('upseeDateOfExaminationAdditional'),'upseeDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
				<?php if(isset($upseeDateOfExaminationAdditional) && $upseeDateOfExaminationAdditional!=""){ ?>
				  <script>
				      document.getElementById("upseeDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $upseeDateOfExaminationAdditional );  ?>";
				      document.getElementById("upseeDateOfExaminationAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upseeDateOfExaminationAdditional_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoRightCol'>
				<label>UPSEE Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='upseeScoreAdditional' id='upseeScoreAdditional'   validate="validateFloat"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA</b>."  allowNA="true"   value=''  />
				<?php if(isset($upseeScoreAdditional) && $upseeScoreAdditional!=""){ ?>
				  <script>
				      document.getElementById("upseeScoreAdditional").value = "<?php echo str_replace("\n", '\n', $upseeScoreAdditional );  ?>";
				      document.getElementById("upseeScoreAdditional").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'upseeScoreAdditional_error'></div></div>
				</div>
				</div>
				
			</li>


            <?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Centre Preference: </label>
				<div class='fieldBoxLarge'>
				<select style="width:100px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				    I hereby declare that I have read and understood all the rules and regulations of the Institute.I will fully
				    abide by the rules and regulations of the institute during the course of my studies, otherwise my admission
				    can be cancelled or any other disciplinary action can be taken against me.
				<div class='additionalInfoLeftCol' style="margin-left:-88px;">
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='agreeToTermsGnit[]' id='agreeToTermsGnit'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsGnit) && $agreeToTermsGnit!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsGnit[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$agreeToTermsGnit);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsGnit_error'></div></div>
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

	for(var j=0; j<4; j++){
		checkTestScore(document.getElementById('GNIT_testNames'+j));
	}

  </script>