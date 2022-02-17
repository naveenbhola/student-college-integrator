<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"ScoreAdditional",key+"RankAdditional",key+"PercentileAdditional");
	if(obj && $(key+"1")){
	      if( obj.checked == false ){
			$(key+"1").style.display = 'none';
			if($(key+"2"))
			$(key+"2").style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
			$(key+"1").style.display = '';
		       if($(key+"2"))
                        $(key+"2").style.display = '';
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
                        	<li>
				<h3 class="upperCase">TESTS:</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Examination Taken: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='testNamesJKBS[]' id='testNamesJKBS0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test percentile (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='testNamesJKBS[]' id='testNamesJKBS1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test percentile (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='testNamesJKBS[]' id='testNamesJKBS2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test percentile or score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				 <input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='testNamesJKBS[]' id='testNamesJKBS3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test percentile or score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;

			
                              <?php if(isset($testNamesJKBS) && $testNamesJKBS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["testNamesJKBS[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$testNamesJKBS);
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
				
				<div style='display:none'><div class='errorMsg' id= 'testNamesJKBS_error'></div></div>
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
				    <input type='text' name='cmatPercentileJKBS' id='cmatPercentileJKBS'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
				    <?php if(isset($cmatPercentileJKBS) && $cmatPercentileJKBS!=""){ ?>
				    <script>
					document.getElementById("cmatPercentileJKBS").value = "<?php echo str_replace("\n", '\n', $cmatPercentileJKBS );  ?>";
					document.getElementById("cmatPercentileJKBS").style.color = "";
				    </script>
				    <?php } ?>
	    
				    <div style='display:none'><div class='errorMsg' id= 'cmatPercentileJKBS_error'></div></div>
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
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
1.I hereby certify that the particulars furnished by me in the application form are correct.<br/>
2.I undertake that I will not associate/involve myself in any unlawful activity.<br/>
3.I undertake that I will neither smoke nor consume alcohol. Drugs or any other intoxicant, within Institute/hostel premises.<br/>
4.I certify that I have never been debarred from appearing in any examination.<br/>
5.I also declare that no police inquiry is pending against me.<br/>
6.I fully understand and undertake that I will attend all classes as per institute time table. I also understand that I will not be
allowed to appear in exam, in case my attendance falls below 80% in all subjects and activities. <br/>
7.I undertake that I will submit my final result before last date of admission failing which I will have no claim whatsoever and the institute may cancel my admission.<br/>
8.I also clearly understand that if I am found to be directly/indirectly involved in any case of ragging at any stage of my stay in the institute/Hostel, the institute will have the right to expel me from the institute and the hostel and register a criminal case against me, as per the direction of Honourable Supreme Court of India.<br/>
9.In case of dispute, the decision of Director shall be final and binding.<br/>
10.I have understood the rules and regulations of the institute and I shall abide by them.I also fully understand that in case of any violation, I can be detained in the trimester, rusticated, or expelled from the institute.<br/>
11.I shall understand that I shall pay all the due fees else, I have to pay penalty according to the rules of the institute. I also understand that the institute may charge me with fine for not attending classes, Guest Lectures and activities organised by the institute.
</div>

				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='JKBS_agreeToTerms[]' id='JKBS_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($JKBS_agreeToTerms) && $JKBS_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["JKBS_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$JKBS_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'JKBS_agreeToTerms0_error'></div></div>
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
        
           for(var j=0; j<4; j++){
		checkTestScore(document.getElementById('testNamesJKBS'+j));
	}
  </script>
                                                                                                                            


