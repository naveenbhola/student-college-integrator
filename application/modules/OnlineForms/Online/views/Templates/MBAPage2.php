        <div class="formChildWrapper">
        	<div class="formSection">
                <h2>Address</h2><br/>
                <h3 class="noBorder">Permanent Address <a id="addressExampleLink" class="seeExample" href="javascript:void(0);" onmouseover="showTipOnline('10, Janpath, Connaught Place, New Delhi – 110011, Delhi, India<br/><br/>Here:<br/>- <b>House Number</b> is <b>10</b><br/>- <b>Street Name</b> is <b>Janpath</b><br/>- <b>Area/Locality</b> is <b>Connaught Place</b><br/>- <b>Country</b> is <b>India</b><br/>- <b>City</b> is <b>New Delhi</b><br/>- <b>Pincode</b> is <b>110011</b>',this);" onmouseout="hidetip();">See example</a></h3>
                <ul>
		    <li>
                        <div class="formColumns">
			    <label>House Number: </label>
			    <div class="fieldBoxSmall">
			    <input type='text' name='houseNumber' id='houseNumber'   required="true"  tip="Name or Number of the house you own, e.g. 45A or Woodstock Villa, or 49B Jeevan Apartments"   value=''  validate="validateStr"   caption="House Number"   minlength="2"   maxlength="50" />
			    <?php if(isset($houseNumber) && $houseNumber!=""){ ?>
					    <script>
						document.getElementById("houseNumber").value = "<?php echo str_replace("\n", '\n', $houseNumber );  ?>";
						document.getElementById("houseNumber").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'houseNumber_error'></div></div>
			    </div>
                        </div>

                        <div class="formColumns">
			    <label>Street Name: </label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='streetName' id='streetName'   class = "textboxLarge" validate="validateStr"  minlength="2"   maxlength="50" caption="either Street Name or Area/Locality"  tip="Street on which the house is located, e.g. Bhagat Singh Marg or Veera Desai Road."   value=''  />
			    <?php if(isset($streetName) && $streetName!=""){ ?>
				    <script>
					document.getElementById("streetName").value = "<?php echo str_replace("\n", '\n', $streetName );  ?>";
					document.getElementById("streetName").style.color = "";
				    </script>
				  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'streetName_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    <label>Area/Locality: </label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='area' id='area' validate="validateStr"   caption="either Area/Locality or Street Name"   minlength="2"   maxlength="50"  class = "textboxLarge"   tip="Name of locality or area on which the house is situated, e.g. South Extension or Andheri East."   value=''  />
			    <?php if(isset($area) && $area!=""){ ?>
					    <script>
						document.getElementById("area").value = "<?php echo str_replace("\n", '\n', $area );  ?>";
						document.getElementById("area").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'area_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    <label>Country: </label>
			    <div class="float_L">
			    <select class="selectBoxMedium" name='country' id='country'    onmouseover="showTipOnline('Choose the country of your residence.',this);" onmouseout="hidetip();"  required="true"   onchange='getCitiesForCountryOnline("",false,"",false); setState(this.value,"state");'><?php foreach($country_list as $countryItem) {
							$countryId = $countryItem["countryID"];
							$countryName = $countryItem["countryName"];
							if($countryId == 1) { continue; }
								if($countryId == $country) { $selected = "selected"; }
							else { $selected = ""; } ?>
						<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php } ?></select>
			    <div style='display:none'><div class='errorMsg' id= 'country_error'></div></div>
			    </div>
                        </div>

						<div class="formColumns" <?php if($city == '') { echo 'style="display:none"'; } ?>>
							<label>City: </label>
							<div class="float_L">
								<select class="selectBoxLarge" name='city' id='city'   onmouseover="showTipOnline('Choose the city in which the house is located. If the city is not on the list, choose the nearest city here and write the name of your city in the text box corresponding to Area/Locality name.',this);" onmouseout="hidetip();"  required="true" validate="validateSelect"  caption = "City" onChange = "fillState(this.value,'state');"/></select>
								<div style='display:none'><div class='errorMsg' id= 'city_error'></div></div>
							</div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
							<label>State: </label>
							<div class="fieldBoxLarge">
								<input class="textboxLarge" type='text' name='state' id='state'  validate="validateStr"   required="true"   caption="State"   minlength="2"   maxlength="25"     tip="Please type your State. If you are an Indian, please select the city and state will be populated."   value=''  readonly/>
								<?php if(isset($state) && $state!=""){ ?>
								<script>
								document.getElementById("state").value = "<?php echo str_replace("\n", '\n', $state);  ?>";
								document.getElementById("state").style.color = "";
								</script>
								<?php } ?>
								<div style='display:none'><div class='errorMsg' id= 'state_error'></div></div>
							</div>
                        </div>
  
                        <div class="formColumns">
			    <label>Pincode: </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxSmall" type='text' name='pincode' id='pincode'  validate="validateInteger"   required="true"   caption="Pincode"   minlength=<?php  if($country =='2'){echo '6';}else{echo '4';} ?>   maxlength=<?php  if($country =='2'){echo '6';}else{echo '8';} ?>     tip="Please type in your <?php  if($country =='2'){echo '6';}else{echo '4';} ?> pin code here."   value=''  />
			    <?php if(isset($pincode) && $pincode!=""){ ?>
					    <script>
						document.getElementById("pincode").value = "<?php echo str_replace("\n", '\n', $pincode );  ?>";
						document.getElementById("pincode").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'pincode_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>

            	<div class="clearFix"></div>
            	<h3 style="margin-bottom:10px !important; border:none">Correspondence Address:</h3>
                <br/><input type='checkbox' onclick='copyAddressFields();' id="copyAddress">&nbsp;Same as permanent address
                <div class="clearFix spacer15"></div>

                <ul>
                    <li>
                        <div class="formColumns">
			    <label>House Number: </label>
			    <div class="fieldBoxSmall">
			    <input type='text' name='ChouseNumber' id='ChouseNumber'   required="true"         title="Enter your House Number"  value=''  validate="validateStr"   caption="House Number"   minlength="2"   maxlength="50" />
			    <?php if(isset($ChouseNumber) && $ChouseNumber!=""){ ?>
					    <script>
						document.getElementById("ChouseNumber").value = "<?php echo str_replace("\n", '\n', $ChouseNumber );  ?>";
						document.getElementById("ChouseNumber").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'ChouseNumber_error'></div></div>
			    </div>
                        </div>

                        <div class="formColumns">
			    <label>Street Name: </label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='CstreetName' id='CstreetName'  validate="validateStr"   caption="either Street Name or Area/Locality" minlength="2"   maxlength="50" class = "textboxLarge"      title="Enter your Street Name"  value=''  />
			    <?php if(isset($CstreetName) && $CstreetName!=""){ ?>
					    <script>
						document.getElementById("CstreetName").value = "<?php echo str_replace("\n", '\n', $CstreetName );  ?>";
						document.getElementById("CstreetName").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'CstreetName_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    <label>Area/Locality: </label>
			    <div class="fieldBoxLarge">
			    <input type='text' name='Carea' id='Carea' validate="validateStr" caption="either Street Name or Area/Locality" minlength="2"   maxlength="50"  caption="Area/Locality"   class = "textboxLarge"     title="Enter your Area Name"  value=''  />
			    <?php if(isset($Carea) && $Carea!=""){ ?>
					    <script>
						document.getElementById("Carea").value = "<?php echo str_replace("\n", '\n', $Carea );  ?>";
						document.getElementById("Carea").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Carea_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formColumns">
			    <label>Country: </label>
			    <div class="float_L">
			    <select class="selectBoxMedium" name='Ccountry' id='Ccountry'   onmouseover="showTipOnline('Choose the country of your residence.',this);" onmouseout="hidetip();"    required="true"   onchange='getCitiesForCountryOnlineCorrespondence("",false,"",false); setCorrespondentState(this.value); setState(this.value,"Cstate");'><?php foreach($country_list as $countryItem) {
							$countryId = $countryItem["countryID"];
							$countryName = $countryItem["countryName"];
							if($countryId == 1) { continue; }
								if($countryId == $Ccountry) { $selected = "selected"; }
							else { $selected = ""; } ?>
						<option value="<?php echo $countryId; ?>" <?php echo $selected; ?> ><?php echo $countryName; ?></option>
						<?php } ?></select>
			    <div style='display:none'><div class='errorMsg' id= 'Ccountry_error'></div></div>
			    </div>
                        </div>

						<div class="formColumns">
                            <label>City: </label>
			    <div class="float_L">
			    <select name='Ccity' id='Ccity'  class="selectBoxLarge"   onmouseover="showTipOnline('Choose the city in which the house is located. If the city is not on the list, choose the nearest city here and write the name of your city in the text box corresponding to Area/Locality name.',this);" onmouseout="hidetip();"   required="true"  validate="validateSelect"  caption = "City"  onChange = "fillState(this.value,'Cstate');"/></select>
			    <div style='display:none'><div class='errorMsg' id= 'Ccity_error'></div></div>
			    </div>
                        </div>




                    </li>
                    
                    <li>
                        
                        <div class="formColumns">
			    <label>State: </label>
			    <div class="fieldBoxLarge">
			    <input type='text' class="textboxLarge" name='Cstate' id='Cstate'  validate="validateStr"   required="true"   caption="State"   minlength="2"   maxlength="25"     tip="Please type your State. If you are an Indian, please select the city and state will be populated."   value=''  readonly/>
			    <?php if(isset($Cstate) && $Cstate!=""){ ?>
					    <script>
						document.getElementById("Cstate").value = "<?php echo str_replace("\n", '\n', $Cstate);  ?>";
						document.getElementById("Cstate").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Cstate_error'></div></div>
			    </div>
                        </div>
						
						
                        <div class="formColumns">
			    <label>Pincode: </label>
			    <div class="fieldBoxSmall">
			    <input class="textboxSmall" type='text' name='Cpincode' id='Cpincode'  validate="validateInteger"   required="true"   caption="Pincode"   minlength=<?php  if($Ccountry =='2'){echo '6';}else{echo '4';} ?>   maxlength=<?php  if($Ccountry =='2'){echo '6';}else{echo '8';} ?>      title="Enter your Pincode"  value=''  />
			    <?php if(isset($Cpincode) && $Cpincode!=""){ ?>
					    <script>
						document.getElementById("Cpincode").value = "<?php echo str_replace("\n", '\n', $Cpincode );  ?>";
						document.getElementById("Cpincode").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'Cpincode_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
			    <label>Landline phone number: </label>
				
			    <div class="float_L mr8" style="width:80px">
					<input type="text" class="textBoxSmaller" style="width:75px;" name='landlineISDCode' id='landlineISDCode' validate='validateStr' caption='Counrty Code'   minlength="2"   maxlength="6" tip="Type in your country code here" value='' />
					<?php if(isset($landlineISDCode) && $landlineISDCode!=""){ ?>
					    <script>
						document.getElementById("landlineISDCode").value = "<?php echo str_replace("\n", '\n', $landlineISDCode );  ?>";
						document.getElementById("landlineISDCode").style.color = "";
					    </script>
					<?php } ?>
					<div style='display:none'><div class='errorMsg' id= 'landlineISDCode_error'></div></div>
			    </div>

			    <div class="float_L mr8" style="width:80px;">
			    <input class="textboxSmaller" type='text' name='landlineSTDCode' id='landlineSTDCode'  validate="validateInteger"    caption="STD Code"   minlength="2"   maxlength="4"     tip="Type in the STD Code of your city or town here."   value=''  />
			    <?php if(isset($landlineSTDCode) && $landlineSTDCode!=""){ ?>
					    <script>
						document.getElementById("landlineSTDCode").value = "<?php echo str_replace("\n", '\n', $landlineSTDCode );  ?>";
						document.getElementById("landlineSTDCode").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'landlineSTDCode_error'></div></div>
			    </div>

			    <div class="fieldBoxLarge">
			    <input class="textboxLarge"  type='text' name='landlineNumber' id='landlineNumber'  validate="validateInteger"    caption="Landline Number"   minlength="4"   maxlength="10"     tip="Type in your six digit, seven digit, or eight digit landline phone number here."   value=''  />
			    <?php if(isset($landlineNumber) && $landlineNumber!=""){ ?>
					    <script>
						document.getElementById("landlineNumber").value = "<?php echo str_replace("\n", '\n', $landlineNumber );  ?>";
						document.getElementById("landlineNumber").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'landlineNumber_error'></div></div>
			    </div>

                        </div>
                    </li>
                    
                    <li>
                        <div class="formAutoColumns">
			    <label>Mobile Phone Number: </label>
				
				<div class="float_L mr8" style="width:80px">

					<select class="selectBoxMedium" name='mobileISDCode' id='mobileISDCode' onmouseover="showTipOnline('Select your country code.',this);" onmouseout="hidetip();"  onchange="setMobileNumberFieldLength(this.value)" style="width:81px;">

						<?php $mobileISDCode = trim($mobileISDCode, '+');
						foreach($isdCodeData as $isdCodeValues) {
							$isdCode = $isdCodeValues["isdCode"];
							$isdCountryName = $isdCodeValues["shiksha_countryName"].' ('.$isdCode.')';
							$selected = ""; 
							if($isdCode == $mobileISDCode || $mobileISDCode == '') { 
								$selected = "selected";
							}

						?>
						<option value="<?php echo '+'.$isdCode; ?>" <?php echo $selected; ?>><?php echo $isdCountryName; ?></option>
						<?php } ?>
					</select>

			    </div>

				<?php
				$minLengthMobileNumber = 10; $maxLengthMobileNumber = 10;
				if($mobileISDCode != '91') {
					$minLengthMobileNumber = 6; $maxLengthMobileNumber = 20;
				}
				?>
			    <div class="float_L mr8">
			    <input class="textboxLarge"  type='text' name='mobileNumber' id='mobileNumber'  validate="validateMobileInteger"   required="true"   caption="Mobile Number"   minlength="<?php echo $minLengthMobileNumber;?>"   maxlength="<?php echo $maxLengthMobileNumber;?>" tip="Type in your mobile number here."   value=''  />
			    <?php if(isset($mobileNumber) && $mobileNumber!=""){ ?>
					    <script>
						document.getElementById("mobileNumber").value = "<?php echo str_replace("\n", '\n', $mobileNumber );  ?>";
						document.getElementById("mobileNumber").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'mobileNumber_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>
            </div>
            
            <div class="formSection">
            	<div class="spacer10 clearFix"></div>
                <h2>Family Information</h2>
                <div class="spacer15 clearFix"></div>
                <ul>
                    <li>
                        <div class="formSmallColumns">
			    <label>Father's Name: </label>
			    <div class="fieldBoxMed">
			    <input class="textboxMedium" type='text' name='fatherName' id='fatherName'  validate="validateStr"   required="true"   caption="Father's Name"   minlength="2"   maxlength="50"     tip="Type in the full name of your father here."   value=''  />
			    <?php if(isset($fatherName) && $fatherName!=""){ ?>
					    <script>
						document.getElementById("fatherName").value = "<?php echo str_replace("\n", '\n', $fatherName );  ?>";
						document.getElementById("fatherName").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'fatherName_error'></div></div>
			    </div>
                        </div>
                        <div class="formSmallColumns">
			    <label>Occupation: </label>
			    <div class="fieldBoxMed">
			    <input class="textboxMedium" type='text' name='fatherOccupation' required='true' id='fatherOccupation'  validate="validateStr"    caption="Occupation"   minlength="2"   maxlength="50"     tip="Type in the occupation of your father here or nature of his work, such as Engineer or Business Owner. <?php echo $NAText; ?>"   value=''  />
			    <?php if(isset($fatherOccupation) && $fatherOccupation!=""){ ?>
					    <script>
						document.getElementById("fatherOccupation").value = "<?php echo str_replace("\n", '\n', $fatherOccupation );  ?>";
						document.getElementById("fatherOccupation").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'fatherOccupation_error'></div></div>
			    </div>
                        </div>
                        
                        <div class="formSmallColumns">
			    <label>Designation: </label>
			    <div class="fieldBoxMed">
			    <input class="textboxMedium" type='text' name='fatherDesignation' id='fatherDesignation'  validate="validateStr"    caption="Designation"   minlength="2"   maxlength="50"     tip="Type in the designation of your father here or the exact post he holds, such as Executive Engineer in Indian Oil.  <?php echo $NAText; ?>"   value=''  />
			    <?php if(isset($fatherDesignation) && $fatherDesignation!=""){ ?>
					    <script>
						document.getElementById("fatherDesignation").value = "<?php echo str_replace("\n", '\n', $fatherDesignation );  ?>";
						document.getElementById("fatherDesignation").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'fatherDesignation_error'></div></div>
			    </div>
                        </div>
                    </li>
                    
                    <li>
                        <div class="formSmallColumns">
			    <label>Mother's Name: </label>
			    <div class="fieldBoxMed">
			    <input class="textboxMedium" type='text' name='MotherName' id='MotherName'  validate="validateStr"   required="true"   caption="Mother's Name"   minlength="2"   maxlength="50"     tip="Type in the full name of your mother here."   value=''  />
			    <?php if(isset($MotherName) && $MotherName!=""){ ?>
					    <script>
						document.getElementById("MotherName").value = "<?php echo str_replace("\n", '\n', $MotherName );  ?>";
						document.getElementById("MotherName").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'MotherName_error'></div></div>
			    </div>
                        </div>
                        <div class="formSmallColumns">
			    <label>Occupation: </label>
			    <div class="fieldBoxMed">
			    <input class="textboxMedium" type='text' name='MotherOccupation' required='true' id='MotherOccupation'  validate="validateStr"    caption="Occupation"   minlength="2"   maxlength="50"     tip="Type in the occupation of your mother here or nature of her work, such as Teacher or Homemaker.  <?php echo $NAText; ?>"   value=''  />
			    <?php if(isset($MotherOccupation) && $MotherOccupation!=""){ ?>
					    <script>
						document.getElementById("MotherOccupation").value = "<?php echo str_replace("\n", '\n', $MotherOccupation );  ?>";
						document.getElementById("MotherOccupation").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'MotherOccupation_error'></div></div>
			    </div>
                        </div>
                        
                        <div class="formSmallColumns">
			    <label>Designation: </label>
			    <div class="fieldBoxMed">
			    <input class="textboxMedium" type='text' name='MotherDesignation' id='MotherDesignation'  validate="validateStr"    caption="Designation"   minlength="2"   maxlength="50"     tip="Type in the designation of your mother here or the exact post she holds, such as School Teacher in Kendriya Vidyalaya.  <?php echo $NAText; ?>"   value=''  />
			    <?php if(isset($MotherDesignation) && $MotherDesignation!=""){ ?>
					    <script>
						document.getElementById("MotherDesignation").value = "<?php echo str_replace("\n", '\n', $MotherDesignation );  ?>";
						document.getElementById("MotherDesignation").style.color = "";
					    </script>
					  <?php } ?>
			    <div style='display:none'><div class='errorMsg' id= 'MotherDesignation_error'></div></div>
			    </div>
                        </div>
                    </li>
                </ul>
            </div>
            
     	</div>
        <div class="clearFix"></div>
    <script>
	function fillState(cityId,fieldName){
	      var url = "/Online/OnlineForms/getStateForCity";
	      var data = "cityId="+cityId;
	      new Ajax.Request (url,{ method:'post', parameters: (data), onSuccess:function (request) {
			if(trim(request.responseText) != ''){
				$(fieldName).value = request.responseText;
				$(fieldName).value = $(fieldName).value.toUpperCase();
				$(fieldName+'_error').innerHTML = '';
				$(fieldName+'_error').parentNode.style.display = 'none';
			}
		      }
		      });
	}

	getCitiesForCountryOnline("",false,"",false);
	getCitiesForCountryOnlineCorrespondence("",false,"",false);
    </script>

    <script>
    function setTheSelectedCity(){
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
	    // In case the city is set but the state is not, then fill the state value
    	if($('state').value==''){
	          fillState($('city').value,'state');
	    }
    }
    </script>

    <?php if(isset($city) && $city!=""){ ?>    
    <script>setTimeout(setTheSelectedCity,2000); </script>
    <?php } ?>

    <script>
    function setTheSelectedCityC(){
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
	if($('Cstate').value==''){
	      fillState($('Ccity').value,'Cstate');
	}
    }

    </script>
  
    <?php if(isset($Ccity) && $Ccity!=""){ ?>
    <script>setTimeout(setTheSelectedCityC,2000); </script>
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
  }

  function copyAddressFields(){
	if($('copyAddress').checked == true){
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
		      
		      setCorrespondentState(countrySelected);
			  document.getElementById('Cstate').value = document.getElementById('state').value;
			  
			  var sel = document.getElementById('city');
			  var citySelected = sel.options[sel.selectedIndex].value;
		      getCitiesForCountryOnlineCorrespondence(citySelected,false,"",false);
			  //setISDCodes(countrySelected);
		     
		      /* 	var sel = document.getElementById('city');
				var citySelected = sel.options[sel.selectedIndex].value;
				var selObj = document.getElementById('Ccity'); 
				var A= selObj.options, L= A.length;
				while(L){
				  if (A[--L].value== citySelected){
				      selObj.selectedIndex= L;
				      L= 0;
				  }
				} */

		      resetAddressErrors();
	      }
	}
  }

  function resetAddressErrors(){
	$('ChouseNumber_error').innerHTML = '';
	$('CstreetName_error').innerHTML = '';
	$('Carea_error').innerHTML = '';
	$('Cpincode_error').innerHTML = '';
	$('Cstate_error').innerHTML = '';
	$('Ccity_error').innerHTML = '';
	$('Ccountry_error').innerHTML = '';

	$('ChouseNumber_error').parentNode.style.display = 'none';
	$('CstreetName_error').parentNode.style.display = 'none';
	$('Carea_error').parentNode.style.display = 'none';
	$('Cpincode_error').parentNode.style.display = 'none';
	$('Cstate_error').parentNode.style.display = 'none';
	$('Ccity_error').parentNode.style.display = 'none';
	$('Ccountry_error').parentNode.style.display = 'none';
  }

  function setState(countryValue,stateField){
	if(countryValue==2){
	    $(stateField).readOnly = true;
	}
	else{
	    $(stateField).readOnly = false;
	}
	$(stateField).value = '';
  }

  function setCorrespondentState(countryValue){
	if(countryValue=='2'){
	    $('Cstate').readOnly = true;
	}
	else
	    $('Cstate').readOnly = false;
	$('Cstate').value = '';
  }
  
  function setISDCodes(countryValue){
	if(countryValue=='2'){
	    $('landlineISDCode').value = '+91';
		$('mobileISDCode').value = '+91';
		$('mobileISDCode_error').style.display = 'none';
	}
	else {
	    $('landlineISDCode').value = '';
		$('mobileISDCode').value = '';
	}
  }
  
	function setMobileNumberFieldLength(isdCodeValue) {
		var isdCode = isdCodeValue.split('+');
  		if(isdCode[1] != '91') {
  			$('mobileNumber').setAttribute('minlength','6');	
     		$('mobileNumber').setAttribute('maxlength','20');
     	}else{
     		$('mobileNumber').setAttribute('minlength','10');	
     		$('mobileNumber').setAttribute('maxlength','10');
  	 	}
  	}

  if($('country').value != '2')
	$('state').readOnly = false;
  if($('Ccountry').value != '2')
	$('Cstate').readOnly = false;

  </script>
