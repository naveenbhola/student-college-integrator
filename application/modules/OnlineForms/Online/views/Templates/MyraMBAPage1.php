<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array("Myra_"+key.toUpperCase()+"_score",'Myra_'+key.toUpperCase()+'_regNo',key+"RollNumberAdditional",key+"ScoreAdditional");
	if(obj && $(key)){
	      if( obj.checked == false ){
		    $(key).style.display = 'none';
		    //Set the required paramters when any Exam is hidden
		    resetExamFields(objects1);
	      }
	      else{
		    $(key).style.display = '';
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
			<li>
				<h3 class="upperCase">Application Form</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol'>
				<label>Course: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="course"   name='Myra_course[]' id='Myra_course0'   value='PGDM'    onmouseover="showTipOnline('Please select the desired course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the desired course.',this);" onmouseout="hidetip();" >PGDM</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="course"   name='Myra_course[]' id='Myra_course1'   value='PGPX'     onmouseover="showTipOnline('Please select the desired course.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the desired course.',this);" onmouseout="hidetip();" >PGPX</span>&nbsp;&nbsp;
				<?php if(isset($Myra_course) && $Myra_course!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Myra_course[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Myra_course);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Myra_course_error'></div></div>
				</div>
				</div>
			</li>

			<?php endif; ?>
			
			<li>
				<h3 class="upperCase">TESTS</h3>
				</li>
				<li>
				<div class='additionalInfoLeftCol' style="width:800px">
				<label>TESTS: </label>
				<div class='fieldBoxLarge' style="width:400px">
				<input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Myra_testNames[]' id='Myra_testNames0'   value='CAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				<input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Myra_testNames[]' id='Myra_testNames1'   value='GMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >GMAT</span>&nbsp;&nbsp;
				<input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Myra_testNames[]' id='Myra_testNames2'   value='XAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >XAT</span>&nbsp;&nbsp;
				<input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Myra_testNames[]' id='Myra_testNames3'   value='GRE'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >GRE</span>&nbsp;&nbsp;
                                <input  onClick="checkTestScore(this);" type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="tests"   name='Myra_testNames[]' id='Myra_testNames4'   value='CMAT'     onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration and score (if available)',this);" onmouseout="hidetip();" >CMAT</span>&nbsp;&nbsp;
				<?php if(isset($Myra_testNames) && $Myra_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Myra_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Myra_testNames);
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
				
				<div style='display:none'><div class='errorMsg' id= 'Myra_testNames_error'></div></div>
				</div>
				</div>
			</li>

			
			

			<li id="cat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>CAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'    validate="validateStr"    caption="roll number"   minlength="1"   maxlength="20"         tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA='true'  />
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
				<label>CAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='catScoreAdditional' id='catScoreAdditional'    validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"       tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''   allowNA='true'  />
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

			

			<li id="gmat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>GMAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatRollNumberAdditional' id='gmatRollNumberAdditional'   validate="validateStr"    caption="roll number"   minlength="1"   maxlength="20"            tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''  allowNA='true'   />
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
				<label>GMAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='gmatScoreAdditional' id='gmatScoreAdditional'    validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"        tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''   allowNA='true'  />
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

			<li id="xat" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>XAT Roll Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatRollNumberAdditional' id='xatRollNumberAdditional'   validate="validateStr"    caption="roll number"   minlength="1"   maxlength="20"              tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA='true'  />
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
				<label>XAT Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='xatScoreAdditional' id='xatScoreAdditional'   validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"      tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true'   />
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
			
			<li id="gre" style="display:none">
				<div class='additionalInfoLeftCol'>
				<label>GRE Registration Number: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Myra_GRE_regNo' id='Myra_GRE_regNo'  validate="validateStr"    caption="GRE registration number"   minlength="1"   maxlength="20"     tip="Please enter your GRE registration number. If you have not appeared for this examination enter NA."   value=''  allowNA='true'   />
				<?php if(isset($Myra_GRE_regNo) && $Myra_GRE_regNo!=""){ ?>
				  <script>
				      document.getElementById("Myra_GRE_regNo").value = "<?php echo str_replace("\n", '\n', $Myra_GRE_regNo );  ?>";
				      document.getElementById("Myra_GRE_regNo").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Myra_GRE_regNo_error'></div></div>
				</div>
				</div>
			
				<div class='additionalInfoRightCol'>
				<label>GRE Score: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='Myra_GRE_score' id='Myra_GRE_score'  validate="validateInteger"    caption="GRE score"   minlength="1"   maxlength="5"     tip="Please enter your GRE score. If you do not know your score, enter NA."   value=''    allowNA='true' />
				<?php if(isset($Myra_GRE_score) && $Myra_GRE_score!=""){ ?>
				  <script>
				      document.getElementById("Myra_GRE_score").value = "<?php echo str_replace("\n", '\n', $Myra_GRE_score );  ?>";
				      document.getElementById("Myra_GRE_score").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Myra_GRE_score_error'></div></div>
				</div>
				</div>
			</li>

                        <li id="cmat" style="display:none">

				<div class='additionalInfoLeftCol'>

				<label>CMAT Roll Number: </label>

				<div class='fieldBoxLarge'>

				<input type='text' name='cmatRollNumberAdditional' id='cmatRollNumberAdditional'   validate="validateStr"    caption="roll number"   minlength="1"   maxlength="20"              tip="Mention your roll number for the exam. If you have not appeared for this examination enter NA."   value=''   allowNA='true'  />

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

				<label>CMAT Score: </label>

				<div class='fieldBoxLarge'>

				<input type='text' name='cmatScoreAdditional' id='cmatScoreAdditional'   validate="validateInteger"    caption="score"   minlength="1"   maxlength="5"      tip="Mention how much you scored in the exam. If the exam authorities have only declared percentiles for the exam, enter NA."   value=''  allowNA='true'   />

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
			
			<li>
				<h3 class=upperCase'>GD/PI Location</h3>
				<label style='font-weight:normal'>Centre Preference 1: </label>
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
			
				<div class='additionalInfoRightCol'>
				<label>Centre Preference 2: </label>
				<div class='fieldBoxLarge'>
				<select name='Myra_GDPI2' id='Myra_GDPI2'    tip="Select second GD/PI Center."    validate="validateSelect"   required="true"   caption="center preference"   onmouseover="showTipOnline('Select second GD/PI Center.',this);" onmouseout="hidetip();" ><option value="">Select</option><option value='Bangalore'>Bangalore</option><option value='Chennai' >Chennai</option><option value='Delhi' >Delhi</option><option value='Hyderabad' >Hyderabad</option><option value='Kolkata' >Kolkata</option><option value='Mysore' >Mysore</option><option value='Mumbai' >Mumbai</option></select>
				<?php if(isset($Myra_GDPI2) && $Myra_GDPI2!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Myra_GDPI2"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Myra_GDPI2;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Myra_GDPI2_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>Centre Preference 3: </label>
				<div class='fieldBoxLarge'>
				<select name='Myra_GDPI3' id='Myra_GDPI3'    tip="Select third GD/PI Center."    validate="validateSelect"   required="true"   caption="center preference"   onmouseover="showTipOnline('Select third GD/PI Center.',this);" onmouseout="hidetip();" ><option value="">Select</option><option value='Bangalore'>Bangalore</option><option value='Chennai' >Chennai</option><option value='Delhi' >Delhi</option><option value='Hyderabad' >Hyderabad</option><option value='Kolkata' >Kolkata</option><option value='Mysore' >Mysore</option><option value='Mumbai' >Mumbai</option></select>
				<?php if(isset($Myra_GDPI3) && $Myra_GDPI3!=""){ ?>
			      <script>
				  var selObj = document.getElementById("Myra_GDPI3"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $Myra_GDPI3;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'Myra_GDPI3_error'></div></div>
				</div>
				</div>
			</li>
			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
					I hereby declare that the information furnished in the application form is true and correct to the
best of my knowledge. I also declare that I will produce all original certificates whenever they
are required. I also understand that misrepresentation or omission of facts in my application can
lead to denial of admission, cancellation of admission, or even warrant expulsion from the
programme.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox'  validate="validateChecked" checked   required="true"   caption="Please check to accept terms"   name='Myra_agreeToTerms[]' id='Myra_agreeToTerms0'   value='1'    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($Myra_agreeToTerms) && $Myra_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["Myra_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$Myra_agreeToTerms);
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
				<div style='display:none'><div class='errorMsg' id= 'Myra_agreeToTerms0_error'></div></div>
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
  
  for(var j=0; j<5; j++){
		checkTestScore(document.getElementById('Myra_testNames'+j));
	}

  </script>
