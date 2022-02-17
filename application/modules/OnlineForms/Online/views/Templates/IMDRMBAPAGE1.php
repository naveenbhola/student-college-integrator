<script>
    function checkTestScore(obj){
        var key = obj.value.toLowerCase();
        if(obj.value == "CMAT" || obj.value == "CAT" || obj.value == "MAT" || obj.value == "XAT" ){
            var objects1 = new Array(key+"RollNumberAdditional",key+"PercentileAdditional");
        }else{
            var objects1 = new Array(key+"RollNumberAdditional",key+"PercentileIMDR");
        }

        if(obj){
            if( obj.checked == false ){
                $(key+'1').style.display = 'none';
                //Set the required paramters when any Exam is hidden
                resetExamFields(objects1);
            }
            else{
                $(key+'1').style.display = '';
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
	
			
			<li>
				<h3 class=upperCase'>PERSONAL DETAILS</h3>
				<div class='additionalInfoLeftCol'>
				<label>Caste: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='casteIMDR' id='casteIMDR'  required="true"    validate="validateStr"    caption="caste"  tip="Please Enter your Caste."   value=''  minlength="2" maxlength="20" />
				<?php if(isset($casteIMDR) && $casteIMDR!=""){ ?>
				  <script>
				      document.getElementById("casteIMDR").value = "<?php echo str_replace("\n", '\n', $casteIMDR );  ?>";
				      document.getElementById("casteIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'casteIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Whether member of the Scheduled Caste etc: </label>
				<div class='fieldBoxLarge'>
				<select name='ReservedCat_IMDR' id='ReservedCat_IMDR'    tip="Please Select your Category."       onmouseover="showTipOnline('Please Select your Category.',this);" onmouseout="hidetip();" ><option value='' selected>Select
				</option><option value="sc">SC</option><option value="st">ST</option><option value="nt">NT</option><option value="vint">VINT</option><option value="obc">OBC</option><option value="open">OPEN</option></select>
				<?php if(isset($ReservedCat_IMDR) && $ReservedCat_IMDR!=""){ ?>
			      <script>
				  var selObj = document.getElementById("ReservedCat_IMDR"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $ReservedCat_IMDR;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ReservedCat_IMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Place of Birth(Village/Town): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='placeofBirthVillageIMDR' id='placeofBirthVillageIMDR'  required="true"    validate="validateStr"  caption="Village/Town"        tip="Please enter the village/town where you were born."   value='' minlength="2" maxlength="50"  />
				<?php if(isset($placeofBirthVillageIMDR) && $placeofBirthVillageIMDR!=""){ ?>
				  <script>
				      document.getElementById("placeofBirthVillageIMDR").value = "<?php echo str_replace("\n", '\n', $placeofBirthVillageIMDR );  ?>";
				      document.getElementById("placeofBirthVillageIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'placeofBirthVillageIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Place of Birth(State): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='placeofBirthStateIMDR' id='placeofBirthStateIMDR'  required="true" minlength="2" maxlength="50"   validate="validateStr"   caption="State"    tip="Please enter the state where your were born."   value=''   />
				<?php if(isset($placeofBirthStateIMDR) && $placeofBirthStateIMDR!=""){ ?>
				  <script>
				      document.getElementById("placeofBirthStateIMDR").value = "<?php echo str_replace("\n", '\n', $placeofBirthStateIMDR );  ?>";
				      document.getElementById("placeofBirthStateIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'placeofBirthStateIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Years: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ageYearsIMDR' id='ageYearsIMDR'  required="true" minlength="0" maxlength="3"   validate="validateInteger"  caption="age  as on 30th June 2015"     tip="Enter your age(in years)  as on 30th June 2015."   value=''   />
				<?php if(isset($ageYearsIMDR) && $ageYearsIMDR!=""){ ?>
				  <script>
				      document.getElementById("ageYearsIMDR").value = "<?php echo str_replace("\n", '\n', $ageYearsIMDR );  ?>";
				      document.getElementById("ageYearsIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageYearsIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Months: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='ageMonthsIMDR' id='ageMonthsIMDR' required="true" validate="validateInteger" minlength="2" maxlength="2"  caption="age  as on 30th June 2015"        tip="Enter your age(in months)  as on 30th June 2015."   value=''   />
				<?php if(isset($ageMonthsIMDR) && $ageMonthsIMDR!=""){ ?>
				  <script>
				      document.getElementById("ageMonthsIMDR").value = "<?php echo str_replace("\n", '\n', $ageMonthsIMDR );  ?>";
				      document.getElementById("ageMonthsIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'ageMonthsIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Permanent phone number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='phoneNumberIMDR' id='phoneNumberIMDR'  required="true"  validate="validateInteger" minlength="6" maxlength="10" caption="permanent phone number"       tip="Enter phone number of your permanent address"   value=''   />
				<?php if(isset($phoneNumberIMDR) && $phoneNumberIMDR!=""){ ?>
				  <script>
				      document.getElementById("phoneNumberIMDR").value = "<?php echo str_replace("\n", '\n', $phoneNumberIMDR );  ?>";
				      document.getElementById("phoneNumberIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'phoneNumberIMDR_error'></div></div>
				</div>
				</div>

				
			</li>
			
			

			<li>
				<h3 class=upperCase'>PERSON TO BE CONTACTED IN CASE OF EMERGENCY </h3>
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='emergencyNameIMDR' id='emergencyNameIMDR'  minlength="2" maxlength="30" required="true" validate="validateStr" caption="name of the person"        tip="Please enter name of the person to be contacted in case of  emergency."   value=''   />
				<?php if(isset($emergencyNameIMDR) && $emergencyNameIMDR!=""){ ?>
				  <script>
				      document.getElementById("emergencyNameIMDR").value = "<?php echo str_replace("\n", '\n', $emergencyNameIMDR );  ?>";
				      document.getElementById("emergencyNameIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emergencyNameIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='emergencyAddressIMDR' id='emergencyAddressIMDR' minlength="2" maxlength="40" required="true" validate="validateStr" caption="address of the person"         tip="Please enter address of the person to be contacted in case of  emergency."   value=''   />
				<?php if(isset($emergencyAddressIMDR) && $emergencyAddressIMDR!=""){ ?>
				  <script>
				      document.getElementById("emergencyAddressIMDR").value = "<?php echo str_replace("\n", '\n', $emergencyAddressIMDR );  ?>";
				      document.getElementById("emergencyAddressIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emergencyAddressIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>PIN Code: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='emergencyPincodeIMDR' id='emergencyPincodeIMDR'  required="true" minlength="4" maxlength="7" validate="validateInteger"" caption="pincode of the person"       tip="Enter pincode of the person to be contacted in case of emergency"   value=''   />
				<?php if(isset($emergencyPincodeIMDR) && $emergencyPincodeIMDR!=""){ ?>
				  <script>
				      document.getElementById("emergencyPincodeIMDR").value = "<?php echo str_replace("\n", '\n', $emergencyPincodeIMDR );  ?>";
				      document.getElementById("emergencyPincodeIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emergencyPincodeIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Phone Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='emergencyPhoneNoIMDR' id='emergencyPhoneNoIMDR' required="true" validate="validateInteger"  minlength="6" maxlength="10"    caption="phone number of the person"        tip="Enter Phone Number of the person to be contacted in case of emergency"   value=''   />
				<?php if(isset($emergencyPhoneNoIMDR) && $emergencyPhoneNoIMDR!=""){ ?>
				  <script>
				      document.getElementById("emergencyPhoneNoIMDR").value = "<?php echo str_replace("\n", '\n', $emergencyPhoneNoIMDR );  ?>";
				      document.getElementById("emergencyPhoneNoIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'emergencyPhoneNoIMDR_error'></div></div>
				</div>
				</div>	    <?php endif; ?>

			</li>
			
			 <li>
            	<h3 class="upperCase">Qualifying Examinations:</h3>
                <div class='additionalInfoLeftCol' style="width:900px;">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:415px;">
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='IMDR_testNames[]' id='IMDR_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='IMDR_testNames[]' id='IMDR_testNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='IMDR_testNames[]' id='IMDR_testNames2'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='IMDR_testNames[]' id='IMDR_testNames3'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='IMDR_testNames[]' id='IMDR_testNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='IMDR_testNames[]' id='IMDR_testNames5'   value='MHCET'     onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score, percentile, registration number and exam date (if available)',this);" onmouseout="hidetip();" >MH-CET</span>&nbsp;&nbsp;
			 <?php if(isset($IMDR_testNames) && $IMDR_testNames!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["IMDR_testNames[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$IMDR_testNames);
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

                        <div style='display:none'><div class='errorMsg' id= 'IMDR_testNames_error'></div></div>
                    </div>
                </div>
            </li>

			
            <li id='cat1' style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>CAT Seat No: </label>
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

           
            <?php
            if(isset($IMDR_testNames) && $IMDR_testNames!="" && strpos($IMDR_testNames,'CAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('IMDR_testNames0'));
            </script>
                <?php
            }
            ?>


<li id='xat1' style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>XAT Seat No: </label>
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
                    <label>XAT Percentile: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='xatPercentileAdditional' id='xatPercentileAdditional'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength='1' maxlength='5' validate='validateFloat' />
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
            if(isset($IMDR_testNames) && $IMDR_testNames!="" && strpos($IMDR_testNames,'XAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('IMDR_testNames1'));
            </script>
                <?php
            }
            ?>

<li id='mat1' style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>MAT Seat No: </label>
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
                    <label>MAT Percentile: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='matPercentileAdditional' id='matPercentileAdditional'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength='1' maxlength='5' validate='validateFloat' />
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
            if(isset($IMDR_testNames) && $IMDR_testNames!="" && strpos($IMDR_testNames,'MAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('IMDR_testNames2'));
            </script>
                <?php
            }
            ?>


<li id='atma1' style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>ATMA Seat No: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='atmaRollNumberAdditional' id='atmaRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
                    <label>ATMA Percentile: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='atmaPercentileAdditional' id='atmaPercentileAdditional'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength='1' maxlength='5' validate='validateFloat' />
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
            if(isset($IMDR_testNames) && $IMDR_testNames!="" && strpos($IMDR_testNames,'ATMA')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('IMDR_testNames3'));
            </script>
                <?php
            }
            ?>

<li id='cmat1' style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>CMAT Seat No: </label>
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
                    <label>CMAT Percentile: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='cmatPercentileIMDR' id='cmatPercentileIMDR'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength='1' maxlength='5' validate='validateFloat' />
                        <?php if(isset($cmatPercentileIMDR) && $cmatPercentileIMDR!=""){ ?>
                        <script>
                            document.getElementById("cmatPercentileIMDR").value = "<?php echo str_replace("\n", '\n', $cmatPercentileIMDR );  ?>";
                            document.getElementById("cmatPercentileIMDR").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'cmatPercentileIMDR_error'></div></div>
                    </div>
                </div>

            </li>

           
            <?php
            if(isset($IMDR_testNames) && $IMDR_testNames!="" && strpos($IMDR_testNames,'CMAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('IMDR_testNames4'));
            </script>
                <?php
            }
            ?>

<li id='mhcet1' style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">
                <div class='additionalInfoLeftCol'>
                <label>MHCET Seat No: </label>
                <div class='fieldBoxLarge'>
                <input class="textboxLarge" type='text' name='mhcetRollNumberAdditional' id='mhcetRollNumberAdditional'  validate="validateStr"    caption="regn no"   minlength="2"   maxlength="50"        tip="Mention your Registration number for the exam."   value=''   />
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
                    <label>MHCET Percentile: </label>
                    <div class='fieldBoxLarge'>
                        <input type='text' name='mhcetPercentileIMDR' id='mhcetPercentileIMDR'          tip="Mention your percentile in the exam. If you don't know your percentile, enter NA."   value=''  allowNA='true' caption='percentile' minlength='1' maxlength='5' validate='validateFloat' />
                        <?php if(isset($mhcetPercentileIMDR) && $mhcetPercentileIMDR!=""){ ?>
                        <script>
                            document.getElementById("mhcetPercentileIMDR").value = "<?php echo str_replace("\n", '\n', $mhcetPercentileIMDR );  ?>";
                            document.getElementById("mhcetPercentileIMDR").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'mhcetPercentileIMDR_error'></div></div>
                    </div>
                </div>

            </li>

           
            <?php
            if(isset($IMDR_testNames) && $IMDR_testNames!="" && strpos($IMDR_testNames,'MHCET')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('IMDR_testNames5'));
            </script>
                <?php
            }
            ?>

		
		
		
		<?php if($action != 'updateScore'):?>

			<li>
				<h3 class=upperCase'>EDUCATIONAL QUALIFICATION </h3>
				<div class='additionalInfoLeftCol'>
				<label>11th Std Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Standard11thMaxMarksIMDR' id='Standard11thMaxMarksIMDR' required="true" validate="validateFloat"  minlength="1"   maxlength="5" caption="maximum marks in 11th standard "        tip="Enter the maximum marks for 11 Std."   value=''   />
				<?php if(isset($Standard11thMaxMarksIMDR) && $Standard11thMaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Standard11thMaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Standard11thMaxMarksIMDR );  ?>";
				      document.getElementById("Standard11thMaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Standard11thMaxMarksIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>11th Std Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Standard11thMarksObtainedIMDR' id='Standard11thMarksObtainedIMDR'  required="true" validate="validateFloat"  minlength="1"   maxlength="5"  caption="marks obtained in 11th standard "       tip="Enter the  marks obtained for 11th Std."   value=''   />
				<?php if(isset($Standard11thMarksObtainedIMDR) && $Standard11thMarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Standard11thMarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Standard11thMarksObtainedIMDR );  ?>";
				      document.getElementById("Standard11thMarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Standard11thMarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>12th Std Maximum Marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Standard12thMaxMarksIMDR' id='Standard12thMaxMarksIMDR'  required="true" minlength="1"   maxlength="5" validate="validateFloat" caption="maximum marks in 12th Standard"        tip="Enter the  maximum marks for 12th Std."   value=''   />
				<?php if(isset($Standard12thMaxMarksIMDR) && $Standard12thMaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Standard12thMaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Standard12thMaxMarksIMDR );  ?>";
				      document.getElementById("Standard12thMaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Standard12thMaxMarksIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>12th Std Marks Obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Standard12thMarksObtainedIMDR' id='Standard12thMarksObtainedIMDR' minlength="1"   maxlength="5" required="true" validate="validateFloat" caption="marks obtained in 12th standard "        tip="Enter the  marks obtained for 12th Std."   value=''   />
				<?php if(isset($Standard12thMarksObtainedIMDR) && $Standard12thMarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Standard12thMarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Standard12thMarksObtainedIMDR );  ?>";
				      document.getElementById("Standard12thMarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Standard12thMarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Bachelor's Special Subject at final year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='BachelorSpecialSubjIMDR' id='BachelorSpecialSubjIMDR'  required="true" validate="validateStr" minlength="2" maxlength="30"   caption="special subject in Bachelor's final year"       tip="Enter special subject in final year in your bachelor's degree."   value=''   />
				<?php if(isset($BachelorSpecialSubjIMDR) && $BachelorSpecialSubjIMDR!=""){ ?>
				  <script>
				      document.getElementById("BachelorSpecialSubjIMDR").value = "<?php echo str_replace("\n", '\n', $BachelorSpecialSubjIMDR );  ?>";
				      document.getElementById("BachelorSpecialSubjIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'BachelorSpecialSubjIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester I Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester1BoardIMDR' id='Semester1BoardIMDR'   required="true" validate="validateStr" minlength="2" maxlength="40" caption="Board/University of Semester I"      tip="Enter your board/university for Semester I"   value=''   />
				<?php if(isset($Semester1BoardIMDR) && $Semester1BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester1BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester1BoardIMDR );  ?>";
				      document.getElementById("Semester1BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester1BoardIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester I Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester1YearIMDR' id='Semester1YearIMDR'  required="true" validate="validateInteger" caption="year of passing of Semester I " minlength="4"   maxlength="4"      tip="Enter year of passing for Semester I"   value=''   />
				<?php if(isset($Semester1YearIMDR) && $Semester1YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester1YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester1YearIMDR );  ?>";
				      document.getElementById("Semester1YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester1YearIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester I Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester1MaxMarksIMDR' id='Semester1MaxMarksIMDR' minlength="1"   maxlength="5" required="true" validate="validateFloat" caption="maximum marks of Semester I"        tip="Enter the maximum marks for Semester 1."   value=''   />
				<?php if(isset($Semester1MaxMarksIMDR) && $Semester1MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester1MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester1MaxMarksIMDR );  ?>";
				      document.getElementById("Semester1MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester1MaxMarksIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester I Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester1MarksObtainedIMDR' id='Semester1MarksObtainedIMDR' minlength="1"   maxlength="5" required="true" validate="validateFloat" caption="maximum marks obtained in Semester I"        tip="Enter the marks obtained for Semester 1."   value=''   />
				<?php if(isset($Semester1MarksObtainedIMDR) && $Semester1MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester1MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester1MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester1MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester1MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Percentage in Semester I: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester1PercentIMDR' id='Semester1PercentIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="Percentage in Semester I"        tip="Enter Percentage in First Semester"   value=''   />
				<?php if(isset($Semester1PercentIMDR) && $Semester1PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester1PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester1PercentIMDR );  ?>";
				      document.getElementById("Semester1PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester1PercentIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester II Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester2BoardIMDR' id='Semester2BoardIMDR'  required="true" validate="validateStr" minlength="2" maxlength="40" caption="Semester II Board/University"       tip="Enter your board/university for Semester II"   value=''   />
				<?php if(isset($Semester2BoardIMDR) && $Semester2BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester2BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester2BoardIMDR );  ?>";
				      document.getElementById("Semester2BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester2BoardIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Semester II Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester2YearIMDR' id='Semester2YearIMDR' required="true" validate="validateInteger" minlength="4"   maxlength="4" caption="year of passing of Semester II "        tip="Enter year of passing for Semester II"   value=''   />
				<?php if(isset($Semester2YearIMDR) && $Semester2YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester2YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester2YearIMDR );  ?>";
				      document.getElementById("Semester2YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester2YearIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester II Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester2MaxMarksIMDR' id='Semester2MaxMarksIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks in semester II"         tip="Enter the maximum marks for Semester II."   value=''   />
				<?php if(isset($Semester2MaxMarksIMDR) && $Semester2MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester2MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester2MaxMarksIMDR );  ?>";
				      document.getElementById("Semester2MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester2MaxMarksIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Semester II Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester2MarksObtainedIMDR' id='Semester2MarksObtainedIMDR'  required="true" validate="validateFloat"  minlength="1"   maxlength="5" caption="maximum marks obtained in semester II"       tip="Enter the marks obtained for Semester II."   value=''   />
				<?php if(isset($Semester2MarksObtainedIMDR) && $Semester2MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester2MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester2MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester2MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester2MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage in Semester II: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester2PercentIMDR' id='Semester2PercentIMDR' required="true" validate="validateFloat"  minlength="1"   maxlength="5" caption="Percentage in Semester II"        tip="Enter Percentage in Second Semester"   value=''   />
				<?php if(isset($Semester2PercentIMDR) && $Semester2PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester2PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester2PercentIMDR );  ?>";
				      document.getElementById("Semester2PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester2PercentIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester III Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester3BoardIMDR' id='Semester3BoardIMDR'   required="true" validate="validateStr" caption="Board/University of Semester III"  minlength="2" maxlength="40"      tip="Enter your board/university for Semester III"   value=''   />
				<?php if(isset($Semester3BoardIMDR) && $Semester3BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester3BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester3BoardIMDR );  ?>";
				      document.getElementById("Semester3BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester3BoardIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester III Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester3YearIMDR' id='Semester3YearIMDR'  required="true" validate="validateInteger" minlength="4"   maxlength="4" caption="year of passing of Semester III "       tip="Enter year of passing for Semester III"   value=''   />
				<?php if(isset($Semester3YearIMDR) && $Semester3YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester3YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester3YearIMDR );  ?>";
				      document.getElementById("Semester3YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester3YearIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester III Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester3MaxMarksIMDR' id='Semester3MaxMarksIMDR'  required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks in Semester III "       tip="Enter the maximum marks for Semester III."   value=''   />
				<?php if(isset($Semester3MaxMarksIMDR) && $Semester3MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester3MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester3MaxMarksIMDR );  ?>";
				      document.getElementById("Semester3MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester3MaxMarksIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester III Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester3MarksObtainedIMDR' id='Semester3MarksObtainedIMDR'  required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks obtained in semester III"       tip="Enter the marks obtained for Semester III."   value=''   />
				<?php if(isset($Semester3MarksObtainedIMDR) && $Semester3MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester3MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester3MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester3MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester3MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Percentage in Semester III: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester3PercentIMDR' id='Semester3PercentIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5"  caption="Percentage in Semester III"        tip="Enter Percentage in third Semester"   value=''   />
				<?php if(isset($Semester3PercentIMDR) && $Semester3PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester3PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester3PercentIMDR );  ?>";
				      document.getElementById("Semester3PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester3PercentIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester IV Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester4BoardIMDR' id='Semester4BoardIMDR'  required="true" validate="validateStr" caption="Board/University of Semester IV"  minlength="2" maxlength="40"     tip="Enter your board/university for Semester IV"   value=''   />
				<?php if(isset($Semester4BoardIMDR) && $Semester4BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester4BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester4BoardIMDR );  ?>";
				      document.getElementById("Semester4BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester4BoardIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester IV Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester4YearIMDR' id='Semester4YearIMDR' required="true" validate="validateInteger" minlength="4"   maxlength="4" caption="year of passing of Semester IV "        tip="Enter year of passing for Semester IV"   value=''   />
				<?php if(isset($Semester4YearIMDR) && $Semester4YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester4YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester4YearIMDR );  ?>";
				      document.getElementById("Semester4YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester4YearIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester IV Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester4MaxMarksIMDR' id='Semester4MaxMarksIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks in Semester IV "        tip="Enter the maximum marks for Semester IV."   value=''   />
				<?php if(isset($Semester4MaxMarksIMDR) && $Semester4MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester4MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester4MaxMarksIMDR );  ?>";
				      document.getElementById("Semester4MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester4MaxMarksIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester IV Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester4MarksObtainedIMDR' id='Semester4MarksObtainedIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5"  caption="marks obtained in semester IV"        tip="Enter the marks obtained for Semester IV."   value=''   />
				<?php if(isset($Semester4MarksObtainedIMDR) && $Semester4MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester4MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester4MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester4MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester4MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage in Semester IV: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester4PercentIMDR' id='Semester4PercentIMDR' required="true" validate="validateFloat"  minlength="1"   maxlength="5" caption="Percentage in Semester IV "          tip="Enter Percentage in fourth Semester"   value=''   />
				<?php if(isset($Semester4PercentIMDR) && $Semester4PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester4PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester4PercentIMDR );  ?>";
				      document.getElementById("Semester4PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester4PercentIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester V Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester5BoardIMDR' id='Semester5BoardIMDR' required="true" validate="validateStr" caption="board/university of semester V"  minlength="2" maxlength="40"        tip="Enter your board/university for Semester V"   value=''   />
				<?php if(isset($Semester5BoardIMDR) && $Semester5BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester5BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester5BoardIMDR );  ?>";
				      document.getElementById("Semester5BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester5BoardIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester V Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester5YearIMDR' id='Semester5YearIMDR' required="true" validate="validateInteger" minlength="4"   maxlength="4" caption="year of passing of semester V"        tip="Enter year of passing for Semester V"   value=''   />
				<?php if(isset($Semester5YearIMDR) && $Semester5YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester5YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester5YearIMDR );  ?>";
				      document.getElementById("Semester5YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester5YearIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester V Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester5MaxMarksIMDR' id='Semester5MaxMarksIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks in semester V"        tip="Enter the maximum marks for Semester V."   value=''   />
				<?php if(isset($Semester5MaxMarksIMDR) && $Semester5MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester5MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester5MaxMarksIMDR );  ?>";
				      document.getElementById("Semester5MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester5MaxMarksIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester V Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester5MarksObtainedIMDR' id='Semester5MarksObtainedIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption=" maximum marks obtained in Semester V"         tip="Enter the marks obtained for Semester V."   value=''   />
				<?php if(isset($Semester5MarksObtainedIMDR) && $Semester5MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester5MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester5MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester5MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester5MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Percentage in Semester V: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester5PercentIMDR' id='Semester5PercentIMDR'  required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="Percentage in Semester V"       tip="Enter Percentage in fifth Semester"   value=''   />
				<?php if(isset($Semester5PercentIMDR) && $Semester5PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester5PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester5PercentIMDR );  ?>";
				      document.getElementById("Semester5PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester5PercentIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester VI Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester6BoardIMDR' id='Semester6BoardIMDR' required="true" validate="validateStr" caption="Board/University of Semester VI "  minlength="2" maxlength="40"        tip="Enter your board/university for Semester VI"   value=''   />
				<?php if(isset($Semester6BoardIMDR) && $Semester6BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester6BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester6BoardIMDR );  ?>";
				      document.getElementById("Semester6BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester6BoardIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester VI Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester6YearIMDR' id='Semester6YearIMDR' required="true" validate="validateInteger" minlength="4"   maxlength="4"  caption="year of passing of Semester VI "        tip="Enter year of passing for Semester VI"   value=''   />
				<?php if(isset($Semester6YearIMDR) && $Semester6YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester6YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester6YearIMDR );  ?>";
				      document.getElementById("Semester6YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester6YearIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester VI Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester6MaxMarksIMDR' id='Semester6MaxMarksIMDR'  required="true" validate="validateFloat"  minlength="1"   maxlength="5" caption="maximum marks in semester VI"       tip="Enter the maximum marks for Semester VI."   value=''   />
				<?php if(isset($Semester6MaxMarksIMDR) && $Semester6MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester6MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester6MaxMarksIMDR );  ?>";
				      document.getElementById("Semester6MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester6MaxMarksIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester VI Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester6MarksObtainedIMDR' id='Semester6MarksObtainedIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5"  caption="marks obtained in semester VI"        tip="Enter the marks obtained for Semester VI."   value=''   />
				<?php if(isset($Semester6MarksObtainedIMDR) && $Semester6MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester6MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester6MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester6MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester6MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage in Semester VI: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester6PercentIMDR' id='Semester6PercentIMDR'  required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="Percentage in Semester VI"       tip="Enter Percentage in sixth Semester"   value=''   />
				<?php if(isset($Semester6PercentIMDR) && $Semester6PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester6PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester6PercentIMDR );  ?>";
				      document.getElementById("Semester6PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester6PercentIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester VII Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester7BoardIMDR' id='Semester7BoardIMDR'  required="true" validate="validateStr" caption="board/university of semester VII"  minlength="2" maxlength="40"     tip="Enter your board/university for Semester VII"   value=''   />
				<?php if(isset($Semester7BoardIMDR) && $Semester7BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester7BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester7BoardIMDR );  ?>";
				      document.getElementById("Semester7BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester7BoardIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester VII Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester7YearIMDR' id='Semester7YearIMDR'  required="true" validate="validateInteger" minlength="4"   maxlength="4" caption="year of passing of semester VII"       tip="Enter year of passing for Semester VII"   value=''   />
				<?php if(isset($Semester7YearIMDR) && $Semester7YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester7YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester7YearIMDR );  ?>";
				      document.getElementById("Semester7YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester7YearIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester VII Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester7MaxMarksIMDR' id='Semester7MaxMarksIMDR'  required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks in semester VII"       tip="Enter the maximum marks for Semester VII."   value=''   />
				<?php if(isset($Semester7MaxMarksIMDR) && $Semester7MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester7MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester7MaxMarksIMDR );  ?>";
				      document.getElementById("Semester7MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester7MaxMarksIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester VII Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester7MarksObtainedIMDR' id='Semester7MarksObtainedIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="maximum marks obtained in semester VII"        tip="Enter the marks obtained for Semester VII."   value=''   />
				<?php if(isset($Semester7MarksObtainedIMDR) && $Semester7MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester7MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester7MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester7MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester7MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Percentage in Semester VII: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester7PercentIMDR' id='Semester7PercentIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5" caption="Percentage in Semester VII"        tip="Enter Percentage in seventh Semester"   value=''   />
				<?php if(isset($Semester7PercentIMDR) && $Semester7PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester7PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester7PercentIMDR );  ?>";
				      document.getElementById("Semester7PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester7PercentIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester VIII Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester8BoardIMDR' id='Semester8BoardIMDR' required="true" validate="validateStr" caption="board/university of semester VIII"  minlength="2" maxlength="40"      tip="Enter your board/university for Semester VIII"   value=''   />
				<?php if(isset($Semester8BoardIMDR) && $Semester8BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester8BoardIMDR").value = "<?php echo str_replace("\n", '\n', $Semester8BoardIMDR );  ?>";
				      document.getElementById("Semester8BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester8BoardIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester VIII Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester8YearIMDR' id='Semester8YearIMDR'  required="true" validate="validateInteger" minlength="4"   maxlength="4" caption="year of passing of semester VIII"       tip="Enter year of passing for Semester VIII"   value=''   />
				<?php if(isset($Semester8YearIMDR) && $Semester8YearIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester8YearIMDR").value = "<?php echo str_replace("\n", '\n', $Semester8YearIMDR );  ?>";
				      document.getElementById("Semester8YearIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester8YearIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Semester VIII Maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester8MaxMarksIMDR' id='Semester8MaxMarksIMDR' required="true" validate="validateFloat" minlength="1"   maxlength="5"  caption="maximum marks in semester VIII"         tip="Enter the maximum marks for Semester VIII."   value=''   />
				<?php if(isset($Semester8MaxMarksIMDR) && $Semester8MaxMarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester8MaxMarksIMDR").value = "<?php echo str_replace("\n", '\n', $Semester8MaxMarksIMDR );  ?>";
				      document.getElementById("Semester8MaxMarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester8MaxMarksIMDR_error'></div></div>
				</div>
				</div>
				<div class='additionalInfoRightCol'>
				<label>Semester VIII Marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester8MarksObtainedIMDR' id='Semester8MarksObtainedIMDR'  tip="Enter the marks obtained for Semester VIII."   value=''  caption="marks" validate="validateInteger" minlength ="1" maxlength="5" />
				<?php if(isset($Semester8MarksObtainedIMDR) && $Semester8MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester8MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $Semester8MarksObtainedIMDR );  ?>";
				      document.getElementById("Semester8MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester8MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Percentage in Semester VIII: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Semester8PercentIMDR' id='Semester8PercentIMDR'  tip="Enter Percentage in eigth Semester" caption="percentage" validate="validateInteger" minlength ="1" maxlength="5"  value=''   />
				<?php if(isset($Semester8PercentIMDR) && $Semester8PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("Semester8PercentIMDR").value = "<?php echo str_replace("\n", '\n', $Semester8PercentIMDR );  ?>";
				      document.getElementById("Semester8PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Semester8PercentIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Master's Special Subject at final year: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MasterSpecialSubjIMDR' id='MasterSpecialSubjIMDR'  tip="Enter special subject in final year in your Master's degree."  caption="subject" validate = "validateStr" minlength ="1" maxlength="30"   value='' />
				<?php if(isset($MasterSpecialSubjIMDR) && $MasterSpecialSubjIMDR!=""){ ?>
				  <script>
				      document.getElementById("MasterSpecialSubjIMDR").value = "<?php echo str_replace("\n", '\n', $MasterSpecialSubjIMDR );  ?>";
				      document.getElementById("MasterSpecialSubjIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MasterSpecialSubjIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Master's First year Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear1BoardIMDR' id='MastersYear1BoardIMDR'         tip="Enter your board/university of first year of your Master's" caption="University" validate="validateStr" minlength ="1" maxlength="30"  value=''   />
				<?php if(isset($MastersYear1BoardIMDR) && $MastersYear1BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear1BoardIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear1BoardIMDR );  ?>";
				      document.getElementById("MastersYear1BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear1BoardIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Master's First Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear1PassingIMDR' id='MastersYear1PassingIMDR'         tip="Enter year of passing of First year of your Master's" caption="year of passing" validate="validateInteger" minlength ="1" maxlength="4"  value=''   />
				<?php if(isset($MastersYear1PassingIMDR) && $MastersYear1PassingIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear1PassingIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear1PassingIMDR );  ?>";
				      document.getElementById("MastersYear1PassingIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear1PassingIMDR_error'></div></div>
				</div>
				</div>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Master first year's maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear1MarksIMDR' id='MastersYear1MarksIMDR'         tip="Enter the maximum marks of first year of your Master's." caption="marks" validate="validateInteger" minlength ="1" maxlength="5"  value=''   />
				<?php if(isset($MastersYear1MarksIMDR) && $MastersYear1MarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear1MarksIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear1MarksIMDR );  ?>";
				      document.getElementById("MastersYear1MarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear1MarksIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Master's first year's marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear1MarksObtainedIMDR' id='MastersYear1MarksObtainedIMDR'         tip="Enter the marks obtained in first year of your Master's." caption="marks" validate="validateInteger" minlength ="1" maxlength="5"  value=''   />
				<?php if(isset($MastersYear1MarksObtainedIMDR) && $MastersYear1MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear1MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear1MarksObtainedIMDR );  ?>";
				      document.getElementById("MastersYear1MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear1MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
				
			</li>
			<li>			
				<div class='additionalInfoLeftCol'>
				<label>Percentage in first year of Master's: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear1PercentIMDR' id='MastersYear1PercentIMDR'         tip="Enter Percentage in first year of Master's"   value='' caption="percentage" validate="validateInteger" minlength ="1" maxlength="5"   />
				<?php if(isset($MastersYear1PercentIMDR) && $MastersYear1PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear1PercentIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear1PercentIMDR );  ?>";
				      document.getElementById("MastersYear1PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear1PercentIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Master's Second year Board/University: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear2BoardIMDR' id='MastersYear2BoardIMDR'         tip="Enter your board/university of Second year of your Master's" caption="University" validate="validateStr" minlength ="1" maxlength="20"  value=''   />
				<?php if(isset($MastersYear2BoardIMDR) && $MastersYear2BoardIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear2BoardIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear2BoardIMDR );  ?>";
				      document.getElementById("MastersYear2BoardIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear2BoardIMDR_error'></div></div>
				</div>
				</div>
			</li>	
				
			<li>
			
				<div class='additionalInfoLeftCol'>
				<label>Master's Second Year of passing: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear2PassingIMDR' id='MastersYear2PassingIMDR'         tip="Enter year of passing of Second year of your Master's"  caption="year" validate="validateInteger" minlength ="1" maxlength="4" value=''   />
				<?php if(isset($MastersYear2PassingIMDR) && $MastersYear2PassingIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear2PassingIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear2PassingIMDR );  ?>";
				      document.getElementById("MastersYear2PassingIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear2PassingIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Master Second year's maximum marks: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear2MarksIMDR' id='MastersYear2MarksIMDR'         tip="Enter the maximum marks of Second year of your Master's."   value='' caption="marks" validate="validateInteger" minlength ="1" maxlength="5"   />
				<?php if(isset($MastersYear2MarksIMDR) && $MastersYear2MarksIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear2MarksIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear2MarksIMDR );  ?>";
				      document.getElementById("MastersYear2MarksIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear2MarksIMDR_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol'>
				<label>Master's Second year's marks obtained: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear2MarksObtainedIMDR' id='MastersYear2MarksObtainedIMDR'         tip="Enter the marks obtained in Second year of your Master's."  caption="marks" validate="validateInteger" minlength ="1" maxlength="5"  value=''   />
				<?php if(isset($MastersYear2MarksObtainedIMDR) && $MastersYear2MarksObtainedIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear2MarksObtainedIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear2MarksObtainedIMDR );  ?>";
				      document.getElementById("MastersYear2MarksObtainedIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear2MarksObtainedIMDR_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>Percentage in Second year of Master's: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MastersYear2PercentIMDR' id='MastersYear2PercentIMDR'         tip="Enter Percentage in Second year of Master's"   value='' caption="percentage" validate="validateInteger" minlength ="1" maxlength="5"  />
				<?php if(isset($MastersYear2PercentIMDR) && $MastersYear2PercentIMDR!=""){ ?>
				  <script>
				      document.getElementById("MastersYear2PercentIMDR").value = "<?php echo str_replace("\n", '\n', $MastersYear2PercentIMDR );  ?>";
				      document.getElementById("MastersYear2PercentIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MastersYear2PercentIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<h3 class=upperCase'> OTHER RELEVANT INFORMATION</h3>
				<div class='additionalInfoLeftCol'>
				<label>Rate your academic performance uptil now: </label>
				<div class='fieldBoxLarge'>
				<select name='AcademicPerformanceIMDR' id='AcademicPerformanceIMDR'    tip="Rate your academic performance uptil now " validate="validateSelect"  minlength="1"   maxlength="1500" required="true" caption="your academic performance uptil now"      onmouseover="showTipOnline('Rate your academic performance uptil now ',this);" onmouseout="hidetip();" ><option value="">Select</option></option><option value="poor">Poor</option><option value="fair">Fair</option><option value="good" >Good</option><option value="outstanding">Outstanding</option></select>
				<?php if(isset($AcademicPerformanceIMDR) && $AcademicPerformanceIMDR!=""){ ?>
			      <script>
				  var selObj = document.getElementById("AcademicPerformanceIMDR"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $AcademicPerformanceIMDR;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'AcademicPerformanceIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Write about yourself as a person: </label>
				<div class='fieldBoxLarge'>
				<textarea name='aboutYourselfIMDR' id='aboutYourselfIMDR'     required="true" validate="validateStr" caption="about yourself data" tip="Write about yourself as a person"  minlength="10" maxlength="200"  ></textarea>
				<?php if(isset($aboutYourselfIMDR) && $aboutYourselfIMDR!=""){ ?>
				  <script>
				      document.getElementById("aboutYourselfIMDR").value = "<?php echo str_replace("\n", '\n', $aboutYourselfIMDR );  ?>";
				      document.getElementById("aboutYourselfIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'aboutYourselfIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Extra-curricular activities: </label>
				<div class='fieldBoxLarge'>
				<textarea name='extraCurricularIMDR' id='extraCurricularIMDR'  required="true" validate="validateStr" caption="extra curricular activities" minlength="10" maxlength="200"      tip="Write about your Extra-curricular activities"    ></textarea>
				<?php if(isset($extraCurricularIMDR) && $extraCurricularIMDR!=""){ ?>
				  <script>
				      document.getElementById("extraCurricularIMDR").value = "<?php echo str_replace("\n", '\n', $extraCurricularIMDR );  ?>";
				      document.getElementById("extraCurricularIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'extraCurricularIMDR_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>State your career goals five years from now : </label>
				<div class='fieldBoxLarge'>
				<textarea name='careerGoalsIMDR' id='careerGoalsIMDR' required="true" validate="validateStr" minlength="10" maxlength="200" caption="career goals"        tip="State your career goals five years from now "    ></textarea>
				<?php if(isset($careerGoalsIMDR) && $careerGoalsIMDR!=""){ ?>
				  <script>
				      document.getElementById("careerGoalsIMDR").value = "<?php echo str_replace("\n", '\n', $careerGoalsIMDR );  ?>";
				      document.getElementById("careerGoalsIMDR").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'careerGoalsIMDR_error'></div></div>
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
			
			$MonthlyRemunerationIMDRValues 	= explode(",",$MonthlyRemunerationIMDR);
			$ReasonforleavingIMDRValues 	= explode(",",$ReasonforleavingIMDR);
			
			//_p($MonthlyRemunerationIMDRValues);die;
			if(!empty($workCompanies))
			{
			?>
			
			<li>
				<h3 class='upperCase'>WORK EXPERIENCE</h3>
			<?php
			$i = 0;
			foreach($workCompanies as $key=>$workCompaniesRow)
			{
			?>
				<div class='additionalInfoLeftCol' style="margin-top:10px;">
			
				<label>Monthly Remuneration for <?php echo $workCompaniesRow;?>: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MonthlyRemunerationIMDR[]' id='MonthlyRemunerationIMDR_<?=$key?>'  minlength="2" maxlength="10" required="true" validate="validateFloat" minlength="1" caption="monthly remuneration"        tip="Monthly Remuneration"   value=''   />
				<?php
				if(isset($MonthlyRemunerationIMDRValues[$i]) && $MonthlyRemunerationIMDRValues[$i]!=""){ ?>
				  <script>
				      document.getElementById("MonthlyRemunerationIMDR_<?=$key?>").value = "<?php echo str_replace("\n", '\n', $MonthlyRemunerationIMDRValues[$i] );  ?>";
				      document.getElementById("MonthlyRemunerationIMDR_<?=$key?>").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MonthlyRemunerationIMDR_<?=$key?>_error'></div></div>
				</div>
				</div>
				
				<div class='additionalInfoLeftCol' style="margin-top:10px;">

				<label>Reason for leaving <?php echo $workCompaniesRow;?>: </label>

				<div class='fieldBoxLarge'>

				<input type='text' name='ReasonforleavingIMDR[]' id='ReasonforleavingIMDR_<?=$key?>' required="true" minlength="2" maxlength="40" validate="validateStr" caption="reason for leaving"         tip="Reason for leaving"   value=''   />

				<?php if(isset($ReasonforleavingIMDRValues[$i]) && $ReasonforleavingIMDRValues[$i]!=""){ ?>
				  <script>
				      document.getElementById("ReasonforleavingIMDR_<?=$key?>").value = "<?php echo str_replace("\n", '\n', $ReasonforleavingIMDRValues[$i] );  ?>";
				      document.getElementById("ReasonforleavingIMDR_<?=$key?>").style.color = "";
				  </script>
				<?php } ?>

				

				<div style='display:none'><div class='errorMsg' id= 'ReasonforleavingIMDR_<?=$key?>_error'></div></div>

				</div>

				</div>
			<?php
				$i++;
			}
			?>
			</li>
<?php
			} //end of workCompanies check
			?>
			
			
			<li>
				<h3 class=upperCase'>DECLARATION</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				I declare that the information given by me in this application is true to the best of my knowledge. I agree to comply with the rules of the Institute, if admitted.<br><br>
				I have carefully noted the rules, regulations etc. (as given in the Prospectus received along with the form) which I am required to follow. I have also noted that the Director's decision in all respects will be final and binding. Refund of tuition fee will be subject to AICTE guidelines.<br><br>
				I shall observe and abide by the rules made by the Head of the Institute and other regulatory bodies from time to time.
				</div>
				
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='IMDR_agreeToTerms[]' id='IMDR_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($IMDR_agreeToTerms) && $IMDR_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["IMDR_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$IMDR_agreeToTerms);
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
				<div style="display:none;">
					<div  class="errorMsg" id= 'IMDR_agreeToTerms0_error'></div>
				</div>
				</div>
				</div>
				
			</li>
			
			<?php endif; ?>


			
			<li>
				<h3 class="upperCase">INSTRUCTIONS</h3>
				<div class="additionalInfoLeftCol">
				<div class="fieldBoxLarge" style="width:950px;">
				

1. Attestation of Mark sheets may be done by the Principal / Registrar / Head of your Department / College / University / Institute / an MP / MLA or Gazetted Officer.<br/>
2. Eligibility Criteria : Graduates from any discipline with or without work experience are eligible to apply for admission to this programme, provided they have obtained not less than 50% marks at graduation. Final year graduate students are eligible, provided they have scored not less than 50% marks at the immediately preceding examination. (45% in case of candidates of backward class categories and physically handicapped belonging to Maharashtra State only) Candidates can apply through CAT / MAT / XAT / CMAT / ATMA / MH-CET. <br/>
3. Admission to the Programme on the basis of incomplete and / or false information will stand automatically cancelled.<br/>
4. Incomplete applications shall not be considered for the purpose of selection. Applicants are, therefore, advised to ensure in their own interest, that all relevant documents are produced at the time of selection process. Candidates are also advised not to leave any mandatory fields blank.<br/>
5. Use Internet Explorer for online application. In case of problem you may download, print, fill up the form and send it with required documents to the institute.<br/>
6. The decision of the Director in all matters relating to admission will be final and binding.<br/>


				
				</div>
				</div>
			</li>

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