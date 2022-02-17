
<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
                        <?php if($action != 'updateScore'):?>
                         <li>
				<h3 class="upperCase">Campus where you want to study:</h3>
				<div class='additionalInfoLeftCol'>
				<label>First preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidPreference(1);" name='pref1AGBS' id='pref1AGBS'  style="width:97px;"  tip="Please select the AGBS campus where you prefer to study. Select your first preference."   onmouseover="showTipOnline('Please select the AGBS campus where you prefer to study. Select your first preference.',this);" onmouseout="hidetip();" >
				<option value=''>Select</option>
				<option value='Ahmedabad'>Ahmedabad</option>
                                <option value='Chandigarh' >Chandigarh</option>
                                <option value='Indore'>Indore</option>
                                <option value='Mumbai'>Mumbai</option>
                                <option value='Pune'>Pune</option>
                                <option value='Bangalore'>Bangalore</option>
                                <option value='Chennai'>Chennai</option>
                                <option value='Kochi'>Kochi</option>
                                <option value='Noida'>Noida</option>
                                <option value='Bhubaneshwar'>Bhubaneshwar</option>
                                <option value='Hyderabad'>Hyderabad</option>
                                <option value='Kolkata'>Kolkata</option>
                                <option value='Patna'>Patna</option></select>
				<?php if(isset($pref1AGBS) && $pref1AGBS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref1AGBS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref1AGBS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>  
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref1AGBS_error'></div></div>
				</div>
				</div>

				<div class='additionalInfoRightCol'>
				<label>Second preference: </label>
				<div class='fieldBoxLarge'>
				<select blurMethod="checkValidPreference(2);" name='pref2AGBS' id='pref2AGBS'  style="width:97px;"  tip="Please select the AGBS campus where you prefer to study. Select your second preference."   onmouseover="showTipOnline('Please select the AGBS campus where you prefer to study. Select your second preference.',this);" onmouseout="hidetip();" >
				<option value=''>Select</option>
				<option value='Ahmedabad'>Ahmedabad</option>
                                <option value='Chandigarh' >Chandigarh</option>
                                <option value='Indore'>Indore</option>
                                <option value='Mumbai'>Mumbai</option>
                                <option value='Pune'>Pune</option>
                                <option value='Bangalore'>Bangalore</option>
                                <option value='Chennai'>Chennai</option>
                                <option value='Kochi'>Kochi</option>
                                <option value='Noida'>Noida</option>
                                <option value='Bhubaneshwar'>Bhubaneshwar</option>
                                <option value='Hyderabad'>Hyderabad</option>
                                <option value='Kolkata'>Kolkata</option>
                                <option value='Patna'>Patna</option></select>
				<?php if(isset($pref2AGBS) && $pref2AGBS!=""){ ?>
			      <script>
				  var selObj = document.getElementById("pref2AGBS"); 
				  var A= selObj.options, L= A.length;
				  while(L){
				      if (A[--L].value== "<?php echo $pref2AGBS;?>"){
					  selObj.selectedIndex= L;
					  L= 0;
				      }
				  }
			      </script>
			    <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'pref2AGBS_error'></div></div>
				</div>
				</div>
			</li>

			<?php if(is_array($gdpiLocations)): ?>
			<li>
				<label style="font-weight:normal">Preference of Centre for Group Work / Interview: </label>
				<div class='float_L'>
			<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateStr"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location">
				<option value=''>Select</option>
				<?php foreach($gdpiLocations as $gdpiLocation): ?>
					<?php //if($gdpiLocation['city_name']=='Harihar' || $gdpiLocation['city_name']=='Pune'){ ?>
						<option value='<?php echo $gdpiLocation['city_id']; ?>'><?php echo $gdpiLocation['city_name']; ?></option>
					<?php //} ?>
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
			<?php endif ?>


			<li>
				<label style="font-weight:normal; padding-top:0">Terms:</label>
				<div class='float_L' style="width:620px; color:#666666; font-style:italic">
                                <?php
				$nameOfTheUser = array();
				foreach($basicInformation as $info) {
					if($info['fieldName'] == 'firstName' || $info['fieldName'] == 'middleName' || $info['fieldName'] == 'lastName') {
						$nameOfTheUser[$info['fieldName']] .= $info['value'];
					}
				}
				$nameOfTheUser = $nameOfTheUser['firstName'].' '.$nameOfTheUser['middleName'].' '.$nameOfTheUser['lastName'];
				?>

				I <?php echo $nameOfTheUser; ?>, hereby certify that the information given in theApplication (All relevant Forms) is complete and accurate. I understand and agree that misrepresentation or omission of facts will justify the denial of admission, the cancellation of admission, or expulsion.<BR />
I have read and do hereby consent to the Terms & Conditions or Admission being enclosed with the Application Form.</div>
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsAGBS' id='agreeToTermsAGBS' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;I agree to the terms stated above

			      <?php if(isset($agreeToTermsAGBS) && $agreeToTermsAGBS!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsAGBS"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsKIAMS);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsAGBS_error'></div></div>


				</div>
				
			</li>
			
                     <?php endif ?>
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
  
	function checkValidPreference(id) {
		if(id==1) sId = 2; else sId = 1;
		var selectedPrefObj = document.getElementById('pref'+id+'AGBS'); 
		var selectedPref = selectedPrefObj.options[selectedPrefObj.selectedIndex].value;
		var selObj = document.getElementById('pref'+sId+'AGBS'); 
		var selPref = selObj.options[selObj.selectedIndex].value;
		if(selectedPref == selPref && selectedPrefObj!=''){
			$('pref'+id+'AGBS'+'_error').innerHTML = 'Same preference can’t be set.';
			$('pref'+id+'AGBS'+'_error').parentNode.style.display = '';
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
			$('pref'+id+'AGBS'+'_error').innerHTML = '';
			$('pref'+id+'AGBS'+'_error').parentNode.style.display = 'none';
		}
		return true;
	}
</script>
