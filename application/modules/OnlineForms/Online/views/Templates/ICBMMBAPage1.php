<div class='formChildWrapper'>
	<div class='formSection'>
		<ul> 
			<?php if($action != 'updateScore'):?>
			<li>	<h3 class="upperCase">Preferred Course</h3>
				<div class='additionalInfoLeftCol' style="width:100%">
				<label>Select the name of preferred PGDM Course: </label>
				<div class='fieldBoxLarge' style="width:620px;">
				<input type='checkbox' name='coursePrefICBM0' id='coursePrefICBM0'   value='MBA'  onmouseover="showTipOnline('Choose the PGDM program.',this);" onmouseout="hidetip();" ></input><span  onmouseover="showTipOnline('Choose the PGDM program.',this);" onmouseout="hidetip();" >Post Graduate Diploma in Business Management (PGDM)</span><br/>
				<?php if(isset($coursePrefICBM0) && $coursePrefICBM0!=""){ ?>
				<script>
				    
				    objCheckBoxes0 = document.forms["OnlineForm"].elements["coursePrefICBM0"];
				    objCheckBoxes0.checked = false;
   				    if(objCheckBoxes0.value == "<?php echo $coursePrefICBM0;?>")
					 objCheckBoxes0.checked = true;
				</script>
			      	<?php } ?>
				
				<div style='display:none'><div class='errorMsg' id= 'coursePrefICBM_error'></div></div>
				</div>
				</div>
			</li>


			<li>	<label style="font-weight:normal; padding-top:0px">Disclaimer:</label>
				<div class='float_L' style="width:600px; color:#666666; font-style:italic; float:left">
				<p>I confirm that I have read the Prospectus for the PGDM program. I am registering for the programe and understand the terms and
conditions of admission fully. I understand that ICBM-SBE, Hyderabad reserves the right to accept or reject my application. I
confirm that I will pay the fee to ICBM-SBE as prescribed without delay. I understand that my studentship with the ICBM-SBE
shall be terminated if I engage myself in any agitations / strikes or any other activities which disturb the study atmosphere or am
charged with misbehaviour or misconduct; or in the event if I fail to pay the fee within the prescribed period. I further agree that I
shall abide by all the rules and regulations that may be prescribed by ICBM-SBE, Hyderabad time to time. I further affirm that the
above information provided in this application is true and correct, and the application bears my true signature and photo. I
understand that the fee once paid is non refundable and non transferable.
</p>
				</div>
				<div class="spacer10 clearFix"></div>
				<div class='additionalInfoLeftCol'>
				<label>I agree to the terms stated above: </label>
				<div class='fieldBoxLarge'>
				<input type='checkbox' name='agreeToTermsICBM' id='agreeToTermsICBM'  value='1'  required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input><span ><span>&nbsp;&nbsp;
				<?php if(isset($agreeToTermsICBM) && $agreeToTermsICBM!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsICBM"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){
					      objCheckBoxes.checked = false;

					      <?php $arr = explode(",",$agreeToTermsICBM);
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
				
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsICBM_error'></div></div>
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
