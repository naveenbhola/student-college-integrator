<script>
  function checkTestScore(obj){
    var key = obj.value.toLowerCase();
    var objects1 = new Array(key+"RollNumberAdditional",key+"PercentileAdditional");
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
  
  function toggleSourceTextBox(objId,txt,str){
	if(objId.checked==true){
	        
		$(txt+str+'_li').style.display = '';
                $(txt+str).setAttribute('required','true');
	}else{
		$(txt+str).value = '';
		$(txt+str+'_li').style.display = 'none';
		$(txt+str).removeAttribute('required');
		$(txt+str+'_error').innerHTML = '';
		$(txt+str+'_error').parentNode.style.display = 'none';
      }
  }
  
</script>


<div class='formChildWrapper'>
	<div class='formSection'>
		<ul> 
			
			<?php if($action != 'updateScore'):?>
                     
			<li>
				<h3 class="upperCase">Preferred GD & PI Centres</h3>
				
				<div class='additionalInfoLeftCol'>
				<label>1st Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(1);" name='preferredGDPILocation' id='preferredGDPILocation' style="width:120px;"    onmouseover="showTipOnline('Select your 1st preference of GD/PI Location from the list.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="1st preference of GD/PI location">
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

				<div class='additionalInfoRightCol'>
				<label>2nd Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(2);" name='pref2CIMP' id='pref2CIMP'  style="width:120px;"   tip="Select your 2nd preference of GD/PI Location from the list."  onmouseover="showTipOnline('Select your 2nd preference of campus from the list.',this);" onmouseout="hidetip();" validate="validateSelect" required="true" caption="2nd preference of GD/PI location" >
				  <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Delhi' >Delhi</option><option value='Patna' >Patna</option><option value='Pune' >Pune</option>
				</select>
				<?php if(isset($pref2CIMP) && $pref2CIMP!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2CIMP"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2CIMP;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2CIMP_error'></div></div>
				</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
				<label>3rd Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(3);" name='pref3CIMP' id='pref3CIMP'   style="width:120px;"  tip="Select your 3rd preference of GD/PI Location from the list."  onmouseover="showTipOnline('Select your 3rd preference of campus from the list.',this);" onmouseout="hidetip();" validate="validateSelect" required="true"  caption="3rd preference of GD/PI location">
				      <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Delhi' >Delhi</option><option value='Patna' >Patna</option><option value='Pune' >Pune</option>
				
				</select>
				<?php if(isset($pref3CIMP) && $pref3CIMP!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref3CIMP"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref3CIMP;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref3CIMP_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>4th Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(4);" name='pref4CIMP' id='pref4CIMP'  style="width:120px;"   tip="Select your 4th preference of GD/PI Location from the list." onmouseover="showTipOnline('Select your 4th preference of campus from the list.',this);" onmouseout="hidetip();" validate="validateSelect" required="true"  caption="4th preference of GD/PI location" >
				      <option value='' selected>Select</option><option value='Bangalore' >Bangalore</option><option value='Delhi' >Delhi</option><option value='Patna' >Patna</option><option value='Pune' >Pune</option>
				</select>
				<?php if(isset($pref4CIMP) && $pref4CIMP!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref4CIMP"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref4CIMP;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref4CIMP_error'></div></div>
				</div>
				</div>
				</li>
			      
<?php endif; ?>
			
			<li>
				<h3 class="upperCase">Admission Test Results</h3>
			</li>
			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Details of CAT 2014 (Photocopy of the Admit Card/ Test score must be attached to the form)</label>
				<div class='fieldBoxLarge' style="width:500px">
				<input type='checkbox'  onClick="checkTestScore(this);"  validate="validateCheckedGroup"   required="true"   caption="tests"   name='CIMP_testNames[]' id='CIMP_testNames0'   value='CAT'   onmouseover="showTipOnline('Tick the appropriate box and provide test Registration no. and percentile (if available)',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide test Registration no. and percentile (if available)',this);" onmouseout="hidetip();" >CAT</span>&nbsp;&nbsp;
				
				<?php if(isset($CIMP_testNames) && $CIMP_testNames!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["CIMP_testNames[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    if (typeof(countCheckBoxes)=='undefined') {
				      countCheckBoxes = 1;
				    }
				    objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$CIMP_testNames);
						    for($x=0;$x<count($arr);$x++){ ?>
							  if(objCheckBoxes.value == "<?php echo $arr[$x];?>") {
								  objCheckBoxes.checked = true;
							  }
					      <?php
						    }
					      ?>
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_testNames_error'></div></div>
				</div>
				</div>
			</li>
			

                        <li id="cat1" style="display:none;">
				<div class='additionalInfoLeftCol'>
				<label>CAT Registration No: </label>
				<div class='fieldBoxLarge'>
				<input class="textboxLarge" type='text' name='catRollNumberAdditional' id='catRollNumberAdditional'  validate="validateStr"    caption="registration no"   minlength="2"   maxlength="50"  tip="Mention your Registration number for the exam."   value=''  style="width:156px" />
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

			<li id="cat2" style="display:none;">
				<div class='additionalInfoLeftCol'>
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

			<?php if($action != 'updateScore'):?>
			<li>
				<h3 class="upperCase">Personal Details</h3>
			</li>
			<li>
			  <div class='additionalInfoLeftCol'>
				  <label>Salutation:</label>
				  <div class='fieldBoxLarge'>
				  <input type='radio' validate="validateCheckedGroup" caption="salutation"  name='CIMP_salutation' id='CIMP_salutation0'   value='Mr.' title="Salutation"   onmouseover="showTipOnline('Please select the appropriate gender.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('please select the appropriate gender.',this);" onmouseout="hidetip();" >Mr.</span>&nbsp;&nbsp;
				  <input type='radio' validate="validateCheckedGroup" caption="salutation"  name='CIMP_salutation' id='CIMP_salutation1'   value='Ms.'    title="Salutation"   onmouseover="showTipOnline('Please select the appropriate gender.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate gender.',this);" onmouseout="hidetip();" >Ms.</span>&nbsp;&nbsp;<br/>
				  
				  <?php if(isset($CIMP_salutation) && $CIMP_salutation!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["CIMP_salutation"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $CIMP_salutation;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
				  
				  <div style='display:none'><div class='errorMsg' id= 'CIMP_salutation_error'></div></div>
				  </div>
				</div>
			  </li>
			<li>
			<div class='additionalInfoLeftCol'>
				<label>Father's Qualification: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_fatherQual' id='CIMP_fatherQual'  validate="validateStr"   required="true"   caption="father's qualification"   minlength="1"   maxlength="100"     tip="Enter your father's qualification."   title="Father's Qualification"  value=''   />
				<?php if(isset($CIMP_fatherQual) && $CIMP_fatherQual!=""){ ?>
				  <script>
				      document.getElementById("CIMP_fatherQual").value = "<?php echo str_replace("\n", '\n', $CIMP_fatherQual );  ?>";
				      document.getElementById("CIMP_fatherQual").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_fatherQual_error'></div></div>
				</div>
			</div>
			</li>
			
			<li>
			<div class='additionalInfoLeftCol'>
				<label>Mother's Qualification: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_motherQual' id='CIMP_motherQual'  validate="validateStr"   required="true"   caption="mother's qualification"   minlength="1"   maxlength="100"     tip="Enter your mother's qualification."   title="Mother's Qualification"  value=''   />
				<?php if(isset($CIMP_motherQual) && $CIMP_motherQual!=""){ ?>
				  <script>
				      document.getElementById("CIMP_motherQual").value = "<?php echo str_replace("\n", '\n', $CIMP_motherQual );  ?>";
				      document.getElementById("CIMP_motherQual").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_motherQual_error'></div></div>
				</div>
			</div>
			</li>
			
			<li>
			<div class='additionalInfoLeftCol'>
				<label>Family Yearly Income: </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_yearlyincome' id='CIMP_yearlyincome'  validate="validateStr"   required="true"   caption="family's yearly income"   minlength="1"   maxlength="100"     tip="Enter your family's yearly income. If it doesn't apply to you, just enter NA.."   title="Family's Yearly Income"  value=''   />
				<?php if(isset($CIMP_yearlyincome) && $CIMP_yearlyincome!=""){ ?>
				  <script>
				      document.getElementById("CIMP_yearlyincome").value = "<?php echo str_replace("\n", '\n', $CIMP_yearlyincome );  ?>";
				      document.getElementById("CIMP_yearlyincome").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_yearlyincome_error'></div></div>
				</div>
			</div>
			</li>
			
			<li>
			  <div class='additionalInfoLeftCol' style="width:100%;">
				  <label>Category:</label>
				  <div class='fieldBoxLarge' style="width:600px;">
				  <input type='radio' validate="validateCheckedGroup" caption="the category"  name='CIMP_category' id='CIMP_category0'   value='General' title="Category"   onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('please select the appropriate category.',this);" onmouseout="hidetip();" >General</span>&nbsp;&nbsp;
				  <input type='radio' validate="validateCheckedGroup" caption="the category"  name='CIMP_category' id='CIMP_category1'   value='Scheduled Caste'    title="Category"   onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" >Scheduled Caste</span>&nbsp;&nbsp;
				  <input type='radio' validate="validateCheckedGroup" caption="the category"  name='CIMP_category' id='CIMP_category2'   value='Scheduled Tribe'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" >Scheduled Tribe</span>&nbsp;&nbsp;
				  <input type='radio'  validate="validateCheckedGroup" caption="the category"  name='CIMP_category' id='CIMP_category2'   value='Extremely Backward Class'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" >Extremely Backward Class</span>&nbsp;&nbsp;<br/>
				  <input type='radio' validate="validateCheckedGroup" caption="the category"  name='CIMP_category' id='CIMP_category2'   value='Backward Class'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" >Backward Class</span>&nbsp;&nbsp;
				  <input type='radio' validate="validateCheckedGroup" caption="the category"  name='CIMP_category' id='CIMP_category2'   value='Women of Backward Class'    title="Category"   onmouseover="showTipOnline('Please select your appropriate category.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please select the appropriate category.',this);" onmouseout="hidetip();" >Women of Backward Class</span>&nbsp;&nbsp;
				  <?php if(isset($CIMP_category) && $CIMP_category!=""){ ?>
				    <script>
					radioObj = document.forms["OnlineForm"].elements["CIMP_category"];
					var radioLength = radioObj.length;
					for(var i = 0; i < radioLength; i++) {
						radioObj[i].checked = false;
						if(radioObj[i].value == "<?php echo $CIMP_category;?>") {
							radioObj[i].checked = true;
						}
					}
				    </script>
				  <?php } ?>
				  
				  <div style='display:none'><div class='errorMsg' id= 'CIMP_category_error'></div></div>
				  </div>
				</div>
			  </li>
			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Disability(Attach Certificate) </label>
				<div class='fieldBoxLarge' style="width:400px">
				<input type='checkbox'   validate="validateCheckedGroup"   required="true"   caption="the disability"   name='CIMP_person_disability[]' id='CIMP_person_disability0'   value='None'   onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" >None</span>&nbsp;&nbsp;
				<input type='checkbox'    validate="validateCheckedGroup"   required="true"   caption="the disability"   name='CIMP_person_disability[]' id='CIMP_person_disability1'   value='Low Vision /Blindness'   onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" >Low Vision /Blindness</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the disability"   name='CIMP_person_disability[]' id='CIMP_person_disability2'   value='Hearing Impairment'   onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" >Hearing Impairment</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the disability"   name='CIMP_person_disability[]' id='CIMP_person_disability3'   value='Locomotor Disability /Cerebral Palsy'   onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide your disability',this);" onmouseout="hidetip();" >Locomotor Disability /Cerebral Palsy</span>&nbsp;&nbsp;
				
				
				<?php if(isset($CIMP_person_disability) && $CIMP_person_disability!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["CIMP_person_disability[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$CIMP_person_disability);
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
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_person_disability_error'></div></div>
				</div>
				</div>
			</li>
			
			<li>
				<h3 class="upperCase">Examination Details</h3>
			</li>
			
			<li>
			  
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Class 10th division </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_division10' id='CIMP_division10'  validate="validateStr"   required="true"   caption="Class 10th Division"   minlength="1"   maxlength="100"     tip="Enter the division of your class 10th."   title="Class 10th Divison"  value=''   />
				<?php if(isset($CIMP_division10) && $CIMP_division10!=""){ ?>
				  <script>
				      document.getElementById("CIMP_division10").value = "<?php echo str_replace("\n", '\n', $CIMP_division10 );  ?>";
				      document.getElementById("CIMP_division10").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_division10_error'></div></div>
				</div>
			  </div>
			  
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Class 10th medium of instruction </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_medium10' id='CIMP_medium10'  validate="validateStr"   required="true"   caption="Class 10th medium of instruction"   minlength="1"   maxlength="100"     tip="Enter the class 10th medium of instruction."   title="Class 10th Divison"  value=''   />
				<?php if(isset($CIMP_medium10) && $CIMP_medium10!=""){ ?>
				  <script>
				      document.getElementById("CIMP_medium10").value = "<?php echo str_replace("\n", '\n', $CIMP_medium10);  ?>";
				      document.getElementById("CIMP_medium10").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_medium10_error'></div></div>
				</div>
			  </div>
			  
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Class 10th major subjects/stream </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_stream10' id='CIMP_stream10'  validate="validateStr"   required="true"   caption="Class 10th major subjects/stream"   minlength="1"   maxlength="100"     tip="Enter the class 10th major subjects/stream."   title="Class 10th major subjects/stream"  value=''  style="width:318px" />
				<?php if(isset($CIMP_stream10) && $CIMP_stream10!=""){ ?>
				  <script>
				      document.getElementById("CIMP_stream10").value = "<?php echo str_replace("\n", '\n', $CIMP_stream10);  ?>";
				      document.getElementById("CIMP_stream10").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_stream10_error'></div></div>
				</div>
			  </div>
			</li>
			
			<li>
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Class 12th division </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_division12' id='CIMP_division12'  validate="validateStr"   required="true"   caption="Class 12th Division"   minlength="1"   maxlength="100"     tip="Enter the division of your class 12th."   title="Class 12th Divison"  value=''   />
				<?php if(isset($CIMP_division12) && $CIMP_division12!=""){ ?>
				  <script>
				      document.getElementById("CIMP_division12").value = "<?php echo str_replace("\n", '\n', $CIMP_division12 );  ?>";
				      document.getElementById("CIMP_division12").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_division12_error'></div></div>
				</div>
			  </div>
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Class 12th medium of instruction </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_medium12' id='CIMP_medium12'  validate="validateStr"   required="true"   caption="Class 12th medium of instruction"   minlength="1"   maxlength="100"     tip="Enter the class 12th medium of instruction."   title="Class 12th Divison"  value=''   />
				<?php if(isset($CIMP_medium12) && $CIMP_medium12!=""){ ?>
				  <script>
				      document.getElementById("CIMP_medium12").value = "<?php echo str_replace("\n", '\n', $CIMP_medium12);  ?>";
				      document.getElementById("CIMP_medium12").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_medium12_error'></div></div>
				</div>
			  </div>
			  
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Class 12th major subjects/stream </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_stream12' id='CIMP_stream12'  validate="validateStr"   required="true"   caption="Class 12th major subjects/stream"   minlength="1"   maxlength="100"     tip="Enter the class 12th major subjects/stream."   title="Class 12th major subjects/stream"  value=''  style="width:318px" />
				<?php if(isset($CIMP_stream12) && $CIMP_stream12!=""){ ?>
				  <script>
				      document.getElementById("CIMP_stream12").value = "<?php echo str_replace("\n", '\n', $CIMP_stream12);  ?>";
				      document.getElementById("CIMP_stream12").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_stream12_error'></div></div>
				</div>
			  </div>
			</li>
			
			<li>
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Graduation division </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_divisionGrad' id='CIMP_divisionGrad'  validate="validateStr"   required="true"   caption="Graduation Division"   minlength="1"   maxlength="100"     tip="Enter the division of your graduation."   title="Graduation Divison"  value=''   />
				<?php if(isset($CIMP_divisionGrad) && $CIMP_divisionGrad!=""){ ?>
				  <script>
				      document.getElementById("CIMP_divisionGrad").value = "<?php echo str_replace("\n", '\n', $CIMP_divisionGrad );  ?>";
				      document.getElementById("CIMP_divisionGrad").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_divisionGrad_error'></div></div>
				</div>
			  </div>
			  
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Graduation medium of instruction </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_mediumGrad' id='CIMP_mediumGrad'  validate="validateStr"   required="true"   caption="graduation medium of instruction."   minlength="1"   maxlength="100"     tip="Enter the graduation medium of instruction."   title="Graduation Medium of Instruction"  value=''   />
				<?php if(isset($CIMP_mediumGrad) && $CIMP_mediumGrad!=""){ ?>
				  <script>
				      document.getElementById("CIMP_mediumGrad").value = "<?php echo str_replace("\n", '\n', $CIMP_mediumGrad);  ?>";
				      document.getElementById("CIMP_mediumGrad").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_mediumGrad_error'></div></div>
				</div>
			  </div>
			  
			  <div class='additionalInfoLeftCol' style="margin-bottom:8px;">
				<label>Graduation major subjects/stream </label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_streamGrad' id='CIMP_streamGrad'  validate="validateStr"   required="true"   caption="graduation medium of instruction."   minlength="1"   maxlength="100"     tip="Enter the graduation medium of instruction."   title="Graduation Medium of Instruction"  value='' style="width:318px"  />
				<?php if(isset($CIMP_streamGrad) && $CIMP_streamGrad!=""){ ?>
				  <script>
				      document.getElementById("CIMP_streamGrad").value = "<?php echo str_replace("\n", '\n', $CIMP_streamGrad);  ?>";
				      document.getElementById("CIMP_streamGrad").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_streamGrad_error'></div></div>
				</div>
			  </div>
			  
			  
			</li>
			
			<li>
				<h3 class="upperCase">Other Details</h3>
			</li>
			
			<li>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>How did you get to know about Chandragupt Institute of Management, Patna?<br/>
<span style="color: #666; font-size:13px;">(Please specify the source)</span></label>
				<div class='fieldBoxLarge' style="width:480px">
				<input type='checkbox'   validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know0'   value='Alumni'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Alumni');"></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Alumni</span>&nbsp;&nbsp;
				<input type='checkbox'    validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know1'   value='Friend/Relative/Parent'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Friend');"></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Friend/Relative/Parent</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know2'   value='Website'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Website');"></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Website</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know3'   value='Coaching Institute'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Institute');"></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Coaching Institute</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know4'   value='Newspaper'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Newspaper');" ></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Newspaper</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know5'   value='Magazine'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Magazine');"></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Magazine</span>&nbsp;&nbsp;
				<input type='checkbox'  validate="validateCheckedGroup"   required="true"   caption="the source"   name='CIMP_know[]' id='CIMP_know6'   value='Other(s)'   onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" onClick="toggleSourceTextBox(this,'CIMP_know','Others');"></input><span  onmouseover="showTipOnline('Tick the appropriate box and provide the source',this);" onmouseout="hidetip();" >Other(s)</span>&nbsp;&nbsp;
				
				
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["CIMP_know[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$CIMP_know);
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
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_know_error'></div></div>
				</div>
				</div>
			</li>
		
			<li  id='CIMP_knowAlumni_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Alumni Name</label>
				<div class='fieldBoxLarge'>
				  
				<input type='text' name='CIMP_knowAlumni' id='CIMP_knowAlumni'  tip="Please mention source name."  validate="validateStr" caption="Alumni Name" minlength="1" maxlength="100" value='' style="width:318px" />
				<?php if(isset($CIMP_knowAlumni) && $CIMP_knowAlumni!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowAlumni").value = "<?php echo str_replace("\n", '\n', $CIMP_knowAlumni );  ?>";
				      document.getElementById("CIMP_knowAlumni").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowAlumni_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Alumni'){
							document.getElementById('CIMP_knowAlumni_li').style.display='';
						      }
						      
					      }
					  <?php } ?>
				      }
				  </script>
				<?php } ?>
			</li>
			<li  id='CIMP_knowFriend_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Friend/Relative/Parent Name</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_knowFriend' id='CIMP_knowFriend'   tip="Please mention source name."  validate="validateStr" caption="friend/relative/parent name" minlength="1" maxlength="100" value='' style="width:318px" />
				<?php if(isset($CIMP_knowFriend) && $CIMP_knowFriend!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowFriend").value = "<?php echo str_replace("\n", '\n', $CIMP_knowFriend);  ?>";
				      document.getElementById("CIMP_knowFriend").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowFriend_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Friend/Relative/Parent'){
							document.getElementById('CIMP_knowFriend_li').style.display='';
						      }
						      
					      }
					  <?php } ?>
				      }
				  </script>
				<?php } ?>
			</li>
			<li  id='CIMP_knowWebsite_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Website Name</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_knowWebsite' id='CIMP_knowWebsite' tip="Please mention source name."  validate="validateStr" caption="website name" minlength="1" maxlength="100" value='' style="width:318px">
				<?php if(isset($CIMP_knowWebsite) && $CIMP_knowWebsite!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowWebsite").value = "<?php echo str_replace("\n", '\n', $CIMP_knowWebsite);  ?>";
				      document.getElementById("CIMP_knowWebsite").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowWebsite_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Website'){
							document.getElementById('CIMP_knowWebsite_li').style.display='';
						      }
						      
					      }
					  <?php } ?>
				      }
				  </script>
				<?php } ?>
			</li>
			<li  id='CIMP_knowInstitute_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Coaching Institute Name</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_knowInstitute' id='CIMP_knowInstitute'  tip="Please mention source name."  validate="validateStr" caption="coaching institute name" minlength="1" maxlength="100" value='' style="width:318px"/>
				<?php if(isset($CIMP_knowInstitute) && $CIMP_knowInstitute!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowInstitute").value = "<?php echo str_replace("\n", '\n', $CIMP_knowInstitute);  ?>";
				      document.getElementById("CIMP_knowInstitute").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowInstitute_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Coaching Institute'){
							document.getElementById('CIMP_knowInstitute_li').style.display='';
						      }
						      
					      }
					  <?php } ?>
				      }
				  </script>
				<?php } ?>
			</li>
			
			<li  id='CIMP_knowNewspaper_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Newspaper Name</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_knowNewspaper' id='CIMP_knowNewspaper' tip="Please mention source name."  validate="validateStr" caption="newspaper name" minlength="1" maxlength="100" value='' style="width:318px"/>
				<?php if(isset($CIMP_knowNewspaper) && $CIMP_knowNewspaper!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowNewspaper").value = "<?php echo str_replace("\n", '\n', $CIMP_knowNewspaper);  ?>";
				      document.getElementById("CIMP_knowNewspaper").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowNewspaper_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Newspaper'){
							document.getElementById('CIMP_knowNewspaper_li').style.display='';
						      }
						      
					      }
					      
					  <?php } ?>
				      }
				  </script>
				<?php } ?>
			</li>
			
			<li  id='CIMP_knowMagazine_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Magazine Name</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_knowMagazine' id='CIMP_knowMagazine'  tip="Please mention source name."  validate="validateStr" caption="magazine" minlength="1" maxlength="100" value='' style="width:318px"/>
				<?php if(isset($CIMP_knowMagazine) && $CIMP_knowMagazine!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowMagazine").value = "<?php echo str_replace("\n", '\n', $CIMP_knowMagazine);  ?>";
				      document.getElementById("CIMP_knowMagazine").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowMagazine_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Magazine'){
							document.getElementById('CIMP_knowMagazine_li').style.display='';
						      }
						      
					      }
					  <?php } ?>
				      }
				  </script>
				<?php } ?>
			</li>
			<li  id='CIMP_knowOthers_li' style="display:none;">
			   <div class='additionalInfoLeftCol' style="width:950px ;">
			      <label>Other Source</label>
				<div class='fieldBoxLarge'>
				<input type='text' name='CIMP_knowOthers' id='CIMP_knowOthers'  tip="Please mention other source."  validate="validateStr" caption="other source" minlength="1" maxlength="100" style="width:318px" />
				<?php if(isset($CIMP_knowOthers) && $CIMP_knowOthers!=""){ ?>
				  <script>
				      document.getElementById("CIMP_knowOthers").value = "<?php echo str_replace("\n", '\n', $CIMP_knowOthers);  ?>";
				      document.getElementById("CIMP_knowOthers").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_knowOthers_error'></div></div>
				</div>
				</div>
					
				<?php if(isset($CIMP_know) && $CIMP_know!=""){ ?>
				  <script>
				      checkObj = document.forms["OnlineForm"].elements["CIMP_know[]"];
				      var checkLength = checkObj.length;
				      for(var i = 0; i < checkLength; i++) {
					      checkObj[i].checked = false;
					      <?php $arr = explode(",",$CIMP_know);
					      for($x=0;$x<count($arr);$x++){ ?>
					      if(checkObj[i].value == "<?php echo $arr[$x];?>") {
						      checkObj[i].checked = true;
						      if(checkObj[i].value=='Other(s)'){
							document.getElementById('CIMP_knowOthers_li').style.display='';
						      }
						      
					      }
				    <?php  } ?>
				    }  
				  </script>
				<?php } ?>
			</li>
			
			
			
			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Describe how a management career is in line with your aims in life?  </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='CIMP_career' id='CIMP_career' required="true" style="width:318px; height:74px; padding:5px" tip="Describe how a management career is in line with your aims in life?" validate="validateStr" caption="description how a management career is in line with your aims in life?" minlength="1" maxlength="1000" ></textarea>
				<?php if(isset($CIMP_career) && $CIMP_career!=""){ ?>
				  <script>
				      document.getElementById("CIMP_career").value = "<?php echo str_replace("\n", '\n', $CIMP_career );  ?>";
				      document.getElementById("CIMP_career").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_career_error'></div></div>
				</div>
				</div>
				
			</li>
			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>Why would you choose CIMP for your PGDM?  </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='CIMP_choice' id='CIMP_choice' required="true"  style="width:318px; height:74px; padding:5px" tip="Why would you choose CIMP for your PGDM?" validate="validateStr" caption="reason Why would you choose CIMP for your PGDM?" minlength="1" maxlength="1000" ></textarea>
				<?php if(isset($CIMP_choice) && $CIMP_choice!=""){ ?>
				  <script>
				      document.getElementById("CIMP_choice").value = "<?php echo str_replace("\n", '\n', $CIMP_choice );  ?>";
				      document.getElementById("CIMP_choice").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_choice_error'></div></div>
				</div>
				</div>
				
			</li>
			<li style="width:100%">
				<div class='additionalInfoLeftCol' style="width:950px">
				<label>If you become a manager, what will be your contribution to society?  </label>
				<div class='fieldBoxLarge' style="width:630px">
				<textarea name='CIMP_societycontribution' id='CIMP_societycontribution' required="true"  style="width:318px; height:74px; padding:5px"    tip="Explain If you become a manager, what will be your contribution to society?" validate="validateStr" caption="explanation ,if you become a manager, what will be your contribution to society?" minlength="1" maxlength="1000" ></textarea>
				<?php if(isset($CIMP_societycontribution) && $CIMP_societycontribution!=""){ ?>
				  <script>
				      document.getElementById("CIMP_societycontribution").value = "<?php echo str_replace("\n", '\n', $CIMP_societycontribution );  ?>";
				      document.getElementById("CIMP_societycontribution").style.color = "";
				  </script>
				<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_societycontribution_error'></div></div>
				</div>
				</div>
				
			</li>
			
			    
		
			<li>	
			<h3 class="upperCase">DISCLAIMER</h3>
			<label style="font-weight:normal; padding-top:0">Declaration: &nbsp;</label>
		    <div class="fieldBoxLarge" style="width:620px; color:#666666; font-style:italic">
		    
			
<ul>
<li> I hereby declare that all particulars given by me in this application form are true to the best of my
knowledge and belief. I agree to abide by the decision of the institute authorities regarding my selection for the
program. Any misrepresentation of facts in this application form could result in cancellation of admission at a later date.</li>
</ul>
</div>
			</li>

				<li>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='CIMP_agreeToTerms' id='CIMP_agreeToTerms'  value='1'  required="true" caption="Please agree to the terms stated above" validate="validateChecked"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($CIMP_agreeToTerms) && $CIMP_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["CIMP_agreeToTerms"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$CIMP_agreeToTerms);
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
				
				<div style='display:none'><div class='errorMsg' id= 'CIMP_agreeToTerms_error'></div></div>
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
  <?php } ?>
  <script>
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
  
  function checkValidCampusPreference(id){
	if(id==1){id='preferredGDPILocation'; sId = 'pref2CIMP'; tId='pref3CIMP'; fId='pref4CIMP';}
	else if(id==2){ id='pref2CIMP'; sId = 'preferredGDPILocation'; tId = 'pref3CIMP'; fId='pref4CIMP';  }
	else if(id==3){id='pref3CIMP'; sId = 'preferredGDPILocation'; tId = 'pref2CIMP';  fId='pref4CIMP';  }
	else {id='pref4CIMP'; sId = 'preferredGDPILocation'; tId = 'pref2CIMP';  fId='pref3CIMP';  }
	var selectedPrefObj = document.getElementById(id); 
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].innerHTML;
	var selObj1 = document.getElementById(sId); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].innerHTML;
	var selObj2 = document.getElementById(tId); 
	var selPref2 = selObj2.options[selObj2.selectedIndex].innerHTML;
	var selObj3 = document.getElementById(fId); 
	var selPref3 = selObj3.options[selObj3.selectedIndex].innerHTML;
	if( (selectedPref == selPref1 && selectedPref!='' ) || (selectedPref == selPref2 && selectedPref!='' ) || (selectedPref == selPref3 && selectedPref!='' ) ){
		$(id +'_error').innerHTML = 'Same preference cant be set.';
		$(id +'_error').parentNode.style.display = '';
		var A= selectedPrefObj.options, L= A.length;
		while(L){
		    if (A[--L].value== ""){
			selectedPrefObj.selectedIndex= L;
			L= 0;
		    }
		}
		return false;
	}
	else{
		$(id +'_error').innerHTML = '';
		$(id +'_error').parentNode.style.display = 'none';
	}
	return true;
  }
  
  
   for(var j=0; j<1; j++){
   
        checkTestScore(document.getElementById('CIMP_testNames'+j));
    } 
  </script>
