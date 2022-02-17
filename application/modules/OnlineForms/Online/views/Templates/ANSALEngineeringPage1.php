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
						<label>JEE Main Exam Date: </label>
						<div class='fieldBoxLarge'>
							<input type='text' name='jeeDateOfExaminationAdditional' id='jeeDateOfExaminationAdditional' readonly maxlength='10'  minlength='1' maxlength='10'  validate="validateDateForms" caption='date'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy');" />
							&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='jeeDateOfExaminationAdditional_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('jeeDateOfExaminationAdditional'),'jeeDateOfExaminationAdditional_dateImg','dd/MM/yyyy'); return false;" />
							<?php if(isset($jeeDateOfExaminationAdditional) && $jeeDateOfExaminationAdditional!=""){ ?>
							  <script>
							      document.getElementById("jeeDateOfExaminationAdditional").value = "<?php echo str_replace("\n", '\n', $jeeDateOfExaminationAdditional );  ?>";
							      document.getElementById("jeeDateOfExaminationAdditional").style.color = "";
							  </script>
							<?php } ?>
					
							<div style='display:none'><div class='errorMsg' id= 'jeeDateOfExaminationAdditional_error'></div></div>
						</div>
				</div>
				
				<div class='additionalInfoRightCol'>
						<label>JEE Main Score: </label>
						<div class='fieldBoxLarge'>
						<input type='text' name='jeeScoreAdditional' id='jeeScoreAdditional'    maxlength="7" minlength="1" caption="score" validate="validateInteger"       tip="Mention your Marks in the exam. If you don't know your Marks, you can leave this field blank."   value=''   />
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
						<label>AUEE Exam Date: </label>
						<div class='fieldBoxLarge'>
							<input type='text' name='ANSAL_AUEEDate' id='ANSAL_AUEEDate' readonly maxlength='10'  minlength='1' maxlength='10'  validate="validateDateForms"     caption='date'         tip="Mention the date on which the examination was conducted."     onmouseover="showTipOnline('Mention the date on which the examination was conducted.',this);" onmouseout="hidetip();"  onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('ANSAL_AUEEDate'),'ANSAL_AUEEDate_dateImg','dd/MM/yyyy');" />
							&nbsp;<img src='/public/images/eventIcon.gif' style='cursor:pointer' align='absmiddle' id='ANSAL_AUEEDate_dateImg' onClick="var cal = new CalendarPopup('calendardiv'); cal.select($('ANSAL_AUEEDate'),'ANSAL_AUEEDate_dateImg','dd/MM/yyyy'); return false;" />
							<?php if(isset($ANSAL_AUEEDate) && $ANSAL_AUEEDate!=""){ ?>
							  <script>
							      document.getElementById("ANSAL_AUEEDate").value = "<?php echo str_replace("\n", '\n', $ANSAL_AUEEDate );  ?>";
							      document.getElementById("ANSAL_AUEEDate").style.color = "";
							  </script>
							<?php } ?>
					
							<div style='display:none'><div class='errorMsg' id= 'ANSAL_AUEEDate_error'></div></div>
						</div>
				</div>
				
				<div class='additionalInfoRightCol'>
						<label>AUEE Score: </label>
						<div class='fieldBoxLarge'>
						<input type='text' name='ANSAL_AUEEScore' id='ANSAL_AUEEScore'    maxlength="7" minlength="1" caption="score" validate="validateInteger"       tip="Mention your Marks in the exam. If you don't know your Marks, you can leave this field blank."   value=''   />
						<?php if(isset($ANSAL_AUEEScore) && $ANSAL_AUEEScore!=""){ ?>
						  <script>
						      document.getElementById("ANSAL_AUEEScore").value = "<?php echo str_replace("\n", '\n', $ANSAL_AUEEScore );  ?>";
						      document.getElementById("ANSAL_AUEEScore").style.color = "";
						  </script>
						<?php } ?>
						
						<div style='display:none'><div class='errorMsg' id= 'ANSAL_AUEEScore_error'></div></div>
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
By submitting the application, the applicant certify and declares that<br/>
the information provided in the application form is complete and accurate. The applicant/ candidate understand and agree that misrepresentation or omission of facts will justify the denial of admission, cancellation of admission or any other action against the applicant/ candidate. The applicant/ candidate is aware that the Admission granted on the basis of the incorrect/ false information and misrepresentation will ipso facto be declared null and void. In all matters relating to the admissions, the decision of Ansal University will be final and binding on the applicant. The applicant/ candidate has read and do hereby consent to the Terms & Conditions as mentioned.
				</div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' caption='Please agree to terms stated above'  validate="validateChecked" checked  required="true"   name='ANSAL_agreeToTerms[]' id='ANSAL_agreeToTerms0'   value=''    title="I agree to the terms stated above"   onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Please check to accept terms',this);" onmouseout="hidetip();" ></span>&nbsp;&nbsp;
				<?php if(isset($ANSAL_agreeToTerms) && $ANSAL_agreeToTerms!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["ANSAL_agreeToTerms[]"];
				    var countCheckBoxes = objCheckBoxes.length;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes[i].checked = false;

					      <?php $arr = explode(",",$ANSAL_agreeToTerms);
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
				
				<div style='display:none'><div class='errorMsg' id= 'ANSAL_agreeToTerms0_error'></div></div>
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
  
  
