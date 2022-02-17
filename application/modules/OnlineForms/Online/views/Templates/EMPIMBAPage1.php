<script>
    function checkTestScore(obj){
        var key = obj.value.toLowerCase();
        if(obj.value == "CMAT"){
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileEMPI");
        }else{
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileAdditional");
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

	function setTitle(obj){
        var key = obj.value.toLowerCase();
        if(key == "father"){
            $('EMPIGuardianTitle').value = 'Mr.';
        }else if(key == "mother"){
            $('EMPIGuardianTitle').value = 'Ms.';
        }
	}

	function setReferred(obj){
        if(obj.value == "Person"){
			$("coach1").style.display = '';
			$("coach2").style.display = '';
			$("coachInstitute1").style.display = 'none';
			$("coachInstitute2").style.display = 'none';
			$("otherSource").style.display = 'none';
            var objects1 = new Array("EMPIName","EMPIAddress","EMPITel","EMPIEmail");
			setExamFields(objects1);
			var objects2 = new Array("EMPIOtherSource","EMPICoachingName","EMPICoachingAddress","EMPICoachingTel","EMPICoachingEmail");
			resetExamFields(objects2);
			clearOtherSource();
			clearCoachInstituteValues();
        } else if(obj.value == "Coaching Center"){
			$("coach1").style.display = 'none';
			$("coach2").style.display = 'none';
			$("coachInstitute1").style.display = '';
			$("coachInstitute2").style.display = '';
			$("otherSource").style.display = 'none';
            var objects1 = new Array("EMPICoachingName","EMPICoachingAddress","EMPICoachingTel","EMPICoachingEmail");
			setExamFields(objects1);
			var objects2 = new Array("EMPIOtherSource","EMPIName","EMPIAddress","EMPITel","EMPIEmail");
			resetExamFields(objects2);
			clearOtherSource();
			clearCoachValues();
        }else if(obj.value == "Other"){
			$("coach1").style.display = 'none';
			$("coach2").style.display = 'none';
			$("coachInstitute1").style.display = 'none';
			$("coachInstitute2").style.display = 'none';
			$("otherSource").style.display = '';
            var objects1 = new Array("EMPIName","EMPIAddress","EMPITel","EMPIEmail","EMPICoachingName","EMPICoachingAddress","EMPICoachingTel","EMPICoachingEmail");
			resetExamFields(objects1);
			var objects2 = new Array("EMPIOtherSource");
			setExamFields(objects2);
			clearCoachValues();
			clearCoachInstituteValues();
        }else{
			$("coach1").style.display = 'none';
			$("coach2").style.display = 'none';
			$("coachInstitute1").style.display = 'none';
			$("coachInstitute2").style.display = 'none';
			$("otherSource").style.display = 'none';
            var objects1 = new Array("EMPIName","EMPIAddress","EMPITel","EMPIEmail","EMPICoachingName","EMPICoachingAddress","EMPICoachingTel","EMPICoachingEmail");
			resetExamFields(objects1);
			var objects2 = new Array("EMPIOtherSource");
			resetExamFields(objects2);
			clearOtherSource();
			clearCoachValues();
			clearCoachInstituteValues();
		}
	}

	function clearCoachValues(){
		$('EMPIName').value = '';
		$('EMPIAddress').value = '';
		$('EMPITel').value = '';
		$('EMPIEmail').value = '';
	}

	function clearOtherSource(){
		$('EMPIOtherSource').value = '';
	}

	function clearCoachInstituteValues(){
		$('EMPICoachingName').value = '';
		$('EMPICoachingAddress').value = '';
		$('EMPICoachingTel').value = '';
		$('EMPICoachingEmail').value = '';
	}

</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

            <?php if($action != 'updateScore'):?>
			<li>
                <h3 class="upperCase">Additional personal Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Native place: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPINativePlace' id='EMPINativePlace'  validate="validateStr"   required="true"   caption="native place"   minlength="1"   maxlength="50"     tip="Enter the name of your native place, i.e. the city that you originally belong to."   value=''   />
				<?php if(isset($EMPINativePlace) && $EMPINativePlace!=""){ ?>
				  <script>
				      document.getElementById("EMPINativePlace").value = "<?php echo str_replace("\n", '\n', $EMPINativePlace );  ?>";
				      document.getElementById("EMPINativePlace").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPINativePlace_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Age: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPIAge' id='EMPIAge'  validate="validateInteger"   required="true"   caption="age"   minlength="1"   maxlength="2"     tip="Enter your age in years as on today"   value=''   />
				<?php if(isset($EMPIAge) && $EMPIAge!=""){ ?>
				  <script>
				      document.getElementById("EMPIAge").value = "<?php echo str_replace("\n", '\n', $EMPIAge );  ?>";
				      document.getElementById("EMPIAge").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIAge_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Category: </label>
				<div class='fieldBoxLarge'>
				<select name='EMPICategory' id='EMPICategory'    tip="Please select a category that's appropriate to you"    validate="validateSelect"   required="true"   caption="category"   onmouseover="showTipOnline('Please select a category that\'s appropriate to you',this);" onmouseout="hidetip();" >
				<option value='' selected>Select</option><option value='General' >General</option><option value='NRI' >NRI</option><option value='Company Sponsored' >Company Sponsored</option><option value='Dependent of defence personnel' >Dependent of defence personnel</option></select>
				<?php if(isset($EMPICategory) && $EMPICategory!=""){ ?>
			      <script>
				  var selObj = document.getElementById("EMPICategory"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $EMPICategory;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPICategory_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Who's your legal guardian: </label>
				<div class='fieldBoxLarge'>
				<select style="width:150px;" onChange="setTitle(this);" name='EMPIGuardian' id='EMPIGuardian'    tip="Please select who's your legal guardian"    validate="validateSelect"   required="true"   caption="guardian"   onmouseover="showTipOnline('Please select who\'s your legal guardian',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Father' >Father</option><option value='Mother' >Mother</option></select>
				<?php if(isset($EMPIGuardian) && $EMPIGuardian!=""){ ?>
			      <script>
				  var selObj = document.getElementById("EMPIGuardian"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $EMPIGuardian;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIGuardian_error'></div></div>
				</div>
				</div>
			</li>

			<input type='hidden' name='EMPITitle' id='EMPITitle'    value='<?php if($EMPIgender=='MALE' || $EMPIgender == 'Male'){echo "Mr.";} else {echo "Ms.";} ?>'>
			<?php if(isset($EMPITitle) && $EMPITitle!=""){ ?>
				  <script>
				      document.getElementById("EMPITitle").value = "<?php echo str_replace("\n", '\n', $EMPITitle );  ?>";
				      document.getElementById("EMPITitle").style.color = "";
				  </script>
			<?php } ?>

			<input type='hidden' name='EMPIgender' id='EMPIgender'    value='<?=$EMPIgender?>'>
			<input type='hidden' name='EMPIGuardianTitle' id='EMPIGuardianTitle'    value=''>
			<script>setTitle($('EMPIGuardian'));</script>



			<li>
                <h3 class="upperCase">Additional personal Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Graduation Completed ?: </label>
				<div class='fieldBoxLarge'>
				<select style="width:150px;" name='EMPIGraduationCompleted' id='EMPIGraduationCompleted'    tip="Specify whether you've completed your graduation or not"    validate="validateSelect"   required="true"   caption="an option"   onmouseover="showTipOnline('Specify whether you\'ve completed your graduation or not',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Yes' >Yes</option><option value='No' >No</option></select>
				<?php if(isset($EMPIGraduationCompleted) && $EMPIGraduationCompleted!=""){ ?>
			      <script>
				  var selObj = document.getElementById("EMPIGraduationCompleted"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $EMPIGraduationCompleted;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIGraduationCompleted_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Work Experience in Year(s): </label>
				<div class='fieldBoxLarge'>
				<select style="width:150px;" name='EMPIWorkExperience' id='EMPIWorkExperience'    tip="Please select your work experience (in years)"    validate="validateSelect"   required="true"   caption="year (s)"   onmouseover="showTipOnline('Please select your work experience (in years)',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='0' >0</option><option value='1' >1</option><option value='1.5' >1.5</option><option value='2' >2</option><option value='2.5' >2.5</option><option value='3' >3</option><option value='3.5' >3.5</option><option value='4' >4</option><option value='4.5' >4.5</option><option value='5' >5</option></select>
				<?php if(isset($EMPIWorkExperience) && $EMPIWorkExperience!=""){ ?>
			      <script>
				  var selObj = document.getElementById("EMPIWorkExperience"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $EMPIWorkExperience;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIWorkExperience_error'></div></div>
				</div>
				</div>
			</li>


			<li>
                <h3 class="upperCase">Other</h3>
				<div class='additionalInfoLeftCol'>
				<label>Referred By: </label>
				<div class='fieldBoxLarge'>
				<select style="width:150px;" onChange="setReferred(this);" name='EMPIReferredBy' id='EMPIReferredBy'    tip="Please specify who referred you to EMPI"    validate="validateSelect"   required="true"   caption="an option"   onmouseover="showTipOnline('Please specify who referred you to EMPI',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Person' >Person</option><option value='Coaching Center' >Coaching Center</option><option value='Other' >Other</option></select>
				<?php if(isset($EMPIReferredBy) && $EMPIReferredBy!=""){ ?>
			      <script>
				  var selObj = document.getElementById("EMPIReferredBy"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $EMPIReferredBy;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIReferredBy_error'></div></div>
				</div>
				</div>
			</li>

			<li id="coach1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPIName' id='EMPIName'   validate="validateStr" caption="name" minlength="1" maxlength="50"       tip="Please enter the name of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPIName) && $EMPIName!=""){ ?>
				  <script>
				      document.getElementById("EMPIName").value = "<?php echo str_replace("\n", '\n', $EMPIName );  ?>";
				      document.getElementById("EMPIName").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIName_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Telephone number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPITel' id='EMPITel'     validate="validateInteger" caption="number" minlength="6" maxlength="12"     tip="Please enter the Telephone number of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPITel) && $EMPITel!=""){ ?>
				  <script>
				      document.getElementById("EMPITel").value = "<?php echo str_replace("\n", '\n', $EMPITel );  ?>";
				      document.getElementById("EMPITel").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPITel_error'></div></div>
				</div>
				</div>
			</li>

			<li id="coach2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPIEmail' id='EMPIEmail'      validate="validateEmail" caption="email" minlength="1" maxlength="50"    tip="Please enter the email of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPIEmail) && $EMPIEmail!=""){ ?>
				  <script>
				      document.getElementById("EMPIEmail").value = "<?php echo str_replace("\n", '\n', $EMPIEmail );  ?>";
				      document.getElementById("EMPIEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIEmail_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPIAddress' id='EMPIAddress'    validate="validateStr" caption="address" minlength="1" maxlength="100"     tip="Please enter the address of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPIAddress) && $EMPIAddress!=""){ ?>
				  <script>
				      document.getElementById("EMPIAddress").value = "<?php echo str_replace("\n", '\n', $EMPIAddress );  ?>";
				      document.getElementById("EMPIAddress").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIAddress_error'></div></div>
				</div>
				</div>
			</li>

			<li id="coachInstitute1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Name: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPICoachingName' id='EMPICoachingName'   validate="validateStr" caption="name" minlength="1" maxlength="50"       tip="Please enter the name of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPICoachingName) && $EMPICoachingName!=""){ ?>
				  <script>
				      document.getElementById("EMPICoachingName").value = "<?php echo str_replace("\n", '\n', $EMPICoachingName );  ?>";
				      document.getElementById("EMPICoachingName").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPICoachingName_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Telephone number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPICoachingTel' id='EMPICoachingTel'     validate="validateInteger" caption="number" minlength="6" maxlength="12"     tip="Please enter the Telephone number of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPICoachingTel) && $EMPICoachingTel!=""){ ?>
				  <script>
				      document.getElementById("EMPICoachingTel").value = "<?php echo str_replace("\n", '\n', $EMPICoachingTel );  ?>";
				      document.getElementById("EMPICoachingTel").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPICoachingTel_error'></div></div>
				</div>
				</div>
			</li>

			<li id="coachInstitute2" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Email: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPICoachingEmail' id='EMPICoachingEmail'      validate="validateEmail" caption="email" minlength="1" maxlength="50"    tip="Please enter the email of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPICoachingEmail) && $EMPICoachingEmail!=""){ ?>
				  <script>
				      document.getElementById("EMPICoachingEmail").value = "<?php echo str_replace("\n", '\n', $EMPICoachingEmail );  ?>";
				      document.getElementById("EMPICoachingEmail").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPICoachingEmail_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Address: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='EMPICoachingAddress' id='EMPICoachingAddress'    validate="validateStr" caption="address" minlength="1" maxlength="100"     tip="Please enter the address of person or coaching center that referred you"   value=''   />
				<?php if(isset($EMPICoachingAddress) && $EMPICoachingAddress!=""){ ?>
				  <script>
				      document.getElementById("EMPICoachingAddress").value = "<?php echo str_replace("\n", '\n', $EMPICoachingAddress );  ?>";
				      document.getElementById("EMPICoachingAddress").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPICoachingAddress_error'></div></div>
				</div>
				</div>
			</li>

			<li id="otherSource" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>Select source: </label>
				<div class='fieldBoxLarge'>
				<select style="width:150px;" name='EMPIOtherSource' id='EMPIOtherSource'    tip="Please select the source"    validate="validateSelect"     caption="source"    onmouseover="showTipOnline('Please select the source',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='Shiksha.com' >Shiksha.com</option><option value='Newspaper' >Newspaper</option><option value='Magazine' >Magazine</option><option value='Career Fair' >Career Fair</option><option value='EMPI Website' >EMPI Website</option><option value='Electronic media' >Electronic media</option></select>
				<?php if(isset($EMPIOtherSource) && $EMPIOtherSource!=""){ ?>
			      <script>
				  var selObj = document.getElementById("EMPIOtherSource"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $EMPIOtherSource;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'EMPIOtherSource_error'></div></div>
				</div>
				</div>
			</li>
			<script>setReferred($('EMPIReferredBy'));</script>



            <?php endif; ?>

            <li>
                <h3 class="upperCase">Exams</h3>
                <div class='additionalInfoLeftCol' style="width:670px;">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:320px;">
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='EMPITestNames[]' id='EMPITestNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile(if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='EMPITestNames[]' id='EMPITestNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='EMPITestNames[]' id='EMPITestNames2'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='EMPITestNames[]' id='EMPITestNames3'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='EMPITestNames[]' id='EMPITestNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                        <?php if(isset($EMPITestNames) && $EMPITestNames!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["EMPITestNames[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$EMPITestNames);
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

                        <div style='display:none'><div class='errorMsg' id= 'EMPITestNames_error'></div></div>
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
            <?php
            if(isset($EMPITestNames) && $EMPITestNames!="" && strpos($EMPITestNames,'CAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('EMPITestNames0'));
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
            <?php
            if(isset($EMPITestNames) && $EMPITestNames!="" && strpos($EMPITestNames,'XAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('EMPITestNames1'));
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
            <?php
            if(isset($EMPITestNames) && $EMPITestNames!=""){
                $tests = explode(',',$EMPITestNames);
                foreach ($tests as $test){
                    if($test=='MAT'){
                        ?>
                    <script>
                        checkTestScore(document.getElementById('EMPITestNames2'));
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
            <?php
            if(isset($EMPITestNames) && $EMPITestNames!="" && strpos($EMPITestNames,'ATMA')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('EMPITestNames3'));
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
                        <input type='text' name='cmatPercentileEMPI' id='cmatPercentileEMPI'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
                        <?php if(isset($cmatPercentileEMPI) && $cmatPercentileEMPI!=""){ ?>
                        <script>
                            document.getElementById("cmatPercentileEMPI").value = "<?php echo str_replace("\n", '\n', $cmatPercentileEMPI );  ?>";
                            document.getElementById("cmatPercentileEMPI").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'cmatPercentileEMPI_error'></div></div>
                    </div>
                </div>

            </li>
            <?php
            if(isset($EMPITestNames) && $EMPITestNames!="" && strpos($EMPITestNames,'CMAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('EMPITestNames4'));
            </script>
                <?php
            }
            ?>

            <?php if($action != 'updateScore'):?>
            <?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
            <li>
                <h3 class=upperCase'>GD/PI LOCATION</h3>
                <label style='font-weight:normal'>Preferred GD/PI location: </label>
                <div class='fieldBoxLarge'>
                    <select onChange='fillDate(this);' style="width:150px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
                <div class="additionalInfoLeftCol" style="width:950px">
                    <label style="font-weight:normal; padding-top:0">Terms:</label>
                    <div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
                        I declare that the information furnished by me is my true personal response.
                        <div class="spacer10 clearFix"></div>
                        <div>
                            <input type='checkbox' name='agreeToTermsEMPI' id='agreeToTermsEMPI' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above

                            <?php if(isset($agreeToTermsEMPI) && $agreeToTermsEMPI!=""){ ?>
                            <script>
                                objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsEMPI"];
                                var countCheckBoxes = 1;
                                for(var i = 0; i < countCheckBoxes; i++){
                                    objCheckBoxes.checked = false;
                                    <?php $arr = explode(",",$agreeToTermsEMPI);
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
                            <div style='display:none'><div class='errorMsg' id= 'agreeToTermsEMPI_error'></div></div>


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
