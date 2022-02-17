<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<h3 class="upperCase">Preference for GD & PI Centres</h3>
				<div class='additionalInfoLeftCol'>
				<label>1st Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(1);" name='pref1Kohinoor' id='pref1Kohinoor'  style="width:120px;"  tip="Select your 1st preference of campus from the list."       onmouseover="showTipOnline('Select your 1st preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Agra' >Agra</option><option value='Ahmedabad' >Ahmedabad</option><option value='Bangalore' >Bangalore</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Dehradun' >Dehradun</option><option value='Delhi' >Delhi</option><option value='Hisar' >Hissar</option><option value='Indore' >Indore</option><option value='Jaipur' >Jaipur</option><option value='Jamshedpur' >Jamshedpur</option><option value='Kanpur' >Kanpur</option><option value='Khandala' >Khandala</option><option value='Kolkata' >Kolkatta</option><option value='Lucknow' >Lucknow</option><option value='Mumbai' >Mumbai</option><option value='Hyderabad'>Hyderabad</option><option value='Chennai' >Chennai</option><option value='Patna'>Patna</option><option value='Allahabad' >Allahabad</option>
				</select>
				<?php if(isset($pref1Kohinoor) && $pref1Kohinoor!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref1Kohinoor"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref1Kohinoor;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref1Kohinoor_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>2nd Preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidCampusPreference(2);" name='pref2Kohinoor' id='pref2Kohinoor'  style="width:120px;"   tip="Select your 2nd preference of campus from the list."       onmouseover="showTipOnline('Select your 2nd preference of campus from the list.',this);" onmouseout="hidetip();" >
				      <option value='' selected>Select</option><option value='Agra' >Agra</option><option value='Ahmedabad' >Ahmedabad</option><option value='Bangalore' >Bangalore</option><option value='Bhubaneswar' >Bhubaneswar</option><option value='Dehradun' >Dehradun</option><option value='Delhi' >Delhi</option><option value='Hisar' >Hissar</option><option value='Indore' >Indore</option><option value='Jaipur' >Jaipur</option><option value='Jamshedpur' >Jamshedpur</option><option value='Kanpur' >Kanpur</option><option value='Khandala' >Khandala</option><option value='Kolkata' >Kolkatta</option><option value='Lucknow' >Lucknow</option><option value='Mumbai' >Mumbai</option><option value='Hyderabad'>Hyderabad</option><option value='Chennai' >Chennai</option><option value='Patna'>Patna</option><option value='Allahabad' >Allahabad</option>
				</select>
				<?php if(isset($pref2Kohinoor) && $pref2Kohinoor!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2Kohinoor"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2Kohinoor;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2Kohinoor_error'></div></div>
				</div>
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
				I, <?php echo $nameOfTheUser; ?>, certify that the information furnished in this application is true to the best of my knowledge. My application may be rejected and admission shall be cancelled if any information provided herein is found to be incorrect at any time during or after admission.
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsKohinoor' id='agreeToTermsKohinoor'  value='1'  required="true" caption="Please agree to the terms stated above" validate="validateChecked"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsKohinoor) && $agreeToTermsKohinoor!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsKohinoor"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsKohinoor);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsKohinoor_error'></div></div>
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
  function checkValidCampusPreference(id){
	if(id==1){ sId = 2;}
	else { sId = 1;}
	var selectedPrefObj = document.getElementById('pref'+id+'Kohinoor');
	var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
	var selObj1 = document.getElementById('pref'+sId+'Kohinoor'); 
	var selPref1 = selObj1.options[selObj1.selectedIndex].value;
	

	if( (selectedPref == selPref1 && selectedPref!='' )){
		$('pref'+id+'Kohinoor'+'_error').innerHTML = 'Same preference can’t be set.';
		$('pref'+id+'Kohinoor'+'_error').parentNode.style.display = '';
		//Select the blank option
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
		$('pref'+id+'Kohinoor'+'_error').innerHTML = '';
		$('pref'+id+'Kohinoor'+'_error').parentNode.style.display = 'none';
	}
	return true;
  }
  </script>
