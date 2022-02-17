<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<li>
				<h3 class="upperCase">Preferred Course</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Select the name of preferred PGDM Course: </label>
				<div class='fieldBoxLarge' style="width:620px;">
				<input type='checkbox' name='coursePrefJGIiDEA0' id='coursePrefJGIiDEA0'   value='MBA' onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for.',this);" onmouseout="hidetip();" >MBA + PGP in Entrepreneurship</span>
				<?php if(isset($coursePrefJGIiDEA0) && $coursePrefJGIiDEA0!=""){ ?>
				<script>
				    
				    objCheckBoxes0 = document.forms["OnlineForm"].elements["coursePrefJGIiDEA0"];
				    objCheckBoxes0.checked = false;
   				    if(objCheckBoxes0.value == "<?php echo $coursePrefJGIiDEA0;?>")
					 objCheckBoxes0.checked = true;
				</script>
			      	<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'coursePrefJGIiDEA_error'></div></div>
				</div>
				</div>
			</li>
			<?php if(is_array($gdpiLocations)): ?>
                        <li>
				<label style="font-weight:normal">Preferred GD/PI location: </label>
				<div class='fieldBoxLarge'>
				<select name='preferredGDPILocation' id='preferredGDPILocation' onmouseover="showTipOnline('Select your preferred GD/PI location.',this);" onmouseout='hidetip();'  validate="validateSelect"  minlength="1"   maxlength="1500"  required="true" caption="Preferred GD/PI location" style="width:100px;">
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
				<p>I, Mr. /Ms. <?php echo $nameOfTheUser;?> have read the above rules and regulation of the JGI iDEA. I hereby agree to abide by above rules and regulation of the college and I will be liable for punishment, in case I am found disobeying the order or instruction given by the college authorities from time to time.
</p>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsJGIiDEA' id='agreeToTermsJGIiDEA'  value='1'  required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsJGIiDEA) && $agreeToTermsJGIiDEA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsJGIiDEA"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$agreeToTermsJGIiDEA);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsJGIiDEA_error'></div></div>
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
