
<div>House Number: <input type='text' name='houseNumber' id='houseNumber'   required="true"         title="Enter your House Number"  value=''  /></div>
<?php if(isset($houseNumber) && $houseNumber!=""){ ?>
		<script>
		    document.getElementById("houseNumber").value = "<?php echo $houseNumber; ?>";
		    document.getElementById("houseNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'houseNumber_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Street Name: <input type='text' name='streetName' id='streetName'   required="true"         title="Enter your Street Name"  value=''  /></div>
<?php if(isset($streetName) && $streetName!=""){ ?>
		<script>
		    document.getElementById("streetName").value = "<?php echo $streetName; ?>";
		    document.getElementById("streetName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'streetName_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Area/Locality: <input type='text' name='area' id='area'   required="true"         title="Enter your Area Name"  value=''  /></div>
<?php if(isset($area) && $area!=""){ ?>
		<script>
		    document.getElementById("area").value = "<?php echo $area; ?>";
		    document.getElementById("area").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'area_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Country: <select name='country' id='country'     title="Select your Country"    required="true"   onchange='getCitiesForCountryOnline("",false,"",false);'><?php foreach($country_list as $countryItem) {
							$countryId = $countryItem["countryID"];
							$countryName = $countryItem["countryName"];
							if($countryId == 1) { continue; }
								if($countryId == $country) { $selected = "selected"; }
							else { $selected = ""; } ?>
						<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php } ?><option value='others' > others </option></select></div><div style='display:none'><div class='errorMsg' id= 'country_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>City: <select name='city' id='city'     title="Select your City"    required="true"    /></select></div><div style='display:none'><div class='errorMsg' id= 'city_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Pincode: <input type='text' name='pincode' id='pincode'  validate="validateInteger"   required="true"   caption="Pincode"   minlength="4"   maxlength="8"      title="Enter your Pincode"  value=''  /></div>
<?php if(isset($pincode) && $pincode!=""){ ?>
		<script>
		    document.getElementById("pincode").value = "<?php echo $pincode; ?>";
		    document.getElementById("pincode").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'pincode_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>House Number: <input type='text' name='ChouseNumber' id='ChouseNumber'   required="true"         title="Enter your House Number"  value=''  /></div>
<?php if(isset($ChouseNumber) && $ChouseNumber!=""){ ?>
		<script>
		    document.getElementById("ChouseNumber").value = "<?php echo $ChouseNumber; ?>";
		    document.getElementById("ChouseNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'ChouseNumber_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Street Name: <input type='text' name='CstreetName' id='CstreetName'   required="true"         title="Enter your Street Name"  value=''  /></div>
<?php if(isset($CstreetName) && $CstreetName!=""){ ?>
		<script>
		    document.getElementById("CstreetName").value = "<?php echo $CstreetName; ?>";
		    document.getElementById("CstreetName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'CstreetName_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Area/Locality: <input type='text' name='Carea' id='Carea'   required="true"         title="Enter your Area Name"  value=''  /></div>
<?php if(isset($Carea) && $Carea!=""){ ?>
		<script>
		    document.getElementById("Carea").value = "<?php echo $Carea; ?>";
		    document.getElementById("Carea").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'Carea_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Country: <select name='Ccountry' id='Ccountry'     title="Select your Country"    required="true"   onchange='getCitiesForCountryOnlineCorrespondence("",false,"",false);'><?php foreach($country_list as $countryItem) {
							$countryId = $countryItem["countryID"];
							$countryName = $countryItem["countryName"];
							if($countryId == 1) { continue; }
								if($countryId == $country) { $selected = "selected"; }
							else { $selected = ""; } ?>
						<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php } ?><option value='others' > others </option></select></div><input type='checkbox' onclick='copyAddressFields();'>&nbsp;Same as permanent address<div style='display:none'><div class='errorMsg' id= 'Ccountry_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>City: <select name='Ccity' id='Ccity'     title="Select your City"    required="true"    /></select></div><div style='display:none'><div class='errorMsg' id= 'Ccity_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Pincode: <input type='text' name='Cpincode' id='Cpincode'  validate="validateInteger"   required="true"   caption="Pincode"   minlength="4"   maxlength="8"      title="Enter your Pincode"  value=''  /></div>
<?php if(isset($Cpincode) && $Cpincode!=""){ ?>
		<script>
		    document.getElementById("Cpincode").value = "<?php echo $Cpincode; ?>";
		    document.getElementById("Cpincode").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'Cpincode_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Landline phone number: <select name='landlineISDCode' id='landlineISDCode'     title="Select the Landline ISD code"    required="true"  ><option value='+91' selected>+91</option><option value='+11' >+11</option><option value='+22' >+22</option><option value='+33' >+33</option><option value='+44' >+44</option></select></div>
<?php if(isset($landlineISDCode) && $landlineISDCode!=""){ ?>
		<script>
		    var selObj = document.getElementById("landlineISDCode"); 
		    var A= selObj.options, L= A.length;
		    while(L){
			if (A[--L].value== "<?php echo $landlineISDCode;?>"){
			    selObj.selectedIndex= L;
			    L= 0;
			}
		    }
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'landlineISDCode_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>: <input type='text' name='landlineSTDCode' id='landlineSTDCode'  validate="validateInteger"   required="true"   caption="STD Code"   minlength="2"   maxlength="4"      title="Enter the Landline STD code"  value=''  /></div>
<?php if(isset($landlineSTDCode) && $landlineSTDCode!=""){ ?>
		<script>
		    document.getElementById("landlineSTDCode").value = "<?php echo $landlineSTDCode; ?>";
		    document.getElementById("landlineSTDCode").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'landlineSTDCode_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>: <input type='text' name='landlineNumber' id='landlineNumber'  validate="validateInteger"   required="true"   caption="Landline Number"   minlength="4"   maxlength="10"     tip="Enter Landline Number"   title="Enter Landline Number"  value=''  /></div>
<?php if(isset($landlineNumber) && $landlineNumber!=""){ ?>
		<script>
		    document.getElementById("landlineNumber").value = "<?php echo $landlineNumber; ?>";
		    document.getElementById("landlineNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'landlineNumber_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Mobile Phone Number: <select name='mobileISDCode' id='mobileISDCode'    tip="Enter the Mobile ISD code"   title="Enter the Mobile ISD code"   validate="validateInteger"   required="true"   caption="STD Code" ><option value='+91' selected>+91</option><option value='+11' >+11</option><option value='+22' >+22</option><option value='+33' >+33</option><option value='+44' >+44</option></select></div>
<?php if(isset($mobileISDCode) && $mobileISDCode!=""){ ?>
		<script>
		    var selObj = document.getElementById("mobileISDCode"); 
		    var A= selObj.options, L= A.length;
		    while(L){
			if (A[--L].value== "<?php echo $mobileISDCode;?>"){
			    selObj.selectedIndex= L;
			    L= 0;
			}
		    }
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mobileISDCode_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>: <input type='text' name='mobileSTDCode' id='mobileSTDCode'   required="true"        tip="Enter Mobile STD Code"   title="Enter Mobile STD Code"  value=''  /></div>
<?php if(isset($mobileSTDCode) && $mobileSTDCode!=""){ ?>
		<script>
		    document.getElementById("mobileSTDCode").value = "<?php echo $mobileSTDCode; ?>";
		    document.getElementById("mobileSTDCode").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mobileSTDCode_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>: <input type='text' name='mobileNumber' id='mobileNumber'  validate="validateMobileInteger"   required="true"   caption="Mobile Number"   minlength="10"   maxlength="10"     tip="Enter Mobile Number"   title="Enter Mobile Number"  value=''  /></div>
<?php if(isset($mobileNumber) && $mobileNumber!=""){ ?>
		<script>
		    document.getElementById("mobileNumber").value = "<?php echo $mobileNumber; ?>";
		    document.getElementById("mobileNumber").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'mobileNumber_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Fathers Name: <input type='text' name='fatherName' id='fatherName'  validate="validateStr"   required="true"   caption="Fathers Name"   minlength="3"   maxlength="50"     tip="Enter Fathers Name"   title="Enter Fathers Name"  value=''  /></div>
<?php if(isset($fatherName) && $fatherName!=""){ ?>
		<script>
		    document.getElementById("fatherName").value = "<?php echo $fatherName; ?>";
		    document.getElementById("fatherName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'fatherName_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Occupation: <input type='text' name='fatherOccupation' id='fatherOccupation'  validate="validateStr"   required="true"   caption="Occupation"   minlength="3"   maxlength="50"     tip="Enter Fathers Occupation"   title="Enter Fathers Occupation"  value=''  /></div>
<?php if(isset($fatherOccupation) && $fatherOccupation!=""){ ?>
		<script>
		    document.getElementById("fatherOccupation").value = "<?php echo $fatherOccupation; ?>";
		    document.getElementById("fatherOccupation").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'fatherOccupation_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Designation: <input type='text' name='fatherDesignation' id='fatherDesignation'  validate="validateStr"   required="true"   caption="Designation"   minlength="3"   maxlength="50"     tip="Enter Fathers Designation"   title="Enter Fathers Designation"  value=''  /></div>
<?php if(isset($fatherDesignation) && $fatherDesignation!=""){ ?>
		<script>
		    document.getElementById("fatherDesignation").value = "<?php echo $fatherDesignation; ?>";
		    document.getElementById("fatherDesignation").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'fatherDesignation_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Mothers Name: <input type='text' name='MotherName' id='MotherName'  validate="validateStr"   required="true"   caption="Mothers Name"   minlength="3"   maxlength="50"     tip="Enter Mothers Name"   title="Enter Mothers Name"  value=''  /></div>
<?php if(isset($MotherName) && $MotherName!=""){ ?>
		<script>
		    document.getElementById("MotherName").value = "<?php echo $MotherName; ?>";
		    document.getElementById("MotherName").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'MotherName_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Occupation: <input type='text' name='MotherOccupation' id='MotherOccupation'  validate="validateStr"   required="true"   caption="Occupation"   minlength="3"   maxlength="50"     tip="Enter Mothers Occupation"   title="Enter Mothers Occupation"  value=''  /></div>
<?php if(isset($MotherOccupation) && $MotherOccupation!=""){ ?>
		<script>
		    document.getElementById("MotherOccupation").value = "<?php echo $MotherOccupation; ?>";
		    document.getElementById("MotherOccupation").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'MotherOccupation_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script>
<div>Designation: <input type='text' name='MotherDesignation' id='MotherDesignation'  validate="validateStr"   required="true"   caption="Designation"   minlength="3"   maxlength="50"     tip="Enter Mothers Designation"   title="Enter Mothers Designation"  value=''  /></div>
<?php if(isset($MotherDesignation) && $MotherDesignation!=""){ ?>
		<script>
		    document.getElementById("MotherDesignation").value = "<?php echo $MotherDesignation; ?>";
		    document.getElementById("MotherDesignation").style.color = "";
		</script>
	      <?php } ?>
<div style='display:none'><div class='errorMsg' id= 'MotherDesignation_error'></div></div>
<div class='lineSpace_10'></div><script>getCitiesForCountryOnline("",false,"",false);</script><script>getCitiesForCountryOnlineCorrespondence("",false,"",false);</script><?php if(isset($city) && $city!=""){ ?>
    <script>
	var selObj = document.getElementById("city"); 
	var A= selObj.options, L= A.length;
	while(L){
	    if (A[--L].innerHTML == "<?php echo $city;?>" || A[--L].value == "<?php echo $city;?>"){
		selObj.selectedIndex= L;
		L= 0;
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