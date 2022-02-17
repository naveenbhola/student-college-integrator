<script>
    function checkTestScore(obj){
        var key = obj.value.toLowerCase();
        if(obj.value == "CMAT"){
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileNIILM",key+"RollNumberAdditional");
        }else{
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileAdditional",key+"RollNumberAdditional");
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

            <li>
            	<h3 class="upperCase">Qualifying Examinations:</h3>
                <div class='additionalInfoLeftCol' style="width:670px;">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:320px;">
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='NIILM_testNames[]' id='NIILM_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='NIILM_testNames[]' id='NIILM_testNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='NIILM_testNames[]' id='NIILM_testNames2'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='NIILM_testNames[]' id='NIILM_testNames3'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='NIILM_testNames[]' id='NIILM_testNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile and roll number (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                        <?php if(isset($NIILM_testNames) && $NIILM_testNames!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["NIILM_testNames[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$NIILM_testNames);
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

                        <div style='display:none'><div class='errorMsg' id= 'NIILM_testNames_error'></div></div>
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
                <label>CAT Roll No.: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
                <?php if(isset($catRollNumberAdditional) && $catRollNumberAdditional!=""){ ?>
                  <script>
                      document.getElementById("catRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $catRollNumberAdditional );  ?>";
                      document.getElementById("catRollNumberAdditional").style.color = "";
                  </script>
                <?php } ?>

                <div style='display:none'><div class='errorMsg' id= 'catRollNumberAdditional_error'></div></div>
                </div>
                </div>

	    </li>

            <?php
            if(isset($NIILM_testNames) && $NIILM_testNames!="" && strpos($NIILM_testNames,'CAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('NIILM_testNames0'));
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
                <label>XAT Roll No.: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
                <?php if(isset($xatRollNumberAdditional) && $xatRollNumberAdditional!=""){ ?>
                  <script>
                      document.getElementById("xatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $xatRollNumberAdditional );  ?>";
                      document.getElementById("xatRollNumberAdditional").style.color = "";
                  </script>
                <?php } ?>

                <div style='display:none'><div class='errorMsg' id= 'xatRollNumberAdditional_error'></div></div>
                </div>
                </div>

			</li>

			<?php
            if(isset($NIILM_testNames) && $NIILM_testNames!="" && strpos($NIILM_testNames,'XAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('NIILM_testNames1'));
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
                <label>MAT Roll No.: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='matRollNumberAdditional' id='matRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
                <?php if(isset($matRollNumberAdditional) && $matRollNumberAdditional!=""){ ?>
                  <script>
                      document.getElementById("matRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $matRollNumberAdditional );  ?>";
                      document.getElementById("matRollNumberAdditional").style.color = "";
                  </script>
                <?php } ?>

                <div style='display:none'><div class='errorMsg' id= 'matRollNumberAdditional_error'></div></div>
                </div>
                </div>

			</li>

            <?php
            if(isset($NIILM_testNames) && $NIILM_testNames!=""){
                $tests = explode(',',$NIILM_testNames);
                foreach ($tests as $test){
                    if($test=='MAT'){
                        ?>
                    <script>
                        checkTestScore(document.getElementById('NIILM_testNames2'));
                    </script>
                        <?php }
                }
            }
            ?>


            <li id='atma1' style="display:none;">
                <div class='additionalInfoLeftCol'>
                <label>ATMA Score: </label>
                <div class='fieldBoxLarge'>
                <input type='text' name='atmaScoreAdditional' id='atmaScoreAdditional'         tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true' caption='score' minlength=1 maxlength=5 validate='validateFloat' />
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
                        <input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'         tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat' />
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

            <li id="atma2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>ATMA Roll No.: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
                <?php if(isset($atmaRollNumberAdditional) && $atmaRollNumberAdditional!=""){ ?>
                  <script>
                      document.getElementById("atmaRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $atmaRollNumberAdditional );  ?>";
                      document.getElementById("atmaRollNumberAdditional").style.color = "";
                  </script>
                <?php } ?>

                <div style='display:none'><div class='errorMsg' id= 'atmaRollNumberAdditional_error'></div></div>
                </div>
                </div>

			</li>

            <?php
            if(isset($NIILM_testNames) && $NIILM_testNames!="" && strpos($NIILM_testNames,'ATMA')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('NIILM_testNames3'));
            </script>
                <?php
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
                        <input type='text' name='cmatPercentileNIILM' id='cmatPercentileNIILM'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
                        <?php if(isset($cmatPercentileNIILM) && $cmatPercentileNIILM!=""){ ?>
                        <script>
                            document.getElementById("cmatPercentileNIILM").value = "<?php echo str_replace("\n", '\n', $cmatPercentileNIILM );  ?>";
                            document.getElementById("cmatPercentileNIILM").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'cmatPercentileNIILM_error'></div></div>
                    </div>
                </div>

            </li>

            <li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>CMAT Roll No.: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Roll number for the exam."   value=''   />
                <?php if(isset($cmatRollNumberAdditional) && $cmatRollNumberAdditional!=""){ ?>
                  <script>
                      document.getElementById("cmatRollNumberAdditional").value = "<?php echo str_replace("\n", '\n', $cmatRollNumberAdditional );  ?>";
                      document.getElementById("cmatRollNumberAdditional").style.color = "";
                  </script>
                <?php } ?>

                <div style='display:none'><div class='errorMsg' id= 'cmatRollNumberAdditional_error'></div></div>
                </div>
                </div>
			</li>

            <?php
            if(isset($NIILM_testNames) && $NIILM_testNames!="" && strpos($NIILM_testNames,'CMAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('NIILM_testNames4'));
            </script>
                <?php
            }
			?>


		
		
		<?php if($action != 'updateScore'):?>
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
	
			<li>	<label style="font-weight:normal; padding-top:0px">Disclaimer:</label>
				<div class='float_L' style="width:600px; color:#666666; font-style:italic; float:left">
				<?php
				$nameOfTheUser = array();
				foreach($basicInformation as $info) {
					if($info['fieldName'] == 'firstName' || $info['fieldName'] == 'middleName' || $info['fieldName'] == 'lastName') {
						$nameOfTheUser[$info['fieldName']] .= $info['value'];
					}
				}
				$nameOfTheUser = $nameOfTheUser['firstName'].' '.$nameOfTheUser['middleName'].' '.$nameOfTheUser['lastName'];
				?>
                I, <?php echo $nameOfTheUser; ?>, hereby declare that the particulars furnished in this application form are correct to the best of my knowledge and belief. I will upon admission to NIILM-CMS, adhere to the rule of the institute strive to uphold its esteem by following the highest standard of morals, intellectual and social discipline. I hold myself responsible for the PROMPT PAYMENT OF ALL dues and fees as are required by institution and fully understand that the institution shell be free to take penal action against me in the event(s) of any conduct of mine which is deemed to be contravening the statutes, norms ,rules and regulation of NIILM-CMS.                
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input blurmethod="return false" type='checkbox' name='agreeToTermsNIILM' id='agreeToTermsNIILM'   value='1'  required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsNIILM) && $agreeToTermsNIILM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsNIILM"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					     objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsNIILM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsNIILM_error'></div></div>
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
