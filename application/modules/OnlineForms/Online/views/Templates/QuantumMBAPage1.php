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
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }
  
</script>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>  

                      <li>
				<h3 class="upperCase">Admission Test Results</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Examination Taken: </label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Quantum_testNames[]' id='Quantum_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Quantum_testNames[]' id='Quantum_testNames1'   value='MAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" >MAT</span>&nbsp;&nbsp;
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Quantum_testNames[]' id='Quantum_testNames3'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input type='checkbox'   onClick="checkTestScore(this);" validate="validateCheckedGroup"   required="true"   caption="tests"   name='Quantum_testNames[]' id='Quantum_testNames2'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test rollno and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				
				<?php if(isset($Quantum_testNames) && $Quantum_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Quantum_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Quantum_testNames);

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
				
				<div style='display:none'><div class='errorMsg' id= 'Quantum_testNames_error'></div></div>
				</div>
				</div>
			</li>
			
			
			

			<li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT ROLLNO: </label>
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
				
				
			</li>
			

			<li id="mat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>MAT ROLLNO: </label>
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
				
				
			</li>
			

			<li id="xat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>XAT ROLLNO: </label>
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
				
				
			</li>

			<li id="cmat1" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CMAT ROLLNO: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'         tip="Mention your roll number for the exam."   validate="validateStr"    caption="roll no"   minlength="2"   maxlength="50"    value=''   />
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

			<li id="cmat2" style="display:none; border-bottom:1px dotted #c0c0c0; padding-bottom:15px">

				<div class='additionalInfoLeftCol'>
				<label>CMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'  validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"     tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter <b>NA.</b>"   value=''   allowNA='true' />
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


                       
			<?php if($action != 'updateScore'):?>
						
			
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<div class="additionalInfoLeftCol">
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select style="width:100px;" name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
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
			</li>
			<?php endif; ?>
                        <?php endif; ?>
	
						
			
			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class=upperCase'>Undertaking</h3>
				<label style="font-weight:normal; padding-top:0">Undertaking:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
	                        		I hereby untertake that the entries filled by me in the form are true to best of my knowledge and belief. I have secured 50% or more marks in Qualifying Examination for MBA for Registration.<BR />
                                I am aware of the fact that in case of any wrong declaration , my registration/admission will stand cancelled & fee will be forfeited and I will have no objection to the same, besides rendering meliable to such action as the school/university may deem proper.<BR />
<div style="margin-top:10px">
It may kindly be noted that registration has no bearing on the admissions of the student, which solely depends upon the merit and/or availablity of vacant seats after the central counseling Attested photocopies of the certificates must be enclosed for the testmony of the eligibility, along with your rank/score card if any.
</div>
</div>

				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='agreeToTermsQuantum[]' id='agreeToTermsQuantum'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsQuantum) && $agreeToTermsQuantum!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsQuantum[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$agreeToTermsQuantum);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsQuantum_error'></div></div>
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
		checkTestScore(document.getElementById('Quantum_testNames'+j));
	}

  </script>
