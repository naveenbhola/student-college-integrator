<script>
  function checkTestScore(obj){
	var key = obj.value.toLowerCase();
	var objects1 = new Array(key+"RollNumberAdditional",key+"ScoreAdditional",key+"DateOfExaminationAdditional",key+"RankAdditional",key+"PercentileAdditional",key+"PercentileDummy");
	if(obj && $(key+"1") && $(key+"2")){
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
			document.getElementById(objectsArr[i]+'_error').innerHTML = '';
			document.getElementById(objectsArr[i]+'_error').parentNode.style.display = 'none';
		}
	}
  }
</script>


<div class='formChildWrapper'>
	<div class='formSection'>
		<ul>



			<li>
				<h3 class=upperCase'>Declaration</h3>
				<label style="font-weight:normal; padding-top:0">Declaration:</label>
				<div class='fieldBoxLarge' style="width:620px; color:#666666; font-style:italic">
				<div class="spacer10 clearFix"></div>
				<div >
				<input type='checkbox' name='agreeToTermsDummy' id='agreeToTermsDummy' value='1' required="true" validate="validateChecked" caption="Please agree to the terms stated above"></input>&nbsp;&nbsp;
				I agree to the terms stated above

			      <?php if(isset($agreeToTermsDummy) && $agreeToTermsDummy!=""){ ?>
				<script>
				    objCheckBoxes = document.forms["OnlineForm"].elements["agreeToTermsDummy"];
				    var countCheckBoxes = 1;
				    for(var i = 0; i < countCheckBoxes; i++){ 
					      objCheckBoxes.checked = false;
					      <?php $arr = explode(",",$agreeToTermsDummy);
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
				<div style='display:none'><div class='errorMsg' id= 'agreeToTermsDummy_error'></div></div>

				</div>
				</div>
			</li>

		</ul>
	</div>
</div>

<script>	
	function showMoreGroups(groupId,maxAllowed)
	{
		if(document.getElementById(groupId))
		{
			for(i=1;i<=maxAllowed;i++)
			{
				if(document.getElementById(String(groupId)+String(i)).style.display == 'none'){
				document.getElementById(String(groupId)+String(i)).style.display = '';
				break;
			}
		}
		if(i==maxAllowed)
		{
		  document.getElementById('showMore'+groupId).style.display = 'none';
		}
		}
	}
	function copyAddressFields()
	{
		if(document.getElementById('city') && document.getElementById('Ccity'))
		{
			document.getElementById('ChouseNumber').value = document.getElementById('houseNumber').value;
			document.getElementById('CstreetName').value = document.getElementById('streetName').value;
			document.getElementById('Carea').value = document.getElementById('area').value;
			document.getElementById('Cpincode').value = document.getElementById('pincode').value;
			var sel = document.getElementById('country');
			var countrySelected = sel.options[sel.selectedIndex].value;
			var selObj = document.getElementById('Ccountry'); 
			var A= selObj.options, L= A.length;
			while(L){
				if (A[--L].value== countrySelected)
				{
					selObj.selectedIndex= L;
					L= 0;
				}
			}
			getCitiesForCountryOnlineCorrespondence("",false,"",false);
	
			var sel = document.getElementById('city');
			var citySelected = sel.options[sel.selectedIndex].value;
			var selObj = document.getElementById('Ccity'); 
			var A= selObj.options, L= A.length;
			while(L)
			{
				if (A[--L].value== citySelected)
				{
					selObj.selectedIndex= L;
					L= 0;
				}
			}
		}
	}
  
</script>
