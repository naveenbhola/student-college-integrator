<style>
.cutom-tr {height: 50px;}
</style>
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			
			<li>
				<h3 class="upperCase">Qualifying exam details</h3>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
						<label>JEE Main Year: </label>
						<div class='fieldBoxLarge'>
						<input type='text' name='JRE_JEEYear' id='JRE_JEEYear'    maxlength="4" minlength="4" caption="year" validate="validateInteger"       tip="Mention your year of the exam. If you don't know the year, you can leave this field blank."   value=''   />
						<?php if(isset($JRE_JEEYear) && $JRE_JEEYear!=""){ ?>
						  <script>
						      document.getElementById("JRE_JEEYear").value = "<?php echo str_replace("\n", '\n', $JRE_JEEYear );  ?>";
						      document.getElementById("JRE_JEEYear").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'JRE_JEEYear_error'></div></div>
						</div>
				</div>
				
				<div class='additionalInfoRightCol'>
						<label>JEE Main % of Marks: </label>
						<div class='fieldBoxLarge'>
						<input type='text' name='jeeScoreAdditional' id='jeeScoreAdditional'    maxlength="7" minlength="1" caption="score" validate="validateFloat"       tip="Mention your Marks in the exam. If you don't know your Marks, you can leave this field blank."   value=''   />
						<?php if(isset($jeeScoreAdditional) && $jeeScoreAdditional!=""){ ?>
						  <script>
						      document.getElementById("jeeScoreAdditional").value = "<?php echo str_replace("\n", '\n', $jeeScoreAdditional );  ?>";
						      document.getElementById("jeeScoreAdditional").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'jeeScoreAdditional_error'></div></div>
						</div>
				</div>
			</li>

			<li>
				<div class='additionalInfoLeftCol'>
						<label>UPSEE Year: </label>
						<div class='fieldBoxLarge'>
						<input type='text' name='JRE_UPSEEYear' id='JRE_UPSEEYear'    maxlength="4" minlength="4" caption="year" validate="validateInteger"       tip="Mention your year of the exam. If you don't know the year, you can leave this field blank."   value=''   />
						<?php if(isset($JRE_UPSEEYear) && $JRE_UPSEEYear!=""){ ?>
						  <script>
						      document.getElementById("JRE_UPSEEYear").value = "<?php echo str_replace("\n", '\n', $JRE_UPSEEYear );  ?>";
						      document.getElementById("JRE_UPSEEYear").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'JRE_UPSEEYear_error'></div></div>
						</div>
				</div>
				
				<div class='additionalInfoRightCol'>
						<label>UPSEE % of Marks: </label>
						<div class='fieldBoxLarge'>
						<input type='text' name='JRE_UPSEEPercentage' id='JRE_UPSEEPercentage'    maxlength="7" minlength="1" caption="score" validate="validateFloat"       tip="Mention your Marks in the exam. If you don't know your Marks, you can leave this field blank."   value=''   />
						<?php if(isset($JRE_UPSEEPercentage) && $JRE_UPSEEPercentage!=""){ ?>
						  <script>
						      document.getElementById("JRE_UPSEEPercentage").value = "<?php echo str_replace("\n", '\n', $JRE_UPSEEPercentage );  ?>";
						      document.getElementById("JRE_UPSEEPercentage").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'JRE_UPSEEPercentage_error'></div></div>
						</div>
				</div>
			</li>
			
<?php if($action != 'updateScore'):?>

			<?php if(is_array($gdpiLocations) && count($gdpiLocations)): ?>
			<li>
				<h3 class=upperCase'>Interview/Councelling Location</h3>
				<label style='font-weight:normal'>Preferred Interview/Councelling Location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred Interview/Councelling location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred Interview/Councelling Location">
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
				<h3 class="upperCase">Disclaimer</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
I have read, understood and hereby accept the Terms and Conditions of enrollment for admission into JRE Group of Institutions. I also agree to abide by the rules and regulations of JRE.<br/>
<div style='margin-top:10px;'>I confirm that I have read and understood the description of the programme I have applied for and that the information provided prior to admission is correct to the best of my knowledge. I agree and confirm that JRE reserves the right to withdraw / terminate the admission granted at any time in the event if any information provided is found to be false or incorrect. I accept that JRE reserves the right to amend fees as per MTU guidelines, schedules, class structures and JRE Rules and Regulations as prescribed in the Student Hand Book during the course of study.</div>
<div style='margin-top:10px;margin-bottom:10px;'>I understand that while JRE shall take all reasonable precautions in ensuring the physical safety of the students on campus however, JRE shall not be responsible for any harm, injury, disability (temporary or permanent) or death caused due to any accident  / incident on JRE campus.</div>
I agree to comply with the code of conduct, rules and regulations supplied by JRE and as may be amended / modified from time to time. Non-compliance of any code of conduct, rule or regulation may result in restriction, expulsion or any other form of punishment. The decision of the DIRECTOR/JRE Management in this regard would be final and binding.<br/>
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' caption='Please agree to terms stated above'  validate="validateChecked" checked  required="true"   name='JRE_agreeToTerms[]' id='JRE_agreeToTerms0'   value=''    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($JRE_agreeToTerms) && $JRE_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["JRE_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$JRE_agreeToTerms);
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
				
				<div style='display:none'><div class='errorMsg' id= 'JRE_agreeToTerms0_error'></div></div>
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
  
  
