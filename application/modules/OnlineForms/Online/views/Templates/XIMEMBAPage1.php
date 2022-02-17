<script>
    function checkTestScore(obj){
        var key = obj.value.toLowerCase();
        if(obj.value == "CMAT"){
		
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileXIME",key+"RollNumberAdditional",key+"DateOfExaminationAdditional");
        }
	if(obj.value == "CAT" || obj.value == "MAT" || obj.value == "XAT"){
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileAdditional",key+"RollNumberAdditional",key+"DateOfExaminationAdditional");
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
		<?php if($action != 'updateScore'):?>
		<li>
			<h3 class="upperCase">Application For:</h3>
				<div class='additionalInfoLeftCol'>
				<div class='fieldBoxLarge' style="width:520px;">
				<input type='radio' name='appliedLocation' id='appliedLocation0'   value='bangalore'   checked  onmouseover="showTipOnline('Please select your desired XIME campus.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your desired XIME campus.',this);" onmouseout="hidetip();" >XIME, Bangalore only</span>&nbsp;&nbsp;
				<input type='radio' name='appliedLocation' id='appliedLocation1'   value='kochi'      onmouseover="showTipOnline('Please select your desired XIME campus.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your desired XIME campus.',this);" onmouseout="hidetip();" >XIME, Kochi only</span>&nbsp;&nbsp;
				<input type='radio' name='appliedLocation' id='appliedLocation2'   value='bothBK'     onmouseover="showTipOnline('Please select your desired XIME campus.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select your desired XIME campus.',this);" onmouseout="hidetip();" >EITHER</span>&nbsp;&nbsp;
				<?php if(isset($appliedLocation) && $appliedLocation!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["appliedLocation"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $appliedLocation;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'appliedLocation_error'></div></div>
				</div>
				</div>
			</li>
	    <?php endif; ?>
            <li>
            	<h3 class="upperCase">Qualifying Examinations:</h3>
                <div class='additionalInfoLeftCol' style="width:670px;">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:320px;">
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='XIMEKOCHI_testNames[]' id='XIMEKOCHI_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='XIMEKOCHI_testNames[]' id='XIMEKOCHI_testNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='XIMEKOCHI_testNames[]' id='XIMEKOCHI_testNames2'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='XIMEKOCHI_testNames[]' id='XIMEKOCHI_testNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                        <?php if(isset($XIMEKOCHI_testNames) && $XIMEKOCHI_testNames!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["XIMEKOCHI_testNames[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$XIMEKOCHI_testNames);
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

                        <div style='display:none'><div class='errorMsg' id= 'XIMEKOCHI_testNames_error'></div></div>
                    </div>
                </div>
            </li>

			
            <li id='cat1' style='display:none;'>
                <div class='additionalInfoLeftCol'>
                <label>CAT Score: </label>
                <div class='fieldBoxLarge'>
                <input type='text' name='catScoreAdditional' id='catScoreAdditional'     caption="score" minlength="1" maxlength="5" validate="validateFloat"      tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA="true"  />
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
                        <input type='text' name='catPercentileAdditional' id='catPercentileAdditional'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength='1' maxlength='5' validate='validateFloat' />
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

            <li id="cat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>CAT Registration No.: </label>
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
                <label>CAT Exam Date: </label>
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

            <?php
            if(isset($XIMEKOCHI_testNames) && $XIMEKOCHI_testNames!="" && strpos($XIMEKOCHI_testNames,'CAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('XIMEKOCHI_testNames0'));
            </script>
                <?php
            }
            ?>



            <li id='xat1' style="display:none;">
                <div class='additionalInfoLeftCol'>
                    <label>XAT Score: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='xatScoreAdditional' id='xatScoreAdditional'          tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
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
                        <input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'         tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''   allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat' />
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

            <li id="xat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>XAT Registration No.: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
                <label>XAT Exam Date: </label>
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

			<?php
            if(isset($XIMEKOCHI_testNames) && $XIMEKOCHI_testNames!="" && strpos($XIMEKOCHI_testNames,'XAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('XIMEKOCHI_testNames1'));
            </script>
                <?php
            }
            ?>

            <li id='mat1' style='display:none;'>

                <div class='additionalInfoLeftCol'>
                <label>MAT Score: </label>
                <div class='fieldBoxLarge'>
                <input type='text' name='matScoreAdditional' id='matScoreAdditional'           tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
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
                        <input type='text' name='matPercentileAdditional' id='matPercentileAdditional'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat' />
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

            <li id="mat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>MAT Registration No.: </label>
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

            <?php
            if(isset($XIMEKOCHI_testNames) && $XIMEKOCHI_testNames!=""){
                $tests = explode(',',$XIMEKOCHI_testNames);
                foreach ($tests as $test){
                    if($test=='MAT'){
                        ?>
                    <script>
                        checkTestScore(document.getElementById('XIMEKOCHI_testNames2'));
                    </script>
                        <?php }
                }
            }
            ?>


            

            <li id='cmat1' style='display:none;'>

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
                        <input type='text' name='cmatPercentileXIME' id='cmatPercentileXIME'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
                        <?php if(isset($cmatPercentileXIME) && $cmatPercentileXIME!=""){ ?>
                        <script>
                            document.getElementById("cmatPercentileXIME").value = "<?php echo str_replace("\n", '\n', $cmatPercentileXIME );  ?>";
                            document.getElementById("cmatPercentileXIME").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'cmatPercentileXIME_error'></div></div>
                    </div>
                </div>

            </li>

            <li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>CMAT Registration No.: </label>
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
                <label>CMAT Exam Date: </label>
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

            <?php
            if(isset($XIMEKOCHI_testNames) && $XIMEKOCHI_testNames!="" && strpos($XIMEKOCHI_testNames,'CMAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('XIMEKOCHI_testNames4'));
            </script>
                <?php
            }
			?>


			<?php if($action != 'updateScore'):?>
			<li>
            	<h3 class="upperCase">Personal Informations:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Age as on 1/7/2015: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ageXIME' id='ageXIME'  validate="validateOnlineFormAge"   required="true"   caption="age"   minlength="2"   maxlength="2"     tip="Enter your age as on 1 July 2014."   value=''  class="textboxSmall" />
				<?php if(isset($ageXIME) && $ageXIME!=""){ ?>
				  <script>
				      document.getElementById("ageXIME").value = "<?php echo str_replace("\n", '\n', $ageXIME );  ?>";
				      document.getElementById("ageXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Mother tongue: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='motherTongueXIME' id='motherTongueXIME'   required="true"        tip="Please enter your native language."   value=''  validate="validateStr" minlength="1" maxlength="50" caption="mother tongue"/>
				<?php if(isset($motherTongueXIME) && $motherTongueXIME!=""){ ?>
				  <script>
				      document.getElementById("motherTongueXIME").value = "<?php echo str_replace("\n", '\n', $motherTongueXIME );  ?>";
				      document.getElementById("motherTongueXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'motherTongueXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>NRI: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='nriXIME' id='nriXIME0'   value='Yes'     onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='nriXIME' id='nriXIME1'   value='No'  checked   onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select an option.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($nriXIME) && $nriXIME!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["nriXIME"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $nriXIME;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nriXIME_error'></div></div>
				</div>
				</div>
			</li>

			
			<li>
				<h3 class="upperCase">Academic Record (additional information):</h3>
				<div class='additionalInfoLeftCol'>
				<label>10th From Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10YearFromXIME' id='class10YearFromXIME'  validate="validateInteger"   required="true"   caption="10th year"   minlength="4"   maxlength="4"     tip="Enter the year when you started your 10th."   value='' />
				<?php if(isset($class10YearFromXIME) && $class10YearFromXIME!=""){ ?>
				  <script>
				      document.getElementById("class10YearFromXIME").value = "<?php echo str_replace("\n", '\n', $class10YearFromXIME );  ?>";
				      document.getElementById("class10YearFromXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10YearFromXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>10th Major Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10SubjectsXIME' id='class10SubjectsXIME'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="250"     tip="Enter the major subjects you studied in 10th."   value=''   />
				<?php if(isset($class10SubjectsXIME) && $class10SubjectsXIME!=""){ ?>
				  <script>
				      document.getElementById("class10SubjectsXIME").value = "<?php echo str_replace("\n", '\n', $class10SubjectsXIME );  ?>";
				      document.getElementById("class10SubjectsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10SubjectsXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>10th Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10TotalMarksXIME' id='class10TotalMarksXIME'  validate="validateFloat"   required="true"   caption="total marks"   minlength="2"   maxlength="6"     tip="Enter the total marks you obtained in 10th."   value='' />
				<?php if(isset($class10TotalMarksXIME) && $class10TotalMarksXIME!=""){ ?>
				  <script>
				      document.getElementById("class10TotalMarksXIME").value = "<?php echo str_replace("\n", '\n', $class10TotalMarksXIME );  ?>";
				      document.getElementById("class10TotalMarksXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10TotalMarksXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>10th Max. Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class10MaxMarksXIME' id='class10MaxMarksXIME'  validate="validateFloat"   required="true"   caption="maximum marks"   minlength="2"   maxlength="6"     tip="Enter the maximum marks of examinations in 10th."   value='' />
				<?php if(isset($class10MaxMarksXIME) && $class10MaxMarksXIME!=""){ ?>
				  <script>
				      document.getElementById("class10MaxMarksXIME").value = "<?php echo str_replace("\n", '\n', $class10MaxMarksXIME );  ?>";
				      document.getElementById("class10MaxMarksXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class10MaxMarksXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>12th From Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12YearFromXIME' id='class12YearFromXIME'  validate="validateInteger"   required="true"   caption="12th year"   minlength="4"   maxlength="4"     tip="Enter the year when you started your 12th."   value=''  />
				<?php if(isset($class12YearFromXIME) && $class12YearFromXIME!=""){ ?>
				  <script>
				      document.getElementById("class12YearFromXIME").value = "<?php echo str_replace("\n", '\n', $class12YearFromXIME );  ?>";
				      document.getElementById("class12YearFromXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12YearFromXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>12th Major Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12SubjectsXIME' id='class12SubjectsXIME'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="250"     tip="Enter the major subjects you studied in 12th."   value=''   />
				<?php if(isset($class12SubjectsXIME) && $class12SubjectsXIME!=""){ ?>
				  <script>
				      document.getElementById("class12SubjectsXIME").value = "<?php echo str_replace("\n", '\n', $class12SubjectsXIME );  ?>";
				      document.getElementById("class12SubjectsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12SubjectsXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>12th Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12TotalMarksXIME' id='class12TotalMarksXIME'  validate="validateFloat"   required="true"   caption="total marks"   minlength="2"   maxlength="6"     tip="Enter the total marks you obtained in 12th."   value=''  />
				<?php if(isset($class12TotalMarksXIME) && $class12TotalMarksXIME!=""){ ?>
				  <script>
				      document.getElementById("class12TotalMarksXIME").value = "<?php echo str_replace("\n", '\n', $class12TotalMarksXIME );  ?>";
				      document.getElementById("class12TotalMarksXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12TotalMarksXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>12th Max. Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='class12MaxMarksXIME' id='class12MaxMarksXIME'  validate="validateFloat"   required="true"   caption="maximum marks"   minlength="2"   maxlength="6"     tip="Enter the maximum marks of examinations in 12th."   value=''  />
				<?php if(isset($class12MaxMarksXIME) && $class12MaxMarksXIME!=""){ ?>
				  <script>
				      document.getElementById("class12MaxMarksXIME").value = "<?php echo str_replace("\n", '\n', $class12MaxMarksXIME );  ?>";
				      document.getElementById("class12MaxMarksXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'class12MaxMarksXIME_error'></div></div>
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
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> From Year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradYearFromXIME' id='gradYearFromXIME'  validate="validateInteger"   required="true"   caption="<?php echo $graduationCourseName;?> year"   minlength="4"   maxlength="4"     tip="Enter the year when you started your <?php echo $graduationCourseName;?>."   value='' />
				<?php if(isset($gradYearFromXIME) && $gradYearFromXIME!=""){ ?>
				  <script>
				      document.getElementById("gradYearFromXIME").value = "<?php echo str_replace("\n", '\n', $gradYearFromXIME );  ?>";
				      document.getElementById("gradYearFromXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradYearFromXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Major Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradSubjectsXIME' id='gradSubjectsXIME'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="250"     tip="Enter the major subjects you studied in <?php echo $graduationCourseName;?>."   value=''   />
				<?php if(isset($gradSubjectsXIME) && $gradSubjectsXIME!=""){ ?>
				  <script>
				      document.getElementById("gradSubjectsXIME").value = "<?php echo str_replace("\n", '\n', $gradSubjectsXIME );  ?>";
				      document.getElementById("gradSubjectsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradSubjectsXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $graduationCourseName;?> Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradTotalMarksXIME' id='gradTotalMarksXIME'  validate="validateFloat"   required="true"   caption="total marks"   minlength="2"   maxlength="6"     tip="Enter the total marks you obtained in <?php echo $graduationCourseName;?>. If you are awaiting the result, enter <b>NA</b>."   value='' allowNA="true" />
				<?php if(isset($gradTotalMarksXIME) && $gradTotalMarksXIME!=""){ ?>
				  <script>
				      document.getElementById("gradTotalMarksXIME").value = "<?php echo str_replace("\n", '\n', $gradTotalMarksXIME );  ?>";
				      document.getElementById("gradTotalMarksXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradTotalMarksXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $graduationCourseName;?> Max. Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gradMaxMarksXIME' id='gradMaxMarksXIME'  validate="validateFloat"   required="true"   caption="maximum marks"   minlength="2"   maxlength="6"     tip="Enter the maximum marks of examinations in <?php echo $graduationCourseName;?>."   value='' />
				<?php if(isset($gradMaxMarksXIME) && $gradMaxMarksXIME!=""){ ?>
				  <script>
				      document.getElementById("gradMaxMarksXIME").value = "<?php echo str_replace("\n", '\n', $gradMaxMarksXIME );  ?>";
				      document.getElementById("gradMaxMarksXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'gradMaxMarksXIME_error'></div></div>
				</div>
				</div>
			</li>

			<?php
			if(count($otherCourses)>0) {
				foreach($otherCourses as $otherCourseId => $otherCourseName) {
				
					$yearFrom = 'otherCourseYearFrom_mul_'.$otherCourseId;
					$yearFromVal = $$yearFrom;
					$oSubjects = 'otherCourseSubjects_mul_'.$otherCourseId;
					$oSubjectsVal = $$oSubjects;
					$oTotalMarks = 'otherCourseTotalMarks_mul_'.$otherCourseId;
					$oTotalMarksVal = $$oTotalMarks;
					$oMaxMarks = 'otherCourseMaxMarks_mul_'.$otherCourseId;
					$oMaxMarksVal = $$oMaxMarks;
					$pgCheck = 'otherCoursePGCheck_mul_'.$otherCourseId;
					$pgCheckVal = $$pgCheck;
					
			?>

			<li>
				<div class="additionalInfoLeftCol">
				    <label><?php echo $otherCourseName; ?> From Year:</label>
				    <div class='fieldBoxLarge'>
					  <input type='text' name='<?php echo $yearFrom; ?>' id='<?php echo $yearFrom; ?>'  validate="validateInteger"   required="true"   caption="<?php echo $otherCourseName; ?> year"   minlength="4" maxlength="4" tip="Enter the year when you started your <?php echo $otherCourseName;?>."   value='' />
					<?php if(isset($yearFromVal) && $yearFromVal!=""){ ?>
					<script>
					  document.getElementById("<?php echo $yearFrom; ?>").value = "<?php echo str_replace("\n", '\n', $yearFromVal );  ?>";
					  document.getElementById("<?php echo $yearFrom; ?>").style.color = "";
				      </script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= '<?php echo $yearFrom; ?>_error'></div></div>
				    </div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $otherCourseName;?> Major Subjects: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $oSubjects; ?>' id='<?php echo $oSubjects; ?>'  validate="validateStr"   required="true"   caption="subjects"   minlength="1"   maxlength="250"     tip="Enter the major subjects you studied in <?php echo $otherCourseName;?>."   value=''   />
				<?php if(isset($oSubjectsVal) && $oSubjectsVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $oSubjects; ?>").value = "<?php echo str_replace("\n", '\n', $oSubjectsVal );  ?>";
				      document.getElementById("<?php echo $oSubjects; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $oSubjects; ?>_error'></div></div>
				</div>
				</div>

			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label><?php echo $otherCourseName;?> Total Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $oTotalMarks; ?>' id='<?php echo $oTotalMarks; ?>'  validate="validateFloat"   required="true"   caption="total marks"   minlength="2"   maxlength="6"     tip="Enter the total marks you obtained in <?php echo $otherCourseName;?>."   value='' />
				<?php if(isset($oTotalMarksVal) && $oTotalMarksVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $oTotalMarks; ?>").value = "<?php echo str_replace("\n", '\n', $oTotalMarksVal );  ?>";
				      document.getElementById("<?php echo $oTotalMarks; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $oTotalMarks; ?>_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label><?php echo $otherCourseName;?> Max. Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='<?php echo $oMaxMarks; ?>' id='<?php echo $oMaxMarks; ?>'  validate="validateFloat"   required="true"   caption="maximum marks"   minlength="2"   maxlength="6"     tip="Enter the maximum marks of examinations in <?php echo $otherCourseName;?>."   value='' />
				<?php if(isset($oMaxMarksVal) && $oMaxMarksVal!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $oMaxMarks; ?>").value = "<?php echo str_replace("\n", '\n', $oMaxMarksVal );  ?>";
				      document.getElementById("<?php echo $oMaxMarks; ?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= '<?php echo $oMaxMarks; ?>_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Is <?php echo $otherCourseName;?> a PG Course: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='<?php echo $pgCheck; ?>' id='<?php echo $pgCheck; ?>'  tip="If this is a Post Graduate course of one or more years duration, please tick this check box."   value='1'  />

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
			</li>

			<?php
				}
			}
			?>



			<li>
				<h3 class="upperCase">Awards, Scholarships, and Significant Achievements (Be specific) - Optional:</h3>
		<!---here -->		<div class="semesterDetailsBox">
                <div class='additionalInfoLeftCol'>
				<label>School Level Academics: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='schoolAcademicsXIME' id='schoolAcademicsXIME'  validate="validateStr"    caption="academics"   minlength="0"   maxlength="500"     tip="Briefly explain your academic achievements in School. Mention only those achivements in which your rank was up to 2."   value=''   />
				<?php if(isset($schoolAcademicsXIME) && $schoolAcademicsXIME!=""){ ?>
				  <script>
				      document.getElementById("schoolAcademicsXIME").value = "<?php echo str_replace("\n", '\n', $schoolAcademicsXIME );  ?>";
				      document.getElementById("schoolAcademicsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'schoolAcademicsXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>School Level Sports/Games: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='schoolSportsXIME' id='schoolSportsXIME'  validate="validateStr"    caption="sports/games"   minlength="0"   maxlength="500"     tip="Briefly explain your sports achievements in School. Mention only those achivements in which your rank was up to 2."   value=''   />
				<?php if(isset($schoolSportsXIME) && $schoolSportsXIME!=""){ ?>
				  <script>
				      document.getElementById("schoolSportsXIME").value = "<?php echo str_replace("\n", '\n', $schoolSportsXIME );  ?>";
				      document.getElementById("schoolSportsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'schoolSportsXIME_error'></div></div>
				</div>
				</div>
                
                <div class="spacer15 clearFix"></div>
                <div class='additionalInfoLeftCol'>
				<label>School Level Cultural Events: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='schoolCulturalXIME' id='schoolCulturalXIME'  validate="validateStr"    caption="cultural events"   minlength="0"   maxlength="500"     tip="Briefly explain your cultural achievements in School. Mention only those achivements in which your rank was up to 2."   value=''   />
				<?php if(isset($schoolCulturalXIME) && $schoolCulturalXIME!=""){ ?>
				  <script>
				      document.getElementById("schoolCulturalXIME").value = "<?php echo str_replace("\n", '\n', $schoolCulturalXIME );  ?>";
				      document.getElementById("schoolCulturalXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'schoolCulturalXIME_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>College Level Academics: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='collegeAcademicsXIME' id='collegeAcademicsXIME'  validate="validateStr"    caption="academics"   minlength="0"   maxlength="500"     tip="Briefly explain your academic achievements in College. Mention only those achivements in which your rank was up to 2."   value=''   />
				<?php if(isset($collegeAcademicsXIME) && $collegeAcademicsXIME!=""){ ?>
				  <script>
				      document.getElementById("collegeAcademicsXIME").value = "<?php echo str_replace("\n", '\n', $collegeAcademicsXIME );  ?>";
				      document.getElementById("collegeAcademicsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'collegeAcademicsXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>College Level Sports/Games: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='collegeSportsXIME' id='collegeSportsXIME'  validate="validateStr"    caption="sports/games"   minlength="0"   maxlength="500"     tip="Briefly explain your sports achievements in College. Mention only those achivements in which your rank was up to 2."   value=''   />
				<?php if(isset($collegeSportsXIME) && $collegeSportsXIME!=""){ ?>
				  <script>
				      document.getElementById("collegeSportsXIME").value = "<?php echo str_replace("\n", '\n', $collegeSportsXIME );  ?>";
				      document.getElementById("collegeSportsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'collegeSportsXIME_error'></div></div>
				</div>
				</div>
                
                <div class="spacer15 clearFix"></div>
            	<div class='additionalInfoLeftCol'>
				<label>College Level Cultural Events: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='collegeCulturalXIME' id='collegeCulturalXIME'  validate="validateStr"    caption="cultural events"   minlength="0"   maxlength="500"     tip="Briefly explain your cultural achievements in College. Mention only those achivements in which your rank was up to 2."   value=''   />
				<?php if(isset($collegeCulturalXIME) && $collegeCulturalXIME!=""){ ?>
				  <script>
				      document.getElementById("collegeCulturalXIME").value = "<?php echo str_replace("\n", '\n', $collegeCulturalXIME );  ?>";
				      document.getElementById("collegeCulturalXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'collegeCulturalXIME_error'></div></div>
				</div>
				</div>    
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>University / State Level Academics: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='univAcademicsXIME' id='univAcademicsXIME'  validate="validateStr"    caption="academics"   minlength="0"   maxlength="500"     tip="Briefly explain your academic achievements in University / State level. Mention only those achivements in which your rank was up to 10."   value=''   />
				<?php if(isset($univAcademicsXIME) && $univAcademicsXIME!=""){ ?>
				  <script>
				      document.getElementById("univAcademicsXIME").value = "<?php echo str_replace("\n", '\n', $univAcademicsXIME );  ?>";
				      document.getElementById("univAcademicsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'univAcademicsXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>University / State Level Sports/Games: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='univSportsXIME' id='univSportsXIME'  validate="validateStr"    caption="sports/games"   minlength="0"   maxlength="500"     tip="Briefly explain your sports achievements in University / State level. Mention only those achivements in which your rank was up to 10."   value=''   />
				<?php if(isset($univSportsXIME) && $univSportsXIME!=""){ ?>
				  <script>
				      document.getElementById("univSportsXIME").value = "<?php echo str_replace("\n", '\n', $univSportsXIME );  ?>";
				      document.getElementById("univSportsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'univSportsXIME_error'></div></div>
				</div>
				</div>
                <div class="spacer5 clearFix" style="height:1px"></div>
                <div class='additionalInfoLeftCol'>
				<label>University / State Level Cultural Events: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='univCulturalXIME' id='univCulturalXIME'  validate="validateStr"    caption="cultural events"   minlength="0"   maxlength="500"     tip="Briefly explain your cultural achievements in University / State level. Mention only those achivements in which your rank was up to 10."   value=''   />
				<?php if(isset($univCulturalXIME) && $univCulturalXIME!=""){ ?>
				  <script>
				      document.getElementById("univCulturalXIME").value = "<?php echo str_replace("\n", '\n', $univCulturalXIME );  ?>";
				      document.getElementById("univCulturalXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'univCulturalXIME_error'></div></div>
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>National Level Academics: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='nationalAcademicsXIME' id='nationalAcademicsXIME'  validate="validateStr"    caption="academics"   minlength="0"   maxlength="500"     tip="Briefly explain your academic achievements in National level. Mention only those achivements in which your rank was up to 20."   value=''   />
				<?php if(isset($nationalAcademicsXIME) && $nationalAcademicsXIME!=""){ ?>
				  <script>
				      document.getElementById("nationalAcademicsXIME").value = "<?php echo str_replace("\n", '\n', $nationalAcademicsXIME );  ?>";
				      document.getElementById("nationalAcademicsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nationalAcademicsXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>National Level Sports/Games: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='nationalSportsXIME' id='nationalSportsXIME'  validate="validateStr"    caption="sports/games"   minlength="0"   maxlength="500"     tip="Briefly explain your sports achievements in National level. Mention only those achivements in which your rank was up to 20."   value=''   />
				<?php if(isset($nationalSportsXIME) && $nationalSportsXIME!=""){ ?>
				  <script>
				      document.getElementById("nationalSportsXIME").value = "<?php echo str_replace("\n", '\n', $nationalSportsXIME );  ?>";
				      document.getElementById("nationalSportsXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nationalSportsXIME_error'></div></div>
				</div>
				</div>
                <div class="spacer5 clearFix" style="height:1px"></div>
            	<div class='additionalInfoLeftCol'>
				<label>National Level Cultural Events: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='nationalCulturalXIME' id='nationalCulturalXIME'  validate="validateStr"    caption="cultural events"   minlength="0"   maxlength="500"     tip="Briefly explain your cultural achievements in National level. Mention only those achivements in which your rank was up to 20."   value=''   />
				<?php if(isset($nationalCulturalXIME) && $nationalCulturalXIME!=""){ ?>
				  <script>
				      document.getElementById("nationalCulturalXIME").value = "<?php echo str_replace("\n", '\n', $nationalCulturalXIME );  ?>";
				      document.getElementById("nationalCulturalXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nationalCulturalXIME_error'></div></div>
				</div>
				</div>    
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>NCC/NSS Participation: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='nccXIME' id='nccXIME0'   value='Yes'  onClick="nccParticipation('true');"    onmouseover="showTipOnline('Please select if you were a part of NCC or NSS.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you were a part of NCC or NSS.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='nccXIME' id='nccXIME1'   value='No'  onClick="nccParticipation('false');"  checked   onmouseover="showTipOnline('Please select if you were a part of NCC or NSS.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you were a part of NCC or NSS.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($nccXIME) && $nccXIME!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["nccXIME"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $nccXIME;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nccXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>If Yes, give details: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='nccDetailsXIME' id='nccDetailsXIME'  validate="validateStr"    caption="NCC or NSS details"   minlength="0"   maxlength="500"     tip="Please give details about your participation in NCC or NSS."   value='' disabled  />
				<?php if(isset($nccDetailsXIME) && $nccDetailsXIME!=""){ ?>
				  <script>
				      document.getElementById("nccDetailsXIME").disabled = false;
				      document.getElementById("nccDetailsXIME").value = "<?php echo str_replace("\n", '\n', $nccDetailsXIME );  ?>";
				      document.getElementById("nccDetailsXIME").style.color = "";
				      document.getElementById("nccXIME0").checked = true;
				      document.getElementById("nccXIME1").checked = false;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'nccDetailsXIME_error'></div></div>
				</div>
				</div>
                </div>
			</li>

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
			
			error_log(print_r($workCompanies,true));
			//_p("Coint : ".count($workCompanies));
			if(count($workCompanies) > 0) {
				$j = 0;
				foreach($workCompanies as $workCompanyKey => $rkCompany) {
					$grossSalaryFieldName = 'annualSalaryXIME'.$workCompanyKey;
					$grossSalaryFieldValue = $$grossSalaryFieldName;

					$reasonsFieldName = 'reasonsLeavingXIME'.$workCompanyKey;
					$reasonsFieldValue = $$reasonsFieldName;
					$natureWorkFieldName = 'natureWorkXIME'.$workCompanyKey;
					$natureWorkFieldValue = $$natureWorkFieldName;
			?>

			<li>
				<?php if($j == 0) { ?><h3 class="upperCase">Work experience (if applicable):</h3><?php $j = 1;} ?>
				<div class='additionalInfoLeftCol'>
				<label>Annual Salary at <?php echo $rkCompany; ?>: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='<?php echo $grossSalaryFieldName; ?>' id='<?php echo $grossSalaryFieldName; ?>' tip="Please enter your last drawn salary (if applicable)."  validate="validateFloat"  caption="salary"   minlength="2"   maxlength="10" value=''  />
						<?php if(isset($grossSalaryFieldValue) && $grossSalaryFieldValue!=""){ ?>
								<script>
									document.getElementById("<?php echo $grossSalaryFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $grossSalaryFieldValue );  ?>";
									document.getElementById("<?php echo $grossSalaryFieldName; ?>").style.color = "";
								</script>
								  <?php } ?>
						<div style='display:none'><div class='errorMsg' id= '<?php echo $grossSalaryFieldName; ?>_error'></div></div>
					</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Reasons for Leaving <?php echo $workCompany; ?>: </label>
					<div class='fieldBoxLarge'>
						<input type='text' name='<?php echo $reasonsFieldName; ?>' id='<?php echo $reasonsFieldName; ?>'  validate="validateStr"    caption="reasons for leaving"   minlength="2"   maxlength="500"     tip="Briefly explain why did you leave your last organization."   value=''   />
						<?php if(isset($reasonsFieldValue) && $reasonsFieldValue!=""){ ?>
						  <script>
						      document.getElementById("<?php echo $reasonsFieldName; ?>").value = "<?php echo str_replace("\n", '\n', $reasonsFieldValue );  ?>";
						      document.getElementById("<?php echo $reasonsFieldName; ?>").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= '<?php echo $reasonsFieldName; ?>_error'></div></div>
					</div>
				</div>

				<div class='additionalInfoLeftCol' style="width:100%;margin-top:20px;">
				<label>Describe the nature of work and responsibilities associated with your most recent job: </label>
				<div class='fieldBoxLarge'>
				<textarea name='<?php echo $natureWorkFieldName;?>' id='<?php echo $natureWorkFieldName;?>'  validate="validateStr"  caption="nature of work and responsibilities associated with your most recent job"   minlength="2"   maxlength="500"     tip="Describe the nature of work and responsibilities associated with your most recent job"   style="width:613px; height:74px" ></textarea>
				<?php if(isset($natureWorkFieldValue) && $natureWorkFieldValue!=""){ ?>
				  <script>
				      document.getElementById("<?php echo $natureWorkFieldName;?>").value = "<?php echo str_replace("\n", '\n', $natureWorkFieldValue );  ?>";
				      document.getElementById("<?php echo $natureWorkFieldName;?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= "<?php echo $natureWorkFieldName;?>_error"></div></div>
				</div>
				</div>
			</li>
		
			<?php
				}
			}
			?>



			<li>
				<h3 class="upperCase">General:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Explain why you have chosen to study at XIME.: </label>
				<div class='fieldBoxLarge'>
				<textarea name='whyChosenXIME' id='whyChosenXIME'  validate="validateStr"   required="true"   caption="reason why you have chosen to study at XIME"   minlength="2"   maxlength="500"     tip="Write a short essay explaining why do you want to join XIME?"   style="width:613px; height:74px" ></textarea>
				<?php if(isset($whyChosenXIME) && $whyChosenXIME!=""){ ?>
				  <script>
				      document.getElementById("whyChosenXIME").value = "<?php echo str_replace("\n", '\n', $whyChosenXIME );  ?>";
				      document.getElementById("whyChosenXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'whyChosenXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:922px">
				<label>How did you come to know about XIME ?: </label>
				<div class='fieldBoxLarge' style="width:605px">
				<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME7'   value='XIME Website' ></input><span>XIME Website</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME0'   value='Newspaper' ></input><span>Newspaper</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

				<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME2'   value='Coaching centre'    ></input><span >Coaching centre</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
              
		<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME6'   value='Other sources (specify)'  onClick="otherDetails(this);"  ></input><span >Other sources (specify)</span>&nbsp;&nbsp;
		  <div class="spacer10 clearFix"></div>				

				<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME4'   value='XIME Student'    ></input><span >XIME Student</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME5'   value='Alumni'    ></input><span >Alumni</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				<input type='checkbox' name='cameToKnowAboutXIME[]' id='cameToKnowAboutXIME3'   value='Internet' onClick="internetDetails(this);"   ></input><span >Internet(Mention Sites Visited)</span>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
				
				
				
				<?php if(isset($cameToKnowAboutXIME) && $cameToKnowAboutXIME!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["cameToKnowAboutXIME[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$cameToKnowAboutXIME);
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
				
				<div style='display:none'><div class='errorMsg' id= 'cameToKnowAboutXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Other Sources: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='otherDetailsXIME' id='otherDetailsXIME'  validate="validateDisplayName"   caption="other details"   minlength="2"   maxlength="50"     tip="Please specify from where did you hear about XIME."   value='' disabled  />
				<?php if(isset($otherDetailsXIME) && $otherDetailsXIME!=""){ ?>
				  <script>
				      document.getElementById("otherDetailsXIME").disabled = false;
				      document.getElementById("otherDetailsXIME").value = "<?php echo str_replace("\n", '\n', $otherDetailsXIME );  ?>";
				      document.getElementById("otherDetailsXIME").style.color = "";
				      document.getElementById("cameToKnowAboutXIME6").checked = true;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'otherDetailsXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Mention Sites Visited (Comma Separated Values): </label>
				
				<div class='fieldBoxLarge'>
				<input type='text' name='internetDetailsXIME' id='internetDetailsXIME'  validate="validateDisplayName"   caption="Sites Visited"   minlength="2"   maxlength="50"     tip="Please specify from where did you hear about XIME."   value='' disabled  />
				<?php if(isset($internetDetailsXIME) && $internetDetailsXIME!=""){ ?>
				  <script>
				      document.getElementById("internetDetailsXIME").disabled = false;
				      document.getElementById("internetDetailsXIME").value = "<?php echo str_replace("\n", '\n', $internetDetailsXIME );  ?>";
				      document.getElementById("internetDetailsXIME").style.color = "";
				      document.getElementById("cameToKnowAboutXIME3").checked = true;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'internetDetailsXIME_error'></div></div>
				</div>
				</div>
			</li>







<!--
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you ever applied to XIME before ?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='appliedXIME' id='appliedXIME0'   value='Yes'   onClick="appliedBefore('true');" ></input><span >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='appliedXIME' id='appliedXIME1'   value='No'   checked onClick="appliedBefore('false');" ></input><span >No</span>&nbsp;&nbsp;
				<?php if(isset($appliedXIME) && $appliedXIME!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["appliedXIME"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $appliedXIME;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'appliedXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>If Yes, mention year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='yearAppliedXIME' id='yearAppliedXIME'  validate="validateInteger"    caption="year"   minlength="4"   maxlength="4"     tip="Mention the year when you applied to XIME before."   value='' disabled class="textboxSmall"/>
				<?php if(isset($yearAppliedXIME) && $yearAppliedXIME!=""){ ?>
				  <script>
				      document.getElementById("yearAppliedXIME").disabled = false;
				      document.getElementById("yearAppliedXIME").value = "<?php echo str_replace("\n", '\n', $yearAppliedXIME );  ?>";
				      document.getElementById("yearAppliedXIME").style.color = "";
				      document.getElementById("appliedXIME0").checked = true;
				      document.getElementById("appliedXIME1").checked = false;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'yearAppliedXIME_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Have you applied to XIME Bangalore in 2013 ?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='XIMEKOCHI_AppliedToBanglore' id='XIMEKOCHI_AppliedToBanglore0'   value='Yes'   onClick="appliedBanglore('true');" ></input><span >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='XIMEKOCHI_AppliedToBanglore' id='XIMEKOCHI_AppliedToBanglore1'   value='No'   checked onClick="appliedBanglore('false');" ></input><span >No</span>&nbsp;&nbsp;
				<?php if(isset($XIMEKOCHI_AppliedToBanglore) && $XIMEKOCHI_AppliedToBanglore!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["XIMEKOCHI_AppliedToBanglore"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $XIMEKOCHI_AppliedToBanglore;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'XIMEKOCHI_AppliedToBanglore_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>If Yes, mention the application number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='XIMEKOCHI_BangloreAppNumber' id='XIMEKOCHI_BangloreAppNumber'  validate="validateStr"    caption="application number"   minlength="2"   maxlength="10"     tip="If you've also applied to XIME Bangalore in 2013 session, please enter your registration number"   value='' disabled class="textboxSmall"/>
				<?php if(isset($XIMEKOCHI_BangloreAppNumber) && $XIMEKOCHI_BangloreAppNumber!=""){ ?>
				  <script>
				      document.getElementById("XIMEKOCHI_BangloreAppNumber").disabled = false;
				      document.getElementById("XIMEKOCHI_BangloreAppNumber").value = "<?php echo str_replace("\n", '\n', $XIMEKOCHI_BangloreAppNumber );  ?>";
				      document.getElementById("XIMEKOCHI_BangloreAppNumber").style.color = "";
				      document.getElementById("XIMEKOCHI_AppliedToBanglore0").checked = true;
				      document.getElementById("XIMEKOCHI_AppliedToBanglore1").checked = false;
				  </script>
				<?php } ?>
				Cannot read property 'value' of null
				<div style='display:none'><div class='errorMsg' id= 'XIMEKOCHI_BangloreAppNumber_error'></div></div>
				</div>
				</div>
			</li>
-->
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
			<select style="width:100px" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
			<?php endif; ?>
			
			<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Do you have any existing medical conditions or concerns? </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='medicalconXIME' id='medicalconXIME0'   value='Yes'  onClick="hasMedicalConcerns('true');"    onmouseover="showTipOnline('Please select if you have any existing medical conditions or concerns.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you have any existing medical conditions or concerns.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='medicalconXIME' id='medicalconXIME1'   value='No'  onClick="hasMedicalConcerns('false');"  checked   onmouseover="showTipOnline('Please select if you dont have any existing medical conditions or concerns.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you dont have any existing medical conditions or concerns.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				
				
				<?php if(isset($medicalconXIME) && $medicalconXIME!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["medicalconXIME"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $medicalconXIME;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'medicalconXIME_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoLeftCol'>
				<label>If Yes, give details: </label>
				<div class='fieldBoxLarge'>
				
				<textarea name='medicalDetailsXIME' id='medicalDetailsXIME'  validate="validateStr"      caption="your medical conditions or concerns"   minlength="2" value='' disabled  maxlength="500"     tip="Write in short about your medical concerns."   style="width:613px; height:74px" ></textarea>
				
				
				
				
				
				<?php if(isset($medicalDetailsXIME) && $medicalDetailsXIME!=""){ ?>
				  <script>
				      document.getElementById("medicalDetailsXIME").disabled = false;
				      document.getElementById("medicalDetailsXIME").value = "<?php echo str_replace("\n", '\n', $medicalDetailsXIME );  ?>";
				      document.getElementById("medicalDetailsXIME").style.color = "";
				      document.getElementById("medicalconXIME0").checked = true;
				      document.getElementById("medicalconXIME1").checked = false;
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'medicalDetailsXIME_error'></div></div>
				</div>
				</div>
                </div>
			</li>


				<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Do you have any allergies? </label>
				<div class='fieldBoxLarge'>
				<input type='radio' name='allergiesXIME' id='allergiesXIME0'   value='Yes'  onClick="hasAllergy('true');"    onmouseover="showTipOnline('Please select if you have any allergies?.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you have any existing alleriges.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input type='radio' name='allergiesXIME' id='allergiesXIME1'   value='No'  onClick="hasAllergy('false');"  checked   onmouseover="showTipOnline('Please select if you dont have any allergies',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select if you dont have any allergies.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				
				
				<?php if(isset($allergiesXIME) && $allergiesXIME!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["allergiesXIME"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $allergiesXIME;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'allergiesXIME_error'></div></div> 
				</div>
				</div>

				<div class='additionalInfoLeftCol'>
				<label>If Yes, give details: </label>
				<div class='fieldBoxLarge'>
				
				<textarea name='allergiesDetailsXIME' id='allergiesDetailsXIME'  validate="validateStr"    caption="allergies"   minlength="2" value='' disabled  maxlength="500"     tip="Write in short about your allergies."   style="width:613px; height:74px" ></textarea>
				
				
				
				
				
				<?php if(isset($allergiesDetailsXIME) && $allergiesDetailsXIME!=""){ ?>
				  <script>
				      document.getElementById("allergiesDetailsXIME").disabled = false;
				      document.getElementById("allergiesDetailsXIME").value = "<?php echo str_replace("\n", '\n', $allergiesDetailsXIME);  ?>";
				      document.getElementById("allergiesDetailsXIME").style.color = "";
				      document.getElementById("allergiesDetailsXIME").checked = true;
				      document.getElementById("allergiesDetailsXIME").checked = false;
				  </script>
				<?php } ?>
			
				<div style='display:none'><div class='errorMsg' id= 'allergiesDetailsXIME_error'></div></div> 
				</div>
				</div>
                </div>
			</li>

			<li>
            	<div class="semesterDetailsBox">
				<div class='additionalInfoLeftCol'>
				<label>Mention any other special health or medical needs you have</label>
				<div class='fieldBoxLarge'>
				
				<textarea name='anyothermedicalDetailsXIME' id='anyothermedicalDetailsXIME'  validate="validateStr"      caption="other medical details"   minlength="2" value=''   maxlength="500"     tip="Write in short if you have any other medical details."   style="width:613px; height:74px" ></textarea>
				
				<?php if(isset($anyothermedicalDetailsXIME) && $anyothermedicalDetailsXIME!=""){ ?>
				  <script>
				      document.getElementById("anyothermedicalDetailsXIME").disabled = false;
				      document.getElementById("anyothermedicalDetailsXIME").value = "<?php echo str_replace("\n", '\n', $anyothermedicalDetailsXIME);  ?>";
				      document.getElementById("anyothermedicalDetailsXIME").style.color = "";
				      document.getElementById("anyothermedicalDetailsXIME").checked = true;
				      document.getElementById("anyothermedicalDetailsXIME").checked = false;
				  </script>
				<?php } ?>
				
				
				
				
				
				<div style='display:none'><div class='errorMsg' id= 'anyothermedicalDetailsXIME_error'></div></div> 

				</div>
				</div>
		
		</div>		
			</li>
			
			<li>
				<h3 class="upperCase">Address For Communication:</h3>
				<div class='additionalInfoLeftCol'>
				<label>Please write in capital letters the address to which communication should be sent: </label>
				<div class='fieldBoxLarge'>
				<textarea name='addressforcommXIME' id='addressforcommXIME'  validate="validateStr"   required="true"   caption="the address to which communication should be sent"   minlength="2"   maxlength="500"     tip="Enter your complete address for communication."   style="width:613px; height:74px" ></textarea>
				<?php if(isset($addressforcommXIME) && $addressforcommXIME!=""){ ?>
				  <script>
				      document.getElementById("addressforcommXIME").value = "<?php echo str_replace("\n", '\n', $addressforcommXIME );  ?>";
				      document.getElementById("addressforcommXIME").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'addressforcommXIME_error'></div></div>
				</div>
				</div>
			</li>

			
			
			
			
			
			
			
			
			
			`<li>
				<h3 class="upperCase">Declaration:</h3>
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
                I hereby declare that the particulars given in this application are true and correct and will be supported by original documents when required. I confirm that I have fully read the terms and conditions regarding the admission to XIME's PGDM programme and pursuit of the same including those relating to withdrawal from the programme after admission.                
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsXIME' id='agreeToTermsXIME' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsXIME) && $agreeToTermsXIME!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsXIME"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsXIME);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsXIME_error'></div></div>


				</div>
				</div>
			</li>

                <?php endif;?>
			
		</ul>
	</div>
</div>
<script>
getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
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

  <script>
  function appliedBefore(hasApplied){
      if(hasApplied=='true'){
	  $('yearAppliedXIME').disabled = false;
	  $('yearAppliedXIME').setAttribute('required','true');
      }
      else{
	  $('yearAppliedXIME').value = '';
	  $('yearAppliedXIME').disabled = true;
	  $('yearAppliedXIME').removeAttribute('required');
	  $('yearAppliedXIME_error').innerHTML = '';
	  $('yearAppliedXIME_error').parentNode.style.display = 'none';
      }
  }

  function appliedBanglore(hasApplied){
      if(hasApplied=='true'){
	  $('XIMEKOCHI_BangloreAppNumber').disabled = false;
	  $('XIMEKOCHI_BangloreAppNumber').setAttribute('required','true');
      }
      else{
	  $('XIMEKOCHI_BangloreAppNumber').value = '';
	  $('XIMEKOCHI_BangloreAppNumber').disabled = true;
	  $('XIMEKOCHI_BangloreAppNumber').removeAttribute('required');
	  $('XIMEKOCHI_BangloreAppNumber_error').innerHTML = '';
	  $('XIMEKOCHI_BangloreAppNumber_error').parentNode.style.display = 'none';
      }
  }

  function nccParticipation(hasApplied){
      if(hasApplied=='true'){
	  $('nccDetailsXIME').disabled = false;
	  $('nccDetailsXIME').setAttribute('required','true');
      }
      else{
	  $('nccDetailsXIME').value = '';
	  $('nccDetailsXIME').disabled = true;
	  $('nccDetailsXIME').removeAttribute('required');
	  $('nccDetailsXIME_error').innerHTML = '';
	  $('nccDetailsXIME_error').parentNode.style.display = 'none';
      }
  }

  
    function hasMedicalConcerns(hasApplied){
      if(hasApplied=='true'){
	  $('medicalDetailsXIME').disabled = false;
	  $('medicalDetailsXIME').setAttribute('required','true');
      }
      else{
	  $('medicalDetailsXIME').value = '';
	  $('medicalDetailsXIME').disabled = true;
	  $('medicalDetailsXIME').removeAttribute('required');
	  $('medicalDetailsXIME_error').innerHTML = '';
	  $('medicalDetailsXIME_error').parentNode.style.display = 'none';
      }
  }

  function hasAllergy(hasApplied){
      if(hasApplied=='true'){
	  $('allergiesDetailsXIME').disabled = false;
	  $('allergiesDetailsXIME').setAttribute('required','true');
      }
      else{
	  $('allergiesDetailsXIME').value = '';
	  $('allergiesDetailsXIME').disabled = true;
	  $('allergiesDetailsXIME').removeAttribute('required');
	  $('allergiesDetailsXIME_error').innerHTML = '';
	  $('allergiesDetailsXIME_error').parentNode.style.display = 'none';
      }s
  }

  
  
  
  
  
  
  
  function otherDetails(objId){
      if(objId.checked==true){
	  $('otherDetailsXIME').disabled = false;
	  $('otherDetailsXIME').setAttribute('required','true');
      }
      else{
	  $('otherDetailsXIME').value = '';
	  $('otherDetailsXIME').disabled = true;
	  $('otherDetailsXIME').removeAttribute('required');
	  $('otherDetailsXIME_error').innerHTML = '';
	  $('otherDetailsXIME_error').parentNode.style.display = 'none';
      }
  }

  function  internetDetails(objId) {
 if(objId.checked==true){
	  $('internetDetailsXIME').disabled = false;
	  $('internetDetailsXIME').setAttribute('required','true');
      }
      else{
	  $('internetDetailsXIME').value = '';
	  $('internetDetailsXIME').disabled = true;
	  $('internetDetailsXIME').removeAttribute('required');
	  $('internetDetailsXIME_error').innerHTML = '';
	  $('internetDetailsXIME_error').parentNode.style.display = 'none';
      }	
  }
  
  
  
  
  
  </script>
