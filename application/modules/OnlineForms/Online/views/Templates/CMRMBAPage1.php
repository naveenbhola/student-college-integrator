<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>
			<?php if($action != 'updateScore'):?>
			<li>    <h3 class="upperCase">Preferred Course</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Tick() the Name of the Course Applied: </label>
				<div class='fieldBoxLarge' style="width:620px;">

                <input type='checkbox' name='coursePrefCMR3' id='coursePrefCMR3'   value='PGDM - CMR CENTER FOR BUISNESS STUDIES'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" >MBA(BU)* - CMR CENTER FOR BUSINESS STUDIES</span><br />

                <input type='checkbox' name='coursePrefCMR1' id='coursePrefCMR1'   value='PGDM - CMR INSTITUTE OF MANAGEMENT STUDIES (AUTONOMOUS)'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" >MBA(BU)* - CMR INSTITUTE OF MANAGEMENT STUDIES (AUTONOMOUS)</span><br />

                <input type='checkbox' name='coursePrefCMR2' id='coursePrefCMR2'   value='PGDM - CMR INSTITUTE OF TECHNOLOGY'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" >MBA(VTU)** - CMR INSTITUTE OF TECHNOLOGY</span><br />

				<input type='checkbox' name='coursePrefCMR0' id='coursePrefCMR0'   value='PGDM - CMR INSTITUTE OF MANAGEMENT AND TECHNOLOGY'    onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();"  ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" >PGDM - CMR INSTITUTE OF MANAGEMENT AND TECHNOLOGY</span><br />


				<input type='checkbox' name='coursePrefCMR4' id='coursePrefCMR4'   value='PGDM - CMRS BANGALORE SCHOOL OF BUISNESS'     onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program that you wish to apply for. You can choose multiple programmes',this);" onmouseout="hidetip();" >PGDM - CMR BANGALORE SCHOOL OF BUSINESS</span>&nbsp;&nbsp;

				<?php if(isset($coursePrefCMR0) && $coursePrefCMR0!=""){ ?>
				<script>
				    
				    objCheckBoxes0 = document.forms["OnlineForm"].elements["coursePrefCMR0"];
				    objCheckBoxes0.checked = false;
   				    if(objCheckBoxes0.value == "<?php echo $coursePrefCMR0;?>")
					 objCheckBoxes0.checked = true;
				</script>
			      	<?php } ?>
				<?php if(isset($coursePrefCMR1) && $coursePrefCMR1!=""){ ?>
				<script>
				    
				    objCheckBoxes1 = document.forms["OnlineForm"].elements["coursePrefCMR1"];
				    objCheckBoxes1.checked = false;
   				    if(objCheckBoxes1.value == "<?php echo $coursePrefCMR1;?>")
					 objCheckBoxes1.checked = true;
				</script>
			      <?php } ?><?php //echo "ssssssss".$coursePrefCMR2;?>
				<?php if(isset($coursePrefCMR2) && $coursePrefCMR2!=""){ ?>
				<script>
				    
				    objCheckBoxes2 = document.forms["OnlineForm"].elements["coursePrefCMR2"];
				    objCheckBoxes2.checked = false;
   				    if(objCheckBoxes2.value == "<?php echo $coursePrefCMR2;?>")
					 objCheckBoxes2.checked = true;
				</script>
			      <?php } ?>

				<?php if(isset($coursePrefCMR3) && $coursePrefCMR3!=""){ ?>
				<script>
				    
				    objCheckBoxes3 = document.forms["OnlineForm"].elements["coursePrefCMR3"];
				    objCheckBoxes3.checked = false;
   				    if(objCheckBoxes3.value == "<?php echo $coursePrefCMR3;?>")
					 objCheckBoxes3.checked = true;
				</script>
			      <?php } ?>

			      <?php if(isset($coursePrefCMR4) && $coursePrefCMR4!=""){ ?>
				<script>
				    
				    objCheckBoxes4 = document.forms["OnlineForm"].elements["coursePrefCMR4"];
				    objCheckBoxes4.checked = false;
   				    if(objCheckBoxes4.value == "<?php echo $coursePrefCMR4;?>")
					 objCheckBoxes4.checked = true;
				</script>
			      <?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'coursePrefCMR_error'></div></div>
				<div class="spacer10 clearFix"></div>
				<div style="width:600px; color:#666666; font-style:italic; float:left;font-size:11px;">*Bangalore University&nbsp;&nbsp;**Visvesvaraya Technological University</div>
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
				<?php if(!empty($coursePrefCMR0)) $coursePrefCMRVal0='CMR INSTITUTE OF MANAGEMENT AND TECHNOGOLY';else $coursePrefCMRVal0='<strike>CMR INSTITUTE OF MANAGEMENT AND TECHNOGOLY</strike>';?>
				<?php if(!empty($coursePrefCMR1)) $coursePrefCMRVal1='CMR INSTITUTE OF MANAGEMENT STUDIES(AUTONOMOUS)';else $coursePrefCMRVal1='<strike>CMR INSTITUTE OF MANAGEMENT STUDIES(AUTONOMOUS)</strike>';?>
				<?php if(!empty($coursePrefCMR2)) $coursePrefCMRVal2='CMR INSTITUTE OF TECHNOLOGY';else $coursePrefCMRVal2='<strike>CMR INSTITUTE OF TECHNOLOGY</strike>';?>		
				<?php if(!empty($coursePrefCMR3)) $coursePrefCMRVal3='CMR CENTRE FOR BUISNESS STUDIES';else $coursePrefCMRVal3='<strike>CMR CENTRE FOR BUISNESS STUDIES</strike>';?>
				<?php if(!empty($coursePrefCMR4)) $coursePrefCMRVal4='CMR BANGALORE SCHOOL OF BUISNESS';else $coursePrefCMRVal4='<strike>CMR BANGALORE SCHOOL OF BUISNESS</strike>';?>
				I,<?php echo $nameOfTheUser; ?> agree to abide by the rules and regulation of &nbsp;<?php echo $coursePrefCMRVal0;?>&nbsp;/&nbsp;<?php echo $coursePrefCMRVal1;?>&nbsp;/&nbsp;<?php echo $coursePrefCMRVal2;?>&nbsp;/&nbsp;<?php echo $coursePrefCMRVal3;?>&nbsp;/&nbsp;<?php echo $coursePrefCMRVal4;?> and declare that the information provided above is true and correct to the best of my knowledge and belief and in the event of any information being found incorrect or misleading, that my candidature shall be liable to be cancelled.If I am Provisionally admitted and incase the University rejects my eligibility at a later date, I will not claim my refund from the college and I will not hold the college responsible.
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsCMR' id='agreeToTermsCMR'   value='1'  required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsCMR) && $agreeToTermsCMR!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsCMR"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					     objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsCMR);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsCMR_error'></div></div>
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
