<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
	
			<li>
				<h3 class="upperCase">Preferred Course</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Select the name of preferred PGDM Course: </label>
				<div class='fieldBoxLarge' style="width:620px;">
				<input type='checkbox' name='coursePrefSURYADATTA0' id='coursePrefSURYADATTA0'   value='PGDM' onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for.',this);" onmouseout="hidetip();" >PGDM with Dual Specialization</span>
				<?php if(isset($coursePrefSURYADATTA0) && $coursePrefSURYADATTA0!=""){ ?>
				<script>
				    
				    objCheckBoxes0 = document.forms["OnlineForm"].elements["coursePrefSURYADATTA0"];
				    objCheckBoxes0.checked = false;
   				    if(objCheckBoxes0.value == "<?php echo $coursePrefSURYADATTA0;?>")
					 objCheckBoxes0.checked = true;
				</script>
			      	<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'coursePrefSURYADATTA_error'></div></div>
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
				<p>I hereby, submit myself to the disciplinary authority of the Institute and to rules laid down by concerned competent authorities. I am aware of the fact that I need to maintain the requisite attendance for the academic and other activities by Virtue of my being a student of this institute, failing which the Institute has the full and ﬁnal authority to initiate disciplinary action against me, as per the rules and regulations of the institute. I hereby agree to comply with all the rules and regulations of the Institute . In case of any dispute I agree that the decision of the Institute authorities will be final and binding on me. I have also read, understood and accepted all the codes for discipline, academic standards & mandatory information as mentioned in proﬁle of various institutes of Suryadatta Group, the contents of this admission prospectus, website, undertaking of the institute & I shall take note of all communication put up from time to time.
</p>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsSURYADATTA' id='agreeToTermsSURYADATTA'  value='1'  required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsSURYADATTA) && $agreeToTermsSURYADATTA!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsSURYADATTA"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$agreeToTermsSURYADATTA);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsSURYADATTA_error'></div></div>
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
