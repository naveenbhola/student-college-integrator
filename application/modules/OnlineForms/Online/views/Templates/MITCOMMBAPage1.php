<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
                        <?php if($action != 'updateScore'):?>
			<li>    <h3 class="upperCase">Preferred Course</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Select the name of preferred PGDM Course: </label>
				<div class='fieldBoxLarge' style="width:620px;">
				<input type='checkbox' name='coursePrefMITCOM0' id='coursePrefMITCOM0'   value='1'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>MBA in General Management
                                <div class="spacer10 clearFix"></div>
                                <input type='checkbox' name='coursePrefMITCOM1' id='coursePrefMITCOM1'   value='2'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>MBA-PGP in Retail Business Management
                                <div class="spacer10 clearFix"></div>
                                <input type='checkbox' name='coursePrefMITCOM2' id='coursePrefMITCOM2'   value='3'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>MBA-PGP in Agri & Food Business Management
                                <div class="spacer10 clearFix"></div>
                                <input type='checkbox' name='coursePrefMITCOM3' id='coursePrefMITCOM3'   value='4'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>MBA in Forestry & Environment Management
                                <div class="spacer10 clearFix"></div>
                                <input type='checkbox' name='coursePrefMITCOM4' id='coursePrefMITCOM4'   value='5'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>PGP in Project Construction & Infrastructure Management
                                <div class="spacer10 clearFix"></div>
                                <input type='checkbox' name='coursePrefMITCOM5' id='coursePrefMITCOM5'   value='6'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>Project Construction Management
                                <div class="spacer10 clearFix"></div>
                                <input type='checkbox' name='coursePrefMITCOM6' id='coursePrefMITCOM6'   value='7'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>MBA - LE
&nbsp;&nbsp;
                                <input type='checkbox' name='coursePrefMITCOM7' id='coursePrefMITCOM7'   value='8'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></span>MCA - LE
                                <div class="spacer10 clearFix"></div>
                                

				<?php if(isset($coursePrefMITCOM0) && $coursePrefMITCOM0!=""){ ?>
				<script>

				    objCheckBoxes0 = document.forms["OnlineForm"].elements["coursePrefMITCOM0"];
				    objCheckBoxes0.checked = false;
   				    if(objCheckBoxes0.value == "<?php echo coursePrefMITCOM0;?>")
					 objCheckBoxes0.checked = true;
				</script>
			      <?php } ?>
                              <?php if(isset($coursePrefMITCOM1) && $coursePrefMITCOM1!=""){ ?>
				<script>

				    objCheckBoxes1 = document.forms["OnlineForm"].elements["coursePrefMITCOM1"];
				    objCheckBoxes1.checked = false;
   				    if(objCheckBoxes1.value == "<?php echo $coursePrefMITCOM1;?>")
					 objCheckBoxes1.checked = true;
				</script>
			      <?php } ?>
			      <?php if(isset($coursePrefMITCOM2) && $coursePrefMITCOM2!=""){ ?>
				<script>

				    objCheckBoxes2 = document.forms["OnlineForm"].elements["coursePrefMITCOM2"];
				    objCheckBoxes2.checked = false;
   				    if(objCheckBoxes2.value == "<?php echo $coursePrefMITCOM2;?>")
					 objCheckBoxes2.checked = true;
				</script>
			      <?php } ?>
                              <?php if(isset($coursePrefMITCOM3) && $coursePrefMITCOM3!=""){ ?>
				<script>

				    objCheckBoxes3 = document.forms["OnlineForm"].elements["coursePrefMITCOM3"];
				    objCheckBoxes3.checked = false;
   				    if(objCheckBoxes3.value == "<?php echo $coursePrefMITCOM3;?>")
					 objCheckBoxes3.checked = true;
				</script>
			      <?php } ?>
                              <?php if(isset($coursePrefMITCOM4) && $coursePrefMITCOM4!=""){ ?>
				<script>

				    objCheckBoxes4 = document.forms["OnlineForm"].elements["coursePrefMITCOM4"];
				    objCheckBoxes4.checked = false;
   				    if(objCheckBoxes4.value == "<?php echo $coursePrefMITCOM4;?>")
					 objCheckBoxes4.checked = true;
				</script>
			      <?php } ?>
                              <?php if(isset($coursePrefMITCOM5) && $coursePrefMITCOM5!=""){ ?>
				<script>

				    objCheckBoxes5 = document.forms["OnlineForm"].elements["coursePrefMITCOM5"];
				    objCheckBoxes5.checked = false;
   				    if(objCheckBoxes5.value == "<?php echo $coursePrefMITCOM5;?>")
					 objCheckBoxes5.checked = true;
				</script>
			      <?php } ?>
                              <?php if(isset($coursePrefMITCOM6) && $coursePrefMITCOM6!=""){ ?>
				<script>

				    objCheckBoxes6 = document.forms["OnlineForm"].elements["coursePrefMITCOM6"];
				    objCheckBoxes6.checked = false;
   				    if(objCheckBoxes6.value == "<?php echo $coursePrefMITCOM6;?>")
					 objCheckBoxes6.checked = true;
				</script>
			      <?php } ?>
                              <?php if(isset($coursePrefMITCOM7) && $coursePrefMITCOM7!=""){ ?>
				<script>

				    objCheckBoxes7 = document.forms["OnlineForm"].elements["coursePrefMITCOM7"];
				    objCheckBoxes7.checked = false;
   				    if(objCheckBoxes7.value == "<?php echo $coursePrefMITCOM7;?>")
					 objCheckBoxes7.checked = true;
				</script>
			      <?php } ?>
				<div style='display:none'><div class='errorMsg' id= 'coursePrefMITCOM_error'></div></div>
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
			
		<li>
                                <label style="font-weight:normal; padding-top:0px">Disclaimer:</label>
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
				<p>I, Mr. /Ms. <?php echo $nameOfTheUser;?> have read the above rules and regulation of the MIT College of Management.
I hereby agree to abide by above rules and regulation of the college and I will be liable for punishment, in case I am found
disobeying the order or instruction given by the college authorities from time to time.</p>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsMITCOM' id='agreeToTermsMITCOM'   value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsMITCOM) && $agreeToTermsMITCOM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsMITCOM"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$agreeToTermsMITCOM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsMITCOM_error'></div></div>
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