<script>
    function fillDate(obj){
        var selectedPref = obj.options[obj.selectedIndex].value;
        var elSel = document.getElementById('MITCONGDPIDate');
        elSel.options.length = 0;

        if(selectedPref=='174'){  //Pune is selected
            if(navigator.appName=="Microsoft Internet Explorer"){
                addOption('Select','');
                addOption('30-March-2013','2013-03-30');
            }
            else{
                addOption('30-March-2013','2013-03-30');
                addOption('Select','');
            }
        }
        else if(selectedPref=='45'){  //Aurangabad is selected
            if(navigator.appName=="Microsoft Internet Explorer"){
                addOption('Select','');
                addOption('23-March-2013','2013-03-23');
            }
            else{
                addOption('23-March-2013','2013-03-23');
                addOption('Select','');
            }
        }
        else if(selectedPref=='129'){  //Kolhapur is selected
            if(navigator.appName=="Microsoft Internet Explorer"){
                addOption('Select','');
                addOption('30-March-2013','2013-03-30');
                addOption('31-March-2013','2013-03-31');
            }
            else{
                addOption('31-March-2013','2013-03-31');
                addOption('30-March-2013','2013-03-30');
                addOption('Select','');
            }
        }
        else if(selectedPref=='151'){  //Mumbai is selected
            if(navigator.appName=="Microsoft Internet Explorer"){
                addOption('Select','');
                addOption('25-March-2013','2013-03-25');
                addOption('30-March-2013','2013-03-30');
            }
            else{
                addOption('30-March-2013','2013-03-30');
                addOption('25-March-2013','2013-03-25');
                addOption('Select','');
            }
        }
        else if(selectedPref=='156'){  //Nagpur is selected
            if(navigator.appName=="Microsoft Internet Explorer"){
                addOption('Select','');
                addOption('28-March-2013','2013-03-28');
            }
            else{
                addOption('28-March-2013','2013-03-28');
                addOption('Select','');
            }
        }
        else{ //Nashik
            if(navigator.appName=="Microsoft Internet Explorer"){
                addOption('Select','');
                addOption('25-March-2013','2013-03-25');
            }
            else{
                addOption('25-March-2013','2013-03-25');
                addOption('Select','');
            }
        }

        //Also clear the GDPI Date error message
        $('MITCONGDPIDate_error').innerHTML = '';
        $('MITCONGDPIDate_error').parentNode.style.display = 'none';

    }

    function addOption(text,value){
        var elSel = document.getElementById('MITCONGDPIDate');
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
        if(obj.value == "CMAT"){
            var objects1 = new Array(key+"ScoreAdditional",key+"PercentileMITCON");
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

</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>

            <?php if($action != 'updateScore'):?>
			<li>
                <h3 class="upperCase">Course Details</h3>
				<div class='additionalInfoLeftCol' style='width:630px;'>
				<label>Select Course: </label>
				<div class='fieldBoxLarge' style="width:280px;">
				<input type='radio' selected  required="true"   name='MITCONcourse' id='MITCONcourse0'   value='PGDM: Business Administration'  checked   onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" >PGDM: Business Administration</span>&nbsp;&nbsp;<br/>
				<input type='radio'   required="true"   name='MITCONcourse' id='MITCONcourse1'   value='PGDM: Banking and Financial Services'     onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" >PGDM: Banking and Financial Services</span>&nbsp;&nbsp;<br/>
				<input type='radio'   required="true"   name='MITCONcourse' id='MITCONcourse2'   value='PGDM: Agribusiness Management'     onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" >PGDM: Agribusiness Management</span>&nbsp;&nbsp;<br/>
				<input type='radio'   required="true"   name='MITCONcourse' id='MITCONcourse3'   value='PGDM: Pharmaceutical Management'     onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select the course that you wish to apply to. You can select only one course.',this);" onmouseout="hidetip();" >PGDM: Pharmaceutical Management</span>&nbsp;&nbsp;<br/>
				<?php if(isset($MITCONcourse) && $MITCONcourse!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["MITCONcourse"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $MITCONcourse;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONcourse_error'></div></div>
				</div>
				</div>
			</li>

			<li>
                <h3 class="upperCase">Additional personal Information</h3>
				<div class='additionalInfoLeftCol'>
				<label>Blood Group: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MITCONBloodgroup' id='MITCONBloodgroup'  validate="validateStr"   required="true"   caption="blood group"   minlength="1"   maxlength="3"     tip="Enter your blood group. If you are not sure about your blood group, just enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($MITCONBloodgroup) && $MITCONBloodgroup!=""){ ?>
				  <script>
				      document.getElementById("MITCONBloodgroup").value = "<?php echo str_replace("\n", '\n', $MITCONBloodgroup );  ?>";
				      document.getElementById("MITCONBloodgroup").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONBloodgroup_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Emergency contact number (with STD code): </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MITCONEmergencyNumber' id='MITCONEmergencyNumber'  validate="validateStr"   required="true"   caption="contact number"   minlength="8"   maxlength="15"     tip="Enter the phone number of the person to whom the Institute can contact in case of emergency."   value=''   />
				<?php if(isset($MITCONEmergencyNumber) && $MITCONEmergencyNumber!=""){ ?>
				  <script>
				      document.getElementById("MITCONEmergencyNumber").value = "<?php echo str_replace("\n", '\n', $MITCONEmergencyNumber );  ?>";
				      document.getElementById("MITCONEmergencyNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONEmergencyNumber_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Passport Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MITCONPassport' id='MITCONPassport'  validate="validateStr"   required="true"   caption="passport"   minlength="6"   maxlength="10"     tip="Enter your current passport number. If you do not have a passport, just enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($MITCONPassport) && $MITCONPassport!=""){ ?>
				  <script>
				      document.getElementById("MITCONPassport").value = "<?php echo str_replace("\n", '\n', $MITCONPassport );  ?>";
				      document.getElementById("MITCONPassport").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONPassport_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>PAN Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='MITCONPanNumber' id='MITCONPanNumber'  validate="validateStr"   required="true"   caption="pan number"   minlength="10"   maxlength="10"     tip="Enter your PAN number (Permanent Account Number). If you do not have a PAN card, just enter <b>NA</b>."   value=''    allowNA = 'true' />
				<?php if(isset($MITCONPanNumber) && $MITCONPanNumber!=""){ ?>
				  <script>
				      document.getElementById("MITCONPanNumber").value = "<?php echo str_replace("\n", '\n', $MITCONPanNumber );  ?>";
				      document.getElementById("MITCONPanNumber").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONPanNumber_error'></div></div>
				</div>
				</div>
			</li>

			<li>
                <h3 class="upperCase">Additional Education Info</h3>
				<div class='additionalInfoLeftCol'>
				<label>Passing Examination: </label>
				<div class='fieldBoxLarge'>
				<input type='radio' selected  required="true"   name='MITCONPassingExam' id='MITCONPassingExam0'   value='Within Maharashta'  checked   onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" >Within Maharashta</span>&nbsp;&nbsp;<br/>
				<input type='radio'   required="true"   name='MITCONPassingExam' id='MITCONPassingExam1'   value='Outside Maharashta'     onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" >Outside Maharashta</span>&nbsp;&nbsp;<br/>
				<input type='radio'   required="true"   name='MITCONPassingExam' id='MITCONPassingExam2'   value='NRI-PIO'     onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" >NRI-PIO</span>&nbsp;&nbsp;<br/>
				<input type='radio'   required="true"   name='MITCONPassingExam' id='MITCONPassingExam3'   value='Foreign'     onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Select from where have you passed your qualifying examination like graduation or PG.',this);" onmouseout="hidetip();" >Foreign</span>&nbsp;&nbsp;<br/>
				<?php if(isset($MITCONPassingExam) && $MITCONPassingExam!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["MITCONPassingExam"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $MITCONPassingExam;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONPassingExam_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol' style="width:620px;">
				<label>Preferred Mode of Payment: </label>
				<div class='fieldBoxLarge' style="width:240px;">
				<input type='radio' selected  required="true"   name='MITCONModeOfPayment' id='MITCONModeOfPayment0'   value='Own Source'  checked   onmouseover="showTipOnline('Please select how you plan to fund your education at MITCON, if selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select how you plan to fund your education at MITCON, if selected.',this);" onmouseout="hidetip();" >Own Source</span>&nbsp;&nbsp;
				<input type='radio'   required="true"   name='MITCONModeOfPayment' id='MITCONModeOfPayment1'   value='Education loan'     onmouseover="showTipOnline('Please select how you plan to fund your education at MITCON, if selected.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select how you plan to fund your education at MITCON, if selected.',this);" onmouseout="hidetip();" >Education loan</span>&nbsp;&nbsp;
				<?php if(isset($MITCONModeOfPayment) && $MITCONModeOfPayment!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["MITCONModeOfPayment"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $MITCONModeOfPayment;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONModeOfPayment_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Is the hostel facility required?: </label>
				<div class='fieldBoxLarge'>
				<input type='radio'   required="true"   name='MITCONHostelRequired' id='MITCONHostelRequired0'   value='Yes'  checked   onmouseover="showTipOnline('Enter your choice for hostel facility, if selected to MITCON. If you require hostel, select YES, else select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your choice for hostel facility, if selected to MITCON. If you require hostel, select YES, else select NO.',this);" onmouseout="hidetip();" >Yes</span>&nbsp;&nbsp;
				<input selected type='radio'   required="true"   name='MITCONHostelRequired' id='MITCONHostelRequired1'   value='No'     onmouseover="showTipOnline('Enter your choice for hostel facility, if selected to MITCON. If you require hostel, select YES, else select NO.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Enter your choice for hostel facility, if selected to MITCON. If you require hostel, select YES, else select NO.',this);" onmouseout="hidetip();" >No</span>&nbsp;&nbsp;
				<?php if(isset($MITCONHostelRequired) && $MITCONHostelRequired!=""){ ?>
				  <script>
				      radioObj = document.forms["OnlineForm"].elements["MITCONHostelRequired"];
				      var radioLength = radioObj.length;
				      for(var i = 0; i < radioLength; i++) {
					      radioObj[i].checked = false;
					      if(radioObj[i].value == "<?php echo $MITCONHostelRequired;?>") {
						      radioObj[i].checked = true;
					      }
				      }
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONHostelRequired_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Computer Literacy: </label>
				<div class='fieldBoxLarge'>
				<select name='MITCONComputer' id='MITCONComputer'    tip="Select your level of computer proficiency"     required="true"  validate='validateSelect' minlength='1' maxlength='100' caption='computer literacy'  onmouseover="showTipOnline('Select your level of computer proficiency',this);" onmouseout="hidetip();" ><option value='' selected>Select</option><option value='I have basic understanding of computers' >I have basic understanding of computers</option><option value='I know about Windows and MS Office' >I know about Windows and MS Office</option><option value='I know programming and OS' >I know programming and OS</option><option value='I do not have any understanding of computers' >I do not have any understanding of computers</option></select>
				<?php if(isset($MITCONComputer) && $MITCONComputer!=""){ ?>
			      <script>
				  var selObj = document.getElementById("MITCONComputer"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $MITCONComputer;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'MITCONComputer_error'></div></div>
				</div>
				</div>
			</li>

            <li>
                <h3 class="upperCase">Other Information</h3>
                <div class='additionalInfoLeftCol' style="width:750px;">
                    <label>Sports / Extra Curricular Activities: </label>
                    <div class='fieldBoxLarge' style="width:400px;">
                        <textarea name='MITCONExtraCurricular' id='MITCONExtraCurricular'  validate="validateStr"   required="true"   caption="sports / extra curricular activities"   minlength="50"   maxlength="500"     tip="Enter your Sports / Extra Curricular activities"  style="width:600px; padding:5px"   ></textarea>
                        <?php if(isset($MITCONExtraCurricular) && $MITCONExtraCurricular!=""){ ?>
                        <script>
                            document.getElementById("MITCONExtraCurricular").value = "<?php echo str_replace("\n", '\n', $MITCONExtraCurricular );  ?>";
                            document.getElementById("MITCONExtraCurricular").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'MITCONExtraCurricular_error'></div></div>
                    </div>
                </div>
            </li>


            <li>
                <div class='additionalInfoLeftCol' style="width:910px;">
                    <label>From where did you come to know about MITCON?: </label>
                    <div class='fieldBoxLarge' style="width:600px;">
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation0'   value='Shiksha.com'   ></input><span >Shiksha.com</span>&nbsp;&nbsp;
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation1'   value='Print Media'   ></input><span >Print Media</span>&nbsp;&nbsp;
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation2'   value='Friends'    ></input><span >Friends</span>&nbsp;&nbsp;
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation3'   value='Website'    ></input><span >Website</span>&nbsp;&nbsp;
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation4'   value='Exhibition'    ></input><span >Exhibition</span>&nbsp;&nbsp;
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation5'   value='Alumni'    ></input><span >Alumni</span>&nbsp;&nbsp;<br/>
                        <input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="source of information"   name='MITCONSourceOfInformation[]' id='MITCONSourceOfInformation6'   value='any other Source'    ></input><span >any other Source</span>&nbsp;&nbsp;
                        <?php if(isset($MITCONSourceOfInformation) && $MITCONSourceOfInformation!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["MITCONSourceOfInformation[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$MITCONSourceOfInformation);
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

                        <div style='display:none'><div class='errorMsg' id= 'MITCONSourceOfInformation_error'></div></div>
                    </div>
                </div>
            </li>
            <?php endif; ?>

            <li>
                <h3 class="upperCase">Exams</h3>
                <div class='additionalInfoLeftCol' style="width:670px;">
                    <label>TESTS: </label>
                    <div class='fieldBoxLarge' style="width:320px;">
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='MITCONTestNames[]' id='MITCONTestNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile(if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='MITCONTestNames[]' id='MITCONTestNames1'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='MITCONTestNames[]' id='MITCONTestNames2'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='MITCONTestNames[]' id='MITCONTestNames3'   value='ATMA'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >ATMA</span>&nbsp;&nbsp;
                        <input onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='MITCONTestNames[]' id='MITCONTestNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test score and percentile (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
                        <?php if(isset($MITCONTestNames) && $MITCONTestNames!=""){ ?>
                        <script>
                            objCheckBoxes = document.forms["OnlineForm"].elements["MITCONTestNames[]"];
                            var countCheckBoxes = objCheckBoxes.length;
                            for(var i = 0; i < countCheckBoxes; i++){
                                objCheckBoxes[i].checked = false;

                                <?php $arr = explode(",",$MITCONTestNames);
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

                        <div style='display:none'><div class='errorMsg' id= 'MITCONTestNames_error'></div></div>
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
            if(isset($MITCONTestNames) && $MITCONTestNames!="" && strpos($MITCONTestNames,'CAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('MITCONTestNames0'));
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
            if(isset($MITCONTestNames) && $MITCONTestNames!="" && strpos($MITCONTestNames,'XAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('MITCONTestNames1'));
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
            if(isset($MITCONTestNames) && $MITCONTestNames!=""){
                $tests = explode(',',$MITCONTestNames);
                foreach ($tests as $test){
                    if($test=='MAT'){
                        ?>
                    <script>
                        checkTestScore(document.getElementById('MITCONTestNames2'));
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
            if(isset($MITCONTestNames) && $MITCONTestNames!="" && strpos($MITCONTestNames,'ATMA')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('MITCONTestNames3'));
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
                        <input type='text' name='cmatPercentileMITCON' id='cmatPercentileMITCON'         tip="Mention your Percentile in the exam. If you don't know your percentile, enter NA."   value='' allowNA='true' caption='percentile' minlength=1 maxlength=5 validate='validateFloat'  />
                        <?php if(isset($cmatPercentileMITCON) && $cmatPercentileMITCON!=""){ ?>
                        <script>
                            document.getElementById("cmatPercentileMITCON").value = "<?php echo str_replace("\n", '\n', $cmatPercentileMITCON );  ?>";
                            document.getElementById("cmatPercentileMITCON").style.color = "";
                        </script>
                        <?php } ?>

                        <div style='display:none'><div class='errorMsg' id= 'cmatPercentileMITCON_error'></div></div>
                    </div>
                </div>

            </li>
            <?php
            if(isset($MITCONTestNames) && $MITCONTestNames!="" && strpos($MITCONTestNames,'CMAT')!==false){ ?>
            <script>
                checkTestScore(document.getElementById('MITCONTestNames4'));
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

                <div class='additionalInfoRightCol'>
                    <label>GDPI Date: </label>
                    <div class='fieldBoxLarge'>
                        <select style="width:150px;" name='MITCONGDPIDate' id='MITCONGDPIDate'    tip="Select the GDPI Date. You will have to first select the GDPI Location."     required="true"  caption='date' validate='validateSelect' minlength='1' maxlength='100'  onmouseover="showTipOnline('Select the GDPI Date. You will have to first select the GDPI Location.',this);" onmouseout="hidetip();" ></select>
                        <div style='display:none'><div class='errorMsg' id= 'MITCONGDPIDate_error'></div></div>

                        <?php if(isset($MITCONGDPIDate) && $MITCONGDPIDate!=""){ ?>
                        <script>
                            fillDate(document.getElementById("preferredGDPILocation"));
                            var selObj = document.getElementById("MITCONGDPIDate");
                            var A= selObj.options, L= A.length;
                            while(L){
                                if (A[--L].value== "<?php echo $MITCONGDPIDate;?>"){
                                    selObj.selectedIndex= L;
                                    L= 0;
                                }
                            }
                        </script>
                        <?php } ?>

                    </div>
                </div>
            </li>
            <?php endif; ?>


            <li>
                <div class="additionalInfoLeftCol" style="width:950px">
                    <label style="font-weight:normal; padding-top:0">Terms:</label>
                    <div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
                        I hereby declare that the above given information is correct and true to the best of my knowledge and belief.
                        <div class="spacer10 clearFix"></div>
                        <div>
                            <input type='checkbox' name='agreeToTermsMITCON' id='agreeToTermsMITCON' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above">&nbsp;I agree to the terms stated above

                            <?php if(isset($agreeToTermsMITCON) && $agreeToTermsMITCON!=""){ ?>
                            <script>
                                objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsMITCON"];
                                var countCheckBoxes = 1;
                                for(var i = 0; i < countCheckBoxes; i++){
                                    objCheckBoxes.checked = false;
                                    <?php $arr = explode(",",$agreeToTermsMITCON);
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
                            <div style='display:none'><div class='errorMsg' id= 'agreeToTermsMITCON_error'></div></div>


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
